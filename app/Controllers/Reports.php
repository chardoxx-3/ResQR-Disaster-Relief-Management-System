<?php
// app/Controllers/Reports.php

namespace App\Controllers;

use App\Models\DistributionModel;
use App\Models\ResidentModel;
use App\Models\InventoryModel;
use App\Models\InventoryBatchModel;
use App\Models\AttendanceModel;
use App\Models\BatchDistributionModel;
use App\Models\FamilyMemberModel;

class Reports extends BaseController
{
    public function index()
    {
        // Check user permissions
        $userRole = session()->get('role');
        if ($userRole != 'admin' && $userRole != 'barangay' && $userRole != 'distributor') {
            return redirect()->to('/');
        }

        // Get filter parameters
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $selectedBarangay = $this->request->getGet('barangay') ?? '';
$selectedStreet   = $this->request->getGet('street') ?? '';

        // Load models
        $distributionModel = new BatchDistributionModel();
        $residentModel = new ResidentModel();
        $inventoryModel = new InventoryModel();
        $batchModel = new InventoryBatchModel();
        $attendanceModel = new AttendanceModel();

        // Get distributions with details
        $distributions = $distributionModel
            ->select('batch_distributions.*, 
                     residents.first_name, residents.last_name, residents.household_no, residents.barangay,
                     inventory.item_name, inventory.unit_type,
                     inventory_batches.batch_number,
                     users.username as distributor_name')
            ->join('residents', 'residents.id = batch_distributions.resident_id', 'left')
            ->join('inventory_batches', 'inventory_batches.id = batch_distributions.batch_id', 'left')
            ->join('inventory', 'inventory.id = inventory_batches.inventory_id', 'left')
            ->join('users', 'users.id = batch_distributions.distributor_id', 'left')
            ->orderBy('batch_distributions.distribution_date', 'DESC')
            ->findAll();

        // Get all batches with item details
        $batches = $batchModel
            ->select('inventory_batches.*, inventory.item_name, inventory.unit_type')
            ->join('inventory', 'inventory.id = inventory_batches.inventory_id')
            ->orderBy('inventory_batches.created_at', 'DESC')
            ->findAll();

// Get attendance records - FIXED
if ($selectedBarangay) {
    $attendance = $attendanceModel->getAttendanceWithDetails($startDate, $selectedBarangay);
} else {
    // If no barangay filter, get all attendance for the date range
    $attendance = $attendanceModel->getByDateRange($startDate, $endDate);
}

        // Get all residents with family member counts
        $residents = $residentModel
            ->select('residents.*, COUNT(family_members.id) as family_members_count')
            ->join('family_members', 'family_members.resident_id = residents.id', 'left')
            ->groupBy('residents.id')
            ->orderBy('residents.created_at', 'DESC')
            ->findAll();

        // Calculate summary statistics
        $totalDistributions = $distributionModel->countAll();
        $totalResidents = $residentModel->countAll();
        $activeBatches = $batchModel->where('quantity_remaining >', 0)->countAllResults();
        $todayAttendance = $attendanceModel->where('attendance_date', date('Y-m-d'))->countAllResults();

        // Get unique barangays for filter
        $barangays = $residentModel->distinct()->select('barangay')->where('barangay !=', '')->findAll();
        $barangayList = array_column($barangays, 'barangay');

        // Get top barangays by distribution
        $topBarangays = $residentModel
            ->select('residents.barangay, COUNT(batch_distributions.id) as count')
            ->join('batch_distributions', 'batch_distributions.resident_id = residents.id')
            ->where('residents.barangay !=', '')
            ->groupBy('residents.barangay')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->findAll();

        // Calculate percentages for top barangays
        $maxCount = !empty($topBarangays) ? max(array_column($topBarangays, 'count')) : 1;
        foreach ($topBarangays as &$barangay) {
            $barangay['percentage'] = ($barangay['count'] / $maxCount) * 100;
        }

        // Prepare chart data
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('D', strtotime($date));
            
            $count = $distributionModel
                ->where('DATE(distribution_date)', $date)
                ->countAllResults();
            $chartData[] = $count;
        }

        $data = [
            'distributions' => $distributions,
            'batches' => $batches,
            'attendance' => $attendance,
            'residents' => $residents,
            'totalDistributions' => $totalDistributions,
            'totalResidents' => $totalResidents,
            'activeBatches' => $activeBatches,
            'todayAttendance' => $todayAttendance,
            'barangays' => $barangayList,
            'selectedBarangay' => $selectedBarangay,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'topBarangays' => $topBarangays,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ];

        return view('reports/index', $data);
    }

/**
 * Handle report export functionality
 */
public function export()
{
    // Get form data from POST
    $type = $this->request->getPost('report_type');
    $format = $this->request->getPost('format');
    $startDate = $this->request->getPost('start_date');
    $endDate = $this->request->getPost('end_date');

    // Debug - log what we received
    log_message('debug', 'EXPORT REQUEST - Type: ' . $type . ', Format: ' . $format . ', Start: ' . $startDate . ', End: ' . $endDate);

    // Validate required fields
    if (!$type || !$format) {
        return redirect()->back()->with('error', 'Missing report type or format');
    }

    // Set default dates if not provided
    if (!$startDate) {
        $startDate = date('Y-m-01');
    }
    if (!$endDate) {
        $endDate = date('Y-m-d');
    }

    // Generate filename based on report type and format
    $filename = $type . '_report_' . date('Y-m-d_His');

    // Route to appropriate export method based on format
    if ($format === 'csv') {
        return $this->exportCSV($type, $startDate, $endDate, $filename);
    } 
    else if ($format === 'excel') {
        return $this->exportExcel($type, $startDate, $endDate, $filename);
    } 
    else if ($format === 'pdf') {
        return $this->exportPDF($type, $startDate, $endDate, $filename);
    } 
    else {
        return redirect()->back()->with('error', 'Unsupported export format: ' . $format);
    }
}

    /**
     * Export report as CSV
     */
    private function exportCSV($type, $startDate, $endDate, $filename)
    {
        // Load models
        $distributionModel = new BatchDistributionModel();
        $residentModel = new ResidentModel();
        $batchModel = new InventoryBatchModel();
        $attendanceModel = new AttendanceModel();

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Generate report based on type
        switch ($type) {
            case 'distributions':
                $this->exportDistributionsCSV($output, $startDate, $endDate);
                break;
            case 'inventory':
                $this->exportInventoryCSV($output, $startDate, $endDate);
                break;
            case 'attendance':
                $this->exportAttendanceCSV($output, $startDate, $endDate);
                break;
            case 'residents':
                $this->exportResidentsCSV($output, $startDate, $endDate);
                break;
            default:
                fputcsv($output, ['Error: Invalid report type']);
        }

        fclose($output);
        exit;
    }

    /**
     * Export distributions report as CSV
     */
    private function exportDistributionsCSV($output, $startDate, $endDate)
    {
        // Add CSV headers
        fputcsv($output, [
            'Date',
            'Time',
            'Household #',
            'Recipient Name',
            'Barangay',
            'Item',
            'Batch #',
            'Quantity',
            'Unit',
            'Distributor',
            'Status'
        ]);

        // Get data
        $distributionModel = new BatchDistributionModel();
        $distributions = $distributionModel
            ->select('batch_distributions.*, 
                     residents.first_name, residents.last_name, residents.household_no, residents.barangay,
                     inventory.item_name, inventory.unit_type,
                     inventory_batches.batch_number,
                     users.username as distributor_name')
            ->join('residents', 'residents.id = batch_distributions.resident_id', 'left')
            ->join('inventory_batches', 'inventory_batches.id = batch_distributions.batch_id', 'left')
            ->join('inventory', 'inventory.id = inventory_batches.inventory_id', 'left')
            ->join('users', 'users.id = batch_distributions.distributor_id', 'left')
            ->where('DATE(batch_distributions.distribution_date) >=', $startDate)
            ->where('DATE(batch_distributions.distribution_date) <=', $endDate)
            ->orderBy('batch_distributions.distribution_date', 'DESC')
            ->findAll();

        // Add data rows
        foreach ($distributions as $d) {
            $date = date('Y-m-d', strtotime($d['distribution_date']));
            $time = date('H:i:s', strtotime($d['distribution_date']));
            
            fputcsv($output, [
                $date,
                $time,
                $d['household_no'] ?? '',
                trim(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? '')),
                $d['barangay'] ?? '',
                $d['item_name'] ?? '',
                $d['batch_number'] ?? '',
                $d['quantity_distributed'] ?? 1,
                $d['unit_type'] ?? '',
                $d['distributor_name'] ?? 'System',
                $d['status'] ?? 'completed'
            ]);
        }
    }

    /**
     * Export inventory report as CSV
     */
    private function exportInventoryCSV($output, $startDate, $endDate)
    {
        // Add CSV headers
        fputcsv($output, [
            'Item Name',
            'Batch #',
            'Source Type',
            'Source Details',
            'Initial Quantity',
            'Remaining Quantity',
            'Unit',
            'Date Received',
            'Expiry Date',
            'Storage Location',
            'Assigned To',
            'Status'
        ]);

        // Get data
        $batchModel = new InventoryBatchModel();
        $batches = $batchModel
            ->select('inventory_batches.*, inventory.item_name')
            ->join('inventory', 'inventory.id = inventory_batches.inventory_id')
            ->where('DATE(inventory_batches.date_received) >=', $startDate)
            ->where('DATE(inventory_batches.date_received) <=', $endDate)
            ->orderBy('inventory_batches.date_received', 'DESC')
            ->findAll();

        // Add data rows
        foreach ($batches as $b) {
            fputcsv($output, [
                $b['item_name'] ?? '',
                $b['batch_number'] ?? '',
                $b['source_type'] ?? '',
                $b['source_details'] ?? '',
                $b['quantity_initial'] ?? 0,
                $b['quantity_remaining'] ?? 0,
                $b['unit_type'] ?? '',
                $b['date_received'] ?? '',
                $b['expiry_date'] ?? '',
                $b['storage_location'] ?? '',
                $b['assigned_to'] ?? 'Unassigned',
                $b['status'] ?? ''
            ]);
        }
    }

