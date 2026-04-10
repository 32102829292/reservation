<?php
/**
 * Views/admin/partials/layout.php
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

// First 4 items go on the bottom bar, rest go in the drawer
$primaryTabs = array_slice($navItems, 0, 4);
$drawerItems = array_slice($navItems, 4);

// Is the active page in the drawer?
$activeInDrawer = in_array($page, array_column($drawerItems, 'key'));

// Helper: get badge count for a nav key
$getBadge = function(string $key) use ($pendingCount, $pendingSkCount, $pendingBorrowings): int {
    if ($key === 'manage-reservations') return $pendingCount;
    if ($key === 'manage-sk')           return $pendingSkCount;
    if ($key === 'books')               return $pendingBorrowings;
    return 0;
};
?>

<!-- ═══════════════════════════════════════════════════════════
     DARK MODE FOUC GUARD
═══════════════════════════════════════════════════════════════ -->
<script>
(function(){
    try {
        if (localStorage.getItem('admin_theme') === 'dark') {
            document.documentElement.classList.add('dark-pre');
            document.body.classList.add('dark');
        }
    } catch(e){}
})();
</script>

<!-- ═══════════════════════════════════════════════════════════
     FONTS — loaded via <link> (faster than @import in CSS)
═══════════════════════════════════════════════════════════════ -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap">

<!-- ═══════════════════════════════════════════════════════════
     STYLESHEETS
═══════════════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- Ensure font propagates to all form elements -->
<style>
  input, button, select, textarea, optgroup {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
  }
</style>

<!-- ═══════════════════════════════════════════════════════════
     SHELL OPEN
═══════════════════════════════════════════════════════════════ -->
<div class="l-shell">

<!-- ═══════════════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════════════════ -->
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
                $badgeCount = $getBadge($item['key']);
            ?>
            <a href="<?= $item['url'] ?>"
               class="l-nav__link<?= $isActive ? ' is-active' : '' ?>"
               <?= $isActive ? 'aria-current="page"' : '' ?>>
                <div class="l-nav__icon" aria-hidden="true">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
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
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                Sign Out
            </a>
        </div>

    </div>
</aside>

<!-- ═══════════════════════════════════════════════════════════
     MOBILE BOTTOM NAV
═══════════════════════════════════════════════════════════════ -->
<nav class="l-mobile-nav" aria-label="Mobile navigation">
    <div class="l-mobile-nav__inner">

        <?php foreach ($primaryTabs as $item):
            $isActive   = ($page === $item['key']);
            $badgeCount = $getBadge($item['key']);
        ?>
        <a href="<?= $item['url'] ?>"
           class="l-mn__tab<?= $isActive ? ' is-active' : '' ?>"
           <?= $isActive ? 'aria-current="page"' : '' ?>>
            <div class="l-mn__icon-wrap">
                <i class="fa-solid <?= $item['icon'] ?>"></i>
                <?php if ($badgeCount > 0): ?>
                    <span class="l-mn__dot" aria-hidden="true"></span>
                <?php endif; ?>
            </div>
            <span class="l-mn__label"><?= htmlspecialchars($item['label']) ?></span>
        </a>
        <?php endforeach; ?>

        <!-- More tab — opens the drawer -->
        <button type="button"
                class="l-mn__tab<?= $activeInDrawer ? ' is-active' : '' ?>"
                onclick="lDrawerOpen()"
                aria-haspopup="true"
                aria-expanded="false"
                id="l-more-btn">
            <div class="l-mn__icon-wrap">
                <i class="fa-solid fa-ellipsis"></i>
                <?php
                    // Show dot on More if the active page is in the drawer
                    $drawerBadgeTotal = 0;
                    foreach ($drawerItems as $di) $drawerBadgeTotal += $getBadge($di['key']);
                    if ($drawerBadgeTotal > 0):
                ?>
                    <span class="l-mn__dot" aria-hidden="true"></span>
                <?php endif; ?>
            </div>
            <span class="l-mn__label">More</span>
        </button>

    </div>
</nav>

<!-- ═══════════════════════════════════════════════════════════
     DRAWER BACKDROP
═══════════════════════════════════════════════════════════════ -->
<div class="l-drawer-backdrop" id="l-drawer-backdrop" onclick="lDrawerClose()"></div>

<!-- ═══════════════════════════════════════════════════════════
     DRAWER PANEL
═══════════════════════════════════════════════════════════════ -->
<div class="l-drawer" id="l-drawer" role="dialog" aria-modal="true" aria-label="More navigation">
    <div class="l-drawer__handle"></div>

    <div class="l-drawer__section-label">More</div>

    <?php foreach ($drawerItems as $item):
        $isActive   = ($page === $item['key']);
        $badgeCount = $getBadge($item['key']);
    ?>
    <a href="<?= $item['url'] ?>"
       class="l-drawer__item<?= $isActive ? ' is-active' : '' ?>"
       <?= $isActive ? 'aria-current="page"' : '' ?>>
        <div class="l-drawer__icon">
            <i class="fa-solid <?= $item['icon'] ?>"></i>
        </div>
        <span class="l-drawer__name"><?= htmlspecialchars($item['label']) ?></span>
        <?php if ($badgeCount > 0): ?>
            <span class="l-drawer__badge" aria-label="<?= $badgeCount ?> pending">
                <?= $badgeCount > 9 ? '9+' : $badgeCount ?>
            </span>
        <?php endif; ?>
        <i class="fa-solid fa-chevron-right l-drawer__chev"></i>
    </a>
    <?php endforeach; ?>

    <div class="l-drawer__divider"></div>

    <div class="l-drawer__section-label">Account</div>

    <a href="/logout" class="l-drawer__item l-drawer__item--logout">
        <div class="l-drawer__icon l-drawer__icon--logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <span class="l-drawer__name">Sign Out</span>
        <i class="fa-solid fa-chevron-right l-drawer__chev"></i>
    </a>
</div>

<!-- ═══════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════════ -->
<script>
(function(){
    'use strict';

    /* ── Dark mode ── */
    var MOON = '<i class="fa-regular fa-moon"  style="font-size:.85rem;"></i>';
    var SUN  = '<i class="fa-regular fa-sun"   style="font-size:.85rem;"></i>';

    function applyDark(isDark) {
        document.body.classList.toggle('dark', isDark);
        document.documentElement.classList.remove('dark-pre');
        document.querySelectorAll('#darkIcon, [data-dark-icon]').forEach(function(el){
            el.innerHTML = isDark ? MOON : SUN;
        });
    }

    document.addEventListener('DOMContentLoaded', function(){
        applyDark(localStorage.getItem('admin_theme') === 'dark');
    });

    window.adminToggleDark = function(){
        var isDark = !document.body.classList.contains('dark');
        try { localStorage.setItem('admin_theme', isDark ? 'dark' : 'light'); } catch(e){}
        applyDark(isDark);
    };

    /* Keep both names working in case any page calls either */
    window.toggleDark        = window.adminToggleDark;
    window.layoutToggleDark  = window.adminToggleDark;

    /* ── Drawer (identical logic to SK layout) ── */
    var drawer   = null;
    var backdrop = null;
    var moreBtn  = null;

    function initDrawer() {
        drawer   = document.getElementById('l-drawer');
        backdrop = document.getElementById('l-drawer-backdrop');
        moreBtn  = document.getElementById('l-more-btn');
    }

    window.lDrawerOpen = function() {
        if (!drawer) initDrawer();
        drawer.classList.add('is-open');
        backdrop.classList.add('show');
        if (moreBtn) moreBtn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    };

    window.lDrawerClose = function() {
        if (!drawer) return;
        drawer.classList.remove('is-open');
        backdrop.classList.remove('show');
        if (moreBtn) moreBtn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    };

    /* Close on Escape */
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') window.lDrawerClose();
    });

    /* Swipe-down to close */
    var touchStartY = 0;
    document.addEventListener('touchstart', function(e){
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    document.addEventListener('touchend', function(e){
        if (!drawer || !drawer.classList.contains('is-open')) return;
        if (e.changedTouches[0].clientY - touchStartY > 60) window.lDrawerClose();
    }, { passive: true });

})();
</script>