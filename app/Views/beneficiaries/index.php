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
  --shadow-lg:    0 12px 40px rgba(119,188,63,.16);
  --radius:       14px;
  --radius-sm:    8px;
}

* { box-sizing: border-box; }
body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text-1); }
.mono { font-family: 'DM Mono', monospace; }

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }

/* ── Flash messages ── */
.flash {
  display:flex; align-items:center; gap:10px;
  padding:10px 16px; border-radius:10px; font-size:.75rem; font-weight:500;
  margin-bottom:12px; border:1px solid;
}
.flash.success { background:#e8f5ee; color:var(--green-deep); border-color:#b2dfc2; }
.flash.error   { background:#fdf0ee; color:var(--orange-deep); border-color:#f7c5ae; }
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
  box-shadow:0 4px 12px rgba(26,92,46,.3); font-family:'Outfit',sans-serif;
}
.btn-primary-c:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(26,92,46,.4); color:#fff; }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:#e8f5ee; }

.btn-orange-c {
  display:inline-flex; align-items:center; gap:5px;
  background:linear-gradient(135deg,var(--orange-deep),var(--orange-mid));
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
  box-shadow:0 3px 10px rgba(192,74,18,.25);
}
.btn-orange-c:hover { color:#fff; transform:translateY(-1px); }

/* ── Filter bar ── */
.filter-bar {
  background:var(--surface2); border:1px solid var(--border); border-top:none;
  padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;
}
.filter-input-wrap {
  position:relative; flex:1; min-width:200px; max-width:320px;
}
.filter-input-wrap i { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-3); font-size:.72rem; }
.filter-input {
  width:100%; padding:6px 10px 6px 30px; border:1.5px solid var(--border);
  border-radius:8px; font-size:.72rem; font-family:'Outfit',sans-serif;
  background:var(--surface); color:var(--text-1); transition:border-color .2s;
}
.filter-input:focus { outline:none; border-color:var(--green-mid); box-shadow:0 0 0 3px rgba(119,188,63,.1); }
.filter-select {
  padding:6px 28px 6px 10px; border:1.5px solid var(--border);
  border-radius:8px; font-size:.72rem; font-family:'Outfit',sans-serif;
  background:var(--surface); color:var(--text-1); cursor:pointer;
  appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2364748b'/%3E%3C/svg%3E");
  background-repeat:no-repeat; background-position:right 8px center; transition:border-color .2s;
}
.filter-select:focus { outline:none; border-color:var(--green-mid); box-shadow:0 0 0 3px rgba(119,188,63,.1); }
.reset-btn {
  display:inline-flex; align-items:center; gap:5px;
  padding:6px 12px; border-radius:8px; border:1.5px solid var(--border);
  background:var(--surface); color:var(--text-3); font-size:.7rem; font-weight:600;
  cursor:pointer; font-family:'Outfit',sans-serif; transition:all .2s;
}
.reset-btn:hover { border-color:var(--orange-mid); color:var(--orange-deep); background:#fef0e8; }

.stats-card {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;
}
.stats-card .stat-block { display:flex; flex-direction:column; gap:4px; }
.stats-card .stat-label { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-3); }
.stats-card .stat-value { font-size:1.15rem; font-weight:800; color:var(--green-deep); }
.stats-card .stat-value small { font-size:.7rem; font-weight:600; color:var(--text-3); }
.stats-progress { flex:1; min-width:180px; }
.stats-progress-bar { height:8px; border-radius:99px; background:var(--border); overflow:hidden; }
.stats-progress-fill { height:100%; background:linear-gradient(135deg,var(--green-deep),var(--green-mid)); border-radius:99px; transition:width .3s; }
.stats-progress-pct { font-size:.65rem; color:var(--text-3); margin-top:5px; }

.dist-toggle {
  display:inline-flex; border:1.5px solid var(--border); border-radius:8px; overflow:hidden;
}
.dist-toggle .toggle-btn {
  padding:6px 12px; font-size:.7rem; font-weight:600; font-family:'Outfit',sans-serif;
  background:var(--surface); color:var(--text-3); border:none; cursor:pointer; transition:all .2s;
  display:inline-flex; align-items:center; gap:5px;
}
.dist-toggle .toggle-btn:not(:last-child) { border-right:1.5px solid var(--border); }
.dist-toggle .toggle-btn:hover { background:#e8f5ee; color:var(--green-deep); }
.dist-toggle .toggle-btn.active {
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid)); color:#fff;
}

/* ── Table wrapper ── */
.table-wrap {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  overflow:hidden;
}
.table-scroll { overflow-x:auto; }

