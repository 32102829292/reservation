<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage Reservations</title>

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

        .btn-decline {
            background-color: #ef4444;
            color: white;
        }

        .btn-decline:hover {
            background-color: #dc2626;
        }

        .status-pending {
            background-color: #fbbf24;
            color: #78350f;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-approved {
            background-color: #86efac;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-declined {
            background-color: #fca5a5;
            color: #7f1d1d;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
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

        .search-box select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
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

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
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

        .badge-info {
            display: inline-block;
            background-color: #dbeafe;
            color: #0c4a6e;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'manage-reservations'; ?>

    <div class="flex flex-1 flex-col lg:flex-row">

        <aside class="hidden lg:flex flex-col w-64 bg-blue-600 text-white shadow-xl rounded-tr-3xl rounded-br-3xl p-6">
            <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="/admin/new-reservation" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-plus"></i> New Reservation
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
                <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">Manage Reservations</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow">
                <div class="mb-4">
                    <h3 class="font-semibold text-blue-900">Reservations</h3>
                </div>

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by ID, User, or Resource...">
                    <select id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="canceled">Declined</option>
                    </select>
                </div>

                <div class="mb-3 text-sm text-gray-600">
                    <span>Total: <strong id="totalCount">0</strong></span> | 
                    <span>Showing: <strong id="visibleCount">0</strong></span>
                </div>

                <div class="overflow-x-auto">
                    <table id="reservationsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Resource ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($reservations) && is_array($reservations)): ?>
                                <?php foreach ($reservations as $res): ?>
                                    <tr class="reservation-row" data-status="<?= $res['status'] ?? 'pending' ?>" data-id="<?= $res['id'] ?>">
                                        <td>#<?= $res['id'] ?? 'N/A' ?></td>
                                        <td><?= $res['user_id'] ?? 'N/A' ?></td>
                                        <td><?= $res['resource_id'] ?? 'N/A' ?></td>
                                        <td><?= isset($res['reservation_date']) ? date('M d, Y', strtotime($res['reservation_date'])) : 'N/A' ?></td>
                                        <td><?= ($res['start_time'] ?? 'N/A') . ' - ' . ($res['end_time'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php
                                            $status = $res['status'] ?? 'pending';
                                            if ($status === 'pending') {
                                                echo '<span class="status-pending">Pending</span>';
                                            } elseif ($status === 'approved') {
                                                echo '<span class="status-approved">Approved</span>';
                                            } else {
                                                echo '<span class="status-declined">Declined</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-approve" onclick="viewDetails(<?= $res['id'] ?>, '<?= $res['status'] ?? 'pending' ?>')">Details</button>
                                            <?php if ($status === 'pending'): ?>
                                                <button type="button" class="btn btn-approve" onclick="approveReservation(<?= $res['id'] ?>)">Approve</button>
                                                <button type="button" class="btn btn-decline" onclick="declineReservation(<?= $res['id'] ?>)">Decline</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 py-4">No reservations found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
            <span class="close" onclick="closeModal('detailsModal')">&times;</span>
            <div class="modal-header">Reservation Details</div>
            <div class="modal-body" id="modalBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('confirmModal')">&times;</span>
            <div class="modal-header" id="confirmTitle">Confirm Action</div>
            <div class="modal-body" id="confirmMessage">
                Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('confirmModal')">Cancel</button>
                <button type="button" class="btn btn-approve" id="confirmBtn" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        const reservationsData = <?= json_encode($reservations ?? []) ?>;
        let pendingAction = null;
        let pendingId = null;

        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.reservation-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const status = row.dataset.status;
                const matchesSearch = text.includes(searchInput);
                const matchesStatus = !statusFilter || status === statusFilter;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('totalCount').textContent = rows.length;
            document.getElementById('visibleCount').textContent = visibleCount;
        }

        function viewDetails(id, status) {
            const reservation = reservationsData.find(r => r.id == id);
            if (!reservation) return;

            const detailsHTML = `
                <div>
                    <p><strong>Reservation ID:</strong> #${reservation.id}</p>
                    <p><strong>User ID:</strong> ${reservation.user_id || 'N/A'}</p>
                    <p><strong>Resource ID:</strong> ${reservation.resource_id || 'N/A'}</p>
                    <p><strong>Date:</strong> ${new Date(reservation.reservation_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'})}</p>
                    <p><strong>Time:</strong> ${reservation.start_time || 'N/A'} - ${reservation.end_time || 'N/A'}</p>
                    <p><strong>Status:</strong> <span class="status-${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></p>
                    <p><strong>Created:</strong> ${new Date(reservation.created_at).toLocaleString()}</p>
                </div>
            `;
            document.getElementById('modalBody').innerHTML = detailsHTML;
            document.getElementById('detailsModal').classList.add('show');
        }

        function approveReservation(id) {
            pendingAction = 'approve';
            pendingId = id;
            document.getElementById('confirmTitle').textContent = 'Approve Reservation';
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to approve this reservation?';
            document.getElementById('confirmBtn').className = 'btn btn-approve';
            document.getElementById('confirmModal').classList.add('show');
        }

        function declineReservation(id) {
            pendingAction = 'decline';
            pendingId = id;
            document.getElementById('confirmTitle').textContent = 'Decline Reservation';
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to decline this reservation?';
            document.getElementById('confirmBtn').className = 'btn btn-decline';
            document.getElementById('confirmModal').classList.add('show');
        }

        function confirmAction() {
            if (!pendingAction || !pendingId) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="id" value="${pendingId}">`;
            form.action = pendingAction === 'approve' ? '/admin/approve' : '/admin/decline';
            document.body.appendChild(form);
            form.submit();
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        window.onclick = function(event) {
            const modal = event.target;
            if (modal.classList.contains('modal')) {
                modal.classList.remove('show');
            }
        }

        filterTable();
    </script>

</body>
</html>
