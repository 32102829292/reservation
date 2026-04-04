<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        (function(){
            if(localStorage.getItem('admin_theme')==='dark') document.documentElement.classList.add('dark-pre');
        })();
    </script>

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
        html { height: 100%; height: 100dvh; font-size: 16px; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: #0f172a;
            display: flex;
            height: 100vh; height: 100dvh;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }
        html.dark-pre body,
        html.dark-pre .sidebar-inner,
        html.dark-pre .mobile-nav-pill { background: #060e1e; }

        /* ══ Sidebar ══ */
        .sidebar { width: var(--sidebar-w); flex-shrink: 0; padding: 18px 14px; height: 100vh; height: 100dvh; display: flex; flex-direction: column; }
        .sidebar-inner { background: var(--card); border-radius: var(--r-xl); border: 1px solid rgba(99,102,241,.1); height: 100%; display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-md); }
        .sidebar-top { padding: 22px 18px 16px; border-bottom: 1px solid rgba(99,102,241,.07); }
        .brand-tag { font-size: .6rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px; }
        .brand-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.03em; line-height: 1.1; }
        .brand-name em { font-style: normal; color: var(--indigo); }
        .brand-sub { font-size: .7rem; color: #94a3b8; margin-top: 3px; }

        .user-card { margin: 12px 12px 0; background: var(--indigo-light); border-radius: var(--r-md); padding: 12px 14px; border: 1px solid var(--indigo-border); display: flex; align-items: center; gap: 9px; }
        .user-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--indigo); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(55,48,163,.3); }
        .user-name-txt { font-size: .8rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role-txt { font-size: .68rem; color: #6366f1; font-weight: 500; margin-top: 1px; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 3px; scrollbar-width: none; }
        .sidebar-nav::-webkit-scrollbar { display: none; }
        .nav-section-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #cbd5e1; padding: 10px 10px 5px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #64748b; text-decoration: none; transition: all var(--ease); touch-action: manipulation; }
        .nav-link:hover { background: var(--indigo-light); color: var(--indigo); }
        .nav-link.active { background: var(--indigo); color: #fff; box-shadow: 0 4px 14px rgba(55,48,163,.32); }
        .nav-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .9rem; }
        .nav-link:not(.active) .nav-icon { background: #f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background: #e0e7ff; }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.15); }
        .nav-badge { margin-left: auto; background: rgba(245,158,11,.18); color: #d97706; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; }
        .nav-link.active .nav-badge { background: rgba(255,255,255,.22); color: #fff; }

        .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07); }
        .logout-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all var(--ease); }
        .logout-link:hover { background: #fef2f2; color: #dc2626; }
        .logout-link:hover .nav-icon { background: #fee2e2; }

        /* ══ Mobile Nav ══ */
        .mobile-nav-pill { display: none; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; background: white; border-top: 1px solid rgba(99,102,241,.1); height: var(--mob-nav-total); z-index: 200; box-shadow: 0 -4px 20px rgba(55,48,163,.1); }
        .mobile-scroll-container { display: flex; justify-content: space-evenly; align-items: center; height: var(--mob-nav-h); width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; padding: 0 4px; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }
        .mob-nav-item { flex-shrink: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 6px 10px; border-radius: 12px; cursor: pointer; text-decoration: none; color: #64748b; position: relative; transition: background .15s, color .15s; font-size: .62rem; font-weight: 700; touch-action: manipulation; gap: 2px; }
        .mob-nav-item:hover { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active::after { content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; background: var(--indigo); border-radius: 50%; }
        .mob-badge { position: absolute; top: 4px; right: 4px; background: #ef4444; color: white; font-size: .5rem; font-weight: 700; width: 14px; height: 14px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; pointer-events: none; }

        @media(max-width:1023px) { .sidebar { display: none !important; } .mobile-nav-pill { display: flex !important; } .main-area { padding-bottom: calc(var(--mob-nav-total) + 16px) !important; } }
        @media(min-width:1024px) { .sidebar { display: flex !important; } .mobile-nav-pill { display: none !important; } }

        /* ══ Main ══ */
        .main-area { flex: 1; min-width: 0; padding: 24px 28px 40px; height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; overscroll-behavior-y: contain; }
        @media(max-width:1023px) { .main-area { scrollbar-width: none; } .main-area::-webkit-scrollbar { display: none; } }
        @media(min-width:1024px) { .main-area::-webkit-scrollbar { width: 4px; } .main-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; } }
        @media(max-width:639px) { .main-area { padding: 14px 12px 0; } }

        /* ══ Topbar ══ */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .greeting-name { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .greeting-date { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; flex-wrap: wrap; }
        @media(max-width:639px) { .greeting-name { font-size: 1.35rem; } }

        .sync-badge { display: inline-flex; align-items: center; gap: 4px; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; white-space: nowrap; }
        .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); touch-action: manipulation; }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }
        .pending-pill { display: flex; align-items: center; gap: 6px; background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 8px 14px; border-radius: var(--r-sm); font-size: .78rem; font-weight: 700; text-decoration: none; transition: all var(--ease); }
        .pending-pill:hover { background: #fde68a; }
        .borrow-pill { display: flex; align-items: center; gap: 6px; background: var(--indigo-light); border: 1px solid var(--indigo-border); color: var(--indigo); padding: 8px 14px; border-radius: var(--r-sm); font-size: .78rem; font-weight: 700; text-decoration: none; transition: all var(--ease); }
        .borrow-pill:hover { background: var(--indigo-border); }
        .notif-bell { position: relative; }
        .notif-badge-dot { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: .55rem; font-weight: 700; padding: 2px 5px; border-radius: 999px; min-width: 17px; text-align: center; border: 2px solid var(--bg); line-height: 1.3; pointer-events: none; }

        /* ══ Section label ══ */
        .section-label, .section-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .section-label::before, .section-lbl::before { content: ''; display: inline-block; width: 3px; height: 14px; border-radius: 2px; background: var(--indigo); flex-shrink: 0; }

        /* ══ Cards ══ */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }
        .card-p-lg { padding: 22px 24px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .9rem; }
        .card-title { font-size: .9rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
        .card-sub { font-size: .7rem; color: #94a3b8; margin-top: 2px; }
        .link-sm { font-size: .65rem; font-weight: 700; color: var(--indigo); text-decoration: none; letter-spacing: .05em; text-transform: uppercase; transition: opacity .15s; }
        .link-sm:hover { opacity: .7; }

        /* ══ Stat cards ══ */
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 20px; }
        .stat-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 18px 20px; box-shadow: var(--shadow-sm); transition: transform var(--ease), box-shadow var(--ease); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 14px; }
        .stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8; }
        .stat-num { font-size: 2rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -.04em; font-family: var(--mono); }
        .stat-hint { font-size: .72rem; color: #94a3b8; margin-top: 4px; }
        .stat-badge { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; }
        @media(max-width:639px) { .stats-grid { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px; } .stat-card { padding: 14px 16px; } .stat-num { font-size: 1.6rem; } }

        /* ══ KPI row ══ */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 18px; }
        .kpi-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-md); padding: 14px 16px; border-left-width: 4px; box-shadow: var(--shadow-sm); transition: transform var(--ease); }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .kpi-num { font-size: 1.6rem; font-weight: 800; font-family: var(--mono); line-height: 1; margin-top: 6px; }
        @media(max-width:639px) { .kpi-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }

        /* ══ Progress bars ══ */
        .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; transition: width .8s cubic-bezier(.34,1.56,.64,1); }

        /* ══ Charts ══ */
        .chart-wrap { position: relative; height: 200px; width: 100%; }
        @media(max-width:639px) { .chart-wrap { height: 160px; } }
        .resource-chart-wrap { display: flex; align-items: center; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
        .resource-chart-canvas { width: 150px !important; height: 150px !important; flex-shrink: 0; }

        /* ══ Grid layouts ══ */
        .grid-main { display: grid; grid-template-columns: minmax(0,1.9fr) minmax(0,1fr); gap: 16px; margin-bottom: 18px; }
        .side-col { display: flex; flex-direction: column; gap: 14px; }
        @media(max-width:900px) { .grid-main { grid-template-columns: 1fr; } }
        .grid-lib { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1.6fr); gap: 16px; margin-bottom: 16px; }
        @media(max-width:900px) { .grid-lib { grid-template-columns: 1fr; } }
        .grid-two { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1fr); gap: 14px; margin-bottom: 18px; }
        @media(max-width:639px) { .grid-two { grid-template-columns: 1fr; } }
        .grid-three { display: grid; grid-template-columns: minmax(0,1.5fr) minmax(0,1fr); gap: 14px; margin-bottom: 18px; }
        @media(max-width:900px) { .grid-three { grid-template-columns: 1fr; } }
        .grid-four { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 18px; }
        @media(max-width:900px) { .grid-four { grid-template-columns: repeat(2, minmax(0,1fr)); } }

        /* ══ Calendar ══ */
        #calendar { font-size: .8rem; font-family: var(--font); }
        .fc .fc-toolbar { flex-wrap: wrap; gap: .5rem; }
        .fc-toolbar-title { font-size: .95rem !important; font-weight: 800 !important; color: #0f172a !important; font-family: var(--font) !important; letter-spacing: -.02em !important; }
        .fc-button-primary { background: var(--indigo) !important; border-color: var(--indigo) !important; border-radius: 9px !important; font-family: var(--font) !important; font-weight: 700 !important; font-size: .72rem !important; padding: .3rem .65rem !important; box-shadow: none !important; }
        .fc-button-primary:hover { background: #312e81 !important; }
        .fc-daygrid-event { border-radius: 5px !important; font-size: .65rem !important; font-weight: 600 !important; padding: 2px 5px !important; border: none !important; cursor: pointer !important; font-family: var(--font) !important; }
        .fc-daygrid-day:hover { background-color: var(--indigo-light) !important; cursor: pointer; }
        .fc-day-today { background: rgba(55,48,163,.06) !important; }
        .fc-day-today .fc-daygrid-day-number { color: var(--indigo) !important; font-weight: 800 !important; }
        .fc-daygrid-day-number { font-size: .72rem; font-weight: 600; font-family: var(--font); }
        .fc-col-header-cell-cushion { font-family: var(--font); font-size: .72rem; font-weight: 700; letter-spacing: .04em; }

        .cal-legend { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .leg-item { display: flex; align-items: center; gap: 5px; }
        .leg-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .leg-lbl { font-size: .68rem; font-weight: 600; color: #94a3b8; }

        /* ══ Live sessions ══ */
        .tl-session-card { background: #f8fafc; border-radius: var(--r-md); border: 1px solid rgba(99,102,241,.08); padding: 12px 14px; border-left-width: 4px; transition: all .2s; box-shadow: var(--shadow-sm); }
        .tl-session-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }
        .tl-session-card.tl-ok       { border-left-color: #10b981; }
        .tl-session-card.tl-warning  { border-left-color: #f59e0b; }
        .tl-session-card.tl-critical { border-left-color: #ef4444; }
        .tl-session-card.tl-ended    { border-left-color: #94a3b8; opacity: .6; }
        .tl-countdown { display: inline-flex; align-items: center; gap: 4px; padding: .2rem .6rem; border-radius: 999px; font-size: .7rem; font-weight: 700; font-family: var(--mono); white-space: nowrap; }
        .tl-ok       .tl-countdown { background: #dcfce7; color: #166534; }
        .tl-warning  .tl-countdown { background: #fef3c7; color: #92400e; }
        .tl-critical .tl-countdown { background: #fee2e2; color: #991b1b; }
        .tl-ended    .tl-countdown { background: #f1f5f9; color: #64748b; }
        .tl-prog-track { height: 4px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 8px; }
        .tl-prog-fill  { height: 100%; border-radius: 999px; transition: width 1s linear; }
        .tl-ok       .tl-prog-fill { background: #10b981; }
        .tl-warning  .tl-prog-fill { background: #f59e0b; }
        .tl-critical .tl-prog-fill { background: #ef4444; }
        .tl-ended    .tl-prog-fill { background: #94a3b8; }

        /* ══ Toast ══ */
        #tl-toast-container { position: fixed; bottom: calc(80px + env(safe-area-inset-bottom,0px)); right: 16px; z-index: 9000; display: flex; flex-direction: column; gap: 8px; pointer-events: none; max-width: 320px; }
        @media(max-width:479px) { #tl-toast-container { left: 12px; right: 12px; max-width: none; } }
        .tl-toast { background: #0f172a; color: white; border-radius: var(--r-md); padding: 12px 14px; box-shadow: var(--shadow-lg); display: flex; align-items: flex-start; gap: 10px; pointer-events: auto; animation: toastIn .3s cubic-bezier(.34,1.56,.64,1) both; }
        .tl-toast.dismissing { animation: toastOut .2s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform: translateX(16px) scale(.96); } to { opacity:1; transform:none; } }
        @keyframes toastOut { to   { opacity:0; transform: translateX(20px) scale(.96); } }
        .tl-toast-icon { width: 30px; height: 30px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .8rem; }

        /* ══ Availability pills ══ */
        .avail-pill { font-size: .6rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; flex-shrink: 0; }
        .avail-on  { background: #dcfce7; color: #166634; }
        .avail-off { background: #fee2e2; color: #991b1b; }
        .avail-low { background: #fef3c7; color: #92400e; }

        /* ══ Quick actions ══ */
        .qa-link { display: flex; align-items: center; gap: 11px; padding: 11px 12px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.09); background: white; text-decoration: none; color: #475569; font-size: .83rem; font-weight: 600; transition: all var(--ease); }
        .qa-link:hover { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo); transform: translateX(3px); }
        .qa-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .85rem; }
        .qa-chev { margin-left: auto; color: #cbd5e1; transition: color var(--ease); }
        .qa-link:hover .qa-chev { color: var(--indigo); }

        /* ══ Booking rows ══ */
        .bk-row { display: flex; align-items: center; gap: 10px; padding: 8px; border-radius: 11px; text-decoration: none; color: inherit; transition: background var(--ease); }
        .bk-row:hover { background: var(--indigo-light); }
        .bk-date { width: 38px; height: 38px; background: #f8fafc; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(99,102,241,.09); }
        .bk-month { font-size: .55rem; font-weight: 700; text-transform: uppercase; color: #94a3b8; }
        .bk-day   { font-size: .95rem; font-weight: 800; color: #0f172a; line-height: 1; font-family: var(--mono); }
        .bk-name  { font-size: .82rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bk-time  { font-size: .68rem; color: #94a3b8; margin-top: 1px; font-family: var(--mono); }

        /* ══ Status tags ══ */
        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
        .tag-pending  { background: #fef3c7; color: #92400e; }
        .tag-approved { background: #dcfce7; color: #166534; }
        .tag-claimed  { background: #ede9fe; color: #5b21b6; }
        .tag-declined, .tag-canceled { background: #fee2e2; color: #991b1b; }

        /* ══ Library banner ══ */
        .lib-banner { background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%); border-radius: var(--r-lg); padding: 22px; overflow: hidden; position: relative; }
        .lib-stat-item { flex: 1; background: rgba(255,255,255,.1); border-radius: 10px; padding: 8px 10px; border: 1px solid rgba(255,255,255,.1); }
        .lib-stat-lbl { font-size: .52rem; font-weight: 600; color: rgba(255,255,255,.55); text-transform: uppercase; letter-spacing: .06em; }
        .lib-stat-val { font-size: .95rem; font-weight: 800; color: white; font-family: var(--mono); }

        /* ══ Book rows ══ */
        .book-letter { width: 34px; height: 34px; border-radius: 9px; background: var(--indigo-light); color: var(--indigo); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .8rem; flex-shrink: 0; }
        .book-spine { width: 3px; border-radius: 4px; align-self: stretch; flex-shrink: 0; min-height: 26px; }
        .book-row { display: flex; align-items: center; gap: 10px; padding: 7px 6px; border-radius: 10px; text-decoration: none; color: inherit; transition: background .15s; }
        .book-row:hover { background: var(--indigo-light); }

        /* ══ Pending item ══ */
        .pending-item { padding: .7rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; transition: all .2s; cursor: pointer; text-decoration: none; display: block; }
        .pending-item:hover { background: #fef3c7; border-color: #fbbf24; }

        /* ══ Borrow request ══ */
        .borrow-req { display: flex; align-items: center; gap: 10px; padding: .65rem .75rem; border-radius: var(--r-sm); background: var(--indigo-light); border: 1px solid var(--indigo-border); transition: all .18s; }
        .borrow-req:hover { background: var(--indigo-border); }
        .btn-approve { font-size: .65rem; font-weight: 800; padding: .35rem .7rem; border-radius: 9px; background: var(--indigo); color: white; border: none; cursor: pointer; transition: background .15s; min-width: 34px; min-height: 34px; }
        .btn-approve:hover { background: #312e81; }
        .btn-reject  { font-size: .65rem; font-weight: 800; padding: .35rem .7rem; border-radius: 9px; background: #fee2e2; color: #dc2626; border: none; cursor: pointer; transition: background .15s; min-width: 34px; min-height: 34px; }
        .btn-reject:hover { background: #fecaca; }

        /* ══ Insight cards ══ */
        .insight-mini { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 16px 18px; box-shadow: var(--shadow-sm); overflow: hidden; position: relative; transition: transform var(--ease), box-shadow var(--ease); }
        .insight-mini:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .insight-mini::before { content: attr(data-emoji); position: absolute; right: -8px; top: -8px; font-size: 4rem; opacity: .04; pointer-events: none; line-height: 1; }
        .ins-heatmap-cell { height: 28px; border-radius: 5px; cursor: default; transition: transform .15s; position: relative; }
        .ins-heatmap-cell:hover { transform: scaleY(1.1); }

        /* ══ Date Modal ══ */
        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 520px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg); }
        .date-row { display: flex; align-items: center; gap: 11px; padding: .75rem; border-bottom: 1px solid #f8fafc; border-radius: 10px; transition: background .15s; cursor: pointer; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }
        @media(max-width:479px) { .modal-back { padding: .75rem; } .modal-card { padding: 18px 16px; border-radius: var(--r-lg); } }

        /* ══ Print Modal ══ */
        .print-modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 400; padding: 1.5rem; align-items: center; justify-content: center; }
        .print-modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .print-modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 400px; padding: 24px; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg); }
        .tl-print-toggle { display: flex; border-radius: var(--r-sm); overflow: hidden; border: 1px solid rgba(99,102,241,.15); background: #f8fafc; }
        .tl-print-toggle button { flex: 1; padding: .75rem .5rem; font-size: .78rem; font-weight: 700; border: none; background: transparent; cursor: pointer; transition: all .15s; color: #64748b; font-family: var(--font); }
        .tl-print-toggle button.active { background: var(--indigo); color: white; border-radius: var(--r-sm); box-shadow: 0 4px 12px -2px rgba(55,48,163,.35); }
        .tl-page-counter { display: flex; align-items: center; gap: 12px; background: #f8fafc; border-radius: var(--r-sm); padding: .75rem 1rem; border: 1px solid rgba(99,102,241,.12); }
        .tl-page-counter button { width: 38px; height: 38px; border-radius: 9px; border: 1px solid rgba(99,102,241,.15); background: white; font-weight: 800; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--indigo); transition: all .15s; flex-shrink: 0; }
        .tl-page-counter button:hover { background: var(--indigo-light); border-color: var(--indigo-border); }
        .tl-page-num { flex: 1; text-align: center; font-size: 1.5rem; font-weight: 800; color: #0f172a; font-family: var(--mono); }
        .tl-save-btn { width: 100%; padding: .875rem; border-radius: var(--r-sm); border: none; background: var(--indigo); color: white; font-size: .875rem; font-weight: 800; cursor: pointer; transition: all .2s; margin-top: 1rem; font-family: var(--font); box-shadow: 0 4px 14px rgba(55,48,163,.28); }
        .tl-save-btn:hover { background: #312e81; }
        .tl-save-btn:disabled { opacity: .6; cursor: not-allowed; }
        .tl-skip-btn { width: 100%; padding: .65rem; border-radius: var(--r-sm); border: none; background: transparent; color: #94a3b8; font-size: .8rem; font-weight: 700; cursor: pointer; transition: color .15s; margin-top: .3rem; font-family: var(--font); }
        .tl-skip-btn:hover { color: #64748b; }

        /* ══ Notif dropdown ══ */
        .notif-dd { position: fixed; top: 80px; right: 20px; width: 320px; background: white; border-radius: var(--r-xl); box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.09); z-index: 200; display: none; overflow: hidden; animation: dropIn .15s ease; }
        .notif-dd.show { display: block; }
        .notif-item { padding: .85rem 1.1rem; border-bottom: 1px solid #f8fafc; transition: background .15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: var(--indigo-light); }
        .notif-item:last-child { border-bottom: none; }
        @media(max-width:479px) { .notif-dd { left: 12px; right: 12px; width: auto; top: 72px; } }

        /* ══ Animations ══ */
        @keyframes fadeIn   { from{opacity:0}to{opacity:1} }
        @keyframes dropIn   { from{opacity:0;transform:translateY(-4px) scale(.98)}to{opacity:1;transform:none} }
        @keyframes slideUp  { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none} }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s ease both; }
        .fade-up-3 { animation: slideUp .45s .15s ease both; }
        .fade-up-4 { animation: slideUp .45s .2s ease both; }

        /* ══ DARK MODE ══ */
        body.dark {
            --bg: #060e1e;
            --card: #0b1628;
            --indigo-light: rgba(55,48,163,.12);
            --indigo-border: rgba(99,102,241,.25);
            color: #e2eaf8;
        }
        body.dark .sidebar-inner { background: #0b1628; border-color: rgba(99,102,241,.12); }
        body.dark .sidebar-top, body.dark .sidebar-footer { border-color: rgba(99,102,241,.1); }
        body.dark .brand-name { color: #e2eaf8; }
        body.dark .brand-tag, body.dark .brand-sub { color: #4a6fa5; }
        body.dark .nav-section-lbl { color: #1e3a5f; }
        body.dark .nav-link { color: #7fb3e8; }
        body.dark .nav-link:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .nav-link:not(.active) .nav-icon { background: rgba(99,102,241,.1); }
        body.dark .nav-link:hover:not(.active) .nav-icon { background: rgba(99,102,241,.2); }
        body.dark .user-card { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.2); }
        body.dark .user-name-txt { color: #e2eaf8; }
        body.dark .user-role-txt { color: #7fb3e8; }
        body.dark .logout-link { color: #4a6fa5; }
        body.dark .logout-link:hover { background: rgba(239,68,68,.1); color: #f87171; }
        body.dark .logout-link:hover .nav-icon { background: rgba(239,68,68,.12); }
        body.dark .mobile-nav-pill { background: #0b1628; border-color: rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color: #7fb3e8; }
        body.dark .mob-nav-item.active { background: rgba(99,102,241,.18); }
        body.dark .greeting-eyebrow { color: #1e3a5f; }
        body.dark .greeting-name { color: #e2eaf8; }
        body.dark .greeting-date { color: #4a6fa5; }
        body.dark .icon-btn { background: #0b1628; border-color: rgba(99,102,241,.15); color: #7fb3e8; }
        body.dark .icon-btn:hover { background: rgba(99,102,241,.12); border-color: rgba(99,102,241,.3); color: #a5b4fc; }
        body.dark .pending-pill { background: rgba(180,83,9,.2); border-color: rgba(180,83,9,.3); color: #fcd34d; }
        body.dark .borrow-pill { background: rgba(55,48,163,.2); border-color: rgba(99,102,241,.3); color: #a5b4fc; }
        body.dark .card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .card-title { color: #e2eaf8; }
        body.dark .card-sub { color: #4a6fa5; }
        body.dark .section-label, body.dark .section-lbl { color: #4a6fa5; }
        body.dark .link-sm { color: #818cf8; }
        body.dark .stat-card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .stat-num { color: #e2eaf8; }
        body.dark .stat-lbl, body.dark .stat-hint { color: #4a6fa5; }
        body.dark .kpi-card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .prog-bar { background: rgba(99,102,241,.15); }
        body.dark .tl-session-card { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .tl-prog-track { background: rgba(99,102,241,.15); }
        body.dark .tl-ended .tl-countdown { background: #1a2a42; color: #7fb3e8; }
        body.dark .qa-link { background: #0b1628; border-color: rgba(99,102,241,.1); color: #7fb3e8; }
        body.dark .qa-link:hover { background: rgba(99,102,241,.12); border-color: var(--indigo); color: #a5b4fc; }
        body.dark .qa-chev { color: #1e3a5f; }
        body.dark .bk-row:hover { background: rgba(99,102,241,.08); }
        body.dark .bk-date { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .bk-day { color: #e2eaf8; }
        body.dark .bk-name { color: #e2eaf8; }
        body.dark .bk-month, body.dark .bk-time { color: #4a6fa5; }
        body.dark .fc-toolbar-title { color: #e2eaf8 !important; }
        body.dark .fc-daygrid-day-number { color: #7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color: #4a6fa5; }
        body.dark .fc-day-today { background: rgba(55,48,163,.15) !important; }
        body.dark .fc-theme-standard td, body.dark .fc-theme-standard th, body.dark .fc-theme-standard .fc-scrollgrid { border-color: #101e35 !important; }
        body.dark .fc-daygrid-day { background: #0b1628 !important; }
        body.dark .fc-daygrid-day:hover { background-color: rgba(99,102,241,.08) !important; }
        body.dark .modal-card { background: #0b1628; }
        body.dark .date-row { border-color: #101e35; }
        body.dark .date-row:hover { background: #101e35; }
        body.dark .notif-dd { background: #0b1628; border-color: rgba(99,102,241,.15); box-shadow: 0 20px 48px -8px rgba(0,0,0,.5); }
        body.dark .notif-item { border-color: #101e35; }
        body.dark .notif-item.unread { background: rgba(55,48,163,.18); }
        body.dark .notif-item:hover { background: #101e35; }
        body.dark .insight-mini { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .book-letter { background: rgba(55,48,163,.2); color: #818cf8; }
        body.dark .book-row:hover { background: rgba(99,102,241,.08); }
        body.dark .borrow-req { background: rgba(55,48,163,.12); border-color: rgba(99,102,241,.2); }
        body.dark .pending-item { background: rgba(180,83,9,.12); border-color: rgba(180,83,9,.2); }
        body.dark .pending-item:hover { background: rgba(180,83,9,.2); }
        body.dark .print-modal-card { background: #0b1628; }
        body.dark .tl-print-toggle { background: #101e35; border-color: rgba(99,102,241,.15); }
        body.dark .tl-page-counter { background: #101e35; border-color: rgba(99,102,241,.12); }
        body.dark .tl-page-counter button { background: #0b1628; color: #818cf8; border-color: rgba(99,102,241,.18); }
        body.dark .tl-page-num { color: #e2eaf8; }
        body.dark .ins-heatmap-cell { opacity: .85; }
    </style>
</head>
<body>

<?php
$page     = $page ?? 'dashboard';
$navItems = [
    ['url'=>'/admin/dashboard',           'icon'=>'fa-house',           'label'=>'Dashboard',       'key'=>'dashboard'],
    ['url'=>'/admin/new-reservation',     'icon'=>'fa-plus',            'label'=>'New Reservation', 'key'=>'new-reservation'],
    ['url'=>'/admin/manage-reservations', 'icon'=>'fa-calendar',        'label'=>'Reservations',    'key'=>'manage-reservations'],
    ['url'=>'/admin/manage-pcs',          'icon'=>'fa-desktop',         'label'=>'Manage PCs',      'key'=>'manage-pcs'],
    ['url'=>'/admin/manage-sk',           'icon'=>'fa-user-shield',     'label'=>'Manage SK',       'key'=>'manage-sk'],
    ['url'=>'/admin/books',               'icon'=>'fa-book-open',       'label'=>'Library',         'key'=>'books'],
    ['url'=>'/admin/login-logs',          'icon'=>'fa-clock',           'label'=>'Login Logs',      'key'=>'login-logs'],
    ['url'=>'/admin/scanner',             'icon'=>'fa-qrcode',          'label'=>'Scanner',         'key'=>'scanner'],
    ['url'=>'/admin/activity-logs',       'icon'=>'fa-list',            'label'=>'Activity Logs',   'key'=>'activity-logs'],
    ['url'=>'/admin/profile',             'icon'=>'fa-user',            'label'=>'Profile',         'key'=>'profile'],
];

$approvalRate    = ($total ?? 0)    > 0 ? round((($approved ?? 0) / $total)    * 100) : 0;
$utilizationRate = ($approved ?? 0) > 0 ? round((($claimed  ?? 0) / $approved) * 100) : 0;
$dashBooks         = $dashBooks         ?? [];
$dashBorrowReqs    = $dashBorrowReqs    ?? [];
$bookTotalCount    = $bookTotalCount    ?? 0;
$bookAvailCount    = $bookAvailCount    ?? 0;
$pendingBorrowings = $pendingBorrowings ?? 0;

$insHourArr = array_fill(0,24,0); $insDowArr = array_fill(0,7,0); $insMonArr = array_fill(0,12,0);
$insResMap = []; $insDateVol = []; $ins7 = 0; $insPrev7 = 0;
foreach ($reservations ?? [] as $r) {
    if (!empty($r['start_time']))       $insHourArr[(int)date('G', strtotime($r['start_time']))]++;
    if (!empty($r['reservation_date'])) {
        $insDowArr[(int)date('w', strtotime($r['reservation_date']))]++;
        $insMonArr[(int)date('n', strtotime($r['reservation_date']))-1]++;
        $insDateVol[$r['reservation_date']] = ($insDateVol[$r['reservation_date']] ?? 0) + 1;
        $d = (int)floor((time()-strtotime($r['reservation_date']))/86400);
        if ($d>=0&&$d<7) $ins7++; if ($d>=7&&$d<14) $insPrev7++;
    }
    $insResMap[$r['resource_name']??'Unknown'] = ($insResMap[$r['resource_name']??'Unknown'] ?? 0)+1;
}
$insPH  = array_search(max($insHourArr), $insHourArr);
$insPD  = array_search(max($insDowArr),  $insDowArr);
$insPM  = array_search(max($insMonArr),  $insMonArr);
$f12    = fn($h)=>(($h%12)?:12).' '.($h<12?'AM':'PM');
$insPHL = $f12($insPH).'–'.$f12($insPH+1);
$insPDL = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$insPD]??'—';
$insPML = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][$insPM]??'—';
arsort($insResMap);
$insTopRes    = (string)(array_key_first($insResMap)??'N/A');
$insTopResCnt = (int)(reset($insResMap)?:0);
arsort($insDateVol);
$insBD  = array_key_first($insDateVol)??null;
$insBDC = (int)(reset($insDateVol)?:0);
$insBDL = $insBD ? date('M j, Y', strtotime($insBD)) : 'N/A';
$insTrP = $insPrev7>0 ? round((($ins7-$insPrev7)/$insPrev7)*100) : ($ins7>0?100:0);
$insTrD = $insTrP>=0?'up':'down';
$insTrC = $insTrD==='up'?'#10b981':'#ef4444';
$insNS  = ($approved??0)>0 ? round((($approved-($claimed??0))/$approved)*100) : 0;
$insDR  = ($total??0)>0    ? round((($declined??0)/$total)*100)               : 0;

$monthlyTotal = $monthlyTotal ?? 0;
$avatarLetter = strtoupper(mb_substr(trim($admin_name ?? 'A'), 0, 1));
?>

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-top">
            <div class="brand-tag">Admin Control Room</div>
            <div class="brand-name">my<em>Space.</em></div>
            <div class="brand-sub">Administration Panel</div>
        </div>

        <div class="user-card">
            <div class="user-avatar"><?= $avatarLetter ?></div>
            <div style="min-width:0;">
                <div class="user-name-txt"><?= htmlspecialchars($admin_name ?? 'Administrator') ?></div>
                <div class="user-role-txt">System Admin</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $hasPendingBadge = ($item['key']==='manage-reservations' && ($pending??0)>0);
                $hasBorrowBadge  = ($item['key']==='books' && $pendingBorrowings>0);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                    <div class="nav-icon">
                        <i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem;"></i>
                    </div>
                    <?= $item['label'] ?>
                    <?php if ($hasPendingBadge): ?>
                        <span class="nav-badge"><?= $pending ?></span>
                    <?php elseif ($hasBorrowBadge): ?>
                        <span class="nav-badge"><?= $pendingBorrowings ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08);">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171;"></i>
                </div>
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
            $hasBadge = ($item['key']==='manage-reservations' && ($pending??0)>0) ||
                        ($item['key']==='books' && $pendingBorrowings>0);
            $badgeCount = $item['key']==='manage-reservations' ? ($pending??0) : $pendingBorrowings;
        ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= $item['label'] ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.05rem;"></i>
                <?php if ($hasBadge): ?><span class="mob-badge"><?= $badgeCount > 9 ? '9+' : $badgeCount ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item" title="Sign Out" style="color:#f87171;">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.05rem;"></i>
        </a>
    </div>
</nav>

<!-- ════════ MODALS ════════ -->
<div id="dateModal" class="modal-back" onclick="if(event.target===this)closeDateModal()">
    <div class="modal-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div>
                <h3 style="font-family:var(--font);font-size:16px;font-weight:700;color:#0f172a;" id="modalDateTitle"></h3>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px;" id="modalDateSub"></p>
            </div>
            <button onclick="closeDateModal()" style="width:36px;height:36px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-xmark" style="font-size:.8rem;"></i>
            </button>
        </div>
        <div id="modalList"></div>
        <div id="modalEmpty" class="hidden" style="text-align:center;padding:24px 12px;">
            <i class="fa-regular fa-calendar-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
            <p style="font-size:12px;color:#94a3b8;">No reservations for this date.</p>
        </div>
        <button onclick="closeDateModal()" style="margin-top:16px;width:100%;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:600;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);">Close</button>
    </div>
</div>

<!-- Print Modal -->
<div id="tl-print-modal" class="print-modal-back" onclick="if(event.target===this)tlClosePrintModal()">
    <div class="print-modal-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div class="card-icon" style="background:#eef2ff;flex-shrink:0;">
                <i class="fa-solid fa-print" style="color:var(--indigo);font-size:.9rem;"></i>
            </div>
            <div>
                <h3 style="font-weight:800;color:#0f172a;font-size:.95rem;" id="tl-modal-title">Session Ended</h3>
                <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;" id="tl-modal-sub">Did this user print?</p>
            </div>
        </div>
        <p style="font-size:.62rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;">Print status</p>
        <div class="tl-print-toggle" style="margin-bottom:14px;">
            <button id="tl-yes-btn" class="active" onclick="tlSetPrinted(true)"><i class="fa-solid fa-check" style="margin-right:4px;font-size:.75rem;"></i> Yes, printed</button>
            <button id="tl-no-btn" onclick="tlSetPrinted(false)"><i class="fa-solid fa-xmark" style="margin-right:4px;font-size:.75rem;"></i> No print</button>
        </div>
        <div id="tl-page-section">
            <p style="font-size:.62rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;">Pages printed</p>
            <div class="tl-page-counter">
                <button onclick="tlAdjustPages(-1)"><i class="fa-solid fa-minus" style="font-size:.7rem;"></i></button>
                <span class="tl-page-num" id="tl-page-num">1</span>
                <button onclick="tlAdjustPages(1)"><i class="fa-solid fa-plus" style="font-size:.7rem;"></i></button>
            </div>
        </div>
        <button class="tl-save-btn" id="tl-save-btn" onclick="tlSavePrint()">
            <i class="fa-solid fa-floppy-disk" style="margin-right:8px;"></i> Save &amp; Log
        </button>
        <button class="tl-skip-btn" onclick="tlSkipPrint()">Skip — don't log</button>
    </div>
</div>

<div id="notifDD" class="notif-dd">
    <div style="padding:11px 13px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-weight:700;font-size:13px;color:#0f172a;">Notifications</span>
        <button onclick="markAllRead()" style="font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
    </div>
    <div id="notifList" style="max-height:300px;overflow-y:auto;"></div>
</div>

<div id="tl-toast-container"></div>

<!-- ════════ MAIN ════════ -->
<main class="main-area">

    <!-- TOPBAR -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow"><?php $hh=(int)date('H'); echo $hh<12?'Good morning':($hh<17?'Good afternoon':'Good evening'); ?>, <?= htmlspecialchars($admin_name ?? 'Administrator') ?></div>
            <div class="greeting-name">Admin Dashboard</div>
            <div class="greeting-date">
                <span><?= date('l, F j, Y') ?></span>
                <span class="sync-badge"><i class="fa-solid fa-shield-halved" style="font-size:.55rem;"></i> Control Room</span>
            </div>
        </div>
        <div class="topbar-right">
            <?php if (($pending??0)>0): ?>
                <a href="/admin/manage-reservations?status=pending" class="pending-pill">
                    <i class="fa-solid fa-clock" style="font-size:.75rem;"></i>
                    <?= $pending ?> pending
                </a>
            <?php endif; ?>
            <?php if ($pendingBorrowings>0): ?>
                <a href="/admin/books#borrowings" class="borrow-pill">
                    <i class="fa-solid fa-book" style="font-size:.75rem;"></i>
                    <?= $pendingBorrowings ?> borrow<?= $pendingBorrowings!=1?'s':'' ?>
                </a>
            <?php endif; ?>
            <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
            </div>
            <div class="notif-bell" onclick="toggleNotifications()">
                <div class="icon-btn"><i class="fa-regular fa-bell" style="font-size:.9rem;"></i></div>
                <span class="notif-badge-dot" id="notifBadge" style="display:none;">0</span>
            </div>
            <a href="/admin/new-reservation" style="display:flex;align-items:center;gap:7px;padding:10px 18px;background:var(--indigo);color:#fff;border-radius:var(--r-sm);font-size:.85rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);text-decoration:none;box-shadow:0 4px 12px rgba(55,48,163,.28);">
                <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> Reserve
            </a>
        </div>
    </div>

    <!-- ── SECTION 1: LIVE SESSIONS ── -->
    <p class="section-label fade-up-1">Live Monitor <span class="sync-badge" style="margin-left:6px;">All Users</span></p>
    <div class="card card-p fade-up-1" style="margin-bottom:20px;">
        <div class="card-head">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="card-icon" style="background:#eef2ff;">
                    <i class="fa-solid fa-stopwatch" style="color:var(--indigo);font-size:.9rem;"></i>
                </div>
                <div>
                    <div class="card-title">Active Sessions</div>
                    <div class="card-sub">System-wide · Real-time</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:#94a3b8;"><span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;"></span>Active</span>
                <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:#94a3b8;"><span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>Warning</span>
                <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:#94a3b8;"><span style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;"></span>Critical</span>
            </div>
        </div>
        <div id="tl-sessions-grid" class="grid-four" style="margin-bottom:0;"></div>
        <p id="tl-no-sessions" class="hidden" style="text-align:center;font-size:.85rem;color:#94a3b8;padding:24px 0;font-weight:500;">
            <i class="fa-regular fa-circle-pause" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>No active sessions right now
        </p>
    </div>

    <!-- ── SECTION 2: RESERVATION OVERVIEW ── -->
    <p class="section-label fade-up-2">Reservation Overview <span class="sync-badge" style="margin-left:6px;">System-wide</span></p>

    <div class="stats-grid fade-up-2">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#eef2ff;"><i class="fa-solid fa-layer-group" style="color:var(--indigo);font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:var(--indigo);">+<?= $monthlyTotal ?> mo</span>
            </div>
            <div class="stat-lbl">Total</div>
            <div class="stat-num"><?= $total??0 ?></div>
            <div class="stat-hint">Avg <strong style="color:var(--indigo);"><?= ($total??0)>0?round(($total??0)/30,1):0 ?>/day</strong></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#dcfce7;"><i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:#16a34a;"><?= $approvalRate ?>%</span>
            </div>
            <div class="stat-lbl">Approved</div>
            <div class="stat-num" style="color:#16a34a;"><?= $approved??0 ?></div>
            <div class="prog-bar" style="margin-top:8px;"><div class="prog-fill" style="width:<?= $approvalRate ?>%;background:#16a34a;"></div></div>
            <div class="stat-hint" style="margin-top:4px;">Approval rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#fef3c7;"><i class="fa-regular fa-clock" style="color:#d97706;font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:#d97706;"><?= $todayTotal??0 ?> today</span>
            </div>
            <div class="stat-lbl" style="margin-bottom:8px;">Today</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:4px;text-align:center;">
                <div><div style="font-size:1.3rem;font-weight:800;color:#d97706;font-family:var(--mono);"><?= $todayPending??0 ?></div><div style="font-size:.6rem;color:#94a3b8;font-weight:700;">Pending</div></div>
                <div><div style="font-size:1.3rem;font-weight:800;color:#16a34a;font-family:var(--mono);"><?= $todayApproved??0 ?></div><div style="font-size:.6rem;color:#94a3b8;font-weight:700;">Approved</div></div>
                <div><div style="font-size:1.3rem;font-weight:800;color:#7c3aed;font-family:var(--mono);"><?= $todayClaimed??0 ?></div><div style="font-size:.6rem;color:#94a3b8;font-weight:700;">Claimed</div></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#ede9fe;"><i class="fa-solid fa-check-double" style="color:#7c3aed;font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:#7c3aed;"><?= $utilizationRate ?>%</span>
            </div>
            <div class="stat-lbl">Claimed</div>
            <div class="stat-num" style="color:#7c3aed;"><?= $claimed??0 ?></div>
            <div class="prog-bar" style="margin-top:8px;"><div class="prog-fill" style="width:<?= $utilizationRate ?>%;background:#7c3aed;"></div></div>
            <div class="stat-hint" style="margin-top:4px;">Utilization rate</div>
        </div>
    </div>

    <div class="kpi-grid fade-up-2">
        <?php foreach ([
            ['Total',    $total??0,    'border-color:#3730a3', 'color:#3730a3', 'fa-layer-group'],
            ['Pending',  $pending??0,  'border-color:#d97706', 'color:#d97706', 'fa-clock'],
            ['Approved', $approved??0, 'border-color:#16a34a', 'color:#16a34a', 'fa-circle-check'],
            ['Declined', $declined??0, 'border-color:#ef4444', 'color:#ef4444', 'fa-xmark-circle'],
        ] as [$l,$v,$bc,$c,$i]): ?>
            <div class="kpi-card" style="<?= $bc ?>;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#94a3b8;"><?= $l ?></span>
                    <i class="fa-solid <?= $i ?>" style="font-size:.85rem;<?= $c ?>;"></i>
                </div>
                <div class="kpi-num" style="<?= $c ?>"><?= $v ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ── Charts ── -->
    <div class="grid-two fade-up-3" style="margin-bottom:20px;">
        <div class="card card-p">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#eef2ff;"><i class="fa-solid fa-chart-line" style="color:var(--indigo);font-size:.9rem;"></i></div>
                    <div>
                        <div class="card-title">Reservations Trend</div>
                        <div class="card-sub">Last 7 days · All users</div>
                    </div>
                </div>
                <span style="font-size:.65rem;font-weight:700;background:#eef2ff;color:var(--indigo);padding:4px 10px;border-radius:999px;">System-wide</span>
            </div>
            <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="card card-p">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#ede9fe;"><i class="fa-solid fa-chart-pie" style="color:#7c3aed;font-size:.9rem;"></i></div>
                    <div>
                        <div class="card-title">Popular Resources</div>
                        <div class="card-sub">Most reserved · All users</div>
                    </div>
                </div>
                <span style="font-size:.65rem;font-weight:700;background:#ede9fe;color:#7c3aed;padding:4px 10px;border-radius:999px;">Top 5</span>
            </div>
            <div class="resource-chart-wrap">
                <canvas id="resourceChart" class="resource-chart-canvas"></canvas>
                <div id="resourceLegend" style="flex:1;min-width:0;display:flex;flex-direction:column;gap:8px;"></div>
            </div>
        </div>
    </div>

    <!-- ── SECTION 3: SCHEDULE & MANAGEMENT ── -->
    <p class="section-label fade-up-3">Schedule &amp; Management</p>
    <div class="grid-main fade-up-3">
        <div class="card card-p-lg">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#eef2ff;"><i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.9rem;"></i></div>
                    <div>
                        <div class="card-title">Reservation Calendar</div>
                        <div class="card-sub">All users · Tap date to view</div>
                    </div>
                </div>
                <div class="cal-legend">
                    <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                        <div class="leg-item"><div class="leg-dot" style="background:<?= $c ?>;"></div><span class="leg-lbl"><?= $l ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <div class="side-col">
            <!-- System Stats banner -->
            <div style="background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%);border-radius:var(--r-lg);padding:18px;overflow:hidden;position:relative;">
                <div style="position:absolute;inset:0;background:url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'20\' cy=\'20\' r=\'18\' fill=\'none\' stroke=\'rgba(255,255,255,.05)\' stroke-width=\'1\'/%3E%3C/svg%3E') repeat;opacity:.4;pointer-events:none;"></div>
                <div style="position:relative;z-index:1;">
                    <div style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:10px;display:flex;align-items:center;gap:6px;"><i class="fa-solid fa-bolt" style="font-size:.6rem;color:#a5b4fc;"></i>System Stats</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <?php foreach ([
                            ['Approval',    $approvalRate.'%',    'fa-chart-line'],
                            ['Utilization', $utilizationRate.'%', 'fa-chart-pie'],
                            ['Resources',   $totalResources??0,   'fa-desktop'],
                            ['Users',       $totalUsers??0,       'fa-users'],
                        ] as [$l,$v,$ic]): ?>
                            <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:10px;border:1px solid rgba(255,255,255,.08);">
                                <div style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.55);margin-bottom:3px;display:flex;align-items:center;gap:4px;"><i class="fa-solid <?= $ic ?>" style="font-size:.55rem;color:#a5b4fc;"></i><?= $l ?></div>
                                <div style="font-size:1.3rem;font-weight:800;color:white;font-family:var(--mono);"><?= $v ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card card-p">
                <div class="section-lbl" style="margin-bottom:12px;">Quick Actions</div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <a href="/admin/new-reservation" class="qa-link">
                        <div class="qa-icon" style="background:#eef2ff;"><i class="fa-solid fa-plus" style="color:var(--indigo);font-size:.85rem;"></i></div>
                        New Reservation
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/admin/manage-reservations" class="qa-link">
                        <div class="qa-icon" style="background:#ede9fe;"><i class="fa-solid fa-calendar-alt" style="color:#7c3aed;font-size:.85rem;"></i></div>
                        All Reservations
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/admin/manage-pcs" class="qa-link">
                        <div class="qa-icon" style="background:#fef3c7;"><i class="fa-solid fa-desktop" style="color:#d97706;font-size:.85rem;"></i></div>
                        Manage PCs
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/admin/manage-sk" class="qa-link">
                        <div class="qa-icon" style="background:#dcfce7;"><i class="fa-solid fa-user-shield" style="color:#16a34a;font-size:.85rem;"></i></div>
                        Manage SK Officers
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/admin/scanner" class="qa-link">
                        <div class="qa-icon" style="background:#f3e8ff;"><i class="fa-solid fa-qrcode" style="color:#9333ea;font-size:.85rem;"></i></div>
                        QR Scanner
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                </div>
            </div>

            <!-- Needs Approval -->
            <div class="card card-p" style="flex:1;">
                <div class="card-head" style="margin-bottom:10px;">
                    <div class="section-lbl" style="margin-bottom:0;">Needs Approval</div>
                    <?php if(($pending??0)>0): ?>
                        <a href="/admin/manage-reservations?status=pending" class="link-sm">View all →</a>
                    <?php endif; ?>
                </div>
                <?php $pl=array_filter($reservations??[],fn($r)=>($r['status']??'')==='pending');
                if(!empty($pl)): foreach(array_slice($pl,0,4) as $res): ?>
                    <a href="/admin/manage-reservations?id=<?= $res['id'] ?>" class="bk-row">
                        <?php if(!empty($res['reservation_date'])): $dt=new DateTime($res['reservation_date']); ?>
                        <div class="bk-date">
                            <div class="bk-month"><?= $dt->format('M') ?></div>
                            <div class="bk-day"><?= $dt->format('j') ?></div>
                        </div>
                        <?php else: ?><div style="width:38px;height:38px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-desktop" style="color:#94a3b8;font-size:.75rem;"></i></div><?php endif; ?>
                        <div style="flex:1;min-width:0;">
                            <div class="bk-name"><?= htmlspecialchars($res['resource_name']??'Resource') ?></div>
                            <div class="bk-time"><?= htmlspecialchars($res['visitor_name']??'Guest') ?> · <?= !empty($res['start_time'])?date('g:i A',strtotime($res['start_time'])):'—' ?></div>
                        </div>
                        <span class="tag tag-pending">Pending</span>
                    </a>
                <?php endforeach;
                $pc=count($pl);if($pc>4): ?><div style="text-align:center;padding:6px;"><a href="/admin/manage-reservations?status=pending" style="font-size:.75rem;font-weight:700;color:var(--indigo);">+<?= $pc-4 ?> more →</a></div><?php endif;
                else: ?>
                    <div style="text-align:center;padding:20px 12px;">
                        <i class="fa-regular fa-circle-check" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:12px;color:#94a3b8;">All caught up!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── SECTION 4: LIBRARY ── -->
    <p class="section-label fade-up-4">
        Library
        <span style="margin-left:auto;">
            <a href="/admin/books" style="font-size:.65rem;font-weight:700;color:var(--indigo);text-decoration:none;letter-spacing:.05em;text-transform:uppercase;">Browse All →</a>
        </span>
    </p>
    <div class="grid-lib fade-up-4">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Library banner -->
            <div class="lib-banner">
                <div style="position:relative;z-index:1;">
                    <div style="font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:4px;">Book Collection</div>
                    <div style="font-size:1.8rem;font-weight:800;color:white;letter-spacing:-.04em;line-height:1.1;"><?= $bookAvailCount ?> <span style="font-size:.9rem;font-weight:500;color:rgba(255,255,255,.55);">available</span></div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.45);margin-top:3px;margin-bottom:16px;"><?= $bookTotalCount ?> total titles</div>
                    <div style="display:flex;gap:8px;">
                        <div class="lib-stat-item">
                            <div class="lib-stat-lbl">Borrow Reqs</div>
                            <div class="lib-stat-val"><?= $pendingBorrowings ?></div>
                        </div>
                        <?php $bpct=$bookTotalCount>0?round($bookAvailCount/$bookTotalCount*100):0; ?>
                        <div class="lib-stat-item">
                            <div class="lib-stat-lbl">In Stock</div>
                            <div class="lib-stat-val"><?= $bpct ?>%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrow Requests -->
            <div class="card card-p" style="flex:1;">
                <div class="card-head">
                    <div>
                        <div class="card-title">Borrow Requests</div>
                        <div class="card-sub">Pending approval</div>
                    </div>
                    <?php if($pendingBorrowings>0): ?>
                        <a href="/admin/books#borrowings" class="link-sm">All <?= $pendingBorrowings ?> →</a>
                    <?php endif; ?>
                </div>
                <?php $sr=array_slice(array_values(array_filter($dashBorrowReqs,fn($b)=>($b['status']??'')==='pending')),0,4);
                if(!empty($sr)): ?>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <?php foreach($sr as $bw): ?>
                            <div class="borrow-req">
                                <div class="book-letter" style="width:32px;height:32px;font-size:.75rem;flex-shrink:0;"><?= mb_strtoupper(mb_substr($bw['book_title']??'B',0,1)) ?></div>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-weight:700;font-size:.8rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($bw['book_title']??'Unknown Book') ?></p>
                                    <p style="font-size:.68rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($bw['resident_name']??'Unknown') ?></p>
                                </div>
                                <div style="display:flex;gap:5px;flex-shrink:0;">
                                    <form method="post" action="/admin/borrowings/approve/<?= $bw['id'] ?>"><?= csrf_field() ?><button type="submit" class="btn-approve" title="Approve">✓</button></form>
                                    <form method="post" action="/admin/borrowings/reject/<?= $bw['id'] ?>"><?= csrf_field() ?><button type="submit" class="btn-reject" title="Reject">✕</button></form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align:center;padding:20px 12px;">
                        <i class="fa-regular fa-circle-check" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:12px;color:#94a3b8;">No pending requests</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Books catalog -->
        <div class="card card-p-lg">
            <div class="card-head">
                <div>
                    <div class="card-title">Books Catalog</div>
                    <div class="card-sub">Availability at a glance</div>
                </div>
                <a href="/admin/books" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.75rem;font-weight:700;text-decoration:none;transition:background var(--ease);"><i class="fa-solid fa-plus" style="font-size:.7rem;"></i> Add Book</a>
            </div>
            <?php if(!empty($dashBooks)):
                $gc=['fiction'=>'#3730a3','fantasy'=>'#7c3aed','poetry'=>'#ec4899','humor'=>'#f59e0b','history'=>'#64748b','science'=>'#06b6d4','romance'=>'#f43f5e'];
                // Header row
            ?>
                <div style="display:grid;grid-template-columns:1fr auto;gap:8px;padding:0 6px 8px;border-bottom:1px solid #f1f5f9;margin-bottom:4px;">
                    <span style="font-size:.6rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#94a3b8;">Title / Author</span>
                    <span style="font-size:.6rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#94a3b8;">Stock</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <?php foreach(array_slice($dashBooks,0,10) as $book):
                        $g=$book['genre']??''; $sc=$gc[strtolower($g)]??'#3730a3';
                        $av=(int)($book['available_copies']??0);
                        $tc=(int)($book['total_copies']??1);
                        $ac=$av===0?'avail-off':($av<=1?'avail-low':'avail-on');
                        $at=$av===0?'Out':($av<=1?'1 left':$av.' left');
                    ?>
                        <a href="/admin/books" class="book-row">
                            <div class="book-spine" style="background:<?= $sc ?>"></div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:.82rem;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($book['title']) ?></div>
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($book['author']??'—') ?> <?= !empty($book['genre'])? '· '.htmlspecialchars($book['genre']) :'' ?></div>
                            </div>
                            <span class="avail-pill <?= $ac ?>"><?= $at ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php if(count($dashBooks)>10): ?>
                    <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9;text-align:center;">
                        <a href="/admin/books" style="font-size:.75rem;font-weight:700;color:var(--indigo);">+<?= count($dashBooks)-10 ?> more books →</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="text-align:center;padding:32px 12px;">
                    <i class="fa-solid fa-book-open" style="font-size:2rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                    <p style="font-size:.82rem;color:#94a3b8;">No books yet</p>
                    <a href="/admin/books" style="display:inline-flex;align-items:center;gap:6px;margin-top:12px;padding:8px 16px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.8rem;font-weight:700;text-decoration:none;"><i class="fa-solid fa-plus" style="font-size:.7rem;"></i> Add the first book</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── SECTION 5: INSIGHTS ── -->
    <p class="section-label fade-up-4">
        Insights
        <span style="margin-left:auto;font-size:.65rem;font-weight:700;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:3px 10px;border-radius:999px;">
            <i class="fa-solid fa-sparkles" style="font-size:.55rem;"></i> Auto-generated
        </span>
    </p>

    <div class="grid-four fade-up-4">
        <div class="insight-mini" data-emoji="⏰">
            <div class="card-icon" style="background:#fef3c7;margin-bottom:10px;"><i class="fa-solid fa-sun" style="color:#d97706;font-size:.85rem;"></i></div>
            <div class="stat-lbl">Peak Hour</div>
            <div style="font-size:1rem;font-weight:800;color:#0f172a;margin-top:4px;line-height:1.3;"><?= htmlspecialchars($insPHL) ?></div>
            <div style="font-size:.68rem;color:#94a3b8;margin-top:4px;">Busiest window</div>
            <div class="prog-bar" style="margin-top:10px;"><div class="prog-fill" style="width:<?= max(array_values($insHourArr))>0?min(100,round($insHourArr[$insPH]/max(array_values($insHourArr))*100)):0 ?>%;background:#f59e0b;"></div></div>
        </div>
        <div class="insight-mini" data-emoji="📅">
            <div class="card-icon" style="background:#eef2ff;margin-bottom:10px;"><i class="fa-solid fa-calendar-week" style="color:var(--indigo);font-size:.85rem;"></i></div>
            <div class="stat-lbl">Busiest Day</div>
            <div style="font-size:1rem;font-weight:800;color:#0f172a;margin-top:4px;"><?= htmlspecialchars($insPDL) ?></div>
            <div style="font-size:.68rem;color:#94a3b8;margin-top:4px;">Most bookings</div>
            <div id="ins-dow-mini" style="display:flex;gap:2px;margin-top:10px;align-items:flex-end;height:20px;"></div>
        </div>
        <div class="insight-mini" data-emoji="🖥️">
            <div class="card-icon" style="background:#dcfce7;margin-bottom:10px;"><i class="fa-solid fa-fire" style="color:#16a34a;font-size:.85rem;"></i></div>
            <div class="stat-lbl">Most Wanted</div>
            <div style="font-size:.9rem;font-weight:800;color:#0f172a;margin-top:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($insTopRes) ?></div>
            <div style="font-size:.68rem;color:#94a3b8;margin-top:4px;"><?= $insTopResCnt ?> reservations</div>
            <div style="margin-top:10px;"><span style="font-size:.6rem;font-weight:700;background:#dcfce7;color:#166534;padding:2px 8px;border-radius:999px;"><i class="fa-solid fa-arrow-trend-up" style="font-size:.55rem;"></i> High demand</span></div>
        </div>
        <div class="insight-mini" data-emoji="📈">
            <div class="card-icon" style="background:#ede9fe;margin-bottom:10px;"><i class="fa-solid fa-chart-line" style="color:#7c3aed;font-size:.85rem;"></i></div>
            <div class="stat-lbl">WoW Trend</div>
            <div style="font-size:1.1rem;font-weight:800;margin-top:4px;color:<?= $insTrC ?>;"><?= ($insTrD==='up'?'+':'').$insTrP ?>%</div>
            <div style="font-size:.68rem;color:#94a3b8;margin-top:4px;">vs prev 7 days</div>
            <div class="prog-bar" style="margin-top:10px;"><div class="prog-fill" style="width:<?= min(abs($insTrP),100) ?>%;background:<?= $insTrC ?>;"></div></div>
        </div>
    </div>

    <div class="grid-three fade-up-4">
        <div class="card card-p">
            <div class="card-head">
                <div>
                    <div class="card-title">Hourly Activity Heatmap</div>
                    <div class="card-sub">Booking density by hour</div>
                </div>
                <span style="font-size:.65rem;font-weight:700;background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:999px;border:1px solid #fde68a;">Demand Map</span>
            </div>
            <div id="ins-heatmap" style="display:grid;grid-template-columns:repeat(12,1fr);gap:4px;"></div>
            <div style="display:flex;justify-content:space-between;margin-top:6px;padding:0 2px;">
                <span style="font-size:.6rem;color:#94a3b8;font-weight:600;">12 AM</span>
                <span style="font-size:.6rem;color:#94a3b8;font-weight:600;">12 PM</span>
                <span style="font-size:.6rem;color:#94a3b8;font-weight:600;">11 PM</span>
            </div>
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <div class="stat-lbl" style="margin-bottom:10px;">Day-of-Week Volume</div>
                <div id="ins-dow-bars" style="display:flex;gap:6px;align-items:flex-end;height:56px;"></div>
                <div id="ins-dow-labels" style="display:flex;gap:6px;margin-top:6px;"></div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card card-p">
                <div class="card-title" style="margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#f87171;font-size:.85rem;"></i> Health Indicators
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;"><span style="font-weight:600;color:#475569;">No-show rate</span><span style="font-weight:700;color:#dc2626;"><?= $insNS ?>%</span></div>
                        <div class="prog-bar"><div class="prog-fill" style="width:<?= $insNS ?>%;background:#f87171;"></div></div>
                        <p style="font-size:.65rem;color:#94a3b8;margin-top:3px;">Approved but never claimed</p>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;"><span style="font-weight:600;color:#475569;">Decline rate</span><span style="font-weight:700;color:#d97706;"><?= $insDR ?>%</span></div>
                        <div class="prog-bar"><div class="prog-fill" style="width:<?= $insDR ?>%;background:#f59e0b;"></div></div>
                        <p style="font-size:.65rem;color:#94a3b8;margin-top:3px;">Of all reservations rejected</p>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;"><span style="font-weight:600;color:#475569;">Claim rate</span><span style="font-weight:700;color:#16a34a;"><?= $utilizationRate ?>%</span></div>
                        <div class="prog-bar"><div class="prog-fill" style="width:<?= $utilizationRate ?>%;background:#10b981;"></div></div>
                        <p style="font-size:.65rem;color:#94a3b8;margin-top:3px;">Approved slots used</p>
                    </div>
                </div>
            </div>
            <div class="card card-p">
                <div class="card-title" style="margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-crown" style="color:#f59e0b;font-size:.85rem;"></i> Record Day
                </div>
                <div style="font-size:2rem;font-weight:800;color:#0f172a;font-family:var(--mono);"><?= $insBDC ?></div>
                <div style="font-size:.82rem;color:#475569;font-weight:600;"><?= htmlspecialchars($insBDL) ?></div>
                <div style="font-size:.7rem;color:#94a3b8;margin-top:4px;">Most reservations in a single day</div>
            </div>
            <div style="border-radius:var(--r-md);padding:14px 16px;border:1px solid var(--indigo-border);background:var(--indigo-light);">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div class="card-icon" style="background:rgba(55,48,163,.12);flex-shrink:0;"><i class="fa-solid fa-lightbulb" style="color:var(--indigo);font-size:.85rem;"></i></div>
                    <div>
                        <p style="font-size:.75rem;font-weight:800;color:#312e81;margin-bottom:5px;">Smart Suggestion</p>
                        <p style="font-size:.78rem;color:#3730a3;line-height:1.65;font-weight:500;" id="ins-suggestion">Analyzing patterns…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-two fade-up-4" style="margin-bottom:0;">
        <div class="card card-p">
            <div class="card-head">
                <div>
                    <div class="card-title">Monthly Seasonality</div>
                    <div class="card-sub">Volume by calendar month</div>
                </div>
                <span style="font-size:.65rem;font-weight:700;background:#eef2ff;color:var(--indigo);padding:4px 10px;border-radius:999px;border:1px solid var(--indigo-border);">Peak: <?= htmlspecialchars($insPML) ?></span>
            </div>
            <div class="chart-wrap" style="height:150px;"><canvas id="ins-month-chart"></canvas></div>
        </div>
        <div class="card card-p">
            <div class="card-head">
                <div>
                    <div class="card-title">Resource Demand Ranking</div>
                    <div class="card-sub">All-time count per resource</div>
                </div>
                <span style="font-size:.65rem;font-weight:700;background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;border:1px solid #bbf7d0;">All Time</span>
            </div>
            <div id="ins-resource-ranking" style="display:flex;flex-direction:column;gap:8px;"></div>
        </div>
    </div>

</main>

<script>
const allRes = <?= json_encode($reservations ?? []) ?>;
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const PRINT_EP = '/admin/log-print';
const INS = {
    hourArr:      <?= json_encode(array_values($insHourArr)) ?>,
    dowArr:       <?= json_encode(array_values($insDowArr)) ?>,
    monthArr:     <?= json_encode(array_values($insMonArr)) ?>,
    peakHourIdx:  <?= (int)$insPH ?>,
    peakDowIdx:   <?= (int)$insPD ?>,
    peakMonthIdx: <?= (int)$insPM ?>,
    noShowRate:   <?= (int)$insNS ?>,
    declineRate:  <?= (int)$insDR ?>,
    trendPct:     <?= (int)$insTrP ?>,
    trendDir:     '<?= $insTrD ?>',
    topResource:  <?= json_encode($insTopRes) ?>,
    peakDayLabel: <?= json_encode($insPDL) ?>,
    resourceMap:  <?= json_encode($insResMap) ?>,
    totalCount:   <?= (int)($total ?? 0) ?>
};
const clamp = (v,lo,hi) => Math.max(lo,Math.min(hi,v));
const pct   = (v,max)   => max>0 ? clamp(Math.round(v/max*100),0,100) : 0;
const timeAgo = t => { const s=Math.floor((Date.now()-new Date(t))/1000); if(s<60)return 'Just now'; if(s<3600)return `${Math.floor(s/60)}m ago`; if(s<86400)return `${Math.floor(s/3600)}h ago`; return `${Math.floor(s/86400)}d ago`; };
const isMobile = () => window.innerWidth < 640;
function isClaimed(r){ return [true,1,'t','true','1'].includes(r.claimed); }

/* Dark mode */
function toggleDark(){
    const isDark = document.body.classList.toggle('dark');
    document.getElementById('darkIcon').innerHTML = isDark
        ? '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>'
        : '<i class="fa-regular fa-sun" style="font-size:.85rem;"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
    updateChartsForTheme(isDark);
}
function initDarkMode(){
    if(localStorage.getItem('admin_theme')==='dark'){
        document.body.classList.add('dark');
        const icon = document.getElementById('darkIcon');
        if(icon) icon.innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    }
    document.documentElement.classList.remove('dark-pre');
}

/* Date modal */
function openDateModal(dateStr, list){
    const fmt = new Date(dateStr+'T00:00:00').toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
    document.getElementById('modalDateTitle').textContent = fmt;
    document.getElementById('modalDateSub').textContent = list?.length ? `${list.length} reservation${list.length>1?'s':''}` : '';
    const c = document.getElementById('modalList'), empty = document.getElementById('modalEmpty');
    c.innerHTML = '';
    if(!list?.length){ empty.classList.remove('hidden'); }
    else {
        empty.classList.add('hidden');
        [...list].sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).forEach(r=>{
            const st=isClaimed(r)?'claimed':(r.status||'pending');
            const clr={approved:'background:#dcfce7;color:#166534',pending:'background:#fef3c7;color:#92400e',declined:'background:#fee2e2;color:#991b1b',claimed:'background:#ede9fe;color:#5b21b6'};
            const t=r.start_time?r.start_time.slice(0,5):'—',et=r.end_time?r.end_time.slice(0,5):'';
            const row=document.createElement('div');
            row.className='date-row';
            row.onclick=()=>location=`/admin/manage-reservations?id=${r.id}`;
            row.innerHTML=`<div style="width:30px;height:30px;background:#eef2ff;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-desktop" style="font-size:.7rem;color:#3730a3;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:600;font-size:.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Resource'}</p><p style="font-size:.7rem;color:#94a3b8;">${r.visitor_name||r.full_name||'Guest'} · ${t}${et?'–'+et:''}</p></div><span style="padding:2px 8px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase;${clr[st]||'background:#f1f5f9;color:#64748b'};flex-shrink:0;">${st}</span>`;
            c.appendChild(row);
        });
    }
    document.getElementById('dateModal').classList.add('show');
    document.body.style.overflow='hidden';
}
function closeDateModal(){ document.getElementById('dateModal').classList.remove('show'); document.body.style.overflow=''; }
document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ closeDateModal(); tlClosePrintModal(); } });

/* Notifications */
let readIds=JSON.parse(localStorage.getItem('admin_read_notifs')||'[]'), notifs=[];
function loadNotifications(){
    allRes.filter(r=>r.status==='pending'&&!readIds.includes(String(r.id))).slice(0,10).forEach(r=>notifs.push({id:r.id,msg:`${r.visitor_name||'User'} → ${r.resource_name||'Resource'}`,time:r.created_at||new Date().toISOString()}));
    updateNotifBadge(); renderNotifs();
}
function markAllRead(){ notifs.forEach(n=>{if(!readIds.includes(String(n.id)))readIds.push(String(n.id));}); notifs=[]; localStorage.setItem('admin_read_notifs',JSON.stringify(readIds)); updateNotifBadge(); renderNotifs(); }
function updateNotifBadge(){ const b=document.getElementById('notifBadge'),n=notifs.length; b.style.display=n>0?'block':'none'; b.textContent=n>9?'9+':n; }
function renderNotifs(){
    const l=document.getElementById('notifList');
    if(!notifs.length){ l.innerHTML=`<div style="text-align:center;padding:24px;"><i class="fa-regular fa-bell-slash" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i><p style="font-size:.78rem;color:#94a3b8;">No notifications</p></div>`; return; }
    l.innerHTML=notifs.map(n=>`<div class="notif-item unread" onclick="location='/admin/manage-reservations?id=${n.id}'"><div style="display:flex;align-items:flex-start;gap:9px;"><div style="width:30px;height:30px;background:#fef3c7;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-clock" style="font-size:.7rem;color:#d97706;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.78rem;color:#0f172a;">New Pending Request</p><p style="font-size:.68rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p><p style="font-size:.62rem;color:#94a3b8;margin-top:2px;">${timeAgo(n.time)}</p></div><span style="width:7px;height:7px;border-radius:50%;background:var(--indigo);flex-shrink:0;margin-top:4px;"></span></div></div>`).join('');
}
function toggleNotifications(){ document.getElementById('notifDD').classList.toggle('show'); }
document.addEventListener('click',e=>{ const dd=document.getElementById('notifDD'),bell=document.querySelector('.notif-bell'); if(!bell?.contains(e.target)&&!dd?.contains(e.target)) dd?.classList.remove('show'); });

/* Print modal */
const TL_LOGGED_KEY='tl_admin_print_logged';
let tlSessions={}, tlPrintQueue=[], tlCurrentPrint=null, tlPageCount=1, tlPrinted=true;
function tlGetLogged(){ try{ return JSON.parse(localStorage.getItem(TL_LOGGED_KEY)||'[]'); }catch(e){ return[]; } }
function tlMarkLogged(id){ const ids=tlGetLogged(); if(!ids.includes(id)){ ids.push(id); localStorage.setItem(TL_LOGGED_KEY,JSON.stringify(ids.slice(-500))); } }
function tlIsLogged(id){ return tlGetLogged().includes(id); }
function tlOpenPrintModal(r){ tlCurrentPrint=r; tlPageCount=1; tlPrinted=true; document.getElementById('tl-modal-title').textContent=r.visitor_name||r.full_name||'User'; document.getElementById('tl-modal-sub').textContent=`${r.resource_name||'Resource'} · Session ended`; document.getElementById('tl-page-num').textContent='1'; document.getElementById('tl-page-section').style.display='block'; document.getElementById('tl-yes-btn').classList.add('active'); document.getElementById('tl-no-btn').classList.remove('active'); document.getElementById('tl-print-modal').classList.add('show'); document.body.style.overflow='hidden'; }
function tlSetPrinted(v){ tlPrinted=v; document.getElementById('tl-yes-btn').classList.toggle('active',v); document.getElementById('tl-no-btn').classList.toggle('active',!v); document.getElementById('tl-page-section').style.display=v?'block':'none'; }
function tlAdjustPages(d){ tlPageCount=Math.max(1,Math.min(999,tlPageCount+d)); document.getElementById('tl-page-num').textContent=tlPageCount; }
async function tlSavePrint(){ if(!tlCurrentPrint)return; const btn=document.getElementById('tl-save-btn'); btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin" style="margin-right:8px;"></i>Saving…'; try{ const r=await fetch(PRINT_EP,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN},body:JSON.stringify({reservation_id:tlCurrentPrint.id,printed:tlPrinted,pages:tlPrinted?tlPageCount:0})}); if(r.ok)tlMarkLogged(tlCurrentPrint.id); }catch(e){ console.error(e); } btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-floppy-disk" style="margin-right:8px;"></i>Save & Log'; tlClosePrintModal(); tlNextPrintModal(); }
function tlSkipPrint(){ if(tlCurrentPrint)tlMarkLogged(tlCurrentPrint.id); tlClosePrintModal(); tlNextPrintModal(); }
function tlClosePrintModal(){ document.getElementById('tl-print-modal').classList.remove('show'); document.body.style.overflow=''; tlCurrentPrint=null; }
function tlNextPrintModal(){ if(tlPrintQueue.length>0)setTimeout(()=>tlOpenPrintModal(tlPrintQueue.shift()),400); }

/* Live sessions */
const TL_WARN=5*60*1000, TL_CRIT=2*60*1000;
function tlGetActiveSessions(){
    const today=new Date().toISOString().split('T')[0],nowMs=Date.now();
    return allRes.filter(r=>{ if(!r.start_time||!r.end_time||!r.reservation_date||r.reservation_date!==today)return false; if((r.status||'').toLowerCase()!=='approved')return false; if(!isClaimed(r))return false; const s=new Date(r.reservation_date+'T'+r.start_time).getTime(),e=new Date(r.reservation_date+'T'+r.end_time).getTime(); return s<=nowMs&&e>=nowMs; });
}
const tlFmt=ms=>{ if(ms<=0)return 'Ended'; const s=Math.floor(ms/1000),m=Math.floor(s/60),h=Math.floor(m/60); if(h>0)return `${h}h ${m%60}m`; if(m>0)return `${m}m ${s%60}s`; return `${s}s`; };
const tlState=ms=>ms<=0?'tl-ended':ms<=TL_CRIT?'tl-critical':ms<=TL_WARN?'tl-warning':'tl-ok';
function tlToast(type,title,sub){ const c=document.getElementById('tl-toast-container'),t=document.createElement('div'); t.className='tl-toast'; const ic=type==='warning'?'fa-triangle-exclamation':'fa-clock-rotate-left'; const bg=type==='warning'?'rgba(245,158,11,.2)':'rgba(239,68,68,.2)'; t.innerHTML=`<div class="tl-toast-icon" style="background:${bg};"><i class="fa-solid ${ic}" style="color:${type==='warning'?'#f59e0b':'#ef4444'};font-size:.8rem;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.75rem;color:white;">${title}</p><p style="font-size:.68rem;color:#94a3b8;margin-top:2px;">${sub}</p></div><button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:.75rem;flex-shrink:0;"><i class="fa-solid fa-xmark"></i></button>`; c.appendChild(t); setTimeout(()=>{t.classList.add('dismissing');setTimeout(()=>t.remove(),220);},7000); }

function tlRender(){
    const sessions=tlGetActiveSessions(),grid=document.getElementById('tl-sessions-grid'),noS=document.getElementById('tl-no-sessions'),nowMs=Date.now();
    if(!sessions.length){ grid.innerHTML=''; noS.classList.remove('hidden'); return; }
    noS.classList.add('hidden');
    sessions.forEach(r=>{
        const eMs=new Date(r.reservation_date+'T'+r.end_time).getTime(),sMs=new Date(r.reservation_date+'T'+r.start_time).getTime(),totMs=eMs-sMs,remMs=eMs-nowMs,elMs=nowMs-sMs;
        const prog=Math.min(100,Math.max(0,(elMs/totMs)*100)),state=tlState(remMs),name=r.visitor_name||r.full_name||'Guest',res=r.resource_name||'Resource';
        if(!tlSessions[r.id])tlSessions[r.id]={warned:false,expired:false};
        const s=tlSessions[r.id];
        if(!s.warned&&remMs>0&&remMs<=TL_WARN){s.warned=true;tlToast('warning',`${name} — 5 min left`,`${res} ending soon`);}
        if(!s.expired&&remMs<=0){s.expired=true;tlToast('expired',`${name}'s session ended`,`${res} time limit reached`);if(!tlIsLogged(r.id)){if(!tlCurrentPrint)setTimeout(()=>tlOpenPrintModal(r),1200);else tlPrintQueue.push(r);}}
        let card=document.getElementById(`tl-card-${r.id}`);
        if(!card){card=document.createElement('div');card.id=`tl-card-${r.id}`;grid.appendChild(card);}
        const sf=r.start_time?r.start_time.substring(0,5):'–',ef=r.end_time?r.end_time.substring(0,5):'–',usedMin=Math.max(0,Math.floor(elMs/60000)),logged=tlIsLogged(r.id);
        card.className=`tl-session-card ${state}`;
        card.innerHTML=`<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:8px;"><div style="min-width:0;flex:1;"><p style="font-weight:700;font-size:.82rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</p><p style="font-size:.68rem;color:#94a3b8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${res}</p></div><span class="tl-countdown"><i class="fa-regular fa-clock" style="font-size:.6rem;"></i>${tlFmt(remMs)}</span></div><div class="tl-prog-track"><div class="tl-prog-fill" style="width:${prog}%"></div></div><div style="display:flex;justify-content:space-between;margin-top:7px;"><span style="font-size:.65rem;color:#94a3b8;font-family:var(--mono);">${sf}–${ef}</span><span style="font-size:.65rem;font-weight:600;color:#64748b;">${usedMin}m used</span></div>${logged&&remMs<=0?'<div style="margin-top:6px;display:flex;align-items:center;gap:4px;font-size:.65rem;font-weight:700;color:#16a34a;"><i class="fa-solid fa-check" style="font-size:.6rem;"></i>Logged</div>':''}`;
    });
    const ids=sessions.map(r=>`tl-card-${r.id}`);
    Array.from(grid.children).forEach(c=>{if(!ids.includes(c.id))c.remove();});
}

/* Chart instances */
let trendChartInst=null, monthChartInst=null;
function getChartColors(isDark){ return { grid: isDark ? '#101e35' : '#f1f5f9', tick: isDark ? '#4a6fa5' : '#94a3b8' }; }
function updateChartsForTheme(isDark){ const c=getChartColors(isDark); [trendChartInst,monthChartInst].forEach(chart=>{ if(!chart)return; chart.options.scales.x.grid.color=c.grid; chart.options.scales.x.ticks.color=c.tick; chart.options.scales.y.grid.color=c.grid; chart.options.scales.y.ticks.color=c.tick; chart.update('none'); }); }

document.addEventListener('DOMContentLoaded',()=>{
    initDarkMode();
    tlRender(); setInterval(tlRender,1000);
    loadNotifications();

    const mob = isMobile();
    const isDark = document.body.classList.contains('dark');
    const chartFont = { family:'Plus Jakarta Sans', size: mob?9:11 };
    const cc = getChartColors(isDark);

    /* Trend Chart */
    const tCtx=document.getElementById('trendChart')?.getContext('2d');
    if(tCtx){ trendChartInst=new Chart(tCtx,{type:'line',data:{labels:<?= json_encode($chartLabels??['Mon','Tue','Wed','Thu','Fri','Sat','Sun']) ?>,datasets:[{data:<?= json_encode($chartData??[0,0,0,0,0,0,0]) ?>,borderColor:'#3730a3',backgroundColor:'rgba(55,48,163,0.07)',borderWidth:2.5,tension:0.4,fill:true,pointBackgroundColor:'#3730a3',pointRadius:mob?3:4,pointHoverRadius:mob?5:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0f172a',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10}},scales:{x:{grid:{display:false},ticks:{font:chartFont,color:cc.tick}},y:{grid:{color:cc.grid},ticks:{font:chartFont,color:cc.tick,stepSize:1},beginAtZero:true}}}}); }

    /* Resource donut */
    const rCtx=document.getElementById('resourceChart')?.getContext('2d');
    const rL=<?= json_encode($resourceLabels??['No Data']) ?>,rD=<?= json_encode($resourceData??[1]) ?>,pal=['#3730a3','#7c3aed','#16a34a','#d97706','#ec4899'];
    if(rCtx){ new Chart(rCtx,{type:'doughnut',data:{labels:rL,datasets:[{data:rD,backgroundColor:pal,borderWidth:0,hoverOffset:4}]},options:{responsive:false,animation:false,cutout:'65%',plugins:{legend:{display:false},tooltip:{backgroundColor:'#0f172a',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10}}}}); const leg=document.getElementById('resourceLegend'); if(leg)leg.innerHTML=rL.map((l,i)=>`<div style="display:flex;align-items:center;gap:8px;min-width:0;"><span style="width:9px;height:9px;border-radius:50%;background:${pal[i]||'#94a3b8'};flex-shrink:0;"></span><span style="font-size:.78rem;color:#475569;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;font-weight:500;">${l}</span><span style="font-size:.78rem;font-weight:800;color:#0f172a;flex-shrink:0;">${rD[i]}</span></div>`).join(''); }

    /* Calendar */
    const byDate={};
    allRes.forEach(r=>{ if(!r.reservation_date)return; (byDate[r.reservation_date]=byDate[r.reservation_date]||[]).push(r); });
    const events=allRes.filter(r=>r.reservation_date).map(r=>{ const st=isClaimed(r)?'claimed':(r.status||'pending'); const clr={approved:'#10b981',pending:'#fbbf24',declined:'#f87171',claimed:'#a855f7'}; return{title:(r.visitor_name||r.full_name||'Guest')+' · '+(r.resource_name||'Res'),start:r.reservation_date+(r.start_time?'T'+r.start_time:''),end:r.reservation_date+(r.end_time?'T'+r.end_time:''),backgroundColor:clr[st]||'#94a3b8',borderColor:'transparent',textColor:'#fff'}; });
    new FullCalendar.Calendar(document.getElementById('calendar'),{
        initialView:'dayGridMonth',headerToolbar:{left:'prev,next',center:'title',right:'today'},events,height:mob?260:380,eventDisplay:'block',eventMaxStack:mob?1:2,
        dateClick:info=>openDateModal(info.dateStr,byDate[info.dateStr]||[]),
        eventClick:info=>openDateModal(info.event.startStr.split('T')[0],byDate[info.event.startStr.split('T')[0]]||[]),
        dayCellDidMount:info=>{ const d=info.date.toISOString().split('T')[0],cnt=(byDate[d]||[]).length; if(cnt){const b=document.createElement('div');b.style.cssText='font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;font-family:var(--mono);';b.textContent=cnt;info.el.querySelector('.fc-daygrid-day-top')?.appendChild(b);} }
    }).render();

    /* Insights */
    (function(){
        const DOW=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],MONTH=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const{hourArr,dowArr,monthArr,peakHourIdx,peakDowIdx,peakMonthIdx,noShowRate,declineRate,trendPct,trendDir,topResource,peakDayLabel,resourceMap,totalCount}=INS;
        const maxH=Math.max(...hourArr,1),maxD=Math.max(...dowArr,1);

        const sg=document.getElementById('ins-suggestion');
        if(sg){ let t=''; if(noShowRate>30)t=`High no-show rate (${noShowRate}%). Consider sending session reminders.`; else if(declineRate>25)t=`Decline rate elevated (${declineRate}%). Review approval rules or add more resources.`; else if(trendDir==='up'&&trendPct>20)t=`Reservations up ${trendPct}% this week — keep "${topResource}" available.`; else if(trendDir==='down'&&Math.abs(trendPct)>20)t=`Bookings dropped ${Math.abs(trendPct)}% vs last week. Consider community outreach.`; else t=`${peakDayLabel}s are your busiest day. Keep "${topResource}" free and well-resourced.`; sg.textContent=t; }

        const hm=document.getElementById('ins-heatmap');
        if(hm){ hm.innerHTML=''; const f12=h=>{const ap=h<12?'AM':'PM';return `${h%12||12}${ap}`;}; for(let h=0;h<24;h++){ const cell=document.createElement('div'); const alpha=0.06+(pct(hourArr[h],maxH)/100)*0.9; const isPk=h===peakHourIdx; cell.className='ins-heatmap-cell'; cell.style.cssText=`background:rgba(55,48,163,${alpha.toFixed(2)});${isPk?'box-shadow:0 0 0 2px #3730a3;':''}`; cell.title=`${f12(h)}: ${hourArr[h]} reservations`; hm.appendChild(cell); } }

        const be=document.getElementById('ins-dow-bars'),le=document.getElementById('ins-dow-labels');
        if(be&&le){ be.innerHTML=''; le.innerHTML=''; dowArr.forEach((cnt,i)=>{ const bar=document.createElement('div'); bar.style.cssText=`flex:1;border-radius:5px 5px 0 0;background:${i===peakDowIdx?'#3730a3':'#c7d2fe'};height:${Math.max(pct(cnt,maxD),4)}%;min-height:4px;`; bar.title=`${DOW[i]}: ${cnt}`; be.appendChild(bar); const lbl=document.createElement('div'); lbl.style.cssText=`flex:1;text-align:center;font-size:${mob?'8px':'9px'};font-weight:${i===peakDowIdx?'800':'600'};color:${i===peakDowIdx?'#3730a3':'#94a3b8'};`; lbl.textContent=mob?DOW[i][0]:DOW[i].slice(0,3); le.appendChild(lbl); }); }

        const mini=document.getElementById('ins-dow-mini');
        if(mini){ mini.innerHTML=''; dowArr.forEach((cnt,i)=>{ const b=document.createElement('div'); b.style.cssText=`flex:1;border-radius:3px;background:${i===peakDowIdx?'#3730a3':'#c7d2fe'};height:${Math.max(pct(cnt,maxD),10)}%;min-height:3px;`; mini.appendChild(b); }); }

        const mCtx=document.getElementById('ins-month-chart')?.getContext('2d');
        if(mCtx){ monthChartInst=new Chart(mCtx,{type:'bar',data:{labels:MONTH,datasets:[{data:monthArr,backgroundColor:monthArr.map((_,i)=>i===peakMonthIdx?'#3730a3':'rgba(55,48,163,.15)'),borderRadius:5,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0f172a',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10,callbacks:{label:ctx=>` ${ctx.raw} reservations`}}},scales:{x:{grid:{display:false},ticks:{font:{family:'Plus Jakarta Sans',size:mob?8:10},color:cc.tick}},y:{grid:{color:cc.grid},beginAtZero:true,ticks:{font:{family:'Plus Jakarta Sans',size:mob?8:10},color:cc.tick,stepSize:1}}}}}); }

        const rk=document.getElementById('ins-resource-ranking');
        if(rk){ const entries=Object.entries(resourceMap).sort((a,b)=>b[1]-a[1]),topMax=entries[0]?.[1]||1,colors=['#3730a3','#d97706','#7c3aed','#16a34a','#ec4899','#06b6d4','#f87171']; rk.innerHTML=!entries.length?'<p style="font-size:.75rem;color:#94a3b8;text-align:center;padding:16px;">No data yet</p>':entries.slice(0,7).map(([name,cnt],i)=>{ const w=pct(cnt,topMax),c=colors[i]||'#94a3b8',share=totalCount>0?Math.round(cnt/totalCount*100):0; return `<div><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;gap:8px;"><div style="display:flex;align-items:center;gap:8px;min-width:0;"><span style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:white;background:${c};flex-shrink:0;">${i+1}</span><span style="font-size:.78rem;font-weight:600;color:#475569;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</span></div><div style="display:flex;align-items:center;gap:8px;flex-shrink:0;"><span style="font-size:.65rem;color:#94a3b8;">${share}%</span><span style="font-size:.78rem;font-weight:800;color:#0f172a;">${cnt}</span></div></div><div class="prog-bar"><div class="prog-fill" style="width:${w}%;background:${c};"></div></div></div>`; }).join(''); }
    })();
});
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>