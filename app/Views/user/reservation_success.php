<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Submitted | SK Reserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

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

        .sidebar-header { flex-shrink: 0; padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }

        .sidebar-item { transition: all 0.2s; }
        .sidebar-item.active {
            background: #16a34a;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(22, 163, 74, 0.3);
        }

        .mobile-nav-pill {
            position: fixed;
            bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 600px;
            background: rgba(20, 83, 45, 0.98);
            backdrop-filter: blur(12px);
            border-radius: 24px; padding: 6px;
            z-index: 100;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .mobile-scroll-container {
            display: flex; gap: 4px; overflow-x: auto;
            scroll-behavior: smooth; -webkit-overflow-scrolling: touch;
        }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        .success-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .pending-icon {
            width: 80px; height: 80px;
            background: #fef9c3;
            border: 3px solid #fde047;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            animation: softPulse 2.5s infinite ease-in-out;
        }

        @keyframes softPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.25); }
            50%       { box-shadow: 0 0 0 12px rgba(234, 179, 8, 0); }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.65rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            font-size: 0.68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: #94a3b8;
        }
        .detail-value { font-weight: 700; color: #1e293b; font-size: 0.88rem; text-align: right; }

        .btn-primary {
            background: #16a34a; color: white; border: none;
            padding: 0.875rem 1.5rem; border-radius: 16px;
            font-weight: 800; font-size: 0.9rem;
            cursor: pointer; transition: all 0.25s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: inline-flex; align-items: center;
            justify-content: center; gap: 8px;
            text-decoration: none; width: 100%;
        }
        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(22, 163, 74, 0.35);
        }

        .btn-secondary {
            background: white; color: #475569;
            border: 1.5px solid #e2e8f0;
            padding: 0.875rem 1.5rem; border-radius: 16px;
            font-weight: 700; font-size: 0.9rem;
            cursor: pointer; transition: all 0.25s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: inline-flex; align-items: center;
            justify-content: center; gap: 8px;
            text-decoration: none; width: 100%;
        }
        .btn-secondary:hover {
            border-color: #16a34a; color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .step-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 0.6rem 0;
        }
        .step-dot {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 11px; font-weight: 800;
        }
    </style>
</head>
<body class="flex">

    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/profile',          'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];
    ?>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Resident Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">my<span class="text-green-600">Space.</span></h1>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = (isset($page) && $page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- Mobile Nav -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = (isset($page) && $page == $item['key']);
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main -->
    <main class="flex-1 p-6 lg:p-12 pb-32 flex items-start justify-center">
        <div class="success-card">

            <!-- Pending Icon -->
            <div class="pending-icon">
                <i class="fa-regular fa-hourglass-half text-3xl text-yellow-500"></i>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-black text-slate-900 mb-1">Reservation Submitted!</h1>
                <p class="text-slate-500 font-medium">Your request is pending approval from an SK officer.</p>
            </div>

            <!-- Reservation Details -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-amber-600 text-xs"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-sm tracking-tight">Reservation Details</h3>
                    <span class="ml-auto text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 px-3 py-1 rounded-full">Pending</span>
                </div>

                <div>
                    <div class="detail-row">
                        <span class="detail-label">Reservation ID</span>
                        <span class="detail-value font-mono text-slate-500">#<?= $reservation['id'] ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Resource</span>
                        <span class="detail-value"><?= esc($reservation['resource_name'] ?? 'Resource') ?></span>
                    </div>
                    <?php if (!empty($reservation['pc_number'])): ?>
                    <div class="detail-row">
                        <span class="detail-label">Workstation</span>
                        <span class="detail-value"><?= esc($reservation['pc_number']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value"><?= date('F j, Y', strtotime($reservation['reservation_date'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time</span>
                        <span class="detail-value">
                            <?= date('g:i A', strtotime($reservation['start_time'])) ?> – <?= date('g:i A', strtotime($reservation['end_time'])) ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Purpose</span>
                        <span class="detail-value"><?= esc($reservation['purpose'] ?? '—') ?></span>
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-green-600 text-xs"></i>
                    </div>
                    <h4 class="font-extrabold text-slate-800 text-sm">What happens next?</h4>
                </div>

                <div class="space-y-1">
                    <div class="step-item">
                        <div class="step-dot bg-amber-100 text-amber-700">1</div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Waiting for review</p>
                            <p class="text-xs text-slate-400 mt-0.5">An SK officer will review your reservation request</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-dot bg-slate-100 text-slate-400">2</div>
                        <div>
                            <p class="font-bold text-slate-400 text-sm">Approval notification</p>
                            <p class="text-xs text-slate-400 mt-0.5">You'll get a bell notification and email once approved</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-dot bg-slate-100 text-slate-400">3</div>
                        <div>
                            <p class="font-bold text-slate-400 text-sm">E-ticket released</p>
                            <p class="text-xs text-slate-400 mt-0.5">Your QR e-ticket will be available after approval</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                <a href="<?= base_url('/reservation-list') ?>" class="btn-secondary">
                    <i class="fa-regular fa-calendar"></i> My Reservations
                </a>
                <a href="<?= base_url('/dashboard') ?>" class="btn-primary">
                    <i class="fa-solid fa-house"></i> Go to Dashboard
                </a>
            </div>

            <!-- Quick Links -->
            <div class="flex justify-center gap-5 text-xs text-slate-400 pt-2 border-t border-slate-100">
                <a href="<?= base_url('/reservation') ?>" class="hover:text-green-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
                <span class="text-slate-200">|</span>
                <a href="<?= base_url('/reservation-list') ?>" class="hover:text-green-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa-regular fa-clock"></i> Check Status
                </a>
            </div>

        </div>
    </main>

</body>
</html>