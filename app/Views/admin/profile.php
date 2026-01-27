<!DOCTYPE html>
<html lang="en" x-data="{ openModal: false }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Profile</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

        .role-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #dbeafe;
            color: #1e40af;
            border-radius: 9999px;
            font-weight: 600;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'profile'; ?>

    <div class="flex flex-1 flex-col lg:flex-row">

        <aside class="hidden lg:flex flex-col w-64 bg-blue-600 text-white shadow-xl rounded-tr-3xl rounded-br-3xl p-6">
            <h1 class="text-2xl font-bold mb-10">E-Learning Resource Reservation</h1>
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

        <main class="flex-1 p-6">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-blue-900">My Profile</h2>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-lg">

                <div class="flex flex-col items-center mb-6">
                    <div class="bg-blue-100 text-blue-600 w-20 h-20 flex items-center justify-center rounded-full text-4xl mb-2">
                        <i class="fa-solid fa-shield"></i>
                    </div>
                    <h3 class="text-xl font-semibold"><?= esc($user['name'] ?? 'Admin') ?></h3>
                    <p class="text-sm text-gray-500"><?= esc($user['email']) ?></p>
                    <div class="mt-2">
                        <span class="role-badge">Administrator</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-badge text-blue-600"></i>
                        <strong>Role:</strong> <?= ucfirst((string)($user['role'] ?? 'admin')) ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-blue-600"></i>
                        <strong>Phone:</strong> <?= esc($user['phone'] ?? 'Not set') ?>
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

        </main>
    </div>

    <div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="openModal = false" class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Update Profile</h3>
            <form action="<?= base_url('admin/profile/update') ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Name</label>
                    <input type="text" name="name" value="<?= esc($user['name'] ?? '') ?>" class="w-full border border-gray-300 rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?= esc($user['email']) ?>" class="w-full border border-gray-300 rounded-lg p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="<?= esc($user['phone'] ?? '') ?>" class="w-full border border-gray-300 rounded-lg p-2">
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
