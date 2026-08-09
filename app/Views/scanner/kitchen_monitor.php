<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

:root {
  --green-deep:   #4a7a26;
  --green-mid:    #77BC3F;
  --green-light:  #99d15f;
  --green-glow:   #b8e48a;
  --orange-deep:  #c96b10;
  --orange-mid:   #F58220;
  --orange-light: #f9a75a;
  --amber:        #f5a623;
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

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* ── Page header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.2px; }
.page-title p  { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }
.page-actions  { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

.live-pill {
  display:inline-flex; align-items:center; gap:6px;
  background:var(--surface2); border:1px solid var(--green-glow);
  color:var(--green-deep); font-size:.65rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.5px;
  padding:5px 12px; border-radius:20px;
}
.live-dot {
  width:7px; height:7px; border-radius:50%; background:var(--green-mid);
  animation:pulse 1.4s infinite;
}
@keyframes pulse {
  0%   { box-shadow:0 0 0 0 rgba(119,188,63,.5); }
  70%  { box-shadow:0 0 0 6px rgba(119,188,63,0); }
  100% { box-shadow:0 0 0 0 rgba(119,188,63,0); }
}

/* ── KPI Cards ── */
.kpi-grid {
  display:grid; grid-template-columns:repeat(4,1fr); gap:12px;
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
@media(max-width:900px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:500px){ .kpi-grid{ grid-template-columns:1fr; } }

.kpi-card {
  background:var(--surface); border-radius:var(--radius);
  padding:14px 16px; border:1px solid var(--border);
  box-shadow:var(--shadow-sm); overflow:hidden;
  transition:box-shadow .2s, transform .2s;
}
.kpi-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.kpi-card.kpi-hero {
  background:linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 100%);
  border-color:transparent;
}
.kpi-icon {
  width:34px; height:34px; border-radius:8px;
  display:flex; align-items:center; justify-content:center;
  font-size:.85rem; margin-bottom:8px;
}
.kpi-hero .kpi-icon { background:rgba(255,255,255,.2); color:#fff; }
.kpi-icon.green  { background:#f0fdf4; color:var(--green-deep); }
.kpi-icon.orange { background:#fff7ed; color:var(--orange-deep); }
.kpi-icon.blue   { background:#eff6ff; color:#1d4ed8; }
.kpi-label { font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--text-3); margin-bottom:2px; }
.kpi-hero .kpi-label { color:rgba(255,255,255,.75); }
.kpi-val { font-size:1.6rem; font-weight:800; line-height:1; color:var(--text-1); font-family:'DM Mono',monospace; }
.kpi-hero .kpi-val { color:#fff; }

/* ── Filter bar ── */
.filter-bar {
  background:var(--surface2); border:1px solid var(--border); border-top:none;
  padding:12px 20px; display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end;
}
.filter-group { display:flex; flex-direction:column; gap:3px; }
.filter-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--text-3); }
.filter-select {
  padding:6px 28px 6px 10px; border:1.5px solid var(--border); border-radius:8px;
  font-size:.72rem; font-family:'Outfit',sans-serif;
  background:var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2364748b'/%3E%3C/svg%3E") no-repeat right 8px center;
  color:var(--text-1); transition:border-color .2s; appearance:none;
}
.filter-select:focus { outline:none; border-color:var(--green-mid); box-shadow:0 0 0 3px rgba(119,188,63,.1); }

/* ── Table card ── */
.table-card {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); overflow:hidden;
}
.table-card-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;
  padding:12px 20px; border-bottom:1px solid var(--border);
}
.tch-title { font-size:.78rem; font-weight:700; color:var(--text-1); display:flex; align-items:center; gap:6px; }
.tch-title i { color:var(--green-mid); }
.records-badge {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  background:var(--surface2); color:var(--green-deep);
  border:1px solid var(--green-glow); border-radius:20px; padding:3px 10px;
}

/* ── Table ── */
.table-scroll { overflow-x:auto; }
.rt { width:100%; border-collapse:collapse; font-size:.7rem; }
.rt thead th {
  background:var(--bg); padding:8px 10px; text-align:left;
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.55px;
  color:var(--text-3); border-bottom:1px solid var(--border); white-space:nowrap;
}
.rt thead th:first-child { padding-left:20px; }
.rt thead th:last-child  { padding-right:20px; }
.rt tbody tr { transition:background .15s; }
.rt tbody tr:hover { background:var(--surface2); }
.rt td { padding:9px 10px; border-bottom:1px solid var(--border); vertical-align:middle; }
.rt td:first-child { padding-left:20px; }
.rt td:last-child  { padding-right:20px; }

/* ── Avatar ── */
.av {
  width:30px; height:30px; border-radius:9px;
  background:linear-gradient(135deg,var(--green-light),var(--green-mid));
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:.7rem; font-weight:700; flex-shrink:0;
}
.resident-chip { display:flex; align-items:center; gap:8px; }
.res-name { font-weight:700; color:var(--text-1); font-size:.72rem; }
.res-sub  { font-size:.6rem; color:var(--text-3); }

.hh-badge {
  font-family:'DM Mono',monospace; font-size:.62rem;
  background:var(--bg); border:1px solid var(--border);
  border-radius:5px; padding:2px 7px; color:var(--text-2);
}

.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 8px; border-radius:20px; font-size:.6rem; font-weight:600;
}
.badge2.green   { background:#f0fdf4; color:var(--green-deep); }
.badge2.orange  { background:#fff7ed; color:var(--orange-deep); }
.badge2.blue    { background:#eff6ff; color:#1d4ed8; }
.badge2.neutral { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }

.empty-state { text-align:center; padding:40px 20px; color:var(--text-3); }
.empty-state i { font-size:2rem; display:block; margin-bottom:8px; opacity:.3; }
.empty-state p { font-size:.75rem; margin:0; }

.row-fresh { animation: rowFlash 1.6s ease; }
@keyframes rowFlash {
  0%   { background: var(--surface2); }
  100% { background: transparent; }
}
</style>

<div class="page-wrap">

  <!-- ── Page Header ── -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-kitchen-set me-2" style="color:var(--green-mid)"></i>Smart Mobile Kitchen — Monitoring</h5>
      <p>
        <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--green-mid);margin-right:5px;vertical-align:middle"></span>
        Live view of meal claims as they're scanned
      </p>
    </div>
    <div class="page-actions">
      <span class="live-pill"><span class="live-dot"></span> Live</span>
      <a href="/smart-kitchen/scanner" class="btn-outline-c">
        <i class="fa-solid fa-camera"></i> Open Scanner
      </a>
    </div>
  </div>

  <!-- ── KPI Cards ── -->
  <div class="kpi-grid">
    <div class="kpi-card kpi-hero">
      <div class="kpi-icon"><i class="fa-solid fa-bowl-food"></i></div>
      <div class="kpi-label">Meals Claimed Today</div>
      <div class="kpi-val" id="kmTodayCount">—</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon green"><i class="fa-solid fa-user"></i></div>
      <div class="kpi-label">Family Heads</div>
      <div class="kpi-val" id="kmHeadCount">—</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon orange"><i class="fa-solid fa-people-group"></i></div>
      <div class="kpi-label">Family Members</div>
      <div class="kpi-val" id="kmMemberCount">—</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon blue"><i class="fa-solid fa-clock"></i></div>
      <div class="kpi-label">Last Updated</div>
      <div class="kpi-val" id="kmLastUpdated" style="font-size:.95rem;">—</div>
    </div>
  </div>

  <!-- ── Filter Bar ── -->
  <div class="filter-bar">
    <div class="filter-group">
      <span class="filter-label">Event</span>
      <select id="kmEventSelect" class="filter-select" style="min-width:220px;">
        <option value="">All Active Events</option>
        <?php foreach ($active_events as $ev): ?>
          <option value="<?= esc($ev['id']) ?>"><?= esc($ev['event_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="filter-group">
  <span class="filter-label">Date</span>
  <input type="date" id="kmDateSelect" class="filter-select" style="min-width:150px;" value="">
</div>
<div class="filter-group" style="justify-content:flex-end;">
  <button type="button" class="btn-outline-c" id="kmTodayBtn" style="padding:6px 12px;">
    <i class="fa-solid fa-calendar-day"></i> Today
  </button>
</div>
  </div>
  

  <!-- ── Live Table ── -->
  <div class="table-card">
    <div class="table-card-header">
      <div class="tch-title">
        <i class="fa-solid fa-list"></i>
        Live Activity
      </div>
      <span class="records-badge" id="kmRecordsBadge">0 records</span>
    </div>
    <div class="table-scroll">
      <table class="rt" id="kmTable">
        <thead>
          <tr>
            <th>Claimant</th>
            <th>Type</th>
            <th>Household</th>
            <th>Barangay</th>
            <th>Distributor</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody id="kmTableBody">
          <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Loading...</p></div></td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Live Claim Notification Modal -->
<div class="modal fade" id="claimNotifyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius); border:none; overflow:hidden;">
      <div class="modal-body text-center py-4" id="claimNotifyBody"></div>
    </div>
  </div>
</div>

<script>
(function() {
    const eventSelect   = document.getElementById('kmEventSelect');
    const tableBody      = document.getElementById('kmTableBody');
    const todayCountEl   = document.getElementById('kmTodayCount');
    const headCountEl    = document.getElementById('kmHeadCount');
    const memberCountEl  = document.getElementById('kmMemberCount');
    const lastUpdatedEl  = document.getElementById('kmLastUpdated');
    const recordsBadgeEl = document.getElementById('kmRecordsBadge');

    let knownIds = new Set();
    let firstLoad = true;

    function initials(name) {
        if (!name) return '?';
        return name.trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();
    }

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"']/g, m => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        })[m]);
    }

function renderRows(rows) {
    if (!rows || rows.length === 0) {
        tableBody.innerHTML = `
            <tr><td colspan="6">
                <div class="empty-state">
                    <i class="fa-solid fa-bowl-food"></i>
                    <p>No meals claimed yet.</p>
                </div>
            </td></tr>`;
        recordsBadgeEl.textContent = '0 records';
        return;
    }

    recordsBadgeEl.textContent = rows.length + ' record' + (rows.length === 1 ? '' : 's');

    let headCount = 0, memberCount = 0, guestCount = 0;
    const newRows = []; // NEW: collect rows added since last poll

    tableBody.innerHTML = rows.map(row => {
        // --- NEW: guest rows have no resident/family_member joined ---
        const isGuest = row.claimant_type === 'guest';
        if (isGuest) guestCount++;
        // --- END NEW ---

        const isMember = row.claimant_type === 'family_member';
        if (!isGuest) { if (isMember) memberCount++; else headCount++; }

        const claimantName = isGuest
            ? (row.remarks || 'Guest')
            : (isMember ? row.family_member_name : (row.head_first_name + ' ' + row.head_last_name));
        const photo = isMember ? row.member_photo : row.head_photo;

        const avatar = isGuest
            ? `<div class="av" style="background:linear-gradient(135deg,#f0a500,#c98a1f); color:#fff;">${initials(claimantName)}</div>`
            : (photo
                ? `<a href="/${escapeHtml(photo)}" target="_blank" style="text-decoration:none;">
                     <div class="av" style="background:none; overflow:hidden; border:1px solid var(--border);">
                       <img src="/${escapeHtml(photo)}" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                     </div>
                   </a>`
                : `<div class="av">${initials(claimantName)}</div>`);

        const badge = isGuest
            ? `<span class="badge2 orange"><i class="fa-solid fa-user-group" style="font-size:.55rem"></i> Guest</span>`
            : (isMember
                ? `<span class="badge2 orange"><i class="fa-solid fa-people-group" style="font-size:.55rem"></i> Family Member</span>`
                : `<span class="badge2 green"><i class="fa-solid fa-user" style="font-size:.55rem"></i> Head</span>`);

        const time = row.distribution_date
            ? new Date(row.distribution_date.replace(' ', 'T')).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})
            : '—';

        const isNew = !knownIds.has(row.id) && !firstLoad;
        if (isNew) newRows.push({ claimantName, photo, isGuest, isMember, time }); // NEW

        return `
            <tr class="${isNew ? 'row-fresh' : ''}">
                <td>
                    <div class="resident-chip">
                        ${avatar}
                        <div>
                            <div class="res-name">${escapeHtml(claimantName)}</div>
                            ${isMember && !isGuest ? `<div class="res-sub">${escapeHtml(row.relation || '')}</div>` : ''}
                        </div>
                    </div>
                </td>
                <td>${badge}</td>
                <td><span class="hh-badge">${isGuest ? '—' : escapeHtml(row.household_no || '—')}</span></td>
                <td><span class="badge2 neutral">${isGuest ? '—' : escapeHtml(row.barangay || '—')}</span></td>
                <td><span class="mono" style="font-size:.65rem;color:var(--text-3)">${escapeHtml(row.distributor_name || '—')}</span></td>
                <td><span class="mono" style="font-size:.65rem;">${time}</span></td>
            </tr>
        `;
    }).join('');

    headCountEl.textContent = headCount;
    memberCountEl.textContent = memberCount;
    knownIds = new Set(rows.map(r => r.id));
    firstLoad = false;

    // NEW: pop a live notification for freshly-arrived claims
    if (newRows.length) newRows.forEach(queueClaimModal);
}

// ==================== LIVE CLAIM POPUP ====================
let claimModalQueue = [];
let claimModalBusy = false;
let claimModalInstance = null;

function queueClaimModal(nr) {
    claimModalQueue.push(nr);
    if (!claimModalBusy) processClaimModalQueue();
}

function processClaimModalQueue() {
    if (!claimModalQueue.length) { claimModalBusy = false; return; }
    claimModalBusy = true;
    showClaimModal(claimModalQueue.shift());
}

function showClaimModal({ claimantName, photo, isGuest, time, status }) {
    const initial = initials(claimantName);
    const isDenied = status === 'denied';

    const ringColor = isDenied ? '#dc3545' : 'var(--green-mid)';
    const gradient  = isDenied ? '#dc3545,#a71d2a' : (isGuest ? '#f0a500,#c98a1f' : 'var(--green-light),var(--green-mid)');

    const avatarHTML = (!isGuest && photo)
        ? `<img src="/${escapeHtml(photo)}" style="width:84px;height:84px;border-radius:50%;object-fit:cover;border:4px solid ${ringColor};">`
        : `<div style="width:84px;height:84px;border-radius:50%;margin:0 auto;background:linear-gradient(135deg,${gradient});display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.6rem;font-weight:700;">${initial}</div>`;

    document.getElementById('claimNotifyBody').innerHTML = isDenied ? `
        <i class="fa-solid fa-triangle-exclamation text-danger fa-2x mb-2"></i>
        <h5 class="fw-bold text-danger mb-3">Already Claimed!</h5>
        <div class="mb-2">${avatarHTML}</div>
        <div class="fw-bold" style="font-size:1rem;">${escapeHtml(claimantName)}</div>
        <div class="text-danger" style="font-size:.7rem;">Duplicate attempt • ${time}</div>
    ` : `
        <i class="fa-solid fa-circle-check text-success fa-2x mb-2"></i>
        <h5 class="fw-bold text-success mb-3">Meal Claimed</h5>
        <div class="mb-2">${avatarHTML}</div>
        <div class="fw-bold" style="font-size:1rem;">${escapeHtml(claimantName)}</div>
        <div class="text-muted" style="font-size:.7rem;">${isGuest ? 'Guest' : 'Registered'} • ${time}</div>
    `;

    if (!claimModalInstance) {
        claimModalInstance = new bootstrap.Modal(document.getElementById('claimNotifyModal'));
        document.getElementById('claimNotifyModal').addEventListener('hidden.bs.modal', processClaimModalQueue);
    }
    claimModalInstance.show();
    setTimeout(() => claimModalInstance.hide(), 2500);
}

// ==================== DENIED / DUPLICATE-ATTEMPT POLLING ====================
let knownActivityIds = new Set();
let firstActivityLoad = true;

function pollRecentActivity() {
    const eventId = eventSelect.value;
    const date = dateSelect.value || todayStr();

    const params = new URLSearchParams();
    if (eventId) params.set('event_id', eventId);
    params.set('date', date);

    fetch('/smart-kitchen/recent-activity?' + params.toString())
        .then(r => r.json())
        .then(data => {
            const rows = data.data || [];
            const newOnes = rows.filter(r => !knownActivityIds.has(r.id));

            if (!firstActivityLoad) {
                newOnes
                    .filter(r => r.status === 'denied')
                    .forEach(r => {
                        const isMember = r.claimant_type === 'family_member';
                        const claimantName = isMember ? r.family_member_name : (r.head_first_name + ' ' + r.head_last_name);
                        const photo = isMember ? r.member_photo : r.head_photo;
                        const time = r.distribution_date
                            ? new Date(r.distribution_date.replace(' ', 'T')).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})
                            : '—';
                        queueClaimModal({ claimantName, photo, isGuest: false, time, status: 'denied' });
                    });
            }

            knownActivityIds = new Set(rows.map(r => r.id));
            firstActivityLoad = false;
        })
        .catch(() => {});
}

const dateSelect = document.getElementById('kmDateSelect');
    const todayBtn    = document.getElementById('kmTodayBtn');

    function todayStr() {
        const d = new Date();
        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
    }
    dateSelect.value = todayStr();

    let pollTimer = null;

    function refresh() {
        const eventId = eventSelect.value;
        const date = dateSelect.value || todayStr();
        const isToday = date === todayStr();

        const params = new URLSearchParams();
        if (eventId) params.set('event_id', eventId);
        params.set('date', date);
        const qs = '?' + params.toString();

        todayCountEl.previousElementSibling.textContent = isToday ? 'Meals Claimed Today' : 'Meals Claimed on ' + date;

        fetch('/smart-kitchen/today-stats' + qs)
            .then(r => r.json())
            .then(data => { todayCountEl.textContent = data.today ?? 0; })
            .catch(() => {});

fetch('/smart-kitchen/list-json' + qs)
            .then(r => r.json())
            .then(data => {
                renderRows(data.data || []);
                lastUpdatedEl.textContent = new Date().toLocaleTimeString();
            })
            .catch(() => {
                tableBody.innerHTML = `
                    <tr><td colspan="6">
                        <div class="empty-state">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <p>Failed to load data.</p>
                        </div>
                    </td></tr>`;
            });

        pollRecentActivity();   // ← ADD THIS LINE
    }

    function restartPolling() {
        clearInterval(pollTimer);
        const isToday = dateSelect.value === todayStr();
        // Only auto-refresh when looking at today; past dates are static.
        if (isToday) pollTimer = setInterval(refresh, 1000);
    }

eventSelect.addEventListener('change', function() {
        firstLoad = true;
        knownIds = new Set();
        firstActivityLoad = true;      // ← ADD
        knownActivityIds = new Set();  // ← ADD
        refresh();
    });

    dateSelect.addEventListener('change', function() {
        firstLoad = true;
        knownIds = new Set();
        firstActivityLoad = true;      // ← ADD
        knownActivityIds = new Set();  // ← ADD
        refresh();
        restartPolling();
    });

    todayBtn.addEventListener('click', function() {
        dateSelect.value = todayStr();
        firstLoad = true;
        knownIds = new Set();
        firstActivityLoad = true;      // ← ADD
        knownActivityIds = new Set();  // ← ADD
        refresh();
        restartPolling();
    });

    refresh();
    restartPolling();
})();
</script>

<?= $this->endSection() ?>