<?php
/**
 * Views/partials/sk_layout.php
 *
 * Shared layout partial — renders:
 *   • Dark-mode FOUC-prevention script
 *   • Opens <div class="l-shell">  ← pages MUST close this with </div>
 *   • Sidebar (desktop)
 *   • Mobile bottom nav
 *   • Layout JS (dark-mode toggle)
 *
 * ─────────────────────────────────────────────────────────────
 * USAGE IN EVERY PAGE
 * ─────────────────────────────────────────────────────────────
 *
 *   <body>
 *     <?php include APPPATH . 'Views/partials/sk_layout.php'; ?>
 *
 *     <main class="main-area">
 *       ... page content ...
 *     </main>
 *     </div><!-- /.l-shell -->
 *   </body>
 *
 * ─────────────────────────────────────────────────────────────
 * VARIABLES (all optional — sensible defaults provided)
 * ─────────────────────────────────────────────────────────────
 *   $page                   string    active nav key
 *   $user_name              string    display name
 *   $pendingUserCount       int       badge on "User Requests"
 *   $remainingReservations  int|null  null = hide quota widget
 *   $usedThisMonth          int       slots used this month
 *   $maxMonthlySlots        int       slot cap
 * ─────────────────────────────────────────────────────────────
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
?>
<!-- ═══════════════════════════════════════════════════════════
     DARK MODE FOUC GUARD — must run synchronously
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

<!-- Font Awesome — loaded once here, remove from individual pages -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- ═══════════════════════════════════════════════════════════
     SHELL OPEN — close with </div> after </main> in each page
═══════════════════════════════════════════════════════════════ -->
<div class="l-shell">

<!-- ═══════════════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════════════════ -->
<aside class="l-sidebar" role="navigation" aria-label="Main navigation">
    <div class="l-sidebar__inner">

        <!-- Brand -->
        <div class="l-sidebar__top">
            <div class="l-brand-tag">SK Officer Portal</div>
            <div class="l-brand-name">my<em>Space.</em></div>
            <div class="l-brand-sub">Community Management</div>
        </div>

        <!-- User card -->
        <div class="l-user-card">
            <div class="l-user-avatar"><?= htmlspecialchars($avatarLetter) ?></div>
            <div class="l-user-info">
                <div class="l-user-name"><?= htmlspecialchars($user_name) ?></div>
                <div class="l-user-role">SK Officer</div>
            </div>
        </div>

        <!-- Nav -->
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

        <!-- Quota widget -->
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

        <!-- Logout -->
        <div class="l-sidebar__footer">
            <a href="/logout" class="l-logout">
                <div class="l-logout__icon">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                Sign Out
            </a>
        </div>

    </div><!-- /.l-sidebar__inner -->
</aside><!-- /.l-sidebar -->

<!-- ═══════════════════════════════════════════════════════════
     MOBILE BOTTOM NAV
═══════════════════════════════════════════════════════════════ -->
<nav class="l-mobile-nav" aria-label="Mobile navigation">
    <div class="l-mobile-nav__inner">
        <?php foreach ($navItems as $item):
            $isActive = ($page === $item['key']);
            $hasBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
        ?>
        <a href="<?= $item['url'] ?>"
           class="l-mobile-nav__item<?= $isActive ? ' is-active' : '' ?>"
           title="<?= htmlspecialchars($item['label']) ?>"
           <?= $isActive ? 'aria-current="page"' : '' ?>>
            <i class="fa-solid <?= $item['icon'] ?>"></i>
            <?php if ($hasBadge): ?>
                <span class="l-mobile-nav__badge"><?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?></span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
        <a href="/logout" class="l-mobile-nav__item l-mobile-nav__item--logout" title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</nav><!-- /.l-mobile-nav -->

<!-- ═══════════════════════════════════════════════════════════
     LAYOUT JS — dark-mode toggle
     Call  layoutToggleDark()  from topbar buttons
═══════════════════════════════════════════════════════════════ -->
<script>
(function(){
    'use strict';

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
})();
</script>