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
  --orange-light: #f9a75a;
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
.mono { font-family: 'DM Mono', monospace; }

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* ── Flash messages ── */
.flash {
  display:flex; align-items:center; gap:10px;
  padding:10px 16px; border-radius:10px; font-size:.75rem; font-weight:500;
  margin-bottom:12px; border:1px solid;
}
.flash.success { background:#f0fdf4; color:var(--green-deep); border-color:var(--green-glow); }
.flash.error   { background:#fff7ed; color:var(--orange-deep); border-color:var(--orange-light); }
.flash-close   { margin-left:auto; background:none; border:none; cursor:pointer; opacity:.5; font-size:.8rem; }
.flash-close:hover { opacity:1; }

/* ── Page header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.2px; }
.page-title p  { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }
.page-actions  { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

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

/* ── KPI Cards ── */
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
.kpi-icon.blue   { background:#eff6ff; color:#1d4ed8; }
.kpi-label { font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--text-3); margin-bottom:2px; }
.kpi-hero .kpi-label { color:rgba(255,255,255,.75); }
.kpi-val { font-size:1.6rem; font-weight:800; line-height:1; color:var(--text-1); font-family:'DM Mono',monospace; }
.kpi-hero .kpi-val { color:#fff; }

/* ── Filter bar ── */
.filter-bar {
  background:var(--surface2); border:1px solid var(--border); border-top:none;
  padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end;
}
.filter-group { display:flex; flex-direction:column; gap:3px; }
.filter-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--text-3); }
.filter-input, .filter-select {
  padding:6px 10px; border:1.5px solid var(--border); border-radius:8px;
  font-size:.72rem; font-family:'Outfit',sans-serif;
  background:var(--surface); color:var(--text-1); transition:border-color .2s;
}
.filter-input:focus, .filter-select:focus { outline:none; border-color:var(--green-mid); box-shadow:0 0 0 3px rgba(119,188,63,.1); }
.filter-select {
  padding-right:28px; appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2364748b'/%3E%3C/svg%3E");
  background-repeat:no-repeat; background-position:right 8px center;
}

/* NEW: view toggle (Latest Taps / Show All) */
.view-toggle { display:flex; border:1.5px solid var(--border); border-radius:8px; overflow:hidden; }
.view-toggle-btn {
  display:inline-flex; align-items:center; gap:5px;
  background:var(--surface); color:var(--text-2); border:none;
  padding:6px 12px; font-size:.7rem; font-weight:600; cursor:pointer;
  font-family:'Outfit',sans-serif; transition:all .2s;
}
.view-toggle-btn + .view-toggle-btn { border-left:1.5px solid var(--border); }
.view-toggle-btn.active {
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff;
}
.view-toggle-btn:hover:not(.active) { background:var(--surface2); color:var(--green-deep); }

/* ── Table card ── */
.table-card {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); overflow:hidden;
}
.table-card-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:12px 20px; border-bottom:1px solid var(--border);
}
.tch-title { font-size:.78rem; font-weight:700; color:var(--text-1); display:flex; align-items:center; gap:6px; }
.tch-title i { color:var(--green-mid); }
.records-badge {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  background:var(--surface2); color:var(--green-deep);
  border:1px solid var(--green-glow); border-radius:20px; padding:3px 10px;
}

/* ── Table ── */
.rt { width:100%; border-collapse:collapse; font-size:.7rem; }
.rt thead th {
  background:var(--bg); padding:8px 10px; text-align:left;
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.55px;
  color:var(--text-3); border-bottom:1px solid var(--border); white-space:nowrap;
}
.rt thead th:first-child { padding-left:20px; }
.rt thead th:last-child  { text-align:right; padding-right:20px; }
.rt tbody tr { transition:background .15s; }
.rt tbody tr:hover { background:var(--surface2); }
.rt td { padding:9px 10px; border-bottom:1px solid var(--border); vertical-align:middle; }
.rt td:first-child { padding-left:20px; }
.rt td:last-child  { text-align:right; padding-right:20px; }

