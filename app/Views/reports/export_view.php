<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Relief Distribution Report' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; font-size: 12px; font-family: Arial, sans-serif; }
        .report-header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .report-title { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        @media print { .no-print { display: none; } }
        .footer { margin-top: 50px; }
        .signature-line { border-top: 1px solid #000; width: 200px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f0f0f0; }
    </style>
</head>
<body onload="window.print()">
    <div class="container my-5">
        <div class="report-header text-center">
            <h3 class="fw-bold"><?= $title ?? 'Disaster Relief Operation Report' ?></h3>
            <p class="m-0 text-muted">
                Date Range: <?= $date_range ?? date('F d, Y') ?><br>
                Generated on: <?= $generated_date ?? date('F d, Y h:i A') ?>
            </p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Resident Name</th>
                    <th>Household #</th>
                    <th>Barangay</th>
                    <th>Item</th>
                    <th>Distributor</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($logs) && !empty($logs)): ?>
                    <?php foreach($logs as $log): ?>
                    <tr>
                        <td><?= date('M d, Y h:i A', strtotime($log['distribution_date'] ?? $log['claimed_at'] ?? '')) ?></td>
                        <td><?= ($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '') ?></td>
                        <td><?= $log['household_no'] ?? '' ?></td>
                        <td><?= $log['barangay'] ?? '' ?></td>
                        <td><?= $log['item_name'] ?? 'Relief Goods' ?></td>
                        <td><?= $log['distributor_name'] ?? 'System' ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No records found for the selected date range</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer mt-5 pt-5 row">
            <div class="col-4 text-center">
                <div class="signature-line mb-1"></div>
                <small>Prepared By</small>
            </div>
            <div class="col-4 text-center">
                <div class="signature-line mb-1"></div>
                <small>Noted By</small>
            </div>
            <div class="col-4 text-center">
                <div class="signature-line mb-1"></div>
                <small>Approved By</small>
            </div>
        </div>
    </div>
</body>
</html>