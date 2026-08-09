<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #ffffff;
            color: #1e293b;
            padding: 20px;
            font-size: 11px;
            line-height: 1.4;
        }

        /* Header Section */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #4a7a26;
            padding-bottom: 15px;
        }

        .report-header h1 {
            color: #4a7a26;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-header h3 {
            color: #64748b;
            font-size: 14px;
            font-weight: normal;
        }

        /* Meta Information */
        .meta-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #77BC3F;
        }

        .meta-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .meta-row {
            display: table-row;
        }

        .meta-label {
            display: table-cell;
            font-weight: bold;
            color: #4a7a26;
            width: 120px;
            padding: 5px 10px;
        }

        .meta-value {
            display: table-cell;
            color: #334155;
            padding: 5px 10px;
        }

        /* Summary Cards */
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1 1 200px;
            background: linear-gradient(135deg, #4a7a26, #77BC3F);
            border-radius: 10px;
            padding: 18px 15px;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        .card.secondary {
            background: linear-gradient(135deg, #F58220, #f5a623);
        }

        .card.info {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        /* Section Headers */
        .section-header {
            color: #4a7a26;
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-header::before {
            content: "■";
            color: #77BC3F;
            margin-right: 8px;
            font-size: 14px;
        }

        /* Analytics Grid */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .analytics-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .panel-header {
            background: #f0fdf4;
            padding: 10px 15px;
            border-bottom: 2px solid #77BC3F;
            font-weight: bold;
            color: #4a7a26;
            font-size: 13px;
            text-transform: uppercase;
        }

        .panel-body {
            padding: 15px;
        }

        /* Data Lists */
        .data-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .data-list li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .data-list li:last-child {
            border-bottom: none;
        }

        .list-label {
            font-weight: 600;
            color: #334155;
        }

        .list-value {
            font-weight: 600;
            color: #4a7a26;
            font-family: 'Courier New', monospace;
        }

        /* Progress Bar */
        .progress-container {
            margin: 10px 0;
        }

        .progress-bar {
            height: 10px;
            background: #e2e8f0;
            border-radius: 5px;
            overflow: hidden;
            margin: 5px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4a7a26, #77BC3F);
            border-radius: 5px;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background: #f0fdf4;
            color: #4a7a26;
            border: 1px solid #b8e48a;
        }

        .badge-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-danger {
            background: #fef2f2;
            color: #c0392b;
            border: 1px solid #fecaca;
        }

        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }

        .data-table th {
            background: #f0fdf4;
            color: #1e293b;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 9px;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .data-table tr:hover {
            background: #f0fdf4;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0.03;
            font-size: 60px;
            color: #4a7a26;
            z-index: -1;
            font-weight: bold;
            transform: rotate(-15deg);
        }

        /* Utility Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'Courier New', monospace; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="watermark">RELIEF SYSTEM</div>

<!-- Header -->
<div class="report-header">
    <h1><?= esc($title) ?></h1>
    <h3><?= esc($date_range) ?></h3>
</div>

<!-- Meta Information -->
<div class="meta-container">
    <div class="meta-grid">
        <div class="meta-row">
            <div class="meta-label">Generated:</div>
            <div class="meta-value"><?= esc($generated_date) ?></div>
            <div class="meta-label">Generated By:</div>
            <div class="meta-value"><?= esc($generated_by) ?></div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Date Range:</div>
            <div class="meta-value"><?= esc($start_date) ?> to <?= esc($end_date) ?></div>
            <div class="meta-label">Report Type:</div>
            <div class="meta-value"><?= ucfirst(esc($type)) ?> Report</div>
        </div>
    </div>
</div>

<?php if (isset($summary)): ?>

<!-- Summary Cards -->
<div class="cards-container">
    <?php if ($type == 'distributions'): ?>
        <div class="card">
            <div class="card-value"><?= number_format($summary['total']) ?></div>
            <div class="card-label">Total Distributions</div>
        </div>
        <div class="card secondary">
            <div class="card-value"><?= number_format($summary['unique_households']) ?></div>
            <div class="card-label">Unique Households</div>
        </div>
        <div class="card info">
            <div class="card-value"><?= number_format($summary['items_distributed']) ?></div>
            <div class="card-label">Items Distributed</div>
        </div>
        <div class="card">
            <div class="card-value"><?= $summary['avg_per_day'] ?></div>
            <div class="card-label">Avg per Day</div>
        </div>
    <?php elseif ($type == 'inventory'): ?>
        <div class="card">
            <div class="card-value"><?= number_format($summary['total_batches']) ?></div>
            <div class="card-label">Total Batches</div>
        </div>
        <div class="card secondary">
            <div class="card-value"><?= number_format($summary['total_remaining']) ?></div>
            <div class="card-label">Remaining Items</div>
        </div>
        <div class="card info">
            <div class="card-value"><?= $summary['utilization_rate'] ?>%</div>
            <div class="card-label">Utilization Rate</div>
        </div>
        <div class="card">
            <div class="card-value"><?= number_format($summary['expiring_soon']) ?></div>
            <div class="card-label">Expiring Soon</div>
        </div>
    <?php elseif ($type == 'attendance'): ?>
        <div class="card">
            <div class="card-value"><?= number_format($summary['total']) ?></div>
            <div class="card-label">Total Attendance</div>
        </div>
        <div class="card secondary">
            <div class="card-value"><?= $summary['completion_rate'] ?>%</div>
            <div class="card-label">Completion Rate</div>
        </div>
        <div class="card info">
            <div class="card-value"><?= number_format($summary['checked_in_now']) ?></div>
            <div class="card-label">Currently Checked In</div>
        </div>
        <div class="card">
            <div class="card-value"><?= $summary['avg_daily'] ?></div>
            <div class="card-label">Avg Daily</div>
        </div>
    <?php elseif ($type == 'residents'): ?>
        <div class="card">
            <div class="card-value"><?= number_format($summary['total']) ?></div>
            <div class="card-label">Total Households</div>
        </div>
        <div class="card secondary">
            <div class="card-value"><?= number_format($summary['total_beneficiaries']) ?></div>
            <div class="card-label">Total Beneficiaries</div>
        </div>
        <div class="card info">
            <div class="card-value"><?= $summary['claim_rate'] ?>%</div>
            <div class="card-label">Claim Rate</div>
        </div>
        <div class="card">
            <div class="card-value"><?= $summary['avg_family_size'] ?></div>
            <div class="card-label">Avg Family Size</div>
        </div>
    <?php endif; ?>
</div>

<!-- Analytics Section -->
<div class="section-header">Detailed Analytics</div>

<div class="analytics-grid">
    <?php if ($type == 'distributions'): ?>
        <!-- Items Distributed -->
        <div class="analytics-panel">
            <div class="panel-header">📦 Items Distributed</div>
            <div class="panel-body">
                <ul class="data-list">
                    <?php foreach ($summary['items_breakdown'] as $item => $data): ?>
                    <li>
                        <span class="list-label"><?= esc($item) ?>:</span>
                        <span class="list-value"><?= $data['quantity'] ?> units (<?= $data['count'] ?>x)</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Barangay Breakdown -->
        <div class="analytics-panel">
            <div class="panel-header">📍 By Barangay</div>
            <div class="panel-body">
                <ul class="data-list">
                    <?php 
                    arsort($summary['barangay_breakdown']);
                    foreach ($summary['barangay_breakdown'] as $brgy => $count): 
                    ?>
                    <li>
                        <span class="list-label"><?= esc($brgy) ?>:</span>
                        <span class="list-value"><?= $count ?> households</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Source Breakdown -->
        <div class="analytics-panel">
            <div class="panel-header">🏭 Source Types</div>
            <div class="panel-body">
                <ul class="data-list">
                    <?php foreach ($summary['source_breakdown'] as $source => $count): ?>
                    <li>
                        <span class="list-label"><?= ucfirst(str_replace('_', ' ', esc($source))) ?>:</span>
                        <span class="list-value"><?= $count ?> distributions</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Top Distributors -->
        <div class="analytics-panel">
            <div class="panel-header">👤 Top Distributors</div>
            <div class="panel-body">
                <ul class="data-list">
                    <?php 
                    arsort($summary['distributor_performance']);
                    foreach (array_slice($summary['distributor_performance'], 0, 5) as $dist => $count): 
                    ?>
                    <li>
                        <span class="list-label"><?= esc($dist) ?>:</span>
                        <span class="list-value"><?= $count ?> transactions</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    <?php elseif ($type == 'inventory'): ?>
        <!-- Status Breakdown -->
        <div class="analytics-panel">
            <div class="panel-header">📊 Batch Status</div>
            <div class="panel-body">
                <ul class="data-list">
                    <?php foreach ($summary['status_breakdown'] as $status => $count): ?>
                    <li>
                        <span class="list-label"><?= ucfirst(str_replace('_', ' ', $status)) ?>:</span>
                        <span class="list-value"><?= $count ?> batches</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="analytics-panel">
            <div class="panel-header">⚠️ Low Stock Alert</div>
            <div class="panel-body">
                <?php if (!empty($summary['low_stock_items'])): ?>
                <ul class="data-list">
                    <?php foreach ($summary['low_stock_items'] as $item => $data): ?>
                    <li>
                        <span class="list-label"><?= esc($item) ?>:</span>
                        <span class="list-value"><?= $data['remaining'] ?> units (<?= $data['percent'] ?>%)</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p style="color: #4a7a26; padding: 10px; text-align: center;">✓ No low stock items</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Expiry Status -->
        <div class="analytics-panel">
            <div class="panel-header">📅 Expiry Status</div>
            <div class="panel-body">
                <ul class="data-list">
                    <li>
                        <span class="list-label">Expiring Soon (30 days):</span>
                        <span class="list-value"><span class="badge badge-warning"><?= $summary['expiring_soon'] ?></span></span>
                    </li>
                    <li>
                        <span class="list-label">Expired:</span>
                        <span class="list-value"><span class="badge badge-danger"><?= $summary['expired'] ?></span></span>
                    </li>
                </ul>
                <div class="progress-container">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Utilization Rate</span>
                        <span class="list-value"><?= $summary['utilization_rate'] ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $summary['utilization_rate'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory by Source -->
        <div class="analytics-panel">
            <div class="panel-header">📦 Inventory by Source</div>
            <div class="panel-body">
                <ul class="data-list">
                    <?php foreach ($summary['source_type_breakdown'] as $source => $data): ?>
                    <li>
                        <span class="list-label"><?= ucfirst(str_replace('_', ' ', esc($source))) ?>:</span>
                        <span class="list-value"><?= number_format($data['remaining']) ?> / <?= number_format($data['initial']) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php endif; ?>

<!-- Detailed Records Section -->
<div class="section-header">Detailed Records</div>

<?php if ($type == 'distributions' && !empty($logs)): ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Date/Time</th>
            <th>Household #</th>
            <th>Recipient</th>
            <th>Barangay</th>
            <th>Item</th>
            <th>Batch</th>
            <th>Qty</th>
            <th>Distributor</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= date('M d, Y H:i', strtotime($log['distribution_date'])) ?></td>
            <td class="font-mono"><?= esc($log['household_no'] ?? 'N/A') ?></td>
            <td>
                <?= esc(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                <?php if (!empty($log['family_member_name'])): ?>
                <br><small style="color: #64748b;"><?= esc($log['family_member_name']) ?></small>
                <?php endif; ?>
            </td>
            <td><?= esc($log['barangay'] ?? 'N/A') ?></td>
            <td><?= esc($log['item_name'] ?? 'N/A') ?></td>
            <td class="font-mono"><?= esc($log['batch_number'] ?? 'N/A') ?></td>
            <td class="font-mono text-right"><?= $log['quantity_distributed'] ?? 1 ?> <?= esc($log['unit_type'] ?? '') ?></td>
            <td><?= esc($log['distributor_name'] ?? 'System') ?></td>
            <td><span class="badge badge-success"><?= ucfirst($log['status'] ?? 'completed') ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php elseif ($type == 'inventory' && !empty($batches)): ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Batch #</th>
            <th>Item</th>
            <th>Source</th>
            <th>Initial</th>
            <th>Remaining</th>
            <th>% Left</th>
            <th>Received</th>
            <th>Expiry</th>
            <th>Status</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($batches as $batch): ?>
        <?php 
            $pct = $batch['quantity_initial'] > 0 ? round(($batch['quantity_remaining'] / $batch['quantity_initial']) * 100) : 0;
            $badgeClass = $pct > 50 ? 'badge-success' : ($pct > 20 ? 'badge-warning' : 'badge-danger');
        ?>
        <tr>
            <td class="font-mono"><?= esc($batch['batch_number'] ?? 'N/A') ?></td>
            <td><?= esc($batch['item_name'] ?? 'N/A') ?></td>
            <td><?= ucfirst(str_replace('_', ' ', esc($batch['source_type'] ?? 'Unknown'))) ?></td>
            <td class="font-mono text-right"><?= number_format($batch['quantity_initial'] ?? 0) ?></td>
            <td class="font-mono text-right"><?= number_format($batch['quantity_remaining'] ?? 0) ?></td>
            <td class="text-center"><span class="badge <?= $badgeClass ?>"><?= $pct ?>%</span></td>
            <td><?= isset($batch['date_received']) ? date('M d, Y', strtotime($batch['date_received'])) : 'N/A' ?></td>
            <td><?= isset($batch['expiry_date']) ? date('M d, Y', strtotime($batch['expiry_date'])) : 'N/A' ?></td>
            <td><span class="badge badge-info"><?= ucfirst(str_replace('_', ' ', $batch['status'] ?? 'unknown')) ?></span></td>
            <td><?= esc($batch['assigned_to'] ?? $batch['barangay'] ?? 'Main Warehouse') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php elseif ($type == 'attendance' && !empty($attendance)): ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Household #</th>
            <th>Name</th>
            <th>Barangay</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Purpose</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($attendance as $a): ?>
        <tr>
            <td><?= date('M d, Y', strtotime($a['attendance_date'])) ?></td>
            <td class="font-mono"><?= esc($a['household_no'] ?? 'N/A') ?></td>
            <td>
                <?= esc(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? '')) ?>
                <?php if (!empty($a['family_member_name'])): ?>
                <br><small style="color: #64748b;"><?= esc($a['family_member_name']) ?></small>
                <?php endif; ?>
            </td>
            <td><?= esc($a['barangay'] ?? 'N/A') ?></td>
            <td><?= date('h:i A', strtotime($a['check_in_time'])) ?></td>
            <td><?= isset($a['check_out_time']) ? date('h:i A', strtotime($a['check_out_time'])) : '—' ?></td>
            <td><?= esc($a['purpose'] ?? 'N/A') ?></td>
            <td>
                <?php if (empty($a['check_out_time'])): ?>
                <span class="badge badge-success">Checked In</span>
                <?php else: ?>
                <span class="badge badge-info">Completed</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php elseif ($type == 'residents' && !empty($residents)): ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Household #</th>
            <th>Head of Family</th>
            <th>Barangay</th>
            <th>Contact</th>
            <th>Family Size</th>
            <th>Status</th>
            <th>Registered</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($residents as $r): ?>
        <tr>
            <td class="font-mono"><?= esc($r['household_no'] ?? 'N/A') ?></td>
            <td><?= esc(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')) ?></td>
            <td><?= esc($r['barangay'] ?? 'N/A') ?></td>
            <td><?= esc($r['contact_number'] ?? 'N/A') ?></td>
            <td class="text-center"><?= ($r['family_members_count'] ?? 0) + 1 ?></td>
            <td class="text-center">
                <span class="badge <?= ($r['status'] ?? '') == 'claimed' ? 'badge-success' : 'badge-warning' ?>">
                    <?= ucfirst($r['status'] ?? 'pending') ?>
                </span>
            </td>
            <td><?= isset($r['created_at']) ? date('M d, Y', strtotime($r['created_at'])) : 'N/A' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>
<div style="text-align: center; padding: 50px; color: #64748b; border: 1px dashed #e2e8f0; border-radius: 8px;">
    <p style="font-size: 14px; margin-bottom: 10px;">📊 No records found</p>
    <p>No data available for the selected date range and criteria.</p>
</div>
<?php endif; ?>

<!-- Footer -->
<div class="report-footer">
    <p>This report was generated automatically by the Relief System. For official use only.</p>
    <p>Page 1 of 1 • Generated on <?= date('F d, Y \a\t h:i A') ?></p>
</div>

</body>
</html>