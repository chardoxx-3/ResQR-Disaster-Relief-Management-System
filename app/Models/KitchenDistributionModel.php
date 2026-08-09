<?php

namespace App\Models;

use CodeIgniter\Model;

class KitchenDistributionModel extends Model
{
    protected $table            = 'kitchen_distributions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'event_id',
        'resident_id',
        'family_member_id',
        'distributor_id',
        'claimant_type',
        'qr_code_scanned',
        'distribution_date',
        'status',
        'remarks'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Has this specific claimant (head or family member) already claimed
     * a meal for this event today?
     */
    public function hasClaimedToday($residentId, $familyMemberId, $eventId)
    {
        $builder = $this->where('resident_id', $residentId)
                         ->where('event_id', $eventId)
                         ->where('DATE(distribution_date)', date('Y-m-d'));

        if ($familyMemberId) {
            $builder->where('family_member_id', $familyMemberId);
        } else {
            $builder->where('family_member_id IS NULL');
        }

        return $builder->first();
    }

/**
     * Recent claims for the live monitoring list
     */
    public function getRecentByEvent($eventId = null, $limit = 100, $date = null)
    {
        $date = $date ?: date('Y-m-d');

        $builder = $this->db->table('kitchen_distributions kd');
        $builder->select('kd.*,
                         r.first_name as head_first_name,
                         r.last_name as head_last_name,
                         r.household_no,
                         r.barangay,
                         r.photo as head_photo,
                         fm.name as family_member_name,
                         fm.relation,
                         fm.photo as member_photo,
                         u.username as distributor_name')
->join('residents r', 'r.id = kd.resident_id', 'left')   // was inner join
                ->join('family_members fm', 'fm.id = kd.family_member_id', 'left')
                ->join('users u', 'u.id = kd.distributor_id', 'left')
->where('DATE(kd.distribution_date)', $date)
                ->where('kd.status', 'completed') // NEW: keep the main list free of denial logs
                ->orderBy('kd.distribution_date', 'DESC')
                ->limit($limit);

        if ($eventId) {
            $builder->where('kd.event_id', $eventId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * All recent scan activity (successful claims AND duplicate-attempt denials)
     * for the live monitor popup feed.
     */
    public function getRecentActivity($eventId = null, $limit = 20, $date = null)
    {
        $date = $date ?: date('Y-m-d');

        $builder = $this->db->table('kitchen_distributions kd');
        $builder->select('kd.*,
                         r.first_name as head_first_name,
                         r.last_name as head_last_name,
                         r.household_no,
                         r.barangay,
                         r.photo as head_photo,
                         fm.name as family_member_name,
                         fm.relation,
                         fm.photo as member_photo')
                ->join('residents r', 'r.id = kd.resident_id', 'left')
                ->join('family_members fm', 'fm.id = kd.family_member_id', 'left')
                ->where('DATE(kd.distribution_date)', $date)
                ->orderBy('kd.distribution_date', 'DESC')
                ->limit($limit);

        if ($eventId) {
            $builder->where('kd.event_id', $eventId);
        }

        return $builder->get()->getResultArray();
    }

    public function getTodayCount($eventId = null, $date = null)
    {
        $date = $date ?: date('Y-m-d');
        $builder = $this->where('DATE(distribution_date)', $date)
                         ->where('status', 'completed'); // NEW: exclude denied duplicate-attempt logs
        if ($eventId) {
            $builder->where('event_id', $eventId);
        }
        return $builder->countAllResults();
    }

    /**
     * Next sequential guest number for this event, today.
     */
    public function getNextGuestNumber($eventId)
    {
        $count = $this->where('event_id', $eventId)
                      ->where('claimant_type', 'guest')
                      ->where('DATE(distribution_date)', date('Y-m-d'))
                      ->countAllResults();

        return $count + 1;
    }
}