<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | SK</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            background-image:
                radial-gradient(ellipse 900px 500px at 75% -5%, rgba(22,163,74,0.06) 0%, transparent 65%),
                radial-gradient(ellipse 600px 400px at 0% 100%, rgba(22,163,74,0.04) 0%, transparent 60%);
            background-attachment: fixed;
            color: #1e293b;
        }

        /* ── Sidebar ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header {
            flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }
        .sidebar-header h1 { font-family: 'Syne', sans-serif; font-weight: 800; letter-spacing: -0.04em; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .sidebar-item:not(.active):hover { background: #f0fdf4 !important; color: #16a34a !important; transform: translateX(3px); }
        .sidebar-item.active { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; box-shadow: 0 8px 20px rgba(22,163,74,0.28), 0 2px 6px rgba(22,163,74,0.15); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(9,57,26,0.97);
            backdrop-filter: blur(16px); border-radius: 24px; padding: 6px; z-index: 100;
            box-shadow: 0 20px 40px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.06);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; scroll-behavior: smooth; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Content card ── */
        .content-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04), 0 1px 3px rgba(0,0,0,0.03);
            overflow: hidden; transition: box-shadow 0.3s;
        }

        /* ── Desktop Table (md+) ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 660px; }
        th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 800; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b;
            padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td {
            padding: 1rem; border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem; font-weight: 500; vertical-align: middle; transition: background 0.15s;
        }
        tr:last-child td { border-bottom: none; }
        .reservation-row { transition: background 0.15s; }
        .reservation-row:hover td { background: linear-gradient(180deg, #fafffe 0%, #f8fafc 100%); }
        .reservation-row[data-status="declined"] td,
        .reservation-row[data-status="canceled"] td { opacity: 0.6; }

        /* ── Mobile cards ── */
        .res-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s;
            position: relative; overflow: hidden;
        }
        .res-card:hover, .res-card:active {
            border-color: #bbf7d0;
            box-shadow: 0 6px 20px -4px rgba(22,163,74,0.15);
            transform: translateY(-1px);
        }
        .res-card::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 4px; border-radius: 0 4px 4px 0;
        }
        .res-card[data-status="pending"]::before  { background: #f59e0b; }
        .res-card[data-status="approved"]::before { background: #22c55e; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before { background: #f43f5e; }
        .res-card[data-status="declined"],
        .res-card[data-status="canceled"] { opacity: 0.7; }

        /* ── Status badges ── */
        .status-badge {
            padding: 0.35rem 0.75rem; border-radius: 10px;
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.05em; display: inline-flex; align-items: center;
            gap: 5px; white-space: nowrap; border: 1px solid transparent;
        }
        .status-badge::before {
            content: ''; width: 5px; height: 5px;
            border-radius: 50%; flex-shrink: 0; display: inline-block;
        }
        .status-pending  { background-color: #fffbeb; color: #92400e; border-color: #fde68a; }
        .status-pending::before  { background: #f59e0b; box-shadow: 0 0 5px rgba(245,158,11,0.6); }
        .status-approved { background-color: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .status-approved::before { background: #22c55e; box-shadow: 0 0 5px rgba(34,197,94,0.6); }
        .status-declined, .status-canceled { background-color: #fff1f2; color: #991b1b; border-color: #fecdd3; }
        .status-declined::before, .status-canceled::before { background: #f43f5e; }

        /* ── Buttons ── */
        .btn-action {
            padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.78rem;
            transition: all 0.18s cubic-bezier(0.4,0,0.2,1); cursor: pointer;
            border: 1px solid transparent; display: inline-flex; align-items: center; gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap;
        }
        .btn-details { background-color: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .btn-details:hover { background-color: #e8f0fe; color: #1e293b; border-color: #cbd5e1; transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .btn-cancel { background-color: #fff1f2; color: #991b1b; border-color: #fecdd3; }
        .btn-cancel:hover { background-color: #ffe4e6; border-color: #fda4af; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(244,63,94,0.15); }
        .btn-cancel:disabled { opacity: 0.4; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ── Inputs ── */
        input, select {
            background: #fcfdfe; border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1.25rem; font-size: 0.9rem;
            transition: all 0.2s cubic-bezier(0.4,0,0.2,1); border-radius: 12px; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        input:focus, select:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 4px rgba(22,163,74,0.1); background: #fff; }

        /* ── Overlays ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(2,6,23,0.72); backdrop-filter: blur(10px); }

        /* ── Modal box ── */
        .modal-box {
            position: relative; margin: auto; background: white; border-radius: 32px;
            width: 94%; max-width: 520px; max-height: 92vh; overflow-y: auto;
            box-shadow: 0 40px 80px rgba(0,0,0,0.22), 0 8px 24px rgba(0,0,0,0.08);
            animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .modal-box.sm { max-width: 380px; }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* Drag handle — mobile only */
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 0; }

        /* Bottom-sheet on mobile */
        @media (max-width: 639px) {
            .overlay#detailsModal .modal-box,
            .overlay#cancelModal  .modal-box { margin: 0; width: 100%; max-width: 100%; border-radius: 28px 28px 0 0; max-height: 92vh; animation: slideUp 0.28s cubic-bezier(0.34,1.2,0.64,1) both; }
            .overlay#detailsModal,
            .overlay#cancelModal  { align-items: flex-end; }
            .sheet-handle { display: block; }
        }

        @keyframes popIn   { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes slideUp { from { opacity:0; transform:translateY(60px); }            to { opacity:1; transform:none; } }

        /* ── Detail rows ── */
        .detail-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; flex-shrink: 0; }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }

        /* ── Empty states ── */
        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .card-empty  { padding: 3rem 1.5rem; text-align: center; background: white; border-radius: 20px; border: 1px dashed #e2e8f0; }

        /* ── Notification Bell ── */
        .notification-bell {
            position: fixed; top: 24px; right: 24px; z-index: 150; cursor: pointer;
            background: white; width: 48px; height: 48px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0; transition: all 0.2s;
        }
        .notification-bell:hover { transform: scale(1.06) translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .notification-badge {
            position: absolute; top: -5px; right: -5px;
            background: linear-gradient(135deg, #ef4444, #dc2626); color: white;
            font-size: 0.6rem; font-weight: 700; padding: 0.2rem 0.4rem; border-radius: 999px;
            min-width: 1.2rem; text-align: center; animation: pulse 2s infinite; border: 2px solid white;
        }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }

        /* ── Notification dropdown ── */
        .notification-dropdown {
            position: fixed; top: 80px; right: 24px; width: 360px;
            background: white; border-radius: 24px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.14), 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0; z-index: 1000; display: none;
            animation: slideDown 0.22s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px) scale(0.97); } to { opacity:1; transform:none; } }
        .notification-dropdown.show { display: block; }
        .notification-header { padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; font-weight: 800; color: #1e293b; display: flex; justify-content: space-between; align-items: center; }
        .notification-list { max-height: 400px; overflow-y: auto; }
        .notification-item { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notification-item:hover { background: #f8fafc; }
        .notification-item.unread { background: linear-gradient(135deg, #f0fdf4, #fafffe); border-left: 3px solid #16a34a; }
        .notification-item:last-child { border-bottom: none; }
        .notification-time { font-size: 0.65rem; color: #94a3b8; margin-top: 0.25rem; }
        .notification-empty { padding: 3rem 2rem; text-align: center; color: #94a3b8; }

        /* ── Toasts ── */
        .toast-container {
            position: fixed; top: 80px; right: 24px; left: 24px;
            z-index: 2000; pointer-events: none;
            display: flex; flex-direction: column; align-items: flex-end;
        }
        @media (min-width: 640px) { .toast-container { left: auto; width: 380px; } }
        .toast-message {
            background: white; border-radius: 16px; padding: 1rem;
            box-shadow: 0 12px 28px rgba(0,0,0,0.1), 0 2px 6px rgba(0,0,0,0.05);
            margin-bottom: 0.75rem; pointer-events: auto; width: 100%;
            animation: slideInRight 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .toast-message.approved { border-left: 4px solid #10b981; }
        .toast-message.declined { border-left: 4px solid #ef4444; }
        .toast-message.pending  { border-left: 4px solid #f59e0b; }
        @keyframes slideInRight { from { transform:translateX(24px); opacity:0; } to { transform:none; opacity:1; } }

        @media (max-width: 640px) {
            .notification-bell { top: 16px; right: 16px; width: 44px; height: 44px; border-radius: 14px; }
            .notification-dropdown { position: fixed; top: 70px; right: 10px; left: 10px; width: auto; max-width: none; }
        }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
        ['url' => '/sk/dashboard',            'icon' => 'fa-house',           'label' => 'Dashboard',        'key' => 'dashboard'],
        ['url' => '/sk/reservations',         'icon' => 'fa-calendar-alt',    'label' => 'All Reservations', 'key' => 'reservations'],
        ['url' => '/sk/new-reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation',  'key' => 'new-reservation'],
        ['url' => '/sk/user-requests',        'icon' => 'fa-users',           'label' => 'User Requests',    'key' => 'user-requests'],
        ['url' => '/sk/my-reservations',      'icon' => 'fa-calendar',        'label' => 'My Reservations',  'key' => 'my-reservations'],
        ['url' => '/sk/books',                'icon' => 'fa-book-open',       'label' => 'Library',          'key' => 'books'],
        ['url' => '/sk/scanner',              'icon' => 'fa-qrcode',          'label' => 'Scanner',          'key' => 'scanner'],
        ['url' => '/sk/profile',              'icon' => 'fa-regular fa-user', 'label' => 'Profile',          'key' => 'profile'],
    ];
    ?>

    <!-- Notification Bell -->
    <div class="notification-bell" onclick="toggleNotifications()">
        <i class="fa-regular fa-bell text-xl text-slate-600"></i>
        <span class="notification-badge" id="notificationBadge" style="display:none">0</span>
    </div>

    <!-- Notification Dropdown -->
    <div id="notificationDropdown" class="notification-dropdown">
        <div class="notification-header">
            <span>Status Updates</span>
            <button onclick="markAllAsRead()" class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-bold transition">Mark all read</button>
        </div>
        <div id="notificationList" class="notification-list"></div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- ══════════════════════════════════════
         DETAILS MODAL  (bottom-sheet on mobile)
         ══════════════════════════════════════ -->
    <div id="detailsModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('detailsModal')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div class="p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black">Reservation Details</h3>
                    <span id="modalStatusBadge" class="status-badge"></span>
                </div>
                <div id="modalBody" class="bg-slate-50 rounded-3xl p-5 border border-slate-100 mb-5 space-y-1"></div>
                <div class="bg-white border-2 border-dashed border-green-100 rounded-3xl p-6 flex flex-col items-center mb-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">E-Ticket / Access QR</p>
                    <canvas id="qrCanvas" class="mx-auto rounded-xl"></canvas>
                    <p id="qrCodeText" class="text-xs text-slate-400 font-mono mt-3 text-center break-all px-2"></p>
                    <button onclick="downloadTicket()" class="mt-4 flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                        <i class="fa-solid fa-download"></i> Download E-Ticket
                    </button>
                </div>
                <button onclick="closeModal('detailsModal')" class="w-full py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">Close</button>
            </div>
        </div>
    </div>

    <!-- Cancel Confirm Modal  (bottom-sheet on mobile) -->
    <div id="cancelModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('cancelModal')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div class="p-6 lg:p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 class="text-xl font-black">Cancel Reservation?</h3>
                    <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                    <p class="text-slate-600 text-sm mt-3 font-bold" id="cancelConfirmName"></p>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeModal('cancelModal')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">Keep it</button>
                    <button id="confirmCancelBtn" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i> Yes, Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="cancelForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="cancelId">
    </form>

    <!-- ══════════════════════════════════════
         SIDEBAR  (lg+)
         ══════════════════════════════════════ -->
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
                    <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] == 'my-reservations' && isset($pendingCount) && $pendingCount > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingCount ?></span>
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

    <!-- ══════════════════════════════════════
         MOBILE NAV
         ══════════════════════════════════════ -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = (isset($page) && $page == $item['key']);
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?> relative">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    <?php if ($item['key'] == 'my-reservations' && isset($pendingCount) && $pendingCount > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full"><?= $pendingCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ══════════════════════════════════════
         MAIN
         ══════════════════════════════════════ -->
    <main class="flex-1 min-w-0 p-4 lg:p-12 pb-32">

        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">My Reservations</h2>
                <p class="text-slate-500 font-medium">Track and manage your reservation requests.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right flex-shrink-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Showing</p>
                    <p class="text-xl font-black text-green-600" id="totalCount">0</p>
                </div>
                <a href="/sk/new-reservation" class="flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition flex-shrink-0">
                    <i class="fa-solid fa-plus"></i> New
                </a>
            </div>
        </header>

        <!-- Status banners -->
        <?php if (isset($pendingCount) && $pendingCount > 0): ?>
            <div class="mb-6 px-6 py-4 bg-amber-50 border border-amber-200 text-amber-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-clock"></i>
                You have <span class="bg-white px-2 py-0.5 rounded-full text-amber-700 mx-1"><?= $pendingCount ?></span>
                pending reservation<?= $pendingCount != 1 ? 's' : '' ?> waiting for approval.
            </div>
        <?php endif; ?>
        <?php if (isset($approvedCount) && $approvedCount > 0): ?>
            <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-check-circle"></i>
                You have <span class="bg-white px-2 py-0.5 rounded-full text-green-700 mx-1"><?= $approvedCount ?></span>
                approved reservation<?= $approvedCount != 1 ? 's' : '' ?>.
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

        <!-- ── Filter bar ── -->
        <div class="content-card mb-4">
            <div class="p-4 lg:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" class="pl-10" placeholder="Search by name, asset, date…" oninput="applyFilters()">
                </div>
                <select id="statusFilter" class="sm:w-44 flex-shrink-0" onchange="applyFilters()">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Declined</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>
        </div>

        <!-- ══════════════════════════════════════
             DESKTOP TABLE  (md+)
             ══════════════════════════════════════ -->
        <div id="desktopTableWrap" class="hidden md:block content-card mb-4">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:56px">ID</th>
                            <th>Name</th>
                            <th>Asset</th>
                            <th>Schedule</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="7"><div class="empty-state">
                                <i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                                <p class="font-bold text-slate-500">No reservations yet.</p>
                                <a href="/sk/new-reservation" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                                    <i class="fa-solid fa-plus"></i> Make one now
                                </a>
                            </div></td></tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res):
                                $status    = $res['status'] ?? 'pending';
                                $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                                $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                $pcNumbers = '';
                                if (!empty($res['pc_numbers'])) {
                                    $decoded   = json_decode($res['pc_numbers'], true);
                                    $pcNumbers = is_array($decoded) ? implode(', ', $decoded) : $res['pc_numbers'];
                                } elseif (!empty($res['pc_number'])) {
                                    $pcNumbers = $res['pc_number'];
                                }
                            ?>
                                <tr class="reservation-row" data-status="<?= $status ?>" data-id="<?= $res['id'] ?>"
                                    data-search="<?= strtolower("$name " . ($res['reservation_date'] ?? '') . " $resource") ?>">
                                    <td><span class="text-slate-400 font-bold">#</span><?= $res['id'] ?></td>
                                    <td>
                                        <div class="font-bold text-slate-800"><?= $name ?></div>
                                        <?php if (!empty($res['visitor_email']) || !empty($res['user_email'])): ?>
                                            <div class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($res['visitor_email'] ?? $res['user_email']) ?></div>
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
                                        <div class="flex items-center justify-end gap-1 flex-wrap">
                                            <button onclick="viewDetails(<?= $res['id'] ?>)" class="btn-action btn-details">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <?php if ($status === 'pending'): ?>
                                                <button onclick="handleCancel(<?= $res['id'] ?>)" class="btn-action btn-cancel" id="cancelBtn-<?= $res['id'] ?>">
                                                    <i class="fa-solid fa-xmark"></i> Cancel
                                                </button>
                                            <?php else: ?>
                                                <span class="inline-flex items-center text-xs font-bold text-slate-300 px-2">
                                                    <i class="fa-solid fa-ban"></i>
                                                </span>
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
                <p class="font-bold">No reservations match your search.</p>
            </div>
        </div>

        <!-- ══════════════════════════════════════
             MOBILE CARD LIST  (below md)
             ══════════════════════════════════════ -->
        <div id="mobileCardList" class="md:hidden space-y-3">
            <?php if (empty($reservations)): ?>
                <div class="card-empty">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-slate-200 mb-3 block"></i>
                    <p class="font-black text-slate-400">No reservations yet</p>
                    <a href="/sk/new-reservation" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                        <i class="fa-solid fa-plus"></i> Make one now
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($reservations as $res):
                    $status    = $res['status'] ?? 'pending';
                    $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                    $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                    $pcNumbers = '';
                    if (!empty($res['pc_numbers'])) {
                        $decoded   = json_decode($res['pc_numbers'], true);
                        $pcNumbers = is_array($decoded) ? implode(', ', $decoded) : $res['pc_numbers'];
                    } elseif (!empty($res['pc_number'])) {
                        $pcNumbers = $res['pc_number'];
                    }
                    $email = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '');

                    $avatarBg = [
                        'pending'  => 'bg-amber-100 text-amber-700',
                        'approved' => 'bg-emerald-100 text-emerald-700',
                        'declined' => 'bg-red-100 text-red-600',
                        'canceled' => 'bg-red-100 text-red-600',
                    ][$status] ?? 'bg-slate-100 text-slate-500';
                ?>
                    <div class="res-card"
                         data-id="<?= $res['id'] ?>"
                         data-status="<?= $status ?>"
                         data-search="<?= strtolower("$name " . ($res['reservation_date'] ?? '') . " $resource") ?>"
                         onclick="viewDetails(<?= $res['id'] ?>)">

                        <!-- Top: avatar + name/email + badge -->
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-2xl <?= $avatarBg ?> flex items-center justify-center font-black text-sm flex-shrink-0">
                                <?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="status-badge status-<?= $status ?> flex-shrink-0"><?= ucfirst($status) ?></span>
                        </div>

                        <!-- Asset + schedule -->
                        <div class="mb-2">
                            <div class="flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-desktop text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs font-bold text-slate-700 truncate"><?= $resource ?><?= $pcNumbers ? ' · ' . htmlspecialchars($pcNumbers) : '' ?></p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs text-slate-500 font-semibold"><?= htmlspecialchars($res['reservation_date'] ?? '—') ?></p>
                                <span class="text-[10px] text-green-600 font-bold"><?= htmlspecialchars($res['start_time'] ?? '') ?> – <?= htmlspecialchars($res['end_time'] ?? '') ?></span>
                            </div>
                        </div>

                        <!-- Purpose -->
                        <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= htmlspecialchars($res['purpose'] ?: '—') ?></p>

                        <!-- Footer: cancel button or ID -->
                        <?php if ($status === 'pending'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick="handleCancel(<?= $res['id'] ?>)" id="cancelBtn-<?= $res['id'] ?>"
                                    class="flex-1 h-9 rounded-xl bg-red-50 hover:bg-red-500 hover:text-white text-red-600 border border-red-100 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-xmark text-[10px]"></i> Cancel Reservation
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="pt-2.5 border-t border-slate-100">
                                <p class="text-[10px] text-slate-300 font-semibold">#<?= $res['id'] ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Mobile no-results -->
        <div id="mobileEmpty" class="md:hidden card-empty" style="display:none">
            <i class="fa-solid fa-filter-circle-xmark text-4xl text-slate-200 mb-3 block"></i>
            <p class="font-black text-slate-400">No reservations match your search.</p>
        </div>

    </main>

    <script>
    /* ──────────────────────────────────────────
       DATA
    ────────────────────────────────────────── */
    const reservationsData = <?= json_encode($reservations ?? []) ?>;
    let cancelTargetId = null;

    const allTableRows = Array.from(document.querySelectorAll('#reservationTableBody .reservation-row'));
    const allCards     = Array.from(document.querySelectorAll('#mobileCardList .res-card'));

    /* ──────────────────────────────────────────
       FILTERS  (synced across table + cards)
    ────────────────────────────────────────── */
    function applyFilters() {
        const s = document.getElementById('searchInput').value.toLowerCase();
        const f = document.getElementById('statusFilter').value;
        let n   = 0;

        const matches = el => {
            const searchTarget = (el.dataset.search || el.textContent).toLowerCase();
            const matchSearch  = !s || searchTarget.includes(s);
            const matchStatus  = !f || el.dataset.status === f;
            return matchSearch && matchStatus;
        };

        // Desktop rows
        allTableRows.forEach(row => {
            const show = matches(row);
            row.style.display = show ? '' : 'none';
            if (show) n++;
        });

        // Mobile cards
        let cardVisible = 0;
        allCards.forEach(card => {
            const show = matches(card);
            card.style.display = show ? '' : 'none';
            if (show) cardVisible++;
        });

        document.getElementById('totalCount').textContent = n;

        // Desktop no-results
        const noResults = document.getElementById('noResults');
        if (noResults) noResults.classList.toggle('hidden', n > 0);

        // Mobile empty state
        const mobileEmpty = document.getElementById('mobileEmpty');
        if (allCards.length > 0) mobileEmpty.style.display = cardVisible === 0 ? 'block' : 'none';
    }

    /* ──────────────────────────────────────────
       VIEW DETAILS
    ────────────────────────────────────────── */
    function viewDetails(id) {
        const res = reservationsData.find(r => r.id == id);
        if (!res) return;

        const name = res.visitor_name || res.full_name || 'Guest';
        const code = res.e_ticket_code || `SK-${res.id}-${res.reservation_date}`;

        let pcLabel = '—';
        if (res.pc_numbers) {
            try {
                const arr = typeof res.pc_numbers === 'string' ? JSON.parse(res.pc_numbers) : res.pc_numbers;
                pcLabel = Array.isArray(arr) ? arr.join(', ') : arr;
            } catch { pcLabel = res.pc_numbers; }
        } else if (res.pc_number) { pcLabel = res.pc_number; }

        const badge = document.getElementById('modalStatusBadge');
        badge.textContent = res.status.charAt(0).toUpperCase() + res.status.slice(1);
        badge.className   = `status-badge status-${res.status}`;

        document.getElementById('modalBody').innerHTML = `
            <div class="detail-row"><span class="detail-label">Reservation #</span><span class="detail-value">#${res.id}</span></div>
            <div class="detail-row"><span class="detail-label">Name</span><span class="detail-value">${name}</span></div>
            <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">${res.visitor_email || res.user_email || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Type</span><span class="detail-value">${res.visitor_type || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Asset</span><span class="detail-value">${res.resource_name || res.resource_id || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Stations</span><span class="detail-value">${pcLabel}</span></div>
            <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value">${res.reservation_date || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">${res.start_time || '—'} – ${res.end_time || '—'}</span></div>
            <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value">${res.purpose || '—'}</span></div>
        `;

        QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
            width: 180, margin: 1, color: { dark: '#1e293b', light: '#ffffff' }
        });
        document.getElementById('qrCodeText').textContent = code;
        openModal('detailsModal');
    }

    function downloadTicket() {
        const canvas = document.getElementById('qrCanvas');
        const code   = document.getElementById('qrCodeText').textContent;
        const link   = document.createElement('a');
        link.download = `E-Ticket-${code}.png`;
        link.href     = canvas.toDataURL('image/png');
        link.click();
    }

    /* ──────────────────────────────────────────
       CANCEL
    ────────────────────────────────────────── */
    function handleCancel(id) {
        cancelTargetId = id;
        const res  = reservationsData.find(r => r.id == id);
        const name = res ? (res.visitor_name || res.full_name || 'Guest') : '';
        document.getElementById('cancelConfirmName').textContent = name ? `"${name}"` : '';
        openModal('cancelModal');
    }

    document.getElementById('confirmCancelBtn').addEventListener('click', function () {
        if (!cancelTargetId) return;
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Canceling…';
        document.getElementById('cancelId').value = cancelTargetId;
        document.getElementById('cancelForm').submit();
    });

    /* ──────────────────────────────────────────
       UPDATE TABLE ROW  (live status updates)
    ────────────────────────────────────────── */
    function updateTableRow(reservationId, newStatus) {
        // Desktop
        const row = document.querySelector(`.reservation-row[data-id="${reservationId}"]`);
        if (row) {
            const statusCell = row.querySelector('.status-badge');
            if (statusCell) { statusCell.className = `status-badge status-${newStatus}`; statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1); }
            row.dataset.status = newStatus;
            const cancelBtn = row.querySelector('.btn-cancel');
            if (cancelBtn && newStatus !== 'pending') cancelBtn.style.display = 'none';
        }
        // Mobile card
        const card = document.querySelector(`.res-card[data-id="${reservationId}"]`);
        if (card) {
            const statusCell = card.querySelector('.status-badge');
            if (statusCell) { statusCell.className = `status-badge status-${newStatus} flex-shrink-0`; statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1); }
            card.dataset.status = newStatus;
            card.setAttribute('data-status', newStatus);
            // Remove left accent class via inline style trick — just update dataset
        }
    }

    /* ──────────────────────────────────────────
       MODAL HELPERS
    ────────────────────────────────────────── */
    function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal('detailsModal'); closeModal('cancelModal'); } });

    /* ──────────────────────────────────────────
       NOTIFICATION SYSTEM
    ────────────────────────────────────────── */
    let notifications = [];
    let unreadCount   = 0;
    let lastCheckTime = new Date().toISOString();

    document.addEventListener('DOMContentLoaded', function () {
        if ('Notification' in window) Notification.requestPermission();
        loadNotifications();
        setInterval(checkForStatusUpdates, 30000);
        document.addEventListener('visibilitychange', () => { if (!document.hidden) checkForStatusUpdates(); });

        const urlParams = new URLSearchParams(window.location.search);
        const status    = urlParams.get('status');
        const message   = urlParams.get('message');
        if (status && message) showToast({ title: status === 'approved' ? '✓ Approved!' : status === 'declined' ? '✗ Declined' : 'ℹ️ Update', message: decodeURIComponent(message), type: status });
    });

    function checkForStatusUpdates() {
        fetch('/sk/check-reservation-updates', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ last_check: lastCheckTime, user_id: <?= $userId ?? 'null' ?> })
        })
        .then(r => r.json())
        .then(data => {
            (data.updates || []).forEach(update => {
                const notification = {
                    id: update.id,
                    title: update.status === 'approved' ? '✓ Reservation Approved!' : update.status === 'declined' ? '✗ Reservation Declined' : 'ℹ️ Status Update',
                    message: `Your reservation for ${update.resource_name} on ${new Date(update.reservation_date).toLocaleDateString()} has been ${update.status}.`,
                    time: new Date().toISOString(), read: false, status: update.status,
                    resource: update.resource_name, date: update.reservation_date
                };
                addNotification(notification);
                showPushNotification(notification);
                showToast(notification);
                updateTableRow(update.id, update.status);
            });
            lastCheckTime = new Date().toISOString();
        })
        .catch(() => {});
    }

    function addNotification(notification) {
        notifications.unshift(notification);
        unreadCount++;
        updateNotificationBadge();
        renderNotifications();
    }

    function showPushNotification(notification) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(notification.title, { body: notification.message, icon: '/favicon.ico', tag: 'status-update', renotify: true });
        }
    }

    function showToast(notification) {
        const wrap    = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now() + Math.random();
        let icon = 'fa-clock', bgColor = 'bg-amber-100', iconColor = 'text-amber-600';
        if ((notification.status || notification.type) === 'approved') { icon = 'fa-check-circle'; bgColor = 'bg-green-100'; iconColor = 'text-green-600'; }
        else if ((notification.status || notification.type) === 'declined') { icon = 'fa-times-circle'; bgColor = 'bg-red-100'; iconColor = 'text-red-600'; }
        const t = document.createElement('div');
        t.id = toastId; t.className = `toast-message ${notification.status || notification.type || 'pending'}`;
        t.innerHTML = `<div class="flex items-start gap-3">
            <div class="w-8 h-8 ${bgColor} rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid ${icon} ${iconColor}"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-sm text-slate-800">${notification.title}</p>
                <p class="text-xs text-slate-600 mt-1">${notification.message}</p>
                <p class="text-[10px] text-slate-400 mt-1">Just now</p>
            </div>
            <button onclick="document.getElementById('${toastId}').remove()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>`;
        wrap.appendChild(t);
        setTimeout(() => { const el = document.getElementById(toastId); if (el) el.remove(); }, 5000);
    }

    function loadNotifications() {
        reservationsData.forEach(res => {
            if (res.status !== 'approved' && res.status !== 'declined') return;
            const hoursAgo = (new Date() - new Date(res.updated_at || res.created_at)) / 3600000;
            if (hoursAgo >= 24) return;
            notifications.push({
                id: res.id,
                title: res.status === 'approved' ? '✓ Reservation Approved!' : '✗ Reservation Declined',
                message: `Your reservation for ${res.resource_name || 'Resource'} on ${new Date(res.reservation_date).toLocaleDateString()} has been ${res.status}.`,
                time: res.updated_at || res.created_at, read: false, status: res.status,
            });
        });
        unreadCount = notifications.length;
        updateNotificationBadge();
        renderNotifications();
        notifications.forEach(n => { if (!n.read) showToast(n); });
    }

    function toggleNotifications() { document.getElementById('notificationDropdown').classList.toggle('show'); }

    function markAllAsRead() {
        notifications.forEach(n => n.read = true); unreadCount = 0;
        updateNotificationBadge(); renderNotifications();
    }

    function updateNotificationBadge() {
        const b = document.getElementById('notificationBadge');
        b.style.display = unreadCount > 0 ? 'block' : 'none';
        b.textContent   = unreadCount > 9 ? '9+' : unreadCount;
    }

    function renderNotifications() {
        const list = document.getElementById('notificationList');
        if (!notifications.length) {
            list.innerHTML = `<div class="notification-empty"><i class="fa-regular fa-bell-slash text-4xl mb-3 block text-slate-200"></i><p class="text-sm">No status updates</p></div>`;
            return;
        }
        list.innerHTML = [...notifications].sort((a, b) => new Date(b.time) - new Date(a.time)).map(n => {
            let icon = 'fa-clock', bgColor = 'bg-amber-100';
            if (n.status === 'approved') { icon = 'fa-check-circle'; bgColor = 'bg-green-100'; }
            else if (n.status === 'declined') { icon = 'fa-times-circle'; bgColor = 'bg-red-100'; }
            return `<div class="notification-item ${!n.read ? 'unread' : ''}" onclick="markAsRead(${n.id})">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 ${bgColor} rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid ${icon} text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm text-slate-800">${n.title}</p>
                        <p class="text-xs text-slate-600 mt-1">${n.message}</p>
                        <p class="notification-time">${formatTimeAgo(n.time)}</p>
                    </div>
                    ${!n.read ? '<span class="w-2 h-2 bg-green-500 rounded-full mt-1"></span>' : ''}
                </div>
            </div>`;
        }).join('');
    }

    function formatTimeAgo(time) {
        const s = Math.floor((new Date() - new Date(time)) / 1000);
        if (s < 60) return 'Just now';
        if (s < 3600) return `${Math.floor(s / 60)} minutes ago`;
        if (s < 86400) return `${Math.floor(s / 3600)} hours ago`;
        return `${Math.floor(s / 86400)} days ago`;
    }

    function markAsRead(id) {
        const n = notifications.find(x => x.id === id);
        if (n && !n.read) { n.read = true; unreadCount = Math.max(0, unreadCount - 1); updateNotificationBadge(); renderNotifications(); }
    }

    document.addEventListener('click', e => {
        const drop = document.getElementById('notificationDropdown');
        const bell = document.querySelector('.notification-bell');
        if (bell && !bell.contains(e.target) && drop && !drop.contains(e.target)) drop.classList.remove('show');
    });

    /* ── Boot ── */
    applyFilters();
    </script>
</body>
</html>