<?= $this->extend('layout/auth_layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center mb-4">
            <div class="bg-success d-inline-block p-3 rounded-circle text-white mb-3 shadow">
                <i class="fa-solid fa-check fa-2x"></i>
            </div>
            <h2 class="fw-bold">Welcome, <?= esc($full_name) ?>!</h2>
            <p class="text-muted">Your QR code and safety information</p>
        </div>

        <!-- QR Code Card -->
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-body p-4 text-center">
                <h4 class="card-title mb-3">
                    <i class="fa-solid fa-qrcode me-2 text-primary"></i>
                    Your QR Code
                </h4>
                
                <div class="qr-container p-3 bg-light rounded d-inline-block mb-3">
                    <?php if(isset($qr_token) && !empty($qr_token)): ?>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qr_token) ?>" 
                             alt="QR Code" 
                             class="img-fluid"
                             style="width: 200px; height: 200px;">
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            QR code not available
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
                    <a href="<?= base_url('residentportal/print-qr/' . ($member_id ?? $resident_id)) ?>" 
                       class="btn btn-outline-primary me-md-2" 
                       target="_blank">
                        <i class="fa-solid fa-print me-2"></i>Print QR Code
                    </a>
                    <a href="<?= base_url('residentportal/download-qr/' . ($member_id ?? $resident_id)) ?>" 
                       class="btn btn-outline-success">
                        <i class="fa-solid fa-download me-2"></i>Download QR Code
                    </a>
                </div>
                
                <p class="text-muted small mt-3 mb-0">
                    <i class="fa-regular fa-circle-check text-success me-1"></i>
                    Present this QR code at the evacuation center for assistance
                </p>
            </div>
        </div>

        <!-- Household Information Card -->
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fa-solid fa-users me-2 text-primary"></i>
                    Household Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Household No.:</th>
                                <td><strong><?= esc($household_no ?? 'N/A') ?></strong></td>
                            </tr>
                            <tr>
                                <th>Full Name:</th>
                                <td><?= esc($full_name) ?></td>
                            </tr>
                            <tr>
                                <th>Birthdate:</th>
                                <td><?= isset($birthdate) ? date('F d, Y', strtotime($birthdate)) : 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Age:</th>
                                <td><?= esc($age ?? 'N/A') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Barangay:</th>
                                <td><?= esc($barangay ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>City/Municipality:</th>
                                <td><?= esc($city_municipality ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Province:</th>
                                <td><?= esc($province ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <?php if(($status ?? '') === 'claimed'): ?>
                                        <span class="badge bg-success">Claimed</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Safety Announcements Card -->
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-warning bg-opacity-25">
                <h5 class="mb-0">
                    <i class="fa-solid fa-bullhorn me-2 text-warning"></i>
                    Safety Guides & Announcements
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    <strong>Current Weather Alert:</strong> Stay updated with the latest weather conditions from PAGASA.
                </div>

                <!-- Disaster Preparedness Tips -->
                <div class="accordion" id="safetyAccordion">
                    <!-- Typhoon Safety -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#typhoon">
                                <i class="fa-solid fa-cloud-rain me-2 text-primary"></i>
                                Typhoon Safety Tips
                            </button>
                        </h2>
                        <div id="typhoon" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Monitor weather updates and advisories</li>
                                    <li>Prepare emergency kit with food, water, and medicine</li>
                                    <li>Secure your home and evacuate when necessary</li>
                                    <li>Charge mobile phones and power banks</li>
                                    <li>Keep important documents in waterproof containers</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Earthquake Safety -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#earthquake">
                                <i class="fa-solid fa-house-crack me-2 text-danger"></i>
                                Earthquake Safety Tips
                            </button>
                        </h2>
                        <div id="earthquake" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>DROP:</strong> Drop to the ground</li>
                                    <li><strong>COVER:</strong> Take cover under sturdy furniture</li>
                                    <li><strong>HOLD ON:</strong> Hold on until shaking stops</li>
                                    <li>Stay away from windows and falling objects</li>
                                    <li>Evacuate to open areas after shaking stops</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Fire Safety -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fire">
                                <i class="fa-solid fa-fire me-2 text-danger"></i>
                                Fire Safety Tips
                            </button>
                        </h2>
                        <div id="fire" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Know your building's fire exits and evacuation plan</li>
                                    <li>Keep flammable materials away from heat sources</li>
                                    <li>Check electrical connections regularly</li>
                                    <li>Never leave cooking unattended</li>
                                    <li>Have fire extinguishers and know how to use them</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Flood Safety -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flood">
                                <i class="fa-solid fa-water me-2 text-info"></i>
                                Flood Safety Tips
                            </button>
                        </h2>
                        <div id="flood" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li>Evacuate immediately when flood waters rise</li>
                                    <li>Avoid walking or driving through flood waters</li>
                                    <li>Move to higher ground and stay there</li>
                                    <li>Avoid electrical equipment in wet areas</li>
                                    <li>Boil water before drinking during floods</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Hotlines -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="fw-bold">
                        <i class="fa-solid fa-phone-alt me-2 text-success"></i>
                        Emergency Hotlines:
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="d-block"><i class="fa-solid fa-phone me-1"></i> National Emergency: 911</small>
                            <small class="d-block"><i class="fa-solid fa-phone me-1"></i> Red Cross: 143</small>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block"><i class="fa-solid fa-phone me-1"></i> PAGASA: (02) 8926-4258</small>
                            <small class="d-block"><i class="fa-solid fa-phone me-1"></i> NDRRMC: (02) 8911-5061</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Search Button -->
        <div class="text-center">
            <a href="<?= base_url('residentportal') ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Search
            </a>
        </div>
    </div>
</div>

<style>
.qr-container {
    border: 2px dashed #dee2e6;
}
.accordion-button:not(.collapsed) {
    background-color: #e7f1ff;
    color: #0a58ca;
}
</style>
<?= $this->endSection() ?>