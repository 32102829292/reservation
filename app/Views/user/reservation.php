<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>New Reservation | <?= esc($user_name ?? 'User') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark-pre');
        })();
    </script>
    <style>
        *,
        *::before,
        *::after {
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
            --text: #0f172a;
            --text-muted: #64748b;
            --text-sub: #94a3b8;
            --border: rgba(99, 102, 241, .12);
            --input-bg: #f8fafc;
            --input-border: rgba(99, 102, 241, .15);
            --font: 'Plus Jakarta Sans', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --shadow-sm: 0 1px 4px rgba(15, 23, 42, .07), 0 1px 2px rgba(15, 23, 42, .04);
            --shadow-md: 0 4px 16px rgba(15, 23, 42, .09), 0 2px 4px rgba(15, 23, 42, .04);
            --shadow-lg: 0 12px 40px rgba(15, 23, 42, .12), 0 4px 8px rgba(15, 23, 42, .06);
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 20px;
            --r-xl: 24px;
            --ease: .18s cubic-bezier(.4, 0, .2, 1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }

        /* ── Dark mode variable overrides ── */
        body.dark {
            --bg: #060e1e;
            --card: #0b1628;
            --indigo-light: rgba(55, 48, 163, .15);
            --indigo-border: rgba(99, 102, 241, .3);
            --text: #e2eaf8;
            --text-muted: #a5b4fc;
            --text-sub: #4a6fa5;
            --border: rgba(99, 102, 241, .18);
            --input-bg: #101e35;
            --input-border: rgba(99, 102, 241, .22);
            color: #e2eaf8;
        }

        html {
            height: 100%;
            font-size: 16px;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            transition: background .2s, color .2s;
        }

        html.dark-pre body {
            background: #060e1e;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 268px;
            flex-shrink: 0;
            padding: 18px 14px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
        }

        .sidebar-inner {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: background .2s, border-color .2s;
        }

        .sidebar-top {
            padding: 22px 18px 16px;
            border-bottom: 1px solid var(--border);
        }

        .brand-tag {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--text-sub);
            margin-bottom: 5px;
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.03em;
            line-height: 1.1;
        }

        .brand-name em {
            font-style: normal;
            color: var(--indigo);
        }

        .brand-sub {
            font-size: .7rem;
            color: var(--text-sub);
            margin-top: 3px;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            scrollbar-width: none;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 2px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 2px;
        }

        .nav-lbl {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--text-sub);
            padding: 10px 10px 5px;
            opacity: .7;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: all var(--ease);
        }

        .nav-link:hover {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .nav-link.active {
            background: var(--indigo);
            color: #fff;
            box-shadow: 0 4px 14px rgba(55, 48, 163, .32);
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(99, 102, 241, .08);
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, .15);
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: var(--indigo-light);
        }

        .sidebar-footer {
            padding: 10px 10px 12px;
            border-top: 1px solid var(--border);
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 600;
            color: var(--text-sub);
            text-decoration: none;
            transition: all var(--ease);
        }

        .logout-link:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: var(--card);
            border-top: 1px solid var(--border);
            height: var(--mob-nav-total);
            z-index: 200;
            box-shadow: 0 -4px 20px rgba(55, 48, 163, .1);
        }

        .mobile-scroll-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            height: var(--mob-nav-h);
            width: 100%;
        }

        .mob-nav-item {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--text-muted);
            position: relative;
            transition: background .15s, color .15s;
        }

        .mob-nav-item:hover,
        .mob-nav-item.active {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .mob-nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--indigo);
            border-radius: 50%;
        }

        .mob-logout {
            color: var(--text-sub);
        }

        .mob-logout:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        @media(max-width:1023px) {
            .sidebar {
                display: none !important;
            }

            .mobile-nav-pill {
                display: flex !important;
            }

            .main-area {
                padding-bottom: calc(var(--mob-nav-total) + 16px) !important;
            }
        }

        @media(min-width:1024px) {
            .sidebar {
                display: flex !important;
            }

            .mobile-nav-pill {
                display: none !important;
            }
        }

        /* ── Main ── */
        .main-area {
            flex: 1;
            min-width: 0;
            padding: 28px 28px 40px;
            overflow-x: hidden;
        }

        @media(max-width:639px) {
            .main-area {
                padding: 16px 14px 0;
            }
        }

        /* ── Page header ── */
        .page-eyebrow {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--text-sub);
            margin-bottom: 4px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.04em;
            line-height: 1.1;
        }

        .page-sub {
            font-size: .8rem;
            color: var(--text-sub);
            margin-top: 4px;
            font-weight: 500;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            font-size: .8rem;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            transition: all var(--ease);
            box-shadow: var(--shadow-sm);
        }

        .back-btn:hover {
            background: var(--indigo-light);
            border-color: var(--indigo-border);
            color: var(--indigo);
        }

        /* ── Flash ── */
        .flash {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 13px 18px;
            font-weight: 600;
            border-radius: var(--r-md);
            font-size: .88rem;
            border: 1px solid;
        }

        .flash-ok {
            background: var(--indigo-light);
            border-color: var(--indigo-border);
            color: var(--indigo);
        }

        .flash-err {
            background: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .flash-info {
            background: #fef3c7;
            border-color: #fde68a;
            color: #92400e;
        }

        body.dark .flash-ok {
            background: rgba(55, 48, 163, .2);
            border-color: rgba(99, 102, 241, .3);
            color: #a5b4fc;
        }

        body.dark .flash-err {
            background: rgba(220, 38, 38, .1);
            border-color: rgba(248, 113, 113, .3);
            color: #f87171;
        }

        body.dark .flash-info {
            background: rgba(180, 83, 9, .15);
            border-color: rgba(251, 191, 36, .25);
            color: #fcd34d;
        }

        /* ── Form card ── */
        .form-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 28px;
            max-width: 760px;
            margin: 0 auto;
            transition: background .2s, border-color .2s;
        }

        @media(max-width:639px) {
            .form-card {
                padding: 18px 16px;
                border-radius: var(--r-lg);
            }
        }

        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .section-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.01em;
        }

        .section-sub {
            font-size: .7rem;
            color: var(--text-sub);
            margin-top: 2px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.75rem 0;
        }

        .field-label {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--text-sub);
            display: block;
            margin-bottom: 6px;
        }

        /* ── Inputs — use CSS vars so dark mode works ── */
        input,
        select,
        textarea {
            width: 100%;
            padding: .75rem 1rem;
            border: 1px solid var(--input-border);
            font-size: .88rem;
            transition: all var(--ease);
            background: var(--input-bg);
            border-radius: var(--r-sm);
            font-family: var(--font);
            color: var(--text);
            outline: none;
            -webkit-appearance: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #818cf8;
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        input[readonly] {
            background: var(--input-bg);
            color: var(--text-sub);
            cursor: not-allowed;
            opacity: .75;
        }

        select option {
            background: var(--card);
            color: var(--text);
        }

        /* ── PC section ── */
        .pc-section {
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            border-radius: var(--r-md);
            padding: 1.25rem;
        }

        .pc-section-lbl {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--indigo);
            display: block;
            margin-bottom: 10px;
        }

        .pc-btn {
            padding: .6rem .75rem;
            border-radius: 9px;
            font-size: .75rem;
            font-weight: 700;
            border: 1px solid var(--indigo-border);
            background: var(--card);
            color: var(--text-muted);
            transition: all var(--ease);
            cursor: pointer;
            font-family: var(--font);
        }

        .pc-btn:hover {
            border-color: var(--indigo);
            color: var(--indigo);
        }

        .pc-btn.selected-pc {
            background: var(--indigo) !important;
            color: white !important;
            border-color: var(--indigo) !important;
            box-shadow: 0 4px 10px rgba(55, 48, 163, .3);
        }

        /* ── Availability badges ── */
        .available {
            background: #dcfce7;
            color: #166534;
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
        }

        .unavailable {
            background: #fee2e2;
            color: #991b1b;
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
        }

        /* ── Submit button ── */
        .btn-primary {
            background: var(--indigo);
            color: white;
            border: none;
            padding: .85rem 1.75rem;
            border-radius: var(--r-md);
            font-weight: 700;
            font-size: .88rem;
            cursor: pointer;
            transition: all var(--ease);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(55, 48, 163, .28);
            touch-action: manipulation;
        }

        .btn-primary:hover {
            background: #312e81;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(55, 48, 163, .35);
        }

        /* ── Notification elements ── */
        .notif-btn {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 150;
            width: 44px;
            height: 44px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all var(--ease);
        }

        .notif-btn:hover {
            background: var(--indigo-light);
            border-color: var(--indigo-border);
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: .55rem;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 999px;
            min-width: 17px;
            text-align: center;
            border: 2px solid var(--bg);
            line-height: 1.3;
        }

        .notif-dd {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 320px;
            background: var(--card);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-lg), 0 0 0 1px var(--border);
            z-index: 1000;
            display: none;
            overflow: hidden;
        }

        .notif-dd.show {
            display: block;
            animation: dropIn .15s ease;
        }

        .notif-item {
            padding: .85rem 1.1rem;
            border-bottom: 1px solid var(--border);
            transition: background .15s;
            cursor: pointer;
            color: var(--text);
        }

        .notif-item:hover {
            background: var(--input-bg);
        }

        .notif-item.unread {
            background: var(--indigo-light);
        }

        @media(max-width:479px) {
            .notif-btn {
                top: 16px;
                right: 16px;
            }

            .notif-dd {
                left: 12px;
                right: 12px;
                width: auto;
                top: 72px;
            }
        }

        /* ── Modal ── */
        .modal-back {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .52);
            backdrop-filter: blur(6px);
            z-index: 300;
            padding: 1.5rem;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
        }

        .modal-back.show {
            display: flex;
            animation: fadeIn .15s ease;
        }

        .modal-box {
            background: var(--card);
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 460px;
            padding: 28px;
            margin: auto;
            animation: slideUp .2s ease;
            max-height: calc(100dvh - 3rem);
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
        }

        .mrow {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .55rem 0;
            border-bottom: 1px solid var(--border);
            gap: 1rem;
        }

        .mrow:last-child {
            border-bottom: none;
        }

        .mrow-label {
            font-size: .6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--text-sub);
            flex-shrink: 0;
        }

        .mrow-value {
            font-weight: 600;
            color: var(--text);
            font-size: .84rem;
            text-align: right;
        }

        .modal-summary-box {
            background: var(--input-bg);
            border-radius: var(--r-md);
            padding: 16px;
            border: 1px solid var(--border);
            margin-bottom: 16px;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.02em;
        }

        .modal-cancel-btn {
            flex: 1;
            padding: .75rem;
            background: var(--input-bg);
            border-radius: var(--r-sm);
            font-weight: 700;
            color: var(--text-muted);
            border: 1px solid var(--border);
            cursor: pointer;
            font-family: var(--font);
            font-size: .85rem;
            transition: background .15s;
        }

        .modal-cancel-btn:hover {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        /* ── Toast ── */
        .toast-wrap {
            position: fixed;
            top: 80px;
            right: 24px;
            left: 24px;
            z-index: 2000;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        @media(min-width:640px) {
            .toast-wrap {
                left: auto;
                width: 320px;
            }
        }

        .toast {
            background: #0f172a;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3);
            margin-bottom: .65rem;
            pointer-events: auto;
            width: 100%;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: slideUp .3s ease;
        }

        /* ── Grid helpers ── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
        }

        @media(max-width:639px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .grid-3 {
                grid-template-columns: 1fr;
            }
        }

        /* ── Utility ── */
        .hidden {
            display: none !important;
        }

        @keyframes dropIn {
            from {
                opacity: 0;
                transform: translateY(-4px) scale(.98)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'fa-house',      'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'fa-plus',       'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',   'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books',            'icon' => 'fa-book-open',  'label' => 'Library',         'key' => 'books'],
        ['url' => '/profile',          'icon' => 'fa-user',       'label' => 'Profile',         'key' => 'profile'],
    ];
    $avatarLetter = strtoupper(mb_substr(trim($user['name'] ?? 'U'), 0, 1));
    ?>

    <!-- Notification dropdown -->
    <div id="notificationDropdown" class="notif-dd">
        <div style="padding:11px 13px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
            <button onclick="markAllAsRead()" style="font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
        </div>
        <div id="notificationList" style="max-height:280px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
    </div>

    <!-- Toast container -->
    <div id="toastContainer" class="toast-wrap"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">Resident Portal</div>
                <div class="brand-name">my<em>Space.</em></div>
                <div class="brand-sub">Community Management</div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-lbl">Menu</div>
                <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:14px;color:<?= $active ? 'white' : 'var(--text-muted)' ?>;"></i></div>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:14px;color:#f87171;"></i></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <!-- Mobile Nav -->
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                <a href="<?= base_url($item['url']) ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= esc($item['label']) ?>">
                    <i class="fa-solid <?= $item['icon'] ?>" style="font-size:20px;"></i>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-logout" title="Sign Out">
                <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:20px;color:#f87171;"></i>
            </a>
        </div>
    </nav>

    <main class="main-area">
        <!-- Topbar -->
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap;">
            <div>
                <div class="page-eyebrow">New Booking</div>
                <div class="page-title">New Reservation</div>
                <div class="page-sub">Book a resource for your upcoming visit.</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
                <!-- Notification bell -->
                <div class="notif-btn" onclick="toggleNotifications()" style="position:relative;">
                    <i class="fa-regular fa-bell" style="font-size:.9rem;color:var(--text-muted);"></i>
                    <span class="notif-badge" id="notificationBadge" style="display:none;">0</span>
                </div>
                <a href="<?= base_url('/reservation-list') ?>" class="back-btn">
                    <i class="fa-solid fa-chevron-left" style="font-size:11px;"></i> My Reservations
                </a>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash flash-err"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash flash-ok"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (isset($remainingReservations) && $remainingReservations > 0): ?>
            <div class="flash flash-info"><i class="fa-solid fa-info-circle"></i>
                You have <?= $remainingReservations ?> reservation<?= $remainingReservations != 1 ? 's' : '' ?> remaining this period (max 3 per 2 weeks).
            </div>
        <?php endif; ?>
        <?php if (isset($isBlocked) && $isBlocked): ?>
            <div class="flash flash-err"><i class="fa-solid fa-ban"></i>
                You are temporarily blocked from making reservations until <?= date('F j, Y', strtotime($isBlocked['blocked_until'])) ?>.
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form id="reservationForm" method="POST" action="<?= base_url('reservation/create') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" id="finalUserId" value="<?= $user['id'] ?? '' ?>">
                <input type="hidden" name="visitor_name" id="finalVisitorName" value="<?= esc($user['name'] ?? '') ?>">
                <input type="hidden" name="user_email" id="finalUserEmail" value="<?= esc($user['email'] ?? '') ?>">
                <input type="hidden" name="visitor_type" id="finalVisitorType" value="User">
                <input type="hidden" name="purpose" id="finalPurpose">
                <input type="hidden" name="pcs" id="finalPcs" value="">

                <!-- Your Details -->
                <div style="margin-bottom:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                        <div class="section-icon"><i class="fa-solid fa-user" style="font-size:14px;"></i></div>
                        <div>
                            <div class="section-title">Your Details</div>
                            <div class="section-sub">Auto-filled from your account</div>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div>
                            <label class="field-label">Full Name</label>
                            <input type="text" value="<?= esc($user['name'] ?? '') ?>" readonly>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" value="<?= esc($user['email'] ?? '') ?>" readonly>
                        </div>
                    </div>
                    <p style="font-size:.72rem;color:var(--indigo);margin-top:8px;display:flex;align-items:center;gap:5px;font-weight:600;">
                        <i class="fa-solid fa-circle-check"></i> Booking as yourself
                    </p>
                </div>

                <hr class="section-divider">

                <!-- Resource & Schedule -->
                <div style="margin-bottom:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                        <div class="section-icon"><i class="fa-solid fa-calendar-days" style="font-size:14px;"></i></div>
                        <div>
                            <div class="section-title">Resource & Schedule</div>
                            <div class="section-sub">Choose your resource, date and time</div>
                        </div>
                    </div>

                    <!-- Resource select -->
                    <div style="margin-bottom:16px;">
                        <label class="field-label">Select Resource</label>
                        <select id="resourceSelect" name="resource_id" required onchange="handleResourceChange(this)">
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>"
                                    data-name="<?= esc($res['name']) ?>"
                                    data-type="<?= $res['type'] ?? '' ?>"
                                    data-has-pcs="<?= (strpos(strtolower($res['name']), 'computer') !== false || strpos(strtolower($res['name']), 'pc') !== false || strpos(strtolower($res['name']), 'lab') !== false) ? '1' : '0' ?>">
                                    <?= esc($res['name']) ?><?php if (!empty($res['capacity'])): ?> (Capacity: <?= $res['capacity'] ?>)<?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- PC section — hidden until a computer/lab resource is chosen -->
                    <div id="pcSection" class="hidden" style="margin-bottom:16px;">
                        <div class="pc-section">
                            <label class="pc-section-lbl">
                                <i class="fa-solid fa-desktop" style="margin-right:5px;"></i>Select Workstation(s)
                            </label>
                            <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(70px,1fr));gap:8px;">
                                <?php foreach ($pcs ?? [] as $pc):
                                    $num = esc($pc['pc_number'] ?? $pc['name'] ?? '');
                                    if (!empty($num)): ?>
                                        <button type="button" onclick="togglePc('<?= $num ?>',this)" data-pc="<?= $num ?>" class="pc-btn"><?= $num ?></button>
                                <?php endif;
                                endforeach; ?>
                            </div>
                            <p style="font-size:.68rem;color:var(--indigo);font-weight:600;margin-top:10px;display:flex;align-items:center;gap:4px;">
                                <i class="fa-solid fa-circle-info"></i> Selected: <span id="pcSelectedLabel" style="font-family:var(--mono)">None</span>
                            </p>
                        </div>
                    </div>

                    <!-- Date & time -->
                    <div class="grid-3" style="margin-bottom:16px;">
                        <div>
                            <label class="field-label">Date</label>
                            <input type="date" name="reservation_date" id="resDate" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" onchange="checkAvailability()" required>
                        </div>
                        <div>
                            <label class="field-label">Start Time</label>
                            <input type="time" name="start_time" id="startTime" onchange="checkAvailability()" required>
                        </div>
                        <div>
                            <label class="field-label">End Time</label>
                            <input type="time" name="end_time" id="endTime" onchange="checkAvailability()" required>
                        </div>
                    </div>

                    <!-- Availability message -->
                    <div id="availabilityMsg" class="hidden" style="margin-bottom:14px;padding:10px 14px;border-radius:var(--r-sm);font-size:.82rem;font-weight:600;"></div>

                    <!-- Purpose -->
                    <div style="margin-bottom:16px;">
                        <label class="field-label">Purpose of Visit</label>
                        <select id="purposeSelect" name="purpose" required onchange="handlePurposeChange(this)">
                            <option value="">— Select purpose —</option>
                            <?php foreach ($purposes ?? ['Work', 'Personal', 'Study', 'SK Activity', 'Others'] as $purpose): ?>
                                <option value="<?= esc($purpose) ?>"><?= esc($purpose) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="purposeOtherWrap" class="hidden">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" placeholder="Describe your purpose...">
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;padding-top:8px;">
                    <button type="button" onclick="previewReservation()" class="btn-primary" style="width:100%;">
                        <i class="fa-solid fa-eye"></i> Preview & Confirm
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal-back" onclick="handleBackdrop(event)">
        <div class="modal-box">
            <div style="text-align:center;margin-bottom:20px;">
                <div style="width:52px;height:52px;background:#fef3c7;border:2px solid #fde68a;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fa-solid fa-clock" style="color:#d97706;font-size:1.1rem;"></i>
                </div>
                <h3 class="modal-title">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:var(--text-sub);margin-top:4px;">Review your booking details</p>
                <p style="font-size:.72rem;color:#d97706;font-weight:700;margin-top:6px;">Your reservation will be pending approval</p>
            </div>
            <div class="modal-summary-box">
                <div class="mrow"><span class="mrow-label">Name</span><span class="mrow-value" id="mName"><?= esc($user['name'] ?? '') ?></span></div>
                <div class="mrow"><span class="mrow-label">Email</span><span class="mrow-value" id="mEmail"><?= esc($user['email'] ?? '') ?></span></div>
                <div class="mrow"><span class="mrow-label">Resource</span><span class="mrow-value" id="mAsset"></span></div>
                <div class="mrow"><span class="mrow-label">Workstation</span><span class="mrow-value" id="mStation"></span></div>
                <div class="mrow"><span class="mrow-label">Date</span><span class="mrow-value" id="mDate"></span></div>
                <div class="mrow"><span class="mrow-label">Time</span><span class="mrow-value" id="mTime"></span></div>
                <div class="mrow"><span class="mrow-label">Purpose</span><span class="mrow-value" id="mPurpose"></span></div>
            </div>
            <div style="background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-md);padding:12px 14px;margin-bottom:16px;text-align:center;">
                <i class="fa-regular fa-bell" style="color:var(--indigo);margin-bottom:6px;font-size:1rem;display:block;"></i>
                <p style="font-size:.75rem;color:var(--indigo);font-weight:500;">You'll receive a notification once your reservation is approved.</p>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeModal()" class="modal-cancel-btn">Cancel</button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" class="btn-primary" style="flex:2;">
                    <i class="fa-solid fa-check"></i> Submit Request
                </button>
            </div>
        </div>
    </div>

    <script>
        const currentUser = {
            id: <?= $user['id'] ?? 'null' ?>,
            name: "<?= esc($user['name']  ?? '', 'js') ?>",
            email: "<?= esc($user['email'] ?? '', 'js') ?>"
        };
        let selectedPcs = [],
            selectedResource = null;
        let notifications = [<?php if (!empty($recentApprovals)): ?><?php foreach ($recentApprovals as $approval): ?> {
            id: <?= $approval['id'] ?>,
            title: 'Reservation Approved!',
            message: 'Your reservation for <?= esc($approval['resource_name']) ?> on <?= date('M j, Y', strtotime($approval['reservation_date'])) ?> has been approved.',
            time: '<?= $approval['approved_at'] ?? date('Y-m-d H:i:s') ?>',
            read: false
        }, <?php endforeach; ?><?php endif; ?>];
        let unreadCount = notifications.filter(n => !n.read).length,
            checkInterval, lastCheckTime = new Date().toISOString();

        document.addEventListener('DOMContentLoaded', function() {
            // Apply dark mode immediately
            if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark');
            document.documentElement.classList.remove('dark-pre');

            if ('Notification' in window) Notification.requestPermission();
            renderNotifications();
            updateBadge();
            checkInterval = setInterval(checkForNewApprovals, 30000);
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) checkForNewApprovals();
            });
            notifications.forEach(n => {
                if (!n.read) showToast(n);
            });
        });

        /* ── Resource change — show/hide PC section ── */
        function handleResourceChange(select) {
            const opt = select.options[select.selectedIndex];
            const hasPcs = opt?.dataset?.hasPcs === '1';

            const pcSection = document.getElementById('pcSection');
            if (hasPcs) {
                pcSection.classList.remove('hidden');
            } else {
                pcSection.classList.add('hidden');
            }

            // Reset PC selection
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(b => b.classList.remove('selected-pc'));

            selectedResource = {
                id: select.value,
                name: opt?.dataset?.name || '',
                hasPcs
            };
            checkAvailability();
        }

        /* ── PC toggle ── */
        function togglePc(num, btn) {
            const i = selectedPcs.indexOf(num);
            if (i === -1) {
                selectedPcs.push(num);
                btn.classList.add('selected-pc');
            } else {
                selectedPcs.splice(i, 1);
                btn.classList.remove('selected-pc');
            }
            updatePcHidden();
        }

        function updatePcHidden() {
            document.getElementById('finalPcs').value = selectedPcs.join(', ');
            document.getElementById('pcSelectedLabel').textContent = selectedPcs.length ? selectedPcs.join(', ') : 'None';
        }

        /* ── Purpose change ── */
        function handlePurposeChange(select) {
            const isOther = select.value === 'Others';
            const wrap = document.getElementById('purposeOtherWrap');
            if (isOther) {
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
                document.getElementById('purposeOther').value = '';
            }
        }

        /* ── Availability check ── */
        function checkAvailability() {
            const rid = document.getElementById('resourceSelect').value,
                date = document.getElementById('resDate').value,
                st = document.getElementById('startTime').value,
                et = document.getElementById('endTime').value;
            const m = document.getElementById('availabilityMsg');
            if (!rid || !date || !st || !et) {
                m.classList.add('hidden');
                return;
            }

            m.classList.remove('hidden', 'available', 'unavailable');
            m.textContent = 'Checking availability…';
            m.style.cssText = 'background:var(--input-bg);color:var(--text-sub);margin-bottom:14px;padding:10px 14px;border-radius:var(--r-sm);font-size:.82rem;font-weight:600;';

            fetch('<?= base_url("reservation/check-availability") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    resource_id: rid,
                    date,
                    start_time: st,
                    end_time: et,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            }).then(r => r.json()).then(data => {
                m.style.cssText = '';
                m.textContent = data.message;
                m.classList.add(data.available ? 'available' : 'unavailable');
            }).catch(() => {
                m.textContent = 'Error checking availability';
                m.classList.add('unavailable');
            });
        }

        /* ── Preview ── */
        function previewReservation() {
            const re = document.getElementById('resourceSelect'),
                rid = re.value,
                rn = re.options[re.selectedIndex]?.text || '—',
                date = document.getElementById('resDate').value,
                st = document.getElementById('startTime').value,
                et = document.getElementById('endTime').value,
                pv = document.getElementById('purposeSelect').value,
                po = document.getElementById('purposeOther').value.trim(),
                pf = pv === 'Others' && po ? `Others - ${po}` : pv,
                hasPc = !document.getElementById('pcSection').classList.contains('hidden');

            if (!rid) {
                alert('Please select a resource');
                return;
            }
            if (hasPc && selectedPcs.length === 0) {
                alert('Please select at least one workstation');
                return;
            }
            if (!date) {
                alert('Please select a date');
                return;
            }
            if (!st) {
                alert('Please enter start time');
                return;
            }
            if (!et) {
                alert('Please enter end time');
                return;
            }
            if (!pv) {
                alert('Please select a purpose');
                return;
            }

            document.getElementById('finalPurpose').value = pf;
            document.getElementById('mName').textContent = currentUser.name;
            document.getElementById('mEmail').textContent = currentUser.email;
            document.getElementById('mAsset').textContent = rn;
            document.getElementById('mStation').textContent = selectedPcs.length ? selectedPcs.join(', ') : 'None';
            document.getElementById('mDate').textContent = date;
            document.getElementById('mTime').textContent = `${st} – ${et}`;
            document.getElementById('mPurpose').textContent = pf;
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting…';
            document.getElementById('reservationForm').submit();
        }

        function openModal() {
            document.getElementById('confirmModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('show');
            document.body.style.overflow = '';
            const btn = document.getElementById('confirmBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Submit Request';
        }

        function handleBackdrop(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        document.getElementById('resDate').setAttribute('min', new Date().toISOString().split('T')[0]);
        window.addEventListener('beforeunload', () => {
            if (checkInterval) clearInterval(checkInterval);
        });

        /* ── Notifications ── */
        function checkForNewApprovals() {
            fetch('<?= base_url("reservation/check-new-approvals") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    user_id: currentUser.id,
                    last_check: lastCheckTime
                })
            }).then(r => r.json()).then(data => {
                if (data.new_approvals?.length) {
                    data.new_approvals.forEach(a => {
                        const n = {
                            id: a.id,
                            title: 'Reservation Approved!',
                            message: `Your reservation for ${a.resource_name} on ${new Date(a.date).toLocaleDateString()} has been approved.`,
                            time: new Date().toISOString(),
                            read: false
                        };
                        notifications.unshift(n);
                        unreadCount++;
                        updateBadge();
                        renderNotifications();
                        showPush(n);
                        showToast(n);
                    });
                    lastCheckTime = new Date().toISOString();
                }
            }).catch(e => console.error(e));
        }

        function showPush(n) {
            if ('Notification' in window && Notification.permission === 'granted')
                new Notification(n.title, {
                    body: n.message,
                    icon: '/favicon.ico'
                });
        }

        function showToast(n) {
            const c = document.getElementById('toastContainer'),
                tid = 't' + Date.now(),
                t = document.createElement('div');
            t.id = tid;
            t.className = 'toast';
            t.innerHTML = `<div style="width:28px;height:28px;background:rgba(99,102,241,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-check" style="color:#818cf8;font-size:11px;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.78rem;color:white;">${n.title}</p><p style="font-size:.68rem;color:rgba(255,255,255,.6);margin-top:2px;">${n.message}</p></div><button onclick="document.getElementById('${tid}').remove()" style="background:rgba(255,255,255,.08);border:none;border-radius:6px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;color:rgba(255,255,255,.6);"><i class="fa-solid fa-xmark" style="font-size:9px;"></i></button>`;
            c.appendChild(t);
            setTimeout(() => {
                const el = document.getElementById(tid);
                if (el) el.remove();
            }, 5000);
        }

        function toggleNotifications() {
            document.getElementById('notificationDropdown').classList.toggle('show');
        }

        function markAllAsRead() {
            notifications.forEach(n => n.read = true);
            unreadCount = 0;
            updateBadge();
            renderNotifications();
        }

        function markAsRead(id) {
            const n = notifications.find(n => n.id === id);
            if (n && !n.read) {
                n.read = true;
                unreadCount = Math.max(0, unreadCount - 1);
                updateBadge();
                renderNotifications();
            }
        }

        function updateBadge() {
            const b = document.getElementById('notificationBadge');
            if (unreadCount > 0) {
                b.style.display = 'block';
                b.textContent = unreadCount > 9 ? '9+' : unreadCount;
            } else b.style.display = 'none';
        }

        function renderNotifications() {
            const l = document.getElementById('notificationList');
            if (!notifications.length) {
                l.innerHTML = '<div style="text-align:center;padding:24px;font-size:.8rem;color:var(--text-sub);">All caught up!</div>';
                return;
            }
            l.innerHTML = notifications.sort((a, b) => new Date(b.time) - new Date(a.time)).map(n => `
                <div class="notif-item ${!n.read?'unread':''}" onclick="markAsRead(${n.id})">
                    <div style="display:flex;align-items:flex-start;gap:9px;">
                        <div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-check" style="color:var(--indigo);font-size:10px;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:700;font-size:.8rem;color:var(--text);">${n.title}</p>
                            <p style="font-size:.7rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.message}</p>
                            <p style="font-size:.62rem;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p>
                        </div>
                        ${!n.read?'<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:3px;"></span>':''}
                    </div>
                </div>`).join('');
        }
        const timeAgo = t => {
            const s = Math.floor((Date.now() - new Date(t)) / 1000);
            if (s < 60) return 'Just now';
            if (s < 3600) return `${Math.floor(s/60)}m ago`;
            if (s < 86400) return `${Math.floor(s/3600)}h ago`;
            return `${Math.floor(s/86400)}d ago`;
        };
        document.addEventListener('click', e => {
            const dd = document.getElementById('notificationDropdown'),
                bell = document.querySelector('.notif-btn');
            if (bell && !bell.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('show');
        });
    </script>
</body>

</html>