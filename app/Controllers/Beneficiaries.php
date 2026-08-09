<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Beneficiaries extends BaseController
{
    protected $residentModel;
    protected $familyMemberModel;

public function __construct()
{
    $this->residentModel = new ResidentModel();
    $this->familyMemberModel = new FamilyMemberModel();
    
    // Create upload directories if they don't exist
    $uploadPaths = [
        'uploads/head_photos',
        'uploads/signatures',
        'uploads/thumbmarks',
        'uploads/id_pictures',
        'uploads/family_photos',
        'uploads/family_documents'
    ];
    
    foreach ($uploadPaths as $path) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}

public function index()
{
    $userRole     = session()->get('role');
    $userBarangay = session()->get('barangay');

    $selectedDate = $this->request->getGet('date') ?: date('Y-m-d');
    $distType     = $this->request->getGet('type') ?: 'regular';
    $eventId      = $this->request->getGet('event_id') ?: null;

    $db = \Config\Database::connect();

    $data['selected_date']     = $selectedDate;
    $data['distribution_type'] = $distType;
    $data['selected_event']    = $eventId;
    $data['events']            = (new \App\Models\EventModel())->orderBy('event_name', 'ASC')->findAll();

    // NEW: total registered beneficiaries (respects barangay-role scoping) — used for the stats card
    $totalQuery = $this->residentModel;
    if ($userRole == 'barangay') {
        $totalQuery = $totalQuery->where('barangay', $userBarangay);
    }
    $data['total_residents'] = $totalQuery->countAllResults();

    if ($distType === 'kitchen') {
        $builder = $db->table('kitchen_distributions kd')
            ->select('kd.*,
                      r.household_no, r.first_name as head_first_name, r.last_name as head_last_name,
                      r.barangay, r.photo as head_photo,
                      fm.name as family_member_name, fm.relation, fm.photo as member_photo,
                      u.username as distributor_name')
            ->join('residents r', 'r.id = kd.resident_id', 'left')
            ->join('family_members fm', 'fm.id = kd.family_member_id', 'left')
            ->join('users u', 'u.id = kd.distributor_id', 'left')
            ->where('DATE(kd.distribution_date)', $selectedDate)
            ->where('kd.status', 'completed')
            ->orderBy('kd.distribution_date', 'DESC');

        if ($userRole == 'barangay' && $userBarangay) {
            $builder->groupStart()
                        ->where('r.barangay', $userBarangay)
                        ->orWhere('kd.resident_id', null)
                    ->groupEnd();
        }

        if ($eventId) {
            $builder->where('kd.event_id', $eventId);
        }

$kitchenClaims = $builder->get()->getResultArray();

        // NEW: count every individual claim — head AND member each count separately (guests excluded here)
        $beneficiaryClaims = array_filter($kitchenClaims, fn($c) => $c['claimant_type'] !== 'guest');
        $guestClaims       = array_filter($kitchenClaims, fn($c) => $c['claimant_type'] === 'guest');

        $data['kitchen_claims']              = $kitchenClaims;
        $data['residents']                   = [];
        $data['claimed_beneficiaries_count'] = count($beneficiaryClaims);      // heads + members, no guests
        $data['guest_claims_count']          = count($guestClaims);
        $data['total_claimed_count']         = count($kitchenClaims);          // everything, including guests

        return view('beneficiaries/index', $data);
    }

    $query = $this->residentModel;
    if ($userRole == 'barangay') {
        $query = $query->where('barangay', $userBarangay);
    }
    $allResidents = $query->orderBy('created_at', 'DESC')->findAll();

    $claimedResidents = [];

    foreach ($allResidents as $resident) {
        $resident['family_members'] = $this->familyMemberModel->getByResidentId($resident['id']);

        $headBuilder = $db->table('batch_distributions')
            ->where('resident_id', $resident['id'])
            ->where('DATE(distribution_date)', $selectedDate);
        if ($eventId) {
            $headBuilder->where('event_id', $eventId);
        }
        $headClaimed = $headBuilder->get()->getRowArray();

        $resident['head_claimed_today'] = !empty($headClaimed);
        $resident['head_claimed_at']    = $headClaimed['distribution_date'] ?? null;
        $resident['distributor_id']     = $headClaimed['distributor_id'] ?? null;

        foreach ($resident['family_members'] as &$member) {
            $memberBuilder = $db->table('batch_distributions')
                ->where('family_member_id', $member['id'])
                ->where('DATE(distribution_date)', $selectedDate);
            if ($eventId) {
                $memberBuilder->where('event_id', $eventId);
            }
            $memberClaimed = $memberBuilder->get()->getRowArray();
            $member['claimed_today'] = !empty($memberClaimed);
            $member['claimed_at']    = $memberClaimed['distribution_date'] ?? null;
        }

        if ($resident['head_claimed_today']) {
            $claimedResidents[] = $resident;
        }
    }

// NEW: count every individual claim — head AND each family member who claimed
    $claimedBeneficiariesCount = 0;
    foreach ($claimedResidents as $resident) {
        $claimedBeneficiariesCount++; // the head
        foreach ($resident['family_members'] as $member) {
            if (!empty($member['claimed_today'])) {
                $claimedBeneficiariesCount++;
            }
        }
    }

    $data['residents']                   = $claimedResidents;
    $data['kitchen_claims']              = [];
    $data['claimed_beneficiaries_count'] = $claimedBeneficiariesCount;
    $data['guest_claims_count']          = 0;
    $data['total_claimed_count']         = $claimedBeneficiariesCount; // no guests in regular mode

    return view('beneficiaries/index', $data);
}

    /**
     * Display the form for adding a new beneficiary
     */
    public function add()
    {
        return view('beneficiaries/add');
    }

public function store()
{
    // Get all post data
    $data = $this->request->getPost();
    
    // Handle checkboxes
    $data['is_4ps_beneficiary'] = $this->request->getPost('is_4ps_beneficiary') ? 1 : 0;
    $data['data_privacy_consent'] = $this->request->getPost('data_privacy_consent') ? 1 : 0;
    
    // Set default values for numeric fields if empty
    $numericFields = [
        'vulnerable_older_persons',
        'vulnerable_pregnant',
        'vulnerable_lactating',
        'vulnerable_pwd',
        'monthly_income'
    ];
    
    foreach ($numericFields as $field) {
        $data[$field] = !empty($data[$field]) ? $data[$field] : 0;
    }
    
// Handle signature/thumbmark - Priority 1: Drawn signature from canvas
$drawnSignatureData = $this->request->getPost('signature_thumbmark_data');
if (!empty($drawnSignatureData)) {
    // This is a drawn signature from the canvas
    // Generate filename using family head's name
    $firstName = $data['first_name'] ?? 'unknown';
    $lastName = $data['last_name'] ?? 'unknown';
    $middleName = $data['middle_name'] ?? '';
    
    $savedPath = $this->processDrawnSignature($drawnSignatureData, $firstName, $lastName, $middleName);
    if ($savedPath) {
        $data['signature_thumbmark'] = $savedPath;
        log_message('debug', 'Using drawn signature: ' . $savedPath);
    } else {
        log_message('error', 'Failed to process drawn signature');
    }
} 
// Priority 2: Uploaded signature file
else {
    $signatureFile = $this->request->getFile('signature_thumbmark');
    if ($signatureFile && $signatureFile->isValid() && !$signatureFile->hasMoved()) {
        // Generate filename using family head's name
        $firstName = $data['first_name'] ?? 'unknown';
        $lastName = $data['last_name'] ?? 'unknown';
        $middleName = $data['middle_name'] ?? '';
        
        $sanitizedName = $this->sanitizeFilename($firstName . '_' . $lastName);
        if (!empty($middleName)) {
            $sanitizedName = $this->sanitizeFilename($firstName . '_' . $middleName . '_' . $lastName);
        }
        
        $extension = $signatureFile->getClientExtension();
        $newName = $sanitizedName . '_SIGNATURE_' . date('Y-m-d_H-i-s') . '.' . $extension;
        
        $signatureFile->move('uploads/signatures', $newName);
        $data['signature_thumbmark'] = 'uploads/signatures/' . $newName;
        log_message('debug', 'Using uploaded signature: ' . $data['signature_thumbmark']);
    }
}
    
$thumbmarkFile = $this->request->getFile('right_thumbmark');
if ($thumbmarkFile && $thumbmarkFile->isValid() && !$thumbmarkFile->hasMoved()) {
    // Generate filename using family head's name
    $firstName = $data['first_name'] ?? 'unknown';
    $lastName = $data['last_name'] ?? 'unknown';
    $middleName = $data['middle_name'] ?? '';
    
    $sanitizedName = $this->sanitizeFilename($firstName . '_' . $lastName);
    if (!empty($middleName)) {
        $sanitizedName = $this->sanitizeFilename($firstName . '_' . $middleName . '_' . $lastName);
    }
    
    $extension = $thumbmarkFile->getClientExtension();
    $newName = $sanitizedName . '_THUMBMARK_' . date('Y-m-d_H-i-s') . '.' . $extension;
    
    $thumbmarkFile->move('uploads/thumbmarks', $newName);
    $data['right_thumbmark'] = 'uploads/thumbmarks/' . $newName;
    log_message('debug', 'Thumbmark saved: ' . $data['right_thumbmark']);
}
    
    // Handle ID picture uploads for head of family
    $idFrontFile = $this->request->getFile('id_picture_front');
    if ($idFrontFile && $idFrontFile->isValid() && !$idFrontFile->hasMoved()) {
        if (in_array($idFrontFile->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $newName = $idFrontFile->getRandomName();
            $idFrontFile->move('uploads/id_pictures', $newName);
            $data['id_picture_front'] = 'uploads/id_pictures/' . $newName;
        }
    }

    $idBackFile = $this->request->getFile('id_picture_back');
    if ($idBackFile && $idBackFile->isValid() && !$idBackFile->hasMoved()) {
        if (in_array($idBackFile->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $newName = $idBackFile->getRandomName();
            $idBackFile->move('uploads/id_pictures', $newName);
            $data['id_picture_back'] = 'uploads/id_pictures/' . $newName;
        }
    }
    
// Handle head of family photo (supports both upload and camera capture)
$headPhotoFile = $this->request->getFile('head_photo');
$capturedPhotoData = $this->request->getPost('head_photo_captured');

// Priority 1: Check for captured photo from camera
if ($capturedPhotoData && !empty($capturedPhotoData)) {
    // Handle captured photo from camera (data URL format)
    // Remove the data:image/jpeg;base64, prefix
    $imageData = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $capturedPhotoData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageBinary = base64_decode($imageData);
    
    if ($imageBinary !== false) {
        // Ensure directory exists
        $uploadPath = FCPATH . 'uploads/head_photos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate filename using resident's name
        $firstName = $data['first_name'] ?? 'unknown';
        $lastName = $data['last_name'] ?? 'unknown';
        $middleName = $data['middle_name'] ?? '';
        
        // Create sanitized name (remove special characters, replace spaces with underscore)
        $sanitizedName = preg_replace('/[^A-Za-z0-9]/', '_', $firstName . '_' . $lastName);
        if (!empty($middleName)) {
            $sanitizedName = preg_replace('/[^A-Za-z0-9]/', '_', $firstName . '_' . $middleName . '_' . $lastName);
        }
        
        $newName = $sanitizedName . '_' . date('Y-m-d_H-i-s') . '.jpg';
        $filepath = $uploadPath . $newName;
        
        // Save the captured image
        if (file_put_contents($filepath, $imageBinary)) {
            $data['photo'] = 'uploads/head_photos/' . $newName;
            log_message('debug', 'Captured photo saved: ' . $data['photo']);
        } else {
            log_message('error', 'Failed to save captured photo');
        }
    }
} 
    // Priority 2: Check for uploaded file
    elseif ($headPhotoFile && $headPhotoFile->isValid() && !$headPhotoFile->hasMoved()) {
        if (in_array($headPhotoFile->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $newName = $headPhotoFile->getRandomName();
            $headPhotoFile->move('uploads/head_photos', $newName);
            $data['photo'] = 'uploads/head_photos/' . $newName;
            log_message('debug', 'Uploaded photo saved: ' . $data['photo']);
        }
    }
    
    // Set registration date if not provided
    if (empty($data['registration_date'])) {
        $data['registration_date'] = date('Y-m-d');
    }
    
    // Generate unique QR code tokens
    $data['qr_code_token'] = bin2hex(random_bytes(16)); // Individual token for head
    $data['household_qr_token'] = bin2hex(random_bytes(16)); // Household token for the whole family
    
    // Set initial status
    $data['status'] = 'pending';
    
    // Calculate age from birthdate if not provided
    if (!empty($data['birthdate']) && empty($data['age'])) {
        $birthDate = new \DateTime($data['birthdate']);
        $today = new \DateTime('today');
        $data['age'] = $birthDate->diff($today)->y;
    }
    
    // Generate household number if not provided or if empty
    if (empty($data['household_no'])) {
        $data['household_no'] = $this->residentModel->generateHouseholdNo();
    }
    
    // Start transaction
    $db = \Config\Database::connect();
    $db->transStart();
    
    // Save resident
    $residentId = $this->residentModel->insert($data);
    
    if ($residentId) {
        // Get the resident to get the household number
        $resident = $this->residentModel->find($residentId);
        $householdNo = $resident['household_no'];
        
        // Handle family members
        $familyData = $this->processFamilyMembers($residentId, $_FILES);
        
        // Save each family member with generated member_id
        foreach ($familyData as $member) {
            if (!empty($member['name'])) {
                // Generate member ID for each family member
                $member['member_id'] = $this->familyMemberModel->generateMemberId($householdNo);
                $this->familyMemberModel->insert($member);
            }
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === true) {
            session()->setFlashdata('success', 'Resident and family members added successfully');
            return redirect()->to('/beneficiaries/resident-list');
        }
    }
    
    $db->transRollback();
    session()->setFlashdata('errors', $this->residentModel->errors());
    return redirect()->back()->withInput();
}

/**
 * Process family members data and file uploads
 */
private function processFamilyMembers($residentId, $files)
{
    $familyMembers = [];
    
    // Get form data
    $names = $this->request->getPost('member_name') ?? [];
    $relations = $this->request->getPost('relation') ?? [];
    $birthdates = $this->request->getPost('member_birthdate') ?? [];
    $ages = $this->request->getPost('member_age') ?? [];
    $sexes = $this->request->getPost('member_sex') ?? [];
    $educations = $this->request->getPost('education') ?? [];
    $occupations = $this->request->getPost('member_occupation') ?? [];
    $remarks = $this->request->getPost('remarks') ?? [];
    $qrTokens = $this->request->getPost('member_qr_token') ?? [];
    $qrIds = $this->request->getPost('member_qr_id') ?? [];
    
    // Get existing file references from hidden inputs
    $existingPhotos = $this->request->getPost('existing_member_photo') ?? [];
    $existingIdPhotos = $this->request->getPost('existing_member_id_photo') ?? [];
    
    // Get captured photo data from camera (ONLY for member photos)
    $capturedMemberPhotos = $this->request->getPost('member_photo_captured') ?? [];
    
    // Process each family member
    for ($i = 0; $i < count($names); $i++) {
        if (empty($names[$i])) continue;
        
        $member = [
            'resident_id' => $residentId,
            'name' => $names[$i] ?? '',
            'relation' => $relations[$i] ?? '',
            'birthdate' => $birthdates[$i] ?? null,
            'age' => $ages[$i] ?? null,
            'sex' => $sexes[$i] ?? '',
            'education' => $educations[$i] ?? '',
            'occupation' => $occupations[$i] ?? '',
            'remarks' => $remarks[$i] ?? '',
            'qr_code_token' => $qrTokens[$i] ?? $this->familyMemberModel->generateQRToken($residentId, $names[$i]),
            'qr_code_id' => $qrIds[$i] ?? uniqid() . '_' . $i,
            'status' => 'active'
        ];
        
// Check if there's a captured photo from camera (priority 1)
if (isset($capturedMemberPhotos[$i]) && !empty($capturedMemberPhotos[$i])) {
    // Handle captured photo from camera (data URL format)
    $capturedPhotoData = $capturedMemberPhotos[$i];
    
    // Remove the data:image/jpeg;base64, prefix
    $imageData = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $capturedPhotoData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageBinary = base64_decode($imageData);
    
    if ($imageBinary !== false) {
        // Ensure directory exists
        $uploadPath = FCPATH . 'uploads/family_photos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate filename using member's full name
        $memberName = $names[$i] ?? 'unknown';
        $sanitizedName = $this->sanitizeFilename($memberName);
        $newName = $sanitizedName . '_' . date('Y-m-d_H-i-s') . '.jpg';
        $filepath = $uploadPath . $newName;
        
        // Save the captured image
        if (file_put_contents($filepath, $imageBinary)) {
            $member['photo'] = 'uploads/family_photos/' . $newName;
            log_message('debug', 'Captured family member photo saved: ' . $member['photo'] . ' for member: ' . $names[$i]);
        } else {
            log_message('error', 'Failed to save captured family member photo for: ' . $names[$i]);
        }
    }
}
// Handle uploaded file (priority 2)
elseif (isset($files['member_photo']['name'][$i]) && !empty($files['member_photo']['name'][$i])) {
    $file = $this->request->getFile('member_photo.' . $i);
    if ($file && $file->isValid() && !$file->hasMoved()) {
        if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            // Generate filename using member's full name
            $memberName = $names[$i] ?? 'unknown';
            $sanitizedName = $this->sanitizeFilename($memberName);
            
            // Get file extension
            $extension = $file->getClientExtension();
            $newName = $sanitizedName . '_' . date('Y-m-d_H-i-s') . '.' . $extension;
            
            $file->move(FCPATH . 'uploads/family_photos', $newName);
            $member['photo'] = 'uploads/family_photos/' . $newName;
            log_message('debug', 'Uploaded family member photo saved: ' . $member['photo'] . ' for member: ' . $names[$i]);
        }
    }
}
        // Handle uploaded file (priority 2)
        elseif (isset($files['member_photo']['name'][$i]) && !empty($files['member_photo']['name'][$i])) {
            $file = $this->request->getFile('member_photo.' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/family_photos', $newName);
                    $member['photo'] = 'uploads/family_photos/' . $newName;
                    log_message('debug', 'Uploaded family member photo saved: ' . $member['photo'] . ' for member: ' . $names[$i]);
                }
            }
        } 
        // Preserve existing photo (priority 3)
        else {
            if (isset($existingPhotos[$i]) && !empty($existingPhotos[$i])) {
                // Remove any leading slashes to ensure consistent format
                $member['photo'] = ltrim($existingPhotos[$i], '/');
                log_message('debug', 'Preserved existing family member photo: ' . $member['photo']);
            }
        }
        
        // Handle ID/Birth certificate upload (NO camera capture for ID photos - just file upload)
        if (isset($files['member_id_photo']['name'][$i]) && !empty($files['member_id_photo']['name'][$i])) {
            $file = $this->request->getFile('member_id_photo.' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/family_documents', $newName);
                $member['id_photo_front'] = 'uploads/family_documents/' . $newName;
            }
        } 
        else {
            // Preserve existing ID photo
            if (isset($existingIdPhotos[$i]) && !empty($existingIdPhotos[$i])) {
                $member['id_photo_front'] = ltrim($existingIdPhotos[$i], '/');
            }
        }
        
        $familyMembers[] = $member;
    }
    
    return $familyMembers;
}

public function edit($id)
{
    $data['resident'] = $this->residentModel->find($id);
    
    if (empty($data['resident'])) {
        session()->setFlashdata('error', 'Resident not found');
        return redirect()->to('/beneficiaries/resident-list');
    }
    
    // Load family members
    $data['family_members'] = $this->familyMemberModel->getByResidentId($id);
    
    // DEBUG: Check if files actually exist
    foreach ($data['family_members'] as &$member) {
        if (!empty($member['photo'])) {
            $fullPath = FCPATH . $member['photo'];
            $member['photo_exists'] = file_exists($fullPath) ? 'YES' : 'NO';
            $member['full_path'] = $fullPath;
        }
    }
    
    // You can log this or display temporarily
    log_message('debug', 'Family members with file check: ' . print_r($data['family_members'], true));
    
    return view('beneficiaries/edit', $data);
}

public function update($id)
{
    // Check if resident exists
    $resident = $this->residentModel->find($id);
    if (empty($resident)) {
        session()->setFlashdata('error', 'Resident not found');
        return redirect()->to('/beneficiaries');
    }
    
    // Get all post data
    $data = $this->request->getPost();
    
    // If household_qr_token is empty, generate one
    if (empty($resident['household_qr_token'])) {
        $data['household_qr_token'] = bin2hex(random_bytes(16));
    }
    
    // Handle checkboxes
    $data['is_4ps_beneficiary'] = $this->request->getPost('is_4ps_beneficiary') ? 1 : 0;
    $data['data_privacy_consent'] = $this->request->getPost('data_privacy_consent') ? 1 : 0;
    
    // Set default values for numeric fields if empty
    $numericFields = [
        'vulnerable_older_persons',
        'vulnerable_pregnant',
        'vulnerable_lactating',
        'vulnerable_pwd',
        'monthly_income'
    ];
    
    foreach ($numericFields as $field) {
        $data[$field] = !empty($data[$field]) ? $data[$field] : 0;
    }
    
// Handle signature/thumbmark - Priority 1: Drawn signature from canvas
$drawnSignatureData = $this->request->getPost('signature_thumbmark_data');
if (!empty($drawnSignatureData)) {
    // This is a drawn signature from the canvas
    // Delete old file if exists
    if (!empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark'])) {
        unlink(FCPATH . $resident['signature_thumbmark']);
        log_message('debug', 'Deleted old signature: ' . $resident['signature_thumbmark']);
    }
    
    // Generate filename using family head's name
    $firstName = $data['first_name'] ?? $resident['first_name'] ?? 'unknown';
    $lastName = $data['last_name'] ?? $resident['last_name'] ?? 'unknown';
    $middleName = $data['middle_name'] ?? $resident['middle_name'] ?? '';
    
    $savedPath = $this->processDrawnSignature($drawnSignatureData, $firstName, $lastName, $middleName);
    if ($savedPath) {
        $data['signature_thumbmark'] = $savedPath;
        log_message('debug', 'Using drawn signature for update: ' . $savedPath);
    } else {
        log_message('error', 'Failed to process drawn signature for update');
    }
} 
// Priority 2: Check if user wants to remove existing signature
elseif ($this->request->getPost('signature_thumbmark_remove') == '1') {
    if (!empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark'])) {
        unlink(FCPATH . $resident['signature_thumbmark']);
        log_message('debug', 'Removed signature due to remove flag');
    }
    $data['signature_thumbmark'] = null;
}
// Priority 3: Uploaded signature file
else {
    $signatureFile = $this->request->getFile('signature_thumbmark');
    if ($signatureFile && $signatureFile->isValid() && !$signatureFile->hasMoved()) {
        if (!empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark'])) {
            unlink(FCPATH . $resident['signature_thumbmark']);
        }
        
        // Generate filename using family head's name
        $firstName = $data['first_name'] ?? $resident['first_name'] ?? 'unknown';
        $lastName = $data['last_name'] ?? $resident['last_name'] ?? 'unknown';
        $middleName = $data['middle_name'] ?? $resident['middle_name'] ?? '';
        
        $sanitizedName = $this->sanitizeFilename($firstName . '_' . $lastName);
        if (!empty($middleName)) {
            $sanitizedName = $this->sanitizeFilename($firstName . '_' . $middleName . '_' . $lastName);
        }
        
        $extension = $signatureFile->getClientExtension();
        $newName = $sanitizedName . '_SIGNATURE_' . date('Y-m-d_H-i-s') . '.' . $extension;
        
        $signatureFile->move('uploads/signatures', $newName);
        $data['signature_thumbmark'] = 'uploads/signatures/' . $newName;
        log_message('debug', 'Using uploaded signature for update: ' . $data['signature_thumbmark']);
    }
}
    
$thumbmarkFile = $this->request->getFile('right_thumbmark');
if ($thumbmarkFile && $thumbmarkFile->isValid() && !$thumbmarkFile->hasMoved()) {
    if (!empty($resident['right_thumbmark']) && file_exists(FCPATH . $resident['right_thumbmark'])) {
        unlink(FCPATH . $resident['right_thumbmark']);
        log_message('debug', 'Deleted old thumbmark: ' . $resident['right_thumbmark']);
    }
    
    // Generate filename using family head's name
    $firstName = $data['first_name'] ?? $resident['first_name'] ?? 'unknown';
    $lastName = $data['last_name'] ?? $resident['last_name'] ?? 'unknown';
    $middleName = $data['middle_name'] ?? $resident['middle_name'] ?? '';
    
    $sanitizedName = $this->sanitizeFilename($firstName . '_' . $lastName);
    if (!empty($middleName)) {
        $sanitizedName = $this->sanitizeFilename($firstName . '_' . $middleName . '_' . $lastName);
    }
    
    $extension = $thumbmarkFile->getClientExtension();
    $newName = $sanitizedName . '_THUMBMARK_' . date('Y-m-d_H-i-s') . '.' . $extension;
    
    $thumbmarkFile->move('uploads/thumbmarks', $newName);
    $data['right_thumbmark'] = 'uploads/thumbmarks/' . $newName;
    log_message('debug', 'Updated thumbmark saved: ' . $data['right_thumbmark']);
}
    
    // Handle ID picture uploads for head of family
    $idFrontFile = $this->request->getFile('id_picture_front');
    if ($idFrontFile && $idFrontFile->isValid() && !$idFrontFile->hasMoved()) {
        if (!empty($resident['id_picture_front']) && file_exists($resident['id_picture_front'])) {
            unlink($resident['id_picture_front']);
        }
        if (in_array($idFrontFile->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $newName = $idFrontFile->getRandomName();
            $idFrontFile->move('uploads/id_pictures', $newName);
            $data['id_picture_front'] = 'uploads/id_pictures/' . $newName;
        }
    }

    $idBackFile = $this->request->getFile('id_picture_back');
    if ($idBackFile && $idBackFile->isValid() && !$idBackFile->hasMoved()) {
        if (!empty($resident['id_picture_back']) && file_exists($resident['id_picture_back'])) {
            unlink($resident['id_picture_back']);
        }
        if (in_array($idBackFile->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $newName = $idBackFile->getRandomName();
            $idBackFile->move('uploads/id_pictures', $newName);
            $data['id_picture_back'] = 'uploads/id_pictures/' . $newName;
        }
    }
    
// Handle head of family photo (upload, camera capture, remove, or keep existing)
$headPhotoFile     = $this->request->getFile('head_photo');
$capturedPhotoData = $this->request->getPost('head_photo_captured');
$removePhoto       = $this->request->getPost('head_photo_remove');

// Priority 1: captured photo from camera
if (!empty($capturedPhotoData)) {
    if (!empty($resident['photo']) && file_exists(FCPATH . $resident['photo'])) {
        unlink(FCPATH . $resident['photo']);
    }
    $imageData   = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $capturedPhotoData);
    $imageData   = str_replace(' ', '+', $imageData);
    $imageBinary = base64_decode($imageData);
    if ($imageBinary !== false) {
        $uploadPath = FCPATH . 'uploads/head_photos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate filename using resident's name from update data
        $firstName = $data['first_name'] ?? $resident['first_name'] ?? 'unknown';
        $lastName = $data['last_name'] ?? $resident['last_name'] ?? 'unknown';
        $middleName = $data['middle_name'] ?? $resident['middle_name'] ?? '';
        
        // Create sanitized name (remove special characters, replace spaces with underscore)
        $sanitizedName = preg_replace('/[^A-Za-z0-9]/', '_', $firstName . '_' . $lastName);
        if (!empty($middleName)) {
            $sanitizedName = preg_replace('/[^A-Za-z0-9]/', '_', $firstName . '_' . $middleName . '_' . $lastName);
        }
        
        $newName  = $sanitizedName . '_' . date('Y-m-d_H-i-s') . '.jpg';
        $filepath = $uploadPath . $newName;
        if (file_put_contents($filepath, $imageBinary)) {
            $data['photo'] = 'uploads/head_photos/' . $newName;
        }
    }
}
    // Priority 2: uploaded file
    elseif ($headPhotoFile && $headPhotoFile->isValid() && !$headPhotoFile->hasMoved()) {
        if (!empty($resident['photo']) && file_exists(FCPATH . $resident['photo'])) {
            unlink(FCPATH . $resident['photo']);
        }
        if (in_array($headPhotoFile->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $newName = $headPhotoFile->getRandomName();
            $headPhotoFile->move('uploads/head_photos', $newName);
            $data['photo'] = 'uploads/head_photos/' . $newName;
        }
    }
    // Priority 3: user explicitly removed the photo
    elseif ($removePhoto == '1') {
        if (!empty($resident['photo']) && file_exists(FCPATH . $resident['photo'])) {
            unlink(FCPATH . $resident['photo']);
        }
        $data['photo'] = null;
    }
    // Priority 4: no change — keep the existing photo
    else {
        $data['photo'] = $resident['photo'];
    }
    
    // Calculate age from birthdate if provided
    if (!empty($data['birthdate'])) {
        $birthDate   = new \DateTime($data['birthdate']);
        $today       = new \DateTime('today');
        $data['age'] = $birthDate->diff($today)->y;
    }
    
    // Handle household number unique validation
    if (isset($data['household_no']) && $resident['household_no'] == $data['household_no']) {
        $this->residentModel->setValidationRule('household_no', 'required');
    } else {
        $this->residentModel->setValidationRule('household_no', "required|is_unique[residents.household_no,id,{$id}]");
    }
    
    // Start transaction
    $db = \Config\Database::connect();
    $db->transStart();
    
    // Update resident
    if ($this->residentModel->update($id, $data)) {
        $this->updateFamilyMembers($id, $_FILES);
        
        $db->transComplete();
        
        if ($db->transStatus() === true) {
            session()->setFlashdata('success', 'Resident and family members updated successfully');
            return redirect()->to('/beneficiaries/resident-list');
        }
    }
    
    $db->transRollback();
    session()->setFlashdata('errors', $this->residentModel->errors());
    return redirect()->back()->withInput();
}

/**
 * Update family members (preserve existing files if not changed)
 */
private function updateFamilyMembers($residentId, $files)
{
    // Get existing members to check for files that should be preserved
    $existingMembers = $this->familyMemberModel->getByResidentId($residentId);
    
    // Create a map of existing files by index
    $existingPhotos = [];
    $existingIdPhotos = [];
    
    foreach ($existingMembers as $member) {
        if (!empty($member['photo'])) {
            $existingPhotos[] = $member['photo'];
        }
        if (!empty($member['id_photo_front'])) {
            $existingIdPhotos[] = $member['id_photo_front'];
        }
    }
    
    // Get form data for existing files (these are the paths from hidden inputs)
    $preservedPhotos = $this->request->getPost('existing_member_photo') ?? [];
    $preservedIdPhotos = $this->request->getPost('existing_member_id_photo') ?? [];
    
    // Get captured photo data from camera for updates
    $capturedMemberPhotos = $this->request->getPost('member_photo_captured') ?? [];
    
    // Process and insert new family members
    $familyData = $this->processFamilyMembers($residentId, $files);
    
    // Get the resident to get the household number
    $resident = $this->residentModel->find($residentId);
    $householdNo = $resident['household_no'];
    
    // First, delete ALL existing family members from database
    $this->familyMemberModel->deleteByResidentId($residentId);
    
    // Now insert the new/updated family members
    foreach ($familyData as $member) {
        if (!empty($member['name'])) {
            // Generate member ID for each family member
            $member['member_id'] = $this->familyMemberModel->generateMemberId($householdNo);
            $this->familyMemberModel->insert($member);
        }
    }
    
    // After successful insertion, check for files that are no longer used
    $newMembers = $this->familyMemberModel->getByResidentId($residentId);
    $newPhotos = [];
    $newIdPhotos = [];
    
    foreach ($newMembers as $member) {
        if (!empty($member['photo'])) {
            $newPhotos[] = $member['photo'];
        }
        if (!empty($member['id_photo_front'])) {
            $newIdPhotos[] = $member['id_photo_front'];
        }
    }
    
    // Delete old photos that are no longer referenced
    foreach ($existingPhotos as $oldPhoto) {
        if (!in_array($oldPhoto, $newPhotos) && file_exists(FCPATH . $oldPhoto)) {
            @unlink(FCPATH . $oldPhoto);
            log_message('debug', 'Deleted old family member photo: ' . $oldPhoto);
        }
    }
    
    foreach ($existingIdPhotos as $oldIdPhoto) {
        if (!in_array($oldIdPhoto, $newIdPhotos) && file_exists(FCPATH . $oldIdPhoto)) {
            @unlink(FCPATH . $oldIdPhoto);
            log_message('debug', 'Deleted old family member ID photo: ' . $oldIdPhoto);
        }
    }
}

/**
 * Process captured ID photos for family members
 */
private function processFamilyMemberIdPhotos($residentId, $files)
{
    $capturedIdPhotos = $this->request->getPost('member_id_photo_captured') ?? [];
    $processedIds = [];
    
    foreach ($capturedIdPhotos as $idx => $capturedPhotoData) {
        if (!empty($capturedPhotoData)) {
            // Handle captured ID photo from camera (data URL format)
            $imageData = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $capturedPhotoData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageBinary = base64_decode($imageData);
            
            if ($imageBinary !== false) {
                // Ensure directory exists
                $uploadPath = FCPATH . 'uploads/family_documents/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $newName = 'member_id_captured_' . uniqid() . '_' . time() . '.jpg';
                $filepath = $uploadPath . $newName;
                
                // Save the captured image
                if (file_put_contents($filepath, $imageBinary)) {
                    $processedIds[$idx] = 'uploads/family_documents/' . $newName;
                    log_message('debug', 'Captured family member ID photo saved: ' . $processedIds[$idx]);
                }
            }
        }
    }
    
    return $processedIds;
}

/**
 * View family member QR code
 */
public function viewMemberQR($residentId, $memberId)
{
    $resident = $this->residentModel->find($residentId);
    $member = $this->familyMemberModel->find($memberId);
    
    if (empty($resident) || empty($member) || $member['resident_id'] != $residentId) {
        session()->setFlashdata('error', 'Family member not found');
        return redirect()->to('/beneficiaries');
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
        'head_name' => $resident['first_name'] . ' ' . $resident['last_name'],
        'member_index' => $member_index !== false ? $member_index : 0 // Add this line
    ];
    
    return view('residentportal/member_qr_view', $data);
}

/**
 * Print family member QR code
 */
public function printMemberQR($residentId, $memberId)
{
    $resident = $this->residentModel->find($residentId);
    $member = $this->familyMemberModel->find($memberId);
    
    if (empty($resident) || empty($member) || $member['resident_id'] != $residentId) {
        session()->setFlashdata('error', 'Family member not found');
        return redirect()->to('/beneficiaries');
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
        'head_name' => $resident['first_name'] . ' ' . $resident['last_name'],
        'member_index' => $member_index !== false ? $member_index : 0 // Add this line
    ];
    
    return view('residentportal/member_qr_print', $data);
}

    public function delete($id)
    {
        // Check if resident exists
        $resident = $this->residentModel->find($id);
        if (empty($resident)) {
            session()->setFlashdata('error', 'Resident not found');
            return redirect()->to('/beneficiaries');
        }
        
        // Delete signature files if they exist
        if (!empty($resident['signature_thumbmark']) && file_exists($resident['signature_thumbmark'])) {
            unlink($resident['signature_thumbmark']);
        }
        if (!empty($resident['right_thumbmark']) && file_exists($resident['right_thumbmark'])) {
            unlink($resident['right_thumbmark']);
        }
        
if ($this->residentModel->delete($id)) {
    session()->setFlashdata('success', 'Resident and family members deleted successfully');
} else {
    session()->setFlashdata('error', 'Failed to delete resident');
}

return redirect()->to('/beneficiaries/resident-list');  // Changed from '/beneficiaries'
    }

public function view($id)
{
    $data['resident'] = $this->residentModel->find($id);
    
if (empty($data['resident'])) {
    session()->setFlashdata('error', 'Resident not found');
    return redirect()->to('/beneficiaries/resident-list');  // Changed from '/beneficiaries'
}

// Check if barangay user has access to this resident
$userRole = session()->get('role');
if ($userRole == 'barangay') {
    $userBarangay = session()->get('barangay');
    if ($data['resident']['barangay'] != $userBarangay) {
        session()->setFlashdata('error', 'You do not have permission to view this resident');
        return redirect()->to('/beneficiaries/resident-list');  // Changed from '/beneficiaries'
    }
}
    
    // Load family members
    $data['family_members'] = $this->familyMemberModel->getByResidentId($id);
    
    return view('beneficiaries/view', $data);
}

public function export()
{
    $residents = $this->residentModel->orderBy('household_no', 'ASC')->findAll();
 
    // Attach family members for each resident
    foreach ($residents as &$resident) {
        $resident['family_members'] = $this->familyMemberModel->getByResidentId($resident['id']);
    }
 
    $data['residents'] = $residents;
 
    // Return the printable FACED view
    return view('beneficiaries/faced_export', $data);
}
 
/**
 * Export SELECTED beneficiaries as DSWD FACED cards (landscape, 2 per page)
 * Route: GET /beneficiaries/export-selected/(:segment)
 * e.g. /beneficiaries/export-selected/3,7,12,15
 */
public function exportSelected($idString = '')
{
    if (empty($idString)) {
        return redirect()->to('/beneficiaries/resident-list');
    }
 
    // Parse and sanitize IDs
    $ids = array_filter(
        array_map('intval', explode(',', $idString)),
        fn($id) => $id > 0
    );
 
    if (empty($ids)) {
        return redirect()->to('/beneficiaries/resident-list');
    }
 
    $residents = $this->residentModel
        ->whereIn('id', $ids)
        ->orderBy('household_no', 'ASC')
        ->findAll();
 
    // Attach family members
    foreach ($residents as &$resident) {
        $resident['family_members'] = $this->familyMemberModel->getByResidentId($resident['id']);
    }
 
    $data['residents'] = $residents;
 
    return view('beneficiaries/faced_export', $data);
}
    
    /**
     * Process CSV file for bulk import
     */
    private function processCsvFile($filepath)
    {
        $model = new ResidentModel();
        
        if (($handle = fopen($filepath, 'r')) !== false) {
            // Skip header row
            fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== false) {
                // Map CSV columns to database fields
                $data = [
                    'household_no' => $row[0] ?? null,
                    'last_name' => $row[1] ?? null,
                    'first_name' => $row[2] ?? null,
                    'middle_name' => $row[3] ?? null,
                    'name_extension' => $row[4] ?? null,
                    'birthdate' => $row[5] ?? null,
                    'age' => $row[6] ?? null,
                    'sex' => $row[7] ?? null,
                    'civil_status' => $row[8] ?? null,
                    'contact_number' => $row[9] ?? null,
                    'barangay' => $row[10] ?? null,
                    'city_municipality' => $row[11] ?? null,
                    'province' => $row[12] ?? null,
                    'qr_code_token' => bin2hex(random_bytes(16)),
                    'status' => 'pending'
                ];
                
                // Only insert if required fields are present
                if (!empty($data['household_no']) && !empty($data['last_name']) && !empty($data['first_name'])) {
                    $model->insert($data);
                }
            }
            
            fclose($handle);
        }
        
        // Delete temporary file
        unlink($filepath);
    }

public function residentList()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Get pagination settings from request or session
    $perPage = $this->request->getGet('per_page');
    
    // Convert to integer and validate
    if ($perPage !== null && is_numeric($perPage)) {
        $perPage = (int)$perPage;
        if ($perPage >= 10 && $perPage <= 500) {
            session()->set('resident_per_page', $perPage);
        } else {
            $perPage = null;
        }
    }
    
    // If no valid per_page from request, get from session or use default
    if ($perPage === null) {
        $perPage = session()->get('resident_per_page');
        if ($perPage === null || !is_numeric($perPage) || $perPage < 10 || $perPage > 500) {
            $perPage = 10;
            session()->set('resident_per_page', $perPage);
        } else {
            $perPage = (int)$perPage;
        }
    }
    
    // Get current page
    $currentPage = $this->request->getGet('page');
    $currentPage = ($currentPage !== null && is_numeric($currentPage)) ? (int)$currentPage : 1;
    if ($currentPage < 1) $currentPage = 1;
    
    // Get search/filter values
    $search = $this->request->getGet('search');
    $barangayFilter = $this->request->getGet('barangay');
    $shelterFilter = $this->request->getGet('shelter_damage');
    $streetFilter = $this->request->getGet('street');
    
    // Get sort parameters
    $sortBy = $this->request->getGet('sort_by');
    $sortOrder = $this->request->getGet('sort_order');
    
    // Validate sort_by
    $allowedSortFields = ['name', 'created_at', 'updated_at'];
    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'name'; // default
    }
    
    // Validate sort_order
    $sortOrder = ($sortOrder === 'desc') ? 'DESC' : 'ASC';
    
    // Build the query
    $query = $this->residentModel;
    
    // Filter by barangay if user is barangay role
    if ($userRole == 'barangay') {
        $barangay = session()->get('barangay');
        $query = $query->where('barangay', $barangay);
    }
    
    // Apply search filter (server-side)
// Apply search filter (server-side)
    if ($search && !empty(trim($search))) {
        $searchTerm = trim($search);

        // Find resident_ids whose family members match the search term
        $matchingResidentIds = $this->familyMemberModel
            ->select('resident_id')
            ->like('name', $searchTerm)
            ->findColumn('resident_id') ?? [];

        $query->groupStart()
            ->like('household_no', $searchTerm)
            ->orLike('first_name', $searchTerm)
            ->orLike('last_name', $searchTerm)
            ->orLike('barangay', $searchTerm)
            ->orLike('contact_number', $searchTerm);

        if (!empty($matchingResidentIds)) {
            $query->orWhereIn('id', $matchingResidentIds);
        }

        $query->groupEnd();
    }
    
    // Apply barangay filter
    if ($barangayFilter && !empty($barangayFilter)) {
        $query->where('barangay', $barangayFilter);
    }
    
if ($streetFilter && !empty($streetFilter)) {
    $query->where('UPPER(street) =', strtolower($streetFilter));
}

    // Apply shelter filter
    if ($shelterFilter && $shelterFilter !== '' && $shelterFilter !== 'All Shelter Damage') {
        $query->where('shelter_damage', $shelterFilter);
    }
    
    // Get total count for pagination (with filters applied)
    $totalResidents = $query->countAllResults(false);
    
    // Calculate offset
    $offset = ($currentPage - 1) * $perPage;
    
    // Apply sorting
    switch ($sortBy) {
        case 'name':
            // Sort by last_name, then first_name
            $query->orderBy('last_name', $sortOrder)
                  ->orderBy('first_name', $sortOrder);
            break;
        case 'created_at':
            $query->orderBy('created_at', $sortOrder);
            break;
        case 'updated_at':
            $query->orderBy('updated_at', $sortOrder);
            break;
        default:
            $query->orderBy('last_name', 'ASC');
            break;
    }
    
    // Get paginated results
    $residents = $query->limit((int)$perPage, (int)$offset)->findAll();
    
    // Calculate total pages
    $totalPages = ($perPage > 0) ? ceil($totalResidents / $perPage) : 1;
    
    // For each resident, load their family members AND calculate family size
    foreach ($residents as &$resident) {
        $resident['family_members'] = $this->familyMemberModel->getByResidentId($resident['id']);
        $resident['family_size'] = 1 + count($resident['family_members']);
    }
    
    // ========== FIX: Get ALL distinct barangays from the ENTIRE database ==========
    // This query runs separately and gets ALL barangays, not filtered by pagination
    $barangayQuery = $this->residentModel->select('barangay')->distinct();
    
    // Apply role-based filtering for barangay users
    if ($userRole == 'barangay' && $userBarangay) {
        $barangayQuery->where('barangay', $userBarangay);
    }
    
    // Execute query without any pagination or filter limits
    $allBarangays = $barangayQuery->findAll();
    
    $barangayList = [];
    foreach ($allBarangays as $b) {
        if (!empty($b['barangay'])) {
            $barangayList[] = $b['barangay'];
        }
    }
    
    // Sort barangays alphabetically for better UX
    sort($barangayList);

    $db = \Config\Database::connect();
    $streetBuilder = $db->table('residents')
        ->select('barangay, street')
        ->where('street IS NOT NULL')
        ->where('street !=', '')
        ->groupBy('barangay, street')
        ->orderBy('barangay', 'ASC')
        ->orderBy('street', 'ASC');

    if ($userRole == 'barangay' && $userBarangay) {
        $streetBuilder->where('barangay', $userBarangay);
    }

    $streetRows = $streetBuilder->get()->getResultArray();
    $streetsByBarangay = [];
    foreach ($streetRows as $row) {
        if (!empty($row['barangay']) && !empty($row['street'])) {
            $streetsByBarangay[$row['barangay']][] = $row['street'];
        }
    }
    // ========== END OF FIX ==========
    
    $data = [
        'residents' => $residents,
        'totalResidents' => $totalResidents,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'perPage' => $perPage,
        'search' => $search,
        'barangayFilter' => $barangayFilter,
        'shelterFilter' => $shelterFilter,
        'barangays' => $barangayList,  // Now contains ALL barangays from database
        'streetFilter' => $streetFilter,
        'streetsByBarangay' => $streetsByBarangay,
        'sortBy' => $sortBy,
        'sortOrder' => strtolower($sortOrder)
    ];
    
    return view('beneficiaries/resident_list', $data);
}

/**
 * Generate and view household QR code
 */
public function viewHouseholdQR($residentId)
{
    $resident = $this->residentModel->find($residentId);
    
    if (empty($resident)) {
        session()->setFlashdata('error', 'Resident not found');
        return redirect()->to('/beneficiaries');
    }
    
    // Get all family members for this household
    $familyMembers = $this->familyMemberModel->getByResidentId($residentId);
    
    $data = [
        'resident' => $resident,
        'family_members' => $familyMembers,
        'household_no' => $resident['household_no'],
        'head_name' => $resident['first_name'] . ' ' . $resident['last_name'],
        'total_members' => count($familyMembers) + 1, // +1 for the head
        'household_qr_token' => $resident['household_qr_token'] // Add this line to pass the actual token
    ];
    
    return view('residentportal/household_qr_view', $data);
}

private function buildFacedCard(Worksheet $ws, int $startRow, array $r, array $fam): void
{
    // Convenience closures
    $fv    = fn($v) => strtoupper(trim((string)($v ?? '')));
    $fdate = function ($v) {
        if (empty($v)) return '';
        try { return (new \DateTime($v))->format('m-d-Y'); }
        catch (\Exception $e) { return $v; }
    };
 
    // Cell address helper: column index (1-based) + row → "A1" etc.
    $C     = fn(int $col, int $row) => Coordinate::stringFromColumnIndex($col) . $row;
    $merge = fn(int $c1, int $r1, int $c2, int $r2) =>
                 $ws->mergeCells($C($c1, $r1) . ':' . $C($c2, $r2));
    $set   = fn(int $col, int $row, $val) => $ws->setCellValue($C($col, $row), $val);
 
    $r0 = $startRow;
 
    // ── HEADER (logo / agency / title / serial) ────────────────────────────
    $merge(1, $r0, 2, $r0 + 2);
    $ws->getCell($C(1, $r0))->setValue('DSWD Logo');
    $ws->getStyle($C(1, $r0))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        'font'      => ['size' => 7, 'color' => ['rgb' => '888888']],
    ]);
 
    $merge(3, $r0, 13, $r0);
    $set(3, $r0, 'Republic of the Philippines');
    $ws->getStyle($C(3, $r0))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['size' => 7],
    ]);
 
    $merge(3, $r0 + 1, 13, $r0 + 1);
    $set(3, $r0 + 1, 'Department of Social Welfare and Development');
    $ws->getStyle($C(3, $r0 + 1))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['bold' => true, 'size' => 8],
    ]);
 
    $merge(3, $r0 + 2, 13, $r0 + 2);
    $set(3, $r0 + 2, 'FAMILY ASSISTANCE CARD IN EMERGENCIES AND DISASTERS (FACED)');
    $ws->getStyle($C(3, $r0 + 2))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['bold' => true, 'size' => 9],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']],
    ]);
 
    // "THIS CARD IS NOT FOR SALE"
    $merge(14, $r0, 16, $r0);
    $set(14, $r0, 'THIS CARD IS NOT FOR SALE');
    $ws->getStyle($C(14, $r0))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
        'font'      => ['bold' => true, 'size' => 7],
    ]);
 
    // "BENEFICIARY'S COPY" — black background, white text
    // FIX: font color belongs inside the 'font' array, not at the top level
    $merge(14, $r0 + 1, 16, $r0 + 1);
    $set(14, $r0 + 1, "BENEFICIARY'S COPY");
    $ws->getStyle($C(14, $r0 + 1))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['bold' => true, 'size' => 7, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
    ]);
 
    // Serial number
    $merge(14, $r0 + 2, 16, $r0 + 2);
    $set(14, $r0 + 2, 'SERIAL NUMBER: ' . $fv($r['household_no']));
    $ws->getStyle($C(14, $r0 + 2))->applyFromArray([
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['bold' => true, 'size' => 7],
    ]);
 
    // ── SECTION HEADER: Location ───────────────────────────────────────────
    $r1 = $r0 + 3;
    $merge(1, $r1, 16, $r1);
    $set(1, $r1, 'LOCATION OF THE AFFECTED FAMILY');
    $ws->getStyle($C(1, $r1) . ':' . $C(16, $r1))->applyFromArray([
        'font'      => ['bold' => true, 'size' => 7, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
 
    // Location fields (span 2 rows each)
    $r2 = $r0 + 4;
    foreach ([
        [1,  3,  '1. REGION',               $fv($r['region'])],
        [4,  6,  '2. PROVINCE',             $fv($r['province'])],
        [7,  9,  '3. DISTRICT',             $fv($r['district'])],
        [10, 12, '4. CITY/MUNICIPALITY',    $fv($r['city_municipality'])],
        [13, 14, '5. BARANGAY',             $fv($r['barangay'])],
        [15, 16, '6. EVACUATION CENTER/SITE', $fv($r['evacuation_center'])],
    ] as [$c1, $c2, $label, $val]) {
        $merge($c1, $r2, $c2, $r2 + 1);
        $ws->setCellValue($C($c1, $r2), $label . "\n" . $val);
        $ws->getStyle($C($c1, $r2))->applyFromArray([
            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
            'font'      => ['size' => 7],
        ]);
    }
 
    // ── SECTION HEADER: Head of Family ────────────────────────────────────
    $r3 = $r2 + 2;
    $merge(1, $r3, 16, $r3);
    $set(1, $r3, 'HEAD OF THE FAMILY');
    $ws->getStyle($C(1, $r3) . ':' . $C(16, $r3))->applyFromArray([
        'font'      => ['bold' => true, 'size' => 7, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
 
    // Head of Family fields
    $fullName = trim(implode(' ', array_filter([
        $fv($r['first_name']),
        $fv($r['middle_name'] ?? ''),
        $fv($r['last_name']),
        $fv($r['name_extension'] ?? ''),
    ])));
 
    $permanentAddress = implode(' ', array_filter([
        $fv($r['house_no']          ?? ''),
        $fv($r['street']            ?? ''),
        $fv($r['subdivision']       ?? ''),
        $fv($r['permanent_barangay'] ?? ''),
        $fv($r['permanent_city']    ?? ''),
        $fv($r['permanent_province'] ?? ''),
        $fv($r['zip_code']          ?? ''),
    ]));
 
    $r4 = $r3 + 1;
    foreach ([
        // [c1, c2, rowOffset, label, value]
        [1,  4,  1, '7. LAST NAME',             $fv($r['last_name'])],
        [5,  8,  1, '15. CIVIL STATUS',          $fv($r['civil_status']       ?? '')],
        [9,  16, 1, "16. MOTHER'S MAIDEN NAME",  $fv($r['mother_maiden_name'] ?? '')],
        [1,  4,  2, '8. FIRST NAME',             $fv($r['first_name'])],
        [5,  8,  2, '17. RELIGION',              $fv($r['religion']           ?? '')],
        [9,  16, 2, '18. OCCUPATION',            $fv($r['occupation']         ?? '')],
        [1,  4,  3, '9. MIDDLE NAME',            $fv($r['middle_name']        ?? '')],
        [5,  8,  3, '10. NAME EXT.',             $fv($r['name_extension']     ?? '')],
        [9,  12, 3, '11. BIRTHDATE',             $fdate($r['birthdate']       ?? '')],
        [13, 16, 3, '12. AGE',                   $fv($r['age']                ?? '')],
        [1,  4,  4, '13. BIRTHPLACE',            $fv($r['birthplace']         ?? '')],
        [5,  8,  4, '14. SEX',                   $fv($r['sex']                ?? '')],
        [9,  12, 4, '19. MONTHLY INCOME',        $fv($r['monthly_income']     ?? '0.00')],
        [13, 16, 4, '20. ID CARD PRESENTED',     $fv($r['id_card_presented']  ?? '')],
        [1,  8,  5, '21. ID CARD NUMBER',        $fv($r['id_card_number']     ?? '')],
        [9,  16, 5,
            '22. CONTACT #: ' . $fv($r['contact_number'] ?? '') . '   ALT: ' . $fv($r['alternate_number'] ?? ''),
            ''
        ],
    ] as [$c1, $c2, $offset, $label, $val]) {
        $row = $r4 + $offset - 1;
        $merge($c1, $row, $c2, $row);
        $ws->setCellValue($C($c1, $row), $label . ($val !== '' ? "\n" . $val : ''));
        $ws->getStyle($C($c1, $row))->applyFromArray([
            'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
            'font'      => ['size' => 7],
        ]);
    }
 
    // Permanent address (full width)
    $rAddr = $r4 + 5;
    $merge(1, $rAddr, 16, $rAddr);
    $ws->setCellValue($C(1, $rAddr), '23. PERMANENT ADDRESS: ' . $permanentAddress);
    $ws->getStyle($C(1, $rAddr))->applyFromArray([
        'font'      => ['size' => 7],
        'alignment' => ['wrapText' => true],
    ]);
 
    // Others (4Ps / IP ethnicity)
    $rOthers = $rAddr + 1;
    $merge(1, $rOthers, 16, $rOthers);
    $is4ps = !empty($r['is_4ps_beneficiary']) ? '☑' : '☐';
    $ws->setCellValue($C(1, $rOthers),
        '24. OTHERS:   ' . $is4ps . ' 4Ps Beneficiary    IP- Type of Ethnicity: ' . $fv($r['ip_ethnicity'] ?? ''));
    $ws->getStyle($C(1, $rOthers))->applyFromArray(['font' => ['size' => 7]]);
 
    // ── FAMILY MEMBERS TABLE ───────────────────────────────────────────────
    $rFamHeader = $rOthers + 1;
    $merge(1, $rFamHeader, 16, $rFamHeader);
    $set(1, $rFamHeader, '25. FAMILY INFORMATION');
    $ws->getStyle($C(1, $rFamHeader))->applyFromArray([
        'font'      => ['bold' => true, 'size' => 7],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
 
    // Column header row
    $rFamCols = $rFamHeader + 1;
    foreach ([
        [1,  5,  "FAMILY MEMBERS\n(Last Name, First Name)"],
        [6,  8,  "RELATION TO\nFAMILY HEAD"],
        [9,  10, 'BIRTHDATE'],
        [11, 11, 'AGE'],
        [12, 12, 'SEX'],
        [13, 14, "HIGHEST EDUCATIONAL\nATTAINMENT"],
        [15, 15, 'OCCUPATION'],
        [16, 16, 'REMARKS'],
    ] as [$c1, $c2, $label]) {
        $merge($c1, $rFamCols, $c2, $rFamCols);
        $ws->setCellValue($C($c1, $rFamCols), $label);
        $ws->getStyle($C($c1, $rFamCols))->applyFromArray([
            'font'      => ['bold' => true, 'size' => 6],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
    }
 
    // Data rows — minimum 5
    $totalFamRows = max(5, count($fam));
    for ($i = 0; $i < $totalFamRows; $i++) {
        $rData = $rFamCols + 1 + $i;
        $m     = $fam[$i] ?? [];
        foreach ([
            [1,  5,  $fv($m['name']       ?? '')],
            [6,  8,  $fv($m['relation']   ?? '')],
            [9,  10, $fdate($m['birthdate'] ?? '')],
            [11, 11, $fv($m['age']        ?? '')],
            [12, 12, $fv($m['sex']        ?? '')],
            [13, 14, $fv($m['education']  ?? '')],
            [15, 15, $fv($m['occupation'] ?? '')],
            [16, 16, $fv($m['remarks']    ?? '')],
        ] as [$c1, $c2, $val]) {
            $merge($c1, $rData, $c2, $rData);
            $ws->setCellValue($C($c1, $rData), $val);
            $ws->getStyle($C($c1, $rData))->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'font'      => ['size' => 7],
            ]);
        }
        $ws->getRowDimension($rData)->setRowHeight(14);
    }
 
    // ── VULNERABLE + OWNERSHIP + SHELTER ──────────────────────────────────
    $rVul     = $rFamCols + 1 + $totalFamRows;
    $ownership  = $r['ownership_status'] ?? '';
    $shelterDmg = $r['shelter_damage']    ?? '';
 
    $merge(1, $rVul, 8, $rVul + 1);
    $ws->setCellValue($C(1, $rVul),
        "26. VULNERABLE MEMBERS:\n" .
        'Older Persons: '  . $fv($r['vulnerable_older_persons'] ?? '0') . '   ' .
        'Pregnant: '       . $fv($r['vulnerable_pregnant']      ?? '0') . '   ' .
        'Lactating: '      . $fv($r['vulnerable_lactating']     ?? '0') . '   ' .
        'PWD: '            . $fv($r['vulnerable_pwd']           ?? '0')
    );
    $ws->getStyle($C(1, $rVul))->applyFromArray([
        'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
        'font'      => ['size' => 7],
    ]);
 
    $isOwner  = strtolower($ownership)  === 'owner'             ? '☑' : '☐';
    $isRenter = strtolower($ownership)  === 'renter'            ? '☑' : '☐';
    $isSharer = strtolower($ownership)  === 'sharer'            ? '☑' : '☐';
    $isPartial = strtolower($shelterDmg) === 'partially damaged' ? '☑' : '☐';
    $isTotal   = strtolower($shelterDmg) === 'totally damaged'   ? '☑' : '☐';
 
    $merge(9, $rVul, 16, $rVul + 1);
    $ws->setCellValue($C(9, $rVul),
        "27. HOUSE OWNERSHIP:   {$isOwner} Owner  {$isRenter} Renter  {$isSharer} Sharer\n" .
        "28. SHELTER DAMAGE:    {$isPartial} Partially Damaged   {$isTotal} Totally Damaged"
    );
    $ws->getStyle($C(9, $rVul))->applyFromArray([
        'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP],
        'font'      => ['size' => 7],
    ]);
 
    // ── SIGNATURES ────────────────────────────────────────────────────────
    $rSig = $rVul + 2;
 
    $merge(1, $rSig, 5, $rSig + 1);
    $ws->setCellValue($C(1, $rSig), "Signature/Thumbmark of Family Head\n\n\n" . $fullName);
    $ws->getStyle($C(1, $rSig))->applyFromArray([
        'alignment' => ['wrapText' => true, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['size' => 7],
    ]);
 
    $merge(6, $rSig, 8, $rSig + 1);
    $ws->setCellValue($C(6, $rSig), "Date Registered\n" . $fdate($r['registration_date'] ?? ''));
    $ws->getStyle($C(6, $rSig))->applyFromArray([
        'alignment' => ['wrapText' => true, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['size' => 7],
    ]);
 
    $merge(9, $rSig, 12, $rSig + 1);
    $ws->setCellValue($C(9, $rSig), "Name/Signature of Brgy. Captain\n\n" . $fv($r['barangay_captain_name'] ?? ''));
    $ws->getStyle($C(9, $rSig))->applyFromArray([
        'alignment' => ['wrapText' => true, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['size' => 7],
    ]);
 
    $merge(13, $rSig, 16, $rSig + 1);
    $ws->setCellValue($C(13, $rSig), "Name/Signature of LSWDO\n\n" . $fv($r['lswdo_name'] ?? ''));
    $ws->getStyle($C(13, $rSig))->applyFromArray([
        'alignment' => ['wrapText' => true, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        'font'      => ['size' => 7],
    ]);
 
    // ── DATA PRIVACY DECLARATION ──────────────────────────────────────────
    $rPrivacy = $rSig + 2;
    $merge(1, $rPrivacy, 16, $rPrivacy);
    $ws->setCellValue($C(1, $rPrivacy),
        '29. DATA PRIVACY DECLARATION – All data and information indicated herein shall be used for identification ' .
        'purposes for the implementation of DRRM programs, projects and activities and its disclosure shall be in ' .
        'compliance to RA 10173 (Data Privacy Act of 2012).'
    );
    $ws->getStyle($C(1, $rPrivacy))->applyFromArray([
        'alignment' => ['wrapText' => true, 'horizontal' => Alignment::HORIZONTAL_JUSTIFY],
        'font'      => ['size' => 6, 'italic' => true],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F8F8']],
    ]);
    $ws->getRowDimension($rPrivacy)->setRowHeight(20);
 
    // ── OUTER BORDER around the entire card ───────────────────────────────
    $ws->getStyle($C(1, $r0) . ':' . $C(16, $rPrivacy))->applyFromArray([
        'borders' => [
            'outline'    => ['borderStyle' => Border::BORDER_MEDIUM],
            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
        ],
    ]);
}
 
public function exportExcel()
{
    $residents = $this->residentModel->orderBy('household_no', 'ASC')->findAll();
    foreach ($residents as &$resident) {
        $resident['family_members'] = $this->familyMemberModel->getByResidentId($resident['id']);
    }
    return $this->_generateFacedExcel($residents);
}
 
public function exportExcelSelected(string $idString = '')
{
    if (empty($idString)) {
        return redirect()->to('/beneficiaries/resident-list');
    }
 
    $ids = array_filter(array_map('intval', explode(',', $idString)), fn($id) => $id > 0);
    if (empty($ids)) {
        return redirect()->to('/beneficiaries/resident-list');
    }
 
    $residents = $this->residentModel
        ->whereIn('id', $ids)
        ->orderBy('household_no', 'ASC')
        ->findAll();
 
    foreach ($residents as &$resident) {
        $resident['family_members'] = $this->familyMemberModel->getByResidentId($resident['id']);
    }
 
    return $this->_generateFacedExcel($residents);

}
 
private function _generateFacedExcel(array $residents)
{
    require_once ROOTPATH . 'vendor/autoload.php';
 
    $spreadsheet = new Spreadsheet();
    $ws = $spreadsheet->getActiveSheet();
    $ws->setTitle('FACED Export');
 
    // Page / print setup — Legal landscape
    $ws->getPageSetup()
        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_LEGAL)
        ->setFitToWidth(1)
        ->setFitToHeight(0);
    $ws->getPageMargins()->setTop(0.2)->setBottom(0.2)->setLeft(0.2)->setRight(0.2);
    $ws->getSheetView()->setZoomScale(80);
 
    // 16 equal columns (A–P)
    for ($i = 1; $i <= 16; $i++) {
        $ws->getColumnDimensionByColumn($i)->setWidth(8);
    }
 
    // Default font
    $spreadsheet->getDefaultStyle()->applyFromArray([
        'font'      => ['name' => 'Arial', 'size' => 7],
        'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
    ]);
 
    $currentRow = 1;
    $CARD_ROWS  = 28; // base rows per card
    $SPACER     = 2;  // blank separator rows between cards
 
    foreach ($residents as $idx => $resident) {
        if ($idx > 0) {
            $ws->setBreak('A' . $currentRow, Worksheet::BREAK_ROW);
        }
 
        $fam = $resident['family_members'] ?? [];
        $this->buildFacedCard($ws, $currentRow, $resident, $fam);
 
        $extraFamRows = max(0, count($fam) - 5);
        $currentRow  += $CARD_ROWS + $extraFamRows + $SPACER;
    }
 
// AFTER:
    // ── IMAGE METADATA SHEET ─────────────────────────────────────────────────
    $meta = $spreadsheet->createSheet();
    $meta->setTitle('_image_paths');

    // Header row
    $meta->fromArray([[
        'household_no',
        'type',          // head_photo | head_signature | head_thumbmark | head_id_front | head_id_back
                         // member_photo | member_id_front | member_id_back | member_birth_cert
        'member_name',   // empty for head fields
        'file_path',
    ]], null, 'A1');

    $metaRow = 2;
    foreach ($residents as $resident) {
        $hh = $resident['household_no'] ?? '';

        $headFiles = [
            'head_photo'      => $resident['photo']            ?? '',
            'head_signature'  => $resident['signature_thumbmark'] ?? '',
            'head_thumbmark'  => $resident['right_thumbmark']  ?? '',
            'head_id_front'   => $resident['id_picture_front'] ?? '',
            'head_id_back'    => $resident['id_picture_back']  ?? '',
        ];

        foreach ($headFiles as $type => $path) {
            if (!empty($path)) {
                $meta->fromArray([[$hh, $type, '', $path]], null, 'A' . $metaRow);
                $metaRow++;
            }
        }

        foreach (($resident['family_members'] ?? []) as $member) {
            $memberFiles = [
                'member_photo'       => $member['photo']           ?? '',
                'member_id_front'    => $member['id_photo_front']  ?? '',
                'member_id_back'     => $member['id_photo_back']   ?? '',
                'member_birth_cert'  => $member['birth_certificate'] ?? '',
            ];
            foreach ($memberFiles as $type => $path) {
                if (!empty($path)) {
                    $meta->fromArray([[$hh, $type, $member['name'], $path]], null, 'A' . $metaRow);
                    $metaRow++;
                }
            }
        }
    }

    // Hide the metadata sheet so it doesn't confuse users
    $meta->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

    $filename = 'FACED_Export_' . date('Y-m-d') . '.xlsx';
    $writer   = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;

}

/**
 * Display import form
 */
public function import()
{
    return view('beneficiaries/import');
}

/**
 * Process uploaded Excel/CSV file for import
 */
public function upload()
{
    // Validate file upload
    $file = $this->request->getFile('import_file');
    
    if (!$file || !$file->isValid()) {
        session()->setFlashdata('error', 'Please select a valid file to upload.');
        return redirect()->back();
    }
    
    // Check file extension
    $extension = $file->getClientExtension();
    if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
        session()->setFlashdata('error', 'Invalid file type. Please upload Excel (.xlsx, .xls) or CSV file.');
        return redirect()->back();
    }
    
    // Move file to temp location
    $tempPath = WRITEPATH . 'uploads/';
    if (!is_dir($tempPath)) {
        mkdir($tempPath, 0777, true);
    }
    
    $newName = $file->getRandomName();
    $file->move($tempPath, $newName);
    $filepath = $tempPath . $newName;
    
// In upload() method, replace the success message section:
try {
    // Process based on file type
    if ($extension === 'csv') {
        $this->processCsvImport($filepath);
    } else {
        $this->processExcelImport($filepath);
    }
    
$summary = session()->getFlashdata('import_summary');
if ($summary) {
    $message = "Import completed! ";
    $message .= "✓ Added: {$summary['inserted']} | ";
    $message .= "⟳ Updated: {$summary['updated']} | ";
    $message .= "⚠️ Duplicate Skipped: {$summary['duplicate']} | ";
    $message .= "✗ Failed: {$summary['failed']}";
    session()->setFlashdata('success', $message);
    
    // If there are duplicates, also show them in errors
    $duplicateErrors = session()->getFlashdata('import_errors') ?: [];
    if (!empty($duplicateDetails)) {
        $duplicateErrors = array_merge($duplicateErrors, $duplicateDetails);
        session()->setFlashdata('import_errors', $duplicateErrors);
    }
} else {
        session()->setFlashdata('success', 'Residents imported successfully!');
    }
    
} catch (\Exception $e) {
    session()->setFlashdata('error', 'Import failed: ' . $e->getMessage());
}
    
    // Clean up temp file
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    
    return redirect()->to('/beneficiaries/resident-list');
}

private function processExcelImport($filepath)
{
    require_once ROOTPATH . 'vendor/autoload.php';
    
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Get all rows as array
    $rows = $worksheet->toArray();
    
// AFTER — extract image path map first:
    if (empty($rows) || count($rows) < 2) {
        throw new \Exception('No data found in the file.');
    }

    // ── Read hidden image-path metadata sheet if present ─────────────────────
    $imagePathMap = []; // ['household_no' => ['head_photo'=>'...', 'members'=>['Name'=>['photo'=>'...']]]]
    try {
        $metaSheet = $spreadsheet->getSheetByName('_image_paths');
        if ($metaSheet) {
            $metaRows = $metaSheet->toArray();
            array_shift($metaRows); // remove header
            foreach ($metaRows as $mr) {
                $hh       = trim($mr[0] ?? '');
                $type     = trim($mr[1] ?? '');
                $mName    = trim($mr[2] ?? '');
                $filePath = trim($mr[3] ?? '');
                if (empty($hh) || empty($filePath)) continue;

                if (!isset($imagePathMap[$hh])) {
                    $imagePathMap[$hh] = ['head' => [], 'members' => []];
                }

                switch ($type) {
                    case 'head_photo':      $imagePathMap[$hh]['head']['photo']            = $filePath; break;
                    case 'head_signature':  $imagePathMap[$hh]['head']['signature_thumbmark'] = $filePath; break;
                    case 'head_thumbmark':  $imagePathMap[$hh]['head']['right_thumbmark']  = $filePath; break;
                    case 'head_id_front':   $imagePathMap[$hh]['head']['id_picture_front'] = $filePath; break;
                    case 'head_id_back':    $imagePathMap[$hh]['head']['id_picture_back']  = $filePath; break;
                    case 'member_photo':      $imagePathMap[$hh]['members'][$mName]['photo']           = $filePath; break;
                    case 'member_id_front':   $imagePathMap[$hh]['members'][$mName]['id_photo_front']  = $filePath; break;
                    case 'member_id_back':    $imagePathMap[$hh]['members'][$mName]['id_photo_back']   = $filePath; break;
                    case 'member_birth_cert': $imagePathMap[$hh]['members'][$mName]['birth_certificate'] = $filePath; break;
                }
            }
        }
    } catch (\Exception $e) {
        log_message('debug', 'No image metadata sheet found or error reading it: ' . $e->getMessage());
    }
    
    $db = \Config\Database::connect();
    $db->transStart();
    
$importedCount = 0;
$updatedCount = 0;
$failedCount = 0;
$errors = [];
$successDetails = [];
    
    // Find all card start positions
    $cardStarts = [];
    for ($i = 0; $i < count($rows); $i++) {
        $row = $rows[$i];
        foreach ($row as $cellValue) {
            if (is_string($cellValue) && strpos($cellValue, 'HEAD OF THE FAMILY') !== false) {
                $cardStarts[] = $i;
                break;
            }
        }
    }
    
    log_message('debug', 'Found ' . count($cardStarts) . ' cards to process');
    
    foreach ($cardStarts as $index => $startRow) {
        try {
            $residentData = $this->parseFacedCardLenient($rows, $startRow);
            
            // CRITICAL: Check required fields
            if (empty($residentData['first_name']) || empty($residentData['last_name'])) {
                $failedCount++;
                $errorMsg = "⚠️ CARD #" . ($index + 1) . " (row {$startRow}): Missing required fields - First Name: '" . ($residentData['first_name'] ?? 'EMPTY') . "', Last Name: '" . ($residentData['last_name'] ?? 'EMPTY') . "'";
                $errors[] = $errorMsg;
                log_message('error', $errorMsg);
                continue;
            }
            
// Try to find existing resident - ONLY by household number
$existing = null;

// Match by household_no only (most reliable)
if (!empty($residentData['household_no'])) {
    $existing = $this->residentModel
        ->where('household_no', $residentData['household_no'])
        ->first();
}

if ($existing) {
    // UPDATE existing resident (by household_no match)
    $updateData = $residentData;
    unset($updateData['family_members']);
    unset($updateData['qr_code_token']);
    unset($updateData['household_qr_token']);
    unset($updateData['photo_url']);

    // ── Restore head image paths BEFORE update ────────────────────────────
    $hh = $existing['household_no'] ?? '';
    $headMeta = $imagePathMap[$hh]['head'] ?? [];
    foreach ($headMeta as $field => $path) {
        $fullPath = FCPATH . ltrim($path, '/');
        log_message('debug', "UPDATE head image check: {$field} => {$fullPath} exists=" . (file_exists($fullPath) ? 'YES' : 'NO'));
        if (file_exists($fullPath)) {
            $updateData[$field] = $path;
        }
    }

    if ($this->residentModel->update($existing['id'], $updateData)) {
        $membersUpdated = 0;
        $membersInserted = 0;

        if (!empty($residentData['family_members'])) {
            $existingMembers = $this->familyMemberModel->getByResidentId($existing['id']);
            $existingByName = [];
            foreach ($existingMembers as $em) {
                $existingByName[strtoupper(trim($em['name']))] = $em;
            }

            foreach ($residentData['family_members'] as $member) {
                if (empty($member['name'])) continue;

                $member['resident_id'] = $existing['id'];
                $nameKey = strtoupper(trim($member['name']));

                // ── Restore member image paths ────────────────────────────
                $memberMeta = $imagePathMap[$hh]['members'][$member['name']] ?? [];
                foreach ($memberMeta as $field => $path) {
                    $fullPath = FCPATH . ltrim($path, '/');
                    log_message('debug', "UPDATE member image check: {$member['name']} {$field} => {$fullPath} exists=" . (file_exists($fullPath) ? 'YES' : 'NO'));
                    if (file_exists($fullPath)) {
                        $member[$field] = $path;
                    }
                }

                if (isset($existingByName[$nameKey])) {
                    $memberUpdate = $member;
                    unset($memberUpdate['qr_code_token']);
                    $this->familyMemberModel->update($existingByName[$nameKey]['id'], $memberUpdate);
                    $membersUpdated++;
                } else {
                    $member['member_id'] = $this->familyMemberModel->generateMemberId($existing['household_no']);
                    $member['qr_code_token'] = bin2hex(random_bytes(16));
                    $this->familyMemberModel->insert($member);
                    $membersInserted++;
                }
            }
        }

        $updatedCount++;
        $successDetails[] = "🔄 UPDATED: {$residentData['first_name']} {$residentData['last_name']} (HH#: {$residentData['household_no']}) - Updated {$membersUpdated} members, Added {$membersInserted}";
    } else {
        $failedCount++;
        $errorMsg = "❌ UPDATE FAILED for {$residentData['first_name']} {$residentData['last_name']}: " . implode(', ', $this->residentModel->errors());
        $errors[] = $errorMsg;
    }
} else {
                // INSERT new resident
                // Generate required fields
                $residentData['qr_code_token'] = bin2hex(random_bytes(16));
                $residentData['household_qr_token'] = bin2hex(random_bytes(16));
                $residentData['status'] = 'active';
                $residentData['registration_date'] = date('Y-m-d');
                $residentData['data_privacy_consent'] = 1;
                $residentData['data_privacy_date'] = date('Y-m-d');
                
                // Generate household number if not provided
                if (empty($residentData['household_no'])) {
                    $residentData['household_no'] = $this->residentModel->generateHouseholdNo();
                }
                
                // Ensure numeric fields have values
                $numericFields = ['vulnerable_older_persons', 'vulnerable_pregnant', 'vulnerable_lactating', 'vulnerable_pwd', 'monthly_income'];
                foreach ($numericFields as $field) {
                    if (empty($residentData[$field]) && $residentData[$field] !== 0) {
                        $residentData[$field] = 0;
                    }
                }
                
                // Remove family_members before insert
                $familyMembersData = $residentData['family_members'] ?? [];
                unset($residentData['family_members']);
                
                // ── Restore image file paths from metadata sheet ──────────────
                $hh = $residentData['household_no'] ?? '';
                if (!empty($hh) && isset($imagePathMap[$hh]['head'])) {
                    foreach ($imagePathMap[$hh]['head'] as $field => $path) {
                        $fullPath = FCPATH . ltrim($path, '/');
                        log_message('debug', "INSERT head image check: {$field} => {$fullPath} exists=" . (file_exists($fullPath) ? 'YES' : 'NO'));
                        if (file_exists($fullPath)) {
                            $residentData[$field] = $path;
                        }
                    }
                }

                $residentId = $this->residentModel->insert($residentData);
                
                if ($residentId) {
                    $membersInserted = 0;
                    
foreach ($familyMembersData as $member) {
                        if (empty($member['name'])) continue;

                        $member['resident_id'] = $residentId;
                        $member['member_id'] = $this->familyMemberModel->generateMemberId($residentData['household_no']);
                        $member['qr_code_token'] = bin2hex(random_bytes(16));
                        $member['status'] = 'active';

                        // ── Restore member image paths ────────────────────────
        $memberMeta = $imagePathMap[$hh]['members'][$member['name']] ?? [];
        foreach ($memberMeta as $field => $path) {
            $fullPath = FCPATH . ltrim($path, '/');
            log_message('debug', "INSERT member image check: {$member['name']} {$field} => {$fullPath} exists=" . (file_exists($fullPath) ? 'YES' : 'NO'));
            if (file_exists($fullPath)) {
                $member[$field] = $path;
            }
        }

                        // ... rest of existing member insert code
                        
                        // Ensure numeric fields
                        if (empty($member['age']) && !empty($member['birthdate'])) {
                            $birthDate = new \DateTime($member['birthdate']);
                            $today = new \DateTime('today');
                            $member['age'] = $birthDate->diff($today)->y;
                        }
                        
                        $this->familyMemberModel->insert($member);
                        $membersInserted++;
                    }
                    
                    $importedCount++;
                    $successDetails[] = "➕ INSERTED: {$residentData['first_name']} {$residentData['last_name']} (HH#: {$residentData['household_no']}) - Added {$membersInserted} family members";
                } else {
                    $failedCount++;
                    $errorMsg = "❌ INSERT FAILED for {$residentData['first_name']} {$residentData['last_name']}: " . implode(', ', $this->residentModel->errors());
                    $errors[] = $errorMsg;
                    log_message('error', $errorMsg);
                }
            }
            
        } catch (\Exception $e) {
            $failedCount++;
            $errorMsg = "⚠️ CARD #" . ($index + 1) . " ERROR: " . $e->getMessage();
            $errors[] = $errorMsg;
            log_message('error', $errorMsg);
        }
    }
    
    $db->transComplete();
    
    // Store results
    if (!empty($successDetails)) {
        // Limit to 50 details to avoid memory issues
        session()->setFlashdata('import_success_details', array_slice($successDetails, 0, 50));
    }
    
    if (!empty($errors)) {
        session()->setFlashdata('import_errors', $errors);
    }
    
session()->setFlashdata('import_summary', [
    'inserted' => $importedCount,
    'updated' => $updatedCount,
    'failed' => $failedCount,
    'total' => $importedCount + $updatedCount + $failedCount
]);
    
    log_message('debug', "Import completed. Inserted: $importedCount, Updated: $updatedCount, Failed: $failedCount");
    
    return $importedCount + $updatedCount;
}
private function parseFacedCardLenient($rows, $startRow)
{
    $resident = [];
    $familyMembers = [];
    $dataStartRow = $startRow + 1;
    
    // Default values
    $resident = [
        'household_no' => null,
        'region' => '', 'province' => '', 'district' => '', 'city_municipality' => '', 
        'barangay' => '', 'evacuation_center' => '',
        'last_name' => '', 'first_name' => '', 'middle_name' => '', 'name_extension' => '',
        'birthdate' => null, 'age' => null, 'birthplace' => '', 'sex' => '', 
        'civil_status' => '', 'mother_maiden_name' => '', 'religion' => '', 'occupation' => '',
        'monthly_income' => 0, 'id_card_presented' => '', 'id_card_number' => '',
        'contact_number' => '', 'alternate_number' => '',
        'house_no' => '', 'street' => '', 'subdivision' => '', 'permanent_barangay' => '',
        'permanent_city' => '', 'permanent_province' => '', 'zip_code' => '',
        'is_4ps_beneficiary' => 0, 'ip_ethnicity' => '',
        'vulnerable_older_persons' => 0, 'vulnerable_pregnant' => 0, 
        'vulnerable_lactating' => 0, 'vulnerable_pwd' => 0,
        'ownership_status' => '', 'shelter_damage' => 'No Damage',
        'registration_date' => date('Y-m-d'), 'barangay_captain_name' => '', 'lswdo_name' => '',
        'data_privacy_consent' => 1
    ];
    
    // Extract SERIAL NUMBER - search in rows before startRow
    for ($i = max(0, $startRow - 15); $i <= $startRow; $i++) {
        if (!isset($rows[$i])) continue;
        foreach ($rows[$i] as $cellValue) {
            if (is_string($cellValue) && preg_match('/SERIAL\s+NUMBER:\s*(\S+)/i', $cellValue, $m)) {
                $resident['household_no'] = str_pad((string)((int)ltrim($m[1], '0') ?: 0), 4, '0', STR_PAD_LEFT);
                break 2;
            }
        }
    }
    
    // LOCATION section - find the location row and extract all 6 fields
    for ($i = max(0, $startRow - 10); $i < $startRow; $i++) {
        if (!isset($rows[$i])) continue;
        $firstCell = trim($rows[$i][0] ?? '');
        if (stripos($firstCell, 'LOCATION OF THE AFFECTED FAMILY') !== false && isset($rows[$i + 1])) {
            $locRow = $rows[$i + 1];
            // Extract from each column (1-6 correspond to columns 0-5 in zero-index)
            $resident['region'] = $this->extractValueFromMergedCell($locRow[0] ?? '');
            $resident['province'] = $this->extractValueFromMergedCell($locRow[3] ?? '');
            $resident['district'] = $this->extractValueFromMergedCell($locRow[6] ?? '');
            $resident['city_municipality'] = $this->extractValueFromMergedCell($locRow[9] ?? '');
            $resident['barangay'] = $this->extractValueFromMergedCell($locRow[12] ?? '');
            $resident['evacuation_center'] = $this->extractValueFromMergedCell($locRow[14] ?? '');
            break;
        }
    }
    
    // HEAD OF FAMILY section - parse all rows
    if (isset($rows[$dataStartRow])) {
        $row = $rows[$dataStartRow];
        $resident['last_name'] = $this->extractValueFromMergedCell($row[0] ?? '');
        $resident['civil_status'] = ucwords(strtolower($this->extractValueFromMergedCell($row[4] ?? '')));
        $resident['mother_maiden_name'] = $this->extractValueFromMergedCell($row[8] ?? '');
    }
    
    if (isset($rows[$dataStartRow + 1])) {
        $row = $rows[$dataStartRow + 1];
        $resident['first_name'] = $this->extractValueFromMergedCell($row[0] ?? '');
        $resident['religion'] = ucwords(strtolower($this->extractValueFromMergedCell($row[4] ?? '')));
        $resident['occupation'] = ucwords(strtolower($this->extractValueFromMergedCell($row[8] ?? '')));
    }
    
    if (isset($rows[$dataStartRow + 2])) {
        $row = $rows[$dataStartRow + 2];
        $resident['middle_name'] = $this->extractValueFromMergedCell($row[0] ?? '');
        $resident['name_extension'] = $this->extractValueFromMergedCell($row[4] ?? '');
        $resident['age'] = $this->extractValueFromMergedCell($row[12] ?? '');
        
        $bdRaw = $this->extractValueFromMergedCell($row[8] ?? '');
        if (!empty($bdRaw) && strpos($bdRaw, '--') === false && strlen($bdRaw) > 5) {
            $parsed = $this->parseDate($bdRaw);
            if ($parsed && $parsed != '1970-01-01') {
                $resident['birthdate'] = $parsed;
            }
        }
    }
    
    if (isset($rows[$dataStartRow + 3])) {
        $row = $rows[$dataStartRow + 3];
        $resident['birthplace'] = $this->extractValueFromMergedCell($row[0] ?? '');
        $resident['sex'] = ucfirst(strtolower($this->extractValueFromMergedCell($row[4] ?? '')));
        $incomeRaw = $this->extractValueFromMergedCell($row[8] ?? '');
        $resident['monthly_income'] = is_numeric($incomeRaw) ? (float)$incomeRaw : 0;
        $resident['id_card_presented'] = $this->extractValueFromMergedCell($row[12] ?? '');
    }
    
    if (isset($rows[$dataStartRow + 4])) {
        $row = $rows[$dataStartRow + 4];
        $resident['id_card_number'] = $this->extractValueFromMergedCell($row[0] ?? '');
        
        $contactRaw = trim($row[8] ?? '');
        if (preg_match('/CONTACT\s*#:\s*(\d+)\s+ALT:\s*(\d*)/i', $contactRaw, $m)) {
            if (!empty($m[1])) $resident['contact_number'] = $m[1];
            if (!empty($m[2])) $resident['alternate_number'] = $m[2];
        } elseif (preg_match('/(\d{10,11})/', $contactRaw, $m)) {
            $resident['contact_number'] = $m[1];
        }
    }
    
    // PERMANENT ADDRESS
    if (isset($rows[$dataStartRow + 5])) {
        $addrRaw = trim($rows[$dataStartRow + 5][0] ?? '');
        $addrRaw = preg_replace('/^23\.\s*PERMANENT ADDRESS:\s*/i', '', $addrRaw);
        if (!empty($addrRaw)) {
            $addrFields = $this->parsePermanentAddress($addrRaw);
            foreach ($addrFields as $field => $value) {
                $resident[$field] = $value;
            }
        }
    }
    
    // 4Ps and IP Ethnicity
    if (isset($rows[$dataStartRow + 6])) {
        $othersText = trim($rows[$dataStartRow + 6][0] ?? '');
        $resident['is_4ps_beneficiary'] = (strpos($othersText, '☑') !== false) ? 1 : 0;
        if (preg_match('/Ethnicity:\s*(.+?)(?:\s*$)/i', $othersText, $m)) {
            $resident['ip_ethnicity'] = trim($m[1]);
        }
    }
    
// FAMILY MEMBERS - find the table and parse (IMPROVED)
$familyStartRow = null;
// Search more rows down (up to 40 rows after the head section)
for ($i = $dataStartRow + 5; $i <= min($dataStartRow + 40, count($rows) - 1); $i++) {
    if (!isset($rows[$i][0])) continue;
    $cellVal = trim($rows[$i][0] ?? '');
    // Look for the FAMILY INFORMATION or FAMILY MEMBERS header
    if (stripos($cellVal, 'FAMILY INFORMATION') !== false || stripos($cellVal, 'FAMILY MEMBERS') !== false) {
        $familyStartRow = $i + 2; // Skip header row (the column headers)
        log_message('debug', "Found family header at row {$i}, data starts at row {$familyStartRow}");
        break;
    }
}

if ($familyStartRow !== null) {
    // Parse family members until we hit the VULNERABLE section or end of table
    for ($i = $familyStartRow; $i <= min($familyStartRow + 20, count($rows) - 1); $i++) {
        if (!isset($rows[$i][0])) continue;
        
        // Get the first column which contains the member name (columns 0-4 are merged for name)
        $memberName = '';
        // Check column 0 first (where name should be)
        if (isset($rows[$i][0])) {
            $memberName = trim($rows[$i][0]);
        }
        // If column 0 is empty, check column 1 or 2 (sometimes Excel shifts columns)
        if (empty($memberName) && isset($rows[$i][1])) {
            $memberName = trim($rows[$i][1]);
        }
        
        // Stop conditions - look for section markers
        if (empty($memberName)) {
            // Don't continue if empty, but check next row
            continue;
        }
        
        // Stop when we hit VULNERABLE or other sections
        if (preg_match('/VULNERABLE|HOUSE OWNERSHIP|SHELTER DAMAGE|Signature|DATA PRIVACY/i', $memberName)) {
            log_message('debug', "Stopping family parse at row {$i} - found: {$memberName}");
            break;
        }
        
        // Skip if it looks like a header or has no meaningful name
        if (strlen($memberName) < 2 || stripos($memberName, 'FAMILY') !== false || stripos($memberName, 'RELATION') !== false) {
            continue;
        }
        
        // Extract family member data with proper column mapping
        // Based on FACED export structure:
        // Col 0-4: Name (merged)
        // Col 5-7: Relation to Family Head (merged)
        // Col 8-9: Birthdate (merged)
        // Col 10: Age
        // Col 11: Sex
        // Col 12-13: Education (merged)
        // Col 14: Occupation
        // Col 15: Remarks
        
        $relation = '';
        if (isset($rows[$i][5])) {
            $relation = trim($rows[$i][5]);
            if (empty($relation) && isset($rows[$i][6])) $relation = trim($rows[$i][6]);
        }
        
        $bdRaw = '';
        if (isset($rows[$i][8])) {
            $bdRaw = trim($rows[$i][8]);
            if (empty($bdRaw) && isset($rows[$i][9])) $bdRaw = trim($rows[$i][9]);
        }
        
        $age = '';
        if (isset($rows[$i][10])) $age = trim($rows[$i][10]);
        
        $sex = '';
        if (isset($rows[$i][11])) $sex = trim($rows[$i][11]);
        
        $education = '';
        if (isset($rows[$i][12])) {
            $education = trim($rows[$i][12]);
            if (empty($education) && isset($rows[$i][13])) $education = trim($rows[$i][13]);
        }
        
        $occupation = '';
        if (isset($rows[$i][14])) $occupation = trim($rows[$i][14]);
        
        $remarks = '';
        if (isset($rows[$i][15])) $remarks = trim($rows[$i][15]);
        
        // Parse birthdate
        $bdParsed = null;
        if (!empty($bdRaw) && strpos($bdRaw, '--') === false && strlen($bdRaw) > 5) {
            // Remove any label prefixes like "11. BIRTHDATE" if present
            $bdRaw = preg_replace('/^\d+\.\s*BIRTHDATE\s*/i', '', $bdRaw);
            $bdParsed = $this->parseDate($bdRaw);
        }
        
        // Calculate age from birthdate if missing or invalid
        if ((empty($age) || $age == '0' || !is_numeric($age)) && $bdParsed) {
            $birthDate = new \DateTime($bdParsed);
            $today = new \DateTime('today');
            $age = $birthDate->diff($today)->y;
        }
        
        // Only add if we have a valid name
        if (!empty($memberName) && strlen($memberName) >= 2) {
            $familyMembers[] = [
                'name' => $memberName,
                'relation' => ucwords(strtolower($relation)),
                'birthdate' => $bdParsed,
                'age' => $age,
                'sex' => ucwords(strtolower($sex)),
                'education' => $education,
                'occupation' => ucwords(strtolower($occupation)),
                'remarks' => $remarks,
                'status' => 'active'
            ];
            log_message('debug', "Added family member: {$memberName} (Relation: {$relation})");
        }
    }
}

// If no family members found, try an alternative parsing method
if (empty($familyMembers)) {
    log_message('debug', "No family members found with primary method, trying alternative...");
    
    // Alternative: Look for the actual data rows by scanning for names in column 0
    for ($i = $dataStartRow + 8; $i <= min($dataStartRow + 35, count($rows) - 1); $i++) {
        if (!isset($rows[$i][0])) continue;
        
        $cellVal = trim($rows[$i][0] ?? '');
        if (empty($cellVal)) continue;
        
        // Skip section headers
        if (preg_match('/VULNERABLE|HOUSE OWNERSHIP|SHELTER DAMAGE|Signature|DATA PRIVACY|FAMILY/i', $cellVal)) {
            break;
        }
        
        // Check if this looks like a person's name (contains letters and not just numbers)
        if (preg_match('/[A-Za-z]/', $cellVal) && strlen($cellVal) > 2) {
            $familyMembers[] = [
                'name' => $cellVal,
                'relation' => isset($rows[$i][5]) ? ucwords(strtolower(trim($rows[$i][5]))) : '',
                'birthdate' => null,
                'age' => isset($rows[$i][10]) ? trim($rows[$i][10]) : '',
                'sex' => isset($rows[$i][11]) ? ucwords(strtolower(trim($rows[$i][11]))) : '',
                'education' => isset($rows[$i][12]) ? trim($rows[$i][12]) : '',
                'occupation' => isset($rows[$i][14]) ? ucwords(strtolower(trim($rows[$i][14]))) : '',
                'remarks' => isset($rows[$i][15]) ? trim($rows[$i][15]) : '',
                'status' => 'active'
            ];
            log_message('debug', "Added family member via alternative: {$cellVal}");
        }
    }
}

log_message('debug', "Total family members parsed: " . count($familyMembers));
    
    // VULNERABLE, OWNERSHIP, SHELTER
    for ($i = $dataStartRow + 14; $i <= min($dataStartRow + 30, count($rows) - 1); $i++) {
        if (!isset($rows[$i][0])) continue;
        $cellVal = trim($rows[$i][0] ?? '');
        if (stripos($cellVal, 'VULNERABLE MEMBERS') !== false || stripos($cellVal, '26.') !== false) {
            $resident['vulnerable_older_persons'] = $this->extractNumberFromText($cellVal, 'Older Persons');
            $resident['vulnerable_pregnant'] = $this->extractNumberFromText($cellVal, 'Pregnant');
            $resident['vulnerable_lactating'] = $this->extractNumberFromText($cellVal, 'Lactating');
            $resident['vulnerable_pwd'] = $this->extractNumberFromText($cellVal, 'PWD');
            
            // Right side data (column 8)
            $rightText = $rows[$i][8] ?? '';
            if (strpos($rightText, '☑ Owner') !== false) $resident['ownership_status'] = 'Owner';
            elseif (strpos($rightText, '☑ Renter') !== false) $resident['ownership_status'] = 'Renter';
            elseif (strpos($rightText, '☑ Sharer') !== false) $resident['ownership_status'] = 'Sharer';
            
            if (strpos($rightText, '☑ Partially Damaged') !== false) $resident['shelter_damage'] = 'Partially Damaged';
            elseif (strpos($rightText, '☑ Totally Damaged') !== false) $resident['shelter_damage'] = 'Totally Damaged';
            break;
        }
    }
    
    // SIGNATURES section
    for ($i = $dataStartRow + 15; $i <= min($dataStartRow + 35, count($rows) - 1); $i++) {
        if (!isset($rows[$i][0])) continue;
        $cellVal = trim($rows[$i][0] ?? '');
        if (stripos($cellVal, 'Signature/Thumbmark') !== false) {
            // Date Registered (column 5)
            if (isset($rows[$i][5])) {
                $dateRaw = trim($rows[$i][5]);
                $dateRaw = preg_replace('/^Date Registered\s*/i', '', $dateRaw);
                $dateRaw = trim(explode("\n", $dateRaw)[0]);
                if (!empty($dateRaw) && ($pd = $this->parseDate($dateRaw))) {
                    $resident['registration_date'] = $pd;
                }
            }
            
            // Barangay Captain (column 8)
            if (isset($rows[$i][8])) {
                $captainRaw = trim($rows[$i][8]);
                $captainRaw = preg_replace('/^Name\/Signature of Brgy\. Captain\s*/i', '', $captainRaw);
                $resident['barangay_captain_name'] = trim($captainRaw);
            }
            
            // LSWDO (column 12)
            if (isset($rows[$i][12])) {
                $lswdoRaw = trim($rows[$i][12]);
                $lswdoRaw = preg_replace('/^Name\/Signature of LSWDO\s*/i', '', $lswdoRaw);
                $resident['lswdo_name'] = trim($lswdoRaw);
            }
            break;
        }
    }
    
    // Calculate age from birthdate if missing
    if ((empty($resident['age']) || $resident['age'] == '0') && !empty($resident['birthdate'])) {
        $birthDate = new \DateTime($resident['birthdate']);
        $today = new \DateTime('today');
        $resident['age'] = $birthDate->diff($today)->y;
    }
    
    $resident['family_members'] = $familyMembers;
    
    return $resident;
}

// Add this helper method if not already present
private function extractNumberFromText($text, $label)
{
    if (preg_match('/' . preg_quote($label, '/') . ':\s*(\d+)/i', $text, $m)) {
        return (int)$m[1];
    }
    return 0;
}

private function extractSimpleValue($cellValue)
{
    $value = trim((string)$cellValue);
    if ($value === '') return '';
    
    if (strpos($value, "\n") !== false) {
        $parts = explode("\n", $value, 2);
        $result = trim(end($parts));
        // Remove numeric prefixes like "7. " from the extracted value
        $result = preg_replace('/^\d+\.\s*/', '', $result);
        return $result;
    }
    
    // Remove numeric prefixes
    $value = preg_replace('/^\d+\.\s*/', '', $value);
    return $value;
}

private function parseFacedCard($rows, $startRow)
{
    log_message('debug', "parseFacedCard called with startRow: $startRow");
 
    $resident      = [];
    $familyMembers = [];
    $dataStartRow  = $startRow + 1; // row immediately after "HEAD OF THE FAMILY"
 
    // Don't fail if not enough rows - just try to parse what we can
    // Removed the strict row count check
 
    // ── 1. SERIAL NUMBER / HOUSEHOLD NO ─────────────────────────────────────
    for ($i = max(0, $startRow - 10); $i <= $startRow; $i++) {
        if (isset($rows[$i])) {
            foreach ($rows[$i] as $cellValue) {
                if (is_string($cellValue) &&
                    preg_match('/SERIAL\s+NUMBER:\s*(\S+)/i', $cellValue, $m)) {
                    $resident['household_no'] = str_pad(
                        (string)((int)ltrim($m[1], '0') ?: 0),
                        4, '0', STR_PAD_LEFT
                    );
                    log_message('debug', "Found household_no: " . $resident['household_no']);
                    break 2;
                }
            }
        }
    }
    
    // If no household_no found, generate one later
    if (empty($resident['household_no'])) {
        $resident['household_no'] = null; // Will be generated on insert
    }
 
    // ── 2. LOCATION ──────────────────────────────────────────────────────────
    for ($i = max(0, $startRow - 6); $i < $startRow; $i++) {
        if (isset($rows[$i][0]) &&
            stripos($rows[$i][0], 'LOCATION OF THE AFFECTED FAMILY') !== false) {
            $locRow = $rows[$i + 1] ?? [];
            $resident['region']            = $this->extractLabelValue($locRow[0]  ?? '') ?? '';
            $resident['province']          = $this->extractLabelValue($locRow[3]  ?? '') ?? '';
            $resident['district']          = $this->extractLabelValue($locRow[6]  ?? '') ?? '';
            $resident['city_municipality'] = $this->extractLabelValue($locRow[9]  ?? '') ?? '';
            $resident['barangay']          = $this->extractLabelValue($locRow[12] ?? '') ?? '';
            $resident['evacuation_center'] = $this->extractLabelValue($locRow[14] ?? '') ?? '';
            break;
        }
    }
    
    // Set defaults for location if missing
    $resident['region'] = $resident['region'] ?? '';
    $resident['province'] = $resident['province'] ?? '';
    $resident['district'] = $resident['district'] ?? '';
    $resident['city_municipality'] = $resident['city_municipality'] ?? '';
    $resident['barangay'] = $resident['barangay'] ?? '';
    $resident['evacuation_center'] = $resident['evacuation_center'] ?? '';
 
    // ── 3. HEAD OF FAMILY ROWS ───────────────────────────────────────────────
    // Safely extract each row with fallbacks
    
    // Row +0 - Last Name, Civil Status, Mother's Maiden Name
    if (isset($rows[$dataStartRow])) {
        $row = $rows[$dataStartRow];
        $resident['last_name'] = $this->extractLabelValue($row[0] ?? '') ?? '';
        $resident['civil_status'] = $this->titleCaseValue($this->extractLabelValue($row[4] ?? '') ?? '');
        $resident['mother_maiden_name'] = $this->extractLabelValue($row[8] ?? '') ?? '';
    }
 
    // Row +1 - First Name, Religion, Occupation
    if (isset($rows[$dataStartRow + 1])) {
        $row = $rows[$dataStartRow + 1];
        $resident['first_name'] = $this->extractLabelValue($row[0] ?? '') ?? '';
        $resident['religion'] = $this->titleCaseValue($this->extractLabelValue($row[4] ?? '') ?? '');
        $resident['occupation'] = $this->titleCaseValue($this->extractLabelValue($row[8] ?? '') ?? '');
    }
 
    // Row +2 - Middle Name, Name Ext, Birthdate, Age
    if (isset($rows[$dataStartRow + 2])) {
        $row = $rows[$dataStartRow + 2];
        $resident['middle_name'] = $this->extractLabelValue($row[0] ?? '') ?? '';
        $resident['name_extension'] = $this->extractLabelValue($row[4] ?? '') ?? '';
        $resident['age'] = $this->extractLabelValue($row[12] ?? '') ?? '';
 
        $bdRaw = $this->extractLabelValue($row[8] ?? '');
        // Only parse valid dates, skip invalid ones like "11-30--0001"
        if (!empty($bdRaw) && strpos($bdRaw, '--') === false && preg_match('/^\d{1,2}[-/]\d{1,2}[-/]\d{2,4}$/', $bdRaw)) {
            $parsed = $this->parseDate($bdRaw);
            $resident['birthdate'] = $parsed ?: null;
        } else {
            $resident['birthdate'] = null;
        }
    }
 
    // Row +3 - Birthplace, Sex, Monthly Income, ID Card
    if (isset($rows[$dataStartRow + 3])) {
        $row = $rows[$dataStartRow + 3];
        $resident['birthplace'] = $this->extractLabelValue($row[0] ?? '') ?? '';
        $resident['sex'] = $this->titleCaseValue($this->extractLabelValue($row[4] ?? '') ?? '');
        
        $incomeRaw = $this->extractLabelValue($row[8] ?? '');
        $resident['monthly_income'] = is_numeric($incomeRaw) ? (float)$incomeRaw : 0;
        
        $resident['id_card_presented'] = $this->extractLabelValue($row[12] ?? '') ?? '';
    }
 
    // Row +4 - ID Card Number, Contact
    if (isset($rows[$dataStartRow + 4])) {
        $row = $rows[$dataStartRow + 4];
        $resident['id_card_number'] = $this->extractLabelValue($row[0] ?? '') ?? '';
        
        $contactRaw = trim($row[8] ?? '');
        if (preg_match('/CONTACT\s*#:\s*(\d+)\s+ALT:\s*(\d*)/i', $contactRaw, $m)) {
            if (!empty($m[1])) $resident['contact_number'] = $m[1];
            if (!empty($m[2])) $resident['alternate_number'] = $m[2];
        } elseif (preg_match('/(\d{10,11})/', $contactRaw, $m)) {
            $resident['contact_number'] = $m[1];
        } else {
            $resident['contact_number'] = '';
            $resident['alternate_number'] = '';
        }
    }
 
    // Row +5 — Permanent Address
    if (isset($rows[$dataStartRow + 5])) {
        $addrRaw = trim($rows[$dataStartRow + 5][0] ?? '');
        $addrRaw = preg_replace('/^23\.\s*PERMANENT ADDRESS:\s*/i', '', $addrRaw);
        if (!empty($addrRaw)) {
            $addrFields = $this->parsePermanentAddress($addrRaw);
            foreach ($addrFields as $field => $value) {
                $resident[$field] = $value;
            }
        } else {
            // Set empty defaults
            $resident['house_no'] = '';
            $resident['street'] = '';
            $resident['subdivision'] = '';
            $resident['permanent_barangay'] = '';
            $resident['permanent_city'] = '';
            $resident['permanent_province'] = '';
            $resident['zip_code'] = '';
        }
    }
 
    // Row +6 — 24. OTHERS
    if (isset($rows[$dataStartRow + 6])) {
        $othersText = trim($rows[$dataStartRow + 6][0] ?? '');
        $resident['is_4ps_beneficiary'] = (strpos($othersText, '☑') !== false) ? 1 : 0;
        if (preg_match('/Ethnicity:\s*(.+?)(?:\s*$)/i', $othersText, $m)) {
            $candidate = trim($m[1]);
            if ($candidate !== '') {
                $resident['ip_ethnicity'] = $candidate;
            } else {
                $resident['ip_ethnicity'] = '';
            }
        } else {
            $resident['ip_ethnicity'] = '';
        }
    } else {
        $resident['is_4ps_beneficiary'] = 0;
        $resident['ip_ethnicity'] = '';
    }
 
    // ── 4. FAMILY MEMBERS ────────────────────────────────────────────────────
    $familyDataStartRow = null;
    $searchFrom = $dataStartRow + 7;
    $searchTo   = min($dataStartRow + 20, count($rows) - 1);
 
    for ($i = $searchFrom; $i <= $searchTo; $i++) {
        if (isset($rows[$i][0]) &&
            stripos(trim($rows[$i][0]), 'FAMILY MEMBERS') !== false) {
            $familyDataStartRow = $i + 1;
            log_message('debug', "Family header at row $i → data from $familyDataStartRow");
            break;
        }
    }
 
    if ($familyDataStartRow !== null) {
        for ($i = $familyDataStartRow; $i < min($familyDataStartRow + 10, count($rows)); $i++) {
            $row        = $rows[$i];
            $memberName = trim($row[0] ?? '');
 
            // Stop at section boundary markers
            if (!empty($memberName) && preg_match(
                '/26\.\s*VULNERABLE|HOUSE OWNERSHIP|SHELTER DAMAGE|Signature\/Thumbmark|DATA PRIVACY/i',
                $memberName
            )) {
                break;
            }
 
            if (empty($memberName) || stripos($memberName, 'FAMILY MEMBERS') !== false) {
                continue;
            }
 
            $bdRaw    = trim($row[8] ?? '');
            $bdParsed = null;
            // Only parse valid dates
            if (!empty($bdRaw) && strpos($bdRaw, '--') === false && preg_match('/^\d{1,2}[-/]\d{1,2}[-/]\d{2,4}$/', $bdRaw)) {
                $bdParsed = $this->parseDate($bdRaw);
            }
            
            // Calculate age from birthdate if not provided or invalid
            $age = trim($row[10] ?? '');
            if (($age === '0' || empty($age) || !is_numeric($age)) && $bdParsed) {
                $birthDate = new \DateTime($bdParsed);
                $today = new \DateTime('today');
                $age = $birthDate->diff($today)->y;
            } elseif (!is_numeric($age) || $age < 0) {
                $age = null;
            }
 
            $familyMembers[] = [
                'name'       => $memberName,
                'relation'   => $this->titleCaseValue(trim($row[5]  ?? '')) ?? '',
                'birthdate'  => $bdParsed,
                'age'        => $age,
                'sex'        => $this->titleCaseValue(trim($row[11] ?? '')) ?? '',
                'education'  => trim($row[12] ?? ''),
                'occupation' => $this->titleCaseValue(trim($row[14] ?? '')) ?? '',
                'remarks'    => trim($row[15] ?? ''),
                'status'     => 'active',
            ];
            log_message('debug', "Family member: $memberName");
        }
    }
 
    // ── 5. VULNERABLE + OWNERSHIP + SHELTER ─────────────────────────────────
    $vulFrom = $dataStartRow + 14;
    $vulTo   = min($dataStartRow + 30, count($rows) - 1);
    
    // Set defaults
    $resident['vulnerable_older_persons'] = 0;
    $resident['vulnerable_pregnant'] = 0;
    $resident['vulnerable_lactating'] = 0;
    $resident['vulnerable_pwd'] = 0;
    $resident['ownership_status'] = '';
    $resident['shelter_damage'] = 'No Damage';
 
    for ($i = $vulFrom; $i <= $vulTo; $i++) {
        if (!isset($rows[$i][0])) continue;
        $cellVal = trim($rows[$i][0] ?? '');
        if (stripos($cellVal, 'VULNERABLE MEMBERS') !== false ||
            stripos($cellVal, '26.') !== false) {
 
            if (preg_match('/Older Persons:\s*(\d+)/i', $cellVal, $m)) {
                $resident['vulnerable_older_persons'] = (int)$m[1];
            }
            if (preg_match('/Pregnant:\s*(\d+)/i', $cellVal, $m)) {
                $resident['vulnerable_pregnant'] = (int)$m[1];
            }
            if (preg_match('/Lactating:\s*(\d+)/i', $cellVal, $m)) {
                $resident['vulnerable_lactating'] = (int)$m[1];
            }
            if (preg_match('/PWD:\s*(\d+)/i', $cellVal, $m)) {
                $resident['vulnerable_pwd'] = (int)$m[1];
            }
 
            // Get ownership and shelter from the right side (column 8)
            $ownerText = isset($rows[$i][8]) ? trim($rows[$i][8]) : '';
            if (preg_match('/☑\s*Owner/u', $ownerText)) {
                $resident['ownership_status'] = 'Owner';
            } elseif (preg_match('/☑\s*Renter/u', $ownerText)) {
                $resident['ownership_status'] = 'Renter';
            } elseif (preg_match('/☑\s*Sharer/u', $ownerText)) {
                $resident['ownership_status'] = 'Sharer';
            }
 
            if (preg_match('/☑\s*Partially Damaged/u', $ownerText)) {
                $resident['shelter_damage'] = 'Partially Damaged';
            } elseif (preg_match('/☑\s*Totally Damaged/u', $ownerText)) {
                $resident['shelter_damage'] = 'Totally Damaged';
            }
            break;
        }
    }
 
    // ── 6. SIGNATURES ROW ────────────────────────────────────────────────────
    $sigFrom = $dataStartRow + 15;
    $sigTo   = min($dataStartRow + 30, count($rows) - 1);
    
    $resident['registration_date'] = date('Y-m-d');
    $resident['barangay_captain_name'] = '';
    $resident['lswdo_name'] = '';
 
    for ($i = $sigFrom; $i <= $sigTo; $i++) {
        if (!isset($rows[$i][0])) continue;
        $cellVal = trim($rows[$i][0] ?? '');
        if (stripos($cellVal, 'Signature/Thumbmark') !== false ||
            stripos($cellVal, 'Date Registered') !== false) {
 
            // col 5 = "Date Registered\n03-24-2026"
            if (isset($rows[$i][5])) {
                $dateRaw = trim($rows[$i][5]);
                $dateRaw = preg_replace('/^Date Registered\s*/i', '', $dateRaw);
                $dateRaw = trim(explode("\n", $dateRaw)[0]);
                if (!empty($dateRaw) && ($pd = $this->parseDate($dateRaw))) {
                    $resident['registration_date'] = $pd;
                }
            }
 
            // col 8 = "Name/Signature of Brgy. Captain"
            if (isset($rows[$i][8])) {
                $captainRaw = trim($rows[$i][8]);
                $captainRaw = preg_replace('/^Name\/Signature of Brgy\. Captain\s*/i', '', $captainRaw);
                $captainRaw = trim(ltrim($captainRaw, "\n"));
                if (!empty($captainRaw)) {
                    $resident['barangay_captain_name'] = $captainRaw;
                }
            }
 
            // col 12 = "Name/Signature of LSWDO"
            if (isset($rows[$i][12])) {
                $lswdoRaw = trim($rows[$i][12]);
                $lswdoRaw = preg_replace('/^Name\/Signature of LSWDO\s*/i', '', $lswdoRaw);
                $lswdoRaw = trim(ltrim($lswdoRaw, "\n"));
                if (!empty($lswdoRaw)) {
                    $resident['lswdo_name'] = $lswdoRaw;
                }
            }
            break;
        }
    }
 
    // ── 7. DEFAULTS for all missing fields ──────────────────────────────────────────
    $defaults = [
        'vulnerable_older_persons' => 0,
        'vulnerable_pregnant' => 0,
        'vulnerable_lactating' => 0,
        'vulnerable_pwd' => 0,
        'monthly_income' => 0,
        'data_privacy_consent' => 1,
        'is_4ps_beneficiary' => 0,
        'civil_status' => '',
        'sex' => '',
        'occupation' => '',
        'contact_number' => '',
        'alternate_number' => '',
        'religion' => '',
        'birthplace' => '',
        'id_card_presented' => '',
        'id_card_number' => '',
        'house_no' => '',
        'street' => '',
        'subdivision' => '',
        'permanent_barangay' => '',
        'permanent_city' => '',
        'permanent_province' => '',
        'zip_code' => '',
        'ip_ethnicity' => '',
        'ownership_status' => '',
        'shelter_damage' => 'No Damage',
        'middle_name' => '',
        'name_extension' => '',
        'mother_maiden_name' => '',
        'photo' => null,
        'signature_thumbmark' => null,
        'right_thumbmark' => null,
        'barangay_captain_signature' => null,
        'lswdo_signature' => null,
        'data_privacy_date' => date('Y-m-d')
    ];
 
    foreach ($defaults as $field => $default) {
        if (!isset($resident[$field]) || $resident[$field] === null || $resident[$field] === '') {
            $resident[$field] = $default;
        }
    }
 
    $resident['family_members'] = $familyMembers;
    
    // Calculate age from birthdate if age is missing or zero
    if ((empty($resident['age']) || $resident['age'] == '0') && !empty($resident['birthdate'])) {
        $birthDate = new \DateTime($resident['birthdate']);
        $today = new \DateTime('today');
        $resident['age'] = $birthDate->diff($today)->y;
    }
    
    // If still no age, set to null
    if (empty($resident['age']) || $resident['age'] == '0') {
        $resident['age'] = null;
    }
 
    log_message('debug',
        "Parsed: " . ($resident['first_name'] ?? '?') . ' ' . ($resident['last_name'] ?? '?') .
        " | HH#: " . ($resident['household_no'] ?? 'none') .
        " | Members: " . count($familyMembers)
    );
 
    return $resident;
}

private function parsePermanentAddress(string $address): array
{
    $fields = [
        'house_no'           => '',
        'street'             => '',
        'subdivision'        => '',
        'permanent_barangay' => '',
        'permanent_city'     => '',
        'permanent_province' => '',
        'zip_code'           => '',
    ];
 
    $parts = array_values(array_filter(preg_split('/\s+/', trim($address))));
 
    if (empty($parts)) {
        return $fields;
    }
 
    // Pull from the right (stable positions)
    if (count($parts) >= 1 && preg_match('/^\d{4}$/', end($parts))) {
        $fields['zip_code'] = array_pop($parts);
    }
    if (!empty($parts)) {
        $fields['permanent_province'] = ucwords(strtolower(array_pop($parts)));
    }
    if (!empty($parts)) {
        $fields['permanent_city'] = ucwords(strtolower(array_pop($parts)));
    }
    if (!empty($parts)) {
        $fields['permanent_barangay'] = ucwords(strtolower(array_pop($parts)));
    }
 
    // Pull house_no from the left if it starts with a digit
    if (!empty($parts) && preg_match('/^\d/', $parts[0])) {
        $fields['house_no'] = array_shift($parts);
    }
 
    // Whatever remains in the middle is: street [...words...] subdivision
    // The LAST token is always subdivision (1 word).
    // Everything before it is the street name (1 or more words).
    if (count($parts) >= 2) {
        // Last token = subdivision
        $fields['subdivision'] = ucwords(strtolower(array_pop($parts)));
        // Remaining tokens = street
        $fields['street'] = ucwords(strtolower(implode(' ', $parts)));
    } elseif (count($parts) === 1) {
        // Only one token left — treat it as street (no subdivision)
        $fields['street'] = ucwords(strtolower($parts[0]));
    }
 
    return $fields;
}

private function titleCaseValue(?string $value): ?string
{
    if ($value === null || $value === '') {
        return $value;
    }
    return ucwords(strtolower($value));
}

private function extractLabelValue($cellValue): ?string
{
    $value = trim((string)$cellValue);
    if ($value === '') {
        return null;
    }
    if (strpos($value, "\n") !== false) {
        $actual = trim(explode("\n", $value, 2)[1]);
        return $actual !== '' ? $actual : null;
    }
    // No newline = label only, field was empty on export
    return null;
}


private function extractValueFromMergedCell($cellValue)
{
    if (empty($cellValue)) {
        return '';
    }
    
    $value = trim((string)$cellValue);
    
    // If there's a newline, take the part after it
    if (strpos($value, "\n") !== false) {
        $parts = explode("\n", $value, 2);
        $result = trim(end($parts));
        // Remove numeric prefixes like "7. " from the extracted value
        $result = preg_replace('/^\d+\.\s*/', '', $result);
        return $result;
    }
    
    // Remove numeric prefixes
    $value = preg_replace('/^\d+\.\s*/', '', $value);
    return $value;
}


private function extractFromLocationRow($row, $columnIndex)
{
    if (isset($row[$columnIndex])) {
        $value = trim($row[$columnIndex]);
        // Remove the label part if present (e.g., "1. REGION\nCAMIGUIN" -> "CAMIGUIN")
        if (strpos($value, "\n") !== false) {
            $parts = explode("\n", $value);
            return trim(end($parts));
        }
        return $value;
    }
    return null;
}

/**
 * Extract value from a head of family row at a specific column
 */
private function extractFromHeadRow($row, $columnIndex)
{
    if (isset($row[$columnIndex])) {
        $value = trim($row[$columnIndex]);
        // Remove the label part if present (e.g., "7. LAST NAME\nJURADO" -> "JURADO")
        if (strpos($value, "\n") !== false) {
            $parts = explode("\n", $value);
            return trim(end($parts));
        }
        return $value;
    }
    return null;
}

private function extractNumericFromHeadRow($row, $columnIndex): float
{
    $value = $this->extractFromHeadRow($row, $columnIndex);
    if (is_numeric($value)) {
        return (float)$value;
    }
    if ($value !== null && preg_match('/(\d+(?:\.\d+)?)/', $value, $m)) {
        return (float)$m[1];
    }
    return 0.0;
}

/**
 * Process CSV file import
 */
private function processCsvImport($filepath)
{
    if (($handle = fopen($filepath, 'r')) !== false) {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $importedCount = 0;
        $errors = [];
        $rowNum = 0;
        
        while (($data = fgetcsv($handle)) !== false) {
            $rowNum++;
            
            // Skip header row
            if ($rowNum == 1) {
                continue;
            }
            
            // Skip empty rows
            if (empty($data[0]) && empty($data[1]) && empty($data[2])) {
                continue;
            }
            
            try {
                $residentData = [
                    'household_no' => $data[0] ?? null,
                    'last_name' => $data[1] ?? null,
                    'first_name' => $data[2] ?? null,
                    'middle_name' => $data[3] ?? null,
                    'name_extension' => $data[4] ?? null,
                    'birthdate' => $data[5] ?? null,
                    'age' => $data[6] ?? null,
                    'sex' => $data[7] ?? null,
                    'civil_status' => $data[8] ?? null,
                    'contact_number' => $data[9] ?? null,
                    'barangay' => $data[10] ?? null,
                    'city_municipality' => $data[11] ?? null,
                    'province' => $data[12] ?? null,
                    'region' => $data[13] ?? null,
                    'district' => $data[14] ?? null,
                    'evacuation_center' => $data[15] ?? null,
                    'occupation' => $data[16] ?? null,
                    'monthly_income' => $data[17] ?? 0,
                    'ownership_status' => $data[18] ?? null,
                    'shelter_damage' => $data[19] ?? null
                ];
                
                if (empty($residentData['first_name']) || empty($residentData['last_name'])) {
                    $errors[] = "Row {$rowNum}: Missing required fields (first name or last name)";
                    continue;
                }
                
                // Generate QR tokens
                $residentData['qr_code_token'] = bin2hex(random_bytes(16));
                $residentData['household_qr_token'] = bin2hex(random_bytes(16));
                $residentData['status'] = 'active';
                $residentData['registration_date'] = date('Y-m-d');
                
                if (empty($residentData['household_no'])) {
                    $residentData['household_no'] = $this->residentModel->generateHouseholdNo();
                }
                
                if ($this->residentModel->insert($residentData)) {
                    $importedCount++;
                } else {
                    $errors[] = "Row {$rowNum}: " . implode(', ', $this->residentModel->errors());
                }
                
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }
        
        fclose($handle);
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            throw new \Exception('Database transaction failed.');
        }
        
        if (!empty($errors)) {
            session()->setFlashdata('import_errors', $errors);
        }
        
        return $importedCount;
    }
    
    throw new \Exception('Could not open the file.');
}

/**
 * Parse Excel row based on FACED export format
 */
private function parseExcelRow($row)
{
    // Map column indices to fields (based on FACED export structure)
    return [
        // Location fields
        'region' => $this->extractValueFromRow($row, 1),
        'province' => $this->extractValueFromRow($row, 2),
        'district' => $this->extractValueFromRow($row, 3),
        'city_municipality' => $this->extractValueFromRow($row, 4),
        'barangay' => $this->extractValueFromRow($row, 5),
        'evacuation_center' => $this->extractValueFromRow($row, 6),
        
        // Head of Family fields
        'last_name' => $this->extractValueFromRow($row, 7),
        'first_name' => $this->extractValueFromRow($row, 8),
        'middle_name' => $this->extractValueFromRow($row, 9),
        'name_extension' => $this->extractValueFromRow($row, 10),
        'birthdate' => $this->parseDate($this->extractValueFromRow($row, 11)),
        'age' => $this->extractValueFromRow($row, 12),
        'birthplace' => $this->extractValueFromRow($row, 13),
        'sex' => $this->extractValueFromRow($row, 14),
        'civil_status' => $this->extractValueFromRow($row, 15),
        'mother_maiden_name' => $this->extractValueFromRow($row, 16),
        'religion' => $this->extractValueFromRow($row, 17),
        'occupation' => $this->extractValueFromRow($row, 18),
        'monthly_income' => $this->extractNumericValue($row, 19),
        'id_card_presented' => $this->extractValueFromRow($row, 20),
        'id_card_number' => $this->extractValueFromRow($row, 21),
        'contact_number' => $this->extractValueFromRow($row, 22),
        'alternate_number' => $this->extractValueFromRow($row, 23),
        
        // Permanent Address
        'house_no' => $this->extractValueFromRow($row, 24),
        'street' => $this->extractValueFromRow($row, 25),
        'subdivision' => $this->extractValueFromRow($row, 26),
        'permanent_barangay' => $this->extractValueFromRow($row, 27),
        'permanent_city' => $this->extractValueFromRow($row, 28),
        'permanent_province' => $this->extractValueFromRow($row, 29),
        'zip_code' => $this->extractValueFromRow($row, 30),
        
        // Additional Info
        'is_4ps_beneficiary' => $this->extractBooleanValue($row, 31),
        'ip_ethnicity' => $this->extractValueFromRow($row, 32),
        
        // Vulnerable counts
        'vulnerable_older_persons' => $this->extractNumericValue($row, 33),
        'vulnerable_pregnant' => $this->extractNumericValue($row, 34),
        'vulnerable_lactating' => $this->extractNumericValue($row, 35),
        'vulnerable_pwd' => $this->extractNumericValue($row, 36),
        
        // Ownership & Shelter
        'ownership_status' => $this->extractValueFromRow($row, 37),
        'shelter_damage' => $this->extractValueFromRow($row, 38),
        
        // Household number (from serial number)
        'household_no' => $this->extractValueFromRow($row, 39)
    ];
}

/**
 * Helper to extract value from row with index check
 */
private function extractValueFromRow($row, $index)
{
    // Adjust for 0-based array
    $idx = $index - 1;
    return isset($row[$idx]) && !empty($row[$idx]) ? trim($row[$idx]) : null;
}

/**
 * Helper to extract numeric value
 */
private function extractNumericValue($row, $index)
{
    $value = $this->extractValueFromRow($row, $index);
    return is_numeric($value) ? (int)$value : 0;
}

/**
 * Helper to extract boolean value (☑ or ☐)
 */
private function extractBooleanValue($row, $index)
{
    $value = $this->extractValueFromRow($row, $index);
    return ($value === '☑' || $value === '✓' || $value === 'true' || $value === '1') ? 1 : 0;
}

/**
 * Parse date from various formats
 */
private function parseDate($dateString)
{
    if (empty($dateString)) {
        return null;
    }
    
    try {
        // Try to parse common date formats
        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'm-d-Y', 'd-m-Y'];
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date && $date->format($format) === $dateString) {
                return $date->format('Y-m-d');
            }
        }
        
        // Try strtotime as fallback
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return null;
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Delete multiple residents at once
 * AJAX endpoint for bulk delete
 */
public function deleteAll()
{
    // Check if this is an AJAX request
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    // Get JSON data
    $json = $this->request->getJSON(true);
    $ids = $json['ids'] ?? [];
    
    if (empty($ids) || !is_array($ids)) {
        return $this->response->setJSON(['success' => false, 'message' => 'No resident IDs provided']);
    }
    
    // Sanitize IDs (ensure they are integers)
    $ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);
    
    if (empty($ids)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid resident IDs']);
    }
    
    $db = \Config\Database::connect();
    $db->transStart();
    
    $deletedCount = 0;
    $errors = [];
    
    foreach ($ids as $id) {
        // Check if resident exists
        $resident = $this->residentModel->find($id);
        if (empty($resident)) {
            $errors[] = "Resident ID {$id} not found";
            continue;
        }
        
        // Delete associated files
        $fileFields = ['signature_thumbmark', 'right_thumbmark', 'photo', 'id_picture_front', 'id_picture_back'];
        foreach ($fileFields as $field) {
            if (!empty($resident[$field]) && file_exists(FCPATH . $resident[$field])) {
                @unlink(FCPATH . $resident[$field]);
            }
        }
        
        // Get family members to delete their files too
        $familyMembers = $this->familyMemberModel->getByResidentId($id);
        foreach ($familyMembers as $member) {
            if (!empty($member['photo']) && file_exists(FCPATH . $member['photo'])) {
                @unlink(FCPATH . $member['photo']);
            }
            if (!empty($member['id_photo_front']) && file_exists(FCPATH . $member['id_photo_front'])) {
                @unlink(FCPATH . $member['id_photo_front']);
            }
            if (!empty($member['id_photo_back']) && file_exists(FCPATH . $member['id_photo_back'])) {
                @unlink(FCPATH . $member['id_photo_back']);
            }
            if (!empty($member['birth_certificate']) && file_exists(FCPATH . $member['birth_certificate'])) {
                @unlink(FCPATH . $member['birth_certificate']);
            }
        }
        
        // Delete family members first (foreign key constraint)
        $this->familyMemberModel->deleteByResidentId($id);
        
        // Delete the resident
        if ($this->residentModel->delete($id)) {
            $deletedCount++;
        } else {
            $errors[] = "Failed to delete resident ID {$id}";
        }
    }
    
    $db->transComplete();
    
    if ($db->transStatus() === false) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Database transaction failed',
            'errors' => $errors
        ]);
    }
    
    return $this->response->setJSON([
        'success' => true,
        'message' => "Successfully deleted {$deletedCount} resident(s)" . (empty($errors) ? '' : ' with some errors'),
        'deleted_count' => $deletedCount,
        'errors' => $errors
    ]);
}

/**
 * Get ALL resident IDs for bulk delete (AJAX endpoint)
 */
public function getAllIds()
{
    // Check if this is an AJAX request
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Build query based on user role
    $query = $this->residentModel->select('id');
    
    // Filter by barangay if user is barangay role
    if ($userRole == 'barangay' && $userBarangay) {
        $query = $query->where('barangay', $userBarangay);
    }
    
    // Get ALL IDs (no pagination, no filters)
    $residents = $query->findAll();
    
    $ids = array_column($residents, 'id');
    
    return $this->response->setJSON([
        'success' => true,
        'ids' => $ids,
        'total' => count($ids)
    ]);
}

private function processDrawnSignature($base64Data, $firstName = 'unknown', $lastName = 'unknown', $middleName = '')
{
    if (empty($base64Data)) {
        return null;
    }
    
    // Debug logging
    log_message('debug', 'Processing drawn signature - data length: ' . strlen($base64Data));
    log_message('debug', 'Base64 data preview: ' . substr($base64Data, 0, 100));
    
    // Remove the data:image/png;base64, prefix if present
    $imageData = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $base64Data);
    $imageData = str_replace(' ', '+', $imageData);
    $imageBinary = base64_decode($imageData);
    
    if ($imageBinary === false) {
        log_message('error', 'Failed to decode base64 signature data');
        return null;
    }
    
    // Create upload directory if it doesn't exist
    $uploadPath = FCPATH . 'uploads/signatures/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    
    // Generate filename using family head's name
    $sanitizedName = $this->sanitizeFilename($firstName . '_' . $lastName);
    if (!empty($middleName)) {
        $sanitizedName = $this->sanitizeFilename($firstName . '_' . $middleName . '_' . $lastName);
    }
    
    $newName = $sanitizedName . '_SIGNATURE_DRAWN_' . date('Y-m-d_H-i-s') . '.png';
    $filepath = $uploadPath . $newName;
    
    // Save the image
    if (file_put_contents($filepath, $imageBinary)) {
        $savedPath = 'uploads/signatures/' . $newName;
        log_message('debug', 'Drawn signature saved successfully: ' . $savedPath);
        return $savedPath;
    } else {
        log_message('error', 'Failed to save drawn signature to: ' . $filepath);
        return null;
    }
}

/**
 * AJAX endpoint to check for duplicate resident names
 */
public function checkDuplicateName()
{
    // Enable CORS and debug logging
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    log_message('debug', 'checkDuplicateName method called');
    
    // Check if this is an AJAX request (make it work for both AJAX and regular GET)
    $firstName = trim($this->request->getGet('first_name'));
    $lastName = trim($this->request->getGet('last_name'));
    $excludeId = $this->request->getGet('exclude_id');
    
    log_message('debug', "Checking duplicate for: {$firstName} {$lastName}, exclude_id: {$excludeId}");
    
    // Validate input
    if (empty($firstName) || empty($lastName)) {
        return $this->response->setJSON([
            'success' => true,
            'is_duplicate' => false,
            'message' => ''
        ]);
    }
    
    // Direct database query to check for duplicate
    $db = \Config\Database::connect();
    $builder = $db->table('residents')
        ->where('first_name', $firstName)
        ->where('last_name', $lastName);
    
    if ($excludeId && is_numeric($excludeId)) {
        $builder->where('id !=', $excludeId);
    }
    
    $count = $builder->countAllResults();
    $isDuplicate = $count > 0;
    
    log_message('debug', "Found {$count} matching records, is_duplicate: " . ($isDuplicate ? 'YES' : 'NO'));
    
    $message = '';
    if ($isDuplicate) {
        // Get the existing resident's details
        $existing = $db->table('residents')
            ->select('household_no, barangay')
            ->where('first_name', $firstName)
            ->where('last_name', $lastName);
        
        if ($excludeId && is_numeric($excludeId)) {
            $existing->where('id !=', $excludeId);
        }
        
        $existingResident = $existing->get()->getRowArray();
        
        if ($existingResident) {
            $message = "⚠️ RESIDENT ALREADY EXISTS! {$firstName} {$lastName} is already registered (Household #: {$existingResident['household_no']}, Barangay: {$existingResident['barangay']})";
        } else {
            $message = "⚠️ {$firstName} {$lastName} is already registered!";
        }
    }
    
    return $this->response->setJSON([
        'success' => true,
        'is_duplicate' => $isDuplicate,
        'message' => $message,
        'count' => $count
    ]);
}
private function sanitizeFilename($filename)
{
    // Convert to uppercase for consistency
    $filename = strtoupper(trim($filename));
    
    // Remove any path separators
    $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $filename);
    
    // Replace spaces and special characters with underscore
    $filename = preg_replace('/[^A-Za-z0-9\-_]/', '_', $filename);
    
    // Replace multiple underscores with single underscore
    $filename = preg_replace('/_+/', '_', $filename);
    
    // Remove trailing/leading underscores
    $filename = trim($filename, '_');
    
    // Limit length to 100 characters
    if (strlen($filename) > 100) {
        $filename = substr($filename, 0, 100);
    }
    
    return $filename;
}

/**
 * Download all images as ZIP file
 * Route: GET /beneficiaries/download-images
 */
public function downloadImages()
{
    // Create a temporary directory
    $tempDir = WRITEPATH . 'temp/images_' . time() . '/';
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
    }
    
    $zip = new \ZipArchive();
    $zipFileName = 'all_images_' . date('Y-m-d_H-i-s') . '.zip';
    $zipPath = WRITEPATH . 'temp/' . $zipFileName;
    
    // Define folders to include
    $folders = [
        'head_photos' => FCPATH . 'uploads/head_photos/',
        'family_photos' => FCPATH . 'uploads/family_photos/',
        'signatures' => FCPATH . 'uploads/signatures/',
        'thumbmarks' => FCPATH . 'uploads/thumbmarks/',
        'id_pictures' => FCPATH . 'uploads/id_pictures/',
        'family_documents' => FCPATH . 'uploads/family_documents/'
    ];
    
    $fileCount = 0;
    
    // First check if there are any files at all
    foreach ($folders as $folderName => $folderPath) {
        if (is_dir($folderPath)) {
            $files = glob($folderPath . '*');
            $fileCount += count($files);
        }
    }
    
    // If no files found, show error and redirect
    if ($fileCount === 0) {
        // Clean up temp directory
        if (is_dir($tempDir)) {
            $this->deleteDirectory($tempDir);
        }
        session()->setFlashdata('error', 'No images found to download');
        return redirect()->back();
    }
    
    // Now create the ZIP file since we have files
    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
        session()->setFlashdata('error', 'Cannot create ZIP file');
        return redirect()->back();
    }
    
    // Add files to ZIP
    foreach ($folders as $folderName => $folderPath) {
        if (is_dir($folderPath)) {
            $files = glob($folderPath . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    // Add file to ZIP with subfolder structure
                    $zip->addFile($file, $folderName . '/' . basename($file));
                }
            }
        } else {
            // Create empty directory in ZIP if folder doesn't exist
            $zip->addEmptyDir($folderName);
        }
    }
    
    $zip->close();
    
    // Verify ZIP file was created successfully
    if (!file_exists($zipPath) || filesize($zipPath) === 0) {
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }
        if (is_dir($tempDir)) {
            $this->deleteDirectory($tempDir);
        }
        session()->setFlashdata('error', 'Failed to create ZIP file');
        return redirect()->back();
    }
    
    // Set headers for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipPath));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: public');
    
    // Output the ZIP file
    readfile($zipPath);
    
    // Clean up
    if (file_exists($zipPath)) {
        unlink($zipPath);
    }
    if (is_dir($tempDir)) {
        $this->deleteDirectory($tempDir);
    }
    
    exit();
}

