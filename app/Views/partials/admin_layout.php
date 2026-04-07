<?php
/**
 * Views/admin/partials/layout.php
 *
 * Shared layout partial for all Admin views.
 *
 * USAGE
 * ─────
 * 1. Set $page to the current route key before including:
 *      $page = 'dashboard';
 *
 * 2. Include immediately after <body>:
 *      <?php include APPPATH . 'Views/admin/partials/layout.php'; ?>
 *
 * 3. Wrap page content in:
 *      <main class="main-area"> ... </main>
 *
 * VARIABLES (all optional with defaults)
 * ───────────────────────────────────────
 *   $page              string   active nav key
 *   $user_name         string   display name  (falls back to session)
 *   $pendingCount      int      badge for Reservations nav item
 *   $pendingSkCount    int      badge for Manage SK nav item
 *   $pendingBorrowings int      badge for Library nav item
 */

$page     = $page     ?? '';
$userName = $user_name
    ?? $admin_name
    ?? session()->get('name')
    ?? session()->get('username')
    ?? 'Administrator';

$pendingCount      = (int)($pendingCount      ?? $pending ?? 0);
$pendingSkCount    = (int)($pendingSkCount    ?? 0);
$pendingBorrowings = (int)($pendingBorrowings ?? 0);

$avatarLetter = strtoupper(mb_substr(trim($userName), 0, 1)) ?: 'A';

$navItems = [
    ['url' => '/admin/dashboard',           'icon' => 'fa-house',        'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',         'label' => 'New Reservation', 'key' => 'new-reservation'],
    ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',     'label' => 'Reservations',    'key' => 'manage-reservations'],
    ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',      'label' => 'Manage PCs',      'key' => 'manage-pcs'],
    ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',  'label' => 'Manage SK',       'key' => 'manage-sk'],
    ['url' => '/admin/books',               'icon' => 'fa-book-open',    'label' => 'Library',         'key' => 'books'],
    ['url' => '/admin/login-logs',          'icon' => 'fa-clock',        'label' => 'Login Logs',      'key' => 'login-logs'],
    ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',       'label' => 'Scanner',         'key' => 'scanner'],
    ['url' => '/admin/activity-logs',       'icon' => 'fa-list',         'label' => 'Activity Logs',   'key' => 'activity-logs'],
    ['url' => '/admin/profile',             'icon' => 'fa-user',         'label' => 'Profile',         'key' => 'profile'],
];

// Mobile nav: 3 pinned items (Dashboard, New Reservation, Profile)
// Everything else goes in the "More" drawer
$mobilePin  = ['dashboard', 'new-reservation', 'profile'];
$mobileMore = array_filter($navItems, fn($i) => !in_array($i['key'], $mobilePin));

// Check if the current page is in the "More" drawer
$moreIsActive = !in_array($page, $mobilePin) && $page !== '';
?>

<script>
(function () {
    try {
        if (localStorage.getItem('admin_theme') === 'dark') {
            document.documentElement.classList.add('dark-pre');
            document.body.classList.add('dark');
        }
    } catch (e) {}
})();
</script>

<link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- ══ SIDEBAR ══ -->
<aside class="l-sidebar" role="navigation" aria-label="Admin navigation">
    <div class="l-sidebar__inner">

        <div class="l-sidebar__top">
            <div class="l-brand-tag">Admin Control Room</div>
            <div class="l-brand-name">my<em>Space.</em></div>
            <div class="l-brand-sub">Administration Panel</div>
        </div>

        <div class="l-user-card">
            <div class="l-user-avatar" aria-hidden="true"><?= htmlspecialchars($avatarLetter) ?></div>
            <div class="l-user-info">
                <div class="l-user-name"><?= htmlspecialchars($userName) ?></div>
                <div class="l-user-role">Administrator</div>
            </div>
        </div>

        <nav class="l-nav" aria-label="Sidebar navigation">
            <div class="l-nav__section">Menu</div>

            <?php foreach ($navItems as $item):
                $isActive   = ($page === $item['key']);
                $badgeCount = 0;
                if ($item['key'] === 'manage-reservations') $badgeCount = $pendingCount;
                if ($item['key'] === 'manage-sk')           $badgeCount = $pendingSkCount;
                if ($item['key'] === 'books')               $badgeCount = $pendingBorrowings;
            ?>
                <a href="<?= $item['url'] ?>"
                   class="l-nav__link<?= $isActive ? ' is-active' : '' ?>"
                   <?= $isActive ? 'aria-current="page"' : '' ?>>
                    <div class="l-nav__icon" aria-hidden="true">
                        <i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem;"></i>
                    </div>
                    <?= htmlspecialchars($item['label']) ?>
                    <?php if ($badgeCount > 0): ?>
                        <span class="l-nav__badge" aria-label="<?= $badgeCount ?> pending">
                            <?= $badgeCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="l-sidebar__footer">
            <a href="/logout" class="l-logout">
                <div class="l-logout__icon" aria-hidden="true">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171;"></i>
                </div>
                Sign Out
            </a>
        </div>

    </div>
</aside>

