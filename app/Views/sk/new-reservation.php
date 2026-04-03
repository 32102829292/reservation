<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#15803d">
    <script>
        (function() {
            if (localStorage.getItem('sk_theme') === 'dark') document.documentElement.classList.add('dark-pre')
        })();
    </script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent
        }

        :root {
            --green: #15803d;
            --green-mid: #16a34a;
            --green-light: #f0fdf4;
            --green-border: #bbf7d0;
            --bg: #f0f4f1;
            --card: #ffffff;
            --font: 'Plus Jakarta Sans', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --shadow-sm: 0 1px 4px rgba(15, 23, 42, .07), 0 1px 2px rgba(15, 23, 42, .04);
            --shadow-md: 0 4px 16px rgba(15, 23, 42, .09), 0 2px 4px rgba(15, 23, 42, .04);
            --shadow-lg: 0 12px 40px rgba(15, 23, 42, .12), 0 4px 8px rgba(15, 23, 42, .06);
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 20px;
            --r-xl: 24px;
            --sidebar-w: 268px;
            --ease: .18s cubic-bezier(.4, 0, .2, 1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }

        html {
            height: 100%;
            height: 100dvh;
            font-size: 16px
        }

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
            -webkit-font-smoothing: antialiased
        }

        html.dark-pre body {
            background: #031a0a
        }

        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            padding: 18px 14px;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column
        }

        .sidebar-inner {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(22, 163, 74, .12);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-md)
        }

        .sidebar-top {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(22, 163, 74, .08)
        }

        .brand-tag {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 5px
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.03em;
            line-height: 1.1
        }

        .brand-name em {
            font-style: normal;
            color: var(--green)
        }

        .brand-sub {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: 3px
        }

        .user-card {
            margin: 12px 12px 0;
            background: var(--green-light);
            border-radius: var(--r-md);
            padding: 12px 14px;
            border: 1px solid var(--green-border)
        }

        .user-avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .85rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(21, 128, 61, .3)
        }

        .user-name-txt {
            font-size: .8rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -.01em
        }

        .user-role-txt {
            font-size: .68rem;
            color: #16a34a;
            font-weight: 500;
            margin-top: 1px
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 2px
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 2px
        }

        .nav-section-lbl {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #cbd5e1;
            padding: 10px 10px 5px;
            margin-top: 2px
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all var(--ease)
        }

        .nav-link:hover {
            background: var(--green-light);
            color: var(--green)
        }

        .nav-link.active {
            background: var(--green);
            color: #fff;
            box-shadow: 0 4px 14px rgba(21, 128, 61, .32)
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, .15)
        }

        .nav-link:not(.active) .nav-icon {
            background: #f1f5f9
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: #dcfce7
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(239, 68, 68, .15);
            color: #dc2626;
            font-size: .6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px
        }

        .nav-link.active .nav-badge {
            background: rgba(255, 255, 255, .22);
            color: #fff
        }

        .sidebar-footer {
            padding: 10px 10px 12px;
            border-top: 1px solid rgba(22, 163, 74, .07)
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            transition: all var(--ease)
        }

        .logout-link:hover {
            background: #fef2f2;
            color: #dc2626
        }

        .mobile-nav-pill {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: white;
            border-top: 1px solid rgba(22, 163, 74, .12);
            height: var(--mob-nav-total);
            z-index: 200;
            box-shadow: 0 -4px 20px rgba(21, 128, 61, .1)
        }

        .mobile-scroll-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            height: var(--mob-nav-h);
            width: 100%
        }

        .mob-nav-item {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            color: #64748b;
            position: relative;
            transition: background .15s
        }

        .mob-nav-item:hover,
        .mob-nav-item.active {
            background: var(--green-light);
            color: var(--green)
        }

        .mob-nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--green);
            border-radius: 50%
        }

        .mob-logout {
            color: #94a3b8
        }

        .mob-logout:hover {
            background: #fef2f2;
            color: #dc2626
        }

        @media(max-width:1023px) {
            .sidebar {
                display: none !important
            }

            .mobile-nav-pill {
                display: flex !important
            }

            .main-area {
                padding-bottom: calc(var(--mob-nav-total) + 16px) !important
            }
        }

        @media(min-width:1024px) {
            .sidebar {
                display: flex !important
            }

            .mobile-nav-pill {
                display: none !important
            }
        }

        .main-area {
            flex: 1;
            min-width: 0;
            padding: 24px 28px 40px;
            height: 100vh;
            height: 100dvh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-y: contain
        }

        @media(max-width:1023px) {
            .main-area::-webkit-scrollbar {
                display: none
            }

            .main-area {
                scrollbar-width: none
            }
        }

        @media(min-width:1024px) {
            .main-area::-webkit-scrollbar {
                width: 4px
            }

            .main-area::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px
            }
        }

        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px
        }

        .greeting-eyebrow {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 4px
        }

        .greeting-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.04em;
            line-height: 1.1
        }

        .greeting-sub {
            font-size: .78rem;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 500
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            margin-top: 4px
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            background: white;
            border: 1px solid rgba(22, 163, 74, .12);
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all var(--ease);
            box-shadow: var(--shadow-sm)
        }

        .icon-btn:hover {
            background: var(--green-light);
            border-color: var(--green-border);
            color: var(--green)
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 16px;
            background: white;
            border: 1px solid rgba(22, 163, 74, .15);
            border-radius: var(--r-sm);
            font-size: .82rem;
            font-weight: 700;
            color: #475569;
            text-decoration: none;
            transition: all var(--ease);
            box-shadow: var(--shadow-sm)
        }

        .back-btn:hover {
            border-color: var(--green);
            color: var(--green);
            background: var(--green-light)
        }

        /* Form card */
        .form-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(22, 163, 74, .08);
            box-shadow: var(--shadow-sm);
            padding: 28px 32px;
            max-width: 760px;
            margin: 0 auto
        }

        @media(max-width:639px) {
            .form-card {
                padding: 18px 16px
            }
        }

        .section-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(22, 163, 74, .06)
        }

        .section-icon {
            width: 36px;
            height: 36px;
            background: var(--green-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .section-title {
            font-size: .9rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -.01em
        }

        .field-label {
            display: block;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 6px
        }

        .field-input {
            width: 100%;
            background: #f8fafc;
            border: 1px solid rgba(22, 163, 74, .15);
            border-radius: var(--r-sm);
            padding: 11px 14px;
            font-family: var(--font);
            font-size: .87rem;
            font-weight: 600;
            color: #0f172a;
            transition: all .2s;
            outline: none
        }

        .field-input:focus {
            border-color: #4ade80;
            background: white;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .08)
        }

        .field-input:read-only {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed
        }

        textarea.field-input {
            resize: vertical;
            min-height: 90px
        }

        /* Type toggle */
        .type-toggle {
            display: flex;
            background: #f1f5f9;
            padding: 5px;
            border-radius: var(--r-md);
            gap: 4px
        }

        .type-btn {
            flex: 1;
            padding: 10px 14px;
            border-radius: var(--r-sm);
            cursor: pointer;
            font-weight: 700;
            font-size: .82rem;
            transition: all var(--ease);
            color: #64748b;
            border: none;
            background: transparent;
            font-family: var(--font);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px
        }

        .type-btn.active {
            background: var(--green);
            color: white;
            box-shadow: 0 4px 12px rgba(21, 128, 61, .25)
        }

        /* Autocomplete */
        .autocomplete-wrap {
            position: relative
        }

        .autocomplete-list {
            position: absolute;
            z-index: 50;
            background: white;
            border: 1px solid rgba(22, 163, 74, .15);
            border-radius: var(--r-md);
            box-shadow: var(--shadow-lg);
            max-height: 220px;
            overflow-y: auto;
            width: 100%;
            top: calc(100% + 4px);
            left: 0
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            font-size: .87rem;
            transition: background .15s;
            font-weight: 500
        }

        .autocomplete-item:hover {
            background: var(--green-light);
            color: var(--green)
        }

        .autocomplete-item .sub {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 2px
        }

        /* PC section */
        .pc-section {
            background: var(--green-light);
            border: 1px solid var(--green-border);
            border-radius: var(--r-md);
            padding: 18px
        }

        .pc-btn {
            padding: 9px 12px;
            border-radius: 9px;
            font-size: .75rem;
            font-weight: 700;
            border: 1px solid var(--green-border);
            background: white;
            color: #475569;
            cursor: pointer;
            transition: all var(--ease)
        }

        .pc-btn:hover {
            border-color: var(--green);
            color: var(--green)
        }

        .pc-btn.selected {
            background: var(--green);
            color: white;
            border-color: var(--green)
        }

        /* Submit btn */
        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: var(--green);
            color: white;
            border-radius: var(--r-sm);
            font-size: .9rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            box-shadow: 0 4px 12px rgba(21, 128, 61, .28)
        }

        .submit-btn:hover {
            background: #14532d;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(21, 128, 61, .35)
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(22, 163, 74, .06);
            margin: 24px 0
        }

        /* Flash */
        .flash-ok {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 13px 18px;
            background: var(--green-light);
            border: 1px solid var(--green-border);
            color: var(--green);
            font-weight: 600;
            border-radius: var(--r-md);
            font-size: .9rem
        }

        .flash-err {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 13px 18px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-weight: 600;
            border-radius: var(--r-md);
            font-size: .9rem
        }

        /* Modal */
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
            justify-content: center
        }

        .modal-back.show {
            display: flex;
            animation: fadeIn .15s ease
        }

        .modal-card {
            background: white;
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 480px;
            padding: 24px;
            max-height: calc(100dvh - 3rem);
            overflow-y: auto;
            margin: auto;
            animation: slideUp .2s ease;
            box-shadow: var(--shadow-lg)
        }

        .sheet-handle {
            display: none;
            width: 36px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 999px;
            margin: 0 auto 16px
        }

        @media(max-width:639px) {
            .modal-back {
                padding: 0;
                align-items: flex-end !important
            }

            .modal-card {
                border-radius: var(--r-xl) var(--r-xl) 0 0;
                max-width: 100%;
                max-height: 92dvh;
                animation: sheetUp .25s cubic-bezier(.34, 1.2, .64, 1) both
            }

            .sheet-handle {
                display: block
            }
        }

        .mrow {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px solid rgba(22, 163, 74, .06);
            gap: 12px
        }

        .mrow:last-child {
            border-bottom: none
        }

        .mrow-label {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #94a3b8;
            flex-shrink: 0;
            padding-top: 1px
        }

        .mrow-value {
            font-weight: 600;
            color: #0f172a;
            font-size: .83rem;
            text-align: right;
            word-break: break-word
        }

        .qr-section {
            background: var(--green-light);
            border: 1.5px dashed var(--green-border);
            border-radius: var(--r-md);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px
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

        @keyframes sheetUp {
            from {
                opacity: 0;
                transform: translateY(60px)
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

        .fade-up {
            animation: slideUp .4s ease both
        }

        .fade-up-1 {
            animation: slideUp .45s .05s ease both
        }

        @media(max-width:639px) {
            .main-area {
                padding: 14px 12px 0
            }
        }

        body.dark {
            --bg: #031a0a;
            --card: #061a0e;
            --green-light: rgba(21, 128, 61, .12);
            --green-border: rgba(22, 163, 74, .25);
            color: #e2eaf8
        }

        body.dark .sidebar-inner {
            background: #061a0e;
            border-color: rgba(22, 163, 74, .12)
        }

        body.dark .brand-name {
            color: #e2eaf8
        }

        body.dark .nav-link {
            color: #6ee7a0
        }

        body.dark .nav-link:hover {
            background: rgba(22, 163, 74, .12);
            color: #4ade80
        }

        body.dark .nav-link:not(.active) .nav-icon {
            background: rgba(22, 163, 74, .1)
        }

        body.dark .user-card {
            background: rgba(21, 128, 61, .15);
            border-color: rgba(22, 163, 74, .2)
        }

        body.dark .user-name-txt {
            color: #e2eaf8
        }

        body.dark .greeting-name {
            color: #e2eaf8
        }

        body.dark .form-card {
            background: #061a0e;
            border-color: rgba(22, 163, 74, .1)
        }

        body.dark .field-input {
            background: #0a2e14;
            border-color: rgba(22, 163, 74, .18);
            color: #e2eaf8
        }

        body.dark .field-input:focus {
            background: #061a0e
        }

        body.dark .icon-btn {
            background: #061a0e;
            border-color: rgba(22, 163, 74, .15);
            color: #6ee7a0
        }

        body.dark .icon-btn:hover {
            background: rgba(22, 163, 74, .12)
        }

        body.dark .modal-card {
            background: #061a0e;
            color: #e2eaf8
        }

        body.dark .mrow {
            border-color: rgba(22, 163, 74, .08)
        }

        body.dark .mrow-value {
            color: #e2eaf8
        }

        body.dark .pc-section {
            background: rgba(21, 128, 61, .12);
            border-color: rgba(22, 163, 74, .2)
        }

        body.dark .pc-btn {
            background: #0a2e14;
            border-color: rgba(22, 163, 74, .15);
            color: #6ee7a0
        }

        body.dark .mobile-nav-pill {
            background: #061a0e;
            border-color: rgba(22, 163, 74, .18)
        }

        body.dark .mob-nav-item {
            color: #6ee7a0
        }

        body.dark .mob-nav-item.active {
            background: rgba(22, 163, 74, .18)
        }

        body.dark .type-toggle {
            background: #0a2e14
        }

        body.dark .autocomplete-list {
            background: #061a0e;
            border-color: rgba(22, 163, 74, .2)
        }

        body.dark .autocomplete-item:hover {
            background: rgba(22, 163, 74, .12)
        }

        body.dark .back-btn {
            background: #061a0e;
            border-color: rgba(22, 163, 74, .15);
            color: #6ee7a0
        }

        body.dark .section-head {
            border-color: rgba(22, 163, 74, .08)
        }

        body.dark .divider {
            border-color: rgba(22, 163, 74, .06)
        }
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/sk/dashboard',       'icon' => 'house',     'label' => 'Dashboard',        'key' => 'dashboard'],
        ['url' => '/sk/reservations',    'icon' => 'calendar',  'label' => 'All Reservations', 'key' => 'reservations'],
        ['url' => '/sk/new-reservation', 'icon' => 'plus',      'label' => 'New Reservation',  'key' => 'new-reservation'],
        ['url' => '/sk/user-requests',   'icon' => 'users',     'label' => 'User Requests',    'key' => 'user-requests'],
        ['url' => '/sk/my-reservations', 'icon' => 'bookmark',  'label' => 'My Reservations',  'key' => 'my-reservations'],
        ['url' => '/sk/books',           'icon' => 'book-open', 'label' => 'Library',          'key' => 'books'],
        ['url' => '/sk/scanner',         'icon' => 'qrcode',    'label' => 'Scanner',          'key' => 'scanner'],
        ['url' => '/sk/profile',         'icon' => 'user',      'label' => 'Profile',          'key' => 'profile'],
    ];
    $pendingUserCount = $pendingUserCount ?? 0;
    $sk_name = session()->get('name') ?? 'SK Officer';
    $avatarLetter = strtoupper(mb_substr(trim($sk_name), 0, 1));

    function skIcon2($name, $size = 16, $stroke = 'currentColor')
    {
        $icons = [
            'house'    => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
            'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'plus'     => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
            'users'    => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-linecap="round" stroke-linejoin="round"/>',
            'bookmark' => '<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>',
            'book-open' => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
            'qrcode'   => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="5" y="5" width="3" height="3" fill="currentColor" stroke="none"/><rect x="16" y="5" width="3" height="3" fill="currentColor" stroke="none"/><rect x="5" y="16" width="3" height="3" fill="currentColor" stroke="none"/><path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 17h3" stroke-linecap="round"/>',
            'user'     => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
            'logout'   => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
            'sun'      => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
            'moon'     => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
            'chevron-l' => '<polyline points="15 18 9 12 15 6"/>',
            'id-card'  => '<path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" stroke-linecap="round" stroke-linejoin="round"/>',
            'cal-days' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/>',
            'eye'      => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
            'check'    => '<polyline points="20 6 9 17 4 12"/>',
            'x'        => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            'download' => '<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
            'person-w' => '<circle cx="12" cy="5" r="2"/><path d="M5 20a7 7 0 0114 0" stroke-linecap="round"/><line x1="12" y1="7" x2="12" y2="14" stroke-linecap="round"/>',
            'desktop'  => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
            'clipboard' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>',
        ];
        $d = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $stroke . '" stroke-width="1.8">' . $d . '</svg>';
    }
    ?>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal-back" onclick="if(event.target===this)closeModal()">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px;">
                <div style="width:52px;height:52px;background:var(--green-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;"><?= skIcon2('clipboard', 22, 'var(--green)') ?></div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:#94a3b8;margin-top:4px;">Review details before saving.</p>
            </div>
            <div style="background:#f8fafc;border-radius:var(--r-md);padding:14px 16px;border:1px solid rgba(22,163,74,.08);margin-bottom:14px;" id="modalSummary"></div>
            <div id="qrWrap" style="display:none;" class="qr-section" style="margin-bottom:14px;">
                <p style="font-size:.6rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:var(--green);">E-Ticket Preview</p>
                <canvas id="qrCanvas" style="border-radius:10px;"></canvas>
                <p id="qrText" style="font-size:.7rem;color:#94a3b8;font-family:var(--mono);text-align:center;word-break:break-all;"></p>
                <button onclick="downloadQR()" style="display:flex;align-items:center;gap:7px;padding:9px 16px;background:var(--green);color:white;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);"><?= skIcon2('download', 13, 'white') ?> Download E-Ticket</button>
            </div>
            <div id="modalActions" style="display:flex;gap:10px;">
                <button type="button" onclick="closeModal()" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(22,163,74,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);">Cancel</button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" style="flex:2;padding:12px;background:var(--green);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 12px rgba(21,128,61,.28);"><?= skIcon2('check', 13, 'white') ?> Confirm & Save</button>
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">SK Officer</div>
                <div class="brand-name">Portal<em>.</em></div>
                <div class="brand-sub">Youth Management System</div>
            </div>
            <div class="user-card" style="display:flex;align-items:center;gap:9px;">
                <div class="user-avatar-sm"><?= $avatarLetter ?></div>
                <div style="min-width:0;">
                    <div class="user-name-txt" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($sk_name) ?></div>
                    <div class="user-role-txt">SK Officer</div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-lbl">Menu</div>
                <?php foreach ($navItems as $item):
                    $active = (isset($page) && $page == $item['key']);
                    $showBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
                ?>
                    <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><?= skIcon2($item['icon'], 16, $active ? 'white' : '#64748b') ?></div>
                        <?= $item['label'] ?>
                        <?php if ($showBadge): ?><span class="nav-badge"><?= $pendingUserCount ?></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="/logout" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);"><?= skIcon2('logout', 16, '#f87171') ?></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active = (isset($page) && $page == $item['key']);
            ?>
                <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= esc($item['label']) ?>">
                    <?= skIcon2($item['icon'], 22, $active ? 'var(--green)' : '#64748b') ?>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out"><?= skIcon2('logout', 22, '#f87171') ?></a>
        </div>
    </nav>

    <main class="main-area">
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">SK Portal</div>
                <div class="greeting-name">New Reservation</div>
                <div class="greeting-sub">Register a manual entry into the system.</div>
            </div>
            <div class="topbar-right">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn"><span id="dark-icon"><?= skIcon2('sun', 14, '#94a3b8') ?></span></div>
                <a href="/sk/my-reservations" class="back-btn"><?= skIcon2('chevron-l', 14, 'currentColor') ?> Back</a>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><?= skIcon2('x', 15, '#dc2626') ?><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok"><?= skIcon2('check', 15, 'var(--green)') ?><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="form-card fade-up-1">
            <form id="reservationForm" method="POST" action="<?= base_url('sk/create-reservation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="visitor_name" id="finalVisitorName">
                <input type="hidden" name="user_email" id="finalUserEmail">
                <input type="hidden" name="user_id" id="finalUserId">
                <input type="hidden" name="visitor_type" id="finalVisitorType" value="User">
                <input type="hidden" name="purpose" id="finalPurpose">
                <input type="hidden" name="pcs" id="finalPcs" value="[]">

                <!-- Type toggle -->
                <div style="margin-bottom:22px;">
                    <label class="field-label" style="margin-bottom:8px;">Visitor Classification</label>
                    <div class="type-toggle">
                        <button type="button" class="type-btn active" id="btnUser" onclick="setType('User')">
                            <?= skIcon2('user', 14, 'currentColor') ?> Registered User
                        </button>
                        <button type="button" class="type-btn" id="btnVisitor" onclick="setType('Visitor')">
                            <?= skIcon2('person-w', 14, 'currentColor') ?> Walk-in Visitor
                        </button>
                    </div>
                </div>

                <hr class="divider">

                <!-- Personal Details -->
                <div style="margin-bottom:22px;">
                    <div class="section-head">
                        <div class="section-icon"><?= skIcon2('id-card', 16, 'var(--green)') ?></div>
                        <div>
                            <div class="section-title">Personal Details</div>
                        </div>
                    </div>
                    <div id="userFields" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="field-label">Full Name</label>
                            <div class="autocomplete-wrap">
                                <input type="text" id="userNameInput" class="field-input" placeholder="Type to search users…" autocomplete="off">
                                <ul id="autocompleteList" class="autocomplete-list" style="display:none;"></ul>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="userEmailDisplay" class="field-input" placeholder="Auto-filled on selection" readonly>
                            <p style="font-size:.65rem;color:#94a3b8;margin-top:4px;">Fills automatically when a user is selected</p>
                        </div>
                    </div>
                    <div id="visitorFields" style="display:none;grid-template-columns:1fr 1fr;gap:14px;">
                        <div><label class="field-label">Full Name</label><input type="text" id="visitorNameInput" class="field-input" placeholder="Enter visitor's full name"></div>
                        <div><label class="field-label">Email Address</label><input type="email" id="visitorEmailInput" class="field-input" placeholder="Enter email (optional)"></div>
                    </div>
                </div>

                <hr class="divider">

                <!-- Resource & Schedule -->
                <div style="margin-bottom:22px;">
                    <div class="section-head">
                        <div class="section-icon"><?= skIcon2('cal-days', 16, 'var(--green)') ?></div>
                        <div>
                            <div class="section-title">Resource & Schedule</div>
                        </div>
                    </div>

                    <div style="margin-bottom:14px;">
                        <label class="field-label">Select Asset / Resource</label>
                        <select id="resourceSelect" name="resource_id" class="field-input" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>" data-name="<?= htmlspecialchars($res['name']) ?>"><?= htmlspecialchars($res['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="pcSection" style="display:none;" class="pc-section" style="margin-bottom:14px;">
                        <label class="field-label" style="color:var(--green);margin-bottom:10px;display:block;">Assign Workstation(s)</label>
                        <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:8px;">
                            <?php foreach ($pcs as $pc): ?>
                                <button type="button" onclick="togglePc('<?= htmlspecialchars($pc['pc_number']) ?>',this)" data-pc="<?= htmlspecialchars($pc['pc_number']) ?>" class="pc-btn"><?= htmlspecialchars($pc['pc_number']) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <p style="font-size:.72rem;color:var(--green);font-weight:600;margin-top:10px;">Selected: <span id="pcSelectedLabel">None</span></p>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px;">
                        <div><label class="field-label">Date</label><input type="date" name="reservation_date" id="resDate" class="field-input" value="<?= date('Y-m-d') ?>" required></div>
                        <div><label class="field-label">Start Time</label><input type="time" name="start_time" id="startTime" class="field-input" required></div>
                        <div><label class="field-label">End Time</label><input type="time" name="end_time" id="endTime" class="field-input" required></div>
                    </div>

                    <div>
                        <label class="field-label">Purpose of Visit</label>
                        <select id="purposeSelect" class="field-input" required>
                            <option value="">— Select purpose —</option>
                            <option>Work</option>
                            <option>Personal</option>
                            <option>Study</option>
                            <option>SK Activity</option>
                            <option>Others</option>
                        </select>
                    </div>
                    <div id="purposeOtherWrap" style="display:none;margin-top:10px;">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" class="field-input" placeholder="Describe the purpose…">
                    </div>
                </div>

                <button type="button" onclick="previewReservation()" class="submit-btn">
                    <?= skIcon2('eye', 16, 'white') ?> Preview & Confirm
                </button>
            </form>
        </div>
    </main>

    <script>
        const allUsers = <?= json_encode($users ?? []) ?>;
        let currentType = 'User',
            selectedUser = null,
            selectedPcs = [];

        function setType(type) {
            currentType = type;
            document.getElementById('finalVisitorType').value = type;
            const isUser = type === 'User';
            document.getElementById('btnUser').classList.toggle('active', isUser);
            document.getElementById('btnVisitor').classList.toggle('active', !isUser);
            document.getElementById('userFields').style.display = isUser ? 'grid' : 'none';
            document.getElementById('visitorFields').style.display = isUser ? 'none' : 'grid';
            selectedUser = null;
            ['userNameInput', 'userEmailDisplay', 'visitorNameInput', 'visitorEmailInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('finalUserId').value = '';
        }

        const userNameInput = document.getElementById('userNameInput');
        const autocompleteList = document.getElementById('autocompleteList');
        userNameInput.addEventListener('input', () => {
            const q = userNameInput.value.toLowerCase().trim();
            autocompleteList.innerHTML = '';
            selectedUser = null;
            if (!q) {
                autocompleteList.style.display = 'none';
                return;
            }
            const matches = allUsers.filter(u =>
                (u.name || '').toLowerCase().includes(q) || (u.full_name || '').toLowerCase().includes(q) || (u.email || '').toLowerCase().includes(q)
            ).slice(0, 8);
            if (!matches.length) {
                autocompleteList.style.display = 'none';
                return;
            }
            matches.forEach(u => {
                const displayName = u.full_name || u.name || '';
                const li = document.createElement('li');
                li.className = 'autocomplete-item';
                li.innerHTML = `<div style="font-weight:600;">${displayName}</div><div class="sub">${u.email}</div>`;
                li.addEventListener('mousedown', () => {
                    selectedUser = u;
                    userNameInput.value = displayName;
                    document.getElementById('userEmailDisplay').value = u.email;
                    document.getElementById('finalUserId').value = u.id;
                    autocompleteList.style.display = 'none';
                });
                autocompleteList.appendChild(li);
            });
            autocompleteList.style.display = 'block';
        });
        userNameInput.addEventListener('blur', () => setTimeout(() => autocompleteList.style.display = 'none', 150));

        document.getElementById('resourceSelect').addEventListener('change', function() {
            const name = (this.options[this.selectedIndex]?.dataset.name || '').toLowerCase();
            const showPcs = name.includes('computer') || name.includes('pc') || name.includes('lab');
            document.getElementById('pcSection').style.display = showPcs ? 'block' : 'none';
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(b => b.classList.remove('selected'));
        });

        function togglePc(num, btn) {
            const idx = selectedPcs.indexOf(num);
            if (idx === -1) {
                selectedPcs.push(num);
                btn.classList.add('selected');
            } else {
                selectedPcs.splice(idx, 1);
                btn.classList.remove('selected');
            }
            updatePcHidden();
        }

        function updatePcHidden() {
            document.getElementById('finalPcs').value = JSON.stringify(selectedPcs);
            document.getElementById('pcSelectedLabel').textContent = selectedPcs.length ? selectedPcs.join(', ') : 'None';
        }

        document.getElementById('purposeSelect').addEventListener('change', function() {
            document.getElementById('purposeOtherWrap').style.display = this.value === 'Others' ? 'block' : 'none';
        });

        function previewReservation() {
            const isUser = currentType === 'User';
            const name = isUser ? userNameInput.value.trim() : document.getElementById('visitorNameInput').value.trim();
            const email = isUser ? document.getElementById('userEmailDisplay').value.trim() : document.getElementById('visitorEmailInput').value.trim();
            const resourceEl = document.getElementById('resourceSelect');
            const resourceName = resourceEl.options[resourceEl.selectedIndex]?.text || '—';
            const showPcs = document.getElementById('pcSection').style.display !== 'none';
            const date = document.getElementById('resDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const purposeVal = document.getElementById('purposeSelect').value;
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ? `Others — ${purposeOther}` : purposeVal;

            if (!name) return alert('Please enter a name.');
            if (!resourceEl.value) return alert('Please select a resource.');
            if (showPcs && !selectedPcs.length) return alert('Please select at least one workstation.');
            if (!date) return alert('Please select a date.');
            if (!startTime) return alert('Please enter a start time.');
            if (!endTime) return alert('Please enter an end time.');
            if (!purposeVal) return alert('Please select a purpose.');
            if (isUser && !selectedUser && !document.getElementById('finalUserId').value) return alert('Please select a registered user from the dropdown.');

            document.getElementById('finalVisitorName').value = name;
            document.getElementById('finalUserEmail').value = email;
            document.getElementById('finalPurpose').value = purposeFinal;

            const rows = [
                ['Type', isUser ? 'Registered User' : 'Walk-in Visitor'],
                ['Name', name || '—'],
                ['Email', email || '—'],
                ['Resource', resourceName],
                ['Workstations', selectedPcs.length ? selectedPcs.join(', ') : '—'],
                ['Date', date],
                ['Time', `${startTime} – ${endTime}`],
                ['Purpose', purposeFinal || '—'],
            ];
            document.getElementById('modalSummary').innerHTML = rows.map(([l, v]) =>
                `<div class="mrow"><span class="mrow-label">${l}</span><span class="mrow-value">${v}</span></div>`
            ).join('');
            document.getElementById('qrWrap').style.display = 'none';
            document.getElementById('confirmBtn').style.display = 'flex';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = 'Saving…';
            const code = `SK-${Date.now()}`;
            document.getElementById('qrText').textContent = code;
            QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
                width: 160,
                margin: 1,
                color: {
                    dark: '#1e293b',
                    light: '#ffffff'
                }
            }, () => {
                document.getElementById('qrWrap').style.display = 'flex';
                btn.style.display = 'none';
                setTimeout(() => document.getElementById('reservationForm').submit(), 800);
            });
        }

        function downloadQR() {
            const canvas = document.getElementById('qrCanvas'),
                code = document.getElementById('qrText').textContent;
            const link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
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
            btn.style.display = 'flex';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        function toggleDark() {
            const isDark = document.body.classList.toggle('dark');
            const icon = document.getElementById('dark-icon');
            icon.innerHTML = isDark ?
                `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>` :
                `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
            localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sk_theme') === 'dark') {
                document.body.classList.add('dark');
                document.getElementById('dark-icon').innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
            }
            document.documentElement.classList.remove('dark-pre');
            // Responsive grid for personal details
            function checkGrid() {
                const uf = document.getElementById('userFields'),
                    vf = document.getElementById('visitorFields');
                const cols = window.innerWidth < 640 ? '1fr' : '1fr 1fr';
                [uf, vf].forEach(el => {
                    if (el) el.style.gridTemplateColumns = cols;
                });
                const dateGrid = document.querySelector('[style*="grid-template-columns:1fr 1fr 1fr"]');
                if (dateGrid) dateGrid.style.gridTemplateColumns = window.innerWidth < 640 ? '1fr' : '1fr 1fr 1fr';
            }
            checkGrid();
            window.addEventListener('resize', checkGrid);
        });
    </script>
</body>

</html>