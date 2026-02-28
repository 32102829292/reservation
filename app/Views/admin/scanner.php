<?php
date_default_timezone_set('Asia/Manila');
$page = 'scanner';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Scanner | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/html5-qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b;
            overflow-x: hidden;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* Sidebar */
        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            width: 100%;
        }

        .sidebar-header {
            flex-shrink: 0;
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item { transition: all 0.2s; border-radius: 20px; }
        .sidebar-item.active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }

        /* Mobile Nav */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(30,41,59,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container {
            display: flex; gap: 4px; overflow-x: auto;
            scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
        }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        main { 
            flex: 1;
            min-width: 0;
            padding: 1.5rem;
            overflow-y: auto;
            max-width: calc(100vw - 320px);
        }
        
        @media (max-width: 1024px) {
            main {
                max-width: 100vw;
            }
        }

        /* Scanner UI */
        .content-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        #scanner-viewport {
            width: 100%;
            border-radius: 24px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            min-height: 350px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        #scanner-viewport > * { border-radius: 22px; }
        #scanner-viewport img[alt="Info icon"] { display: none !important; }

        /* Result panel states */
        .result-idle    { background: #f8fafc; border-color: #e2e8f0; }
        .result-success { background: #f0fdf4; border-color: #86efac; }
        .result-warning { background: #fffbeb; border-color: #fcd34d; }
        .result-error   { background: #fef2f2; border-color: #fca5a5; }

        /* Scan flash animation */
        @keyframes flashBlue {
            0%   { box-shadow: 0 0 0 0 rgba(37,99,235,0.5); }
            70%  { box-shadow: 0 0 0 20px rgba(37,99,235,0); }
            100% { box-shadow: 0 0 0 0 rgba(37,99,235,0); }
        }
        .scan-flash { animation: flashBlue 0.6s ease; }

        /* History item */
        .history-item { 
            transition: all 0.2s; 
            cursor: pointer;
        }
        .history-item:hover {
            transform: translateX(4px);
            background-color: #f1f5f9;
        }

        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-declined { background: #fee2e2; color: #991b1b; }
        .status-claimed { background: #f3e8ff; color: #6b21a8; }

        /* Input */
        input[type="text"] {
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 0.875rem 1rem; font-size: 0.95rem;
            background: #f8fafc; transition: all 0.2s;
        }
        input[type="text"]:focus {
            outline: none; border-color: #2563eb; background: white;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
        }

        .btn-primary {
            background: #2563eb; color: white; border: none;
            padding: 0.875rem 2rem; border-radius: 14px; font-weight: 700;
            transition: all 0.2s; cursor: pointer;
        }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(37,99,235,0.3); }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            border-left-width: 4px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <?php
        $navItems = [
            ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
            ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
            ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
            ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
            ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
            ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
            ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
            ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
            ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
        ];
        ?>

        <!-- Sidebar -->
        <aside class="hidden lg:block w-80 flex-shrink-0 p-6">
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                    <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
                </div>

                <nav class="sidebar-nav space-y-1">
                    <?php foreach ($navItems as $item):
                        $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
                    ?>
                        <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                            <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                            <?= $item['label'] ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <div class="sidebar-footer">
                    <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                    </a>
                </div>
            </div>
        </aside>

        <!-- Mobile Nav -->
        <nav class="lg:hidden mobile-nav-pill">
            <div class="mobile-scroll-container text-white px-2">
                <?php foreach ($navItems as $item):
                    $isActive = ($page == $item['key']);
                    $btnClass = $isActive ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
                ?>
                    <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                        <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                        <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    </a>
                <?php endforeach; ?>
                <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition hover:bg-red-500/30 text-red-400">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">QR Scanner</h2>
                    <p class="text-slate-500">Verify and validate reservation e-tickets</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Scanner Active</span>
                </div>
            </header>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Left Column: Scanner -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Camera Card -->
                    <div class="content-card">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                            <h3 class="text-lg font-extrabold text-slate-800">Live Camera Feed</h3>
                            <div class="flex gap-3 w-full sm:w-auto">
                                <button id="startBtn" onclick="startScanner()"
                                    class="flex-1 sm:flex-none bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-camera"></i> Start
                                </button>
                                <button id="stopBtn" onclick="stopScanner()" style="display:none;"
                                    class="flex-1 sm:flex-none bg-slate-200 text-slate-700 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-300 transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-stop"></i> Stop
                                </button>
                            </div>
                        </div>

                        <!-- Scanner Viewport -->
                        <div id="scanner-viewport">
                            <div class="text-center p-8">
                                <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                                    <i class="fa-solid fa-camera text-3xl"></i>
                                </div>
                                <p class="text-slate-500 font-semibold">Camera is inactive</p>
                                <p class="text-slate-400 text-sm mt-2">Click "Start Camera" to begin scanning QR codes</p>
                            </div>
                        </div>

                        <!-- Manual Entry -->
                        <div class="mt-8 pt-6 border-t border-slate-200">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                <i class="fa-regular fa-keyboard mr-2"></i>Manual Code Entry
                            </label>
                            <div class="flex gap-3">
                                <input type="text" id="manualCode" placeholder="Enter e-ticket code..."
                                    class="flex-1 bg-white border-slate-200 focus:border-blue-500"
                                    onkeydown="if(event.key==='Enter') processCode(this.value)">
                                <button onclick="processCode(document.getElementById('manualCode').value)"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition whitespace-nowrap shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i> Verify
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result Panel -->
                    <div id="resultPanel" class="content-card !p-0 border-2 result-idle hidden overflow-hidden">
                        <div class="p-8">
                            <div class="flex items-start gap-5">
                                <div id="resultIcon" class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0"></div>
                                <div class="flex-1">
                                    <p id="resultTitle" class="text-xl font-black text-slate-900"></p>
                                    <p id="resultSub" class="text-slate-500 font-medium mt-1"></p>
                                </div>
                            </div>
                            <div id="resultDetails" class="mt-6 space-y-3 bg-slate-50 rounded-2xl p-5"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: History & Stats -->
                <div class="space-y-6">
                    <!-- Stats Card -->
                    <div class="content-card !p-6">
                        <h3 class="font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-blue-600"></i>
                            Session Stats
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="stat-card border-l-4 border-blue-500 p-4">
                                <p class="text-xs text-slate-400 mb-1">Total Scans</p>
                                <p class="text-2xl font-black text-slate-800" id="statTotal">0</p>
                            </div>
                            <div class="stat-card border-l-4 border-emerald-500 p-4">
                                <p class="text-xs text-slate-400 mb-1">Valid</p>
                                <p class="text-2xl font-black text-emerald-600" id="statValid">0</p>
                            </div>
                            <div class="stat-card border-l-4 border-amber-500 p-4">
                                <p class="text-xs text-slate-400 mb-1">Pending</p>
                                <p class="text-2xl font-black text-amber-600" id="statPending">0</p>
                            </div>
                            <div class="stat-card border-l-4 border-rose-500 p-4">
                                <p class="text-xs text-slate-400 mb-1">Invalid</p>
                                <p class="text-2xl font-black text-rose-600" id="statInvalid">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- History Card -->
                    <div class="content-card !p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-extrabold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                                Recent Scans
                            </h3>
                            <button onclick="clearHistory()" 
                                class="text-sm text-red-500 hover:text-red-700 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-trash-can"></i> Clear
                            </button>
                        </div>
                        <div id="historyList" class="space-y-2 max-h-[400px] overflow-y-auto pr-2">
                            <p class="text-slate-400 text-sm text-center py-8 italic">No scans yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Reservation data from PHP
        const reservations = <?= json_encode($allReservations ?? $reservations ?? []) ?>;
        const validateUrl = '<?= base_url("admin/validateETicket") ?>';
        const csrfToken = '<?= csrf_hash() ?>';
        const csrfName = '<?= csrf_token() ?>';

        let scanner = null;
        let scanHistory = [];
        let lastScanned = null;
        let currentReservation = null;
        let stats = {
            total: 0,
            valid: 0,
            pending: 0,
            invalid: 0
        };

        // Camera Functions
        function startScanner() {
            const viewport = document.getElementById('scanner-viewport');
            viewport.innerHTML = '<div id="qr-reader" style="width:100%; height:100%;"></div>';
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('stopBtn').style.display = 'inline-flex';

            scanner = new Html5Qrcode('qr-reader');
            Html5Qrcode.getCameras().then(devices => {
                if (!devices || !devices.length) {
                    showCameraError('No camera found');
                    return;
                }
                const camera = devices.find(d => /back|environment/i.test(d.label)) || devices[0];
                scanner.start(
                    camera.id,
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess,
                    () => {}
                ).catch(err => showCameraError(err));
            }).catch(err => showCameraError(err));
        }

        function stopScanner() {
            if (scanner) {
                scanner.stop().then(() => {
                    scanner = null;
                    resetViewport();
                }).catch(() => resetViewport());
            }
        }

        function resetViewport() {
            document.getElementById('scanner-viewport').innerHTML = `
                <div class="text-center p-8">
                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fa-solid fa-camera text-3xl"></i>
                    </div>
                    <p class="text-slate-500 font-semibold">Camera is inactive</p>
                    <p class="text-slate-400 text-sm mt-2">Click "Start Camera" to begin scanning QR codes</p>
                </div>`;
            document.getElementById('startBtn').style.display = 'inline-flex';
            document.getElementById('stopBtn').style.display = 'none';
        }

        function showCameraError(msg) {
            document.getElementById('scanner-viewport').innerHTML = `
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-red-400">
                        <i class="fa-solid fa-camera-slash text-2xl"></i>
                    </div>
                    <p class="text-red-600 font-bold">Camera Error</p>
                    <p class="text-slate-500 text-sm mt-2">${msg}</p>
                </div>`;
            document.getElementById('startBtn').style.display = 'inline-flex';
            document.getElementById('stopBtn').style.display = 'none';
        }

        // Scan Success
        function onScanSuccess(code) {
            if (code === lastScanned) return;
            lastScanned = code;
            setTimeout(() => lastScanned = null, 2000);

            const viewport = document.getElementById('scanner-viewport');
            viewport.classList.add('scan-flash');
            setTimeout(() => viewport.classList.remove('scan-flash'), 600);

            processCode(code);
        }

        // Process Code
        function processCode(code) {
            code = (code || '').trim();
            if (!code) return;

            addToHistory(code);

            // Find reservation
            let reservation = findReservation(code);

            if (!reservation) {
                stats.invalid++;
                updateStats();
                showResult('error', 'fa-circle-xmark', 'text-red-500', 'bg-red-50',
                    'Invalid Code',
                    `"${code}" not found`,
                    []);
                return;
            }

            currentReservation = reservation;
            const status = reservation.claimed ? 'claimed' : reservation.status;

            if (status === 'approved') stats.valid++;
            else if (status === 'pending') stats.pending++;
            else stats.invalid++;
            updateStats();

            // Prepare details
            const details = [
                { label: 'Reservation ID', value: '#' + reservation.id },
                { label: 'Name', value: reservation.visitor_name || reservation.full_name || 'Guest' },
                { label: 'Email', value: reservation.user_email || reservation.visitor_email || '—' },
                { label: 'Resource', value: reservation.resource_name || 'Resource #' + reservation.resource_id },
                { label: 'PC Number', value: reservation.pc_number || '—' },
                { label: 'Date', value: reservation.reservation_date },
                { label: 'Time', value: `${reservation.start_time?.substring(0,5)} – ${reservation.end_time?.substring(0,5)}` },
                { label: 'Purpose', value: reservation.purpose || '—' },
                { label: 'Status', value: status.charAt(0).toUpperCase() + status.slice(1) }
            ];

            if (reservation.claimed) {
                details.push({ label: 'Claimed At', value: new Date(reservation.claimed_at).toLocaleString() });
            }

            // Status configuration
            const config = {
                approved: { title: '✓ Valid Ticket', sub: 'Reservation approved', icon: 'fa-circle-check', color: 'text-emerald-600', bg: 'bg-emerald-50' },
                pending: { title: '⏳ Pending Approval', sub: 'Not yet approved', icon: 'fa-clock', color: 'text-amber-600', bg: 'bg-amber-50' },
                declined: { title: '✗ Declined', sub: 'Reservation rejected', icon: 'fa-ban', color: 'text-red-500', bg: 'bg-red-50' },
                claimed: { title: '✓✓ Already Used', sub: 'Ticket already claimed', icon: 'fa-check-double', color: 'text-purple-600', bg: 'bg-purple-50' }
            };

            const cfg = config[status] || config.pending;
            showResult(status, cfg.icon, cfg.color, cfg.bg, cfg.title, cfg.sub, details);
            document.getElementById('manualCode').value = '';
        }

        // Find reservation helper
        function findReservation(code) {
            // Direct match
            let res = reservations.find(r => r.e_ticket_code === code);
            if (res) return res;

            // Match by ID in code
            const idMatch = code.match(/(\d+)/);
            if (idMatch) {
                const id = parseInt(idMatch[1]);
                res = reservations.find(r => r.id === id);
                if (res) return res;
            }

            // Partial match
            return reservations.find(r => 
                code.includes(r.e_ticket_code) || 
                (r.id && code.includes(r.id.toString()))
            );
        }

        // Show Result
        function showResult(state, icon, iconColor, iconBg, title, sub, details) {
            const panel = document.getElementById('resultPanel');
            panel.className = `content-card !p-0 border-2 result-${state} overflow-hidden`;
            panel.classList.remove('hidden');

            document.getElementById('resultIcon').className = `w-16 h-16 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 ${iconBg} ${iconColor}`;
            document.getElementById('resultIcon').innerHTML = `<i class="fa-solid ${icon}"></i>`;
            document.getElementById('resultTitle').textContent = title;
            document.getElementById('resultSub').textContent = sub;

            document.getElementById('resultDetails').innerHTML = details.map(d => `
                <div class="flex justify-between items-start py-3 border-b border-slate-200 last:border-0">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">${d.label}</span>
                    <span class="font-semibold text-slate-800 text-sm text-right">${d.value}</span>
                </div>
            `).join('');

            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // History Functions
        function addToHistory(code) {
            const time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            scanHistory.unshift({ code, time });
            if (scanHistory.length > 10) scanHistory.pop();
            renderHistory();
        }

        function renderHistory() {
            const list = document.getElementById('historyList');
            if (!scanHistory.length) {
                list.innerHTML = '<p class="text-slate-400 text-sm text-center py-8 italic">No scans yet</p>';
                return;
            }

            list.innerHTML = scanHistory.map(item => {
                const res = findReservation(item.code);
                let statusColor = 'bg-slate-100 text-slate-500';
                let statusIcon = 'fa-qrcode';

                if (res) {
                    if (res.claimed) {
                        statusColor = 'bg-purple-100 text-purple-600';
                        statusIcon = 'fa-check-double';
                    } else {
                        const colors = {
                            approved: 'bg-emerald-100 text-emerald-600',
                            pending: 'bg-amber-100 text-amber-600',
                            declined: 'bg-red-100 text-red-500'
                        };
                        statusColor = colors[res.status] || 'bg-slate-100 text-slate-500';
                        statusIcon = res.status === 'approved' ? 'fa-check' : 
                                    res.status === 'pending' ? 'fa-clock' : 
                                    res.status === 'declined' ? 'fa-ban' : 'fa-qrcode';
                    }
                } else {
                    statusColor = 'bg-red-100 text-red-500';
                    statusIcon = 'fa-circle-exclamation';
                }

                return `
                    <div class="history-item flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all"
                         onclick="processCode('${item.code}')">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 text-sm truncate">${item.code}</p>
                            <p class="text-xs text-slate-400 mt-1">${item.time}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl ${statusColor} flex items-center justify-center ml-2">
                            <i class="fa-solid ${statusIcon} text-sm"></i>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function clearHistory() {
            if (!scanHistory.length) return;
            if (confirm('Clear scan history?')) {
                scanHistory = [];
                stats = { total: 0, valid: 0, pending: 0, invalid: 0 };
                updateStats();
                renderHistory();
                document.getElementById('resultPanel').classList.add('hidden');
            }
        }

        function updateStats() {
            stats.total = scanHistory.length;
            document.getElementById('statTotal').textContent = stats.total;
            document.getElementById('statValid').textContent = stats.valid;
            document.getElementById('statPending').textContent = stats.pending;
            document.getElementById('statInvalid').textContent = stats.invalid;
        }

        // Initialize
        renderHistory();
    </script>
</body>
</html>