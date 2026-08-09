<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fa-solid fa-qrcode me-2"></i> Scanner Settings</h2>
        <a href="/distribution/scanner" class="btn btn-outline-primary">
            <i class="fa-solid fa-camera me-2"></i> Go to Scanner
        </a>
    </div>

    <div class="row">
        <!-- Camera Scanner Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-camera me-2 text-primary"></i>
                        Phone Camera Scanner
                    </h5>
                </div>
                <div class="card-body">
                    <form id="cameraSettingsForm">
                        <div class="mb-3">
                            <label class="form-label">Default Camera</label>
                            <select class="form-select" name="camera_default" id="cameraDefault">
                                <option value="environment" <?= (session()->get('camera_default') ?? 'environment') == 'environment' ? 'selected' : '' ?>>Rear Camera</option>
                                <option value="user" <?= (session()->get('camera_default') == 'user' ? 'selected' : '') ?>>Front Camera</option>
                            </select>
                            <div class="form-text">Choose which camera to use by default</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Scan Mode</label>
                            <select class="form-select" name="camera_scan_mode" id="cameraScanMode">
                                <option value="continuous" <?= (session()->get('camera_scan_mode') ?? 'continuous') == 'continuous' ? 'selected' : '' ?>>Continuous Scan</option>
                                <option value="single" <?= (session()->get('camera_scan_mode') == 'single' ? 'selected' : '') ?>>Single Scan</option>
                            </select>
                            <div class="form-text">Continuous automatically scans, Single requires manual capture</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Scan Sound</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="camera_sound" id="cameraSound" <?= (session()->get('camera_sound') ?? 'on') == 'on' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cameraSound">Enable beep sound on successful scan</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Flashlight</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="camera_flashlight" id="cameraFlashlight" <?= (session()->get('camera_flashlight') ?? 'off') == 'on' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cameraFlashlight">Enable flashlight by default</label>
                            </div>
                        </div>
                    </form>

                    <!-- Camera Test Section -->
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-3">Test Camera Scanner</h6>
                        <div class="border rounded p-3 bg-light">
                            <div id="cameraTestContainer" class="mb-3" style="min-height: 200px; background: #dee2e6; display: flex; align-items: center; justify-content: center;">
                                <button class="btn btn-primary" onclick="startCameraTest()">
                                    <i class="fa-solid fa-play me-2"></i> Start Camera Test
                                </button>
                            </div>
                            <div id="cameraTestResult" class="alert alert-info d-none">
                                <strong>Scan Result:</strong> <span id="cameraScanData"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hardware Scanner Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-microchip me-2 text-success"></i>
                        Hardware Scanner
                    </h5>
                </div>
                <div class="card-body">
                    <form id="hardwareSettingsForm">
                        <div class="mb-3">
                            <label class="form-label">Scanner Type</label>
                            <select class="form-select" name="hardware_type" id="hardwareType">
                                <option value="usb" <?= (session()->get('hardware_type') ?? 'usb') == 'usb' ? 'selected' : '' ?>>USB Scanner (Keyboard Emulation)</option>
                                <option value="bluetooth" <?= (session()->get('hardware_type') == 'bluetooth' ? 'selected' : '') ?>>Bluetooth Scanner</option>
                                <option value="serial" <?= (session()->get('hardware_type') == 'serial' ? 'selected' : '') ?>>Serial/COM Port</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Connection Settings</label>
                            <div id="usbSettings" class="border rounded p-3 mb-2">
                                <p class="mb-1"><i class="fa-solid fa-info-circle me-2"></i> USB scanners typically work as keyboard emulation</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="usb_enter_key" id="usbEnterKey" <?= (session()->get('usb_enter_key') ?? 'on') == 'on' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="usbEnterKey">Add Enter key after scan</label>
                                </div>
                            </div>
                            
                            <div id="bluetoothSettings" class="border rounded p-3 mb-2 d-none">
                                <div class="mb-2">
                                    <label class="form-label">Device Name/Address</label>
                                    <input type="text" class="form-control" name="bluetooth_device" value="<?= session()->get('bluetooth_device') ?? '' ?>" placeholder="e.g., Scanner-1234">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="scanBluetoothDevices()">
                                    <i class="fa-solid fa-bluetooth me-2"></i> Scan for Devices
                                </button>
                            </div>
                            
                            <div id="serialSettings" class="border rounded p-3 d-none">
                                <div class="mb-2">
                                    <label class="form-label">COM Port</label>
                                    <input type="text" class="form-control" name="com_port" value="<?= session()->get('com_port') ?? 'COM1' ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Baud Rate</label>
                                    <select class="form-select" name="baud_rate">
                                        <option value="9600" <?= session()->get('baud_rate') == '9600' ? 'selected' : '' ?>>9600</option>
                                        <option value="19200" <?= session()->get('baud_rate') == '19200' ? 'selected' : '' ?>>19200</option>
                                        <option value="38400" <?= session()->get('baud_rate') == '38400' ? 'selected' : '' ?>>38400</option>
                                        <option value="115200" <?= session()->get('baud_rate') == '115200' ? 'selected' : '' ?>>115200</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Scan Timeout (seconds)</label>
                            <input type="number" class="form-control" name="scan_timeout" value="<?= session()->get('scan_timeout') ?? '5' ?>" min="1" max="30">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prefix/Suffix</label>
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" name="scan_prefix" value="<?= session()->get('scan_prefix') ?? '' ?>" placeholder="Prefix">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="scan_suffix" value="<?= session()->get('scan_suffix') ?? '' ?>" placeholder="Suffix">
                                </div>
                            </div>
                            <div class="form-text">Some scanners add prefixes/suffixes to scans</div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" onclick="saveHardwareSettings()">
                                <i class="fa-solid fa-save me-2"></i> Save Hardware Settings
                            </button>
                        </div>
                    </form>

                    <!-- Hardware Test Section -->
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-3">Test Hardware Scanner</h6>
                        <div class="border rounded p-3 bg-light">
                            <p class="text-muted mb-3">Scan a QR code with your hardware scanner</p>
                            <div class="d-flex gap-2">
