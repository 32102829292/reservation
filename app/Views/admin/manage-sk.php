<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Manage SK Accounts | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar-card { background: white; border-radius: 32px; border: 1px solid #e2e8f0; height: calc(100vh - 48px); position: sticky; top: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; overflow: hidden; width: 100%; }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav    { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item   { transition: all 0.18s; }
        .sidebar-item.active { background: #2563eb; color: white !important; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.35); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 92%; max-width: 600px; background: rgba(15,23,42,0.97); backdrop-filter: blur(12px); border-radius: 24px; padding: 6px; z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3); }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.28rem 0.65rem; border-radius: 10px; font-size: 0.66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        /* ── Stat cards ── */
        .stat-card { background: white; border-radius: 20px; padding: 1rem 1.15rem; border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }
        .stat-card.ring-active { box-shadow: 0 0 0 2px #2563eb; }

        /* ── Quick tabs ── */
        .qtab { display: inline-flex; align-items: center; gap: 6px; padding: 0.45rem 0.9rem; border-radius: 12px; font-size: 0.78rem; font-weight: 700; cursor: pointer; border: 1.5px solid #e2e8f0; color: #64748b; background: white; transition: all 0.18s; white-space: nowrap; font-family: inherit; }
        .qtab:hover  { border-color: #2563eb; color: #2563eb; }
        .qtab.active { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 4px 12px -2px rgba(37,99,235,0.3); }

        /* ── Desktop table ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-wrap::-webkit-scrollbar { height: 4px; }
        .table-wrap::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        table  { width: 100%; border-collapse: collapse; min-width: 640px; }
        thead th { background: #f8fafc; font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8; padding: 0.85rem 0.9rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        td { padding: 0.8rem 0.9rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; }
        tbody tr:hover td { background: #eff6ff; }

        /* ── Field ── */
        .field { background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 0.7rem 1rem 0.7rem 2.5rem; font-size: 0.875rem; font-family: inherit; color: #1e293b; transition: all 0.2s; width: 100%; }
        .field:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* ── Modals — bottom-sheet on mobile, centered on sm+ ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: flex-end; justify-content: center; padding: 0; }
        @media (min-width: 640px) { .overlay { align-items: center; padding: 12px; } }
        .overlay.open { display: flex; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); }
        .modal-box {
            position: relative; background: white; width: 100%; max-width: 500px;
            border-radius: 28px 28px 0 0; max-height: 92dvh; overflow-y: auto;
            box-shadow: 0 -8px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.28s cubic-bezier(0.34,1.3,0.64,1) both;
        }
        @media (min-width: 640px) {
            .modal-box { border-radius: 28px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); animation-name: popIn; }
            .modal-box.sm { max-width: 380px; }
        }
        @keyframes slideUp { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:none; } }
        @keyframes popIn   { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* ── Detail rows ── */
        .drow  { display: flex; align-items: flex-start; gap: 12px; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; }
        .drow:last-child { border-bottom: none; }
        .dicon { width: 34px; height: 34px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.82rem; flex-shrink: 0; }
        .dlabel { font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 2px; }
        .dvalue { font-size: 0.88rem; font-weight: 700; color: #1e293b; break-all: break-all; }

        /* ── Buttons ── */
        .btn-ghost    { background: #f1f5f9; color: #475569; border: none; border-radius: 10px; padding: 0.45rem 0.8rem; font-size: 0.75rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: inline-flex; align-items: center; gap: 4px; font-family: inherit; }
        .btn-ghost:hover { background: #e2e8f0; }
        .btn-approve  { background: #dcfce7; color: #166534; border: 1.5px solid #86efac; border-radius: 10px; padding: 0.45rem 0.8rem; font-size: 0.75rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: inline-flex; align-items: center; gap: 4px; font-family: inherit; }
        .btn-approve:hover { background: #bbf7d0; }
        .btn-reject   { background: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5; border-radius: 10px; padding: 0.45rem 0.65rem; font-size: 0.75rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: inline-flex; align-items: center; gap: 4px; font-family: inherit; }
        .btn-reject:hover { background: #fecaca; }
        .btn-cancel-lg { background: #f1f5f9; color: #475569; border: none; border-radius: 14px; padding: 0.8rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; font-family: inherit; flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px; }
        .btn-cancel-lg:hover { background: #e2e8f0; }
        .btn-confirm-approve { background: #16a34a; color: white; border: none; border-radius: 14px; padding: 0.8rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; font-family: inherit; flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px; }
        .btn-confirm-approve:hover { background: #15803d; }
        .btn-confirm-reject  { background: #ef4444; color: white; border: none; border-radius: 14px; padding: 0.8rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; font-family: inherit; flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px; }
        .btn-confirm-reject:hover  { background: #dc2626; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }

        .sk-avatar { width: 36px; height: 36px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.88rem; flex-shrink: 0; }

        /* ── Mobile SK card ── */
        .sk-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1rem 1.1rem; transition: box-shadow 0.15s, border-color 0.15s; cursor: pointer; }
        .sk-card:hover { box-shadow: 0 6px 20px -4px rgba(0,0,0,0.08); border-color: #bfdbfe; }
        .sk-card:active { background: #f0f7ff; }
    </style>
</head>
<body class="flex min-h-screen">

    <?php
    $page = $page ?? 'manage-sk';
    $navItems = [
        ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
        ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
        ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
        ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
        ['url' => '/admin/books',               'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
        ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
        ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
        ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
        ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];

    $pCount = count($pending  ?? []);
    $aCount = count($approved ?? []);
    $rCount = count($rejected ?? []);
    $total  = $pCount + $aCount + $rCount;

    $avatarPalette = [
        ['bg-blue-100','text-blue-700'],
        ['bg-purple-100','text-purple-700'],
        ['bg-emerald-100','text-emerald-700'],
        ['bg-rose-100','text-rose-700'],
        ['bg-amber-100','text-amber-700'],
    ];

    $allMerged = array_merge(
        array_map(fn($s) => array_merge($s, ['_status' => 'pending']),  $pending  ?? []),
        array_map(fn($s) => array_merge($s, ['_status' => 'approved']), $approved ?? []),
        array_map(fn($s) => array_merge($s, ['_status' => 'rejected']), $rejected ?? [])
    );

    $sIcon = ['pending'=>'fa-clock','approved'=>'fa-check','rejected'=>'fa-xmark'];
    ?>

    <!-- Hidden forms -->
    <form id="approveForm" method="POST" action="/admin/approve-sk" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
    <form id="rejectForm"  method="POST" action="/admin/reject-sk"  style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="rejectId"></form>

    <!-- ══ DETAIL MODAL ══ -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('detail')"></div>
        <div class="modal-box">
            <div class="flex justify-center pt-3 sm:hidden"><div class="w-10 h-1 bg-slate-200 rounded-full"></div></div>
            <div class="flex items-start justify-between px-5 pt-4 sm:pt-6 pb-3">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">SK Account</p>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900">Account Info</h3>
                </div>
                <button onclick="closeModal('detail')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="dHero" class="mx-5 mb-4 bg-gradient-to-br from-blue-50 to-slate-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-4"></div>
            <div id="dStatusBar" class="mx-5 mb-4 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>
            <div class="px-5 pb-2">
                <div class="drow"><div class="dicon"><i class="fa-solid fa-envelope"></i></div><div><p class="dlabel">Email</p><p id="dEmail" class="dvalue break-all"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-phone"></i></div><div><p class="dlabel">Phone</p><p id="dPhone" class="dvalue"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-regular fa-calendar"></i></div><div><p class="dlabel">Applied</p><p id="dDate" class="dvalue"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-shield-check"></i></div><div><p class="dlabel">Email Verified</p><p id="dVerified" class="dvalue"></p></div></div>
            </div>
            <div id="dActions" class="px-5 py-5 border-t border-slate-100 flex gap-3 mt-2"></div>
        </div>
    </div>

    <!-- Approve confirm -->
    <div id="approveModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('approve')"></div>
        <div class="modal-box sm">
            <div class="flex justify-center pt-3 sm:hidden"><div class="w-10 h-1 bg-slate-200 rounded-full"></div></div>
            <div class="px-6 pt-5 pb-5 text-center">
                <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-user-check"></i></div>
                <h3 class="text-xl font-black text-slate-900">Approve SK Account?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This will grant SK portal access.</p>
                <p id="approveConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-6 pb-7 flex gap-3">
                <button class="btn-cancel-lg" onclick="closeModal('approve')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmApproveBtn" class="btn-confirm-approve" onclick="submitApprove()"><i class="fa-solid fa-check"></i> Approve</button>
            </div>
        </div>
    </div>

    <!-- Reject confirm -->
    <div id="rejectModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('reject')"></div>
        <div class="modal-box sm">
            <div class="flex justify-center pt-3 sm:hidden"><div class="w-10 h-1 bg-slate-200 rounded-full"></div></div>
            <div class="px-6 pt-5 pb-5 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-user-xmark"></i></div>
                <h3 class="text-xl font-black text-slate-900">Reject SK Account?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p id="rejectConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-6 pb-7 flex gap-3">
                <button class="btn-cancel-lg" onclick="closeModal('reject')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmRejectBtn" class="btn-confirm-reject" onclick="submitReject()"><i class="fa-solid fa-xmark"></i> Reject</button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="hidden lg:block w-80 flex-shrink-0 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
                ?>
                    <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] === 'manage-sk' && $pCount > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pCount ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all"><i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout</a>
            </div>
        </div>
    </aside>

    <!-- Mobile Nav -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $btnClass = ($page == $item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
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

    <!-- Main -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-10 pb-32">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 fade-up">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Admin Portal</p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">SK Accounts</h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5">Manage Sangguniang Kabataan registrations</p>
            </div>
            <?php if ($pCount > 0): ?>
                <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-2.5 rounded-2xl font-bold text-sm flex-shrink-0">
                    <i class="fa-solid fa-clock text-xs"></i>
                    <?= $pCount ?> pending approval<?= $pCount > 1 ? 's' : '' ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Stat cards: 2 → 4 (sm) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            <?php foreach ([
                ['Total',    $total,  'border-blue-400',    'text-slate-700',   'fa-users',      'all'],
                ['Pending',  $pCount, 'border-amber-400',   'text-amber-600',   'fa-clock',      'pending'],
                ['Approved', $aCount, 'border-emerald-400', 'text-emerald-600', 'fa-user-check', 'approved'],
                ['Rejected', $rCount, 'border-rose-400',    'text-rose-600',    'fa-user-xmark', 'rejected'],
            ] as [$lbl, $val, $border, $color, $ico, $tab]): ?>
                <div class="stat-card <?= $border ?>" onclick="switchToTab('<?= $tab ?>')">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $lbl ?></p>
                        <i class="fa-solid <?= $ico ?> text-sm <?= $color ?>"></i>
                    </div>
                    <p class="text-2xl font-black <?= $color ?>"><?= $val ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Search + tabs -->
        <div class="bg-white border border-slate-200 rounded-[24px] p-4 mb-4 shadow-sm">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input id="searchInput" type="text" placeholder="Search by name or email…" class="field" oninput="applyFilter()">
            </div>
            <div class="flex gap-2 mt-3 overflow-x-auto pb-1 -mx-1 px-1" style="-webkit-overflow-scrolling:touch;">
                <button class="qtab active" data-tab="all"      onclick="switchToTab('all')"><i class="fa-solid fa-users text-xs"></i> All <span class="text-[9px] font-black opacity-60"><?= $total ?></span></button>
                <button class="qtab" data-tab="pending"  onclick="switchToTab('pending')"><i class="fa-solid fa-clock text-xs"></i> Pending<?php if ($pCount > 0): ?><span class="bg-amber-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $pCount ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="approved" onclick="switchToTab('approved')"><i class="fa-solid fa-user-check text-xs"></i> Approved</button>
                <button class="qtab" data-tab="rejected" onclick="switchToTab('rejected')"><i class="fa-solid fa-user-xmark text-xs"></i> Rejected</button>
            </div>
        </div>

        <p id="resultCount" class="text-xs font-bold text-slate-400 px-1 mb-4"></p>

        <!-- ══ DESKTOP TABLE (md+) ══ -->
        <div class="hidden md:block bg-white border border-slate-200 rounded-[28px] shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:48px">ID</th>
                            <th>Account</th>
                            <th>Email</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th class="text-right" style="width:190px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="skTableBody">
                        <?php if (empty($allMerged)): ?>
                            <tr><td colspan="6">
                                <div class="py-20 text-center">
                                    <i class="fa-solid fa-users text-5xl text-slate-200 mb-4 block"></i>
                                    <p class="font-black text-slate-400 text-lg">No SK accounts yet</p>
                                    <p class="text-slate-300 text-sm mt-1">Accounts will appear when users register.</p>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($allMerged as $idx => $sk):
                                $s    = $sk['_status'];
                                $name = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                                $email= htmlspecialchars($sk['email'] ?? '—');
                                $phone= htmlspecialchars($sk['phone'] ?? 'N/A');
                                $date = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                                $ver  = !empty($sk['is_verified']) ? 'Yes' : 'No';
                                $col  = $avatarPalette[$idx % count($avatarPalette)];
                                $init = strtoupper(substr($name, 0, 1));
                                $colStr = $col[0] . ' ' . $col[1];
                                $mdata  = json_encode(['id'=>$sk['id'],'status'=>$s,'name'=>$name,'email'=>$email,'phone'=>$phone,'date'=>$date,'verified'=>$ver,'color'=>$colStr,'initials'=>$init]);
                            ?>
                            <tr class="sk-row" data-status="<?= $s ?>" data-search="<?= strtolower("$name $email") ?>">
                                <td><span class="text-xs font-black text-slate-400 font-mono">#<?= $sk['id'] ?></span></td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="sk-avatar <?= $colStr ?>"><?= $init ?></div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-800 leading-tight"><?= $name ?></p>
                                            <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Applied <?= $date ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td><p class="text-sm text-slate-500 font-semibold truncate max-w-[180px]"><?= $email ?></p></td>
                                <td><p class="text-sm text-slate-500 font-semibold whitespace-nowrap"><?= $date ?></p></td>
                                <td>
                                    <span class="badge badge-<?= $s ?>">
                                        <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?> text-[9px]"></i> <?= ucfirst($s) ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <button onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-ghost"><i class="fa-solid fa-eye text-[11px]"></i> View</button>
                                        <?php if ($s === 'pending'): ?>
                                            <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve"><i class="fa-solid fa-check text-[11px]"></i> Approve</button>
                                            <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"  class="btn-reject"><i class="fa-solid fa-xmark text-[11px]"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <p id="tableFooter" class="text-xs font-bold text-slate-400"></p>
                <p class="text-xs text-slate-300 font-semibold hidden sm:block">Click View to see full account details</p>
            </div>
        </div>

        <!-- ══ MOBILE CARDS (< md) ══ -->
        <div class="md:hidden space-y-3" id="mobileCardList">
            <?php if (empty($allMerged)): ?>
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm py-16 text-center">
                    <i class="fa-solid fa-users text-5xl text-slate-200 mb-4 block"></i>
                    <p class="font-black text-slate-400 text-lg">No SK accounts yet</p>
                </div>
            <?php else: ?>
                <?php foreach ($allMerged as $idx => $sk):
                    $s    = $sk['_status'];
                    $name = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                    $email= htmlspecialchars($sk['email'] ?? '—');
                    $phone= htmlspecialchars($sk['phone'] ?? 'N/A');
                    $date = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                    $ver  = !empty($sk['is_verified']) ? 'Yes' : 'No';
                    $col  = $avatarPalette[$idx % count($avatarPalette)];
                    $init = strtoupper(substr($name, 0, 1));
                    $colStr = $col[0] . ' ' . $col[1];
                    $mdata  = json_encode(['id'=>$sk['id'],'status'=>$s,'name'=>$name,'email'=>$email,'phone'=>$phone,'date'=>$date,'verified'=>$ver,'color'=>$colStr,'initials'=>$init]);
                ?>
                    <div class="sk-card mobile-sk-card"
                         data-status="<?= $s ?>"
                         data-search="<?= strtolower("$name $email") ?>"
                         onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                        <!-- Top: avatar + name + badge -->
                        <div class="flex items-center gap-3 mb-2">
                            <div class="sk-avatar <?= $colStr ?>"><?= $init ?></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 leading-tight truncate"><?= $name ?></p>
                                <p class="text-[11px] text-slate-400 truncate"><?= $email ?></p>
                            </div>
                            <span class="badge badge-<?= $s ?> flex-shrink-0">
                                <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?> text-[9px]"></i> <?= ucfirst($s) ?>
                            </span>
                        </div>

                        <!-- Applied date -->
                        <p class="text-xs text-slate-400 font-semibold mb-3">
                            <i class="fa-regular fa-calendar text-[10px] mr-1"></i>Applied <?= $date ?>
                        </p>

                        <!-- Pending actions -->
                        <?php if ($s === 'pending'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                    class="flex-1 h-9 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-check text-[11px]"></i> Approve
                                </button>
                                <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                    class="flex-1 h-9 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-xmark text-[11px]"></i> Reject
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="pt-2 border-t border-slate-50">
                                <p class="text-[10px] font-black text-slate-300 font-mono">#<?= $sk['id'] ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="mobileNoResults" class="hidden md:hidden bg-white rounded-[24px] border border-slate-200 shadow-sm px-6 py-10 text-center">
            <i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-200 mb-3 block"></i>
            <p class="font-bold text-slate-400">No accounts match your search.</p>
        </div>

    </main>

    <script>
    let curTab = 'all';
    let approveTargetId = null, rejectTargetId = null;

    const allTableRows   = Array.from(document.querySelectorAll('.sk-row'));
    const allMobileCards = Array.from(document.querySelectorAll('.mobile-sk-card'));

    /* ── Tabs ── */
    function switchToTab(tab) {
        curTab = tab;
        document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
        document.querySelectorAll('.stat-card[onclick]').forEach(c => {
            c.classList.toggle('ring-active', (c.getAttribute('onclick') || '').includes(`'${tab}'`));
        });
        applyFilter();
    }

    function applyFilter() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        const match = el => {
            const mt = curTab === 'all' || el.dataset.status === curTab;
            const ms = !q || el.dataset.search.includes(q);
            return mt && ms;
        };
        let n = 0;
        allTableRows.forEach(r => { const s = match(r); r.style.display = s ? '' : 'none'; if (s) n++; });
        let m = 0;
        allMobileCards.forEach(c => { const s = match(c); c.style.display = s ? '' : 'none'; if (s) m++; });
        const total = allTableRows.length;
        document.getElementById('resultCount').textContent = `Showing ${n || m} of ${total} account${total !== 1 ? 's' : ''}`;
        const tf = document.getElementById('tableFooter'); if (tf) tf.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
        const mnr = document.getElementById('mobileNoResults'); if (mnr) mnr.classList.toggle('hidden', m > 0 || allMobileCards.length === 0);
    }

    /* ── Detail modal ── */
    const STATUS_META = {
        pending:  { icon:'fa-clock',      bg:'#fef3c7', color:'#92400e', label:'Pending — Awaiting review' },
        approved: { icon:'fa-user-check', bg:'#dcfce7', color:'#166534', label:'Approved — Portal access granted' },
        rejected: { icon:'fa-user-xmark', bg:'#fee2e2', color:'#991b1b', label:'Rejected' },
    };

    function openDetail(d) {
        const m = STATUS_META[d.status] || STATUS_META.pending;
        document.getElementById('dHero').innerHTML = `
            <div class="w-14 h-14 rounded-2xl ${d.color} flex items-center justify-center text-2xl font-black flex-shrink-0">${d.initials}</div>
            <div class="min-w-0">
                <p class="font-black text-slate-900 text-lg leading-tight truncate">${d.name}</p>
                <p class="text-xs text-slate-400 font-semibold mt-0.5 truncate">${d.email}</p>
            </div>`;
        const bar = document.getElementById('dStatusBar');
        bar.style.background = m.bg; bar.style.color = m.color;
        bar.innerHTML = `<i class="fa-solid ${m.icon}"></i> <span>${m.label}</span>`;
        document.getElementById('dEmail').textContent    = d.email;
        document.getElementById('dPhone').textContent    = d.phone;
        document.getElementById('dDate').textContent     = d.date;
        document.getElementById('dVerified').textContent = d.verified === 'Yes' ? '✓ Verified' : '✗ Not verified';
        const acts = document.getElementById('dActions');
        if (d.status === 'pending') {
            acts.innerHTML = `
                <button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}'); closeModal('detail');" class="btn-confirm-approve flex-1"><i class="fa-solid fa-check"></i> Approve</button>
                <button onclick="triggerReject(${d.id},'${d.name.replace(/'/g,"\\'")}');  closeModal('detail');" class="btn-confirm-reject flex-1"><i class="fa-solid fa-xmark"></i> Reject</button>`;
        } else {
            acts.innerHTML = `<button onclick="closeModal('detail')" class="btn-cancel-lg w-full"><i class="fa-solid fa-xmark text-xs"></i> Close</button>`;
        }
        document.getElementById('detailModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    /* ── Approve / Reject ── */
    function triggerApprove(id, name) { approveTargetId = id; document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : ''; openModal('approve'); }
    function triggerReject(id, name)  { rejectTargetId  = id; document.getElementById('rejectConfirmName').textContent  = name ? `"${name}"` : ''; openModal('reject');  }
    function submitApprove() {
        const b = document.getElementById('confirmApproveBtn'); b.disabled = true; b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
        document.getElementById('approveId').value = approveTargetId; document.getElementById('approveForm').submit();
    }
    function submitReject() {
        const b = document.getElementById('confirmRejectBtn'); b.disabled = true; b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Rejecting…';
        document.getElementById('rejectId').value = rejectTargetId; document.getElementById('rejectForm').submit();
    }

    /* ── Modal helpers ── */
    const overlayIds = { detail:'detailModal', approve:'approveModal', reject:'rejectModal' };
    function openModal(key)  { const el = document.getElementById(overlayIds[key]); if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; } }
    function closeModal(key) { const el = document.getElementById(overlayIds[key]); if (el) { el.classList.remove('open'); document.body.style.overflow = ''; } }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal('detail'); closeModal('approve'); closeModal('reject'); } });

    applyFilter();
    </script>
</body>
</html>