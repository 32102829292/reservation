<?= $this->extend('sk/layout') ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">

<style>
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

    .badge {
        padding: .25rem .75rem;
        border-radius: 9999px;
        font-size: .75rem;
        font-weight: 600;
    }

    .badge-pending {
        background: #f59e0b;
        color: #fff;
    }

    .badge-approved {
        background: #10b981;
        color: #fff;
    }

    .badge-claimed {
        background: #0ea5e9;
        color: #fff;
    }

    main {
        padding-bottom: env(safe-area-inset-bottom, 5.5rem);
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .4rem .9rem;
        border-radius: 9999px;
        background-color: #3b82f6;
        color: white;
        font-weight: 500;
    }

    .logout-btn:hover {
        background-color: #2563eb;
    }

    #calendar {
        width: 100%;
    }

    .fc {
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .fc {
            font-size: 0.75rem;
        }
    }
</style>

<?php
$page = $page ?? 'dashboard';
$total = $total ?? 0;
$approved = $approved ?? 0;
$pending = $pending ?? 0;
$claimed = $claimed ?? 0;
$reservations = $reservations ?? [];
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
    <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">
        <i class="fa-solid fa-gauge me-2"></i> SK Dashboard
    </h2>
    <a href="/logout" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="card bg-blue-500 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Total</p>
        <h3 class="text-3xl font-bold"><?= $total ?></h3>
    </div>
    <div class="card bg-blue-400 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Approved</p>
        <h3 class="text-3xl font-bold"><?= $approved ?></h3>
    </div>
    <div class="card bg-blue-600 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Pending</p>
        <h3 class="text-3xl font-bold"><?= $pending ?></h3>
    </div>
    <div class="card bg-blue-700 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Claimed</p>
        <h3 class="text-3xl font-bold"><?= $claimed ?></h3>
    </div>
</div>

<div class="flex flex-wrap gap-3 mb-4">
    <select id="statusFilter" class="border rounded-lg px-3 py-2 text-sm">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="claimed">Claimed</option>
    </select>
    <input id="searchBox" type="text" placeholder="Search" class="border rounded-lg px-3 py-2 text-sm">
    <button id="downloadCsv" class="border px-4 py-2 rounded-lg text-blue-700 hover:bg-blue-50">
        <i class="fa-solid fa-download"></i> CSV
    </button>
</div>

<div class="bg-white rounded-2xl shadow overflow-x-auto mb-6">
    <table class="min-w-full text-sm" id="reservationsTable">
        <thead class="bg-blue-100 text-blue-900">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3">Resource</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Time</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($reservations as $r): ?>
                <tr data-status="<?= esc($r['status']) ?>" class="hover:bg-blue-50">
                    <td class="px-4 py-3"><?= esc($r['user_name']) ?></td>
                    <td class="px-4 py-3">
                        <?= esc($r['resource_name']) ?>
                        <?= !empty($r['pc_number']) ? '(PC ' . $r['pc_number'] . ')' : '' ?>
                    </td>
                    <td class="px-4 py-3"><?= esc($r['reservation_date']) ?></td>
                    <td class="px-4 py-3">
                        <?= date('h:i A', strtotime($r['start_time'])) ?> –
                        <?= date('h:i A', strtotime($r['end_time'])) ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge badge-<?= esc($r['status']) ?>">
                            <?= ucfirst($r['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="bg-white rounded-2xl p-4 shadow">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-blue-900">Reservation Calendar</h3>
        <select id="viewSelect" class="border rounded-lg px-2 py-1 text-sm">
            <option value="dayGridMonth">Month</option>
            <option value="timeGridWeek">Week</option>
            <option value="timeGridDay">Day</option>
        </select>
    </div>
    <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
    const statusFilter = document.getElementById('statusFilter')
    const searchBox = document.getElementById('searchBox')

    function filterTable() {
        document.querySelectorAll('#reservationsTable tbody tr').forEach(row => {
            const s = statusFilter.value
            const q = searchBox.value.toLowerCase()
            row.style.display = (!s || row.dataset.status === s) && row.innerText.toLowerCase().includes(q) ? '' : 'none'
        })
    }
    statusFilter.onchange = filterTable
    searchBox.onkeyup = filterTable

    document.getElementById('downloadCsv').onclick = () => {
        let csv = 'Name,Resource,Date,Time,Status\n'
        document.querySelectorAll('#reservationsTable tbody tr').forEach(r => {
            if (r.style.display !== 'none') {
                csv += [...r.children].map(td => `"${td.innerText}"`).join(',') + '\n'
            }
        })
        const a = document.createElement('a')
        a.href = URL.createObjectURL(new Blob([csv], {
            type: 'text/csv'
        }))
        a.download = 'reservations.csv'
        a.click()
    }

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const viewSelect = document.getElementById('viewSelect');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            expandRows: true,
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: ''
            },
            events: <?= json_encode($reservations) ?>.map(r => ({
                title: r.resource_name + ' (' + r.user_name + ')',
                start: r.reservation_date + 'T' + r.start_time,
                backgroundColor: r.status === 'approved' ? '#10b981' : (r.status === 'claimed' ? '#0ea5e9' : '#f59e0b')
            }))
        });

        calendar.render();

        viewSelect.addEventListener('change', function() {
            calendar.changeView(this.value);
        });
    });
</script>

<?= $this->endSection() ?>
