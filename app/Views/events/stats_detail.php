<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep: #4a7a26;
  --green-mid: #77BC3F;
  --green-light: #99d15f;
  --green-glow: #b8e48a;
  --orange-deep: #c96b10;
  --orange-mid: #F58220;
  --orange-light: #f9a75a;
  --amber: #f5a623;
  --bg: #f8fafc;
  --surface: #ffffff;
  --surface2: #f0fdf4;
  --text-1: #1e293b;
  --text-2: #334155;
  --text-3: #64748b;
  --border: #e2e8f0;
  --shadow-sm: 0 1px 4px rgba(119,188,63,.08);
  --shadow-md: 0 4px 16px rgba(119,188,63,.12);
  --radius: 14px;
  --radius-sm: 8px;
}

* { box-sizing: border-box; }
body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text-1); }
.mono { font-family: 'DM Mono', monospace; }

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }

/* Page Header */
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
  box-shadow:0 4px 12px rgba(119,188,63,.3);
}
.btn-primary-c:hover { transform:translateY(-1px); color:#fff; }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

/* KPI Cards */
.kpi-grid {
  display:grid; grid-template-columns:repeat(4,1fr); gap:12px;
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
@media(max-width:900px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }

.kpi-card {
  background:var(--surface); border-radius:var(--radius);
  padding:14px 16px; border:1px solid var(--border);
  box-shadow:var(--shadow-sm); transition:box-shadow .2s;
}
.kpi-card.kpi-hero { background:linear-gradient(135deg, var(--green-deep), var(--green-mid)); border-color:transparent; }
.kpi-icon {
  width:34px; height:34px; border-radius:8px;
  display:flex; align-items:center; justify-content:center;
  font-size:.85rem; margin-bottom:8px;
}
.kpi-hero .kpi-icon { background:rgba(255,255,255,.2); color:#fff; }
.kpi-icon.green  { background:#f0fdf4; color:var(--green-deep); }
.kpi-icon.orange { background:#fff7ed; color:var(--orange-deep); }
.kpi-icon.blue   { background:#eff6ff; color:#1d4ed8; }
.kpi-label { font-size:.65rem; font-weight:600; text-transform:uppercase; color:var(--text-3); }
.kpi-hero .kpi-label { color:rgba(255,255,255,.75); }
.kpi-val { font-size:1.6rem; font-weight:800; line-height:1; font-family:'DM Mono',monospace; }
.kpi-hero .kpi-val { color:#fff; }

/* Tabs */
.stats-tabs {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:0 20px;
}
.nav-tabs-custom {
  display:flex; gap:4px; border-bottom:1px solid var(--border); padding-top:8px;
}
.nav-tab-custom {
  padding:8px 16px; font-size:.75rem; font-weight:600; color:var(--text-3);
  border:1px solid transparent; border-bottom:none; border-radius:8px 8px 0 0;
  cursor:pointer; transition:all .2s;
}
.nav-tab-custom:hover { color:var(--green-deep); background:var(--surface2); }
.nav-tab-custom.active {
  background:var(--surface); color:var(--green-deep); border-color:var(--border);
  border-bottom-color:var(--surface); transform:translateY(1px);
}

/* Tables */
.table-card {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); overflow:hidden;
}
.table-scroll { overflow-x:auto; }

.rt {
  width:100%; border-collapse:collapse; font-size:.7rem;
}
.rt thead th {
  background:var(--bg); padding:8px 10px; text-align:left;
  font-size:.6rem; font-weight:700; text-transform:uppercase;
  color:var(--text-3); border-bottom:1px solid var(--border); white-space:nowrap;
}
.rt thead th:first-child { padding-left:20px; }
.rt thead th:last-child { padding-right:20px; }
.rt td {
  padding:9px 10px; border-bottom:1px solid var(--border); vertical-align:middle;
}
.rt td:first-child { padding-left:20px; }
.rt td:last-child { padding-right:20px; }

/* Event Banner */
.event-banner {
  background:linear-gradient(135deg, #f8fafc, var(--surface2));
  border:1px solid var(--border); border-top:none; padding:16px 20px;
}
.event-title {
  font-size:1.4rem; font-weight:800; color:var(--green-deep); line-height:1.2;
}
.event-desc {
  font-size:.75rem; color:var(--text-2); margin-top:4px;
}
.event-meta {
  display:flex; gap:20px; margin-top:10px; font-size:.7rem;
}
.event-meta-item {
  display:flex; align-items:center; gap:6px; color:var(--text-3);
}
.event-meta-item i { color:var(--green-mid); }

/* Resident Chip */
.resident-chip {
  display:flex; align-items:center; gap:8px;
}
.av {
  width:30px; height:30px; border-radius:9px;
  background:linear-gradient(135deg,var(--green-light),var(--green-mid));
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:.7rem; font-weight:700; flex-shrink:0;
}
.av.orange { background:linear-gradient(135deg,var(--orange-mid),var(--orange-deep)); }
.res-name { font-weight:700; color:var(--text-1); font-size:.72rem; }
.res-sub { font-size:.6rem; color:var(--text-3); }

/* Badges */
.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 7px; border-radius:20px; font-size:.6rem; font-weight:600;
}
.badge2.green { background:#e8f5ee; color:var(--green-deep); }
.badge2.orange { background:#fef0e8; color:var(--orange-deep); }
.badge2.amber { background:#fff8e8; color:#c08000; }
.badge2.blue { background:#eff6ff; color:#1d4ed8; }
.badge2.gray { background:var(--bg); color:var(--text-3); border:1px solid var(--border); }

/* HH Badge */
.hh-badge {
  font-family:'DM Mono',monospace; font-size:.62rem;
  background:var(--bg); border:1px solid var(--border);
  border-radius:5px; padding:2px 7px; color:var(--text-2);
}

/* Time */
.time-in {
  font-family:'DM Mono',monospace; font-size:.68rem; font-weight:700; color:var(--text-1);
}

/* Family Panel */
.family-panel {
  background:var(--surface2);
  border-radius:var(--radius-sm);
  padding:12px;
  margin:6px 0;
}
.family-panel-title {
  font-size:.7rem; font-weight:700; color:var(--text-2);
  margin-bottom:8px; display:flex; align-items:center; gap:6px;
}
.fam-table {
  width:100%; border-collapse:collapse; font-size:.65rem;
}
.fam-table th {
  text-align:left; padding:6px 8px;
  background:rgba(0,0,0,.02); font-weight:600;
  color:var(--text-3); border-bottom:1px solid var(--border);
}
.fam-table td {
  padding:6px 8px; border-bottom:1px solid var(--border);
}

.expand-btn {
  background:none; border:1px solid var(--border);
  border-radius:4px; width:22px; height:22px;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; color:var(--text-3); transition:all .2s;
}
.expand-btn:hover {
  background:var(--green-mid); color:#fff; border-color:var(--green-mid);
}
.expand-btn.expanded i { transform:rotate(90deg); }

/* Stats Cards */
.stats-breakdown {
  display:grid; grid-template-columns:repeat(2,1fr); gap:12px;
  padding:20px; background:var(--surface); border:1px solid var(--border); border-top:none;
}
.stats-card {
  background:var(--surface2); border-radius:var(--radius-sm); padding:12px;
}
.stats-card-title {
  font-size:.65rem; font-weight:700; text-transform:uppercase;
  color:var(--text-3); margin-bottom:8px;
}
.stats-card-content {
  font-size:.8rem; font-weight:600; color:var(--text-1);
}
.stats-list {
  list-style:none; padding:0; margin:0;
}
.stats-list li {
  display:flex; justify-content:space-between;
  padding:4px 0; font-size:.7rem; border-bottom:1px dashed var(--border);
}
.stats-list li:last-child { border-bottom:none; }

/* Filter Bar */
.filter-bar {
  background:var(--surface2); border:1px solid var(--border); border-top:none;
  padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;
}
.filter-input-wrap {
  position:relative; flex:1; min-width:200px;
}
.filter-input-wrap i {
  position:absolute; left:10px; top:50%; transform:translateY(-50%);
  color:var(--text-3); font-size:.72rem;
}
.filter-input {
  width:100%; padding:6px 10px 6px 30px; border:1.5px solid var(--border);
  border-radius:8px; font-size:.72rem; background:var(--surface);
}
.filter-input:focus { outline:none; border-color:var(--green-mid); }
.filter-select {
  padding:6px 28px 6px 10px; border:1.5px solid var(--border);
  border-radius:8px; font-size:.72rem; background:var(--surface);
  appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2364748b'/%3E%3C/svg%3E");
  background-repeat:no-repeat; background-position:right 8px center;
}
.filter-select:focus { outline:none; border-color:var(--green-mid); }

/* Empty State */
.empty-state {
  text-align:center; padding:40px 20px; color:var(--text-3);
}
.empty-state i { font-size:2rem; display:block; margin-bottom:8px; opacity:.3; }
.empty-state p { font-size:.75rem; margin:0; }

/* Print Styles */
@media print {
  .page-header, .page-actions, .filter-bar, .stats-tabs,
  .kpi-grid, .stats-breakdown, .expand-btn, .btn-primary-c,
  .btn-outline-c, .footer-actions { display:none !important; }
  body { background:white; }
  .page-wrap { animation:none; }
}
</style>

<div class="page-wrap">
  
  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h5>
        <i class="fa-solid fa-chart-pie me-2" style="color:var(--green-mid)"></i>
        Event Statistics
      </h5>
      <p>Detailed breakdown of attendance and distribution per event</p>
    </div>
    <div class="page-actions">
      <a href="/events" class="btn-outline-c">
        <i class="fa-solid fa-arrow-left"></i> Back to Events
      </a>
      <button onclick="window.print()" class="btn-outline-c">
        <i class="fa-solid fa-print"></i> Print Report
      </button>
    </div>
  </div>

  <!-- Event Banner -->
  <div class="event-banner">
    <div class="event-title"><?= esc($event['event_name']) ?></div>
    <div class="event-desc"><?= esc($event['description']) ?></div>
    <div class="event-meta">
      <div class="event-meta-item">
        <i class="fa-solid fa-calendar"></i>
        <?= date('F j, Y', strtotime($event['start_date'])) ?>
        <?= $event['end_date'] && $event['end_date'] != '0000-00-00' ? ' - ' . date('F j, Y', strtotime($event['end_date'])) : '' ?>
      </div>
      <div class="event-meta-item">
        <i class="fa-solid fa-tag"></i>
        Status: <span class="badge2 <?= $event['is_active'] ? 'green' : 'gray' ?>">
          <?= $event['is_active'] ? 'ACTIVE' : 'CLOSED' ?>
        </span>
      </div>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="kpi-grid">
    <div class="kpi-card kpi-hero">
      <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
      <div class="kpi-label">Total Attendance</div>
      <div class="kpi-val"><?= $stats['total_attendance'] ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon green"><i class="fa-solid fa-house"></i></div>
      <div class="kpi-label">Households Attended</div>
      <div class="kpi-val"><?= $stats['unique_households_attended'] ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon orange"><i class="fa-solid fa-boxes-packing"></i></div>
      <div class="kpi-label">Total Distributions</div>
      <div class="kpi-val"><?= $stats['total_distributions'] ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon blue"><i class="fa-solid fa-house-circle-check"></i></div>
      <div class="kpi-label">Households Received</div>
      <div class="kpi-val"><?= $stats['unique_households_received'] ?></div>
    </div>
  </div>

  <!-- Stats Breakdown -->
  <div class="stats-breakdown">
    <div class="stats-card">
      <div class="stats-card-title">Attendance by Date</div>
      <ul class="stats-list">
        <?php if(empty($stats['attendance_by_date'])): ?>
          <li>No attendance records</li>
        <?php else: ?>
          <?php foreach($stats['attendance_by_date'] as $dateStat): ?>
          <li>
            <span><?= date('M j, Y', strtotime($dateStat['date'])) ?></span>
            <span class="badge2 green"><?= $dateStat['count'] ?></span>
          </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
    <div class="stats-card">
      <div class="stats-card-title">Items Distributed</div>
      <ul class="stats-list">
        <?php if(empty($stats['items_distributed'])): ?>
          <li>No distributions</li>
        <?php else: ?>
          <?php foreach($stats['items_distributed'] as $item): ?>
          <li>
            <span><?= esc($item['item_name']) ?></span>
            <span class="badge2 orange"><?= $item['total_quantity'] ?> <?= $item['unit_type'] ?></span>
          </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <!-- Tabs -->
  <div class="stats-tabs">
    <div class="nav-tabs-custom">
      <div class="nav-tab-custom active" onclick="switchTab('attendance')" id="tab-attendance">
        <i class="fa-solid fa-calendar-check me-1"></i> Attendance Records
      </div>
      <div class="nav-tab-custom" onclick="switchTab('distribution')" id="tab-distribution">
        <i class="fa-solid fa-boxes-packing me-1"></i> Distribution Records
      </div>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="filter-bar">
    <div class="filter-input-wrap">
      <i class="fa-solid fa-search"></i>
      <input type="text" class="filter-input" id="searchInput" placeholder="Search by name or household #...">
    </div>
    <select class="filter-select" id="barangayFilter">
      <option value="">All Barangays</option>
      <?php
      $barangays = [];
      if(!empty($attendances)) {
          foreach($attendances as $att) {
              if(!empty($att['barangay']) && !in_array($att['barangay'], $barangays)) {
                  $barangays[] = $att['barangay'];
              }
          }
      }
      foreach($barangays as $barangay):
      ?>
      <option value="<?= $barangay ?>"><?= $barangay ?></option>
      <?php endforeach; ?>
    </select>
  </div>

<!-- Attendance Tab Content -->
<div id="attendance-tab" class="tab-content">
  <div class="table-card">
    <div class="table-scroll">
      <table class="rt" id="attendanceTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Check In Time</th> <!-- Changed from "Time" to "Check In Time" -->
            <th>Household #</th>
            <th>Name</th>
            <th>Barangay</th> <!-- Added Barangay column -->
            <th>Scanned By</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($attendances)): ?>
          <tr>
            <td colspan="6"> <!-- Updated colspan from 8 to 6 -->
              <div class="empty-state">
                <i class="fa-solid fa-calendar-xmark"></i>
                <p>No attendance records found for this event.</p>
              </div>
            </td>
          </tr>
          <?php else: ?>
            <?php foreach($attendances as $att): 
              $isFamilyMember = !empty($att['family_member_id']);
              $initials = $isFamilyMember 
                ? strtoupper(substr($att['family_member_name'] ?? 'M', 0, 1))
                : strtoupper(substr($att['first_name'] ?? '?', 0, 1));
            ?>
            <tr class="attendance-row" 
                data-search="<?= strtolower(($att['first_name'] ?? '') . ' ' . ($att['last_name'] ?? '') . ' ' . ($att['family_member_name'] ?? '') . ' ' . ($att['household_no'] ?? '')) ?>"
                data-barangay="<?= $att['barangay'] ?? '' ?>">
              <td><?= date('M j, Y', strtotime($att['attendance_date'])) ?></td>
              <td><span class="time-in"><?= date('h:i A', strtotime($att['check_in_time'])) ?></span></td>
              <td><span class="hh-badge"><?= $att['household_no'] ?? '—' ?></span></td>
              <td>
                <div class="resident-chip">
                  <?php if($isFamilyMember): ?>
                    <?php if(!empty($att['member_photo'])): ?>
                      <div class="av" style="background:none; overflow:hidden;">
                        <img src="/<?= $att['member_photo'] ?>" style="width:100%; height:100%; object-fit:cover;">
                      </div>
                    <?php else: ?>
                      <div class="av orange"><?= $initials ?></div>
                    <?php endif; ?>
                    <div>
                      <div class="res-name"><?= esc($att['family_member_name']) ?></div>
                    </div>
                  <?php else: ?>
                    <?php if(!empty($att['resident_photo'])): ?>
                      <div class="av" style="background:none; overflow:hidden;">
                        <img src="/<?= $att['resident_photo'] ?>" style="width:100%; height:100%; object-fit:cover;">
                      </div>
                    <?php else: ?>
                      <div class="av"><?= $initials ?></div>
                    <?php endif; ?>
                    <div>
                      <div class="res-name"><?= $att['first_name'] ?>, <?= $att['last_name'] ?></div>
                    </div>
                  <?php endif; ?>
                </div>
              </td>
              <td><span class="badge2 neutral"><?= $att['barangay'] ?? '—' ?></span></td>
              <td><?= $att['scanned_by_name'] ?? '—' ?></td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Distribution Tab Content (hidden by default) -->
<div id="distribution-tab" class="tab-content" style="display:none">
  <div class="table-card">
    <div class="table-scroll">
      <table class="rt" id="distributionTable">
        <thead>
          <tr>
            <th style="width:36px"></th>
            <th>Distribution Time</th>
            <th>Household #</th>
            <th>Recipient</th>
            <th>Barangay</th>
            <th>Distributor</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($distributions)): ?>
          <tr>
            <td colspan="6">
              <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <p>No distribution records found for this event.</p>
              </div>
            </td>
          </tr>
          <?php else: 
            // Group distributions by recipient (resident_id + family_member_id)
            $groupedDistributions = [];
            foreach($distributions as $dist) {
              $recipientKey = $dist['resident_id'] . '_' . ($dist['family_member_id'] ?? '0');
              
              if (!isset($groupedDistributions[$recipientKey])) {
                $groupedDistributions[$recipientKey] = [
                  'resident_id' => $dist['resident_id'],
                  'family_member_id' => $dist['family_member_id'],
                  'household_no' => $dist['household_no'],
                  'first_name' => $dist['first_name'],
                  'last_name' => $dist['last_name'],
                  'family_member_name' => $dist['family_member_name'],
                  'relation' => $dist['relation'],
                  'resident_photo' => $dist['resident_photo'],
                  'member_photo' => $dist['member_photo'],
                  'barangay' => $dist['barangay'],
                  'distributor_name' => $dist['distributor_name'],
                  'distribution_date' => $dist['distribution_date'],
                  'remarks' => $dist['remarks'],
                  'items' => [],
                  'all_distributions' => [] // Store all distribution records for this recipient
                ];
              }
              
              // Add item to the recipient's items list
              $groupedDistributions[$recipientKey]['items'][] = [
                'item_name' => $dist['item_name'],
                'quantity' => $dist['quantity_distributed'],
                'unit_type' => $dist['unit_type'],
                'batch_number' => $dist['batch_number'],
                'qr_code_scanned' => $dist['qr_code_scanned'],
                'status' => $dist['status'],
                'distribution_id' => $dist['id']
              ];
              
              // Store the full distribution record (use the first one for main info)
              if (empty($groupedDistributions[$recipientKey]['all_distributions'])) {
                $groupedDistributions[$recipientKey]['all_distributions'][] = $dist;
              }
            }
            
            $groupIndex = 0;
            foreach($groupedDistributions as $recipientKey => $recipient): 
              $isFamilyMember = !empty($recipient['family_member_id']);
              $initials = $isFamilyMember 
                ? strtoupper(substr($recipient['family_member_name'] ?? 'M', 0, 1))
                : strtoupper(substr($recipient['first_name'] ?? '?', 0, 1));
              
              // Get the remarks (Claimed by info)
              $remarks = '';
              if (!empty($recipient['remarks'])) {
                $remarks = $recipient['remarks'];
              } elseif (!empty($recipient['all_distributions'][0]['remarks'])) {
                $remarks = $recipient['all_distributions'][0]['remarks'];
              }
              
              // Extract just the "Claimed by: XXX" part if it exists
              $claimedByText = '';
              if (strpos($remarks, 'Claimed by:') !== false) {
                preg_match('/Claimed by: ([^(]+)/', $remarks, $matches);
                $claimedByText = !empty($matches[1]) ? trim($matches[1]) : '';
              }
          ?>
          <tr class="distribution-row" 
              data-search="<?= strtolower(($recipient['first_name'] ?? '') . ' ' . ($recipient['last_name'] ?? '') . ' ' . ($recipient['family_member_name'] ?? '') . ' ' . ($recipient['household_no'] ?? '')) ?>"
              data-barangay="<?= $recipient['barangay'] ?? '' ?>">
            <td>
              <button class="expand-btn" onclick="toggleDistributionDetails(<?= $groupIndex ?>, this)" title="View items distributed">
                <i class="fa-solid fa-chevron-right"></i>
              </button>
            </td>
            <td><span class="time-in"><?= date('M j, Y h:i A', strtotime($recipient['distribution_date'])) ?></span></td>
            <td><span class="hh-badge"><?= $recipient['household_no'] ?? '—' ?></span></td>
            <td>
              <div class="resident-chip">
                <?php if($isFamilyMember): ?>
                  <?php if(!empty($recipient['member_photo'])): ?>
                    <div class="av" style="background:none; overflow:hidden;">
                      <img src="/<?= $recipient['member_photo'] ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                  <?php else: ?>
                    <div class="av orange"><?= $initials ?></div>
                  <?php endif; ?>
                  <div>
                    <div class="res-name"><?= esc($recipient['family_member_name']) ?></div>
                    <div class="res-sub">(<?= $recipient['relation'] ?>) of <?= $recipient['first_name'] ?> <?= $recipient['last_name'] ?></div>
                  </div>
                <?php else: ?>
                  <?php if(!empty($recipient['resident_photo'])): ?>
                    <div class="av" style="background:none; overflow:hidden;">
                      <img src="/<?= $recipient['resident_photo'] ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                  <?php else: ?>
                    <div class="av"><?= $initials ?></div>
                  <?php endif; ?>
                  <div>
                    <div class="res-name"><?= $recipient['last_name'] ?>, <?= $recipient['first_name'] ?></div>
                    <div class="res-sub">Head of Family</div>
                  </div>
                <?php endif; ?>
              </div>
            </td>
            <td><span class="badge2 neutral"><?= $recipient['barangay'] ?? '—' ?></span></td>
            <td><?= $recipient['distributor_name'] ?? '—' ?></td>
          </tr>
          
          <!-- Distribution Details Row - Shows items list -->
          <tr id="dist-details-<?= $groupIndex ?>" class="family-row" style="display:none">
            <td colspan="6">
              <div class="family-panel">
                <div class="family-panel-title">
                  <i class="fa-solid fa-boxes-packing"></i>
                  Items Distributed
                </div>
                
                <?php if(!empty($claimedByText)): ?>
                <div style="margin-bottom: 12px; padding: 8px 12px; background: #fff; border-radius: 8px; border-left: 3px solid var(--green-mid);">
                  <span style="font-size: 0.7rem; color: var(--text-3);">Claimed:</span>
                  <span style="font-size: 0.7rem; font-weight: 500; color: var(--text-1); margin-left: 8px;">Claimed by: <?= esc($claimedByText) ?></span>
                </div>
                <?php endif; ?>
                
                <table class="fam-table">
                  <thead>
                    <tr>
                      <th style="width: 40%">Item Name</th>
                      <th style="width: 20%">Quantity</th>
                      <th style="width: 25%">Batch Number</th>
                      <th style="width: 15%">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($recipient['items'] as $item): ?>
                    <tr>
                      <td><strong><?= esc($item['item_name']) ?></strong></td>
                      <td><?= $item['quantity'] ?> <?= $item['unit_type'] ?></td>
                      <td><span class="mono" style="font-size:0.65rem"><?= $item['batch_number'] ?? '—' ?></span></td>
                      <td><span class="badge2 green"><?= $item['status'] ?? 'completed' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
          <?php 
            $groupIndex++;
            endforeach; 
          endif; 
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>

<script>
function switchTab(tab) {
  // Update tab styles
  document.querySelectorAll('.nav-tab-custom').forEach(t => t.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  
  // Show/hide tab content
  document.getElementById('attendance-tab').style.display = tab === 'attendance' ? 'block' : 'none';
  document.getElementById('distribution-tab').style.display = tab === 'distribution' ? 'block' : 'none';
}

// Filter functionality
document.getElementById('searchInput').addEventListener('keyup', filterTables);
document.getElementById('barangayFilter').addEventListener('change', filterTables);

function filterTables() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  const barangay = document.getElementById('barangayFilter').value;
  
  // Filter attendance rows
  document.querySelectorAll('.attendance-row').forEach(row => {
    const matchesSearch = (row.dataset.search || '').includes(searchTerm);
    const matchesBarangay = !barangay || row.dataset.barangay === barangay;
    const detailsRow = document.getElementById('att-details-' + row.rowIndex);
    
    if (matchesSearch && matchesBarangay) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
      if (detailsRow) detailsRow.style.display = 'none';
    }
  });
  
  // Filter distribution rows
  document.querySelectorAll('.distribution-row').forEach(row => {
    const matchesSearch = (row.dataset.search || '').includes(searchTerm);
    const matchesBarangay = !barangay || row.dataset.barangay === barangay;
    const detailsRow = document.getElementById('dist-details-' + row.rowIndex);
    
    if (matchesSearch && matchesBarangay) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
      if (detailsRow) detailsRow.style.display = 'none';
    }
  });
}

function toggleAttendanceDetails(index, btn) {
  const detailsRow = document.getElementById('att-details-' + index);
  if (detailsRow) {
    if (detailsRow.style.display === 'none') {
      detailsRow.style.display = 'table-row';
      btn.classList.add('expanded');
    } else {
      detailsRow.style.display = 'none';
      btn.classList.remove('expanded');
    }
  }
}

function toggleDistributionDetails(index, btn) {
  const detailsRow = document.getElementById('dist-details-' + index);
  if (detailsRow) {
    if (detailsRow.style.display === 'none') {
      detailsRow.style.display = 'table-row';
      btn.classList.add('expanded');
    } else {
      detailsRow.style.display = 'none';
      btn.classList.remove('expanded');
    }
  }
}

// Auto-highlight rows that belong to this event (they're already filtered by event_id)
document.addEventListener('DOMContentLoaded', function() {
  // All rows shown are for this event, so no additional highlighting needed
  console.log('Event statistics page loaded for event ID: <?= $eventId ?? $event["id"] ?>');
});
</script>

<?= $this->endSection() ?>