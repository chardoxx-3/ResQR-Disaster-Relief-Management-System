<!DOCTYPE html>
<html>
<head>
    <title>Missing Images Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', Arial, sans-serif; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #3a6b1a; }
        .header h1 { font-size: 18px; color: #3a6b1a; margin-bottom: 5px; }
        .header p { font-size: 10px; color: #666; }
        .summary { margin-bottom: 20px; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .summary-table td { padding: 5px; border: 1px solid #ddd; }
        .section-title { font-size: 14px; font-weight: bold; margin: 20px 0 10px; background: #f0f0f0; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f5f5f5; font-weight: bold; font-size: 10px; }
        td { font-size: 10px; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        .badge { background: #fffbeb; color: #d4920a; padding: 2px 6px; border-radius: 4px; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MISSING IMAGES REPORT</h1>
        <p>Generated: <?= $generated_date ?> | By: <?= $generated_by ?></p>
        <p>Barangay: <?= $selectedBarangay ?: 'All' ?> | Street: <?= $selectedStreet ?: 'All' ?> | Type: <?= ucfirst($selectedType ?: 'All') ?></p>
    </div>

    <div class="summary">
        <table class="summary-table">
            <tr>
                <td width="33%"><strong>Heads Without Photo:</strong> <?= number_format($totalHeadsMissing) ?></td>
                <td width="33%"><strong>Members Without Photo:</strong> <?= number_format($totalMembersMissing) ?></td>
                <td width="33%"><strong>Total Missing:</strong> <?= number_format($totalMissing) ?></td>
            </tr>
        </table>
    </div>

    <?php if(!empty($missingHeadPhotos)): ?>
    <div class="section-title">FAMILY HEADS WITHOUT PHOTOS (<?= count($missingHeadPhotos) ?>)</div>
    <table>
        <thead>
            <tr>
                <th>Household #</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Barangay</th>
                <th>Street</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($missingHeadPhotos as $head): ?>
            <tr>
                <td><?= htmlspecialchars($head['household_no']) ?></td>
                <td><?= htmlspecialchars($head['last_name']) ?></td>
                <td><?= htmlspecialchars($head['first_name']) ?></td>
                <td><?= htmlspecialchars($head['middle_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($head['barangay']) ?></td>
                <td><?= htmlspecialchars($head['street'] ?? '—') ?></td>
                <td><?= htmlspecialchars($head['contact_number'] ?? '—') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if(!empty($missingMemberPhotos)): ?>
    <div class="section-title">FAMILY MEMBERS WITHOUT PHOTOS (<?= count($missingMemberPhotos) ?>)</div>
    <table>
        <thead>
            <tr>
                <th>Household #</th>
                <th>Head Name</th>
                <th>Member Name</th>
                <th>Relation</th>
                <th>Barangay</th>
                <th>Street</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($missingMemberPhotos as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['household_no']) ?></td>
                <td><?= htmlspecialchars($item['last_name']) ?>, <?= htmlspecialchars($item['first_name']) ?></td>
                <td><strong><?= htmlspecialchars($item['member_name']) ?></strong></td>
                <td><span class="badge"><?= htmlspecialchars($item['relation'] ?? '—') ?></span></td>
                <td><?= htmlspecialchars($item['barangay']) ?></td>
                <td><?= htmlspecialchars($item['street'] ?? '—') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="footer">
        This report was generated automatically by the Relief System.
    </div>
</body>
</html>