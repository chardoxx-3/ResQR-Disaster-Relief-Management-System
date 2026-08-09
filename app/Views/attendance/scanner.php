<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --brand-green: #3d8e33;
        --brand-orange: #f47b33;
        --brand-red: #e53935;
    }

    #qr-reader {
        border: none !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    #qr-reader video {
        border-radius: 0.75rem;
    }

    #qr-reader__scan_region {
        background: white;
    }

    #qr-reader__dashboard_section {
        padding: 1rem;
    }

    #qr-reader__dashboard_section_csr span {
        background-color: var(--brand-green) !important;
        border-color: var(--brand-green) !important;
    }

    .result-card {
        background: white;
        border-radius: 0.75rem;
        border-left: 4px solid var(--brand-orange);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-brand-green mb-1">QR Scanner</h4>
        <p class="text-muted small mb-0">Scan QR codes for attendance check-in</p>
    </div>
    <a href="/attendance" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Attendance
    </a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-camera me-2 text-brand-green"></i>Scanner</h6>
            </div>
            <div class="card-body p-3">
                <div id="qr-reader" style="width: 100%"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-circle-info me-2 text-brand-green"></i>Scan Result</h6>
            </div>
            <div class="card-body">
                <div id="scanResult" class="text-center text-muted py-4">
                    <i class="fa-solid fa-qrcode fa-3x mb-3 opacity-25"></i>
                    <p>Waiting for QR code...</p>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock me-2 text-brand-green"></i>Recent Check-ins</h6>
            </div>
            <div class="list-group list-group-flush" id="recentCheckins">
                <div class="list-group-item text-center text-muted py-3">
                    <small>No recent check-ins</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
const html5QrCode = new Html5Qrcode("qr-reader");
const qrCodeSuccessCallback = (decodedText, decodedResult) => {
    // Stop scanning temporarily
    html5QrCode.pause();
    
    // Process the QR code
    fetch('/attendance/qr-checkin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'qr_token=' + encodeURIComponent(decodedText)
    })
    .then(response => response.json())
    .then(data => {
        let html = '';
        
        if (data.status === 'success') {
            html = `
                <div class="result-card p-3">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-2" style="width: 64px; height: 64px; background-color: #e8f5e9;">
                            <i class="fa-solid fa-check-circle fa-2x" style="color: var(--brand-green);"></i>
                        </div>
                        <h6 class="fw-bold text-success mb-1">Check-in Successful!</h6>
                        <p class="small text-muted mb-0">${data.message}</p>
                    </div>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Time:</span>
                            <span class="fw-bold">${new Date().toLocaleTimeString()}</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Add to recent check-ins
            addToRecent(data.message);
            
            // Beep sound (optional)
            new Audio('data:audio/wav;base64,UklGRlwAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YVoAAACAgICAf39/f39/f3+AgICAf39/f39/f3+AgICAf39/f39/f3+AgICAf39/f39/f3+AgICAf39/f39/f3+AgICAf39/f39/f3+AgICAf39/f38=').play();
            
        } else if (data.status === 'already_checked_in') {
            html = `
                <div class="result-card p-3" style="border-left-color: #f47b33;">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-2" style="width: 64px; height: 64px; background-color: #fff3e0;">
                            <i class="fa-solid fa-clock fa-2x" style="color: #f47b33;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #f47b33;">Already Checked In</h6>
                        <p class="small text-muted mb-0">Checked in at ${data.data.check_in_time}</p>
                    </div>
                </div>
            `;
        } else {
            html = `
                <div class="result-card p-3" style="border-left-color: #e53935;">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-2" style="width: 64px; height: 64px; background-color: #ffebee;">
                            <i class="fa-solid fa-exclamation-circle fa-2x" style="color: #e53935;"></i>
                        </div>
                        <h6 class="fw-bold text-danger mb-1">Error</h6>
                        <p class="small text-muted mb-0">${data.message}</p>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('scanResult').innerHTML = html;
        
        // Resume scanning after 2 seconds
        setTimeout(() => {
            html5QrCode.resume();
            document.getElementById('scanResult').innerHTML = `
                <i class="fa-solid fa-qrcode fa-3x mb-3 opacity-25"></i>
                <p>Waiting for QR code...</p>
            `;
        }, 3000);
    });
};

const config = { fps: 10, qrbox: { width: 250, height: 250 } };

html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);

function addToRecent(message) {
    const recentDiv = document.getElementById('recentCheckins');
    const time = new Date().toLocaleTimeString();
    
    const item = document.createElement('div');
    item.className = 'list-group-item d-flex align-items-center';
    item.innerHTML = `
        <div class="avatar-circle me-2" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-user-check fa-xs"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold small">${message}</div>
            <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i>${time}</div>
        </div>
    `;
    
    recentDiv.insertBefore(item, recentDiv.firstChild);
    
    // Keep only last 5
    if (recentDiv.children.length > 5) {
        recentDiv.removeChild(recentDiv.lastChild);
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop();
    }
});
</script>

<?= $this->endSection() ?>