<?php
/* ── Status logic (unchanged from original) ── */
$unclaimedCount = 0;
$claimedCount   = 0;
$processedRecent = [];
foreach (($reservations ?? []) as $r) {
    $isCl = in_array($r['claimed'] ?? false, [true, 1, 't', 'true', '1'], true)
        || strtolower($r['status'] ?? '') === 'claimed'
        || !empty($r['claimed_at']);
    $s = $isCl ? 'claimed' : strtolower($r['status'] ?? 'pending');
    if ($s === 'approved') {
        $edt = strtotime(($r['reservation_date'] ?? '') . ' ' . ($r['end_time'] ?? '23:59:59'));
        if ($edt && $edt < time()) $s = 'unclaimed';
    } elseif ($s === 'pending') {
        $rdt = strtotime($r['reservation_date'] ?? '');
        if ($rdt && $rdt < strtotime('today')) $s = 'expired';
    }
    if ($s === 'unclaimed') $unclaimedCount++;
    if ($s === 'claimed')   $claimedCount++;
    $r['_status'] = $s;
    $processedRecent[] = $r;
}

$remaining       = $remainingReservations ?? 3;
$maxSlots        = 3;
$featuredBooks   = $featuredBooks  ?? [];
$myBorrowings    = $myBorrowings   ?? [];
$availableCount  = $availableCount ?? 0;
$totalBooks      = $totalBooks     ?? 0;

$upcoming = null;
if (!empty($reservations)) {
    foreach ($reservations as $r) {
        if (($r['status'] ?? '') === 'approved' && empty($r['claimed'])) {
            $dt = strtotime($r['reservation_date'] . ' ' . ($r['end_time'] ?? '23:59'));
            if ($dt > time()) { $upcoming = $r; break; }
        }
    }
}

/* ── SVG icon helper ── */
function sk_icon(string $name, int $size = 20): string
{
    static $p = [
        'home'       => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'users'      => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0',
        'book'       => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'search'     => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0',
        'bell'       => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        'plus'       => 'M12 5v14M5 12h14',
        'calendar'   => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'arrow-r'    => 'M14 5l7 7m0 0l-7 7m7-7H3',
        'star'       => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        'clock'      => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0',
        'logout'     => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
        'sparkle'    => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
        'eye'        => 'M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c-3 6-6 9-9 9S3 18 3 12s3-9 9-9 9 3 9 9z',
        'bookmark'   => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z',
        'user'       => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'chevron-r'  => 'M9 5l7 7-7 7',
        'x'          => 'M6 18L18 6M6 6l12 12',
        'sun'        => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 000 10A5 5 0 0012 7z',
        'moon'       => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
        'check-c'    => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0',
        'ban'        => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
        'trending'   => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'check'      => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'cash'       => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'ticket'     => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
        'chart'      => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    ];
    $d = $p[$name] ?? 'M12 12h.01';
    return sprintf(
        '<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width:%1$dpx;height:%1$dpx;flex-shrink:0;">
            <path d="%2$s"/>
        </svg>',
        $size, $d
    );
}

/* ── Book SVG illustrations ── */
function book_art(string $palette, float $rotate = -8): string
{
    $palettes = [
        'rose'   => ['#FF6B8A','#FF8FA3','#FFB3C1','#FFF0F3','rgba(255,107,138,.15)'],
        'violet' => ['#7C3AED','#9F7AEA','#C4B5FD','#F5F3FF','rgba(124,58,237,.15)'],
        'sky'    => ['#0EA5E9','#38BDF8','#BAE6FD','#F0F9FF','rgba(14,165,233,.15)'],
        'amber'  => ['#F59E0B','#FCD34D','#FDE68A','#FFFBEB','rgba(245,158,11,.15)'],
        'emerald'=> ['#10B981','#34D399','#A7F3D0','#ECFDF5','rgba(16,185,129,.15)'],
        'indigo' => ['#4338CA','#6366F1','#A5B4FC','#EEF2FF','rgba(67,56,202,.15)'],
        'coral'  => ['#EF4444','#F87171','#FCA5A5','#FFF1F2','rgba(239,68,68,.15)'],
        'teal'   => ['#0D9488','#2DD4BF','#99F6E4','#F0FDFA','rgba(13,148,136,.15)'],
    ];
    [$c1,$c2,$c3,$bg,$glow] = $palettes[$palette] ?? $palettes['violet'];
    $r = $rotate;
    return <<<SVG
<svg viewBox="0 0 160 160" xmlns="http://www.w3.org/2000/svg" style="width:130px;height:130px;">
  <defs>
    <filter id="bs_{$palette}" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="{$c1}" flood-opacity="0.35"/>
    </filter>
    <filter id="bs2_{$palette}" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="{$c2}" flood-opacity="0.3"/>
    </filter>
  </defs>
  <circle cx="80" cy="88" r="54" fill="{$glow}"/>
  <g transform="translate(80,88) rotate(-18) translate(-80,-88)">
    <rect x="38" y="42" width="50" height="68" rx="5" fill="{$c3}" filter="url(#bs2_{$palette})"/>
    <rect x="38" y="42" width="7" height="68" rx="3" fill="{$c2}"/>
    <rect x="49" y="55" width="28" height="3" rx="1.5" fill="rgba(255,255,255,.45)"/>
    <rect x="49" y="62" width="20" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
    <rect x="49" y="69" width="24" height="3" rx="1.5" fill="rgba(255,255,255,.3)"/>
  </g>
  <g transform="translate(80,88) rotate(10) translate(-80,-88)">
    <rect x="58" y="48" width="48" height="66" rx="5" fill="{$c2}" filter="url(#bs_{$palette})"/>
    <rect x="58" y="48" width="7" height="66" rx="3" fill="{$c1}"/>
    <rect x="69" y="60" width="26" height="3" rx="1.5" fill="rgba(255,255,255,.5)"/>
    <rect x="69" y="67" width="18" height="3" rx="1.5" fill="rgba(255,255,255,.4)"/>
    <rect x="69" y="74" width="22" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
    <rect x="69" y="81" width="16" height="3" rx="1.5" fill="rgba(255,255,255,.3)"/>
  </g>
  <rect x="46" y="44" width="52" height="72" rx="6" fill="{$c1}" filter="url(#bs_{$palette})"/>
  <rect x="46" y="44" width="8" height="72" rx="4" fill="rgba(0,0,0,.15)"/>
  <rect x="48" y="44" width="3" height="72" rx="1.5" fill="rgba(255,255,255,.25)"/>
  <rect x="58" y="62" width="30" height="4" rx="2" fill="rgba(255,255,255,.6)"/>
  <rect x="58" y="70" width="22" height="3" rx="1.5" fill="rgba(255,255,255,.45)"/>
  <rect x="58" y="77" width="26" height="3" rx="1.5" fill="rgba(255,255,255,.4)"/>
  <rect x="58" y="84" width="18" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
  <rect x="58" y="50" width="32" height="8" rx="3" fill="rgba(255,255,255,.2)"/>
  <path d="M46 50 Q52 44 98 44 L98 56 Q70 58 46 62Z" fill="rgba(255,255,255,.12)"/>
</svg>
SVG;
}

