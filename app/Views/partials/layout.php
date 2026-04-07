<?php
/**
 * Views/partials/layout.php
 *
 * Shared layout partial — renders sidebar, mobile nav, and injects the
 * global CSS link + dark-mode pre-init script.
 *
 * HOW TO USE IN ANY VIEW:
 * ─────────────────────────────────────────────────────────────────────
 * 1. Set $page to the current route key BEFORE including this file.
 *    e.g.  $page = 'dashboard';
 *
 * 2. Include it right after <body>:
 *    <?php include APPPATH . 'Views/partials/layout.php'; ?>
 *
 * 3. Wrap your page content in <main class="main-area"> ... </main>
 *
 * 4. Optionally pass $remainingReservations (int) to show the quota widget.
 *    e.g.  $remainingReservations = 2;
 *
 * VARIABLES USED:
 *   $page                  (string)  active nav key
 *   $user_name             (string)  displayed name (sidebar + avatar)
 *   $remainingReservations (int)     quota slots left (optional)
 *   $pending               (int)     pending reservations badge (optional)
 * ─────────────────────────────────────────────────────────────────────
 */

$page      = $page      ?? '';
$user_name = $user_name ?? ($user['name'] ?? 'Resident');
$pending   = $pending   ?? 0;

$avatarLetter = strtoupper(mb_substr(trim($user_name, " \t\n\r\0\x0B"), 0, 1)) ?: 'R';

$maxSlots  = 3;
$remaining = $remainingReservations ?? null;
$usedSlots = $remaining !== null ? ($maxSlots - $remaining) : 0;

$navItems = [
    ['url' => '/dashboard',        'label' => 'Dashboard',       'key' => 'dashboard',        'icon' => _layout_icon('house',     16)],
    ['url' => '/reservation',      'label' => 'New Reservation', 'key' => 'reservation',      'icon' => _layout_icon('plus',      16)],
    ['url' => '/reservation-list', 'label' => 'My Reservations', 'key' => 'reservation-list', 'icon' => _layout_icon('calendar',  16)],
    ['url' => '/books',            'label' => 'Library',         'key' => 'books',            'icon' => _layout_icon('book-open', 16)],
    ['url' => '/profile',          'label' => 'Profile',         'key' => 'profile',          'icon' => _layout_icon('user',      16)],
];

/** Inline SVG icon helper — only used inside this partial */
function _layout_icon(string $name, int $size = 16, string $stroke = 'currentColor'): string {
    static $icons = [
        'house'      => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus'       => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'book-open'  => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'user'       => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'logout'     => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
        'sun'        => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
        'moon'       => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
    ];
    $d  = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    $sw = in_array($name, ['calendar', 'book-open']) ? '1.5' : '1.8';
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="%s" stroke-width="%s">%s</svg>',
        $size, $size, htmlspecialchars($stroke, ENT_QUOTES), $sw, $d
    );
}
?>

<!-- ═══════════════════════════════════════════════════════════════════
     DARK MODE PRE-INIT  (eliminates flash of wrong theme)
════════════════════════════════════════════════════════════════════ -->
<script>
(function () {
    try {
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-pre');
            document.body.classList.add('dark');
        }
    } catch (e) {}
})();
</script>

<!-- ═══════════════════════════════════════════════════════════════════
     GLOBAL STYLESHEET
     Link your compiled app.css here. The file is output alongside
     this partial. Adjust the path to match your public asset folder.
════════════════════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= base_url('css/app.css') ?>">