/* ── Avatar ── */
.av {
  width:30px; height:30px; border-radius:9px;
  background:linear-gradient(135deg,var(--green-light),var(--green-mid));
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:.7rem; font-weight:700; flex-shrink:0;
}
.resident-chip { display:flex; align-items:center; gap:8px; }
.res-name { font-weight:700; color:var(--text-1); font-size:.72rem; }
.res-sub  { font-size:.6rem; color:var(--text-3); }

/* ── HH badge ── */
.hh-badge {
  font-family:'DM Mono',monospace; font-size:.62rem;
  background:var(--bg); border:1px solid var(--border);
  border-radius:5px; padding:2px 7px; color:var(--text-2);
}

/* ── Badges ── */
.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 8px; border-radius:20px; font-size:.6rem; font-weight:600;
}
.badge2.green   { background:#f0fdf4; color:var(--green-deep); }
.badge2.orange  { background:#fff7ed; color:var(--orange-deep); }
.badge2.blue    { background:#eff6ff; color:#1d4ed8; }
.badge2.neutral { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }
.badge2.success { background:#f0fdf4; color:var(--green-deep); }
.badge2.muted   { background:#f1f5f9; color:var(--text-3); }

/* ── Time display ── */
.time-in  { font-family:'DM Mono',monospace; font-size:.68rem; font-weight:700; color:var(--text-1); }
.time-out { font-family:'DM Mono',monospace; font-size:.68rem; color:var(--text-3); }

/* ── Checkout btn ── */
.checkout-btn {
  display:inline-flex; align-items:center; gap:4px;
  padding:4px 10px; border-radius:6px; font-size:.62rem; font-weight:600;
  border:1.5px solid var(--orange-light); background:#fff7ed; color:var(--orange-deep);
  text-decoration:none; transition:all .2s; cursor:pointer;
}
.checkout-btn:hover { background:var(--orange-deep); color:#fff; border-color:var(--orange-deep); }

/* ── Empty state ── */
.empty-state { text-align:center; padding:40px 20px; color:var(--text-3); }
.empty-state i { font-size:2rem; display:block; margin-bottom:8px; opacity:.3; }
.empty-state p { font-size:.75rem; margin:0; }

/* ── Dropdown ── */
.dd-menu { font-size:.72rem; border:1px solid var(--border); border-radius:10px; box-shadow:var(--shadow-md); padding:4px; }
.dd-menu .dropdown-item { border-radius:6px; padding:6px 10px; font-size:.7rem; }
.dd-menu .dropdown-item:hover { background:var(--surface2); color:var(--green-deep); }

.family-panel {
    background: var(--surface2);
    border-radius: var(--radius-sm);
    padding: 12px;
    margin: 6px 0;
}

.family-panel-title {
    font-size: .7rem;
    font-weight: 700;
    color: var(--text-2);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.fam-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .65rem;
}

.fam-table th {
    text-align: left;
    padding: 6px 8px;
    background: rgba(0,0,0,.02);
    font-weight: 600;
    color: var(--text-3);
    border-bottom: 1px solid var(--border);
}

.fam-table td {
    padding: 6px 8px;
    border-bottom: 1px solid var(--border);
}

.expand-btn {
    background: none;
    border: 1px solid var(--border);
    border-radius: 4px;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--text-3);
    transition: all .2s;
}

.expand-btn:hover {
    background: var(--green-mid);
    color: #fff;
    border-color: var(--green-mid);
}

.expand-btn.expanded i {
    transform: rotate(90deg);
}

.main-row.expanded-active {
    background: var(--surface2);
}

/* Missing members highlighting */
.main-row.has-missing-members {
    background: linear-gradient(to right, rgba(245, 158, 11, 0.05), transparent);
    border-left: 3px solid #f59e0b;
}

.main-row.has-missing-members:hover {
    background: rgba(245, 158, 11, 0.1);
}

.expand-btn.missing-members {
    position: relative;
    border-color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.missing-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #f59e0b;
    color: white;
    font-size: 0.5rem;
    font-weight: bold;
    min-width: 14px;
    height: 14px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
}

/* Highlight missing family members in the expanded view */
.fam-table tr.missing-member {
    background: rgba(245, 158, 11, 0.05);
}

.fam-table tr.missing-member td:first-child {
    border-left: 3px solid #f59e0b;
}

/* Status badge for missing */
.badge2.warning {
    background: #fffbeb;
    color: #f59e0b;
    border-color: #fcd34d;
}
</style>

<div class="page-wrap">

  <!-- ── Flash Messages ── -->
  <?php if(session()->getFlashdata('success')): ?>
  <div class="flash success" id="flash-success">
    <i class="fa-solid fa-circle-check"></i>
    <?= session()->getFlashdata('success') ?>
    <button class="flash-close" onclick="document.getElementById('flash-success').remove()"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
  <div class="flash error" id="flash-error">
    <i class="fa-solid fa-circle-exclamation"></i>
    <?= session()->getFlashdata('error') ?>
    <button class="flash-close" onclick="document.getElementById('flash-error').remove()"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <?php endif; ?>

  <!-- ── Page Header ── -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-calendar-check me-2" style="color:var(--green-mid)"></i>Attendance List</h5>
      <p>
        <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--green-mid);margin-right:5px;vertical-align:middle"></span>
        Track beneficiary check-ins and check-outs
      </p>
    </div>
    <div class="page-actions">
      <a href="/attendance/scanner" class="btn-outline-c">
        <i class="fa-solid fa-camera"></i> QR Scanner
      </a>
      <a href="/attendance/manual-checkin" class="btn-outline-c">
        <i class="fa-solid fa-pen"></i> Manual Check-in
      </a>
      <div class="dropdown">
        <button class="btn-primary-c dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-download"></i> Export
        </button>
        <ul class="dropdown-menu dd-menu">
          <li>
            <a class="dropdown-item" href="/attendance/export?start_date=<?= date('Y-m-d', strtotime('-30 days')) ?>&end_date=<?= date('Y-m-d') ?>">
              <i class="fa-solid fa-file-csv me-2" style="color:var(--green-mid)"></i>CSV
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- ── KPI Cards ── -->
  <div class="kpi-grid">
    <div class="kpi-card kpi-hero">
      <div class="kpi-icon"><i class="fa-solid fa-calendar-day"></i></div>
      <div class="kpi-label">Today's Attendance</div>
      <div class="kpi-val" id="todayCount"><?= $todayCount ?? 0 ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon orange"><i class="fa-solid fa-clock"></i></div>
      <div class="kpi-label">Currently Inside</div>
      <div class="kpi-val" id="activeNow">0</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon green"><i class="fa-solid fa-calendar-week"></i></div>
      <div class="kpi-label">This Week</div>
      <div class="kpi-val" id="weekCount">0</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon blue"><i class="fa-solid fa-calendar-alt"></i></div>
      <div class="kpi-label">This Month</div>
      <div class="kpi-val" id="monthCount">0</div>
    </div>
  </div>

<!-- ── Filter Bar ── -->
<form method="get" action="/attendance" id="filterForm">
    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">Search</span>
            <input type="text" name="search" class="filter-input" placeholder="Name, HH#, Barangay..." value="<?= $search ?? '' ?>" style="min-width:180px;">
        </div>
        <div class="filter-group">
            <span class="filter-label">Date</span>
            <input type="date" name="date" class="filter-input" value="<?= $selectedDate ?>">
        </div>
        <div class="filter-group">
            <span class="filter-label">Barangay</span>
            <select name="barangay" class="filter-select">
                <option value="">All Barangays</option>
                <?php foreach($barangays as $b): ?>
                    <option value="<?= $b ?>" <?= ($selectedBarangay == $b) ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>
<div class="filter-group">
            <span class="filter-label">Status</span>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="checked_in" <?= ($selectedStatus == 'checked_in') ? 'selected' : '' ?>>Checked In</option>
                <option value="not_present" <?= ($selectedStatus == 'not_present') ? 'selected' : '' ?>>Not Present</option>
            </select>
        </div>
        <!-- NEW: event filter, same pattern as beneficiaries -->
        <div class="filter-group">
            <span class="filter-label">Event</span>
            <select name="event_id" class="filter-select" onchange="this.form.submit()">
                <option value="">All Events</option>
                <?php foreach($events as $ev): ?>
                    <option value="<?= $ev['id'] ?>" <?= ((string)($selectedEvent ?? '') === (string)$ev['id']) ? 'selected' : '' ?>>
                        <?= esc($ev['event_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- NEW: latest-tap vs full-list toggle -->
        <div class="filter-group">
            <span class="filter-label">View</span>
            <div class="view-toggle">
                <button type="button" class="view-toggle-btn <?= ($viewMode ?? 'latest') === 'latest' ? 'active' : '' ?>" onclick="setViewMode('latest')">
                    <i class="fa-solid fa-bolt"></i> Latest Taps
                </button>
                <button type="button" class="view-toggle-btn <?= ($viewMode ?? 'latest') === 'all' ? 'active' : '' ?>" onclick="setViewMode('all')">
                    <i class="fa-solid fa-list"></i> Show All List
                </button>
            </div>
        </div>
        <!-- NEW: carries the current view mode along on Apply/Reset submits -->
        <input type="hidden" name="view" id="viewModeInput" value="<?= esc($viewMode ?? 'latest') ?>">
        <div style="display:flex;gap:6px;align-items:flex-end">
            <button type="submit" class="btn-primary-c">
                <i class="fa-solid fa-filter"></i> Apply Filter
            </button>
            <a href="/attendance" class="btn-outline-c">
                <i class="fa-solid fa-rotate"></i> Reset
            </a>
        </div>
    </div>
</form>

<!-- Per Page Selector -->
<div class="per-page-controls" style="background:var(--surface2); border:1px solid var(--border); border-bottom:none; padding:8px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
    <div class="per-page-selector" style="display:flex; align-items:center; gap:8px;">
        <label style="font-size:.68rem; color:var(--text-3);">Show</label>
        <select id="perPageSelect" class="filter-select" style="width:auto; padding:4px 24px 4px 8px; font-size:.68rem;">
            <option value="10" <?= ($perPage ?? 50) == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= ($perPage ?? 50) == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= ($perPage ?? 50) == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= ($perPage ?? 50) == 100 ? 'selected' : '' ?>>100</option>
            <option value="200" <?= ($perPage ?? 50) == 200 ? 'selected' : '' ?>>200</option>
            <option value="500" <?= ($perPage ?? 50) == 500 ? 'selected' : '' ?>>500</option>
        </select>
        <span style="font-size:.68rem; color:var(--text-3);">entries</span>
    </div>
    <div class="showing-info" style="font-size:.68rem; color:var(--text-3);">
        Showing <?= min(($currentPage ?? 1) * ($perPage ?? 50), ($totalResidents ?? 0)) ?> of <?= $totalResidents ?? 0 ?> entries
    </div>
</div>

<!-- ── Attendance Table ── -->
<div class="table-card">
    <div class="table-card-header">
<div class="tch-title">
            <i class="fa-solid fa-list"></i>
            Attendance Records — <?= date('F j, Y', strtotime($selectedDate)) ?>
            <span style="font-size:.62rem; font-weight:600; color:var(--text-3); margin-left:6px;">
                (<?= ($viewMode ?? 'latest') === 'latest' ? 'Latest Taps' : 'Full List' ?>)
            </span>
        </div>
        <span class="records-badge"><?= count($attendances) ?> records</span>
    </div>
    <div class="table-scroll">
        <table class="rt" id="attendanceTable">
<thead>
    <tr>
        <th class="th-expand" style="width:36px"></th>
        <th>Check In</th>
        <th>Status</th>
        <th>Household #</th>
        <th>Name</th>
        <th>Type</th>
        <th>Contact</th>        <!-- NEW -->
        <th>Barangay</th>       <!-- NEW -->
        <th>Family Size</th>    <!-- NEW -->
        <th>Vulnerable</th>      <!-- NEW -->
    </tr>
</thead>
<tbody>
    <?php if(empty($attendances)): ?>
    <tr>
<td colspan="11"> <!-- Changed from 10 to 11 since we added a column -->
    <div class="empty-state">
        <i class="fa-solid fa-users-slash"></i>
        <p>No beneficiaries found.</p>
    </div>
</td>
    </tr>
    <?php else: ?>
    <?php foreach($attendances as $index => $a): 
        $hasFamilyMembers = !empty($a['family_members']);
        $initials = strtoupper(substr($a['first_name'] ?? '?', 0, 1));
    ?>
<!-- Main row -->
<tr class="main-row <?= ($a['has_missing_family_members'] && $a['checked_in_today']) ? 'has-missing-members' : '' ?>" 
    data-id="<?= $a['id'] ?>" 
    data-resident-id="<?= $a['resident_id'] ?? $a['id'] ?>"
    <?= ($a['has_missing_family_members'] && $a['checked_in_today']) ? 'data-auto-expand="true"' : '' ?>>
    
    <!-- Expand -->
    <td class="td-expand">
        <?php if($hasFamilyMembers): ?>
        <button class="expand-btn <?= ($a['has_missing_family_members'] && $a['checked_in_today']) ? 'missing-members' : '' ?>" 
                onclick="toggleFamily(<?= $a['id'] ?>, this)" 
                title="<?= ($a['has_missing_family_members'] && $a['checked_in_today']) ? 'Has missing family members - Click to view' : 'Show family members' ?>">
            <i class="fa-solid fa-chevron-right"></i>
            <?php if($a['has_missing_family_members'] && $a['checked_in_today']): ?>
                <span class="missing-badge"><?= $a['missing_family_members_count'] ?></span>
            <?php endif; ?>
        </button>
        <?php else: ?>
        <span style="display:block;width:26px;height:26px"></span>
        <?php endif; ?>
    </td>

    <!-- Check In Time -->
    <td>
        <?php if($a['checked_in_today']): ?>
            <span class="time-in"><?= date('h:i A', strtotime($a['check_in_time'])) ?></span>
        <?php else: ?>
            <span class="badge2 neutral">—</span>
        <?php endif; ?>
    </td>

    <!-- Status -->
    <td>
        <?php if($a['checked_in_today'] && !$a['check_out_time']): ?>
            <span class="badge2 success"><i class="fa-solid fa-circle-check"></i>Checked In</span>
            <?php if($a['has_missing_family_members']): ?>
                <span class="badge2 orange" style="margin-left:5px;" title="Missing family members">
                    <i class="fa-solid fa-exclamation-triangle"></i> <?= $a['missing_family_members_count'] ?> missing
                </span>
            <?php endif; ?>
        <?php elseif($a['checked_in_today'] && $a['check_out_time']): ?>
            <span class="badge2 muted"><i class="fa-solid fa-circle-xmark"></i>Checked Out</span>
        <?php else: ?>
            <span class="badge2 neutral"><i class="fa-regular fa-circle"></i>Not Present</span>
        <?php endif; ?>
    </td>

    <!-- Household # -->
    <td>
        <span class="hh-badge"><?= $a['household_no'] ?? '—' ?></span>
    </td>

    <!-- Name -->
    <td>
        <div class="resident-chip">
            <?php if(!empty($a['photo'])): ?>
                <a href="/<?= $a['photo'] ?>" target="_blank" style="text-decoration:none;" title="Click to view full size">
                    <div class="av" style="background:none; overflow:hidden; cursor:pointer; border:1px solid var(--border);">
                        <img src="/<?= $a['photo'] ?>" alt="Photo" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                    </div>
                </a>
            <?php else: ?>
                <div class="av"><?= $initials ?></div>
            <?php endif; ?>
            <div>
                <div class="res-name"><?= $a['last_name'] ?>, <?= $a['first_name'] ?></div>
            </div>
        </div>
    </td>

    <!-- Type -->
    <td>
        <span class="badge2 green">Head of Family</span>
    </td>

    <!-- Contact -->
    <td><span class="mono" style="font-size:.65rem;color:var(--text-3)"><?= $a['contact_number'] ?? '—' ?></span></td>

    <!-- Barangay -->
    <td><span class="badge2 neutral"><?= $a['barangay'] ?? '—' ?></span></td>

    <!-- Family Size -->
    <td>
        <span class="badge2 green">
            <i class="fa-solid fa-users" style="font-size:.55rem"></i>
            <?= $a['family_size'] ?? 1 ?>
        </span>
    </td>

    <!-- Vulnerable -->
    <td>
        <?php if(($a['vulnerable_count'] ?? 0) > 0): ?>
        <span class="badge2 amber">
            <i class="fa-solid fa-heart-pulse" style="font-size:.55rem"></i>
            <?= $a['vulnerable_count'] ?? 0 ?>
        </span>
        <?php else: ?>
        <span style="color:var(--text-3);font-size:.65rem">—</span>
        <?php endif; ?>
    </td>
</tr>

<!-- Family expand row -->
<?php if($hasFamilyMembers): ?>
<tr id="family-<?= $a['id'] ?>" class="family-row" style="display:none">
    <td colspan="11">
        <div class="family-panel">
            <div class="family-panel-title">
                <i class="fa-solid fa-people-group"></i>
                Family Members (<?= count($a['family_members']) ?>) 
                <?php if($a['has_missing_family_members'] && $a['checked_in_today']): ?>
                    <span class="badge2 orange" style="margin-left:5px;">
                        <i class="fa-solid fa-exclamation-triangle"></i> <?= $a['missing_family_members_count'] ?> not present
                    </span>
                <?php endif; ?>
            </div>
            <table class="fam-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Relation</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Check In</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach($a['family_members'] as $member): 
    $memberInitials = strtoupper(substr($member['name'] ?? 'M', 0, 1));
    $isMissing = !$member['checked_in_today'];
?>
<tr class="<?= $isMissing ? 'missing-member' : '' ?>">
    <td>
        <div style="display:flex; align-items:center; gap:8px;">
            <?php if(!empty($member['photo'])): ?>
                <a href="/<?= $member['photo'] ?>" target="_blank" style="text-decoration:none;" title="Click to view full size">
                    <div style="width:28px; height:28px; border-radius:6px; overflow:hidden; flex-shrink:0; cursor:pointer; border:1px solid var(--border); transition:opacity 0.2s;">
                        <img src="/<?= $member['photo'] ?>" alt="Photo" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                </a>
            <?php else: ?>
                <div style="width:28px; height:28px; border-radius:6px; background:linear-gradient(135deg, var(--green-light), var(--green-mid)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:.65rem; font-weight:700; flex-shrink:0;">
                    <?= $memberInitials ?>
                </div>
            <?php endif; ?>
            <span style="font-weight:600;color:var(--text-1)"><?= $member['name'] ?></span>
            <?php if($isMissing): ?>
                <span class="badge2 warning" style="margin-left:5px;">
                    <i class="fa-solid fa-exclamation-triangle"></i> Not Present
                </span>
            <?php endif; ?>
        </div>
    </td>
    <td><?= $member['relation'] ?></td>
    <td><?= $member['age'] ?? '—' ?></td>
    <td><?= $member['sex'] ?? '—' ?></td>
    <td>
        <?php if($member['checked_in_today']): ?>
            <span class="time-in"><?= date('h:i A', strtotime($member['check_in_time'])) ?></span>
        <?php else: ?>
            <span style="color:var(--text-3)">—</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if($member['checked_in_today'] && !$member['check_out_time']): ?>
            <span class="badge2 success"><i class="fa-solid fa-circle-check"></i>Checked In</span>
        <?php elseif($member['checked_in_today'] && $member['check_out_time']): ?>
            <span class="badge2 muted"><i class="fa-solid fa-circle-xmark"></i>Checked Out</span>
        <?php else: ?>
            <span class="badge2 warning"><i class="fa-regular fa-circle"></i>Not Present</span>
        <?php endif; ?>
    </td>
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

<!-- ── Pagination ── -->
<?php if(($totalPages ?? 1) > 1): ?>
<div class="pagination-wrapper" style="display:flex; align-items:center; justify-content:center; gap:5px; flex-wrap:wrap; padding:15px 20px; border-top:1px solid var(--border); background:var(--surface);">
    <?php
    $current = $currentPage ?? 1;
    $total = $totalPages ?? 1;
    $queryParams = $_GET;
    unset($queryParams['page']);
    $baseUrl = '?' . http_build_query($queryParams);
    ?>
    
    <!-- First Page -->
    <?php if($current > 1): ?>
    <a href="<?= $baseUrl . '&page=1' ?>" class="pagination-link" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:var(--surface); color:var(--text-2); text-decoration:none; font-size:.68rem; transition:all .2s;">«</a>
    <?php else: ?>
    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:#f0f0f0; color:var(--text-3); font-size:.68rem; opacity:0.5;">«</span>
    <?php endif; ?>
    
    <!-- Previous Page -->
    <?php if($current > 1): ?>
    <a href="<?= $baseUrl . '&page=' . ($current - 1) ?>" class="pagination-link" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:var(--surface); color:var(--text-2); text-decoration:none; font-size:.68rem;">‹</a>
    <?php else: ?>
    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:#f0f0f0; color:var(--text-3); font-size:.68rem; opacity:0.5;">‹</span>
    <?php endif; ?>
    
    <!-- Page Numbers -->
    <?php
    $startPage = max(1, $current - 2);
    $endPage = min($total, $current + 2);
    
    if($startPage > 1): ?>
    <span style="padding:0 4px; font-size:.65rem; color:var(--text-3);">...</span>
    <?php endif; ?>
    
    <?php for($i = $startPage; $i <= $endPage; $i++): ?>
        <?php if($i == $current): ?>
        <span style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; background:linear-gradient(135deg,var(--green-deep),var(--green-mid)); color:#fff; font-size:.7rem; font-weight:600;"><?= $i ?></span>
        <?php else: ?>
        <a href="<?= $baseUrl . '&page=' . $i ?>" class="pagination-link" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:var(--surface); color:var(--text-2); text-decoration:none; font-size:.68rem;"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    
    <?php if($endPage < $total): ?>
    <span style="padding:0 4px; font-size:.65rem; color:var(--text-3);">...</span>
    <a href="<?= $baseUrl . '&page=' . $total ?>" class="pagination-link" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:var(--surface); color:var(--text-2); text-decoration:none; font-size:.68rem;"><?= $total ?></a>
    <?php endif; ?>
    
    <!-- Next Page -->
    <?php if($current < $total): ?>
    <a href="<?= $baseUrl . '&page=' . ($current + 1) ?>" class="pagination-link" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:var(--surface); color:var(--text-2); text-decoration:none; font-size:.68rem;">›</a>
    <?php else: ?>
    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:#f0f0f0; color:var(--text-3); font-size:.68rem; opacity:0.5;">›</span>
    <?php endif; ?>
    
    <!-- Last Page -->
    <?php if($current < $total): ?>
    <a href="<?= $baseUrl . '&page=' . $total ?>" class="pagination-link" style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:var(--surface); color:var(--text-2); text-decoration:none; font-size:.68rem;">»</a>
    <?php else: ?>
    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 8px; border-radius:6px; border:1.5px solid var(--border); background:#f0f0f0; color:var(--text-3); font-size:.68rem; opacity:0.5;">»</span>
    <?php endif; ?>
    
    <!-- Go to page input -->
    <div style="display:flex; align-items:center; gap:5px; margin-left:10px;">
        <span style="font-size:.65rem; color:var(--text-3);">Go to:</span>
        <input type="number" id="gotoPage" min="1" max="<?= $total ?>" value="<?= $current ?>" style="width:55px; padding:4px 6px; border:1.5px solid var(--border); border-radius:6px; font-size:.65rem; text-align:center;">
        <button onclick="gotoPage()" style="padding:4px 8px; border-radius:6px; border:1.5px solid var(--green-mid); background:var(--surface); color:var(--green-deep); font-size:.65rem; cursor:pointer;">Go</button>
    </div>
</div>
<?php endif; ?>

</div>

<script>
function loadStats() {
  fetch('/attendance/stats')
    .then(r => r.json())
    .then(d => {
      document.getElementById('todayCount').textContent = d.today;
      document.getElementById('activeNow').textContent  = d.active_now;
      document.getElementById('weekCount').textContent  = d.week;
      document.getElementById('monthCount').textContent = d.month;
    });
}
loadStats();
setInterval(loadStats, 30000);

function toggleFamily(id, btn) {
    const fr = document.getElementById('family-' + id);
    const main = document.querySelector(`.main-row[data-id="${id}"]`);
    if(!fr) return;
    
    if (fr.style.display === 'none' || fr.style.display === '') {
        fr.style.display = 'table-row';
        btn.classList.add('expanded');
        main.classList.add('expanded-active');
    } else {
        fr.style.display = 'none';
        btn.classList.remove('expanded');
        main.classList.remove('expanded-active');
    }
}

// Auto-expand rows with missing family members on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.main-row[data-auto-expand="true"]').forEach(row => {
        const btn = row.querySelector('.expand-btn');
        if(btn) {
            setTimeout(() => {
                btn.click();
            }, 500); // Small delay for better UX
        }
    });
});

// Optional: Add click on row to expand/collapse (like resident list)
document.querySelectorAll('.main-row').forEach(row => {
    row.addEventListener('click', function(e) {
        if(e.target.closest('button, a')) return;
        const btn = this.querySelector('.expand-btn');
        if(btn) btn.click();
    });
});

// Optional: Add click on row to expand/collapse (like resident list)
document.querySelectorAll('.main-row').forEach(row => {
    row.addEventListener('click', function(e) {
        if(e.target.closest('button, a')) return;
        const btn = this.querySelector('.expand-btn');
        if(btn) btn.click();
    });
});

document.getElementById('perPageSelect')?.addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', this.value);
    url.searchParams.delete('page'); // Reset to page 1
    window.location.href = url.toString();
});