/**
 * Helper function to delete directory recursively
 */
private function deleteDirectory($dir)
{
    if (!is_dir($dir)) {
        return;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $this->deleteDirectory($path);
        } else {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
    if (is_dir($dir)) {
        rmdir($dir);
    }
}

/**
 * Check and display duplicate residents
 * Route: GET /beneficiaries/check-duplicates
 */
public function checkDuplicates()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Get all duplicates
    $allDuplicates = $this->residentModel->findDuplicates();
    
    // Filter by barangay if user is barangay role
    if ($userRole == 'barangay') {
        $filteredDuplicates = [];
        foreach ($allDuplicates as $duplicate) {
            $filteredRecords = [];
            foreach ($duplicate['records'] as $record) {
                if ($record['barangay'] == $userBarangay) {
                    $filteredRecords[] = $record;
                }
            }
            if (!empty($filteredRecords) && count($filteredRecords) > 1) {
                $duplicate['records'] = $filteredRecords;
                $duplicate['count'] = count($filteredRecords);
                $filteredDuplicates[] = $duplicate;
            }
        }
        $duplicates = $filteredDuplicates;
    } else {
        $duplicates = $allDuplicates;
    }
    
    $data['duplicates'] = $duplicates;
    $data['hasDuplicates'] = !empty($duplicates);
    $data['totalDuplicateGroups'] = count($duplicates);
    $data['totalDuplicateRecords'] = array_sum(array_column($duplicates, 'count'));
    
    return view('beneficiaries/duplicates_check', $data);
}