/* ── Achievement SVG ── */
function achievement_art(string $color, string $letter): string
{
    $c = ['rose'=>'#FF6B8A','violet'=>'#7C3AED','sky'=>'#0EA5E9','amber'=>'#F59E0B','emerald'=>'#10B981'][$color]??'#7C3AED';
    return <<<SVG
<svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;flex-shrink:0;">
  <circle cx="20" cy="20" r="18" fill="{$c}" opacity=".15"/>
  <circle cx="20" cy="20" r="14" fill="{$c}" opacity=".25"/>
  <circle cx="20" cy="20" r="10" fill="{$c}"/>
  <text x="20" y="24.5" text-anchor="middle" font-family="'DM Sans',sans-serif" font-size="11" font-weight="700" fill="white">{$letter}</text>
</svg>
SVG;
}
?>
<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"/>
    <title>Dashboard · <?= esc($user_name ?? 'User') ?></title>
    <?php if (function_exists('base_url')): ?>
        <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
        <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
/* ─────────── RESET & TOKENS ─────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --font:   'DM Sans', system-ui, sans-serif;
    --mono:   'DM Mono', monospace;

    --purple:      #7C3AED;
    --purple-mid:  #9F7AEA;
    --purple-lt:   #EDE9FE;
    --purple-dk:   #5B21B6;

    --bg:          #F4F4FB;
    --card:        #FFFFFF;
    --border:      rgba(0,0,0,.07);
    --border-md:   rgba(0,0,0,.1);

    --text:        #1A1A2E;
    --text-2:      #4A4A6A;
    --text-3:      #8888AA;

    /* Sidebar-specific tokens */
    --sb-bg1:      #1E1535;
    --sb-bg2:      #160F2B;
    --sb-link:     rgba(255,255,255,.55);
    --sb-link-h:   rgba(255,255,255,.92);
    --sb-active-bg:rgba(255,255,255,.1);
    --sb-label:    rgba(255,255,255,.3);
    --sb-border:   rgba(255,255,255,.07);

    --r-sm: 12px;
    --r-md: 16px;
    --r-lg: 20px;
    --r-xl: 24px;
    --ease: .2s ease;
    --shadow-sm: 0 2px 8px rgba(0,0,0,.05);
    --shadow-md: 0 4px 20px rgba(0,0,0,.08);
    --shadow-lg: 0 8px 32px rgba(0,0,0,.1);
}

body.dark {
    --bg:         #0F0E1A;
    --card:       #1E1D30;
    --border:     rgba(255,255,255,.07);
    --border-md:  rgba(255,255,255,.1);
    --text:       #E8E8F8;
    --text-2:     #A8A8C8;
    --text-3:     #6868A8;
    --purple-lt:  rgba(124,58,237,.2);
}

html, body { height: 100%; overflow: hidden; font-family: var(--font); background: var(--bg); color: var(--text); }

/* ─────────── LAYOUT SHELL ─────────── */
.sk-shell { display: flex; height: 100vh; overflow: hidden; }

/* ═══════════════════════════════════
   REDESIGNED SIDEBAR
═══════════════════════════════════ */
.sk-sidebar {
    width: 236px;
    flex-shrink: 0;
    background: var(--sb-bg1);
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
    z-index: 100;
    transition: transform var(--ease);
    overflow: hidden;
}

/* Subtle geometric background texture */
.sk-sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 50% -10%, rgba(124,58,237,.35) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 100% 100%, rgba(67,56,202,.2) 0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
}

/* Dot-grid texture overlay */
.sk-sidebar::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
    background-size: 20px 20px;
    pointer-events: none;
    z-index: 0;
}

.sk-sidebar > * { position: relative; z-index: 1; }

/* ── Logo area ── */
.sk-logo {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 22px 18px 18px;
    border-bottom: 1px solid var(--sb-border);
    flex-shrink: 0;
}

.sk-logo-mark {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #9F7AEA 0%, #7C3AED 50%, #4338CA 100%);
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 0 1px rgba(255,255,255,.15), 0 4px 14px rgba(124,58,237,.5);
    flex-shrink: 0;
}
.sk-logo-mark svg { color: white; }

.sk-logo-text { display: flex; flex-direction: column; gap: 1px; }
.sk-logo-name {
    font-size: 15px; font-weight: 700;
    color: white; letter-spacing: -.4px; line-height: 1.1;
}
.sk-logo-tag {
    font-size: 9.5px; font-weight: 600; letter-spacing: .12em;
    text-transform: uppercase; color: rgba(255,255,255,.35);
}

/* ── Nav ── */
.sk-nav { flex: 1; overflow-y: auto; padding: 14px 12px 10px; }
.sk-nav::-webkit-scrollbar { width: 3px; }
.sk-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 99px; }

.sk-nav-section { margin-bottom: 4px; }

.sk-nav-label {
    font-size: 9px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--sb-label);
    padding: 0 10px; margin-bottom: 3px; margin-top: 16px; display: block;
}
.sk-nav-label:first-child { margin-top: 4px; }

.sk-nav-link {
    display: flex; align-items: center; gap: 9px;
    padding: 8.5px 10px; border-radius: 10px;
    color: var(--sb-link); font-size: 13.5px; font-weight: 500;
    text-decoration: none; cursor: pointer; border: none; background: none;
    width: 100%; transition: all .18s ease; position: relative;
}
.sk-nav-link:hover {
    background: rgba(255,255,255,.08);
    color: var(--sb-link-h);
}
.sk-nav-link.active {
    background: linear-gradient(90deg, rgba(124,58,237,.35) 0%, rgba(124,58,237,.12) 100%);
    color: white; font-weight: 600;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.1);
}
/* Active left bar */
.sk-nav-link.active::before {
    content: '';
    position: absolute; left: 0; top: 50%; transform: translateY(-50%);
    width: 3px; height: 55%; background: linear-gradient(180deg,#C4B5FD,#7C3AED);
    border-radius: 0 3px 3px 0;
}

.sk-nav-link svg { opacity: .7; transition: opacity .15s ease; }
.sk-nav-link:hover svg, .sk-nav-link.active svg { opacity: 1; }

.sk-nav-link .nav-count {
    margin-left: auto;
    background: linear-gradient(135deg,#9F7AEA,#7C3AED);
    color: white; font-size: 9.5px; font-weight: 700;
    padding: 2px 7px; border-radius: 999px; min-width: 20px; text-align: center;
    box-shadow: 0 2px 6px rgba(124,58,237,.4);
}

/* Sign out link special color */
.sk-nav-link.danger { color: rgba(252,165,165,.6); }
.sk-nav-link.danger:hover { background: rgba(239,68,68,.12); color: #FCA5A5; }

/* ── Divider ── */
.sk-nav-divider {
    height: 1px; background: var(--sb-border);
    margin: 8px 10px;
}

/* ── User footer card ── */
.sk-sidebar-footer {
    margin: 10px 12px 14px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 14px;
    padding: 12px 12px;
    display: flex; align-items: center; gap: 9px;
    backdrop-filter: blur(8px);
    flex-shrink: 0;
}

.sk-footer-avatar {
    width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #C4B5FD 0%, #7C3AED 100%);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 13px;
    box-shadow: 0 0 0 2px rgba(255,255,255,.15);
}
.sk-footer-info { flex: 1; min-width: 0; }
.sk-footer-name {
    font-size: 12.5px; font-weight: 650; color: rgba(255,255,255,.9);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2;
}
.sk-footer-role {
    font-size: 10px; font-weight: 500; color: rgba(255,255,255,.4);
    margin-top: 2px; text-transform: uppercase; letter-spacing: .08em;
}
.sk-footer-dot {
    width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
    background: #4ADE80;
    box-shadow: 0 0 0 2px rgba(74,222,128,.25);
}

/* ─────────── MAIN AREA ─────────── */
.sk-main { flex: 1; min-width: 0; display: flex; flex-direction: column; height: 100%; overflow: hidden; }

/* ─────────── TOPBAR ─────────── */
.sk-topbar {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 28px; background: var(--card);
    border-bottom: 1px solid var(--border); flex-shrink: 0;
}

.sk-search { flex: 1; max-width: 360px; position: relative; }
.sk-search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--text-3); }
.sk-search-input {
    width: 100%; padding: 9px 14px 9px 38px;
    background: var(--bg); border: 1px solid var(--border);
    border-radius: 50px; font-family: var(--font); font-size: 13.5px;
    color: var(--text); outline: none; transition: all var(--ease);
}
.sk-search-input::placeholder { color: var(--text-3); }
.sk-search-input:focus { border-color: var(--purple-mid); box-shadow: 0 0 0 3px rgba(124,58,237,.1); }

.sk-topbar-actions { display: flex; align-items: center; gap: 8px; margin-left: auto; }

.sk-icon-btn {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: var(--bg); border: 1px solid var(--border);
    color: var(--text-2); cursor: pointer; transition: all var(--ease); position: relative;
}
.sk-icon-btn:hover { background: var(--purple-lt); color: var(--purple); border-color: transparent; }

.sk-notif-dot {
    position: absolute; top: 6px; right: 6px;
    width: 8px; height: 8px; border-radius: 50%;
    background: #EF4444; border: 2px solid var(--card);
}

.sk-reserve-btn {
    display: flex; align-items: center; gap: 7px;
    padding: 9px 18px; background: var(--purple);
    color: white; border-radius: 50px;
    font-family: var(--font); font-size: 13.5px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(124,58,237,.35); transition: all var(--ease);
}
.sk-reserve-btn:hover { background: var(--purple-dk); transform: translateY(-1px); }

.sk-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, var(--purple) 0%, #9F7AEA 100%);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 14px; flex-shrink: 0; cursor: pointer;
}

