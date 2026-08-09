<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep:   #4a7a26;
  --green-mid:    #77BC3F;
  --green-light:  #99d15f;
  --green-glow:   #b8e48a;
  --green-bg:     #f0fdf4;
  --orange-deep:  #c96b10;
  --orange-mid:   #F58220;
  --orange-light: #f9a75a;
  --orange-bg:    #fff7ed;
  --amber:        #f5a623;
  --amber-bg:     #fffbeb;
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

/* ── Breadcrumb ── */
.breadcrumb-bar {
  display: flex; align-items: center; gap: 6px;
  font-size: .7rem; color: var(--text-3); margin-bottom: 14px;
}
.breadcrumb-bar a { color: var(--green-mid); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { color: var(--green-deep); }
.breadcrumb-bar i { font-size: .55rem; color: var(--text-3); }

/* ── Page header ── */
.view-header {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius) var(--radius) 0 0;
  padding: 16px 22px;
}
.view-header-left { display: flex; align-items: center; gap: 12px; }
.view-header-icon {
  width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 1rem;
  box-shadow: 0 4px 12px rgba(119,188,63,.3);
}
.view-header-text h5 { font-size: .95rem; font-weight: 800; color: var(--text-1); margin: 0; letter-spacing: -.2px; }
.view-header-text p  { font-size: .67rem; color: var(--text-3); margin: 2px 0 0; }

