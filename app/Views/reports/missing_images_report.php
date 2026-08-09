<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
:root {
    --red-deep: #b91c1c;
    --red-light: #fee2e2;
    --orange-warning: #ea580c;
    --amber-bg: #fffbeb;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px 16px 0 0;
    padding: 18px 24px;
}

.filter-bar {
    background: #fafbfc;
    border: 1px solid #e5e7eb;
    border-top: none;
    padding: 14px 24px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.filter-select, .filter-input {
    padding: 7px 30px 7px 11px;
    border: 1.5px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.74rem;
    font-family: inherit;
    background: white;
    min-width: 180px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid transparent;
    text-decoration: none;
}

.btn-primary {
    background: #3a6b1a;
    color: white;
}

.btn-outline {
    background: white;
    color: #374151;
    border-color: #e5e7eb;
}

.report-body {
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 16px 16px;
    padding: 20px;
    background: #f6f7f9;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px 20px;
}

.stat-card.warning {
    border-left: 4px solid #ea580c;
}

.stat-card.danger {
    border-left: 4px solid #dc2626;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #dc2626;
}

.stat-label {
    font-size: 0.7rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.table-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.table-scroll {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    padding: 10px 14px;
    text-align: left;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    background: #fafbfc;
    border-bottom: 1px solid #e5e7eb;
}

.data-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.74rem;
}

.badge-warning {
    background: #fffbeb;
    color: #d4920a;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.62rem;
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 48px;
    color: #9ca3af;
}

.section-title {
    font-size: 1rem;
    font-weight: 700;
    margin: 20px 0 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #3a6b1a;
}
</style>

<div class="report-wrap">
    <div class="page-header">
        <div>
            <h5 style="margin:0"><i class="fa-solid fa-image-slash"></i> Missing Images Report</h5>
            <p style="margin:4px 0 0; font-size:0.7rem; color:#6b7280">List of residents and family members without photos</p>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="/reports" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back</a>
            <button onclick="window.print()" class="btn btn-outline"><i class="fa-solid fa-print"></i> Print</button>
            <a href="/reports/missing-images/export?barangay=<?= urlencode($selectedBarangay ?? '') ?>&street=<?= urlencode($selectedStreet ?? '') ?>&type=<?= urlencode($selectedType ?? '') ?>" class="btn btn-primary"><i class="fa-solid fa-download"></i> Export CSV</a>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">Barangay</span>
            <select id="barangayFilter" class="filter-select">
                <option value="">All Barangays</option>
                <?php foreach($barangays as $b): ?>
                    <option value="<?= htmlspecialchars($b) ?>" <?= ($selectedBarangay ?? '') == $b ? 'selected' : '' ?>><?= htmlspecialchars($b) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Street</span>
            <select id="streetFilter" class="filter-select" <?= empty($streets) ? 'disabled' : '' ?>>
                <option value="">All Streets</option>
                <?php foreach($streets as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= ($selectedStreet ?? '') == $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Show</span>
            <select id="typeFilter" class="filter-select">
                <option value="all" <?= ($selectedType ?? 'all') == 'all' ? 'selected' : '' ?>>All (Heads & Members)</option>
                <option value="head" <?= ($selectedType ?? '') == 'head' ? 'selected' : '' ?>>Heads Only</option>
                <option value="member" <?= ($selectedType ?? '') == 'member' ? 'selected' : '' ?>>Members Only</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">&nbsp;</span>
            <div style="display:flex; gap:6px;">
                <button class="btn btn-primary" onclick="applyFilters()"><i class="fa-solid fa-magnifying-glass"></i> Generate</button>
                <button class="btn btn-outline" onclick="resetFilters()"><i class="fa-solid fa-rotate"></i> Reset</button>
            </div>
        </div>
    </div>

    <div class="report-body">
        <?php if($totalMissing > 0): ?>
        <div class="stats-grid">
            <div class="stat-card danger">
                <div class="stat-label"><i class="fa-solid fa-user-large"></i> Heads Without Photo</div>
                <div class="stat-value"><?= number_format($totalHeadsMissing) ?></div>
            </div>
            <div class="stat-card warning">
                <div class="stat-label"><i class="fa-solid fa-users"></i> Members Without Photo</div>
                <div class="stat-value"><?= number_format($totalMembersMissing) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-chart-simple"></i> Total Missing</div>
                <div class="stat-value" style="color:#3a6b1a"><?= number_format($totalMissing) ?></div>
            </div>
        </div>

        <?php if(!empty($missingHeadPhotos)): ?>
        <div class="section-title">
            <i class="fa-solid fa-user-large"></i> Family Heads Without Photos (<?= count($missingHeadPhotos) ?>)
        </div>
        <div class="table-card">
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Household #</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Contact Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($missingHeadPhotos as $head): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($head['household_no']) ?></code></td>
                            <td><?= htmlspecialchars($head['last_name']) ?></td>
                            <td><?= htmlspecialchars($head['first_name']) ?></td>
                            <td><?= htmlspecialchars($head['middle_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($head['contact_number'] ?? '—') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($missingMemberPhotos)): ?>
        <div class="section-title">
            <i class="fa-solid fa-users"></i> Family Members Without Photos (<?= count($missingMemberPhotos) ?>)
        </div>
        <div class="table-card">
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Household #</th>
                            <th>Head Name</th>
                            <th>Member Name</th>
                            <th>Relation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($missingMemberPhotos as $member): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($member['household_no']) ?></code></td>
                            <td><?= htmlspecialchars($member['last_name']) ?>, <?= htmlspecialchars($member['first_name']) ?></td>
                            <td><strong><?= htmlspecialchars($member['member_name']) ?></strong></td>
                            <td><span class="badge-warning"><?= htmlspecialchars($member['relation'] ?? '—') ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-check-circle" style="font-size:48px; color:#3a6b1a; margin-bottom:16px; display:block;"></i>
            <strong>Great news!</strong>
            <p>No missing images found for the selected filters.</p>
            <p style="margin-top:8px;">All residents and family members have photos uploaded.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const streetsByBarangay = <?= json_encode($streetsByBarangay ?? []) ?>;

document.getElementById('barangayFilter')?.addEventListener('change', function() {
    const barangay = this.value;
    const streetSel = document.getElementById('streetFilter');
    
    if (barangay && streetsByBarangay[barangay]?.length > 0) {
        streetSel.innerHTML = '<option value="">All Streets</option>';
        streetsByBarangay[barangay].forEach(street => {
            const opt = document.createElement('option');
            opt.value = street;
            opt.textContent = street;
            streetSel.appendChild(opt);
        });
        streetSel.disabled = false;
    } else {
        streetSel.innerHTML = '<option value="">All Streets</option>';
        streetSel.disabled = true;
    }
});

function applyFilters() {
    const barangay = document.getElementById('barangayFilter').value;
    const street = document.getElementById('streetFilter').value;
    const type = document.getElementById('typeFilter').value;
    let url = '/reports/missing-images?';
    const params = [];
    if (barangay) params.push('barangay=' + encodeURIComponent(barangay));
    if (street) params.push('street=' + encodeURIComponent(street));
    if (type && type !== 'all') params.push('type=' + encodeURIComponent(type));
    window.location.href = url + params.join('&');
}

function resetFilters() {
    window.location.href = '/reports/missing-images';
}
</script>

<?= $this->endSection() ?>