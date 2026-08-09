<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Summary Report - <?= esc($selectedBarangay) ?></title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            .no-print {
                display: none !important;
            }
            .report-container {
                padding: 0;
                margin: 0;
            }
            .page-break {
                page-break-before: always;
            }
            .page-break-inside-avoid {
                page-break-inside: avoid;
            }
            @page {
                size: portrait;
                margin: 1.5cm;
            }
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Outfit', Arial, sans-serif;
            background: #ffffff;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4a7a26;
            padding-bottom: 15px;
        }
        
        .print-header h1 {
            margin: 0;
            font-size: 20px;
            color: #4a7a26;
        }
        
        .print-header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #334155;
        }
        
        .print-header p {
            margin: 5px 0;
            font-size: 11px;
            color: #64748b;
        }
        
        .report-meta {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        .report-meta table {
            width: 100%;
        }
        
        .report-meta td {
            padding: 3px 5px;
        }
        
        /* KPI Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .kpi-card {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        
        .kpi-label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            color: #4a7a26;
            margin-bottom: 5px;
        }
        
        .kpi-value {
            font-size: 20px;
            font-weight: 800;
            color: #4a7a26;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stats-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .stats-header {
            background: #f1f5f9;
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            font-size: 12px;
        }
        
        .stats-body {
            padding: 12px;
        }
        
        .stats-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }
        
        .stats-row:last-child {
            border-bottom: none;
        }
        
        .stats-label {
            color: #64748b;
        }
        
        .stats-value {
            font-weight: 600;
            color: #1e293b;
        }
        
        .stats-value.highlight {
            color: #4a7a26;
            font-size: 14px;
        }
        
        /* Vulnerable Grid */
        .vulnerable-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        
        .vuln-item {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }
        
        .vuln-number {
            font-size: 18px;
            font-weight: 800;
            color: #c96b10;
        }
        
        .vuln-label {
            font-size: 9px;
            color: #64748b;
        }
        
        /* Bar Chart Simulation */
        .bar-container {
            margin: 10px 0;
        }
        
        .bar-item {
            margin-bottom: 10px;
        }
        
        .bar-label {
            font-size: 10px;
            margin-bottom: 3px;
            display: flex;
            justify-content: space-between;
        }
        
        .bar-bg {
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .bar-fill {
            background: #4a7a26;
            height: 20px;
            border-radius: 4px;
            transition: width 0.3s;
        }
        
        .bar-fill.orange {
            background: #F58220;
        }
        
        .bar-fill.amber {
            background: #f5a623;
        }
        
        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 15px;
        }
        
        .data-table th {
            background: #f1f5f9;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }
        
        .data-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Print Button */
        .print-button-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .print-btn {
            background: #4a7a26;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        
        .print-btn:hover {
            background: #3a5e1e;
        }
        
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
        }
        
        @media (max-width: 768px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="print-button-container no-print">
        <button class="print-btn" onclick="window.print();">
            <i class="fa-solid fa-print"></i> Print / Save as PDF
        </button>
    </div>
    
    <div class="report-container">
        <!-- Header -->
        <div class="print-header">
            <h1>Republic of the Philippines</h1>
            <h2>Department of Social Welfare and Development (DSWD)</h2>
            <h2>Barangay Summary Report</h2>
            <div style="margin-top: 10px;">
                <p><strong>Barangay:</strong> <?= esc($selectedBarangay) ?></p>
                <?php if (!empty($selectedStreet)): ?>
                <p><strong>Street:</strong> <?= esc($selectedStreet) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Report Meta -->
        <div class="report-meta">
            <table>
                <tr>
                    <td><strong>Generated:</strong> <?= esc($generated_date) ?></td>
                    <td><strong>Generated By:</strong> <?= esc($generated_by) ?></td>
                </tr>
            </table>
        </div>
        
        <!-- KPI Cards -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-label">Total Families</div>
                <div class="kpi-value"><?= number_format($totalFamilies) ?></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Total Residents</div>
                <div class="kpi-value"><?= number_format($totalResidents) ?></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Avg Family Size</div>
                <div class="kpi-value"><?= $avgFamilySize ?></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Male</div>
                <div class="kpi-value"><?= number_format($maleCount) ?> (<?= $malePercent ?>%)</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Female</div>
                <div class="kpi-value"><?= number_format($femaleCount) ?> (<?= $femalePercent ?>%)</div>
            </div>
        </div>
        
        <!-- Stats Grid Row 1 -->
        <div class="stats-grid">
            <!-- 4Ps Status -->
            <div class="stats-card">
                <div class="stats-header">4Ps Beneficiary Status</div>
                <div class="stats-body">
                    <div class="stats-row">
                        <span class="stats-label">4Ps Beneficiary</span>
                        <span class="stats-value"><?= number_format($fourPsCount) ?> (<?= $fourPsPercent ?>%)</span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">Non-4Ps</span>
                        <span class="stats-value"><?= number_format($nonFourPsCount) ?> (<?= round(100 - $fourPsPercent, 1) ?>%)</span>
                    </div>
                    <div class="bar-container" style="margin-top: 8px;">
                        <div class="bar-bg">
                            <div class="bar-fill" style="width: <?= $fourPsPercent ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Civil Status -->
            <div class="stats-card">
                <div class="stats-header">Civil Status Distribution</div>
                <div class="stats-body">
                    <div class="stats-row">
                        <span class="stats-label">Single</span>
                        <span class="stats-value"><?= number_format($civilStatus['single']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">Married</span>
                        <span class="stats-value"><?= number_format($civilStatus['married']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">Widowed</span>
                        <span class="stats-value"><?= number_format($civilStatus['widowed']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">Separated</span>
                        <span class="stats-value"><?= number_format($civilStatus['separated']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Grid Row 2 -->
        <div class="stats-grid">
            <!-- Age Groups -->
            <div class="stats-card">
                <div class="stats-header">Age Group (Family Heads)</div>
                <div class="stats-body">
                    <div class="stats-row">
                        <span class="stats-label">18-30 years</span>
                        <span class="stats-value"><?= number_format($ageGroups['18-30']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">31-45 years</span>
                        <span class="stats-value"><?= number_format($ageGroups['31-45']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">46-60 years</span>
                        <span class="stats-value"><?= number_format($ageGroups['46-60']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">Senior (60+)</span>
                        <span class="stats-value"><?= number_format($ageGroups['senior']) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Income -->
            <div class="stats-card">
                <div class="stats-header">Monthly Income Analysis</div>
                <div class="stats-body">
                    <div class="stats-row">
                        <span class="stats-label">₱0 - ₱5,000</span>
                        <span class="stats-value"><?= number_format($incomeRanges['₱0-₱5k']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">₱5,001 - ₱10,000</span>
                        <span class="stats-value"><?= number_format($incomeRanges['₱5k-₱10k']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">₱10,001 - ₱20,000</span>
                        <span class="stats-value"><?= number_format($incomeRanges['₱10k-₱20k']) ?></span>
                    </div>
                    <div class="stats-row">
                        <span class="stats-label">₱20,000+</span>
                        <span class="stats-value"><?= number_format($incomeRanges['₱20k+']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vulnerable Members -->
        <div class="stats-card" style="margin-bottom: 25px;">
            <div class="stats-header">Vulnerable Members Summary</div>
            <div class="stats-body">
                <div class="vulnerable-grid">
                    <div class="vuln-item">
                        <div class="vuln-number"><?= number_format($vulnerableStats['older_persons']) ?></div>
                        <div class="vuln-label">Older Persons</div>
                    </div>
                    <div class="vuln-item">
                        <div class="vuln-number"><?= number_format($vulnerableStats['pregnant']) ?></div>
                        <div class="vuln-label">Pregnant Women</div>
                    </div>
                    <div class="vuln-item">
                        <div class="vuln-number"><?= number_format($vulnerableStats['lactating']) ?></div>
                        <div class="vuln-label">Lactating Mothers</div>
                    </div>
                    <div class="vuln-item">
                        <div class="vuln-number"><?= number_format($vulnerableStats['pwd']) ?></div>
                        <div class="vuln-label">PWD</div>
                    </div>
                </div>
            </div>
        </div>
    
        
        <!-- Footer -->
        <div class="footer">
            This report is system-generated from the DSWD Relief Management System.<br>
            For verification and concerns, please contact the DSWD office.
        </div>
    </div>
</body>
</html>