<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>User Requests | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <meta name="theme-color" content="#16a34a">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; }

        /* ── Sidebar ── */
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
        .sidebar-item.active { background: #16a34a; color: white !important; box-shadow: 0 8px 20px -4px rgba(22,163,74,0.35); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Table (desktop md+) ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table  { width: 100%; border-collapse: collapse; min-width: 640px; }
        thead th {
            background: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8;
            padding: 0.9rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; cursor: pointer; }
        tbody tr:hover td { background: #f0fdf4; }

        /* ── Mobile cards ── */
        .req-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s;
            position: relative; overflow: hidden;
        }
        .req-card:hover, .req-card:active {
            border-color: #bbf7d0;
            box-shadow: 0 6px 20px -4px rgba(22,163,74,0.15);
            transform: translateY(-1px);
        }
        /* left accent bar */
        .req-card::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0;
            width: 4px; border-radius: 0 4px 4px 0;
        }
        .req-card[data-status="pending"]::before  { background: #fbbf24; }
        .req-card[data-status="approved"]::before { background: #22c55e; }
        .req-card[data-status="declined"]::before,
        .req-card[data-status="canceled"]::before { background: #ef4444; }

        /* ── Status badges ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.75rem; border-radius: 10px; font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-declined,
        .badge-canceled { background: #fee2e2; color: #991b1b; }

        /* ── Inputs ── */
        .field {
            background: white; border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 0.7rem 1rem 0.7rem 2.5rem; font-size: 0.875rem;
            font-family: inherit; color: #1e293b; transition: all 0.2s; width: 100%;
        }
        .field:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }

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

        /* ── Overlays ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; }
        .modal-backdrop { position: absolute; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(6px); }

        /* ── Modal box ── */
        .modal-box {
            position: relative; margin: auto; background: white; border-radius: 32px;
            width: 94%; max-width: 520px; max-height: 92vh; overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);
            animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .modal-box.sm { max-width: 380px; }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* drag handle — only shown on mobile */
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 0; }

        /* Bottom-sheet on mobile */
        @media (max-width: 639px) {
            .overlay#detailModal,
            .overlay#approveModal,
            .overlay#declineModal { align-items: flex-end; }
            .overlay#detailModal .modal-box,
            .overlay#approveModal .modal-box,
            .overlay#declineModal .modal-box {
                margin: 0;
                width: 100%;
                max-width: 100%;
                border-radius: 28px 28px 0 0;
                max-height: 92vh;
                animation: slideUp 0.28s cubic-bezier(0.34,1.2,0.64,1) both;
            }
            .sheet-handle { display: block; }
        }

        @keyframes popIn   { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes slideUp { from { opacity:0; transform:translateY(60px); }            to { opacity:1; transform:none; } }

        /* ── Detail rows ── */
        .drow  { display: flex; align-items: flex-start; gap: 12px; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
        .drow:last-child { border-bottom: none; }
        .dicon { width: 36px; height: 36px; border-radius: 12px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
        .dlabel { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 3px; }
        .dvalue { font-size: 0.9rem; font-weight: 700; color: #1e293b; }

        /* ── Action buttons ── */
        .btn-approve {
            background: #dcfce7; color: #166534; border: none; border-radius: 14px;
            padding: 0.75rem 1.25rem; font-size: 0.85rem; font-weight: 800; cursor: pointer;
            transition: all 0.18s; display: inline-flex; align-items: center; gap: 7px;
            font-family: inherit; flex: 1; justify-content: center;
        }
        .btn-approve:hover { background: #16a34a; color: white; box-shadow: 0 6px 14px -3px rgba(22,163,74,0.4); }
        .btn-decline {
            background: #fee2e2; color: #991b1b; border: none; border-radius: 14px;
            padding: 0.75rem 1.25rem; font-size: 0.85rem; font-weight: 800; cursor: pointer;
            transition: all 0.18s; display: inline-flex; align-items: center; gap: 7px;
            font-family: inherit; flex: 1; justify-content: center;
        }
        .btn-decline:hover { background: #ef4444; color: white; }
        .btn-close {
            background: #f1f5f9; color: #475569; border: none; border-radius: 14px;
            padding: 0.75rem 1.25rem; font-size: 0.85rem; font-weight: 800; cursor: pointer;
            transition: all 0.18s; display: inline-flex; align-items: center; gap: 7px;
            font-family: inherit; flex: 1; justify-content: center;
        }
        .btn-close:hover { background: #e2e8f0; }
        /* confirm modal buttons */
        .btn-confirm-approve {
            background: #16a34a; color: white; border: none; border-radius: 14px; padding: 0.85rem;
            font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1;
        }
        .btn-confirm-approve:hover:not(:disabled) { background: #15803d; }
        .btn-confirm-decline {
            background: #ef4444; color: white; border: none; border-radius: 14px; padding: 0.85rem;
            font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1;
        }
        .btn-confirm-decline:hover:not(:disabled) { background: #dc2626; }
        .btn-cancel {
            background: #f1f5f9; color: #475569; border: none; border-radius: 14px; padding: 0.85rem;
            font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1;
        }
        .btn-cancel:hover { background: #e2e8f0; }

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
            min-width: 1.1rem; text-align: center; animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }

        /* ── Notification dropdown ── */
        .notif-dropdown {
            position: fixed; top: 76px; right: 24px; width: 360px;
            background: white; border-radius: 24px;
            box-shadow: 0 20px 40px -8px rgba(0,0,0,0.2);
            border: 1px solid #e2e8f0; z-index: 300; display: none;
        }
        .notif-dropdown.open { display: block; animation: slideDown 0.22s ease; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:none; } }
        .notif-list { max-height: 380px; overflow-y: auto; }
        .notif-list::-webkit-scrollbar { width: 4px; }
        .notif-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .notif-item { padding: 0.9rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0fdf4; border-left: 3px solid #16a34a; }
        .notif-item.unread:hover { background: #dcfce7; }
        .notif-item:last-child { border-bottom: none; }

        /* ── Toast ── */
        .toast-wrap { position: fixed; top: 80px; right: 24px; z-index: 400; display: flex; flex-direction: column; align-items: flex-end; gap: 10px; pointer-events: none; width: 360px; }
        .toast {
            background: white; border-radius: 18px; padding: 1rem 1.1rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
            border-left: 4px solid #f59e0b; pointer-events: auto; cursor: pointer; width: 100%;
            animation: slideInRight 0.3s ease;
        }
        @keyframes slideInRight { from { transform:translateX(110%); opacity:0; } to { transform:none; opacity:1; } }
        .toast:hover { transform: translateX(-4px); }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }

        /* ── Empty states ── */
        .empty-state { padding: 5rem 2rem; text-align: center; }
        .card-empty { padding: 3rem 1.5rem; text-align: center; background: white; border-radius: 20px; border: 1px dashed #e2e8f0; }

        @media (max-width: 640px) {
            .notif-dropdown { right: 10px; left: 10px; width: auto; top: 70px; }
            .toast-wrap { right: 10px; left: 10px; width: auto; }
        }
    </style>
</head>
<body class="flex min-h-screen">

    <?php
    $page    = $page ?? 'user-requests';
    $sk_name = session()->get('name') ?? session()->get('username') ?? 'SK Officer';
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
    $pending  = array_filter($userReservations ?? [], fn($r) => ($r['status'] ?? '') === 'pending');
    $approved = array_filter($userReservations ?? [], fn($r) => ($r['status'] ?? '') === 'approved');
    $declined = array_filter($userReservations ?? [], fn($r) => in_array($r['status'] ?? '', ['declined','canceled']));
    $pCount   = count($pending);
    $aCount   = count($approved);
    $dCount   = count($declined);
    $total    = count($userReservations ?? []);

    $statusIcons = [
        'pending'  => 'fa-clock',
        'approved' => 'fa-circle-check',
        'declined' => 'fa-xmark',
        'canceled' => 'fa-ban',
    ];
    ?>

    <!-- Hidden forms -->
    <form id="approveForm" method="POST" action="<?= base_url('sk/approve') ?>" style="display:none">
        <?= csrf_field() ?><input type="hidden" name="id" id="approveId">
    </form>
    <form id="declineForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none">
        <?= csrf_field() ?><input type="hidden" name="id" id="declineId">
    </form>

    <!-- Notification dropdown -->
    <div id="notifDropdown" class="notif-dropdown">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <p class="font-black text-slate-800 text-sm">New Requests</p>
            <button onclick="markAllRead()" class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-bold transition">Mark all read</button>
        </div>
        <div id="notifList" class="notif-list"></div>
    </div>

    <!-- Toast container -->
    <div id="toastWrap" class="toast-wrap"></div>

    <!-- ══════════════════════════════════════
         DETAIL MODAL  (bottom-sheet on mobile)
         ══════════════════════════════════════ -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true">
        <div class="modal-backdrop" onclick="closeModal('detail')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div class="flex items-start justify-between px-7 pt-5 pb-3">
                <div>
                    <p id="mId" class="text-xs font-black text-slate-400 font-mono mb-1"></p>
                    <h3 class="text-xl font-black text-slate-900">Request Details</h3>
                </div>
                <button onclick="closeModal('detail')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="mStatusBar" class="mx-7 mb-4 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>

            <div class="px-7 pb-2">
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-user"></i></div>
                    <div><p class="dlabel">Requestor</p><p id="mName" class="dvalue"></p><p id="mEmail" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-desktop"></i></div>
                    <div><p class="dlabel">Resource</p><p id="mResource" class="dvalue"></p><p id="mPc" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-calendar-day"></i></div>
                    <div><p class="dlabel">Schedule</p><p id="mDate" class="dvalue"></p><p id="mTime" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-pen-to-square"></i></div>
                    <div><p class="dlabel">Purpose</p><p id="mPurpose" class="dvalue"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-regular fa-clock"></i></div>
                    <div><p class="dlabel">Submitted</p><p id="mCreated" class="dvalue"></p></div>
                </div>
            </div>

            <div id="mActions" class="px-7 py-5 border-t border-slate-100 flex gap-3 flex-wrap mt-2"></div>
        </div>
    </div>

    <!-- Approve confirm modal -->
    <div id="approveModal" class="overlay">
        <div class="modal-backdrop" onclick="closeModal('approve')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div class="px-7 pt-7 pb-5 text-center">
                <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-circle-check"></i></div>
                <h3 class="text-xl font-black text-slate-900">Approve Request?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This will confirm the reservation.</p>
                <p id="approveConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-7 pb-7 flex gap-3">
                <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmApproveBtn" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
            </div>
        </div>
    </div>

    <!-- Decline confirm modal -->
    <div id="declineModal" class="overlay">
        <div class="modal-backdrop" onclick="closeModal('decline')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div class="px-7 pt-7 pb-5 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <h3 class="text-xl font-black text-slate-900">Decline Request?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p id="declineConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-7 pb-7 flex gap-3">
                <button class="btn-cancel" onclick="closeModal('decline')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmDeclineBtn" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>
            </div>
        </div>
    </div>

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
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                        <?php if ($item['key'] === 'user-requests' && $pCount > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pCount ?></span>
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
                $btnClass = ($page == $item['key']) ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?> relative">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    <?php if ($item['key'] === 'user-requests' && $pCount > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full leading-none"><?= $pCount ?></span>
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
    <main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 fade-up">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">SK Portal</p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">User Requests</h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5">Review and manage resident reservation requests</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="notif-bell" onclick="toggleNotif()" id="bellBtn">
                    <i class="fa-regular fa-bell text-slate-500"></i>
                    <span id="notifBadge" class="notif-dot" style="display:none">0</span>
                </div>
                <a href="<?= base_url('/sk/new-reservation') ?>" class="flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
            </div>
        </header>

        <!-- Stat cards: 2-col on xs, 4-col on sm+ -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <?php foreach ([
                ['Total',    $total,   'border-slate-400',   'text-slate-700'],
                ['Pending',  $pCount,  'border-amber-400',   'text-amber-600'],
                ['Approved', $aCount,  'border-emerald-400', 'text-emerald-600'],
                ['Declined', $dCount,  'border-rose-400',    'text-rose-600'],
            ] as [$lbl, $val, $border, $color]): ?>
                <div class="stat-card <?= $border ?>">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?= $lbl ?></p>
                    <p class="text-2xl font-black <?= $color ?>"><?= $val ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pending alert banner -->
        <?php if ($pCount > 0): ?>
            <div class="mb-5 px-5 py-4 bg-amber-50 border border-amber-200 text-amber-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-solid fa-clock text-amber-500"></i>
                You have <span class="bg-white border border-amber-200 px-2 py-0.5 rounded-full font-black mx-1"><?= $pCount ?></span>
                pending request<?= $pCount != 1 ? 's' : '' ?> waiting for approval.
            </div>
        <?php endif; ?>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-5 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Search + tabs -->
        <div class="bg-white border border-slate-200 rounded-[28px] p-4 lg:p-5 mb-4 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="field" oninput="applyFilters()">
                </div>
                <button onclick="clearFilters()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center gap-2 flex-shrink-0">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </button>
            </div>
            <div class="flex gap-2 mt-3 overflow-x-auto pb-0.5" style="-webkit-overflow-scrolling:touch;">
                <button class="qtab active" data-tab="all"     onclick="setTab(this,'all')">
                    <i class="fa-solid fa-layer-group text-xs"></i> All
                    <span class="text-[9px] font-black opacity-70"><?= $total ?></span>
                </button>
                <button class="qtab" data-tab="pending"        onclick="setTab(this,'pending')">
                    <i class="fa-solid fa-clock text-xs"></i> Pending
                    <?php if ($pCount > 0): ?>
                        <span class="bg-amber-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $pCount ?></span>
                    <?php endif; ?>
                </button>
                <button class="qtab" data-tab="approved"       onclick="setTab(this,'approved')">
                    <i class="fa-solid fa-circle-check text-xs"></i> Approved
                </button>
                <button class="qtab" data-tab="declined"       onclick="setTab(this,'declined')">
                    <i class="fa-solid fa-xmark text-xs"></i> Declined
                </button>
            </div>
        </div>

        <!-- Result count -->
        <div class="px-1 mb-3">
            <p id="resultCount" class="text-xs font-bold text-slate-400"></p>
        </div>

        <!-- ══════════════════════════════════════
             DESKTOP TABLE  (md+)
             ══════════════════════════════════════ -->
        <div id="desktopTableWrap" class="hidden md:block bg-white border border-slate-200 rounded-[28px] shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:52px">ID</th>
                            <th>User</th>
                            <th>Resource</th>
                            <th>Schedule</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th class="text-right" style="width:180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (empty($userReservations)): ?>
                            <tr><td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox text-5xl text-slate-200 mb-4 block"></i>
                                    <p class="font-black text-slate-400 text-lg">No requests yet</p>
                                    <p class="text-slate-300 text-sm mt-1">User reservation requests will appear here.</p>
                                </div>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($userReservations as $res):
                                $s        = strtolower($res['status'] ?? 'pending');
                                $name     = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                                $email    = htmlspecialchars($res['user_email'] ?? '');
                                $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                                $pc       = htmlspecialchars($res['pc_number'] ?? '');
                                $rawDate  = $res['reservation_date'] ?? '';
                                $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                                $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                                $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                                $purpose  = htmlspecialchars($res['purpose'] ?? '—');
                                $created  = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                                $icon     = $statusIcons[$s] ?? 'fa-circle';
                                $mdata    = json_encode([
                                    'id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,
                                    'resource'=>$resource,'pc'=>$pc,'date'=>$date,
                                    'start'=>$start,'end'=>$end,'purpose'=>$purpose,'created'=>$created,
                                ]);
                            ?>
                                <tr class="res-row"
                                    data-id="<?= $res['id'] ?>"
                                    data-status="<?= $s ?>"
                                    data-search="<?= strtolower("$name $resource $purpose $email") ?>"
                                    onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                                    <td><span class="text-xs font-black text-slate-400 font-mono">#<?= $res['id'] ?></span></td>

                                    <td>
                                        <p class="font-bold text-sm text-slate-800 leading-tight"><?= $name ?></p>
                                        <?php if ($email): ?>
                                            <p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[160px]"><?= $email ?></p>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <p class="font-bold text-sm text-slate-800 leading-tight"><?= $resource ?></p>
                                        <?php if ($pc): ?>
                                            <div class="flex items-center gap-1 mt-0.5">
                                                <i class="fa-solid fa-desktop text-[9px] text-slate-400"></i>
                                                <span class="text-[11px] text-slate-500 font-semibold"><?= $pc ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <p class="text-sm font-bold text-slate-700"><?= $date ?></p>
                                        <p class="text-[11px] text-green-500 font-semibold mt-0.5"><?= $start ?> – <?= $end ?></p>
                                    </td>

                                    <td>
                                        <span class="text-sm text-slate-500 font-medium"
                                              style="display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px">
                                            <?= $purpose ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-<?= $s ?>">
                                            <i class="fa-solid <?= $icon ?> text-[9px]"></i>
                                            <?= ucfirst($s) ?>
                                        </span>
                                    </td>

                                    <!-- Actions: flex-wrap so buttons wrap if tight -->
                                    <td class="text-right" onclick="event.stopPropagation()">
                                        <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                            <?php if ($s === 'pending'): ?>
                                                <button onclick="triggerApprove(<?= $res['id'] ?>, '<?= addslashes($name) ?>')"
                                                    class="h-8 px-3 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center gap-1.5">
                                                    <i class="fa-solid fa-check text-[11px]"></i> Approve
                                                </button>
                                                <button onclick="triggerDecline(<?= $res['id'] ?>, '<?= addslashes($name) ?>')"
                                                    class="h-8 px-3 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center gap-1.5">
                                                    <i class="fa-solid fa-xmark text-[11px]"></i> Decline
                                                </button>
                                            <?php else: ?>
                                                <span class="text-xs text-slate-300 font-semibold italic">No actions</span>
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
                <p class="text-xs text-slate-300 font-semibold hidden sm:block">Click any row to preview details</p>
            </div>
        </div>

        <!-- ══════════════════════════════════════
             MOBILE CARD LIST  (below md)
             ══════════════════════════════════════ -->
        <div id="mobileCardList" class="md:hidden space-y-3">
            <?php if (empty($userReservations)): ?>
                <div class="card-empty">
                    <i class="fa-solid fa-inbox text-4xl text-slate-200 mb-3 block"></i>
                    <p class="font-black text-slate-400">No requests yet</p>
                    <p class="text-slate-300 text-sm mt-1">User reservation requests will appear here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($userReservations as $res):
                    $s        = strtolower($res['status'] ?? 'pending');
                    $name     = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                    $email    = htmlspecialchars($res['user_email'] ?? '');
                    $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                    $pc       = htmlspecialchars($res['pc_number'] ?? '');
                    $rawDate  = $res['reservation_date'] ?? '';
                    $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                    $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                    $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                    $purpose  = htmlspecialchars($res['purpose'] ?? '—');
                    $created  = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                    $icon     = $statusIcons[$s] ?? 'fa-circle';
                    $mdata    = json_encode([
                        'id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,
                        'resource'=>$resource,'pc'=>$pc,'date'=>$date,
                        'start'=>$start,'end'=>$end,'purpose'=>$purpose,'created'=>$created,
                    ]);

                    $avatarBg = [
                        'pending'  => 'bg-amber-100 text-amber-700',
                        'approved' => 'bg-emerald-100 text-emerald-700',
                        'declined' => 'bg-red-100 text-red-600',
                        'canceled' => 'bg-red-100 text-red-600',
                    ][$s] ?? 'bg-slate-100 text-slate-500';
                ?>
                    <div class="req-card"
                         data-id="<?= $res['id'] ?>"
                         data-status="<?= $s ?>"
                         data-search="<?= strtolower("$name $resource $purpose $email") ?>"
                         onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                        <!-- Top: avatar + name + badge -->
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-2xl <?= $avatarBg ?> flex items-center justify-center font-black text-sm flex-shrink-0">
                                <?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="badge badge-<?= $s ?> flex-shrink-0">
                                <i class="fa-solid <?= $icon ?> text-[9px]"></i><?= ucfirst($s) ?>
                            </span>
                        </div>

                        <!-- Resource + schedule -->
                        <div class="mb-2">
                            <div class="flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-desktop text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs font-bold text-slate-700 truncate"><?= $resource ?><?= $pc ? ' · ' . $pc : '' ?></p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs text-slate-500 font-semibold"><?= $date ?></p>
                                <span class="text-[10px] text-green-500 font-bold"><?= $start ?> – <?= $end ?></span>
                            </div>
                        </div>

                        <!-- Purpose -->
                        <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= $purpose ?></p>

                        <!-- Footer: ID or action buttons for pending -->
                        <?php if ($s === 'pending'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick="triggerApprove(<?= $res['id'] ?>, '<?= addslashes($name) ?>')"
                                    class="flex-1 h-9 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-check text-[10px]"></i> Approve
                                </button>
                                <button onclick="triggerDecline(<?= $res['id'] ?>, '<?= addslashes($name) ?>')"
                                    class="flex-1 h-9 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-xmark text-[10px]"></i> Decline
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

        <!-- Mobile no-results empty state -->
        <div id="mobileEmpty" class="md:hidden card-empty" style="display:none">
            <i class="fa-solid fa-filter-circle-xmark text-4xl text-slate-200 mb-3 block"></i>
            <p class="font-black text-slate-400">No requests match</p>
            <p class="text-slate-300 text-sm mt-1">Try adjusting your search or filter.</p>
        </div>

    </main>

    <script>
    /* ──────────────────────────────────────────
       DATA
    ────────────────────────────────────────── */
    const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
    const allCards     = Array.from(document.querySelectorAll('#mobileCardList .req-card'));
    const resData      = <?= json_encode($userReservations ?? []) ?>;
    let   curTab       = 'all';
    let   approveTargetId = null, declineTargetId = null;

    /* ──────────────────────────────────────────
       TABS & FILTERS  (synced across both views)
    ────────────────────────────────────────── */
    function setTab(btn, tab) {
        document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        curTab = tab;
        applyFilters();
    }

    function applyFilters() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        let n = 0;

        const matches = el => {
            const matchTab    = curTab === 'all' ||
                                (curTab === 'declined' && ['declined','canceled'].includes(el.dataset.status)) ||
                                el.dataset.status === curTab;
            const matchSearch = !q || el.dataset.search.includes(q);
            return matchTab && matchSearch;
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

        // Mobile empty state
        const mobileEmpty = document.getElementById('mobileEmpty');
        if (allCards.length > 0) {
            mobileEmpty.style.display = cardVisible === 0 ? 'block' : 'none';
        }

        const total = allTableRows.length;
        document.getElementById('resultCount').textContent = `Showing ${n} of ${total} request${total !== 1 ? 's' : ''}`;
        document.getElementById('tableFooter').textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        curTab = 'all';
        document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === 'all'));
        applyFilters();
    }

    /* ──────────────────────────────────────────
       DETAIL MODAL
    ────────────────────────────────────────── */
    const META = {
        pending:  { icon: 'fa-clock',        bg: '#fef3c7', color: '#92400e', label: 'Pending — Awaiting your approval' },
        approved: { icon: 'fa-circle-check', bg: '#dcfce7', color: '#166534', label: 'Approved' },
        declined: { icon: 'fa-xmark-circle', bg: '#fee2e2', color: '#991b1b', label: 'Declined' },
        canceled: { icon: 'fa-ban',          bg: '#fee2e2', color: '#991b1b', label: 'Cancelled by user' },
    };

    function openDetailModal(d) {
        const m = META[d.status] || META.pending;
        document.getElementById('mId').textContent       = 'Request #' + d.id;
        document.getElementById('mName').textContent     = d.name;
        document.getElementById('mEmail').textContent    = d.email;
        document.getElementById('mResource').textContent = d.resource;
        document.getElementById('mPc').textContent       = d.pc ? 'PC: ' + d.pc : '';
        document.getElementById('mDate').textContent     = d.date;
        document.getElementById('mTime').textContent     = d.start + ' – ' + d.end;
        document.getElementById('mPurpose').textContent  = d.purpose;
        document.getElementById('mCreated').textContent  = d.created;

        const bar = document.getElementById('mStatusBar');
        bar.style.background = m.bg;
        bar.style.color      = m.color;
        bar.innerHTML = `<i class="fa-solid ${m.icon}"></i> <span>${m.label}</span>`;

        const acts = document.getElementById('mActions');
        if (d.status === 'pending') {
            acts.innerHTML = `
                <button onclick="closeModal('detail');triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');" class="btn-approve">
                    <i class="fa-solid fa-circle-check"></i> Approve
                </button>
                <button onclick="closeModal('detail');triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}');" class="btn-decline">
                    <i class="fa-solid fa-xmark"></i> Decline
                </button>`;
        } else {
            acts.innerHTML = `<button onclick="closeModal('detail')" class="btn-close"><i class="fa-solid fa-xmark text-xs"></i> Close</button>`;
        }

        openModal('detail');
    }

    /* ──────────────────────────────────────────
       APPROVE / DECLINE TRIGGERS
    ────────────────────────────────────────── */
    function triggerApprove(id, name) {
        approveTargetId = id;
        document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : '';
        openModal('approve');
    }
    function triggerDecline(id, name) {
        declineTargetId = id;
        document.getElementById('declineConfirmName').textContent = name ? `"${name}"` : '';
        openModal('decline');
    }

    document.getElementById('confirmApproveBtn').addEventListener('click', function () {
        if (!approveTargetId) return;
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
        document.getElementById('approveId').value = approveTargetId;
        document.getElementById('approveForm').submit();
    });

    document.getElementById('confirmDeclineBtn').addEventListener('click', function () {
        if (!declineTargetId) return;
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Declining…';
        document.getElementById('declineId').value = declineTargetId;
        document.getElementById('declineForm').submit();
    });

    /* ──────────────────────────────────────────
       MODAL HELPERS
    ────────────────────────────────────────── */
    const modalIds = { detail: 'detailModal', approve: 'approveModal', decline: 'declineModal' };

    function openModal(key) {
        const el = document.getElementById(modalIds[key]);
        if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
    }
    function closeModal(key) {
        const el = document.getElementById(modalIds[key]);
        if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
        if (key === 'approve') { const b = document.getElementById('confirmApproveBtn'); b.disabled = false; b.innerHTML = '<i class="fa-solid fa-check"></i> Approve'; }
        if (key === 'decline') { const b = document.getElementById('confirmDeclineBtn'); b.disabled = false; b.innerHTML = '<i class="fa-solid fa-xmark"></i> Decline'; }
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeModal('detail'); closeModal('approve'); closeModal('decline'); }
    });

    /* ──────────────────────────────────────────
       NOTIFICATION SYSTEM
    ────────────────────────────────────────── */
    let notifs    = [];
    let unread    = 0;
    let readIds   = JSON.parse(localStorage.getItem('sk_read_notifs') || '[]');
    let lastCheck = new Date().toISOString();

    function initNotifs() {
        resData.forEach(r => {
            if (r.status !== 'pending') return;
            if (readIds.includes(String(r.id))) return;
            const hrs = (Date.now() - new Date(r.created_at)) / 3600000;
            if (hrs > 48) return;
            notifs.push({ id: r.id, title: 'Pending Request', msg: `${r.visitor_name || 'User'} requested ${r.resource_name || 'a resource'}`, time: r.created_at, read: false });
        });
        unread = notifs.length;
        renderBadge();
        renderNotifs();
    }

    function toggleNotif() { document.getElementById('notifDropdown').classList.toggle('open'); }

    function markAllRead() {
        notifs.forEach(n => { n.read = true; if (!readIds.includes(String(n.id))) readIds.push(String(n.id)); });
        unread = 0;
        renderBadge(); renderNotifs();
        localStorage.setItem('sk_read_notifs', JSON.stringify(readIds));
    }

    function markRead(id) {
        const n = notifs.find(x => x.id == id);
        if (n && !n.read) {
            n.read = true; unread = Math.max(0, unread - 1);
            if (!readIds.includes(String(id))) readIds.push(String(id));
            renderBadge(); renderNotifs();
            localStorage.setItem('sk_read_notifs', JSON.stringify(readIds));
        }
        const t = document.getElementById('toast-' + id);
        if (t) t.remove();
    }

    function renderBadge() {
        const b = document.getElementById('notifBadge');
        b.style.display = unread > 0 ? 'block' : 'none';
        b.textContent   = unread > 9 ? '9+' : unread;
    }

    function renderNotifs() {
        const list = document.getElementById('notifList');
        if (!notifs.length) {
            list.innerHTML = `<div class="p-8 text-center text-slate-400"><i class="fa-regular fa-bell-slash text-3xl mb-3 block text-slate-200"></i><p class="text-sm font-bold">No new requests</p></div>`;
            return;
        }
        list.innerHTML = [...notifs].sort((a, b) => new Date(b.time) - new Date(a.time)).map(n => `
            <div class="notif-item ${!n.read ? 'unread' : ''}" onclick="handleNotifClick(${n.id})">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-amber-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-slate-800 leading-tight">${n.title}</p>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">${n.msg}</p>
                        <p class="text-[10px] text-slate-400 mt-1">${timeAgo(n.time)}</p>
                    </div>
                    ${!n.read ? '<span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0 mt-1.5"></span>' : ''}
                </div>
            </div>`).join('');
    }

    function handleNotifClick(id) {
        markRead(id);
        const res = resData.find(r => r.id == id);
        if (res) {
            openDetailModal({
                id: res.id, status: res.status,
                name: res.visitor_name || res.full_name || 'Guest',
                email: res.user_email || '',
                resource: res.resource_name || '', pc: res.pc_number || '',
                date: res.reservation_date ? new Date(res.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '—',
                start: res.start_time || '—', end: res.end_time || '—',
                purpose: res.purpose || '—',
                created: res.created_at ? new Date(res.created_at).toLocaleString() : '—',
            });
        }
        document.getElementById('notifDropdown').classList.remove('open');
    }

    function showToast(n) {
        const wrap = document.getElementById('toastWrap');
        if (document.getElementById('toast-' + n.id)) return;
        const t = document.createElement('div');
        t.id = 'toast-' + n.id; t.className = 'toast';
        t.innerHTML = `<div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-amber-600 text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm text-slate-800">${n.title}</p>
                <p class="text-xs text-slate-500 mt-0.5 truncate">${n.msg}</p>
                <p class="text-[10px] text-slate-400 mt-1">Just now</p>
            </div>
            <button onclick="this.closest('.toast').remove()" class="text-slate-300 hover:text-slate-500 ml-1 flex-shrink-0">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>`;
        t.onclick = e => { if (e.target.closest('button')) return; markRead(n.id); handleNotifClick(n.id); t.remove(); };
        wrap.appendChild(t);
        setTimeout(() => { if (document.getElementById('toast-' + n.id)) t.remove(); }, 6000);
    }

    function checkNew() {
        fetch('<?= base_url("sk/check-new-user-requests") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ last_check: lastCheck })
        })
        .then(r => r.json())
        .then(data => {
            (data.new_requests || []).forEach(req => {
                if (readIds.includes(String(req.id))) return;
                const n = { id: req.id, title: 'New Request', msg: `${req.visitor_name} requested ${req.resource_name}`, time: new Date().toISOString(), read: false };
                notifs.unshift(n); unread++;
                renderBadge(); renderNotifs(); showToast(n);
            });
            lastCheck = new Date().toISOString();
        })
        .catch(() => {});
    }

    function timeAgo(t) {
        const s = Math.floor((Date.now() - new Date(t)) / 1000);
        if (s < 60)    return 'Just now';
        if (s < 3600)  return `${Math.floor(s / 60)}m ago`;
        if (s < 86400) return `${Math.floor(s / 3600)}h ago`;
        return `${Math.floor(s / 86400)}d ago`;
    }

    /* close notif dropdown on outside click */
    document.addEventListener('click', e => {
        const drop = document.getElementById('notifDropdown');
        const bell = document.getElementById('bellBtn');
        if (!bell.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
    });

    /* ── Init ── */
    initNotifs();
    applyFilters();
    setInterval(checkNew, 30000);
    document.addEventListener('visibilitychange', () => { if (!document.hidden) checkNew(); });
    </script>
</body>
</html>