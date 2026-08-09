<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table            = 'inventory';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
protected $allowedFields    = [
    'item_name', 
    'quantity', 
    'unit_type', 
    'allocation',
    'inventory_type',
    'distribution_type',
    'original_quantity'   // ADD THIS
];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Deduct stock based on a successful scan
     */
    public function deductStock()
    {
        $items = $this->findAll();
        foreach ($items as $item) {
            $newQty = $item['quantity'] - $item['allocation'];
            // Ensure stock doesn't go below zero
            $newQty = ($newQty < 0) ? 0 : $newQty;
            
            $this->update($item['id'], ['quantity' => $newQty]);
        }
    }
/**
 * Add a Cooked Foods item. Cooked foods have no batch/source/assignment
 * tracking - just a name, distribution type, and current stock.
 *
 * @param array $data Expects: item_name, distribution_type, quantity
 * @return int|false Inserted inventory ID, or false on failure
 */
public function addCookedFood($data)
{
    $insertData = [
        'item_name'         => $data['item_name'],
        'inventory_type'    => 'cooked_food',
        'unit_type'         => 'Serving',
        'allocation'        => 1,
        'distribution_type' => $data['distribution_type'],
        'quantity'          => $data['quantity'],
        'original_quantity' => $data['quantity']   // ADD THIS — remembers the starting stock
    ];

    $this->insert($insertData);
    return $this->getInsertID();
}

public function addWithBatch($data, $batchData)
{
    $db = \Config\Database::connect();
    $db->transStart();

    // Insert main inventory item if it doesn't exist
    $inventoryId = null;
    if (isset($data['id']) && !empty($data['id'])) {
        $inventoryId = $data['id'];
    } else {
        // Check if item already exists with same name
        $existing = $this->where('item_name', $data['item_name'])->first();
        if ($existing) {
            $inventoryId = $existing['id'];
        } else {
            $this->insert($data);
            $inventoryId = $this->getInsertID();
        }
    }

    // Create batch record
    $batchModel = new InventoryBatchModel();
    $batchNumber = $this->generateBatchNumber($inventoryId);
    
    // Calculate total individual items based on storage units and units per storage
    $storageUnits = $batchData['quantity']; // Number of boxes/crates/etc.
    $unitsPerStorage = $batchData['units_per_storage'] ?? 1; // Items per box
    $totalIndividualItems = $storageUnits * $unitsPerStorage; // Total pieces
    
    $batchInsertData = [
        'inventory_id' => $inventoryId,
        'batch_number' => $batchNumber,
        'source_type' => $batchData['source_type'],
        'source_details' => $batchData['source_details'] ?? null,
        'quantity_initial' => $totalIndividualItems, // Store total individual items
        'quantity_remaining' => $totalIndividualItems, // Store total individual items
        'unit_type' => $data['unit_type'],
        'date_received' => $batchData['date_received'] ?? date('Y-m-d'),
        'expiry_date' => $batchData['expiry_date'] ?? null,
        'storage_location' => $batchData['storage_location'] ?? null,
        'storage_unit' => $batchData['storage_unit'] ?? null,
        'units_per_storage' => $unitsPerStorage,
        'storage_quantity' => $storageUnits, // Add this field to track number of storage units
        'assigned_to' => $batchData['assigned_to'] ?? null,
        'barangay' => null,
        'assignment_type' => $batchData['assignment_type'] ?? null,
        'status' => \App\Models\InventoryBatchModel::STATUS_IN_WAREHOUSE,
        'notes' => $batchData['notes'] ?? null
    ];

    // Insert the batch first to get an ID
    $batchModel->insert($batchInsertData);
    $batchId = $batchModel->getInsertID();

    // NOW generate the token using the batch ID
    $qrToken = $batchModel->generateQRToken($batchId, $inventoryId, $batchNumber);

    // For the QR data, we just want the token (plain text)
    $qrData = $qrToken;

    // Update the batch with the token
    $batchModel->update($batchId, [
        'qr_code_token' => $qrToken,
        'qr_code_data' => $qrData
    ]);

    // Update total inventory quantity (sum of all individual items from all batches)
    $totalQuantity = $batchModel->where('inventory_id', $inventoryId)
                                ->selectSum('quantity_remaining')
                                ->get()
                                ->getRow()
                                ->quantity_remaining ?? 0;
    
    $this->update($inventoryId, ['quantity' => $totalQuantity]);

    $db->transComplete();

    return [
        'inventory_id' => $inventoryId,
        'batch_id' => $batchId,
        'qr_token' => $qrToken,
        'batch_number' => $batchNumber
    ];
}
// In InventoryModel.php, replace the generateBatchNumber method:

