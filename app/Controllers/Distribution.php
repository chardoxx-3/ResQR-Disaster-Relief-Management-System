<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\InventoryModel;
use App\Models\DistributionModel;
use App\Models\FamilyMemberModel;
use App\Models\InventoryBatchModel;
use App\Models\BatchDistributionModel;

class Distribution extends BaseController
{

protected $eventModel;
public function scanner()
{
    $data['active_events'] = $this->eventModel->getActiveEvents();
    return view('scanner/index', $data);
}

    public function __construct()
{
    $this->eventModel = new \App\Models\EventModel();
}

public function processScanView()
{
    $qrToken = $this->request->getGet('qr_token');
    $eventId = $this->request->getGet('event_id');
    
    // Event now comes from the scanner dropdown, not a single global active event
    $activeEvent = $eventId ? $this->eventModel->find($eventId) : null;
    
    if (!$activeEvent || $activeEvent['is_active'] != 1) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please select a valid active event before scanning.'
            ]);
        }
        return redirect()->to('/distribution/scanner')->with('error', 'Please select a valid active event before scanning.');
    }
    
    // Check if this is an AJAX request
    $isAjax = $this->request->isAJAX();
    
    if (!$qrToken) {
        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No QR code provided'
            ]);
        }
        return redirect()->to('/distribution/scanner')->with('error', 'No QR code provided');
    }
    
    // Load models
    $inventoryModel = new \App\Models\InventoryModel();
    $residentModel = new \App\Models\ResidentModel();
    $familyMemberModel = new \App\Models\FamilyMemberModel();
    $distributionModel = new \App\Models\BatchDistributionModel();
    $attendanceModel = new \App\Models\AttendanceModel();
    $batchModel = new \App\Models\InventoryBatchModel();
    $db = \Config\Database::connect();
    
    // Check what type of QR code this is
    $familyMember = $familyMemberModel->getByQRToken($qrToken);
    $headOfFamily = $residentModel->where('qr_code_token', $qrToken)->first();
    $householdQR = $residentModel->where('household_qr_token', $qrToken)->first();
    
    // Determine who is trying to claim
    $claimingResidentId = null;
    $claimingFamilyMemberId = null;
    $claimingPerson = null;
    $claimingPersonType = null;
    $claimingPersonName = null;
    $isHouseholdQR = false;
    
    if ($familyMember) {
        // This is a family member individual QR
        $claimingFamilyMemberId = $familyMember['id'];
        $claimingResidentId = $familyMember['resident_id'];
        $claimingPerson = $familyMember;
        $claimingPersonType = 'family_member';
        $claimingPersonName = $familyMember['name'];
        $resident = $residentModel->find($claimingResidentId);
    } elseif ($householdQR) {
        // This is a household QR - represents the entire household
        $isHouseholdQR = true;
        $claimingResidentId = $householdQR['id'];
        $claimingFamilyMemberId = null;
        $claimingPerson = $householdQR;
        $claimingPersonType = 'household';
        $claimingPersonName = $householdQR['first_name'] . ' ' . $householdQR['last_name'] . ' (Household)';
        $resident = $householdQR;
    } elseif ($headOfFamily) {
        // This is head of family individual QR - REJECT (should use household QR instead)
        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid QR code - Please scan the household QR code, not the head of family individual QR'
            ]);
        }
        return redirect()->to('/distribution/scanner')->with('error', 'Invalid QR code - Please scan the household QR code');
    } else {
        // Check if it's a batch QR code (for inventory)
        $batch = $batchModel->getByQRToken($qrToken);
        if ($batch) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'This is a batch QR code, not a beneficiary QR code'
                ]);
            }
            return redirect()->to('/distribution/scanner')->with('error', 'Invalid QR code - Please scan a beneficiary QR code');
        }
        
        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid QR code - Household not found'
            ]);
        }
        return redirect()->to('/distribution/scanner')->with('error', 'Invalid QR code');
    }
    
    if (!$resident) {
        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Resident not found'
            ]);
        }
        return redirect()->to('/distribution/scanner')->with('error', 'Resident not found');
    }
    
    $residentId = $resident['id'];
    
    // Start database transaction
    $db->transStart();
    
    try {
        // ===== CHECK IF HOUSEHOLD ALREADY CLAIMED =====
        // Check if this household has already claimed for THIS EVENT
        $existingClaim = $distributionModel
            ->where('resident_id', $residentId)
            ->where('event_id', $activeEvent['id'])
            ->where('DATE(distribution_date)', date('Y-m-d'))
            ->first();
        
        if ($existingClaim) {
            // Get distributor info
            $distributorName = 'Unknown';
            if (isset($existingClaim['distributor_id'])) {
                $userModel = new \App\Models\UserModel();
                $distributor = $userModel->find($existingClaim['distributor_id']);
                $distributorName = $distributor ? $distributor['username'] : 'Unknown';
            }
            
            $db->transComplete();
            
            $familyMembers = $familyMemberModel->getByResidentId($residentId);
            
            // Determine who claimed previously
            $previousClaimant = 'Head of Family';
            if ($existingClaim['family_member_id']) {
                $previousMember = $familyMemberModel->find($existingClaim['family_member_id']);
                $previousClaimant = $previousMember ? $previousMember['name'] : 'Family Member';
            }
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'denied',
                    'message' => 'This household has already claimed relief goods for event: ' . $activeEvent['event_name'] . ' (Claimed by: ' . $previousClaimant . ')',
                    'resident_id' => $residentId,
                    'resident' => [
                        'id' => $resident['id'],
                        'full_name' => $resident['first_name'] . ' ' . $resident['last_name'],
                        'first_name' => $resident['first_name'],
                        'last_name' => $resident['last_name'],
                        'household_no' => $resident['household_no'],
                        'barangay' => $resident['barangay'],
                        'contact_number' => $resident['contact_number'] ?? 'N/A',
                        'photo' => $resident['photo'] ?? null
                    ],
                    'family_members' => $familyMembers,
                    'distribution_history' => [
                        'claimed_at' => $existingClaim['distribution_date'] ?? date('Y-m-d H:i:s'),
                        'distributor_name' => $distributorName,
                        'event_name' => $activeEvent['event_name'],
                        'claimed_by' => $previousClaimant
                    ],
                    'event' => $activeEvent
                ]);
            }
            
            $data = [
                'status' => 'denied',
                'message' => 'This household has already claimed their relief goods for event: ' . $activeEvent['event_name'] . ' (Claimed by: ' . $previousClaimant . ')',
                'resident' => $resident,
                'event' => $activeEvent,
                'distribution_history' => [
                    'claimed_at' => $existingClaim['distribution_date'] ?? date('Y-m-d H:i:s'),
                    'distributor_name' => $distributorName,
                    'claimed_by' => $previousClaimant
                ]
            ];
            
            return view('distribution/scan_result', $data);
        }
        
        // ===== ATTENDANCE CHECK =====
        // Get the ordered list of eligible claimants based on attendance
        $eligibleClaimants = $this->getEligibleClaimantsOrder($residentId, $activeEvent['id'], $familyMemberModel, $attendanceModel);
        
        if (empty($eligibleClaimants)) {
            $db->transRollback();
            
            $message = 'No household member has checked in for attendance. Please check in first before claiming relief goods.';
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $message,
                    'resident_id' => $residentId,
                    'event' => $activeEvent
                ]);
            }
            
            $data = [
                'status' => 'error',
                'message' => $message,
                'resident' => $resident,
                'event' => $activeEvent
            ];
            return view('distribution/scan_result', $data);
        }
        
        // The next eligible claimant is the first person in the list
        $nextClaimant = $eligibleClaimants[0];
        
        // Check if the scanned person is allowed to claim
        $isAllowedToClaim = false;
        
        if ($isHouseholdQR) {
            // HOUSEHOLD QR EXCEPTION: Any eligible family member can claim
            // The person claiming MUST be the one with attendance
            // So we need to check if the person scanning the household QR
            // is actually one of the eligible claimants
            // But since we don't know who is scanning, we'll use the household QR to mean
            // "the next eligible claimant can claim on behalf of the household"
            
            // For household QR, we'll auto-assign to the next eligible claimant
            // This means whoever is scanning (the eligible person) can claim
            $claimingFamilyMemberId = ($nextClaimant['type'] === 'family_member') ? $nextClaimant['id'] : null;
            $claimingPersonName = $nextClaimant['name'] . ' (via Household QR)';
            $isAllowedToClaim = true;
        } else {
            // For individual QR codes, the scanned person MUST match the next eligible claimant
            if ($claimingFamilyMemberId) {
                // Family member individual QR
                if ($nextClaimant['type'] === 'family_member' && $nextClaimant['id'] == $claimingFamilyMemberId) {
                    $isAllowedToClaim = true;
                }
            } else {
                // Head individual QR (should not happen as we reject it earlier)
                if ($nextClaimant['type'] === 'head' && $nextClaimant['id'] == $residentId) {
                    $isAllowedToClaim = true;
                }
            }
        }
        
        if (!$isAllowedToClaim) {
            $db->transRollback();
            
            // Tell them who should claim
            $claimantName = $nextClaimant['name'];
            $claimantType = $nextClaimant['type'] === 'head' ? 'Head of Family' : 'Family Member';
            
            $message = "The person who checked in for attendance must claim the relief goods. ";
            $message .= "Please have {$claimantName} ({$claimantType}) scan their QR code.";
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $message,
                    'resident_id' => $residentId,
                    'next_claimant' => $nextClaimant,
                    'event' => $activeEvent
                ]);
            }
            
            $data = [
                'status' => 'error',
                'message' => $message,
                'resident' => $resident,
                'event' => $activeEvent,
                'next_claimant' => $nextClaimant
            ];
            return view('distribution/scan_result', $data);
        }
        
        // ===== STOCK CHECK AND DISTRIBUTION =====
        // Get available batches for this barangay
