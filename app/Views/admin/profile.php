<?php
date_default_timezone_set('Asia/Manila');
$page = $page ?? 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Profile | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        /* ── Sidebar (identical to dashboard) ── */
        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            flex-shrink: 0;
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px;
        }

        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }

        /* ── Mobile nav pill (identical to dashboard) ── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px; background: rgba(30,41,59,0.98);
            backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
            z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        }
        .mobile-scroll-container {
            display: flex; gap: 4px; overflow-x: auto;
            scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
        }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Modal ── */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.65); backdrop-filter: blur(6px);
            z-index: 200; padding: 1.5rem; overflow-y: auto;
            align-items: center; justify-content: center;
        }
        .modal-backdrop.show { display: flex; animation: fadeIn 0.15s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-box {
            background: white; border-radius: 32px; width: 100%;
            max-width: 460px; padding: 2.5rem; margin: auto;
            animation: slideUp 0.2s ease;
        }
        @keyframes slideUp { from { transform: translateY(16px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* ── Form inputs ── */
        .field-input {
            width: 100%; background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 0.875rem 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.92rem; font-weight: 700; color: #1e293b;
            transition: all 0.2s;
        }
        .field-input:focus {
            outline: none; border-color: #2563eb; background: white;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.08);
        }
        .field-label {
            display: block; font-size: 0.68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: #94a3b8; margin-bottom: 0.4rem;
        }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
        ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
        ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
        ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
        ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
        ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
        ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
        ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
        ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];
    ?>

    <!-- ── Sidebar ── -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
                ?>
                    <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar-footer">
                <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- ── Mobile Nav (matching dashboard exactly) ── -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = ($page == $item['key']);
                $btnClass = $isActive ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- ── Main ── -->
    <main class="flex-1 p-6 lg:p-12 pb-32">

        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">My Profile</h2>
                <p class="text-slate-500 font-medium">Manage your account settings and security.</p>
            </div>
            <span class="hidden md:inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest border border-blue-100">
                System Verified
            </span>
        </header>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Avatar card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 text-center sticky top-8">
                        <div class="relative inline-block mb-6">
                            <div class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-3xl flex items-center justify-center text-white text-3xl shadow-xl shadow-blue-200 mx-auto">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-emerald-500 border-4 border-white w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-check text-[10px] text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-black text-slate-800"><?= htmlspecialchars($user['name'] ?? 'Administrator') ?></h3>
                        <p class="text-slate-400 font-semibold text-sm mb-4"><?= htmlspecialchars($user['email'] ?? '') ?></p>

                        <div class="inline-block px-4 py-1.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-8">
                            Administrator
                        </div>

                        <button onclick="openModal()"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl py-4 transition shadow-lg shadow-blue-200/50 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                        </button>
                    </div>
                </div>

                <!-- Info cards -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-8 py-5 border-b border-slate-100">
                            <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Account Information</h4>
                        </div>
                        <div class="p-8 space-y-6">

                            <?php
                            $fields = [
                                ['icon' => 'fa-id-badge',      'label' => 'Full Name',      'value' => $user['name']  ?? 'Admin'],
                                ['icon' => 'fa-envelope',      'label' => 'Email Address',  'value' => $user['email'] ?? '—'],
                                ['icon' => 'fa-phone',         'label' => 'Contact Number', 'value' => $user['phone'] ?? 'Not set'],
                                ['icon' => 'fa-shield-halved', 'label' => 'Security Level', 'value' => 'Level 1 (Full Access)'],
                            ];
                            foreach ($fields as $i => $f):
                                $border = $i < count($fields) - 1 ? 'border-b border-slate-50' : '';
                            ?>
                                <div class="flex items-center gap-4 py-2 <?= $border ?>">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0">
                                        <i class="fa-solid <?= $f['icon'] ?>"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $f['label'] ?></p>
                                        <p class="font-bold text-slate-700"><?= htmlspecialchars($f['value']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                    <!-- Info banner -->
                    <div class="bg-blue-600 rounded-[32px] p-8 text-white flex items-center gap-6">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg leading-tight">Keep your info up to date</h5>
                            <p class="text-blue-100 text-sm mt-1">Ensure your contact details are correct to receive critical system notifications.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ── Edit Profile Modal ── -->
    <div id="editModal" class="modal-backdrop" onclick="handleBackdrop(event)">
        <div class="modal-box">
            <div class="mb-6">
                <h3 class="text-xl font-black text-slate-900">Update Profile</h3>
                <p class="text-slate-400 text-sm font-medium mt-1">Changes will be logged in the activity trail.</p>
            </div>

            <form action="<?= base_url('admin/profile/update') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="field-label">Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                        class="field-input" required>
                </div>
                <div>
                    <label class="field-label">Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                        class="field-input" required>
                </div>
                <div>
                    <label class="field-label">Contact Number</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        class="field-input" placeholder="e.g. +63 912 345 6789">
                </div>
                <div>
                    <label class="field-label">New Password</label>
                    <input type="password" name="password"
                        class="field-input" placeholder="Leave blank to keep current">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition text-sm shadow-lg shadow-blue-200/50">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('editModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('editModal').classList.remove('show');
            document.body.style.overflow = '';
        }
        function handleBackdrop(e) {
            if (e.target === document.getElementById('editModal')) closeModal();
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>
</body>
</html>