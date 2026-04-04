<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Scanner | Admin</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>(function(){if(localStorage.getItem('admin_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}

        :root{
            --indigo:#3730a3;--indigo-mid:#4338ca;--indigo-light:#eef2ff;--indigo-border:#c7d2fe;
            --bg:#f0f2f9;--card:#ffffff;
            --font:'Plus Jakarta Sans',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;
            --shadow-sm:0 1px 4px rgba(15,23,42,.07),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.09),0 2px 4px rgba(15,23,42,.04);
            --shadow-lg:0 12px 40px rgba(15,23,42,.12),0 4px 8px rgba(15,23,42,.06);
            --r-sm:10px;--r-md:14px;--r-lg:20px;--r-xl:24px;
            --sidebar-w:268px;--ease:.18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h:60px;--mob-nav-total:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));
        }

        html{height:100%;height:100dvh;font-size:16px}
        body{font-family:var(--font);background:var(--bg);color:#0f172a;display:flex;height:100vh;height:100dvh;overflow:hidden;-webkit-font-smoothing:antialiased;}
        html.dark-pre body{background:#060e1e}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;padding:18px 14px;height:100vh;height:100dvh;display:flex;flex-direction:column}
        .sidebar-inner{background:var(--card);border-radius:var(--r-xl);border:1px solid rgba(99,102,241,.1);height:100%;display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-md)}
        .sidebar-top{padding:22px 18px 16px;border-bottom:1px solid rgba(99,102,241,.07)}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px;display:flex;flex-direction:column;gap:3px;scrollbar-width:none}
        .sidebar-nav::-webkit-scrollbar{display:none}
        .sidebar-footer{flex-shrink:0;padding:10px 10px 12px;border-top:1px solid rgba(99,102,241,.07)}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:.85rem;font-weight:600;color:#64748b;text-decoration:none;transition:all var(--ease)}
        .nav-link:hover{background:var(--indigo-light);color:var(--indigo)}
        .nav-link.active{background:var(--indigo);color:#fff;box-shadow:0 4px 14px rgba(55,48,163,.32)}
        .nav-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.9rem}
        .nav-link:not(.active) .nav-icon{background:#f1f5f9}
        .nav-link:hover:not(.active) .nav-icon{background:#e0e7ff}
        .nav-link.active .nav-icon{background:rgba(255,255,255,.15)}
        .logout-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:.85rem;font-weight:600;color:#94a3b8;text-decoration:none;transition:all var(--ease)}
        .logout-link:hover{background:#fef2f2;color:#dc2626}
        .logout-link:hover .nav-icon{background:#fee2e2}

        /* ── Mobile Nav ── */
        .mobile-nav-pill{display:none;position:fixed;bottom:0;left:0;right:0;width:100%;background:white;border-top:1px solid rgba(99,102,241,.1);height:var(--mob-nav-total);z-index:200;box-shadow:0 -4px 20px rgba(55,48,163,.1)}
        .mobile-scroll-container{display:flex;justify-content:space-evenly;align-items:center;height:var(--mob-nav-h);width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:0 4px}
        .mobile-scroll-container::-webkit-scrollbar{display:none}
        .mob-nav-item{flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:6px 10px;border-radius:12px;cursor:pointer;text-decoration:none;color:#64748b;transition:background .15s,color .15s;font-size:.62rem;font-weight:700;gap:2px}
        .mob-nav-item:hover,.mob-nav-item.active{background:var(--indigo-light);color:var(--indigo)}

        @media(max-width:1023px){.sidebar{display:none!important}.mobile-nav-pill{display:flex!important}.main-area{padding-bottom:calc(var(--mob-nav-total) + 16px)!important}}
        @media(min-width:1024px){.sidebar{display:flex!important}.mobile-nav-pill{display:none!important}}

        /* ── Main ── */
        .main-area{flex:1;min-width:0;height:100vh;height:100dvh;overflow-y:auto;overflow-x:hidden;-webkit-overflow-scrolling:touch;padding:24px 28px 40px}
        @media(max-width:1023px){.main-area::-webkit-scrollbar{display:none}.main-area{scrollbar-width:none}}
        @media(min-width:1024px){.main-area::-webkit-scrollbar{width:4px}.main-area::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}}
        @media(max-width:639px){.main-area{padding:14px 12px 20px}}

        /* ── Cards ── */
        .card{background:var(--card);border-radius:var(--r-lg);border:1px solid rgba(99,102,241,.08);box-shadow:var(--shadow-sm);padding:20px 22px}

        /* ── Scanner Viewport ── */
        #scanner-viewport{width:100%;border-radius:var(--r-md);overflow:hidden;border:2px solid rgba(99,102,241,.15);min-height:280px;background:#f8fafc;display:flex;align-items:center;justify-content:center;position:relative;transition:border-color .3s}
        @media(min-width:640px){#scanner-viewport{min-height:340px}}
        #scanner-viewport.active{border-color:var(--indigo-border)}

        #scan-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none}
        .scan-frame{width:200px;height:200px;position:relative}
        @media(min-width:640px){.scan-frame{width:220px;height:220px}}
        .scan-frame::before,.scan-frame::after{content:'';position:absolute;width:36px;height:36px;border-color:var(--indigo);border-style:solid}
        .scan-frame::before{top:0;left:0;border-width:3px 0 0 3px;border-radius:4px 0 0 0}
        .scan-frame::after{bottom:0;right:0;border-width:0 3px 3px 0;border-radius:0 0 4px 0}
        .scan-frame-tr,.scan-frame-bl{position:absolute;width:36px;height:36px;border-color:var(--indigo);border-style:solid}
        .scan-frame-tr{top:0;right:0;border-width:3px 3px 0 0;border-radius:0 4px 0 0}
        .scan-frame-bl{bottom:0;left:0;border-width:0 0 3px 3px;border-radius:0 0 0 4px}
        .scan-line{position:absolute;left:8px;right:8px;height:2px;background:linear-gradient(90deg,transparent,var(--indigo),transparent);top:8px;animation:scanLine 2s ease-in-out infinite;border-radius:1px}
        @keyframes scanLine{0%{top:8px;opacity:0}10%{opacity:1}90%{opacity:1}100%{top:calc(100% - 10px);opacity:0}}

        /* ── Result states ── */
        .result-idle{border-color:rgba(99,102,241,.08)!important}
        .result-success{background:#f0fdf4!important;border-color:#86efac!important}
        .result-warning{background:#fffbeb!important;border-color:#fcd34d!important}
        .result-error{background:#fff1f2!important;border-color:#fca5a5!important}
        .result-claimed{background:#faf5ff!important;border-color:#d8b4fe!important}

        @keyframes flashIndigo{0%{box-shadow:0 0 0 0 rgba(55,48,163,.5)}70%{box-shadow:0 0 0 20px rgba(55,48,163,0)}100%{box-shadow:0 0 0 0 rgba(55,48,163,0)}}
        .scan-flash{animation:flashIndigo .6s ease}

        /* ── Field input ── */
        .field-input{width:100%;background:#f8fafc;border:1px solid rgba(99,102,241,.15);border-radius:var(--r-sm);padding:11px 14px;font-family:var(--font);font-size:.875rem;font-weight:600;color:#0f172a;transition:all .2s;outline:none}
        .field-input:focus{border-color:var(--indigo);background:white;box-shadow:0 0 0 4px rgba(55,48,163,.08)}

        /* ── Stat row ── */
        .stat-row{display:flex;justify-content:space-between;align-items:center;padding:.5rem .75rem;border-radius:var(--r-sm);transition:background .15s}
        .stat-row:hover{background:rgba(99,102,241,.04)}

        /* ── Cam status ── */
        .cam-status{display:flex;align-items:center;gap:6px;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8}
        .cam-dot{width:7px;height:7px;border-radius:50%;background:#94a3b8;transition:background .3s}
        .cam-dot.live{background:var(--indigo);box-shadow:0 0 6px rgba(55,48,163,.5);animation:livePulse 1.5s ease-in-out infinite}
        @keyframes livePulse{0%,100%{opacity:1}50%{opacity:.5}}

        /* ── Buttons ── */
        .btn-start{background:var(--indigo);color:white;box-shadow:0 4px 12px rgba(55,48,163,.3);transition:all .18s}
        .btn-start:hover{box-shadow:0 8px 20px rgba(55,48,163,.4);transform:translateY(-1px)}
        .btn-stop{background:#f1f5f9;color:#475569;border:1px solid rgba(99,102,241,.12);transition:all .18s}
        .btn-stop:hover{background:#e2e8f0;color:#0f172a}
        .btn-verify{background:#0f172a;color:white;transition:all .18s}
        .btn-verify:hover{background:#1e293b;transform:translateY(-1px)}

        /* ── History items ── */
        .history-item{transition:all .18s;border:1px solid rgba(99,102,241,.08)}
        .history-item:hover{border-color:var(--indigo-border);background:var(--indigo-light);transform:translateX(2px)}

        /* ── Grid ── */
        .scanner-grid{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(0,1fr);gap:18px}
        @media(max-width:900px){.scanner-grid{grid-template-columns:1fr}}

        /* ── Result sheet (mobile) ── */
        .result-overlay{display:none;position:fixed;inset:0;z-index:200;align-items:flex-end;justify-content:center}
        .result-overlay.open{display:flex;animation:fadeBg .18s ease}
        @keyframes fadeBg{from{opacity:0}to{opacity:1}}
        .result-overlay-bg{position:absolute;inset:0;background:rgba(15,23,42,.45);backdrop-filter:blur(6px)}
        .result-sheet{position:relative;width:100%;background:var(--card);border-radius:var(--r-xl) var(--r-xl) 0 0;max-height:88vh;overflow-y:auto;z-index:1;animation:sheetUp .26s cubic-bezier(.34,1.2,.64,1) both;box-shadow:0 -8px 32px rgba(0,0,0,.14)}
        @keyframes sheetUp{from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none}}
        .sheet-handle{width:40px;height:4px;background:#e2e8f0;border-radius:999px;margin:12px auto 0}

        @media(min-width:640px){.result-overlay{display:none!important}.result-panel-inline{display:block!important}}
        .result-panel-inline{display:none}

        /* ── Debug ── */
        #debugPanel{position:fixed;bottom:20px;left:20px;background:white;border:1px solid rgba(99,102,241,.1);border-radius:var(--r-md);padding:12px;font-size:12px;max-width:420px;max-height:320px;overflow-y:auto;display:none;z-index:1000;box-shadow:var(--shadow-md)}
        .debug-show{display:block!important}
        .debug-content{font-family:monospace;white-space:pre-wrap;word-break:break-all}

        /* ── Dark Mode ── */
        body.dark{--bg:#060e1e;--card:#0b1628;--indigo-light:rgba(55,48,163,.12);--indigo-border:rgba(99,102,241,.25);color:#e2eaf8}
        body.dark .sidebar-inner{background:#0b1628;border-color:rgba(99,102,241,.12)}
        body.dark .sidebar-top{border-color:rgba(99,102,241,.1)}
        body.dark .sidebar-footer{border-color:rgba(99,102,241,.1)}
        body.dark .nav-link{color:#7fb3e8}
        body.dark .nav-link:hover{background:rgba(99,102,241,.12);color:#a5b4fc}
        body.dark .nav-link:not(.active) .nav-icon{background:rgba(99,102,241,.1)}
        body.dark .nav-link:hover:not(.active) .nav-icon{background:rgba(99,102,241,.2)}
        body.dark .logout-link{color:#4a6fa5}
        body.dark .logout-link:hover{background:rgba(239,68,68,.1);color:#f87171}
        body.dark .logout-link:hover .nav-icon{background:rgba(239,68,68,.12)}
        body.dark .mobile-nav-pill{background:#0b1628;border-color:rgba(99,102,241,.18)}
        body.dark .mob-nav-item{color:#7fb3e8}
        body.dark .mob-nav-item.active{background:rgba(99,102,241,.18)}
        body.dark .card{background:#0b1628;border-color:rgba(99,102,241,.1)}
        body.dark #scanner-viewport{background:#101e35;border-color:rgba(99,102,241,.18)}
        body.dark #scanner-viewport.active{border-color:rgba(99,102,241,.4)}
        body.dark .field-input{background:#101e35;border-color:rgba(99,102,241,.18);color:#e2eaf8}
        body.dark .field-input:focus{background:#0b1628;border-color:var(--indigo)}
        body.dark .field-input::placeholder{color:#4a6fa5}
        body.dark .stat-row:hover{background:rgba(99,102,241,.06)}
        body.dark .btn-stop{background:#101e35;border-color:rgba(99,102,241,.15);color:#7fb3e8}
        body.dark .btn-stop:hover{background:#1e3a5f;color:#e2eaf8}
        body.dark .btn-verify{background:#e2eaf8;color:#0f172a}
        body.dark .btn-verify:hover{background:#ffffff}
        body.dark .history-item{border-color:rgba(99,102,241,.1);background:#101e35}
        body.dark .history-item:hover{background:rgba(99,102,241,.12);border-color:rgba(99,102,241,.3)}
        body.dark .result-success{background:rgba(5,46,22,.5)!important;border-color:#16a34a!important}
        body.dark .result-warning{background:rgba(120,53,15,.4)!important;border-color:#d97706!important}
        body.dark .result-error{background:rgba(127,29,29,.4)!important;border-color:#f87171!important}
        body.dark .result-claimed{background:rgba(59,7,100,.4)!important;border-color:#a855f7!important}
        body.dark .result-sheet{background:#0b1628;color:#e2eaf8}
        body.dark .sheet-handle{background:#1e3a5f}
        body.dark #debugPanel{background:#0b1628;border-color:rgba(99,102,241,.15)}
        body.dark .cam-status{color:#4a6fa5}
    </style>
</head>
<body class="flex">

<?php
date_default_timezone_set('Asia/Manila');
$page = 'scanner';
$reservationModel = new \App\Models\ReservationModel();
$allReservations  = $reservationModel
    ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
    ->join('resources', 'resources.id = reservations.resource_id', 'left')
    ->join('users',     'users.id = reservations.user_id',         'left')
    ->orderBy('reservations.created_at', 'DESC')
    ->findAll();

$navItems = [
    ['url'=>'/admin/dashboard',           'icon'=>'fa-house',           'label'=>'Dashboard',       'key'=>'dashboard'],
    ['url'=>'/admin/new-reservation',     'icon'=>'fa-plus',            'label'=>'New Reservation', 'key'=>'new-reservation'],
    ['url'=>'/admin/manage-reservations', 'icon'=>'fa-calendar',        'label'=>'Reservations',    'key'=>'manage-reservations'],
    ['url'=>'/admin/manage-pcs',          'icon'=>'fa-desktop',         'label'=>'Manage PCs',      'key'=>'manage-pcs'],
    ['url'=>'/admin/manage-sk',           'icon'=>'fa-user-shield',     'label'=>'Manage SK',       'key'=>'manage-sk'],
    ['url'=>'/admin/books',               'icon'=>'fa-book-open',       'label'=>'Library',         'key'=>'books'],
    ['url'=>'/admin/login-logs',          'icon'=>'fa-clock',           'label'=>'Login Logs',      'key'=>'login-logs'],
    ['url'=>'/admin/scanner',             'icon'=>'fa-qrcode',          'label'=>'Scanner',         'key'=>'scanner'],
    ['url'=>'/admin/activity-logs',       'icon'=>'fa-list',            'label'=>'Activity Logs',   'key'=>'activity-logs'],
    ['url'=>'/admin/profile',             'icon'=>'fa-user',            'label'=>'Profile',         'key'=>'profile'],
];
?>

<div id="debugPanel">
    <strong>Debug Info:</strong>
    <div id="debugContent" class="debug-content"></div>
    <button onclick="clearDebug()" style="margin-top:8px;font-size:11px;background:#f1f5f9;padding:3px 8px;border-radius:6px;border:none;cursor:pointer">Clear</button>
    <button onclick="showAllReservations()" style="margin-top:8px;font-size:11px;background:var(--indigo-light);padding:3px 8px;border-radius:6px;border:none;cursor:pointer;margin-left:4px">Show All</button>
</div>

<div class="result-overlay" id="resultOverlay">
    <div class="result-overlay-bg" onclick="closeResultSheet()"></div>
    <div class="result-sheet" id="resultSheet">
        <div class="sheet-handle"></div>
        <div id="resultSheetBody" style="padding:16px 24px 32px"></div>
    </div>
</div>

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-top">
            <div style="font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#94a3b8;margin-bottom:5px">Admin Control Room</div>
            <div style="font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.03em">my<span style="color:var(--indigo)">Space.</span></div>
            <div style="font-size:.7rem;color:#94a3b8;margin-top:3px">Administration Panel</div>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']) ? 'active' : '';
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ?>">
                    <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem"></i></div>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08)"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171"></i></div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- ════════ MOBILE NAV ════════ -->
<nav class="mobile-nav-pill">
    <div class="mobile-scroll-container">
        <?php foreach ($navItems as $item):
            $active = ($page == $item['key']) ? 'active' : '';
        ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ?>" title="<?= $item['label'] ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.05rem"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item" style="color:#f87171" title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.05rem"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>

<!-- ════════ MAIN ════════ -->
<main class="main-area">
    <!-- Header -->
    <header style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <div>
            <p style="font-size:.62rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px">Administration</p>
            <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1">QR Scanner</h2>
            <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Verify and validate reservation e-tickets.</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
            <div onclick="toggleDark()" title="Toggle dark mode" style="width:44px;height:44px;background:white;border:1px solid rgba(99,102,241,.12);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;transition:all var(--ease);box-shadow:var(--shadow-sm)">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
            </div>
            <div class="cam-status">
                <span class="cam-dot" id="camDot"></span>
                <span id="camStatusLabel">Camera off</span>
            </div>
        </div>
    </header>

    <div class="scanner-grid">
        <!-- Left: scanner + result -->
        <div style="display:flex;flex-direction:column;gap:16px">
            <div class="card">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:16px;gap:10px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:36px;height:36px;border-radius:10px;background:var(--indigo-light);display:flex;align-items:center;justify-content:center">
                            <i class="fa-solid fa-camera" style="color:var(--indigo);font-size:.85rem"></i>
                        </div>
                        <div>
                            <p style="font-weight:800;font-size:.9rem;color:#0f172a">Live Camera Feed</p>
                            <p style="font-size:.68rem;color:#94a3b8;margin-top:1px">Point at a QR code to scan</p>
                        </div>
                    </div>
                    <div style="display:flex;gap:8px">
                        <button id="startBtn" onclick="startScanner()" class="btn-start" style="padding:9px 16px;border-radius:var(--r-sm);font-weight:700;font-size:.8rem;display:flex;align-items:center;gap:6px;border:none;cursor:pointer;font-family:var(--font)">
                            <i class="fa-solid fa-camera" style="font-size:.75rem"></i> Start Camera
                        </button>
                        <button id="switchBtn" onclick="switchCamera()" style="display:none;padding:9px 12px;border-radius:var(--r-sm);font-weight:700;font-size:.8rem;display:none;align-items:center;border:none;cursor:pointer;font-family:var(--font)" class="btn-stop" title="Switch Camera">
                            <i class="fa-solid fa-camera-rotate"></i>
                        </button>
                        <button id="stopBtn" onclick="stopScanner()" style="display:none;padding:9px 16px;border-radius:var(--r-sm);font-weight:700;font-size:.8rem;align-items:center;gap:6px;border:none;cursor:pointer;font-family:var(--font)" class="btn-stop">
                            <i class="fa-solid fa-stop" style="font-size:.75rem"></i> Stop
                        </button>
                    </div>
                </div>
                <div id="scanner-viewport">
                    <div style="text-align:center;padding:40px 20px" id="viewport-placeholder">
                        <div style="width:56px;height:56px;background:rgba(99,102,241,.08);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                            <i class="fa-solid fa-qrcode" style="font-size:1.5rem;color:#94a3b8"></i>
                        </div>
                        <p style="font-weight:700;color:#475569;font-size:.88rem">Camera is inactive.</p>
                        <p style="color:#94a3b8;font-size:.75rem;margin-top:4px">Tap "Start Camera" to begin scanning.</p>
                    </div>
                </div>
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(99,102,241,.08)">
                    <label style="display:block;font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px">Manual Code Entry</label>
                    <div style="display:flex;gap:8px">
                        <input type="text" id="manualCode" class="field-input" placeholder="Paste or type the reservation code…" onkeydown="if(event.key==='Enter') processCode(this.value)">
                        <button onclick="processCode(document.getElementById('manualCode').value)" class="btn-verify" style="padding:11px 18px;border-radius:var(--r-sm);font-weight:700;font-size:.8rem;white-space:nowrap;flex-shrink:0;border:none;cursor:pointer;font-family:var(--font)">Verify</button>
                    </div>
                </div>
            </div>

            <div id="resultPanelInline" class="result-panel-inline card result-idle" style="padding:0;border-width:2px;transition:all .3s;display:none">
                <div id="resultBodyInline" style="padding:20px 22px"></div>
            </div>
        </div>

        <!-- Right: history + stats -->
        <div style="display:flex;flex-direction:column;gap:14px">
            <div class="card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                    <p style="font-weight:800;font-size:.88rem;color:#0f172a">Recent Scans</p>
                    <button onclick="clearHistory()" style="font-size:.7rem;font-weight:700;color:#94a3b8;background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:7px;transition:all .15s" onmouseover="this.style.background='#fef2f2';this.style.color='#dc2626'" onmouseout="this.style.background='none';this.style.color='#94a3b8'">Clear</button>
                </div>
                <div id="historyList" style="display:flex;flex-direction:column;gap:6px">
                    <p style="color:#94a3b8;font-size:.82rem;text-align:center;padding:24px 0;font-style:italic">No recent scans</p>
                </div>
            </div>

            <div class="card">
                <p style="font-weight:800;font-size:.88rem;color:#0f172a;margin-bottom:14px">Session Stats</p>
                <div style="display:flex;flex-direction:column;gap:2px">
                    <div class="stat-row"><span style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8">Total Scanned</span><span id="statTotal" style="font-weight:800;color:#0f172a;font-family:var(--mono)">0</span></div>
                    <div class="stat-row"><span style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#16a34a">Valid</span><span id="statValid" style="font-weight:800;color:#16a34a;font-family:var(--mono)">0</span></div>
                    <div class="stat-row"><span style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#7c3aed">Already Claimed</span><span id="statClaimed" style="font-weight:800;color:#7c3aed;font-family:var(--mono)">0</span></div>
                    <div class="stat-row"><span style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#d97706">Pending</span><span id="statPending" style="font-weight:800;color:#d97706;font-family:var(--mono)">0</span></div>
                    <div class="stat-row"><span style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#dc2626">Invalid / Declined</span><span id="statInvalid" style="font-weight:800;color:#dc2626;font-family:var(--mono)">0</span></div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
const reservations = <?= json_encode($allReservations) ?>;
const validateUrl  = '<?= base_url("admin/validateETicket") ?>';
const csrfToken    = '<?= csrf_hash() ?>';
const csrfName     = '<?= csrf_token() ?>';

let videoStream=null, videoEl=null, canvasEl=null, rafId=null;
let scanHistory=[], lastScanned=null, currentCode=null, currentReservation=null;
let stats={total:0,valid:0,claimed:0,pending:0,invalid:0};
let debugMode=false, scanning=false, availableCameras=[], currentCamIndex=0;

/* Dark mode */
function toggleDark(){
    const isDark=document.body.classList.toggle('dark');
    document.getElementById('darkIcon').innerHTML=isDark
        ?'<i class="fa-regular fa-moon" style="font-size:.85rem"></i>'
        :'<i class="fa-regular fa-sun" style="font-size:.85rem"></i>';
    localStorage.setItem('admin_theme',isDark?'dark':'light');
}
(function(){
    if(localStorage.getItem('admin_theme')==='dark'){
        document.body.classList.add('dark');
        const icon=document.getElementById('darkIcon');
        if(icon)icon.innerHTML='<i class="fa-regular fa-moon" style="font-size:.85rem"></i>';
    }
    document.documentElement.classList.remove('dark-pre');
})();

function debug(msg,data){if(!debugMode)return;const c=document.getElementById('debugContent'),t=new Date().toLocaleTimeString();c.innerHTML=`[${t}] ${msg}`+(data?'\n'+JSON.stringify(data,null,2):'')+'\n'+c.innerHTML;}
function clearDebug(){document.getElementById('debugContent').innerHTML='';}
function showAllReservations(){debug('All:',reservations.map(r=>({id:r.id,code:r.e_ticket_code,status:r.status,claimed:r.claimed})));}
document.addEventListener('keydown',e=>{if(e.ctrlKey&&e.shiftKey&&e.key==='D'){e.preventDefault();debugMode=!debugMode;document.getElementById('debugPanel').classList.toggle('debug-show');if(debugMode)showAllReservations();}});

function isClaimed(res){return[true,1,'t','true','1'].includes(res.claimed);}
function setCamStatus(live){document.getElementById('camDot').classList.toggle('live',live);document.getElementById('camStatusLabel').textContent=live?'LIVE':'Camera off';}

function buildScannerUI(){
    const vp=document.getElementById('scanner-viewport');
    vp.innerHTML='';vp.classList.add('active');
    videoEl=document.createElement('video');
    videoEl.setAttribute('autoplay','');videoEl.setAttribute('muted','');videoEl.setAttribute('playsinline','');
    videoEl.style.cssText='width:100%;height:100%;object-fit:cover;border-radius:12px;display:block;';
    vp.appendChild(videoEl);
    const overlay=document.createElement('div');
    overlay.id='scan-overlay';
    overlay.innerHTML='<div class="scan-frame"><div class="scan-frame-tr"></div><div class="scan-frame-bl"></div><div class="scan-line"></div></div>';
    vp.appendChild(overlay);
    canvasEl=document.createElement('canvas');
    canvasEl.style.display='none';
    document.body.appendChild(canvasEl);
}

async function startScanner(camIndex){
    if(location.protocol!=='https:'&&location.hostname!=='localhost'&&location.hostname!=='127.0.0.1'){showCameraError('Camera requires HTTPS.');return;}
    if(!navigator.mediaDevices?.getUserMedia){showCameraError('Your browser does not support camera access.');return;}
    document.getElementById('startBtn').style.display='none';
    document.getElementById('stopBtn').style.display='inline-flex';
    buildScannerUI();
    try{const tmp=await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}}});tmp.getTracks().forEach(t=>t.stop());}
    catch(e){showCameraError('Could not access camera. Please allow camera permissions.');return;}
    try{const devices=await navigator.mediaDevices.enumerateDevices();availableCameras=devices.filter(d=>d.kind==='videoinput');}
    catch(e){availableCameras=[];}
    if(camIndex!=null){currentCamIndex=camIndex;}
    else{const rearIdx=availableCameras.findIndex(c=>/back|rear|environment/i.test(c.label));currentCamIndex=rearIdx>=0?rearIdx:0;}
    document.getElementById('switchBtn').style.display=availableCameras.length>1?'inline-flex':'none';
    await openCamera(currentCamIndex);
}

async function openCamera(index){
    if(videoStream){scanning=false;if(rafId){cancelAnimationFrame(rafId);rafId=null;}videoStream.getTracks().forEach(t=>t.stop());videoStream=null;}
    const device=availableCameras[index];
    let stream=null;
    const attempts=[
        device?.deviceId?{video:{deviceId:{exact:device.deviceId},width:{ideal:1280},height:{ideal:720}}}:null,
        {video:{facingMode:{ideal:'environment'},width:{ideal:1280},height:{ideal:720}}},
        {video:{facingMode:'environment'}},{video:true}
    ].filter(Boolean);
    for(const constraint of attempts){try{stream=await navigator.mediaDevices.getUserMedia(constraint);if(stream)break;}catch(e){debug('Constraint failed:',e.message);}}
    if(!stream){showCameraError('Could not open camera. Please check permissions.');return;}
    videoStream=stream;videoEl.srcObject=stream;
    videoEl.onloadedmetadata=()=>{videoEl.play().then(()=>{scanning=true;setCamStatus(true);requestAnimationFrame(scanFrame);}).catch(e=>showCameraError('Could not play video: '+e.message));};
    videoEl.onerror=()=>showCameraError('Video error');
}

async function switchCamera(){
    if(availableCameras.length<2)return;
    const btn=document.getElementById('switchBtn');
    btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i>';
    currentCamIndex=(currentCamIndex+1)%availableCameras.length;
    await openCamera(currentCamIndex);
    btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-camera-rotate"></i>';
}

function scanFrame(){
    if(!scanning||!videoEl)return;
    if(videoEl.readyState>=2){
        const w=videoEl.videoWidth,h=videoEl.videoHeight;
        if(w&&h){
            canvasEl.width=w;canvasEl.height=h;
            try{
                const ctx=canvasEl.getContext('2d');ctx.drawImage(videoEl,0,0,w,h);
                const code=jsQR(ctx.getImageData(0,0,w,h).data,w,h,{inversionAttempts:'dontInvert'});
                if(code?.data&&code.data!==lastScanned){
                    lastScanned=code.data;setTimeout(()=>{lastScanned=null;},3000);
                    const vp=document.getElementById('scanner-viewport');
                    vp.classList.add('scan-flash');vp.addEventListener('animationend',()=>vp.classList.remove('scan-flash'),{once:true});
                    processCode(code.data);
                }
            }catch(e){debug('scanFrame error:',e.message);}
        }
    }
    rafId=requestAnimationFrame(scanFrame);
}

function stopScanner(){
    scanning=false;if(rafId){cancelAnimationFrame(rafId);rafId=null;}
    if(videoStream){videoStream.getTracks().forEach(t=>t.stop());videoStream=null;}
    if(canvasEl?.parentNode){canvasEl.parentNode.removeChild(canvasEl);canvasEl=null;}
    videoEl=null;availableCameras=[];
    document.getElementById('switchBtn').style.display='none';
    setCamStatus(false);resetViewport();
}

function resetViewport(){
    const vp=document.getElementById('scanner-viewport');vp.classList.remove('active');
    vp.innerHTML='<div style="text-align:center;padding:40px 20px"><div style="width:56px;height:56px;background:rgba(99,102,241,.08);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px"><i class="fa-solid fa-qrcode" style="font-size:1.5rem;color:#94a3b8"></i></div><p style="font-weight:700;color:#475569;font-size:.88rem">Camera is inactive.</p><p style="color:#94a3b8;font-size:.75rem;margin-top:4px">Tap "Start Camera" to begin scanning.</p></div>';
    document.getElementById('startBtn').style.display='inline-flex';
    document.getElementById('stopBtn').style.display='none';
}

function showCameraError(msg){
    const vp=document.getElementById('scanner-viewport');vp.classList.remove('active');
    vp.innerHTML=`<div style="width:100%;text-align:center;padding:32px 20px;background:#fff1f2;border-radius:var(--r-md)"><div style="width:52px;height:52px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px"><i class="fa-solid fa-camera-slash" style="font-size:1.1rem;color:#dc2626"></i></div><p style="font-weight:700;color:#dc2626;font-size:.88rem;margin-bottom:4px">Camera unavailable</p><p style="color:#64748b;font-size:.75rem">${msg}</p><button onclick="startScanner()" class="btn-start" style="margin-top:14px;padding:9px 18px;border-radius:var(--r-sm);font-weight:700;font-size:.8rem;display:inline-flex;align-items:center;gap:6px;border:none;cursor:pointer;font-family:var(--font)"><i class="fa-solid fa-rotate-right"></i> Try Again</button></div>`;
    document.getElementById('startBtn').style.display='inline-flex';
    document.getElementById('stopBtn').style.display='none';
    setCamStatus(false);
}

function processCode(code){
    code=(code||'').trim();if(!code)return;debug('Processing:',code);currentCode=code;addToHistory(code);
    const res=findReservationByCode(code);
    if(!res){stats.invalid++;updateStats();showResult('error','fa-circle-xmark','#dc2626','#fee2e2','Code Not Recognised',`"${code}" doesn't match any reservation.`,[],false);return;}
    debug('Found:',{id:res.id,status:res.status,claimed:res.claimed});currentReservation=res;
    let pcLabel='—';
    if(res.pc_numbers){try{const a=typeof res.pc_numbers==='string'?JSON.parse(res.pc_numbers):res.pc_numbers;pcLabel=Array.isArray(a)?a.join(', '):a;}catch{pcLabel=res.pc_numbers;}}
    else if(res.pc_number){pcLabel=res.pc_number;}
    const effectiveStatus=isClaimed(res)?'claimed':res.status;
    const cfgMap={
        approved:{state:'success',icon:'fa-circle-check',iconColor:'#16a34a',iconBg:'#dcfce7',title:'Access Granted',sub:'Reservation is approved and valid.'},
        pending:{state:'warning',icon:'fa-clock',iconColor:'#d97706',iconBg:'#fef3c7',title:'Pending Approval',sub:'This reservation has not been approved yet.'},
        declined:{state:'error',icon:'fa-ban',iconColor:'#dc2626',iconBg:'#fee2e2',title:'Access Denied',sub:'This reservation has been declined.'},
        canceled:{state:'error',icon:'fa-ban',iconColor:'#dc2626',iconBg:'#fee2e2',title:'Access Denied',sub:'This reservation was canceled.'},
        claimed:{state:'claimed',icon:'fa-check-double',iconColor:'#7c3aed',iconBg:'#ede9fe',title:'Already Claimed',sub:'This ticket has already been used.'},
    };
    const cfg=cfgMap[effectiveStatus]||cfgMap.pending;
    if(effectiveStatus==='approved')stats.valid++;else if(effectiveStatus==='claimed')stats.claimed++;else if(effectiveStatus==='pending')stats.pending++;else stats.invalid++;
    updateStats();
    const details=[
        {label:'Reservation ID',value:'#'+res.id},
        {label:'Name',value:res.visitor_name||res.full_name||'Guest'},
        {label:'Email',value:res.visitor_email||res.user_email||'—'},
        {label:'Asset',value:res.resource_name||`Resource #${res.resource_id}`},
        {label:'Workstation',value:pcLabel},
        {label:'Date',value:res.reservation_date},
        {label:'Time',value:`${res.start_time} – ${res.end_time}`},
        {label:'Purpose',value:res.purpose||'—'},
        {label:'E-Ticket Code',value:res.e_ticket_code||'—'},
        {label:'Status',value:effectiveStatus.charAt(0).toUpperCase()+effectiveStatus.slice(1)},
    ];
    if(isClaimed(res)&&res.claimed_at)details.push({label:'Claimed At',value:new Date(res.claimed_at).toLocaleString()});
    showResult(cfg.state,cfg.icon,cfg.iconColor,cfg.iconBg,cfg.title,cfg.sub,details,effectiveStatus==='approved');
    document.getElementById('manualCode').value='';
}

function findReservationByCode(code){
    if(!code)return null;
    let r=reservations.find(r=>r.e_ticket_code===code);if(r)return r;
    if(!isNaN(code)){r=reservations.find(r=>r.id===parseInt(code)||String(r.id)===code);if(r)return r;}
    const m1=code.match(/(?:SK|ADMIN|RES)-(\d+)-/i);if(m1){r=reservations.find(r=>r.id===parseInt(m1[1]));if(r)return r;}
    const m2=code.match(/(?:SK|ADMIN|RES)(\d+)/i);if(m2){r=reservations.find(r=>r.id===parseInt(m2[1]));if(r)return r;}
    for(const rv of reservations){if(rv.e_ticket_code&&code.includes(rv.e_ticket_code))return rv;}
    return null;
}

function buildResultHTML(state,icon,iconColor,iconBg,title,sub,details,showValidate,idSuffix){
    const rows=details.map(d=>`<div style="display:flex;justify-content:space-between;align-items:flex-start;padding:9px 0;border-bottom:1px solid rgba(99,102,241,.07);gap:12px"><span style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#94a3b8;flex-shrink:0;margin-top:2px">${d.label}</span><span style="font-weight:700;color:#0f172a;font-size:.82rem;text-align:right;word-break:break-all">${d.value}</span></div>`).join('');
    const validateSection=showValidate?`<div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(99,102,241,.08)"><button id="validateBtn${idSuffix}" onclick="validateTicket()" style="width:100%;padding:13px;background:var(--indigo);color:white;border-radius:var(--r-md);font-weight:700;font-size:.85rem;border:none;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(55,48,163,.28);transition:background .15s"><i class="fa-solid fa-circle-check" style="font-size:.8rem"></i> Mark as Used / Check In</button></div>`:'';
    return `<div style="display:flex;align-items:center;gap:14px;margin-bottom:16px"><div style="width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.1rem;background:${iconBg}"><i class="fa-solid ${icon}" style="color:${iconColor}"></i></div><div style="flex:1;min-width:0"><p style="font-weight:800;font-size:.95rem;color:#0f172a;line-height:1.2">${title}</p><p style="font-size:.78rem;color:#64748b;font-weight:500;margin-top:3px">${sub}</p></div></div><div>${rows}</div>${validateSection}`;
}

function showResult(state,icon,iconColor,iconBg,title,sub,details,showValidate){
    const isMobile=window.innerWidth<640;
    const html=buildResultHTML(state,icon,iconColor,iconBg,title,sub,details,showValidate,isMobile?'Sheet':'Inline');
    if(isMobile){
        document.getElementById('resultSheetBody').innerHTML=html;
        document.getElementById('resultOverlay').classList.add('open');
        document.body.style.overflow='hidden';
    }else{
        const panel=document.getElementById('resultPanelInline');
        panel.className=`result-panel-inline card result-${state}`;
        panel.style.display='block';panel.style.padding='0';panel.style.borderWidth='2px';
        document.getElementById('resultBodyInline').innerHTML=html;
        panel.scrollIntoView({behavior:'smooth',block:'nearest'});
    }
}

function closeResultSheet(){document.getElementById('resultOverlay').classList.remove('open');document.body.style.overflow='';}

function validateTicket(){
    if(!currentCode||!currentReservation)return;
    const isMobile=window.innerWidth<640;
    const btn=document.getElementById('validateBtn'+(isMobile?'Sheet':'Inline'));
    if(!btn)return;
    btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Checking in…';
    const codeToValidate=currentReservation.e_ticket_code||currentCode;
    const body=new URLSearchParams();body.append(csrfName,csrfToken);body.append('code',codeToValidate);
    fetch(validateUrl,{method:'POST',body,headers:{'X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded'}})
    .then(r=>r.json()).then(data=>{
        if(data.status==='success'||data.updated){
            const localRes=reservations.find(r=>r.id===currentReservation.id);
            if(localRes){localRes.claimed=true;localRes.claimed_at=new Date().toISOString();}
            stats.valid=Math.max(0,stats.valid-1);stats.claimed++;updateStats();
            btn.innerHTML='<i class="fa-solid fa-circle-check"></i> Checked In!';
            btn.style.background='#16a34a';btn.disabled=true;renderHistory();
        }else if(data.status==='error'&&(data.message||'').toLowerCase().includes('claimed')){
            const localRes=reservations.find(r=>r.id===currentReservation.id);if(localRes)localRes.claimed=true;
            stats.valid=Math.max(0,stats.valid-1);stats.claimed++;updateStats();
            btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-check-double"></i> Already claimed';btn.style.background='#7c3aed';
        }else{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> '+(data.message||'Failed');btn.style.background='#dc2626';}
    }).catch(()=>{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> Network error';btn.style.background='#dc2626';});
}

function addToHistory(code){
    const time=new Date().toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'});
    scanHistory=scanHistory.filter(h=>h.code!==code);scanHistory.unshift({code,time});
    if(scanHistory.length>10)scanHistory.pop();renderHistory();
}

function renderHistory(){
    const list=document.getElementById('historyList');
    if(!scanHistory.length){list.innerHTML='<p style="color:#94a3b8;font-size:.82rem;text-align:center;padding:24px 0;font-style:italic">No recent scans</p>';return;}
    const colorMap={approved:'background:#dcfce7;color:#16a34a',pending:'background:#fef3c7;color:#d97706',declined:'background:#fee2e2;color:#dc2626',canceled:'background:#fee2e2;color:#dc2626',claimed:'background:#ede9fe;color:#7c3aed'};
    const iconMap={approved:'fa-check',pending:'fa-clock',declined:'fa-ban',canceled:'fa-ban',claimed:'fa-check-double'};
    list.innerHTML=scanHistory.map(item=>{
        const res=findReservationByCode(item.code);
        const eff=res?(isClaimed(res)?'claimed':res.status):null;
        const sc=eff?(colorMap[eff]||'background:#f1f5f9;color:#64748b'):'background:#fee2e2;color:#dc2626';
        const si=eff?(iconMap[eff]||'fa-question'):'fa-xmark';
        return `<div class="history-item" style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:var(--r-sm);cursor:pointer" onclick="processCode('${item.code.replace(/'/g,"\\'")}')"><div style="min-width:0;margin-right:10px"><p style="font-weight:700;color:#0f172a;font-size:.78rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${item.code}</p><p style="font-size:.65rem;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-top:2px">${item.time}</p></div><div style="width:30px;height:30px;border-radius:9px;${sc};display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fa-solid ${si}" style="font-size:.7rem"></i></div></div>`;
    }).join('');
}

function clearHistory(){
    if(!scanHistory.length)return;if(!confirm('Clear all scan history?'))return;
    scanHistory=[];stats={total:0,valid:0,claimed:0,pending:0,invalid:0};updateStats();renderHistory();
    const p=document.getElementById('resultPanelInline');p.style.display='none';
    closeResultSheet();currentReservation=null;currentCode=null;
}

function updateStats(){
    stats.total=scanHistory.length;
    document.getElementById('statTotal').textContent=stats.total;
    document.getElementById('statValid').textContent=stats.valid;
    document.getElementById('statClaimed').textContent=stats.claimed;
    document.getElementById('statPending').textContent=stats.pending;
    document.getElementById('statInvalid').textContent=stats.invalid;
}

document.addEventListener('visibilitychange',()=>{if(document.hidden&&scanning)stopScanner();});
window.addEventListener('beforeunload',()=>{if(scanning)stopScanner();});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeResultSheet();});
renderHistory();
</script>
</body>
</html>