<?php
// app/Models/AttendanceModel.php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
// In AttendanceModel.php, update the allowedFields array:
protected $allowedFields = [
    'event_id',              // ADD THIS LINE
    'resident_id',
    'family_member_id',
    'attendance_date',
    'check_in_time',
    'check_out_time',
    'purpose',
    'scanned_by',
    'qr_token_used',
    'status',
    'remarks'
];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'resident_id' => 'permit_empty|numeric',
        'family_member_id' => 'permit_empty|numeric',
        'attendance_date' => 'required|valid_date',
        'check_in_time' => 'required',
    ];

    /**
     * Get attendance with resident details
     */
public function getAttendanceWithDetails($date = null, $barangay = null, $eventId = null)
{
    $builder = $this->db->table('attendance a');
    $builder->select('a.*, 
                      r.household_no, 
                      r.first_name, 
                      r.last_name, 
                      r.barangay,
                      r.contact_number,
                      r.photo as head_photo,
                      fm.name as family_member_name,
                      fm.relation,
                      fm.photo as member_photo,
                      u.username as scanned_by_name')
            ->join('residents r', 'r.id = a.resident_id', 'left')
            ->join('family_members fm', 'fm.id = a.family_member_id', 'left')
            ->join('users u', 'u.id = a.scanned_by', 'left')
            ->orderBy('a.attendance_date DESC, a.check_in_time DESC');

    if ($eventId) {
        $builder->where('a.event_id', $eventId);
    }

    if ($date) {
        $builder->where('a.attendance_date', $date);
    }

    if ($barangay) {
        $builder->where('r.barangay', $barangay);
    }

    return $builder->get()->getResultArray();
}

public function getTodayCount($barangay = null)
{
    $today = date('Y-m-d');
    $builder = $this->where('attendance_date', $today);
    
    if ($barangay) {
        $builder->join('residents', 'residents.id = attendance.resident_id')
                ->where('residents.barangay', $barangay);
    }
    
    return $builder->countAllResults();
}

    /**
     * Get attendance by date range
     */
    public function getByDateRange($startDate, $endDate, $barangay = null)
    {
        $builder = $this->db->table('attendance a');
        $builder->select('a.*, 
                          r.household_no, 
                          r.first_name, 
                          r.last_name, 
                          r.barangay,
                          fm.name as family_member_name')
                ->join('residents r', 'r.id = a.resident_id', 'left')
                ->join('family_members fm', 'fm.id = a.family_member_id', 'left')
                ->where('a.attendance_date >=', $startDate)
                ->where('a.attendance_date <=', $endDate)
                ->orderBy('a.attendance_date DESC, a.check_in_time DESC');

        if ($barangay) {
            $builder->where('r.barangay', $barangay);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get attendance by resident
     */
    public function getByResident($residentId)
    {
        return $this->where('resident_id', $residentId)
                    ->orWhere('family_member_id IN (SELECT id FROM family_members WHERE resident_id = ' . $residentId . ')')
                    ->orderBy('attendance_date DESC, check_in_time DESC')
                    ->findAll();
    }

/**
 * Check if already checked in today
 */
public function isCheckedInToday($residentId, $familyMemberId = null)
{
    $today = date('Y-m-d');
    
    $this->where('attendance_date', $today)
         ->where('check_out_time IS NULL');
    
    if ($familyMemberId) {
        // Check for specific family member
        $this->where('family_member_id', $familyMemberId);
    } else {
        // Check for head of family only (no family_member_id)
        $this->where('resident_id', $residentId)
             ->where('family_member_id IS NULL');
    }
    
    return $this->first();
}

    /**
     * Check out a resident
     */
    public function checkOut($attendanceId)
    {
        return $this->update($attendanceId, [
            'check_out_time' => date('H:i:s'),
            'status' => 'completed'
        ]);
    }
}