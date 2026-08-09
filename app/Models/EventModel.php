<?php
// app/Models/EventModel.php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
protected $allowedFields = [
    'event_name',
    'description',
    'start_date',
    'end_date',
    'evacuation_center',
    'status',
    'is_active',
    'created_by'
];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get the currently active event
     * Only one event should be active at a time
     */
    public function getActiveEvent()
    {
        return $this->where('is_active', 1)
                    ->where('status', 'active')
                    ->first();
    }

public function setActiveEvent($eventId)
{
    return $this->update($eventId, ['is_active' => 1, 'status' => 'active']);
}

    /**
     * Get events by barangay
     */
    public function getByBarangay($barangay, $includeAll = false)
    {
        $builder = $this->orderBy('created_at', 'DESC');
        
        if (!$includeAll && $barangay) {
            $builder->where('barangay', $barangay);
        }
        
        return $builder->findAll();
    }

public function closeEvent($eventId)
{
    return $this->update($eventId, [
        'status' => 'closed',
        'is_active' => 0
    ]);
}

/**
 * Get distinct evacuation center suggestions matching a keyword,
 * pulled from the residents table.
 */
public function suggestEvacuationCenters($keyword)
{
    $db = \Config\Database::connect();

    return $db->table('residents')
        ->select('evacuation_center')
        ->where('evacuation_center IS NOT NULL')
        ->where('evacuation_center !=', '')
        ->like('evacuation_center', $keyword)
        ->groupBy('evacuation_center')
        ->orderBy('evacuation_center', 'ASC')
        ->limit(10)
        ->get()
        ->getResultArray();
}

/**
 * Get ALL currently active events (multiple can be active now)
 */
public function getActiveEvents()
{
    return $this->where('is_active', 1)
                ->where('status', 'active')
                ->orderBy('event_name', 'ASC')
                ->findAll();
}
}