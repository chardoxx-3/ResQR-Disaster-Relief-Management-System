<?php
// app/Models/InventoryBatchModel.php

namespace App\Models;

use CodeIgniter\Model;

class InventoryBatchModel extends Model
{
    protected $table            = 'inventory_batches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
protected $allowedFields    = [
    'inventory_id',      
    'batch_number',      
    'source_type',       
    'source_details',    
    'quantity_initial',  
    'quantity_remaining', 
    'unit_type',         
    'date_received',
    'expiry_date',       
    'storage_location',  
    'storage_unit',      // Storage container type
    'units_per_storage', // Items per storage unit
    'storage_quantity',  // ← ADD THIS NEW FIELD - number of storage units
    'assigned_to',       
    'assignment_type',   
    'barangay',
    'qr_code_token',     
    'qr_code_data',      
    'status',            
    'notes'
];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    const STATUS_IN_WAREHOUSE = 'in_warehouse';
const STATUS_IN_TRANSIT = 'in_transit';
const STATUS_RECEIVED = 'received';
const STATUS_DEPLETED = 'depleted';
const STATUS_EXPIRED = 'expired';

    /**
     * Generate unique QR code token for batch
     */
    public function generateQRToken($batchId, $inventoryId, $batchNumber)
    {
        $uniqueString = $inventoryId . '_' . $batchId . '_' . $batchNumber . '_' . time() . '_' . bin2hex(random_bytes(8));
        return hash('sha256', $uniqueString);
    }

/**
 * Generate QR code data array for embedding
 */
public function generateQRData($batchData)
{
    // Return just the token as plain text, not JSON
    // This makes it compatible with the scanner
    return $batchData['qr_code_token'];
}

    /**
     * Get item name from inventory table
     */
    private function getItemName($inventoryId)
    {
        $db = \Config\Database::connect();
        $query = $db->table('inventory')
                    ->select('item_name')
                    ->where('id', $inventoryId)
                    ->get()
                    ->getRow();
        return $query ? $query->item_name : null;
    }

    /**
     * Get batch by QR token
     */
    public function getByQRToken($token)
    {
        return $this->where('qr_code_token', $token)
                    ->where('status !=', 'depleted')
                    ->first();
    }

// In InventoryBatchModel.php - around line 100-120

// In InventoryBatchModel.php - REPLACE the deductFromBatch method with this:

public function deductFromBatch($batchId, $quantity)
{
    $batch = $this->find($batchId);
    if (!$batch) return false;

    $newRemaining = $batch['quantity_remaining'] - $quantity;
    
    // Ensure quantity doesn't go below zero
    if ($newRemaining < 0) {
        $newRemaining = 0;
    }
    
    // Prepare update data
    $updateData = [
        'quantity_remaining' => $newRemaining
    ];
    
    // Only change status to depleted if completely out of stock
    if ($newRemaining <= 0) {
        $updateData['status'] = 'depleted';
    }
    // Otherwise, keep the original status (in_warehouse, in_transit, or received)
    // Don't change it to 'active'
    
    return $this->update($batchId, $updateData);
}

    /**
     * Get batches by inventory item
     */
    public function getBatchesByInventory($inventoryId)
    {
        return $this->where('inventory_id', $inventoryId)
                    ->orderBy('date_received', 'DESC')
                    ->findAll();
    }

    /**
     * Get batches by assignment (barangay or beneficiary)
     */
    public function getBatchesByAssignment($assignmentType, $assignedTo)
    {
        return $this->where('assignment_type', $assignmentType)
                    ->where('assigned_to', $assignedTo)
                    ->where('quantity_remaining >', 0)
                    ->orderBy('date_received', 'DESC')
                    ->findAll();
    }

    /**
     * Get low stock alerts (batches with low quantity)
     */
    public function getLowStockAlerts($threshold = 10)
    {
        return $this->where('quantity_remaining <', $threshold)
                    ->where('quantity_remaining >', 0)
                    ->where('status !=', 'depleted')
                    ->findAll();
    }