$availableBatches = $batchModel
    ->select('inventory_batches.*, inventory.item_name, inventory.allocation, inventory.id as inventory_id')
    ->join('inventory', 'inventory.id = inventory_batches.inventory_id')
    ->where('inventory_batches.quantity_remaining >', 0)
    ->where('inventory_batches.status !=', 'depleted')
    ->groupStart() // Start OR condition for assignment types
        // Condition 1: Batch specifically assigned to this barangay
        ->groupStart()
            ->where('inventory_batches.assignment_type', 'barangay')
            ->where('inventory_batches.barangay', $resident['barangay'])
        ->groupEnd()
        // Condition 2: General stock (no specific assignment)
        ->orGroupStart()
            ->where('inventory_batches.assignment_type', NULL)
            ->orWhere('inventory_batches.assignment_type', '')
            ->orWhere('inventory_batches.assigned_to', NULL)
            ->orWhere('inventory_batches.assigned_to', '')
        ->groupEnd()
    ->groupEnd()
    ->orderBy('inventory_batches.expiry_date', 'ASC')
    ->findAll();
        
if (empty($availableBatches)) {
    $db->transRollback();
    
    $errorMessage = 'No available stock for your barangay for event: ' . $activeEvent['event_name'];
    
    if ($isAjax) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => $errorMessage,
            'resident_id' => $residentId,
            'event' => $activeEvent
        ]);
    }
    
    $data = [
        'status' => 'error',
        'message' => $errorMessage,
        'resident' => $resident,
        'event' => $activeEvent
    ];
    return view('distribution/scan_result', $data);
}
        
        // Distribute items
        $itemsDistributed = [];
        $fulfilledItems = [];
        
        foreach ($availableBatches as $batch) {
            $inventoryId = $batch['inventory_id'];
            $itemName = $batch['item_name'];
            
            if (in_array($inventoryId, $fulfilledItems)) {
                continue;
            }
            
            $allocation = $batch['allocation'] ?? 1;
            
            if ($batch['quantity_remaining'] < $allocation) {
                continue;
            }
            
            $updateResult = $batchModel->deductFromBatch($batch['id'], $allocation);
            
            if ($updateResult) {
                // Log the distribution with event_id - use the claiming person's info
                $distributionId = $distributionModel->insert([
                    'event_id' => $activeEvent['id'],
                    'batch_id' => $batch['id'],
                    'resident_id' => $residentId,
                    'family_member_id' => $claimingFamilyMemberId,
                    'distributor_id' => session()->get('id'),
                    'quantity_distributed' => $allocation,
                    'distribution_date' => date('Y-m-d H:i:s'),
                    'qr_code_scanned' => $qrToken,
                    'status' => 'completed',
                    'remarks' => 'Claimed by: ' . $claimingPersonName
                ]);
                
                $itemsDistributed[] = [
                    'item_name' => $itemName,
                    'quantity' => $allocation,
                    'unit_type' => $batch['unit_type'],
                    'batch_number' => $batch['batch_number']
                ];
                
                $fulfilledItems[] = $inventoryId;
                
                // Update main inventory
                $totalRemaining = $batchModel
                    ->where('inventory_id', $inventoryId)
                    ->selectSum('quantity_remaining')
                    ->get()
                    ->getRow()
                    ->quantity_remaining ?? 0;
                
                $inventoryModel->update($inventoryId, ['quantity' => $totalRemaining]);
            }
        }
        
        if (empty($itemsDistributed)) {
            $db->transRollback();
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Unable to distribute items - insufficient stock for event: ' . $activeEvent['event_name'],
                    'resident_id' => $residentId,
                    'event' => $activeEvent
                ]);
            }
            
            $data = [
                'status' => 'error',
                'message' => 'Unable to distribute items - insufficient stock',
                'resident' => $resident,
                'event' => $activeEvent
            ];
            return view('distribution/scan_result', $data);
        }
        
        $db->transComplete();
        
        $familyMembers = $familyMemberModel->getByResidentId($residentId);
        
        // Determine who claimed (for display)
        $claimedByType = $claimingFamilyMemberId ? 'Family Member' : 'Head of Family';
        if ($isHouseholdQR) {
            $claimedByType = 'Household QR - ' . $claimedByType;
        }
        
