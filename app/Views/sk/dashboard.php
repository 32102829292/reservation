<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | SK Officer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        (function(){
            if(localStorage.getItem('sk_theme')==='dark') document.documentElement.classList.add('dark-pre');
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

        /* ── Sidebar ── */
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

        .quota-wrap { margin: 8px 12px; background: #f8fafc; border-radius: var(--r-sm); padding: 12px 14px; border: 1px solid rgba(99,102,241,.09); }
        .quota-track { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .quota-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--indigo), #818cf8); transition: width .6s cubic-bezier(.34,1.56,.64,1); }

        .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07); }
        .logout-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all var(--ease); }
        .logout-link:hover { background: #fef2f2; color: #dc2626; }
        .logout-link:hover .nav-icon { background: #fee2e2; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { display: none; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; background: white; border-top: 1px solid rgba(99,102,241,.1); height: var(--mob-nav-total); z-index: 200; box-shadow: 0 -4px 20px rgba(55,48,163,.1); }
        .mobile-scroll-container { display: flex; justify-content: space-evenly; align-items: center; height: var(--mob-nav-h); width: 100%; }
        .mob-nav-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 48px; border-radius: 14px; cursor: pointer; text-decoration: none; color: #64748b; position: relative; transition: background .15s, color .15s; font-size: .82rem; touch-action: manipulation; }
        .mob-nav-item:hover { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active::after { content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; background: var(--indigo); border-radius: 50%; }
        .mob-badge { position: absolute; top: 6px; right: 20%; background: #ef4444; color: white; font-size: .5rem; font-weight: 700; width: 14px; height: 14px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; pointer-events: none; }
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

        /* ── Main area ── */
        .main-area { flex: 1; min-width: 0; padding: 24px 28px 40px; height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; overscroll-behavior-y: contain; }
        @media(max-width:1023px) { .main-area { scrollbar-width: none; } .main-area::-webkit-scrollbar { display: none; } }
        @media(min-width:1024px) { .main-area::-webkit-scrollbar { width: 4px; } .main-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; } }
        @media(max-width:639px) { .main-area { padding: 14px 12px 0; } }

        /* ── Topbar ── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .greeting-name { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .greeting-date { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; flex-wrap: wrap; }
        @media(max-width:639px) { .greeting-name { font-size: 1.35rem; } }

        .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); touch-action: manipulation; }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }
        .reserve-btn { display: flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--indigo); color: #fff; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); text-decoration: none; box-shadow: 0 4px 12px rgba(55,48,163,.28); touch-action: manipulation; }
        .reserve-btn:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(55,48,163,.35); }
        .pending-pill { display: flex; align-items: center; gap: 6px; background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 8px 14px; border-radius: var(--r-sm); font-size: .78rem; font-weight: 700; text-decoration: none; transition: all var(--ease); }
        .pending-pill:hover { background: #fde68a; }
        .notif-bell { position: relative; }
        .notif-badge-dot { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: .55rem; font-weight: 700; padding: 2px 5px; border-radius: 999px; min-width: 17px; text-align: center; border: 2px solid var(--bg); line-height: 1.3; pointer-events: none; }

        /* ── Sync badge ── */
        .sync-badge { display: inline-flex; align-items: center; gap: 4px; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; white-space: nowrap; }

        /* ── Section label ── FIX: was .section-label but CSS only had .section-lbl */
        .section-label,
        .section-lbl {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-label::before,
        .section-lbl::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 14px;
            border-radius: 2px;
            background: var(--indigo);
            flex-shrink: 0;
        }

        /* ── Cards ── */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }
        .card-p-lg { padding: 22px 24px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .9rem; }
        .card-title { font-size: .9rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
        .card-sub { font-size: .7rem; color: #94a3b8; margin-top: 2px; }
        .link-sm { font-size: .65rem; font-weight: 700; color: var(--indigo); text-decoration: none; letter-spacing: .05em; text-transform: uppercase; transition: opacity .15s; touch-action: manipulation; }
        .link-sm:hover { opacity: .7; }

        /* ── Stat cards ── */
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

        /* ── KPI row ── */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 18px; }
        .kpi-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-md); padding: 14px 16px; border-left-width: 4px; box-shadow: var(--shadow-sm); transition: transform var(--ease); }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .kpi-num { font-size: 1.6rem; font-weight: 800; font-family: var(--mono); line-height: 1; margin-top: 6px; }
        @media(max-width:639px) { .kpi-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }

        /* ── Progress bars ── */
        .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; transition: width .8s cubic-bezier(.34,1.56,.64,1); }

        /* ── Chart ── */
        .chart-wrap { position: relative; height: 200px; width: 100%; }
        @media(max-width:639px) { .chart-wrap { height: 160px; } }
        .resource-chart-wrap { display: flex; align-items: center; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
        .resource-chart-canvas { width: 150px !important; height: 150px !important; flex-shrink: 0; }

        /* ── Grid layouts ── */
        .grid-main { display: grid; grid-template-columns: minmax(0,1.9fr) minmax(0,1fr); gap: 16px; margin-bottom: 18px; }
        .side-col { display: flex; flex-direction: column; gap: 14px; }
        @media(max-width:900px) { .grid-main { grid-template-columns: 1fr; } }
        .grid-lib { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1fr); gap: 16px; margin-bottom: 16px; }
        @media(max-width:900px) { .grid-lib { grid-template-columns: 1fr; } }
        .grid-two { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1fr); gap: 14px; margin-bottom: 18px; }
        @media(max-width:639px) { .grid-two { grid-template-columns: 1fr; } }
        .grid-four { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 18px; }
        @media(max-width:900px) { .grid-four { grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media(max-width:480px) { .grid-four { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px; } }
        .grid-three { display: grid; grid-template-columns: minmax(0,1.5fr) minmax(0,1fr); gap: 14px; margin-bottom: 18px; }
        @media(max-width:900px) { .grid-three { grid-template-columns: 1fr; } }

        /* ── Calendar ── */
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

        /* ── Cal legend ── */
        .cal-legend { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .leg-item { display: flex; align-items: center; gap: 5px; }
        .leg-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .leg-lbl { font-size: .68rem; font-weight: 600; color: #94a3b8; }

        /* ── Timer banner ── */
        .timer-banner { border-radius: var(--r-md); padding: 14px 18px; margin-bottom: 18px; border: 1px solid; animation: slideDown .35s cubic-bezier(.34,1.56,.64,1) both; }
        .timer-banner.active   { background: #f0fdf4; border-color: #86efac; color: #14532d; }
        .timer-banner.upcoming { background: var(--indigo-light); border-color: var(--indigo-border); color: #312e81; }
        .timer-pulse { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; flex-shrink: 0; }
        .timer-pulse.pulse { animation: livePulse 1.5s infinite; }
        @keyframes livePulse { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.4);opacity:.6;} }

        /* ── Live sessions ── */
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

        /* ── Toast ── */
        #tl-toast-container { position: fixed; bottom: calc(80px + env(safe-area-inset-bottom,0px)); right: 16px; z-index: 9000; display: flex; flex-direction: column; gap: 8px; pointer-events: none; max-width: 320px; }
        @media(max-width:479px) { #tl-toast-container { left: 12px; right: 12px; max-width: none; } }
        .tl-toast { background: #0f172a; color: white; border-radius: var(--r-md); padding: 12px 14px; box-shadow: var(--shadow-lg); display: flex; align-items: flex-start; gap: 10px; pointer-events: auto; animation: toastIn .3s cubic-bezier(.34,1.56,.64,1) both; }
        .tl-toast.dismissing { animation: toastOut .2s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform: translateX(16px) scale(.96); } to { opacity:1; transform:none; } }
        @keyframes toastOut { to   { opacity:0; transform: translateX(20px) scale(.96); } }
        .tl-toast-icon { width: 30px; height: 30px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .8rem; }

        /* ── Quick actions ── */
        .qa-link { display: flex; align-items: center; gap: 11px; padding: 11px 12px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.09); background: white; text-decoration: none; color: #475569; font-size: .83rem; font-weight: 600; transition: all var(--ease); touch-action: manipulation; }
        .qa-link:hover { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo); transform: translateX(3px); }
        .qa-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .85rem; }
        .qa-chev { margin-left: auto; color: #cbd5e1; transition: color var(--ease); }
        .qa-link:hover .qa-chev { color: var(--indigo); }

        /* ── Booking rows ── */
        .bk-row { display: flex; align-items: center; gap: 10px; padding: 8px; border-radius: 11px; text-decoration: none; color: inherit; transition: background var(--ease); touch-action: manipulation; }
        .bk-row:hover { background: var(--indigo-light); }
        .bk-date { width: 38px; height: 38px; background: #f8fafc; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(99,102,241,.09); }
        .bk-month { font-size: .55rem; font-weight: 700; text-transform: uppercase; color: #94a3b8; }
        .bk-day   { font-size: .95rem; font-weight: 800; color: #0f172a; line-height: 1; font-family: var(--mono); }
        .bk-name  { font-size: .82rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bk-time  { font-size: .68rem; color: #94a3b8; margin-top: 1px; font-family: var(--mono); }

        /* ── Status tags ── */
        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
        .tag-pending  { background: #fef3c7; color: #92400e; }
        .tag-approved { background: #dcfce7; color: #166534; }
        .tag-claimed  { background: #ede9fe; color: #5b21b6; }
        .tag-declined, .tag-canceled { background: #fee2e2; color: #991b1b; }

        /* ── Library ── */
        .lib-banner { background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%); border-radius: var(--r-lg); padding: 22px; overflow: hidden; position: relative; }
        .lib-stat-item { flex: 1; background: rgba(255,255,255,.1); border-radius: 10px; padding: 8px 10px; border: 1px solid rgba(255,255,255,.1); }
        .lib-stat-lbl { font-size: .52rem; font-weight: 600; color: rgba(255,255,255,.55); text-transform: uppercase; letter-spacing: .06em; }
        .lib-stat-val { font-size: .95rem; font-weight: 800; color: white; font-family: var(--mono); }

        /* ── Book rows ── */
        .book-letter { width: 34px; height: 34px; border-radius: 9px; background: var(--indigo-light); color: var(--indigo); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .8rem; flex-shrink: 0; }
        .avail-pill { font-size: .6rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; flex-shrink: 0; }
        .avail-on  { background: #dcfce7; color: #166634; }
        .avail-off { background: #fee2e2; color: #991b1b; }
        .avail-low { background: #fef3c7; color: #92400e; }

        /* ── AI finder ── */
        .search-input { width: 100%; padding: 11px 12px 11px 34px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .9rem; font-family: var(--font); background: #f8fafc; color: #0f172a; transition: all var(--ease); outline: none; }
        .search-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .find-btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 16px; background: var(--indigo); color: white; border-radius: var(--r-sm); font-size: .8rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); }
        .find-btn:hover { background: #312e81; }
        .ai-shimmer { height: 11px; border-radius: 4px; background: linear-gradient(90deg,#eef2ff 25%,#e0e7ff 50%,#eef2ff 75%); background-size: 200%; animation: shimmer 1.2s infinite; margin-bottom: 6px; }
        /* RAG loading state */
        .rag-loading { display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: #f8fafc; border-radius: 9px; }
        .rag-spinner { width: 16px; height: 16px; border: 2px solid #e0e7ff; border-top-color: var(--indigo); border-radius: 50%; animation: spin .7s linear infinite; flex-shrink: 0; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes shimmer { 0%{background-position:200%}100%{background-position:-200%} }

        /* ── Insights ── */
        .ins-heatmap-cell { height: 28px; border-radius: 5px; cursor: default; transition: transform .15s; position: relative; }
        .ins-heatmap-cell:hover { transform: scaleY(1.1); }
        .insight-mini { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 16px 18px; box-shadow: var(--shadow-sm); overflow: hidden; position: relative; transition: transform var(--ease), box-shadow var(--ease); }
        .insight-mini:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .insight-mini::before { content: attr(data-emoji); position: absolute; right: -8px; top: -8px; font-size: 4rem; opacity: .04; pointer-events: none; line-height: 1; }

        /* ── Date modal ── */
        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 520px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg); }
        .date-row { display: flex; align-items: center; gap: 11px; padding: .75rem; border-bottom: 1px solid #f8fafc; border-radius: 10px; transition: background .15s; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }
        @media(max-width:479px) { .modal-back { padding: .75rem; } .modal-card { padding: 18px 16px; border-radius: var(--r-lg); } }

        /* ── Notif dropdown ── */
        .notif-dd { position: fixed; top: 80px; right: 20px; width: 320px; background: white; border-radius: var(--r-xl); box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.09); z-index: 200; display: none; overflow: hidden; animation: dropIn .15s ease; }
        .notif-dd.show { display: block; }
        .notif-item { padding: .85rem 1.1rem; border-bottom: 1px solid #f8fafc; transition: background .15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: var(--indigo-light); }
        .notif-item:last-child { border-bottom: none; }
        @media(max-width:479px) { .notif-dd { left: 12px; right: 12px; width: auto; top: 72px; } }

        /* ── Login toast ── */
        .login-toast { position: fixed; bottom: calc(var(--mob-nav-total) + 8px); right: 16px; z-index: 400; max-width: 280px; background: #0f172a; border-radius: var(--r-md); padding: 12px 14px; display: flex; align-items: flex-start; gap: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.3); transform: translateY(8px); opacity: 0; pointer-events: none; transition: all .35s cubic-bezier(.34,1.56,.64,1); }
        .login-toast.show { transform: none; opacity: 1; pointer-events: auto; }
        @media(min-width:1024px) { .login-toast { bottom: 24px; } }

        /* ── Animations ── */
        @keyframes fadeIn   { from{opacity:0}to{opacity:1} }
        @keyframes dropIn   { from{opacity:0;transform:translateY(-4px) scale(.98)}to{opacity:1;transform:none} }
        @keyframes slideDown{ from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none} }
        @keyframes slideUp  { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none} }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s ease both; }
        .fade-up-3 { animation: slideUp .45s .15s ease both; }
        .fade-up-4 { animation: slideUp .45s .2s ease both; }

        /* ════════ DARK MODE ════════ */
        body.dark {
            --bg: #060e1e;
            --card: #0b1628;
            --indigo-light: rgba(55,48,163,.12);
            --indigo-border: rgba(99,102,241,.25);
            color: #e2eaf8;
        }
        body.dark .sidebar-inner { background: #0b1628; border-color: rgba(99,102,241,.12); }
        body.dark .sidebar-top,
        body.dark .sidebar-footer { border-color: rgba(99,102,241,.1); }
        body.dark .brand-name { color: #e2eaf8; }
        body.dark .brand-tag,
        body.dark .brand-sub { color: #4a6fa5; }
        body.dark .nav-section-lbl { color: #1e3a5f; }
        body.dark .nav-link { color: #7fb3e8; }
        body.dark .nav-link:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .nav-link:not(.active) .nav-icon { background: rgba(99,102,241,.1); }
        body.dark .nav-link:hover:not(.active) .nav-icon { background: rgba(99,102,241,.2); }
        body.dark .user-card { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.2); }
        body.dark .user-name-txt { color: #e2eaf8; }
        body.dark .user-role-txt { color: #7fb3e8; }
        body.dark .quota-wrap { background: rgba(99,102,241,.07); border-color: rgba(99,102,241,.1); }
        body.dark .quota-track { background: rgba(99,102,241,.15); }
        body.dark .logout-link { color: #4a6fa5; }
        body.dark .logout-link:hover { background: rgba(239,68,68,.1); color: #f87171; }
        body.dark .logout-link:hover .nav-icon { background: rgba(239,68,68,.12); }
        body.dark .mobile-nav-pill { background: #0b1628; border-color: rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color: #7fb3e8; }
        body.dark .mob-nav-item.active { background: rgba(99,102,241,.18); }
        body.dark .mob-badge { border-color: #0b1628; }
        body.dark .greeting-eyebrow { color: #1e3a5f; }
        body.dark .greeting-name { color: #e2eaf8; }
        body.dark .greeting-date { color: #4a6fa5; }
        body.dark .sync-badge { background: rgba(29,78,216,.2); color: #7fb3e8; border-color: rgba(59,130,246,.2); }
        body.dark .icon-btn { background: #0b1628; border-color: rgba(99,102,241,.15); color: #7fb3e8; }
        body.dark .icon-btn:hover { background: rgba(99,102,241,.12); border-color: rgba(99,102,241,.3); color: #a5b4fc; }
        body.dark .pending-pill { background: rgba(180,83,9,.2); border-color: rgba(180,83,9,.3); color: #fcd34d; }
        body.dark .notif-badge-dot { border-color: var(--bg); }
        body.dark .card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .card-title { color: #e2eaf8; }
        body.dark .card-sub { color: #4a6fa5; }
        body.dark .section-label,
        body.dark .section-lbl { color: #4a6fa5; }
        body.dark .link-sm { color: #818cf8; }
        body.dark .stat-card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .stat-num { color: #e2eaf8; }
        body.dark .stat-lbl { color: #4a6fa5; }
        body.dark .stat-hint { color: #4a6fa5; }
        body.dark .kpi-card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .prog-bar { background: rgba(99,102,241,.15); }
        body.dark .tl-session-card { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .tl-ended .tl-countdown { background: #1a2a42; color: #7fb3e8; }
        body.dark .tl-prog-track { background: rgba(99,102,241,.15); }
        body.dark .qa-link { background: #0b1628; border-color: rgba(99,102,241,.1); color: #7fb3e8; }
        body.dark .qa-link:hover { background: rgba(99,102,241,.12); border-color: var(--indigo); color: #a5b4fc; }
        body.dark .qa-chev { color: #1e3a5f; }
        body.dark .bk-row:hover { background: rgba(99,102,241,.08); }
        body.dark .bk-date { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .bk-day { color: #e2eaf8; }
        body.dark .bk-name { color: #e2eaf8; }
        body.dark .bk-month,
        body.dark .bk-time { color: #4a6fa5; }
        body.dark .fc-toolbar-title { color: #e2eaf8 !important; }
        body.dark .fc-daygrid-day-number { color: #7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color: #4a6fa5; }
        body.dark .fc-day-today { background: rgba(55,48,163,.15) !important; }
        body.dark .fc-theme-standard td,
        body.dark .fc-theme-standard th,
        body.dark .fc-theme-standard .fc-scrollgrid { border-color: #101e35 !important; }
        body.dark .fc-daygrid-day { background: #0b1628 !important; }
        body.dark .fc-daygrid-day:hover { background-color: rgba(99,102,241,.08) !important; }
        body.dark .modal-card { background: #0b1628; }
        body.dark #modalDateTitle { color: #e2eaf8 !important; }
        body.dark #modalDateSub { color: #4a6fa5 !important; }
        body.dark .date-row { border-color: #101e35; }
        body.dark .date-row:hover { background: #101e35; }
        body.dark .modal-card button:last-child { background: #101e35 !important; color: #7fb3e8 !important; border-color: rgba(99,102,241,.15) !important; }
        body.dark .notif-dd { background: #0b1628; border-color: rgba(99,102,241,.15); box-shadow: 0 20px 48px -8px rgba(0,0,0,.5); }
        body.dark .notif-dd>div:first-child { border-color: #101e35; }
        body.dark .notif-item { border-color: #101e35; }
        body.dark .notif-item.unread { background: rgba(55,48,163,.18); }
        body.dark .notif-item:hover { background: #101e35; }
        body.dark .timer-banner.active   { background: rgba(20,83,45,.25); border-color: rgba(134,239,172,.2); color: #86efac; }
        body.dark .timer-banner.upcoming { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.3); color: #a5b4fc; }
        body.dark .insight-mini { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .search-input { background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8; }
        body.dark .search-input:focus { border-color: #818cf8; background: #0b1628; }
        body.dark .ai-shimmer { background: linear-gradient(90deg,#101e35 25%,#1a2a42 50%,#101e35 75%); }
        body.dark .book-letter { background: rgba(55,48,163,.2); color: #818cf8; }
        body.dark .ins-heatmap-cell { opacity: .85; }
        body.dark .rag-loading { background: #101e35; }
        body.dark .rag-spinner { border-color: #1a2a42; border-top-color: #818cf8; }
    </style>
</head>
<body>

<?php
$page     = $page ?? 'dashboard';

// FIX: Corrected icon classes — profile uses fa-user (solid), not doubled "fa-regular fa-user"
$navItems = [
    ['url'=>'/sk/dashboard',       'icon'=>'fa-house',        'label'=>'Dashboard',        'key'=>'dashboard'],
    ['url'=>'/sk/reservations',    'icon'=>'fa-calendar-alt', 'label'=>'All Reservations', 'key'=>'reservations'],
    ['url'=>'/sk/new-reservation', 'icon'=>'fa-plus',         'label'=>'New Reservation',  'key'=>'new-reservation'],
    ['url'=>'/sk/user-requests',   'icon'=>'fa-users',        'label'=>'User Requests',    'key'=>'user-requests'],
    ['url'=>'/sk/my-reservations', 'icon'=>'fa-calendar',     'label'=>'My Reservations',  'key'=>'my-reservations'],
    ['url'=>'/sk/scanner',         'icon'=>'fa-qrcode',       'label'=>'Scanner',          'key'=>'scanner'],
    ['url'=>'/sk/profile',         'icon'=>'fa-user',         'label'=>'Profile',          'key'=>'profile'],
];

$myRes  = $reservations    ?? [];
$sysRes = $allReservations ?? [];

$sysTotal    = count($sysRes);
$sysPending  = count(array_filter($sysRes, fn($r) => ($r['status']??'') === 'pending'));
$sysApproved = count(array_filter($sysRes, fn($r) => ($r['status']??'') === 'approved'));
$sysDeclined = count(array_filter($sysRes, fn($r) => in_array($r['status']??'', ['declined','canceled'])));
$sysClaimed  = count(array_filter($sysRes, fn($r) => in_array($r['claimed']??false, [true,1,'t','true','1'], true)));

$sysToday         = date('Y-m-d');
$sysTodayAll      = array_filter($sysRes, fn($r) => ($r['reservation_date']??'') === $sysToday);
$sysTodayTotal    = count($sysTodayAll);
$sysTodayApproved = count(array_filter($sysTodayAll, fn($r) => ($r['status']??'') === 'approved'));
$sysTodayPending  = count(array_filter($sysTodayAll, fn($r) => ($r['status']??'') === 'pending'));
$sysTodayClaimed  = count(array_filter($sysTodayAll, fn($r) => in_array($r['claimed']??false, [true,1,'t','true','1'], true)));

$sysApprovalRate    = $sysTotal    > 0 ? round($sysApproved / $sysTotal    * 100) : 0;
$sysUtilizationRate = $sysApproved > 0 ? round($sysClaimed  / $sysApproved * 100) : 0;
$thirtyDaysAgo      = date('Y-m-d', strtotime('-30 days'));
$sysMonthlyTotal    = count(array_filter($sysRes, fn($r) => ($r['reservation_date']??'') >= $thirtyDaysAgo));

$remainingReservations = $remainingReservations ?? 0;
$pendingUserCount      = $pendingUserCount      ?? 0;
$dashBooks             = $dashBooks             ?? [];
$myBorrowings          = $myBorrowings          ?? [];
$availableCount        = $availableCount        ?? 0;
$totalBooks            = $totalBooks            ?? 0;

$usedSlots = (int)($usedThisMonth   ?? 0);
$maxSlots  = (int)($maxMonthlySlots ?? 3);
$maxSlots  = max(1, $maxSlots);
$quotaPct  = min(100, round($usedSlots / $maxSlots * 100));

$insHourArr = array_fill(0,24,0); $insDowArr = array_fill(0,7,0);
$insMonArr  = array_fill(0,12,0); $insResMap = []; $insDateVol = [];
$ins7 = 0; $insPrev7 = 0;
foreach ($sysRes as $r) {
    if (!empty($r['start_time'])) $insHourArr[(int)date('G', strtotime($r['start_time']))]++;
    if (!empty($r['reservation_date'])) {
        $insDowArr[(int)date('w', strtotime($r['reservation_date']))]++;
        $insMonArr[(int)date('n', strtotime($r['reservation_date']))-1]++;
        $insDateVol[$r['reservation_date']] = ($insDateVol[$r['reservation_date']] ?? 0) + 1;
        $d = (int)floor((time()-strtotime($r['reservation_date']))/86400);
        if ($d>=0&&$d<7) $ins7++; if ($d>=7&&$d<14) $insPrev7++;
    }
    $rname = $r['resource_name'] ?? $r['full_name'] ?? 'Unknown';
    $insResMap[$rname] = ($insResMap[$rname] ?? 0) + 1;
}
$insPH = array_search(max($insHourArr), $insHourArr);
$insPD = array_search(max($insDowArr),  $insDowArr);
$insPM = array_search(max($insMonArr),  $insMonArr);
$f12   = fn($h)=>(($h%12)?:12).' '.($h<12?'AM':'PM');
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
$insTrD = $insTrP>=0?'up':'down'; $insTrC = $insTrD==='up'?'#10b981':'#ef4444';
$insNS  = $sysApproved > 0 ? round((($sysApproved - $sysClaimed) / $sysApproved) * 100) : 0;
$insDR  = $sysTotal    > 0 ? round(($sysDeclined / $sysTotal) * 100) : 0;

$chartLabels = []; $chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $chartLabels[] = date('D', strtotime($d));
    $chartData[]   = count(array_filter($sysRes, fn($r) => ($r['reservation_date']??'') === $d));
}
$resourceLabels = []; $resourceData = []; $resCount = [];
foreach ($sysRes as $r) { $rn = $r['resource_name'] ?? 'Unknown'; $resCount[$rn] = ($resCount[$rn] ?? 0) + 1; }
arsort($resCount);
foreach (array_slice($resCount, 0, 5, true) as $rname => $cnt) { $resourceLabels[] = $rname; $resourceData[] = (int)$cnt; }
if (empty($resourceLabels)) { $resourceLabels = ['No Data']; $resourceData = [1]; }

$avatarLetter = strtoupper(mb_substr(trim($user_name ?? 'S'), 0, 1));
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
                <div class="user-name-txt"><?= htmlspecialchars($user_name ?? 'Officer') ?></div>
                <div class="user-role-txt">SK Officer</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $showBadge = ($item['key']==='user-requests' && ($pendingUserCount??0)>0);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                    <div class="nav-icon">
                        <!-- FIX: Use only fa-solid prefix, icon class is just the icon name -->
                        <i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem;"></i>
                    </div>
                    <?= $item['label'] ?>
                    <?php if ($showBadge): ?>
                        <span class="nav-badge"><?= $pendingUserCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="quota-wrap">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:.7rem;font-weight:600;color:#64748b;">Monthly Quota</span>
                <span style="font-size:.7rem;font-weight:700;color:var(--indigo);font-family:var(--mono);"><?= $usedSlots ?>/<?= $maxSlots ?></span>
            </div>
            <div class="quota-track">
                <div class="quota-fill" style="width:<?= $quotaPct ?>%;<?= $quotaPct>=100?'background:#ef4444':($quotaPct>=66?'background:linear-gradient(90deg,#f59e0b,#fbbf24)':'') ?>"></div>
            </div>
            <p style="font-size:.7rem;color:#94a3b8;margin-top:5px;<?= $remainingReservations===0?'color:#dc2626;font-weight:700':($remainingReservations===1?'color:#d97706;font-weight:600':'') ?>">
                <?php if ($remainingReservations===0): ?>⚠ No slots left
                <?php elseif ($remainingReservations===1): ?>⚡ 1 slot remaining
                <?php else: ?><?= $remainingReservations ?> slots remaining<?php endif; ?>
            </p>
        </div>

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
            $active    = ($page == $item['key']);
            $showBadge = ($item['key']==='user-requests' && ($pendingUserCount??0)>0);
        ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= $item['label'] ?>">
                <!-- FIX: fa-solid prefix only -->
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem;"></i>
                <?php if ($showBadge): ?><span class="mob-badge"><?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171;"></i>
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

<div id="notifDD" class="notif-dd">
    <div style="padding:11px 13px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-weight:700;font-size:13px;color:#0f172a;">Notifications</span>
        <button onclick="markAllRead()" style="font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
    </div>
    <div id="notifList" style="max-height:280px;overflow-y:auto;"></div>
</div>

<div id="tl-toast-container"></div>

<div id="loginToast" class="login-toast">
    <div id="loginToastIcon" style="width:28px;height:28px;border-radius:8px;background:rgba(99,102,241,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fa-solid fa-hand-wave" style="font-size:.8rem;color:#818cf8;"></i>
    </div>
    <div style="flex:1;min-width:0;">
        <p style="font-weight:700;font-size:12px;color:white;">Welcome back, <?= htmlspecialchars($user_name ?? 'Officer') ?>!</p>
        <p style="font-size:10px;color:rgba(255,255,255,.6);margin-top:2px;"><?= date('l, F j') ?></p>
    </div>
    <button onclick="this.closest('.login-toast').classList.remove('show')" style="background:rgba(255,255,255,.08);border:none;border-radius:6px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;">
        <i class="fa-solid fa-xmark" style="font-size:.65rem;color:white;"></i>
    </button>
</div>

<!-- ════════ MAIN ════════ -->
<main class="main-area">

    <!-- TOPBAR -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow"><?php $hh=(int)date('H'); echo $hh<12?'Good morning':($hh<17?'Good afternoon':'Good evening'); ?>, <?= htmlspecialchars($user_name ?? 'Officer') ?></div>
            <div class="greeting-name">SK Dashboard</div>
            <div class="greeting-date">
                <span><?= date('l, F j, Y') ?></span>
                <span class="sync-badge"><i class="fa-solid fa-rotate" style="font-size:.55rem;"></i> Synced with Admin</span>
            </div>
        </div>
        <div class="topbar-right">
            <?php if (($pendingUserCount??0)>0): ?>
                <a href="/sk/user-requests" class="pending-pill">
                    <i class="fa-solid fa-clock" style="font-size:.75rem;"></i>
                    <?= $pendingUserCount ?> pending
                </a>
            <?php endif; ?>
            <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
            </div>
            <div class="notif-bell" onclick="toggleNotifications()">
                <div class="icon-btn"><i class="fa-regular fa-bell" style="font-size:.9rem;"></i></div>
                <span class="notif-badge-dot" id="notifBadge" style="display:none;">0</span>
            </div>
            <a href="/sk/new-reservation" class="reserve-btn">
                <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> Reserve
            </a>
        </div>
    </div>

    <!-- TIMER BANNER -->
    <?php
    $activeBanner = null; $upcomingBanner = null; $now = time();
    foreach ($myRes as $r) {
        if (empty($r['reservation_date'])||empty($r['start_time'])||empty($r['end_time'])) continue;
        if (($r['status']??'')==='approved' && !($r['claimed']??false)) {
            $s=strtotime($r['reservation_date'].'T'.$r['start_time']);
            $e=strtotime($r['reservation_date'].'T'.$r['end_time']);
            if ($s<=$now&&$e>=$now) { $activeBanner=$r; break; }
            if ($s>$now&&$s<=$now+3600) $upcomingBanner=$r;
        }
    }
    if ($activeBanner||$upcomingBanner): $b=$activeBanner??$upcomingBanner; $isActive=!!$activeBanner;
    ?>
    <div class="timer-banner <?= $isActive?'active':'upcoming' ?>" id="timerBanner"
         data-start="<?= strtotime($b['reservation_date'].'T'.$b['start_time']) ?>"
         data-end="<?= strtotime($b['reservation_date'].'T'.$b['end_time']) ?>"
         data-active="<?= $isActive?'1':'0' ?>">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <div class="timer-pulse <?= $isActive?'pulse':'' ?>"></div>
            <div style="flex:1;min-width:140px;">
                <p style="font-weight:700;font-size:.88rem;"><?= $isActive?'My session in progress':'My session starting soon' ?> · <?= htmlspecialchars($b['resource_name']??'Resource') ?></p>
                <p style="font-size:.72rem;opacity:.7;margin-top:2px;font-family:var(--mono);"><?= date('g:i A', strtotime($b['start_time'])) ?> – <?= date('g:i A', strtotime($b['end_time'])) ?></p>
            </div>
            <span id="timerDisplay" style="font-weight:800;font-family:var(--mono);font-size:1rem;flex-shrink:0;">—</span>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── SECTION 1: LIVE SESSIONS ── -->
    <!-- FIX: was "section-label" but CSS only defined "section-lbl" — now both are defined -->
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
                <span class="stat-badge" style="color:var(--indigo);">+<?= $sysMonthlyTotal ?> mo</span>
            </div>
            <div class="stat-lbl">Total</div>
            <div class="stat-num"><?= $sysTotal ?></div>
            <div class="stat-hint">Avg <strong style="color:var(--indigo);"><?= $sysTotal>0?round($sysTotal/30,1):0 ?>/day</strong></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#dcfce7;"><i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:#16a34a;"><?= $sysApprovalRate ?>%</span>
            </div>
            <div class="stat-lbl">Approved</div>
            <div class="stat-num" style="color:#16a34a;"><?= $sysApproved ?></div>
            <div class="prog-bar" style="margin-top:8px;"><div class="prog-fill" style="width:<?= $sysApprovalRate ?>%;background:#16a34a;"></div></div>
            <div class="stat-hint" style="margin-top:4px;">Approval rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#fef3c7;"><i class="fa-regular fa-clock" style="color:#d97706;font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:#d97706;"><?= $sysTodayTotal ?> today</span>
            </div>
            <div class="stat-lbl" style="margin-bottom:8px;">Today</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:4px;text-align:center;">
                <div><div style="font-size:1.3rem;font-weight:800;color:#d97706;font-family:var(--mono);"><?= $sysTodayPending ?></div><div style="font-size:.6rem;color:#94a3b8;font-weight:700;">Pending</div></div>
                <div><div style="font-size:1.3rem;font-weight:800;color:#16a34a;font-family:var(--mono);"><?= $sysTodayApproved ?></div><div style="font-size:.6rem;color:#94a3b8;font-weight:700;">Approved</div></div>
                <div><div style="font-size:1.3rem;font-weight:800;color:#7c3aed;font-family:var(--mono);"><?= $sysTodayClaimed ?></div><div style="font-size:.6rem;color:#94a3b8;font-weight:700;">Claimed</div></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#ede9fe;"><i class="fa-solid fa-check-double" style="color:#7c3aed;font-size:.9rem;"></i></div>
                <span class="stat-badge" style="color:#7c3aed;"><?= $sysUtilizationRate ?>%</span>
            </div>
            <div class="stat-lbl">Claimed</div>
            <div class="stat-num" style="color:#7c3aed;"><?= $sysClaimed ?></div>
            <div class="prog-bar" style="margin-top:8px;"><div class="prog-fill" style="width:<?= $sysUtilizationRate ?>%;background:#7c3aed;"></div></div>
            <div class="stat-hint" style="margin-top:4px;">Utilization rate</div>
        </div>
    </div>

    <div class="kpi-grid fade-up-2">
        <?php foreach ([
            ['Total',    $sysTotal,             'border-color:#3730a3', 'color:#3730a3',   'fa-layer-group'],
            ['Pending',  $sysPending,            'border-color:#d97706', 'color:#d97706',   'fa-clock'],
            ['Approved', $sysApproved,           'border-color:#16a34a', 'color:#16a34a',   'fa-circle-check'],
            ['My Slots', $remainingReservations, 'border-color:#7c3aed', 'color:#7c3aed',   'fa-hourglass-half'],
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

    <!-- ── SECTION 3: SCHEDULE & ACTIVITY ── -->
    <p class="section-label fade-up-3">Schedule &amp; Activity</p>
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
            <!-- System stats -->
            <div style="background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%);border-radius:var(--r-lg);padding:18px;overflow:hidden;position:relative;">
                <div style="position:absolute;inset:0;background:url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'20\' cy=\'20\' r=\'18\' fill=\'none\' stroke=\'rgba(255,255,255,.05)\' stroke-width=\'1\'/%3E%3C/svg%3E') repeat;opacity:.4;pointer-events:none;"></div>
                <div style="position:relative;z-index:1;">
                    <div style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:10px;display:flex;align-items:center;gap:6px;"><i class="fa-solid fa-bolt" style="font-size:.6rem;color:#a5b4fc;"></i>System Stats</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <?php foreach ([
                            ['Approval',    $sysApprovalRate.'%',    'fa-chart-line'],
                            ['Utilization', $sysUtilizationRate.'%', 'fa-chart-pie'],
                            ['My Slots',    $remainingReservations,  'fa-hourglass-half'],
                            ['Total',       $sysTotal,               'fa-layer-group'],
                        ] as [$l,$v,$ic]): ?>
                            <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:10px;border:1px solid rgba(255,255,255,.08);">
                                <div style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.55);margin-bottom:3px;display:flex;align-items:center;gap:4px;"><i class="fa-solid <?= $ic ?>" style="font-size:.55rem;color:#a5b4fc;"></i><?= $l ?></div>
                                <div style="font-size:1.3rem;font-weight:800;color:white;font-family:var(--mono);"><?= $v ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="card card-p">
                <div class="section-lbl" style="margin-bottom:12px;">Quick Actions</div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <a href="/sk/new-reservation" class="qa-link">
                        <div class="qa-icon" style="background:#eef2ff;"><i class="fa-solid fa-plus" style="color:var(--indigo);font-size:.85rem;"></i></div>
                        New Reservation
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/sk/reservations" class="qa-link">
                        <div class="qa-icon" style="background:#ede9fe;"><i class="fa-solid fa-calendar-alt" style="color:#7c3aed;font-size:.85rem;"></i></div>
                        All Reservations
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/sk/books" class="qa-link">
                        <div class="qa-icon" style="background:#fef3c7;"><i class="fa-solid fa-book-open" style="color:#d97706;font-size:.85rem;"></i></div>
                        Browse Library
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                    <a href="/sk/profile" class="qa-link">
                        <div class="qa-icon" style="background:#f3e8ff;"><i class="fa-solid fa-user" style="color:#9333ea;font-size:.85rem;"></i></div>
                        View Profile
                        <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;"></i>
                    </a>
                </div>
            </div>

            <!-- Recent bookings -->
            <div class="card card-p" style="flex:1;">
                <div class="card-head" style="margin-bottom:10px;">
                    <div class="section-lbl" style="margin-bottom:0;">Recent Bookings</div>
                    <a href="/sk/reservations" class="link-sm">View all →</a>
                </div>
                <?php
                $recentAll = array_slice(array_reverse($sysRes), 0, 5);
                foreach ($recentAll as $r):
                    $isCl = in_array($r['claimed']??false, [true,1,'t','true','1'], true);
                    $st   = $isCl ? 'claimed' : ($r['status']??'pending');
                    $isExpired = !empty($r['reservation_date']) && strtotime($r['reservation_date']) < strtotime('today') && !$isCl;
                    $rName = $r['resource_name'] ?? 'Resource';
                    $vName = $r['visitor_name']  ?? $r['full_name'] ?? 'Guest';
                    if (!empty($r['reservation_date'])) { $dt = new DateTime($r['reservation_date']); }
                ?>
                    <a href="/sk/reservations" class="bk-row" style="<?= $isExpired?'opacity:.55':'' ?>">
                        <?php if (!empty($r['reservation_date'])): ?>
                        <div class="bk-date">
                            <div class="bk-month"><?= $dt->format('M') ?></div>
                            <div class="bk-day"><?= $dt->format('j') ?></div>
                        </div>
                        <?php else: ?><div style="width:38px;height:38px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-desktop" style="color:#94a3b8;font-size:.75rem;"></i></div><?php endif; ?>
                        <div style="flex:1;min-width:0;">
                            <div class="bk-name"><?= htmlspecialchars($rName) ?></div>
                            <div class="bk-time"><?= htmlspecialchars($vName) ?> · <?= !empty($r['reservation_date'])?date('M j',strtotime($r['reservation_date'])):'—' ?></div>
                        </div>
                        <span class="tag tag-<?= $st ?>"><?= ucfirst($st) ?></span>
                    </a>
                <?php endforeach; ?>
                <?php if (empty($recentAll)): ?>
                    <div style="text-align:center;padding:20px 12px;">
                        <i class="fa-regular fa-calendar-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:12px;color:#94a3b8;">No bookings yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── SECTION 4: LIBRARY ── -->
    <p class="section-label fade-up-4">
        Library
        <span style="margin-left:auto;font-size:.65rem;font-weight:700;color:var(--indigo);text-decoration:none;letter-spacing:.05em;text-transform:uppercase;">
            <a href="/sk/books" style="color:var(--indigo);text-decoration:none;">Browse All →</a>
        </span>
    </p>
    <div class="grid-lib fade-up-4">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Library banner -->
            <div class="lib-banner">
                <div style="position:relative;z-index:1;">
                    <div style="font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:4px;">Book Collection</div>
                    <div style="font-size:1.8rem;font-weight:800;color:white;letter-spacing:-.04em;line-height:1.1;"><?= $availableCount ?> <span style="font-size:.9rem;font-weight:500;color:rgba(255,255,255,.55);">available</span></div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.45);margin-top:3px;margin-bottom:16px;"><?= $totalBooks ?> total titles</div>
                    <div style="display:flex;gap:8px;flex-wrap:nowrap;">
                        <div class="lib-stat-item">
                            <div class="lib-stat-lbl">My Borrows</div>
                            <div class="lib-stat-val"><?= count($myBorrowings) ?></div>
                        </div>
                        <div class="lib-stat-item">
                            <?php $bpct=$totalBooks>0?round($availableCount/$totalBooks*100):0; ?>
                            <div class="lib-stat-lbl">In Stock</div>
                            <div class="lib-stat-val"><?= $bpct ?>%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Finder — FIXED: improved UX, error handling, loading states, CSRF -->
            <div class="card card-p">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div class="card-icon" style="background:#ede9fe;"><i class="fa-solid fa-wand-magic-sparkles" style="color:#7c3aed;font-size:.9rem;"></i></div>
                    <div>
                        <div class="card-title">AI Book Finder</div>
                        <div class="card-sub">Powered by RAG</div>
                    </div>
                </div>
                <div style="position:relative;">
                    <span style="position:absolute;left:11px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8;"><i class="fa-solid fa-magnifying-glass" style="font-size:.75rem;"></i></span>
                    <input id="ai-query" type="text" class="search-input" placeholder="What are you looking for?" autocomplete="off"
                           onkeydown="if(event.key==='Enter')aiFind()"
                           oninput="document.getElementById('ai-results').innerHTML=''">
                </div>
                <!-- Loading shimmer -->
                <div id="ai-skel" style="display:none;margin-top:10px;">
                    <div class="rag-loading">
                        <div class="rag-spinner"></div>
                        <span style="font-size:.75rem;color:#94a3b8;font-weight:500;">Searching library…</span>
                    </div>
                </div>
                <!-- Results -->
                <div id="ai-results" style="margin-top:8px;display:flex;flex-direction:column;gap:5px;max-height:160px;overflow-y:auto;"></div>
                <!-- Error -->
                <div id="ai-error" style="display:none;margin-top:8px;padding:10px 12px;background:#fef2f2;border-radius:9px;border:1px solid #fee2e2;">
                    <p style="font-size:.75rem;color:#dc2626;font-weight:600;display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-circle-exclamation" style="font-size:.8rem;"></i>
                        <span id="ai-error-msg">Could not connect to the AI service.</span>
                    </p>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:11px;">
                    <button onclick="aiFind()" class="find-btn" id="ai-btn">
                        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:.75rem;"></i> Find Books
                    </button>
                    <a href="/sk/books" class="link-sm">Full library →</a>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Books catalog -->
            <div class="card card-p-lg" style="flex:1;">
                <div class="card-head">
                    <div>
                        <div class="card-title">Books Catalog</div>
                        <div class="card-sub">Availability at a glance</div>
                    </div>
                    <a href="/sk/books" class="link-sm">Browse All →</a>
                </div>
                <?php if (!empty($dashBooks)):
                    $gc=['fiction'=>'#3730a3','fantasy'=>'#7c3aed','poetry'=>'#ec4899','humor'=>'#f59e0b','history'=>'#64748b','science'=>'#06b6d4','romance'=>'#f43f5e']; ?>
                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <?php foreach (array_slice($dashBooks,0,8) as $book):
                            $g=$book['genre']??''; $sc=$gc[strtolower($g)]??'#3730a3';
                            $av=(int)($book['available_copies']??0);
                            $ac=$av===0?'avail-off':($av<=1?'avail-low':'avail-on');
                            $at=$av===0?'Out':($av<=1?'1 left':$av.' left');
                        ?>
                            <a href="/sk/books" style="display:flex;align-items:center;gap:10px;padding:7px 6px;border-radius:10px;text-decoration:none;color:inherit;transition:background .15s;">
                                <div class="book-letter"><?= mb_strtoupper(mb_substr($book['title'],0,1)) ?></div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.82rem;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($book['title']) ?></div>
                                    <div style="font-size:.7rem;color:#94a3b8;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($book['author']??'—') ?></div>
                                </div>
                                <span class="avail-pill <?= $ac ?>"><?= $at ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if(count($dashBooks)>8): ?>
                        <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9;text-align:center;">
                            <a href="/sk/books" style="font-size:.75rem;font-weight:700;color:var(--indigo);">+<?= count($dashBooks)-8 ?> more books →</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:32px 12px;">
                        <i class="fa-solid fa-book-open" style="font-size:2rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:.82rem;color:#94a3b8;">No books available</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- My borrows -->
            <?php if(!empty($myBorrowings)): ?>
            <div class="card card-p">
                <div class="section-lbl" style="margin-bottom:12px;"><i class="fa-solid fa-book" style="color:#16a34a;font-size:.75rem;"></i> My Active Borrows</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <?php foreach(array_slice($myBorrowings,0,4) as $bw):
                        $due=!empty($bw['due_date'])?strtotime($bw['due_date']):null;
                        $overdue=$due&&$due<time(); $dueSoon=$due&&!$overdue&&$due<time()+3*86400;
                    ?>
                        <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;border-radius:10px;padding:9px 12px;border:1px solid rgba(99,102,241,.07);">
                            <div class="book-letter" style="width:30px;height:30px;font-size:.72rem;"><?= mb_strtoupper(mb_substr($bw['book_title']??'B',0,1)) ?></div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-weight:600;font-size:.8rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($bw['book_title']??'Book') ?></p>
                                <p style="font-size:.68rem;<?= $overdue?'color:#ef4444;font-weight:600':($dueSoon?'color:#d97706;font-weight:600':'color:#94a3b8') ?>;font-family:var(--mono);"><?= $due?($overdue?'Overdue · ':($dueSoon?'Due soon · ':'')).date('M j, Y',$due):'No due date' ?></p>
                            </div>
                            <span style="font-size:.6rem;font-weight:700;text-transform:uppercase;padding:3px 8px;border-radius:999px;white-space:nowrap;<?= $overdue?'background:#fee2e2;color:#991b1b':($dueSoon?'background:#fef3c7;color:#92400e':'background:#dcfce7;color:#166534') ?>"><?= $overdue?'Overdue':($dueSoon?'Due Soon':'Active') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;"><span style="font-weight:600;color:#475569;">Claim rate</span><span style="font-weight:700;color:#16a34a;"><?= $sysUtilizationRate ?>%</span></div>
                        <div class="prog-bar"><div class="prog-fill" style="width:<?= $sysUtilizationRate ?>%;background:#10b981;"></div></div>
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
const allRes    = <?= json_encode($myRes)  ?>;
const allResAll = <?= json_encode($sysRes) ?>;
// FIX: Read CSRF token from meta tag rather than hardcoding it inline
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
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
    totalCount:   <?= (int)$sysTotal ?>
};
const clamp = (v,lo,hi) => Math.max(lo,Math.min(hi,v));
const pct   = (v,max)   => max>0 ? clamp(Math.round(v/max*100),0,100) : 0;
const timeAgo = t => { const s=Math.floor((Date.now()-new Date(t))/1000); if(s<60)return 'Just now'; if(s<3600)return `${Math.floor(s/60)}m ago`; if(s<86400)return `${Math.floor(s/3600)}h ago`; return `${Math.floor(s/86400)}d ago`; };
const isMobile = () => window.innerWidth < 640;

/* ── Dark Mode ── */
function toggleDark(){
    const isDark = document.body.classList.toggle('dark');
    const icon = document.getElementById('darkIcon');
    icon.innerHTML = isDark
        ? '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>'
        : '<i class="fa-regular fa-sun" style="font-size:.85rem;"></i>';
    localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
    updateChartsForTheme(isDark);
}
function initDarkMode(){
    if(localStorage.getItem('sk_theme')==='dark'){
        document.body.classList.add('dark');
        const icon = document.getElementById('darkIcon');
        if(icon) icon.innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    }
    document.documentElement.classList.remove('dark-pre');
}

/* ── Login Toast ── */
(function(){
    const key = 'sk_login_toast_'+new Date().toISOString().split('T')[0];
    if(!sessionStorage.getItem(key)){
        const t = document.getElementById('loginToast');
        setTimeout(()=>t.classList.add('show'),900);
        setTimeout(()=>t.classList.remove('show'),5000);
        sessionStorage.setItem(key,'1');
    }
})();

/* ── Timer Banner ── */
(function(){
    const banner = document.getElementById('timerBanner');
    if(!banner) return;
    const start  = parseInt(banner.dataset.start)*1000;
    const end    = parseInt(banner.dataset.end)*1000;
    const isActive = banner.dataset.active==='1';
    const disp   = document.getElementById('timerDisplay');
    function updateTimer(){
        const now=Date.now(), countdown=isActive?end-now:start-now;
        if(countdown<=0){disp.textContent=isActive?'Ended':'Now';return;}
        const s=Math.floor(countdown/1000),m=Math.floor(s/60),h=Math.floor(m/60);
        disp.textContent=h>0?`${h}h ${m%60}m`:`${m}m ${s%60}s`;
    }
    updateTimer(); setInterval(updateTimer,1000);
})();

/* ── Date Modal ── */
function openDateModal(dateStr, list){
    const fmt = new Date(dateStr+'T00:00:00').toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
    document.getElementById('modalDateTitle').textContent = fmt;
    document.getElementById('modalDateSub').textContent = list?.length ? `${list.length} reservation${list.length>1?'s':''}` : '';
    const c = document.getElementById('modalList');
    const empty = document.getElementById('modalEmpty');
    c.innerHTML = '';
    if(!list?.length){ empty.classList.remove('hidden'); }
    else {
        empty.classList.add('hidden');
        [...list].sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).forEach(r=>{
            const isCl=r.claimed==1||r.claimed===true||r.claimed==='true';
            const st=isCl?'claimed':(r.status||'pending');
            const clr={approved:'background:#dcfce7;color:#166534',pending:'background:#fef3c7;color:#92400e',declined:'background:#fee2e2;color:#991b1b',claimed:'background:#ede9fe;color:#5b21b6'};
            const t=r.start_time?r.start_time.slice(0,5):'—',et=r.end_time?r.end_time.slice(0,5):'';
            const name=r.visitor_name||r.full_name||'Guest';
            const row=document.createElement('div');
            row.className='date-row';
            row.innerHTML=`<div style="width:30px;height:30px;background:#eef2ff;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-desktop" style="font-size:.7rem;color:#3730a3;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:600;font-size:.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Resource'}</p><p style="font-size:.7rem;color:#94a3b8;">${name} · ${t}${et?'–'+et:''}</p></div><span style="padding:2px 8px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase;${clr[st]||'background:#f1f5f9;color:#64748b'};flex-shrink:0;">${st}</span>`;
            c.appendChild(row);
        });
    }
    document.getElementById('dateModal').classList.add('show');
    document.body.style.overflow='hidden';
}
function closeDateModal(){ document.getElementById('dateModal').classList.remove('show'); document.body.style.overflow=''; }
document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeDateModal(); });

/* ── Notifications ── */
const NOTIF_KEY = 'sk_notifs_<?= session()->get('user_id') ?>';
let notifications = [];
function loadNotifications(){
    const seen = JSON.parse(localStorage.getItem(NOTIF_KEY)||'[]');
    notifications = allRes.filter(r=>['approved','declined'].includes(r.status||'')).map(r=>({
        id:parseInt(r.id),title:`Reservation ${r.status==='approved'?'Approved':'Declined'}`,
        msg:`${r.resource_name||'Resource'}`,time:r.updated_at||r.created_at||new Date().toISOString(),
        status:r.status,read:seen.includes(parseInt(r.id))
    }));
    updateNotifBadge(); renderNotifs();
}
function markAllRead(){
    const seen=[...new Set([...JSON.parse(localStorage.getItem(NOTIF_KEY)||'[]'),...notifications.map(n=>n.id)])];
    localStorage.setItem(NOTIF_KEY,JSON.stringify(seen));
    notifications.forEach(n=>n.read=true); updateNotifBadge(); renderNotifs();
}
function updateNotifBadge(){ const badge=document.getElementById('notifBadge'),n=notifications.filter(x=>!x.read).length; badge.style.display=n>0?'block':'none'; badge.textContent=n>9?'9+':n; }
function renderNotifs(){
    const l=document.getElementById('notifList');
    if(!notifications.length){ l.innerHTML=`<div style="text-align:center;padding:24px;"><p style="font-size:.78rem;color:#94a3b8;">All caught up!</p></div>`; return; }
    l.innerHTML=notifications.sort((a,b)=>new Date(b.time)-new Date(a.time)).map(n=>`<div class="notif-item ${!n.read?'unread':''}"><div style="display:flex;align-items:flex-start;gap:9px;"><div style="width:30px;height:30px;background:${n.status==='approved'?'#dcfce7':'#fee2e2'};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid ${n.status==='approved'?'fa-circle-check':'fa-circle-xmark'}" style="font-size:.7rem;color:${n.status==='approved'?'#16a34a':'#dc2626'};"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.78rem;color:#0f172a;">${n.title}</p><p style="font-size:.68rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p><p style="font-size:.62rem;color:#94a3b8;margin-top:2px;">${timeAgo(n.time)}</p></div></div></div>`).join('');
}
function toggleNotifications(){ document.getElementById('notifDD').classList.toggle('show'); }
document.addEventListener('click',e=>{ const dd=document.getElementById('notifDD'),bell=document.querySelector('.notif-bell'); if(!bell.contains(e.target)&&!dd.contains(e.target)) dd.classList.remove('show'); });

/* ── AI Book Finder (RAG) — FIXED ── */
let ragAbortCtrl = null;
async function aiFind(){
    const q = document.getElementById('ai-query').value.trim();
    const results = document.getElementById('ai-results');
    const skel = document.getElementById('ai-skel');
    const errBox = document.getElementById('ai-error');
    const errMsg = document.getElementById('ai-error-msg');
    const btn = document.getElementById('ai-btn');

    if(!q) {
        document.getElementById('ai-query').focus();
        return;
    }

    // Cancel any in-flight request
    if(ragAbortCtrl) ragAbortCtrl.abort();
    ragAbortCtrl = new AbortController();

    // Reset UI
    results.innerHTML = '';
    errBox.style.display = 'none';
    skel.style.display = 'block';
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.75rem;"></i> Searching…';

    try {
        const res = await fetch('/rag/suggest', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ query: q }),
            signal: ragAbortCtrl.signal
        });

        skel.style.display = 'none';

        if(!res.ok) {
            const code = res.status;
            errMsg.textContent = code === 422
                ? 'Please enter a valid search query.'
                : code === 429
                    ? 'Too many requests. Please wait a moment.'
                    : code >= 500
                        ? 'The AI service encountered an error. Try again later.'
                        : `Request failed (${code}).`;
            errBox.style.display = 'block';
            return;
        }

        const d = await res.json();
        const books = d.books ?? d.results ?? d.data ?? [];

        if(books.length) {
            results.innerHTML = books.map(b => {
                const title = b.title ?? b.book_title ?? 'Untitled';
                const author = b.author ?? b.book_author ?? '—';
                const id = b.id ?? '';
                const avail = b.available_copies ?? b.available ?? null;
                const availHtml = avail !== null
                    ? `<span class="avail-pill ${avail > 0 ? 'avail-on' : 'avail-off'}" style="flex-shrink:0;">${avail > 0 ? avail+' left' : 'Out'}</span>`
                    : '';
                return `<a href="/sk/books${id ? '?id='+id : ''}" style="display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:9px;background:#f8fafc;border:1px solid rgba(99,102,241,.08);text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#eef2ff'" onmouseout="this.style.background='#f8fafc'">
                    <div class="book-letter" style="width:28px;height:28px;font-size:.7rem;">${title.charAt(0).toUpperCase()}</div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:.78rem;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${title}</p>
                        <p style="font-size:.65rem;color:#94a3b8;">${author}</p>
                    </div>
                    ${availHtml}
                </a>`;
            }).join('');
        } else {
            results.innerHTML = `<div style="text-align:center;padding:16px 12px;">
                <i class="fa-solid fa-book-open" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:6px;"></i>
                <p style="font-size:.75rem;color:#94a3b8;font-weight:500;">No books matched "<em>${q}</em>"</p>
                <a href="/sk/books" style="font-size:.7rem;color:var(--indigo);font-weight:700;text-decoration:none;margin-top:4px;display:inline-block;">Browse all books →</a>
            </div>`;
        }
    } catch(e) {
        skel.style.display = 'none';
        if(e.name === 'AbortError') return; // user cancelled, ignore
        errMsg.textContent = 'Could not connect to the AI service. Check your connection and try again.';
        errBox.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles" style="font-size:.75rem;"></i> Find Books';
        ragAbortCtrl = null;
    }
}

/* ── Active Sessions ── */
const TL_WARN=5*60*1000, TL_CRIT=2*60*1000;
let tlSessions={};
function tlGetActiveSessions(){
    const today=new Date().toISOString().split('T')[0],nowMs=Date.now();
    return allResAll.filter(r=>{
        if(!r.start_time||!r.end_time||!r.reservation_date||r.reservation_date!==today) return false;
        if((r.status||'').toLowerCase()!=='approved') return false;
        const s=new Date(r.reservation_date+'T'+r.start_time).getTime(),e=new Date(r.reservation_date+'T'+r.end_time).getTime();
        return s<=nowMs&&e>=nowMs;
    });
}
const tlFmt=ms=>{ if(ms<=0)return 'Ended'; const s=Math.floor(ms/1000),m=Math.floor(s/60),h=Math.floor(m/60); if(h>0)return `${h}h ${m%60}m`; if(m>0)return `${m}m ${s%60}s`; return `${s}s`; };
const tlState=ms=>ms<=0?'tl-ended':ms<=TL_CRIT?'tl-critical':ms<=TL_WARN?'tl-warning':'tl-ok';
function tlToast(type,title,sub){
    const c=document.getElementById('tl-toast-container'),t=document.createElement('div');
    t.className='tl-toast';
    const ic=type==='warning'?'fa-triangle-exclamation':'fa-clock-rotate-left';
    const bg=type==='warning'?'rgba(245,158,11,.2)':'rgba(239,68,68,.2)';
    t.innerHTML=`<div class="tl-toast-icon" style="background:${bg};"><i class="fa-solid ${ic}" style="color:${type==='warning'?'#f59e0b':'#ef4444'};font-size:.8rem;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.75rem;color:white;">${title}</p><p style="font-size:.68rem;color:#94a3b8;margin-top:2px;">${sub}</p></div><button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:.75rem;flex-shrink:0;"><i class="fa-solid fa-xmark"></i></button>`;
    c.appendChild(t);
    setTimeout(()=>{t.classList.add('dismissing');setTimeout(()=>t.remove(),220);},7000);
}
function tlRender(){
    const sessions=tlGetActiveSessions(),grid=document.getElementById('tl-sessions-grid'),noS=document.getElementById('tl-no-sessions'),nowMs=Date.now();
    if(!sessions.length){grid.innerHTML='';noS.classList.remove('hidden');return;}
    noS.classList.add('hidden');
    sessions.forEach(r=>{
        const eMs=new Date(r.reservation_date+'T'+r.end_time).getTime(),sMs=new Date(r.reservation_date+'T'+r.start_time).getTime(),totMs=eMs-sMs,remMs=eMs-nowMs,elMs=nowMs-sMs;
        const prog=Math.min(100,Math.max(0,(elMs/totMs)*100)),state=tlState(remMs),name=r.visitor_name||r.full_name||'Guest',res=r.resource_name||'Resource';
        if(!tlSessions[r.id]) tlSessions[r.id]={warned:false,expired:false};
        const s=tlSessions[r.id];
        if(!s.warned&&remMs>0&&remMs<=TL_WARN){s.warned=true;tlToast('warning',`${name} — 5 min left`,`${res} ending soon`);}
        if(!s.expired&&remMs<=0){s.expired=true;tlToast('expired',`${name}'s session ended`,`${res} time limit reached`);}
        let card=document.getElementById(`tl-card-${r.id}`);
        if(!card){card=document.createElement('div');card.id=`tl-card-${r.id}`;grid.appendChild(card);}
        const sf=r.start_time?r.start_time.substring(0,5):'–',ef=r.end_time?r.end_time.substring(0,5):'–',usedMin=Math.max(0,Math.floor(elMs/60000));
        card.className=`tl-session-card ${state}`;
        card.innerHTML=`<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:8px;"><div style="min-width:0;flex:1;"><p style="font-weight:700;font-size:.82rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</p><p style="font-size:.68rem;color:#94a3b8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${res}</p></div><span class="tl-countdown"><i class="fa-regular fa-clock" style="font-size:.6rem;"></i>${tlFmt(remMs)}</span></div><div class="tl-prog-track"><div class="tl-prog-fill" style="width:${prog}%"></div></div><div style="display:flex;justify-content:space-between;margin-top:7px;"><span style="font-size:.65rem;color:#94a3b8;font-family:var(--mono);">${sf}–${ef}</span><span style="font-size:.65rem;font-weight:600;color:#64748b;">${usedMin}m used</span></div>`;
    });
    const ids=sessions.map(r=>`tl-card-${r.id}`);
    Array.from(grid.children).forEach(c=>{if(!ids.includes(c.id))c.remove();});
}

/* ── Chart instances ── */
let trendChartInst = null, resourceChartInst = null, monthChartInst = null;
function getChartColors(isDark){
    return { grid: isDark ? '#101e35' : '#f1f5f9', tick: isDark ? '#4a6fa5' : '#94a3b8' };
}
function updateChartsForTheme(isDark){
    const c = getChartColors(isDark);
    [trendChartInst, monthChartInst].forEach(chart => {
        if(!chart) return;
        chart.options.scales.x.grid.color = c.grid;
        chart.options.scales.x.ticks.color = c.tick;
        chart.options.scales.y.grid.color = c.grid;
        chart.options.scales.y.ticks.color = c.tick;
        chart.update('none');
    });
}

/* ── DOMContentLoaded ── */
document.addEventListener('DOMContentLoaded',()=>{
    initDarkMode();
    tlRender(); setInterval(tlRender,1000);
    loadNotifications();

    const mob = window.innerWidth < 640;
    const isDark = document.body.classList.contains('dark');
    const chartFont = { family:'Plus Jakarta Sans', size: mob?9:11 };
    const cc = getChartColors(isDark);

    /* Trend Chart */
    const tCtx = document.getElementById('trendChart')?.getContext('2d');
    if(tCtx){
        trendChartInst = new Chart(tCtx,{type:'line',data:{labels:<?= json_encode($chartLabels) ?>,datasets:[{data:<?= json_encode($chartData) ?>,borderColor:'#3730a3',backgroundColor:'rgba(55,48,163,0.07)',borderWidth:2.5,tension:0.4,fill:true,pointBackgroundColor:'#3730a3',pointRadius:mob?3:4,pointHoverRadius:mob?5:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0f172a',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10}},scales:{x:{grid:{display:false},ticks:{font:chartFont,color:cc.tick}},y:{grid:{color:cc.grid},ticks:{font:chartFont,color:cc.tick,stepSize:1},beginAtZero:true}}}});
    }

    /* Resource Donut */
    const rCtx = document.getElementById('resourceChart')?.getContext('2d');
    const rL = <?= json_encode($resourceLabels) ?>, rD = <?= json_encode($resourceData) ?>;
    const pal = ['#3730a3','#7c3aed','#16a34a','#d97706','#ec4899'];
    if(rCtx){
        resourceChartInst = new Chart(rCtx,{type:'doughnut',data:{labels:rL,datasets:[{data:rD,backgroundColor:pal,borderWidth:0,hoverOffset:4}]},options:{responsive:false,animation:false,cutout:'65%',plugins:{legend:{display:false},tooltip:{backgroundColor:'#0f172a',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10}}}});
        const leg = document.getElementById('resourceLegend');
        if(leg) leg.innerHTML = rL.map((l,i)=>`<div style="display:flex;align-items:center;gap:8px;min-width:0;"><span style="width:9px;height:9px;border-radius:50%;background:${pal[i]||'#94a3b8'};flex-shrink:0;"></span><span style="font-size:.78rem;color:#475569;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;font-weight:500;">${l}</span><span style="font-size:.78rem;font-weight:800;color:#0f172a;flex-shrink:0;">${rD[i]}</span></div>`).join('');
    }

    /* Calendar */
    const byDate={};
    allResAll.forEach(r=>{ if(!r.reservation_date)return; (byDate[r.reservation_date]=byDate[r.reservation_date]||[]).push(r); });
    const events = allResAll.filter(r=>r.reservation_date).map(r=>{
        const isCl=r.claimed==1||r.claimed===true||r.claimed==='true';
        const st=isCl?'claimed':(r.status||'pending');
        const clr={approved:'#10b981',pending:'#fbbf24',declined:'#f87171',claimed:'#a855f7'};
        return{title:(r.visitor_name||r.full_name||'Guest')+' · '+(r.resource_name||'Res'),start:r.reservation_date+(r.start_time?'T'+r.start_time:''),end:r.reservation_date+(r.end_time?'T'+r.end_time:''),backgroundColor:clr[st]||'#94a3b8',borderColor:'transparent',textColor:'#fff'};
    });
    new FullCalendar.Calendar(document.getElementById('calendar'),{
        initialView:'dayGridMonth',
        headerToolbar:{left:'prev,next',center:'title',right:'today'},
        events,
        height: mob ? 260 : 380,
        eventDisplay:'block',
        eventMaxStack: mob ? 1 : 2,
        dateClick: info => openDateModal(info.dateStr, byDate[info.dateStr]||[]),
        eventClick: info => openDateModal(info.event.startStr.split('T')[0], byDate[info.event.startStr.split('T')[0]]||[]),
        dayCellDidMount: info => {
            const d=info.date.toISOString().split('T')[0], cnt=(byDate[d]||[]).length;
            if(cnt){const b=document.createElement('div');b.style.cssText='font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;font-family:var(--mono);';b.textContent=cnt;info.el.querySelector('.fc-daygrid-day-top')?.appendChild(b);}
        }
    }).render();

    /* ── Insights ── */
    (function(){
        const DOW=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],DOW_S=['S','M','T','W','T','F','S'],MONTH=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const{hourArr,dowArr,monthArr,peakHourIdx,peakDowIdx,peakMonthIdx,noShowRate,declineRate,trendPct,trendDir,topResource,peakDayLabel,resourceMap,totalCount}=INS;
        const maxH=Math.max(...hourArr,1),maxD=Math.max(...dowArr,1);

        /* Suggestion */
        const sg=document.getElementById('ins-suggestion');
        if(sg){let t='';if(noShowRate>30)t=`High no-show rate (${noShowRate}%). Consider sending reminders before sessions.`;else if(declineRate>25)t=`Many requests declined (${declineRate}%). Book "${topResource}" earlier for better chances.`;else if(trendDir==='up'&&trendPct>20)t=`System activity up ${trendPct}% this week — high demand!`;else t=`${peakDayLabel}s have highest demand. "${topResource}" is most booked.`;sg.textContent=t;}

        /* Heatmap */
        const hm=document.getElementById('ins-heatmap');
        if(hm){hm.innerHTML='';const f12=h=>{const ap=h<12?'AM':'PM';return `${h%12||12}${ap}`;};for(let h=0;h<24;h++){const cell=document.createElement('div');const alpha=0.06+(pct(hourArr[h],maxH)/100)*0.9;const isPk=h===peakHourIdx;cell.className='ins-heatmap-cell';cell.style.cssText=`background:rgba(55,48,163,${alpha.toFixed(2)});${isPk?'box-shadow:0 0 0 2px #3730a3;':''}`;cell.title=`${f12(h)}: ${hourArr[h]} reservations`;hm.appendChild(cell);}}

        /* DoW bars */
        const be=document.getElementById('ins-dow-bars'),le=document.getElementById('ins-dow-labels');
        if(be&&le){be.innerHTML='';le.innerHTML='';const labels=mob?DOW_S:DOW;dowArr.forEach((cnt,i)=>{const bar=document.createElement('div');bar.style.cssText=`flex:1;border-radius:5px 5px 0 0;background:${i===peakDowIdx?'#3730a3':'#c7d2fe'};height:${Math.max(pct(cnt,maxD),4)}%;min-height:4px;`;bar.title=`${DOW[i]}: ${cnt}`;be.appendChild(bar);const lbl=document.createElement('div');lbl.style.cssText=`flex:1;text-align:center;font-size:${mob?'8px':'9px'};font-weight:${i===peakDowIdx?'800':'600'};color:${i===peakDowIdx?'#3730a3':'#94a3b8'};`;lbl.textContent=labels[i];le.appendChild(lbl);});}

        /* Mini sparkline */
        const mini=document.getElementById('ins-dow-mini');
        if(mini){mini.innerHTML='';dowArr.forEach((cnt,i)=>{const b=document.createElement('div');b.style.cssText=`flex:1;border-radius:3px;background:${i===peakDowIdx?'#3730a3':'#c7d2fe'};height:${Math.max(pct(cnt,maxD),10)}%;min-height:3px;`;mini.appendChild(b);});}

        /* Monthly chart */
        const mCtx=document.getElementById('ins-month-chart')?.getContext('2d');
        if(mCtx){
            monthChartInst = new Chart(mCtx,{type:'bar',data:{labels:MONTH,datasets:[{data:monthArr,backgroundColor:monthArr.map((_,i)=>i===peakMonthIdx?'#3730a3':'rgba(55,48,163,.15)'),borderRadius:5,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0f172a',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10,callbacks:{label:ctx=>` ${ctx.raw} reservations`}}},scales:{x:{grid:{display:false},ticks:{font:{family:'Plus Jakarta Sans',size:mob?8:10},color:cc.tick}},y:{grid:{color:cc.grid},beginAtZero:true,ticks:{font:{family:'Plus Jakarta Sans',size:mob?8:10},color:cc.tick,stepSize:1}}}}});
        }

        /* Resource ranking */
        const rk=document.getElementById('ins-resource-ranking');
        if(rk){const entries=Object.entries(resourceMap).sort((a,b)=>b[1]-a[1]),topMax=entries[0]?.[1]||1,colors=['#3730a3','#d97706','#7c3aed','#16a34a','#ec4899','#06b6d4','#f87171'];rk.innerHTML=!entries.length?'<p style="font-size:.75rem;color:#94a3b8;text-align:center;padding:16px;">No data yet</p>':entries.slice(0,7).map(([name,cnt],i)=>{const w=pct(cnt,topMax),c=colors[i]||'#94a3b8',share=totalCount>0?Math.round(cnt/totalCount*100):0;return `<div><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;gap:8px;"><div style="display:flex;align-items:center;gap:8px;min-width:0;"><span style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:white;background:${c};flex-shrink:0;">${i+1}</span><span style="font-size:.78rem;font-weight:600;color:#475569;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</span></div><div style="display:flex;align-items:center;gap:8px;flex-shrink:0;"><span style="font-size:.65rem;color:#94a3b8;">${share}%</span><span style="font-size:.78rem;font-weight:800;color:#0f172a;">${cnt}</span></div></div><div class="prog-bar"><div class="prog-fill" style="width:${w}%;background:${c};"></div></div></div>`;}).join('');}
    })();
});
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>