    /**
     * Export attendance report as CSV
     */
    private function exportAttendanceCSV($output, $startDate, $endDate)
    {
        // Add CSV headers
        fputcsv($output, [
            'Date',
            'Household #',
            'Name',
            'Type',
            'Barangay',
            'Check In Time',
            'Check Out Time',
            'Purpose',
            'Scanned By',
            'Status'
        ]);

        // Get data
        $attendanceModel = new AttendanceModel();
        $attendances = $attendanceModel->getByDateRange($startDate, $endDate);

        // Add data rows
        foreach ($attendances as $a) {
            $name = trim(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));
            $type = $a['family_member_name'] ? 'Family Member (' . $a['family_member_name'] . ')' : 'Head of Family';
            
            fputcsv($output, [
                $a['attendance_date'] ?? '',
                $a['household_no'] ?? '',
                $name,
                $type,
                $a['barangay'] ?? '',
                $a['check_in_time'] ?? '',
                $a['check_out_time'] ?? '',
                $a['purpose'] ?? '',
                $a['scanned_by_name'] ?? 'System',
                isset($a['check_out_time']) ? 'Completed' : 'Active'
            ]);
        }
    }

    /**
     * Export residents report as CSV
     */
    private function exportResidentsCSV($output, $startDate, $endDate)
    {
        // Add CSV headers
        fputcsv($output, [
            'Household #',
            'Last Name',
            'First Name',
            'Middle Name',
            'Extension',
            'Birthdate',
            'Age',
            'Sex',
            'Civil Status',
            'Contact Number',
            'Barangay',
            'City/Municipality',
            'Province',
            'Registration Date',
            'Family Members Count',
            'Status'
        ]);

        // Get data
        $residentModel = new ResidentModel();
        $residents = $residentModel
            ->select('residents.*, COUNT(family_members.id) as family_members_count')
            ->join('family_members', 'family_members.resident_id = residents.id', 'left')
            ->where('DATE(residents.created_at) >=', $startDate)
            ->where('DATE(residents.created_at) <=', $endDate)
            ->groupBy('residents.id')
            ->orderBy('residents.created_at', 'DESC')
            ->findAll();

        // Add data rows
        foreach ($residents as $r) {
            fputcsv($output, [
                $r['household_no'] ?? '',
                $r['last_name'] ?? '',
                $r['first_name'] ?? '',
                $r['middle_name'] ?? '',
                $r['name_extension'] ?? '',
                $r['birthdate'] ?? '',
                $r['age'] ?? '',
                $r['sex'] ?? '',
                $r['civil_status'] ?? '',
                $r['contact_number'] ?? '',
                $r['barangay'] ?? '',
                $r['city_municipality'] ?? '',
                $r['province'] ?? '',
                $r['created_at'] ?? '',
                $r['family_members_count'] ?? 0,
                $r['status'] ?? ''
            ]);
        }
    }

/**
 * Export report as Excel (XLSX)
 */
private function exportExcel($type, $startDate, $endDate, $filename)
{
    // Check if PhpSpreadsheet is installed
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        // If not installed, show helpful message
        log_message('error', 'PhpSpreadsheet not installed. Please run: composer require phpoffice/phpspreadsheet');
        
        // Fallback to CSV with user-friendly message
        header('Content-Type: text/html');
        echo '<html><head><title>Excel Export Error</title>';
        echo '<style>body{font-family:Arial;padding:30px;background:#f8fafc;color:#1e293b}</style></head>';
        echo '<body><h2>📊 Excel Export Requires Additional Library</h2>';
        echo '<p>To enable Excel export functionality, please install PhpSpreadsheet:</p>';
        echo '<pre style="background:#1e293b;color:#fff;padding:15px;border-radius:8px;">composer require phpoffice/phpspreadsheet</pre>';
        echo '<p><a href="javascript:history.back()" style="background:#77BC3F;color:#fff;padding:10px 20px;text-decoration:none;border-radius:6px;">← Go Back</a></p>';
        echo '</body></html>';
        exit;
    }

    // Load PhpSpreadsheet
    require_once ROOTPATH . 'vendor/autoload.php';

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator("Relief System")
        ->setLastModifiedBy("Relief System")
        ->setTitle(ucfirst($type) . " Report")
        ->setSubject("Export from Relief System")
        ->setDescription("Report generated on " . date('Y-m-d H:i:s'));

    // Style for title
    $sheet->setCellValue('A1', ucfirst($type) . ' Report');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FF4A7A26');
    $sheet->mergeCells('A1:K1');

    $sheet->setCellValue('A2', 'Date Range: ' . $startDate . ' to ' . $endDate);
    $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
    $sheet->mergeCells('A2:K2');

    $sheet->setCellValue('A3', 'Generated: ' . date('Y-m-d H:i:s'));
    $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);
    $sheet->mergeCells('A3:K3');

    $row = 5; // Start data from row 5

    // Load models
    $distributionModel = new BatchDistributionModel();
    $residentModel = new ResidentModel();
    $batchModel = new InventoryBatchModel();
    $attendanceModel = new AttendanceModel();

    switch ($type) {
        case 'distributions':
            // Set headers
            $headers = ['Date', 'Time', 'Household #', 'Recipient Name', 'Barangay', 
                       'Item', 'Batch #', 'Quantity', 'Unit', 'Distributor', 'Status'];
            
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $sheet->getStyle($col . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF0FDF4');
                $col++;
            }

            // Get data
            $distributions = $distributionModel
                ->select('batch_distributions.*, 
                         residents.first_name, residents.last_name, residents.household_no, residents.barangay,
                         inventory.item_name, inventory.unit_type,
                         inventory_batches.batch_number,
                         users.username as distributor_name')
                ->join('residents', 'residents.id = batch_distributions.resident_id', 'left')
                ->join('inventory_batches', 'inventory_batches.id = batch_distributions.batch_id', 'left')
                ->join('inventory', 'inventory.id = inventory_batches.inventory_id', 'left')
                ->join('users', 'users.id = batch_distributions.distributor_id', 'left')
                ->where('DATE(batch_distributions.distribution_date) >=', $startDate)
                ->where('DATE(batch_distributions.distribution_date) <=', $endDate)
                ->orderBy('batch_distributions.distribution_date', 'DESC')
                ->findAll();

            foreach ($distributions as $d) {
                $row++;
                $date = date('Y-m-d', strtotime($d['distribution_date']));
                $time = date('H:i:s', strtotime($d['distribution_date']));
                
                $sheet->setCellValue('A' . $row, $date);
                $sheet->setCellValue('B' . $row, $time);
                $sheet->setCellValue('C' . $row, $d['household_no'] ?? '');
                $sheet->setCellValue('D' . $row, trim(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? '')));
                $sheet->setCellValue('E' . $row, $d['barangay'] ?? '');
                $sheet->setCellValue('F' . $row, $d['item_name'] ?? '');
                $sheet->setCellValue('G' . $row, $d['batch_number'] ?? '');
                $sheet->setCellValue('H' . $row, $d['quantity_distributed'] ?? 1);
                $sheet->setCellValue('I' . $row, $d['unit_type'] ?? '');
                $sheet->setCellValue('J' . $row, $d['distributor_name'] ?? 'System');
                $sheet->setCellValue('K' . $row, $d['status'] ?? 'completed');
            }
            break;

        case 'inventory':
            // Similar headers for inventory...
            $headers = ['Batch #', 'Item', 'Source Type', 'Source Details', 'Initial Qty', 
                       'Remaining', 'Unit', 'Date Received', 'Expiry Date', 'Location', 'Assigned To', 'Status'];
            
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $sheet->getStyle($col . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF0FDF4');
                $col++;
            }

            $batches = $batchModel
                ->select('inventory_batches.*, inventory.item_name')
                ->join('inventory', 'inventory.id = inventory_batches.inventory_id')
                ->where('DATE(inventory_batches.date_received) >=', $startDate)
                ->where('DATE(inventory_batches.date_received) <=', $endDate)
                ->orderBy('inventory_batches.date_received', 'DESC')
                ->findAll();

            foreach ($batches as $b) {
                $row++;
                $sheet->setCellValue('A' . $row, $b['batch_number'] ?? '');
                $sheet->setCellValue('B' . $row, $b['item_name'] ?? '');
                $sheet->setCellValue('C' . $row, $b['source_type'] ?? '');
                $sheet->setCellValue('D' . $row, $b['source_details'] ?? '');
                $sheet->setCellValue('E' . $row, $b['quantity_initial'] ?? 0);
                $sheet->setCellValue('F' . $row, $b['quantity_remaining'] ?? 0);
                $sheet->setCellValue('G' . $row, $b['unit_type'] ?? '');
                $sheet->setCellValue('H' . $row, $b['date_received'] ?? '');
                $sheet->setCellValue('I' . $row, $b['expiry_date'] ?? '');
                $sheet->setCellValue('J' . $row, $b['storage_location'] ?? '');
                $sheet->setCellValue('K' . $row, $b['assigned_to'] ?? 'Unassigned');
                $sheet->setCellValue('L' . $row, $b['status'] ?? '');
            }
            break;

        case 'attendance':
            // Similar for attendance...
            $headers = ['Date', 'Household #', 'Name', 'Type', 'Barangay', 'Check In', 'Check Out', 'Purpose', 'Status'];
            
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $sheet->getStyle($col . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF0FDF4');
                $col++;
            }

            $attendances = $attendanceModel->getByDateRange($startDate, $endDate);

            foreach ($attendances as $a) {
                $row++;
                $name = trim(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));
                $type = $a['family_member_name'] ? 'Family Member' : 'Head of Family';
                
                $sheet->setCellValue('A' . $row, $a['attendance_date'] ?? '');
                $sheet->setCellValue('B' . $row, $a['household_no'] ?? '');
                $sheet->setCellValue('C' . $row, $name);
                $sheet->setCellValue('D' . $row, $type);
                $sheet->setCellValue('E' . $row, $a['barangay'] ?? '');
                $sheet->setCellValue('F' . $row, $a['check_in_time'] ?? '');
                $sheet->setCellValue('G' . $row, $a['check_out_time'] ?? '');
                $sheet->setCellValue('H' . $row, $a['purpose'] ?? '');
                $sheet->setCellValue('I' . $row, isset($a['check_out_time']) ? 'Completed' : 'Active');
            }
            break;

        case 'residents':
            // Similar for residents...
            $headers = ['Household #', 'Last Name', 'First Name', 'Middle Name', 'Extension', 
                       'Birthdate', 'Age', 'Sex', 'Civil Status', 'Contact', 'Barangay', 'City', 'Status'];
            
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $sheet->getStyle($col . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF0FDF4');
                $col++;
            }

            $residents = $residentModel
                ->select('residents.*')
                ->where('DATE(residents.created_at) >=', $startDate)
                ->where('DATE(residents.created_at) <=', $endDate)
                ->orderBy('residents.created_at', 'DESC')
                ->findAll();

            foreach ($residents as $r) {
                $row++;
                $sheet->setCellValue('A' . $row, $r['household_no'] ?? '');
                $sheet->setCellValue('B' . $row, $r['last_name'] ?? '');
                $sheet->setCellValue('C' . $row, $r['first_name'] ?? '');
                $sheet->setCellValue('D' . $row, $r['middle_name'] ?? '');
                $sheet->setCellValue('E' . $row, $r['name_extension'] ?? '');
                $sheet->setCellValue('F' . $row, $r['birthdate'] ?? '');
                $sheet->setCellValue('G' . $row, $r['age'] ?? '');
                $sheet->setCellValue('H' . $row, $r['sex'] ?? '');
                $sheet->setCellValue('I' . $row, $r['civil_status'] ?? '');
                $sheet->setCellValue('J' . $row, $r['contact_number'] ?? '');
                $sheet->setCellValue('K' . $row, $r['barangay'] ?? '');
                $sheet->setCellValue('L' . $row, $r['city_municipality'] ?? '');
                $sheet->setCellValue('M' . $row, $r['status'] ?? '');
            }
            break;
    }

    // Auto-size columns
    $lastColumn = $sheet->getHighestColumn();
    $lastRow = $sheet->getHighestRow();
    
    foreach (range('A', $lastColumn) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Style the data rows with alternating colors
    for ($i = 6; $i <= $lastRow; $i++) {
        if ($i % 2 == 0) {
            $sheet->getStyle('A' . $i . ':' . $lastColumn . $i)
                ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF9F9F9');
        }
    }

    // Add borders to all cells
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FFE2E8F0'],
            ],
        ],
    ];
    $sheet->getStyle('A5:' . $lastColumn . $lastRow)->applyFromArray($styleArray);

    // Set headers for Excel download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

