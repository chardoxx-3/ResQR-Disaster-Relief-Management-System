<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Scanner extends BaseController
{
    public function settings()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        return view('scanner/settings');
    }
    
    public function saveCameraSettings()
    {
        if ($this->request->isAJAX()) {
            $settings = $this->request->getJSON(true);
            
            // Save to session
            foreach ($settings as $key => $value) {
                session()->set($key, $value);
            }
            
            return $this->response->setJSON(['success' => true]);
        }
        
        return $this->response->setJSON(['success' => false]);
    }
    
    public function saveHardwareSettings()
    {
        if ($this->request->isAJAX()) {
            $settings = $this->request->getJSON(true);
            
            // Save to session
            foreach ($settings as $key => $value) {
                session()->set($key, $value);
            }
            
            return $this->response->setJSON(['success' => true]);
        }
        
        return $this->response->setJSON(['success' => false]);
    }
    
    public function testConnection()
    {
        $type = $this->request->getPost('type');
        
        // Simulate connection test
        // In production, you'd actually test the connection here
        
        return $this->response->setJSON([
            'success' => true,
            'message' => ucfirst($type) . ' scanner is ready'
        ]);
    }
}