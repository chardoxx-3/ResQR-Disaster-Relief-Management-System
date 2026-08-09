<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">
            <a href="/users" class="text-decoration-none text-secondary me-2">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            Create New User Account
        </h4>
    </div>

    <!-- Error Messages -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Success/Error Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="/users/create" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Account Information Section -->
                        <h5 class="fw-bold mb-3">Account Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('email') ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="username" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['username']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('username') ?>"
                                       required>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <h5 class="fw-bold mb-3 mt-4">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="last_name" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['last_name']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('last_name') ?>"
                                       required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="first_name" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['first_name']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('first_name') ?>"
                                       required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" 
                                       name="middle_name" 
                                       class="form-control" 
                                       value="<?= old('middle_name') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Suffix</label>
                                <select name="suffix" class="form-select">
                                    <option value="">None</option>
                                    <option value="Jr." <?= old('suffix') == 'Jr.' ? 'selected' : '' ?>>Jr.</option>
                                    <option value="Sr." <?= old('suffix') == 'Sr.' ? 'selected' : '' ?>>Sr.</option>
                                    <option value="II" <?= old('suffix') == 'II' ? 'selected' : '' ?>>II</option>
                                    <option value="III" <?= old('suffix') == 'III' ? 'selected' : '' ?>>III</option>
                                    <option value="IV" <?= old('suffix') == 'IV' ? 'selected' : '' ?>>IV</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="birthdate" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['birthdate']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('birthdate') ?>"
                                       required
                                       onchange="calculateAge(this.value)">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Age</label>
                                <input type="number" 
                                       name="age" 
                                       id="age"
                                       class="form-control" 
                                       value="<?= old('age') ?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Birthplace <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="birthplace" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['birthplace']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('birthplace') ?>"
                                       placeholder="City/Municipality, Province"
                                       required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Sex <span class="text-danger">*</span></label>
                                <select name="sex" class="form-select" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male" <?= old('sex') == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= old('sex') == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= old('sex') == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <h5 class="fw-bold mb-3 mt-4">Address Information</h5>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">House/Lot No.</label>
                                <input type="text" 
                                       name="house_no" 
                                       class="form-control" 
                                       value="<?= old('house_no') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Street</label>
                                <input type="text" 
                                       name="street" 
                                       class="form-control" 
                                       value="<?= old('street') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Subdivision/Village</label>
                                <input type="text" 
                                       name="subdivision" 
                                       class="form-control" 
                                       value="<?= old('subdivision') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Barangay <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="barangay" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['barangay']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('barangay') ?>"
                                       placeholder="Barangay name"
                                       required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">City/Municipality <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="city" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['city']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('city') ?>"
                                       required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Province <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="province" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['province']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('province') ?>"
                                       required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Zip Code <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="zip_code" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['zip_code']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('zip_code') ?>"
                                       maxlength="4"
                                       required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Contact No. <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="contact_number" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['contact_number']) ? 'is-invalid' : '' ?>" 
                                       value="<?= old('contact_number') ?>"
                                       placeholder="09XXXXXXXXX"
                                       required>
                            </div>
<div class="col-md-2 mb-3">
    <label class="form-label fw-bold">Position</label>  <!-- Changed label -->
    <input type="text" 
           name="position"                               
           class="form-control" 
           value="<?= old('position') ?>"                
           placeholder="e.g. Captain, Secretary">        <!-- Updated placeholder -->
</div>
                        </div>

                        <!-- System Information Section -->
                        <h5 class="fw-bold mb-3 mt-4">System Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control <?= session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password']) ? 'is-invalid' : '' ?>" 
                                       required
                                       minlength="6">
                                <div class="form-text">Minimum 6 characters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select">
                                    <option value="distributor" <?= old('role') == 'distributor' ? 'selected' : '' ?>>Relief Distributor</option>
                                    <option value="barangay" <?= old('role') == 'barangay' ? 'selected' : '' ?>>Barangay Official</option>
                                    <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>System Administrator</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="fa-solid fa-save me-2"></i>Create Account
                            </button>
                            <a href="/users" class="btn btn-light fw-bold px-4 border">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateAge(birthdate) {
    if (birthdate) {
        const today = new Date();
        const birthDate = new Date(birthdate);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        document.getElementById('age').value = age;
    }
}
</script>

<?= $this->endSection() ?>