<?php

namespace App\Models;

use CodeIgniter\Model;

class FamilyMemberModel extends Model
{
    protected $table = 'family_members';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'resident_id',
        'member_id',
        'name',
        'relation',
        'birthdate',
        'age',
        'sex',
        'education',
        'occupation',
        'remarks',
        'photo',
        'id_photo_front',
        'id_photo_back',
        'birth_certificate',
        'qr_code_token',
        'qr_code_id',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'resident_id' => 'required|numeric',
        'name' => 'required|min_length[3]|max_length[255]',
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Generate unique QR code token for a family member
     */
    public function generateQRToken($residentId, $memberName)
    {
        $uniqueString = $residentId . '_' . time() . '_' . $memberName . '_' . bin2hex(random_bytes(8));
        return hash('sha256', $uniqueString);
    }

    /**
     * Get all family members for a specific resident
     */
    public function getByResidentId($residentId)
    {
        return $this->where('resident_id', $residentId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get family member by QR token
     */
    public function getByQRToken($token)
    {
        return $this->where('qr_code_token', $token)->first();
    }

    /**
     * Delete all family members for a resident
     */
    public function deleteByResidentId($residentId)
    {
        return $this->where('resident_id', $residentId)->delete();
    }

    /**
 * Generate member ID for a family member
 * Format: HouseholdNo-sequential number (e.g., 0001-01, 0001-02)
 * 
 * @param string $householdNo The household number of the head
 * @return string Formatted member ID
 */
public function generateMemberId($householdNo)
{
    // Get the last member for this household
    $lastMember = $this->select('member_id')
                       ->like('member_id', $householdNo . '-', 'after')
                       ->orderBy('member_id', 'DESC')
                       ->first();
    
    if ($lastMember && !empty($lastMember['member_id'])) {
        // Extract the sequence number and increment
        $parts = explode('-', $lastMember['member_id']);
        $lastSequence = (int)end($parts);
        $nextSequence = $lastSequence + 1;
    } else {
        // Start at 01 for first family member
        $nextSequence = 1;
    }
    
    // Format as HouseholdNo-XX
    return $householdNo . '-' . str_pad($nextSequence, 2, '0', STR_PAD_LEFT);
}
}