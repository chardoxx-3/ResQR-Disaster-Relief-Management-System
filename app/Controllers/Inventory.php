<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\InventoryBatchModel;
use App\Models\BatchDistributionModel;

class Inventory extends BaseController
{
public function index()
{
    $model = new InventoryModel();
    $batchModel = new InventoryBatchModel();
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay'); // Make sure barangay is stored in session
    
    // Get all inventory items
    $items = $model->findAll();
    
    // Enhance items with batch information
    foreach ($items as &$item) {
        // Cooked foods have no batch tracking - their quantity is stored directly
        if (($item['inventory_type'] ?? 'canned_goods') === 'cooked_food') {
            $item['batches'] = [];
            $item['batch_count'] = 0;
            continue;
        }

        // For barangay users, only get batches where the batch's barangay matches user's barangay
        if ($userRole === 'barangay') {
            $batches = $batchModel->where('inventory_id', $item['id'])
                                 ->where('barangay', $userBarangay) // Filter by barangay in batches table
                                 ->orderBy('date_received', 'DESC')
                                 ->findAll();
        } else {
            // Admin and distributors see all batches
            $batches = $batchModel->where('inventory_id', $item['id'])
                                 ->orderBy('date_received', 'DESC')
                                 ->findAll();
        }
        
        $item['batches'] = $batches;
        $item['batch_count'] = count($batches);
        
        // Calculate total quantity from filtered batches
        $totalQuantity = 0;
        foreach ($batches as $batch) {
            $totalQuantity += $batch['quantity_remaining'];
        }
        $item['quantity'] = $totalQuantity;
    }
    
    // For barangay users, filter out canned-goods items that have no batches in their barangay
    // (cooked foods have no batch concept, so they're always kept)
    if ($userRole === 'barangay') {
        $items = array_filter($items, function($item) {
            if (($item['inventory_type'] ?? 'canned_goods') === 'cooked_food') {
                return true;
            }
            return $item['batch_count'] > 0; // Only show items that have batches in this barangay
        });
        // Re-index array
        $items = array_values($items);
    }
    
    // Split into the two inventory sections for the view
    $cookedItems = array_values(array_filter($items, function($item) {
        return ($item['inventory_type'] ?? 'canned_goods') === 'cooked_food';
    }));
    $cannedItems = array_values(array_filter($items, function($item) {
        return ($item['inventory_type'] ?? 'canned_goods') === 'canned_goods';
    }));
    
    // Get batch summaries - filtered for barangay users
    $batchSummary = $batchModel->getBatchSummary();
    
    // For barangay users, modify the batch summary
    if ($userRole === 'barangay') {
        // Recalculate batch summary for barangay only based on batches barangay field
        $db = \Config\Database::connect();
        
        $totalBatches = $batchModel->where('barangay', $userBarangay)->countAllResults();
        
        $activeBatches = $batchModel->where('barangay', $userBarangay)
                                   ->where('status', 'active')
                                   ->countAllResults();
        
        $totalQuantity = $batchModel->selectSum('quantity_remaining')
                                   ->where('barangay', $userBarangay)
                                   ->get()
                                   ->getRow()
                                   ->quantity_remaining ?? 0;
        
        $batchSummary = [
            'total_batches' => $totalBatches,
            'active_batches' => $activeBatches,
            'total_quantity' => $totalQuantity,
            'by_source' => []
        ];
    }
    
    // Get low stock batches - filtered for barangay users
    if ($userRole === 'barangay') {
        $lowStockBatches = $batchModel->where('quantity_remaining <', 10)
                                     ->where('quantity_remaining >', 0)
                                     ->where('status !=', 'depleted')
                                     ->where('barangay', $userBarangay)
                                     ->findAll();
    } else {
        $lowStockBatches = $batchModel->where('quantity_remaining <', 10)
                                     ->where('quantity_remaining >', 0)
                                     ->where('status !=', 'depleted')
                                     ->findAll();
    }
    
    // Get source summary - filtered for barangay users
    $db = \Config\Database::connect();
    $sourceQuery = $db->table('inventory_batches')
                     ->select('source_type, SUM(quantity_remaining) as total')
                     ->groupBy('source_type');
    
    if ($userRole === 'barangay') {
        $sourceQuery->where('barangay', $userBarangay);
    }
    
    $sourceSummary = $sourceQuery->get()->getResultArray();
    
    $data = [
        'items' => $items,
        'cooked_items' => $cookedItems,
        'canned_items' => $cannedItems,
        'batch_summary' => $batchSummary,
        'source_summary' => $sourceSummary,
        'low_stock_count' => count($lowStockBatches)
    ];
    
    return view('inventory/index', $data);
}
    
/**
 * Display the form for adding a new item with batch tracking
 */
public function add_item()
{
    // Check if user can modify inventory
    if (!$this->canModifyInventory()) {
        return redirect()->to('/inventory')->with('error', 'You do not have permission to add inventory items');
    }
    
    // Get all distinct barangays from users table
    $db = \Config\Database::connect();
    
    // CORRECTED: Use distinct() method instead of 'DISTINCT' in select()
    $usersQuery = $db->table('users')
                    ->select('barangay')
                    ->distinct()  // Use distinct() method
                    ->where('barangay IS NOT NULL')
                    ->where('barangay !=', '')
                    ->orderBy('barangay', 'ASC')
                    ->get();
    
    $barangays = [];
    foreach ($usersQuery->getResultArray() as $row) {
        if (!empty($row['barangay'])) {
            $barangays[$row['barangay']] = $row['barangay'];
        }
    }
    
    // Also get barangays from residents table as backup
    $residentsQuery = $db->table('residents')
                        ->select('barangay')
                        ->distinct()  // Use distinct() method
                        ->where('barangay IS NOT NULL')
                        ->where('barangay !=', '')
                        ->orderBy('barangay', 'ASC')
                        ->get();
    
    foreach ($residentsQuery->getResultArray() as $row) {
        if (!empty($row['barangay'])) {
            $barangays[$row['barangay']] = $row['barangay'];
        }
    }
    
    // Sort alphabetically
    asort($barangays);
    
    return view('inventory/add_item', ['barangays' => $barangays]);
}

    
/**
 * Display the form to update stock for a specific item
 */
public function update_stock($id)
{
    // Check if user can modify inventory
    if (!$this->canModifyInventory()) {
        return redirect()->to('/inventory')->with('error', 'You do not have permission to update stock');
    }
    
    $model = new InventoryModel();
    $batchModel = new InventoryBatchModel();
    
    $item = $model->find($id);
    
    if (!$item) {
        return redirect()->to('/inventory')->with('error', 'Item not found');
    }
    
    // Get recent batches for this item
    $recentBatches = $batchModel->where('inventory_id', $id)
                               ->orderBy('date_received', 'DESC')
                               ->limit(5)
                               ->findAll();
    
    // Get all distinct barangays from users table
    $db = \Config\Database::connect();
    
    // CORRECTED: Use distinct() method instead of 'DISTINCT' in select()
    $usersQuery = $db->table('users')
                    ->select('barangay')
                    ->distinct()  // Use distinct() method
                    ->where('barangay IS NOT NULL')
                    ->where('barangay !=', '')
                    ->orderBy('barangay', 'ASC')
                    ->get();
    
    $barangays = [];
    foreach ($usersQuery->getResultArray() as $row) {
        if (!empty($row['barangay'])) {
            $barangays[$row['barangay']] = $row['barangay'];
        }
    }
    
    // Also get barangays from residents table as backup
    $residentsQuery = $db->table('residents')
                        ->select('barangay')
                        ->distinct()  // Use distinct() method
                        ->where('barangay IS NOT NULL')
                        ->where('barangay !=', '')
                        ->orderBy('barangay', 'ASC')
                        ->get();
    
    foreach ($residentsQuery->getResultArray() as $row) {
        if (!empty($row['barangay'])) {
            $barangays[$row['barangay']] = $row['barangay'];
        }
    }
    
    // Sort alphabetically
    asort($barangays);
    
    return view('inventory/update_stock', [
        'item' => $item,
        'recent_batches' => $recentBatches,
        'barangays' => $barangays
    ]);
}
    
public function saveItemWithBatch()
{
    // Check if user can modify inventory
    if (!$this->canModifyInventory()) {
        return redirect()->to('/inventory')->with('error', 'You do not have permission to add inventory items');
    }
    
    $model = new InventoryModel();
    
    $itemData = [
        'item_name' => $this->request->getPost('item_name'),
        'unit_type' => $this->request->getPost('unit_type'),
        'allocation' => $this->request->getPost('allocation'),
        'inventory_type' => 'canned_goods'
    ];

    // Check if this is for an existing item
    $existingItemId = $this->request->getPost('existing_item_id');
    if ($existingItemId) {
        $itemData['id'] = $existingItemId;
    }

    // Get the assignment type and value
    $assignmentType = $this->request->getPost('assignment_type');
    $assignedTo = '';
    
    if ($assignmentType === 'barangay') {
        $assignedTo = $this->request->getPost('barangay_name');
    } elseif ($assignmentType === 'beneficiary') {
        $assignedTo = $this->request->getPost('assigned_to');
    }

    // Handle expiry date - if empty, set to 1 year from date received
    $expiryDate = $this->request->getPost('expiry_date');
    if (empty($expiryDate)) {
        $dateReceived = $this->request->getPost('date_received') ?: date('Y-m-d');
        $expiryDate = date('Y-m-d', strtotime($dateReceived . ' +1 year'));
    }

    // Batch data
    $batchData = [
        'source_type' => $this->request->getPost('source_type'),
        'source_details' => $this->request->getPost('source_details'),
        'quantity' => $this->request->getPost('quantity'),
        'date_received' => $this->request->getPost('date_received') ?: date('Y-m-d'),
        'expiry_date' => $expiryDate, // Use the processed expiry date
        'storage_location' => $this->request->getPost('storage_location'),
        'storage_unit' => $this->request->getPost('storage_unit'),
        'units_per_storage' => $this->request->getPost('units_per_storage'),
        'assigned_to' => $assignedTo,
        'assignment_type' => $assignmentType,
        'barangay' => ($assignmentType === 'barangay') ? $assignedTo : $this->request->getPost('barangay'),
        'notes' => $this->request->getPost('notes')
    ];

    $result = $model->addWithBatch($itemData, $batchData);

    if ($result) {
        $message = $existingItemId ? 'Stock updated with new batch' : 'Item added with batch tracking';
        return redirect()->to('/inventory/batches/' . $result['batch_id'])
                        ->with('success', $message)
                        ->with('qr_token', $result['qr_token']);
    }

    return redirect()->back()->with('error', 'Failed to add item/batch');
}

/**
 * Save a new Cooked Foods item (Basic Information only - no batch/source/assignment)
 */
public function saveCookedFood()
{
    // Check if user can modify inventory
    if (!$this->canModifyInventory()) {
        return redirect()->to('/inventory')->with('error', 'You do not have permission to add inventory items');
    }

    $model = new InventoryModel();

    $data = [
        'item_name'         => $this->request->getPost('item_name'),
        'distribution_type' => $this->request->getPost('distribution_type'),
        'quantity'          => $this->request->getPost('quantity')
    ];

    $id = $model->addCookedFood($data);

    if ($id) {
        return redirect()->to('/inventory')->with('success', 'Cooked food item added successfully');
    }

    return redirect()->back()->with('error', 'Failed to add cooked food item');
}
    
/**
 * View a specific batch
 */
public function viewBatch($batchId)
{
    $batchModel = new InventoryBatchModel();
    $inventoryModel = new InventoryModel();
    $distributionModel = new BatchDistributionModel();
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    $batch = $batchModel->find($batchId);
    if (!$batch) {
        return redirect()->to('/inventory')->with('error', 'Batch not found');
    }

    // Check if barangay user has access to this batch
    if ($userRole === 'barangay') {
        if ($batch['assigned_to'] !== $userBarangay || $batch['assignment_type'] !== 'barangay') {
            return redirect()->to('/inventory')->with('error', 'You do not have access to this batch');
        }
    }

    $inventory = $inventoryModel->find($batch['inventory_id']);
    
    // Get distribution history for this batch
    $distributions = $distributionModel->where('batch_id', $batchId)
                                      ->orderBy('distribution_date', 'DESC')
                                      ->limit(10)
                                      ->findAll();
    
    return view('inventory/view_batch', [
        'batch' => $batch,
        'item' => $inventory,
        'distributions' => $distributions
    ]);
}

