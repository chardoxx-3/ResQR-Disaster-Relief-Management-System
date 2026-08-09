<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    /**
     * Generates a summary for the Admin Dashboard
     */
    public function getDashboardSummary()
    {
        $db = \Config\Database::connect();
        
        return [
            'total_residents'   => $db->table('residents')->countAllResults(),
            'total_claimed'     => $db->table('residents')->where('status', 'claimed')->countAllResults(),
            'total_unclaimed'   => $db->table('residents')->where('status', 'unclaimed')->countAllResults(),
            'low_stock_items'   => $db->table('inventory')->where('quantity <', 50)->get()->getResultArray(),
        ];
    }

    /**
     * Get distribution count grouped by date for charts
     */
    public function getDistributionStats()
    {
        $db = \Config\Database::connect();
        return $db->table('distribution_logs')
                  ->select('DATE(claimed_at) as date, COUNT(id) as total')
                  ->groupBy('DATE(claimed_at)')
                  ->get()
                  ->getResultArray();
    }
}