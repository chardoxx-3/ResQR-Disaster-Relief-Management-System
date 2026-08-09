<?php

namespace App\Controllers;

use App\Models\InventoryBatchModel;

class Dispatch extends BaseController
{
    protected $batchModel;

    public function __construct()
    {
        $this->batchModel = new InventoryBatchModel();
    }

    /**
     * Display batches ready for dispatch (in warehouse)
     */
    public function index()
    {
        $data['batches'] = $this->batchModel->getBatchesByStatus('in_warehouse');
        return view('dispatch/index', $data);
    }

    /**
     * Show batches in transit
     */
    public function inTransit()
    {
        $data['batches'] = $this->batchModel->getBatchesByStatus('in_transit');
        return view('dispatch/in_transit', $data);
    }

    /**
     * Show batches received by barangay
     */
    public function received()
    {
        $data['batches'] = $this->batchModel->getBatchesByStatus('received');
        return view('dispatch/received', $data);
    }

    /**
     * Display form to dispatch batch to barangay
     * 
     * @param int $batchId The batch ID
     */
    public function dispatchForm($batchId)
    {
        $batch = $this->batchModel->find($batchId);
        
        if (!$batch) {
            return redirect()->to('/dispatch')->with('error', 'Batch not found');
        }
        
        if ($batch['status'] !== 'in_warehouse') {
            return redirect()->to('/dispatch')->with('error', 'Batch is not available for dispatch');
        }
        
        $data['batch'] = $batch;
        return view('dispatch/dispatch_form', $data);
    }

    /**
     * Process dispatch to barangay
     */
    public function processDispatch()
    {
        $batchId = $this->request->getPost('batch_id');
        $barangay = $this->request->getPost('barangay');
        
        if (!$batchId || !$barangay) {
            return redirect()->back()->with('error', 'Missing required information');
        }
        
        $result = $this->batchModel->markAsInTransit(
            $batchId, 
            $barangay, 
            session()->get('id')
        );
        
        if ($result) {
            return redirect()->to('/dispatch/in-transit')
                ->with('success', 'Batch marked as In Transit to ' . $barangay);
        }
        
        return redirect()->back()->with('error', 'Failed to dispatch batch');
    }

    /**
     * Display form for barangay to confirm receipt
     * 
     * @param int $batchId The batch ID
     */
    public function receiveForm($batchId)
    {
        $batch = $this->batchModel->find($batchId);
        
        if (!$batch) {
            return redirect()->to('/dispatch/in-transit')->with('error', 'Batch not found');
        }
        
        if ($batch['status'] !== 'in_transit') {
            return redirect()->to('/dispatch/in-transit')->with('error', 'Batch is not in transit');
        }
        
        $data['batch'] = $batch;
        return view('dispatch/receive_form', $data);
    }

    /**
     * Process receipt confirmation by barangay
     */
    public function processReceipt()
    {
        $batchId = $this->request->getPost('batch_id');
        $barangay = $this->request->getPost('barangay');
        
        if (!$batchId || !$barangay) {
            return redirect()->back()->with('error', 'Missing required information');
        }
        
        $result = $this->batchModel->markAsReceived(
            $batchId,
            session()->get('id'),
            $barangay
        );
        
        if ($result) {
            return redirect()->to('/dispatch/received')
                ->with('success', 'Batch confirmed as received by ' . $barangay);
        }
        
        return redirect()->back()->with('error', 'Failed to confirm receipt');
    }

    /**
     * View tracking history of a batch
     * 
     * @param int $batchId The batch ID
     */
    public function track($batchId)
    {
        $batch = $this->batchModel->find($batchId);
        
        if (!$batch) {
            return redirect()->to('/dispatch')->with('error', 'Batch not found');
        }
        
        $data = [
            'batch' => $batch,
            'history' => $this->batchModel->getBatchTrackingHistory($batchId)
        ];
        
        return view('dispatch/track', $data);
    }

/**
 * Handle scan for marking batch as In Transit
 */
public function scanForTransit()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $json = $this->request->getJSON();
    $qrToken = $json->qr_token ?? '';
    
    if (!$qrToken) {
        return $this->response->setJSON(['success' => false, 'message' => 'No QR code provided']);
    }
    
    // Find batch by QR token
    $batch = $this->batchModel->where('qr_code_token', $qrToken)->first();
    