<input type="text" class="form-control" id="hardwareScanInput" 
       placeholder="Scanner input will appear here..." 
       readonly
       <?= (session()->get('hardware_type') ?? 'usb') == 'usb' ? 'autofocus' : '' ?>>
                                <button class="btn btn-success" onclick="startHardwareTest()">
                                    <i class="fa-solid fa-play me-2"></i> Start Test
                                </button>
                            </div>
                            <div id="hardwareTestStatus" class="mt-2 small text-muted">
                                Status: Ready
                            </div>
                            <div id="hardwareTestResult" class="alert alert-success mt-3 d-none">
                                <strong>Scan Result:</strong> <span id="hardwareScanData"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save All Settings -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-success" onclick="saveAllSettings()">
                            <i class="fa-solid fa-check me-2"></i> Save All Settings
                        </button>
                        <button class="btn btn-outline-secondary ms-2" onclick="resetToDefault()">
                            <i class="fa-solid fa-undo me-2"></i> Reset to Default
                        </button>
                    </div>
                    <div class="text-muted">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Settings are saved to your session
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<!-- QR Scanning Library - Use same version as index.php -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let cameraTestScanner = null;

// Show/hide hardware settings based on type
document.getElementById('hardwareType').addEventListener('change', function() {
    const type = this.value;
    document.getElementById('usbSettings').classList.toggle('d-none', type !== 'usb');
    document.getElementById('bluetoothSettings').classList.toggle('d-none', type !== 'bluetooth');
    document.getElementById('serialSettings').classList.toggle('d-none', type !== 'serial');
});

// Camera Test Functions
// Replace the existing startCameraTest function
function startCameraTest() {
    const container = document.getElementById('cameraTestContainer');
    container.innerHTML = '<div id="test-reader" style="width: 100%; height: 300px;"></div>';
    
    const cameraDefault = document.getElementById('cameraDefault').value;
    
    // Clear any existing scanner
    if (cameraTestScanner) {
        cameraTestScanner.clear();
        cameraTestScanner = null;
    }
    
    // Small delay to ensure DOM is updated
    setTimeout(() => {
        initCameraTest(cameraDefault);
    }, 100);
}

