<?php
/**
 * Views/partials/layout.php
 *
 * Shared layout partial — renders:
 *   • Global CSS link + dark-mode FOUC-prevention script
 *   • Sidebar (desktop)
 *   • Mobile bottom nav
 *
 * ─────────────────────────────────────────────────────────────
 * USAGE
 * ─────────────────────────────────────────────────────────────
 * 1. Set $page to the current route key BEFORE including:
 *      $page = 'dashboard';
 *
 * 2. Include immediately after <body>:
 *      <?php include APPPATH . 'Views/partials/layout.php'; ?>
 *
 * 3. Wrap page content in:
 *      <main class="main-area"> ... </main>
 *
 * ─────────────────────────────────────────────────────────────
 * VARIABLES (all optional with sensible defaults)
 * ─────────────────────────────────────────────────────────────
 *   $page                   string   active nav key
 *   $user_name              string   display name
 *   $pendingUserCount       int      badge count for User Requests
 *   $remainingReservations  int|null show quota widget when set
 *   $usedThisMonth          int      slots used this month
 *   $maxMonthlySlots        int      max slots allowed
 * ─────────────────────────────────────────────────────────────
 */

// ── Defaults ──────────────────────────────────────────────────
$page             = $page             ?? '';
$user_name        = $user_name        ?? ($user['name'] ?? 'Officer');
$pendingUserCount = $pendingUserCount ?? 0;
$maxSlots         = max(1, (int)($maxMonthlySlots ?? 3));
$usedSlots        = (int)($usedThisMonth ?? 0);
$remaining        = $remainingReservations ?? null;   // null = hide quota
$quotaPct         = $remaining !== null ? min(100, round($usedSlots / $maxSlots * 100)) : 0;
$avatarLetter     = strtoupper(mb_substr(trim($user_name), 0, 1)) ?: 'O';

// ── Nav items ─────────────────────────────────────────────────
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

<!-- ══════════════════════════════════════════════════════════
     DARK MODE — prevent flash of wrong theme
════════════════════════════════════════════════════════════ -->
<script>
(function () {
    try {
        if (localStorage.getItem('sk_theme') === 'dark') {
            document.documentElement.classList.add('dark-pre');
            document.body.classList.add('dark');
        }
    } catch (e) {}
})();
</script>

<!-- ══════════════════════════════════════════════════════════
     GLOBAL STYLESHEET
     Adjust the path to match your public folder structure.
════════════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

<!-- ══════════════════════════════════════════════════════════
     FONT AWESOME (if not already in <head>)
     Comment out if you load it in head_meta.php instead.
════════════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- ══════════════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════════════ -->
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
            <div class="l-user-avatar" aria-hidden="true"><?= htmlspecialchars($avatarLetter) ?></div>
            <div class="l-user-info">
                <div class="l-user-name"><?= htmlspecialchars($user_name) ?></div>
                <div class="l-user-role">SK Officer</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="l-nav" aria-label="Sidebar navigation">
            <div class="l-nav__section">Menu</div>

            <?php foreach ($navItems as $item):
                $isActive  = ($page === $item['key']);
                $hasBadge  = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
            ?>
                <a href="<?= $item['url'] ?>"
                   class="l-nav__link<?= $isActive ? ' is-active' : '' ?>"
                   <?= $isActive ? 'aria-current="page"' : '' ?>>
                    <div class="l-nav__icon" aria-hidden="true">
                        <i class="fa-solid <?= $item['icon'] ?>" style="font-size:.85rem;"></i>
                    </div>
                    <?= htmlspecialchars($item['label']) ?>
                    <?php if ($hasBadge): ?>
                        <span class="l-nav__badge" aria-label="<?= $pendingUserCount ?> pending">
                            <?= $pendingUserCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Monthly quota widget (only rendered when $remaining is provided) -->
        <?php if ($remaining !== null): ?>
            <div class="l-quota" role="status" aria-label="Monthly reservation quota">
                <div class="l-quota__row">
                    <span class="l-quota__label">Monthly Quota</span>
                    <span class="l-quota__value"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                </div>
                <div class="l-quota__track"
                     role="progressbar"
                     aria-valuenow="<?= $usedSlots ?>"
                     aria-valuemin="0"
                     aria-valuemax="<?= $maxSlots ?>">
                    <div class="l-quota__fill<?= $quotaPct >= 100 ? ' is-full' : ($quotaPct >= 67 ? ' is-warn' : '') ?>"
                         style="width:<?= $quotaPct ?>%;"></div>
                </div>
                <p class="l-quota__note<?= $remaining === 0 ? ' is-err' : ($remaining === 1 ? ' is-warn' : '') ?>">
                    <?php if ($remaining === 0): ?>
                        ⚠ No slots left this month
                    <?php elseif ($remaining === 1): ?>
                        ⚡ Only 1 slot remaining
                    <?php else: ?>
                        <?= $remaining ?> slots remaining
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Logout -->
        <div class="l-sidebar__footer">
            <a href="/logout" class="l-logout">
                <div class="l-logout__icon" aria-hidden="true">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171;"></i>
                </div>
                Sign Out
            </a>
        </div>

    </div><!-- /.l-sidebar__inner -->
</aside><!-- /.l-sidebar -->

<!-- ══════════════════════════════════════════════════════════
     MOBILE BOTTOM NAV
════════════════════════════════════════════════════════════ -->
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
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem;"></i>
                <?php if ($hasBadge): ?>
                    <span class="l-mobile-nav__badge"
                          aria-label="<?= $pendingUserCount ?> pending">
                        <?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?>
                    </span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>

        <a href="/logout"
           class="l-mobile-nav__item l-mobile-nav__item--logout"
           title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171;"></i>
        </a>

    </div><!-- /.l-mobile-nav__inner -->
</nav><!-- /.l-mobile-nav -->

<!-- ══════════════════════════════════════════════════════════
     LAYOUT JAVASCRIPT
     Dark-mode toggle + init.  Call layoutToggleDark() from
     any icon button in your topbar.
════════════════════════════════════════════════════════════ -->
<script>
(function () {
    'use strict';

    var MOON_SVG = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    var SUN_SVG  = '<i class="fa-regular fa-sun"  style="font-size:.85rem;"></i>';

    /**
     * Apply or remove dark mode.
     * @param {boolean} isDark
     */
    function applyDark(isDark) {
        document.body.classList.toggle('dark', isDark);
        document.documentElement.classList.remove('dark-pre');
        // Update any icon elements that have id="darkIcon"
        var icons = document.querySelectorAll('#darkIcon, [data-dark-icon]');
        icons.forEach(function (el) {
            el.innerHTML = isDark ? MOON_SVG : SUN_SVG;
        });
    }

    // Init from saved preference on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        applyDark(localStorage.getItem('sk_theme') === 'dark');
    });

    /**
     * Toggle dark mode. Call from your topbar button:
     *   onclick="layoutToggleDark()"
     */
    window.layoutToggleDark = function () {
        var isDark = !document.body.classList.contains('dark');
        localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
        applyDark(isDark);
    };
})();
</script>