if ($isAjax) {
    // Add attendance data to family members
    $attendanceModel = new \App\Models\AttendanceModel();
    $today = date('Y-m-d');
    
    // Get family members with attendance data
    foreach ($familyMembers as &$member) {
        $attendance = $attendanceModel
            ->where('family_member_id', $member['id'])
            ->where('event_id', $activeEvent['id'])
            ->where('attendance_date', $today)
            ->first();
        
        $member['checked_in_today'] = !empty($attendance);
        $member['check_in_time'] = $attendance['check_in_time'] ?? null;
    }
    
    // Get head attendance
    $headAttendance = $attendanceModel
        ->where('resident_id', $residentId)
        ->where('family_member_id', null)
        ->where('event_id', $activeEvent['id'])
        ->where('attendance_date', $today)
        ->first();
    
    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Distribution successful for event: ' . $activeEvent['event_name'] . ' (Claimed by: ' . $claimingPersonName . ')',
        'resident_id' => $residentId,
        'resident' => [
            'id' => $resident['id'],
            'full_name' => $resident['first_name'] . ' ' . $resident['last_name'],
            'first_name' => $resident['first_name'],
            'last_name' => $resident['last_name'],
            'household_no' => $resident['household_no'],
            'barangay' => $resident['barangay'],
            'contact_number' => $resident['contact_number'] ?? 'N/A',
            'photo' => $resident['photo'] ?? null,
            'checked_in_today' => !empty($headAttendance),
            'check_in_time' => $headAttendance['check_in_time'] ?? null
        ],
        'family_members' => $familyMembers,
        'items_distributed' => $itemsDistributed,
        'distribution_time' => date('Y-m-d H:i:s'),
        'event' => $activeEvent,
        'claimed_by' => [
            'name' => $claimingPersonName,
            'type' => $claimedByType,
            'id' => $claimingFamilyMemberId ?? $residentId
        ]
    ]);
}
        
        $data = [
            'status' => 'success',
            'message' => 'Distribution successful (Claimed by: ' . $claimingPersonName . ')',
            'resident' => $resident,
            'items_distributed' => $itemsDistributed,
            'family_members' => $familyMembers,
            'event' => $activeEvent,
            'claimed_by' => [
                'name' => $claimingPersonName,
                'type' => $claimedByType
            ]
        ];
        
        return view('distribution/scan_result', $data);
        
    } catch (\Exception $e) {
        $db->transRollback();
        
        log_message('error', 'Distribution error: ' . $e->getMessage());
        
        if ($isAjax) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
        
        $data = [
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage(),
            'resident' => $resident ?? null
        ];
        return view('distribution/scan_result', $data);
    }
}

