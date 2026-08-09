<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resident List – <?= htmlspecialchars($barangay ?: 'All Barangays') ?> <?= $street ? '/ ' . htmlspecialchars($street) : '' ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep:  #4a7a26;
  --green-mid:   #77BC3F;
  --green-light: #99d15f;
  --orange-mid:  #F58220;
  --orange-deep: #c96b10;
  --bg:          #f8fafc;
  --surface:     #ffffff;
  --surface2:    #f0fdf4;
  --text-1:      #1e293b;
  --text-2:      #334155;
  --text-3:      #64748b;
  --border:      #e2e8f0;
  --radius:      14px;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text-1); padding: 20px; }

/* ── Print header ── */
.print-header {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius) var(--radius) 0 0;
  padding: 16px 20px;
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.print-title h5 { font-size: 1rem; font-weight: 800; color: var(--text-1); }
.print-title p  { font-size: .68rem; color: var(--text-3); margin-top: 3px; }
.print-btn {
  display: inline-flex; align-items: center; gap: 6px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color: #fff; border: none; border-radius: 8px;
  padding: 8px 16px; font-size: .72rem; font-weight: 600;
  cursor: pointer; font-family: 'Outfit', sans-serif;
  box-shadow: 0 4px 12px rgba(26,92,46,.3);
}
.print-btn:hover { opacity: .9; }
.close-btn {
  display: inline-flex; align-items: center; gap: 6px;
  background: transparent; color: var(--text-2);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 8px 14px; font-size: .72rem; font-weight: 600;
  cursor: pointer; font-family: 'Outfit', sans-serif;
}
.close-btn:hover { border-color: var(--orange-mid); color: var(--orange-deep); }

