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
  background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid));
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 1rem;
  box-shadow: 0 4px 12px rgba(201,107,16,.3);
}
.form-header-text h5 { font-size: .95rem; font-weight: 800; color: var(--text-1); margin: 0; letter-spacing: -.2px; }
.form-header-text p  { font-size: .67rem; color: var(--text-3); margin: 2px 0 0; }

/* ── HH display badge ── */
.hh-display {
  display: flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  border-radius: 8px; padding: 8px 12px; border: none;
}
.hh-display .hh-label { font-size: .6rem; color: rgba(255,255,255,.75); font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.hh-display .hh-val   { font-size: 1rem; font-weight: 800; color: #fff; font-family: 'DM Mono', monospace; }

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
.file-input-wrap .fi-name { font-size: .68rem; color: var(--green-deep); font-weight: 600; }
.file-preview { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; display: none; }

/* ── Existing file badge ── */
.existing-file {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: .62rem; color: var(--green-deep); font-weight: 600;
  background: var(--green-bg); border: 1px solid var(--green-glow);
  border-radius: 5px; padding: 2px 8px; margin-bottom: 5px;
  text-decoration: none;
}
.existing-file:hover { background: var(--green-mid); color: #fff; }

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
  background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid));
  color: #fff; font-size: .78rem; font-weight: 700;
  cursor: pointer; font-family: 'Outfit', sans-serif; transition: all .2s;
  box-shadow: 0 4px 14px rgba(201,107,16,.3);
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(201,107,16,.4); }
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

/* ── Damage meter ── */
.damage-meter { height: 4px; border-radius: 2px; background: var(--border); margin-top: 4px; overflow: hidden; }

/* ── Auto-capitalize all text inputs ── */
.form-input[type="text"], .form-input:not([type]), .fam-input[type="text"], .fam-input:not([type]) { text-transform: uppercase; }

/* ══════════════════════════════════════════
   MOBILE RESPONSIVE IMPROVEMENTS
   ══════════════════════════════════════════ */

/* ── Base mobile padding ── */
@media (max-width: 768px) {
  .page-wrap { padding: 0 2px; }

  /* ── Breadcrumb ── */
  .breadcrumb-bar { font-size: .65rem; margin-bottom: 10px; }

  /* ── Form header ── */
  .form-header {
    padding: 12px 14px;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    border-radius: var(--radius) var(--radius) 0 0;
  }
  .form-header-left { gap: 10px; }
  .form-header-icon { width: 36px; height: 36px; font-size: .85rem; }
  .form-header-text h5 { font-size: .85rem; }
  .form-header-text p  { font-size: .62rem; }

  /* HH badge full width on mobile */
  .hh-display { width: 100%; justify-content: flex-start; }

  /* ── Step bar ── */
  .step-bar {
    padding: 10px 12px;
    gap: 5px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
  }
  .step-bar::-webkit-scrollbar { display: none; }
  .step-pill { padding: 5px 10px; font-size: .58rem; }
  .step-pill .sp-num { width: 15px; height: 15px; font-size: .5rem; }

  /* ── Form body ── */
  .form-body { padding: 12px; }

  /* ── Section cards ── */
  .section-card { margin-bottom: 10px; }
  .section-head { padding: 9px 12px; }
  .section-body { padding: 12px; }
  .section-title { font-size: .72rem; }

  /* ── Form labels & inputs ── */
  .form-label { font-size: .6rem; }
  .form-input, .form-select-custom { font-size: .78rem; padding: 9px 10px; }

  /* ── Radio & checkbox groups ── */
  .radio-group, .check-group { gap: 8px; }
  .radio-opt, .check-opt { font-size: .74rem; }

  /* ── Vulnerable counters: 2-col on mobile ── */
  .form-row.cols-4:has(.vuln-counter) { grid-template-columns: 1fr 1fr; }
  .vuln-counter { padding: 10px; }
  .vuln-counter .vc-label { font-size: .58rem; }

  /* ── Shelter & ownership chips ── */
  .shelter-group, .ownership-group { gap: 6px; }
  .shelter-chip, .own-chip { font-size: .68rem; padding: 7px 10px; border-radius: 16px; }

  /* ── Submit bar ── */
  .submit-bar {
    padding: 12px 14px;
    flex-wrap: wrap;
    gap: 8px;
  }
  .btn-save, .btn-cancel { flex: 1; justify-content: center; font-size: .75rem; padding: 10px 14px; }
  .submit-bar > span { width: 100%; text-align: center; margin-left: 0 !important; }

  /* ── File preview card ── */
  .file-preview-card { padding: 8px 10px; gap: 8px; flex-wrap: wrap; }
  .file-preview-card img { width: 44px; height: 44px; }
  .fp-remove-btn { flex: 1; justify-content: center; }

  /* ── Photo source buttons ── */
  .photo-source-btn { font-size: .68rem; padding: 7px 14px; }

  /* ── Add member button ── */
  .add-member-btn { width: 100%; justify-content: center; }

  /* ── Sig modal ── */
  #sig-modal > div { padding: 16px; }
  #sig-modal canvas { width: 100% !important; height: 220px !important; }
}

/* ── Extra small screens (phones < 400px) ── */
@media (max-width: 400px) {
  .form-header-icon { display: none; }
  .step-pill { padding: 4px 8px; font-size: .55rem; }
  .form-body { padding: 10px; }
  .section-body { padding: 10px; }
  .btn-save, .btn-cancel { font-size: .72rem; padding: 9px 10px; }
}

/* ── Family members: card layout on mobile (replaces horizontal table) ── */
@media (max-width: 768px) {
  .fam-table-wrap { overflow-x: unset; }
  .fam-table { min-width: unset; display: block; }
  .fam-table thead { display: none; }
  .fam-table tbody { display: flex; flex-direction: column; gap: 10px; }
  .fam-table tbody tr {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px;
    position: relative;
  }
  .fam-table tbody tr:hover td { background: transparent; }

  /* Each cell becomes a labeled mini-group */
  .fam-table td {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding: 0;
    border: none;
  }
  .fam-table td::before {
    content: attr(data-label);
    font-size: .55rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--text-3);
  }

  /* Full-width cells */
  .fam-table td:nth-child(2),  /* Name */
  .fam-table td:nth-child(10), /* Remarks */
  .fam-table td:nth-child(9)   /* Photo */ {
    grid-column: span 2;
  }

  /* Drag handle + remove in top-right corner */
  .fam-table td:nth-child(1) {
    position: absolute;
    top: 10px;
    left: 10px;
    width: auto;
    flex-direction: row;
  }
  .fam-table td:nth-child(11) {
    position: absolute;
    top: 10px;
    right: 10px;
    width: auto;
    flex-direction: row;
    align-items: center;
  }
  .fam-table td:nth-child(11)::before { display: none; }
  .fam-table td:nth-child(1)::before  { display: none; }

  /* Push name cell down to clear the drag handle */
  .fam-table td:nth-child(2) { padding-top: 28px; }

  /* Inputs inside card */
  .fam-input { font-size: .74rem; padding: 7px 8px; }

  /* Empty state row */
  #fam-empty { display: block !important; }
  #fam-empty td { grid-column: unset !important; }

  /* Photo cell */
  .mem-photo-btns { flex-direction: row !important; flex-wrap: wrap; gap: 4px; }
}

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

.member-photo-preview {
    margin-bottom: 5px;
    text-align: center;
}

.member-photo-preview img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    object-fit: cover;
    border: 2px solid var(--green-mid);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

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

.sortable-drag {
    opacity: 0.5;
    background: var(--surface2);
}

.sortable-ghost {
    opacity: 0.3;
    background: var(--green-bg);
    border: 2px dashed var(--green-mid);
}

#familyBody tr {
    transition: background .1s ease;
}

#familyBody tr:hover {
    background: var(--surface2);
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

/* Signature modal cursor styles */
#sig-modal-canvas {
    cursor: crosshair !important;
}

#sig-modal-canvas:hover {
    cursor: crosshair !important;
}

/* Ensure default cursor for the rest of the modal */
#sig-modal *:not(canvas) {
    cursor: default;
}

.member-photo-source-btn {
    font-size: 0.55rem;
    padding: 2px 6px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    cursor: pointer;
    transition: all 0.2s;
}

.member-photo-source-btn.active {
    border-color: var(--green-mid);
    background: var(--green-bg);
    color: var(--green-deep);
}

.member-photo-source-btn:hover {
    border-color: var(--green-mid);
    background: var(--green-bg);
}

/* Ensure family member camera modal video hides properly */
#member-camera-video {
    display: block;
    background: #1a1a1a;
}

#member-camera-preview {
    background: var(--surface);
    border-radius: 16px;
    overflow: hidden;
}

#member-camera-preview-img {
    display: block;
    width: 100%;
    background: #f0f0f0;
}

/* Hide canvas elements */
#member-camera-canvas,
#camera-modal-canvas {
    display: none !important;
}
</style>

<div class="page-wrap">

  <!-- ── Breadcrumb ── -->
  <div class="breadcrumb-bar">
    <a href="/beneficiaries/resident-list"><i class="fa-solid fa-users me-1"></i>Resident List</a>
    <i class="fa-solid fa-chevron-right"></i>
    <span style="color:var(--text-1);font-weight:600">Edit Beneficiary</span>
  </div>

  <!-- ── Page Header ── -->
  <div class="form-header">
    <div class="form-header-left">
      <div class="form-header-icon"><i class="fa-solid fa-user-pen"></i></div>
      <div class="form-header-text">
        <h5>Edit Resident Information</h5>
        <p>Update the fields below. Fields marked <span style="color:var(--orange-deep)">*</span> are required.</p>
      </div>
    </div>
    <div class="hh-display">
      <div>
        <div class="hh-label">Household No.</div>
        <div class="hh-val mono"><?= $resident['household_no'] ?? '—' ?></div>
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
  <form action="/beneficiaries/update/<?= $resident['id'] ?>" method="post" id="beneficiaryForm" enctype="multipart/form-data">
    <?= csrf_field() ?>

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
              <input type="text" name="region" class="form-input" placeholder="e.g. Region XI" value="<?= old('region', $resident['region'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Province</label>
              <input type="text" name="province" class="form-input" placeholder="Province name" value="<?= old('province', $resident['province'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">District</label>
              <input type="text" name="district" class="form-input" placeholder="District" value="<?= old('district', $resident['district'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">City / Municipality</label>
              <input type="text" name="city_municipality" class="form-input" placeholder="City or Municipality" value="<?= old('city_municipality', $resident['city_municipality'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Barangay <span class="req">*</span></label>
              <input type="text" name="barangay" class="form-input" placeholder="Barangay" required value="<?= old('barangay', $resident['barangay'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Evacuation Center / Site</label>
              <input type="text" name="evacuation_center" class="form-input" placeholder="Evacuation center name" value="<?= old('evacuation_center', $resident['evacuation_center'] ?? '') ?>">
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
        <div id="head-photo-btns" style="display:<?= !empty($resident['photo']) ? 'none' : 'flex' ?>; gap:10px; margin-bottom:12px;">
            <label class="photo-source-btn" style="cursor:pointer;">
                <i class="fa-solid fa-cloud-upload-alt"></i> Upload Photo
                <input type="file" name="head_photo" id="head_photo_file" accept="image/*" style="display:none" onchange="handleHeadPhoto(this)">
            </label>
            <button type="button" class="photo-source-btn" onclick="openCameraModal()">
                <i class="fa-solid fa-camera"></i> Capture Photo
            </button>
        </div>

        <!-- Preview + Remove shown when photo exists (existing or newly selected) -->
        <div id="head-photo-preview-wrap" style="display:<?= !empty($resident['photo']) ? 'flex' : 'none' ?>; align-items:center; gap:12px; margin-bottom:12px;">
