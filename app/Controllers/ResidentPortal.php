<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;

class ResidentPortal extends BaseController
{
    protected $residentModel;
    protected $familyMemberModel;

    public function __construct()
    {
        $this->residentModel = new ResidentModel();
        $this->familyMemberModel = new FamilyMemberModel();
    }

    public function index()
    {
        return view('portal/search');
    }

public function verify()
{
    $model = new ResidentModel();
    $familyMemberModel = new FamilyMemberModel();
    
    $name = $this->request->getVar('search_name');
    
    // Validate name is provided
    if (empty($name)) {
        return redirect()->to('/residentportal')->with('error', 'Please provide your name.');
    }
    
    // Search for head of family (resident)
    $resident = $model->groupStart()
                        ->where('first_name', $name)
                        ->orWhere('last_name', $name)
                        ->orWhere("CONCAT(first_name, ' ', last_name)", $name)
                        ->orWhere("CONCAT(last_name, ' ', first_name)", $name)
                      ->groupEnd()
                      ->first();
    
    if ($resident) {
        // Found as head of family
        return redirect()->to('/residentportal/generateQR/' . $resident['id']);
    }
    
    // If not found as head, search in family members
    $familyMember = $familyMemberModel->groupStart()
                                        ->where('name', $name)
                                        ->orWhere('name LIKE', '%' . $name . '%')
                                      ->groupEnd()
                                      ->first();
    
    if ($familyMember) {
        // Found as family member - redirect to their QR code
        return redirect()->to('/residentportal/view-member-qr/' . $familyMember['resident_id'] . '/' . $familyMember['id']);
    }
    
    // No match found
    return redirect()->to('/residentportal')->with('error', 'No record found matching your name.');
}

    /**
     * Generate QR code for head of family
     */
    public function generateQR($id)
    {
        $resident = $this->residentModel->find($id);
        
        if (!$resident) {
            return redirect()->to('/residentportal')->with('error', 'Resident not found.');
        }

        return view('residentportal/view_qr', ['resident' => $resident]);
    }

    /**
     * View QR code for family member
     */
    public function viewMemberQR($residentId, $memberId)
    {
        $resident = $this->residentModel->find($residentId);
        $member = $this->familyMemberModel->find($memberId);
        
        if (!$resident || !$member || $member['resident_id'] != $residentId) {
            return redirect()->to('/residentportal')->with('error', 'Family member not found.');
        }
        
        // Get all family members to find the index
        $allMembers = $this->familyMemberModel->getByResidentId($residentId);
        $member_index = array_search($memberId, array_column($allMembers, 'id'));
        
        $data = [
            'resident' => $resident,
            'member' => $member,
            'full_name' => $member['name'],
            'qr_token' => $member['qr_code_token'],
            'household_no' => $resident['household_no'] ?? '',
            'head_name' => trim($resident['first_name'] . ' ' . $resident['last_name']),
            'member_index' => $member_index !== false ? $member_index : 0
        ];
        
        return view('residentportal/member_qr_view', $data);
    }

    /**
     * Print QR code for family member
     */
    public function printMemberQR($residentId, $memberId)
    {
        $resident = $this->residentModel->find($residentId);
        $member = $this->familyMemberModel->find($memberId);
        
        if (!$resident || !$member || $member['resident_id'] != $residentId) {
            return redirect()->to('/residentportal')->with('error', 'Family member not found.');
        }
        
        // Get all family members to find the index
        $allMembers = $this->familyMemberModel->getByResidentId($residentId);
        $member_index = array_search($memberId, array_column($allMembers, 'id'));
        
        $data = [
            'resident' => $resident,
            'member' => $member,
            'full_name' => $member['name'],
            'qr_token' => $member['qr_code_token'],
            'household_no' => $resident['household_no'] ?? '',
            'head_name' => trim($resident['first_name'] . ' ' . $resident['last_name']),
            'member_index' => $member_index !== false ? $member_index : 0
        ];
        
        return view('residentportal/member_qr_print', $data);
    }

    /**
     * Process QR code scan (for scanner)
     */
    public function processScan()
    {
        $token = $this->request->getGet('token');
        
        if (!$token) {
            return $this->response->setJSON(['error' => 'No token provided']);
        }

        // First check if it's a head of family QR
        $resident = $this->residentModel->where('qr_code_token', $token)->first();
        
        if ($resident) {
            return $this->response->setJSON([
                'type' => 'head',
                'data' => [
                    'id' => $resident['id'],
                    'name' => trim($resident['first_name'] . ' ' . $resident['last_name']),
                    'household_no' => $resident['household_no'],
                    'age' => $resident['age'],
                    'sex' => $resident['sex'],
                    'barangay' => $resident['barangay'],
                    'status' => $resident['status']
                ]
            ]);
        }

        // If not head, check if it's a family member QR
        $member = $this->familyMemberModel->where('qr_code_token', $token)->first();
        
        if ($member) {
            $resident = $this->residentModel->find($member['resident_id']);
            
            return $this->response->setJSON([
                'type' => 'member',
                'data' => [
                    'id' => $member['id'],
                    'resident_id' => $member['resident_id'],
                    'name' => $member['name'],
                    'relation' => $member['relation'],
                    'birthdate' => $member['birthdate'],
                    'age' => $member['age'],
                    'sex' => $member['sex'],
                    'household_no' => $resident ? $resident['household_no'] : null,
                    'head_name' => $resident ? trim($resident['first_name'] . ' ' . $resident['last_name']) : null,
                    'status' => $member['status']
                ]
            ]);
        }

        return $this->response->setJSON(['error' => 'Invalid QR code']);
    }
}