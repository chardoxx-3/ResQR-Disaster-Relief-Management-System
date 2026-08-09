<?php

namespace App\Models;

use CodeIgniter\Model;

class DistributionModel extends Model
{
    protected $table            = 'distribution_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
protected $allowedFields = [
    'resident_id', 
    'family_member_id',  // Add this line
    'distributor_id', 
    'claimed_at'
];

    // We use claimed_at manually or via CI timestamps
    protected $useTimestamps = false; 

    /**
     * Get detailed logs with Resident and Distributor names
     */
    public function getDetailedLogs()
    {
        return $this->select('distribution_logs.*, residents.full_name as resident_name, users.username as distributor_name')
                    ->join('residents', 'residents.id = distribution_logs.resident_id')
                    ->join('users', 'users.id = distribution_logs.distributor_id')
                    ->orderBy('distribution_logs.claimed_at', 'DESC')
                    ->findAll();
    }
}