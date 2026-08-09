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

/* ── Utility ── */
.mono { font-family: 'DM Mono', monospace; }

/* ── Page wrapper ── */
.dash-wrap { padding: 0; animation: fadeUp .45s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }

/* ── Top bar ── */
.topbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 20px 14px;
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  border-radius: var(--radius) var(--radius) 0 0;
  position: sticky; top: 0; z-index: 100;
}
.topbar-brand { display:flex; align-items:center; gap:10px; }
.topbar-brand img { height: 36px; }
.topbar-brand .brand-text { font-size:.8rem; font-weight:600; color:var(--green-deep); line-height:1.2; }
.topbar-brand .brand-sub  { font-size:.65rem; color:var(--text-3); font-weight:400; }
.topbar-right { display:flex; align-items:center; gap:8px; }
.topbar-date {
  font-size:.7rem; color:var(--text-3);
  background:var(--bg); border:1px solid var(--border);
  padding:4px 10px; border-radius:20px;
}
.topbar-badge {
  display:flex; align-items:center; gap:8px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color:#fff; border-radius: 20px; padding: 4px 10px 4px 6px;
  font-size:.7rem; font-weight:500;
}
.topbar-badge .avatar { width:26px; height:26px; border-radius:50%; border:2px solid rgba(255,255,255,.4); }

/* ── Header section ── */
.dash-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  padding: 16px 20px 0;
}
.dash-title h4 { font-size:1.15rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.3px; }
.dash-title p  { font-size:.7rem; color:var(--text-3); margin:2px 0 0; }
.dash-actions  { display:flex; gap:6px; flex-wrap:wrap; }

