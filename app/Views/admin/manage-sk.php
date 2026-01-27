<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage SK Accounts</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff6ff;
            overflow-x: hidden;
        }

        main {
            padding-bottom: env(safe-area-inset-bottom, 5.5rem);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 9999px;
            background-color: #3b82f6;
            color: white;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: #2563eb;
        }

        .tab-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #6b7280;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #1f2937;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #fbbf24;
            color: #78350f;
        }

        .badge-approved {
            background-color: #86efac;
            color: #166534;
        }

        .badge-rejected {
            background-color: #fca5a5;
            color: #7f1d1d;
        }

        .btn {
            padding: 0.4rem 0.8rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            margin-right: 0.25rem;
        }

        .btn-approve {
            background-color: #10b981;
            color: white;
        }

        .btn-approve:hover {
            background-color: #059669;
        }

        .btn-reject {
            background-color: #ef4444;
            color: white;
        }

        .btn-reject:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .search-box {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card .count {
            font-size: 1.75rem;
            font-weight: 700;
            color: #3b82f6;
        }

        .stat-card .label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
        }

        .modal-header {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .modal-body {
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .modal-footer {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 2rem;
            cursor: pointer;
            font-weight: bold;
        }

        .close:hover {
            color: #000;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.875rem;
            }

            th, td {
                padding: 0.5rem;
            }

            .btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }

            .tab-buttons {
                gap: 0.5rem;
            }

            .tab-btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .stats {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 0.75rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-card .count {
                font-size: 1.5rem;
            }

            .stat-card .label {
                font-size: 0.75rem;
            }

            .search-box {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .search-box input {
                width: 100%;
            }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'manage-sk'; ?>

    <div class="flex flex-1 flex-col lg:flex-row">

        <aside class="hidden lg:flex flex-col w-64 bg-blue-600 text-white shadow-xl rounded-tr-3xl rounded-br-3xl p-6">
            <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="/admin/new-reservation" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-plus text-lg"></i>New Reservation</span>
                </a>
                <a href="/admin/manage-reservations" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'manage-reservations') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-calendar"></i> Manage Reservations
                </a>
                <a href="/admin/manage-sk" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'manage-sk') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-user-shield"></i> Manage SK Accounts
                </a>
                <a href="/admin/login-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'login-logs') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-clock"></i> Login Logs
                </a>
                <a href="/admin/scanner" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'scanner') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-qrcode"></i> Scanner
                </a>
                <a href="/admin/activity-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'activity-logs') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-list"></i> Activity Logs
                </a>
                <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'profile') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-regular fa-user"></i> Profile
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-4 lg:p-6 overflow-auto">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">Manage SK Accounts</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="count" id="totalCount">0</div>
                    <div class="label">Total SK Accounts</div>
                </div>
                <div class="stat-card">
                    <div class="count" id="pendingCount">0</div>
                    <div class="label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="count" id="approvedCount">0</div>
                    <div class="label">Approved</div>
                </div>
                <div class="stat-card">
                    <div class="count" id="rejectedCount">0</div>
                    <div class="label">Rejected</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow">
                <div class="tab-buttons">
                    <button class="tab-btn active" onclick="switchTab('pending')">
                        <i class="fa-solid fa-hourglass"></i> Pending
                    </button>
                    <button class="tab-btn" onclick="switchTab('approved')">
                        <i class="fa-solid fa-check"></i> Approved
                    </button>
                    <button class="tab-btn" onclick="switchTab('rejected')">
                        <i class="fa-solid fa-times"></i> Rejected
                    </button>
                </div>

                <!-- Pending Tab -->
                <div id="pending" class="tab-content active">
                    <div class="search-box">
                        <input type="text" id="pendingSearch" placeholder="Search by name or email..." onkeyup="filterPending()">
                    </div>
                    <div class="overflow-x-auto">
                        <table id="pendingTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pending) && is_array($pending)): ?>
                                    <?php foreach ($pending as $sk): ?>
                                        <tr class="sk-row" data-status="pending">
                                            <td>#<?= $sk['id'] ?></td>
                                            <td><?= $sk['full_name'] ?? $sk['name'] ?? 'N/A' ?></td>
                                            <td><?= $sk['email'] ?></td>
                                            <td><?= date('M d, Y', strtotime($sk['created_at'])) ?></td>
                                            <td><span class="badge badge-pending">Pending</span></td>
                                            <td>
                                                <button type="button" class="btn btn-secondary" onclick="viewDetails(<?= $sk['id'] ?>)">View</button>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?= $sk['id'] ?>">
                                                    <button type="submit" class="btn btn-approve" formaction="/admin/approve-sk">Approve</button>
                                                    <button type="submit" class="btn btn-reject" formaction="/admin/reject-sk">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-4">No pending SK accounts</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Approved Tab -->
                <div id="approved" class="tab-content">
                    <div class="search-box">
                        <input type="text" id="approvedSearch" placeholder="Search by name or email..." onkeyup="filterApproved()">
                    </div>
                    <div class="overflow-x-auto">
                        <table id="approvedTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Approved Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($approved) && is_array($approved)): ?>
                                    <?php foreach ($approved as $sk): ?>
                                        <tr class="sk-row" data-status="approved">
                                            <td>#<?= $sk['id'] ?></td>
                                            <td><?= $sk['full_name'] ?? $sk['name'] ?? 'N/A' ?></td>
                                            <td><?= $sk['email'] ?></td>
                                            <td><?= $sk['is_approved'] ? date('M d, Y', strtotime($sk['updated_at'] ?? $sk['created_at'])) : 'N/A' ?></td>
                                            <td><span class="badge badge-approved">Approved</span></td>
                                            <td>
                                                <button type="button" class="btn btn-secondary" onclick="viewDetails(<?= $sk['id'] ?>)">View</button>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?= $sk['id'] ?>">
                                                    <button type="submit" class="btn btn-reject" formaction="/admin/reject-sk">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-4">No approved SK accounts</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Rejected Tab -->
                <div id="rejected" class="tab-content">
                    <div class="search-box">
                        <input type="text" id="rejectedSearch" placeholder="Search by name or email..." onkeyup="filterRejected()">
                    </div>
                    <div class="overflow-x-auto">
                        <table id="rejectedTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Rejected Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rejected) && is_array($rejected)): ?>
                                    <?php foreach ($rejected as $sk): ?>
                                        <tr class="sk-row" data-status="rejected">
                                            <td>#<?= $sk['id'] ?></td>
                                            <td><?= $sk['full_name'] ?? $sk['name'] ?? 'N/A' ?></td>
                                            <td><?= $sk['email'] ?></td>
                                            <td><?= date('M d, Y', strtotime($sk['updated_at'] ?? $sk['created_at'])) ?></td>
                                            <td><span class="badge badge-rejected">Rejected</span></td>
                                            <td>
                                                <button type="button" class="btn btn-secondary" onclick="viewDetails(<?= $sk['id'] ?>)">View</button>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?= $sk['id'] ?>">
                                                    <button type="submit" class="btn btn-approve" formaction="/admin/approve-sk">Approve</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-4">No rejected SK accounts</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <nav class="fixed bottom-0 left-0 right-0 bg-blue-600 text-white shadow-xl lg:hidden z-50">
        <div class="flex overflow-x-auto gap-1 p-2">
            <a href="/admin/dashboard" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Dashboard</span>
            </a>
            <a href="/admin/new-reservation" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-plus text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">New Reservation</span>
            </a>
            <a href="/admin/manage-reservations" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'manage-reservations') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-calendar text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Reservations</span>
            </a>
            <a href="/admin/manage-sk" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'manage-sk') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-user-shield text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Manage SK</span>
            </a>
            <a href="/admin/login-logs" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'login-logs') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-clock text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Login Logs</span>
            </a>
            <a href="/admin/scanner" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'scanner') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-qrcode text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Scanner</span>
            </a>
            <a href="/admin/activity-logs" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'activity-logs') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-list text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Activity Logs</span>
            </a>
            <a href="/admin/profile" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'profile') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Profile</span>
            </a>
        </div>
    </nav>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-header">SK Account Details</div>
            <div class="modal-body" id="modalBody">
                <!-- Details will be populated here -->
            </div>
        </div>
    </div>

    <script>
        const skData = <?= json_encode(array_merge($pending ?? [], $approved ?? [], $rejected ?? [])) ?>;

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');

            updateStats();
        }

        function updateStats() {
            const pending = document.querySelectorAll('#pendingTable tbody tr').length - 1;
            const approved = document.querySelectorAll('#approvedTable tbody tr').length - 1;
            const rejected = document.querySelectorAll('#rejectedTable tbody tr').length - 1;
            const total = pending + approved + rejected;

            document.getElementById('totalCount').textContent = total;
            document.getElementById('pendingCount').textContent = Math.max(0, pending);
            document.getElementById('approvedCount').textContent = Math.max(0, approved);
            document.getElementById('rejectedCount').textContent = Math.max(0, rejected);
        }

        function filterPending() {
            filterTable('pendingTable', 'pendingSearch');
        }

        function filterApproved() {
            filterTable('approvedTable', 'approvedSearch');
        }

        function filterRejected() {
            filterTable('rejectedTable', 'rejectedSearch');
        }

        function filterTable(tableId, searchId) {
            const input = document.getElementById(searchId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                rows[i].style.display = text.includes(filter) ? "" : "none";
            }
        }

        function viewDetails(id) {
            const sk = skData.find(s => s.id == id);
            if (!sk) return;

            const detailsHTML = `
                <div>
                    <p><strong>ID:</strong> #${sk.id}</p>
                    <p><strong>Full Name:</strong> ${sk.full_name || sk.name || 'N/A'}</p>
                    <p><strong>Email:</strong> ${sk.email}</p>
                    <p><strong>Phone:</strong> ${sk.phone || 'N/A'}</p>
                    <p><strong>Applied Date:</strong> ${new Date(sk.created_at).toLocaleDateString()}</p>
                    <p><strong>Status:</strong> ${sk.is_approved ? '<span class="badge badge-approved">Approved</span>' : '<span class="badge badge-pending">Pending</span>'}</p>
                    <p><strong>Email Verified:</strong> ${sk.is_verified ? 'Yes' : 'No'}</p>
                </div>
            `;
            document.getElementById('modalBody').innerHTML = detailsHTML;
            document.getElementById('detailsModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.remove('show');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        }

        // Initialize stats on page load
        updateStats();
    </script>

</body>

</html>
