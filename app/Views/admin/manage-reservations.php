<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage Reservations | Admin</title>
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
            overflow-x: hidden;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
        .sidebar-item { transition: all 0.2s; border-radius: 20px; }
        .sidebar-item.active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37,99,235,0.3); }

        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(30,41,59,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; scroll-behavior: smooth; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

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

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            border-left-width: 4px;
            transition: all 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
        }

        /* Content Card */
        .content-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        /* Table Styles */
        .table-wrap { overflow-x: auto; }
        @media (min-width: 1024px) {
            .table-wrap { overflow-x: visible; }
            table { min-width: 0 !important; }
        }
        @media (max-width: 1023px) {
            table { min-width: 800px; }
        }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th {
            background-color: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b;
            padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; font-weight: 500; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        /* Form Elements */
        input, select {
            background: #fcfdfe; border: 1px solid #e2e8f0; padding: 0.75rem 1.25rem;
            font-size: 0.9rem; transition: all 0.2s; border-radius: 12px; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        input:focus, select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.08); }

        /* Status Badges */
        .status-badge { 
            padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.7rem; 
            font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; 
            display: inline-block; white-space: nowrap; 
        }
        .status-pending  { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-declined { background-color: #fee2e2; color: #991b1b; }
        .status-claimed { background-color: #f3e8ff; color: #6b21a8; }

        /* Action Buttons */
        .btn-action { 
            padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; 
            font-size: 0.78rem; transition: all 0.2s; cursor: pointer; 
            border: none; display: inline-flex; align-items: center; gap: 5px; 
            font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap; 
        }
        .btn-details { background-color: #f1f5f9; color: #475569; }
        .btn-details:hover { background-color: #e2e8f0; color: #1e293b; }
        .btn-approve { background-color: #dcfce7; color: #166534; }
        .btn-approve:hover { background-color: #bbf7d0; }
        .btn-decline { background-color: #fee2e2; color: #991b1b; }
        .btn-decline:hover { background-color: #fecaca; }

        /* Modal Styles */
        .modal { 
            display: none; position: fixed; inset: 0; 
            background: rgba(15,23,42,0.65); backdrop-filter: blur(6px); 
            z-index: 200; padding: 1.5rem; overflow-y: auto; 
            align-items: center; justify-content: center; 
        }
        .modal.show { display: flex; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .modal-card { 
            background: white; border-radius: 32px; width: 100%; 
            max-width: 520px; padding: 2.5rem; animation: slideUp 0.2s ease; 
            max-height: 90vh; overflow-y: auto; margin: auto; 
        }
        @keyframes slideUp { from { transform:translateY(16px); opacity:0; } to { transform:translateY(0); opacity:1; } }

        .detail-row { 
            display: flex; justify-content: space-between; align-items: flex-start; 
            padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; 
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { 
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase; 
            letter-spacing: 0.1em; color: #94a3b8; flex-shrink: 0; 
        }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }

        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .reservation-row { transition: background 0.15s; }
        .reservation-row:hover td { background-color: #f8fafc; }

        /* Quick Action Bar */
        .quick-action-bar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border-radius: 20px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Scrollbar */
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
    </style>
</head>
<body>
    <div class="app-wrapper">
        <?php
        $navItems = [
            ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
            ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
            ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
            ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
            ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
            ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
            ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
            ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
            ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
        ];
        ?>

        <!-- Sidebar -->
        <aside class="hidden lg:block w-80 flex-shrink-0 p-6">
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                    <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
                </div>
                <nav class="sidebar-nav space-y-1">
                    <?php foreach ($navItems as $item):
                        $active = (isset($page) && $page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
                    ?>
                        <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                            <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                            <?= $item['label'] ?>
                            <?php if ($item['key'] == 'manage-reservations' && ($pendingCount ?? 0) > 0): ?>
                                <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    <?= $pendingCount ?>
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
                    $btnClass = $isActive ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
                ?>
                    <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                        <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                        <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                    </a>
                <?php endforeach; ?>
                <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition hover:bg-red-500/30 text-red-400">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <!-- Header -->
            <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Manage Reservations</h2>
                    <p class="text-slate-500 font-medium">Review and manage all reservation requests.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</p>
                        <p class="text-xl font-black text-blue-600" id="totalCount"><?= count($reservations) ?></p>
                    </div>
                    <a href="/admin/new-reservation" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> New
                    </a>
                </div>
            </header>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 animate-pulse">
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="stat-card border-l-4 border-blue-500">
                    <div class="text-xs font-bold text-slate-400 mb-1">Total</div>
                    <div class="text-2xl font-black text-slate-800"><?= $total ?? count($reservations) ?></div>
                </div>
                <div class="stat-card border-l-4 border-amber-500">
                    <div class="text-xs font-bold text-slate-400 mb-1">Pending</div>
                    <div class="text-2xl font-black text-amber-600"><?= $pendingCount ?? 0 ?></div>
                </div>
                <div class="stat-card border-l-4 border-emerald-500">
                    <div class="text-xs font-bold text-slate-400 mb-1">Approved</div>
                    <div class="text-2xl font-black text-emerald-600"><?= $approvedCount ?? 0 ?></div>
                </div>
                <div class="stat-card border-l-4 border-rose-500">
                    <div class="text-xs font-bold text-slate-400 mb-1">Declined</div>
                    <div class="text-2xl font-black text-rose-600"><?= $declinedCount ?? 0 ?></div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="content-card">
                <!-- Filters -->
                <div class="p-4 lg:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="searchInput" class="pl-10" placeholder="Search by name, asset, date...">
                    </div>
                    <select id="statusFilter" class="sm:w-44">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                        <option value="claimed">Claimed</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Resource</th>
                                <th>PC</th>
                                <th>Schedule</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationTableBody">
                            <?php if (empty($reservations)): ?>
                                <tr><td colspan="8"><div class="empty-state">
                                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                                    <p class="font-bold text-slate-500">No reservations found.</p>
                                </div></td></tr>
                            <?php else: ?>
                                <?php foreach ($reservations as $res): 
                                    $status = $res['status'] ?? 'pending';
                                    if (!empty($res['claimed']) && $res['claimed'] == 1) {
                                        $status = 'claimed';
                                    }
                                ?>
                                    <tr class="reservation-row" data-status="<?= $status ?>" data-id="<?= $res['id'] ?>">
                                        <td><span class="text-slate-400 font-bold">#</span><?= $res['id'] ?></td>
                                        <td>
                                            <div class="font-bold text-slate-800"><?= htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest') ?></div>
                                            <?php if (!empty($res['user_email'])): ?>
                                                <div class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($res['user_email']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-semibold"><?= htmlspecialchars($res['resource_name'] ?? 'Resource #' . $res['resource_id']) ?></div>
                                        </td>
                                        <td>
                                            <?php if (!empty($res['pc_number'])): ?>
                                                <span class="text-xs bg-blue-50 text-blue-700 font-bold px-2 py-1 rounded-lg">
                                                    <?= htmlspecialchars($res['pc_number']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="text-slate-700 font-semibold"><?= htmlspecialchars($res['reservation_date']) ?></div>
                                            <div class="text-xs text-blue-600 font-bold mt-0.5">
                                                <?= htmlspecialchars(substr($res['start_time'] ?? '', 0, 5)) ?> – 
                                                <?= htmlspecialchars(substr($res['end_time'] ?? '', 0, 5)) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-slate-600 max-w-[140px] truncate">
                                                <?= htmlspecialchars($res['purpose'] ?: '—') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= $status ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="viewDetails(<?= $res['id'] ?>)" class="btn-action btn-details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <?php if ($status === 'pending'): ?>
                                                    <button onclick="approveReservation(<?= $res['id'] ?>)" class="btn-action btn-approve">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <button onclick="handleDecline(<?= $res['id'] ?>)" class="btn-action btn-decline">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                <?php elseif ($status === 'approved'): ?>
                                                    <span class="text-xs font-bold text-emerald-600 px-2">✓</span>
                                                <?php elseif ($status === 'claimed'): ?>
                                                    <span class="text-xs font-bold text-purple-600 px-2">✓✓</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- No Results -->
                <div id="noResults" class="hidden empty-state">
                    <i class="fa-solid fa-filter-circle-xmark text-3xl mb-2 block"></i>
                    <p class="font-bold">No reservations match your search.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal" onclick="handleModalBackdrop(event, 'detailsModal')">
        <div class="modal-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black">Reservation Details</h3>
                <span id="modalStatusBadge" class="status-badge"></span>
            </div>
            <div id="modalBody" class="bg-slate-50 rounded-3xl p-5 border border-slate-100 mb-5 space-y-1"></div>
            
            <!-- QR Code Section -->
            <div id="qrSection" class="bg-white border-2 border-dashed border-blue-100 rounded-3xl p-6 flex flex-col items-center mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">E-Ticket</p>
                <canvas id="qrCanvas" class="mx-auto rounded-xl"></canvas>
                <p id="qrCodeText" class="text-xs text-slate-400 font-mono mt-3 text-center break-all px-2"></p>
                <button onclick="downloadTicket()" class="mt-4 flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition">
                    <i class="fa-solid fa-download"></i> Download
                </button>
            </div>

            <!-- Claimed Message -->
            <div id="claimedMessage" class="hidden bg-purple-50 border-2 border-dashed border-purple-200 rounded-3xl p-6 text-center mb-5">
                <i class="fa-solid fa-check-double text-3xl text-purple-500 mb-2"></i>
                <p class="font-bold text-purple-700">Ticket Already Claimed</p>
                <p class="text-xs text-purple-500 mt-1">This reservation has been used.</p>
            </div>

            <button onclick="closeModal('detailsModal')" class="w-full py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">
                Close
            </button>
        </div>
    </div>

    <!-- Decline Modal -->
    <div id="declineModal" class="modal" onclick="handleModalBackdrop(event, 'declineModal')">
        <div class="modal-card" style="max-width:380px;">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-black">Decline Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p class="text-slate-600 text-sm mt-3 font-bold" id="declineConfirmName"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeModal('declineModal')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">
                    Cancel
                </button>
                <button id="confirmDeclineBtn" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Decline
                </button>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="modal" onclick="handleModalBackdrop(event, 'approveModal')">
        <div class="modal-card" style="max-width:380px;">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h3 class="text-xl font-black">Approve Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This will confirm the reservation.</p>
                <p class="text-slate-600 text-sm mt-3 font-bold" id="approveConfirmName"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeModal('approveModal')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">
                    Cancel
                </button>
                <button id="confirmApproveBtn" class="flex-1 py-4 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Approve
                </button>
            </div>
        </div>
    </div>

    <!-- Forms -->
    <form id="declineForm" method="POST" action="<?= base_url('admin/decline') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="declineId">
    </form>

    <form id="approveForm" method="POST" action="<?= base_url('admin/approve') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="approveId">
    </form>

    <script>
        const reservationsData = <?= json_encode($reservations) ?>;
        let declineTargetId = null;
        let approveTargetId = null;

        // Filter functionality
        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            let count = 0;
            
            document.querySelectorAll('.reservation-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(searchTerm);
                const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
                const visible = matchesSearch && matchesStatus;
                
                row.style.display = visible ? '' : 'none';
                if (visible) count++;
            });
            
            document.getElementById('totalCount').textContent = count;
            document.getElementById('noResults').classList.toggle('hidden', count > 0);
        }

        // View details
        function viewDetails(id) {
            const res = reservationsData.find(r => r.id == id);
            if (!res) return;

            const name = res.visitor_name || res.full_name || 'Guest';
            const code = res.e_ticket_code || `RES-${res.id}-${res.reservation_date}`;
            const isClaimed = res.claimed == 1;
            
            // Format status
            let displayStatus = res.status;
            if (isClaimed) {
                displayStatus = 'claimed';
            }
            
            const badge = document.getElementById('modalStatusBadge');
            badge.textContent = displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1);
            badge.className = `status-badge status-${displayStatus}`;

            // Build details
            const detailsHtml = `
                <div class="detail-row"><span class="detail-label">Reservation #</span><span class="detail-value">#${res.id}</span></div>
                <div class="detail-row"><span class="detail-label">Name</span><span class="detail-value">${name}</span></div>
                <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">${res.user_email || res.visitor_email || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Type</span><span class="detail-value">${res.visitor_type || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value">${res.resource_name || 'Resource #' + res.resource_id}</span></div>
                <div class="detail-row"><span class="detail-label">PC Station</span><span class="detail-value">${res.pc_number || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value">${res.reservation_date}</span></div>
                <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">${res.start_time?.substring(0,5)} – ${res.end_time?.substring(0,5)}</span></div>
                <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value">${res.purpose || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">E-Ticket</span><span class="detail-value font-mono text-xs">${code}</span></div>
            `;
            
            document.getElementById('modalBody').innerHTML = detailsHtml;

            // Handle QR and claimed sections
            const qrSection = document.getElementById('qrSection');
            const claimedMessage = document.getElementById('claimedMessage');

            if (isClaimed) {
                qrSection.style.display = 'none';
                claimedMessage.classList.remove('hidden');
            } else {
                qrSection.style.display = 'flex';
                claimedMessage.classList.add('hidden');
                
                // Generate QR
                QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
                    width: 160,
                    margin: 1,
                    color: { dark: '#1e293b', light: '#ffffff' }
                });
                document.getElementById('qrCodeText').textContent = code;
            }

            openModal('detailsModal');
        }

        function downloadTicket() {
            const canvas = document.getElementById('qrCanvas');
            const code = document.getElementById('qrCodeText').textContent;
            const link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // Approve functionality
        function approveReservation(id) {
            approveTargetId = id;
            const res = reservationsData.find(r => r.id == id);
            const name = res ? (res.visitor_name || 'Guest') : '';
            document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : '';
            openModal('approveModal');
        }

        document.getElementById('confirmApproveBtn').addEventListener('click', function() {
            if (!approveTargetId) return;
            
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving...';
            
            document.getElementById('approveId').value = approveTargetId;
            document.getElementById('approveForm').submit();
        });

        // Decline functionality
        function handleDecline(id) {
            declineTargetId = id;
            const res = reservationsData.find(r => r.id == id);
            const name = res ? (res.visitor_name || 'Guest') : '';
            document.getElementById('declineConfirmName').textContent = name ? `"${name}"` : '';
            openModal('declineModal');
        }

        document.getElementById('confirmDeclineBtn').addEventListener('click', function() {
            if (!declineTargetId) return;
            
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Declining...';
            
            document.getElementById('declineId').value = declineTargetId;
            document.getElementById('declineForm').submit();
        });

        // Modal helpers
        function openModal(id) { 
            document.getElementById(id).classList.add('show'); 
            document.body.style.overflow = 'hidden'; 
        }
        
        function closeModal(id) { 
            document.getElementById(id).classList.remove('show'); 
            document.body.style.overflow = ''; 
            
            // Reset buttons
            if (id === 'approveModal') {
                const btn = document.getElementById('confirmApproveBtn');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Approve';
            }
            if (id === 'declineModal') {
                const btn = document.getElementById('confirmDeclineBtn');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Decline';
            }
        }
        
        function handleModalBackdrop(event, modalId) { 
            if (event.target === document.getElementById(modalId)) closeModal(modalId); 
        }
        
        document.addEventListener('keydown', e => { 
            if (e.key === 'Escape') { 
                closeModal('detailsModal'); 
                closeModal('approveModal'); 
                closeModal('declineModal'); 
            } 
        });

        // Initialize filter
        filterTable();
    </script>
</body>
</html>