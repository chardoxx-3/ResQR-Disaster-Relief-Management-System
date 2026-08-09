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

/* Hide the auto-generated breadcrumb title bar from layout/main — this page only */
.page-title-bar { display: none !important; }

.kitchen-brand-bar { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:12px 20px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); margin-bottom:14px; }
.kitchen-brand-bar .brand-group { display:flex; align-items:center; gap:12px; }
.kitchen-brand-logo { height:34px; width:auto; object-fit:contain; }
.kitchen-brand-divider { width:1px; height:26px; background:var(--border); }
.kitchen-brand-title { font-size:.78rem; font-weight:700; color:var(--text-2); display:flex; align-items:center; }
.kitchen-live-clock { display:inline-flex; align-items:center; gap:6px; margin-left:12px; padding:4px 10px; border-radius:20px; background:var(--green-bg); color:var(--green-dark); font-size:.72rem; font-weight:700; font-variant-numeric:tabular-nums; }
.kitchen-live-clock .clock-dot { width:6px; height:6px; border-radius:50%; background:var(--green); flex-shrink:0; animation:clockPulse 1.5s ease-in-out infinite; }
@keyframes clockPulse { 0%,100%{opacity:1} 50%{opacity:.35} }

.page-header { background:var(--surface); border-radius:var(--radius) var(--radius) 0 0; padding:16px 20px; border:1px solid var(--border); border-bottom:none; }
.page-title h5 { font-size:1rem; font-weight:700; color:var(--text-1); margin:0; }
.page-body { background:var(--surface); border:1px solid var(--border); border-top:none; border-radius:0 0 var(--radius) var(--radius); padding:20px; }

.scanner-type-toggle { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px; }
.scanner-type-toggle input[type=radio] { display:none; }
.stype-card { border:1.5px solid var(--border); border-radius:var(--radius-sm); padding:8px 12px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px; font-size:.72rem; font-weight:600; color:var(--text-2); }
.stype-card:hover { border-color:var(--green); color:var(--green-dark); background:var(--green-bg); }
.scanner-type-toggle input:checked + .stype-card { border-color:var(--green); background:var(--green-bg); color:var(--green-dark); }
@media(max-width:640px) { .scanner-type-wrap { display:none !important; } }

