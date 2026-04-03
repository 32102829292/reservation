<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>Reservation Submitted | SK Reserve</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --indigo: #3730a3;
            --indigo-light: #eef2ff;
            --indigo-border: #c7d2fe;
            --bg: #f0f2f9;
            --card: #ffffff;
            --font: 'Plus Jakarta Sans', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --shadow-sm: 0 1px 4px rgba(15, 23, 42, .07), 0 1px 2px rgba(15, 23, 42, .04);
            --shadow-md: 0 4px 16px rgba(15, 23, 42, .09), 0 2px 4px rgba(15, 23, 42, .04);
            --shadow-lg: 0 12px 40px rgba(15, 23, 42, .12), 0 4px 8px rgba(15, 23, 42, .06);
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 20px;
            --r-xl: 24px;
            --ease: .18s cubic-bezier(.4, 0, .2, 1);
            --mob-nav-h: 60px;
            --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
        }

        html {
            height: 100%;
            font-size: 16px;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: #0f172a;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .sidebar {
            width: 268px;
            flex-shrink: 0;
            padding: 18px 14px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
        }

        .sidebar-inner {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(99, 102, 241, .1);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .sidebar-top {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
        }

        .brand-tag {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.03em;
            line-height: 1.1;
        }

        .brand-name em {
            font-style: normal;
            color: var(--indigo);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .nav-lbl {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #cbd5e1;
            padding: 10px 10px 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 600;
            color: #64748b;
            text-decoration: none;
            transition: all var(--ease);
        }

        .nav-link:hover {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .nav-link.active {
            background: var(--indigo);
            color: #fff;
            box-shadow: 0 4px 14px rgba(55, 48, 163, .32);
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f1f5f9;
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, .15);
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: #e0e7ff;
        }

        .sidebar-footer {
            padding: 10px 10px 12px;
            border-top: 1px solid rgba(99, 102, 241, .07);
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            transition: all var(--ease);
        }

        .logout-link:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .mobile-nav-pill {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: white;
            border-top: 1px solid rgba(99, 102, 241, .1);
            height: var(--mob-nav-total);
            z-index: 200;
            box-shadow: 0 -4px 20px rgba(55, 48, 163, .1);
        }

        .mobile-scroll-container {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            height: var(--mob-nav-h);
            width: 100%;
        }

        .mob-nav-item {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            border-radius: 14px;
            text-decoration: none;
            color: #64748b;
            position: relative;
            transition: background .15s, color .15s;
        }

        .mob-nav-item:hover,
        .mob-nav-item.active {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .mob-nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--indigo);
            border-radius: 50%;
        }

        .mob-logout {
            color: #94a3b8;
        }

        @media(max-width:1023px) {
            .sidebar {
                display: none !important;
            }

            .mobile-nav-pill {
                display: flex !important;
            }

            .main-area {
                padding-bottom: calc(var(--mob-nav-total)+16px) !important;
            }
        }

        @media(min-width:1024px) {
            .sidebar {
                display: flex !important;
            }

            .mobile-nav-pill {
                display: none !important;
            }
        }

        .main-area {
            flex: 1;
            min-width: 0;
            padding: 28px 28px 40px;
            overflow-x: hidden;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        @media(max-width:639px) {
            .main-area {
                padding: 16px 14px 0;
            }
        }

        .success-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 28px;
            width: 100%;
            max-width: 560px;
            animation: slideUp .4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .pending-icon {
            width: 72px;
            height: 72px;
            background: #fef9c3;
            border: 2px solid #fde047;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            animation: pulse 2.5s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(234, 179, 8, .25)
            }

            50% {
                box-shadow: 0 0 0 10px rgba(234, 179, 8, 0)
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
            gap: 1rem;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: .6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .detail-value {
            font-weight: 600;
            color: #0f172a;
            font-size: .84rem;
            text-align: right;
        }

        .btn-primary {
            background: var(--indigo);
            color: white;
            border: none;
            padding: .8rem 1.5rem;
            border-radius: var(--r-md);
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            transition: all var(--ease);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(55, 48, 163, .28);
        }

        .btn-primary:hover {
            background: #312e81;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--card);
            color: #64748b;
            border: 1px solid rgba(99, 102, 241, .12);
            padding: .8rem 1.5rem;
            border-radius: var(--r-md);
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            transition: all var(--ease);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            border-color: var(--indigo-border);
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
        }

        .step-item:last-child {
            border-bottom: none;
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .68rem;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <?php
    $navItems = [
        ['url' => '/dashboard',        'icon' => 'fa-house',     'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',      'icon' => 'fa-plus',      'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',  'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/books',            'icon' => 'fa-book-open', 'label' => 'Library',         'key' => 'books'],
        ['url' => '/profile',          'icon' => 'fa-user',      'label' => 'Profile',         'key' => 'profile'],
    ];
    $page = $page ?? '';
    ?>
    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">Resident Portal</div>
                <div class="brand-name">my<em>Space.</em></div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-lbl">Menu</div>
                <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:14px;color:<?= $active ? 'white' : '#64748b' ?>;"></i></div>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:14px;color:#f87171;"></i></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item): $active = ($page == $item['key']); ?>
                <a href="<?= base_url($item['url']) ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>">
                    <i class="fa-solid <?= $item['icon'] ?>" style="font-size:20px;"></i>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-logout">
                <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:20px;"></i>
            </a>
        </div>
    </nav>

    <main class="main-area">
        <div class="success-card">
            <div class="pending-icon">
                <i class="fa-regular fa-hourglass-half" style="font-size:1.8rem;color:#ca8a04;"></i>
            </div>
            <div style="text-align:center;margin-bottom:24px;">
                <h1 style="font-size:1.4rem;font-weight:800;color:#0f172a;letter-spacing:-.03em;margin-bottom:6px;">Reservation Submitted!</h1>
                <p style="font-size:.82rem;color:#94a3b8;font-weight:500;">Your request is pending approval from an SK officer.</p>
            </div>

            <!-- Details -->
            <div style="background:#f8fafc;border-radius:var(--r-md);padding:16px;border:1px solid rgba(99,102,241,.08);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div style="width:28px;height:28px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-receipt" style="color:#d97706;font-size:11px;"></i>
                    </div>
                    <span style="font-size:.78rem;font-weight:700;color:#0f172a;">Reservation Details</span>
                    <span style="margin-left:auto;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:999px;">Pending</span>
                </div>
                <div class="detail-row"><span class="detail-label">Reservation ID</span><span class="detail-value" style="font-family:var(--mono);color:#94a3b8;">#<?= $reservation['id'] ?></span></div>
                <div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value"><?= esc($reservation['resource_name'] ?? 'Resource') ?></span></div>
                <?php if (!empty($reservation['pc_number'])): ?>
                    <div class="detail-row"><span class="detail-label">Workstation</span><span class="detail-value"><?= esc($reservation['pc_number']) ?></span></div>
                <?php endif; ?>
                <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value"><?= date('F j, Y', strtotime($reservation['reservation_date'])) ?></span></div>
                <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value"><?= date('g:i A', strtotime($reservation['start_time'])) ?> – <?= date('g:i A', strtotime($reservation['end_time'])) ?></span></div>
                <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value"><?= esc($reservation['purpose'] ?? '—') ?></span></div>
            </div>

            <!-- What's next -->
            <div style="background:var(--card);border-radius:var(--r-md);padding:16px;border:1px solid rgba(99,102,241,.08);margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-list-check" style="color:var(--indigo);font-size:11px;"></i>
                    </div>
                    <span style="font-size:.78rem;font-weight:700;color:#0f172a;">What happens next?</span>
                </div>
                <div class="step-item">
                    <div class="step-num" style="background:#fef3c7;color:#92400e;">1</div>
                    <div>
                        <p style="font-weight:700;font-size:.82rem;color:#0f172a;">Waiting for review</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">An SK officer will review your request</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num" style="background:#f1f5f9;color:#94a3b8;">2</div>
                    <div>
                        <p style="font-weight:700;font-size:.82rem;color:#94a3b8;">Approval notification</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">You'll get a notification once approved</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num" style="background:#f1f5f9;color:#94a3b8;">3</div>
                    <div>
                        <p style="font-weight:700;font-size:.82rem;color:#94a3b8;">E-ticket released</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Your QR e-ticket will be available after approval</p>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                <a href="<?= base_url('/reservation-list') ?>" class="btn-secondary"><i class="fa-regular fa-calendar"></i> My Reservations</a>
                <a href="<?= base_url('/dashboard') ?>" class="btn-primary"><i class="fa-solid fa-house"></i> Dashboard</a>
            </div>
            <div style="display:flex;justify-content:center;gap:20px;padding-top:12px;border-top:1px solid rgba(99,102,241,.08);">
                <a href="<?= base_url('/reservation') ?>" style="font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;display:flex;align-items:center;gap:4px;"><i class="fa-solid fa-plus" style="font-size:10px;"></i> New Reservation</a>
                <span style="color:#e2e8f0;">|</span>
                <a href="<?= base_url('/reservation-list') ?>" style="font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;display:flex;align-items:center;gap:4px;"><i class="fa-regular fa-clock" style="font-size:10px;"></i> Check Status</a>
            </div>
        </div>
    </main>
</body>

</html>