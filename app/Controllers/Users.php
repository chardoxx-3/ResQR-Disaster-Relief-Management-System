<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        // Check if user is admin (only admins should access user management)
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $userModel = new UserModel();
        
        // Get all users from the database
        $data['users'] = $userModel->findAll();
        
        return view('users/index', $data);
    }

    /**
     * Show the create user form page
     */
    public function createPage()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        return view('users/create');
    }

public function create()
{
    // Check if user is admin
    if (session()->get('role') !== 'admin') {
        return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
    }

    $userModel = new UserModel();
    
    $rules = [
        'username'          => 'required|is_unique[users.username]',
        'email'             => 'required|valid_email|is_unique[users.email]',
        'last_name'         => 'required|string',
        'first_name'        => 'required|string',
        'middle_name'       => 'permit_empty|string',
        'suffix'            => 'permit_empty|string',
        'birthdate'         => 'required|valid_date',
        'birthplace'        => 'required|string',
        'sex'               => 'required|in_list[Male,Female,Other]',
        'house_no'          => 'permit_empty|string',
        'street'            => 'permit_empty|string',
        'subdivision'       => 'permit_empty|string',
        'barangay'          => 'permit_empty|string', // Changed to permit_empty
        'city'              => 'required|string',
        'province'          => 'required|string',
        'zip_code'          => 'required|numeric|exact_length[4]',
        'contact_number'    => 'required|numeric|min_length[11]|max_length[13]',
        'position'          => 'permit_empty|string',
        'password'          => 'required|min_length[6]',
        'role'              => 'required|in_list[admin,distributor,barangay]',
    ];

    // Make barangay required only for barangay role
    if ($this->request->getPost('role') == 'barangay') {
        $rules['barangay'] = 'required|string';
    }

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

        // Calculate age from birthdate
        $birthdate = $this->request->getPost('birthdate');
        $age = $this->calculateAge($birthdate);

        // Construct full name from components
        $fullName = trim($this->request->getPost('first_name') . ' ' . 
                         $this->request->getPost('middle_name') . ' ' . 
                         $this->request->getPost('last_name') . ' ' . 
                         $this->request->getPost('suffix'));
        // Clean up extra spaces
        $fullName = preg_replace('/\s+/', ' ', $fullName);

        // UPDATED: Include all new fields in data array
        $data = [
            'username'          => $this->request->getPost('username'),
            'email'             => $this->request->getPost('email'),
            'last_name'         => $this->request->getPost('last_name'),
            'first_name'        => $this->request->getPost('first_name'),
            'middle_name'       => $this->request->getPost('middle_name'),
            'suffix'            => $this->request->getPost('suffix'),
            'birthdate'         => $birthdate,
            'age'               => $age,
            'birthplace'        => $this->request->getPost('birthplace'),
            'sex'               => $this->request->getPost('sex'),
            'house_no'          => $this->request->getPost('house_no'),
            'street'            => $this->request->getPost('street'),
            'subdivision'       => $this->request->getPost('subdivision'),
            'barangay'          => $this->request->getPost('barangay'),
            'city'              => $this->request->getPost('city'),
            'province'          => $this->request->getPost('province'),
            'zip_code'          => $this->request->getPost('zip_code'),
            'contact_number'    => $this->request->getPost('contact_number'),
            'position'           => $this->request->getPost('position'),
            'full_name'         => $fullName,
            'password'          => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'              => $this->request->getPost('role'),
            'status'            => 'active'
        ];

        if ($userModel->save($data)) {
            return redirect()->to('/users')->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }
    }

    /**
     * Calculate age from birthdate
     */
    private function calculateAge($birthdate)
    {
        $birthDate = new \DateTime($birthdate);
        $today = new \DateTime();
        $age = $today->diff($birthDate);
        return $age->y;
    }

    public function resetPassword($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Generate new random password
        $newPassword = bin2hex(random_bytes(4));
        
        $userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Password reset successfully',
            'new_password' => $newPassword
        ]);
    }

    public function deactivate($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Don't allow deactivating yourself
        if ($id == session()->get('id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot deactivate your own account']);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
        
        $userModel->update($id, ['status' => $newStatus]);

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'User ' . $newStatus . 'd successfully',
            'new_status' => $newStatus
        ]);
    }
}