<!-- ═══════════════════════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════════════════════ -->
<aside class="l-sidebar" role="navigation" aria-label="Main navigation">
    <div class="l-sidebar__inner">

        <!-- Brand -->
        <div class="l-sidebar__top">
            <div class="l-brand-tag">Resident Portal</div>
            <div class="l-brand-name">my<em>Space.</em></div>
            <div class="l-brand-sub">Community Management</div>
        </div>

        <!-- User card -->
        <div class="l-user-card">
            <div class="l-user-avatar" aria-hidden="true"><?= $avatarLetter ?></div>
            <div class="l-user-info">
                <div class="l-user-name"><?= esc($user_name) ?></div>
                <div class="l-user-role">Resident</div>
            </div>
        </div>

        <!-- Nav links -->
        <nav class="l-nav" aria-label="Sidebar navigation">
            <div class="l-nav__label">Menu</div>

            <?php foreach ($navItems as $item):
                $active    = ($page === $item['key']);
                $hasBadge  = ($item['key'] === 'reservation-list' && $pending > 0);
            ?>
                <a href="<?= base_url($item['url']) ?>"
                   class="l-nav__link<?= $active ? ' is-active' : '' ?>"
                   <?= $active ? 'aria-current="page"' : '' ?>>
                    <span class="l-nav__icon" aria-hidden="true">
                        <?= $active
                            ? _layout_icon($item['key'] === 'house' ? 'house' : explode('-', $item['key'])[0], 16, 'white')
                            : $item['icon']
                        ?>
                    </span>
                    <?= esc($item['label']) ?>
                    <?php if ($hasBadge): ?>
                        <span class="l-nav__badge" aria-label="<?= $pending ?> pending"><?= $pending ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Quota widget (only if $remainingReservations is set) -->
        <?php if ($remaining !== null): ?>
            <div class="l-quota" role="status" aria-label="Monthly reservation quota">
                <div class="l-quota__row">
                    <span class="l-quota__label">Monthly Quota</span>
                    <span class="l-quota__value"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                </div>
                <div class="l-quota__track" role="progressbar"
                     aria-valuenow="<?= $usedSlots ?>"
                     aria-valuemin="0"
                     aria-valuemax="<?= $maxSlots ?>">
                    <div class="l-quota__fill<?= $remaining === 0 ? ' is-full' : ($remaining === 1 ? ' is-low' : '') ?>"
                         style="width:<?= round(($usedSlots / $maxSlots) * 100) ?>%"></div>
                </div>
                <p class="l-quota__note<?= $remaining === 0 ? ' is-err' : ($remaining === 1 ? ' is-warn' : '') ?>">
                    <?php if ($remaining === 0): ?>
                        ⚠ No slots left this month
                    <?php elseif ($remaining === 1): ?>
                        ⚡ Only 1 slot remaining
                    <?php else: ?>
                        <?= $remaining ?> slots remaining this month
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Logout -->
        <div class="l-sidebar__footer">
            <a href="<?= base_url('/logout') ?>" class="l-logout">
                <span class="l-nav__icon l-nav__icon--logout" aria-hidden="true">
                    <?= _layout_icon('logout', 16, '#f87171') ?>
                </span>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- ═══════════════════════════════════════════════════════════════════
     MOBILE BOTTOM NAV
════════════════════════════════════════════════════════════════════ -->
<nav class="l-mobile-nav" aria-label="Mobile navigation">
    <?php foreach ($navItems as $item):
        $active   = ($page === $item['key']);
        $hasBadge = ($item['key'] === 'reservation-list' && $pending > 0);
    ?>
        <a href="<?= base_url($item['url']) ?>"
           class="l-mobile-nav__item<?= $active ? ' is-active' : '' ?>"
           title="<?= esc($item['label']) ?>"
           <?= $active ? 'aria-current="page"' : '' ?>>
            <?= _layout_icon(
                /* derive icon name from key */
                ['dashboard'=>'house','reservation'=>'plus','reservation-list'=>'calendar','books'=>'book-open','profile'=>'user'][$item['key']] ?? 'house',
                22,
                $active ? 'var(--indigo)' : '#64748b'
            ) ?>
            <?php if ($hasBadge): ?>
                <span class="l-mobile-nav__badge" aria-label="<?= $pending ?> pending"><?= $pending > 9 ? '9+' : $pending ?></span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>

    <a href="<?= base_url('/logout') ?>" class="l-mobile-nav__item l-mobile-nav__item--logout" title="Sign Out">
        <?= _layout_icon('logout', 22, '#f87171') ?>
    </a>
</nav>

<!-- ═══════════════════════════════════════════════════════════════════
     DARK MODE TOGGLE BUTTON  (place anywhere in your topbar)
     Usage:  <?= layout_dark_toggle() ?>
     Or just copy the button below into your topbar HTML.
════════════════════════════════════════════════════════════════════ -->
<?php
/**
 * Returns the dark-mode toggle button HTML.
 * Call layout_dark_toggle() anywhere in your view topbar.
 */
function layout_dark_toggle(): string {
    ob_start(); ?>
    <button
        id="l-dark-toggle"
        class="l-icon-btn"
        onclick="layoutToggleDark()"
        aria-label="Toggle dark mode"
        title="Toggle dark mode">
        <span id="l-dark-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="5"/>
                <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
        </span>
    </button>
    <?php return ob_get_clean();
}
?>

<!-- ═══════════════════════════════════════════════════════════════════
     LAYOUT JAVASCRIPT (self-contained, no framework needed)
════════════════════════════════════════════════════════════════════ -->
<script>
/* ── Dark mode ───────────────────────────────────────────────────── */
(function () {
    const MOON = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
    const SUN  = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;

    function applyDark(isDark) {
        document.body.classList.toggle('dark', isDark);
        document.documentElement.classList.remove('dark-pre');
        const icon = document.getElementById('l-dark-icon');
        if (icon) icon.innerHTML = isDark ? MOON : SUN;
    }

    // Run on DOMContentLoaded so the icon reflects saved preference
    document.addEventListener('DOMContentLoaded', function () {
        applyDark(localStorage.getItem('theme') === 'dark');
    });

    // Exposed globally so the toggle button can call it
    window.layoutToggleDark = function () {
        const isDark = !document.body.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        applyDark(isDark);
    };
})();
</script>