/**
 * Get ordered list of eligible claimants based on attendance
 * Order: Head first, then family members by created_at (oldest first)
 */
private function getEligibleClaimantsOrder($residentId, $eventId, $familyMemberModel, $attendanceModel)
{
    $eligibleClaimants = [];
    
    // Check if head has attendance
    $headAttendance = $attendanceModel
        ->where('resident_id', $residentId)
        ->where('family_member_id', null)
        ->where('event_id', $eventId)
        ->where('attendance_date', date('Y-m-d'))
        ->first();
    
    if ($headAttendance) {
        $residentModel = new \App\Models\ResidentModel();
        $head = $residentModel->find($residentId);
        
        $eligibleClaimants[] = [
            'type' => 'head',
            'id' => $residentId,
            'name' => ($head['first_name'] ?? '') . ' ' . ($head['last_name'] ?? ''),
            'attendance_time' => $headAttendance['check_in_time']
        ];
    }
    
    // Get family members with attendance, ordered by created_at (oldest first)
    $familyMembers = $familyMemberModel
        ->where('resident_id', $residentId)
        ->orderBy('created_at', 'ASC')
        ->findAll();
    
    foreach ($familyMembers as $member) {
        $memberAttendance = $attendanceModel
            ->where('family_member_id', $member['id'])
            ->where('event_id', $eventId)
            ->where('attendance_date', date('Y-m-d'))
            ->first();
        
        if ($memberAttendance) {
            $eligibleClaimants[] = [
                'type' => 'family_member',
                'id' => $member['id'],
                'name' => $member['name'],
                'relation' => $member['relation'],
                'attendance_time' => $memberAttendance['check_in_time']
            ];
        }
    }
    
    return $eligibleClaimants;
}

