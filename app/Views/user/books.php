<?php /* Views/user/books.php */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>Library — Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
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
            font-size: 1rem;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden
        }

        /* ── Sidebar ── */
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
            margin-top: 3px;
            letter-spacing: .01em
        }

        .user-card {
            margin: 12px 12px 0;
            background: var(--indigo-light);
            border-radius: var(--r-md);
            padding: 12px 14px;
            border: 1px solid var(--indigo-border)
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
            letter-spacing: -.01em
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
            padding: 10px 10px;
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
            flex-shrink: 0
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, .15)
        }

        .nav-link:not(.active) .nav-icon {
            background: #f1f5f9
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: #e0e7ff
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

        .quota-wrap {
            margin: 8px 12px;
            background: #f8fafc;
            border-radius: var(--r-sm);
            padding: 12px 14px;
            border: 1px solid rgba(99, 102, 241, .09)
        }

        .quota-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px
        }

        .quota-lbl {
            font-size: .7rem;
            font-weight: 600;
            color: #64748b
        }

        .quota-val {
            font-size: .7rem;
            font-weight: 700;
            color: var(--indigo);
            font-family: var(--mono)
        }

        .quota-track {
            height: 5px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden
        }

        .quota-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--indigo), #818cf8);
            transition: width .6s cubic-bezier(.34, 1.56, .64, 1)
        }

        .quota-note {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: 5px
        }

        .quota-note.warn {
            color: #d97706;
            font-weight: 600
        }

        .quota-note.err {
            color: #dc2626;
            font-weight: 700
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

        /* ── Mobile Nav ── */
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

        .mob-nav-item:hover {
            background: var(--indigo-light);
            color: var(--indigo)
        }

        .mob-nav-item.active {
            background: var(--indigo-light);
            color: var(--indigo)
        }

        .mob-nav-item.active svg {
            stroke: var(--indigo)
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

        /* ── Main Area ── */
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

        /* ── Topbar ── */
        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
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
            font-size: 1.75rem;
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

        /* ── Cards ── */
        .card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm)
        }

        .card-p {
            padding: 20px 22px
        }

        /* ── Flash ── */
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
            font-size: .9rem;
            animation: slideUp .4s ease both
        }

        .flash-err {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 13px 18px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            font-weight: 600;
            border-radius: var(--r-md);
            font-size: .9rem;
            animation: slideUp .4s ease both
        }

        /* ── AI Card ── */
        .ai-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 20px 22px;
            margin-bottom: 16px
        }

        .ai-card-head {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px
        }

        .ai-icon {
            width: 30px;
            height: 30px;
            background: var(--indigo-light);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .ai-label {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #94a3b8
        }

        .ai-input-row {
            display: flex;
            gap: 8px
        }

        .ai-input {
            flex: 1;
            min-width: 0;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--r-sm);
            font-size: .88rem;
            font-family: var(--font);
            color: #0f172a;
            outline: none;
            transition: all var(--ease)
        }

        .ai-input:focus {
            border-color: #818cf8;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08)
        }

        .find-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 16px;
            background: var(--indigo);
            color: white;
            border-radius: var(--r-sm);
            font-size: .82rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            white-space: nowrap;
            flex-shrink: 0
        }

        .find-btn:hover {
            background: #312e81
        }

        .find-btn:disabled {
            opacity: .55;
            cursor: not-allowed
        }

        .ai-result-box {
            display: none;
            margin-top: .75rem;
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            border-radius: var(--r-sm);
            padding: 14px 16px
        }

        .ai-result-box.show {
            display: block;
            animation: slideUp .3s ease
        }

        .shimmer {
            height: 12px;
            border-radius: 4px;
            background: linear-gradient(90deg, #eef2ff 25%, #e0e7ff 50%, #eef2ff 75%);
            background-size: 200%;
            animation: shimmer 1.2s infinite
        }

        .shimmer+.shimmer {
            margin-top: 6px
        }

        @keyframes shimmer {
            0% {
                background-position: 200%
            }

            100% {
                background-position: -200%
            }
        }

        /* ── Controls Row ── */
        .controls-row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 16px
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 150px
        }

        .search-icon-pos {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8
        }

        .search-input {
            width: 100%;
            padding: 10px 12px 10px 34px;
            background: white;
            border: 1px solid rgba(99, 102, 241, .12);
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-family: var(--font);
            color: #0f172a;
            outline: none;
            box-shadow: var(--shadow-sm);
            transition: all var(--ease)
        }

        .search-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08)
        }

        .genre-select {
            padding: 10px 12px;
            background: white;
            border: 1px solid rgba(99, 102, 241, .12);
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-family: var(--font);
            color: #64748b;
            outline: none;
            box-shadow: var(--shadow-sm);
            cursor: pointer
        }

        .tab-group {
            display: flex;
            border-radius: var(--r-sm);
            border: 1px solid rgba(99, 102, 241, .12);
            overflow: hidden;
            background: white;
            box-shadow: var(--shadow-sm)
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: .82rem;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap
        }

        .tab-btn.active {
            background: var(--indigo);
            color: white;
            box-shadow: 0 2px 8px rgba(55, 48, 163, .2)
        }

        .tab-btn:hover:not(.active) {
            background: var(--indigo-light);
            color: var(--indigo)
        }

        /* ── Books Grid ── */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px
        }

        @media(max-width:639px) {
            .books-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px
            }
        }

        /* ── Book Card ── */
        .book-card {
            background: white;
            border-radius: var(--r-md);
            border: 1px solid rgba(99, 102, 241, .08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: all var(--ease)
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--indigo-border)
        }

        .book-card:hover .cover-overlay {
            opacity: 1
        }

        .book-card.rag-hl {
            border-color: var(--indigo);
            box-shadow: 0 0 0 3px rgba(55, 48, 163, .15)
        }

        .book-cover {
            height: 130px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0
        }

        .book-cover img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .cover-ph {
            font-size: 2.8rem;
            font-weight: 900;
            color: rgba(55, 48, 163, .18);
            position: relative;
            z-index: 1
        }

        .cover-overlay {
            position: absolute;
            inset: 0;
            background: rgba(55, 48, 163, .5);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .2s;
            z-index: 3
        }

        .cover-overlay-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: white;
            color: var(--indigo);
            font-weight: 700;
            font-size: .7rem;
            padding: .4rem .9rem;
            border-radius: 999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
            white-space: nowrap
        }

        .cover-genre-badge {
            position: absolute;
            top: 7px;
            left: 7px;
            z-index: 2;
            max-width: calc(100% - 68px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .62rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .92);
            color: var(--indigo);
            border: 1px solid var(--indigo-border)
        }

        .cover-avail-badge {
            position: absolute;
            top: 7px;
            right: 7px;
            z-index: 2;
            flex-shrink: 0;
            font-size: .6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 2px 8px;
            border-radius: 999px
        }

        .avail-yes {
            background: #dcfce7;
            color: #166534
        }

        .avail-no {
            background: #fee2e2;
            color: #991b1b
        }

        .book-body {
            padding: 10px 11px 11px;
            display: flex;
            flex-direction: column;
            flex: 1
        }

        .book-title-txt {
            font-weight: 700;
            font-size: .82rem;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden
        }

        .book-author-txt {
            font-size: .7rem;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 9px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .book-meta {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            margin-top: auto;
            margin-bottom: 4px
        }

        .meta-pill {
            padding: 2px 7px;
            background: #f8fafc;
            border-radius: 7px;
            font-size: .6rem;
            font-weight: 700;
            color: #64748b;
            border: 1px solid rgba(99, 102, 241, .07)
        }

        .meta-pill-mono {
            padding: 2px 7px;
            background: #f3f0ff;
            border-radius: 7px;
            font-size: .6rem;
            font-weight: 700;
            color: #5b21b6;
            font-family: var(--mono);
            border: 1px solid #ede9fe
        }

        .tap-hint {
            font-size: .6rem;
            color: #cbd5e1;
            font-weight: 600;
            text-align: center
        }

        /* ── My Borrowings ── */
        .borrow-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem
        }

        .borrow-table thead {
            background: #f8fafc;
            border-bottom: 2px solid rgba(99, 102, 241, .1)
        }

        .borrow-table thead th {
            padding: .65rem 1rem;
            text-align: left;
            font-weight: 700;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8
        }

        .borrow-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s
        }

        .borrow-table tbody tr:last-child {
            border-bottom: none
        }

        .borrow-table tbody tr:hover {
            background: #f8fafc
        }

        .borrow-table td {
            padding: .7rem 1rem;
            vertical-align: middle
        }

        .borrow-card {
            background: white;
            border: 1px solid rgba(99, 102, 241, .08);
            border-radius: var(--r-md);
            padding: .85rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0;
            box-shadow: var(--shadow-sm)
        }

        .borrow-card-top {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding-bottom: .6rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: .6rem
        }

        .borrow-card-dates {
            display: flex;
            gap: .75rem;
            font-size: .68rem;
            font-weight: 600;
            color: #94a3b8
        }

        /* ── Tags ── */
        .tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: .6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em
        }

        .tag-pending {
            background: #fef3c7;
            color: #92400e
        }

        .tag-approved {
            background: #dcfce7;
            color: #166534
        }

        .tag-returned {
            background: #ede9fe;
            color: #5b21b6
        }

        .tag-rejected {
            background: #fee2e2;
            color: #991b1b
        }

        .tag-available {
            background: #dcfce7;
            color: #166534
        }

        .tag-out {
            background: #fee2e2;
            color: #991b1b
        }

        /* ── Detail Modal ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(6px);
            z-index: 300;
            padding: 1.25rem;
            overflow-y: auto;
            align-items: flex-start;
            justify-content: center
        }

        .modal-backdrop.show {
            display: flex;
            animation: fadeIn .15s ease
        }

        .detail-card {
            background: white;
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            margin: auto;
            animation: slideUp .22s cubic-bezier(.34, 1.56, .64, 1) both;
            box-shadow: var(--shadow-lg);
            max-height: 92vh;
            overflow-y: auto
        }

        .modal-card {
            background: white;
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 460px;
            padding: 24px;
            margin: auto;
            animation: slideUp .22s cubic-bezier(.34, 1.56, .64, 1) both;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg)
        }

        .sheet-handle {
            display: none;
            width: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 999px;
            margin: 10px auto 0;
            flex-shrink: 0
        }

        @media(max-width:639px) {
            .modal-backdrop {
                padding: 0;
                align-items: flex-end !important
            }

            .modal-backdrop .modal-card,
            .modal-backdrop .detail-card {
                max-width: 100%;
                width: 100%;
                margin: 0;
                border-radius: var(--r-xl) var(--r-xl) 0 0;
                max-height: 92vh;
                animation: sheetUp .28s cubic-bezier(.32, .72, 0, 1) both
            }

            .sheet-handle {
                display: block
            }

            @keyframes sheetUp {
                from {
                    opacity: 0;
                    transform: translateY(100%)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }
        }

        .detail-cover {
            height: 190px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0
        }

        .detail-cover img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .detail-cover-ph {
            font-size: 5rem;
            font-weight: 900;
            color: rgba(55, 48, 163, .18)
        }

        .detail-body {
            padding: 1.5rem 1.5rem 2rem
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: .55rem 0;
            border-bottom: 1px solid #f1f5f9
        }

        .info-row:last-of-type {
            border-bottom: none
        }

        .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: var(--indigo-light);
            color: var(--indigo);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            flex-shrink: 0;
            margin-top: 1px
        }

        .info-label {
            font-size: .58rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            margin-bottom: 2px
        }

        .info-value {
            font-size: .85rem;
            font-weight: 700;
            color: #0f172a
        }

        .call-number-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f3f0ff;
            color: #5b21b6;
            font-size: .72rem;
            font-weight: 700;
            font-family: var(--mono);
            padding: .25rem .65rem;
            border-radius: 8px
        }

        /* ── Empty State ── */
        .empty-state {
            padding: 48px 20px;
            text-align: center
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            background: #f8fafc;
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            border: 1px solid rgba(99, 102, 241, .08)
        }

        /* ── Animations ── */
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
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .fade-up {
            animation: slideUp .4s ease both
        }

        .fade-up-1 {
            animation: slideUp .45s .05s ease both
        }

        /* ── Dark Mode ── */
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

        /* FIX #2: Was .body.dark — corrected to body.dark */
        body.dark .sidebar-top,
        body.dark .sidebar-footer {
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .brand-name {
            color: #e2eaf8
        }

        body.dark .nav-link {
            color: #7fb3e8
        }

        body.dark .nav-link:hover {
            background: rgba(99, 102, 241, .12);
            color: #a5b4fc
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

        body.dark .quota-wrap {
            background: rgba(99, 102, 241, .07);
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .quota-track {
            background: rgba(99, 102, 241, .15)
        }

        /* FIX #2: Was .body.dark — corrected to body.dark */
        body.dark .card,
        body.dark .ai-card,
        body.dark .book-card,
        body.dark .borrow-card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .greeting-name {
            color: #e2eaf8
        }

        body.dark .icon-btn {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .15);
            color: #7fb3e8
        }

        /* FIX #2: Was .body.dark — corrected to body.dark */
        body.dark .search-input,
        body.dark .genre-select,
        body.dark .ai-input {
            background: #101e35;
            border-color: rgba(99, 102, 241, .18);
            color: #e2eaf8
        }

        body.dark .tab-group {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .15)
        }

        body.dark .tab-btn {
            color: #7fb3e8
        }

        body.dark .tab-btn:hover:not(.active) {
            background: rgba(99, 102, 241, .1);
            color: #a5b4fc
        }

        body.dark .book-card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .book-title-txt {
            color: #e2eaf8
        }

        body.dark .meta-pill {
            background: #101e35;
            color: #7fb3e8;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .meta-pill-mono {
            background: rgba(91, 33, 182, .2);
            color: #c4b5fd;
            border-color: rgba(91, 33, 182, .3)
        }

        /* FIX #2: Was .body.dark — corrected to body.dark */
        body.dark .detail-card,
        body.dark .modal-card {
            background: #0b1628
        }

        body.dark .info-value {
            color: #e2eaf8
        }

        body.dark .info-row {
            border-color: #101e35
        }

        body.dark .borrow-table thead {
            background: #101e35
        }

        body.dark .borrow-table tbody tr {
            border-color: #101e35
        }

        body.dark .borrow-table tbody tr:hover {
            background: #101e35
        }

        body.dark .borrow-table td {
            color: #e2eaf8
        }

        body.dark .mobile-nav-pill {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .18)
        }

        body.dark .mob-nav-item {
            color: #7fb3e8
        }

        body.dark .mob-nav-item.active {
            background: rgba(99, 102, 241, .18)
        }

        body.dark .ai-result-box {
            background: rgba(55, 48, 163, .15);
            border-color: rgba(99, 102, 241, .25)
        }

        @media(max-width:639px) {
            .main-area {
                padding: 14px 14px 0
            }

            .greeting-name {
                font-size: 1.35rem
            }
        }
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'house',     'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'plus',      'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'calendar',  'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books',            'icon' => 'book-open', 'label' => 'Library',         'key' => 'books'],
        ['url' => '/profile',          'icon' => 'user',      'label' => 'Profile',         'key' => 'profile'],
    ];
    $page = 'books';
    $avatarLetter = strtoupper(mb_substr(trim($user_name ?? 'U'), 0, 1));
    $booksJson = json_encode(array_map(fn($b) => [
        'id'              => (int)($b['id'] ?? 0),
        'title'           => $b['title'] ?? '',
        'author'          => $b['author'] ?? 'Unknown',
        'genre'           => $b['genre'] ?? '',
        'preface'         => $b['preface'] ?? '',
        'published_year'  => $b['published_year'] ?? '',
        'cover_image'     => $b['cover_image'] ?? '',
        'isbn'            => $b['isbn'] ?? '',
        'call_number'     => $b['call_number'] ?? '',
        'available_copies' => (int)($b['available_copies'] ?? 0),
        'total_copies'    => (int)($b['total_copies'] ?? 1),
    ], $books ?? []));

    function svgIcon(string $name, int $size = 16, string $stroke = 'currentColor'): string
    {
        $icons = [
            'house'      => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
            'plus'       => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
            'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'book-open'  => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
            'user'       => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
            'logout'     => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
            'sun'        => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
            'moon'       => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
            'bell'       => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
            'bolt'       => '<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>',
            'search'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
            'grid'       => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'history'    => '<path d="M3 12a9 9 0 105.657-8.486"/><path d="M3 4v4h4"/><path d="M12 7v5l3 3"/>',
            'eye'        => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
            'barcode'    => '<path d="M3 5h2v14H3V5zm4 0h1v14H7V5zm3 0h2v14h-2V5zm4 0h1v14h-1V5zm3 0h2v14h-2V5z"/>',
            'copy'       => '<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>',
            'tag'        => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
            'location'   => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>',
            'calendar-days' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>',
            'check-circle' => '<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22 4 12 14.01 9 11.01"/>',
            'x-circle'   => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
            'xmark'      => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        ];
        $d  = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
        $sw = in_array($name, ['calendar', 'calendar-days', 'barcode', 'tag', 'grid']) ? '1.5' : '1.8';
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $stroke . '" stroke-width="' . $sw . '">' . $d . '</svg>';
    }
    ?>

    <!-- ═══════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ -->
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
                    <div class="user-name-txt" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($user_name ?? 'Resident') ?></div>
                    <div class="user-role-txt">Resident</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-lbl">Menu</div>
                <?php foreach ($navItems as $item):
                    $active = ($page === $item['key']);
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon">
                            <?= svgIcon($item['icon'], 16, $active ? 'white' : '#64748b') ?>
                        </div>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if (isset($remainingReservations)):
                $maxSlots  = 3;
                $remaining = $remainingReservations ?? 3;
                $usedSlots = $maxSlots - $remaining;
            ?>
                <div class="quota-wrap">
                    <div class="quota-row">
                        <span class="quota-lbl">Monthly Quota</span>
                        <span class="quota-val"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                    </div>
                    <div class="quota-track">
                        <div class="quota-fill" style="width:<?= ($usedSlots / $maxSlots) * 100 ?>%;<?= $remaining === 0 ? 'background:#ef4444' : ($remaining === 1 ? 'background:linear-gradient(90deg,#f59e0b,#fbbf24)' : '') ?>"></div>
                    </div>
                    <p class="quota-note <?= $remaining === 0 ? 'err' : ($remaining === 1 ? 'warn' : '') ?>">
                        <?php if ($remaining === 0): ?>⚠ No slots left this month
                        <?php elseif ($remaining === 1): ?>⚡ Only 1 slot remaining
                        <?php else: ?><?= $remaining ?> slots remaining this month<?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);">
                        <?= svgIcon('logout', 16, '#f87171') ?>
                    </div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════
     MOBILE NAV
