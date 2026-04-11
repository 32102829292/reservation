<?php
/**
 * Views/partials/layout.php
 * Shared layout — sidebar, mobile nav, dark-mode toggle.
 */

$page      = $page      ?? '';
$user_name = $user_name ?? ($user['name'] ?? 'Resident');
$pending   = $pending   ?? 0;

$avatarLetter = strtoupper(mb_substr(trim($user_name, " \t\n\r\0\x0B"), 0, 1)) ?: 'R';

$maxSlots  = 3;
$remaining = $remainingReservations ?? null;
$usedSlots = $remaining !== null ? ($maxSlots - $remaining) : 0;

$navItems = [
    ['url' => '/dashboard',        'label' => 'Home',     'key' => 'dashboard',        'icon' => 'house'],
    ['url' => '/reservation',      'label' => 'Reserve',  'key' => 'reservation',      'icon' => 'plus'],
    ['url' => '/reservation-list', 'label' => 'My Slots', 'key' => 'reservation-list', 'icon' => 'calendar'],
    ['url' => '/books',            'label' => 'Library',  'key' => 'books',            'icon' => 'book-open'],
    ['url' => '/profile',          'label' => 'Profile',  'key' => 'profile',          'icon' => 'user'],
];

/* SVG paths only — sizes set explicitly in markup */
function _layout_svg(string $name): string {
    static $paths = [
        'house'     => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus'      => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'book-open' => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'user'      => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'logout'    => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
        'sun'       => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
        'moon'      => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
    ];
    $sw = in_array($name, ['calendar', 'book-open']) ? '1.5' : '1.8';
    $d  = $paths[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    return $d . '" stroke-width="' . $sw; /* returned into the svg template below */
}

/**
 * Render a sidebar/layout icon at a given pixel size.
 * stroke is passed explicitly — never relies on CSS color inheritance so
 * dark-mode transitions work without JavaScript SVG re-painting.
 */
function _layout_icon(string $name, int $size, string $stroke): string {
    static $icons = [
        'house'     => ['<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>', '1.8'],
        'plus'      => ['<path d="M12 5v14M5 12h14" stroke-linecap="round"/>', '1.8'],
        'calendar'  => ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>', '1.5'],
        'book-open' => ['<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>', '1.5'],
        'user'      => ['<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>', '1.8'],
        'logout'    => ['<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>', '1.8'],
        'sun'       => ['<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>', '1.8'],
        'moon'      => ['<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>', '1.8'],
    ];
    [$d, $sw] = $icons[$name] ?? ['<circle cx="12" cy="12" r="10"/>', '1.8'];
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="%s" stroke-width="%s" style="width:%dpx;height:%dpx;flex-shrink:0;">%s</svg>',
        $size, $size,
        htmlspecialchars($stroke, ENT_QUOTES),
        $sw,
        $size, $size,
        $d
    );
}
?>

<style>
/* ── Scroll fix ── */

/* Desktop: sidebar fixed, only main scrolls */
@media (min-width: 1024px) {
    html, body { height: 100%; overflow: hidden; }
    body        { display: flex; }
    .l-sidebar  { height: 100vh; overflow-y: auto; position: sticky; top: 0; flex-shrink: 0; }
    .main-area  { height: 100vh; overflow-y: auto; flex: 1; min-width: 0; }
}

/* Mobile: normal page scroll, just pad bottom for the nav bar */
@media (max-width: 1023px) {
    html, body { height: auto; overflow: auto; }
    .main-area { height: auto; overflow: visible; padding-bottom: 80px; }
}
</style>

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

<!-- ════════════════════════════════
     SIDEBAR (desktop only ≥1024px)
════════════════════════════════ -->
<aside class="l-sidebar" role="navigation" aria-label="Main navigation">
    <div class="l-sidebar__inner">

        <div class="l-sidebar__top">
            <div class="l-brand-tag">Resident Portal</div>
            <div class="l-brand-name">my<em>Space.</em></div>
            <div class="l-brand-sub">Community Management</div>
        </div>

        <div class="l-user-card">
            <div class="l-user-avatar" aria-hidden="true"><?= $avatarLetter ?></div>
            <div class="l-user-info">
                <div class="l-user-name"><?= esc($user_name) ?></div>
                <div class="l-user-role">Resident</div>
            </div>
        </div>

        <nav class="l-nav" aria-label="Sidebar navigation">
            <div class="l-nav__section">Menu</div>

            <?php foreach ($navItems as $item):
                $active   = ($page === $item['key']);
                $hasBadge = ($item['key'] === 'reservation-list' && $pending > 0);
                $iconColor = $active ? 'white' : 'currentColor';
            ?>
                <a href="<?= base_url($item['url']) ?>"
                   class="l-nav__link<?= $active ? ' is-active' : '' ?>"
                   <?= $active ? 'aria-current="page"' : '' ?>>
                    <span class="l-nav__icon" aria-hidden="true">
                        <?= _layout_icon($item['icon'], 16, $iconColor) ?>
                    </span>
                    <?= esc($item['label']) ?>
                    <?php if ($hasBadge): ?>
                        <span class="l-nav__badge" aria-label="<?= $pending ?> pending"><?= $pending ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <?php if ($remaining !== null): ?>
            <div class="l-quota" role="status" aria-label="Monthly reservation quota">
                <div class="l-quota__row">
                    <span class="l-quota__label">Monthly Quota</span>
                    <span class="l-quota__value"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                </div>
                <div class="l-quota__track" role="progressbar"
                     aria-valuenow="<?= $usedSlots ?>" aria-valuemin="0" aria-valuemax="<?= $maxSlots ?>">
                    <div class="l-quota__fill<?= $remaining === 0 ? ' is-full' : ($remaining === 1 ? ' is-low' : '') ?>"
                         style="width:<?= round(($usedSlots / $maxSlots) * 100) ?>%"></div>
                </div>
                <p class="l-quota__note<?= $remaining === 0 ? ' is-err' : ($remaining === 1 ? ' is-warn' : '') ?>">
                    <?php if ($remaining === 0): ?>⚠ No slots left this month
                    <?php elseif ($remaining === 1): ?>⚡ Only 1 slot remaining
                    <?php else: ?><?= $remaining ?> slots remaining this month
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="l-sidebar__footer">
            <a href="<?= base_url('/logout') ?>" class="l-logout">
                <span class="l-logout__icon" aria-hidden="true">
                    <?= _layout_icon('logout', 16, '#f87171') ?>
                </span>
                Sign Out
            </a>
        </div>

    </div>
