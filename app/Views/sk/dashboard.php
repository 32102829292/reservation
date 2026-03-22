<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | SK Officer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        :root {
            --green:       #16a34a;
            --green-dark:  #14532d;
            --green-light: #f0fdf4;
            --green-mid:   #dcfce7;
            --green-border:#bbf7d0;
            --slate-bg:    #f8fafc;
            --card-border: #e2e8f0;
            --text-main:   #1e293b;
            --text-muted:  #64748b;
            --text-faint:  #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--slate-bg); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        /* ── Sidebar ── */
        .sidebar-card { background: white; border-radius: 32px; border: 1px solid var(--card-border); height: calc(100vh - 48px); position: sticky; top: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,.05); display: flex; flex-direction: column; overflow: hidden; width: 100%; }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; scrollbar-width: thin; scrollbar-color: var(--card-border) transparent; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item { transition: all .18s; display: flex; align-items: center; gap: 14px; padding: 12px 18px; border-radius: 16px; font-weight: 600; font-size: .875rem; text-decoration: none; color: var(--text-muted); }
        .sidebar-item:hover { background: var(--green-light); color: var(--green); }
        .sidebar-item.active { background: var(--green); color: white; box-shadow: 0 8px 20px -4px rgba(22,163,74,.35); }
        .sidebar-item i { width: 20px; text-align: center; font-size: 1rem; flex-shrink: 0; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { position: fixed; bottom: calc(12px + env(safe-area-inset-bottom,0px)); left: 50%; transform: translateX(-50%); width: 94%; max-width: 600px; background: rgba(15,23,42,.97); backdrop-filter: blur(12px); border-radius: 22px; padding: 5px; z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,.3); }
        .mobile-scroll-container { display: flex; gap: 3px; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }
        .mobile-nav-item { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 7px 8px; min-width: 60px; border-radius: 14px; transition: background .15s; flex-shrink: 0; text-decoration: none; }

        /* ── Cards ── */
        .dash-card { background: white; border-radius: 20px; border: 1px solid var(--card-border); box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        @media (min-width:640px) { .dash-card { border-radius: 24px; } }
        .stat-card { background: white; border-radius: 16px; padding: .9rem; border: 1px solid var(--card-border); transition: all .2s; position: relative; overflow: hidden; }
        @media (min-width:640px) { .stat-card { border-radius: 20px; padding: 1.25rem; } }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(0,0,0,.1); }
        .kpi-card { background: white; border-radius: 16px; padding: .875rem 1rem; border: 1px solid var(--card-border); border-left-width: 4px; transition: all .2s; }
        @media (min-width:640px) { .kpi-card { border-radius: 20px; padding: 1.1rem 1.25rem; } }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,.08); }

        /* ── Prog bars ── */
        .prog-bar  { height: 5px; border-radius: 999px; background: var(--card-border); overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; transition: width .8s cubic-bezier(.34,1.56,.64,1); }

        /* ── Charts ── */
        .chart-wrap { position: relative; height: 170px; width: 100%; }
        @media (min-width:640px) { .chart-wrap { height: 220px; } }

        /* Donut chart + legend — stack on mobile */
        .resource-chart-wrap { display: flex; align-items: center; gap: 16px; margin-top: .875rem; flex-wrap: wrap; }
        .resource-chart-canvas { width: 130px !important; height: 130px !important; flex-shrink: 0; }
        @media (min-width:400px) { .resource-chart-canvas { width: 150px !important; height: 150px !important; } }
        @media (min-width:640px) { .resource-chart-canvas { width: 160px !important; height: 160px !important; } }

        /* ── Calendar ── */
        #calendar { font-size: .72rem; }
        @media (min-width:640px) { #calendar { font-size: .78rem; } }
        .fc .fc-toolbar { flex-wrap: wrap; gap: .5rem; }
        .fc-toolbar-title { font-size: .82rem !important; font-weight: 800 !important; color: var(--text-main) !important; }
        @media (min-width:640px) { .fc-toolbar-title { font-size: .9rem !important; } }
        .fc-button-primary { background: var(--green) !important; border-color: var(--green) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans',sans-serif !important; font-weight: 700 !important; font-size: .72rem !important; padding: .28rem .6rem !important; }
        .fc-button-primary:hover { background: var(--green-dark) !important; }
        .fc-daygrid-event { border-radius: 5px !important; font-size: .62rem !important; font-weight: 700 !important; padding: 1px 3px !important; border: none !important; cursor: pointer !important; }
        .fc-daygrid-day:hover { background: var(--green-light) !important; cursor: pointer; }
        .fc-day-today { background: var(--green-light) !important; }
        .fc-day-today .fc-daygrid-day-number { color: var(--green) !important; font-weight: 800 !important; }
        .fc-daygrid-day-number { font-size: .7rem; font-weight: 600; }

        /* ── Avail pills ── */
        .avail-pill { font-size: .63rem; font-weight: 800; padding: .16rem .5rem; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }
        .avail-on  { background: #dcfce7; color: #166634; }
        .avail-off { background: #fee2e2; color: #991b1b; }
        .avail-low { background: #fef3c7; color: #92400e; }

        /* ── Timer banner ── */
        .timer-banner { border-radius: 16px; padding: .875rem 1rem; border: 1px solid; }
        @media (min-width:640px) { .timer-banner { border-radius: 20px; padding: 1rem 1.25rem; } }
        .timer-banner.active   { background: #f0fdf4; border-color: #86efac; }
        .timer-banner.upcoming { background: #eff6ff; border-color: #bfdbfe; }
        .timer-pulse { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; flex-shrink: 0; }
        .timer-pulse.pulse { animation: livePulse 1.5s infinite; }
        @keyframes livePulse { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.4);opacity:.6;} }

        /* ── Date modal — bottom sheet on mobile ── */
        #dateModal { display: none; position: fixed; inset: 0; z-index: 200; align-items: flex-end; justify-content: center; }
        #dateModal.open { display: flex; }
        @media (min-width:480px) { #dateModal { align-items: center; padding: 12px; } }
        .modal-backdrop-layer { position: absolute; inset: 0; background: rgba(15,23,42,.55); backdrop-filter: blur(6px); }
        .modal-box { position: relative; background: white; border-radius: 28px 28px 0 0; width: 100%; max-width: 560px; max-height: 88vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,.35); animation: slideUp .22s cubic-bezier(.34,1.56,.64,1) both; padding-bottom: env(safe-area-inset-bottom,0px); }
        @media (min-width:480px) { .modal-box { border-radius: 32px; animation: popIn .22s cubic-bezier(.34,1.56,.64,1) both; padding-bottom: 0; } }
        @keyframes popIn   { from { opacity:0; transform: scale(.92) translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes slideUp { from { opacity:0; transform: translateY(40px); } to { opacity:1; transform:none; } }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 4px; }
        .date-row { display: flex; align-items: center; gap: 12px; padding: .75rem 1rem; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background .15s; border-radius: 12px; }
        .date-row:hover { background: var(--slate-bg); }
        .date-row:last-child { border-bottom: none; }
        .sheet-handle { width: 40px; height: 4px; background: var(--card-border); border-radius: 999px; margin: 10px auto 0; }
        @media (min-width:480px) { .sheet-handle { display: none; } }

        /* ── Notif dropdown — full-width on mobile ── */
        .notif-dropdown { position: fixed; top: 68px; left: 12px; right: 12px; width: auto; background: white; border-radius: 20px; box-shadow: 0 20px 40px -8px rgba(0,0,0,.2); border: 1px solid var(--card-border); z-index: 300; display: none; }
        @media (min-width:480px) { .notif-dropdown { top: 76px; left: auto; right: 24px; width: 340px; border-radius: 24px; } }
        .notif-dropdown.open { display: block; animation: slideDown .2s ease; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:none; } }
        .notif-list { max-height: 280px; overflow-y: auto; }
        @media (min-width:480px) { .notif-list { max-height: 340px; } }
        .notif-item { padding: .85rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background .15s; cursor: pointer; }
        .notif-item:hover { background: var(--slate-bg); }
        .notif-item.unread { background: var(--green-light); border-left: 3px solid var(--green); }
        .notif-item:last-child { border-bottom: none; }

        /* ── Toast ── */
        #tl-toast-container { position: fixed; bottom: calc(80px + env(safe-area-inset-bottom,0px)); right: 10px; z-index: 9000; display: flex; flex-direction: column; gap: 8px; pointer-events: none; width: calc(100vw - 20px); max-width: 340px; }
        @media (min-width:480px) { #tl-toast-container { right: 20px; width: auto; } }
        .tl-toast { background: #1e293b; color: white; border-radius: 16px; padding: .875rem 1.1rem; box-shadow: 0 12px 28px -4px rgba(0,0,0,.35); display: flex; align-items: flex-start; gap: 10px; pointer-events: auto; animation: toastIn .3s cubic-bezier(.34,1.56,.64,1) both; }
        .tl-toast.dismissing { animation: toastOut .2s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform: translateX(20px) scale(.95); } to { opacity:1; transform:none; } }
        @keyframes toastOut { to   { opacity:0; transform: translateX(24px) scale(.95); } }
        .tl-toast-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .85rem; }
        .tl-toast-warning .tl-toast-icon { background: #f59e0b22; color: #f59e0b; }
        .tl-toast-expired .tl-toast-icon { background: #ef444422; color: #ef4444; }
        .tl-toast-title { font-size: .78rem; font-weight: 800; color: white; line-height: 1.3; }
        .tl-toast-sub   { font-size: .7rem; color: #94a3b8; margin-top: 2px; line-height: 1.4; }

        /* ── Live sessions panel ── */
        .tl-panel { background: white; border-radius: 20px; border: 1px solid var(--card-border); padding: 1rem; }
        @media (min-width:640px) { .tl-panel { border-radius: 24px; padding: 1.25rem; } }
        .tl-session-card { background: var(--slate-bg); border-radius: 14px; border: 1px solid var(--card-border); padding: .75rem .875rem; border-left-width: 4px; transition: all .2s; }
        @media (min-width:640px) { .tl-session-card { border-radius: 16px; padding: .875rem 1rem; } }
        .tl-session-card:hover { box-shadow: 0 4px 12px -2px rgba(0,0,0,.08); }
        .tl-session-card.tl-ok       { border-left-color: #10b981; }
        .tl-session-card.tl-warning  { border-left-color: #f59e0b; }
        .tl-session-card.tl-critical { border-left-color: #ef4444; }
        .tl-session-card.tl-ended    { border-left-color: #94a3b8; opacity: .65; }
        .tl-countdown { display: inline-flex; align-items: center; gap: 5px; padding: .25rem .65rem; border-radius: 999px; font-size: .72rem; font-weight: 800; font-variant-numeric: tabular-nums; white-space: nowrap; }
        .tl-ok       .tl-countdown { background: #dcfce7; color: #166634; }
        .tl-warning  .tl-countdown { background: #fef3c7; color: #92400e; }
        .tl-critical .tl-countdown { background: #fee2e2; color: #991b1b; }
        .tl-ended    .tl-countdown { background: #f1f5f9; color: var(--text-muted); }
        .tl-prog-track { height: 4px; border-radius: 999px; background: var(--card-border); overflow: hidden; margin-top: .5rem; }
        .tl-prog-fill  { height: 100%; border-radius: 999px; transition: width 1s linear; }
        .tl-ok       .tl-prog-fill { background: #10b981; }
        .tl-warning  .tl-prog-fill { background: #f59e0b; }
        .tl-critical .tl-prog-fill { background: #ef4444; }
        .tl-ended    .tl-prog-fill { background: #94a3b8; }

        /* ── Login toast ── */
        #loginToast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); z-index: 8999; background: #1e293b; color: white; border-radius: 20px; padding: .875rem 1.5rem; box-shadow: 0 16px 40px -8px rgba(0,0,0,.4); display: flex; align-items: center; gap: 12px; white-space: nowrap; animation: toastRise .4s cubic-bezier(.34,1.56,.64,1) both; }
        @keyframes toastRise { from{opacity:0;transform:translateX(-50%) translateY(20px) scale(.94);}to{opacity:1;transform:translateX(-50%) translateY(0) scale(1);} }

        /* ── Library ── */
        .library-banner { border-radius: 16px; padding: 1rem; position: relative; overflow: hidden; background: linear-gradient(135deg,#052e16 0%,#15803d 55%,#16a34a 100%); border: 1px solid #22c55e; }
        @media (min-width:640px) { .library-banner { border-radius: 20px; padding: 1.25rem 1.5rem; } }
        .library-banner::before { content:'📚'; position:absolute; right:-10px; top:-10px; font-size:6.5rem; opacity:.07; transform:rotate(14deg); pointer-events:none; line-height:1; }
        .book-row { display: flex; align-items: center; gap: 8px; padding: .5rem .6rem; border-radius: 12px; transition: all .18s; text-decoration: none; color: inherit; border: 1px solid transparent; }
        @media (min-width:640px) { .book-row { gap: 10px; padding: .6rem .75rem; border-radius: 14px; } }
        .book-row:hover { background: var(--green-light); border-color: var(--green-border); }
        .book-spine { width: 3px; border-radius: 4px; align-self: stretch; flex-shrink: 0; min-height: 28px; }

        /* ── AI finder ── */
        .ai-shimmer { background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size: 200% 100%; animation: shimmer 1.4s infinite; border-radius: 8px; }
        @keyframes shimmer { 0%{background-position:200% 0;}100%{background-position:-200% 0;} }

        /* ── Action buttons ── */
        .action-btn { display: flex; flex-direction: column; align-items: center; gap: 7px; padding: .75rem .5rem; border-radius: 14px; background: var(--green-light); border: 1px solid var(--green-border); transition: all .2s; text-decoration: none; color: var(--green); }
        @media (min-width:640px) { .action-btn { padding: .875rem .5rem; border-radius: 16px; gap: 8px; } }
        .action-btn:hover { background: var(--green-mid); border-color: #86efac; transform: translateY(-2px); box-shadow: 0 8px 20px -4px rgba(22,163,74,.2); }

        /* ── Booking rows ── */
        .booking-row { display: flex; align-items: center; gap: 10px; padding: .65rem .75rem; border-radius: 14px; transition: background .15s; }
        .booking-row:hover { background: var(--green-light); }
        .booking-row.expired { opacity: .55; }

        /* ── Heatmap cells ── */
        .ins-heatmap-cell { height: 26px; border-radius: 5px; cursor: default; transition: transform .15s; position: relative; }
        @media (min-width:640px) { .ins-heatmap-cell { height: 32px; border-radius: 6px; } }
        .ins-heatmap-cell:hover { transform: scaleY(1.1); }

        /* ── Section divider ── */
        .section-divider { display: flex; align-items: center; gap: 10px; margin: 1.75rem 0 1rem; }
        @media (min-width:640px) { .section-divider { gap: 12px; margin: 2.25rem 0 1.25rem; } }
        .section-divider > * { flex-shrink: 0; }
        .section-divider-line { flex: 1 1 auto; height: 1px; background: var(--card-border); }

        .section-label { display: inline-flex; align-items: center; gap: 8px; font-size: .65rem; font-weight: 900; text-transform: uppercase; letter-spacing: .12em; color: var(--text-faint); margin-bottom: .875rem; }
        .section-label::before { content: ''; display: inline-block; width: 3px; height: 14px; border-radius: 2px; background: var(--green); flex-shrink: 0; }

        @keyframes fadeUp { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp .35s ease both; }

        /* ── Page layout ── */
        .page-wrapper { display: flex; width: 100%; height: 100vh; overflow: hidden; }
        .sidebar-col  { width: 280px; flex-shrink: 0; padding: 24px; display: none; height: 100vh; overflow: hidden; }
        @media (min-width: 1024px) { .sidebar-col { display: block; } }
        .main-col { flex: 1; min-width: 0; height: 100vh; overflow-y: auto; }
        .dash-main { padding-bottom: calc(90px + env(safe-area-inset-bottom,16px)); }

        /* ── Sync badge ── */
        .sync-badge { display: inline-flex; align-items: center; gap: 5px; font-size: .62rem; font-weight: 800; padding: .2rem .55rem; border-radius: 999px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; white-space: nowrap; }
    </style>
</head>
<body>

<?php
/* ═══════════════════════════════════════════════════
   PHP DATA PREPARATION
   SYNC FIX: Use $allReservations (system-wide) for
   stats, live sessions, and calendar so numbers match
   what the admin sees.
═══════════════════════════════════════════════════ */
$page     = $page ?? 'dashboard';
$navItems = [
    ['url'=>'/sk/dashboard',            'icon'=>'fa-house',           'label'=>'Dashboard',        'key'=>'dashboard'],
    ['url'=>'/sk/reservations',         'icon'=>'fa-calendar-alt',    'label'=>'All Reservations', 'key'=>'reservations'],
    ['url'=>'/sk/new-reservation',      'icon'=>'fa-plus',            'label'=>'New Reservation',  'key'=>'new-reservation'],
    ['url'=>'/sk/user-requests',        'icon'=>'fa-users',           'label'=>'User Requests',    'key'=>'user-requests'],
    ['url'=>'/sk/my-reservations',      'icon'=>'fa-calendar',        'label'=>'My Reservations',  'key'=>'my-reservations'],
    ['url'=>'/sk/scanner',              'icon'=>'fa-qrcode',          'label'=>'Scanner',          'key'=>'scanner'],
    ['url'=>'/sk/profile',              'icon'=>'fa-regular fa-user', 'label'=>'Profile',          'key'=>'profile'],
];

$myRes  = $reservations    ?? [];
$sysRes = $allReservations ?? [];

$sysTotal    = count($sysRes);
$sysPending  = count(array_filter($sysRes, fn($r) => ($r['status']??'') === 'pending'));
$sysApproved = count(array_filter($sysRes, fn($r) => ($r['status']??'') === 'approved'));
$sysDeclined = count(array_filter($sysRes, fn($r) => in_array($r['status']??'', ['declined','canceled'])));
$sysClaimed  = count(array_filter($sysRes, fn($r) => in_array($r['claimed']??false, [true,1,'t','true','1'], true)));

$sysToday    = date('Y-m-d');
$sysTodayAll = array_filter($sysRes, fn($r) => ($r['reservation_date']??'') === $sysToday);
$sysTodayTotal    = count($sysTodayAll);
$sysTodayApproved = count(array_filter($sysTodayAll, fn($r) => ($r['status']??'') === 'approved'));
$sysTodayPending  = count(array_filter($sysTodayAll, fn($r) => ($r['status']??'') === 'pending'));
$sysTodayClaimed  = count(array_filter($sysTodayAll, fn($r) => in_array($r['claimed']??false, [true,1,'t','true','1'], true)));

$sysApprovalRate    = $sysTotal    > 0 ? round($sysApproved / $sysTotal    * 100) : 0;
$sysUtilizationRate = $sysApproved > 0 ? round($sysClaimed  / $sysApproved * 100) : 0;

$thirtyDaysAgo   = date('Y-m-d', strtotime('-30 days'));
$sysMonthlyTotal = count(array_filter($sysRes, fn($r) => ($r['reservation_date']??'') >= $thirtyDaysAgo));

$remainingReservations = $remainingReservations ?? 0;
$pendingUserCount      = $pendingUserCount      ?? 0;
$dashBooks             = $dashBooks             ?? [];
$featuredBooks         = $featuredBooks         ?? [];
$myBorrowings          = $myBorrowings          ?? [];
$availableCount        = $availableCount        ?? 0;
$totalBooks            = $totalBooks            ?? 0;

$usedSlots = (int)($usedThisMonth   ?? 0);
$maxSlots  = (int)($maxMonthlySlots ?? 3);
$maxSlots  = max(1, $maxSlots);
$quotaPct  = min(100, round($usedSlots / $maxSlots * 100));

$insHourArr = array_fill(0,24,0);
$insDowArr  = array_fill(0,7,0);
$insMonArr  = array_fill(0,12,0);
$insResMap  = [];
$insDateVol = [];
$ins7 = 0; $insPrev7 = 0;

foreach ($sysRes as $r) {
    if (!empty($r['start_time']))       $insHourArr[(int)date('G', strtotime($r['start_time']))]++;
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
$insNS  = $sysApproved > 0 ? round((($sysApproved - $sysClaimed) / $sysApproved) * 100) : 0;
$insDR  = $sysTotal    > 0 ? round(($sysDeclined / $sysTotal) * 100) : 0;

$chartLabels = []; $chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $chartLabels[] = date('D', strtotime($d));
    $chartData[]   = count(array_filter($sysRes, fn($r) => ($r['reservation_date']??'') === $d));
}

$resourceLabels = []; $resourceData = []; $topResources = []; $resCount = [];
foreach ($sysRes as $r) { $rn = $r['resource_name'] ?? 'Unknown'; $resCount[$rn] = ($resCount[$rn] ?? 0) + 1; }
arsort($resCount);
foreach (array_slice($resCount, 0, 5, true) as $rname => $cnt) { $resourceLabels[] = $rname; $resourceData[] = (int)$cnt; $topResources[] = ['name' => $rname, 'count' => $cnt]; }
if (empty($resourceLabels)) { $resourceLabels = ['No Data']; $resourceData = [1]; }
?>

<div class="page-wrapper">

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar-col">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">SK Officer</span>
            <h1 class="text-2xl font-extrabold text-slate-800 mt-0.5">Portal<span class="text-green-600">.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-1">
            <?php foreach ($navItems as $item): $a=($page==$item['key'])?'active':''; ?>
                <a href="<?= $item['url'] ?>" class="sidebar-item <?= $a ?>">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
                    <?= $item['label'] ?>
                    <?php if ($item['key']==='user-requests' && ($pendingUserCount??0)>0): ?>
                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingUserCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <div class="bg-slate-50 rounded-2xl p-3 mb-3 border border-slate-100">
                <div class="flex items-center justify-between mb-1.5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">My Monthly Quota</p>
                    <span class="text-[10px] font-black text-green-600"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                </div>
                <div class="prog-bar"><div class="prog-fill <?= $quotaPct >= 100 ? 'bg-red-500' : ($quotaPct >= 66 ? 'bg-amber-500' : 'bg-green-500') ?>" style="width:<?= $quotaPct ?>%"></div></div>
                <p class="text-[10px] text-slate-400 font-medium mt-1"><?= $remainingReservations ?> slot<?= $remainingReservations != 1 ? 's' : '' ?> remaining</p>
            </div>
            <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
            </a>
        </div>
    </div>
</aside>

<!-- ════════ MOBILE NAV ════════ -->
<nav class="lg:hidden mobile-nav-pill">
    <div class="mobile-scroll-container text-white px-1">
        <?php foreach ($navItems as $item): $cls=($page==$item['key'])?'bg-green-700':'hover:bg-green-500/30'; ?>
            <a href="<?= $item['url'] ?>" class="mobile-nav-item <?= $cls ?>">
                <i class="fa-solid <?= $item['icon'] ?> text-base"></i>
                <span class="text-[9px] mt-0.5 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mobile-nav-item hover:bg-red-500/30 text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
            <span class="text-[9px] mt-0.5 text-center leading-tight whitespace-nowrap">Logout</span>
        </a>
    </div>
</nav>

<!-- ════════ MODALS ════════ -->
<div id="dateModal" role="dialog" aria-modal="true">
    <div class="modal-backdrop-layer" onclick="closeDateModal()"></div>
    <div class="modal-box">
        <div class="sheet-handle"></div>
        <div class="flex items-center justify-between px-5 sm:px-7 pt-4 sm:pt-7 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Schedule</p>
                <h3 id="modalDateTitle" class="text-base sm:text-lg font-black text-slate-900"></h3>
            </div>
            <button onclick="closeDateModal()" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="modalList" class="px-4 py-3 space-y-1"></div>
        <div class="px-5 sm:px-7 pb-5 sm:pb-7 pt-2">
            <button onclick="closeDateModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">Close</button>
        </div>
    </div>
</div>

<div id="notifDropdown" class="notif-dropdown">
    <div class="flex items-center justify-between px-4 sm:px-5 py-3 sm:py-4 border-b border-slate-100">
        <p class="font-black text-slate-800 text-sm">Notifications</p>
        <button onclick="markAllRead()" class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-bold transition">Mark all read</button>
    </div>
    <div id="notifList" class="notif-list">
        <div class="p-6 text-center text-slate-400"><i class="fa-regular fa-bell-slash text-3xl mb-2 block text-slate-200"></i><p class="text-sm font-bold">No notifications</p></div>
    </div>
</div>

<div id="tl-toast-container"></div>

<div id="loginToast" style="display:none">
    <div class="w-9 h-9 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-hand-wave text-white"></i></div>
    <div><p class="font-black text-sm">Welcome back, <?= htmlspecialchars($user_name ?? 'Officer') ?>!</p><p class="text-[11px] text-slate-400 mt-0.5"><?= date('l, F j') ?></p></div>
</div>

<!-- ════════ MAIN ════════ -->
<div class="main-col">
<main class="w-full max-w-screen-xl mx-auto px-4 lg:px-8 pt-5 lg:pt-6 dash-main">

    <!-- HEADER -->
    <header class="flex items-start justify-between mb-5 gap-3 fade-up flex-wrap">
        <div class="min-w-0">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                <?php $hh=(int)date('H'); echo $hh<12?'Good morning':($hh<17?'Good afternoon':'Good evening'); ?>, <?= htmlspecialchars($user_name ?? 'Officer') ?>
            </p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight">SK Dashboard</h2>
            <p class="text-slate-400 font-medium text-xs sm:text-sm mt-0.5 flex items-center gap-2 flex-wrap">
                <span><?= date('l, F j, Y') ?></span>
                <span class="sync-badge"><i class="fa-solid fa-rotate text-[9px]"></i> Synced with Admin</span>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0 flex-wrap justify-end">
            <?php if (($pendingUserCount??0)>0): ?>
                <a href="/sk/user-requests" class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-2 rounded-2xl font-bold text-xs hover:bg-amber-100 transition">
                    <i class="fa-solid fa-clock text-xs"></i>
                    <span class="hidden sm:inline"><?= $pendingUserCount ?> pending</span>
                    <span class="sm:hidden"><?= $pendingUserCount ?></span>
                </a>
            <?php endif; ?>
            <div class="relative">
                <button id="bellBtn" onclick="toggleNotif()" class="w-9 h-9 sm:w-10 sm:h-10 bg-white border border-slate-200 rounded-2xl flex items-center justify-center shadow-sm hover:border-green-300 transition text-slate-500"><i class="fa-regular fa-bell text-sm"></i></button>
                <span id="notifBadge" style="display:none" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full border-2 border-white leading-none">0</span>
            </div>
            <a href="/sk/new-reservation" class="flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-2xl font-bold text-xs transition shadow-lg shadow-green-200">
                <i class="fa-solid fa-plus text-xs"></i>
                <span class="hidden sm:inline">Reserve</span>
            </a>
        </div>
    </header>

    <!-- Timer banner -->
    <?php
    $activeBanner = null; $upcomingBanner = null; $now = time();
    foreach ($myRes as $r) {
        if (empty($r['reservation_date'])||empty($r['start_time'])||empty($r['end_time'])) continue;
        if (($r['status']??'')==='approved' && !($r['claimed']??false)) {
            $s=strtotime($r['reservation_date'].'T'.$r['start_time']);
            $e=strtotime($r['reservation_date'].'T'.$r['end_time']);
            if ($s<=$now&&$e>=$now) { $activeBanner=$r; break; }
            if ($s>$now&&$s<=$now+3600) { $upcomingBanner=$r; }
        }
    }
    if ($activeBanner||$upcomingBanner): $b=$activeBanner??$upcomingBanner; $isActive=!!$activeBanner;
    ?>
    <div class="timer-banner <?= $isActive?'active':'upcoming' ?> mb-4 flex items-center gap-3 fade-up" id="timerBanner"
         data-start="<?= strtotime($b['reservation_date'].'T'.$b['start_time']) ?>"
         data-end="<?= strtotime($b['reservation_date'].'T'.$b['end_time']) ?>"
         data-active="<?= $isActive?'1':'0' ?>">
        <div class="timer-pulse <?= $isActive?'pulse':'' ?>"></div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-black <?= $isActive?'text-green-800':'text-blue-800' ?> truncate"><?= $isActive?'My session in progress':'My session starting soon' ?> · <?= htmlspecialchars($b['resource_name']??'Resource') ?></p>
            <p class="text-[11px] <?= $isActive?'text-green-600':'text-blue-500' ?> font-medium"><?= date('g:i A', strtotime($b['start_time'])) ?> – <?= date('g:i A', strtotime($b['end_time'])) ?></p>
        </div>
        <span id="timerDisplay" class="text-sm font-black <?= $isActive?'text-green-700':'text-blue-700' ?> font-variant-numeric tabular-nums flex-shrink-0">—</span>
    </div>
    <?php endif; ?>

    <!-- SECTION 1 — LIVE SESSIONS -->
    <p class="section-label">Live Monitor <span class="sync-badge ml-2">All Users</span></p>
    <div class="tl-panel mb-6 sm:mb-8">
        <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-green-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-timer text-green-600 text-sm"></i></div>
                <div><h3 class="font-extrabold text-slate-800 text-sm leading-tight">Active Sessions</h3><p class="text-[10px] text-slate-400 font-medium">System-wide · Real-time</p></div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-emerald-400"></span><span class="hidden sm:inline">Active</span></span>
                <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-amber-400"></span><span class="hidden sm:inline">Warning</span></span>
                <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-red-400"></span><span class="hidden sm:inline">Critical</span></span>
            </div>
        </div>
        <div id="tl-sessions-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3"></div>
        <p id="tl-no-sessions" class="hidden text-center text-sm text-slate-400 py-5 sm:py-6 font-medium"><i class="fa-regular fa-circle-pause text-2xl text-slate-200 block mb-2"></i>No active sessions right now</p>
    </div>

    <!-- SECTION 2 — RESERVATION OVERVIEW -->
    <p class="section-label">Reservation Overview <span class="sync-badge ml-2">System-wide</span></p>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-3 sm:mb-4">
        <div class="stat-card">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-green-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-layer-group text-green-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-green-600 uppercase tracking-wider">+<?= $sysMonthlyTotal ?> mo</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total</p>
            <p class="text-2xl sm:text-3xl font-black text-slate-800"><?= $sysTotal ?></p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Avg <span class="font-bold text-green-600"><?= $sysTotal>0?round($sysTotal/30,1):0 ?>/day</span></p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-wider"><?= $sysApprovalRate ?>%</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Approved</p>
            <p class="text-2xl sm:text-3xl font-black text-emerald-600"><?= $sysApproved ?></p>
            <div class="prog-bar mt-1.5 sm:mt-2"><div class="prog-fill bg-emerald-500" style="width:<?= $sysApprovalRate ?>%"></div></div>
            <p class="text-xs text-slate-400 mt-1 sm:mt-1.5 font-medium">Approval rate</p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-amber-50 rounded-xl flex items-center justify-center"><i class="fa-regular fa-clock text-amber-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-amber-600 uppercase tracking-wider"><?= $sysTodayTotal ?> today</span>
            </div>
            <div class="grid grid-cols-3 gap-1 text-center mt-1">
                <div><p class="text-lg sm:text-xl font-black text-amber-600"><?= $sysTodayPending ?></p><p class="text-[9px] text-slate-400 font-bold">Pending</p></div>
                <div><p class="text-lg sm:text-xl font-black text-emerald-600"><?= $sysTodayApproved ?></p><p class="text-[9px] text-slate-400 font-bold">Approved</p></div>
                <div><p class="text-lg sm:text-xl font-black text-purple-600"><?= $sysTodayClaimed ?></p><p class="text-[9px] text-slate-400 font-bold">Claimed</p></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-purple-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-check-double text-purple-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-purple-600 uppercase tracking-wider"><?= $sysUtilizationRate ?>%</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Claimed</p>
            <p class="text-2xl sm:text-3xl font-black text-purple-600"><?= $sysClaimed ?></p>
            <div class="prog-bar mt-1.5 sm:mt-2"><div class="prog-fill bg-purple-500" style="width:<?= $sysUtilizationRate ?>%"></div></div>
            <p class="text-xs text-slate-400 mt-1 sm:mt-1.5 font-medium">Utilization rate</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <?php foreach ([
            ['Total',    $sysTotal,             'border-green-500',   'text-slate-700',   'fa-layer-group',    'text-green-500'],
            ['Pending',  $sysPending,            'border-amber-500',   'text-amber-600',   'fa-clock',          'text-amber-500'],
            ['Approved', $sysApproved,           'border-emerald-500', 'text-emerald-600', 'fa-circle-check',   'text-emerald-500'],
            ['My Slots', $remainingReservations, 'border-purple-500',  'text-purple-600',  'fa-hourglass-half', 'text-purple-500'],
        ] as [$l,$v,$b,$c,$i,$ic]): ?>
            <div class="kpi-card <?= $b ?>">
                <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $l ?></p>
                    <i class="fa-solid <?= $i ?> text-sm <?= $ic ?>"></i>
                </div>
                <p class="text-xl sm:text-2xl font-black <?= $c ?>"><?= $v ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-6 sm:mb-8">
        <div class="dash-card p-4 sm:p-5">
            <div class="flex items-center justify-between mb-1">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Reservations Trend</h3><p class="text-[11px] text-slate-400 font-medium">Last 7 days · All users</p></div>
                <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-full"><span class="w-2 h-2 rounded-full bg-green-500"></span><span class="hidden sm:inline">System-wide</span></span>
            </div>
            <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="dash-card p-4 sm:p-5">
            <div class="flex items-center justify-between mb-1">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Popular Resources</h3><p class="text-[11px] text-slate-400 font-medium">Most reserved · All users</p></div>
                <span class="text-[10px] font-black bg-green-50 text-green-600 px-2.5 py-1 rounded-full">Top 5</span>
            </div>
            <div class="resource-chart-wrap">
                <canvas id="resourceChart" class="resource-chart-canvas"></canvas>
                <div id="resourceLegend" class="flex-1 min-w-0 space-y-2.5"></div>
            </div>
        </div>
    </div>

    <!-- SECTION 3 — SCHEDULE & ACTIVITY -->
    <p class="section-label">Schedule &amp; Activity</p>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">

        <div class="lg:col-span-2 dash-card p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-calendar-days text-sm"></i></div>
                    <div><h3 class="font-extrabold text-slate-800 text-sm leading-tight">Reservation Calendar</h3><p class="text-[10px] text-slate-400 font-medium">All users · Tap date to view</p></div>
                </div>
                <div class="hidden sm:flex items-center gap-3 flex-wrap">
                    <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500"><span class="w-2 h-2 rounded-full" style="background:<?= $c ?>"></span><?= $l ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <div class="flex flex-col gap-3 sm:gap-4">
            <div class="rounded-2xl p-3 sm:p-4 text-white" style="background:linear-gradient(135deg,#052e16,#16a34a);">
                <div class="flex items-center gap-2 mb-2 sm:mb-3"><i class="fa-solid fa-bolt text-green-300 text-sm"></i><h3 class="font-black text-sm">System Stats</h3></div>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach ([
                        ['Approval',    $sysApprovalRate.'%',    'fa-chart-line'],
                        ['Utilization', $sysUtilizationRate.'%', 'fa-chart-pie'],
                        ['My Slots',    $remainingReservations,  'fa-hourglass-half'],
                        ['Total',       $sysTotal,               'fa-layer-group'],
                    ] as [$l,$v,$ic]): ?>
                        <div class="bg-white/10 rounded-xl p-2.5 sm:p-3"><div class="flex items-center gap-1.5 mb-1"><i class="fa-solid <?= $ic ?> text-green-300 text-[10px]"></i><p class="text-[9px] text-green-200 font-black uppercase tracking-wider"><?= $l ?></p></div><p class="text-lg sm:text-xl font-black"><?= $v ?></p></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="dash-card p-3 sm:p-4">
                <h3 class="font-extrabold text-slate-800 text-sm mb-2 sm:mb-3">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="/sk/new-reservation"  class="action-btn"><i class="fa-solid fa-plus text-base sm:text-lg"></i><span class="text-[10px] font-black text-center leading-tight">New Reservation</span></a>
                    <a href="/sk/reservations"      class="action-btn"><i class="fa-solid fa-calendar text-base sm:text-lg"></i><span class="text-[10px] font-black text-center leading-tight">All Reservations</span></a>
                    <a href="/sk/books"             class="action-btn"><i class="fa-solid fa-book-open text-base sm:text-lg"></i><span class="text-[10px] font-black text-center leading-tight">Browse Library</span></a>
                    <a href="/sk/profile"           class="action-btn"><i class="fa-regular fa-user text-base sm:text-lg"></i><span class="text-[10px] font-black text-center leading-tight">View Profile</span></a>
                </div>
            </div>

            <div class="dash-card p-3 sm:p-4 flex-1">
                <div class="flex items-center justify-between mb-2 sm:mb-3">
                    <h3 class="font-extrabold text-slate-800 text-sm">Recent Bookings</h3>
                    <a href="/sk/reservations" class="text-[10px] font-black text-green-600 bg-green-50 border border-green-200 px-2.5 py-1 rounded-xl hover:bg-green-100 transition">View all →</a>
                </div>
                <div class="space-y-1">
                    <?php
                    $recentAll = array_slice(array_reverse($sysRes), 0, 4);
                    foreach ($recentAll as $r):
                        $isCl = in_array($r['claimed']??false, [true,1,'t','true','1'], true);
                        $st   = $isCl ? 'claimed' : ($r['status']??'pending');
                        $clr  = ['approved'=>'text-emerald-600','pending'=>'text-amber-600','declined'=>'text-rose-600','claimed'=>'text-purple-600'];
                        $ico  = ['approved'=>'fa-circle-check','pending'=>'fa-clock','declined'=>'fa-xmark-circle','claimed'=>'fa-check-double'];
                        $isExpired = !empty($r['reservation_date']) && strtotime($r['reservation_date']) < strtotime('today') && !$isCl;
                        $rName = $r['resource_name'] ?? 'Resource';
                        $vName = $r['visitor_name']  ?? $r['full_name'] ?? 'Guest';
                    ?>
                        <div class="booking-row <?= $isExpired?'expired':'' ?>">
                            <div class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-desktop text-slate-500 text-xs"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-xs text-slate-800 truncate"><?= htmlspecialchars($rName) ?></p>
                                <p class="text-[10px] text-slate-400 truncate"><?= htmlspecialchars($vName) ?> · <?= !empty($r['reservation_date'])?date('M j',strtotime($r['reservation_date'])):'—' ?></p>
                            </div>
                            <i class="fa-solid <?= $ico[$st]??'fa-circle' ?> text-xs <?= $clr[$st]??'text-slate-400' ?> flex-shrink-0"></i>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($recentAll)): ?>
                        <div class="text-center py-5"><i class="fa-regular fa-calendar-xmark text-2xl text-slate-200 mb-1 block"></i><p class="text-xs text-slate-400 font-medium">No bookings yet</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4 — LIBRARY -->
    <div class="section-divider">
        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-book-open text-green-600 text-xs sm:text-sm"></i></div>
        <span class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Library</span>
        <div class="section-divider-line"></div>
        <a href="/sk/books" class="text-xs font-black text-green-600 bg-green-50 border border-green-200 px-2.5 sm:px-3 py-1.5 rounded-xl hover:bg-green-100 transition whitespace-nowrap">Browse All →</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">
        <div class="flex flex-col gap-3 sm:gap-4">
            <div class="library-banner">
                <div class="relative z-10">
                    <p class="text-[10px] font-black tracking-[0.18em] text-green-300 uppercase mb-1">Book Collection</p>
                    <p class="text-xl sm:text-2xl font-black text-white leading-tight"><?= $availableCount ?> <span class="text-sm font-semibold text-green-300">available</span></p>
                    <p class="text-green-400 text-xs font-medium mb-3 sm:mb-4"><?= $totalBooks ?> total titles</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-white/10 rounded-xl px-2.5 sm:px-3 py-2 sm:py-2.5 text-center"><p class="text-lg sm:text-xl font-black text-white"><?= count($myBorrowings) ?></p><p class="text-[9px] font-black text-green-300 uppercase tracking-wider mt-0.5">My Borrows</p></div>
                        <div class="bg-white/10 rounded-xl px-2.5 sm:px-3 py-2 sm:py-2.5 text-center"><?php $bpct=$totalBooks>0?round($availableCount/$totalBooks*100):0; ?><p class="text-lg sm:text-xl font-black text-white"><?= $bpct ?>%</p><p class="text-[9px] font-black text-green-300 uppercase tracking-wider mt-0.5">In Stock</p></div>
                    </div>
                </div>
            </div>

            <div class="dash-card p-3 sm:p-4">
                <div class="flex items-center gap-2 mb-2 sm:mb-3">
                    <div class="w-7 h-7 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-wand-magic-sparkles text-green-600 text-xs"></i></div>
                    <div><h3 class="font-extrabold text-slate-800 text-xs">AI Book Finder</h3><p class="text-[9px] text-slate-400 font-medium">Powered by RAG</p></div>
                </div>
                <div class="flex gap-2 mb-2 sm:mb-3">
                    <input id="ai-query" type="text" placeholder="What are you looking for?" class="flex-1 text-xs border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:border-green-400 focus:ring-2 focus:ring-green-100 font-medium bg-slate-50 min-w-0" style="min-height:38px;" onkeydown="if(event.key==='Enter')aiFind()">
                    <button onclick="aiFind()" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-xs transition flex-shrink-0" style="min-width:38px;min-height:38px;"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div id="ai-results" class="space-y-2 max-h-36 overflow-y-auto"></div>
            </div>
        </div>

        <div class="lg:col-span-2 flex flex-col gap-3 sm:gap-4">
            <div class="dash-card p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div><h3 class="font-extrabold text-slate-800 text-sm">Books Catalog</h3><p class="text-[10px] text-slate-400 font-medium">Availability at a glance</p></div>
                    <a href="/sk/books" class="text-[10px] font-black text-green-600 bg-green-50 px-2.5 py-1.5 rounded-xl border border-green-200 hover:bg-green-100 transition">Browse All</a>
                </div>
                <?php if(!empty($dashBooks)):
                    $gc=['fiction'=>'#16a34a','fantasy'=>'#8b5cf6','poetry'=>'#ec4899','humor'=>'#f59e0b','history'=>'#78716c','science'=>'#06b6d4','romance'=>'#f43f5e']; ?>
                    <div class="grid grid-cols-12 gap-2 px-3 pb-2 border-b border-slate-100 mb-1">
                        <div class="col-span-7 sm:col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Title</div>
                        <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Author</div>
                        <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Genre</div>
                        <div class="col-span-5 sm:col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Stock</div>
                    </div>
                    <div class="space-y-0.5">
                        <?php foreach(array_slice($dashBooks,0,8) as $book):
                            $g=$book['genre']??''; $sc=$gc[strtolower($g)]??'#16a34a';
                            $av=(int)($book['available_copies']??0); $tc=(int)($book['total_copies']??1);
                            $ac=$av===0?'avail-off':($av<=1?'avail-low':'avail-on');
                            $at=$av===0?'Out':($av<=1?'1 left':$av.' left');
                        ?>
                            <a href="/sk/books" class="book-row grid grid-cols-12 gap-2 items-center">
                                <div class="col-span-7 sm:col-span-5 flex items-center gap-2 min-w-0"><div class="book-spine" style="background:<?= $sc ?>"></div><div class="min-w-0"><p class="font-bold text-xs text-slate-800 truncate leading-tight"><?= htmlspecialchars($book['title']) ?></p><p class="text-[10px] text-slate-400 truncate sm:hidden"><?= htmlspecialchars($book['author']??'—') ?></p></div></div>
                                <div class="col-span-3 hidden sm:block"><p class="text-xs text-slate-500 truncate"><?= htmlspecialchars($book['author']??'—') ?></p></div>
                                <div class="col-span-2 hidden sm:block"><?php if(!empty($book['genre'])): ?><span class="text-[10px] font-bold text-slate-500 truncate block"><?= htmlspecialchars($book['genre']) ?></span><?php else: ?><span class="text-[10px] text-slate-300">—</span><?php endif; ?></div>
                                <div class="col-span-5 sm:col-span-2 flex items-center justify-end gap-1.5"><span class="text-[10px] text-slate-400 font-medium hidden sm:inline"><?= $av ?>/<?= $tc ?></span><span class="avail-pill <?= $ac ?>"><?= $at ?></span></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if(count($dashBooks)>8): ?><div class="mt-3 pt-3 border-t border-slate-100 text-center"><a href="/sk/books" class="text-xs font-bold text-green-600 hover:text-green-700">+<?= count($dashBooks)-8 ?> more books →</a></div><?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-8 sm:py-10"><i class="fa-solid fa-book-open text-4xl text-slate-200 mb-3 block"></i><p class="text-sm text-slate-400 font-medium">No books available</p></div>
                <?php endif; ?>
            </div>

            <?php if(!empty($myBorrowings)): ?>
            <div class="dash-card p-4 sm:p-5">
                <h3 class="font-extrabold text-slate-800 text-sm mb-3 flex items-center gap-2"><i class="fa-solid fa-book text-green-500 text-xs"></i> My Active Borrows</h3>
                <div class="space-y-2">
                    <?php foreach(array_slice($myBorrowings,0,4) as $bw):
                        $due=!empty($bw['due_date'])?strtotime($bw['due_date']):null;
                        $overdue=$due&&$due<time(); $dueSoon=$due&&!$overdue&&$due<time()+3*86400;
                    ?>
                        <div class="flex items-center gap-3 p-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-book-open text-green-600 text-xs"></i></div>
                            <div class="flex-1 min-w-0"><p class="font-bold text-xs text-slate-800 truncate"><?= htmlspecialchars($bw['book_title']??'Book') ?></p><p class="text-[10px] <?= $overdue?'text-red-500 font-bold':($dueSoon?'text-amber-600 font-bold':'text-slate-400') ?>"><?= $due?($overdue?'Overdue · ':($dueSoon?'Due soon · ':'')).date('M j, Y',$due):'No due date' ?></p></div>
                            <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-1 rounded-full whitespace-nowrap flex-shrink-0 <?= $overdue?'bg-red-50 text-red-600':($dueSoon?'bg-amber-50 text-amber-600':'bg-green-50 text-green-600') ?>"><?= $overdue?'Overdue':($dueSoon?'Due Soon':'Active') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- SECTION 5 — INSIGHTS -->
    <div class="section-divider">
        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-chart-mixed text-emerald-600 text-xs sm:text-sm"></i></div>
        <span class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Insights</span>
        <div class="section-divider-line"></div>
        <span class="text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 sm:px-3 py-1.5 rounded-xl whitespace-nowrap"><i class="fa-solid fa-sparkles text-[10px] mr-1"></i><span class="hidden sm:inline">System-wide · </span>Auto-generated</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-5">
        <div class="dash-card p-3 sm:p-4 relative overflow-hidden">
            <div class="absolute -right-3 -top-3 text-6xl sm:text-7xl opacity-[0.04] pointer-events-none select-none">⏰</div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-amber-50 rounded-xl flex items-center justify-center mb-2 sm:mb-3"><i class="fa-solid fa-sun text-amber-500 text-xs sm:text-sm"></i></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Peak Hour</p>
            <p class="text-sm sm:text-base font-black text-slate-800 leading-tight"><?= htmlspecialchars($insPHL) ?></p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">Busiest window</p>
            <div class="mt-2 sm:mt-3 h-1 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-amber-400 rounded-full" style="width:<?= max(array_values($insHourArr))>0?min(100,round($insHourArr[$insPH]/max(array_values($insHourArr))*100)):0 ?>%"></div></div>
        </div>
        <div class="dash-card p-3 sm:p-4 relative overflow-hidden">
            <div class="absolute -right-3 -top-3 text-6xl sm:text-7xl opacity-[0.04] pointer-events-none select-none">📅</div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-50 rounded-xl flex items-center justify-center mb-2 sm:mb-3"><i class="fa-solid fa-calendar-week text-green-500 text-xs sm:text-sm"></i></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Busiest Day</p>
            <p class="text-sm sm:text-base font-black text-slate-800 leading-tight"><?= htmlspecialchars($insPDL) ?></p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">Most bookings</p>
            <div id="ins-dow-mini" class="flex gap-0.5 mt-2 sm:mt-3 items-end h-5 sm:h-6"></div>
        </div>
        <div class="dash-card p-3 sm:p-4 relative overflow-hidden">
            <div class="absolute -right-3 -top-3 text-6xl sm:text-7xl opacity-[0.04] pointer-events-none select-none">🖥️</div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-50 rounded-xl flex items-center justify-center mb-2 sm:mb-3"><i class="fa-solid fa-fire text-emerald-500 text-xs sm:text-sm"></i></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Most Wanted</p>
            <p class="text-sm sm:text-base font-black text-slate-800 leading-tight truncate"><?= htmlspecialchars($insTopRes) ?></p>
            <p class="text-[10px] text-slate-400 font-medium mt-1"><?= $insTopResCnt ?> reservations</p>
            <div class="mt-2 sm:mt-3"><span class="inline-flex items-center gap-1 text-[10px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0"><i class="fa-solid fa-arrow-trend-up text-[8px]"></i> High demand</span></div>
        </div>
        <div class="dash-card p-3 sm:p-4 relative overflow-hidden">
            <div class="absolute -right-3 -top-3 text-6xl sm:text-7xl opacity-[0.04] pointer-events-none select-none">📈</div>
            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-violet-50 rounded-xl flex items-center justify-center mb-2 sm:mb-3"><i class="fa-solid fa-chart-line text-violet-500 text-xs sm:text-sm"></i></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">WoW Trend</p>
            <p class="text-sm sm:text-base font-black leading-tight" style="color:<?= $insTrC ?>"><?= ($insTrD==='up'?'+':'').$insTrP ?>%</p>
            <p class="text-[10px] text-slate-400 font-medium mt-1">vs prev 7 days</p>
            <div class="mt-2 sm:mt-3 h-1 bg-slate-100 rounded-full overflow-hidden"><div class="h-full rounded-full" style="width:<?= min(abs($insTrP),100) ?>%;background:<?= $insTrC ?>"></div></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-4 sm:mb-5">
        <div class="lg:col-span-2 dash-card p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Hourly Activity Heatmap</h3><p class="text-[11px] text-slate-400 font-medium">Booking density by hour</p></div>
                <span class="text-[10px] font-black bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full border border-amber-200 whitespace-nowrap">Demand Map</span>
            </div>
            <div id="ins-heatmap" class="grid gap-1 sm:gap-1.5" style="grid-template-columns:repeat(12,1fr)"></div>
            <div class="flex justify-between mt-1.5 px-0.5"><span class="text-[9px] text-slate-400 font-bold">12 AM</span><span class="text-[9px] text-slate-400 font-bold">12 PM</span><span class="text-[9px] text-slate-400 font-bold">11 PM</span></div>
            <div class="mt-4 sm:mt-5 pt-3 sm:pt-4 border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 sm:mb-3">Day-of-Week Volume</p>
                <div id="ins-dow-bars" class="flex gap-1.5 sm:gap-2 items-end h-14 sm:h-16"></div>
                <div id="ins-dow-labels" class="flex gap-1.5 sm:gap-2 mt-1.5"></div>
            </div>
        </div>
        <div class="flex flex-col gap-3">
            <div class="dash-card p-3 sm:p-4">
                <h3 class="font-extrabold text-slate-800 text-sm mb-2 sm:mb-3 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-rose-400 text-xs"></i> Health Indicators</h3>
                <div class="space-y-2.5 sm:space-y-3">
                    <div><div class="flex justify-between text-xs mb-1"><span class="font-semibold text-slate-600">No-show rate</span><span class="font-black text-rose-600"><?= $insNS ?>%</span></div><div class="prog-bar"><div class="prog-fill bg-rose-400" style="width:<?= $insNS ?>%"></div></div><p class="text-[9px] text-slate-400 mt-0.5 font-medium">Approved but never claimed</p></div>
                    <div><div class="flex justify-between text-xs mb-1"><span class="font-semibold text-slate-600">Decline rate</span><span class="font-black text-amber-600"><?= $insDR ?>%</span></div><div class="prog-bar"><div class="prog-fill bg-amber-400" style="width:<?= $insDR ?>%"></div></div><p class="text-[9px] text-slate-400 mt-0.5 font-medium">Of all reservations rejected</p></div>
                    <div><div class="flex justify-between text-xs mb-1"><span class="font-semibold text-slate-600">Claim rate</span><span class="font-black text-emerald-600"><?= $sysUtilizationRate ?>%</span></div><div class="prog-bar"><div class="prog-fill bg-emerald-500" style="width:<?= $sysUtilizationRate ?>%"></div></div><p class="text-[9px] text-slate-400 mt-0.5 font-medium">Approved slots used</p></div>
                </div>
            </div>
            <div class="dash-card p-3 sm:p-4">
                <h3 class="font-extrabold text-slate-800 text-sm mb-2 sm:mb-3 flex items-center gap-2"><i class="fa-solid fa-crown text-amber-400 text-xs"></i> Record Day</h3>
                <p class="text-xl sm:text-2xl font-black text-slate-800"><?= $insBDC ?></p>
                <p class="text-xs text-slate-500 font-semibold"><?= htmlspecialchars($insBDL) ?></p>
                <p class="text-[10px] text-slate-400 font-medium mt-1">Most reservations in a single day</p>
            </div>
            <div class="rounded-2xl p-3 sm:p-4 border border-emerald-200 bg-emerald-50">
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-lightbulb text-emerald-600 text-xs"></i></div>
                    <div><p class="text-xs font-black text-emerald-800 mb-1">Smart Suggestion</p><p class="text-[11px] text-emerald-700 font-medium leading-relaxed" id="ins-suggestion">Analyzing patterns…</p></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 mb-6 sm:mb-8">
        <div class="dash-card p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Monthly Seasonality</h3><p class="text-[11px] text-slate-400 font-medium">Volume by calendar month</p></div>
                <span class="text-[10px] font-black bg-green-50 text-green-600 px-2.5 py-1 rounded-full border border-green-200 whitespace-nowrap">Peak: <?= htmlspecialchars($insPML) ?></span>
            </div>
            <div class="chart-wrap" style="height:160px;"><canvas id="ins-month-chart"></canvas></div>
        </div>
        <div class="dash-card p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Resource Demand Ranking</h3><p class="text-[11px] text-slate-400 font-medium">All-time count per resource</p></div>
                <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full border border-emerald-200 whitespace-nowrap">All Time</span>
            </div>
            <div id="ins-resource-ranking" class="space-y-2 sm:space-y-2.5"></div>
        </div>
    </div>

</main>
</div>
</div>

<script>
const allRes    = <?= json_encode($myRes)  ?>;
const allResAll = <?= json_encode($sysRes) ?>;
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

const clamp  = (v,lo,hi) => Math.max(lo,Math.min(hi,v));
const pct    = (v,max)   => max>0 ? clamp(Math.round(v/max*100),0,100) : 0;
const timeAgo= t => { const s=Math.floor((Date.now()-new Date(t))/1000); if(s<60)return 'Just now'; if(s<3600)return `${Math.floor(s/60)}m ago`; if(s<86400)return `${Math.floor(s/3600)}h ago`; return `${Math.floor(s/86400)}d ago`; };
const isMobile = () => window.innerWidth < 640;

/* ── Login Toast ── */
(function(){
    const key='sk_login_toast_'+new Date().toISOString().split('T')[0];
    if(!sessionStorage.getItem(key)){const t=document.getElementById('loginToast');t.style.display='flex';sessionStorage.setItem(key,'1');setTimeout(()=>{t.style.opacity='0';t.style.transition='opacity .4s';setTimeout(()=>t.remove(),400);},4000);}
})();

/* ── Timer Banner ── */
(function(){
    const banner=document.getElementById('timerBanner');
    if(!banner)return;
    const start=parseInt(banner.dataset.start)*1000,end=parseInt(banner.dataset.end)*1000,isActive=banner.dataset.active==='1';
    const disp=document.getElementById('timerDisplay');
    function updateTimer(){
        const now=Date.now(),rem=end-now,countdown=isActive?rem:start-now;
        if(countdown<=0){disp.textContent=isActive?'Ended':'Now';return;}
        const s=Math.floor(countdown/1000),m=Math.floor(s/60),h=Math.floor(m/60);
        disp.textContent=h>0?`${h}h ${m%60}m`:`${m}m ${s%60}s`;
    }
    updateTimer();setInterval(updateTimer,1000);
})();

/* ── Date Modal ── */
function openDateModal(dateStr,list){
    const fmt=new Date(dateStr+'T00:00:00').toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
    document.getElementById('modalDateTitle').textContent=fmt;
    const c=document.getElementById('modalList');
    if(!list?.length){c.innerHTML=`<div class="py-8 text-center text-slate-400"><i class="fa-solid fa-calendar-xmark text-3xl mb-2 block text-slate-200"></i><p class="text-sm font-bold">No reservations on this date</p></div>`;}
    else{c.innerHTML=[...list].sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).map(r=>{
        const isCl=r.claimed==1||r.claimed===true||r.claimed==='true';
        const st=isCl?'claimed':(r.status||'pending');
        const clr={approved:'bg-emerald-100 text-emerald-700',pending:'bg-amber-100 text-amber-700',declined:'bg-rose-100 text-rose-700',claimed:'bg-purple-100 text-purple-700'};
        const t=r.start_time?r.start_time.slice(0,5):'—',et=r.end_time?r.end_time.slice(0,5):'';
        const name=r.visitor_name||r.full_name||'Guest';
        return `<div class="date-row" onclick="location='/sk/reservations?id=${r.id}'"><div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-desktop text-green-600 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800 leading-tight truncate">${r.resource_name||'Resource'}</p><p class="text-xs text-slate-400 mt-0.5">${name}</p></div><div class="text-right flex-shrink-0"><p class="text-xs font-black text-green-600">${t}${et?'–'+et:''}</p><span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase ${clr[st]||'bg-slate-100 text-slate-600'}">${st}</span></div></div>`;
    }).join('');}
    document.getElementById('dateModal').classList.add('open');document.body.style.overflow='hidden';
}
function closeDateModal(){document.getElementById('dateModal').classList.remove('open');document.body.style.overflow='';}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDateModal();});

/* ── Notifications ── */
let readIds=JSON.parse(localStorage.getItem('sk_read_notifs')||'[]'),notifs=[];
function initNotifs(){
    allRes.filter(r=>['approved','declined'].includes(r.status||'')&&!readIds.includes(String(r.id))).slice(0,10).forEach(r=>notifs.push({id:r.id,msg:`Your ${r.resource_name||'resource'} reservation was ${r.status}`,time:r.updated_at||r.created_at||new Date().toISOString(),status:r.status}));
    const b=document.getElementById('notifBadge');if(notifs.length){b.style.display='block';b.textContent=notifs.length>9?'9+':notifs.length;}renderNotifs();
}
function renderNotifs(){
    const l=document.getElementById('notifList');
    if(!notifs.length){l.innerHTML=`<div class="p-6 text-center text-slate-400"><i class="fa-regular fa-bell-slash text-3xl mb-2 block text-slate-200"></i><p class="text-sm font-bold">No notifications</p></div>`;return;}
    l.innerHTML=notifs.map(n=>{const ico=n.status==='approved'?'fa-circle-check text-emerald-600':'fa-xmark-circle text-rose-600';const bg=n.status==='approved'?'bg-emerald-100':'bg-rose-100';return `<div class="notif-item unread" onclick="location='/sk/reservations?id=${n.id}'"><div class="flex items-start gap-3"><div class="w-8 h-8 ${bg} rounded-full flex items-center justify-center flex-shrink-0"><i class="fa-solid ${ico} text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">Reservation ${n.status==='approved'?'Approved':'Declined'}</p><p class="text-xs text-slate-500 mt-0.5 truncate">${n.msg}</p><p class="text-[10px] text-slate-400 mt-1">${timeAgo(n.time)}</p></div></div></div>`;}).join('');
}
function markAllRead(){notifs.forEach(n=>{if(!readIds.includes(String(n.id)))readIds.push(String(n.id));});notifs=[];localStorage.setItem('sk_read_notifs',JSON.stringify(readIds));document.getElementById('notifBadge').style.display='none';renderNotifs();}
function toggleNotif(){document.getElementById('notifDropdown').classList.toggle('open');}
document.addEventListener('click',e=>{const d=document.getElementById('notifDropdown'),b=document.getElementById('bellBtn');if(!b.contains(e.target)&&!d.contains(e.target))d.classList.remove('open');});

/* ── AI Finder ── */
async function aiFind(){
    const q=document.getElementById('ai-query').value.trim();if(!q)return;
    const r=document.getElementById('ai-results');
    r.innerHTML=`<div class="ai-shimmer h-10 w-full mb-1"></div><div class="ai-shimmer h-10 w-3/4"></div>`;
    try{
        const res=await fetch('/rag/suggest',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'<?= csrf_token() ?>'},body:JSON.stringify({query:q})});
        const d=await res.json();
        if(d.books?.length){r.innerHTML=d.books.map(b=>`<a href="/sk/books?id=${b.id}" class="flex items-center gap-2 p-2 hover:bg-green-50 rounded-xl transition"><div class="w-6 h-full rounded flex-shrink-0" style="min-height:28px;background:var(--green)"></div><div class="min-w-0"><p class="text-xs font-bold text-slate-800 truncate">${b.title}</p><p class="text-[10px] text-slate-400 truncate">${b.author||'—'}</p></div></a>`).join('');}
        else r.innerHTML=`<p class="text-xs text-slate-400 text-center py-3 font-medium">No matches found</p>`;
    }catch(e){r.innerHTML=`<p class="text-xs text-rose-500 text-center py-2 font-medium">Search failed</p>`;}
}

/* ── Active Sessions (system-wide) ── */
const TL_WARN=5*60,TL_CRIT=2*60;
let tlSessions={};
function tlGetActiveSessions(){
    const today=new Date().toISOString().split('T')[0],nowMs=Date.now();
    return allResAll.filter(r=>{
        if(!r.start_time||!r.end_time||!r.reservation_date||r.reservation_date!==today)return false;
        if((r.status||'').toLowerCase()!=='approved')return false;
        const s=new Date(r.reservation_date+'T'+r.start_time).getTime(),e=new Date(r.reservation_date+'T'+r.end_time).getTime();
        return s<=nowMs&&e>=nowMs;
    });
}
const tlFmt=ms=>{if(ms<=0)return 'Ended';const s=Math.floor(ms/1000),m=Math.floor(s/60),h=Math.floor(m/60);if(h>0)return `${h}h ${m%60}m`;if(m>0)return `${m}m ${s%60}s`;return `${s}s`;};
const tlState=ms=>ms<=0?'tl-ended':ms<=TL_CRIT*1000?'tl-critical':ms<=TL_WARN*1000?'tl-warning':'tl-ok';
function tlToast(type,title,sub){const c=document.getElementById('tl-toast-container'),t=document.createElement('div');t.className=`tl-toast tl-toast-${type}`;const ic=type==='warning'?'fa-triangle-exclamation':'fa-clock-rotate-left';t.innerHTML=`<div class="tl-toast-icon"><i class="fa-solid ${ic}"></i></div><div class="flex-1 min-w-0"><p class="tl-toast-title">${title}</p><p class="tl-toast-sub">${sub}</p></div><button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;padding:0;font-size:.8rem;"><i class="fa-solid fa-xmark"></i></button>`;c.appendChild(t);setTimeout(()=>{t.classList.add('dismissing');setTimeout(()=>t.remove(),220);},7000);}

function tlRender(){
    const sessions=tlGetActiveSessions(),grid=document.getElementById('tl-sessions-grid'),noS=document.getElementById('tl-no-sessions'),nowMs=Date.now();
    if(!sessions.length){grid.innerHTML='';noS.classList.remove('hidden');return;}noS.classList.add('hidden');
    sessions.forEach(r=>{
        const eMs=new Date(r.reservation_date+'T'+r.end_time).getTime(),sMs=new Date(r.reservation_date+'T'+r.start_time).getTime(),totMs=eMs-sMs,remMs=eMs-nowMs,elMs=nowMs-sMs;
        const prog=Math.min(100,Math.max(0,(elMs/totMs)*100)),state=tlState(remMs),name=r.visitor_name||r.full_name||'Guest',res=r.resource_name||'Resource';
        if(!tlSessions[r.id])tlSessions[r.id]={warned:false,expired:false};
        const s=tlSessions[r.id];
        if(!s.warned&&remMs>0&&remMs<=TL_WARN*1000){s.warned=true;tlToast('warning',`${name} — 5 min left`,`${res} ending soon`);}
        if(!s.expired&&remMs<=0){s.expired=true;tlToast('expired',`${name}'s session ended`,`${res} time limit reached`);}
        let card=document.getElementById(`tl-card-${r.id}`);
        if(!card){card=document.createElement('div');card.id=`tl-card-${r.id}`;grid.appendChild(card);}
        const sf=r.start_time?r.start_time.substring(0,5):'–',ef=r.end_time?r.end_time.substring(0,5):'–',usedMin=Math.max(0,Math.floor(elMs/60000));
        card.className=`tl-session-card ${state}`;
        card.innerHTML=`<div class="flex items-start justify-between gap-2 mb-2"><div class="min-w-0 flex-1"><p class="font-extrabold text-slate-800 text-xs truncate">${name}</p><p class="text-[10px] text-slate-400 truncate mt-0.5">${res}</p></div><span class="tl-countdown flex-shrink-0"><i class="fa-regular fa-clock" style="font-size:.6rem"></i>${tlFmt(remMs)}</span></div><div class="tl-prog-track"><div class="tl-prog-fill" style="width:${prog}%"></div></div><div class="flex items-center justify-between mt-2"><span class="text-[10px] text-slate-400 font-medium">${sf} – ${ef}</span><span class="text-[10px] font-bold text-slate-500">${usedMin} min used</span></div>`;
    });
    const ids=sessions.map(r=>`tl-card-${r.id}`);Array.from(grid.children).forEach(c=>{if(!ids.includes(c.id))c.remove();});
}

/* ── DOMContentLoaded ── */
document.addEventListener('DOMContentLoaded',()=>{
    tlRender();setInterval(tlRender,1000);
    initNotifs();

    const mobile = window.innerWidth < 640;
    const chartFont = { family:'Plus Jakarta Sans', size: mobile ? 9 : 11 };

    /* Trend chart */
    const tCtx=document.getElementById('trendChart')?.getContext('2d');
    if(tCtx)new Chart(tCtx,{type:'line',data:{labels:<?= json_encode($chartLabels) ?>,datasets:[{data:<?= json_encode($chartData) ?>,borderColor:'#16a34a',backgroundColor:'rgba(22,163,74,0.08)',borderWidth:2.5,tension:0.4,fill:true,pointBackgroundColor:'#16a34a',pointRadius:mobile?3:4,pointHoverRadius:mobile?5:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#1e293b',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10}},scales:{x:{grid:{display:false},ticks:{font:chartFont,color:'#94a3b8'}},y:{grid:{color:'#f1f5f9'},ticks:{font:chartFont,color:'#94a3b8',stepSize:1},beginAtZero:true}}}});

    /* Donut chart */
    const rCtx=document.getElementById('resourceChart')?.getContext('2d');
    const rL=<?= json_encode($resourceLabels) ?>,rD=<?= json_encode($resourceData) ?>,pal=['#16a34a','#f59e0b','#8b5cf6','#10b981','#ec4899'];
    if(rCtx){
        new Chart(rCtx,{type:'doughnut',data:{labels:rL,datasets:[{data:rD,backgroundColor:pal,borderWidth:0,hoverOffset:4}]},options:{responsive:false,animation:false,cutout:'65%',plugins:{legend:{display:false},tooltip:{backgroundColor:'#1e293b',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10}}}});
        const leg=document.getElementById('resourceLegend');
        if(leg)leg.innerHTML=rL.map((l,i)=>`<div class="flex items-center gap-2.5 min-w-0"><span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${pal[i]||'#94a3b8'}"></span><span class="text-xs sm:text-sm text-slate-600 truncate flex-1 min-w-0 font-medium">${l}</span><span class="text-xs sm:text-sm font-black text-slate-800 flex-shrink-0">${rD[i]}</span></div>`).join('');
    }

    /* Calendar */
    const byDate={};
    allResAll.forEach(r=>{if(!r.reservation_date)return;(byDate[r.reservation_date]=byDate[r.reservation_date]||[]).push(r);});
    const events=allResAll.filter(r=>r.reservation_date).map(r=>{
        const isCl=r.claimed==1||r.claimed===true||r.claimed==='true';
        const st=isCl?'claimed':(r.status||'pending');
        const clr={approved:'#10b981',pending:'#fbbf24',declined:'#f87171',claimed:'#a855f7'};
        return{title:(r.visitor_name||r.full_name||'Guest')+' · '+(r.resource_name||'Resource'),start:r.reservation_date+(r.start_time?'T'+r.start_time:''),end:r.reservation_date+(r.end_time?'T'+r.end_time:''),backgroundColor:clr[st]||'#94a3b8',borderColor:'transparent',textColor:'#fff'};
    });
    new FullCalendar.Calendar(document.getElementById('calendar'),{
        initialView:'dayGridMonth',
        headerToolbar:{left:'prev,next',center:'title',right:'today'},
        events,
        height: mobile ? 250 : 370,
        eventDisplay:'block',
        eventMaxStack: mobile ? 1 : 2,
        dateClick:info=>openDateModal(info.dateStr,byDate[info.dateStr]||[]),
        eventClick:info=>openDateModal(info.event.startStr.split('T')[0],byDate[info.event.startStr.split('T')[0]]||[]),
        dayCellDidMount:info=>{
            const d=info.date.toISOString().split('T')[0];
            const cnt=(byDate[d]||[]).length;
            if(cnt){const b=document.createElement('div');b.style.cssText='font-size:8px;font-weight:800;color:white;background:#16a34a;border-radius:999px;width:15px;height:15px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:4px;margin-bottom:2px;';b.textContent=cnt;info.el.querySelector('.fc-daygrid-day-top')?.appendChild(b);}
        }
    }).render();

    /* ── Insights ── */
    (function(){
        const DOW=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],DOW_MOBILE=['S','M','T','W','T','F','S'],MONTH=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const{hourArr,dowArr,monthArr,peakHourIdx,peakDowIdx,peakMonthIdx,noShowRate,declineRate,trendPct,trendDir,topResource,peakDayLabel,resourceMap,totalCount}=INS;
        const maxH=Math.max(...hourArr,1),maxD=Math.max(...dowArr,1);

        /* Suggestion */
        const sg=document.getElementById('ins-suggestion');
        if(sg){let t='';if(noShowRate>30)t=`High no-show rate (${noShowRate}%). Consider sending reminders before sessions.`;else if(declineRate>25)t=`Many requests declined (${declineRate}%). Book "${topResource}" earlier for better chances.`;else if(trendDir==='up'&&trendPct>20)t=`System up ${trendPct}% this week — high demand!`;else if(trendDir==='down'&&Math.abs(trendPct)>20)t=`Activity dropped ${Math.abs(trendPct)}%. ${peakDayLabel}s are still busiest.`;else t=`${peakDayLabel}s have the highest demand. Book "${topResource}" early.`;sg.textContent=t;}

        /* Heatmap */
        const hm=document.getElementById('ins-heatmap');
        if(hm){hm.innerHTML='';const f12=h=>{const ap=h<12?'AM':'PM';const h12=h%12||12;return `${h12}${ap}`;};for(let h=0;h<24;h++){const cell=document.createElement('div');const alpha=0.07+(pct(hourArr[h],maxH)/100)*0.88;const isPk=h===peakHourIdx;cell.className='ins-heatmap-cell';cell.style.cssText=`background:rgba(22,163,74,${alpha.toFixed(2)});${isPk?'box-shadow:0 0 0 2px #16a34a;':''}`;cell.title=`${f12(h)}: ${hourArr[h]} reservations`;if(isPk){const p=document.createElement('div');p.style.cssText='position:absolute;top:2px;right:2px;width:4px;height:4px;border-radius:50%;background:#fbbf24;';cell.appendChild(p);}hm.appendChild(cell);}}

        /* DoW bars */
        const be=document.getElementById('ins-dow-bars'),le=document.getElementById('ins-dow-labels');
        if(be&&le){be.innerHTML='';le.innerHTML='';const dowLabels=mobile?DOW_MOBILE:DOW;dowArr.forEach((cnt,i)=>{const bar=document.createElement('div');bar.style.cssText=`flex:1;border-radius:6px 6px 0 0;background:${i===peakDowIdx?'#16a34a':'#bbf7d0'};height:${Math.max(pct(cnt,maxD),4)}%;min-height:4px;`;bar.title=`${DOW[i]}: ${cnt}`;be.appendChild(bar);const lbl=document.createElement('div');lbl.style.cssText=`flex:1;text-align:center;font-size:${mobile?'8px':'9px'};font-weight:${i===peakDowIdx?'800':'600'};color:${i===peakDowIdx?'#16a34a':'#94a3b8'};`;lbl.textContent=dowLabels[i];le.appendChild(lbl);});}

        /* Mini sparkline */
        const mini=document.getElementById('ins-dow-mini');
        if(mini){mini.innerHTML='';dowArr.forEach((cnt,i)=>{const b=document.createElement('div');b.style.cssText=`flex:1;border-radius:3px;background:${i===peakDowIdx?'#16a34a':'#dcfce7'};height:${Math.max(pct(cnt,maxD),10)}%;min-height:3px;`;mini.appendChild(b);});}

        /* Monthly chart */
        const mCtx=document.getElementById('ins-month-chart')?.getContext('2d');
        if(mCtx)new Chart(mCtx,{type:'bar',data:{labels:MONTH,datasets:[{data:monthArr,backgroundColor:monthArr.map((_,i)=>i===peakMonthIdx?'#16a34a':'rgba(22,163,74,0.15)'),borderRadius:6,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#1e293b',titleFont:{family:'Plus Jakarta Sans',weight:'700'},bodyFont:{family:'Plus Jakarta Sans'},padding:10,cornerRadius:10,callbacks:{label:ctx=>` ${ctx.raw} reservations`}}},scales:{x:{grid:{display:false},ticks:{font:{family:'Plus Jakarta Sans',size:mobile?8:10},color:'#94a3b8'}},y:{grid:{color:'#f1f5f9'},beginAtZero:true,ticks:{font:{family:'Plus Jakarta Sans',size:mobile?8:10},color:'#94a3b8',stepSize:1}}}}});

        /* Resource ranking */
        const rk=document.getElementById('ins-resource-ranking');
        if(rk){const entries=Object.entries(resourceMap).sort((a,b)=>b[1]-a[1]),topMax=entries[0]?.[1]||1,colors=['#16a34a','#f59e0b','#8b5cf6','#10b981','#ec4899','#06b6d4','#f87171'];rk.innerHTML=!entries.length?'<p class="text-xs text-slate-400 text-center py-6 font-medium">No data yet</p>':entries.slice(0,7).map(([name,cnt],i)=>{const w=pct(cnt,topMax),c=colors[i]||'#94a3b8',share=totalCount>0?Math.round(cnt/totalCount*100):0;return `<div><div class="flex items-center justify-between mb-1 gap-2"><div class="flex items-center gap-2 min-w-0"><span class="w-5 h-5 rounded-lg flex items-center justify-center text-[9px] font-black text-white flex-shrink-0" style="background:${c}">${i+1}</span><span class="text-xs font-semibold text-slate-700 truncate">${name}</span></div><div class="flex items-center gap-1.5 flex-shrink-0"><span class="text-[10px] text-slate-400 font-medium">${share}%</span><span class="text-xs font-black text-slate-800">${cnt}</span></div></div><div class="prog-bar"><div class="prog-fill" style="width:${w}%;background:${c}"></div></div></div>`;}).join('');}
    })();
});
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>