════════════════════════════════════════════ -->
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active = ($page === $item['key']);
            ?>
                <a href="<?= base_url($item['url']) ?>"
                    class="mob-nav-item <?= $active ? 'active' : '' ?>"
                    title="<?= esc($item['label']) ?>">
                    <?= svgIcon($item['icon'], 22, $active ? 'var(--indigo)' : '#64748b') ?>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-logout" title="Sign Out">
                <?= svgIcon('logout', 22, '#f87171') ?>
            </a>
        </div>
    </nav>

    <!-- ═══════════════════════════════════════════
     BOOK DETAIL MODAL
════════════════════════════════════════════ -->
    <div class="modal-backdrop" id="bookDetailModal" onclick="onDetailBackdrop(event)">
        <div class="detail-card">
            <div class="sheet-handle"></div>
            <div class="detail-cover" id="detailCover">
                <span class="detail-cover-ph" id="detailCoverPh"></span>
            </div>
            <div class="detail-body">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px;">
                    <div style="flex:1;min-width:0;">
                        <p id="detailGenrePill" style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:var(--indigo);margin-bottom:3px;"></p>
                        <h3 id="detailTitle" style="font-size:1.15rem;font-weight:800;color:#0f172a;line-height:1.25;letter-spacing:-.02em;"></h3>
                        <p id="detailAuthor" style="font-size:.82rem;color:#94a3b8;font-weight:600;margin-top:2px;"></p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0;">
                        <button onclick="closeDetailModal()" style="width:36px;height:36px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background var(--ease);" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            <?= svgIcon('xmark', 13, '#64748b') ?>
                        </button>
                        <span id="detailAvailTag" class="tag"></span>
                    </div>
                </div>

                <div id="detailPrefaceBox" class="hidden" style="margin-bottom:16px;padding:14px;background:#f8fafc;border-radius:var(--r-md);border:1px solid rgba(99,102,241,.08);">
                    <p style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:5px;">About this book</p>
                    <p id="detailPreface" style="font-size:.82rem;color:#475569;line-height:1.65;font-style:italic;font-weight:500;"></p>
                </div>

                <div class="info-row">
                    <div class="info-icon"><?= svgIcon('copy', 13, 'var(--indigo)') ?></div>
                    <div>
                        <p class="info-label">Copies</p>
                        <p id="detailCopies" class="info-value"></p>
                    </div>
                </div>
                <div class="info-row" id="detailYearRow">
                    <div class="info-icon"><?= svgIcon('calendar-days', 13, 'var(--indigo)') ?></div>
                    <div>
                        <p class="info-label">Published</p>
                        <p id="detailYear" class="info-value"></p>
                    </div>
                </div>
                <div class="info-row" id="detailGenreRow">
                    <div class="info-icon"><?= svgIcon('tag', 13, 'var(--indigo)') ?></div>
                    <div>
                        <p class="info-label">Genre</p>
                        <p id="detailGenreVal" class="info-value"></p>
                    </div>
                </div>
                <div class="info-row" id="detailCallRow">
                    <div class="info-icon"><?= svgIcon('location', 13, 'var(--indigo)') ?></div>
                    <div>
                        <p class="info-label">Call Number <span style="text-transform:none;font-weight:500;color:#cbd5e1;">(shelf location)</span></p>
                        <p id="detailCallVal" class="info-value"></p>
                    </div>
                </div>
                <div class="info-row" id="detailIsbnRow">
                    <div class="info-icon"><?= svgIcon('barcode', 13, 'var(--indigo)') ?></div>
                    <div>
                        <p class="info-label">ISBN</p>
                        <p id="detailIsbnVal" class="info-value" style="font-family:var(--mono);"></p>
                    </div>
                </div>

                <div id="detailActions" style="display:flex;gap:10px;margin-top:20px;"></div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════
     BORROW CONFIRM MODAL