/* ─────────── SCROLL AREA ─────────── */
.sk-scroll { flex: 1; overflow-y: auto; padding: 24px 28px 32px; scroll-behavior: smooth; }
.sk-scroll::-webkit-scrollbar { width: 5px; }
.sk-scroll::-webkit-scrollbar-thumb { background: var(--border-md); border-radius: 99px; }

/* ─────────── HERO BANNER ─────────── */
.sk-hero {
    background: linear-gradient(130deg, #4338CA 0%, #7C3AED 45%, #9F7AEA 100%);
    border-radius: var(--r-xl); padding: 0 32px;
    display: flex; align-items: flex-end; justify-content: space-between;
    overflow: hidden; position: relative; min-height: 172px; margin-bottom: 28px;
}
.sk-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.sk-hero-text { padding: 28px 0; position: relative; z-index: 1; max-width: 380px; }
.sk-hero-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: rgba(255,255,255,.65); margin-bottom: 6px; }
.sk-hero-name { font-size: 22px; font-weight: 700; color: white; margin-bottom: 6px; line-height: 1.25; }
.sk-hero-sub { font-size: 13px; color: rgba(255,255,255,.7); line-height: 1.55; margin-bottom: 16px; }
.sk-hero-cta {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 20px; background: white; color: var(--purple);
    border-radius: 50px; font-weight: 700; font-size: 13px;
    border: none; cursor: pointer; text-decoration: none; transition: all var(--ease);
}
.sk-hero-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }
.sk-hero-books { position: relative; display: flex; align-items: flex-end; gap: 4px; padding-bottom: 0; flex-shrink: 0; }
.sk-hero-books > * { filter: drop-shadow(0 8px 20px rgba(0,0,0,.25)); }

