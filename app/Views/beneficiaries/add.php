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
  --red:          #c0392b;
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

.page-wrap { animation: fadeUp .4s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none} }

/* ── Breadcrumb ── */
.breadcrumb-bar {
  display: flex; align-items: center; gap: 6px;
  font-size: .7rem; color: var(--text-3); margin-bottom: 14px;
}
.breadcrumb-bar a { color: var(--green-mid); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { color: var(--green-deep); }
.breadcrumb-bar i { font-size: .55rem; color: var(--text-3); }

/* ── Page header ── */
.form-header {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius) var(--radius) 0 0;
  padding: 16px 22px;
}
.form-header-left { display: flex; align-items: center; gap: 12px; }
.form-header-icon {
  width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 1rem;
  box-shadow: 0 4px 12px rgba(119,188,63,.3);
}
.form-header-text h5 { font-size: .95rem; font-weight: 800; color: var(--text-1); margin: 0; letter-spacing: -.2px; }
.form-header-text p  { font-size: .67rem; color: var(--text-3); margin: 2px 0 0; }

/* ── Step progress bar ── */
.step-bar {
  background: var(--surface2); border: 1px solid var(--border); border-top: none;
  padding: 12px 22px; display: flex; gap: 4px; overflow-x: auto;
}
.step-pill {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 5px 12px; border-radius: 20px; font-size: .63rem; font-weight: 700;
  white-space: nowrap; cursor: pointer; transition: all .2s;
  border: 1.5px solid var(--border); background: var(--surface); color: var(--text-3);
}
.step-pill .sp-num {
  width: 16px; height: 16px; border-radius: 50%;
  background: var(--border); color: var(--text-3);
  display: flex; align-items: center; justify-content: center; font-size: .55rem; font-weight: 800;
  flex-shrink: 0;
}
.step-pill.active { border-color: var(--green-mid); background: var(--green-bg); color: var(--green-deep); }
.step-pill.active .sp-num { background: var(--green-mid); color: #fff; }
.step-pill.done   { border-color: var(--green-glow); background: var(--green-bg); color: var(--green-mid); opacity: .7; }
.step-pill.done .sp-num { background: var(--green-light); color: #fff; }

/* ── Form body ── */
.form-body {
  background: var(--surface); border: 1px solid var(--border); border-top: none;
  padding: 22px; border-radius: 0 0 var(--radius) var(--radius);
}

/* ── Section card ── */
.section-card {
  border: 1px solid var(--border); border-radius: var(--radius-sm);
  margin-bottom: 16px; overflow: hidden;
  transition: box-shadow .2s;
}
.section-card:hover { box-shadow: var(--shadow-sm); }

.section-head {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 16px; cursor: pointer; user-select: none;
  background: var(--surface2);
  border-bottom: 1px solid var(--border);
  transition: background .15s;
}
.section-head:hover { background: var(--green-bg); }

.section-num {
  width: 22px; height: 22px; border-radius: 6px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: .62rem; font-weight: 800; color: #fff;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
}
.section-num.orange { background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid)); }
.section-num.amber  { background: linear-gradient(135deg, #c08000, var(--amber)); }

.section-title { font-size: .75rem; font-weight: 700; color: var(--text-1); flex: 1; letter-spacing: .2px; }
.section-toggle { color: var(--text-3); font-size: .65rem; transition: transform .25s; }
.section-card.collapsed .section-toggle { transform: rotate(-90deg); }

.section-body { padding: 16px; animation: slideIn .2s ease; }
@keyframes slideIn { from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:none} }
.section-card.collapsed .section-body { display: none; }

/* ── Form elements ── */
.form-row { display: grid; gap: 12px; margin-bottom: 12px; }
.form-row.cols-2 { grid-template-columns: repeat(2, 1fr); }
.form-row.cols-3 { grid-template-columns: repeat(3, 1fr); }
.form-row.cols-4 { grid-template-columns: repeat(4, 1fr); }
.form-row.cols-6 { grid-template-columns: repeat(6, 1fr); }
.form-row.cols-auto { grid-template-columns: 1fr 1fr 1fr 1.5fr 0.6fr; }
@media(max-width:768px) {
  .form-row.cols-2,.form-row.cols-3,.form-row.cols-4,.form-row.cols-6,.form-row.cols-auto {
    grid-template-columns: 1fr 1fr;
  }
}
@media(max-width:520px) {
  .form-row.cols-2,.form-row.cols-3,.form-row.cols-4,.form-row.cols-6,.form-row.cols-auto {
    grid-template-columns: 1fr;
  }
}

.form-group { display: flex; flex-direction: column; gap: 3px; }
.form-label {
  font-size: .65rem; font-weight: 700; color: var(--text-2);
  text-transform: uppercase; letter-spacing: .4px;
}
.form-label .req { color: var(--orange-deep); margin-left: 2px; }

.form-input, .form-select-custom {
  padding: 7px 10px; border: 1.5px solid var(--border); border-radius: 7px;
  font-size: .75rem; font-family: 'Outfit', sans-serif; color: var(--text-1);
  background: var(--surface); transition: border-color .2s, box-shadow .2s;
  width: 100%;
}
.form-input:focus, .form-select-custom:focus {
  outline: none; border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(119,188,63,.1);
}
.form-input.readonly-field {
  background: var(--bg); color: var(--text-3);
  font-family: 'DM Mono', monospace; font-size: .72rem;
}
.form-hint { font-size: .6rem; color: var(--text-3); margin-top: 2px; }

/* ── Radio/checkbox groups ── */
.radio-group, .check-group {
  display: flex; gap: 12px; flex-wrap: wrap; padding-top: 4px;
}
.radio-opt, .check-opt {
  display: flex; align-items: center; gap: 6px;
  font-size: .72rem; color: var(--text-2); cursor: pointer;
}
.radio-opt input, .check-opt input {
  accent-color: var(--green-mid); width: 14px; height: 14px; cursor: pointer;
}

/* ── File input ── */
.file-input-wrap {
  border: 1.5px dashed var(--border); border-radius: 7px;
  padding: 8px 12px; background: var(--surface2);
  display: flex; align-items: center; gap: 8px; cursor: pointer;
  transition: border-color .2s, background .2s;
}
.file-input-wrap:hover { border-color: var(--green-mid); background: var(--green-bg); }
.file-input-wrap input { display: none; }
.file-input-wrap .fi-icon { color: var(--green-mid); font-size: .9rem; }
.file-input-wrap .fi-text { font-size: .68rem; color: var(--text-3); }

/* ── File upload preview card ── */
.file-preview-card {
  display: flex; align-items: center; gap: 10px;
  background: var(--green-bg); border: 1.5px solid var(--green-glow);
  border-radius: 8px; padding: 8px 12px;
}
.file-preview-card img {
  width: 52px; height: 52px; border-radius: 7px; object-fit: cover;
  border: 2px solid var(--green-mid); cursor: zoom-in; flex-shrink: 0;
}
.file-preview-card .fp-info { flex: 1; min-width: 0; }
.file-preview-card .fp-name {
  font-size: .68rem; font-weight: 700; color: var(--green-deep);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.file-preview-card .fp-size { font-size: .6rem; color: var(--text-3); margin-top: 1px; }
.fp-remove-btn {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 4px 10px; border-radius: 6px; border: 1.5px solid var(--orange-light);
  background: var(--orange-bg); color: var(--orange-deep);
  font-size: .62rem; font-weight: 700; cursor: pointer;
  font-family: 'Outfit', sans-serif; transition: all .15s; flex-shrink: 0;
}
.fp-remove-btn:hover { background: var(--orange-deep); color: #fff; border-color: var(--orange-deep); }
.file-input-wrap .fi-name { font-size: .68rem; color: var(--green-deep); font-weight: 600; }
.file-preview { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; display: none; }

/* ── Household number display ── */
.hh-display {
  display: flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  border-radius: 8px; padding: 8px 12px; border: none;
}
.hh-display .hh-label { font-size: .6rem; color: rgba(255,255,255,.75); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.hh-display .hh-val   { font-size: 1rem; font-weight: 800; color: #fff; font-family: 'DM Mono', monospace; }

/* ── Family members table ── */
.fam-table-wrap { overflow-x: auto; }
.fam-table { width: 100%; border-collapse: collapse; font-size: .68rem; min-width: 900px; }
.fam-table thead th {
  background: var(--bg); padding: 7px 8px;
  font-size: .58rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
  color: var(--text-3); border-bottom: 1px solid var(--border); white-space: nowrap;
}
.fam-table td { padding: 5px 6px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.fam-table tbody tr:hover td { background: var(--surface2); }

.fam-input {
  padding: 5px 8px; border: 1.5px solid var(--border); border-radius: 6px;
  font-size: .68rem; font-family: 'Outfit', sans-serif; width: 100%;
  background: var(--surface); transition: border-color .2s;
}
.fam-input:focus { outline: none; border-color: var(--green-mid); box-shadow: 0 0 0 2px rgba(119,188,63,.08); }

.add-member-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border-radius: 8px;
  background: var(--green-bg); border: 1.5px solid var(--green-mid);
  color: var(--green-deep); font-size: .7rem; font-weight: 700;
  cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .2s;
  margin-bottom: 12px;
}
.add-member-btn:hover { background: var(--green-mid); color: #fff; }

.remove-row-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 24px; height: 24px; border-radius: 6px;
  background: var(--orange-bg); border: 1.5px solid var(--orange-light);
  color: var(--orange-deep); font-size: .65rem; cursor: pointer; transition: all .2s;
}
.remove-row-btn:hover { background: var(--orange-deep); color: #fff; border-color: var(--orange-deep); }

/* ── Vulnerable counter ── */
.vuln-counter {
  display: flex; flex-direction: column; gap: 4px;
  background: var(--surface2); border: 1px solid var(--border); border-radius: 8px;
  padding: 10px 12px;
}
.vuln-counter .vc-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-3); }
.vuln-counter .vc-ctrl  { display: flex; align-items: center; gap: 6px; margin-top: 2px; }
.vuln-counter input     { width: 50px; text-align: center; font-family: 'DM Mono', monospace; font-size: .8rem; font-weight: 700; }
.vc-btn {
  width: 24px; height: 24px; border-radius: 5px; border: 1.5px solid var(--border);
  background: var(--surface); display: flex; align-items: center; justify-content: center;
  cursor: pointer; font-size: .7rem; color: var(--text-2); transition: all .15s;
}
.vc-btn:hover { background: var(--green-mid); color: #fff; border-color: var(--green-mid); }

/* ── Privacy consent box ── */
.consent-box {
  background: var(--amber-bg); border: 1.5px solid #f5e4a0; border-radius: 8px;
  padding: 12px 14px; display: flex; gap: 10px; align-items: flex-start;
}
.consent-box input { accent-color: var(--green-mid); margin-top: 2px; flex-shrink: 0; width: 15px; height: 15px; }
.consent-box label { font-size: .7rem; color: var(--text-2); line-height: 1.5; cursor: pointer; }

/* ── Submit bar ── */
.submit-bar {
  display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
  padding: 14px 22px; background: var(--surface2);
  border: 1px solid var(--border); border-top: none;
  border-radius: 0 0 var(--radius) var(--radius);
  margin-top: -1px;
}
.btn-save {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 22px; border-radius: 8px; border: none;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color: #fff; font-size: .78rem; font-weight: 700;
  cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .2s;
  box-shadow: 0 4px 14px rgba(119,188,63,.3);
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(119,188,63,.4); }
.btn-cancel {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 18px; border-radius: 8px;
  border: 1.5px solid var(--border); background: var(--surface);
  color: var(--text-2); font-size: .78rem; font-weight: 600;
  text-decoration: none; font-family: 'Outfit', sans-serif; transition: all .2s;
}
.btn-cancel:hover { border-color: var(--orange-mid); color: var(--orange-deep); background: var(--orange-bg); }

/* ── Shelter damage chips ── */
.shelter-group { display: flex; gap: 8px; flex-wrap: wrap; }
.shelter-chip {
  display: flex; align-items: center; gap: 6px;
  padding: 6px 12px; border-radius: 20px; border: 1.5px solid var(--border);
  background: var(--surface); font-size: .7rem; font-weight: 600; color: var(--text-2);
  cursor: pointer; transition: all .2s;
}
.shelter-chip input { position: absolute; opacity: 0; width: 0; height: 0; }
.shelter-chip.s-none     { }
.shelter-chip.s-partial  { }
.shelter-chip.s-total    { }
.shelter-chip:has(input:checked).s-none    { border-color: var(--green-mid); background: var(--green-bg); color: var(--green-deep); }
.shelter-chip:has(input:checked).s-partial { border-color: var(--amber); background: var(--amber-bg); color: #c08000; }
.shelter-chip:has(input:checked).s-total   { border-color: var(--orange-deep); background: var(--orange-bg); color: var(--orange-deep); }

/* ── Section divider label ── */
.sub-section-label {
  font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px;
  color: var(--text-3); display: flex; align-items: center; gap: 8px; margin: 12px 0 8px;
}
.sub-section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* ── Ownership radio chips ── */
.ownership-group { display: flex; gap: 8px; flex-wrap: wrap; }
.own-chip {
  display: flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: 8px; border: 1.5px solid var(--border);
  background: var(--surface); font-size: .72rem; font-weight: 600; color: var(--text-2);
  cursor: pointer; transition: all .2s;
}
.own-chip input { position: absolute; opacity: 0; width: 0; height: 0; }
.own-chip:has(input:checked) { border-color: var(--green-mid); background: var(--green-bg); color: var(--green-deep); }

/* ── Shelter damage visual ── */
.damage-meter { height: 4px; border-radius: 2px; background: var(--border); margin-top: 4px; overflow: hidden; }

/* ── Auto-capitalize all text inputs ── */
.form-input[type="text"], .form-input:not([type]), .fam-input[type="text"], .fam-input:not([type]) { text-transform: uppercase; }

.drag-handle {
    cursor: grab;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 5px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    color: var(--text-3);
    transition: all .2s;
    margin-right: 5px;
}

.drag-handle:active {
    cursor: grabbing;
}

.drag-handle:hover {
    background: var(--green-bg);
    border-color: var(--green-mid);
    color: var(--green-deep);
}

/* Sortable drag ghost styling */
.sortable-drag {
    opacity: 0.5;
    background: var(--surface2);
}

.sortable-ghost {
    opacity: 0.3;
    background: var(--green-bg);
    border: 2px dashed var(--green-mid);
}

/* Table row hover effect for drag */
#familyBody tr {
    transition: background .1s ease;
}

#familyBody tr:hover {
    background: var(--surface2);
}

/* Add a visual indicator for draggable rows */
#familyBody td:first-child {
    position: relative;
}

/* Photo source buttons */
.photo-source-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
}

.photo-source-btn.active {
    border-color: var(--green-mid);
    background: var(--green-bg);
    color: var(--green-deep);
}

.photo-source-btn:hover {
    border-color: var(--green-mid);
    background: var(--green-bg);
}
</style>

<div class="page-wrap">

  <!-- ── Breadcrumb ── -->
  <div class="breadcrumb-bar">
    <a href="/beneficiaries/resident-list"><i class="fa-solid fa-users me-1"></i>Resident List</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span style="color:var(--text-1);font-weight:600">New Beneficiary</span>
  </div>

  <!-- ── Page Header ── -->
  <div class="form-header">
    <div class="form-header-left">
      <div class="form-header-icon"><i class="fa-solid fa-user-plus"></i></div>
      <div class="form-header-text">
        <h5>Complete Resident Information Form</h5>
        <p>Fill out all required sections accurately. Fields marked <span style="color:var(--orange-deep)">*</span> are required.</p>
      </div>
    </div>
    <!-- Household number display -->
    <div class="hh-display">
      <div>
        <div class="hh-label">Household No.</div>
        <div class="hh-val" id="household_preview">Loading…</div>
      </div>
    </div>
  </div>

  <!-- ── Step Pills ── -->
  <div class="step-bar">
    <div class="step-pill active" onclick="scrollToSection('sec-i')"><span class="sp-num">I</span>Location</div>
    <div class="step-pill" onclick="scrollToSection('sec-ii')"><span class="sp-num">II</span>Family Head</div>
    <div class="step-pill" onclick="scrollToSection('sec-iii')"><span class="sp-num">III</span>Address</div>
    <div class="step-pill" onclick="scrollToSection('sec-iv')"><span class="sp-num">IV</span>Additional Info</div>
    <div class="step-pill" onclick="scrollToSection('sec-v')"><span class="sp-num">V</span>Family Members</div>
    <div class="step-pill" onclick="scrollToSection('sec-vi')"><span class="sp-num">VI</span>Vulnerable</div>
    <div class="step-pill" onclick="scrollToSection('sec-vii')"><span class="sp-num">VII</span>Shelter</div>
    <div class="step-pill" onclick="scrollToSection('sec-viii')"><span class="sp-num">VIII</span>Ownership</div>
    <div class="step-pill" onclick="scrollToSection('sec-ix')"><span class="sp-num">IX</span>Authentication</div>
  </div>

  <!-- ── Form ── -->
  <form action="/beneficiaries/store" method="post" id="beneficiaryForm" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="household_no" id="household_no">

    <div class="form-body">

      <!-- ══ I. LOCATION ══ -->
      <div class="section-card" id="sec-i">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num">I</div>
          <div class="section-title">Location of Affected Family</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-3">
            <div class="form-group">
              <label class="form-label">Region</label>
              <input type="text" name="region" class="form-input" placeholder="e.g. Region XI">
            </div>
            <div class="form-group">
              <label class="form-label">Province</label>
              <input type="text" name="province" class="form-input" placeholder="Province name">
            </div>
            <div class="form-group">
              <label class="form-label">District</label>
              <input type="text" name="district" class="form-input" placeholder="District">
            </div>
            <div class="form-group">
              <label class="form-label">City / Municipality</label>
              <input type="text" name="city_municipality" class="form-input" placeholder="City or Municipality">
            </div>
            <div class="form-group">
              <label class="form-label">Barangay <span class="req">*</span></label>
              <input type="text" name="barangay" class="form-input" placeholder="Barangay" required>
            </div>
            <div class="form-group">
              <label class="form-label">Evacuation Center / Site</label>
              <input type="text" name="evacuation_center" class="form-input" placeholder="Evacuation center name">
            </div>
          </div>
        </div>
      </div>

      <!-- ══ II. HEAD OF FAMILY ══ -->
      <div class="section-card" id="sec-ii">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num">II</div>
          <div class="section-title">Head of the Family</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
<div class="sub-section-label">Head of Family Photo</div>
<div class="form-row cols-1">
    <div class="form-group">
        <label class="form-label">Profile Photo of Family Head</label>

        <!-- Buttons shown when no photo -->
        <div id="head-photo-btns" style="display:flex; gap:10px; margin-bottom:12px;">
            <label class="photo-source-btn" style="cursor:pointer;">
                <i class="fa-solid fa-cloud-upload-alt"></i> Upload Photo
                <input type="file" name="head_photo" id="head_photo_file" accept="image/*" style="display:none" onchange="handleHeadPhoto(this)">
            </label>
            <button type="button" class="photo-source-btn" onclick="openCameraModal()">
                <i class="fa-solid fa-camera"></i> Capture Photo
            </button>
        </div>

        <!-- Preview shown after photo selected/captured -->
        <div id="head-photo-preview-wrap" style="display:none; align-items:center; gap:12px; margin-bottom:12px;">
            <img id="prev-head" style="width:70px;height:70px;border-radius:10px;object-fit:cover;border:2px solid var(--green-mid);box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            <button type="button" onclick="removeHeadPhoto()" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;border:1.5px solid var(--orange-light);background:var(--orange-bg);color:var(--orange-deep);font-size:.7rem;font-weight:600;cursor:pointer;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </div>

        <div class="form-hint">Upload or capture a clear photo of the family head (JPG, PNG)</div>
        <input type="hidden" name="head_photo_captured" id="head_photo_captured" value="">
    </div>
</div>

          <div class="sub-section-label">Name</div>
          <div class="form-row cols-auto">
            <div class="form-group">
              <label class="form-label">Last Name <span class="req">*</span></label>
              <input type="text" name="last_name" class="form-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">First Name <span class="req">*</span></label>
              <input type="text" name="first_name" class="form-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Middle Name</label>
              <input type="text" name="middle_name" class="form-input">
            </div>
            <div class="form-group" style="grid-column:span 1">
              <label class="form-label">Extension</label>
              <input type="text" name="name_extension" class="form-input" placeholder="Jr./Sr.">
            </div>
          </div>

          <div class="sub-section-label">Personal Details</div>
          <div class="form-row cols-4">
            <div class="form-group">
              <label class="form-label">Birthdate</label>
              <input type="date" name="birthdate" class="form-input" id="birthdate" onchange="calculateAge()">
            </div>
            <div class="form-group">
              <label class="form-label">Age</label>
              <input type="number" name="age" class="form-input readonly-field" id="age" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Birthplace</label>
              <input type="text" name="birthplace" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Religion</label>
              <input type="text" name="religion" class="form-input">
            </div>
          </div>

          <div class="form-row cols-3">
            <div class="form-group">
              <label class="form-label">Sex <span class="req">*</span></label>
              <div class="radio-group" style="padding-top:6px">
                <label class="radio-opt"><input type="radio" name="sex" value="Male"> Male</label>
                <label class="radio-opt"><input type="radio" name="sex" value="Female"> Female</label>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Civil Status</label>
              <select name="civil_status" class="form-select-custom form-input">
                <option value="">Select status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Mother's Maiden Name</label>
              <input type="text" name="mother_maiden_name" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Occupation</label>
              <input type="text" name="occupation" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Monthly Family Net Income</label>
              <input type="number" name="monthly_income" class="form-input" step="0.01" placeholder="0.00">
            </div>
          </div>

          <div class="sub-section-label">Identification</div>
          <div class="form-row cols-4">
            <div class="form-group">
              <label class="form-label">ID Card Presented</label>
              <input type="text" name="id_card_presented" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">ID Card Number</label>
              <input type="text" name="id_card_number" class="form-input mono">
            </div>
            <div class="form-group">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Alternate Number</label>
              <input type="text" name="alternate_number" class="form-input">
            </div>
          </div>

          <div class="sub-section-label">ID Photos</div>
          <div class="form-row cols-2">
            <div class="form-group">
              <label class="form-label">ID Picture (Front)</label>
              <label class="file-input-wrap" id="wrap-front">
                <i class="fa-solid fa-id-card fi-icon"></i>
                <div>
                  <div class="fi-text">Click to upload front of ID</div>
                  <div class="fi-name" id="name-front">No file selected</div>
                </div>
                <img class="file-preview" id="prev-front">
                <input type="file" name="id_picture_front" accept="image/*" onchange="handleFile(this,'name-front','prev-front')">
              </label>
            </div>
            <div class="form-group">
              <label class="form-label">ID Picture (Back)</label>
              <label class="file-input-wrap" id="wrap-back">
                <i class="fa-solid fa-id-card fi-icon"></i>
                <div>
                  <div class="fi-text">Click to upload back of ID</div>
                  <div class="fi-name" id="name-back">No file selected</div>
                </div>
                <img class="file-preview" id="prev-back">
                <input type="file" name="id_picture_back" accept="image/*" onchange="handleFile(this,'name-back','prev-back')">
              </label>
            </div>
          </div>

        </div>
      </div>

      <!-- ══ III. PERMANENT ADDRESS ══ -->
      <div class="section-card" id="sec-iii">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num">III</div>
          <div class="section-title">Permanent Address</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-4">
            <div class="form-group">
              <label class="form-label">House / Lot No.</label>
              <input type="text" name="house_no" class="form-input">
            </div>
            <div class="form-group" style="grid-column:span 2">
              <label class="form-label">Street</label>
              <input type="text" name="street" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Subdivision / Village</label>
              <input type="text" name="subdivision" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Barangay</label>
              <input type="text" name="permanent_barangay" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">City / Municipality</label>
              <input type="text" name="permanent_city" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Province</label>
              <input type="text" name="permanent_province" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Zip Code</label>
              <input type="text" name="zip_code" class="form-input mono">
            </div>
          </div>
        </div>
      </div>

      <!-- ══ IV. ADDITIONAL INFO ══ -->
      <div class="section-card" id="sec-iv">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num orange">IV</div>
          <div class="section-title">Additional Information</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-2">
            <div class="form-group">
              <label class="form-label">4Ps Beneficiary</label>
              <label class="check-opt" style="padding-top:4px">
                <input type="checkbox" name="is_4ps_beneficiary" value="1"> Yes, this family is a 4Ps beneficiary
              </label>
            </div>
            <div class="form-group">
              <label class="form-label">IP Type / Ethnicity</label>
              <input type="text" name="ip_ethnicity" class="form-input">
            </div>
          </div>
        </div>
      </div>

<!-- ══ V. FAMILY MEMBERS ══ -->
<div class="section-card" id="sec-v">
    <div class="section-head" onclick="toggleSection(this)">
        <div class="section-num">V</div>
        <div class="section-title">Family Information <span style="font-size:0.6rem; margin-left:8px; color:var(--text-3); font-weight:normal;">(Drag ↕️ to reorder)</span></div>
        <i class="fa-solid fa-chevron-down section-toggle"></i>
    </div>
    <div class="section-body">
        <button type="button" class="add-member-btn" onclick="addFamilyMember()">
            <i class="fa-solid fa-plus"></i> Add Family Member
        </button>
        <div class="fam-table-wrap">
            <table class="fam-table" id="familyTable">
<thead>
    <tr>
        <th style="width: 30px;"></th> <!-- Drag handle column -->
        <th>Name</th>
        <th>Relation</th>
        <th>Birthdate</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Education</th>
        <th>Occupation</th>
        <th>Photo</th>
        <th>Remarks</th>
        <th></th>
    </tr>
</thead>
                <tbody id="familyBody">
                    <tr id="fam-empty">
                        <td colspan="12" style="text-align:center;padding:20px;color:var(--text-3);font-size:.7rem">
                            <i class="fa-solid fa-people-group" style="font-size:1.2rem;display:block;margin-bottom:6px;opacity:.3"></i>
                            No family members added yet. Click "Add Family Member" above.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

      <!-- ══ VI. VULNERABLE ══ -->
      <div class="section-card" id="sec-vi">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num orange">VI</div>
          <div class="section-title">Vulnerable Family Members</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-4">
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-person-cane me-1"></i>Older Persons</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_older_persons',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_older_persons" class="form-input" value="0" min="0" style="width:50px;text-align:center">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_older_persons',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-baby me-1"></i>Pregnant Women</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pregnant',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_pregnant" class="form-input" value="0" min="0" style="width:50px;text-align:center">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pregnant',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-hands-holding-child me-1"></i>Lactating Women</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_lactating',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_lactating" class="form-input" value="0" min="0" style="width:50px;text-align:center">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_lactating',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-wheelchair me-1"></i>PWDs</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pwd',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_pwd" class="form-input" value="0" min="0" style="width:50px;text-align:center">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pwd',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ VII. SHELTER DAMAGE (was missing from original - added) ══ -->
      <div class="section-card" id="sec-vii">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num amber">VII</div>
          <div class="section-title">Shelter Damage Assessment</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-group">
            <label class="form-label">Shelter Damage Status</label>
            <div class="shelter-group" style="margin-top:4px">
              <label class="shelter-chip s-none">
                <input type="radio" name="shelter_damage" value="No Damage" checked>
                <i class="fa-solid fa-house-circle-check" style="color:var(--green-mid)"></i> No Damage
              </label>
              <label class="shelter-chip s-partial">
                <input type="radio" name="shelter_damage" value="Partially Damaged">
                <i class="fa-solid fa-house-crack" style="color:var(--amber)"></i> Partially Damaged
              </label>
              <label class="shelter-chip s-total">
                <input type="radio" name="shelter_damage" value="Totally Damaged">
                <i class="fa-solid fa-house-tsunami" style="color:var(--orange-deep)"></i> Totally Damaged
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ VIII. OWNERSHIP ══ -->
      <div class="section-card" id="sec-viii">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num">VIII</div>
          <div class="section-title">Family Ownership Status</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-group">
            <label class="form-label">Ownership Type</label>
            <div class="ownership-group" style="margin-top:4px">
              <label class="own-chip">
                <input type="radio" name="ownership_status" value="Owner" checked>
                <i class="fa-solid fa-key"></i> Owner
              </label>
              <label class="own-chip">
                <input type="radio" name="ownership_status" value="Renter">
                <i class="fa-solid fa-file-contract"></i> Renter
              </label>
              <label class="own-chip">
                <input type="radio" name="ownership_status" value="Sharer">
                <i class="fa-solid fa-handshake"></i> Sharer
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ IX. AUTHENTICATION ══ -->
      <div class="section-card" id="sec-ix">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num orange">IX</div>
          <div class="section-title">Authentication</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-2" style="margin-bottom:12px">
<div class="form-group">
  <label class="form-label">Signature of the Family Head</label>

  <!-- Toggle buttons -->
  <div style="display:flex;gap:8px;margin-bottom:10px;">
    <button type="button" class="photo-source-btn active" id="sig-btn-upload" onclick="setSigSource('upload')">
      <i class="fa-solid fa-cloud-upload-alt"></i> Upload
    </button>
    <button type="button" class="photo-source-btn" id="sig-btn-draw" onclick="setSigSource('draw')">
      <i class="fa-solid fa-pen-nib"></i> Draw Signature
    </button>
  </div>

  <!-- Upload option -->
  <div id="sig-upload-option">
    <label class="file-input-wrap">
      <i class="fa-solid fa-signature fi-icon"></i>
      <div>
        <div class="fi-text">Upload signature or thumbmark image</div>
        <div class="fi-name" id="name-sig">No file selected</div>
      </div>
      <input type="file" name="signature_thumbmark" id="sig-file-input" accept="image/*" onchange="handleFile(this,'name-sig',null)">
    </label>
  </div>

  <!-- Draw option (hidden by default) — clicking the tab auto-opens the modal -->
  <div id="sig-draw-option" style="display:none;">
    <!-- Saved preview (shown after drawing) -->
    <div id="sig-saved-preview" style="display:none;margin-bottom:8px;">
      <div class="file-preview-card">
        <img id="sig-preview-img" onclick="openLightbox(this.src)" alt="Drawn Signature" style="width:52px;height:52px;border-radius:7px;object-fit:contain;border:2px solid var(--green-mid);cursor:zoom-in;">
        <div class="fp-info">
          <div class="fp-name"><i class="fa-solid fa-check-circle" style="color:var(--green-mid)"></i> Signature saved</div>
          <div class="fp-size">Click image to view · Click Re-draw to redo</div>
        </div>
        <button type="button" class="fp-remove-btn" onclick="clearSigDraw()">
          <i class="fa-solid fa-rotate-left"></i> Re-draw
        </button>
      </div>
    </div>
    <!-- Inline mini canvas (hidden, only used as fallback) -->
    <canvas id="sig-canvas" style="display:none;"></canvas>
  </div>

  <!-- Hidden input that carries the base64 signature to the server -->
  <input type="hidden" name="signature_thumbmark_data" id="sig-data-input">
</div>
            <div class="form-group">
              <label class="form-label">Right Thumbmark</label>
              <label class="file-input-wrap">
                <i class="fa-solid fa-hand fi-icon"></i>
                <div>
                  <div class="fi-text">Upload right thumbmark image</div>
                  <div class="fi-name" id="name-thumb">No file selected</div>
                </div>
                <input type="file" name="right_thumbmark" accept="image/*" onchange="handleFile(this,'name-thumb',null)">
              </label>
            </div>
          </div>
          <div class="form-row cols-3">
            <div class="form-group">
              <label class="form-label">Date Registered</label>
              <input type="date" name="registration_date" class="form-input" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Name of Barangay Captain</label>
              <input type="text" name="barangay_captain_name" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Name of LSWDO</label>
              <input type="text" name="lswdo_name" class="form-input">
            </div>
          </div>
        </div>
      </div>


    </div><!-- /form-body -->

    <!-- ── Submit Bar ── -->
    <div class="submit-bar">
      <button type="submit" class="btn-save">
        <i class="fa-solid fa-floppy-disk"></i> Save Resident
      </button>
      <a href="/beneficiaries/resident-list" class="btn-cancel">
        <i class="fa-solid fa-xmark"></i> Cancel
      </a>
      <span style="margin-left:auto;font-size:.65rem;color:var(--text-3)">
        <i class="fa-solid fa-shield-halved me-1" style="color:var(--green-mid)"></i>
        Data protected under RA 10173
      </span>
    </div>

  </form>
</div>

<div id="sig-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.75);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
<div style="background:#fff;border-radius:20px;padding:28px;width:min(1100px,96vw);max-width:96vw;height:auto;box-shadow:0 20px 60px rgba(0,0,0,.3);animation:fadeUp .25s ease both;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <div>
        <div style="font-size:1.1rem;font-weight:800;color:var(--text-1);">
          <i class="fa-solid fa-pen-nib" style="color:var(--green-mid);margin-right:8px;"></i>Draw Signature / Thumbmark
        </div>
        <div style="font-size:.7rem;color:var(--text-3);margin-top:4px;">Sign inside the box using mouse or touch</div>
      </div>
      <button type="button" onclick="closeSigModal()" style="background:var(--bg);border:1.5px solid var(--border);border-radius:8px;width:34px;height:34px;cursor:pointer;font-size:.9rem;color:var(--text-2);display:flex;align-items:center;justify-content:center;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

<div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
  <div style="display:flex;align-items:center;gap:8px;font-size:.7rem;color:var(--text-2);">
    <label style="font-weight:600;">Color:</label>
    <input type="color" id="sig-pen-color" value="#1e293b" style="width:36px;height:30px;border:1.5px solid var(--border);border-radius:5px;cursor:pointer;padding:2px;">
  </div>
  <div style="display:flex;align-items:center;gap:8px;font-size:.7rem;color:var(--text-2);">
    <label style="font-weight:600;">Size:</label>
    <input type="range" id="sig-pen-size" min="1" max="12" value="2" style="width:120px;accent-color:var(--green-mid);">
    <span id="sig-pen-size-label" style="font-size:.65rem;color:var(--text-3);min-width:24px;">2</span>
  </div>
  <!-- Eraser toggle -->
  <button type="button" id="sig-eraser-btn" onclick="toggleEraser()" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.7rem;cursor:pointer;">
    <i class="fa-solid fa-eraser"></i> Eraser
  </button>
  <!-- Eraser size (shown when eraser is active) -->
  <div id="sig-eraser-size-wrap" style="display:none;align-items:center;gap:6px;font-size:.7rem;color:var(--text-2);">
    <label style="font-weight:600;">Eraser Size:</label>
    <input type="range" id="sig-eraser-size" min="5" max="60" value="20" style="width:90px;accent-color:var(--orange-mid);">
    <span id="sig-eraser-size-label" style="font-size:.65rem;color:var(--text-3);min-width:24px;">20</span>
  </div>
  <button type="button" onclick="clearModalCanvas()" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.7rem;cursor:pointer;">
    <i class="fa-solid fa-trash"></i> Clear All
  </button>
</div>

<canvas id="sig-modal-canvas" 
  style="width:100%;height:420px;border:2px solid var(--border);border-radius:8px;background:#fff;cursor:crosshair;touch-action:none;display:block;">
    </canvas>

    <div style="display:flex;gap:12px;margin-top:20px;justify-content:flex-end;">
      <button type="button" class="btn-cancel" onclick="closeSigModal()" style="padding:8px 20px;"><i class="fa-solid fa-xmark"></i> Cancel</button>
<button type="button" class="btn-save" onclick="previewSigModal()" style="padding:8px 20px;"><i class="fa-solid fa-eye"></i> Preview Signature</button>
    </div>
  </div>
</div>

<!-- ── Signature Preview & Resize Modal ── -->
<div id="sig-preview-modal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.8);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:20px;padding:24px;width:min(560px,94vw);box-shadow:0 20px 60px rgba(0,0,0,.35);animation:fadeUp .25s ease both;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
      <div>
        <div style="font-size:1rem;font-weight:800;color:var(--text-1);">
          <i class="fa-solid fa-arrows-up-down-left-right" style="color:var(--green-mid);margin-right:8px;"></i>Adjust Signature Size
        </div>
        <div style="font-size:.68rem;color:var(--text-3);margin-top:3px;">Drag corners to resize · Drag body to reposition</div>
      </div>
      <button type="button" onclick="closeSigPreviewModal()" style="background:var(--bg);border:1.5px solid var(--border);border-radius:8px;width:34px;height:34px;cursor:pointer;font-size:.9rem;color:var(--text-2);display:flex;align-items:center;justify-content:center;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Preview stage — checkerboard = transparent bg indicator -->
    <div id="sig-resize-stage" style="
      position:relative;
      width:100%;
      height:280px;
      border:2px dashed var(--border);
      border-radius:10px;
      overflow:hidden;
      background-image:
        linear-gradient(45deg,#e0e0e0 25%,transparent 25%),
        linear-gradient(-45deg,#e0e0e0 25%,transparent 25%),
        linear-gradient(45deg,transparent 75%,#e0e0e0 75%),
        linear-gradient(-45deg,transparent 75%,#e0e0e0 75%);
      background-size:16px 16px;
      background-position:0 0,0 8px,8px -8px,-8px 0px;
      cursor:default;
    ">
      <!-- The signature image — acts as a draggable+resizable shape -->
      <img id="sig-resize-img" src="" draggable="false" style="
        position:absolute;
        left:50px; top:50px;
        width:320px; height:auto;
        cursor:move;
        user-select:none;
        border:2px solid var(--green-mid);
        border-radius:4px;
        background:transparent;
      ">
      <!-- Resize handle (bottom-right corner) -->
      <div id="sig-resize-handle" style="
        position:absolute;
        width:14px; height:14px;
        background:var(--green-mid);
        border:2px solid #fff;
        border-radius:3px;
        cursor:se-resize;
        z-index:10;
      "></div>
    </div>

    <!-- Size readout -->
    <div style="display:flex;align-items:center;gap:12px;margin-top:10px;font-size:.68rem;color:var(--text-3);">
      <span><i class="fa-solid fa-ruler-combined" style="color:var(--green-mid)"></i> W: <strong id="sig-resize-w-label">—</strong>px &nbsp; H: <strong id="sig-resize-h-label">—</strong>px</span>
      <button type="button" onclick="resetSigResize()" style="padding:3px 10px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.65rem;cursor:pointer;color:var(--text-2);">
        <i class="fa-solid fa-rotate-left"></i> Reset
      </button>
    </div>

    <div style="display:flex;gap:10px;margin-top:16px;justify-content:flex-end;">
      <button type="button" onclick="closeSigPreviewModal()" style="padding:8px 18px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.75rem;font-weight:600;cursor:pointer;">
        <i class="fa-solid fa-arrow-left"></i> Back to Draw
      </button>
      <button type="button" onclick="saveSigFromPreview()" style="padding:8px 20px;border-radius:8px;border:none;background:linear-gradient(135deg,var(--orange-deep),var(--orange-mid));color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(201,107,16,.3);">
        <i class="fa-solid fa-check"></i> Save Signature
      </button>
    </div>
  </div>
</div>

<!-- Camera Capture Modal - Larger Size -->
<div id="camera-modal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:24px;padding:28px;width:min(900px,95vw);max-width:95vw;box-shadow:0 30px 80px rgba(0,0,0,.4);animation:fadeUp .3s ease both;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <div>
                <div style="font-size:1.3rem;font-weight:800;color:var(--text-1);">
                    <i class="fa-solid fa-camera" style="color:var(--green-mid);margin-right:12px;"></i>Capture Profile Photo
                </div>
                <div style="font-size:.8rem;color:var(--text-3);margin-top:6px;">Position the face clearly in the frame for best results</div>
            </div>
            <button type="button" onclick="closeCameraModal()" style="background:var(--bg);border:1.5px solid var(--border);border-radius:10px;width:38px;height:38px;cursor:pointer;font-size:1rem;color:var(--text-2);display:flex;align-items:center;justify-content:center;transition:all .2s;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Camera preview area - LARGER -->
        <div style="position:relative;background:#1a1a1a;border-radius:16px;overflow:hidden;margin-bottom:20px;">
            <video id="camera-modal-video" autoplay playsinline style="width:100%;height:auto;min-height:400px;display:block;background:#1a1a1a;"></video>
            <canvas id="camera-modal-canvas" style="display:none;"></canvas>
        </div>

        <!-- Photo preview after capture -->
        <div id="camera-modal-preview" style="display:none;margin-bottom:20px;">
            <div style="border:2px solid var(--green-mid);border-radius:16px;padding:16px;background:var(--green-bg);">
                <div style="font-size:.7rem;color:var(--green-deep);font-weight:700;margin-bottom:10px;">
                    <i class="fa-solid fa-check-circle"></i> Captured Photo Preview
                </div>
                <img id="camera-modal-preview-img" style="width:100%;max-height:300px;border-radius:12px;object-fit:contain;background:#f0f0f0;">
            </div>
        </div>

        <!-- Control buttons - LARGER -->
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <button type="button" id="capture-modal-btn" class="btn-save" onclick="captureModalPhoto()" style="padding:12px 32px;font-size:0.9rem;">
                <i class="fa-solid fa-camera" style="margin-right:8px;"></i> Capture Photo
            </button>
            <button type="button" id="retake-modal-btn" class="btn-cancel" onclick="retakeModalPhoto()" style="display:none;padding:12px 32px;font-size:0.9rem;">
                <i class="fa-solid fa-rotate-left" style="margin-right:8px;"></i> Retake
            </button>
            <button type="button" id="confirm-modal-btn" class="btn-save" onclick="confirmModalPhoto()" style="display:none;padding:12px 32px;font-size:0.9rem;background:linear-gradient(135deg, var(--green-deep), var(--green-mid));">
                <i class="fa-solid fa-check" style="margin-right:8px;"></i> Use This Photo
            </button>
        </div>

        <div class="form-hint" style="text-align:center;margin-top:16px;font-size:0.7rem;">
            <i class="fa-solid fa-lightbulb"></i> Tips: Ensure good lighting, center your face, and keep the camera steady
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// ── Section toggle ──
function toggleSection(head) {
  head.closest('.section-card').classList.toggle('collapsed');
}
function scrollToSection(id) {
  const el = document.getElementById(id);
  if(el) { el.scrollIntoView({behavior:'smooth',block:'start'}); }
}

// ── Age calculator ──
function calculateAge() {
  const v = document.getElementById('birthdate').value;
  if(!v) return;
  const today = new Date(), bd = new Date(v);
  let age = today.getFullYear() - bd.getFullYear();
  const m = today.getMonth() - bd.getMonth();
  if(m < 0 || (m===0 && today.getDate() < bd.getDate())) age--;
  document.getElementById('age').value = age;
}

// ── File input display ──
function handleFile(input, nameId, previewId) {
  const file = input.files[0];
  document.getElementById(nameId).textContent = file ? file.name : 'No file selected';
  if(previewId && file) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById(previewId);
      img.src = e.target.result;
      img.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
}

// ── Vulnerable counter ──
function adjustCount(name, delta) {
  const input = document.querySelector(`input[name="${name}"]`);
  if(!input) return;
  const v = Math.max(0, parseInt(input.value||0) + delta);
  input.value = v;
}

// ── Update the addFamilyMember function to use the new handler ──
function addFamilyMember() {
    const empty = document.getElementById('fam-empty');
    if(empty) empty.remove();

    const tr = document.createElement('tr');
tr.innerHTML = `
    <td style="text-align:center; vertical-align:middle;">
        <div class="drag-handle">
            <i class="fa-solid fa-grip-vertical"></i>
        </div>
    </td>
    <td><input type="text" class="fam-input" name="member_name[]" placeholder="Full name"></td>
    <td><input type="text" class="fam-input" name="relation[]" placeholder="Son/Daughter…"></td>
    <td><input type="date" class="fam-input" name="member_birthdate[]" onchange="calcMemberAge(this)"></td>
    <td><input type="number" class="fam-input mono" name="member_age[]" readonly style="width:60px;text-align:center"></td>
    <td>
        <select class="fam-input" name="member_sex[]" style="width:72px">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </td>
    <td><input type="text" class="fam-input" name="education[]" placeholder="Attainment"></td>
    <td><input type="text" class="fam-input" name="member_occupation[]" placeholder="Occupation"></td>
<td style="position:relative; min-width:90px;">
    <div class="mem-photo-cell">
        <!-- Buttons shown when no photo -->
        <div class="mem-photo-btns" style="display:flex;flex-direction:column;gap:4px;">
            <label class="mem-upload-btn" style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.6rem;font-weight:600;color:var(--text-2);white-space:nowrap;">
                <i class="fa-solid fa-cloud-upload-alt" style="color:var(--green-mid)"></i> Upload
                <input type="file" name="member_photo[]" accept="image/*" style="display:none" onchange="handleFamilyMemberPhoto(this)">
            </label>
            <button type="button" class="mem-capture-btn" onclick="openMemberCameraModal(this.closest('tr'))" style="display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.6rem;font-weight:600;color:var(--text-2);cursor:pointer;white-space:nowrap;">
                <i class="fa-solid fa-camera" style="color:var(--green-mid)"></i> Capture
            </button>
        </div>
        <!-- Preview shown after photo is added -->
        <div class="mem-photo-preview-wrap" style="display:none;flex-direction:column;align-items:center;gap:4px;">
            <img class="mem-photo-preview-img" style="width:36px;height:36px;border-radius:6px;object-fit:cover;border:2px solid var(--green-mid);">
            <button type="button" onclick="removeMemberPhoto(this.closest('tr'))" style="display:inline-flex;align-items:center;gap:3px;padding:2px 6px;border-radius:5px;border:1.5px solid var(--orange-light);background:var(--orange-bg);color:var(--orange-deep);font-size:.58rem;cursor:pointer;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </div>
        <input type="hidden" name="member_photo_captured[]" value="">
    </div>
</td>
    <td><input type="text" class="fam-input" name="remarks[]" placeholder="Remarks" style="min-width:130px"></td>
    <td><button type="button" class="remove-row-btn" onclick="this.closest('tr').remove(); checkFamEmpty()"><i class="fa-solid fa-xmark"></i></button></td>
`;
    document.getElementById('familyBody').appendChild(tr);
    
    // Re-initialize Sortable after adding new row
    initSortableFamilyList();
}



function checkFamEmpty() {
    if(document.getElementById('familyBody').children.length === 0) {
        const tr = document.createElement('tr');
        tr.id = 'fam-empty';
        tr.innerHTML = '<td colspan="12" style="text-align:center;padding:20px;color:var(--text-3);font-size:.7rem"><i class="fa-solid fa-people-group" style="font-size:1.2rem;display:block;margin-bottom:6px;opacity:.3"></i>No family members added yet.</td>';
        document.getElementById('familyBody').appendChild(tr);
        
        // Destroy Sortable when empty
        if (familySortable) {
            familySortable.destroy();
            familySortable = null;
        }
    } else {
        // Reinitialize Sortable when rows exist
        initSortableFamilyList();
    }
}

function calcMemberAge(input) {
  const row = input.closest('tr');
  const af  = row.querySelector('input[name="member_age[]"]');
  if(!input.value) return;
  const today = new Date(), bd = new Date(input.value);
  let age = today.getFullYear() - bd.getFullYear();
  const m = today.getMonth() - bd.getMonth();
  if(m<0||(m===0&&today.getDate()<bd.getDate())) age--;
  af.value = age;
}

// ── Update the existing updateFileName function ──
function updateFileName(input) {
    const span = input.previousElementSibling;
    if(span && input.files[0]) {
        span.textContent = input.files[0].name.substring(0, 10) + '…';
    } else {
        span.textContent = 'Choose';
    }
}

// ── Household number ──
document.addEventListener('DOMContentLoaded', function() {
  fetch('/beneficiaries/get-next-household-no')
    .then(r => r.json())
    .then(d => {
      if(d.success) {
        document.getElementById('household_preview').textContent = d.household_no;
        document.getElementById('household_no').value = d.household_no;
      }
    })
    .catch(() => {
      document.getElementById('household_preview').textContent = '0001';
      document.getElementById('household_no').value = '0001';
    });
});

// ── Highlight active step pill on scroll ──
const sections = ['sec-i','sec-ii','sec-iii','sec-iv','sec-v','sec-vi','sec-vii','sec-viii','sec-ix','sec-x'];
const pills = document.querySelectorAll('.step-pill');
window.addEventListener('scroll', () => {
  let current = 0;
  sections.forEach((id, i) => {
    const el = document.getElementById(id);
    if(el && el.getBoundingClientRect().top < 180) current = i;
  });
  pills.forEach((p,i) => {
    p.classList.toggle('active', i===current);
    p.classList.toggle('done', i<current);
  });
}, { passive: true });

function handleHeadPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { showHeadPhotoPreview(e.target.result); };
        reader.readAsDataURL(input.files[0]);
    }
}

function showHeadPhotoPreview(src) {
    document.getElementById('prev-head').src = src;
    document.getElementById('head-photo-btns').style.display = 'none';
    document.getElementById('head-photo-preview-wrap').style.display = 'flex';
}

function removeHeadPhoto() {
    const fileInput = document.getElementById('head_photo_file');
    if (fileInput) fileInput.value = '';
    document.getElementById('head_photo_captured').value = '';
    document.getElementById('prev-head').src = '';
    document.getElementById('head-photo-preview-wrap').style.display = 'none';
    document.getElementById('head-photo-btns').style.display = 'flex';
}

function handleFamilyMemberPhoto(input) {
    const row = input.closest('tr');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            showMemberPhotoPreview(row, e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function showMemberPhotoPreview(row, src) {
    const cell = row.querySelector('.mem-photo-cell');
    cell.querySelector('.mem-photo-btns').style.display = 'none';
    const previewWrap = cell.querySelector('.mem-photo-preview-wrap');
    previewWrap.style.display = 'flex';
    previewWrap.querySelector('.mem-photo-preview-img').src = src;
}

function removeMemberPhoto(row) {
    const cell = row.querySelector('.mem-photo-cell');
    // Clear file input
    const fileInput = cell.querySelector('input[type="file"]');
    if (fileInput) fileInput.value = '';
    // Clear hidden captured input
    const hiddenInput = cell.querySelector('input[type="hidden"]');
    if (hiddenInput) hiddenInput.value = '';
    // Show buttons, hide preview
    cell.querySelector('.mem-photo-btns').style.display = 'flex';
    cell.querySelector('.mem-photo-preview-wrap').style.display = 'none';
    cell.querySelector('.mem-photo-preview-img').src = '';
}

// ── Auto-uppercase all text inputs on input event ──
function applyAutoUppercase(root) {
  root = root || document;
  root.querySelectorAll('input[type="text"], input:not([type]), .fam-input:not(select)').forEach(function(el) {
    if (el.dataset.ucBound) return;
    el.dataset.ucBound = '1';
    el.addEventListener('input', function() {
      var pos = this.selectionStart;
      this.value = this.value.toUpperCase();
      this.setSelectionRange(pos, pos);
    });
    // Also uppercase any pre-filled value
    if (el.value) el.value = el.value.toUpperCase();
  });
}


// Initialize SortableJS for drag and drop
let familySortable = null;

function initSortableFamilyList() {
    const familyBody = document.getElementById('familyBody');
    const emptyRow = document.getElementById('fam-empty');
    
    // Don't initialize if there's only the empty row
    if (emptyRow && familyBody.children.length === 1) {
        if (familySortable) {
            familySortable.destroy();
            familySortable = null;
        }
        return;
    }
    
    // Destroy existing Sortable instance if it exists
    if (familySortable) {
        familySortable.destroy();
    }
    
    // Create new Sortable instance
    familySortable = new Sortable(familyBody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function() {
            // Optional: Update any order-related hidden fields if needed
            console.log('Family members reordered');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() { applyAutoUppercase(); });

// Re-apply when new family member rows are added (patch addFamilyMember)
var _origAddFamilyMember = addFamilyMember;
addFamilyMember = function() {
  _origAddFamilyMember();
  var rows = document.getElementById('familyBody');
  if (rows) applyAutoUppercase(rows.lastElementChild);
  initSortableFamilyList();
};
// Camera modal variables
let cameraModalStream = null;
let capturedModalPhotoData = null;
let currentPhotoSource = 'upload';

// Set photo source (Upload or Camera)
function setPhotoSource(source) {
    currentPhotoSource = source;
    
    // Update button active states
    document.querySelectorAll('.photo-source-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-source') === source) {
            btn.classList.add('active');
        }
    });
    
    // Show/hide appropriate options
    const uploadOption = document.getElementById('upload-photo-option');
    const cameraOption = document.getElementById('camera-photo-option');
    
    if (source === 'upload') {
        if (uploadOption) uploadOption.style.display = 'block';
        if (cameraOption) cameraOption.style.display = 'none';
        stopCameraModal(); // Stop camera if running
    } else {
        if (uploadOption) uploadOption.style.display = 'none';
        if (cameraOption) cameraOption.style.display = 'block';
        startCameraModal(); // Start camera
    }
}

// Open camera modal
function openCameraModal() {
    const modal = document.getElementById('camera-modal');
    modal.style.display = 'flex';
    
    // Reset UI
    document.getElementById('camera-modal-preview').style.display = 'none';
    document.getElementById('capture-modal-btn').style.display = 'inline-flex';
    document.getElementById('retake-modal-btn').style.display = 'none';
    document.getElementById('confirm-modal-btn').style.display = 'none';
    
    // Reset captured data
    capturedModalPhotoData = null;
    
    // Start camera
    startCameraModal();
}

function closeCameraModal() {
    const modal = document.getElementById('camera-modal');
    modal.style.display = 'none';
    
    // Reset video display
    const video = document.getElementById('camera-modal-video');
    video.style.display = 'block';
    
    stopCameraModal();
}

// Start camera in modal
async function startCameraModal() {
    const video = document.getElementById('camera-modal-video');
    
    try {
        if (cameraModalStream) {
            stopCameraModal();
        }
        
        cameraModalStream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user' } 
        });
        video.srcObject = cameraModalStream;
    } catch (err) {
        console.error('Camera error:', err);
        alert('Unable to access camera. Please check permissions or use upload option.');
        closeCameraModal();
        setPhotoSource('upload');
    }
}

// Stop camera modal
function stopCameraModal() {
    if (cameraModalStream) {
        cameraModalStream.getTracks().forEach(track => track.stop());
        cameraModalStream = null;
    }
    const video = document.getElementById('camera-modal-video');
    if (video) {
        video.srcObject = null;
    }
}

function captureModalPhoto() {
    const video = document.getElementById('camera-modal-video');
    const canvas = document.getElementById('camera-modal-canvas');
    
    if (!video.videoWidth || !video.videoHeight) {
        alert('Camera not ready. Please wait.');
        return;
    }
    
    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert to data URL
    capturedModalPhotoData = canvas.toDataURL('image/jpeg', 0.9);
    
    // Hide the video element completely
    video.style.display = 'none';
    
    // Show preview
    const previewImg = document.getElementById('camera-modal-preview-img');
    previewImg.src = capturedModalPhotoData;
    document.getElementById('camera-modal-preview').style.display = 'block';
    
    // Update button visibility
    document.getElementById('capture-modal-btn').style.display = 'none';
    document.getElementById('retake-modal-btn').style.display = 'inline-flex';
    document.getElementById('confirm-modal-btn').style.display = 'inline-flex';
    
    // Stop camera temporarily
    stopCameraModal();
}

function retakeModalPhoto() {
    // Hide preview
    document.getElementById('camera-modal-preview').style.display = 'none';
    capturedModalPhotoData = null;
    
    // Show video again
    const video = document.getElementById('camera-modal-video');
    video.style.display = 'block';
    
    // Reset buttons
    document.getElementById('capture-modal-btn').style.display = 'inline-flex';
    document.getElementById('retake-modal-btn').style.display = 'none';
    document.getElementById('confirm-modal-btn').style.display = 'none';
    
    // Restart camera
    startCameraModal();
}

function confirmModalPhoto() {
    if (!capturedModalPhotoData) return;

    if (currentMemberCameraRow) {
        // ── Route to family member row ──
        const row = currentMemberCameraRow;
        currentMemberCameraRow = null;

        // Store base64 in hidden input
        const cell = row.querySelector('.mem-photo-cell');
        cell.querySelector('input[type="hidden"]').value = capturedModalPhotoData;

        showMemberPhotoPreview(row, capturedModalPhotoData);
        closeCameraModal();
} else {
        // ── Family head photo ──
        document.getElementById('head_photo_captured').value = capturedModalPhotoData;
        showHeadPhotoPreview(capturedModalPhotoData);
        closeCameraModal();
    }
}

// Helper: Convert data URL to Blob (keep this function)
function dataURLtoBlob(dataURL) {
    const arr = dataURL.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], { type: mime });
}

let currentMemberCameraRow = null;

function openMemberCameraModal(row) {
    currentMemberCameraRow = row;
    // Reuse the existing camera modal
    const modal = document.getElementById('camera-modal');
    modal.style.display = 'flex';
    document.getElementById('camera-modal-preview').style.display = 'none';
    document.getElementById('capture-modal-btn').style.display = 'inline-flex';
    document.getElementById('retake-modal-btn').style.display = 'none';
    document.getElementById('confirm-modal-btn').style.display = 'none';
    capturedModalPhotoData = null;
    startCameraModal();
}

// Clean up camera when leaving the page
window.addEventListener('beforeunload', function() {
    stopCameraModal();
});

// Also close modal on backdrop click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('camera-modal');
    if (e.target === modal) {
        closeCameraModal();
    }
});



