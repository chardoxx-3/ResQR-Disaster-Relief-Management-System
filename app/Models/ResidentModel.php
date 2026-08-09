<?php

namespace App\Models;

use CodeIgniter\Model;

class ResidentModel extends Model
{
    protected $table            = 'residents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
protected $allowedFields    = [
    // Location of Affected Family
    'region',
    'province',
    'district',
    'city_municipality',
    'barangay',
    'evacuation_center',
    
    // Head of Family Information
    'household_no',
    'last_name',
    'first_name',
    'middle_name',
    'name_extension',
    'birthdate',
    'age',
    'birthplace',
    'sex',
    'civil_status',
    'mother_maiden_name',
    'religion',
    'occupation',
    'monthly_income',
    'id_card_presented',
    'id_card_number',
    'id_picture_front',
    'id_picture_back',
    'contact_number',
    'alternate_number',
    
    // Permanent Address
    'house_no',
    'street',
    'subdivision',
    'permanent_barangay',
    'permanent_city',
    'permanent_province',
    'zip_code',
    
    // Additional Information
    'is_4ps_beneficiary',
    'ip_ethnicity',
    
    // Vulnerable Members
    'vulnerable_older_persons',
    'vulnerable_pregnant',
    'vulnerable_lactating',
    'vulnerable_pwd',
    
    // REMOVE THIS LINE - 'shelter_damage',   <--- Delete this line
    
    // Ownership Status
    'ownership_status',
    
    // Authentication Fields
    'signature_thumbmark',
    'right_thumbmark',
    'registration_date',
    'barangay_captain_name',
    'barangay_captain_signature',
    'lswdo_name',
    'lswdo_signature',
    
    // Data Privacy
    'data_privacy_consent',
    'data_privacy_date',
    
    // Original fields
    'qr_code_token',
    'household_qr_token',
    'status',
    'photo'
];
    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
protected $validationRules = [
    'household_no' => 'permit_empty|is_unique[residents.household_no,id,{id}]',
    'last_name'    => 'required',
    'first_name'   => 'required',
    'birthdate'    => 'permit_empty|valid_date',
];

    /**
     * Calculate age based on birthdate
     */
    public function calculateAge($birthdate)
    {
        if (empty($birthdate)) return null;
        
        $birthDate = new \DateTime($birthdate);
        $today = new \DateTime('today');
        return $birthDate->diff($today)->y;
    }

    /**
     * Check if a resident has already claimed their relief
     */
    public function hasClaimed($id)
    {
        $resident = $this->find($id);
        return ($resident && $resident['status'] === 'claimed');
    }
    
    /**
     * Get residents by barangay
     */
    public function getByBarangay($barangay)
    {
        return $this->where('barangay', $barangay)->findAll();
    }
    
    /**
     * Get vulnerable families count
     */
    public function getVulnerableFamiliesCount()
    {
        return $this->where('vulnerable_older_persons >', 0)
                    ->orWhere('vulnerable_pregnant >', 0)
                    ->orWhere('vulnerable_lactating >', 0)
                    ->orWhere('vulnerable_pwd >', 0)
                    ->countAllResults();
    }

    // Add this method to your ResidentModel.php
/**
 * Generate unique QR code token for a family member
 * 
 * @param int $residentId The resident/head ID
 * @param int $memberIndex The index of the family member
 * @param string $memberName The name of the family member
 * @return string Unique token
 */
public function generateMemberQRToken($residentId, $memberIndex, $memberName)
{
    // Create a unique token combining resident ID, member index, timestamp, and random bytes
    $uniqueString = $residentId . '_' . $memberIndex . '_' . time() . '_' . $memberName;
    return hash('sha256', $uniqueString . bin2hex(random_bytes(8)));
}

/**
 * Get all family members with their QR codes for a specific resident
 * 
 * @param int $residentId
 * @return array
 */
public function getFamilyMembersWithQR($residentId)
{
    $resident = $this->find($residentId);
    if (empty($resident) || empty($resident['family_members'])) {
        return [];
    }
    
    $familyMembers = json_decode($resident['family_members'], true);
    $membersWithQR = [];
    
    foreach ($familyMembers as $index => $member) {
        // If member doesn't have a QR token yet, generate one
        if (!isset($member['qr_code_token'])) {
            $member['qr_code_token'] = $this->generateMemberQRToken($residentId, $index, $member['name'] ?? '');
            $member['qr_code_id'] = $residentId . '_' . $index; // Unique identifier
        }
        $membersWithQR[] = $member;
    }
    
    return $membersWithQR;
}

/**
 * Generate the next household number
 * Format: 4-digit sequential number (0001, 0002, etc.)
 */
public function generateHouseholdNo()
{
    // Use a raw query to get the max numeric value
    $db = \Config\Database::connect();
    $query = $db->query("SELECT MAX(CAST(household_no AS UNSIGNED)) as max_no FROM residents WHERE household_no REGEXP '^[0-9]+$'");
    $result = $query->getRow();
    
    if ($result && $result->max_no) {
        $nextNumber = (int)$result->max_no + 1;
    } else {
        // Start from 1 if no existing records
        $nextNumber = 1;
    }
    
    // Format as 4-digit with leading zeros
    return str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
            
            // Get claimed count for this barangay
            $claimedCount = $db->table('distribution_logs d')
                ->join('residents r2', 'r2.id = d.resident_id')
                ->where('r2.barangay', $row['barangay'])
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

public function isDuplicateName($firstName, $lastName, $excludeId = null)
{
    $builder = $this->db->table('residents')
        ->where('first_name', $firstName)
        ->where('last_name', $lastName);
    
    if ($excludeId) {
        $builder->where('id !=', $excludeId);
    }
    
    return $builder->countAllResults() > 0;
}

/**
 * Find duplicate residents by first name and last name
 * 
 * @return array Array of duplicate records grouped by name
 */
public function findDuplicates()
{
    $db = \Config\Database::connect();
    
    // Find names that appear more than once
    $duplicateNames = $db->table('residents')
        ->select('first_name, last_name, COUNT(*) as count')
        ->groupBy('first_name, last_name')
        ->having('COUNT(*) > 1')
        ->get()
        ->getResultArray();
    
    if (empty($duplicateNames)) {
        return [];
    }
    
    $duplicates = [];
    foreach ($duplicateNames as $dup) {
        // Get all records with this name
        $records = $this->where('first_name', $dup['first_name'])
            ->where('last_name', $dup['last_name'])
            ->orderBy('id', 'ASC')
            ->findAll();
        
        $duplicates[] = [
            'first_name' => $dup['first_name'],
            'last_name' => $dup['last_name'],
            'count' => $dup['count'],
            'records' => $records
        ];
    }
    
    return $duplicates;
}
}