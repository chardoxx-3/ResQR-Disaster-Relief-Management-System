<?= $this->extend('layout/auth_layout') ?>
<?= $this->section('content') ?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep:   #4a7a26;
  --green-mid:    #77BC3F;
  --green-light:  #99d15f;
  --green-glow:   #b8e48a;
  --orange-deep:  #c96b10;
  --orange-mid:   #F58220;
  --bg:           #f8fafc;
  --surface:      #ffffff;
  --surface2:     #f0fdf4;
  --text-1:       #1e293b;
  --text-2:       #334155;
  --text-3:       #64748b;
  --border:       #e2e8f0;
  --shadow-sm:    0 1px 4px rgba(119,188,63,.08);
  --shadow-md:    0 4px 20px rgba(119,188,63,.14);
  --radius:       14px;
  --radius-sm:    8px;
}

* { box-sizing: border-box; }
body {
  font-family: 'Outfit', sans-serif;
  background: var(--bg);
  color: var(--text-1);
  min-height: 100vh;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 40px 16px 60px;
}

.portal-wrap {
  width: 100%; max-width: 480px;
  animation: fadeUp .45s ease both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* ── Branding ── */
.portal-brand {
  text-align: center;
  margin-bottom: 24px;
}
.brand-logo {
  width: 80px; height: 80px; border-radius: 20px;
  object-fit: contain;
  box-shadow: 0 8px 24px rgba(119,188,63,.25);
  margin-bottom: 14px;
  background: var(--surface);
  border: 1px solid var(--border);
  padding: 6px;
}
.brand-title { font-size: 1.25rem; font-weight: 800; color: var(--text-1); margin: 0 0 4px; }
.brand-sub   { font-size: .75rem; color: var(--text-3); margin: 0; }

/* ── Card ── */
.portal-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow-md);
  overflow: hidden;
}
.card-header-strip {
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  padding: 14px 20px;
  display: flex; align-items: center; gap: 10px;
}
.card-header-strip h6 {
  font-size: .82rem; font-weight: 700; color: #fff; margin: 0;
}
.card-header-strip p {
  font-size: .65rem; color: rgba(255,255,255,.75); margin: 2px 0 0;
}
.card-body { padding: 24px 20px; }

/* ── Form controls ── */
.field-group { margin-bottom: 16px; }
.field-label {
  display: block; font-size: .7rem; font-weight: 700;
  color: var(--text-2); margin-bottom: 6px;
}
.input-wrap {
  display: flex; align-items: center;
  border: 1.5px solid var(--border); border-radius: var(--radius-sm);
  overflow: hidden; transition: border-color .2s, box-shadow .2s;
  background: var(--surface);
}
.input-wrap:focus-within {
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(119,188,63,.12);
}
.input-icon {
  padding: 0 12px; color: var(--text-3); font-size: .85rem;
  background: var(--bg); border-right: 1.5px solid var(--border);
  height: 40px; display: flex; align-items: center; flex-shrink: 0;
}
.input-wrap input {
  flex: 1; border: none; outline: none; padding: 10px 12px;
  font-size: .8rem; font-family: 'Outfit', sans-serif;
  color: var(--text-1); background: transparent;
}
.input-wrap input::placeholder { color: #b0bec5; }

/* ── Submit button ── */
.btn-submit {
  width: 100%; padding: 11px;
  background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color: #fff; border: none; border-radius: var(--radius-sm);
  font-size: .82rem; font-weight: 700; cursor: pointer;
  font-family: 'Outfit', sans-serif;
  box-shadow: 0 4px 14px rgba(119,188,63,.35);
  transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px;
  margin-top: 4px;
}
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(119,188,63,.45); }

/* ── Error flash ── */
.error-flash {
  display: flex; align-items: center; gap: 8px;
  background: #fff1f2; border: 1px solid #fecaca; border-radius: var(--radius-sm);
  padding: 10px 14px; margin-top: 14px;
  font-size: .72rem; color: #c0392b; font-weight: 600;
}

