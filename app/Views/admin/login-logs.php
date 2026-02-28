<?php
date_default_timezone_set('Asia/Manila');
$page = 'login-logs';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Login Logs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        /* ── Sidebar (identical to dashboard) ── */
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

        /* ── Mobile nav pill (identical to dashboard) ── */
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

        /* ── Content ── */
        .content-card { background: white; border-radius: 32px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }

        .log-table th {
            text-transform: uppercase; font-size: 0.7rem;
            letter-spacing: 0.1em; font-weight: 800;
            color: #64748b; padding: 1.5rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .log-table td { padding: 1.25rem 1rem; border-bottom: 1px solid #f8fafc; font-size: 0.875rem; }

        .status-active { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
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

    <!-- ── Sidebar ── -->
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

    <!-- ── Mobile Nav (matching dashboard exactly) ── -->
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

    <!-- ── Main ── -->
    <main class="flex-1 p-6 lg:p-12 pb-32">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Login Logs</h2>
                <p class="text-slate-500 font-medium">Authentication history and session tracking.</p>
            </div>
        </header>

        <div class="content-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="log-table w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Role</th>
                            <th>Login Timestamp</th>
                            <th>Logout Timestamp</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log):
                                $isOnline = empty($log['logout_time']);
                                $login  = new DateTime($log['login_time']);
                                $logout = $log['logout_time'] ? new DateTime($log['logout_time']) : null;
                            ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900"><?= htmlspecialchars($log['name']) ?></span>
                                            <span class="text-xs text-slate-400 font-normal"><?= htmlspecialchars($log['email']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider">
                                            <?= htmlspecialchars($log['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col">
                                            <span><?= $login->format('M d, Y') ?></span>
                                            <span class="text-[10px] text-slate-400"><?= $login->format('h:i A') ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($logout): ?>
                                            <div class="flex flex-col">
                                                <span><?= $logout->format('M d, Y') ?></span>
                                                <span class="text-[10px] text-slate-400"><?= $logout->format('h:i A') ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300 italic font-normal text-xs">Session active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($isOnline): ?>
                                            <span class="status-active px-3 py-1.5 rounded-full text-[10px] font-black flex items-center gap-2 w-fit">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                                ACTIVE
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-[10px] font-bold uppercase pl-3">Closed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-400">
                                    <i class="fa-solid fa-clock text-3xl mb-3 block"></i>
                                    No logs found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>