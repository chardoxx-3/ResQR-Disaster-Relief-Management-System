<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if($status == 'success'): ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Check-in Successful</h2>
                <p class="text-muted"><?= $message ?></p>
                <hr>
                <div class="text-start mb-4">
                    <label class="small text-muted">Name</label>
                    <h5 class="fw-bold"><?= $data['name'] ?? 'Beneficiary' ?></h5>
                    <label class="small text-muted mt-2">Time</label>
                    <p class="fw-bold"><?= date('h:i A', strtotime($data['check_in_time'])) ?></p>
                </div>
                <a href="/distribution/scanner" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">Next Scan</a>
            </div>
        <?php elseif($status == 'info'): ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-info text-info fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Already Checked In</h2>
                <p class="text-info fw-bold"><?= $message ?></p>
                <div class="text-start mb-4">
                    <label class="small text-muted">Check-in Time</label>
                    <p class="fw-bold"><?= date('h:i A', strtotime($data['check_in_time'])) ?></p>
                </div>
                <a href="/distribution/scanner" class="btn btn-dark btn-lg w-100 fw-bold mt-4">Return to Scanner</a>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-xmark text-danger fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Check-in Failed</h2>
                <p class="text-danger fw-bold"><?= $message ?></p>
                <p class="text-muted small">Please try again or contact administrator.</p>
                <a href="/distribution/scanner" class="btn btn-dark btn-lg w-100 fw-bold mt-4">Return to Scanner</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>