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

.page-wrap { animation: fadeUp .45s ease both; max-width: 860px; margin: 0 auto; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* ── Page Header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.2px; }
.page-title p  { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }

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
.btn-primary-c.lg { padding:9px 22px; font-size:.78rem; }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

/* ── Item Info Banner ── */
.item-banner {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
.item-banner-inner {
  display:flex; align-items:center; gap:14px;
  background:var(--surface2); border:1px solid var(--green-glow);
  border-radius:var(--radius-sm); padding:12px 16px;
}
.item-banner-icon {
  width:40px; height:40px; border-radius:10px; flex-shrink:0;
  background:#fff; border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center;
  color:var(--green-mid); font-size:1rem;
}
.item-banner-name { font-size:.88rem; font-weight:800; color:var(--text-1); margin-bottom:4px; }
.item-banner-meta { display:flex; gap:6px; flex-wrap:wrap; }
.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 8px; border-radius:20px; font-size:.6rem; font-weight:700;
}
.badge2.green  { background:#f0fdf4; color:var(--green-deep); border:1px solid var(--green-glow); }
.badge2.blue   { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.badge2.neutral { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }
.badge2.success { background:#f0fdf4; color:var(--green-deep); border:1px solid var(--green-glow); }
.badge2.warning { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.badge2.secondary { background:var(--bg); color:var(--text-3); border:1px solid var(--border); }

/* ── Form Body ── */
.form-body {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:24px 20px;
}
.form-section-title {
  font-size:.62rem; font-weight:700; text-transform:uppercase;
  letter-spacing:.6px; color:var(--text-3);
  margin-bottom:14px; padding-bottom:6px;
  border-bottom:1px solid var(--border);
}
.field-group { margin-bottom:14px; }
.field-label { display:block; font-size:.7rem; font-weight:700; color:var(--text-2); margin-bottom:5px; }
.field-label .req { color:#e74c3c; margin-left:2px; }
.field-hint { font-size:.62rem; color:var(--text-3); margin-top:4px; }

.form-control, .form-select {
  width:100%; border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:8px 12px; font-size:.78rem; font-family:'Outfit',sans-serif;
  color:var(--text-1); background:var(--surface);
  transition:border-color .2s, box-shadow .2s; outline:none;
}
.form-control:focus, .form-select:focus {
  border-color:var(--green-mid);
  box-shadow:0 0 0 3px rgba(119,188,63,.12);
}
.form-control::placeholder { color:#b0bec5; }
textarea.form-control { resize:vertical; min-height:70px; }

.row-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media(max-width:580px){ .row-grid{ grid-template-columns:1fr; } }

/* ── Source Cards ── */
.source-cards { display:flex; gap:8px; flex-wrap:wrap; }
.source-card {
  flex:1; min-width:110px;
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:10px 14px; cursor:pointer; transition:all .2s;
  display:flex; align-items:center; gap:8px;
  font-size:.72rem; font-weight:600; color:var(--text-2);
}
.source-card:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }
.source-card input[type=radio] { display:none; }
.source-card.selected { border-color:var(--green-mid); background:var(--surface2); color:var(--green-deep); }
.source-card i { font-size:.88rem; }

/* ── Form Divider ── */
.form-divider { border:none; border-top:1px solid var(--border); margin:20px 0; }

/* ── Form Footer ── */
.form-footer {
  display:flex; align-items:center; justify-content:flex-end; gap:8px;
  padding-top:16px; border-top:1px solid var(--border);
}

/* ── Recent Batches Table ── */
.batches-section {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius);
  padding:20px;
}
.batches-section.has-top-border { border-top:1px solid var(--border); border-radius:0 0 var(--radius) var(--radius); }

.section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
.section-header h6 { font-size:.78rem; font-weight:800; color:var(--text-1); margin:0; }

.batch-table { width:100%; border-collapse:collapse; }
.batch-table th {
  font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
  color:var(--text-3); padding:8px 10px; border-bottom:1px solid var(--border);
  background:var(--bg); text-align:left;
}
.batch-table th:first-child { border-radius:var(--radius-sm) 0 0 0; }
.batch-table th:last-child  { border-radius:0 var(--radius-sm) 0 0; }
.batch-table td {
  font-size:.72rem; color:var(--text-2); padding:10px 10px;
  border-bottom:1px solid var(--border); vertical-align:middle;
}
.batch-table tr:last-child td { border-bottom:none; }
.batch-table tr:hover td { background:var(--bg); }
.batch-num { font-family:'DM Mono',monospace; font-size:.68rem; color:var(--text-3); }
.qty-val { font-family:'DM Mono',monospace; font-weight:700; font-size:.8rem; color:var(--text-1); }

/* ── No batches empty state ── */
.empty-state { text-align:center; padding:28px 0; color:var(--text-3); font-size:.75rem; }
.empty-state i { font-size:1.6rem; margin-bottom:8px; display:block; color:var(--border); }
</style>

<div class="page-wrap">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-plus-circle me-2" style="color:var(--green-mid)"></i>Add Stock to Item</h5>
      <p>Create a new batch and update existing inventory</p>
    </div>
    <div>
      <a href="/inventory" class="btn-outline-c">
        <i class="fa-solid fa-arrow-left"></i> Back to Inventory
      </a>
    </div>
  </div>

  <!-- Item Info Banner -->
  <div class="item-banner">
    <div class="item-banner-inner">
      <div class="item-banner-icon">
        <i class="fa-solid fa-box"></i>
      </div>
      <div>
        <div class="item-banner-name"><?= esc($item['item_name']) ?></div>
        <div class="item-banner-meta">
          <span class="badge2 green"><i class="fa-solid fa-cubes me-1"></i>Current Total: <?= number_format($item['quantity']) ?> <?= esc($item['unit_type']) ?>s</span>
          <span class="badge2 blue"><i class="fa-solid fa-house me-1"></i>Allocation: <?= esc($item['allocation']) ?> per household</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Body -->
  <div class="form-body">
    <form action="/inventory/saveItemWithBatch" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="barangay" value="">
      <input type="hidden" name="item_name"       value="<?= esc($item['item_name']) ?>">
      <input type="hidden" name="unit_type"        value="<?= esc($item['unit_type']) ?>">
      <input type="hidden" name="allocation"       value="<?= esc($item['allocation']) ?>">
      <input type="hidden" name="existing_item_id" value="<?= esc($item['id']) ?>">

      <!-- Batch Details -->
      <p class="form-section-title"><i class="fa-solid fa-layer-group me-1"></i>Batch Details</p>

      <div class="row-grid">
        <div class="field-group">
          <label class="field-label">Additional Quantity <span class="req">*</span></label>
          <input type="number" name="quantity" class="form-control" placeholder="Enter quantity" required min="1">
          <p class="field-hint">This will create a new batch record</p>
        </div>
        <div class="field-group">
          <label class="field-label">Date Received <span class="req">*</span></label>
          <input type="date" name="date_received" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
      </div>

      <div class="row-grid">
        <div class="field-group">
          <label class="field-label">Storage Unit <span class="req">*</span></label>
          <select name="storage_unit" class="form-select" required>
            <option value="">Select Storage Unit</option>
            <option value="Box">Box</option>
            <option value="Crate">Crate</option>
            <option value="Carton">Carton</option>
            <option value="Bundle">Bundle</option>
            <option value="Pallet">Pallet</option>
            <option value="Container">Container</option>
            <option value="Sack">Sack</option>
            <option value="Drum">Drum</option>
          </select>
          <p class="field-hint">Storage container type for this batch</p>
        </div>
        <div class="field-group">
          <label class="field-label">Units per Storage <span class="req">*</span></label>
          <input type="number" name="units_per_storage" class="form-control" placeholder="e.g., 50" required min="1" value="1">
          <p class="field-hint">Number of individual items per storage unit</p>
        </div>
      </div>

      <div class="row-grid">
        <div class="field-group">
          <label class="field-label">Expiry Date <span style="color:var(--text-3);font-weight:500">(Optional)</span></label>
          <input type="date" name="expiry_date" class="form-control">
          <p class="field-hint">Leave blank if no expiry</p>
        </div>
        <div class="field-group">
          <label class="field-label">Storage Location</label>
          <input type="text" name="storage_location" class="form-control" placeholder="e.g., Warehouse A, Shelf 3">
        </div>
      </div>

      <hr class="form-divider">

      <!-- Source Information -->
      <p class="form-section-title"><i class="fa-solid fa-truck me-1"></i>Source Information</p>

      <div class="field-group">
        <label class="field-label">Source Type <span class="req">*</span></label>
        <div class="source-cards">
          <label class="source-card" id="sc-procured">
            <input type="radio" name="source_type" value="procured" required>
            <i class="fa-solid fa-receipt"></i> Procured
          </label>
          <label class="source-card" id="sc-donated">
            <input type="radio" name="source_type" value="donated">
            <i class="fa-solid fa-hand-holding-heart"></i> Donated
          </label>
          <label class="source-card" id="sc-gov">
            <input type="radio" name="source_type" value="government-issued">
            <i class="fa-solid fa-landmark"></i> Gov. Issued
          </label>
        </div>
      </div>

      <div class="field-group">
        <label class="field-label">Source Details</label>
        <input type="text" name="source_details" class="form-control" placeholder="e.g., Donor name, PO number, Agency">
      </div>

      <div class="field-group">
        <label class="field-label">Batch Notes</label>
        <textarea name="notes" class="form-control" placeholder="Additional notes about this batch..."></textarea>
      </div>

      <div class="form-footer">
        <a href="/inventory" class="btn-outline-c">Cancel</a>
        <button type="submit" class="btn-primary-c lg">
          <i class="fa-solid fa-plus-circle"></i> Add Batch & Update Stock
        </button>
      </div>
    </form>
  </div>

  <!-- Recent Batches -->
  <?php if(isset($recent_batches) && !empty($recent_batches)): ?>
  <div class="batches-section has-top-border" style="margin-top:12px; border-radius:var(--radius); border-top:1px solid var(--border);">
    <div class="section-header">
      <h6><i class="fa-solid fa-clock-rotate-left me-2" style="color:var(--green-mid)"></i>Recent Batches</h6>
      <span class="badge2 neutral"><?= count($recent_batches) ?> batch<?= count($recent_batches) > 1 ? 'es' : '' ?></span>
    </div>
    <table class="batch-table">
      <thead>
        <tr>
          <th>Batch #</th>
          <th>Date Received</th>
          <th>Quantity</th>
          <th>Source</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($recent_batches as $batch): ?>
        <tr>
          <td><span class="batch-num"><?= esc($batch['batch_number']) ?></span></td>
          <td><?= date('M d, Y', strtotime($batch['date_received'])) ?></td>
          <td>
            <span class="qty-val"><?= number_format($batch['quantity_remaining']) ?></span>
            <span style="color:var(--text-3);font-size:.65rem"> / <?= number_format($batch['quantity_initial']) ?></span>
          </td>
          <td><?= ucfirst(esc($batch['source_type'])) ?></td>
          <td>
            <?php if($batch['status'] == 'active'): ?>
              <span class="badge2 success"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
            <?php elseif($batch['status'] == 'partially_distributed'): ?>
              <span class="badge2 warning"><i class="fa-solid fa-circle-half-stroke me-1"></i>Partial</span>
            <?php elseif($batch['status'] == 'depleted'): ?>
              <span class="badge2 secondary"><i class="fa-solid fa-circle-xmark me-1"></i>Depleted</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

</div>

<script>
// Source card click selection
document.querySelectorAll('.source-card').forEach(card => {
  card.addEventListener('click', function() {
    document.querySelectorAll('.source-card').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
  });
});
</script>

<?= $this->endSection() ?>