// ══ SIGNATURE DRAWING PAD ══
let sigModalCtx    = null;
let sigInlineCtx   = null;
let sigEraserActive = false;

function setSigSource(source) {
  document.getElementById('sig-btn-upload').classList.toggle('active', source === 'upload');
  document.getElementById('sig-btn-draw').classList.toggle('active',   source === 'draw');
  document.getElementById('sig-upload-option').style.display = source === 'upload' ? 'block' : 'none';
  document.getElementById('sig-draw-option').style.display   = source === 'draw'   ? 'block' : 'none';

  if (source === 'draw') {
    // Auto-open the drawing modal immediately
    openSigModal();
  } else {
    document.getElementById('sig-data-input').value = '';
    document.getElementById('sig-saved-preview').style.display = 'none';
  }
}

function initInlineCanvas() {
  // No-op: inline canvas is hidden; drawing only happens in the modal
}

function clearSigDraw() {
  document.getElementById('sig-data-input').value = '';
  document.getElementById('sig-saved-preview').style.display = 'none';
  // Re-open the modal so user can re-draw
  openSigModal();
}

// ── Modal open/close ──
function openSigModal() {
  const modal = document.getElementById('sig-modal');
  modal.style.display = 'flex';
  modal.style.cursor = 'default';

  sigEraserActive = false;
  const eraserBtn = document.getElementById('sig-eraser-btn');
  if (eraserBtn) { eraserBtn.style.cssText += ';background:var(--surface);color:var(--text-2);border-color:var(--border);'; }
  const esw = document.getElementById('sig-eraser-size-wrap');
  if (esw) esw.style.display = 'none';

  const canvas = document.getElementById('sig-modal-canvas');
  requestAnimationFrame(() => {
    canvas.width  = canvas.offsetWidth  || 1060;
    canvas.height = canvas.offsetHeight || 420;
    sigModalCtx = canvas.getContext('2d');
    sigModalCtx.lineJoin = sigModalCtx.lineCap = 'round';
    // Fill white so eraser works correctly
    sigModalCtx.fillStyle = '#ffffff';
    sigModalCtx.fillRect(0, 0, canvas.width, canvas.height);
    applyModalPenSettings();
    attachDrawEvents(canvas, sigModalCtx, true);
  });

  document.getElementById('sig-pen-size').oninput = function() {
    document.getElementById('sig-pen-size-label').textContent = this.value;
    if (sigModalCtx && !sigEraserActive) sigModalCtx.lineWidth = parseInt(this.value);
  };
  const esr = document.getElementById('sig-eraser-size');
  if (esr) esr.oninput = function() {
    document.getElementById('sig-eraser-size-label').textContent = this.value;
  };
  document.getElementById('sig-pen-color').oninput = applyModalPenSettings;
}