/**
 * Keep one record, delete all other duplicates with same name
 * AJAX endpoint
 */
public function keepRecord()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $json = $this->request->getJSON(true);
    $keepId = $json['keep_id'] ?? 0;
    
    if (!$keepId) {
        return $this->response->setJSON(['success' => false, 'message' => 'No record ID provided']);
    }
    
    // Get the record to keep
    $keepRecord = $this->residentModel->find($keepId);
    if (!$keepRecord) {
        return $this->response->setJSON(['success' => false, 'message' => 'Record not found']);
    }
    
    // Find all duplicates with same name
    $duplicates = $this->residentModel
        ->where('first_name', $keepRecord['first_name'])
        ->where('last_name', $keepRecord['last_name'])
        ->where('id !=', $keepId)
        ->findAll();
    
    $deletedCount = 0;
    $errors = [];
    
    foreach ($duplicates as $dup) {
        // Delete associated files
        $fileFields = ['signature_thumbmark', 'right_thumbmark', 'photo', 'id_picture_front', 'id_picture_back'];
        foreach ($fileFields as $field) {
            if (!empty($dup[$field]) && file_exists(FCPATH . $dup[$field])) {
                @unlink(FCPATH . $dup[$field]);
            }
        }
        
        // Delete family members
        $this->familyMemberModel->deleteByResidentId($dup['id']);
        
        // Delete the duplicate record
        if ($this->residentModel->delete($dup['id'])) {
            $deletedCount++;
        } else {
            $errors[] = "Failed to delete record ID: {$dup['id']}";
        }
    }
    
    return $this->response->setJSON([
        'success' => true,
        'message' => "Kept record #{$keepId} and deleted {$deletedCount} duplicate(s)",
        'deleted_count' => $deletedCount,
        'errors' => $errors
    ]);
}