════════════════════════════════════════════ -->
    <div class="modal-backdrop" id="borrowModal" onclick="onBorrowBackdrop(event)">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Confirm Borrow</h3>
                    <p style="font-size:.72rem;color:#94a3b8;font-weight:500;margin-top:2px;">14-day loan period</p>
                </div>
                <button onclick="closeBorrowModal()" style="width:36px;height:36px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <?= svgIcon('xmark', 13, '#64748b') ?>
                </button>
            </div>

            <div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--indigo-light);border-radius:var(--r-md);border:1px solid var(--indigo-border);margin-bottom:20px;">
                <div style="width:38px;height:38px;background:white;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:var(--shadow-sm);">
                    <?= svgIcon('book-open', 16, 'var(--indigo)') ?>
                </div>
                <div>
                    <p style="font-weight:700;color:#0f172a;font-size:.88rem;" id="modalBookTitle">—</p>
                    <p style="font-size:.7rem;color:#64748b;margin-top:2px;">You'll be notified once your request is approved</p>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button onclick="closeBorrowModal()" style="flex:1;padding:11px;background:#f8fafc;border-radius:var(--r-sm);font-weight:600;color:#64748b;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.85rem;font-family:var(--font);transition:background var(--ease);" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Cancel</button>
                <form id="borrowForm" method="post" action="" style="flex:1;">
                    <?= csrf_field() ?>
                    <button type="submit" style="width:100%;padding:11px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;font-size:.85rem;border:none;cursor:pointer;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);transition:background var(--ease);" onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'">Yes, Borrow</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════
     MAIN
