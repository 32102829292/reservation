<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>User Reservations | SK Approval</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
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
            min-height: 100vh;
            overflow-y: auto;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; scroll-behavior: smooth; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        main { 
            flex: 1; 
            padding: 1.5rem;
            overflow-y: auto;
            height: 100vh;
        }
        @media (min-width: 1024px) {
            main { padding: 3rem; }
        }

        .content-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }
        th {
            background-color: #f8fafc;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            color: #64748b;
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        td { 
            padding: 1rem; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 0.9rem; 
            font-weight: 500; 
            vertical-align: middle; 
        }
        tr:last-child td { border-bottom: none; }

        input, select {
            background: #fcfdfe;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-radius: 12px;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        input:focus, select:focus { 
            outline: none; 
            border-color: #16a34a; 
            box-shadow: 0 0 0 4px rgba(22,163,74,0.08); 
        }

        .status-badge { 
            padding: 0.35rem 0.75rem; 
            border-radius: 10px; 
            font-size: 0.7rem; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            display: inline-block; 
            white-space: nowrap; 
        }
        .status-pending  { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-declined, .status-canceled { background-color: #fee2e2; color: #991b1b; }

        .btn-action { 
            padding: 0.5rem 0.9rem; 
            border-radius: 10px; 
            font-weight: 700; 
            font-size: 0.78rem; 
            transition: all 0.2s; 
            cursor: pointer; 
            border: none; 
            display: inline-flex; 
            align-items: center; 
            gap: 5px; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            white-space: nowrap; 
        }
        .btn-details { background-color: #f1f5f9; color: #475569; }
        .btn-details:hover { background-color: #e2e8f0; color: #1e293b; }
        .btn-approve { background-color: #dcfce7; color: #166534; }
        .btn-approve:hover { background-color: #bbf7d0; }
        .btn-decline { background-color: #fee2e2; color: #991b1b; }
        .btn-decline:hover { background-color: #fecaca; }

        .modal { 
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(15,23,42,0.65); 
            backdrop-filter: blur(6px); 
            z-index: 1000; 
            padding: 1.5rem; 
            overflow-y: auto; 
            align-items: center; 
            justify-content: center; 
        }
        .modal.show { display: flex; }
        .modal-card { 
            background: white; 
            border-radius: 32px; 
            width: 100%; 
            max-width: 520px; 
            padding: 2.5rem; 
            max-height: 90vh; 
            overflow-y: auto; 
            margin: auto; 
        }

        .detail-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            padding: 0.65rem 0; 
            border-bottom: 1px solid #f1f5f9; 
            gap: 1rem; 
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { 
            font-size: 0.7rem; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.1em; 
            color: #94a3b8; 
            flex-shrink: 0; 
        }
        .detail-value { 
            font-weight: 700; 
            color: #1e293b; 
            font-size: 0.88rem; 
            text-align: right; 
        }

        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .reservation-row { transition: background 0.15s; }
        .reservation-row:hover td { background-color: #f8fafc; }

        /* Notification Styles */
        .notification-bell {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 150;
            cursor: pointer;
            transition: transform 0.2s ease;
            background: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .notification-bell:hover { transform: scale(1.1); }
        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white;
            font-size: 0.6rem; font-weight: 700;
            padding: 0.2rem 0.4rem; border-radius: 999px;
            min-width: 1.2rem; text-align: center;
            animation: pulse 2s infinite; border: 2px solid white;
        }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.1)} }

        .notification-dropdown {
            position: fixed; top: 80px; right: 24px; width: 380px;
            background: white; border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
            border: 1px solid #e2e8f0; z-index: 1000;
            display: none; animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity:0; transform:translateY(-10px); }
            to { opacity:1; transform:translateY(0); }
        }
        .notification-dropdown.show { display: block; }
        .notification-header {
            padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0;
            font-weight: 800; color: #1e293b;
            display: flex; justify-content: space-between; align-items: center;
        }
        .notification-list { max-height: 400px; overflow-y: auto; }
        .notification-item {
            padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s; cursor: pointer;
        }
        .notification-item:hover { background: #f8fafc; }
        .notification-item.unread {
            background: #f0fdf4;
            border-left: 3px solid #16a34a;
        }
        .notification-item.unread:hover {
            background: #dcfce7;
        }
        .notification-time { font-size:0.65rem; color:#94a3b8; margin-top:0.25rem; }
        .notification-empty {
            padding: 3rem 2rem; text-align: center; color: #94a3b8;
        }
        .notification-empty i { font-size:2.5rem; margin-bottom:1rem; color:#cbd5e1; }

        @media (max-width: 640px) {
            .notification-bell { top:16px; right:16px; width:44px; height:44px; }
            .notification-dropdown {
                position: fixed; top:70px; right:10px; left:10px;
                width: auto; max-width: none;
            }
        }

        .toast-container {
            position: fixed;
            top: 80px;
            right: 24px;
            left: 24px;
            z-index: 2000;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        @media (min-width: 640px) { .toast-container { left: auto; width: 380px; } }
        .toast-message {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            margin-bottom: 0.75rem;
            pointer-events: auto;
            width: 100%;
            animation: slideInRight 0.3s ease;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .toast-message:hover {
            transform: translateX(-5px);
        }
        .toast-message.new-request { border-left: 4px solid #f59e0b; }
        @keyframes slideInRight {
            from { transform:translateX(100%); opacity:0; }
            to { transform:translateX(0); opacity:1; }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
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

        <!-- Notification Bell -->
        <div class="notification-bell" onclick="toggleNotifications()">
            <i class="fa-regular fa-bell text-xl text-slate-600"></i>
            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
        </div>
        
        <!-- Notification Dropdown -->
        <div id="notificationDropdown" class="notification-dropdown">
            <div class="notification-header">
                <span>New User Requests</span>
                <button onclick="markAllAsRead()" class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-bold transition">
                    Mark all read
                </button>
            </div>
            <div id="notificationList" class="notification-list">
                <!-- Notifications will be populated here -->
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="toast-container">
            <!-- Toasts will appear here -->
        </div>

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
                        <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                            <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                            <?= $item['label'] ?>
                            <?php if ($item['key'] == 'user-requests' && isset($pendingUserCount) && $pendingUserCount > 0): ?>
                                <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    <?= $pendingUserCount ?>
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
                    $isActive = (isset($page) && $page == $item['key']);
                    $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                        <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                        <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                        <?php if ($item['key'] == 'user-requests' && isset($pendingUserCount) && $pendingUserCount > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full">
                                <?= $pendingUserCount ?>
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
        <main>
            <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">User Reservation Requests</h2>
                    <p class="text-slate-500 font-medium">Review and manage resident reservation requests.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pending</p>
                        <p class="text-xl font-black text-amber-600" id="pendingCount"><?= $pendingUserCount ?? 0 ?></p>
                    </div>
                    <a href="<?= base_url('/sk/new-reservation') ?>" class="flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition flex-shrink-0">
                        <i class="fa-solid fa-plus"></i> New Reservation
                    </a>
                </div>
            </header>

            <!-- Status Summary -->
            <?php if (isset($pendingUserCount) && $pendingUserCount > 0): ?>
                <div class="mb-6 px-6 py-4 bg-amber-50 border border-amber-200 text-amber-700 font-bold rounded-2xl flex items-center gap-3">
                    <i class="fa-solid fa-clock"></i>
                    You have <span class="bg-white px-2 py-0.5 rounded-full text-amber-700 mx-1"><?= $pendingUserCount ?></span> new user request<?= $pendingUserCount != 1 ? 's' : '' ?> waiting for approval.
                </div>
            <?php endif; ?>

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

            <div class="content-card">
                <!-- Filters -->
                <div class="p-4 lg:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="searchInput" class="pl-10" placeholder="Search by name, asset, date…">
                    </div>
                    <select id="statusFilter" class="sm:w-44 flex-shrink-0">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Asset</th>
                                <th>Schedule</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationTableBody">
                            <?php if (empty($userReservations)): ?>
                                <tr><td colspan="7"><div class="empty-state">
                                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                                    <p class="font-bold text-slate-500">No user reservation requests found.</p>
                                </div></td></tr>
                            <?php else: ?>
                                <?php foreach ($userReservations as $res): ?>
                                    <?php
                                        $status    = $res['status'] ?? 'pending';
                                        $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                                        $email     = htmlspecialchars($res['user_email'] ?? '');
                                        $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                        $pcNumbers = $res['pc_number'] ?? '';
                                    ?>
                                    <tr class="reservation-row" data-status="<?= $status ?>" data-id="<?= $res['id'] ?>">
                                        <td><span class="text-slate-400 font-bold">#</span><?= $res['id'] ?></td>
                                        <td>
                                            <div class="font-bold text-slate-800"><?= $name ?></div>
                                            <?php if ($email): ?>
                                                <div class="text-xs text-slate-400 mt-0.5"><?= $email ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-semibold"><?= $resource ?></div>
                                            <?php if ($pcNumbers): ?>
                                                <div class="text-xs text-green-600 font-bold mt-0.5"><?= htmlspecialchars($pcNumbers) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="text-slate-700 font-semibold"><?= htmlspecialchars($res['reservation_date'] ?? '') ?></div>
                                            <div class="text-xs text-green-600 font-bold mt-0.5"><?= htmlspecialchars($res['start_time'] ?? '') ?> – <?= htmlspecialchars($res['end_time'] ?? '') ?></div>
                                        </td>
                                        <td><div class="text-slate-600 max-w-[140px] truncate"><?= htmlspecialchars($res['purpose'] ?: '—') ?></div></td>
                                        <td><span class="status-badge status-<?= $status ?>"><?= ucfirst($status) ?></span></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="viewDetails(<?= $res['id'] ?>)" class="btn-action btn-details">
                                                    <i class="fa-solid fa-eye"></i> View
                                                </button>
                                                <?php if ($status === 'pending'): ?>
                                                    <button onclick="approveReservation(<?= $res['id'] ?>)" class="btn-action btn-approve">
                                                        <i class="fa-solid fa-check"></i> Approve
                                                    </button>
                                                    <button onclick="declineReservation(<?= $res['id'] ?>)" class="btn-action btn-decline">
                                                        <i class="fa-solid fa-times"></i> Decline
                                                    </button>
                                                <?php endif; ?>
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
                    <p class="font-bold">No requests match your search.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal" onclick="handleModalBackdrop(event, 'detailsModal')">
        <div class="modal-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black">Request Details</h3>
                <span id="modalStatusBadge" class="status-badge"></span>
            </div>
            <div id="modalBody" class="bg-slate-50 rounded-3xl p-5 border border-slate-100 mb-5 space-y-1"></div>
            <div class="flex gap-3" id="modalActionButtons">
                <!-- Actions will be populated here -->
            </div>
        </div>
    </div>

    <form id="approveForm" method="POST" action="<?= base_url('sk/approve') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="approveId">
    </form>

    <form id="declineForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="declineId">
    </form>

    <script>
        const userRequestsData = <?= json_encode($userReservations ?? []) ?>;
        
        // Notification System
        let notifications = [];
        let unreadCount = 0;
        let checkInterval;
        let lastCheckTime = new Date().toISOString();

        // Load read status from localStorage
        const readNotifications = JSON.parse(localStorage.getItem('readNotifications') || '[]');

        // Request notification permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            if ('Notification' in window) {
                Notification.requestPermission();
            }
            
            // Load initial notifications
            loadNotifications();
            
            // Check for new user requests every 30 seconds
            checkInterval = setInterval(checkForNewRequests, 30000);
            
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkForNewRequests();
                }
            });
        });

        // Check for new user requests
        function checkForNewRequests() {
            fetch('<?= base_url("sk/check-new-user-requests") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    last_check: lastCheckTime
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.new_requests && data.new_requests.length > 0) {
                    data.new_requests.forEach(request => {
                        // Check if this notification hasn't been read
                        if (!readNotifications.includes(request.id.toString())) {
                            const notification = {
                                id: request.id,
                                title: 'New Reservation Request',
                                message: `${request.visitor_name} requested ${request.resource_name}`,
                                time: new Date().toISOString(),
                                read: false,
                                data: request
                            };
                            
                            addNotification(notification);
                            showPushNotification(notification);
                            showToast(notification);
                        }
                    });
                    
                    lastCheckTime = new Date().toISOString();
                }
            })
            .catch(error => console.error('Error checking requests:', error));
        }

        // Add notification
        function addNotification(notification) {
            notifications.unshift(notification);
            unreadCount++;
            updateNotificationBadge();
            renderNotifications();
        }

        // Show push notification
        function showPushNotification(notification) {
            if ('Notification' in window && Notification.permission === 'granted') {
                const pushNotif = new Notification(notification.title, {
                    body: notification.message,
                    icon: '/favicon.ico',
                    badge: '/favicon.ico',
                    tag: 'new-request-' + notification.id,
                    renotify: true,
                    vibrate: [200, 100, 200],
                    data: { id: notification.id }
                });

                pushNotif.onclick = function(event) {
                    event.preventDefault();
                    window.focus();
                    markAsRead(notification.id);
                    viewDetails(notification.id);
                    this.close();
                };
            }
        }

        // Show toast
        function showToast(notification) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + notification.id;
            
            // Check if toast already exists
            if (document.getElementById(toastId)) return;
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = 'toast-message new-request';
            toast.onclick = function() {
                markAsRead(notification.id);
                viewDetails(notification.id);
                this.remove();
            };
            
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-amber-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm text-slate-800">${notification.title}</p>
                        <p class="text-xs text-slate-600 mt-1">${notification.message}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Just now</p>
                    </div>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                const toastEl = document.getElementById(toastId);
                if (toastEl) toastEl.remove();
            }, 5000);
        }

        // Load initial notifications
        function loadNotifications() {
            userRequestsData.forEach(res => {
                if (res.status === 'pending') {
                    const createdDate = new Date(res.created_at);
                    const hoursAgo = (new Date() - createdDate) / (1000 * 60 * 60);
                    
                    // Check if this notification hasn't been read
                    if (hoursAgo < 24 && !readNotifications.includes(res.id.toString())) {
                        notifications.push({
                            id: res.id,
                            title: 'Pending Request',
                            message: `${res.visitor_name} requested ${res.resource_name}`,
                            time: res.created_at,
                            read: false,
                            data: res
                        });
                    }
                }
            });
            
            unreadCount = notifications.length;
            updateNotificationBadge();
            renderNotifications();
        }

        // Toggle notifications
        function toggleNotifications() {
            document.getElementById('notificationDropdown').classList.toggle('show');
        }

        // Mark all as read
        function markAllAsRead() {
            notifications.forEach(n => {
                n.read = true;
                if (!readNotifications.includes(n.id.toString())) {
                    readNotifications.push(n.id.toString());
                }
            });
            unreadCount = 0;
            updateNotificationBadge();
            renderNotifications();
            localStorage.setItem('readNotifications', JSON.stringify(readNotifications));
        }

        // Mark single notification as read
        function markAsRead(id) {
            const notif = notifications.find(n => n.id === id);
            if (notif && !notif.read) {
                notif.read = true;
                unreadCount = Math.max(0, unreadCount - 1);
                
                if (!readNotifications.includes(id.toString())) {
                    readNotifications.push(id.toString());
                }
                
                updateNotificationBadge();
                renderNotifications();
                localStorage.setItem('readNotifications', JSON.stringify(readNotifications));
                
                // Remove any toasts for this notification
                const toast = document.getElementById('toast-' + id);
                if (toast) toast.remove();
            }
        }

        // Update badge
        function updateNotificationBadge() {
            const badge = document.getElementById('notificationBadge');
            if (unreadCount > 0) {
                badge.style.display = 'block';
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
            } else {
                badge.style.display = 'none';
            }
        }

        // Render notifications
        function renderNotifications() {
            const list = document.getElementById('notificationList');
            
            if (notifications.length === 0) {
                list.innerHTML = `<div class="notification-empty"><i class="fa-regular fa-bell-slash"></i><p class="text-sm">No new requests</p></div>`;
                return;
            }

            list.innerHTML = notifications
                .sort((a,b) => new Date(b.time) - new Date(a.time))
                .map(n => `
                    <div class="notification-item ${!n.read ? 'unread' : ''}" onclick="handleNotificationClick(${n.id})">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-clock text-amber-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-sm">${n.title}</p>
                                <p class="text-xs text-slate-600">${n.message}</p>
                                <p class="notification-time">${formatTimeAgo(n.time)}</p>
                            </div>
                            ${!n.read ? '<span class="w-2 h-2 bg-green-500 rounded-full"></span>' : ''}
                        </div>
                    </div>
                `).join('');
        }

        // Handle notification click
        function handleNotificationClick(id) {
            markAsRead(id);
            viewDetails(id);
            document.getElementById('notificationDropdown').classList.remove('show');
        }

        // Format time ago
        function formatTimeAgo(time) {
            const seconds = Math.floor((new Date() - new Date(time)) / 1000);
            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return `${Math.floor(seconds/60)} minutes ago`;
            if (seconds < 86400) return `${Math.floor(seconds/3600)} hours ago`;
            return `${Math.floor(seconds/86400)} days ago`;
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const bell = document.querySelector('.notification-bell');
            
            if (bell && !bell.contains(event.target) && dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Filter table
        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);

        function filterTable() {
            const s = document.getElementById('searchInput').value.toLowerCase();
            const f = document.getElementById('statusFilter').value;
            let count = 0;
            document.querySelectorAll('.reservation-row').forEach(row => {
                const matches = row.textContent.toLowerCase().includes(s) && (!f || row.dataset.status === f);
                row.style.display = matches ? '' : 'none';
                if (matches) count++;
            });
            document.getElementById('pendingCount').textContent = count;
            document.getElementById('noResults').classList.toggle('hidden', count > 0);
        }

        // View details
        function viewDetails(id) {
            const res = userRequestsData.find(r => r.id == id);
            if (!res) return;

            const name = res.visitor_name || res.full_name || 'Guest';
            const email = res.user_email || '—';
            const pcLabel = res.pc_number || '—';

            document.getElementById('modalStatusBadge').textContent = res.status.charAt(0).toUpperCase() + res.status.slice(1);
            document.getElementById('modalStatusBadge').className = `status-badge status-${res.status}`;

            document.getElementById('modalBody').innerHTML = `
                <div class="detail-row"><span class="detail-label">Request #</span><span class="detail-value">#${res.id}</span></div>
                <div class="detail-row"><span class="detail-label">User</span><span class="detail-value">${name}</span></div>
                <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">${email}</span></div>
                <div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value">${res.resource_name || res.resource_id || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Workstation</span><span class="detail-value">${pcLabel}</span></div>
                <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value">${res.reservation_date || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">${res.start_time || '—'} – ${res.end_time || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value">${res.purpose || '—'}</span></div>
            `;

            const actionDiv = document.getElementById('modalActionButtons');
            if (res.status === 'pending') {
                actionDiv.innerHTML = `
                    <button onclick="approveReservation(${res.id})" class="flex-1 py-4 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700">Approve</button>
                    <button onclick="declineReservation(${res.id})" class="flex-1 py-4 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-700">Decline</button>
                `;
            } else {
                actionDiv.innerHTML = `<button onclick="closeModal('detailsModal')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200">Close</button>`;
            }

            openModal('detailsModal');
        }

        // Approve
        function approveReservation(id) {
            if (confirm('Approve this reservation request?')) {
                document.getElementById('approveId').value = id;
                document.getElementById('approveForm').submit();
            }
        }

        // Decline
        function declineReservation(id) {
            if (confirm('Decline this reservation request?')) {
                document.getElementById('declineId').value = id;
                document.getElementById('declineForm').submit();
            }
        }

        // Modal helpers
        function openModal(id) { 
            document.getElementById(id).classList.add('show'); 
            document.body.style.overflow = 'hidden'; 
        }
        function closeModal(id) { 
            document.getElementById(id).classList.remove('show'); 
            document.body.style.overflow = ''; 
        }
        function handleModalBackdrop(e, id) { 
            if (e.target === document.getElementById(id)) closeModal(id); 
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal('detailsModal'); });

        window.addEventListener('beforeunload', function() {
            if (checkInterval) clearInterval(checkInterval);
        });

        filterTable();
    </script>
</body>
</html>