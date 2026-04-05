<?php
$page    = $page    ?? 'reservations';
$sk_name = session()->get('name') ?? session()->get('username') ?? 'SK Officer';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>All Reservations | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
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

        .nav-link.active .nav-badge {
            background: rgba(255, 255, 255, .22);
            color: #fff
        }

        .sidebar-footer {
            flex-shrink: 0;
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
            width: 100%;
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 48px;
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            color: #64748b;
            position: relative;
            transition: background .15s, color .15s;
            font-size: .65rem;
            font-weight: 600
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

        /* Cards & surfaces */
        .card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm)
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

        /* Stat cards */
        .stat-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .08);
            border-radius: var(--r-lg);
            padding: 16px 18px;
            border-left-width: 4px;
            box-shadow: var(--shadow-sm);
            transition: transform var(--ease), box-shadow var(--ease);
            cursor: pointer
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md)
        }

        .stat-card.ring {
            box-shadow: 0 0 0 2px var(--indigo)
        }

        /* Quick tabs */
        .qtab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: .4rem .9rem;
            border-radius: var(--r-sm);
            font-size: .78rem;
            font-weight: 700;
            transition: all var(--ease);
            cursor: pointer;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
            color: #64748b;
            background: white
        }

        .qtab:hover {
            border-color: var(--indigo);
            color: var(--indigo)
        }

        .qtab.active {
            background: var(--indigo);
            color: white;
            border-color: var(--indigo);
            box-shadow: 0 4px 12px -2px rgba(55, 48, 163, .3)
        }

        /* Search field */
        .search-field {
            background: white;
            border: 1px solid rgba(99, 102, 241, .12);
            border-radius: var(--r-sm);
            padding: .7rem 1rem .7rem 2.5rem;
            font-size: .875rem;
            font-family: var(--font);
            color: #0f172a;
            transition: all .2s;
            width: 100%;
            outline: none
        }

        .search-field:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08)
        }

        /* Table */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px
        }

        thead th {
            background: #f8fafc;
            font-weight: 800;
            text-transform: uppercase;
            font-size: .6rem;
            letter-spacing: .12em;
            color: #94a3b8;
            padding: .85rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
            cursor: pointer;
            user-select: none
        }

        thead th:hover {
            color: var(--indigo)
        }

        thead th .sort-icon {
            opacity: .35;
            margin-left: 4px;
            font-size: .6rem
        }

        thead th.sorted .sort-icon {
            opacity: 1;
            color: var(--indigo)
        }

        td {
            padding: .875rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle
        }

        tbody tr:last-child td {
            border-bottom: none
        }

        tbody tr {
            transition: background .12s;
            cursor: pointer
        }

        tbody tr:hover td {
            background: #eef2ff
        }

        /* Mobile cards */
        .res-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            padding: 1rem 1.1rem;
            cursor: pointer;
            transition: all .18s;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm)
        }

        .res-card:hover {
            border-color: var(--indigo-border);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px)
        }

        .res-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 0 4px 4px 0
        }

        .res-card[data-status="pending"]::before {
            background: #fbbf24
        }

        .res-card[data-status="approved"]::before {
            background: #6366f1
        }

        .res-card[data-status="claimed"]::before {
            background: #a855f7
        }

        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before {
            background: #ef4444
        }

        .res-card[data-status="unclaimed"]::before {
            background: #fb923c
        }

        .res-card[data-status="expired"]::before {
            background: #94a3b8
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: .28rem .7rem;
            border-radius: 9px;
            font-size: .62rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            white-space: nowrap
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e
        }

        .badge-approved {
            background: #e0e7ff;
            color: #3730a3
        }

        .badge-declined,
        .badge-canceled {
            background: #fee2e2;
            color: #991b1b
        }

        .badge-claimed {
            background: #f3e8ff;
            color: #6b21a8
        }

        .badge-expired {
            background: #f1f5f9;
            color: #64748b
        }

        .badge-unclaimed {
            background: #fff7ed;
            color: #c2410c;
            border: 1px dashed #fdba74
        }

        /* Action buttons */
        .btn-approve {
            background: #e0e7ff;
            color: #3730a3;
            border: none;
            border-radius: 9px;
            padding: .45rem .85rem;
            font-size: .72rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: var(--font)
        }

        .btn-approve:hover {
            background: var(--indigo);
            color: white
        }

        .btn-decline {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            border-radius: 9px;
            padding: .45rem .6rem;
            font-size: .72rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            font-family: var(--font)
        }

        .btn-decline:hover {
            background: #ef4444;
            color: white
        }

        /* Print pills */
        .print-pill-yes {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: .18rem .55rem;
            border-radius: 999px;
            font-size: .62rem;
            font-weight: 800;
            background: #e0e7ff;
            color: #3730a3;
            white-space: nowrap
        }

        .print-pill-no {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: .18rem .55rem;
            border-radius: 999px;
            font-size: .62rem;
            font-weight: 800;
            background: #f1f5f9;
            color: #64748b;
            white-space: nowrap
        }

        /* Overlay / modal */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            align-items: center;
            justify-content: center
        }

        .overlay.open {
            display: flex
        }

        .overlay-bg {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(6px)
        }

        .modal-box {
            position: relative;
            margin: auto;
            background: white;
            border-radius: var(--r-xl);
            width: 94%;
            max-width: 520px;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: popIn .22s cubic-bezier(.34, 1.56, .64, 1) both
        }

        .modal-box.sm {
            max-width: 380px
        }

        .sheet-handle {
            display: none;
            width: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 999px;
            margin: 10px auto 0
        }

        @media(max-width:639px) {
            .overlay#detailModal {
                align-items: flex-end
            }

            .overlay#detailModal .modal-box {
                margin: 0;
                width: 100%;
                max-width: 100%;
                border-radius: 28px 28px 0 0;
                max-height: 92vh;
                animation: slideUp .28s cubic-bezier(.34, 1.2, .64, 1) both
            }

            .sheet-handle {
                display: block
            }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(.92) translateY(16px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(60px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        /* Detail rows */
        .drow {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: .7rem 0;
            border-bottom: 1px solid #f1f5f9
        }

        .drow:last-child {
            border-bottom: none
        }

        .dicon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--indigo-light);
            color: var(--indigo);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            flex-shrink: 0
        }

        .dlabel {
            font-size: .6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            margin-bottom: 3px
        }

        .dvalue {
            font-size: .88rem;
            font-weight: 700;
            color: #0f172a
        }

        /* Modal action buttons */
        .btn-confirm-approve {
            background: var(--indigo);
            color: white;
            border: none;
            border-radius: var(--r-sm);
            padding: .85rem;
            font-size: .875rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: var(--font);
            flex: 1
        }

        .btn-confirm-approve:hover:not(:disabled) {
            background: #312e81
        }

        .btn-confirm-decline {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: var(--r-sm);
            padding: .85rem;
            font-size: .875rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: var(--font);
            flex: 1
        }

        .btn-confirm-decline:hover:not(:disabled) {
            background: #dc2626
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: var(--r-sm);
            padding: .85rem;
            font-size: .875rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: var(--font);
            flex: 1
        }

        .btn-cancel:hover {
            background: #e2e8f0
        }

        /* Ticket */
        .ticket-section {
            border: 2px dashed var(--indigo-border);
            border-radius: var(--r-lg);
            padding: 1.5rem;
            background: var(--indigo-light);
            display: flex;
            flex-direction: column;
            align-items: center
        }

        .unclaimed-banner {
            background: #fff7ed;
            border: 1.5px dashed #fdba74;
            border-radius: var(--r-md);
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 1.75rem 1rem
        }

        .unclaimed-banner .ub-icon {
            width: 32px;
            height: 32px;
            background: #fed7aa;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c2410c;
            font-size: .8rem;
            flex-shrink: 0
        }

        /* Print log form */
        #dPrintLogForm {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: var(--r-lg);
            padding: 1rem 1.25rem;
            margin: 0 1.75rem 1rem
        }

        #dPrintLogForm label {
            font-size: .6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            display: block;
            margin-bottom: 6px
        }

        #dPrintLogForm input[type=number] {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: .55rem .8rem;
            font-size: .875rem;
            font-family: var(--font);
            color: #0f172a
        }

        #dPrintLogForm input[type=number]:focus {
            outline: none;
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08)
        }

        .btn-save-print {
            background: var(--indigo);
            color: white;
            border: none;
            border-radius: 10px;
            padding: .6rem 1.1rem;
            font-size: .78rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font);
            white-space: nowrap
        }

        .btn-save-print:hover:not(:disabled) {
            background: #312e81
        }

        .btn-save-print:disabled {
            opacity: .6;
            cursor: not-allowed
        }

        #printSaveMsg {
            font-size: .72rem;
            font-weight: 700;
            margin-top: 6px;
            min-height: 18px
        }

        /* Empty */
        .empty-state {
            padding: 5rem 2rem;
            text-align: center
        }

        .card-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px dashed #e2e8f0
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .fade-up {
            animation: fadeUp .35s ease both
        }

        /* Auto-refresh pill */
        #autoRefreshIndicator {
            position: fixed;
            bottom: calc(90px + env(safe-area-inset-bottom, 16px));
            right: 16px;
            background: rgba(15, 23, 42, .88);
            backdrop-filter: blur(8px);
            color: white;
            font-family: var(--font);
            font-size: .68rem;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 999px;
            z-index: 90;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: var(--shadow-md);
            cursor: pointer
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

        body.dark .card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .stat-card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .search-field {
            background: #101e35;
            border-color: rgba(99, 102, 241, .18);
            color: #e2eaf8
        }

        body.dark thead th {
            background: #060e1e;
            color: #4a6fa5
        }

        body.dark td {
            border-color: #101e35
        }

        body.dark tbody tr:hover td {
            background: rgba(99, 102, 241, .08)
        }

        body.dark .modal-box {
            background: #0b1628;
            color: #e2eaf8
        }

        body.dark .drow {
            border-color: #101e35
        }

        body.dark .dvalue {
            color: #e2eaf8
        }

        body.dark .qtab {
            background: #101e35;
            border-color: rgba(99, 102, 241, .15);
            color: #a5b4fc
        }

        body.dark .qtab.active {
            background: var(--indigo);
            color: white
        }

        body.dark .qtab:hover {
            border-color: var(--indigo);
            color: #c7d2fe
        }

        body.dark .res-card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark #dPrintLogForm {
            background: #101e35;
            border-color: rgba(99, 102, 241, .12)
        }

        body.dark #dPrintLogForm input[type=number] {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .18);
            color: #e2eaf8
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

        body.dark .icon-btn {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .15);
            color: #a5b4fc
        }

        body.dark .icon-btn:hover {
            background: rgba(99, 102, 241, .12)
        }

        body.dark .btn-cancel {
            background: #101e35;
            color: #a5b4fc
        }

        @media (min-width: 768px) {
            #mobileCardList {
                display: none !important;
            }

            #mobileEmpty {
                display: none !important;
            }
        }

        @media (max-width: 767px) {
            #desktopTableWrap {
                display: none !important;
            }
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
    $processed = [];
    foreach (($reservations ?? []) as $res) {
        $s = strtolower($res['status'] ?? 'pending');
        $isClaimed = in_array($res['claimed'] ?? false, [true, 1, 't', 'true', '1'], true);
        if ($isClaimed) {
            $s = 'claimed';
        } elseif ($s === 'approved') {
            $edt = strtotime(($res['reservation_date'] ?? '') . ' ' . ($res['end_time'] ?? '23:59'));
            if ($edt && $edt < time()) $s = 'unclaimed';
        } elseif ($s === 'pending') {
            $rdt = strtotime($res['reservation_date'] ?? '');
            if ($rdt && $rdt < strtotime('today')) $s = 'expired';
        }
        $res['_status'] = $s;
        $res['_unclaimed'] = ($s === 'unclaimed');
        $processed[] = $res;
    }
    $counts = [
        'all'       => count($processed),
        'pending'   => count(array_filter($processed, fn($r) => $r['_status'] === 'pending')),
        'approved'  => count(array_filter($processed, fn($r) => $r['_status'] === 'approved')),
        'claimed'   => count(array_filter($processed, fn($r) => $r['_status'] === 'claimed')),
        'declined'  => count(array_filter($processed, fn($r) => in_array($r['_status'], ['declined', 'canceled']))),
        'expired'   => count(array_filter($processed, fn($r) => $r['_status'] === 'expired')),
        'unclaimed' => count(array_filter($processed, fn($r) => $r['_status'] === 'unclaimed')),
    ];
    $printLogMap  = $printLogMap ?? [];
    $statusIcons  = ['pending' => 'fa-clock', 'approved' => 'fa-circle-check', 'claimed' => 'fa-check-double', 'declined' => 'fa-xmark', 'canceled' => 'fa-ban', 'expired' => 'fa-hourglass-end', 'unclaimed' => 'fa-ticket'];
    $avatarLetter = strtoupper(mb_substr(trim($sk_name), 0, 1));
    ?>

    <form id="approveForm" method="POST" action="<?= base_url('sk/approve') ?>" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
    <form id="declineForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="declineId"></form>

    <!-- DETAIL MODAL -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('detail')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div class="flex items-start justify-between px-7 pt-5 pb-3">
                <div>
                    <p id="dId" class="text-xs font-black text-slate-400 font-mono mb-1"></p>
                    <h3 class="text-xl font-black text-slate-900">Reservation Details</h3>
                </div>
                <button onclick="closeModal('detail')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0 mt-0.5"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="dStatusBar" class="mx-7 mb-3 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>
            <div id="dUnclaimedBanner" class="unclaimed-banner" style="display:none">
                <div class="ub-icon"><i class="fa-solid fa-ticket"></i></div>
                <div>
                    <p class="font-black text-sm text-orange-700">Not Yet Claimed</p>
                    <p class="text-xs text-orange-500 font-medium mt-0.5">Approved but the e-ticket was never scanned.</p>
                </div>
            </div>
            <div class="px-7 pb-2">
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-user"></i></div>
                    <div>
                        <p class="dlabel">Requestor</p>
                        <p id="dName" class="dvalue"></p>
                        <p id="dEmail" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-desktop"></i></div>
                    <div>
                        <p class="dlabel">Resource</p>
                        <p id="dResource" class="dvalue"></p>
                        <p id="dPc" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <p class="dlabel">Schedule</p>
                        <p id="dDate" class="dvalue"></p>
                        <p id="dTime" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-pen-to-square"></i></div>
                    <div>
                        <p class="dlabel">Purpose</p>
                        <p id="dPurpose" class="dvalue"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-id-badge"></i></div>
                    <div>
                        <p class="dlabel">Visitor Type</p>
                        <p id="dType" class="dvalue"></p>
                    </div>
                </div>
                <div class="drow" id="dApprovedByRow" style="display:none">
                    <div class="dicon" id="dApprovedByIcon"><i class="fa-solid fa-user-check"></i></div>
                    <div>
                        <p class="dlabel" id="dApprovedByLabel">Approved By</p>
                        <p id="dApprovedByName" class="dvalue"></p>
                        <p id="dApprovedByEmail" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                        <p id="dApprovedAt" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-regular fa-clock"></i></div>
                    <div>
                        <p class="dlabel">Submitted</p>
                        <p id="dCreated" class="dvalue"></p>
                    </div>
                </div>
            </div>
            <div id="dQr" class="mx-7 mb-4 ticket-section" style="display:none">
                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-3">E-Ticket</p>
                <canvas id="qrCanvas" class="rounded-xl"></canvas>
                <p id="dTicketCode" class="text-xs text-slate-400 font-mono mt-2 text-center break-all px-2"></p>
                <button onclick="downloadTicket()" class="mt-3 flex items-center gap-2 px-5 py-2 bg-indigo-700 text-white rounded-xl font-bold text-sm hover:bg-indigo-800 transition"><i class="fa-solid fa-download text-xs"></i> Download E-Ticket</button>
            </div>
            <div id="dClaimed" class="mx-7 mb-4 bg-purple-50 border-2 border-dashed border-purple-200 rounded-2xl p-5 text-center" style="display:none">
                <i class="fa-solid fa-check-double text-2xl text-purple-500 mb-1 block"></i>
                <p class="font-black text-purple-700 text-sm">Ticket Already Claimed</p>
                <p class="text-xs text-purple-400 mt-0.5">This reservation has been used.</p>
            </div>
            <div id="dPrintLog" class="mx-7 mb-3 rounded-2xl px-4 py-3 border border-slate-100 bg-slate-50 flex items-center gap-3" style="display:none">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-print text-indigo-600 text-sm"></i></div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Print Log</p>
                    <p id="dPrintText" class="text-sm font-bold text-slate-700"></p>
                </div>
                <span id="dPrintBadge" class="text-[10px] font-black px-2.5 py-1 rounded-full flex-shrink-0"></span>
            </div>
            <div id="dPrintLogForm">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-print text-indigo-500"></i> Log Print for this Reservation</p>
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label>Pages Printed <span class="text-slate-300 font-normal normal-case tracking-normal">(0 = not printed)</span></label>
                        <input type="number" id="printPagesInput" min="0" max="999" value="0" placeholder="0">
                    </div>
                    <button id="savePrintBtn" class="btn-save-print" onclick="savePrintLog()"><i class="fa-solid fa-floppy-disk text-xs"></i> Save</button>
                </div>
                <p id="printSaveMsg" class="text-slate-400"></p>
            </div>
            <div id="dActions" class="px-7 py-5 border-t border-slate-100 flex gap-3 flex-wrap mt-2"></div>
        </div>
    </div>

    <!-- Approve modal -->
    <div id="approveModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('approve')"></div>
        <div class="modal-box sm">
            <div class="px-7 pt-7 pb-5 text-center">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-circle-check"></i></div>
                <h3 class="text-xl font-black text-slate-900">Approve Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This will confirm the reservation.</p>
                <p id="approveConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-7 pb-7 flex gap-3">
                <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmApproveBtn" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
            </div>
        </div>
    </div>

    <!-- Decline modal -->
    <div id="declineModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('decline')"></div>
        <div class="modal-box sm">
            <div class="px-7 pt-7 pb-5 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <h3 class="text-xl font-black text-slate-900">Decline Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p id="declineConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-7 pb-7 flex gap-3">
                <button class="btn-cancel" onclick="closeModal('decline')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmDeclineBtn" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>
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
                    <div class="user-name-txt"><?= htmlspecialchars($sk_name) ?></div>
                    <div class="user-role-txt">SK Officer</div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-lbl">Menu</div>
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']);
                ?>
                    <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>"></i></div>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] === 'reservations' && ($counts['pending'] ?? 0) > 0): ?>
                            <span class="nav-badge"><?= $counts['pending'] ?></span>
                        <?php endif; ?>
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
                <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= $item['label'] ?>">
                    <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem"></i>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171"></i></a>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="main-area">
        <!-- Header -->
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:24px" class="fade-up">
            <div>
                <p style="font-size:.7rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px">SK Portal</p>
                <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1">All Reservations</h2>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">
                    <?= $counts['all'] ?> total record<?= $counts['all'] != 1 ? 's' : '' ?>
                    <?php if ($counts['unclaimed'] > 0): ?> · <span style="color:#f59e0b;font-weight:700"><?= $counts['unclaimed'] ?> unclaimed</span><?php endif; ?>
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <button onclick="exportCSV()" style="display:flex;align-items:center;gap:7px;padding:10px 18px;background:var(--indigo);color:white;border:none;border-radius:var(--r-sm);font-size:.82rem;font-weight:700;cursor:pointer;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);transition:all var(--ease)" onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'">
                    <i class="fa-solid fa-file-csv" style="font-size:.8rem"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- Stat cards -->
        <div style="display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;margin-bottom:20px" id="statGrid">
            <?php foreach (
                [
                    ['Total',    $counts['all'],       'border-color:#3730a3', 'color:#3730a3', 'all'],
                    ['Pending',  $counts['pending'],   'border-color:#d97706', 'color:#d97706', 'pending'],
                    ['Approved', $counts['approved'],  'border-color:#4338ca', 'color:#4338ca', 'approved'],
                    ['Claimed',  $counts['claimed'],   'border-color:#7c3aed', 'color:#7c3aed', 'claimed'],
                    ['Declined', $counts['declined'],  'border-color:#dc2626', 'color:#dc2626', 'declined'],
                    ['Unclaimed', $counts['unclaimed'], 'border-color:#f59e0b', 'color:#f59e0b', 'unclaimed'],
                ] as [$lbl, $val, $bc, $c, $key]
            ): ?>
                <div class="stat-card" style="<?= $bc ?>" onclick="filterByStatus('<?= $key ?>')" data-filter="<?= $key ?>">
                    <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#94a3b8;margin-bottom:6px"><?= $lbl ?></p>
                    <p style="font-size:1.8rem;font-weight:800;<?= $c ?>;font-family:var(--mono);line-height:1;letter-spacing:-.04em"><?= $val ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div style="margin-bottom:16px;padding:13px 18px;background:#eef2ff;border:1px solid var(--indigo-border);color:var(--indigo);font-weight:700;border-radius:var(--r-md);display:flex;align-items:center;gap:10px;font-size:.875rem" class="fade-up">
                <i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div style="margin-bottom:16px;padding:13px 18px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-weight:700;border-radius:var(--r-md);display:flex;align-items:center;gap:10px;font-size:.875rem" class="fade-up">
                <i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="card" style="padding:16px 20px;margin-bottom:14px">
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:14px">
                <div style="position:relative;flex:1;min-width:200px">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.75rem;pointer-events:none"></i>
                    <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="search-field" oninput="applyFilters()">
                </div>
                <div style="position:relative;width:160px">
                    <i class="fa-regular fa-calendar" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.75rem;pointer-events:none"></i>
                    <input id="dateInput" type="date" class="search-field" style="padding-left:2.5rem" onchange="applyFilters()">
                </div>
                <button onclick="clearFilters()" style="display:flex;align-items:center;gap:6px;padding:10px 16px;background:#f1f5f9;border:none;border-radius:var(--r-sm);font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:var(--font);transition:all var(--ease)" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </button>
            </div>
            <div style="display:flex;gap:6px;overflow-x:auto;padding-bottom:2px">
                <button class="qtab active" data-tab="all" onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group" style="font-size:.7rem"></i> All <span style="font-size:.62rem;font-weight:800;opacity:.7"><?= $counts['all'] ?></span></button>
                <button class="qtab" data-tab="pending" onclick="setTab(this,'pending')"><i class="fa-solid fa-clock" style="font-size:.7rem"></i> Pending <?php if ($counts['pending'] > 0): ?><span style="background:#f59e0b;color:white;font-size:.6rem;font-weight:800;padding:1px 6px;border-radius:999px"><?= $counts['pending'] ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="approved" onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check" style="font-size:.7rem"></i> Approved</button>
                <button class="qtab" data-tab="unclaimed" onclick="setTab(this,'unclaimed')"><i class="fa-solid fa-ticket" style="font-size:.7rem"></i> Unclaimed<?php if ($counts['unclaimed'] > 0): ?><span style="background:#f59e0b;color:white;font-size:.6rem;font-weight:800;padding:1px 6px;border-radius:999px"><?= $counts['unclaimed'] ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="claimed" onclick="setTab(this,'claimed')"><i class="fa-solid fa-check-double" style="font-size:.7rem"></i> Claimed</button>
                <button class="qtab" data-tab="declined" onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark" style="font-size:.7rem"></i> Declined</button>
                <button class="qtab" data-tab="expired" onclick="setTab(this,'expired')"><i class="fa-solid fa-hourglass-end" style="font-size:.7rem"></i> Expired</button>
            </div>
        </div>

        <div style="padding:0 2px;margin-bottom:10px">
            <p id="resultCount" style="font-size:.72rem;font-weight:700;color:#94a3b8"></p>
        </div>

        <!-- Desktop table -->
        <div id="desktopTableWrap" class="card" style="overflow:hidden;margin-bottom:20px">
            <div class="table-wrap">
                <table id="resTable">
                    <thead>
                        <tr>
                            <th style="width:52px">ID</th>
                            <th onclick="sortTable(1)">User <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(2)">Resource <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(3)">Schedule <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th>Purpose</th>
                            <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(6)">Approved By <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th>Print</th>
                            <th class="text-right" style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (empty($processed)): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state"><i class="fa-solid fa-calendar-xmark" style="font-size:3rem;color:#e2e8f0;display:block;margin-bottom:14px"></i>
                                        <p style="font-weight:800;color:#94a3b8;font-size:1rem">No reservations yet</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($processed as $res):
                                $s = $res['_status'];
                                $isUnclaimed = $res['_unclaimed'];
                                $name = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                                $email = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '');
                                $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                                $pc = htmlspecialchars($res['pc_number'] ?? $res['pc_numbers'] ?? '');
                                $rawDate = $res['reservation_date'] ?? '';
                                $date = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                                $start = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                                $end = !empty($res['end_time']) ? date('g:i A', strtotime($res['end_time'])) : '—';
                                $purpose = htmlspecialchars($res['purpose'] ?? '—');
                                $type = htmlspecialchars($res['visitor_type'] ?? '—');
                                $created = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                                $code = $res['e_ticket_code'] ?? ('RES-' . $res['id'] . '-' . $rawDate);
                                $icon = $statusIcons[$s] ?? 'fa-circle';
                                $approverName = htmlspecialchars($res['approver_name'] ?? $res['approved_by_name'] ?? '');
                                $approverEmail = htmlspecialchars($res['approver_email'] ?? $res['approved_by_email'] ?? '');
                                $approvedAt = !empty($res['updated_at']) && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed']) ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                                $pl = $printLogMap[(int)$res['id']] ?? null;
                                $plPrinted = $pl !== null ? (bool)$pl['printed'] : null;
                                $plPages = $pl ? (int)($pl['pages'] ?? 0) : 0;
                                $plAt = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                                $isClaimed = in_array($res['claimed'] ?? false, [true, 1, 't', 'true', '1'], true);
                                $mdata = json_encode(['id' => $res['id'], 'status' => $s, 'name' => $name, 'email' => $email, 'resource' => $resource, 'pc' => $pc, 'date' => $date, 'rawDate' => $rawDate, 'start' => $start, 'end' => $end, 'purpose' => $purpose, 'type' => $type, 'created' => $created, 'code' => $code, 'claimed' => $isClaimed, 'unclaimed' => $isUnclaimed, 'approverName' => $approverName, 'approverEmail' => $approverEmail, 'approvedAt' => $approvedAt, 'plPrinted' => $plPrinted, 'plPages' => $plPages, 'plAt' => $plAt]);
                            ?>
                                <tr class="res-row" data-id="<?= $res['id'] ?>" data-status="<?= $s ?>" data-unclaimed="<?= $isUnclaimed ? '1' : '0' ?>" data-search="<?= strtolower("$name $resource $purpose $email $approverName") ?>" data-date="<?= $rawDate ?>" data-pl-printed="<?= $plPrinted === null ? '' : ($plPrinted ? 'Yes' : 'No') ?>" data-pl-pages="<?= $plPrinted ? $plPages : '' ?>" data-pl-at="<?= htmlspecialchars($plAt, ENT_QUOTES) ?>" onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                                    <td><span style="font-size:.72rem;font-weight:800;color:#94a3b8;font-family:var(--mono)">#<?= $res['id'] ?></span></td>
                                    <td>
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;line-height:1.3"><?= $name ?></p><?php if ($email): ?><p style="font-size:.7rem;color:#94a3b8;margin-top:2px"><?= $email ?></p><?php endif; ?>
                                    </td>
                                    <td>
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;line-height:1.3"><?= $resource ?></p><?php if ($pc): ?><p style="font-size:.7rem;color:#94a3b8;margin-top:2px"><i class="fa-solid fa-desktop" style="font-size:.65rem"></i> <?= $pc ?></p><?php endif; ?>
                                    </td>
                                    <td>
                                        <p style="font-size:.85rem;font-weight:700;color:#0f172a"><?= $date ?></p>
                                        <p style="font-size:.7rem;color:var(--indigo);font-weight:600;margin-top:2px"><?= $start ?> – <?= $end ?></p>
                                    </td>
                                    <td><span style="font-size:.82rem;color:#64748b;font-weight:500;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px"><?= $purpose ?></span></td>
                                    <td><span class="badge badge-<?= $s ?>"><i class="fa-solid <?= $icon ?>" style="font-size:.6rem"></i><?= ucfirst($s) ?></span></td>
                                    <td onclick="event.stopPropagation()">
                                        <?php if ($approverName && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed'])): ?>
                                            <div style="display:flex;align-items:center;gap:7px">
                                                <div style="width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;flex-shrink:0;<?= $s === 'declined' ? 'background:#fee2e2;color:#dc2626' : 'background:#e0e7ff;color:#3730a3' ?>"><?= mb_strtoupper(mb_substr($approverName, 0, 1)) ?></div>
                                                <div style="min-width:0">
                                                    <p style="font-size:.78rem;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100px"><?= $approverName ?></p><?php if ($approvedAt): ?><p style="font-size:.65rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $approvedAt ?></p><?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?><span style="font-size:.72rem;color:#cbd5e1;font-weight:700">—</span><?php endif; ?>
                                    </td>
                                    <td onclick="event.stopPropagation()">
                                        <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:.6rem"></i> <?= $plPages ?>pg</span>
                                        <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:.6rem"></i> No print</span>
                                        <?php else: ?><span style="font-size:.7rem;color:#cbd5e1;font-weight:700">—</span><?php endif; ?>
                                    </td>
                                    <td style="text-align:right" onclick="event.stopPropagation()">
                                        <?php if ($s === 'pending'): ?>
                                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px">
                                                <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve"><i class="fa-solid fa-check" style="font-size:.65rem"></i> Approve</button>
                                                <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-decline"><i class="fa-solid fa-xmark" style="font-size:.65rem"></i></button>
                                            </div>
                                        <?php elseif ($s === 'unclaimed'): ?><span style="font-size:.72rem;color:#f59e0b;font-weight:800;display:flex;align-items:center;gap:4px;justify-content:flex-end"><i class="fa-solid fa-ticket"></i> Unclaimed</span>
                                        <?php elseif ($s === 'approved'): ?><span style="font-size:.72rem;color:var(--indigo);font-weight:800;display:flex;align-items:center;gap:4px;justify-content:flex-end"><i class="fa-solid fa-circle-check"></i> Approved</span>
                                        <?php elseif ($s === 'claimed'): ?><span style="font-size:.72rem;color:#7c3aed;font-weight:800;display:flex;align-items:center;gap:4px;justify-content:flex-end"><i class="fa-solid fa-check-double"></i> Claimed</span>
                                        <?php else: ?><span style="font-size:.72rem;color:#cbd5e1;font-weight:600;font-style:italic">—</span><?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="padding:10px 20px;border-top:1px solid #f1f5f9;background:#f8fafc;display:flex;align-items:center;justify-content:space-between">
                <p id="tableFooter" style="font-size:.72rem;font-weight:700;color:#94a3b8"></p>
                <p style="font-size:.7rem;color:#cbd5e1;font-weight:600;display:none" class="sm:block">Click row to preview · Export CSV applies current filter</p>
            </div>
        </div>

        <!-- Mobile cards -->
        <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px">
            <?php if (empty($processed)): ?>
                <div class="card-empty"><i class="fa-solid fa-calendar-xmark" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:10px"></i>
                    <p style="font-weight:800;color:#94a3b8">No reservations yet</p>
                </div>
            <?php else: ?>
                <?php foreach ($processed as $res):
                    $s = $res['_status'];
                    $isUnclaimed = $res['_unclaimed'];
                    $name = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                    $email = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '');
                    $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                    $pc = htmlspecialchars($res['pc_number'] ?? $res['pc_numbers'] ?? '');
                    $rawDate = $res['reservation_date'] ?? '';
                    $date = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                    $start = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                    $end = !empty($res['end_time']) ? date('g:i A', strtotime($res['end_time'])) : '—';
                    $purpose = htmlspecialchars($res['purpose'] ?? '—');
                    $type = htmlspecialchars($res['visitor_type'] ?? '—');
                    $created = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                    $code = $res['e_ticket_code'] ?? ('RES-' . $res['id'] . '-' . $rawDate);
                    $icon = $statusIcons[$s] ?? 'fa-circle';
                    $approverName = htmlspecialchars($res['approver_name'] ?? $res['approved_by_name'] ?? '');
                    $approverEmail = htmlspecialchars($res['approver_email'] ?? $res['approved_by_email'] ?? '');
                    $approvedAt = !empty($res['updated_at']) && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed']) ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                    $pl = $printLogMap[(int)$res['id']] ?? null;
                    $plPrinted = $pl !== null ? (bool)$pl['printed'] : null;
                    $plPages = $pl ? (int)($pl['pages'] ?? 0) : 0;
                    $plAt = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                    $isClaimed = in_array($res['claimed'] ?? false, [true, 1, 't', 'true', '1'], true);
                    $mdata = json_encode(['id' => $res['id'], 'status' => $s, 'name' => $name, 'email' => $email, 'resource' => $resource, 'pc' => $pc, 'date' => $date, 'rawDate' => $rawDate, 'start' => $start, 'end' => $end, 'purpose' => $purpose, 'type' => $type, 'created' => $created, 'code' => $code, 'claimed' => $isClaimed, 'unclaimed' => $isUnclaimed, 'approverName' => $approverName, 'approverEmail' => $approverEmail, 'approvedAt' => $approvedAt, 'plPrinted' => $plPrinted, 'plPages' => $plPages, 'plAt' => $plAt]);
                    $avatarBg = ['pending' => 'background:#fef3c7;color:#92400e', 'approved' => 'background:#e0e7ff;color:#3730a3', 'claimed' => 'background:#f3e8ff;color:#6b21a8', 'declined' => 'background:#fee2e2;color:#dc2626', 'canceled' => 'background:#fee2e2;color:#dc2626', 'expired' => 'background:#f1f5f9;color:#64748b', 'unclaimed' => 'background:#fff7ed;color:#c2410c'][$s] ?? 'background:#f1f5f9;color:#64748b';
                ?>
                    <div class="res-card" data-id="<?= $res['id'] ?>" data-status="<?= $s ?>" data-unclaimed="<?= $isUnclaimed ? '1' : '0' ?>" data-search="<?= strtolower("$name $resource $purpose $email $approverName") ?>" data-date="<?= $rawDate ?>" data-pl-printed="<?= $plPrinted === null ? '' : ($plPrinted ? 'Yes' : 'No') ?>" data-pl-pages="<?= $plPrinted ? $plPages : '' ?>" data-pl-at="<?= htmlspecialchars($plAt, ENT_QUOTES) ?>" onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                            <div style="width:38px;height:38px;border-radius:12px;<?= $avatarBg ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0"><?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?></div>
                            <div style="flex:1;min-width:0">
                                <p style="font-weight:700;font-size:.85rem;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p><?php if ($email): ?><p style="font-size:.7rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="badge badge-<?= $s ?>" style="flex-shrink:0"><i class="fa-solid <?= $icon ?>" style="font-size:.6rem"></i><?= ucfirst($s) ?></span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
                            <div style="flex:1;min-width:0">
                                <p style="font-size:.78rem;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><i class="fa-solid fa-desktop" style="font-size:.65rem;color:#94a3b8;margin-right:3px"></i><?= $resource ?><?= $pc ? ' · ' . $pc : '' ?></p>
                                <p style="font-size:.72rem;color:#94a3b8;margin-top:2px"><i class="fa-regular fa-calendar" style="font-size:.65rem;margin-right:3px"></i><?= $date ?> <span style="color:var(--indigo);font-weight:700"><?= $start ?> – <?= $end ?></span></p>
                            </div>
                            <div class="card-print-pill" style="flex-shrink:0">
                                <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:.6rem"></i> <?= $plPages ?>pg</span>
                                <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:.6rem"></i> No print</span><?php endif; ?>
                            </div>
                        </div>
                        <p style="font-size:.72rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:10px"><?= $purpose ?></p>
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding-top:10px;border-top:1px solid #f1f5f9">
                            <div style="display:flex;align-items:center;gap:6px;min-width:0">
                                <?php if ($approverName && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed'])): ?>
                                    <div style="width:18px;height:18px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;flex-shrink:0;<?= $s === 'declined' ? 'background:#fee2e2;color:#dc2626' : 'background:#e0e7ff;color:#3730a3' ?>"><?= mb_strtoupper(mb_substr($approverName, 0, 1)) ?></div>
                                    <p style="font-size:.68rem;color:#64748b;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $s === 'declined' ? 'Declined' : 'Approved' ?> by <?= $approverName ?></p>
                                <?php else: ?><p style="font-size:.68rem;color:#cbd5e1;font-weight:600">#<?= $res['id'] ?></p><?php endif; ?>
                            </div>
                            <?php if ($s === 'pending'): ?>
                                <div style="display:flex;align-items:center;gap:6px;flex-shrink:0" onclick="event.stopPropagation()">
                                    <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve" style="padding:.35rem .7rem;font-size:.68rem"><i class="fa-solid fa-check" style="font-size:.6rem"></i> Approve</button>
                                    <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-decline" style="padding:.35rem .6rem;font-size:.68rem"><i class="fa-solid fa-xmark" style="font-size:.6rem"></i></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="mobileEmpty" class="card-empty" style="display:none">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:10px"></i>
            <p style="font-weight:800;color:#94a3b8">No reservations match</p>
            <p style="color:#cbd5e1;font-size:.85rem;margin-top:4px">Try adjusting your search or filters.</p>
        </div>
    </main>

    <script>
        const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
        const allCards = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
        let curTab = 'all',
            approveTargetId = null,
            declineTargetId = null;
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        let csrfName = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content') ?? 'csrf_token';

        function refreshCsrf(data) {
            if (data.csrf_hash && data.csrf_token) {
                csrfToken = data.csrf_hash;
                csrfName = data.csrf_token;
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', csrfToken);
                document.querySelector('meta[name="csrf-name"]')?.setAttribute('content', csrfName);
            }
        }
        const printLogMap = {};
        <?php foreach ($printLogMap as $resId => $pl): ?>
            printLogMap[<?= (int)$resId ?>] = {
                printed: <?= isset($pl['printed']) ? (in_array($pl['printed'], [true, 1, 't', 'true', '1'], true) ? 'true' : 'false') : 'false' ?>,
                pages: <?= (int)($pl['pages'] ?? 0) ?>,
                at: "<?= !empty($pl['printed_at']) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '' ?>"
            };
        <?php endforeach; ?>
        let _currentReservationId = null;

        async function savePrintLog() {
            const rid = _currentReservationId,
                pages = parseInt(document.getElementById('printPagesInput').value, 10) || 0;
            const btn = document.getElementById('savePrintBtn'),
                msg = document.getElementById('printSaveMsg');
            if (!rid) {
                msg.textContent = 'No reservation selected.';
                msg.style.color = '#ef4444';
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Saving…';
            msg.textContent = '';
            msg.style.color = '';
            const body = new FormData();
            body.append(csrfName, csrfToken);
            body.append('reservation_id', rid);
            body.append('printed', pages > 0 ? 1 : 0);
            body.append('pages', pages);
            try {
                const res = await fetch('<?= base_url('sk/log-print') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body
                });
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch {
                    throw new Error(`Server error (${res.status})`);
                }
                if (data.ok) {
                    refreshCsrf(data);
                    const now = new Date();
                    const fmt = now.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    }) + ' · ' + now.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit'
                    });
                    printLogMap[rid] = {
                        printed: pages > 0,
                        pages,
                        at: fmt
                    };
                    refreshPrintLogStrip(rid);
                    refreshBothPrintCells(rid, pages);
                    msg.textContent = pages > 0 ? `✓ Saved — ${pages} page${pages!==1?'s':''} printed` : '✓ Saved — no printing logged';
                    msg.style.color = '#3730a3';
                    btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i> Saved';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save';
                    }, 2000);
                } else {
                    throw new Error(data.error ?? 'Unknown error');
                }
            } catch (err) {
                msg.textContent = '✗ Failed: ' + err.message;
                msg.style.color = '#ef4444';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save';
            }
        }

        function refreshPrintLogStrip(rid) {
            const plog = printLogMap[rid];
            const logEl = document.getElementById('dPrintLog');
            if (!plog) {
                logEl.style.display = 'none';
                return;
            }
            logEl.style.display = 'flex';
            const logText = document.getElementById('dPrintText'),
                logBadge = document.getElementById('dPrintBadge');
            if (plog.printed) {
                logText.textContent = `Printed ${plog.pages} page${plog.pages!==1?'s':''}` + (plog.at ? ` · ${plog.at}` : '');
                logBadge.textContent = `${plog.pages}pg`;
                logBadge.className = 'text-[10px] font-black px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700';
            } else {
                logText.textContent = 'No printing during this session';
                logBadge.textContent = 'No print';
                logBadge.className = 'text-[10px] font-black px-2.5 py-1 rounded-full bg-slate-200 text-slate-500';
            }
        }

        function refreshBothPrintCells(rid, pages) {
            allTableRows.forEach(row => {
                if (row.dataset.id == rid) {
                    const cell = row.cells[7];
                    cell.innerHTML = pages > 0 ? `<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:.6rem"></i> ${pages}pg</span>` : `<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:.6rem"></i> No print</span>`;
                    row.dataset.plPrinted = pages > 0 ? 'Yes' : 'No';
                    row.dataset.plPages = pages > 0 ? pages : '';
                }
            });
            allCards.forEach(card => {
                if (card.dataset.id == rid) {
                    const wrapper = card.querySelector('.card-print-pill');
                    if (wrapper) wrapper.innerHTML = pages > 0 ? `<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:.6rem"></i> ${pages}pg</span>` : `<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:.6rem"></i> No print</span>`;
                    card.dataset.plPrinted = pages > 0 ? 'Yes' : 'No';
                    card.dataset.plPages = pages > 0 ? pages : '';
                }
            });
        }

        function exportCSV() {
            const visibleRows = allTableRows.filter(r => r.style.display !== 'none');
            const headers = ['ID', 'User Name', 'Email', 'Resource Name', 'PC Number', 'Date', 'Start Time', 'End Time', 'Purpose', 'Visitor Type', 'Status', 'Approved By', 'Approved At', 'Printed', 'Pages Printed', 'Submitted At'];
            const escape = v => {
                const s = String(v ?? '');
                return s.includes(',') || s.includes('"') || s.includes('\n') ? '"' + s.replace(/"/g, '""') + '"' : s;
            };
            const lines = [headers.map(escape).join(',')];
            visibleRows.forEach(row => {
                try {
                    const d = JSON.parse(row.getAttribute('onclick').replace(/^openDetail\(/, '').replace(/\)$/, ''));
                    lines.push([d.id ?? '', d.name ?? '', d.email ?? '', d.resource ?? '', d.pc ?? '', d.date ?? '', d.start ?? '', d.end ?? '', d.purpose ?? '', d.type ?? '', d.status ?? '', d.approverName ?? '', d.approvedAt ?? '', row.dataset.plPrinted ?? '', row.dataset.plPages ?? '', d.created ?? ''].map(escape).join(','));
                } catch (e) {}
            });
            const blob = new Blob([lines.join('\r\n')], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `sk-reservations-${new Date().toISOString().slice(0,10)}.csv`;
            a.click();
            URL.revokeObjectURL(url);
        }

        function setTab(btn, tab) {
            document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            curTab = tab;
            syncCards(tab);
            applyFilters();
        }

        function filterByStatus(tab) {
            curTab = tab;
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
            syncCards(tab);
            applyFilters();
        }

        function syncCards(tab) {
            document.querySelectorAll('[data-filter]').forEach(c => c.classList.toggle('ring', c.dataset.filter === tab));
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const date = document.getElementById('dateInput').value;
            const matchesFilters = el => {
                let matchTab;
                if (curTab === 'all') matchTab = true;
                else if (curTab === 'declined') matchTab = ['declined', 'canceled'].includes(el.dataset.status);
                else matchTab = el.dataset.status === curTab;
                return matchTab && (!q || el.dataset.search.includes(q)) && (!date || el.dataset.date === date);
            };
            let n = 0;
            allTableRows.forEach(row => {
                const show = matchesFilters(row);
                row.style.display = show ? '' : 'none';
                if (show) n++;
            });
            let cardVisible = 0;
            allCards.forEach(card => {
                const show = matchesFilters(card);
                card.style.display = show ? '' : 'none';
                if (show) cardVisible++;
            });
            if (allCards.length > 0) document.getElementById('mobileEmpty').style.display = cardVisible === 0 ? 'block' : 'none';
            const total = allTableRows.length;
            document.getElementById('resultCount').textContent = `Showing ${n} of ${total} reservation${total!==1?'s':''}`;
            document.getElementById('tableFooter').textContent = `${n} result${n!==1?'s':''} displayed`;
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('dateInput').value = '';
            curTab = 'all';
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === 'all'));
            syncCards('all');
            applyFilters();
        }
        let sortDir = {};

        function sortTable(col) {
            sortDir[col] = !sortDir[col];
            const tbody = document.getElementById('tableBody');
            Array.from(tbody.querySelectorAll('.res-row')).sort((a, b) => {
                const at = (a.cells[col]?.innerText ?? '').trim().toLowerCase();
                const bt = (b.cells[col]?.innerText ?? '').trim().toLowerCase();
                return sortDir[col] ? at.localeCompare(bt) : bt.localeCompare(at);
            }).forEach(r => tbody.appendChild(r));
            document.querySelectorAll('thead th').forEach((th, i) => {
                th.classList.toggle('sorted', i === col);
                const ic = th.querySelector('.sort-icon');
                if (ic) ic.className = `fa-solid ${i===col?(sortDir[col]?'fa-sort-up':'fa-sort-down'):'fa-sort'} sort-icon`;
            });
        }
        const STATUS_META = {
            pending: {
                icon: 'fa-clock',
                bg: '#fef3c7',
                color: '#92400e',
                label: 'Pending — Awaiting approval'
            },
            approved: {
                icon: 'fa-circle-check',
                bg: '#eef2ff',
                color: '#3730a3',
                label: 'Approved'
            },
            claimed: {
                icon: 'fa-check-double',
                bg: '#f3e8ff',
                color: '#6b21a8',
                label: 'Claimed — Ticket used'
            },
            declined: {
                icon: 'fa-xmark-circle',
                bg: '#fee2e2',
                color: '#991b1b',
                label: 'Declined'
            },
            canceled: {
                icon: 'fa-ban',
                bg: '#fee2e2',
                color: '#991b1b',
                label: 'Cancelled'
            },
            expired: {
                icon: 'fa-hourglass-end',
                bg: '#f1f5f9',
                color: '#475569',
                label: 'Expired — Was never approved'
            },
            unclaimed: {
                icon: 'fa-ticket',
                bg: '#fff7ed',
                color: '#c2410c',
                label: 'Unclaimed — Approved but did not show up'
            },
        };

        function openDetail(d) {
            _currentReservationId = d.id;
            const plog = printLogMap[d.id];
            document.getElementById('printPagesInput').value = plog ? (plog.printed ? plog.pages : 0) : 0;
            document.getElementById('printSaveMsg').textContent = '';
            const saveBtn = document.getElementById('savePrintBtn');
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save';
            const m = STATUS_META[d.status] || STATUS_META.pending;
            document.getElementById('dId').textContent = 'Reservation #' + d.id;
            document.getElementById('dName').textContent = d.name;
            document.getElementById('dEmail').textContent = d.email;
            document.getElementById('dResource').textContent = d.resource;
            document.getElementById('dPc').textContent = d.pc ? 'PC: ' + d.pc : '';
            document.getElementById('dDate').textContent = d.date;
            document.getElementById('dTime').textContent = d.start + ' – ' + d.end;
            document.getElementById('dPurpose').textContent = d.purpose;
            document.getElementById('dType').textContent = d.type;
            document.getElementById('dCreated').textContent = d.created;
            const approverRow = document.getElementById('dApprovedByRow');
            if (d.approverName && ['approved', 'claimed', 'declined', 'expired', 'unclaimed'].includes(d.status)) {
                approverRow.style.display = 'flex';
                const isDeclined = d.status === 'declined';
                document.getElementById('dApprovedByLabel').textContent = isDeclined ? 'Declined By' : 'Approved By';
                document.getElementById('dApprovedByIcon').className = `dicon ${isDeclined?'bg-red-50 text-red-500':''}`;
                document.getElementById('dApprovedByIcon').innerHTML = `<i class="fa-solid ${isDeclined?'fa-user-xmark':'fa-user-check'}"></i>`;
                document.getElementById('dApprovedByName').textContent = d.approverName;
                document.getElementById('dApprovedByEmail').textContent = d.approverEmail || '';
                document.getElementById('dApprovedAt').textContent = d.approvedAt ? `on ${d.approvedAt}` : '';
            } else {
                approverRow.style.display = 'none';
            }
            const bar = document.getElementById('dStatusBar');
            bar.style.background = m.bg;
            bar.style.color = m.color;
            bar.innerHTML = `<i class="fa-solid ${m.icon}"></i> <span>${m.label}</span>`;
            document.getElementById('dUnclaimedBanner').style.display = d.unclaimed ? 'flex' : 'none';
            const qrSec = document.getElementById('dQr'),
                clSec = document.getElementById('dClaimed');
            if (d.claimed || d.status === 'claimed') {
                qrSec.style.display = 'none';
                clSec.style.display = 'block';
            } else if (d.status === 'approved' || d.status === 'unclaimed') {
                clSec.style.display = 'none';
                qrSec.style.display = 'flex';
                QRCode.toCanvas(document.getElementById('qrCanvas'), d.code, {
                    width: 150,
                    margin: 1,
                    color: {
                        dark: '#1e293b',
                        light: '#ffffff'
                    }
                });
                document.getElementById('dTicketCode').textContent = d.code;
            } else {
                qrSec.style.display = 'none';
                clSec.style.display = 'none';
            }
            refreshPrintLogStrip(d.id);
            const acts = document.getElementById('dActions');
            if (d.status === 'pending') {
                acts.innerHTML = `<button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-approve flex-1"><i class="fa-solid fa-check"></i> Approve</button><button onclick="triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-decline flex-1"><i class="fa-solid fa-xmark"></i> Decline</button>`;
            } else {
                acts.innerHTML = `<button onclick="closeModal('detail')" class="btn-cancel w-full"><i class="fa-solid fa-xmark text-xs"></i> Close</button>`;
            }
            document.getElementById('detailModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function downloadTicket() {
            const canvas = document.getElementById('qrCanvas'),
                code = document.getElementById('dTicketCode').textContent;
            const link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        function triggerApprove(id, name) {
            approveTargetId = id;
            document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : '';
        }

        function triggerDecline(id, name) {
            declineTargetId = id;
            document.getElementById('declineConfirmName').textContent = name ? `"${name}"` : '';
        }
        document.getElementById('confirmApproveBtn').addEventListener('click', function() {
            if (!approveTargetId) return;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
            document.getElementById('approveId').value = approveTargetId;
            document.getElementById('approveForm').submit();
        });
        document.getElementById('confirmDeclineBtn').addEventListener('click', function() {
            if (!declineTargetId) return;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Declining…';
            document.getElementById('declineId').value = declineTargetId;
            document.getElementById('declineForm').submit();
        });
        const modalIds = {
            detail: 'detailModal',
            approve: 'approveModal',
            decline: 'declineModal'
        };

        function openModal(key) {
            const el = document.getElementById(modalIds[key]);
            if (el) {
                el.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(key) {
            const el = document.getElementById(modalIds[key]);
            if (el) {
                el.classList.remove('open');
                document.body.style.overflow = '';
            }
            if (key === 'detail') _currentReservationId = null;
            if (key === 'approve') {
                const b = document.getElementById('confirmApproveBtn');
                b.disabled = false;
                b.innerHTML = '<i class="fa-solid fa-check"></i> Approve';
            }
            if (key === 'decline') {
                const b = document.getElementById('confirmDeclineBtn');
                b.disabled = false;
                b.innerHTML = '<i class="fa-solid fa-xmark"></i> Decline';
            }
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('detail');
                closeModal('approve');
                closeModal('decline');
            }
        });

        // Dark mode
        function toggleDark() {
            const isDark = document.body.classList.toggle('dark');
            document.getElementById('darkIcon').innerHTML = isDark ? '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>' : '<i class="fa-regular fa-sun" style="font-size:.85rem"></i>';
            localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sk_theme') === 'dark') {
                document.body.classList.add('dark');
                document.getElementById('darkIcon').innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>';
            }
            document.documentElement.classList.remove('dark-pre');
        });

        // Responsive stat grid
        function fixStatGrid() {
            const g = document.getElementById('statGrid');
            if (!g) return;
            if (window.innerWidth < 640) g.style.gridTemplateColumns = 'repeat(3,minmax(0,1fr))';
            else if (window.innerWidth < 900) g.style.gridTemplateColumns = 'repeat(3,minmax(0,1fr))';
            else g.style.gridTemplateColumns = 'repeat(6,minmax(0,1fr))';
        }
        fixStatGrid();
        window.addEventListener('resize', fixStatGrid);

        applyFilters();

        // Auto-refresh
        const AUTO_REFRESH_INTERVAL = 30;
        let autoRefreshTimer = null,
            countdownTimer = null,
            secondsLeft = AUTO_REFRESH_INTERVAL,
            refreshPaused = false;
        const refreshIndicator = document.createElement('div');
        refreshIndicator.id = 'autoRefreshIndicator';
        refreshIndicator.innerHTML = `<span id="refreshDot" style="width:7px;height:7px;border-radius:50%;background:#6366f1;display:inline-block"></span><span id="refreshCountdown">Refresh in ${AUTO_REFRESH_INTERVAL}s</span>`;
        refreshIndicator.title = 'Click to refresh now';
        document.body.appendChild(refreshIndicator);
        refreshIndicator.addEventListener('click', () => doAutoRefresh(true));

        function updateCountdown() {
            const el = document.getElementById('refreshCountdown'),
                dot = document.getElementById('refreshDot');
            if (!el) return;
            if (refreshPaused) {
                el.textContent = 'Refresh paused';
                dot.style.background = '#f59e0b';
            } else {
                el.textContent = `Refresh in ${secondsLeft}s`;
                dot.style.background = '#6366f1';
            }
        }

        function startCountdown() {
            clearInterval(countdownTimer);
            secondsLeft = AUTO_REFRESH_INTERVAL;
            updateCountdown();
            countdownTimer = setInterval(() => {
                if (!refreshPaused) {
                    secondsLeft--;
                    if (secondsLeft <= 0) secondsLeft = AUTO_REFRESH_INTERVAL;
                }
                updateCountdown();
            }, 1000);
        }
        async function doAutoRefresh(force = false) {
            if (!force && document.querySelector('.overlay.open')) return;
            const search = document.getElementById('searchInput'),
                date = document.getElementById('dateInput');
            if (!force && (document.activeElement === search || document.activeElement === date)) return;
            try {
                const dot = document.getElementById('refreshDot'),
                    el = document.getElementById('refreshCountdown');
                if (dot) dot.style.background = '#818cf8';
                if (el) el.textContent = 'Refreshing…';
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('HTTP ' + response.status);
                const html = await response.text();
                const parser = new DOMParser(),
                    newDoc = parser.parseFromString(html, 'text/html');
                const newTbody = newDoc.querySelector('#tableBody'),
                    oldTbody = document.querySelector('#tableBody');
                if (newTbody && oldTbody) oldTbody.innerHTML = newTbody.innerHTML;
                const newCards = newDoc.querySelector('#mobileCardList'),
                    oldCards = document.querySelector('#mobileCardList');
                if (newCards && oldCards) oldCards.innerHTML = newCards.innerHTML;
                allTableRows.length = 0;
                document.querySelectorAll('#tableBody .res-row').forEach(r => allTableRows.push(r));
                allCards.length = 0;
                document.querySelectorAll('#mobileCardList .res-card').forEach(c => allCards.push(c));
                applyFilters();
                secondsLeft = AUTO_REFRESH_INTERVAL;
                updateCountdown();
                if (dot) dot.style.background = '#6366f1';
            } catch (err) {
                console.warn('Auto-refresh failed:', err.message);
                const dot = document.getElementById('refreshDot');
                if (dot) {
                    dot.style.background = '#ef4444';
                    setTimeout(() => {
                        dot.style.background = '#6366f1';
                    }, 3000);
                }
            }
        }
        const observer = new MutationObserver(() => {
            refreshPaused = !!document.querySelector('.overlay.open');
            updateCountdown();
        });
        document.querySelectorAll('.overlay').forEach(el => observer.observe(el, {
            attributes: true,
            attributeFilter: ['class']
        }));
        ['searchInput', 'dateInput'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('focus', () => {
                refreshPaused = true;
                updateCountdown();
            });
            el.addEventListener('blur', () => {
                refreshPaused = !!document.querySelector('.overlay.open');
                updateCountdown();
            });
        });
        autoRefreshTimer = setInterval(() => doAutoRefresh(), AUTO_REFRESH_INTERVAL * 1000);
        startCountdown();
    </script>
</body>

</html>