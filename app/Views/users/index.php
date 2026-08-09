<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">System User Accounts</h4>
    <a href="/users/create" class="btn btn-primary btn-sm px-3 fw-bold">
        <i class="fa-solid fa-user-plus me-2"></i>Create New Account
    </a>
</div>

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
    <?php if(empty($users)): ?>
        <div class="col-12">
            <div class="alert alert-info">No users found. Create your first user!</div>
        </div>
    <?php else: ?>
        <?php foreach($users as $user): ?>
        <div class="col-md-4 mb-4" id="user-<?= $user['id'] ?>">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <?php 
$iconBg = match($user['role']) {
    'admin' => 'bg-primary',
    'distributor' => 'bg-secondary',
    'barangay' => 'bg-success',
    default => 'bg-secondary'
};
$textColor = $user['role'] == 'admin' ? 'text-primary' : ($user['role'] == 'barangay' ? 'text-success' : 'text-secondary');
?>
<div class="<?= $iconBg ?> bg-opacity-10 p-3 rounded-circle <?= $textColor ?> me-3">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold m-0"><?= $user['username'] ?></h6>
                            <?php 
$roleBadge = match($user['role']) {
    'admin' => 'bg-primary',
    'distributor' => 'bg-info text-dark',
    'barangay' => 'bg-success',
    default => 'bg-secondary'
};
?>
<span class="badge <?= $roleBadge ?> small-badge uppercase"><?= $user['role'] ?></span>
                            <?php if($user['status'] == 'inactive'): ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">Full Name: <strong><?= $user['full_name'] ?? $user['username'] ?></strong></p>
                    <p class="small text-muted">Created: <?= date('M Y', strtotime($user['created_at'])) ?></p>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex gap-2 p-3">
                    <button class="btn btn-light btn-sm flex-grow-1 border reset-pw" data-userid="<?= $user['id'] ?>" data-username="<?= $user['username'] ?>">Reset PW</button>
                    <button class="btn btn-light btn-sm flex-grow-1 border text-danger deactivate-user" 
                            data-userid="<?= $user['id'] ?>" 
                            data-username="<?= $user['username'] ?>"
                            data-status="<?= $user['status'] ?>">
                        <?= $user['status'] == 'active' ? 'Deactivate' : 'Activate' ?>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Create System Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/users/create" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Optional">
                    </div>
<div class="mb-3">
    <label class="form-label small fw-bold">Role</label>
    <select name="role" class="form-select">
        <option value="distributor">Relief Distributor</option>
        <option value="barangay">Barangay Official</option>
        <option value="admin">System Administrator</option>
    </select>
</div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Reset Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Password Reset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>New password for <strong id="resetUsername"></strong>:</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="newPassword" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyPassword()">
                        <i class="fa-regular fa-copy"></i> Copy
                    </button>
                </div>
                <p class="text-muted small">Please save this password and provide it to the user.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Reset Password
document.querySelectorAll('.reset-pw').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.userid;
        const username = this.dataset.username;
        
        if(confirm(`Reset password for ${username}?`)) {
            fetch(`/users/reset-password/${userId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('resetUsername').textContent = username;
                    document.getElementById('newPassword').value = data.new_password;
                    new bootstrap.Modal(document.getElementById('passwordModal')).show();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    });
});

// Handle Deactivate/Activate
document.querySelectorAll('.deactivate-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.userid;
        const username = this.dataset.username;
        const currentStatus = this.dataset.status;
        const action = currentStatus === 'active' ? 'deactivate' : 'activate';
        
        if(confirm(`Are you sure you want to ${action} ${username}?`)) {
            fetch(`/users/deactivate/${userId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload(); // Reload to show updated status
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    });
});

function copyPassword() {
    const passwordInput = document.getElementById('newPassword');
    passwordInput.select();
    document.execCommand('copy');
    alert('Password copied to clipboard!');
}
</script>

<?= $this->endSection() ?>