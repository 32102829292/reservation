<?php
/**
 * Views/partials/sk_layout.php
 */

$page             = $page             ?? '';
$user_name        = $user_name        ?? ($user['name'] ?? 'Officer');
$pendingUserCount = (int)($pendingUserCount ?? 0);
$maxSlots         = max(1, (int)($maxMonthlySlots ?? 3));
$usedSlots        = (int)($usedThisMonth ?? 0);
$remaining        = $remainingReservations ?? null;
$quotaPct         = $remaining !== null ? min(100, round($usedSlots / $maxSlots * 100)) : 0;
$avatarLetter     = strtoupper(mb_substr(trim($user_name), 0, 1)) ?: 'O';

$navItems = [
    ['url' => '/sk/dashboard',       'icon' => 'fa-house',        'label' => 'Dashboard',        'key' => 'dashboard'],
    ['url' => '/sk/reservations',    'icon' => 'fa-calendar-alt', 'label' => 'All Reservations', 'key' => 'reservations'],
    ['url' => '/sk/new-reservation', 'icon' => 'fa-plus',         'label' => 'New Reservation',  'key' => 'new-reservation'],
    ['url' => '/sk/user-requests',   'icon' => 'fa-users',        'label' => 'User Requests',    'key' => 'user-requests'],
    ['url' => '/sk/my-reservations', 'icon' => 'fa-calendar',     'label' => 'My Reservations',  'key' => 'my-reservations'],
    ['url' => '/sk/books',           'icon' => 'fa-book-open',    'label' => 'Library',          'key' => 'books'],
    ['url' => '/sk/scanner',         'icon' => 'fa-qrcode',       'label' => 'Scanner',          'key' => 'scanner'],
    ['url' => '/sk/profile',         'icon' => 'fa-user',         'label' => 'Profile',          'key' => 'profile'],
];

// Which items land on the bottom bar vs the drawer
$primaryTabs  = array_slice($navItems, 0, 4);
$drawerItems  = array_slice($navItems, 4);

// Is the active page in the drawer? If so, highlight the "More" tab
$activeInDrawer = in_array($page, array_column($drawerItems, 'key'));
?>

<!-- ═══════════════════════════════════════════════════════════
     DARK MODE FOUC GUARD
═══════════════════════════════════════════════════════════════ -->
<script>
(function(){
    try {
        if (localStorage.getItem('sk_theme') === 'dark') {
            document.documentElement.classList.add('dark-pre');
            document.body.classList.add('dark');
        }
    } catch(e){}
})();
</script>
<link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- ═══════════════════════════════════════════════════════════
     SHELL OPEN
═══════════════════════════════════════════════════════════════ -->
<div class="l-shell">

<!-- ═══════════════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════════════════ -->
<aside class="l-sidebar" role="navigation" aria-label="Main navigation">
    <div class="l-sidebar__inner">

        <div class="l-sidebar__top">
            <div class="l-brand-tag">SK Officer Portal</div>
            <div class="l-brand-name">my<em>Space.</em></div>
            <div class="l-brand-sub">Community Management</div>
        </div>

        <div class="l-user-card">
            <div class="l-user-avatar"><?= htmlspecialchars($avatarLetter) ?></div>
            <div class="l-user-info">
                <div class="l-user-name"><?= htmlspecialchars($user_name) ?></div>
                <div class="l-user-role">SK Officer</div>
            </div>
        </div>

        <nav class="l-nav" aria-label="Sidebar navigation">
            <div class="l-nav__section">Menu</div>
            <?php foreach ($navItems as $item):
                $isActive = ($page === $item['key']);
                $hasBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
            ?>
            <a href="<?= $item['url'] ?>"
               class="l-nav__link<?= $isActive ? ' is-active' : '' ?>"
               <?= $isActive ? 'aria-current="page"' : '' ?>>
                <div class="l-nav__icon">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
                </div>
                <?= htmlspecialchars($item['label']) ?>
                <?php if ($hasBadge): ?>
                    <span class="l-nav__badge"><?= $pendingUserCount ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </nav>

        <?php if ($remaining !== null): ?>
        <div class="l-quota">
            <div class="l-quota__row">
                <span class="l-quota__label">Monthly Quota</span>
                <span class="l-quota__value"><?= $usedSlots ?>/<?= $maxSlots ?></span>
            </div>
            <div class="l-quota__track">
                <div class="l-quota__fill<?= $quotaPct >= 100 ? ' is-full' : ($quotaPct >= 67 ? ' is-warn' : '') ?>"
                     style="width:<?= $quotaPct ?>%"></div>
            </div>
            <p class="l-quota__note<?= $remaining === 0 ? ' is-err' : ($remaining === 1 ? ' is-warn' : '') ?>">
                <?php if ($remaining === 0): ?>⚠ No slots left this month
                <?php elseif ($remaining === 1): ?>⚡ Only 1 slot remaining
                <?php else: ?><?= $remaining ?> slots remaining<?php endif; ?>
            </p>
        </div>
        <?php endif; ?>

        <div class="l-sidebar__footer">
            <a href="/logout" class="l-logout">
                <div class="l-logout__icon">
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
            $isActive = ($page === $item['key']);
            $hasBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
        ?>
        <a href="<?= $item['url'] ?>"
           class="l-mn__tab<?= $isActive ? ' is-active' : '' ?>"
           <?= $isActive ? 'aria-current="page"' : '' ?>>
            <div class="l-mn__icon-wrap">
                <i class="fa-solid <?= $item['icon'] ?>"></i>
                <?php if ($hasBadge): ?>
                    <span class="l-mn__dot"></span>
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
        $isActive = ($page === $item['key']);
        $hasBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
    ?>
    <a href="<?= $item['url'] ?>"
       class="l-drawer__item<?= $isActive ? ' is-active' : '' ?>"
       <?= $isActive ? 'aria-current="page"' : '' ?>>
        <div class="l-drawer__icon">
            <i class="fa-solid <?= $item['icon'] ?>"></i>
        </div>
        <span class="l-drawer__name"><?= htmlspecialchars($item['label']) ?></span>
        <?php if ($hasBadge): ?>
            <span class="l-drawer__badge"><?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?></span>
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
     LAYOUT JS
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
        applyDark(localStorage.getItem('sk_theme') === 'dark');
    });

    window.layoutToggleDark = function(){
        var isDark = !document.body.classList.contains('dark');
        try { localStorage.setItem('sk_theme', isDark ? 'dark' : 'light'); } catch(e){}
        applyDark(isDark);
    };

    /* ── Drawer ── */
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

    /* Close drawer on Escape */
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