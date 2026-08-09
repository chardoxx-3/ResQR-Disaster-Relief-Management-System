<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\InventoryModel;
use App\Models\DistributionModel;

class Dashboard extends BaseController
{
public function index()
{
    $userRole = session()->get('role');
    
    // Distributors go straight to scanner
    if ($userRole == 'distributor') {
        return redirect()->to('/distribution/scanner');
    }
    
    // Admin and Barangay users go to dashboard
    $resModel = new ResidentModel();
    $invModel = new InventoryModel();
    $attendanceModel = new \App\Models\AttendanceModel(); // ADD THIS LINE
    $db = \Config\Database::connect();

    $data = [];

    // If barangay user, filter data by their barangay
    if ($userRole == 'barangay') {
        $barangay = session()->get('barangay');
        
        $data['total_residents'] = $resModel->where('barangay', $barangay)->countAllResults();
        
        // Count DISTINCT residents who have claimed
        $query = $db->query("
            SELECT COUNT(DISTINCT bd.resident_id) as total 
            FROM batch_distributions bd
            JOIN residents r ON r.id = bd.resident_id
            WHERE r.barangay = ?
        ", [$barangay]);
        $data['total_distributed'] = $query->getRow()->total;
        
        // Get UNIQUE residents with their MOST RECENT distribution
        // First, get the latest distribution date for each resident
        $latestQuery = $db->query("
            SELECT resident_id, MAX(distribution_date) as latest_date
            FROM batch_distributions
            WHERE resident_id IS NOT NULL
            GROUP BY resident_id
        ");
        $latest = $latestQuery->getResultArray();
        
        $recentClaims = [];
        foreach ($latest as $item) {
            // Get the full record for each resident's latest distribution
            $detailQuery = $db->query("
                SELECT bd.*, 
                       r.full_name as resident_name, 
                       r.household_no, 
                       r.id as resident_id, 
                       u.username as distributor_name
                FROM batch_distributions bd
                JOIN residents r ON r.id = bd.resident_id
                JOIN users u ON u.id = bd.distributor_id
                WHERE bd.resident_id = ? AND bd.distribution_date = ?
                AND r.barangay = ?
            ", [$item['resident_id'], $item['latest_date'], $barangay]);
            
            $result = $detailQuery->getRowArray();
            if ($result) {
                $recentClaims[] = $result;
            }
        }
        
        // Sort by latest date and limit to 5
        usort($recentClaims, function($a, $b) {
            return strtotime($b['distribution_date']) - strtotime($a['distribution_date']);
        });
        $data['recent_claims'] = array_slice($recentClaims, 0, 5);
        
        // ADD THIS - Get recent attendance for barangay user
        $data['recent_attendance'] = $attendanceModel
            ->select('attendance.*, residents.first_name, residents.last_name, residents.household_no, residents.barangay, family_members.name as family_member_name')
            ->join('residents', 'residents.id = attendance.resident_id')
            ->join('family_members', 'family_members.id = attendance.family_member_id', 'left')
            ->where('residents.barangay', $barangay)
            ->orderBy('attendance.attendance_date DESC, attendance.check_in_time DESC')
            ->limit(5)
            ->findAll();
        
    } else {
        // Admin sees all data
        $data['total_residents'] = $resModel->countAllResults();
        
        // Count DISTINCT residents who have claimed
        $query = $db->query("
            SELECT COUNT(DISTINCT resident_id) as total 
            FROM batch_distributions
            WHERE resident_id IS NOT NULL
        ");
        $data['total_distributed'] = $query->getRow()->total;
        
        // Get UNIQUE residents with their MOST RECENT distribution
        // First, get the latest distribution date for each resident
        $latestQuery = $db->query("
            SELECT resident_id, MAX(distribution_date) as latest_date
            FROM batch_distributions
            WHERE resident_id IS NOT NULL
            GROUP BY resident_id
        ");
        $latest = $latestQuery->getResultArray();
        
        $recentClaims = [];
        foreach ($latest as $item) {
            // Get the full record for each resident's latest distribution
            $detailQuery = $db->query("
                SELECT bd.*, 
                       r.full_name as resident_name, 
                       r.household_no, 
                       r.id as resident_id, 
                       u.username as distributor_name
                FROM batch_distributions bd
                JOIN residents r ON r.id = bd.resident_id
                JOIN users u ON u.id = bd.distributor_id
                WHERE bd.resident_id = ? AND bd.distribution_date = ?
            ", [$item['resident_id'], $item['latest_date']]);
            
            $result = $detailQuery->getRowArray();
            if ($result) {
                $recentClaims[] = $result;
            }
        }
        
        // Sort by latest date and limit to 5
        usort($recentClaims, function($a, $b) {
            return strtotime($b['distribution_date']) - strtotime($a['distribution_date']);
        });
        $data['recent_claims'] = array_slice($recentClaims, 0, 5);
        
        // ADD THIS - Get recent attendance for admin
        $data['recent_attendance'] = $attendanceModel
            ->select('attendance.*, residents.first_name, residents.last_name, residents.household_no, residents.barangay, family_members.name as family_member_name')
            ->join('residents', 'residents.id = attendance.resident_id')
            ->join('family_members', 'family_members.id = attendance.family_member_id', 'left')
            ->orderBy('attendance.attendance_date DESC, attendance.check_in_time DESC')
            ->limit(5)
            ->findAll();
    }
    
    $data['inventory_items'] = $invModel->findAll();
    
    // Get barangay distribution data for the chart
    $barangayData = $resModel->getDistributionByBarangay($userRole, session()->get('barangay'));
    $data['barangay_labels'] = json_encode($barangayData['labels']);
    $data['barangay_registered'] = json_encode($barangayData['registered']);
    $data['barangay_claimed'] = json_encode($barangayData['claimed']);

    return view('dashboard/index', $data);
}

/**
 * Get distribution statistics by barangay
 * 
 * @param string|null $userRole The role of the logged-in user
 * @param string|null $userBarangay The barangay of the logged-in user (for barangay role)
 * @return array Array with barangay names, registered counts, and claimed counts
 */
public function getDistributionByBarangay($userRole = null, $userBarangay = null)
{
    $db = \Config\Database::connect();
    
    // Base query
    $builder = $db->table('residents r')
        ->select('r.barangay, COUNT(r.id) as registered')
        ->groupBy('r.barangay')
        ->orderBy('r.barangay');
    
    // If user is barangay, filter by their barangay only
    if ($userRole == 'barangay' && $userBarangay) {
        $builder->where('r.barangay', $userBarangay);
    }
    
    $results = $builder->get()->getResultArray();
    
    // Prepare data arrays
    $barangays = [];
    $registered = [];
    $claimed = [];
    
    foreach ($results as $row) {
        if (!empty($row['barangay'])) {
            $barangays[] = $row['barangay'];
            $registered[] = (int)$row['registered'];
            
            // FIX: Get claimed count from batch_distributions table (DISTINCT residents)
            $claimedCount = $db->table('batch_distributions bd')
                ->join('residents r2', 'r2.id = bd.resident_id')
                ->where('r2.barangay', $row['barangay'])
                ->select('DISTINCT bd.resident_id')
                ->countAllResults();
            
            $claimed[] = $claimedCount;
        }
    }
    
    // If no barangays found, provide fallback data
    if (empty($barangays)) {
        return [
            'labels' => ['No Data'],
            'registered' => [0],
            'claimed' => [0]
        ];
    }
    
    return [
        'labels' => $barangays,
        'registered' => $registered,
        'claimed' => $claimed
    ];
}
}