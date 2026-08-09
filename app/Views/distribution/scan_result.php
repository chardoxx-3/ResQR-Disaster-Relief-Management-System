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
                <p class="text-muted"><?= $message ?></p>
                <hr>
                <div class="text-start mb-4">
                    <label class="small text-muted">Beneficiary Name</label>
                    <h5 class="fw-bold"><?= $resident['full_name'] ?></h5>
                    
                    <label class="small text-muted mt-2">Household No.</label>
                    <p class="fw-bold"><?= $resident['household_no'] ?></p>
                    
                    <?php if(isset($items_distributed) && is_array($items_distributed)): ?>
                        <label class="small text-muted mt-2">Items Distributed</label>
                        <div class="mt-2">
                            <?php foreach($items_distributed as $item): ?>
                            <div class="border-bottom py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><?= $item['item_name'] ?></span>
                                    <span class="badge bg-primary rounded-pill">
                                        <?= $item['quantity'] ?> <?= $item['unit_type'] ?>
                                    </span>
                                </div>
                                <small class="text-muted d-block">
                                    <i class="fa-solid fa-cube me-1"></i>Batch: <?= $item['batch_number'] ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if(count($items_distributed) > 1): ?>
                            <div class="mt-2 text-end">
                                <span class="badge bg-success">
                                    Total: <?= array_sum(array_column($items_distributed, 'quantity')) ?> units
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif(isset($items_distributed) && is_numeric($items_distributed)): ?>
                        <!-- Backward compatibility for old format -->
                        <label class="small text-muted mt-2">Items Distributed</label>
                        <p class="fw-bold"><?= $items_distributed ?> items</p>
                    <?php endif; ?>
                </div>
                <a href="/distribution/scanner" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                    <i class="fa-solid fa-qrcode me-2"></i>Next Scan
                </a>
                <a href="/distribution/today-stats" class="btn btn-link text-muted btn-sm mt-2">View Today's Stats</a>
            </div>
        <?php elseif($status == 'denied'): ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-exclamation text-warning fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Already Claimed</h2>
                <p class="text-warning fw-bold"><?= $message ?></p>
                <?php if(isset($resident['full_name'])): ?>
                    <p class="fw-bold"><?= $resident['full_name'] ?></p>
                <?php endif; ?>
                
                <?php if(isset($distribution_history) && !empty($distribution_history)): ?>
                    <div class="text-start mt-3 p-3 bg-light rounded">
                        <label class="small text-muted">Distribution History</label>
                        <p class="small mb-0">Claimed on: <?= date('F d, Y h:i A', strtotime($distribution_history['claimed_at'])) ?></p>
                        <p class="small">Distributed by: <?= $distribution_history['distributor_name'] ?></p>
                    </div>
                <?php endif; ?>
                
                <a href="/distribution/scanner" class="btn btn-dark btn-lg w-100 fw-bold mt-4">
                    <i class="fa-solid fa-arrow-left me-2"></i>Return to Scanner
                </a>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-lg text-center p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-xmark text-danger fa-5x"></i>
                </div>
                <h2 class="fw-bold text-dark">Scan Failed</h2>
                <p class="text-danger fw-bold"><?= $message ?></p>
                <p class="text-muted small">Please ensure the QR code is valid and try again.</p>
                
                <?php if(strpos($message, 'stock') !== false): ?>
                <div class="alert alert-warning mt-3 small">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Contact inventory manager to restock supplies.
                </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2">
                    <a href="/distribution/scanner" class="btn btn-dark btn-lg fw-bold mt-4">
                        <i class="fa-solid fa-arrow-left me-2"></i>Return to Scanner
                    </a>
                    <?php if(strpos($message, 'stock') !== false): ?>
                    <a href="/inventory" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-boxes me-2"></i>Check Inventory
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if($status == 'success'): ?>
<script>
    // Auto-refresh today's stats after successful distribution
    setTimeout(function() {
        fetch('/distribution/today-stats')
            .then(response => response.json())
            .then(data => {
                // Optionally update a badge or notification
                console.log('Today\'s total distributions:', data.today);
            })
            .catch(error => console.error('Error:', error));
    }, 1000);
    
    // Optional: Auto-redirect after 10 seconds
    setTimeout(function() {
        window.location.href = '/distribution/scanner';
    }, 10000); // 10 seconds
</script>
<?php endif; ?>

<?= $this->endSection() ?>