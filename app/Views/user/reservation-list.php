<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>

<style>
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-pending {
        background: #f59e0b;
        color: white;
    }

    .badge-approved {
        background: #10b981;
        color: white;
    }

    .badge-declined {
        background: #ef4444;
        color: white;
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-blue-900">My Reservations</h2>
    <a href="/logout" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<div class="bg-white shadow-lg rounded-2xl overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-blue-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-blue-900">E-Ticket</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-blue-900">Resource</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-blue-900">Date</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-blue-900">Time</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-blue-900">Purpose</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-blue-900">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No reservations found.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($reservations as $r): ?>
                <tr class="hover:bg-blue-50 transition">
                    <td class="px-4 py-3 font-mono text-sm"><?= esc($r['e_ticket_code']) ?></td>
                    <td class="px-4 py-3 text-sm">
                        <?= esc($r['resource_name']) ?>
                        <?php if ($r['resource_name'] === 'Computer' && !empty($r['pc_number'])): ?>
                            <span class="text-xs text-gray-500">(PC <?= esc($r['pc_number']) ?>)</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-sm"><?= date('M d, Y', strtotime($r['reservation_date'])) ?></td>
                    <td class="px-4 py-3 text-sm"><?= date('h:i A', strtotime($r['start_time'])) ?> – <?= date('h:i A', strtotime($r['end_time'])) ?></td>
                    <td class="px-4 py-3 text-sm"><?= esc($r['purpose']) ?></td>
                    <td class="px-4 py-3 text-sm">
                        <?php if ($r['status'] == 'approved'): ?>
                            <span class="badge badge-approved">Approved</span>
                        <?php elseif ($r['status'] == 'pending'): ?>
                            <span class="badge badge-pending">Pending</span>
                        <?php else: ?>
                            <span class="badge badge-declined">Declined</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