<!-- ══ MOBILE NAV ══ -->
<nav class="l-mobile-nav" aria-label="Mobile navigation">
    <div class="l-mobile-nav__inner">

        <?php foreach ($navItems as $item):
            if (!in_array($item['key'], $mobilePin)) continue;
            $isActive   = ($page === $item['key']);
            $badgeCount = 0;
            if ($item['key'] === 'manage-reservations') $badgeCount = $pendingCount;
            if ($item['key'] === 'manage-sk')           $badgeCount = $pendingSkCount;
            if ($item['key'] === 'books')               $badgeCount = $pendingBorrowings;
        ?>
            <a href="<?= $item['url'] ?>"
               class="l-mobile-nav__item<?= $isActive ? ' is-active' : '' ?>"
               aria-current="<?= $isActive ? 'page' : 'false' ?>">
                <div class="l-mobile-nav__icon-wrap">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
                    <?php if ($badgeCount > 0): ?>
                        <span class="l-mobile-nav__dot" aria-hidden="true"></span>
                    <?php endif; ?>
                </div>
                <span class="l-mobile-nav__label"><?= htmlspecialchars($item['label']) ?></span>
            </a>
        <?php endforeach; ?>

        <!-- More button -->
        <button type="button"
                class="l-mobile-nav__item l-mobile-nav__more-btn<?= $moreIsActive ? ' is-active' : '' ?>"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="mobileMoreDrawer"
                onclick="adminToggleMoreDrawer()">
            <div class="l-mobile-nav__icon-wrap">
                <i class="fa-solid fa-ellipsis"></i>
                <?php
                    $moreBadgeTotal = $pendingCount + $pendingSkCount + $pendingBorrowings;
                    if ($moreBadgeTotal > 0 && !in_array($page, ['manage-reservations','manage-sk','books'])):
                ?>
                    <span class="l-mobile-nav__dot" aria-hidden="true"></span>
                <?php endif; ?>
            </div>
            <span class="l-mobile-nav__label">More</span>
        </button>

    </div>
</nav>

<!-- ══ MORE DRAWER ══ -->
<div id="mobileMoreDrawer"
     class="l-more-drawer"
     role="dialog"
     aria-modal="true"
     aria-label="More navigation options"
     hidden>

    <div class="l-more-drawer__backdrop" onclick="adminToggleMoreDrawer()"></div>

    <div class="l-more-drawer__sheet">
        <div class="l-more-drawer__handle" aria-hidden="true"></div>
        <div class="l-more-drawer__title">More</div>

        <div class="l-more-drawer__grid">
            <?php foreach ($mobileMore as $item):
                $isActive   = ($page === $item['key']);
                $badgeCount = 0;
                if ($item['key'] === 'manage-reservations') $badgeCount = $pendingCount;
                if ($item['key'] === 'manage-sk')           $badgeCount = $pendingSkCount;
                if ($item['key'] === 'books')               $badgeCount = $pendingBorrowings;
            ?>
                <a href="<?= $item['url'] ?>"
                   class="l-more-drawer__item<?= $isActive ? ' is-active' : '' ?>"
                   <?= $isActive ? 'aria-current="page"' : '' ?>>
                    <div class="l-more-drawer__icon-wrap">
                        <i class="fa-solid <?= $item['icon'] ?>"></i>
                        <?php if ($badgeCount > 0): ?>
                            <span class="l-more-drawer__badge" aria-label="<?= $badgeCount ?> pending">
                                <?= $badgeCount > 9 ? '9+' : $badgeCount ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="l-more-drawer__label"><?= htmlspecialchars($item['label']) ?></span>
                </a>
            <?php endforeach; ?>

            <a href="/logout" class="l-more-drawer__item l-more-drawer__item--logout">
                <div class="l-more-drawer__icon-wrap">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <span class="l-more-drawer__label">Sign Out</span>
            </a>
        </div>
    </div>
</div>

<!-- ══ DARK MODE + DRAWER JS ══ -->
<script>
(function () {
    'use strict';

    var MOON = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    var SUN  = '<i class="fa-regular fa-sun"  style="font-size:.85rem;"></i>';

    function applyDark(isDark) {
        document.body.classList.toggle('dark', isDark);
        document.documentElement.classList.remove('dark-pre');
        document.querySelectorAll('#darkIcon, [data-dark-icon]').forEach(function (el) {
            el.innerHTML = isDark ? MOON : SUN;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyDark(localStorage.getItem('admin_theme') === 'dark');
    });

    window.adminToggleDark = function () {
        var isDark = !document.body.classList.contains('dark');
        localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
        applyDark(isDark);
    };

    window.toggleDark = window.adminToggleDark;

    /* ── More drawer ── */
    var _drawerOpen = false;

    window.adminToggleMoreDrawer = function () {
        var drawer  = document.getElementById('mobileMoreDrawer');
        var btn     = document.querySelector('.l-mobile-nav__more-btn');
        if (!drawer) return;

        _drawerOpen = !_drawerOpen;
        drawer.hidden = !_drawerOpen;
        if (btn) btn.setAttribute('aria-expanded', _drawerOpen ? 'true' : 'false');

        // Animate in on next frame
        if (_drawerOpen) {
            requestAnimationFrame(function () {
                drawer.classList.add('is-open');
            });
            document.addEventListener('keydown', _drawerEscHandler);
        } else {
            drawer.classList.remove('is-open');
            document.removeEventListener('keydown', _drawerEscHandler);
        }
    };

    function _drawerEscHandler(e) {
        if (e.key === 'Escape') window.adminToggleMoreDrawer();
    }
})();
</script>