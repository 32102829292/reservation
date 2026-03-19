<?php
date_default_timezone_set('Asia/Manila');
$page = 'claimed-reservations';

$claimedReservations = $claimedReservations ?? [
    ['id'=>88,'e_ticket_code'=>'SK-88-2025-07-12','visitor_name'=>'Maria Santos','visitor_email'=>'maria@example.com','resource_name'=>'Computer Lab A','pc_numbers'=>'["PC-01","PC-02"]','reservation_date'=>'2025-07-12','start_time'=>'09:00 AM','end_time'=>'11:00 AM','purpose'=>'Thesis research','claimed_at'=>date('Y-m-d H:i:s', strtotime('today 10:23:00'))],
    ['id'=>91,'e_ticket_code'=>'SK-91-2025-07-12','visitor_name'=>'Juan dela Cruz','visitor_email'=>'juan@example.com','resource_name'=>'Conference Room','pc_numbers'=>null,'reservation_date'=>'2025-07-12','start_time'=>'01:00 PM','end_time'=>'03:00 PM','purpose'=>'Barangay meeting','claimed_at'=>date('Y-m-d H:i:s', strtotime('today 13:05:00'))],
    ['id'=>75,'e_ticket_code'=>'SK-75-2025-07-11','visitor_name'=>'Ana Reyes','visitor_email'=>'ana@example.com','resource_name'=>'Computer Lab B','pc_numbers'=>'["PC-05"]','reservation_date'=>'2025-07-11','start_time'=>'02:00 PM','end_time'=>'04:00 PM','purpose'=>'Online exam','claimed_at'=>date('Y-m-d H:i:s', strtotime('yesterday 14:01:00'))],
    ['id'=>82,'e_ticket_code'=>'SK-82-2025-07-10','visitor_name'=>'Ben Flores','visitor_email'=>null,'resource_name'=>'Multi-Purpose Hall','pc_numbers'=>null,'reservation_date'=>'2025-07-10','start_time'=>'08:00 AM','end_time'=>'12:00 PM','purpose'=>'Seminar','claimed_at'=>date('Y-m-d H:i:s', strtotime('-3 days 08:15:00'))],
    ['id'=>79,'e_ticket_code'=>'SK-79-2025-07-09','visitor_name'=>'Carla Mendoza','visitor_email'=>'carla@example.com','resource_name'=>'Computer Lab A','pc_numbers'=>'["PC-03","PC-04","PC-07"]','reservation_date'=>'2025-07-09','start_time'=>'10:00 AM','end_time'=>'12:00 PM','purpose'=>'Training session','claimed_at'=>date('Y-m-d H:i:s', strtotime('-4 days 10:00:00'))],
];
$totalClaimed = count($claimedReservations);
$todayStr    = date('Y-m-d');
$todayCount  = count(array_filter($claimedReservations, fn($r) => !empty($r['claimed_at']) && date('Y-m-d', strtotime($r['claimed_at'])) === $todayStr));
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$weekCount   = count(array_filter($claimedReservations, fn($r) => !empty($r['claimed_at']) && date('Y-m-d', strtotime($r['claimed_at'])) >= $startOfWeek));

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Claimed Reservations | SK</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

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

        /* ── Content card ── */
        .content-card {
            background: white; border-radius: 24px; border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        /* ── Desktop Table (md+) ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        thead { background: #f8fafc; }
        th {
            font-weight: 700; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.08em; color: #64748b;
            padding: 14px 20px; border-bottom: 2px solid #e9eef5; white-space: nowrap;
        }
        td {
            padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem; font-weight: 500; vertical-align: middle; color: #334155; line-height: 1.5;
        }
        tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; animation: rowFade 0.3s ease both; }
        tbody tr:nth-child(even) td { background: #fafbfc; }
        tbody tr:hover td { background: #f5f0ff !important; }
        @keyframes rowFade { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }

        /* ── Mobile cards ── */
        .claim-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem; transition: all 0.18s; position: relative; overflow: hidden;
        }
        .claim-card::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 4px; border-radius: 0 4px 4px 0; background: #a855f7;
        }
        .claim-card:hover { border-color: #ddd6fe; box-shadow: 0 6px 20px -4px rgba(168,85,247,0.15); transform: translateY(-1px); }

        /* ── Chips ── */
        .chip-ticket {
            font-family: 'Courier New', monospace; font-size: 0.78rem; font-weight: 700;
            background: #faf5ff; color: #6d28d9; padding: 5px 11px; border-radius: 8px;
            border: 1px solid #ddd6fe; display: inline-block; white-space: nowrap; letter-spacing: 0.03em;
        }
        .chip-ws {
            font-size: 0.78rem; font-weight: 700; background: #f0fdf4; color: #15803d;
            padding: 4px 10px; border-radius: 7px; border: 1px solid #bbf7d0; display: inline-block;
        }
        .id-cell { font-size: 0.82rem; font-weight: 700; color: #94a3b8; }
        .name-main  { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
        .email-sub  { font-size: 0.78rem; color: #94a3b8; margin-top: 2px; }
        .resource-main { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
        .date-main { font-weight: 600; color: #1e293b; font-size: 0.875rem; }
        .time-main { font-size: 0.8rem; color: #16a34a; font-weight: 700; white-space: nowrap; }
        .purpose-cell { color: #64748b; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.875rem; }
        .claimed-date { font-weight: 700; color: #5b21b6; font-size: 0.875rem; }
        .claimed-time { font-size: 0.78rem; color: #7c3aed; margin-top: 3px; font-weight: 500; }

        /* ── Stat cards ── */
        .stat-card {
            background: white; border-radius: 20px; padding: 20px 22px 18px; border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .stat-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
        .stat-val  { font-size: 2.4rem; font-weight: 800; line-height: 1; color: #0f172a; letter-spacing: -0.03em; margin-top: 16px; }
        .stat-lbl  { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-top: 5px; }

        /* ── Export btn ── */
        .btn-excel {
            background: linear-gradient(135deg, #059669, #047857); color: white; border: none;
            padding: 11px 22px; border-radius: 12px; font-weight: 700; font-size: 0.875rem;
            cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px;
            text-decoration: none; box-shadow: 0 3px 12px rgba(5,150,105,0.28);
        }
        .btn-excel:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,0.35); }

        /* ── Inputs ── */
        input, select {
            background: white; border: 1.5px solid #e2e8f0; padding: 10px 16px; font-size: 0.9rem;
            transition: all 0.2s; border-radius: 12px; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b;
        }
        input:focus, select:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.08); }
        input::placeholder { color: #b0bec8; }

        /* ── Filter bar ── */
        .filter-bar { padding: 16px 22px; border-bottom: 1px solid #eef2f7; background: #f9fafc; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .search-wrap { position: relative; flex: 1; min-width: 200px; }
        .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #b0bec8; font-size: 0.85rem; }
        .search-wrap input { padding-left: 40px; }

        /* ── Empty ── */
        .empty-state { padding: 4rem 2rem; text-align: center; }
        .empty-icon { width: 60px; height: 60px; border-radius: 16px; background: #faf5ff; color: #c084fc; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
        .card-empty { padding: 3rem 1.5rem; text-align: center; background: white; border-radius: 20px; border: 1px dashed #e2e8f0; }

        .hint { margin-top: 14px; font-size: 0.78rem; color: #94a3b8; display: flex; align-items: center; gap: 7px; }
        .flash-success { display: flex; align-items: center; gap: 12px; padding: 14px 18px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-weight: 600; border-radius: 16px; margin-bottom: 20px; font-size: 0.9rem; }
    </style>
</head>
<body class="flex">

    <!-- ── Sidebar ── -->
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
                        <?php if ($item['key'] == 'claimed-reservations'): ?>
                            <span class="ml-auto bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= $totalClaimed ?? 0 ?></span>
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
                $btnClass = ($page == $item['key']) ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?> relative">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    <?php if ($item['key'] == 'claimed-reservations'): ?>
                        <span class="absolute -top-1 -right-1 bg-purple-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full"><?= $totalClaimed ?? 0 ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ── Main ── -->
    <main class="flex-1 min-w-0 p-4 lg:p-12 pb-32">

        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Claimed Reservations</h2>
                <p class="text-slate-400 font-medium text-sm mt-1">List of all reservations that have been claimed/used.</p>
            </div>
            <a href="/sk/export-claimed-excel" class="btn-excel">
                <i class="fa-solid fa-file-excel"></i> Export to Excel
            </a>
        </header>

        <?php if (function_exists('session') && session()->getFlashdata('success')): ?>
        <div class="flash-success">
            <i class="fa-solid fa-circle-check text-green-500 text-lg flex-shrink-0"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
        <?php endif; ?>

        <!-- Stat cards: 2-col on xs, 4-col on sm+ -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <div class="flex items-center gap-3">
                    <div class="stat-icon bg-purple-100 text-purple-600"><i class="fa-solid fa-check-double"></i></div>
                    <span class="stat-lbl">Total Claimed</span>
                </div>
                <div class="stat-val"><?= $totalClaimed ?></div>
            </div>
            <div class="stat-card">
                <div class="flex items-center gap-3">
                    <div class="stat-icon bg-blue-100 text-blue-600"><i class="fa-solid fa-calendar-day"></i></div>
                    <span class="stat-lbl">Today</span>
                </div>
                <div class="stat-val"><?= $todayCount ?></div>
            </div>
            <div class="stat-card">
                <div class="flex items-center gap-3">
                    <div class="stat-icon bg-green-100 text-green-600"><i class="fa-solid fa-ticket"></i></div>
                    <span class="stat-lbl">Approved</span>
                </div>
                <div class="stat-val"><?= $totalClaimed ?></div>
            </div>
            <div class="stat-card">
                <div class="flex items-center gap-3">
                    <div class="stat-icon bg-amber-100 text-amber-600"><i class="fa-solid fa-clock"></i></div>
                    <span class="stat-lbl">This Week</span>
                </div>
                <div class="stat-val"><?= $weekCount ?></div>
            </div>
        </div>

        <!-- ── Filter bar (shared) ── -->
        <div class="content-card mb-4">
            <div class="filter-bar">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search by name, code, resource..." oninput="applyFilters()">
                </div>
                <select id="dateFilter" class="sm:w-40 flex-shrink-0" onchange="applyFilters()">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>

        <div class="px-1 mb-3"><p id="resultCount" class="text-xs font-bold text-slate-400"></p></div>

        <!-- ══════════════════════════════════════
             DESKTOP TABLE  (md+)
             ══════════════════════════════════════ -->
        <div id="desktopTableWrap" class="hidden md:block content-card mb-4">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px">ID</th>
                            <th style="width:155px">Code</th>
                            <th>Name &amp; Email</th>
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
                            <tr><td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-check-double"></i></div>
                                    <p class="font-bold text-slate-500 text-base">No claimed reservations yet.</p>
                                    <p class="text-slate-400 text-sm mt-1">Claimed tickets will appear here once scanned.</p>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($claimedReservations as $i => $res):
                                $name     = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                                $email    = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '—');
                                $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #'.($res['resource_id']??'?')));
                                $pcNumbers = '';
                                if (!empty($res['pc_numbers'])) {
                                    try { $arr = json_decode($res['pc_numbers'],true); $pcNumbers = is_array($arr) ? implode(', ',$arr) : $res['pc_numbers']; }
                                    catch(\Exception $e){ $pcNumbers = $res['pc_numbers']; }
                                } elseif (!empty($res['pc_number'])) { $pcNumbers = $res['pc_number']; }
                                $claimedISO  = !empty($res['claimed_at']) ? date('Y-m-d', strtotime($res['claimed_at'])) : '';
                                $claimedDate = !empty($res['claimed_at']) ? date('M j, Y', strtotime($res['claimed_at'])) : '—';
                                $claimedTime = !empty($res['claimed_at']) ? date('g:i A', strtotime($res['claimed_at'])) : '—';
                                $searchText  = strtolower("$name $email $resource {$res['e_ticket_code']} {$res['purpose']}");
                            ?>
                                <tr data-claimed="<?= $claimedISO ?>"
                                    data-search="<?= htmlspecialchars($searchText, ENT_QUOTES) ?>"
                                    style="animation-delay:<?= $i * 0.05 ?>s">
                                    <td><span class="id-cell">#<?= $res['id'] ?></span></td>
                                    <td><span class="chip-ticket"><?= htmlspecialchars($res['e_ticket_code'] ?? '—') ?></span></td>
                                    <td>
                                        <div class="name-main"><?= $name ?></div>
                                        <div class="email-sub"><?= $email ?></div>
                                    </td>
                                    <td><div class="resource-main"><?= $resource ?></div></td>
                                    <td>
                                        <?php if ($pcNumbers): ?>
                                            <span class="chip-ws"><?= htmlspecialchars($pcNumbers) ?></span>
                                        <?php else: ?>
                                            <span style="color:#d1d5db">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><div class="date-main"><?= htmlspecialchars($res['reservation_date'] ?? '') ?></div></td>
                                    <td><div class="time-main"><?= htmlspecialchars($res['start_time'] ?? '') ?> – <?= htmlspecialchars($res['end_time'] ?? '') ?></div></td>
                                    <td><div class="purpose-cell"><?= htmlspecialchars($res['purpose'] ?: '—') ?></div></td>
                                    <td>
                                        <div class="claimed-date"><?= $claimedDate ?></div>
                                        <div class="claimed-time"><?= $claimedTime ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="noResults" style="display:none;border-top:1px solid #f1f5f9">
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-solid fa-filter-circle-xmark"></i></div>
                    <p class="font-bold text-slate-500">No claimed reservations match your search.</p>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════
             MOBILE CARD LIST  (below md)
             ══════════════════════════════════════ -->
        <div id="mobileCardList" class="md:hidden space-y-3">
            <?php if (empty($claimedReservations)): ?>
                <div class="card-empty">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-check-double text-2xl text-purple-300"></i>
                    </div>
                    <p class="font-black text-slate-400">No claimed reservations yet.</p>
                    <p class="text-slate-300 text-sm mt-1">Claimed tickets will appear here once scanned.</p>
                </div>
            <?php else: ?>
                <?php foreach ($claimedReservations as $i => $res):
                    $name     = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                    $email    = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '');
                    $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #'.($res['resource_id']??'?')));
                    $pcNumbers = '';
                    if (!empty($res['pc_numbers'])) {
                        try { $arr = json_decode($res['pc_numbers'],true); $pcNumbers = is_array($arr) ? implode(', ',$arr) : $res['pc_numbers']; }
                        catch(\Exception $e){ $pcNumbers = $res['pc_numbers']; }
                    } elseif (!empty($res['pc_number'])) { $pcNumbers = $res['pc_number']; }
                    $claimedISO  = !empty($res['claimed_at']) ? date('Y-m-d', strtotime($res['claimed_at'])) : '';
                    $claimedDate = !empty($res['claimed_at']) ? date('M j, Y', strtotime($res['claimed_at'])) : '—';
                    $claimedTime = !empty($res['claimed_at']) ? date('g:i A', strtotime($res['claimed_at'])) : '—';
                    $searchText  = strtolower("$name $email $resource {$res['e_ticket_code']} {$res['purpose']}");
                ?>
                    <div class="claim-card"
                         data-claimed="<?= $claimedISO ?>"
                         data-search="<?= htmlspecialchars($searchText, ENT_QUOTES) ?>">

                        <!-- Top: avatar + name/email + ticket chip -->
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-black text-sm flex-shrink-0">
                                <?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="chip-ticket flex-shrink-0 text-[10px] !px-2 !py-1"><?= htmlspecialchars($res['e_ticket_code'] ?? '—') ?></span>
                        </div>

                        <!-- Resource + workstation -->
                        <div class="mb-2">
                            <div class="flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-desktop text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs font-bold text-slate-700 truncate"><?= $resource ?></p>
                                <?php if ($pcNumbers): ?>
                                    <span class="chip-ws text-[10px] !px-1.5 !py-0.5 flex-shrink-0"><?= htmlspecialchars($pcNumbers) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs text-slate-500 font-semibold"><?= htmlspecialchars($res['reservation_date'] ?? '—') ?></p>
                                <span class="text-[10px] text-green-600 font-bold"><?= htmlspecialchars($res['start_time'] ?? '') ?> – <?= htmlspecialchars($res['end_time'] ?? '') ?></span>
                            </div>
                        </div>

                        <!-- Purpose -->
                        <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= htmlspecialchars($res['purpose'] ?: '—') ?></p>

                        <!-- Footer: claimed at -->
                        <div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-check-double text-[10px] text-purple-500 flex-shrink-0"></i>
                                <p class="text-[10px] text-slate-400 font-semibold">Claimed</p>
                            </div>
                            <div class="text-right">
                                <p class="claimed-date !text-xs"><?= $claimedDate ?></p>
                                <p class="claimed-time !text-[10px]"><?= $claimedTime ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Mobile no-results -->
        <div id="mobileEmpty" class="md:hidden card-empty" style="display:none">
            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-filter-circle-xmark text-xl text-purple-300"></i>
            </div>
            <p class="font-black text-slate-400">No claimed reservations match your search.</p>
        </div>

        <p class="hint">
            <i class="fa-solid fa-info-circle"></i>
            Export to Excel will download all claimed reservations as a CSV file compatible with Excel.
        </p>

    </main>

    <script>
    /* Philippine Time helpers */
    function phNow() {
        const now = new Date();
        return new Date(now.getTime() + (8 * 60 - now.getTimezoneOffset()) * 60000);
    }
    const ph       = phNow();
    const todayISO = ph.toISOString().split('T')[0];
    const weekISO  = (() => {
        const d = phNow(), day = d.getDay();
        d.setDate(d.getDate() + (day === 0 ? -6 : 1 - day));
        return d.toISOString().split('T')[0];
    })();
    const monthISO = ph.toISOString().slice(0, 7) + '-01';

    const allTableRows = Array.from(document.querySelectorAll('#reservationTableBody tr[data-claimed]'));
    const allCards     = Array.from(document.querySelectorAll('#mobileCardList .claim-card'));

    function applyFilters() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const d = document.getElementById('dateFilter').value;
        let n = 0;

        const passes = el => {
            const searchTarget = el.dataset.search || el.textContent.toLowerCase();
            const cd = el.dataset.claimed || '';
            let matchDate = true;
            if      (d === 'today') matchDate = cd === todayISO;
            else if (d === 'week')  matchDate = cd >= weekISO;
            else if (d === 'month') matchDate = cd >= monthISO;
            return (!q || searchTarget.includes(q)) && matchDate;
        };

        // Desktop rows
        allTableRows.forEach(row => {
            const show = passes(row);
            row.style.display = show ? '' : 'none';
            if (show) n++;
        });

        // Mobile cards
        let cardVisible = 0;
        allCards.forEach(card => {
            const show = passes(card);
            card.style.display = show ? '' : 'none';
            if (show) cardVisible++;
        });

        const hasRows = allTableRows.length > 0;
        document.getElementById('noResults').style.display = (hasRows && n === 0) ? 'block' : 'none';

        const mobileEmpty = document.getElementById('mobileEmpty');
        if (allCards.length > 0) mobileEmpty.style.display = cardVisible === 0 ? 'block' : 'none';

        const total = allTableRows.length || allCards.length;
        document.getElementById('resultCount').textContent = `Showing ${n || cardVisible} of ${total} record${total !== 1 ? 's' : ''}`;
    }

    applyFilters();
    </script>
</body>
</html>