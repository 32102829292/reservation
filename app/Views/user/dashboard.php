<?php
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

$remaining = $remainingReservations ?? 3;
$maxSlots  = 3;
$usedSlots = $maxSlots - $remaining;
$featuredBooks  = $featuredBooks  ?? [];
$myBorrowings   = $myBorrowings   ?? [];
$availableCount = $availableCount ?? 0;
$totalBooks     = $totalBooks     ?? 0;

$upcoming = null;
if (!empty($reservations)) {
    $now = time();
    foreach ($reservations as $r) {
        if (($r['status'] ?? '') === 'approved' && empty($r['claimed'])) {
            $dt = strtotime($r['reservation_date'] . ' ' . ($r['end_time'] ?? '23:59'));
            if ($dt > $now) { $upcoming = $r; break; }
        }
    }
}

$nextAction = null;
if ($pending > 0) {
    $nextAction = ['type'=>'pending','msg'=>"You have {$pending} reservation".($pending>1?'s':'')." awaiting approval. SK officers usually respond within 24 hours.",'cta'=>'View Reservations','url'=>'/reservation-list','color'=>'amber'];
} elseif ($upcoming) {
    $nextAction = ['type'=>'upcoming','msg'=>"Your approved slot is coming up. Download your e-ticket from My Reservations and scan it at the entrance when you arrive.",'cta'=>'Get E-Ticket','url'=>'/reservation-list','color'=>'blue'];
} elseif ($unclaimedCount > 0) {
    $nextAction = ['type'=>'unclaimed','msg'=>"You missed {$unclaimedCount} approved slot".($unclaimedCount>1?'s':'')." without showing up. Please cancel in advance if you can't attend — repeated no-shows may limit future bookings.",'cta'=>'See Details','url'=>'/reservation-list','color'=>'orange'];
} elseif ($remaining === 0) {
    $nextAction = ['type'=>'quota','msg'=>"You've used all 3 reservation slots for this month. Your quota resets on the 1st of next month.",'cta'=>'Browse Library','url'=>'/books','color'=>'slate'];
} elseif (empty($reservations)) {
    $nextAction = ['type'=>'empty','msg'=>"Welcome! You haven't made any reservations yet. Book a computer, study space, or other facility anytime.",'cta'=>'Make First Reservation','url'=>'/reservation','color'=>'blue'];
}

$nextColors = [
    'amber'  => ['bg'=>'rgba(251,191,36,.08)','border'=>'rgba(251,191,36,.25)','icon_bg'=>'rgba(251,191,36,.15)','icon_fg'=>'#d97706','btn_bg'=>'#d97706','icon'=>'clock'],
    'blue'   => ['bg'=>'rgba(99,102,241,.06)','border'=>'rgba(99,102,241,.2)','icon_bg'=>'rgba(99,102,241,.12)','icon_fg'=>'#4338ca','btn_bg'=>'#4338ca','icon'=>'ticket'],
    'orange' => ['bg'=>'rgba(234,88,12,.06)','border'=>'rgba(234,88,12,.2)','icon_bg'=>'rgba(234,88,12,.1)','icon_fg'=>'#ea580c','btn_bg'=>'#ea580c','icon'=>'triangle'],
    'slate'  => ['bg'=>'rgba(100,116,139,.05)','border'=>'rgba(100,116,139,.15)','icon_bg'=>'rgba(100,116,139,.1)','icon_fg'=>'#64748b','btn_bg'=>'#64748b','icon'=>'calendar-x'],
];

