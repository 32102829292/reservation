<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage PCs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
        :root {
            --indigo:        #3730a3;
            --indigo-mid:    #4338ca;
            --indigo-light:  #eef2ff;
            --indigo-border: #c7d2fe;
            --bg:            #f0f2f9;
            --card:          #ffffff;
            --font:          'Plus Jakarta Sans', system-ui, sans-serif;
            --mono:          'JetBrains Mono', monospace;
            --shadow-sm:     0 1px 4px rgba(15,23,42,.07), 0 1px 2px rgba(15,23,42,.04);
            --shadow-md:     0 4px 16px rgba(15,23,42,.09), 0 2px 4px rgba(15,23,42,.04);
            --shadow-lg:     0 12px 40px rgba(15,23,42,.12), 0 4px 8px rgba(15,23,42,.06);
            --r-sm:  10px; --r-md: 14px; --r-lg: 20px; --r-xl: 24px;
            --sidebar-w: 268px;
            --ease: .18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }
        html { height: 100%; height: 100dvh; }
        body { font-family: var(--font); background: var(--bg); color: #0f172a; display: flex; height: 100vh; height: 100dvh; overflow: hidden; -webkit-font-smoothing: antialiased; }

        /* ── Sidebar ── */
        .sidebar { width: var(--sidebar-w); flex-shrink: 0; padding: 18px 14px; height: 100vh; height: 100dvh; display: flex; flex-direction: column; }
        .sidebar-inner { background: var(--card); border-radius: var(--r-xl); border: 1px solid rgba(99,102,241,.1); height: 100%; display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-md); }
        .sidebar-top { padding: 22px 18px 16px; border-bottom: 1px solid rgba(99,102,241,.07); }
        .brand-tag { font-size: .6rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px; }
        .brand-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.03em; line-height: 1.1; }
        .brand-name em { font-style: normal; color: var(--indigo); }
        .brand-sub { font-size: .7rem; color: #94a3b8; margin-top: 3px; }
        .user-card { margin: 12px 12px 0; background: var(--indigo-light); border-radius: var(--r-md); padding: 12px 14px; border: 1px solid var(--indigo-border); display: flex; align-items: center; gap: 9px; }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--indigo); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; }
        .user-name-txt { font-size: .8rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role-txt { font-size: .68rem; color: #6366f1; font-weight: 500; margin-top: 1px; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 3px; scrollbar-width: none; }
        .sidebar-nav::-webkit-scrollbar { display: none; }
        .nav-section-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #cbd5e1; padding: 10px 10px 5px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #64748b; text-decoration: none; transition: all var(--ease); }
        .nav-link:hover { background: var(--indigo-light); color: var(--indigo); }
        .nav-link.active { background: var(--indigo); color: #fff; box-shadow: 0 4px 14px rgba(55,48,163,.32); }
        .nav-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-link:not(.active) .nav-icon { background: #f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background: #e0e7ff; }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.15); }
        .nav-badge { margin-left: auto; background: rgba(245,158,11,.18); color: #d97706; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; }
        .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07); }
        .logout-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all var(--ease); }
        .logout-link:hover { background: #fef2f2; color: #dc2626; }
        .logout-link:hover .nav-icon { background: #fee2e2; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid rgba(99,102,241,.1); height: var(--mob-nav-total); z-index: 200; box-shadow: 0 -4px 20px rgba(55,48,163,.1); }
        .mobile-scroll-container { display: flex; justify-content: space-evenly; align-items: center; height: var(--mob-nav-h); width: 100%; overflow-x: auto; scrollbar-width: none; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }
        .mob-nav-item { flex: 1; min-width: 52px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 48px; border-radius: 14px; cursor: pointer; text-decoration: none; color: #64748b; position: relative; transition: background .15s, color .15s; font-size: .58rem; font-weight: 600; gap: 2px; }
        .mob-nav-item:hover, .mob-nav-item.active { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active::after { content:''; position:absolute; bottom:4px; left:50%; transform:translateX(-50%); width:4px; height:4px; background:var(--indigo); border-radius:50%; }
        .mob-badge { position:absolute; top:6px; right:15%; background:#ef4444; color:white; font-size:.5rem; font-weight:700; width:14px; height:14px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid white; }
        .mob-logout { color:#94a3b8; }
        .mob-logout:hover { background:#fef2f2; color:#dc2626; }
        @media(max-width:1023px) { .sidebar{display:none!important} .mobile-nav-pill{display:flex!important} .main-area{padding-bottom:calc(var(--mob-nav-total)+16px)!important} }
        @media(min-width:1024px) { .sidebar{display:flex!important} .mobile-nav-pill{display:none!important} }

        /* ── Main ── */
        .main-area { flex: 1; min-width: 0; padding: 24px 28px 40px; height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; }
        @media(max-width:639px) { .main-area { padding: 14px 12px 0; } }

        /* ── Topbar ── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .page-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .page-title { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .page-sub { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; }
        @media(max-width:639px) { .page-title { font-size: 1.35rem; } }

        /* ── Cards ── */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }

        /* ── Stat cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 18px 20px; box-shadow: var(--shadow-sm); border-left-width: 4px; transition: transform var(--ease), box-shadow var(--ease); cursor: pointer; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8; }
        .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; font-family: var(--mono); margin-top: 6px; }
        @media(max-width:639px) { .stats-grid { grid-template-columns: 1fr 1fr; } .stat-num { font-size: 1.6rem; } }

        /* ── Filter bar ── */
        .filter-bar { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); padding: 16px 20px; margin-bottom: 16px; }
        .search-input { width: 100%; padding: 10px 12px 10px 34px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .85rem; font-family: var(--font); background: #f8fafc; color: #0f172a; transition: all var(--ease); outline: none; }
        .search-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .qtab { display: inline-flex; align-items: center; gap: 6px; padding: .4rem .9rem; border-radius: var(--r-sm); font-size: .75rem; font-weight: 700; transition: all var(--ease); cursor: pointer; border: 1px solid rgba(99,102,241,.12); white-space: nowrap; color: #64748b; background: white; font-family: var(--font); }
        .qtab:hover { border-color: var(--indigo); color: var(--indigo); }
        .qtab.active { background: var(--indigo); color: white; border-color: var(--indigo); box-shadow: 0 4px 12px rgba(55,48,163,.25); }
        .reset-btn { display: inline-flex; align-items: center; gap: 5px; padding: .5rem .9rem; border-radius: var(--r-sm); font-size: .75rem; font-weight: 700; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; cursor: pointer; font-family: var(--font); transition: all var(--ease); }
        .reset-btn:hover { background: #e2e8f0; }

        /* ── PC grid cards ── */
        .pc-card { background: var(--card); border-radius: var(--r-lg); padding: 20px; border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); transition: all var(--ease); display: flex; flex-direction: column; }
        .pc-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); border-color: var(--indigo-border); }
        .pc-card.available-card   { border-top: 3px solid #22c55e; }
        .pc-card.maintenance-card { border-top: 3px solid #f59e0b; }

        /* ── PC list cards (mobile) ── */
        .pc-list-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 16px 18px; box-shadow: var(--shadow-sm); }
        .pc-list-card.available-card   { border-left: 4px solid #22c55e; }
        .pc-list-card.maintenance-card { border-left: 4px solid #f59e0b; }

        /* ── Tags ── */
        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
        .tag-available   { background: #dcfce7; color: #166534; }
        .tag-maintenance { background: #fef3c7; color: #92400e; }

        /* ── Prog bar ── */
        .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; }

        /* ── Action buttons ── */
        .btn-primary { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--indigo); color: #fff; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); box-shadow: 0 4px 12px rgba(55,48,163,.28); }
        .btn-primary:hover { background: #312e81; transform: translateY(-1px); }
        .btn-toggle-to-maint { display: inline-flex; align-items: center; justify-content: center; gap: 5px; padding: .5rem .85rem; border-radius: 9px; font-size: .72rem; font-weight: 700; background: #fef3c7; color: #92400e; border: 1.5px solid #fde68a; cursor: pointer; font-family: var(--font); transition: all var(--ease); flex: 1; }
        .btn-toggle-to-maint:hover { background: #fde68a; }
        .btn-toggle-to-avail { display: inline-flex; align-items: center; justify-content: center; gap: 5px; padding: .5rem .85rem; border-radius: 9px; font-size: .72rem; font-weight: 700; background: #dcfce7; color: #166534; border: 1.5px solid #86efac; cursor: pointer; font-family: var(--font); transition: all var(--ease); flex: 1; }
        .btn-toggle-to-avail:hover { background: #bbf7d0; }
        .btn-delete-sm { width: 36px; height: 36px; border-radius: 9px; background: #fef2f2; color: #ef4444; border: 1.5px solid #fecaca; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all var(--ease); flex-shrink: 0; }
        .btn-delete-sm:hover { background: #fee2e2; border-color: #f87171; }

        /* ── Modal ── */
        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 440px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .22s cubic-bezier(.34,1.56,.64,1) both; box-shadow: var(--shadow-lg); }
        .modal-card.sm { max-width: 360px; }
        @media(max-width:479px) { .modal-back { padding: 0; align-items: flex-end; } .modal-card { border-radius: var(--r-xl) var(--r-xl) 0 0; max-height: 92dvh; } .sheet-handle { display: block !important; } }
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 4px; }
        .field-input { width: 100%; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: var(--r-md); padding: 12px 14px 12px 40px; font-size: .9rem; font-family: var(--font); color: #0f172a; transition: all .2s; outline: none; }
        .field-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .field-input-plain { width: 100%; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: var(--r-md); padding: 12px 14px; font-size: .9rem; font-family: var(--font); color: #0f172a; transition: all .2s; outline: none; -webkit-appearance: none; }
        .field-input-plain:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .field-label { display: block; font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8; margin-bottom: 6px; }

        /* ── Alert ── */
        .alert { border-radius: var(--r-md); padding: 14px 18px; margin-bottom: 16px; border: 1px solid; display: flex; align-items: center; gap: 10px; font-size: .82rem; font-weight: 700; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #14532d; }
        .alert-error   { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }

        /* ── Section label ── */
        .section-label { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .section-label::before { content:''; display:inline-block; width:3px; height:14px; border-radius:2px; background:var(--indigo); flex-shrink:0; }

        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
        @keyframes slideUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
        .fade-up { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }

        /* ── Dark mode ── */
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
        body.dark .card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .stat-card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .stat-num { color:#e2eaf8; }
        body.dark .stat-lbl { color:#4a6fa5; }
        body.dark .filter-bar { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .search-input { background:#101e35; border-color:rgba(99,102,241,.18); color:#e2eaf8; }
        body.dark .search-input:focus { background:#0b1628; border-color:#818cf8; }
        body.dark .qtab { background:#0b1628; border-color:rgba(99,102,241,.1); color:#7fb3e8; }
        body.dark .qtab.active { background:var(--indigo); color:white; }
        body.dark .reset-btn { background:#101e35; color:#7fb3e8; border-color:rgba(99,102,241,.1); }
        body.dark .pc-card, body.dark .pc-list-card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .section-label { color:#4a6fa5; }
        body.dark .modal-card { background:#0b1628; }
        body.dark .field-input, body.dark .field-input-plain { background:#101e35; border-color:rgba(99,102,241,.18); color:#e2eaf8; }
        body.dark .field-label { color:#4a6fa5; }
        body.dark .prog-bar { background:rgba(99,102,241,.15); }
        body.dark .logout-link { color:#4a6fa5; }
        body.dark .logout-link:hover { background:rgba(239,68,68,.1); color:#f87171; }
        body.dark .alert-success { background:rgba(20,83,45,.25); border-color:rgba(134,239,172,.2); color:#86efac; }
        body.dark .alert-error { background:rgba(127,29,29,.2); border-color:rgba(252,165,165,.2); color:#fca5a5; }
    </style>
</head>
<body>

<?php
$page     = $page ?? 'manage-pcs';
$navItems = [
    ['url'=>'/admin/dashboard',           'icon'=>'fa-house',        'label'=>'Dashboard',       'key'=>'dashboard'],
    ['url'=>'/admin/new-reservation',     'icon'=>'fa-plus',         'label'=>'New Reservation', 'key'=>'new-reservation'],
    ['url'=>'/admin/manage-reservations', 'icon'=>'fa-calendar',     'label'=>'Reservations',    'key'=>'manage-reservations'],
    ['url'=>'/admin/manage-pcs',          'icon'=>'fa-desktop',      'label'=>'Manage PCs',      'key'=>'manage-pcs'],
    ['url'=>'/admin/manage-sk',           'icon'=>'fa-user-shield',  'label'=>'Manage SK',       'key'=>'manage-sk'],
    ['url'=>'/admin/books',               'icon'=>'fa-book-open',    'label'=>'Library',         'key'=>'books'],
    ['url'=>'/admin/login-logs',          'icon'=>'fa-clock',        'label'=>'Login Logs',      'key'=>'login-logs'],
    ['url'=>'/admin/scanner',             'icon'=>'fa-qrcode',       'label'=>'Scanner',         'key'=>'scanner'],
    ['url'=>'/admin/activity-logs',       'icon'=>'fa-list',         'label'=>'Activity Logs',   'key'=>'activity-logs'],
    ['url'=>'/admin/profile',             'icon'=>'fa-user',         'label'=>'Profile',         'key'=>'profile'],
];

$totalPcs       = count($pcs ?? []);
$availableCount = count(array_filter($pcs ?? [], fn($p) => $p['status'] === 'available'));
$maintenCount   = $totalPcs - $availableCount;
$avatarLetter   = strtoupper(mb_substr(trim($user_name ?? 'A'), 0, 1));
?>

<!-- ════ ADD PC MODAL ════ -->
<div id="addModal" class="modal-back" onclick="if(event.target===this)closeModal('addModal')">
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;">
            <div>
                <div style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px;">Asset Management</div>
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Add New Station</h3>
                <p style="font-size:.75rem;color:#94a3b8;margin-top:3px;">Register a PC to the asset pool.</p>
            </div>
            <button onclick="closeModal('addModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-xmark" style="font-size:.75rem;"></i>
            </button>
        </div>
        <form action="/admin/add-pc" method="POST" style="display:flex;flex-direction:column;gap:14px;">
            <?= csrf_field() ?>
            <div>
                <label class="field-label">PC Number / Station Name</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-desktop" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.8rem;pointer-events:none;"></i>
                    <input type="text" name="pc_number" required placeholder="e.g. PC-01 or Lab Station 3" value="<?= old('pc_number') ?>" class="field-input">
                </div>
            </div>
            <div>
                <label class="field-label">Initial Status</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-circle-dot" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.8rem;pointer-events:none;"></i>
                    <select name="status" class="field-input-plain" style="padding-left:38px;">
                        <option value="available" <?= old('status','available') == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="maintenance" <?= old('status') == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;padding-top:4px;">
                <button type="button" onclick="closeModal('addModal')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">
                    <i class="fa-solid fa-floppy-disk" style="font-size:.8rem;"></i> Save Station
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ════ DELETE CONFIRM MODAL ════ -->
<div id="deleteModal" class="modal-back" onclick="if(event.target===this)closeModal('deleteModal')">
    <div class="modal-card sm">
        <div class="sheet-handle"></div>
        <div style="text-align:center;padding:8px 0 16px;">
            <div style="width:52px;height:52px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.5rem;">
                <i class="fa-solid fa-trash-can" style="color:#dc2626;"></i>
            </div>
            <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Delete Station?</h3>
            <p style="font-size:.8rem;color:#94a3b8;margin-top:4px;font-weight:500;">This action cannot be undone.</p>
            <p id="deleteStationName" style="font-size:.85rem;font-weight:700;color:#0f172a;margin-top:10px;"></p>
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeModal('deleteModal')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);">Cancel</button>
            <a id="deleteLink" href="#" style="flex:1;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;font-size:.85rem;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px;">
                <i class="fa-solid fa-trash-can" style="font-size:.75rem;"></i> Delete
            </a>
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
            <div style="min-width:0;">
                <div class="user-name-txt"><?= htmlspecialchars($user_name ?? 'Admin') ?></div>
                <div class="user-role-txt">Administrator</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
            ?>
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
            <div class="page-eyebrow">Asset Management</div>
            <div class="page-title">Manage PCs</div>
            <div class="page-sub">Monitor and manage station availability</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap;">
            <div style="width:44px;height:44px;background:white;border:1px solid rgba(99,102,241,.12);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;transition:all var(--ease);box-shadow:var(--shadow-sm);" onclick="toggleDark()" title="Toggle dark mode">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
            </div>
            <button onclick="openModal('addModal')" class="btn-primary">
                <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> Add New Station
            </button>
        </div>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success fade-up"><i class="fa-solid fa-circle-check"></i><?= session('success') ?><button onclick="this.closest('.alert').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-error fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session('error') ?><button onclick="this.closest('.alert').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button></div>
    <?php endif; ?>

    <!-- Stat cards -->
    <p class="section-label fade-up-1">Overview</p>
    <div class="stats-grid fade-up-1">
        <div class="stat-card" style="border-left-color:var(--indigo);" onclick="setFilter('all')">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <div class="stat-lbl">Total Stations</div>
                <i class="fa-solid fa-layer-group" style="color:var(--indigo);font-size:.85rem;"></i>
            </div>
            <div class="stat-num" style="color:var(--indigo);"><?= $totalPcs ?></div>
        </div>
        <div class="stat-card" style="border-left-color:#16a34a;" onclick="setFilter('available')">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <div class="stat-lbl">Available</div>
                <i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:.85rem;"></i>
            </div>
            <div class="stat-num" style="color:#16a34a;"><?= $availableCount ?></div>
        </div>
        <div class="stat-card" style="border-left-color:#d97706;" onclick="setFilter('maintenance')">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <div class="stat-lbl">Maintenance</div>
                <i class="fa-solid fa-wrench" style="color:#d97706;font-size:.85rem;"></i>
            </div>
            <div class="stat-num" style="color:#d97706;"><?= $maintenCount ?></div>
        </div>
    </div>

    <!-- Filter bar -->
    <div class="filter-bar fade-up-1">
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:180px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.72rem;pointer-events:none;"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search stations…" oninput="applyFilters()">
            </div>
            <button class="reset-btn" onclick="clearFilters()"><i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Reset</button>
        </div>
        <div style="display:flex;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:2px;">
            <button class="qtab active" data-tab="all"         onclick="setFilter('all')">
                <i class="fa-solid fa-layer-group" style="font-size:.7rem;"></i> All
                <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $totalPcs ?></span>
            </button>
            <button class="qtab" data-tab="available"          onclick="setFilter('available')">
                <i class="fa-solid fa-circle-check" style="font-size:.7rem;"></i> Available
                <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $availableCount ?></span>
            </button>
            <button class="qtab" data-tab="maintenance"        onclick="setFilter('maintenance')">
                <i class="fa-solid fa-wrench" style="font-size:.7rem;"></i> Maintenance
                <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $maintenCount ?></span>
            </button>
        </div>
        <p id="resultCount" style="font-size:.65rem;font-weight:700;color:#94a3b8;margin-top:10px;"></p>
    </div>

    <?php if (empty($pcs)): ?>
        <div class="card card-p" style="text-align:center;padding:48px 20px;">
            <i class="fa-solid fa-desktop" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
            <p style="font-weight:800;color:#94a3b8;font-size:1rem;">No stations yet</p>
            <p style="color:#cbd5e1;font-size:.82rem;margin-top:4px;margin-bottom:16px;">Add your first PC to get started.</p>
            <button onclick="openModal('addModal')" class="btn-primary" style="display:inline-flex;">
                <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> Add Station
            </button>
        </div>
    <?php else: ?>

        <!-- Desktop grid -->
        <div id="pcGrid" style="display:none;" class="fade-up-1">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
                <?php foreach ($pcs as $pc):
                    $isAvail = $pc['status'] === 'available';
                    $assetId = str_pad($pc['id'], 4, '0', STR_PAD_LEFT);
                    $pcNum   = htmlspecialchars($pc['pc_number']);
                ?>
                    <div class="pc-card <?= $isAvail ? 'available-card' : 'maintenance-card' ?>"
                         data-status="<?= $pc['status'] ?>"
                         data-search="<?= strtolower($pcNum) ?>">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                            <div style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;<?= $isAvail ? 'background:var(--indigo-light);color:var(--indigo);' : 'background:#fef3c7;color:#d97706;' ?>">
                                <i class="fa-solid fa-desktop" style="font-size:1.1rem;"></i>
                            </div>
                            <span class="tag <?= $isAvail ? 'tag-available' : 'tag-maintenance' ?>">
                                <i class="fa-solid <?= $isAvail ? 'fa-circle-check' : 'fa-wrench' ?>" style="font-size:.55rem;"></i>
                                <?= ucfirst($pc['status']) ?>
                            </span>
                        </div>
                        <h3 style="font-size:1rem;font-weight:800;color:#0f172a;margin-bottom:2px;">Station <?= $pcNum ?></h3>
                        <p style="font-size:.68rem;color:#94a3b8;font-family:var(--mono);margin-bottom:12px;">Asset #<?= $assetId ?></p>
                        <div style="margin-bottom:14px;">
                            <p style="font-size:.65rem;font-weight:700;color:<?= $isAvail ? '#16a34a' : '#d97706' ?>;margin-bottom:5px;"><?= $isAvail ? 'Ready for booking' : 'Under maintenance' ?></p>
                            <div class="prog-bar"><div class="prog-fill" style="width:<?= $isAvail ? '100%' : '40%' ?>;background:<?= $isAvail ? '#16a34a' : '#d97706' ?>;"></div></div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;margin-top:auto;">
                            <form action="/admin/update-pc-status" method="POST" style="flex:1;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                <input type="hidden" name="status" value="<?= $isAvail ? 'maintenance' : 'available' ?>">
                                <button type="submit" class="<?= $isAvail ? 'btn-toggle-to-maint' : 'btn-toggle-to-avail' ?>" style="width:100%;">
                                    <i class="fa-solid <?= $isAvail ? 'fa-wrench' : 'fa-circle-check' ?>" style="font-size:.65rem;"></i>
                                    <?= $isAvail ? 'Set Maintenance' : 'Set Available' ?>
                                </button>
                            </form>
                            <button onclick="confirmDelete(<?= $pc['id'] ?>, '<?= addslashes($pcNum) ?>')" class="btn-delete-sm">
                                <i class="fa-solid fa-trash-can" style="font-size:.75rem;"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mobile list -->
        <div id="pcListMobile" style="display:flex;flex-direction:column;gap:10px;" class="fade-up-1">
            <?php foreach ($pcs as $pc):
                $isAvail = $pc['status'] === 'available';
                $assetId = str_pad($pc['id'], 4, '0', STR_PAD_LEFT);
                $pcNum   = htmlspecialchars($pc['pc_number']);
            ?>
                <div class="pc-list-card <?= $isAvail ? 'available-card' : 'maintenance-card' ?>"
                     data-status="<?= $pc['status'] ?>"
                     data-search="<?= strtolower($pcNum) ?>">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
                        <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                            <div style="width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;<?= $isAvail ? 'background:var(--indigo-light);color:var(--indigo);' : 'background:#fef3c7;color:#d97706;' ?>">
                                <i class="fa-solid fa-desktop" style="font-size:.95rem;"></i>
                            </div>
                            <div style="min-width:0;">
                                <p style="font-weight:800;font-size:.9rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Station <?= $pcNum ?></p>
                                <p style="font-size:.68rem;color:#94a3b8;font-family:var(--mono);">Asset #<?= $assetId ?></p>
                            </div>
                        </div>
                        <span class="tag <?= $isAvail ? 'tag-available' : 'tag-maintenance' ?>" style="flex-shrink:0;">
                            <i class="fa-solid <?= $isAvail ? 'fa-circle-check' : 'fa-wrench' ?>" style="font-size:.55rem;"></i>
                            <?= ucfirst($pc['status']) ?>
                        </span>
                    </div>
                    <div style="margin-bottom:10px;">
                        <div class="prog-bar"><div class="prog-fill" style="width:<?= $isAvail ? '100%' : '40%' ?>;background:<?= $isAvail ? '#16a34a' : '#d97706' ?>;"></div></div>
                        <p style="font-size:.68rem;font-weight:600;color:<?= $isAvail ? '#16a34a' : '#d97706' ?>;margin-top:5px;"><?= $isAvail ? 'Ready for booking' : 'Under maintenance' ?></p>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <form action="/admin/update-pc-status" method="POST" style="flex:1;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                            <input type="hidden" name="status" value="<?= $isAvail ? 'maintenance' : 'available' ?>">
                            <button type="submit" class="<?= $isAvail ? 'btn-toggle-to-maint' : 'btn-toggle-to-avail' ?>" style="width:100%;padding:.6rem .75rem;">
                                <i class="fa-solid <?= $isAvail ? 'fa-wrench' : 'fa-circle-check' ?>" style="font-size:.65rem;"></i>
                                <?= $isAvail ? 'Set Maintenance' : 'Set Available' ?>
                            </button>
                        </form>
                        <button onclick="confirmDelete(<?= $pc['id'] ?>, '<?= addslashes($pcNum) ?>')" class="btn-delete-sm">
                            <i class="fa-solid fa-trash-can" style="font-size:.75rem;"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="noResultsMsg" class="hidden" style="text-align:center;padding:40px 20px;background:var(--card);border-radius:var(--r-lg);border:1px solid rgba(99,102,241,.08);">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
            <p style="font-size:.85rem;font-weight:700;color:#94a3b8;">No stations match your search.</p>
        </div>
    <?php endif; ?>

</main>

<style>
@media(min-width:640px) { #pcListMobile{display:none!important} #pcGrid{display:block!important} }
@media(max-width:639px) { #pcGrid{display:none!important} }
</style>

<script>
let curFilter = 'all';

function toggleDark(){
    const isDark = document.body.classList.toggle('dark');
    document.getElementById('darkIcon').innerHTML = isDark
        ? '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>'
        : '<i class="fa-regular fa-sun" style="font-size:.85rem;"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
}
(function(){
    if(localStorage.getItem('admin_theme') === 'dark'){
        document.body.classList.add('dark');
        document.getElementById('darkIcon').innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    }
})();

function openModal(id)  { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if(e.key === 'Escape') { closeModal('addModal'); closeModal('deleteModal'); } });

function confirmDelete(id, name) {
    document.getElementById('deleteStationName').textContent = `"Station ${name}"`;
    document.getElementById('deleteLink').href = `/admin/delete-pc/${id}`;
    openModal('deleteModal');
}

function setFilter(f) {
    curFilter = f;
    document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === f));
    applyFilters();
}
function applyFilters() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    let n = 0;
    const allCards = document.querySelectorAll('[data-status][data-search]');
    allCards.forEach(c => {
        const matchS = curFilter === 'all' || c.dataset.status === curFilter;
        const matchQ = !q || c.dataset.search.includes(q);
        const show = matchS && matchQ;
        c.style.display = show ? '' : 'none';
        if(show) n++;
    });
    const total = allCards.length;
    document.getElementById('resultCount').textContent = `Showing ${n} of ${total} station${total !== 1 ? 's' : ''}`;
    const noMsg = document.getElementById('noResultsMsg');
    if(noMsg) noMsg.classList.toggle('hidden', n > 0);
}
function clearFilters() {
    document.getElementById('searchInput').value = '';
    setFilter('all');
}
applyFilters();
</script>
</body>
</html>