/**
 * Delete a single record (AJAX)
 */
public function deleteRecord()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $json = $this->request->getJSON(true);
    $id = $json['id'] ?? 0;
    
    if (!$id) {
        return $this->response->setJSON(['success' => false, 'message' => 'No record ID provided']);
    }
    
    $resident = $this->residentModel->find($id);
    if (!$resident) {
        return $this->response->setJSON(['success' => false, 'message' => 'Record not found']);
    }
    
    // Delete associated files
    $fileFields = ['signature_thumbmark', 'right_thumbmark', 'photo', 'id_picture_front', 'id_picture_back'];
    foreach ($fileFields as $field) {
        if (!empty($resident[$field]) && file_exists(FCPATH . $resident[$field])) {
            @unlink(FCPATH . $resident[$field]);
        }
    }
    
    // Delete family members
    $this->familyMemberModel->deleteByResidentId($id);
    
    // Delete the record
    if ($this->residentModel->delete($id)) {
        return $this->response->setJSON(['success' => true, 'message' => 'Record deleted successfully']);
    }
    
    return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete record']);
}

/**
 * Delete all duplicate records (keep one per name)
 */
public function deleteAllDuplicates()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $duplicates = $this->residentModel->findDuplicates();
    $totalDeleted = 0;
    $errors = [];
    
    foreach ($duplicates as $dup) {
        $records = $dup['records'];
        if (count($records) <= 1) continue;
        
        // Keep the first record, delete the rest
        $keepId = $records[0]['id'];
        
        for ($i = 1; $i < count($records); $i++) {
            $record = $records[$i];
            
            // Delete associated files
            $fileFields = ['signature_thumbmark', 'right_thumbmark', 'photo', 'id_picture_front', 'id_picture_back'];
            foreach ($fileFields as $field) {
                if (!empty($record[$field]) && file_exists(FCPATH . $record[$field])) {
                    @unlink(FCPATH . $record[$field]);
                }
            }
            
            // Delete family members
            $this->familyMemberModel->deleteByResidentId($record['id']);
            
            // Delete the record
            if ($this->residentModel->delete($record['id'])) {
                $totalDeleted++;
            } else {
                $errors[] = "Failed to delete record ID: {$record['id']}";
            }
        }
    }
    
    return $this->response->setJSON([
        'success' => true,
        'message' => "Deleted {$totalDeleted} duplicate records. Kept 1 record per name.",
        'deleted_count' => $totalDeleted,
        'errors' => $errors
    ]);
}