/* ── Table ── */
.table-wrap {
  background: var(--surface);
  border: 1px solid var(--border); border-top: none;
  overflow: hidden;
}
.table-scroll { overflow-x: auto; }
.rt { width: 100%; border-collapse: collapse; font-size: .7rem; }
.rt thead th {
  background: var(--bg);
  padding: 8px 10px; text-align: left;
  font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .55px;
  color: var(--text-3); border-bottom: 1px solid var(--border); white-space: nowrap;
}
.rt thead th:first-child { padding-left: 16px; }
.rt td { padding: 9px 10px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.rt td:first-child { padding-left: 16px; }
.rt tbody tr.main-row:hover { background: var(--surface2); }

.av {
  width: 30px; height: 30px; border-radius: 9px;
  background: linear-gradient(135deg, var(--green-light), var(--green-mid));
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: .7rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
}
.av img { width: 100%; height: 100%; object-fit: cover; }
.resident-chip { display: flex; align-items: center; gap: 8px; }
.res-name { font-weight: 700; color: var(--text-1); font-size: .72rem; }
.res-sub  { font-size: .6rem; color: var(--text-3); }
.hh-badge {
  font-family: 'DM Mono', monospace; font-size: .62rem;
  background: var(--bg); border: 1px solid var(--border);
  border-radius: 5px; padding: 2px 7px; color: var(--text-2);
}
.badge2 {
  display: inline-flex; align-items: center; gap: 3px;
  padding: 2px 7px; border-radius: 20px; font-size: .6rem; font-weight: 600;
}
.badge2.green  { background: #e8f5ee; color: var(--green-deep); }
.badge2.orange { background: #fef0e8; color: var(--orange-deep); }
.badge2.amber  { background: #fff8e8; color: #c08000; }

/* ── Family panel (always expanded) ── */
.family-panel {
  padding: 10px 16px 12px 56px;
  border-left: 3px solid var(--orange-mid);
  background: linear-gradient(90deg, #fff8f4, var(--surface2));
}
.family-panel-title {
  font-size: .58rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px;
  color: var(--orange-deep); margin-bottom: 6px; display: flex; align-items: center; gap: 5px;
}
.fam-table { width: 100%; border-collapse: collapse; font-size: .67rem; }
.fam-table thead th {
  font-size: .58rem; text-transform: uppercase; letter-spacing: .5px;
  color: var(--text-3); font-weight: 700; padding: 4px 10px;
  border-bottom: 1px solid var(--border); background: transparent; text-align: left;
}
.fam-table td {
  padding: 5px 10px; border-bottom: 1px solid rgba(212,230,218,.5);
  color: var(--text-2); vertical-align: middle; text-align: left;
}
.fam-table tbody tr:last-child td { border-bottom: none; }
.member-id-badge {
  font-family: 'DM Mono', monospace; font-size: .6rem;
  background: var(--bg); border: 1px solid var(--border);
  border-radius: 4px; padding: 1px 6px; color: var(--text-2);
}

/* ── Footer ── */
.table-footer {
  background: var(--surface); border: 1px solid var(--border); border-top: none;
  border-radius: 0 0 var(--radius) var(--radius);
  padding: 10px 16px;
  font-size: .68rem; color: var(--text-3);
}
.table-footer strong { color: var(--green-deep); }

@page {
  margin: 10mm;        /* controls the white space — adjust as needed */
  size: A4 landscape;  /* or portrait — remove this line to keep default */
}

/* ── Print styles ── */
@media print {
  body { padding: 0; background: #fff; }
  .no-print { display: none !important; }
  .no-print { display: none !important; }
  .print-header { border-radius: 0; }
  .table-footer { border-radius: 0; }
  .rt td, .rt th { font-size: .62rem; padding: 5px 8px; }
  .family-panel { padding: 6px 12px 8px 40px; }
}
</style>
</head>
<body>

<!-- Header -->
<div class="print-header">
  <div class="print-title">
    <h5>
      <i class="fa-solid fa-users me-2" style="color:var(--green-mid)"></i>
      Resident List
      <?= $barangay ? '— ' . htmlspecialchars($barangay) : '' ?>
      <?= $street   ? ' / ' . htmlspecialchars($street)  : '' ?>
    </h5>
    <p>
      <strong style="color:var(--green-deep)"><?= $totalCount ?></strong> resident(s) &nbsp;·&nbsp;
      Generated: <?= date('F j, Y, g:i a') ?>
    </p>
  </div>
  <div class="no-print" style="display:flex; gap:8px;">
    <button class="close-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
    <button class="print-btn" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
  </div>
</div>

<!-- Table -->
<div class="table-wrap">
  <div class="table-scroll">
    <table class="rt">
      <thead>
        <tr>
          <th>#</th>
          <th>Household #</th>
          <th>Family Head</th>
          <th>Contact</th>
          <th>Barangay / Street</th>
          <th>Family Size</th>
          <th>Vulnerable</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($residents)): ?>
        <tr><td colspan="8" style="text-align:center; padding:40px; color:var(--text-3);">
          <i class="fa-solid fa-users-slash" style="font-size:2rem; display:block; margin-bottom:8px; opacity:.3;"></i>
          No residents found.
        </td></tr>
        <?php else: ?>
        <?php foreach($residents as $i => $r):
          $familyMembers   = $r['family_members'] ?? [];
          $hasFam          = !empty($familyMembers);
          $initials        = strtoupper(substr($r['first_name'] ?? 'R', 0, 1));
          $vulnerableCount = ($r['vulnerable_older_persons']??0)+($r['vulnerable_pregnant']??0)+($r['vulnerable_lactating']??0)+($r['vulnerable_pwd']??0);
          $totalInHousehold = 1 + count($familyMembers);
        ?>
        <!-- Main row -->
        <tr class="main-row">
          <td style="color:var(--text-3); font-size:.62rem;"><?= $i + 1 ?></td>
          <td><span class="hh-badge"><?= htmlspecialchars($r['household_no'] ?? '—') ?></span></td>
          <td>
            <div class="resident-chip">
              <div class="av">
<?php if(!empty($r['photo'])): ?>
  <div class="av" style="background:none; overflow:hidden;">
    <img src="<?= base_url($r['photo']) ?>" alt="Photo" style="width:100%;height:100%;object-fit:cover;border-radius:9px;">
  </div>
<?php else: ?>
  <div class="av"><?= $initials ?></div>
<?php endif; ?>
              </div>
              <div>
                <div class="res-name"><?= htmlspecialchars(($r['last_name'] ?? '') . ', ' . ($r['first_name'] ?? '') . ' ' . ($r['middle_name'] ?? '')) ?></div>
                <div class="res-sub"><?= htmlspecialchars($r['sex'] ?? '') ?> · <?= htmlspecialchars($r['civil_status'] ?? '') ?> · Age <?= $r['age'] ?? '—' ?></div>
              </div>
            </div>
          </td>
          <td style="color:var(--text-2); font-size:.68rem;"><?= htmlspecialchars($r['contact_number'] ?? '—') ?></td>
          <td>
            <div style="font-size:.68rem; font-weight:600; color:var(--text-2);"><?= htmlspecialchars($r['barangay'] ?? '—') ?></div>
            <div style="font-size:.6rem; color:var(--text-3);"><?= htmlspecialchars($r['street'] ?? '') ?></div>
          </td>
          <td style="text-align:center; font-size:.72rem; font-weight:700; color:var(--green-deep);"><?= $totalInHousehold ?></td>
          <td>
            <?php if($vulnerableCount > 0): ?>
              <span class="badge2 orange"><i class="fa-solid fa-heart-pulse"></i> <?= $vulnerableCount ?></span>
            <?php else: ?>
              <span style="color:var(--text-3); font-size:.65rem;">—</span>
            <?php endif; ?>
          </td>
        </tr>

        <!-- Family panel — always expanded -->
        <?php if($hasFam): ?>
        <tr>
          <td colspan="8" style="padding:0; border:none;">
            <div class="family-panel">
              <div class="family-panel-title">
                <i class="fa-solid fa-people-group"></i> Family Members (<?= count($familyMembers) ?>)
              </div>
              <table class="fam-table">
                <thead>
                  <tr>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Relation</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Education</th>
                    <th>Occupation</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($familyMembers as $m): ?>
                  <tr>
                    <td><span class="member-id-badge"><?= htmlspecialchars($m['member_id'] ?? '—') ?></span></td>
                    <td>
                      <div style="display:flex; align-items:center; gap:7px;">
<?php if(!empty($m['photo'])): ?>
  <div style="width:24px;height:24px;border-radius:6px;overflow:hidden;flex-shrink:0;">
    <img src="<?= base_url($m['photo']) ?>" alt="Photo" style="width:100%;height:100%;object-fit:cover;">
  </div>
<?php else: ?>
  <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.6rem;font-weight:700;flex-shrink:0;">
    <?= strtoupper(substr($m['name'] ?? 'M', 0, 1)) ?>
  </div>
<?php endif; ?>
                        <span style="font-weight:600;"><?= htmlspecialchars($m['name'] ?? '—') ?></span>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($m['relation'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($m['age'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($m['sex'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($m['education'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($m['occupation'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($m['remarks'] ?? '—') ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </td>
        </tr>
        <?php endif; ?>

        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Footer -->
<div class="table-footer">
  Showing <strong><?= $totalCount ?></strong> resident(s)
  <?= $barangay ? ' in <strong>' . htmlspecialchars($barangay) . '</strong>' : '' ?>
  <?= $street   ? ' · Street: <strong>' . htmlspecialchars($street) . '</strong>' : '' ?>
  &nbsp;·&nbsp; Printed: <?= date('F j, Y, g:i a') ?>
</div>

<script>
// Auto-trigger print dialog on load (optional — remove if you prefer manual)
// window.addEventListener('load', () => setTimeout(() => window.print(), 400));
</script>
</body>
</html>