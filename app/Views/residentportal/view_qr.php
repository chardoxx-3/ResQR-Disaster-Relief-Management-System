<?= $this->extend('layout/auth_layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep:   #4a7a26;
  --green-mid:    #77BC3F;
  --green-glow:   #b8e48a;
  --orange-deep:  #c96b10;
  --bg:           #f8fafc;
  --surface:      #ffffff;
  --surface2:     #f0fdf4;
  --text-1:       #1e293b;
  --text-2:       #334155;
  --text-3:       #64748b;
  --border:       #e2e8f0;
  --radius:       14px;
  --radius-sm:    8px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }
body { font-family: 'Outfit', sans-serif; background: var(--surface); color: var(--text-1); overflow: hidden; }

.split-screen { display: flex; height: 100vh; width: 100%; }

/* ── LEFT: QR Pane ── */
.qr-pane {
  flex: 0 0 38%;
  background: linear-gradient(165deg, var(--surface2), var(--surface));
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 32px; gap: 4px; text-align: center;
}
.brand-logo { width: 56px; height: 56px; border-radius: 14px; object-fit: contain; padding: 4px; background: var(--surface); border: 1px solid var(--border); margin-bottom: 10px; }
.brand-title { font-size: .95rem; font-weight: 800; }
.brand-sub { font-size: .65rem; color: var(--text-3); margin-bottom: 24px; }

.qr-frame {
  background: var(--surface); border: 2px solid var(--border); border-radius: var(--radius);
  padding: 22px; position: relative; box-shadow: 0 4px 20px rgba(119,188,63,.14);
}
.qr-frame img { display: block; border-radius: var(--radius-sm); }
.qr-frame::before, .qr-frame::after, .qr-frame .corner-br, .qr-frame .corner-bl {
  content: ''; position: absolute; width: 18px; height: 18px; border-color: var(--green-mid); border-style: solid;
}
.qr-frame::before  { top:8px; left:8px; border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
.qr-frame::after   { top:8px; right:8px; border-width:3px 3px 0 0; border-radius:0 4px 0 0; }
.qr-frame .corner-br { bottom:8px; right:8px; border-width:0 3px 3px 0; border-radius:0 0 4px 0; }
.qr-frame .corner-bl { bottom:8px; left:8px; border-width:0 0 3px 3px; border-radius:0 0 0 4px; }

.qr-logo-overlay {
  position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
  width: 68px; height: 68px; border-radius: 50%; background: #fff; padding: 5px;
  box-shadow: 0 0 0 4px #fff; z-index: 2; object-fit: contain;
}

.qr-person-name { margin-top: 22px; font-size: 1.4rem; font-weight: 800; }
.qr-hint { font-size: .8rem; color: var(--text-3); margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 5px; }
.verified-badge {
  display: inline-flex; align-items: center; gap: 5px; margin-top: 16px;
  background: var(--surface2); border: 1px solid var(--green-glow); border-radius: 20px;
  padding: 5px 16px; font-size: .78rem; font-weight: 700; color: var(--green-deep);
}
.btn-submit {
  margin-top: 26px; padding: 13px 28px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color: #fff; border: none; border-radius: var(--radius-sm);
  font-size: .9rem; font-weight: 700; cursor: pointer; font-family: 'Outfit', sans-serif;
  box-shadow: 0 4px 14px rgba(119,188,63,.35);
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-submit:hover { transform: translateY(-1px); }

/* ── RIGHT: Info + Guides Pane (only place that scrolls) ── */
.info-pane { flex: 1; overflow-y: auto; display: flex; }
.info-pane-inner { margin: auto; width: 100%; max-width: 920px; padding: 48px 64px; }

.pane-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 10px; }
.pane-header h6 { font-size: 1.3rem; font-weight: 800; }
.pane-header h6 i { color: var(--green-mid); margin-right: 8px; }

.info-section-title { font-size: .82rem; font-weight: 800; text-transform: uppercase; letter-spacing: .6px; color: var(--text-3); margin: 26px 0 14px; display: flex; align-items: center; gap: 6px; }
.info-section-title:first-child { margin-top: 0; }
.info-section-title i { color: var(--green-mid); }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 32px; }
.info-row { display: flex; align-items: baseline; padding: 11px 0; border-bottom: 1px solid var(--border); font-size: 1rem; }
.info-label { color: var(--text-3); font-weight: 600; min-width: 140px; }
.info-val { color: var(--text-1); font-weight: 700; }
.info-val.mono { font-family: 'DM Mono', monospace; font-size: .92rem; }

