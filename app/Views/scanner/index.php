<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
:root {
  --green:      #3a6b1a;
  --green-dark: #2d5214;
  --green-bg:   #eef6e6;
  --blue:       #1d4ed8;
  --blue-bg:    #eff6ff;
  --amber:      #92400e;
  --amber-bg:   #fffbeb;
  --red:        #c0392b;
  --red-bg:     #fff1f2;
  --bg:         #f6f7f9;
  --surface:    #ffffff;
  --text-1:     #1f2937;
  --text-2:     #374151;
  --text-3:     #6b7280;
  --border:     #e5e7eb;
  --radius:     10px;
  --radius-sm:  6px;
}

* { box-sizing: border-box; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: var(--bg); color: var(--text-1); }

.page-wrap { max-width: 600px; margin: 0 auto; }

/* ── Page Header ── */
.page-header {
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:700; color:var(--text-1); margin:0; }

/* ── Main body panel ── */
.page-body {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius);
  padding:20px;
}

/* ── Mode toggle (Attendance / Distribution) ── */
.mode-toggle { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px; }
.mode-toggle input[type=radio] { display:none; }
.mode-card {
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:10px 12px; cursor:pointer; text-align:center;
}
.mode-card:hover { border-color:var(--green); background:var(--green-bg); }
.mode-card .mc-icon { font-size:1rem; margin-bottom:4px; color:var(--text-3); }
.mode-card .mc-title { font-size:.78rem; font-weight:700; color:var(--text-1); display:block; }
.mode-toggle input:checked + .mode-card { border-color:var(--green); background:var(--green-bg); }
.mode-toggle input:checked + .mode-card .mc-icon { color:var(--green-dark); }
.mode-toggle input:checked + .mode-card .mc-title { color:var(--green-dark); }

/* ── Scanner type toggle (Camera / Hardware) — hidden on mobile ── */
.scanner-type-toggle { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px; }
.scanner-type-toggle input[type=radio] { display:none; }
.stype-card {
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:8px 12px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; gap:7px;
  font-size:.72rem; font-weight:600; color:var(--text-2);
}
.stype-card:hover { border-color:var(--green); color:var(--green-dark); background:var(--green-bg); }
.scanner-type-toggle input:checked + .stype-card { border-color:var(--green); background:var(--green-bg); color:var(--green-dark); }

@media(max-width:640px) {
  .scanner-type-wrap { display:none !important; }
}