function applyModalPenSettings() {
  if (!sigModalCtx) return;
  sigModalCtx.strokeStyle = document.getElementById('sig-pen-color').value;
  sigModalCtx.lineWidth   = parseInt(document.getElementById('sig-pen-size').value);
}

function clearModalCanvas() {
  if (!sigModalCtx) return;
  const c = document.getElementById('sig-modal-canvas');
  sigModalCtx.clearRect(0, 0, c.width, c.height);
  // Fill white so eraser has a solid background to erase back to
  sigModalCtx.fillStyle = '#ffffff';
  sigModalCtx.fillRect(0, 0, c.width, c.height);
}

function closeSigModal() {
  document.getElementById('sig-modal').style.display = 'none';
}

function toggleEraser() {
  sigEraserActive = !sigEraserActive;
  const btn      = document.getElementById('sig-eraser-btn');
  const sizeWrap = document.getElementById('sig-eraser-size-wrap');
  if (sigEraserActive) {
    btn.style.background  = 'var(--orange-bg)';
    btn.style.color       = 'var(--orange-deep)';
    btn.style.borderColor = 'var(--orange-mid)';
    sizeWrap.style.display = 'flex';
    document.getElementById('sig-modal-canvas').style.cursor = 'cell';
  } else {
    btn.style.background  = 'var(--surface)';
    btn.style.color       = 'var(--text-2)';
    btn.style.borderColor = 'var(--border)';
    sizeWrap.style.display = 'none';
    document.getElementById('sig-modal-canvas').style.cursor = 'crosshair';
  }
}

