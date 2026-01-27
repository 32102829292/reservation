<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Activity Logs</title>

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

        .activity-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .activity-create {
            background-color: #dcfce7;
            color: #166534;
        }

        .activity-update {
            background-color: #fef3c7;
            color: #92400e;
        }

        .activity-delete {
            background-color: #fee2e2;
            color: #7f1d1d;
        }

        .activity-approve {
            background-color: #dbeafe;
            color: #0c4a6e;
        }

        .activity-decline {
            background-color: #fce7f3;
            color: #831843;
        }

        .activity-view {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .search-box {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-box input,
        .search-box select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .search-box input {
            flex: 1;
            min-width: 200px;
        }

        .stats-grid {
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
            color: #4b5563;
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

            .search-box {
                flex-direction: column;
            }

            .search-box input,
            .search-box select {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'activity-logs'; ?>

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
                <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">Activity Logs</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="count" id="totalCount">0</div>
                    <div class="label">Total Activities</div>
                </div>
                <div class="stat-card">
                    <div class="count" id="createCount">0</div>
                    <div class="label">Created</div>
                </div>
                <div class="stat-card">
                    <div class="count" id="updateCount">0</div>
                    <div class="label">Updated</div>
                </div>
                <div class="stat-card">
                    <div class="count" id="deleteCount">0</div>
                    <div class="label">Deleted</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by user, action, or resource...">
                    <select id="actionFilter">
                        <option value="">All Actions</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                        <option value="approve">Approve</option>
                        <option value="decline">Decline</option>
                        <option value="view">View</option>
                    </select>
                    <select id="dateFilter">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>

                <div class="mb-3 text-sm text-gray-600">
                    <span>Total: <strong id="visibleCount">0</strong></span>
                </div>

                <div class="overflow-x-auto">
                    <table id="activityTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Resource</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample data - Replace with backend data -->
                            <tr class="activity-row" data-action="create" data-date="2026-01-22" onclick="viewDetails(this)">
                                <td>Jan 22, 2026 10:30 AM</td>
                                <td>Admin User</td>
                                <td><span class="activity-badge activity-create">Create</span></td>
                                <td>Reservation #001</td>
                                <td>New reservation created</td>
                            </tr>
                            <tr class="activity-row" data-action="approve" data-date="2026-01-22" onclick="viewDetails(this)">
                                <td>Jan 22, 2026 11:15 AM</td>
                                <td>Admin User</td>
                                <td><span class="activity-badge activity-approve">Approve</span></td>
                                <td>Reservation #002</td>
                                <td>Reservation approved</td>
                            </tr>
                            <tr class="activity-row" data-action="update" data-date="2026-01-22" onclick="viewDetails(this)">
                                <td>Jan 22, 2026 02:45 PM</td>
                                <td>Admin User</td>
                                <td><span class="activity-badge activity-update">Update</span></td>
                                <td>User Profile</td>
                                <td>Profile information updated</td>
                            </tr>
                            <tr class="activity-row" data-action="decline" data-date="2026-01-21" onclick="viewDetails(this)">
                                <td>Jan 21, 2026 09:20 AM</td>
                                <td>Admin User</td>
                                <td><span class="activity-badge activity-decline">Decline</span></td>
                                <td>Reservation #003</td>
                                <td>Reservation declined</td>
                            </tr>
                            <tr class="activity-row" data-action="view" data-date="2026-01-21" onclick="viewDetails(this)">
                                <td>Jan 21, 2026 03:00 PM</td>
                                <td>SK Officer</td>
                                <td><span class="activity-badge activity-view">View</span></td>
                                <td>Reservations List</td>
                                <td>Viewed all reservations</td>
                            </tr>
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
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-header">Activity Details</div>
            <div class="modal-body" id="modalBody">
                <!-- Details will be populated here -->
            </div>
        </div>
    </div>

    <script>
        const activities = <?= json_encode($activities ?? []) ?>;

        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('actionFilter').addEventListener('change', filterTable);
        document.getElementById('dateFilter').addEventListener('change', filterTable);

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const actionFilter = document.getElementById('actionFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const rows = document.querySelectorAll('.activity-row');
            let visibleCount = 0;
            let createCount = 0;
            let updateCount = 0;
            let deleteCount = 0;

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const action = row.dataset.action;
                const dateStr = row.dataset.date;
                const rowDate = new Date(dateStr);

                const matchesSearch = text.includes(searchInput);
                const matchesAction = !actionFilter || action === actionFilter;
                
                let matchesDate = true;
                if (dateFilter === 'today') {
                    matchesDate = rowDate.toDateString() === today.toDateString();
                } else if (dateFilter === 'week') {
                    const weekAgo = new Date(today);
                    weekAgo.setDate(weekAgo.getDate() - 7);
                    matchesDate = rowDate >= weekAgo;
                } else if (dateFilter === 'month') {
                    const monthAgo = new Date(today);
                    monthAgo.setDate(monthAgo.getDate() - 30);
                    matchesDate = rowDate >= monthAgo;
                }

                if (matchesSearch && matchesAction && matchesDate) {
                    row.style.display = '';
                    visibleCount++;
                    
                    if (action === 'create') createCount++;
                    if (action === 'update') updateCount++;
                    if (action === 'delete') deleteCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('totalCount').textContent = rows.length;
            document.getElementById('visibleCount').textContent = visibleCount;
            document.getElementById('createCount').textContent = createCount;
            document.getElementById('updateCount').textContent = updateCount;
            document.getElementById('deleteCount').textContent = deleteCount;
        }

        function viewDetails(row) {
            const cells = row.cells;
            const modalBody = document.getElementById('modalBody');
            
            modalBody.innerHTML = `
                <div>
                    <p><strong>Date & Time:</strong> ${cells[0].textContent}</p>
                    <p><strong>User:</strong> ${cells[1].textContent}</p>
                    <p><strong>Action:</strong> ${cells[2].textContent}</p>
                    <p><strong>Resource:</strong> ${cells[3].textContent}</p>
                    <p><strong>Description:</strong> ${cells[4].textContent}</p>
                </div>
            `;
            
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

        // Initialize on page load
        filterTable();
    </script>

</body>

</html>
