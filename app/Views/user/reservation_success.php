<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>Reservation Submitted | SK Reserve</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
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
            --ease: .18s cubic-bezier(.4, 0, .2, 1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }

        html {
            height: 100%;
            font-size: 16px;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: #0f172a;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

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
            border: 1px solid rgba(99, 102, 241, .1);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .sidebar-top {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
        }

        .brand-tag {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.03em;
            line-height: 1.1;
        }

        .brand-name em {
            font-style: normal;
            color: var(--indigo);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .nav-lbl {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #cbd5e1;
            padding: 10px 10px 5px;
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
            background: #f1f5f9;
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, .15);
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: #e0e7ff;
        }

        .sidebar-footer {
            padding: 10px 10px 12px;
            border-top: 1px solid rgba(99, 102, 241, .07);
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
            transition: all var(--ease);
        }

        .logout-link:hover {
            background: #fef2f2;
            color: #dc2626;
        }

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
            color: #64748b;
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
            color: #94a3b8;
        }

        @media(max-width:1023px) {
            .sidebar {
                display: none !important;
            }

            .mobile-nav-pill {
                display: flex !important;
            }

            .main-area {
                padding-bottom: calc(var(--mob-nav-total)+16px) !important;
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

        .main-area {
            flex: 1;
            min-width: 0;
            padding: 28px 28px 40px;
            overflow-x: hidden;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        @media(max-width:639px) {
            .main-area {
                padding: 16px 14px 0;
            }
        }

        .success-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 28px;
            width: 100%;
            max-width: 560px;
            animation: slideUp .4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .pending-icon {
            width: 72px;
            height: 72px;
            background: #fef9c3;
            border: 2px solid #fde047;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            animation: pulse 2.5s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(234, 179, 8, .25)
            }

            50% {
                box-shadow: 0 0 0 10px rgba(234, 179, 8, 0)
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
            gap: 1rem;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: .6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .detail-value {
            font-weight: 600;
            color: #0f172a;
            font-size: .84rem;
            text-align: right;
        }

        .btn-primary {
            background: var(--indigo);
            color: white;
            border: none;
            padding: .8rem 1.5rem;
            border-radius: var(--r-md);
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            transition: all var(--ease);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(55, 48, 163, .28);
        }

        .btn-primary:hover {
            background: #312e81;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--card);
            color: #64748b;
            border: 1px solid rgba(99, 102, 241, .12);
            padding: .8rem 1.5rem;
            border-radius: var(--r-md);
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            transition: all var(--ease);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            border-color: var(--indigo-border);
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
        }

        .step-item:last-child {
            border-bottom: none;
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .68rem;
            font-weight: 800;
        }
        /* ═══════════════════════════════════════════════════
   COMPREHENSIVE DARK MODE FIX
   Add this block to the bottom of <style> in every view.
   It targets hardcoded inline color values that class
   selectors alone cannot override.
═══════════════════════════════════════════════════ */

/* ── General text that uses hardcoded #0f172a / #475569 / #64748b ── */
body.dark h1,
body.dark h2,
body.dark h3,
body.dark h4,
body.dark h5 {
    color: #e2eaf8 !important;
}

body.dark p,
body.dark span,
body.dark div {
    color: inherit;
}

/* ── Force readable color on common inline-styled text ── */
body.dark [style*="color:#0f172a"],
body.dark [style*="color: #0f172a"] {
    color: #e2eaf8 !important;
}

body.dark [style*="color:#475569"],
body.dark [style*="color: #475569"] {
    color: #94b4d4 !important;
}

body.dark [style*="color:#64748b"],
body.dark [style*="color: #64748b"] {
    color: #7fb3e8 !important;
}

body.dark [style*="color:#94a3b8"],
body.dark [style*="color: #94a3b8"] {
    color: #4a6fa5 !important;
}

body.dark [style*="color:#cbd5e1"],
body.dark [style*="color: #cbd5e1"] {
    color: #2d4a6a !important;
}

body.dark [style*="color:#334155"],
body.dark [style*="color: #334155"] {
    color: #94b4d4 !important;
}

/* ── Backgrounds: white / light gray surfaces ── */
body.dark [style*="background:#ffffff"],
body.dark [style*="background: #ffffff"],
body.dark [style*="background:white"],
body.dark [style*="background: white"] {
    background: #0b1628 !important;
}

body.dark [style*="background:#f8fafc"],
body.dark [style*="background: #f8fafc"] {
    background: #101e35 !important;
}

body.dark [style*="background:#f1f5f9"],
body.dark [style*="background: #f1f5f9"] {
    background: #101e35 !important;
}

body.dark [style*="background:#f0f2f9"],
body.dark [style*="background: #f0f2f9"] {
    background: #060e1e !important;
}

/* ── Borders that use light colors ── */
body.dark [style*="border-color:#e2e8f0"],
body.dark [style*="border-color: #e2e8f0"] {
    border-color: rgba(99, 102, 241, .12) !important;
}

body.dark [style*="border:1px solid #e2e8f0"],
body.dark [style*="border: 1px solid #e2e8f0"] {
    border-color: rgba(99, 102, 241, .12) !important;
}

/* ── Info rows (profile page) ── */
body.dark .info-row {
    border-color: rgba(99, 102, 241, .08) !important;
}

body.dark .info-value {
    color: #e2eaf8 !important;
}

body.dark .info-label {
    color: #4a6fa5 !important;
}

body.dark .info-icon {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

/* ── Profile page specific ── */
body.dark .profile-avatar {
    box-shadow: 0 8px 24px rgba(55, 48, 163, .5) !important;
}

body.dark .profile-status-dot {
    border-color: #0b1628 !important;
}

/* ── Stat mini cards (profile) ── */
body.dark .stat-mini {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .stat-mini-val {
    color: #e2eaf8 !important;
}

body.dark .stat-mini-lbl,
body.dark .stat-mini-sub {
    color: #4a6fa5 !important;
}

/* ── Quick links (profile) ── */
body.dark .quick-link {
    background: #0b1628 !important;
    border-color: rgba(99, 102, 241, .1) !important;
    color: #a5b4fc !important;
}

body.dark .quick-link:hover {
    background: rgba(99, 102, 241, .12) !important;
    color: #c7d2fe !important;
}

/* ── Card titles / subtitles ── */
body.dark .card-title {
    color: #e2eaf8 !important;
}

body.dark .card-sub {
    color: #4a6fa5 !important;
}

body.dark .section-lbl {
    color: #4a6fa5 !important;
}

/* ── Danger zone (profile) ── */
body.dark .danger-row {
    border-color: rgba(99, 102, 241, .08) !important;
}

body.dark .danger-row p[style*="color:#0f172a"],
body.dark .danger-row p[style*="color: #0f172a"] {
    color: #e2eaf8 !important;
}

/* ── Reservation success / submitted page ── */
body.dark .success-card {
    background: #0b1628 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .detail-row {
    border-color: rgba(99, 102, 241, .08) !important;
}

body.dark .detail-value {
    color: #e2eaf8 !important;
}

body.dark .detail-label {
    color: #4a6fa5 !important;
}

body.dark .step-item {
    border-color: rgba(99, 102, 241, .08) !important;
}

/* ── Step numbers (reservation submitted) ── */
body.dark .step-num[style*="background:#f1f5f9"] {
    background: #101e35 !important;
    color: #4a6fa5 !important;
}

body.dark .step-num[style*="background:#fef3c7"] {
    background: rgba(251, 191, 36, .15) !important;
}

/* ── Buttons: secondary / cancel ── */
body.dark .btn-secondary {
    background: #0b1628 !important;
    border-color: rgba(99, 102, 241, .18) !important;
    color: #a5b4fc !important;
}

body.dark .btn-secondary:hover {
    background: rgba(99, 102, 241, .12) !important;
    color: #c7d2fe !important;
}

/* ── Reservation list: table ── */
body.dark table thead th {
    background: #101e35 !important;
    color: #4a6fa5 !important;
    border-color: rgba(99, 102, 241, .08) !important;
}

body.dark table tbody td {
    color: #e2eaf8 !important;
    border-color: rgba(99, 102, 241, .05) !important;
}

body.dark table tbody tr:hover td {
    background: #101e35 !important;
}

/* ── Reservation mobile cards ── */
body.dark .res-card {
    background: #0b1628 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .res-card:hover {
    border-color: rgba(99, 102, 241, .3) !important;
}

/* ── Booking row (dashboard) ── */
body.dark .bk-row:hover {
    background: rgba(99, 102, 241, .08) !important;
}

body.dark .bk-date {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .bk-day {
    color: #e2eaf8 !important;
}

body.dark .bk-name {
    color: #e2eaf8 !important;
}

/* ── Modal cards (general) ── */
body.dark .modal-card,
body.dark .detail-card {
    background: #0b1628 !important;
    color: #e2eaf8 !important;
}

body.dark .modal-card h3,
body.dark .modal-card h4,
body.dark .detail-card h3 {
    color: #e2eaf8 !important;
}

body.dark .modal-card p,
body.dark .detail-card p {
    color: #7fb3e8 !important;
}

body.dark .sheet-handle {
    background: #1e3a5f !important;
}

/* ── Modal action buttons ── */
body.dark .modal-card button[style*="background:#f8fafc"],
body.dark .modal-card button[style*="background: #f8fafc"],
body.dark .modal-card button[style*="background:#f1f5f9"],
body.dark .modal-card button[style*="background: #f1f5f9"] {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .2) !important;
    color: #a5b4fc !important;
}

/* ── Books page: detail modal cover placeholder ── */
body.dark .detail-cover {
    background: rgba(55, 48, 163, .2) !important;
}

body.dark .detail-cover-ph {
    color: rgba(165, 180, 252, .25) !important;
}

/* ── Books info rows ── */
body.dark .info-row {
    border-color: #101e35 !important;
}

/* ── Book cards ── */
body.dark .book-card {
    background: #0b1628 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .book-title-txt {
    color: #e2eaf8 !important;
}

body.dark .book-cover {
    background: rgba(55, 48, 163, .2) !important;
}

body.dark .cover-ph {
    color: rgba(165, 180, 252, .18) !important;
}

/* ── Borrow table (books) ── */
body.dark .borrow-table thead {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .borrow-table thead th {
    color: #4a6fa5 !important;
}

body.dark .borrow-table tbody tr {
    border-color: #101e35 !important;
}

body.dark .borrow-table tbody tr:hover {
    background: #101e35 !important;
}

body.dark .borrow-table td {
    color: #e2eaf8 !important;
}

/* ── Borrow card (mobile books) ── */
body.dark .borrow-card {
    background: #0b1628 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .borrow-card-top {
    border-color: #101e35 !important;
}

/* ── AI result box ── */
body.dark .ai-result-box {
    background: rgba(55, 48, 163, .15) !important;
    border-color: rgba(99, 102, 241, .25) !important;
}

body.dark #ragText,
body.dark #ragText * {
    color: #a5b4fc !important;
}

/* ── Search / filter inputs ── */
body.dark .search-input,
body.dark .filter-select,
body.dark .genre-select,
body.dark .ai-input,
body.dark .field-input {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .18) !important;
    color: #e2eaf8 !important;
}

body.dark .search-input::placeholder,
body.dark .ai-input::placeholder,
body.dark .field-input::placeholder {
    color: #4a6fa5 !important;
}

body.dark .search-input:focus,
body.dark .ai-input:focus,
body.dark .field-input:focus {
    background: #0b1628 !important;
    border-color: #818cf8 !important;
}

/* ── Flash messages ── */
body.dark .flash-ok {
    background: rgba(55, 48, 163, .15) !important;
    border-color: rgba(99, 102, 241, .3) !important;
    color: #a5b4fc !important;
}

body.dark .flash-err {
    background: rgba(220, 38, 38, .1) !important;
    border-color: rgba(248, 113, 113, .3) !important;
    color: #f87171 !important;
}

body.dark .warn-banner {
    background: rgba(154, 52, 18, .15) !important;
    border-color: rgba(234, 88, 12, .3) !important;
    color: #fb923c !important;
}

/* ── Tip / gradient banner stays readable ── */
body.dark .tip-banner {
    /* gradient banners are already dark-safe, keep as is */
}

/* ── Status tags (keep their semantic colors, just darken backgrounds) ── */
body.dark .tag-pending {
    background: rgba(251, 191, 36, .15) !important;
    color: #fcd34d !important;
}

body.dark .tag-approved {
    background: rgba(16, 185, 129, .12) !important;
    color: #34d399 !important;
}

body.dark .tag-claimed {
    background: rgba(168, 85, 247, .15) !important;
    color: #c084fc !important;
}

body.dark .tag-declined,
body.dark .tag-cancelled {
    background: rgba(239, 68, 68, .15) !important;
    color: #f87171 !important;
}

body.dark .tag-expired {
    background: rgba(100, 116, 139, .15) !important;
    color: #94a3b8 !important;
}

body.dark .tag-unclaimed {
    background: rgba(249, 115, 22, .12) !important;
    color: #fb923c !important;
    border-color: rgba(249, 115, 22, .3) !important;
}

body.dark .tag-available {
    background: rgba(16, 185, 129, .12) !important;
    color: #34d399 !important;
}

body.dark .tag-out {
    background: rgba(239, 68, 68, .12) !important;
    color: #f87171 !important;
}

body.dark .tag-pending.bg-\[\#fef3c7\],
body.dark .tag-returned {
    background: rgba(139, 92, 246, .15) !important;
    color: #c084fc !important;
}

body.dark .tag-rejected {
    background: rgba(239, 68, 68, .15) !important;
    color: #f87171 !important;
}

/* ── Cover badges (available/out) on book cards ── */
body.dark .avail-yes {
    background: rgba(16, 185, 129, .15) !important;
    color: #34d399 !important;
}

body.dark .avail-no {
    background: rgba(239, 68, 68, .15) !important;
    color: #f87171 !important;
}

/* ── Genre badge on book cover ── */
body.dark .cover-genre-badge {
    background: rgba(11, 22, 40, .85) !important;
    border-color: rgba(99, 102, 241, .3) !important;
    color: #a5b4fc !important;
}

/* ── Quota pill on reservation list ── */
body.dark .quota-pill {
    background: rgba(55, 48, 163, .15) !important;
    border-color: rgba(99, 102, 241, .25) !important;
    color: #a5b4fc !important;
}

/* ── Notice blocks inside reservation modal ── */
body.dark .notice-pending {
    background: rgba(180, 83, 9, .12) !important;
    border-color: rgba(217, 119, 6, .3) !important;
}

body.dark .notice-pending p {
    color: #fcd34d !important;
}

body.dark .notice-declined {
    background: rgba(185, 28, 28, .12) !important;
    border-color: rgba(239, 68, 68, .3) !important;
}

body.dark .notice-declined p {
    color: #f87171 !important;
}

body.dark .notice-expired {
    background: rgba(100, 116, 139, .1) !important;
    border-color: rgba(100, 116, 139, .2) !important;
}

body.dark .notice-expired p {
    color: #94a3b8 !important;
}

body.dark .notice-unclaimed {
    background: rgba(194, 65, 12, .1) !important;
    border-color: rgba(249, 115, 22, .3) !important;
}

body.dark .notice-unclaimed p {
    color: #fb923c !important;
}

body.dark .notice-claimed {
    background: rgba(126, 34, 206, .12) !important;
    border-color: rgba(192, 132, 252, .3) !important;
}

body.dark .notice-claimed p {
    color: #c084fc !important;
}

/* ── Ticket section (QR code area) ── */
body.dark .ticket-section {
    background: rgba(55, 48, 163, .12) !important;
    border-color: rgba(99, 102, 241, .25) !important;
}

body.dark .ticket-section p {
    color: #a5b4fc !important;
}

/* ── Library banner stats ── */
body.dark .lib-stat {
    background: rgba(255, 255, 255, .06) !important;
    border-color: rgba(255, 255, 255, .08) !important;
}

/* ── Pending icon (reservation submitted page) ── */
body.dark .pending-icon {
    background: rgba(251, 191, 36, .1) !important;
    border-color: rgba(253, 224, 71, .3) !important;
}

/* ── Success card detail boxes ── */
body.dark .success-card [style*="background:#f8fafc"] {
    background: #101e35 !important;
    border-color: rgba(99, 102, 241, .1) !important;
}

body.dark .success-card [style*="color:#0f172a"] {
    color: #e2eaf8 !important;
}

body.dark .success-card [style*="color:#94a3b8"] {
    color: #4a6fa5 !important;
}

/* ── User card avatar ── */
body.dark .user-avatar {
    box-shadow: 0 2px 8px rgba(55, 48, 163, .5) !important;
}

/* ── Scrollbar in dark ── */
body.dark ::-webkit-scrollbar-thumb {
    background: #1e3a5f !important;
}

body.dark ::-webkit-scrollbar-track {
    background: #060e1e !important;
}
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'fa-house',     'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'fa-plus',      'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',  'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books',            'icon' => 'fa-book-open', 'label' => 'Library',         'key' => 'books'],
        ['url' => '/profile',          'icon' => 'fa-user',      'label' => 'Profile',         'key' => 'profile'],
    ];
    $page = $page ?? '';
    ?>
    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">Resident Portal</div>
                <div class="brand-name">my<em>Space.</em></div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-lbl">Menu</div>
                <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:14px;color:<?= $active ? 'white' : '#64748b' ?>;"></i></div>
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
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                <a href="<?= base_url($item['url']) ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>">
                    <i class="fa-solid <?= $item['icon'] ?>" style="font-size:20px;"></i>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-logout">
                <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:20px;"></i>
            </a>
        </div>
    </nav>

    <main class="main-area">
        <div class="success-card">
            <div class="pending-icon">
                <i class="fa-regular fa-hourglass-half" style="font-size:1.8rem;color:#ca8a04;"></i>
            </div>
            <div style="text-align:center;margin-bottom:24px;">
                <h1 style="font-size:1.4rem;font-weight:800;color:#0f172a;letter-spacing:-.03em;margin-bottom:6px;">Reservation Submitted!</h1>
                <p style="font-size:.82rem;color:#94a3b8;font-weight:500;">Your request is pending approval from an SK officer.</p>
            </div>

            <!-- Details -->
            <div style="background:#f8fafc;border-radius:var(--r-md);padding:16px;border:1px solid rgba(99,102,241,.08);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div style="width:28px;height:28px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-receipt" style="color:#d97706;font-size:11px;"></i>
                    </div>
                    <span style="font-size:.78rem;font-weight:700;color:#0f172a;">Reservation Details</span>
                    <span style="margin-left:auto;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:999px;">Pending</span>
                </div>
                <div class="detail-row"><span class="detail-label">Reservation ID</span><span class="detail-value" style="font-family:var(--mono);color:#94a3b8;">#<?= $reservation['id'] ?></span></div>
                <div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value"><?= esc($reservation['resource_name'] ?? 'Resource') ?></span></div>
                <?php if (!empty($reservation['pc_number'])): ?>
                    <div class="detail-row"><span class="detail-label">Workstation</span><span class="detail-value"><?= esc($reservation['pc_number']) ?></span></div>
                <?php endif; ?>
                <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value"><?= date('F j, Y', strtotime($reservation['reservation_date'])) ?></span></div>
                <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value"><?= date('g:i A', strtotime($reservation['start_time'])) ?> – <?= date('g:i A', strtotime($reservation['end_time'])) ?></span></div>
                <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value"><?= esc($reservation['purpose'] ?? '—') ?></span></div>
            </div>

            <!-- What's next -->
            <div style="background:var(--card);border-radius:var(--r-md);padding:16px;border:1px solid rgba(99,102,241,.08);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-list-check" style="color:var(--indigo);font-size:11px;"></i>
                    </div>
                    <span style="font-size:.78rem;font-weight:700;color:#0f172a;">What happens next?</span>
                </div>
                <div class="step-item">
                    <div class="step-num" style="background:#fef3c7;color:#92400e;">1</div>
                    <div>
                        <p style="font-weight:700;font-size:.82rem;color:#0f172a;">Waiting for review</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">An SK officer will review your request</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num" style="background:#f1f5f9;color:#94a3b8;">2</div>
                    <div>
                        <p style="font-weight:700;font-size:.82rem;color:#94a3b8;">Approval notification</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">You'll get a notification once approved</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num" style="background:#f1f5f9;color:#94a3b8;">3</div>
                    <div>
                        <p style="font-weight:700;font-size:.82rem;color:#94a3b8;">E-ticket released</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Your QR e-ticket will be available after approval</p>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                <a href="<?= base_url('/reservation-list') ?>" class="btn-secondary"><i class="fa-regular fa-calendar"></i> My Reservations</a>
                <a href="<?= base_url('/dashboard') ?>" class="btn-primary"><i class="fa-solid fa-house"></i> Dashboard</a>
            </div>
            <div style="display:flex;justify-content:center;gap:20px;padding-top:12px;border-top:1px solid rgba(99,102,241,.08);">
                <a href="<?= base_url('/reservation') ?>" style="font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;display:flex;align-items:center;gap:4px;"><i class="fa-solid fa-plus" style="font-size:10px;"></i> New Reservation</a>
                <span style="color:#e2e8f0;">|</span>
                <a href="<?= base_url('/reservation-list') ?>" style="font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;display:flex;align-items:center;gap:4px;"><i class="fa-regular fa-clock" style="font-size:10px;"></i> Check Status</a>
            </div>
        </div>
    </main>
</body>

</html>