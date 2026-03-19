<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | SK</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => { navigator.serviceWorker.register('/sw.js').catch(()=>{}); });
        }
        window.addEventListener('message', e => { if (e.data?.type==='RESERVATIONS_SYNCED') window.location.reload(); });
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; }

        /* ── Sidebar ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 20px 16px 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s; border-radius: 20px; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Cards ── */
        .dash-card { background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); }
        .stat-card { background: white; border-radius: 20px; padding: 1.25rem; border: 1px solid #e2e8f0; transition: all 0.2s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1); }

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

        /* ── Progress bar ── */
        .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }

        /* ── Pending item ── */
        .pending-item { padding: 0.75rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; transition: all 0.2s; cursor: pointer; }
        .pending-item:hover { background: #fef3c7; border-color: #fbbf24; }

        /* ── Notification ── */
        .notif-bell { position: relative; cursor: pointer; transition: transform 0.2s; }
        .notif-bell:hover { transform: scale(1.08); }
        .notif-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 0.58rem; font-weight: 800; padding: 0.15rem 0.35rem; border-radius: 999px; min-width: 1.1rem; text-align: center; border: 2px solid white; line-height: 1.2; }
        .notif-dropdown { position: fixed; top: 72px; right: 20px; width: 320px; background: white; border-radius: 20px; box-shadow: 0 24px 48px -8px rgba(0,0,0,0.18), 0 0 0 1px rgba(0,0,0,0.06); z-index: 200; display: none; overflow: hidden; animation: dropIn 0.18s ease; }
        @keyframes dropIn { from { opacity:0; transform: translateY(-8px) scale(0.97); } to { opacity:1; transform: none; } }
        .notif-dropdown.show { display: block; }
        .notif-item { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0fdf4; }

        /* ── Modal ── */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(6px); z-index: 300; padding: 1.25rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .modal-card { background: white; border-radius: 28px; width: 100%; max-width: 560px; padding: 2rem; max-height: calc(100vh - 2.5rem); overflow-y: auto; margin: auto; animation: slideUp 0.2s ease; }
        @keyframes slideUp { from { transform:translateY(16px); opacity:0; } to { transform:none; opacity:1; } }
        .date-row { display: flex; align-items: center; gap: 12px; padding: 0.75rem; border-bottom: 1px solid #f1f5f9; border-radius: 12px; transition: background 0.15s; cursor: pointer; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }

        /* ── Tags ── */
        .tag { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
        .tag-pending  { background: #fef3c7; color: #92400e; }
        .tag-approved { background: #dcfce7; color: #166534; }
        .tag-claimed  { background: #f3e8ff; color: #6b21a8; }
        .tag-declined, .tag-cancelled { background: #fee2e2; color: #991b1b; }

        /* ── Offline banner ── */
        #offlineBanner { position: fixed; top: 0; left: 0; right: 0; z-index: 500; background: #f59e0b; color: white; text-align: center; padding: 0.5rem 1rem; font-size: 0.8rem; font-weight: 700; display: none; }

        /* ── Slide-up animation ── */
        .fade-up { animation: slideUp 0.4s ease both; }

        /* ── Library styles ── */
        .library-banner { background: linear-gradient(135deg, #1a3a28 0%, #14532d 55%, #166534 100%); border-radius: 20px; padding: 1.25rem 1.5rem; position: relative; overflow: hidden; border: 1px solid #15803d; }
        .library-banner::before { content: '📚'; position: absolute; right: -10px; top: -10px; font-size: 6.5rem; opacity: 0.07; transform: rotate(14deg); pointer-events: none; line-height: 1; }
        .book-row { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0.75rem; border-radius: 14px; transition: all 0.18s; text-decoration: none; color: inherit; border: 1px solid transparent; }
        .book-row:hover { background: #f0fdf4; border-color: #bbf7d0; }
        .book-spine { width: 3px; border-radius: 4px; align-self: stretch; flex-shrink: 0; min-height: 30px; }
        .avail-pill { font-size: 0.63rem; font-weight: 800; padding: 0.16rem 0.5rem; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }
        .avail-on  { background: #dcfce7; color: #166534; }
        .avail-off { background: #fee2e2; color: #991b1b; }
        .avail-low { background: #fef3c7; color: #92400e; }
        .borrow-req { display: flex; align-items: center; gap: 10px; padding: 0.65rem 0.75rem; border-radius: 14px; background: #eff6ff; border: 1px solid #bfdbfe; transition: all 0.18s; }
        .borrow-req:hover { background: #dbeafe; border-color: #93c5fd; }
        .btn-approve { font-size: 0.65rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 10px; background: #16a34a; color: white; border: none; cursor: pointer; transition: background 0.15s; line-height: 1.4; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject { font-size: 0.65rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 10px; background: #fee2e2; color: #dc2626; border: none; cursor: pointer; transition: background 0.15s; line-height: 1.4; }
        .btn-reject:hover { background: #fecaca; }
        .section-divider { display: flex; align-items: center; gap: 12px; margin: 2rem 0 1.25rem; }
        .section-divider-line { flex: 1; height: 1px; background: #e2e8f0; }

        /* ══════════════════════════════════════════
           TIME LIMIT MONITOR
        ══════════════════════════════════════════ */
        .tl-panel { background: white; border-radius: 24px; border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 1.5rem; }
        .tl-session-card { background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; padding: 0.875rem 1rem; border-left-width: 4px; transition: all 0.2s; position: relative; overflow: hidden; }
        .tl-session-card:hover { box-shadow: 0 4px 12px -2px rgba(0,0,0,0.08); }
        .tl-session-card.tl-ok       { border-left-color: #10b981; }
        .tl-session-card.tl-warning  { border-left-color: #f59e0b; }
        .tl-session-card.tl-critical { border-left-color: #ef4444; }
        .tl-session-card.tl-ended    { border-left-color: #94a3b8; opacity: 0.65; }
        .tl-countdown { display: inline-flex; align-items: center; gap: 5px; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; font-variant-numeric: tabular-nums; letter-spacing: 0.02em; white-space: nowrap; }
        .tl-ok       .tl-countdown  { background: #dcfce7; color: #166534; }
        .tl-warning  .tl-countdown  { background: #fef3c7; color: #92400e; }
        .tl-critical .tl-countdown  { background: #fee2e2; color: #991b1b; }
        .tl-ended    .tl-countdown  { background: #f1f5f9; color: #64748b; }
        .tl-prog-track { height: 4px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 0.5rem; }
        .tl-prog-fill  { height: 100%; border-radius: 999px; transition: width 1s linear; }
        .tl-ok       .tl-prog-fill  { background: #10b981; }
        .tl-warning  .tl-prog-fill  { background: #f59e0b; }
        .tl-critical .tl-prog-fill  { background: #ef4444; }
        .tl-ended    .tl-prog-fill  { background: #94a3b8; }

        /* ── Toast ── */
        #tl-toast-container { position: fixed; bottom: 88px; right: 20px; z-index: 9000; display: flex; flex-direction: column; gap: 8px; pointer-events: none; }
        .tl-toast { background: #1e293b; color: white; border-radius: 16px; padding: 0.875rem 1.1rem; min-width: 280px; max-width: 360px; box-shadow: 0 12px 28px -4px rgba(0,0,0,0.35); display: flex; align-items: flex-start; gap: 10px; pointer-events: auto; cursor: pointer; animation: toastIn 0.3s cubic-bezier(0.34,1.56,0.64,1) both; }
        .tl-toast.dismissing { animation: toastOut 0.2s ease forwards; }
        @keyframes toastIn  { from { opacity:0; transform: translateX(20px) scale(0.95); } to { opacity:1; transform: none; } }
        @keyframes toastOut { to   { opacity:0; transform: translateX(24px) scale(0.95); } }
        .tl-toast-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; }
        .tl-toast-warning .tl-toast-icon { background: #f59e0b22; color: #f59e0b; }
        .tl-toast-expired .tl-toast-icon { background: #ef444422; color: #ef4444; }
        .tl-toast-title { font-size: 0.78rem; font-weight: 800; color: white; line-height: 1.3; }
        .tl-toast-sub   { font-size: 0.7rem;  color: #94a3b8; margin-top: 2px; line-height: 1.4; }
    </style>
</head>
<body class="flex">

    <!-- Offline banner -->
    <div id="offlineBanner"><i class="fa-solid fa-wifi-slash mr-2"></i>You're offline. Changes will sync when back online.</div>
    <script>
        const updateOnline = () => { document.getElementById('offlineBanner').style.display = navigator.onLine ? 'none' : 'block'; };
        window.addEventListener('online', updateOnline); window.addEventListener('offline', updateOnline); updateOnline();
    </script>

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
    $approvalRate    = ($total ?? 0) > 0 ? round((($approved ?? 0) / $total) * 100) : 0;
    $utilizationRate = ($approved ?? 0) > 0 ? round((($claimed ?? 0) / $approved) * 100) : 0;
    $dashBooks         = $dashBooks         ?? [];
    $dashBorrowReqs    = $dashBorrowReqs    ?? [];
    $bookTotalCount    = $bookTotalCount    ?? 0;
    $bookAvailCount    = $bookAvailCount    ?? 0;
    $pendingBorrowings = $pendingBorrowings ?? 0;
    ?>

    <!-- ── Date Modal ── -->
    <div id="dateModal" class="modal-backdrop" onclick="handleModalBackdrop(event)">
        <div class="modal-card">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-lg font-black text-slate-900" id="modalDateTitle"></h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="modalList" class="space-y-1"></div>
            <p id="modalEmpty" class="hidden text-center text-sm text-slate-400 py-8">
                <i class="fa-regular fa-calendar-xmark text-3xl text-slate-200 block mb-2"></i>No reservations for this date.
            </p>
            <button onclick="closeDateModal()" class="mt-5 w-full py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>
        </div>
    </div>

    <!-- ── Notification dropdown ── -->
    <div id="notifDropdown" class="notif-dropdown">
        <div class="p-3 border-b border-slate-100 flex justify-between items-center">
            <span class="font-extrabold text-sm text-slate-800">Notifications</span>
            <button onclick="markAllRead()" class="text-xs text-green-600 hover:text-green-700 font-bold">Mark all read</button>
        </div>
        <div id="notifList" class="max-h-80 overflow-y-auto"></div>
    </div>

    <!-- ── Toast Container ── -->
    <div id="tl-toast-container"></div>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = (isset($page) && $page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] == 'dashboard' && ($pending ?? 0) > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pending ?></span>
                        <?php endif; ?>
                        <?php if ($item['key'] == 'books' && $pendingBorrowings > 0): ?>
                            <span class="ml-auto bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingBorrowings ?></span>
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

    <!-- ── Mobile Nav ── -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = (isset($page) && $page == $item['key']);
                $cls = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-base"></i>
                    <span class="text-[9px] mt-0.5 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                <span class="text-[9px] mt-0.5 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ── Main ── -->
    <main class="flex-1 min-w-0 p-4 lg:p-8 pb-32">

        <!-- Header -->
        <header class="flex items-start justify-between mb-7 gap-4">
            <div class="fade-up">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                    <?php $h = (int)date('H'); echo $h < 12 ? 'Good morning' : ($h < 17 ? 'Good afternoon' : 'Good evening'); ?>
                </p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    <?= htmlspecialchars($sk_name ?? 'SK Officer') ?>
                </h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5"><?= date('l, F j, Y') ?></p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0 flex-wrap justify-end">
                <div class="hidden sm:flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-3 py-2">
                    <i class="fa-regular fa-calendar text-green-600 text-xs"></i>
                    <span class="text-xs font-bold text-slate-600"><?= date('M j, Y') ?></span>
                </div>
                <?php if (($pending ?? 0) > 0): ?>
                    <a href="/sk/user-requests" class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 px-3 py-2 rounded-2xl font-bold text-xs hover:bg-amber-100 transition">
                        <i class="fa-solid fa-clock text-xs"></i> <?= $pending ?> pending
                    </a>
                <?php endif; ?>
                <?php if ($pendingBorrowings > 0): ?>
                    <a href="/sk/books#borrowings" class="flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-3 py-2 rounded-2xl font-bold text-xs hover:bg-blue-100 transition">
                        <i class="fa-solid fa-book text-xs"></i> <?= $pendingBorrowings ?> borrow<?= $pendingBorrowings != 1 ? 's' : '' ?>
                    </a>
                <?php endif; ?>
                <div class="notif-bell" onclick="toggleNotif()">
                    <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-200 hover:border-green-300 transition">
                        <i class="fa-regular fa-bell text-slate-600"></i>
                    </div>
                    <span class="notif-badge" id="notifBadge" style="display:none">0</span>
                </div>
            </div>
        </header>

        <!-- ── Active Session Monitor ── -->
        <div class="tl-panel" id="tl-panel">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-timer text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm leading-tight">Active Sessions</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Live time tracking</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-emerald-400"></span>Active</span>
                    <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Warning</span>
                    <span class="flex items-center gap-1 text-[10px] font-bold text-slate-400"><span class="w-2 h-2 rounded-full bg-red-400"></span>Critical</span>
                </div>
            </div>
            <div id="tl-sessions-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3"></div>
            <p id="tl-no-sessions" class="hidden text-center text-sm text-slate-400 py-6 font-medium">
                <i class="fa-regular fa-circle-pause text-2xl text-slate-200 block mb-2"></i>
                No active sessions right now
            </p>
        </div>

        <!-- ── Analytics Row ── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-layer-group text-blue-500 text-sm"></i></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Total</span>
                </div>
                <p class="text-3xl font-black text-slate-800"><?= $total ?? 0 ?></p>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">+<?= $monthlyTotal ?? 0 ?> this month</p>
            </div>
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i></div>
                    <span class="text-[10px] font-black text-green-600 uppercase tracking-wider"><?= $approvalRate ?>%</span>
                </div>
                <p class="text-3xl font-black text-emerald-600"><?= $approved ?? 0 ?></p>
                <div class="prog-bar mt-2"><div class="prog-fill bg-emerald-500" style="width:<?= $approvalRate ?>%"></div></div>
                <p class="text-xs text-slate-400 mt-1.5 font-medium">Approval rate</p>
            </div>
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center"><i class="fa-regular fa-clock text-amber-500 text-sm"></i></div>
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-wider"><?= $todayTotal ?? 0 ?> today</span>
                </div>
                <div class="grid grid-cols-3 gap-1 text-center mt-1">
                    <div><p class="text-xl font-black text-amber-600"><?= $todayPending ?? 0 ?></p><p class="text-[9px] text-slate-400 font-bold">Pending</p></div>
                    <div><p class="text-xl font-black text-emerald-600"><?= $todayApproved ?? 0 ?></p><p class="text-[9px] text-slate-400 font-bold">Approved</p></div>
                    <div><p class="text-xl font-black text-purple-600"><?= $todayClaimed ?? 0 ?></p><p class="text-[9px] text-slate-400 font-bold">Claimed</p></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-check-double text-purple-500 text-sm"></i></div>
                    <span class="text-[10px] font-black text-purple-600 uppercase tracking-wider"><?= $utilizationRate ?>%</span>
                </div>
                <p class="text-3xl font-black text-purple-600"><?= $claimed ?? 0 ?></p>
                <div class="prog-bar mt-2"><div class="prog-fill bg-purple-500" style="width:<?= $utilizationRate ?>%"></div></div>
                <p class="text-xs text-slate-400 mt-1.5 font-medium">Utilization rate</p>
            </div>
        </div>

        <!-- ── Charts ── -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
            <div class="dash-card p-5">
                <div class="flex items-center justify-between mb-1">
                    <div><h3 class="font-extrabold text-slate-800 text-sm">Reservations Trend</h3><p class="text-[11px] text-slate-400 font-medium">Last 7 days</p></div>
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-full"><span class="w-2 h-2 rounded-full bg-green-500"></span>Reservations</span>
                </div>
                <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
            </div>
            <div class="dash-card p-5">
                <div class="flex items-center justify-between mb-1">
                    <div><h3 class="font-extrabold text-slate-800 text-sm">Popular Resources</h3><p class="text-[11px] text-slate-400 font-medium">Most reserved</p></div>
                    <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full">Top 5</span>
                </div>
                <div class="flex items-center gap-6 mt-4">
                    <div style="position:relative;width:160px;height:160px;flex-shrink:0;"><canvas id="resourceChart" width="160" height="160"></canvas></div>
                    <div id="resourceLegend" class="flex-1 min-w-0 space-y-3"></div>
                </div>
            </div>
        </div>

        <!-- ── Stat badges row ── -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            <?php foreach ([['Total',$total??0,'#3b82f6'],['Pending',$pending??0,'#f59e0b'],['Approved',$approved??0,'#10b981'],['Claimed',$claimed??0,'#a855f7'],['Declined',$declined??0,'#f43f5e']] as [$label,$val,$hex]): ?>
                <div class="stat-card border-l-4" style="border-left-color:<?= $hex ?>">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?= $label ?></p>
                    <p class="text-2xl font-black text-slate-800"><?= $val ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ── Calendar + Right panel ── -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 dash-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-calendar-days text-sm"></i></div>
                        <div><h3 class="font-extrabold text-slate-800 text-sm leading-tight">Community Calendar</h3><p class="text-[10px] text-slate-400 font-medium">Click any date to view reservations</p></div>
                    </div>
                    <div class="hidden sm:flex items-center gap-3 flex-wrap justify-end">
                        <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500"><span class="w-2 h-2 rounded-full" style="background:<?= $c ?>"></span><?= $l ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="dash-card p-4 flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-extrabold text-slate-800 text-sm">Needs Approval</h3>
                        <?php if (($pending ?? 0) > 0): ?>
                            <a href="/sk/user-requests" class="text-[10px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-xl hover:bg-amber-100 transition">View all →</a>
                        <?php endif; ?>
                    </div>
                    <div class="space-y-2 max-h-52 overflow-y-auto pr-0.5">
                        <?php if (!empty($pendingReservations)): ?>
                            <?php foreach (array_slice($pendingReservations, 0, 4) as $res): ?>
                                <a href="/sk/user-requests" class="pending-item block">
                                    <div class="font-bold text-slate-800 text-xs truncate"><?= htmlspecialchars($res['resource_name'] ?? 'Resource') ?></div>
                                    <div class="text-[11px] text-slate-500 mt-0.5"><?= htmlspecialchars($res['visitor_name'] ?? 'Unknown') ?></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1"><i class="fa-regular fa-calendar text-[9px]"></i><?= date('M j', strtotime($res['reservation_date'] ?? 'now')) ?>&nbsp;·&nbsp;<i class="fa-regular fa-clock text-[9px]"></i><?= date('g:i A', strtotime($res['start_time'] ?? '00:00')) ?></div>
                                </a>
                            <?php endforeach; ?>
                            <?php if (count($pendingReservations) > 4): ?>
                                <a href="/sk/user-requests" class="block text-center text-xs text-amber-600 font-bold py-1 hover:text-amber-700">+<?= count($pendingReservations) - 4 ?> more →</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-6"><i class="fa-regular fa-circle-check text-2xl text-slate-200 mb-1 block"></i><p class="text-xs text-slate-400 font-medium">All caught up!</p></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dash-card p-4">
                    <h3 class="font-extrabold text-slate-800 text-sm mb-3">Top Resources</h3>
                    <div class="space-y-2">
                        <?php if (!empty($topResources)): ?>
                            <?php foreach (array_slice($topResources, 0, 4) as $i => $r): ?>
                                <div class="flex items-center gap-3">
                                    <span class="w-5 h-5 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 flex-shrink-0"><?= $i + 1 ?></span>
                                    <span class="flex-1 text-xs font-semibold text-slate-700 truncate"><?= htmlspecialchars($r['name']) ?></span>
                                    <span class="text-xs font-black text-green-600"><?= $r['count'] ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-xs text-slate-400 text-center py-3 font-medium">No data yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="rounded-2xl p-4 text-white" style="background: linear-gradient(135deg, #14532d, #16a34a);">
                    <h3 class="font-bold text-sm mb-3 text-green-100">Performance</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-white/10 rounded-xl p-3"><p class="text-[10px] text-green-200 font-bold uppercase tracking-wider">Approval</p><p class="text-2xl font-black"><?= $approvalRate ?>%</p></div>
                        <div class="bg-white/10 rounded-xl p-3"><p class="text-[10px] text-green-200 font-bold uppercase tracking-wider">Utilization</p><p class="text-2xl font-black"><?= $utilizationRate ?>%</p></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
             LIBRARY SECTION
        ══════════════════════════════════════════ -->
        <div class="section-divider">
            <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-book-open text-green-600 text-sm"></i></div>
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Library Overview</span>
            <div class="section-divider-line"></div>
            <a href="/sk/books" class="text-xs font-black text-green-600 bg-green-50 border border-green-200 px-3 py-1.5 rounded-xl hover:bg-green-100 transition whitespace-nowrap flex-shrink-0">Manage Library →</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="flex flex-col gap-4">
                <div class="library-banner">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black tracking-[0.18em] text-green-300 uppercase mb-1">Book Collection</p>
                        <p class="text-2xl font-black text-white leading-tight"><?= $bookAvailCount ?> <span class="text-sm font-semibold text-green-300">available</span></p>
                        <p class="text-green-400 text-xs font-medium mb-4"><?= $bookTotalCount ?> total titles</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center"><p class="text-xl font-black text-white"><?= $pendingBorrowings ?></p><p class="text-[9px] font-black text-green-300 uppercase tracking-wider mt-0.5">Borrow Requests</p></div>
                            <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center"><?php $pct = $bookTotalCount > 0 ? round($bookAvailCount / $bookTotalCount * 100) : 0; ?><p class="text-xl font-black text-white"><?= $pct ?>%</p><p class="text-[9px] font-black text-green-300 uppercase tracking-wider mt-0.5">In Stock</p></div>
                        </div>
                    </div>
                </div>

                <div class="dash-card p-4 flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-extrabold text-slate-800 text-sm">Borrow Requests</h3>
                        <?php if ($pendingBorrowings > 0): ?><a href="/sk/books#borrowings" class="text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-xl hover:bg-blue-100 transition">All <?= $pendingBorrowings ?> →</a><?php endif; ?>
                    </div>
                    <?php $shownReqs = array_slice(array_values(array_filter($dashBorrowReqs, fn($b) => ($b['status']??'')==='pending')), 0, 4); ?>
                    <?php if (!empty($shownReqs)): ?>
                        <div class="space-y-2">
                            <?php foreach ($shownReqs as $bw): ?>
                            <div class="borrow-req">
                                <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-100 font-black text-sm text-blue-600"><?= mb_strtoupper(mb_substr($bw['book_title']??'B',0,1)) ?></div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-xs text-slate-800 truncate"><?= esc($bw['book_title']??'Unknown Book') ?></p>
                                    <p class="text-[10px] text-slate-500 truncate"><?= esc($bw['resident_name']??'Unknown') ?></p>
                                </div>
                                <div class="flex gap-1 flex-shrink-0">
                                    <form method="post" action="/sk/borrowings/approve/<?= $bw['id'] ?>"><?= csrf_field() ?><button type="submit" class="btn-approve">✓</button></form>
                                    <form method="post" action="/sk/borrowings/reject/<?= $bw['id'] ?>"><?= csrf_field() ?><button type="submit" class="btn-reject">✕</button></form>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5"><i class="fa-regular fa-circle-check text-2xl text-slate-200 mb-1 block"></i><p class="text-xs text-slate-400 font-medium">No pending requests</p></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-2 dash-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div><h3 class="font-extrabold text-slate-800 text-sm">Books Catalog</h3><p class="text-[10px] text-slate-400 font-medium">Availability at a glance</p></div>
                    <a href="/sk/books" class="text-[10px] font-black text-green-600 bg-green-50 px-2.5 py-1.5 rounded-xl border border-green-200 hover:bg-green-100 transition flex items-center gap-1"><i class="fa-solid fa-plus text-[9px]"></i> Add Book</a>
                </div>
                <?php if (!empty($dashBooks)):
                    $genreColors = ['fiction'=>'#3b82f6','fantasy'=>'#8b5cf6','poetry'=>'#ec4899','humor'=>'#f59e0b','history'=>'#78716c','science'=>'#06b6d4','romance'=>'#f43f5e'];
                ?>
                    <div class="grid grid-cols-12 gap-2 px-3 pb-2 border-b border-slate-100 mb-1">
                        <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Title</div>
                        <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Author</div>
                        <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Genre</div>
                        <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Stock</div>
                    </div>
                    <div class="space-y-0.5">
                        <?php foreach (array_slice($dashBooks, 0, 9) as $book):
                            $genre=$book['genre']??''; $spineClr=$genreColors[strtolower($genre)]??'#16a34a';
                            $avail=(int)($book['available_copies']??0); $totalC=(int)($book['total_copies']??1);
                            $aCls=$avail===0?'avail-off':($avail<=1?'avail-low':'avail-on');
                            $aTxt=$avail===0?'Out':($avail<=1?'1 left':$avail.' left');
                        ?>
                        <a href="/sk/books" class="book-row grid grid-cols-12 gap-2 items-center">
                            <div class="col-span-5 flex items-center gap-2 min-w-0"><div class="book-spine" style="background:<?= $spineClr ?>"></div><div class="min-w-0"><p class="font-bold text-xs text-slate-800 truncate leading-tight"><?= esc($book['title']) ?></p><p class="text-[10px] text-slate-400 truncate sm:hidden"><?= esc($book['author']??'—') ?></p></div></div>
                            <div class="col-span-3 hidden sm:block"><p class="text-xs text-slate-500 truncate"><?= esc($book['author']??'—') ?></p></div>
                            <div class="col-span-2 hidden sm:block"><?php if (!empty($book['genre'])): ?><span class="text-[10px] font-bold text-slate-500 truncate block"><?= esc($book['genre']) ?></span><?php else: ?><span class="text-[10px] text-slate-300">—</span><?php endif; ?></div>
                            <div class="col-span-2 flex items-center justify-end gap-1.5"><span class="text-[10px] text-slate-400 font-medium hidden sm:inline"><?= $avail ?>/<?= $totalC ?></span><span class="avail-pill <?= $aCls ?>"><?= $aTxt ?></span></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($dashBooks) > 9): ?>
                        <div class="mt-3 pt-3 border-t border-slate-100 text-center"><a href="/sk/books" class="text-xs font-bold text-green-600 hover:text-green-700">+<?= count($dashBooks) - 9 ?> more books →</a></div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-12"><i class="fa-solid fa-book-open text-4xl text-slate-200 mb-3 block"></i><p class="text-sm text-slate-400 font-medium">No books in the catalog yet</p><a href="/sk/books" class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-green-600 text-white rounded-xl text-xs font-bold hover:bg-green-700 transition"><i class="fa-solid fa-plus text-[10px]"></i> Add the first book</a></div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <script>
        /* ── PHP data ── */
        const allReservationsData = <?= json_encode($allReservations ?? []) ?>;
        const chartLabels = <?= json_encode($chartLabels  ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']) ?>;
        const chartData   = <?= json_encode($chartData    ?? [0,0,0,0,0,0,0]) ?>;
        const resLabels   = <?= json_encode($resourceLabels ?? ['No Data']) ?>;
        const resData     = <?= json_encode($resourceData   ?? [1]) ?>;

        /* ════════════════════════════════════════════
           TIME LIMIT MONITOR
        ════════════════════════════════════════════ */
        const TL_WARN_BEFORE = 5 * 60;
        const TL_CRIT_BEFORE = 2 * 60;

        let tlSessions = {};

        function tlGetActiveSessions() {
            const today = new Date().toISOString().split('T')[0];
            const nowMs = Date.now();
            return allReservationsData.filter(r => {
                if (!r.start_time || !r.end_time || !r.reservation_date) return false;
                if (r.reservation_date !== today) return false;
                const status = (r.status || '').toLowerCase();
                if (!['approved', 'claimed'].includes(status)) return false;
                const startMs = new Date(r.reservation_date + 'T' + r.start_time).getTime();
                const endMs   = new Date(r.reservation_date + 'T' + r.end_time).getTime();
                return startMs <= nowMs + 60000 && endMs >= nowMs - 30 * 60 * 1000;
            });
        }

        function tlFmtCountdown(ms) {
            if (ms <= 0) return 'Ended';
            const s = Math.floor(ms / 1000), m = Math.floor(s / 60), h = Math.floor(m / 60);
            if (h > 0) return `${h}h ${m % 60}m`;
            if (m > 0) return `${m}m ${s % 60}s`;
            return `${s}s`;
        }

        function tlGetState(remainMs) {
            if (remainMs <= 0)                      return 'tl-ended';
            if (remainMs <= TL_CRIT_BEFORE * 1000)  return 'tl-critical';
            if (remainMs <= TL_WARN_BEFORE * 1000)  return 'tl-warning';
            return 'tl-ok';
        }

        function tlToast(type, title, sub) {
            const c = document.getElementById('tl-toast-container');
            const t = document.createElement('div');
            t.className = `tl-toast tl-toast-${type}`;
            const icon = type === 'warning' ? 'fa-triangle-exclamation' : 'fa-clock-rotate-left';
            t.innerHTML = `<div class="tl-toast-icon"><i class="fa-solid ${icon}"></i></div><div class="flex-1 min-w-0"><p class="tl-toast-title">${title}</p><p class="tl-toast-sub">${sub}</p></div><button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;padding:0;font-size:0.8rem;"><i class="fa-solid fa-xmark"></i></button>`;
            c.appendChild(t);
            setTimeout(() => { t.classList.add('dismissing'); setTimeout(() => t.remove(), 220); }, 7000);
        }

        function tlRender() {
            const sessions = tlGetActiveSessions();
            const grid = document.getElementById('tl-sessions-grid');
            const noSess = document.getElementById('tl-no-sessions');
            const nowMs = Date.now();
            if (!sessions.length) { grid.innerHTML = ''; noSess.classList.remove('hidden'); return; }
            noSess.classList.add('hidden');
            sessions.forEach(r => {
                const endMs    = new Date(r.reservation_date + 'T' + r.end_time).getTime();
                const startMs  = new Date(r.reservation_date + 'T' + r.start_time).getTime();
                const totalMs  = endMs - startMs;
                const remainMs = endMs - nowMs;
                const elapsedMs= nowMs - startMs;
                const progress = Math.min(100, Math.max(0, (elapsedMs / totalMs) * 100));
                const state    = tlGetState(remainMs);
                const name     = r.visitor_name || r.full_name || 'Guest';
                const resource = r.resource_name || 'Resource';
                if (!tlSessions[r.id]) tlSessions[r.id] = { warned: false, expired: false };
                const s = tlSessions[r.id];
                if (!s.warned && remainMs > 0 && remainMs <= TL_WARN_BEFORE * 1000) {
                    s.warned = true; tlToast('warning', `${name} — 5 min left`, `${resource} session ending soon`);
                }
                if (!s.expired && remainMs <= 0) {
                    s.expired = true; tlToast('expired', `${name}'s session ended`, `${resource} time limit reached`);
                }
                let card = document.getElementById(`tl-card-${r.id}`);
                if (!card) { card = document.createElement('div'); card.id = `tl-card-${r.id}`; grid.appendChild(card); }
                const startFmt = r.start_time ? r.start_time.substring(0, 5) : '–';
                const endFmt   = r.end_time   ? r.end_time.substring(0, 5)   : '–';
                const usedMin  = Math.max(0, Math.floor(elapsedMs / 60000));
                card.className = `tl-session-card ${state}`;
                card.innerHTML = `<div class="flex items-start justify-between gap-2 mb-2"><div class="min-w-0 flex-1"><p class="font-extrabold text-slate-800 text-xs truncate">${name}</p><p class="text-[10px] text-slate-400 truncate mt-0.5">${resource}</p></div><span class="tl-countdown flex-shrink-0"><i class="fa-regular fa-clock" style="font-size:0.6rem"></i>${tlFmtCountdown(remainMs)}</span></div><div class="tl-prog-track"><div class="tl-prog-fill" style="width:${progress}%"></div></div><div class="flex items-center justify-between mt-2"><span class="text-[10px] text-slate-400 font-medium">${startFmt} – ${endFmt}</span><span class="text-[10px] font-bold text-slate-500">${usedMin} min used</span></div>`;
            });
            const activeIds = sessions.map(r => `tl-card-${r.id}`);
            Array.from(grid.children).forEach(c => { if (!activeIds.includes(c.id)) c.remove(); });
        }

        document.addEventListener('DOMContentLoaded', () => {

            /* Charts */
            const tCtx = document.getElementById('trendChart')?.getContext('2d');
            if (tCtx) {
                new Chart(tCtx, { type: 'line', data: { labels: chartLabels, datasets: [{ data: chartData, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.08)', borderWidth: 2.5, tension: 0.4, fill: true, pointBackgroundColor: '#16a34a', pointRadius: 4, pointHoverRadius: 6 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', titleFont: { family: 'Plus Jakarta Sans', weight: '700' }, bodyFont: { family: 'Plus Jakarta Sans' }, padding: 10, cornerRadius: 10 } }, scales: { x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94a3b8' } }, y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94a3b8', stepSize: 1 }, beginAtZero: true } } } });
            }

            const rCtx = document.getElementById('resourceChart')?.getContext('2d');
            const palette = ['#16a34a', '#f59e0b', '#8b5cf6', '#3b82f6', '#ec4899'];
            if (rCtx) {
                new Chart(rCtx, { type: 'doughnut', data: { labels: resLabels, datasets: [{ data: resData, backgroundColor: palette, borderWidth: 0, hoverOffset: 4 }] }, options: { responsive: false, animation: false, cutout: '65%', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', titleFont: { family: 'Plus Jakarta Sans', weight: '700' }, bodyFont: { family: 'Plus Jakarta Sans' }, padding: 10, cornerRadius: 10 } } } });
                const legend = document.getElementById('resourceLegend');
                if (legend) legend.innerHTML = resLabels.map((l, i) => `<div class="flex items-center gap-2.5 min-w-0"><span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${palette[i] || '#94a3b8'}"></span><span class="text-sm text-slate-600 truncate flex-1 min-w-0 font-medium">${l}</span><span class="text-sm font-black text-slate-800 flex-shrink-0">${resData[i]}</span></div>`).join('');
            }

            /* Calendar */
            const byDate = {};
            allReservationsData.forEach(r => { if (!r.reservation_date) return; if (!byDate[r.reservation_date]) byDate[r.reservation_date] = []; byDate[r.reservation_date].push(r); });
            const colorMap = { approved: '#10b981', pending: '#fbbf24', declined: '#f87171', canceled: '#f87171', claimed: '#a855f7' };
            const events = allReservationsData.filter(r => r.reservation_date).map(r => {
                const s = r.claimed ? 'claimed' : (r.status || 'pending').toLowerCase();
                const d = r.reservation_date.trim();
                return { title: r.resource_name || 'Reservation', start: d + (r.start_time ? 'T' + r.start_time.substring(0, 8) : ''), end: d + (r.end_time ? 'T' + r.end_time.substring(0, 8) : ''), backgroundColor: colorMap[s] || '#94a3b8', borderColor: 'transparent', textColor: '#fff' };
            });
            const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth', headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
                events, height: 360, eventDisplay: 'block', eventMaxStack: 2,
                dateClick: info => openDateModal(info.dateStr, byDate[info.dateStr] || []),
                eventClick: info => { const d = info.event.startStr.split('T')[0]; openDateModal(d, byDate[d] || []); },
                dayCellDidMount: info => {
                    const d = info.date.toISOString().split('T')[0]; const res = byDate[d];
                    if (res?.length) { const badge = document.createElement('div'); badge.style.cssText = 'font-size:8px;font-weight:800;color:white;background:#16a34a;border-radius:999px;width:15px;height:15px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:4px;margin-bottom:2px;'; badge.textContent = res.length; info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge); }
                }
            });
            cal.render();

            /* Start session monitor */
            tlRender();
            setInterval(tlRender, 1000);
        });

        /* ── Date modal ── */
        function openDateModal(date, items) {
            const d = new Date(date + 'T00:00:00');
            document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            document.getElementById('modalDateSub').textContent = items.length ? `${items.length} reservation${items.length > 1 ? 's' : ''}` : '';
            const list = document.getElementById('modalList'); const empty = document.getElementById('modalEmpty');
            list.innerHTML = '';
            if (items.length) {
                empty.classList.add('hidden');
                items.sort((a, b) => (a.start_time || '').localeCompare(b.start_time || '')).forEach(r => {
                    const isClaimed = r.claimed == 1; const s = isClaimed ? 'claimed' : (r.status || 'pending').toLowerCase();
                    const colorMap = { approved: 'bg-emerald-100 text-emerald-700', pending: 'bg-amber-100 text-amber-700', declined: 'bg-rose-100 text-rose-700', claimed: 'bg-purple-100 text-purple-700' };
                    const col = colorMap[s] || 'bg-slate-100 text-slate-600';
                    const t1 = r.start_time ? r.start_time.substring(0, 5) : 'All day'; const t2 = r.end_time ? ` – ${r.end_time.substring(0, 5)}` : '';
                    const row = document.createElement('div'); row.className = 'date-row';
                    row.onclick = () => window.location.href = `/sk/reservations?id=${r.id}`;
                    row.innerHTML = `<div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-calendar text-slate-400 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${r.resource_name || 'Resource'}</p><p class="text-xs text-slate-400 mt-0.5">${r.visitor_name || r.full_name || 'Guest'} · ${t1}${t2}</p></div><span class="text-[10px] font-black uppercase tracking-wide px-2.5 py-1 rounded-full ${col}">${s.charAt(0).toUpperCase() + s.slice(1)}</span>`;
                    list.appendChild(row);
                });
            } else { empty.classList.remove('hidden'); }
            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeDateModal() { document.getElementById('dateModal').classList.remove('show'); document.body.style.overflow = ''; }
        function handleModalBackdrop(e) { if (e.target.classList.contains('modal-backdrop')) closeDateModal(); }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

        /* ── Notifications ── */
        const NOTIF_KEY = 'sk_notif_seen';
        const getSeen = () => { try { return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]'); } catch (e) { return []; } };
        const saveSeen = ids => localStorage.setItem(NOTIF_KEY, JSON.stringify(ids));
        let notifs = [];

        function loadNotifs() {
            const seen = getSeen();
            const pendingRes = <?= json_encode($pendingReservations ?? []) ?>;
            notifs = pendingRes.map(r => ({ id: parseInt(r.id), title: 'New Reservation Request', msg: `${r.resource_name || 'Resource'} — ${r.visitor_name || 'Guest'}`, time: r.created_at || new Date().toISOString(), read: seen.includes(parseInt(r.id)) }));
            updateBadge(); renderNotifs();
        }
        function markAllRead() { saveSeen([...new Set([...getSeen(), ...notifs.map(n => n.id)])]); notifs.forEach(n => n.read = true); updateBadge(); renderNotifs(); }
        function updateBadge() { const badge = document.getElementById('notifBadge'); const unread = notifs.filter(n => !n.read).length; badge.style.display = unread > 0 ? 'block' : 'none'; badge.textContent = unread > 9 ? '9+' : unread; }
        function renderNotifs() {
            const list = document.getElementById('notifList');
            if (!notifs.length) { list.innerHTML = `<div class="text-center py-8 px-4"><i class="fa-regular fa-bell-slash text-3xl text-slate-200 mb-2 block"></i><p class="text-sm text-slate-400 font-medium">No new notifications</p></div>`; return; }
            list.innerHTML = notifs.map(n => `<div class="notif-item ${!n.read ? 'unread' : ''}" onclick="markRead(${n.id})"><div class="flex items-start gap-3"><div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-clock text-amber-600 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 truncate">${n.msg}</p><p class="text-[10px] text-slate-400 mt-0.5">${timeAgo(n.time)}</p></div>${!n.read ? '<span class="w-2 h-2 bg-amber-500 rounded-full flex-shrink-0 mt-1.5"></span>' : ''}</div></div>`).join('');
        }
        function markRead(id) { const ids = getSeen(); if (!ids.includes(id)) saveSeen([...ids, id]); const n = notifs.find(n => n.id === id); if (n) { n.read = true; updateBadge(); renderNotifs(); } }
        function toggleNotif() { document.getElementById('notifDropdown').classList.toggle('show'); }
        document.addEventListener('click', e => { const dd = document.getElementById('notifDropdown'); const bell = document.querySelector('.notif-bell'); if (!bell?.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('show'); });
        const timeAgo = t => { const s = Math.floor((Date.now() - new Date(t)) / 1000); if (s < 60) return 'Just now'; if (s < 3600) return `${Math.floor(s / 60)}m ago`; if (s < 86400) return `${Math.floor(s / 3600)}h ago`; return `${Math.floor(s / 86400)}d ago`; };

        loadNotifs();
    </script>
</body>
</html>