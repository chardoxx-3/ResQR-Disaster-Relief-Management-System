<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Family Member QR Code - <?= esc($full_name) ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    :root {
      --green-deep:   #4a7a26;
      --green-mid:    #77BC3F;
      --green-glow:   #b8e48a;
      --orange-deep:  #c96b10;
      --orange-mid:   #f97316;
      --bg:           #f8fafc;
      --surface:      #ffffff;
      --surface2:     #fff7ed;
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

    /* ── LEFT: QR Pane (orange-tinted to distinguish from household) ── */
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
      padding: 22px; position: relative; box-shadow: 0 4px 20px rgba(201,107,16,.14);
    }
    .qr-frame img { display: block; border-radius: var(--radius-sm); }
    .qr-frame::before, .qr-frame::after, .qr-frame .corner-br, .qr-frame .corner-bl {
      content: ''; position: absolute; width: 18px; height: 18px; border-color: var(--orange-mid); border-style: solid;
    }
    .qr-frame::before  { top:8px; left:8px; border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
    .qr-frame::after   { top:8px; right:8px; border-width:3px 3px 0 0; border-radius:0 4px 0 0; }
    .qr-frame .corner-br { bottom:8px; right:8px; border-width:0 3px 3px 0; border-radius:0 0 4px 0; }
    .qr-frame .corner-bl { bottom:8px; left:8px; border-width:0 0 3px 3px; border-radius:0 0 0 4px; }

    .qr-logo-overlay {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 60px; height: 60px; border-radius: 50%; background: #fff; padding: 5px;
      box-shadow: 0 0 0 4px #fff; z-index: 2; object-fit: contain;
    }

    .qr-person-name { margin-top: 22px; font-size: 1.4rem; font-weight: 800; }
    .qr-hint { font-size: .8rem; color: var(--text-3); margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 5px; }
    .verified-badge {
      display: inline-flex; align-items: center; gap: 5px; margin-top: 16px;
      background: var(--surface2); border: 1px solid #fed7aa; border-radius: 20px;
      padding: 5px 16px; font-size: .78rem; font-weight: 700; color: var(--orange-deep);
    }
    .btn-submit {
      margin-top: 26px; padding: 13px 28px;
      background: linear-gradient(135deg, var(--orange-deep), var(--orange-mid));
      color: #fff; border: none; border-radius: var(--radius-sm);
      font-size: .9rem; font-weight: 700; cursor: pointer; font-family: 'Outfit', sans-serif;
      box-shadow: 0 4px 14px rgba(201,107,16,.32);
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover { transform: translateY(-1px); }

    /* ── RIGHT: Info Pane (only place that scrolls) ── */
    .info-pane { flex: 1; overflow-y: auto; display: flex; }
    .info-pane-inner { margin: auto; width: 100%; max-width: 920px; padding: 48px 64px; }

    .pane-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 10px; }
    .pane-header h6 { font-size: 1.3rem; font-weight: 800; }
    .pane-header h6 i { color: var(--orange-mid); margin-right: 8px; }

    .info-section-title { font-size: .82rem; font-weight: 800; text-transform: uppercase; letter-spacing: .6px; color: var(--text-3); margin: 26px 0 14px; display: flex; align-items: center; gap: 6px; }
    .info-section-title:first-child { margin-top: 0; }
    .info-section-title i { color: var(--orange-mid); }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 32px; }
    .info-row { display: flex; align-items: baseline; padding: 11px 0; border-bottom: 1px solid var(--border); font-size: 1rem; }
    .info-label { color: var(--text-3); font-weight: 600; min-width: 140px; }
    .info-val { color: var(--text-1); font-weight: 700; }
    .info-val.mono { font-family: 'DM Mono', monospace; font-size: .92rem; }

    .household-ref {
      display: inline-flex; align-items: center; gap: 6px; margin-bottom: 8px;
      font-size: .8rem; color: var(--text-3);
    }
    .household-ref b { color: var(--text-1); font-family: 'DM Mono', monospace; }

    .footer-links { display: flex; align-items: center; gap: 18px; margin-top: 24px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 10px 18px; border-radius: var(--radius-sm);
      border: 1.5px solid var(--border); background: var(--surface);
      font-size: .8rem; font-weight: 600; color: var(--text-2);
      text-decoration: none; transition: all .2s; font-family: 'Outfit', sans-serif;
    }
    .btn-back:hover { border-color: var(--orange-mid); color: var(--orange-deep); background: var(--surface2); }
    .exit-link { display: inline-block; font-size: .72rem; color: var(--text-3); text-decoration: none; }
    .exit-link:hover { color: var(--orange-deep); }

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
      .btn-submit, .btn-back, .exit-link { display: none !important; }
    }
  </style>
</head>
<body>

<div class="split-screen">

  <!-- LEFT: QR -->
  <div class="qr-pane">
    <img src="/uploads/logo.png" alt="Municipality of Mambajao" class="brand-logo">
    <div class="brand-title">Family Member QR Code</div>
    <div class="brand-sub">Municipality of Mambajao, Camiguin</div>

    <div class="qr-frame">
      <img class="qr-logo-overlay" src="/uploads/logo.png" alt="System Logo">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=340x340&ecc=H&margin=8&data=<?= urlencode($qr_token) ?>"
           alt="QR Code for <?= esc($full_name) ?>" width="340" height="340">
      <span class="corner-br"></span>
      <span class="corner-bl"></span>
    </div>

    <div class="qr-person-name"><?= esc($full_name) ?></div>
    <p class="qr-hint"><i class="fa-solid fa-mobile-screen"></i> Scan to verify identity at distribution</p>
    <span class="verified-badge"><i class="fa-solid fa-user-tag"></i> <?= esc($member['relation'] ?? 'Family Member') ?></span>

    <button onclick="window.print()" class="btn-submit">
      <i class="fa-solid fa-print"></i> Print QR Code
    </button>
  </div>

  <!-- RIGHT: Info -->
  <div class="info-pane">
   <div class="info-pane-inner">

    <div class="pane-header">
      <h6><i class="fa-solid fa-id-card"></i>Family Member — FACED QR Code</h6>
    </div>

    <div class="household-ref">
      <i class="fa-solid fa-house"></i> Household <b><?= esc($household_no) ?></b> &mdash; <?= esc($head_name) ?>
    </div>

    <div class="info-section-title"><i class="fa-solid fa-user"></i> Member Information</div>
    <div class="info-grid">
      <div class="info-row"><span class="info-label">Full Name</span><span class="info-val"><?= esc($full_name) ?></span></div>
      <div class="info-row"><span class="info-label">Relationship</span><span class="info-val"><?= esc($member['relation'] ?? 'N/A') ?></span></div>
      <div class="info-row"><span class="info-label">Birthdate</span><span class="info-val"><?= !empty($member['birthdate']) ? date('F d, Y', strtotime($member['birthdate'])) : 'N/A' ?></span></div>
      <div class="info-row"><span class="info-label">Age</span><span class="info-val"><?= esc($member['age'] ?? 'N/A') ?></span></div>
      <div class="info-row"><span class="info-label">Sex</span><span class="info-val"><?= esc($member['sex'] ?? 'N/A') ?></span></div>
      <div class="info-row"><span class="info-label">Household No.</span><span class="info-val mono"><?= esc($household_no) ?></span></div>
    </div>

    <div class="footer-links">
      <a href="/beneficiaries" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
      <a href="/" class="exit-link">Exit Portal</a>
    </div>

   </div>
  </div>

</div><!-- /split-screen -->

</body>
</html>