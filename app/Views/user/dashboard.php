<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | <?= esc($user_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        :root {
            --green: #16a34a;
            --green-light: #f0fdf4;
            --green-dark: #14532d;
            --slate-bg: #f8fafc;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--slate-bg); color: #1e293b; margin: 0; }

        /* ── Sidebar ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden; width: 100%;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 480px; background: rgba(20,83,45,0.97);
            backdrop-filter: blur(16px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.4);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Stat cards ── */
        .stat-card { background: white; border-radius: 24px; padding: 1.5rem; border: 1px solid #e2e8f0; transition: all 0.25s ease; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 16px 32px -8px rgba(0,0,0,0.1); }
        .stat-card::after { content: ''; position: absolute; right: -20px; bottom: -20px; width: 80px; height: 80px; border-radius: 50%; opacity: 0.06; transition: all 0.3s; }
        .stat-card:hover::after { transform: scale(1.4); opacity: 0.1; }
        .stat-total::after   { background: #3b82f6; }
        .stat-pending::after { background: #f59e0b; }
        .stat-approved::after{ background: #10b981; }
        .stat-declined::after{ background: #f43f5e; }

        /* ── Dashboard cards ── */
        .dash-card { background: white; border-radius: 28px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); }

        /* ── Calendar ── */
        #calendar { font-size: 0.8rem; }
        .fc .fc-toolbar { flex-wrap: wrap; gap: 0.5rem; }
        .fc-toolbar-title { font-size: 0.95rem !important; font-weight: 800 !important; color: #1e293b !important; }
        .fc-button-primary { background: var(--green) !important; border-color: var(--green) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 700 !important; font-size: 0.75rem !important; padding: 0.3rem 0.65rem !important; }
        .fc-button-primary:hover { background: #15803d !important; }
        .fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active { background: #166534 !important; }
        .fc-daygrid-event { border-radius: 6px !important; font-size: 0.68rem !important; font-weight: 700 !important; padding: 2px 5px !important; border: none !important; cursor: pointer !important; }
        .fc-event:hover { filter: brightness(0.93); }
        .fc-daygrid-day:hover { background-color: #f0fdf4 !important; cursor: pointer; }
        .fc-day-today { background: #f0fdf4 !important; }
        .fc-day-today .fc-daygrid-day-number { color: var(--green) !important; font-weight: 800 !important; }
        .fc-daygrid-day-number { font-size: 0.75rem; font-weight: 600; }

        /* ── Notification ── */
        .notif-bell { position: relative; cursor: pointer; transition: transform 0.2s; }
        .notif-bell:hover { transform: scale(1.08); }
        .notif-badge { position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; font-size: 0.58rem; font-weight: 800; padding: 0.15rem 0.35rem; border-radius: 999px; min-width: 1.1rem; text-align: center; border: 2px solid white; line-height: 1.2; }
        .notif-dropdown { position: fixed; top: 72px; right: 20px; width: 340px; background: white; border-radius: 20px; box-shadow: 0 24px 48px -8px rgba(0,0,0,0.18), 0 0 0 1px rgba(0,0,0,0.06); z-index: 200; display: none; overflow: hidden; animation: dropIn 0.18s ease; }
        @keyframes dropIn { from{opacity:0;transform:translateY(-8px) scale(0.97)} to{opacity:1;transform:none} }
        .notif-dropdown.show { display: block; }
        .notif-item { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0fdf4; }
        .notif-item:last-child { border-bottom: none; }

        /* ── Modal ── */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(6px); z-index: 300; padding: 1.25rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .modal-card { background: white; border-radius: 28px; width: 100%; max-width: 560px; padding: 2rem; max-height: calc(100vh - 2.5rem); overflow-y: auto; margin: auto; animation: slideUp 0.2s ease; }
        @keyframes slideUp { from{transform:translateY(16px);opacity:0} to{transform:none;opacity:1} }

        /* ── Booking list ── */
        .booking-item { display: flex; align-items: center; gap: 12px; padding: 0.875rem 1rem; border-radius: 16px; transition: all 0.2s; text-decoration: none; color: inherit; }
        .booking-item:hover { background: var(--green-light); }

        /* ── Quick action ── */
        .quick-action { display: flex; align-items: center; gap: 12px; padding: 0.875rem 1rem; border-radius: 16px; border: 1.5px solid #e2e8f0; background: white; transition: all 0.2s; cursor: pointer; text-decoration: none; color: inherit; font-weight: 600; font-size: 0.85rem; }
        .quick-action:hover { border-color: var(--green); background: var(--green-light); color: var(--green); transform: translateY(-1px); }

        /* ── Fairness bar ── */
        .fairness-bar { height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .fairness-fill { height: 100%; border-radius: 999px; background: var(--green); transition: width 0.6s cubic-bezier(0.34,1.56,0.64,1); }

        /* ── Date modal rows ── */
        .date-row { display: flex; align-items: center; gap: 12px; padding: 0.75rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; border-radius: 12px; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }

        /* ── Animations ── */
        .fade-in    { animation: fadeIn 0.4s ease both; }
        .fade-in-up { animation: slideUp 0.4s ease both; }
        @keyframes countUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
        .stat-num { animation: countUp 0.5s ease both; }

        .upcoming-pill { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #bbf7d0; border-radius: 20px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 12px; }

        .tag { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
        .tag-pending   { background: #fef3c7; color: #92400e; }
        .tag-approved  { background: #dcfce7; color: #166534; }
        .tag-claimed   { background: #f3e8ff; color: #6b21a8; }
        .tag-declined, .tag-cancelled { background: #fee2e2; color: #991b1b; }
        .tag-expired   { background: #f1f5f9; color: #475569; }

        /* ══ BOOKS SECTION ══ */
        .books-banner { background: linear-gradient(135deg,#1e3a2f 0%,#14532d 50%,#166534 100%); border-radius: 28px; padding: 1.75rem; position: relative; overflow: hidden; border: 1px solid #15803d; }
        .books-banner::before { content: '📚'; position: absolute; right: -10px; top: -10px; font-size: 7rem; opacity: 0.08; transform: rotate(15deg); pointer-events: none; }
        .books-banner::after { content: ''; position: absolute; left: -40px; bottom: -40px; width: 160px; height: 160px; background: radial-gradient(circle,rgba(255,255,255,0.05),transparent); border-radius: 50%; pointer-events: none; }
        .book-spine-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1rem 1.1rem; display: flex; align-items: center; gap: 12px; transition: all 0.22s; text-decoration: none; color: inherit; position: relative; overflow: hidden; }
        .book-spine-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 4px 0 0 4px; transition: width 0.2s; }
        .book-spine-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px -6px rgba(0,0,0,0.12); background: #fafffe; }
        .book-spine-card:hover::before { width: 6px; }
        .genre-fiction::before  { background: #3b82f6; }
        .genre-fantasy::before  { background: #8b5cf6; }
        .genre-poetry::before   { background: #ec4899; }
        .genre-humor::before    { background: #f59e0b; }
        .genre-history::before  { background: #78716c; }
        .genre-science::before  { background: #06b6d4; }
        .genre-default::before  { background: #16a34a; }
        .avail-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .avail-dot.on  { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.18); }
        .avail-dot.off { background: #f87171; box-shadow: 0 0 0 3px rgba(248,113,113,0.18); }
        .rag-search-wrap { position: relative; }
        .rag-search-input { width: 100%; padding: 0.7rem 1rem 0.7rem 2.5rem; border-radius: 16px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.83rem; font-weight: 500; color: #1e293b; outline: none; transition: all 0.2s; }
        .rag-search-input:focus { border-color: #16a34a; background: white; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }
        .rag-search-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.8rem; }
        .ai-suggestion-box { background: linear-gradient(135deg,#f0fdf4,#ecfdf5); border: 1.5px solid #bbf7d0; border-radius: 16px; padding: 0.875rem 1rem; margin-top: 0.75rem; display: none; animation: fadeIn 0.3s ease; }
        .ai-suggestion-box.show { display: block; }
        .shimmer { height: 11px; border-radius: 6px; background: linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%); background-size: 200% 100%; animation: shimmerAnim 1.4s infinite; margin-bottom: 0.45rem; }
        @keyframes shimmerAnim { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .genre-pill { display: inline-block; padding: 0.15rem 0.55rem; border-radius: 999px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .genre-pill-fiction { background: #dbeafe; color: #1e40af; }
        .genre-pill-fantasy { background: #ede9fe; color: #5b21b6; }
        .genre-pill-poetry  { background: #fce7f3; color: #9d174d; }
        .genre-pill-humor   { background: #fef3c7; color: #92400e; }
        .genre-pill-default { background: #f0fdf4; color: #166534; }
        .borrow-tag-pending  { background: #fef3c7; color: #92400e; }
        .borrow-tag-approved { background: #dcfce7; color: #166534; }
        .borrow-tag-returned { background: #dbeafe; color: #1e40af; }
        .borrow-tag-rejected { background: #fee2e2; color: #991b1b; }

        /* ══ TIMER BANNER ══ */
        .timer-banner { display: none; border-radius: 20px; padding: 0.875rem 1.25rem; margin-bottom: 1.25rem; animation: slideDown 0.35s cubic-bezier(0.34,1.56,0.64,1) both; border: 1.5px solid; position: relative; overflow: hidden; }
        .timer-banner::before { content: ''; position: absolute; inset: 0; opacity: 0.04; background: repeating-linear-gradient(45deg,currentColor 0,currentColor 1px,transparent 0,transparent 50%); background-size: 8px 8px; pointer-events: none; }
        .timer-banner.urgent  { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
        .timer-banner.warning { background: #fefce8; border-color: #fde68a; color: #854d0e; }
        .timer-banner.safe    { background: #f0fdf4; border-color: #bbf7d0; color: #14532d; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:none} }
        .timer-progress-wrap { height: 5px; border-radius: 999px; background: rgba(0,0,0,0.1); overflow: hidden; margin-top: 10px; }
        .timer-progress-fill { height: 100%; border-radius: 999px; background: currentColor; opacity: 0.5; transition: width 1s linear; }
        .timer-digit { display: inline-flex; flex-direction: column; align-items: center; background: rgba(0,0,0,0.08); border-radius: 10px; padding: 0.25rem 0.5rem; min-width: 2.6rem; font-variant-numeric: tabular-nums; font-weight: 900; font-size: 1.1rem; line-height: 1; }
        .timer-digit span { font-size: 0.55rem; font-weight: 700; opacity: 0.65; text-transform: uppercase; letter-spacing: 0.07em; margin-top: 2px; }
        .timer-pulse { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

        /* ══ LOGIN TOAST ══ */
        .login-toast { position: fixed; bottom: 96px; left: 50%; transform: translateX(-50%) translateY(20px); background: #1e293b; color: white; border-radius: 20px; padding: 0.875rem 1.25rem; z-index: 500; max-width: 420px; width: calc(100% - 2.5rem); box-shadow: 0 24px 48px -8px rgba(0,0,0,0.35); display: flex; align-items: flex-start; gap: 12px; opacity: 0; pointer-events: none; transition: all 0.4s cubic-bezier(0.34,1.56,0.64,1); border: 1px solid rgba(255,255,255,0.08); }
        .login-toast.show { opacity: 1; pointer-events: auto; transform: translateX(-50%) translateY(0); }
        .toast-icon { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem; }
        .toast-close { margin-left: auto; flex-shrink: 0; width: 24px; height: 24px; border-radius: 8px; background: rgba(255,255,255,0.1); border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; transition: background 0.15s; }
        .toast-close:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books',            'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
        ['url' => '/profile',          'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
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

    $featuredBooks  = $featuredBooks  ?? [];
    $myBorrowings   = $myBorrowings   ?? [];
    $availableCount = $availableCount ?? 0;
    $totalBooks     = $totalBooks     ?? 0;
    ?>

    <!-- ── Sidebar ── -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Resident Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">my<span class="text-green-600">Space.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'bg-green-600 text-white shadow-lg shadow-green-200/50' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm transition-all <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if (isset($remainingReservations)): ?>
                <div class="mx-4 mb-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Monthly Quota</span>
                        <span class="text-xs font-black text-green-600"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                    </div>
                    <div class="fairness-bar">
                        <div class="fairness-fill" style="width:<?= ($usedSlots/$maxSlots)*100 ?>%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium"><?= $remaining ?> slot<?= $remaining != 1 ? 's' : '' ?> remaining this month</p>
                </div>
            <?php endif; ?>

            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- ── Mobile Nav ── -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = ($page == $item['key']);
                $cls = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ── Date Modal ── -->
    <div id="dateModal" class="modal-backdrop" onclick="handleModalBackdrop(event)">
        <div class="modal-card">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-lg font-black text-slate-900" id="modalDateTitle"></h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="modalReservationsList" class="space-y-1"></div>
            <div class="mt-4 text-center text-sm text-slate-400 hidden" id="modalEmptyMessage">
                <i class="fa-regular fa-calendar-xmark text-2xl block mb-2 text-slate-300"></i>
                No reservations for this date.
            </div>
            <button onclick="closeDateModal()" class="mt-5 w-full py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>
        </div>
    </div>

    <!-- ── Login Toast ── -->
    <div id="loginToast" class="login-toast">
        <div class="toast-icon" id="toastIcon"></div>
        <div class="flex-1 min-w-0">
            <p class="font-black text-sm leading-tight" id="toastTitle"></p>
            <p class="text-xs text-white/70 mt-0.5" id="toastBody"></p>
        </div>
        <button class="toast-close" onclick="dismissToast()"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <!-- ── Main ── -->
    <main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

        <!-- Top bar -->
        <header class="flex items-start justify-between mb-8 gap-4">
            <div class="fade-in-up">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">
                    <?php $hour = (int)date('H'); echo $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening'); ?>
                </p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight"><?= esc($user_name) ?></h2>
                <p class="text-slate-400 font-medium text-sm mt-1"><?= date('l, F j, Y') ?></p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="<?= base_url('/reservation') ?>" class="hidden sm:flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200">
                    <i class="fa-solid fa-plus"></i> Reserve
                </a>
                <div class="notif-bell" onclick="toggleNotifications()">
                    <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-200 hover:border-green-300 transition">
                        <i class="fa-regular fa-bell text-slate-600"></i>
                    </div>
                    <span class="notif-badge" id="notificationBadge" style="display:none">0</span>
                </div>
            </div>
        </header>

        <!-- Notification dropdown -->
        <div id="notificationDropdown" class="notif-dropdown">
            <div class="p-3 border-b border-slate-100 flex justify-between items-center">
                <span class="font-extrabold text-sm text-slate-800">Notifications</span>
                <button onclick="markAllAsRead()" class="text-xs text-green-600 hover:text-green-700 font-bold">Mark all read</button>
            </div>
            <div id="notificationList" class="max-h-80 overflow-y-auto"></div>
        </div>

        <!-- Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-in">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- ══ TIMER COUNTDOWN BANNER ══ -->
        <div id="timerBanner" class="timer-banner">
            <div class="flex items-center gap-3 flex-wrap">
                <div id="timerIcon" class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-sm" style="background:rgba(0,0,0,0.08)">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
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

        <!-- Upcoming banner -->
        <?php if ($upcoming): ?>
            <div class="upcoming-pill mb-6 fade-in">
                <div class="w-10 h-10 bg-green-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-ticket text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Upcoming Reservation</p>
                    <p class="font-bold text-green-900 text-sm truncate">
                        <?= esc($upcoming['resource_name'] ?? 'Resource') ?>
                        <?php if (!empty($upcoming['pc_number'])): ?>· <span class="font-normal"><?= esc($upcoming['pc_number']) ?></span><?php endif; ?>
                    </p>
                    <p class="text-xs text-green-700 font-medium">
                        <?= date('M j, Y', strtotime($upcoming['reservation_date'])) ?> &nbsp;·&nbsp;
                        <?= date('g:i A', strtotime($upcoming['start_time'])) ?> – <?= date('g:i A', strtotime($upcoming['end_time'])) ?>
                    </p>
                </div>
                <a href="<?= base_url('/reservation-list') ?>" class="text-xs font-black text-green-700 bg-white px-3 py-1.5 rounded-xl border border-green-200 hover:bg-green-50 transition flex-shrink-0">View →</a>
            </div>
        <?php endif; ?>

        <!-- Pending alert -->
        <?php if ($pending > 0): ?>
            <div class="mb-6 px-5 py-3.5 bg-amber-50 border border-amber-200 text-amber-800 font-semibold rounded-2xl flex items-center gap-3 text-sm fade-in">
                <i class="fa-solid fa-clock text-amber-500"></i>
                You have <strong class="bg-amber-200 px-1.5 py-0.5 rounded-lg mx-0.5"><?= $pending ?></strong>
                pending reservation<?= $pending != 1 ? 's' : '' ?> awaiting approval.
            </div>
        <?php endif; ?>

        <!-- ── Stat Cards ── -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="stat-card stat-total">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-layer-group text-blue-500 text-sm"></i></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total</span>
                </div>
                <p class="text-3xl font-black text-slate-800 stat-num"><?= $total ?></p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">All time</p>
            </div>
            <div class="stat-card stat-pending">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center"><i class="fa-regular fa-clock text-amber-500 text-sm"></i></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pending</span>
                </div>
                <p class="text-3xl font-black text-amber-600 stat-num"><?= $pending ?></p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Awaiting review</p>
            </div>
            <div class="stat-card stat-approved">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Approved</span>
                </div>
                <p class="text-3xl font-black text-emerald-600 stat-num"><?= $approved ?></p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Ready to use</p>
            </div>
            <div class="stat-card stat-declined">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-rose-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-ban text-rose-400 text-sm"></i></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Declined</span>
                </div>
                <p class="text-3xl font-black text-rose-500 stat-num"><?= $declined ?></p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Not approved</p>
            </div>
        </div>

        <!-- ── Main Grid ── -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Calendar -->
            <div class="lg:col-span-2 dash-card p-5 lg:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center"><i class="fa-solid fa-calendar-days text-sm"></i></div>
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-sm leading-tight">Community Schedule</h3>
                            <p class="text-[10px] text-slate-400 font-medium">Click any date to see reservations</p>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-3 flex-wrap justify-end">
                        <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500"><span class="w-2 h-2 rounded-full flex-shrink-0" style="background:<?= $c ?>"></span><?= $l ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Right panel -->
            <div class="flex flex-col gap-5">
                <div class="dash-card p-5">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="<?= base_url('/reservation') ?>" class="quick-action"><div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-plus text-green-600 text-xs"></i></div><span>New Reservation</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                        <a href="<?= base_url('/reservation-list') ?>" class="quick-action"><div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-calendar text-blue-500 text-xs"></i></div><span>My Reservations</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                        <a href="<?= base_url('/books') ?>" class="quick-action"><div class="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-book-open text-amber-500 text-xs"></i></div><span>Browse Library</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                        <a href="<?= base_url('/profile') ?>" class="quick-action"><div class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-user text-purple-500 text-xs"></i></div><span>View Profile</span><i class="fa-solid fa-chevron-right text-xs text-slate-300 ml-auto"></i></a>
                    </div>
                </div>
                <div class="dash-card p-5 flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Recent Bookings</h3>
                        <a href="<?= base_url('/reservation-list') ?>" class="text-[10px] font-black text-green-600 hover:text-green-700 uppercase tracking-wider">View all →</a>
                    </div>
                    <?php if (!empty($reservations)): ?>
                        <div class="space-y-1">
                            <?php $recent = array_slice($reservations, 0, 5);
                            foreach ($recent as $res):
                                $s = strtolower($res['status'] ?? 'pending');
                                if (!empty($res['claimed'])) $s = 'claimed';
                                if ($s === 'approved') { $edt = strtotime($res['reservation_date'] . ' ' . ($res['end_time'] ?? '23:59')); if ($edt < time()) $s = 'expired'; }
                                $dt = new DateTime($res['reservation_date']);
                            ?>
                                <a href="<?= base_url('/reservation-list') ?>" class="booking-item">
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
                        <div class="text-center py-8">
                            <i class="fa-regular fa-calendar-xmark text-3xl text-slate-200 mb-2 block"></i>
                            <p class="text-sm text-slate-400 font-medium">No bookings yet</p>
                            <a href="<?= base_url('/reservation') ?>" class="inline-flex items-center gap-1 mt-3 text-xs font-bold text-green-600 hover:text-green-700"><i class="fa-solid fa-plus text-[10px]"></i> Make your first reservation</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ══ LIBRARY SECTION ══ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 flex flex-col gap-5">
                <div class="books-banner">
                    <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <p class="text-[10px] font-black tracking-[0.2em] text-green-300 uppercase mb-1">Community Library</p>
                            <h3 class="text-xl font-black text-white leading-tight mb-1"><?= $availableCount ?> books available</h3>
                            <p class="text-green-300 text-xs font-medium"><?= $totalBooks ?> total titles in the collection</p>
                        </div>
                        <a href="<?= base_url('/books') ?>" class="flex items-center gap-2 px-5 py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/20 rounded-2xl font-bold text-sm transition backdrop-blur-sm flex-shrink-0">
                            <i class="fa-solid fa-book-open text-xs"></i> Browse All
                        </a>
                    </div>
                    <div class="relative z-10 flex gap-4 mt-4 pt-4 border-t border-white/10">
                        <div class="flex items-center gap-2"><div class="w-7 h-7 bg-white/10 rounded-xl flex items-center justify-center"><i class="fa-solid fa-bookmark text-green-300 text-xs"></i></div><div><p class="text-[10px] font-black text-green-300 uppercase tracking-wider">My Borrows</p><p class="text-sm font-black text-white"><?= count($myBorrowings) ?></p></div></div>
                        <div class="flex items-center gap-2"><div class="w-7 h-7 bg-white/10 rounded-xl flex items-center justify-center"><i class="fa-solid fa-hourglass-half text-amber-300 text-xs"></i></div><div><p class="text-[10px] font-black text-green-300 uppercase tracking-wider">Pending</p><p class="text-sm font-black text-white"><?= count(array_filter($myBorrowings, fn($b) => ($b['status'] ?? '') === 'pending')) ?></p></div></div>
                        <div class="flex items-center gap-2"><div class="w-7 h-7 bg-white/10 rounded-xl flex items-center justify-center"><i class="fa-solid fa-circle-check text-emerald-300 text-xs"></i></div><div><p class="text-[10px] font-black text-green-300 uppercase tracking-wider">Active</p><p class="text-sm font-black text-white"><?= count(array_filter($myBorrowings, fn($b) => ($b['status'] ?? '') === 'approved')) ?></p></div></div>
                    </div>
                </div>
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
                        <a href="<?= base_url('/books') ?>" class="text-xs font-bold text-green-600 hover:text-green-700">See full library →</a>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-5">
                <div class="dash-card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Available Now</h3>
                        <a href="<?= base_url('/books') ?>" class="text-[10px] font-black text-green-600 hover:text-green-700 uppercase tracking-wider">All →</a>
                    </div>
                    <?php if (!empty($featuredBooks)): ?>
                        <div class="space-y-2">
                            <?php foreach (array_slice($featuredBooks, 0, 5) as $book):
                                $genre      = strtolower($book['genre'] ?? 'default');
                                $genreClass = in_array($genre, ['fiction','fantasy','poetry','humor','history','science']) ? 'genre-'.$genre : 'genre-default';
                                $available  = (int)($book['available_copies'] ?? 0) > 0;
                                $gpill      = in_array($genre, ['fiction','fantasy','poetry','humor']) ? 'genre-pill-'.$genre : 'genre-pill-default';
                            ?>
                            <a href="<?= base_url('/books') ?>" class="book-spine-card <?= $genreClass ?>">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-black text-base" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#166534;"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-xs text-slate-800 truncate leading-tight"><?= esc($book['title']) ?></p>
                                    <p class="text-[10px] text-slate-400 font-medium truncate"><?= esc($book['author'] ?? 'Unknown') ?></p>
                                    <span class="genre-pill <?= $gpill ?> mt-0.5 inline-block"><?= esc($book['genre'] ?? 'General') ?></span>
                                </div>
                                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                    <div class="avail-dot <?= $available ? 'on' : 'off' ?>"></div>
                                    <span class="text-[9px] text-slate-400 font-bold"><?= (int)($book['available_copies'] ?? 0) ?> left</span>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6"><i class="fa-solid fa-book text-3xl text-slate-200 mb-2 block"></i><p class="text-xs text-slate-400 font-medium">No books yet</p></div>
                    <?php endif; ?>
                </div>
                <div class="dash-card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">My Borrows</h3>
                        <a href="<?= base_url('/books') ?>#mine" class="text-[10px] font-black text-green-600 hover:text-green-700 uppercase tracking-wider">All →</a>
                    </div>
                    <?php
                    $activeBorrows = array_filter($myBorrowings, fn($b) => in_array($b['status'] ?? '', ['approved','pending']));
                    $activeBorrows = array_slice(array_values($activeBorrows), 0, 4);
                    ?>
                    <?php if (!empty($activeBorrows)): ?>
                        <div class="space-y-2">
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
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fa-regular fa-bookmark text-2xl text-slate-200 mb-2 block"></i>
                            <p class="text-xs text-slate-400 font-medium">No active borrows</p>
                            <a href="<?= base_url('/books') ?>" class="inline-flex items-center gap-1 mt-2 text-[10px] font-bold text-green-600 hover:text-green-700"><i class="fa-solid fa-book-open text-[9px]"></i> Borrow a book</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        const STORAGE_KEY        = 'notified_ids_<?= session()->get('user_id') ?>';
        const reservations       = <?= json_encode($reservations ?? []) ?>;
        const allReservationsData= <?= json_encode($allReservations ?? []) ?>;
        const _approved          = reservations.filter(r => r.status === 'approved' && !r.claimed);
        let   notifications      = [];

        const getNotifiedIds  = () => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)||'[]'); } catch(e){return[];} };
        const saveNotifiedIds = ids => localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));

        function loadNotifications() {
            const seen = getNotifiedIds();
            notifications = reservations.filter(r => r.status === 'approved').map(r => ({
                id: parseInt(r.id), title: 'Reservation Approved',
                msg: `${r.resource_name||'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
                time: r.updated_at || r.created_at || new Date().toISOString(),
                read: seen.includes(parseInt(r.id)),
            }));
            updateBadge(); renderNotifs();
        }
        function markAllAsRead() { saveNotifiedIds([...new Set([...getNotifiedIds(),...notifications.map(n=>n.id)])]); notifications.forEach(n=>n.read=true); updateBadge(); renderNotifs(); }
        function markAsRead(id) { const ids=getNotifiedIds(); if(!ids.includes(id)) saveNotifiedIds([...ids,id]); const n=notifications.find(n=>n.id===id); if(n){n.read=true;updateBadge();renderNotifs();} }
        function updateBadge() { const badge=document.getElementById('notificationBadge'); const unread=notifications.filter(n=>!n.read).length; badge.style.display=unread>0?'block':'none'; badge.textContent=unread>9?'9+':unread; }
        function renderNotifs() {
            const list=document.getElementById('notificationList');
            if(!notifications.length){ list.innerHTML=`<div class="text-center py-8 px-4"><i class="fa-regular fa-bell-slash text-3xl text-slate-200 mb-2 block"></i><p class="text-sm text-slate-400 font-medium">All caught up!</p></div>`; return; }
            list.innerHTML=notifications.sort((a,b)=>new Date(b.time)-new Date(a.time)).map(n=>`<div class="notif-item ${!n.read?'unread':''}" onclick="markAsRead(${n.id})"><div class="flex items-start gap-3"><div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-check text-green-600 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 truncate">${n.msg}</p><p class="text-[10px] text-slate-400 mt-1">${timeAgo(n.time)}</p></div>${!n.read?'<span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0 mt-1.5"></span>':''}</div></div>`).join('');
        }
        function toggleNotifications(){ document.getElementById('notificationDropdown').classList.toggle('show'); }
        document.addEventListener('click', e => { const dd=document.getElementById('notificationDropdown'); const bell=document.querySelector('.notif-bell'); if(!bell.contains(e.target)&&!dd.contains(e.target)) dd.classList.remove('show'); });
        const timeAgo = t => { const s=Math.floor((Date.now()-new Date(t))/1000); if(s<60) return 'Just now'; if(s<3600) return `${Math.floor(s/60)}m ago`; if(s<86400) return `${Math.floor(s/3600)}h ago`; return `${Math.floor(s/86400)}d ago`; };

        function openDateModal(date, items) {
            const d = new Date(date+'T00:00:00');
            document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
            document.getElementById('modalDateSub').textContent   = items.length ? `${items.length} reservation${items.length>1?'s':''}` : '';
            const list=document.getElementById('modalReservationsList'), empty=document.getElementById('modalEmptyMessage');
            list.innerHTML = '';
            if(items.length) {
                empty.classList.add('hidden');
                items.sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).forEach(r => {
                    const isClaimed=r.claimed==1, s=isClaimed?'claimed':(r.status||'pending').toLowerCase();
                    const colorMap={approved:'bg-emerald-100 text-emerald-700',pending:'bg-amber-100 text-amber-700',declined:'bg-rose-100 text-rose-700',canceled:'bg-rose-100 text-rose-700',claimed:'bg-purple-100 text-purple-700'};
                    const col=colorMap[s]||'bg-slate-100 text-slate-600';
                    const t1=r.start_time?r.start_time.substring(0,5):'All day', t2=r.end_time?` – ${r.end_time.substring(0,5)}`:'';
                    const row=document.createElement('div'); row.className='date-row';
                    row.innerHTML=`<div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-calendar text-slate-400 text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${r.resource_name||'Unknown Resource'}</p><p class="text-xs text-slate-400 mt-0.5">${r.visitor_name||r.full_name||'Guest'} · ${t1}${t2}</p></div><span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full ${col}">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
                    list.appendChild(row);
                });
            } else { empty.classList.remove('hidden'); }
            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeDateModal() { document.getElementById('dateModal').classList.remove('show'); document.body.style.overflow=''; }
        function handleModalBackdrop(e) { if(e.target.classList.contains('modal-backdrop')) closeDateModal(); }
        document.addEventListener('keydown', e => { if(e.key==='Escape') closeDateModal(); });

        document.addEventListener('DOMContentLoaded', () => {
            if('Notification' in window) Notification.requestPermission();
            loadNotifications();
            initCountdownBanner();
            showLoginToast();

            const byDate={};
            allReservationsData.forEach(r=>{ if(!r.reservation_date) return; if(!byDate[r.reservation_date]) byDate[r.reservation_date]=[]; byDate[r.reservation_date].push(r); });
            const colorMap={approved:'#10b981',pending:'#fbbf24',declined:'#f87171',canceled:'#f87171',claimed:'#a855f7'};
            const events=allReservationsData.filter(r=>r.reservation_date).map(r=>{ const isClaimed=r.claimed==1; const s=isClaimed?'claimed':(r.status||'pending').toLowerCase(); const d=r.reservation_date.trim(); return{title:r.resource_name||'Reservation',start:d+(r.start_time?'T'+r.start_time.substring(0,8):''),end:d+(r.end_time?'T'+r.end_time.substring(0,8):''),allDay:!r.start_time,backgroundColor:colorMap[s]||'#94a3b8',borderColor:'transparent',textColor:'#fff'}; });
            const cal=new FullCalendar.Calendar(document.getElementById('calendar'),{initialView:'dayGridMonth',headerToolbar:{left:'prev,next',center:'title',right:'today'},events,height:380,eventDisplay:'block',eventMaxStack:2,dateClick:info=>openDateModal(info.dateStr,byDate[info.dateStr]||[]),eventClick:info=>{const d=info.event.startStr.split('T')[0];openDateModal(d,byDate[d]||[]);},dayCellDidMount:info=>{const d=info.date.toISOString().split('T')[0];const res=byDate[d];if(res?.length){const badge=document.createElement('div');badge.style.cssText='font-size:9px;font-weight:800;color:white;background:#16a34a;border-radius:999px;width:16px;height:16px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:4px;margin-bottom:2px;';badge.textContent=res.length;info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);}}});
            cal.render();
        });

        function initCountdownBanner() {
            const banner=document.getElementById('timerBanner'),titleEl=document.getElementById('timerTitle'),subEl=document.getElementById('timerSub'),hEl=document.getElementById('tdHv'),mEl=document.getElementById('tdMv'),sEl=document.getElementById('tdSv'),iconEl=document.getElementById('timerIcon'),progressWrap=document.getElementById('timerProgressWrap'),progressFill=document.getElementById('timerProgressFill');
            function findTarget() { const now=Date.now(); let active=null,upcoming=null; _approved.forEach(r=>{ if(!r.reservation_date||!r.start_time||!r.end_time) return; const start=new Date(r.reservation_date+'T'+r.start_time).getTime(),end=new Date(r.reservation_date+'T'+r.end_time).getTime(),minsToStart=(start-now)/60000,minsToEnd=(end-now)/60000; if(now>=start&&now<end&&!active) active={r,start,end,mode:'active',minsLeft:minsToEnd}; if(!upcoming&&minsToStart>0&&minsToStart<=30) upcoming={r,start,end,mode:'upcoming',minsLeft:minsToStart}; }); return active||upcoming||null; }
            function tick() {
                const target=findTarget(); if(!target){banner.style.display='none';return;}
                const{r,start,end,mode,minsLeft}=target,now=Date.now(),diff=Math.max(0,(mode==='active'?end:start)-now),h=Math.floor(diff/3600000),m=Math.floor((diff%3600000)/60000),s=Math.floor((diff%60000)/1000);
                hEl.textContent=String(h).padStart(2,'0'); mEl.textContent=String(m).padStart(2,'0'); sEl.textContent=String(s).padStart(2,'0');
                banner.classList.remove('urgent','warning','safe');
                if(mode==='active') { if(minsLeft<=10){banner.classList.add('urgent');iconEl.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i>';}else if(minsLeft<=20){banner.classList.add('warning');iconEl.innerHTML='<i class="fa-solid fa-hourglass-half"></i>';}else{banner.classList.add('safe');iconEl.innerHTML='<i class="fa-solid fa-hourglass-start"></i>';} titleEl.textContent=minsLeft<=10?'⚠️ Your reservation ends very soon!':'Your reservation is active'; subEl.textContent=`${r.resource_name||'Resource'} · Ends at ${r.end_time?.substring(0,5)}`; const pct=Math.min(100,Math.max(0,((now-start)/(end-start))*100)); progressWrap.style.display='block'; progressFill.style.width=pct.toFixed(1)+'%'; } else { banner.classList.add('safe'); iconEl.innerHTML='<i class="fa-solid fa-bell"></i>'; titleEl.textContent='Your reservation starts soon'; subEl.textContent=`${r.resource_name||'Resource'} · Starts at ${r.start_time?.substring(0,5)}`; progressWrap.style.display='none'; }
                banner.style.display='block';
            }
            tick(); setInterval(tick,1000);
        }

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
                (d.books||[]).slice(0,4).forEach(b=>{ const avail=(b.available_copies||0)>0; const chip=document.createElement('a'); chip.href='/books'; chip.className='inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold border transition '+(avail?'bg-white border-green-200 text-green-700 hover:bg-green-50':'bg-slate-50 border-slate-200 text-slate-400'); chip.innerHTML=`<i class="fa-solid fa-book text-[9px]"></i>${b.title}${avail?'':' <span class="text-[9px]">(out)</span>'}`; booksRow.appendChild(chip); });
                res.classList.add('show');
            } catch(e) { skel.style.display='none'; btn.disabled=false; err.textContent='Network error. Try again.'; err.style.display='block'; }
        }
    </script>
</body>
</html>