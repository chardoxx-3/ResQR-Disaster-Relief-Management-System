<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">Distribution Logs</h4>
    <a href="/reports/export" target="_blank" class="btn btn-outline-dark btn-sm fw-bold">
        <i class="fa-solid fa-print me-2"></i>Generate PDF/Print
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-muted">
                    <th class="px-4 py-3">TIMESTAMP</th>
                    <th class="py-3">RESIDENT</th>
                    <th class="py-3">HOUSEHOLD #</th>
                    <th class="py-3">DISTRIBUTOR</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach($logs as $log): ?>
                <tr>
                    <td class="px-4"><?= date('M d, Y | h:i A', strtotime($log['claimed_at'])) ?></td>
                    <td class="fw-bold text-dark"><?= $log['full_name'] ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $log['household_no'] ?></span></td>
                    <td class="text-muted"><?= $log['distributor_name'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>