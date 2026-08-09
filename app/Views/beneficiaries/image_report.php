<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

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
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-1); }

/* ─── Layout ─────────────────────────────────────────── */
.report-wrap {
  animation: fadeUp .4s ease both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: none; }
}

/* ─── Page Header ─────────────────────────────────────── */
.page-header {
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

.btn-ghost {
  background: transparent;
  color: var(--text-3);
  border-color: var(--border);
}
.btn-ghost:hover {
  border-color: var(--orange-mid);
  color: var(--orange-deep);
  background: #fff5ed;
}

/* ─── Filter Bar ──────────────────────────────────────── */
.filter-bar {
  background: var(--surface2);
  border: 1px solid var(--border);
  border-top: none;
  padding: 14px 24px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: flex-end;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.filter-label {
  font-size: .65rem;
  font-weight: 700;
  color: var(--text-3);
  text-transform: uppercase;
  letter-spacing: .6px;
}

.filter-select {
  padding: 7px 30px 7px 11px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  font-size: .74rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: var(--surface);
  color: var(--text-1);
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7280'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
  min-width: 190px;
  box-shadow: var(--shadow-xs);
  transition: border-color .15s, box-shadow .15s;
}
.filter-select:focus {
  outline: none;
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(106,170,53,.12);
}
.filter-select:disabled {
  opacity: .4;
  cursor: not-allowed;
}

.filter-actions {
  display: flex;
  gap: 6px;
  margin-bottom: 1px;
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

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow);
}

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
.chip-amber  { background: #fff8e8;           border-color: #f3d987; }
.chip-red    { background: #fef2f2;           border-color: #fca5a5; }
.chip-teal   { background: #effaf7;           border-color: #a7e3ce; }
.chip-orange { background: #fff5ed;           border-color: #fed7aa; }

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
.data-table tbody tr:hover { background: #fafffe; }

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

/* ─── Actions ─────────────────────────────────────────── */
.action-group { display: inline-flex; gap: 4px; }

.act-btn {
  width: 28px;
  height: 28px;
  border-radius: var(--radius-sm);
  border: 1.5px solid var(--border);
  background: var(--surface);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: .65rem;
  cursor: pointer;
  text-decoration: none;
  transition: all .15s;
  color: var(--text-4);
}
.act-btn:hover { transform: translateY(-1px); }
.act-btn.view:hover { border-color: #3b82f6; background: var(--blue-bg); color: var(--blue-deep); }
.act-btn.edit:hover { border-color: var(--green-mid); background: var(--green-bg); color: var(--green-deep); }

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

/* ─── Report Container ────────────────────────────────── */
.report-body {
  border: 1px solid var(--border);
  border-top: none;
  border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  padding: 20px;
  background: var(--bg);
  display: flex;
  flex-direction: column;
  gap: 14px;
}

/* ─── Responsive ──────────────────────────────────────── */
@media (max-width: 768px) {
  .page-header    { padding: 14px 16px; }
  .filter-bar     { flex-direction: column; padding: 14px 16px; }
  .filter-select  { width: 100%; min-width: unset; }
  .filter-actions { width: 100%; }
  .filter-actions .btn { flex: 1; justify-content: center; }
  .stats-grid     { grid-template-columns: 1fr 1fr; }
  .report-body    { padding: 14px; }
}
@media (max-width: 480px) {
  .stats-grid  { grid-template-columns: 1fr; }
  .page-title p { display: none; }
}

/* Add to your existing CSS */
.status-chip.clickable-filter {
    transition: all 0.2s ease;
    cursor: pointer;
}

.status-chip.clickable-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.status-chip.active-filter {
    border-width: 2px;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
}

.chip-green.active-filter { background: #d4edda; border-color: #3a6b1a; }
.chip-amber.active-filter { background: #fff3cd; border-color: #d4920a; }
.chip-red.active-filter { background: #f8d7da; border-color: #dc2626; }
.chip-teal.active-filter { background: #d1e7dd; border-color: #6aaa35; }
.chip-orange.active-filter { background: #ffe5d0; border-color: #e87620; }
</style>

<div class="report-wrap">

  <!-- ── Page Header ─────────────────────────────── -->
  <div class="page-header">
    <div class="page-title-wrap">
      <div class="page-icon">
        <i class="fa-solid fa-chart-simple"></i>
      </div>
      <div class="page-title">
        <h5>Image Statistics Report</h5>
        <p>Track photo upload status for family heads and members · Sorted A–Z by Family Head</p>
      </div>
    </div>
<div class="page-actions">
  <a href="/beneficiaries/resident-list" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Back to Residents
  </a>
  <button onclick="printReport()" class="btn btn-outline">
    <i class="fa-solid fa-print"></i> Print
  </button>
  <button onclick="exportReport()" class="btn btn-primary">
    <i class="fa-solid fa-download"></i> Export
  </button>
</div>
  </div>

  <!-- ── Filter Bar ──────────────────────────────── -->
  <div class="filter-bar">
    <div class="filter-group">
      <span class="filter-label"><i class="fa-solid fa-map-marker-alt"></i> Barangay</span>
      <select id="barangayFilter" class="filter-select">
        <option value="">All Barangays</option>
        <?php foreach($barangays as $b): ?>
          <option value="<?= htmlspecialchars($b) ?>" <?= ($selectedBarangay ?? '') == $b ? 'selected' : '' ?>>
            <?= htmlspecialchars($b) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <span class="filter-label"><i class="fa-solid fa-road"></i> Street</span>
      <select id="streetFilter" class="filter-select" <?= empty($streets) ? 'disabled' : '' ?>>
        <option value="">All Streets</option>
        <?php foreach($streets as $s): ?>
          <option value="<?= htmlspecialchars($s) ?>" <?= ($selectedStreet ?? '') == $s ? 'selected' : '' ?>>
            <?= htmlspecialchars($s) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <span class="filter-label">&nbsp;</span>
      <div class="filter-actions">
        <button class="btn btn-primary" onclick="applyFilters()">
          <i class="fa-solid fa-magnifying-glass"></i> Generate
        </button>
        <button class="btn btn-ghost" onclick="resetFilters()">
          <i class="fa-solid fa-rotate"></i> Reset
        </button>
      </div>
    </div>
  </div>

  <!-- ── Report Body ─────────────────────────────── -->
  <div class="report-body">

    <?php if(isset($stats) && $stats['total_family_heads'] > 0): ?>

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

      <!-- Add this after the existing stat cards -->
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

<!-- Household Update Status - CLICKABLE FILTER BUTTONS -->
<div class="status-panel">
    <div class="status-panel-header">
        <i class="fa-solid fa-chart-pie"></i> Household Update Status 
        <?php if(!empty($selectedStatus)): ?>
            <span class="badge" style="margin-left: 10px; background: var(--green-bg);">
                Filter: <?= ucfirst(str_replace('_', ' ', $selectedStatus)) ?>
                <a href="javascript:void(0)" onclick="clearStatusFilter()" style="margin-left: 5px; color: var(--red); text-decoration: none;">✕</a>
            </span>
        <?php endif; ?>
    </div>
    <div class="status-badges">
        <div class="status-chip chip-green clickable-filter" data-status="fully_updated" onclick="applyStatusFilter('fully_updated')" style="cursor: pointer;">
            <div class="dot" style="background:#3a6b1a;"></div>
            <span class="chip-label">Fully Updated</span>
            <span class="chip-count"><?= number_format($stats['households_fully_updated']) ?></span>
        </div>
        <div class="status-chip chip-amber clickable-filter" data-status="partially_updated" onclick="applyStatusFilter('partially_updated')" style="cursor: pointer;">
            <div class="dot" style="background:#d4920a;"></div>
            <span class="chip-label">Partially Updated</span>
            <span class="chip-count"><?= number_format($stats['households_partially_updated']) ?></span>
        </div>
        <div class="status-chip chip-red clickable-filter" data-status="not_updated" onclick="applyStatusFilter('not_updated')" style="cursor: pointer;">
            <div class="dot" style="background:#dc2626;"></div>
            <span class="chip-label">Not Updated</span>
            <span class="chip-count"><?= number_format($stats['households_not_updated']) ?></span>
        </div>
        <div class="status-chip chip-teal clickable-filter" data-status="head_only" onclick="applyStatusFilter('head_only')" style="cursor: pointer;">
            <div class="dot" style="background:#6aaa35;"></div>
            <span class="chip-label">Head Only</span>
            <span class="chip-count"><?= number_format($stats['households_with_head_only']) ?></span>
        </div>
        <div class="status-chip chip-orange clickable-filter" data-status="members_only" onclick="applyStatusFilter('members_only')" style="cursor: pointer;">
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
        <th style="text-align:center;">Actions</th>
    </tr>
</thead>
<tbody>
    <?php foreach($householdDetails as $hh): ?>
    <tr>
        <td><span class="hh-code"><?= htmlspecialchars($hh['household_no']) ?></span></td>
        <td><span class="head-name"><?= htmlspecialchars($hh['head_name']) ?></span></td>
        <td><span class="location-text"><?= htmlspecialchars($hh['barangay'] ?? '—') ?></span></td>
        <td><span class="location-text"><?= htmlspecialchars($hh['street'] ?? '—') ?></span></td>
        <td style="text-align:center;">
            <?php if($hh['head_has_photo']): ?>
                <span class="badge badge-green"><i class="fa-solid fa-check"></i> Yes</span>
            <?php else: ?>
                <span class="badge badge-orange"><i class="fa-solid fa-circle-exclamation"></i> No</span>
            <?php endif; ?>
        </td>
        <!-- NEW: Signature column -->
        <td style="text-align:center;">
            <?php if(isset($hh['head_has_signature']) && $hh['head_has_signature']): ?>
                <span class="badge badge-green"><i class="fa-solid fa-check"></i> Yes</span>
            <?php else: ?>
                <span class="badge badge-orange"><i class="fa-solid fa-circle-exclamation"></i> No</span>
            <?php endif; ?>
        </td>
        <!-- NEW: Thumbmark column -->
        <td style="text-align:center;">
            <?php if(isset($hh['head_has_thumbmark']) && $hh['head_has_thumbmark']): ?>
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
        <td style="text-align:center;">
            <div class="action-group">
                <a href="/beneficiaries/view/<?= $hh['id'] ?>" class="act-btn view" title="View" target="_blank">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="/beneficiaries/edit/<?= $hh['id'] ?>" class="act-btn edit" title="Edit">
                    <i class="fa-solid fa-pen"></i>
                </a>
            </div>
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
      <p>Select filters above and click <strong>Generate</strong> to load the report.</p>
    </div>

    <?php endif; ?>

  </div><!-- /report-body -->

</div><!-- /report-wrap -->

<script>
const streetsByBarangay = <?= json_encode($streetsByBarangay ?? []) ?>;
const currentStatus = '<?= $selectedStatus ?? '' ?>';

document.getElementById('barangayFilter')?.addEventListener('change', function () {
  const barangay = this.value;
  const streetSel = document.getElementById('streetFilter');

  if (barangay && streetsByBarangay[barangay]?.length > 0) {
    streetSel.innerHTML = '<option value="">All Streets</option>';
    streetsByBarangay[barangay].forEach(street => {
      const opt = document.createElement('option');
      opt.value = street;
      opt.textContent = street;
      streetSel.appendChild(opt);
    });
    streetSel.disabled = false;
    streetSel.style.opacity = '1';
    streetSel.style.cursor = 'pointer';
  } else {
    streetSel.innerHTML = '<option value="">All Streets</option>';
    streetSel.disabled = true;
    streetSel.style.opacity = '.4';
    streetSel.style.cursor = 'not-allowed';
  }
});

function applyFilters() {
  const barangay = document.getElementById('barangayFilter').value;
  const street = document.getElementById('streetFilter').value;
  const status = currentStatus; // Preserve status filter
  let url = '/beneficiaries/image-report';
  const params = [];
  if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
  if (street)   params.push('street=' + encodeURIComponent(street));
  if (status)   params.push('status=' + encodeURIComponent(status));
  if (params.length) url += '?' + params.join('&');
  window.location.href = url;
}

function resetFilters() {
  window.location.href = '/beneficiaries/image-report';
}

function applyStatusFilter(status) {
  // Get current barangay and street filters
  const barangay = document.getElementById('barangayFilter').value;
  const street = document.getElementById('streetFilter').value;
  let url = '/beneficiaries/image-report';
  const params = [];
  if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
  if (street)   params.push('street=' + encodeURIComponent(street));
  if (status)   params.push('status=' + encodeURIComponent(status));
  if (params.length) url += '?' + params.join('&');
  window.location.href = url;
}

function clearStatusFilter() {
  // Remove status filter but keep barangay and street
  const barangay = document.getElementById('barangayFilter').value;
  const street = document.getElementById('streetFilter').value;
  let url = '/beneficiaries/image-report';
  const params = [];
  if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
  if (street)   params.push('street=' + encodeURIComponent(street));
  if (params.length) url += '?' + params.join('&');
  window.location.href = url;
}

function exportReport() {
  const barangay = document.getElementById('barangayFilter').value;
  const street = document.getElementById('streetFilter').value;
  const status = currentStatus;
  let url = '/beneficiaries/image-report/export';
  const params = [];
  if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
  if (street)   params.push('street=' + encodeURIComponent(street));
  if (status)   params.push('status=' + encodeURIComponent(status));
  if (params.length) url += '?' + params.join('&');
  window.open(url, '_blank');
}

// Add hover effect to show clickable
document.querySelectorAll('.clickable-filter').forEach(el => {
    el.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.transition = 'transform 0.2s';
    });
    el.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

function printReport() {
  const barangay = document.getElementById('barangayFilter').value;
  const street = document.getElementById('streetFilter').value;
  const status = currentStatus;
  let url = '/beneficiaries/print-image-report';
  const params = [];
  if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
  if (street)   params.push('street=' + encodeURIComponent(street));
  if (status)   params.push('status=' + encodeURIComponent(status));
  if (params.length) url += '?' + params.join('&');
  window.open(url, '_blank');
}
</script>

<?= $this->endSection() ?>