// In initCameraTest function, update the scanner config:
function initCameraTest(cameraId) {
    try {
        cameraTestScanner = new Html5Qrcode("test-reader");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            rememberLastUsedCamera: true
        };
        
        // Map camera selection to facing mode
        const facingMode = cameraId === 'environment' ? 'environment' : 'user';
        
        cameraTestScanner.start(
            { facingMode: facingMode },
            config,
            onCameraScanSuccess,  // This will now validate with system
            onCameraScanError
        ).then(() => {
            document.getElementById('cameraTestStatus')?.remove();
            const status = document.createElement('div');
            status.id = 'cameraTestStatus';
            status.className = 'alert alert-success mt-2';
            status.innerHTML = '<i class="fa-solid fa-check me-2"></i> Camera active - scan a registered QR code';
            document.getElementById('cameraTestContainer').appendChild(status);
        }).catch(err => {
            console.error('Camera start error:', err);
            document.getElementById('cameraTestContainer').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Failed to start camera: ${err.message || 'Unknown error'}
                    <button class="btn btn-sm btn-primary mt-2" onclick="startCameraTest()">
                        <i class="fa-solid fa-rotate-right me-2"></i> Retry
                    </button>
                </div>
            `;
        });
    } catch (err) {
        console.error('Init error:', err);
        document.getElementById('cameraTestContainer').innerHTML = `
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                Error initializing camera: ${err.message}
                <button class="btn btn-sm btn-primary mt-2" onclick="startCameraTest()">
                    <i class="fa-solid fa-rotate-right me-2"></i> Retry
                </button>
            </div>
        `;
    }
}

// Replace the existing onCameraScanSuccess function with this:
function onCameraScanSuccess(decodedText, decodedResult) {
    // Immediately stop scanning to prevent multiple triggers
    if (cameraTestScanner) {
        cameraTestScanner.pause();
    }
    
    // Show scanning status
    const container = document.getElementById('cameraTestContainer');
    const statusDiv = document.createElement('div');
    statusDiv.className = 'alert alert-info position-absolute top-0 start-0 end-0 m-2';
    statusDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Validating QR code with system...';
    container.style.position = 'relative';
    container.appendChild(statusDiv);
    
    // Validate the QR code against the system using the correct endpoint
    fetch(`/residentportal/processScan?token=${encodeURIComponent(decodedText)}`)
        .then(response => response.json())
        .then(data => {
            statusDiv.remove();
            
            if (data.error) {
                // Not found in system at all
                showScanResult('error', '❌ Invalid QR Code', 'This QR code is not registered in the system');
                
                // Play error sound if enabled
                if (document.getElementById('cameraSound').checked) {
                    playErrorSound();
                }
            } else {
                // QR found in system
                let message = '';
                let title = '';
                
                if (data.type === 'head') {
                    title = 'Head of Family';
                    message = `${data.data.name} (Household #${data.data.household_no || 'N/A'})`;
                } else if (data.type === 'member') {
                    title = 'Family Member';
                    message = `${data.data.name} - ${data.data.relation || 'Member'}`;
                }
                
                showScanResult('success', '✅ Valid QR Code', `${title}<br>${message}`);
                
                // Play success sound if enabled
                if (document.getElementById('cameraSound').checked) {
                    playSuccessSound();
                }
            }
            
            document.getElementById('cameraScanData').textContent = decodedText;
            document.getElementById('cameraTestResult').classList.remove('d-none');
        })
        .catch(error => {
            statusDiv.remove();
            console.error('Validation error:', error);
            showScanResult('error', '❌ Validation Failed', 'Could not verify QR code with server');
        })
        .finally(() => {
            // Handle scan mode
            if (document.getElementById('cameraScanMode').value === 'single') {
                // Stop scanning for single mode
                if (cameraTestScanner) {
                    cameraTestScanner.stop().then(() => {
                        cameraTestScanner.clear();
                        cameraTestScanner = null;
                        document.getElementById('cameraTestContainer').innerHTML = `
                            <button class="btn btn-primary" onclick="startCameraTest()">
                                <i class="fa-solid fa-camera me-2"></i> Scan Again
                            </button>
                        `;
                    });
                }
            } else {
                // Resume scanning for continuous mode after 2 seconds
                setTimeout(() => {
                    if (cameraTestScanner) {
                        cameraTestScanner.resume();
                    }
                }, 2000);
            }
        });
}

