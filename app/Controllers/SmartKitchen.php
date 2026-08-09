<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use App\Models\KitchenDistributionModel;

class SmartKitchen extends BaseController
{
    // Fixed, non-personal token — printed once and reused by all guests.
    const GUEST_QR_TOKEN = 'SMK-GUEST-CLAIM-2026';

    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new \App\Models\EventModel();
    }

    /**
     * Static Guest QR page — printable, same token every time.
     * Any unregistered person scans this to claim a meal as a guest.
     */
    public function guestQR()
    {
        $data['guest_token'] = self::GUEST_QR_TOKEN;
        return view('scanner/guest_qr_view', $data);
    }

    public function scanner()
    {
        $data['active_events'] = $this->eventModel->getActiveEvents();
        return view('scanner/kitchen_index', $data); // reuse scanner/index.php as a starting template
    }

public function processScan()
{
    $qrToken = $this->request->getGet('qr_token');
    $eventId = $this->request->getGet('event_id');
    $isAjax  = $this->request->isAJAX();

    $activeEvent = $eventId ? $this->eventModel->find($eventId) : null;

    if (!$activeEvent || $activeEvent['is_active'] != 1) {
        $message = 'Please select a valid active event before scanning.';
        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message]);
        }
        return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
    }

    if (!$qrToken) {
        $message = 'No QR code provided';
        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message]);
        }
        return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
    }

        if ($qrToken === self::GUEST_QR_TOKEN) {
        return $this->processGuestScan($activeEvent, $isAjax);
    }

    $residentModel     = new ResidentModel();
    $familyMemberModel = new FamilyMemberModel();
    $kitchenModel      = new KitchenDistributionModel();
    $inventoryModel    = new \App\Models\InventoryModel();
    $db                = \Config\Database::connect();

    // Only individual Family Head QR (qr_code_token) and Family Member QR are accepted.
    // Household/FACED QR (household_qr_token) is intentionally NOT checked here.
    $familyMember  = $familyMemberModel->getByQRToken($qrToken);
    $headOfFamily  = $residentModel->where('qr_code_token', $qrToken)->first();

    $claimingResidentId     = null;
    $claimingFamilyMemberId = null;
    $claimantType           = null;
    $claimingPersonName     = null;
    $resident               = null;

    if ($familyMember) {
        $claimingFamilyMemberId = $familyMember['id'];
        $claimingResidentId     = $familyMember['resident_id'];
        $claimantType           = 'family_member';
        $claimingPersonName     = $familyMember['name'];
        $resident               = $residentModel->find($claimingResidentId);
    } elseif ($headOfFamily) {
        $claimingResidentId     = $headOfFamily['id'];
        $claimingFamilyMemberId = null;
        $claimantType           = 'head';
        $claimingPersonName     = $headOfFamily['first_name'] . ' ' . $headOfFamily['last_name'];
        $resident               = $headOfFamily;
    } else {
        $householdQR = $residentModel->where('household_qr_token', $qrToken)->first();

        $message = $householdQR
            ? 'Household (FACED) QR codes are not supported for Smart Mobile Kitchen distribution. Please scan the individual Family Head or Family Member QR code.'
            : 'Invalid QR code - not recognized';

        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message]);
        }
        return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
    }

    if (!$resident) {
        $message = 'Resident not found';
        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message]);
        }
        return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
    }

    // --- NEW: resolve the active cooked food item and required allocation ---
    $cookedFood = $inventoryModel->getActiveCookedFood();

    if (!$cookedFood) {
        $message = 'No cooked food item has been set up in inventory yet.';
        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message]);
        }
        return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
    }

// Each individual (head or family member) scans and claims their own
// single serving — never multiplied by household/family member count.
$allocation = 1;

    if ($cookedFood['quantity'] < $allocation) {
        $message = 'Insufficient cooked food stock remaining for this claim.';
        if ($isAjax) {
            return $this->response->setJSON(['status' => 'error', 'message' => $message]);
        }
        return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
    }
    // --- END NEW ---

    $db->transStart();

    // No attendance requirement — just prevent the same person double-claiming
    // a meal for the same event on the same day.
    $existingClaim = $kitchenModel->hasClaimedToday($claimingResidentId, $claimingFamilyMemberId, $activeEvent['id']);

