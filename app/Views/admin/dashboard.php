<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | Admin</title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
</head>

<body>

    <?php
    $page = 'dashboard';
    $admin_name = session()->get('name') ?? session()->get('username') ?? 'Administrator';

    // Sample data - replace with your actual database queries
    $total = 145;
    $approved = 98;
    $pending = 32;
    $declined = 15;
    $claimed = 67;
    $todayTotal = 12;
    $todayPending = 5;
    $todayApproved = 6;
    $todayClaimed = 1;
    $monthlyTotal = 89;
    $totalResources = 24;
    $totalUsers = 156;
    $pendingBorrowings = 3;

    $approvalRate = $total > 0 ? round(($approved / $total) * 100) : 0;
    $utilizationRate = $approved > 0 ? round(($claimed / $approved) * 100) : 0;

    $chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $chartData = [12, 19, 15, 22, 28, 18, 14];
    $resourceLabels = ['PC 1', 'PC 2', 'PC 3', 'PC 4', 'Laptop'];
    $resourceData = [45, 32, 28, 24, 18];

    $reservations = [
        ['id' => 1, 'resource_name' => 'PC Station A', 'visitor_name' => 'John Doe', 'status' => 'pending', 'reservation_date' => '2026-04-08', 'start_time' => '10:00:00', 'end_time' => '12:00:00'],
        ['id' => 2, 'resource_name' => 'PC Station B', 'visitor_name' => 'Jane Smith', 'status' => 'pending', 'reservation_date' => '2026-04-08', 'start_time' => '14:00:00', 'end_time' => '16:00:00'],
    ];

    $dashBooks = [
        ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'genre' => 'fiction', 'available_copies' => 3],
        ['title' => '1984', 'author' => 'George Orwell', 'genre' => 'fiction', 'available_copies' => 2],
        ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'genre' => 'romance', 'available_copies' => 1],
        ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'genre' => 'fantasy', 'available_copies' => 4],
    ];
    $bookTotalCount = count($dashBooks);
    $bookAvailCount = array_sum(array_column($dashBooks, 'available_copies'));

    $dashBorrowReqs = [];
    ?>

    <!-- Sidebar -->
    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- Main Content -->
    <main class="main-area">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <div class="page-eyebrow"><?php
                                            $hh = (int)date('H');
                                            echo $hh < 12 ? 'Good morning' : ($hh < 17 ? 'Good afternoon' : 'Good evening');
                                            ?>, <?= htmlspecialchars($admin_name) ?></div>
                <div class="page-title">Admin Dashboard</div>
                <div class="page-sub"><?= date('l, F j, Y') ?></div>
            </div>
            <div class="topbar-right">
                <?php if ($pending > 0): ?>
                    <a href="/admin/manage-reservations?status=pending" class="pending-pill">
                        <i class="fa-solid fa-clock"></i> <?= $pending ?> pending
                    </a>
                <?php endif; ?>
                <div class="icon-btn" onclick="adminToggleDark()">
                    <i class="fa-regular fa-moon"></i>
                </div>
                <a href="/admin/new-reservation" class="action-btn">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" style="border-left-color: var(--indigo);">
                <div class="stat-lbl">Total Reservations</div>
                <div class="stat-num"><?= $total ?></div>
                <div class="stat-hint">+<?= $monthlyTotal ?> this month</div>
            </div>
            <div class="stat-card" style="border-left-color: #16a34a;">
                <div class="stat-lbl">Approved</div>
                <div class="stat-num" style="color: #16a34a;"><?= $approved ?></div>
                <div class="prog-bar">
                    <div class="prog-fill" style="width: <?= $approvalRate ?>%; background: #16a34a;"></div>
                </div>
                <div class="stat-hint"><?= $approvalRate ?>% approval rate</div>
            </div>
            <div class="stat-card" style="border-left-color: #d97706;">
                <div class="stat-lbl">Today</div>
                <div class="stat-num" style="color: #d97706;"><?= $todayTotal ?></div>
                <div class="stat-hint"><?= $todayPending ?> pending · <?= $todayApproved ?> approved</div>
            </div>
            <div class="stat-card" style="border-left-color: #7c3aed;">
                <div class="stat-lbl">Utilization</div>
                <div class="stat-num" style="color: #7c3aed;"><?= $utilizationRate ?>%</div>
                <div class="prog-bar">
                    <div class="prog-fill" style="width: <?= $utilizationRate ?>%; background: #7c3aed;"></div>
                </div>
                <div class="stat-hint"><?= $claimed ?> of <?= $approved ?> claimed</div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="stats-grid" style="margin-bottom: 24px;">
            <?php foreach (
                [
                    ['Total', $total, '#3730a3', 'fa-layer-group'],
                    ['Pending', $pending, '#d97706', 'fa-clock'],
                    ['Approved', $approved, '#16a34a', 'fa-circle-check'],
                    ['Declined', $declined, '#ef4444', 'fa-circle-xmark'],
                ] as [$label, $value, $color, $icon]
            ): ?>
                <div class="stat-card" style="border-left-color: <?= $color ?>;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="stat-lbl"><?= $label ?></span>
                        <i class="fa-solid <?= $icon ?>" style="color: <?= $color ?>;"></i>
                    </div>
                    <div class="stat-num" style="color: <?= $color ?>;"><?= $value ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Charts Row -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
            <div class="card card-p">
                <div class="card-head">
                    <div>
                        <div class="card-title">Reservations Trend</div>
                        <div class="card-sub">Last 7 days</div>
                    </div>
                </div>
                <div style="height: 200px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
            <div class="card card-p">
                <div class="card-head">
                    <div>
                        <div class="card-title">Popular Resources</div>
                        <div class="card-sub">Most reserved</div>
                    </div>
                </div>
                <div style="height: 200px;">
                    <canvas id="resourceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Calendar and Quick Actions -->
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div class="card card-p">
                <div class="card-head">
                    <div>
                        <div class="card-title">Reservation Calendar</div>
                        <div class="card-sub">Click on date to view</div>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <!-- System Stats -->
                <div style="background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 100%); border-radius: 20px; padding: 20px;">
                    <div style="color: rgba(255,255,255,0.6); font-size: 0.7rem; margin-bottom: 12px;">SYSTEM STATS</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <div style="color: rgba(255,255,255,0.5); font-size: 0.6rem;">Resources</div>
                            <div style="color: white; font-size: 1.5rem; font-weight: 800;"><?= $totalResources ?></div>
                        </div>
                        <div>
                            <div style="color: rgba(255,255,255,0.5); font-size: 0.6rem;">Users</div>
                            <div style="color: white; font-size: 1.5rem; font-weight: 800;"><?= $totalUsers ?></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card card-p">
                    <div class="card-title" style="margin-bottom: 12px;">Quick Actions</div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="/admin/new-reservation" class="qa-link"><i class="fa-solid fa-plus"></i> New Reservation <i class="fa-solid fa-chevron-right" style="margin-left: auto;"></i></a>
                        <a href="/admin/manage-reservations" class="qa-link"><i class="fa-solid fa-calendar"></i> All Reservations <i class="fa-solid fa-chevron-right" style="margin-left: auto;"></i></a>
                        <a href="/admin/manage-pcs" class="qa-link"><i class="fa-solid fa-desktop"></i> Manage PCs <i class="fa-solid fa-chevron-right" style="margin-left: auto;"></i></a>
                        <a href="/admin/books" class="qa-link"><i class="fa-solid fa-book"></i> Library <i class="fa-solid fa-chevron-right" style="margin-left: auto;"></i></a>
                    </div>
                </div>

                <!-- Needs Approval -->
                <div class="card card-p">
                    <div class="card-head" style="margin-bottom: 12px;">
                        <div class="card-title">Needs Approval</div>
                        <?php if ($pending > 0): ?>
                            <a href="/admin/manage-reservations?status=pending" class="link-sm">View all</a>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $res): ?>
                            <div class="qa-link" style="margin-bottom: 8px;">
                                <div>
                                    <div style="font-weight: 600;"><?= htmlspecialchars($res['resource_name']) ?></div>
                                    <div style="font-size: 0.7rem; color: var(--text-sub);"><?= htmlspecialchars($res['visitor_name']) ?></div>
                                </div>
                                <span class="tag tag-pending" style="margin-left: auto;">Pending</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 20px; color: var(--text-sub);">
                            <i class="fa-regular fa-circle-check"></i>
                            <p>All caught up!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Library Section -->
        <div class="card card-p-lg">
            <div class="card-head">
                <div>
                    <div class="card-title">Library Collection</div>
                    <div class="card-sub"><?= $bookAvailCount ?> available · <?= $bookTotalCount ?> total titles</div>
                </div>
                <a href="/admin/books" class="action-btn" style="padding: 8px 16px; font-size: 0.75rem;">Browse All →</a>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;">
                <?php foreach ($dashBooks as $book): ?>
                    <div class="qa-link" style="flex-direction: column; align-items: flex-start;">
                        <div style="display: flex; justify-content: space-between; width: 100%;">
                            <span style="font-weight: 700;"><?= htmlspecialchars($book['title']) ?></span>
                            <span class="tag tag-approved"><?= $book['available_copies'] ?> left</span>
                        </div>
                        <div style="font-size: 0.7rem; color: var(--text-sub);"><?= htmlspecialchars($book['author']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script>
        // Charts initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Trend Chart
            const trendCtx = document.getElementById('trendChart')?.getContext('2d');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($chartLabels) ?>,
                        datasets: [{
                            data: <?= json_encode($chartData) ?>,
                            borderColor: '#3730a3',
                            backgroundColor: 'rgba(55,48,163,0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3730a3',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Resource Chart
            const resourceCtx = document.getElementById('resourceChart')?.getContext('2d');
            if (resourceCtx) {
                new Chart(resourceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: <?= json_encode($resourceLabels) ?>,
                        datasets: [{
                            data: <?= json_encode($resourceData) ?>,
                            backgroundColor: ['#3730a3', '#7c3aed', '#16a34a', '#d97706', '#ec4899'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Calendar
            const calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today'
                    },
                    height: 350,
                    events: []
                });
                calendar.render();
            }
        });
    </script>

</body>

</html>