/* ── Opens the resize/preview modal after drawing ── */
function previewSigModal() {
  const canvas = document.getElementById('sig-modal-canvas');
  const ctx    = canvas.getContext('2d');
  const data   = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
  let hasStroke = false;
  for (let i = 0; i < data.length; i += 4) {
    if (data[i] < 250 || data[i+1] < 250 || data[i+2] < 250) { hasStroke = true; break; }
  }
  if (!hasStroke) { alert('Please draw your signature before saving.'); return; }

  // Export with TRANSPARENT background
  const tmp = document.createElement('canvas');
  tmp.width  = canvas.width;
  tmp.height = canvas.height;
  const tmpCtx = tmp.getContext('2d');
  tmpCtx.drawImage(canvas, 0, 0);
  const imgData = tmpCtx.getImageData(0, 0, tmp.width, tmp.height);
  const d = imgData.data;
  for (let i = 0; i < d.length; i += 4) {
    if (d[i] > 240 && d[i+1] > 240 && d[i+2] > 240) { d[i+3] = 0; }
  }
  tmpCtx.putImageData(imgData, 0, 0);

  const dataURL = tmp.toDataURL('image/png');
  openSigPreviewModal(dataURL);
}

/* ── Signature Preview / Resize Modal ── */
let _sigResizeData = '';
let _sigDrag = null;
let _sigResz = null;