/**
 * Export report as PDF with enhanced analytics
 */
private function exportPDF($type, $startDate, $endDate, $filename)
{
    // Check if Dompdf is installed
    if (!class_exists('Dompdf\Dompdf')) {
        log_message('error', 'Dompdf not installed. Please run: composer require dompdf/dompdf');
        
        // Show helpful message
        header('Content-Type: text/html');
        echo '<html><head><title>PDF Export Error</title>';
        echo '<style>body{font-family:Arial;padding:30px;background:#f8fafc;color:#1e293b}</style></head>';
        echo '<body><h2>📄 PDF Export Requires Additional Library</h2>';
        echo '<p>To enable PDF export functionality, please install Dompdf:</p>';
        echo '<pre style="background:#1e293b;color:#fff;padding:15px;border-radius:8px;">composer require dompdf/dompdf</pre>';
        echo '<p><a href="javascript:history.back()" style="background:#77BC3F;color:#fff;padding:10px 20px;text-decoration:none;border-radius:6px;">← Go Back</a></p>';
        echo '</body></html>';
        exit;
    }

    // Load models
    $distributionModel = new BatchDistributionModel();
    $residentModel = new ResidentModel();
    $batchModel = new InventoryBatchModel();
    $attendanceModel = new AttendanceModel();
    $familyMemberModel = new \App\Models\FamilyMemberModel();
    $userModel = new \App\Models\UserModel();
    
    $db = \Config\Database::connect();

    // Get data based on report type
    $data = [
        'title' => ucfirst($type) . ' Report',
        'date_range' => $startDate . ' to ' . $endDate,
        'generated_date' => date('Y-m-d H:i:s'),
        'generated_by' => session()->get('username') ?? 'System',
        'type' => $type,
        'start_date' => $startDate,
        'end_date' => $endDate
    ];

    switch ($type) {
        case 'distributions':
            // Get detailed distribution data
            $data['logs'] = $distributionModel
                ->select('batch_distributions.*, 
                         residents.first_name, residents.last_name, residents.household_no, residents.barangay,
                         inventory.item_name, inventory.unit_type,
                         inventory_batches.batch_number, inventory_batches.source_type,
                         users.username as distributor_name,
                         family_members.name as family_member_name')
                ->join('residents', 'residents.id = batch_distributions.resident_id', 'left')
                ->join('inventory_batches', 'inventory_batches.id = batch_distributions.batch_id', 'left')
                ->join('inventory', 'inventory.id = inventory_batches.inventory_id', 'left')
                ->join('users', 'users.id = batch_distributions.distributor_id', 'left')
                ->join('family_members', 'family_members.id = batch_distributions.family_member_id', 'left')
                ->where('DATE(batch_distributions.distribution_date) >=', $startDate)
                ->where('DATE(batch_distributions.distribution_date) <=', $endDate)
                ->orderBy('batch_distributions.distribution_date', 'DESC')
                ->findAll();
            
            // ENHANCED ANALYTICS FOR DISTRIBUTIONS
            $totalDistributed = count($data['logs']);
            $uniqueHouseholds = count(array_unique(array_column($data['logs'], 'household_no')));
            
            // Items distribution breakdown
            $itemsBreakdown = [];
            $barangayBreakdown = [];
            $dailyTrend = [];
            
            foreach ($data['logs'] as $log) {
                // Items breakdown
                $itemName = $log['item_name'] ?? 'Unknown';
                if (!isset($itemsBreakdown[$itemName])) {
                    $itemsBreakdown[$itemName] = [
                        'count' => 0,
                        'quantity' => 0
                    ];
                }
                $itemsBreakdown[$itemName]['count']++;
                $itemsBreakdown[$itemName]['quantity'] += $log['quantity_distributed'] ?? 1;
                
                // Barangay breakdown
                $brgy = $log['barangay'] ?? 'Unknown';
                if (!isset($barangayBreakdown[$brgy])) {
                    $barangayBreakdown[$brgy] = 0;
                }
                $barangayBreakdown[$brgy]++;
                
                // Daily trend
                $date = date('Y-m-d', strtotime($log['distribution_date']));
                if (!isset($dailyTrend[$date])) {
                    $dailyTrend[$date] = 0;
                }
                $dailyTrend[$date]++;
            }
            
            // Get source type breakdown
            $sourceBreakdown = [];
            foreach ($data['logs'] as $log) {
                $source = $log['source_type'] ?? 'Unknown';
                if (!isset($sourceBreakdown[$source])) {
                    $sourceBreakdown[$source] = 0;
                }
                $sourceBreakdown[$source]++;
            }
            
            // Get distributor performance
            $distributorPerformance = [];
            foreach ($data['logs'] as $log) {
                $distributor = $log['distributor_name'] ?? 'System';
                if (!isset($distributorPerformance[$distributor])) {
                    $distributorPerformance[$distributor] = 0;
                }
                $distributorPerformance[$distributor]++;
            }
            
            $data['summary'] = [
                'total' => $totalDistributed,
                'unique_households' => $uniqueHouseholds,
                'items_distributed' => count($itemsBreakdown),
                'avg_per_day' => $totalDistributed > 0 ? round($totalDistributed / max(count($dailyTrend), 1), 1) : 0,
                'items_breakdown' => $itemsBreakdown,
                'barangay_breakdown' => $barangayBreakdown,
                'daily_trend' => $dailyTrend,
                'source_breakdown' => $sourceBreakdown,
                'distributor_performance' => $distributorPerformance,
                'peak_day' => !empty($dailyTrend) ? array_search(max($dailyTrend), $dailyTrend) : 'N/A',
                'peak_day_count' => !empty($dailyTrend) ? max($dailyTrend) : 0
            ];
            break;

        case 'inventory':
            // Get all batches with detailed info
            $data['batches'] = $batchModel
                ->select('inventory_batches.*, inventory.item_name, inventory.unit_type, inventory.allocation')
                ->join('inventory', 'inventory.id = inventory_batches.inventory_id')
                ->where('DATE(inventory_batches.date_received) >=', $startDate)
                ->where('DATE(inventory_batches.date_received) <=', $endDate)
                ->orderBy('inventory_batches.date_received', 'DESC')
                ->findAll();
            
            // ENHANCED INVENTORY ANALYTICS
            $totalInitial = 0;
            $totalRemaining = 0;
            $totalUtilized = 0;
            $expiringSoon = 0;
            $expired = 0;
            $statusBreakdown = [
                'in_warehouse' => 0,
                'in_transit' => 0,
                'received' => 0,
                'depleted' => 0,
                'expired' => 0
            ];
            $itemCategoryBreakdown = [];
            $sourceTypeBreakdown = [];
            $barangayInventory = [];
            
            $today = time();
            $thirtyDaysFromNow = strtotime('+30 days');
            
            foreach ($data['batches'] as $batch) {
                $totalInitial += $batch['quantity_initial'];
                $totalRemaining += $batch['quantity_remaining'];
                
                // Status breakdown
                $status = $batch['status'] ?? 'unknown';
                if (isset($statusBreakdown[$status])) {
                    $statusBreakdown[$status]++;
                }
                
                // Item category breakdown
                $itemName = $batch['item_name'] ?? 'Unknown';
                if (!isset($itemCategoryBreakdown[$itemName])) {
                    $itemCategoryBreakdown[$itemName] = [
                        'initial' => 0,
                        'remaining' => 0,
                        'batches' => 0
                    ];
                }
                $itemCategoryBreakdown[$itemName]['initial'] += $batch['quantity_initial'];
                $itemCategoryBreakdown[$itemName]['remaining'] += $batch['quantity_remaining'];
                $itemCategoryBreakdown[$itemName]['batches']++;
                
                // Source type breakdown
                $source = $batch['source_type'] ?? 'Unknown';
                if (!isset($sourceTypeBreakdown[$source])) {
                    $sourceTypeBreakdown[$source] = [
                        'initial' => 0,
                        'remaining' => 0
                    ];
                }
                $sourceTypeBreakdown[$source]['initial'] += $batch['quantity_initial'];
                $sourceTypeBreakdown[$source]['remaining'] += $batch['quantity_remaining'];
                
                // Barangay inventory
                $assignedTo = $batch['assigned_to'] ?? 'Unassigned';
                if (!isset($barangayInventory[$assignedTo])) {
                    $barangayInventory[$assignedTo] = [
                        'total' => 0,
                        'items' => []
                    ];
                }
                $barangayInventory[$assignedTo]['total'] += $batch['quantity_remaining'];
                
                // Expiry tracking
                if (!empty($batch['expiry_date'])) {
                    $expiryTimestamp = strtotime($batch['expiry_date']);
                    if ($expiryTimestamp < $today) {
                        $expired++;
                    } elseif ($expiryTimestamp <= $thirtyDaysFromNow) {
                        $expiringSoon++;
                    }
                }
            }
            
            $totalUtilized = $totalInitial - $totalRemaining;
            $utilizationRate = $totalInitial > 0 ? round(($totalUtilized / $totalInitial) * 100, 1) : 0;
            
            // Get low stock items (less than 20% remaining)
            $lowStockItems = [];
            foreach ($itemCategoryBreakdown as $item => $data) {
                $remainingPercent = $data['initial'] > 0 ? ($data['remaining'] / $data['initial']) * 100 : 0;
                if ($remainingPercent < 20) {
                    $lowStockItems[$item] = [
                        'remaining' => $data['remaining'],
                        'percent' => round($remainingPercent, 1)
                    ];
                }
            }
            
            $data['summary'] = [
                'total_batches' => count($data['batches']),
                'total_initial' => $totalInitial,
                'total_remaining' => $totalRemaining,
                'total_utilized' => $totalUtilized,
                'utilization_rate' => $utilizationRate,
                'expiring_soon' => $expiringSoon,
                'expired' => $expired,
                'status_breakdown' => $statusBreakdown,
                'item_category_breakdown' => $itemCategoryBreakdown,
                'source_type_breakdown' => $sourceTypeBreakdown,
                'barangay_inventory' => $barangayInventory,
                'low_stock_items' => $lowStockItems,
                'avg_batch_size' => count($data['batches']) > 0 ? round($totalInitial / count($data['batches']), 1) : 0
            ];
            break;

        case 'attendance':
            // Get attendance records
            $data['attendance'] = $attendanceModel->getByDateRange($startDate, $endDate);
            
            // ENHANCED ATTENDANCE ANALYTICS
            $totalAttendance = count($data['attendance']);
            $checkedIn = 0;
            $completed = 0;
            $peakHours = [];
            $barangayAttendance = [];
            $dailyAttendanceTrend = [];
            $purposeBreakdown = [];
            $hourlyDistribution = array_fill(0, 24, 0);
            
            foreach ($data['attendance'] as $a) {
                // Status tracking
                if (empty($a['check_out_time'])) {
                    $checkedIn++;
                } else {
                    $completed++;
                }
                
                // Barangay breakdown
                $brgy = $a['barangay'] ?? 'Unknown';
                if (!isset($barangayAttendance[$brgy])) {
                    $barangayAttendance[$brgy] = 0;
                }
                $barangayAttendance[$brgy]++;
                
                // Daily trend
                $date = $a['attendance_date'];
                if (!isset($dailyAttendanceTrend[$date])) {
                    $dailyAttendanceTrend[$date] = 0;
                }
                $dailyAttendanceTrend[$date]++;
                
                // Purpose breakdown
                $purpose = $a['purpose'] ?? 'General';
                if (!isset($purposeBreakdown[$purpose])) {
                    $purposeBreakdown[$purpose] = 0;
                }
                $purposeBreakdown[$purpose]++;
                
                // Peak hours analysis
                if (!empty($a['check_in_time'])) {
                    $hour = (int)date('H', strtotime($a['check_in_time']));
                    if (isset($hourlyDistribution[$hour])) {
                        $hourlyDistribution[$hour]++;
                    }
                }
            }
            
            // Find peak hour
            $peakHour = array_search(max($hourlyDistribution), $hourlyDistribution);
            
            // Calculate average check-in time
            $totalMinutes = 0;
            $countWithTime = 0;
            foreach ($data['attendance'] as $a) {
                if (!empty($a['check_in_time'])) {
                    $time = strtotime($a['check_in_time']);
                    $minutes = (int)date('H', $time) * 60 + (int)date('i', $time);
                    $totalMinutes += $minutes;
                    $countWithTime++;
                }
            }
            $avgMinutes = $countWithTime > 0 ? round($totalMinutes / $countWithTime) : 0;
            $avgCheckInTime = sprintf('%02d:%02d', floor($avgMinutes / 60), $avgMinutes % 60);
            
            $data['summary'] = [
                'total' => $totalAttendance,
                'checked_in_now' => $checkedIn,
                'completed' => $completed,
                'completion_rate' => $totalAttendance > 0 ? round(($completed / $totalAttendance) * 100, 1) : 0,
                'barangay_breakdown' => $barangayAttendance,
                'daily_trend' => $dailyAttendanceTrend,
                'purpose_breakdown' => $purposeBreakdown,
                'hourly_distribution' => $hourlyDistribution,
                'peak_hour' => $peakHour,
                'peak_hour_count' => $hourlyDistribution[$peakHour] ?? 0,
                'avg_check_in_time' => $avgCheckInTime,
                'avg_daily' => $totalAttendance > 0 ? round($totalAttendance / max(count($dailyAttendanceTrend), 1), 1) : 0
            ];
            break;

        case 'residents':
            // Get residents with comprehensive data
            $data['residents'] = $residentModel
                ->select('residents.*, 
                         (SELECT COUNT(*) FROM family_members WHERE family_members.resident_id = residents.id) as family_members_count')
                ->where('DATE(residents.created_at) >=', $startDate)
                ->where('DATE(residents.created_at) <=', $endDate)
                ->orderBy('residents.created_at', 'DESC')
                ->findAll();
            
            // ENHANCED RESIDENT ANALYTICS
            $totalResidents = count($data['residents']);
            $claimed = 0;
            $pending = 0;
            $male = 0;
            $female = 0;
            $ageGroups = [
                '0-5' => 0,
                '6-12' => 0,
                '13-17' => 0,
                '18-30' => 0,
                '31-50' => 0,
                '51-65' => 0,
                '65+' => 0
            ];
            $barangayResidents = [];
            $vulnerableCounts = [
                'senior' => 0,
                'pwd' => 0,
                'pregnant' => 0,
                'lactating' => 0
            ];
            $civilStatusBreakdown = [];
            $occupationBreakdown = [];
            
            // Family member stats
            $totalFamilyMembers = 0;
            $avgFamilySize = 0;
            
            foreach ($data['residents'] as $r) {
                // Status
                if (($r['status'] ?? '') == 'claimed') {
                    $claimed++;
                } else {
                    $pending++;
                }
                
                // Gender
                if (($r['sex'] ?? '') == 'Male') {
                    $male++;
                } elseif (($r['sex'] ?? '') == 'Female') {
                    $female++;
                }
                
                // Age groups
                $age = (int)($r['age'] ?? 0);
                if ($age <= 5) $ageGroups['0-5']++;
                elseif ($age <= 12) $ageGroups['6-12']++;
                elseif ($age <= 17) $ageGroups['13-17']++;
                elseif ($age <= 30) $ageGroups['18-30']++;
                elseif ($age <= 50) $ageGroups['31-50']++;
                elseif ($age <= 65) $ageGroups['51-65']++;
                else $ageGroups['65+']++;
                
                // Barangay
                $brgy = $r['barangay'] ?? 'Unknown';
                if (!isset($barangayResidents[$brgy])) {
                    $barangayResidents[$brgy] = 0;
                }
                $barangayResidents[$brgy]++;
                
                // Vulnerable counts
                if (!empty($r['vulnerable_older_persons'])) {
                    $vulnerableCounts['senior'] += (int)$r['vulnerable_older_persons'];
                }
                if (!empty($r['vulnerable_pwd'])) {
                    $vulnerableCounts['pwd'] += (int)$r['vulnerable_pwd'];
                }
                if (!empty($r['vulnerable_pregnant'])) {
                    $vulnerableCounts['pregnant'] += (int)$r['vulnerable_pregnant'];
                }
                if (!empty($r['vulnerable_lactating'])) {
                    $vulnerableCounts['lactating'] += (int)$r['lactating'];
                }
                
                // Civil status
                $status = $r['civil_status'] ?? 'Unknown';
                if (!isset($civilStatusBreakdown[$status])) {
                    $civilStatusBreakdown[$status] = 0;
                }
                $civilStatusBreakdown[$status]++;
                
                // Occupation (simplified)
                $occ = !empty($r['occupation']) ? 'Employed' : 'Unemployed/Not Specified';
                if (!isset($occupationBreakdown[$occ])) {
                    $occupationBreakdown[$occ] = 0;
                }
                $occupationBreakdown[$occ]++;
                
                // Family members
                $familyCount = (int)($r['family_members_count'] ?? 0);
                $totalFamilyMembers += $familyCount;
            }
            
            $avgFamilySize = $totalResidents > 0 ? round($totalFamilyMembers / $totalResidents, 1) : 0;
            $totalBeneficiaries = $totalResidents + $totalFamilyMembers;
            
            // Get 4Ps beneficiaries count
            $fourPsCount = $residentModel->where('is_4ps_beneficiary', 1)
                                        ->where('DATE(created_at) >=', $startDate)
                                        ->where('DATE(created_at) <=', $endDate)
                                        ->countAllResults();
            
            $data['summary'] = [
                'total' => $totalResidents,
                'claimed' => $claimed,
                'pending' => $pending,
                'claim_rate' => $totalResidents > 0 ? round(($claimed / $totalResidents) * 100, 1) : 0,
                'male' => $male,
                'female' => $female,
                'age_groups' => $ageGroups,
                'barangay_breakdown' => $barangayResidents,
                'vulnerable' => $vulnerableCounts,
                'civil_status' => $civilStatusBreakdown,
                'occupation' => $occupationBreakdown,
                'total_family_members' => $totalFamilyMembers,
                'total_beneficiaries' => $totalBeneficiaries,
                'avg_family_size' => $avgFamilySize,
                'four_ps_beneficiaries' => $fourPsCount,
                'registration_trend' => $this->getRegistrationTrend($startDate, $endDate)
            ];
            break;
    }

    // Load the view content
    $html = view('reports/pdf_export', $data);

    // Initialize Dompdf
    $dompdf = new \Dompdf\Dompdf();
    $options = $dompdf->getOptions();
    $options->setDefaultFont('Helvetica');
    $options->setIsRemoteEnabled(true);
    $options->setChroot(ROOTPATH); // Security
    $dompdf->setOptions($options);
    
    // Load HTML content
    $dompdf->loadHtml($html);
    
    // Set paper size and orientation (use 'portrait' for residents report, 'landscape' for others)
    if ($type == 'residents') {
        $dompdf->setPaper('A4', 'portrait');
    } else {
        $dompdf->setPaper('A4', 'landscape');
    }
    
    // Render PDF
    $dompdf->render();
    
    // Set headers for PDF download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    
    // Output PDF
    echo $dompdf->output();
    exit;
}

/**
 * Helper method to get registration trend
 */
private function getRegistrationTrend($startDate, $endDate)
{
    $db = \Config\Database::connect();
    
    $results = $db->table('residents')
        ->select('DATE(created_at) as date, COUNT(*) as count')
        ->where('DATE(created_at) >=', $startDate)
        ->where('DATE(created_at) <=', $endDate)
        ->groupBy('DATE(created_at)')
        ->orderBy('date', 'ASC')
        ->get()
        ->getResultArray();
    
    $trend = [];
    foreach ($results as $row) {
        $trend[$row['date']] = (int)$row['count'];
    }
    
    return $trend;
}

    /**
     * Export view for PDF generation
     * This is the existing method that returns the print-friendly view
     */
    public function export_view()
    {
        $distributionModel = new BatchDistributionModel();
        
        $logs = $distributionModel
            ->select('batch_distributions.*, 
                     residents.first_name, residents.last_name, residents.household_no, residents.barangay, residents.address,
                     inventory.item_name,
                     inventory_batches.batch_number,
                     users.username as distributor_name')
            ->join('residents', 'residents.id = batch_distributions.resident_id', 'left')
            ->join('inventory_batches', 'inventory_batches.id = batch_distributions.batch_id', 'left')
            ->join('inventory', 'inventory.id = inventory_batches.inventory_id', 'left')
            ->join('users', 'users.id = batch_distributions.distributor_id', 'left')
            ->orderBy('batch_distributions.distribution_date', 'DESC')
            ->limit(100)
            ->findAll();
        
        $data['logs'] = $logs;
        
        return view('reports/export_view', $data);
    }

        /**
     * Barangay Summary Report - Complete analytics dashboard
     * Shows statistics filtered by selected barangay
     */
    public function barangarySummary()
    {
        $userRole = session()->get('role');
        $userBarangay = session()->get('barangay');
        
        // Load models
        $residentModel = new ResidentModel();
        $familyMemberModel = new \App\Models\FamilyMemberModel();
        
        // Get selected barangay from query parameter
        $selectedBarangay = $this->request->getGet('barangay');
        $selectedStreet = $this->request->getGet('street');
        
        // Build base query
        $query = $residentModel;
        
        // Apply role-based filtering
        if ($userRole == 'barangay' && $userBarangay) {
            $selectedBarangay = $userBarangay;
            $query = $query->where('barangay', $userBarangay);
        }
        
// Apply barangay filter
if ($selectedBarangay && !empty($selectedBarangay)) {
    $query = $query->where('barangay', $selectedBarangay);
}

// Apply street filter
if ($selectedStreet && !empty($selectedStreet)) {
    $query = $query->where('street', $selectedStreet);
}
        
        // Get all residents with family members
        $residents = $query->findAll();
        
        // Get all unique barangays for filter dropdown
        $barangayQuery = $residentModel->select('barangay')->distinct();
        if ($userRole == 'barangay' && $userBarangay) {
            $barangayQuery->where('barangay', $userBarangay);
        }
        $allBarangays = array_column($barangayQuery->findAll(), 'barangay');
        sort($allBarangays);

        // Build streets list for selected barangay (for the dropdown)
$streets = [];
if ($selectedBarangay) {
    $streetRows = $residentModel->select('street')
        ->where('barangay', $selectedBarangay)
        ->where('street !=', '')
        ->distinct()
        ->orderBy('street', 'ASC')
        ->findAll();
    $streets = array_filter(array_column($streetRows, 'street'));
}

// Build streets-by-barangay map (for JS dynamic population)
$streetsByBarangay = [];
foreach ($allBarangays as $b) {
    $rows = $residentModel->select('street')
        ->where('barangay', $b)
        ->where('street !=', '')
        ->distinct()
        ->orderBy('street', 'ASC')
        ->findAll();
    $streetsByBarangay[$b] = array_values(array_filter(array_column($rows, 'street')));
}
        
        // If no barangay selected but user is not barangay, use first barangay
        if (empty($selectedBarangay) && !empty($allBarangays) && $userRole != 'barangay') {
            $selectedBarangay = $allBarangays[0];
            // Refetch with barangay filter
            $query = $residentModel->where('barangay', $selectedBarangay);
            $residents = $query->findAll();
        }
        
        // ========== CALCULATE STATISTICS ==========
        
        // Basic counts
        $totalFamilies = count($residents);
        $totalResidents = $totalFamilies; // Start with heads
        
        // Family size calculation
        $totalFamilyMembers = 0;
        
        foreach ($residents as &$resident) {
            $familyMembers = $familyMemberModel->getByResidentId($resident['id']);
            $memberCount = count($familyMembers);
            $totalResidents += $memberCount;
            $totalFamilyMembers += $memberCount;
            $resident['family_members'] = $familyMembers;
            $resident['family_size'] = 1 + $memberCount;
        }
        
        $avgFamilySize = $totalFamilies > 0 ? round($totalResidents / $totalFamilies, 1) : 0;
        
        // Registration stats (today and this month)
        $today = date('Y-m-d');
        $firstDayOfMonth = date('Y-m-01');
        
        $registeredToday = 0;
        $registeredThisMonth = 0;
        
        foreach ($residents as $resident) {
            $regDate = $resident['registration_date'] ?? $resident['created_at'] ?? '';
            if (!empty($regDate)) {
                $regDateOnly = date('Y-m-d', strtotime($regDate));
                if ($regDateOnly == $today) {
                    $registeredToday++;
                }
                if ($regDateOnly >= $firstDayOfMonth) {
                    $registeredThisMonth++;
                }
            }
        }
        
// Gender distribution (all individuals: heads + family members)
$maleCount = 0;
$femaleCount = 0;
$totalGenderCount = 0;

foreach ($residents as $resident) {
    // Count family head
    $sex = strtolower(trim($resident['sex'] ?? ''));
    if ($sex == 'male' || $sex == 'm') {
        $maleCount++;
    } elseif ($sex == 'female' || $sex == 'f') {
        $femaleCount++;
    }

    // Count family members (already loaded in $resident['family_members'])
    foreach ($resident['family_members'] ?? [] as $member) {
        $memberSex = strtolower(trim($member['sex'] ?? ''));
        if ($memberSex == 'male' || $memberSex == 'm') {
            $maleCount++;
        } elseif ($memberSex == 'female' || $memberSex == 'f') {
            $femaleCount++;
        }
    }
}

$totalGenderCount = $maleCount + $femaleCount;
$malePercent   = $totalGenderCount > 0 ? round(($maleCount   / $totalGenderCount) * 100, 1) : 0;
$femalePercent = $totalGenderCount > 0 ? round(($femaleCount / $totalGenderCount) * 100, 1) : 0;
        
        // 4Ps Beneficiary count
        $fourPsCount = 0;
        $nonFourPsCount = 0;
        
        foreach ($residents as $resident) {
            if (!empty($resident['is_4ps_beneficiary']) && $resident['is_4ps_beneficiary'] == 1) {
                $fourPsCount++;
            } else {
                $nonFourPsCount++;
            }
        }
        
        $fourPsPercent = $totalFamilies > 0 ? round(($fourPsCount / $totalFamilies) * 100, 1) : 0;
        
        // Civil Status distribution
        $civilStatus = [
            'single' => 0,
            'married' => 0,
            'widowed' => 0,
            'separated' => 0,
            'other' => 0
        ];
        
        foreach ($residents as $resident) {
            $status = strtolower(trim($resident['civil_status'] ?? ''));
            if ($status == 'single') {
                $civilStatus['single']++;
            } elseif ($status == 'married') {
                $civilStatus['married']++;
            } elseif ($status == 'widowed') {
                $civilStatus['widowed']++;
            } elseif ($status == 'separated') {
                $civilStatus['separated']++;
            } else {
                $civilStatus['other']++;
            }
        }
        
        // Age Group distribution
        $ageGroups = [
            '18-30' => 0,
            '31-45' => 0,
            '46-60' => 0,
            'senior' => 0,
            'unknown' => 0
        ];
        
        foreach ($residents as $resident) {
            $age = (int)($resident['age'] ?? 0);
            if ($age >= 18 && $age <= 30) {
                $ageGroups['18-30']++;
            } elseif ($age >= 31 && $age <= 45) {
                $ageGroups['31-45']++;
            } elseif ($age >= 46 && $age <= 60) {
                $ageGroups['46-60']++;
            } elseif ($age > 60) {
                $ageGroups['senior']++;
            } else {
                $ageGroups['unknown']++;
            }
        }
        
        // Income analysis
        $incomeRanges = [
            '₱0-₱5k' => 0,
            '₱5k-₱10k' => 0,
            '₱10k-₱20k' => 0,
            '₱20k+' => 0,
            'unknown' => 0
        ];
        
        $totalIncome = 0;
        $incomeCount = 0;
        
        foreach ($residents as $resident) {
            $income = (float)($resident['monthly_income'] ?? 0);
            if ($income > 0) {
                $totalIncome += $income;
                $incomeCount++;
                
                if ($income <= 5000) {
                    $incomeRanges['₱0-₱5k']++;
                } elseif ($income <= 10000) {
                    $incomeRanges['₱5k-₱10k']++;
                } elseif ($income <= 20000) {
                    $incomeRanges['₱10k-₱20k']++;
                } else {
                    $incomeRanges['₱20k+']++;
                }
            } else {
                $incomeRanges['unknown']++;
            }
        }
        
        $averageIncome = $incomeCount > 0 ? round($totalIncome / $incomeCount, 2) : 0;
        
        // Vulnerable members summary (from family heads)
        $vulnerableStats = [
            'older_persons' => 0,
            'pregnant' => 0,
            'lactating' => 0,
            'pwd' => 0
        ];
        
        foreach ($residents as $resident) {
            $vulnerableStats['older_persons'] += (int)($resident['vulnerable_older_persons'] ?? 0);
            $vulnerableStats['pregnant'] += (int)($resident['vulnerable_pregnant'] ?? 0);
            $vulnerableStats['lactating'] += (int)($resident['vulnerable_lactating'] ?? 0);
            $vulnerableStats['pwd'] += (int)($resident['vulnerable_pwd'] ?? 0);
        }
        
        // Ownership Status
        $ownershipStatus = [
            'owner' => 0,
            'renter' => 0,
            'sharer' => 0,
            'unknown' => 0
        ];
        
        foreach ($residents as $resident) {
            $status = strtolower(trim($resident['ownership_status'] ?? ''));
            if ($status == 'owner') {
                $ownershipStatus['owner']++;
            } elseif ($status == 'renter') {
                $ownershipStatus['renter']++;
            } elseif ($status == 'sharer') {
                $ownershipStatus['sharer']++;
            } else {
                $ownershipStatus['unknown']++;
            }
        }
        
        // Shelter damage summary
        $shelterDamage = [
            'no_damage' => 0,
            'partially_damaged' => 0,
            'totally_damaged' => 0,
            'unknown' => 0
        ];
        
        foreach ($residents as $resident) {
            $damage = strtolower(trim($resident['shelter_damage'] ?? ''));
            if ($damage == 'no damage' || $damage == '') {
                $shelterDamage['no_damage']++;
            } elseif ($damage == 'partially damaged') {
                $shelterDamage['partially_damaged']++;
            } elseif ($damage == 'totally damaged') {
                $shelterDamage['totally_damaged']++;
            } else {
                $shelterDamage['unknown']++;
            }
        }
        
        // Prepare data for charts (JSON)
        $chartData = [
            'gender' => [
                'male' => $maleCount,
                'female' => $femaleCount,
                'male_percent' => $malePercent,
                'female_percent' => $femalePercent
            ],
            'fourPs' => [
                'beneficiary' => $fourPsCount,
                'non_beneficiary' => $nonFourPsCount,
                'beneficiary_percent' => $fourPsPercent
            ],
            'civilStatus' => $civilStatus,
            'ageGroups' => $ageGroups,
            'incomeRanges' => $incomeRanges,
            'ownershipStatus' => $ownershipStatus,
            'shelterDamage' => $shelterDamage
        ];
        
        $data = [
            'selectedBarangay' => $selectedBarangay,
            'allBarangays' => $allBarangays,
            'totalFamilies' => $totalFamilies,
            'totalResidents' => $totalResidents,
            'avgFamilySize' => $avgFamilySize,
            'registeredToday' => $registeredToday,
            'registeredThisMonth' => $registeredThisMonth,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'malePercent' => $malePercent,
            'femalePercent' => $femalePercent,
            'fourPsCount' => $fourPsCount,
            'nonFourPsCount' => $nonFourPsCount,
            'fourPsPercent' => $fourPsPercent,
            'civilStatus' => $civilStatus,
            'ageGroups' => $ageGroups,
            'incomeRanges' => $incomeRanges,
            'averageIncome' => $averageIncome,
            'vulnerableStats' => $vulnerableStats,
            'ownershipStatus' => $ownershipStatus,
            'shelterDamage' => $shelterDamage,
            'chartData' => json_encode($chartData),
            'userRole' => $userRole,
            'totalGenderCount' => $totalGenderCount,
            'selectedStreet'    => $selectedStreet,
'streets'           => $streets,
'streetsByBarangay' => $streetsByBarangay,
            'userBarangay' => $userBarangay
            
        ];
        
        return view('reports/barangay_summary', $data);
    }

/**
 * Print Barangay Summary Report (PDF)
 * GET: /reports/barangay-summary/print?barangay=XXX&street=XXX
 */
public function printBarangaySummary()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Load models
    $residentModel = new ResidentModel();
    $familyMemberModel = new \App\Models\FamilyMemberModel();
    
    // Get selected barangay from query parameter
    $selectedBarangay = $this->request->getGet('barangay');
    $selectedStreet = $this->request->getGet('street');
    
    // Build base query
    $query = $residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $selectedBarangay = $userBarangay;
        $query = $query->where('barangay', $userBarangay);
    }
    
    // Apply barangay filter
    if ($selectedBarangay && !empty($selectedBarangay)) {
        $query = $query->where('barangay', $selectedBarangay);
    }
    
    // Apply street filter
    if ($selectedStreet && !empty($selectedStreet)) {
        $query = $query->where('street', $selectedStreet);
    }
    
    // Get all residents
    $residents = $query->findAll();
    
    if (empty($residents)) {
        return redirect()->back()->with('error', 'No data available for the selected filters');
    }
    
    // Calculate all statistics (same as barangarySummary method)
    $totalFamilies = count($residents);
    $totalResidents = $totalFamilies;
    $totalFamilyMembers = 0;
    
    foreach ($residents as &$resident) {
        $familyMembers = $familyMemberModel->getByResidentId($resident['id']);
        $memberCount = count($familyMembers);
        $totalResidents += $memberCount;
        $totalFamilyMembers += $memberCount;
        $resident['family_members'] = $familyMembers;
        $resident['family_size'] = 1 + $memberCount;
    }
    
    $avgFamilySize = $totalFamilies > 0 ? round($totalResidents / $totalFamilies, 1) : 0;
    
    // Gender distribution
    $maleCount = 0;
    $femaleCount = 0;
    
    foreach ($residents as $resident) {
        $sex = strtolower(trim($resident['sex'] ?? ''));
        if ($sex == 'male' || $sex == 'm') {
            $maleCount++;
        } elseif ($sex == 'female' || $sex == 'f') {
            $femaleCount++;
        }
        
        foreach ($resident['family_members'] ?? [] as $member) {
            $memberSex = strtolower(trim($member['sex'] ?? ''));
            if ($memberSex == 'male' || $memberSex == 'm') {
                $maleCount++;
            } elseif ($memberSex == 'female' || $memberSex == 'f') {
                $femaleCount++;
            }
        }
    }
    
    $totalGenderCount = $maleCount + $femaleCount;
    $malePercent = $totalGenderCount > 0 ? round(($maleCount / $totalGenderCount) * 100, 1) : 0;
    $femalePercent = $totalGenderCount > 0 ? round(($femaleCount / $totalGenderCount) * 100, 1) : 0;
    
    // 4Ps count
    $fourPsCount = 0;
    foreach ($residents as $resident) {
        if (!empty($resident['is_4ps_beneficiary']) && $resident['is_4ps_beneficiary'] == 1) {
            $fourPsCount++;
        }
    }
    $nonFourPsCount = $totalFamilies - $fourPsCount;
    $fourPsPercent = $totalFamilies > 0 ? round(($fourPsCount / $totalFamilies) * 100, 1) : 0;
    
    // Civil Status
    $civilStatus = ['single' => 0, 'married' => 0, 'widowed' => 0, 'separated' => 0, 'other' => 0];
    foreach ($residents as $resident) {
        $status = strtolower(trim($resident['civil_status'] ?? ''));
        if ($status == 'single') $civilStatus['single']++;
        elseif ($status == 'married') $civilStatus['married']++;
        elseif ($status == 'widowed') $civilStatus['widowed']++;
        elseif ($status == 'separated') $civilStatus['separated']++;
        else $civilStatus['other']++;
    }
    
    // Age Groups
    $ageGroups = ['18-30' => 0, '31-45' => 0, '46-60' => 0, 'senior' => 0, 'unknown' => 0];
    foreach ($residents as $resident) {
        $age = (int)($resident['age'] ?? 0);
        if ($age >= 18 && $age <= 30) $ageGroups['18-30']++;
        elseif ($age >= 31 && $age <= 45) $ageGroups['31-45']++;
        elseif ($age >= 46 && $age <= 60) $ageGroups['46-60']++;
        elseif ($age > 60) $ageGroups['senior']++;
        else $ageGroups['unknown']++;
    }
    
    // Income Ranges
    $incomeRanges = ['₱0-₱5k' => 0, '₱5k-₱10k' => 0, '₱10k-₱20k' => 0, '₱20k+' => 0, 'unknown' => 0];
    $totalIncome = 0;
    $incomeCount = 0;
    foreach ($residents as $resident) {
        $income = (float)($resident['monthly_income'] ?? 0);
        if ($income > 0) {
            $totalIncome += $income;
            $incomeCount++;
            if ($income <= 5000) $incomeRanges['₱0-₱5k']++;
            elseif ($income <= 10000) $incomeRanges['₱5k-₱10k']++;
            elseif ($income <= 20000) $incomeRanges['₱10k-₱20k']++;
            else $incomeRanges['₱20k+']++;
        } else {
            $incomeRanges['unknown']++;
        }
    }
    $averageIncome = $incomeCount > 0 ? round($totalIncome / $incomeCount, 2) : 0;
    
    // Vulnerable Stats
    $vulnerableStats = ['older_persons' => 0, 'pregnant' => 0, 'lactating' => 0, 'pwd' => 0];
    foreach ($residents as $resident) {
        $vulnerableStats['older_persons'] += (int)($resident['vulnerable_older_persons'] ?? 0);
        $vulnerableStats['pregnant'] += (int)($resident['vulnerable_pregnant'] ?? 0);
        $vulnerableStats['lactating'] += (int)($resident['vulnerable_lactating'] ?? 0);
        $vulnerableStats['pwd'] += (int)($resident['vulnerable_pwd'] ?? 0);
    }
    
    // Ownership Status
    $ownershipStatus = ['owner' => 0, 'renter' => 0, 'sharer' => 0, 'unknown' => 0];
    foreach ($residents as $resident) {
        $status = strtolower(trim($resident['ownership_status'] ?? ''));
        if ($status == 'owner') $ownershipStatus['owner']++;
        elseif ($status == 'renter') $ownershipStatus['renter']++;
        elseif ($status == 'sharer') $ownershipStatus['sharer']++;
        else $ownershipStatus['unknown']++;
    }
    
    // Shelter Damage
    $shelterDamage = ['no_damage' => 0, 'partially_damaged' => 0, 'totally_damaged' => 0, 'unknown' => 0];
    foreach ($residents as $resident) {
        $damage = strtolower(trim($resident['shelter_damage'] ?? ''));
        if ($damage == 'no damage' || $damage == '') $shelterDamage['no_damage']++;
        elseif ($damage == 'partially damaged') $shelterDamage['partially_damaged']++;
        elseif ($damage == 'totally damaged') $shelterDamage['totally_damaged']++;
        else $shelterDamage['unknown']++;
    }
    
    $data = [
        'selectedBarangay' => $selectedBarangay,
        'selectedStreet' => $selectedStreet,
        'totalFamilies' => $totalFamilies,
        'totalResidents' => $totalResidents,
        'avgFamilySize' => $avgFamilySize,
        'maleCount' => $maleCount,
        'femaleCount' => $femaleCount,
        'malePercent' => $malePercent,
        'femalePercent' => $femalePercent,
        'totalGenderCount' => $totalGenderCount,
        'fourPsCount' => $fourPsCount,
        'nonFourPsCount' => $nonFourPsCount,
        'fourPsPercent' => $fourPsPercent,
        'civilStatus' => $civilStatus,
        'ageGroups' => $ageGroups,
        'incomeRanges' => $incomeRanges,
        'averageIncome' => $averageIncome,
        'vulnerableStats' => $vulnerableStats,
        'ownershipStatus' => $ownershipStatus,
        'shelterDamage' => $shelterDamage,
        'generated_date' => date('Y-m-d H:i:s'),
        'generated_by' => session()->get('username') ?? 'System'
    ];
    
    return view('reports/barangay_summary_print', $data);
}

/**
 * Export Barangay Summary Report as CSV (kept for reference)
 */
public function exportBarangaySummary()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Load models
    $residentModel = new ResidentModel();
    $familyMemberModel = new FamilyMemberModel();
    
    // Get selected barangay from query parameter
    $selectedBarangay = $this->request->getGet('barangay');
    $selectedStreet = $this->request->getGet('street');
    
    // Build base query
    $query = $residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $selectedBarangay = $userBarangay;
        $query = $query->where('barangay', $userBarangay);
    }
    
    // Apply barangay filter
    if ($selectedBarangay && !empty($selectedBarangay)) {
        $query = $query->where('barangay', $selectedBarangay);
    }
    
    // Apply street filter
    if ($selectedStreet && !empty($selectedStreet)) {
        $query = $query->where('street', $selectedStreet);
    }
    
    // Get all residents
    $residents = $query->findAll();
    
    // Set CSV headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="barangay_summary_' . $selectedBarangay . '_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Headers
    fputcsv($output, [
        'Household #',
        'Last Name',
        'First Name',
        'Middle Name',
        'Extension',
        'Barangay',
        'Street',
        'Family Size',
        'Head Photo',
        'Head Signature',
        'Head Thumbmark',
        'Members with Photo',
        'Total Members',
        'Update Status'
    ]);
    
    foreach ($residents as $resident) {
        $familyMembers = $familyMemberModel->getByResidentId($resident['id']);
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
        
        // Determine update status
        $allMembersHavePhotos = ($membersWithPhoto == $memberCount && $memberCount > 0);
        
        if ($headHasPhoto && ($memberCount == 0 || $allMembersHavePhotos)) {
            $status = 'Fully Updated';
        } elseif (!$headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $status = 'Not Updated';
        } elseif ($headHasPhoto && $membersWithPhoto > 0 && !$allMembersHavePhotos) {
            $status = 'Partially Updated';
        } elseif ($headHasPhoto && $membersWithPhoto == 0 && $memberCount > 0) {
            $status = 'Head Only';
        } elseif (!$headHasPhoto && $membersWithPhoto > 0) {
            $status = 'Members Only';
        } else {
            $status = 'Not Updated';
        }
        
        fputcsv($output, [
            $resident['household_no'],
            $resident['last_name'],
            $resident['first_name'],
            $resident['middle_name'] ?? '',
            $resident['name_extension'] ?? '',
            $resident['barangay'],
            $resident['street'] ?? '',
            1 + $memberCount,
            $headHasPhoto ? 'Yes' : 'No',
            $headHasSignature ? 'Yes' : 'No',
            $headHasThumbmark ? 'Yes' : 'No',
            $membersWithPhoto,
            $memberCount,
            $status
        ]);
    }
    
    fclose($output);
    exit;
}

