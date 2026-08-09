<?php
// app/Controllers/Event.php

namespace App\Controllers;

use App\Models\EventModel;

class Event extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

public function index()
{
    $data['events'] = $this->eventModel->orderBy('created_at', 'DESC')->findAll();
    $data['active_events'] = $this->eventModel->getActiveEvents();
    
    return view('events/index', $data);
}

    /**
     * Show create event form
     */
    public function create()
    {
        return view('events/create');
    }

public function store()
{
    $data = $this->request->getPost();
    
    // Set default values - status now only 'active' or 'closed'
    $data['status'] = 'active'; // Default to active when created
    $data['is_active'] = 0; // Not active by default (needs manual activation)
    $data['created_by'] = session()->get('id');
    
    if ($this->eventModel->insert($data)) {
        session()->setFlashdata('success', 'Event created successfully');
        return redirect()->to('/events');
    } else {
        session()->setFlashdata('errors', $this->eventModel->errors());
        return redirect()->back()->withInput();
    }
}

    /**
     * Show edit event form
     */
    public function edit($id)
    {
        $data['event'] = $this->eventModel->find($id);
        
        if (empty($data['event'])) {
            session()->setFlashdata('error', 'Event not found');
            return redirect()->to('/events');
        }
        
        return view('events/edit', $data);
    }

    /**
     * Update event
     */
    public function update($id)
    {
        $event = $this->eventModel->find($id);
        
        if (empty($event)) {
            session()->setFlashdata('error', 'Event not found');
            return redirect()->to('/events');
        }
        
        $data = $this->request->getPost();
        
        if ($this->eventModel->update($id, $data)) {
            session()->setFlashdata('success', 'Event updated successfully');
            return redirect()->to('/events');
        } else {
            session()->setFlashdata('errors', $this->eventModel->errors());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Set event as active
     */
    public function setActive($id)
    {
        if ($this->eventModel->setActiveEvent($id)) {
            session()->setFlashdata('success', 'Event activated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to activate event');
        }
        
        return redirect()->to('/events');
    }

public function close($id)
{
    if ($this->eventModel->closeEvent($id)) {
        session()->setFlashdata('success', 'Event closed successfully');
    } else {
        session()->setFlashdata('error', 'Failed to close event');
    }
    
    return redirect()->to('/events');
}

    /**
     * Delete event
     */
    public function delete($id)
    {
        $event = $this->eventModel->find($id);
        
        if (empty($event)) {
            session()->setFlashdata('error', 'Event not found');
            return redirect()->to('/events');
        }
        
        // Prevent deleting active event
        if ($event['is_active'] == 1) {
            session()->setFlashdata('error', 'Cannot delete active event. Deactivate it first.');
            return redirect()->to('/events');
        }
        
        if ($this->eventModel->delete($id)) {
            session()->setFlashdata('success', 'Event deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete event');
        }
        
        return redirect()->to('/events');
    }

    /**
     * Get event statistics
     */
    public function stats($id)
    {
        $db = \Config\Database::connect();
        
        // Get attendance count for this event
        $attendance = $db->table('attendance')
            ->where('event_id', $id)
            ->countAllResults();
        
        // Get distribution count for this event
        $distribution = $db->table('batch_distributions')
            ->where('event_id', $id)
            ->countAllResults();
        
        return $this->response->setJSON([
            'attendance' => $attendance,
            'distribution' => $distribution
        ]);
    }

    /**
 * Show detailed event statistics with attendance and distribution per resident
 * 
 * @param int $eventId The event ID
 * @return View
 */
public function statsDetail($eventId)
{
    $event = $this->eventModel->find($eventId);
    
    if (empty($event)) {
        session()->setFlashdata('error', 'Event not found');
        return redirect()->to('/events');
    }
    
    $db = \Config\Database::connect();
    
    // Get all attendance records for this event with resident and family member details
    $attendanceQuery = $db->table('attendance a')
        ->select('a.*, 
                  r.id as resident_id, 
                  r.household_no, 
                  r.first_name, 
                  r.last_name, 
                  r.barangay,
                  r.photo as resident_photo,
                  fm.name as family_member_name,
                  fm.relation,
                  fm.photo as member_photo,
                  u.username as scanned_by_name')
        ->join('residents r', 'r.id = a.resident_id', 'left')
        ->join('family_members fm', 'fm.id = a.family_member_id', 'left')
        ->join('users u', 'u.id = a.scanned_by', 'left')
        ->where('a.event_id', $eventId)
        ->orderBy('a.attendance_date DESC, a.check_in_time DESC')
        ->get();
    
    $attendances = $attendanceQuery->getResultArray();
    
    // Group attendance by household
    $households = [];
    $processedResidents = [];
    
    foreach ($attendances as $att) {
        $residentId = $att['resident_id'];
        
        if (!isset($processedResidents[$residentId])) {
            // Get all family members for this resident
            $familyMembers = $db->table('family_members')
                ->where('resident_id', $residentId)
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getResultArray();
            
            // Get resident details
            $resident = $db->table('residents')
                ->where('id', $residentId)
                ->get()
                ->getRowArray();
            
            $processedResidents[$residentId] = [
                'resident' => $resident,
                'family_members' => $familyMembers,
                'attendance' => []
            ];
        }
        
        // Add this attendance record
        $processedResidents[$residentId]['attendance'][] = $att;
    }
    
    // Get all distribution records for this event
    $distributionQuery = $db->table('batch_distributions bd')
        ->select('bd.*,
                  r.id as resident_id,
                  r.household_no,
                  r.first_name,
                  r.last_name,
                  r.barangay,
                  r.photo as resident_photo,
                  fm.name as family_member_name,
                  fm.relation,
                  fm.photo as member_photo,
                  ib.batch_number,
                  ib.unit_type,
                  i.item_name,
                  u.username as distributor_name')
        ->join('residents r', 'r.id = bd.resident_id', 'left')
        ->join('family_members fm', 'fm.id = bd.family_member_id', 'left')
        ->join('inventory_batches ib', 'ib.id = bd.batch_id', 'left')
        ->join('inventory i', 'i.id = ib.inventory_id', 'left')
        ->join('users u', 'u.id = bd.distributor_id', 'left')
        ->where('bd.event_id', $eventId)
        ->orderBy('bd.distribution_date DESC')
        ->get();
    
    $distributions = $distributionQuery->getResultArray();
    
    // Group distributions by household
    $distributionHouseholds = [];
    foreach ($distributions as $dist) {
        $residentId = $dist['resident_id'];
        if (!isset($distributionHouseholds[$residentId])) {
            $distributionHouseholds[$residentId] = [
                'resident' => $db->table('residents')->where('id', $residentId)->get()->getRowArray(),
                'distributions' => []
            ];
        }
        $distributionHouseholds[$residentId]['distributions'][] = $dist;
    }
    
    // Calculate statistics
    $stats = [
        'total_attendance' => count($attendances),
        'unique_households_attended' => count($processedResidents),
        'total_distributions' => count($distributions),
        'unique_households_received' => count($distributionHouseholds),
        'attendance_by_date' => $db->table('attendance')
            ->select('DATE(attendance_date) as date, COUNT(*) as count')
            ->where('event_id', $eventId)
            ->groupBy('DATE(attendance_date)')
            ->orderBy('date', 'DESC')
            ->get()
            ->getResultArray(),
        'items_distributed' => $db->table('batch_distributions bd')
            ->select('i.item_name, SUM(bd.quantity_distributed) as total_quantity, i.unit_type')
            ->join('inventory_batches ib', 'ib.id = bd.batch_id')
            ->join('inventory i', 'i.id = ib.inventory_id')
            ->where('bd.event_id', $eventId)
            ->groupBy('i.id, i.item_name, i.unit_type')
            ->get()
            ->getResultArray()
    ];
    
    $data = [
        'event' => $event,
        'attendances' => $attendances,
        'processedResidents' => $processedResidents,
        'distributions' => $distributions,
        'distributionHouseholds' => $distributionHouseholds,
        'stats' => $stats,
        'title' => 'Event Statistics: ' . $event['event_name']
    ];
    
    return view('events/stats_detail', $data);
}

/**
 * Return matching evacuation center suggestions for autocomplete
 */
public function suggestEvacuationCenters()
{
    $keyword = $this->request->getGet('term');
    $results = $this->eventModel->suggestEvacuationCenters($keyword);

    $suggestions = array_map(function($row) {
        return $row['evacuation_center'];
    }, $results);

    return $this->response->setJSON($suggestions);
}
}