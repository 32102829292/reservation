<?= $this->extend('sk/layout') ?>

<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/html5-qrcode.min.js"></script>

<style>
    #scanner {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 1rem;
        overflow: hidden;
    }

    .scanner-container {
        background: white;
        border-radius: 2rem;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .result-box {
        background: #ecfdf5;
        border: 2px solid #10b981;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
        display: none;
    }

    .result-box.show {
        display: block;
    }

    .result-box h3 {
        color: #059669;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .result-box p {
        color: #047857;
        font-size: 0.95rem;
        word-break: break-all;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .status-box {
        background: #dbeafe;
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        color: #0c4a6e;
        font-size: 0.9rem;
    }

    .status-box.error {
        background: #fee2e2;
        border-left-color: #ef4444;
        color: #7f1d1d;
    }

    .status-box.success {
        background: #ecfdf5;
        border-left-color: #10b981;
        color: #065f46;
    }

    .scan-history {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .scan-history h3 {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .history-item {
        background: #f3f4f6;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .history-item .time {
        color: #6b7280;
        font-size: 0.75rem;
    }

    .history-item .copy-btn {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .history-item .copy-btn:hover {
        background: #2563eb;
    }

    .manual-input {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .manual-input h3 {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .manual-input input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .manual-input input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    @media (max-width: 768px) {
        .scanner-container {
            padding: 1rem;
        }

        #scanner {
            max-width: 100%;
        }
    }
</style>

<?php $page = $page ?? 'scanner'; ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
    <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">QR Code Scanner</h2>
</div>

<div class="status-box">
    <i class="fa-solid fa-info-circle"></i> Use your camera to scan QR codes or manually enter codes for reservations
</div>

<div class="scanner-container">
    <div class="mb-4 flex gap-2 flex-wrap">
        <button id="startBtn" class="btn btn-primary" onclick="startScanner()">
            <i class="fa-solid fa-play"></i> Start Scanner
        </button>
        <button id="stopBtn" class="btn btn-danger" onclick="stopScanner()" style="display: none;">
            <i class="fa-solid fa-stop"></i> Stop Scanner
        </button>
    </div>

    <div id="scanner" style="display: none;"></div>

    <div id="result" class="result-box">
        <h3><i class="fa-solid fa-check-circle"></i> Scan Successful!</h3>
        <p><strong>Result:</strong> <span id="resultText"></span></p>
    </div>
</div>

<div class="manual-input">
    <h3><i class="fa-solid fa-keyboard"></i> Manual Code Entry</h3>
    <input type="text" id="manualCode" placeholder="Enter QR code or reservation code manually" onkeypress="handleKeyPress(event)">
    <div class="flex gap-2">
        <button class="btn btn-primary" onclick="processManualCode()">
            <i class="fa-solid fa-check"></i> Process Code
        </button>
        <button class="btn btn-secondary" onclick="clearManualInput()">
            <i class="fa-solid fa-eraser"></i> Clear
        </button>
    </div>
</div>

<div class="scan-history">
    <h3><i class="fa-solid fa-history"></i> Scan History</h3>
    <div id="historyList">
        <p class="text-gray-500 text-sm">No scans yet</p>
    </div>
    <button class="btn btn-danger" onclick="clearHistory()" style="width: 100%; margin-top: 1rem;">
        <i class="fa-solid fa-trash"></i> Clear History
    </button>
</div>

<script>
    let html5QrcodeScanner = null;
    let scanHistory = JSON.parse(localStorage.getItem('skScanHistory')) || [];

    function startScanner() {
        const scanner = document.getElementById('scanner');
        scanner.style.display = 'block';
        document.getElementById('startBtn').style.display = 'none';
        document.getElementById('stopBtn').style.display = 'inline-block';

        html5QrcodeScanner = new Html5Qrcode("scanner");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                html5QrcodeScanner.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    onScanError
                );
            }
        }).catch(err => {
            showError("Camera not accessible: " + err);
        });
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(ignore => {
                document.getElementById('scanner').style.display = 'none';
                document.getElementById('startBtn').style.display = 'inline-block';
                document.getElementById('stopBtn').style.display = 'none';
            }).catch(err => {
                console.log("Failed to stop scanner: " + err);
            });
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('resultText').textContent = decodedText;
        document.getElementById('result').classList.add('show');

        addToHistory(decodedText);
    }

    function onScanError(error) {
        // Silently ignore scanning errors
    }

    function processManualCode() {
        const code = document.getElementById('manualCode').value.trim();
        if (!code) {
            alert('Please enter a code');
            return;
        }

        document.getElementById('resultText').textContent = code;
        document.getElementById('result').classList.add('show');

        addToHistory(code);
        clearManualInput();
    }

    function clearManualInput() {
        document.getElementById('manualCode').value = '';
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            processManualCode();
        }
    }

    function addToHistory(result) {
        const item = {
            text: result,
            time: new Date().toLocaleTimeString(),
            method: html5QrcodeScanner ? 'Camera' : 'Manual'
        };
        scanHistory.unshift(item);
        if (scanHistory.length > 10) {
            scanHistory.pop();
        }
        localStorage.setItem('skScanHistory', JSON.stringify(scanHistory));
        updateHistoryDisplay();
    }

    function updateHistoryDisplay() {
        const historyList = document.getElementById('historyList');
        if (scanHistory.length === 0) {
            historyList.innerHTML = '<p class="text-gray-500 text-sm">No scans yet</p>';
            return;
        }

        historyList.innerHTML = scanHistory.map((item, index) => `
            <div class="history-item">
                <div>
                    <div>${item.text} <span style="color: #6b7280; font-size: 0.75rem;">(${item.method})</span></div>
                    <div class="time">${item.time}</div>
                </div>
                <button class="copy-btn" onclick="copyToClipboard('${item.text}')">
                    <i class="fa-solid fa-copy"></i>
                </button>
            </div>
        `).join('');
    }

    function clearHistory() {
        if (confirm('Are you sure you want to clear the scan history?')) {
            scanHistory = [];
            localStorage.removeItem('skScanHistory');
            updateHistoryDisplay();
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }

    function showError(message) {
        document.getElementById('result').classList.remove('show');
        alert('Error: ' + message);
    }

    // Load history on page load
    updateHistoryDisplay();
</script>

<?= $this->endSection() ?>
