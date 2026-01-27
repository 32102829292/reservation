<?= $this->extend('sk/layout') ?>

<?= $this->section('content') ?>

<?php $page = 'profile'; ?>

<div x-data="{ openModal: false }">

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

    .role-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: #dbeafe;
        color: #1e40af;
        border-radius: 9999px;
        font-weight: 600;
    }
</style>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-blue-900">My Profile</h2>
    <a href="/logout" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>

<div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-lg">

    <div class="flex flex-col items-center mb-6">
        <div class="bg-blue-100 text-blue-600 w-20 h-20 flex items-center justify-center rounded-full text-4xl mb-2">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <h3 class="text-xl font-semibold"><?= esc($user['name']) ?></h3>
        <p class="text-sm text-gray-500"><?= esc($user['email']) ?></p>
        <div class="mt-2">
            <span class="role-badge">SK</span>
        </div>
    </div>

    <!-- Profile details -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-badge text-blue-600"></i>
            <strong>Role:</strong> <?= ucfirst((string)($user['role'] ?? 'sk')) ?>
        </div>
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-phone text-blue-600"></i>
            <strong>Phone:</strong> <?= !empty($user['phone']) ? esc($user['phone']) : 'Not set' ?>
        </div>
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-key text-blue-600"></i>
            <strong>Password:</strong> ********
        </div>

        <button @click="openModal = true" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i> Update Profile
        </button>
    </div>
</div>

<div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div @click.away="openModal = false" class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Update Profile</h3>
        <form action="<?= base_url('sk/profile/update') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-blue-700 mb-1">Name</label>
                <input type="text" name="name" value="<?= esc($user['name']) ?>" class="w-full border border-gray-300 rounded-lg p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-blue-700 mb-1">Email</label>
                <input type="email" name="email" value="<?= esc($user['email']) ?>" class="w-full border border-gray-300 rounded-lg p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-blue-700 mb-1">Phone</label>
                <input type="text" name="phone" value="<?= esc($user['phone']) ?>" class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-blue-700 mb-1">Password (leave blank to keep current)</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" @click="openModal=false" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<?= $this->endSection() ?>
