<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | <?= esc($user_name ?? session()->get('name') ?? 'SK Officer') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; display: flex; height: 100vh; overflow: hidden; }

        /* ── Sidebar ── */
        .sidebar-card { background: white; border-radius: 32px; border: 1px solid #e2e8f0; height: calc(100vh - 48px); position: sticky; top: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; overflow: hidden; width: 100%; }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item { transition: all 0.18s; display: flex; align-items: center; gap: 16px; padding: 14px 20px; border-radius: 16px; font-weight: 600; font-size: 0.875rem; text-decoration: none; color: #64748b; }
        .sidebar-item:hover { background: #f8fafc; color: #16a34a; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 8px 20px -4px rgba(22,163,74,0.35); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 92%; max-width: 560px; background: rgba(20,83,45,0.97); backdrop-filter: blur(16px); border-radius: 24px; padding: 6px; z-index: 100; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.4); }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Cards ── */
        .dash-card { background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); }
        .stat-card { background: white; border-radius: 20px; padding: 1.25rem; border: 1px solid #e2e8f0; transition: all 0.2s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1); }
        .kpi-card { background: white; border-radius: 20px; padding: 1.1rem 1.25rem; border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s; }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }

        /* ── Progress bars ── */
        .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }
        .fairness-bar { height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .fairness-fill { height: 100%; border-radius: 999px; background: #16a34a; transition: width 0.6s cubic-bezier(0.34,1.56,0.64,1); }

        /* ── Charts ── */
        .chart-wrap { position: relative; height: 220px; width: 100%; }

        /* ── Calendar ── */
        #calendar { font-size: 0.78rem; }
        .fc .fc-toolbar { flex-wrap: wrap; gap: 0.5rem; }
        .fc-toolbar-title { font-size: 0.9rem !important; font-weight: 800 !important; color: #1e293b !important; }
        .fc-button-primary { background: #16a34a !important; border-color: #16a34a !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 700 !important; font-size: 0.72rem !important; padding: 0.28rem 0.6rem !important; }
        .fc-button-primary:hover { background: #15803d !important; }
        .fc-daygrid-event { border-radius: 5px !important; font-size: 0.65rem !important; font-weight: 700 !important; padding: 1px 4px !important; border: none !important; cursor: pointer !important; }
        .fc-daygrid-day:hover { background-color: #f0fdf4 !important; cursor: pointer; }
        .fc-day-today { background: #f0fdf4 !important; }
        .fc-day-today .fc-daygrid-day-number { color: #16a34a !important; font-weight: 800 !important; }
        .fc-daygrid-day-number { font-size: 0.72rem; font-weight: 600; }

        /* ── Tags ── */
        .tag { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
        .tag-pending   { background: #fef3c7; color: #92400e; }
        .tag-approved  { background: #dcfce7; color: #166534; }
        .tag-claimed   { background: #f3e8ff; color: #6b21a8; }
        .tag-declined, .tag-cancelled { background: #fee2e2; color: #991b1b; }
        .tag-expired   { background: #f1f5f9; color: #475569; }
        .borrow-tag-pending  { background: #fef3c7; color: #92400e; }
        .borrow-tag-approved { background: #dcfce7; color: #166534; }
        .borrow-tag-returned { background: #dbeafe; color: #1e40af; }
        .borrow-tag-rejected { background: #fee2e2; color: #991b1b; }

        /* ── Date Modal ── */
        #dateModal { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        #dateModal.open { display: flex; }
        .modal-backdrop-layer { position: absolute; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); }
        .modal-box { position: relative; margin: auto; background: white; border-radius: 32px; width: 94%; max-width: 560px; max-height: 88vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both; }
        @keyframes popIn { from { opacity:0; transform: scale(0.92) translateY(16px); } to { opacity:1; transform: none; } }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .date-row { display: flex; align-items: center; gap: 12px; padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.15s; border-radius: 12px; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }

        /* ── Notification Dropdown ── */
        .notif-dropdown { position: fixed; top: 76px; right: 20px; width: 340px; background: white; border-radius: 24px; box-shadow: 0 20px 40px -8px rgba(0,0,0,0.2); border: 1px solid #e2e8f0; z-index: 300; display: none; }
        .notif-dropdown.open { display: block; animation: slideDown 0.2s ease; }
        @keyframes slideDown { from { opacity:0; transform: translateY(-8px); } to { opacity:1; transform: none; } }
        .notif-list { max-height: 340px; overflow-y: auto; }
        .notif-item { padding: 0.85rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0fdf4; border-left: 3px solid #16a34a; }
        .notif-item:last-child { border-bottom: none; }

        @keyframes fadeUp { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }

        /* ── Section divider ── */
        .section-divider { display: flex; align-items: center; gap: 12px; margin: 2rem 0 1.25rem; }
        .section-divider-line { flex: 1; height: 1px; background: #e2e8f0; }

        /* ── Library ── */
        .library-banner { background: linear-gradient(135deg, #1e3a2f 0%, #14532d 55%, #166534 100%); border-radius: 20px; padding: 1.25rem 1.5rem; position: relative; overflow: hidden; border: 1px solid #15803d; }
        .library-banner::before { content: '📚'; position: absolute; right: -10px; top: -10px; font-size: 6.5rem; opacity: 0.07; transform: rotate(14deg); pointer-events: none; line-height: 1; }
        .book-row { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0.75rem; border-radius: 14px; transition: all 0.18s; text-decoration: none; color: inherit; border: 1px solid transparent; }
        .book-row:hover { background: #f0fdf4; border-color: #bbf7d0; }
        .book-spine { width: 3px; border-radius: 4px; align-self: stretch; flex-shrink: 0; min-height: 30px; }
        .avail-pill { font-size: 0.63rem; font-weight: 800; padding: 0.16rem 0.5rem; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }
        .avail-on  { background: #dcfce7; color: #166634; }
        .avail-off { background: #fee2e2; color: #991b1b; }
        .avail-low { background: #fef3c7; color: #92400e; }

        /* ── Booking / Quick action ── */
        .booking-item { display: flex; align-items: center; gap: 12px; padding: 0.875rem 1rem; border-radius: 16px; transition: all 0.2s; text-decoration: none; color: inherit; }
        .booking-item:hover { background: #f0fdf4; }
        .quick-action { display: flex; align-items: center; gap: 12px; padding: 0.875rem 1rem; border-radius: 16px; border: 1.5px solid #e2e8f0; background: white; transition: all 0.2s; cursor: pointer; text-decoration: none; color: inherit; font-weight: 600; font-size: 0.85rem; }
        .quick-action:hover { border-color: #16a34a; background: #f0fdf4; color: #16a34a; transform: translateY(-1px); }

        /* ── Timer Banner ── */
        .timer-banner { display: none; border-radius: 20px; padding: 0.875rem 1.25rem; margin-bottom: 1.25rem; border: 1.5px solid; position: relative; overflow: hidden; }
        .timer-banner::before { content: ''; position: absolute; inset: 0; opacity: 0.04; background: repeating-linear-gradient(45deg,currentColor 0,currentColor 1px,transparent 0,transparent 50%); background-size: 8px 8px; pointer-events: none; }
        .timer-banner.urgent  { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
        .timer-banner.warning { background: #fefce8; border-color: #fde68a; color: #854d0e; }
        .timer-banner.safe    { background: #f0fdf4; border-color: #bbf7d0; color: #14532d; }
        .timer-progress-wrap { height: 5px; border-radius: 999px; background: rgba(0,0,0,0.1); overflow: hidden; margin-top: 10px; }
        .timer-progress-fill { height: 100%; border-radius: 999px; background: currentColor; opacity: 0.5; transition: width 1s linear; }
        .timer-digit { display: inline-flex; flex-direction: column; align-items: center; background: rgba(0,0,0,0.08); border-radius: 10px; padding: 0.25rem 0.5rem; min-width: 2.6rem; font-variant-numeric: tabular-nums; font-weight: 900; font-size: 1.1rem; line-height: 1; }
        .timer-digit span { font-size: 0.55rem; font-weight: 700; opacity: 0.65; text-transform: uppercase; letter-spacing: 0.07em; margin-top: 2px; }
        .timer-pulse { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

        /* ── Upcoming pill ── */
        .upcoming-pill { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #bbf7d0; border-radius: 20px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 12px; }

        /* ── Login Toast ── */
        .login-toast { position: fixed; bottom: 96px; left: 50%; transform: translateX(-50%) translateY(20px); background: #1e293b; color: white; border-radius: 20px; padding: 0.875rem 1.25rem; z-index: 500; max-width: 420px; width: calc(100% - 2.5rem); box-shadow: 0 24px 48px -8px rgba(0,0,0,0.35); display: flex; align-items: flex-start; gap: 12px; opacity: 0; pointer-events: none; transition: all 0.4s cubic-bezier(0.34,1.56,0.64,1); border: 1px solid rgba(255,255,255,0.08); }
        .login-toast.show { opacity: 1; pointer-events: auto; transform: translateX(-50%) translateY(0); }
        .toast-icon { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem; }
        .toast-close { margin-left: auto; flex-shrink: 0; width: 24px; height: 24px; border-radius: 8px; background: rgba(255,255,255,0.1); border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; transition: background 0.15s; }
        .toast-close:hover { background: rgba(255,255,255,0.2); }

        /* ── AI Search ── */
        .rag-search-wrap { position: relative; }
        .rag-search-input { width: 100%; padding: 0.7rem 1rem 0.7rem 2.5rem; border-radius: 16px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.83rem; font-weight: 500; color: #1e293b; outline: none; transition: all 0.2s; }
        .rag-search-input:focus { border-color: #16a34a; background: white; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }
        .rag-search-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.8rem; }
        .ai-suggestion-box { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1.5px solid #bbf7d0; border-radius: 16px; padding: 0.875rem 1rem; margin-top: 0.75rem; display: none; }
        .ai-suggestion-box.show { display: block; animation: fadeUp 0.3s ease; }
        .shimmer { height: 11px; border-radius: 6px; background: linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%); background-size: 200% 100%; animation: shimmerAnim 1.4s infinite; margin-bottom: 0.45rem; }
        @keyframes shimmerAnim { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        @keyframes countUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
        .stat-num { animation: countUp 0.5s ease both; }

        /* ── Active Session Monitor ── */
        .tl-panel { background: white; border-radius: 24px; border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 1.5rem; }
        .tl-session-card { background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; padding: 0.875rem 1rem; border-left-width: 4px; transition: all 0.2s; position: relative; overflow: hidden; }
        .tl-session-card:hover { box-shadow: 0 4px 12px -2px rgba(0,0,0,0.08); }
        .tl-session-card.tl-ok       { border-left-color: #10b981; }
        .tl-session-card.tl-warning  { border-left-color: #f59e0b; }
        .tl-session-card.tl-critical { border-left-color: #ef4444; }
        .tl-session-card.tl-ended    { border-left-color: #94a3b8; opacity: 0.65; }
        .tl-countdown { display: inline-flex; align-items: center; gap: 5px; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; font-variant-numeric: tabular-nums; white-space: nowrap; }
        .tl-ok       .tl-countdown { background: #dcfce7; color: #166634; }
        .tl-warning  .tl-countdown { background: #fef3c7; color: #92400e; }
        .tl-critical .tl-countdown { background: #fee2e2; color: #991b1b; }
        .tl-ended    .tl-countdown { background: #f1f5f9; color: #64748b; }
        .tl-prog-track { height: 4px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 0.5rem; }
        .tl-prog-fill  { height: 100%; border-radius: 999px; transition: width 1s linear; }
        .tl-ok       .tl-prog-fill { background: #10b981; }
        .tl-warning  .tl-prog-fill { background: #f59e0b; }
        .tl-critical .tl-prog-fill { background: #ef4444; }
        .tl-ended    .tl-prog-fill { background: #94a3b8; }

        /* ── Session Toasts ── */
        #tl-toast-container { position: fixed; bottom: 88px; right: 20px; z-index: 9000; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
        .tl-toast { background: #1e293b; color: white; border-radius: 16px; padding: 0.875rem 1.1rem; min-width: 280px; max-width: 360px; box-shadow: 0 12px 28px -4px rgba(0,0,0,0.35); display: flex; align-items: flex-start; gap: 10px; pointer-events: auto; animation: toastIn 0.3s cubic-bezier(0.34,1.56,0.64,1) both; }
        .tl-toast.dismissing { animation: toastOut 0.2s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform: translateX(20px) scale(0.95); } to { opacity:1; transform: none; } }
        @keyframes toastOut { to   { opacity:0; transform: translateX(24px) scale(0.95); } }
        .tl-toast-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; }
        .tl-toast-warning .tl-toast-icon { background: #f59e0b22; color: #f59e0b; }
        .tl-toast-expired .tl-toast-icon { background: #ef444422; color: #ef4444; }
        .tl-toast-title { font-size: 0.78rem; font-weight: 800; color: white; line-height: 1.3; }
        .tl-toast-sub   { font-size: 0.7rem; color: #94a3b8; margin-top: 2px; line-height: 1.4; }

        /* ── Layout ── */
        .page-wrapper { display: flex; width: 100%; height: 100vh; overflow: hidden; }
        .sidebar-col { width: 280px; flex-shrink: 0; padding: 24px; display: none; height: 100vh; overflow: hidden; }
        @media (min-width: 1024px) { .sidebar-col { display: block; } }
        .main-col { flex: 1; min-width: 0; height: 100vh; overflow-y: auto; }
    </style>
</head>
<body>

<?php
$navItems = [
    ['url' => '/sk/dashboard',            'icon' => 'fa-house',           'label' => 'Dashboard',        'key' => 'dashboard'],
    ['url' => '/sk/reservations',         'icon' => 'fa-calendar-alt',    'label' => 'All Reservations', 'key' => 'reservations'],
    ['url' => '/sk/new-reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation',  'key' => 'new-reservation'],
    ['url' => '/sk/user-requests',        'icon' => 'fa-users',           'label' => 'User Requests',    'key' => 'user-requests'],
    ['url' => '/sk/my-reservations',      'icon' => 'fa-calendar',        'label' => 'My Reservations',  'key' => 'my-reservations'],
    ['url' => '/sk/claimed-reservations', 'icon' => 'fa-check-double',    'label' => 'Claimed',          'key' => 'claimed-reservations'],
    ['url' => '/sk/books',                'icon' => 'fa-book-open',       'label' => 'Library',          'key' => 'books'],
    ['url' => '/sk/scanner',              'icon' => 'fa-qrcode',          'label' => 'Scanner',          'key' => 'scanner'],
    ['url' => '/sk/profile',              'icon' => 'fa-regular fa-user', 'label' => 'Profile',          'key' => 'profile'],
];

$upcoming = null;
if (!empty($reservations)) {
    $now = time();
    foreach ($reservations as $r) {
        if (($r['status'] ?? '') === 'approved' && empty($r['claimed'])) {
            $dt = strtotime($r['reservation_date'] . ' ' . ($r['end_time'] ?? '23:59'));
            if ($dt > $now) { $upcoming = $r; break; }
        }
    }
}

$remaining = $remainingReservations ?? 3;
$maxSlots  = 3;
$usedSlots = $maxSlots - $remaining;

$featuredBooks  = $featuredBooks  ?? $dashBooks        ?? [];
$myBorrowings   = $myBorrowings   ?? [];
$availableCount = $availableCount ?? $bookAvailCount   ?? 0;
$totalBooks     = $totalBooks     ?? $bookTotalCount   ?? 0;

$approvalRate = ($total ?? 0) > 0    ? round((($approved ?? 0) / ($total ?? 0)) * 100)    : 0;
$claimRate    = ($approved ?? 0) > 0 ? round((($claimed  ?? 0) / ($approved ?? 0)) * 100) : 0;
?>

<div class="page-wrapper">

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar-col">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
            <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-1">
            <?php foreach ($navItems as $item):
                $isActive = ($page ?? 'dashboard') == $item['key'];
                $cls = $isActive ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
            ?>
                <a href="<?= $item['url'] ?>" class="sidebar-item <?= $cls ?>">
                    <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                    <?= $item['label'] ?>
                    <?php if ($item['key'] === 'reservations' && ($pending ?? 0) > 0): ?>
                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pending ?></span>
                    <?php endif; ?>
                    <?php if ($item['key'] === 'books' && ($pendingBorrowings ?? 0) > 0): ?>
                        <span class="ml-auto bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingBorrowings ?></span>
                    <?php endif; ?>
                    <?php if ($item['key'] === 'user-requests' && ($pendingUserCount ?? 0) > 0): ?>
                        <span class="ml-auto bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingUserCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
            </a>
        </div>
    </div>
</aside>

<!-- ══ MOBILE NAV ══ -->
<nav class="lg:hidden mobile-nav-pill">
    <div class="mobile-scroll-container text-white px-2">
        <?php foreach ($navItems as $item):
            $isActive = ($page ?? 'dashboard') == $item['key'];
            $cls = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
        ?>
            <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
                <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
            <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
        </a>
    </div>
</nav>

<!-- ══ MODALS & OVERLAYS ══ -->
<div id="dateModal" role="dialog" aria-modal="true">
    <div class="modal-backdrop-layer" onclick="closeDateModal()"></div>
    <div class="modal-box">
        <div class="flex items-center justify-between px-7 pt-7 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Schedule</p>
                <h3 id="modalDateTitle" class="text-lg font-black text-slate-900"></h3>
            </div>
            <button onclick="closeDateModal()" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="modalList" class="px-4 py-3 space-y-1"></div>
        <div class="px-7 pb-7 pt-2">
            <button onclick="closeDateModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">Close</button>
        </div>
    </div>
</div>

<div id="notifDropdown" class="notif-dropdown">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <p class="font-black text-slate-800 text-sm">Notifications</p>
        <button onclick="markAllRead()" class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-bold transition">Mark all read</button>
    </div>
    <div id="notifList" class="notif-list">
        <div class="p-6 text-center text-slate-400"><i class="fa-regular fa-bell-slash text-3xl mb-2 block text-slate-200"></i><p class="text-sm font-bold">No notifications</p></div>
    </div>
</div>

<div id="loginToast" class="login-toast">
    <div class="toast-icon" id="toastIcon"></div>
    <div class="flex-1 min-w-0">
        <p class="font-black text-sm leading-tight" id="toastTitle"></p>
        <p class="text-xs text-white/70 mt-0.5" id="toastBody"></p>
    </div>
    <button class="toast-close" onclick="dismissToast()"><i class="fa-solid fa-xmark"></i></button>
</div>
<div id="tl-toast-container"></div>

<!-- ══ MAIN ══ -->
<div class="main-col">
<main class="w-full max-w-screen-xl mx-auto px-4 lg:px-8 pt-6 pb-32">

    <!-- Header -->
    <header class="flex items-start justify-between mb-7 gap-4 fade-up">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                <?php $hour = (int)date('H'); echo $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening'); ?>
            </p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight"><?= esc($user_name ?? session()->get('name') ?? 'SK Officer') ?></h2>
            <p class="text-slate-400 font-medium text-sm mt-0.5"><?= date('l, F j, Y') ?></p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0 flex-wrap justify-end">
            <div class="hidden sm:flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-3 py-2">
                <i class="fa-regular fa-calendar text-green-600 text-xs"></i>
                <span class="text-xs font-bold text-slate-600"><?= date('M j, Y') ?></span>
            </div>
            <?php if (($pending ?? 0) > 0): ?>
                <a href="/sk/reservations" class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 px-3 py-2 rounded-2xl font-bold text-xs hover:bg-amber-100 transition">
                    <i class="fa-solid fa-clock text-xs"></i> <?= $pending ?> pending
                </a>
            <?php endif; ?>
            <a href="<?= base_url('/sk/new-reservation') ?>" class="hidden sm:flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200">
                <i class="fa-solid fa-plus text-xs"></i> Reserve
            </a>
            <div class="relative">
                <button id="bellBtn" onclick="toggleNotif()" class="w-10 h-10 bg-white border border-slate-200 rounded-2xl flex items-center justify-center shadow-sm hover:border-green-300 transition text-slate-500">
                    <i class="fa-regular fa-bell"></i>
                </button>
                <span id="notifBadge" style="display:none" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full border-2 border-white leading-none">0</span>
            </div>
        </div>
    </header>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Timer Banner -->
    <div id="timerBanner" class="timer-banner">
        <div class="flex items-center gap-3 flex-wrap">
            <div id="timerIcon" class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-sm" style="background:rgba(0,0,0,0.08)"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="flex-1 min-w-0">
                <p class="font-black text-sm leading-tight" id="timerTitle">Your reservation ends soon</p>
                <p class="text-[11px] font-medium opacity-75 mt-0.5" id="timerSub"></p>
            </div>
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <div class="timer-digit"><span id="tdHv">00</span><span>hrs</span></div>
                <span class="font-black text-base opacity-50 timer-pulse">:</span>
                <div class="timer-digit"><span id="tdMv">00</span><span>min</span></div>
                <span class="font-black text-base opacity-50 timer-pulse">:</span>
                <div class="timer-digit"><span id="tdSv">00</span><span>sec</span></div>
            </div>
        </div>
        <div class="timer-progress-wrap" id="timerProgressWrap" style="display:none">
            <div class="timer-progress-fill" id="timerProgressFill" style="width:0%"></div>
        </div>
    </div>

    <!-- Upcoming Banner -->
    <?php if ($upcoming): ?>
        <div class="upcoming-pill mb-6 fade-up">
            <div class="w-10 h-10 bg-green-600 rounded-2xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-ticket text-white text-sm"></i></div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Upcoming Reservation</p>
                <p class="font-bold text-green-900 text-sm truncate"><?= esc($upcoming['resource_name'] ?? 'Resource') ?></p>
                <p class="text-xs text-green-700 font-medium"><?= date('M j, Y', strtotime($upcoming['reservation_date'])) ?> · <?= date('g:i A', strtotime($upcoming['start_time'])) ?> – <?= date('g:i A', strtotime($upcoming['end_time'])) ?></p>
            </div>
            <a href="<?= base_url('/sk/my-reservations') ?>" class="text-xs font-black text-green-700 bg-white px-3 py-1.5 rounded-xl border border-green-200 hover:bg-green-50 transition flex-shrink-0">View →</a>
        </div>
    <?php endif; ?>

    <!-- Pending alert -->
    <?php if (($pending ?? 0) > 0): ?>
        <div class="mb-6 px-5 py-3.5 bg-amber-50 border border-amber-200 text-amber-800 font-semibold rounded-2xl flex items-center gap-3 text-sm">
            <i class="fa-solid fa-clock text-amber-500"></i>
            You have <strong class="bg-amber-200 px-1.5 py-0.5 rounded-lg mx-0.5"><?= $pending ?></strong> pending reservation<?= ($pending ?? 0) != 1 ? 's' : '' ?> awaiting approval.
        </div>
    <?php endif; ?>

    <!-- ── Active Sessions Monitor ── -->
    <div class="tl-panel" id="tl-panel">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-timer text-green-600 text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-800 text-sm leading-tight">Active Sessions</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Live time tracking</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-emerald-400"></span>Active</span>
                <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Warning</span>
                <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-red-400"></span>Critical</span>
            </div>
        </div>
        <div id="tl-sessions-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3"></div>
        <p id="tl-no-sessions" class="hidden text-center text-sm text-slate-400 py-6 font-medium">
            <i class="fa-regular fa-circle-pause text-2xl text-slate-200 block mb-2"></i>No active sessions right now
        </p>
    </div>

    <!-- ── Stat Cards ── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3"><div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-layer-group text-blue-500 text-sm"></i></div><span class="text-[10px] font-black text-blue-600 uppercase tracking-wider">+<?= $monthlyTotal ?? 0 ?> mo</span></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total</p>
            <p class="text-3xl font-black text-slate-800 stat-num"><?= $total ?? 0 ?></p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">All time</p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3"><div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i></div><span class="text-[10px] font-black text-emerald-600 uppercase tracking-wider"><?= $approvalRate ?>%</span></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Approved</p>
            <p class="text-3xl font-black text-emerald-600 stat-num"><?= $approved ?? 0 ?></p>
            <div class="prog-bar mt-2"><div class="prog-fill bg-emerald-500" style="width:<?= $approvalRate ?>%"></div></div>
            <p class="text-xs text-slate-400 mt-1.5 font-medium">Approval rate</p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3"><div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center"><i class="fa-regular fa-clock text-amber-500 text-sm"></i></div><span class="text-[10px] font-black text-amber-600 uppercase tracking-wider"><?= $todayTotal ?? 0 ?> today</span></div>
            <div class="grid grid-cols-3 gap-1 text-center mt-1">
                <div><p class="text-xl font-black text-amber-600"><?= $todayPending ?? 0 ?></p><p class="text-[9px] text-slate-400 font-bold">Pending</p></div>
                <div><p class="text-xl font-black text-emerald-600"><?= $todayApproved ?? 0 ?></p><p class="text-[9px] text-slate-400 font-bold">Approved</p></div>
                <div><p class="text-xl font-black text-purple-600"><?= $todayClaimed ?? 0 ?></p><p class="text-[9px] text-slate-400 font-bold">Claimed</p></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3"><div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-check-double text-purple-500 text-sm"></i></div><span class="text-[10px] font-black text-purple-600 uppercase tracking-wider"><?= $claimRate ?>%</span></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Claimed</p>
            <p class="text-3xl font-black text-purple-600 stat-num"><?= $claimed ?? 0 ?></p>
            <div class="prog-bar mt-2"><div class="prog-fill bg-purple-500" style="width:<?= $claimRate ?>%"></div></div>
            <p class="text-xs text-slate-400 mt-1.5 font-medium">Utilization rate</p>
        </div>
    </div>

    <!-- ── Charts ── -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        <div class="dash-card p-5">
            <div class="flex items-center justify-between mb-1">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Reservations Trend</h3><p class="text-[11px] text-slate-400 font-medium">Last 7 days activity</p></div>
                <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-full"><span class="w-2 h-2 rounded-full bg-green-500"></span>Reservations</span>
            </div>
            <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="dash-card p-5">
            <div class="flex items-center justify-between mb-1">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Popular Resources</h3><p class="text-[11px] text-slate-400 font-medium">Most reserved</p></div>
                <span class="text-[10px] font-black bg-green-50 text-green-600 px-2.5 py-1 rounded-full">Top 5</span>
            </div>
            <div class="flex items-center gap-6 mt-4">
                <div style="position:relative;width:160px;height:160px;flex-shrink:0;"><canvas id="resourceChart" width="160" height="160"></canvas></div>
                <div id="resourceLegend" class="flex-1 min-w-0 space-y-3"></div>
            </div>
        </div>
    </div>

    <!-- ── KPI row ── -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <?php foreach ([
            ['Total',    $total ?? 0,    'border-blue-500',    'text-slate-700',   'fa-layer-group',  'text-blue-500'],
            ['Pending',  $pending ?? 0,  'border-amber-500',   'text-amber-600',   'fa-clock',        'text-amber-500'],
            ['Approved', $approved ?? 0, 'border-emerald-500', 'text-emerald-600', 'fa-circle-check', 'text-emerald-500'],
            ['Declined', $declined ?? 0, 'border-rose-500',    'text-rose-600',    'fa-xmark-circle', 'text-rose-500'],
        ] as [$lbl, $val, $border, $color, $ico, $icoc]): ?>
            <div class="kpi-card <?= $border ?>">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $lbl ?></p>
                    <i class="fa-solid <?= $ico ?> text-sm <?= $icoc ?>"></i>
                </div>
                <p class="text-2xl font-black <?= $color ?>"><?= $val ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ── Calendar + Right panel ── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <div class="lg:col-span-2 dash-card p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-calendar-days text-sm"></i></div>
                    <div><h3 class="font-extrabold text-slate-800 text-sm leading-tight">Community Schedule</h3><p class="text-[10px] text-slate-400 font-medium">Click any date to see reservations</p></div>
                </div>
                <div class="hidden sm:flex items-center gap-3 flex-wrap justify-end">
                    <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500"><span class="w-2 h-2 rounded-full flex-shrink-0" style="background:<?= $c ?>"></span><?= $l ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <div class="flex flex-col gap-4">
            <!-- Quick Stats -->
            <div class="rounded-2xl p-4 text-white" style="background: linear-gradient(135deg, #1e3a2f, #16a34a);">
                <div class="flex items-center gap-2 mb-3"><i class="fa-solid fa-bolt text-green-300 text-sm"></i><h3 class="font-black text-sm">Quick Stats</h3></div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-white/10 rounded-xl p-3"><div class="flex items-center gap-1.5 mb-1"><i class="fa-solid fa-chart-line text-green-300 text-[10px]"></i><p class="text-[9px] text-green-200 font-black uppercase tracking-wider">Approval</p></div><p class="text-xl font-black"><?= $approvalRate ?>%</p></div>
                    <div class="bg-white/10 rounded-xl p-3"><div class="flex items-center gap-1.5 mb-1"><i class="fa-solid fa-chart-pie text-green-300 text-[10px]"></i><p class="text-[9px] text-green-200 font-black uppercase tracking-wider">Claimed</p></div><p class="text-xl font-black"><?= $claimRate ?>%</p></div>
                    <div class="bg-white/10 rounded-xl p-3"><div class="flex items-center gap-1.5 mb-1"><i class="fa-solid fa-calendar-check text-green-300 text-[10px]"></i><p class="text-[9px] text-green-200 font-black uppercase tracking-wider">Monthly</p></div><p class="text-xl font-black"><?= $monthlyTotal ?? 0 ?></p></div>
                    <div class="bg-white/10 rounded-xl p-3"><div class="flex items-center gap-1.5 mb-1"><i class="fa-solid fa-layer-group text-green-300 text-[10px]"></i><p class="text-[9px] text-green-200 font-black uppercase tracking-wider">Quota</p></div><p class="text-xl font-black"><?= $usedSlots ?>/<?= $maxSlots ?></p></div>
                </div>
                <?php if (isset($remainingReservations)): ?>
                    <div class="mt-3 pt-3 border-t border-white/10">
                        <div class="fairness-bar bg-white/20 overflow-hidden"><div class="fairness-fill bg-white" style="width:<?= ($usedSlots/$maxSlots)*100 ?>%;opacity:0.8;"></div></div>
                        <p class="text-[10px] text-green-300 mt-1.5 font-medium"><?= $remaining ?> slot<?= $remaining != 1 ? 's' : '' ?> remaining this month</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="dash-card p-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="<?= base_url('/sk/new-reservation') ?>" class="quick-action"><div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-plus text-green-600 text-xs"></i></div><span>New Reservation</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                    <a href="<?= base_url('/sk/my-reservations') ?>" class="quick-action"><div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-calendar text-blue-500 text-xs"></i></div><span>My Reservations</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                    <a href="<?= base_url('/sk/books') ?>" class="quick-action"><div class="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-book-open text-amber-500 text-xs"></i></div><span>Browse Library</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                    <a href="<?= base_url('/sk/profile') ?>" class="quick-action"><div class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-user text-purple-500 text-xs"></i></div><span>View Profile</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="dash-card p-4 flex-1">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Recent Bookings</h3>
                    <a href="<?= base_url('/sk/my-reservations') ?>" class="text-[10px] font-black text-green-600 hover:text-green-700 uppercase tracking-wider">View all →</a>
                </div>
                <?php if (!empty($reservations)): ?>
                    <div class="space-y-1">
                        <?php $recent = array_slice($reservations, 0, 4);
                        foreach ($recent as $res):
                            $s = strtolower($res['status'] ?? 'pending');
                            if (!empty($res['claimed'])) $s = 'claimed';
                            if ($s === 'approved') { $edt = strtotime($res['reservation_date'] . ' ' . ($res['end_time'] ?? '23:59')); if ($edt < time()) $s = 'expired'; }
                            $dt = new DateTime($res['reservation_date']);
                        ?>
                            <a href="<?= base_url('/sk/my-reservations') ?>" class="booking-item">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-[9px] font-black text-slate-400 uppercase leading-none"><?= $dt->format('M') ?></span>
                                    <span class="text-sm font-black text-slate-700 leading-tight"><?= $dt->format('j') ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= esc($res['resource_name'] ?? 'Resource #'.$res['resource_id']) ?></p>
                                    <p class="text-[11px] text-slate-400 font-medium mt-0.5"><?= date('g:i A', strtotime($res['start_time'])) ?> – <?= date('g:i A', strtotime($res['end_time'])) ?></p>
                                </div>
                                <span class="tag tag-<?= $s ?> flex-shrink-0"><?= ucfirst($s) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-6">
                        <i class="fa-regular fa-calendar-xmark text-3xl text-slate-200 mb-2 block"></i>
                        <p class="text-sm text-slate-400 font-medium">No bookings yet</p>
                        <a href="<?= base_url('/sk/new-reservation') ?>" class="inline-flex items-center gap-1 mt-3 text-xs font-bold text-green-600 hover:text-green-700"><i class="fa-solid fa-plus text-[10px]"></i> Make your first reservation</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ══ LIBRARY SECTION ══ -->
    <div class="section-divider">
        <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-book-open text-green-600 text-sm"></i></div>
        <span class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Library Overview</span>
        <div class="section-divider-line"></div>
        <a href="/sk/books" class="text-xs font-black text-green-600 bg-green-50 border border-green-200 px-3 py-1.5 rounded-xl hover:bg-green-100 transition whitespace-nowrap flex-shrink-0">Browse Library →</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Left: Banner + AI Finder -->
        <div class="flex flex-col gap-4">
            <div class="library-banner">
                <div class="relative z-10">
                    <p class="text-[10px] font-black tracking-[0.18em] text-green-300 uppercase mb-1">Community Library</p>
                    <p class="text-2xl font-black text-white leading-tight"><?= $availableCount ?> <span class="text-sm font-semibold text-green-300">available</span></p>
                    <p class="text-green-400 text-xs font-medium mb-4"><?= $totalBooks ?> total titles</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center"><p class="text-xl font-black text-white"><?= count($myBorrowings) ?></p><p class="text-[9px] font-black text-green-300 uppercase tracking-wider mt-0.5">My Borrows</p></div>
                        <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center"><?php $bookPct = $totalBooks > 0 ? round($availableCount / $totalBooks * 100) : 0; ?><p class="text-xl font-black text-white"><?= $bookPct ?>%</p><p class="text-[9px] font-black text-green-300 uppercase tracking-wider mt-0.5">In Stock</p></div>
                    </div>
                </div>
            </div>

            <!-- AI Book Finder -->
            <div class="dash-card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl flex items-center justify-center"><i class="fa-solid fa-wand-magic-sparkles text-green-600 text-xs"></i></div>
                    <div><h3 class="font-extrabold text-slate-800 text-sm leading-tight">AI Book Finder</h3><p class="text-[10px] text-slate-400 font-medium">Describe what you want to read</p></div>
                </div>
                <div class="rag-search-wrap">
                    <i class="fa-solid fa-magnifying-glass rag-search-icon"></i>
                    <input type="text" id="dashRagInput" class="rag-search-input" placeholder="e.g. Filipino history, funny stories, adventure for kids…" onkeydown="if(event.key==='Enter') dashRagSearch()">
                </div>
                <div id="dashRagSkel" style="display:none; margin-top:.75rem">
                    <div class="shimmer" style="width:90%"></div>
                    <div class="shimmer" style="width:72%"></div>
                    <div class="shimmer" style="width:55%"></div>
                </div>
                <div class="ai-suggestion-box" id="dashRagResult">
                    <div class="flex items-start gap-2 mb-2"><i class="fa-solid fa-robot text-green-600 text-xs mt-0.5 flex-shrink-0"></i><p class="text-[10px] font-black text-green-700 uppercase tracking-wider">Librarian Suggestion</p></div>
                    <p class="text-sm text-green-900 font-medium leading-relaxed" id="dashRagText"></p>
                    <div id="dashRagBooks" class="mt-2 flex flex-wrap gap-1.5"></div>
                </div>
                <div id="dashRagErr" class="mt-2 text-xs text-red-500 font-medium" style="display:none"></div>
                <div class="flex items-center justify-between mt-3">
                    <button onclick="dashRagSearch()" id="dashRagBtn" class="flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-xs transition"><i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> Find Books</button>
                    <a href="<?= base_url('/sk/books') ?>" class="text-xs font-bold text-green-600 hover:text-green-700">See full library →</a>
                </div>
            </div>
        </div>

        <!-- Books Catalog -->
        <div class="lg:col-span-2 dash-card p-5">
            <div class="flex items-center justify-between mb-4">
                <div><h3 class="font-extrabold text-slate-800 text-sm">Books Catalog</h3><p class="text-[10px] text-slate-400 font-medium">Availability at a glance</p></div>
                <a href="<?= base_url('/sk/books') ?>" class="text-[10px] font-black text-green-600 bg-green-50 px-2.5 py-1.5 rounded-xl border border-green-200 hover:bg-green-100 transition flex items-center gap-1"><i class="fa-solid fa-arrow-right text-[9px]"></i> View All</a>
            </div>
            <?php
            $genreColors = ['fiction'=>'#3b82f6','fantasy'=>'#8b5cf6','poetry'=>'#ec4899','humor'=>'#f59e0b','history'=>'#78716c','science'=>'#06b6d4','romance'=>'#f43f5e','academic'=>'#0891b2'];
            if (!empty($featuredBooks)):
            ?>
                <div class="grid grid-cols-12 gap-2 px-3 pb-2 border-b border-slate-100 mb-1">
                    <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Title</div>
                    <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Author</div>
                    <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Genre</div>
                    <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Stock</div>
                </div>
                <div class="space-y-0.5">
                    <?php foreach (array_slice($featuredBooks, 0, 9) as $book):
                        $genre = $book['genre'] ?? ''; $spineClr = $genreColors[strtolower($genre)] ?? '#16a34a';
                        $avail = (int)($book['available_copies'] ?? 0); $totalC = (int)($book['total_copies'] ?? 1);
                        $aCls = $avail === 0 ? 'avail-off' : ($avail <= 1 ? 'avail-low' : 'avail-on');
                        $aTxt = $avail === 0 ? 'Out' : ($avail <= 1 ? '1 left' : $avail . ' left');
                    ?>
                    <a href="<?= base_url('/sk/books') ?>" class="book-row grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-5 flex items-center gap-2 min-w-0"><div class="book-spine" style="background:<?= $spineClr ?>"></div><div class="min-w-0"><p class="font-bold text-xs text-slate-800 truncate leading-tight"><?= esc($book['title']) ?></p><p class="text-[10px] text-slate-400 truncate sm:hidden"><?= esc($book['author'] ?? '—') ?></p></div></div>
                        <div class="col-span-3 hidden sm:block"><p class="text-xs text-slate-500 truncate"><?= esc($book['author'] ?? '—') ?></p></div>
                        <div class="col-span-2 hidden sm:block"><?php if (!empty($book['genre'])): ?><span class="text-[10px] font-bold text-slate-500 truncate block"><?= esc($book['genre']) ?></span><?php else: ?><span class="text-[10px] text-slate-300">—</span><?php endif; ?></div>
                        <div class="col-span-2 flex items-center justify-end gap-1.5"><span class="text-[10px] text-slate-400 font-medium hidden sm:inline"><?= $avail ?>/<?= $totalC ?></span><span class="avail-pill <?= $aCls ?>"><?= $aTxt ?></span></div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php if (count($featuredBooks) > 9): ?>
                    <div class="mt-3 pt-3 border-t border-slate-100 text-center"><a href="<?= base_url('/sk/books') ?>" class="text-xs font-bold text-green-600 hover:text-green-700">+<?= count($featuredBooks) - 9 ?> more books →</a></div>
                <?php endif; ?>

                <?php
                $activeBorrows = array_filter($myBorrowings, fn($b) => in_array($b['status'] ?? '', ['approved','pending']));
                $activeBorrows = array_slice(array_values($activeBorrows), 0, 4);
                if (!empty($activeBorrows)):
                ?>
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400">My Active Borrows</h4>
                            <a href="<?= base_url('/sk/books') ?>#mine" class="text-[10px] font-black text-green-600 hover:text-green-700">All →</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php foreach ($activeBorrows as $borrow):
                                $bs = strtolower($borrow['status'] ?? 'pending');
                            ?>
                            <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-slate-200 font-black text-sm text-green-700"><?= mb_strtoupper(mb_substr($borrow['title'] ?? 'B', 0, 1)) ?></div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-xs text-slate-800 truncate"><?= esc($borrow['title'] ?? 'Unknown Book') ?></p>
                                    <?php if (!empty($borrow['due_date']) && $bs === 'approved'): ?><p class="text-[10px] text-slate-400 font-medium">Due <?= date('M j', strtotime($borrow['due_date'])) ?></p><?php endif; ?>
                                </div>
                                <span class="tag borrow-tag-<?= $bs ?> text-[9px] flex-shrink-0"><?= ucfirst($bs) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fa-solid fa-book-open text-4xl text-slate-200 mb-3 block"></i>
                    <p class="text-sm text-slate-400 font-medium">No books in the catalog yet</p>
                    <a href="<?= base_url('/sk/books') ?>" class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-green-600 text-white rounded-xl text-xs font-bold hover:bg-green-700 transition"><i class="fa-solid fa-arrow-right text-[10px]"></i> Browse Library</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>
</div><!-- /.main-col -->
</div><!-- /.page-wrapper -->

<script>
    const STORAGE_KEY         = 'notified_ids_<?= session()->get('user_id') ?>';
    const reservations        = <?= json_encode($reservations ?? []) ?>;
    const allReservationsData = <?= json_encode($allReservations ?? []) ?>;
    const _approved           = reservations.filter(r => r.status === 'approved' && !r.claimed);
    let   notifs              = [];

    // ── Notifications ──
    const getNotifiedIds  = () => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)||'[]'); } catch(e){return[];} };
    const saveNotifiedIds = ids => localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    const timeAgo = t => { const s=Math.floor((Date.now()-new Date(t))/1000); if(s<60) return 'Just now'; if(s<3600) return `${Math.floor(s/60)}m ago`; if(s<86400) return `${Math.floor(s/3600)}h ago`; return `${Math.floor(s/86400)}d ago`; };

    function loadNotifications() {
        const seen = getNotifiedIds();
        notifs = reservations.filter(r => r.status === 'approved').map(r => ({
            id: parseInt(r.id), title: 'Reservation Approved',
            msg: `${r.resource_name||'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
            time: r.updated_at || r.created_at || new Date().toISOString(),
            read: seen.includes(parseInt(r.id)),
        }));
        updateBadge(); renderNotifs();
    }
    function markAllRead() { saveNotifiedIds([...new Set([...getNotifiedIds(),...notifs.map(n=>n.id)])]); notifs.forEach(n=>n.read=true); updateBadge(); renderNotifs(); }
    function updateBadge() { const b=document.getElementById('notifBadge'); const u=notifs.filter(n=>!n.read).length; b.style.display=u>0?'block':'none'; b.textContent=u>9?'9+':u; }
    function renderNotifs() {
        const list=document.getElementById('notifList');
        if(!notifs.length){ list.innerHTML=`<div class="p-6 text-center text-slate-400"><i class="fa-regular fa-bell-slash text-3xl mb-2 block text-slate-200"></i><p class="text-sm font-bold">All caught up!</p></div>`; return; }
        list.innerHTML=notifs.sort((a,b)=>new Date(b.time)-new Date(a.time)).map(n=>`<div class="notif-item ${!n.read?'unread':''}" onclick="markRead(${n.id})"><div class="flex items-start gap-3"><div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-check text-green-600 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 truncate">${n.msg}</p><p class="text-[10px] text-slate-400 mt-1">${timeAgo(n.time)}</p></div>${!n.read?'<span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0 mt-1.5"></span>':''}</div></div>`).join('');
    }
    function markRead(id) { const ids=getNotifiedIds(); if(!ids.includes(id)) saveNotifiedIds([...ids,id]); const n=notifs.find(n=>n.id===id); if(n){n.read=true;updateBadge();renderNotifs();} }
    function toggleNotif() { document.getElementById('notifDropdown').classList.toggle('open'); }
    document.addEventListener('click', e => { const dd=document.getElementById('notifDropdown'); const bell=document.getElementById('bellBtn'); if(!bell.contains(e.target)&&!dd.contains(e.target)) dd.classList.remove('open'); });

    // ── Date Modal ──
    function openDateModal(dateStr, list) {
        const fmt = new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', { weekday:'long', month:'long', day:'numeric', year:'numeric' });
        document.getElementById('modalDateTitle').textContent = fmt;
        const container = document.getElementById('modalList');
        if (!list || !list.length) {
            container.innerHTML = `<div class="py-8 text-center text-slate-400"><i class="fa-solid fa-calendar-xmark text-3xl mb-2 block text-slate-200"></i><p class="text-sm font-bold">No reservations on this date</p></div>`;
        } else {
            const sorted = [...list].sort((a,b) => (a.start_time||'').localeCompare(b.start_time||''));
            container.innerHTML = sorted.map(r => {
                const st = r.claimed ? 'claimed' : (r.status||'pending');
                const colors = { approved:'bg-emerald-100 text-emerald-700', pending:'bg-amber-100 text-amber-700', declined:'bg-rose-100 text-rose-700', claimed:'bg-purple-100 text-purple-700' };
                const cls = colors[st]||'bg-slate-100 text-slate-600';
                const t = r.start_time ? r.start_time.slice(0,5) : '—';
                const et = r.end_time ? r.end_time.slice(0,5) : '';
                return `<div class="date-row"><div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-calendar text-green-600 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800 leading-tight truncate">${r.resource_name||'Resource'}</p><p class="text-xs text-slate-400 mt-0.5">${r.visitor_name||r.full_name||'Guest'}</p></div><div class="text-right flex-shrink-0"><p class="text-xs font-black text-green-600">${t}${et?'–'+et:''}</p><span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase ${cls}">${st}</span></div></div>`;
            }).join('');
        }
        document.getElementById('dateModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDateModal() { document.getElementById('dateModal').classList.remove('open'); document.body.style.overflow = ''; }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

    // ── Init ──
    document.addEventListener('DOMContentLoaded', () => {
        if('Notification' in window) Notification.requestPermission();
        loadNotifications();
        initCountdownBanner();
        showLoginToast();
        tlRender(); setInterval(tlRender, 1000);

        // Trend Chart
        const tCtx = document.getElementById('trendChart')?.getContext('2d');
        if (tCtx) new Chart(tCtx, { type:'line', data:{ labels:<?= json_encode($chartLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']) ?>, datasets:[{ data:<?= json_encode($chartData ?? [0,0,0,0,0,0,0]) ?>, borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.08)', borderWidth:2.5, tension:0.4, fill:true, pointBackgroundColor:'#16a34a', pointRadius:4, pointHoverRadius:6 }] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#1e293b', titleFont:{family:'Plus Jakarta Sans',weight:'700'}, bodyFont:{family:'Plus Jakarta Sans'}, padding:10, cornerRadius:10 } }, scales:{ x:{ grid:{display:false}, ticks:{font:{family:'Plus Jakarta Sans',size:11},color:'#94a3b8'} }, y:{ grid:{color:'#f1f5f9'}, ticks:{font:{family:'Plus Jakarta Sans',size:11},color:'#94a3b8',stepSize:1}, beginAtZero:true } } } });

        // Resource Donut Chart
        const rCtx = document.getElementById('resourceChart')?.getContext('2d');
        const resLabels = <?= json_encode($resourceLabels ?? ['No Data']) ?>;
        const resData   = <?= json_encode($resourceData ?? [1]) ?>;
        const palette   = ['#16a34a','#f59e0b','#8b5cf6','#10b981','#ec4899'];
        if (rCtx) {
            new Chart(rCtx, { type:'doughnut', data:{ labels:resLabels, datasets:[{ data:resData, backgroundColor:palette, borderWidth:0, hoverOffset:4 }] }, options:{ responsive:false, animation:false, cutout:'65%', plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#1e293b', titleFont:{family:'Plus Jakarta Sans',weight:'700'}, bodyFont:{family:'Plus Jakarta Sans'}, padding:10, cornerRadius:10 } } } });
            const legend = document.getElementById('resourceLegend');
            if (legend) legend.innerHTML = resLabels.map((l,i) => `<div class="flex items-center gap-2.5 min-w-0"><span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${palette[i]||'#94a3b8'}"></span><span class="text-sm text-slate-600 truncate flex-1 min-w-0 font-medium">${l}</span><span class="text-sm font-black text-slate-800 flex-shrink-0">${resData[i]}</span></div>`).join('');
        }

        // Calendar
        const byDate = {};
        allReservationsData.forEach(r => { if(!r.reservation_date) return; (byDate[r.reservation_date] = byDate[r.reservation_date]||[]).push(r); });
        const colorMap = { approved:'#10b981', pending:'#fbbf24', declined:'#f87171', canceled:'#f87171', claimed:'#a855f7' };
        const events = allReservationsData.filter(r=>r.reservation_date).map(r => {
            const isClaimed=r.claimed==1; const s=isClaimed?'claimed':(r.status||'pending').toLowerCase();
            return { title:r.resource_name||'Reservation', start:r.reservation_date+(r.start_time?'T'+r.start_time.substring(0,8):''), end:r.reservation_date+(r.end_time?'T'+r.end_time.substring(0,8):''), allDay:!r.start_time, backgroundColor:colorMap[s]||'#94a3b8', borderColor:'transparent', textColor:'#fff' };
        });
        const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView:'dayGridMonth', headerToolbar:{left:'prev,next',center:'title',right:'today'}, events, height:380,
            eventDisplay:'block', eventMaxStack:2,
            dateClick: info => openDateModal(info.dateStr, byDate[info.dateStr]||[]),
            eventClick: info => { const d=info.event.startStr.split('T')[0]; openDateModal(d, byDate[d]||[]); },
            dayCellDidMount: info => {
                const d = info.date.toISOString().split('T')[0];
                const res = byDate[d];
                if(res?.length){ const badge=document.createElement('div'); badge.style.cssText='font-size:9px;font-weight:800;color:white;background:#16a34a;border-radius:999px;width:16px;height:16px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:4px;margin-bottom:2px;'; badge.textContent=res.length; info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge); }
            }
        });
        cal.render();
    });

    // ── Countdown Banner ──
    function initCountdownBanner() {
        const banner=document.getElementById('timerBanner'),titleEl=document.getElementById('timerTitle'),subEl=document.getElementById('timerSub'),hEl=document.getElementById('tdHv'),mEl=document.getElementById('tdMv'),sEl=document.getElementById('tdSv'),iconEl=document.getElementById('timerIcon'),progressWrap=document.getElementById('timerProgressWrap'),progressFill=document.getElementById('timerProgressFill');
        function findTarget() { const now=Date.now(); let active=null,upcoming=null; _approved.forEach(r=>{ if(!r.reservation_date||!r.start_time||!r.end_time) return; const start=new Date(r.reservation_date+'T'+r.start_time).getTime(),end=new Date(r.reservation_date+'T'+r.end_time).getTime(),minsToStart=(start-now)/60000,minsToEnd=(end-now)/60000; if(now>=start&&now<end&&!active) active={r,start,end,mode:'active',minsLeft:minsToEnd}; if(!upcoming&&minsToStart>0&&minsToStart<=30) upcoming={r,start,end,mode:'upcoming',minsLeft:minsToStart}; }); return active||upcoming||null; }
        function tick() {
            const target=findTarget(); if(!target){banner.style.display='none';return;}
            const{r,start,end,mode,minsLeft}=target,now=Date.now(),diff=Math.max(0,(mode==='active'?end:start)-now),h=Math.floor(diff/3600000),m=Math.floor((diff%3600000)/60000),s=Math.floor((diff%60000)/1000);
            hEl.textContent=String(h).padStart(2,'0'); mEl.textContent=String(m).padStart(2,'0'); sEl.textContent=String(s).padStart(2,'0');
            banner.classList.remove('urgent','warning','safe');
            if(mode==='active'){if(minsLeft<=10){banner.classList.add('urgent');iconEl.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i>';}else if(minsLeft<=20){banner.classList.add('warning');iconEl.innerHTML='<i class="fa-solid fa-hourglass-half"></i>';}else{banner.classList.add('safe');iconEl.innerHTML='<i class="fa-solid fa-hourglass-start"></i>';}titleEl.textContent=minsLeft<=10?'⚠️ Your reservation ends very soon!':'Your reservation is active';subEl.textContent=`${r.resource_name||'Resource'} · Ends at ${r.end_time?.substring(0,5)}`;const pct=Math.min(100,Math.max(0,((now-start)/(end-start))*100));progressWrap.style.display='block';progressFill.style.width=pct.toFixed(1)+'%';}else{banner.classList.add('safe');iconEl.innerHTML='<i class="fa-solid fa-bell"></i>';titleEl.textContent='Your reservation starts soon';subEl.textContent=`${r.resource_name||'Resource'} · Starts at ${r.start_time?.substring(0,5)}`;progressWrap.style.display='none';}
            banner.style.display='block';
        }
        tick(); setInterval(tick, 1000);
    }

    // ── Login Toast ──
    function showLoginToast() {
        const key='toast_shown_<?= session()->get('user_id') ?>_'+new Date().toDateString();
        if(sessionStorage.getItem(key)) return;
        sessionStorage.setItem(key,'1');
        const now=Date.now(); let toastData=null;
        _approved.forEach(r=>{ if(!r.reservation_date||!r.start_time||!r.end_time) return; const start=new Date(r.reservation_date+'T'+r.start_time).getTime(),end=new Date(r.reservation_date+'T'+r.end_time).getTime(),minsToStart=(start-now)/60000,today=new Date().toDateString(),resDay=new Date(r.reservation_date+'T00:00:00').toDateString(); if(now>=start&&now<end&&!toastData) toastData={icon:'<i class="fa-solid fa-circle-play" style="color:#22c55e"></i>',bg:'#16a34a',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${r.end_time?.substring(0,5)} — don't forget!`}; if(!toastData&&resDay===today&&minsToStart>0&&minsToStart<=120) toastData={icon:'<i class="fa-solid fa-bell" style="color:#f59e0b"></i>',bg:'#d97706',title:`Reservation in ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${r.start_time?.substring(0,5)} – ${r.end_time?.substring(0,5)}`}; if(!toastData&&resDay===today){const fmt=t=>{const[h,m]=t.split(':');const hr=+h;return `${hr%12||12}:${m} ${hr<12?'AM':'PM'}`;}; toastData={icon:'<i class="fa-solid fa-calendar-check" style="color:#60a5fa"></i>',bg:'#2563eb',title:'You have a reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`};} });
        if(!toastData) return;
        const toast=document.getElementById('loginToast');
        document.getElementById('toastIcon').innerHTML=toastData.icon; document.getElementById('toastIcon').style.background=toastData.bg+'33'; document.getElementById('toastTitle').textContent=toastData.title; document.getElementById('toastBody').textContent=toastData.body;
        setTimeout(()=>toast.classList.add('show'),900); setTimeout(()=>toast.classList.remove('show'),7900);
    }
    function dismissToast() { document.getElementById('loginToast').classList.remove('show'); }

    // ── AI Book Finder ──
    async function dashRagSearch() {
        const query=document.getElementById('dashRagInput').value.trim(); if(query.length<2) return;
        const skel=document.getElementById('dashRagSkel'),res=document.getElementById('dashRagResult'),err=document.getElementById('dashRagErr'),btn=document.getElementById('dashRagBtn');
        res.classList.remove('show'); err.style.display='none'; skel.style.display='block'; btn.disabled=true;
        try {
            const r=await fetch('/rag/suggest',{method:'POST',headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({query})});
            const d=await r.json(); skel.style.display='none'; btn.disabled=false;
            if(d.message&&!d.suggestion){err.textContent=d.message;err.style.display='block';return;} if(d.error&&!d.books){err.textContent=d.error;err.style.display='block';return;}
            document.getElementById('dashRagText').textContent=d.suggestion||'';
            const booksRow=document.getElementById('dashRagBooks'); booksRow.innerHTML='';
            (d.books||[]).slice(0,4).forEach(b=>{ const avail=(b.available_copies||0)>0; const chip=document.createElement('a'); chip.href='/sk/books'; chip.className='inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold border transition '+(avail?'bg-white border-green-200 text-green-700 hover:bg-green-50':'bg-slate-50 border-slate-200 text-slate-400'); chip.innerHTML=`<i class="fa-solid fa-book text-[9px]"></i>${b.title}${avail?'':' <span class="text-[9px]">(out)</span>'}`; booksRow.appendChild(chip); });
            res.classList.add('show');
        } catch(e) { skel.style.display='none'; btn.disabled=false; err.textContent='Network error. Try again.'; err.style.display='block'; }
    }

    // ── Active Session Monitor ──
    const TL_WARN_BEFORE = 5 * 60;
    const TL_CRIT_BEFORE = 2 * 60;
    const TL_LOGGED_KEY  = 'tl_sk_session_seen';
    let tlSessions = {};

    function tlGetSeen()  { try { return JSON.parse(localStorage.getItem(TL_LOGGED_KEY)||'[]'); } catch(e){ return []; } }
    function tlMarkSeen(id) { const ids=tlGetSeen(); if(!ids.includes(id)){ ids.push(id); localStorage.setItem(TL_LOGGED_KEY,JSON.stringify(ids.slice(-500))); } }

     function tlGetActiveSessions() {
        const today = new Date().toISOString().split('T')[0];
        const nowMs = Date.now();
        return reservations.filter(r => {
            if (!r.start_time || !r.end_time || !r.reservation_date) return false;
            if (r.reservation_date !== today) return false;
            const status = (r.status || '').toLowerCase();
            // Must be approved only — not claimed
            if (status !== 'approved') return false;
            // Exclude reservations that have already been claimed (e-ticket scanned)
            if (r.claimed == 1 || r.claimed === true || r.claimed === 'true') return false;
            const startMs = new Date(r.reservation_date + 'T' + r.start_time).getTime();
            const endMs   = new Date(r.reservation_date + 'T' + r.end_time).getTime();
            // Session must have actually started — no early lookahead
            return startMs <= nowMs && endMs >= nowMs - 30 * 60 * 1000;
        });
    }
    function tlFmtCountdown(ms) { if(ms<=0) return 'Ended'; const s=Math.floor(ms/1000),m=Math.floor(s/60),h=Math.floor(m/60); if(h>0) return `${h}h ${m%60}m`; if(m>0) return `${m}m ${s%60}s`; return `${s}s`; }
    function tlGetState(remainMs) { if(remainMs<=0) return 'tl-ended'; if(remainMs<=TL_CRIT_BEFORE*1000) return 'tl-critical'; if(remainMs<=TL_WARN_BEFORE*1000) return 'tl-warning'; return 'tl-ok'; }

    function tlToast(type, title, sub) {
        const c=document.getElementById('tl-toast-container'), t=document.createElement('div');
        t.className=`tl-toast tl-toast-${type}`;
        const icon=type==='warning'?'fa-triangle-exclamation':'fa-clock-rotate-left';
        t.innerHTML=`<div class="tl-toast-icon"><i class="fa-solid ${icon}"></i></div><div class="flex-1 min-w-0"><p class="tl-toast-title">${title}</p><p class="tl-toast-sub">${sub}</p></div><button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;padding:0;font-size:0.8rem;"><i class="fa-solid fa-xmark"></i></button>`;
        c.appendChild(t);
        setTimeout(()=>{ t.classList.add('dismissing'); setTimeout(()=>t.remove(),220); }, 7000);
    }

    function tlRender() {
        const sessions=tlGetActiveSessions(), grid=document.getElementById('tl-sessions-grid'), noSess=document.getElementById('tl-no-sessions'), nowMs=Date.now();
        if(!sessions.length){ grid.innerHTML=''; noSess.classList.remove('hidden'); return; }
        noSess.classList.add('hidden');
        sessions.forEach(r => {
            const endMs=new Date(r.reservation_date+'T'+r.end_time).getTime();
            const startMs=new Date(r.reservation_date+'T'+r.start_time).getTime();
            const totalMs=endMs-startMs, remainMs=endMs-nowMs, elapsedMs=nowMs-startMs;
            const progress=Math.min(100,Math.max(0,(elapsedMs/totalMs)*100)), state=tlGetState(remainMs);
            const name=r.visitor_name||r.full_name||'Guest', resource=r.resource_name||'Resource';
            if(!tlSessions[r.id]) tlSessions[r.id]={warned:false,expired:false};
            const s=tlSessions[r.id];
            if(!s.warned&&remainMs>0&&remainMs<=TL_WARN_BEFORE*1000){ s.warned=true; tlToast('warning',`${name} — 5 min left`,`${resource} session ending soon`); }
            if(!s.expired&&remainMs<=0){ s.expired=true; tlToast('expired',`Session ended`,`${resource} · ${name}`); tlMarkSeen(r.id); }
            let card=document.getElementById(`tl-card-${r.id}`);
            if(!card){ card=document.createElement('div'); card.id=`tl-card-${r.id}`; grid.appendChild(card); }
            const sf=r.start_time?r.start_time.substring(0,5):'–', ef=r.end_time?r.end_time.substring(0,5):'–';
            const usedMin=Math.max(0,Math.floor(elapsedMs/60000));
            card.className=`tl-session-card ${state}`;
            card.innerHTML=`<div class="flex items-start justify-between gap-2 mb-2"><div class="min-w-0 flex-1"><p class="font-extrabold text-slate-800 text-xs truncate">${name}</p><p class="text-[10px] text-slate-400 truncate mt-0.5">${resource}</p></div><span class="tl-countdown flex-shrink-0"><i class="fa-regular fa-clock" style="font-size:0.6rem"></i>${tlFmtCountdown(remainMs)}</span></div><div class="tl-prog-track"><div class="tl-prog-fill" style="width:${progress}%"></div></div><div class="flex items-center justify-between mt-2"><span class="text-[10px] text-slate-400 font-medium">${sf} – ${ef}</span><span class="text-[10px] font-bold text-slate-500">${usedMin} min used</span></div>`;
        });
        const activeIds=sessions.map(r=>`tl-card-${r.id}`);
        Array.from(grid.children).forEach(c=>{ if(!activeIds.includes(c.id)) c.remove(); });
    }
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>