.step-list { list-style: none; }
.step-list li { display: flex; gap: 10px; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: .9rem; color: var(--text-2); }
.step-list li:last-child { border-bottom: none; }
.step-num { width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0; background: linear-gradient(135deg, var(--green-deep), var(--green-mid)); color: #fff; font-size: .6rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin-top: 1px; }

.safety-header { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border: 1px solid #fed7aa; border-radius: var(--radius-sm); background: #fff7ed; cursor: pointer; margin-top: 10px; }
.safety-header-icon { width: 28px; height: 28px; border-radius: 7px; background: #fff; border: 1px solid #fed7aa; color: var(--orange-deep); display: flex; align-items: center; justify-content: center; font-size: .78rem; flex-shrink: 0; }
.safety-header-text strong { font-size: .74rem; color: #92400e; display: block; }
.safety-header-text span { font-size: .64rem; color: var(--orange-deep); }
.safety-chevron { margin-left: auto; font-size: .7rem; color: var(--orange-deep); transition: transform .25s; }
.safety-chevron.open { transform: rotate(180deg); }
.safety-body { display: none; border: 1px solid var(--border); border-top: none; border-radius: 0 0 var(--radius-sm) var(--radius-sm); }
.safety-body.open { display: block; }

.alert-banner { display: flex; gap: 10px; align-items: flex-start; background: #fff1f2; border: 1px solid #fecaca; border-radius: var(--radius-sm); padding: 10px 14px; margin: 14px; font-size: .7rem; color: #c0392b; line-height: 1.5; }
.alert-banner i { margin-top: 2px; flex-shrink: 0; }

.safety-protocol { padding: 12px 16px; border-bottom: 1px solid var(--border); display: flex; gap: 12px; align-items: flex-start; }
.safety-protocol:last-child { border-bottom: none; }
.sp-icon { width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: .82rem; }
.sp-icon.orange { background: #fff7ed; color: var(--orange-deep); }
.sp-icon.red    { background: #fff1f2; color: #c0392b; }
.sp-icon.blue   { background: #eff6ff; color: #1d4ed8; }
.sp-icon.green  { background: var(--surface2); color: var(--green-deep); }
.sp-icon.amber  { background: #fffbeb; color: #92400e; }
.sp-title { font-size: .75rem; font-weight: 800; margin-bottom: 2px; }
.sp-desc  { font-size: .7rem; color: var(--text-3); line-height: 1.5; }

.exit-link { display: inline-block; margin-top: 20px; font-size: .72rem; color: var(--text-3); text-decoration: none; }
.exit-link:hover { color: var(--green-deep); }

/* ── Mobile: stack + allow scroll ── */
@media (max-width: 900px) {
  body { overflow: auto; }
  .split-screen { flex-direction: column; height: auto; }
  .qr-pane { border-right: none; border-bottom: 1px solid var(--border); padding: 28px 20px; }
  .info-pane { overflow: visible; padding: 24px 20px; }
  .info-grid { grid-template-columns: 1fr; }
}

/* ── Print ── */
@media print {
  body { overflow: visible; }
  .split-screen { display: block; height: auto; }
  .btn-submit, .exit-link { display: none !important; }
}

/* ── Safety Modal ── */
.modal-overlay {
  display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55);
  z-index: 999; align-items: center; justify-content: center; padding: 24px;
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: var(--surface); border-radius: var(--radius);
  width: 100%; max-width: 680px; max-height: 85vh;
  display: flex; flex-direction: column; overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,.25);
  animation: modalIn .25s ease both;
}
@keyframes modalIn { from{opacity:0;transform:translateY(12px) scale(.98)} to{opacity:1;transform:none} }
.modal-header {
  display: flex; align-items: center; gap: 10px;
  padding: 18px 20px; border-bottom: 1px solid var(--border); background: #fff7ed;
}
.modal-close {
  margin-left: auto; width: 32px; height: 32px; border-radius: 50%;
  border: none; background: var(--surface); color: var(--text-3);
  display: flex; align-items: center; justify-content: center; cursor: pointer;
  font-size: .9rem; transition: all .2s;
}
.modal-close:hover { background: var(--border); color: var(--text-1); }
.modal-body { overflow-y: auto; }
</style>

<div class="split-screen">

  <!-- LEFT: QR -->
  <div class="qr-pane">
    <img src="/uploads/logo.png" alt="Municipality of Mambajao" class="brand-logo">
    <div class="brand-title">Your FACED QR Code</div>
    <div class="brand-sub">Municipality of Mambajao, Camiguin</div>

    <div class="qr-frame">
      <img class="qr-logo-overlay" src="/uploads/logo.png" alt="System Logo">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=340x340&ecc=H&margin=8&data=<?= urlencode($resident['qr_code_token']) ?>"
           alt="Resident QR Code" width="340" height="340">
      <span class="corner-br"></span>
      <span class="corner-bl"></span>
    </div>

    <div class="qr-person-name"><?= esc(trim($resident['first_name'] . ' ' . $resident['last_name'] . ' ' . ($resident['name_extension'] ?? ''))) ?></div>
    <p class="qr-hint"><i class="fa-solid fa-mobile-screen"></i> Save or print for distribution</p>
    <span class="verified-badge"><i class="fa-solid fa-circle-check"></i> Verified &amp; Ready for Claiming</span>

    <button onclick="window.print()" class="btn-submit">
      <i class="fa-solid fa-print"></i> Download or Print
    </button>
  </div>

  <!-- RIGHT: Info + Guides -->
<!-- RIGHT: Info + Guides -->
  <div class="info-pane">
   <div class="info-pane-inner">

    <div class="pane-header">
      <h6><i class="fa-solid fa-id-card"></i>Family Access Card in Emergencies and Disaster</h6>
    </div>


    <div class="info-section-title"><i class="fa-solid fa-list-check"></i> How to Use Your QR Code</div>
    <ol class="step-list">
      <li><span class="step-num">1</span><span>Save this QR code to your phone gallery or print a copy to keep with you.</span></li>
      <li><span class="step-num">2</span><span>Go to the designated <strong>Distribution Point</strong> in your barangay at the scheduled time.</span></li>
      <li><span class="step-num">3</span><span>Present this QR code to the relief distributor for scanning. Only the <strong>household head or an authorized representative</strong> may claim.</span></li>
      <li><span class="step-num">4</span><span>Wait for the scanner to confirm your eligibility. Each household may only claim <strong>once per distribution</strong>.</span></li>
      <li><span class="step-num">5</span><span>If you lose your FACED card or QR code, report to the registration desk. Replacement is charged <strong>₱50.00</strong>.</span></li>
    </ol>

<div class="safety-header" onclick="openSafetyModal()">
      <div class="safety-header-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="safety-header-text">
        <strong>Disaster Safety Protocols</strong>
        <span>Important guidelines during emergencies — tap to view</span>
      </div>
      <i class="fa-solid fa-up-right-from-square" style="margin-left:auto;font-size:.75rem;color:var(--orange-deep);"></i>
    </div>

<a href="/" class="exit-link"><i class="fa-solid fa-arrow-left me-1"></i> Exit Portal</a>
   </div>
  </div>

</div><!-- /split-screen -->

<!-- Disaster Safety Protocols Modal -->
<div class="modal-overlay" id="safetyModal" onclick="if(event.target===this) closeSafetyModal()">
  <div class="modal-box">
    <div class="modal-header">
      <div class="safety-header-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="safety-header-text">
        <strong>Disaster Safety Protocols</strong>
        <span>Important guidelines during emergencies</span>
      </div>
      <button class="modal-close" onclick="closeSafetyModal()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <div class="alert-banner">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>During a disaster, your safety is the top priority. Please follow all instructions from barangay officials, PNP, and BFP personnel at all times.</span>
      </div>
      <div class="safety-protocol"><div class="sp-icon orange"><i class="fa-solid fa-person-running"></i></div><div><div class="sp-title">Evacuate Immediately When Ordered</div><div class="sp-desc">Follow pre-emptive evacuation orders issued by your Barangay LDRRMC. Proceed to your designated evacuation center calmly and bring your FACED card. Do not wait for floodwaters to rise or structures to weaken.</div></div></div>
      <div class="safety-protocol"><div class="sp-icon blue"><i class="fa-solid fa-location-pin"></i></div><div><div class="sp-title">Know Your Distribution Point</div><div class="sp-desc">Distribution schedules and locations are announced publicly through barangay channels. Go only to your assigned distribution point. Unauthorized or separate distributions not coordinated with the MRDC are prohibited.</div></div></div>
      <div class="safety-protocol"><div class="sp-icon green"><i class="fa-solid fa-users-line"></i></div><div><div class="sp-title">Follow Queue and Crowd Guidelines</div><div class="sp-desc">PNP and Barangay Peacekeeping Action Teams (BPATs) are deployed at distribution points. Respect the queue, avoid crowding, and follow security personnel's instructions. Priority lanes are available for PWDs, pregnant women, senior citizens, and lactating mothers.</div></div></div>
      <div class="safety-protocol"><div class="sp-icon amber"><i class="fa-solid fa-box-open"></i></div><div><div class="sp-title">One Claim Per Household</div><div class="sp-desc">Each FACED QR code is linked to one registered household. Attempting to claim multiple times or for another household is a violation of the Mambajao Relief Goods Distribution Ordinance and is subject to penalties.</div></div></div>
      <div class="safety-protocol"><div class="sp-icon red"><i class="fa-solid fa-ban"></i></div><div><div class="sp-title">Report Violations Immediately</div><div class="sp-desc">Any acts of hoarding, diversion, politicking, or demanding payment in exchange for relief goods are strictly prohibited. Report violations immediately to the nearest barangay official, PNP, or MDRRMO personnel.</div></div></div>
      <div class="safety-protocol"><div class="sp-icon blue"><i class="fa-solid fa-phone-volume"></i></div><div><div class="sp-title">Emergency Contacts</div><div class="sp-desc">Keep the following on hand: your <strong>Barangay Hotline</strong>, <strong>Mambajao MPS (PNP)</strong>, <strong>Bureau of Fire Protection (BFP)</strong>, and the <strong>MDRRMO</strong>. In life-threatening situations, call <strong>911</strong> immediately.</div></div></div>
    </div>
  </div>
</div>

<script>
function openSafetyModal() {
  document.getElementById('safetyModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeSafetyModal() {
  document.getElementById('safetyModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeSafetyModal();
});
</script>

<?= $this->endSection() ?>