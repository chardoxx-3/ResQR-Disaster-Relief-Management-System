<?php
// app/Views/reports/index.php
$pageTitle = 'Reports Dashboard';
?>
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep:   #4a7a26;
  --green-mid:    #77BC3F;
  --green-light:  #99d15f;
  --green-glow:   #b8e48a;
  --orange-deep:  #c96b10;
  --orange-mid:   #F58220;
  --amber:        #f5a623;
  --bg:           #f8fafc;
  --surface:      #ffffff;
  --surface2:     #f0fdf4;
  --text-1:       #1e293b;
  --text-2:       #334155;
  --text-3:       #64748b;
  --border:       #e2e8f0;
  --shadow-sm:    0 1px 4px rgba(119,188,63,.08);
  --shadow-md:    0 4px 16px rgba(119,188,63,.12);
  --radius:       14px;
  --radius-sm:    8px;
}

* { box-sizing: border-box; }
body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text-1); }

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* ── Page Header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.2px; }
.page-title p  { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }
.page-actions  { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

/* ── Buttons ── */
.btn-primary-c {
  display:inline-flex; align-items:center; gap:5px;
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s;
  box-shadow:0 4px 12px rgba(119,188,63,.3); font-family:'Outfit',sans-serif;
}
.btn-primary-c:hover { transform:translateY(-1px); color:#fff; box-shadow:0 6px 18px rgba(119,188,63,.4); }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

.btn-icon {
  display:inline-flex; align-items:center; justify-content:center;
  width:28px; height:28px; border-radius:var(--radius-sm);
  border:1.5px solid var(--border); background:var(--surface);
  color:var(--text-3); font-size:.72rem; text-decoration:none;
  transition:all .2s; cursor:pointer; font-family:'Outfit',sans-serif;
}
.btn-icon:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

/* ── Badges ── */
.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 8px; border-radius:20px; font-size:.6rem; font-weight:700;
}
.badge2.success  { background:#f0fdf4; color:var(--green-deep); border:1px solid var(--green-glow); }
.badge2.danger   { background:#fff1f2; color:#c0392b; border:1px solid #fecaca; }
.badge2.blue     { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.badge2.warning  { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.badge2.neutral  { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }
.badge2.orange   { background:#fff7ed; color:var(--orange-deep); border:1px solid #fed7aa; }
.badge2.dark     { background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; }

/* ── Filter Bar ── */
.filter-bar {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
.filter-inner {
  display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;
}
.filter-group { display:flex; flex-direction:column; gap:4px; }
.filter-label { font-size:.62rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:.5px; }
.filter-date-group { display:flex; align-items:center; gap:6px; }
.filter-date-group .form-control { width:130px; }
.filter-sep { font-size:.7rem; color:var(--text-3); }
.form-control, .form-select {
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:7px 10px; font-size:.75rem; font-family:'Outfit',sans-serif;
  color:var(--text-1); background:var(--surface); outline:none;
  transition:border-color .2s, box-shadow .2s;
}
.form-control:focus, .form-select:focus {
  border-color:var(--green-mid); box-shadow:0 0 0 3px rgba(119,188,63,.12);
}
.filter-meta {
  margin-left:auto; display:flex; align-items:center;
  font-size:.62rem; color:var(--text-3); gap:4px;
}

/* ── KPI Grid ── */
.kpi-grid {
  display:grid; grid-template-columns:repeat(4,1fr); gap:12px;
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
@media(max-width:900px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:500px){ .kpi-grid{ grid-template-columns:1fr; } }

.kpi-card {
  background:var(--surface); border-radius:var(--radius);
  padding:14px 16px; border:1px solid var(--border);
  box-shadow:var(--shadow-sm); overflow:hidden;
  transition:box-shadow .2s, transform .2s;
}
.kpi-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.kpi-card.kpi-hero {
  background:linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 100%);
  border-color:transparent;
}
.kpi-icon {
  width:34px; height:34px; border-radius:8px;
  display:flex; align-items:center; justify-content:center;
  font-size:.85rem; margin-bottom:8px;
}
.kpi-hero .kpi-icon { background:rgba(255,255,255,.2); color:#fff; }
.kpi-icon.green  { background:#f0fdf4; color:var(--green-deep); }
.kpi-icon.orange { background:#fff7ed; color:var(--orange-deep); }
.kpi-icon.amber  { background:#fffbeb; color:#c08000; }
.kpi-icon.blue   { background:#eff6ff; color:#1d4ed8; }
.kpi-label { font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--text-3); margin-bottom:2px; }
.kpi-hero .kpi-label { color:rgba(255,255,255,.75); }
.kpi-val { font-size:1.6rem; font-weight:800; line-height:1; color:var(--text-1); font-family:'DM Mono',monospace; }
.kpi-hero .kpi-val { color:#fff; }
.kpi-sub { font-size:.62rem; color:var(--text-3); margin-top:3px; }
.kpi-hero .kpi-sub { color:rgba(255,255,255,.65); }

/* ── Charts Row ── */
.charts-row {
  display:grid; grid-template-columns:1fr 320px; gap:12px;
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
@media(max-width:860px){ .charts-row{ grid-template-columns:1fr; } }

.chart-panel {
  background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
  overflow:hidden;
}
.chart-panel-header {
  padding:12px 16px; border-bottom:1px solid var(--border); background:var(--bg);
  display:flex; align-items:center; justify-content:space-between;
}
.chart-panel-header h6 { font-size:.75rem; font-weight:800; color:var(--text-1); margin:0; }
.chart-panel-body { padding:16px; }

/* ── Top barangays list ── */
.barangay-row {
  display:flex; align-items:center; gap:10px; margin-bottom:10px;
}
.barangay-row:last-child { margin-bottom:0; }
.bgy-rank {
  width:22px; height:22px; border-radius:6px;
  background:var(--bg); border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center;
  font-size:.62rem; font-weight:800; color:var(--text-3); flex-shrink:0;
}
.bgy-info { flex:1; }
.bgy-name { font-size:.72rem; font-weight:700; color:var(--text-1); margin-bottom:3px; display:flex; justify-content:space-between; }
.bgy-count { font-family:'DM Mono',monospace; font-size:.68rem; color:var(--green-deep); }
.bgy-bar-track { height:4px; background:var(--border); border-radius:20px; overflow:hidden; }
.bgy-bar-fill  { height:100%; border-radius:20px; background:linear-gradient(90deg,var(--green-deep),var(--green-mid)); }

/* ── Tab Nav ── */
.tab-nav-wrap {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:0 20px;
}
.tab-nav {
  display:flex; gap:0; list-style:none; margin:0; padding:0;
  border-bottom:1px solid var(--border);
}
.tab-nav li button {
  display:inline-flex; align-items:center; gap:6px;
  padding:12px 16px; font-size:.72rem; font-weight:600;
  color:var(--text-3); background:none; border:none;
  border-bottom:2px solid transparent; cursor:pointer;
  transition:all .2s; font-family:'Outfit',sans-serif; margin-bottom:-1px;
}
.tab-nav li button:hover { color:var(--green-deep); }
.tab-nav li button.active { color:var(--green-deep); border-bottom-color:var(--green-mid); }

/* ── Tab Panels ── */
.tab-panels {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius);
  overflow:hidden;
}
.tab-panel { display:none; }
.tab-panel.active { display:block; }

.panel-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:12px 16px; border-bottom:1px solid var(--border); background:var(--bg);
}
.panel-header h6 { font-size:.75rem; font-weight:800; color:var(--text-1); margin:0; }

/* ── Data tables ── */
.data-table { width:100%; border-collapse:collapse; }
.data-table thead th {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  color:var(--text-3); padding:10px 14px; border-bottom:1px solid var(--border);
  background:var(--bg); text-align:left; white-space:nowrap;
}
.data-table tbody td {
  font-size:.72rem; color:var(--text-2); padding:10px 14px;
  border-bottom:1px solid var(--border); vertical-align:middle;
}
.data-table tbody tr:last-child td { border-bottom:none; }
.data-table tbody tr:hover td { background:#fafcf8; }

.mono  { font-family:'DM Mono',monospace; }
.fw800 { font-weight:800; color:var(--text-1); }
.sub   { font-size:.62rem; color:var(--text-3); display:block; margin-top:1px; }

/* ── Mini progress ── */
.mini-progress { display:flex; align-items:center; gap:8px; }
.mini-bar-track { flex:1; height:5px; background:var(--border); border-radius:20px; overflow:hidden; min-width:50px; }
.mini-bar-fill  { height:100%; border-radius:20px; }
.mini-bar-fill.green  { background:linear-gradient(90deg,var(--green-deep),var(--green-mid)); }
.mini-bar-fill.orange { background:linear-gradient(90deg,var(--orange-deep),var(--orange-mid)); }
.mini-bar-fill.danger { background:linear-gradient(90deg,#c0392b,#e74c3c); }

/* ── Empty state ── */
.empty-state { text-align:center; padding:40px 20px; color:var(--text-3); font-size:.75rem; }
.empty-state i { font-size:1.8rem; margin-bottom:8px; display:block; color:var(--border); }

/* ── Modal ── */
.modal-custom .modal-content { border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-md); }
.modal-custom .modal-header { padding:14px 20px; border-bottom:1px solid var(--border); background:var(--bg); border-radius:var(--radius) var(--radius) 0 0; }
.modal-custom .modal-title  { font-size:.88rem; font-weight:800; color:var(--text-1); }
.modal-custom .modal-body   { padding:20px; }
.modal-custom .modal-footer { padding:12px 20px; border-top:1px solid var(--border); gap:6px; }
.field-label { display:block; font-size:.7rem; font-weight:700; color:var(--text-2); margin-bottom:5px; }
</style>

<div class="page-wrap">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-chart-line me-2" style="color:var(--green-mid)"></i>Reports Dashboard</h5>
      <p>Comprehensive reports and analytics for relief operations</p>
    </div>
    <div class="page-actions">
      <button onclick="window.print()" class="btn-outline-c">
        <i class="fa-solid fa-print"></i> Print
      </button>
      <button class="btn-primary-c" data-bs-toggle="modal" data-bs-target="#exportModal">
        <i class="fa-solid fa-download"></i> Export Data
      </button>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar">
    <form method="get" action="/reports">
      <div class="filter-inner">
        <div class="filter-group">
          <span class="filter-label"><i class="fa-solid fa-calendar me-1"></i>Date Range</span>
          <div class="filter-date-group">
            <input type="date" name="start_date" class="form-control" value="<?= $startDate ?? date('Y-m-01') ?>">
            <span class="filter-sep">to</span>
            <input type="date" name="end_date" class="form-control" value="<?= $endDate ?? date('Y-m-d') ?>">
          </div>
        </div>
        <div class="filter-group">
          <span class="filter-label"><i class="fa-solid fa-location-dot me-1"></i>Barangay</span>
          <select name="barangay" class="form-select" style="width:160px">
            <option value="">All Barangays</option>
            <?php if(isset($barangays) && !empty($barangays)): ?>
              <?php foreach($barangays as $b): ?>
                <option value="<?= esc($b) ?>" <?= ($selectedBarangay == $b) ? 'selected' : '' ?>><?= esc($b) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <button type="submit" class="btn-primary-c" style="align-self:flex-end">
          <i class="fa-solid fa-filter"></i> Apply Filter
        </button>
        <div class="filter-meta">
          <i class="fa-solid fa-circle-info"></i>
          Last updated: <?= date('M d, Y h:i A') ?>
        </div>
      </div>
    </form>
  </div>

  <!-- KPI Grid -->
  <div class="kpi-grid">
    <div class="kpi-card kpi-hero">
      <div class="kpi-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
      <div class="kpi-label">Total Distributions</div>
      <div class="kpi-val"><?= number_format($totalDistributions ?? 0) ?></div>
      <div class="kpi-sub">All time records</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon blue"><i class="fa-solid fa-users"></i></div>
      <div class="kpi-label">Total Residents</div>
      <div class="kpi-val"><?= number_format($totalResidents ?? 0) ?></div>
      <div class="kpi-sub">Registered families</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon orange"><i class="fa-solid fa-boxes-stacked"></i></div>
      <div class="kpi-label">Active Batches</div>
      <div class="kpi-val"><?= number_format($activeBatches ?? 0) ?></div>
      <div class="kpi-sub">With remaining stock</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon amber"><i class="fa-solid fa-calendar-check"></i></div>
      <div class="kpi-label">Today's Attendance</div>
      <div class="kpi-val"><?= number_format($todayAttendance ?? 0) ?></div>
      <div class="kpi-sub">Checked in today</div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="charts-row">
    <!-- Distribution Trend Chart -->
    <div class="chart-panel">
      <div class="chart-panel-header">
        <h6><i class="fa-solid fa-chart-area me-2" style="color:var(--green-mid)"></i>Distribution Trends</h6>
      </div>
      <div class="chart-panel-body">
        <canvas id="distributionChart" style="height:240px"></canvas>
      </div>
    </div>

    <!-- Top Barangays -->
    <div class="chart-panel">
      <div class="chart-panel-header">
        <h6><i class="fa-solid fa-ranking-star me-2" style="color:var(--green-mid)"></i>Top Barangays</h6>
      </div>
      <div class="chart-panel-body">
        <?php if(isset($topBarangays) && !empty($topBarangays)): ?>
          <?php foreach($topBarangays as $index => $barangay): ?>
          <div class="barangay-row">
            <div class="bgy-rank"><?= $index + 1 ?></div>
            <div class="bgy-info">
              <div class="bgy-name">
                <span><?= esc($barangay['barangay'] ?? 'N/A') ?></span>
                <span class="bgy-count"><?= number_format($barangay['count'] ?? 0) ?></span>
              </div>
              <div class="bgy-bar-track">
                <div class="bgy-bar-fill" style="width:<?= $barangay['percentage'] ?? 0 ?>%"></div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty-state" style="padding:20px 0">
            <i class="fa-solid fa-chart-bar"></i>No data available
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Tab Navigation -->
  <div class="tab-nav-wrap">
    <ul class="tab-nav" id="reportTabs">
      <li><button class="active" data-tab="distributions"><i class="fa-solid fa-hand-holding-heart"></i> Distributions</button></li>
      <li><button data-tab="inventory"><i class="fa-solid fa-boxes-stacked"></i> Inventory Batches</button></li>
      <li><button data-tab="attendance"><i class="fa-solid fa-calendar-check"></i> Attendance</button></li>
      <li><button data-tab="residents"><i class="fa-solid fa-users"></i> Residents</button></li>
    </ul>
  </div>

  <!-- Tab Panels -->
  <div class="tab-panels">

    <!-- ── Distributions ── -->
    <div class="tab-panel active" id="tab-distributions">
      <div class="panel-header">
        <h6><i class="fa-solid fa-hand-holding-heart me-2" style="color:var(--green-mid)"></i>Distribution Logs</h6>
        <span class="badge2 neutral"><?= isset($distributions) ? count($distributions) : 0 ?> records</span>
      </div>
      <div style="overflow-x:auto">
        <table class="data-table datatable">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Recipient</th>
              <th>Household #</th>
              <th>Barangay</th>
              <th>Item</th>
              <th>Batch #</th>
              <th>Quantity</th>
              <th>Distributor</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($distributions) && !empty($distributions)): ?>
              <?php foreach($distributions as $dist): ?>
              <tr>
                <td>
                  <span class="fw800 mono" style="font-size:.68rem"><?= date('M d, Y', strtotime($dist['distribution_date'] ?? $dist['claimed_at'] ?? '')) ?></span>
                  <span class="sub"><?= date('h:i A', strtotime($dist['distribution_date'] ?? $dist['claimed_at'] ?? '')) ?></span>
                </td>
                <td>
                  <span class="fw800"><?= esc(($dist['first_name'] ?? $dist['resident_name'] ?? $dist['full_name'] ?? 'N/A') . ' ' . ($dist['last_name'] ?? '')) ?></span>
                  <?php if(isset($dist['family_member_name'])): ?>
                    <span class="sub"><?= esc($dist['family_member_name']) ?></span>
                  <?php endif; ?>
                </td>
                <td><span class="mono"><?= esc($dist['household_no'] ?? 'N/A') ?></span></td>
                <td><?= esc($dist['barangay'] ?? 'N/A') ?></td>
                <td><span class="badge2 blue"><?= esc($dist['item_name'] ?? 'N/A') ?></span></td>
                <td><span class="mono" style="color:var(--text-3);font-size:.68rem"><?= esc($dist['batch_number'] ?? 'N/A') ?></span></td>
                <td>
                  <span class="fw800 mono"><?= esc($dist['quantity_distributed'] ?? 1) ?></span>
                  <span class="sub"><?= esc($dist['unit_type'] ?? '') ?></span>
                </td>
                <td><?= esc($dist['distributor_name'] ?? $dist['username'] ?? 'System') ?></td>
                <td>
                  <?php $dstatus = $dist['status'] ?? 'completed'; ?>
                  <span class="badge2 <?= $dstatus == 'completed' ? 'success' : 'warning' ?>">
                    <i class="fa-solid fa-<?= $dstatus == 'completed' ? 'circle-check' : 'clock' ?> me-1"></i>
                    <?= ucfirst($dstatus) ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="9"><div class="empty-state"><i class="fa-solid fa-inbox"></i>No distribution records found</div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── Inventory Batches ── -->
    <div class="tab-panel" id="tab-inventory">
      <div class="panel-header">
        <h6><i class="fa-solid fa-boxes-stacked me-2" style="color:var(--green-mid)"></i>Batch Inventory Status</h6>
        <span class="badge2 neutral"><?= isset($batches) ? count($batches) : 0 ?> batches</span>
      </div>
      <div style="overflow-x:auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Batch #</th>
              <th>Item</th>
              <th>Source</th>
              <th>Initial Stock</th>
              <th>Remaining</th>
              <th>Storage Details</th>
              <th>Location</th>
              <th>Status</th>
              <th>Expiry</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($batches) && !empty($batches)): ?>
              <?php foreach($batches as $batch): ?>
              <?php
                $remaining   = $batch['quantity_remaining'] ?? 0;
                $initial     = $batch['quantity_initial'] ?? 1;
                $pct         = $initial > 0 ? ($remaining / $initial) * 100 : 0;
                $barClass    = $pct > 50 ? 'green' : ($pct > 20 ? 'orange' : 'danger');
                $bstatus     = $batch['status'] ?? 'in_warehouse';
                $bstatusMap  = [
                  'in_warehouse' => ['neutral', 'warehouse',            'In Warehouse'],
                  'in_transit'   => ['warning', 'truck',                'In Transit'],
                  'received'     => ['success', 'circle-check',         'Received'],
                  'depleted'     => ['danger',  'circle-xmark',         'Depleted'],
                  'expired'      => ['dark',    'triangle-exclamation', 'Expired'],
                ];
                $bs = $bstatusMap[$bstatus] ?? ['neutral','circle', ucfirst(str_replace('_',' ',$bstatus))];
              ?>
              <tr>
                <td><span class="mono" style="font-size:.68rem;color:var(--text-3)"><?= esc($batch['batch_number'] ?? 'N/A') ?></span></td>
                <td>
                  <span class="fw800"><?= esc($batch['item_name'] ?? 'N/A') ?></span>
                  <span class="sub"><?= esc($batch['unit_type'] ?? '') ?></span>
                </td>
                <td>
                  <?= ucfirst(str_replace('_',' ', $batch['source_type'] ?? 'Unknown')) ?>
                  <?php if(isset($batch['source_details'])): ?>
                    <span class="sub"><?= esc($batch['source_details']) ?></span>
                  <?php endif; ?>
                </td>
                <td><span class="fw800 mono"><?= number_format($batch['quantity_initial'] ?? 0) ?></span></td>
                <td>
                  <div class="mini-progress">
                    <span class="fw800 mono" style="min-width:36px"><?= number_format($remaining) ?></span>
                    <div class="mini-bar-track">
                      <div class="mini-bar-fill <?= $barClass ?>" style="width:<?= round($pct) ?>%"></div>
                    </div>
                  </div>
                </td>
                <td>
                  <?php if(isset($batch['storage_quantity'])): ?>
                    <span class="fw800"><?= number_format($batch['storage_quantity']) ?> <?= esc($batch['storage_unit'] ?? 'units') ?></span>
                    <span class="sub"><?= esc($batch['units_per_storage'] ?? 1) ?>/unit</span>
                  <?php else: ?>
                    <span style="color:var(--text-3)">N/A</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= esc($batch['storage_location'] ?? 'Main Warehouse') ?>
                  <?php if(isset($batch['barangay'])): ?>
                    <span class="sub">→ <?= esc($batch['barangay']) ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="badge2 <?= $bs[0] ?>">
                    <i class="fa-solid fa-<?= $bs[1] ?> me-1"></i><?= $bs[2] ?>
                  </span>
                </td>
                <td>
                  <?php if(isset($batch['expiry_date']) && $batch['expiry_date']): ?>
                    <?php $expiry = strtotime($batch['expiry_date']); ?>
                    <?php if($expiry < time()): ?>
                      <span class="badge2 danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Expired <?= date('M d, Y', $expiry) ?></span>
                    <?php else: ?>
                      <?= date('M d, Y', $expiry) ?>
                    <?php endif; ?>
                  <?php else: ?>
                    <span style="color:var(--text-3)">N/A</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="9"><div class="empty-state"><i class="fa-solid fa-box-open"></i>No batch records found</div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── Attendance ── -->
    <div class="tab-panel" id="tab-attendance">
      <div class="panel-header">
        <h6><i class="fa-solid fa-calendar-check me-2" style="color:var(--green-mid)"></i>Attendance Records</h6>
        <span class="badge2 neutral"><?= isset($attendance) ? count($attendance) : 0 ?> records</span>
      </div>
      <div style="overflow-x:auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Name</th>
              <th>Household #</th>
              <th>Barangay</th>
              <th>Check In</th>
              <th>Purpose</th>
              <th>Status</th>
              <th>Scanned By</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($attendance) && !empty($attendance)): ?>
              <?php foreach($attendance as $att): ?>
              <tr>
                <td><span class="mono" style="font-size:.68rem"><?= date('M d, Y', strtotime($att['attendance_date'] ?? '')) ?></span></td>
                <td>
                  <span class="fw800"><?= esc(($att['first_name'] ?? '') . ' ' . ($att['last_name'] ?? '')) ?></span>
                  <?php if(isset($att['family_member_name'])): ?>
                    <span class="sub"><?= esc($att['family_member_name']) ?></span>
                  <?php endif; ?>
                </td>
                <td><span class="mono"><?= esc($att['household_no'] ?? 'N/A') ?></span></td>
                <td><?= esc($att['barangay'] ?? 'N/A') ?></td>
                <td>
                  <span class="badge2 success">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i>
                    <?= date('h:i A', strtotime($att['check_in_time'] ?? '')) ?>
                  </span>
                </td>
                <td><?= esc($att['purpose'] ?? 'Relief Distribution') ?></td>
                <td>
                  <?php if(!isset($att['check_out_time'])): ?>
                    <span class="badge2 success"><i class="fa-solid fa-circle-dot me-1"></i>Checked In</span>
                  <?php else: ?>
                    <span class="badge2 neutral"><i class="fa-solid fa-circle-check me-1"></i>Completed</span>
                  <?php endif; ?>
                </td>
                <td><?= esc($att['scanned_by_name'] ?? 'System') ?></td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="9"><div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i>No attendance records found</div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── Residents ── -->
    <div class="tab-panel" id="tab-residents">
      <div class="panel-header">
        <h6><i class="fa-solid fa-users me-2" style="color:var(--green-mid)"></i>Registered Residents</h6>
        <span class="badge2 neutral"><?= isset($residents) ? count($residents) : 0 ?> residents</span>
      </div>
      <div style="overflow-x:auto">
        <table class="data-table">
          <thead>
            <tr>
              <th>Household #</th>
              <th>Head of Family</th>
              <th>Barangay</th>
              <th>Contact #</th>
              <th>Family Members</th>
              <th>Status</th>
              <th>Registration Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($residents) && !empty($residents)): ?>
              <?php foreach($residents as $resident): ?>
              <tr>
                <td><span class="mono badge2 neutral"><?= esc($resident['household_no'] ?? 'N/A') ?></span></td>
                <td>
                  <span class="fw800"><?= esc(($resident['first_name'] ?? '') . ' ' . ($resident['last_name'] ?? '')) ?></span>
                  <?php if(isset($resident['name_extension'])): ?>
                    <span class="sub"><?= esc($resident['name_extension']) ?></span>
                  <?php endif; ?>
                </td>
                <td><?= esc($resident['barangay'] ?? 'N/A') ?></td>
                <td><span class="mono"><?= esc($resident['contact_number'] ?? 'N/A') ?></span></td>
                <td>
                  <?php $familyCount = $resident['family_members_count'] ?? 0; ?>
                  <span class="badge2 blue"><i class="fa-solid fa-users me-1"></i><?= $familyCount ?> members</span>
                </td>
                <td>
                  <?php $rstatus = $resident['status'] ?? 'unclaimed'; ?>
                  <span class="badge2 <?= $rstatus == 'claimed' ? 'success' : 'warning' ?>">
                    <i class="fa-solid fa-<?= $rstatus == 'claimed' ? 'circle-check' : 'clock' ?> me-1"></i>
                    <?= ucfirst($rstatus) ?>
                  </span>
                </td>
                <td><?= isset($resident['created_at']) ? date('M d, Y', strtotime($resident['created_at'])) : 'N/A' ?></td>
                <td>
                  <button class="btn-icon view-resident" data-id="<?= $resident['id'] ?? 0 ?>" title="View Resident">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8"><div class="empty-state"><i class="fa-solid fa-address-book"></i>No resident records found</div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /tab-panels -->
</div><!-- /page-wrap -->

<!-- ══ Export Modal ══ -->
<div class="modal fade modal-custom" id="exportModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title"><i class="fa-solid fa-file-export me-2" style="color:var(--green-mid)"></i>Export Reports</div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="/reports/export" method="post">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="field-label">Report Type</label>
            <select name="report_type" class="form-select" style="width:100%">
              <option value="distributions">Distributions Report</option>
              <option value="inventory">Inventory Report</option>
              <option value="attendance">Attendance Report</option>
              <option value="residents">Residents Report</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="field-label">Format</label>
            <select name="format" class="form-select" style="width:100%">
              <option value="csv">CSV</option>
              <option value="excel">Excel</option>
              <option value="pdf">PDF</option>
            </select>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
            <div>
              <label class="field-label">Start Date</label>
              <input type="date" name="start_date" class="form-control" style="width:100%" value="<?= date('Y-m-01') ?>">
            </div>
            <div>
              <label class="field-label">End Date</label>
              <input type="date" name="end_date" class="form-control" style="width:100%" value="<?= date('Y-m-d') ?>">
            </div>
          </div>
          <button type="submit" class="btn-primary-c" style="width:100%;justify-content:center;padding:9px">
            <i class="fa-solid fa-file-export"></i> Generate Export
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- DataTables -->
<link  rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
// ── Tab Switcher ──
document.querySelectorAll('.tab-nav button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-nav button').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
  });
});

document.addEventListener('DOMContentLoaded', function() {
  // ── Distribution Chart ──
  const ctx = document.getElementById('distributionChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($chartLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']) ?>,
      datasets: [{
        label: 'Distributions',
        data: <?= json_encode($chartData ?? [0,0,0,0,0,0,0]) ?>,
        borderColor: '#77BC3F',
        backgroundColor: 'rgba(119,188,63,0.08)',
        pointBackgroundColor: '#4a7a26',
        pointRadius: 4,
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: '#f0f0f0' }, ticks: { font: { family: 'Outfit', size: 11 } } },
        y: { grid: { color: '#f0f0f0' }, ticks: { font: { family: 'DM Mono', size: 11 } } }
      }
    }
  });

  // ── DataTables ──
  if($.fn.DataTable) {
    $('.datatable').DataTable({ pageLength: 25, order: [[0, 'desc']] });
  }
});
</script>

<?= $this->endSection() ?>