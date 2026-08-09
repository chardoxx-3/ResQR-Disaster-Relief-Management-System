<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Image Statistics Report – <?= htmlspecialchars($selectedBarangay ?: 'All Barangays') ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --green-deep:   #3a6b1a;
  --green-mid:    #6aaa35;
  --green-light:  #8ed15a;
  --green-bg:     #f2faea;
  --orange-deep:  #b85e0a;
  --orange-mid:   #e87620;
  --amber:        #d4920a;
  --blue-deep:    #1746a0;
  --blue-bg:      #eef3fd;
  --red:          #dc2626;

  --bg:           #f6f7f9;
  --surface:      #ffffff;
  --surface2:     #fafbfc;
  --text-1:       #111827;
  --text-2:       #374151;
  --text-3:       #6b7280;
  --text-4:       #9ca3af;
  --border:       #e5e7eb;
  --border-light: #f3f4f6;

  --radius-lg:    16px;
  --radius:       10px;
  --radius-sm:    6px;

  --shadow-xs:    0 1px 2px rgba(0,0,0,.04);
  --shadow-sm:    0 2px 8px rgba(0,0,0,.06);
  --shadow:       0 4px 16px rgba(0,0,0,.08);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { 
  font-family: 'Plus Jakarta Sans', sans-serif; 
  background: var(--bg); 
  color: var(--text-1); 
  padding: 20px;
}

/* ─── Print Header ─────────────────────────────────────── */
.print-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  padding: 18px 24px;
}

.page-title-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
}

.page-icon {
  width: 38px;
  height: 38px;
  background: var(--green-bg);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--green-deep);
  font-size: .95rem;
  flex-shrink: 0;
}

.page-title h5 {
  font-size: .95rem;
  font-weight: 700;
  color: var(--text-1);
  letter-spacing: -.2px;
}
.page-title p {
  font-size: .7rem;
  color: var(--text-3);
  margin-top: 2px;
}

.page-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

/* ─── Buttons ─────────────────────────────────────────── */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  border-radius: var(--radius-sm);
  font-size: .72rem;
  font-weight: 600;
  font-family: 'Plus Jakarta Sans', sans-serif;
  cursor: pointer;
  border: 1.5px solid transparent;
  text-decoration: none;
  transition: all .18s ease;
  white-space: nowrap;
}
.btn i { font-size: .7rem; }

.btn-primary {
  background: var(--green-deep);
  color: #fff;
  border-color: var(--green-deep);
}
.btn-primary:hover {
  background: var(--green-mid);
  border-color: var(--green-mid);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(58,107,26,.25);
}

.btn-outline {
  background: var(--surface);
  color: var(--text-2);
  border-color: var(--border);
}
.btn-outline:hover {
  border-color: var(--green-mid);
  color: var(--green-deep);
  background: var(--green-bg);
}

/* ─── Stats Grid ──────────────────────────────────────── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
  margin-bottom: 16px;
}

.stat-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px 20px;
  box-shadow: var(--shadow-xs);
  transition: box-shadow .2s, transform .2s;
  position: relative;
  overflow: hidden;
}
.stat-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: var(--green-mid);
}
.stat-card.blue::before  { background: #3b82f6; }
.stat-card.amber::before { background: var(--amber); }

.stat-label {
  font-size: .65rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--text-3);
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.stat-value {
  font-size: 1.9rem;
  font-weight: 800;
  color: var(--green-deep);
  line-height: 1;
  letter-spacing: -1px;
}
.stat-card.blue  .stat-value { color: var(--blue-deep); }
.stat-card.amber .stat-value { color: var(--amber); }

.stat-sub {
  font-size: .62rem;
  color: var(--text-4);
  margin-top: 6px;
}

/* ─── Status Summary ──────────────────────────────────── */
.status-panel {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  margin-bottom: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-xs);
}

.status-panel-header {
  padding: 10px 18px;
  background: var(--surface2);
  border-bottom: 1px solid var(--border);
  font-size: .7rem;
  font-weight: 700;
  color: var(--text-2);
  display: flex;
  align-items: center;
  gap: 6px;
}