.hh-display {
  display: flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  border-radius: 8px; padding: 8px 12px; border: none;
}
.hh-display .hh-label { font-size: .6rem; color: rgba(255,255,255,.75); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.hh-display .hh-val   { font-size: 1rem; font-weight: 800; color: #fff; font-family: 'DM Mono', monospace; }

/* ── Action buttons ── */
.action-group { display: flex; gap: 8px; }
.btn-primary-c {
  display: inline-flex; align-items: center; gap: 5px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color: #fff; border: none; border-radius: 8px;
  padding: 8px 16px; font-size: .7rem; font-weight: 600;
  text-decoration: none; cursor: pointer; transition: all .2s;
  box-shadow: 0 4px 12px rgba(119,188,63,.3);
}
.btn-primary-c:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(119,188,63,.4); color: #fff; }
.btn-outline-c {
  display: inline-flex; align-items: center; gap: 5px;
  background: transparent; color: var(--text-2);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 8px 14px; font-size: .7rem; font-weight: 600;
  text-decoration: none; cursor: pointer; transition: all .2s;
}
.btn-outline-c:hover { border-color: var(--green-mid); color: var(--green-deep); background: #e8f5ee; }
.btn-orange-c {
  display: inline-flex; align-items: center; gap: 5px;
  background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid));
  color: #fff; border: none; border-radius: 8px;
  padding: 8px 16px; font-size: .7rem; font-weight: 600;
  text-decoration: none; cursor: pointer; transition: all .2s;
  box-shadow: 0 4px 12px rgba(201,107,16,.3);
}
.btn-orange-c:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(201,107,16,.4); color: #fff; }

/* ── Content card ── */
.content-card {
  background: var(--surface); border: 1px solid var(--border); border-top: none;
  padding: 22px; border-radius: 0 0 var(--radius) var(--radius);
}

.info-grid {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;
  margin-bottom: 20px;
}
@media(max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }

.info-section {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  overflow: hidden;
}
.info-section-header {
  background: var(--surface2); padding: 8px 14px;
  border-bottom: 1px solid var(--border);
  font-size: .7rem; font-weight: 700; color: var(--text-2);
  display: flex; align-items: center; gap: 8px;
}
.info-section-header i { color: var(--green-mid); font-size: .75rem; }
.info-section-body { padding: 14px; }

.info-row {
  display: flex; padding: 5px 0; border-bottom: 1px dashed var(--border);
  font-size: .7rem;
}
.info-row:last-child { border-bottom: none; }
.info-label {
  width: 110px; flex-shrink: 0; color: var(--text-3); font-weight: 500;
}
.info-value {
  flex: 1; color: var(--text-1); font-weight: 500; word-break: break-word;
}
.info-value.highlight {
  color: var(--green-deep); font-weight: 700; font-family: 'DM Mono', monospace;
}

.qr-codes {
  display: flex; gap: 12px; flex-wrap: wrap;
}
.qr-item {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  padding: 12px; background: var(--surface2);
  display: inline-flex; flex-direction: column; align-items: center; gap: 6px;
  text-decoration: none; color: var(--text-2); transition: all .2s;
}
.qr-item:hover {
  border-color: var(--green-mid); background: var(--green-bg); color: var(--green-deep);
}
.qr-item i { font-size: 1.4rem; color: var(--green-mid); }
.qr-item span { font-size: .65rem; font-weight: 600; }

/* ── Family members table ── */
.fam-table-wrap { overflow-x: auto; margin-top: 12px; }
.fam-table { width: 100%; border-collapse: collapse; font-size: .7rem; }
.fam-table thead th {
  background: var(--bg); padding: 8px 10px; text-align: left;
  font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .55px;
  color: var(--text-3); border-bottom: 1px solid var(--border);
}
.fam-table td { padding: 8px 10px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.fam-table tbody tr:hover td { background: var(--surface2); }

/* ── Badges ── */
.badge2 {
  display: inline-flex; align-items: center; gap: 3px;
  padding: 2px 8px; border-radius: 20px; font-size: .6rem; font-weight: 600;
}
.badge2.green   { background: #e8f5ee; color: var(--green-deep); }
.badge2.orange  { background: #fef0e8; color: var(--orange-deep); }
.badge2.amber   { background: #fff8e8; color: #c08000; }
.badge2.neutral { background: var(--bg); color: var(--text-2); border: 1px solid var(--border); }
</style>

<div class="page-wrap">

  <!-- ── Breadcrumb ── -->
  <div class="breadcrumb-bar">
    <a href="/beneficiaries/resident-list"><i class="fa-solid fa-users me-1"></i>Resident List</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span style="color:var(--text-1);font-weight:600">View Beneficiary</span>
  </div>

  <!-- ── Header ── -->
  <div class="view-header">
    <div class="view-header-left">
      <div class="view-header-icon"><i class="fa-solid fa-eye"></i></div>
      <div class="view-header-text">
        <h5>Beneficiary Information</h5>
        <p>Complete details for <?= $resident['first_name'] ?> <?= $resident['last_name'] ?></p>
      </div>
    </div>
    <div class="hh-display">
      <div>
        <div class="hh-label">Household No.</div>
        <div class="hh-val mono"><?= $resident['household_no'] ?? '—' ?></div>
      </div>
    </div>
  </div>

  <!-- ── Actions ── -->
  <div style="background: var(--surface2); border: 1px solid var(--border); border-top: none; padding: 12px 22px; display: flex; gap: 8px; flex-wrap: wrap;">
    <a href="/beneficiaries/edit/<?= $resident['id'] ?>" class="btn-primary-c">
      <i class="fa-solid fa-pen-to-square"></i> Edit
    </a>
    <a href="/beneficiaries/view-household-qr/<?= $resident['id'] ?>" target="_blank" class="btn-outline-c">
      <i class="fa-solid fa-qrcode"></i> View Household QR
    </a>
    <a href="/residentportal/generateQR/<?= $resident['id'] ?>" target="_blank" class="btn-outline-c">
      <i class="fa-solid fa-print"></i> Print Head QR
    </a>
    <a href="/beneficiaries/resident-list" class="btn-outline-c" style="margin-left:auto;">
      <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
  </div>

  <!-- ── Content ── -->
  <div class="content-card">

    <!-- Main Information Grid -->
    <div class="info-grid">

      <!-- Section: Location -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-location-dot"></i> Location
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">Region:</span><span class="info-value"><?= $resident['region'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Province:</span><span class="info-value"><?= $resident['province'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">District:</span><span class="info-value"><?= $resident['district'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">City/Municipality:</span><span class="info-value"><?= $resident['city_municipality'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Barangay:</span><span class="info-value highlight"><?= $resident['barangay'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Evacuation Center:</span><span class="info-value"><?= $resident['evacuation_center'] ?: '—' ?></span></div>
        </div>
      </div>

      <!-- Section: Personal Information -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-user"></i> Personal Information
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">Full Name:</span><span class="info-value highlight"><?= $resident['last_name'] ?>, <?= $resident['first_name'] ?> <?= $resident['middle_name'] ?><?= $resident['name_extension'] ? ' ' . $resident['name_extension'] : '' ?></span></div>
          <div class="info-row"><span class="info-label">Birthdate:</span><span class="info-value"><?= $resident['birthdate'] ?: '—' ?> (<?= $resident['age'] ?> yrs old)</span></div>
          <div class="info-row"><span class="info-label">Birthplace:</span><span class="info-value"><?= $resident['birthplace'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Sex:</span><span class="info-value"><?= $resident['sex'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Civil Status:</span><span class="info-value"><?= $resident['civil_status'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Religion:</span><span class="info-value"><?= $resident['religion'] ?: '—' ?></span></div>
        </div>
      </div>

      <!-- Section: Contact Information -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-phone"></i> Contact & ID
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">Contact Number:</span><span class="info-value highlight"><?= $resident['contact_number'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Alternate Number:</span><span class="info-value"><?= $resident['alternate_number'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">ID Card Presented:</span><span class="info-value"><?= $resident['id_card_presented'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">ID Card Number:</span><span class="info-value"><?= $resident['id_card_number'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">ID Pictures:</span><span class="info-value">
            <?php if(!empty($resident['id_picture_front'])): ?>
              <a href="/<?= $resident['id_picture_front'] ?>" target="_blank" class="badge2 green"><i class="fa-solid fa-image"></i> Front</a>
            <?php endif; ?>
            <?php if(!empty($resident['id_picture_back'])): ?>
              <a href="/<?= $resident['id_picture_back'] ?>" target="_blank" class="badge2 green"><i class="fa-solid fa-image"></i> Back</a>
            <?php endif; ?>
          </span></div>
        </div>
      </div>

      <div class="info-section">
    <div class="info-section-header">
        <i class="fa-solid fa-camera"></i> Head of Family Photo
    </div>
    <div class="info-section-body">
        <div class="info-row">
            <span class="info-label">Profile Photo:</span>
            <span class="info-value">
                <?php if(!empty($resident['photo'])): ?>
                    <a href="/<?= $resident['photo'] ?>" target="_blank" style="display: inline-block;">
                        <img src="/<?= $resident['photo'] ?>" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 2px solid var(--border);">
                    </a>
                    <div style="margin-top: 4px;">
                        <a href="/<?= $resident['photo'] ?>" target="_blank" class="badge2 green">
                            <i class="fa-solid fa-image"></i> View Full Size
                        </a>
                    </div>
                <?php else: ?>
                    <span class="badge2 neutral">No photo uploaded</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>

      <!-- Section: Household & Additional Info -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-house"></i> Household Information
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">Household No.:</span><span class="info-value highlight"><?= $resident['household_no'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Mother's Maiden Name:</span><span class="info-value"><?= $resident['mother_maiden_name'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Occupation:</span><span class="info-value"><?= $resident['occupation'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Monthly Income:</span><span class="info-value">₱<?= number_format($resident['monthly_income'] ?? 0, 2) ?></span></div>
          <div class="info-row"><span class="info-label">4Ps Beneficiary:</span><span class="info-value"><?= ($resident['is_4ps_beneficiary'] ?? 0) ? 'Yes' : 'No' ?></span></div>
          <div class="info-row"><span class="info-label">IP Ethnicity:</span><span class="info-value"><?= $resident['ip_ethnicity'] ?: '—' ?></span></div>
        </div>
      </div>

      <!-- Section: Permanent Address -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-map-pin"></i> Permanent Address
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">House No./Lot:</span><span class="info-value"><?= $resident['house_no'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Street:</span><span class="info-value"><?= $resident['street'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Subdivision:</span><span class="info-value"><?= $resident['subdivision'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Barangay:</span><span class="info-value"><?= $resident['permanent_barangay'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">City/Municipality:</span><span class="info-value"><?= $resident['permanent_city'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Province:</span><span class="info-value"><?= $resident['permanent_province'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Zip Code:</span><span class="info-value"><?= $resident['zip_code'] ?: '—' ?></span></div>
        </div>
      </div>

      <!-- Section: Vulnerability & Status -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-heart-pulse"></i> Vulnerability & Status
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">Older Persons:</span><span class="info-value"><?= $resident['vulnerable_older_persons'] ?? 0 ?></span></div>
          <div class="info-row"><span class="info-label">Pregnant Women:</span><span class="info-value"><?= $resident['vulnerable_pregnant'] ?? 0 ?></span></div>
          <div class="info-row"><span class="info-label">Lactating Women:</span><span class="info-value"><?= $resident['vulnerable_lactating'] ?? 0 ?></span></div>
          <div class="info-row"><span class="info-label">PWDs:</span><span class="info-value"><?= $resident['vulnerable_pwd'] ?? 0 ?></span></div>
          <div class="info-row"><span class="info-label">Shelter Damage:</span><span class="info-value">
            <?php
            $damage = $resident['shelter_damage'] ?? 'No Damage';
            $badgeClass = 'neutral';
            if($damage == 'Partially Damaged') $badgeClass = 'amber';
            if($damage == 'Totally Damaged') $badgeClass = 'orange';
            ?>
            <span class="badge2 <?= $badgeClass ?>"><?= $damage ?></span>
          </span></div>
          <div class="info-row"><span class="info-label">Ownership Status:</span><span class="info-value"><?= $resident['ownership_status'] ?? '—' ?></span></div>
          <div class="info-row"><span class="info-label">Registration Status:</span><span class="info-value">
            <span class="badge2 <?= ($resident['status'] ?? 'pending') == 'claimed' ? 'green' : 'neutral' ?>">
              <?= ucfirst($resident['status'] ?? 'pending') ?>
            </span>
          </span></div>
        </div>
      </div>

      <!-- Section: Authentication -->
      <div class="info-section">
        <div class="info-section-header">
          <i class="fa-solid fa-signature"></i> Authentication
        </div>
        <div class="info-section-body">
          <div class="info-row"><span class="info-label">Registration Date:</span><span class="info-value"><?= $resident['registration_date'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Barangay Captain:</span><span class="info-value"><?= $resident['barangay_captain_name'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">LSWDO Name:</span><span class="info-value"><?= $resident['lswdo_name'] ?: '—' ?></span></div>
          <div class="info-row"><span class="info-label">Signature/Thumbmark:</span><span class="info-value">
            <?php if(!empty($resident['signature_thumbmark'])): ?>
              <a href="/<?= $resident['signature_thumbmark'] ?>" target="_blank" class="badge2 green"><i class="fa-solid fa-file-image"></i> View</a>
            <?php else: ?>—<?php endif; ?>
          </span></div>
          <div class="info-row"><span class="info-label">Right Thumbmark:</span><span class="info-value">
            <?php if(!empty($resident['right_thumbmark'])): ?>
              <a href="/<?= $resident['right_thumbmark'] ?>" target="_blank" class="badge2 green"><i class="fa-solid fa-file-image"></i> View</a>
            <?php else: ?>—<?php endif; ?>
          </span></div>
        </div>
      </div>
    </div>

    <!-- QR Codes Section -->
    <?php if(!empty($resident['qr_code_token']) || !empty($resident['household_qr_token'])): ?>
    <div style="margin: 20px 0; padding: 16px; background: var(--surface2); border-radius: var(--radius-sm); border: 1px solid var(--border);">
      <div style="font-size: .7rem; font-weight: 700; color: var(--text-2); margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-qrcode" style="color:var(--green-mid)"></i> QR Codes
      </div>
      <div class="qr-codes">
        <?php if(!empty($resident['qr_code_token'])): ?>
        <a href="/residentportal/generateQR/<?= $resident['id'] ?>" target="_blank" class="qr-item">
          <i class="fa-solid fa-qrcode"></i>
          <span>Head of Family QR</span>
        </a>
        <?php endif; ?>
        <?php if(!empty($resident['household_qr_token'])): ?>
        <a href="/beneficiaries/view-household-qr/<?= $resident['id'] ?>" target="_blank" class="qr-item">
          <i class="fa-solid fa-qrcode"></i>
          <span>Household QR</span>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Family Members Section -->
    <?php if(!empty($family_members)): ?>
    <div style="margin-top: 24px;">
      <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
        <div style="width: 24px; height: 24px; border-radius: 6px; background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: .65rem;">
          <i class="fa-solid fa-people-group"></i>
        </div>
        <h6 style="font-size: .75rem; font-weight: 800; color: var(--text-1); margin: 0;">Family Members (<?= count($family_members) ?>)</h6>
      </div>
      <div class="fam-table-wrap">
        <table class="fam-table">
          <thead>
            <tr>
              <th>Member ID</th>
              <th>Name</th>
              <th>Relation</th>
              <th>Birthdate</th>
              <th>Age</th>
              <th>Sex</th>
              <th>Education</th>
              <th>Occupation</th>
              <th>QR Code</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
<?php foreach($family_members as $member): 
  $memberInitials = strtoupper(substr($member['name'] ?? 'M', 0, 1));
?>
<tr>
  <td><span class="badge2 neutral"><?= $member['member_id'] ?? '—' ?></span></td>
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
      <span style="font-weight:600;"><?= $member['name'] ?></span>
    </div>
  </td>
  <td><?= $member['relation'] ?></td>
  <td><?= $member['birthdate'] ?: '—' ?></td>
  <td><?= $member['age'] ?: '—' ?></td>
  <td><?= $member['sex'] ?></td>
  <td><?= $member['education'] ?: '—' ?></td>
  <td><?= $member['occupation'] ?: '—' ?></td>
  <td>
    <?php if(!empty($member['qr_code_token'])): ?>
    <a href="/residentportal/view-member-qr/<?= $resident['id'] ?>/<?= $member['id'] ?>" target="_blank" style="color: var(--green-mid);" title="View QR">
      <i class="fa-solid fa-qrcode"></i>
    </a>
    <?php else: ?>—<?php endif; ?>
  </td>
  <td><?= $member['remarks'] ?: '—' ?></td>
</tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Document Links -->
    <?php if(!empty($family_members) && array_filter(array_column($family_members, 'id_photo_front'))): ?>
    <div style="margin-top: 24px;">
      <div style="font-size: .7rem; font-weight: 700; color: var(--text-2); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-folder-open" style="color:var(--orange-mid)"></i> Member Documents
      </div>
      <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <?php foreach($family_members as $member): ?>
          <?php if(!empty($member['id_photo_front'])): ?>
          <a href="/<?= $member['id_photo_front'] ?>" target="_blank" class="badge2 green">
            <i class="fa-solid fa-file-pdf"></i> <?= $member['name'] ?> - ID/Birth Cert
          </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <!-- ── Footer Actions ── -->
  <div style="margin-top: 16px; display: flex; gap: 8px; justify-content: flex-end;">
    <a href="/beneficiaries/resident-list" class="btn-outline-c">
      <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>
    <a href="/beneficiaries/edit/<?= $resident['id'] ?>" class="btn-primary-c">
      <i class="fa-solid fa-pen-to-square"></i> Edit Beneficiary
    </a>
  </div>

</div>

<?= $this->endSection() ?>