.btn-primary-custom {
  display:inline-flex; align-items:center; gap:6px;
  background: linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 100%);
  color:#fff; border:none; border-radius:8px;
  padding: 7px 14px; font-size:.72rem; font-weight:600; cursor:pointer;
  text-decoration:none; transition: all .2s ease;
  box-shadow: 0 4px 12px rgba(26,92,46,.3);
}
.btn-primary-custom:hover { transform:translateY(-1px); box-shadow: 0 6px 18px rgba(26,92,46,.4); color:#fff; }

.btn-outline-custom {
  display:inline-flex; align-items:center; gap:6px;
  background:transparent; color:var(--green-deep);
  border:1.5px solid var(--green-mid); border-radius:8px;
  padding: 7px 14px; font-size:.72rem; font-weight:600; cursor:pointer;
  text-decoration:none; transition: all .2s ease;
}
.btn-outline-custom:hover { background:var(--green-deep); color:#fff; }

.btn-orange-custom {
  display:inline-flex; align-items:center; gap:6px;
  background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid));
  color:#fff; border:none; border-radius:8px;
  padding: 7px 14px; font-size:.72rem; font-weight:600; cursor:pointer;
  text-decoration:none; transition: all .2s ease;
  box-shadow: 0 4px 12px rgba(192,74,18,.3);
}
.btn-orange-custom:hover { transform:translateY(-1px); color:#fff; }

/* ── KPI Grid ── */
.kpi-grid {
  display:grid; grid-template-columns: repeat(4, 1fr); gap:12px;
  padding: 14px 20px;
}
@media(max-width:900px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:500px){ .kpi-grid{ grid-template-columns:1fr; } }

.kpi-card {
  background:var(--surface); border-radius:var(--radius);
  padding:14px 16px; border:1px solid var(--border);
  box-shadow:var(--shadow-sm); position:relative; overflow:hidden;
  transition: box-shadow .2s, transform .2s;
}
.kpi-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.kpi-card.kpi-hero {
  background: linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 60%, var(--green-light) 100%);
  color:#fff; border-color:transparent;
}
.kpi-card .kpi-icon {
  width:34px; height:34px; border-radius:8px;
  display:flex; align-items:center; justify-content:center;
  font-size:.85rem; margin-bottom:8px;
}
.kpi-card.kpi-hero .kpi-icon { background:rgba(255,255,255,.2); color:#fff; }
.kpi-card .kpi-icon.green  { background:#e8f5ee; color:var(--green-deep); }
.kpi-card .kpi-icon.orange { background:#fef0e8; color:var(--orange-deep); }
.kpi-card .kpi-icon.amber  { background:#fff8e8; color:#c08000; }
.kpi-card .kpi-label { font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--text-3); margin-bottom:2px; }
.kpi-card.kpi-hero .kpi-label { color:rgba(255,255,255,.75); }
.kpi-card .kpi-val { font-size:1.6rem; font-weight:800; line-height:1; color:var(--text-1); }
.kpi-card.kpi-hero .kpi-val { color:#fff; }
.kpi-card .kpi-sub { font-size:.65rem; margin-top:6px; color:var(--text-3); display:flex; align-items:center; gap:4px; }
.kpi-card.kpi-hero .kpi-sub { color:rgba(255,255,255,.75); }
.kpi-card .kpi-link { font-size:.65rem; color:var(--green-mid); text-decoration:none; font-weight:600; }
.kpi-card .kpi-link:hover { color:var(--green-deep); }
.kpi-foot { display:flex; justify-content:space-between; align-items:center; margin-top:8px; padding-top:8px; border-top:1px solid var(--border); }
.kpi-card.kpi-hero .kpi-foot { border-top-color:rgba(255,255,255,.25); }

.kpi-progress { height:5px; background:rgba(0,0,0,.07); border-radius:3px; margin-top:8px; }
.kpi-progress-bar { height:100%; border-radius:3px; transition:width .8s ease; }

/* ── Main grid ── */
.main-grid {
  display:grid; grid-template-columns:1fr 320px; gap:12px;
  padding: 0 20px 12px;
  align-items: start;
}
@media(max-width:900px){ .main-grid{ grid-template-columns:1fr; } }

/* ── Cards ── */
.card2 {
  background:var(--surface); border-radius:var(--radius);
  border:1px solid var(--border); box-shadow:var(--shadow-sm);
  overflow:hidden;
}
.card2-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:12px 16px 0; font-size:.78rem; font-weight:700; color:var(--text-1);
}
.card2-header .icon { font-size:.8rem; color:var(--green-mid); margin-right:6px; }
.card2-body { padding:12px 16px 14px; }

/* ── Table ── */
.dash-table { width:100%; border-collapse:collapse; font-size:.7rem; }
.dash-table thead th {
  background:var(--bg); padding:7px 10px; text-align:left;
  font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  color:var(--text-3); border-bottom:1px solid var(--border);
}
.dash-table tbody tr { transition:background .15s; }
.dash-table tbody tr:hover { background:var(--surface2); }
.dash-table td { padding:8px 10px; border-bottom:1px solid var(--border); vertical-align:middle; }
.dash-table td:first-child { padding-left:16px; }
.dash-table thead th:first-child { padding-left:16px; }

.resident-chip { display:flex; align-items:center; gap:8px; }
.resident-av {
  width:28px; height:28px; border-radius:8px;
  background: linear-gradient(135deg, var(--green-light), var(--green-mid));
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:.65rem; font-weight:700; flex-shrink:0;
}
.resident-name { font-weight:600; color:var(--text-1); font-size:.7rem; }
.resident-id   { font-size:.6rem; color:var(--text-3); }

.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:3px 8px; border-radius:20px; font-size:.6rem; font-weight:600;
}
.badge2.success { background:#e8f5ee; color:#1a5c2e; }
.badge2.warning { background:#fff8e8; color:#c08000; }
.badge2.danger  { background:#fdf0ee; color:#c04a12; }
.badge2.info    { background:#e8f0fe; color:#1a56a0; }
.badge2.neutral { background:var(--bg); color:var(--text-2); }

.hh-badge { background:var(--bg); border:1px solid var(--border); color:var(--text-2); border-radius:5px; padding:2px 7px; font-size:.62rem; font-family:'DM Mono',monospace; }

/* ── Inventory bars ── */
.inv-item { margin-bottom:12px; }
.inv-row { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:3px; }
.inv-name { font-size:.7rem; font-weight:600; color:var(--text-1); }
.inv-qty  { font-size:.7rem; font-weight:700; color:var(--text-2); font-family:'DM Mono',monospace; }
.inv-bar  { height:5px; background:var(--bg); border-radius:3px; overflow:hidden; }
.inv-bar-fill { height:100%; border-radius:3px; transition:width .8s ease; }
.inv-alloc { font-size:.6rem; color:var(--text-3); margin-top:2px; }

/* ── Quick Actions ── */
.qa-list { display:flex; flex-direction:column; gap:6px; padding:12px 16px 14px; }
.qa-btn {
  display:flex; align-items:center; gap:8px;
  padding:8px 12px; border-radius:8px;
  border:1.5px solid var(--border); background:var(--surface2);
  color:var(--text-1); font-size:.7rem; font-weight:600; text-decoration:none;
  transition:all .2s; cursor:pointer;
}
.qa-btn:hover { border-color:var(--green-mid); background:#e8f5ee; color:var(--green-deep); }
.qa-btn .qa-icon { width:26px; height:26px; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:.72rem; flex-shrink:0; }
.qa-btn.green .qa-icon  { background:#e8f5ee; color:var(--green-deep); }
.qa-btn.orange .qa-icon { background:#fef0e8; color:var(--orange-deep); }
.qa-btn.amber .qa-icon  { background:#fff8e8; color:#c08000; }

/* ── Bottom grid ── */
.bottom-grid {
  display:grid; grid-template-columns:1fr 1fr; gap:12px;
  padding:0 20px 20px;
}
@media(max-width:700px){ .bottom-grid{ grid-template-columns:1fr; } }

/* ── Vulnerable chips ── */
.vuln-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
.vuln-chip {
  background:var(--surface2); border:1px solid var(--border);
  border-radius:10px; padding:10px 12px;
}
.vuln-chip .vc-label { font-size:.62rem; color:var(--text-3); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
.vuln-chip .vc-val   { font-size:1.3rem; font-weight:800; color:var(--text-1); line-height:1.1; }

/* ── Section label ── */
.section-label {
  font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.7px;
  color:var(--text-3); padding:12px 20px 4px; display:flex; align-items:center; gap:6px;
}
.section-label::after { content:''; flex:1; height:1px; background:var(--border); }

/* ── Filter dropdown ── */
.filter-btn {
  background:var(--bg); border:1px solid var(--border); border-radius:6px;
  padding:4px 10px; font-size:.65rem; font-weight:600; color:var(--text-2);
  cursor:pointer; display:flex; align-items:center; gap:4px;
}

/* ── Chart canvas ── */
#barangayChart { max-height:210px; }

/* ── View all link ── */
.view-all { font-size:.65rem; color:var(--green-mid); text-decoration:none; font-weight:600; padding:8px 16px; display:block; text-align:right; border-top:1px solid var(--border); }
.view-all:hover { color:var(--green-deep); }

/* ── Alert info bar ── */
.info-bar {
  display:flex; align-items:center; gap:6px;
  background:linear-gradient(90deg,#e8f5ee,#f7faf8);
  border:1px solid var(--green-glow); border-radius:8px;
  padding:8px 12px; margin-top:10px; font-size:.68rem; color:var(--green-deep); font-weight:500;
}

/* ── Glow pulse ── */
.pulse-dot { width:7px; height:7px; background:var(--green-light); border-radius:50%; position:relative; flex-shrink:0; }
.pulse-dot::after { content:''; position:absolute; inset:-3px; border-radius:50%; background:var(--green-glow); opacity:.4; animation: pulse 1.8s ease-in-out infinite; }
@keyframes pulse { 0%,100%{transform:scale(1);opacity:.4} 50%{transform:scale(1.5);opacity:0;} }

/* ── Role pill ── */
.role-pill {
  display:inline-flex; align-items:center; gap:5px;
  padding:4px 10px; border-radius:20px; font-size:.65rem; font-weight:700;
  letter-spacing:.3px; border:1.5px solid;
}
.role-pill.role-superadmin { background:#fdf0e8; color:var(--orange-deep); border-color:var(--orange-light); }
.role-pill.role-admin       { background:#e8f0fe; color:#1a56a0; border-color:#b8d0fc; }
.role-pill.role-staff       { background:#e8f5ee; color:var(--green-deep); border-color:var(--green-glow); }

/* ── Empty state ── */
.empty-state { text-align:center; padding:28px 16px; color:var(--text-3); }
.empty-state i { font-size:1.8rem; display:block; margin-bottom:8px; }
.empty-state p { font-size:.72rem; margin:0; }
</style>

<div class="dash-wrap">

  <!-- ══ Top Bar ══ -->
  <div class="topbar">
    <div class="topbar-brand">
      <img src="/uploads/logo.png" alt="Logo">
      <div>
        <div class="brand-text">Relief Operations</div>
        <div class="brand-sub">Command Center</div>
      </div>
    </div>
    <div class="topbar-right">
      <span class="topbar-date">
        <i class="fa-regular fa-calendar me-1"></i><?= date('F d, Y') ?>
      </span>
      <div class="topbar-badge">
        <span class="pulse-dot"></span>
        <span><?= session()->get('full_name') ?? 'Admin' ?></span>
      </div>
      <?php $role = strtolower(session()->get('role') ?? 'admin'); ?>
      <span class="role-pill role-<?= $role ?>">
        <i class="fa-solid <?= $role === 'superadmin' ? 'fa-shield-halved' : ($role === 'admin' ? 'fa-user-shield' : 'fa-user') ?>"></i>
        <?= ucfirst($role) ?>
      </span>
    </div>
  </div>

  <!-- ══ Header ══ -->
  <div class="dash-header">
    <div class="dash-title">
      <h4><i class="fa-solid fa-chart-line me-2" style="color:var(--green-mid)"></i><?= $role === 'superadmin' ? 'Super Admin' : ($role === 'admin' ? 'Administrative' : 'Staff') ?> Dashboard</h4>
      <p>Welcome back, <?= session()->get('full_name') ?? 'Admin' ?>! Here's your relief operations overview.</p>
    </div>
    <div class="dash-actions">
      <a href="/beneficiaries/add" class="btn-primary-custom">
        <i class="fa-solid fa-plus"></i>Add Beneficiary
      </a>
      <a href="/distribution/scanner" class="btn-orange-custom">
        <i class="fa-solid fa-qrcode"></i>QR Scanner
      </a>
      <a href="/beneficiaries/import" class="btn-outline-custom">
        <i class="fa-regular fa-file-excel"></i>Import
      </a>
    </div>
  </div>

  <!-- ══ KPI Grid ══ -->
  <div class="kpi-grid">

    <!-- Total Residents -->
    <div class="kpi-card kpi-hero">
      <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
      <div class="kpi-label">Total Registered</div>
      <div class="kpi-val mono"><?= number_format($total_residents) ?></div>
      <div class="kpi-foot">
        <span class="kpi-sub"><i class="fa-regular fa-clock"></i>Last 30 days</span>
        <a href="/beneficiaries" class="kpi-link" style="color:rgba(255,255,255,.85)">View All →</a>
      </div>
    </div>

    <!-- Distributed -->
    <?php $pct = $total_residents > 0 ? round(($total_distributed / $total_residents)*100) : 0; ?>
    <div class="kpi-card">
      <div class="kpi-icon green"><i class="fa-solid fa-circle-check"></i></div>
      <div class="kpi-label">Total Distributed</div>
      <div class="kpi-val mono"><?= number_format($total_distributed) ?></div>
      <div class="kpi-progress"><div class="kpi-progress-bar" style="width:<?= $pct ?>%;background:var(--green-mid)"></div></div>
      <div class="kpi-foot">
        <span class="kpi-sub"><i class="fa-solid fa-percent" style="font-size:.55rem"></i><?= $pct ?>% Completion</span>
        <a href="/reports" class="kpi-link">Report →</a>
      </div>
    </div>

    <!-- Pending -->
    <?php 
      $pending = $total_residents - $total_distributed;
      $pendPct = $total_residents > 0 ? ($pending / $total_residents)*100 : 0;
    ?>
    <div class="kpi-card">
      <div class="kpi-icon amber"><i class="fa-solid fa-hourglass-half"></i></div>
      <div class="kpi-label">Pending Distribution</div>
      <div class="kpi-val mono"><?= number_format($pending) ?></div>
      <div class="kpi-progress"><div class="kpi-progress-bar" style="width:<?= $pendPct ?>%;background:var(--amber)"></div></div>
      <div class="kpi-foot">
        <span class="kpi-sub"><?= number_format($pendPct,1) ?>% remaining</span>
      </div>
    </div>

    <!-- Inventory -->
    <?php 
      $lowStockCount = 0; $totalStock = 0;
      foreach($inventory_items as $item):
        $totalStock += $item['quantity'];
        if($item['quantity'] < 50) $lowStockCount++;
      endforeach;
    ?>
    <div class="kpi-card">
      <div class="kpi-icon" style="background:#e8f0fe;color:#1a56a0"><i class="fa-solid fa-boxes-stacked"></i></div>
      <div class="kpi-label">Inventory Items</div>
      <div class="kpi-val mono"><?= number_format(count($inventory_items)) ?></div>
      <div class="kpi-foot">
        <span class="kpi-sub">Stock: <?= number_format($totalStock) ?>
          <?php if($lowStockCount>0): ?>
            &nbsp;<span class="badge2 danger"><i class="fa-solid fa-triangle-exclamation"></i><?= $lowStockCount ?> Low</span>
          <?php endif; ?>
        </span>
        <a href="/inventory" class="kpi-link">Manage →</a>
      </div>
    </div>

  </div>

  <!-- ══ Main Content ══ -->
  <div class="section-label"><i class="fa-solid fa-table-list" style="color:var(--green-mid)"></i>Activity & Operations</div>

  <!-- Two-column main grid: left = activity tables, right = sidebar -->
  <div class="main-grid" style="padding-bottom:0; align-items:start;">

  <!-- Left Column - Distribution and Attendance Cards -->
  <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:12px;">
    
    <!-- Recent Distribution Table Card -->
    <div class="card2">
      <div class="card2-header">
        <span><i class="fa-solid fa-clock-rotate-left icon"></i>Recent Distribution Activity</span>
        <div class="dropdown">
          <button class="filter-btn" type="button" data-bs-toggle="dropdown">
            <i class="fa-solid fa-sliders"></i> Filter
          </button>
          <ul class="dropdown-menu dropdown-menu-end" style="font-size:.72rem">
            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Week</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">All Time</a></li>
          </ul>
        </div>
      </div>
      <div style="overflow-x:auto">
        <table class="dash-table">
          <thead>
            <tr>
              <th>Resident</th>
              <th>Household No.</th>
              <th>Distributed By</th>
              <th>Status</th>
              <th>Date & Time</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($recent_claims)): ?>
              <tr><td colspan="5">
                <div class="empty-state">
                  <i class="fa-regular fa-circle-xmark"></i>
                  <p>No distribution records found</p>
                </div>
              </td></tr>
            <?php else: ?>
              <?php foreach($recent_claims as $claim): ?>
              <tr>
                <td>
                  <div class="resident-chip">
                    <div class="resident-av"><?= strtoupper(substr($claim['resident_name'] ?? 'R', 0, 1)) ?></div>
                    <div>
                      <div class="resident-name"><?= $claim['resident_name'] ?? 'Resident #'.$claim['resident_id'] ?></div>
                      <div class="resident-id">ID: <?= $claim['resident_id'] ?></div>
                    </div>
                  </div>
                </td>
                <td><span class="hh-badge">H-<?= str_pad($claim['resident_id'],5,'0',STR_PAD_LEFT) ?></span></td>
                <td><span style="font-size:.68rem;color:var(--text-2)"><?= $claim['distributor_name'] ?? 'Unknown' ?></span></td>
                <td><span class="badge2 success"><i class="fa-solid fa-circle-check"></i>Claimed</span></td>
<td>
  <?php 
  $dateField = isset($claim['distribution_date']) ? $claim['distribution_date'] : ($claim['claimed_at'] ?? null);
  ?>
  <?php if ($dateField): ?>
    <span style="font-size:.65rem;color:var(--text-3);display:block"><?= date('M d, Y', strtotime($dateField)) ?></span>
    <span style="font-size:.63rem;color:var(--text-3)"><?= date('h:i A', strtotime($dateField)) ?></span>
  <?php else: ?>
    <span style="font-size:.65rem;color:var(--text-3)">Date not available</span>
  <?php endif; ?>
</td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <a href="/reports" class="view-all">View All Activities <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    <!-- Recent Attendance Card (New separate card below Distribution) -->
    <div class="card2">
      <div class="card2-header">
        <span><i class="fa-solid fa-calendar-check icon" style="color:var(--orange-mid)"></i>Recent Attendance</span>
        <div class="dropdown">
          <button class="filter-btn" type="button" data-bs-toggle="dropdown">
            <i class="fa-solid fa-sliders"></i> Filter
          </button>
          <ul class="dropdown-menu dropdown-menu-end" style="font-size:.72rem">
            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Week</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
          </ul>
        </div>
      </div>
      <div style="overflow-x:auto">
        <table class="dash-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Household #</th>
              <th>Barangay</th>
              <th>Type</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($recent_attendance)): ?>
              <tr><td colspan="7">
                <div class="empty-state">
                  <i class="fa-regular fa-calendar-xmark"></i>
                  <p>No recent attendance records</p>
                </div>
              </td></tr>
            <?php else: ?>
              <?php foreach($recent_attendance as $att): 
                $name = $att['first_name'] . ' ' . $att['last_name'];
                if (!empty($att['family_member_name'])) {
                  $name .= ' - ' . $att['family_member_name'];
                }
                $initials = strtoupper(substr($att['first_name'] ?? '?', 0, 1));
                $isFamilyMember = !empty($att['family_member_name']);
              ?>
              <tr>
                <td>
                  <div class="resident-chip">
                    <div class="resident-av" style="background:<?= $isFamilyMember ? 'var(--orange-mid)' : 'var(--green-mid)' ?>">
                      <?= $initials ?>
                    </div>
                    <div>
                      <div class="resident-name"><?= $name ?></div>
                      <div class="resident-id"><?= $att['attendance_date'] ? date('M d, Y', strtotime($att['attendance_date'])) : '' ?></div>
                    </div>
                  </div>
                </td>
                <td><span class="hh-badge"><?= $att['household_no'] ?? '—' ?></span></td>
                <td><span class="badge2 neutral"><?= $att['barangay'] ?? '—' ?></span></td>
                <td>
                  <span class="badge2 <?= $isFamilyMember ? 'info' : 'green' ?>">
                    <?= $isFamilyMember ? 'Family Member' : 'Head of Family' ?>
                  </span>
                </td>
                <td>
                  <?php if($att['check_in_time']): ?>
                    <span style="font-size:.68rem;font-weight:600;color:var(--green-deep)">
                      <?= date('h:i A', strtotime($att['check_in_time'])) ?>
                    </span>
                  <?php else: ?>
                    <span style="color:var(--text-3)">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($att['check_out_time']): ?>
                    <span style="font-size:.68rem;color:var(--text-3)">
                      <?= date('h:i A', strtotime($att['check_out_time'])) ?>
                    </span>
                  <?php else: ?>
                    <span style="color:var(--text-3)">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if(!empty($att['check_out_time'])): ?>
                    <span class="badge2 neutral"><i class="fa-solid fa-circle-check"></i>Completed</span>
                  <?php elseif($att['check_in_time']): ?>
                    <span class="badge2 success"><i class="fa-solid fa-circle-check"></i>Active</span>
                  <?php else: ?>
                    <span class="badge2 muted">—</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <a href="/attendance" class="view-all">View All Attendance <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    <!-- ══ Analytics (moved below Recent Attendance) ══ -->
    <div class="section-label" style="margin-top:10px"><i class="fa-solid fa-chart-bar" style="color:var(--green-mid)"></i>Analytics</div>

    <div class="bottom-grid" style="padding:0 0 12px;">

      <!-- Barangay Chart -->
      <div class="card2">
        <div class="card2-header"><span><i class="fa-solid fa-map-location-dot icon"></i>Distribution by Barangay</span></div>
        <div class="card2-body"><canvas id="barangayChart"></canvas></div>
      </div>

      <!-- Vulnerable Families -->
      <div class="card2">
        <div class="card2-header"><span><i class="fa-solid fa-heart-circle-check icon"></i>Vulnerable Families</span></div>
        <div class="card2-body">
          <?php
          $vulnerableStats = ['senior'=>0,'pwd'=>0,'pregnant'=>0,'lactating'=>0];
          ?>
          <div class="vuln-grid">
            <div class="vuln-chip">
              <div class="vc-label"><i class="fa-solid fa-person-cane me-1"></i>Older Persons</div>
              <div class="vc-val mono"><?= number_format($vulnerableStats['senior']) ?></div>
            </div>
            <div class="vuln-chip">
              <div class="vc-label"><i class="fa-solid fa-wheelchair me-1"></i>PWD</div>
              <div class="vc-val mono"><?= number_format($vulnerableStats['pwd']) ?></div>
            </div>
            <div class="vuln-chip">
              <div class="vc-label"><i class="fa-solid fa-baby me-1"></i>Pregnant</div>
              <div class="vc-val mono"><?= number_format($vulnerableStats['pregnant']) ?></div>
            </div>
            <div class="vuln-chip">
              <div class="vc-label"><i class="fa-solid fa-hands-holding-child me-1"></i>Lactating</div>
              <div class="vc-val mono"><?= number_format($vulnerableStats['lactating']) ?></div>
            </div>
          </div>
          <div class="info-bar">
            <span class="pulse-dot"></span>
            Total vulnerable families tracked: <strong>&nbsp;<?= number_format($total_residents) ?></strong>
          </div>
        </div>
      </div>

    </div>

  </div><!-- /left column -->

  <!-- Right Column - Sidebar with Inventory and Quick Actions -->
  <div style="display:flex;flex-direction:column;gap:12px;position:sticky;top:60px;">

      <!-- Inventory Status -->
      <div class="card2">
        <div class="card2-header">
          <span><i class="fa-solid fa-boxes-packing icon"></i>Inventory Status</span>
          <a href="/inventory" class="kpi-link" style="font-size:.65rem">All Items →</a>
        </div>
        <div class="card2-body">
          <?php if(empty($inventory_items)): ?>
            <div class="empty-state">
              <i class="fa-regular fa-box-open"></i>
              <p>No inventory items</p>
              <a href="/inventory/add_item" class="btn-primary-custom" style="margin-top:8px;font-size:.65rem">Add Item</a>
            </div>
          <?php else: ?>
            <?php foreach(array_slice($inventory_items,0,5) as $item):
              $p = min(($item['quantity']/500)*100,100);
              $c = $item['quantity']<50 ? 'var(--orange-deep)' : ($item['quantity']<200 ? 'var(--amber)' : 'var(--green-mid)');
            ?>
            <div class="inv-item">
              <div class="inv-row">
                <div class="inv-name">
                  <?= $item['item_name'] ?>
                  <?php if($item['quantity']<50): ?><span class="badge2 danger ms-1">Low</span><?php endif; ?>
                </div>
                <div class="inv-qty"><?= number_format($item['quantity']) ?> <?= $item['unit_type'] ?></div>
              </div>
              <div class="inv-bar"><div class="inv-bar-fill" style="width:<?= $p ?>%;background:<?= $c ?>"></div></div>
              <div class="inv-alloc">Allocation: <?= $item['allocation'] ?? 1 ?> <?= $item['unit_type'] ?>/household</div>
            </div>
            <?php endforeach; ?>
            <?php if(count($inventory_items)>5): ?>
              <a href="/inventory" class="kpi-link d-block mt-2 text-center">View all <?= count($inventory_items) ?> items →</a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card2">
        <div class="card2-header"><span><i class="fa-solid fa-bolt icon"></i>Quick Actions</span></div>
        <div class="qa-list">
          <a href="/beneficiaries/add" class="qa-btn green">
            <span class="qa-icon"><i class="fa-regular fa-square-plus"></i></span>Add New Beneficiary
          </a>
          <a href="/beneficiaries/import" class="qa-btn green">
            <span class="qa-icon"><i class="fa-regular fa-file-excel"></i></span>Import Beneficiaries
          </a>
          <a href="/distribution/scanner" class="qa-btn orange">
            <span class="qa-icon"><i class="fa-solid fa-qrcode"></i></span>Open QR Scanner
          </a>
          <a href="/inventory/add_item" class="qa-btn amber">
            <span class="qa-icon"><i class="fa-solid fa-cubes"></i></span>Add Inventory Item
          </a>
          <a href="/reports/export" class="qa-btn">
            <span class="qa-icon" style="background:var(--bg);color:var(--text-2)"><i class="fa-regular fa-file-pdf"></i></span>Generate Report
          </a>
        </div>
      </div>

  </div><!-- /right column -->

  </div><!-- /main-grid -->

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('barangayChart').getContext('2d');
  const labels = <?= $barangay_labels ?? '[]' ?>;
  const registeredData = <?= $barangay_registered ?? '[]' ?>;
  const claimedData = <?= $barangay_claimed ?? '[]' ?>;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Registered',
        data: registeredData,
        backgroundColor: 'rgba(119,188,63,.25)',
        borderColor: '#77BC3F',
        borderWidth: 1.5,
        borderRadius: 5,
      },{
        label: 'Claimed',
        data: claimedData,
        backgroundColor: 'rgba(245,130,32,.25)',
        borderColor: '#F58220',
        borderWidth: 1.5,
        borderRadius: 5,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { font: { family:'Outfit', size:11 }, color:'#334155' } },
        tooltip: { callbacks: { label: ctx => ctx.dataset.label+': '+new Intl.NumberFormat().format(ctx.parsed.y) } }
      },
      scales: {
        x: { grid: { display:false }, ticks: { font:{family:'Outfit',size:10}, color:'#64748b' } },
        y: { beginAtZero:true, grid: { color:'rgba(0,0,0,.05)' }, ticks: { font:{family:'Outfit',size:10}, color:'#64748b', stepSize:1 } }
      }
    }
  });
});
</script>

<?= $this->endSection() ?>