<img id="prev-head" src="<?= !empty($resident['photo']) ? '/' . $resident['photo'] : '' ?>" onclick="openLightbox(this.src)" style="width:70px;height:70px;border-radius:10px;object-fit:cover;border:2px solid var(--green-mid);box-shadow:0 2px 8px rgba(0,0,0,0.1);cursor:zoom-in;">
            <button type="button" onclick="removeHeadPhoto()" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;border:1.5px solid var(--orange-light);background:var(--orange-bg);color:var(--orange-deep);font-size:.7rem;font-weight:600;cursor:pointer;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </div>


</div>

        <div class="form-hint">Upload or capture a clear photo of the family head (JPG, PNG)</div>
        <input type="hidden" name="head_photo_captured" id="head_photo_captured" value="">
        <input type="hidden" name="head_photo_remove" id="head_photo_remove" value="0">
    </div>
</div>

          <div class="sub-section-label">Name</div>
          <div class="form-row cols-auto">
            <div class="form-group">
              <label class="form-label">Last Name <span class="req">*</span></label>
              <input type="text" name="last_name" class="form-input" required value="<?= old('last_name', $resident['last_name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">First Name <span class="req">*</span></label>
              <input type="text" name="first_name" class="form-input" required value="<?= old('first_name', $resident['first_name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Middle Name</label>
              <input type="text" name="middle_name" class="form-input" value="<?= old('middle_name', $resident['middle_name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Extension</label>
              <input type="text" name="name_extension" class="form-input" placeholder="Jr./Sr." value="<?= old('name_extension', $resident['name_extension'] ?? '') ?>">
            </div>
          </div>

          <div class="sub-section-label">Personal Details</div>
          <div class="form-row cols-4">
            <div class="form-group">
              <label class="form-label">Birthdate</label>
              <input type="date" name="birthdate" class="form-input" id="birthdate" onchange="calculateAge()" value="<?= old('birthdate', $resident['birthdate'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Age</label>
              <input type="number" name="age" class="form-input readonly-field" id="age" readonly value="<?= old('age', $resident['age'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Birthplace</label>
              <input type="text" name="birthplace" class="form-input" value="<?= old('birthplace', $resident['birthplace'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Religion</label>
              <input type="text" name="religion" class="form-input" value="<?= old('religion', $resident['religion'] ?? '') ?>">
            </div>
          </div>

          <div class="form-row cols-3">
            <div class="form-group">
              <label class="form-label">Sex <span class="req">*</span></label>
              <div class="radio-group">
                <label class="radio-opt">
                  <input type="radio" name="sex" value="Male" <?= (old('sex', $resident['sex'] ?? '') == 'Male') ? 'checked' : '' ?>> Male
                </label>
                <label class="radio-opt">
                  <input type="radio" name="sex" value="Female" <?= (old('sex', $resident['sex'] ?? '') == 'Female') ? 'checked' : '' ?>> Female
                </label>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Civil Status</label>
              <select name="civil_status" class="form-select-custom">
                <option value="">Select status</option>
                <option value="Single"    <?= (old('civil_status', $resident['civil_status'] ?? '') == 'Single')    ? 'selected' : '' ?>>Single</option>
                <option value="Married"   <?= (old('civil_status', $resident['civil_status'] ?? '') == 'Married')   ? 'selected' : '' ?>>Married</option>
                <option value="Widowed"   <?= (old('civil_status', $resident['civil_status'] ?? '') == 'Widowed')   ? 'selected' : '' ?>>Widowed</option>
                <option value="Separated" <?= (old('civil_status', $resident['civil_status'] ?? '') == 'Separated') ? 'selected' : '' ?>>Separated</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Mother's Maiden Name</label>
              <input type="text" name="mother_maiden_name" class="form-input" value="<?= old('mother_maiden_name', $resident['mother_maiden_name'] ?? '') ?>">
            </div>
          </div>

          <div class="form-row cols-2">
            <div class="form-group">
              <label class="form-label">Occupation</label>
              <input type="text" name="occupation" class="form-input" value="<?= old('occupation', $resident['occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Monthly Family Net Income</label>
              <input type="number" name="monthly_income" class="form-input" step="0.01" value="<?= old('monthly_income', $resident['monthly_income'] ?? '') ?>">
            </div>
          </div>

          <div class="sub-section-label">Identification</div>
          <div class="form-row cols-4">
            <div class="form-group">
              <label class="form-label">ID Card Presented</label>
              <input type="text" name="id_card_presented" class="form-input" value="<?= old('id_card_presented', $resident['id_card_presented'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">ID Card Number</label>
              <input type="text" name="id_card_number" class="form-input" value="<?= old('id_card_number', $resident['id_card_number'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" class="form-input" value="<?= old('contact_number', $resident['contact_number'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Alternate Number</label>
              <input type="text" name="alternate_number" class="form-input" value="<?= old('alternate_number', $resident['alternate_number'] ?? '') ?>">
            </div>
          </div>

          <div class="sub-section-label">ID Pictures</div>
          <div class="form-row cols-2">
            <!-- ID Picture Front -->
            <div class="form-group">
              <label class="form-label">ID Picture (Front)</label>
              <div id="id-front-upload-wrap">
                <?php if(!empty($resident['id_picture_front'])): ?>
                  <!-- Existing file: show preview card immediately -->
                  <div class="file-preview-card" id="id-front-preview-card">
                    <img src="/<?= $resident['id_picture_front'] ?>" onclick="openLightbox(this.src)" alt="Front ID">
                    <div class="fp-info">
                      <div class="fp-name">Current Front ID</div>
                      <div class="fp-size">Click image to view</div>
                    </div>
                    <button type="button" class="fp-remove-btn" onclick="removeFilePreview('id-front','id_picture_front_remove')">
                      <i class="fa-solid fa-trash"></i> Remove
                    </button>
                  </div>
                  <input type="hidden" name="id_picture_front_remove" id="id_picture_front_remove" value="0">
                  <div id="id-front-picker" style="display:none;">
                    <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
                      <i class="fa-solid fa-upload fi-icon"></i>
                      <span class="fi-text">Choose a new front ID image</span>
                      <input type="file" name="id_picture_front" accept="image/*" onchange="handleFilePreview(this,'id-front')">
                    </label>
                  </div>
                <?php else: ?>
                  <input type="hidden" name="id_picture_front_remove" id="id_picture_front_remove" value="0">
                  <div id="id-front-preview-card" class="file-preview-card" style="display:none;">
                    <img onclick="openLightbox(this.src)" alt="Front ID">
                    <div class="fp-info">
                      <div class="fp-name" id="id-front-fname"></div>
                      <div class="fp-size" id="id-front-fsize"></div>
                    </div>
                    <button type="button" class="fp-remove-btn" onclick="removeFilePreview('id-front','id_picture_front_remove')">
                      <i class="fa-solid fa-trash"></i> Remove
                    </button>
                  </div>
                  <div id="id-front-picker">
                    <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
                      <i class="fa-solid fa-upload fi-icon"></i>
                      <span class="fi-text">Click to upload front ID image</span>
                      <input type="file" name="id_picture_front" accept="image/*" onchange="handleFilePreview(this,'id-front')">
                    </label>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <!-- ID Picture Back -->
            <div class="form-group">
              <label class="form-label">ID Picture (Back)</label>
              <div id="id-back-upload-wrap">
                <?php if(!empty($resident['id_picture_back'])): ?>
                  <div class="file-preview-card" id="id-back-preview-card">
                    <img src="/<?= $resident['id_picture_back'] ?>" onclick="openLightbox(this.src)" alt="Back ID">
                    <div class="fp-info">
                      <div class="fp-name">Current Back ID</div>
                      <div class="fp-size">Click image to view</div>
                    </div>
                    <button type="button" class="fp-remove-btn" onclick="removeFilePreview('id-back','id_picture_back_remove')">
                      <i class="fa-solid fa-trash"></i> Remove
                    </button>
                  </div>
                  <input type="hidden" name="id_picture_back_remove" id="id_picture_back_remove" value="0">
                  <div id="id-back-picker" style="display:none;">
                    <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
                      <i class="fa-solid fa-upload fi-icon"></i>
                      <span class="fi-text">Choose a new back ID image</span>
                      <input type="file" name="id_picture_back" accept="image/*" onchange="handleFilePreview(this,'id-back')">
                    </label>
                  </div>
                <?php else: ?>
                  <input type="hidden" name="id_picture_back_remove" id="id_picture_back_remove" value="0">
                  <div id="id-back-preview-card" class="file-preview-card" style="display:none;">
                    <img onclick="openLightbox(this.src)" alt="Back ID">
                    <div class="fp-info">
                      <div class="fp-name" id="id-back-fname"></div>
                      <div class="fp-size" id="id-back-fsize"></div>
                    </div>
                    <button type="button" class="fp-remove-btn" onclick="removeFilePreview('id-back','id_picture_back_remove')">
                      <i class="fa-solid fa-trash"></i> Remove
                    </button>
                  </div>
                  <div id="id-back-picker">
                    <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
                      <i class="fa-solid fa-upload fi-icon"></i>
                      <span class="fi-text">Click to upload back ID image</span>
                      <input type="file" name="id_picture_back" accept="image/*" onchange="handleFilePreview(this,'id-back')">
                    </label>
                  </div>
                <?php endif; ?>
              </div>
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
              <label class="form-label">House No. / Lot No.</label>
              <input type="text" name="house_no" class="form-input" value="<?= old('house_no', $resident['house_no'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Street</label>
              <input type="text" name="street" class="form-input" value="<?= old('street', $resident['street'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Subdivision / Village</label>
              <input type="text" name="subdivision" class="form-input" value="<?= old('subdivision', $resident['subdivision'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Barangay</label>
              <input type="text" name="permanent_barangay" class="form-input" value="<?= old('permanent_barangay', $resident['permanent_barangay'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">City / Municipality</label>
              <input type="text" name="permanent_city" class="form-input" value="<?= old('permanent_city', $resident['permanent_city'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Province</label>
              <input type="text" name="permanent_province" class="form-input" value="<?= old('permanent_province', $resident['permanent_province'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Zip Code</label>
              <input type="text" name="zip_code" class="form-input" value="<?= old('zip_code', $resident['zip_code'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- ══ IV. ADDITIONAL INFORMATION ══ -->
      <div class="section-card" id="sec-iv">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num">IV</div>
          <div class="section-title">Additional Information</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-2">
            <div class="form-group">
              <label class="form-label">4Ps Beneficiary</label>
              <div class="check-group">
                <label class="check-opt">
                  <input type="checkbox" name="is_4ps_beneficiary" value="1" <?= (old('is_4ps_beneficiary', $resident['is_4ps_beneficiary'] ?? 0) == 1) ? 'checked' : '' ?>>
                  Yes, this household is a 4Ps beneficiary
                </label>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">IP Type / Ethnicity</label>
              <input type="text" name="ip_ethnicity" class="form-input" value="<?= old('ip_ethnicity', $resident['ip_ethnicity'] ?? '') ?>">
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
            <i class="fa-solid fa-user-plus"></i> Add Family Member
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
<?php
if(!empty($family_members)):
    foreach($family_members as $index => $member):
        if(empty($member['name'])) continue;