/**
 * Check if any household member has attendance for the event
 * Returns the first person who checked in (in order of created_at)
 */
private function checkHouseholdAttendance($residentId, $eventId, $familyMemberModel, $residentModel = null)
{
    $attendanceModel = new \App\Models\AttendanceModel();
    
    // If residentModel not provided, load it
    if (!$residentModel) {
        $residentModel = new \App\Models\ResidentModel();
    }
    
    // First, check if head of family has attendance
    $headAttendance = $attendanceModel
        ->where('resident_id', $residentId)
        ->where('family_member_id', null)
        ->where('event_id', $eventId)
        ->where('attendance_date', date('Y-m-d'))
        ->first();
    
    if ($headAttendance) {
        $head = $residentModel->find($residentId);
        return [
            'has_attendance' => true,
            'person_type' => 'head',
            'person_id' => $residentId,
            'person_name' => ($head['first_name'] ?? '') . ' ' . ($head['last_name'] ?? ''),
            'attendance_time' => $headAttendance['check_in_time']
        ];
    }
    
    // If head not present, get all family members ordered by created_at (oldest first)
    $familyMembers = $familyMemberModel
        ->where('resident_id', $residentId)
        ->orderBy('created_at', 'ASC') // This ensures first added comes first
        ->findAll();
    
    // Check each family member in order of creation
    foreach ($familyMembers as $member) {
        $memberAttendance = $attendanceModel
            ->where('family_member_id', $member['id'])
            ->where('event_id', $eventId)
            ->where('attendance_date', date('Y-m-d'))
            ->first();
        
        if ($memberAttendance) {
            return [
                'has_attendance' => true,
                'person_type' => 'family_member',
                'person_id' => $member['id'],
                'person_name' => $member['name'],
                'relation' => $member['relation'],
                'attendance_time' => $memberAttendance['check_in_time']
            ];
        }
    }
    
    // No one has attendance
    return [
        'has_attendance' => false
    ];
}