/* ── Table ── */
.rt { width:100%; border-collapse:collapse; font-size:.7rem; }
.rt thead th {
  background:var(--bg); padding:8px 10px; text-align:left;
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.55px;
  color:var(--text-3); border-bottom:1px solid var(--border); white-space:nowrap;
}
.rt thead th:first-child { padding-left:16px; }
.rt thead th.th-expand { width:36px; text-align:center; padding-left:4px; padding-right:4px; }
.rt thead th:last-child  { text-align:right; padding-right:16px; }

.rt tbody tr.main-row { cursor:pointer; transition:background .15s; }
.rt tbody tr.main-row:hover { background:var(--surface2); }
.rt tbody tr.main-row.expanded-active {
  background:#e8f5ee !important;
  border-left:3px solid var(--orange-mid);
}
.rt td { padding:9px 10px; border-bottom:1px solid var(--border); vertical-align:middle; }
.rt td:first-child { padding-left:16px; }
.rt td.td-expand { padding:4px; text-align:center; vertical-align:middle; }
.rt td:last-child  { text-align:right; padding-right:16px; }

/* ── Checkbox ── */
.rt-check {
  width:14px; height:14px; accent-color:var(--green-mid); cursor:pointer;
}

/* ── Expand button ── */
.expand-btn {
  background:none; border:none; cursor:pointer;
  color:var(--text-3); font-size:.72rem; padding:4px 6px;
  border-radius:6px; transition:all .2s; line-height:1;
  display:flex; align-items:center; justify-content:center;
  width:26px; height:26px; margin:0 auto;
}
.expand-btn:hover { background:#e8f5ee; color:var(--green-deep); }
.expand-btn i { transition:transform .25s; display:block; }
.expand-btn.expanded i { transform:rotate(90deg); color:var(--orange-mid); }

/* ── Avatar ── */
.av {
  width:30px; height:30px; border-radius:9px;
  background:linear-gradient(135deg,var(--green-light),var(--green-mid));
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:.7rem; font-weight:700; flex-shrink:0;
}
.av.orange { background:linear-gradient(135deg,var(--orange-mid),var(--orange-deep)); }

.resident-chip { display:flex; align-items:center; gap:8px; }
.res-name { font-weight:700; color:var(--text-1); font-size:.72rem; }
.res-sub  { font-size:.6rem; color:var(--text-3); }

/* ── HH badge ── */
.hh-badge {
  font-family:'DM Mono',monospace; font-size:.62rem;
  background:var(--bg); border:1px solid var(--border);
  border-radius:5px; padding:2px 7px; color:var(--text-2);
}
.hh-qr-link { color:var(--text-3); text-decoration:none; margin-left:4px; font-size:.65rem; transition:color .15s; }
.hh-qr-link:hover { color:var(--green-deep); }

/* ── Badges ── */
.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 7px; border-radius:20px; font-size:.6rem; font-weight:600;
}
.badge2.green   { background:#e8f5ee; color:var(--green-deep); }
.badge2.orange  { background:#fef0e8; color:var(--orange-deep); }
.badge2.amber   { background:#fff8e8; color:#c08000; }
.badge2.neutral { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }
.badge2.blue    { background:#eff6ff; color:#1d4ed8; }
.badge2.gray    { background:var(--bg); color:var(--text-3); border:1px solid var(--border); }

/* ── Action buttons ── */
.action-group { display:inline-flex; gap:4px; justify-content:flex-end; }
.act-btn {
  width:26px; height:26px; border-radius:6px; border:1.5px solid var(--border);
  background:var(--surface); display:flex; align-items:center; justify-content:center;
  font-size:.65rem; cursor:pointer; text-decoration:none; transition:all .15s; color:var(--text-3);
}
.act-btn:hover { border-color:var(--green-mid); background:#e8f5ee; color:var(--green-deep); }
.act-btn.view:hover   { border-color:#1a56a0; background:#e8f0fe; color:#1a56a0; }
.act-btn.dist:hover   { border-color:var(--orange-mid); background:#fef0e8; color:var(--orange-deep); }

/* ── Family expand row ── */
.family-row td { padding:0; border:none; }
.family-panel {
  padding:12px 16px 14px 56px;
  border-left:3px solid var(--orange-mid);
  background:linear-gradient(90deg,#fff8f4,var(--surface2));
  animation:slideDown .2s ease;
}
@keyframes slideDown { from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:none} }
.family-panel-title {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px;
  color:var(--orange-deep); margin-bottom:8px; display:flex; align-items:center; gap:6px;
}
.fam-table { width:100%; border-collapse:collapse; font-size:.67rem; table-layout:fixed; }
.fam-table thead th {
  font-size:.58rem; text-transform:uppercase; letter-spacing:.5px;
  color:var(--text-3); font-weight:700; padding:5px 10px; border-bottom:1px solid var(--border);
  background:transparent; text-align:left; vertical-align:middle; white-space:nowrap;
}
.fam-table thead th:last-child { text-align:center; }
.fam-table td { padding:6px 10px; border-bottom:1px solid rgba(212,230,218,.5); color:var(--text-2); vertical-align:middle; text-align:left; }
.fam-table td:last-child { text-align:center; }
.fam-table tbody tr:last-child td { border-bottom:none; }
.fam-table tbody tr:hover td { background:rgba(255,255,255,.7); }
.member-id-badge {
  font-family:'DM Mono',monospace; font-size:.6rem;
  background:var(--bg); border:1px solid var(--border);
  border-radius:4px; padding:1px 6px; color:var(--text-2);
}

/* ── Footer bar ── */
.table-footer {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius);
  padding:10px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;
}
.footer-info { font-size:.68rem; color:var(--text-3); }
.footer-info strong { color:var(--green-deep); }
.footer-actions { display:flex; gap:6px; flex-wrap:wrap; }
.footer-btn {
  display:inline-flex; align-items:center; gap:5px;
  padding:5px 12px; border-radius:7px; border:1.5px solid var(--border);
  background:var(--surface); font-size:.68rem; font-weight:600; color:var(--text-2);
  cursor:pointer; font-family:'Outfit',sans-serif; transition:all .2s;
}
.footer-btn:hover { border-color:var(--green-mid); color:var(--green-deep); background:#e8f5ee; }
.footer-btn:disabled { opacity:.4; cursor:not-allowed; }

/* ── Empty state ── */
.empty-state { text-align:center; padding:40px 20px; color:var(--text-3); }
.empty-state i { font-size:2rem; display:block; margin-bottom:8px; opacity:.3; }
.empty-state p { font-size:.75rem; margin:0; }

/* ── Dropdown ── */
.dd-menu { font-size:.72rem; border:1px solid var(--border); border-radius:10px; box-shadow:var(--shadow-md); padding:4px; }
.dd-menu .dropdown-item { border-radius:6px; padding:6px 10px; font-size:.7rem; }
.dd-menu .dropdown-item:hover { background:#e8f5ee; color:var(--green-deep); }

/* ── Modal ── */
.modal-card { border:none; border-radius:var(--radius); box-shadow:var(--shadow-lg); font-family:'Outfit',sans-serif; }
.modal-card .modal-header { background:var(--surface2); border-bottom:1px solid var(--border); border-radius:var(--radius) var(--radius) 0 0; padding:14px 20px; }
.modal-card .modal-title { font-size:.85rem; font-weight:800; color:var(--text-1); }
.modal-card .modal-body { padding:20px; }
.modal-card .modal-footer { background:var(--bg); border-top:1px solid var(--border); border-radius:0 0 var(--radius) var(--radius); padding:10px 20px; }
.modal-av {
  width:64px; height:64px; border-radius:16px; margin:0 auto 12px;
  background:linear-gradient(135deg,var(--green-light),var(--green-mid));
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:1.4rem; font-weight:700;
}
.modal-info-block { background:var(--bg); border-radius:var(--radius-sm); padding:12px 14px; margin-bottom:12px; }
.modal-info-block h6 { font-size:.58rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--text-3); margin-bottom:8px; }
.modal-info-row { display:flex; justify-content:space-between; font-size:.7rem; margin-bottom:4px; }
.modal-info-row span:first-child { color:var(--text-3); }
.modal-info-row span:last-child { font-weight:600; color:var(--text-1); }
.modal-contact-row { display:flex; align-items:center; gap:8px; font-size:.72rem; color:var(--text-2); margin-bottom:6px; }
.modal-contact-row i { color:var(--green-mid); width:16px; text-align:center; font-size:.7rem; }
</style>

<!-- Flash Messages -->
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

<?php $familyMemberModel = new \App\Models\FamilyMemberModel(); ?>

<div class="page-wrap">

  <!-- ── Page Header ── -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-users me-2" style="color:var(--green-mid)"></i>Master List of Beneficiaries</h5>
<p>
  <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--green-light);margin-right:5px;vertical-align:middle"></span>
  <?php if($distribution_type === 'regular'): ?>
  Total: <strong style="color:var(--green-deep)"><?= count($residents) ?></strong> registered families claimed on <?= date('M d, Y', strtotime($selected_date)) ?>
  <?php else: ?>
  Total: <strong style="color:var(--green-deep)"><?= count($kitchen_claims) ?></strong> meals claimed on <?= date('M d, Y', strtotime($selected_date)) ?>
  <?php endif; ?>
</p>
    </div>
    <div class="page-actions">
      <div class="dropdown">
        <button class="btn-outline-c dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="fa-solid fa-download"></i> Export
        </button>
        <ul class="dropdown-menu dd-menu">
          <li><a class="dropdown-item" href="/beneficiaries/export/csv"><i class="fa-solid fa-file-csv me-2" style="color:var(--green-mid)"></i>CSV</a></li>
          <li><a class="dropdown-item" href="/beneficiaries/export/pdf"><i class="fa-solid fa-file-pdf me-2" style="color:var(--orange-mid)"></i>PDF</a></li>
          <li><hr class="dropdown-divider" style="margin:4px 0"></li>
          <li><a class="dropdown-item" href="/beneficiaries/print"><i class="fa-solid fa-print me-2" style="color:var(--text-3)"></i>Print All</a></li>
        </ul>
      </div>
</div>
  </div>

<!-- ── Stats Card ── -->
  <div class="stats-card">
    <div class="stat-block">
      <span class="stat-label">Total Beneficiaries</span>
      <span class="stat-value"><?= $total_residents ?></span>
    </div>
    <div class="stat-block">
      <span class="stat-label">Beneficiaries Claimed</span>
      <span class="stat-value"><?= $claimed_beneficiaries_count ?></span>
    </div>
    <?php if($distribution_type === 'kitchen' && $guest_claims_count > 0): ?>
    <div class="stat-block">
      <span class="stat-label">Guest Claims</span>
      <span class="stat-value"><?= $guest_claims_count ?></span>
    </div>
        <div class="stat-block">
      <span class="stat-label">Total Claimed</span>
      <span class="stat-value"><?= $total_claimed_count ?></span>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── Filter Bar ── -->
  <div class="filter-bar">
    <div class="filter-input-wrap">
      <i class="fa-solid fa-search"></i>
      <input type="text" class="filter-input" id="searchInput" placeholder="Search by name, household #, or barangay...">
    </div>
    <select class="filter-select" id="barangayFilter">
      <option value="">All Barangays</option>
      <?php
      $barangays = array_unique(array_column($residents, 'barangay'));
      foreach($barangays as $barangay): if(!empty($barangay)):
      ?>
      <option value="<?= $barangay ?>"><?= $barangay ?></option>
      <?php endif; endforeach; ?>
    </select>

    <!-- NEW: record date -->
    <input type="date" class="filter-input" style="max-width:150px" id="dateFilter"
           value="<?= esc($selected_date) ?>" onchange="applyServerFilters()">

    <!-- NEW: event dropdown -->
    <select class="filter-select" id="eventFilter" onchange="applyServerFilters()">
      <option value="">All Events</option>
      <?php foreach($events as $ev): ?>
      <option value="<?= $ev['id'] ?>" <?= ((string)$selected_event === (string)$ev['id']) ? 'selected' : '' ?>>
        <?= esc($ev['event_name']) ?>
      </option>
      <?php endforeach; ?>
    </select>

    <!-- NEW: regular vs kitchen toggle -->
    <div class="dist-toggle" id="distTypeToggle" data-selected-type="<?= esc($distribution_type) ?>">
      <button type="button" class="toggle-btn <?= $distribution_type === 'regular' ? 'active' : '' ?>"
              onclick="setDistributionType('regular')">
        <i class="fa-solid fa-box"></i> Regular
      </button>
      <button type="button" class="toggle-btn <?= $distribution_type === 'kitchen' ? 'active' : '' ?>"
              onclick="setDistributionType('kitchen')">
        <i class="fa-solid fa-utensils"></i> Kitchen
      </button>
    </div>

    <button class="reset-btn" onclick="resetFilters()">
      <i class="fa-solid fa-rotate"></i> Reset
    </button>
  </div>

<!-- ── Table ── -->
<?php if($distribution_type === 'regular'): ?>
  <div class="table-wrap">
    <div class="table-scroll">
      <table class="rt" id="beneficiariesTable">
        <thead>
          <tr>
            <th><input class="rt-check" type="checkbox" id="selectAll"></th>
            <th class="th-expand"></th>
            <th>Household #</th>
            <th>Family Head</th>
            <th>Contact</th>
            <th>Barangay</th>
            <th>Family Size</th>
            <th>Vulnerable</th>
            <th>Status</th>
            <th style="text-align:right;padding-right:16px">Distributor</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($residents)): ?>
          <tr>
            <td colspan="10">
              <div class="empty-state">
                <i class="fa-solid fa-users-slash"></i>
                <p>No beneficiaries found.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
          <?php foreach($residents as $index => $r):
            $familyMembers = $familyMemberModel->getByResidentId($r['id']);
            $hasFamilyMembers = !empty($familyMembers);
          ?>
          <tr class="beneficiary-row main-row"
              data-search="<?= strtolower($r['last_name'] . ' ' . $r['first_name'] . ' ' . $r['household_no'] . ' ' . ($r['barangay'] ?? '')) ?>"
              data-barangay="<?= $r['barangay'] ?? '' ?>"
              data-status="<?= $r['status'] ?>"
              data-shelter="<?= $r['shelter_damage'] ?? 'No Damage' ?>"
              data-id="<?= $r['id'] ?>">
            <!-- Checkbox -->
            <td><input class="rt-check row-checkbox" type="checkbox" value="<?= $r['id'] ?>"></td>
            <!-- Expand -->
            <td class="td-expand">
              <?php if($hasFamilyMembers): ?>
              <button class="expand-btn" onclick="toggleFamily(<?= $r['id'] ?>, this)" title="Show family members">
                <i class="fa-solid fa-chevron-right"></i>
              </button>
              <?php endif; ?>
            </td>
            <!-- Household # -->
            <td>
              <span class="hh-badge"><?= $r['household_no'] ?></span>
              <?php if(!empty($r['qr_code_token'])): ?>
              <a href="/residentportal/generateQR/<?= $r['id'] ?>" class="hh-qr-link" target="_blank" title="View QR">
                <i class="fa-solid fa-qrcode"></i>
              </a>
              <?php endif; ?>
            </td>
            <!-- Family Head -->
            <td>
              <div class="resident-chip">
                <?php if(!empty($r['photo'])): ?>
                  <a href="/<?= $r['photo'] ?>" target="_blank" style="text-decoration:none;" title="Click to view full size">
                    <div class="av" style="background:none; overflow:hidden; cursor:pointer; border:1px solid var(--border);">
                      <img src="/<?= $r['photo'] ?>" alt="Photo" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                    </div>
                  </a>
                <?php else: ?>
                  <div class="av"><?= strtoupper(substr($r['first_name'] ?? $r['full_name'], 0, 1)) ?></div>
                <?php endif; ?>
                <div>
                  <div class="res-name"><?= $r['last_name'] ?>, <?= $r['first_name'] ?></div>
                  <div class="res-sub"><?= $r['age'] ?> yrs old &bull; <?= $r['sex'] ?></div>
                </div>
              </div>
            </td>
            <!-- Contact -->
            <td style="font-size:.68rem;color:var(--text-3)"><?= $r['contact_number'] ?: '—' ?></td>
            <!-- Barangay -->
            <td><span class="badge2 neutral"><?= $r['barangay'] ?: '—' ?></span></td>
            <!-- Family Size -->
            <td>
              <span class="badge2 green">
                <i class="fa-solid fa-users" style="font-size:.55rem"></i>
                <?= $r['family_size'] ?? 1 ?>
              </span>
            </td>
            <!-- Vulnerable -->
            <td>
              <?php
              $vulnerableCount = ($r['vulnerable_older_persons'] ?? 0) + ($r['vulnerable_pregnant'] ?? 0) + ($r['vulnerable_lactating'] ?? 0) + ($r['vulnerable_pwd'] ?? 0);
              if($vulnerableCount > 0): ?>
              <span class="badge2 amber">
                <i class="fa-solid fa-heart-pulse" style="font-size:.55rem"></i>
                <?= $vulnerableCount ?>
              </span>
              <?php else: ?>
              <span style="color:var(--text-3);font-size:.65rem">—</span>
              <?php endif; ?>
            </td>
            <!-- Status -->
            <td>
              <?php if($r['head_claimed_today']): ?>
              <span class="badge2 green" title="Claimed at <?= date('h:i A', strtotime($r['head_claimed_at'])) ?>">
                <i class="fa-regular fa-circle-check" style="font-size:.55rem"></i> Claimed Today
              </span>
              <?php else: ?>
              <span class="badge2 gray">
                <i class="fa-regular fa-clock" style="font-size:.55rem"></i> Not Yet Claimed
              </span>
              <?php endif; ?>
            </td>
            <!-- Distributor -->
            <td>
              <div class="action-group">
                <?php if($r['head_claimed_today'] && isset($r['distributor_id'])): ?>
                <button type="button" class="act-btn dist" onclick="showDistributorInfo(<?= $r['distributor_id'] ?>, <?= $r['id'] ?>)" title="View distributor info">
                  <i class="fa-solid fa-user-check"></i>
                </button>
                <?php else: ?>
                <span style="color:var(--text-3);font-size:.65rem;padding-right:8px">—</span>
                <?php endif; ?>
              </div>
            </td>
          </tr>

          <!-- Family Row -->
          <?php if($hasFamilyMembers): ?>
          <tr id="family-<?= $r['id'] ?>" class="family-row" style="display:none">
            <td colspan="10">
              <div class="family-panel">
                <div class="family-panel-title">
                  <i class="fa-solid fa-people-group"></i>
                  Family Members (<?= count($familyMembers) ?>)
                </div>
                <table class="fam-table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Relation</th>
                      <th>Age</th>
                      <th>Sex</th>
                      <th>Education</th>
                      <th>Occupation</th>
                      <th>QR</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($familyMembers as $member):
                      $memberInitials = strtoupper(substr($member['name'] ?? 'M', 0, 1));
                    ?>
                    <tr>
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
                        </div>
                      </td>
                      <td><?= $member['relation'] ?></td>
                      <td><?= $member['age'] ?></td>
                      <td><?= $member['sex'] ?></td>
                      <td><?= $member['education'] ?></td>
                      <td><?= $member['occupation'] ?></td>
                      <td>
                        <?php if(!empty($member['qr_code_token'])): ?>
                        <a href="/residentportal/view-member-qr/<?= $r['id'] ?>/<?= $member['id'] ?>"
                           class="act-btn view" target="_blank" title="View QR" style="display:inline-flex;margin:0 auto">
                          <i class="fa-solid fa-qrcode"></i>
                        </a>
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
<?php else: ?>
  <!-- Kitchen distribution: ONE ROW PER CLAIM (head, family member, or guest) -->
  <div class="table-wrap">
    <div class="table-scroll">
      <table class="rt" id="kitchenClaimsTable">
        <thead>
          <tr>
            <th>Claimant</th>
            <th>Type</th>
            <th>Household #</th>
            <th>Barangay</th>
            <th>Claimed At</th>
            <th style="text-align:right;padding-right:16px">Distributor</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($kitchen_claims)): ?>
          <tr>
            <td colspan="6">
              <div class="empty-state">
                <i class="fa-solid fa-utensils"></i>
                <p>No kitchen claims found for this date.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
          <?php foreach($kitchen_claims as $c):
if ($c['claimant_type'] === 'guest') {
    $claimantName = !empty($c['remarks']) ? $c['remarks'] : 'Guest'; // remarks already holds "Guest 1", "Guest 2", etc.
    $photo = null;
    $initials = 'G';
} elseif ($c['claimant_type'] === 'family_member') {
                $claimantName = $c['family_member_name'];
                $photo = $c['member_photo'] ?? null;
                $initials = strtoupper(substr($claimantName ?? 'M', 0, 1));
            } else {
                $claimantName = trim(($c['head_first_name'] ?? '') . ' ' . ($c['head_last_name'] ?? ''));
                $photo = $c['head_photo'] ?? null;
                $initials = strtoupper(substr($c['head_first_name'] ?? 'H', 0, 1));
            }
          ?>
          <tr class="beneficiary-row main-row"
              data-search="<?= strtolower($claimantName . ' ' . ($c['household_no'] ?? '') . ' ' . ($c['barangay'] ?? '')) ?>"
              data-barangay="<?= $c['barangay'] ?? '' ?>">
            <td>
              <div class="resident-chip">
                <?php if(!empty($photo)): ?>
                  <div class="av" style="background:none; overflow:hidden; border:1px solid var(--border);">
                    <img src="/<?= $photo ?>" alt="Photo" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                  </div>
                <?php else: ?>
                  <div class="av"><?= $initials ?></div>
                <?php endif; ?>
                <div>
                  <div class="res-name"><?= esc($claimantName ?: 'Guest') ?></div>
                  <?php if($c['claimant_type'] === 'family_member' && !empty($c['relation'])): ?>
                  <div class="res-sub"><?= esc($c['relation']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </td>
            <td>
              <?php if($c['claimant_type'] === 'head'): ?>
                <span class="badge2 green"><i class="fa-solid fa-user" style="font-size:.55rem"></i> Head</span>
              <?php elseif($c['claimant_type'] === 'family_member'): ?>
                <span class="badge2 neutral"><i class="fa-solid fa-people-group" style="font-size:.55rem"></i> Member</span>
              <?php else: ?>
                <span class="badge2 amber"><i class="fa-solid fa-user-plus" style="font-size:.55rem"></i> Guest</span>
              <?php endif; ?>
            </td>
            <td><?= $c['household_no'] ?? '—' ?></td>
            <td><span class="badge2 neutral"><?= $c['barangay'] ?? '—' ?></span></td>
            <td style="font-size:.68rem;color:var(--text-3)"><?= date('h:i A', strtotime($c['distribution_date'])) ?></td>
            <td style="text-align:right;padding-right:16px;font-size:.68rem;color:var(--text-3)"><?= esc($c['distributor_name'] ?? '—') ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
    
<!-- ── Footer ── -->
<?php if($distribution_type === 'regular' && !empty($residents)): ?>
<div class="table-footer">
  <div class="footer-info">
    <strong id="selectedCount">0</strong> of <?= count($residents) ?> selected
  </div>
  <div class="footer-actions">
    <button class="footer-btn" onclick="expandAll()"><i class="fa-solid fa-chevron-down"></i> Expand All</button>
    <button class="footer-btn" onclick="collapseAll()"><i class="fa-solid fa-chevron-up"></i> Collapse All</button>
    <button class="footer-btn" onclick="bulkPrintQR()" disabled id="bulkPrintBtn"><i class="fa-solid fa-qrcode"></i> Print QR</button>
    <button class="footer-btn" onclick="bulkExport()" disabled id="bulkExportBtn"><i class="fa-solid fa-file-export"></i> Export Selected</button>
  </div>
</div>
<?php elseif($distribution_type === 'kitchen' && !empty($kitchen_claims)): ?>
<div class="table-footer">
  <div class="footer-info">
    <strong><?= count($kitchen_claims) ?></strong> claim<?= count($kitchen_claims) === 1 ? '' : 's' ?> recorded
  </div>
</div>
<?php endif; ?>

</div>



<!-- ── Distributor Information Modal ── -->
<div class="modal fade" id="distributorInfoModal" tabindex="-1" aria-labelledby="distributorInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-card">
      <div class="modal-header">
        <h5 class="modal-title" id="distributorInfoModalLabel">
          <i class="fa-solid fa-user-circle me-2" style="color:var(--green-mid)"></i>Distributor Information
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="distributorInfoContent">
        <div style="text-align:center;padding:24px 0">
          <div class="spinner-border" role="status" style="color:var(--green-mid);width:1.5rem;height:1.5rem;border-width:2px">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p style="margin-top:10px;font-size:.72rem;color:var(--text-3)">Loading distributor information...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="footer-btn" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('barangayFilter').addEventListener('change', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('shelterFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const barangay = document.getElementById('barangayFilter').value;
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.main-row');
    
    rows.forEach(row => {
        const matchesSearch = (row.dataset.search || '').includes(searchTerm);
        const matchesBarangay = !barangay || row.dataset.barangay === barangay;
        const matchesStatus = !status || row.dataset.status === status;
        
        const familyRow = document.getElementById('family-' + row.dataset.id);
        if (matchesSearch && matchesBarangay && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
            if (familyRow) familyRow.style.display = 'none';
        }
    });
}


// NEW: server-side filters (date / event / type) — reload page with query params
function applyServerFilters() {
    const date    = document.getElementById('dateFilter').value;
    const eventId = document.getElementById('eventFilter').value;
    const type    = document.getElementById('distTypeToggle').dataset.selectedType;

    const params = new URLSearchParams();
    if (date) params.set('date', date);
    if (eventId) params.set('event_id', eventId);
    params.set('type', type);

    window.location.href = '/beneficiaries?' + params.toString();
}

// NEW: toggle regular vs kitchen distribution
function setDistributionType(type) {
    document.getElementById('distTypeToggle').dataset.selectedType = type;
    applyServerFilters();
}
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('barangayFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();

    // NEW: also clear server-side filters and reload to today/regular/all events
    window.location.href = '/beneficiaries';
}

function toggleFamily(id, btn) {
    const familyRow = document.getElementById('family-' + id);
    const mainRow = document.querySelector(`.main-row[data-id="${id}"]`);
    if (familyRow) {
        if (familyRow.style.display === 'none') {
            familyRow.style.display = 'table-row';
            btn.classList.add('expanded');
            mainRow.classList.add('expanded-active');
        } else {
            familyRow.style.display = 'none';
            btn.classList.remove('expanded');
            mainRow.classList.remove('expanded-active');
        }
    }
}

function expandAll() {
    document.querySelectorAll('.expand-btn').forEach(btn => {
        const id = btn.closest('.main-row').dataset.id;
        const familyRow = document.getElementById('family-' + id);
        if (familyRow) {
            familyRow.style.display = 'table-row';
            btn.classList.add('expanded');
            btn.closest('.main-row').classList.add('expanded-active');
        }
    });
}

function collapseAll() {
    document.querySelectorAll('.expand-btn').forEach(btn => {
        const id = btn.closest('.main-row').dataset.id;
        const familyRow = document.getElementById('family-' + id);
        if (familyRow) {
            familyRow.style.display = 'none';
            btn.classList.remove('expanded');
            btn.closest('.main-row').classList.remove('expanded-active');
        }
    });
}

document.getElementById('selectAll').addEventListener('change', function(e) {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        if (cb.closest('.main-row').style.display !== 'none') cb.checked = e.target.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('.row-checkbox').forEach(cb => cb.addEventListener('change', updateSelectedCount));

function updateSelectedCount() {
    const selected = document.querySelectorAll('.row-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selected;
    document.getElementById('bulkPrintBtn').disabled = selected === 0;
    document.getElementById('bulkExportBtn').disabled = selected === 0;
}

function bulkPrintQR() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value).join(',');
    window.open('/residentportal/bulk-qr/' + ids, '_blank');
}

function bulkExport() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value).join(',');
    window.location.href = '/beneficiaries/export-selected/' + ids;
}

document.querySelectorAll('.main-row').forEach(row => {
    row.addEventListener('click', function(e) {
        if (e.target.closest('.form-check') || e.target.closest('button') || e.target.closest('a')) return;
        const expandBtn = this.querySelector('.expand-btn');
        if (expandBtn) expandBtn.click();
    });
});

// Function to show distributor information
function showDistributorInfo(distributorId, residentId) {
    // Show modal with loading spinner
    const modal = new bootstrap.Modal(document.getElementById('distributorInfoModal'));
    modal.show();
    
    // Fetch distributor information
    fetch(`/distribution/get-distributor-info/${distributorId}/${residentId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            displayDistributorInfo(data);
        } else {
            document.getElementById('distributorInfoContent').innerHTML = `
                <div class="alert alert-danger border-0">
                    <i class="fa-solid fa-exclamation-circle me-2"></i>${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        document.getElementById('distributorInfoContent').innerHTML = `
            <div class="alert alert-danger border-0">
                <i class="fa-solid fa-exclamation-circle me-2"></i>Error loading distributor information
            </div>
        `;
        console.error('Error:', error);
    });
}

function displayDistributorInfo(data) {
    const distributor = data.distributor;
    const distribution = data.distribution;

    const content = `
        <div style="text-align:center;margin-bottom:16px">
            <div class="modal-av">${distributor.first_name ? distributor.first_name.charAt(0).toUpperCase() : 'U'}</div>
            <div style="font-weight:800;font-size:.9rem;color:var(--text-1)">${distributor.full_name || distributor.username}</div>
            <span class="badge2 green" style="margin-top:4px">${distributor.role || 'Distributor'}</span>
        </div>
        <div class="modal-info-block">
            <h6>Distribution Details</h6>
            <div class="modal-info-row"><span>Distribution Date</span><span>${new Date(distribution.distribution_date).toLocaleString()}</span></div>
            <div class="modal-info-row"><span>Claim Type</span><span>${distribution.family_member_id ? 'Family Member' : 'Head of Family'}</span></div>
            <div class="modal-info-row"><span>Item(s) Distributed</span><span>${data.items ? data.items.join(', ') : 'N/A'}</span></div>
        </div>
        <div class="modal-info-block">
            <h6>Contact Information</h6>
            ${distributor.contact_number ? `<div class="modal-contact-row"><i class="fa-solid fa-phone"></i><span>${distributor.contact_number}</span></div>` : ''}
            ${distributor.email ? `<div class="modal-contact-row"><i class="fa-solid fa-envelope"></i><span>${distributor.email}</span></div>` : ''}
            ${distributor.barangay ? `<div class="modal-contact-row"><i class="fa-solid fa-location-dot"></i><span>${distributor.barangay}${distributor.city ? ', ' + distributor.city : ''}</span></div>` : ''}
        </div>
    `;
    document.getElementById('distributorInfoContent').innerHTML = content;
}
</script>

<?= $this->endSection() ?>