private function generateBatchNumber($inventoryId)
{
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $timestamp = time();
    
    // Get count of batches today for this item
    $db = \Config\Database::connect();
    $query = $db->table('inventory_batches')
                ->select('COUNT(*) as count')
                ->where('inventory_id', $inventoryId)
                ->where('DATE(created_at)', date('Y-m-d'))
                ->get()
                ->getRow();
    
    $sequence = ($query->count ?? 0) + 1;
    
    // Format: BATCH-YYYYMMDD-[sequence]-[last 4 digits of timestamp]
    return sprintf('BATCH-%s%s%s-%03d-%04d', $year, $month, $day, $sequence, substr($timestamp, -4));
}

    /**
     * NEW METHOD: Process scan of QR code for distribution
     */
    public function processBatchQRScan($qrToken, $residentId, $distributorId, $familyMemberId = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Get batch by QR token
        $batchModel = new InventoryBatchModel();
        $batch = $batchModel->getByQRToken($qrToken);

        if (!$batch) {
            return ['success' => false, 'message' => 'Invalid or depleted batch QR code'];
        }

        // Check if batch is assigned to specific location and matches resident's location
        if ($batch['assigned_to'] && $batch['assignment_type']) {
            // Get resident's barangay
            $residentModel = new \App\Models\ResidentModel();
            $resident = $residentModel->find($residentId);
            
            if ($batch['assignment_type'] == 'barangay' && $batch['assigned_to'] != $resident['barangay']) {
                return ['success' => false, 'message' => 'This batch is assigned to a different barangay'];
            }
        }

        // Get allocation amount from inventory
        $inventory = $this->find($batch['inventory_id']);
        $allocation = $inventory['allocation'] ?? 1;

        // Check if batch has enough quantity
        if ($batch['quantity_remaining'] < $allocation) {
            return ['success' => false, 'message' => 'Insufficient stock in this batch'];
        }

        // Log distribution
        $distributionModel = new BatchDistributionModel();
        $distributionModel->logDistribution([
            'batch_id' => $batch['id'],
            'resident_id' => $residentId,
            'family_member_id' => $familyMemberId,
            'distributor_id' => $distributorId,
            'quantity_distributed' => $allocation,
            'distribution_date' => date('Y-m-d H:i:s'),
            'qr_code_scanned' => $qrToken,
            'status' => 'completed'
        ]);

        // Update main inventory total
        $totalRemaining = $batchModel->where('inventory_id', $batch['inventory_id'])
                                    ->selectSum('quantity_remaining')
                                    ->get()
                                    ->getRow()
                                    ->quantity_remaining ?? 0;
        
        $this->update($batch['inventory_id'], ['quantity' => $totalRemaining]);

        $db->transComplete();

        return [
            'success' => true,
            'message' => 'Distribution successful',
            'data' => [
                'item_name' => $inventory['item_name'],
                'quantity' => $allocation,
                'unit_type' => $batch['unit_type'],
                'batch_number' => $batch['batch_number'],
                'remaining' => $totalRemaining
            ]
        ];
    }

    /**
     * NEW METHOD: Get detailed batch information for reporting
     */
    public function getBatchReport($batchId = null)
    {
        $batchModel = new InventoryBatchModel();
        $distributionModel = new BatchDistributionModel();

        if ($batchId) {
            $batch = $batchModel->find($batchId);
            $inventory = $this->find($batch['inventory_id']);
            $distributions = $distributionModel->getSummaryByBatch($batchId);
            
            return [
                'batch' => $batch,
                'item' => $inventory,
                'distributions' => $distributions,
                'history' => $distributionModel->getDistributionHistory(null, null)
            ];
        }

        // Return summary of all batches
        $batches = $batchModel->findAll();
        $result = [];
        
        foreach ($batches as $batch) {
            $inventory = $this->find($batch['inventory_id']);
            $distributions = $distributionModel->getSummaryByBatch($batch['id']);
            
            $result[] = [
                'batch' => $batch,
                'item' => $inventory,
                'distributed' => $distributions['total_distributed'] ?? 0,
                'transactions' => $distributions['total_transactions'] ?? 0
            ];
        }
        
        return $result;
    }

    /**
 * Get the single active cooked food item (SMK distribution source).
 */
public function getActiveCookedFood()
{
    return $this->where('inventory_type', 'cooked_food')
                ->orderBy('id', 'DESC')
                ->first();
}

/**
 * Deduct cooked food stock after a successful SMK claim.
 */
public function deductCookedFoodStock($inventoryId, $amount)
{
    $item = $this->find($inventoryId);
    if (!$item) return false;

    $newQty = $item['quantity'] - $amount;

    if ($newQty <= 0) {
        // Reset back to the original stock level instead of sitting at 0.
        // Falls back to leaving it at 0 if no original_quantity was ever recorded
        // (e.g. legacy items added before this feature existed).
        $newQty = !empty($item['original_quantity']) ? $item['original_quantity'] : 0;
    }

    return $this->update($inventoryId, ['quantity' => $newQty]);
}
}