/**
 * Missing Images Report - List all residents without photos
 * GET: /reports/missing-images
 */
public function missingImages()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Load models
    $residentModel = new \App\Models\ResidentModel();
    $familyMemberModel = new \App\Models\FamilyMemberModel();
    
    // Get filter parameters
    $selectedBarangay = $this->request->getGet('barangay');
    $selectedStreet = $this->request->getGet('street');
    $type = $this->request->getGet('type'); // 'head', 'member', or 'all'
    
    // Build query for residents (heads)
    $query = $residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $selectedBarangay = $userBarangay;
        $query = $query->where('barangay', $userBarangay);
    }
    
    // Apply barangay filter
    if ($selectedBarangay && !empty($selectedBarangay)) {
        $query = $query->where('barangay', $selectedBarangay);
    }
    
    // Apply street filter
    if ($selectedStreet && !empty($selectedStreet)) {
        $query = $query->where('street', $selectedStreet);
    }
    
    // Get all residents
    $residents = $query->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC')->findAll();
    
    // Prepare data arrays
    $missingHeadPhotos = [];
    $missingMemberPhotos = [];
    
    foreach ($residents as $resident) {
        // Check if head has photo
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        
        if (!$headHasPhoto && ($type == 'head' || $type == 'all' || empty($type))) {
            $missingHeadPhotos[] = [
                'type' => 'Head of Family',
                'household_no' => $resident['household_no'],
                'last_name' => $resident['last_name'],
                'first_name' => $resident['first_name'],
                'middle_name' => $resident['middle_name'] ?? '',
                'barangay' => $resident['barangay'],
                'street' => $resident['street'] ?? '',
                'contact_number' => $resident['contact_number'] ?? '',
                'resident_id' => $resident['id'],
                'member_id' => null,
                'member_name' => null
            ];
        }
        
        // Get family members and check their photos
        $familyMembers = $familyMemberModel->getByResidentId($resident['id']);
        
        foreach ($familyMembers as $member) {
            $memberHasPhoto = !empty($member['photo']) && file_exists(FCPATH . $member['photo']);
            
            if (!$memberHasPhoto && ($type == 'member' || $type == 'all' || empty($type))) {
                $missingMemberPhotos[] = [
                    'type' => 'Family Member',
                    'household_no' => $resident['household_no'],
                    'last_name' => $resident['last_name'],
                    'first_name' => $resident['first_name'],
                    'barangay' => $resident['barangay'],
                    'street' => $resident['street'] ?? '',
                    'contact_number' => $resident['contact_number'] ?? '',
                    'resident_id' => $resident['id'],
                    'member_id' => $member['id'],
                    'member_name' => $member['name'],
                    'relation' => $member['relation']
                ];
            }
        }
    }
    
    // Get unique barangays for filter dropdown
    $barangayQuery = $residentModel->select('barangay')->distinct();
    if ($userRole == 'barangay' && $userBarangay) {
        $barangayQuery->where('barangay', $userBarangay);
    }
    $barangays = array_column($barangayQuery->findAll(), 'barangay');
    sort($barangays);
    
    // Get streets by barangay for dynamic loading
    $streetsByBarangay = [];
    $streetQuery = $residentModel->select('barangay, street')->distinct()
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
    if ($selectedBarangay && isset($streetsByBarangay[$selectedBarangay])) {
        $streets = $streetsByBarangay[$selectedBarangay];
        sort($streets);
    }
    
    $data = [
        'missingHeadPhotos' => $missingHeadPhotos,
        'missingMemberPhotos' => $missingMemberPhotos,
        'totalHeadsMissing' => count($missingHeadPhotos),
        'totalMembersMissing' => count($missingMemberPhotos),
        'totalMissing' => count($missingHeadPhotos) + count($missingMemberPhotos),
        'barangays' => $barangays,
        'streetsByBarangay' => $streetsByBarangay,
        'streets' => $streets,
        'selectedBarangay' => $selectedBarangay,
        'selectedStreet' => $selectedStreet,
        'selectedType' => $type,
        'userRole' => $userRole,
        'userBarangay' => $userBarangay,
        'generated_date' => date('Y-m-d H:i:s'),
        'generated_by' => session()->get('username') ?? 'System'
    ];
    
    return view('reports/missing_images_report', $data);
}

