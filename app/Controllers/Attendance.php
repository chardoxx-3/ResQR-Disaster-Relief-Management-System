<?php
// app/Controllers/Attendance.php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;

class Attendance extends BaseController
{
    protected $attendanceModel;
    protected $residentModel;
    protected $familyMemberModel;
    protected $eventModel;

public function __construct()
{
    $this->attendanceModel = new AttendanceModel();
    $this->residentModel = new ResidentModel();
    $this->familyMemberModel = new FamilyMemberModel();
    $this->eventModel = new \App\Models\EventModel(); // ADD THIS LINE
}

public function index()
{
    $date = $this->request->getGet('date') ?? date('Y-m-d');
    $barangay = $this->request->getGet('barangay');
    $status = $this->request->getGet('status');
    $search = $this->request->getGet('search');
    $eventId = $this->request->getGet('event_id') ?: null;              // NEW: event filter
    $viewMode = $this->request->getGet('view') ?: 'latest';             // NEW: latest (default) vs all

    // If user is barangay role, force filter to their barangay
    $userRole = session()->get('role');
    if ($userRole == 'barangay') {
        $barangay = session()->get('barangay');
    }

    // Get pagination settings from request or session
    $perPage = $this->request->getGet('per_page');
    if ($perPage !== null && is_numeric($perPage)) {
        $perPage = (int)$perPage;
        if ($perPage >= 10 && $perPage <= 500) {
            session()->set('attendance_per_page', $perPage);
        } else {
            $perPage = null;
        }
    }
    if ($perPage === null) {
        $perPage = session()->get('attendance_per_page');
        if ($perPage === null || !is_numeric($perPage) || $perPage < 10 || $perPage > 500) {
            $perPage = 50;
            session()->set('attendance_per_page', $perPage);
        } else {
            $perPage = (int)$perPage;
        }
    }

    $currentPage = $this->request->getGet('page');
    $currentPage = ($currentPage !== null && is_numeric($currentPage)) ? (int)$currentPage : 1;
    if ($currentPage < 1) $currentPage = 1;

    // NEW: "latest" mode — only residents with an attendance tap today, newest tap first
    if ($viewMode === 'latest') {
        $tapBuilder = $this->attendanceModel
            ->select('attendance.*')
            ->where('attendance_date', $date)
            ->where('family_member_id', null); // head-level taps only, matches list grouping below

        if ($eventId) {
            $tapBuilder->where('event_id', $eventId);
        }

        if ($barangay) {
            $tapBuilder->join('residents', 'residents.id = attendance.resident_id')
                       ->where('residents.barangay', $barangay);
        }

        $tapBuilder->orderBy('check_in_time', 'DESC');

        $totalResidents = $tapBuilder->countAllResults(false);
        $offset = ($currentPage - 1) * $perPage;
        $taps = $tapBuilder->limit((int)$perPage, (int)$offset)->findAll();

        $residentIds = array_column($taps, 'resident_id');
        $allResidents = empty($residentIds) ? [] : $this->residentModel->whereIn('id', $residentIds)->findAll();

        // Re-order residents to match latest-tap order (whereIn doesn't preserve order)
        $residentsById = array_column($allResidents, null, 'id');
        $allResidents = array_filter(array_map(fn($id) => $residentsById[$id] ?? null, $residentIds));
    } else {
        // Build query for all residents (your original full-list behavior)
        $residentQuery = $this->residentModel->select('residents.*');

        if ($barangay) {
            $residentQuery->where('residents.barangay', $barangay);
        }

        if ($search && !empty($search)) {
            $residentQuery->groupStart()
                ->like('household_no', $search)
                ->orLike('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('barangay', $search)
                ->groupEnd();
        }

        $totalResidents = $residentQuery->countAllResults(false);
        $offset = ($currentPage - 1) * $perPage;
        $allResidents = $residentQuery->orderBy('residents.last_name', 'ASC')
            ->limit((int)$perPage, (int)$offset)
            ->findAll();
    }

    $totalPages = ($perPage > 0) ? ceil($totalResidents / $perPage) : 1;

    $beneficiaries = [];
    foreach ($allResidents as $resident) {
        $attendanceQuery = $this->attendanceModel
            ->where('resident_id', $resident['id'])
            ->where('family_member_id', null)
            ->where('attendance_date', $date);
        if ($eventId) {
            $attendanceQuery->where('event_id', $eventId);   // NEW
        }
        $attendanceToday = $attendanceQuery->first();

        $familyMembers = $this->familyMemberModel->getByResidentId($resident['id']);
        $allFamilyMembersPresent = true;
        $missingFamilyMembers = [];

        foreach ($familyMembers as &$member) {
            $memberQuery = $this->attendanceModel
                ->where('family_member_id', $member['id'])
                ->where('attendance_date', $date);
            if ($eventId) {
                $memberQuery->where('event_id', $eventId);   // NEW
            }
            $memberAttendance = $memberQuery->first();

            $member['checked_in_today'] = !empty($memberAttendance);
            $member['check_in_time'] = $memberAttendance['check_in_time'] ?? null;
            $member['check_out_time'] = $memberAttendance['check_out_time'] ?? null;
            $member['status'] = $memberAttendance['status'] ?? null;
            $member['attendance_id'] = $memberAttendance['id'] ?? null;

            if (!$member['checked_in_today']) {
                $allFamilyMembersPresent = false;
                $missingFamilyMembers[] = $member;
            }
        }

        $headCheckedIn = !empty($attendanceToday);
        $headCheckedOut = $headCheckedIn && !empty($attendanceToday['check_out_time']);

        if ($headCheckedIn && !$headCheckedOut) {
            $headStatus = 'checked_in';
        } elseif ($headCheckedIn && $headCheckedOut) {
            $headStatus = 'checked_out';
        } else {
            $headStatus = 'not_present';
        }

        $beneficiary = [
            'id' => $resident['id'],
            'resident_id' => $resident['id'],
            'household_no' => $resident['household_no'],
            'first_name' => $resident['first_name'],
            'last_name' => $resident['last_name'],
            'photo' => $resident['photo'] ?? null,
            'barangay' => $resident['barangay'],
            'contact_number' => $resident['contact_number'] ?? '—',
            'family_size' => $resident['family_size'] ?? count($familyMembers) + 1,
            'vulnerable_count' => $this->calculateVulnerableCount($resident),
            'checked_in_today' => $headCheckedIn,
            'check_in_time' => $attendanceToday['check_in_time'] ?? null,
            'check_out_time' => $attendanceToday['check_out_time'] ?? null,
            'status' => $attendanceToday['status'] ?? null,
            'attendance_id' => $attendanceToday['id'] ?? null,
            'family_members' => $familyMembers,
            'purpose' => $attendanceToday['purpose'] ?? null,
            'scanned_by_name' => null,
            'head_status' => $headStatus,
            'has_missing_family_members' => !$allFamilyMembersPresent,
            'missing_family_members_count' => count($missingFamilyMembers),
            'missing_family_members' => $missingFamilyMembers
        ];

        $beneficiaries[] = $beneficiary;
    }

    // Status filter only applies in "all" mode (latest mode is already tap-driven)
    if ($status && $viewMode !== 'latest') {
        $beneficiaries = array_filter($beneficiaries, function($beneficiary) use ($status) {
            return $beneficiary['head_status'] === $status;
        });
    }

    $data['attendances'] = $beneficiaries;
    $data['selectedDate'] = $date;
    $data['selectedBarangay'] = $barangay;
    $data['selectedStatus'] = $status;
    $data['search'] = $search;
    $data['selectedEvent'] = $eventId;                                   // NEW
    $data['viewMode'] = $viewMode;                                       // NEW
    $data['events'] = $this->eventModel->orderBy('event_name', 'ASC')->findAll(); // NEW
    $data['todayCount'] = $this->attendanceModel->getTodayCount($barangay);

    $data['totalResidents'] = $totalResidents;
    $data['currentPage'] = $currentPage;
    $data['totalPages'] = $totalPages;
    $data['perPage'] = $perPage;

    if ($userRole == 'admin') {
        $barangays = $this->residentModel->select('barangay')
                                         ->distinct()
                                         ->where('barangay !=', '')
                                         ->findAll();
        $data['barangays'] = array_column($barangays, 'barangay');
    } else {
        $data['barangays'] = [$barangay];
    }

    return view('attendance/index', $data);
}

/**
 * Calculate vulnerable count for a resident
 */
private function calculateVulnerableCount($resident)
{
    $count = 0;
    $count += $resident['vulnerable_older_persons'] ?? 0;
    $count += $resident['vulnerable_pregnant'] ?? 0;
    $count += $resident['vulnerable_lactating'] ?? 0;
    $count += $resident['vulnerable_pwd'] ?? 0;
    return $count;
}
// Also update getTodayCount method in AttendanceModel to accept barangay parameter

    /**
     * Manual check-in (for admin use)
     */
    public function manualCheckIn()
    {
        if ($this->request->getMethod() === 'post') {
            $residentId = $this->request->getPost('resident_id');
            $familyMemberId = $this->request->getPost('family_member_id');
            $purpose = $this->request->getPost('purpose');
            
            // Check if already checked in
            $existing = $this->attendanceModel->isCheckedInToday($residentId, $familyMemberId);
            
            if ($existing) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'This person is already checked in today'
                ]);
            }
            
