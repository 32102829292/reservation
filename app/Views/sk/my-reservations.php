<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | SK</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc; color: #1e293b;
            display: flex; height: 100vh; overflow: hidden;
        }

        /* ── Sidebar — matches user-requests exactly ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav    { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item   { transition: all 0.18s; }
        .sidebar-item.active {
            background: #16a34a; color: white !important;
            box-shadow: 0 8px 20px -4px rgba(22,163,74,0.35);
        }
        .sidebar-item:not(.active):hover { background: #f0fdf4; color: #16a34a; }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Cards ── */
        .dash-card { background: white; border-radius: 28px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); }

        /* ── Stat cards ── */
        .stat-card {
            background: white; border-radius: 20px; padding: 1.1rem 1.25rem;
            border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }

        /* ── Quick tabs ── */
        .qtab {
            display: inline-flex; align-items: center; gap: 6px; padding: 0.45rem 1rem;
            border-radius: 12px; font-size: 0.8rem; font-weight: 700; transition: all 0.18s;
            cursor: pointer; border: 1px solid #e2e8f0; white-space: nowrap;
            color: #64748b; background: white;
        }
        .qtab:hover  { border-color: #16a34a; color: #16a34a; }
        .qtab.active { background: #16a34a; color: white; border-color: #16a34a; box-shadow: 0 4px 12px -2px rgba(22,163,74,0.3); }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 660px; }
        thead th {
            background: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8;
            padding: 0.9rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; cursor: pointer; }
        tbody tr:hover td { background: #f0fdf4; }
        .reservation-row[data-status="declined"] td,
        .reservation-row[data-status="canceled"] td { opacity: 0.6; }

        /* ── Mobile cards ── */
        .res-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s; position: relative; overflow: hidden;
        }
        .res-card:hover { border-color: #bbf7d0; box-shadow: 0 6px 20px -4px rgba(22,163,74,0.15); transform: translateY(-1px); }
        .res-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 0 4px 4px 0; }
        .res-card[data-status="pending"]::before   { background: #f59e0b; }
        .res-card[data-status="approved"]::before  { background: #22c55e; }
        .res-card[data-status="claimed"]::before   { background: #a855f7; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before  { background: #f43f5e; }
        .res-card[data-status="declined"],
        .res-card[data-status="canceled"] { opacity: 0.7; }

        /* ── Status badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.6rem;
            border-radius: 999px; font-size: 0.68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap;
        }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-approved  { background: #dcfce7; color: #166534; }
        .badge-claimed   { background: #f3e8ff; color: #6b21a8; }
        .badge-declined,
        .badge-canceled  { background: #fee2e2; color: #991b1b; }
        .badge-expired   { background: #f1f5f9; color: #475569; }
        .badge-unclaimed { background: #fff7ed; color: #c2410c; border: 1px dashed #fdba74; }

        /* ── Buttons ── */
        .btn-action {
            padding: 0.45rem 0.85rem; border-radius: 10px; font-weight: 700; font-size: 0.75rem;
            transition: all 0.18s; cursor: pointer; border: 1px solid transparent;
            display: inline-flex; align-items: center; gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap;
        }
        .btn-view   { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .btn-view:hover { background: #e2e8f0; color: #1e293b; transform: translateY(-1px); }
        .btn-cancel { background: #fff1f2; color: #991b1b; border-color: #fecdd3; }
        .btn-cancel:hover { background: #ffe4e6; border-color: #fda4af; transform: translateY(-1px); }
        .btn-cancel:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

        /* ── Search input ── */
        .field {
            background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px;
            padding: 0.7rem 1rem 0.7rem 2.5rem; font-size: 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; font-weight: 500;
            transition: all 0.2s; width: 100%;
        }
        .field:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); background: white; }

        /* ── Overlays ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(6px); }

        /* ── Modal ── */
        .modal-box {
            position: relative; margin: auto; background: white; border-radius: 28px;
            width: 94%; max-width: 560px; max-height: 92vh; overflow-y: auto;
            box-shadow: 0 40px 80px rgba(0,0,0,0.2), 0 8px 24px rgba(0,0,0,0.06);
            animation: slideUp 0.22s cubic-bezier(0.34,1.56,0.64,1) both; padding: 2rem;
        }
        .modal-box.sm { max-width: 380px; }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 0; }

        @media (max-width: 639px) {
            .overlay#detailsModal .modal-box,
            .overlay#cancelModal  .modal-box { margin: 0; width: 100%; max-width: 100%; border-radius: 28px 28px 0 0; max-height: 92vh; }
            .overlay#detailsModal,
            .overlay#cancelModal  { align-items: flex-end; }
            .sheet-handle { display: block; }
        }

        @keyframes slideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
        @keyframes fadeUp  { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }

        /* ── Detail rows ── */
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; flex-shrink: 0; }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }

        /* ── Empty states ── */
        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .card-empty  { padding: 3rem 1.5rem; text-align: center; background: white; border-radius: 20px; border: 1px dashed #e2e8f0; }

        /* ── Notification bell ── */
        .notif-bell {
            width: 44px; height: 44px; border-radius: 50%; background: white;
            border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; position: relative; flex-shrink: 0;
        }
        .notif-bell:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.1); transform: scale(1.05); }
        .notif-dot {
            position: absolute; top: -3px; right: -3px;
            background: #ef4444; color: white; font-size: 0.58rem; font-weight: 800;
            padding: 0.15rem 0.4rem; border-radius: 999px; border: 2px solid white;
            min-width: 1.1rem; text-align: center;
        }
        .notif-dropdown {
            position: fixed; top: 76px; right: 24px; width: 340px; background: white;
            border-radius: 20px; box-shadow: 0 24px 48px -8px rgba(0,0,0,0.18), 0 0 0 1px rgba(0,0,0,0.06);
            z-index: 300; display: none; overflow: hidden;
        }
        .notif-dropdown.show { display: block; animation: fadeIn 0.18s ease; }
        .notif-item { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0fdf4; }
        .notif-item:last-child { border-bottom: none; }

        /* ── Toasts ── */
        .toast-container { position: fixed; top: 80px; right: 24px; left: 24px; z-index: 2000; pointer-events: none; display: flex; flex-direction: column; align-items: flex-end; }
        @media (min-width: 640px) { .toast-container { left: auto; width: 360px; } }
        .toast-message {
            background: white; border-radius: 16px; padding: 1rem;
            box-shadow: 0 12px 28px rgba(0,0,0,0.1); margin-bottom: 0.75rem;
            pointer-events: auto; width: 100%; border: 1px solid #e2e8f0;
            animation: slideUp 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .toast-message.approved { border-left: 4px solid #10b981; }
        .toast-message.declined { border-left: 4px solid #ef4444; }
        .toast-message.pending  { border-left: 4px solid #f59e0b; }

        .fade-up { animation: fadeUp 0.35s ease both; }

        @media (max-width: 640px) {
            .notif-dropdown { right: 10px; left: 10px; width: auto; top: 70px; }
        }
    </style>
</head>
<body>

    <?php
    $navItems = [
        ['url' => '/sk/dashboard',       'icon' => 'fa-house',           'label' => 'Dashboard',        'key' => 'dashboard'],
        ['url' => '/sk/reservations',    'icon' => 'fa-calendar-alt',    'label' => 'All Reservations', 'key' => 'reservations'],
        ['url' => '/sk/new-reservation', 'icon' => 'fa-plus',            'label' => 'New Reservation',  'key' => 'new-reservation'],
        ['url' => '/sk/user-requests',   'icon' => 'fa-users',           'label' => 'User Requests',    'key' => 'user-requests'],
        ['url' => '/sk/my-reservations', 'icon' => 'fa-calendar',        'label' => 'My Reservations',  'key' => 'my-reservations'],
        ['url' => '/sk/books',           'icon' => 'fa-book-open',       'label' => 'Library',          'key' => 'books'],
        ['url' => '/sk/scanner',         'icon' => 'fa-qrcode',          'label' => 'Scanner',          'key' => 'scanner'],
        ['url' => '/sk/profile',         'icon' => 'fa-regular fa-user', 'label' => 'Profile',          'key' => 'profile'],
    ];

    $myTotal    = count($reservations ?? []);
    $myPending  = count(array_filter($reservations ?? [], fn($r) => ($r['status'] ?? '') === 'pending'));
    $myApproved = count(array_filter($reservations ?? [], fn($r) => ($r['status'] ?? '') === 'approved' && empty($r['claimed'])));
    $myClaimed  = count(array_filter($reservations ?? [], fn($r) => !empty($r['claimed']) && $r['claimed'] == 1));
    $myDeclined = count(array_filter($reservations ?? [], fn($r) => in_array($r['status'] ?? '', ['declined','canceled'])));
    ?>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Details Modal -->
    <div id="detailsModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('detailsModal')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div class="flex items-center justify-between mb-1">
                <p id="modalId" class="text-xs font-black text-slate-400 font-mono"></p>
                <button onclick="closeModal('detailsModal')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-black text-slate-900">Reservation Details</h3>
                <span id="modalStatusBadge" class="badge"></span>
            </div>
            <div id="modalStatusBar" class="px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold mb-5"></div>
            <div id="modalBody" class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-5"></div>
            <div class="bg-white border-2 border-dashed border-green-100 rounded-2xl p-6 flex flex-col items-center mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">E-Ticket / Access QR</p>
                <canvas id="qrCanvas" class="mx-auto rounded-xl"></canvas>
                <p id="qrCodeText" class="text-xs text-slate-400 font-mono mt-3 text-center break-all px-2"></p>
                <button onclick="downloadTicket()" class="mt-4 flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition">
                    <i class="fa-solid fa-download"></i> Download E-Ticket
                </button>
            </div>
            <div id="modalActions" class="flex gap-3">
                <button onclick="closeModal('detailsModal')" class="flex-1 py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('cancelModal')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-lg font-black">Cancel Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p class="text-slate-600 text-sm mt-3 font-bold" id="cancelConfirmName"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeModal('cancelModal')" class="flex-1 py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Keep it</button>
                <button id="confirmCancelBtn" class="flex-1 py-3.5 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-xmark"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>

    <form id="cancelForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="cancelId">
    </form>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6" style="height:100vh;overflow:hidden;">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] == 'my-reservations' && $myPending > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $myPending ?></span>
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
                $cls = ($page==$item['key']) ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 <?= $cls ?> relative">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    <?php if ($item['key'] == 'my-reservations' && $myPending > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full"><?= $myPending ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main -->
    <main class="flex-1 min-w-0 p-4 lg:p-10 pb-32" style="height:100vh;overflow-y:auto;">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 fade-up">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">SK Portal</p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">My Reservations</h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5">Track and manage your personal reservation requests.</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="<?= base_url('/sk/new-reservation') ?>" class="flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
                <div class="notif-bell" onclick="toggleNotifications()" id="bellBtn">
                    <i class="fa-regular fa-bell text-slate-500"></i>
                    <span id="notificationBadge" class="notif-dot" style="display:none">0</span>
                </div>
            </div>
        </header>

        <!-- Notification dropdown -->
        <div id="notificationDropdown" class="notif-dropdown">
            <div class="p-3 border-b border-slate-100 flex justify-between items-center">
                <span class="font-extrabold text-sm text-slate-800">Status Updates</span>
                <button onclick="markAllAsRead()" class="text-xs text-green-600 hover:text-green-700 font-bold">Mark all read</button>
            </div>
            <div id="notificationList" class="max-h-80 overflow-y-auto"></div>
        </div>

        <!-- Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-5 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-solid fa-circle-check text-green-500"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Stat cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <?php foreach ([
                ['Total',    $myTotal,    'border-slate-400',   'text-slate-700',   'All time'],
                ['Pending',  $myPending,  'border-amber-400',   'text-amber-600',   'Awaiting review'],
                ['Approved', $myApproved, 'border-emerald-400', 'text-emerald-600', 'Ready to use'],
                ['Claimed',  $myClaimed,  'border-purple-400',  'text-purple-600',  'Tickets used'],
            ] as [$lbl, $val, $border, $color, $sub]): ?>
                <div class="stat-card <?= $border ?>">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?= $lbl ?></p>
                    <p class="text-2xl font-black <?= $color ?>"><?= $val ?></p>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5"><?= $sub ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Banners -->
        <?php if ($myPending > 0): ?>
            <div class="mb-5 px-5 py-4 bg-amber-50 border border-amber-200 text-amber-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-regular fa-clock text-amber-500"></i>
                You have <span class="bg-white border border-amber-200 px-2 py-0.5 rounded-full font-black mx-1"><?= $myPending ?></span>
                pending reservation<?= $myPending != 1 ? 's' : '' ?> awaiting SK officer approval.
            </div>
        <?php endif; ?>
        <?php if (isset($approvedCount) && $approvedCount > 0): ?>
            <div class="mb-5 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                You have <span class="bg-white border border-green-200 px-2 py-0.5 rounded-full font-black mx-1"><?= $approvedCount ?></span>
                approved reservation<?= $approvedCount != 1 ? 's' : '' ?>. Download your e-ticket below.
            </div>
        <?php endif; ?>

        <!-- Filter bar -->
        <div class="dash-card mb-4">
            <div class="p-4 lg:p-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1 min-w-0">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="text" id="searchInput" class="field" placeholder="Search by name, asset, date…" oninput="applyFilters()">
                    </div>
                    <button onclick="clearFilters()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center gap-2 flex-shrink-0">
                        <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                    </button>
                </div>
                <div class="flex gap-2 mt-3 overflow-x-auto pb-0.5" style="-webkit-overflow-scrolling:touch;">
                    <button class="qtab active" data-tab="all"     onclick="setTab(this,'all')">
                        <i class="fa-solid fa-layer-group text-xs"></i> All
                        <span class="text-[9px] font-black opacity-70"><?= $myTotal ?></span>
                    </button>
                    <button class="qtab" data-tab="pending"        onclick="setTab(this,'pending')">
                        <i class="fa-regular fa-clock text-xs"></i> Pending
                        <?php if ($myPending > 0): ?>
                            <span class="bg-amber-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $myPending ?></span>
                        <?php endif; ?>
                    </button>
                    <button class="qtab" data-tab="approved"       onclick="setTab(this,'approved')">
                        <i class="fa-solid fa-circle-check text-xs"></i> Approved
                    </button>
                    <button class="qtab" data-tab="claimed"        onclick="setTab(this,'claimed')">
                        <i class="fa-solid fa-check-double text-xs"></i> Claimed
                    </button>
                    <button class="qtab" data-tab="declined"       onclick="setTab(this,'declined')">
                        <i class="fa-solid fa-xmark text-xs"></i> Declined
                    </button>
                </div>
            </div>
            <div class="px-5 py-2.5 flex items-center justify-between">
                <p id="resultCount" class="text-xs font-bold text-slate-400"></p>
                <p class="text-xs text-slate-300 font-semibold hidden sm:block">Click any row to view details &amp; download e-ticket</p>
            </div>
        </div>

        <!-- Desktop Table -->
        <div id="desktopTableWrap" class="hidden md:block dash-card mb-4">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:52px">ID</th>
                            <th>Name</th>
                            <th>Asset</th>
                            <th>Schedule</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th class="text-right" style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-regular fa-calendar-xmark text-5xl text-slate-200 mb-4 block"></i>
                                    <p class="font-black text-slate-400 text-lg">No reservations yet</p>
                                    <p class="text-slate-300 text-sm mt-1 mb-4">Make your first reservation to get started.</p>
                                    <a href="<?= base_url('/sk/new-reservation') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition">
                                        <i class="fa-solid fa-plus"></i> New Reservation
                                    </a>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php
                            $statusIcons = ['pending'=>'fa-clock','approved'=>'fa-circle-check','claimed'=>'fa-check-double','declined'=>'fa-ban','canceled'=>'fa-ban','expired'=>'fa-hourglass-end','unclaimed'=>'fa-ticket'];
                            foreach ($reservations as $res):
                                $status    = $res['status'] ?? 'pending';
                                $isClaimed = !empty($res['claimed']) && $res['claimed'] == 1;
                                if ($isClaimed) $status = 'claimed';
                                $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                                $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                $pcNumbers = '';
                                if (!empty($res['pc_numbers'])) {
                                    $decoded = json_decode($res['pc_numbers'], true);
                                    $pcNumbers = is_array($decoded) ? implode(', ', $decoded) : $res['pc_numbers'];
                                } elseif (!empty($res['pc_number'])) {
                                    $pcNumbers = $res['pc_number'];
                                }
                                $displayStatus = $status === 'unclaimed' ? 'No-show' : ucfirst($status);
                                $rawDate = $res['reservation_date'] ?? '';
                                $fDate   = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                                $fStart  = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                                $fEnd    = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                                $icon    = $statusIcons[$status] ?? 'fa-circle';
                                $mdata   = json_encode([
                                    'id'=>$res['id'],'status'=>$status,'displayStatus'=>$displayStatus,
                                    'name'=>$name,'email'=>htmlspecialchars($res['visitor_email']??$res['user_email']??''),
                                    'resource'=>$resource,'pc'=>$pcNumbers,
                                    'date'=>$fDate,'start'=>$fStart,'end'=>$fEnd,
                                    'purpose'=>htmlspecialchars($res['purpose']??'—'),
                                    'code'=>htmlspecialchars($res['e_ticket_code']??'SK-'.$res['id'].'-'.($res['reservation_date']??'')),
                                ]);
                            ?>
                                <tr class="reservation-row"
                                    data-status="<?= $status ?>"
                                    data-id="<?= $res['id'] ?>"
                                    data-search="<?= strtolower("$name $resource $rawDate $status") ?>"
                                    onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                                    <td><span class="text-xs font-black text-slate-400 font-mono">#<?= $res['id'] ?></span></td>
                                    <td>
                                        <p class="font-bold text-sm text-slate-800 leading-tight"><?= $name ?></p>
                                        <?php if (!empty($res['visitor_email']) || !empty($res['user_email'])): ?>
                                            <p class="text-[11px] text-slate-400 mt-0.5"><?= htmlspecialchars($res['visitor_email'] ?? $res['user_email']) ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <p class="font-bold text-sm text-slate-700"><?= $resource ?></p>
                                        <?php if ($pcNumbers): ?>
                                            <div class="flex items-center gap-1 mt-0.5">
                                                <i class="fa-solid fa-desktop text-[9px] text-slate-400"></i>
                                                <span class="text-[11px] text-green-600 font-semibold"><?= htmlspecialchars($pcNumbers) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <p class="text-sm font-bold text-slate-700"><?= $fDate ?></p>
                                        <p class="text-[11px] text-green-500 font-semibold mt-0.5"><?= $fStart ?> – <?= $fEnd ?></p>
                                    </td>
                                    <td>
                                        <span class="text-sm text-slate-500 font-medium" style="display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px">
                                            <?= htmlspecialchars($res['purpose'] ?: '—') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $status ?>">
                                            <i class="fa-solid <?= $icon ?> text-[9px]"></i>
                                            <?= $displayStatus ?>
                                        </span>
                                    </td>
                                    <td class="text-right" onclick="event.stopPropagation()">
                                        <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                            <button onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-action btn-view">
                                                <i class="fa-solid fa-eye text-[11px]"></i> View
                                            </button>
                                            <?php if ($status === 'pending'): ?>
                                                <button onclick="handleCancel(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-action btn-cancel" id="cancelBtn-<?= $res['id'] ?>">
                                                    <i class="fa-solid fa-xmark text-[11px]"></i> Cancel
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
                <i class="fa-solid fa-filter-circle-xmark text-3xl mb-2 block text-slate-200"></i>
                <p class="font-bold text-slate-400">No reservations match your search.</p>
            </div>
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/60">
                <p id="tableFooter" class="text-xs font-bold text-slate-400"></p>
            </div>
        </div>

        <!-- Mobile Card List -->
        <div id="mobileCardList" class="md:hidden space-y-3">
            <?php if (empty($reservations)): ?>
                <div class="card-empty">
                    <i class="fa-regular fa-calendar-xmark text-4xl text-slate-200 mb-3 block"></i>
                    <p class="font-black text-slate-400">No reservations yet</p>
                    <a href="<?= base_url('/sk/new-reservation') ?>" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition">
                        <i class="fa-solid fa-plus"></i> Make one now
                    </a>
                </div>
            <?php else: ?>
                <?php
                $statusIcons = ['pending'=>'fa-clock','approved'=>'fa-circle-check','claimed'=>'fa-check-double','declined'=>'fa-ban','canceled'=>'fa-ban','expired'=>'fa-hourglass-end','unclaimed'=>'fa-ticket'];
                foreach ($reservations as $res):
                    $status    = $res['status'] ?? 'pending';
                    $isClaimed = !empty($res['claimed']) && $res['claimed'] == 1;
                    if ($isClaimed) $status = 'claimed';
                    $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                    $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                    $pcNumbers = '';
                    if (!empty($res['pc_numbers'])) {
                        $decoded = json_decode($res['pc_numbers'], true);
                        $pcNumbers = is_array($decoded) ? implode(', ', $decoded) : $res['pc_numbers'];
                    } elseif (!empty($res['pc_number'])) {
                        $pcNumbers = $res['pc_number'];
                    }
                    $email         = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '');
                    $displayStatus = $status === 'unclaimed' ? 'No-show' : ucfirst($status);
                    $rawDate = $res['reservation_date'] ?? '';
                    $fDate   = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                    $fStart  = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                    $fEnd    = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                    $icon    = $statusIcons[$status] ?? 'fa-circle';
                    $mdata   = json_encode([
                        'id'=>$res['id'],'status'=>$status,'displayStatus'=>$displayStatus,
                        'name'=>$name,'email'=>$email,'resource'=>$resource,'pc'=>$pcNumbers,
                        'date'=>$fDate,'start'=>$fStart,'end'=>$fEnd,
                        'purpose'=>htmlspecialchars($res['purpose']??'—'),
                        'code'=>htmlspecialchars($res['e_ticket_code']??'SK-'.$res['id'].'-'.($res['reservation_date']??'')),
                    ]);
                    $avatarBg = [
                        'pending'   => 'bg-amber-100 text-amber-700',
                        'approved'  => 'bg-emerald-100 text-emerald-700',
                        'claimed'   => 'bg-purple-100 text-purple-700',
                        'declined'  => 'bg-red-100 text-red-600',
                        'canceled'  => 'bg-red-100 text-red-600',
                        'unclaimed' => 'bg-orange-100 text-orange-600',
                        'expired'   => 'bg-slate-100 text-slate-500',
                    ][$status] ?? 'bg-slate-100 text-slate-500';
                ?>
                    <div class="res-card"
                         data-id="<?= $res['id'] ?>"
                         data-status="<?= $status ?>"
                         data-search="<?= strtolower("$name $resource $rawDate $status") ?>"
                         onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-2xl <?= $avatarBg ?> flex items-center justify-center font-black text-sm flex-shrink-0">
                                <?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="badge badge-<?= $status ?> flex-shrink-0">
                                <i class="fa-solid <?= $icon ?> text-[9px]"></i><?= $displayStatus ?>
                            </span>
                        </div>

                        <div class="mb-2">
                            <div class="flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-desktop text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs font-bold text-slate-700 truncate"><?= $resource ?><?= $pcNumbers ? ' · ' . htmlspecialchars($pcNumbers) : '' ?></p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs text-slate-500 font-semibold"><?= $fDate ?></p>
                                <span class="text-[10px] text-green-600 font-bold"><?= $fStart ?> – <?= $fEnd ?></span>
                            </div>
                        </div>

                        <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= htmlspecialchars($res['purpose'] ?: '—') ?></p>

                        <?php if ($status === 'pending'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'
                                    class="flex-1 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-eye text-[10px]"></i> View
                                </button>
                                <button onclick="handleCancel(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" id="cancelBtn-<?= $res['id'] ?>"
                                    class="flex-1 h-9 rounded-xl bg-red-50 hover:bg-red-500 hover:text-white text-red-600 border border-red-100 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-xmark text-[10px]"></i> Cancel
                                </button>
                            </div>
                        <?php elseif ($status === 'approved'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'
                                    class="flex-1 h-9 rounded-xl bg-green-50 hover:bg-green-600 hover:text-white text-green-700 border border-green-200 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-download text-[10px]"></i> Get E-Ticket
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

        <div id="mobileEmpty" class="md:hidden card-empty" style="display:none">
            <i class="fa-solid fa-filter-circle-xmark text-4xl text-slate-200 mb-3 block"></i>
            <p class="font-black text-slate-400">No reservations match your search.</p>
        </div>

    </main>

    <script>
    const reservationsData = <?= json_encode($reservations ?? []) ?>;
    let cancelTargetId = null;
    let curTab = 'all';

    const allTableRows = Array.from(document.querySelectorAll('#reservationTableBody .reservation-row'));
    const allCards     = Array.from(document.querySelectorAll('#mobileCardList .res-card'));

    /* ── Tabs ── */
    function setTab(btn, tab) {
        document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        curTab = tab;
        applyFilters();
    }

    /* ── Filters ── */
    function applyFilters() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        let n = 0;
        const matches = el => {
            const st = el.dataset.status;
            const matchTab = curTab === 'all' ||
                (curTab === 'declined' && ['declined','canceled'].includes(st)) ||
                st === curTab;
            const matchSearch = !q || (el.dataset.search || '').includes(q);
            return matchTab && matchSearch;
        };
        allTableRows.forEach(row => { const show = matches(row); row.style.display = show ? '' : 'none'; if (show) n++; });
        let cardVisible = 0;
        allCards.forEach(card => { const show = matches(card); card.style.display = show ? '' : 'none'; if (show) cardVisible++; });
        const total = allTableRows.length;
        document.getElementById('resultCount').textContent = `Showing ${n} of ${total} reservation${total !== 1 ? 's' : ''}`;
        const tf = document.getElementById('tableFooter');
        if (tf) tf.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
        const noResults = document.getElementById('noResults');
        if (noResults) noResults.classList.toggle('hidden', n > 0);
        const mobileEmpty = document.getElementById('mobileEmpty');
        if (allCards.length > 0) mobileEmpty.style.display = cardVisible === 0 ? 'block' : 'none';
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        curTab = 'all';
        document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === 'all'));
        applyFilters();
    }

    /* ── Detail modal ── */
    const STATUS_META = {
        pending:   { icon: 'fa-clock',        bg: '#fef3c7', color: '#92400e', label: 'Pending — Awaiting SK officer approval' },
        approved:  { icon: 'fa-circle-check', bg: '#dcfce7', color: '#166534', label: 'Approved — Download your e-ticket below' },
        claimed:   { icon: 'fa-check-double', bg: '#f3e8ff', color: '#6b21a8', label: 'Claimed — Ticket was successfully scanned' },
        unclaimed: { icon: 'fa-ticket',       bg: '#fff7ed', color: '#c2410c', label: 'No-show — Approved slot was not attended' },
        declined:  { icon: 'fa-ban',          bg: '#fee2e2', color: '#991b1b', label: 'Declined — Try booking a different time' },
        canceled:  { icon: 'fa-ban',          bg: '#fee2e2', color: '#991b1b', label: 'Cancelled' },
        expired:   { icon: 'fa-hourglass-end',bg: '#f1f5f9', color: '#475569', label: 'Expired — Request passed date unapproved' },
    };

    function openDetailModal(d) {
        const m = STATUS_META[d.status] || STATUS_META.pending;
        document.getElementById('modalId').textContent = 'Request #' + d.id;
        const badge = document.getElementById('modalStatusBadge');
        badge.textContent = d.displayStatus || d.status;
        badge.className   = `badge badge-${d.status}`;
        const bar = document.getElementById('modalStatusBar');
        bar.style.background = m.bg; bar.style.color = m.color;
        bar.innerHTML = `<i class="fa-solid ${m.icon} flex-shrink-0"></i><span class="text-sm font-bold">${m.label}</span>`;
        document.getElementById('modalBody').innerHTML = `
            <div class="detail-row"><span class="detail-label">Name</span><span class="detail-value">${d.name}</span></div>
            ${d.email ? `<div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">${d.email}</span></div>` : ''}
            <div class="detail-row"><span class="detail-label">Asset</span><span class="detail-value">${d.resource}${d.pc ? ' · ' + d.pc : ''}</span></div>
            <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value">${d.date}</span></div>
            <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">${d.start} – ${d.end}</span></div>
            <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value">${d.purpose}</span></div>
        `;
        QRCode.toCanvas(document.getElementById('qrCanvas'), d.code, { width: 180, margin: 1, color: { dark: '#1e293b', light: '#ffffff' } });
        document.getElementById('qrCodeText').textContent = d.code;
        const acts = document.getElementById('modalActions');
        if (d.status === 'pending') {
            acts.innerHTML = `
                <button onclick="closeModal('detailsModal');handleCancel(${d.id},'${d.name.replace(/'/g,"\\'")}');"
                    class="flex-1 py-3.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Cancel Reservation
                </button>
                <button onclick="closeModal('detailsModal')" class="flex-1 py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>`;
        } else {
            acts.innerHTML = `<button onclick="closeModal('detailsModal')" class="flex-1 py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>`;
        }
        openModal('detailsModal');
    }

    function downloadTicket() {
        const canvas = document.getElementById('qrCanvas');
        const code   = document.getElementById('qrCodeText').textContent;
        const link   = document.createElement('a');
        link.download = `E-Ticket-${code}.png`; link.href = canvas.toDataURL('image/png'); link.click();
    }

    /* ── Cancel ── */
    function handleCancel(id, name) {
        cancelTargetId = id;
        document.getElementById('cancelConfirmName').textContent = name ? `"${name}"` : '';
        openModal('cancelModal');
    }
    document.getElementById('confirmCancelBtn').addEventListener('click', function () {
        if (!cancelTargetId) return;
        this.disabled = true; this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Canceling…';
        document.getElementById('cancelId').value = cancelTargetId;
        document.getElementById('cancelForm').submit();
    });

    /* ── Modals ── */
    function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal('detailsModal'); closeModal('cancelModal'); } });

    /* ── Notifications ── */
    let notifications = [], unreadCount = 0, lastCheckTime = new Date().toISOString();
    document.addEventListener('DOMContentLoaded', function () {
        if ('Notification' in window) Notification.requestPermission();
        loadNotifications();
        setInterval(checkForStatusUpdates, 30000);
        document.addEventListener('visibilitychange', () => { if (!document.hidden) checkForStatusUpdates(); });
        const p = new URLSearchParams(window.location.search);
        if (p.get('status') && p.get('message')) showToast({ title: p.get('status') === 'approved' ? '✓ Approved!' : '✗ Declined', message: decodeURIComponent(p.get('message')), status: p.get('status') });
    });
    function checkForStatusUpdates() {
        fetch('/sk/check-reservation-updates', {
            method:'POST', headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({ last_check: lastCheckTime, user_id: <?= $userId ?? 'null' ?> })
        }).then(r=>r.json()).then(data=>{
            (data.updates||[]).forEach(u=>{
                const n={id:u.id,title:u.status==='approved'?'✓ Reservation Approved!':'✗ Reservation Declined',
                    message:`Your reservation for ${u.resource_name} on ${new Date(u.reservation_date).toLocaleDateString()} has been ${u.status}.`,
                    time:new Date().toISOString(),read:false,status:u.status};
                addNotification(n); showToast(n);
            });
            lastCheckTime=new Date().toISOString();
        }).catch(()=>{});
    }
    function addNotification(n){notifications.unshift(n);unreadCount++;updateNotificationBadge();renderNotifications();}
    function showToast(n){
        const wrap=document.getElementById('toastContainer'),id='toast-'+Date.now();
        let icon='fa-clock',bg='bg-amber-100',fg='text-amber-600';
        if(n.status==='approved'){icon='fa-circle-check';bg='bg-green-100';fg='text-green-600';}
        else if(n.status==='declined'){icon='fa-circle-xmark';bg='bg-red-100';fg='text-red-600';}
        const t=document.createElement('div');t.id=id;t.className=`toast-message ${n.status||'pending'}`;
        t.innerHTML=`<div class="flex items-start gap-3"><div class="w-8 h-8 ${bg} rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid ${icon} ${fg} text-sm"></i></div><div class="flex-1"><p class="font-black text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 mt-0.5">${n.message}</p></div><button onclick="document.getElementById('${id}').remove()" class="text-slate-300 hover:text-slate-500 flex-shrink-0"><i class="fa-solid fa-xmark text-xs"></i></button></div>`;
        wrap.appendChild(t);setTimeout(()=>{const el=document.getElementById(id);if(el)el.remove();},5000);
    }
    function loadNotifications(){
        reservationsData.forEach(res=>{
            if(res.status!=='approved'&&res.status!=='declined')return;
            if((new Date()-new Date(res.updated_at||res.created_at))/3600000>=24)return;
            notifications.push({id:res.id,title:res.status==='approved'?'✓ Reservation Approved!':'✗ Reservation Declined',
                message:`Your reservation for ${res.resource_name||'Resource'} on ${new Date(res.reservation_date).toLocaleDateString()} has been ${res.status}.`,
                time:res.updated_at||res.created_at,read:false,status:res.status});
        });
        unreadCount=notifications.length;updateNotificationBadge();renderNotifications();
        notifications.forEach(n=>{if(!n.read)showToast(n);});
    }
    function toggleNotifications(){document.getElementById('notificationDropdown').classList.toggle('show');}
    function markAllAsRead(){notifications.forEach(n=>n.read=true);unreadCount=0;updateNotificationBadge();renderNotifications();}
    function updateNotificationBadge(){const b=document.getElementById('notificationBadge');b.style.display=unreadCount>0?'block':'none';b.textContent=unreadCount>9?'9+':unreadCount;}
    function renderNotifications(){
        const list=document.getElementById('notificationList');
        if(!notifications.length){list.innerHTML=`<div class="p-8 text-center"><i class="fa-regular fa-bell-slash text-3xl text-slate-200 mb-2 block"></i><p class="text-sm text-slate-400 font-medium">All caught up!</p></div>`;return;}
        const tAgo=t=>{const s=Math.floor((Date.now()-new Date(t))/1000);if(s<60)return'Just now';if(s<3600)return`${Math.floor(s/60)}m ago`;if(s<86400)return`${Math.floor(s/3600)}h ago`;return`${Math.floor(s/86400)}d ago`;};
        list.innerHTML=[...notifications].sort((a,b)=>new Date(b.time)-new Date(a.time)).map(n=>{
            let icon='fa-clock',bg='bg-amber-100',fg='text-amber-600';
            if(n.status==='approved'){icon='fa-circle-check';bg='bg-green-100';fg='text-green-600';}
            else if(n.status==='declined'){icon='fa-circle-xmark';bg='bg-red-100';fg='text-red-600';}
            return`<div class="notif-item ${!n.read?'unread':''}" onclick="markAsRead(${n.id})"><div class="flex items-start gap-3"><div class="w-8 h-8 ${bg} rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid ${icon} ${fg} text-xs"></i></div><div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 truncate mt-0.5">${n.message}</p><p class="text-[10px] text-slate-400 mt-1">${tAgo(n.time)}</p></div>${!n.read?'<span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0 mt-1.5"></span>':''}</div></div>`;
        }).join('');
    }
    function markAsRead(id){const n=notifications.find(x=>x.id===id);if(n&&!n.read){n.read=true;unreadCount=Math.max(0,unreadCount-1);updateNotificationBadge();renderNotifications();}}
    document.addEventListener('click',e=>{
        const drop=document.getElementById('notificationDropdown'),bell=document.getElementById('bellBtn');
        if(bell&&!bell.contains(e.target)&&drop&&!drop.contains(e.target))drop.classList.remove('show');
    });

    applyFilters();
    </script>
</body>
</html>