<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff6ff;
            overflow-x: hidden;
        }

        main {
            padding-bottom: env(safe-area-inset-bottom, 5.5rem);
        }

        .card:hover {
            transform: translateY(-4px);
            transition: all 0.3s ease;
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

        th,
        td {
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

        @media (max-width: 768px) {
            table {
                font-size: 0.875rem;
            }

            th,
            td {
                padding: 0.5rem;
            }
        }

        #calendar {
            background: white;
        }

        .fc {
            font-family: 'Inter', sans-serif;
        }

        .fc-button-primary {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        .fc-button-primary:hover {
            background-color: #2563eb !important;
        }

        .fc-button-primary.fc-button-active {
            background-color: #1e40af !important;
        }

        .fc-daygrid-day.fc-day-other {
            background-color: #f9fafb;
        }

        .fc-daygrid-day:hover {
            background-color: #f3f4f6;
        }

        .fc-event {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'dashboard'; ?>

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
                <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">Admin Dashboard</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
                <div class="card bg-blue-500 text-white rounded-2xl p-5 shadow">
                    <p class="text-sm">Total Reservations</p>
                    <h3 class="text-3xl font-bold"><?= $total ?? 0 ?></h3>
                </div>
                <div class="card bg-yellow-500 text-white rounded-2xl p-5 shadow">
                    <p class="text-sm">Pending</p>
                    <h3 class="text-3xl font-bold"><?= $pending ?? 0 ?></h3>
                </div>
                <div class="card bg-green-500 text-white rounded-2xl p-5 shadow">
                    <p class="text-sm">Approved</p>
                    <h3 class="text-3xl font-bold"><?= $approved ?? 0 ?></h3>
                </div>
                <div class="card bg-red-500 text-white rounded-2xl p-5 shadow">
                    <p class="text-sm">Declined</p>
                    <h3 class="text-3xl font-bold"><?= $declined ?? 0 ?></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-2xl p-4 shadow">
                    <h3 class="font-semibold text-blue-900 mb-4">Reservation Calendar</h3>
                    <div id="calendar"></div>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow">
                    <h3 class="font-semibold text-blue-900 mb-4">Pending Reservations</h3>
                    <div class="space-y-3">
                        <?php 
                        $pending_res = array_filter($reservations ?? [], function($res) {
                            return isset($res['status']) && $res['status'] === 'pending';
                        });
                        ?>
                        <?php if (!empty($pending_res)): ?>
                            <?php foreach (array_slice($pending_res, 0, 5) as $event): ?>
                                <div class="border-l-4 border-yellow-500 pl-3 py-2">
                                    <p class="text-sm font-medium text-gray-900">#<?= $event['id'] ?? 'N/A' ?></p>
                                    <p class="text-xs text-gray-600">Resource: <?= $event['resource_id'] ?? 'N/A' ?></p>
                                    <p class="text-xs text-gray-600"><?= date('M d, Y', strtotime($event['date'])) ?></p>
                                    <span class="inline-block text-xs px-2 py-1 rounded-full mt-1 bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">No pending reservations</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- FILTER + CSV (added above Recent Reservations) -->
            <div class="flex flex-wrap gap-3 mb-4">
                <select id="statusFilter" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Declined</option>
                </select>

                <input id="searchBox" type="text" placeholder="Search" class="border rounded-lg px-3 py-2 text-sm">

                <button id="downloadCsv" class="border px-4 py-2 rounded-lg text-blue-700 hover:bg-blue-50">
                    <i class="fa-solid fa-download"></i> CSV
                </button>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-blue-900">Recent Reservations</h3>
                    <a href="/admin/manage-reservations" class="text-blue-600 hover:text-blue-700 text-sm">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table id="reservationsTable">
                        <thead>
                            <tr>
                                <th>Reservation ID</th>
                                <th>User</th>
                                <th>Resource</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($reservations) && is_array($reservations)): ?>
                                <?php foreach (array_slice($reservations, 0, 5) as $reservation): ?>
                                    <tr data-status="<?= $reservation['status'] ?? 'pending' ?>">
                                        <td>#<?= $reservation['id'] ?? 'N/A' ?></td>
                                        <td><?= $reservation['user_id'] ?? 'N/A' ?></td>
                                        <td><?= $reservation['resource_id'] ?? 'N/A' ?></td>
                                        <td><?= isset($reservation['date']) ? date('M d, Y', strtotime($reservation['date'])) : 'N/A' ?></td>
                                        <td>
                                            <?php
                                            $status = $reservation['status'] ?? 'pending';
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
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                                                <?php if ($status === 'pending'): ?>
                                                    <button type="submit" formaction="/admin/approve" class="btn btn-approve">Approve</button>
                                                    <button type="submit" formaction="/admin/decline" class="btn btn-decline">Decline</button>
                                                <?php endif; ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-gray-500 py-4">No reservations found</td>
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

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const reservations = <?= json_encode($reservations ?? []) ?>;

            const events = reservations.map(res => ({
                title: 'Reservation #' + res.id,
                start: res.date,
                backgroundColor: res.status === 'approved' ? '#10b981' : (res.status === 'pending' ? '#f59e0b' : '#ef4444'),
                borderColor: res.status === 'approved' ? '#059669' : (res.status === 'pending' ? '#d97706' : '#dc2626'),
                extendedProps: {
                    resourceId: res.resource_id,
                    status: res.status
                }
            }));

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,listMonth'
                },
                events: events,
                height: 'auto',
                eventClick: function(info) {
                    alert('Resource: ' + info.event.extendedProps.resourceId + '\nStatus: ' + info.event.extendedProps.status);
                }
            });

            calendar.render();
            const statusFilter = document.getElementById('statusFilter');
            const searchBox = document.getElementById('searchBox');

            function filterTable() {
                document.querySelectorAll('#reservationsTable tbody tr').forEach(row => {
                    const s = statusFilter.value;
                    const q = searchBox.value.toLowerCase();
                    row.style.display = (!s || row.dataset.status === s) && row.innerText.toLowerCase().includes(q) ? '' : 'none';
                });
            }

            statusFilter.onchange = filterTable;
            searchBox.onkeyup = filterTable;

            document.getElementById('downloadCsv').onclick = () => {
                let csv = 'ID,User,Resource,Date,Status\n';
                document.querySelectorAll('#reservationsTable tbody tr').forEach(r => {
                    if (r.style.display !== 'none') {
                        csv += [...r.children].slice(0, 5).map(td => `"${td.innerText}"`).join(',') + '\n';
                    }
                });
                const a = document.createElement('a');
                a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
                a.download = 'admin_reservations.csv';
                a.click();
            };

        });
    </script>



</body>

</html>
