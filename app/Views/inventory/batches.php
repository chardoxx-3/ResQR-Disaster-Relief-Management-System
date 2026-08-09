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

.btn-icon {
  display:inline-flex; align-items:center; justify-content:center;
  width:28px; height:28px; border-radius:var(--radius-sm);
  border:1.5px solid var(--border); background:var(--surface);
  color:var(--text-3); font-size:.72rem; text-decoration:none;
  transition:all .2s; cursor:pointer;
}
.btn-icon:hover         { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }
.btn-icon.btn-icon-qr:hover { border-color:#1d4ed8; color:#1d4ed8; background:#eff6ff; }

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

/* ── Table Section ── */
.table-section {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius);
  overflow:hidden;
}

.batch-table { width:100%; border-collapse:collapse; }
.batch-table thead th {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  color:var(--text-3); padding:10px 14px; border-bottom:1px solid var(--border);
  background:var(--bg); text-align:left; white-space:nowrap;
}
.batch-table tbody td {
  font-size:.72rem; color:var(--text-2); padding:11px 14px;
  border-bottom:1px solid var(--border); vertical-align:middle;
}
.batch-table tbody tr:last-child td { border-bottom:none; }
.batch-table tbody tr:hover td { background:#fafcf8; }

/* ── Progress mini bar ── */
.mini-progress {
  display:flex; align-items:center; gap:8px;
}
.mini-bar-track {
  flex:1; height:5px; background:var(--border); border-radius:20px; overflow:hidden;
}
.mini-bar-fill {
  height:100%; border-radius:20px;
  background:linear-gradient(90deg,var(--green-deep),var(--green-mid));
}

/* ── Batch number ── */
.batch-num { font-family:'DM Mono',monospace; font-size:.68rem; color:var(--text-3); }
.qty-val   { font-family:'DM Mono',monospace; font-weight:700; font-size:.8rem; color:var(--text-1); }

/* ── Empty state ── */
.empty-state {
  text-align:center; padding:48px 20px; color:var(--text-3); font-size:.78rem;
}
.empty-state i { font-size:2rem; margin-bottom:10px; display:block; color:var(--border); }
</style>

<div class="page-wrap">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-layer-group me-2" style="color:var(--green-mid)"></i><?= esc($item['item_name']) ?></h5>
      <p>Manage and view all batches for this item</p>
    </div>
    <div class="page-actions">
      <?php
        $userRole  = session()->get('role');
        $canModify = in_array($userRole, ['admin', 'staff']);
      ?>
      <?php if($canModify): ?>
      <a href="/inventory/update_stock/<?= $item['id'] ?>" class="btn-primary-c">
        <i class="fa-solid fa-plus"></i> Add New Batch
      </a>
      <?php endif; ?>
      <a href="/inventory" class="btn-outline-c">
        <i class="fa-solid fa-arrow-left"></i> Back to Inventory
      </a>
    </div>
  </div>

  <!-- Table Section -->
  <div class="table-section">
    <?php if(empty($batches)): ?>
      <div class="empty-state">
        <i class="fa-solid fa-inbox"></i>
        No batches found for this item.
        <?php if($canModify): ?>
          <br><a href="/inventory/update_stock/<?= $item['id'] ?>" class="btn-primary-c" style="margin-top:12px">
            <i class="fa-solid fa-plus"></i> Add First Batch
          </a>
        <?php endif; ?>
      </div>
    <?php else: ?>
    <div style="overflow-x:auto">
      <table class="batch-table">
        <thead>
          <tr>
            <th>Batch #</th>
            <th>Storage</th>
            <th>Date Received</th>
            <th>Source</th>
            <th>Initial Qty</th>
            <th>Remaining</th>
            <th>Distributed</th>
            <th>Status</th>
            <th>Expiry</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($batches as $batch): ?>
          <?php
            $distributed = $batch['quantity_initial'] - $batch['quantity_remaining'];
            $pct = $batch['quantity_initial'] > 0 ? round(($distributed / $batch['quantity_initial']) * 100) : 0;

            $statusMap = [
              'active'                => ['success', 'circle-check',        'Active'],
              'partially_distributed' => ['warning', 'circle-half-stroke',  'Partial'],
              'depleted'              => ['neutral', 'circle-xmark',         'Depleted'],
              'expired'               => ['danger',  'triangle-exclamation', 'Expired'],
            ];
            $s = $statusMap[$batch['status']] ?? ['neutral', 'circle', ucfirst(str_replace('_',' ',$batch['status']))];

            $expiryDisplay = '';
            if($batch['expiry_date']) {
              $expiryDate = new DateTime($batch['expiry_date']);
              $today      = new DateTime();
              $daysLeft   = (int)$today->diff($expiryDate)->format('%r%a');
              if($daysLeft < 0) {
                $expiryDisplay = '<span class="badge2 danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Expired</span>';
              } elseif($daysLeft <= 30) {
                $expiryDisplay = '<span class="badge2 warning"><i class="fa-solid fa-clock me-1"></i>'.$daysLeft.' days</span>';
              } else {
                $expiryDisplay = date('M d, Y', strtotime($batch['expiry_date']));
              }
            } else {
              $expiryDisplay = '<span style="color:var(--text-3)">N/A</span>';
            }
          ?>
          <tr>
            <!-- Batch # -->
            <td><span class="batch-num"><?= esc($batch['batch_number']) ?></span></td>

            <!-- Storage -->
            <td>
              <?php if($batch['storage_unit']): ?>
                <span class="badge2 blue"><?= esc($batch['storage_unit']) ?></span>
                <span style="display:block;font-size:.62rem;color:var(--text-3);margin-top:2px">
                  <?= number_format($batch['storage_quantity'] ?? ($batch['quantity_initial'] / max($batch['units_per_storage'],1))) ?> units
                </span>
              <?php else: ?>
                <span style="color:var(--text-3)">—</span>
              <?php endif; ?>
            </td>

            <!-- Date Received -->
            <td style="white-space:nowrap"><?= date('M d, Y', strtotime($batch['date_received'])) ?></td>

            <!-- Source -->
            <td>
              <span class="badge2 blue"><?= ucfirst(esc($batch['source_type'])) ?></span>
              <?php if($batch['source_details']): ?>
                <span style="display:block;font-size:.62rem;color:var(--text-3);margin-top:2px"><?= esc($batch['source_details']) ?></span>
              <?php endif; ?>
            </td>

            <!-- Initial Qty -->
            <td><span class="qty-val"><?= number_format($batch['quantity_initial']) ?></span></td>

            <!-- Remaining -->
            <td>
              <span class="qty-val"><?= number_format($batch['quantity_remaining']) ?></span>
              <span style="color:var(--text-3);font-size:.65rem"> / <?= number_format($batch['quantity_initial']) ?></span>
            </td>

            <!-- Distributed + mini bar -->
            <td>
              <div class="mini-progress">
                <span class="qty-val" style="min-width:32px"><?= number_format($distributed) ?></span>
                <div class="mini-bar-track">
                  <div class="mini-bar-fill" style="width:<?= $pct ?>%"></div>
                </div>
                <span style="font-size:.62rem;color:var(--text-3);min-width:28px"><?= $pct ?>%</span>
              </div>
            </td>

            <!-- Status -->
            <td>
              <span class="badge2 <?= $s[0] ?>">
                <i class="fa-solid fa-<?= $s[1] ?> me-1"></i><?= $s[2] ?>
              </span>
            </td>

            <!-- Expiry -->
            <td style="white-space:nowrap"><?= $expiryDisplay ?></td>

            <!-- Actions -->
            <td>
              <div style="display:flex;gap:5px">
                <a href="/inventory/batch/view/<?= $batch['id'] ?>" class="btn-icon" title="View Batch">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="/inventory/batch/qr/<?= $batch['id'] ?>" class="btn-icon btn-icon-qr" title="View QR Code">
                  <i class="fa-solid fa-qrcode"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div>

<?= $this->endSection() ?>