<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if($status == 'success'): ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Distribution Confirmed</h2>
                <p class="text-muted">Goods have been allocated to the resident.</p>
                <hr>
                <div class="text-start mb-4">
                    <label class="small text-muted">Beneficiary Name</label>
                    <h5 class="fw-bold"><?= $resident['full_name'] ?></h5>
                    <label class="small text-muted mt-2">Household</label>
                    <p class="fw-bold"><?= $resident['household_no'] ?></p>
                </div>
                <a href="/distribution/scanner" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">Next Scan</a>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-xmark text-danger fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Scan Denied</h2>
                <p class="text-danger fw-bold"><?= $message ?></p>
                <p class="text-muted small">This resident may have already claimed their relief or the QR is invalid.</p>
                <a href="/distribution/scanner" class="btn btn-dark btn-lg w-100 fw-bold mt-4">Return to Scanner</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>