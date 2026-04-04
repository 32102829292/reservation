<?php date_default_timezone_set('Asia/Manila');
$page = $page ?? 'profile'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Profile | <?= esc($user['name'] ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e1b4b">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark-pre');
        })();
    </script>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --indigo: #3730a3;
            --indigo-mid: #4338ca;
            --indigo-light: #eef2ff;
            --indigo-border: #c7d2fe;
            --bg: #f0f2f9;
            --card: #ffffff;
            --font: 'Plus Jakarta Sans', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --shadow-sm: 0 1px 4px rgba(15,23,42,.07), 0 1px 2px rgba(15,23,42,.04);
            --shadow-md: 0 4px 16px rgba(15,23,42,.09), 0 2px 4px rgba(15,23,42,.04);
            --shadow-lg: 0 12px 40px rgba(15,23,42,.12), 0 4px 8px rgba(15,23,42,.06);
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 20px;
            --r-xl: 24px;
            --sidebar-w: 268px;
            --ease: .18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }

        html { height: 100%; height: 100dvh; font-size: 16px; }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: #0f172a;
            display: flex;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
            font-size: 1rem;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        html.dark-pre body, html.dark-pre .sidebar-inner, html.dark-pre .mobile-nav-pill {
            background: #060e1e;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            padding: 18px 14px;
            height: 100vh; height: 100dvh;
            display: flex; flex-direction: column;
        }
        .sidebar-inner {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(99,102,241,.1);
            height: 100%;
            display: flex; flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        .sidebar-top {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(99,102,241,.07);
        }
        .brand-tag { font-size: .6rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px; }
        .brand-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.03em; line-height: 1.1; }
        .brand-name em { font-style: normal; color: var(--indigo); }
        .brand-sub { font-size: .7rem; color: #94a3b8; margin-top: 3px; }
        .user-card {
            margin: 12px 12px 0;
            background: var(--indigo-light);
            border-radius: var(--r-md);
            padding: 12px 14px;
            border: 1px solid var(--indigo-border);
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--indigo); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: .85rem; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(55,48,163,.3);
        }
        .user-name-txt { font-size: .8rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
        .user-role-txt { font-size: .68rem; color: #6366f1; font-weight: 500; margin-top: 1px; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 3px; }
        .sidebar-nav::-webkit-scrollbar { width: 2px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
        .nav-section-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #cbd5e1; padding: 10px 10px 5px; margin-top: 2px; }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: var(--r-sm);
            font-size: .85rem; font-weight: 600; color: #64748b;
            text-decoration: none; transition: all var(--ease);
        }
        .nav-link:hover { background: var(--indigo-light); color: var(--indigo); }
        .nav-link.active { background: var(--indigo); color: #fff; box-shadow: 0 4px 14px rgba(55,48,163,.32); }
        .nav-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.15); }
        .nav-link:not(.active) .nav-icon { background: #f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background: #e0e7ff; }
        .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07); }
        .logout-link {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px;
            border-radius: var(--r-sm); font-size: .85rem; font-weight: 600;
            color: #94a3b8; text-decoration: none; transition: all var(--ease);
        }
        .logout-link:hover { background: #fef2f2; color: #dc2626; }
        .logout-link:hover .nav-icon { background: #fee2e2; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0; width: 100%;
            background: white;
            border-top: 1px solid rgba(99,102,241,.1);
            height: var(--mob-nav-total);
            z-index: 200;
            box-shadow: 0 -4px 20px rgba(55,48,163,.1);
        }
        .mobile-scroll-container {
            display: flex; justify-content: space-evenly; align-items: center;
            height: var(--mob-nav-h); width: 100%; padding: 0;
        }
        .mob-nav-item {
            flex: 1; display: flex; align-items: center; justify-content: center;
            height: 48px; border-radius: 14px; cursor: pointer;
            text-decoration: none; color: #64748b; position: relative;
            transition: background .15s, color .15s;
        }
        .mob-nav-item:hover { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active svg { stroke: var(--indigo); }
        .mob-nav-item.active::after {
            content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
            width: 4px; height: 4px; background: var(--indigo); border-radius: 50%;
        }
        .mob-logout { color: #94a3b8; }
        .mob-logout:hover { background: #fef2f2; color: #dc2626; }

        @media(max-width:1023px) {
            .sidebar { display: none !important; }
            .mobile-nav-pill { display: flex !important; }
            .main-area { padding-bottom: calc(var(--mob-nav-total) + 16px) !important; }
        }
        @media(min-width:1024px) {
            .sidebar { display: flex !important; }
            .mobile-nav-pill { display: none !important; }
        }

        /* ── Main ── */
        .main-area {
            flex: 1; min-width: 0;
            padding: 24px 28px 40px;
            height: 100vh; height: 100dvh;
            overflow-y: auto; overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-y: contain;
        }
        @media(max-width:1023px) {
            .main-area::-webkit-scrollbar { display: none; }
            .main-area { scrollbar-width: none; }
        }
        @media(min-width:1024px) {
            .main-area::-webkit-scrollbar { width: 4px; }
            .main-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        }

        /* ── Topbar ── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
        .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .greeting-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .greeting-sub { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; }
        .icon-btn {
            width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12);
            border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center;
            color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm);
        }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }

        /* ── Cards ── */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }
        .card-p-lg { padding: 22px 24px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .card-title { font-size: .9rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
        .card-sub { font-size: .7rem; color: #94a3b8; margin-top: 2px; }
        .section-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; }

        /* ── Flash ── */
        .flash-ok {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px; padding: 13px 18px;
            background: var(--indigo-light); border: 1px solid var(--indigo-border);
            color: var(--indigo); font-weight: 600; border-radius: var(--r-md);
            font-size: .9rem; animation: slideUp .4s ease both;
        }
        .flash-err {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px; padding: 13px 18px;
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; font-weight: 600; border-radius: var(--r-md);
            font-size: .9rem; animation: slideUp .4s ease both;
        }

        /* ── Avatar ── */
        .profile-avatar-wrap {
            position: relative; display: inline-block; margin-bottom: 18px;
        }
        .profile-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 800; color: white;
            box-shadow: 0 8px 24px rgba(55,48,163,.3);
            font-family: var(--mono);
            letter-spacing: -.04em;
        }
        .profile-status-dot {
            position: absolute; bottom: -4px; right: -4px;
            width: 22px; height: 22px;
            background: #10b981; border: 3px solid white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Info rows ── */
        .info-row {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(99,102,241,.06);
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: #f8fafc; border: 1px solid rgba(99,102,241,.09);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .info-label { font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #94a3b8; }
        .info-value { font-size: .85rem; font-weight: 600; color: #0f172a; margin-top: 1px; }

        /* ── Stats (security/activity) ── */
        .stat-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-mini {
            background: #f8fafc; border: 1px solid rgba(99,102,241,.09);
            border-radius: var(--r-sm); padding: 12px 14px;
        }
        .stat-mini-lbl { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .14em; color: #94a3b8; margin-bottom: 4px; }
        .stat-mini-val { font-size: 1.25rem; font-weight: 800; color: #0f172a; font-family: var(--mono); line-height: 1; letter-spacing: -.03em; }
        .stat-mini-sub { font-size: .68rem; color: #94a3b8; margin-top: 3px; }

        /* ── Quota bar ── */
        .quota-track { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 8px; }
        .quota-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--indigo), #818cf8); transition: width .6s cubic-bezier(.34,1.56,.64,1); }

        /* ── Edit btn ── */
        .edit-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 12px; background: var(--indigo); color: white;
            border-radius: var(--r-sm); font-size: .85rem; font-weight: 700;
            border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease);
            box-shadow: 0 4px 12px rgba(55,48,163,.28); margin-top: 16px;
        }
        .edit-btn:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(55,48,163,.35); }

        /* ── Tip banner ── */
        .tip-banner {
            background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%);
            border-radius: var(--r-lg); padding: 20px 22px;
            display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden;
        }
        .tip-banner::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;
            opacity: .4;
        }
        .tip-icon { width: 42px; height: 42px; background: rgba(255,255,255,.15); border-radius: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 1; }

        /* ── Password strength ── */
        .pw-strength { height: 3px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
        .pw-fill { height: 100%; border-radius: 999px; transition: width .3s, background .3s; }

        /* ── Modal ── */
        .modal-back {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,.52); backdrop-filter: blur(6px);
            z-index: 300; padding: 1.5rem; overflow-y: auto;
            align-items: center; justify-content: center;
        }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card {
            background: white; border-radius: var(--r-xl);
            width: 100%; max-width: 480px; padding: 28px;
            max-height: calc(100dvh - 3rem); overflow-y: auto;
            margin: auto; animation: slideUp .2s ease;
            box-shadow: var(--shadow-lg);
        }
        .field-label { display: block; font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #94a3b8; margin-bottom: 6px; }
        .field-input {
            width: 100%; background: #f8fafc; border: 1px solid rgba(99,102,241,.15);
            border-radius: var(--r-sm); padding: 11px 14px;
            font-family: var(--font); font-size: .87rem; font-weight: 600;
            color: #0f172a; transition: all .2s; outline: none;
        }
        .field-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .field-hint { font-size: .65rem; color: #94a3b8; margin-top: 4px; }

        /* ── Danger zone ── */
        .danger-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 0; border-bottom: 1px solid rgba(99,102,241,.06);
            gap: 12px;
        }
        .danger-row:last-child { border-bottom: none; }
        .danger-btn {
            font-size: .75rem; font-weight: 700; padding: 8px 14px;
            border-radius: 9px; border: 1px solid #fecaca;
            background: #fef2f2; color: #dc2626; cursor: pointer;
            font-family: var(--font); transition: all var(--ease); white-space: nowrap; flex-shrink: 0;
        }
        .danger-btn:hover { background: #fee2e2; border-color: #f87171; }

        /* ── Link ── */
        .link-sm { font-size: .65rem; font-weight: 700; color: var(--indigo); text-decoration: none; letter-spacing: .05em; text-transform: uppercase; transition: opacity .15s; }
        .link-sm:hover { opacity: .7; }

        /* ── Tag ── */
        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        .tag-indigo { background: var(--indigo-light); color: #3730a3; border: 1px solid var(--indigo-border); }

        /* ── Delete modal specific ── */
        #deleteSubmitBtn:disabled { opacity: .4; cursor: not-allowed; }
        #deleteSubmitBtn:not(:disabled) { opacity: 1; cursor: pointer; }
        .delete-confirm-input-error { border-color: #f87171 !important; background: #fff5f5 !important; }

        /* ── Animations ── */
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s  ease both; }
        .fade-up-3 { animation: slideUp .45s .15s ease both; }

        /* ── Mobile ── */
        @media(max-width:639px) {
            .main-area { padding: 14px 12px 0; }
            .stat-mini-val { font-size: 1.1rem; }
        }

        /* ── Dark mode ── */
        body.dark {
            --bg: #060e1e; --card: #0b1628;
            --indigo-light: rgba(55,48,163,.12);
            --indigo-border: rgba(99,102,241,.25);
            color: #e2eaf8;
        }
        body.dark .sidebar-inner { background: #0b1628; border-color: rgba(99,102,241,.12); }
        body.dark .sidebar-top, body.dark .sidebar-footer { border-color: rgba(99,102,241,.1); }
        body.dark .brand-name { color: #e2eaf8; }
        body.dark .nav-link { color: #7fb3e8; }
        body.dark .nav-link:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .nav-link:not(.active) .nav-icon { background: rgba(99,102,241,.1); }
        body.dark .user-card { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.2); }
        body.dark .user-name-txt { color: #e2eaf8; }
        body.dark .greeting-name { color: #e2eaf8; }
        body.dark .card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .card-title { color: #e2eaf8; }
        body.dark .icon-btn { background: #0b1628; border-color: rgba(99,102,241,.15); color: #7fb3e8; }
        body.dark .icon-btn:hover { background: rgba(99,102,241,.12); }
        body.dark .info-icon { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .info-value { color: #e2eaf8; }
        body.dark .stat-mini { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .stat-mini-val { color: #e2eaf8; }
        body.dark .quota-track { background: rgba(99,102,241,.15); }
        body.dark .mobile-nav-pill { background: #0b1628; border-color: rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color: #7fb3e8; }
        body.dark .mob-nav-item.active { background: rgba(99,102,241,.18); }
        body.dark .profile-status-dot { border-color: #0b1628; }
        body.dark .modal-card { background: #0b1628; color: #e2eaf8; }
        body.dark .field-input { background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8; }
        body.dark .field-input:focus { background: #0b1628; }
        body.dark .danger-btn { background: rgba(220,38,38,.1); border-color: rgba(248,113,113,.3); }
        body.dark .danger-row { border-color: rgba(99,102,241,.08); }
        body.dark .info-row { border-color: rgba(99,102,241,.06); }
        body.dark .flash-ok { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.3); color: #a5b4fc; }
        body.dark .flash-err { background: rgba(220,38,38,.1); border-color: rgba(248,113,113,.3); color: #f87171; }
        body.dark .delete-modal-card { background: #0b1628; }
        body.dark #deleteConfirmInput { background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8; }
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'house',      'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'plus',       'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'calendar',   'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books',            'icon' => 'book-open',  'label' => 'Library',         'key' => 'books'],
        ['url' => '/profile',          'icon' => 'user',       'label' => 'Profile',         'key' => 'profile'],
    ];

    function svgIcon($name, $size = 16, $stroke = 'currentColor') {
        $icons = [
            'house'      => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
            'plus'       => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
            'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'book-open'  => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
            'user'       => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
            'logout'     => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
            'sun'        => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
            'moon'       => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
            'edit'       => '<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/>',
            'id'         => '<path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" stroke-linecap="round" stroke-linejoin="round"/>',
            'mail'       => '<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/>',
            'phone'      => '<path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round"/>',
            'calendar2'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/>',
            'shield'     => '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/>',
            'key'        => '<path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" stroke-linecap="round" stroke-linejoin="round"/>',
            'layers'     => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
            'check'      => '<polyline points="20 6 9 17 4 12"/>',
            'check-c'    => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>',
            'lightbulb'  => '<path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" stroke-linecap="round" stroke-linejoin="round"/>',
            'trending'   => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
            'x'          => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            'trash'      => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke-linecap="round" stroke-linejoin="round"/>',
            'lock'       => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round" stroke-linejoin="round"/>',
            'alert'      => '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        ];
        $d = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
        return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="'.$stroke.'" stroke-width="1.8">'.$d.'</svg>';
    }

    $avatarLetter = strtoupper(mb_substr(trim($user['name'] ?? 'U'), 0, 1));
    $memberSince  = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : '—';
    $memberYear   = isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y');
    ?>

    <!-- ══════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════ -->
    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">Resident Portal</div>
                <div class="brand-name">my<em>Space.</em></div>
                <div class="brand-sub">Community Management</div>
            </div>
            <div class="user-card" style="display:flex;align-items:center;gap:9px;">
                <div class="user-avatar"><?= $avatarLetter ?></div>
                <div style="min-width:0;">
                    <div class="user-name-txt" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($user['name'] ?? 'User') ?></div>
                    <div class="user-role-txt">Resident</div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-lbl">Menu</div>
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']);
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><?= svgIcon($item['icon'], 16, $active ? 'white' : '#64748b') ?></div>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);"><?= svgIcon('logout', 16, '#f87171') ?></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <!-- ══════════════════════════════════════════
         MOBILE NAV
    ══════════════════════════════════════════ -->
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
            ?>
                <a href="<?= base_url($item['url']) ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= esc($item['label']) ?>">
                    <?= svgIcon($item['icon'], 22, $active ? 'var(--indigo)' : '#64748b') ?>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-logout" title="Sign Out">
                <?= svgIcon('logout', 22, '#f87171') ?>
            </a>
        </div>
    </nav>

    <!-- ══════════════════════════════════════════
         EDIT PROFILE MODAL
    ══════════════════════════════════════════ -->
    <div id="editModal" class="modal-back" onclick="handleModalBack(event)">
        <div class="modal-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px;">
                <div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Update Profile</h3>
                    <p style="font-size:.75rem;color:#94a3b8;margin-top:3px;">Changes are saved immediately.</p>
                </div>
                <button onclick="closeModal()" style="width:36px;height:36px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <?= svgIcon('x', 13, '#64748b') ?>
                </button>
            </div>
            <form action="<?= base_url('profile/update') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="field-label">Full Name</label>
                    <input type="text" name="name" value="<?= esc($user['name'] ?? '') ?>" class="field-input" required>
                </div>
                <div>
                    <label class="field-label">Email Address</label>
                    <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="field-input" required>
                </div>
                <div>
                    <label class="field-label">Contact Number</label>
                    <input type="text" name="phone" value="<?= esc($user['phone'] ?? '') ?>" class="field-input" placeholder="+63 912 345 6789">
                </div>
                <div>
                    <label class="field-label">New Password</label>
                    <input type="password" name="password" id="pwInput" class="field-input" placeholder="Leave blank to keep current" oninput="checkPw(this.value)">
                    <div class="pw-strength"><div id="pwFill" class="pw-fill" style="width:0%;background:#e2e8f0;"></div></div>
                    <p class="field-hint" id="pwHint">Minimum 8 characters</p>
                </div>
                <div style="display:flex;gap:10px;padding-top:6px;">
                    <button type="button" onclick="closeModal()" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background var(--ease);" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Cancel</button>
                    <button type="submit" style="flex:2;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background var(--ease);box-shadow:0 4px 12px rgba(55,48,163,.28);" onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         DELETE ACCOUNT MODAL
    ══════════════════════════════════════════ -->
    <div id="deleteModal" class="modal-back" onclick="handleDeleteBack(event)">
        <div class="modal-card" style="max-width:420px;">
            <!-- Header -->
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:#fef2f2;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid #fecaca;">
                        <?= svgIcon('trash', 18, '#dc2626') ?>
                    </div>
                    <div>
                        <h3 style="font-size:.97rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Delete Account</h3>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">This cannot be undone</p>
                    </div>
                </div>
                <button onclick="closeDeleteModal()" style="width:34px;height:34px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <?= svgIcon('x', 12, '#64748b') ?>
                </button>
            </div>

            <!-- Warning banner -->
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-sm);padding:13px 15px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start;">
                <?= svgIcon('alert', 15, '#dc2626') ?>
                <div>
                    <p style="font-size:.8rem;font-weight:700;color:#b91c1c;margin-bottom:3px;">Warning: Permanent Action</p>
                    <p style="font-size:.73rem;color:#dc2626;line-height:1.5;">All your reservations, library history, and account data will be <strong>permanently deleted</strong>. You will be immediately logged out and cannot recover your account.</p>
                </div>
            </div>

            <!-- Confirmation form -->
            <form action="<?= base_url('profile/delete') ?>" method="POST" id="deleteForm">
                <?= csrf_field() ?>
                <div style="margin-bottom:18px;">
                    <label class="field-label" style="color:#dc2626;">
                        Type <span style="font-family:var(--mono);background:#fef2f2;padding:1px 6px;border-radius:4px;font-size:.65rem;">DELETE</span> to confirm
                    </label>
                    <input
                        type="text"
                        id="deleteConfirmInput"
                        class="field-input"
                        placeholder="Type DELETE here"
                        oninput="checkDeleteConfirm(this.value)"
                        autocomplete="off"
                        style="margin-top:6px;border-color:#fecaca;"
                    >
                    <p class="field-hint" id="deleteHint" style="color:#dc2626;">This field is case-sensitive</p>
                </div>

                <div style="display:flex;gap:10px;">
                    <button
                        type="button"
                        onclick="closeDeleteModal()"
                        style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background var(--ease);"
                        onmouseover="this.style.background='#f1f5f9'"
                        onmouseout="this.style.background='#f8fafc'"
                    >Cancel</button>
                    <button
                        type="submit"
                        id="deleteSubmitBtn"
                        disabled
                        style="flex:2;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;font-size:.82rem;font-family:var(--font);transition:all var(--ease);box-shadow:0 4px 12px rgba(220,38,38,.25);"
                    >
                        <span id="deleteSubmitTxt">Delete My Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ -->
    <main class="main-area">

        <!-- Topbar -->
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">Account</div>
                <div class="greeting-name">My Profile</div>
                <div class="greeting-sub">Manage your account settings and security.</div>
            </div>
            <div class="topbar-right">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn">
                    <span id="dark-icon"><?= svgIcon('sun', 14, '#94a3b8') ?></span>
                </div>
                <span class="tag tag-indigo" style="font-size:.6rem;font-weight:700;">Resident</span>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok fade-up">
                <?= svgIcon('check-c', 15, 'var(--indigo)') ?>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err fade-up">
                <?= svgIcon('x', 15, '#dc2626') ?>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.6fr);gap:16px;" class="fade-up-1" id="profileGrid">

            <!-- ── LEFT: Identity card ── -->
            <div style="display:flex;flex-direction:column;gap:14px;">

                <div class="card card-p" style="text-align:center;">
                    <div class="profile-avatar-wrap" style="margin:0 auto 18px;">
                        <div class="profile-avatar"><?= $avatarLetter ?></div>
                        <div class="profile-status-dot">
                            <?= svgIcon('check', 8, 'white') ?>
                        </div>
                    </div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;"><?= esc($user['name'] ?? 'Resident') ?></h3>
                    <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($user['email'] ?? '') ?></p>
                    <?php if (!empty($user['phone'])): ?>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;font-family:var(--mono);"><?= esc($user['phone']) ?></p>
                    <?php endif; ?>
                    <div style="margin-top:12px;">
                        <span class="tag tag-indigo">Resident User</span>
                    </div>
                    <button class="edit-btn" onclick="openModal()">
                        <?= svgIcon('edit', 14, 'white') ?> Edit Profile
                    </button>
                </div>

                <!-- Mini stats -->
                <div class="card card-p">
                    <div class="section-lbl">Account Activity</div>
                    <div class="stat-mini-grid">
                        <div class="stat-mini">
                            <div class="stat-mini-lbl">Member Since</div>
                            <div class="stat-mini-val" style="font-size:.95rem;letter-spacing:-.01em;font-weight:800;"><?= $memberYear ?></div>
                            <div class="stat-mini-sub">Year joined</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-lbl">Status</div>
                            <div style="display:flex;align-items:center;gap:5px;margin-top:2px;">
                                <div style="width:8px;height:8px;background:#10b981;border-radius:50%;box-shadow:0 0 0 3px rgba(16,185,129,.15);flex-shrink:0;"></div>
                                <div class="stat-mini-val" style="font-size:.85rem;font-family:var(--font);">Active</div>
                            </div>
                            <div class="stat-mini-sub">Verified</div>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="card card-p">
                    <div class="section-lbl">Quick Access</div>
                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <?php
                        $quickLinks = [
                            ['/reservation',      'plus',      '#eef2ff', 'var(--indigo)', 'New Reservation'],
                            ['/reservation-list', 'calendar',  '#ede9fe', '#7c3aed',       'My Reservations'],
                            ['/books',            'book-open', '#fef3c7', '#d97706',        'Browse Library'],
                            ['/dashboard',        'layers',    '#f3e8ff', '#9333ea',        'Dashboard'],
                        ];
                        foreach ($quickLinks as [$url, $icon, $bg, $fg, $label]):
                        ?>
                        <a href="<?= base_url($url) ?>" style="display:flex;align-items:center;gap:10px;padding:10px 10px;border-radius:var(--r-sm);border:1px solid rgba(99,102,241,.09);background:white;text-decoration:none;color:#475569;font-size:.83rem;font-weight:600;transition:all var(--ease);" onmouseover="this.style.borderColor='var(--indigo)';this.style.background='var(--indigo-light)';this.style.color='var(--indigo)'" onmouseout="this.style.borderColor='rgba(99,102,241,.09)';this.style.background='white';this.style.color='#475569'">
                            <div style="width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $bg ?>;"><?= svgIcon($icon, 14, $fg) ?></div>
                            <?= $label ?>
                            <span style="margin-left:auto;color:#cbd5e1;"><?= svgIcon('check', 12, 'currentColor') ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ── RIGHT: Details ── -->
            <div style="display:flex;flex-direction:column;gap:14px;">

                <!-- Personal Info -->
                <div class="card card-p-lg">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:#eef2ff;"><?= svgIcon('id', 16, 'var(--indigo)') ?></div>
                            <div>
                                <div class="card-title">Personal Information</div>
                                <div class="card-sub">Your account details</div>
                            </div>
                        </div>
                        <button onclick="openModal()" style="display:flex;align-items:center;gap:6px;padding:8px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:9px;font-size:.72rem;font-weight:700;color:var(--indigo);cursor:pointer;font-family:var(--font);transition:all var(--ease);" onmouseover="this.style.background='var(--indigo)';this.style.color='white'" onmouseout="this.style.background='var(--indigo-light)';this.style.color='var(--indigo)'">
                            <?= svgIcon('edit', 12, 'currentColor') ?> Edit
                        </button>
                    </div>

                    <?php
                    $infoFields = [
                        ['icon' => 'id',       'label' => 'Full Name',      'value' => $user['name']  ?? 'Not set'],
                        ['icon' => 'mail',     'label' => 'Email Address',  'value' => $user['email'] ?? 'Not set'],
                        ['icon' => 'phone',    'label' => 'Contact Number', 'value' => $user['phone'] ?? 'Not set'],
                        ['icon' => 'calendar2','label' => 'Member Since',   'value' => $memberSince],
                    ];
                    foreach ($infoFields as $f):
                    ?>
                    <div class="info-row">
                        <div class="info-icon"><?= svgIcon($f['icon'], 14, '#94a3b8') ?></div>
                        <div style="min-width:0;">
                            <div class="info-label"><?= $f['label'] ?></div>
                            <div class="info-value"><?= esc($f['value']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Security -->
                <div class="card card-p-lg">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:#fef3c7;"><?= svgIcon('shield', 16, '#d97706') ?></div>
                            <div>
                                <div class="card-title">Security</div>
                                <div class="card-sub">Password and account protection</div>
                            </div>
                        </div>
                    </div>
                    <div class="danger-row">
                        <div style="min-width:0;">
                            <p style="font-size:.83rem;font-weight:600;color:#0f172a;">Password</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Last changed — update regularly for safety</p>
                        </div>
                        <button onclick="openModal()" class="danger-btn" style="background:var(--indigo-light);border-color:var(--indigo-border);color:var(--indigo);" onmouseover="this.style.background='var(--indigo)';this.style.color='white'" onmouseout="this.style.background='var(--indigo-light)';this.style.color='var(--indigo)'">
                            Change
                        </button>
                    </div>
                    <div class="danger-row" style="border-bottom:none;padding-bottom:0;">
                        <div style="min-width:0;">
                            <p style="font-size:.83rem;font-weight:600;color:#0f172a;">Account Access</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">You are currently signed in as a Resident</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;padding:6px 12px;background:#dcfce7;border-radius:999px;flex-shrink:0;">
                            <?= svgIcon('check', 10, '#16a34a') ?>
                            <span style="font-size:.65rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.05em;">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Tip Banner -->
                <div class="tip-banner">
                    <div class="tip-icon"><?= svgIcon('lightbulb', 18, 'white') ?></div>
                    <div style="position:relative;z-index:1;">
                        <h5 style="font-size:.88rem;font-weight:700;color:white;line-height:1.3;">Keep your info up to date</h5>
                        <p style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;line-height:1.5;">Ensure your contact details are correct so reservations and notifications reach you properly.</p>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card card-p-lg">
                    <div class="card-head" style="margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:#fef2f2;"><?= svgIcon('trash', 15, '#dc2626') ?></div>
                            <div>
                                <div class="card-title" style="color:#dc2626;">Danger Zone</div>
                                <div class="card-sub">Irreversible account actions</div>
                            </div>
                        </div>
                    </div>
                    <div class="danger-row" style="padding-top:12px;border-bottom:none;padding-bottom:0;">
                        <div style="min-width:0;">
                            <p style="font-size:.83rem;font-weight:600;color:#0f172a;">Delete Account</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Permanently remove your account and all data</p>
                        </div>
                        <!-- ✅ This button now opens the delete confirmation modal -->
                        <button class="danger-btn" onclick="openDeleteModal()">Delete</button>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <script>
        /* ── Edit Modal ── */
        function openModal() {
            document.getElementById('editModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('editModal').classList.remove('show');
            document.body.style.overflow = '';
        }
        function handleModalBack(e) {
            if (e.target === document.getElementById('editModal')) closeModal();
        }

        /* ── Delete Modal ── */
        function openDeleteModal() {
            // Reset state each time it opens
            document.getElementById('deleteConfirmInput').value = '';
            document.getElementById('deleteConfirmInput').classList.remove('delete-confirm-input-error');
            document.getElementById('deleteSubmitBtn').disabled = true;
            document.getElementById('deleteHint').textContent = 'This field is case-sensitive';
            document.getElementById('deleteHint').style.color = '#dc2626';

            document.getElementById('deleteModal').classList.add('show');
            document.body.style.overflow = 'hidden';

            // Auto-focus input after animation
            setTimeout(() => document.getElementById('deleteConfirmInput').focus(), 200);
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            document.body.style.overflow = '';
        }
        function handleDeleteBack(e) {
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
        }

        /* ── Delete confirm input check ── */
        function checkDeleteConfirm(val) {
            const btn  = document.getElementById('deleteSubmitBtn');
            const hint = document.getElementById('deleteHint');
            const inp  = document.getElementById('deleteConfirmInput');

            if (val === 'DELETE') {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                inp.classList.remove('delete-confirm-input-error');
                inp.style.borderColor = '#86efac';
                inp.style.background  = '#f0fdf4';
                hint.textContent = '✓ Confirmed — you may now delete your account';
                hint.style.color = '#16a34a';
            } else {
                btn.disabled = true;
                btn.style.opacity = '0.4';
                btn.style.cursor = 'not-allowed';
                if (val.length > 0) {
                    inp.classList.add('delete-confirm-input-error');
                    hint.textContent = 'Must be exactly "DELETE" in uppercase';
                    hint.style.color = '#dc2626';
                } else {
                    inp.classList.remove('delete-confirm-input-error');
                    inp.style.borderColor = '#fecaca';
                    inp.style.background  = '';
                    hint.textContent = 'This field is case-sensitive';
                    hint.style.color = '#dc2626';
                }
            }
        }

        /* ── Prevent accidental double-submit on delete ── */
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            const val = document.getElementById('deleteConfirmInput').value;
            if (val !== 'DELETE') {
                e.preventDefault();
                return;
            }
            const btn = document.getElementById('deleteSubmitBtn');
            const txt = document.getElementById('deleteSubmitTxt');
            btn.disabled = true;
            txt.textContent = 'Deleting…';
            btn.style.opacity = '0.7';
        });

        /* ── Global ESC key handler ── */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });

        /* ── Password strength ── */
        function checkPw(val) {
            const fill = document.getElementById('pwFill');
            const hint = document.getElementById('pwHint');
            if (!val) { fill.style.width = '0%'; fill.style.background = '#e2e8f0'; hint.textContent = 'Minimum 8 characters'; hint.style.color = '#94a3b8'; return; }
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            const labels = ['Too short','Weak','Fair','Good','Strong'];
            const colors = ['#e2e8f0','#f87171','#fbbf24','#34d399','#10b981'];
            fill.style.width = (score * 25) + '%';
            fill.style.background = colors[score];
            hint.textContent = labels[score];
            hint.style.color = colors[score];
        }

        /* ── Dark mode ── */
        function toggleDark() {
            const isDark = document.body.classList.toggle('dark');
            const icon = document.getElementById('dark-icon');
            icon.innerHTML = isDark
                ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`
                : `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('dark');
                const icon = document.getElementById('dark-icon');
                if (icon) icon.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
            }
            document.documentElement.classList.remove('dark-pre');

            // Responsive grid
            function checkGrid() {
                const grid = document.getElementById('profileGrid');
                if (!grid) return;
                grid.style.gridTemplateColumns = window.innerWidth < 900 ? '1fr' : 'minmax(0,1fr) minmax(0,1.6fr)';
            }
            checkGrid();
            window.addEventListener('resize', checkGrid);
        });
    </script>
</body>
</html>