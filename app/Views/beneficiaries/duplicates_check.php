<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
.duplicate-warning {
    background: linear-gradient(135deg, #fff3e0, #ffe8cc);
    border-left: 4px solid #dc3545;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}
.duplicate-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #f0b3b3;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.08);
}
.duplicate-header {
    background: #dc3545;
    color: white;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.duplicate-header h5 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
}
.duplicate-badge {
    background: rgba(255,255,255,0.2);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}
.duplicate-table {
    width: 100%;
    border-collapse: collapse;
}
.duplicate-table th {
    background: #f8f9fa;
    padding: 10px 12px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #6c757d;
    border-bottom: 1px solid #dee2e6;
}
.duplicate-table td {
    padding: 10px 12px;
    font-size: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}
.duplicate-table tr:hover {
    background: #fff8f0;
}
.keep-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.keep-btn:hover {
    background: #1e7e34;
    transform: translateY(-1px);
}
.delete-dup-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    margin-left: 5px;
}
.delete-dup-btn:hover {
    background: #c82333;
}
.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}
.no-duplicates {
    text-align: center;
    padding: 50px 20px;
    background: #e8f5e9;
    border-radius: 12px;
    color: #2e7d32;
}
.no-duplicates i {
    font-size: 3rem;
    margin-bottom: 15px;
}
</style>

<div class="page-wrap">
    <div class="page-header">
        <div class="page-title">
            <h5><i class="fa-solid fa-circle-exclamation me-2" style="color:#dc3545"></i>Duplicate Records Check</h5>
            <p>Find and resolve duplicate resident records</p>
        </div>
        <div class="page-actions">
            <a href="/beneficiaries/resident-list" class="btn-outline-c">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <?php if(!$hasDuplicates): ?>
        <div class="no-duplicates">
            <i class="fa-solid fa-check-circle" style="color:#2e7d32"></i>
            <h4>No Duplicate Records Found</h4>
            <p>All residents have unique first and last name combinations.</p>
            <a href="/beneficiaries/resident-list" class="btn-primary-c" style="margin-top: 15px; display: inline-block;">
                Return to Resident List
            </a>
        </div>
    <?php else: ?>
        <div class="duplicate-warning">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.2rem; margin-right: 10px;"></i>
            <strong>Warning:</strong> Found <?= $totalDuplicateGroups ?> duplicate name group(s) affecting <?= $totalDuplicateRecords ?> records.
            Please review and resolve duplicates below.
        </div>

        <?php foreach($duplicates as $index => $dup): ?>
        <div class="duplicate-card" id="dup-group-<?= $index ?>">
            <div class="duplicate-header">
                <h5>
                    <i class="fa-solid fa-users"></i> 
                    <?= htmlspecialchars($dup['first_name']) ?> <?= htmlspecialchars($dup['last_name']) ?>
                </h5>
                <span class="duplicate-badge"><?= $dup['count'] ?> duplicate records</span>
            </div>
            <table class="duplicate-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Household #</th>
                        <th>Full Name</th>
                        <th>Barangay</th>
                        <th>Contact</th>
                        <th>Registration Date</th>
                        <th style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dup['records'] as $record): ?>
                    <tr id="record-<?= $record['id'] ?>">
                        <td><?= $record['id'] ?></td>
                        <td><span class="hh-badge"><?= $record['household_no'] ?></span></td>
                        <td>
                            <strong><?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?></strong>
                            <?php if(!empty($record['middle_name'])): ?>
                                <br><small><?= htmlspecialchars($record['middle_name']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($record['barangay'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($record['contact_number'] ?? '—') ?></td>
                        <td><?= date('M d, Y', strtotime($record['registration_date'])) ?></td>
                        <td class="action-buttons">
                            <a href="/beneficiaries/view/<?= $record['id'] ?>" class="act-btn view" target="_blank" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button onclick="keepRecord(<?= $record['id'] ?>, <?= $dup['records'][0]['id'] ?>)" class="keep-btn" title="Keep this, delete others">
                                <i class="fa-solid fa-check"></i> Keep
                            </button>
                            <button onclick="deleteSingleRecord(<?= $record['id'] ?>, '<?= htmlspecialchars($dup['first_name'] . ' ' . $dup['last_name']) ?>')" class="delete-dup-btn" title="Delete this record">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endforeach; ?>

        <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="deleteAllDuplicates()" class="btn-primary-c" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                <i class="fa-solid fa-trash-can"></i> Delete All Duplicates
            </button>
            <a href="/beneficiaries/resident-list" class="btn-outline-c">
                <i class="fa-solid fa-check"></i> Done, Back to List
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function keepRecord(keepId, referenceId) {
    if(!confirm('Keep this record and delete all other duplicates? This action cannot be undone.')) return;
    
    fetch('/beneficiaries/keep-record', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ keep_id: keepId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to process'));
        }
    })
    .catch(error => {
        alert('An error occurred: ' + error);
    });
}

function deleteSingleRecord(recordId, name) {
    if(!confirm(`Delete record #${recordId} (${name})? This action cannot be undone.`)) return;
    
    fetch('/beneficiaries/delete-record', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: recordId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const row = document.getElementById(`record-${recordId}`);
            if(row) row.remove();
            
            // Check if group has no more rows
            const group = row?.closest('.duplicate-card');
            if(group && group.querySelectorAll('tbody tr').length === 0) {
                group.remove();
            }
            
            if(document.querySelectorAll('.duplicate-card').length === 0) {
                location.reload();
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to delete'));
        }
    })
    .catch(error => {
        alert('An error occurred: ' + error);
    });
}

function deleteAllDuplicates() {
    if(!confirm('Delete ALL duplicate records? This action cannot be undone. Only one record per name group will be kept.')) return;
    
    fetch('/beneficiaries/delete-all-duplicates', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Success: ' + data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete duplicates'));
        }
    })
    .catch(error => {
        alert('An error occurred: ' + error);
    });
}
</script>

<?= $this->endSection() ?>