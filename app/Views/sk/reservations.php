<?php
$page = $page ?? 'reservations';
$sk_name = session()->get('name') ?? session()->get('username') ?? 'SK Officer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>All Reservations | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        .content-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); overflow: hidden;
        }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        th {
            background-color: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b;
            padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0;
        }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; font-weight: 500; vertical-align: middle; }

        .status-badge { padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.7rem; font-weight: 800; display: inline-block; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-declined { background-color: #fee2e2; color: #991b1b; }
        .status-canceled { background-color: #fee2e2; color: #991b1b; }
        .status-claimed { background-color: #f3e8ff; color: #6b21a8; }

        .btn-action { padding: 0.4rem 0.8rem; border-radius: 10px; font-weight: 700; font-size: 0.75rem; transition: all 0.2s; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 5px; }
        .btn-details { background-color: #f1f5f9; color: #475569; }
        .btn-details:hover { background-color: #e2e8f0; }

        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .reservation-row { transition: background 0.15s; }
        .reservation-row:hover td { background-color: #f8fafc; }

        .stat-card { 
            background: white; border-radius: 20px; padding: 1.25rem; 
            border: 1px solid #e2e8f0; border-left-width: 4px;
        }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
            ['url' => '/sk/dashboard',       'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
            ['url' => '/sk/reservations',    'icon' => 'fa-calendar-alt',    'label' => 'All Reservations', 'key' => 'reservations'],
            ['url' => '/sk/new-reservation', 'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
            ['url' => '/sk/user-requests',   'icon' => 'fa-users',           'label' => 'User Requests',   'key' => 'user-requests'],
            ['url' => '/sk/claimed-reservations', 'icon' => 'fa-check-double', 'label' => 'Claimed',       'key' => 'claimed-reservations'],
            ['url' => '/sk/my-reservations', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'my-reservations'],
            ['url' => '/sk/scanner',         'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
            ['url' => '/sk/profile',         'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
        ];
    ?>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
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
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
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

    <!-- Main Content -->
    <main class="flex-1 min-w-0 p-6 lg:p-12 pb-32">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">All Reservations</h2>
                <p class="text-slate-500 font-medium">View all reservation requests in the system.</p>
            </div>
            <div class="flex gap-3">
                <a href="/sk/reservations/download" class="px-4 py-2 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-download"></i> Export CSV
                </a>
            </div>
        </header>

        <!-- Stats Summary - Using the correct variable names -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="stat-card border-l-4 border-green-500">
                <div class="text-xs font-bold text-slate-400 mb-1">Total</div>
                <div class="text-2xl font-black text-slate-800"><?= $total ?? 0 ?></div>
            </div>
            <div class="stat-card border-l-4 border-amber-500">
                <div class="text-xs font-bold text-slate-400 mb-1">Pending</div>
                <div class="text-2xl font-black text-amber-600"><?= $pending ?? 0 ?></div>
            </div>
            <div class="stat-card border-l-4 border-emerald-500">
                <div class="text-xs font-bold text-slate-400 mb-1">Approved</div>
                <div class="text-2xl font-black text-emerald-600"><?= $approved ?? 0 ?></div>
            </div>
            <div class="stat-card border-l-4 border-purple-500">
                <div class="text-xs font-bold text-slate-400 mb-1">Claimed</div>
                <div class="text-2xl font-black text-purple-600"><?= $claimed ?? 0 ?></div>
            </div>
            <div class="stat-card border-l-4 border-rose-500">
                <div class="text-xs font-bold text-slate-400 mb-1">Declined</div>
                <div class="text-2xl font-black text-rose-600"><?= $declined ?? 0 ?></div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="bg-white rounded-[32px] p-6 border border-slate-200 shadow-sm mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <select name="status" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= ($currentStatus ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($currentStatus ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="declined" <?= ($currentStatus ?? '') == 'declined' ? 'selected' : '' ?>>Declined</option>
                        <option value="canceled" <?= ($currentStatus ?? '') == 'canceled' ? 'selected' : '' ?>>Canceled</option>
                        <option value="claimed" <?= ($currentStatus ?? '') == 'claimed' ? 'selected' : '' ?>>Claimed</option>
                    </select>
                </div>
                <div class="flex-1">
                    <input type="date" name="date" value="<?= $currentDate ?? '' ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                        Filter
                    </button>
                    <a href="/sk/reservations" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Reservations Table -->
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Resource</th>
                            <th>PC</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="9" class="text-center py-12 text-slate-400">No reservations found</td></tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <?php
                                    $status = $res['status'] ?? 'pending';
                                    if (!empty($res['claimed']) && $res['claimed'] == 1) {
                                        $status = 'claimed';
                                    }
                                    $statusClass = match($status) {
                                        'approved' => 'status-approved',
                                        'pending' => 'status-pending',
                                        'declined', 'canceled' => 'status-declined',
                                        'claimed' => 'status-claimed',
                                        default => 'status-pending'
                                    };
                                ?>
                                <tr class="reservation-row">
                                    <td><span class="text-slate-400">#</span><?= $res['id'] ?></td>
                                    <td>
                                        <div class="font-bold"><?= htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest') ?></div>
                                        <div class="text-xs text-slate-400"><?= htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '') ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($res['resource_name'] ?? 'Resource #' . $res['resource_id']) ?></td>
                                    <td><?= htmlspecialchars($res['pc_number'] ?? $res['pc_numbers'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($res['reservation_date'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['start_time'] ?? '') ?> - <?= htmlspecialchars($res['end_time'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['purpose'] ?? '—') ?></td>
                                    <td><span class="status-badge <?= $statusClass ?>"><?= ucfirst($status) ?></span></td>
                                    <td>
                                        <a href="/sk/reservations?id=<?= $res['id'] ?>" class="btn-action btn-details">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>