    if (!$batch) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid QR code - Batch not found']);
    }
    
    // Check if batch is in warehouse
    if ($batch['status'] !== InventoryBatchModel::STATUS_IN_WAREHOUSE) {
        $statusMsg = [
            'in_transit' => 'already in transit',
            'received' => 'already received',
            'depleted' => 'already depleted',
            'expired' => 'expired'
        ];
        $msg = $statusMsg[$batch['status']] ?? 'not available for dispatch';
        return $this->response->setJSON([
            'success' => false, 
            'message' => "This batch is {$msg}. Current status: {$batch['status']}"
        ]);
    }
    
    // Get the barangay from the batch's assignment
    $batchBarangay = $batch['assigned_to'] ?? null;
    
    // If the batch has an assigned barangay, use it
    if ($batchBarangay && $batch['assignment_type'] === 'barangay') {
        $destinationBarangay = $batchBarangay;
    } else {
        // If no barangay assigned, check if user has barangay in session
        $destinationBarangay = session()->get('barangay');
        
        if (!$destinationBarangay) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'This batch has no assigned barangay. Please select destination barangay.',
                'need_barangay' => true,
                'batch_id' => $batch['id'],
                'batch_info' => [
                    'number' => $batch['batch_number'],
                    'item' => $this->getItemName($batch['inventory_id'])
                ]
            ]);
        }
    }
    
    // Process dispatch
    $result = $this->batchModel->markAsInTransit(
        $batch['id'],
        $destinationBarangay,
        session()->get('id')
    );
    
    if ($result) {
        return $this->response->setJSON([
            'success' => true,
            'message' => "✅ Batch {$batch['batch_number']} marked as IN TRANSIT to {$destinationBarangay}",
            'batch' => [
                'number' => $batch['batch_number'],
                'item' => $this->getItemName($batch['inventory_id'])
            ]
        ]);
    }
    
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to update batch status'
    ]);
}

/**
 * Handle scan for marking batch as Received
 */
public function scanForReceipt()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $json = $this->request->getJSON();
    $qrToken = $json->qr_token ?? '';
    
    if (!$qrToken) {
        return $this->response->setJSON(['success' => false, 'message' => 'No QR code provided']);
    }
    
    // Find batch by QR token
    $batch = $this->batchModel->where('qr_code_token', $qrToken)->first();
    
    if (!$batch) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid QR code - Batch not found']);
    }
    
    // Check if batch is in transit
    if ($batch['status'] !== InventoryBatchModel::STATUS_IN_TRANSIT) {
        $statusMsg = [
            'in_warehouse' => 'still in warehouse (not dispatched yet)',
            'received' => 'already received',
            'depleted' => 'already depleted',
            'expired' => 'expired'
        ];
        $msg = $statusMsg[$batch['status']] ?? 'not available for receipt';
        return $this->response->setJSON([
            'success' => false, 
            'message' => "This batch is {$msg}. Current status: {$batch['status']}"
        ]);
    }
    
    // Get the batch's assigned barangay
    $batchBarangay = $batch['assigned_to'] ?? null;
    
    // Verify barangay matches
    $userBarangay = session()->get('barangay');
    
    if (!$userBarangay) {
        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Your account does not have a barangay assignment',
            'need_barangay' => true
        ]);
    }
    
    if ($batchBarangay && $batchBarangay !== $userBarangay) {
        return $this->response->setJSON([
            'success' => false,
            'message' => "❌ This batch is assigned to {$batchBarangay}, but you are from {$userBarangay}",
            'batch_info' => [
                'number' => $batch['batch_number'],
                'assigned_to' => $batchBarangay,
                'your_barangay' => $userBarangay
            ]
        ]);
    }
    
    // Process receipt - UPDATED: Pass userBarangay to markAsReceived method
    $result = $this->batchModel->markAsReceived(
        $batch['id'],
        session()->get('id'),
        $userBarangay  // Pass the user's barangay
    );
    
    if ($result) {
        return $this->response->setJSON([
            'success' => true,
            'message' => "✅ Batch {$batch['batch_number']} marked as RECEIVED by {$userBarangay}",
            'batch' => [
                'number' => $batch['batch_number'],
                'item' => $this->getItemName($batch['inventory_id'])
            ]
        ]);
    }
    
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to update batch status'
    ]);
}

/**
 * Helper method to get item name
 */
private function getItemName($inventoryId)
{
    $db = \Config\Database::connect();
    $query = $db->table('inventory')
                ->select('item_name')
                ->where('id', $inventoryId)
                ->get()
                ->getRow();
    return $query ? $query->item_name : 'Unknown Item';
}
}