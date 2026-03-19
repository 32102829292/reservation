<?php
date_default_timezone_set('Asia/Manila');
$page = 'login-logs';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden; width: 100%;
        }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav    { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item   { transition: all 0.18s; }
        .sidebar-item.active { background: #2563eb; color: white !important; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.35); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(15,23,42,0.97);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Desktop table ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table  { width: 100%; border-collapse: collapse; min-width: 700px; }
        thead th {
            background: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8;
            padding: 0.9rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
            cursor: pointer; user-select: none;
        }
        thead th:hover { color: #2563eb; }
        thead th .sort-icon { opacity: 0.3; margin-left: 4px; font-size: 0.6rem; }
        thead th.sorted .sort-icon { opacity: 1; color: #2563eb; }
        td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; }
        tbody tr:hover td { background: #eff6ff; }

        /* ── Mobile log card ── */
        .log-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 20px;
            padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s;
            display: flex; flex-direction: column; gap: 0.5rem;
        }
        .log-card:active { transform: scale(0.985); background: #f8fafc; }
        .log-card.active-session { border-left: 4px solid #22c55e; }
        .log-card.closed-session { border-left: 4px solid #e2e8f0; }

        /* ── Stat cards ── */
        .stat-card {
            background: white; border-radius: 20px; padding: 1.1rem 1.25rem;
            border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s; cursor: pointer;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.75rem; border-radius: 10px; font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
        .badge-active   { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-closed   { background: #f1f5f9; color: #64748b; }
        .badge-user     { background: #dbeafe; color: #1e40af; }
        .badge-sk       { background: #f3e8ff; color: #6b21a8; }
        .badge-admin    { background: #fef3c7; color: #92400e; }
        .badge-chairman { background: #fef3c7; color: #92400e; }

        /* ── Search / filter field ── */
        .field {
            background: white; border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 0.7rem 1rem 0.7rem 2.5rem; font-size: 0.875rem;
            font-family: inherit; color: #1e293b; transition: all 0.2s; width: 100%;
        }
        .field:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* ── Quick tabs ── */
        .qtab {
            display: inline-flex; align-items: center; gap: 6px; padding: 0.45rem 1rem;
            border-radius: 12px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
            border: 1px solid #e2e8f0; color: #64748b; background: white; transition: all 0.18s;
            white-space: nowrap; font-family: inherit;
        }
        .qtab:hover  { border-color: #2563eb; color: #2563eb; }
        .qtab.active { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 4px 12px -2px rgba(37,99,235,0.3); }

        /* ── Avatar ── */
        .log-avatar { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.85rem; flex-shrink: 0; }

        /* ── Modal (centered, sm+) ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); }
        .modal-box {
            position: relative; margin: auto; background: white; border-radius: 32px;
            width: 94%; max-width: 460px; max-height: 90vh; overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);
            animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        @keyframes popIn { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* ── Bottom-sheet override on mobile ── */
        @media (max-width: 639px) {
            .overlay { align-items: flex-end; padding: 0; }
            .modal-box {
                max-width: 100%; width: 100%; margin: 0;
                border-radius: 28px 28px 0 0;
                max-height: 92vh;
                animation: slideUp 0.28s cubic-bezier(0.32,0.72,0,1) both;
            }
            @keyframes slideUp {
                from { opacity:0; transform: translateY(100%); }
                to   { opacity:1; transform: translateY(0); }
            }
        }

        /* Drag-handle pill */
        .sheet-handle {
            display: none; width: 40px; height: 5px; border-radius: 999px;
            background: #e2e8f0; margin: 0 auto 12px;
        }
        @media (max-width: 639px) { .sheet-handle { display: block; } }

        /* ── Detail modal rows ── */
        .drow  { display: flex; align-items: flex-start; gap: 12px; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
        .drow:last-child { border-bottom: none; }
        .dicon { width: 36px; height: 36px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
        .dlabel { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 3px; }
        .dvalue { font-size: 0.9rem; font-weight: 700; color: #1e293b; }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }

        /* ── Duration bar ── */
        .dur-bar  { height: 4px; border-radius: 2px; background: #e2e8f0; overflow: hidden; margin-top: 4px; }
        .dur-fill { height: 100%; border-radius: 2px; background: #2563eb; }
    </style>
</head>
<body class="flex min-h-screen">

    <?php
    $navItems = [
        ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
        ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
        ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
        ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
        ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
        ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
        ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
        ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];

    /* ── Pre-process logs ── */
    $processed      = [];
    $totalSessions  = 0;
    $activeSessions = 0;
    $maxDuration    = 1;

    foreach (($logs ?? []) as $log) {
        $loginDt  = new DateTime($log['login_time']);
        $logoutDt = !empty($log['logout_time']) ? new DateTime($log['logout_time']) : null;
        $isActive = ($logoutDt === null);
        $dur = 0; $durLabel = '—';
        if ($logoutDt) {
            $diff = $loginDt->diff($logoutDt);
            $dur  = $diff->h * 60 + $diff->i;
            if ($diff->d) $dur += $diff->d * 1440;
            $durLabel  = $diff->d ? $diff->d . 'd ' : '';
            $durLabel .= $diff->h ? $diff->h . 'h ' : '';
            $durLabel .= $diff->i . 'm';
        } elseif ($isActive) {
            $durLabel = 'Active now';
        }
        if ($dur > $maxDuration) $maxDuration = $dur;
        $role = strtolower($log['role'] ?? 'user');
        $processed[] = array_merge($log, [
            '_login'    => $loginDt,
            '_logout'   => $logoutDt,
            '_active'   => $isActive,
            '_dur'      => $dur,
            '_durLabel' => $durLabel,
            '_role'     => $role,
        ]);
        $totalSessions++;
        if ($isActive) $activeSessions++;
    }
    $closedSessions = $totalSessions - $activeSessions;

    $avatarColors = [
        'bg-blue-100 text-blue-700',
        'bg-purple-100 text-purple-700',
        'bg-emerald-100 text-emerald-700',
        'bg-rose-100 text-rose-700',
        'bg-amber-100 text-amber-700',
    ];

    $roleIcons = ['user' => 'fa-user', 'sk' => 'fa-user-shield', 'admin' => 'fa-crown', 'chairman' => 'fa-crown'];
    ?>

    <!-- ══ DETAIL MODAL ══ -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeDetail()"></div>
        <div class="modal-box">
            <div class="px-7 pt-5 pb-0"><div class="sheet-handle"></div></div>
            <div class="flex items-start justify-between px-7 pt-2 pb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Login Logs</p>
                    <h3 class="text-xl font-black text-slate-900">Session Details</h3>
                </div>
                <button onclick="closeDetail()" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="dHero" class="mx-7 mb-4 bg-gradient-to-br from-blue-50 to-slate-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-4"></div>
            <div id="dStatusBar" class="mx-7 mb-4 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>

            <div class="px-7 pb-2">
                <div class="drow"><div class="dicon"><i class="fa-solid fa-envelope"></i></div>
                    <div><p class="dlabel">Email</p><p id="dEmail" class="dvalue"></p></div>
                </div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div><p class="dlabel">Role</p><p id="dRole" class="dvalue"></p></div>
                </div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-right-to-bracket"></i></div>
                    <div><p class="dlabel">Login Time</p><p id="dLogin" class="dvalue"></p></div>
                </div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-right-from-bracket"></i></div>
                    <div><p class="dlabel">Logout Time</p><p id="dLogout" class="dvalue"></p></div>
                </div>
                <div class="drow"><div class="dicon"><i class="fa-regular fa-clock"></i></div>
                    <div><p class="dlabel">Session Duration</p><p id="dDur" class="dvalue"></p></div>
                </div>
            </div>

            <div class="px-7 py-5 border-t border-slate-100 mt-2">
                <button onclick="closeDetail()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- ══ SIDEBAR ══ -->
    <aside class="hidden lg:block w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800">Chairman<span class="text-blue-600">.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
                ?>
                <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                    <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                    <?= $item['label'] ?>
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
                $btnClass = ($page == $item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
            ?>
            <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
            </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ══ MAIN ══ -->
    <main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 fade-up">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Admin Portal</p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Login Logs</h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5">Authentication history &amp; session tracking</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <?php if ($activeSessions > 0): ?>
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2.5 rounded-2xl font-bold text-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <?= $activeSessions ?> active session<?= $activeSessions > 1 ? 's' : '' ?>
                </div>
                <?php endif; ?>
                <div class="bg-white border border-slate-200 rounded-2xl px-3 py-2 hidden sm:flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-blue-600 text-xs"></i>
                    <span class="text-xs font-bold text-slate-600"><?= date('M j, Y') ?></span>
                </div>
            </div>
        </header>

        <!-- ── Stat cards: single column on xs, 3-col from sm ── -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="stat-card border-blue-400" onclick="setFilter('all')">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Sessions</p>
                    <i class="fa-solid fa-list text-blue-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-slate-700"><?= $totalSessions ?></p>
            </div>
            <div class="stat-card border-emerald-400" onclick="setFilter('active')">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Now</p>
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                </div>
                <p class="text-2xl font-black text-emerald-600"><?= $activeSessions ?></p>
            </div>
            <div class="stat-card border-slate-300" onclick="setFilter('closed')">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Closed</p>
                    <i class="fa-solid fa-circle-xmark text-slate-300 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-slate-500"><?= $closedSessions ?></p>
            </div>
        </div>

        <!-- Search + filter bar -->
        <div class="bg-white border border-slate-200 rounded-[28px] p-4 mb-4 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input id="searchInput" type="text" placeholder="Search by name, email, or role…"
                           class="field" oninput="applyFilters()">
                </div>
                <div class="relative sm:w-44">
                    <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input id="dateInput" type="date" class="field" onchange="applyFilters()">
                </div>
                <button onclick="clearFilters()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center gap-2 flex-shrink-0">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </button>
            </div>
            <!-- Quick tabs -->
            <div class="flex gap-2 mt-3 overflow-x-auto pb-0.5">
                <button class="qtab active" data-tab="all"     onclick="setFilter('all')">
                    <i class="fa-solid fa-list text-xs"></i> All
                    <span class="text-[9px] font-black opacity-60"><?= $totalSessions ?></span>
                </button>
                <button class="qtab" data-tab="active"  onclick="setFilter('active')">
                    <i class="fa-solid fa-circle text-xs text-emerald-500"></i> Active
                    <?php if ($activeSessions > 0): ?>
                    <span class="bg-emerald-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $activeSessions ?></span>
                    <?php endif; ?>
                </button>
                <button class="qtab" data-tab="closed"  onclick="setFilter('closed')">
                    <i class="fa-solid fa-circle-xmark text-xs"></i> Closed
                </button>
                <button class="qtab" data-tab="user"    onclick="setFilter('user')">
                    <i class="fa-solid fa-user text-xs"></i> Users
                </button>
                <button class="qtab" data-tab="sk"      onclick="setFilter('sk')">
                    <i class="fa-solid fa-user-shield text-xs"></i> SK
                </button>
                <button class="qtab" data-tab="chairman" onclick="setFilter('chairman')">
                    <i class="fa-solid fa-crown text-xs"></i> Chairman
                </button>
            </div>
        </div>

        <!-- Result count -->
        <p id="resultCount" class="text-xs font-bold text-slate-400 px-1 mb-4"></p>

        <!-- ── Desktop table (md+) ── -->
        <div class="hidden md:block bg-white border border-slate-200 rounded-[28px] shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">User <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(1)">Role <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(2)">Login Time <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(3)">Logout Time <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th>Duration</th>
                            <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        <?php if (empty($processed)): ?>
                        <tr><td colspan="6">
                            <div class="py-20 text-center">
                                <i class="fa-solid fa-clock text-5xl text-slate-200 mb-4 block"></i>
                                <p class="font-black text-slate-400 text-lg">No login logs yet</p>
                                <p class="text-slate-300 text-sm mt-1">Activity will appear here once users log in.</p>
                            </div>
                        </td></tr>
                        <?php else: ?>
                        <?php foreach ($processed as $i => $log):
                            $col     = $avatarColors[$i % count($avatarColors)];
                            $init    = strtoupper(substr($log['name'] ?? '?', 0, 1));
                            $name    = htmlspecialchars($log['name'] ?? '—');
                            $email   = htmlspecialchars($log['email'] ?? '—');
                            $role    = $log['_role'];
                            $loginF  = $log['_login']->format('M j, Y · g:i A');
                            $logoutF = $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                            $rawDate = $log['_login']->format('Y-m-d');
                            $durPct  = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                            $mdata   = json_encode([
                                'name'    => $name,   'email'    => $email,
                                'role'    => $role,   'login'    => $loginF,
                                'logout'  => $logoutF,'dur'      => $log['_durLabel'],
                                'active'  => $log['_active'],
                                'color'   => $col,    'initials' => $init,
                            ]);
                        ?>
                        <tr class="log-row"
                            data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                            data-role="<?= $role ?>"
                            data-search="<?= strtolower("$name $email $role") ?>"
                            data-date="<?= $rawDate ?>"
                            style="cursor:pointer"
                            onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="log-avatar <?= $col ?>"><?= $init ?></div>
                                    <div>
                                        <p class="font-bold text-sm text-slate-800 leading-tight"><?= $name ?></p>
                                        <p class="text-[11px] text-slate-400 font-semibold mt-0.5"><?= $email ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?= $role ?>">
                                    <i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?> text-[9px]"></i>
                                    <?= ucfirst($role) ?>
                                </span>
                            </td>
                            <td>
                                <p class="text-sm font-bold text-slate-700"><?= $log['_login']->format('M j, Y') ?></p>
                                <p class="text-[11px] text-blue-500 font-semibold mt-0.5"><?= $log['_login']->format('g:i A') ?></p>
                            </td>
                            <td>
                                <?php if ($log['_logout']): ?>
                                <p class="text-sm font-bold text-slate-700"><?= $log['_logout']->format('M j, Y') ?></p>
                                <p class="text-[11px] text-slate-400 font-semibold mt-0.5"><?= $log['_logout']->format('g:i A') ?></p>
                                <?php else: ?>
                                <span class="text-xs text-emerald-500 font-black italic">Still active</span>
                                <?php endif; ?>
                            </td>
                            <td style="min-width:100px">
                                <p class="text-xs font-bold text-slate-600"><?= $log['_durLabel'] ?></p>
                                <?php if ($log['_dur'] > 0): ?>
                                <div class="dur-bar" style="width:80px">
                                    <div class="dur-fill" style="width:<?= $durPct ?>%"></div>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($log['_active']): ?>
                                <span class="badge badge-active">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Active
                                </span>
                                <?php else: ?>
                                <span class="badge badge-closed">
                                    <i class="fa-solid fa-circle-xmark text-[9px]"></i>
                                    Closed
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Footer -->
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <p id="tableFooter" class="text-xs font-bold text-slate-400"></p>
                <p class="text-xs text-slate-300 font-semibold hidden sm:block">Click any row for session details</p>
            </div>
        </div>

        <!-- ── Mobile card list (below md) ── -->
        <?php if (!empty($processed)): ?>
        <div class="md:hidden space-y-3" id="logCardList">
            <?php foreach ($processed as $i => $log):
                $col     = $avatarColors[$i % count($avatarColors)];
                $init    = strtoupper(substr($log['name'] ?? '?', 0, 1));
                $name    = htmlspecialchars($log['name'] ?? '—');
                $email   = htmlspecialchars($log['email'] ?? '—');
                $role    = $log['_role'];
                $loginF  = $log['_login']->format('M j, Y · g:i A');
                $logoutF = $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                $rawDate = $log['_login']->format('Y-m-d');
                $durPct  = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                $mdata   = json_encode([
                    'name'    => $name,   'email'    => $email,
                    'role'    => $role,   'login'    => $loginF,
                    'logout'  => $logoutF,'dur'      => $log['_durLabel'],
                    'active'  => $log['_active'],
                    'color'   => $col,    'initials' => $init,
                ]);
            ?>
            <div class="log-card <?= $log['_active'] ? 'active-session' : 'closed-session' ?>"
                 data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                 data-role="<?= $role ?>"
                 data-search="<?= strtolower("$name $email $role") ?>"
                 data-date="<?= $rawDate ?>"
                 onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                <!-- Top row: avatar + name/email + status badge -->
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="log-avatar <?= $col ?>"><?= $init ?></div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-slate-800 truncate"><?= $name ?></p>
                            <p class="text-[11px] text-slate-400 font-semibold truncate"><?= $email ?></p>
                        </div>
                    </div>
                    <?php if ($log['_active']): ?>
                    <span class="badge badge-active flex-shrink-0">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>Active
                    </span>
                    <?php else: ?>
                    <span class="badge badge-closed flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-[9px]"></i>Closed
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Role + login time row -->
                <div class="flex items-center gap-3 flex-wrap text-xs font-medium text-slate-400 px-0.5">
                    <span class="badge badge-<?= $role ?> !py-0.5">
                        <i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?> text-[9px]"></i>
                        <?= ucfirst($role) ?>
                    </span>
                    <span><i class="fa-solid fa-right-to-bracket text-[10px] mr-1 text-blue-400"></i><?= $log['_login']->format('M j · g:i A') ?></span>
                    <?php if ($log['_logout']): ?>
                    <span><i class="fa-solid fa-right-from-bracket text-[10px] mr-1 text-slate-300"></i><?= $log['_logout']->format('g:i A') ?></span>
                    <?php endif; ?>
                </div>

                <!-- Duration bar -->
                <div class="px-0.5">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-[10px] font-bold text-slate-400"><?= $log['_durLabel'] ?></p>
                    </div>
                    <?php if ($log['_dur'] > 0): ?>
                    <div class="dur-bar">
                        <div class="dur-fill" style="width:<?= $durPct ?>%"></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tap hint -->
                <p class="text-[10px] text-slate-300 font-semibold text-right">Tap for details →</p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="md:hidden bg-white border border-slate-200 rounded-3xl p-16 text-center">
            <i class="fa-solid fa-clock text-4xl text-slate-200 mb-4 block"></i>
            <p class="font-black text-slate-400 text-lg">No login logs yet</p>
            <p class="text-slate-300 text-sm mt-1">Activity will appear here once users log in.</p>
        </div>
        <?php endif; ?>

        <!-- No-results shared message -->
        <div id="noResultsMsg" class="hidden mt-6 text-center py-12 bg-white border border-slate-200 rounded-3xl">
            <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300 mb-2"></i>
            <p class="text-sm font-bold text-slate-500">No sessions match your search.</p>
        </div>

    </main>

    <script>
    var curFilter = 'all';

    /* ── Filter helpers ── */
    function setFilter(f) {
        curFilter = f;
        document.querySelectorAll('.qtab').forEach(function(t) {
            t.classList.toggle('active', t.dataset.tab === f);
        });
        applyFilters();
    }

    function applyFilters() {
        var q    = document.getElementById('searchInput').value.toLowerCase().trim();
        var date = document.getElementById('dateInput').value;

        /* Desktop table rows */
        var tableRows = document.querySelectorAll('#logTableBody .log-row');
        /* Mobile cards */
        var cards = document.querySelectorAll('#logCardList .log-card');

        var n = 0;
        var total = tableRows.length;

        function matchRow(el) {
            var matchFilter =
                curFilter === 'all'     ||
                (curFilter === 'active'   && el.dataset.status === 'active')   ||
                (curFilter === 'closed'   && el.dataset.status === 'closed')   ||
                (curFilter === 'user'     && el.dataset.role   === 'user')     ||
                (curFilter === 'sk'       && el.dataset.role   === 'sk')       ||
                (curFilter === 'chairman' && el.dataset.role   === 'chairman');
            var matchSearch = !q    || el.dataset.search.includes(q);
            var matchDate   = !date || el.dataset.date === date;
            return matchFilter && matchSearch && matchDate;
        }

        tableRows.forEach(function(r) {
            var show = matchRow(r);
            r.style.display = show ? '' : 'none';
            if (show) n++;
        });
        cards.forEach(function(c) {
            c.style.display = matchRow(c) ? '' : 'none';
        });

        document.getElementById('resultCount').textContent =
            'Showing ' + n + ' of ' + total + ' session' + (total !== 1 ? 's' : '');

        var tf = document.getElementById('tableFooter');
        if (tf) tf.textContent = n + ' result' + (n !== 1 ? 's' : '') + ' displayed';

        var noMsg = document.getElementById('noResultsMsg');
        if (noMsg) noMsg.classList.toggle('hidden', n > 0);
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('dateInput').value   = '';
        setFilter('all');
    }

    /* ── Sort (desktop table only) ── */
    var sortDir = {};
    function sortTable(col) {
        sortDir[col] = !sortDir[col];
        var tbody = document.getElementById('logTableBody');
        Array.from(tbody.querySelectorAll('.log-row'))
            .sort(function(a, b) {
                var at = (a.cells[col]?.innerText ?? '').trim().toLowerCase();
                var bt = (b.cells[col]?.innerText ?? '').trim().toLowerCase();
                return sortDir[col] ? at.localeCompare(bt) : bt.localeCompare(at);
            })
            .forEach(function(r) { tbody.appendChild(r); });

        document.querySelectorAll('thead th').forEach(function(th, i) {
            th.classList.toggle('sorted', i === col);
            var ic = th.querySelector('.sort-icon');
            if (ic) ic.className = 'fa-solid ' + (i === col ? (sortDir[col] ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort') + ' sort-icon';
        });
    }

    /* ── Detail modal ── */
    function openDetail(d) {
        document.getElementById('dHero').innerHTML =
            '<div class="log-avatar ' + d.color + ' w-14 h-14 rounded-2xl text-2xl font-black flex-shrink-0 flex items-center justify-center">' + d.initials + '</div>' +
            '<div><p class="font-black text-slate-900 text-lg leading-tight">' + d.name + '</p>' +
            '<p class="text-xs text-slate-400 font-semibold mt-0.5">' + d.email + '</p></div>';

        var bar = document.getElementById('dStatusBar');
        if (d.active) {
            bar.style.background = '#dcfce7'; bar.style.color = '#166534';
            bar.innerHTML = '<span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Session currently active';
        } else {
            bar.style.background = '#f1f5f9'; bar.style.color = '#64748b';
            bar.innerHTML = '<i class="fa-solid fa-circle-xmark text-xs"></i> Session closed';
        }

        document.getElementById('dEmail').textContent  = d.email;
        document.getElementById('dRole').textContent   = d.role.charAt(0).toUpperCase() + d.role.slice(1);
        document.getElementById('dLogin').textContent  = d.login;
        document.getElementById('dLogout').textContent = d.logout;
        document.getElementById('dDur').textContent    = d.dur;

        document.getElementById('detailModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('detailModal').querySelector('.overlay-bg').addEventListener('click', closeDetail);
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDetail(); });

    /* Init */
    applyFilters();
    </script>
</body>
</html>