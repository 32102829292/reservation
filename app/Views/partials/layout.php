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

/**
 * Render a sidebar/layout icon at a given pixel size.
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
    /* ══════════════════════════════════════════
       SCROLL FIX — flex body layout
       body is the flex row (sidebar + main).
       Only .main-area should scroll, not body.
    ══════════════════════════════════════════ */
    html, body {
        height: 100%;
        overflow: hidden; /* body itself must NOT scroll */
    }

    .main-area {
        flex: 1;
        min-height: 0;          /* critical: lets flex child shrink & scroll */
        overflow-y: auto;
        -webkit-overflow-scrolling: touch; /* smooth momentum on iOS */
        padding-bottom: 80px;   /* clear the mobile bottom nav */
    }

    @media (max-width: 639px) {
        .main-area {
            padding: 16px 14px 90px !important; /* extra bottom clearance on small screens */
        }
    }

    /* ══════════════════════════════════════════
       LAYOUT SHELL
    ══════════════════════════════════════════ */
    .l-sidebar {
        width: 240px;
        flex-shrink: 0;
        height: 100vh;
        position: sticky;
        top: 0;
        overflow-y: auto;
        background: var(--sidebar-bg, #0f172a);
        border-right: 1px solid var(--border, rgba(255,255,255,.07));
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 1023px) {
        .l-sidebar { display: none; }
    }

    .l-sidebar__inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 24px 16px 20px;
    }

    /* ── Brand ── */
    .l-brand-tag {
        font-size: .55rem;
        font-weight: 700;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: var(--indigo, #6366f1);
        margin-bottom: 4px;
    }

    .l-brand-name {
        font-size: 1.35rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.04em;
        line-height: 1;
    }

    .l-brand-name em {
        font-style: normal;
        color: var(--indigo, #6366f1);
    }

    .l-brand-sub {
        font-size: .62rem;
        color: rgba(255,255,255,.35);
        margin-top: 3px;
        font-weight: 500;
    }

    .l-sidebar__top { margin-bottom: 20px; }

    /* ── User card ── */
    .l-user-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: rgba(255,255,255,.05);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,.07);
        margin-bottom: 22px;
    }

    .l-user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--indigo, #6366f1);
        color: #fff;
        font-weight: 800;
        font-size: .9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .l-user-name {
        font-size: .82rem;
        font-weight: 700;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .l-user-role {
        font-size: .65rem;
        color: rgba(255,255,255,.4);
        font-weight: 500;
    }

    /* ── Nav ── */
    .l-nav { flex: 1; }

    .l-nav__section {
        font-size: .55rem;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: rgba(255,255,255,.25);
        padding: 0 8px;
        margin-bottom: 8px;
    }

    .l-nav__link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 10px;
        border-radius: 10px;
        font-size: .82rem;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-decoration: none;
        transition: all .18s ease;
        margin-bottom: 2px;
        position: relative;
    }

    .l-nav__link:hover {
        background: rgba(255,255,255,.06);
        color: rgba(255,255,255,.85);
    }

    .l-nav__link.is-active {
        background: var(--indigo, #6366f1);
        color: #fff;
        box-shadow: 0 4px 12px rgba(99,102,241,.35);
    }

    .l-nav__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .l-nav__badge {
        margin-left: auto;
        background: #ef4444;
        color: #fff;
        font-size: .6rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 999px;
        min-width: 18px;
        text-align: center;
    }

    /* ── Quota ── */
    .l-quota {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.07);
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 16px;
        margin-bottom: 12px;
    }

    .l-quota__row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .l-quota__label {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255,255,255,.35);
    }

    .l-quota__value {
        font-size: .75rem;
        font-weight: 700;
        color: rgba(255,255,255,.6);
    }

    .l-quota__track {
        height: 5px;
        background: rgba(255,255,255,.08);
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .l-quota__fill {
        height: 100%;
        background: var(--indigo, #6366f1);
        border-radius: 999px;
        transition: width .4s ease;
    }

    .l-quota__fill.is-low  { background: #f59e0b; }
    .l-quota__fill.is-full { background: #ef4444; }

    .l-quota__note {
        font-size: .65rem;
        color: rgba(255,255,255,.35);
        font-weight: 500;
    }

    .l-quota__note.is-warn { color: #fbbf24; }
    .l-quota__note.is-err  { color: #f87171; }

    /* ── Footer / logout ── */
    .l-sidebar__footer { margin-top: auto; padding-top: 12px; }

    .l-logout {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 10px;
        border-radius: 10px;
        font-size: .82rem;
        font-weight: 600;
        color: #f87171;
        text-decoration: none;
        transition: background .18s ease;
    }

    .l-logout:hover { background: rgba(248,113,113,.1); }
    .l-logout__icon { display: flex; align-items: center; flex-shrink: 0; }

    /* ══════════════════════════════════════════
       MOBILE BOTTOM NAV
    ══════════════════════════════════════════ */
    .l-mobile-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 900;
        background: var(--card, #fff);
        border-top: 1px solid var(--border, #e5e7eb);
        box-shadow: 0 -4px 20px rgba(0,0,0,.08);
        /* safe area for notched phones */
        padding-bottom: env(safe-area-inset-bottom, 0px);
    }

    @media (max-width: 1023px) {
        .l-mobile-nav { display: block; }
    }

    .l-mobile-nav__inner {
        display: flex;
        align-items: stretch;
        height: 60px;
    }

    .l-mobile-nav__item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        text-decoration: none;
        color: var(--text-sub, #94a3b8);
        font-size: .58rem;
        font-weight: 600;
        position: relative;
        transition: color .18s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .l-mobile-nav__item.is-active { color: var(--indigo, #6366f1); }

    .l-mobile-nav__item--logout { color: #f87171 !important; }

    .l-mobile-nav__badge {
        position: absolute;
        top: 6px;
        right: calc(50% - 18px);
        background: #ef4444;
        color: #fff;
        font-size: .5rem;
        font-weight: 700;
        padding: 1px 4px;
        border-radius: 999px;
        min-width: 14px;
        text-align: center;
        line-height: 1.4;
    }

    .nav-icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }

    .nav-lbl { line-height: 1; }

    /* ══════════════════════════════════════════
       DARK MODE TOGGLE BUTTON
    ══════════════════════════════════════════ */
    .l-icon-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text-muted);
        cursor: pointer;
        transition: all .18s ease;
        box-shadow: var(--shadow-sm);
    }

    .l-icon-btn:hover {
        background: var(--indigo-light);
        border-color: var(--indigo-border);
        color: var(--indigo);
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
                $active    = ($page === $item['key']);
                $hasBadge  = ($item['key'] === 'reservation-list' && $pending > 0);
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
        const iconWrap = document.getElementById('l-dark-icon');
        if (iconWrap) iconWrap.innerHTML = isDark ? MOON_SVG : SUN_SVG;
    }

    document.addEventListener('DOMContentLoaded', function () {
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