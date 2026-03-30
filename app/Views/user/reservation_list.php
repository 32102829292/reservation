<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | <?= esc($user_name ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .05);
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

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item {
            transition: all .2s;
        }

        .sidebar-item.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, .3);
        }

        .mobile-nav-pill {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 600px;
            background: rgba(30, 58, 138, .97);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 6px;
            z-index: 100;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, .3);
        }

        .mobile-scroll-container {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            scroll-behavior: smooth;
        }

        .mobile-scroll-container::-webkit-scrollbar {
            display: none;
        }

        main {
            min-width: 0;
        }

        .content-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .02);
            overflow: hidden;
        }

        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 520px;
        }

        th {
            background: #f8fafc;
            font-weight: 800;
            text-transform: uppercase;
            font-size: .68rem;
            letter-spacing: .1em;
            color: #94a3b8;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        td {
            padding: .875rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .reservation-row {
            transition: background .15s;
        }

        .reservation-row:hover td {
            background: #f8fafc;
        }

        .res-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem;
            cursor: pointer;
            transition: all .18s;
            position: relative;
            overflow: hidden;
        }

        .res-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 0 4px 4px 0;
        }

        .res-card[data-status="pending"]::before {
            background: #f59e0b;
        }

        .res-card[data-status="approved"]::before {
            background: #22c55e;
        }

        .res-card[data-status="claimed"]::before {
            background: #a855f7;
        }

        .res-card[data-status="declined"]::before,
        .res-card[data-status="cancelled"]::before {
            background: #ef4444;
        }

        .res-card[data-status="expired"]::before {
            background: #94a3b8;
        }

        .res-card[data-status="unclaimed"]::before {
            background: #f97316;
        }

        .res-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 6px 20px -4px rgba(37, 99, 235, .12);
            transform: translateY(-1px);
        }

        input,
        select {
            background: #fcfdfe;
            border: 1px solid #e2e8f0;
            padding: .75rem 1.25rem;
            font-size: .9rem;
            transition: all .2s;
            border-radius: 12px;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .08);
        }

        .status-badge {
            padding: .35rem .75rem;
            border-radius: 10px;
            font-size: .7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-declined {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-claimed {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .status-expired {
            background: #f1f5f9;
            color: #475569;
        }

        .status-unclaimed {
            background: #fff7ed;
            color: #c2410c;
            border: 1px dashed #fdba74;
        }

        .fairness-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: .5rem 1rem;
            border-radius: 100px;
            font-size: .75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            align-items: center;
            justify-content: center;
        }

        .overlay.show {
            display: flex;
            animation: fadeBg .15s ease;
        }

        @keyframes fadeBg {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        .overlay-bg {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .65);
            backdrop-filter: blur(6px);
        }

        .modal-card {
            position: relative;
            margin: auto;
            background: white;
            border-radius: 28px;
            width: 94%;
            max-width: 480px;
            padding: 1.75rem;
            max-height: 92vh;
            overflow-y: auto;
            animation: popIn .22s cubic-bezier(.34, 1.56, .64, 1) both;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .22);
        }

        .modal-card.sm {
            max-width: 360px;
        }

        .modal-card::-webkit-scrollbar {
            width: 4px;
        }

        .modal-card::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(.92) translateY(16px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .sheet-handle {
            display: none;
            width: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 9999px;
            margin: 0 auto 1rem;
        }

        @media(max-width:639px) {
            .overlay .modal-card {
                margin: 0;
                width: 100%;
                max-width: 100%;
                border-radius: 28px 28px 0 0;
                max-height: 92vh;
                animation: sheetUp .28s cubic-bezier(.34, 1.2, .64, 1) both;
            }

            .overlay {
                align-items: flex-end !important;
            }

            .sheet-handle {
                display: block;
            }
        }

        @keyframes sheetUp {
            from {
                opacity: 0;
                transform: translateY(60px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .6rem 0;
            border-bottom: 1px solid #f1f5f9;
            gap: 1rem;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .detail-value {
            font-weight: 700;
            color: #1e293b;
            font-size: .85rem;
            text-align: right;
            word-break: break-word;
            max-width: 60%;
        }

        .ticket-section {
            background: #eff6ff;
            border: 2px dashed #93c5fd;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .75rem;
        }

        .pending-notice {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .claimed-notice {
            background: #faf5ff;
            border: 2px dashed #d8b4fe;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
        }

        .expired-notice {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .unclaimed-notice {
            background: #fff7ed;
            border: 1.5px dashed #fdba74;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        .card-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            background: white;
            border-radius: 20px;
            border: 1px dashed #e2e8f0;
        }
    </style>
</head>

<body class="flex">
    <?php
    $navItems = [
        ['url' => '/dashboard', 'icon' => 'fa-house', 'label' => 'Dashboard', 'key' => 'dashboard'],
        ['url' => '/reservation', 'icon' => 'fa-plus', 'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar', 'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books', 'icon' => 'fa-book-open', 'label' => 'Library', 'key' => 'books'],
        ['url' => '/profile', 'icon' => 'fa-regular fa-user', 'label' => 'Profile', 'key' => 'profile'],
    ];
    $processed = [];
    foreach (($reservations ?? []) as $res) {
        $isClaimed = !empty($res['claimed']) && $res['claimed'] == 1;
        $status = $isClaimed ? 'claimed' : strtolower($res['status'] ?? 'pending');
        if ($status === 'approved') {
            $edt = strtotime($res['reservation_date'] . ' ' . ($res['end_time'] ?? '23:59:59'));
            if ($edt < time()) $status = 'unclaimed';
        } elseif ($status === 'pending') {
            $rdt = strtotime($res['reservation_date'] ?? '');
            if ($rdt && $rdt < strtotime('today')) $status = 'expired';
        }
        $res['_status'] = $status;
        $processed[] = $res;
    }
    $unclaimedCount = count(array_filter($processed, fn($r) => $r['_status'] === 'unclaimed'));
    $statusIcons = ['pending' => 'fa-regular fa-clock', 'approved' => 'fa-circle-check', 'claimed' => 'fa-check-double', 'declined' => 'fa-xmark', 'cancelled' => 'fa-ban', 'expired' => 'fa-regular fa-hourglass', 'unclaimed' => 'fa-ticket'];
    ?>
    <!-- DETAILS MODAL -->
    <div id="detailsModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('detailsModal')"></div>
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-black text-slate-900">Reservation Details</h3>
                <span id="modalStatusBadge" class="status-badge"></span>
            </div>
            <div id="modalBody" class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-4"></div>
            <div id="pendingNotice" class="hidden pending-notice mb-4">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-hourglass-half text-amber-600"></i></div>
                <div>
                    <p class="font-bold text-amber-800 text-sm">Awaiting Approval</p>
                    <p class="text-xs text-amber-700 mt-0.5">Your e-ticket will appear here once an SK officer approves your reservation.</p>
                </div>
            </div>
            <div id="rejectedNotice" class="hidden mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-ban text-red-400"></i>
                <p class="text-sm font-semibold text-red-700" id="rejectedText"></p>
            </div>
            <div id="qrSection" class="hidden ticket-section mb-4">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-700">E-Ticket · Scan to Enter</p>
                <canvas id="qrCanvas" class="rounded-xl"></canvas>
                <p id="qrCodeText" class="text-xs text-slate-400 font-mono text-center break-all px-2"></p>
                <button onclick="downloadTicket()" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition"><i class="fa-solid fa-download"></i> Download E-Ticket</button>
            </div>
            <div id="claimedNotice" class="hidden claimed-notice mb-4">
                <i class="fa-solid fa-check-double text-3xl text-purple-500"></i>
                <p class="font-extrabold text-purple-700">Ticket Already Used</p>
                <p class="text-xs text-purple-400 text-center">This reservation has been claimed and cannot be used again.</p>
                <p id="claimedAtText" class="text-[10px] text-purple-400 mt-1"></p>
            </div>
            <div id="expiredNotice" class="hidden expired-notice mb-4">
                <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-regular fa-clock text-slate-500"></i></div>
                <div>
                    <p class="font-bold text-slate-600 text-sm">Reservation Expired</p>
                    <p class="text-xs text-slate-400 mt-0.5">This reservation was never approved before the date passed.</p>
                </div>
            </div>
            <div id="unclaimedNotice" class="hidden unclaimed-notice mb-4">
                <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-ticket text-orange-500"></i></div>
                <div>
                    <p class="font-bold text-orange-700 text-sm">Slot Not Used</p>
                    <p class="text-xs text-orange-500 mt-0.5">Your reservation was approved but the e-ticket was never scanned.</p>
                    <p class="text-xs text-orange-400 mt-2 font-semibold">Tip: Repeated no-shows may affect your future booking priority.</p>
                </div>
            </div>
            <button onclick="closeModal('detailsModal')" class="w-full py-3.5 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Close</button>
        </div>
    </div>
    <!-- Cancel Modal -->
    <div id="cancelModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('cancelModal')"></div>
        <div class="modal-card sm">
            <div class="sheet-handle"></div>
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <h3 class="text-xl font-black">Cancel Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1">This action cannot be undone.</p>
                <p class="text-slate-700 text-sm mt-3 font-bold" id="cancelConfirmResource"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeModal('cancelModal')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">Keep it</button>
                <button id="confirmCancelBtn" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition flex items-center justify-center gap-2"><i class="fa-solid fa-xmark"></i> Yes, Cancel</button>
            </div>
        </div>
    </div>
    <form id="cancelForm" method="POST" action="" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="cancelId"></form>
    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Resident Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">my<span class="text-blue-600">Space.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item): $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600'; ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i><?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer"><a href="<?= base_url('/logout') ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all"><i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout</a></div>
        </div>
    </aside>
    <!-- Mobile Nav -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item): $btnClass = ($page == $item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30'; ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i><span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400"><i class="fa-solid fa-arrow-right-from-bracket text-lg"></i><span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span></a>
        </div>
    </nav>
    <!-- Main -->
    <main class="flex-1 min-w-0 p-4 lg:p-12 pb-32">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">My Reservations</h2>
                <p class="text-slate-500 font-medium">Track and manage your booking requests.</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <?php if (isset($remainingReservations)): ?>
                    <div class="fairness-badge"><i class="fa-solid fa-clock"></i><?= $remainingReservations ?> of 3 remaining</div>
                <?php endif; ?>
                <div class="text-right flex-shrink-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Showing</p>
                    <p class="text-xl font-black text-blue-600" id="totalCount">0</p>
                </div>
                <a href="<?= base_url('/reservation') ?>" class="flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition flex-shrink-0"><i class="fa-solid fa-plus"></i> New</a>
            </div>
        </header>
        <?php if (session()->getFlashdata('success')): ?><div class="mb-6 px-6 py-4 bg-blue-50 border border-blue-200 text-blue-700 font-bold rounded-2xl flex items-center gap-3"><i class="fa-solid fa-circle-check text-blue-500"></i><?= session()->getFlashdata('success') ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?><div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3"><i class="fa-solid fa-circle-exclamation text-red-500"></i><?= session()->getFlashdata('error') ?></div><?php endif; ?>
        <?php if ($unclaimedCount > 0): ?>
            <div class="mb-6 px-5 py-4 bg-orange-50 border border-orange-200 rounded-2xl flex items-start gap-3">
                <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-ticket text-orange-500 text-sm"></i></div>
                <div>
                    <p class="font-bold text-orange-700 text-sm">You have <?= $unclaimedCount ?> unclaimed reservation<?= $unclaimedCount > 1 ? 's' : '' ?></p>
                    <p class="text-xs text-orange-500 mt-0.5">Repeated no-shows may affect your future booking priority.</p>
                </div>
            </div>
        <?php endif; ?>
        <div class="content-card mb-4">
            <div class="p-4 lg:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" class="pl-10" placeholder="Search by resource, date, purpose…" oninput="filterTable()">
                </div>
                <select id="statusFilter" class="sm:w-44 flex-shrink-0" onchange="filterTable()">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="claimed">Claimed</option>
                    <option value="declined">Declined</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="expired">Expired</option>
                    <option value="unclaimed">Unclaimed (No-show)</option>
                </select>
            </div>
        </div>
        <div class="px-1 mb-3">
            <p id="resultHint" class="text-xs font-bold text-slate-400"></p>
        </div>
        <!-- DESKTOP TABLE -->
        <div id="desktopTableWrap" class="hidden md:block content-card mb-4">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:52px">ID</th>
                            <th>Resource</th>
                            <th>Schedule</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th style="width:80px" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                        <?php if (empty($processed)): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state"><i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                                        <p class="font-bold text-slate-500">No reservations yet.</p><a href="<?= base_url('/reservation') ?>" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition"><i class="fa-solid fa-plus"></i> Make one now</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($processed as $res):
                                $status = $res['_status'];
                                $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                $pcNumber = htmlspecialchars($res['pc_number'] ?? '—');
                                $purpose = htmlspecialchars($res['purpose'] ?: '—');
                                $formattedDate = (new DateTime($res['reservation_date']))->format('M j, Y');
                                $startTime = date('g:i A', strtotime($res['start_time']));
                                $endTime = date('g:i A', strtotime($res['end_time']));
                                $searchText = strtolower("$resource $formattedDate $purpose");
                            ?>
                                <tr class="reservation-row" data-status="<?= $status ?>" data-id="<?= $res['id'] ?>" data-search="<?= htmlspecialchars($searchText, ENT_QUOTES) ?>">
                                    <td><span class="text-xs font-black text-slate-400 font-mono">#<?= $res['id'] ?></span></td>
                                    <td>
                                        <div class="font-bold text-slate-800 text-sm"><?= $resource ?></div><?php if ($pcNumber && $pcNumber !== '—'): ?><div class="flex items-center gap-1 mt-0.5"><i class="fa-solid fa-desktop text-[9px] text-slate-400"></i><span class="text-[11px] text-slate-500 font-semibold"><?= $pcNumber ?></span></div><?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-sm font-bold text-slate-700"><?= $formattedDate ?></div>
                                        <div class="text-[11px] text-slate-400 font-semibold mt-0.5"><?= $startTime ?> – <?= $endTime ?></div>
                                    </td>
                                    <td><span class="text-sm text-slate-500 font-medium"><?= $purpose ?></span></td>
                                    <td><span class="status-badge status-<?= $status ?>"><i class="fa-solid <?= $statusIcons[$status] ?? 'fa-circle' ?> text-[9px]"></i><?= $status === 'unclaimed' ? 'No-show' : ucfirst($status) ?></span></td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-1.5"><button onclick="viewDetails(<?= $res['id'] ?>)" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"><i class="fa-solid fa-eye text-xs"></i></button><?php if ($status === 'pending'): ?><button onclick="handleCancel(<?= $res['id'] ?>)" class="w-8 h-8 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition"><i class="fa-solid fa-xmark text-xs"></i></button><?php endif; ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="noResults" class="hidden empty-state"><i class="fa-solid fa-filter-circle-xmark text-3xl mb-2 block"></i>
                <p class="font-bold">No reservations match your search.</p>
            </div>
        </div>
        <!-- MOBILE CARDS -->
        <div id="mobileCardList" class="md:hidden space-y-3">
            <?php if (empty($processed)): ?>
                <div class="card-empty"><i class="fa-solid fa-calendar-xmark text-4xl text-slate-200 mb-3 block"></i>
                    <p class="font-black text-slate-400">No reservations yet.</p><a href="<?= base_url('/reservation') ?>" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition"><i class="fa-solid fa-plus"></i> Make one now</a>
                </div>
            <?php else: ?>
                <?php foreach ($processed as $res):
                    $status = $res['_status'];
                    $resource = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                    $pcNumber = htmlspecialchars($res['pc_number'] ?? '');
                    $purpose = htmlspecialchars($res['purpose'] ?: '—');
                    $formattedDate = (new DateTime($res['reservation_date']))->format('M j, Y');
                    $startTime = date('g:i A', strtotime($res['start_time']));
                    $endTime = date('g:i A', strtotime($res['end_time']));
                    $searchText = strtolower("$resource $formattedDate $purpose");
                    $avatarBg = ['pending' => 'bg-amber-100 text-amber-700', 'approved' => 'bg-emerald-100 text-emerald-700', 'claimed' => 'bg-purple-100 text-purple-700', 'declined' => 'bg-red-100 text-red-600', 'cancelled' => 'bg-red-100 text-red-600', 'expired' => 'bg-slate-100 text-slate-500', 'unclaimed' => 'bg-orange-100 text-orange-600'][$status] ?? 'bg-slate-100 text-slate-500';
                ?>
                    <div class="res-card" data-id="<?= $res['id'] ?>" data-status="<?= $status ?>" data-search="<?= htmlspecialchars($searchText, ENT_QUOTES) ?>" onclick="viewDetails(<?= $res['id'] ?>)">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-2xl <?= $avatarBg ?> flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-desktop text-sm"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $resource ?></p><?php if ($pcNumber): ?><p class="text-[11px] text-slate-400 truncate"><?= $pcNumber ?></p><?php endif; ?>
                            </div>
                            <span class="status-badge status-<?= $status ?> flex-shrink-0"><i class="fa-solid <?= $statusIcons[$status] ?? 'fa-circle' ?> text-[9px]"></i><?= $status === 'unclaimed' ? 'No-show' : ucfirst($status) ?></span>
                        </div>
                        <div class="flex items-center gap-1.5 mb-1"><i class="fa-regular fa-calendar text-[10px] text-slate-400 flex-shrink-0"></i>
                            <p class="text-xs text-slate-500 font-semibold"><?= $formattedDate ?></p><span class="text-[10px] text-blue-600 font-bold"><?= $startTime ?> – <?= $endTime ?></span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= $purpose ?></p>
                        <?php if ($status === 'pending'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick="handleCancel(<?= $res['id'] ?>)" class="flex-1 h-8 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs transition flex items-center justify-center gap-1.5"><i class="fa-solid fa-xmark text-[10px]"></i> Cancel</button>
                                <button onclick="viewDetails(<?= $res['id'] ?>)" class="flex-1 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition flex items-center justify-center gap-1.5"><i class="fa-solid fa-eye text-[10px]"></i> View</button>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
                                <p class="text-[10px] text-slate-300 font-semibold">#<?= $res['id'] ?></p>
                                <p class="text-[10px] text-slate-300 font-semibold flex items-center gap-1"><i class="fa-solid fa-chevron-right text-[9px]"></i> Tap to view</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div id="mobileEmpty" class="md:hidden card-empty" style="display:none"><i class="fa-solid fa-filter-circle-xmark text-4xl text-slate-200 mb-3 block"></i>
            <p class="font-black text-slate-400">No reservations match your search.</p>
        </div>
    </main>
    <script>
        const reservationsData = <?= json_encode($processed ?? []) ?>;
        const allTableRows = Array.from(document.querySelectorAll('#reservationTableBody .reservation-row'));
        const allCards = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
        let cancelTargetId = null;

        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase(),
                sf = document.getElementById('statusFilter').value;
            let n = 0;
            const matches = el => {
                const mQ = !q || (el.dataset.search || el.textContent).toLowerCase().includes(q);
                const mS = !sf || el.dataset.status === sf;
                return mQ && mS;
            };
            allTableRows.forEach(row => {
                const show = matches(row);
                row.style.display = show ? '' : 'none';
                if (show) n++;
            });
            let cardVisible = 0;
            allCards.forEach(card => {
                const show = matches(card);
                card.style.display = show ? '' : 'none';
                if (show) cardVisible++;
            });
            document.getElementById('totalCount').textContent = n;
            document.getElementById('noResults').classList.toggle('hidden', n > 0);
            const me = document.getElementById('mobileEmpty');
            if (allCards.length > 0) me.style.display = cardVisible === 0 ? 'block' : 'none';
            const total = allTableRows.length || allCards.length;
            document.getElementById('resultHint').textContent = `Showing ${n} of ${total} reservation${total!==1?'s':''}`;
        }
        filterTable();

        function viewDetails(id) {
            const res = reservationsData.find(r => r.id == id);
            if (!res) return;
            const status = res._status || 'pending',
                isClaimed = res.claimed == 1,
                isApproved = status === 'approved',
                isPending = status === 'pending',
                isRejected = status === 'declined' || status === 'cancelled',
                isUnclaimed = status === 'unclaimed';
            const resourceName = res.resource_name || ('Resource #' + res.resource_id);
            const code = res.e_ticket_code || null;
            const fmtDate = new Date(res.reservation_date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const fmtStart = new Date('1970-01-01T' + res.start_time).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit'
            });
            const fmtEnd = new Date('1970-01-01T' + res.end_time).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit'
            });
            const badge = document.getElementById('modalStatusBadge');
            badge.textContent = status === 'unclaimed' ? 'No-show' : (status.charAt(0).toUpperCase() + status.slice(1));
            badge.className = `status-badge status-${status}`;
            let html = `<div class="detail-row"><span class="detail-label">Reservation #</span><span class="detail-value">#${res.id}</span></div><div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value">${resourceName}</span></div><div class="detail-row"><span class="detail-label">PC / Station</span><span class="detail-value">${res.pc_number||'—'}</span></div><div class="detail-row"><span class="detail-label">Date</span><span class="detail-value">${fmtDate}</span></div><div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">${fmtStart} – ${fmtEnd}</span></div><div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value">${res.purpose||'—'}</span></div>`;
            document.getElementById('modalBody').innerHTML = html;
            ['pendingNotice', 'rejectedNotice', 'qrSection', 'claimedNotice', 'expiredNotice', 'unclaimedNotice'].forEach(i => document.getElementById(i).classList.add('hidden'));
            if (isPending) {
                document.getElementById('pendingNotice').classList.remove('hidden');
            } else if (isRejected) {
                document.getElementById('rejectedNotice').classList.remove('hidden');
                document.getElementById('rejectedText').textContent = status === 'declined' ? 'This reservation was declined by an SK officer.' : 'This reservation was cancelled.';
            } else if (isApproved && code) {
                document.getElementById('qrSection').classList.remove('hidden');
                document.getElementById('qrCodeText').textContent = code;
                QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
                    width: 180,
                    margin: 1,
                    color: {
                        dark: '#1e293b',
                        light: '#ffffff'
                    }
                });
            } else if (isApproved && !code) {
                document.getElementById('pendingNotice').classList.remove('hidden');
            } else if (status === 'expired') {
                document.getElementById('expiredNotice').classList.remove('hidden');
            } else if (status === 'claimed') {
                document.getElementById('claimedNotice').classList.remove('hidden');
                if (res.claimed_at) {
                    const fc = new Date(res.claimed_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    document.getElementById('claimedAtText').textContent = 'Used on ' + fc;
                }
            } else if (isUnclaimed) {
                document.getElementById('unclaimedNotice').classList.remove('hidden');
            }
            openModal('detailsModal');
        }

        function downloadTicket() {
            const canvas = document.getElementById('qrCanvas'),
                code = document.getElementById('qrCodeText').textContent,
                link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        function handleCancel(id) {
            cancelTargetId = id;
            const res = reservationsData.find(r => r.id == id);
            document.getElementById('cancelConfirmResource').textContent = res ? `"${res.resource_name||'Resource'}"` : '';
            document.getElementById('cancelForm').action = '<?= base_url("reservation/cancel") ?>/' + id;
            openModal('cancelModal');
        }
        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Canceling…';
            document.getElementById('cancelId').value = cancelTargetId;
            document.getElementById('cancelForm').submit();
        });

        function openModal(id) {
            document.getElementById(id).classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
            document.body.style.overflow = '';
            if (id === 'cancelModal') {
                const btn = document.getElementById('confirmCancelBtn');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Yes, Cancel';
            }
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('detailsModal');
                closeModal('cancelModal');
            }
        });
    </script>
</body>

</html>