/* ── Help note ── */
.help-note {
  text-align: center; font-size: .68rem; color: var(--text-3);
  margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 5px;
}

/* ── Privacy Notice ── */
.privacy-notice {
  margin-top: 20px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
}
.privacy-header {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px; border-bottom: 1px solid var(--border);
  background: var(--bg); cursor: pointer; user-select: none;
}
.privacy-header-icon {
  width: 28px; height: 28px; border-radius: 7px;
  background: #eff6ff; border: 1px solid #bfdbfe;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem; color: #1d4ed8; flex-shrink: 0;
}
.privacy-header-text { flex: 1; }
.privacy-header-text strong { font-size: .72rem; color: var(--text-1); display: block; }
.privacy-header-text span   { font-size: .62rem; color: var(--text-3); }
.privacy-chevron {
  font-size: .7rem; color: var(--text-3);
  transition: transform .25s;
}
.privacy-chevron.open { transform: rotate(180deg); }

.privacy-body {
  display: none; padding: 16px;
}
.privacy-body.open { display: block; }

.privacy-section { margin-bottom: 14px; }
.privacy-section:last-child { margin-bottom: 0; }
.privacy-section-title {
  font-size: .65rem; font-weight: 800; text-transform: uppercase;
  letter-spacing: .6px; color: var(--green-deep);
  margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
}
.privacy-section-title i { font-size: .72rem; }
.privacy-section p {
  font-size: .7rem; color: var(--text-2); line-height: 1.6; margin: 0 0 6px;
}
.privacy-section p:last-child { margin-bottom: 0; }

.privacy-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px; }
.privacy-tag {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 10px; border-radius: 20px; font-size: .6rem; font-weight: 700;
  background: var(--surface2); color: var(--green-deep); border: 1px solid var(--green-glow);
}