if ($existingClaim) {
        // NEW: log the duplicate attempt so the live monitor can flag it
        $kitchenModel->insert([
            'event_id'           => $activeEvent['id'],
            'resident_id'        => $claimingResidentId,
            'family_member_id'   => $claimingFamilyMemberId,
            'distributor_id'     => session()->get('id'),
            'claimant_type'      => $claimantType,
            'qr_code_scanned'    => $qrToken,
            'distribution_date'  => date('Y-m-d H:i:s'),
            'status'             => 'denied',
            'remarks'            => 'Duplicate attempt by: ' . $claimingPersonName
        ]);
        // END NEW

        $db->transComplete();
        $message = $claimingPersonName . ' has already claimed a meal for event: ' . $activeEvent['event_name'];

if ($isAjax) {
            return $this->response->setJSON([
                'status'        => 'denied',
                'message'       => $message,
                'resident_id'   => $claimingResidentId,
                'event'         => $activeEvent,
                'claimed_at'    => $existingClaim['distribution_date'],
                'item'          => [
                    'name'      => $cookedFood['item_name'],
                    'quantity'  => $allocation,
                    'unit_type' => $cookedFood['unit_type']
                ],
                'resident'      => [
                    'id'             => $resident['id'],
                    'full_name'      => $resident['first_name'] . ' ' . $resident['last_name'],
                    'first_name'     => $resident['first_name'],
                    'last_name'      => $resident['last_name'],
                    'household_no'   => $resident['household_no'],
                    'barangay'       => $resident['barangay'],
                    'contact_number' => $resident['contact_number'] ?? 'N/A',
                    'photo'          => $resident['photo'] ?? null
                ],
                'family_member' => $claimantType === 'family_member' ? [
                    'id'       => $claimingFamilyMemberId,
                    'name'     => $claimingPersonName,
                    'relation' => $familyMember['relation'] ?? null,
                    'photo'    => $familyMember['photo'] ?? null
                ] : null
            ]);
        }

        return view('distribution/scan_result', [
            'status'  => 'denied',
            'message' => $message,
            'resident'=> $resident,
            'event'   => $activeEvent
        ]);
    }

    $kitchenModel->insert([
        'event_id'           => $activeEvent['id'],
        'resident_id'        => $claimingResidentId,
        'family_member_id'   => $claimingFamilyMemberId,
        'distributor_id'     => session()->get('id'),
        'claimant_type'      => $claimantType,
        'qr_code_scanned'    => $qrToken,
        'distribution_date'  => date('Y-m-d H:i:s'),
        'status'             => 'completed',
        'remarks'            => 'Claimed by: ' . $claimingPersonName
    ]);

    // --- NEW: deduct from cooked food inventory, not batch inventory ---
    $inventoryModel->deductCookedFoodStock($cookedFood['id'], $allocation);
    // --- END NEW ---

    $db->transComplete();

    $message = 'Meal successfully recorded for ' . $claimingPersonName;

if ($isAjax) {
    return $this->response->setJSON([
        'status'        => 'success',
        'message'       => $message,
        'resident_id'   => $claimingResidentId,
        'event'         => $activeEvent,
        'claimed_at'    => date('Y-m-d H:i:s'),
        'item'          => [
            'name'      => $cookedFood['item_name'],
            'quantity'  => $allocation,
            'unit_type' => $cookedFood['unit_type']
        ],
        'resident'      => [
            'id'             => $resident['id'],
            'full_name'      => $resident['first_name'] . ' ' . $resident['last_name'],
            'first_name'     => $resident['first_name'],
            'last_name'      => $resident['last_name'],
            'household_no'   => $resident['household_no'],
            'barangay'       => $resident['barangay'],
            'contact_number' => $resident['contact_number'] ?? 'N/A',
            'photo'          => $resident['photo'] ?? null
        ],
        'family_member' => $claimantType === 'family_member' ? [
            'id'       => $claimingFamilyMemberId,
            'name'     => $claimingPersonName,
            'relation' => $familyMember['relation'] ?? null,
            'photo'    => $familyMember['photo'] ?? null
        ] : null
    ]);
}

    return view('distribution/scan_result', [
        'status'  => 'success',
        'message' => $message,
        'resident'=> $resident,
        'event'   => $activeEvent
    ]);
}