public function imageReport()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Get filter parameters - ADDED status filter
    $barangay = $this->request->getGet('barangay');
    $street = $this->request->getGet('street');
    $statusFilter = $this->request->getGet('status'); // NEW: status filter
    
    // Build the query
    $query = $this->residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $query = $query->where('residents.barangay', $userBarangay);
        $barangay = $userBarangay;
    }
    
    // Apply barangay filter
    if ($barangay && !empty($barangay)) {
        $query = $query->where('residents.barangay', $barangay);
    }
    
    // Apply street filter
    if ($street && !empty($street)) {
        $query = $query->where('residents.street', $street);
    }
    
    // Sort by last_name, then first_name A-Z
    $query->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC');
    
    // Get ALL residents (no pagination)
    $residents = $query->findAll();
    
    // Initialize counters
    $stats = [
        'total_family_heads' => 0,
        'total_individuals' => 0,
        'heads_with_photo' => 0,
        'heads_without_photo' => 0,
        'total_family_members' => 0,
        'members_with_photo' => 0,
        'members_without_photo' => 0,
        'households_fully_updated' => 0,
        'households_partially_updated' => 0,
        'households_not_updated' => 0,
        'households_with_head_only' => 0,
        'households_with_members_only' => 0,
        // Signature/Thumbmark statistics
        'heads_with_signature' => 0,
        'heads_without_signature' => 0,
        'heads_with_thumbmark' => 0,
        'heads_without_thumbmark' => 0,
    ];
    
    $householdDetails = [];
    
    foreach ($residents as $resident) {
        $stats['total_family_heads']++;
        
        // Format name as "Lastname, Firstname"
        $formattedName = $resident['last_name'] . ', ' . $resident['first_name'];
        if (!empty($resident['middle_name'])) {
            $formattedName .= ' ' . substr($resident['middle_name'], 0, 1) . '.';
        }
        
        // Check head photo
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        if ($headHasPhoto) {
            $stats['heads_with_photo']++;
        } else {
            $stats['heads_without_photo']++;
        }
        
        // Check signature/thumbmark
        $headHasSignature = !empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark']);
        if ($headHasSignature) {
            $stats['heads_with_signature']++;
        } else {
            $stats['heads_without_signature']++;
        }
        
        $headHasThumbmark = !empty($resident['right_thumbmark']) && file_exists(FCPATH . $resident['right_thumbmark']);
        if ($headHasThumbmark) {
            $stats['heads_with_thumbmark']++;
        } else {
            $stats['heads_without_thumbmark']++;
        }
        
        // Get family members
        $familyMembers = $this->familyMemberModel->getByResidentId($resident['id']);
        $memberCount = count($familyMembers);
        $stats['total_family_members'] += $memberCount;
        
        // Add to total individuals (head + members)
        $stats['total_individuals'] += (1 + $memberCount);
        
        $membersWithPhoto = 0;
        $membersWithoutPhoto = 0;
        
        foreach ($familyMembers as $member) {
            $memberHasPhoto = !empty($member['photo']) && file_exists(FCPATH . $member['photo']);
            if ($memberHasPhoto) {
                $membersWithPhoto++;
            } else {
                $membersWithoutPhoto++;
            }
        }
        
        $stats['members_with_photo'] += $membersWithPhoto;
        $stats['members_without_photo'] += $membersWithoutPhoto;
        
        // Determine household update status
        $allMembersHavePhotos = ($membersWithPhoto == $memberCount && $memberCount > 0);
        
        if ($headHasPhoto && ($memberCount == 0 || $allMembersHavePhotos)) {
            $stats['households_fully_updated']++;
            $updateStatus = 'fully_updated';
            $displayStatus = 'Fully Updated';
            $statusClass = 'chip-green';
        } elseif (!$headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $stats['households_not_updated']++;
            $updateStatus = 'not_updated';
            $displayStatus = 'Not Updated';
            $statusClass = 'chip-red';
        } elseif ($headHasPhoto && $membersWithPhoto > 0 && !$allMembersHavePhotos) {
            $stats['households_partially_updated']++;
            $updateStatus = 'partially_updated';
            $displayStatus = 'Partially Updated';
            $statusClass = 'chip-amber';
        } elseif ($headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $stats['households_with_head_only']++;
            $updateStatus = 'head_only';
            $displayStatus = 'Head Only';
            $statusClass = 'chip-teal';
        } elseif (!$headHasPhoto && $membersWithPhoto > 0) {
            $stats['households_with_members_only']++;
            $updateStatus = 'members_only';
            $displayStatus = 'Members Only';
            $statusClass = 'chip-orange';
        } elseif ($headHasPhoto && $memberCount == 0) {
            $stats['households_fully_updated']++;
            $updateStatus = 'fully_updated';
            $displayStatus = 'Fully Updated';
            $statusClass = 'chip-green';
        } else {
            $stats['households_not_updated']++;
            $updateStatus = 'not_updated';
            $displayStatus = 'Not Updated';
            $statusClass = 'chip-red';
        }
        
        // Store detailed info for each household
        $householdDetails[] = [
            'id' => $resident['id'],
            'household_no' => $resident['household_no'],
            'head_name' => $formattedName,
            'head_first_name' => $resident['first_name'],
            'head_last_name' => $resident['last_name'],
            'barangay' => $resident['barangay'],
            'street' => $resident['street'],
            'head_has_photo' => $headHasPhoto,
            'head_photo_path' => $resident['photo'],
            'head_has_signature' => $headHasSignature,
            'head_has_thumbmark' => $headHasThumbmark,
            'signature_path' => $resident['signature_thumbmark'],
            'thumbmark_path' => $resident['right_thumbmark'],
            'member_count' => $memberCount,
            'members_with_photo' => $membersWithPhoto,
            'members_without_photo' => $membersWithoutPhoto,
            'update_status' => $updateStatus,
            'display_status' => $displayStatus,
            'status_class' => $statusClass
        ];
    }
    
    // NEW: Apply status filter if selected
    if ($statusFilter && !empty($statusFilter)) {
        $householdDetails = array_filter($householdDetails, function($hh) use ($statusFilter) {
            return $hh['update_status'] == $statusFilter;
        });
        // Re-index array
        $householdDetails = array_values($householdDetails);
    }
    
    // Get unique barangays for filter dropdown
    $barangayQuery = $this->residentModel->select('barangay')->distinct();
    if ($userRole == 'barangay' && $userBarangay) {
        $barangayQuery->where('barangay', $userBarangay);
    }
    $barangays = array_column($barangayQuery->findAll(), 'barangay');
    sort($barangays);
    
    // Get streets by barangay for dynamic loading
    $streetsByBarangay = [];
    $streetQuery = $this->residentModel->select('barangay, street')->distinct()
        ->where('street IS NOT NULL')
        ->where('street !=', '');
    if ($userRole == 'barangay' && $userBarangay) {
        $streetQuery->where('barangay', $userBarangay);
    }
    $streetRows = $streetQuery->findAll();
    foreach ($streetRows as $row) {
        if (!empty($row['barangay']) && !empty($row['street'])) {
            if (!isset($streetsByBarangay[$row['barangay']])) {
                $streetsByBarangay[$row['barangay']] = [];
            }
            if (!in_array($row['street'], $streetsByBarangay[$row['barangay']])) {
                $streetsByBarangay[$row['barangay']][] = $row['street'];
            }
        }
    }
    
    // Get streets for current selection
    $streets = [];
    if ($barangay && isset($streetsByBarangay[$barangay])) {
        $streets = $streetsByBarangay[$barangay];
        sort($streets);
    }
    
    $data = [
        'stats' => $stats,
        'householdDetails' => $householdDetails,
        'barangays' => $barangays,
        'streets' => $streets,
        'streetsByBarangay' => $streetsByBarangay,
        'selectedBarangay' => $barangay,
        'selectedStreet' => $street,
        'selectedStatus' => $statusFilter, // NEW: pass selected status to view
        'userRole' => $userRole,
        'userBarangay' => $userBarangay,
        'totalRecords' => count($householdDetails)
    ];
    
    return view('beneficiaries/image_report', $data);
}

