<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Manage PCs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

        /* Original Sidebar - COMPLETELY UNCHANGED */
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

        .sidebar-header span {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            color: #2563eb;
            text-transform: uppercase;
        }

        .sidebar-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
        }

        .sidebar-header h1 span {
            color: #2563eb;
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

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #64748b;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .sidebar-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-item:hover {
            background: #f8fafc;
            color: #2563eb;
        }

        .sidebar-item.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            border-radius: 16px;
            color: #ef4444;
            font-weight: 700;
            transition: all 0.2s;
        }

        .sidebar-footer a:hover {
            background: #fef2f2;
        }

        /* Original Mobile Nav - UNCHANGED */
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

        /* Enhanced Main Content Area */
        .main-content {
            flex: 1;
            padding: 2.5rem 3rem;
            background-color: #ffffff;
            margin: 1.5rem 1.5rem 1.5rem 0;
            border-radius: 32px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .page-header p {
            color: #64748b;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .add-btn {
            background: #0f172a;
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px -2px rgba(15, 23, 42, 0.2);
        }

        .add-btn:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(15, 23, 42, 0.25);
        }

        /* Enhanced Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }
        
        .alert-success {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        /* Enhanced PC Cards */
        .pc-card {
            background: white;
            border-radius: 24px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 10px -4px rgba(0, 0, 0, 0.03);
        }
        .pc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -10px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .pc-card .pc-icon {
            width: 3.5rem;
            height: 3.5rem;
            background: #eef2ff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
        }

        .pc-card .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pc-card .status-badge.available {
            background: #dcfce7;
            color: #166534;
        }

        .pc-card .status-badge.maintenance {
            background: #fff3cd;
            color: #856404;
        }

        .pc-card h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            margin: 1rem 0 0.25rem;
        }

        .pc-card .asset-id {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }

        .pc-actions {
            display: flex;
            gap: 0.75rem;
        }

        .status-btn {
            flex: 1;
            padding: 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .status-btn.available {
            background: #fef9e7;
            color: #92400e;
            border-color: #fcd34d;
        }

        .status-btn.available:hover {
            background: #ffedd5;
        }

        .status-btn.maintenance {
            background: #e6f7e6;
            color: #166534;
            border-color: #86efac;
        }

        .status-btn.maintenance:hover {
            background: #d1fae5;
        }

        .delete-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            background: #fef2f2;
            transition: all 0.2s;
            border: 1px solid #fecaca;
        }

        .delete-btn:hover {
            background: #fee2e2;
        }

        /* Enhanced Modal */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.5); backdrop-filter: blur(4px);
            z-index: 200; padding: 1.5rem;
            align-items: center; justify-content: center;
        }
        .modal-backdrop.show { display: flex; animation: fadeIn 0.2s ease; }
        
        .modal-box {
            background: white; border-radius: 32px; width: 100%;
            max-width: 440px; padding: 2.5rem;
            animation: slideUp 0.3s ease;
            box-shadow: 0 30px 40px -15px rgba(0, 0, 0, 0.2);
        }

        .modal-box h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
        }

        .modal-box p {
            color: #64748b;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .modal-box .close-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .modal-box .close-btn:hover {
            background: #e2e8f0;
            transform: rotate(90deg);
        }

        /* Enhanced Form Elements */
        input, select {
            width: 100%; padding: 1rem 1.25rem;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #ffffff;
            color: #1e293b;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .modal-btn {
            flex: 1;
            padding: 1rem;
            border-radius: 100px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .modal-btn.cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .modal-btn.cancel:hover {
            background: #e2e8f0;
        }

        .modal-btn.save {
            background: #0f172a;
            color: white;
        }

        .modal-btn.save:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -8px rgba(15, 23, 42, 0.4);
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 4rem;
            text-align: center;
            background: #fafafa;
            border-radius: 24px;
            border: 1px dashed #cbd5e1;
        }

        .empty-state i {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.2rem;
            font-weight: 600;
            color: #64748b;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .add-btn {
                width: 100%;
                justify-content: center;
            }
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
    
    $userId = session()->get('user_id');
    ?>

    <!-- Original Sidebar - COMPLETELY UNCHANGED -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
                <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = (isset($page) && $page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
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

    <!-- Original Mobile Nav - UNCHANGED -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = (isset($page) && $page == $item['key']);
                $btnClass = $isActive ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
            ?>
                <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Enhanced Main Content -->
    <main class="main-content">
        <div class="page-header">
            <div>
                <h2>Manage PCs</h2>
                <p>Monitor and manage station availability.</p>
            </div>
            <button onclick="openModal()" class="add-btn">
                <i class="fa-solid fa-plus"></i>
                Add New PC
            </button>
        </div>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($pcs as $pc): ?>
                <div class="pc-card">
                    <div class="flex justify-between items-start mb-6">
                        <div class="pc-icon">
                            <i class="fa-solid fa-desktop text-xl"></i>
                        </div>
                        <span class="status-badge <?= $pc['status'] == 'available' ? 'available' : 'maintenance' ?>">
                            <?= $pc['status'] ?>
                        </span>
                    </div>

                    <h3>Station <?= htmlspecialchars($pc['pc_number']) ?></h3>
                    <p class="asset-id">Asset ID: #<?= str_pad($pc['id'], 4, '0', STR_PAD_LEFT) ?></p>

                    <div class="pc-actions">
                        <form action="/admin/update-pc-status" method="POST" class="flex-1">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                            <input type="hidden" name="status" value="<?= $pc['status'] == 'available' ? 'maintenance' : 'available' ?>">
                            <button type="submit" class="status-btn <?= $pc['status'] == 'available' ? 'available' : 'maintenance' ?>">
                                <?= $pc['status'] == 'available' ? 'Set Maintenance' : 'Set Available' ?>
                            </button>
                        </form>

                        <a href="/admin/delete-pc/<?= $pc['id'] ?>"
                            onclick="return confirm('Delete this PC?')"
                            class="delete-btn">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($pcs)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-desktop"></i>
                    <p>No stations added yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Enhanced Modal -->
    <div id="addPcModal" class="modal-backdrop" onclick="handleBackdrop(event)">
        <div class="modal-box">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3>New Station</h3>
                    <p>Add a PC to the asset pool.</p>
                </div>
                <button onclick="closeModal()" class="close-btn">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="/admin/add-pc" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-5">
                    <label class="block text-[0.68rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">PC Number / Name</label>
                    <input type="text" name="pc_number" required placeholder="e.g. PC-01 or Lab Station 3" value="<?= old('pc_number') ?>">
                </div>

                <div class="mb-5">
                    <label class="block text-[0.68rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">Initial Status</label>
                    <select name="status">
                        <option value="available" <?= old('status') == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="maintenance" <?= old('status') == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" onclick="closeModal()" class="modal-btn cancel">Cancel</button>
                    <button type="submit" class="modal-btn save">Save Asset</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('addPcModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            document.getElementById('addPcModal').classList.remove('show');
            document.body.style.overflow = '';
        }
        
        function handleBackdrop(e) {
            if (e.target === document.getElementById('addPcModal')) closeModal();
        }
        
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
        
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>