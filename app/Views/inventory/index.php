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
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }

/* ── Page header ── */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
  margin-bottom:0;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; letter-spacing:-.2px; }
.page-title p  { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }
.page-actions  { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }

.btn-primary-c {
  display:inline-flex; align-items:center; gap:5px;
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s;
  box-shadow:0 4px 12px rgba(119,188,63,.3); font-family:'Outfit',sans-serif;
}
.btn-primary-c:hover { transform:translateY(-1px); color:#fff; box-shadow:0 6px 18px rgba(119,188,63,.4); }

.btn-orange-c {
  display:inline-flex; align-items:center; gap:5px;
  background:linear-gradient(135deg,var(--orange-deep),var(--orange-mid));
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
  box-shadow:0 4px 12px rgba(201,107,16,.25);
}
.btn-orange-c:hover { color:#fff; transform:translateY(-1px); }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer; transition:all .2s; font-family:'Outfit',sans-serif;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

/* ── KPI Grid ── */
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
.kpi-icon.amber  { background:#fffbeb; color:#c08000; }
.kpi-icon.blue   { background:#eff6ff; color:#1d4ed8; }
.kpi-label { font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--text-3); margin-bottom:2px; }
.kpi-hero .kpi-label { color:rgba(255,255,255,.75); }
.kpi-val { font-size:1.6rem; font-weight:800; line-height:1; color:var(--text-1); font-family:'DM Mono',monospace; }
.kpi-hero .kpi-val { color:#fff; }

/* ── Source Summary Bar ── */
.source-bar {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:14px 20px;
}
.source-bar-inner {
  display:flex; gap:24px; flex-wrap:wrap; align-items:center;
}
.source-label { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-3); margin-bottom:6px; }
.source-item {
  display:flex; align-items:center; gap:10px;
  background:var(--bg); border:1px solid var(--border); border-radius:10px; padding:8px 14px;
}
.source-item .si-icon { font-size:1rem; }
.source-item .si-label { font-size:.62rem; color:var(--text-3); text-transform:capitalize; }
.source-item .si-val { font-size:.82rem; font-weight:800; color:var(--text-1); font-family:'DM Mono',monospace; }

/* ── Items Grid ── */
.items-section {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); padding:20px;
}
.items-grid {
  display:grid; grid-template-columns:repeat(3,1fr); gap:14px;
}
@media(max-width:900px){ .items-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:580px){ .items-grid{ grid-template-columns:1fr; } }

/* ── Item Card ── */
.item-card {
  background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
  box-shadow:var(--shadow-sm); overflow:hidden;
  transition:box-shadow .2s, transform .2s; display:flex; flex-direction:column;
}
.item-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }

.item-card-body { padding:16px; flex:1; }
.item-card-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px; }
.item-icon {
  width:40px; height:40px; border-radius:10px; flex-shrink:0;
  background:var(--surface2); border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center;
  color:var(--green-mid); font-size:1rem;
}
.item-badges { display:flex; gap:4px; flex-wrap:wrap; justify-content:flex-end; }

