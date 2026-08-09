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

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* ── Page Header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.2px; }
.page-title p  { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }
.page-actions  { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

/* ── Buttons ── */
.btn-primary-c {
  display:inline-flex; align-items:center; gap:5px;
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s;
  box-shadow:0 4px 12px rgba(119,188,63,.3); font-family:'Outfit',sans-serif;
}
.btn-primary-c:hover { transform:translateY(-1px); color:#fff; box-shadow:0 6px 18px rgba(119,188,63,.4); }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

/* ── Badges ── */
.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 8px; border-radius:20px; font-size:.6rem; font-weight:700;
}
.badge2.success  { background:#f0fdf4; color:var(--green-deep); border:1px solid var(--green-glow); }
.badge2.danger   { background:#fff1f2; color:#c0392b; border:1px solid #fecaca; }
.badge2.blue     { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.badge2.warning  { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.badge2.neutral  { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }
.badge2.orange   { background:#fff7ed; color:var(--orange-deep); border:1px solid #fed7aa; }

/* ── Two-column layout ── */
.content-grid {
  display:grid; grid-template-columns:1fr 300px; gap:12px; align-items:start;
}
@media(max-width:860px){ .content-grid{ grid-template-columns:1fr; } }

/* ── Panel (shared card style) ── */
.panel {
  background:var(--surface); border:1px solid var(--border);
  border-radius:var(--radius); box-shadow:var(--shadow-sm);
  overflow:hidden;
}
.panel + .panel { margin-top:12px; }
.panel-header {
  display:flex; align-items:center; justify-content:space-between;
  padding:12px 16px; border-bottom:1px solid var(--border);
  background:var(--bg);
}
.panel-header h6 { font-size:.75rem; font-weight:800; color:var(--text-1); margin:0; }
.panel-body { padding:16px; }

/* ── Section connecting to page header ── */
.main-panel-wrap {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); padding:20px;
}

/* ── Info grid ── */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
@media(max-width:580px){ .info-grid{ grid-template-columns:1fr; } }

.info-row {
  display:flex; align-items:flex-start; gap:8px;
  padding:9px 12px; border-bottom:1px solid var(--border);
}
.info-row:last-child { border-bottom:none; }
.info-grid .info-row:nth-child(odd)  { border-right:1px solid var(--border); }
.info-label { font-size:.65rem; font-weight:700; color:var(--text-3); min-width:90px; padding-top:1px; }
.info-val   { font-size:.75rem; color:var(--text-1); font-weight:500; }

/* ── Progress bar ── */
.progress-wrap { padding:14px 16px; border-top:1px solid var(--border); }
.progress-meta { display:flex; justify-content:space-between; margin-bottom:6px; }
.progress-meta span { font-size:.68rem; color:var(--text-3); font-weight:600; }
.progress-meta span strong { color:var(--text-1); }
.progress-bar-track {
  height:10px; background:var(--border); border-radius:20px; overflow:hidden;
}
.progress-bar-fill {
  height:100%; border-radius:20px;
  background:linear-gradient(90deg, var(--green-deep), var(--green-mid));
  transition:width .5s ease;
}
.progress-pct { font-size:.62rem; color:var(--text-3); margin-top:5px; text-align:right; }

/* ── Distribution table ── */
.dist-table { width:100%; border-collapse:collapse; }
.dist-table th {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  color:var(--text-3); padding:8px 12px; border-bottom:1px solid var(--border);
  background:var(--bg); text-align:left;
}
.dist-table td {
  font-size:.72rem; color:var(--text-2); padding:10px 12px;
  border-bottom:1px solid var(--border); vertical-align:middle;
}
.dist-table tr:last-child td { border-bottom:none; }
.dist-table tr:hover td { background:var(--bg); }

.empty-state { text-align:center; padding:32px 0; color:var(--text-3); font-size:.75rem; }
.empty-state i { font-size:1.8rem; margin-bottom:8px; display:block; color:var(--border); }

/* ── QR card ── */
.qr-box {
  display:flex; flex-direction:column; align-items:center;
  padding:20px 16px;
}
.qr-box #qrcode { margin-bottom:10px; }
.qr-box p { font-size:.65rem; color:var(--text-3); margin-bottom:12px; text-align:center; }
.qr-actions { display:flex; gap:6px; width:100%; }
.qr-actions .btn-outline-c,
.qr-actions .btn-primary-c { flex:1; justify-content:center; }

/* ── Stats list ── */
.stats-list { padding:0; }
.stat-row {
  display:flex; align-items:center; justify-content:space-between;
  padding:9px 16px; border-bottom:1px solid var(--border);
  font-size:.72rem;
}
.stat-row:last-child { border-bottom:none; }
.stat-label { color:var(--text-3); font-weight:600; }
.stat-val { font-family:'DM Mono',monospace; font-weight:700; color:var(--text-1); font-size:.78rem; }
.stat-val.green { color:var(--green-deep); }
.stat-val.orange { color:var(--orange-deep); }
.stat-divider { border:none; border-top:1px solid var(--border); margin:0; }
</style>

<div class="page-wrap">

<!-- Page Header -->
<div class="page-header">
  <div class="page-title">
    <h5>
      <i class="fa-solid fa-layer-group me-2" style="color:var(--green-mid)"></i>
      <?= esc($item['item_name']) ?>
      <span style="color:var(--text-3);font-weight:500;font-size:.82rem;margin-left:6px">&mdash; Batch #<?= esc($batch['batch_number']) ?></span>
    </h5>
    <p>Batch details and distribution history</p>
  </div>
  <div class="page-actions">
    <a href="/inventory/batches/<?= $item['id'] ?>" class="btn-outline-c">
      <i class="fa-solid fa-arrow-left"></i> Back to Batches
    </a>
    <a href="/distribution/process-scan-view?qr=<?= $batch['qr_code_token'] ?>" class="btn-primary-c">
      <i class="fa-solid fa-qrcode"></i> Scan for Distribution
    </a>
    <?php if($batch['status'] === 'received'): ?>
    <button type="button" class="btn-outline-c" onclick="showTrackingHistory()">
      <i class="fa-solid fa-truck"></i> View Transit History
    </button>
    <?php endif; ?>
  </div>
</div>

  <!-- Main content area (connected to header) -->
  <div class="main-panel-wrap">
    <div class="content-grid">

      <!-- ── Left Column ── -->
      <div>

        <!-- Batch Information Panel -->
        <div class="panel">
          <div class="panel-header">
            <h6><i class="fa-solid fa-circle-info me-2" style="color:var(--green-mid)"></i>Batch Information</h6>
            <?php
              $statusMap = [
                'active'                => ['success', 'circle-check', 'Active'],
                'partially_distributed' => ['warning', 'circle-half-stroke', 'Partial'],
                'depleted'              => ['neutral', 'circle-xmark', 'Depleted'],
              ];
              $s = $statusMap[$batch['status']] ?? ['neutral', 'circle', ucfirst($batch['status'])];
            ?>
            <span class="badge2 <?= $s[0] ?>"><i class="fa-solid fa-<?= $s[1] ?> me-1"></i><?= $s[2] ?></span>
          </div>

          <div class="info-grid">
            <div class="info-row">
              <span class="info-label">Source Type</span>
              <span class="info-val">
                <span class="badge2 blue"><?= ucfirst(esc($batch['source_type'])) ?></span>
              </span>
            </div>
            <div class="info-row">
              <span class="info-label">Source Details</span>
              <span class="info-val"><?= esc($batch['source_details'] ?? 'N/A') ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Date Received</span>
              <span class="info-val"><?= date('M d, Y', strtotime($batch['date_received'])) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Expiry Date</span>
              <span class="info-val">
                <?php if($batch['expiry_date']): ?>
                  <?= date('M d, Y', strtotime($batch['expiry_date'])) ?>
                  <?php if(strtotime($batch['expiry_date']) < time()): ?>
                    <span class="badge2 danger ms-1">Expired</span>
                  <?php endif; ?>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </span>
            </div>
            <div class="info-row">
              <span class="info-label">Storage Location</span>
              <span class="info-val"><?= esc($batch['storage_location'] ?? 'Unspecified') ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Storage Unit</span>
              <span class="info-val">
                <?php if($batch['storage_unit']): ?>
                  <span class="badge2 blue"><?= esc($batch['storage_unit']) ?></span>
                <?php else: ?>
                  <span class="badge2 neutral">Not Specified</span>
                <?php endif; ?>
              </span>
            </div>
            <div class="info-row">
              <span class="info-label">Assignment</span>
              <span class="info-val">
                <?php if($batch['assigned_to'] && $batch['assignment_type']): ?>
                  <span class="badge2 warning"><?= ucfirst(esc($batch['assignment_type'])) ?>: <?= esc($batch['assigned_to']) ?></span>
                <?php else: ?>
                  <span class="badge2 neutral">Unassigned</span>
                <?php endif; ?>
              </span>
            </div>
            <?php if($batch['notes']): ?>
            <div class="info-row">
              <span class="info-label">Notes</span>
              <span class="info-val"><?= esc($batch['notes']) ?></span>
            </div>
            <?php endif; ?>
          </div>

          <!-- Stock Progress -->
          <?php
            $used = $batch['quantity_initial'] - $batch['quantity_remaining'];
            $pct  = $batch['quantity_initial'] > 0 ? ($used / $batch['quantity_initial']) * 100 : 0;
          ?>
          <div class="progress-wrap">
            <div class="progress-meta">
              <span>Used: <strong><?= number_format($used) ?> <?= esc($batch['unit_type']) ?></strong></span>
              <span>Remaining: <strong><?= number_format($batch['quantity_remaining']) ?> <?= esc($batch['unit_type']) ?></strong></span>
            </div>
            <div class="progress-bar-track">
              <div class="progress-bar-fill" style="width:<?= round($pct) ?>%"></div>
            </div>
            <div class="progress-pct"><?= round($pct) ?>% distributed</div>
          </div>
        </div>

        <!-- Distribution History Panel -->
        <div class="panel">
          <div class="panel-header">
            <h6><i class="fa-solid fa-clock-rotate-left me-2" style="color:var(--green-mid)"></i>Distribution History</h6>
            <span class="badge2 neutral"><?= count($distributions) ?> transaction<?= count($distributions) != 1 ? 's' : '' ?></span>
          </div>
          <?php if(empty($distributions)): ?>
            <div class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              No distribution records for this batch yet.
            </div>
          <?php else: ?>
            <div style="overflow-x:auto">
              <table class="dist-table">
                <thead>
                  <tr>
                    <th>Date / Time</th>
                    <th>Resident</th>
                    <th>Quantity</th>
                    <th>Distributor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($distributions as $dist): ?>
                  <tr>
                    <td style="font-family:'DM Mono',monospace;font-size:.68rem">
                      <?= date('M d, Y', strtotime($dist['distribution_date'])) ?>
                      <span style="color:var(--text-3)"> <?= date('h:i A', strtotime($dist['distribution_date'])) ?></span>
                    </td>
                    <td>
                      Resident #<?= $dist['resident_id'] ?>
                      <?php if($dist['family_member_id']): ?>
                        <span class="badge2 neutral ms-1">Member</span>
                      <?php endif; ?>
                    </td>
                    <td style="font-family:'DM Mono',monospace;font-weight:700">
                      <?= number_format($dist['quantity_distributed']) ?>
                      <span style="font-family:'Outfit',sans-serif;font-weight:400;color:var(--text-3);font-size:.65rem"> <?= esc($batch['unit_type']) ?></span>
                    </td>
                    <td>User #<?= $dist['distributor_id'] ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

      </div><!-- /left column -->

      <!-- ── Right Column ── -->
      <div>

        <!-- QR Code Panel -->
        <div class="panel">
          <div class="panel-header">
            <h6><i class="fa-solid fa-qrcode me-2" style="color:var(--green-mid)"></i>Batch QR Code</h6>
          </div>
          <?php if($batch['qr_code_data']): ?>
            <div class="qr-box">
              <div id="qrcode"></div>
              <p>Scan to distribute this batch</p>
              <div class="qr-actions">
                <a href="/distribution/process-scan-view?qr=<?= $batch['qr_code_token'] ?>" class="btn-outline-c">
                  <i class="fa-solid fa-camera"></i> Test
                </a>
                <button class="btn-primary-c" onclick="printQR()">
                  <i class="fa-solid fa-print"></i> Print
                </button>
              </div>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <i class="fa-solid fa-qrcode"></i>
              QR code not generated
            </div>
          <?php endif; ?>
        </div>

        <!-- Quick Stats Panel -->
        <div class="panel" style="margin-top:12px">
          <div class="panel-header">
            <h6><i class="fa-solid fa-chart-simple me-2" style="color:var(--green-mid)"></i>Quick Stats</h6>
          </div>
          <div class="stats-list">
            <?php if($batch['storage_unit']): ?>
              <?php
                $storageUnitsRemaining = $batch['units_per_storage'] > 0 ? $batch['quantity_remaining'] / $batch['units_per_storage'] : 0;
                $storageUnitsInitial   = $batch['units_per_storage'] > 0 ? $batch['quantity_initial']   / $batch['units_per_storage'] : 0;
              ?>
              <div class="stat-row">
                <span class="stat-label">Storage Type</span>
                <span class="stat-val"><?= esc($batch['storage_unit']) ?></span>
              </div>
              <div class="stat-row">
                <span class="stat-label">Units / Storage</span>
                <span class="stat-val"><?= number_format($batch['units_per_storage']) ?> <?= esc($batch['unit_type']) ?>s</span>
              </div>
              <div class="stat-row">
                <span class="stat-label">Storage Units</span>
                <span class="stat-val"><?= number_format($storageUnitsRemaining, 1) ?> / <?= number_format($storageUnitsInitial, 1) ?></span>
              </div>
              <hr class="stat-divider">
            <?php endif; ?>
            <div class="stat-row">
              <span class="stat-label">Initial Stock</span>
              <span class="stat-val"><?= number_format($batch['quantity_initial']) ?> <?= esc($batch['unit_type']) ?></span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Remaining</span>
              <span class="stat-val green"><?= number_format($batch['quantity_remaining']) ?> <?= esc($batch['unit_type']) ?></span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Distributed</span>
              <span class="stat-val orange"><?= number_format($used) ?> <?= esc($batch['unit_type']) ?></span>
            </div>
            <hr class="stat-divider">
            <div class="stat-row">
              <span class="stat-label">Allocation / HH</span>
              <span class="stat-val"><?= esc($item['allocation']) ?> <?= esc($item['unit_type']) ?></span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Potential HH</span>
              <span class="stat-val"><?= floor($batch['quantity_remaining'] / max($item['allocation'], 1)) ?> households</span>
            </div>
          </div>
        </div>

      </div><!-- /right column -->
    </div><!-- /content-grid -->
  </div><!-- /main-panel-wrap -->

</div><!-- /page-wrap -->

<!-- Transit History Modal -->
<div class="modal fade" id="transitHistoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius); border:1px solid var(--border);">
      <div class="modal-header" style="border-bottom:1px solid var(--border); padding:16px 20px;">
        <h5 class="modal-title" style="font-size:.9rem; font-weight:800;">
          <i class="fa-solid fa-truck me-2" style="color:var(--green-mid)"></i>
          Batch Transit History
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="padding:20px;" id="transitHistoryContent">
        <div class="text-center py-4">
          <div class="spinner-border" style="color:var(--green-mid)" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2" style="font-size:.75rem; color:var(--text-3);">Loading transit history...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QR Code library -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
<?php if($batch['qr_code_data']): ?>
new QRCode(document.getElementById("qrcode"), {
  text: '<?= $batch['qr_code_data'] ?>',
  width: 190,
  height: 190,
  colorDark: "#1e293b",
  colorLight: "#ffffff",
  correctLevel: QRCode.CorrectLevel.H
});
<?php endif; ?>

function printQR() {
  var printWindow = window.open('', '_blank');
  printWindow.document.write('<html><head><title>Print QR Code</title>');
  printWindow.document.write('<style>body{text-align:center;padding:20px;font-family:sans-serif;}</style>');
  printWindow.document.write('</head><body>');
  printWindow.document.write('<h3><?= esc($item['item_name']) ?></h3>');
  printWindow.document.write('<p>Batch: <?= esc($batch['batch_number']) ?></p>');
  printWindow.document.write(document.getElementById('qrcode').innerHTML);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}


function showTrackingHistory() {
    // Show modal with loading state
    var myModal = new bootstrap.Modal(document.getElementById('transitHistoryModal'));
    myModal.show();
    
    // Get the batch ID from PHP
    const batchId = <?= $batch['id'] ?>;
    
    console.log('Fetching tracking history for batch:', batchId); // Debug log
    
    // Fetch tracking history
    fetch('/inventory/get-batch-tracking/' + batchId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data); // Debug log
        
        if (data.success) {
            let html = '';
            
            // Debug info - remove in production
            html += `<div style="margin-bottom:15px; padding:10px; background:#f8f9fa; border-radius:5px; font-size:12px;">
                <strong>Debug:</strong> Batch Status: ${data.batch_status || 'Unknown'}<br>
                Logs found: ${data.history.all_logs ? data.history.all_logs.length : 0}
            </div>`;
            
            // Transit event
            if (data.history.transit) {
                html += `
                    <div style="margin-bottom:20px;">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <div style="width:32px; height:32px; background:var(--orange-deep); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa-solid fa-truck" style="color:white; font-size:.8rem;"></i>
                            </div>
                            <div>
                                <h6 style="font-size:.8rem; font-weight:700; margin:0;">Dispatched to Barangay</h6>
                                <p style="font-size:.7rem; color:var(--text-3); margin:2px 0 0;">${data.history.transit.date}</p>
                            </div>
                        </div>
                        <div style="background:var(--bg); border-radius:var(--radius-sm); padding:12px 16px; margin-left:44px;">
                            <div style="display:grid; gap:8px;">
                                <div style="display:flex; gap:8px;">
                                    <span style="font-size:.7rem; font-weight:600; color:var(--text-3); min-width:100px;">Dispatched By:</span>
                                    <span style="font-size:.7rem; color:var(--text-1);">${data.history.transit.dispatched_by}</span>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    <span style="font-size:.7rem; font-weight:600; color:var(--text-3); min-width:100px;">Destination:</span>
                                    <span style="font-size:.7rem; color:var(--text-1);">${data.history.transit.location}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div style="margin-bottom:20px;">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <div style="width:32px; height:32px; background:#ccc; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa-solid fa-truck" style="color:white; font-size:.8rem;"></i>
                            </div>
                            <div>
                                <h6 style="font-size:.8rem; font-weight:700; margin:0;">No Transit Record</h6>
                                <p style="font-size:.7rem; color:var(--text-3); margin:2px 0 0;">No dispatch information found</p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Receipt event
            if (data.history.receipt) {
                html += `
                    <div>
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <div style="width:32px; height:32px; background:var(--green-deep); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa-solid fa-check" style="color:white; font-size:.8rem;"></i>
                            </div>
                            <div>
                                <h6 style="font-size:.8rem; font-weight:700; margin:0;">Received by Barangay</h6>
                                <p style="font-size:.7rem; color:var(--text-3); margin:2px 0 0;">${data.history.receipt.date}</p>
                            </div>
                        </div>
                        <div style="background:var(--bg); border-radius:var(--radius-sm); padding:12px 16px; margin-left:44px;">
                            <div style="display:grid; gap:8px;">
                                <div style="display:flex; gap:8px;">
                                    <span style="font-size:.7rem; font-weight:600; color:var(--text-3); min-width:100px;">Received By:</span>
                                    <span style="font-size:.7rem; color:var(--text-1);">${data.history.receipt.received_by}</span>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    <span style="font-size:.7rem; font-weight:600; color:var(--text-3); min-width:100px;">Barangay:</span>
                                    <span style="font-size:.7rem; color:var(--text-1);">${data.history.receipt.location}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div>
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <div style="width:32px; height:32px; background:#ccc; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                <i class="fa-solid fa-check" style="color:white; font-size:.8rem;"></i>
                            </div>
                            <div>
                                <h6 style="font-size:.8rem; font-weight:700; margin:0;">No Receipt Record</h6>
                                <p style="font-size:.7rem; color:var(--text-3); margin:2px 0 0;">No receipt information found</p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            document.getElementById('transitHistoryContent').innerHTML = html;
        } else {
            document.getElementById('transitHistoryContent').innerHTML = 
                '<div class="text-center py-4" style="color:var(--orange-deep);">' + 
                (data.message || 'Error loading history') + 
                '</div>';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        document.getElementById('transitHistoryContent').innerHTML = 
            '<div class="text-center py-4" style="color:var(--orange-deep);">' +
            'Error: ' + error.message + 
            '</div>';
    });
}
</script>

<?= $this->endSection() ?>