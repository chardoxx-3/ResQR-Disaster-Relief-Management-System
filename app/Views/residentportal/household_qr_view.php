<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Household QR Code - <?= esc($household_no) ?></title>
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
      width: 60px; height: 60px; border-radius: 50%; background: #fff; padding: 5px;
      box-shadow: 0 0 0 4px #fff; z-index: 2; object-fit: contain;
    }

    .qr-person-name { margin-top: 22px; font-size: 1.3rem; font-weight: 800; font-family: 'DM Mono', monospace; }
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

    /* ── RIGHT: Info Pane (only place that scrolls) ── */
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

    /* ── Family Members ── */
    .member-row {
      display: flex; align-items: center; justify-content: space-between; gap: 10px;
      padding: 12px 0; border-bottom: 1px solid var(--border); font-size: .92rem;
    }
    .member-row:last-child { border-bottom: none; }
    .member-left { display: flex; flex-direction: column; gap: 2px; }
    .member-name { font-weight: 700; color: var(--text-1); font-size: .95rem; }
    .member-meta { font-size: .74rem; color: var(--text-3); }
    .btn-qr-link {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 6px 14px; border-radius: 20px; font-size: .74rem; font-weight: 700;
      background: var(--surface2); border: 1px solid var(--green-glow);
      color: var(--green-deep); text-decoration: none; white-space: nowrap;
      transition: all .2s;
    }
    .btn-qr-link:hover { background: var(--green-glow); color: var(--green-deep); }

    .empty-state { text-align: center; padding: 20px; color: var(--text-3); font-size: .85rem; }
    .empty-state i { font-size: 1.4rem; margin-bottom: 6px; display: block; color: var(--border); }

    .footer-links { display: flex; align-items: center; gap: 18px; margin-top: 24px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 10px 18px; border-radius: var(--radius-sm);
      border: 1.5px solid var(--border); background: var(--surface);
      font-size: .8rem; font-weight: 600; color: var(--text-2);
      text-decoration: none; transition: all .2s; font-family: 'Outfit', sans-serif;
    }
    .btn-back:hover { border-color: var(--green-mid); color: var(--green-deep); background: var(--surface2); }
    .exit-link { display: inline-block; font-size: .72rem; color: var(--text-3); text-decoration: none; }
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
      .btn-submit, .btn-back, .exit-link, .btn-qr-link { display: none !important; }
    }
  </style>
</head>
<body>

<div class="split-screen">

  <!-- LEFT: QR -->
  <div class="qr-pane">
    <img src="/uploads/logo.png" alt="Municipality of Mambajao" class="brand-logo">
    <div class="brand-title">Household QR Code</div>
    <div class="brand-sub">Municipality of Mambajao, Camiguin</div>

    <div class="qr-frame">
      <img class="qr-logo-overlay" src="/uploads/logo.png" alt="System Logo">
      <?php if (!empty($household_qr_token)): ?>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=340x340&ecc=H&margin=8&data=<?= urlencode($household_qr_token) ?>"
             alt="Household QR Code" width="340" height="340">
      <?php else: ?>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=340x340&ecc=H&margin=8&data=<?= urlencode($household_no) ?>"
             alt="Household QR Code" width="340" height="340">
      <?php endif; ?>
      <span class="corner-br"></span>
      <span class="corner-bl"></span>
    </div>

    <div class="qr-person-name">#<?= esc($household_no) ?></div>
    <p class="qr-hint"><i class="fa-solid fa-mobile-screen"></i> Scan to verify household at distribution</p>
    <span class="verified-badge"><i class="fa-solid fa-house"></i> Head: <?= esc($head_name) ?></span>

    <button onclick="window.print()" class="btn-submit">
      <i class="fa-solid fa-print"></i> Print QR Code
    </button>
  </div>

  <!-- RIGHT: Info -->
  <div class="info-pane">
   <div class="info-pane-inner">

    <div class="pane-header">
      <h6><i class="fa-solid fa-house"></i>Family Access Card in Emergencies and Disaster</h6>
    </div>

    <div class="info-section-title"><i class="fa-solid fa-house"></i> Household Summary</div>
    <div class="info-grid">
      <div class="info-row"><span class="info-label">Household No.</span><span class="info-val mono"><?= esc($household_no) ?></span></div>
      <div class="info-row"><span class="info-label">Head of Family</span><span class="info-val"><?= esc($head_name) ?></span></div>
      <div class="info-row"><span class="info-label">Total Members</span><span class="info-val"><?= esc($total_members) ?> member<?= $total_members != 1 ? 's' : '' ?></span></div>
      <div class="info-row"><span class="info-label">Barangay</span><span class="info-val"><?= esc($resident['barangay'] ?? 'N/A') ?></span></div>
      <div class="info-row"><span class="info-label">Contact Number</span><span class="info-val mono"><?= esc($resident['contact_number'] ?? 'N/A') ?></span></div>
    </div>

    <div class="info-section-title"><i class="fa-solid fa-users"></i> Family Members</div>
    <?php if (!empty($family_members)): ?>
      <?php foreach ($family_members as $member): ?>
      <div class="member-row">
        <div class="member-left">
          <span class="member-name"><?= esc($member['name'] ?? '') ?></span>
          <span class="member-meta">
            <?= esc($member['relation'] ?? '') ?><?= !empty($member['age']) ? ' · ' . $member['age'] . ' yrs' : '' ?>
          </span>
        </div>
        <?php if (!empty($member['qr_code_token'])): ?>
        <a href="/residentportal/view-member-qr/<?= $resident['id'] ?>/<?= $member['id'] ?>"
           class="btn-qr-link" target="_blank">
          <i class="fa-solid fa-qrcode"></i> View QR
        </a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">
        <i class="fa-solid fa-user-slash"></i>
        No additional family members registered.
      </div>
    <?php endif; ?>

    <div class="footer-links">
      <a href="/beneficiaries" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
      <a href="/" class="exit-link">Exit Portal</a>
    </div>

   </div>
  </div>

</div><!-- /split-screen -->

</body>
</html>