.privacy-law {
  display: flex; align-items: flex-start; gap: 8px;
  background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-sm);
  padding: 10px 12px; margin-top: 10px;
}
.privacy-law i { color: #1d4ed8; font-size: .78rem; margin-top: 2px; flex-shrink: 0; }
.privacy-law p { font-size: .68rem; color: #1d4ed8; margin: 0; line-height: 1.5; }

.privacy-divider { border: none; border-top: 1px solid var(--border); margin: 12px 0; }
</style>

<div class="portal-wrap">

  <!-- Branding -->
  <div class="portal-brand">
    <img src="/uploads/logo.png" alt="Municipality of Mambajao Logo" class="brand-logo"><br>
    <h1 class="brand-title">Resident QR Portal</h1>
    <p class="brand-sub">Municipality of Mambajao, Camiguin</p>
  </div>

  <!-- Search Card -->
  <div class="portal-card">
    <div class="card-header-strip">
      <div>
        <h6>Find Your QR Code</h6>
        <p>Enter your name and birthdate to retrieve your FACED QR code</p>
      </div>
    </div>
    <div class="card-body">
      <form action="/residentportal/verify" method="post">
        <?= csrf_field() ?>

        <div class="field-group">
          <label class="field-label">Full Name / First Name</label>
          <div class="input-wrap">
            <span class="input-icon"><i class="fa-solid fa-user"></i></span>
            <input type="text" name="search_name" placeholder="Enter your complete name" required>
          </div>
        </div>


        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-magnifying-glass"></i> Find My QR Code
        </button>

        <?php if(session()->getFlashdata('error')): ?>
          <div class="error-flash">
            <i class="fa-solid fa-circle-exclamation"></i>
            <?= esc(session()->getFlashdata('error')) ?>
          </div>
        <?php endif; ?>
      </form>

      <div class="help-note">
        <i class="fa-solid fa-circle-info"></i>
        Need help? Visit the registration desk at your evacuation center.
      </div>
    </div>
  </div>

  <!-- Privacy Notice -->
  <div class="privacy-notice">
    <div class="privacy-header" onclick="togglePrivacy(this)">
      <div class="privacy-header-icon">
        <i class="fa-solid fa-shield-halved"></i>
      </div>
      <div class="privacy-header-text">
        <strong>Data Privacy Notice</strong>
        <span>How your personal information is used and protected</span>
      </div>
      <i class="fa-solid fa-chevron-down privacy-chevron"></i>
    </div>
    <div class="privacy-body">

      <div class="privacy-section">
        <div class="privacy-section-title">
          <i class="fa-solid fa-circle-info"></i> Purpose of Data Collection
        </div>
        <p>
          Your personal information — including your name, birthdate, and household details — is collected
          and processed solely for the purpose of relief goods distribution under the
          <strong>Mambajao Relief Goods Distribution Standard Operating Procedure Ordinance of 2025</strong>.
        </p>
        <p>
          This data is used to verify your identity and eligibility as a beneficiary, generate your
          <strong>Family Access Card in Emergencies and Disaster (FACED)</strong> QR code, and record
          your acknowledgment of received relief goods in real time.
        </p>
      </div>

      <hr class="privacy-divider">

      <div class="privacy-section">
        <div class="privacy-section-title">
          <i class="fa-solid fa-database"></i> What Data We Collect
        </div>
        <div class="privacy-tags">
          <span class="privacy-tag"><i class="fa-solid fa-user"></i> Full Name</span>
          <span class="privacy-tag"><i class="fa-solid fa-calendar"></i> Birthdate</span>
          <span class="privacy-tag"><i class="fa-solid fa-house"></i> Household Number</span>
          <span class="privacy-tag"><i class="fa-solid fa-location-dot"></i> Barangay</span>
          <span class="privacy-tag"><i class="fa-solid fa-qrcode"></i> QR Scan Logs</span>
          <span class="privacy-tag"><i class="fa-solid fa-box"></i> Distribution Records</span>
        </div>
      </div>

      <hr class="privacy-divider">

      <div class="privacy-section">
        <div class="privacy-section-title">
          <i class="fa-solid fa-lock"></i> How We Protect Your Data
        </div>
        <p>
          Access to your personal data is strictly limited to authorized personnel of the
          Municipal Disaster Risk Reduction and Management Office (MDRRMO), Municipal Social Welfare
          and Development Office (MSWDO), and the Municipal IT Unit.
        </p>
        <p>
          Records are securely stored with backup copies maintained. No personal information will
          be publicly disclosed without your consent. The municipality's designated Data Protection
          Officer oversees compliance, breach reporting, and corrective measures.
        </p>
      </div>

      <hr class="privacy-divider">

      <div class="privacy-section">
        <div class="privacy-section-title">
          <i class="fa-solid fa-file-contract"></i> Official Use & Audit
        </div>
        <p>
          All digital and QR-based records constitute official municipal records subject to
          internal audit and Commission on Audit (COA) review. Distribution data is consolidated
          and reported for transparency and public disclosure in accordance with applicable laws.
        </p>
        <p>
          Any manipulation, tampering, or unauthorized access to this system constitutes a
          violation of the Ordinance and is subject to penalties.
        </p>
      </div>

      <div class="privacy-law">
        <i class="fa-solid fa-balance-scale"></i>
        <p>
          Your data is processed in full compliance with <strong>Republic Act No. 10173</strong>
          (Data Privacy Act of 2012) and in accordance with the municipal ordinance enacted by
          the Sangguniang Bayan of Mambajao, Camiguin.
        </p>
      </div>

    </div><!-- /privacy-body -->
  </div><!-- /privacy-notice -->

</div><!-- /portal-wrap -->

<script>
function togglePrivacy(header) {
  const body    = header.nextElementSibling;
  const chevron = header.querySelector('.privacy-chevron');
  const isOpen  = body.classList.contains('open');
  body.classList.toggle('open', !isOpen);
  chevron.classList.toggle('open', !isOpen);
}
</script>

<?= $this->endSection() ?>