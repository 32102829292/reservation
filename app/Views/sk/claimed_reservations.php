<?php
date_default_timezone_set('Asia/Manila');
$page = 'claimed-reservations';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Claimed Reservations | SK</title>
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

        main { min-width: 0; }

        .content-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); overflow: hidden;
        }

        .table-wrap { overflow-x: auto; }
        @media (min-width: 1024px) { .table-wrap { overflow-x: visible; } table { min-width: 0 !important; } }
        @media (max-width: 1023px) { table { min-width: 1000px; } }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th {
            background-color: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b;
            padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; font-weight: 500; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        input, select {
            background: #fcfdfe; border: 1px solid #e2e8f0; padding: 0.75rem 1.25rem;
            font-size: 0.9rem; transition: all 0.2s; border-radius: 12px; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        input:focus, select:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 4px rgba(22,163,74,0.08); }

        .status-badge { padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; white-space: nowrap; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-claimed { background-color: #f3e8ff; color: #6b21a8; }

        .btn-primary {
            background: #16a34a; color: white; border: none;
            padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700;
            font-size: 0.85rem; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-primary:hover { background: #15803d; }

        .btn-excel {
            background: #059669; color: white; border: none;
            padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700;
            font-size: 0.85rem; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-excel:hover { background: #047857; }

        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        
        .stat-card {
            background: white; border-radius: 24px; padding: 1.5rem;
            border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
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
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] == 'claimed-reservations'): ?>
                            <span class="ml-auto bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded-full">
                                <?= $totalClaimed ?? 0 ?>
                            </span>
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

    <!-- Mobile Nav -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = ($page == $item['key']);
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    <?php if ($item['key'] == 'claimed-reservations'): ?>
                        <span class="absolute -top-1 -right-1 bg-purple-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full">
                            <?= $totalClaimed ?? 0 ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 min-w-0 p-4 lg:p-12 pb-32">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Claimed Reservations</h2>
                <p class="text-slate-500 font-medium">List of all reservations that have been claimed/used.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('/sk/export-claimed-excel') ?>" class="btn-excel">
                    <i class="fa-solid fa-file-excel"></i> Export to Excel
                </a>
            </div>
        </header>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Claimed</span>
                </div>
                <div class="text-3xl font-black text-slate-800"><?= $totalClaimed ?></div>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Today</span>
                </div>
                <div class="text-3xl font-black text-slate-800"><?= $todayCount ?></div>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Approved</span>
                </div>
                <div class="text-3xl font-black text-slate-800"><?= $totalClaimed ?></div>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">This Week</span>
                </div>
                <div class="text-3xl font-black text-slate-800">
                    <?php
                    $weekCount = 0;
                    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                    foreach ($claimedReservations as $r) {
                        if (!empty($r['claimed_at']) && date('Y-m-d', strtotime($r['claimed_at'])) >= $startOfWeek) {
                            $weekCount++;
                        }
                    }
                    echo $weekCount;
                    ?>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <!-- Filters -->
            <div class="p-4 lg:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" class="pl-10" placeholder="Search by name, code, resource...">
                </div>
                <div class="flex gap-2">
                    <select id="dateFilter" class="sm:w-40 flex-shrink-0">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Resource</th>
                            <th>Workstation</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Purpose</th>
                            <th>Claimed At</th>
                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                        <?php if (empty($claimedReservations)): ?>
                            <tr><td colspan="10"><div class="empty-state">
                                <i class="fa-solid fa-check-double text-4xl mb-3 block"></i>
                                <p class="font-bold text-slate-500">No claimed reservations yet.</p>
                            </div></td></tr>
                        <?php else: ?>
                            <?php foreach ($claimedReservations as $res): ?>
                                <?php
                                    $name = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                                    $email = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '—');
                                    $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                    
                                    // Parse workstation numbers
                                    $pcNumbers = '';
                                    if (!empty($res['pc_numbers'])) {
                                        try {
                                            $arr = json_decode($res['pc_numbers'], true);
                                            $pcNumbers = is_array($arr) ? implode(', ', $arr) : $res['pc_numbers'];
                                        }
                                        catch (\Exception $e) {
                                            $pcNumbers = $res['pc_numbers'];
                                        }
                                    } elseif (!empty($res['pc_number'])) {
                                        $pcNumbers = $res['pc_number'];
                                    }
                                    
                                    $claimedDate = !empty($res['claimed_at']) ? date('M j, Y', strtotime($res['claimed_at'])) : '—';
                                    $claimedTime = !empty($res['claimed_at']) ? date('g:i A', strtotime($res['claimed_at'])) : '—';
                                ?>
                                <tr class="hover:bg-purple-50 transition">
                                    <td><span class="text-slate-400 font-bold">#</span><?= $res['id'] ?></td>
                                    <td>
                                        <span class="font-mono text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded-lg">
                                            <?= $res['e_ticket_code'] ?? '—' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-bold text-slate-800"><?= $name ?></div>
                                    </td>
                                    <td>
                                        <div class="text-xs text-slate-600"><?= $email ?></div>
                                    </td>
                                    <td>
                                        <div class="font-semibold"><?= $resource ?></div>
                                    </td>
                                    <td>
                                        <?php if ($pcNumbers): ?>
                                            <div class="text-xs bg-purple-50 text-purple-700 font-bold px-2 py-1 rounded-lg inline-block">
                                                <?= htmlspecialchars($pcNumbers) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-slate-700"><?= htmlspecialchars($res['reservation_date'] ?? '') ?></div>
                                    </td>
                                    <td>
                                        <div class="text-xs text-green-600 font-bold">
                                            <?= htmlspecialchars($res['start_time'] ?? '') ?> – <?= htmlspecialchars($res['end_time'] ?? '') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-slate-600 max-w-[120px] truncate"><?= htmlspecialchars($res['purpose'] ?: '—') ?></div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-purple-700"><?= $claimedDate ?></span>
                                            <span class="text-xs text-purple-500"><?= $claimedTime ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="noResults" class="hidden empty-state">
                <i class="fa-solid fa-filter-circle-xmark text-3xl mb-2 block"></i>
                <p class="font-bold">No claimed reservations match your search.</p>
            </div>
        </div>
        
        <!-- Export Hint -->
        <div class="mt-6 text-xs text-slate-400 flex items-center gap-2">
            <i class="fa-solid fa-info-circle"></i>
            <span>Export to Excel will download all claimed reservations as a CSV file compatible with Excel.</span>
        </div>
    </main>

    <script>
        // Filter functionality
        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('dateFilter').addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const dateFilter = document.getElementById('dateFilter').value;
            let count = 0;
            
            const today = new Date().toISOString().split('T')[0];
            const startOfWeek = new Date();
            startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay() + 1);
            const weekStart = startOfWeek.toISOString().split('T')[0];
            
            const startOfMonth = new Date();
            startOfMonth.setDate(1);
            const monthStart = startOfMonth.toISOString().split('T')[0];
            
            document.querySelectorAll('#reservationTableBody tr').forEach(row => {
                if (row.querySelector('td[colspan]')) return; // Skip empty state row
                
                const text = row.textContent.toLowerCase();
                let matchesSearch = text.includes(searchTerm);
                
                // Date filtering
                let matchesDate = true;
                if (dateFilter) {
                    const claimedCell = row.querySelector('td:last-child .text-sm');
                    if (claimedCell) {
                        const claimedText = claimedCell.textContent;
                        const claimedDate = new Date(claimedText).toISOString().split('T')[0];
                        
                        if (dateFilter === 'today') {
                            matchesDate = claimedDate === today;
                        } else if (dateFilter === 'week') {
                            matchesDate = claimedDate >= weekStart;
                        } else if (dateFilter === 'month') {
                            matchesDate = claimedDate >= monthStart;
                        }
                    }
                }
                
                const visible = matchesSearch && matchesDate;
                row.style.display = visible ? '' : 'none';
                if (visible) count++;
            });
            
            document.getElementById('noResults').classList.toggle('hidden', count > 0);
        }
    </script>
</body>
</html>