            $data = [
                'resident_id' => $residentId,
                'family_member_id' => $familyMemberId ?: null,
                'attendance_date' => date('Y-m-d'),
                'check_in_time' => date('H:i:s'),
                'purpose' => $purpose,
                'scanned_by' => session()->get('id'),
                'status' => 'active'
            ];
            
            if ($this->attendanceModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Check-in recorded successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to record check-in'
            ]);
        }
        
        // Load residents for dropdown
        $data['residents'] = $this->residentModel->orderBy('last_name', 'ASC')->findAll();
        return view('attendance/manual_checkin', $data);
    }

    /**
     * Check out a person
     */
    public function checkOut($id)
    {
        if ($this->attendanceModel->checkOut($id)) {
            session()->setFlashdata('success', 'Check-out recorded successfully');
        } else {
            session()->setFlashdata('error', 'Failed to record check-out');
        }
        
        return redirect()->to('/attendance');
    }

    /**
     * Export attendance report
     */
    public function export()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $barangay = $this->request->getGet('barangay');
        
        $attendances = $this->attendanceModel->getByDateRange($startDate, $endDate, $barangay);
        
        // Set filename
        $filename = 'attendance_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'Date',
            'Check In Time',
            'Check Out Time',
            'Household #',
            'Name',
            'Type',
            'Barangay',
            'Purpose',
            'Status'
        ]);
        
        // Add data rows
        foreach ($attendances as $a) {
            $name = $a['first_name'] . ' ' . $a['last_name'];
            if ($a['family_member_name']) {
                $name .= ' - ' . $a['family_member_name'] . ' (' . $a['relation'] . ')';
                $type = 'Family Member';
            } else {
                $type = 'Head of Family';
            }
            
            fputcsv($output, [
                $a['attendance_date'],
                $a['check_in_time'],
                $a['check_out_time'] ?? '—',
                $a['household_no'] ?? '—',
                $name,
                $type,
                $a['barangay'] ?? '—',
                $a['purpose'] ?? '—',
                $a['status'] ?? 'active'
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * QR-based check-in (for scanner)
     */
    public function qrCheckIn()
    {
        $qrToken = $this->request->getVar('qr_token');
        
        if (!$qrToken) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No QR code data received'
            ]);
        }
        
        // Check if it's a resident (head) or family member
        $resident = $this->residentModel->where('qr_code_token', $qrToken)->first();
        $familyMember = null;
        
        if (!$resident) {
            $familyMember = $this->familyMemberModel->where('qr_code_token', $qrToken)->first();
            if ($familyMember) {
                $resident = $this->residentModel->find($familyMember['resident_id']);
            }
        }
        
        if (!$resident) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid QR Code - No matching record found'
            ]);
        }
        
        // Check if already checked in today
        $existing = $this->attendanceModel->isCheckedInToday(
            $resident['id'], 
            $familyMember['id'] ?? null
        );
        
        if ($existing) {
            return $this->response->setJSON([
                'status' => 'already_checked_in',
                'data' => $existing,
                'message' => 'Already checked in at ' . $existing['check_in_time']
            ]);
        }
        
        // Record check-in
        $data = [
            'resident_id' => $resident['id'],
            'family_member_id' => $familyMember['id'] ?? null,
            'attendance_date' => date('Y-m-d'),
            'check_in_time' => date('H:i:s'),
            'purpose' => 'Distribution/Visit',
            'scanned_by' => session()->get('id'),
            'qr_token_used' => $qrToken,
            'status' => 'active'
        ];
        
        if ($this->attendanceModel->insert($data)) {
            $name = $familyMember ? $familyMember['name'] : $resident['first_name'] . ' ' . $resident['last_name'];
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Check-in successful for ' . $name,
                'data' => $data
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to record check-in'
        ]);
    }