// Helper function to show scan results
function showScanResult(type, title, message) {
    const container = document.getElementById('cameraTestContainer');
    const resultDiv = document.createElement('div');
    resultDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-absolute top-0 start-0 end-0 m-2`;
    resultDiv.innerHTML = `
        <strong>${title}</strong><br>
        <small>${message}</small>
    `;
    container.style.position = 'relative';
    container.appendChild(resultDiv);
    
    setTimeout(() => {
        resultDiv.remove();
    }, 3000);
}

// Add error sound function
function playErrorSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(300, audioContext.currentTime);
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    
    oscillator.start();
    oscillator.stop(audioContext.currentTime + 0.3);
}

// Add success sound function (different from regular beep)
function playSuccessSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    // Two quick beeps for success
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    
    oscillator.start();
    oscillator.stop(audioContext.currentTime + 0.1);
    
    // Second beep
    setTimeout(() => {
        const oscillator2 = audioContext.createOscillator();
        const gainNode2 = audioContext.createGain();
        oscillator2.connect(gainNode2);
        gainNode2.connect(audioContext.destination);
        oscillator2.frequency.setValueAtTime(1000, audioContext.currentTime);
        gainNode2.gain.setValueAtTime(0.1, audioContext.currentTime);
        oscillator2.start();
        oscillator2.stop(audioContext.currentTime + 0.1);
    }, 150);
}

// Replace the existing onCameraScanError function
function onCameraScanError(error) {
    // Only log actual errors, not normal scanning attempts
    if (error && !error.includes('No MultiFormat Readers')) {
        console.warn('Camera scan error:', error);
    }
}

window.addEventListener('beforeunload', function() {
    if (cameraTestScanner) {
        try {
            cameraTestScanner.stop();
            cameraTestScanner.clear();
        } catch (e) {
            // Ignore errors during cleanup
        }
    }
});

// Replace the existing hardware test initialization and handlers with these:

let hardwareBuffer = '';
let hardwareTimeout = null;
let hardwareTestActive = false;

function startHardwareTest() {
    hardwareTestActive = true;
    hardwareBuffer = ''; // Clear any existing buffer
    
    const input = document.getElementById('hardwareScanInput');
    input.value = '';
    input.readOnly = false;
    input.focus();
    input.style.backgroundColor = '#fff3cd'; // Highlight when active
    document.getElementById('hardwareTestStatus').innerHTML = 'Status: <span class="text-success">Listening for scanner input... Please scan a QR code</span>';
    document.getElementById('hardwareTestResult').classList.add('d-none');
    
    // Clear after 30 seconds if no input
    if (hardwareTimeout) clearTimeout(hardwareTimeout);
    hardwareTimeout = setTimeout(() => {
        if (hardwareTestActive) {
            stopHardwareTest('timed out');
        }
    }, 30000);
}

function stopHardwareTest(reason = 'completed') {
    hardwareTestActive = false;
    const input = document.getElementById('hardwareScanInput');
    input.readOnly = true;
    input.style.backgroundColor = '';
    
    if (reason === 'timed out') {
        document.getElementById('hardwareTestStatus').innerHTML = 'Status: <span class="text-danger">Test timed out - click Start Test again</span>';
    }
    
    if (hardwareTimeout) {
        clearTimeout(hardwareTimeout);
        hardwareTimeout = null;
    }
}

// Handle hardware scanner input - REPLACE the existing keypress handler
document.getElementById('hardwareScanInput').addEventListener('input', function(e) {
    if (!hardwareTestActive) return;
    
    // Most hardware scanners send characters rapidly followed by Enter
    // We'll accumulate in buffer until we see a pause or Enter
    hardwareBuffer = this.value;
});

// Use keydown instead of keypress for better Enter key handling
document.getElementById('hardwareScanInput').addEventListener('keydown', function(e) {
    if (!hardwareTestActive) return;
    
    // If Enter key is pressed (typical scanner behavior)
    if (e.key === 'Enter') {
        e.preventDefault();
        
        const scanData = hardwareBuffer.trim(); // Use the buffer, not this.value
        
        if (scanData) {
            // Process the scan
            processHardwareScan(scanData);
        }
    }
});

// Also handle case where scanner doesn't send Enter (use timeout)
let scanTimeout;
document.getElementById('hardwareScanInput').addEventListener('input', function(e) {
    if (!hardwareTestActive) return;
    
    // Clear previous timeout
    if (scanTimeout) clearTimeout(scanTimeout);
    
    // If scanner doesn't send Enter, process after short pause
    scanTimeout = setTimeout(() => {
        if (hardwareTestActive && this.value.trim()) {
            processHardwareScan(this.value.trim());
        }
    }, 500); // 500ms pause indicates end of scan
});

// New function to process hardware scan
function processHardwareScan(scanData) {
    // Stop the test
    stopHardwareTest('completed');
    
    // Clear any pending timeouts
    if (scanTimeout) clearTimeout(scanTimeout);
    
    // Show scanning status
    document.getElementById('hardwareTestStatus').innerHTML = 'Status: <span class="text-primary">Validating QR code with system...</span>';
    
    // Apply prefix/suffix stripping if configured
    let processedData = scanData;
    const prefix = document.querySelector('[name="scan_prefix"]').value;
    const suffix = document.querySelector('[name="scan_suffix"]').value;
    
    if (prefix && processedData.startsWith(prefix)) {
        processedData = processedData.substring(prefix.length);
    }
    if (suffix && processedData.endsWith(suffix)) {
        processedData = processedData.substring(0, processedData.length - suffix.length);
    }
    
    // Validate the QR code against the system
    fetch(`/residentportal/processScan?token=${encodeURIComponent(processedData)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Not found in system
                document.getElementById('hardwareTestStatus').innerHTML = 'Status: <span class="text-danger">Invalid QR code</span>';
                showHardwareResult('error', '❌ Invalid QR Code', 'This QR code is not registered in the system');
                
                // Play error sound if enabled
                if (document.getElementById('cameraSound').checked) {
                    playErrorSound();
                }
            } else {
                // QR found in system
                let message = '';
                let title = '';
                
                if (data.type === 'head') {
                    title = 'Head of Family';
                    message = `${data.data.name} (Household #${data.data.household_no || 'N/A'})`;
                } else if (data.type === 'member') {
                    title = 'Family Member';
                    message = `${data.data.name} - ${data.data.relation || 'Member'}`;
                }
                
                document.getElementById('hardwareTestStatus').innerHTML = 'Status: <span class="text-success">Valid QR code detected</span>';
                showHardwareResult('success', '✅ Valid QR Code', `${title}<br>${message}`);
                
                // Play success sound if enabled
                if (document.getElementById('cameraSound').checked) {
                    playSuccessSound();
                }
            }
            
            document.getElementById('hardwareScanData').textContent = scanData;
            document.getElementById('hardwareTestResult').classList.remove('d-none');
        })
        .catch(error => {
            console.error('Validation error:', error);
            document.getElementById('hardwareTestStatus').innerHTML = 'Status: <span class="text-danger">Validation failed</span>';
            showHardwareResult('error', '❌ Validation Failed', 'Could not verify QR code with server');
        });
}