?>
<tr data-member-index="<?= $index ?>">
    <td style="text-align:center; vertical-align:middle;">
        <div class="drag-handle">
            <i class="fa-solid fa-grip-vertical"></i>
        </div>
    </td>
    <td data-label="Name"><input type="text" class="fam-input" name="member_name[]" value="<?= htmlspecialchars($member['name'] ?? '') ?>"></td>
    <td data-label="Relation"><input type="text" class="fam-input" name="relation[]" value="<?= htmlspecialchars($member['relation'] ?? '') ?>"></td>
    <td data-label="Birthdate"><input type="date" class="fam-input" name="member_birthdate[]" onchange="calcMemberAge(this)" value="<?= $member['birthdate'] ?? '' ?>"></td>
    <td data-label="Age"><input type="number" class="fam-input mono" name="member_age[]" readonly style="width:44px;text-align:center" value="<?= $member['age'] ?? '' ?>"></td>
    <td data-label="Sex">
        <select class="fam-input" name="member_sex[]" style="width:72px">
            <option value="Male"   <?= ($member['sex'] ?? '') == 'Male'   ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= ($member['sex'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
    </td>
    <td data-label="Education"><input type="text" class="fam-input" name="education[]" value="<?= htmlspecialchars($member['education'] ?? '') ?>"></td>
    <td data-label="Occupation"><input type="text" class="fam-input" name="member_occupation[]" value="<?= htmlspecialchars($member['occupation'] ?? '') ?>"></td>
<td data-label="Photo" style="min-width:90px;">
    <div class="mem-photo-cell">
        <!-- Shown when no photo selected -->
        <div class="mem-photo-btns" style="display:<?= !empty($member['photo']) ? 'none' : 'flex' ?>;flex-direction:column;gap:4px;">
            <label class="mem-upload-btn" style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.6rem;font-weight:600;color:var(--text-2);white-space:nowrap;">
                <i class="fa-solid fa-cloud-upload-alt" style="color:var(--green-mid)"></i> Upload
                <input type="file" name="member_photo[]" accept="image/*" style="display:none" onchange="handleFamilyMemberPhotoUpload(this)">
            </label>
            <button type="button" onclick="openMemberCameraModal(this.closest('tr'))" style="display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.6rem;font-weight:600;color:var(--text-2);cursor:pointer;white-space:nowrap;">
                <i class="fa-solid fa-camera" style="color:var(--green-mid)"></i> Capture
            </button>
        </div>
        <!-- Shown after photo selected/captured OR if existing photo -->
        <div class="mem-photo-preview-wrap" style="display:<?= !empty($member['photo']) ? 'flex' : 'none' ?>;flex-direction:column;align-items:center;gap:4px;">
<img class="mem-photo-preview-img" src="<?= !empty($member['photo']) ? '/' . $member['photo'] : '' ?>" onclick="openLightbox(this.src)" style="width:36px;height:36px;border-radius:6px;object-fit:cover;border:2px solid var(--green-mid);cursor:zoom-in;">
            <button type="button" onclick="removeMemberPhoto(this.closest('tr'))" style="display:inline-flex;align-items:center;gap:3px;padding:2px 6px;border-radius:5px;border:1.5px solid var(--orange-light);background:var(--orange-bg);color:var(--orange-deep);font-size:.58rem;cursor:pointer;">
                <i class="fa-solid fa-trash"></i> Remove
            </button>
        </div>
        <input type="hidden" name="existing_member_photo[]" value="<?= htmlspecialchars($member['photo'] ?? '') ?>">
        <input type="hidden" name="member_photo_captured[]" class="member-photo-captured" value="">
    </div>
</td>
    <td data-label="Remarks"><input type="text" class="fam-input" name="remarks[]" value="<?= htmlspecialchars($member['remarks'] ?? '') ?>"></td>
    <td>
        <button type="button" class="remove-row-btn" onclick="this.closest('tr').remove(); checkFamEmpty()"><i class="fa-solid fa-xmark"></i></button>
        <input type="hidden" name="member_qr_token[]" value="<?= $member['qr_code_token'] ?? '' ?>">
        <input type="hidden" name="member_qr_id[]" value="<?= $member['qr_code_id'] ?? '' ?>">
    </td>
</tr>
<?php endforeach; endif; ?>
                    <?php if(empty($family_members)): ?>
                    <tr id="fam-empty">
                        <td colspan="12" style="text-align:center;padding:20px;color:var(--text-3);font-size:.7rem">
                            <i class="fa-solid fa-people-group" style="font-size:1.2rem;display:block;margin-bottom:6px;opacity:.3"></i>
                            No family members added yet.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <p class="form-hint mt-2"><i class="fa-solid fa-info-circle me-1"></i>QR codes for existing family members will be preserved. Drag the ⋮⋮ handle to reorder family members.</p>
    </div>
</div>

      <!-- ══ VI. VULNERABLE MEMBERS ══ -->
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
                <input type="number" name="vulnerable_older_persons" class="form-input" min="0" value="<?= old('vulnerable_older_persons', $resident['vulnerable_older_persons'] ?? 0) ?>" style="width:50px;text-align:center;font-family:'DM Mono',monospace">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_older_persons',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-baby me-1"></i>Pregnant Women</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pregnant',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_pregnant" class="form-input" min="0" value="<?= old('vulnerable_pregnant', $resident['vulnerable_pregnant'] ?? 0) ?>" style="width:50px;text-align:center;font-family:'DM Mono',monospace">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pregnant',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-hands-holding-child me-1"></i>Lactating Women</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_lactating',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_lactating" class="form-input" min="0" value="<?= old('vulnerable_lactating', $resident['vulnerable_lactating'] ?? 0) ?>" style="width:50px;text-align:center;font-family:'DM Mono',monospace">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_lactating',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
            <div class="vuln-counter">
              <div class="vc-label"><i class="fa-solid fa-wheelchair me-1"></i>PWDs</div>
              <div class="vc-ctrl">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pwd',-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="vulnerable_pwd" class="form-input" min="0" value="<?= old('vulnerable_pwd', $resident['vulnerable_pwd'] ?? 0) ?>" style="width:50px;text-align:center;font-family:'DM Mono',monospace">
                <button type="button" class="vc-btn" onclick="adjustCount('vulnerable_pwd',1)"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ VII. SHELTER ══ -->
      <div class="section-card" id="sec-vii">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num amber">VII</div>
          <div class="section-title">Shelter Damage Classification</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="shelter-group">
            <label class="shelter-chip s-none">
              <input type="radio" name="shelter_damage" value="No Damage" <?= (old('shelter_damage', $resident['shelter_damage'] ?? 'No Damage') == 'No Damage') ? 'checked' : '' ?>>
              <i class="fa-solid fa-house-circle-check"></i> No Damage
            </label>
            <label class="shelter-chip s-partial">
              <input type="radio" name="shelter_damage" value="Partially Damaged" <?= (old('shelter_damage', $resident['shelter_damage'] ?? '') == 'Partially Damaged') ? 'checked' : '' ?>>
              <i class="fa-solid fa-house-crack"></i> Partially Damaged
            </label>
            <label class="shelter-chip s-total">
              <input type="radio" name="shelter_damage" value="Totally Damaged" <?= (old('shelter_damage', $resident['shelter_damage'] ?? '') == 'Totally Damaged') ? 'checked' : '' ?>>
              <i class="fa-solid fa-house-chimney-crack"></i> Totally Damaged
            </label>
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
          <div class="ownership-group">
            <label class="own-chip">
              <input type="radio" name="ownership_status" value="Owner" <?= (old('ownership_status', $resident['ownership_status'] ?? 'Owner') == 'Owner') ? 'checked' : '' ?>>
              <i class="fa-solid fa-key"></i> Owner
            </label>
            <label class="own-chip">
              <input type="radio" name="ownership_status" value="Renter" <?= (old('ownership_status', $resident['ownership_status'] ?? '') == 'Renter') ? 'checked' : '' ?>>
              <i class="fa-solid fa-file-contract"></i> Renter
            </label>
            <label class="own-chip">
              <input type="radio" name="ownership_status" value="Sharer" <?= (old('ownership_status', $resident['ownership_status'] ?? '') == 'Sharer') ? 'checked' : '' ?>>
              <i class="fa-solid fa-handshake"></i> Sharer
            </label>
            <label class="own-chip">
              <input type="radio" name="ownership_status" value="Informal Settler" <?= (old('ownership_status', $resident['ownership_status'] ?? '') == 'Informal Settler') ? 'checked' : '' ?>>
              <i class="fa-solid fa-tent"></i> Informal Settler
            </label>
          </div>
        </div>
      </div>

      <!-- ══ IX. AUTHENTICATION ══ -->
      <div class="section-card" id="sec-ix">
        <div class="section-head" onclick="toggleSection(this)">
          <div class="section-num">IX</div>
          <div class="section-title">Authentication</div>
          <i class="fa-solid fa-chevron-down section-toggle"></i>
        </div>
        <div class="section-body">
          <div class="form-row cols-2">
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
    <!-- Preview card (shown when file chosen or existing) -->
    <?php if(!empty($resident['signature_thumbmark'])): ?>
      <div class="file-preview-card" id="sig-upload-preview-card">
        <img src="/<?= $resident['signature_thumbmark'] ?>" onclick="openLightbox(this.src)" alt="Signature">
        <div class="fp-info">
          <div class="fp-name">Current Signature / Thumbmark</div>
          <div class="fp-size">Click image to view</div>
        </div>
        <button type="button" class="fp-remove-btn" onclick="removeSigUploadPreview()">
          <i class="fa-solid fa-trash"></i> Remove
        </button>
      </div>
      <input type="hidden" name="signature_thumbmark_remove" id="signature_thumbmark_remove" value="0">
      <div id="sig-upload-picker" style="display:none;">
        <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
          <i class="fa-solid fa-signature fi-icon"></i>
          <span class="fi-text">Choose a new signature or thumbmark image</span>
          <input type="file" name="signature_thumbmark" id="sig-file-input" accept="image/*" onchange="handleSigUpload(this)">
        </label>
      </div>
    <?php else: ?>
      <input type="hidden" name="signature_thumbmark_remove" id="signature_thumbmark_remove" value="0">
      <div class="file-preview-card" id="sig-upload-preview-card" style="display:none;">
        <img onclick="openLightbox(this.src)" alt="Signature" id="sig-upload-preview-img">
        <div class="fp-info">
          <div class="fp-name" id="sig-upload-fname"></div>
          <div class="fp-size" id="sig-upload-fsize"></div>
        </div>
        <button type="button" class="fp-remove-btn" onclick="removeSigUploadPreview()">
          <i class="fa-solid fa-trash"></i> Remove
        </button>
      </div>
      <div id="sig-upload-picker">
        <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
          <i class="fa-solid fa-signature fi-icon"></i>
          <span class="fi-text">Click to upload signature or thumbmark image</span>
          <input type="file" name="signature_thumbmark" id="sig-file-input" accept="image/*" onchange="handleSigUpload(this)">
        </label>
      </div>
    <?php endif; ?>
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

  <!-- Source toggle buttons -->
  <div style="display:flex;gap:8px;margin-bottom:10px;">
    <button type="button" class="photo-source-btn active" id="thumb-btn-upload" onclick="setThumbSource('upload')">
      <i class="fa-solid fa-cloud-upload-alt"></i> Upload
    </button>
    <button type="button" class="photo-source-btn" id="thumb-btn-scan" onclick="setThumbSource('scan')">
      <i class="fa-solid fa-fingerprint"></i> Scan Fingerprint
    </button>
  </div>

  <!-- Upload option -->
  <div id="thumb-upload-option">
    <div id="thumbmark-upload-wrap">
      <?php if(!empty($resident['right_thumbmark'])): ?>
        <div class="file-preview-card" id="thumbmark-preview-card">
          <img src="/<?= $resident['right_thumbmark'] ?>" onclick="openLightbox(this.src)" alt="Right Thumbmark">
          <div class="fp-info">
            <div class="fp-name">Current Right Thumbmark</div>
            <div class="fp-size">Click image to view</div>
          </div>
          <button type="button" class="fp-remove-btn" onclick="removeFilePreview('thumbmark','right_thumbmark_remove')">
            <i class="fa-solid fa-trash"></i> Remove
          </button>
        </div>
        <input type="hidden" name="right_thumbmark_remove" id="right_thumbmark_remove" value="0">
        <div id="thumbmark-picker" style="display:none;">
          <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
            <i class="fa-solid fa-fingerprint fi-icon"></i>
            <span class="fi-text">Choose a new thumbmark image</span>
            <input type="file" name="right_thumbmark" accept="image/*" onchange="handleFilePreview(this,'thumbmark')">
          </label>
        </div>
      <?php else: ?>
        <input type="hidden" name="right_thumbmark_remove" id="right_thumbmark_remove" value="0">
        <div id="thumbmark-preview-card" class="file-preview-card" style="display:none;">
          <img onclick="openLightbox(this.src)" alt="Right Thumbmark">
          <div class="fp-info">
            <div class="fp-name" id="thumbmark-fname"></div>
            <div class="fp-size" id="thumbmark-fsize"></div>
          </div>
          <button type="button" class="fp-remove-btn" onclick="removeFilePreview('thumbmark','right_thumbmark_remove')">
            <i class="fa-solid fa-trash"></i> Remove
          </button>
        </div>
        <div id="thumbmark-picker">
          <label class="file-input-wrap" style="cursor:pointer;" onclick="this.querySelector('input').click()">
            <i class="fa-solid fa-fingerprint fi-icon"></i>
            <span class="fi-text">Click to upload right thumbmark</span>
            <input type="file" name="right_thumbmark" accept="image/*" onchange="handleFilePreview(this,'thumbmark')">
          </label>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Scan option -->
  <div id="thumb-scan-option" style="display:none;">
    <!-- Status indicator -->
    <div id="thumb-scan-status" style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;background:var(--surface2);border:1.5px solid var(--border);margin-bottom:10px;font-size:.72rem;color:var(--text-3);">
      <i class="fa-solid fa-circle-info" style="color:var(--green-mid)"></i>
      <span id="thumb-scan-status-text">ZKTeco Live 20R connected. Click "Scan Now" to capture fingerprint.</span>
    </div>
    <!-- Scan button -->
    <button type="button" id="thumb-scan-btn" onclick="scanFingerprint()" style="display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;border:none;background:linear-gradient(135deg,var(--green-deep),var(--green-mid));color:#fff;font-size:.75rem;font-weight:700;cursor:pointer;margin-bottom:12px;font-family:'Outfit',sans-serif;">
      <i class="fa-solid fa-fingerprint"></i> Scan Now
    </button>
    <!-- Scan result preview -->
    <div id="thumb-scan-preview" style="display:none;">
      <div class="file-preview-card">
        <img id="thumb-scan-img" onclick="openLightbox(this.src)" alt="Scanned Thumbmark" style="width:52px;height:52px;border-radius:7px;object-fit:contain;border:2px solid var(--green-mid);cursor:zoom-in;flex-shrink:0;">
        <div class="fp-info">
          <div class="fp-name"><i class="fa-solid fa-check-circle" style="color:var(--green-mid)"></i> Fingerprint Captured</div>
          <div class="fp-size" id="thumb-scan-quality">Quality: —</div>
        </div>
        <button type="button" class="fp-remove-btn" onclick="clearScannedThumbmark()">
          <i class="fa-solid fa-rotate-left"></i> Rescan
        </button>
      </div>
    </div>
    <!-- Hidden input: base64 fingerprint image sent to server -->
    <input type="hidden" name="right_thumbmark_scanned" id="right_thumbmark_scanned">
  </div>
          </div>
          <div class="form-row cols-3" style="margin-top:12px">
            <div class="form-group">
              <label class="form-label">Date Registered</label>
              <input type="date" name="registration_date" class="form-input" value="<?= old('registration_date', $resident['registration_date'] ?? date('Y-m-d')) ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Name of Barangay Captain</label>
              <input type="text" name="barangay_captain_name" class="form-input" value="<?= old('barangay_captain_name', $resident['barangay_captain_name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Name of LSWDO</label>
              <input type="text" name="lswdo_name" class="form-input" value="<?= old('lswdo_name', $resident['lswdo_name'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>


    </div><!-- /form-body -->

    <!-- ── Submit Bar ── -->
    <div class="submit-bar">
      <button type="submit" class="btn-save">
        <i class="fa-solid fa-floppy-disk"></i> Update Resident
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

<!-- ── Lightbox Modal ── -->
<div id="lightbox-modal" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.85);backdrop-filter:blur(6px);align-items:center;justify-content:center;cursor:zoom-out;">
    <img id="lightbox-img" style="max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,.5);object-fit:contain;">
    <button onclick="closeLightbox()" style="position:absolute;top:18px;right:18px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);border-radius:50%;width:38px;height:38px;color:#fff;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
        <i class="fa-solid fa-xmark"></i>
    </button>
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// ── Section toggle ──
function toggleSection(head) {
  head.closest('.section-card').classList.toggle('collapsed');
}

function scrollToSection(id) {
  const el = document.getElementById(id);
  if(el) el.scrollIntoView({behavior:'smooth', block:'start'});
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

// ── Vulnerable counter ──
function adjustCount(name, delta) {
  const input = document.querySelector(`input[name="${name}"]`);
  if(!input) return;
  input.value = Math.max(0, parseInt(input.value||0) + delta);
}

// Initialize SortableJS for drag and drop
let familySortable = null;

function initSortableFamilyList() {
    const familyBody = document.getElementById('familyBody');
    const emptyRow = document.getElementById('fam-empty');
    
    // Don't initialize if there's only the empty row
    if (!familyBody) return;
    
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
        familySortable = null;
    }
    
    // Make sure there are draggable rows (skip the empty row if it exists but has no content)
    const rows = familyBody.querySelectorAll('tr:not(#fam-empty)');
    if (rows.length === 0) return;
    
    // Create new Sortable instance
    familySortable = new Sortable(familyBody, {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function() {
            console.log('Family members reordered');
            // Optional: Update any order indicators if needed
        }
    });
}

let memberCameraRow = null;
let memberCameraBtn = null;
let currentMemberStream = null;

function addFamilyMember() {
    const empty = document.getElementById('fam-empty');
    if(empty) empty.remove();

    const tr = document.createElement('tr');
    const memberIndex = Date.now();
    
    tr.innerHTML = `
        <td style="text-align:center; vertical-align:middle;">
            <div class="drag-handle">
                <i class="fa-solid fa-grip-vertical"></i>
            </div>
        </td>
        <td data-label="Name"><input type="text" class="fam-input" name="member_name[]" placeholder="Full name"></td>
        <td data-label="Relation"><input type="text" class="fam-input" name="relation[]" placeholder="Son/Daughter…"></td>
        <td data-label="Birthdate"><input type="date" class="fam-input" name="member_birthdate[]" onchange="calcMemberAge(this)"></td>
        <td data-label="Age"><input type="number" class="fam-input mono" name="member_age[]" readonly style="width:60px;text-align:center"></td>
        <td data-label="Sex">
            <select class="fam-input" name="member_sex[]" style="width:72px">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </td>
        <td data-label="Education"><input type="text" class="fam-input" name="education[]" placeholder="Attainment"></td>
        <td data-label="Occupation"><input type="text" class="fam-input" name="member_occupation[]" placeholder="Occupation"></td>
<td data-label="Photo" style="min-width:90px;">
            <div class="mem-photo-cell">
                <div class="mem-photo-btns" style="display:flex;flex-direction:column;gap:4px;">
                    <label class="mem-upload-btn" style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.6rem;font-weight:600;color:var(--text-2);white-space:nowrap;">
                        <i class="fa-solid fa-cloud-upload-alt" style="color:var(--green-mid)"></i> Upload
                        <input type="file" name="member_photo[]" accept="image/*" style="display:none" onchange="handleFamilyMemberPhotoUpload(this)">
                    </label>
                    <button type="button" onclick="openMemberCameraModal(this.closest('tr'))" style="display:inline-flex;align-items:center;gap:4px;padding:3px 7px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);font-size:.6rem;font-weight:600;color:var(--text-2);cursor:pointer;white-space:nowrap;">
                        <i class="fa-solid fa-camera" style="color:var(--green-mid)"></i> Capture
                    </button>
                </div>
                <div class="mem-photo-preview-wrap" style="display:none;flex-direction:column;align-items:center;gap:4px;">
                    <img class="mem-photo-preview-img" style="width:36px;height:36px;border-radius:6px;object-fit:cover;border:2px solid var(--green-mid);">
                    <button type="button" onclick="removeMemberPhoto(this.closest('tr'))" style="display:inline-flex;align-items:center;gap:3px;padding:2px 6px;border-radius:5px;border:1.5px solid var(--orange-light);background:var(--orange-bg);color:var(--orange-deep);font-size:.58rem;cursor:pointer;">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>
                <input type="hidden" name="existing_member_photo[]" value="">
                <input type="hidden" name="member_photo_captured[]" class="member-photo-captured" value="">
            </div>
        </td>
        <td data-label="Remarks"><input type="text" class="fam-input" name="remarks[]" placeholder="Remarks" style="min-width:130px"></td>
        <td><button type="button" class="remove-row-btn" onclick="this.closest('tr').remove(); checkFamEmpty()"><i class="fa-solid fa-xmark"></i></button></td>
    `;
    document.getElementById('familyBody').appendChild(tr);
    
    // Apply uppercase to new inputs
    applyAutoUppercase(tr);
    
    // Re-initialize Sortable after adding new row
    initSortableFamilyList();
}

// New function: Set member photo source (upload or camera)
function setMemberPhotoSource(btn, source) {
    const row = btn.closest('tr');
    const uploadDiv = row.querySelector('.member-upload-option');
    const cameraDiv = row.querySelector('.member-camera-option');
    const btns = row.querySelectorAll('.member-photo-source-btn');
    
    btns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    if (source === 'upload') {
        uploadDiv.style.display = 'block';
        cameraDiv.style.display = 'none';
    } else {
        uploadDiv.style.display = 'none';
        cameraDiv.style.display = 'block';
    }
}

// Open camera modal for family member
function openMemberCameraModal(element) {
    const row = element.closest('tr');
    memberCameraRow = row;
    
    const modal = document.getElementById('member-camera-modal');
    if (!modal) {
        createMemberCameraModal();
    }
    
    const memberModal = document.getElementById('member-camera-modal');
    memberModal.style.display = 'flex';
    memberModal.dataset.targetRow = row.getAttribute('data-row-id') || Date.now();
    if (!row.getAttribute('data-row-id')) {
        row.setAttribute('data-row-id', Date.now());
    }
    
    // Reset UI
    document.getElementById('member-camera-preview').style.display = 'none';
    document.getElementById('member-capture-btn').style.display = 'inline-flex';
    document.getElementById('member-retake-btn').style.display = 'none';
    document.getElementById('member-confirm-btn').style.display = 'none';
    
    // Reset captured data
    window.capturedMemberPhotoData = null;
    
    // Start camera
    startMemberCamera();
}

// Create member camera modal (add this to the page, near the end of the file before </div>)
function createMemberCameraModal() {
    const modalHTML = `
    <div id="member-camera-modal" style="display:none;position:fixed;inset:0;z-index:10001;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:24px;padding:28px;width:min(700px,95vw);max-width:95vw;box-shadow:0 30px 80px rgba(0,0,0,.4);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <div>
                    <div style="font-size:1.2rem;font-weight:800;color:var(--text-1);">
                        <i class="fa-solid fa-camera" style="color:var(--green-mid);margin-right:12px;"></i>Capture Family Member Photo
                    </div>
                    <div style="font-size:.75rem;color:var(--text-3);margin-top:6px;">Position face clearly in the frame</div>
                </div>
                <button type="button" onclick="closeMemberCameraModal()" style="background:var(--bg);border:1.5px solid var(--border);border-radius:10px;width:38px;height:38px;cursor:pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div style="position:relative;background:#1a1a1a;border-radius:16px;overflow:hidden;margin-bottom:20px;">
                <video id="member-camera-video" autoplay playsinline style="width:100%;height:auto;min-height:300px;display:block;background:#1a1a1a;"></video>
                <canvas id="member-camera-canvas" style="display:none;"></canvas>
            </div>
            
            <div id="member-camera-preview" style="display:none;margin-bottom:20px;">
                <div style="border:2px solid var(--green-mid);border-radius:16px;padding:16px;background:var(--green-bg);">
                    <div style="font-size:.7rem;color:var(--green-deep);font-weight:700;margin-bottom:10px;">
                        <i class="fa-solid fa-check-circle"></i> Captured Photo Preview
                    </div>
                    <img id="member-camera-preview-img" style="width:100%;max-height:250px;border-radius:12px;object-fit:contain;">
                </div>
            </div>
            
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <button type="button" id="member-capture-btn" class="btn-save" onclick="captureMemberPhoto()" style="padding:10px 28px;">
                    <i class="fa-solid fa-camera"></i> Capture
                </button>
                <button type="button" id="member-retake-btn" class="btn-cancel" onclick="retakeMemberPhoto()" style="display:none;padding:10px 28px;">
                    <i class="fa-solid fa-rotate-left"></i> Retake
                </button>
                <button type="button" id="member-confirm-btn" class="btn-save" onclick="confirmMemberPhoto()" style="display:none;padding:10px 28px;background:linear-gradient(135deg, var(--green-deep), var(--green-mid));">
                    <i class="fa-solid fa-check"></i> Use Photo
                </button>
            </div>
        </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

let memberCameraStream = null;
let capturedMemberPhotoData = null;

function startMemberCamera() {
    const video = document.getElementById('member-camera-video');
    
    if (memberCameraStream) {
        stopMemberCamera();
    }
    
    const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    const constraints = isMobile
        ? { video: { facingMode: { ideal: "environment" } } }
        : { video: true };
    
    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            memberCameraStream = stream;
            video.srcObject = stream;
        })
        .catch(err => {
            console.error('Camera error, trying any camera:', err);
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(fallbackStream => {
                    memberCameraStream = fallbackStream;
                    video.srcObject = fallbackStream;
                })
                .catch(fallbackErr => {
                    console.error('Camera fallback failed:', fallbackErr);
                    alert('Unable to access camera. Please check permissions.');
                    closeMemberCameraModal();
                });
        });
}

function stopMemberCamera() {
    if (memberCameraStream) {
        memberCameraStream.getTracks().forEach(track => track.stop());
        memberCameraStream = null;
    }
    const video = document.getElementById('member-camera-video');
    if (video) {
        video.srcObject = null;
    }
}

function captureMemberPhoto() {
    const video = document.getElementById('member-camera-video');
    const canvas = document.getElementById('member-camera-canvas');
    
    if (!video.videoWidth || !video.videoHeight) {
        alert('Camera not ready. Please wait.');
        return;
    }
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    capturedMemberPhotoData = canvas.toDataURL('image/jpeg', 0.9);
    
    // Hide the video element completely
    video.style.display = 'none';
    
    const previewImg = document.getElementById('member-camera-preview-img');
    previewImg.src = capturedMemberPhotoData;
    document.getElementById('member-camera-preview').style.display = 'block';
    
    document.getElementById('member-capture-btn').style.display = 'none';
    document.getElementById('member-retake-btn').style.display = 'inline-flex';
    document.getElementById('member-confirm-btn').style.display = 'inline-flex';
    
    stopMemberCamera();
}

function retakeMemberPhoto() {
    document.getElementById('member-camera-preview').style.display = 'none';
    capturedMemberPhotoData = null;
    
    // Show video again
    const video = document.getElementById('member-camera-video');
    video.style.display = 'block';
    
    document.getElementById('member-capture-btn').style.display = 'inline-flex';
    document.getElementById('member-retake-btn').style.display = 'none';
    document.getElementById('member-confirm-btn').style.display = 'none';
    
    startMemberCamera();
}

function confirmMemberPhoto() {
    if (!capturedMemberPhotoData) {
        alert('Please capture a photo first.');
        return;
    }
    if (memberCameraRow) {
        const hiddenInput = memberCameraRow.querySelector('.member-photo-captured');
        if (hiddenInput) hiddenInput.value = capturedMemberPhotoData;
        showMemberPhotoPreview(memberCameraRow, capturedMemberPhotoData);
    }
    closeMemberCameraModal();
    const toast = document.createElement('div');
    toast.textContent = 'Photo captured successfully!';
    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;background:var(--green-mid);color:#fff;padding:8px 16px;border-radius:8px;z-index:10002;font-size:.75rem;';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

function closeMemberCameraModal() {
    const modal = document.getElementById('member-camera-modal');
    if (modal) {
        modal.style.display = 'none';
    }
    
    // Reset video display
    const video = document.getElementById('member-camera-video');
    if (video) {
        video.style.display = 'block';
    }
    
    stopMemberCamera();
    capturedMemberPhotoData = null;
    memberCameraRow = null;
}

function handleFamilyMemberPhotoUpload(input) {
    const row = input.closest('tr');
    // Clear any previously captured base64
    const capturedInput = row.querySelector('.member-photo-captured');
    if (capturedInput) capturedInput.value = '';

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
    if (!cell) return;
    cell.querySelector('.mem-photo-btns').style.display = 'none';
    const wrap = cell.querySelector('.mem-photo-preview-wrap');
    wrap.style.display = 'flex';
    const img = wrap.querySelector('.mem-photo-preview-img');
    img.src = src;
    img.style.cursor = 'zoom-in';
    img.onclick = function() { openLightbox(this.src); };
}

function removeMemberPhoto(row) {
    const cell = row.querySelector('.mem-photo-cell');
    if (!cell) return;
    const fileInput = cell.querySelector('input[type="file"]');
    if (fileInput) fileInput.value = '';
    const capturedInput = cell.querySelector('.member-photo-captured');
    if (capturedInput) capturedInput.value = '';
    const existingInput = cell.querySelector('input[name="existing_member_photo[]"]');
    if (existingInput) existingInput.value = '';
    cell.querySelector('.mem-photo-btns').style.display = 'flex';
    cell.querySelector('.mem-photo-preview-wrap').style.display = 'none';
    cell.querySelector('.mem-photo-preview-img').src = '';
}

// Check if family table is empty
function checkFamEmpty() {
    const familyBody = document.getElementById('familyBody');
    if(!familyBody) return;
    
    // Get all non-empty rows (excluding the empty placeholder)
    const rows = familyBody.querySelectorAll('tr');
    let hasData = false;
    rows.forEach(row => {
        const nameInput = row.querySelector('input[name="member_name[]"]');
        if(nameInput && nameInput.value.trim() !== '') {
            hasData = true;
        }
    });
    
    if(!hasData && rows.length === 0) {
        const tr = document.createElement('tr');
        tr.id = 'fam-empty';
        tr.innerHTML = '<td colspan="12" style="text-align:center;padding:20px;color:var(--text-3);font-size:.7rem"><i class="fa-solid fa-people-group" style="font-size:1.2rem;display:block;margin-bottom:6px;opacity:.3"></i>No family members added yet.</td>';
        familyBody.appendChild(tr);
        
        // Destroy Sortable when empty
        if (familySortable) {
            familySortable.destroy();
            familySortable = null;
        }
    } else {
        // Remove empty placeholder if it exists
        const emptyRow = document.getElementById('fam-empty');
        if(emptyRow) emptyRow.remove();
        
        // Reinitialize Sortable when rows exist
        setTimeout(function() {
            initSortableFamilyList();
        }, 50);
    }
}

function calcMemberAge(input) {
  const row = input.closest('tr');
  const af  = row.querySelector('input[name="member_age[]"]');
  if(!input.value) return;
  const today = new Date(), bd = new Date(input.value);
  let age = today.getFullYear() - bd.getFullYear();
  const m = today.getMonth() - bd.getMonth();
  if(m < 0 || (m===0 && today.getDate() < bd.getDate())) age--;
  af.value = age;
}

// Update file name display
function updateFileName(input) {
    if (input.files && input.files[0]) {
        const span = input.previousElementSibling;
        if (span && span.classList && span.classList.contains('mem-photo-name')) {
            span.textContent = 'Selected';
        } else if (span) {
            span.textContent = input.files[0].name.substring(0, 10) + '…';
        }
    }
}

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

// ── Collect family members JSON on submit ──
document.getElementById('beneficiaryForm').addEventListener('submit', function() {
  const rows = document.querySelectorAll('#familyBody tr:not(#fam-empty)');
  const familyData = [];
  rows.forEach(row => {
    const name = row.querySelector('input[name="member_name[]"]')?.value || '';
    if(!name.trim()) return;
    familyData.push({
      name: name,
      relation:   row.querySelector('input[name="relation[]"]')?.value || '',
      birthdate:  row.querySelector('input[name="member_birthdate[]"]')?.value || '',
      age:        row.querySelector('input[name="member_age[]"]')?.value || '',
      sex:        row.querySelector('select[name="member_sex[]"]')?.value || '',
      education:  row.querySelector('input[name="education[]"]')?.value || '',
      occupation: row.querySelector('input[name="member_occupation[]"]')?.value || '',
      remarks:    row.querySelector('input[name="remarks[]"]')?.value || '',
      qr_code_token: row.querySelector('input[name="member_qr_token[]"]')?.value || '',
      qr_code_id:    row.querySelector('input[name="member_qr_id[]"]')?.value || '',
      photo:         row.querySelector('input[name="existing_member_photo[]"]')?.value || '',
      id_photo_front: row.querySelector('input[name="existing_member_id_photo[]"]')?.value || ''
    });
  });
  const hidden = document.getElementById('familyMembersJSON');
  if(hidden) hidden.value = JSON.stringify(familyData);
});

function handleHeadPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { showHeadPhotoPreview(e.target.result); };
        reader.readAsDataURL(input.files[0]);
    }
}

function showHeadPhotoPreview(src) {
    const img = document.getElementById('prev-head');
    img.src = src;
    img.style.cursor = 'zoom-in';
    img.onclick = function() { openLightbox(this.src); };
    document.getElementById('head-photo-btns').style.display = 'none';
    document.getElementById('head-photo-preview-wrap').style.display = 'flex';
    const removeFlag = document.getElementById('head_photo_remove');
    if (removeFlag) removeFlag.value = '0';
}

function removeHeadPhoto() {
    const fileInput = document.getElementById('head_photo_file');
    if (fileInput) fileInput.value = '';
    document.getElementById('head_photo_captured').value = '';
    document.getElementById('prev-head').src = '';
    document.getElementById('head-photo-preview-wrap').style.display = 'none';
    document.getElementById('head-photo-btns').style.display = 'flex';
    // Signal server to remove the existing photo
    const removeFlag = document.getElementById('head_photo_remove');
    if (removeFlag) removeFlag.value = '1';
}

// ── Handle family member photo upload with preview for edit form ──
function handleFamilyMemberPhotoEdit(input, row) {
    const photoCell = input.closest('td');
    let previewDiv = photoCell.querySelector('.member-photo-preview');
    const fileNameSpan = photoCell.querySelector('.mem-photo-name');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        fileNameSpan.textContent = 'Selected';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Make sure preview div exists and is visible
            if (previewDiv) {
                previewDiv.style.display = 'block';
                previewDiv.innerHTML = `<img src="${e.target.result}" style="width:50px;height:50px;border-radius:5px;object-fit:cover;border:2px solid var(--green-mid);box-shadow:0 2px 8px rgba(0,0,0,0.1);">`;
            } else {
                // Create preview div if it doesn't exist
                const newPreviewDiv = document.createElement('div');
                newPreviewDiv.className = 'member-photo-preview';
                newPreviewDiv.style.marginBottom = '5px';
                newPreviewDiv.style.textAlign = 'center';
                newPreviewDiv.innerHTML = `<img src="${e.target.result}" style="width:50px;height:50px;border-radius:5px;object-fit:cover;border:2px solid var(--green-mid);box-shadow:0 2px 8px rgba(0,0,0,0.1);">`;
                
                // Insert at the beginning of the cell
                photoCell.insertBefore(newPreviewDiv, photoCell.firstChild);
                previewDiv = newPreviewDiv;
            }
            
            // Hide the existing image if there is one (from database)
            const oldImage = photoCell.querySelector('img:not(.member-photo-preview img)');
            if (oldImage && oldImage !== previewDiv.querySelector('img')) {
                oldImage.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    } else {
        fileNameSpan.textContent = 'Choose';
        
        // If no file selected, show the original image if it exists
        if (previewDiv) {
            previewDiv.style.display = 'none';
            previewDiv.innerHTML = '';
            
            const oldImage = photoCell.querySelector('img:not(.member-photo-preview img)');
            if (oldImage) {
                oldImage.style.display = 'block';
            }
        }
    }
}

// ── Auto-uppercase all text inputs on input event ──
function applyAutoUppercase(root) {
  root = root || document;
  root.querySelectorAll('input[type="text"], input:not([type]), .fam-input:not(select)').forEach(function(el) {
    if (el.dataset && el.dataset.ucBound) return;
    if (el.dataset) el.dataset.ucBound = '1';
    el.addEventListener('input', function() {
      var pos = this.selectionStart;
      this.value = this.value.toUpperCase();
      this.setSelectionRange(pos, pos);
    });
    if (el.value) el.value = el.value.toUpperCase();
  });
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    calculateAge();
    applyAutoUppercase();
    
    // Initialize Sortable for existing family members
    setTimeout(function() {
        initSortableFamilyList();
    }, 100);
});

// Also initialize after any AJAX or dynamic content
window.addEventListener('load', function() {
    setTimeout(function() {
        initSortableFamilyList();
    }, 200);
});

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

async function startCameraModal() {
    const video = document.getElementById('camera-modal-video');
    
    try {
        if (cameraModalStream) {
            stopCameraModal();
        }
        
        const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const constraints = isMobile
            ? { video: { facingMode: { ideal: "environment" } } }
            : { video: true };
        
        try {
            cameraModalStream = await navigator.mediaDevices.getUserMedia(constraints);
        } catch (err) {
            // Fallback to any camera if preferred mode fails
            cameraModalStream = await navigator.mediaDevices.getUserMedia({ video: true });
        }
        
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
    if (!capturedModalPhotoData) {
        alert('Please capture a photo first.');
        return;
    }
    document.getElementById('head_photo_captured').value = capturedModalPhotoData;
    showHeadPhotoPreview(capturedModalPhotoData);
    closeCameraModal();
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


function toggleEraser() {
  sigEraserActive = !sigEraserActive;
  sigCropMode = false; // mutually exclusive
  cancelCrop();
  const btn = document.getElementById('sig-eraser-btn');
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

let cropOverlayCanvas = null; // the transparent overlay canvas

function toggleCropMode() {
  sigCropMode = !sigCropMode;
  sigEraserActive = false;
  // Reset eraser UI
  const eraserBtn = document.getElementById('sig-eraser-btn');
  const sizeWrap  = document.getElementById('sig-eraser-size-wrap');
  eraserBtn.style.background = 'var(--surface)'; eraserBtn.style.color = 'var(--text-2)'; eraserBtn.style.borderColor = 'var(--border)';
  sizeWrap.style.display = 'none';

  const cropBtn   = document.getElementById('sig-crop-btn');
  const cropCtrls = document.getElementById('sig-crop-controls');
  const drawCanvas = document.getElementById('sig-modal-canvas');

  if (sigCropMode) {
    cropBtn.style.background = 'var(--green-bg)'; cropBtn.style.color = 'var(--green-deep)'; cropBtn.style.borderColor = 'var(--green-mid)';
    cropCtrls.style.display = 'flex';
    initCropOverlay();
  } else {
    cancelCrop();
  }
}

function initCropOverlay() {
  const drawCanvas = document.getElementById('sig-modal-canvas');
  const wrap = drawCanvas.parentNode;

  // Remove any previous overlay
  if (cropOverlayCanvas) { cropOverlayCanvas.remove(); cropOverlayCanvas = null; }

  // Create overlay canvas positioned exactly over the draw canvas
  cropOverlayCanvas = document.createElement('canvas');
  cropOverlayCanvas.width  = drawCanvas.width;
  cropOverlayCanvas.height = drawCanvas.height;
  cropOverlayCanvas.style.cssText = `
    position:absolute;
    left:${drawCanvas.offsetLeft}px;
    top:${drawCanvas.offsetTop}px;
    width:${drawCanvas.offsetWidth}px;
    height:${drawCanvas.offsetHeight}px;
    border-radius:8px;
    cursor:move;
    touch-action:none;
    z-index:10;
  `;
  // Make wrap position:relative so overlay positions correctly
  if (getComputedStyle(wrap).position === 'static') wrap.style.position = 'relative';
  wrap.appendChild(cropOverlayCanvas);

  // Default crop box centered
  const cw = drawCanvas.width, ch = drawCanvas.height;
  const bw = Math.min(parseInt(document.getElementById('crop-w').value) || 400, cw - 20);
  const bh = Math.min(parseInt(document.getElementById('crop-h').value) || 180, ch - 20);
  cropOverlay = { x: Math.floor((cw - bw) / 2), y: Math.floor((ch - bh) / 2), w: bw, h: bh, dragging: false, ox: 0, oy: 0 };

  document.getElementById('crop-w').value = bw;
  document.getElementById('crop-h').value = bh;

  drawCropOverlay();

  // W/H inputs resize the box
  document.getElementById('crop-w').oninput = function() {
    if (!cropOverlay) return;
    cropOverlay.w = Math.max(30, Math.min(parseInt(this.value)||100, drawCanvas.width - cropOverlay.x));
    drawCropOverlay();
  };
  document.getElementById('crop-h').oninput = function() {
    if (!cropOverlay) return;
    cropOverlay.h = Math.max(20, Math.min(parseInt(this.value)||80, drawCanvas.height - cropOverlay.y));
    drawCropOverlay();
  };

  // Drag on overlay canvas
  function overlayPos(e) {
    const r  = cropOverlayCanvas.getBoundingClientRect();
    const sx = cropOverlayCanvas.width / r.width, sy = cropOverlayCanvas.height / r.height;
    const s  = e.touches ? e.touches[0] : e;
    return { x: (s.clientX - r.left) * sx, y: (s.clientY - r.top) * sy };
  }

  cropOverlayCanvas.addEventListener('mousedown', (e) => {
    e.preventDefault();
    const p = overlayPos(e);
    // Only drag if click is inside the box
    if (p.x >= cropOverlay.x && p.x <= cropOverlay.x + cropOverlay.w &&
        p.y >= cropOverlay.y && p.y <= cropOverlay.y + cropOverlay.h) {
      cropOverlay.dragging = true;
      cropOverlay.ox = p.x - cropOverlay.x;
      cropOverlay.oy = p.y - cropOverlay.y;
    }
  });
  cropOverlayCanvas.addEventListener('mousemove', (e) => {
    if (!cropOverlay || !cropOverlay.dragging) return;
    const p = overlayPos(e);
    const dc = document.getElementById('sig-modal-canvas');
    cropOverlay.x = Math.max(0, Math.min(p.x - cropOverlay.ox, dc.width  - cropOverlay.w));
    cropOverlay.y = Math.max(0, Math.min(p.y - cropOverlay.oy, dc.height - cropOverlay.h));
    drawCropOverlay();
  });
  cropOverlayCanvas.addEventListener('mouseup',    () => { if (cropOverlay) cropOverlay.dragging = false; });
  cropOverlayCanvas.addEventListener('mouseleave', () => { if (cropOverlay) cropOverlay.dragging = false; });
  cropOverlayCanvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    const p = overlayPos(e);
    if (p.x >= cropOverlay.x && p.x <= cropOverlay.x + cropOverlay.w &&
        p.y >= cropOverlay.y && p.y <= cropOverlay.y + cropOverlay.h) {
      cropOverlay.dragging = true; cropOverlay.ox = p.x - cropOverlay.x; cropOverlay.oy = p.y - cropOverlay.y;
    }
  }, { passive: false });
  cropOverlayCanvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    if (!cropOverlay || !cropOverlay.dragging) return;
    const p = overlayPos(e);
    const dc = document.getElementById('sig-modal-canvas');
    cropOverlay.x = Math.max(0, Math.min(p.x - cropOverlay.ox, dc.width  - cropOverlay.w));
    cropOverlay.y = Math.max(0, Math.min(p.y - cropOverlay.oy, dc.height - cropOverlay.h));
    drawCropOverlay();
  }, { passive: false });
  cropOverlayCanvas.addEventListener('touchend', () => { if (cropOverlay) cropOverlay.dragging = false; });
}

function drawCropOverlay() {
  if (!cropOverlayCanvas || !cropOverlay) return;
  const oc  = cropOverlayCanvas;
  const ctx = oc.getContext('2d');
  ctx.clearRect(0, 0, oc.width, oc.height);

  // Dark mask outside the crop box
  ctx.fillStyle = 'rgba(0,0,0,0.5)';
  ctx.fillRect(0, 0, oc.width, oc.height);

  // "Punch out" the crop area (make it fully transparent to show drawing below)
  ctx.clearRect(cropOverlay.x, cropOverlay.y, cropOverlay.w, cropOverlay.h);

  // Dashed border around crop box
  ctx.save();
  ctx.strokeStyle = '#77BC3F';
  ctx.lineWidth   = 2;
  ctx.setLineDash([6, 3]);
  ctx.strokeRect(cropOverlay.x + 1, cropOverlay.y + 1, cropOverlay.w - 2, cropOverlay.h - 2);
  ctx.setLineDash([]);
  // Corner handles
  ctx.fillStyle = '#77BC3F';
  const hs = 7;
  [[cropOverlay.x, cropOverlay.y],[cropOverlay.x + cropOverlay.w - hs, cropOverlay.y],
   [cropOverlay.x, cropOverlay.y + cropOverlay.h - hs],[cropOverlay.x + cropOverlay.w - hs, cropOverlay.y + cropOverlay.h - hs]]
  .forEach(([rx, ry]) => ctx.fillRect(rx, ry, hs, hs));
  ctx.restore();
}

function applyCrop() {
  if (!sigModalCtx || !cropOverlay) return;
  const drawCanvas = document.getElementById('sig-modal-canvas');
  const { x, y, w, h } = cropOverlay;

  // Snapshot the cropped region from the drawing canvas
  const tmp = document.createElement('canvas');
  tmp.width = w; tmp.height = h;
  tmp.getContext('2d').drawImage(drawCanvas, x, y, w, h, 0, 0, w, h);

  // Clear drawing canvas and refill white, then draw cropped content centered
  sigModalCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
  sigModalCtx.fillStyle = '#ffffff';
  sigModalCtx.fillRect(0, 0, drawCanvas.width, drawCanvas.height);
  const dx = Math.floor((drawCanvas.width  - w) / 2);
  const dy = Math.floor((drawCanvas.height - h) / 2);
  sigModalCtx.drawImage(tmp, dx, dy);

  cancelCrop();
}

function cancelCrop() {
  sigCropMode = false;
  cropOverlay = null;
  if (cropOverlayCanvas) { cropOverlayCanvas.remove(); cropOverlayCanvas = null; }

  const cropBtn   = document.getElementById('sig-crop-btn');
  const cropCtrls = document.getElementById('sig-crop-controls');
  if (cropBtn)   { cropBtn.style.background = 'var(--surface)'; cropBtn.style.color = 'var(--text-2)'; cropBtn.style.borderColor = 'var(--border)'; }
  if (cropCtrls) cropCtrls.style.display = 'none';
  const canvas = document.getElementById('sig-modal-canvas');
  if (canvas) canvas.style.cursor = 'crosshair';
}

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

function clearSigDraw() {
  document.getElementById('sig-data-input').value = '';
  document.getElementById('sig-saved-preview').style.display = 'none';
  // Re-open the modal so user can re-draw
  openSigModal();
}

function initInlineCanvas() {
  // No-op: inline canvas is now hidden; drawing only happens in the modal
}

function clearSigCanvas() {
  if (!sigInlineCtx) return;
  const c = document.getElementById('sig-canvas');
  sigInlineCtx.clearRect(0, 0, c.width, c.height);
  document.getElementById('sig-data-input').value = '';
  document.getElementById('sig-saved-preview').style.display = 'none';
}

function openSigModal() {
  const modal = document.getElementById('sig-modal');
  modal.style.display = 'flex';
  modal.style.cursor = 'default';

  sigEraserActive = false;
  sigCropMode = false;
  const eraserBtn = document.getElementById('sig-eraser-btn');
  const cropBtn   = document.getElementById('sig-crop-btn');
  if (eraserBtn) { eraserBtn.style.cssText += ';background:var(--surface);color:var(--text-2);border-color:var(--border);'; }
  if (cropBtn)   { cropBtn.style.cssText   += ';background:var(--surface);color:var(--text-2);border-color:var(--border);'; }
  const esw = document.getElementById('sig-eraser-size-wrap');
  const cc  = document.getElementById('sig-crop-controls');
  if (esw) esw.style.display = 'none';
  if (cc)  cc.style.display  = 'none';

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
// ══ SIGNATURE DRAWING PAD ══
let sigModalCtx    = null;
let sigInlineCtx   = null;
let sigEraserActive = false;
// (crop removed — replaced by preview/resize modal)

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

  // Export with TRANSPARENT background: re-draw on a fresh canvas without white fill
  const tmp = document.createElement('canvas');
  tmp.width  = canvas.width;
  tmp.height = canvas.height;
  const tmpCtx = tmp.getContext('2d');
  // Use destination-in trick: draw white-bg sig, then set globalCompositeOperation
  // Simpler: draw the original but make white pixels transparent via pixel manipulation
  tmpCtx.drawImage(canvas, 0, 0);
  const imgData = tmpCtx.getImageData(0, 0, tmp.width, tmp.height);
  const d = imgData.data;
  for (let i = 0; i < d.length; i += 4) {
    // Make near-white pixels fully transparent
    if (d[i] > 240 && d[i+1] > 240 && d[i+2] > 240) {
      d[i+3] = 0;
    }
  }
  tmpCtx.putImageData(imgData, 0, 0);

  const dataURL = tmp.toDataURL('image/png');

  // Pass to preview modal
  openSigPreviewModal(dataURL);
}

/* ── Signature Preview / Resize Modal ── */
let _sigResizeData = '';   // stores the transparent-bg dataURL
let _sigDrag = null;       // drag state for moving
let _sigResz = null;       // drag state for resizing

function openSigPreviewModal(dataURL) {
  _sigResizeData = dataURL;

  const modal = document.getElementById('sig-preview-modal');
  const img   = document.getElementById('sig-resize-img');
  const stage = document.getElementById('sig-resize-stage');
  const handle= document.getElementById('sig-resize-handle');

  img.src = dataURL;
  modal.style.display = 'flex';

  // Wait for img natural size
  img.onload = function() {
    // Start at a sensible default: 70% of stage width, keep aspect ratio
    const stageW  = stage.clientWidth;
    const stageH  = stage.clientHeight;
    const natW    = img.naturalWidth  || 320;
    const natH    = img.naturalHeight || 120;
    const scale   = Math.min((stageW * 0.7) / natW, (stageH * 0.7) / natH, 1);
    const initW   = Math.round(natW * scale);
    const initH   = Math.round(natH * scale);
    const initL   = Math.round((stageW - initW) / 2);
    const initT   = Math.round((stageH - initH) / 2);

    img.style.width  = initW + 'px';
    img.style.height = initH + 'px';
    img.style.left   = initL + 'px';
    img.style.top    = initT + 'px';

    updateResizeHandle();
    updateSizeLabelSig();
  };
  if (img.complete && img.naturalWidth) img.onload(); // fire immediately if cached

  // ── Move (drag) ──
  img.onmousedown = function(e) {
    e.preventDefault();
    const rect = img.getBoundingClientRect();
    _sigDrag = { ox: e.clientX - rect.left, oy: e.clientY - rect.top };
  };
  stage.onmousemove = function(e) {
    if (_sigDrag) {
      const sr   = stage.getBoundingClientRect();
      const imgW = img.offsetWidth, imgH = img.offsetHeight;
      let nx = e.clientX - sr.left - _sigDrag.ox;
      let ny = e.clientY - sr.top  - _sigDrag.oy;
      img.style.left = nx + 'px';
      img.style.top  = ny + 'px';
      updateResizeHandle();
    }
    if (_sigResz) {
      const sr  = stage.getBoundingClientRect();
      const il  = parseInt(img.style.left);
      const it  = parseInt(img.style.top);
      let nw = e.clientX - sr.left - il;
      let nh = e.clientY - sr.top  - it;
      nw = Math.max(60, nw);
      nh = Math.max(20, nh);
      img.style.width  = nw + 'px';
      img.style.height = nh + 'px';
      updateResizeHandle();
      updateSizeLabelSig();
    }
  };
  stage.onmouseup   = () => { _sigDrag = null; _sigResz = null; };
  stage.onmouseleave= () => { _sigDrag = null; _sigResz = null; };

  // ── Resize handle ──
  handle.onmousedown = function(e) {
    e.preventDefault();
    e.stopPropagation();
    _sigResz = true;
    _sigDrag = null;
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
      const imgW = img.offsetWidth, imgH = img.offsetHeight;
      let nx = t.clientX - sr.left - _sigDrag.ox;
      let ny = t.clientY - sr.top  - _sigDrag.oy;
         nw = Math.max(60, nw);
      nh = Math.max(20, nh);
      img.style.left = nx + 'px';
      img.style.top  = ny + 'px';
      updateResizeHandle();
    }
    if (_sigResz) {
      const il = parseInt(img.style.left), it = parseInt(img.style.top);
      let nw = t.clientX - sr.left - il;
      let nh = t.clientY - sr.top  - it;
      nw = Math.max(60, nw);
      nh = Math.max(20, nh);
      img.style.width  = nw + 'px';
      img.style.height = nh + 'px';
      updateResizeHandle();
      updateSizeLabelSig();
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
  const l = parseInt(img.style.left)  || 0;
  const t = parseInt(img.style.top)   || 0;
  const w = img.offsetWidth;
  const h = img.offsetHeight;
  handle.style.left = (l + w - 7)  + 'px';
  handle.style.top  = (t + h - 7)  + 'px';
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
  updateResizeHandle();
  updateSizeLabelSig();
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

// ══ GENERIC FILE PREVIEW HELPERS ══

/**
 * Handle file input change → show preview card, hide picker.
 * prefix: e.g. 'id-front', 'id-back', 'thumbmark'
 */
function handleFilePreview(input, prefix) {
  if (!input.files || !input.files[0]) return;
  const file  = input.files[0];
  const card  = document.getElementById(prefix + '-preview-card');
  const picker = document.getElementById(prefix + '-picker');

  const reader = new FileReader();
  reader.onload = function(e) {
    // Set image src
    const img = card.querySelector('img');
    img.src = e.target.result;
    img.onclick = function() { openLightbox(this.src); };

    // Set name / size labels if they exist
    const fnEl = document.getElementById(prefix + '-fname');
    const fsEl = document.getElementById(prefix + '-fsize');
    if (fnEl) fnEl.textContent = file.name.length > 24 ? file.name.substring(0,22)+'…' : file.name;
    if (fsEl) fsEl.textContent = (file.size / 1024).toFixed(1) + ' KB';

    // Show card, hide picker
    card.style.display   = 'flex';
    if (picker) picker.style.display = 'none';
  };
  reader.readAsDataURL(file);
}

/**
 * Remove preview card → show picker again, flag removal if existing file.
 * prefix: e.g. 'id-front', 'id-back', 'thumbmark'
 * removeFlagId: the hidden input id for remove flag (optional)
 */
function removeFilePreview(prefix, removeFlagId) {
  const card   = document.getElementById(prefix + '-preview-card');
  const picker = document.getElementById(prefix + '-picker');

  // Clear the file input inside picker so a fresh pick is possible
  if (picker) {
    const fi = picker.querySelector('input[type="file"]');
    if (fi) fi.value = '';
    picker.style.display = 'block';
  }

  // Reset image
  const img = card.querySelector('img');
  if (img) img.src = '';

  // Set remove flag
  if (removeFlagId) {
    const flag = document.getElementById(removeFlagId);
    if (flag) flag.value = '1';
  }

  card.style.display = 'none';
}

// ── Signature upload handler ──
function handleSigUpload(input) {
  if (!input.files || !input.files[0]) return;
  const file  = input.files[0];
  const card  = document.getElementById('sig-upload-preview-card');
  const picker = document.getElementById('sig-upload-picker');

  const reader = new FileReader();
  reader.onload = function(e) {
    let img = card.querySelector('img');
    if (!img) {
      img = document.createElement('img');
      card.prepend(img);
    }
    img.src = e.target.result;
    img.onclick = function() { openLightbox(this.src); };
    img.style.cssText = 'width:52px;height:52px;border-radius:7px;object-fit:cover;border:2px solid var(--green-mid);cursor:zoom-in;flex-shrink:0;';

    const fnEl = document.getElementById('sig-upload-fname');
    const fsEl = document.getElementById('sig-upload-fsize');
    if (fnEl) fnEl.textContent = file.name.length > 24 ? file.name.substring(0,22)+'…' : file.name;
    if (fsEl) fsEl.textContent = (file.size / 1024).toFixed(1) + ' KB';

    card.style.display   = 'flex';
    if (picker) picker.style.display = 'none';
  };
  reader.readAsDataURL(file);
}

function removeSigUploadPreview() {
  const card  = document.getElementById('sig-upload-preview-card');
  const picker = document.getElementById('sig-upload-picker');
  const flag  = document.getElementById('signature_thumbmark_remove');

  if (card)  { const img = card.querySelector('img'); if (img) img.src = ''; card.style.display = 'none'; }
  if (picker){ const fi = picker.querySelector('input[type="file"]'); if (fi) fi.value = ''; picker.style.display = 'block'; }
  if (flag)  flag.value = '1';
}

// ── Right Thumbmark source toggle ──
function setThumbSource(src) {
  document.getElementById('thumb-upload-option').style.display = src === 'upload' ? 'block' : 'none';
  document.getElementById('thumb-scan-option').style.display  = src === 'scan'   ? 'block' : 'none';
  document.getElementById('thumb-btn-upload').classList.toggle('active', src === 'upload');
  document.getElementById('thumb-btn-scan').classList.toggle('active', src === 'scan');
  // Clear scanned data when switching away
  if (src === 'upload') clearScannedThumbmark();
}

// ── ZKTeco Live 20R fingerprint scan ──
async function scanFingerprint() {
  const btn       = document.getElementById('thumb-scan-btn');
  const statusEl  = document.getElementById('thumb-scan-status-text');
  const preview   = document.getElementById('thumb-scan-preview');

  // Update UI to scanning state
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Scanning…';
  statusEl.textContent = 'Place your right thumb on the scanner…';

  try {
    // ZKTeco Live 20R exposes a local HTTP service on port 8888
    // The SDK broadcasts a small REST API via its Windows service (ZKFPServer)
    const response = await fetch('http://localhost:8888/mfs100/captureimage', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ quality: 60, timeout: 10000 })
    });

    if (!response.ok) throw new Error('Scanner service returned ' + response.status);

    const data = await response.json();

    // Expected response shape from ZKFPServer:
    // { ErrorCode: 0, BMPBase64: "...", Quality: 75, ... }
    if (data.ErrorCode !== 0) throw new Error(data.ErrorMsg || 'Scan failed (code ' + data.ErrorCode + ')');

    const base64Image = 'data:image/bmp;base64,' + data.BMPBase64;
    showScannedThumbmark(base64Image, data.Quality ?? '—');

  } catch (err) {
    console.error('Fingerprint scan error:', err);

    // Friendly error messages
    let msg = 'Scan failed. ';
    if (err.message.includes('Failed to fetch') || err.message.includes('NetworkError')) {
      msg += 'Cannot reach scanner service. Make sure ZKFPServer is running on this PC.';
    } else {
      msg += err.message;
    }
    statusEl.textContent = msg;
    document.getElementById('thumb-scan-status').style.borderColor = 'var(--orange-mid)';
    document.getElementById('thumb-scan-status').style.background  = 'var(--orange-bg)';
    document.querySelector('#thumb-scan-status i').style.color      = 'var(--orange-mid)';
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-fingerprint"></i> Scan Now';
  }
}

function showScannedThumbmark(base64, quality) {
  const img      = document.getElementById('thumb-scan-img');
  const qualEl   = document.getElementById('thumb-scan-quality');
  const preview  = document.getElementById('thumb-scan-preview');
  const statusEl = document.getElementById('thumb-scan-status-text');
  const statusBox= document.getElementById('thumb-scan-status');
  const hidden   = document.getElementById('right_thumbmark_scanned');

  img.src = base64;
  img.onclick = function() { openLightbox(this.src); };
  qualEl.textContent = 'Quality score: ' + quality;
  hidden.value = base64;
  preview.style.display = 'block';

  statusEl.textContent = 'Fingerprint captured successfully.';
  statusBox.style.borderColor = 'var(--green-mid)';
  statusBox.style.background  = 'var(--green-bg)';
  statusBox.querySelector('i').style.color = 'var(--green-mid)';
}

function clearScannedThumbmark() {
  document.getElementById('thumb-scan-img').src        = '';
  document.getElementById('right_thumbmark_scanned').value = '';
  document.getElementById('thumb-scan-preview').style.display = 'none';
  const statusEl  = document.getElementById('thumb-scan-status-text');
  const statusBox = document.getElementById('thumb-scan-status');
  if (statusEl)  statusEl.textContent = 'ZKTeco Live 20R connected. Click "Scan Now" to capture fingerprint.';
  if (statusBox) {
    statusBox.style.borderColor = 'var(--border)';
    statusBox.style.background  = 'var(--surface2)';
    statusBox.querySelector('i').style.color = 'var(--green-mid)';
  }
}

function attachDrawEvents(canvas, ctx, isModal) {
  let drawing = false;
  const fresh = canvas.cloneNode(true);
  canvas.parentNode.replaceChild(fresh, canvas);

  if (isModal) {
    sigModalCtx = fresh.getContext('2d');
    // Restore white fill after clone (cloneNode copies dimensions but not pixel data)
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
    // If crop mode, let the overlay canvas handle it
    if (isModal && sigCropMode) return;
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
    const p = pos(e);
    ctx.moveTo(p.x, p.y);
  });

  fresh.addEventListener('mousemove', (e) => {
    if (!drawing) return;
    if (isModal && sigCropMode) return;
    const p = pos(e);
    ctx.lineTo(p.x, p.y);
    ctx.stroke();
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
    if (isModal && sigCropMode) return;
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
    if (!drawing || (isModal && sigCropMode)) return;
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

// ── Lightbox ──
function openLightbox(src) {
    if (!src) return;
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
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