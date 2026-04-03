<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>My E-Tickets</title>
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
        }

        @media(max-width:639px) {
            .main-area {
                padding: 16px 14px 0;
            }
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.04em;
            line-height: 1.1;
        }

        .page-sub {
            font-size: .8rem;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 500;
        }

        .ticket-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 14px;
            transition: transform var(--ease), box-shadow var(--ease);
        }

        .ticket-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .ticket-top {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(99, 102, 241, .06);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .ticket-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ticket-body {
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .ticket-info {
            flex: 1;
            min-width: 200px;
        }

        .ticket-qr {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .ticket-code {
            font-family: var(--mono);
            font-size: .72rem;
            color: #94a3b8;
            background: #f8fafc;
            border: 1px solid rgba(99, 102, 241, .1);
            border-radius: 8px;
            padding: 4px 10px;
            margin-top: 6px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .info-icon {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-text {
            font-size: .8rem;
            font-weight: 600;
            color: #0f172a;
        }

        .info-label {
            font-size: .6rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .dl-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: .65rem 1.1rem;
            background: var(--indigo);
            color: white;
            border-radius: var(--r-sm);
            font-size: .78rem;
            font-weight: 700;
            text-decoration: none;
            transition: all var(--ease);
            box-shadow: 0 4px 10px rgba(55, 48, 163, .25);
        }

        .dl-btn:hover {
            background: #312e81;
            transform: translateY(-1px);
        }

        .empty-state {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(99, 102, 241, .08);
            padding: 56px 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        @media(max-width:639px) {
            .ticket-body {
                flex-direction: column;
            }

            .ticket-qr {
                align-self: center;
            }
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
    $page = $page ?? 'reservation-list';
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
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap;">
            <div>
                <div style="font-size:.62rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px;">E-Tickets</div>
                <div class="page-title">My Approved E-Tickets</div>
                <div class="page-sub">Show your QR code at the facility entrance.</div>
            </div>
            <a href="<?= base_url('/reservation-list') ?>" style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:var(--card);border:1px solid rgba(99,102,241,.12);border-radius:var(--r-sm);font-size:.8rem;font-weight:700;color:#64748b;text-decoration:none;box-shadow:var(--shadow-sm);margin-top:4px;transition:all var(--ease);">
                <i class="fa-solid fa-chevron-left" style="font-size:11px;"></i> All Reservations
            </a>
        </div>

        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <div style="width:56px;height:56px;background:var(--indigo-light);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fa-solid fa-ticket" style="font-size:1.3rem;color:var(--indigo);"></i>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">No approved e-tickets</h3>
                <p style="font-size:.82rem;color:#94a3b8;">Once your reservation is approved, your QR ticket will appear here.</p>
                <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;padding:.7rem 1.25rem;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.82rem;font-weight:700;text-decoration:none;">
                    <i class="fa-solid fa-plus" style="font-size:11px;"></i> Make a Reservation
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($reservations as $res): ?>
                <div class="ticket-card">
                    <div class="ticket-top">
                        <div class="ticket-icon">
                            <i class="fa-solid fa-ticket" style="color:var(--indigo);font-size:1rem;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.88rem;font-weight:700;color:#0f172a;"><?= esc($res['resource_name'] ?? 'Resource #' . $res['resource_id']) ?></div>
                            <?php if (!empty($res['pc_number'])): ?>
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Workstation: <?= esc($res['pc_number']) ?></div>
                            <?php endif; ?>
                        </div>
                        <span style="display:inline-flex;padding:3px 10px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:#dcfce7;color:#166534;flex-shrink:0;">Approved</span>
                    </div>
                    <div class="ticket-body">
                        <div class="ticket-info">
                            <div class="info-row">
                                <div class="info-icon"><i class="fa-solid fa-calendar" style="color:var(--indigo);font-size:11px;"></i></div>
                                <div>
                                    <div class="info-label">Date</div>
                                    <div class="info-text"><?= date('F j, Y', strtotime($res['reservation_date'])) ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon"><i class="fa-solid fa-clock" style="color:var(--indigo);font-size:11px;"></i></div>
                                <div>
                                    <div class="info-label">Time</div>
                                    <div class="info-text"><?= date('g:i A', strtotime($res['start_time'])) ?> – <?= date('g:i A', strtotime($res['end_time'])) ?></div>
                                </div>
                            </div>
                            <div class="ticket-code"><?= esc($res['e_ticket_code'] ?? '—') ?></div>
                        </div>
                        <div class="ticket-qr">
                            <?php if (isset($res['qr_base64'])): ?>
                                <div style="background:white;padding:8px;border-radius:12px;border:1px solid rgba(99,102,241,.12);box-shadow:var(--shadow-sm);">
                                    <img src="<?= $res['qr_base64'] ?>" style="width:120px;height:120px;display:block;" alt="QR Code">
                                </div>
                            <?php endif; ?>
                            <a href="<?= base_url('/user/downloadTicket/' . $res['id']) ?>" class="dl-btn">
                                <i class="fa-solid fa-download" style="font-size:11px;"></i> Download QR
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>

</html>