.status-badges {
  padding: 14px 18px;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.status-chip {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: .68rem;
  font-weight: 600;
  border: 1.5px solid transparent;
}
.status-chip .dot {
  width: 7px; height: 7px;
  border-radius: 50%;
}
.status-chip .chip-label { color: var(--text-3); font-weight: 500; }
.status-chip .chip-count { color: var(--text-1); font-weight: 700; }

.chip-green  { background: var(--green-bg);  border-color: #c3e6a0; }
.chip-amber  { background: #fff8e8;         border-color: #f3d987; }
.chip-red    { background: #fef2f2;         border-color: #fca5a5; }
.chip-teal   { background: #effaf7;         border-color: #a7e3ce; }
.chip-orange { background: #fff5ed;         border-color: #fed7aa; }

/* ─── Table ───────────────────────────────────────────── */
.table-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow-xs);
}

.table-scroll { overflow-x: auto; }

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead th {
  padding: 10px 14px;
  text-align: left;
  font-size: .62rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: var(--text-3);
  background: var(--surface2);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}

.data-table tbody td {
  padding: 10px 14px;
  border-bottom: 1px solid var(--border-light);
  font-size: .74rem;
  vertical-align: middle;
  color: var(--text-2);
}

.data-table tbody tr:last-child td { border-bottom: none; }

/* ─── Cell Components ─────────────────────────────────── */
.hh-code {
  font-family: 'JetBrains Mono', monospace;
  font-size: .62rem;
  background: var(--surface2);
  border: 1px solid var(--border);
  border-radius: 4px;
  padding: 2px 7px;
  color: var(--text-2);
}

.head-name {
  font-weight: 600;
  color: var(--text-1);
}

.location-text {
  color: var(--text-3);
  font-size: .7rem;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 9px;
  border-radius: 20px;
  font-size: .62rem;
  font-weight: 600;
}
.badge-green   { background: var(--green-bg);  color: var(--green-deep); }
.badge-orange  { background: #fff5ed;          color: var(--orange-deep); }
.badge-amber   { background: #fff8e8;          color: var(--amber); }
.badge-neutral { background: var(--bg);        color: var(--text-3); border: 1px solid var(--border); }

.member-count {
  font-size: .72rem;
  font-weight: 600;
  color: var(--text-1);
}
.member-sub {
  font-size: .62rem;
  color: var(--text-4);
}

/* ─── Filter Info ─────────────────────────────────────── */
.filter-info {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-top: none;
  padding: 12px 24px;
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  font-size: .68rem;
  color: var(--text-3);
}
.filter-info strong {
  color: var(--green-deep);
}

/* ─── Table Footer ────────────────────────────────────── */
.table-footer {
  padding: 10px 18px;
  background: var(--surface2);
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
}
.footer-text {
  font-size: .68rem;
  color: var(--text-3);
}
.footer-text strong { color: var(--green-deep); }

/* ─── Empty State ─────────────────────────────────────── */
.empty-state {
  text-align: center;
  padding: 64px 24px;
  color: var(--text-4);
}
.empty-icon {
  width: 56px;
  height: 56px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  margin: 0 auto 14px;
  color: var(--text-4);
}
.empty-state p {
  font-size: .78rem;
  color: var(--text-3);
  margin-top: 4px;
}

/* ─── Print Styles ────────────────────────────────────── */
@media print {
  body { padding: 0; background: #fff; }
  .no-print { display: none !important; }
  .print-header { border-radius: 0; }
  .table-footer { border-radius: 0; }
  .data-table td, .data-table th { font-size: .62rem; padding: 6px 10px; }
  .stats-grid { break-inside: avoid; }
  .status-panel { break-inside: avoid; }
  .table-card { break-inside: auto; }
  tr { break-inside: avoid; break-after: auto; }
}

@page {
  margin: 10mm;
  size: A4 landscape;
}
</style>
</head>
<body>

<!-- ── Page Header ─────────────────────────────── -->
<div class="print-header">
  <div class="page-title-wrap">
    <div class="page-icon">
      <i class="fa-solid fa-chart-simple"></i>
    </div>
    <div class="page-title">
      <h5>Image Statistics Report</h5>
      <p>Track photo upload status for family heads and members · Sorted A–Z by Family Head</p>
    </div>
  </div>
  <div class="page-actions no-print">
    <button class="btn btn-outline" onclick="window.close()">
      <i class="fa-solid fa-xmark"></i> Close
    </button>
    <button class="btn btn-primary" onclick="window.print()">
      <i class="fa-solid fa-print"></i> Print
    </button>
  </div>
</div>

<!-- ── Filter Info ─────────────────────────────── -->
<div class="filter-info">
  <span><i class="fa-solid fa-map-marker-alt"></i> Barangay: <strong><?= htmlspecialchars($selectedBarangay ?: 'All') ?></strong></span>
  <?php if($selectedStreet): ?>
  <span><i class="fa-solid fa-road"></i> Street: <strong><?= htmlspecialchars($selectedStreet) ?></strong></span>
  <?php endif; ?>
  <?php if($selectedStatus): ?>
  <span><i class="fa-solid fa-chart-pie"></i> Status: <strong><?= ucfirst(str_replace('_', ' ', $selectedStatus)) ?></strong></span>
  <?php endif; ?>
  <span><i class="fa-regular fa-calendar"></i> Generated: <strong><?= date('F j, Y, g:i a') ?></strong></span>
</div>

<!-- ── Report Body ─────────────────────────────── -->
<div class="report-body" style="border: 1px solid var(--border); border-top: none; border-radius: 0 0 var(--radius-lg) var(--radius-lg); padding: 20px; background: var(--bg); display: flex; flex-direction: column; gap: 14px;">

  <?php if(!empty($residents) && $totalRecords > 0): ?>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-label"><i class="fa-solid fa-users-viewfinder"></i> Total Household</div>
      <div class="stat-value"><?= number_format($stats['total_individuals']) ?></div>
      <div class="stat-sub"><?= number_format($stats['total_family_heads']) ?> heads · <?= number_format($stats['total_family_members']) ?> members</div>
    </div>

    <div class="stat-card">
      <div class="stat-label"><i class="fa-solid fa-users"></i> Total Family Heads</div>
      <div class="stat-value"><?= number_format($stats['total_family_heads']) ?></div>
    </div>

    <div class="stat-card">
      <div class="stat-label"><i class="fa-solid fa-camera"></i> Heads with Photo</div>
      <div class="stat-value"><?= number_format($stats['heads_with_photo']) ?></div>
      <div class="stat-sub"><?= number_format($stats['heads_without_photo']) ?> without photo</div>
    </div>

    <div class="stat-card amber">
      <div class="stat-label"><i class="fa-solid fa-people-arrows"></i> Total Members</div>
      <div class="stat-value"><?= number_format($stats['total_family_members']) ?></div>
    </div>

    <div class="stat-card amber">
      <div class="stat-label"><i class="fa-solid fa-camera"></i> Members with Photo</div>
      <div class="stat-value"><?= number_format($stats['members_with_photo']) ?></div>
      <div class="stat-sub"><?= number_format($stats['members_without_photo']) ?> without photo</div>
    </div>

    <div class="stat-card">
      <div class="stat-label"><i class="fa-solid fa-signature"></i> Heads with Signature</div>
      <div class="stat-value"><?= number_format($stats['heads_with_signature'] ?? 0) ?></div>
      <div class="stat-sub"><?= number_format(($stats['heads_without_signature'] ?? 0)) ?> without signature</div>
    </div>

    <div class="stat-card">
      <div class="stat-label"><i class="fa-solid fa-fingerprint"></i> Heads with Thumbmark</div>
      <div class="stat-value"><?= number_format($stats['heads_with_thumbmark'] ?? 0) ?></div>
      <div class="stat-sub"><?= number_format(($stats['heads_without_thumbmark'] ?? 0)) ?> without thumbmark</div>
    </div>
  </div>

  <!-- Household Update Status -->
  <div class="status-panel">
    <div class="status-panel-header">
      <i class="fa-solid fa-chart-pie"></i> Household Update Status Summary
    </div>
    <div class="status-badges">
      <div class="status-chip chip-green">
        <div class="dot" style="background:#3a6b1a;"></div>
        <span class="chip-label">Fully Updated</span>
        <span class="chip-count"><?= number_format($stats['households_fully_updated']) ?></span>
      </div>
      <div class="status-chip chip-amber">
        <div class="dot" style="background:#d4920a;"></div>
        <span class="chip-label">Partially Updated</span>
        <span class="chip-count"><?= number_format($stats['households_partially_updated']) ?></span>
      </div>
      <div class="status-chip chip-red">
        <div class="dot" style="background:#dc2626;"></div>
        <span class="chip-label">Not Updated</span>
        <span class="chip-count"><?= number_format($stats['households_not_updated']) ?></span>
      </div>
      <div class="status-chip chip-teal">
        <div class="dot" style="background:#6aaa35;"></div>
        <span class="chip-label">Head Only</span>
        <span class="chip-count"><?= number_format($stats['households_with_head_only']) ?></span>
      </div>
      <div class="status-chip chip-orange">
        <div class="dot" style="background:#e87620;"></div>
        <span class="chip-label">Members Only</span>
        <span class="chip-count"><?= number_format($stats['households_with_members_only']) ?></span>
      </div>
    </div>
  </div>

  <!-- Detailed Table -->
  <div class="table-card">
    <div class="table-scroll">
      <table class="data-table">
        <thead>
          <tr>
            <th>Household #</th>
            <th>Family Head</th>
            <th>Barangay</th>
            <th>Street</th>
            <th style="text-align:center;">Head Photo</th>
            <th style="text-align:center;">Signature</th>
            <th style="text-align:center;">Thumbmark</th>
            <th style="text-align:center;">Members</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($residents as $hh): ?>
          <tr>
            <td><span class="hh-code"><?= htmlspecialchars($hh['household_no']) ?></span></td>
            <td><span class="head-name"><?= htmlspecialchars($hh['formatted_name']) ?></span></td>
            <td><span class="location-text"><?= htmlspecialchars($hh['barangay'] ?? '—') ?></span></td>
            <td><span class="location-text"><?= htmlspecialchars($hh['street'] ?? '—') ?></span></td>
            <td style="text-align:center;">
              <?php if($hh['head_has_photo']): ?>
                <span class="badge badge-green"><i class="fa-solid fa-check"></i> Yes</span>
              <?php else: ?>
                <span class="badge badge-orange"><i class="fa-solid fa-circle-exclamation"></i> No</span>
              <?php endif; ?>
            </td>
            <td style="text-align:center;">
              <?php if($hh['head_has_signature']): ?>
                <span class="badge badge-green"><i class="fa-solid fa-check"></i> Yes</span>
              <?php else: ?>
                <span class="badge badge-orange"><i class="fa-solid fa-circle-exclamation"></i> No</span>
              <?php endif; ?>
            </td>
            <td style="text-align:center;">
              <?php if($hh['head_has_thumbmark']): ?>
                <span class="badge badge-green"><i class="fa-solid fa-check"></i> Yes</span>
              <?php else: ?>
                <span class="badge badge-orange"><i class="fa-solid fa-circle-exclamation"></i> No</span>
              <?php endif; ?>
            </td>
            <td style="text-align:center;">
              <div class="member-count"><?= $hh['members_with_photo'] ?> / <?= $hh['member_count'] ?></div>
              <div class="member-sub">with photo</div>
            </td>
            <td>
              <?php if($hh['update_status'] == 'fully_updated'): ?>
                <span class="badge badge-green">Fully Updated</span>
              <?php elseif($hh['update_status'] == 'partially_updated'): ?>
                <span class="badge badge-amber">Partially Updated</span>
              <?php elseif($hh['update_status'] == 'head_only'): ?>
                <span class="badge badge-green">Head Only</span>
              <?php elseif($hh['update_status'] == 'members_only'): ?>
                <span class="badge badge-amber">Members Only</span>
              <?php else: ?>
                <span class="badge badge-orange">Not Updated</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="table-footer">
      <span class="footer-text">
        Showing <strong><?= number_format($totalRecords) ?></strong> household<?= $totalRecords != 1 ? 's' : '' ?>
      </span>
      <span class="footer-text">
        <i class="fa-solid fa-arrow-up-a-z"></i> Sorted by Family Head Name A–Z
      </span>
    </div>
  </div>

  <?php else: ?>

  <div class="empty-state">
    <div class="empty-icon"><i class="fa-solid fa-chart-simple"></i></div>
    <strong style="font-size:.82rem; color:var(--text-2);">No data to display</strong>
    <p>No households match the selected filters.</p>
  </div>

  <?php endif; ?>

</div><!-- /report-body -->

</body>
</html>z