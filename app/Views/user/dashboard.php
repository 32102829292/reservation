<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">

<style>
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

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
    <div class="flex items-center gap-4 mb-3 md:mb-0">
        <h2 class="text-2xl font-semibold text-blue-900">Dashboard</h2>
        <div class="flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
            <i class="fa-solid fa-robot"></i> AI Fairness: Active
        </div>
    </div>
    <a href="/logout" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<?php
$total = count($reservations);
$pending = count(array_filter($reservations, fn($r) => $r['status'] === 'pending'));
$approved = count(array_filter($reservations, fn($r) => $r['status'] === 'approved'));
$declined = count(array_filter($reservations, fn($r) => $r['status'] === 'canceled' || $r['status'] === 'declined'));
?>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="card bg-blue-500 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Total Reservations</p>
        <h3 class="text-3xl font-bold"><?= $total ?></h3>
    </div>
    <div class="card bg-blue-400 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Pending</p>
        <h3 class="text-3xl font-bold"><?= $pending ?></h3>
    </div>
    <div class="card bg-blue-600 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Approved</p>
        <h3 class="text-3xl font-bold"><?= $approved ?></h3>
    </div>
    <div class="card bg-blue-700 text-white rounded-2xl p-5 shadow">
        <p class="text-sm">Declined</p>
        <h3 class="text-3xl font-bold"><?= $declined ?></h3>
    </div>
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
            events: <?= json_encode(array_map(function($r) {
                return [
                    'title' => ($r['resource_name'] ?? 'Reservation') . ' (' . $r['status'] . ')',
                    'start' => $r['reservation_date'] . 'T' . $r['start_time'],
                    'backgroundColor' => $r['status'] === 'approved' ? '#10b981' : ($r['status'] === 'pending' ? '#f59e0b' : '#ef4444')
                ];
            }, $reservations)) ?>
        });

        calendar.render();

        viewSelect.addEventListener('change', function() {
            calendar.changeView(this.value);
        });
    });
</script>

<?= $this->endSection() ?>