    /**
     * Process the stock update form submission (legacy method - kept for backward compatibility)
     */
    public function addStock()
    {
        // Redirect to the new batch-based update form
        $id = $this->request->getPost('id');
        return redirect()->to('/inventory/update_stock/' . $id);
    }

/**
 * Display all batches for a specific inventory item
 * 
 * @param int $itemId The inventory item ID
 */
public function batches($itemId)
{
    $model = new InventoryModel();
    $batchModel = new InventoryBatchModel();
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Get the inventory item
    $item = $model->find($itemId);
    
    if (!$item) {
        return redirect()->to('/inventory')->with('error', 'Item not found');
    }
    
    // Get all batches for this item - filtered by barangay if user is barangay role
    $batchQuery = $batchModel->where('inventory_id', $itemId);
    
    if ($userRole === 'barangay') {
        $batchQuery->where('assigned_to', $userBarangay)
                   ->where('assignment_type', 'barangay');
    }
    
    $batches = $batchQuery->orderBy('date_received', 'DESC')->findAll();
    
    // If barangay user and no batches found, redirect with message
    if ($userRole === 'barangay' && empty($batches)) {
        return redirect()->to('/inventory')->with('error', 'No batches assigned to your barangay for this item');
    }
    
    // Get distribution summary for each batch
    $distributionModel = new BatchDistributionModel();
    foreach ($batches as &$batch) {
        $summary = $distributionModel->getSummaryByBatch($batch['id']);
        $batch['total_distributed'] = $summary['total_distributed'] ?? 0;
        $batch['total_transactions'] = $summary['total_transactions'] ?? 0;
    }
    
    $data = [
        'item' => $item,
        'batches' => $batches
    ];
    
    return view('inventory/batches', $data);
}

public function delete($id)
{
    // Check if user can modify inventory
    if (!$this->canModifyInventory()) {
        return redirect()->to('/inventory')->with('error', 'You do not have permission to delete inventory items');
    }
    
    $model = new InventoryModel();
    $batchModel = new InventoryBatchModel();
    $distributionModel = new BatchDistributionModel();
    
    // Rest of your existing delete code...
    $item = $model->find($id);
    
    if (!$item) {
        return redirect()->to('/inventory')->with('error', 'Item not found');
    }
    
    // Start transaction
    $db = \Config\Database::connect();
    $db->transStart();
    
    try {
        // Get all batches for this item
        $batches = $batchModel->where('inventory_id', $id)->findAll();
        
        // Delete distribution records for each batch
        foreach ($batches as $batch) {
            $distributionModel->where('batch_id', $batch['id'])->delete();
        }
        
        // Delete all batches for this item
        $batchModel->where('inventory_id', $id)->delete();
        
        // Delete the main inventory item
        $model->delete($id);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            throw new \Exception('Transaction failed');
        }
        
        return redirect()->to('/inventory')->with('success', 'Item "' . esc($item['item_name']) . '" and all related records have been deleted successfully.');
        
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->to('/inventory')->with('error', 'Failed to delete item: ' . $e->getMessage());
    }
}