function openSigPreviewModal(dataURL) {
  _sigResizeData = dataURL;

  const modal  = document.getElementById('sig-preview-modal');
  const img    = document.getElementById('sig-resize-img');
  const stage  = document.getElementById('sig-resize-stage');
  const handle = document.getElementById('sig-resize-handle');

  img.src = dataURL;
  modal.style.display = 'flex';

  img.onload = function() {
    const stageW = stage.clientWidth;
    const stageH = stage.clientHeight;
    const natW   = img.naturalWidth  || 320;
    const natH   = img.naturalHeight || 120;
    const scale  = Math.min((stageW * 0.7) / natW, (stageH * 0.7) / natH, 1);
    const initW  = Math.round(natW * scale);
    const initH  = Math.round(natH * scale);
    const initL  = Math.round((stageW - initW) / 2);
    const initT  = Math.round((stageH - initH) / 2);

    img.style.width  = initW + 'px';
    img.style.height = initH + 'px';
    img.style.left   = initL + 'px';
    img.style.top    = initT + 'px';

    updateResizeHandle();
    updateSizeLabelSig();
  };
  if (img.complete && img.naturalWidth) img.onload();

  // ── Move (drag) ──
  img.onmousedown = function(e) {
    e.preventDefault();
    const rect = img.getBoundingClientRect();
    _sigDrag = { ox: e.clientX - rect.left, oy: e.clientY - rect.top };
  };
  stage.onmousemove = function(e) {
    if (_sigDrag) {
      const sr = stage.getBoundingClientRect();
      let nx = e.clientX - sr.left - _sigDrag.ox;
      let ny = e.clientY - sr.top  - _sigDrag.oy;
      img.style.left = nx + 'px';
      img.style.top  = ny + 'px';
      updateResizeHandle();
    }
    if (_sigResz) {
      const sr = stage.getBoundingClientRect();
      const il = parseInt(img.style.left);
      const it = parseInt(img.style.top);
      let nw = e.clientX - sr.left - il;
      let nh = e.clientY - sr.top  - it;
      nw = Math.max(60, nw); nh = Math.max(20, nh);
      img.style.width  = nw + 'px';
      img.style.height = nh + 'px';
      updateResizeHandle();
      updateSizeLabelSig();
    }
  };
  stage.onmouseup    = () => { _sigDrag = null; _sigResz = null; };
  stage.onmouseleave = () => { _sigDrag = null; _sigResz = null; };

  // ── Resize handle ──
  handle.onmousedown = function(e) {
    e.preventDefault(); e.stopPropagation();
    _sigResz = true; _sigDrag = null;
  };

  // ── Touch support ──
  img.ontouchstart = function(e) {
    e.preventDefault();
    const t = e.touches[0];
    const rect = img.getBoundingClientRect();
    _sigDrag = { ox: t.clientX - rect.left, oy: t.clientY - rect.top };
  };
  stage.ontouchmove = function(e) {
    e.preventDefault();
    const t  = e.touches[0];
    const sr = stage.getBoundingClientRect();
    if (_sigDrag) {
      let nx = t.clientX - sr.left - _sigDrag.ox;
      let ny = t.clientY - sr.top  - _sigDrag.oy;
      img.style.left = nx + 'px'; img.style.top = ny + 'px';
      updateResizeHandle();
    }
    if (_sigResz) {
      const il = parseInt(img.style.left), it = parseInt(img.style.top);
      let nw = t.clientX - sr.left - il;
      let nh = t.clientY - sr.top  - it;
      nw = Math.max(60, nw); nh = Math.max(20, nh);
      img.style.width  = nw + 'px'; img.style.height = nh + 'px';
      updateResizeHandle(); updateSizeLabelSig();
    }
  };
  stage.ontouchend = () => { _sigDrag = null; _sigResz = null; };
  handle.ontouchstart = function(e) {
    e.preventDefault(); e.stopPropagation();
    _sigResz = true; _sigDrag = null;
  };
}

