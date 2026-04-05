<?php
date_default_timezone_set('Asia/Manila');
$page = $page ?? 'new-reservation';
$sk_name = session()->get('name') ?? 'SK Officer';
$pendingUserCount = $pendingUserCount ?? 0;
$avatarLetter = strtoupper(mb_substr(trim($sk_name), 0, 1));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
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
            --indigo: #3730a3;
            --indigo-mid: #4338ca;
            --indigo-light: #eef2ff;
            --indigo-border: #c7d2fe;
            --bg: #f0f2f9;
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
            -webkit-font-smoothing: antialiased
        }

        html.dark-pre body {
            background: #060e1e
        }

        /* Sidebar */
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
            border: 1px solid rgba(99, 102, 241, .1);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-md)
        }

        .sidebar-top {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(99, 102, 241, .07)
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
            color: var(--indigo)
        }

        .brand-sub {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: 3px
        }

        .user-card {
            margin: 12px 12px 0;
            background: var(--indigo-light);
            border-radius: var(--r-md);
            padding: 12px 14px;
            border: 1px solid var(--indigo-border);
            display: flex;
            align-items: center;
            gap: 9px
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--indigo);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .85rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(55, 48, 163, .3)
        }

        .user-name-txt {
            font-size: .8rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .user-role-txt {
            font-size: .68rem;
            color: #6366f1;
            font-weight: 500;
            margin-top: 1px
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            scrollbar-width: none
        }

        .sidebar-nav::-webkit-scrollbar {
            display: none
        }

        .nav-section-lbl {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #cbd5e1;
            padding: 10px 10px 5px
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
            background: var(--indigo-light);
            color: var(--indigo)
        }

        .nav-link.active {
            background: var(--indigo);
            color: #fff;
            box-shadow: 0 4px 14px rgba(55, 48, 163, .32)
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .85rem
        }

        .nav-link:not(.active) .nav-icon {
            background: #f1f5f9
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: #e0e7ff
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, .15)
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(245, 158, 11, .18);
            color: #d97706;
            font-size: .6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px
        }

        .sidebar-footer {
            padding: 10px 10px 12px;
            border-top: 1px solid rgba(99, 102, 241, .07)
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

        .logout-link:hover .nav-icon {
            background: #fee2e2
        }

        /* Mobile nav */
        .mobile-nav-pill {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid rgba(99, 102, 241, .1);
            height: var(--mob-nav-total);
            z-index: 200;
            box-shadow: 0 -4px 20px rgba(55, 48, 163, .1)
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
            transition: background .15s, color .15s
        }

        .mob-nav-item:hover,
        .mob-nav-item.active {
            background: var(--indigo-light);
            color: var(--indigo)
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

        /* Main */
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

        @media(max-width:639px) {
            .main-area {
                padding: 14px 12px 0
            }
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            background: white;
            border: 1px solid rgba(99, 102, 241, .12);
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
            background: var(--indigo-light);
            border-color: var(--indigo-border);
            color: var(--indigo)
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 16px;
            background: white;
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--r-sm);
            font-size: .82rem;
            font-weight: 700;
            color: #475569;
            text-decoration: none;
            transition: all var(--ease);
            box-shadow: var(--shadow-sm)
        }

        .back-btn:hover {
            border-color: var(--indigo);
            color: var(--indigo);
            background: var(--indigo-light)
        }

        /* Form card */
        .form-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 28px 32px;
            max-width: 780px;
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
            border-bottom: 1px solid rgba(99, 102, 241, .07)
        }

        .section-icon {
            width: 36px;
            height: 36px;
            background: var(--indigo-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .section-title {
            font-size: .9rem;
            font-weight: 700;
            color: #0f172a
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
            border: 1px solid rgba(99, 102, 241, .12);
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
            border-color: #818cf8;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08)
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
            background: var(--indigo);
            color: white;
            box-shadow: 0 4px 12px rgba(55, 48, 163, .25)
        }

        /* Autocomplete */
        .autocomplete-wrap {
            position: relative
        }

        .autocomplete-list {
            position: absolute;
            z-index: 50;
            background: white;
            border: 1px solid rgba(99, 102, 241, .15);
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
            background: var(--indigo-light);
            color: var(--indigo)
        }

        .autocomplete-item .sub {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 2px
        }

        /* PC grid */
        .pc-section {
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            border-radius: var(--r-md);
            padding: 18px
        }

        .pc-btn {
            padding: 9px 12px;
            border-radius: 9px;
            font-size: .75rem;
            font-weight: 700;
            border: 1px solid var(--indigo-border);
            background: white;
            color: #475569;
            cursor: pointer;
            transition: all var(--ease)
        }

        .pc-btn:hover {
            border-color: var(--indigo);
            color: var(--indigo)
        }

        .pc-btn.selected {
            background: var(--indigo);
            color: white;
            border-color: var(--indigo)
        }

        /* Submit */
        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: var(--indigo);
            color: white;
            border-radius: var(--r-sm);
            font-size: .9rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            box-shadow: 0 4px 12px rgba(55, 48, 163, .28)
        }

        .submit-btn:hover {
            background: #312e81;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(55, 48, 163, .35)
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(99, 102, 241, .07);
            margin: 22px 0
        }

        /* Flash */
        .flash-ok {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 13px 18px;
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            color: var(--indigo);
            font-weight: 600;
            border-radius: var(--r-md);
            font-size: .875rem
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
            font-size: .875rem
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
            border-bottom: 1px solid rgba(99, 102, 241, .06);
            gap: 12px
        }

        .mrow:last-child {
            border-bottom: none
        }

        .mrow-label {
            font-size: .6rem;
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
            background: var(--indigo-light);
            border: 1.5px dashed var(--indigo-border);
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

        /* Dark mode */
        body.dark {
            --bg: #060e1e;
            --card: #0b1628;
            --indigo-light: rgba(55, 48, 163, .12);
            --indigo-border: rgba(99, 102, 241, .25);
            color: #e2eaf8
        }

        body.dark .sidebar-inner {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .12)
        }

        body.dark .brand-name {
            color: #e2eaf8
        }

        body.dark .nav-link {
            color: #a5b4fc
        }

        body.dark .nav-link:hover {
            background: rgba(99, 102, 241, .12);
            color: #c7d2fe
        }

        body.dark .nav-link:not(.active) .nav-icon {
            background: rgba(99, 102, 241, .1)
        }

        body.dark .user-card {
            background: rgba(55, 48, 163, .15);
            border-color: rgba(99, 102, 241, .2)
        }

        body.dark .user-name-txt {
            color: #e2eaf8
        }

        body.dark .greeting-name {
            color: #e2eaf8
        }

        body.dark .form-card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .field-input {
            background: #101e35;
            border-color: rgba(99, 102, 241, .18);
            color: #e2eaf8
        }

        body.dark .field-input:focus {
            background: #0b1628
        }

        body.dark .icon-btn {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .15);
            color: #a5b4fc
        }

        body.dark .icon-btn:hover {
            background: rgba(99, 102, 241, .12)
        }

        body.dark .modal-card {
            background: #0b1628;
            color: #e2eaf8
        }

        body.dark .mrow {
            border-color: rgba(99, 102, 241, .08)
        }

        body.dark .mrow-value {
            color: #e2eaf8
        }

        body.dark .pc-section {
            background: rgba(55, 48, 163, .12);
            border-color: rgba(99, 102, 241, .2)
        }

        body.dark .pc-btn {
            background: #101e35;
            border-color: rgba(99, 102, 241, .15);
            color: #a5b4fc
        }

        body.dark .mobile-nav-pill {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .18)
        }

        body.dark .mob-nav-item {
            color: #a5b4fc
        }

        body.dark .mob-nav-item.active {
            background: rgba(99, 102, 241, .18)
        }

        body.dark .type-toggle {
            background: #101e35
        }

        body.dark .autocomplete-list {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .2)
        }

        body.dark .autocomplete-item:hover {
            background: rgba(99, 102, 241, .12)
        }

        body.dark .back-btn {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .15);
            color: #a5b4fc
        }

        body.dark .section-head {
            border-color: rgba(99, 102, 241, .08)
        }

        body.dark .divider {
            border-color: rgba(99, 102, 241, .06)
        }
        /* ═══════════════════════════════════════════════════
   SK OFFICER PORTAL — DARK MODE COMPREHENSIVE FIX
   Paste at the bottom of <style> in every SK view.
   Targets hardcoded inline colors that class selectors
   cannot override on their own.
═══════════════════════════════════════════════════ */