// Add this method to Inventory.php controller
private function canModifyInventory()
{
    $userRole = session()->get('role');
    // Only admin and staff can modify, distributor can only view
    return in_array($userRole, ['admin', 'staff']);
}

/**
 * Get batch tracking history for AJAX request
 * 
 * @param int $batchId The batch ID
 * @return JSON Response with transit and receipt history
 */
public function getBatchTracking($batchId)
{
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Set JSON header
    $this->response->setContentType('application/json');
    
    // Check if it's an AJAX request or just a regular GET
    // Allow both for easier debugging
    $batchModel = new InventoryBatchModel();
    
    // Validate batch ID
    if (!is_numeric($batchId)) {
        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Invalid batch ID'
        ]);
    }
    
    try {
        // Get tracking history from status logs
        $history = $batchModel->getBatchTrackingHistory($batchId);
        
        // Get batch details to verify it exists
        $batch = $batchModel->find($batchId);
        if (!$batch) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Batch not found'
            ]);
        }
        
        $result = [
            'success' => true,
            'batch_status' => $batch['status'],
            'history' => [
                'transit' => null,
                'receipt' => null,
                'all_logs' => $history // Include all logs for debugging
            ]
        ];
        
        foreach ($history as $log) {
            if ($log['status'] === 'in_transit') {
                $result['history']['transit'] = [
                    'date' => date('M d, Y h:i A', strtotime($log['created_at'])),
                    'dispatched_by' => $log['changed_by_name'] ?? 'Unknown',
                    'location' => $log['location'] ?? 'Unknown'
                ];
            } elseif ($log['status'] === 'received') {
                $result['history']['receipt'] = [
                    'date' => date('M d, Y h:i A', strtotime($log['created_at'])),
                    'received_by' => $log['changed_by_name'] ?? 'Unknown',
                    'location' => $log['location'] ?? 'Unknown'
                ];
            }
        }
        
        return $this->response->setJSON($result);
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
}