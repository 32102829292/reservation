<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage SK Accounts | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        /* ── Sidebar (identical to dashboard) ── */
        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
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

        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }

        /* ── Mobile nav pill (identical to dashboard) ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(30,41,59,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container {
            display: flex; gap: 4px; overflow-x: auto;
            scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
        }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Content ── */
        .content-card { background: white; border-radius: 32px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { background-color: #f8fafc; font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.1em; color: #64748b; padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; }
        td { padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; font-weight: 500; }
        tr:last-child td { border-bottom: none; }

        .status-badge { padding: 0.4rem 0.8rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-pending  { background-color: #fef3c7; color: #92400e; }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-rejected { background-color: #fee2e2; color: #991b1b; }

        .btn-action { padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; font-size: 0.8rem; transition: all 0.2s; cursor: pointer; border: none; font-family: 'Plus Jakarta Sans', sans-serif; }

        .tab-btn { padding: 0.75rem 1.5rem; font-weight: 700; font-size: 0.875rem; color: #64748b; border-bottom: 3px solid transparent; transition: all 0.2s; background: none; border-left: none; border-right: none; border-top: none; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
        .tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }

        /* ── Modal ── */
        .modal {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.65); backdrop-filter: blur(6px);
            z-index: 200; padding: 1.5rem; overflow-y: auto;
        }
        .modal.show { display: flex; align-items: center; justify-content: center; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-card {
            background: white; border-radius: 32px; width: 100%;
            max-width: 500px; padding: 2.5rem; margin: auto;
            animation: slideUp 0.2s ease; max-height: 90vh; overflow-y: auto;
        }
        @keyframes slideUp { from { transform: translateY(16px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        input {
            background: #fcfdfe; border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 0.75rem 1.25rem; font-size: 0.9rem; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;
        }
        input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.08); }

        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }
    </style>
</head>
<body class="flex">

    <!-- ── Sidebar ── -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
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
                foreach ($navItems as $item):
                    $active = (isset($page) && $page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
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

    <!-- ── Mobile Nav (matching dashboard exactly) ── -->
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
        </div>
    </nav>

    <!-- ── Main ── -->
    <main class="flex-1 p-6 lg:p-12 pb-32">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">SK Accounts</h2>
                <p class="text-slate-500 font-medium">Manage Sangguniang Kabataan registrations.</p>
            </div>
        </header>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="content-card p-6 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total</p>
                <p class="text-2xl font-black text-blue-600" id="totalCount">0</p>
            </div>
            <div class="content-card p-6 text-center border-l-4 border-l-amber-400">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending</p>
                <p class="text-2xl font-black text-amber-600" id="pendingCount">0</p>
            </div>
            <div class="content-card p-6 text-center border-l-4 border-l-emerald-400">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Approved</p>
                <p class="text-2xl font-black text-emerald-600" id="approvedCount">0</p>
            </div>
            <div class="content-card p-6 text-center border-l-4 border-l-rose-400">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rejected</p>
                <p class="text-2xl font-black text-rose-600" id="rejectedCount">0</p>
            </div>
        </div>

        <div class="content-card overflow-hidden">
            <!-- Tabs -->
            <div class="flex border-b border-slate-100 px-6">
                <button class="tab-btn active" onclick="switchTab('pending', this)">Pending</button>
                <button class="tab-btn" onclick="switchTab('approved', this)">Approved</button>
                <button class="tab-btn" onclick="switchTab('rejected', this)">Rejected</button>
            </div>

            <!-- Search -->
            <div class="p-6 bg-slate-50/50 border-b border-slate-100">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="globalSearch" class="pl-10" placeholder="Search by name or email..." onkeyup="filterAllTables()">
                </div>
            </div>

            <!-- Pending Tab -->
            <div id="pending" class="tab-content overflow-x-auto">
                <table id="pendingTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pending)): foreach ($pending as $sk): ?>
                            <tr class="sk-row hover:bg-slate-50 transition-colors" data-status="pending">
                                <td><span class="text-slate-400 font-bold">#</span><?= $sk['id'] ?></td>
                                <td class="font-bold text-slate-800"><?= htmlspecialchars($sk['full_name'] ?? $sk['name']) ?></td>
                                <td class="text-slate-500"><?= htmlspecialchars($sk['email']) ?></td>
                                <td class="text-slate-500"><?= date('M d, Y', strtotime($sk['created_at'])) ?></td>
                                <td class="whitespace-nowrap">
                                    <button class="btn-action bg-slate-100 text-slate-600 hover:bg-slate-200" onclick="viewDetails(<?= $sk['id'] ?>)">
                                        <i class="fa-solid fa-eye mr-1"></i>Details
                                    </button>
                                    <form method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $sk['id'] ?>">
                                        <button type="submit" class="btn-action bg-blue-600 text-white hover:bg-blue-700 ml-1" formaction="/admin/approve-sk">Approve</button>
                                        <button type="submit" class="btn-action bg-red-50 text-red-600 hover:bg-red-100 ml-1" formaction="/admin/reject-sk">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-slate-400 py-12 font-medium">No pending accounts</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Approved Tab -->
            <div id="approved" class="tab-content hidden overflow-x-auto">
                <table id="approvedTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($approved)): foreach ($approved as $sk): ?>
                            <tr class="sk-row hover:bg-slate-50 transition-colors" data-status="approved">
                                <td><span class="text-slate-400 font-bold">#</span><?= $sk['id'] ?></td>
                                <td class="font-bold text-slate-800"><?= htmlspecialchars($sk['full_name'] ?? $sk['name']) ?></td>
                                <td class="text-slate-500"><?= htmlspecialchars($sk['email']) ?></td>
                                <td><span class="status-badge badge-approved">Approved</span></td>
                                <td>
                                    <button class="btn-action bg-slate-100 text-slate-600 hover:bg-slate-200" onclick="viewDetails(<?= $sk['id'] ?>)">
                                        <i class="fa-solid fa-eye mr-1"></i>Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-slate-400 py-12 font-medium">No approved accounts</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Rejected Tab -->
            <div id="rejected" class="tab-content hidden overflow-x-auto">
                <table id="rejectedTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rejected)): foreach ($rejected as $sk): ?>
                            <tr class="sk-row hover:bg-slate-50 transition-colors" data-status="rejected">
                                <td><span class="text-slate-400 font-bold">#</span><?= $sk['id'] ?></td>
                                <td class="font-bold text-slate-800"><?= htmlspecialchars($sk['full_name'] ?? $sk['name']) ?></td>
                                <td class="text-slate-500"><?= htmlspecialchars($sk['email']) ?></td>
                                <td><span class="status-badge badge-rejected">Rejected</span></td>
                                <td>
                                    <button class="btn-action bg-slate-100 text-slate-600 hover:bg-slate-200" onclick="viewDetails(<?= $sk['id'] ?>)">
                                        <i class="fa-solid fa-eye mr-1"></i>Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center text-slate-400 py-12 font-medium">No rejected accounts</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- ── Details Modal ── -->
    <div id="detailsModal" class="modal" onclick="handleBackdrop(event)">
        <div class="modal-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-slate-900">Account Info</h3>
                <button onclick="closeModal()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="modalBody" class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-5"></div>
            <button onclick="closeModal()" class="w-full py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition">
                Close
            </button>
        </div>
    </div>

    <script>
        const skData = <?= json_encode(array_merge($pending ?? [], $approved ?? [], $rejected ?? [])) ?>;

        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.remove('hidden');
            btn.classList.add('active');
        }

        function filterAllTables() {
            const query = document.getElementById('globalSearch').value.toLowerCase();
            document.querySelectorAll('.sk-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
            });
        }

        function viewDetails(id) {
            const sk = skData.find(s => s.id == id);
            if (!sk) return;
            document.getElementById('modalBody').innerHTML = `
                <div class="detail-row"><span class="detail-label">Full Name</span><span class="detail-value">${sk.full_name || sk.name || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">${sk.email || '—'}</span></div>
                <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-value">${sk.phone || 'N/A'}</span></div>
                <div class="detail-row"><span class="detail-label">Applied</span><span class="detail-value">${new Date(sk.created_at).toLocaleDateString()}</span></div>
                <div class="detail-row"><span class="detail-label">Verified</span><span class="detail-value">${sk.is_verified ? 'Yes' : 'No'}</span></div>
            `;
            document.getElementById('detailsModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        function handleBackdrop(e) {
            if (e.target === document.getElementById('detailsModal')) closeModal();
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        function updateStats() {
            document.getElementById('totalCount').textContent = skData.length;
            document.getElementById('pendingCount').textContent  = document.querySelectorAll('#pendingTable  tbody tr:not(:has(td[colspan]))').length;
            document.getElementById('approvedCount').textContent = document.querySelectorAll('#approvedTable tbody tr:not(:has(td[colspan]))').length;
            document.getElementById('rejectedCount').textContent = document.querySelectorAll('#rejectedTable tbody tr:not(:has(td[colspan]))').length;
        }

        updateStats();
    </script>
</body>
</html>