<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | <?= esc($user_name ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
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

        main { min-width: 0; }

        .content-card {
            background: white; border-radius: 32px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); overflow: hidden;
        }

        .table-wrap { overflow-x: auto; }
        @media (min-width: 1024px) { .table-wrap { overflow-x: visible; } table { min-width: 0 !important; } }
        @media (max-width: 1023px) { table { min-width: 760px; } }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th {
            background-color: #f8fafc; font-weight: 800; text-transform: uppercase;
            font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b;
            padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; font-weight: 500; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        input, select {
            background: #fcfdfe; border: 1px solid #e2e8f0; padding: 0.75rem 1.25rem;
            font-size: 0.9rem; transition: all 0.2s; border-radius: 12px; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        input:focus, select:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 4px rgba(22,163,74,0.08); }

        .status-badge { padding: 0.35rem 0.75rem; border-radius: 10px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; white-space: nowrap; }
        .status-pending  { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #dcfce7; color: #166534; }
        .status-declined, .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .status-claimed { background-color: #f3e8ff; color: #6b21a8; }

        .btn-action { padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.78rem; transition: all 0.2s; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 5px; font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap; }
        .btn-details { background-color: #f1f5f9; color: #475569; }
        .btn-details:hover { background-color: #e2e8f0; color: #1e293b; }
        .btn-cancel { background-color: #fee2e2; color: #991b1b; }
        .btn-cancel:hover { background-color: #fecaca; }
        .btn-cancel:disabled { opacity: 0.4; cursor: not-allowed; }

        .modal { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.65); backdrop-filter: blur(6px); z-index: 200; padding: 1.5rem; overflow-y: auto; }
        .modal.show { display: flex; align-items: flex-start; justify-content: center; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .modal-card { background: white; border-radius: 32px; width: 100%; max-width: 520px; padding: 2.5rem; animation: slideUp 0.2s ease; max-height: 90vh; overflow-y: auto; margin: auto; }
        @keyframes slideUp { from { transform:translateY(16px); opacity:0; } to { transform:translateY(0); opacity:1; } }

        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; flex-shrink: 0; }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }

        .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
        .reservation-row { transition: background 0.15s; }
        .reservation-row:hover td { background-color: #f8fafc; }
        .reservation-row[data-status="declined"] td,
        .reservation-row[data-status="cancelled"] td { opacity: 0.6; }

        .fairness-badge {
            background: #dbeafe; color: #1e40af; padding: 0.5rem 1rem;
            border-radius: 100px; font-size: 0.75rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }

        .claimed-badge {
            background: #f3e8ff;
            color: #6b21a8;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
        ['url' => '/dashboard',       'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/profile',         'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];
    ?>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
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

    <!-- Mobile Nav -->
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

    <!-- Main Content -->
    <main class="flex-1 min-w-0 p-4 lg:p-12 pb-32">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">My Reservations</h2>
                <p class="text-slate-500 font-medium">Track and manage your booking requests.</p>
            </div>
            <div class="flex items-center gap-4">
                <?php if (isset($remainingReservations)): ?>
                    <div class="fairness-badge">
                        <i class="fa-solid fa-clock"></i>
                        <?= $remainingReservations ?> of 3 remaining
                    </div>
                <?php endif; ?>
                <div class="text-right flex-shrink-0">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Showing</p>
                    <p class="text-xl font-black text-green-600" id="totalCount">0</p>
                </div>
                <a href="<?= base_url('/reservation') ?>" class="flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition flex-shrink-0">
                    <i class="fa-solid fa-plus"></i> New
                </a>
            </div>
        </header>

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
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" class="pl-10" placeholder="Search by resource, date, purpose…">
                </div>
                <select id="statusFilter" class="sm:w-44 flex-shrink-0">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="claimed">Claimed</option>
                    <option value="declined">Declined</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Table -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:56px">ID</th>
                            <th>Resource</th>
                            <th>PC Number</th>
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
                                <a href="<?= base_url('/reservation') ?>" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                                    <i class="fa-solid fa-plus"></i> Make one now
                                </a>
                            </div></td></tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <?php
                                    $status    = strtolower($res['status'] ?? 'pending');
                                    // Override status if claimed
                                    if (!empty($res['claimed']) && $res['claimed'] == 1) {
                                        $status = 'claimed';
                                    }
                                    $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                    $pcNumber  = htmlspecialchars($res['pc_number'] ?? '—');
                                    $purpose   = htmlspecialchars($res['purpose'] ?: '—');
                                    
                                    // Format date
                                    $date = new DateTime($res['reservation_date']);
                                    $formattedDate = $date->format('M j, Y');
                                    
                                    // Format time
                                    $startTime = date('g:i A', strtotime($res['start_time']));
                                    $endTime = date('g:i A', strtotime($res['end_time']));
                                ?>
                                <tr class="reservation-row" data-status="<?= $status ?>" data-id="<?= $res['id'] ?>">
                                    <td><span class="text-slate-400 font-bold">#</span><?= $res['id'] ?></td>
                                    <td>
                                        <div class="font-bold text-slate-800"><?= $resource ?></div>
                                        <?php if (!empty($res['e_ticket_code'])): ?>
                                            <div class="text-[10px] text-green-600 font-mono mt-1"><?= $res['e_ticket_code'] ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($pcNumber && $pcNumber !== '—'): ?>
                                            <div class="text-xs bg-green-50 text-green-700 font-bold px-2 py-1 rounded-lg inline-block"><?= $pcNumber ?></div>
                                        <?php else: ?>
                                            <span class="text-slate-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-slate-700 font-semibold"><?= $formattedDate ?></div>
                                        <div class="text-xs text-green-600 font-bold mt-0.5"><?= $startTime ?> – <?= $endTime ?></div>
                                    </td>
                                    <td><div class="text-slate-600 max-w-[140px] truncate"><?= $purpose ?></div></td>
                                    <td>
                                        <?php if ($status === 'claimed'): ?>
                                            <span class="claimed-badge">
                                                <i class="fa-solid fa-check-double text-xs"></i> Claimed
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-<?= $status ?>"><?= ucfirst($status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button onclick="viewDetails(<?= $res['id'] ?>)" class="btn-action btn-details">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <?php if ($status === 'pending'): ?>
                                                <button onclick="handleCancel(<?= $res['id'] ?>)" class="btn-action btn-cancel" id="cancelBtn-<?= $res['id'] ?>">
                                                    <i class="fa-solid fa-xmark"></i> Cancel
                                                </button>
                                            <?php elseif ($status === 'approved'): ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-purple-400 px-2">
                                                    <i class="fa-solid fa-hourglass-half"></i> Ready
                                                </span>
                                            <?php elseif ($status === 'claimed'): ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 px-2">
                                                    <i class="fa-solid fa-check-double"></i> Used
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-300 px-2">
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
    </main>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal" onclick="handleModalBackdrop(event, 'detailsModal')">
        <div class="modal-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black">Reservation Details</h3>
                <span id="modalStatusBadge" class="status-badge"></span>
            </div>
            <div id="modalBody" class="bg-slate-50 rounded-3xl p-5 border border-slate-100 mb-5 space-y-1"></div>
            
            <!-- Show claimed info if applicable -->
            <div id="claimedInfo" class="hidden bg-purple-50 border border-purple-200 rounded-2xl p-4 mb-5 text-center">
                <i class="fa-solid fa-check-double text-purple-500 mb-2 text-xl"></i>
                <p class="text-xs text-purple-700 font-medium">
                    This ticket has already been claimed and used.
                </p>
                <?php if (!empty($res['claimed_at'])): ?>
                    <p class="text-[10px] text-purple-500 mt-1">
                        Claimed on: <?= date('F j, Y g:i A', strtotime($res['claimed_at'])) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- QR Code Section - Only show for approved and not claimed -->
            <div id="qrSection" class="bg-white border-2 border-dashed border-green-100 rounded-3xl p-6 flex flex-col items-center mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">E-Ticket / Access QR</p>
                <canvas id="qrCanvas" class="mx-auto rounded-xl"></canvas>
                <p id="qrCodeText" class="text-xs text-slate-400 font-mono mt-3 text-center break-all px-2"></p>
                <button id="downloadBtn" onclick="downloadTicket()" class="mt-4 flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                    <i class="fa-solid fa-download"></i> Download E-Ticket
                </button>
            </div>

            <!-- Claimed Message -->
            <div id="claimedMessage" class="hidden bg-purple-50 border-2 border-dashed border-purple-200 rounded-3xl p-6 flex flex-col items-center mb-5">
                <i class="fa-solid fa-check-double text-4xl text-purple-500 mb-3"></i>
                <p class="font-bold text-purple-700">Ticket Already Used</p>
                <p class="text-xs text-purple-500 text-center mt-1">This reservation has already been claimed and cannot be used again.</p>
            </div>

            <button onclick="closeModal('detailsModal')" class="w-full py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">Close</button>
        </div>
    </div>

    <!-- Cancel Confirm Modal -->
    <div id="cancelModal" class="modal" onclick="handleModalBackdrop(event, 'cancelModal')">
        <div class="modal-card" style="max-width:380px;">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-black">Cancel Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p class="text-slate-600 text-sm mt-3 font-bold" id="cancelConfirmResource"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeModal('cancelModal')" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">Keep it</button>
                <button id="confirmCancelBtn" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>

    <form id="cancelForm" method="POST" action="" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="cancelId">
    </form>

    <script>
        const reservationsData = <?= json_encode($reservations ?? []) ?>;
        let cancelTargetId = null;

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

            const resourceName = res.resource_name || ('Resource #' + res.resource_id);
            const code = res.e_ticket_code || `SK-${res.id}-${res.reservation_date}`;
            const pcNumber = res.pc_number || '—';
            const isClaimed = res.claimed == 1;
            
            // Format date
            const date = new Date(res.reservation_date);
            const formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            // Format time
            const startTime = new Date('1970-01-01T' + res.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            const endTime = new Date('1970-01-01T' + res.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

            const badge = document.getElementById('modalStatusBadge');
            let displayStatus = res.status;
            if (isClaimed) {
                displayStatus = 'claimed';
            }
            badge.textContent = displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1);
            badge.className = `status-badge status-${displayStatus.toLowerCase()}`;

            // Build details HTML
            let detailsHtml = `
                <div class="detail-row"><span class="detail-label">Reservation #</span><span class="detail-value">#${res.id}</span></div>
                <div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value">${resourceName}</span></div>
                <div class="detail-row"><span class="detail-label">PC Number</span><span class="detail-value">${pcNumber}</span></div>
                <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value">${formattedDate}</span></div>
                <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">${startTime} – ${endTime}</span></div>
                <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value">${res.purpose || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">E-Ticket</span><span class="detail-value font-mono text-xs">${code}</span></div>
            `;

            // Add claimed info if applicable
            if (isClaimed && res.claimed_at) {
                const claimedDate = new Date(res.claimed_at).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                detailsHtml += `
                    <div class="detail-row"><span class="detail-label">Claimed On</span><span class="detail-value">${claimedDate}</span></div>
                `;
            }

            document.getElementById('modalBody').innerHTML = detailsHtml;

            // Handle QR code and claimed sections
            const qrSection = document.getElementById('qrSection');
            const claimedMessage = document.getElementById('claimedMessage');
            const downloadBtn = document.getElementById('downloadBtn');

            if (isClaimed) {
                // Hide QR section, show claimed message
                qrSection.style.display = 'none';
                claimedMessage.classList.remove('hidden');
            } else {
                // Show QR section, hide claimed message
                qrSection.style.display = 'flex';
                claimedMessage.classList.add('hidden');
                
                // Generate QR code
                QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
                    width: 180,
                    margin: 1,
                    color: { dark: '#1e293b', light: '#ffffff' }
                }, function(error) {
                    if (error) console.error(error);
                });
                document.getElementById('qrCodeText').textContent = code;
                
                // Enable/disable download based on status
                if (res.status.toLowerCase() === 'approved' && !isClaimed) {
                    downloadBtn.disabled = false;
                    downloadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    downloadBtn.disabled = true;
                    downloadBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

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

        // Cancel functionality
        function handleCancel(id) {
            cancelTargetId = id;
            const res = reservationsData.find(r => r.id == id);
            const resourceName = res ? (res.resource_name || 'Resource') : '';
            document.getElementById('cancelConfirmResource').textContent = resourceName ? `"${resourceName}"` : '';
            
            // Set the form action dynamically
            const form = document.getElementById('cancelForm');
            form.action = '<?= base_url("reservation/cancel") ?>/' + id;
            
            openModal('cancelModal');
        }

        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if (!cancelTargetId) return;
            
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Canceling…';
            
            document.getElementById('cancelId').value = cancelTargetId;
            document.getElementById('cancelForm').submit();
        });

        // Modal helpers
        function openModal(id) { 
            document.getElementById(id).classList.add('show'); 
            document.body.style.overflow = 'hidden'; 
        }
        
        function closeModal(id) { 
            document.getElementById(id).classList.remove('show'); 
            document.body.style.overflow = ''; 
            
            // Reset cancel button if modal was closed
            if (id === 'cancelModal') {
                const btn = document.getElementById('confirmCancelBtn');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Yes, Cancel';
            }
        }
        
        function handleModalBackdrop(e, id) { 
            if (e.target === document.getElementById(id)) closeModal(id); 
        }
        
        document.addEventListener('keydown', e => { 
            if (e.key === 'Escape') { 
                closeModal('detailsModal'); 
                closeModal('cancelModal'); 
            } 
        });

        // Initialize filter count
        filterTable();
    </script>
</body>
</html>