</aside>

<!-- ════════════════════════════════
     MOBILE BOTTOM NAV  (<1024px)
     Explicit icon sizes, no color
     inheritance issues in dark mode
════════════════════════════════ -->
<nav class="l-mobile-nav" aria-label="Mobile navigation" id="l-mobile-nav">
    <div class="l-mobile-nav__inner">
        <?php foreach ($navItems as $item):
            $active   = ($page === $item['key']);
            $hasBadge = ($item['key'] === 'reservation-list' && $pending > 0);
        ?>
            <a href="<?= base_url($item['url']) ?>"
               class="l-mobile-nav__item<?= $active ? ' is-active' : '' ?>"
               title="<?= esc($item['label']) ?>"
               data-nav-key="<?= $item['key'] ?>"
               <?= $active ? 'aria-current="page"' : '' ?>>
                <?php if ($hasBadge): ?>
                    <span class="l-mobile-nav__badge" aria-label="<?= $pending ?> pending">
                        <?= $pending > 9 ? '9+' : $pending ?>
                    </span>
                <?php endif; ?>
                <span class="nav-icon-wrap" aria-hidden="true">
                    <?= _layout_icon($item['icon'], 20, 'currentColor') ?>
                </span>
                <span class="nav-lbl"><?= esc($item['label']) ?></span>
            </a>
        <?php endforeach; ?>

        <a href="<?= base_url('/logout') ?>"
           class="l-mobile-nav__item l-mobile-nav__item--logout"
           title="Sign Out">
            <span class="nav-icon-wrap" aria-hidden="true">
                <?= _layout_icon('logout', 20, '#f87171') ?>
            </span>
            <span class="nav-lbl" style="color:#f87171;">Logout</span>
        </a>
    </div>
</nav>

<!-- ════════════════════════════════
     DARK MODE TOGGLE
════════════════════════════════ -->
<?php
function layout_dark_toggle(): string {
    ob_start(); ?>
    <button
        id="l-dark-toggle"
        class="l-icon-btn"
        onclick="layoutToggleDark()"
        aria-label="Toggle dark mode"
        title="Toggle dark mode">
        <span id="l-dark-icon" style="display:flex;align-items:center;justify-content:center;width:16px;height:16px;">
            <svg id="l-dark-svg" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.8"
                 style="width:15px;height:15px;flex-shrink:0;">
                <circle cx="12" cy="12" r="5"/>
                <line x1="12" y1="1" x2="12" y2="3"/>
                <line x1="12" y1="21" x2="12" y2="23"/>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                <line x1="1" y1="12" x2="3" y2="12"/>
                <line x1="21" y1="12" x2="23" y2="12"/>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
        </span>
    </button>
    <?php return ob_get_clean();
}
?>

<script>
(function () {
    const MOON_SVG = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:15px;height:15px;flex-shrink:0;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
    const SUN_SVG  = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:15px;height:15px;flex-shrink:0;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;

    function applyDark(isDark) {
        document.body.classList.toggle('dark', isDark);
        document.documentElement.classList.remove('dark-pre');

        /* Toggle the dark-mode toggle icon */
        const iconWrap = document.getElementById('l-dark-icon');
        if (iconWrap) iconWrap.innerHTML = isDark ? MOON_SVG : SUN_SVG;

        /*
         * Mobile nav: update SVG stroke color on each nav item.
         * We use CSS currentColor so the color cascades from the
         * .l-mobile-nav__item color property — but SVGs rendered
         * server-side with explicit stroke="currentColor" need the
         * parent element color to be correct, which CSS handles.
         * Nothing extra needed here beyond toggling body.dark.
         */
    }

    document.addEventListener('DOMContentLoaded', function () {
        /* Apply saved theme on load */
        const saved = localStorage.getItem('theme');
        applyDark(saved === 'dark');
    });

    window.layoutToggleDark = function () {
        const isDark = !document.body.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        applyDark(isDark);
    };
})();
</script>