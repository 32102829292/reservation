<?php
date_default_timezone_set('Asia/Manila');
$page = 'scanner';

// Load ALL reservations for the scanner
$reservationModel = new \App\Models\ReservationModel();
$allReservations = $reservationModel
    ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
    ->join('resources', 'resources.id = reservations.resource_id', 'left')
    ->join('users', 'users.id = reservations.user_id', 'left')
    ->orderBy('reservations.created_at', 'DESC')
    ->findAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Scanner | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/html5-qrcode.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }

        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        .content-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 2rem;
        }

        #scanner-viewport {
            width: 100%; border-radius: 20px; overflow: hidden;
            border: 2px solid #e2e8f0; min-height: 320px;
            background: #f8fafc; display: flex; align-items: center; justify-content: center;
        }
        #scanner-viewport > * { border-radius: 16px; }
        #scanner-viewport img[alt="Info icon"] { display: none !important; }

        .result-idle    { background: #f8fafc; border-color: #e2e8f0; }
        .result-success { background: #f0fdf4; border-color: #86efac; }
        .result-warning { background: #fffbeb; border-color: #fcd34d; }
        .result-error   { background: #fef2f2; border-color: #fca5a5; }

        @keyframes flashGreen {
            0%   { box-shadow: 0 0 0 0 rgba(22,163,74,0.5); }
            70%  { box-shadow: 0 0 0 16px rgba(22,163,74,0); }
            100% { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
        }
        .scan-flash { animation: flashGreen 0.6s ease; }

        input[type="text"] {
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 0.875rem 1rem; font-size: 0.9rem;
            background: #f8fafc; color: #1e293b; transition: all 0.2s;
        }
        input[type="text"]:focus {
            outline: none; border-color: #16a34a; background: white;
            box-shadow: 0 0 0 4px rgba(22,163,74,0.08);
        }

        /* Debug panel */
        #debugPanel {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            font-size: 12px;
            max-width: 400px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .debug-show { display: block !important; }
        
        .debug-content {
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
            ['url' => '/sk/dashboard',       'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
            ['url' => '/sk/reservations',    'icon' => 'fa-calendar-alt',    'label' => 'All Reservations', 'key' => 'reservations'],
            ['url' => '/sk/new-reservation', 'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
            ['url' => '/sk/user-requests',   'icon' => 'fa-users',           'label' => 'User Requests',   'key' => 'user-requests'],
            ['url' => '/sk/claimed-reservations', 'icon' => 'fa-check-double', 'label' => 'Claimed',       'key' => 'claimed-reservations'],
            ['url' => '/sk/my-reservations', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'my-reservations'],
            ['url' => '/sk/scanner',         'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
            ['url' => '/sk/profile',         'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
        ];
    ?>

    <!-- Debug Panel -->
    <div id="debugPanel">
        <strong>Debug Info:</strong>
        <div id="debugContent" class="debug-content"></div>
        <button onclick="clearDebug()" class="mt-2 text-xs bg-slate-100 px-2 py-1 rounded">Clear</button>
        <button onclick="showAllReservations()" class="mt-2 text-xs bg-blue-100 px-2 py-1 rounded">Show All</button>
    </div>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
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
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-12 pb-32">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">QR Scanner</h2>
                <p class="text-slate-500 font-medium">Verify reservations in real-time.</p>
            </div>
        </header>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <!-- Left: Camera + Manual -->
            <div class="xl:col-span-2 space-y-6">
                <div class="content-card">
                    <!-- Camera controls -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                        <h3 class="text-lg font-extrabold text-slate-800">Live Camera Feed</h3>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button id="startBtn" onclick="startScanner()"
                                class="flex-1 sm:flex-none bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-green-700 transition shadow-lg shadow-green-200/60 flex items-center gap-2">
                                <i class="fa-solid fa-camera"></i> Start Camera
                            </button>
                            <button id="stopBtn" onclick="stopScanner()" style="display:none;"
                                class="flex-1 sm:flex-none bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-200 transition flex items-center gap-2">
                                <i class="fa-solid fa-stop"></i> Stop
                            </button>
                        </div>
                    </div>

                    <!-- Viewport -->
                    <div id="scanner-viewport">
                        <div class="text-center p-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                                <i class="fa-solid fa-qrcode text-2xl"></i>
                            </div>
                            <p class="text-slate-400 font-semibold">Camera is inactive.</p>
                            <p class="text-slate-400 text-xs mt-1">Click "Start Camera" to begin scanning.</p>
                        </div>
                    </div>

                    <!-- Manual entry -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <label class="block text-[0.68rem] font-black uppercase tracking-widest text-slate-400 mb-2">Manual Code Entry</label>
                        <div class="flex gap-2">
                            <input type="text" id="manualCode" placeholder="Paste or type the reservation code…"
                                class="flex-1" onkeydown="if(event.key==='Enter') processCode(this.value)">
                            <button onclick="processCode(document.getElementById('manualCode').value)"
                                class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition whitespace-nowrap">
                                Verify
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Result panel -->
                <div id="resultPanel" class="content-card !p-0 border-2 result-idle hidden overflow-hidden transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-5">
                            <div id="resultIcon" class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl flex-shrink-0"></div>
                            <div>
                                <p id="resultTitle" class="font-extrabold text-lg text-slate-900"></p>
                                <p id="resultSub" class="text-sm text-slate-500 font-medium"></p>
                            </div>
                        </div>
                        <div id="resultDetails" class="space-y-2"></div>

                        <!-- Validate button -->
                        <div id="validateWrap" class="hidden mt-5 pt-5 border-t border-slate-100">
                            <button id="validateBtn" onclick="validateTicket()"
                                class="w-full py-3.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-check"></i> Mark as Used / Check In
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Scan history -->
            <div class="space-y-6">
                <div class="content-card !p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-extrabold text-slate-800">Recent Scans</h3>
                        <button onclick="clearHistory()" class="text-red-400 text-xs font-bold hover:bg-red-50 hover:text-red-600 px-2 py-1 rounded-lg transition">
                            Clear
                        </button>
                    </div>
                    <div id="historyList" class="space-y-3">
                        <p class="text-slate-400 text-sm text-center py-6 italic">No recent scans</p>
                    </div>
                </div>

                <!-- Quick stats -->
                <div class="content-card !p-6">
                    <h3 class="font-extrabold text-slate-800 mb-4">Session Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Scanned</span>
                            <span id="statTotal" class="font-black text-slate-800">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black uppercase tracking-widest text-emerald-500">Approved</span>
                            <span id="statApproved" class="font-black text-emerald-600">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black uppercase tracking-widest text-amber-500">Pending</span>
                            <span id="statPending" class="font-black text-amber-600">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black uppercase tracking-widest text-red-400">Invalid / Declined</span>
                            <span id="statInvalid" class="font-black text-red-500">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Load all reservations from the database
        const reservations = <?= json_encode($allReservations) ?>;
        const validateUrl  = '<?= base_url("sk/validateETicket") ?>';
        const csrfToken    = '<?= csrf_hash() ?>';
        const csrfName     = '<?= csrf_token() ?>';

        let scanner       = null;
        let scanHistory   = [];
        let lastScanned   = null;
        let currentCode   = null;
        let currentReservation = null;
        let stats         = { total: 0, approved: 0, pending: 0, invalid: 0 };
        let debugMode     = false;

        // Debug function
        function debug(message, data = null) {
            const panel = document.getElementById('debugPanel');
            const content = document.getElementById('debugContent');
            const timestamp = new Date().toLocaleTimeString();
            
            let logMessage = `[${timestamp}] ${message}`;
            if (data) {
                if (typeof data === 'object') {
                    logMessage += `<br>Data: ${JSON.stringify(data, null, 2)}`;
                } else {
                    logMessage += `<br>Data: ${data}`;
                }
            }
            
            content.innerHTML = logMessage + '<br>' + content.innerHTML;
            if (content.children.length > 10) {
                content.removeChild(content.lastChild);
            }
        }

        function clearDebug() {
            document.getElementById('debugContent').innerHTML = '';
        }

        function showAllReservations() {
            debug('All reservations in database:', reservations.map(r => ({
                id: r.id,
                e_ticket_code: r.e_ticket_code,
                status: r.status,
                name: r.visitor_name || r.full_name
            })));
        }

        // Toggle debug mode with Ctrl+Shift+D
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                debugMode = !debugMode;
                document.getElementById('debugPanel').classList.toggle('debug-show');
                debug('Debug mode ' + (debugMode ? 'enabled' : 'disabled'));
                if (debugMode) {
                    showAllReservations();
                }
            }
        });

        // Camera functions
        function startScanner() {
            const viewport = document.getElementById('scanner-viewport');
            viewport.innerHTML = '<div id="qr-reader" style="width:100%"></div>';
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('stopBtn').style.display  = 'inline-flex';

            scanner = new Html5Qrcode('qr-reader');
            Html5Qrcode.getCameras().then(devices => {
                if (!devices || !devices.length) { showCameraError('No cameras found on this device.'); return; }
                const cam = devices.find(d => /back|environment/i.test(d.label)) || devices[0];
                scanner.start(
                    cam.id,
                    { fps: 12, qrbox: { width: 240, height: 240 }, aspectRatio: 1.0 },
                    onScanSuccess,
                    () => {}
                ).catch(err => showCameraError(err));
            }).catch(err => showCameraError(err));
        }

        function stopScanner() {
            if (scanner) {
                scanner.stop().then(() => { scanner = null; resetViewport(); })
                             .catch(() => { scanner = null; resetViewport(); });
            }
        }

        function resetViewport() {
            document.getElementById('scanner-viewport').innerHTML = `
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fa-solid fa-qrcode text-2xl"></i>
                    </div>
                    <p class="text-slate-400 font-semibold">Camera is inactive.</p>
                    <p class="text-slate-400 text-xs mt-1">Click "Start Camera" to begin scanning.</p>
                </div>`;
            document.getElementById('startBtn').style.display = 'inline-flex';
            document.getElementById('stopBtn').style.display  = 'none';
        }

        function showCameraError(msg) {
            document.getElementById('scanner-viewport').innerHTML = `
                <div class="text-center p-8">
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-red-400">
                        <i class="fa-solid fa-camera-slash text-xl"></i>
                    </div>
                    <p class="text-red-500 font-bold text-sm">Camera unavailable</p>
                    <p class="text-slate-400 text-xs mt-1">${msg}</p>
                </div>`;
            document.getElementById('startBtn').style.display = 'inline-flex';
            document.getElementById('stopBtn').style.display  = 'none';
        }

        // Scan success
        function onScanSuccess(code) {
            if (code === lastScanned) return;
            lastScanned = code;
            setTimeout(() => { lastScanned = null; }, 3000);

            const vp = document.getElementById('scanner-viewport');
            vp.classList.add('scan-flash');
            vp.addEventListener('animationend', () => vp.classList.remove('scan-flash'), { once: true });

            processCode(code);
        }

        // Process code
        function processCode(code) {
            code = (code || '').trim();
            debug('Processing code:', code);
            
            if (!code) return;

            currentCode = code;
            addToHistory(code);

            // Try to find reservation by multiple methods
            let res = findReservationByCode(code);

            if (!res) {
                debug('No reservation found for code:', code);
                stats.invalid++;
                updateStats();
                showResult('error', 'fa-circle-xmark', 'text-red-500', 'bg-red-50',
                    'Code Not Recognised',
                    `"${code}" doesn't match any reservation.`,
                    [], false);
                return;
            }

            debug('Found reservation:', {
                id: res.id,
                e_ticket_code: res.e_ticket_code,
                status: res.status,
                name: res.visitor_name || res.full_name
            });
            
            currentReservation = res;

            // Parse pc_numbers
            let pcLabel = '—';
            if (res.pc_numbers) {
                try {
                    const arr = typeof res.pc_numbers === 'string' ? JSON.parse(res.pc_numbers) : res.pc_numbers;
                    pcLabel = Array.isArray(arr) ? arr.join(', ') : arr;
                } catch { pcLabel = res.pc_numbers; }
            } else if (res.pc_number) {
                pcLabel = res.pc_number;
            }

            const statusConfig = {
                approved: { state: 'success', icon: 'fa-circle-check', iconColor: 'text-emerald-600', iconBg: 'bg-emerald-50', title: 'Access Granted',   sub: 'Reservation is approved and valid.' },
                pending:  { state: 'warning', icon: 'fa-clock',        iconColor: 'text-amber-600',   iconBg: 'bg-amber-50',   title: 'Pending Approval', sub: 'This reservation has not been approved yet.' },
                declined: { state: 'error',   icon: 'fa-ban',          iconColor: 'text-red-500',     iconBg: 'bg-red-50',     title: 'Access Denied',    sub: 'This reservation has been declined.' },
                canceled: { state: 'error',   icon: 'fa-ban',          iconColor: 'text-red-500',     iconBg: 'bg-red-50',     title: 'Access Denied',    sub: 'This reservation was canceled.' },
            };
            const cfg = statusConfig[res.status] || statusConfig.pending;

            if (res.status === 'approved')     stats.approved++;
            else if (res.status === 'pending') stats.pending++;
            else                               stats.invalid++;
            updateStats();

            // Show validate button only if approved (removed qr_used check)
            const canValidate = res.status === 'approved';

            const details = [
                { label: 'Reservation ID', value: '#' + res.id },
                { label: 'Name',           value: res.visitor_name || res.full_name || 'Guest' },
                { label: 'Email',          value: res.visitor_email || res.user_email || '—' },
                { label: 'Asset',           value: res.resource_name || `Resource #${res.resource_id}` },
                { label: 'Workstation',     value: pcLabel },
                { label: 'Date',            value: res.reservation_date },
                { label: 'Time',            value: `${res.start_time} – ${res.end_time}` },
                { label: 'Purpose',         value: res.purpose || '—' },
                { label: 'E-Ticket Code',   value: res.e_ticket_code || '—' },
                { label: 'Status',          value: res.status.charAt(0).toUpperCase() + res.status.slice(1) }
            ];

            showResult(cfg.state, cfg.icon, cfg.iconColor, cfg.iconBg, cfg.title, cfg.sub, details, canValidate);
            document.getElementById('manualCode').value = '';
        }

        // Helper function to find reservation by various code formats
        function findReservationByCode(code) {
            if (!code) return null;
            
            debug('Searching for code:', code);
            
            // Method 1: Direct match with e_ticket_code
            let res = reservations.find(r => r.e_ticket_code === code);
            if (res) {
                debug('Found by direct match with e_ticket_code');
                return res;
            }
            
            // Method 2: Try to find by ID directly (convert code to number if possible)
            if (!isNaN(code)) {
                const id = parseInt(code);
                res = reservations.find(r => r.id === id);
                if (res) {
                    debug('Found by ID (numeric):', id);
                    return res;
                }
            }
            
            // Method 3: Try to extract ID from SK-{id}-{timestamp} format
            const skFormatMatch = code.match(/SK-(\d+)-/);
            if (skFormatMatch) {
                const id = parseInt(skFormatMatch[1]);
                debug('Extracted ID from SK- format:', id);
                res = reservations.find(r => r.id === id);
                if (res) {
                    debug('Found by ID from SK- format');
                    return res;
                }
            }
            
            // Method 4: Try to extract ID from SK{id} format (like SK699)
            const skNumericMatch = code.match(/SK(\d+)/);
            if (skNumericMatch) {
                const id = parseInt(skNumericMatch[1]);
                debug('Extracted ID from SK format:', id);
                res = reservations.find(r => r.id === id);
                if (res) {
                    debug('Found by ID from SK format');
                    return res;
                }
            }
            
            // Method 5: Try to find by ID anywhere in the code
            for (let r of reservations) {
                if (code.includes(r.id.toString())) {
                    debug('Found by ID in code:', r.id);
                    return r;
                }
            }
            
            // Method 6: If code contains the e_ticket_code as substring
            res = reservations.find(r => r.e_ticket_code && code.includes(r.e_ticket_code));
            if (res) {
                debug('Found by e_ticket_code in code');
                return res;
            }
            
            debug('No reservation found');
            return null;
        }

        function showResult(state, icon, iconColor, iconBg, title, sub, details, showValidate) {
            const panel = document.getElementById('resultPanel');
            panel.className = `content-card !p-0 border-2 result-${state} overflow-hidden transition-all duration-300`;
            panel.classList.remove('hidden');

            document.getElementById('resultIcon').className = `w-12 h-12 rounded-2xl flex items-center justify-center text-xl flex-shrink-0 ${iconBg} ${iconColor}`;
            document.getElementById('resultIcon').innerHTML  = `<i class="fa-solid ${icon}"></i>`;
            document.getElementById('resultTitle').textContent = title;
            document.getElementById('resultSub').textContent   = sub;

            document.getElementById('resultDetails').innerHTML = details.map(d => `
                <div class="flex justify-between items-center py-2 border-b border-slate-100 last:border-0 gap-4">
                    <span class="text-[0.68rem] font-black uppercase tracking-widest text-slate-400 flex-shrink-0">${d.label}</span>
                    <span class="font-bold text-slate-800 text-sm text-right">${d.value}</span>
                </div>
            `).join('');

            // Validate button
            const validateWrap = document.getElementById('validateWrap');
            const validateBtn  = document.getElementById('validateBtn');
            if (showValidate) {
                validateWrap.classList.remove('hidden');
                validateBtn.disabled = false;
                validateBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Mark as Used / Check In';
            } else {
                validateWrap.classList.add('hidden');
            }

            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Validate ticket - UPDATED to remove qr_used
        function validateTicket() {
            if (!currentCode || !currentReservation) return;
            
            const btn = document.getElementById('validateBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Checking in…';

            // Use the e_ticket_code from the reservation
            const codeToValidate = currentReservation.e_ticket_code || currentCode;
            debug('Validating ticket with code:', codeToValidate);

            const body = new URLSearchParams();
            body.append(csrfName, csrfToken);
            body.append('code', codeToValidate);

            fetch(validateUrl, { 
                method: 'POST', 
                body, 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                } 
            })
            .then(r => r.json())
            .then(data => {
                debug('Validation response:', data);
                if (data.status === 'success') {
                    btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Checked In!';
                    btn.classList.replace('bg-green-600', 'bg-emerald-500');
                    
                    // Update local data (if your API returns updated status)
                    if (data.updated) {
                        currentReservation.checked_in = true;
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + (data.message || 'Failed');
                    btn.classList.add('bg-red-600');
                }
            })
            .catch(error => {
                debug('Validation error:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Network error';
                btn.classList.add('bg-red-600');
            });
        }

        // History functions
        function addToHistory(code) {
            const time = new Date().toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' });
            scanHistory.unshift({ code, time });
            if (scanHistory.length > 10) scanHistory.pop();
            renderHistory();
        }

        function renderHistory() {
            const list = document.getElementById('historyList');
            if (!scanHistory.length) {
                list.innerHTML = '<p class="text-slate-400 text-sm text-center py-6 italic">No recent scans</p>';
                return;
            }
            list.innerHTML = scanHistory.map(item => {
                const res = findReservationByCode(item.code);
                const statusColor = res
                    ? ({ approved: 'bg-emerald-100 text-emerald-600', pending: 'bg-amber-100 text-amber-600', declined: 'bg-red-100 text-red-500', canceled: 'bg-red-100 text-red-400' }[res.status] || 'bg-slate-100 text-slate-400')
                    : 'bg-red-100 text-red-400';
                const statusIcon = res
                    ? ({ approved: 'fa-check', pending: 'fa-clock', declined: 'fa-ban', canceled: 'fa-ban' }[res.status] || 'fa-question')
                    : 'fa-xmark';

                return `
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-100 hover:border-green-200 transition cursor-pointer"
                         onclick="processCode('${item.code.replace(/'/g, "\\'")}')">
                        <div class="overflow-hidden mr-3">
                            <p class="font-bold text-slate-800 text-sm truncate">${item.code}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">${item.time}</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl ${statusColor} flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid ${statusIcon} text-xs"></i>
                        </div>
                    </div>`;
            }).join('');
        }

        function clearHistory() {
            if (!scanHistory.length) return;
            if (confirm('Clear all scan history?')) {
                scanHistory = [];
                stats = { total: 0, approved: 0, pending: 0, invalid: 0 };
                updateStats();
                renderHistory();
                document.getElementById('resultPanel').classList.add('hidden');
                currentReservation = null;
                currentCode = null;
            }
        }

        function updateStats() {
            stats.total = scanHistory.length;
            document.getElementById('statTotal').textContent    = stats.total;
            document.getElementById('statApproved').textContent = stats.approved;
            document.getElementById('statPending').textContent  = stats.pending;
            document.getElementById('statInvalid').textContent  = stats.invalid;
        }

        // Initial debug
        setTimeout(() => {
            if (debugMode) {
                showAllReservations();
            }
        }, 1000);

        renderHistory();
    </script>
</body>
</html>