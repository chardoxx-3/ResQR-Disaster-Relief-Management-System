<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FACED Export – <?= date('Y-m-d') ?></title>
<style>
  /* ── Reset & base ── */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 7.5pt;
    background: #fff;
    color: #000;
  }

  /* ── Print page setup: landscape Legal (long bond paper) ── */
  @page {
    size: 355.6mm 215.9mm;
    margin: 5mm 5mm 5mm 5mm;
  }
  @media print {
    html, body { margin: 0; padding: 0; }
    .no-print { display: none !important; }
    .page-pair {
      page-break-before: always;
      page-break-after: always;
      page-break-inside: avoid;
      break-before: page;
      break-after: page;
      break-inside: avoid;
      padding-top: 0 !important;
      margin: 0 !important;
    }
    .page-pair:first-child { page-break-before: avoid; break-before: avoid; }
  }

  /* ── Screen preview toolbar ── */
  .no-print {
    position: fixed; top: 0; left: 0; right: 0; z-index: 999;
    background: #1e293b; color: #fff;
    padding: 8px 16px;
    display: flex; align-items: center; gap: 12px;
    font-family: Arial, sans-serif; font-size: 12px;
  }
  .no-print button {
    background: #3b82f6; color: #fff; border: none;
    padding: 5px 14px; border-radius: 5px; cursor: pointer; font-size: 12px;
  }
  .no-print button:hover { background: #2563eb; }
  .no-print .count { margin-left: auto; opacity: .7; }

  /* ── Page pair wrapper (two cards side by side, fits exactly in Legal landscape) ── */
  .page-pair {
    display: flex;
    gap: 3mm;
    /* Legal landscape = 355.6mm wide, 215.9mm tall. Minus 5mm margins each side */
    width: 345mm;
    height: 205mm;   /* fixed height so nothing overflows */
    margin: 0 auto;
    padding-top: 12mm; /* space for toolbar on screen only */
    overflow: hidden;
  }

  /* ── Single FACED card ── */
  .card {
    flex: 1;
    border: 1.5px solid #000;
    padding: 2mm;
    font-size: 6.5pt;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  /* ── Card header ── */
  .card-header {
    display: flex;
    align-items: center;
    gap: 3mm;
    border-bottom: 1.5px solid #000;
    padding-bottom: 1mm;
    margin-bottom: 1mm;
  }
  .card-header .logo-placeholder {
    width: 14mm; height: 14mm;
    border: 1px solid #aaa;
    display: flex; align-items: center; justify-content: center;
    font-size: 5pt; color: #666; text-align: center; flex-shrink: 0;
  }
  .card-header .agency-info {
    flex: 1; text-align: center; line-height: 1.3;
  }
  .card-header .agency-info .republic { font-size: 6.5pt; }
  .card-header .agency-info .dept { font-weight: bold; font-size: 7pt; }
  .card-header .agency-info .form-title {
    font-weight: bold; font-size: 7.5pt;
    text-transform: uppercase;
    border: 1.5px solid #000; padding: 1mm 2mm; margin-top: 1mm;
    display: inline-block;
  }
  .card-header .copy-box {
    border: 1px solid #000; padding: 1mm; font-size: 5.5pt;
    text-align: center; width: 22mm; flex-shrink: 0; line-height: 1.4;
  }
  .card-header .copy-box .not-for-sale {
    font-weight: bold; font-size: 5pt;
    border: 1px solid #000; padding: 0.5mm; margin-bottom: 1mm;
  }
  .card-header .copy-box .copy-label {
    background: #000; color: #fff; font-weight: bold;
    padding: 0.5mm; font-size: 6pt;
  }

  /* ── Section headers ── */
  .section-header {
    background: #000; color: #fff;
    font-weight: bold; font-size: 6.5pt;
    text-align: center; padding: 0.5mm;
    text-transform: uppercase; letter-spacing: .3px;
    margin: 1mm 0 0;
  }

  /* ── Field grid rows ── */
  .fields-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    border: 1px solid #000;
    border-bottom: none;
  }
  .field-cell {
    border-right: 1px solid #000;
    border-bottom: 1px solid #000;
    padding: 0.3mm 1.5mm;
    min-height: 5mm;
  }
  .field-cell:last-child { border-right: none; }
  .field-cell.span2 { grid-column: span 2; }
  .field-cell.span3 { grid-column: span 3; }
  .field-cell.span4 { grid-column: span 4; }
  .field-label {
    font-size: 5pt; color: #333; line-height: 1;
    margin-bottom: 0.5mm;
  }
  .field-value {
    font-size: 7pt; font-weight: bold; line-height: 1.2;
    text-transform: uppercase;
    border-bottom: 0.5px solid #999;
    min-height: 4mm;
    padding-bottom: 0.5mm;
  }

  /* ── Head of family grid ── */
  .head-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border: 1px solid #000;
    border-top: none;
    border-bottom: none;
  }
  .head-cell {
    border-right: 1px solid #000;
    border-bottom: 1px solid #000;
    padding: 0.3mm 1.5mm;
    min-height: 5mm;
  }
  .head-cell:last-child { border-right: none; }
  .head-cell.span2 { grid-column: span 2; }
  .head-cell.span3 { grid-column: span 3; }
  .head-cell.span4 { grid-column: span 4; }

  /* ── Family members table ── */
  .family-section-label {
    font-size: 5.5pt; font-weight: bold; text-align: center;
    background: #e0e0e0; padding: 0.5mm;
    border: 1px solid #000; border-top: none; border-bottom: none;
    text-transform: uppercase;
  }
  .fam-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
    border-top: none;
  }
  .fam-table th {
    background: #f0f0f0;
    font-size: 5pt; font-weight: bold;
    border: 0.5px solid #000; padding: 0.5mm 1mm;
    text-align: center; text-transform: uppercase;
    line-height: 1.2;
  }
  .fam-table td {
    font-size: 6pt; border: 0.5px solid #000;
    padding: 0.5mm 1mm; text-align: center;
    text-transform: uppercase; min-height: 4.5mm;
    line-height: 1.2;
  }
  .fam-table td.name-col { text-align: left; }
  /* blank rows */
  .fam-table tr.blank-row td { min-height: 4.5mm; height: 4.5mm; }

  /* ── Vulnerable + Ownership ── */
  .bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border: 1px solid #000;
    border-top: none;
  }
  .bottom-cell {
    padding: 1mm;
    border-right: 1px solid #000;
  }
  .bottom-cell:last-child { border-right: none; }
  .bottom-cell-label {
    font-size: 5pt; font-weight: bold; text-transform: uppercase;
    margin-bottom: 1mm;
  }
  .vul-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5mm;
  }
  .vul-item { font-size: 5.5pt; display: flex; gap: 2mm; }
  .vul-count { font-weight: bold; font-size: 7pt; border-bottom: 1px solid #000; min-width: 6mm; text-align: center; }

  /* ── Ownership ── */
  .own-row { display: flex; gap: 4mm; font-size: 6pt; }
  .own-box { display: flex; align-items: center; gap: 1.5mm; }
  .checkbox-sq {
    width: 3mm; height: 3mm; border: 1px solid #000;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 7pt; font-weight: bold; flex-shrink: 0;
  }

  /* ── Signatures section ── */
  .sig-section {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 2mm;
    border: 1px solid #000;
    border-top: none;
    padding: 1mm;
  }
  .sig-block { text-align: center; }
  .sig-line { border-bottom: 1px solid #000; margin-bottom: 0.5mm; min-height: 8mm; }
  .sig-label { font-size: 5pt; color: #333; }

  /* ── Data privacy footer ── */
  .privacy-bar {
    border: 1px solid #000;
    border-top: none;
    padding: 1mm 1.5mm;
    font-size: 5pt;
    line-height: 1.4;
  }
  .privacy-bar strong { font-size: 5.5pt; }

  /* ── Shelter damage section (right side of bottom) ── */
  .shelter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5mm;
  }
  .shelter-item { font-size: 5.5pt; display: flex; align-items: center; gap: 1.5mm; }

</style>
</head>
<body>

<!-- Toolbar (screen only) -->
<div class="no-print">
  <span>📋 DSWD FACED Card Export</span>
  <button onclick="window.print()">🖨️ Print / Save as PDF</button>
  <span class="count">
    <?php
      $total = count($residents);
      $pairs = ceil($total / 2);
    ?>
    <?= $total ?> record(s) &nbsp;|&nbsp; <?= $pairs ?> page(s)
  </span>
</div>

<?php
// Helper: safe value
function fv($val, $fallback = '') {
    return !empty($val) ? htmlspecialchars($val) : $fallback;
}

// Helper: checkbox
function cbx($checked) {
    return $checked ? '&#10003;' : '&nbsp;';
}

// Helper: format date
function fdate($d) {
    if (empty($d)) return '';
    try {
        return date('m-d-Y', strtotime($d));
    } catch(Exception $e) { return $d; }
}

// Chunk into pairs of 2
$chunks = array_chunk($residents, 2);

foreach ($chunks as $chunkIndex => $pair):
    // Pad to always have 2
    while (count($pair) < 2) $pair[] = null;
?>
<div class="page-pair">

<?php foreach ($pair as $colIndex => $r):
  $isBlank = ($r === null);
  $fam     = (!$isBlank && !empty($r['family_members'])) ? $r['family_members'] : [];
  $maxRows = 4; // visible family rows — keep compact to fit A4
  $filledRows = count($fam);
  $blankRows  = max(0, $maxRows - $filledRows);

  // Ownership flags
  $isOwner  = (!$isBlank && $r['ownership_status'] === 'Owner');
  $isRenter = (!$isBlank && $r['ownership_status'] === 'Renter');
  $isSharer = (!$isBlank && $r['ownership_status'] === 'Sharer');

  // Shelter damage
  $isPartial = (!$isBlank && !empty($r['shelter_damage']) && $r['shelter_damage'] === 'Partially Damaged');
  $isTotal   = (!$isBlank && !empty($r['shelter_damage']) && $r['shelter_damage'] === 'Totally Damaged');

  // Copy label: first card = Beneficiary's Copy, second = Social Worker's Copy
  $copyLabel = ($colIndex === 0) ? "BENEFICIARY'S COPY" : "SOCIAL WORKER'S COPY";
  $officialLabel = ($colIndex === 0) ? "OFFICIAL USE ONLY" : "OFFICIAL USE ONLY";
?>
<div class="card">

  <!-- ── HEADER ── -->
  <div class="card-header">
    <div class="logo-placeholder">DSWD<br>Logo</div>
    <div class="agency-info">
      <div class="republic">Republic of the Philippines</div>
      <div class="dept">Department of Social Welfare and Development</div>
      <div class="form-title">Family Assistance Card in Emergencies and Disasters (FACED)</div>
    </div>
    <div class="copy-box">
      <div class="not-for-sale">THIS CARD IS NOT FOR SALE</div>
      <div class="copy-label"><?= $copyLabel ?></div>
      <div style="font-size:5pt;margin-top:1mm;"><?= $officialLabel ?></div>
      <div style="font-size:5pt;">SERIAL NUMBER: <strong><?= $isBlank ? '' : fv($r['household_no']) ?></strong></div>
    </div>
  </div>

  <!-- ── LOCATION SECTION ── -->
  <div class="section-header">Location of the Affected Family</div>
  <div class="fields-grid">
    <div class="field-cell">
      <div class="field-label">1. REGION</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['region']) ?></div>
    </div>
    <div class="field-cell span2">
      <div class="field-label">4. CITY/MUNICIPALITY</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['city_municipality']) ?></div>
    </div>
    <div class="field-cell">
      <div class="field-label">6. EVACUATION CENTER/SITE</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['evacuation_center']) ?></div>
    </div>
    <div class="field-cell">
      <div class="field-label">2. PROVINCE</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['province']) ?></div>
    </div>
    <div class="field-cell span2">
      <div class="field-label">5. BARANGAY</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['barangay']) ?></div>
    </div>
    <div class="field-cell">
      <div class="field-label">3. DISTRICT</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['district']) ?></div>
    </div>
  </div>

  <!-- ── HEAD OF FAMILY ── -->
  <div class="section-header">Head of the Family</div>
  <div class="head-grid">
    <div class="head-cell span2">
      <div class="field-label">7. LAST NAME</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['last_name']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">15. CIVIL STATUS</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['civil_status']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">16. MOTHER'S MAIDEN NAME</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['mother_maiden_name']) ?></div>
    </div>
    <div class="head-cell span2">
      <div class="field-label">8. FIRST NAME</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['first_name']) ?></div>
    </div>
    <div class="head-cell span2">
      <div class="field-label">17. RELIGION</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['religion']) ?></div>
    </div>
    <div class="head-cell span2">
      <div class="field-label">9. MIDDLE NAME</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['middle_name']) ?></div>
    </div>
    <div class="head-cell span2">
      <div class="field-label">18. OCCUPATION</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['occupation']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">10. NAME EXT.</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['name_extension']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">11. BIRTHDATE</div>
      <div class="field-value"><?= $isBlank ? '' : fdate($r['birthdate']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">19. MONTHLY FAMILY NET INCOME</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['monthly_income']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">20. ID CARD PRESENTED</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['id_card_presented']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">12. AGE</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['age']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">13. BIRTHPLACE</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['birthplace']) ?></div>
    </div>
    <div class="head-cell span2">
      <div class="field-label">21. ID CARD NUMBER</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['id_card_number']) ?></div>
    </div>
    <div class="head-cell">
      <div class="field-label">14. SEX</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['sex']) ?></div>
    </div>
    <div class="head-cell span3">
      <div class="field-label">22. CONTACT NUMBER &nbsp;&nbsp; PRIMARY &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ALTERNATE</div>
      <div class="field-value"><?= $isBlank ? '' : fv($r['contact_number']) ?> &nbsp;&nbsp;&nbsp;&nbsp; <?= $isBlank ? '' : fv($r['alternate_number']) ?></div>
    </div>
    <!-- Permanent Address -->
    <div class="head-cell span4">
      <div class="field-label">23. PERMANENT ADDRESS &nbsp; House/Door No. &nbsp;&nbsp; Street &nbsp;&nbsp; Subdivision/Village &nbsp;&nbsp; Barangay &nbsp;&nbsp; City/Municipality &nbsp;&nbsp; Province &nbsp;&nbsp; Zip Code</div>
      <div class="field-value">
        <?= $isBlank ? '' : implode(' ', array_filter([
            fv($r['house_no']), fv($r['street']), fv($r['subdivision']),
            fv($r['permanent_barangay']), fv($r['permanent_city']),
            fv($r['permanent_province']), fv($r['zip_code'])
        ])) ?>
      </div>
    </div>
    <!-- Others -->
    <div class="head-cell span4" style="display:flex; align-items:center; gap:6mm; min-height:6mm;">
      <div style="font-size:5.5pt; font-weight:bold;">24. OTHERS:</div>
      <div class="own-box">
        <div class="checkbox-sq"><?= $isBlank ? '&nbsp;' : cbx(!empty($r['is_4ps_beneficiary'])) ?></div>
        <span style="font-size:5.5pt;">4Ps Beneficiary</span>
      </div>
      <div class="own-box">
        <div class="checkbox-sq" style="width:auto;min-width:3mm;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <span style="font-size:5.5pt;">IP- Type of Ethnicity: <?= $isBlank ? '' : fv($r['ip_ethnicity']) ?></span>
      </div>
    </div>
  </div>

  <!-- ── FAMILY MEMBERS ── -->
  <div class="family-section-label">25. Family Information</div>
  <table class="fam-table">
    <thead>
      <tr>
        <th style="width:26%">FAMILY MEMBERS<br><span style="font-weight:normal;">(Last Name, First Name)</span></th>
        <th style="width:14%">RELATION TO<br>FAMILY HEAD</th>
        <th style="width:11%">BIRTHDATE</th>
        <th style="width:5%">AGE</th>
        <th style="width:5%">SEX</th>
        <th style="width:17%">HIGHEST EDUCATIONAL<br>ATTAINMENT</th>
        <th style="width:12%">OCCUPATION</th>
        <th style="width:10%">REMARKS</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($fam as $m): ?>
      <tr>
        <td class="name-col"><?= fv($m['name']) ?></td>
        <td><?= fv($m['relation']) ?></td>
        <td><?= fdate($m['birthdate'] ?? '') ?></td>
        <td><?= fv($m['age'] ?? '') ?></td>
        <td><?= fv($m['sex'] ?? '') ?></td>
        <td><?= fv($m['education'] ?? '') ?></td>
        <td><?= fv($m['occupation'] ?? '') ?></td>
        <td><?= fv($m['remarks'] ?? '') ?></td>
      </tr>
      <?php endforeach; ?>
      <?php for ($i = 0; $i < $blankRows; $i++): ?>
      <tr class="blank-row">
        <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
      </tr>
      <?php endfor; ?>
    </tbody>
  </table>

  <!-- ── VULNERABLE + OWNERSHIP + SHELTER ── -->
  <div class="bottom-grid">
    <div class="bottom-cell">
      <div class="bottom-cell-label">26. No. of Vulnerable Family Members:</div>
      <div class="vul-grid">
        <div class="vul-item">
          <div class="vul-count"><?= $isBlank ? '' : fv($r['vulnerable_older_persons'] ?? '0') ?></div>
          <span>No. of Older Persons</span>
        </div>
        <div class="vul-item">
          <div class="vul-count"><?= $isBlank ? '' : fv($r['vulnerable_pregnant'] ?? '0') ?></div>
          <span>No. of Pregnant Women</span>
        </div>
        <div class="vul-item">
          <div class="vul-count"><?= $isBlank ? '' : fv($r['vulnerable_lactating'] ?? '0') ?></div>
          <span>No. of Lactating Women</span>
        </div>
        <div class="vul-item">
          <div class="vul-count"><?= $isBlank ? '' : fv($r['vulnerable_pwd'] ?? '0') ?></div>
          <span>No. of PWDs due to Medical Conditions</span>
        </div>
      </div>
    </div>
    <div class="bottom-cell">
      <div class="bottom-cell-label">27. House Ownership</div>
      <div class="own-row" style="margin-bottom:2mm;">
        <div class="own-box"><div class="checkbox-sq"><?= $isBlank ? '&nbsp;' : cbx($isOwner) ?></div> Owner</div>
        <div class="own-box"><div class="checkbox-sq"><?= $isBlank ? '&nbsp;' : cbx($isRenter) ?></div> Renter</div>
        <div class="own-box"><div class="checkbox-sq"><?= $isBlank ? '&nbsp;' : cbx($isSharer) ?></div> Sharer</div>
      </div>
      <div class="bottom-cell-label">28. Shelter Damage Classification</div>
      <div class="shelter-grid">
        <div class="shelter-item"><div class="checkbox-sq"><?= $isBlank ? '&nbsp;' : cbx($isPartial) ?></div> Partially Damaged</div>
        <div class="shelter-item"><div class="checkbox-sq"><?= $isBlank ? '&nbsp;' : cbx($isTotal) ?></div> Totally Damaged</div>
      </div>
    </div>
  </div>

  <!-- ── SIGNATURES ── -->
  <div class="sig-section">
    <div class="sig-block">
      <div class="sig-line" style="min-height:7mm;">
        <?php if (!$isBlank && !empty($r['signature_thumbmark'])): ?>
        <img src="<?= base_url($r['signature_thumbmark']) ?>" style="max-height:6mm;max-width:100%;object-fit:contain;" alt="Sig">
        <?php endif; ?>
      </div>
      <div class="sig-label">Signature/Thumbmark of Family Head</div>
      <div style="margin-top:0.5mm; font-size:5pt; border-top:1px solid #000; padding-top:0.5mm;">
        RIGHT THUMBMARK &nbsp;
        <?php if (!$isBlank && !empty($r['right_thumbmark'])): ?>
        <img src="<?= base_url($r['right_thumbmark']) ?>" style="max-height:5mm;max-width:10mm;" alt="Thumb">
        <?php endif; ?>
      </div>
    </div>
    <div class="sig-block">
      <div class="sig-line" style="min-height:7mm;"></div>
      <div class="sig-label">Date Registered</div>
      <div style="font-size:6.5pt; font-weight:bold; margin-top:0.5mm;">
        <?= $isBlank ? '' : fdate($r['registration_date'] ?? '') ?>
      </div>
    </div>
    <div class="sig-block">
      <div class="sig-line" style="min-height:7mm;">
        <?php if (!$isBlank && !empty($r['barangay_captain_name'])): ?>
        <div style="font-size:6.5pt; font-weight:bold;"><?= fv($r['barangay_captain_name']) ?></div>
        <?php endif; ?>
      </div>
      <div class="sig-label">Name/Signature of Brgy. Captain</div>
      <div style="margin-top:1mm; border-top:1px solid #000; padding-top:0.5mm;">
        <div class="sig-line" style="min-height:8mm;">
          <?php if (!$isBlank && !empty($r['lswdo_name'])): ?>
          <div style="font-size:6.5pt; font-weight:bold;"><?= fv($r['lswdo_name']) ?></div>
          <?php endif; ?>
        </div>
        <div class="sig-label">Name/Signature of LSWDO</div>
      </div>
    </div>
  </div>

  <!-- ── DATA PRIVACY ── -->
  <div class="privacy-bar">
    <strong>29. DATA PRIVACY DECLARATION</strong><br>
    All data and information indicated herein shall be used for identification purposes for the implementation of disaster risk reduction and management
    (DRRM) programs, projects and activities and its disclosure shall be in compliance to Republic Act 10173 (Data Privacy Act of 2012).
  </div>

</div><!-- /.card -->
<?php endforeach; ?>

</div><!-- /.page-pair -->
<?php endforeach; ?>

</body>
</html>