function updateResizeHandle() {
  const img    = document.getElementById('sig-resize-img');
  const handle = document.getElementById('sig-resize-handle');
  const l = parseInt(img.style.left) || 0;
  const t = parseInt(img.style.top)  || 0;
  handle.style.left = (l + img.offsetWidth  - 7) + 'px';
  handle.style.top  = (t + img.offsetHeight - 7) + 'px';
}

function updateSizeLabelSig() {
  const img = document.getElementById('sig-resize-img');
  document.getElementById('sig-resize-w-label').textContent = img.offsetWidth;
  document.getElementById('sig-resize-h-label').textContent = img.offsetHeight;
}

function resetSigResize() {
  const img   = document.getElementById('sig-resize-img');
  const stage = document.getElementById('sig-resize-stage');
  const natW  = img.naturalWidth  || 320;
  const natH  = img.naturalHeight || 120;
  const scale = Math.min((stage.clientWidth * 0.7) / natW, (stage.clientHeight * 0.7) / natH, 1);
  const initW = Math.round(natW * scale);
  const initH = Math.round(natH * scale);
  img.style.width  = initW + 'px';
  img.style.height = initH + 'px';
  img.style.left   = Math.round((stage.clientWidth  - initW) / 2) + 'px';
  img.style.top    = Math.round((stage.clientHeight - initH) / 2) + 'px';
  updateResizeHandle(); updateSizeLabelSig();
}