public function scanner()
{
    $data['active_events'] = $this->eventModel->getActiveEvents();
    return view('attendance/scanner', $data);
}

    /**
     * Get attendance stats
     */
    public function stats()
    {
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));
        
        $stats = [
            'today' => $this->attendanceModel->getTodayCount(),
            'week' => $this->attendanceModel->where('attendance_date >=', $weekAgo)->countAllResults(),
            'month' => $this->attendanceModel->where('attendance_date >=', date('Y-m-01'))->countAllResults(),
            'active_now' => $this->attendanceModel->where('attendance_date', $today)
                                                  ->where('check_out_time IS NULL')
                                                  ->countAllResults()
        ];
        
        return $this->response->setJSON($stats);
    }

public function qrCheckinRedirect()
{
    $qrToken = $this->request->getVar('qr_token');
    $eventId = $this->request->getVar('event_id');
    
    if (!$qrToken) {
        return redirect()->to('/distribution/scanner')->with('error', 'No QR code data received');
    }
    
    // Process the QR check-in
    $result = $this->processQRCheckin($qrToken, $eventId);
    
    if ($result['status'] === 'success') {
        return view('attendance/scan_result', [
            'status' => 'success',
            'message' => 'Check-in successful',
            'data' => $result['data']
        ]);
    } elseif ($result['status'] === 'already_checked_in') {
        return view('attendance/scan_result', [
            'status' => 'info',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    } else {
        return view('attendance/scan_result', [
            'status' => 'error',
            'message' => $result['message']
        ]);
    }
}

private function processQRCheckin($qrToken, $eventId = null)
{
    // Event now comes from the scanner dropdown, not a single global active event
    $activeEvent = $eventId ? $this->eventModel->find($eventId) : null;
    
    if (!$activeEvent || $activeEvent['is_active'] != 1) {
        return [
            'status' => 'error',
            'message' => 'Please select a valid active event before scanning.'
        ];
    }
    
    // Check if it's a resident (head) or family member
    $resident = $this->residentModel->where('qr_code_token', $qrToken)->first();
    $familyMember = null;
    
    if (!$resident) {
        $familyMember = $this->familyMemberModel->where('qr_code_token', $qrToken)->first();
        if ($familyMember) {
            $resident = $this->residentModel->find($familyMember['resident_id']);
        }
    }
    
    if (!$resident) {
        return [
            'status' => 'error',
            'message' => 'Invalid QR Code - No matching record found'
        ];
    }
    
    // IMPORTANT FIX: Check if this is a family member scan
    $isFamilyMember = !empty($familyMember);
    
    // Check if already checked in today for THIS EVENT
    if ($isFamilyMember) {
        // Check attendance for this SPECIFIC family member in this event
        $existing = $this->attendanceModel
            ->where('family_member_id', $familyMember['id'])
            ->where('event_id', $activeEvent['id'])  // ADD EVENT CONDITION
            ->where('attendance_date', date('Y-m-d'))
            ->first();
    } else {
        // Check attendance for this SPECIFIC resident (head) in this event
        $existing = $this->attendanceModel
            ->where('resident_id', $resident['id'])
            ->where('family_member_id', null)
            ->where('event_id', $activeEvent['id'])  // ADD EVENT CONDITION
            ->where('attendance_date', date('Y-m-d'))
            ->first();
    }
    
    if ($existing) {
        return [
            'status' => 'already_checked_in',
            'message' => 'Already checked in at ' . $existing['check_in_time'] . ' for event: ' . $activeEvent['event_name'],
            'data' => $existing
        ];
    }
    
    // Record check-in - ONLY for the specific person
    $data = [
        'event_id' => $activeEvent['id'],  // ADD THIS LINE
        'resident_id' => $resident['id'],
        'family_member_id' => $isFamilyMember ? $familyMember['id'] : null,
        'attendance_date' => date('Y-m-d'),
        'check_in_time' => date('H:i:s'),
        'purpose' => $activeEvent['event_name'],  // Use event name as purpose
        'scanned_by' => session()->get('id'),
        'qr_token_used' => $qrToken,
        'status' => 'active'
    ];
    
    if ($this->attendanceModel->insert($data)) {
        $name = $isFamilyMember ? $familyMember['name'] : $resident['first_name'] . ' ' . $resident['last_name'];
        
        return [
            'status' => 'success',
            'message' => 'Check-in successful for ' . $name . ' (Event: ' . $activeEvent['event_name'] . ')',
            'data' => array_merge($data, ['name' => $name]),
            'event' => $activeEvent
        ];
    }
    
    return [
        'status' => 'error',
        'message' => 'Failed to record check-in'
    ];
}

public function processScan()
{
    $qrToken = $this->request->getGet('qr_token');
    $eventId = $this->request->getGet('event_id');
    
    if (!$qrToken) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No QR code provided'
        ]);
    }
    
    if (!$eventId) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Please select an event before scanning.'
        ]);
    }
    
    $activeEvent = $this->eventModel->find($eventId);
    
    if (!$activeEvent || $activeEvent['is_active'] != 1) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Selected event is not active. Please choose a valid event.'
        ]);
    }
    
    // Process the QR check-in using your existing logic
    $result = $this->processQRCheckin($qrToken, $eventId);
    
    // Add event info to result
    $result['active_event'] = $activeEvent;
    
    // If successful or already checked in, enhance the result with more data
    if (in_array($result['status'], ['success', 'already_checked_in'])) {
        // Get the resident ID and family member ID from the result
        $residentId = $result['data']['resident_id'] ?? null;
        $familyMemberId = $result['data']['family_member_id'] ?? null;
        
        if ($residentId) {
            // Get resident details (head of family)
            $resident = $this->residentModel->find($residentId);
            
            // Add resident details to result
            $result['resident_details'] = [
                'id' => $resident['id'],
                'household_no' => $resident['household_no'] ?? 'N/A',
                'barangay' => $resident['barangay'] ?? 'N/A',
                'head_name' => trim(($resident['first_name'] ?? '') . ' ' . ($resident['last_name'] ?? '')),
                'photo' => $resident['photo'] ?? null,
                'is_4ps_beneficiary' => $resident['is_4ps_beneficiary'] ?? false,
                'ip_ethnicity' => $resident['ip_ethnicity'] ?? null
            ];
            
            // Get ALL family members
            $familyMemberModel = new \App\Models\FamilyMemberModel();
            $allFamilyMembers = $familyMemberModel->getByResidentId($residentId);
            
            // Track attendance status for ALL family members AND the head for THIS EVENT
            $today = date('Y-m-d');
            $attendanceRecords = [];
            
            // Get all attendance records for this household today for the active event
            $allAttendances = $this->attendanceModel
                ->where('resident_id', $residentId)
                ->where('event_id', $activeEvent['id'])  // FILTER BY ACTIVE EVENT
                ->where('attendance_date', $today)
                ->findAll();
            
            // Index attendance records by family_member_id (null for head)
            foreach ($allAttendances as $att) {
                if ($att['family_member_id']) {
                    $attendanceRecords[$att['family_member_id']] = $att;
                } else {
                    $attendanceRecords['head'] = $att;
                }
            }
            
            // Create head of family entry for the family members list
            $headEntry = [
                'id' => $resident['id'],
                'name' => trim(($resident['first_name'] ?? '') . ' ' . ($resident['last_name'] ?? '')),
                'name_extension' => $resident['name_extension'] ?? '',
                'relation' => 'Head of Family',
                'birthdate' => $resident['birthdate'] ?? null,
                'age' => $resident['age'] ?? null,
                'sex' => $resident['sex'] ?? 'N/A',
                'photo' => $resident['photo'] ?? null,
                'is_head' => true,
                'checked_in_today' => !empty($attendanceRecords['head']),
                'check_in_time' => $attendanceRecords['head']['check_in_time'] ?? null,
                'check_out_time' => $attendanceRecords['head']['check_out_time'] ?? null,
                'status' => $attendanceRecords['head']['status'] ?? null,
                'attendance_id' => $attendanceRecords['head']['id'] ?? null,
                'is_scanned_person' => (!$familyMemberId) // True if head was scanned
            ];
            
            // Add attendance status for family members
            $enhancedFamilyMembers = [];
            foreach ($allFamilyMembers as $member) {
                $memberAttendance = $attendanceRecords[$member['id']] ?? null;
                
                $enhancedMember = $member;
                $enhancedMember['checked_in_today'] = !empty($memberAttendance);
                $enhancedMember['check_in_time'] = $memberAttendance['check_in_time'] ?? null;
                $enhancedMember['check_out_time'] = $memberAttendance['check_out_time'] ?? null;
                $enhancedMember['status'] = $memberAttendance['status'] ?? null;
                $enhancedMember['attendance_id'] = $memberAttendance['id'] ?? null;
                $enhancedMember['is_head'] = false;
                
                // Mark if this is the scanned family member
                $enhancedMember['is_scanned_person'] = ($familyMemberId && $member['id'] == $familyMemberId);
                
                $enhancedFamilyMembers[] = $enhancedMember;
            }
            
            // Combine head and family members into one array
            $allHouseholdMembers = array_merge([$headEntry], $enhancedFamilyMembers);
            
            $result['family_members'] = $allHouseholdMembers;
            
            // If this is a family member scan, get their specific details
            if ($familyMemberId) {
                $familyMember = $familyMemberModel->find($familyMemberId);
                
                if ($familyMember) {
                    // Get this specific family member's attendance
                    $memberAttendance = $attendanceRecords[$familyMemberId] ?? null;
                    
                    $result['person_details'] = [
                        'id' => $familyMember['id'],
                        'name' => $familyMember['name'] ?? '',
                        'relation' => $familyMember['relation'] ?? 'Family Member',
                        'birthdate' => $familyMember['birthdate'] ?? null,
                        'age' => $familyMember['age'] ?? null,
                        'sex' => $familyMember['sex'] ?? 'N/A',
                        'photo' => $familyMember['photo'] ?? null,
                        'occupation' => $familyMember['occupation'] ?? 'N/A',
                        'education' => $familyMember['education'] ?? 'N/A',
                        'is_family_member' => true,
                        'ip_ethnicity' => $familyMember['ip_ethnicity'] ?? null,
                        'is_scanned_person' => true,
                        'checked_in_today' => !empty($memberAttendance),
                        'check_in_time' => $memberAttendance['check_in_time'] ?? null
                    ];
                }
            } else {
                // This is a head of family scan
                $headAttendance = $attendanceRecords['head'] ?? null;
                
                $result['person_details'] = [
                    'id' => $resident['id'],
                    'name' => trim(($resident['first_name'] ?? '') . ' ' . ($resident['last_name'] ?? '')),
                    'name_extension' => $resident['name_extension'] ?? '',
                    'relation' => 'Head of Family',
                    'birthdate' => $resident['birthdate'] ?? null,
                    'age' => $resident['age'] ?? null,
                    'sex' => $resident['sex'] ?? 'N/A',
                    'civil_status' => $resident['civil_status'] ?? 'N/A',
                    'photo' => $resident['photo'] ?? null,
                    'occupation' => $resident['occupation'] ?? 'N/A',
                    'contact_number' => $resident['contact_number'] ?? 'N/A',
                    'is_4ps_beneficiary' => $resident['is_4ps_beneficiary'] ?? 'No',
                    'ip_ethnicity' => $resident['ip_ethnicity'] ?? 'N/A',
                    'is_family_member' => false,
                    'is_scanned_person' => true,
                    'checked_in_today' => !empty($headAttendance),
                    'check_in_time' => $headAttendance['check_in_time'] ?? null
                ];
                
                // Add vulnerable counts if available
                $vulnerableCount = 0;
                $vulnerableCount += $resident['vulnerable_older_persons'] ?? 0;
                $vulnerableCount += $resident['vulnerable_pregnant'] ?? 0;
                $vulnerableCount += $resident['vulnerable_lactating'] ?? 0;
                $vulnerableCount += $resident['vulnerable_pwd'] ?? 0;
                
                $result['person_details']['vulnerable_count'] = $vulnerableCount;
                
                // Add additional resident fields
                $result['person_details']['mother_maiden_name'] = $resident['mother_maiden_name'] ?? null;
                $result['person_details']['religion'] = $resident['religion'] ?? null;
                $result['person_details']['birthplace'] = $resident['birthplace'] ?? null;
                $result['person_details']['monthly_income'] = $resident['monthly_income'] ?? null;
                $result['person_details']['id_card_presented'] = $resident['id_card_presented'] ?? null;
                $result['person_details']['id_card_number'] = $resident['id_card_number'] ?? null;
                $result['person_details']['house_no'] = $resident['house_no'] ?? null;
                $result['person_details']['street'] = $resident['street'] ?? null;
                $result['person_details']['subdivision'] = $resident['subdivision'] ?? null;
                $result['person_details']['permanent_barangay'] = $resident['permanent_barangay'] ?? null;
                $result['person_details']['permanent_city'] = $resident['permanent_city'] ?? null;
                $result['person_details']['ownership_status'] = $resident['ownership_status'] ?? null;
                $result['person_details']['vulnerable_older_persons'] = $resident['vulnerable_older_persons'] ?? 0;
                $result['person_details']['vulnerable_pregnant'] = $resident['vulnerable_pregnant'] ?? 0;
                $result['person_details']['vulnerable_lactating'] = $resident['vulnerable_lactating'] ?? 0;
                $result['person_details']['vulnerable_pwd'] = $resident['vulnerable_pwd'] ?? 0;
            }
            
            // Get scanned by info
            if (isset($result['data']['scanned_by'])) {
                $userModel = new \App\Models\UserModel();
                $scanner = $userModel->find($result['data']['scanned_by']);
                $result['scanned_by_name'] = $scanner ? ($scanner['username'] ?? 'Unknown') : 'Unknown';
            }
            
            // Get check-in time
            $result['check_in_time'] = $result['data']['check_in_time'] ?? date('H:i:s');
            
            // Add event details
            $result['event_details'] = [
                'id' => $activeEvent['id'],
                'name' => $activeEvent['event_name'],
                'type' => $activeEvent['event_type'] ?? 'disaster',
                'description' => $activeEvent['description'] ?? '',
                'location' => $activeEvent['location'] ?? '',
                'start_date' => $activeEvent['start_date'] ?? '',
                'end_date' => $activeEvent['end_date'] ?? ''
            ];
        }
    }
    
    return $this->response->setJSON($result);
}

public function listJson()
{
    $eventId = $this->request->getGet('event_id');
    $model = new \App\Models\AttendanceModel();
    $rows = $model->getAttendanceWithDetails(date('Y-m-d'), null, $eventId);

    return $this->response->setJSON(['status' => 'success', 'data' => $rows]);
}
}