public function todayStats()
{
    $eventId = $this->request->getGet('event_id');
    $date    = $this->request->getGet('date'); // optional, model defaults to today
    $model = new KitchenDistributionModel();

    $inventoryModel = new \App\Models\InventoryModel();
    $cookedFood = $inventoryModel->getActiveCookedFood();

    return $this->response->setJSON([
        'today' => $model->getTodayCount($eventId, $date),
        'food'  => $cookedFood ? [
            'name'      => $cookedFood['item_name'],
            'remaining' => $cookedFood['quantity'],
            'unit_type' => $cookedFood['unit_type']
        ] : null
    ]);
}

public function listJson()
{
    $eventId = $this->request->getGet('event_id');
    $date    = $this->request->getGet('date'); // optional, model defaults to today
    $model = new KitchenDistributionModel();
    $rows = $model->getRecentByEvent($eventId, 100, $date);

    return $this->response->setJSON(['status' => 'success', 'data' => $rows]);
}

    /**
     * Admin-side live monitoring page for Smart Mobile Kitchen distribution.
     * Read-only — pulls from the same today-stats / list-json endpoints
     * used by the scanner page.
     */
    public function monitor()
    {
        $data['active_events'] = $this->eventModel->getActiveEvents();
        return view('scanner/kitchen_monitor', $data);
    }

    private function processGuestScan($activeEvent, $isAjax)
    {
        $inventoryModel = new \App\Models\InventoryModel();
        $kitchenModel   = new KitchenDistributionModel();
        $db             = \Config\Database::connect();

        $cookedFood = $inventoryModel->getActiveCookedFood();

        if (!$cookedFood) {
            $message = 'No cooked food item has been set up in inventory yet.';
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => $message]);
            }
            return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
        }

        $allocation = 1;

        if ($cookedFood['quantity'] < $allocation) {
            $message = 'Insufficient cooked food stock remaining for this claim.';
            if ($isAjax) {
                return $this->response->setJSON(['status' => 'error', 'message' => $message]);
            }
            return redirect()->to('/smart-kitchen/scanner')->with('error', $message);
        }

        $db->transStart();

        $guestNumber = $kitchenModel->getNextGuestNumber($activeEvent['id']);
        $guestLabel  = 'Guest ' . $guestNumber;

        $kitchenModel->insert([
            'event_id'          => $activeEvent['id'],
            'resident_id'       => null,
            'family_member_id'  => null,
            'distributor_id'    => session()->get('id'),
            'claimant_type'     => 'guest',
            'qr_code_scanned'   => self::GUEST_QR_TOKEN,
            'distribution_date' => date('Y-m-d H:i:s'),
            'status'            => 'completed',
            'remarks'           => $guestLabel
        ]);

        $inventoryModel->deductCookedFoodStock($cookedFood['id'], $allocation);

        $db->transComplete();

        $message = 'Meal successfully recorded for ' . $guestLabel;

        if ($isAjax) {
            return $this->response->setJSON([
                'status'      => 'success',
                'message'     => $message,
                'event'       => $activeEvent,
                'claimed_at'  => date('Y-m-d H:i:s'),
                'item'        => [
                    'name'      => $cookedFood['item_name'],
                    'quantity'  => $allocation,
                    'unit_type' => $cookedFood['unit_type']
                ],
                'guest'       => [
                    'label'  => $guestLabel,
                    'number' => $guestNumber
                ]
            ]);
        }

        return view('distribution/scan_result', [
            'status'  => 'success',
            'message' => $message,
            'event'   => $activeEvent
        ]);
    }

    /**
     * Live activity feed for the monitor popup — includes both
     * successful claims and duplicate-attempt denials.
     */
    public function recentActivity()
    {
        $eventId = $this->request->getGet('event_id');
        $date    = $this->request->getGet('date');
        $model = new KitchenDistributionModel();
        $rows = $model->getRecentActivity($eventId, 20, $date);

        return $this->response->setJSON(['status' => 'success', 'data' => $rows]);
    }
}