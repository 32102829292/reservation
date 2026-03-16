<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Manage PCs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden; width: 100%;
        }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav    { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item   { transition: all 0.18s; }
        .sidebar-item.active { background: #2563eb; color: white !important; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.35); }

        /* ── Mobile Nav ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(15,23,42,0.97);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── PC Cards (grid) ── */
        .pc-card {
            background: white; border-radius: 24px; padding: 1.5rem;
            border: 1px solid #e2e8f0; transition: all 0.25s ease;
            display: flex; flex-direction: column; gap: 0;
        }
        .pc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
            border-color: #bfdbfe;
        }
        .pc-card.available-card   { border-top: 3px solid #22c55e; }
        .pc-card.maintenance-card { border-top: 3px solid #f59e0b; }

        /* ── Mobile list card for PCs ── */
        .pc-list-card {
            background: white; border: 1px solid #e2e8f0; border-radius: 20px;
            padding: 1rem 1.1rem; transition: all 0.18s;
            display: flex; flex-direction: column; gap: 0.5rem;
        }
        .pc-list-card.available-card   { border-left: 4px solid #22c55e; }
        .pc-list-card.maintenance-card { border-left: 4px solid #f59e0b; }
        .pc-list-card-actions { display: flex; gap: 8px; margin-top: 0.25rem; }
        .pc-list-card-actions form { flex: 1; }
        .pc-list-card-actions form button { width: 100%; }

        /* ── Status badges ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.75rem; border-radius: 10px; font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
        .badge-available   { background: #dcfce7; color: #166534; }
        .badge-maintenance { background: #fef3c7; color: #92400e; }

        /* ── Stat cards ── */
        .stat-card {
            background: white; border-radius: 20px; padding: 1.1rem 1.25rem;
            border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }

        /* ── Form fields ── */
        .field {
            width: 100%; background: white; border: 1.5px solid #e2e8f0;
            border-radius: 14px; padding: 0.8rem 1rem;
            font-size: 0.9rem; font-family: inherit; color: #1e293b; transition: all 0.2s;
        }
        .field:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* ── Modal (centered, sm+) ── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); }

        .modal-box {
            position: relative; margin: auto; background: white; border-radius: 32px;
            width: 94%; max-width: 440px; padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);
            animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .modal-box.sm { max-width: 360px; }
        @keyframes popIn { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }

        /* ── Bottom-sheet override on mobile ── */
        @media (max-width: 639px) {
            .overlay { align-items: flex-end; padding: 0; }
            .modal-box,
            .modal-box.sm {
                max-width: 100%; width: 100%; margin: 0;
                border-radius: 28px 28px 0 0;
                max-height: 90vh; overflow-y: auto;
                animation: slideUp 0.28s cubic-bezier(0.32,0.72,0,1) both;
            }
            @keyframes slideUp {
                from { opacity:0; transform: translateY(100%); }
                to   { opacity:1; transform: translateY(0); }
            }
        }

        /* Drag-handle pill */
        .sheet-handle {
            display: none; width: 40px; height: 5px; border-radius: 999px;
            background: #e2e8f0; margin: 0 auto 16px;
        }
        @media (max-width: 639px) { .sheet-handle { display: block; } }

        /* ── Search bar ── */
        .search-field {
            background: white; border: 1px solid #e2e8f0; border-radius: 14px;
            padding: 0.65rem 1rem 0.65rem 2.4rem; font-size: 0.875rem;
            font-family: inherit; color: #1e293b; transition: all 0.2s; width: 100%;
        }
        .search-field:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* ── Filter tabs ── */
        .ftab {
            display: inline-flex; align-items: center; gap: 6px; padding: 0.45rem 1rem;
            border-radius: 12px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
            border: 1px solid #e2e8f0; color: #64748b; background: white; transition: all 0.18s;
            white-space: nowrap;
        }
        .ftab:hover  { border-color: #2563eb; color: #2563eb; }
        .ftab.active { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 4px 12px -2px rgba(37,99,235,0.3); }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }

        /* ── Action buttons ── */
        .btn-toggle-available {
            flex: 1; padding: 0.55rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 800;
            background: #fef3c7; color: #92400e; border: 1.5px solid #fcd34d; cursor: pointer; transition: all 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 5px; font-family: inherit;
        }
        .btn-toggle-available:hover { background: #fde68a; }
        .btn-toggle-maintenance {
            flex: 1; padding: 0.55rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 800;
            background: #dcfce7; color: #166534; border: 1.5px solid #86efac; cursor: pointer; transition: all 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 5px; font-family: inherit;
        }
        .btn-toggle-maintenance:hover { background: #bbf7d0; }
        .btn-delete {
            width: 36px; height: 36px; border-radius: 10px; background: #fef2f2; color: #ef4444;
            border: 1.5px solid #fecaca; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.18s; flex-shrink: 0;
        }
        .btn-delete:hover { background: #fee2e2; border-color: #fca5a5; }
        .btn-primary {
            background: #2563eb; color: white; border: none; border-radius: 14px;
            padding: 0.8rem 1.5rem; font-size: 0.875rem; font-weight: 800; cursor: pointer;
            transition: all 0.18s; display: flex; align-items: center; gap: 7px; font-family: inherit;
        }
        .btn-primary:hover { background: #1d4ed8; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.4); }
        .btn-cancel {
            background: #f1f5f9; color: #475569; border: none; border-radius: 14px;
            padding: 0.8rem 1.5rem; font-size: 0.875rem; font-weight: 800; cursor: pointer;
            transition: all 0.18s; display: flex; align-items: center; gap: 7px; font-family: inherit; flex: 1;
        }
        .btn-cancel:hover { background: #e2e8f0; }
        .btn-danger {
            background: #ef4444; color: white; border: none; border-radius: 14px;
            padding: 0.8rem 1.5rem; font-size: 0.875rem; font-weight: 800; cursor: pointer;
            transition: all 0.18s; display: flex; align-items: center; gap: 7px; font-family: inherit; flex: 1;
            justify-content: center;
        }
        .btn-danger:hover { background: #dc2626; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="flex min-h-screen">

    <?php
    $page = $page ?? 'manage-pcs';
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

    $totalPcs       = count($pcs ?? []);
    $availableCount = count(array_filter($pcs ?? [], fn($p) => $p['status'] === 'available'));
    $maintenCount   = $totalPcs - $availableCount;
    ?>

    <!-- ══ ADD PC MODAL ══ -->
    <div id="addModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeAdd()"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div class="flex items-start justify-between mb-6">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Asset Management</p>
                    <h3 class="text-xl font-black text-slate-900">Add New Station</h3>
                    <p class="text-sm text-slate-400 font-medium mt-0.5">Register a PC to the asset pool.</p>
                </div>
                <button onclick="closeAdd()" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/add-pc" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-[0.68rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">
                        PC Number / Station Name
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-desktop absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                        <input type="text" name="pc_number" required placeholder="e.g. PC-01 or Lab Station 3"
                               value="<?= old('pc_number') ?>"
                               class="field pl-10">
                    </div>
                </div>
                <div>
                    <label class="block text-[0.68rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">
                        Initial Status
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-circle-dot absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                        <select name="status" class="field pl-10 appearance-none">
                            <option value="available" <?= old('status','available') == 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="maintenance" <?= old('status') == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAdd()" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        <i class="fa-solid fa-floppy-disk text-sm"></i> Save Station
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ DELETE CONFIRM MODAL ══ -->
    <div id="deleteModal" class="overlay">
        <div class="overlay-bg" onclick="closeDelete()"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div class="text-center py-2">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl text-red-500">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-1">Delete Station?</h3>
                <p class="text-sm text-slate-400 font-medium">This action cannot be undone.</p>
                <p id="deleteStationName" class="text-sm font-black text-slate-700 mt-2"></p>
            </div>
            <div class="flex gap-3 mt-6">
                <button class="btn-cancel" onclick="closeDelete()"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <a id="deleteLink" href="#" class="btn-danger"><i class="fa-solid fa-trash-can text-sm"></i> Delete</a>
            </div>
        </div>
    </div>

    <!-- ══ SIDEBAR ══ -->
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

    <!-- ══ MOBILE NAV ══ -->
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

    <!-- ══ MAIN ══ -->
    <main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 fade-up">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Asset Management</p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Manage PCs</h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5">Monitor and manage station availability</p>
            </div>
            <button onclick="openAdd()" class="btn-primary flex-shrink-0">
                <i class="fa-solid fa-plus text-sm"></i> Add New Station
            </button>
        </header>

        <!-- Flash messages -->
        <?php if (session()->has('success')): ?>
        <div id="flashMsg" class="mb-6 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            <?= session('success') ?>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>
        <?php endif; ?>
        <?php if (session()->has('error')): ?>
        <div id="flashMsg" class="mb-6 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
            <?= session('error') ?>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>
        <?php endif; ?>

        <!-- ── Stat cards: 3-col always (was grid-cols-3, stays the same — looks fine at all widths) ── -->
        <!-- Changed to grid-cols-1 sm:grid-cols-3 so on very small phones each card is full-width -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="stat-card border-blue-400 cursor-pointer" onclick="setFilter('all')" data-filter="all">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Stations</p>
                    <i class="fa-solid fa-layer-group text-blue-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-slate-700"><?= $totalPcs ?></p>
            </div>
            <div class="stat-card border-emerald-400 cursor-pointer" onclick="setFilter('available')" data-filter="available">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Available</p>
                    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-emerald-600"><?= $availableCount ?></p>
            </div>
            <div class="stat-card border-amber-400 cursor-pointer" onclick="setFilter('maintenance')" data-filter="maintenance">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Maintenance</p>
                    <i class="fa-solid fa-wrench text-amber-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-amber-600"><?= $maintenCount ?></p>
            </div>
        </div>

        <!-- Search + filter bar -->
        <div class="bg-white border border-slate-200 rounded-[28px] p-4 mb-5 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input id="searchInput" type="text" placeholder="Search stations…"
                           class="search-field" oninput="applyFilters()">
                </div>
                <button onclick="clearFilters()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center gap-2 flex-shrink-0">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </button>
            </div>
            <!-- Quick filter tabs -->
            <div class="flex gap-2 mt-3 overflow-x-auto pb-0.5">
                <button class="ftab active" data-tab="all" onclick="setFilter('all')">
                    <i class="fa-solid fa-layer-group text-xs"></i> All
                    <span class="text-[9px] font-black opacity-60"><?= $totalPcs ?></span>
                </button>
                <button class="ftab" data-tab="available" onclick="setFilter('available')">
                    <i class="fa-solid fa-circle-check text-xs"></i> Available
                    <span class="text-[9px] font-black opacity-60"><?= $availableCount ?></span>
                </button>
                <button class="ftab" data-tab="maintenance" onclick="setFilter('maintenance')">
                    <i class="fa-solid fa-wrench text-xs"></i> Maintenance
                    <span class="text-[9px] font-black opacity-60"><?= $maintenCount ?></span>
                </button>
            </div>
        </div>

        <!-- Result count -->
        <p id="resultCount" class="text-xs font-bold text-slate-400 px-1 mb-4"></p>

        <?php if (empty($pcs)): ?>
        <div class="flex flex-col items-center justify-center py-20 text-center bg-white border border-dashed border-slate-200 rounded-[28px]">
            <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-desktop text-4xl text-slate-300"></i>
            </div>
            <p class="font-black text-slate-400 text-lg">No stations yet</p>
            <p class="text-slate-300 text-sm mt-1 mb-5">Add your first PC to get started.</p>
            <button onclick="openAdd()" class="btn-primary">
                <i class="fa-solid fa-plus text-sm"></i> Add Station
            </button>
        </div>
        <?php else: ?>

        <!-- ── Desktop grid (sm+) ── -->
        <div id="pcGrid" class="hidden sm:grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php foreach ($pcs as $pc):
                $isAvail = $pc['status'] === 'available';
                $assetId = str_pad($pc['id'], 4, '0', STR_PAD_LEFT);
                $pcNum   = htmlspecialchars($pc['pc_number']);
            ?>
            <div class="pc-card <?= $isAvail ? 'available-card' : 'maintenance-card' ?>"
                 data-status="<?= $pc['status'] ?>"
                 data-search="<?= strtolower($pcNum) ?>">

                <!-- Top row -->
                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0
                                <?= $isAvail ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' ?>">
                        <i class="fa-solid fa-desktop text-xl"></i>
                    </div>
                    <span class="badge <?= $isAvail ? 'badge-available' : 'badge-maintenance' ?>">
                        <i class="fa-solid <?= $isAvail ? 'fa-circle-check' : 'fa-wrench' ?> text-[9px]"></i>
                        <?= ucfirst($pc['status']) ?>
                    </span>
                </div>

                <!-- Info -->
                <div class="mb-4">
                    <h3 class="text-lg font-black text-slate-800 leading-tight">Station <?= $pcNum ?></h3>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5 font-mono">Asset #<?= $assetId ?></p>
                </div>

                <!-- Availability bar -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Status</p>
                        <p class="text-[10px] font-black <?= $isAvail ? 'text-emerald-600' : 'text-amber-600' ?>">
                            <?= $isAvail ? 'Ready for booking' : 'Under maintenance' ?>
                        </p>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full">
                        <div class="h-1.5 rounded-full <?= $isAvail ? 'bg-emerald-400' : 'bg-amber-400' ?>"
                             style="width: <?= $isAvail ? '100%' : '40%' ?>"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 mt-auto">
                    <form action="/admin/update-pc-status" method="POST" class="flex-1">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                        <input type="hidden" name="status" value="<?= $isAvail ? 'maintenance' : 'available' ?>">
                        <button type="submit" class="<?= $isAvail ? 'btn-toggle-available' : 'btn-toggle-maintenance' ?> w-full">
                            <i class="fa-solid <?= $isAvail ? 'fa-wrench' : 'fa-circle-check' ?> text-[11px]"></i>
                            <?= $isAvail ? 'Set Maintenance' : 'Set Available' ?>
                        </button>
                    </form>
                    <button onclick="confirmDelete(<?= $pc['id'] ?>, '<?= addslashes($pcNum) ?>')"
                            class="btn-delete" title="Delete station">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── Mobile list (below sm) ── -->
        <div id="pcListMobile" class="sm:hidden space-y-3">
            <?php foreach ($pcs as $pc):
                $isAvail = $pc['status'] === 'available';
                $assetId = str_pad($pc['id'], 4, '0', STR_PAD_LEFT);
                $pcNum   = htmlspecialchars($pc['pc_number']);
            ?>
            <div class="pc-list-card <?= $isAvail ? 'available-card' : 'maintenance-card' ?>"
                 data-status="<?= $pc['status'] ?>"
                 data-search="<?= strtolower($pcNum) ?>">

                <!-- Top row: icon + name + badge -->
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                    <?= $isAvail ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' ?>">
                            <i class="fa-solid fa-desktop text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-sm text-slate-800 truncate">Station <?= $pcNum ?></p>
                            <p class="text-[11px] text-slate-400 font-semibold font-mono">Asset #<?= $assetId ?></p>
                        </div>
                    </div>
                    <span class="badge <?= $isAvail ? 'badge-available' : 'badge-maintenance' ?> flex-shrink-0">
                        <i class="fa-solid <?= $isAvail ? 'fa-circle-check' : 'fa-wrench' ?> text-[9px]"></i>
                        <?= ucfirst($pc['status']) ?>
                    </span>
                </div>

                <!-- Status bar -->
                <div class="px-0.5">
                    <div class="w-full h-1.5 bg-slate-100 rounded-full">
                        <div class="h-1.5 rounded-full <?= $isAvail ? 'bg-emerald-400' : 'bg-amber-400' ?>"
                             style="width: <?= $isAvail ? '100%' : '40%' ?>"></div>
                    </div>
                    <p class="text-[10px] font-semibold <?= $isAvail ? 'text-emerald-600' : 'text-amber-600' ?> mt-1">
                        <?= $isAvail ? 'Ready for booking' : 'Under maintenance' ?>
                    </p>
                </div>

                <!-- Action buttons: full-width toggle + delete -->
                <div class="pc-list-card-actions">
                    <form action="/admin/update-pc-status" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                        <input type="hidden" name="status" value="<?= $isAvail ? 'maintenance' : 'available' ?>">
                        <button type="submit" class="<?= $isAvail ? 'btn-toggle-available' : 'btn-toggle-maintenance' ?> py-2.5">
                            <i class="fa-solid <?= $isAvail ? 'fa-wrench' : 'fa-circle-check' ?> text-[11px]"></i>
                            <?= $isAvail ? 'Set Maintenance' : 'Set Available' ?>
                        </button>
                    </form>
                    <button onclick="confirmDelete(<?= $pc['id'] ?>, '<?= addslashes($pcNum) ?>')"
                            class="btn-delete" title="Delete station" style="height:auto;min-height:36px;">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No results (shared) -->
        <div id="noResultsMsg" class="hidden mt-6 text-center py-12 bg-white border border-slate-200 rounded-3xl">
            <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300 mb-2"></i>
            <p class="text-sm font-bold text-slate-500">No stations match your search.</p>
        </div>

        <?php endif; ?>

    </main>

    <script>
    /* ── Modal helpers ── */
    function openAdd()  { document.getElementById('addModal').classList.add('open');    document.body.style.overflow = 'hidden'; }
    function closeAdd() { document.getElementById('addModal').classList.remove('open'); document.body.style.overflow = ''; }

    function confirmDelete(id, name) {
        document.getElementById('deleteStationName').textContent = `"Station ${name}"`;
        document.getElementById('deleteLink').href = `/admin/delete-pc/${id}`;
        document.getElementById('deleteModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDelete() {
        document.getElementById('deleteModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    /* Close overlays on backdrop click */
    document.querySelectorAll('.overlay').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target === this) {
                if (this.id === 'addModal')    closeAdd();
                if (this.id === 'deleteModal') closeDelete();
            }
        });
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { closeAdd(); closeDelete(); }
    });

    /* ── Filters — synced across desktop grid + mobile list ── */
    var curFilter = 'all';

    function setFilter(f) {
        curFilter = f;
        document.querySelectorAll('.ftab').forEach(function(t) {
            t.classList.toggle('active', t.dataset.tab === f);
        });
        document.querySelectorAll('[data-filter]').forEach(function(c) {
            c.classList.toggle('ring-2',        c.dataset.filter === f);
            c.classList.toggle('ring-blue-400', c.dataset.filter === f);
        });
        applyFilters();
    }

    function applyFilters() {
        var q = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();

        /* Desktop grid cards */
        var gridCards = document.querySelectorAll('#pcGrid .pc-card[data-status]');
        /* Mobile list cards */
        var listCards = document.querySelectorAll('#pcListMobile .pc-list-card[data-status]');

        var visible = 0;
        var total   = gridCards.length; /* same count as list, use grid as source of truth */

        function filterCard(card) {
            var matchStatus = curFilter === 'all' || card.dataset.status === curFilter;
            var matchSearch = !q || card.dataset.search.includes(q);
            var show = matchStatus && matchSearch;
            card.style.display = show ? '' : 'none';
            return show;
        }

        gridCards.forEach(function(c) { if (filterCard(c)) visible++; });
        listCards.forEach(filterCard); /* listCards mirror same data, count from grid */

        var rc = document.getElementById('resultCount');
        if (rc) rc.textContent = 'Showing ' + visible + ' of ' + total + ' station' + (total !== 1 ? 's' : '');

        var noMsg = document.getElementById('noResultsMsg');
        if (noMsg) noMsg.classList.toggle('hidden', visible > 0);
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        setFilter('all');
    }

    /* ── Flash auto-dismiss ── */
    setTimeout(function() {
        var f = document.getElementById('flashMsg');
        if (f) { f.style.transition = 'opacity 0.5s'; f.style.opacity = '0'; setTimeout(function() { f && f.remove(); }, 500); }
    }, 5000);

    /* Init */
    applyFilters();
    </script>
</body>
</html>