.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 8px; border-radius:20px; font-size:.58rem; font-weight:700;
}
.badge2.success { background:#f0fdf4; color:var(--green-deep); border:1px solid var(--green-glow); }
.badge2.danger  { background:#fff1f2; color:#c0392b; border:1px solid #fecaca; }
.badge2.blue    { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.badge2.neutral { background:var(--bg); color:var(--text-2); border:1px solid var(--border); }

.item-name { font-size:.85rem; font-weight:800; color:var(--text-1); margin-bottom:2px; }
.item-alloc { font-size:.62rem; color:var(--text-3); margin-bottom:10px; }

.item-qty-row { display:flex; align-items:baseline; gap:4px; margin-bottom:8px; }
.item-qty { font-size:1.8rem; font-weight:800; color:var(--text-1); font-family:'DM Mono',monospace; line-height:1; }
.item-unit { font-size:.65rem; color:var(--text-3); }

/* ── Batch collapse ── */
.batches-toggle {
  display:inline-flex; align-items:center; gap:4px;
  background:none; border:none; cursor:pointer;
  font-size:.62rem; font-weight:600; color:var(--green-mid);
  padding:0; font-family:'Outfit',sans-serif; transition:color .2s;
  margin-bottom:8px;
}
.batches-toggle:hover { color:var(--green-deep); }
.batches-toggle i { transition:transform .25s; }
.batches-toggle.open i { transform:rotate(180deg); }

.batches-list { display:none; }
.batches-list.open { display:block; }

.batch-item {
  display:flex; align-items:center; justify-content:space-between;
  padding:6px 0; border-bottom:1px solid var(--border);
}
.batch-item:last-child { border-bottom:none; }
.batch-num { font-size:.65rem; font-weight:700; color:var(--text-1); font-family:'DM Mono',monospace; }
.batch-meta { font-size:.6rem; color:var(--text-3); }
.batch-qty { font-size:.62rem; font-weight:700; color:var(--green-deep); font-family:'DM Mono',monospace; }

/* ── Item card footer ── */
.item-card-footer { padding:12px 16px; border-top:1px solid var(--border); background:var(--bg); }
.item-actions { display:flex; gap:6px; }
.item-actions .act-btn {
  display:inline-flex; align-items:center; gap:4px;
  padding:5px 10px; border-radius:7px; font-size:.62rem; font-weight:600;
  border:1.5px solid var(--border); background:var(--surface);
  text-decoration:none; cursor:pointer; transition:all .15s; color:var(--text-2);
  font-family:'Outfit',sans-serif;
}
.item-actions .act-btn:hover.update { border-color:var(--green-mid); background:var(--surface2); color:var(--green-deep); }
.item-actions .act-btn:hover.batches { border-color:#1d4ed8; background:#eff6ff; color:#1d4ed8; }
.item-actions .act-btn:hover.del     { border-color:#c0392b; background:#fff1f2; color:#c0392b; }
.item-actions .act-btn-full {
  display:flex; align-items:center; justify-content:center; gap:5px;
  width:100%; padding:6px; border-radius:7px; font-size:.68rem; font-weight:600;
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff; border:none; text-decoration:none; transition:all .2s; font-family:'Outfit',sans-serif;
}
.item-actions .act-btn-full:hover { color:#fff; transform:translateY(-1px); }

/* ── Modal ── */
.modal-custom .modal-content {
  border:none; border-radius:var(--radius);
  box-shadow:0 20px 60px rgba(0,0,0,.15); overflow:hidden;
}
.modal-custom .modal-header {
  padding:14px 20px; border-bottom:1px solid var(--border); background:var(--surface);
}
.modal-custom .modal-header.danger { background:linear-gradient(135deg,#c0392b,#e74c3c); }
.modal-custom .modal-header.green  { background:linear-gradient(135deg,var(--green-deep),var(--green-mid)); }
.modal-custom .modal-header.amber  { background:linear-gradient(135deg,#c08000,var(--amber)); }
.modal-custom .modal-title { font-size:.85rem; font-weight:800; color:#fff; display:flex; align-items:center; gap:8px; }
.modal-custom .btn-close-white { filter:brightness(10); }
.modal-custom .modal-body { padding:20px; font-size:.78rem; color:var(--text-2); }
.modal-custom .modal-footer { padding:12px 20px; border-top:1px solid var(--border); background:var(--bg); display:flex; gap:8px; justify-content:flex-end; }

/* ── Scan modal ── */
#batchReader { width:100%; min-height:300px; background:#000; }
#batchReader video { width:100%; height:auto; object-fit:cover; }
#batchReader canvas { display:none; }
.scan-status {
  background:#0f172a; color:#fff; padding:10px 14px;
  display:flex; align-items:center; justify-content:center; gap:8px;
  font-size:.7rem; font-weight:600;
}
.scan-pulse { width:8px; height:8px; border-radius:50%; background:var(--green-mid); animation:pulse 1.5s infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.8)} }
.scan-result {
  display:none; padding:10px 14px; border-radius:8px; font-size:.72rem; font-weight:600; margin-top:10px;
}
.scan-result.success { background:#f0fdf4; color:var(--green-deep); border:1px solid var(--green-glow); }
.scan-result.danger  { background:#fff1f2; color:#c0392b; border:1px solid #fecaca; }

/* ── Low stock list ── */
.low-stock-list { list-style:none; padding:0; margin:0; }
.low-stock-list li {
  display:flex; align-items:center; justify-content:space-between;
  padding:7px 0; border-bottom:1px solid var(--border); font-size:.72rem;
}
.low-stock-list li:last-child { border-bottom:none; }

/* ── Empty state ── */
.empty-state { text-align:center; padding:40px 20px; color:var(--text-3); }
.empty-state i { font-size:2rem; display:block; margin-bottom:8px; opacity:.3; }
.empty-state p { font-size:.75rem; margin:0; }

/* ── Section tabs (Cooked / Canned filter) ── */
.section-tab-wrap {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  padding:0 20px;
}
.section-tab-wrap .tab-nav {
  display:flex; gap:0; list-style:none; margin:0; padding:0;
  border-bottom:1px solid var(--border);
}
.section-tab-wrap .tab-nav li button {
  display:inline-flex; align-items:center; gap:6px;
  padding:12px 16px; font-size:.72rem; font-weight:600;
  color:var(--text-3); background:none; border:none;
  border-bottom:2px solid transparent; cursor:pointer;
  transition:all .2s; font-family:'Outfit',sans-serif; margin-bottom:-1px;
}
.section-tab-wrap .tab-nav li button:hover { color:var(--green-deep); }
.section-tab-wrap .tab-nav li button.active { color:var(--green-deep); border-bottom-color:var(--green-mid); }

/* ── Two-section item list (Cooked Foods / Canned Goods) ── */
.items-section-title {
  display:flex; align-items:center; gap:8px;
  font-size:.74rem; font-weight:800; color:var(--text-1);
  margin:0 0 14px; text-transform:uppercase; letter-spacing:.4px;
}
.items-section.divider { border-top:1px solid var(--border); }
.items-section.no-radius { border-radius:0; }
</style>

<?php
$userRole = session()->get('role');
$canModify = in_array($userRole, ['admin', 'staff']);
?>

<div class="page-wrap">

  <!-- ── Page Header ── -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-boxes-stacked me-2" style="color:var(--green-mid)"></i>Inventory Management</h5>
      <p>
        <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--green-mid);margin-right:5px;vertical-align:middle"></span>
        Monitor and manage relief goods stock levels with batch tracking
      </p>
    </div>
    <div class="page-actions">
      <?php if($canModify): ?>
      <a href="/inventory/add_item" class="btn-primary-c">
        <i class="fa-solid fa-plus"></i> New Relief Item
      </a>
      <?php endif; ?>
      <button type="button" class="btn-orange-c" data-bs-toggle="modal" data-bs-target="#scanDispatchModal">
        <i class="fa-solid fa-qrcode"></i> Scan Batch
      </button>
      <?php if($canModify): ?>
      <a href="/inventory/batch/report" class="btn-outline-c">
        <i class="fa-solid fa-chart-line"></i> Batch Report
      </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- ── KPI Cards ── -->
  <div class="kpi-grid">
    <div class="kpi-card kpi-hero">
      <div class="kpi-icon"><i class="fa-solid fa-box"></i></div>
      <div class="kpi-label">Total Items</div>
      <div class="kpi-val"><?= count($items) ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon green"><i class="fa-solid fa-layer-group"></i></div>
      <div class="kpi-label">Active Batches</div>
      <div class="kpi-val"><?= $batch_summary['active_batches'] ?? 0 ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon blue"><i class="fa-solid fa-warehouse"></i></div>
      <div class="kpi-label">Total Stock</div>
      <div class="kpi-val"><?= number_format($batch_summary['total_quantity'] ?? 0) ?></div>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon amber"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="kpi-label">Low Stock Alerts</div>
      <div class="kpi-val"><?= $low_stock_count ?? 0 ?></div>
    </div>
  </div>

  <!-- ── Source Summary ── -->
  <?php if(!empty($source_summary)): ?>
  <div class="source-bar">
    <div class="source-label">Stock by Source</div>
    <div class="source-bar-inner">
      <?php foreach($source_summary as $source): ?>
      <div class="source-item">
        <?php if($source['source_type'] == 'donated'): ?>
          <i class="fa-solid fa-hand-holding-heart si-icon" style="color:var(--green-mid)"></i>
        <?php elseif($source['source_type'] == 'procured'): ?>
          <i class="fa-solid fa-cart-shopping si-icon" style="color:#1d4ed8"></i>
        <?php else: ?>
          <i class="fa-solid fa-building-flag si-icon" style="color:var(--orange-mid)"></i>
        <?php endif; ?>
        <div>
          <div class="si-label"><?= $source['source_type'] ?></div>
          <div class="si-val"><?= number_format($source['total']) ?> units</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── Section Tabs ── -->
  <div class="section-tab-wrap">
    <ul class="tab-nav" id="invSectionTabs">
      <li><button class="active" data-section="all" type="button"><i class="fa-solid fa-layer-group"></i> All</button></li>
      <li><button data-section="cooked" type="button"><i class="fa-solid fa-bowl-food"></i> Cooked Foods</button></li>
      <li><button data-section="canned" type="button"><i class="fa-solid fa-box"></i> Canned Goods</button></li>
    </ul>
  </div>

  <!-- ── Cooked Foods Inventory ── -->
  <div class="items-section no-radius" id="section-cooked" data-panel="cooked">
    <p class="items-section-title"><i class="fa-solid fa-bowl-food" style="color:var(--orange-mid)"></i>Cooked Foods Inventory</p>
    <?php if(empty($cooked_items)): ?>
      <div class="empty-state">
        <i class="fa-solid fa-bowl-food"></i>
        <p>No cooked food items found.</p>
      </div>
    <?php else: ?>
    <div class="items-grid">
      <?php foreach($cooked_items as $item): ?>
      <div class="item-card">
        <div class="item-card-body">

          <!-- Top row: icon + badges -->
          <div class="item-card-top">
            <div class="item-icon" style="color:var(--orange-mid)"><i class="fa-solid fa-bowl-food"></i></div>
            <div class="item-badges">
              <?php if($item['quantity'] <= 50): ?>
                <span class="badge2 danger"><i class="fa-solid fa-circle-exclamation"></i>Low Stock</span>
              <?php else: ?>
                <span class="badge2 success"><i class="fa-solid fa-circle-check"></i>In Stock</span>
              <?php endif; ?>
              <?php if(($item['distribution_type'] ?? '') === 'per_family_member'): ?>
                <span class="badge2 blue"><i class="fa-solid fa-people-group"></i>Auto by Family</span>
              <?php else: ?>
                <span class="badge2 neutral"><i class="fa-solid fa-house"></i>Per Household</span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Name + distribution -->
          <div class="item-name"><?= esc($item['item_name']) ?></div>
          <div class="item-alloc">
            <?= (($item['distribution_type'] ?? '') === 'per_family_member')
                  ? '1 serving per family member (auto)'
                  : '1 serving per household' ?>
          </div>

<!-- Quantity -->
          <div class="item-qty-row">
            <span class="item-qty"><?= number_format($item['quantity']) ?></span>
            <span class="item-unit">servings remaining</span>
          </div>
          <?php if(!empty($item['original_quantity'])): ?>
          <div class="item-alloc" style="margin-top:-4px;margin-bottom:10px;">
            <i class="fa-solid fa-rotate" style="color:var(--green-mid)"></i>
            Auto-resets to <?= number_format($item['original_quantity']) ?> when depleted
          </div>
          <?php endif; ?>

        </div>

        <!-- Footer actions -->
        <div class="item-card-footer">
          <?php if($canModify): ?>
          <div class="item-actions">
            <button type="button" class="act-btn del" onclick="confirmDelete(<?= $item['id'] ?>, '<?= esc($item['item_name']) ?>')">
              <i class="fa-solid fa-trash-can"></i> Delete
            </button>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── Canned Goods Inventory ── -->
  <div class="items-section divider" id="section-canned" data-panel="canned">
    <p class="items-section-title"><i class="fa-solid fa-box" style="color:var(--green-mid)"></i>Canned Goods Inventory</p>
    <?php if(empty($canned_items)): ?>
      <div class="empty-state">
        <i class="fa-solid fa-box-open"></i>
        <p>No canned goods items found.</p>
      </div>
    <?php else: ?>
    <div class="items-grid">
      <?php foreach($canned_items as $item): ?>
      <div class="item-card">
        <div class="item-card-body">

          <!-- Top row: icon + badges -->
          <div class="item-card-top">
            <div class="item-icon"><i class="fa-solid fa-box"></i></div>
            <div class="item-badges">
              <?php if($item['quantity'] <= 50): ?>
                <span class="badge2 danger"><i class="fa-solid fa-circle-exclamation"></i>Low Stock</span>
              <?php else: ?>
                <span class="badge2 success"><i class="fa-solid fa-circle-check"></i>In Stock</span>
              <?php endif; ?>
              <?php if(isset($item['batch_count']) && $item['batch_count'] > 1): ?>
                <span class="badge2 blue"><?= $item['batch_count'] ?> batches</span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Name + alloc -->
          <div class="item-name"><?= esc($item['item_name']) ?></div>
          <div class="item-alloc">Allocation: <strong><?= $item['allocation'] ?> <?= $item['unit_type'] ?></strong> per household</div>

          <!-- Quantity -->
          <div class="item-qty-row">
            <span class="item-qty"><?= number_format($item['quantity']) ?></span>
            <span class="item-unit"><?= $item['unit_type'] ?>s remaining</span>
          </div>

          <!-- Batches collapse -->
          <?php if(isset($item['batches']) && !empty($item['batches'])): ?>
          <button class="batches-toggle" type="button" onclick="toggleBatches(this, 'batches-<?= $item['id'] ?>')">
            <i class="fa-solid fa-chevron-down"></i> View Batches
          </button>
          <div class="batches-list" id="batches-<?= $item['id'] ?>">
            <?php foreach(array_slice($item['batches'], 0, 3) as $batch): ?>
            <div class="batch-item">
              <div>
                <div class="batch-num"><?= $batch['batch_number'] ?></div>
                <div class="batch-meta"><?= ucfirst($batch['source_type']) ?></div>
              </div>
              <div style="display:flex;align-items:center;gap:8px">
                <span class="batch-qty"><?= number_format($batch['quantity_remaining']) ?>/<?= number_format($batch['quantity_initial']) ?></span>
                <a href="/inventory/batch/view/<?= $batch['id'] ?>" class="act-btn" title="View Batch" style="width:24px;height:24px;border-radius:6px;border:1.5px solid var(--border);background:var(--surface);display:inline-flex;align-items:center;justify-content:center;font-size:.6rem;color:var(--text-3);text-decoration:none">
                  <i class="fa-solid fa-qrcode"></i>
                </a>
              </div>
            </div>
            <?php endforeach; ?>
            <?php if(count($item['batches']) > 3): ?>
              <a href="/inventory/batch/item/<?= $item['id'] ?>" style="font-size:.62rem;color:var(--green-mid);font-weight:600;text-decoration:none;display:block;margin-top:4px">
                +<?= count($item['batches']) - 3 ?> more batches →
              </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>

        </div>

        <!-- Footer actions -->
        <div class="item-card-footer">
          <?php if($canModify): ?>
          <div class="item-actions">
            <a href="/inventory/update_stock/<?= $item['id'] ?>" class="act-btn update">
              <i class="fa-solid fa-arrows-rotate"></i> Update
            </a>
            <a href="/inventory/batches/<?= $item['id'] ?>" class="act-btn batches">
              <i class="fa-solid fa-layer-group"></i> Batches
            </a>
            <button type="button" class="act-btn del" onclick="confirmDelete(<?= $item['id'] ?>, '<?= esc($item['item_name']) ?>')">
              <i class="fa-solid fa-trash-can"></i> Delete
            </button>
          </div>
          <?php else: ?>
          <div class="item-actions">
            <a href="/inventory/batches/<?= $item['id'] ?>" class="act-btn-full">
              <i class="fa-solid fa-eye"></i> View Batches
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

</div>

<!-- ══ Delete Confirmation Modal ══ -->
<div class="modal fade modal-custom" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header danger">
        <div class="modal-title"><i class="fa-solid fa-triangle-exclamation"></i> Confirm Delete</div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="deleteItemName"></strong>?</p>
        <div style="background:#fff1f2;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;font-size:.72rem;color:#c0392b;margin-top:8px">
          <strong><i class="fa-solid fa-exclamation-circle me-1"></i>This will permanently delete:</strong>
          <ul style="margin:6px 0 0;padding-left:16px">
            <li>The inventory item</li>
            <li>All associated batches</li>
            <li>All distribution records for this item</li>
          </ul>
          <p style="margin:6px 0 0"><strong>This action cannot be undone!</strong></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-c" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" style="display:inline-flex;align-items:center;gap:5px;padding:7px 16px;border-radius:8px;background:linear-gradient(135deg,#c0392b,#e74c3c);color:#fff;border:none;font-size:.72rem;font-weight:700;text-decoration:none;cursor:pointer">
          <i class="fa-solid fa-trash-can"></i> Delete Permanently
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ══ Low Stock Alert Modal ══ -->
<?php if(($low_stock_count ?? 0) > 0): ?>
<div class="modal fade modal-custom" id="lowStockModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header amber">
        <div class="modal-title"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock Alert</div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p style="margin-bottom:12px">The following items are running low on stock:</p>
        <ul class="low-stock-list">
          <?php foreach($items as $item): ?>
            <?php if($item['quantity'] <= 50): ?>
            <li>
              <span><?= esc($item['item_name']) ?></span>
              <span class="badge2 danger"><?= number_format($item['quantity']) ?> left</span>
            </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-c" data-bs-dismiss="modal">Close</button>
        <a href="/inventory/add_item" class="btn-primary-c"><i class="fa-solid fa-plus"></i> Add Stock</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ══ Scan Batch Modal ══ -->
<div class="modal fade modal-custom" id="scanDispatchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header green">
        <div class="modal-title"><i class="fa-solid fa-qrcode"></i> Scan Batch QR Code</div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p style="font-size:.7rem;color:var(--text-3);margin-bottom:12px">Scan a batch QR code to update its status:</p>

        <!-- Scanner -->
        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border)">
          <div id="batchReader"></div>
          <div class="scan-status">
            <span class="scan-pulse"></span>
            Camera Active — Point at Batch QR Code
          </div>
        </div>

        <input type="hidden" id="batchManualQrInput" value="">

        <!-- Action buttons -->
        <div style="display:flex;gap:8px;justify-content:center;margin-top:14px">
          <button type="button" class="btn-orange-c" onclick="processBatchScan('transit')" id="batchTransitBtn">
            <i class="fa-solid fa-truck"></i> Mark as In Transit
          </button>
          <button type="button" class="btn-primary-c" onclick="processBatchScan('received')" id="batchReceiveBtn">
            <i class="fa-solid fa-circle-check"></i> Mark as Received
          </button>
        </div>

        <div id="batchScanResult" class="scan-result"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-c" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
// ── Batch toggle ──
function toggleBatches(btn, id) {
  const list = document.getElementById(id);
  const open = list.classList.toggle('open');
  btn.classList.toggle('open', open);
  btn.querySelector('span') && (btn.querySelector('span').textContent = open ? ' Hide Batches' : ' View Batches');
}

// ── Cooked Foods / Canned Goods section tabs ──
document.querySelectorAll('#invSectionTabs button').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('#invSectionTabs button').forEach(b => b.classList.remove('active'));
    this.classList.add('active');

    const section = this.dataset.section;
    const cooked = document.getElementById('section-cooked');
    const canned = document.getElementById('section-canned');

    cooked.style.display = (section === 'all' || section === 'cooked') ? '' : 'none';
    canned.style.display = (section === 'all' || section === 'canned') ? '' : 'none';
  });
});

// ── Delete modal ──
function confirmDelete(itemId, itemName) {
  document.getElementById('deleteItemName').textContent = itemName;
  document.getElementById('confirmDeleteBtn').href = '/inventory/delete/' + itemId;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// ── Low stock alert on load ──
window.addEventListener('load', function() {
  <?php if(($low_stock_count ?? 0) > 0): ?>
  new bootstrap.Modal(document.getElementById('lowStockModal')).show();
  <?php endif; ?>
});

// ── QR Scanner ──
let batchQrScanner = null;

function onBatchScanSuccess(decodedText) {
  if(batchQrScanner) batchQrScanner.clear();
  document.getElementById('batchManualQrInput').value = decodedText;
  showBatchResult('QR Code scanned! Click an action button to continue.', 'success');
}

function initBatchCameraScanner() {
  if(batchQrScanner) batchQrScanner.clear();
  batchQrScanner = new Html5QrcodeScanner('batchReader', { fps:10, qrbox:{width:250,height:250}, rememberLastUsedCamera:true }, false);
  batchQrScanner.render(onBatchScanSuccess);
}

function processBatchScan(action) {
  const qrToken = document.getElementById('batchManualQrInput').value.trim();
  const transitBtn = document.getElementById('batchTransitBtn');
  const receiveBtn = document.getElementById('batchReceiveBtn');
  if(!qrToken) { showBatchResult('Please scan a QR code first.', 'danger'); return; }

  transitBtn.disabled = receiveBtn.disabled = true;
  transitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing…';
  receiveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing…';

  const url = (action === 'transit') ? '/dispatch/scan-for-transit' : '/dispatch/scan-for-receipt';
  fetch(url, {
    method:'POST',
    headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' },
    body: JSON.stringify({ qr_token: qrToken, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
  })
  .then(r => r.json())
  .then(d => {
    showBatchResult(d.message, d.success ? 'success' : 'danger');
    if(d.success) {
      document.getElementById('batchManualQrInput').value = '';
      setTimeout(() => location.reload(), 2000);
    }
  })
  .catch(err => showBatchResult('Error: ' + err, 'danger'))
  .finally(() => {
    transitBtn.disabled = receiveBtn.disabled = false;
    transitBtn.innerHTML = '<i class="fa-solid fa-truck"></i> Mark as In Transit';
    receiveBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Mark as Received';
  });
}

function showBatchResult(msg, type) {
  const el = document.getElementById('batchScanResult');
  el.className = 'scan-result ' + type;
  el.style.display = 'block';
  el.textContent = msg;
  setTimeout(() => { el.style.display = 'none'; }, 5000);
}

document.getElementById('scanDispatchModal').addEventListener('shown.bs.modal', initBatchCameraScanner);
document.getElementById('scanDispatchModal').addEventListener('hidden.bs.modal', function() {
  if(batchQrScanner) { batchQrScanner.clear(); batchQrScanner = null; }
  document.getElementById('batchManualQrInput').value = '';
});
</script>

<?= $this->endSection() ?>