════════════════════════════════════════════ -->
    <main class="main-area">

        <!-- Topbar -->
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">Resident Portal</div>
                <div class="greeting-name">Community <span style="color:var(--indigo)">Library</span></div>
                <div class="greeting-sub">Browse, search, and borrow books available to all residents</div>
            </div>
            <div class="topbar-right">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                    <span id="dark-icon"><?= svgIcon('sun', 14, '#94a3b8') ?></span>
                </div>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok fade-up">
                <?= svgIcon('check-circle', 15, 'var(--indigo)') ?>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err fade-up">
                <?= svgIcon('x-circle', 15, '#dc2626') ?>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- AI Smart Suggestion -->
        <div class="ai-card fade-up-1">
            <div class="ai-card-head">
                <div class="ai-icon">
                    <?= svgIcon('bolt', 13, 'var(--indigo)') ?>
                </div>
                <div>
                    <div class="ai-label">AI Smart Suggestion</div>
                </div>
            </div>
            <div class="ai-input-row">
                <input id="ragQuery" type="text" class="ai-input"
                    placeholder="e.g. adventure for kids, Philippine history…"
                    onkeydown="if(event.key==='Enter')doRag()">
                <button id="ragBtn" onclick="doRag()" class="find-btn">
                    <?= svgIcon('search', 12, 'white') ?>
                    Find for Me
                </button>
            </div>
            <div id="ragSkel" style="display:none;margin-top:.65rem;">
                <div class="shimmer" style="width:88%"></div>
                <div class="shimmer" style="width:68%"></div>
                <div class="shimmer" style="width:50%"></div>
            </div>
            <div id="ragErr" style="display:none;margin-top:8px;padding:10px 14px;background:#fee2e2;border:1px solid #fecaca;border-radius:var(--r-sm);font-size:.8rem;color:#991b1b;font-weight:500;"></div>
            <div class="ai-result-box" id="ragRes">
                <p style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:var(--indigo);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                    <?= svgIcon('bolt', 10, 'var(--indigo)') ?> Librarian Suggestion
                </p>
                <p id="ragText" style="font-size:.82rem;color:#312e81;line-height:1.65;font-style:italic;font-weight:500;"></p>
                <p style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#94a3b8;margin-top:10px;margin-bottom:6px;">Matching books</p>
                <div id="ragChips" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls-row fade-up-1">
            <div class="search-wrap">
                <span class="search-icon-pos"><?= svgIcon('search', 13, '#94a3b8') ?></span>
                <input id="searchInput" type="text" class="search-input"
                    placeholder="Search title or author…"
                    oninput="filterBooks()">
            </div>
            <select id="genreFilter" class="genre-select" onchange="filterBooks()">
                <option value="">All Genres</option>
                <?php foreach ($genres as $g): ?>
                    <option value="<?= esc($g) ?>"><?= esc($g) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="tab-group">
                <button id="tabBrowse" onclick="switchTab('browse')" class="tab-btn">
                    <?= svgIcon('grid', 12, 'currentColor') ?> Browse
                </button>
                <button id="tabMine" onclick="switchTab('mine')" class="tab-btn">
                    <?= svgIcon('history', 12, 'currentColor') ?> My Borrowings
                </button>
            </div>
        </div>

        <!-- ── Browse Tab ── -->
        <div id="paneBrowse">
            <?php if (empty($books)): ?>
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-icon"><?= svgIcon('book-open', 24, '#cbd5e1') ?></div>
                        <h3 style="font-size:.95rem;font-weight:700;color:#64748b;margin-bottom:4px;">No books yet</h3>
                        <p style="font-size:.78rem;color:#94a3b8;">The library is being stocked — check back soon!</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="books-grid" id="booksGrid">
                    <?php foreach ($books as $book):
                        $available = (int)($book['available_copies'] ?? 0) > 0;
                    ?>
                        <div class="book-card"
                            id="book-<?= (int)$book['id'] ?>"
                            data-title="<?= strtolower(htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8')) ?>"
                            data-author="<?= strtolower(htmlspecialchars($book['author'] ?? '', ENT_QUOTES, 'UTF-8')) ?>"
                            data-genre="<?= htmlspecialchars($book['genre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            onclick="openBookDetail(<?= (int)$book['id'] ?>)">

                            <div class="book-cover">
                                <?php if (!empty($book['cover_image'])): ?>
                                    <img src="<?= esc($book['cover_image']) ?>" alt="<?= esc($book['title']) ?>">
                                <?php else: ?>
                                    <span class="cover-ph"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></span>
                                <?php endif; ?>
                                <div class="cover-overlay">
                                    <span class="cover-overlay-btn">
                                        <?= svgIcon('eye', 11, 'var(--indigo)') ?> View Details
                                    </span>
                                </div>
                                <?php if (!empty($book['genre'])): ?>
                                    <span class="cover-genre-badge"><?= esc($book['genre']) ?></span>
                                <?php endif; ?>
                                <span class="cover-avail-badge <?= $available ? 'avail-yes' : 'avail-no' ?>">
                                    <?= $available ? 'Available' : 'Out' ?>
                                </span>
                            </div>

                            <div class="book-body">
                                <p class="book-title-txt"><?= esc($book['title']) ?></p>
                                <p class="book-author-txt">by <?= esc($book['author'] ?? 'Unknown') ?></p>
                                <div class="book-meta">
                                    <span class="meta-pill"><?= (int)($book['available_copies'] ?? 0) ?>/<?= (int)($book['total_copies'] ?? 1) ?> copies</span>
                                    <?php if (!empty($book['published_year'])): ?>
                                        <span class="meta-pill"><?= esc($book['published_year']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($book['call_number'])): ?>
                                        <span class="meta-pill-mono"><?= esc($book['call_number']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="tap-hint">Tap to view &amp; borrow</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ── My Borrowings Tab ── -->
        <div id="paneMine" style="display:none;">
            <?php if (empty($myBorrowings)): ?>
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-icon"><?= svgIcon('history', 24, '#cbd5e1') ?></div>
                        <h3 style="font-size:.95rem;font-weight:700;color:#64748b;margin-bottom:4px;">No borrowing history</h3>
                        <p style="font-size:.78rem;color:#94a3b8;">Books you borrow will appear here.</p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Desktop table -->
                <div class="card" style="display:none;" id="borrowTableWrap">
                    <div style="padding:16px 18px;border-bottom:1px solid rgba(99,102,241,.07);display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;background:var(--indigo-light);border-radius:9px;display:flex;align-items:center;justify-content:center;">
                            <?= svgIcon('history', 14, 'var(--indigo)') ?>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:#0f172a;">My Borrowing History</div>
                            <div style="font-size:.68rem;color:#94a3b8;"><?= count($myBorrowings) ?> record<?= count($myBorrowings) !== 1 ? 's' : '' ?></div>
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="borrow-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book</th>
                                    <th>Borrowed On</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($myBorrowings as $i => $b):
                                    $s = strtolower($b['status'] ?? 'pending');
                                ?>
                                    <tr>
                                        <td style="color:#94a3b8;font-weight:700;font-size:.78rem;"><?= $i + 1 ?></td>
                                        <td>
                                            <p style="font-weight:600;font-size:.85rem;color:#0f172a;"><?= esc($b['title']) ?></p>
                                            <p style="font-size:.72rem;color:#94a3b8;margin-top:1px;"><?= esc($b['author'] ?? '') ?></p>
                                        </td>
                                        <td style="font-size:.82rem;color:#475569;font-weight:500;"><?= esc($b['borrowed_at'] ?? '—') ?></td>
                                        <td style="font-size:.82rem;color:#475569;font-weight:500;"><?= esc($b['due_date'] ?? '—') ?></td>
                                        <td><span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile cards -->
                <div id="borrowCardsWrap" style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;align-items:center;gap:9px;margin-bottom:2px;">
                        <div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <?= svgIcon('history', 13, 'var(--indigo)') ?>
                        </div>
                        <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#94a3b8;">Borrowing History</p>
                    </div>
                    <?php foreach ($myBorrowings as $b):
                        $s = strtolower($b['status'] ?? 'pending');
                    ?>
                        <div class="borrow-card">
                            <div class="borrow-card-top">
                                <div style="width:36px;height:36px;background:var(--indigo-light);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <?= svgIcon('book-open', 15, 'var(--indigo)') ?>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;line-height:1.3;"><?= esc($b['title']) ?></p>
                                        <span class="tag tag-<?= $s ?>" style="flex-shrink:0;margin-top:1px;"><?= ucfirst($s) ?></span>
                                    </div>
                                    <p style="font-size:.7rem;color:#94a3b8;font-weight:500;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($b['author'] ?? '') ?></p>
                                </div>
                            </div>
                            <div class="borrow-card-dates">
                                <span style="display:flex;align-items:center;gap:4px;">
                                    <?= svgIcon('calendar-days', 10, '#cbd5e1') ?>
                                    Borrowed: <?= esc($b['borrowed_at'] ?? '—') ?>
                                </span>
                                <span style="display:flex;align-items:center;gap:4px;<?= $s === 'approved' ? 'color:#e11d48;font-weight:700;' : '' ?>">
                                    <?= svgIcon('calendar-days', 10, '#cbd5e1') ?>
                                    Due: <?= esc($b['due_date'] ?? '—') ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </main><!-- /main -->

    <!-- FIX #3: Expose base_url to JS so borrow form action is not hardcoded -->
    <script>
        const BASE_URL = "<?= base_url() ?>";
    </script>

    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark');
        })();

        const BOOKS = <?= $booksJson ?? '[]' ?>;
        const bookMap = {};
        BOOKS.forEach(b => bookMap[b.id] = b);

        /* ── Dark mode ── */
        function toggleDark() {
            const isDark = document.body.classList.toggle('dark');
            updateDarkIcon(isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        function updateDarkIcon(isDark) {
            const el = document.getElementById('dark-icon');
            if (!el) return;
            el.innerHTML = isDark ?
                `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>` :
                `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
        }
        document.addEventListener('DOMContentLoaded', () => {
            updateDarkIcon(document.body.classList.contains('dark'));
            responsiveBorrowView();
        });

        /* ── Responsive borrow view ── */
        function responsiveBorrowView() {
            const table = document.getElementById('borrowTableWrap');
            const cards = document.getElementById('borrowCardsWrap');
            if (!table || !cards) return;
            const isWide = window.innerWidth >= 768;
            table.style.display = isWide ? 'block' : 'none';
            cards.style.display = isWide ? 'none' : 'flex';
        }
        window.addEventListener('resize', responsiveBorrowView);

        /* ── Tab switching ── */
        function switchTab(t) {
            document.getElementById('paneBrowse').style.display = t === 'browse' ? '' : 'none';
            document.getElementById('paneMine').style.display = t === 'mine' ? '' : 'none';
            const ACT = 'tab-btn active',
                INQ = 'tab-btn';
            document.getElementById('tabBrowse').className = t === 'browse' ? ACT : INQ;
            document.getElementById('tabMine').className = t === 'mine' ? ACT : INQ;
        }
        switchTab('browse');

        /* ── Filter books ── */
        function filterBooks() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const g = document.getElementById('genreFilter').value;
            let visible = 0;
            document.querySelectorAll('.book-card').forEach(c => {
                const mQ = c.dataset.title.includes(q) || c.dataset.author.includes(q);
                const mG = !g || c.dataset.genre === g;
                c.style.display = mQ && mG ? '' : 'none';
                if (mQ && mG) visible++;
            });
        }

        /* ── Book detail modal ── */
        function openBookDetail(id) {
            const b = bookMap[id];
            if (!b) return;
            const avail = b.available_copies > 0;

            const coverEl = document.getElementById('detailCover');
            const phEl = document.getElementById('detailCoverPh');
            const oldImg = coverEl.querySelector('img');
            if (oldImg) oldImg.remove();
            if (b.cover_image) {
                phEl.style.display = 'none';
                const img = document.createElement('img');
                img.src = b.cover_image;
                img.alt = b.title;
                coverEl.appendChild(img);
            } else {
                phEl.style.display = '';
                phEl.textContent = b.title.charAt(0).toUpperCase();
            }

            document.getElementById('detailGenrePill').textContent = b.genre || '';
            document.getElementById('detailTitle').textContent = b.title;
            document.getElementById('detailAuthor').textContent = 'by ' + b.author;
            document.getElementById('detailCopies').textContent = b.available_copies + ' available of ' + b.total_copies + ' total';

            const tag = document.getElementById('detailAvailTag');
            tag.textContent = avail ? 'Available' : 'Not Available';
            tag.className = 'tag ' + (avail ? 'tag-available' : 'tag-out');

            const prefBox = document.getElementById('detailPrefaceBox');
            if (b.preface) {
                document.getElementById('detailPreface').textContent = b.preface;
                prefBox.classList.remove('hidden');
            } else prefBox.classList.add('hidden');

            const yr = document.getElementById('detailYearRow');
            if (b.published_year) {
                document.getElementById('detailYear').textContent = b.published_year;
                yr.style.display = '';
            } else yr.style.display = 'none';

            const gr = document.getElementById('detailGenreRow');
            if (b.genre) {
                document.getElementById('detailGenreVal').textContent = b.genre;
                gr.style.display = '';
            } else gr.style.display = 'none';

            const cr = document.getElementById('detailCallRow');
            if (b.call_number) {
                document.getElementById('detailCallVal').innerHTML = '<span class="call-number-badge">' + b.call_number + '</span>';
                cr.style.display = '';
            } else cr.style.display = 'none';

            const ir = document.getElementById('detailIsbnRow');
            if (b.isbn) {
                document.getElementById('detailIsbnVal').textContent = b.isbn;
                ir.style.display = '';
            } else ir.style.display = 'none';

            const acts = document.getElementById('detailActions');
            if (avail) {
                // FIX #1: Use data attributes instead of JSON.stringify in onclick
                // to avoid quote-escaping issues with titles containing " characters
                acts.innerHTML = `
                    <button
                        data-id="${b.id}"
                        data-title="${b.title.replace(/"/g, '&quot;')}"
                        onclick="closeDetailModal(); openBorrowModal(+this.dataset.id, this.dataset.title)"
                        style="flex:1;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;font-size:.85rem;border:none;cursor:pointer;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);display:flex;align-items:center;justify-content:center;gap:7px;transition:background var(--ease);"
                        onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Borrow This Book
                    </button>
                    <button onclick="closeDetailModal()"
                        style="padding:12px 18px;background:#f8fafc;border-radius:var(--r-sm);font-weight:600;font-size:.85rem;border:1px solid rgba(99,102,241,.1);cursor:pointer;color:#64748b;font-family:var(--font);">
                        Close
                    </button>`;
            } else {
                acts.innerHTML = `
                    <button disabled
                        style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:600;font-size:.85rem;border:1px solid rgba(99,102,241,.08);cursor:not-allowed;color:#94a3b8;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;">
                        Currently Unavailable
                    </button>
                    <button onclick="closeDetailModal()"
                        style="padding:12px 18px;background:#f8fafc;border-radius:var(--r-sm);font-weight:600;font-size:.85rem;border:1px solid rgba(99,102,241,.1);cursor:pointer;color:#64748b;font-family:var(--font);">
                        Close
                    </button>`;
            }

            document.getElementById('bookDetailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('bookDetailModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function onDetailBackdrop(e) {
            if (e.target === document.getElementById('bookDetailModal')) closeDetailModal();
        }

        /* ── Borrow modal ── */
        function openBorrowModal(id, title) {
            document.getElementById('modalBookTitle').textContent = title;
            // FIX #3: Use BASE_URL instead of hardcoded /books/borrow/
            document.getElementById('borrowForm').action = BASE_URL + 'books/borrow/' + id;
            document.getElementById('borrowModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeBorrowModal() {
            document.getElementById('borrowModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function onBorrowBackdrop(e) {
            if (e.target === document.getElementById('borrowModal')) closeBorrowModal();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeBorrowModal();
            }
        });

        /* ── RAG ── */
        async function doRag() {
            const query = document.getElementById('ragQuery').value.trim();
            if (query.length < 2) return;
            const skel = document.getElementById('ragSkel');
            const err = document.getElementById('ragErr');
            const res = document.getElementById('ragRes');
            const btn = document.getElementById('ragBtn');
            res.classList.remove('show');
            err.style.display = 'none';
            skel.style.display = 'block';
            btn.disabled = true;
            document.querySelectorAll('.book-card').forEach(c => c.classList.remove('rag-hl'));
            try {
                const r = await fetch('/rag/suggest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        query
                    })
                });
                const d = await r.json();
                skel.style.display = 'none';
                btn.disabled = false;
                if (!d.suggestion) {
                    err.textContent = d.error || d.message || 'No suggestion found.';
                    err.style.display = 'block';
                    return;
                }
                document.getElementById('ragText').textContent = d.suggestion;
                const chips = document.getElementById('ragChips');
                chips.innerHTML = '';
                (d.books || []).forEach(b => {
                    const avail = (b.available_copies || 0) > 0;
                    const chip = document.createElement('button');
                    chip.style.cssText = `display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:9px;font-size:.72rem;font-weight:600;border:1px solid;cursor:pointer;font-family:var(--font);transition:all .15s;${avail?'background:white;border-color:var(--indigo-border);color:var(--indigo);':'background:#f8fafc;border-color:#e2e8f0;color:#94a3b8;border-style:dashed;'}`;
                    chip.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/></svg>` +
                        b.title + (avail ? '' : ' <span style="opacity:.55">(out)</span>');
                    chip.onclick = () => {
                        openBookDetail(b.id);
                        const card = document.getElementById('book-' + b.id);
                        if (card) {
                            card.classList.add('rag-hl');
                            card.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    };
                    chips.appendChild(chip);
                    const card = document.getElementById('book-' + b.id);
                    if (card) card.classList.add('rag-hl');
                });
                res.classList.add('show');
            } catch (e) {
                skel.style.display = 'none';
                btn.disabled = false;
                err.textContent = 'Network error. Please try again.';
                err.style.display = 'block';
            }
        }
    </script>

</body>

</html>