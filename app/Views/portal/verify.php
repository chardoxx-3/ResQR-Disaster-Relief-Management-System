<?= $this->extend('layout/auth_layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-0 overflow-hidden shadow-lg border-0">
            <div class="bg-primary p-4 text-white text-center">
                <i class="fa-solid fa-user-check fa-3x mb-3 opacity-50"></i>
                <h4 class="fw-bold m-0">Confirm Identity</h4>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="text-muted small d-block mb-1">Full Name</label>
                    <h5 class="fw-bold border-bottom pb-2 text-dark"><?= $resident['full_name'] ?></h5>
                </div>
                <div class="mb-4">
                    <label class="text-muted small d-block mb-1">Household Number</label>
                    <h5 class="fw-bold border-bottom pb-2 text-dark"><?= $resident['household_no'] ?></h5>
                </div>
                <div class="mb-4">
                    <label class="text-muted small d-block mb-1">Registered Address</label>
                    <p class="text-dark mb-0"><?= $resident['address'] ?></p>
                </div>

                <div class="alert alert-info py-2 small border-0">
                    <i class="fa-solid fa-circle-info me-1"></i> Is this information correct?
                </div>

                <div class="d-grid gap-2 mt-4">
                    <a href="/residentportal/generateQR/<?= $resident['id'] ?>" class="btn btn-primary btn-lg fw-bold">Yes, Generate My QR Code</a>
                    <a href="/residentportal" class="btn btn-light border fw-bold text-muted">No, Go Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>