/* ── Mode description banner ── */
.mode-banner {
  display:flex; align-items:center; gap:8px;
  padding:9px 14px; border-radius:var(--radius-sm);
  font-size:.72rem; font-weight:600;
  margin-bottom:14px; border:1px solid;
}
.mode-banner.attendance   { background:var(--blue-bg); color:var(--blue); border-color:#bfdbfe; }
.mode-banner.distribution { background:var(--green-bg); color:var(--green-dark); border-color:#c8e0b5; }

/* ── Camera viewer ── */
.camera-wrap {
  border-radius:var(--radius); overflow:hidden;
  border:1px solid var(--border); background:#000;
}
.camera-wrap #reader { width:100%; min-height:340px; }
.camera-status {
  background:#1f2937; color:#d1d5db; padding:10px 16px;
  display:flex; align-items:center; gap:8px; font-size:.7rem; font-weight:600;
}
.scan-pulse { width:8px; height:8px; border-radius:50%; background:var(--green); flex-shrink:0; }

/* ── Hardware scanner panel ── */
.hw-panel { border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
.hw-panel-header {
  background:#1f2937; color:#f9fafb;
  padding:12px 16px; display:flex; align-items:center; gap:8px;
  font-size:.78rem; font-weight:700;
}
.hw-panel-body { padding:16px; }
.field-label { display:block; font-size:.7rem; font-weight:700; color:var(--text-2); margin-bottom:5px; }
.form-control {
  width:100%; border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:10px 12px; font-size:.82rem;
  color:var(--text-1); background:var(--surface); outline:none;
}
.form-control:focus { border-color:var(--green); }
.hw-status {
  display:flex; align-items:center; gap:8px;
  padding:9px 14px; border-radius:var(--radius-sm);
  font-size:.72rem; font-weight:600; margin-top:12px; border:1px solid;
}
.hw-status.ready       { background:var(--green-bg); color:var(--green-dark); border-color:#c8e0b5; }
.hw-status.processing  { background:var(--blue-bg); color:var(--blue); border-color:#bfdbfe; }
.hw-status.warning     { background:var(--amber-bg); color:var(--amber); border-color:#fde68a; }
.hw-status.error       { background:var(--red-bg); color:var(--red); border-color:#fecaca; }
.hw-status.idle        { background:var(--bg); color:var(--text-3); border-color:var(--border); }

.hw-result { margin-top:12px; }
.hw-result-inner {
  background:var(--blue-bg); border:1px solid #bfdbfe; border-radius:var(--radius-sm);
  padding:12px 14px; display:flex; align-items:center; gap:10px;
  font-size:.72rem; color:var(--blue);
}

.hw-panel-footer {
  padding:10px 16px; border-top:1px solid var(--border); background:var(--bg);
  display:flex; align-items:center; justify-content:flex-end;
}

/* ── Buttons ── */
.btn-primary-c {
  display:inline-flex; align-items:center; gap:5px;
  background:var(--green); color:#fff; border:none; border-radius:6px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer;
}
.btn-primary-c:hover { background:var(--green-dark); color:#fff; }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:6px;
  padding:5px 10px; font-size:.68rem; font-weight:600;
  text-decoration:none; cursor:pointer;
}
.btn-outline-c:hover { border-color:var(--green); color:var(--green-dark); background:var(--green-bg); }

.btn-advance-mode {
  flex:1; padding:8px 14px; border-radius:var(--radius-sm);
  border:1px solid var(--border); background:var(--surface);
  color:var(--text-3); font-weight:600; font-size:.8rem; cursor:pointer;
}
.btn-advance-mode.active { background:var(--green-bg); border-color:var(--green); color:var(--green-dark); }

#hardwareScanInput:focus { caret-color: transparent; }

/* ── Split Layout: Scanner (left) + Live List (right) ── */
.split-layout { display:flex; gap:16px; align-items:flex-start; width:100%; max-width:none; margin:0; }
.scanner-col {
  flex:0 1 380px; max-width:380px; margin:0;
  overflow:hidden; transition:flex-basis .25s ease, max-width .25s ease, opacity .2s ease;
}
.scanner-col.is-collapsed {
  flex-basis:0; max-width:0; min-width:0; opacity:0; pointer-events:none;
}
.list-panel.is-expanded { flex:1 1 100%; }

.scanner-toggle-btn {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; border:1.5px solid var(--border); border-radius:6px;
  padding:5px 10px; font-size:.68rem; font-weight:600; color:var(--text-2); cursor:pointer;
}
.scanner-toggle-btn:hover { border-color:var(--green); color:var(--green-dark); background:var(--green-bg); }

.list-panel {
  flex:1 1 640px; min-width:380px;
  background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
  position:sticky; top:16px;
  max-height:calc(100vh - 32px); display:flex; flex-direction:column;
}
.list-panel-header { padding:16px 18px 0; }
.list-panel-header h6 { font-size:.9rem; font-weight:700; margin:0 0 12px; color:var(--text-1); }

.list-stats {
  display:flex; align-items:center; flex-wrap:wrap; gap:6px 12px;
  font-size:.7rem; font-weight:700; color:var(--green-dark);
  background:var(--green-bg); border:1px solid var(--border); border-radius:var(--radius-sm);
  padding:7px 10px; margin-bottom:10px;
}
.list-stats .ls-sep { color:var(--border); font-weight:400; }

.list-toggle { display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-bottom:12px; }
.list-toggle input[type=radio] { display:none; }
.lt-card {
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:8px 10px; text-align:center; cursor:pointer;
  font-size:.72rem; font-weight:700; color:var(--text-2);
}
.lt-card:hover { border-color:var(--green); background:var(--green-bg); }
.list-toggle input:checked + .lt-card { border-color:var(--green); background:var(--green-bg); color:var(--green-dark); }

.list-panel-body { flex:1; overflow-y:auto; padding:0 18px 18px; }
.list-empty { text-align:center; color:var(--text-3); font-size:.78rem; padding:30px 10px; }

.list-item {
  display:flex; align-items:center; gap:10px;
  padding:10px 12px; border-radius:var(--radius-sm); border:1px solid var(--border);
  margin-bottom:8px; background:var(--bg);
}
.list-item .li-avatar {
  width:36px; height:36px; border-radius:50%; flex-shrink:0;
  background:var(--green);
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:.8rem; font-weight:700;
}
.list-item .li-body { flex:1; min-width:0; }
.list-item .li-name { font-size:.78rem; font-weight:700; color:var(--text-1); }
.list-item .li-sub  { font-size:.65rem; color:var(--text-3); margin-top:1px; }
.list-item .li-time { font-size:.62rem; color:var(--text-3); white-space:nowrap; flex-shrink:0; }
.list-item .li-badge {
  font-size:.6rem; font-weight:700; padding:2px 7px; border-radius:20px;
  background:var(--green-bg); color:var(--green-dark); margin-left:4px;
}

@media(max-width:900px) {
  .split-layout { flex-direction:column; }
  .list-panel { position:static; max-height:480px; width:100%; }
}

.li-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

/* ── Idle Screensaver Overlay ── */
.idle-overlay{position:fixed;inset:0;z-index:9999;background:#fff;display:none;overflow:hidden}
.idle-overlay.active{display:block}
.idle-overlay *{margin:0;padding:0;box-sizing:border-box}
.idle-overlay .progress-track{position:fixed;top:0;left:0;right:0;height:3px;background:var(--border);z-index:100}
.idle-overlay .progress-fill{height:100%;width:0%;background:linear-gradient(90deg,#16a34a,#ea580c);border-radius:0 2px 2px 0}
.idle-overlay .screen{position:fixed;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1}
.idle-overlay .screen-inner{max-width:400px;width:88%;text-align:center;opacity:0;transform:translateY(24px);transition:opacity .5s ease,transform .55s cubic-bezier(.34,1.56,.64,1);will-change:transform,opacity}
.idle-overlay .screen-inner.enter{opacity:1;transform:translateY(0)}
.idle-overlay .screen-inner > *{opacity:0;transform:translateY(10px);transition:opacity .35s ease,transform .4s cubic-bezier(.34,1.56,.64,1)}
.idle-overlay .screen-inner.enter > *{opacity:1;transform:translateY(0)}
.idle-overlay .screen-inner.enter .tag{transition-delay:50ms}
.idle-overlay .screen-inner.enter h1,.idle-overlay .screen-inner.enter h2,.idle-overlay .screen-inner.enter h3{transition-delay:120ms}
.idle-overlay .screen-inner.enter .sub,.idle-overlay .screen-inner.enter p{transition-delay:190ms}
.idle-overlay .screen-inner.enter .scanner-box,.idle-overlay .screen-inner.enter .spinner,.idle-overlay .screen-inner.enter .icon-wrap,.idle-overlay .screen-inner.enter .icn{transition-delay:260ms}
.idle-overlay .screen-inner.enter .info-list{transition-delay:260ms}
.idle-overlay .screen-inner.enter .info-row{transition-delay:260ms}
.idle-overlay .screen-inner.enter .info-row:nth-child(2){transition-delay:300ms}
.idle-overlay .screen-inner.enter .info-row:nth-child(3){transition-delay:340ms}
.idle-overlay .screen-inner.enter .info-row:nth-child(4){transition-delay:380ms}
.idle-overlay .screen-inner.enter .info-row:nth-child(5){transition-delay:420ms}
.idle-overlay .screen-inner.enter .info-row:nth-child(6){transition-delay:460ms}
.idle-overlay .screen-inner.enter .btn{transition-delay:500ms}
.idle-overlay .screen-inner.enter .fade-1{transition-delay:500ms}
.idle-overlay .screen-inner.enter .fade-2{transition-delay:580ms}
.idle-overlay h1{font-size:2rem;font-weight:700;letter-spacing:-.02em;margin-bottom:4px;color:#1c1917;font-family:Georgia,'Times New Roman',serif}
.idle-overlay h3{font-size:1.25rem;font-weight:600;letter-spacing:-.01em;margin-bottom:2px;color:#1c1917}
.idle-overlay p{font-size:.88rem;color:#78716c;line-height:1.5}
.idle-overlay .sub{font-size:.88rem;color:#78716c;line-height:1.5}
.idle-overlay .tag{font-size:.7rem;font-weight:600;letter-spacing:.06em;color:#16a34a;text-transform:uppercase;margin-bottom:10px}
.idle-overlay .tag.orange{color:#ea580c}
.idle-overlay .divider{height:1px;background:#e7e5e4;margin:18px 0}
.idle-overlay .scanner-box{width:160px;height:160px;border:2px solid #e7e5e4;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:20px auto;position:relative}
.idle-overlay .scanner-box .scan-line{position:absolute;left:14px;right:14px;height:2px;background:#16a34a;border-radius:2px;will-change:transform}
.idle-overlay .scanner-box .scan-line::after{content:'';position:absolute;top:-8px;left:50%;transform:translateX(-50%);width:6px;height:16px;background:radial-gradient(ellipse,#16a34a 20%,transparent 70%);border-radius:50%;opacity:.5}
.idle-overlay .scanner-box .corners span{position:absolute;width:10px;height:10px}
.idle-overlay .scanner-box .corners span:nth-child(1){top:-1px;left:-1px;border-top:2px solid #16a34a;border-left:2px solid #16a34a;border-radius:2px 0 0 0}
.idle-overlay .scanner-box .corners span:nth-child(2){top:-1px;right:-1px;border-top:2px solid #16a34a;border-right:2px solid #16a34a;border-radius:0 2px 0 0}
.idle-overlay .scanner-box .corners span:nth-child(3){bottom:-1px;left:-1px;border-bottom:2px solid #16a34a;border-right:none;border-left:2px solid #16a34a;border-radius:0 0 0 2px}
.idle-overlay .scanner-box .corners span:nth-child(4){bottom:-1px;right:-1px;border-bottom:2px solid #16a34a;border-left:none;border-right:2px solid #16a34a;border-radius:0 0 2px 0}
.idle-overlay .scanner-box .qr-glyph{width:56px;height:56px;position:relative}
.idle-overlay .scanner-box .qr-glyph i,.idle-overlay .scanner-box .qr-glyph b,.idle-overlay .scanner-box .qr-glyph em,.idle-overlay .scanner-box .qr-glyph strong{position:absolute;width:20px;height:20px;border:2px solid #a8a29e}
.idle-overlay .scanner-box .qr-glyph i{top:0;left:0;border-right:none;border-bottom:none;border-radius:3px 0 0 0}
.idle-overlay .scanner-box .qr-glyph b{top:0;right:0;border-left:none;border-bottom:none;border-radius:0 3px 0 0}
.idle-overlay .scanner-box .qr-glyph em{bottom:0;left:0;border-right:none;border-top:none;border-radius:0 0 0 3px}
.idle-overlay .scanner-box .qr-glyph strong{bottom:0;right:0;border-left:none;border-top:none;border-radius:0 0 3px 0}
.idle-overlay .spinner{width:26px;height:26px;border:2.5px solid #e7e5e4;border-top-color:#16a34a;border-radius:50%;margin:18px auto}
.idle-overlay .info-list{text-align:left;margin:16px 0}
.idle-overlay .info-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #e7e5e4}
.idle-overlay .info-row:last-child{border-bottom:none}
.idle-overlay .info-row .lbl{font-size:.78rem;color:#78716c}
.idle-overlay .info-row .val{font-size:.88rem;font-weight:550;color:#1c1917}
.idle-overlay .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 22px;border-radius:40px;border:none;font-size:.85rem;font-weight:550;cursor:pointer;transition:all .2s cubic-bezier(.34,1.56,.64,1)}
.idle-overlay .btn:active{transform:scale(.96)}
.idle-overlay .btn-primary{background:#16a34a;color:#fff;box-shadow:0 1px 2px rgba(22,163,74,.2)}
.idle-overlay .btn-outline{border:1.5px solid #e7e5e4;background:transparent;color:#78716c}
.idle-overlay .btn-amber{background:#ea580c;color:#fff;box-shadow:0 1px 2px rgba(234,88,12,.2)}
.idle-overlay .icon-wrap{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:14px auto;position:relative}
.idle-overlay .icon-wrap svg{width:22px;height:22px}
.idle-overlay .icon-wrap.green{background:#dcfce7}
.idle-overlay .icon-wrap.green svg *{stroke:#16a34a}
.idle-overlay .icon-wrap.orange{background:#fff7ed}
.idle-overlay .icon-wrap.orange svg *{stroke:#ea580c}
.idle-overlay .draw svg *{fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:80;stroke-dashoffset:80;transition:stroke-dashoffset .6s cubic-bezier(.34,1.56,.64,1) .15s}
.idle-overlay .screen-inner.enter .draw svg *{stroke-dashoffset:0}
.idle-overlay .draw-delay svg *{stroke-dasharray:100;stroke-dashoffset:100;transition:stroke-dashoffset .5s cubic-bezier(.34,1.56,.64,1) .1s}
.idle-overlay .screen-inner.enter .draw-delay svg *{stroke-dashoffset:0}
.idle-overlay .draw-x svg line{fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:18;stroke-dashoffset:18;transition:stroke-dashoffset .35s cubic-bezier(.34,1.56,.64,1) .08s}
.idle-overlay .screen-inner.enter .draw-x svg line{stroke-dashoffset:0}
.idle-overlay .draw-x svg line:nth-child(2){transition-delay:.14s}
.idle-overlay .bars{width:36px;height:28px;display:flex;align-items:flex-end;gap:4px;margin:14px auto}
.idle-overlay .bars span{width:5px;border-radius:3px 3px 0 0;background:#16a34a;height:6px;transition:height .4s cubic-bezier(.34,1.56,.64,1)}
.idle-overlay .bars span:nth-child(1){transition-delay:.1s;--h:6px}
.idle-overlay .bars span:nth-child(2){transition-delay:.2s;--h:18px}
.idle-overlay .bars span:nth-child(3){transition-delay:.3s;--h:26px}
.idle-overlay .bars span:nth-child(4){transition-delay:.4s;--h:12px}
.idle-overlay .screen-inner.enter .bars span{height:var(--h)}
@keyframes idleScan{0%{transform:translateY(0)}50%{transform:translateY(108px)}100%{transform:translateY(0)}}
@keyframes idleSpin{to{transform:rotate(360deg)}}
@keyframes idleBounce{0%,80%,100%{transform:translateY(0);opacity:.4}40%{transform:translateY(-10px);opacity:1}}
#demoProgress{transition:width .12s linear}
</style>

<div class="split-layout">
<div class="page-wrap scanner-col">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-qrcode me-2" style="color:var(--green)"></i>QR Code Scanner</h5>
    </div>
  </div>

  <!-- Main Body -->
  <div class="page-body">

  <!-- Scan Advance Mode: Manual / Auto -->
 <div class="advance-mode-toggle" style="display:flex; gap:8px; margin-bottom:14px;">
  <button type="button" class="btn-advance-mode active" id="manualAdvanceBtn" onclick="setAdvanceMode('manual')">
    <i class="fa-solid fa-hand-pointer me-1"></i> Manual
  </button>
  <button type="button" class="btn-advance-mode" id="autoAdvanceBtn" onclick="setAdvanceMode('auto')">
    <i class="fa-solid fa-bolt me-1"></i> Auto
  </button>
 </div>

  <!-- Active Event Selector -->
    <div class="event-select-wrap" style="margin-bottom:14px;">
      <label class="field-label" for="eventSelect">Recording For Event <span class="text-danger">*</span></label>
      <select id="eventSelect" class="form-control">
        <option value="">-- Select Active Event --</option>
        <?php foreach ($active_events as $ev): ?>
          <option value="<?= $ev['id'] ?>"><?= esc($ev['event_name']) ?><?= !empty($ev['evacuation_center']) ? ' - ' . esc($ev['evacuation_center']) : '' ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (empty($active_events)): ?>
        <p style="font-size:.65rem;color:#c0392b;margin-top:5px">
          <i class="fa-solid fa-triangle-exclamation me-1"></i> No active events. Please ask an administrator to activate one.
        </p>
      <?php endif; ?>
    </div>

    <!-- Scan Mode: Attendance / Distribution -->
    <div class="mode-toggle">
      <input type="radio" name="scanMode" id="attendanceMode" value="attendance" autocomplete="off" checked>
      <label class="mode-card" for="attendanceMode">
        <div class="mc-icon"><i class="fa-solid fa-calendar-check"></i></div>
        <span class="mc-title">Attendance</span>
      </label>

      <input type="radio" name="scanMode" id="distributionMode" value="distribution" autocomplete="off">
      <label class="mode-card" for="distributionMode">
        <div class="mc-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
        <span class="mc-title">Distribution</span>
      </label>
    </div>

    <!-- Scanner Type Toggle — hidden on mobile -->
    <div class="scanner-type-wrap">
      <div class="scanner-type-toggle">
        <input type="radio" name="scannerType" id="cameraScanner" value="camera" autocomplete="off" checked>
        <label class="stype-card" for="cameraScanner">
          <i class="fa-solid fa-camera"></i> Camera Scanner
        </label>

        <input type="radio" name="scannerType" id="hardwareScanner" value="hardware" autocomplete="off">
        <label class="stype-card" for="hardwareScanner">
          <i class="fa-solid fa-microchip"></i> Hardware Scanner
        </label>
      </div>
    </div>

    <!-- Mode Description Banner -->
    <div id="modeBanner" class="mode-banner attendance">
      <i class="fa-solid fa-circle-info"></i>
      <span id="modeText">Attendance Mode</span>
    </div>

    <!-- Camera Scanner Section -->
    <div id="cameraSection">
      <div class="camera-wrap">
        <div id="reader" style="width:100%; min-height:340px; background:#000;"></div>
        <div class="camera-status">
          <span class="scan-pulse"></span>
          Camera Active
        </div>
      </div>
    </div>

    <!-- Hardware Scanner Section (Desktop only, hidden on mobile via CSS) -->
    <div id="hardwareSection" style="display:none">
      <div class="hw-panel">
        <div class="hw-panel-header">
          <i class="fa-solid fa-microchip"></i> Hardware Scanner Mode
        </div>
        <div class="hw-panel-body">
          <label class="field-label">Scan Input Field</label>
          <input type="text" class="form-control" id="hardwareScanInput"
                 placeholder="Scanner input will appear here..." readonly>
          <div id="hardwareStatus" class="hw-status idle">
            <i class="fa-solid fa-circle-dot"></i>
            <span>Ready — click the field above and scan</span>
          </div>
          <div id="hardwareResult" class="hw-result" style="display:none"></div>
        </div>
        <div class="hw-panel-footer">
          <button class="btn-outline-c" onclick="resetHardwareScanner()">
            <i class="fa-solid fa-rotate-right"></i> Reset
          </button>
        </div>
      </div>
    </div>

</div><!-- /page-body -->

</div><!-- /page-wrap -->

<!-- Live List Panel -->
<div class="list-panel">
<div class="list-panel-header">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
      <h6 style="margin:0;"><i class="fa-solid fa-list-check me-2" style="color:var(--green)"></i>Live Activity</h6>
      <button type="button" class="scanner-toggle-btn" id="scannerToggleBtn" onclick="toggleScannerPanel()">
        <i class="fa-solid fa-eye-slash"></i> Hide Scanner
      </button>
    </div>
    <div class="list-toggle">
      <input type="radio" name="listMode" id="listAttendance" value="attendance" checked>
      <label class="lt-card" for="listAttendance"><i class="fa-solid fa-calendar-check me-1"></i> Attendance</label>

      <input type="radio" name="listMode" id="listDistribution" value="distribution">
      <label class="lt-card" for="listDistribution"><i class="fa-solid fa-hand-holding-heart me-1"></i> Distribution</label>
    </div>
    <div class="list-stats" id="listStats"></div>
  </div>
  <div class="list-panel-body" id="listPanelBody">
    <div class="list-empty">Select an event to see activity.</div>
  </div>
</div>
<!-- /list-panel -->

</div><!-- /split-layout -->

<!-- ═══════ Idle Screensaver Overlay ═══════ -->
<div class="idle-overlay" id="idleOverlay">
<div class="progress-track"><div class="progress-fill" id="demoProgress"></div></div>

<!-- 0: intro video -->
<div id="demoScreen0" class="screen" style="background:#fff">
  <div class="screen-inner" id="demoInner0" style="max-width:100%;width:100%;padding:0">
    <video id="demoIntroVideo" muted playsinline preload="auto" style="width:100vw;height:100vh;object-fit:cover;display:block">
      <source src="/uploads/intro.mp4" type="video/mp4">
    </video>
  </div>
</div>

<!-- 1: scanner -->
<div id="demoScreen1" class="screen">
  <div class="screen-inner" id="demoInner1">
    <div class="tag">Scanner</div>
    <h3>Scan QR Code</h3>
    <p class="sub">Place QR in frame</p>
    <div class="scanner-box">
      <div class="scan-line" style="animation:idleScan 2s ease-in-out infinite"></div>
      <div class="corners"><span></span><span></span><span></span><span></span></div>
      <div class="qr-glyph"><i></i><b></b><em></em><strong></strong></div>
    </div>
    <div class="btn btn-outline" style="opacity:.6;cursor:default">Scanning&hellip;</div>
  </div>
</div>

<!-- 2: validating -->
<div id="demoScreen2" class="screen">
  <div class="screen-inner" id="demoInner2">
    <div class="tag orange">Validating</div>
    <div style="margin:20px auto;position:relative;width:48px;height:48px">
      <div style="position:absolute;inset:0;border:3px solid #e7e5e4;border-top-color:#ea580c;border-radius:50%;animation:idleSpin .8s linear infinite"></div>
      <div style="position:absolute;inset:6px;border:2px solid #e7e5e4;border-bottom-color:#16a34a;border-radius:50%;animation:idleSpin 1.2s linear infinite reverse"></div>
    </div>
    <h3>Checking Database</h3>
    <p>Verifying QR code in registry</p>
    <div style="display:flex;gap:6px;justify-content:center;margin-top:14px">
      <div style="width:6px;height:6px;border-radius:50%;background:#16a34a;animation:idleBounce 1.2s ease-in-out infinite"></div>
      <div style="width:6px;height:6px;border-radius:50%;background:#ea580c;animation:idleBounce 1.2s ease-in-out .2s infinite"></div>
      <div style="width:6px;height:6px;border-radius:50%;background:#16a34a;animation:idleBounce 1.2s ease-in-out .4s infinite"></div>
    </div>
  </div>
</div>

<!-- 3: beneficiary -->
<div id="demoScreen3" class="screen">
  <div class="screen-inner" id="demoInner3">
    <div class="tag">Claimed</div>
    <div class="icon-wrap green draw" style="width:56px;height:56px;border-radius:16px">
      <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <h3>Beneficiary</h3>
    <div class="info-list">
      <div class="info-row"><span class="lbl">Name</span><span class="val">Maria C. Santos</span></div>
      <div class="info-row"><span class="lbl">Household</span><span class="val">HH-2024-1842</span></div>
      <div class="info-row"><span class="lbl">Barangay</span><span class="val">San Isidro</span></div>
      <div class="info-row"><span class="lbl">Contact</span><span class="val">09XX-XXX-1842</span></div>
      <div class="info-row"><span class="lbl">Claimed</span><span class="val" id="demoDateDisplay"></span></div>
      <div class="info-row"><span class="lbl">Food Pack</span><span class="val">Family Pack A</span></div>
    </div>
    <div class="btn btn-primary"><svg width="14" height="14" viewBox="0 0 24 24" style="vertical-align:middle"><path d="M20 6L9 17l-5-5" stroke="#fff" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg> Verified &mdash; Proceed</div>
  </div>
</div>

<!-- 4: distribution -->
<div id="demoScreen4" class="screen">
  <div class="screen-inner" id="demoInner4">
    <div class="tag">Distribution</div>
    <div class="icon-wrap green draw-delay" style="width:56px;height:56px;border-radius:16px">
      <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V14H8v7"/></svg>
    </div>
    <h3>Distribute Food Pack</h3>
    <p class="sub" style="margin-top:2px">Family Pack A &rarr; Maria C. Santos</p>
    <div style="margin-top:14px">
      <div class="btn btn-primary"><svg width="14" height="14" viewBox="0 0 24 24" style="vertical-align:middle"><path d="M20 6L9 17l-5-5" stroke="#fff" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg> Distribute</div>
    </div>
  </div>
</div>

<!-- 5: recording -->
<div id="demoScreen5" class="screen">
  <div class="screen-inner" id="demoInner5">
    <div class="tag">Recording</div>
    <div class="bars icn">
      <span></span><span></span><span></span><span></span>
    </div>
    <h3 style="color:#16a34a">Saving Transaction</h3>
    <p>Updating monitoring dashboard</p>
    <div style="margin-top:14px;font-size:.78rem;color:#78716c;line-height:1.8">
      <div class="fade-1"><span style="color:#16a34a">&#10003;</span> Transaction recorded</div>
      <div class="fade-2"><span style="color:#16a34a">&#10003;</span> Dashboard updated</div>
    </div>
  </div>
</div>

<!-- 6: already claimed -->
<div id="demoScreen6" class="screen">
  <div class="screen-inner" id="demoInner6">
    <div class="tag orange">Duplicate</div>
    <div class="icon-wrap orange draw-x" style="width:56px;height:56px;border-radius:16px">
      <svg viewBox="0 0 24 24"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
    </div>
    <h3>Already Claimed</h3>
    <p>Maria C. Santos &mdash; HH-2024-1842</p>
    <div style="margin-top:10px;padding:10px 14px;background:#fff7ed;border-radius:10px;font-size:.8rem;color:#ea580c">
      Previous claim detected. No additional distribution.
    </div>
  </div>
</div>

<!-- 7: guest -->
<div id="demoScreen7" class="screen">
  <div class="screen-inner" id="demoInner7">
    <div class="tag orange">Guest</div>
    <div class="icon-wrap orange draw-delay" style="width:56px;height:56px;border-radius:16px">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 22c0-6 4-9 8-9s8 3 8 9"/></svg>
    </div>
    <h3>Guest Distribution</h3>
    <p class="sub">Not registered? Use Guest QR</p>
    <div class="info-list">
      <div class="info-row"><span class="lbl">Temp ID</span><span class="val">Guest #4</span></div>
      <div class="info-row"><span class="lbl">Status</span><span class="val" style="color:#16a34a">Active</span></div>
    </div>
    <div class="btn btn-amber">Record as Guest</div>
  </div>
</div>
</div><!-- /idleOverlay -->

<!-- QR Scan Result Modal -->
<div class="modal fade" id="scanResultModal" tabindex="-1" aria-labelledby="scanResultModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanResultModalLabel">
          <i class="fa-solid fa-circle-check text-success me-2"></i>
          Scan Result
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="scanResultModalBody">
        <!-- Content will be loaded dynamically -->
        <div class="text-center py-4">
          <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Processing scan result...</p>
        </div>
      </div>
<div class="modal-footer" id="scanResultModalFooter">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-1"></i>Close
        </button>
        <button type="button" class="btn btn-success" id="scanAnotherBtn">
          <i class="fa-solid fa-qrcode me-1"></i>Scan Another
        </button>
      </div>
    </div>
  </div>
</div>


<!-- QR Library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner;
let currentMode = 'attendance';
let currentScannerType = 'camera';
let isModalOpen = false; // Track modal state

let hardwareBuffer = '';
let hardwareTimeout = null;
let hardwareActive = false;

// ── Detect mobile: hide hardware toggle & force camera ──
const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent) || window.innerWidth <= 640;

// ==================== CAMERA SCANNER ====================
function onScanSuccess(decodedText) {
    const eventId = getSelectedEventId();
    if (!eventId) {
        alert('Please select an event before scanning.');
        return;
    }
    
    // Pause scanner
    if (html5QrcodeScanner) {
        try {
            html5QrcodeScanner.pause();
        } catch (e) {
            console.log('Error pausing scanner:', e);
        }
    }
    
    const mode = document.querySelector('input[name="scanMode"]:checked').value;
    
    // Show modal with loading state
    showScanResultModal(decodedText, mode);
}

function initCameraScanner() {
    if (html5QrcodeScanner) {
        try {
            html5QrcodeScanner.clear();
        } catch (e) {
            console.log('Error clearing scanner:', e);
        }
    }
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 }, 
            rememberLastUsedCamera: true,
            showTorchButtonIfSupported: true // Add torch button if needed
        },
        false
    );
    html5QrcodeScanner.render(onScanSuccess);
}

// ==================== HARDWARE SCANNER ====================
function initHardwareScanner() {
    const input = document.getElementById('hardwareScanInput');
    hardwareActive = true;
    hardwareBuffer = '';
    input.value = '';
    input.readOnly = false;
    input.focus();
    updateHardwareStatus('ready', 'Hardware scanner active — Scan a QR code now');
    if (hardwareTimeout) clearTimeout(hardwareTimeout);
    // Timeout removed - no more "No scan detected" warning
}

function stopHardwareScanner() {
    hardwareActive = false;
    const input = document.getElementById('hardwareScanInput');
    if (input) input.readOnly = true;
    if (hardwareTimeout) { 
        clearTimeout(hardwareTimeout); 
        hardwareTimeout = null; 
    }
}

function resetHardwareScanner() {
    stopHardwareScanner();
    document.getElementById('hardwareResult').style.display = 'none';
    document.getElementById('hardwareResult').innerHTML = '';
    if (currentScannerType === 'hardware' && !isModalOpen) setTimeout(() => initHardwareScanner(), 200);
}

function updateHardwareStatus(type, message) {
    const el = document.getElementById('hardwareStatus');
    const icons = {
        ready:      ['fa-circle-dot',          'ready'],
        processing: ['fa-spinner fa-spin',     'processing'],
        warning:    ['fa-triangle-exclamation','warning'],
        error:      ['fa-circle-exclamation',  'error'],
        idle:       ['fa-circle-dot',          'idle'],
    };
    const [icon, cls] = icons[type] || icons.idle;
    el.className = 'hw-status ' + cls;
    el.innerHTML = `<i class="fa-solid ${icon}"></i><span>${message}</span>`;
}

function processHardwareScan(scanData) {
    if (!hardwareActive) return;
    
    const eventId = getSelectedEventId();
    if (!eventId) {
        updateHardwareStatus('warning', 'Please select an event before scanning.');
        return;
    }
    
    stopHardwareScanner();
    updateHardwareStatus('processing', 'Validating QR code...');
    
    const mode = document.querySelector('input[name="scanMode"]:checked').value;
    showHardwareResult(scanData, mode);
    
    // Show modal with loading state
    showScanResultModal(scanData.trim(), mode);
}

function showHardwareResult(scanData, mode) {
    const el = document.getElementById('hardwareResult');
    const modeName = mode === 'attendance' ? 'Attendance' : 'Distribution';
    el.innerHTML = `
        <div class="hw-result-inner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>
                <strong>Processing ${modeName} Scan…</strong><br>
                <span style="font-size:.65rem;opacity:.7">QR: ${scanData.substring(0,30)}${scanData.length>30?'…':''}</span>
            </div>
        </div>`;
    el.style.display = 'block';
}

// ==================== TOGGLE FUNCTIONS ====================
function switchToCamera() {
    currentScannerType = 'camera';
    document.getElementById('cameraSection').style.display = 'block';
    document.getElementById('hardwareSection').style.display = 'none';
    stopHardwareScanner();
    if (!isModalOpen) setTimeout(() => initCameraScanner(), 100);
}

function switchToHardware() {
    currentScannerType = 'hardware';
    document.getElementById('cameraSection').style.display = 'none';
    document.getElementById('hardwareSection').style.display = 'block';
    if (html5QrcodeScanner) {
        try {
            html5QrcodeScanner.clear();
        } catch (e) {}
    }
    if (!isModalOpen) setTimeout(() => initHardwareScanner(), 100);
}

// ==================== MODE BANNER ====================
function updateModeBanner(mode) {
    const banner = document.getElementById('modeBanner');
    const text   = document.getElementById('modeText');
    if (mode === 'attendance') {
        banner.className = 'mode-banner attendance';
        text.textContent = 'Attendance Mode';
    } else {
        banner.className = 'mode-banner distribution';
        text.textContent = 'Distribution Mode';
    }
}

// ==================== EVENTS ====================
document.querySelectorAll('input[name="scannerType"]').forEach(r => {
    r.addEventListener('change', function() {
        saveScannerType(this.value);
        this.value === 'camera' ? switchToCamera() : switchToHardware();
    });
});

document.querySelectorAll('input[name="scanMode"]').forEach(r => {
    r.addEventListener('change', function() {
        currentMode = this.value;
        updateModeBanner(currentMode);
        saveSelectedMode(currentMode);
    });
});

document.getElementById('hardwareScanInput').addEventListener('input', function() {
    if (!hardwareActive) return;
    if (hardwareTimeout) clearTimeout(hardwareTimeout);
    hardwareBuffer = this.value;
    hardwareTimeout = setTimeout(() => {
        if (hardwareActive && this.value.trim()) processHardwareScan(this.value.trim());
    }, 500);
});

document.getElementById('hardwareScanInput').addEventListener('keydown', function(e) {
    if (!hardwareActive) return;
    if (e.key === 'Enter') {
        e.preventDefault();
        const data = hardwareBuffer || this.value;
        if (data.trim()) processHardwareScan(data.trim());
    }
});

// ==================== STATS ====================
function loadStats() {
    fetch('/attendance/stats')
        .then(r => r.json())
        .then(d => { document.getElementById('todayAttendance').textContent = d.today ?? 0; })
        .catch(() => {});
    fetch('/distribution/today-stats')
        .then(r => r.json())
        .then(d => { document.getElementById('todayDistribution').textContent = d.today ?? 0; })
        .catch(() => {});
}

// ==================== PERSISTENCE ====================
function saveSelectedMode(mode)  { localStorage.setItem('preferredScanMode', mode); }
function loadSavedMode()         { return localStorage.getItem('preferredScanMode'); }
function saveScannerType(type)   { localStorage.setItem('preferredScannerType', type); }
function loadSavedScannerType()  { return localStorage.getItem('preferredScannerType'); }

function applySavedMode() {
    const saved = loadSavedMode();
    if (saved) {
        const radio = document.querySelector(`input[name="scanMode"][value="${saved}"]`);
        if (radio) { radio.checked = true; currentMode = saved; updateModeBanner(saved); }
    }
}

function applySavedScannerType() {
    // On mobile, always force camera regardless of saved preference
    if (isMobile) { switchToCamera(); return; }
    const saved = loadSavedScannerType();
    if (saved) {
        const radio = document.querySelector(`input[name="scannerType"][value="${saved}"]`);
        if (radio) {
            radio.checked = true;
            saved === 'camera' ? switchToCamera() : switchToHardware();
            return;
        }
    }
    switchToCamera();
}

// ==================== MODAL FUNCTIONS ====================
function resumeScanning() {
    console.log('Resuming scanner... Current type:', currentScannerType);
    
    if (currentScannerType === 'camera') {
        if (html5QrcodeScanner) {
            try {
                html5QrcodeScanner.clear();
                setTimeout(() => {
                    initCameraScanner();
                }, 100);
            } catch (e) {
                console.log('Error with scanner, reinitializing:', e);
                setTimeout(() => initCameraScanner(), 300);
            }
        } else {
            setTimeout(() => initCameraScanner(), 300);
        }
    } else if (currentScannerType === 'hardware') {
        setTimeout(() => {
            initHardwareScanner();
            maintainHardwareFocus(); // Add this line
        }, 300);
    }
}

function showScanResultModal(qrToken, mode) {
    isModalOpen = true;
    
    // Show modal with loading
    const modalElement = document.getElementById('scanResultModal');
    const modal = new bootstrap.Modal(modalElement);
    
    document.getElementById('scanResultModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Processing ${mode} scan...</p>
            <small class="text-muted">QR: ${qrToken.substring(0,30)}${qrToken.length>30?'…':''}</small>
        </div>
    `;
    
    modal.show();

const eventId = getSelectedEventId();

url = mode === 'attendance' 
    ? `/attendance/process-scan?qr_token=${encodeURIComponent(qrToken)}&event_id=${encodeURIComponent(eventId)}`
    : `/distribution/process-scan-view?qr_token=${encodeURIComponent(qrToken)}&event_id=${encodeURIComponent(eventId)}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
.then(data => {
        // Update modal with result
        document.getElementById('scanResultModalBody').innerHTML = generateResultHTML(data, mode);
        
        // Refresh stats after successful scan
// Refresh stats after successful scan
        if (data.status === 'success') {
            loadStats();
            loadList();
        }
// AUTO MODE: hide footer buttons, auto-close after 2s, no click needed
        clearTimeout(autoAdvanceTimer);
        const footerEl = document.getElementById('scanResultModalFooter');
        if (scanAdvanceMode === 'auto') {
            footerEl.style.display = 'none';
            autoAdvanceTimer = setTimeout(() => {
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('scanResultModal'));
                if (modalInstance) modalInstance.hide();
            }, 2000);
        } else {
            footerEl.style.display = '';
        }
    })
    .catch(error => {
        document.getElementById('scanResultModalBody').innerHTML = `
            <div class="text-center py-4">
                <i class="fa-solid fa-circle-exclamation text-danger fa-4x mb-3"></i>
                <h4 class="text-danger">Error</h4>
                <p>Failed to process scan. Please try again.</p>
                <small class="text-muted">${error.message}</small>
            </div>
        `;
    });
}

function generateResultHTML(data, mode) {
    if (mode === 'attendance') {
        // ATTENDANCE MODE RESULTS WITH PHOTO
        if (data.status === 'success' || data.status === 'already_checked_in') {
            // Get details from the response
            const resident = data.resident_details || {}; // Head of family info
            const person = data.person_details || {}; // The actual person scanned (head or family member)
            const familyMembers = data.family_members || [];
            const isFamilyMember = person.is_family_member || false;
            
            // Determine check-in time
            const checkInTime = data.check_in_time || 
                (data.data?.check_in_time || new Date().toLocaleTimeString());
            
            // Generate photo HTML for the person scanned
            let photoHTML = '';
            const personPhoto = person.photo || resident.photo;
            
            if (personPhoto) {
                // Remove leading slash if present
                let photoPath = personPhoto;
                if (photoPath.startsWith('/')) {
                    photoPath = photoPath.substring(1);
                }
                
                photoHTML = `
                    <div class="text-center mb-3">
                        <a href="/${photoPath}" target="_blank">
                            <img src="/${photoPath}" 
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid ${data.status === 'success' ? 'var(--green-mid)' : 'var(--orange-mid)'}; box-shadow: 0 4px 12px rgba(0,0,0,0.1); cursor: pointer;"
                                 title="Click to view full size"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#77BC3F,#4a7a26);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem;font-weight:700;margin:0 auto;\'>${person.name ? person.name.charAt(0).toUpperCase() : '?'}</div>'">
                        </a>
                    </div>
                `;
            } else {
                photoHTML = `
                    <div class="text-center mb-3">
                        <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#77BC3F,#4a7a26);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem;font-weight:700;margin:0 auto;">
                            ${person.name ? person.name.charAt(0).toUpperCase() : '?'}
                        </div>
                    </div>
                `;
            }
            
            // Generate status icon and message
            const statusIcon = data.status === 'success' ? 'fa-circle-check text-success' : 'fa-circle-info text-info';
            const statusTitle = data.status === 'success' ? 'Attendance Confirmed' : 'Already Checked In';
            
            // Person type badge
            const personTypeBadge = isFamilyMember ? 
                '<span class="badge bg-info ms-2">Family Member</span>' : 
                '<span class="badge bg-primary ms-2">Head of Family</span>';
            
            // Check if household is 4Ps beneficiary (from resident data - this is the key!)
            const isHousehold4Ps = resident.is_4ps_beneficiary || false;
            
            // Get IP Ethnicity - first check person, then fallback to resident
            const ipEthnicity = (person.ip_ethnicity && person.ip_ethnicity !== 'N/A' && person.ip_ethnicity !== '') 
                ? person.ip_ethnicity 
                : (resident.ip_ethnicity && resident.ip_ethnicity !== 'N/A' && resident.ip_ethnicity !== '') 
                    ? resident.ip_ethnicity 
                    : null;
            
            return `
                <div class="text-center mb-3">
                    <i class="fa-solid ${statusIcon} fa-4x"></i>
                    <h4 class="fw-bold ${data.status === 'success' ? 'text-success' : 'text-info'} mt-2">
                        ${statusTitle}
                        ${personTypeBadge}
                    </h4>
                    <p class="text-muted">${data.message || ''}</p>
                </div>
                
                ${photoHTML}
                
                <div class="bg-light p-3 rounded">
                    <div class="row">
                        <!-- Person's Name (the actual person scanned) -->
                        <div class="col-12 mb-2">
                            <small class="text-muted d-block">Name</small>
                            <span class="fw-bold fs-6">${person.name || 'N/A'} ${person.name_extension || ''}</span>
                            ${isFamilyMember ? `<div><small class="text-muted">Relation: ${person.relation || 'Family Member'}</small></div>` : ''}
                        </div>
                        
                        <!-- Household Info (from head of family) -->
                        <div class="col-6">
                            <small class="text-muted d-block">Household No.</small>
                            <span class="fw-bold">${resident.household_no || 'N/A'}</span>
                        </div>
                        
                        <div class="col-6">
                            <small class="text-muted d-block">Barangay</small>
                            <span class="fw-bold">${resident.barangay || 'N/A'}</span>
                        </div>
                        
                        <!-- Personal Info -->
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Age / Sex</small>
                            <span class="fw-bold">${person.age || '?'} / ${person.sex || 'N/A'}</span>
                        </div>
                        
                        ${!isFamilyMember ? `
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Civil Status</small>
                            <span class="fw-bold">${person.civil_status || 'N/A'}</span>
                        </div>
                        ` : ''}
                        
                        ${!isFamilyMember && person.contact_number ? `
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Contact #</small>
                            <span class="fw-bold">${person.contact_number}</span>
                        </div>
                        ` : ''}
                        
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Check-in Time</small>
                            <span class="fw-bold">${checkInTime}</span>
                        </div>
                        
                        ${person.birthdate ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Birthdate</small>
                            <span class="fw-bold">${person.birthdate}</span>
                        </div>
                        ` : ''}
                        
                        <!-- Mother's Maiden Name (Head only) -->
                        ${!isFamilyMember && person.mother_maiden_name ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Mother's Maiden Name</small>
                            <span class="fw-bold">${person.mother_maiden_name}</span>
                        </div>
                        ` : ''}
                        
                        <!-- Religion (Head only) -->
                        ${!isFamilyMember && person.religion ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Religion</small>
                            <span class="fw-bold">${person.religion}</span>
                        </div>
                        ` : ''}
                        
                        <!-- Birthplace (Head only) -->
                        ${!isFamilyMember && person.birthplace ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Birthplace</small>
                            <span class="fw-bold">${person.birthplace}</span>
                        </div>
                        ` : ''}
                        
                        <!-- Monthly Income (Head only) -->
                        ${!isFamilyMember && person.monthly_income ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Monthly Income</small>
                            <span class="fw-bold">₱${Number(person.monthly_income).toLocaleString()}</span>
                        </div>
                        ` : ''}
                        
                        <!-- ID Card Info (Head only) -->
                        ${!isFamilyMember && person.id_card_presented ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">ID Presented</small>
                            <span class="fw-bold">${person.id_card_presented} ${person.id_card_number ? `(${person.id_card_number})` : ''}</span>
                        </div>
                        ` : ''}
                        
                        <!-- 4Ps Beneficiary - Show for BOTH Head and Family Members (from household data) -->
                        ${isHousehold4Ps ? `
                        <div class="col-12 mt-2">
                            <div style="background-color: #d1e7dd; border-left: 4px solid #0f5132; padding: 8px 12px; border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-hand-holding-heart" style="color: #0f5132;"></i>
                                    <div>
                                        <span style="font-weight: 700; color: #0f5132;">4Ps Beneficiary</span>
                                        <p style="margin: 0; font-size: 0.75rem; color: #0f5132;">Yes, this family is a 4Ps beneficiary</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                        
                        <!-- IP Ethnicity - Show for BOTH Head and Family Members (using fallback) -->
                        ${ipEthnicity ? `
                        <div class="col-12 mt-2">
                            <div style="background-color: #fff3cd; border-left: 4px solid #856404; padding: 8px 12px; border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-users" style="color: #856404;"></i>
                                    <div>
                                        <span style="font-weight: 700; color: #856404;">IP Type / Ethnicity</span>
                                        <p style="margin: 0; font-size: 0.75rem; color: #856404;">${ipEthnicity}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                        
                        <!-- Vulnerable Information (Head only) -->
                        ${!isFamilyMember && (person.vulnerable_older_persons || person.vulnerable_pregnant || person.vulnerable_lactating || person.vulnerable_pwd) ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Vulnerable Members</small>
                            <div class="mt-1">
                                ${person.vulnerable_older_persons ? `<span class="badge bg-info me-1">Older: ${person.vulnerable_older_persons}</span>` : ''}
                                ${person.vulnerable_pregnant ? `<span class="badge bg-info me-1">Pregnant: ${person.vulnerable_pregnant}</span>` : ''}
                                ${person.vulnerable_lactating ? `<span class="badge bg-info me-1">Lactating: ${person.vulnerable_lactating}</span>` : ''}
                                ${person.vulnerable_pwd ? `<span class="badge bg-info me-1">PWD: ${person.vulnerable_pwd}</span>` : ''}
                            </div>
                        </div>
                        ` : ''}
                        
                        <!-- Address (Head only) -->
                        ${!isFamilyMember && (person.house_no || person.street || person.subdivision || person.permanent_barangay) ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Address</small>
                            <span class="fw-bold">
                                ${[person.house_no, person.street, person.subdivision].filter(Boolean).join(', ')}
                                ${person.permanent_barangay ? (person.permanent_barangay !== resident.barangay ? `, ${person.permanent_barangay}` : '') : ''}
                                ${person.permanent_city ? `, ${person.permanent_city}` : ''}
                            </span>
                        </div>
                        ` : ''}
                        
                        <!-- Ownership Status (Head only) -->
                        ${!isFamilyMember && person.ownership_status ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Ownership Status</small>
                            <span class="fw-bold">${person.ownership_status}</span>
                        </div>
                        ` : ''}
                        
                        ${data.scanned_by_name ? `
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Scanned By</small>
                            <span class="fw-bold">${data.scanned_by_name}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
${familyMembers.length > 0 ? `
<div class="mt-4">
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-users me-2"></i>Household Members (${familyMembers.length})</h6>
    <div class="row g-2">
        ${familyMembers.map(member => {
            // Highlight the scanned person
            const isScannedPerson = member.is_scanned_person || false;
            const isHead = member.is_head || false;
            const memberInitial = member.name ? member.name.charAt(0).toUpperCase() : '?';
            
            // Check-in status badge
            const memberCheckedIn = member.checked_in_today ? 
                '<span class="badge bg-success ms-2" style="font-size:0.6rem;">Checked In</span>' : 
                '<span class="badge bg-secondary ms-2" style="font-size:0.6rem;">Not Checked In</span>';
            
            // Head of family badge
            const headBadge = isHead ? 
                '<span class="badge bg-primary ms-2" style="font-size:0.6rem;">Head</span>' : '';
            
            // Scanned badge
            const scannedBadge = isScannedPerson ? 
                '<span class="badge bg-warning ms-2" style="font-size:0.6rem;">Scanned</span>' : '';
            
            // Highlight styling for the scanned person
            const highlightStyle = isScannedPerson ? 'background-color: #e8f5e9; border: 2px solid #28a745;' : '';
            
            return `
                <div class="col-12">
                    <div class="bg-light p-2 rounded d-flex align-items-center gap-2" style="${highlightStyle}">
                        ${member.photo ? `
                            <a href="/${member.photo}" target="_blank">
                                <img src="/${member.photo}" 
                                     style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border); cursor: pointer;"
                                     title="Click to view full size"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'width:45px;height:45px;border-radius:8px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;font-weight:700;\'>${memberInitial}</div>'">
                            </a>
                        ` : `
                            <div style="width:45px;height:45px;border-radius:8px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;font-weight:700;">
                                ${memberInitial}
                            </div>
                        `}
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:600; font-size:.8rem; display:flex; align-items:center; flex-wrap:wrap;">
                                ${member.name}
                                ${headBadge}
                                ${scannedBadge}
                                ${memberCheckedIn}
                            </div>
                            <small class="text-muted d-block" style="font-size:.65rem;">
                                ${member.relation || 'Family Member'} 
                                ${member.age ? `• ${member.age} years old` : ''}
                                ${member.sex && member.sex !== 'N/A' ? `• ${member.sex}` : ''}
                            </small>
                            ${member.check_in_time ? `
                                <small class="text-success d-block" style="font-size:.6rem;">
                                    <i class="fa-solid fa-clock"></i> Check-in: ${member.check_in_time}
                                </small>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('')}
    </div>
</div>
` : ''}
            `;
        } else {
            // Error case
            return `
                <div class="text-center mb-3">
                    <i class="fa-solid fa-circle-xmark text-danger fa-4x"></i>
                    <h4 class="fw-bold text-danger mt-2">Scan Failed</h4>
                    <p class="fw-bold">${data.message || 'Invalid QR code'}</p>
                </div>
                
                <div class="bg-light p-3 rounded">
                    <p class="text-muted small mb-0">Please ensure the QR code is valid and try again.</p>
                </div>
            `;
        }
    } else {
        // DISTRIBUTION MODE RESULTS
        if (data.status === 'success') {
            // Head of Family Photo
            let headPhotoHTML = '';
            if (data.resident && data.resident.photo) {
                // Remove the leading slash from the path if it exists
                let photoPath = data.resident.photo;
                if (photoPath.startsWith('/')) {
                    photoPath = photoPath.substring(1);
                }
                
                headPhotoHTML = `
                    <div class="text-center mb-3">
                        <a href="/${photoPath}" target="_blank">
                            <img src="/${photoPath}" 
                                 style="width: 80px; height: 80px; border-radius: 16px; object-fit: cover; border: 3px solid var(--green-mid); cursor: pointer;"
                                 title="Click to view full size"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;font-weight:700;margin:0 auto;\'>${data.resident.first_name ? data.resident.first_name.charAt(0).toUpperCase() : 'H'}</div>'">
                        </a>
                    </div>
                `;
            } else {
                headPhotoHTML = `
                    <div class="text-center mb-3">
                        <div style="width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;font-weight:700;margin:0 auto;">
                            ${data.resident ? data.resident.first_name?.charAt(0).toUpperCase() : 'H'}
                        </div>
                    </div>
                `;
            }

            let itemsHTML = '';
            if (data.items_distributed && Array.isArray(data.items_distributed)) {
                itemsHTML = data.items_distributed.map(item => `
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">${item.item_name}</span>
                            <span class="badge bg-primary rounded-pill">
                                ${item.quantity} ${item.unit_type}
                            </span>
                        </div>
                        <small class="text-muted d-block">
                            <i class="fa-solid fa-cube me-1"></i>Batch: ${item.batch_number}
                        </small>
                    </div>
                `).join('');
            }

            // ===== UPDATED FAMILY MEMBERS SECTION WITH PRESENT/ABSENT STATUS =====
            let familyMembersHTML = '';
            if (data.family_members && data.family_members.length > 0) {
                // Count present members
                const presentCount = data.family_members.filter(m => m.checked_in_today).length;
                const totalCount = data.family_members.length;
                
                familyMembersHTML = `
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-users me-2"></i>Family Members (Present/Absent)</h6>
                        
                        <!-- Attendance Summary Badge -->
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                                <span><i class="fa-solid fa-users me-1"></i>Attendance Summary:</span>
                                <span>
                                    <span class="badge bg-success me-1">Present: ${presentCount}</span>
                                    <span class="badge bg-secondary">Absent: ${totalCount - presentCount}</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="row g-2">
                `;
                
                data.family_members.forEach(member => {
                    const memberInitial = member.name ? member.name.charAt(0).toUpperCase() : 'M';
                    const isPresent = member.checked_in_today || false;
                    
                    // Different styling based on presence
                    const borderColor = isPresent ? '#28a745' : '#dc3545';
                    const bgColor = isPresent ? '#f0fff4' : '#fff5f5';
                    const statusIcon = isPresent ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger';
                    const statusText = isPresent ? 'Present' : 'Absent';
                    
                    familyMembersHTML += `
                        <div class="col-12">
                            <div class="p-2 rounded d-flex align-items-center gap-2" 
                                 style="background-color: ${bgColor}; border-left: 4px solid ${borderColor};">
                                ${member.photo ? `
                                    <a href="/${member.photo}" target="_blank">
                                        <img src="/${member.photo}" 
                                             style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border); cursor: pointer;"
                                             title="Click to view full size"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;\'>${memberInitial}</div>'">
                                    </a>
                                ` : `
                                    <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,var(--green-light),var(--green-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;">
                                        ${memberInitial}
                                    </div>
                                `}
                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight:600; font-size:.8rem; display: flex; align-items: center; justify-content: space-between;">
                                        <span>${member.name}</span>
                                        <span class="badge ${isPresent ? 'bg-success' : 'bg-danger'} ms-2" style="font-size:0.65rem;">
                                            <i class="fa-solid ${isPresent ? 'fa-circle-check' : 'fa-circle-xmark'} me-1"></i>
                                            ${statusText}
                                        </span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <small class="text-muted d-block" style="font-size:.65rem;">
                                            ${member.relation || 'Family Member'} 
                                            ${member.age ? `• ${member.age} years old` : ''}
                                            ${member.sex && member.sex !== 'N/A' ? `• ${member.sex}` : ''}
                                        </small>
                                        ${member.check_in_time ? `
                                            <small class="text-success" style="font-size:.6rem;">
                                                <i class="fa-regular fa-clock"></i> ${member.check_in_time}
                                            </small>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                familyMembersHTML += `
                        </div>
                    </div>
                `;
            }
            // ===== END OF UPDATED FAMILY MEMBERS SECTION =====

            return `
                <div class="text-center mb-3">
                    <i class="fa-solid fa-circle-check text-success fa-4x"></i>
                    <h4 class="fw-bold text-success mt-2">Distribution Confirmed</h4>
                    <p class="text-muted">${data.message}</p>
                </div>
                
                ${headPhotoHTML}
                
                <div class="bg-light p-3 rounded">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted d-block">Beneficiary Name</small>
                            <span class="fw-bold">${data.resident ? data.resident.full_name : 'N/A'}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Household No.</small>
                            <span class="fw-bold">${data.resident ? data.resident.household_no : 'N/A'}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Barangay</small>
                            <span class="fw-bold">${data.resident ? data.resident.barangay : 'N/A'}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted d-block">Contact</small>
                            <span class="fw-bold">${data.resident ? data.resident.contact_number : 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-boxes me-2"></i>Items Distributed</h6>
                    <div class="bg-light p-2 rounded">
                        ${itemsHTML || '<p class="text-muted">No items distributed</p>'}
                    </div>
                </div>
                
                ${familyMembersHTML}
            `;
        } else if (data.status === 'denied') {
            // Already claimed result
            let headPhotoHTML = '';
            if (data.resident && data.resident.photo) {
                headPhotoHTML = `
                    <div class="text-center mb-3">
                        <a href="/${data.resident.photo}" target="_blank">
                            <img src="/${data.resident.photo}" 
                                 style="width: 80px; height: 80px; border-radius: 16px; object-fit: cover; border: 3px solid var(--orange-mid); cursor: pointer;"
                                 title="Click to view full size"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,var(--orange-light),var(--orange-mid));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;font-weight:700;margin:0 auto;\'>${data.resident.first_name ? data.resident.first_name.charAt(0).toUpperCase() : 'H'}</div>'">
                        </a>
                    </div>
                `;
            }

            return `
                <div class="text-center mb-3">
                    <i class="fa-solid fa-circle-exclamation text-warning fa-4x"></i>
                    <h4 class="fw-bold text-warning mt-2">Already Claimed</h4>
                    <p class="fw-bold">${data.message}</p>
                </div>
                
                ${headPhotoHTML}
                
                <div class="bg-light p-3 rounded">
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted d-block">Beneficiary Name</small>
                            <span class="fw-bold">${data.resident ? data.resident.full_name : 'N/A'}</span>
                        </div>
                    </div>
                    
                    ${data.distribution_history ? `
                    <div class="mt-3 p-2 bg-white rounded">
                        <label class="small text-muted">Distribution History</label>
                        <p class="small mb-0">Claimed on: ${new Date(data.distribution_history.claimed_at).toLocaleString()}</p>
                        <p class="small">Distributed by: ${data.distribution_history.distributor_name}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            // Error result
            return `
                <div class="text-center mb-3">
                    <i class="fa-solid fa-circle-xmark text-danger fa-4x"></i>
                    <h4 class="fw-bold text-danger mt-2">Scan Failed</h4>
                    <p class="fw-bold">${data.message}</p>
                </div>
                
                <div class="bg-light p-3 rounded">
                    <p class="text-muted small mb-0">Please ensure the QR code is valid and try again.</p>
                </div>
                
                ${data.message && data.message.includes('stock') ? `
                <div class="alert alert-warning mt-3 small">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Contact inventory manager to restock supplies.
                </div>
                ` : ''}
            `;
        }
    }
}

// ==================== MODAL EVENT LISTENERS ====================
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const modalElement = document.getElementById('scanResultModal');
    
modalElement.addEventListener('hidden.bs.modal', function() {
        isModalOpen = false;
        clearTimeout(autoAdvanceTimer); // prevent stray auto-close firing after manual close
        console.log('Modal closed, resuming scanning');
        // Resume scanning when modal is closed
        resumeScanning();
    });
    
    // Add event listener for when modal is shown
    modalElement.addEventListener('shown.bs.modal', function() {
        console.log('Modal opened, scanner paused');
    });
    
    // Scan Another button - remove any existing listeners to prevent duplicates
    const scanAnotherBtn = document.getElementById('scanAnotherBtn');
    
    // Remove all existing listeners and add new one
    scanAnotherBtn.replaceWith(scanAnotherBtn.cloneNode(true));
    document.getElementById('scanAnotherBtn').addEventListener('click', function() {
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
    });
});

// Remove duplicate event listeners by cleaning up
window.addEventListener('load', function() {
    // Remove any existing keyboard listeners that might conflict
    document.removeEventListener('keydown', handleEscapeKey);
    document.addEventListener('keydown', handleEscapeKey);
    
    // Remove existing visibility change listener
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    document.addEventListener('visibilitychange', handleVisibilityChange);
});

function handleEscapeKey(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('scanResultModal');
        if (modal.classList.contains('show')) {
            // Modal is open, let it close naturally
            return;
        }
    }
}

function handleVisibilityChange() {
    if (!document.hidden && currentScannerType === 'camera' && !isModalOpen) {
        // Page is visible again and modal is not open
        resumeScanning();
    }
}

// ==================== INIT ====================
window.onload = function() {
    applySavedMode();
    applySavedScannerType();
    applySavedEvent();
    applySavedAdvanceMode();
    loadStats();
    loadList();
};

window.addEventListener('beforeunload', function() {
    if (html5QrcodeScanner) { 
        try { 
            html5QrcodeScanner.clear(); 
        } catch(e) {} 
    }
    if (hardwareTimeout) clearTimeout(hardwareTimeout);
});

// Add this new function to maintain focus on hardware input
function maintainHardwareFocus() {
    const input = document.getElementById('hardwareScanInput');
    if (currentScannerType === 'hardware' && hardwareActive && !isModalOpen) {
        // Focus the input
        input.focus();
        
        // Prevent the cursor from being visible (optional)
        // input.style.caretColor = 'transparent'; // Uncomment to hide blinking cursor
    }
}

// Add event listener to refocus when clicking anywhere
document.addEventListener('click', function(e) {
    // Don't refocus if clicking the input itself, the event dropdown, or if modal is open
    if (e.target.id === 'hardwareScanInput' || e.target.closest('#eventSelect')) return;
    
    if (currentScannerType === 'hardware' && hardwareActive && !isModalOpen) {
        setTimeout(() => {
            document.getElementById('hardwareScanInput').focus();
        }, 10);
    }
});

// Also refocus when the page regains focus
window.addEventListener('focus', function() {
    if (currentScannerType === 'hardware' && hardwareActive && !isModalOpen) {
        setTimeout(() => {
            document.getElementById('hardwareScanInput').focus();
        }, 100);
    }
});

function saveSelectedEvent(eventId) { localStorage.setItem('preferredEventId', eventId); }
function loadSavedEvent()            { return localStorage.getItem('preferredEventId'); }

function getSelectedEventId() {
    return document.getElementById('eventSelect').value;
}

function applySavedEvent() {
    const saved = loadSavedEvent();
    const select = document.getElementById('eventSelect');
    if (saved && select.querySelector(`option[value="${saved}"]`)) {
        select.value = saved;
    }
}

document.getElementById('eventSelect').addEventListener('change', function() {
    saveSelectedEvent(this.value);
    loadList();
});

// ==================== FULLSCREEN SCANNER: AUTO-HIDE SIDEBAR ====================
(function() {
    const body = document.body;
    const wasCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    // Force the sidebar hidden while on this page, without touching the saved preference
    body.classList.add('sidebar-collapsed');

    // Restore the user's normal sidebar state when they leave the scanner page
    window.addEventListener('beforeunload', function() {
        if (!wasCollapsed) {
            body.classList.remove('sidebar-collapsed');
        }
    });
})();

let scanAdvanceMode = 'manual'; // 'manual' or 'auto'
let autoAdvanceTimer = null;

function setAdvanceMode(mode) {
    scanAdvanceMode = mode;
    localStorage.setItem('preferredAdvanceMode', mode);
    document.getElementById('manualAdvanceBtn').classList.toggle('active', mode === 'manual');
    document.getElementById('autoAdvanceBtn').classList.toggle('active', mode === 'auto');
}

function applySavedAdvanceMode() {
    const saved = localStorage.getItem('preferredAdvanceMode');
    setAdvanceMode(saved === 'auto' ? 'auto' : 'manual');
}

// ==================== LIVE LIST PANEL ====================
let currentListMode = 'attendance';

function switchListMode(mode) {
    currentListMode = mode;
    loadList();
}

function loadList() {
    const eventId = getSelectedEventId();
    const body = document.getElementById('listPanelBody');
    if (!eventId) {
        body.innerHTML = '<div class="list-empty">Select an event to see activity.</div>';
        document.getElementById('listStats').innerHTML = '';
        return;
    }

    const url = currentListMode === 'attendance'
        ? `/attendance/list-json?event_id=${encodeURIComponent(eventId)}`
        : `/distribution/list-json?event_id=${encodeURIComponent(eventId)}`;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const rows = res.data || [];
            body.innerHTML = currentListMode === 'attendance'
                ? renderAttendanceList(rows)
                : renderDistributionList(rows);
            updateListStats(rows);
        })
        .catch(() => {
            body.innerHTML = '<div class="list-empty">Failed to load activity.</div>';
            document.getElementById('listStats').innerHTML = '';
        });
}

function updateListStats(rows) {
    const statsEl = document.getElementById('listStats');
    if (!rows.length) { statsEl.innerHTML = ''; return; }

    if (currentListMode === 'attendance') {
        statsEl.innerHTML = `
            <span><i class="fa-solid fa-user-check me-1"></i>${rows.length} ${rows.length === 1 ? 'person' : 'people'} attended</span>
        `;
    } else {
        const familyKeys = new Set(rows.map(row =>
            row.household_no || `${row.head_first_name || ''}_${row.head_last_name || ''}`
        ));
        statsEl.innerHTML = `
            <span><i class="fa-solid fa-people-roof me-1"></i>${familyKeys.size} ${familyKeys.size === 1 ? 'family' : 'families'}</span>
        `;
    }
}

function renderAttendanceList(rows) {
    if (!rows.length) return '<div class="list-empty">No check-ins yet.</div>';

    return rows.map(row => {
        const headName = `${row.first_name || ''} ${row.last_name || ''}`.trim();
        const isMember = !!row.family_member_name;
        const displayName = isMember ? row.family_member_name : headName;
        const initial = displayName ? displayName.charAt(0).toUpperCase() : '?';
        const subLine = isMember
            ? `${row.relation || 'Family Member'} of ${headName}`
            : (row.household_no ? `Household #${row.household_no}` : 'Head of Family');

        const photo = isMember ? row.member_photo : row.head_photo;
        const avatarHTML = photo
            ? `<img src="/${photo.replace(/^\//, '')}" class="li-avatar-img" alt="${displayName}">`
            : initial;

        return `
            <div class="list-item">
                <div class="li-avatar">${avatarHTML}</div>
                <div class="li-body">
                    <div class="li-name">${displayName}${isMember ? '<span class="li-badge">Member</span>' : ''}</div>
                    <div class="li-sub">${subLine}</div>
                </div>
                <div class="li-time">${row.check_in_time || ''}</div>
            </div>
        `;
    }).join('');
}

function renderDistributionList(rows) {
    if (!rows.length) return '<div class="list-empty">No claims yet.</div>';

    return rows.map(row => {
        const headName = `${row.head_first_name || ''} ${row.head_last_name || ''}`.trim();
        const claimedByMember = !!row.family_member_name;
        const displayName = claimedByMember ? row.family_member_name : headName;
        const initial = displayName ? displayName.charAt(0).toUpperCase() : '?';
        const otherMembers = parseInt(row.total_family_members, 10) || 0;
        const totalFamilySize = otherMembers + 1;
        const subLine = claimedByMember
            ? `${row.relation || 'Family Member'} of ${headName}`
            : (row.household_no ? `Household #${row.household_no}` : 'Head of Family');
        const time = row.claimed_at || row.distribution_date || '';

        const photo = claimedByMember ? row.member_photo : row.head_photo;
        const avatarHTML = photo
            ? `<img src="/${photo.replace(/^\//, '')}" class="li-avatar-img" alt="${displayName}">`
            : initial;

        return `
            <div class="list-item">
                <div class="li-avatar">${avatarHTML}</div>
                <div class="li-body">
                    <div class="li-name">
                        ${displayName}${claimedByMember ? '<span class="li-badge">Member</span>' : ''}
                        <span class="li-badge">${otherMembers} ${otherMembers === 1 ? 'member' : 'members'}</span>
                    </div>
                    <div class="li-sub">${subLine}${row.item_name ? ' • ' + row.item_name : ''} • Family of ${totalFamilySize} benefited</div>
                </div>
                <div class="li-time">${time ? new Date(time).toLocaleTimeString() : ''}</div>
            </div>
        `;
    }).join('');
}

document.querySelectorAll('input[name="listMode"]').forEach(r => {
    r.addEventListener('change', function() { switchListMode(this.value); });
});

function toggleScannerPanel() {
    const scannerCol = document.querySelector('.scanner-col');
    const listPanel = document.querySelector('.list-panel');
    const btn = document.getElementById('scannerToggleBtn');

    const collapsed = scannerCol.classList.toggle('is-collapsed');
    listPanel.classList.toggle('is-expanded', collapsed);

    btn.innerHTML = collapsed
        ? '<i class="fa-solid fa-eye"></i> Show Scanner'
        : '<i class="fa-solid fa-eye-slash"></i> Hide Scanner';

    localStorage.setItem('scannerPanelHidden', collapsed);
}

// restore preference on load
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('scannerPanelHidden') === 'true') {
        toggleScannerPanel();
    }
});

// ==================== IDLE SCREENSAVER MODE ====================
;(function(){
    var IDLE_TIMEOUT = 30000
    var idleTimer = null
    var demoOverlay = document.getElementById('idleOverlay')
    if (!demoOverlay) return

    var demoIdx = 0
    var demoTimer = null
    var demoCurrentId = -1
    var DEMO_VIDEO_DUR = 10500
    var demoFlowing = false

    var demoFlow = [
        [0, DEMO_VIDEO_DUR],
        [1, 3600],
        [2, 3000],
        [3, 4800],
        [4, 3000],
        [5, 3000],
        [0, DEMO_VIDEO_DUR],
        [1, 2600],
        [2, 2400],
        [6, 3600],
        [0, DEMO_VIDEO_DUR],
        [1, 2600],
        [2, 2200],
        [7, 3600],
        [5, 2600],
        [0, DEMO_VIDEO_DUR],
    ]

    // set current date in demo beneficiary screen
    var demoDateEl = document.getElementById('demoDateDisplay')
    if (demoDateEl) {
        var d = new Date()
        demoDateEl.textContent = d.toLocaleDateString('en-PH', {month:'short', day:'numeric', year:'numeric'}) +
            ' \u00b7 ' + d.toLocaleTimeString('en-PH', {hour:'2-digit', minute:'2-digit'})
    }

    function demoNext() {
        if (!demoOverlay.classList.contains('active')) return

        var step = demoFlow[demoIdx]
        var id = step[0]
        var dur = step[1]

        var prevEl = document.getElementById('demoInner' + demoCurrentId)
        if (prevEl && demoCurrentId !== id) {
            prevEl.style.transition = 'opacity .35s ease, transform .35s cubic-bezier(.55,0,1,.45)'
            prevEl.classList.remove('enter')
            prevEl.style.transform = 'translateY(-24px)'
        }

        var el = document.getElementById('demoInner' + id)
        if (el) {
            el.classList.remove('enter')
            el.style.transition = 'none'
            el.style.opacity = ''
            el.style.transform = ''
            void el.offsetWidth
            el.style.transition = ''
            el.classList.add('enter')
        }

        demoCurrentId = id

        if (id === 0) {
            var video = document.getElementById('demoIntroVideo')
            if (video) { video.currentTime = 0; video.play() }
        }

        demoIdx = (demoIdx + 1) % demoFlow.length
        demoTimer = setTimeout(demoNext, dur)
    }

    function startDemoLoop() {
        if (demoFlowing) return
        // If hardware scanner is active, let pointer events pass through
        if (currentScannerType === 'hardware' && hardwareActive) {
            demoOverlay.style.pointerEvents = 'none'
        }
        demoFlowing = true
        demoIdx = 0
        demoCurrentId = -1
        demoNext()
    }

    function stopDemoLoop() {
        demoFlowing = false
        clearTimeout(demoTimer)
        demoTimer = null
        var video = document.getElementById('demoIntroVideo')
        if (video) { video.pause() }
        var inners = demoOverlay.querySelectorAll('.screen-inner')
        for (var i = 0; i < inners.length; i++) {
            inners[i].classList.remove('enter')
            inners[i].style.opacity = ''
            inners[i].style.transform = ''
        }
    }

    function isIdleActive() {
        return demoOverlay.classList.contains('active')
    }

    window.resetIdleTimer = function() {
        clearTimeout(idleTimer)
        if (!isIdleActive() && !isModalOpen) {
            idleTimer = setTimeout(function() {
                if (isModalOpen) return
                demoOverlay.classList.add('active')
                startDemoLoop()
            }, IDLE_TIMEOUT)
        }
    }

    function clearIdleTimer() {
        clearTimeout(idleTimer)
        idleTimer = null
    }

    function exitIdleMode() {
        if (!isIdleActive()) return
        stopDemoLoop()
        demoOverlay.classList.remove('active')
        demoOverlay.style.pointerEvents = ''
        clearIdleTimer()
        if (!isModalOpen) {
            setTimeout(window.resetIdleTimer, 500)
        }
    }

    // Hook into existing modal events (safe: adds extra listeners, doesn't replace)
    var modalEl = document.getElementById('scanResultModal')
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function() {
            setTimeout(window.resetIdleTimer, 800)
        })
        modalEl.addEventListener('shown.bs.modal', function() {
            clearIdleTimer()
            exitIdleMode()
        })
    }

    // User interaction dismisses idle mode (click, key, touch)
    document.addEventListener('mousedown', function(e) {
        if (isIdleActive()) exitIdleMode()
    })
    // Don't intercept keydown in hardware mode (scanner keystrokes pass through)
    document.addEventListener('keydown', function(e) {
        if (isIdleActive() && currentScannerType !== 'hardware') exitIdleMode()
    })
    document.addEventListener('touchstart', function(e) {
        if (isIdleActive()) exitIdleMode()
    })

    // Start idle timer after page loads (give scanner time to init)
    setTimeout(window.resetIdleTimer, 2000)

    // Reset timer when visibility changes (page comes back to foreground)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !isModalOpen) {
            setTimeout(window.resetIdleTimer, 500)
        }
    })
})();
</script>

<?= $this->endSection() ?>