    /**
     * Get summary statistics for dashboard
     */
    public function getBatchSummary()
    {
        $db = \Config\Database::connect();
        
        return [
            'total_batches' => $this->countAll(),
            'active_batches' => $this->where('status', 'active')->countAllResults(),
            'total_quantity' => $db->table('inventory_batches')
                                  ->selectSum('quantity_remaining')
                                  ->get()
                                  ->getRow()
                                  ->quantity_remaining ?? 0,
            'by_source' => $db->table('inventory_batches')
                             ->select('source_type, SUM(quantity_remaining) as total')
                             ->groupBy('source_type')
                             ->get()
                             ->getResultArray()
        ];
    }

    /**
 * Mark batch as "In Transit" when dispatched to barangay
 * 
 * @param int $batchId The batch ID
 * @param string $destinationBarangay The barangay where goods are being sent
 * @param int $dispatchedBy User ID of the staff dispatching
 * @return bool Success/failure
 */
public function markAsInTransit($batchId, $destinationBarangay, $dispatchedBy)
{
    $db = \Config\Database::connect();
    
    // Check if batch exists and is in warehouse
    $batch = $this->find($batchId);
    if (!$batch || $batch['status'] !== self::STATUS_IN_WAREHOUSE) {
        return false;
    }
    
    // Update batch status
    $result = $this->update($batchId, [
        'status' => self::STATUS_IN_TRANSIT,
        'assigned_to' => $destinationBarangay,
        'assignment_type' => 'barangay',
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        // Log the transit event
        $db->table('batch_status_logs')->insert([
            'batch_id' => $batchId,
            'status' => self::STATUS_IN_TRANSIT,
            'location' => $destinationBarangay,
            'changed_by' => $dispatchedBy,
            'remarks' => 'Dispatched to barangay',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    return $result;
}

/**
 * Mark batch as "Received" when barangay confirms receipt
 * 
 * @param int $batchId The batch ID
 * @param int $receivedBy User ID of the barangay staff receiving
 * @param string $barangay The barangay name/location
 * @return bool Success/failure
 */
public function markAsReceived($batchId, $receivedBy, $barangay)
{
    $db = \Config\Database::connect();
    
    // Check if batch exists and is in transit
    $batch = $this->find($batchId);
    if (!$batch || $batch['status'] !== self::STATUS_IN_TRANSIT) {
        return false;
    }
    
    // Verify that the barangay matches
    if ($batch['assigned_to'] !== $barangay) {
        return false;
    }
    
    // Update batch status AND set the barangay field
    $result = $this->update($batchId, [
        'status' => self::STATUS_RECEIVED,
        'barangay' => $barangay,  // <-- ADD THIS LINE to set the barangay field
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        // Log the receipt event
        $db->table('batch_status_logs')->insert([
            'batch_id' => $batchId,
            'status' => self::STATUS_RECEIVED,
            'location' => $barangay,
            'changed_by' => $receivedBy,
            'remarks' => 'Received by barangay',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    return $result;
}

/**
 * Get batch tracking history with timestamps
 * 
 * @param int $batchId The batch ID
 * @return array Status history with user details
 */
public function getBatchTrackingHistory($batchId)
{
    $db = \Config\Database::connect();
    
    return $db->table('batch_status_logs bsl')
        ->select('bsl.*, u.username as changed_by_name')
        ->join('users u', 'u.id = bsl.changed_by', 'left')
        ->where('bsl.batch_id', $batchId)
        ->orderBy('bsl.created_at', 'DESC')
        ->get()
        ->getResultArray();
}

/**
 * Get batches by status for dashboard
 * 
 * @param string $status The status to filter by
 * @return array Batches with item details
 */
public function getBatchesByStatus($status)
{
    $db = \Config\Database::connect();
    
    return $db->table('inventory_batches ib')
        ->select('ib.*, i.item_name, i.unit_type')
        ->join('inventory i', 'i.id = ib.inventory_id')
        ->where('ib.status', $status)
        ->orderBy('ib.updated_at', 'DESC')
        ->get()
        ->getResultArray();
}
}