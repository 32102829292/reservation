<?php
/**
 * Views/partials/layout.php
 *
 * Shared layout partial — renders sidebar, mobile wave nav, and injects the
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
    ['url' => '/dashboard',        'label' => 'Dashboard',       'key' => 'dashboard',        'icon_key' => 'house'],
    ['url' => '/reservation',      'label' => 'New Reservation', 'key' => 'reservation',      'icon_key' => 'plus'],
    ['url' => '/reservation-list', 'label' => 'My Reservations', 'key' => 'reservation-list', 'icon_key' => 'calendar'],
    ['url' => '/books',            'label' => 'Library',         'key' => 'books',            'icon_key' => 'book-open'],
    ['url' => '/profile',          'label' => 'Profile',         'key' => 'profile',          'icon_key' => 'user'],
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

/* Build a JS-safe array of nav items for the wave nav script */
$navItemsJson = json_encode(array_map(function($item) {
    return ['key' => $item['key'], 'label' => $item['label'], 'icon_key' => $item['icon_key']];
}, $navItems));

/* Find the active index for the wave nav initial state */
$activeIndex = 0;
foreach ($navItems as $i => $item) {
    if ($item['key'] === $page) { $activeIndex = $i; break; }
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
════════════════════════════════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

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
                            ? _layout_icon($item['icon_key'], 16, 'white')
                            : _layout_icon($item['icon_key'], 16)
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
     MOBILE WAVE NAV
════════════════════════════════════════════════════════════════════ -->
<nav class="l-wave-nav" id="l-wave-nav" aria-label="Mobile navigation">

    <!-- SVG background with the animated notch cutout -->
    <svg class="l-wave-nav__bg" id="l-wave-bg"
         xmlns="http://www.w3.org/2000/svg"
         preserveAspectRatio="none">
        <path id="l-wave-path" d=""/>
    </svg>

    <!-- Pulse ring behind bubble -->
    <div class="l-wave-nav__ring" id="l-wave-ring" aria-hidden="true"></div>

    <!-- Floating active bubble -->
    <div class="l-wave-nav__bubble" id="l-wave-bubble" aria-hidden="true">
        <span class="l-wave-nav__bubble-icon" id="l-wave-bubble-icon"></span>
    </div>

    <!-- Nav items row -->
    <div class="l-wave-nav__items" id="l-wave-items">
        <?php foreach ($navItems as $i => $item):
            $active   = ($page === $item['key']);
            $hasBadge = ($item['key'] === 'reservation-list' && $pending > 0);
        ?>
            <a href="<?= base_url($item['url']) ?>"
               class="l-wave-nav__item<?= $active ? ' is-active' : '' ?>"
               data-idx="<?= $i ?>"
               data-key="<?= esc($item['key']) ?>"
               title="<?= esc($item['label']) ?>"
               <?= $active ? 'aria-current="page"' : '' ?>>
                <span class="l-wave-nav__item-icon" aria-hidden="true">
                    <?= _layout_icon($item['icon_key'], 22) ?>
                </span>
                <span class="l-wave-nav__item-label"><?= esc($item['label']) ?></span>
                <?php if ($hasBadge): ?>
                    <span class="l-wave-nav__badge" aria-label="<?= $pending ?> pending">
                        <?= $pending > 9 ? '9+' : $pending ?>
                    </span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>

        <!-- Logout as last item -->
        <a href="<?= base_url('/logout') ?>"
           class="l-wave-nav__item l-wave-nav__item--logout"
           title="Sign Out">
            <span class="l-wave-nav__item-icon" aria-hidden="true">
                <?= _layout_icon('logout', 22, '#f87171') ?>
            </span>
            <span class="l-wave-nav__item-label" style="color:#f87171;">Out</span>
        </a>
    </div>
</nav>

<!-- ═══════════════════════════════════════════════════════════════════
     DARK MODE TOGGLE BUTTON
════════════════════════════════════════════════════════════════════ -->
<?php
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
     LAYOUT JAVASCRIPT
════════════════════════════════════════════════════════════════════ -->
<script>
/* ── Dark mode ───────────────────────────────────────────────────── */
(function () {
    const MOON = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
    const SUN  = `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;

    function applyDark(isDark) {
        document.body.classList.toggle('dark', isDark);
        document.documentElement.classList.remove('dark-pre');
        var icon = document.getElementById('l-dark-icon');
        if (icon) icon.innerHTML = isDark ? MOON : SUN;
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyDark(localStorage.getItem('theme') === 'dark');
    });

    window.layoutToggleDark = function () {
        var isDark = !document.body.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        applyDark(isDark);
    };
})();

/* ── Wave mobile nav ─────────────────────────────────────────────── */
(function () {

    /* SVG inner paths for each nav icon (matches PHP $navItems order) */
    var ICON_PATHS = [
        /* dashboard  */ '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        /* reservation*/ '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        /* res-list   */ '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        /* books      */ '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        /* profile    */ '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        /* logout     */ '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>'
    ];

    var activeIdx = <?= $activeIndex ?>;

    function navSVG(pathInner, size, stroke) {
        size   = size   || 22;
        stroke = stroke || 'white';
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="' + stroke + '" stroke-width="1.8">' + pathInner + '</svg>';
    }

    function notchPath(idx, total, W, H) {
        var slotW  = W / total;
        var cx     = slotW * idx + slotW / 2;
        var r      = 34;
        var depth  = 26;
        var curve  = 20;
        var top    = 14;
        return 'M0,' + top + ' Q0,0 ' + top + ',0'
             + ' L' + (cx - r - curve) + ',0'
             + ' Q' + (cx - r) + ',0 ' + (cx - r + 4) + ',' + (depth * 0.45)
             + ' Q' + (cx - 4) + ',' + (depth + 4) + ' ' + cx + ',' + (depth + 4)
             + ' Q' + (cx + 4) + ',' + (depth + 4) + ' ' + (cx + r - 4) + ',' + (depth * 0.45)
             + ' Q' + (cx + r) + ',0 ' + (cx + r + curve) + ',0'
             + ' L' + (W - top) + ',0 Q' + W + ',0 ' + W + ',' + top
             + ' L' + W + ',' + H + ' L0,' + H + ' Z';
    }

    function positionBubble(idx, total) {
        var nav    = document.getElementById('l-wave-nav');
        if (!nav) return;
        var W      = nav.offsetWidth;
        var slotW  = W / total;
        var cx     = slotW * idx + slotW / 2;

        var bubble = document.getElementById('l-wave-bubble');
        var ring   = document.getElementById('l-wave-ring');
        if (bubble) bubble.style.left = cx + 'px';
        if (ring)   ring.style.left   = cx + 'px';
    }

    function updateNotch(idx) {
        var nav  = document.getElementById('l-wave-nav');
        var path = document.getElementById('l-wave-path');
        if (!nav || !path) return;

        /* total slots = navItems + logout */
        var total = document.querySelectorAll('.l-wave-nav__item').length;
        var W     = nav.offsetWidth;
        var H     = nav.offsetHeight;

        path.setAttribute('d', notchPath(idx, total, W, H));
        positionBubble(idx, total);

        var bubbleIcon = document.getElementById('l-wave-bubble-icon');
        if (bubbleIcon) {
            bubbleIcon.innerHTML = navSVG(ICON_PATHS[idx] || ICON_PATHS[0], 22, 'white');
        }
    }

    function init() {
        /* Set active class */
        document.querySelectorAll('.l-wave-nav__item').forEach(function (el) {
            el.classList.remove('is-active');
            if (parseInt(el.dataset.idx) === activeIdx) el.classList.add('is-active');
        });

        updateNotch(activeIdx);
    }

    document.addEventListener('DOMContentLoaded', function () {
        init();

        /* Re-draw on resize (orientation change) */
        window.addEventListener('resize', function () { updateNotch(activeIdx); });

        /* Optional: intercept clicks to animate without full page reload */
        document.querySelectorAll('.l-wave-nav__item[data-idx]').forEach(function (el) {
            el.addEventListener('click', function (e) {
                var idx = parseInt(el.dataset.idx);
                document.querySelectorAll('.l-wave-nav__item').forEach(function (i) {
                    i.classList.remove('is-active');
                });
                el.classList.add('is-active');
                activeIdx = idx;
                updateNotch(idx);
                /* Let the default href navigation proceed */
            });
        });
    });

})();
</script>