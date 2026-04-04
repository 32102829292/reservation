<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Scanner | SK Officer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
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
            --r-sm:  10px;
            --r-md:  14px;
            --r-lg:  20px;
            --r-xl:  24px;
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
        .quota-wrap { margin: 8px 12px; background: #f8fafc; border-radius: var(--r-sm); padding: 12px 14px; border: 1px solid rgba(99,102,241,.09); }
        .quota-track { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .quota-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--indigo), #818cf8); }
        .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07); }
        .logout-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all var(--ease); }
        .logout-link:hover { background: #fef2f2; color: #dc2626; }
        .logout-link:hover .nav-icon { background: #fee2e2; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid rgba(99,102,241,.1); height: var(--mob-nav-total); z-index: 200; box-shadow: 0 -4px 20px rgba(55,48,163,.1); }
        .mobile-scroll-container { display: flex; justify-content: space-evenly; align-items: center; height: var(--mob-nav-h); width: 100%; }
        .mob-nav-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 48px; border-radius: 14px; cursor: pointer; text-decoration: none; color: #64748b; position: relative; transition: background .15s, color .15s; }
        .mob-nav-item:hover, .mob-nav-item.active { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active::after { content:''; position:absolute; bottom:4px; left:50%; transform:translateX(-50%); width:4px; height:4px; background:var(--indigo); border-radius:50%; }
        .mob-badge { position: absolute; top: 6px; right: 20%; background: #ef4444; color: white; font-size: .5rem; font-weight: 700; width: 14px; height: 14px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; }
        .mob-logout { color: #94a3b8; }
        .mob-logout:hover { background: #fef2f2; color: #dc2626; }
        @media(max-width:1023px) { .sidebar{display:none!important;} .mobile-nav-pill{display:flex!important;} .main-area{padding-bottom:calc(var(--mob-nav-total)+16px)!important;} }
        @media(min-width:1024px) { .sidebar{display:flex!important;} .mobile-nav-pill{display:none!important;} }

        /* ── Main ── */
        .main-area { flex: 1; min-width: 0; padding: 24px 28px 40px; height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; }
        @media(max-width:639px) { .main-area { padding: 14px 12px 0; } }

        /* ── Topbar ── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .page-title { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .page-sub { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; }
        @media(max-width:639px) { .page-title { font-size: 1.35rem; } }
        .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }

        /* ── Section label ── */
        .section-label { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .section-label::before { content:''; display:inline-block; width:3px; height:14px; border-radius:2px; background:var(--indigo); flex-shrink:0; }

        /* ── Cards ── */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 10px; flex-wrap: wrap; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .card-title { font-size: .9rem; font-weight: 700; color: #0f172a; }
        .card-sub { font-size: .7rem; color: #94a3b8; margin-top: 2px; }

        /* ── Cam status badge ── */
        .cam-status { display: flex; align-items: center; gap: 6px; font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; }
        .cam-dot { width: 7px; height: 7px; border-radius: 50%; background: #94a3b8; transition: background .3s; flex-shrink: 0; }
        .cam-dot.live { background: #22c55e; box-shadow: 0 0 6px rgba(34,197,94,.6); animation: livePulse 1.5s infinite; }
        @keyframes livePulse { 0%,100%{opacity:1} 50%{opacity:.5} }

        /* ── Scanner viewport ── */
        #scanner-viewport { width: 100%; border-radius: var(--r-lg); overflow: hidden; border: 2px solid rgba(99,102,241,.12); min-height: 280px; background: #f8fafc; display: flex; align-items: center; justify-content: center; position: relative; transition: border-color .3s; }
        #scanner-viewport.active { border-color: var(--indigo-border); }
        @media(min-width:640px) { #scanner-viewport { min-height: 340px; } }

        /* ── Scan frame ── */
        #scan-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; }
        .scan-frame { width: 200px; height: 200px; position: relative; }
        @media(min-width:640px) { .scan-frame { width: 220px; height: 220px; } }
        .scan-frame::before, .scan-frame::after { content:''; position:absolute; width:36px; height:36px; border-color:var(--indigo); border-style:solid; }
        .scan-frame::before { top:0; left:0; border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
        .scan-frame::after  { bottom:0; right:0; border-width:0 3px 3px 0; border-radius:0 0 4px 0; }
        .scan-frame-tr, .scan-frame-bl { position:absolute; width:36px; height:36px; border-color:var(--indigo); border-style:solid; }
        .scan-frame-tr { top:0; right:0; border-width:3px 3px 0 0; border-radius:0 4px 0 0; }
        .scan-frame-bl { bottom:0; left:0; border-width:0 0 3px 3px; border-radius:0 0 0 4px; }
        .scan-line { position:absolute; left:8px; right:8px; height:2px; background:linear-gradient(90deg,transparent,var(--indigo),transparent); top:8px; animation:scanLine 2s ease-in-out infinite; border-radius:1px; }
        @keyframes scanLine { 0%{top:8px;opacity:0} 10%{opacity:1} 90%{opacity:1} 100%{top:calc(100% - 10px);opacity:0} }

        /* ── Manual input ── */
        .search-input { width: 100%; padding: 10px 12px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .85rem; font-family: var(--font); background: #f8fafc; color: #0f172a; transition: all var(--ease); outline: none; }
        .search-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }

        /* ── Buttons ── */
        .btn-start { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--indigo); color: white; border-radius: var(--r-sm); font-size: .82rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); box-shadow: 0 4px 12px rgba(55,48,163,.28); }
        .btn-start:hover { background: #312e81; transform: translateY(-1px); }
        .btn-start:disabled { opacity: .5; cursor: not-allowed; transform: none; }
        .btn-stop { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; background: #f1f5f9; color: #475569; border-radius: var(--r-sm); font-size: .82rem; font-weight: 700; border: 1px solid #e2e8f0; cursor: pointer; font-family: var(--font); transition: all var(--ease); }
        .btn-stop:hover { background: #e2e8f0; color: #0f172a; }
        .btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: var(--r-sm); color: #475569; cursor: pointer; transition: all var(--ease); }
        .btn-icon:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }
        .btn-verify { padding: 10px 18px; background: #0f172a; color: white; border-radius: var(--r-sm); font-size: .82rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); white-space: nowrap; flex-shrink: 0; }
        .btn-verify:hover { background: #1e293b; }

        /* ── Result panel ── */
        .result-idle    { border-color: rgba(99,102,241,.08); }
        .result-success { border-color: #86efac; background: #f0fdf4 !important; }
        .result-warning { border-color: #fde68a; background: #fffbeb !important; }
        .result-error   { border-color: #fca5a5; background: #fff1f2 !important; }
        .result-claimed { border-color: #d8b4fe; background: #faf5ff !important; }

        /* ── Result overlay (mobile) ── */
        .result-overlay { display: none; position: fixed; inset: 0; z-index: 300; align-items: flex-end; justify-content: center; }
        .result-overlay.open { display: flex; animation: fadeIn .15s ease; }
        .result-overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); }
        .result-sheet { position: relative; width: 100%; background: white; border-radius: var(--r-xl) var(--r-xl) 0 0; max-height: 88vh; overflow-y: auto; z-index: 1; animation: sheetUp .26s cubic-bezier(.34,1.2,.64,1) both; box-shadow: var(--shadow-lg); }
        @keyframes sheetUp { from{opacity:0;transform:translateY(60px)} to{opacity:1;transform:none} }
        @keyframes scanFlash { 0%{box-shadow:0 0 0 0 rgba(55,48,163,.4)} 70%{box-shadow:0 0 0 20px rgba(55,48,163,0)} 100%{box-shadow:0 0 0 0 rgba(55,48,163,0)} }
        .scan-flash { animation: scanFlash .6s ease; }
        .sheet-handle { width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 12px auto 0; }

        @media(min-width:640px) { .result-overlay { display: none !important; } .result-panel-inline { display: block !important; } }
        .result-panel-inline { display: none; }

        /* ── Grid ── */
        .scanner-grid { display: grid; grid-template-columns: minmax(0,1.6fr) minmax(0,1fr); gap: 16px; }
        @media(max-width:900px) { .scanner-grid { grid-template-columns: 1fr; } }

        /* ── Stat row ── */
        .stat-row { display: flex; justify-content: space-between; align-items: center; padding: .5rem .75rem; border-radius: var(--r-sm); transition: background var(--ease); }
        .stat-row:hover { background: var(--indigo-light); }
        .stat-row-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #94a3b8; }
        .stat-row-val { font-size: .95rem; font-weight: 800; font-family: var(--mono); }

        /* ── History items ── */
        .history-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #f8fafc; border-radius: var(--r-md); border: 1px solid rgba(99,102,241,.07); cursor: pointer; transition: all var(--ease); gap: 10px; }
        .history-item:hover { border-color: var(--indigo-border); background: var(--indigo-light); }

        /* ── Permission error ── */
        .camera-error { background: #fff1f2; border: 1px solid #fca5a5; border-radius: var(--r-md); padding: 24px; text-align: center; width: 100%; }

        /* ── Detail rows ── */
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: .6rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; flex-shrink: 0; }
        .detail-value { font-weight: 700; color: #0f172a; font-size: .85rem; text-align: right; }

        /* ── Animations ── */
        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
        @keyframes slideUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }

        /* ══════ DARK MODE ══════ */
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
        body.dark .quota-wrap { background:rgba(99,102,241,.07); }
        body.dark .quota-track { background:rgba(99,102,241,.15); }
        body.dark .logout-link { color:#4a6fa5; }
        body.dark .logout-link:hover { background:rgba(239,68,68,.1); color:#f87171; }
        body.dark .mobile-nav-pill { background:#0b1628; border-color:rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color:#7fb3e8; }
        body.dark .mob-nav-item.active { background:rgba(99,102,241,.18); }
        body.dark .page-title { color:#e2eaf8; }
        body.dark .greeting-eyebrow, body.dark .page-sub { color:#4a6fa5; }
        body.dark .icon-btn { background:#0b1628; border-color:rgba(99,102,241,.15); color:#7fb3e8; }
        body.dark .icon-btn:hover { background:rgba(99,102,241,.12); color:#a5b4fc; }
        body.dark .cam-status { color:#4a6fa5; }
        body.dark .card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .card-title { color:#e2eaf8; }
        body.dark .card-sub { color:#4a6fa5; }
        body.dark .section-label { color:#4a6fa5; }
        body.dark #scanner-viewport { background:#101e35; border-color:rgba(99,102,241,.15); }
        body.dark #scanner-viewport.active { border-color:rgba(99,102,241,.4); }
        body.dark .search-input { background:#101e35; border-color:rgba(99,102,241,.18); color:#e2eaf8; }
        body.dark .search-input:focus { background:#0b1628; border-color:#818cf8; }
        body.dark .btn-stop { background:#101e35; border-color:rgba(99,102,241,.12); color:#7fb3e8; }
        body.dark .btn-stop:hover { background:#1a2a42; }
        body.dark .btn-icon { background:#101e35; border-color:rgba(99,102,241,.12); color:#7fb3e8; }
        body.dark .btn-icon:hover { background:rgba(99,102,241,.15); border-color:rgba(99,102,241,.3); color:#a5b4fc; }
        body.dark .btn-verify { background:#e2eaf8; color:#0f172a; }
        body.dark .btn-verify:hover { background:#f0f2f9; }
        body.dark .stat-row:hover { background:rgba(99,102,241,.08); }
        body.dark .stat-row-lbl { color:#4a6fa5; }
        body.dark .stat-row-val { color:#e2eaf8; }
        body.dark .history-item { background:#101e35; border-color:rgba(99,102,241,.08); }
        body.dark .history-item:hover { background:rgba(99,102,241,.12); border-color:rgba(99,102,241,.25); }
        body.dark .result-sheet { background:#0b1628; }
        body.dark .sheet-handle { background:#1a2a42; }
        body.dark .detail-row { border-color:#101e35; }
        body.dark .detail-label { color:#4a6fa5; }
        body.dark .detail-value { color:#e2eaf8; }
        body.dark .result-success { background:#0a1f10 !important; }
        body.dark .result-warning { background:#1a1400 !important; }
        body.dark .result-error   { background:#1a0a0a !important; }
        body.dark .result-claimed { background:#130a1f !important; }
    </style>
</head>
<body>

<?php
date_default_timezone_set('Asia/Manila');
$page = 'scanner';
$navItems = [
    ['url'=>'/sk/dashboard',       'icon'=>'fa-house',        'label'=>'Dashboard',        'key'=>'dashboard'],
    ['url'=>'/sk/reservations',    'icon'=>'fa-calendar-alt', 'label'=>'All Reservations', 'key'=>'reservations'],
    ['url'=>'/sk/new-reservation', 'icon'=>'fa-plus',         'label'=>'New Reservation',  'key'=>'new-reservation'],
    ['url'=>'/sk/user-requests',   'icon'=>'fa-users',        'label'=>'User Requests',    'key'=>'user-requests'],
    ['url'=>'/sk/my-reservations', 'icon'=>'fa-calendar',     'label'=>'My Reservations',  'key'=>'my-reservations'],
    ['url'=>'/sk/scanner',         'icon'=>'fa-qrcode',       'label'=>'Scanner',          'key'=>'scanner'],
    ['url'=>'/sk/profile',         'icon'=>'fa-user',         'label'=>'Profile',          'key'=>'profile'],
];
$pendingUserCount = $pendingUserCount ?? 0;
$usedSlots      = (int)($usedThisMonth ?? 0);
$maxSlots       = max(1, (int)($maxMonthlySlots ?? 3));
$quotaPct       = min(100, round($usedSlots / $maxSlots * 100));
$remainingReservations = $remainingReservations ?? 0;
$user_name      = $user_name ?? 'Officer';
$avatarLetter   = strtoupper(mb_substr(trim($user_name), 0, 1));

$reservationModel = new \App\Models\ReservationModel();
$allReservations  = $reservationModel
    ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
    ->join('resources', 'resources.id = reservations.resource_id', 'left')
    ->join('users',     'users.id = reservations.user_id',         'left')
    ->orderBy('reservations.created_at', 'DESC')
    ->findAll();
?>

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-top">
            <div class="brand-tag">SK Officer Portal</div>
            <div class="brand-name">my<em>Space.</em></div>
            <div class="brand-sub">Community Management</div>
        </div>
        <div class="user-card">
            <div class="user-avatar"><?= $avatarLetter ?></div>
            <div style="min-width:0;">
                <div class="user-name-txt"><?= htmlspecialchars($user_name) ?></div>
                <div class="user-role-txt">SK Officer</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $showBadge = ($item['key']==='user-requests' && $pendingUserCount>0);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active?'active':'' ?>">
                    <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem;"></i></div>
                    <?= $item['label'] ?>
                    <?php if ($showBadge): ?><span class="nav-badge"><?= $pendingUserCount ?></span><?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="quota-wrap">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:.7rem;font-weight:600;color:#64748b;">Monthly Quota</span>
                <span style="font-size:.7rem;font-weight:700;color:var(--indigo);font-family:var(--mono);"><?= $usedSlots ?>/<?= $maxSlots ?></span>
            </div>
            <div class="quota-track"><div class="quota-fill" style="width:<?= $quotaPct ?>%;"></div></div>
            <p style="font-size:.7rem;color:#94a3b8;margin-top:5px;"><?= $remainingReservations ?> slots remaining</p>
        </div>
        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08);"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171;"></i></div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- ════════ MOBILE NAV ════════ -->
<nav class="mobile-nav-pill">
    <div class="mobile-scroll-container">
        <?php foreach ($navItems as $item):
            $active = ($page == $item['key']);
            $showBadge = ($item['key']==='user-requests' && $pendingUserCount>0);
        ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active?'active':'' ?>" title="<?= $item['label'] ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem;"></i>
                <?php if ($showBadge): ?><span class="mob-badge"><?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171;"></i>
        </a>
    </div>
</nav>

<!-- Result overlay (mobile) -->
<div class="result-overlay" id="resultOverlay">
    <div class="result-overlay-bg" onclick="closeResultSheet()"></div>
    <div class="result-sheet" id="resultSheet">
        <div class="sheet-handle"></div>
        <div id="resultSheetBody" style="padding:16px 20px 32px;"></div>
    </div>
</div>

<!-- ════════ MAIN ════════ -->
<main class="main-area">

    <!-- TOPBAR -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow">SK Officer Portal</div>
            <div class="page-title">QR Scanner</div>
            <div class="page-sub">Verify reservations in real-time.</div>
        </div>
        <div class="topbar-right">
            <div class="cam-status">
                <span class="cam-dot" id="camDot"></span>
                <span id="camStatusLabel">Camera off</span>
            </div>
            <div class="icon-btn" onclick="toggleDark()" title="Toggle dark mode">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
            </div>
        </div>
    </div>

    <!-- SCANNER GRID -->
    <div class="scanner-grid fade-up-1">
        <!-- Left: Camera + Manual entry -->
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card card-p">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:var(--indigo-light);">
                            <i class="fa-solid fa-camera" style="color:var(--indigo);font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div class="card-title">Live Camera Feed</div>
                            <div class="card-sub">Point at a QR code to scan</div>
                        </div>
                    </div>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        <button id="startBtn" onclick="startScanner()" class="btn-start">
                            <i class="fa-solid fa-camera" style="font-size:.75rem;"></i> Start Camera
                        </button>
                        <button id="switchBtn" onclick="switchCamera()" style="display:none;" class="btn-icon" title="Switch Camera">
                            <i class="fa-solid fa-camera-rotate" style="font-size:.85rem;"></i>
                        </button>
                        <button id="stopBtn" onclick="stopScanner()" style="display:none;" class="btn-stop">
                            <i class="fa-solid fa-stop" style="font-size:.75rem;"></i> Stop
                        </button>
                    </div>
                </div>

                <div id="scanner-viewport">
                    <div style="text-align:center;padding:40px 20px;">
                        <div style="width:52px;height:52px;background:var(--indigo-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;"><i class="fa-solid fa-qrcode" style="font-size:1.5rem;color:var(--indigo);"></i></div>
                        <p style="font-size:.85rem;font-weight:600;color:#64748b;">Camera is inactive.</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:4px;">Tap "Start Camera" to begin scanning.</p>
                    </div>
                </div>

                <div style="margin-top:18px;padding-top:18px;border-top:1px solid rgba(99,102,241,.07);">
                    <p style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;">Manual Code Entry</p>
                    <div style="display:flex;gap:8px;">
                        <input type="text" id="manualCode" class="search-input" placeholder="Paste or type the reservation code…" onkeydown="if(event.key==='Enter')processCode(this.value)" style="flex:1;">
                        <button onclick="processCode(document.getElementById('manualCode').value)" class="btn-verify">Verify</button>
                    </div>
                </div>
            </div>

            <!-- Inline result panel (desktop) -->
            <div id="resultPanelInline" class="result-panel-inline card border-2 result-idle" style="display:none;">
                <div id="resultBodyInline" class="card-p"></div>
            </div>
        </div>

        <!-- Right: Stats + History -->
        <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Session stats -->
            <div class="card card-p">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <div class="card-icon" style="background:var(--indigo-light);"><i class="fa-solid fa-chart-bar" style="color:var(--indigo);font-size:.9rem;"></i></div>
                    <div>
                        <div class="card-title">Session Stats</div>
                        <div class="card-sub">This scan session</div>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <div class="stat-row"><span class="stat-row-lbl">Total Scanned</span><span class="stat-row-val" id="statTotal" style="color:var(--indigo);">0</span></div>
                    <div class="stat-row"><span class="stat-row-lbl">Approved / Valid</span><span class="stat-row-val" id="statApproved" style="color:#16a34a;">0</span></div>
                    <div class="stat-row"><span class="stat-row-lbl">Already Claimed</span><span class="stat-row-val" id="statClaimed" style="color:#7c3aed;">0</span></div>
                    <div class="stat-row"><span class="stat-row-lbl">Pending</span><span class="stat-row-val" id="statPending" style="color:#d97706;">0</span></div>
                    <div class="stat-row"><span class="stat-row-lbl">Invalid</span><span class="stat-row-val" id="statInvalid" style="color:#dc2626;">0</span></div>
                </div>
            </div>

            <!-- Recent scans -->
            <div class="card card-p" style="flex:1;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:var(--indigo-light);"><i class="fa-solid fa-clock-rotate-left" style="color:var(--indigo);font-size:.9rem;"></i></div>
                        <div class="card-title">Recent Scans</div>
                    </div>
                    <button onclick="clearHistory()" style="font-size:.65rem;font-weight:700;color:#ef4444;background:#fee2e2;border:none;border-radius:8px;padding:4px 10px;cursor:pointer;font-family:var(--font);">Clear</button>
                </div>
                <div id="historyList" style="display:flex;flex-direction:column;gap:6px;">
                    <p style="text-align:center;font-size:.8rem;color:#94a3b8;padding:20px 0;font-style:italic;">No recent scans</p>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
const reservations  = <?= json_encode($allReservations) ?>;
const validateUrl   = '<?= base_url("sk/validateETicket") ?>';
const csrfToken     = '<?= csrf_hash() ?>';
const csrfName      = '<?= csrf_token() ?>';

let videoStream=null, videoEl=null, canvasEl=null, rafId=null;
let scanHistory=[], lastScanned=null, currentCode=null, currentReservation=null;
let stats={total:0,approved:0,claimed:0,pending:0,invalid:0};
let scanning=false, availableCameras=[], currentCamIndex=0;

/* ── Dark Mode ── */
function toggleDark(){
    const isDark=document.body.classList.toggle('dark');
    document.getElementById('darkIcon').innerHTML=isDark?'<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>':'<i class="fa-regular fa-sun" style="font-size:.85rem;"></i>';
    localStorage.setItem('sk_theme',isDark?'dark':'light');
}
(function(){
    if(localStorage.getItem('sk_theme')==='dark'){
        document.body.classList.add('dark');
        document.getElementById('darkIcon').innerHTML='<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    }
})();

function setCamStatus(live){document.getElementById('camDot').classList.toggle('live',live);document.getElementById('camStatusLabel').textContent=live?'LIVE':'Camera off';}

function isClaimed(res){return[true,1,'t','true','1'].includes(res.claimed);}

function buildScannerUI(){
    const vp=document.getElementById('scanner-viewport');
    vp.innerHTML=''; vp.classList.add('active');
    videoEl=document.createElement('video');
    videoEl.setAttribute('autoplay',''); videoEl.setAttribute('muted',''); videoEl.setAttribute('playsinline','');
    videoEl.style.cssText='width:100%;height:100%;object-fit:cover;border-radius:18px;display:block;';
    vp.appendChild(videoEl);
    const overlay=document.createElement('div'); overlay.id='scan-overlay';
    overlay.innerHTML='<div class="scan-frame"><div class="scan-frame-tr"></div><div class="scan-frame-bl"></div><div class="scan-line"></div></div>';
    vp.appendChild(overlay);
    canvasEl=document.createElement('canvas'); canvasEl.style.display='none';
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
    try{const devices=await navigator.mediaDevices.enumerateDevices();availableCameras=devices.filter(d=>d.kind==='videoinput');}catch(e){availableCameras=[];}
    if(camIndex!=null){currentCamIndex=camIndex;}else{const ri=availableCameras.findIndex(c=>/back|rear|environment/i.test(c.label));currentCamIndex=ri>=0?ri:0;}
    document.getElementById('switchBtn').style.display=availableCameras.length>1?'inline-flex':'none';
    await openCamera(currentCamIndex);
}
async function openCamera(index){
    if(videoStream){scanning=false;if(rafId){cancelAnimationFrame(rafId);rafId=null;}videoStream.getTracks().forEach(t=>t.stop());videoStream=null;}
    const device=availableCameras[index];
    let stream=null;
    const attempts=[device?.deviceId?{video:{deviceId:{exact:device.deviceId},width:{ideal:1280},height:{ideal:720}}}:null,{video:{facingMode:{ideal:'environment'},width:{ideal:1280},height:{ideal:720}}},{video:{facingMode:'environment'}},{video:true}].filter(Boolean);
    for(const c of attempts){try{stream=await navigator.mediaDevices.getUserMedia(c);if(stream)break;}catch(e){}}
    if(!stream){showCameraError('Could not open camera.');return;}
    videoStream=stream; videoEl.srcObject=stream;
    videoEl.onloadedmetadata=()=>{videoEl.play().then(()=>{scanning=true;setCamStatus(true);requestAnimationFrame(scanFrame);}).catch(e=>showCameraError('Could not play video.'));};
    videoEl.onerror=()=>showCameraError('Video error');
}
async function switchCamera(){
    if(availableCameras.length<2)return;
    const btn=document.getElementById('switchBtn');btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin" style="font-size:.85rem;"></i>';
    currentCamIndex=(currentCamIndex+1)%availableCameras.length;
    await openCamera(currentCamIndex);
    btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-camera-rotate" style="font-size:.85rem;"></i>';
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
            }catch(e){}
        }
    }
    rafId=requestAnimationFrame(scanFrame);
}
function stopScanner(){
    scanning=false;
    if(rafId){cancelAnimationFrame(rafId);rafId=null;}
    if(videoStream){videoStream.getTracks().forEach(t=>t.stop());videoStream=null;}
    if(canvasEl?.parentNode){canvasEl.parentNode.removeChild(canvasEl);canvasEl=null;}
    videoEl=null;availableCameras=[];
    document.getElementById('switchBtn').style.display='none';
    setCamStatus(false);resetViewport();
}
function resetViewport(){
    const vp=document.getElementById('scanner-viewport');
    vp.classList.remove('active');
    vp.innerHTML='<div style="text-align:center;padding:40px 20px;"><div style="width:52px;height:52px;background:var(--indigo-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;"><i class="fa-solid fa-qrcode" style="font-size:1.5rem;color:var(--indigo);"></i></div><p style="font-size:.85rem;font-weight:600;color:#64748b;">Camera is inactive.</p><p style="font-size:.72rem;color:#94a3b8;margin-top:4px;">Tap "Start Camera" to begin scanning.</p></div>';
    document.getElementById('startBtn').style.display='inline-flex';
    document.getElementById('stopBtn').style.display='none';
}
function showCameraError(msg){
    const vp=document.getElementById('scanner-viewport');vp.classList.remove('active');
    vp.innerHTML=`<div class="camera-error"><div style="width:48px;height:48px;background:#fee2e2;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;"><i class="fa-solid fa-camera-slash" style="font-size:1.2rem;color:#dc2626;"></i></div><p style="font-weight:700;font-size:.85rem;color:#dc2626;margin-bottom:4px;">Camera unavailable</p><p style="font-size:.75rem;color:#64748b;">${msg}</p><button onclick="startScanner()" class="btn-start" style="margin:12px auto 0;"><i class="fa-solid fa-rotate-right" style="font-size:.75rem;"></i> Try Again</button></div>`;
    document.getElementById('startBtn').style.display='inline-flex';
    document.getElementById('stopBtn').style.display='none';
    setCamStatus(false);
}

function processCode(code){
    code=(code||'').trim(); if(!code)return;
    currentCode=code; addToHistory(code);
    const res=findByCode(code);
    if(!res){stats.invalid++;updateStats();showResult('error','fa-circle-xmark','#ef4444','#fee2e2','Code Not Recognised',`"${code}" doesn't match any reservation.`,[],false);return;}
    currentReservation=res;
    let pcLabel='—';
    if(res.pc_numbers){try{const a=typeof res.pc_numbers==='string'?JSON.parse(res.pc_numbers):res.pc_numbers;pcLabel=Array.isArray(a)?a.join(', '):a;}catch{pcLabel=res.pc_numbers;}}
    else if(res.pc_number){pcLabel=res.pc_number;}
    const eff=isClaimed(res)?'claimed':res.status;
    const cfgMap={
        approved:{state:'success',icon:'fa-circle-check',iconColor:'#16a34a',iconBg:'#dcfce7',title:'Access Granted',  sub:'Reservation is approved and valid.'},
        pending: {state:'warning',icon:'fa-clock',       iconColor:'#d97706',iconBg:'#fef3c7',title:'Pending Approval',sub:'This reservation has not been approved yet.'},
        declined:{state:'error',  icon:'fa-ban',         iconColor:'#dc2626',iconBg:'#fee2e2',title:'Access Denied',  sub:'This reservation has been declined.'},
        canceled:{state:'error',  icon:'fa-ban',         iconColor:'#dc2626',iconBg:'#fee2e2',title:'Access Denied',  sub:'This reservation was canceled.'},
        claimed: {state:'claimed',icon:'fa-check-double',iconColor:'#7c3aed',iconBg:'#ede9fe',title:'Already Claimed',sub:'This ticket has already been used.'},
    };
    const cfg=cfgMap[eff]||cfgMap.pending;
    if(eff==='approved') stats.approved++;
    else if(eff==='claimed') stats.claimed++;
    else if(eff==='pending') stats.pending++;
    else stats.invalid++;
    updateStats();
    const details=[
        {label:'Reservation ID', value:'#'+res.id},
        {label:'Name',           value:res.visitor_name||res.full_name||'Guest'},
        {label:'Email',          value:res.visitor_email||res.user_email||'—'},
        {label:'Asset',          value:res.resource_name||`Resource #${res.resource_id}`},
        {label:'Workstation',    value:pcLabel},
        {label:'Date',           value:res.reservation_date},
        {label:'Time',           value:`${res.start_time} – ${res.end_time}`},
        {label:'Purpose',        value:res.purpose||'—'},
        {label:'Status',         value:eff.charAt(0).toUpperCase()+eff.slice(1)},
    ];
    if(isClaimed(res)&&res.claimed_at) details.push({label:'Claimed At',value:new Date(res.claimed_at).toLocaleString()});
    showResult(cfg.state,cfg.icon,cfg.iconColor,cfg.iconBg,cfg.title,cfg.sub,details,eff==='approved');
    document.getElementById('manualCode').value='';
}
function findByCode(code){
    if(!code)return null;
    let r=reservations.find(r=>r.e_ticket_code===code);if(r)return r;
    if(!isNaN(code)){r=reservations.find(r=>r.id===parseInt(code)||String(r.id)===code);if(r)return r;}
    const m1=code.match(/(?:SK|ADMIN|RES)-(\d+)-/i);if(m1){r=reservations.find(r=>r.id===parseInt(m1[1]));if(r)return r;}
    const m2=code.match(/(?:SK|ADMIN|RES)(\d+)/i);if(m2){r=reservations.find(r=>r.id===parseInt(m2[1]));if(r)return r;}
    for(const rv of reservations){if(rv.e_ticket_code&&code.includes(rv.e_ticket_code))return rv;}
    return null;
}
function buildResultHTML(state,icon,iconColor,iconBg,title,sub,details,showValidate,idSuffix){
    const rows=details.map(d=>`<div class="detail-row"><span class="detail-label">${d.label}</span><span class="detail-value">${d.value}</span></div>`).join('');
    const vSect=showValidate?`<div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(99,102,241,.07);"><button id="validateBtn${idSuffix}" onclick="validateTicket()" style="width:100%;padding:12px;background:var(--indigo);color:white;border:none;border-radius:var(--r-sm);font-weight:700;font-size:.85rem;font-family:var(--font);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all var(--ease);" onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'"><i class="fa-solid fa-circle-check" style="font-size:.8rem;"></i> Mark as Used / Check In</button></div>`:'';
    return `<div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;"><div style="width:44px;height:44px;border-radius:12px;background:${iconBg};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid ${icon}" style="font-size:1.1rem;color:${iconColor};"></i></div><div><p style="font-weight:800;font-size:.95rem;color:#0f172a;">${title}</p><p style="font-size:.75rem;color:#64748b;font-weight:500;margin-top:2px;">${sub}</p></div></div><div style="background:#f8fafc;border-radius:var(--r-md);padding:12px;border:1px solid rgba(99,102,241,.07);">${rows}</div>${vSect}`;
}
function showResult(state,icon,iconColor,iconBg,title,sub,details,showValidate){
    const isMob=window.innerWidth<640;
    const html=buildResultHTML(state,icon,iconColor,iconBg,title,sub,details,showValidate,isMob?'Sheet':'Inline');
    const stateClass=`result-${state}`;
    if(isMob){
        document.getElementById('resultSheetBody').innerHTML=html;
        document.getElementById('resultOverlay').classList.add('open');
        document.body.style.overflow='hidden';
    }else{
        const panel=document.getElementById('resultPanelInline');
        panel.className=`result-panel-inline card border-2 ${stateClass}`;
        document.getElementById('resultBodyInline').innerHTML=html;
        panel.style.display='block';
        panel.scrollIntoView({behavior:'smooth',block:'nearest'});
    }
}
function closeResultSheet(){document.getElementById('resultOverlay').classList.remove('open');document.body.style.overflow='';}
function validateTicket(){
    if(!currentCode||!currentReservation)return;
    const isMob=window.innerWidth<640;
    const btn=document.getElementById('validateBtn'+(isMob?'Sheet':'Inline'));
    if(!btn)return;
    btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Checking in…';
    const codeToValidate=currentReservation.e_ticket_code||currentCode;
    const body=new URLSearchParams();
    body.append(csrfName,csrfToken);body.append('code',codeToValidate);
    fetch(validateUrl,{method:'POST',body,headers:{'X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded'}})
    .then(r=>r.json()).then(data=>{
        if(data.status==='success'||data.updated){
            const lr=reservations.find(r=>r.id===currentReservation.id);
            if(lr){lr.claimed=true;lr.claimed_at=new Date().toISOString();}
            stats.approved=Math.max(0,stats.approved-1);stats.claimed++;updateStats();
            btn.innerHTML='<i class="fa-solid fa-circle-check"></i> Checked In!';
            btn.style.background='#10b981';btn.disabled=true;renderHistory();
        }else if((data.message||'').toLowerCase().includes('claimed')){
            const lr=reservations.find(r=>r.id===currentReservation.id);if(lr)lr.claimed=true;
            stats.approved=Math.max(0,stats.approved-1);stats.claimed++;updateStats();
            btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-check-double"></i> Already claimed';btn.style.background='#7c3aed';
        }else{
            btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> '+(data.message||'Failed');btn.style.background='#dc2626';
        }
    }).catch(()=>{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> Network error';btn.style.background='#dc2626';});
}
function addToHistory(code){
    const time=new Date().toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'});
    scanHistory=scanHistory.filter(h=>h.code!==code);
    scanHistory.unshift({code,time});if(scanHistory.length>10)scanHistory.pop();renderHistory();
}
function renderHistory(){
    const list=document.getElementById('historyList');
    if(!scanHistory.length){list.innerHTML='<p style="text-align:center;font-size:.8rem;color:#94a3b8;padding:20px 0;font-style:italic;">No recent scans</p>';return;}
    const colorMap={approved:'background:#dcfce7;color:#166534',pending:'background:#fef3c7;color:#92400e',declined:'background:#fee2e2;color:#991b1b',claimed:'background:#ede9fe;color:#5b21b6'};
    const iconMap={approved:'fa-check',pending:'fa-clock',declined:'fa-ban',claimed:'fa-check-double'};
    list.innerHTML=scanHistory.map(item=>{
        const res=findByCode(item.code);
        const eff=res?(isClaimed(res)?'claimed':res.status):null;
        const sc=eff?(colorMap[eff]||'background:#f1f5f9;color:#64748b'):'background:#fee2e2;color:#991b1b';
        const si=eff?(iconMap[eff]||'fa-question'):'fa-xmark';
        return `<div class="history-item" onclick="processCode('${item.code.replace(/'/g,"\\'")}')"><div style="overflow:hidden;flex:1;"><p style="font-weight:700;font-size:.75rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-family:var(--mono);">${item.code}</p><p style="font-size:.62rem;color:#94a3b8;font-weight:600;letter-spacing:.05em;margin-top:2px;text-transform:uppercase;">${item.time}</p></div><div style="width:28px;height:28px;border-radius:9px;${sc};display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid ${si}" style="font-size:.7rem;"></i></div></div>`;
    }).join('');
}
function clearHistory(){
    if(!scanHistory.length)return;
    if(!confirm('Clear all scan history?'))return;
    scanHistory=[];stats={total:0,approved:0,claimed:0,pending:0,invalid:0};
    updateStats();renderHistory();
    const p=document.getElementById('resultPanelInline');p.style.display='none';p.className='result-panel-inline card border-2 result-idle';
    closeResultSheet();currentReservation=null;currentCode=null;
}
function updateStats(){
    stats.total=scanHistory.length;
    document.getElementById('statTotal').textContent   =stats.total;
    document.getElementById('statApproved').textContent=stats.approved;
    document.getElementById('statClaimed').textContent =stats.claimed;
    document.getElementById('statPending').textContent =stats.pending;
    document.getElementById('statInvalid').textContent =stats.invalid;
}
document.addEventListener('visibilitychange',()=>{if(document.hidden&&scanning)stopScanner();});
window.addEventListener('beforeunload',()=>{if(scanning)stopScanner();});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeResultSheet();});
renderHistory();
</script>
</body>
</html>