function closeSigPreviewModal() {
  document.getElementById('sig-preview-modal').style.display = 'none';
  // Return to draw modal
  document.getElementById('sig-modal').style.display = 'flex';
}

function saveSigFromPreview() {
  const stage  = document.getElementById('sig-resize-stage');
  const img    = document.getElementById('sig-resize-img');

  const stageW = stage.offsetWidth;
  const stageH = stage.offsetHeight;
  const imgL   = parseInt(img.style.left) || 0;
  const imgT   = parseInt(img.style.top)  || 0;
  const imgW   = img.offsetWidth;
  const imgH   = img.offsetHeight;

  const out    = document.createElement('canvas');
  out.width    = stageW;
  out.height   = stageH;
  const outCtx = out.getContext('2d');
  
  // Fill with white background (NOT transparent)
  outCtx.fillStyle = '#ffffff';
  outCtx.fillRect(0, 0, stageW, stageH);
  
  // Draw signature on top
  outCtx.drawImage(img, imgL, imgT, imgW, imgH);

  const finalDataURL = out.toDataURL('image/png');
  document.getElementById('sig-data-input').value = finalDataURL;

  const previewImg = document.getElementById('sig-preview-img');
  previewImg.src   = finalDataURL;
  document.getElementById('sig-saved-preview').style.display = 'block';

  document.getElementById('sig-preview-modal').style.display = 'none';
  document.getElementById('sig-modal').style.display = 'none';
}

// ── Shared drawing events ──
function attachDrawEvents(canvas, ctx, isModal) {
  let drawing = false;
  const fresh = canvas.cloneNode(true);
  canvas.parentNode.replaceChild(fresh, canvas);

  if (isModal) {
    sigModalCtx = fresh.getContext('2d');
    sigModalCtx.fillStyle = '#ffffff';
    sigModalCtx.fillRect(0, 0, fresh.width, fresh.height);
    applyModalPenSettings();
    fresh.style.cursor = 'crosshair';
  } else {
    sigInlineCtx = fresh.getContext('2d');
    sigInlineCtx.lineJoin = sigInlineCtx.lineCap = 'round';
    sigInlineCtx.strokeStyle = '#1e293b';
    sigInlineCtx.lineWidth = 2;
    fresh.style.cursor = 'crosshair';
  }
  ctx = isModal ? sigModalCtx : sigInlineCtx;

  function pos(e) {
    const r  = fresh.getBoundingClientRect();
    const sx = fresh.width / r.width, sy = fresh.height / r.height;
    const s  = e.touches ? e.touches[0] : e;
    return { x: (s.clientX - r.left) * sx, y: (s.clientY - r.top) * sy };
  }

  fresh.addEventListener('mouseenter', () => {
    fresh.style.cursor = (isModal && sigEraserActive) ? 'cell' : 'crosshair';
  });

  fresh.addEventListener('mousedown', (e) => {
    e.preventDefault();
    drawing = true;
    ctx.beginPath();
    if (isModal && sigEraserActive) {
      ctx.globalCompositeOperation = 'destination-out';
      ctx.lineWidth = parseInt(document.getElementById('sig-eraser-size').value) || 20;
      ctx.strokeStyle = 'rgba(0,0,0,1)';
      ctx.lineJoin = ctx.lineCap = 'round';
    } else {
      ctx.globalCompositeOperation = 'source-over';
      applyModalPenSettings();
    }
    const p = pos(e); ctx.moveTo(p.x, p.y);
  });

  fresh.addEventListener('mousemove', (e) => {
    if (!drawing) return;
    const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke();
  });

  fresh.addEventListener('mouseup', () => {
    if (!drawing) return;
    drawing = false;
    ctx.globalCompositeOperation = 'source-over';
    ctx.beginPath();
    saveInlineIfNeeded(isModal);
  });

  fresh.addEventListener('mouseleave', () => {
    if (!drawing) return;
    drawing = false;
    ctx.globalCompositeOperation = 'source-over';
    ctx.beginPath();
  });

  fresh.addEventListener('touchstart', (e) => {
    e.preventDefault();
    drawing = true;
    ctx.beginPath();
    if (isModal && sigEraserActive) {
      ctx.globalCompositeOperation = 'destination-out';
      ctx.lineWidth = parseInt(document.getElementById('sig-eraser-size').value) || 20;
      ctx.strokeStyle = 'rgba(0,0,0,1)';
      ctx.lineJoin = ctx.lineCap = 'round';
    } else {
      ctx.globalCompositeOperation = 'source-over';
      applyModalPenSettings();
    }
    const p = pos(e); ctx.moveTo(p.x, p.y);
  }, { passive: false });

  fresh.addEventListener('touchmove', (e) => {
    e.preventDefault();
    if (!drawing) return;
    const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke();
  }, { passive: false });

  fresh.addEventListener('touchend', () => {
    drawing = false;
    ctx.globalCompositeOperation = 'source-over';
    ctx.beginPath();
    saveInlineIfNeeded(isModal);
  });
}

function saveInlineIfNeeded(isModal) {
  if (isModal) return;
  const c = document.getElementById('sig-canvas');
  if (!isCanvasBlank(c)) document.getElementById('sig-data-input').value = c.toDataURL('image/png');
}

function isCanvasBlank(canvas) {
  return !canvas.getContext('2d').getImageData(0, 0, canvas.width, canvas.height).data.some(v => v !== 0);
}

// Close modal on backdrop click
document.addEventListener('click', function(e) {
  const modal = document.getElementById('sig-modal');
  if (e.target === modal) closeSigModal();
});

// ── REAL-TIME DUPLICATE NAME CHECKING ──
let duplicateCheckTimeout = null;
let isNameDuplicate = false;