// Go to specific page
function gotoPage() {
    const pageInput = document.getElementById('gotoPage');
    let page = parseInt(pageInput.value);
    const maxPage = parseInt(pageInput.getAttribute('max'));
    
    if (isNaN(page) || page < 1) page = 1;
    if (page > maxPage) page = maxPage;
    
    const url = new URL(window.location.href);
    url.searchParams.set('page', page);
    window.location.href = url.toString();
}

function setViewMode(mode) {
    document.getElementById('viewModeInput').value = mode;
    document.getElementById('filterForm').submit();
}

// Enter key on goto page input
document.getElementById('gotoPage')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        gotoPage();
    }
});

function loadStats() {
  fetch('/attendance/stats')
    .then(r => r.json())
    .then(d => {
      document.getElementById('todayCount').textContent = d.today;
      document.getElementById('activeNow').textContent  = d.active_now;
      document.getElementById('weekCount').textContent  = d.week;
      document.getElementById('monthCount').textContent = d.month;
    });
}
loadStats();
setInterval(loadStats, 30000);

function toggleFamily(id, btn) {
    const fr = document.getElementById('family-' + id);
    const main = document.querySelector(`.main-row[data-id="${id}"]`);
    if(!fr) return;
    
    if (fr.style.display === 'none' || fr.style.display === '') {
        fr.style.display = 'table-row';
        btn.classList.add('expanded');
        main.classList.add('expanded-active');
    } else {
        fr.style.display = 'none';
        btn.classList.remove('expanded');
        main.classList.remove('expanded-active');
    }
}

// Auto-expand rows with missing family members on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.main-row[data-auto-expand="true"]').forEach(row => {
        const btn = row.querySelector('.expand-btn');
        if(btn) {
            setTimeout(() => {
                btn.click();
            }, 500);
        }
    });
});

// Optional: Add click on row to expand/collapse
document.querySelectorAll('.main-row').forEach(row => {
    row.addEventListener('click', function(e) {
        if(e.target.closest('button, a')) return;
        const btn = this.querySelector('.expand-btn');
        if(btn) btn.click();
    });
});
</script>

<?= $this->endSection() ?>