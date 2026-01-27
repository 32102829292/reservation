<?= $this->extend('sk/layout') ?>

<?= $this->section('content') ?>

<style>
    .badge {
        padding: .25rem .75rem;
        border-radius: 9999px;
        font-size: .75rem;
        font-weight: 600
    }

    .badge-pending {
        background: #f59e0b;
        color: #fff
    }

    .badge-approved {
        background: #10b981;
        color: #fff
    }

    .badge-claimed {
        background: #0ea5e9;
        color: #fff
    }

    .badge-canceled {
        background: #ef4444;
        color: #fff
    }
</style>

<?php
$page = $page ?? 'reservations';
$reservations = $reservations ?? [];
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
    <h2 class="text-2xl font-semibold text-blue-900 mb-3 md:mb-0">
        <i class="fa-solid fa-list me-2"></i> Reservations
    </h2>
</div>

<div class="flex flex-wrap gap-3 mb-4">
    <form method="get" class="flex gap-3 flex-wrap">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="claimed">Claimed</option>
            <option value="canceled">Canceled</option>
        </select>

        <input type="date" name="date" class="border rounded-lg px-3 py-2 text-sm">

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
            Filter
        </button>
    </form>

    <a href="<?= base_url('sk/download-csv') ?>" class="border px-4 py-2 rounded-lg text-blue-700 hover:bg-blue-50 text-sm">
        <i class="fa-solid fa-download"></i> CSV
    </a>
</div>

<div class="bg-white rounded-2xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-blue-100 text-blue-900">
            <tr>
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3">Resource</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Time</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            <?php foreach ($reservations as $r): ?>
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-3"><?= esc($r['user_id']) ?></td>
                    <td class="px-4 py-3"><?= esc($r['resource_id']) ?></td>
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
                    <td class="px-4 py-3 flex gap-2">
                        <?php if ($r['status'] === 'pending'): ?>
                            <form method="post" action="<?= base_url('sk/approve') ?>">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button class="bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                            </form>

                            <form method="post" action="<?= base_url('sk/decline') ?>">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs">Decline</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