/**
 * Get today's distribution stats from batch distributions
 */
public function todayStats()
{
    $batchDistributionModel = new BatchDistributionModel();
    
    $today = date('Y-m-d');
    $count = $batchDistributionModel
        ->where('DATE(distribution_date)', $today)
        ->countAllResults();
    
    return $this->response->setJSON([
        'today' => $count
    ]);
}

/**
 * Get distributor information for a specific distribution
 * 
 * @param int $distributorId The distributor's user ID
 * @param int $residentId The resident ID who claimed
 * @return JSON Response
 */
public function getDistributorInfo($distributorId, $residentId)
{
    // Remove the AJAX check temporarily for debugging
    // if (!$this->request->isAJAX()) {
    //     return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
    // }
    
    try {
        // Load models
        $userModel = new \App\Models\UserModel();
        $distributionModel = new \App\Models\BatchDistributionModel();
        $batchModel = new \App\Models\InventoryBatchModel();
        
        // Get distributor information
        $distributor = $userModel->find($distributorId);
        
        if (!$distributor) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Distributor not found'
            ]);
        }
        
        // Get today's distribution for this resident
        $today = date('Y-m-d');
        $distribution = $distributionModel
            ->where('resident_id', $residentId)
            ->where('DATE(distribution_date)', $today)
            ->first();
        
        if (!$distribution) {
            // Try to find if it was a family member claim
            $distribution = $distributionModel
                ->where('family_member_id', $residentId)
                ->where('DATE(distribution_date)', $today)
                ->first();
        }
        
        // Get items distributed in this batch
        $items = [];
        if ($distribution && isset($distribution['batch_id'])) {
            $batch = $batchModel
                ->select('inventory_batches.*, inventory.item_name')
                ->join('inventory', 'inventory.id = inventory_batches.inventory_id')
                ->where('inventory_batches.id', $distribution['batch_id'])
                ->first();
            
            if ($batch) {
                $items[] = $batch['item_name'] . ' (' . $distribution['quantity_distributed'] . ' ' . $batch['unit_type'] . ')';
            }
        }
        
        // Set proper JSON header
        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/json')
            ->setJSON([
                'success' => true,
                'distributor' => [
                    'id' => $distributor['id'],
                    'username' => $distributor['username'],
                    'full_name' => $distributor['full_name'] ?? trim(($distributor['first_name'] ?? '') . ' ' . ($distributor['last_name'] ?? '')),
                    'first_name' => $distributor['first_name'] ?? '',
                    'last_name' => $distributor['last_name'] ?? '',
                    'email' => $distributor['email'] ?? '',
                    'contact_number' => $distributor['contact_number'] ?? '',
                    'barangay' => $distributor['barangay'] ?? '',
                    'city' => $distributor['city'] ?? '',
                    'role' => $distributor['role'] ?? 'distributor'
                ],
                'distribution' => $distribution ? [
                    'distribution_date' => $distribution['distribution_date'],
                    'family_member_id' => $distribution['family_member_id'] ?? null
                ] : null,
                'items' => $items
            ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error in getDistributorInfo: ' . $e->getMessage());
        return $this->response
            ->setStatusCode(500)
            ->setContentType('application/json')
            ->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
    }
}

public function listJson()
{
    $eventId = $this->request->getGet('event_id');
    $model = new \App\Models\BatchDistributionModel();
    $rows = $model->getRecentByEvent($eventId);

    return $this->response->setJSON(['status' => 'success', 'data' => $rows]);
}
}