/**
 * Export Missing Images Report as CSV
 * GET: /reports/missing-images/export
 */
public function exportMissingImages()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Load models
    $residentModel = new \App\Models\ResidentModel();
    $familyMemberModel = new \App\Models\FamilyMemberModel();
    
    // Get filter parameters
    $selectedBarangay = $this->request->getGet('barangay');
    $selectedStreet = $this->request->getGet('street');
    $type = $this->request->getGet('type'); // 'head', 'member', or 'all'
    
    // Build query
    $query = $residentModel;
    
    // Apply role-based filtering
    if ($userRole == 'barangay' && $userBarangay) {
        $selectedBarangay = $userBarangay;
        $query = $query->where('barangay', $userBarangay);
    }
    
    if ($selectedBarangay && !empty($selectedBarangay)) {
        $query = $query->where('barangay', $selectedBarangay);
    }
    
    if ($selectedStreet && !empty($selectedStreet)) {
        $query = $query->where('street', $selectedStreet);
    }
    
    $residents = $query->orderBy('last_name', 'ASC')->findAll();
    
    // Prepare CSV data
    $csvData = [];
    
    // Add header
    $csvData[] = ['MISSING IMAGES REPORT'];
    $csvData[] = ['Generated:', date('Y-m-d H:i:s')];
    $csvData[] = ['Barangay Filter:', $selectedBarangay ?: 'All'];
    $csvData[] = ['Street Filter:', $selectedStreet ?: 'All'];
    $csvData[] = ['Type Filter:', $type ?: 'All (Heads & Members)'];
    $csvData[] = [];
    $csvData[] = ['HOUSEHOLDS WITH MISSING HEAD PHOTOS'];
    $csvData[] = [];
    $csvData[] = ['Household #', 'Last Name', 'First Name', 'Middle Name', 'Barangay', 'Street', 'Contact Number'];
    
    // Add heads without photos
    foreach ($residents as $resident) {
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        
        if (!$headHasPhoto && ($type == 'head' || $type == 'all' || empty($type))) {
            $csvData[] = [
                $resident['household_no'],
                $resident['last_name'],
                $resident['first_name'],
                $resident['middle_name'] ?? '',
                $resident['barangay'],
                $resident['street'] ?? '',
                $resident['contact_number'] ?? ''
            ];
        }
    }
    
    $csvData[] = [];
    $csvData[] = ['FAMILY MEMBERS WITHOUT PHOTOS'];
    $csvData[] = [];
    $csvData[] = ['Household #', 'Head Last Name', 'Head First Name', 'Member Name', 'Relation', 'Barangay', 'Street'];
    
    // Add members without photos
    foreach ($residents as $resident) {
        $familyMembers = $familyMemberModel->getByResidentId($resident['id']);
        
        foreach ($familyMembers as $member) {
            $memberHasPhoto = !empty($member['photo']) && file_exists(FCPATH . $member['photo']);
            
            if (!$memberHasPhoto && ($type == 'member' || $type == 'all' || empty($type))) {
                $csvData[] = [
                    $resident['household_no'],
                    $resident['last_name'],
                    $resident['first_name'],
                    $member['name'],
                    $member['relation'],
                    $resident['barangay'],
                    $resident['street'] ?? ''
                ];
            }
        }
    }
    
    // Output CSV
    $filename = 'missing_images_report_' . date('Y-m-d') . '.csv';
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
 * Print Missing Images Report
 * GET: /reports/missing-images/print
 */