function icon(string $name, int $size = 16, string $stroke = 'currentColor', string $extra = ''): string {
    static $icons = [
        'house'         => ['<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'plus'          => ['<path d="M12 5v14M5 12h14" stroke-linecap="round"/>','1.8'],
        'calendar'      => ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>','1.5'],
        'book-open'     => ['<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>','1.5'],
        'user'          => ['<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'logout'        => ['<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'clock'         => ['<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>','1.8'],
        'check-circle'  => ['<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22 4 12 14.01 9 11.01"/>','1.8'],
        'ticket'        => ['<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'triangle'      => ['<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>','1.8'],
        'calendar-x'    => ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="14" x2="14" y2="18"/><line x1="14" y1="14" x2="10" y2="18"/>','1.5'],
        'bell'          => ['<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>','1.8'],
        'check'         => ['<polyline points="20 6 9 17 4 12"/>','1.8'],
        'x'             => ['<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>','1.8'],
        'chevron-right' => ['<polyline points="9 18 15 12 9 6"/>','1.8'],
        'arrow-right'   => ['<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>','1.8'],
        'ban'           => ['<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>','1.8'],
        'hourglass'     => ['<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'layers'        => ['<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>','1.8'],
        'list-check'    => ['<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'sparkles'      => ['<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'search'        => ['<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>','1.8'],
        'bookmark'      => ['<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>','1.5'],
        'info'          => ['<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>','1.8'],
        'check-double'  => ['<path d="M17 1l-8.5 8.5L6 7M22 6l-8.5 8.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 13l-4 4 1.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'calendar-days' => ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>','1.5'],
        'bar-chart'     => ['<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>','1.5'],
        'eye'           => ['<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>','1.8'],
        'trending-up'   => ['<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>','1.8'],
        'hand-wave'     => ['<path d="M18 11V6a2 2 0 00-2-2 2 2 0 00-2 2"/><path d="M14 10V4a2 2 0 00-2-2 2 2 0 00-2 2v2"/><path d="M10 10.5V6a2 2 0 00-2-2 2 2 0 00-2 2v8"/><path d="M18 8a2 2 0 014 0v6a8 8 0 01-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 012.83-2.82L7 15" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'glasses'       => ['<circle cx="5" cy="13" r="3"/><circle cx="19" cy="13" r="3"/><path d="M2 13l1-4h3M17 13l1-4h3M8 13h8" stroke-linecap="round" stroke-linejoin="round"/>','1.8'],
        'feather'       => ['<path d="M20.24 12.24a6 6 0 00-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/>','1.8'],
        'sun'           => ['<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>','1.5'],
        'star'          => ['<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>','1.5'],
        'zap'           => ['<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>','1.8'],
        'map-pin'       => ['<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>','1.8'],
    ];
    [$d, $sw] = $icons[$name] ?? ['<circle cx="12" cy="12" r="10"/>','1.8'];
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="%s" stroke-width="%s" style="width:%dpx;height:%dpx;flex-shrink:0;" %s>%s</svg>',
        $size, $size, htmlspecialchars($stroke, ENT_QUOTES), $sw, $size, $size, $extra, $d
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"/>
    <title>Dashboard | <?= esc($user_name) ?></title>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <meta name="theme-color" content="#1e1b4b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        (function(){try{if(localStorage.getItem('theme')==='dark')document.documentElement.classList.add('dark-pre');}catch(e){}})();
    </script>
    <style>
        /* ══════════════════════════════════════════════════════
           ENHANCED DASHBOARD — FULL REDESIGN
        ══════════════════════════════════════════════════════ */

        /* ── Custom Properties ── */
        :root {
            --indigo-50:  #eef2ff;
            --indigo-100: #e0e7ff;
            --indigo-400: #818cf8;
            --indigo-500: #6366f1;
            --indigo-600: #4f46e5;
            --indigo-700: #4338ca;
            --indigo-800: #3730a3;
            --indigo-900: #312e81;
            --indigo-950: #1e1b4b;
            --glow-indigo: 0 0 0 1px rgba(99,102,241,.12), 0 4px 16px rgba(67,56,202,.15);
            --glow-indigo-lg: 0 0 0 1px rgba(99,102,241,.15), 0 8px 32px rgba(67,56,202,.2);
            --ease-spring: cubic-bezier(.34,1.56,.64,1);
            --ease-smooth: cubic-bezier(.4,0,.2,1);
        }

        /* ── Base Layout ── */
        body { display:flex; height:100vh; height:100dvh; overflow:hidden; }
        html.dark-pre body,
        html.dark-pre .l-sidebar__inner,
        html.dark-pre .l-mobile-nav { background:#060e1e; }

        /* ── Scrollable Main Area ── */
        .main-area {
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        /* ── Topbar ── */
        .db-topbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 14px 20px 0;
            position: sticky;
            top: 0;
            z-index: 50;
            background: transparent;
        }
        @media(max-width:479px){ .db-topbar{ padding: 12px 14px 0; } }

        /* ── Reserve Button ── */
        .reserve-btn {
            display: none;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            background: linear-gradient(135deg, var(--indigo-600), var(--indigo-800));
            color: #fff;
            border-radius: 12px;
            font-family: var(--font);
            font-size: .82rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s var(--ease-smooth);
            box-shadow: 0 4px 14px rgba(67,56,202,.35), inset 0 1px 0 rgba(255,255,255,.1);
            letter-spacing: -.01em;
        }
        @media(min-width:480px){ .reserve-btn{ display:flex; } }
        .reserve-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(67,56,202,.45), inset 0 1px 0 rgba(255,255,255,.15);
            background: linear-gradient(135deg, var(--indigo-500), var(--indigo-700));
        }
        .reserve-btn:active { transform: translateY(0); }

        /* ── Notifications ── */
        .notif-bell { position: relative; }
        .notif-badge {
            position: absolute;
            top: -4px; right: -4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-family: var(--font);
            font-size: .55rem;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 999px;
            min-width: 17px;
            text-align: center;
            border: 2px solid var(--bg);
            line-height: 1.3;
            pointer-events: none;
            box-shadow: 0 2px 8px rgba(239,68,68,.3);
        }
        .notif-dd {
            position: fixed;
            top: 72px; right: 16px;
            width: 320px;
            background: var(--card);
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15), 0 0 0 1px rgba(99,102,241,.1);
            z-index: 200;
            display: none;
            overflow: hidden;
        }
        .notif-dd.show { display:block; animation: db-fade-drop .2s var(--ease-spring) both; }
        @keyframes db-fade-drop {
            from { opacity:0; transform:translateY(-8px) scale(.97); }
            to   { opacity:1; transform:translateY(0)   scale(1); }
        }
        .notif-item { padding:.9rem 1.1rem; border-bottom:1px solid var(--border-subtle); transition:background .15s; cursor:pointer; }
        .notif-item:hover { background: var(--input-bg); }
        .notif-item.unread { background: rgba(99,102,241,.04); }
        .notif-item.unread::before { content:''; display:block; width:3px; height:100%; background:var(--indigo); position:absolute; left:0; border-radius:0 2px 2px 0; }
        .notif-item { position:relative; }
        .notif-item:last-child { border-bottom:none; }
        @media(max-width:479px){ .notif-dd{left:12px;right:12px;width:auto;top:68px;} }

        /* ── Flash Message ── */
        .flash-ok {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 20px 14px;
            padding: 12px 16px;
            background: linear-gradient(135deg, rgba(16,185,129,.08), rgba(16,185,129,.04));
            border: 1px solid rgba(16,185,129,.25);
            border-radius: 12px;
            font-family: var(--font);
            font-size: .82rem;
            font-weight: 600;
            color: #065f46;
            animation: db-slide-in .4s var(--ease-spring) both;
        }
        body.dark .flash-ok { color: #34d399; background: rgba(16,185,129,.08); border-color: rgba(16,185,129,.2); }

        /* ── Animations ── */
        @keyframes db-slide-in {
            from { opacity:0; transform:translateY(-10px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes db-fade-up {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes db-pulse {
            0%,100% { opacity:1; }
            50%      { opacity:.3; }
        }
        @keyframes db-shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        .fade-up   { animation: db-fade-up .5s var(--ease-smooth) .05s both; }
        .fade-up-1 { animation: db-fade-up .5s var(--ease-smooth) .12s both; }
        .fade-up-2 { animation: db-fade-up .5s var(--ease-smooth) .2s  both; }
        .fade-up-3 { animation: db-fade-up .5s var(--ease-smooth) .28s both; }
        .fade-up-4 { animation: db-fade-up .5s var(--ease-smooth) .36s both; }

        /* ══════════════════════════════════════════════════════
           HERO BANNER
        ══════════════════════════════════════════════════════ */
        .hero-banner {
            margin: 14px 20px 18px;
            border-radius: 24px;
            padding: 28px 32px 26px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(125deg,
                #1e1b4b 0%,
                #2d2a7a 20%,
                #3730a3 50%,
                #4338ca 75%,
                #3730a3 100%
            );
            box-shadow:
                0 20px 60px rgba(55,48,163,.35),
                0 0 0 1px rgba(255,255,255,.08) inset,
                0 1px 0 rgba(255,255,255,.12) inset;
        }
        @media(max-width:599px){ .hero-banner{ margin:12px 14px 14px; padding:20px 18px 20px; border-radius:18px; } }
        .hero-banner::before {
            content:'';
            position:absolute; inset:0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='.06'/%3E%3C/svg%3E");
            pointer-events:none; z-index:1;
        }
        .hero-banner::after {
            content:'';
            position:absolute; top:-80px; right:-60px;
            width:320px; height:320px;
            background: radial-gradient(circle, rgba(165,180,252,.18) 0%, rgba(99,102,241,.06) 50%, transparent 70%);
            pointer-events:none; z-index:1;
        }
        .hero-streak {
            position:absolute; bottom:0; left:0; right:0; height:1px;
            background:linear-gradient(90deg,transparent,rgba(165,180,252,.3),transparent);
            z-index:2;
        }
        .hero-dots {
            position:absolute; bottom:-10px; right:160px;
            width:120px; height:80px;
            background-image: radial-gradient(circle, rgba(255,255,255,.12) 1px, transparent 1px);
            background-size: 12px 12px;
            z-index:1; pointer-events:none;
        }
        @media(max-width:599px){ .hero-dots{ display:none; } }
        .hero-inner {
            position:relative; z-index:2;
            display:flex; align-items:center; justify-content:space-between;
            gap:16px; flex-wrap:wrap;
        }
        .hero-greeting {
            font-family:var(--font); font-size:.65rem; font-weight:700;
            color:rgba(165,180,252,.7); letter-spacing:.14em; text-transform:uppercase;
            margin-bottom:6px; display:flex; align-items:center; gap:6px;
        }
        .hero-greeting-dot { width:5px; height:5px; border-radius:50%; background:rgba(165,180,252,.5); display:inline-block; }
        .hero-name {
            font-family:var(--font); font-size:1.9rem; font-weight:800;
            color:#fff; letter-spacing:-.05em; line-height:1.1;
            display:flex; align-items:center; gap:12px;
        }
        @media(max-width:479px){ .hero-name{ font-size:1.45rem; } }
        .hero-name-badge {
            display:inline-flex; align-items:center; justify-content:center;
            width:38px; height:38px;
            background:rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
            border-radius:12px; flex-shrink:0; backdrop-filter: blur(4px);
        }
        .hero-date { font-family:var(--font); font-size:.75rem; color:rgba(255,255,255,.45); margin-top:6px; letter-spacing:.02em; }

        /* ── FIXED: Monthly Quota Row ── */
        .hero-quota-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            flex-wrap: wrap;
        }
        .hero-quota-label {
            font-family: var(--font);
            font-size: .6rem;
            font-weight: 700;
            color: rgba(165,180,252,.6);
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .hero-quota-track-wrap {
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1;
            max-width: 110px;
        }
        .hero-quota-track {
            width: 100%;
            height: 5px;
            background: rgba(255,255,255,.12);
            border-radius: 999px;
            overflow: hidden;
        }
        .hero-quota-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #a5b4fc, #818cf8);
            transition: width .6s var(--ease-smooth) .4s;
        }
        .hero-quota-hint {
            font-family: var(--font);
            font-size: .54rem;
            color: rgba(165,180,252,.45);
            display: flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }
        .hero-quota-hint svg { flex-shrink: 0; }
        .hero-quota-text {
            font-family: var(--mono);
            font-size: .65rem;
            font-weight: 700;
            color: rgba(165,180,252,.8);
            white-space: nowrap;
        }

        .hero-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        @media(max-width:479px){ .hero-actions{ width:100%; } }
        .hero-btn {
            display:inline-flex; align-items:center; gap:7px; padding:11px 20px;
            background:rgba(255,255,255,.1); color:#fff; border-radius:14px;
            font-family:var(--font); font-size:.82rem; font-weight:700;
            border:1px solid rgba(255,255,255,.18); text-decoration:none;
            transition:all .2s; backdrop-filter: blur(4px); letter-spacing:-.01em;
        }
        @media(max-width:479px){ .hero-btn{ flex:1; justify-content:center; padding:10px 14px; font-size:.78rem; } }
        .hero-btn:hover { background:rgba(255,255,255,.18); transform:translateY(-1px); border-color:rgba(255,255,255,.28); }
        .hero-btn.solid { background:rgba(255,255,255,.95); color:#3730a3; border-color:transparent; box-shadow:0 4px 14px rgba(0,0,0,.2); }
        .hero-btn.solid:hover { background:#fff; box-shadow:0 6px 20px rgba(0,0,0,.25); }

        /* ══════════════════════════════════════════════════════
           STAT PILLS
        ══════════════════════════════════════════════════════ */
        .stat-pill-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 12px;
            margin: 0 20px 18px;
        }
        @media(max-width:700px){ .stat-pill-row{ grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; } }
        @media(max-width:599px){ .stat-pill-row{ margin:0 14px 14px; } }

        .stat-pill {
            background: var(--card); border: 1px solid var(--border); border-radius: 18px;
            padding: 18px 18px 16px; display: flex; flex-direction:column; gap:0;
            box-shadow: var(--shadow-sm); transition: transform .2s var(--ease-smooth), box-shadow .2s var(--ease-smooth);
            cursor: default; position: relative; overflow: hidden;
        }
        .stat-pill::before {
            content:''; position:absolute; top:0; left:0; right:0; height:2px;
            border-radius:18px 18px 0 0; opacity: 0; transition: opacity .2s;
        }
        .stat-pill:hover { transform:translateY(-3px); box-shadow:var(--glow-indigo-lg); }
        .stat-pill:hover::before { opacity:1; }
        .stat-pill-total::before    { background:linear-gradient(90deg,#6366f1,#818cf8); }
        .stat-pill-pending::before  { background:linear-gradient(90deg,#f59e0b,#fbbf24); }
        .stat-pill-approved::before { background:linear-gradient(90deg,#10b981,#34d399); }
        .stat-pill-extra::before    { background:linear-gradient(90deg,#ea580c,#f97316); }
        .stat-pill-claimed::before  { background:linear-gradient(90deg,#7c3aed,#a78bfa); }
        .stat-pill-declined::before { background:linear-gradient(90deg,#dc2626,#f87171); }
        @media(max-width:400px){ .stat-pill{ padding:14px 14px 12px; border-radius:14px; } }
        .sp-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
        .sp-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        @media(max-width:400px){ .sp-icon{ width:34px; height:34px; border-radius:10px; } }
        .sp-icon svg { width:18px; height:18px; flex-shrink:0; }
        .sp-val { font-family:var(--mono); font-size:2rem; font-weight:800; color:var(--text); line-height:1; letter-spacing:-.06em; margin-bottom:4px; }
        @media(max-width:400px){ .sp-val{ font-size:1.6rem; } }
        .sp-lbl { font-family:var(--font); font-size:.68rem; font-weight:600; color:var(--text-sub); letter-spacing:.02em; }
        .sp-hint { font-family:var(--font); font-size:.62rem; color:var(--text-faint); margin-top:1px; }

        /* ══════════════════════════════════════════════════════
           NEXT ACTION CARD
        ══════════════════════════════════════════════════════ */
        .next-card {
            display:flex; align-items:flex-start; gap:14px;
            border-radius:16px; padding:16px 18px; border:1px solid;
            margin: 0 20px 16px;
            animation: db-fade-up .4s var(--ease-smooth) .1s both;
            position:relative; overflow:hidden;
        }
        @media(max-width:599px){ .next-card{ margin:0 14px 14px; padding:14px 14px; border-radius:14px; gap:11px; } }
        .next-icon-wrap { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .next-eyebrow { font-family:var(--font); font-size:.58rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; margin-bottom:4px; }
        .next-msg { font-family:var(--font); font-size:.82rem; color:var(--text-muted); line-height:1.6; }
        .next-cta {
            display:inline-flex; align-items:center; gap:6px; margin-top:11px;
            padding:9px 16px; border-radius:10px;
            font-family:var(--font); font-size:.74rem; font-weight:700; color:#fff;
            text-decoration:none; transition:all .2s; box-shadow: 0 3px 10px rgba(0,0,0,.15);
        }
        .next-cta:hover { transform:translateY(-1px); opacity:.92; }

        /* ══════════════════════════════════════════════════════
           TIMER BANNER
        ══════════════════════════════════════════════════════ */
        .timer-banner {
            display:none; border-radius:16px; padding:14px 18px;
            margin: 0 20px 16px; border:1px solid; animation:db-fade-up .35s var(--ease-spring) both;
        }
        @media(max-width:599px){ .timer-banner{ margin:0 14px 14px; } }
        .timer-banner.urgent  { background:rgba(239,68,68,.06); border-color:rgba(239,68,68,.25); color:#991b1b; }
        .timer-banner.warning { background:rgba(245,158,11,.06); border-color:rgba(245,158,11,.25); color:#92400e; }
        .timer-banner.safe    { background:rgba(99,102,241,.06); border-color:rgba(99,102,241,.2); color:#3730a3; }
        body.dark .timer-banner.safe    { background:rgba(55,48,163,.12); border-color:rgba(55,48,163,.3); color:#a5b4fc; }
        body.dark .timer-banner.warning { background:rgba(180,83,9,.15); border-color:rgba(180,83,9,.3); color:#fcd34d; }
        body.dark .timer-banner.urgent  { background:rgba(154,52,18,.15); border-color:rgba(154,52,18,.3); color:#fb923c; }
        .timer-inner { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        .timer-text-col { flex:1; min-width:130px; }
        .timer-text-col p { font-family:var(--font); }
        .timer-digit {
            display:inline-flex; flex-direction:column; align-items:center;
            background:rgba(0,0,0,.06); border-radius:9px; padding:.2rem .55rem;
            min-width:2.8rem; font-weight:700; font-size:1.15rem;
            line-height:1; font-family:var(--mono); letter-spacing:-.02em;
        }
        .timer-digit span { font-family:var(--font); font-size:.48rem; font-weight:600; opacity:.55; text-transform:uppercase; letter-spacing:.08em; margin-top:3px; }
        .timer-sep { font-family:var(--font); font-weight:700; font-size:14px; opacity:.35; animation:db-pulse .9s ease-in-out infinite; }
        .timer-progress-wrap { height:3px; border-radius:999px; background:rgba(0,0,0,.07); overflow:hidden; margin-top:10px; }
        .timer-progress-fill { height:100%; border-radius:999px; background:currentColor; opacity:.35; transition:width 1s linear; }

        /* ══════════════════════════════════════════════════════
           UPCOMING PILL
        ══════════════════════════════════════════════════════ */
        .upcoming-pill {
            background:rgba(99,102,241,.06); border:1px solid rgba(99,102,241,.18);
            border-radius:16px; padding:14px 16px; margin: 0 20px 16px;
            display:flex; align-items:center; gap:14px; flex-wrap:wrap;
            animation: db-fade-up .4s var(--ease-smooth) .18s both;
            box-shadow: var(--glow-indigo);
        }
        @media(max-width:599px){ .upcoming-pill{ margin:0 14px 14px; } }
        .up-icon {
            width:40px; height:40px; background:linear-gradient(135deg, var(--indigo-600), var(--indigo-800));
            border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
            box-shadow:0 4px 12px rgba(67,56,202,.3);
        }
        .up-icon svg { width:16px; height:16px; flex-shrink:0; }
        .up-eyebrow { font-family:var(--font); font-size:.56rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--indigo-500); margin-bottom:3px; }
        .up-name { font-family:var(--font); font-size:.88rem; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px; }
        .up-time { font-family:var(--mono); font-size:.68rem; color:var(--indigo-600); margin-top:2px; font-weight:500; }
        .up-btn {
            margin-left:auto; font-family:var(--font); font-size:.72rem; font-weight:700;
            color:var(--indigo-700); background:var(--card);
            border:1px solid rgba(99,102,241,.25); border-radius:10px; padding:8px 14px;
            text-decoration:none; white-space:nowrap; transition:all .2s;
        }
        .up-btn:hover { background:var(--indigo-600); color:white; border-color:transparent; }
        @media(max-width:479px){ .up-name{max-width:100%;} .up-btn{margin-left:0;width:100%;text-align:center;display:block;} }

        /* ══════════════════════════════════════════════════════
           DASHBOARD MAIN GRID
        ══════════════════════════════════════════════════════ */
        .dash-grid {
            display: grid;
            grid-template-columns: minmax(0,1.55fr) minmax(0,1fr);
            gap: 16px;
            margin: 0 20px 18px;
        }
        @media(max-width:860px){ .dash-grid{ grid-template-columns:1fr; } }
        @media(max-width:599px){ .dash-grid{ margin:0 14px 14px; gap:12px; } }
        .side-col { display:flex; flex-direction:column; gap:14px; }
        @media(max-width:860px){ .side-col{ display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media(max-width:560px){ .side-col{ grid-template-columns:1fr; } }

        /* ── Shared Card Helpers ── */
        .db-card { background:var(--card); border:1px solid var(--border); border-radius:20px; box-shadow:var(--shadow-sm); transition:box-shadow .2s; }
        .db-card:hover { box-shadow:var(--shadow-md); }
        .db-card-p { padding:20px 20px 16px; }
        @media(max-width:479px){ .db-card-p{ padding:16px 14px 14px; } }
        .card-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px; }
        .card-icon { width:36px; height:36px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .card-icon svg { width:16px; height:16px; flex-shrink:0; }
        .card-title { font-family:var(--font); font-size:.9rem; font-weight:700; color:var(--text); letter-spacing:-.02em; }
        .card-sub   { font-family:var(--font); font-size:.7rem; color:var(--text-sub); margin-top:2px; }
        .section-lbl {
            font-family:var(--font); font-size:.6rem; font-weight:700;
            letter-spacing:.2em; text-transform:uppercase; color:var(--text-sub);
            margin-bottom:12px; display:flex; align-items:center; gap:7px;
        }
        .section-lbl::before { content:''; width:3px; height:13px; border-radius:2px; background:linear-gradient(180deg, var(--indigo-500), var(--indigo-700)); flex-shrink:0; }
        .link-sm {
            font-family:var(--font); font-size:.64rem; font-weight:700;
            color:var(--indigo); text-decoration:none; letter-spacing:.06em;
            text-transform:uppercase; transition:opacity .15s; white-space:nowrap;
        }
        .link-sm:hover { opacity:.65; }

        /* ── Quick Actions ── */
        .qa-link {
            display:flex; align-items:center; gap:11px; padding:11px 13px;
            border-radius:13px; border:1px solid var(--border); background:var(--card);
            text-decoration:none; color:var(--text-muted);
            font-family:var(--font); font-size:.82rem; font-weight:600;
            transition:all .2s var(--ease-smooth);
        }
        .qa-link:hover { border-color:rgba(99,102,241,.3); background:rgba(99,102,241,.04); color:var(--indigo-700); transform:translateX(2px); box-shadow:var(--glow-indigo); }
        .qa-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .qa-icon svg { width:15px; height:15px; flex-shrink:0; }
        .qa-chev { margin-left:auto; color:var(--text-faint); flex-shrink:0; transition:transform .2s; }
        .qa-chev svg { width:12px; height:12px; flex-shrink:0; }
        .qa-link:hover .qa-chev { transform:translateX(2px); color:var(--indigo); }

        /* ── Recent Bookings ── */
        .bk-row {
            display:flex; align-items:center; gap:11px; padding:9px 10px;
            border-radius:12px; text-decoration:none; color:inherit;
            transition:background .15s; cursor:pointer;
        }
        .bk-row:hover { background:rgba(99,102,241,.05); }
        .bk-date {
            width:40px; height:40px; background:var(--input-bg); border-radius:11px;
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            flex-shrink:0; border:1px solid var(--border-subtle);
        }
        .bk-month { font-family:var(--font); font-size:.5rem; font-weight:700; text-transform:uppercase; color:var(--text-sub); }
        .bk-day   { font-family:var(--mono); font-size:.95rem; font-weight:800; color:var(--text); line-height:1; }
        .bk-name  { font-family:var(--font); font-size:.82rem; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .bk-time  { font-family:var(--mono); font-size:.64rem; color:var(--text-sub); margin-top:1px; }

        /* ── Calendar Card ── */
        .cal-card { padding:20px 20px 16px; }
        @media(max-width:479px){ .cal-card{ padding:14px 12px 12px; } }
        .cal-legend { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .leg-item { display:flex; align-items:center; gap:5px; }
        .leg-dot  { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .leg-lbl  { font-family:var(--font); font-size:.66rem; font-weight:600; color:var(--text-sub); }
        @media(max-width:479px){ .cal-legend{gap:6px;} .leg-lbl{display:none;} .leg-dot{width:8px;height:8px;} }

        /* ── FullCalendar overrides ── */
        #calendar { font-family:var(--font) !important; font-size:.8rem; }
        .fc .fc-toolbar { flex-wrap:wrap; gap:.5rem; }
        .fc-toolbar-title { font-family:var(--font) !important; font-size:.92rem !important; font-weight:800 !important; color:var(--text) !important; letter-spacing:-.03em !important; }
        .fc-button-primary { background:var(--indigo) !important; border-color:var(--indigo) !important; border-radius:10px !important; font-family:var(--font) !important; font-weight:700 !important; font-size:.7rem !important; padding:.28rem .6rem !important; box-shadow:none !important; }
        .fc-button-primary:hover { background:var(--indigo-800) !important; }
        .fc-button-primary:not(:disabled):active,
        .fc-button-primary:not(:disabled).fc-button-active { background:var(--indigo-900) !important; }
        .fc-daygrid-event { border-radius:5px !important; font-family:var(--font) !important; font-size:.62rem !important; font-weight:600 !important; padding:2px 5px !important; border:none !important; cursor:pointer !important; }
        .fc-daygrid-day:hover { background-color:rgba(99,102,241,.05) !important; cursor:pointer; }
        .fc-day-today { background:rgba(99,102,241,.06) !important; }
        .fc-day-today .fc-daygrid-day-number { color:var(--indigo) !important; font-weight:800 !important; }
        .fc-daygrid-day-number { font-family:var(--font); font-size:.7rem; font-weight:600; }
        .fc-col-header-cell-cushion { font-family:var(--font); font-size:.7rem; font-weight:700; }
        body.dark .fc-toolbar-title { color:var(--text) !important; }
        body.dark .fc-daygrid-day-number { color:#7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color:#7fb3e8; }
        body.dark .fc-day-today { background:rgba(55,48,163,.15) !important; }
        body.dark .fc-daygrid-day { background:var(--card) !important; }
        body.dark .fc-theme-standard td,
        body.dark .fc-theme-standard th,
        body.dark .fc-theme-standard .fc-scrollgrid { border-color:#101e35 !important; }
        @media(max-width:479px){
            .fc .fc-toolbar{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:6px;}
            .fc-toolbar-chunk:nth-child(2){text-align:center;}
            .fc-toolbar-title{font-size:.78rem !important;}
            .fc-button-primary{font-size:.62rem !important;padding:.22rem .45rem !important;}
            #calendar{font-size:.68rem;}
            .fc .fc-daygrid-body{min-height:auto !important;}
            #calendar .fc-daygrid-body,
            #calendar .fc-scrollgrid-sync-table{width:100% !important;}
            .fc .fc-daygrid-day-frame{min-height:30px !important;}
        }

        /* ══════════════════════════════════════════════════════
           GUIDE GRID
        ══════════════════════════════════════════════════════ */
        .guide-grid { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:16px; margin:0 20px 18px; }
        @media(max-width:700px){ .guide-grid{ grid-template-columns:1fr; } }
        @media(max-width:599px){ .guide-grid{ margin:0 14px 14px; gap:12px; } }
        .how-step { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px solid var(--border-subtle); }
        .how-step:last-child { border-bottom:none; }
        .step-num {
            width:26px; height:26px; border-radius:50%;
            background:linear-gradient(135deg, var(--indigo-600), var(--indigo-800));
            color:white; font-family:var(--font); font-size:.68rem; font-weight:800;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
            margin-top:1px; box-shadow:0 2px 8px rgba(67,56,202,.25);
        }
        .how-step p { font-family:var(--font); }
        .status-guide-row { display:flex; align-items:center; gap:10px; padding:7px 0; border-bottom:1px solid var(--border-subtle); }
        .status-guide-row:last-child { border-bottom:none; }
        .status-guide-row p { font-family:var(--font); font-size:.72rem; color:var(--text-muted); }
        .date-row { display:flex; align-items:center; gap:11px; padding:.75rem; border-bottom:1px solid var(--border-subtle); border-radius:10px; transition:background .15s; }
        .date-row:hover { background:var(--input-bg); }
        .date-row:last-child { border-bottom:none; }

        /* ══════════════════════════════════════════════════════
           LIBRARY SECTION — FIXED (no brown/amber color)
        ══════════════════════════════════════════════════════ */
        .lib-section {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 0 20px 24px;
        }
        @media(max-width:599px){ .lib-section{ margin: 0 14px 20px; gap: 10px; } }

        /* ── Library Header Band — clean card, no color ── */
        .lib-header-band {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            flex-wrap: wrap;
        }
        @media(max-width:479px){ .lib-header-band{ padding: 14px 16px; gap: 10px; } }

        .lib-header-left { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; }
        .lib-header-icon {
            width: 44px; height: 44px;
            background: #eef2ff;
            border: 1px solid rgba(99,102,241,.15);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        body.dark .lib-header-icon { background: rgba(99,102,241,.12); border-color: rgba(99,102,241,.2); }
        .lib-header-eyebrow {
            font-family: var(--font); font-size: .55rem; font-weight: 700;
            letter-spacing: .2em; text-transform: uppercase;
            color: var(--text-sub); margin-bottom: 3px;
        }
        .lib-header-count {
            font-family: var(--mono); font-size: 1.5rem; font-weight: 900;
            color: var(--text); line-height: 1; letter-spacing: -.05em;
        }
        .lib-header-count-faint { font-size: 1rem; font-weight: 600; color: var(--text-faint); }
        .lib-header-sub { font-family: var(--font); font-size: .65rem; color: var(--text-faint); margin-top: 2px; }
        .lib-header-chips { display: flex; gap: 8px; position: relative; z-index: 1; flex-shrink: 0; }
        @media(max-width:479px){ .lib-header-chips{ display: none; } }
        .lib-header-chip {
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px; padding: 7px 12px;
            text-align: center;
        }
        .lib-header-chip-val {
            font-family: var(--mono); font-size: .9rem; font-weight: 800;
            color: var(--text); display: block;
        }
        .lib-header-chip-lbl {
            font-family: var(--font); font-size: .48rem; font-weight: 700;
            color: var(--text-sub); text-transform: uppercase;
            letter-spacing: .08em; display: block; margin-top: 1px;
        }
        .lib-browse-btn-inline {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 16px;
            background: rgba(99,102,241,.08);
            border: 1px solid rgba(99,102,241,.2);
            border-radius: 11px;
            color: var(--indigo-700);
            font-family: var(--font); font-size: .74rem; font-weight: 700;
            text-decoration: none; transition: all .2s; position: relative; z-index: 1;
            white-space: nowrap; flex-shrink: 0;
        }
        body.dark .lib-browse-btn-inline { color: #a5b4fc; background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.25); }
        .lib-browse-btn-inline:hover {
            background: var(--indigo-600);
            color: white;
            border-color: transparent;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67,56,202,.25);
        }
        @media(max-width:479px){ .lib-browse-btn-inline{ padding: 8px 12px; font-size: .68rem; } }

        /* ── Two-column card grid ── */
        .lib-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        @media(max-width:599px){ .lib-card-grid{ grid-template-columns: 1fr; gap: 10px; } }

        /* ── Shared lib card ── */
        .lib-card { background: var(--card); border: 1px solid var(--border); border-radius: 18px; overflow: hidden; box-shadow: var(--shadow-sm); }
        .lib-card-header {
            padding: 14px 16px 12px; border-bottom: 1px solid var(--border-subtle);
            display: flex; align-items: center; justify-content: space-between;
        }
        .lib-card-header-left { display: flex; align-items: center; gap: 9px; }
        .lib-card-icon {
            width: 34px; height: 34px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .lib-card-icon svg { width: 15px; height: 15px; flex-shrink: 0; }
        .lib-card-icon-indigo { background: linear-gradient(135deg, #4f46e5, #3730a3); box-shadow: 0 3px 8px rgba(79,70,229,.22); }
        .lib-card-icon-green  { background: linear-gradient(135deg, #16a34a, #065f46); box-shadow: 0 3px 8px rgba(22,163,74,.2); }

        /* Available Books */
        .book-list  { padding: 4px 8px 8px; }
        .book-row {
            display: flex; align-items: center; gap: 10px; padding: 8px 8px;
            border-radius: 11px; text-decoration: none; color: inherit; transition: background .15s;
        }
        .book-row:hover { background: rgba(99,102,241,.05); }
        .book-cover {
            width: 32px; height: 44px; border-radius: 4px 5px 5px 4px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            font-family: var(--font); font-weight: 800; font-size: .78rem; color: #fff;
            box-shadow: 2px 3px 8px rgba(0,0,0,.22), inset -2px 0 0 rgba(0,0,0,.18);
            position: relative; overflow: hidden;
        }
        .book-cover::after { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; background:rgba(0,0,0,.2); border-radius:3px 0 0 3px; }
        .book-row:nth-child(6n+1) .book-cover { background: linear-gradient(160deg,#6366f1,#4338ca); }
        .book-row:nth-child(6n+2) .book-cover { background: linear-gradient(160deg,#ec4899,#be185d); }
        .book-row:nth-child(6n+3) .book-cover { background: linear-gradient(160deg,#10b981,#065f46); }
        .book-row:nth-child(6n+4) .book-cover { background: linear-gradient(160deg,#f97316,#c2410c); }
        .book-row:nth-child(6n+5) .book-cover { background: linear-gradient(160deg,#3b82f6,#1d4ed8); }
        .book-row:nth-child(6n+6) .book-cover { background: linear-gradient(160deg,#8b5cf6,#6d28d9); }
        .book-title  { font-family: var(--font); font-size: .8rem; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -.01em; }
        .book-author { font-family: var(--font); font-size: .64rem; color: var(--text-sub); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .avail-pill  { font-family: var(--font); font-size: .56rem; font-weight: 700; padding: 3px 8px; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }

        /* Active Borrows */
        .borrow-list { padding: 8px; display: flex; flex-direction: column; gap: 6px; }
        .borrow-row {
            display: flex; align-items: center; gap: 9px; padding: 9px 10px;
            background: var(--input-bg); border: 1px solid var(--border-subtle);
            border-radius: 11px; transition: border-color .2s;
        }
        .borrow-row:hover { border-color: rgba(22,163,74,.28); }
        .borrow-cover {
            width: 26px; height: 36px; border-radius: 3px 4px 4px 3px;
            background: linear-gradient(135deg, #a5b4fc, #818cf8);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            font-family: var(--font); font-weight: 800; font-size: .64rem; color: #fff;
            box-shadow: 1px 2px 5px rgba(0,0,0,.18), inset -2px 0 0 rgba(0,0,0,.15);
            position: relative;
        }
        .borrow-cover::after { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; background:rgba(0,0,0,.18); border-radius:2px 0 0 2px; }

        /* ══════════════════════════════════════════════════════
           LOGIN TOAST
        ══════════════════════════════════════════════════════ */
        .login-toast {
            position:fixed; bottom:calc(var(--mob-nav-total,0px) + 12px); right:16px; z-index:400;
            max-width:288px;
            background:linear-gradient(135deg,#0f172a,#1e293b);
            border:1px solid rgba(255,255,255,.08); border-radius:16px; padding:13px 14px;
            display:flex; align-items:flex-start; gap:10px;
            box-shadow:0 16px 48px rgba(0,0,0,.35), 0 0 0 1px rgba(99,102,241,.1);
            transform:translateY(12px); opacity:0; pointer-events:none;
            transition:all .4s var(--ease-spring);
        }
        .login-toast.show { transform:none; opacity:1; pointer-events:auto; }
        .toast-icon { width:28px; height:28px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .toast-close { background:rgba(255,255,255,.07); border:none; border-radius:7px; width:24px; height:24px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; margin-top:1px; transition:background .15s; }
        .toast-close:hover { background:rgba(255,255,255,.14); }
        #toastTitle { font-family:var(--font); font-weight:700; font-size:12px; line-height:1.3; color:white; }
        #toastBody  { font-family:var(--font); font-size:10px; color:rgba(255,255,255,.55); margin-top:2px; }
        @media(min-width:1024px){ .login-toast{ bottom:24px; } }
        @media(max-width:479px){ .login-toast{ bottom:calc(var(--mob-nav-total,0px) + 8px); left:12px; right:12px; max-width:none; } }

        /* ══════════════════════════════════════════════════════
           SECTION DIVIDER
        ══════════════════════════════════════════════════════ */
        .section-divider { display:flex; align-items:center; gap:12px; margin:0 20px 16px; padding:0 4px; }
        @media(max-width:599px){ .section-divider{ margin:0 14px 14px; } }
        .section-divider-line { flex:1; height:1px; background:var(--border-subtle); }
        .section-divider-label { font-family:var(--font); font-size:.6rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--text-faint); white-space:nowrap; }

        /* ── Responsive padding at bottom ── */
        .main-area > *:last-child { padding-bottom: 32px; }
        @media(max-width:599px){ .main-area > *:last-child{ padding-bottom:20px; } }
    </style>
</head>

<body>
    <?php $page = 'dashboard'; include(APPPATH . 'Views/partials/layout.php'); ?>

    <main class="main-area">

        <!-- Topbar -->
        <div class="db-topbar fade-up">
            <?= layout_dark_toggle() ?>
            <a href="<?= base_url('/reservation') ?>" class="reserve-btn">
                <?= icon('plus', 15, 'white') ?> Reserve
            </a>
            <div class="notif-bell" onclick="toggleNotifications()">
                <div class="l-icon-btn"><?= icon('bell', 16, 'currentColor') ?></div>
                <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
            </div>
        </div>

        <!-- Notification Dropdown -->
        <div id="notifDD" class="notif-dd">
            <div style="padding:12px 14px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
                <span style="font-family:var(--font);font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
                <button onclick="markAllRead()" style="font-family:var(--font);font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
            </div>
            <div id="notifList" style="max-height:290px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok">
                <?= icon('check-circle', 14, '#10b981') ?>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- ══ HERO BANNER ══ -->
        <div class="hero-banner fade-up">
            <div class="hero-streak"></div>
            <div class="hero-dots"></div>
            <div class="hero-inner">
                <div>
                    <div class="hero-greeting">
                        <span class="hero-greeting-dot"></span>
                        <?php $h=(int)date('H'); echo $h<12?'Good morning':($h<17?'Good afternoon':'Good evening'); ?>
                    </div>
                    <div class="hero-name">
                        <?= esc($user_name) ?>
                        <span class="hero-name-badge"><?= icon('hand-wave', 20, 'rgba(255,255,255,.8)') ?></span>
                    </div>
                    <div class="hero-date"><?= date('l, F j, Y') ?></div>

                    <!-- FIXED: Monthly Quota Row -->
                    <div class="hero-quota-row">
                        <span class="hero-quota-label">Monthly Quota</span>
                        <div class="hero-quota-track-wrap">
                            <div class="hero-quota-track">
                                <div class="hero-quota-fill" style="width:<?= min(100, ($usedSlots / $maxSlots) * 100) ?>%;"></div>
                            </div>
                            <?php if ($remaining > 0): ?>
                                <span class="hero-quota-hint">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:9px;height:9px;flex-shrink:0;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                    Only <?= $remaining ?> slot<?= $remaining > 1 ? 's' : '' ?> remaining
                                </span>
                            <?php elseif ($remaining === 0): ?>
                                <span class="hero-quota-hint" style="color:rgba(248,113,113,.6);">
                                    All slots used this month
                                </span>
                            <?php endif; ?>
                        </div>
                        <span class="hero-quota-text"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="<?= base_url('/reservation') ?>" class="hero-btn solid">
                        <?= icon('plus', 15, '#3730a3') ?> Reserve
                    </a>
                    <a href="<?= base_url('/reservation-list') ?>" class="hero-btn">
                        <?= icon('calendar', 15, 'white') ?> Bookings
                    </a>
                </div>
            </div>
        </div>

        <!-- ══ STAT PILLS ══ -->
        <div class="stat-pill-row fade-up-1">
            <div class="stat-pill stat-pill-total">
                <div class="sp-top">
                    <div class="sp-icon" style="background:#eef2ff;"><?= icon('layers', 18, '#4338ca') ?></div>
                </div>
                <div class="sp-val"><?= $total ?></div>
                <div class="sp-lbl">Total</div>
                <div class="sp-hint">All time</div>
            </div>
            <div class="stat-pill stat-pill-pending">
                <div class="sp-top">
                    <div class="sp-icon" style="background:#fef3c7;"><?= icon('clock', 18, '#d97706') ?></div>
                </div>
                <div class="sp-val" style="color:#d97706;"><?= $pending ?></div>
                <div class="sp-lbl">Pending</div>
                <div class="sp-hint">Awaiting review</div>
            </div>
            <div class="stat-pill stat-pill-approved">
                <div class="sp-top">
                    <div class="sp-icon" style="background:#dcfce7;"><?= icon('check-circle', 18, '#16a34a') ?></div>
                </div>
                <div class="sp-val" style="color:#16a34a;"><?= $approved ?></div>
                <div class="sp-lbl">Approved</div>
                <div class="sp-hint">Ready to use</div>
            </div>
            <?php if ($unclaimedCount > 0): ?>
                <div class="stat-pill stat-pill-extra">
                    <div class="sp-top">
                        <div class="sp-icon" style="background:#fff7ed;"><?= icon('triangle', 18, '#ea580c') ?></div>
                    </div>
                    <div class="sp-val" style="color:#ea580c;"><?= $unclaimedCount ?></div>
                    <div class="sp-lbl">No-show</div>
                    <div class="sp-hint">Missed slot<?= $unclaimedCount>1?'s':'' ?></div>
                </div>
            <?php elseif ($claimedCount > 0): ?>
                <div class="stat-pill stat-pill-claimed">
                    <div class="sp-top">
                        <div class="sp-icon" style="background:#ede9fe;"><?= icon('check-double', 18, '#7c3aed') ?></div>
                    </div>
                    <div class="sp-val" style="color:#7c3aed;"><?= $claimedCount ?></div>
                    <div class="sp-lbl">Claimed</div>
                    <div class="sp-hint">Tickets used</div>
                </div>
            <?php else: ?>
                <div class="stat-pill stat-pill-declined">
                    <div class="sp-top">
                        <div class="sp-icon" style="background:#fee2e2;"><?= icon('ban', 18, '#dc2626') ?></div>
                    </div>
                    <div class="sp-val" style="color:#dc2626;"><?= $declined ?></div>
                    <div class="sp-lbl">Declined</div>
                    <div class="sp-hint">Not approved</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- ══ NEXT ACTION ══ -->
        <?php if ($nextAction): $nc = $nextColors[$nextAction['color']]; ?>
            <div class="next-card fade-up-1" style="background:<?= $nc['bg'] ?>;border-color:<?= $nc['border'] ?>;">
                <div style="position:absolute;top:0;left:0;bottom:0;width:3px;border-radius:3px 0 0 3px;background:<?= $nc['btn_bg'] ?>;"></div>
                <div class="next-icon-wrap" style="background:<?= $nc['icon_bg'] ?>;">
                    <?= icon($nc['icon'], 15, $nc['icon_fg']) ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="next-eyebrow" style="color:<?= $nc['icon_fg'] ?>;">What to do next</div>
                    <div class="next-msg"><?= $nextAction['msg'] ?></div>
                    <a href="<?= base_url($nextAction['url']) ?>" class="next-cta" style="background:<?= $nc['btn_bg'] ?>;">
                        <?= $nextAction['cta'] ?> <?= icon('arrow-right', 12, 'white') ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- ══ TIMER BANNER ══ -->
        <div id="timerBanner" class="timer-banner">
            <div class="timer-inner">
                <div id="timerIconWrap" style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.06);flex-shrink:0;">
                    <?= icon('hourglass', 16, 'currentColor') ?>
                </div>
                <div class="timer-text-col">
                    <p style="font-weight:700;font-size:.88rem;line-height:1.3;" id="timerTitle">Your reservation ends soon</p>
                    <p style="font-size:.74rem;opacity:.65;margin-top:2px;" id="timerSub"></p>
                </div>
                <div style="display:flex;align-items:center;gap:4px;flex-shrink:0;">
                    <div class="timer-digit"><span id="tdH">00</span><span>hrs</span></div>
                    <span class="timer-sep">:</span>
                    <div class="timer-digit"><span id="tdM">00</span><span>min</span></div>
                    <span class="timer-sep">:</span>
                    <div class="timer-digit"><span id="tdS">00</span><span>sec</span></div>
                </div>
            </div>
            <div id="timerPW" class="timer-progress-wrap" style="display:none;">
                <div id="timerPF" class="timer-progress-fill" style="width:0%;"></div>
            </div>
        </div>

        <!-- ══ UPCOMING PILL ══ -->
        <?php if ($upcoming): ?>
            <div class="upcoming-pill fade-up-2">
                <div class="up-icon"><?= icon('ticket', 16, 'white') ?></div>
                <div style="flex:1;min-width:0;">
                    <div class="up-eyebrow">Upcoming Reservation</div>
                    <div class="up-name"><?= esc($upcoming['resource_name'] ?? 'Resource') ?><?php if (!empty($upcoming['pc_number'])): ?> &middot; <span style="font-weight:400;"><?= esc($upcoming['pc_number']) ?></span><?php endif; ?></div>
                    <div class="up-time"><?= date('M j, Y', strtotime($upcoming['reservation_date'])) ?> &nbsp;·&nbsp; <?= date('g:i A', strtotime($upcoming['start_time'])) ?> – <?= date('g:i A', strtotime($upcoming['end_time'])) ?></div>
                </div>
                <a href="<?= base_url('/reservation-list') ?>" class="up-btn">View →</a>
            </div>
        <?php endif; ?>

        <!-- ══ MAIN DASHBOARD GRID ══ -->
        <div class="dash-grid fade-up-2">

            <!-- Calendar Card -->
            <div class="db-card cal-card">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#eef2ff;"><?= icon('calendar-days', 15, 'var(--indigo)') ?></div>
                        <div>
                            <div class="card-title">Community Schedule</div>
                            <div class="card-sub">Tap any date to see reservations</div>
                        </div>
                    </div>
                    <div class="cal-legend">
                        <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                            <div class="leg-item">
                                <div class="leg-dot" style="background:<?= $c ?>;"></div>
                                <span class="leg-lbl"><?= $l ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Side Column -->
            <div class="side-col">
                <!-- Quick Actions -->
                <div class="db-card db-card-p">
                    <div class="section-lbl">Quick Actions</div>
                    <div style="display:flex;flex-direction:column;gap:7px;">
                        <a href="<?= base_url('/reservation') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#eef2ff;"><?= icon('plus', 14, 'var(--indigo)') ?></div>
                            New Reservation
                            <span class="qa-chev"><?= icon('chevron-right', 12, 'currentColor') ?></span>
                        </a>
                        <a href="<?= base_url('/reservation-list') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#ede9fe;"><?= icon('calendar', 14, '#7c3aed') ?></div>
                            My Reservations
                            <?php if ($pending > 0): ?>
                                <span style="margin-left:auto;background:#fef3c7;color:#92400e;font-family:var(--font);font-size:9px;font-weight:700;padding:2px 8px;border-radius:999px;"><?= $pending ?></span>
                            <?php else: ?>
                                <span class="qa-chev"><?= icon('chevron-right', 12, 'currentColor') ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= base_url('/books') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#eef2ff;"><?= icon('book-open', 14, 'var(--indigo)') ?></div>
                            Browse Library
                            <span class="qa-chev"><?= icon('chevron-right', 12, 'currentColor') ?></span>
                        </a>
                        <a href="<?= base_url('/profile') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#f3e8ff;"><?= icon('user', 14, '#9333ea') ?></div>
                            View Profile
                            <span class="qa-chev"><?= icon('chevron-right', 12, 'currentColor') ?></span>
                        </a>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="db-card db-card-p" style="flex:1;">
                    <div class="card-head">
                        <div class="section-lbl" style="margin-bottom:0;">Recent Bookings</div>
                        <a href="<?= base_url('/reservation-list') ?>" class="link-sm">View all →</a>
                    </div>
                    <?php if (!empty($processedRecent)): ?>
                        <div>
                            <?php foreach (array_slice($processedRecent, 0, 5) as $res):
                                $s  = $res['_status'];
                                $dt = new DateTime($res['reservation_date']);
                            ?>
                                <a href="<?= base_url('/reservation-list') ?>" class="bk-row">
                                    <div class="bk-date">
                                        <div class="bk-month"><?= $dt->format('M') ?></div>
                                        <div class="bk-day"><?= $dt->format('j') ?></div>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="bk-name"><?= esc($res['resource_name'] ?? 'Resource #' . $res['resource_id']) ?></div>
                                        <div class="bk-time"><?= date('g:i A', strtotime($res['start_time'])) ?> – <?= date('g:i A', strtotime($res['end_time'])) ?></div>
                                    </div>
                                    <span class="tag tag-<?= $s ?>"><?= $s === 'unclaimed' ? 'No-show' : ucfirst($s) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:26px 12px;">
                            <div style="display:flex;justify-content:center;margin-bottom:10px;color:var(--text-faint);"><?= icon('calendar-x', 28, 'currentColor') ?></div>
                            <p style="font-family:var(--font);font-size:.78rem;font-weight:600;color:var(--text-sub);">No bookings yet</p>
                            <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:10px;font-family:var(--font);font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;">
                                <?= icon('plus', 12, 'var(--indigo)') ?> Make your first reservation
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ══ GUIDE GRID ══ -->
        <?php if (empty($reservations) || $unclaimedCount > 0 || $pending > 0): ?>
            <div class="section-divider fade-up-3">
                <div class="section-divider-line"></div>
                <span class="section-divider-label">How It Works</span>
                <div class="section-divider-line"></div>
            </div>
            <div class="guide-grid fade-up-3">
                <div class="db-card db-card-p">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                        <div class="card-icon" style="background:#eef2ff;"><?= icon('list-check', 15, 'var(--indigo)') ?></div>
                        <div>
                            <div class="card-title">How to Reserve</div>
                            <div class="card-sub">Step-by-step guide</div>
                        </div>
                    </div>
                    <?php $step=1;
                    foreach ([
                        ['Click "New Reservation"','Choose a resource, pick your date and time, and describe your purpose.'],
                        ['Wait for approval','An SK officer will review your request, usually within 24 hours.'],
                        ['Download your e-ticket','Once approved, open My Reservations and download your QR code.'],
                        ['Scan at the entrance','Show your e-ticket to be scanned when you arrive.'],
                        ['Be on time',"Slots expire if you don't show up. Cancel in advance if plans change."],
                    ] as [$title,$body]): ?>
                        <div class="how-step">
                            <div class="step-num"><?= $step++ ?></div>
                            <div>
                                <p style="font-weight:700;font-size:.8rem;color:var(--text);letter-spacing:-.01em;"><?= $title ?></p>
                                <p style="font-size:.7rem;color:var(--text-sub);margin-top:2px;line-height:1.5;"><?= $body ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="db-card db-card-p">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                        <div class="card-icon" style="background:#eef2ff;"><?= icon('info', 15, 'var(--indigo)') ?></div>
                        <div>
                            <div class="card-title">Status Reference</div>
                            <div class="card-sub">What each status means</div>
                        </div>
                    </div>
                    <?php foreach ([
                        ['pending',  'clock',       '#fef3c7','#92400e','#d97706','Pending', 'Waiting for SK officer review.'],
                        ['approved', 'check-circle','#dcfce7','#166534','#16a34a','Approved','Confirmed. Get your e-ticket.'],
                        ['claimed',  'check-double','#ede9fe','#5b21b6','#7c3aed','Claimed', 'E-ticket scanned. Slot used.'],
                        ['unclaimed','ticket',      '#fff7ed','#c2410c','#ea580c','No-show', "Approved but you didn't attend."],
                        ['declined', 'ban',         '#fee2e2','#991b1b','#dc2626','Declined','Not approved. Try another time.'],
                        ['expired',  'hourglass',   '#f1f5f9','#475569','#64748b','Expired', 'Date passed before approval.'],
                    ] as [$key,$ico,$bg,$fg,$ic,$label,$desc]): ?>
                        <div class="status-guide-row">
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:8px;font-family:var(--font);font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;flex-shrink:0;min-width:74px;justify-content:center;background:<?= $bg ?>;color:<?= $fg ?>;">
                                <?= icon($ico, 8, $ic) ?><?= $label ?>
                            </span>
                            <p><?= $desc ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ══ LIBRARY SECTION ══ -->
        <div class="section-divider fade-up-4">
            <div class="section-divider-line"></div>
            <span class="section-divider-label">Community Library</span>
            <div class="section-divider-line"></div>
        </div>

        <div class="lib-section fade-up-4">

            <!-- Clean header band (no color) -->
            <div class="lib-header-band">
                <div class="lib-header-left">
                    <div class="lib-header-icon">
                        <?= icon('book-open', 22, 'var(--indigo-600)') ?>
                    </div>
                    <div>
                        <div class="lib-header-eyebrow">Collection</div>
                        <div class="lib-header-count">
                            <?= $availableCount ?>
                            <span class="lib-header-count-faint">/ <?= $totalBooks ?></span>
                        </div>
                        <div class="lib-header-sub">books available · <?= $totalBooks ?> total</div>
                    </div>
                </div>
                <div class="lib-header-chips">
                    <div class="lib-header-chip">
                        <span class="lib-header-chip-val"><?= count($myBorrowings) ?></span>
                        <span class="lib-header-chip-lbl">My Borrows</span>
                    </div>
                    <div class="lib-header-chip">
                        <span class="lib-header-chip-val"><?= count(array_filter($myBorrowings, fn($b) => ($b['status']??'') === 'approved')) ?></span>
                        <span class="lib-header-chip-lbl">Active</span>
                    </div>
                </div>
                <a href="<?= base_url('/books') ?>" class="lib-browse-btn-inline">
                    <?= icon('arrow-right', 13, 'currentColor') ?> Browse
                </a>
            </div>

            <!-- Two-card grid -->
            <div class="lib-card-grid">

                <!-- Available Books -->
                <div class="lib-card">
                    <div class="lib-card-header">
                        <div class="lib-card-header-left">
                            <div class="lib-card-icon lib-card-icon-indigo"><?= icon('book-open', 15, 'white') ?></div>
                            <div>
                                <div class="card-title">Available Now</div>
                                <div class="card-sub">Borrow today</div>
                            </div>
                        </div>
                        <a href="<?= base_url('/books') ?>" class="link-sm">All →</a>
                    </div>
                    <?php if (!empty($featuredBooks)): ?>
                        <div class="book-list">
                            <?php foreach (array_slice($featuredBooks, 0, 5) as $book):
                                $avail   = (int)($book['available_copies'] ?? 0);
                                $pillBg  = $avail === 0 ? '#fecaca' : ($avail <= 1 ? '#ddd6fe' : '#bbf7d0');
                                $pillFg  = $avail === 0 ? '#7f1d1d' : ($avail <= 1 ? '#4c1d95' : '#14532d');
                                $pillTxt = $avail === 0 ? 'Out'     : ($avail <= 1 ? '1 left'  : $avail . ' left');
                            ?>
                                <a href="<?= base_url('/books') ?>" class="book-row">
                                    <div class="book-cover"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="book-title"><?= esc($book['title']) ?></div>
                                        <div class="book-author"><?= esc($book['author'] ?? 'Unknown') ?></div>
                                    </div>
                                    <span class="avail-pill" style="background:<?= $pillBg ?>;color:<?= $pillFg ?>;"><?= $pillTxt ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($featuredBooks) > 5): ?>
                            <div style="padding:6px 16px 12px;border-top:1px solid var(--border-subtle);text-align:center;">
                                <a href="<?= base_url('/books') ?>" class="link-sm">+<?= count($featuredBooks) - 5 ?> more →</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="text-align:center;padding:28px 16px;">
                            <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('book-open', 26, 'currentColor') ?></div>
                            <p style="font-family:var(--font);font-size:.75rem;color:var(--text-sub);">No books available</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Active Borrows -->
                <?php
                $activeBorrows = array_slice(
                    array_values(array_filter($myBorrowings, fn($b) => in_array($b['status']??'', ['approved','pending']))),
                    0, 4
                );
                ?>
                <div class="lib-card">
                    <div class="lib-card-header">
                        <div class="lib-card-header-left">
                            <div class="lib-card-icon lib-card-icon-green"><?= icon('bookmark', 15, 'white') ?></div>
                            <div>
                                <div class="card-title">My Borrows</div>
                                <div class="card-sub">Currently checked out</div>
                            </div>
                        </div>
                        <a href="<?= base_url('/books') ?>#mine" class="link-sm">All →</a>
                    </div>
                    <?php if (!empty($activeBorrows)): ?>
                        <div class="borrow-list">
                            <?php foreach ($activeBorrows as $borrow):
                                $bs      = strtolower($borrow['status'] ?? 'pending');
                                $due     = !empty($borrow['due_date']) ? strtotime($borrow['due_date']) : null;
                                $overdue = $due && $due < time();
                                $dueSoon = $due && !$overdue && $due < time() + 3 * 86400;
                            ?>
                                <div class="borrow-row">
                                    <div class="borrow-cover"><?= mb_strtoupper(mb_substr($borrow['title'] ?? 'B', 0, 1)) ?></div>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-family:var(--font);font-weight:700;font-size:.78rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($borrow['title'] ?? 'Unknown Book') ?></p>
                                        <?php if ($due && $bs === 'approved'): ?>
                                            <p style="font-family:var(--mono);font-size:.62rem;color:<?= $overdue ? '#ef4444' : ($dueSoon ? '#d97706' : '#6366f1') ?>;">
                                                <?= $overdue ? 'Overdue · ' : ($dueSoon ? 'Due soon · ' : '') ?><?= date('M j', $due) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="tag tag-<?= $overdue ? 'declined' : ($dueSoon ? 'pending' : $bs) ?>" style="border-radius:8px;font-size:.58rem;">
                                        <?= $overdue ? 'Overdue' : ($dueSoon ? 'Due Soon' : ucfirst($bs)) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:28px 16px;">
                            <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('bookmark', 26, 'currentColor') ?></div>
                            <p style="font-family:var(--font);font-size:.75rem;color:var(--text-sub);font-weight:600;">No active borrows</p>
                            <a href="<?= base_url('/books') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;font-family:var(--font);font-size:.7rem;font-weight:700;color:#7c3aed;text-decoration:none;">
                                <?= icon('book-open', 11, '#7c3aed') ?> Borrow a book
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div><!-- end .lib-card-grid -->
        </div>
        <!-- End Library Section -->

        <div style="height:24px;"></div>

    </main>

    <!-- Date Modal -->
    <div id="dateModal" class="modal-back" onclick="handleModalBack(event)">
        <div class="modal-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                <div>
                    <h3 style="font-family:var(--font);font-size:16px;font-weight:700;letter-spacing:-.2px;" id="modalDateTitle"></h3>
                    <p style="font-family:var(--font);font-size:11px;color:var(--text-sub);margin-top:2px;" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" style="width:36px;height:36px;border-radius:10px;background:var(--input-bg);border:none;color:var(--text-sub);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s;">
                    <?= icon('x', 13, 'currentColor') ?>
                </button>
            </div>
            <div id="modalList"></div>
            <div class="hidden" id="modalEmpty" style="text-align:center;padding:24px 12px;">
                <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('calendar-x', 26, 'currentColor') ?></div>
                <p style="font-family:var(--font);font-size:12px;color:var(--text-sub);">No reservations for this date.</p>
            </div>
            <button onclick="closeDateModal()" style="margin-top:16px;width:100%;padding:12px;background:var(--input-bg);border-radius:12px;font-family:var(--font);font-weight:600;color:var(--text-muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;transition:background .15s;">Close</button>
        </div>
    </div>

    <!-- Login Toast -->
    <div id="loginToast" class="login-toast">
        <div class="toast-icon" id="toastIcon"></div>
        <div style="flex:1;min-width:0;">
            <p id="toastTitle"></p>
            <p id="toastBody"></p>
        </div>
        <button class="toast-close" onclick="dismissToast()"><?= icon('x', 10, 'white') ?></button>
    </div>

    <script>
        const NOTIF_KEY  = 'notified_ids_<?= session()->get('user_id') ?>';
        const reservations = <?= json_encode($reservations ?? []) ?>;
        const allResData   = <?= json_encode($allReservations ?? []) ?>;
        const approvedRes  = reservations.filter(r => r.status === 'approved' && !r.claimed);
        let notifications  = [];

        const getSeenIds  = () => { try { return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]'); } catch(e){ return []; } };
        const saveSeenIds = ids => localStorage.setItem(NOTIF_KEY, JSON.stringify(ids));

        function loadNotifications() {
            const seen = getSeenIds();
            notifications = reservations.filter(r => r.status === 'approved').map(r => ({
                id: parseInt(r.id),
                title: 'Reservation Approved',
                msg: `${r.resource_name||'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
                time: r.updated_at || r.created_at || new Date().toISOString(),
                read: seen.includes(parseInt(r.id))
            }));
            updateBadge(); renderNotifs();
        }

        function markAllRead() {
            saveSeenIds([...new Set([...getSeenIds(),...notifications.map(n=>n.id)])]);
            notifications.forEach(n=>n.read=true);
            updateBadge(); renderNotifs();
        }
        function markRead(id) {
            const ids=getSeenIds();
            if(!ids.includes(id)) saveSeenIds([...ids,id]);
            const n=notifications.find(n=>n.id===id);
            if(n){n.read=true;updateBadge();renderNotifs();}
        }
        function updateBadge() {
            const badge=document.getElementById('notifBadge');
            const unread=notifications.filter(n=>!n.read).length;
            badge.style.display=unread>0?'block':'none';
            badge.textContent=unread>9?'9+':unread;
        }
        function renderNotifs() {
            const list=document.getElementById('notifList');
            if(!notifications.length){
                list.innerHTML=`<div style="text-align:center;padding:24px 16px;"><p style="font-family:var(--font);font-size:12px;color:var(--text-sub);">All caught up!</p></div>`;
                return;
            }
            list.innerHTML=notifications
                .sort((a,b)=>new Date(b.time)-new Date(a.time))
                .map(n=>`
                <div class="notif-item ${!n.read?'unread':''}" onclick="markRead(${n.id})">
                    <div style="display:flex;align-items:flex-start;gap:9px;">
                        <div style="width:30px;height:30px;background:var(--indigo-light);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="2" style="width:12px;height:12px;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-family:var(--font);font-weight:700;font-size:12px;color:var(--text);">${n.title}</p>
                            <p style="font-family:var(--font);font-size:10px;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                            <p style="font-family:var(--font);font-size:9px;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p>
                        </div>
                        ${!n.read?'<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:4px;"></span>':''}
                    </div>
                </div>`).join('');
        }

        function toggleNotifications(){document.getElementById('notifDD').classList.toggle('show');}
        document.addEventListener('click',e=>{
            const dd=document.getElementById('notifDD'),bell=document.querySelector('.notif-bell');
            if(!bell.contains(e.target)&&!dd.contains(e.target))dd.classList.remove('show');
        });

        const timeAgo=t=>{
            const s=Math.floor((Date.now()-new Date(t))/1000);
            if(s<60)return'Just now';if(s<3600)return`${Math.floor(s/60)}m ago`;
            if(s<86400)return`${Math.floor(s/3600)}h ago`;return`${Math.floor(s/86400)}d ago`;
        };

        function to12hPHT(ts){
            if(!ts)return'—';const parts=ts.split(':');let h=parseInt(parts[0],10);
            const m=parts[1]?parts[1].padStart(2,'0'):'00';if(isNaN(h))return ts;
            const ampm=h<12?'AM':'PM';h=h%12||12;return`${h}:${m} ${ampm}`;
        }

        function openDateModal(date,items){
            const d=new Date(date+'T00:00:00');
            document.getElementById('modalDateTitle').textContent=d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
            document.getElementById('modalDateSub').textContent=items.length?`${items.length} reservation${items.length>1?'s':''}`:' ';
            const list=document.getElementById('modalList'),empty=document.getElementById('modalEmpty');
            list.innerHTML='';
            if(items.length){
                empty.classList.add('hidden');
                const cmap={approved:'#dcfce7|#166534',pending:'#fef3c7|#92400e',declined:'#fee2e2|#991b1b',canceled:'#fee2e2|#991b1b',claimed:'#ede9fe|#5b21b6'};
                items.sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).forEach(r=>{
                    const isClaimed=r.claimed==1||r.status==='claimed'||!!r.claimed_at;
                    const s=isClaimed?'claimed':(r.status||'pending').toLowerCase();
                    const[cbg,cfg]=(cmap[s]||'#f1f5f9|#475569').split('|');
                    const tFmt=r.start_time?to12hPHT(r.start_time):'All day';
                    const etFmt=r.end_time?to12hPHT(r.end_time):'';
                    const timeDisplay=etFmt?`${tFmt} – ${etFmt} PHT`:tFmt;
                    const row=document.createElement('div');
                    row.className='date-row';
                    row.innerHTML=`
                    <div style="width:32px;height:32px;background:var(--input-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border);">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-sub)" stroke-width="1.5" style="width:13px;height:13px;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-family:var(--font);font-weight:600;font-size:13px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Reserved'}</p>
                        <p style="font-family:var(--font);font-size:11px;color:#3730a3;margin-top:1px;font-weight:600;">${timeDisplay}</p>
                    </div>
                    <span style="display:inline-flex;padding:2px 9px;border-radius:999px;font-family:var(--font);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:${cbg};color:${cfg};flex-shrink:0;">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
                    list.appendChild(row);
                });
            }else{empty.classList.remove('hidden');}
            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow='hidden';
        }

        function closeDateModal(){document.getElementById('dateModal').classList.remove('show');document.body.style.overflow='';}
        function handleModalBack(e){if(e.target.classList.contains('modal-back'))closeDateModal();}
        document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDateModal();});

        function initTimer(){
            const banner=document.getElementById('timerBanner'),
                titleEl=document.getElementById('timerTitle'),
                subEl=document.getElementById('timerSub'),
                hEl=document.getElementById('tdH'),mEl=document.getElementById('tdM'),sEl=document.getElementById('tdS'),
                iconW=document.getElementById('timerIconWrap'),pw=document.getElementById('timerPW'),pf=document.getElementById('timerPF');
            const mkSvg=(path,sw)=>`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="${sw}" style="width:16px;height:16px;flex-shrink:0;">${path}</svg>`;
            const icons={
                urgent: mkSvg('<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>','1.8'),
                warning:mkSvg('<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round"/>','1.8'),
                safe:   mkSvg('<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>','1.8'),
            };
            function findTarget(){
                const now=Date.now();let active=null,upcoming=null;
                approvedRes.forEach(r=>{
                    if(!r.reservation_date||!r.start_time||!r.end_time)return;
                    const start=new Date(r.reservation_date+'T'+r.start_time).getTime();
                    const end=new Date(r.reservation_date+'T'+r.end_time).getTime();
                    const minsToStart=(start-now)/60000,minsToEnd=(end-now)/60000;
                    if(now>=start&&now<end&&!active)active={r,start,end,mode:'active',minsLeft:minsToEnd};
                    if(!upcoming&&minsToStart>0&&minsToStart<=30)upcoming={r,start,end,mode:'upcoming',minsLeft:minsToStart};
                });
                return active||upcoming||null;
            }
            function tick(){
                const target=findTarget();
                if(!target){banner.style.display='none';return;}
                const{r,start,end,mode,minsLeft}=target;
                const now=Date.now(),diff=Math.max(0,(mode==='active'?end:start)-now);
                const h=Math.floor(diff/3600000),m=Math.floor((diff%3600000)/60000),s=Math.floor((diff%60000)/1000);
                hEl.textContent=String(h).padStart(2,'0');mEl.textContent=String(m).padStart(2,'0');sEl.textContent=String(s).padStart(2,'0');
                banner.classList.remove('urgent','warning','safe');
                if(mode==='active'){
                    if(minsLeft<=10){banner.classList.add('urgent');iconW.innerHTML=icons.urgent;}
                    else if(minsLeft<=20){banner.classList.add('warning');iconW.innerHTML=icons.warning;}
                    else{banner.classList.add('safe');iconW.innerHTML=icons.safe;}
                    titleEl.textContent=minsLeft<=10?'Reservation ends very soon!':'Your reservation is active';
                    subEl.textContent=`${r.resource_name||'Resource'} · Ends at ${(r.end_time||'').substring(0,5)}`;
                    const pct=Math.min(100,Math.max(0,((now-start)/(end-start))*100));
                    pw.style.display='block';pf.style.width=pct.toFixed(1)+'%';
                }else{
                    banner.classList.add('safe');iconW.innerHTML=icons.safe;
                    titleEl.textContent='Your reservation starts soon';
                    subEl.textContent=`${r.resource_name||'Resource'} · Starts at ${(r.start_time||'').substring(0,5)}`;
                    pw.style.display='none';
                }
                banner.style.display='block';
            }
            tick();setInterval(tick,1000);
        }

        function showLoginToast(){
            const key='toast_<?= session()->get('user_id') ?>_'+new Date().toDateString();
            if(sessionStorage.getItem(key))return;
            sessionStorage.setItem(key,'1');
            const now=Date.now();let td=null;
            approvedRes.forEach(r=>{
                if(!r.reservation_date||!r.start_time||!r.end_time)return;
                const start=new Date(r.reservation_date+'T'+r.start_time).getTime();
                const end=new Date(r.reservation_date+'T'+r.end_time).getTime();
                const minsToStart=(start-now)/60000;
                const today=new Date().toDateString(),resDay=new Date(r.reservation_date+'T00:00:00').toDateString();
                if(now>=start&&now<end&&!td)td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><polygon points="5 3 19 12 5 21 5 3"/></svg>',bg:'rgba(37,99,235,.2)',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${(r.end_time||'').substring(0,5)}`};
                if(!td&&resDay===today&&minsToStart>0&&minsToStart<=120)td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',bg:'rgba(217,119,6,.2)',title:`In ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${(r.start_time||'').substring(0,5)} – ${(r.end_time||'').substring(0,5)}`};
                if(!td&&resDay===today){const fmt=t=>{const[h,m]=t.split(':');const hr=+h;return`${hr%12||12}:${m} ${hr<12?'AM':'PM'}`};td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>',bg:'rgba(37,99,235,.2)',title:'Reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`};}
            });
            if(!td)return;
            const toast=document.getElementById('loginToast');
            document.getElementById('toastIcon').innerHTML=td.icon;
            document.getElementById('toastIcon').style.background=td.bg;
            document.getElementById('toastTitle').textContent=td.title;
            document.getElementById('toastBody').textContent=td.body;
            setTimeout(()=>toast.classList.add('show'),900);
            setTimeout(()=>toast.classList.remove('show'),7500);
        }

        function dismissToast(){document.getElementById('loginToast').classList.remove('show');}

        document.addEventListener('DOMContentLoaded',()=>{
            document.documentElement.classList.remove('dark-pre');
            if('Notification'in window)Notification.requestPermission();
            loadNotifications();initTimer();showLoginToast();

            const byDate={};
            allResData.forEach(r=>{
                if(!r.reservation_date)return;
                if(!byDate[r.reservation_date])byDate[r.reservation_date]=[];
                byDate[r.reservation_date].push(r);
            });

            const colorMap={approved:'#10b981',pending:'#fbbf24',declined:'#f87171',canceled:'#f87171',claimed:'#a855f7'};
            const events=allResData.filter(r=>r.reservation_date).map(r=>{
                const isClaimed=r.claimed==1||r.status==='claimed'||!!r.claimed_at;
                const s=isClaimed?'claimed':(r.status||'pending').toLowerCase();
                const d=r.reservation_date.trim();
                return{
                    title:r.resource_name||'Reservation',
                    start:d+(r.start_time?'T'+r.start_time.substring(0,8):''),
                    end:d+(r.end_time?'T'+r.end_time.substring(0,8):''),
                    allDay:!r.start_time,
                    backgroundColor:colorMap[s]||'#94a3b8',
                    borderColor:'transparent',
                    textColor:'#fff',
                    extendedProps:{status:s}
                };
            });

            const w=window.innerWidth,calView=w<480?'listWeek':'dayGridMonth',calHeight=w<640?'auto':390;
            const cal=new FullCalendar.Calendar(document.getElementById('calendar'),{
                initialView:calView,
                headerToolbar:{left:'prev,next',center:'title',right:'today'},
                events,height:calHeight,eventDisplay:'block',eventMaxStack:2,
                dateClick:info=>openDateModal(info.dateStr,byDate[info.dateStr]||[]),
                eventClick:info=>{const d=info.event.startStr.split('T')[0];openDateModal(d,byDate[d]||[]);},
                dayCellDidMount:info=>{
                    const d=info.date.toISOString().split('T')[0],items=byDate[d];
                    if(items&&items.length){
                        const badge=document.createElement('div');
                        badge.style.cssText='font-family:var(--mono);font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;margin-bottom:1px;';
                        badge.textContent=items.length;
                        info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);
                    }
                }
            });
            cal.render();
        });
    </script>

    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>