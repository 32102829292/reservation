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
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --blue:       #2563eb;
            --blue-light: #eff6ff;
            --card-border:#e2e8f0;
            --slate-bg:   #f8fafc;
            --text-muted: #64748b;
            --text-faint: #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }

        /* ── ROOT LAYOUT ── */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--slate-bg);
            color: #1e293b;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .page-wrapper { display: flex; width: 100%; height: 100vh; overflow: hidden; }

        .sidebar-col { width: 280px; flex-shrink: 0; padding: 24px; display: none; height: 100vh; overflow: hidden; }
        @media (min-width: 1024px) { .sidebar-col { display: block; } }

        .main-col { flex: 1; min-width: 0; height: 100vh; overflow-y: auto; }

        /* ── SIDEBAR CARD ── */
        .sidebar-card {
            background: white; border-radius: 32px; border: 1px solid var(--card-border);
            height: calc(100vh - 48px); position: sticky; top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.05);
            display: flex; flex-direction: column; overflow: hidden; width: 100%;
        }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; scrollbar-width: thin; scrollbar-color: var(--card-border) transparent; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }

        .sidebar-item {
            transition: all .18s; display: flex; align-items: center; gap: 14px;
            padding: 12px 18px; border-radius: 16px; font-weight: 600;
            font-size: .875rem; text-decoration: none; color: var(--text-muted);
        }
        .sidebar-item:hover { background: var(--blue-light); color: var(--blue); }
        .sidebar-item.active { background: var(--blue); color: white; box-shadow: 0 8px 20px -4px rgba(37,99,235,.35); }
        .sidebar-item i { width: 20px; text-align: center; font-size: 1rem; flex-shrink: 0; }

        /* ── MOBILE NAV PILL ── */
        .mobile-nav-pill {
            position: fixed;
            bottom: calc(16px + env(safe-area-inset-bottom, 0px));
            left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px;
            background: rgba(15,23,42,.97);
            backdrop-filter: blur(12px);
            border-radius: 24px; padding: 6px;
            z-index: 100;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,.3);
        }
        .mobile-scroll-container {
            display: flex; gap: 4px;
            overflow-x: auto; -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── MODAL ── */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
            z-index: 200; padding: 1.5rem; overflow-y: auto;
            align-items: center; justify-content: center;
        }
        .modal-backdrop.show { display: flex; animation: fadeIn .15s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-box {
            background: white; border-radius: 32px; width: 100%;
            max-width: 460px; padding: 2.5rem; margin: auto;
            animation: slideUp .2s ease;
        }
        @keyframes slideUp { from { transform: translateY(16px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* ── FORM FIELDS ── */
        .field-input {
            width: 100%; background: #f8fafc; border: 1px solid var(--card-border);
            border-radius: 12px; padding: .875rem 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .92rem; font-weight: 700; color: #1e293b; transition: all .2s;
        }
        .field-input:focus { outline: none; border-color: var(--blue); background: white; box-shadow: 0 0 0 4px rgba(37,99,235,.08); }
        .field-label { display: block; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--text-faint); margin-bottom: .4rem; }
    </style>
</head>
<body>

<?php
$navItems = [
    ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
    ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
    ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
    ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
    ['url' => '/admin/books',               'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
    ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
    ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
    ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
    ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
];
?>

<div class="page-wrapper">

    <!-- ════════ SIDEBAR ════════ -->
    <aside class="sidebar-col">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800 mt-0.5">Admin<span class="text-blue-600">.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item): $a = ($page == $item['key']) ? 'active' : ''; ?>
                    <a href="<?= $item['url'] ?>" class="sidebar-item <?= $a ?>">
                        <i class="fa-solid <?= $item['icon'] ?>"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all text-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- ════════ MOBILE NAV ════════ -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item): $cls = ($page == $item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30'; ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ════════ MAIN ════════ -->
    <div class="main-col">
        <main class="w-full max-w-screen-xl mx-auto px-4 lg:px-8 pt-6 pb-36">

            <header class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Account</p>
                    <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight">My Profile</h2>
                    <p class="text-slate-400 font-medium text-sm mt-0.5">Manage your account settings and security.</p>
                </div>
                <span class="hidden md:inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest border border-blue-100">
                    System Verified
                </span>
            </header>

            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Avatar card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-6 text-center lg:sticky lg:top-8">
                            <div class="relative inline-block mb-5">
                                <div class="w-20 h-20 lg:w-24 lg:h-24 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-3xl flex items-center justify-center text-white text-3xl shadow-xl shadow-blue-200 mx-auto">
                                    <i class="fa-solid fa-user-shield"></i>
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-emerald-500 border-4 border-white w-7 h-7 lg:w-8 lg:h-8 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-check text-[10px] text-white"></i>
                                </div>
                            </div>
                            <h3 class="text-lg lg:text-xl font-black text-slate-800"><?= htmlspecialchars($user['name'] ?? 'Administrator') ?></h3>
                            <p class="text-slate-400 font-semibold text-sm mb-4 truncate"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                            <div class="inline-block px-4 py-1.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-6">
                                Administrator
                            </div>
                            <button onclick="openModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl py-3.5 transition shadow-lg shadow-blue-200/50 flex items-center justify-center gap-2 text-sm">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                            </button>
                        </div>
                    </div>

                    <!-- Info cards -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 lg:px-8 py-4 lg:py-5 border-b border-slate-100">
                                <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Account Information</h4>
                            </div>
                            <div class="p-6 lg:p-8 space-y-5">
                                <?php
                                $fields = [
                                    ['icon' => 'fa-id-badge',      'label' => 'Full Name',      'value' => $user['name']  ?? 'Admin'],
                                    ['icon' => 'fa-envelope',      'label' => 'Email Address',  'value' => $user['email'] ?? '—'],
                                    ['icon' => 'fa-phone',         'label' => 'Contact Number', 'value' => $user['phone'] ?? 'Not set'],
                                    ['icon' => 'fa-shield-halved', 'label' => 'Security Level', 'value' => 'Level 1 (Full Access)'],
                                ];
                                foreach ($fields as $i => $f):
                                    $border = $i < count($fields) - 1 ? 'border-b border-slate-50 pb-4' : '';
                                ?>
                                    <div class="flex items-center gap-4 <?= $border ?>">
                                        <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0">
                                            <i class="fa-solid <?= $f['icon'] ?> text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $f['label'] ?></p>
                                            <p class="font-bold text-slate-700 text-sm truncate"><?= htmlspecialchars($f['value']) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Info banner -->
                        <div class="bg-blue-600 rounded-[32px] p-6 lg:p-8 text-white flex items-center gap-4 lg:gap-6">
                            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white/20 rounded-2xl flex items-center justify-center text-xl lg:text-2xl flex-shrink-0">
                                <i class="fa-solid fa-lightbulb"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-base lg:text-lg leading-tight">Keep your info up to date</h5>
                                <p class="text-blue-100 text-xs lg:text-sm mt-1">Ensure your contact details are correct to receive critical system notifications.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div><!-- /.main-col -->

</div><!-- /.page-wrapper -->

<!-- ════════ MODAL ════════ -->
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
                <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Contact Number</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="field-input" placeholder="e.g. +63 912 345 6789">
            </div>
            <div>
                <label class="field-label">New Password</label>
                <input type="password" name="password" class="field-input" placeholder="Leave blank to keep current">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Cancel</button>
                <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition text-sm shadow-lg shadow-blue-200/50">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal()  { document.getElementById('editModal').classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeModal() { document.getElementById('editModal').classList.remove('show'); document.body.style.overflow = ''; }
    function handleBackdrop(e) { if (e.target === document.getElementById('editModal')) closeModal(); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
</body>
</html>