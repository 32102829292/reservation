<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | <?= esc($user_name ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e1b4b">
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark-pre');
        })();
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }

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
            --r-sm: 10px; --r-md: 14px; --r-lg: 20px; --r-xl: 24px;
            --sidebar-w: 268px;
            --ease: .18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }

        html { height: 100%; height: 100dvh; font-size: 16px; }
        body {
            font-family: var(--font); background: var(--bg); color: #0f172a;
            display: flex; height: 100vh; height: 100dvh; overflow: hidden;
            font-size: 1rem; line-height: 1.6;
            -webkit-font-smoothing: antialiased; overflow-x: hidden;
        }
        html.dark-pre body { background: #060e1e; }

        /* ── Sidebar ── */
        .sidebar { width: var(--sidebar-w); flex-shrink: 0; padding: 18px 14px; height: 100vh; height: 100dvh; display: flex; flex-direction: column; }
        .sidebar-inner { background: var(--card); border-radius: var(--r-xl); border: 1px solid rgba(99,102,241,.1); height: 100%; display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-md); }
        .sidebar-top { padding: 22px 18px 16px; border-bottom: 1px solid rgba(99,102,241,.07); }
        .brand-tag { font-size: .6rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px; }
        .brand-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.03em; line-height: 1.1; }
        .brand-name em { font-style: normal; color: var(--indigo); }
        .brand-sub { font-size: .7rem; color: #94a3b8; margin-top: 3px; }
        .user-card { margin: 12px 12px 0; background: var(--indigo-light); border-radius: var(--r-md); padding: 12px 14px; border: 1px solid var(--indigo-border); }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--indigo); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(55,48,163,.3); }
        .user-name-txt { font-size: .8rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
        .user-role-txt { font-size: .68rem; color: #6366f1; font-weight: 500; margin-top: 1px; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 3px; }
        .sidebar-nav::-webkit-scrollbar { width: 2px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
        .nav-section-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #cbd5e1; padding: 10px 10px 5px; margin-top: 2px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #64748b; text-decoration: none; transition: all var(--ease); }
        .nav-link:hover { background: var(--indigo-light); color: var(--indigo); }
        .nav-link.active { background: var(--indigo); color: #fff; box-shadow: 0 4px 14px rgba(55,48,163,.32); }
        .nav-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.15); }
        .nav-link:not(.active) .nav-icon { background: #f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background: #e0e7ff; }
        .nav-badge { margin-left: auto; background: rgba(239,68,68,.15); color: #dc2626; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; }
        .nav-link.active .nav-badge { background: rgba(255,255,255,.22); color: #fff; }
        .quota-wrap { margin: 8px 12px; background: #f8fafc; border-radius: var(--r-sm); padding: 12px 14px; border: 1px solid rgba(99,102,241,.09); }
        .quota-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .quota-lbl { font-size: .7rem; font-weight: 600; color: #64748b; }
        .quota-val { font-size: .7rem; font-weight: 700; color: var(--indigo); font-family: var(--mono); }
        .quota-track { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .quota-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--indigo), #818cf8); transition: width .6s cubic-bezier(.34,1.56,.64,1); }
        .quota-note { font-size: .7rem; color: #94a3b8; margin-top: 5px; }
        .quota-note.warn { color: #d97706; font-weight: 600; }
        .quota-note.err  { color: #dc2626; font-weight: 700; }
        .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07); }
        .logout-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all var(--ease); }
        .logout-link:hover { background: #fef2f2; color: #dc2626; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { display: none; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; background: white; border-top: 1px solid rgba(99,102,241,.1); height: var(--mob-nav-total); z-index: 200; box-shadow: 0 -4px 20px rgba(55,48,163,.1); }
        .mobile-scroll-container { display: flex; justify-content: space-evenly; align-items: center; height: var(--mob-nav-h); width: 100%; }
        .mob-nav-item { flex: 1; display: flex; align-items: center; justify-content: center; height: 48px; border-radius: 14px; cursor: pointer; text-decoration: none; color: #64748b; position: relative; transition: background .15s, color .15s; }
        .mob-nav-item:hover { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active { background: var(--indigo-light); }
        .mob-nav-item.active svg { stroke: var(--indigo); }
        .mob-nav-item.active::after { content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; background: var(--indigo); border-radius: 50%; }
        .mob-nav-item.mob-badge-wrap { position: relative; }
        .mob-badge { position: absolute; top: 6px; right: 20%; background: #ef4444; color: white; font-size: .5rem; font-weight: 700; width: 14px; height: 14px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; pointer-events: none; }
        .mob-logout { color: #94a3b8; }
        .mob-logout:hover { background: #fef2f2; color: #dc2626; }

        @media(max-width:1023px) { .sidebar { display: none !important; } .mobile-nav-pill { display: flex !important; } .main-area { padding-bottom: calc(var(--mob-nav-total) + 16px) !important; } }
        @media(min-width:1024px) { .sidebar { display: flex !important; } .mobile-nav-pill { display: none !important; } }

        /* ── Main ── */
        .main-area { flex: 1; min-width: 0; padding: 24px 28px 40px; height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; overscroll-behavior-y: contain; }
        @media(max-width:1023px) { .main-area::-webkit-scrollbar { display: none; } .main-area { scrollbar-width: none; } }
        @media(min-width:1024px) { .main-area::-webkit-scrollbar { width: 4px; } .main-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; } }

        /* ── Topbar ── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
        .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .greeting-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .greeting-sub { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; }
        .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }
        .reserve-btn { display: flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--indigo); color: #fff; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); text-decoration: none; box-shadow: 0 4px 12px rgba(55,48,163,.28); }
        .reserve-btn:hover { background: #312e81; transform: translateY(-1px); }

        /* ── Cards ── */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }

        /* ── Flash ── */
        .flash-ok { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding: 13px 18px; background: var(--indigo-light); border: 1px solid var(--indigo-border); color: var(--indigo); font-weight: 600; border-radius: var(--r-md); font-size: .9rem; animation: slideUp .4s ease both; }
        .flash-err { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding: 13px 18px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-weight: 600; border-radius: var(--r-md); font-size: .9rem; animation: slideUp .4s ease both; }
        .warn-banner { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; padding: 13px 18px; background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; font-weight: 600; border-radius: var(--r-md); font-size: .85rem; animation: slideUp .4s ease both; }

        /* ── Filters ── */
        .filter-row { display: flex; gap: 10px; flex-wrap: wrap; }
        .search-wrap { flex: 1; min-width: 180px; position: relative; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
        .search-input { width: 100%; padding: 10px 12px 10px 34px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .85rem; font-family: var(--font); background: white; color: #0f172a; transition: all var(--ease); outline: none; }
        .search-input:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .filter-select { padding: 10px 14px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .85rem; font-family: var(--font); background: white; color: #0f172a; outline: none; transition: all var(--ease); cursor: pointer; min-width: 150px; }
        .filter-select:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 0 0 var(--r-lg) var(--r-lg); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 580px; }
        thead th { background: #f8fafc; font-weight: 700; text-transform: uppercase; font-size: .6rem; letter-spacing: .14em; color: #94a3b8; padding: 12px 16px; border-bottom: 1px solid rgba(99,102,241,.08); white-space: nowrap; }
        thead th:first-child { border-radius: 0; }
        tbody td { padding: 12px 16px; border-bottom: 1px solid rgba(99,102,241,.05); vertical-align: middle; font-size: .85rem; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background var(--ease); }
        tbody tr:hover td { background: #f8fafc; }

        /* ── Status tags ── */
        .tag { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
        .tag-pending   { background: #fef3c7; color: #92400e; }
        .tag-approved  { background: #dcfce7; color: #166534; }
        .tag-claimed   { background: #ede9fe; color: #5b21b6; }
        .tag-declined, .tag-cancelled { background: #fee2e2; color: #991b1b; }
        .tag-expired   { background: #f1f5f9; color: #475569; }
        .tag-unclaimed { background: #fff7ed; color: #c2410c; border: 1px dashed #fdba74; }

        /* ── Mobile reservation cards ── */
        .res-card {
            background: var(--card); border-radius: var(--r-lg);
            border: 1px solid rgba(99,102,241,.08);
            box-shadow: var(--shadow-sm);
            padding: 14px 16px; cursor: pointer;
            transition: all var(--ease); position: relative; overflow: hidden;
        }
        .res-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; border-radius: 0 3px 3px 0; }
        .res-card[data-status="pending"]::before   { background: #f59e0b; }
        .res-card[data-status="approved"]::before  { background: #10b981; }
        .res-card[data-status="claimed"]::before   { background: #a855f7; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="cancelled"]::before { background: #ef4444; }
        .res-card[data-status="expired"]::before   { background: #94a3b8; }
        .res-card[data-status="unclaimed"]::before { background: #f97316; }
        .res-card:hover { border-color: var(--indigo-border); box-shadow: var(--shadow-md); transform: translateY(-1px); }

        /* ── Action btns ── */
        .action-btn { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all var(--ease); flex-shrink: 0; }
        .action-view   { background: #f1f5f9; color: #64748b; }
        .action-view:hover { background: var(--indigo-light); color: var(--indigo); }
        .action-cancel { background: #fef2f2; color: #dc2626; }
        .action-cancel:hover { background: #fee2e2; }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 48px 24px; }
        .empty-icon { display: flex; justify-content: center; margin-bottom: 12px; color: #e2e8f0; }

        /* ── Modal ── */
        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 480px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg); -webkit-overflow-scrolling: touch; }
        .modal-card::-webkit-scrollbar { width: 3px; }
        .modal-card::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
        .sheet-handle { display: none; width: 36px; height: 4px; background: #e2e8f0; border-radius: 999px; margin: 0 auto 16px; }

        @media(max-width:639px) {
            .modal-back { padding: 0; align-items: flex-end !important; }
            .modal-card { border-radius: var(--r-xl) var(--r-xl) 0 0; max-width: 100%; max-height: 92dvh; animation: sheetUp .25s cubic-bezier(.34,1.2,.64,1) both; }
            .sheet-handle { display: block; }
        }
        @keyframes sheetUp { from { opacity:0; transform:translateY(60px); } to { opacity:1; transform:none; } }

        /* ── Detail rows ── */
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 9px 0; border-bottom: 1px solid rgba(99,102,241,.06); gap: 12px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; flex-shrink: 0; padding-top: 1px; }
        .detail-value { font-weight: 600; color: #0f172a; font-size: .83rem; text-align: right; word-break: break-word; max-width: 62%; }

        /* ── Ticket / notice blocks ── */
        .ticket-section { background: var(--indigo-light); border: 1.5px dashed var(--indigo-border); border-radius: var(--r-md); padding: 20px; display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .notice-block { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: var(--r-md); border: 1px solid; }
        .notice-pending  { background: #fef9ec; border-color: #fde68a; }
        .notice-claimed  { background: #faf5ff; border-color: #d8b4fe; flex-direction: column; align-items: center; text-align: center; padding: 20px; border-style: dashed; border-width: 1.5px; }
        .notice-expired  { background: #f8fafc; border-color: rgba(99,102,241,.12); }
        .notice-declined { background: #fef2f2; border-color: #fecaca; }
        .notice-unclaimed{ background: #fff7ed; border-color: #fdba74; border-style: dashed; border-width: 1.5px; }
        .notice-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

        /* ── Download btn ── */
        .dl-btn { display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: var(--indigo); color: white; border-radius: var(--r-sm); font-size: .8rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); box-shadow: 0 4px 12px rgba(55,48,163,.28); }
        .dl-btn:hover { background: #312e81; }

        /* ── Quota pill ── */
        .quota-pill { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: var(--indigo-light); border: 1px solid var(--indigo-border); border-radius: var(--r-sm); font-size: .75rem; font-weight: 700; color: var(--indigo); }

        /* ── Animations ── */
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s  ease both; }

        /* ── Mobile ── */
        @media(max-width:639px) { .main-area { padding: 14px 12px 0; } }

        /* ── Dark mode ── */
        body.dark { --bg:#060e1e; --card:#0b1628; --indigo-light:rgba(55,48,163,.12); --indigo-border:rgba(99,102,241,.25); color:#e2eaf8; }
        body.dark .sidebar-inner { background:#0b1628; border-color:rgba(99,102,241,.12); }
        body.dark .sidebar-top, body.dark .sidebar-footer { border-color:rgba(99,102,241,.1); }
        body.dark .brand-name { color:#e2eaf8; }
        body.dark .nav-link { color:#7fb3e8; }
        body.dark .nav-link:hover { background:rgba(99,102,241,.12); color:#a5b4fc; }
        body.dark .nav-link:not(.active) .nav-icon { background:rgba(99,102,241,.1); }
        body.dark .user-card { background:rgba(55,48,163,.15); border-color:rgba(99,102,241,.2); }
        body.dark .user-name-txt { color:#e2eaf8; }
        body.dark .greeting-name { color:#e2eaf8; }
        body.dark .card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .icon-btn { background:#0b1628; border-color:rgba(99,102,241,.15); color:#7fb3e8; }
        body.dark .icon-btn:hover { background:rgba(99,102,241,.12); }
        body.dark .search-input, body.dark .filter-select { background:#101e35; border-color:rgba(99,102,241,.18); color:#e2eaf8; }
        body.dark .search-input:focus, body.dark .filter-select:focus { background:#0b1628; }
        body.dark thead th { background:#101e35; border-color:rgba(99,102,241,.08); }
        body.dark tbody td { border-color:rgba(99,102,241,.05); }
        body.dark tbody tr:hover td { background:#101e35; }
        body.dark .res-card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .res-card:hover { border-color:rgba(99,102,241,.3); }
        body.dark .action-view { background:#101e35; color:#7fb3e8; }
        body.dark .action-cancel { background:rgba(220,38,38,.1); color:#f87171; }
        body.dark .mobile-nav-pill { background:#0b1628; border-color:rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color:#7fb3e8; }
        body.dark .mob-nav-item.active { background:rgba(99,102,241,.18); }
        body.dark .mob-badge { border-color:#0b1628; }
        body.dark .modal-card { background:#0b1628; color:#e2eaf8; }
        body.dark .detail-row { border-color:rgba(99,102,241,.08); }
        body.dark .detail-value { color:#e2eaf8; }
        body.dark .quota-wrap { background:#101e35; border-color:rgba(99,102,241,.1); }
        body.dark .quota-track { background:rgba(99,102,241,.15); }
        body.dark .flash-ok { background:rgba(55,48,163,.15); border-color:rgba(99,102,241,.3); color:#a5b4fc; }
        body.dark .flash-err { background:rgba(220,38,38,.1); border-color:rgba(248,113,113,.3); color:#f87171; }
        body.dark .warn-banner { background:rgba(154,52,18,.15); border-color:rgba(234,88,12,.3); color:#fb923c; }
        body.dark .ticket-section { background:rgba(55,48,163,.12); border-color:rgba(99,102,241,.25); }
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

    $processed = [];
    foreach (($reservations ?? []) as $res) {
        $isClaimed = !empty($res['claimed']) && $res['claimed'] == 1;
        $status = $isClaimed ? 'claimed' : strtolower($res['status'] ?? 'pending');
        if ($status === 'approved') {
            $edt = strtotime($res['reservation_date'] . ' ' . ($res['end_time'] ?? '23:59:59'));
            if ($edt < time()) $status = 'unclaimed';
        } elseif ($status === 'pending') {
            $rdt = strtotime($res['reservation_date'] ?? '');
            if ($rdt && $rdt < strtotime('today')) $status = 'expired';
        }
        $res['_status'] = $status;
        $processed[] = $res;
    }
    $unclaimedCount = count(array_filter($processed, fn($r) => $r['_status'] === 'unclaimed'));
    $pendingCount   = count(array_filter($processed, fn($r) => $r['_status'] === 'pending'));

    $maxSlots  = 3;
    $remaining = $remainingReservations ?? 3;
    $usedSlots = $maxSlots - $remaining;

    $avatarLetter = strtoupper(mb_substr(trim($user_name ?? 'U'), 0, 1));

    function icon2($name, $size = 16, $stroke = 'currentColor') {
        $icons = [
            'house'      => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
            'plus'       => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
            'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'book-open'  => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
            'user'       => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
            'logout'     => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
            'sun'        => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
            'moon'       => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
            'search'     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
            'eye'        => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
            'x'          => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            'ban'        => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>',
            'clock'      => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'check2'     => '<polyline points="20 6 9 17 4 12"/>',
            'check-c'    => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>',
            'check-d'    => '<path d="M17 1l-8.5 8.5L6 7M22 6l-8.5 8.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 13l-4 4 1.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>',
            'ticket'     => '<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round"/>',
            'hourglass'  => '<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/>',
            'triangle'   => '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
            'download'   => '<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
            'filter-x'   => '<path d="M13.5 3H6l6 8v5l4 2v-7l4-8H6" stroke-linecap="round" stroke-linejoin="round"/><line x1="18" y1="18" x2="22" y2="22"/><line x1="22" y1="18" x2="18" y2="22"/>',
            'calendar-x' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="14" x2="14" y2="18"/><line x1="14" y1="14" x2="10" y2="18"/>',
            'desktop'    => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
            'chevron-r'  => '<polyline points="9 18 15 12 9 6"/>',
        ];
        $d = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
        return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="'.$stroke.'" stroke-width="1.8">'.$d.'</svg>';
    }

    $statusIcons = [
        'pending'   => 'clock',
        'approved'  => 'check-c',
        'claimed'   => 'check-d',
        'declined'  => 'ban',
        'cancelled' => 'ban',
        'expired'   => 'hourglass',
        'unclaimed' => 'ticket',
    ];
    ?>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal-back" onclick="handleBackdrop(event,'detailsModal')">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;gap:12px;">
                <div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Reservation Details</h3>
                    <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Full booking information</p>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span id="modalStatusBadge" class="tag"></span>
                    <button onclick="closeModal('detailsModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><?= icon2('x',12,'#64748b') ?></button>
                </div>
            </div>

            <div id="modalBody" style="background:#f8fafc;border-radius:var(--r-md);padding:14px 16px;border:1px solid rgba(99,102,241,.08);margin-bottom:14px;"></div>

            <!-- Notices (all hidden by default) -->
            <div id="pendingNotice" class="notice-block notice-pending" style="display:none;margin-bottom:14px;">
                <div class="notice-icon" style="background:#fef3c7;"><?= icon2('clock',14,'#d97706') ?></div>
                <div>
                    <p style="font-weight:700;font-size:.83rem;color:#92400e;">Awaiting Approval</p>
                    <p style="font-size:.72rem;color:#b45309;margin-top:3px;">Your e-ticket will appear here once an SK officer approves your reservation. Usually within 24 hours.</p>
                </div>
            </div>

            <div id="rejectedNotice" class="notice-block notice-declined" style="display:none;margin-bottom:14px;">
                <div class="notice-icon" style="background:#fee2e2;"><?= icon2('ban',14,'#dc2626') ?></div>
                <div><p style="font-weight:700;font-size:.83rem;color:#991b1b;" id="rejectedText">This reservation was declined.</p></div>
            </div>

            <div id="qrSection" class="ticket-section" style="display:none;margin-bottom:14px;">
                <p style="font-size:.6rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:var(--indigo);">E-Ticket · Scan to Enter</p>
                <canvas id="qrCanvas" style="border-radius:10px;"></canvas>
                <p id="qrCodeText" style="font-size:.7rem;color:#94a3b8;font-family:var(--mono);text-align:center;word-break:break-all;max-width:200px;"></p>
                <button class="dl-btn" onclick="downloadTicket()"><?= icon2('download',14,'white') ?> Download E-Ticket</button>
            </div>

            <div id="claimedNotice" class="notice-block notice-claimed" style="display:none;margin-bottom:14px;">
                <?= icon2('check-d',28,'#a855f7') ?>
                <p style="font-weight:800;font-size:.9rem;color:#5b21b6;margin-top:4px;">Ticket Already Used</p>
                <p style="font-size:.72rem;color:#a78bfa;margin-top:2px;">This reservation has been claimed and cannot be used again.</p>
                <p id="claimedAtText" style="font-size:.68rem;color:#c4b5fd;margin-top:4px;"></p>
            </div>

            <div id="expiredNotice" class="notice-block notice-expired" style="display:none;margin-bottom:14px;">
                <div class="notice-icon" style="background:#f1f5f9;"><?= icon2('hourglass',14,'#64748b') ?></div>
                <div>
                    <p style="font-weight:700;font-size:.83rem;color:#475569;">Reservation Expired</p>
                    <p style="font-size:.72rem;color:#94a3b8;margin-top:3px;">This reservation was never approved before the scheduled date passed.</p>
                </div>
            </div>

            <div id="unclaimedNotice" class="notice-block notice-unclaimed" style="display:none;margin-bottom:14px;">
                <div class="notice-icon" style="background:#ffedd5;"><?= icon2('ticket',14,'#ea580c') ?></div>
                <div>
                    <p style="font-weight:700;font-size:.83rem;color:#c2410c;">Slot Not Used</p>
                    <p style="font-size:.72rem;color:#ea580c;margin-top:3px;">Your reservation was approved but the e-ticket was never scanned. Repeated no-shows may affect future bookings.</p>
                </div>
            </div>

            <button onclick="closeModal('detailsModal')" style="width:100%;padding:11px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background var(--ease);" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Close</button>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal-back" onclick="handleBackdrop(event,'cancelModal')">
        <div class="modal-card" style="max-width:380px;">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px;">
                <div style="width:52px;height:52px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;"><?= icon2('triangle',22,'#dc2626') ?></div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Cancel Reservation?</h3>
                <p style="font-size:.78rem;color:#94a3b8;margin-top:4px;">This action cannot be undone.</p>
                <p style="font-size:.83rem;font-weight:700;color:#0f172a;margin-top:10px;" id="cancelConfirmResource"></p>
            </div>
            <div style="display:flex;gap:10px;">
                <button onclick="closeModal('cancelModal')" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);">Keep it</button>
                <button id="confirmCancelBtn" style="flex:1.4;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;transition:background var(--ease);" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <?= icon2('x',13,'white') ?> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
    <form id="cancelForm" method="POST" action="" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="cancelId"></form>

    <!-- Sidebar -->
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
                    <div class="user-name-txt" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($user_name) ?></div>
                    <div class="user-role-txt">Resident</div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-lbl">Menu</div>
                <?php foreach ($navItems as $item):
                    $active    = ($page == $item['key']);
                    $showBadge = ($item['key'] === 'reservation-list' && $pendingCount > 0);
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><?= icon2($item['icon'], 16, $active ? 'white' : '#64748b') ?></div>
                        <?= $item['label'] ?>
                        <?php if ($showBadge): ?><span class="nav-badge"><?= $pendingCount ?></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if (isset($remainingReservations)): ?>
                <div class="quota-wrap">
                    <div class="quota-row">
                        <span class="quota-lbl">Monthly Quota</span>
                        <span class="quota-val"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                    </div>
                    <div class="quota-track">
                        <div class="quota-fill" style="width:<?= ($usedSlots/$maxSlots)*100 ?>%;<?= $remaining===0?'background:#ef4444':($remaining===1?'background:linear-gradient(90deg,#f59e0b,#fbbf24)':'') ?>"></div>
                    </div>
                    <p class="quota-note <?= $remaining===0?'err':($remaining===1?'warn':'') ?>">
                        <?php if ($remaining===0): ?>⚠ No slots left this month
                        <?php elseif ($remaining===1): ?>⚡ Only 1 slot remaining
                        <?php else: ?><?= $remaining ?> slots remaining this month<?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);"><?= icon2('logout', 16, '#f87171') ?></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <!-- Mobile Nav -->
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active    = ($page == $item['key']);
                $showBadge = ($item['key'] === 'reservation-list' && $pendingCount > 0);
            ?>
                <a href="<?= base_url($item['url']) ?>" class="mob-nav-item <?= $active ? 'active' : '' ?> <?= $showBadge ? 'mob-badge-wrap' : '' ?>" title="<?= esc($item['label']) ?>">
                    <?= icon2($item['icon'], 22, $active ? 'var(--indigo)' : '#64748b') ?>
                    <?php if ($showBadge): ?><span class="mob-badge"><?= $pendingCount > 9 ? '9+' : $pendingCount ?></span><?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-logout" title="Sign Out">
                <?= icon2('logout', 22, '#f87171') ?>
            </a>
        </div>
    </nav>

    <!-- Main -->
    <main class="main-area">

        <!-- Topbar -->
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">Bookings</div>
                <div class="greeting-name">My Reservations</div>
                <div class="greeting-sub">Track and manage your booking requests.</div>
            </div>
            <div class="topbar-right">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn">
                    <span id="dark-icon"><?= icon2('sun', 14, '#94a3b8') ?></span>
                </div>
                <a href="<?= base_url('/reservation') ?>" class="reserve-btn">
                    <?= icon2('plus', 14, 'white') ?> New
                </a>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok"><?= icon2('check-c',15,'var(--indigo)') ?><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><?= icon2('x',15,'#dc2626') ?><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if ($unclaimedCount > 0): ?>
            <div class="warn-banner fade-up">
                <div style="width:34px;height:34px;background:rgba(234,88,12,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><?= icon2('ticket',14,'#ea580c') ?></div>
                <div>
                    <p style="font-weight:700;font-size:.83rem;">You have <?= $unclaimedCount ?> unclaimed reservation<?= $unclaimedCount>1?'s':'' ?></p>
                    <p style="font-size:.72rem;opacity:.8;margin-top:2px;">Repeated no-shows may affect your future booking priority. Please cancel in advance if you can't attend.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="card card-p fade-up-1" style="margin-bottom:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:10px;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;background:var(--indigo-light);border-radius:10px;display:flex;align-items:center;justify-content:center;"><?= icon2('calendar',14,'var(--indigo)') ?></div>
                    <div>
                        <p style="font-size:.82rem;font-weight:700;color:#0f172a;letter-spacing:-.01em;">All Reservations</p>
                        <p style="font-size:.68rem;color:#94a3b8;margin-top:1px;" id="resultHint">Loading…</p>
                    </div>
                </div>
                <?php if (isset($remainingReservations)): ?>
                    <div class="quota-pill"><?= icon2('clock',12,'var(--indigo)') ?> <?= $remainingReservations ?> of <?= $maxSlots ?> slots remaining</div>
                <?php endif; ?>
            </div>
            <div class="filter-row">
                <div class="search-wrap">
                    <span class="search-icon"><?= icon2('search',13,'#94a3b8') ?></span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by resource, date, purpose…" oninput="filterTable()" autocomplete="off">
                </div>
                <select id="statusFilter" class="filter-select" onchange="filterTable()">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="claimed">Claimed</option>
                    <option value="declined">Declined</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="expired">Expired</option>
                    <option value="unclaimed">No-show</option>
                </select>
            </div>
        </div>

        <!-- Desktop table -->
        <div id="desktopWrap" class="card fade-up-2" style="margin-bottom:14px;overflow:hidden;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px;">ID</th>
                            <th>Resource</th>
                            <th>Schedule</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th style="width:90px;text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                        <?php if (empty($processed)): ?>
                            <tr><td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><?= icon2('calendar-x',36,'#e2e8f0') ?></div>
                                    <p style="font-weight:700;font-size:.85rem;color:#94a3b8;">No reservations yet.</p>
                                    <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:7px;margin-top:12px;padding:9px 16px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;text-decoration:none;box-shadow:0 4px 12px rgba(55,48,163,.28);"><?= icon2('plus',13,'white') ?> Make your first reservation</a>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($processed as $res):
                                $status = $res['_status'];
                                $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                $pcNumber = htmlspecialchars($res['pc_number'] ?? '');
                                $purpose  = htmlspecialchars($res['purpose'] ?: '—');
                                $fmtDate  = (new DateTime($res['reservation_date']))->format('M j, Y');
                                $start    = date('g:i A', strtotime($res['start_time']));
                                $end      = date('g:i A', strtotime($res['end_time']));
                                $search   = strtolower("$resource $fmtDate $purpose");
                            ?>
                                <tr class="res-row" data-status="<?= $status ?>" data-id="<?= $res['id'] ?>" data-search="<?= htmlspecialchars($search, ENT_QUOTES) ?>">
                                    <td><span style="font-size:.7rem;font-weight:700;color:#94a3b8;font-family:var(--mono);">#<?= $res['id'] ?></span></td>
                                    <td>
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $resource ?></p>
                                        <?php if ($pcNumber): ?><p style="font-size:.68rem;color:#94a3b8;margin-top:2px;display:flex;align-items:center;gap:4px;"><?= icon2('desktop',10,'#94a3b8') ?><?= $pcNumber ?></p><?php endif; ?>
                                    </td>
                                    <td>
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $fmtDate ?></p>
                                        <p style="font-size:.68rem;color:#94a3b8;margin-top:2px;font-family:var(--mono);"><?= $start ?> – <?= $end ?></p>
                                    </td>
                                    <td><p style="font-size:.82rem;color:#64748b;font-weight:500;"><?= $purpose ?></p></td>
                                    <td>
                                        <span class="tag tag-<?= $status ?>">
                                            <?= icon2($statusIcons[$status] ?? 'clock', 9, 'currentColor') ?>
                                            <?= $status === 'unclaimed' ? 'No-show' : ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                            <button class="action-btn action-view" onclick="viewDetails(<?= $res['id'] ?>)" title="View Details"><?= icon2('eye', 12, 'currentColor') ?></button>
                                            <?php if ($status === 'pending'): ?>
                                                <button class="action-btn action-cancel" onclick="handleCancel(<?= $res['id'] ?>)" title="Cancel"><?= icon2('x', 12, 'currentColor') ?></button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="noResults" style="display:none;">
                <div class="empty-state">
                    <div class="empty-icon"><?= icon2('filter-x',32,'#e2e8f0') ?></div>
                    <p style="font-weight:700;font-size:.85rem;color:#94a3b8;">No reservations match your search.</p>
                </div>
            </div>
        </div>

        <!-- Mobile cards -->
        <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px;" class="fade-up-2">
            <?php if (empty($processed)): ?>
                <div style="text-align:center;padding:48px 24px;background:var(--card);border-radius:var(--r-lg);border:1px dashed rgba(99,102,241,.15);">
                    <div style="display:flex;justify-content:center;margin-bottom:12px;"><?= icon2('calendar-x',36,'#e2e8f0') ?></div>
                    <p style="font-weight:700;font-size:.85rem;color:#94a3b8;">No reservations yet.</p>
                    <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:7px;margin-top:12px;padding:9px 16px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;text-decoration:none;"><?= icon2('plus',13,'white') ?> Make your first</a>
                </div>
            <?php else: ?>
                <?php foreach ($processed as $res):
                    $status = $res['_status'];
                    $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                    $pcNumber = htmlspecialchars($res['pc_number'] ?? '');
                    $purpose  = htmlspecialchars($res['purpose'] ?: '—');
                    $fmtDate  = (new DateTime($res['reservation_date']))->format('M j, Y');
                    $start    = date('g:i A', strtotime($res['start_time']));
                    $end      = date('g:i A', strtotime($res['end_time']));
                    $search   = strtolower("$resource $fmtDate $purpose");
                    $iconColors = [
                        'pending'   => ['bg'=>'#fef3c7','fg'=>'#d97706'],
                        'approved'  => ['bg'=>'#dcfce7','fg'=>'#16a34a'],
                        'claimed'   => ['bg'=>'#ede9fe','fg'=>'#7c3aed'],
                        'declined'  => ['bg'=>'#fee2e2','fg'=>'#dc2626'],
                        'cancelled' => ['bg'=>'#fee2e2','fg'=>'#dc2626'],
                        'expired'   => ['bg'=>'#f1f5f9','fg'=>'#64748b'],
                        'unclaimed' => ['bg'=>'#ffedd5','fg'=>'#ea580c'],
                    ];
                    $ic = $iconColors[$status] ?? ['bg'=>'#f1f5f9','fg'=>'#64748b'];
                ?>
                    <div class="res-card" data-id="<?= $res['id'] ?>" data-status="<?= $status ?>" data-search="<?= htmlspecialchars($search, ENT_QUOTES) ?>" onclick="viewDetails(<?= $res['id'] ?>)">
                        <div style="display:flex;align-items:center;gap:11px;margin-bottom:10px;">
                            <div style="width:38px;height:38px;border-radius:11px;background:<?= $ic['bg'] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <?= icon2($statusIcons[$status] ?? 'clock', 15, $ic['fg']) ?>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-weight:700;font-size:.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $resource ?></p>
                                <?php if ($pcNumber): ?><p style="font-size:.68rem;color:#94a3b8;margin-top:1px;"><?= $pcNumber ?></p><?php endif; ?>
                            </div>
                            <span class="tag tag-<?= $status ?>" style="flex-shrink:0;"><?= $status==='unclaimed'?'No-show':ucfirst($status) ?></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
                            <?= icon2('calendar',10,'#94a3b8') ?>
                            <p style="font-size:.72rem;color:#64748b;font-weight:600;"><?= $fmtDate ?></p>
                            <span style="font-size:.68rem;color:var(--indigo);font-weight:700;font-family:var(--mono);"><?= $start ?> – <?= $end ?></span>
                        </div>
                        <p style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:10px;"><?= $purpose ?></p>
                        <?php if ($status === 'pending'): ?>
                            <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid rgba(99,102,241,.06);" onclick="event.stopPropagation()">
                                <button onclick="handleCancel(<?= $res['id'] ?>)" style="flex:1;height:34px;border-radius:9px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-weight:700;font-size:.75rem;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px;"><?= icon2('x',11,'currentColor') ?> Cancel</button>
                                <button onclick="viewDetails(<?= $res['id'] ?>)" style="flex:1;height:34px;border-radius:9px;background:#f8fafc;border:1px solid rgba(99,102,241,.1);color:#475569;font-weight:700;font-size:.75rem;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px;"><?= icon2('eye',11,'currentColor') ?> View</button>
                            </div>
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;border-top:1px solid rgba(99,102,241,.05);">
                                <span style="font-size:.68rem;color:#cbd5e1;font-family:var(--mono);">#<?= $res['id'] ?></span>
                                <span style="font-size:.68rem;color:#cbd5e1;display:flex;align-items:center;gap:3px;"><?= icon2('chevron-r',10,'currentColor') ?> Tap to view</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="mobileEmpty" style="display:none;text-align:center;padding:48px 24px;background:var(--card);border-radius:var(--r-lg);border:1px dashed rgba(99,102,241,.15);">
            <div style="display:flex;justify-content:center;margin-bottom:12px;"><?= icon2('filter-x',32,'#e2e8f0') ?></div>
            <p style="font-weight:700;font-size:.85rem;color:#94a3b8;">No reservations match your search.</p>
        </div>

    </main>

    <script>
        const reservationsData = <?= json_encode($processed ?? []) ?>;
        const allRows  = Array.from(document.querySelectorAll('.res-row'));
        const allCards = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
        let cancelTargetId = null;

        function filterTable() {
            const q  = document.getElementById('searchInput').value.toLowerCase();
            const sf = document.getElementById('statusFilter').value;
            let n = 0, c = 0;
            const matches = el => {
                const mQ = !q || (el.dataset.search || el.textContent.toLowerCase()).includes(q);
                const mS = !sf || el.dataset.status === sf;
                return mQ && mS;
            };
            allRows.forEach(r => { const s = matches(r); r.style.display = s ? '' : 'none'; if (s) n++; });
            allCards.forEach(card => { const s = matches(card); card.style.display = s ? '' : 'none'; if (s) c++; });
            document.getElementById('noResults').style.display = (allRows.length > 0 && n === 0) ? 'block' : 'none';
            document.getElementById('mobileEmpty').style.display = (allCards.length > 0 && c === 0) ? 'block' : 'none';
            const total = allRows.length || allCards.length;
            document.getElementById('resultHint').textContent = `Showing ${n || c} of ${total} reservation${total !== 1 ? 's' : ''}`;
        }

        function viewDetails(id) {
            const res = reservationsData.find(r => r.id == id);
            if (!res) return;
            const status = res._status || 'pending';
            const isApproved  = status === 'approved';
            const isPending   = status === 'pending';
            const isRejected  = status === 'declined' || status === 'cancelled';
            const isUnclaimed = status === 'unclaimed';
            const code = res.e_ticket_code || null;

            const fmtDate  = new Date(res.reservation_date).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
            const fmtStart = new Date('1970-01-01T' + res.start_time).toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' });
            const fmtEnd   = new Date('1970-01-01T' + res.end_time).toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' });

            const badge = document.getElementById('modalStatusBadge');
            badge.textContent = status === 'unclaimed' ? 'No-show' : (status.charAt(0).toUpperCase() + status.slice(1));
            badge.className = `tag tag-${status}`;

            document.getElementById('modalBody').innerHTML = [
                ['Reservation #', '#' + res.id],
                ['Resource', res.resource_name || ('Resource #' + res.resource_id)],
                ['PC / Station', res.pc_number || '—'],
                ['Date', fmtDate],
                ['Time', fmtStart + ' – ' + fmtEnd],
                ['Purpose', res.purpose || '—'],
            ].map(([l, v]) => `<div class="detail-row"><span class="detail-label">${l}</span><span class="detail-value">${v}</span></div>`).join('');

            ['pendingNotice','rejectedNotice','qrSection','claimedNotice','expiredNotice','unclaimedNotice'].forEach(i => document.getElementById(i).style.display = 'none');

            if (isPending) {
                document.getElementById('pendingNotice').style.display = 'flex';
            } else if (isRejected) {
                document.getElementById('rejectedNotice').style.display = 'flex';
                document.getElementById('rejectedText').textContent = status === 'declined' ? 'This reservation was declined by an SK officer.' : 'This reservation was cancelled.';
            } else if (isApproved && code) {
                document.getElementById('qrSection').style.display = 'flex';
                document.getElementById('qrCodeText').textContent = code;
                QRCode.toCanvas(document.getElementById('qrCanvas'), code, { width: 180, margin: 1, color: { dark: '#1e293b', light: '#ffffff' } });
            } else if (isApproved && !code) {
                document.getElementById('pendingNotice').style.display = 'flex';
            } else if (status === 'expired') {
                document.getElementById('expiredNotice').style.display = 'flex';
            } else if (status === 'claimed') {
                document.getElementById('claimedNotice').style.display = 'flex';
                if (res.claimed_at) {
                    const fc = new Date(res.claimed_at).toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
                    document.getElementById('claimedAtText').textContent = 'Used on ' + fc;
                }
            } else if (isUnclaimed) {
                document.getElementById('unclaimedNotice').style.display = 'flex';
            }

            openModal('detailsModal');
        }

        function downloadTicket() {
            const canvas = document.getElementById('qrCanvas');
            const code   = document.getElementById('qrCodeText').textContent;
            const link   = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        function handleCancel(id) {
            cancelTargetId = id;
            const res = reservationsData.find(r => r.id == id);
            document.getElementById('cancelConfirmResource').textContent = res ? `"${res.resource_name || 'Resource'}"` : '';
            document.getElementById('cancelForm').action = '<?= base_url("reservation/cancel") ?>/' + id;
            openModal('cancelModal');
        }

        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = 'Canceling…';
            document.getElementById('cancelId').value = cancelTargetId;
            document.getElementById('cancelForm').submit();
        });

        function openModal(id) {
            document.getElementById(id).classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
            document.body.style.overflow = '';
            if (id === 'cancelModal') {
                const btn = document.getElementById('confirmCancelBtn');
                btn.disabled = false;
                btn.innerHTML = 'Yes, Cancel';
            }
        }
        function handleBackdrop(e, id) {
            if (e.target.classList.contains('modal-back')) closeModal(id);
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeModal('detailsModal'); closeModal('cancelModal'); }
        });

        function toggleDark() {
            const isDark = document.body.classList.toggle('dark');
            const icon = document.getElementById('dark-icon');
            icon.innerHTML = isDark
                ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`
                : `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        function initResponsive() {
            const dt = document.getElementById('desktopWrap');
            const mc = document.getElementById('mobileCardList');
            const me = document.getElementById('mobileEmpty');
            const isMob = window.innerWidth < 768;
            dt.style.display = isMob ? 'none' : 'block';
            mc.style.display  = isMob ? 'flex'  : 'none';
            me.style.display  = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('dark');
                const icon = document.getElementById('dark-icon');
                if (icon) icon.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
            }
            document.documentElement.classList.remove('dark-pre');
            initResponsive();
            window.addEventListener('resize', () => { initResponsive(); filterTable(); });
            filterTable();
        });
    </script>
</body>
</html>