/* ── General inline text overrides ── */
body.dark [style*="color:#0f172a"],
body.dark [style*="color: #0f172a"] { color: #e2eaf8 !important; }

body.dark [style*="color:#475569"],
body.dark [style*="color: #475569"] { color: #94b4d4 !important; }

body.dark [style*="color:#64748b"],
body.dark [style*="color: #64748b"] { color: #7fb3e8 !important; }

body.dark [style*="color:#94a3b8"],
body.dark [style*="color: #94a3b8"] { color: #4a6fa5 !important; }

body.dark [style*="color:#cbd5e1"],
body.dark [style*="color: #cbd5e1"] { color: #2d4a6a !important; }

body.dark [style*="color:#334155"],
body.dark [style*="color: #334155"] { color: #94b4d4 !important; }

body.dark [style*="color:#1e293b"],
body.dark [style*="color: #1e293b"] { color: #c7d2fe !important; }

/* ── General inline background overrides ── */
body.dark [style*="background:#ffffff"],
body.dark [style*="background: #ffffff"],
body.dark [style*="background:white"],
body.dark [style*="background: white"] { background: #0b1628 !important; }

body.dark [style*="background:#f8fafc"],
body.dark [style*="background: #f8fafc"] { background: #101e35 !important; }

body.dark [style*="background:#f1f5f9"],
body.dark [style*="background: #f1f5f9"] { background: #101e35 !important; }

body.dark [style*="background:#f0f2f9"],
body.dark [style*="background: #f0f2f9"] { background: #060e1e !important; }

/* ── Border overrides ── */
body.dark [style*="border:1px solid #e2e8f0"],
body.dark [style*="border: 1px solid #e2e8f0"],
body.dark [style*="border-color:#e2e8f0"],
body.dark [style*="border-color: #e2e8f0"] { border-color: rgba(99,102,241,.12) !important; }

body.dark [style*="border-bottom:1px solid #f1f5f9"],
body.dark [style*="border-bottom: 1px solid #f1f5f9"] { border-color: rgba(99,102,241,.08) !important; }

/* ── Card titles / subtitles ── */
body.dark .card-title { color: #e2eaf8 !important; }
body.dark .card-sub   { color: #4a6fa5 !important; }
body.dark .section-label,
body.dark .section-lbl { color: #4a6fa5 !important; }
body.dark .link-sm { color: #818cf8 !important; }

/* ── Stat cards ── */
body.dark .stat-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .stat-num  { color: #e2eaf8 !important; }
body.dark .stat-lbl,
body.dark .stat-hint { color: #4a6fa5 !important; }
body.dark .stat-badge { opacity: .85; }
body.dark .kpi-card  { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Table ── */
body.dark thead th  { background: #0f1a2e !important; color: #4a6fa5 !important; border-color: rgba(99,102,241,.08) !important; }
body.dark tbody td  { border-color: rgba(99,102,241,.05) !important; }
body.dark tbody tr:hover td { background: #101e35 !important; }
body.dark table thead th { background: #0f1a2e !important; color: #4a6fa5 !important; }

/* ── Tab bar ── */
body.dark .tab-bar { background: #0b1628 !important; border-color: rgba(99,102,241,.12) !important; }
body.dark .tab-btn { color: #7fb3e8 !important; }
body.dark .tab-btn:not(.active):hover { background: rgba(99,102,241,.12) !important; color: #a5b4fc !important; }
body.dark .tab-badge { background: rgba(255,255,255,.12) !important; color: #fff !important; }

/* ── Search / filter inputs ── */
body.dark .search-input,
body.dark .search-field,
body.dark .filter-select,
body.dark .genre-select { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
body.dark .search-input:focus,
body.dark .search-field:focus { background: #0b1628 !important; border-color: #818cf8 !important; }
body.dark .search-input::placeholder,
body.dark .search-field::placeholder { color: #4a6fa5 !important; }

/* ── Filter pills ── */
body.dark .fpill { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .fpill:hover { background: #1a2a42 !important; }

/* ── Quick tabs ── */
body.dark .qtab { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }
body.dark .qtab:hover { border-color: var(--indigo) !important; color: #a5b4fc !important; }
body.dark .reset-btn { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }

/* ── Books table wrap ── */
body.dark .tbl-wrap { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Book cards ── */
body.dark .book-card  { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .book-letter { background: rgba(55,48,163,.2) !important; color: #818cf8 !important; }
body.dark .book-meta-chip { background: #101e35 !important; border-color: rgba(99,102,241,.12) !important; color: #7fb3e8 !important; }
body.dark .book-copies-row { border-color: rgba(99,102,241,.08) !important; }
body.dark .call-badge { background: rgba(92,33,182,.2) !important; color: #a78bfa !important; }

/* ── Copies control (+/−) ── */
body.dark .cpy-btn { background: #101e35 !important; border-color: rgba(99,102,241,.2) !important; color: #7fb3e8 !important; }
body.dark .cpy-btn:hover { background: rgba(99,102,241,.18) !important; }
body.dark .cpy-val  { color: #e2eaf8 !important; }
body.dark .cpy-total { color: #4a6fa5 !important; }

/* ── Action buttons in books table ── */
body.dark .act-edit { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .act-edit:hover { background: rgba(99,102,241,.18) !important; color: #a5b4fc !important; }
body.dark .act-del  { background: rgba(239,68,68,.1) !important; color: #f87171 !important; }
body.dark .act-del:hover { background: rgba(239,68,68,.18) !important; }
body.dark .act-approve { background: rgba(22,163,74,.15) !important; color: #4ade80 !important; }
body.dark .act-approve:hover { background: rgba(22,163,74,.25) !important; }
body.dark .act-reject { background: rgba(239,68,68,.1) !important; color: #f87171 !important; }
body.dark .act-return { background: #101e35 !important; color: #7fb3e8 !important; }

/* ── Borrow cards ── */
body.dark .borrow-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Status tags (darker semantic versions) ── */
body.dark .tag-pending  { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }
body.dark .tag-approved { background: rgba(16,185,129,.1) !important; color: #34d399 !important; }
body.dark .tag-returned { background: rgba(59,130,246,.1) !important; color: #7fb3e8 !important; }
body.dark .tag-rejected { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .tag-active   { background: rgba(16,185,129,.1) !important; color: #34d399 !important; }
body.dark .tag-inactive { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .tag-rag-yes  { background: rgba(139,92,246,.15) !important; color: #c084fc !important; }
body.dark .tag-rag-no   { background: #101e35 !important; color: #4a6fa5 !important; }
body.dark .tag-expired  { background: rgba(100,116,139,.15) !important; color: #94a3b8 !important; }
body.dark .tag-unclaimed { background: rgba(249,115,22,.12) !important; color: #fb923c !important; border-color: rgba(249,115,22,.3) !important; }
body.dark .tag-claimed  { background: rgba(168,85,247,.15) !important; color: #c084fc !important; }
body.dark .tag-declined,
body.dark .tag-canceled { background: rgba(239,68,68,.15) !important; color: #f87171 !important; }

/* ── Badges (reservations view) ── */
body.dark .badge-pending  { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }
body.dark .badge-approved { background: rgba(99,102,241,.15) !important; color: #a5b4fc !important; }
body.dark .badge-declined,
body.dark .badge-canceled { background: rgba(239,68,68,.15) !important; color: #f87171 !important; }
body.dark .badge-claimed  { background: rgba(168,85,247,.15) !important; color: #c084fc !important; }
body.dark .badge-expired  { background: #101e35 !important; color: #4a6fa5 !important; }
body.dark .badge-unclaimed { background: rgba(249,115,22,.12) !important; color: #fb923c !important; border-color: rgba(249,115,22,.3) !important; }

/* ── Modals (general) ── */
body.dark .modal-box,
body.dark .modal-card { background: #0b1628 !important; color: #e2eaf8 !important; }
body.dark .modal-head { border-color: rgba(99,102,241,.1) !important; }
body.dark .modal-title { color: #e2eaf8 !important; }
body.dark .modal-title-lbl { color: #4a6fa5 !important; }
body.dark .modal-close { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .modal-close:hover { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .modal-cancel { background: #101e35 !important; border-color: rgba(99,102,241,.12) !important; color: #7fb3e8 !important; }
body.dark .modal-cancel:hover { background: #1a2a42 !important; }
body.dark .sheet-handle { background: #1e3a5f !important; }

/* ── Modal action buttons with inline styles ── */
body.dark .modal-card button[style*="background:#f8fafc"],
body.dark .modal-card button[style*="background: #f8fafc"],
body.dark .modal-card button[style*="background:#f1f5f9"],
body.dark .modal-card button[style*="background: #f1f5f9"],
body.dark .modal-box button[style*="background:#f1f5f9"] {
    background: #101e35 !important;
    border-color: rgba(99,102,241,.15) !important;
    color: #a5b4fc !important;
}
body.dark .btn-cancel { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .btn-cancel:hover { background: #1a2a42 !important; }

/* ── Form inputs (add/edit book modals) ── */
body.dark .form-input,
body.dark .field-input { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
body.dark .form-input:focus,
body.dark .field-input:focus { background: #0b1628 !important; border-color: #818cf8 !important; }
body.dark .form-input::placeholder,
body.dark .field-input::placeholder { color: #4a6fa5 !important; }
body.dark .form-lbl,
body.dark .field-label { color: #4a6fa5 !important; }
body.dark select.form-input,
body.dark select.field-input { background: #101e35 !important; color: #e2eaf8 !important; }
body.dark .form-input.filled { background: rgba(22,163,74,.08) !important; border-color: #22c55e !important; }

/* ── PDF upload / AI extraction ── */
body.dark .drop-zone { background: rgba(55,48,163,.1) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark .drop-zone:hover { background: rgba(55,48,163,.2) !important; border-color: var(--indigo) !important; }
body.dark .ai-progress-bar { background: #101e35 !important; }
body.dark .ai-spinner { border-color: #1a2a42 !important; border-top-color: #818cf8 !important; }
body.dark .step-dot.pending { background: #101e35 !important; color: #4a6fa5 !important; }
body.dark .step-line.pending { background: #101e35 !important; }
body.dark #skDebugPanel { background: rgba(153,27,27,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }

/* ── Flash / alert banners ── */
body.dark .flash-ok { background: rgba(55,48,163,.15) !important; border-color: rgba(99,102,241,.3) !important; color: #a5b4fc !important; }
body.dark .flash-err { background: rgba(153,27,27,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }
body.dark .pending-pill { background: rgba(180,83,9,.2) !important; border-color: rgba(180,83,9,.3) !important; color: #fcd34d !important; }
body.dark .alert-banner.alert-pending { background: rgba(180,83,9,.2) !important; border-color: rgba(180,83,9,.3) !important; color: #fcd34d !important; }
body.dark .alert-banner.alert-success { background: rgba(20,83,45,.25) !important; border-color: rgba(134,239,172,.2) !important; color: #86efac !important; }
body.dark .alert-banner.alert-error   { background: rgba(127,29,29,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }

/* ── Quick action links ── */
body.dark .qa-link { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }
body.dark .qa-link:hover { background: rgba(99,102,241,.1) !important; color: #a5b4fc !important; border-color: var(--indigo) !important; }
body.dark .qa-chev { color: #1e3a5f !important; }

/* ── Booking rows (dashboard) ── */
body.dark .bk-row:hover { background: rgba(99,102,241,.08) !important; }
body.dark .bk-date { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .bk-day  { color: #e2eaf8 !important; }
body.dark .bk-name { color: #e2eaf8 !important; }
body.dark .bk-month, body.dark .bk-time { color: #4a6fa5 !important; }

/* ── Book rows in dashboard library section ── */
body.dark .book-letter { background: rgba(55,48,163,.2) !important; color: #818cf8 !important; }
body.dark .avail-pill.avail-on  { background: rgba(16,185,129,.12) !important; color: #34d399 !important; }
body.dark .avail-pill.avail-off { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .avail-pill.avail-low { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }

/* ── Book rows with inline styles ── */
body.dark [style*="color:#0f172a"][style*="font-weight:600"],
body.dark .book-title { color: #e2eaf8 !important; }
body.dark .book-author { color: #4a6fa5 !important; }
body.dark .avail-num { color: #4a6fa5 !important; }

/* ── Library banner stats ── */
body.dark .lib-stat-item { background: rgba(255,255,255,.06) !important; border-color: rgba(255,255,255,.08) !important; }

/* ── Session timeline cards (dashboard live monitor) ── */
body.dark .tl-session-card { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .tl-ended .tl-countdown { background: #1a2a42 !important; color: #7fb3e8 !important; }
body.dark .tl-prog-track { background: rgba(99,102,241,.15) !important; }

/* ── Insight cards ── */
body.dark .insight-mini { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Pending pill in books tab ── */
body.dark .tab-badge { background: rgba(255,255,255,.12) !important; }

/* ── Empty states ── */
body.dark .empty-state { background: transparent !important; }
body.dark .card-empty { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Detail rows (modals) ── */
body.dark .drow { border-color: #101e35 !important; }
body.dark .dvalue { color: #e2eaf8 !important; }
body.dark .dlabel { color: #4a6fa5 !important; }
body.dark .detail-row { border-color: #101e35 !important; }
body.dark .detail-label { color: #4a6fa5 !important; }
body.dark .detail-value { color: #e2eaf8 !important; }
body.dark .mrow { border-color: rgba(99,102,241,.06) !important; }
body.dark .mrow-value { color: #e2eaf8 !important; }
body.dark .mrow-label { color: #4a6fa5 !important; }

/* ── Reservation list cards ── */
body.dark .res-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .res-card:hover { border-color: rgba(99,102,241,.3) !important; }
body.dark .req-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Print log / ticket ── */
body.dark #dPrintLogForm { background: #101e35 !important; border-color: rgba(99,102,241,.12) !important; }
body.dark #dPrintLogForm input[type=number] { background: #0b1628 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
body.dark .ticket-section { background: rgba(55,48,163,.12) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark #dPrintLog { background: #101e35 !important; border-color: rgba(99,102,241,.08) !important; }
body.dark .print-pill-yes { background: rgba(99,102,241,.18) !important; color: #a5b4fc !important; }
body.dark .print-pill-no  { background: #101e35 !important; color: #4a6fa5 !important; }

/* ── Unclaimed banner ── */
body.dark .unclaimed-banner { background: rgba(194,65,12,.1) !important; border-color: rgba(249,115,22,.3) !important; }
body.dark .unclaimed-banner .ub-icon { background: rgba(249,115,22,.2) !important; }

/* ── Progress bars ── */
body.dark .prog-bar { background: rgba(99,102,241,.15) !important; }
body.dark .quota-track { background: rgba(99,102,241,.15) !important; }

/* ── Scanner page ── */
body.dark #scanner-viewport { background: #101e35 !important; border-color: rgba(99,102,241,.15) !important; }
body.dark #scanner-viewport.active { border-color: rgba(99,102,241,.4) !important; }
body.dark .cam-status { color: #4a6fa5 !important; }
body.dark .stat-row:hover { background: rgba(99,102,241,.08) !important; }
body.dark .stat-row-lbl { color: #4a6fa5 !important; }
body.dark .stat-row-val { color: #e2eaf8 !important; }
body.dark .history-item { background: #101e35 !important; border-color: rgba(99,102,241,.08) !important; }
body.dark .history-item:hover { background: rgba(99,102,241,.12) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark .result-sheet { background: #0b1628 !important; }
body.dark .result-success { background: #0a1f10 !important; }
body.dark .result-warning { background: #1a1400 !important; }
body.dark .result-error   { background: #1a0a0a !important; }
body.dark .result-claimed { background: #130a1f !important; }
body.dark .camera-error { background: rgba(127,29,29,.2) !important; border-color: rgba(252,165,165,.2) !important; }

/* ── User requests — notif dropdown ── */
body.dark .notif-dd { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; }
body.dark .notif-item { border-color: #101e35 !important; }
body.dark .notif-item.unread { background: rgba(55,48,163,.18) !important; }
body.dark .notif-item:hover { background: #101e35 !important; }
body.dark .toast { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; }

/* ── Reservation/all-reservations page overlays ── */
body.dark .modal-box { background: #0b1628 !important; }
body.dark .overlay-bg { background: rgba(6,14,30,.7) !important; }

/* ── Profile page (SK) ── */
body.dark .profile-status-dot { border-color: #0b1628 !important; }
body.dark .stat-mini { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .stat-mini-val { color: #e2eaf8 !important; }
body.dark .stat-mini-lbl,
body.dark .stat-mini-sub { color: #4a6fa5 !important; }
body.dark .info-icon { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .info-value { color: #e2eaf8 !important; }
body.dark .info-label { color: #4a6fa5 !important; }
body.dark .info-row { border-color: rgba(99,102,241,.07) !important; }
body.dark .quick-link { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #a5b4fc !important; }
body.dark .quick-link:hover { background: rgba(99,102,241,.12) !important; color: #c7d2fe !important; }
body.dark .danger-row { border-color: rgba(99,102,241,.08) !important; }

/* ── New reservation page ── */
body.dark .form-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .section-head { border-color: rgba(99,102,241,.08) !important; }
body.dark .section-title { color: #e2eaf8 !important; }
body.dark .divider { border-color: rgba(99,102,241,.06) !important; }
body.dark .type-toggle { background: #101e35 !important; }
body.dark .type-btn { color: #7fb3e8 !important; }
body.dark .autocomplete-list { background: #0b1628 !important; border-color: rgba(99,102,241,.2) !important; }
body.dark .autocomplete-item { color: #7fb3e8 !important; }
body.dark .autocomplete-item:hover { background: rgba(99,102,241,.12) !important; color: #a5b4fc !important; }
body.dark .autocomplete-item .sub { color: #4a6fa5 !important; }
body.dark .pc-section { background: rgba(55,48,163,.12) !important; border-color: rgba(99,102,241,.2) !important; }
body.dark .pc-btn { background: #101e35 !important; border-color: rgba(99,102,241,.15) !important; color: #a5b4fc !important; }
body.dark .back-btn { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; color: #a5b4fc !important; }

/* ── Confirmation modal (new reservation) ── */
body.dark #confirmModal .modal-card { background: #0b1628 !important; }
body.dark .qr-section { background: rgba(55,48,163,.12) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark .mrow { border-color: rgba(99,102,241,.06) !important; }
body.dark .mrow-value { color: #e2eaf8 !important; }

/* ── Delete confirm modal (SK profile) ── */
body.dark #deleteConfirmInput { background: #101e35 !important; border-color: #7f1d1d !important; color: #e2eaf8 !important; }
body.dark #deleteConfirmInput.input-success { background: #052e16 !important; border-color: #16a34a !important; }
body.dark #deleteConfirmInput.input-error { background: #2d0a0a !important; border-color: #f87171 !important; }
body.dark .delete-warning-box { background: rgba(127,29,29,.3) !important; border-color: #7f1d1d !important; }
body.dark .delete-warning-box p { color: #fca5a5 !important; }
body.dark .delete-warning-title { color: #fca5a5 !important; }
body.dark .delete-code-badge { background: rgba(127,29,29,.4) !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }
body.dark .delete-cancel-btn { background: #101e35 !important; border-color: rgba(99,102,241,.2) !important; color: #a5b4fc !important; }

/* ── My reservations (SK) — filter bar ── */
body.dark .filter-bar { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Sync badge (dashboard) ── */
body.dark .sync-badge { background: rgba(29,78,216,.2) !important; color: #7fb3e8 !important; border-color: rgba(59,130,246,.2) !important; }

/* ── Calendar dark overrides (FullCalendar) ── */
body.dark .fc-toolbar-title { color: #e2eaf8 !important; }
body.dark .fc-daygrid-day-number { color: #7fb3e8 !important; }
body.dark .fc-col-header-cell-cushion { color: #4a6fa5 !important; }
body.dark .fc-day-today { background: rgba(55,48,163,.15) !important; }
body.dark .fc-theme-standard td,
body.dark .fc-theme-standard th,
body.dark .fc-theme-standard .fc-scrollgrid { border-color: #101e35 !important; }
body.dark .fc-daygrid-day { background: #0b1628 !important; }
body.dark .fc-daygrid-day:hover { background-color: rgba(99,102,241,.08) !important; }

/* ── Date modal (dashboard) ── */
body.dark #dateModal .modal-card { background: #0b1628 !important; }
body.dark .date-row { border-color: #101e35 !important; }
body.dark .date-row:hover { background: #101e35 !important; }
body.dark #modalDateTitle { color: #e2eaf8 !important; }
body.dark #modalDateSub { color: #4a6fa5 !important; }

/* ── Quota wrap (sidebar) ── */
body.dark .quota-wrap { background: rgba(99,102,241,.07) !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .quota-track { background: rgba(99,102,241,.15) !important; }

/* ── Nav section labels ── */
body.dark .nav-section-lbl { color: #1e3a5f !important; }
body.dark .brand-tag,
body.dark .brand-sub { color: #4a6fa5 !important; }

/* ── Scrollbars ── */
body.dark ::-webkit-scrollbar-thumb { background: #1e3a5f !important; }
body.dark ::-webkit-scrollbar-track { background: #060e1e !important; }
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/sk/dashboard',       'icon' => 'fa-house',        'label' => 'Dashboard',        'key' => 'dashboard'],
        ['url' => '/sk/reservations',    'icon' => 'fa-calendar-alt', 'label' => 'All Reservations', 'key' => 'reservations'],
        ['url' => '/sk/new-reservation', 'icon' => 'fa-plus',         'label' => 'New Reservation',  'key' => 'new-reservation'],
        ['url' => '/sk/user-requests',   'icon' => 'fa-users',        'label' => 'User Requests',    'key' => 'user-requests'],
        ['url' => '/sk/my-reservations', 'icon' => 'fa-calendar',     'label' => 'My Reservations',  'key' => 'my-reservations'],
        ['url' => '/sk/books',           'icon' => 'fa-book-open',    'label' => 'Library',          'key' => 'books'],
        ['url' => '/sk/scanner',         'icon' => 'fa-qrcode',       'label' => 'Scanner',          'key' => 'scanner'],
        ['url' => '/sk/profile',         'icon' => 'fa-user',         'label' => 'Profile',          'key' => 'profile'],
    ];
    ?>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal-back" onclick="if(event.target===this)closeModal()">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px">
                <div style="width:52px;height:52px;background:var(--indigo-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px"><i class="fa-solid fa-clipboard-list" style="font-size:1.3rem;color:var(--indigo)"></i></div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:#94a3b8;margin-top:4px">Review details before saving.</p>
            </div>
            <div style="background:#f8fafc;border-radius:var(--r-md);padding:14px 16px;border:1px solid rgba(99,102,241,.08);margin-bottom:14px" id="modalSummary"></div>
            <div id="qrWrap" style="display:none" class="qr-section" style="margin-bottom:14px">
                <p style="font-size:.6rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:var(--indigo)">E-Ticket Preview</p>
                <canvas id="qrCanvas" style="border-radius:10px"></canvas>
                <p id="qrText" style="font-size:.7rem;color:#94a3b8;font-family:var(--mono);text-align:center;word-break:break-all"></p>
                <button onclick="downloadQR()" style="display:flex;align-items:center;gap:7px;padding:9px 16px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font)"><i class="fa-solid fa-download" style="font-size:.7rem"></i> Download E-Ticket</button>
            </div>
            <div id="modalActions" style="display:flex;gap:10px">
                <button type="button" onclick="closeModal()" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font)">Cancel</button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" style="flex:2;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 12px rgba(55,48,163,.28)"><i class="fa-solid fa-check" style="font-size:.8rem"></i> Confirm & Save</button>
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">SK Officer Portal</div>
                <div class="brand-name">my<em>Space.</em></div>
                <div class="brand-sub">Community Management</div>
            </div>
            <div class="user-card">
                <div class="user-avatar"><?= $avatarLetter ?></div>
                <div style="min-width:0">
                    <div class="user-name-txt"><?= esc($sk_name) ?></div>
                    <div class="user-role-txt">SK Officer</div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-lbl">Menu</div>
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']);
                    $showBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
                ?>
                    <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>"></i></div>
                        <?= $item['label'] ?>
                        <?php if ($showBadge): ?><span class="nav-badge"><?= $pendingUserCount ?></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="/logout" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08)"><i class="fa-solid fa-arrow-right-from-bracket" style="color:#f87171"></i></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <!-- MOBILE NAV -->
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
            ?>
                <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= esc($item['label']) ?>">
                    <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem"></i>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171"></i></a>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="main-area">
        <!-- Topbar -->
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px" class="fade-up">
            <div>
                <p style="font-size:.7rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px">SK Portal</p>
                <h2 class="greeting-name" style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1">New Reservation</h2>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Register a manual entry into the system.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                    <span id="dark-icon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <a href="/sk/my-reservations" class="back-btn"><i class="fa-solid fa-chevron-left" style="font-size:.75rem"></i> Back</a>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
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

                <!-- Visitor type -->
                <div style="margin-bottom:22px">
                    <label class="field-label" style="margin-bottom:8px">Visitor Classification</label>
                    <div class="type-toggle">
                        <button type="button" class="type-btn active" id="btnUser" onclick="setType('User')">
                            <i class="fa-solid fa-user" style="font-size:.8rem"></i> Registered User
                        </button>
                        <button type="button" class="type-btn" id="btnVisitor" onclick="setType('Visitor')">
                            <i class="fa-solid fa-person-walking" style="font-size:.8rem"></i> Walk-in Visitor
                        </button>
                    </div>
                </div>

                <hr class="divider">

                <!-- Personal details -->
                <div style="margin-bottom:22px">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-id-badge" style="color:var(--indigo);font-size:.9rem"></i></div>
                        <div>
                            <div class="section-title">Personal Details</div>
                        </div>
                    </div>
                    <div id="userFields" style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <div>
                            <label class="field-label">Full Name</label>
                            <div class="autocomplete-wrap">
                                <input type="text" id="userNameInput" class="field-input" placeholder="Type to search users…" autocomplete="off">
                                <ul id="autocompleteList" class="autocomplete-list" style="display:none"></ul>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="userEmailDisplay" class="field-input" placeholder="Auto-filled on selection" readonly>
                            <p style="font-size:.65rem;color:#94a3b8;margin-top:4px">Fills automatically when a user is selected</p>
                        </div>
                    </div>
                    <div id="visitorFields" style="display:none;grid-template-columns:1fr 1fr;gap:14px">
                        <div><label class="field-label">Full Name</label><input type="text" id="visitorNameInput" class="field-input" placeholder="Enter visitor's full name"></div>
                        <div><label class="field-label">Email Address</label><input type="email" id="visitorEmailInput" class="field-input" placeholder="Enter email (optional)"></div>
                    </div>
                </div>

                <hr class="divider">

                <!-- Resource & Schedule -->
                <div style="margin-bottom:22px">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.9rem"></i></div>
                        <div>
                            <div class="section-title">Resource & Schedule</div>
                        </div>
                    </div>
                    <div style="margin-bottom:14px">
                        <label class="field-label">Select Asset / Resource</label>
                        <select id="resourceSelect" name="resource_id" class="field-input" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>" data-name="<?= htmlspecialchars($res['name']) ?>"><?= htmlspecialchars($res['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="pcSection" style="display:none;margin-bottom:14px" class="pc-section">
                        <label class="field-label" style="color:var(--indigo);margin-bottom:10px;display:block">Assign Workstation(s)</label>
                        <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:8px">
                            <?php foreach ($pcs as $pc): ?>
                                <button type="button" onclick="togglePc('<?= htmlspecialchars($pc['pc_number']) ?>',this)" data-pc="<?= htmlspecialchars($pc['pc_number']) ?>" class="pc-btn"><?= htmlspecialchars($pc['pc_number']) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <p style="font-size:.72rem;color:var(--indigo);font-weight:600;margin-top:10px">Selected: <span id="pcSelectedLabel">None</span></p>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px" id="dateTimeGrid">
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
                    <div id="purposeOtherWrap" style="display:none;margin-top:10px">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" class="field-input" placeholder="Describe the purpose…">
                    </div>
                </div>

                <button type="button" onclick="previewReservation()" class="submit-btn">
                    <i class="fa-solid fa-eye" style="font-size:.85rem"></i> Preview & Confirm
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
            const matches = allUsers.filter(u => (u.name || '').toLowerCase().includes(q) || (u.full_name || '').toLowerCase().includes(q) || (u.email || '').toLowerCase().includes(q)).slice(0, 8);
            if (!matches.length) {
                autocompleteList.style.display = 'none';
                return;
            }
            matches.forEach(u => {
                const displayName = u.full_name || u.name || '';
                const li = document.createElement('li');
                li.className = 'autocomplete-item';
                li.innerHTML = `<div style="font-weight:600">${displayName}</div><div class="sub">${u.email}</div>`;
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
            const date = document.getElementById('resDate').value,
                startTime = document.getElementById('startTime').value,
                endTime = document.getElementById('endTime').value;
            const purposeVal = document.getElementById('purposeSelect').value,
                purposeOther = document.getElementById('purposeOther').value.trim();
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
                ['Purpose', purposeFinal || '—']
            ];
            document.getElementById('modalSummary').innerHTML = rows.map(([l, v]) => `<div class="mrow"><span class="mrow-label">${l}</span><span class="mrow-value">${v}</span></div>`).join('');
            document.getElementById('qrWrap').style.display = 'none';
            document.getElementById('confirmBtn').style.display = 'flex';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.8rem"></i> Saving…';
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
            icon.innerHTML = isDark ? '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>' : '<i class="fa-regular fa-sun" style="font-size:.85rem"></i>';
            localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sk_theme') === 'dark') {
                document.body.classList.add('dark');
                const icon = document.getElementById('dark-icon');
                if (icon) icon.innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>';
            }
            document.documentElement.classList.remove('dark-pre');

            function checkGrid() {
                const uf = document.getElementById('userFields'),
                    vf = document.getElementById('visitorFields'),
                    cols = window.innerWidth < 640 ? '1fr' : '1fr 1fr';
                [uf, vf].forEach(el => {
                    if (el) el.style.gridTemplateColumns = cols;
                });
                const dg = document.getElementById('dateTimeGrid');
                if (dg) dg.style.gridTemplateColumns = window.innerWidth < 640 ? '1fr' : '1fr 1fr 1fr';
            }
            checkGrid();
            window.addEventListener('resize', checkGrid);
        });
    </script>
</body>

</html>