// New function to show hardware scan results
function showHardwareResult(type, title, message) {
    const resultDiv = document.getElementById('hardwareTestResult');
    resultDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} mt-3`;
    resultDiv.innerHTML = `
        <strong>${title}</strong><br>
        <small>${message}</small>
        <br>
        <small class="text-muted mt-2 d-block">Raw data: <span id="hardwareScanData">${document.getElementById('hardwareScanData').textContent}</span></small>
    `;
    resultDiv.classList.remove('d-none');
}

// Add a stop test function
function stopHardwareTest() {
    hardwareTestActive = false;
    const input = document.getElementById('hardwareScanInput');
    input.readOnly = true;
    input.style.backgroundColor = '';
    input.value = '';
    
    if (hardwareTimeout) {
        clearTimeout(hardwareTimeout);
        hardwareTimeout = null;
    }
    if (scanTimeout) {
        clearTimeout(scanTimeout);
        scanTimeout = null;
    }
    
    document.getElementById('hardwareTestStatus').innerHTML = 'Status: Ready';
}

// Update the USB Enter Key setting to actually work
document.getElementById('usbEnterKey').addEventListener('change', function(e) {
    // This setting is now handled in the keydown event
    console.log('USB Enter Key setting:', this.checked ? 'enabled' : 'disabled');
});

// Make sure to clean up on page unload
window.addEventListener('beforeunload', function() {
    if (hardwareTimeout) clearTimeout(hardwareTimeout);
    if (scanTimeout) clearTimeout(scanTimeout);
});

// Handle hardware scanner input
document.getElementById('hardwareScanInput').addEventListener('keypress', function(e) {
    if (!hardwareTestActive) return;
    
    // If Enter key is pressed (typical scanner behavior)
    if (e.key === 'Enter') {
        e.preventDefault();
        const scanData = this.value.trim();
        
        if (scanData) {
            hardwareTestActive = false;
            this.readOnly = true;
            
            document.getElementById('hardwareTestResult').classList.remove('d-none');
            document.getElementById('hardwareScanData').textContent = scanData;
            document.getElementById('hardwareTestStatus').innerHTML = 'Status: Scan received';
        }
    }
});

// Bluetooth device scanning simulation
function scanBluetoothDevices() {
    alert('Scanning for Bluetooth devices...\n(This is a simulation - in production, this would use Web Bluetooth API)');
}

// Save Functions
function saveCameraSettings() {
    const settings = {
        camera_default: document.getElementById('cameraDefault').value,
        camera_scan_mode: document.getElementById('cameraScanMode').value,
        camera_sound: document.getElementById('cameraSound').checked ? 'on' : 'off',
        camera_flashlight: document.getElementById('cameraFlashlight').checked ? 'on' : 'off'
    };
    
    fetch('/scanner/save-camera-settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Camera settings saved successfully!');
        }
    });
}

function saveHardwareSettings() {
    const settings = {
        hardware_type: document.getElementById('hardwareType').value,
        usb_enter_key: document.getElementById('usbEnterKey').checked ? 'on' : 'off',
        bluetooth_device: document.querySelector('[name="bluetooth_device"]')?.value || '',
        com_port: document.querySelector('[name="com_port"]')?.value || 'COM1',
        baud_rate: document.querySelector('[name="baud_rate"]')?.value || '9600',
        scan_timeout: document.querySelector('[name="scan_timeout"]').value,
        scan_prefix: document.querySelector('[name="scan_prefix"]').value,
        scan_suffix: document.querySelector('[name="scan_suffix"]').value
    };
    
    fetch('/scanner/save-hardware-settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Hardware settings saved successfully!');
        }
    });
}

function saveAllSettings() {
    saveCameraSettings();
    saveHardwareSettings();
}

function resetToDefault() {
    if (confirm('Reset all scanner settings to default?')) {
        document.getElementById('cameraDefault').value = 'environment';
        document.getElementById('cameraScanMode').value = 'continuous';
        document.getElementById('cameraSound').checked = true;
        document.getElementById('cameraFlashlight').checked = false;
        document.getElementById('hardwareType').value = 'usb';
        document.getElementById('usbEnterKey').checked = true;
        document.querySelector('[name="scan_timeout"]').value = '5';
        document.querySelector('[name="scan_prefix"]').value = '';
        document.querySelector('[name="scan_suffix"]').value = '';
        
        saveAllSettings();
    }
}

function playBeepSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    
    oscillator.start();
    oscillator.stop(audioContext.currentTime + 0.1);
}

// Initialize based on current hardware type
document.getElementById('hardwareType').dispatchEvent(new Event('change'));
</script>

<style>
#reader {
    border-radius: 0.5rem;
    overflow: hidden;
}

#reader video {
    width: 100%;
    height: auto;
}

/* Flashlight simulation */
.flashlight-on video {
    filter: brightness(1.2);
}

/* Additional styles for camera test */
#test-reader {
    border-radius: 0.5rem;
    overflow: hidden;
    min-height: 300px;
}

#test-reader video {
    width: 100%;
    height: auto;
    object-fit: cover;
}

#cameraTestContainer {
    position: relative;
    min-height: 300px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    overflow: hidden;
}

#cameraTestStatus {
    position: absolute;
    bottom: 10px;
    left: 10px;
    right: 10px;
    z-index: 10;
    margin: 0;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?= $this->endSection() ?>