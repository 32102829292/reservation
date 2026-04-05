<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Login Logs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; -webkit-tap-highlight-color:transparent; }
        :root {
            --indigo:#3730a3; --indigo-mid:#4338ca; --indigo-light:#eef2ff; --indigo-border:#c7d2fe;
            --bg:#f0f2f9; --card:#ffffff; --font:'Plus Jakarta Sans',system-ui,sans-serif; --mono:'JetBrains Mono',monospace;
            --shadow-sm:0 1px 4px rgba(15,23,42,.07),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.09),0 2px 4px rgba(15,23,42,.04);
            --shadow-lg:0 12px 40px rgba(15,23,42,.12),0 4px 8px rgba(15,23,42,.06);
            --r-sm:10px; --r-md:14px; --r-lg:20px; --r-xl:24px;
            --sidebar-w:268px; --ease:.18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h:60px; --mob-nav-total:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));
        }
        html { height:100%; height:100dvh; }
        body { font-family:var(--font); background:var(--bg); color:#0f172a; display:flex; height:100vh; height:100dvh; overflow:hidden; -webkit-font-smoothing:antialiased; }

        .sidebar { width:var(--sidebar-w); flex-shrink:0; padding:18px 14px; height:100vh; height:100dvh; display:flex; flex-direction:column; }
        .sidebar-inner { background:var(--card); border-radius:var(--r-xl); border:1px solid rgba(99,102,241,.1); height:100%; display:flex; flex-direction:column; overflow:hidden; box-shadow:var(--shadow-md); }
        .sidebar-top { padding:22px 18px 16px; border-bottom:1px solid rgba(99,102,241,.07); }
        .brand-tag { font-size:.6rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:#94a3b8; margin-bottom:5px; }
        .brand-name { font-size:1.35rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1.1; }
        .brand-name em { font-style:normal; color:var(--indigo); }
        .brand-sub { font-size:.7rem; color:#94a3b8; margin-top:3px; }
        .user-card { margin:12px 12px 0; background:var(--indigo-light); border-radius:var(--r-md); padding:12px 14px; border:1px solid var(--indigo-border); display:flex; align-items:center; gap:9px; }
        .user-avatar { width:34px; height:34px; border-radius:50%; background:var(--indigo); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.85rem; flex-shrink:0; }
        .user-name-txt { font-size:.8rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .user-role-txt { font-size:.68rem; color:#6366f1; font-weight:500; margin-top:1px; }
        .sidebar-nav { flex:1; overflow-y:auto; padding:10px; display:flex; flex-direction:column; gap:3px; scrollbar-width:none; }
        .sidebar-nav::-webkit-scrollbar { display:none; }
        .nav-section-lbl { font-size:.6rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:#cbd5e1; padding:10px 10px 5px; }
        .nav-link { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:var(--r-sm); font-size:.85rem; font-weight:600; color:#64748b; text-decoration:none; transition:all var(--ease); }
        .nav-link:hover { background:var(--indigo-light); color:var(--indigo); }
        .nav-link.active { background:var(--indigo); color:#fff; box-shadow:0 4px 14px rgba(55,48,163,.32); }
        .nav-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .nav-link:not(.active) .nav-icon { background:#f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background:#e0e7ff; }
        .nav-link.active .nav-icon { background:rgba(255,255,255,.15); }
        .nav-badge { margin-left:auto; background:rgba(245,158,11,.18); color:#d97706; font-size:.6rem; font-weight:700; padding:2px 7px; border-radius:999px; }
        .sidebar-footer { padding:10px 10px 12px; border-top:1px solid rgba(99,102,241,.07); }
        .logout-link { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:var(--r-sm); font-size:.85rem; font-weight:600; color:#94a3b8; text-decoration:none; transition:all var(--ease); }
        .logout-link:hover { background:#fef2f2; color:#dc2626; }
        .logout-link:hover .nav-icon { background:#fee2e2; }

        .mobile-nav-pill { display:none; position:fixed; bottom:0; left:0; right:0; background:white; border-top:1px solid rgba(99,102,241,.1); height:var(--mob-nav-total); z-index:200; box-shadow:0 -4px 20px rgba(55,48,163,.1); }
        .mobile-scroll-container { display:flex; justify-content:space-evenly; align-items:center; height:var(--mob-nav-h); width:100%; overflow-x:auto; scrollbar-width:none; }
        .mobile-scroll-container::-webkit-scrollbar { display:none; }
        .mob-nav-item { flex:1; min-width:52px; display:flex; flex-direction:column; align-items:center; justify-content:center; height:48px; border-radius:14px; cursor:pointer; text-decoration:none; color:#64748b; position:relative; transition:background .15s, color .15s; font-size:.58rem; font-weight:600; gap:2px; }
        .mob-nav-item:hover, .mob-nav-item.active { background:var(--indigo-light); color:var(--indigo); }
        .mob-nav-item.active::after { content:''; position:absolute; bottom:4px; left:50%; transform:translateX(-50%); width:4px; height:4px; background:var(--indigo); border-radius:50%; }
        .mob-logout { color:#94a3b8; }
        .mob-logout:hover { background:#fef2f2; color:#dc2626; }
        @media(max-width:1023px) { .sidebar{display:none!important} .mobile-nav-pill{display:flex!important} .main-area{padding-bottom:calc(var(--mob-nav-total)+16px)!important} }
        @media(min-width:1024px) { .sidebar{display:flex!important} .mobile-nav-pill{display:none!important} }

        .main-area { flex:1; min-width:0; padding:24px 28px 40px; height:100vh; height:100dvh; overflow-y:auto; overflow-x:hidden; }
        @media(max-width:639px) { .main-area { padding:14px 12px 0; } }

        .topbar { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:16px; flex-wrap:wrap; }
        .page-eyebrow { font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:#94a3b8; margin-bottom:4px; }
        .page-title { font-size:1.75rem; font-weight:800; color:#0f172a; letter-spacing:-.04em; line-height:1.1; }
        .page-sub { font-size:.78rem; color:#94a3b8; margin-top:4px; font-weight:500; }
        @media(max-width:639px) { .page-title { font-size:1.35rem; } }

        .card { background:var(--card); border-radius:var(--r-lg); border:1px solid rgba(99,102,241,.08); box-shadow:var(--shadow-sm); }
        .card-p { padding:20px 22px; }

        .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px; }
        .stat-card { background:var(--card); border:1px solid rgba(99,102,241,.08); border-radius:var(--r-lg); padding:18px 20px; box-shadow:var(--shadow-sm); border-left-width:4px; transition:transform var(--ease),box-shadow var(--ease); cursor:pointer; }
        .stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
        .stat-lbl { font-size:.62rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#94a3b8; }
        .stat-num { font-size:2rem; font-weight:800; line-height:1; letter-spacing:-.04em; font-family:var(--mono); margin-top:6px; }
        @media(max-width:639px) { .stats-grid { grid-template-columns:1fr 1fr; } .stat-num { font-size:1.6rem; } }

        .filter-bar { background:var(--card); border-radius:var(--r-lg); border:1px solid rgba(99,102,241,.08); box-shadow:var(--shadow-sm); padding:16px 20px; margin-bottom:16px; }
        .search-input { width:100%; padding:10px 12px 10px 34px; border-radius:var(--r-sm); border:1px solid rgba(99,102,241,.15); font-size:.85rem; font-family:var(--font); background:#f8fafc; color:#0f172a; transition:all var(--ease); outline:none; }
        .search-input:focus { border-color:#818cf8; background:white; box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .date-input { padding:10px 12px 10px 34px; border-radius:var(--r-sm); border:1px solid rgba(99,102,241,.15); font-size:.82rem; font-family:var(--font); background:#f8fafc; color:#0f172a; transition:all var(--ease); outline:none; }
        .date-input:focus { border-color:#818cf8; background:white; box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .qtab { display:inline-flex; align-items:center; gap:6px; padding:.4rem .9rem; border-radius:var(--r-sm); font-size:.75rem; font-weight:700; transition:all var(--ease); cursor:pointer; border:1px solid rgba(99,102,241,.12); white-space:nowrap; color:#64748b; background:white; font-family:var(--font); }
        .qtab:hover { border-color:var(--indigo); color:var(--indigo); }
        .qtab.active { background:var(--indigo); color:white; border-color:var(--indigo); box-shadow:0 4px 12px rgba(55,48,163,.25); }
        .reset-btn { display:inline-flex; align-items:center; gap:5px; padding:.5rem .9rem; border-radius:var(--r-sm); font-size:.75rem; font-weight:700; background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; cursor:pointer; font-family:var(--font); transition:all var(--ease); }
        .reset-btn:hover { background:#e2e8f0; }

        .table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        table { width:100%; border-collapse:collapse; min-width:680px; }
        thead th { background:#f8fafc; font-weight:700; text-transform:uppercase; font-size:.62rem; letter-spacing:.12em; color:#94a3b8; padding:.9rem 1rem; border-bottom:1px solid rgba(99,102,241,.08); white-space:nowrap; cursor:pointer; user-select:none; }
        thead th:hover { color:var(--indigo); }
        thead th.sorted .sort-icon { color:var(--indigo); opacity:1; }
        td { padding:.875rem 1rem; border-bottom:1px solid #f8fafc; vertical-align:middle; }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr { transition:background var(--ease); cursor:pointer; }
        tbody tr:hover td { background:var(--indigo-light); }
        .sort-icon { opacity:.3; margin-left:4px; font-size:.6rem; }

        .tag { display:inline-flex; align-items:center; gap:3px; padding:3px 9px; border-radius:999px; font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; flex-shrink:0; white-space:nowrap; }
        .tag-active   { background:#dcfce7; color:#166534; }
        .tag-closed   { background:#f1f5f9; color:#475569; }
        .tag-user     { background:var(--indigo-light); color:var(--indigo); }
        .tag-sk       { background:#ede9fe; color:#5b21b6; }
        .tag-admin    { background:#fef3c7; color:#92400e; }
        .tag-chairman { background:#fef3c7; color:#92400e; }

        .log-avatar { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.82rem; flex-shrink:0; }

        .dur-bar { height:4px; border-radius:2px; background:#e2e8f0; overflow:hidden; margin-top:4px; }
        .dur-fill { height:100%; border-radius:2px; background:var(--indigo); }

        .log-card { background:var(--card); border:1px solid rgba(99,102,241,.08); border-radius:var(--r-lg); padding:16px 18px; cursor:pointer; transition:all var(--ease); box-shadow:var(--shadow-sm); }
        .log-card:hover { box-shadow:var(--shadow-md); border-color:var(--indigo-border); }
        .log-card.active-session { border-left:4px solid #10b981; }
        .log-card.closed-session { border-left:4px solid rgba(99,102,241,.2); }

        .section-label { font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:#94a3b8; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .section-label::before { content:''; display:inline-block; width:3px; height:14px; border-radius:2px; background:var(--indigo); flex-shrink:0; }

        .modal-back { display:none; position:fixed; inset:0; background:rgba(15,23,42,.52); backdrop-filter:blur(6px); z-index:300; padding:1.5rem; overflow-y:auto; align-items:center; justify-content:center; }
        .modal-back.show { display:flex; animation:fadeIn .15s ease; }
        .modal-card { background:white; border-radius:var(--r-xl); width:100%; max-width:460px; max-height:92dvh; overflow-y:auto; margin:auto; animation:slideUp .22s cubic-bezier(.34,1.56,.64,1) both; box-shadow:var(--shadow-lg); }
        @media(max-width:479px) { .modal-back{padding:0;align-items:flex-end} .modal-card{border-radius:var(--r-xl) var(--r-xl) 0 0;max-width:100%;} .sheet-handle{display:block!important} }
        .sheet-handle { display:none; width:40px; height:4px; background:#e2e8f0; border-radius:9999px; margin:10px auto 4px; }
        .detail-icon { width:36px; height:36px; border-radius:10px; background:var(--indigo-light); color:var(--indigo); display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
        .detail-row { display:flex; align-items:flex-start; gap:12px; padding:.65rem 0; border-bottom:1px solid #f1f5f9; }
        .detail-row:last-child { border-bottom:none; }
        .detail-label { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:3px; }
        .detail-value { font-size:.88rem; font-weight:700; color:#0f172a; }

        .icon-btn { width:44px; height:44px; background:white; border:1px solid rgba(99,102,241,.12); border-radius:var(--r-sm); display:flex; align-items:center; justify-content:center; color:#64748b; cursor:pointer; transition:all var(--ease); box-shadow:var(--shadow-sm); }
        .icon-btn:hover { background:var(--indigo-light); border-color:var(--indigo-border); color:var(--indigo); }

        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
        @keyframes slideUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
        .fade-up   { animation:slideUp .4s ease both; }
        .fade-up-1 { animation:slideUp .45s .05s ease both; }
    
        body.dark { --bg:#060e1e; --card:#0b1628; --indigo-light:rgba(55,48,163,.12); --indigo-border:rgba(99,102,241,.25); color:#e2eaf8; }
        body.dark .sidebar-inner { background:#0b1628; border-color:rgba(99,102,241,.12); }
        body.dark .sidebar-top, body.dark .sidebar-footer { border-color:rgba(99,102,241,.1); }
        body.dark .brand-name { color:#e2eaf8; }
        body.dark .brand-tag, body.dark .brand-sub { color:#4a6fa5; }
        body.dark .nav-section-lbl { color:#1e3a5f; }
        body.dark .nav-link { color:#7fb3e8; }
        body.dark .nav-link:hover { background:rgba(99,102,241,.12); color:#a5b4fc; }
        body.dark .nav-link:not(.active) .nav-icon { background:rgba(99,102,241,.1); }
        body.dark .user-card { background:rgba(55,48,163,.15); border-color:rgba(99,102,241,.2); }
        body.dark .user-name-txt { color:#e2eaf8; }
        body.dark .mobile-nav-pill { background:#0b1628; border-color:rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color:#7fb3e8; }
        body.dark .mob-nav-item.active { background:rgba(99,102,241,.18); }
        body.dark .page-title { color:#e2eaf8; }
        body.dark .page-eyebrow, body.dark .page-sub { color:#4a6fa5; }
        body.dark .icon-btn { background:#0b1628; border-color:rgba(99,102,241,.15); color:#7fb3e8; }
        body.dark .card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .stat-card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .stat-num { color:#e2eaf8; }
        body.dark .stat-lbl { color:#4a6fa5; }
        body.dark .filter-bar { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .search-input, body.dark .date-input { background:#101e35; border-color:rgba(99,102,241,.18); color:#e2eaf8; }
        body.dark .search-input:focus, body.dark .date-input:focus { background:#0b1628; border-color:#818cf8; }
        body.dark .qtab { background:#0b1628; border-color:rgba(99,102,241,.1); color:#7fb3e8; }
        body.dark .qtab.active { background:var(--indigo); color:white; }
        body.dark .reset-btn { background:#101e35; color:#7fb3e8; border-color:rgba(99,102,241,.1); }
        body.dark thead th { background:#101e35; color:#4a6fa5; border-color:rgba(99,102,241,.08); }
        body.dark td { border-color:#101e35; }
        body.dark tbody tr:hover td { background:rgba(99,102,241,.06); }
        body.dark .log-card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .log-card:hover { border-color:rgba(99,102,241,.3); }
        body.dark .section-label { color:#4a6fa5; }
        body.dark .dur-bar { background:rgba(99,102,241,.15); }
        body.dark .modal-card { background:#0b1628; }
        body.dark .detail-row { border-color:#101e35; }
        body.dark .detail-label { color:#4a6fa5; }
        body.dark .detail-value { color:#e2eaf8; }
        body.dark .detail-icon { background:rgba(55,48,163,.2); color:#818cf8; }
        body.dark .logout-link { color:#4a6fa5; }
        body.dark .logout-link:hover { background:rgba(239,68,68,.1); color:#f87171; }
        body.dark .sheet-handle { background:#1a2a42; }
    </style>
</head>
<body>

<?php
date_default_timezone_set('Asia/Manila');
$page = 'login-logs';
$avatarLetter = strtoupper(mb_substr(trim($user_name ?? 'A'), 0, 1));
$navItems = [
    ['url'=>'/admin/dashboard',           'icon'=>'fa-house',       'label'=>'Dashboard',       'key'=>'dashboard'],
    ['url'=>'/admin/new-reservation',     'icon'=>'fa-plus',        'label'=>'New Reservation', 'key'=>'new-reservation'],
    ['url'=>'/admin/manage-reservations', 'icon'=>'fa-calendar',    'label'=>'Reservations',    'key'=>'manage-reservations'],
    ['url'=>'/admin/manage-pcs',          'icon'=>'fa-desktop',     'label'=>'Manage PCs',      'key'=>'manage-pcs'],
    ['url'=>'/admin/manage-sk',           'icon'=>'fa-user-shield', 'label'=>'Manage SK',       'key'=>'manage-sk'],
    ['url'=>'/admin/books',               'icon'=>'fa-book-open',   'label'=>'Library',         'key'=>'books'],
    ['url'=>'/admin/login-logs',          'icon'=>'fa-clock',       'label'=>'Login Logs',      'key'=>'login-logs'],
    ['url'=>'/admin/scanner',             'icon'=>'fa-qrcode',      'label'=>'Scanner',         'key'=>'scanner'],
    ['url'=>'/admin/activity-logs',       'icon'=>'fa-list',        'label'=>'Activity Logs',   'key'=>'activity-logs'],
    ['url'=>'/admin/profile',             'icon'=>'fa-user',        'label'=>'Profile',         'key'=>'profile'],
];

/* ── Pre-process logs ── */
$processed = []; $totalSessions = 0; $activeSessions = 0; $maxDuration = 1;
foreach (($logs ?? []) as $log) {
    $loginDt  = new DateTime($log['login_time']);
    $logoutDt = !empty($log['logout_time']) ? new DateTime($log['logout_time']) : null;
    $isActive = ($logoutDt === null);
    $dur = 0; $durLabel = '—';
    if ($logoutDt) {
        $diff = $loginDt->diff($logoutDt);
        $dur  = $diff->h * 60 + $diff->i + ($diff->d ? $diff->d * 1440 : 0);
        $durLabel = ($diff->d ? $diff->d.'d ' : '').($diff->h ? $diff->h.'h ' : '').$diff->i.'m';
    } elseif ($isActive) { $durLabel = 'Active now'; }
    if ($dur > $maxDuration) $maxDuration = $dur;
    $role = strtolower($log['role'] ?? 'user');
    $processed[] = array_merge($log, ['_login'=>$loginDt,'_logout'=>$logoutDt,'_active'=>$isActive,'_dur'=>$dur,'_durLabel'=>$durLabel,'_role'=>$role]);
    $totalSessions++;
    if ($isActive) $activeSessions++;
}
$closedSessions = $totalSessions - $activeSessions;
$avatarColors = ['bg-indigo-100 text-indigo-700','bg-purple-100 text-purple-700','bg-emerald-100 text-emerald-700','bg-rose-100 text-rose-700','bg-amber-100 text-amber-700'];
$roleIcons = ['user'=>'fa-user','sk'=>'fa-user-shield','admin'=>'fa-crown','chairman'=>'fa-crown'];
?>

<!-- ════ DETAIL MODAL ════ -->
<div id="detailModal" class="modal-back" onclick="if(event.target===this)closeDetail()">
    <div class="modal-card">
        <div style="padding:14px 20px 0;"><div class="sheet-handle"></div></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:12px 20px 14px;">
            <div>
                <div style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:3px;">Login Logs</div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;">Session Details</h3>
            </div>
            <button onclick="closeDetail()" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button>
        </div>
        <div id="dHero" style="margin:0 20px 12px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-md);padding:14px;display:flex;align-items:center;gap:12px;"></div>
        <div id="dStatusBar" style="margin:0 20px 14px;border-radius:var(--r-sm);padding:10px 14px;display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:700;"></div>
        <div style="padding:0 20px 4px;">
            <div class="detail-row"><div class="detail-icon"><i class="fa-solid fa-envelope"></i></div><div><div class="detail-label">Email</div><div id="dEmail" class="detail-value"></div></div></div>
            <div class="detail-row"><div class="detail-icon"><i class="fa-solid fa-shield-halved"></i></div><div><div class="detail-label">Role</div><div id="dRole" class="detail-value"></div></div></div>
            <div class="detail-row"><div class="detail-icon"><i class="fa-solid fa-right-to-bracket"></i></div><div><div class="detail-label">Login Time</div><div id="dLogin" class="detail-value"></div></div></div>
            <div class="detail-row"><div class="detail-icon"><i class="fa-solid fa-right-from-bracket"></i></div><div><div class="detail-label">Logout Time</div><div id="dLogout" class="detail-value"></div></div></div>
            <div class="detail-row"><div class="detail-icon"><i class="fa-regular fa-clock"></i></div><div><div class="detail-label">Duration</div><div id="dDur" class="detail-value"></div></div></div>
        </div>
        <div style="padding:14px 20px 20px;border-top:1px solid rgba(99,102,241,.07);margin-top:8px;">
            <button onclick="closeDetail()" style="width:100%;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);">Close</button>
        </div>
    </div>
</div>

<!-- ════ SIDEBAR ════ -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-top">
            <div class="brand-tag">Admin Portal</div>
            <div class="brand-name">my<em>Space.</em></div>
            <div class="brand-sub">Control Room</div>
        </div>
        <div class="user-card">
            <div class="user-avatar"><?= $avatarLetter ?></div>
            <div style="min-width:0;"><div class="user-name-txt"><?= htmlspecialchars($user_name ?? 'Admin') ?></div><div class="user-role-txt">Administrator</div></div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-lbl">Menu</div>
            <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active?'active':'' ?>">
                    <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem;"></i></div>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08);"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171;"></i></div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- ════ MOBILE NAV ════ -->
<nav class="mobile-nav-pill">
    <div class="mobile-scroll-container">
        <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active?'active':'' ?>" title="<?= $item['label'] ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1rem;"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item mob-logout" title="Logout">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1rem;color:#f87171;"></i>
        </a>
    </div>
</nav>

<!-- ════ MAIN ════ -->
<main class="main-area">

    <div class="topbar fade-up">
        <div>
            <div class="page-eyebrow">Admin Portal</div>
            <div class="page-title">Login Logs</div>
            <div class="page-sub">Authentication history &amp; session tracking</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap;">
            <?php if ($activeSessions > 0): ?>
                <div style="display:flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #86efac;color:#166534;padding:8px 14px;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;">
                    <span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;animation:livePulse 1.5s infinite;"></span>
                    <?= $activeSessions ?> active session<?= $activeSessions > 1 ? 's' : '' ?>
                </div>
            <?php endif; ?>
            <div class="icon-btn" onclick="toggleDark()" title="Toggle dark mode">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
            </div>
            <div style="background:var(--card);border:1px solid rgba(99,102,241,.1);border-radius:var(--r-sm);padding:8px 14px;display:flex;align-items:center;gap:6px;">
                <i class="fa-regular fa-calendar" style="color:var(--indigo);font-size:.78rem;"></i>
                <span style="font-size:.78rem;font-weight:700;color:#0f172a;"><?= date('M j, Y') ?></span>
            </div>
        </div>
    </div>

    <p class="section-label fade-up-1">Overview</p>
    <div class="stats-grid fade-up-1">
        <div class="stat-card" style="border-left-color:var(--indigo);" onclick="setFilter('all')">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;"><div class="stat-lbl">Total Sessions</div><i class="fa-solid fa-list" style="color:var(--indigo);font-size:.85rem;"></i></div>
            <div class="stat-num" style="color:var(--indigo);"><?= $totalSessions ?></div>
        </div>
        <div class="stat-card" style="border-left-color:#16a34a;" onclick="setFilter('active')">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;"><div class="stat-lbl">Active Now</div><span style="width:8px;height:8px;background:#10b981;border-radius:50%;"></span></div>
            <div class="stat-num" style="color:#16a34a;"><?= $activeSessions ?></div>
        </div>
        <div class="stat-card" style="border-left-color:#94a3b8;" onclick="setFilter('closed')">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;"><div class="stat-lbl">Closed</div><i class="fa-solid fa-circle-xmark" style="color:#94a3b8;font-size:.85rem;"></i></div>
            <div class="stat-num" style="color:#94a3b8;"><?= $closedSessions ?></div>
        </div>
    </div>

    <div class="filter-bar fade-up-1">
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:12px;">
            <div style="position:relative;flex:1;min-width:180px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.72rem;pointer-events:none;"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email, or role…" oninput="applyFilters()">
            </div>
            <div style="position:relative;">
                <i class="fa-regular fa-calendar" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.72rem;pointer-events:none;"></i>
                <input type="date" id="dateInput" class="date-input" onchange="applyFilters()">
            </div>
            <button class="reset-btn" onclick="clearFilters()"><i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Reset</button>
        </div>
        <div style="display:flex;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:2px;">
            <button class="qtab active" data-tab="all"      onclick="setFilter('all')"><i class="fa-solid fa-list" style="font-size:.7rem;"></i> All <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $totalSessions ?></span></button>
            <button class="qtab" data-tab="active"          onclick="setFilter('active')"><i class="fa-solid fa-circle" style="font-size:.55rem;color:#10b981;"></i> Active<?php if($activeSessions > 0): ?><span style="background:#10b981;color:white;font-size:.55rem;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $activeSessions ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="closed"          onclick="setFilter('closed')"><i class="fa-solid fa-circle-xmark" style="font-size:.7rem;"></i> Closed</button>
            <button class="qtab" data-tab="user"            onclick="setFilter('user')"><i class="fa-solid fa-user" style="font-size:.7rem;"></i> Users</button>
            <button class="qtab" data-tab="sk"              onclick="setFilter('sk')"><i class="fa-solid fa-user-shield" style="font-size:.7rem;"></i> SK</button>
            <button class="qtab" data-tab="chairman"        onclick="setFilter('chairman')"><i class="fa-solid fa-crown" style="font-size:.7rem;"></i> Chairman</button>
        </div>
        <p id="resultCount" style="font-size:.65rem;font-weight:700;color:#94a3b8;margin-top:10px;"></p>
    </div>

    <!-- Desktop Table -->
    <p class="section-label fade-up-1">Session Records</p>
    <div class="card fade-up-1" id="desktopTable">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">User <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(1)">Role <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(2)">Login Time <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(3)">Logout Time <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Duration</th>
                        <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    <?php if (empty($processed)): ?>
                        <tr><td colspan="6">
                            <div style="text-align:center;padding:48px 20px;">
                                <i class="fa-solid fa-clock" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                <p style="font-weight:800;color:#94a3b8;">No login logs yet</p>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($processed as $i => $log):
                            $col    = $avatarColors[$i % count($avatarColors)];
                            $init   = strtoupper(substr($log['name'] ?? '?', 0, 2));
                            $name   = htmlspecialchars($log['name'] ?? '—');
                            $email  = htmlspecialchars($log['email'] ?? '—');
                            $role   = $log['_role'];
                            $loginF = $log['_login']->format('M j, Y · g:i A');
                            $logoutF= $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                            $rawDate= $log['_login']->format('Y-m-d');
                            $durPct = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                            $mdata  = json_encode(['name'=>$name,'email'=>$email,'role'=>$role,'login'=>$loginF,'logout'=>$logoutF,'dur'=>$log['_durLabel'],'active'=>$log['_active'],'color'=>$col,'initials'=>$init]);
                        ?>
                            <tr class="log-row"
                                data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                                data-role="<?= $role ?>"
                                data-search="<?= strtolower("$name $email $role") ?>"
                                data-date="<?= $rawDate ?>"
                                onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="log-avatar <?= $col ?>"><?= $init ?></div>
                                        <div>
                                            <p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $name ?></p>
                                            <p style="font-size:.7rem;color:#94a3b8;margin-top:1px;"><?= $email ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="tag tag-<?= $role ?>"><i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?>" style="font-size:.55rem;"></i><?= ucfirst($role) ?></span></td>
                                <td>
                                    <p style="font-size:.85rem;font-weight:700;color:#0f172a;"><?= $log['_login']->format('M j, Y') ?></p>
                                    <p style="font-size:.7rem;color:#6366f1;font-family:var(--mono);margin-top:1px;"><?= $log['_login']->format('g:i A') ?></p>
                                </td>
                                <td>
                                    <?php if ($log['_logout']): ?>
                                        <p style="font-size:.85rem;font-weight:700;color:#0f172a;"><?= $log['_logout']->format('M j, Y') ?></p>
                                        <p style="font-size:.7rem;color:#94a3b8;font-family:var(--mono);margin-top:1px;"><?= $log['_logout']->format('g:i A') ?></p>
                                    <?php else: ?>
                                        <span style="font-size:.75rem;color:#10b981;font-weight:700;font-style:italic;">Still active</span>
                                    <?php endif; ?>
                                </td>
                                <td style="min-width:90px;">
                                    <p style="font-size:.78rem;font-weight:700;color:#0f172a;"><?= $log['_durLabel'] ?></p>
                                    <?php if ($log['_dur'] > 0): ?>
                                        <div class="dur-bar" style="width:80px;"><div class="dur-fill" style="width:<?= $durPct ?>%;"></div></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($log['_active']): ?>
                                        <span class="tag tag-active"><span style="width:5px;height:5px;background:#10b981;border-radius:50%;display:inline-block;"></span>Active</span>
                                    <?php else: ?>
                                        <span class="tag tag-closed"><i class="fa-solid fa-circle-xmark" style="font-size:.55rem;"></i>Closed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;border-top:1px solid rgba(99,102,241,.06);background:#f8fafc;display:flex;align-items:center;justify-content:space-between;">
            <p id="tableFooter" style="font-size:.65rem;font-weight:700;color:#94a3b8;"></p>
            <p style="font-size:.65rem;color:#cbd5e1;font-weight:600;">Click any row for session details</p>
        </div>
    </div>

    <!-- Mobile Cards -->
    <?php if (!empty($processed)): ?>
    <div id="logCardList" style="display:flex;flex-direction:column;gap:10px;margin-top:16px;" class="fade-up-1">
        <?php foreach ($processed as $i => $log):
            $col    = $avatarColors[$i % count($avatarColors)];
            $init   = strtoupper(substr($log['name'] ?? '?', 0, 2));
            $name   = htmlspecialchars($log['name'] ?? '—');
            $email  = htmlspecialchars($log['email'] ?? '—');
            $role   = $log['_role'];
            $loginF = $log['_login']->format('M j, Y · g:i A');
            $logoutF= $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
            $rawDate= $log['_login']->format('Y-m-d');
            $durPct = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
            $mdata  = json_encode(['name'=>$name,'email'=>$email,'role'=>$role,'login'=>$loginF,'logout'=>$logoutF,'dur'=>$log['_durLabel'],'active'=>$log['_active'],'color'=>$col,'initials'=>$init]);
        ?>
            <div class="log-card <?= $log['_active'] ? 'active-session' : 'closed-session' ?>"
                 data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                 data-role="<?= $role ?>"
                 data-search="<?= strtolower("$name $email $role") ?>"
                 data-date="<?= $rawDate ?>"
                 onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <div class="log-avatar <?= $col ?>"><?= $init ?></div>
                        <div style="min-width:0;">
                            <p style="font-weight:700;font-size:.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $name ?></p>
                            <p style="font-size:.7rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $email ?></p>
                        </div>
                    </div>
                    <?php if ($log['_active']): ?>
                        <span class="tag tag-active flex-shrink-0"><span style="width:5px;height:5px;background:#10b981;border-radius:50%;display:inline-block;"></span>Active</span>
                    <?php else: ?>
                        <span class="tag tag-closed flex-shrink-0"><i class="fa-solid fa-circle-xmark" style="font-size:.55rem;"></i>Closed</span>
                    <?php endif; ?>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px;">
                    <span class="tag tag-<?= $role ?>"><i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?>" style="font-size:.55rem;"></i><?= ucfirst($role) ?></span>
                    <span style="font-size:.72rem;color:#94a3b8;font-weight:500;"><i class="fa-solid fa-right-to-bracket" style="font-size:.65rem;color:var(--indigo);margin-right:4px;"></i><?= $log['_login']->format('M j · g:i A') ?></span>
                    <?php if ($log['_logout']): ?><span style="font-size:.72rem;color:#94a3b8;font-weight:500;"><i class="fa-solid fa-right-from-bracket" style="font-size:.65rem;margin-right:4px;"></i><?= $log['_logout']->format('g:i A') ?></span><?php endif; ?>
                </div>
                <p style="font-size:.72rem;font-weight:700;color:#0f172a;margin-bottom:5px;"><?= $log['_durLabel'] ?></p>
                <?php if ($log['_dur'] > 0): ?><div class="dur-bar"><div class="dur-fill" style="width:<?= $durPct ?>%;"></div></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div id="noResultsMsg" class="hidden" style="text-align:center;padding:40px 20px;background:var(--card);border-radius:var(--r-lg);border:1px solid rgba(99,102,241,.08);margin-top:16px;">
        <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
        <p style="font-size:.85rem;font-weight:700;color:#94a3b8;">No sessions match your search.</p>
    </div>

</main>

<style>
@keyframes livePulse { 0%,100%{opacity:1} 50%{opacity:.5} }
@media(min-width:768px) { #logCardList{display:none!important} }
@media(max-width:767px) { #desktopTable{display:none!important} }
</style>

<script>
let curFilter = 'all';

function toggleDark(){
    const isDark = document.body.classList.toggle('dark');
    document.getElementById('darkIcon').innerHTML = isDark ? '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>' : '<i class="fa-regular fa-sun" style="font-size:.85rem;"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
}
(function(){
    if(localStorage.getItem('admin_theme') === 'dark'){
        document.body.classList.add('dark');
        document.getElementById('darkIcon').innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    }
})();

function setFilter(f) {
    curFilter = f;
    document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === f));
    applyFilters();
}
function applyFilters() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const date = document.getElementById('dateInput').value;
    const tableRows = document.querySelectorAll('#logTableBody .log-row');
    const cards = document.querySelectorAll('#logCardList .log-card');
    let n = 0;
    const match = el => {
        const mf = curFilter === 'all' || (curFilter === 'active' && el.dataset.status === 'active') || (curFilter === 'closed' && el.dataset.status === 'closed') || el.dataset.role === curFilter;
        return mf && (!q || el.dataset.search.includes(q)) && (!date || el.dataset.date === date);
    };
    tableRows.forEach(r => { const s = match(r); r.style.display = s ? '' : 'none'; if(s) n++; });
    cards.forEach(c => { c.style.display = match(c) ? '' : 'none'; });
    const total = tableRows.length;
    document.getElementById('resultCount').textContent = `Showing ${n} of ${total} session${total !== 1 ? 's' : ''}`;
    document.getElementById('tableFooter').textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
    document.getElementById('noResultsMsg').classList.toggle('hidden', n > 0);
}
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('dateInput').value = '';
    setFilter('all');
}

let sortDir = {};
function sortTable(col) {
    sortDir[col] = !sortDir[col];
    const tbody = document.getElementById('logTableBody');
    Array.from(tbody.querySelectorAll('.log-row')).sort((a,b) => {
        const at = (a.cells[col]?.innerText ?? '').trim().toLowerCase();
        const bt = (b.cells[col]?.innerText ?? '').trim().toLowerCase();
        return sortDir[col] ? at.localeCompare(bt) : bt.localeCompare(at);
    }).forEach(r => tbody.appendChild(r));
    document.querySelectorAll('thead th').forEach((th, i) => {
        th.classList.toggle('sorted', i === col);
        const ic = th.querySelector('.sort-icon');
        if(ic) ic.className = `fa-solid ${i === col ? (sortDir[col] ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'} sort-icon`;
    });
}

function openDetail(d) {
    const colors = { 'bg-indigo-100 text-indigo-700':'background:#eef2ff;color:#3730a3', 'bg-purple-100 text-purple-700':'background:#f3e8ff;color:#6b21a8', 'bg-emerald-100 text-emerald-700':'background:#dcfce7;color:#166534', 'bg-rose-100 text-rose-700':'background:#fee2e2;color:#991b1b', 'bg-amber-100 text-amber-700':'background:#fef3c7;color:#92400e' };
    const cs = colors[d.color] || 'background:#eef2ff;color:#3730a3';
    document.getElementById('dHero').innerHTML = `<div style="width:44px;height:44px;border-radius:13px;${cs};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;">${d.initials}</div><div><p style="font-weight:800;font-size:1rem;color:#0f172a;">${d.name}</p><p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">${d.email}</p></div>`;
    const bar = document.getElementById('dStatusBar');
    if(d.active) { bar.style.background='#dcfce7'; bar.style.color='#166534'; bar.innerHTML='<span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;"></span> Session currently active'; }
    else { bar.style.background='#f1f5f9'; bar.style.color='#64748b'; bar.innerHTML='<i class="fa-solid fa-circle-xmark" style="font-size:.75rem;"></i> Session closed'; }
    document.getElementById('dEmail').textContent  = d.email;
    document.getElementById('dRole').textContent   = d.role.charAt(0).toUpperCase() + d.role.slice(1);
    document.getElementById('dLogin').textContent  = d.login;
    document.getElementById('dLogout').textContent = d.logout;
    document.getElementById('dDur').textContent    = d.dur;
    document.getElementById('detailModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeDetail() { document.getElementById('detailModal').classList.remove('show'); document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeDetail(); });

applyFilters();
</script>
</body>
</html>