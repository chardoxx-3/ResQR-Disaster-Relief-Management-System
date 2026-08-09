<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
.import-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.import-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 20px;
}

.import-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, #f8fafc, #ffffff);
}

.import-header h4 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-1);
}

.import-header p {
    margin: 5px 0 0;
    font-size: 0.75rem;
    color: var(--text-3);
}

.import-body {
    padding: 24px;
}

.upload-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    padding: 40px;
    text-align: center;
    background: var(--surface2);
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 20px;
}

.upload-area:hover {
    border-color: var(--green-mid);
    background: #e8f5ee;
}

.upload-area i {
    font-size: 48px;
    color: var(--green-mid);
    margin-bottom: 15px;
}

.upload-area .upload-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-1);
    margin-bottom: 8px;
}

.upload-area .upload-hint {
    font-size: 0.7rem;
    color: var(--text-3);
}

.file-info {
    background: var(--bg);
    border-radius: var(--radius-sm);
    padding: 15px;
    margin-top: 15px;
    display: none;
}

.file-info.show {
    display: block;
}

.file-name {
    font-weight: 600;
    color: var(--green-deep);
    margin-bottom: 5px;
}

.file-size {
    font-size: 0.7rem;
    color: var(--text-3);
}

.import-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.import-instructions {
    background: var(--bg);
    border-radius: var(--radius-sm);
    padding: 20px;
    margin-top: 20px;
}

.import-instructions h6 {
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--text-1);
}

.import-instructions ul {
    margin: 0;
    padding-left: 20px;
}

.import-instructions li {
    font-size: 0.7rem;
    color: var(--text-2);
    margin-bottom: 8px;
}

.import-instructions code {
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.65rem;
    font-family: monospace;
}

.error-list {
    background: #fef2e8;
    border-left: 3px solid var(--orange-deep);
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    margin-bottom: 20px;
    max-height: 200px;
    overflow-y: auto;
}

.error-list h6 {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--orange-deep);
    margin-bottom: 8px;
}

.error-list ul {
    margin: 0;
    padding-left: 20px;
}

.error-list li {
    font-size: 0.7rem;
    color: var(--text-2);
    margin-bottom: 4px;
}
</style>

<div class="import-container">
    <div class="import-card">
        <div class="import-header">
            <h4>
                <i class="fa-solid fa-file-import me-2" style="color: var(--green-mid)"></i>
                Import Residents
            </h4>
            <p>Upload Excel or CSV file to import resident data</p>
        </div>
        
        <div class="import-body">
            <?php if(session()->getFlashdata('import_errors')): ?>
            <div class="error-list">
                <h6>
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    Import Completed with Errors
                </h6>
                <ul>
                    <?php foreach(session()->getFlashdata('import_errors') as $error): ?>
                    <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mb-3">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-3">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>
            
            <form action="<?= site_url('beneficiaries/upload') ?>" method="post" enctype="multipart/form-data" id="importForm">
                <?= csrf_field() ?>
                
                <div class="upload-area" id="uploadArea">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <div class="upload-text">Click or drag file to upload</div>
                    <div class="upload-hint">Supported formats: .xlsx, .xls, .csv (Max 10MB)</div>
                    <input type="file" name="import_file" id="importFile" accept=".xlsx,.xls,.csv" style="display: none;">
                </div>
                
                <div class="file-info" id="fileInfo">
                    <div class="file-name" id="fileName"></div>
                    <div class="file-size" id="fileSize"></div>
                </div>
                
                <div class="import-actions">
                    <a href="/beneficiaries/resident-list" class="btn-outline-c">
                        <i class="fa-solid fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-primary-c" id="uploadBtn" disabled>
                        <i class="fa-solid fa-upload"></i> Upload & Import
                    </button>
                </div>
            </form>
            
            <div class="import-instructions">
                <h6><i class="fa-solid fa-circle-info me-1"></i> Instructions</h6>
                <ul>
                    <li>File must follow the <strong>FACED Export format</strong> (same as export feature)</li>
                    <li>Required fields: <code>Last Name</code> and <code>First Name</code></li>
                    <li>Household numbers will be auto-generated if not provided</li>
                    <li>Existing household numbers will update the resident data</li>
                    <li>For best results, export first and use that as your template</li>
                    <li>Maximum file size: <strong>10MB</strong></li>
                </ul>
                <div class="mt-3">
                    <a href="/beneficiaries/export-excel" class="btn-outline-c" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-download"></i> Download Sample Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('uploadArea').addEventListener('click', function() {
    document.getElementById('importFile').click();
});

document.getElementById('importFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileInfo = document.getElementById('fileInfo');
    const uploadBtn = document.getElementById('uploadBtn');
    
    if (file) {
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size exceeds 10MB limit.');
            this.value = '';
            fileInfo.classList.remove('show');
            uploadBtn.disabled = true;
            return;
        }
        
        // Validate file extension
        const allowedExtensions = ['xlsx', 'xls', 'csv'];
        const extension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(extension)) {
            alert('Invalid file type. Please upload .xlsx, .xls, or .csv file.');
            this.value = '';
            fileInfo.classList.remove('show');
            uploadBtn.disabled = true;
            return;
        }
        
        document.getElementById('fileName').innerHTML = '<i class="fa-regular fa-file-excel me-1"></i> ' + file.name;
        document.getElementById('fileSize').innerHTML = formatFileSize(file.size);
        fileInfo.classList.add('show');
        uploadBtn.disabled = false;
    } else {
        fileInfo.classList.remove('show');
        uploadBtn.disabled = true;
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop functionality
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('importFile');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--green-mid)';
    this.style.background = '#e8f5ee';
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border)';
    this.style.background = 'var(--surface2)';
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border)';
    this.style.background = 'var(--surface2)';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
});
</script>

<?= $this->endSection() ?>