.mode-banner { display:flex; align-items:center; gap:8px; padding:9px 14px; border-radius:var(--radius-sm); font-size:.72rem; font-weight:600; margin-bottom:14px; border:1px solid; background:var(--green-bg); color:var(--green-dark); border-color:#c8e0b5; }
.qr-restriction-note { display:flex; align-items:flex-start; gap:8px; padding:9px 14px; border-radius:var(--radius-sm); font-size:.68rem; font-weight:600; margin-bottom:14px; border:1px solid #fde68a; background:var(--amber-bg); color:var(--amber); }

.camera-wrap { border-radius:var(--radius); overflow:hidden; border:1px solid var(--border); background:#000; }
.camera-wrap #reader { width:100%; min-height:340px; }
.camera-status { background:#1f2937; color:#d1d5db; padding:10px 16px; display:flex; align-items:center; gap:8px; font-size:.7rem; font-weight:600; }
.scan-pulse { width:8px; height:8px; border-radius:50%; background:var(--green); flex-shrink:0; }

.hw-panel { border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
.hw-panel-header { background:#1f2937; color:#f9fafb; padding:12px 16px; display:flex; align-items:center; gap:8px; font-size:.78rem; font-weight:700; }
.hw-panel-body { padding:16px; }
.field-label { display:block; font-size:.7rem; font-weight:700; color:var(--text-2); margin-bottom:5px; }
.form-control { width:100%; border:1.5px solid var(--border); border-radius:var(--radius-sm); padding:10px 12px; font-size:.82rem; color:var(--text-1); background:var(--surface); outline:none; }
.form-control:focus { border-color:var(--green); }
.hw-status { display:flex; align-items:center; gap:8px; padding:9px 14px; border-radius:var(--radius-sm); font-size:.72rem; font-weight:600; margin-top:12px; border:1px solid; }
.hw-status.ready { background:var(--green-bg); color:var(--green-dark); border-color:#c8e0b5; }
.hw-status.processing { background:var(--blue-bg); color:var(--blue); border-color:#bfdbfe; }
.hw-status.warning { background:var(--amber-bg); color:var(--amber); border-color:#fde68a; }
.hw-status.error { background:var(--red-bg); color:var(--red); border-color:#fecaca; }
.hw-status.idle { background:var(--bg); color:var(--text-3); border-color:var(--border); }
.hw-result { margin-top:12px; }
.hw-result-inner { background:var(--blue-bg); border:1px solid #bfdbfe; border-radius:var(--radius-sm); padding:12px 14px; display:flex; align-items:center; gap:10px; font-size:.72rem; color:var(--blue); }
.hw-panel-footer { padding:10px 16px; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; }

.btn-primary-c { display:inline-flex; align-items:center; gap:5px; background:var(--green); color:#fff; border:none; border-radius:6px; padding:6px 14px; font-size:.7rem; font-weight:600; text-decoration:none; cursor:pointer; }
.btn-primary-c:hover { background:var(--green-dark); color:#fff; }
.btn-outline-c { display:inline-flex; align-items:center; gap:5px; background:transparent; color:var(--text-2); border:1.5px solid var(--border); border-radius:6px; padding:5px 10px; font-size:.68rem; font-weight:600; text-decoration:none; cursor:pointer; }
.btn-outline-c:hover { border-color:var(--green); color:var(--green-dark); background:var(--green-bg); }
.btn-advance-mode { flex:1; padding:8px 14px; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--surface); color:var(--text-3); font-weight:600; font-size:.8rem; cursor:pointer; }
.btn-advance-mode.active { background:var(--green-bg); border-color:var(--green); color:var(--green-dark); }
#hardwareScanInput:focus { caret-color: transparent; }

.split-layout { display:flex; gap:16px; align-items:flex-start; width:100%; max-width:none; margin:0; }
.scanner-col { flex:0 1 380px; max-width:380px; margin:0; overflow:hidden; transition:flex-basis .25s ease, max-width .25s ease, opacity .2s ease; }
.scanner-col.is-collapsed { flex-basis:0; max-width:0; min-width:0; opacity:0; pointer-events:none; }
.list-panel.is-expanded { flex:1 1 100%; }
.scanner-toggle-btn { display:inline-flex; align-items:center; gap:5px; background:transparent; border:1.5px solid var(--border); border-radius:6px; padding:5px 10px; font-size:.68rem; font-weight:600; color:var(--text-2); cursor:pointer; }
.scanner-toggle-btn:hover { border-color:var(--green); color:var(--green-dark); background:var(--green-bg); }

.list-panel { flex:1 1 640px; min-width:380px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); position:sticky; top:16px; max-height:calc(100vh - 32px); display:flex; flex-direction:column; }
.list-panel-header { padding:16px 18px 0; }
.list-panel-header h6 { font-size:.9rem; font-weight:700; margin:0 0 12px; color:var(--text-1); }
.list-stats { display:flex; align-items:center; flex-wrap:wrap; gap:6px 12px; font-size:.7rem; font-weight:700; color:var(--green-dark); background:var(--green-bg); border:1px solid var(--border); border-radius:var(--radius-sm); padding:7px 10px; margin-bottom:10px; }
.list-panel-body { flex:1; overflow-y:auto; padding:0 18px 18px; }
.list-empty { text-align:center; color:var(--text-3); font-size:.78rem; padding:30px 10px; }
.list-item { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:var(--radius-sm); border:1px solid var(--border); margin-bottom:8px; background:var(--bg); }
.list-item .li-avatar { width:36px; height:36px; border-radius:50%; flex-shrink:0; background:var(--green); display:flex; align-items:center; justify-content:center; color:#fff; font-size:.8rem; font-weight:700; }
.list-item .li-body { flex:1; min-width:0; }
.list-item .li-name { font-size:.78rem; font-weight:700; color:var(--text-1); }
.list-item .li-sub { font-size:.65rem; color:var(--text-3); margin-top:1px; }
.list-item .li-time { font-size:.62rem; color:var(--text-3); white-space:nowrap; flex-shrink:0; }
.list-item .li-badge { font-size:.6rem; font-weight:700; padding:2px 7px; border-radius:20px; background:var(--green-bg); color:var(--green-dark); margin-left:4px; }
@media(max-width:900px) { .split-layout { flex-direction:column; } .list-panel { position:static; max-height:480px; width:100%; } }
.li-avatar-img { width:100%; height:100%; object-fit:cover; border-radius:50%; }

.list-kpi-grid { display:grid; grid-template-columns:1fr; gap:14px; margin-bottom:14px; }
.list-kpi-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm); padding:18px 20px; }
.list-kpi-card.hero {
  background:linear-gradient(135deg, var(--green-dark) 0%, var(--green) 100%);
  border-color:transparent;
  padding:30px 28px;
  animation: heroGlow 3s ease-in-out infinite;
}
.list-kpi-card.highlight { border:1.5px solid var(--green); box-shadow:0 0 0 3px var(--green-bg), 0 4px 10px rgba(58,107,26,.08); position:relative; }
.list-kpi-card.highlight::before { content:''; position:absolute; inset:0; border-radius:var(--radius-sm); box-shadow:0 0 0 1px rgba(58,107,26,.25); pointer-events:none; }
.list-kpi-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; margin-bottom:10px; background:var(--green-bg); color:var(--green-dark); }
.list-kpi-card.hero .list-kpi-icon { width:54px; height:54px; font-size:1.5rem; background:rgba(255,255,255,.2); color:#fff; }
.list-kpi-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-3); }
.list-kpi-card.hero .list-kpi-label { font-size:.85rem; color:rgba(255,255,255,.75); }
.list-kpi-val { font-size:1.9rem; font-weight:800; line-height:1.15; color:var(--text-1); margin-top:6px; }
.list-kpi-card.hero .list-kpi-val { font-size:3.6rem; color:#fff; display:inline-block; }

@keyframes heroGlow {
  0%, 100% { box-shadow:0 4px 12px rgba(58,107,26,.15); }
  50%      { box-shadow:0 4px 30px rgba(58,107,26,.45); }
}
@keyframes kpiBump {
  0%   { transform:scale(1); }
  40%  { transform:scale(1.2); }
  100% { transform:scale(1); }
}
.list-kpi-val.bump { animation: kpiBump .45s ease-out; }

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

<div class="kitchen-brand-bar">
  <div class="brand-group">
    <img src="/uploads/logo.png" alt="ResQR Logo" class="kitchen-brand-logo">
    <span class="kitchen-brand-divider"></span>
    <img src="/uploads/team-logo.png" alt="Team Logo" class="kitchen-brand-logo">
  </div>
  <span class="kitchen-brand-title">
    <i class="fa-solid fa-kitchen-set me-2" style="color:var(--green)"></i>Smart Mobile Kitchen
    <span class="kitchen-live-clock" id="kitchenLiveClock"><span class="clock-dot"></span><span id="kitchenClockText">--:--:--</span></span>
  </span>
</div>

<div class="split-layout">
<div class="page-wrap scanner-col">

  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-kitchen-set me-2" style="color:var(--green)"></i>Smart Mobile Kitchen Scanner</h5>
    </div>
  </div>

  <div class="page-body">

    <div class="advance-mode-toggle" style="display:flex; gap:8px; margin-bottom:14px;">
      <button type="button" class="btn-advance-mode active" id="manualAdvanceBtn" onclick="setAdvanceMode('manual')">
        <i class="fa-solid fa-hand-pointer me-1"></i> Manual
      </button>
      <button type="button" class="btn-advance-mode" id="autoAdvanceBtn" onclick="setAdvanceMode('auto')">
        <i class="fa-solid fa-bolt me-1"></i> Auto
      </button>
    </div>

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

    <!-- Scanner Type Toggle — hidden on mobile -->
    <div class="scanner-type-wrap">
      <div class="scanner-type-toggle">
        <input type="radio" name="scannerType" id="cameraScanner" value="camera" autocomplete="off" checked>
        <label class="stype-card" for="cameraScanner"><i class="fa-solid fa-camera"></i> Camera Scanner</label>

        <input type="radio" name="scannerType" id="hardwareScanner" value="hardware" autocomplete="off">
        <label class="stype-card" for="hardwareScanner"><i class="fa-solid fa-microchip"></i> Hardware Scanner</label>
      </div>
    </div>

    <div class="mode-banner">
      <i class="fa-solid fa-circle-info"></i>
      <span>Smart Mobile Kitchen — Meal Claim (no attendance check-in required)</span>
    </div>

    <div class="qr-restriction-note">
      <i class="fa-solid fa-triangle-exclamation" style="margin-top:1px;"></i>
      <span>Scans Family Head or Family Member QR only. Household/FACED QR codes are not accepted here — have the person scan their individual QR.</span>
    </div>

    <div id="cameraSection">
      <div class="camera-wrap">
        <div id="reader" style="width:100%; min-height:340px; background:#000;"></div>
        <div class="camera-status"><span class="scan-pulse"></span> Camera Active</div>
      </div>
    </div>

    <div id="hardwareSection" style="display:none">
      <div class="hw-panel">
        <div class="hw-panel-header"><i class="fa-solid fa-microchip"></i> Hardware Scanner Mode</div>
        <div class="hw-panel-body">
          <label class="field-label">Scan Input Field</label>
          <input type="text" class="form-control" id="hardwareScanInput" placeholder="Scanner input will appear here..." readonly>
          <div id="hardwareStatus" class="hw-status idle">
            <i class="fa-solid fa-circle-dot"></i>
            <span>Ready — click the field above and scan</span>
          </div>
          <div id="hardwareResult" class="hw-result" style="display:none"></div>
        </div>
        <div class="hw-panel-footer">
          <button class="btn-outline-c" onclick="resetHardwareScanner()"><i class="fa-solid fa-rotate-right"></i> Reset</button>
        </div>
      </div>
    </div>

  </div><!-- /page-body -->
</div><!-- /page-wrap -->

<!-- Live List Panel -->
<div class="list-panel">
  <div class="list-panel-header">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
      <h6 style="margin:0;"><i class="fa-solid fa-list-check me-2" style="color:var(--green)"></i>Today's Meal Claims</h6>
      <button type="button" class="scanner-toggle-btn" id="scannerToggleBtn" onclick="toggleScannerPanel()">
        <i class="fa-solid fa-eye-slash"></i> Hide Scanner
      </button>
    </div>
<div class="list-kpi-grid">
  <div class="list-kpi-card hero highlight">
    <div class="list-kpi-icon"><i class="fa-solid fa-bowl-food"></i></div>
    <div class="list-kpi-label">Meals Claimed Today</div>
    <div class="list-kpi-val" id="kiTodayCount">—</div>
  </div>
</div>
  </div>
  <div class="list-panel-body" id="listPanelBody">
    <div class="list-empty">Select an event to see activity.</div>
  </div>
</div>
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
        <h5 class="modal-title" id="scanResultModalLabel"><i class="fa-solid fa-circle-check text-success me-2"></i>Scan Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="scanResultModalBody">
        <div class="text-center py-4">
          <div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>
          <p class="mt-2">Processing scan result...</p>
        </div>
      </div>
      <div class="modal-footer" id="scanResultModalFooter">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>Close</button>
        <button type="button" class="btn btn-success" id="scanAnotherBtn"><i class="fa-solid fa-qrcode me-1"></i>Scan Another</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner;
let currentScannerType = 'camera';
let isModalOpen = false;

let hardwareBuffer = '';
let hardwareTimeout = null;
let hardwareActive = false;

const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent) || window.innerWidth <= 640;

// ==================== CAMERA SCANNER ====================
function onScanSuccess(decodedText) {
    const eventId = getSelectedEventId();
    if (!eventId) { alert('Please select an event before scanning.'); return; }

    if (html5QrcodeScanner) {
        try { html5QrcodeScanner.pause(); } catch (e) { console.log('Error pausing scanner:', e); }
    }
    showScanResultModal(decodedText);
}

function initCameraScanner() {
    if (html5QrcodeScanner) {
        try { html5QrcodeScanner.clear(); } catch (e) { console.log('Error clearing scanner:', e); }
    }
    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: { width: 250, height: 250 }, rememberLastUsedCamera: true, showTorchButtonIfSupported: true },
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
}

function stopHardwareScanner() {
    hardwareActive = false;
    const input = document.getElementById('hardwareScanInput');
    if (input) input.readOnly = true;
    if (hardwareTimeout) { clearTimeout(hardwareTimeout); hardwareTimeout = null; }
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
    if (!eventId) { updateHardwareStatus('warning', 'Please select an event before scanning.'); return; }

    stopHardwareScanner();
    updateHardwareStatus('processing', 'Validating QR code...');
    showHardwareResult(scanData);
    showScanResultModal(scanData.trim());
}

function showHardwareResult(scanData) {
    const el = document.getElementById('hardwareResult');
    el.innerHTML = `
        <div class="hw-result-inner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>
                <strong>Processing Meal Claim…</strong><br>
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
    if (html5QrcodeScanner) { try { html5QrcodeScanner.clear(); } catch (e) {} }
    if (!isModalOpen) setTimeout(() => initHardwareScanner(), 100);
}

document.querySelectorAll('input[name="scannerType"]').forEach(r => {
    r.addEventListener('change', function() {
        saveScannerType(this.value);
        this.value === 'camera' ? switchToCamera() : switchToHardware();
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
let lastTodayCount = null;

function loadStats() {
    const eventId = getSelectedEventId();
    fetch(`/smart-kitchen/today-stats${eventId ? '?event_id=' + encodeURIComponent(eventId) : ''}`)
        .then(r => r.json())
        .then(d => {
            const countEl = document.getElementById('kiTodayCount');
            const newCount = d.today ?? 0;

            countEl.textContent = newCount;

            if (lastTodayCount !== null && newCount !== lastTodayCount) {
                countEl.classList.remove('bump');
                void countEl.offsetWidth; // restart animation
                countEl.classList.add('bump');
            }
            lastTodayCount = newCount;
        })
        .catch(() => {});
}

// ==================== PERSISTENCE ====================
function saveScannerType(type)  { localStorage.setItem('preferredScannerType', type); }
function loadSavedScannerType() { return localStorage.getItem('preferredScannerType'); }

function applySavedScannerType() {
    if (isMobile) { switchToCamera(); return; }
    const saved = loadSavedScannerType();
    if (saved) {
        const radio = document.querySelector(`input[name="scannerType"][value="${saved}"]`);
        if (radio) { radio.checked = true; saved === 'camera' ? switchToCamera() : switchToHardware(); return; }
    }
    switchToCamera();
}

// ==================== MODAL FUNCTIONS ====================
function resumeScanning() {
    if (currentScannerType === 'camera') {
        if (html5QrcodeScanner) {
            try { html5QrcodeScanner.clear(); setTimeout(() => initCameraScanner(), 100); }
            catch (e) { setTimeout(() => initCameraScanner(), 300); }
        } else {
            setTimeout(() => initCameraScanner(), 300);
        }
    } else if (currentScannerType === 'hardware') {
        setTimeout(() => { initHardwareScanner(); maintainHardwareFocus(); }, 300);
    }
}

let scanAdvanceMode = 'manual';
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

function showScanResultModal(qrToken) {
    isModalOpen = true;
    const modalElement = document.getElementById('scanResultModal');
    const modal = new bootstrap.Modal(modalElement);

    document.getElementById('scanResultModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>
            <p class="mt-2">Processing meal claim...</p>
            <small class="text-muted">QR: ${qrToken.substring(0,30)}${qrToken.length>30?'…':''}</small>
        </div>
    `;
    modal.show();

    const eventId = getSelectedEventId();
    const url = `/smart-kitchen/processScan?qr_token=${encodeURIComponent(qrToken)}&event_id=${encodeURIComponent(eventId)}`;

    fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            document.getElementById('scanResultModalBody').innerHTML = generateResultHTML(data);
            if (data.status === 'success') { loadStats(); loadList(); }

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

function generateResultHTML(data) {
    // --- NEW: Guest claim gets its own minimal modal, no beneficiary fields ---
    if (data.status === 'success' && data.guest) {
        return `
            <div class="text-center mb-3">
                <i class="fa-solid fa-circle-check text-success fa-4x"></i>
                <h4 class="fw-bold text-success mt-2 mb-0">
                    Meal Claimed <span class="badge bg-warning text-dark ms-2">Guest</span>
                </h4>
                <div class="text-success" style="font-size:.85rem;font-weight:600;opacity:.85;">Nadawat na</div>
            </div>

            <div class="text-center mb-3">
                <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#f0a500,#c98a1f);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-weight:700;margin:0 auto;">
                    ${data.guest.label}
                </div>
            </div>

            <div class="mt-3">
                <h6 class="fw-bold mb-2"><i class="fa-solid fa-bowl-food me-2"></i>Items Distributed</h6>
                <div class="bg-light p-2 rounded">
                    <div class="d-flex justify-content-between align-items-center px-1 py-1">
                        <span>${data.item ? data.item.name : 'N/A'}</span>
                        <span class="badge bg-success">${data.item ? data.item.quantity : 1} ${data.item ? data.item.unit_type : 'serving'}</span>
                    </div>
                </div>
            </div>
        `;
    }
    // --- END NEW ---

    if (data.status === 'success' || data.status === 'denied') {
        const resident = data.resident || {};
        const member = data.family_member;
        const isMember = !!member;
        const displayName = isMember ? member.name : `${resident.first_name || ''} ${resident.last_name || ''}`.trim();
        const initial = displayName ? displayName.charAt(0).toUpperCase() : '?';
        const photo = isMember ? member.photo : resident.photo;

        let photoHTML = '';
        if (photo) {
            let photoPath = photo.startsWith('/') ? photo.substring(1) : photo;
            photoHTML = `
                <div class="text-center mb-3">
                    <a href="/${photoPath}" target="_blank">
                        <img src="/${photoPath}"
                             style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid ${data.status === 'success' ? 'var(--green-mid, #3a6b1a)' : 'var(--amber, #92400e)'};box-shadow:0 4px 12px rgba(0,0,0,.1);cursor:pointer;"
                             title="Click to view full size"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\\'width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#77BC3F,#4a7a26);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem;font-weight:700;margin:0 auto;\\'>${initial}</div>'">
                    </a>
                </div>`;
        } else {
            photoHTML = `
                <div class="text-center mb-3">
                    <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#77BC3F,#4a7a26);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem;font-weight:700;margin:0 auto;">
                        ${initial}
                    </div>
                </div>`;
        }

        const statusIcon = data.status === 'success' ? 'fa-circle-check text-success' : 'fa-circle-info text-warning';
        const statusTitle = data.status === 'success' ? 'Meal Claimed' : 'Already Claimed Today';
        const statusTitleBisaya = data.status === 'success' ? 'Nadawat na' : 'Nakadawat na';

        return `
            <div class="text-center mb-3">
                <i class="fa-solid ${statusIcon} fa-4x"></i>
                <h4 class="fw-bold ${data.status === 'success' ? 'text-success' : 'text-warning'} mt-2 mb-0">
                    ${statusTitle}
                </h4>
                <p class="fw-bold mt-1 mb-0" style="font-size:1.15rem;line-height:1.35;color:${data.status === 'success' ? 'var(--green-mid, #3a6b1a)' : 'var(--amber, #92400e)'};">(${statusTitleBisaya})</p>
            </div>

            ${photoHTML}

<div class="bg-light p-3 rounded">
                <div class="row">
                    <div class="col-12 mb-2">
                        <small class="text-muted d-block">Beneficiary Name</small>
                        <span class="fw-bold fs-6">${displayName || 'N/A'}</span>
                        ${isMember ? `<div><small class="text-muted">Relation: ${member.relation || 'Family Member'}</small></div>` : ''}
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Household No.</small>
                        <span class="fw-bold">${resident.household_no || 'N/A'}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Barangay</small>
                        <span class="fw-bold">${resident.barangay || 'N/A'}</span>
                    </div>
                    <div class="col-6 mt-2">
                        <small class="text-muted d-block">Contact</small>
                        <span class="fw-bold">${resident.contact_number || 'N/A'}</span>
                    </div>
                    <div class="col-6 mt-2">
                        <small class="text-muted d-block">Claimed At</small>
                        <span class="fw-bold">${data.claimed_at ? new Date(data.claimed_at).toLocaleString() : ''}</span>
                    </div>
                </div>
            </div>

<div class="mt-3">
    <h6 class="fw-bold mb-2"><i class="fa-solid fa-bowl-food me-2"></i>Items Distributed</h6>
    <div class="bg-light p-2 rounded">
        <div class="d-flex justify-content-between align-items-center px-1 py-1">
            <span>${data.item ? data.item.name : 'N/A'}</span>
            <span class="badge bg-success">${data.item ? data.item.quantity : 1} ${data.item ? data.item.unit_type : 'serving'}</span>
        </div>
    </div>
</div>
        `;
    }

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

// ==================== MODAL EVENT LISTENERS ====================
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('scanResultModal');

    modalElement.addEventListener('hidden.bs.modal', function() {
        isModalOpen = false;
        clearTimeout(autoAdvanceTimer);
        resumeScanning();
    });

    const scanAnotherBtn = document.getElementById('scanAnotherBtn');
    scanAnotherBtn.replaceWith(scanAnotherBtn.cloneNode(true));
    document.getElementById('scanAnotherBtn').addEventListener('click', function() {
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) modalInstance.hide();
    });
});

window.addEventListener('load', function() {
    document.removeEventListener('keydown', handleEscapeKey);
    document.addEventListener('keydown', handleEscapeKey);
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    document.addEventListener('visibilitychange', handleVisibilityChange);
});

function handleEscapeKey(e) {}
function handleVisibilityChange() {
    if (!document.hidden && currentScannerType === 'camera' && !isModalOpen) resumeScanning();
}

// ==================== LIVE POLLING (picks up scans from other open pages) ====================
let kitchenPollTimer = null;

function startKitchenPolling() {
    clearInterval(kitchenPollTimer);
    kitchenPollTimer = setInterval(function() {
        if (isModalOpen) return;
        loadStats();
        loadList();
    }, 5000);
}

// ==================== AUTO PAGE REFRESH (every 1 minute) ====================
let kitchenAutoReloadTimer = null;

function startKitchenAutoReload() {
    clearInterval(kitchenAutoReloadTimer);
    kitchenAutoReloadTimer = setInterval(function() {
        if (isModalOpen) return; // don't yank the page while a scan result is showing
        window.location.reload();
    }, 60000); // 1 minute
}

// ==================== LIVE LIST PANEL ====================
function loadList() {
    const eventId = getSelectedEventId();
    const body = document.getElementById('listPanelBody');
    if (!eventId) {
        body.innerHTML = '<div class="list-empty">Select an event to see activity.</div>';
        return;
    }

    fetch(`/smart-kitchen/list-json?event_id=${encodeURIComponent(eventId)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(res => {
            const rows = res.data || [];
            body.innerHTML = renderKitchenList(rows);
        })
        .catch(() => {
            body.innerHTML = '<div class="list-empty">Failed to load activity.</div>';
        });
}

function renderKitchenList(rows) {
    if (!rows.length) return '<div class="list-empty">No meal claims yet.</div>';

    return rows.map(row => {
        // --- NEW: guest rows have no resident/family_member joined ---
        if (row.claimant_type === 'guest') {
            const guestLabel = row.remarks || 'Guest';
            const time = row.distribution_date || '';
            return `
                <div class="list-item">
                    <div class="li-avatar" style="background:linear-gradient(135deg,#f0a500,#c98a1f);color:#fff;">G</div>
                    <div class="li-body">
                        <div class="li-name">${guestLabel}<span class="li-badge">Guest</span></div>
                        <div class="li-sub">Unregistered claim</div>
                    </div>
                    <div class="li-time">${time ? new Date(time).toLocaleTimeString() : ''}</div>
                </div>
            `;
        }
        // --- END NEW ---

        const headName = `${row.head_first_name || ''} ${row.head_last_name || ''}`.trim();
        const isMember = !!row.family_member_name;
        const displayName = isMember ? row.family_member_name : headName;
        const initial = displayName ? displayName.charAt(0).toUpperCase() : '?';
        const subLine = isMember
            ? `${row.relation || 'Family Member'} of ${headName}`
            : (row.household_no ? `Household #${row.household_no}` : 'Head of Family');
        const time = row.distribution_date || '';
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
                <div class="li-time">${time ? new Date(time).toLocaleTimeString() : ''}</div>
            </div>
        `;
    }).join('');
}

function toggleScannerPanel() {
    const scannerCol = document.querySelector('.scanner-col');
    const listPanel = document.querySelector('.list-panel');
    const btn = document.getElementById('scannerToggleBtn');
    const collapsed = scannerCol.classList.toggle('is-collapsed');
    listPanel.classList.toggle('is-expanded', collapsed);
    btn.innerHTML = collapsed ? '<i class="fa-solid fa-eye"></i> Show Scanner' : '<i class="fa-solid fa-eye-slash"></i> Hide Scanner';
    localStorage.setItem('kitchenScannerPanelHidden', collapsed);
}

// ==================== EVENT SELECT ====================
function saveSelectedEvent(eventId) { localStorage.setItem('preferredKitchenEventId', eventId); }
function loadSavedEvent()           { return localStorage.getItem('preferredKitchenEventId'); }
function getSelectedEventId()       { return document.getElementById('eventSelect').value; }

function applySavedEvent() {
    const saved = loadSavedEvent();
    const select = document.getElementById('eventSelect');
    if (saved && select.querySelector(`option[value="${saved}"]`)) select.value = saved;
}

document.getElementById('eventSelect').addEventListener('change', function() {
    saveSelectedEvent(this.value);
    loadList();
    loadStats();
});

// ==================== HARDWARE FOCUS HELPERS ====================
function maintainHardwareFocus() {
    const input = document.getElementById('hardwareScanInput');
    if (currentScannerType === 'hardware' && hardwareActive && !isModalOpen) input.focus();
}

document.addEventListener('click', function(e) {
    if (e.target.id === 'hardwareScanInput' || e.target.closest('#eventSelect')) return;
    if (currentScannerType === 'hardware' && hardwareActive && !isModalOpen) {
        setTimeout(() => document.getElementById('hardwareScanInput').focus(), 10);
    }
});

window.addEventListener('focus', function() {
    if (currentScannerType === 'hardware' && hardwareActive && !isModalOpen) {
        setTimeout(() => document.getElementById('hardwareScanInput').focus(), 100);
    }
});

// ==================== FULLSCREEN SCANNER: AUTO-HIDE SIDEBAR ====================
(function() {
    const body = document.body;
    const wasCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    body.classList.add('sidebar-collapsed');
    window.addEventListener('beforeunload', function() {
        if (!wasCollapsed) body.classList.remove('sidebar-collapsed');
    });
})();

// ==================== LIVE RUNNING CLOCK ====================
let kitchenClockTimer = null;

function tickKitchenClock() {
    const el = document.getElementById('kitchenClockText');
    if (!el) return;
    el.textContent = new Date().toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

function startKitchenClock() {
    tickKitchenClock();
    clearInterval(kitchenClockTimer);
    kitchenClockTimer = setInterval(tickKitchenClock, 1000);
}

// ==================== INIT ====================
window.onload = function() {
    applySavedScannerType();
    applySavedEvent();
    applySavedAdvanceMode();
    loadStats();
    loadList();
    startKitchenPolling();
    startKitchenAutoReload();   // ← add this line
    startKitchenClock();        // ← live running clock in the top bar
};

window.addEventListener('beforeunload', function() {
    if (html5QrcodeScanner) { try { html5QrcodeScanner.clear(); } catch(e) {} }
    if (hardwareTimeout) clearTimeout(hardwareTimeout);
    clearInterval(kitchenPollTimer);
    clearInterval(kitchenAutoReloadTimer);   // ← add this line
    clearInterval(kitchenClockTimer);
});

document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('kitchenScannerPanelHidden') === 'true') toggleScannerPanel();
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

    document.addEventListener('mousedown', function(e) {
        if (isIdleActive()) exitIdleMode()
    })
    document.addEventListener('keydown', function(e) {
        if (isIdleActive() && currentScannerType !== 'hardware') exitIdleMode()
    })
    document.addEventListener('touchstart', function(e) {
        if (isIdleActive()) exitIdleMode()
    })

    setTimeout(window.resetIdleTimer, 2000)

    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !isModalOpen) {
            setTimeout(window.resetIdleTimer, 500)
        }
    })
})();
</script>

<?= $this->endSection() ?>