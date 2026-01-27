<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>QR Code Scanner</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/html5-qrcode.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff6ff;
            overflow-x: hidden;
        }

        main {
            padding-bottom: env(safe-area-inset-bottom, 5.5rem);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 9999px;
            background-color: #3b82f6;
            color: white;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: #2563eb;
        }

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

        @media (max-width: 768px) {
            .scanner-container {
                padding: 1rem;
            }

            #scanner {
                max-width: 100%;
            }
        }
    </style>
</head>

<!-- Hamburger Menu Button for Small Screens -->
<button id="hamburgerBtn" class="fixed top-4 right-4 z-50 bg-blue-600 text-white p-2 rounded-lg shadow-lg md:hidden">
    <i class="fa-solid fa-bars"></i>
</button>
<div id="mobileDrawer" class="fixed inset-0 z-40 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" id="drawerBackdrop"></div>
    <div class="absolute left-0 top-0 h-full w-64 bg-blue-600 text-white shadow-xl transform -translate-x-full transition-transform duration-300" id="drawerContent">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="/admin/new-reservation" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
                <a href="/admin/manage-reservations" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-calendar"></i> Manage Reservations
                </a>
                <a href="/admin/manage-sk" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-user-shield"></i> Manage SK Accounts
                </a>
                <a href="/admin/login-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-clock"></i> Login Logs
                </a>
                <a href="/admin/scanner" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition bg-blue-700 font-semibold">
                    <i class="fa-solid fa-qrcode"></i> Scanner
                </a>
                <a href="/admin/activity-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-list"></i> Activity Logs
                </a>
                <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-regular fa-user"></i> Profile
                </a>
            </nav>
        </div>
    </div>
</div>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'scanner'; ?>

    <div class="flex flex-1 flex-col lg:flex-row">

        <aside class="hidden lg:flex flex-col w-64 bg-blue-600 text-white shadow-xl rounded-tr-3xl rounded-br-3xl p-6">
            <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="/admin/new-reservation" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
                <a href="/admin/manage-reservations" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'manage-reservations') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-calendar"></i> Manage Reservations
                </a>
                <a href="/admin/manage-sk" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'manage-sk') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-user-shield"></i> Manage SK Accounts
                </a>
                <a href="/admin/login-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'login-logs') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-clock"></i> Login Logs
                </a>
                <a href="/admin/scanner" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'scanner') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-qrcode"></i> Scanner
                </a>
                <a href="/admin/activity-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'activity-logs') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-list"></i> Activity Logs
                </a>
                <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'profile') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-regular fa-user"></i> Profile
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-4 lg:p-6 overflow-auto">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">QR Code Scanner</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="status-box">
                <i class="fa-solid fa-info-circle"></i> Use your camera to scan QR codes for reservations
            </div>

            <div class="scanner-container">
                <div class="mb-4 flex gap-2">
                    <button id="startBtn" class="btn btn-primary" onclick="startScanner()">
                        <i class="fa-solid fa-play"></i> Start Scanner
                    </button>
                    <button id="stopBtn" class="btn btn-danger" onclick="stopScanner()" style="display: none;">
                        <i class="fa-solid fa-stop"></i> Stop Scanner
                    </button>
                </div>

                <div id="scanner" style="display: none;"></div>

                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2"><i class="fa-solid fa-keyboard"></i> Manual Code Entry</h4>
                    <div class="flex gap-2">
                        <input type="text" id="manualCode" placeholder="Enter code manually" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="processManualCode()" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Process
                        </button>
                    </div>
                </div>

                <div id="result" class="result-box">
                    <h3><i class="fa-solid fa-check-circle"></i> Scan Successful!</h3>
                    <p><strong>Result:</strong> <span id="resultText"></span></p>
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

        </main>
    </div>

    <nav class="fixed bottom-0 left-0 right-0 bg-blue-600 text-white shadow-xl lg:hidden z-50">
        <div class="flex overflow-x-auto gap-1 p-2">
            <a href="/admin/dashboard" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Dashboard</span>
            </a>
            <a href="/admin/new-reservation" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-plus text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">New Reservation</span>
            </a>
            <a href="/admin/manage-reservations" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'manage-reservations') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-calendar text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Reservations</span>
            </a>
            <a href="/admin/manage-sk" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'manage-sk') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-user-shield text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Manage SK</span>
            </a>
            <a href="/admin/login-logs" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'login-logs') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-clock text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Login Logs</span>
            </a>
            <a href="/admin/scanner" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'scanner') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-qrcode text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Scanner</span>
            </a>
            <a href="/admin/activity-logs" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'activity-logs') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-list text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Activity Logs</span>
            </a>
            <a href="/admin/profile" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'profile') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Profile</span>
            </a>
        </div>
    </nav>

    <script>
        let html5QrcodeScanner = null;
        let scanHistory = JSON.parse(localStorage.getItem('scanHistory')) || [];

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

        function addToHistory(result) {
            const item = {
                text: result,
                time: new Date().toLocaleTimeString()
            };
            scanHistory.unshift(item);
            if (scanHistory.length > 10) {
                scanHistory.pop();
            }
            localStorage.setItem('scanHistory', JSON.stringify(scanHistory));
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
                        <div>${item.text}</div>
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
                localStorage.removeItem('scanHistory');
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
        updateHistoryDisplay();
    </script>

</body>

</html>
