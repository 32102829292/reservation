<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b;
            overflow-x: hidden;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* Original Sidebar Styles - Restored */
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
            width: 100%;
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

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item { 
            transition: all 0.2s; 
            border-radius: 20px;
        }
        .sidebar-item.active {
            background: #16a34a;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(22, 163, 74, 0.3);
        }

        /* Mobile Nav - Original */
        .mobile-nav-pill {
            position: fixed; 
            bottom: 20px; 
            left: 50%; 
            transform: translateX(-50%);
            width: 92%; 
            max-width: 600px; 
            background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); 
            border-radius: 24px; 
            padding: 6px;
            z-index: 100; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { 
            display: flex; 
            gap: 4px; 
            overflow-x: auto; 
            scroll-behavior: smooth; 
            -webkit-overflow-scrolling: touch; 
        }
        .mobile-scroll-container::-webkit-scrollbar { 
            display: none; 
        }

        main { 
            flex: 1;
            min-width: 0;
            padding: 1.5rem;
            overflow-y: auto;
            max-width: calc(100vw - 320px);
        }
        
        @media (max-width: 1024px) {
            main {
                max-width: 100vw;
            }
        }

        /* Stat Cards */
        .stat-card { 
            background: white; 
            border-radius: 20px; 
            padding: 1.25rem; 
            border: 1px solid #e2e8f0; 
            transition: transform 0.2s ease;
            height: 100%;
        }
        .stat-card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); 
        }

        /* Analytics Cards */
        .analytics-card {
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            height: 100%;
        }

        /* Chart containers */
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
            margin-top: 1rem;
            flex: 1;
        }

        /* Calendar Card */
        .calendar-card {
            background: white;
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            height: 100%;
            overflow: hidden;
        }

        #calendar {
            height: 400px;
            width: 100%;
            font-size: 0.8rem;
        }

        /* Fix FullCalendar overflow and make dates clickable */
        .fc .fc-toolbar {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .fc .fc-toolbar-title {
            font-size: 1rem !important;
        }
        .fc .fc-button {
            padding: 0.3rem 0.6rem !important;
            font-size: 0.75rem !important;
        }
        .fc-daygrid-day-frame {
            min-height: 40px;
            cursor: pointer !important;
        }
        .fc-daygrid-day {
            cursor: pointer !important;
            transition: background-color 0.2s;
        }
        .fc-daygrid-day:hover {
            background-color: #f0fdf4 !important;
        }
        .fc-daygrid-day-number {
            cursor: pointer !important;
            font-weight: 600;
            padding: 4px !important;
        }
        .fc-daygrid-day-number:hover {
            color: #16a34a !important;
            text-decoration: underline;
        }
        .fc-event {
            cursor: pointer !important;
            transition: transform 0.1s;
        }
        .fc-event:hover {
            transform: scale(1.02);
            filter: brightness(0.95);
        }

        /* Date click indicator */
        .fc-day-today {
            background-color: #f0fdf4 !important;
            font-weight: bold;
        }

        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-claimed { background: #f3e8ff; color: #6b21a8; }

        /* Notification styles */
        .notification-bell {
            position: relative;
            cursor: pointer;
        }
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.2rem 0.4rem;
            border-radius: 999px;
            min-width: 1.2rem;
            text-align: center;
            border: 2px solid white;
        }

        .notification-dropdown {
            position: fixed;
            top: 80px;
            right: 24px;
            width: 320px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
            border: 1px solid #e2e8f0;
            z-index: 1000;
            display: none;
        }
        .notification-dropdown.show { display: block; }

        .toast-container {
            position: fixed;
            top: 80px;
            right: 24px;
            width: 320px;
            z-index: 2000;
            pointer-events: none;
        }

        @media (max-width: 1024px) {
            .chart-container {
                height: 200px;
            }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Resource badges */
        .resource-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
        }

        .pending-highlight {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        /* Date details modal */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 1000;
            padding: 1.5rem;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
        }
        .modal-backdrop.show {
            display: flex;
        }
        .modal-card {
            background: white;
            border-radius: 32px;
            width: 100%;
            max-width: 600px;
            padding: 2rem;
            max-height: 80vh;
            overflow-y: auto;
        }
        .date-detail-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .date-detail-row:last-child {
            border-bottom: none;
        }
        .date-detail-time {
            font-weight: 700;
            color: #16a34a;
            min-width: 100px;
        }
        .date-detail-resource {
            font-weight: 600;
            color: #1e293b;
        }
        .date-detail-user {
            color: #64748b;
            font-size: 0.85rem;
        }
        .date-detail-status {
            margin-left: auto;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
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

        <!-- Date Details Modal -->
        <div id="dateModal" class="modal-backdrop" onclick="handleModalBackdrop(event)">
            <div class="modal-card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-black text-slate-900" id="modalDateTitle"></h3>
                    <button onclick="closeDateModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
                <div id="modalReservationsList" class="space-y-2">
                    <!-- Reservations will be loaded here -->
                </div>
                <div class="mt-6 text-center text-sm text-slate-500" id="modalEmptyMessage"></div>
                <button onclick="closeDateModal()" class="mt-6 w-full py-3 bg-slate-100 rounded-xl font-bold text-slate-600 hover:bg-slate-200 transition">
                    Close
                </button>
            </div>
        </div>

        <!-- Notification Bell -->
        <div class="fixed top-6 right-6 z-50">
            <div class="notification-bell" onclick="toggleNotifications()">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-200">
                    <i class="fa-regular fa-bell text-lg text-slate-600"></i>
                </div>
                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            </div>
        </div>
        
        <!-- Notification Dropdown -->
        <div id="notificationDropdown" class="notification-dropdown">
            <div class="p-3 border-b border-slate-100 font-bold text-sm">Notifications</div>
            <div id="notificationList" class="max-h-96 overflow-y-auto"></div>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="toast-container"></div>

        <!-- Sidebar - ORIGINAL DESIGN RESTORED -->
        <aside class="hidden lg:block w-80 flex-shrink-0 p-6">
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
                                <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    <?= $pending ?>
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

        <!-- Mobile Nav - ORIGINAL DESIGN RESTORED -->
        <nav class="lg:hidden mobile-nav-pill">
            <div class="mobile-scroll-container text-white px-2">
                <?php foreach ($navItems as $item):
                    $isActive = (isset($page) && $page == $item['key']);
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
        <main>
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Dashboard & Analytics</h2>
                    <p class="text-sm text-slate-500">Welcome back, <?= htmlspecialchars($sk_name ?? 'SK Officer') ?>.</p>
                </div>
                <div class="flex gap-2">
                    <?php if (($pending ?? 0) > 0): ?>
                        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-xl font-bold text-xs flex items-center gap-1">
                            <i class="fa-solid fa-clock text-xs"></i>
                            <span><?= $pending ?> pending</span>
                        </div>
                    <?php endif; ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-3 py-1.5 rounded-xl font-bold text-xs flex items-center gap-1">
                        <i class="fa-solid fa-calendar text-xs"></i>
                        <span><?= date('M j, Y') ?></span>
                    </div>
                </div>
            </div>

            <!-- Analytics Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Reservations -->
                <div class="analytics-card">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Total</span>
                        <span class="text-xs text-green-600 font-bold">+<?= $monthlyTotal ?? 0 ?> mo</span>
                    </div>
                    <div class="text-2xl font-black text-slate-800 mb-1"><?= $total ?? 0 ?></div>
                    <div class="text-xs text-slate-400">Daily avg: <span class="font-bold text-green-600"><?= $total > 0 ? round($total / 30, 1) : 0 ?></span></div>
                </div>

                <!-- Approval Rate -->
                <div class="analytics-card">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Approval</span>
                        <span class="text-xs font-bold text-green-600">
                            <?php $approvalRate = $total > 0 ? round(($approved / $total) * 100) : 0; echo $approvalRate . '%'; ?>
                        </span>
                    </div>
                    <div class="text-2xl font-black text-slate-800 mb-2"><?= $approved ?? 0 ?> approved</div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5">
                        <div class="bg-green-600 rounded-full h-1.5" style="width: <?= $approvalRate ?>%"></div>
                    </div>
                </div>

                <!-- Today's Stats -->
                <div class="analytics-card">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Today</span>
                        <span class="text-xs font-bold text-amber-600"><?= $todayTotal ?? 0 ?> total</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1 text-center">
                        <div>
                            <div class="text-lg font-black text-amber-600"><?= $todayPending ?? 0 ?></div>
                            <div class="text-[9px] text-slate-400">Pending</div>
                        </div>
                        <div>
                            <div class="text-lg font-black text-green-600"><?= $todayApproved ?? 0 ?></div>
                            <div class="text-[9px] text-slate-400">Approved</div>
                        </div>
                        <div>
                            <div class="text-lg font-black text-purple-600"><?= $todayClaimed ?? 0 ?></div>
                            <div class="text-[9px] text-slate-400">Claimed</div>
                        </div>
                    </div>
                </div>

                <!-- Utilization -->
                <div class="analytics-card">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Utilization</span>
                        <span class="text-xs font-bold text-purple-600"><?= $claimed ?? 0 ?> used</span>
                    </div>
                    <div class="text-2xl font-black text-slate-800 mb-2">
                        <?php $utilizationRate = $approved > 0 ? round(($claimed / $approved) * 100) : 0; echo $utilizationRate . '%'; ?>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5">
                        <div class="bg-purple-600 rounded-full h-1.5" style="width: <?= $utilizationRate ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <!-- Trend Chart -->
                <div class="chart-card">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-800">Reservations Trend</h3>
                            <p class="text-xs text-slate-400">Last 7 days</p>
                        </div>
                        <span class="flex items-center gap-1 text-xs">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-slate-500">Reservations</span>
                        </span>
                    </div>
                    <div class="chart-container">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Resource Chart -->
                <div class="chart-card">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-800">Popular Resources</h3>
                            <p class="text-xs text-slate-400">Most reserved</p>
                        </div>
                        <span class="resource-badge">Top 5</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="resourceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="stat-card border-l-4 border-green-500">
                    <div class="text-xs font-bold text-slate-400 mb-1">Total</div>
                    <div class="text-2xl font-black text-slate-800"><?= $total ?? 0 ?></div>
                </div>
                <div class="stat-card border-l-4 border-amber-500 <?= ($pending ?? 0) > 0 ? 'pending-highlight' : '' ?>">
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

            <!-- Calendar + Right Panel -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Calendar -->
                <div class="lg:col-span-2 calendar-card">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-calendar-day text-green-600"></i>
                        <h3 class="font-bold text-slate-800">Calendar</h3>
                        <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded-full text-slate-500">Click any date</span>
                    </div>
                    <div class="flex flex-wrap gap-3 mb-3">
                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>Pending
                        </span>
                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Approved
                        </span>
                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>Declined
                        </span>
                        <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-purple-400"></span>Claimed
                        </span>
                    </div>
                    <div id="calendar"></div>
                </div>

                <!-- Right Panel -->
                <div class="space-y-4">
                    <!-- Pending Approvals -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-slate-800">Pending</h3>
                            <?php if (($pendingUserCount ?? 0) > 0): ?>
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-1 rounded-full"><?= $pendingUserCount ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1">
                            <?php if (!empty($pendingReservations)): ?>
                                <?php foreach (array_slice($pendingReservations, 0, 3) as $res): ?>
                                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs">
                                        <div class="font-bold text-slate-800"><?= htmlspecialchars($res['resource_name'] ?? 'Resource') ?></div>
                                        <div class="text-slate-500"><?= htmlspecialchars($res['visitor_name'] ?? 'Unknown') ?></div>
                                        <div class="text-slate-600 mt-1"><?= htmlspecialchars($res['reservation_date'] ?? '') ?> · <?= htmlspecialchars($res['start_time'] ?? '') ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-xs text-slate-400 text-center py-4">No pending</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Top Resources -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200">
                        <h3 class="font-bold text-slate-800 mb-3">Top Resources</h3>
                        <div class="space-y-2">
                            <?php if (!empty($topResources)): ?>
                                <?php foreach (array_slice($topResources, 0, 3) as $index => $resource): ?>
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-400">#<?= $index + 1 ?></span>
                                            <span class="text-slate-700"><?= htmlspecialchars($resource['name']) ?></span>
                                        </div>
                                        <span class="font-bold text-green-600"><?= $resource['count'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-xs text-slate-400 text-center py-4">No data</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-2xl p-4 text-white">
                        <h3 class="font-bold text-sm mb-3">Quick Stats</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-[9px] text-green-200">Approval</div>
                                <div class="text-base font-black"><?= $approvalRate ?? 0 ?>%</div>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <div class="text-[9px] text-green-200">Utilization</div>
                                <div class="text-base font-black"><?= $utilizationRate ?? 0 ?>%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        // Store all reservations data for date clicks
        const allReservationsData = <?= json_encode($allReservations ?? []) ?>;

        // Date Modal Functions
        function openDateModal(date, reservations) {
            const modal = document.getElementById('dateModal');
            const dateTitle = document.getElementById('modalDateTitle');
            const listContainer = document.getElementById('modalReservationsList');
            const emptyMessage = document.getElementById('modalEmptyMessage');
            
            const formattedDate = new Date(date).toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            dateTitle.textContent = `Reservations for ${formattedDate}`;
            
            listContainer.innerHTML = '';
            
            if (reservations && reservations.length > 0) {
                emptyMessage.textContent = '';
                
                reservations.sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''));
                
                reservations.forEach(res => {
                    const status = res.claimed ? 'claimed' : (res.status || 'pending').toLowerCase();
                    const statusClass = {
                        'approved': 'bg-emerald-100 text-emerald-700',
                        'pending': 'bg-amber-100 text-amber-700',
                        'declined': 'bg-rose-100 text-rose-700',
                        'claimed': 'bg-purple-100 text-purple-700'
                    }[status] || 'bg-slate-100 text-slate-700';
                    
                    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
                    
                    const timeStr = res.start_time ? res.start_time.substring(0, 5) : 'All day';
                    const endTimeStr = res.end_time ? res.end_time.substring(0, 5) : '';
                    
                    const row = document.createElement('div');
                    row.className = 'date-detail-row';
                    row.innerHTML = `
                        <div class="date-detail-time">${timeStr}${endTimeStr ? ` - ${endTimeStr}` : ''}</div>
                        <div class="flex-1">
                            <div class="date-detail-resource">${res.resource_name || 'Unknown Resource'}</div>
                            <div class="date-detail-user">${res.visitor_name || res.full_name || 'Guest'}</div>
                        </div>
                        <div class="date-detail-status ${statusClass}">${statusText}</div>
                    `;
                    
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', () => {
                        window.location.href = `/sk/reservations?id=${res.id}`;
                    });
                    
                    listContainer.appendChild(row);
                });
            } else {
                emptyMessage.textContent = 'No reservations for this date.';
            }
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDateModal() {
            document.getElementById('dateModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function handleModalBackdrop(event) {
            if (event.target.classList.contains('modal-backdrop')) {
                closeDateModal();
            }
        }

        // Calendar initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Charts initialization (same as before)
            const trendCtx = document.getElementById('trendChart')?.getContext('2d');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) ?>,
                        datasets: [{
                            data: <?= json_encode($chartData ?? [0,0,0,0,0,0,0]) ?>,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            }

            const resourceCtx = document.getElementById('resourceChart')?.getContext('2d');
            if (resourceCtx) {
                new Chart(resourceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: <?= json_encode($resourceLabels ?? ['No Data']) ?>,
                        datasets: [{
                            data: <?= json_encode($resourceData ?? [1]) ?>,
                            backgroundColor: ['#16a34a', '#f59e0b', '#8b5cf6', '#3b82f6', '#ec4899'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // Calendar
            const calendarEl = document.getElementById('calendar');
            if (calendarEl && allReservationsData) {
                const reservationsByDate = {};
                allReservationsData.forEach(res => {
                    if (res.reservation_date) {
                        if (!reservationsByDate[res.reservation_date]) {
                            reservationsByDate[res.reservation_date] = [];
                        }
                        reservationsByDate[res.reservation_date].push(res);
                    }
                });

                const events = allReservationsData
                    .filter(r => r.reservation_date)
                    .map(r => {
                        const status = r.claimed ? 'claimed' : (r.status || 'pending');
                        const colors = {
                            approved: '#10b981',
                            pending: '#fbbf24',
                            declined: '#f87171',
                            claimed: '#a855f7'
                        };
                        return {
                            title: r.resource_name || 'Reservation',
                            start: r.reservation_date + (r.start_time ? 'T' + r.start_time : ''),
                            end: r.reservation_date + (r.end_time ? 'T' + r.end_time : ''),
                            backgroundColor: colors[status] || '#94a3b8',
                            borderColor: 'transparent',
                            textColor: '#fff'
                        };
                    });

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
                    events: events,
                    height: 350,
                    eventDisplay: 'block',
                    eventMaxStack: 2,
                    
                    dateClick: function(info) {
                        const date = info.dateStr;
                        const dayReservations = reservationsByDate[date] || [];
                        openDateModal(date, dayReservations);
                    },
                    
                    eventClick: function(info) {
                        const date = info.event.startStr.split('T')[0];
                        const dayReservations = reservationsByDate[date] || [];
                        openDateModal(date, dayReservations);
                    },
                    
                    dayCellDidMount: function(info) {
                        const date = info.date.toISOString().split('T')[0];
                        const dayReservations = reservationsByDate[date];
                        
                        if (dayReservations && dayReservations.length > 0) {
                            const count = dayReservations.length;
                            const badge = document.createElement('div');
                            badge.className = 'text-[9px] font-bold text-white bg-green-600 rounded-full w-4 h-4 flex items-center justify-center ml-auto mr-1 mb-1';
                            badge.textContent = count;
                            info.el.querySelector('.fc-daygrid-day-top').appendChild(badge);
                        }
                    }
                });

                calendar.render();
            }
        });

        function toggleNotifications() {
            document.getElementById('notificationDropdown').classList.toggle('show');
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDateModal();
            }
        });
    </script>
</body>
</html>