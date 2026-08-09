<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
:root {
  --green-deep: #4a7a26;
  --green-mid: #77BC3F;
  --green-light: #99d15f;
  --orange-deep: #c96b10;
  --orange-mid: #F58220;
  --bg: #f8fafc;
  --surface: #ffffff;
  --surface2: #f0fdf4;
  --text-1: #1e293b;
  --text-2: #334155;
  --text-3: #64748b;
  --border: #e2e8f0;
  --radius: 14px;
  --radius-sm: 8px;
}

.page-wrap { animation: fadeUp .45s ease both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

/* Page Header */
.page-header {
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; }
.page-title p { font-size:.68rem; color:var(--text-3); margin:2px 0 0; }

.btn-primary-c {
  display:inline-flex; align-items:center; gap:5px;
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer;
  box-shadow:0 4px 12px rgba(119,188,63,.3);
}
.btn-primary-c:hover { transform:translateY(-1px); color:#fff; }

.btn-outline-c {
  display:inline-flex; align-items:center; gap:5px;
  background:transparent; color:var(--text-2);
  border:1.5px solid var(--border); border-radius:8px;
  padding:6px 12px; font-size:.7rem; font-weight:600;
  text-decoration:none; cursor:pointer;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }

/* Active Event Banner */
.active-banner {
  background:linear-gradient(135deg, var(--green-deep), var(--green-mid));
  color:white; padding:12px 20px; border-radius:var(--radius-sm);
  margin-bottom:16px; display:flex; align-items:center; justify-content:space-between;
}
.active-banner h6 { margin:0; font-weight:700; font-size:.8rem; }
.active-banner p { margin:2px 0 0; font-size:.7rem; opacity:.9; }

/* Table */
.table-card {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); overflow:hidden;
}
.rt { width:100%; border-collapse:collapse; font-size:.7rem; }
.rt thead th {
  background:var(--bg); padding:8px 10px; text-align:left;
  font-size:.6rem; font-weight:700; text-transform:uppercase;
  color:var(--text-3); border-bottom:1px solid var(--border);
}
.rt td { padding:10px; border-bottom:1px solid var(--border); vertical-align:middle; }

.badge2 {
  display:inline-flex; align-items:center; gap:3px;
  padding:2px 7px; border-radius:20px; font-size:.6rem; font-weight:600;
}
.badge2.green { background:#e8f5ee; color:var(--green-deep); }
.badge2.orange { background:#fef0e8; color:var(--orange-deep); }
.badge2.blue { background:#eff6ff; color:#1d4ed8; }
.badge2.gray { background:#f1f5f9; color:var(--text-3); }

.act-btn {
  width:26px; height:26px; border-radius:6px; border:1.5px solid var(--border);
  background:var(--surface); display:inline-flex; align-items:center; justify-content:center;
  font-size:.65rem; cursor:pointer; text-decoration:none; color:var(--text-3); margin:0 2px;
}
.act-btn:hover { border-color:var(--green-mid); background:#e8f5ee; color:var(--green-deep); }
</style>

<div class="page-wrap">
  
  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-calendar-days me-2" style="color:var(--green-mid)"></i>Events Management</h5>
      <p>Create and manage disaster events for tracking attendance and distribution</p>
    </div>
    <div class="page-actions">
      <a href="/events/create" class="btn-primary-c">
        <i class="fa-solid fa-plus"></i> New Event
      </a>
    </div>
  </div>

<?php if(!empty($active_events)): ?>
  <?php foreach($active_events as $active_event): ?>
  <div class="active-banner mb-2">
    <div>
      <h6><i class="fa-solid fa-circle-check me-2"></i>ACTIVE EVENT: <?= esc($active_event['event_name']) ?></h6>
      <p><?= esc($active_event['description']) ?> | <?= date('F j, Y', strtotime($active_event['start_date'])) ?> <?= $active_event['end_date'] ? ' - ' . date('F j, Y', strtotime($active_event['end_date'])) : '' ?></p>
    </div>
    <span class="badge bg-white text-success fw-bold px-3 py-2">ACTIVE</span>
  </div>
  <?php endforeach; ?>
  <?php else: ?>
  <div class="alert alert-warning border-0" style="background:#fff8e8;color:#c08000;border-radius:var(--radius-sm);">
    <i class="fa-solid fa-exclamation-triangle me-2"></i> No active events. Please activate at least one event to enable scanning.
  </div>
  <?php endif; ?>

  <!-- Events Table -->
  <div class="table-card">
    <table class="rt">
<thead>
    <tr>
        <th>Event Name</th>
        <th>Date Range</th>
        <th>Evacuation Center</th>
        <th>Status</th>
        <th>Stats</th>
        <th style="text-align:right">Actions</th>
    </tr>
</thead>
<tbody>
    <?php if(empty($events)): ?>
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="fa-solid fa-calendar-xmark fa-2x mb-2" style="color:var(--text-3)"></i>
            <p class="mb-0">No events found. Create your first event.</p>
        </td>
    </tr>
    <?php else: ?>
    <?php foreach($events as $event): ?>
    <tr>
        <td>
            <div class="fw-bold"><?= esc($event['event_name']) ?></div>
            <small class="text-muted"><?= esc($event['description']) ?></small>
        </td>
        <td>
            <?= date('M j, Y', strtotime($event['start_date'])) ?>
            <?= $event['end_date'] && $event['end_date'] != '0000-00-00' ? ' - ' . date('M j, Y', strtotime($event['end_date'])) : '' ?>
        </td>
        <td><?= esc($event['evacuation_center'] ?? '—') ?></td>
        <td>
            <?php if($event['is_active']): ?>
                <span class="badge2 green"><i class="fa-solid fa-circle-check"></i> ACTIVE</span>
            <?php else: ?>
                <span class="badge2 gray"><i class="fa-solid fa-circle"></i> CLOSED</span>
            <?php endif; ?>
        </td>
<td>
    <a href="/events/stats-detail/<?= $event['id'] ?>" class="act-btn" title="View detailed statistics">
        <i class="fa-solid fa-chart-pie"></i>
    </a>
</td>
<td style="text-align:right">
    <?php if($event['is_active']): ?>
        <!-- Close button for active events -->
        <a href="/events/close/<?= $event['id'] ?>" class="act-btn" title="Close Event" onclick="return confirm('Close this event? This will mark it as closed and deactivate it.')">
            <i class="fa-solid fa-door-closed"></i>
        </a>
    <?php else: ?>
        <!-- Activate button for inactive events -->
        <a href="/events/set-active/<?= $event['id'] ?>" class="act-btn" title="Activate Event" onclick="return confirm('Activate this event? This will deactivate other events.')">
            <i class="fa-solid fa-play"></i>
        </a>
    <?php endif; ?>
    
    <!-- Edit button (always visible) -->
    <a href="/events/edit/<?= $event['id'] ?>" class="act-btn" title="Edit">
        <i class="fa-solid fa-pencil"></i>
    </a>
    
    <!-- Delete button (only for inactive events) -->
    <?php if(!$event['is_active']): ?>
    <a href="/events/delete/<?= $event['id'] ?>" class="act-btn" title="Delete" onclick="return confirm('Delete this event?')">
        <i class="fa-solid fa-trash"></i>
    </a>
    <?php endif; ?>
</td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</tbody>
    </table>
  </div>
</div>

<!-- Stats Modal -->
<div class="modal fade" id="statsModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Event Statistics</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center" id="statsContent">
        <div class="spinner-border text-success" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function showStats(eventId) {
    const modal = new bootstrap.Modal(document.getElementById('statsModal'));
    document.getElementById('statsContent').innerHTML = '<div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>';
    modal.show();
    
    fetch('/events/stats/' + eventId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('statsContent').innerHTML = `
                <div class="py-3">
                    <div class="mb-3">
                        <div class="display-4 fw-bold text-success">${data.attendance}</div>
                        <div class="small text-muted">Attendance Records</div>
                    </div>
                    <div>
                        <div class="display-4 fw-bold text-warning">${data.distribution}</div>
                        <div class="small text-muted">Distributions</div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            document.getElementById('statsContent').innerHTML = '<p class="text-danger">Error loading stats</p>';
        });
}
</script>

<?= $this->endSection() ?>