function setupDuplicateNameCheck() {
    const firstNameInput = document.querySelector('input[name="first_name"]');
    const lastNameInput = document.querySelector('input[name="last_name"]');
    
    if (!firstNameInput || !lastNameInput) return;
    
    function checkDuplicateName() {
        const firstName = firstNameInput.value.trim();
        const lastName = lastNameInput.value.trim();
        
        // Clear any existing error message
        clearDuplicateError();
        
        // Don't check if names are empty
        if (!firstName || !lastName) {
            isNameDuplicate = false;
            return;
        }
        
        // Get exclude_id if in edit mode (from URL parameter or hidden input)
        const urlParams = new URLSearchParams(window.location.search);
        const residentId = urlParams.get('id') || document.querySelector('input[name="resident_id"]')?.value;
        
        // Show loading indicator
        showDuplicateChecking(true);
        
        // Make AJAX request
        fetch(`/beneficiaries/check-duplicate-name?first_name=${encodeURIComponent(firstName)}&last_name=${encodeURIComponent(lastName)}&exclude_id=${residentId || ''}`)
            .then(response => response.json())
            .then(data => {
                showDuplicateChecking(false);
                
                if (data.is_duplicate) {
                    isNameDuplicate = true;
                    showDuplicateError(data.message);
                } else {
                    isNameDuplicate = false;
                }
            })
            .catch(error => {
                console.error('Duplicate check failed:', error);
                showDuplicateChecking(false);
            });
    }
    
    // Debounced check (wait 500ms after user stops typing)
    function debouncedCheck() {
        clearTimeout(duplicateCheckTimeout);
        duplicateCheckTimeout = setTimeout(checkDuplicateName, 500);
    }
    
    // Add event listeners
    firstNameInput.addEventListener('input', debouncedCheck);
    lastNameInput.addEventListener('input', debouncedCheck);
    
    // Also check on blur (when user leaves the field)
    firstNameInput.addEventListener('blur', checkDuplicateName);
    lastNameInput.addEventListener('blur', checkDuplicateName);
}

function showDuplicateError(message) {
    // Remove existing error message
    clearDuplicateError();
    
    // Create error element
    const errorDiv = document.createElement('div');
    errorDiv.id = 'duplicate-name-error';
    errorDiv.style.cssText = `
        background: #fee2e2;
        border-left: 4px solid #c0392b;
        padding: 10px 12px;
        border-radius: 6px;
        margin-top: 8px;
        margin-bottom: 12px;
        font-size: 0.75rem;
        color: #c0392b;
        display: flex;
        align-items: center;
        gap: 8px;
    `;
    errorDiv.innerHTML = `
        <i class="fa-solid fa-circle-exclamation" style="font-size: 1rem;"></i>
        <span>${message}</span>
    `;
    
    // Find the parent container (the row containing first_name and last_name)
    const nameRow = document.querySelector('input[name="first_name"]')?.closest('.form-row');
    if (nameRow) {
        nameRow.insertAdjacentElement('afterend', errorDiv);
    }
    
    // Highlight the input fields
    document.querySelector('input[name="first_name"]')?.classList.add('duplicate-error');
    document.querySelector('input[name="last_name"]')?.classList.add('duplicate-error');
}

function clearDuplicateError() {
    const existingError = document.getElementById('duplicate-name-error');
    if (existingError) existingError.remove();
    
    // Remove highlight classes
    document.querySelector('input[name="first_name"]')?.classList.remove('duplicate-error');
    document.querySelector('input[name="last_name"]')?.classList.remove('duplicate-error');
}

function showDuplicateChecking(show) {
    let indicator = document.getElementById('duplicate-check-indicator');
    
    if (show) {
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'duplicate-check-indicator';
            indicator.style.cssText = `
                font-size: 0.65rem;
                color: var(--text-3);
                margin-top: 4px;
                display: flex;
                align-items: center;
                gap: 5px;
            `;
            indicator.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Checking if resident exists...';
            
            const nameRow = document.querySelector('input[name="first_name"]')?.closest('.form-row');
            if (nameRow) {
                nameRow.insertAdjacentElement('afterend', indicator);
            }
        }
        indicator.style.display = 'flex';
    } else {
        if (indicator) indicator.style.display = 'none';
    }
}

// Add CSS for duplicate error highlighting
function addDuplicateErrorStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .duplicate-error {
            border-color: #c0392b !important;
            background-color: #fee2e2 !important;
            box-shadow: 0 0 0 2px rgba(192, 57, 43, 0.1) !important;
        }
    `;
    document.head.appendChild(style);
}

// Initialize duplicate checking on page load
document.addEventListener('DOMContentLoaded', function() {
    addDuplicateErrorStyles();
    setupDuplicateNameCheck();
});

// Add this to the existing DOMContentLoaded event listener or create a new one
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...
    
    // Prevent form submission if duplicate name exists
    const form = document.getElementById('beneficiaryForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (isNameDuplicate) {
                e.preventDefault();
                alert('Cannot save: A resident with the same first and last name already exists. Please check the duplicate warning below.');
                return false;
            }
        });
    }
});

// ============================================
// UNSAVED CHANGES WARNING - FIXED VERSION
// ============================================

let formHasChanges = false;
let isSubmitting = false;

// Function to mark form as changed
function markFormAsChanged() {
    if (!isSubmitting) {
        formHasChanges = true;
        console.log('Form has changes'); // Debug
    }
}

// Track all form inputs
function initUnsavedChangesTracking() {
    const form = document.getElementById('beneficiaryForm');
    if (!form) {
        console.log('Form not found');
        return;
    }
    
    console.log('Initializing unsaved changes tracking');
    
    // Track all input, select, textarea
    const inputs = form.querySelectorAll('input, select, textarea');
    console.log('Found inputs:', inputs.length);
    
    inputs.forEach(input => {
        // Skip hidden inputs
        if (input.type === 'hidden') return;
        
        input.addEventListener('change', markFormAsChanged);
        input.addEventListener('input', markFormAsChanged);
    });
    
    // Track file inputs
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', markFormAsChanged);
    });
}

// Setup form submit handler
function initSubmitHandler() {
    const form = document.getElementById('beneficiaryForm');
    if (!form) return;
    
    form.addEventListener('submit', function() {
        console.log('Form submitting - resetting change flag');
        isSubmitting = true;
        formHasChanges = false;
    });
}

// Override addFamilyMember to track changes
function overrideFamilyMemberFunctions() {
    if (typeof addFamilyMember === 'function') {
        const originalAdd = addFamilyMember;
        window.addFamilyMember = function() {
            markFormAsChanged();
            return originalAdd.apply(this, arguments);
        };
    }
    
    // Also override checkFamEmpty if it exists
    if (typeof checkFamEmpty === 'function') {
        const originalCheck = checkFamEmpty;
        window.checkFamEmpty = function() {
            markFormAsChanged();
            return originalCheck.apply(this, arguments);
        };
    }
}

// Show custom modal for unsaved changes
function showUnsavedChangesModal(callback) {
    // Remove existing modal if any
    const existingModal = document.getElementById('unsaved-changes-modal');
    if (existingModal) existingModal.remove();
    
    // Create modal
    const modal = document.createElement('div');
    modal.id = 'unsaved-changes-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.75);
        backdrop-filter: blur(4px);
        z-index: 100000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Outfit', sans-serif;
    `;
    
    modal.innerHTML = `
        <div style="background:#fff;border-radius:20px;padding:28px;width:min(420px,94vw);box-shadow:0 20px 60px rgba(0,0,0,.3);text-align:center;">
            <div style="width:56px;height:56px;background:#fff7ed;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size:1.8rem;color:#F58220;"></i>
            </div>
            <div style="font-size:1.2rem;font-weight:800;color:#1e293b;margin-bottom:8px;">
                Unsaved Changes
            </div>
            <div style="font-size:.75rem;color:#64748b;margin-bottom:24px;">
                You have unsaved changes. Are you sure you want to leave?<br>All unsaved data will be lost.
            </div>
            <div style="display:flex;gap:12px;justify-content:center;">
                <button type="button" id="modal-stay-btn" style="padding:8px 20px;border-radius:8px;border:none;background:linear-gradient(135deg, #4a7a26, #77BC3F);color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;">
                    <i class="fa-solid fa-arrow-left"></i> Stay on Page
                </button>
                <button type="button" id="modal-leave-btn" style="padding:8px 20px;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;color:#334155;font-size:.75rem;font-weight:600;cursor:pointer;">
                    <i class="fa-solid fa-sign-out-alt"></i> Leave Anyway
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Handle buttons
    const stayBtn = document.getElementById('modal-stay-btn');
    const leaveBtn = document.getElementById('modal-leave-btn');
    
    stayBtn.onclick = function() {
        modal.remove();
    };
    
    leaveBtn.onclick = function() {
        modal.remove();
        formHasChanges = false;
        if (callback) callback();
    };
    
    // Close on backdrop click
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    };
}

// Setup leave warning with custom modal
function setupLeaveWarning() {
    // Before page refresh/close - browser's native warning (can't customize)
    window.addEventListener('beforeunload', function(e) {
        if (formHasChanges && !isSubmitting) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });
    
    // Intercept all link clicks with custom modal
    document.body.addEventListener('click', function(e) {
        let target = e.target;
        while (target && target !== document.body) {
            if (target.tagName === 'A' && target.href) {
                const href = target.getAttribute('href');
                
                if (href && !href.startsWith('javascript:') && !href.startsWith('#') && href !== '') {
                    if (formHasChanges && !isSubmitting) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        showUnsavedChangesModal(function() {
                            window.location.href = target.href;
                        });
                        return false;
                    }
                }
                break;
            }
            target = target.parentElement;
        }
    }, true);
    
    // Handle browser back/forward
    window.addEventListener('popstate', function(e) {
        if (formHasChanges && !isSubmitting) {
            e.preventDefault();
            showUnsavedChangesModal(function() {
                formHasChanges = false;
                history.back();
            });
        }
    });
}

// Watch for changes in the family members table
function watchFamilyTableChanges() {
    const familyBody = document.getElementById('familyBody');
    if (!familyBody) return;
    
    const observer = new MutationObserver(function(mutations) {
        markFormAsChanged();
    });
    
    observer.observe(familyBody, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['value']
    });
}

// Initialize everything when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initUnsavedChangesTracking();
        initSubmitHandler();
        setupLeaveWarning();
        watchFamilyTableChanges();
        overrideFamilyMemberFunctions();
        
        // Also add change tracking to dynamically added elements
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        const inputs = node.querySelectorAll ? node.querySelectorAll('input, select, textarea') : [];
                        inputs.forEach(input => {
                            if (input.type !== 'hidden') {
                                input.addEventListener('change', markFormAsChanged);
                                input.addEventListener('input', markFormAsChanged);
                            }
                        });
                    }
                });
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    });
} else {
    // DOM already loaded
    initUnsavedChangesTracking();
    initSubmitHandler();
    setupLeaveWarning();
    watchFamilyTableChanges();
    overrideFamilyMemberFunctions();
}

console.log('Unsaved changes warning script loaded');
</script>

<?= $this->endSection() ?>