public function printMissingImages()
{
    $userRole = session()->get('role');
    $userBarangay = session()->get('barangay');
    
    // Load models
    $residentModel = new \App\Models\ResidentModel();
    $familyMemberModel = new \App\Models\FamilyMemberModel();
    
    // Get filter parameters
    $selectedBarangay = $this->request->getGet('barangay');
    $selectedStreet = $this->request->getGet('street');
    $type = $this->request->getGet('type');
    
    // Build query
    $query = $residentModel;
    
    if ($userRole == 'barangay' && $userBarangay) {
        $selectedBarangay = $userBarangay;
        $query = $query->where('barangay', $userBarangay);
    }
    
    if ($selectedBarangay && !empty($selectedBarangay)) {
        $query = $query->where('barangay', $selectedBarangay);
    }
    
    if ($selectedStreet && !empty($selectedStreet)) {
        $query = $query->where('street', $selectedStreet);
    }
    
    $residents = $query->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC')->findAll();
    
    $missingHeadPhotos = [];
    $missingMemberPhotos = [];
    
    foreach ($residents as $resident) {
        $headHasPhoto = !empty($resident['photo']) && file_exists(FCPATH . $resident['photo']);
        
        if (!$headHasPhoto && ($type == 'head' || $type == 'all' || empty($type))) {
            $missingHeadPhotos[] = $resident;
        }
        
        $familyMembers = $familyMemberModel->getByResidentId($resident['id']);
        
        foreach ($familyMembers as $member) {
            $memberHasPhoto = !empty($member['photo']) && file_exists(FCPATH . $member['photo']);
            
            if (!$memberHasPhoto && ($type == 'member' || $type == 'all' || empty($type))) {
                $missingMemberPhotos[] = [
                    'resident' => $resident,
                    'member' => $member
                ];
            }
        }
    }
    
    $data = [
        'missingHeadPhotos' => $missingHeadPhotos,
        'missingMemberPhotos' => $missingMemberPhotos,
        'totalHeadsMissing' => count($missingHeadPhotos),
        'totalMembersMissing' => count($missingMemberPhotos),
        'totalMissing' => count($missingHeadPhotos) + count($missingMemberPhotos),
        'selectedBarangay' => $selectedBarangay,
        'selectedStreet' => $selectedStreet,
        'selectedType' => $type,
        'generated_date' => date('Y-m-d H:i:s'),
        'generated_by' => session()->get('username') ?? 'System'
    ];
    
    return view('reports/missing_images_print', $data);
}
}