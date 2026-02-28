<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Activity Logs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            flex-shrink: 0;
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px;
        }

        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }

        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(30,41,59,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container {
            display: flex; gap: 4px; overflow-x: auto;
            scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
        }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        .badge { 
            padding: 4px 12px; 
            border-radius: 99px; 
            font-size: 0.72rem; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.04em; 
            display: inline-block; 
        }
        .badge-create  { background: #ecfdf5; color: #059669; }
        .badge-approve { background: #eff6ff; color: #2563eb; }
        .badge-decline { background: #fef2f2; color: #dc2626; }
        .badge-claim   { background: #f3e8ff; color: #6b21a8; }

        .log-row { transition: background 0.15s; }
        .log-row:hover td { background: #f8fafc; }

        .stat-card { 
            background: white; 
            border-radius: 24px; 
            padding: 1.5rem; 
            border: 1px solid #e2e8f0; 
            transition: transform 0.2s, box-shadow 0.2s; 
        }
        .stat-card:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.07); 
        }

        .details-tooltip {
            position: relative;
            cursor: help;
            display: inline-block;
        }
        .details-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .tooltip-text {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            white-space: nowrap;
            transition: opacity 0.2s;
            z-index: 10;
            pointer-events: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #1e293b transparent transparent transparent;
        }

        @media print {
            aside, .mobile-nav-pill, header button { display: none !important; }
            main { margin: 0 !important; padding: 1rem !important; }
        }
    </style>
</head>
<body class="flex">

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
    ?>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
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
                $isActive = ($page == $item['key']);
                $btnClass = $isActive ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-12 pb-32">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Activity Logs</h2>
                <p class="text-slate-500 font-medium">Complete audit trail of all system actions including approvals, declines, and claims.</p>
            </div>
            <button onclick="window.print()"
                class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold text-sm hover:shadow-md transition shadow-sm shrink-0 flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print Report
            </button>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
            <div class="stat-card">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total</p>
                <p class="text-3xl font-black text-slate-800"><?= count($logs) ?></p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">Created</p>
                <p class="text-3xl font-black text-slate-800"><?= $createCount ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-2">Approved</p>
                <p class="text-3xl font-black text-slate-800"><?= $approveCount ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">Declined</p>
                <p class="text-3xl font-black text-slate-800"><?= $declineCount ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] font-black uppercase tracking-widest text-purple-500 mb-2">Claimed</p>
                <p class="text-3xl font-black text-slate-800"><?= $claimCount ?? 0 ?></p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">

            <!-- Filters - Simplified Dropdown -->
            <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" placeholder="Search by user or reservation ID..."
                        class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-4 py-3.5 focus:ring-4 focus:ring-blue-50 outline-none transition font-medium text-sm">
                </div>
                <select id="actionFilter"
                    class="bg-white border border-slate-200 rounded-2xl px-6 py-3.5 focus:ring-4 focus:ring-blue-50 outline-none transition font-bold text-slate-600 text-sm w-full md:w-48">
                    <option value="">All Actions</option>
                    <option value="create">Created</option>
                    <option value="approve">Approved</option>
                    <option value="decline">Declined</option>
                    <option value="claim">Claimed</option>
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 bg-slate-50/50">
                            <th class="px-8 py-5">Timestamp</th>
                            <th class="px-8 py-5">User</th>
                            <th class="px-8 py-5">Action</th>
                            <th class="px-8 py-5">Details</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody" class="divide-y divide-slate-50">
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log):
                                $action    = strtolower(trim($log['action'] ?? ''));
                                $name      = $log['name'] ?? 'System';
                                $initials  = strtoupper(substr($name, 0, 2));
                                $resId     = $log['reservation_id'] ?? null;
                                $details   = $log['details'] ?? '';
                                $createdAt = $log['created_at'] ?? null;
                                $dateStr   = $createdAt ? date('M d, Y', strtotime($createdAt)) : '—';
                                $timeStr   = $createdAt ? date('h:i A', strtotime($createdAt)) : '';

                                $avatarColor = match($action) {
                                    'create'  => 'bg-emerald-50 text-emerald-600',
                                    'approve' => 'bg-blue-50 text-blue-600',
                                    'decline' => 'bg-red-50 text-red-500',
                                    'claim'   => 'bg-purple-50 text-purple-600',
                                    default   => 'bg-slate-100 text-slate-500',
                                };

                                $sentence = match($action) {
                                    'create'  => 'Created reservation',
                                    'approve' => 'Approved reservation',
                                    'decline' => 'Declined reservation',
                                    'claim'   => 'Claimed e-ticket',
                                    default   => ucfirst($action),
                                };
                            ?>
                                <tr class="log-row" data-action="<?= htmlspecialchars($action) ?>" data-reservation="<?= $resId ?>">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <p class="text-sm font-bold text-slate-700"><?= $dateStr ?></p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5"><?= $timeStr ?></p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl <?= $avatarColor ?> flex items-center justify-center text-[11px] font-black flex-shrink-0">
                                                <?= $initials ?>
                                            </div>
                                            <span class="text-sm font-bold text-slate-800"><?= htmlspecialchars($name) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="badge badge-<?= $action ?>">
                                            <?= match($action) {
                                                'create' => 'Created',
                                                'approve' => 'Approved',
                                                'decline' => 'Declined',
                                                'claim' => 'Claimed',
                                                default => ucfirst($action),
                                            } ?>
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-start gap-2">
                                                <p class="text-sm text-slate-600 font-medium">
                                                    <?= $sentence ?>
                                                    <?php if ($resId): ?>
                                                        <span class="font-black text-slate-800">#<?= htmlspecialchars($resId) ?></span>
                                                    <?php endif; ?>
                                                </p>
                                                <?php if (!empty($details) && $details !== $sentence . ' #' . $resId): ?>
                                                    <div class="details-tooltip">
                                                        <i class="fa-solid fa-circle-info text-slate-400 hover:text-blue-600 transition"></i>
                                                        <span class="tooltip-text"><?= htmlspecialchars($details) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($details) && $details !== $sentence . ' #' . $resId): ?>
                                                <p class="text-xs text-slate-500 italic max-w-md"><?= htmlspecialchars($details) ?></p>
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

            <div id="noResults" class="hidden px-8 py-12 text-center">
                <i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-200 mb-3 block"></i>
                <p class="font-bold text-slate-400">No logs match your search.</p>
            </div>
        </div>

        <!-- Summary Footer -->
        <div class="mt-6 text-xs text-slate-400 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Created</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Approved</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Declined</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Claimed</span>
            </div>
            <span>Last updated: <?= date('F j, Y g:i A') ?></span>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const actionFilter = document.getElementById('actionFilter');
            const rows = document.querySelectorAll('.log-row');
            const noResults = document.getElementById('noResults');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const filterAction = actionFilter.value.toLowerCase();
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const action = row.dataset.action;
                    
                    const matchesSearch = searchTerm === '' || text.includes(searchTerm);
                    const matchesFilter = filterAction === '' || action === filterAction;
                    
                    const isVisible = matchesSearch && matchesFilter;
                    row.style.display = isVisible ? '' : 'none';
                    
                    if (isVisible) visibleCount++;
                });
                
                noResults.classList.toggle('hidden', visibleCount > 0);
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (actionFilter) actionFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>