public function exportImageReport()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Get filter parameters - ADDED status filter
    $barangay = $this->request->getGet('barangay');
    $street = $this->request->getGet('street');
    $statusFilter = $this->request->getGet('status'); // NEW: status filter
    
    // Build the query
    $query = $this->residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $query = $query->where('residents.barangay', $userBarangay);
        $barangay = $userBarangay;
    }
    
    // Apply filters
    if ($barangay && !empty($barangay)) {
        $query = $query->where('residents.barangay', $barangay);
    }
    if ($street && !empty($street)) {
        $query = $query->where('residents.street', $street);
    }
    
    // Sort A-Z by lastname, firstname
    $query->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC');
    
    $residents = $query->findAll();
    
    // Process residents and apply status filter
    $filteredResidents = [];
    $totalHeads = 0;
    $totalMembers = 0;
    $headsWithSignature = 0;
    $headsWithThumbmark = 0;
    
    foreach ($residents as $resident) {
        // Calculate status for each resident
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        $familyMembers = $this->familyMemberModel->getByResidentId($resident['id']);
        $memberCount = count($familyMembers);
        
        $membersWithPhoto = 0;
        foreach ($familyMembers as $member) {
            if (!empty($member['photo']) && file_exists(FCPATH . $member['photo'])) {
                $membersWithPhoto++;
            }
        }
        
        $allMembersHavePhotos = ($membersWithPhoto == $memberCount && $memberCount > 0);
        
        if ($headHasPhoto && ($memberCount == 0 || $allMembersHavePhotos)) {
            $updateStatus = 'fully_updated';
        } elseif (!$headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $updateStatus = 'not_updated';
        } elseif ($headHasPhoto && $membersWithPhoto > 0 && !$allMembersHavePhotos) {
            $updateStatus = 'partially_updated';
        } elseif ($headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $updateStatus = 'head_only';
        } elseif (!$headHasPhoto && $membersWithPhoto > 0) {
            $updateStatus = 'members_only';
        } elseif ($headHasPhoto && $memberCount == 0) {
            $updateStatus = 'fully_updated';
        } else {
            $updateStatus = 'not_updated';
        }
        
        // Apply status filter
        if ($statusFilter && !empty($statusFilter) && $updateStatus != $statusFilter) {
            continue;
        }
        
        $filteredResidents[] = $resident;
        $totalHeads++;
        
        // Check signature
        if (!empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark'])) {
            $headsWithSignature++;
        }
        
        // Check thumbmark
        if (!empty($resident['right_thumbmark']) && file_exists(FCPATH . $resident['right_thumbmark'])) {
            $headsWithThumbmark++;
        }
        
        $totalMembers += $memberCount;
    }
    
    $totalIndividuals = $totalHeads + $totalMembers;
    
    // Prepare CSV data
    $csvData = [];
    
    // Add summary row
    $csvData[] = ['IMAGE REPORT SUMMARY'];
    $csvData[] = ['Generated:', date('Y-m-d H:i:s')];
    $csvData[] = ['Barangay Filter:', $barangay ?: 'All'];
    $csvData[] = ['Street Filter:', $street ?: 'All'];
    $csvData[] = ['Status Filter:', $statusFilter ? str_replace('_', ' ', ucfirst($statusFilter)) : 'All'];
    $csvData[] = [];
    $csvData[] = ['Total Individuals:', $totalIndividuals];
    $csvData[] = ['Total Family Heads:', $totalHeads];
    $csvData[] = ['Total Family Members:', $totalMembers];
    $csvData[] = [];
    $csvData[] = ['SIGNATURE/THUMBMARK SUMMARY'];
    $csvData[] = ['Heads with Signature:', $headsWithSignature];
    $csvData[] = ['Heads without Signature:', $totalHeads - $headsWithSignature];
    $csvData[] = ['Heads with Thumbmark:', $headsWithThumbmark];
    $csvData[] = ['Heads without Thumbmark:', $totalHeads - $headsWithThumbmark];
    $csvData[] = [];
    $csvData[] = ['DETAILED HOUSEHOLD LIST'];
    $csvData[] = [];
    $csvData[] = [
        'Household No',
        'Family Head (Lastname, Firstname)',
        'Barangay',
        'Street',
        'Head Has Photo',
        'Head Has Signature',
        'Head Has Thumbmark',
        'Total Members',
        'Members With Photo',
        'Members Without Photo',
        'Update Status'
    ];
    
    foreach ($filteredResidents as $resident) {
        // Format name as "Lastname, Firstname"
        $formattedName = $resident['last_name'] . ', ' . $resident['first_name'];
        if (!empty($resident['middle_name'])) {
            $formattedName .= ' ' . substr($resident['middle_name'], 0, 1) . '.';
        }
        
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        $headHasSignature = !empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark']);
        $headHasThumbmark = !empty($resident['right_thumbmark']) && file_exists(FCPATH . $resident['right_thumbmark']);
        
        $familyMembers = $this->familyMemberModel->getByResidentId($resident['id']);
        $memberCount = count($familyMembers);
        
        $membersWithPhoto = 0;
        foreach ($familyMembers as $member) {
            if (!empty($member['photo']) && file_exists(FCPATH . $member['photo'])) {
                $membersWithPhoto++;
            }
        }
        
        $membersWithoutPhoto = $memberCount - $membersWithPhoto;
        
        // Determine status display
        $allMembersHavePhotos = ($membersWithPhoto == $memberCount && $memberCount > 0);
        
        if ($headHasPhoto && ($memberCount == 0 || $allMembersHavePhotos)) {
            $status = 'Fully Updated';
        } elseif (!$headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $status = 'Not Updated';
        } elseif ($headHasPhoto && $membersWithPhoto > 0 && $membersWithPhoto < $memberCount) {
            $status = 'Partially Updated';
        } elseif ($headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $status = 'Head Only';
        } elseif (!$headHasPhoto && $membersWithPhoto > 0) {
            $status = 'Members Only';
        } else {
            $status = 'Not Updated';
        }
        
        $csvData[] = [
            $resident['household_no'],
            $formattedName,
            $resident['barangay'],
            $resident['street'],
            $headHasPhoto ? 'Yes' : 'No',
            $headHasSignature ? 'Yes' : 'No',
            $headHasThumbmark ? 'Yes' : 'No',
            $memberCount,
            $membersWithPhoto,
            $membersWithoutPhoto,
            $status
        ];
    }
    
    // Output CSV
    $filename = 'image_report_' . date('Y-m-d') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    foreach ($csvData as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

/**
 * AJAX: return distinct streets for a given barangay
 */
public function getStreets()
{
    $barangay = $this->request->getGet('barangay');
    $db = \Config\Database::connect();

    $rows = $db->table('residents')
        ->select('street')
        ->where('barangay', $barangay)
        ->where('street IS NOT NULL')
        ->where('street !=', '')
        ->groupBy('street')
        ->orderBy('street', 'ASC')
        ->get()->getResultArray();

    $streets = array_column($rows, 'street');
    return $this->response->setJSON(['streets' => $streets]);
}

/**
 * Print-friendly full resident list (no pagination, all expanded)
 */
public function printList()
{
    $barangay = $this->request->getGet('barangay');
    $street   = $this->request->getGet('street');

    $query = $this->residentModel;

    if ($barangay) $query = $query->where('barangay', $barangay);
    if ($street)   $query = $query->where('street',   $street);

    $residents = $query->orderBy('last_name', 'ASC')
                       ->orderBy('first_name', 'ASC')
                       ->findAll();

    // Attach family members to each resident
    foreach ($residents as &$r) {
        $r['family_members'] = $this->familyMemberModel->getByResidentId($r['id']);
    }
    unset($r);

    $data = [
        'residents'  => $residents,
        'barangay'   => $barangay,
        'street'     => $street,
        'totalCount' => count($residents),
    ];

    return view('beneficiaries/print_list', $data);
}

/**
 * Print image report with same layout as image_report.php
 * Route: GET /beneficiaries/print-image-report
 */
public function printImageReport()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Get filter parameters
    $barangay = $this->request->getGet('barangay');
    $street = $this->request->getGet('street');
    $statusFilter = $this->request->getGet('status');
    
    // Build the query
    $query = $this->residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $query = $query->where('residents.barangay', $userBarangay);
        $barangay = $userBarangay;
    }
    
    // Apply filters
    if ($barangay && !empty($barangay)) {
        $query = $query->where('residents.barangay', $barangay);
    }
    if ($street && !empty($street)) {
        $query = $query->where('residents.street', $street);
    }
    
    // Sort A-Z by lastname, firstname
    $query->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC');
    
    $residents = $query->findAll();
    
    // Process residents and apply status filter
    $filteredResidents = [];
    $stats = [
        'total_family_heads' => 0,
        'heads_with_photo' => 0,
        'heads_without_photo' => 0,
        'heads_with_signature' => 0,
        'heads_without_signature' => 0,
        'heads_with_thumbmark' => 0,
        'heads_without_thumbmark' => 0,
        'total_family_members' => 0,
        'members_with_photo' => 0,
        'members_without_photo' => 0,
        'total_individuals' => 0,
        'households_fully_updated' => 0,
        'households_partially_updated' => 0,
        'households_not_updated' => 0,
        'households_with_head_only' => 0,
        'households_with_members_only' => 0,
    ];
    
    foreach ($residents as $resident) {
        // Get family members
        $familyMembers = $this->familyMemberModel->getByResidentId($resident['id']);
        $memberCount = count($familyMembers);
        
        // Check head photo
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        $headHasSignature = !empty($resident['signature_thumbmark']) && file_exists(FCPATH . $resident['signature_thumbmark']);
        $headHasThumbmark = !empty($resident['right_thumbmark']) && file_exists(FCPATH . $resident['right_thumbmark']);
        
        // Check member photos
        $membersWithPhoto = 0;
        foreach ($familyMembers as $member) {
            if (!empty($member['photo']) && file_exists(FCPATH . $member['photo'])) {
                $membersWithPhoto++;
            }
        }
        $membersWithoutPhoto = $memberCount - $membersWithPhoto;
        
        $allMembersHavePhotos = ($membersWithPhoto == $memberCount && $memberCount > 0);
        
        // Determine update status
        if ($headHasPhoto && ($memberCount == 0 || $allMembersHavePhotos)) {
            $updateStatus = 'fully_updated';
            $stats['households_fully_updated']++;
        } elseif (!$headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $updateStatus = 'not_updated';
            $stats['households_not_updated']++;
        } elseif ($headHasPhoto && $membersWithPhoto > 0 && !$allMembersHavePhotos) {
            $updateStatus = 'partially_updated';
            $stats['households_partially_updated']++;
        } elseif ($headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $updateStatus = 'head_only';
            $stats['households_with_head_only']++;
        } elseif (!$headHasPhoto && $membersWithPhoto > 0) {
            $updateStatus = 'members_only';
            $stats['households_with_members_only']++;
        } elseif ($headHasPhoto && $memberCount == 0) {
            $updateStatus = 'fully_updated';
            $stats['households_fully_updated']++;
        } else {
            $updateStatus = 'not_updated';
            $stats['households_not_updated']++;
        }
        
        // Apply status filter
        if ($statusFilter && !empty($statusFilter) && $updateStatus != $statusFilter) {
            continue;
        }
        
        // Format name
        $formattedName = $resident['last_name'] . ', ' . $resident['first_name'];
        if (!empty($resident['middle_name'])) {
            $formattedName .= ' ' . substr($resident['middle_name'], 0, 1) . '.';
        }
        
        // Add to filtered list
        $resident['family_members'] = $familyMembers;
        $resident['head_has_photo'] = $headHasPhoto;
        $resident['head_has_signature'] = $headHasSignature;
        $resident['head_has_thumbmark'] = $headHasThumbmark;
        $resident['members_with_photo'] = $membersWithPhoto;
        $resident['members_without_photo'] = $membersWithoutPhoto;
        $resident['member_count'] = $memberCount;
        $resident['update_status'] = $updateStatus;
        $resident['formatted_name'] = $formattedName;
        
        $filteredResidents[] = $resident;
        
        // Update stats
        $stats['total_family_heads']++;
        $stats['total_family_members'] += $memberCount;
        $stats['total_individuals'] += (1 + $memberCount);
        
        if ($headHasPhoto) {
            $stats['heads_with_photo']++;
        } else {
            $stats['heads_without_photo']++;
        }
        
        if ($headHasSignature) {
            $stats['heads_with_signature']++;
        } else {
            $stats['heads_without_signature']++;
        }
        
        if ($headHasThumbmark) {
            $stats['heads_with_thumbmark']++;
        } else {
            $stats['heads_without_thumbmark']++;
        }
        
        $stats['members_with_photo'] += $membersWithPhoto;
        $stats['members_without_photo'] += $membersWithoutPhoto;
    }
    
    // Get unique barangays for filter display
    $barangayQuery = $this->residentModel->select('barangay')->distinct();
    if ($userRole == 'barangay' && $userBarangay) {
        $barangayQuery->where('barangay', $userBarangay);
    }
    $barangays = array_column($barangayQuery->findAll(), 'barangay');
    sort($barangays);
    
    $data = [
        'residents' => $filteredResidents,
        'stats' => $stats,
        'barangays' => $barangays,
        'selectedBarangay' => $barangay,
        'selectedStreet' => $street,
        'selectedStatus' => $statusFilter,
        'totalRecords' => count($filteredResidents),
        'userRole' => $userRole,
        'userBarangay' => $userBarangay
    ];
    
    return view('beneficiaries/print_image_report', $data);
}
}