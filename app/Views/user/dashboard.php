<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | <?= esc($user_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
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
        .sidebar-item { transition: all 0.2s; border-radius: 20px; }
        .sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 480px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        .stat-card {
            background: white; border-radius: 28px; padding: 1.5rem;
            border: 1px solid #e2e8f0; transition: all 0.2s ease;
        }
        .stat-card:hover { box-shadow: 0 12px 24px -8px rgba(0,0,0,0.08); border-color: #bbf7d0; }

        .dashboard-card {
            background: white; border-radius: 28px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); padding: 1.75rem;
        }

        .calendar-card {
            background: white; border-radius: 28px; padding: 1.5rem;
            border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
        }

        #calendar { height: 400px; width: 100%; font-size: 0.8rem; }

        .fc .fc-toolbar { flex-wrap: wrap; gap: 0.5rem; }
        .fc-toolbar-title { font-size: 1rem !important; font-weight: 800 !important; color: #1e293b !important; }
        .fc-button-primary {
            background: #16a34a !important; border-color: #16a34a !important;
            border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important; font-size: 0.8rem !important; padding: 0.3rem 0.6rem !important;
        }
        .fc-button-primary:hover { background: #15803d !important; }
        .fc-button-primary:not(:disabled):active,
        .fc-button-primary:not(:disabled).fc-button-active { background: #166534 !important; }
        .fc-daygrid-event {
            border-radius: 6px !important; font-size: 0.72rem !important; font-weight: 700 !important;
            padding: 2px 5px !important; border: none !important; cursor: pointer !important;
        }
        .fc-event:hover { transform: scale(1.02); filter: brightness(0.95); }
        .fc-daygrid-day { cursor: pointer !important; transition: background-color 0.2s; }
        .fc-daygrid-day:hover { background-color: #f0fdf4 !important; }
        .fc-day-today { background: #f0fdf4 !important; }
        .fc-day-today .fc-daygrid-day-number { color: #16a34a !important; font-weight: 800 !important; }

        .field-label { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }

        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.65); backdrop-filter: blur(4px);
            z-index: 1000; padding: 1.5rem; overflow-y: auto;
            align-items: center; justify-content: center;
        }
        .modal-backdrop.show { display: flex; }
        .modal-card {
            background: white; border-radius: 32px; width: 100%;
            max-width: 600px; padding: 2rem; max-height: 80vh; overflow-y: auto;
        }
        .date-detail-row {
            display: flex; align-items: center; gap: 1rem;
            padding: 0.75rem; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;
        }
        .date-detail-row:hover { background: #f8fafc; border-radius: 12px; }
        .date-detail-row:last-child { border-bottom: none; }
        .date-detail-time { font-weight: 700; color: #16a34a; min-width: 110px; font-size: 0.85rem; }
        .date-detail-resource { font-weight: 600; color: #1e293b; font-size: 0.9rem; }
        .date-detail-user { color: #64748b; font-size: 0.8rem; margin-top: 2px; }

        .notification-bell { position: relative; cursor: pointer; transition: transform 0.2s; }
        .notification-bell:hover { transform: scale(1.1); }
        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white; font-size: 0.6rem; font-weight: 700;
            padding: 0.2rem 0.4rem; border-radius: 999px; min-width: 1.2rem;
            text-align: center; border: 2px solid white;
        }
        .notification-dropdown {
            position: fixed; top: 80px; right: 24px; width: 320px;
            background: white; border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
            border: 1px solid #e2e8f0; z-index: 1000; display: none;
        }
        .notification-dropdown.show { display: block; }
        .notification-item { padding: 1rem; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; cursor: pointer; }
        .notification-item:hover { background: #f8fafc; }
        .notification-item.unread { background: #f0fdf4; }
        .notification-item:last-child { border-bottom: none; }
        .notification-time { font-size: 0.65rem; color: #94a3b8; margin-top: 0.25rem; }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/profile',          'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];
    ?>

    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Resident Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">my<span class="text-green-600">Space.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = ($page == $item['key']);
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Date Modal -->
    <div id="dateModal" class="modal-backdrop" onclick="handleModalBackdrop(event)">
        <div class="modal-card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-black text-slate-900" id="modalDateTitle"></h3>
                <button onclick="closeDateModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalReservationsList" class="space-y-1"></div>
            <div class="mt-2 text-center text-sm text-slate-400" id="modalEmptyMessage"></div>
            <button onclick="closeDateModal()" class="mt-6 w-full py-3 bg-slate-100 rounded-xl font-bold text-slate-600 hover:bg-slate-200 transition">
                Close
            </button>
        </div>
    </div>

    <main class="flex-1 p-6 lg:p-12 pb-32">
        <div class="fixed top-6 right-6 z-50">
            <div class="notification-bell" onclick="toggleNotifications()">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-200">
                    <i class="fa-regular fa-bell text-lg text-slate-600"></i>
                </div>
                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            </div>
            <div id="notificationDropdown" class="notification-dropdown">
                <div class="p-3 border-b border-slate-100 flex justify-between items-center">
                    <span class="font-bold text-sm">Notifications</span>
                    <button onclick="markAllAsRead()" class="text-xs text-green-600 hover:text-green-700 font-bold">Mark all read</button>
                </div>
                <div id="notificationList" class="max-h-96 overflow-y-auto"></div>
            </div>
        </div>

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Welcome back, <?= esc($user_name) ?>!</h2>
                <p class="text-slate-500 font-medium flex items-center gap-2 mt-1">
                    <i class="fa-regular fa-calendar text-green-600"></i>
                    Here's your reservation overview
                </p>
            </div>
            <a href="<?= base_url('/reservation') ?>" class="px-6 py-3 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700 transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> New Reservation
            </a>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-8 px-6 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-600"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if ($pending > 0): ?>
            <div class="mb-8 px-6 py-4 bg-amber-50 border border-amber-200 text-amber-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-clock text-amber-600"></i>
                You have <span class="bg-white px-2 py-0.5 rounded-full text-amber-700 mx-1"><?= $pending ?></span> pending reservation<?= $pending != 1 ? 's' : '' ?>.
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
            <div class="stat-card border-l-4 border-blue-500">
                <span class="field-label">Total Reservations</span>
                <h3 class="text-3xl font-black text-slate-800 mt-1"><?= $total ?></h3>
            </div>
            <div class="stat-card border-l-4 border-amber-500">
                <span class="field-label">Pending</span>
                <h3 class="text-3xl font-black text-amber-600 mt-1"><?= $pending ?></h3>
            </div>
            <div class="stat-card border-l-4 border-emerald-500">
                <span class="field-label">Approved</span>
                <h3 class="text-3xl font-black text-emerald-600 mt-1"><?= $approved ?></h3>
            </div>
            <div class="stat-card border-l-4 border-rose-500">
                <span class="field-label">Declined/Canceled</span>
                <h3 class="text-3xl font-black text-rose-600 mt-1"><?= $declined ?></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendar -->
            <div class="lg:col-span-2 calendar-card">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800">Community Schedule</h3>
                    <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded-full text-slate-500">Click any date</span>
                </div>
                <div class="flex flex-wrap gap-3 mb-4">
                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-full">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>Pending
                    </span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-full">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Approved
                    </span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-full">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>Declined
                    </span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-full">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span>Claimed
                    </span>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Right Panel -->
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-[32px] p-8 text-white shadow-xl">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-5">
                        <i class="fa-solid fa-calendar-plus text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Need a resource?</h3>
                    <p class="text-green-200 text-sm mb-6">Book facilities, workstations, or meeting rooms instantly.</p>
                    <a href="<?= base_url('/reservation') ?>" class="block text-center bg-white text-green-900 hover:bg-green-50 py-4 rounded-2xl font-bold transition shadow-md">
                        <i class="fa-regular fa-calendar-plus mr-2"></i>Reserve Now
                    </a>
                </div>

                <div class="dashboard-card">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800">Your Recent Bookings</h3>
                    </div>
                    <div class="space-y-3">
                        <?php if (!empty($reservations)): ?>
                            <?php
                            $recent = array_slice($reservations, 0, 4);
                            foreach ($recent as $res):
                                $status = $res['status'] ?? 'pending';
                                if (!empty($res['claimed'])) $status = 'claimed';
                                $statusClass = match($status) {
                                    'approved'            => 'bg-emerald-100 text-emerald-700',
                                    'claimed'             => 'bg-purple-100 text-purple-700',
                                    'declined','canceled' => 'bg-rose-100 text-rose-700',
                                    default               => 'bg-amber-100 text-amber-700',
                                };
                                $date = new DateTime($res['reservation_date']);
                                $formattedDate = $date->format('M j, Y');
                            ?>
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-green-50 transition group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center text-green-700 border border-slate-200 group-hover:border-green-300">
                                            <i class="fa-solid fa-calendar-check text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-800 truncate">
                                                <?= esc($res['resource_name'] ?? 'Resource #' . $res['resource_id']) ?>
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5">
                                                <span><i class="fa-regular fa-calendar mr-1"></i><?= $formattedDate ?></span>
                                                <?php if (!empty($res['start_time'])): ?>
                                                    <span><i class="fa-regular fa-clock mr-1"></i><?= date('g:i A', strtotime($res['start_time'])) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 bg-slate-50 rounded-2xl">
                                <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300 mb-2"></i>
                                <p class="text-sm text-slate-400">No bookings yet</p>
                                <a href="<?= base_url('/reservation') ?>" class="inline-block mt-3 text-xs font-bold text-green-600 hover:underline">
                                    make your first reservation →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const STORAGE_KEY = 'notified_reservation_ids_<?= session()->get('user_id') ?>';
        const reservations = <?= json_encode($reservations ?? []) ?>;
        const allReservationsData = <?= json_encode($allReservations ?? []) ?>;
        let notifications = [];

        function getNotifiedIds() {
            try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); }
            catch(e) { return []; }
        }
        function saveNotifiedIds(ids) { localStorage.setItem(STORAGE_KEY, JSON.stringify(ids)); }

        function markAllAsRead() {
            const allIds = notifications.map(n => n.id);
            saveNotifiedIds([...new Set([...getNotifiedIds(), ...allIds])]);
            notifications.forEach(n => n.read = true);
            updateNotificationBadge();
            renderNotifications();
        }

        function markAsRead(id) {
            const existing = getNotifiedIds();
            if (!existing.includes(id)) saveNotifiedIds([...existing, id]);
            const notif = notifications.find(n => n.id === id);
            if (notif) { notif.read = true; updateNotificationBadge(); renderNotifications(); }
        }

        function loadNotifications() {
            const notifiedIds = getNotifiedIds();
            notifications = reservations
                .filter(res => res.status === 'approved')
                .map(res => ({
                    id: parseInt(res.id),
                    title: 'Reservation Approved!',
                    message: `Your reservation for ${res.resource_name || 'Resource'} on ${new Date(res.reservation_date).toLocaleDateString()} has been approved.`,
                    time: res.updated_at || res.created_at || new Date().toISOString(),
                    read: notifiedIds.includes(parseInt(res.id)),
                }));
            const newOnes = notifications.filter(n => !n.read);
            if (newOnes.length > 0 && 'Notification' in window && Notification.permission === 'granted') {
                newOnes.forEach(n => new Notification(n.title, { body: n.message, tag: 'res-' + n.id }));
            }
            updateNotificationBadge();
            renderNotifications();
        }

        function toggleNotifications() { document.getElementById('notificationDropdown').classList.toggle('show'); }

        function updateNotificationBadge() {
            const badge = document.getElementById('notificationBadge');
            const unread = notifications.filter(n => !n.read).length;
            badge.style.display = unread > 0 ? 'block' : 'none';
            badge.textContent = unread > 9 ? '9+' : unread;
        }

        function renderNotifications() {
            const list = document.getElementById('notificationList');
            if (notifications.length === 0) {
                list.innerHTML = `<div class="text-center py-8 px-4"><i class="fa-regular fa-bell-slash text-3xl text-slate-300 mb-2 block"></i><p class="text-sm text-slate-500">No notifications</p></div>`;
                return;
            }
            list.innerHTML = notifications.sort((a,b) => new Date(b.time) - new Date(a.time)).map(n => `
                <div class="notification-item ${!n.read ? 'unread' : ''}" onclick="markAsRead(${n.id})">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-check text-green-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm text-slate-800">${n.title}</p>
                            <p class="text-xs text-slate-600">${n.message}</p>
                            <p class="notification-time">${formatTimeAgo(n.time)}</p>
                        </div>
                        ${!n.read ? '<span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0 mt-1"></span>' : ''}
                    </div>
                </div>`).join('');
        }

        function formatTimeAgo(time) {
            const s = Math.floor((new Date() - new Date(time)) / 1000);
            if (s < 60) return 'Just now';
            if (s < 3600) return `${Math.floor(s/60)}m ago`;
            if (s < 86400) return `${Math.floor(s/3600)}h ago`;
            return `${Math.floor(s/86400)}d ago`;
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notificationDropdown');
            const bell = document.querySelector('.notification-bell');
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.remove('show');
        });

        // Modal
        function openDateModal(date, dateReservations) {
            document.getElementById('modalDateTitle').textContent =
                new Date(date + 'T00:00:00').toLocaleDateString('en-US', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                });

            const list  = document.getElementById('modalReservationsList');
            const empty = document.getElementById('modalEmptyMessage');
            list.innerHTML = '';

            if (dateReservations && dateReservations.length > 0) {
                empty.textContent = '';
                dateReservations
                    .sort((a,b) => (a.start_time||'').localeCompare(b.start_time||''))
                    .forEach(res => {
                        const isClaimed = res.claimed == 1;
                        const status = isClaimed ? 'claimed' : (res.status||'pending').toLowerCase();
                        const colorMap = {
                            approved: 'bg-emerald-100 text-emerald-700',
                            pending:  'bg-amber-100 text-amber-700',
                            declined: 'bg-rose-100 text-rose-700',
                            canceled: 'bg-rose-100 text-rose-700',
                            claimed:  'bg-purple-100 text-purple-700',
                        };
                        const colorClass = colorMap[status] || 'bg-slate-100 text-slate-700';
                        const timeStr = res.start_time ? res.start_time.substring(0,5) : 'All day';
                        const endStr  = res.end_time   ? ` – ${res.end_time.substring(0,5)}` : '';

                        const row = document.createElement('div');
                        row.className = 'date-detail-row';
                        row.innerHTML = `
                            <div class="date-detail-time">${timeStr}${endStr}</div>
                            <div class="flex-1">
                                <div class="date-detail-resource">${res.resource_name || 'Unknown Resource'}</div>
                                <div class="date-detail-user">${res.visitor_name || res.full_name || 'Guest'}</div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold ${colorClass}">
                                ${status.charAt(0).toUpperCase()+status.slice(1)}
                            </span>`;
                        list.appendChild(row);
                    });
            } else {
                empty.textContent = 'No reservations for this date.';
            }

            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDateModal() {
            document.getElementById('dateModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function handleModalBackdrop(e) {
            if (e.target.classList.contains('modal-backdrop')) closeDateModal();
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

        document.addEventListener('DOMContentLoaded', function() {
            if ('Notification' in window) Notification.requestPermission();
            loadNotifications();

            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            const reservationsByDate = {};
            allReservationsData.forEach(res => {
                if (!res.reservation_date) return;
                if (!reservationsByDate[res.reservation_date]) reservationsByDate[res.reservation_date] = [];
                reservationsByDate[res.reservation_date].push(res);
            });

            const colorMap = {
                approved: '#10b981',
                pending:  '#fbbf24',
                declined: '#f87171',
                canceled: '#f87171',
                claimed:  '#a855f7',
            };

            const events = allReservationsData
                .filter(r => r.reservation_date)
                .map(r => {
                    const isClaimed = r.claimed == 1;
                    const status = isClaimed ? 'claimed' : (r.status || 'pending').toLowerCase();
                    const rawDate = r.reservation_date.trim();
                    return {
                        title: r.resource_name || 'Reservation',
                        start: rawDate + (r.start_time ? 'T' + r.start_time.substring(0,8) : ''),
                        end:   rawDate + (r.end_time   ? 'T' + r.end_time.substring(0,8)   : ''),
                        allDay: !r.start_time,
                        backgroundColor: colorMap[status] || '#94a3b8',
                        borderColor: 'transparent',
                        textColor: '#fff',
                    };
                });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
                events,
                height: 400,
                eventDisplay: 'block',
                eventMaxStack: 2,

                dateClick: function(info) {
                    openDateModal(info.dateStr, reservationsByDate[info.dateStr] || []);
                },

                eventClick: function(info) {
                    const date = info.event.startStr.split('T')[0];
                    openDateModal(date, reservationsByDate[date] || []);
                },

                dayCellDidMount: function(info) {
                    const date = info.date.toISOString().split('T')[0];
                    const dayRes = reservationsByDate[date];
                    if (dayRes && dayRes.length > 0) {
                        const badge = document.createElement('div');
                        badge.className = 'text-[9px] font-bold text-white bg-green-600 rounded-full w-4 h-4 flex items-center justify-center ml-auto mr-1 mb-1';
                        badge.textContent = dayRes.length;
                        info.el.querySelector('.fc-daygrid-day-top').appendChild(badge);
                    }
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>