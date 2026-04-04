<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Manage SK Accounts | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <script>(function(){if(localStorage.getItem('admin_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        *{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
        :root{
            --bg:#f0f2f9;--card:#fff;--brd:rgba(99,102,241,.1);--brd2:rgba(99,102,241,.07);
            --text:#0f172a;--muted:#94a3b8;--muted2:#64748b;
            --indigo:#3730a3;--indigo-mid:#4338ca;--indigo-light:#eef2ff;--indigo-brd:#c7d2fe;
            --green:#16a34a;--green-bg:#dcfce7;--amber:#d97706;--amber-bg:#fef3c7;
            --purple:#7c3aed;--purple-bg:#ede9fe;--red:#dc2626;--red-bg:#fee2e2;
            --sidebar-w:268px;--r:20px;--r-md:14px;--r-sm:10px;
            --font:'Plus Jakarta Sans',system-ui,sans-serif;
            --shadow-sm:0 1px 4px rgba(15,23,42,.07),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.09),0 2px 4px rgba(15,23,42,.04);
            --mob-nav-h:60px;
        }
        html.dark-pre body{background:#060e1e}
        body{font-family:var(--font);background:var(--bg);color:var(--text);display:flex;min-height:100vh;-webkit-font-smoothing:antialiased}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;padding:18px 14px;height:100vh;position:sticky;top:0;overflow-y:auto}
        .sidebar::-webkit-scrollbar{display:none}
        .sidebar-inner{background:var(--card);border-radius:24px;border:1px solid var(--brd);height:100%;display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-md)}
        .sb-top{padding:20px 18px 16px;border-bottom:1px solid var(--brd2)}
        .brand-tag{font-size:10px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:var(--muted);margin-bottom:4px}
        .brand-name{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em}
        .brand-name em{font-style:normal;color:var(--indigo-mid)}
        .user-card{margin:12px 12px 0;background:var(--indigo-light);border-radius:var(--r-md);padding:12px 14px;border:1px solid var(--indigo-brd);display:flex;align-items:center;gap:9px}
        .user-av{width:34px;height:34px;border-radius:50%;background:var(--indigo);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0;box-shadow:0 2px 8px rgba(55,48,163,.3)}
        .user-name{font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role{font-size:11px;color:#6366f1;font-weight:500;margin-top:1px}
        .sb-nav{flex:1;overflow-y:auto;overflow-x:hidden;padding:10px;display:flex;flex-direction:column;gap:3px}
        .sb-nav::-webkit-scrollbar{display:none}
        .nav-sec-lbl{font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);padding:10px 10px 5px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:13px;font-weight:600;color:var(--muted2);text-decoration:none;transition:all .18s}
        .nav-link:hover{background:var(--indigo-light);color:var(--indigo)}
        .nav-link.active{background:var(--indigo);color:#fff;box-shadow:0 4px 14px rgba(55,48,163,.32)}
        .nav-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;background:#f1f5f9}
        .nav-link:not(.active):hover .nav-icon{background:var(--indigo-light)}
        .nav-link.active .nav-icon{background:rgba(255,255,255,.15)}
        .nav-badge{margin-left:auto;background:rgba(245,158,11,.18);color:#d97706;font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px}
        .nav-link.active .nav-badge{background:rgba(255,255,255,.22);color:#fff}
        .sb-footer{padding:10px 10px 12px;border-top:1px solid var(--brd2)}
        .logout-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;transition:all .18s}
        .logout-link:hover{background:var(--red-bg);color:var(--red)}
        .logout-link:hover .nav-icon{background:var(--red-bg)}

        /* ── Mobile Nav ── */
        .mobile-nav-pill{display:none;position:fixed;bottom:0;left:0;right:0;width:100%;background:var(--card);border-top:1px solid var(--brd);height:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));z-index:200;box-shadow:0 -4px 20px rgba(55,48,163,.1)}
        .mob-scroll{display:flex;justify-content:space-evenly;align-items:center;height:var(--mob-nav-h);overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:0 4px}
        .mob-scroll::-webkit-scrollbar{display:none}
        .mob-item{flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:6px 10px;border-radius:12px;cursor:pointer;text-decoration:none;color:var(--muted2);font-size:10px;font-weight:700;gap:2px;transition:all .15s;position:relative}
        .mob-item:hover,.mob-item.active{background:var(--indigo-light);color:var(--indigo)}
        @media(max-width:1023px){.sidebar{display:none!important}.mobile-nav-pill{display:flex!important}.main{padding-bottom:calc(var(--mob-nav-h) + 16px)!important}}
        @media(min-width:1024px){.mobile-nav-pill{display:none!important}}

        /* ── Main ── */
        .main{flex:1;min-width:0;padding:24px 28px 48px;overflow-x:hidden}
        @media(max-width:639px){.main{padding:14px 12px 0}}

        /* ── Stats ── */
        .stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:18px}
        @media(max-width:700px){.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        .stat-card{background:var(--card);border:1px solid var(--brd);border-left-width:4px;border-radius:var(--r);padding:14px 16px;cursor:pointer;transition:transform .15s,box-shadow .15s}
        .stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md)}
        .stat-card.ring-active{box-shadow:0 0 0 2px var(--indigo)}
        .stat-lbl{font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:4px}
        .stat-num{font-size:1.8rem;font-weight:800;line-height:1;letter-spacing:-.04em}

        /* ── Filter bar ── */
        .filter-bar{background:var(--card);border:1px solid var(--brd);border-radius:24px;padding:16px 18px;margin-bottom:14px;box-shadow:var(--shadow-sm)}
        .field{background:var(--card);border:1px solid var(--brd);border-radius:12px;padding:9px 14px 9px 36px;font-size:13px;font-family:var(--font);color:var(--text);width:100%;transition:all .2s;outline:none}
        .field:focus{border-color:var(--indigo-mid);box-shadow:0 0 0 3px rgba(67,56,202,.1)}
        .qtab{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid var(--brd);color:var(--muted2);background:var(--card);transition:all .15s;white-space:nowrap;font-family:var(--font)}
        .qtab:hover{border-color:var(--indigo);color:var(--indigo)}
        .qtab.active{background:var(--indigo);color:#fff;border-color:var(--indigo);box-shadow:0 4px 12px rgba(55,48,163,.3)}

        /* ── Badges ── */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:10px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
        .badge-pending{background:var(--amber-bg);color:var(--amber)}
        .badge-approved{background:var(--green-bg);color:var(--green)}
        .badge-rejected{background:var(--red-bg);color:var(--red)}

        /* ── Table ── */
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
        .table-wrap::-webkit-scrollbar{height:4px}
        .table-wrap::-webkit-scrollbar-thumb{background:var(--brd);border-radius:4px}
        table{width:100%;border-collapse:collapse;min-width:640px}
        thead th{background:#f8fafc;font-weight:800;text-transform:uppercase;font-size:10px;letter-spacing:.12em;color:var(--muted);padding:12px 14px;border-bottom:1px solid var(--brd);white-space:nowrap}
        td{padding:12px 14px;border-bottom:1px solid var(--brd2);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr{transition:background .12s;cursor:pointer}
        tbody tr:hover td{background:var(--indigo-light)}

        /* ── SK Avatar ── */
        .sk-avatar{width:36px;height:36px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:14px;flex-shrink:0}

        /* ── Action buttons ── */
        .btn-ghost{background:#f1f5f9;color:var(--muted2);border:none;border-radius:9px;padding:6px 10px;font-size:11px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .15s;display:inline-flex;align-items:center;gap:4px}
        .btn-ghost:hover{background:var(--indigo-light);color:var(--indigo)}
        .btn-approve-sm{background:var(--green-bg);color:var(--green);border:1px solid #86efac;border-radius:9px;padding:6px 10px;font-size:11px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .15s;display:inline-flex;align-items:center;gap:4px}
        .btn-approve-sm:hover{background:#bbf7d0}
        .btn-reject-sm{background:var(--red-bg);color:var(--red);border:1px solid #fca5a5;border-radius:9px;padding:6px 7px;font-size:11px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .15s;display:inline-flex;align-items:center;gap:4px}
        .btn-reject-sm:hover{background:#fecaca}

        /* ── SK card (mobile) ── */
        .sk-card{background:var(--card);border-radius:var(--r);border:1px solid var(--brd);padding:14px 16px;cursor:pointer;transition:all .15s}
        .sk-card:hover{border-color:var(--indigo-brd);box-shadow:var(--shadow-md);transform:translateY(-1px)}

        /* ── Overlays ── */
        .overlay{display:none;position:fixed;inset:0;z-index:200;align-items:center;justify-content:center}
        .overlay.open{display:flex}
        .overlay-bg{position:absolute;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(6px)}
        .modal-box{position:relative;margin:auto;background:var(--card);border-radius:28px;width:94%;max-width:500px;max-height:92vh;overflow-y:auto;box-shadow:0 25px 50px -12px rgba(0,0,0,.35);animation:popIn .22s cubic-bezier(.34,1.56,.64,1) both}
        .modal-box.sm{max-width:380px}
        .sheet-handle{display:none;width:40px;height:4px;background:var(--brd);border-radius:999px;margin:10px auto 0}
        @media(max-width:639px){
            .overlay#detailModal{align-items:flex-end}
            .overlay#detailModal .modal-box{margin:0;width:100%;max-width:100%;border-radius:28px 28px 0 0;max-height:92vh;animation:slideUp .28s cubic-bezier(.34,1.2,.64,1) both}
            .sheet-handle{display:block}
        }
        @keyframes popIn{from{opacity:0;transform:scale(.92) translateY(16px)}to{opacity:1;transform:none}}
        @keyframes slideUp{from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none}}
        .modal-box::-webkit-scrollbar{width:4px}
        .modal-box::-webkit-scrollbar-thumb{background:var(--brd);border-radius:4px}

        /* ── Detail rows ── */
        .drow{display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid var(--brd2)}
        .drow:last-child{border-bottom:none}
        .dicon{width:34px;height:34px;border-radius:12px;background:var(--indigo-light);color:var(--indigo);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
        .dlabel{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:2px}
        .dvalue{font-size:14px;font-weight:700;color:var(--text)}

        /* ── Modal buttons ── */
        .btn-confirm-approve{background:#16a34a;color:#fff;border:none;border-radius:14px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:var(--font);flex:1}
        .btn-confirm-approve:hover:not(:disabled){background:#15803d}
        .btn-confirm-reject{background:#ef4444;color:#fff;border:none;border-radius:14px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:var(--font);flex:1}
        .btn-confirm-reject:hover:not(:disabled){background:#dc2626}
        .btn-cancel{background:#f1f5f9;color:var(--muted2);border:none;border-radius:14px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:var(--font);flex:1}
        .btn-cancel:hover{background:#e2e8f0}

        @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        .fade-up{animation:fadeUp .35s ease both}

        /* ── DARK MODE ── */
        body.dark{background:#060e1e;color:#e2eaf8}
        body.dark .sidebar-inner{background:#0b1628;border-color:rgba(99,102,241,.12)}
        body.dark .sb-top,.dark .sb-footer{border-color:rgba(99,102,241,.1)}
        body.dark .brand-name{color:#e2eaf8}
        body.dark .user-card{background:rgba(55,48,163,.15);border-color:rgba(99,102,241,.2)}
        body.dark .user-name{color:#e2eaf8}
        body.dark .user-role{color:#818cf8}
        body.dark .nav-link{color:#7fb3e8}
        body.dark .nav-link:hover{background:rgba(99,102,241,.12);color:#a5b4fc}
        body.dark .nav-link:not(.active) .nav-icon{background:rgba(99,102,241,.1)}
        body.dark .nav-link:hover:not(.active) .nav-icon{background:rgba(99,102,241,.2)}
        body.dark .mobile-nav-pill{background:#0b1628;border-color:rgba(99,102,241,.18)}
        body.dark .mob-item{color:#7fb3e8}
        body.dark .mob-item.active,.dark .mob-item:hover{background:rgba(99,102,241,.18);color:#a5b4fc}
        body.dark .stat-card,.dark .filter-bar{background:#0b1628;border-color:#1e3a5f}
        body.dark .stat-lbl{color:#4a6fa5}
        body.dark .qtab{background:#0b1628;border-color:#1e3a5f;color:#93c5fd}
        body.dark .qtab:hover{border-color:var(--indigo);color:#a5b4fc}
        body.dark .qtab.active{background:var(--indigo);border-color:var(--indigo);color:#fff}
        body.dark .field{background:#101e35;border-color:#1e3a5f;color:#e2eaf8}
        body.dark .field:focus{border-color:#6366f1}
        body.dark thead th{background:#101e35!important;color:#4a6fa5!important;border-color:#1e3a5f!important}
        body.dark td{border-color:#1e3a5f;color:#e2eaf8}
        body.dark tbody tr:hover td{background:rgba(99,102,241,.08)!important}
        body.dark .badge-pending{background:rgba(251,191,36,.15);color:#fbbf24}
        body.dark .badge-approved{background:rgba(34,197,94,.15);color:#4ade80}
        body.dark .badge-rejected{background:rgba(239,68,68,.15);color:#f87171}
        body.dark .sk-card{background:#0b1628;border-color:#1e3a5f}
        body.dark .sk-card:hover{border-color:var(--indigo)}
        body.dark .modal-box{background:#0b1628}
        body.dark .modal-box::-webkit-scrollbar-thumb{background:#1e3a5f}
        body.dark .sheet-handle{background:#1e3a5f}
        body.dark .drow{border-color:#1e3a5f}
        body.dark .dvalue{color:#e2eaf8}
        body.dark .dicon{background:rgba(99,102,241,.12);color:#818cf8}
        body.dark #dHero{background:#101e35!important;border-color:#1e3a5f!important}
        body.dark .btn-cancel{background:#101e35;color:#93c5fd}
        body.dark .btn-cancel:hover{background:#1e3a5f}
        body.dark .btn-ghost{background:#101e35;color:#93c5fd}
        body.dark .btn-ghost:hover{background:#1e3a5f;color:#a5b4fc}
        body.dark #resultCount,.dark #tableFooter{color:#4a6fa5}
        body.dark .bg-slate-50\/60{background:rgba(16,30,53,.8)!important}
    </style>
</head>
<body class="flex min-h-screen">

<?php
$page = $page ?? 'manage-sk';
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
    ['url'=>'/admin/profile',             'icon'=>'fa-regular fa-user', 'label'=>'Profile',         'key'=>'profile'],
];

$pCount = count($pending  ?? []);
$aCount = count($approved ?? []);
$rCount = count($rejected ?? []);
$total  = $pCount + $aCount + $rCount;

$avatarPalette = [
    ['bg-blue-100 text-blue-700',   'bg-blue-100',   'text-blue-700'],
    ['bg-purple-100 text-purple-700','bg-purple-100', 'text-purple-700'],
    ['bg-emerald-100 text-emerald-700','bg-emerald-100','text-emerald-700'],
    ['bg-rose-100 text-rose-700',   'bg-rose-100',   'text-rose-700'],
    ['bg-amber-100 text-amber-700', 'bg-amber-100',  'text-amber-700'],
];

$avatarStyles = [
    'background:#dbeafe;color:#1d4ed8',
    'background:#f3e8ff;color:#7c3aed',
    'background:#d1fae5;color:#065f46',
    'background:#ffe4e6;color:#be123c',
    'background:#fef3c7;color:#92400e',
];

$allMerged = array_merge(
    array_map(fn($s) => array_merge($s, ['_status'=>'pending']),  $pending  ?? []),
    array_map(fn($s) => array_merge($s, ['_status'=>'approved']), $approved ?? []),
    array_map(fn($s) => array_merge($s, ['_status'=>'rejected']), $rejected ?? [])
);

$sIcon = ['pending'=>'fa-clock','approved'=>'fa-check','rejected'=>'fa-xmark'];
$adminName = session()->get('name') ?? 'Administrator';
$avatarLetter = strtoupper(mb_substr(trim($adminName), 0, 1));
?>

<form id="approveForm" method="POST" action="/admin/approve-sk" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
<form id="rejectForm"  method="POST" action="/admin/reject-sk"  style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="rejectId"></form>

<!-- DETAIL MODAL -->
<div id="detailModal" class="overlay" role="dialog" aria-modal="true">
    <div class="overlay-bg" onclick="closeModal('detail')"></div>
    <div class="modal-box">
        <div class="sheet-handle"></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 12px">
            <div>
                <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:3px">SK Account</p>
                <h3 style="font-size:18px;font-weight:800;color:var(--text)">Account Info</h3>
            </div>
            <button onclick="closeModal('detail')" style="width:36px;height:36px;border-radius:10px;background:#f1f5f9;border:none;color:var(--muted2);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;margin-top:2px"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="dHero" style="margin:0 20px 14px;background:var(--indigo-light);border:1px solid var(--indigo-brd);border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px"></div>
        <div id="dStatusBar" style="margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700"></div>
        <div style="padding:0 20px 8px">
            <div class="drow"><div class="dicon"><i class="fa-solid fa-envelope"></i></div><div><p class="dlabel">Email</p><p id="dEmail" class="dvalue" style="word-break:break-all"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-phone"></i></div><div><p class="dlabel">Phone</p><p id="dPhone" class="dvalue"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-regular fa-calendar"></i></div><div><p class="dlabel">Applied</p><p id="dDate" class="dvalue"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-shield-check"></i></div><div><p class="dlabel">Email Verified</p><p id="dVerified" class="dvalue"></p></div></div>
        </div>
        <div id="dActions" style="padding:16px 20px;border-top:1px solid var(--brd2);display:flex;gap:10px;margin-top:8px"></div>
    </div>
</div>

<!-- Approve confirm -->
<div id="approveModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('approve')"></div>
    <div class="modal-box sm">
        <div class="sheet-handle"></div>
        <div style="padding:24px 24px 20px;text-align:center">
            <div style="width:64px;height:64px;background:var(--green-bg);color:var(--green);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-user-check"></i></div>
            <h3 style="font-size:18px;font-weight:800;color:var(--text)">Approve SK Account?</h3>
            <p style="color:var(--muted);font-size:13px;margin-top:4px;font-weight:500">This will grant SK portal access.</p>
            <p id="approveConfirmName" style="color:var(--text);font-size:13px;margin-top:10px;font-weight:800"></p>
        </div>
        <div style="padding:0 24px 24px;display:flex;gap:10px">
            <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
            <button id="confirmApproveBtn" class="btn-confirm-approve" onclick="submitApprove()"><i class="fa-solid fa-check"></i> Approve</button>
        </div>
    </div>
</div>

<!-- Reject confirm -->
<div id="rejectModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('reject')"></div>
    <div class="modal-box sm">
        <div class="sheet-handle"></div>
        <div style="padding:24px 24px 20px;text-align:center">
            <div style="width:64px;height:64px;background:var(--red-bg);color:var(--red);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-user-xmark"></i></div>
            <h3 style="font-size:18px;font-weight:800;color:var(--text)">Reject SK Account?</h3>
            <p style="color:var(--muted);font-size:13px;margin-top:4px;font-weight:500">This action cannot be undone.</p>
            <p id="rejectConfirmName" style="color:var(--text);font-size:13px;margin-top:10px;font-weight:800"></p>
        </div>
        <div style="padding:0 24px 24px;display:flex;gap:10px">
            <button class="btn-cancel" onclick="closeModal('reject')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
            <button id="confirmRejectBtn" class="btn-confirm-reject" onclick="submitReject()"><i class="fa-solid fa-xmark"></i> Reject</button>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sb-top">
            <div class="brand-tag">Admin Control Room</div>
            <div class="brand-name">my<em>Space.</em></div>
            <div style="font-size:11px;color:var(--muted);margin-top:3px">Administration Panel</div>
        </div>
        <div class="user-card">
            <div class="user-av"><?= $avatarLetter ?></div>
            <div style="min-width:0">
                <div class="user-name"><?= htmlspecialchars($adminName) ?></div>
                <div class="user-role">System Admin</div>
            </div>
        </div>
        <nav class="sb-nav">
            <div class="nav-sec-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $hasBadge = ($item['key']==='manage-sk' && $pCount > 0);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                    <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:13px"></i></div>
                    <?= $item['label'] ?>
                    <?php if ($hasBadge): ?>
                        <span class="nav-badge"><?= $pCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sb-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08)"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:13px;color:#f87171"></i></div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- MOBILE NAV -->
<nav class="mobile-nav-pill">
    <div class="mob-scroll">
        <?php foreach ($navItems as $item):
            $active = ($page == $item['key']);
        ?>
            <a href="<?= $item['url'] ?>" class="mob-item <?= $active ? 'active' : '' ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1rem"></i>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-item" style="color:#f87171">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1rem"></i>
        </a>
    </div>
</nav>

<!-- MAIN -->
<main class="main">
    <!-- Header -->
    <header class="fade-up" style="display:flex;flex-direction:column;gap:3px;margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
            <div>
                <p style="font-size:10px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--muted);margin-bottom:3px">Admin Portal</p>
                <h2 style="font-size:26px;font-weight:800;color:var(--text);letter-spacing:-.04em">SK Accounts</h2>
                <p style="font-size:12px;color:var(--muted);margin-top:3px">Manage Sangguniang Kabataan registrations</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <button onclick="toggleDark()" id="darkBtn" style="width:42px;height:42px;background:var(--card);border:1px solid var(--brd);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:var(--muted2);cursor:pointer;transition:all .15s;font-size:15px">
                    <span id="darkIcon"><i class="fa-regular fa-sun"></i></span>
                </button>
                <?php if ($pCount > 0): ?>
                    <div style="display:flex;align-items:center;gap:7px;background:var(--amber-bg);border:1px solid #fde68a;color:#92400e;padding:9px 14px;border-radius:var(--r-sm);font-weight:700;font-size:12px;flex-shrink:0">
                        <i class="fa-solid fa-clock" style="font-size:11px"></i>
                        <?= $pCount ?> pending approval<?= $pCount > 1 ? 's' : '' ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Stat cards -->
    <div class="stats-grid fade-up">
        <?php foreach ([
            ['Total',    $total,  '#3730a3', 'fa-users',      'all'],
            ['Pending',  $pCount, '#d97706', 'fa-clock',      'pending'],
            ['Approved', $aCount, '#16a34a', 'fa-user-check', 'approved'],
            ['Rejected', $rCount, '#dc2626', 'fa-user-xmark', 'rejected'],
        ] as [$lbl, $val, $color, $ico, $tab]): ?>
            <div class="stat-card" style="border-left-color:<?= $color ?>" onclick="switchToTab('<?= $tab ?>')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <p class="stat-lbl"><?= $lbl ?></p>
                    <i class="fa-solid <?= $ico ?>" style="font-size:13px;color:<?= $color ?>"></i>
                </div>
                <p class="stat-num" style="color:<?= $color ?>"><?= $val ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar fade-up">
        <div style="position:relative;margin-bottom:12px">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:11px;pointer-events:none"></i>
            <input id="searchInput" type="text" placeholder="Search by name or email…" class="field" oninput="applyFilter()">
        </div>
        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px">
            <button class="qtab active" data-tab="all" onclick="switchToTab('all')"><i class="fa-solid fa-users" style="font-size:11px"></i> All <span style="font-size:9px;font-weight:800;opacity:.7"><?= $total ?></span></button>
            <button class="qtab" data-tab="pending" onclick="switchToTab('pending')"><i class="fa-solid fa-clock" style="font-size:11px"></i> Pending<?php if ($pCount > 0): ?><span style="background:#f59e0b;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;line-height:1"><?= $pCount ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="approved" onclick="switchToTab('approved')"><i class="fa-solid fa-user-check" style="font-size:11px"></i> Approved</button>
            <button class="qtab" data-tab="rejected" onclick="switchToTab('rejected')"><i class="fa-solid fa-user-xmark" style="font-size:11px"></i> Rejected</button>
        </div>
    </div>

    <p id="resultCount" style="font-size:11px;font-weight:700;color:var(--muted);padding:0 4px;margin-bottom:12px"></p>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block fade-up" style="background:var(--card);border:1px solid var(--brd);border-radius:24px;overflow:hidden;box-shadow:var(--shadow-sm)">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:48px">ID</th>
                        <th>Account</th>
                        <th>Email</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th style="text-align:right;width:190px">Actions</th>
                    </tr>
                </thead>
                <tbody id="skTableBody">
                    <?php if (empty($allMerged)): ?>
                        <tr><td colspan="6">
                            <div style="padding:80px 24px;text-align:center">
                                <i class="fa-solid fa-users" style="font-size:2.5rem;color:var(--brd);display:block;margin-bottom:12px"></i>
                                <p style="font-weight:800;color:var(--muted);font-size:15px">No SK accounts yet</p>
                                <p style="color:var(--muted);font-size:12px;margin-top:4px">Accounts will appear when users register.</p>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($allMerged as $idx => $sk):
                            $s    = $sk['_status'];
                            $name = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                            $email= htmlspecialchars($sk['email'] ?? '—');
                            $phone= htmlspecialchars($sk['phone'] ?? 'N/A');
                            $date = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                            $ver  = !empty($sk['is_verified']) ? 'Yes' : 'No';
                            $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                            $init = strtoupper(substr($name, 0, 1));
                            $mdata = json_encode(['id'=>$sk['id'],'status'=>$s,'name'=>$name,'email'=>$email,'phone'=>$phone,'date'=>$date,'verified'=>$ver,'avatarStyle'=>$avatarStyle,'initials'=>$init]);
                        ?>
                        <tr class="sk-row" data-status="<?= $s ?>" data-search="<?= strtolower("$name $email") ?>">
                            <td><span style="font-size:11px;font-weight:800;color:var(--muted);font-family:monospace">#<?= $sk['id'] ?></span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="sk-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                                    <div>
                                        <p style="font-weight:700;font-size:13px;color:var(--text)"><?= $name ?></p>
                                        <p style="font-size:11px;color:var(--muted);margin-top:2px">Applied <?= $date ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><p style="font-size:13px;color:var(--muted2);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px"><?= $email ?></p></td>
                            <td><p style="font-size:13px;color:var(--muted2);font-weight:600;white-space:nowrap"><?= $date ?></p></td>
                            <td>
                                <span class="badge badge-<?= $s ?>">
                                    <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?>" style="font-size:9px"></i> <?= ucfirst($s) ?>
                                </span>
                            </td>
                            <td style="text-align:right">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap">
                                    <button onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-ghost"><i class="fa-solid fa-eye" style="font-size:10px"></i> View</button>
                                    <?php if ($s === 'pending'): ?>
                                        <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve-sm"><i class="fa-solid fa-check" style="font-size:10px"></i> Approve</button>
                                        <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-reject-sm"><i class="fa-solid fa-xmark" style="font-size:10px"></i></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:10px 18px;border-top:1px solid var(--brd2);background:rgba(238,242,255,.4);display:flex;align-items:center;justify-content:space-between">
            <p id="tableFooter" style="font-size:11px;font-weight:700;color:var(--muted)"></p>
            <p style="font-size:11px;color:var(--brd);font-weight:600">Click View to see full account details</p>
        </div>
    </div>

    <!-- MOBILE CARDS -->
    <div class="md:hidden" id="mobileCardList" style="display:flex;flex-direction:column;gap:10px">
        <?php if (empty($allMerged)): ?>
            <div style="background:var(--card);border-radius:24px;border:1px solid var(--brd);padding:64px 24px;text-align:center">
                <i class="fa-solid fa-users" style="font-size:2rem;color:var(--brd);display:block;margin-bottom:10px"></i>
                <p style="font-weight:800;color:var(--muted)">No SK accounts yet</p>
            </div>
        <?php else: ?>
            <?php foreach ($allMerged as $idx => $sk):
                $s    = $sk['_status'];
                $name = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                $email= htmlspecialchars($sk['email'] ?? '—');
                $phone= htmlspecialchars($sk['phone'] ?? 'N/A');
                $date = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                $ver  = !empty($sk['is_verified']) ? 'Yes' : 'No';
                $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                $init = strtoupper(substr($name, 0, 1));
                $mdata = json_encode(['id'=>$sk['id'],'status'=>$s,'name'=>$name,'email'=>$email,'phone'=>$phone,'date'=>$date,'verified'=>$ver,'avatarStyle'=>$avatarStyle,'initials'=>$init]);
            ?>
                <div class="sk-card mobile-sk-card"
                     data-status="<?= $s ?>"
                     data-search="<?= strtolower("$name $email") ?>"
                     onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                        <div class="sk-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                        <div style="flex:1;min-width:0">
                            <p style="font-weight:700;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                            <p style="font-size:11px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p>
                        </div>
                        <span class="badge badge-<?= $s ?>" style="flex-shrink:0">
                            <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?>" style="font-size:9px"></i> <?= ucfirst($s) ?>
                        </span>
                    </div>
                    <p style="font-size:11px;color:var(--muted);font-weight:600;margin-bottom:10px">
                        <i class="fa-regular fa-calendar" style="font-size:10px;margin-right:4px"></i>Applied <?= $date ?>
                    </p>
                    <?php if ($s === 'pending'): ?>
                        <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--brd2)" onclick="event.stopPropagation()">
                            <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                style="flex:1;height:36px;border-radius:10px;background:var(--green-bg);color:var(--green);border:1px solid #86efac;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);transition:all .15s;display:flex;align-items:center;justify-content:center;gap:5px">
                                <i class="fa-solid fa-check" style="font-size:10px"></i> Approve
                            </button>
                            <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                style="flex:1;height:36px;border-radius:10px;background:var(--red-bg);color:var(--red);border:1px solid #fca5a5;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);transition:all .15s;display:flex;align-items:center;justify-content:center;gap:5px">
                                <i class="fa-solid fa-xmark" style="font-size:10px"></i> Reject
                            </button>
                        </div>
                    <?php else: ?>
                        <div style="padding-top:8px;border-top:1px solid var(--brd2)">
                            <p style="font-size:10px;font-weight:800;color:var(--brd);font-family:monospace">#<?= $sk['id'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="mobileNoResults" style="display:none" class="md:hidden">
        <div style="background:var(--card);border-radius:24px;border:1px solid var(--brd);padding:40px 24px;text-align:center">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:var(--brd);display:block;margin-bottom:10px"></i>
            <p style="font-weight:800;color:var(--muted)">No accounts match your search.</p>
        </div>
    </div>
</main>

<script>
let curTab = 'all';
let approveTargetId = null, rejectTargetId = null;

const allTableRows   = Array.from(document.querySelectorAll('.sk-row'));
const allMobileCards = Array.from(document.querySelectorAll('.mobile-sk-card'));

function toggleDark(){
    const isDark = document.body.classList.toggle('dark');
    const icon = document.getElementById('darkIcon');
    icon.innerHTML = isDark ? '<i class="fa-regular fa-moon"></i>' : '<i class="fa-regular fa-sun"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
}
(function initDark(){
    if(localStorage.getItem('admin_theme')==='dark'){
        document.body.classList.add('dark');
        const icon=document.getElementById('darkIcon');
        if(icon) icon.innerHTML='<i class="fa-regular fa-moon"></i>';
    }
    document.documentElement.classList.remove('dark-pre');
})();

function switchToTab(tab){
    curTab=tab;
    document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab===tab));
    document.querySelectorAll('.stat-card[onclick]').forEach(c=>{
        c.classList.toggle('ring-active',(c.getAttribute('onclick')||'').includes(`'${tab}'`));
    });
    applyFilter();
}

function applyFilter(){
    const q=document.getElementById('searchInput').value.toLowerCase().trim();
    const match=el=>{
        const mt=curTab==='all'||el.dataset.status===curTab;
        const ms=!q||el.dataset.search.includes(q);
        return mt&&ms;
    };
    let n=0;
    allTableRows.forEach(r=>{const s=match(r);r.style.display=s?'':'none';if(s)n++;});
    let m=0;
    allMobileCards.forEach(c=>{const s=match(c);c.style.display=s?'':'none';if(s)m++;});
    const total=allTableRows.length;
    document.getElementById('resultCount').textContent=`Showing ${n||m} of ${total} account${total!==1?'s':''}`;
    const tf=document.getElementById('tableFooter');if(tf)tf.textContent=`${n} result${n!==1?'s':''} displayed`;
    const mnr=document.getElementById('mobileNoResults');if(mnr)mnr.style.display=(m===0&&allMobileCards.length>0)?'block':'none';
}

const STATUS_META={
    pending:  {icon:'fa-clock',      bg:'#fef3c7',color:'#92400e',label:'Pending — Awaiting review'},
    approved: {icon:'fa-user-check', bg:'#dcfce7',color:'#166534',label:'Approved — Portal access granted'},
    rejected: {icon:'fa-user-xmark', bg:'#fee2e2',color:'#991b1b',label:'Rejected'},
};

function openDetail(d){
    const m=STATUS_META[d.status]||STATUS_META.pending;
    document.getElementById('dHero').innerHTML=`
        <div style="width:52px;height:52px;border-radius:16px;${d.avatarStyle};display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;flex-shrink:0">${d.initials}</div>
        <div style="min-width:0">
            <p style="font-weight:800;color:var(--text);font-size:16px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${d.name}</p>
            <p style="font-size:11px;color:var(--muted);font-weight:600;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${d.email}</p>
        </div>`;
    const bar=document.getElementById('dStatusBar');
    bar.style.background=m.bg; bar.style.color=m.color;
    bar.innerHTML=`<i class="fa-solid ${m.icon}"></i> <span style="font-weight:700">${m.label}</span>`;
    document.getElementById('dEmail').textContent=d.email;
    document.getElementById('dPhone').textContent=d.phone;
    document.getElementById('dDate').textContent=d.date;
    document.getElementById('dVerified').textContent=d.verified==='Yes'?'✓ Verified':'✗ Not verified';
    const acts=document.getElementById('dActions');
    if(d.status==='pending'){
        acts.innerHTML=`
            <button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
            <button onclick="triggerReject(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-reject"><i class="fa-solid fa-xmark"></i> Reject</button>`;
    }else{
        acts.innerHTML=`<button onclick="closeModal('detail')" class="btn-cancel" style="width:100%"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Close</button>`;
    }
    document.getElementById('detailModal').classList.add('open');
    document.body.style.overflow='hidden';
}

function triggerApprove(id,name){approveTargetId=id;document.getElementById('approveConfirmName').textContent=name?`"${name}"`:'';openModal('approve');}
function triggerReject(id,name){rejectTargetId=id;document.getElementById('rejectConfirmName').textContent=name?`"${name}"`:'';openModal('reject');}
function submitApprove(){
    const b=document.getElementById('confirmApproveBtn');b.disabled=true;b.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
    document.getElementById('approveId').value=approveTargetId;document.getElementById('approveForm').submit();
}
function submitReject(){
    const b=document.getElementById('confirmRejectBtn');b.disabled=true;b.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Rejecting…';
    document.getElementById('rejectId').value=rejectTargetId;document.getElementById('rejectForm').submit();
}

const overlayIds={detail:'detailModal',approve:'approveModal',reject:'rejectModal'};
function openModal(key){const el=document.getElementById(overlayIds[key]);if(el){el.classList.add('open');document.body.style.overflow='hidden';}}
function closeModal(key){
    const el=document.getElementById(overlayIds[key]);if(el){el.classList.remove('open');document.body.style.overflow='';}
    if(key==='approve'){const b=document.getElementById('confirmApproveBtn');if(b){b.disabled=false;b.innerHTML='<i class="fa-solid fa-check"></i> Approve';}}
    if(key==='reject'){const b=document.getElementById('confirmRejectBtn');if(b){b.disabled=false;b.innerHTML='<i class="fa-solid fa-xmark"></i> Reject';}}
}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal('detail');closeModal('approve');closeModal('reject');}});

applyFilter();
</script>
</body>
</html>