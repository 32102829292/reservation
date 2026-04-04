<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Activity Logs | Admin</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>(function(){if(localStorage.getItem('admin_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        html.dark-pre body { background: #060e1e; }

        /* ── Sidebar ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37,99,235,0.3); }

        /* ── Mobile nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(30,41,59,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Badges ── */
        .badge {
            padding: 3px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.04em;
            display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;
        }
        .badge-create  { background: #ecfdf5; color: #059669; }
        .badge-approve { background: #eff6ff; color: #2563eb; }
        .badge-approve_user_request { background: #eff6ff; color: #2563eb; }
        .badge-decline { background: #fef2f2; color: #dc2626; }
        .badge-decline_user_request { background: #fef2f2; color: #dc2626; }
        .badge-claim   { background: #f3e8ff; color: #6b21a8; }
        .badge-print   { background: #f0f9ff; color: #0369a1; }
        .badge-default { background: #f1f5f9; color: #64748b; }

        /* ── Table row hover ── */
        .log-row { transition: background 0.12s; }
        .log-row:hover td { background: #f0f7ff; }

        /* ── Stat cards ── */
        .stat-card {
            background: white; border-radius: 20px; padding: 1.1rem 1.25rem;
            border: 1px solid #e2e8f0; border-bottom-width: 3px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -5px rgba(0,0,0,0.08); }

        /* ── Table scroll wrapper ── */
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-scroll::-webkit-scrollbar { height: 4px; }
        .table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* ── Tooltip ── */
        .details-tooltip { position: relative; display: inline-block; }
        .tooltip-text {
            visibility: hidden; opacity: 0; pointer-events: none;
            position: absolute; bottom: calc(100% + 8px); left: 50%;
            transform: translateX(-50%);
            background: #1e293b; color: white;
            padding: 7px 11px; border-radius: 10px;
            font-size: 0.73rem; white-space: nowrap; max-width: min(280px, 80vw);
            white-space: normal; word-break: break-word; line-height: 1.45;
            transition: opacity 0.18s; z-index: 50;
            box-shadow: 0 8px 16px rgba(0,0,0,0.18);
        }
        .tooltip-text::after {
            content: ''; position: absolute; top: 100%; left: 50%; margin-left: -5px;
            border: 5px solid transparent; border-top-color: #1e293b;
        }
        @media (max-width: 640px) {
            .tooltip-text { left: 0; transform: none; }
            .tooltip-text::after { left: 16px; }
        }
        .details-tooltip:hover .tooltip-text { visibility: visible; opacity: 1; }

        /* ── Mobile log card ── */
        .log-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem; transition: box-shadow 0.15s;
        }
        .log-card:hover { box-shadow: 0 6px 20px -4px rgba(0,0,0,0.08); }

        /* ── Footer legend ── */
        .legend-wrap { display: flex; flex-wrap: wrap; gap: 10px 16px; }

        /* ══════════════════════════════════════
           DARK MODE
        ══════════════════════════════════════ */
        body.dark { background-color: #060e1e; color: #e2eaf8; }

        body.dark .sidebar-card { background: #0b1628; border-color: rgba(37,99,235,.12); }
        body.dark .sidebar-header { border-color: rgba(37,99,235,.1); }
        body.dark .sidebar-footer { border-color: rgba(37,99,235,.1); }
        body.dark .sidebar-nav::-webkit-scrollbar-thumb { background: #1e3a5f; }
        body.dark .sidebar-item:not(.active) { color: #93c5fd; }
        body.dark .sidebar-item:not(.active):hover { background: rgba(37,99,235,.1); color: #bfdbfe; }
        body.dark h1.text-slate-800 { color: #e2eaf8 !important; }
        body.dark .text-blue-600 { color: #60a5fa !important; }

        body.dark .mobile-nav-pill { background: rgba(11,22,40,0.98); }

        /* Stat cards */
        body.dark .stat-card { background: #0b1628; border-color: rgba(37,99,235,.12); }
        body.dark .stat-card p.text-slate-400,
        body.dark .stat-card p.text-emerald-500,
        body.dark .stat-card p.text-blue-500,
        body.dark .stat-card p.text-red-500,
        body.dark .stat-card p.text-purple-500 { opacity: 0.85; }
        body.dark .stat-card p.text-slate-800 { color: #e2eaf8 !important; }

        /* Filter bar */
        body.dark .bg-slate-50\/40 { background: rgba(15,23,42,.5) !important; }
        body.dark #searchInput,
        body.dark #actionFilter {
            background: #101e35 !important; border-color: #1e3a5f !important;
            color: #e2eaf8 !important;
        }
        body.dark #searchInput::placeholder { color: #4a6fa5; }

        /* Table container */
        body.dark .bg-white { background: #0b1628 !important; }
        body.dark .border-slate-200 { border-color: #1e3a5f !important; }
        body.dark .border-slate-100 { border-color: rgba(37,99,235,.08) !important; }

        /* Table head */
        body.dark .bg-slate-50\/50 { background: rgba(16,30,53,.8) !important; }
        body.dark thead tr th,
        body.dark .text-slate-400 { color: #4a6fa5 !important; }

        /* Table rows */
        body.dark .log-row td { background: #0b1628; border-color: rgba(37,99,235,.07); }
        body.dark .log-row:hover td { background: #101e35 !important; }
        body.dark .text-slate-700 { color: #cbd5e1 !important; }
        body.dark .text-slate-600 { color: #93a3b8 !important; }
        body.dark .text-slate-800 { color: #e2eaf8 !important; }
        body.dark .text-slate-500 { color: #64748b !important; }
        body.dark .text-slate-300 { color: #1e3a5f !important; }

        /* Dark badges */
        body.dark .badge-create  { background: rgba(5,150,105,.2);  color: #34d399; }
        body.dark .badge-approve { background: rgba(37,99,235,.2);  color: #60a5fa; }
        body.dark .badge-approve_user_request { background: rgba(37,99,235,.2); color: #60a5fa; }
        body.dark .badge-decline { background: rgba(220,38,38,.2);  color: #f87171; }
        body.dark .badge-decline_user_request { background: rgba(220,38,38,.2); color: #f87171; }
        body.dark .badge-claim   { background: rgba(107,33,168,.25); color: #c084fc; }
        body.dark .badge-print   { background: rgba(3,105,161,.2);  color: #38bdf8; }
        body.dark .badge-default { background: rgba(100,116,139,.2); color: #94a3b8; }

        /* Avatars */
        body.dark .bg-emerald-50 { background: rgba(5,150,105,.15) !important; }
        body.dark .bg-blue-50    { background: rgba(37,99,235,.15)  !important; }
        body.dark .bg-red-50     { background: rgba(220,38,38,.15)  !important; }
        body.dark .bg-purple-50  { background: rgba(107,33,168,.15) !important; }
        body.dark .bg-sky-50     { background: rgba(3,105,161,.15)  !important; }
        body.dark .bg-slate-100  { background: rgba(100,116,139,.15)!important; }
        body.dark .text-emerald-600 { color: #34d399 !important; }
        body.dark .text-blue-600    { color: #60a5fa !important; }
        body.dark .text-red-500     { color: #f87171 !important; }
        body.dark .text-purple-600  { color: #c084fc !important; }
        body.dark .text-sky-600     { color: #38bdf8 !important; }
        body.dark .text-slate-500   { color: #64748b !important; }

        /* Mobile cards */
        body.dark .mobile-log-card { background: #0b1628; border-color: #1e3a5f; }

        /* No results */
        body.dark #noResults { color: #4a6fa5; }

        /* Footer */
        body.dark .text-slate-400 { color: #4a6fa5 !important; }
        body.dark footer,
        body.dark .legend-wrap span { color: #4a6fa5; }

        /* Tooltip dark */
        body.dark .tooltip-text { background: #1e3a5f; }
        body.dark .tooltip-text::after { border-top-color: #1e3a5f; }

        /* Header */
        body.dark .text-slate-900 { color: #e2eaf8 !important; }
        body.dark .text-slate-500 { color: #64748b !important; }

        @media print {
            aside, .mobile-nav-pill, header button, .table-scroll::-webkit-scrollbar { display: none !important; }
            main { margin: 0 !important; padding: 1rem !important; }
            .mobile-cards { display: none !important; }
            .desktop-table { display: block !important; }
        }
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
        ['url' => '/admin/books',               'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
        ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
        ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
        ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
        ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];

    $actionLabel = function(string $a): string {
        return match($a) {
            'create'               => 'Created',
            'approve','approve_user_request' => 'Approved',
            'decline','decline_user_request' => 'Declined',
            'claim'                => 'Claimed',
            'print'                => 'Print',
            default                => ucfirst(str_replace('_', ' ', $a)),
        };
    };
    $actionSentence = function(string $a): string {
        return match($a) {
            'create'               => 'Created reservation',
            'approve','approve_user_request' => 'Approved reservation',
            'decline','decline_user_request' => 'Declined reservation',
            'claim'                => 'Claimed e-ticket',
            'print'                => 'Logged print',
            default                => ucfirst(str_replace('_', ' ', $a)),
        };
    };
    $badgeClass = function(string $a): string {
        return match($a) {
            'create'               => 'badge-create',
            'approve','approve_user_request' => 'badge-approve',
            'decline','decline_user_request' => 'badge-decline',
            'claim'                => 'badge-claim',
            'print'                => 'badge-print',
            default                => 'badge-default',
        };
    };
    $avatarColor = function(string $a): string {
        return match($a) {
            'create'               => 'bg-emerald-50 text-emerald-600',
            'approve','approve_user_request' => 'bg-blue-50 text-blue-600',
            'decline','decline_user_request' => 'bg-red-50 text-red-500',
            'claim'                => 'bg-purple-50 text-purple-600',
            'print'                => 'bg-sky-50 text-sky-600',
            default                => 'bg-slate-100 text-slate-500',
        };
    };
    ?>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
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

    <!-- Mobile Nav -->
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

    <!-- Main -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-12 pb-32">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Activity Logs</h2>
                <p class="text-slate-500 font-medium text-sm mt-0.5">Complete audit trail of all system actions.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Dark mode toggle -->
                <button onclick="toggleDark()" id="darkBtn"
                    class="flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-2xl font-bold text-sm hover:border-blue-400 hover:text-blue-600 transition shadow-sm flex-shrink-0">
                    <span id="darkIcon"><i class="fa-regular fa-sun"></i></span>
                </button>
                <button onclick="window.print()"
                    class="flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-5 py-2.5 rounded-2xl font-bold text-sm hover:border-blue-400 hover:text-blue-600 transition shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-print"></i> Print Report
                </button>
            </div>
        </header>

        <!-- Stat cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-8">
            <div class="stat-card border-b-slate-300">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total</p>
                <p class="text-2xl lg:text-3xl font-black text-slate-800"><?= count($logs) ?></p>
            </div>
            <div class="stat-card border-b-emerald-400">
                <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-1">Created</p>
                <p class="text-2xl lg:text-3xl font-black text-slate-800"><?= $createCount ?? 0 ?></p>
            </div>
            <div class="stat-card border-b-blue-400">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">Approved</p>
                <p class="text-2xl lg:text-3xl font-black text-slate-800"><?= $approveCount ?? 0 ?></p>
            </div>
            <div class="stat-card border-b-red-400">
                <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-1">Declined</p>
                <p class="text-2xl lg:text-3xl font-black text-slate-800"><?= $declineCount ?? 0 ?></p>
            </div>
            <div class="stat-card border-b-purple-400 col-span-2 sm:col-span-1">
                <p class="text-[10px] font-black uppercase tracking-widest text-purple-500 mb-1">Claimed</p>
                <p class="text-2xl lg:text-3xl font-black text-slate-800"><?= $claimCount ?? 0 ?></p>
            </div>
        </div>

        <!-- Table / Card container -->
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-sm overflow-hidden">

            <!-- Filters -->
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/40 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input type="text" id="searchInput" placeholder="Search user, action, details…"
                        class="w-full bg-white border border-slate-200 rounded-2xl pl-10 pr-4 py-3 text-sm font-medium focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition">
                </div>
                <select id="actionFilter"
                    class="bg-white border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition w-full sm:w-44 flex-shrink-0">
                    <option value="">All Actions</option>
                    <option value="create">Created</option>
                    <option value="approve">Approved</option>
                    <option value="decline">Declined</option>
                    <option value="claim">Claimed</option>
                    <option value="print">Print</option>
                </select>
            </div>

            <!-- DESKTOP TABLE -->
            <div class="hidden md:block desktop-table">
                <div class="table-scroll">
                    <table class="w-full text-left" style="min-width:680px">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 bg-slate-50/50">
                                <th class="px-6 py-4 whitespace-nowrap w-36">Timestamp</th>
                                <th class="px-6 py-4 whitespace-nowrap w-44">User</th>
                                <th class="px-6 py-4 whitespace-nowrap w-28">Action</th>
                                <th class="px-6 py-4">Details</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody" class="divide-y divide-slate-50">
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log):
                                    $action   = strtolower(trim($log['action'] ?? ''));
                                    $name     = $log['name'] ?? 'System';
                                    $initials = strtoupper(substr($name, 0, 2));
                                    $resId    = $log['reservation_id'] ?? null;
                                    $details  = $log['details'] ?? '';
                                    $dateStr  = !empty($log['created_at']) ? date('M d, Y', strtotime($log['created_at'])) : '—';
                                    $timeStr  = !empty($log['created_at']) ? date('h:i A', strtotime($log['created_at'])) : '';
                                    $sentence = $actionSentence($action);
                                ?>
                                    <tr class="log-row" data-action="<?= htmlspecialchars($action) ?>"
                                        data-search="<?= strtolower(htmlspecialchars("$name $action $details $resId")) ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-bold text-slate-700"><?= $dateStr ?></p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5"><?= $timeStr ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-xl <?= $avatarColor($action) ?> flex items-center justify-center text-[11px] font-black flex-shrink-0">
                                                    <?= $initials ?>
                                                </div>
                                                <span class="text-sm font-bold text-slate-800 truncate max-w-[120px]"><?= htmlspecialchars($name) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="badge <?= $badgeClass($action) ?>">
                                                <?= $actionLabel($action) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-2 min-w-0">
                                                <div class="min-w-0">
                                                    <p class="text-sm text-slate-600 font-medium">
                                                        <?= $sentence ?>
                                                        <?php if ($resId): ?>
                                                            <span class="font-black text-slate-800">#<?= htmlspecialchars($resId) ?></span>
                                                        <?php endif; ?>
                                                    </p>
                                                    <?php if (!empty($details)): ?>
                                                        <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs"><?= htmlspecialchars($details) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($details)): ?>
                                                    <div class="details-tooltip flex-shrink-0 mt-0.5">
                                                        <i class="fa-solid fa-circle-info text-slate-300 hover:text-blue-500 transition text-sm"></i>
                                                        <span class="tooltip-text"><?= htmlspecialchars($details) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <i class="fa-solid fa-clipboard-list text-4xl text-slate-200 mb-3 block"></i>
                                        <p class="font-bold text-slate-400">No activity logs found.</p>
                                        <p class="text-sm text-slate-300 mt-1">Actions will appear here as they happen.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MOBILE CARDS -->
            <div class="md:hidden mobile-cards" id="mobileCardList">
                <?php if (!empty($logs)): ?>
                    <div class="divide-y divide-slate-50">
                        <?php foreach ($logs as $log):
                            $action   = strtolower(trim($log['action'] ?? ''));
                            $name     = $log['name'] ?? 'System';
                            $initials = strtoupper(substr($name, 0, 2));
                            $resId    = $log['reservation_id'] ?? null;
                            $details  = $log['details'] ?? '';
                            $dateStr  = !empty($log['created_at']) ? date('M d, Y', strtotime($log['created_at'])) : '—';
                            $timeStr  = !empty($log['created_at']) ? date('h:i A', strtotime($log['created_at'])) : '';
                            $sentence = $actionSentence($action);
                        ?>
                            <div class="mobile-log-card p-4"
                                 data-action="<?= htmlspecialchars($action) ?>"
                                 data-search="<?= strtolower(htmlspecialchars("$name $action $details $resId")) ?>">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-xl <?= $avatarColor($action) ?> flex items-center justify-center text-[11px] font-black flex-shrink-0">
                                            <?= $initials ?>
                                        </div>
                                        <span class="text-sm font-bold text-slate-800 truncate"><?= htmlspecialchars($name) ?></span>
                                    </div>
                                    <span class="badge <?= $badgeClass($action) ?> flex-shrink-0">
                                        <?= $actionLabel($action) ?>
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 font-medium">
                                    <?= $sentence ?>
                                    <?php if ($resId): ?>
                                        <span class="font-black text-slate-800">#<?= htmlspecialchars($resId) ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($details)): ?>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed"><?= htmlspecialchars($details) ?></p>
                                <?php endif; ?>
                                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-wide mt-2"><?= $dateStr ?> · <?= $timeStr ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-14 text-center">
                        <i class="fa-solid fa-clipboard-list text-4xl text-slate-200 mb-3 block"></i>
                        <p class="font-bold text-slate-400">No activity logs found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- No results state -->
            <div id="noResults" class="hidden px-8 py-12 text-center">
                <i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-200 mb-3 block"></i>
                <p class="font-bold text-slate-400">No logs match your search.</p>
            </div>

            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                <p id="resultCount" class="text-xs font-bold text-slate-400"></p>
            </div>
        </div>

        <!-- Footer legend -->
        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-400">
            <div class="legend-wrap">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>Created</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>Approved</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>Declined</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-purple-500 flex-shrink-0"></span>Claimed</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-400 flex-shrink-0"></span>Print</span>
            </div>
            <span class="font-medium">Last updated: <?= date('F j, Y g:i A') ?></span>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Init dark mode
        if (localStorage.getItem('admin_theme') === 'dark') {
            document.body.classList.add('dark');
            document.getElementById('darkIcon').innerHTML = '<i class="fa-regular fa-moon"></i>';
        }
        document.documentElement.classList.remove('dark-pre');

        const searchInput  = document.getElementById('searchInput');
        const actionFilter = document.getElementById('actionFilter');
        const noResults    = document.getElementById('noResults');
        const countEl      = document.getElementById('resultCount');

        const tableRows   = Array.from(document.querySelectorAll('.log-row'));
        const mobileCards = Array.from(document.querySelectorAll('.mobile-log-card'));
        const total = tableRows.length || mobileCards.length;

        function filterAll() {
            const q      = searchInput.value.toLowerCase().trim();
            const action = actionFilter.value.toLowerCase();
            let visible  = 0;

            tableRows.forEach(row => {
                const matchAction = !action || row.dataset.action === action ||
                    (action === 'approve' && row.dataset.action.startsWith('approve')) ||
                    (action === 'decline' && row.dataset.action.startsWith('decline'));
                const matchSearch = !q || row.dataset.search.includes(q);
                const show = matchAction && matchSearch;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            mobileCards.forEach(card => {
                const matchAction = !action || card.dataset.action === action ||
                    (action === 'approve' && card.dataset.action.startsWith('approve')) ||
                    (action === 'decline' && card.dataset.action.startsWith('decline'));
                const matchSearch = !q || card.dataset.search.includes(q);
                card.style.display = (matchAction && matchSearch) ? '' : 'none';
            });

            const count = tableRows.length ? visible : mobileCards.filter(c => c.style.display !== 'none').length;
            noResults.classList.toggle('hidden', count > 0 || total === 0);
            countEl.textContent = `Showing ${count} of ${total} log${total !== 1 ? 's' : ''}`;
        }

        searchInput.addEventListener('input', filterAll);
        actionFilter.addEventListener('change', filterAll);
        filterAll();
    });

    function toggleDark() {
        const isDark = document.body.classList.toggle('dark');
        const icon = document.getElementById('darkIcon');
        icon.innerHTML = isDark
            ? '<i class="fa-regular fa-moon"></i>'
            : '<i class="fa-regular fa-sun"></i>';
        localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
    }
    </script>
</body>
</html>