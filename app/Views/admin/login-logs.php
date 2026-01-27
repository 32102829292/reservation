<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Login Logs</title>

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

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .role-admin {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .role-sk {
            background-color: #dcfce7;
            color: #166534;
        }

        .role-user {
            background-color: #fce7f3;
            color: #831843;
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
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'login-logs'; ?>

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
                <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">Login Logs</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by name or email...">
                    <select id="monthFilter">
                        <option value="">All Months</option>
                        <?php
                        for ($i = 0; $i < 12; $i++) {
                            $date = new DateTime('first day of this month');
                            $date->modify("-$i months");
                            $value = $date->format('Y-m');
                            echo "<option value=\"$value\">" . $date->format('M Y') . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table id="logsTable">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Login Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs) && is_array($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr class="log-row" data-month="<?= isset($log['login_time']) ? date('Y-m', strtotime($log['login_time'])) : '' ?>">
                                        <td><?= $log['name'] ?? 'N/A' ?></td>
                                        <td><?= $log['email'] ?? 'N/A' ?></td>
                                        <td>
                                            <?php
                                            $role = $log['role'] ?? 'user';
                                            if ($role === 'admin') {
                                                echo '<span class="role-badge role-admin">Admin</span>';
                                            } elseif ($role === 'sk') {
                                                echo '<span class="role-badge role-sk">SK Officer</span>';
                                            } else {
                                                echo '<span class="role-badge role-user">User</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= isset($log['login_time']) ? date('M d, Y H:i:s', strtotime($log['login_time'])) : 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500 py-4">No login logs found</td>
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
            <a href="/logout" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 hover:bg-blue-500">
                <i class="fa-solid fa-sign-out-alt text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Logout</span>
            </a>
        </div>
    </nav>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('monthFilter').addEventListener('change', filterTable);

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const monthFilter = document.getElementById('monthFilter').value;
            const rows = document.querySelectorAll('.log-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const month = row.dataset.month;
                const matchesSearch = text.includes(searchInput);
                const matchesMonth = !monthFilter || month === monthFilter;

                row.style.display = (matchesSearch && matchesMonth) ? '' : 'none';
            });
        }
    </script>

    <script>
        // Hamburger menu functionality
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const mobileDrawer = document.getElementById('mobileDrawer');
        const drawerBackdrop = document.getElementById('drawerBackdrop');
        const drawerContent = document.getElementById('drawerContent');

        hamburgerBtn.addEventListener('click', () => {
            mobileDrawer.classList.remove('hidden');
            setTimeout(() => {
                drawerContent.classList.remove('transform', '-translate-x-full');
            }, 10);
        });

        drawerBackdrop.addEventListener('click', closeDrawer);
        drawerContent.addEventListener('click', (e) => e.stopPropagation());

        function closeDrawer() {
            drawerContent.classList.add('transform', '-translate-x-full');
            setTimeout(() => {
                mobileDrawer.classList.add('hidden');
            }, 300);
        }
    </script>

</body>

</html>
