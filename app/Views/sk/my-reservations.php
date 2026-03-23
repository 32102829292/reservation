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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        :root { --green:#16a34a; --green-light:#f0fdf4; --green-dark:#14532d; --slate-bg:#f8fafc; }
        * { box-sizing: border-box; }
        html { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--slate-bg);
            color: #1e293b;
            margin: 0;
            display: flex !important;
            height: 100vh !important;
            overflow: hidden !important;
        }

        /* ── Sidebar — identical to dashboard ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden; width: 100%;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }

        /* ── Mobile Nav — identical to dashboard ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 480px; background: rgba(20,83,45,0.97);
            backdrop-filter: blur(16px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.4);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Content card ── */
        .dash-card {
            background: white; border-radius: 28px; border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
        }

        /* ── Desktop Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 660px; }
        th {
            background: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b;
            padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td {
            padding: 1rem; border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem; font-weight: 500; vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        .reservation-row:hover td { background: #fafffe; }
        .reservation-row[data-status="declined"] td,
        .reservation-row[data-status="canceled"] td { opacity: 0.6; }

        /* ── Mobile cards ── */
        .res-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s;
            position: relative; overflow: hidden;
        }
        .res-card:hover { border-color: #bbf7d0; box-shadow: 0 6px 20px -4px rgba(22,163,74,0.15); transform: translateY(-1px); }
        .res-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 0 4px 4px 0; }
        .res-card[data-status="pending"]::before  { background: #f59e0b; }
        .res-card[data-status="approved"]::before { background: #22c55e; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before { background: #f43f5e; }
        .res-card[data-status="declined"],
        .res-card[data-status="canceled"] { opacity: 0.7; }

        /* ── Status badges — matching dashboard tags ── */
        .status-badge {
            padding: 0.2rem 0.6rem; border-radius: 999px;
            font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;
        }
        .status-pending   { background: #fef3c7; color: #92400e; }
        .status-approved  { background: #dcfce7; color: #166534; }
        .status-claimed   { background: #f3e8ff; color: #6b21a8; }
        .status-declined,
        .status-canceled  { background: #fee2e2; color: #991b1b; }
        .status-expired   { background: #f1f5f9; color: #475569; }
        .status-unclaimed { background: #fff7ed; color: #c2410c; border: 1px dashed #fdba74; }

        /* ── Buttons ── */
        .btn-action {
            padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.78rem;
            transition: all 0.18s; cursor: pointer; border: 1px solid transparent;
            display: inline-flex; align-items: center; gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap;
        }
        .btn-details { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .btn-details:hover { background: #e8f0fe; color: #1e293b; border-color: #cbd5e1; transform: translateY(-1px); }
        .btn-cancel { background: #fff1f2; color: #991b1b; border-color: #fecdd3; }
        .btn-cancel:hover { background: #ffe4e6; border-color: #fda4af; transform: translateY(-1px); }
        .btn-cancel:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

        /* ── Inputs ── */
        input, select {
            background: #f8fafc; border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1.25rem; font-size: 0.85rem; border-radius: 16px; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; font-weight: 500;
            transition: all 0.2s;
        }
        input:focus, select:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); background: white; }

        /* ── Overlays ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(6px); }

        /* ── Modal — matching dashboard modal-card ── */
        .modal-box {
            position: relative; margin: auto; background: white; border-radius: 28px;
            width: 94%; max-width: 560px; max-height: 92vh; overflow-y: auto;
            box-shadow: 0 40px 80px rgba(0,0,0,0.2), 0 8px 24px rgba(0,0,0,0.06);
            animation: slideUp 0.2s cubic-bezier(0.34,1.56,0.64,1) both;
            padding: 2rem;
        }
        .modal-box.sm { max-width: 380px; }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 0; }

        @media (max-width: 639px) {
            .overlay#detailsModal .modal-box,
            .overlay#cancelModal  .modal-box { margin: 0; width: 100%; max-width: 100%; border-radius: 28px 28px 0 0; max-height: 92vh; animation: slideUp 0.28s ease both; }
            .overlay#detailsModal,
            .overlay#cancelModal  { align-items: flex-end; }
            .sheet-handle { display: block; }
        }

        @keyframes slideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }

        /* ── Detail rows ── */
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; flex-shrink: 0; }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }

        /* ── Empty states ── */
        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .card-empty  { padding: 3rem 1.5rem; text-align: center; background: white; border-radius: 20px; border: 1px dashed #e2e8f0; }

        /* ── Notification Bell ── */
        .notif-bell { position: relative; cursor: pointer; transition: transform 0.2s; }
        .notif-bell:hover { transform: scale(1.08); }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white; font-size: 0.58rem; font-weight: 800;
            padding: 0.15rem 0.35rem; border-radius: 999px; min-width: 1.1rem;
            text-align: center; border: 2px solid white; line-height: 1.2;
        }
        .notif-dropdown {
            position: fixed; top: 72px; right: 20px; width: 340px; background: white;
            border-radius: 20px; box-shadow: 0 24px 48px -8px rgba(0,0,0,0.18), 0 0 0 1px rgba(0,0,0,0.06);
            z-index: 200; display: none; overflow: hidden; animation: fadeIn 0.18s ease;
        }
        .notif-dropdown.show { display: block; }
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

        /* ── Quota bar — matching dashboard ── */
        .fairness-bar { height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .fairness-fill { height: 100%; border-radius: 999px; background: var(--green); transition: width 0.6s cubic-bezier(0.34,1.56,0.64,1); }

        .fade-in-up { animation: slideUp 0.4s ease both; }
        .fade-in    { animation: fadeIn 0.4s ease both; }

        @media (max-width: 640px) {
            .notif-dropdown { position: fixed; top: 70px; right: 10px; left: 10px; width: auto; }
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

    $remaining = $remainingReservations ?? 3;
    $maxSlots  = 3;
    $usedSlots = $maxSlots - $remaining;
    ?>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Details Modal -->
    <div id="detailsModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('detailsModal')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-slate-900">Reservation Details</h3>
                <span id="modalStatusBadge" class="status-badge"></span>
            </div>
            <div id="modalBody" class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-5 space-y-1"></div>
            <div class="bg-white border-2 border-dashed border-green-100 rounded-2xl p-6 flex flex-col items-center mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">E-Ticket / Access QR</p>
                <canvas id="qrCanvas" class="mx-auto rounded-xl"></canvas>
                <p id="qrCodeText" class="text-xs text-slate-400 font-mono mt-3 text-center break-all px-2"></p>
                <button onclick="downloadTicket()" class="mt-4 flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition">
                    <i class="fa-solid fa-download"></i> Download E-Ticket
                </button>
            </div>
            <button onclick="closeModal('detailsModal')" class="w-full py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>
        </div>
    </div>

    <!-- Cancel Confirm Modal -->
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
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Resident Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">my<span class="text-green-600">Space.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'bg-green-600 text-white shadow-lg shadow-green-200/50' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm transition-all <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] == 'my-reservations' && isset($pendingCount) && $pendingCount > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <?php if (isset($remainingReservations)): ?>
                <div class="mx-4 mb-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Monthly Quota</span>
                        <span class="text-xs font-black <?= $remaining===0?'text-red-500':($remaining===1?'text-amber-500':'text-green-600') ?>"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                    </div>
                    <div class="fairness-bar">
                        <div class="fairness-fill" style="width:<?= ($usedSlots/$maxSlots)*100 ?>%;<?= $remaining===0?'background:#ef4444':($remaining===1?'background:#f59e0b':'') ?>"></div>
                    </div>
                    <p class="text-[10px] mt-1.5 font-medium <?= $remaining===0?'text-red-500 font-bold':($remaining===1?'text-amber-500 font-semibold':'text-slate-400') ?>">
                        <?php if ($remaining === 0): ?>⚠ No slots left this month
                        <?php elseif ($remaining === 1): ?>⚡ Only 1 slot remaining
                        <?php else: ?><?= $remaining ?> slot<?= $remaining!=1?'s':'' ?> remaining this month<?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
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
                    <?php if ($item['key'] == 'my-reservations' && isset($pendingCount) && $pendingCount > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full"><?= $pendingCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main -->
    <main class="flex-1 min-w-0 p-4 lg:p-10 pb-32" style="height:100vh;overflow-y:auto;">

        <!-- Top bar -->
        <header class="flex items-start justify-between mb-8 gap-4">
            <div class="fade-in-up">
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">My Reservations</h2>
                <p class="text-slate-400 font-medium text-sm mt-1">Track and manage your reservation requests.</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="<?= base_url('/sk/new-reservation') ?>" class="hidden sm:flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200">
                    <i class="fa-solid fa-plus"></i> Reserve
                </a>
                <div class="notif-bell" onclick="toggleNotifications()">
                    <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-200 hover:border-green-300 transition">
                        <i class="fa-regular fa-bell text-slate-600"></i>
                    </div>
                    <span class="notif-badge" id="notificationBadge" style="display:none">0</span>
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

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-in">
                <i class="fa-solid fa-circle-check text-green-500"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-in">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Status banners -->
        <?php if (isset($pendingCount) && $pendingCount > 0): ?>
            <div class="mb-5 px-5 py-4 bg-amber-50 border border-amber-200 text-amber-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-in">
                <i class="fa-regular fa-clock text-amber-500"></i>
                You have <span class="bg-white px-2 py-0.5 rounded-full text-amber-700 mx-1 font-black"><?= $pendingCount ?></span>
                pending reservation<?= $pendingCount != 1 ? 's' : '' ?> awaiting approval.
            </div>
        <?php endif; ?>
        <?php if (isset($approvedCount) && $approvedCount > 0): ?>
            <div class="mb-5 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-in">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                You have <span class="bg-white px-2 py-0.5 rounded-full text-green-700 mx-1 font-black"><?= $approvedCount ?></span>
                approved reservation<?= $approvedCount != 1 ? 's' : '' ?>. Download your e-ticket from the list below.
            </div>
        <?php endif; ?>

        <!-- Filter bar -->
        <div class="dash-card mb-5">
            <div class="p-4 lg:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-3 flex-1 min-w-0">
                    <div class="relative flex-1 min-w-0">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
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
                <div class="text-right flex-shrink-0 pl-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Showing</p>
                    <p class="text-xl font-black text-green-600" id="totalCount">0</p>
                </div>
            </div>
        </div>

        <!-- Desktop Table -->
        <div id="desktopTableWrap" class="hidden md:block dash-card mb-5">
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
                                <i class="fa-regular fa-calendar-xmark text-4xl mb-3 block text-slate-300"></i>
                                <p class="font-bold text-slate-400">No reservations yet.</p>
                                <a href="<?= base_url('/sk/new-reservation') ?>" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition">
                                    <i class="fa-solid fa-plus"></i> Make one now
                                </a>
                            </div></td></tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res):
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
                                    <td><span class="status-badge status-<?= $status ?>"><?= $displayStatus ?></span></td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1 flex-wrap">
                                            <button onclick="viewDetails(<?= $res['id'] ?>)" class="btn-action btn-details">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <?php if ($status === 'pending'): ?>
                                                <button onclick="handleCancel(<?= $res['id'] ?>)" class="btn-action btn-cancel" id="cancelBtn-<?= $res['id'] ?>">
                                                    <i class="fa-solid fa-xmark"></i> Cancel
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
                <i class="fa-solid fa-filter-circle-xmark text-3xl mb-2 block text-slate-300"></i>
                <p class="font-bold text-slate-400">No reservations match your search.</p>
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
                <?php foreach ($reservations as $res):
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
                    $email = htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '');
                    $displayStatus = $status === 'unclaimed' ? 'No-show' : ucfirst($status);
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
                         data-search="<?= strtolower("$name " . ($res['reservation_date'] ?? '') . " $resource") ?>"
                         onclick="viewDetails(<?= $res['id'] ?>)">

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-2xl <?= $avatarBg ?> flex items-center justify-center font-black text-sm flex-shrink-0">
                                <?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="status-badge status-<?= $status ?> flex-shrink-0"><?= $displayStatus ?></span>
                        </div>

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

                        <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= htmlspecialchars($res['purpose'] ?: '—') ?></p>

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
    const reservationsData = <?= json_encode($reservations ?? []) ?>;
    let cancelTargetId = null;

    const allTableRows = Array.from(document.querySelectorAll('#reservationTableBody .reservation-row'));
    const allCards     = Array.from(document.querySelectorAll('#mobileCardList .res-card'));

    /* ── Filters ── */
    function applyFilters() {
        const s = document.getElementById('searchInput').value.toLowerCase();
        const f = document.getElementById('statusFilter').value;
        let n   = 0;
        const matches = el => {
            const matchSearch = !s || (el.dataset.search || el.textContent).toLowerCase().includes(s);
            const matchStatus = !f || el.dataset.status === f;
            return matchSearch && matchStatus;
        };
        allTableRows.forEach(row => { const show = matches(row); row.style.display = show ? '' : 'none'; if (show) n++; });
        let cardVisible = 0;
        allCards.forEach(card => { const show = matches(card); card.style.display = show ? '' : 'none'; if (show) cardVisible++; });
        document.getElementById('totalCount').textContent = n;
        const noResults = document.getElementById('noResults');
        if (noResults) noResults.classList.toggle('hidden', n > 0);
        const mobileEmpty = document.getElementById('mobileEmpty');
        if (allCards.length > 0) mobileEmpty.style.display = cardVisible === 0 ? 'block' : 'none';
    }

    /* ── View Details ── */
    function viewDetails(id) {
        const res = reservationsData.find(r => r.id == id);
        if (!res) return;
        const name = res.visitor_name || res.full_name || 'Guest';
        const code = res.e_ticket_code || `SK-${res.id}-${res.reservation_date}`;
        let pcLabel = '—';
        if (res.pc_numbers) {
            try { const arr = typeof res.pc_numbers === 'string' ? JSON.parse(res.pc_numbers) : res.pc_numbers; pcLabel = Array.isArray(arr) ? arr.join(', ') : arr; }
            catch { pcLabel = res.pc_numbers; }
        } else if (res.pc_number) { pcLabel = res.pc_number; }

        const isClaimed = res.claimed == 1;
        const displayStatus = isClaimed ? 'claimed' : res.status;
        const badge = document.getElementById('modalStatusBadge');
        badge.textContent = displayStatus === 'unclaimed' ? 'No-show' : displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1);
        badge.className   = `status-badge status-${displayStatus}`;

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
        QRCode.toCanvas(document.getElementById('qrCanvas'), code, { width: 180, margin: 1, color: { dark: '#1e293b', light: '#ffffff' } });
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

    /* ── Cancel ── */
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
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status'), message = urlParams.get('message');
        if (status && message) showToast({ title: status === 'approved' ? '✓ Approved!' : '✗ Declined', message: decodeURIComponent(message), status });
    });

    function checkForStatusUpdates() {
        fetch('/sk/check-reservation-updates', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ last_check: lastCheckTime, user_id: <?= $userId ?? 'null' ?> })
        }).then(r => r.json()).then(data => {
            (data.updates || []).forEach(update => {
                const n = {
                    id: update.id,
                    title: update.status === 'approved' ? '✓ Reservation Approved!' : '✗ Reservation Declined',
                    message: `Your reservation for ${update.resource_name} on ${new Date(update.reservation_date).toLocaleDateString()} has been ${update.status}.`,
                    time: new Date().toISOString(), read: false, status: update.status
                };
                addNotification(n); showPushNotification(n); showToast(n);
                updateTableRow(update.id, update.status);
            });
            lastCheckTime = new Date().toISOString();
        }).catch(() => {});
    }

    function addNotification(n) { notifications.unshift(n); unreadCount++; updateNotificationBadge(); renderNotifications(); }

    function showPushNotification(n) {
        if ('Notification' in window && Notification.permission === 'granted')
            new Notification(n.title, { body: n.message, icon: '/favicon.ico', tag: 'status-update', renotify: true });
    }

    function showToast(n) {
        const wrap = document.getElementById('toastContainer');
        const id   = 'toast-' + Date.now();
        let icon = 'fa-clock', bg = 'bg-amber-100', fg = 'text-amber-600';
        if (n.status === 'approved') { icon = 'fa-circle-check'; bg = 'bg-green-100'; fg = 'text-green-600'; }
        else if (n.status === 'declined') { icon = 'fa-circle-xmark'; bg = 'bg-red-100'; fg = 'text-red-600'; }
        const t = document.createElement('div');
        t.id = id; t.className = `toast-message ${n.status || 'pending'}`;
        t.innerHTML = `<div class="flex items-start gap-3">
            <div class="w-8 h-8 ${bg} rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid ${icon} ${fg} text-sm"></i></div>
            <div class="flex-1"><p class="font-black text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 mt-0.5">${n.message}</p></div>
            <button onclick="document.getElementById('${id}').remove()" class="text-slate-300 hover:text-slate-500 flex-shrink-0"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>`;
        wrap.appendChild(t);
        setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 5000);
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
        updateNotificationBadge(); renderNotifications();
        notifications.forEach(n => { if (!n.read) showToast(n); });
    }

    function toggleNotifications() { document.getElementById('notificationDropdown').classList.toggle('show'); }
    function markAllAsRead() { notifications.forEach(n => n.read = true); unreadCount = 0; updateNotificationBadge(); renderNotifications(); }
    function updateNotificationBadge() {
        const b = document.getElementById('notificationBadge');
        b.style.display = unreadCount > 0 ? 'block' : 'none';
        b.textContent   = unreadCount > 9 ? '9+' : unreadCount;
    }
    function renderNotifications() {
        const list = document.getElementById('notificationList');
        if (!notifications.length) {
            list.innerHTML = `<div class="p-8 text-center"><i class="fa-regular fa-bell-slash text-3xl text-slate-200 mb-2 block"></i><p class="text-sm text-slate-400 font-medium">All caught up!</p></div>`;
            return;
        }
        const timeAgo = t => { const s = Math.floor((Date.now() - new Date(t)) / 1000); if (s < 60) return 'Just now'; if (s < 3600) return `${Math.floor(s/60)}m ago`; if (s < 86400) return `${Math.floor(s/3600)}h ago`; return `${Math.floor(s/86400)}d ago`; };
        list.innerHTML = [...notifications].sort((a,b) => new Date(b.time)-new Date(a.time)).map(n => {
            let icon = 'fa-clock', bg = 'bg-amber-100', fg = 'text-amber-600';
            if (n.status === 'approved') { icon = 'fa-circle-check'; bg = 'bg-green-100'; fg = 'text-green-600'; }
            else if (n.status === 'declined') { icon = 'fa-circle-xmark'; bg = 'bg-red-100'; fg = 'text-red-600'; }
            return `<div class="notif-item ${!n.read ? 'unread' : ''}" onclick="markAsRead(${n.id})">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 ${bg} rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid ${icon} ${fg} text-xs"></i></div>
                    <div class="flex-1 min-w-0"><p class="font-bold text-sm text-slate-800">${n.title}</p><p class="text-xs text-slate-500 truncate mt-0.5">${n.message}</p><p class="text-[10px] text-slate-400 mt-1">${timeAgo(n.time)}</p></div>
                    ${!n.read ? '<span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0 mt-1.5"></span>' : ''}
                </div>
            </div>`;
        }).join('');
    }
    function markAsRead(id) {
        const n = notifications.find(x => x.id === id);
        if (n && !n.read) { n.read = true; unreadCount = Math.max(0, unreadCount - 1); updateNotificationBadge(); renderNotifications(); }
    }
    function updateTableRow(reservationId, newStatus) {
        const row = document.querySelector(`.reservation-row[data-id="${reservationId}"]`);
        if (row) {
            const badge = row.querySelector('.status-badge');
            if (badge) { badge.className = `status-badge status-${newStatus}`; badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1); }
            row.dataset.status = newStatus;
            const cancelBtn = row.querySelector('.btn-cancel');
            if (cancelBtn && newStatus !== 'pending') cancelBtn.style.display = 'none';
        }
        const card = document.querySelector(`.res-card[data-id="${reservationId}"]`);
        if (card) {
            const badge = card.querySelector('.status-badge');
            if (badge) { badge.className = `status-badge status-${newStatus} flex-shrink-0`; badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1); }
            card.dataset.status = newStatus;
        }
    }
    document.addEventListener('click', e => {
        const drop = document.getElementById('notificationDropdown');
        const bell = document.querySelector('.notif-bell');
        if (bell && !bell.contains(e.target) && drop && !drop.contains(e.target)) drop.classList.remove('show');
    });

    applyFilters();
    </script>
</body>
</html>