/* ─────────── STATS ROW ─────────── */
.sk-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 28px; }
.sk-stat {
    background: var(--card); border-radius: var(--r-lg);
    border: 1px solid var(--border); padding: 18px 20px;
    box-shadow: var(--shadow-sm); transition: all var(--ease);
}
.sk-stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.sk-stat-icon { width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.sk-stat-val { font-family: var(--mono); font-size: 26px; font-weight: 700; line-height: 1; margin-bottom: 4px; letter-spacing: -.04em; }
.sk-stat-lbl { font-size: 12px; color: var(--text-3); font-weight: 500; }
.sk-stat-trend { font-size: 11px; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 4px; }

/* ─────────── SECTION HEADING ─────────── */
.sk-section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.sk-section-title { font-size: 16px; font-weight: 700; letter-spacing: -.2px; }
.sk-section-link { font-size: 12px; font-weight: 600; color: var(--purple); text-decoration: none; display: flex; align-items: center; gap: 4px; transition: gap var(--ease); }
.sk-section-link:hover { gap: 7px; }

/* ─────────── BOOK CARDS GRID ─────────── */
.sk-books-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
.sk-book-card { border-radius: var(--r-lg); overflow: hidden; border: 1px solid var(--border); background: var(--card); transition: all var(--ease); cursor: pointer; }
.sk-book-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: transparent; }
.sk-book-art { display: flex; align-items: center; justify-content: center; min-height: 150px; position: relative; overflow: hidden; }
.sk-book-body { padding: 12px 14px 14px; }
.sk-book-label { font-size: 10px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: var(--text-3); margin-bottom: 4px; }
.sk-book-title { font-size: 13px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
.sk-book-author { font-size: 11.5px; color: var(--text-3); }
.sk-book-meta { display: flex; align-items: center; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--border); }
.sk-avail-pill { font-size: 10px; font-weight: 700; padding: 3px 9px; border-radius: 999px; }
.sk-stars { display: flex; align-items: center; gap: 2px; color: #F59E0B; }

/* ─────────── TWO-COL GRID ─────────── */
.sk-grid-2 { display: grid; grid-template-columns: 1fr 340px; gap: 16px; margin-bottom: 28px; }
.sk-card { background: var(--card); border-radius: var(--r-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); }
.sk-card-pad { padding: 20px 22px; }
.sk-card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
.sk-card-title { font-size: 15px; font-weight: 700; letter-spacing: -.2px; }
.sk-card-sub { font-size: 12px; color: var(--text-3); margin-top: 2px; }

/* ─────────── QUICK ACTIONS ─────────── */
.sk-qa-link {
    display: flex; align-items: center; gap: 11px;
    padding: 11px 12px; border-radius: var(--r-sm);
    border: 1px solid var(--border); color: var(--text-2);
    font-size: 13px; font-weight: 500; text-decoration: none;
    transition: all var(--ease); background: var(--card);
}
.sk-qa-link:hover { border-color: var(--purple); background: var(--purple-lt); color: var(--purple); }
.sk-qa-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

/* ─────────── BORROW ROW ─────────── */
.bk-row { display: flex; align-items: center; gap: 10px; padding: 9px 6px; border-radius: 10px; transition: background var(--ease); }
.bk-row:hover { background: var(--purple-lt); }
.bk-avatar { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; flex-shrink: 0; }
.tag-claimed  { background: #EDE9FE; color: #5B21B6; }
.tag-approved { background: #DCFCE7; color: #166534; }
.tag-pending  { background: #FEF3C7; color: #92400E; }
.tag-unclaimed{ background: #FFF7ED; color: #C2410C; }
.tag-declined { background: #FEE2E2; color: #991B1B; }
.tag-expired  { background: #F1F5F9; color: #475569; }
.tag { display: inline-flex; align-items: center; gap: 3px; padding: 2px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }

/* ─────────── ACHIEVEMENT CARD ─────────── */
.sk-achieve-row { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--border); }
.sk-achieve-row:last-child { border-bottom: none; }
.sk-achieve-name { font-size: 13px; font-weight: 600; color: var(--text); }
.sk-achieve-sub { font-size: 11px; color: var(--text-3); margin-top: 1px; }

/* ─────────── CALENDAR OVERRIDES ─────────── */
#dashboard-calendar { font-family: var(--font) !important; font-size: 12px; }
.fc-toolbar-title { font-family: var(--font) !important; font-size: 14px !important; font-weight: 700 !important; color: var(--text) !important; }
.fc-button-primary { background: var(--purple) !important; border-color: var(--purple) !important; font-family: var(--font) !important; font-weight: 600 !important; font-size: 12px !important; padding: .25rem .6rem !important; border-radius: 8px !important; box-shadow: none !important; }
.fc-button-primary:hover { background: var(--purple-dk) !important; }
.fc-daygrid-event { border-radius: 4px !important; font-family: var(--font) !important; font-size: 10px !important; font-weight: 600 !important; border: none !important; padding: 1px 4px !important; }
.fc-day-today { background: rgba(124,58,237,.06) !important; }
.fc-day-today .fc-daygrid-day-number { color: var(--purple) !important; font-weight: 700 !important; }
.fc-daygrid-day-number { font-family: var(--font); font-size: 11px; font-weight: 500; }
.fc-col-header-cell-cushion { font-family: var(--font); font-size: 11px; font-weight: 600; letter-spacing: .04em; }
body.dark .fc-toolbar-title { color: var(--text) !important; }
body.dark .fc-theme-standard td, body.dark .fc-theme-standard th, body.dark .fc-theme-standard .fc-scrollgrid { border-color: rgba(255,255,255,.06) !important; }
body.dark .fc-daygrid-day { background: var(--card) !important; }
body.dark .fc-daygrid-day-number, body.dark .fc-col-header-cell-cushion { color: var(--text-2) !important; }

/* ─────────── NEXT-ACTION BANNER ─────────── */
.sk-next { border-radius: var(--r-md); padding: 14px 18px; border: 1px solid; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 13px; }
.sk-next-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

/* ─────────── FLASH ─────────── */
.sk-flash { display: flex; align-items: center; gap: 9px; padding: 12px 16px; border-radius: var(--r-sm); background: #DCFCE7; border: 1px solid #BBF7D0; color: #166534; font-size: 13px; font-weight: 500; margin-bottom: 18px; }

/* ─────────── MODAL ─────────── */
.sk-modal-back { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 300; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
.sk-modal-back.show { display: flex; }
.sk-modal { background: var(--card); border-radius: var(--r-xl); padding: 24px; width: 90%; max-width: 420px; max-height: 80vh; overflow-y: auto; box-shadow: var(--shadow-lg); animation: fadeUp .2s ease; }
@keyframes fadeUp { from { transform: translateY(12px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

/* ─────────── NOTIFICATIONS ─────────── */
.sk-notif-dd { position: fixed; top: 72px; right: 24px; width: 300px; background: var(--card); border-radius: var(--r-xl); box-shadow: var(--shadow-lg); border: 1px solid var(--border); z-index: 200; display: none; overflow: hidden; }
.sk-notif-dd.show { display: block; animation: fadeUp .15s ease; }
.sk-notif-item { padding: 11px 14px; border-bottom: 1px solid var(--border); cursor: pointer; transition: background var(--ease); }
.sk-notif-item:hover { background: var(--purple-lt); }
.sk-notif-item.unread { background: rgba(124,58,237,.04); }

/* ─────────── RESPONSIVE ─────────── */
@media (max-width: 1100px) {
    .sk-books-grid { grid-template-columns: repeat(3,1fr); }
    .sk-grid-2 { grid-template-columns: 1fr; }
}
@media (max-width: 900px) {
    .sk-sidebar { position: fixed; left: 0; top: 0; height: 100%; transform: translateX(-100%); }
    .sk-sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
    .sk-books-grid { grid-template-columns: repeat(2,1fr); }
    .sk-stats { grid-template-columns: repeat(2,1fr); }
    .sk-hero { flex-direction: column; align-items: flex-start; }
    .sk-hero-books { display: none; }
}
@media (max-width: 560px) {
    .sk-scroll { padding: 16px 14px 24px; }
    .sk-topbar { padding: 12px 14px; }
    .sk-books-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .sk-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
}
@media (max-width: 380px) {
    .sk-books-grid { grid-template-columns: 1fr; }
}
</style>
</head>

<?php
$h = (int)date('H');
$greeting = $h < 12 ? 'Good morning' : ($h < 17 ? 'Good afternoon' : 'Good evening');
$initials = strtoupper(substr($user_name ?? 'U', 0, 1));
if (strpos($user_name ?? '', ' ') !== false) {
    $parts = explode(' ', $user_name);
    $initials = strtoupper(substr($parts[0],0,1) . substr(end($parts),0,1));
}

$nextAction = null;
if (($pending ?? 0) > 0) {
    $nextAction = ['type'=>'pending','msg'=>"You have {$pending} reservation".($pending>1?'s':'')." awaiting approval.",'color'=>'amber','icon'=>'clock','url'=>'/reservation-list','cta'=>'View Reservations'];
} elseif ($upcoming) {
    $nextAction = ['type'=>'upcoming','msg'=>"Approved slot coming up. Download your e-ticket and scan at the entrance.",'color'=>'purple','icon'=>'ticket','url'=>'/reservation-list','cta'=>'Get E-Ticket'];
} elseif ($unclaimedCount > 0) {
    $nextAction = ['type'=>'unclaimed','msg'=>"You missed {$unclaimedCount} approved slot".($unclaimedCount>1?'s':'').". Please cancel in advance if you can't attend.",'color'=>'coral','icon'=>'ban','url'=>'/reservation-list','cta'=>'See Details'];
}

$nextPalette = [
    'amber'  => ['rgba(251,191,36,.08)','rgba(251,191,36,.25)','rgba(251,191,36,.18)','#d97706'],
    'purple' => ['rgba(124,58,237,.07)','rgba(124,58,237,.2)','rgba(124,58,237,.12)','#7C3AED'],
    'coral'  => ['rgba(239,68,68,.06)','rgba(239,68,68,.2)','rgba(239,68,68,.1)','#ef4444'],
];

$artPalettes = ['rose','violet','sky','amber','emerald','indigo','coral','teal'];
$pendingStr = $pending ?? 0;
?>

<body>
<div class="sk-shell">

    <!-- ═══════════════ SIDEBAR ═══════════════ -->
    <aside class="sk-sidebar" id="skSidebar">

        <!-- Logo -->
        <div class="sk-logo">
            <div class="sk-logo-mark"><?= sk_icon('book', 17) ?></div>
            <div class="sk-logo-text">
                <span class="sk-logo-name">eLibReserve</span>
                <span class="sk-logo-tag">Community Library</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sk-nav">
            <span class="sk-nav-label">Main</span>

            <a href="<?= function_exists('base_url') ? base_url('/dashboard') : '#' ?>" class="sk-nav-link active">
                <?= sk_icon('home', 16) ?> Dashboard
            </a>
            <a href="<?= function_exists('base_url') ? base_url('/reservation') : '#' ?>" class="sk-nav-link">
                <?= sk_icon('plus', 16) ?> New Reservation
            </a>
            <a href="<?= function_exists('base_url') ? base_url('/reservation-list') : '#' ?>" class="sk-nav-link">
                <?= sk_icon('calendar', 16) ?> My Reservations
                <?php if ($pendingStr > 0): ?><span class="nav-count"><?= $pendingStr ?></span><?php endif; ?>
            </a>

            <div class="sk-nav-divider"></div>
            <span class="sk-nav-label">Library</span>

            <a href="<?= function_exists('base_url') ? base_url('/books') : '#' ?>" class="sk-nav-link">
                <?= sk_icon('book', 16) ?> Browse Books
            </a>
            <a href="<?= function_exists('base_url') ? base_url('/books') : '#' ?>#mine" class="sk-nav-link">
                <?= sk_icon('bookmark', 16) ?> My Borrows
            </a>

            <div class="sk-nav-divider"></div>
            <span class="sk-nav-label">Account</span>

            <a href="<?= function_exists('base_url') ? base_url('/profile') : '#' ?>" class="sk-nav-link">
                <?= sk_icon('user', 16) ?> Profile
            </a>
            <a href="<?= function_exists('base_url') ? base_url('/logout') : '#' ?>" class="sk-nav-link danger">
                <?= sk_icon('logout', 16) ?> Sign Out
            </a>
        </nav>

        <!-- User footer card -->
        <div class="sk-sidebar-footer">
            <div class="sk-footer-avatar"><?= esc($initials) ?></div>
            <div class="sk-footer-info">
                <div class="sk-footer-name"><?= esc($user_name ?? 'User') ?></div>
                <div class="sk-footer-role">Resident</div>
            </div>
            <div class="sk-footer-dot" title="Online"></div>
        </div>
    </aside>

    <!-- ═══════════════ MAIN ═══════════════ -->
    <div class="sk-main">

        <!-- ── Topbar ── -->
        <header class="sk-topbar">
            <button class="sk-icon-btn" style="display:none;border-radius:10px;" id="menuBtn" onclick="document.getElementById('skSidebar').classList.toggle('open')" aria-label="Menu">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width:18px;height:18px;">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <div class="sk-search">
                <span class="sk-search-icon"><?= sk_icon('search', 15) ?></span>
                <input type="text" class="sk-search-input" placeholder="Search books, reservations…" autocomplete="off"/>
            </div>

            <div class="sk-topbar-actions">
                <button class="sk-icon-btn" onclick="toggleDark()" title="Toggle theme" aria-label="Toggle dark mode">
                    <span id="themeIconLight"><?= sk_icon('moon', 16) ?></span>
                    <span id="themeIconDark" style="display:none;"><?= sk_icon('sun', 16) ?></span>
                </button>

                <button class="sk-icon-btn" onclick="toggleNotifications()" style="position:relative;" aria-label="Notifications">
                    <?= sk_icon('bell', 16) ?>
                    <span class="sk-notif-dot" id="notifDot" style="display:none;"></span>
                </button>

                <a href="<?= function_exists('base_url') ? base_url('/reservation') : '#' ?>" class="sk-reserve-btn">
                    <?= sk_icon('plus', 15) ?> Reserve
                </a>

                <div class="sk-avatar" title="<?= esc($user_name ?? '') ?>"><?= esc($initials) ?></div>
            </div>
        </header>

        <!-- ── Notification dropdown ── -->
        <div id="skNotifDD" class="sk-notif-dd">
            <div style="padding:12px 14px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13px;font-weight:700;color:var(--text);">Notifications</span>
                <button onclick="markAllRead()" style="font-size:11px;color:var(--purple);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
            </div>
            <div id="skNotifList" style="max-height:260px;overflow-y:auto;"></div>
        </div>

        <!-- ── Scrollable content ── -->
        <div class="sk-scroll" id="skScroll">

            <!-- Flash -->
            <?php if (function_exists('session') && session()->getFlashdata('success')): ?>
                <div class="sk-flash">
                    <?= sk_icon('check-c', 16) ?>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Next-action banner -->
            <?php if ($nextAction): $np = $nextPalette[$nextAction['color']] ?? $nextPalette['purple']; ?>
                <div class="sk-next" style="background:<?= $np[0] ?>;border-color:<?= $np[1] ?>;">
                    <div class="sk-next-icon" style="background:<?= $np[2] ?>;color:<?= $np[3] ?>;">
                        <?= sk_icon($nextAction['icon'], 16) ?>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:<?= $np[3] ?>;margin-bottom:3px;">What to do next</p>
                        <p style="font-size:13px;color:var(--text-2);line-height:1.55;"><?= $nextAction['msg'] ?></p>
                        <a href="<?= function_exists('base_url') ? base_url($nextAction['url']) : '#' ?>"
                           style="display:inline-flex;align-items:center;gap:5px;margin-top:9px;padding:8px 15px;border-radius:50px;font-size:12px;font-weight:700;color:white;background:<?= $np[3] ?>;text-decoration:none;transition:opacity .2s;"
                           onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                            <?= $nextAction['cta'] ?> <?= sk_icon('arrow-r', 12) ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ═══ HERO BANNER ═══ -->
            <div class="sk-hero">
                <div class="sk-hero-text">
                    <div class="sk-hero-eyebrow"><?= $greeting ?> &middot; <?= date('l, F j') ?></div>
                    <div class="sk-hero-name">Hi, <?= esc(explode(' ', $user_name ?? 'there')[0]) ?>!</div>
                    <div class="sk-hero-sub">
                        <?php if (!empty($reservations)): ?>
                            You have <strong style="color:white;"><?= count($reservations) ?> reservation<?= count($reservations)>1?'s':'' ?></strong>
                            and <?= $availableCount ?> books available to borrow today.
                        <?php else: ?>
                            Welcome to eLibReserve. Book resources and browse <?= $totalBooks ?> titles anytime.
                        <?php endif; ?>
                    </div>
                    <a href="<?= function_exists('base_url') ? base_url('/reservation') : '#' ?>" class="sk-hero-cta">
                        <?= sk_icon('plus', 14) ?> Make a Reservation
                    </a>
                </div>
                <div class="sk-hero-books">
                    <div style="transform:translateY(12px) rotate(-6deg);opacity:.85;"><?= book_art('sky') ?></div>
                    <div style="transform:translateY(0px);z-index:2;position:relative;"><?= book_art('rose') ?></div>
                    <div style="transform:translateY(16px) rotate(8deg);opacity:.9;"><?= book_art('amber') ?></div>
                </div>
            </div>

            <!-- ═══ STATS ═══ -->
            <div class="sk-stats">
                <div class="sk-stat">
                    <div class="sk-stat-icon" style="background:#EDE9FE;color:#7C3AED;"><?= sk_icon('calendar', 18) ?></div>
                    <div class="sk-stat-val"><?= $total ?? 0 ?></div>
                    <div class="sk-stat-lbl">Total Reservations</div>
                    <div class="sk-stat-trend" style="color:#7C3AED;"><?= sk_icon('trending', 11) ?> All time</div>
                </div>
                <div class="sk-stat">
                    <div class="sk-stat-icon" style="background:#FEF3C7;color:#D97706;"><?= sk_icon('clock', 18) ?></div>
                    <div class="sk-stat-val" style="color:#D97706;"><?= $pending ?? 0 ?></div>
                    <div class="sk-stat-lbl">Awaiting Approval</div>
                    <div class="sk-stat-trend" style="color:#D97706;"><?= sk_icon('eye', 11) ?> Under review</div>
                </div>
                <div class="sk-stat">
                    <div class="sk-stat-icon" style="background:#DCFCE7;color:#16A34A;"><?= sk_icon('check-c', 18) ?></div>
                    <div class="sk-stat-val" style="color:#16A34A;"><?= $approved ?? 0 ?></div>
                    <div class="sk-stat-lbl">Approved Slots</div>
                    <div class="sk-stat-trend" style="color:#16A34A;"><?= sk_icon('check', 11) ?> Ready to use</div>
                </div>
                <div class="sk-stat">
                    <div class="sk-stat-icon" style="background:#F5F3FF;color:#7C3AED;"><?= sk_icon('book', 18) ?></div>
                    <div class="sk-stat-val"><?= $availableCount ?></div>
                    <div class="sk-stat-lbl">Books Available</div>
                    <div class="sk-stat-trend" style="color:#7C3AED;"><?= sk_icon('bookmark', 11) ?> of <?= $totalBooks ?> titles</div>
                </div>
            </div>

            <!-- ═══ POPULAR BOOKS ═══ -->
            <?php if (!empty($featuredBooks)): ?>
                <div class="sk-section-head">
                    <span class="sk-section-title">Available Now</span>
                    <a href="<?= function_exists('base_url') ? base_url('/books') : '#' ?>" class="sk-section-link">
                        View all <?= sk_icon('arrow-r', 12) ?>
                    </a>
                </div>
                <div class="sk-books-grid">
                    <?php foreach (array_slice($featuredBooks, 0, 8) as $i => $book):
                        $pal   = $artPalettes[$i % count($artPalettes)];
                        $avail = (int)($book['available_copies'] ?? 0);
                        $pillBg = $avail === 0 ? '#FEE2E2' : ($avail <= 1 ? '#FEF3C7' : '#DCFCE7');
                        $pillFg = $avail === 0 ? '#991B1B' : ($avail <= 1 ? '#92400E' : '#166534');
                        $pillTx = $avail === 0 ? 'Out' : ($avail <= 1 ? '1 left' : $avail.' left');
                        $artBg = [
                            'rose'   =>'linear-gradient(145deg,#FFE4EC 0%,#FFCCD8 100%)',
                            'violet' =>'linear-gradient(145deg,#EDE9FE 0%,#DDD6FE 100%)',
                            'sky'    =>'linear-gradient(145deg,#E0F2FE 0%,#BAE6FD 100%)',
                            'amber'  =>'linear-gradient(145deg,#FEF9C3 0%,#FDE68A 100%)',
                            'emerald'=>'linear-gradient(145deg,#DCFCE7 0%,#BBF7D0 100%)',
                            'indigo' =>'linear-gradient(145deg,#EEF2FF 0%,#C7D2FE 100%)',
                            'coral'  =>'linear-gradient(145deg,#FEE2E2 0%,#FECACA 100%)',
                            'teal'   =>'linear-gradient(145deg,#CCFBF1 0%,#99F6E4 100%)',
                        ][$pal];
                    ?>
                        <a href="<?= function_exists('base_url') ? base_url('/books') : '#' ?>" class="sk-book-card" style="text-decoration:none;">
                            <div class="sk-book-art" style="background:<?= $artBg ?>;"><?= book_art($pal) ?></div>
                            <div class="sk-book-body">
                                <div class="sk-book-label">Book</div>
                                <div class="sk-book-title"><?= esc($book['title']) ?></div>
                                <div class="sk-book-author"><?= esc($book['author'] ?? 'Unknown Author') ?></div>
                                <div class="sk-book-meta">
                                    <span class="sk-avail-pill" style="background:<?= $pillBg ?>;color:<?= $pillFg ?>;"><?= $pillTx ?></span>
                                    <div class="sk-stars">
                                        <?= sk_icon('star', 11) ?>
                                        <span style="font-size:11px;font-weight:600;color:var(--text-2);margin-left:2px;">4.<?= (int)($i*1.3+4)%10 ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ═══ MY ACTIVE BORROWS ═══ -->
            <?php
            $activeBorrows = array_slice(
                array_values(array_filter($myBorrowings, fn($b)=>in_array($b['status']??'',['approved','pending']))),
                0, 4
            );
            if (!empty($activeBorrows)):
            ?>
                <div class="sk-section-head">
                    <span class="sk-section-title">My Active Borrows</span>
                    <a href="<?= function_exists('base_url') ? base_url('/books') : '#' ?>#mine" class="sk-section-link">
                        View all <?= sk_icon('arrow-r', 12) ?>
                    </a>
                </div>
                <div class="sk-books-grid" style="margin-bottom:28px;">
                    <?php foreach ($activeBorrows as $i => $borrow):
                        $pal   = $artPalettes[($i+3) % count($artPalettes)];
                        $due   = !empty($borrow['due_date']) ? strtotime($borrow['due_date']) : null;
                        $over  = $due && $due < time();
                        $soon  = $due && !$over && $due < time()+3*86400;
                        $artBg = ['rose'=>'linear-gradient(145deg,#FFE4EC 0%,#FFCCD8 100%)','violet'=>'linear-gradient(145deg,#EDE9FE 0%,#DDD6FE 100%)','sky'=>'linear-gradient(145deg,#E0F2FE 0%,#BAE6FD 100%)','amber'=>'linear-gradient(145deg,#FEF9C3 0%,#FDE68A 100%)','emerald'=>'linear-gradient(145deg,#DCFCE7 0%,#BBF7D0 100%)','indigo'=>'linear-gradient(145deg,#EEF2FF 0%,#C7D2FE 100%)','coral'=>'linear-gradient(145deg,#FEE2E2 0%,#FECACA 100%)','teal'=>'linear-gradient(145deg,#CCFBF1 0%,#99F6E4 100%)'][$pal];
                        $bs    = strtolower($borrow['status']??'pending');
                        $tagCls= $over?'declined':($soon?'pending':$bs);
                        $tagTx = $over?'Overdue':($soon?'Due Soon':ucfirst($bs));
                    ?>
                        <div class="sk-book-card">
                            <div class="sk-book-art" style="background:<?= $artBg ?>;"><?= book_art($pal) ?></div>
                            <div class="sk-book-body">
                                <div class="sk-book-label">Borrowed</div>
                                <div class="sk-book-title"><?= esc($borrow['title']??'Unknown') ?></div>
                                <?php if ($due && $bs==='approved'): ?>
                                    <div class="sk-book-author" style="color:<?= $over?'#EF4444':($soon?'#D97706':'') ?>;">Due: <?= date('M j, Y', $due) ?></div>
                                <?php else: ?>
                                    <div class="sk-book-author">&nbsp;</div>
                                <?php endif; ?>
                                <div class="sk-book-meta">
                                    <span class="tag tag-<?= $tagCls ?>"><?= $tagTx ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ═══ CALENDAR + SIDE PANEL ═══ -->
            <div class="sk-grid-2">

                <!-- Calendar -->
                <div class="sk-card sk-card-pad">
                    <div class="sk-card-head">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:10px;background:var(--purple-lt);color:var(--purple);display:flex;align-items:center;justify-content:center;">
                                <?= sk_icon('calendar', 16) ?>
                            </div>
                            <div>
                                <div class="sk-card-title">Community Schedule</div>
                                <div class="sk-card-sub">Tap a date to see reservations</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                            <?php foreach ([['#10b981','Approved'],['#fbbf24','Pending'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                                <div style="display:flex;align-items:center;gap:4px;">
                                    <div style="width:8px;height:8px;border-radius:50%;background:<?= $c ?>;flex-shrink:0;"></div>
                                    <span style="font-size:11px;color:var(--text-3);font-weight:500;"><?= $l ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div id="dashboard-calendar"></div>
                </div>

                <!-- Side column -->
                <div style="display:flex;flex-direction:column;gap:14px;">

                    <!-- Quick Actions -->
                    <div class="sk-card sk-card-pad">
                        <div class="sk-card-title" style="margin-bottom:12px;">Quick Actions</div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <a href="<?= function_exists('base_url') ? base_url('/reservation') : '#' ?>" class="sk-qa-link">
                                <div class="sk-qa-icon" style="background:#EDE9FE;color:#7C3AED;"><?= sk_icon('plus', 16) ?></div>
                                New Reservation
                                <span style="margin-left:auto;color:var(--text-3);"><?= sk_icon('chevron-r', 14) ?></span>
                            </a>
                            <a href="<?= function_exists('base_url') ? base_url('/reservation-list') : '#' ?>" class="sk-qa-link">
                                <div class="sk-qa-icon" style="background:#FEF3C7;color:#D97706;"><?= sk_icon('calendar', 16) ?></div>
                                My Reservations
                                <?php if (($pending??0) > 0): ?>
                                    <span style="margin-left:auto;background:#FEF3C7;color:#92400E;font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px;"><?= $pending ?></span>
                                <?php else: ?>
                                    <span style="margin-left:auto;color:var(--text-3);"><?= sk_icon('chevron-r', 14) ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?= function_exists('base_url') ? base_url('/books') : '#' ?>" class="sk-qa-link">
                                <div class="sk-qa-icon" style="background:#DCFCE7;color:#16A34A;"><?= sk_icon('book', 16) ?></div>
                                Browse Library
                                <span style="margin-left:auto;color:var(--text-3);"><?= sk_icon('chevron-r', 14) ?></span>
                            </a>
                            <a href="<?= function_exists('base_url') ? base_url('/profile') : '#' ?>" class="sk-qa-link">
                                <div class="sk-qa-icon" style="background:#F5F3FF;color:#7C3AED;"><?= sk_icon('user', 16) ?></div>
                                View Profile
                                <span style="margin-left:auto;color:var(--text-3);"><?= sk_icon('chevron-r', 14) ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="sk-card sk-card-pad" style="flex:1;">
                        <div class="sk-card-head">
                            <div class="sk-card-title">Recent Bookings</div>
                            <a href="<?= function_exists('base_url') ? base_url('/reservation-list') : '#' ?>" class="sk-section-link" style="font-size:11px;">
                                All <?= sk_icon('arrow-r', 11) ?>
                            </a>
                        </div>
                        <?php if (!empty($processedRecent)): ?>
                            <div>
                                <?php foreach (array_slice($processedRecent, 0, 5) as $res):
                                    $s  = $res['_status'];
                                    $dt = new DateTime($res['reservation_date']);
                                    $avatarColors = ['claimed'=>['#EDE9FE','#7C3AED'],'approved'=>['#DCFCE7','#16A34A'],'pending'=>['#FEF3C7','#D97706'],'unclaimed'=>['#FFF7ED','#EA580C'],'declined'=>['#FEE2E2','#DC2626'],'expired'=>['#F1F5F9','#64748B']];
                                    [$abg,$afg] = $avatarColors[$s] ?? ['#EDE9FE','#7C3AED'];
                                ?>
                                    <a href="<?= function_exists('base_url') ? base_url('/reservation-list') : '#' ?>" class="bk-row" style="text-decoration:none;display:flex;">
                                        <div class="bk-avatar" style="background:<?= $abg ?>;color:<?= $afg ?>;"><?= $dt->format('j') ?></div>
                                        <div style="flex:1;min-width:0;margin-left:10px;">
                                            <div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($res['resource_name'] ?? 'Resource') ?></div>
                                            <div style="font-family:var(--mono);font-size:11px;color:var(--text-3);margin-top:1px;">
                                                <?= date('M j', strtotime($res['reservation_date'])) ?> &middot; <?= date('g:iA', strtotime($res['start_time'])) ?>
                                            </div>
                                        </div>
                                        <span class="tag tag-<?= $s ?>" style="align-self:center;"><?= $s==='unclaimed'?'No-show':ucfirst($s) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;padding:28px 10px;">
                                <div style="color:var(--text-3);display:flex;justify-content:center;margin-bottom:8px;"><?= sk_icon('calendar', 28) ?></div>
                                <p style="font-size:12px;color:var(--text-3);">No bookings yet</p>
                                <a href="<?= function_exists('base_url') ? base_url('/reservation') : '#' ?>"
                                   style="display:inline-flex;align-items:center;gap:4px;margin-top:9px;font-size:12px;font-weight:700;color:var(--purple);text-decoration:none;">
                                    <?= sk_icon('plus', 12) ?> First reservation
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Activity Snapshot -->
                    <div class="sk-card sk-card-pad">
                        <div class="sk-card-title" style="margin-bottom:14px;">Activity Snapshot</div>
                        <?php
                        $achieves = [];
                        if (($total??0) >= 1)     $achieves[] = ['color'=>'violet','letter'=>'R','name'=>'First Reservation','sub'=>'Made your first booking'];
                        if (($approved??0) >= 3)  $achieves[] = ['color'=>'sky','letter'=>'A','name'=>'Approved ×3','sub'=>'3 reservations approved'];
                        if ($claimedCount >= 1)   $achieves[] = ['color'=>'emerald','letter'=>'C','name'=>'Ticket Claimed','sub'=>'Scanned your first e-ticket'];
                        if (count($myBorrowings)>=1) $achieves[] = ['color'=>'amber','letter'=>'B','name'=>'First Borrow','sub'=>'Borrowed from the library'];
                        if (empty($achieves)):
                        ?>
                            <p style="font-size:12px;color:var(--text-3);text-align:center;padding:12px 0;">Make your first reservation to earn badges!</p>
                        <?php else: foreach ($achieves as $a): ?>
                            <div class="sk-achieve-row">
                                <?= achievement_art($a['color'], $a['letter']) ?>
                                <div>
                                    <div class="sk-achieve-name"><?= esc($a['name']) ?></div>
                                    <div class="sk-achieve-sub"><?= esc($a['sub']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>

                </div>
            </div>

            <!-- Quota bar -->
            <?php $used = 3 - ($remaining ?? 3); ?>
            <div class="sk-card sk-card-pad" style="margin-bottom:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <span style="font-size:13px;font-weight:600;color:var(--text);">Monthly Reservation Quota</span>
                    <span style="font-family:var(--mono);font-size:13px;font-weight:700;color:var(--purple);"><?= $used ?> / 3 used</span>
                </div>
                <div style="height:7px;background:var(--purple-lt);border-radius:99px;overflow:hidden;">
                    <div style="height:100%;width:<?= min(100, round($used/3*100)) ?>%;background:linear-gradient(90deg,var(--purple),#9F7AEA);border-radius:99px;transition:width .6s ease;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:7px;">
                    <span style="font-size:11px;color:var(--text-3);">Resets on the 1st of next month</span>
                    <span style="font-size:11px;color:var(--text-3);"><?= 3 - $used ?> slot<?= (3-$used)!==1?'s':'' ?> remaining</span>
                </div>
            </div>

        </div><!-- /sk-scroll -->
    </div><!-- /sk-main -->
</div><!-- /sk-shell -->

<!-- ═══ DATE MODAL ═══ -->
<div id="dateModal" class="sk-modal-back" onclick="if(event.target===this)closeDateModal()">
    <div class="sk-modal">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div>
                <h3 style="font-size:15px;font-weight:700;" id="modalDateTitle"></h3>
                <p style="font-size:11px;color:var(--text-3);margin-top:2px;" id="modalDateSub"></p>
            </div>
            <button onclick="closeDateModal()" style="width:34px;height:34px;border-radius:10px;background:var(--bg);border:1px solid var(--border);cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-2);">
                <?= sk_icon('x', 14) ?>
            </button>
        </div>
        <div id="modalList"></div>
        <div id="modalEmpty" style="display:none;text-align:center;padding:24px;">
            <p style="font-size:13px;color:var(--text-3);">No reservations on this date.</p>
        </div>
        <button onclick="closeDateModal()" style="margin-top:14px;width:100%;padding:11px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r-sm);font-family:var(--font);font-size:13px;font-weight:600;color:var(--text-2);cursor:pointer;">Close</button>
    </div>
</div>

<script>
const NOTIF_KEY  = 'sk_notifs_<?= session()->get('user_id') ?? 0 ?>';
const reservations = <?= json_encode($reservations ?? []) ?>;
const allResData   = <?= json_encode($allReservations ?? []) ?>;
const approvedRes  = reservations.filter(r => r.status === 'approved' && !r.claimed);

function toggleDark() {
    const isDark = document.body.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    document.getElementById('themeIconLight').style.display = isDark ? 'none' : '';
    document.getElementById('themeIconDark').style.display  = isDark ? '' : 'none';
}
(function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark');
        document.getElementById('themeIconLight').style.display = 'none';
        document.getElementById('themeIconDark').style.display  = '';
    }
})();

function checkWidth() {
    document.getElementById('menuBtn').style.display = window.innerWidth <= 900 ? 'flex' : 'none';
}
checkWidth();
window.addEventListener('resize', checkWidth);
document.addEventListener('click', e => {
    const sb = document.getElementById('skSidebar');
    if (sb.classList.contains('open') && !sb.contains(e.target) && !document.getElementById('menuBtn').contains(e.target)) {
        sb.classList.remove('open');
    }
});

let notifications = [];
const getSeenIds = () => { try { return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]'); } catch { return []; } };
const saveSeenIds = ids => localStorage.setItem(NOTIF_KEY, JSON.stringify(ids));

function loadNotifications() {
    const seen = getSeenIds();
    notifications = reservations.filter(r => r.status === 'approved').map(r => ({
        id: +r.id,
        title: 'Reservation Approved',
        msg: `${r.resource_name || 'Resource'} · ${new Date(r.reservation_date + 'T00:00').toLocaleDateString('en-US', {month:'short',day:'numeric'})}`,
        time: r.updated_at || r.created_at || new Date().toISOString(),
        read: seen.includes(+r.id)
    }));
    updateNotifDot();
    renderNotifs();
}

function markAllRead() {
    saveSeenIds([...new Set([...getSeenIds(), ...notifications.map(n => n.id)])]);
    notifications.forEach(n => n.read = true);
    updateNotifDot();
    renderNotifs();
}

function updateNotifDot() {
    const dot = document.getElementById('notifDot');
    dot.style.display = notifications.some(n => !n.read) ? 'block' : 'none';
}

function renderNotifs() {
    const list = document.getElementById('skNotifList');
    if (!notifications.length) {
        list.innerHTML = '<div style="text-align:center;padding:22px;"><p style="font-size:12px;color:var(--text-3);">All caught up!</p></div>';
        return;
    }
    list.innerHTML = notifications.sort((a,b) => new Date(b.time)-new Date(a.time)).map(n => `
        <div class="sk-notif-item ${!n.read?'unread':''}" onclick="markOneRead(${n.id})">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;background:var(--purple-lt);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--purple);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width:12px;height:12px;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:12px;font-weight:700;color:var(--text);">${n.title}</p>
                    <p style="font-size:11px;color:var(--text-3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${n.msg}</p>
                </div>
                ${!n.read?'<span style="width:6px;height:6px;background:var(--purple);border-radius:50%;flex-shrink:0;"></span>':''}
            </div>
        </div>`).join('');
}

function markOneRead(id) {
    const ids = getSeenIds();
    if (!ids.includes(id)) saveSeenIds([...ids, id]);
    const n = notifications.find(n => n.id === id);
    if (n) { n.read = true; updateNotifDot(); renderNotifs(); }
}

function toggleNotifications() {
    document.getElementById('skNotifDD').classList.toggle('show');
}
document.addEventListener('click', e => {
    const dd = document.getElementById('skNotifDD');
    if (!dd.contains(e.target) && !e.target.closest('[onclick="toggleNotifications()"]')) dd.classList.remove('show');
});

function openDateModal(date, items) {
    const d = new Date(date + 'T00:00');
    document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US', {weekday:'long',month:'long',day:'numeric',year:'numeric'});
    document.getElementById('modalDateSub').textContent = items.length ? `${items.length} reservation${items.length>1?'s':''}` : '';
    const list = document.getElementById('modalList');
    const empty = document.getElementById('modalEmpty');
    list.innerHTML = '';
    if (items.length) {
        empty.style.display = 'none';
        const cm = {approved:'#DCFCE7|#166534',pending:'#FEF3C7|#92400E',declined:'#FEE2E2|#991B1B',claimed:'#EDE9FE|#5B21B6'};
        items.sort((a,b) => (a.start_time||'').localeCompare(b.start_time||'')).forEach(r => {
            const isCl = r.claimed==1||r.status==='claimed'||!!r.claimed_at;
            const s    = isCl?'claimed':(r.status||'pending').toLowerCase();
            const [bg,fg] = (cm[s]||'#F1F5F9|#475569').split('|');
            const t    = r.start_time ? r.start_time.substring(0,5) : 'All day';
            const et   = r.end_time   ? r.end_time.substring(0,5)   : '';
            const row  = document.createElement('div');
            row.style.cssText = 'display:flex;align-items:center;gap:10px;padding:.7rem;border-bottom:1px solid var(--border);border-radius:10px;';
            row.innerHTML = `
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Reserved'}</p>
                    <p style="font-family:var(--mono);font-size:11px;color:var(--purple);margin-top:2px;font-weight:600;">${t}${et?' – '+et:''}</p>
                </div>
                <span style="padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;text-transform:uppercase;background:${bg};color:${fg};">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
            list.appendChild(row);
        });
    } else { empty.style.display = 'block'; }
    document.getElementById('dateModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDateModal() {
    document.getElementById('dateModal').classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

document.addEventListener('DOMContentLoaded', () => {
    loadNotifications();

    const byDate = {};
    allResData.forEach(r => {
        if (!r.reservation_date) return;
        if (!byDate[r.reservation_date]) byDate[r.reservation_date] = [];
        byDate[r.reservation_date].push(r);
    });

    const colorMap = {approved:'#10b981',pending:'#fbbf24',declined:'#f87171',canceled:'#f87171',claimed:'#a855f7'};
    const events = allResData.filter(r => r.reservation_date).map(r => {
        const isCl = r.claimed==1||r.status==='claimed'||!!r.claimed_at;
        const s    = isCl ? 'claimed' : (r.status||'pending').toLowerCase();
        const d    = r.reservation_date.trim();
        return {
            title: r.resource_name || 'Reservation',
            start: d + (r.start_time ? 'T' + r.start_time.substring(0,8) : ''),
            end:   d + (r.end_time   ? 'T' + r.end_time.substring(0,8)   : ''),
            allDay: !r.start_time,
            backgroundColor: colorMap[s] || '#94a3b8',
            borderColor: 'transparent',
            textColor: '#fff',
        };
    });

    const w   = window.innerWidth;
    const cal = new FullCalendar.Calendar(document.getElementById('dashboard-calendar'), {
        initialView: w < 480 ? 'listWeek' : 'dayGridMonth',
        headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
        events,
        height: w < 640 ? 'auto' : 310,
        eventDisplay: 'block',
        eventMaxStack: 2,
        dateClick: info => openDateModal(info.dateStr, byDate[info.dateStr] || []),
        eventClick: info => {
            const d = info.event.startStr.split('T')[0];
            openDateModal(d, byDate[d] || []);
        },
        dayCellDidMount: info => {
            const d = info.date.toISOString().split('T')[0];
            const items = byDate[d];
            if (items && items.length) {
                const badge = document.createElement('div');
                badge.style.cssText = 'font-family:var(--mono);font-size:8px;font-weight:700;color:white;background:#7C3AED;border-radius:999px;width:13px;height:13px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:2px;';
                badge.textContent = items.length;
                info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);
            }
        }
    });
    cal.render();
});
</script>

<?php if (function_exists('base_url') && defined('APPPATH')): ?>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
<?php endif; ?>
</body>
</html>