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

.page-wrap { animation: fadeUp .45s ease both; max-width:800px; margin:0 auto; }
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none} }

.page-header {
  background:var(--surface); border-radius:var(--radius) var(--radius) 0 0;
  padding:16px 20px; border:1px solid var(--border); border-bottom:none;
}
.page-title h5 { font-size:1rem; font-weight:800; color:var(--text-1); margin:0; }

.form-card {
  background:var(--surface); border:1px solid var(--border); border-top:none;
  border-radius:0 0 var(--radius) var(--radius); padding:24px;
}

.form-label { font-size:.7rem; font-weight:700; color:var(--text-2); margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px; }
.form-control, .form-select {
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  padding:8px 12px; font-size:.8rem; transition:all .2s;
}
.form-control:focus, .form-select:focus {
  border-color:var(--green-mid); box-shadow:0 0 0 3px rgba(119,188,63,.1); outline:none;
}

.btn-primary-c {
  background:linear-gradient(135deg,var(--green-deep),var(--green-mid));
  color:#fff; border:none; border-radius:8px; padding:8px 20px; font-size:.8rem; font-weight:600;
}
.btn-outline-c {
  background:transparent; color:var(--text-2); border:1.5px solid var(--border);
  border-radius:8px; padding:8px 20px; font-size:.8rem; font-weight:600;
}
.btn-outline-c:hover { border-color:var(--green-mid); color:var(--green-deep); background:var(--surface2); }
</style>

<div class="page-wrap">
  
  <div class="page-header">
    <div class="page-title">
      <h5><i class="fa-solid fa-pencil me-2" style="color:var(--green-mid)"></i>Edit Event</h5>
    </div>
  </div>

  <div class="form-card">
    <form action="/events/update/<?= $event['id'] ?>" method="post">
      <?= csrf_field() ?>
      
<div class="row g-3">
    <div class="col-md-12">
        <label class="form-label">Event Name <span class="text-danger">*</span></label>
        <input type="text" name="event_name" class="form-control" required value="<?= esc($event['event_name']) ?>">
    </div>
    
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2"><?= esc($event['description']) ?></textarea>
    </div>

    <div class="col-12">
    <label class="form-label">Evacuation Center</label>
    <input type="text" name="evacuation_center" id="evacuation_center" class="form-control" autocomplete="off" value="<?= esc($event['evacuation_center']) ?>">
    <div id="evacSuggestions" class="list-group position-absolute" style="z-index:1000;"></div>
</div>
    
    <div class="col-md-6">
        <label class="form-label">Start Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control" required value="<?= $event['start_date'] ?>">
    </div>
    
    <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="<?= $event['end_date'] ?>">
    </div>
    
    <div class="col-12 mt-4 d-flex gap-2 justify-content-end">
        <a href="/events" class="btn-outline-c">Cancel</a>
        <button type="submit" class="btn-primary-c">Update Event</button>
    </div>
</div>
    </form>
  </div>
</div>

<script>
const evacInput = document.getElementById('evacuation_center');
const evacBox = document.getElementById('evacSuggestions');
let evacTimeout;

evacInput.addEventListener('input', function() {
    clearTimeout(evacTimeout);
    const term = this.value.trim();
    evacBox.innerHTML = '';
    if (term.length < 1) return;

    evacTimeout = setTimeout(() => {
        fetch('/events/suggest-evacuation-centers?term=' + encodeURIComponent(term))
            .then(res => res.json())
            .then(data => {
                evacBox.innerHTML = '';
                data.forEach(name => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.textContent = name;
                    item.style.fontSize = '.8rem';
                    item.onclick = (e) => {
                        e.preventDefault();
                        evacInput.value = name;
                        evacBox.innerHTML = '';
                    };
                    evacBox.appendChild(item);
                });
            });
    }, 250);
});

document.addEventListener('click', function(e) {
    if (e.target !== evacInput) evacBox.innerHTML = '';
});
</script>

<?= $this->endSection() ?>