<?php
$unclaimedCount = 0;
$claimedCount   = 0;
$processedRecent = [];
foreach (($reservations ?? []) as $r) {
    $isCl = !empty($r['claimed']) && $r['claimed'] == 1;
    $s    = $isCl ? 'claimed' : strtolower($r['status'] ?? 'pending');
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
            if ($dt > $now) {
                $upcoming = $r;
                break;
            }
        }
    }
}

$nextAction = null;
if ($pending > 0) {
    $nextAction = ['type' => 'pending', 'msg' => "You have {$pending} reservation" . ($pending > 1 ? 's' : '') . " awaiting approval. SK officers usually respond within 24 hours.", 'cta' => 'View Reservations', 'url' => '/reservation-list', 'color' => 'amber'];
} elseif ($upcoming) {
    $nextAction = ['type' => 'upcoming', 'msg' => "Your approved slot is coming up. Download your e-ticket from My Reservations and scan it at the entrance when you arrive.", 'cta' => 'Get E-Ticket', 'url' => '/reservation-list', 'color' => 'blue'];
} elseif ($unclaimedCount > 0) {
    $nextAction = ['type' => 'unclaimed', 'msg' => "You missed {$unclaimedCount} approved slot" . ($unclaimedCount > 1 ? 's' : '') . " without showing up. Please cancel in advance if you can't attend — repeated no-shows may limit future bookings.", 'cta' => 'See Details', 'url' => '/reservation-list', 'color' => 'orange'];
} elseif ($remaining === 0) {
    $nextAction = ['type' => 'quota', 'msg' => "You've used all 3 reservation slots for this month. Your quota resets on the 1st of next month.", 'cta' => 'Browse Library', 'url' => '/books', 'color' => 'slate'];
} elseif (empty($reservations)) {
    $nextAction = ['type' => 'empty', 'msg' => "Welcome! You haven't made any reservations yet. Book a computer, study space, or other facility anytime.", 'cta' => 'Make First Reservation', 'url' => '/reservation', 'color' => 'blue'];
}

$nextColors = [
    'amber'  => ['bg' => 'rgba(251,191,36,.08)',   'border' => 'rgba(251,191,36,.25)',  'icon_bg' => 'rgba(251,191,36,.15)',  'icon_fg' => '#d97706', 'btn_bg' => '#d97706', 'icon' => 'clock'],
    'blue'   => ['bg' => 'rgba(99,102,241,.06)',   'border' => 'rgba(99,102,241,.2)',   'icon_bg' => 'rgba(99,102,241,.12)', 'icon_fg' => '#4338ca', 'btn_bg' => '#4338ca', 'icon' => 'ticket'],
    'orange' => ['bg' => 'rgba(234,88,12,.06)',    'border' => 'rgba(234,88,12,.2)',    'icon_bg' => 'rgba(234,88,12,.1)',   'icon_fg' => '#ea580c', 'btn_bg' => '#ea580c', 'icon' => 'triangle'],
    'slate'  => ['bg' => 'rgba(100,116,139,.05)',  'border' => 'rgba(100,116,139,.15)', 'icon_bg' => 'rgba(100,116,139,.1)','icon_fg' => '#64748b', 'btn_bg' => '#64748b', 'icon' => 'calendar-x'],
];

function icon($name, $size = 16, $stroke = 'currentColor', $extra = '')
{
    $icons = [
        'house'         => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus'          => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'calendar'      => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'book-open'     => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'user'          => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'logout'        => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
        'clock'         => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'check-circle'  => '<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'ticket'        => '<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round"/>',
        'triangle'      => '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'calendar-x'    => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="14" x2="14" y2="18"/><line x1="14" y1="14" x2="10" y2="18"/>',
        'bell'          => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
        'check'         => '<polyline points="20 6 9 17 4 12"/>',
        'x'             => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'chevron-right' => '<polyline points="9 18 15 12 9 6"/>',
        'arrow-right'   => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'ban'           => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>',
        'hourglass'     => '<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/>',
        'layers'        => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'list-check'    => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke-linecap="round" stroke-linejoin="round"/>',
        'sparkles'      => '<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round" stroke-linejoin="round"/>',
        'search'        => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'bookmark'      => '<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>',
        'robot'         => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><circle cx="12" cy="5" r="1"/>',
        'info'          => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'check-double'  => '<path d="M17 1l-8.5 8.5L6 7M22 6l-8.5 8.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 13l-4 4 1.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar-days' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>',
        'bar-chart'     => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        'eye'           => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
        'trending-up'   => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
    ];
    $d  = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    $sw = in_array($name, ['calendar', 'calendar-days', 'calendar-x', 'bar-chart', 'bookmark', 'robot']) ? '1.5' : '1.8';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $stroke . '" stroke-width="' . $sw . '" ' . $extra . '>' . $d . '</svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>Dashboard | <?= esc($user_name) ?></title>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <meta name="theme-color" content="#1e1b4b">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <?php
    /*
     * Dark-mode pre-init: eliminates flash of wrong theme.
     * Mirrors the snippet in layout.php — must run before <body>.
     */
    ?>
    <script>
    (function () {
        try {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark-pre');
            }
        } catch (e) {}
    })();
    </script>

    <?php
    /*
     * Dashboard-specific styles only.
     * Global tokens, sidebar, mobile-nav, cards, tags, modals,
     * dark-mode overrides, and utility classes all live in app.css.
     */
    ?>
    <style>
        /* ── Layout shell ── */
        body {
            display: flex;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        html.dark-pre body,
        html.dark-pre .sidebar-inner,
        html.dark-pre .mobile-nav-pill { background: #060e1e; }

        /* ── Reserve button (topbar) ── */
        .reserve-btn {
            display: none;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            background: var(--indigo);
            color: #fff;
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            letter-spacing: -.01em;
            transition: all var(--ease);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(55,48,163,.28);
            touch-action: manipulation;
        }
        @media(min-width:480px) { .reserve-btn { display: flex; } }
        .reserve-btn:hover { background:#312e81; transform:translateY(-1px); box-shadow:0 6px 18px rgba(55,48,163,.35); }

        .btn-text { display: none; }

        /* ── Notification dropdown ── */
        .notif-bell { position: relative; }
        .notif-badge {
            position: absolute; top: -5px; right: -5px;
            background: #ef4444; color: white;
            font-size: .55rem; font-weight: 700;
            padding: 2px 5px; border-radius: 999px;
            min-width: 17px; text-align: center;
            border: 2px solid var(--bg); line-height: 1.3;
            pointer-events: none;
        }
        .notif-dd {
            position: fixed; top: 80px; right: 20px;
            width: 320px; background: var(--card);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.09);
            z-index: 200; display: none; overflow: hidden;
            animation: l-fade-in .15s ease;
        }
        .notif-dd.show { display: block; }
        .notif-item {
            padding: .85rem 1.1rem;
            border-bottom: 1px solid var(--border-subtle);
            transition: background .15s; cursor: pointer;
            touch-action: manipulation;
        }
        .notif-item:hover { background: var(--input-bg); }
        .notif-item.unread { background: var(--indigo-light); }
        .notif-item:last-child { border-bottom: none; }
        @media(max-width:479px) { .notif-dd { left:12px; right:12px; width:auto; top:72px; } }

        /* ── Next-action card ── */
        .next-card {
            display: flex; align-items: flex-start; gap: 14px;
            border-radius: var(--r-md); padding: 16px 18px;
            border: 1px solid; margin-bottom: 20px;
            animation: l-slide-up .4s ease both;
        }
        .next-icon-wrap {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .next-eyebrow { font-size:.6rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; margin-bottom:4px; }
        .next-msg { font-size:.83rem; color: var(--text-muted); line-height:1.6; }
        .next-cta {
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 10px; padding: 9px 16px; border-radius: 9px;
            font-size: .75rem; font-weight: 700; color: #fff;
            text-decoration: none; font-family: var(--font);
            transition: opacity var(--ease); touch-action: manipulation;
        }
        .next-cta:hover { opacity: .85; }

        /* ── Countdown timer banner ── */
        .timer-banner {
            display: none; border-radius: var(--r-md);
            padding: 14px 18px; margin-bottom: 18px; border: 1px solid;
            animation: l-slide-up .35s cubic-bezier(.34,1.56,.64,1) both;
        }
        .timer-banner.urgent  { background:#fff7ed; border-color:#fed7aa; color:#9a3412; }
        .timer-banner.warning { background:#fefce8; border-color:#fde68a; color:#854d0e; }
        .timer-banner.safe    { background:var(--indigo-light); border-color:var(--indigo-border); color:#312e81; }
        body.dark .timer-banner.safe    { background:rgba(55,48,163,.15); border-color:rgba(55,48,163,.3); color:#a5b4fc; }
        body.dark .timer-banner.warning { background:rgba(180,83,9,.2); border-color:rgba(180,83,9,.35); color:#fcd34d; }
        body.dark .timer-banner.urgent  { background:rgba(154,52,18,.2); border-color:rgba(154,52,18,.35); color:#fb923c; }

        .timer-inner { display:flex; align-items:center; gap:11px; flex-wrap:wrap; }
        .timer-text-col { flex:1; min-width:140px; }
        .timer-digit {
            display:inline-flex; flex-direction:column; align-items:center;
            background:rgba(0,0,0,.07); border-radius:8px;
            padding:.2rem .5rem; min-width:2.6rem;
            font-variant-numeric:tabular-nums; font-weight:700;
            font-size:1.1rem; line-height:1; font-family:var(--mono);
        }
        .timer-digit span { font-size:.5rem; font-weight:500; opacity:.6; text-transform:uppercase; letter-spacing:.07em; margin-top:3px; font-family:var(--font); }
        .timer-pulse { animation: pulse .9s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
        .timer-progress-wrap { height:3px; border-radius:999px; background:rgba(0,0,0,.08); overflow:hidden; margin-top:10px; }
        .timer-progress-fill { height:100%; border-radius:999px; background:currentColor; opacity:.4; transition:width 1s linear; }
        @media(max-width:400px) { .timer-digit { min-width:2.1rem; padding:.15rem .35rem; font-size:.95rem; } .timer-inner { gap:8px; } }

        /* ── Upcoming pill ── */
        .upcoming-pill {
            background: var(--indigo-light); border:1px solid var(--indigo-border);
            border-radius: var(--r-md); padding:14px 16px;
            display:flex; align-items:center; gap:14px;
            margin-bottom:20px; animation:l-slide-up .4s ease both; flex-wrap:wrap;
        }
        .up-icon { width:38px; height:38px; background:var(--indigo); border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 10px rgba(55,48,163,.28); }
        .up-eyebrow { font-size:.6rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--indigo); margin-bottom:2px; }
        .up-name { font-size:.88rem; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px; }
        .up-time { font-size:.72rem; color:#4338ca; font-family:var(--mono); margin-top:1px; }
        .up-btn { margin-left:auto; font-size:.72rem; font-weight:700; color:var(--indigo); background:var(--card); border:1px solid var(--indigo-border); border-radius:8px; padding:8px 14px; text-decoration:none; white-space:nowrap; transition:all var(--ease); touch-action:manipulation; }
        .up-btn:hover { background:var(--indigo); color:white; box-shadow:0 2px 8px rgba(55,48,163,.22); }
        @media(max-width:479px) { .up-name { max-width:100%; } .up-btn { margin-left:0; width:100%; text-align:center; display:block; } }

        /* ── Stats grid ── */
        .stats-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:20px; }
        .stat-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r-lg); padding:18px 20px; box-shadow:var(--shadow-sm); transition:transform var(--ease), box-shadow var(--ease); }
        .stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
        .stat-card-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px; }
        .stat-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
        .stat-lbl { font-size:.62rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--text-sub); }
        .stat-num { font-size:2rem; font-weight:800; color:var(--text); line-height:1; letter-spacing:-.04em; font-family:var(--mono); }
        .stat-hint { font-size:.72rem; color:var(--text-sub); margin-top:4px; }
        @media(max-width:639px) { .stats-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; } .stat-card { padding:14px 16px; } .stat-num { font-size:1.6rem; } .stat-card-top { margin-bottom:10px; } }
        @media(max-width:360px) { .stats-grid { gap:8px; } .stat-card { padding:12px 14px; } .stat-num { font-size:1.4rem; } .stat-icon { width:30px; height:30px; } }

        /* ── Main two-col grid ── */
        .grid-main { display:grid; grid-template-columns:minmax(0,1.9fr) minmax(0,1fr); gap:16px; margin-bottom:18px; }
        .side-col { display:flex; flex-direction:column; gap:14px; }
        @media(max-width:900px) { .grid-main { grid-template-columns:1fr; } }

        /* ── Card sub-elements ── */
        .card-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .card-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .card-title { font-size:.9rem; font-weight:700; color:var(--text); letter-spacing:-.01em; }
        .card-sub   { font-size:.7rem; color:var(--text-sub); margin-top:2px; }
        .section-lbl { font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--text-sub); margin-bottom:14px; }
        .link-sm { font-size:.65rem; font-weight:700; color:var(--indigo); text-decoration:none; letter-spacing:.05em; text-transform:uppercase; transition:opacity .15s; touch-action:manipulation; }
        .link-sm:hover { opacity:.7; }

        /* ── FullCalendar overrides ── */
        #calendar { font-size:.8rem; font-family:var(--font); }
        .fc .fc-toolbar { flex-wrap:wrap; gap:.5rem; }
        .fc-toolbar-title { font-size:.95rem !important; font-weight:800 !important; color:var(--text) !important; font-family:var(--font) !important; letter-spacing:-.02em !important; }
        .fc-button-primary { background:var(--indigo) !important; border-color:var(--indigo) !important; border-radius:9px !important; font-family:var(--font) !important; font-weight:700 !important; font-size:.72rem !important; padding:.3rem .65rem !important; box-shadow:none !important; touch-action:manipulation !important; }
        .fc-button-primary:hover { background:#312e81 !important; }
        .fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active { background:#1e1b4b !important; }
        .fc-daygrid-event { border-radius:5px !important; font-size:.65rem !important; font-weight:600 !important; padding:2px 5px !important; border:none !important; cursor:pointer !important; font-family:var(--font) !important; }
        .fc-daygrid-day:hover { background-color:var(--indigo-light) !important; cursor:pointer; }
        .fc-day-today { background:rgba(55,48,163,.06) !important; }
        .fc-day-today .fc-daygrid-day-number { color:var(--indigo) !important; font-weight:800 !important; }
        .fc-daygrid-day-number { font-size:.72rem; font-weight:600; font-family:var(--font); }
        .fc-col-header-cell-cushion { font-family:var(--font); font-size:.72rem; font-weight:700; letter-spacing:.04em; }
        body.dark .fc-toolbar-title { color:var(--text) !important; }
        body.dark .fc-daygrid-day-number { color:#7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color:#7fb3e8; }
        body.dark .fc-day-today { background:rgba(55,48,163,.15) !important; }
        body.dark .fc-daygrid-day { background:var(--card) !important; }
        body.dark .fc-theme-standard td, body.dark .fc-theme-standard th, body.dark .fc-theme-standard .fc-scrollgrid { border-color:#101e35 !important; }
        body.dark .fc-list-empty { background:var(--card) !important; }
        body.dark .fc-list-empty-cushion { color:var(--text-sub) !important; }
        body.dark .fc-list-table td { background:var(--card) !important; border-color:var(--input-bg) !important; color:#7fb3e8 !important; }
        body.dark .fc-list-table th { background:var(--input-bg) !important; border-color:var(--input-bg) !important; color:var(--text-sub) !important; }
        body.dark .fc-list-event-title a { color:var(--text) !important; }
        @media(max-width:479px) {
            .fc .fc-toolbar { display:grid; grid-template-columns:auto 1fr auto; align-items:center; gap:6px; }
            .fc-toolbar-chunk:nth-child(2) { text-align:center; }
            .fc-toolbar-title { font-size:.8rem !important; }
            .fc-button-primary { font-size:.65rem !important; padding:.25rem .5rem !important; }
            #calendar { font-size:.7rem; }
            .fc .fc-daygrid-body { min-height:auto !important; }
            #calendar .fc-daygrid-body, #calendar .fc-scrollgrid-sync-table { width:100% !important; }
            .fc .fc-daygrid-day-frame { min-height:32px !important; }
        }

        /* ── Calendar legend ── */
        .cal-legend { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        .leg-item { display:flex; align-items:center; gap:5px; }
        .leg-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .leg-lbl { font-size:.68rem; font-weight:600; color:var(--text-sub); }
        @media(max-width:479px) { .cal-legend { gap:8px; } .leg-lbl { display:none; } .leg-dot { width:9px; height:9px; } }

        /* ── Quick-action links ── */
        .qa-link { display:flex; align-items:center; gap:11px; padding:12px; border-radius:var(--r-sm); border:1px solid var(--border); background:var(--card); text-decoration:none; color:var(--text-muted); font-size:.83rem; font-weight:600; transition:all var(--ease); touch-action:manipulation; }
        .qa-link:hover { border-color:var(--indigo); background:var(--indigo-light); color:var(--indigo); }
        @media(pointer:fine) { .qa-link:hover { transform:translateX(3px); } }
        .qa-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .qa-chev { margin-left:auto; color:var(--text-faint); transition:color var(--ease); }
        .qa-link:hover .qa-chev { color:var(--indigo); }

        /* ── Recent booking rows ── */
        .bk-row { display:flex; align-items:center; gap:11px; padding:9px 8px; border-radius:11px; text-decoration:none; color:inherit; transition:background var(--ease); touch-action:manipulation; }
        .bk-row:hover { background:var(--indigo-light); }
        .bk-date { width:38px; height:38px; background:var(--input-bg); border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0; border:1px solid var(--border-subtle); }
        .bk-month { font-size:.55rem; font-weight:700; text-transform:uppercase; color:var(--text-sub); }
        .bk-day  { font-size:.95rem; font-weight:800; color:var(--text); line-height:1; font-family:var(--mono); }
        .bk-name { font-size:.82rem; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .bk-time { font-size:.68rem; color:var(--text-sub); margin-top:1px; font-family:var(--mono); }

        /* ── Date modal rows ── */
        .date-row { display:flex; align-items:center; gap:11px; padding:.75rem; border-bottom:1px solid var(--border-subtle); border-radius:10px; transition:background .15s; }
        .date-row:hover { background:var(--input-bg); }
        .date-row:last-child { border-bottom:none; }

        /* ── How-to / status guide ── */
        .how-step { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px solid var(--border-subtle); }
        .how-step:last-child { border-bottom:none; }
        .step-num { width:24px; height:24px; border-radius:50%; background:var(--indigo); color:white; font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
        .status-guide-row { display:flex; align-items:center; gap:10px; padding:7px 0; border-bottom:1px solid var(--border-subtle); }
        .status-guide-row:last-child { border-bottom:none; }

        /* ── Library grid ── */
.grid-lib {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 16px;
    margin-bottom: 16px;
    align-items: start;
}

@media (max-width: 900px) {
    .grid-lib { grid-template-columns: 1fr; }
}

/* ── Lib banner — overflow-safe ── */
.lib-banner {
    background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%);
    border-radius: var(--r-lg);
    padding: 20px 20px 16px;
    overflow: hidden;
    position: relative;
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
}

.lib-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;
    opacity: .4;
    pointer-events: none;
}

.lib-stats {
    display: flex;
    gap: 6px;
    flex-wrap: nowrap;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
}

.lib-stat {
    flex: 1 1 0;
    min-width: 0;
    overflow: hidden;
    background: rgba(255,255,255,.1);
    border-radius: 10px;
    padding: 7px 8px;
    border: 1px solid rgba(255,255,255,.1);
    display: flex;
    align-items: center;
    gap: 5px;
    box-sizing: border-box;
}

.lib-stat-icon {
    width: 22px;
    height: 22px;
    background: rgba(255,255,255,.12);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.lib-stat-lbl {
    font-size: .52rem;
    font-weight: 600;
    color: rgba(255,255,255,.55);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

.lib-stat-val {
    font-size: .88rem;
    font-weight: 800;
    color: white;
    font-family: var(--mono);
    line-height: 1.2;
    display: block;
}

.lib-browse {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 14px;
    background: rgba(255,255,255,.18);
    color: white;
    border-radius: 9px;
    font-size: .78rem;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,.2);
    transition: background var(--ease);
    white-space: nowrap;
    flex-shrink: 0;
}

.lib-browse:hover { background: rgba(255,255,255,.28); }

@media (max-width: 639px) {
    .lib-banner { padding: 14px 14px 12px; }

    .lib-stat { flex-direction: column; align-items: flex-start; gap: 3px; padding: 6px 7px; }
    .lib-stat-icon { display: none; }
    .lib-stat-lbl { font-size: .44rem; letter-spacing: 0; }
    .lib-stat-val { font-size: .82rem; }

    .lib-browse { width: 100%; justify-content: center; font-size: .75rem; padding: 9px; }
}

        /* ── Book list items ── */
        .book-letter { width:34px; height:34px; border-radius:9px; background:var(--indigo-light); color:var(--indigo); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.8rem; flex-shrink:0; letter-spacing:-.02em; }
        .book-title  { font-size:.82rem; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .book-author { font-size:.7rem; color:var(--text-sub); margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .avail-dot   { width:8px; height:8px; border-radius:50%; }
        .avail-dot.on  { background:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
        .avail-dot.off { background:var(--text-faint); }
        .avail-num   { font-size:.68rem; color:var(--text-sub); font-family:var(--mono); }
        body.dark .book-letter { background:rgba(55,48,163,.2); color:#818cf8; }

        /* ── Active-borrow rows ── */
        .borrow-row { display:flex; align-items:center; gap:9px; background:var(--input-bg); border-radius:10px; padding:9px 12px; border:1px solid var(--border-subtle); }

        /* ── AI finder ── */
        .rag-wrap { position:relative; margin-top:12px; }
        .rag-icon-pos { position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none; }
        .search-input { width:100%; padding:11px 12px 11px 34px; border-radius:var(--r-sm); border:1px solid rgba(99,102,241,.15); font-size:1rem; font-family:var(--font); background:var(--input-bg); color:var(--text); transition:all var(--ease); outline:none; -webkit-appearance:none; }
        .search-input:focus { border-color:#818cf8; background:var(--card); box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .search-input::placeholder { color:var(--text-sub); }
        .ai-result-box { display:none; margin-top:.75rem; background:var(--indigo-light); border:1px solid var(--indigo-border); border-radius:var(--r-sm); padding:12px 14px; overflow:hidden; }
        .ai-result-box.show { display:block; animation:l-slide-up .3s ease; }
        #ragBooks { margin-top:8px; display:flex; flex-wrap:wrap; gap:5px; overflow:hidden; }
        #ragBooks a { max-width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .find-btn { display:inline-flex; align-items:center; gap:7px; padding:10px 16px; background:var(--indigo); color:white; border-radius:var(--r-sm); font-size:.8rem; font-weight:700; border:none; cursor:pointer; font-family:var(--font); transition:all var(--ease); touch-action:manipulation; }
        .find-btn:hover { background:#312e81; }
        .find-btn:disabled { opacity:.6; cursor:not-allowed; }
        body.dark .ai-result-box { background:rgba(55,48,163,.15) !important; border-color:rgba(99,102,241,.25) !important; }
        body.dark #ragText, body.dark #ragText * { color:#a5b4fc !important; }

        /* ── Login toast ── */
        .login-toast { position:fixed; bottom:calc(var(--mob-nav-total) + 8px); right:16px; z-index:400; max-width:280px; background:#0f172a; border-radius:14px; padding:12px 14px; display:flex; align-items:flex-start; gap:10px; box-shadow:0 8px 32px rgba(0,0,0,.3); transform:translateY(8px); opacity:0; pointer-events:none; transition:all .35s cubic-bezier(.34,1.56,.64,1); }
        .login-toast.show { transform:none; opacity:1; pointer-events:auto; }
        .toast-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .toast-close { background:rgba(255,255,255,.08); border:none; border-radius:6px; width:24px; height:24px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; margin-top:1px; touch-action:manipulation; }
        @media(min-width:1024px) { .login-toast { bottom:24px; } }
        @media(max-width:479px) { .login-toast { bottom:calc(var(--mob-nav-total) + 6px); left:12px; right:12px; max-width:none; } }

        @media(max-width:639px) { .topbar { margin-bottom:14px; } .greeting-name { font-size:1.35rem; } }

/* ── Lib banner mobile overflow fix ── */
.lib-banner { max-width:100%; overflow:hidden; box-sizing:border-box; }
@media(max-width:639px) {
    .lib-banner { padding:14px; }
    .lib-stat-item {
        flex:1 1 calc(50% - 4px);
        min-width:0;
        padding:6px 8px;
        box-sizing:border-box;
    }
    .lib-stat-lbl { font-size:.46rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .lib-stat-val { font-size:.82rem; line-height:1.1; }
    .grid-lib { grid-template-columns:1fr !important; }
}
    </style>
</head>

<body>

<?php
/*
 * ═══════════════════════════════════════════════════════════════════
 * SHARED LAYOUT PARTIAL
 * Renders: sidebar, mobile bottom nav, dark-mode toggle JS,
 * and links app.css. Also exposes layout_dark_toggle() helper.
 * ═══════════════════════════════════════════════════════════════════
 */
$page = 'dashboard';
include(APPPATH . 'Views/partials/layout.php');
?>

<!-- ═══════════════════════════════════════════════════════════════
     MAIN CONTENT AREA
════════════════════════════════════════════════════════════════ -->
<main class="main-area">

    <!-- Topbar -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow"><?php $h=(int)date('H'); echo $h<12?'Good morning':($h<17?'Good afternoon':'Good evening'); ?></div>
            <div class="greeting-name"><?= esc($user_name) ?></div>
            <div class="greeting-sub"><?= date('l, F j, Y') ?></div>
        </div>
        <div class="topbar-right">
            <?= layout_dark_toggle() ?>
            <a href="<?= base_url('/reservation') ?>" class="reserve-btn">
                <?= icon('plus', 16, 'white') ?> <span class="btn-text">Reserve</span>
            </a>
            <div class="notif-bell" onclick="toggleNotifications()">
                <div class="l-icon-btn"><?= icon('bell', 16, '#64748b') ?></div>
                <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
            </div>
        </div>
    </div>

    <!-- Notification dropdown -->
    <div id="notifDD" class="notif-dd">
        <div style="padding:11px 13px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
            <button onclick="markAllRead()" style="font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;touch-action:manipulation;">Mark all read</button>
        </div>
        <div id="notifList" style="max-height:280px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
    </div>

    <!-- Flash message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-ok fade-up">
            <?= icon('check-circle', 14, 'var(--indigo)') ?>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Next-action card -->
    <?php if ($nextAction): $nc = $nextColors[$nextAction['color']]; ?>
        <div class="next-card fade-up" style="background:<?= $nc['bg'] ?>;border-color:<?= $nc['border'] ?>;">
            <div class="next-icon-wrap" style="background:<?= $nc['icon_bg'] ?>;color:<?= $nc['icon_fg'] ?>;">
                <?= icon($nc['icon'], 14, $nc['icon_fg']) ?>
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

    <!-- Countdown timer banner -->
    <div id="timerBanner" class="timer-banner">
        <div class="timer-inner">
            <div id="timerIconWrap" style="width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.07);flex-shrink:0;">
                <?= icon('hourglass', 16, 'currentColor') ?>
            </div>
            <div class="timer-text-col">
                <p style="font-weight:700;font-size:.9rem;line-height:1.3;" id="timerTitle">Your reservation ends soon</p>
                <p style="font-size:.76rem;opacity:.7;margin-top:2px;" id="timerSub"></p>
            </div>
            <div style="display:flex;align-items:center;gap:4px;flex-shrink:0;">
                <div class="timer-digit"><span id="tdH">00</span><span>hrs</span></div>
                <span style="font-weight:700;font-size:14px;opacity:.4;" class="timer-pulse">:</span>
                <div class="timer-digit"><span id="tdM">00</span><span>min</span></div>
                <span style="font-weight:700;font-size:14px;opacity:.4;" class="timer-pulse">:</span>
                <div class="timer-digit"><span id="tdS">00</span><span>sec</span></div>
            </div>
        </div>
        <div id="timerPW" class="timer-progress-wrap" style="display:none;">
            <div id="timerPF" class="timer-progress-fill" style="width:0%;"></div>
        </div>
    </div>

    <!-- Upcoming reservation pill -->
    <?php if ($upcoming): ?>
        <div class="upcoming-pill fade-up-1">
            <div class="up-icon"><?= icon('ticket', 16, 'white') ?></div>
            <div style="flex:1;min-width:0;">
                <div class="up-eyebrow">Upcoming Reservation</div>
                <div class="up-name"><?= esc($upcoming['resource_name'] ?? 'Resource') ?><?php if (!empty($upcoming['pc_number'])): ?> &middot; <span style="font-weight:400;"><?= esc($upcoming['pc_number']) ?></span><?php endif; ?></div>
                <div class="up-time"><?= date('M j, Y', strtotime($upcoming['reservation_date'])) ?> &nbsp;&middot;&nbsp; <?= date('g:i A', strtotime($upcoming['start_time'])) ?> – <?= date('g:i A', strtotime($upcoming['end_time'])) ?></div>
            </div>
            <a href="<?= base_url('/reservation-list') ?>" class="up-btn">View →</a>
        </div>
    <?php endif; ?>

    <!-- Stats grid -->
    <div class="stats-grid fade-up-2">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#eef2ff;"><?= icon('layers', 16, '#3730a3') ?></div>
                <span class="stat-lbl">Total</span>
            </div>
            <div class="stat-num"><?= $total ?></div>
            <div class="stat-hint">All time</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#fef3c7;"><?= icon('clock', 16, '#d97706') ?></div>
                <span class="stat-lbl">Pending</span>
            </div>
            <div class="stat-num" style="color:#d97706;"><?= $pending ?></div>
            <div class="stat-hint">Awaiting review</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#dcfce7;"><?= icon('check-circle', 16, '#16a34a') ?></div>
                <span class="stat-lbl">Approved</span>
            </div>
            <div class="stat-num" style="color:#16a34a;"><?= $approved ?></div>
            <div class="stat-hint">Ready to use</div>
        </div>
        <?php if ($unclaimedCount > 0): ?>
            <div class="stat-card" style="border-color:rgba(251,146,60,.25);">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:#fff7ed;"><?= icon('ticket', 16, '#ea580c') ?></div>
                    <span class="stat-lbl">No-show</span>
                </div>
                <div class="stat-num" style="color:#ea580c;"><?= $unclaimedCount ?></div>
                <div class="stat-hint" style="color:#fb923c;">Slot<?= $unclaimedCount > 1 ? 's' : '' ?> missed</div>
            </div>
        <?php elseif ($claimedCount > 0): ?>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:#ede9fe;"><?= icon('check-double', 16, '#7c3aed') ?></div>
                    <span class="stat-lbl">Claimed</span>
                </div>
                <div class="stat-num" style="color:#7c3aed;"><?= $claimedCount ?></div>
                <div class="stat-hint">Tickets used</div>
            </div>
        <?php else: ?>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon" style="background:#fee2e2;"><?= icon('ban', 16, '#dc2626') ?></div>
                    <span class="stat-lbl">Declined</span>
                </div>
                <div class="stat-num" style="color:#dc2626;"><?= $declined ?></div>
                <div class="stat-hint">Not approved</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Calendar + Quick actions + Recent bookings -->
    <div class="grid-main fade-up-3">
        <div class="card card-p-lg">
            <div class="card-head" style="flex-wrap:wrap;gap:10px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#eef2ff;"><?= icon('calendar-days', 16, 'var(--indigo)') ?></div>
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

        <div class="side-col">
            <!-- Quick actions -->
            <div class="card card-p">
                <div class="section-lbl">Quick Actions</div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <a href="<?= base_url('/reservation') ?>" class="qa-link">
                        <div class="qa-icon" style="background:#eef2ff;"><?= icon('plus', 16, 'var(--indigo)') ?></div>
                        New Reservation
                        <span class="qa-chev"><?= icon('chevron-right', 14, 'currentColor') ?></span>
                    </a>
                    <a href="<?= base_url('/reservation-list') ?>" class="qa-link">
                        <div class="qa-icon" style="background:#ede9fe;"><?= icon('calendar', 16, '#7c3aed') ?></div>
                        My Reservations
                        <?php if ($pending > 0): ?>
                            <span style="margin-left:auto;background:#fef3c7;color:#92400e;font-size:9px;font-weight:700;padding:1px 6px;border-radius:999px;"><?= $pending ?></span>
                        <?php else: ?>
                            <span class="qa-chev"><?= icon('chevron-right', 14, 'currentColor') ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= base_url('/books') ?>" class="qa-link">
                        <div class="qa-icon" style="background:#fef3c7;"><?= icon('book-open', 16, '#d97706') ?></div>
                        Browse Library
                        <span class="qa-chev"><?= icon('chevron-right', 14, 'currentColor') ?></span>
                    </a>
                    <a href="<?= base_url('/profile') ?>" class="qa-link">
                        <div class="qa-icon" style="background:#f3e8ff;"><?= icon('user', 16, '#9333ea') ?></div>
                        View Profile
                        <span class="qa-chev"><?= icon('chevron-right', 14, 'currentColor') ?></span>
                    </a>
                </div>
            </div>

            <!-- Recent bookings -->
            <div class="card card-p" style="flex:1;">
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
                    <div style="text-align:center;padding:22px 12px;">
                        <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('calendar-x', 28, 'currentColor') ?></div>
                        <p style="font-size:12px;color:var(--text-sub);">No bookings yet</p>
                        <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:9px;font-size:11px;font-weight:700;color:var(--indigo);text-decoration:none;touch-action:manipulation;">
                            <?= icon('plus', 12, 'var(--indigo)') ?> Make your first reservation
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- How-to + Status guide (shown conditionally) -->
    <?php if (empty($reservations) || $unclaimedCount > 0 || $pending > 0): ?>
        <div class="grid-main" style="margin-bottom:16px;">
            <div class="card card-p">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <div class="card-icon" style="background:#eef2ff;"><?= icon('list-check', 16, 'var(--indigo)') ?></div>
                    <div>
                        <div class="card-title">How to Reserve</div>
                        <div class="card-sub">Step-by-step guide</div>
                    </div>
                </div>
                <?php $step = 1;
                foreach ([
                    ['Click "New Reservation"', 'Choose a resource, pick your date and time, and describe your purpose.'],
                    ['Wait for approval',        'An SK officer will review your request, usually within 24 hours.'],
                    ['Download your e-ticket',   'Once approved, open My Reservations and download your QR code.'],
                    ['Scan at the entrance',      'Show your e-ticket to be scanned when you arrive.'],
                    ['Be on time',                "Slots expire if you don't show up. Cancel in advance if plans change."],
                ] as [$title, $body]): ?>
                    <div class="how-step">
                        <div class="step-num"><?= $step++ ?></div>
                        <div>
                            <p style="font-weight:600;font-size:12.5px;color:var(--text);letter-spacing:-.1px;"><?= $title ?></p>
                            <p style="font-size:11px;color:var(--text-sub);margin-top:2px;"><?= $body ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card card-p">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <div class="card-icon" style="background:#eef2ff;"><?= icon('info', 16, 'var(--indigo)') ?></div>
                    <div>
                        <div class="card-title">Status Reference</div>
                        <div class="card-sub">What each status means</div>
                    </div>
                </div>
                <?php foreach ([
                    ['pending',   'clock',        '#fef3c7', '#92400e', '#d97706', 'Pending',  'Waiting for SK officer review.'],
                    ['approved',  'check-circle', '#dcfce7', '#166534', '#16a34a', 'Approved', 'Confirmed. Get your e-ticket.'],
                    ['claimed',   'check-double', '#ede9fe', '#5b21b6', '#7c3aed', 'Claimed',  'E-ticket scanned. Slot used.'],
                    ['unclaimed', 'ticket',       '#fff7ed', '#c2410c', '#ea580c', 'No-show',  "Approved but you didn't attend."],
                    ['declined',  'ban',          '#fee2e2', '#991b1b', '#dc2626', 'Declined', 'Not approved. Try another time.'],
                    ['expired',   'hourglass',    '#f1f5f9', '#475569', '#64748b', 'Expired',  'Date passed before approval.'],
                ] as [$key, $ico, $bg, $fg, $ic, $label, $desc]): ?>
                    <div class="status-guide-row">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:7px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;flex-shrink:0;min-width:74px;justify-content:center;background:<?= $bg ?>;color:<?= $fg ?>;">
                            <?= icon($ico, 8, $ic) ?><?= $label ?>
                        </span>
                        <p style="font-size:11px;color:var(--text-muted);"><?= $desc ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Library section -->
<div class="grid-lib fade-up-4">
    <div style="display:flex;flex-direction:column;gap:14px;">

        <!-- Library banner -->
        <div class="lib-banner">
            <div style="position:relative;z-index:1;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px;flex-wrap:wrap;">
                    <div>
                        <div class="lib-eyebrow">Community Library</div>
                        <div class="lib-title"><?= $availableCount ?></div>
                        <div class="lib-sub">available · <?= $totalBooks ?> total titles</div>
                    </div>
                    <a href="<?= base_url('/books') ?>" class="lib-browse">
                        <?= icon('book-open', 13, 'white') ?> Browse Library
                    </a>
                </div>
                <div class="lib-stats">
                    <div class="lib-stat">
                        <div class="lib-stat-icon"><?= icon('bookmark', 13, '#a5b4fc') ?></div>
                        <div>
                            <div class="lib-stat-lbl">My Borrows</div>
                            <div class="lib-stat-val"><?= count($myBorrowings) ?></div>
                        </div>
                    </div>
                    <div class="lib-stat">
                        <div class="lib-stat-icon"><?= icon('hourglass', 13, '#fcd34d') ?></div>
                        <div>
                            <div class="lib-stat-lbl">Pending</div>
                            <div class="lib-stat-val"><?= count(array_filter($myBorrowings, fn($b) => ($b['status'] ?? '') === 'pending')) ?></div>
                        </div>
                    </div>
                    <div class="lib-stat">
                        <div class="lib-stat-icon"><?= icon('check-circle', 13, '#7dd3fc') ?></div>
                        <div>
                            <div class="lib-stat-lbl">Active</div>
                            <div class="lib-stat-val"><?= count(array_filter($myBorrowings, fn($b) => ($b['status'] ?? '') === 'approved')) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Book Finder -->
        <div class="card card-p">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="card-icon" style="background:#ede9fe;"><?= icon('sparkles', 16, '#7c3aed') ?></div>
                <div>
                    <div class="card-title">AI Book Finder</div>
                    <div class="card-sub">Describe what you want to read</div>
                </div>
            </div>
            <div class="rag-wrap">
                <span class="rag-icon-pos"><?= icon('search', 13, 'var(--text-sub)') ?></span>
                <input type="text" id="ragInput" class="search-input"
                    placeholder="e.g. Filipino history, funny stories…"
                    autocomplete="off" autocorrect="off" spellcheck="false"
                    onkeydown="if(event.key==='Enter') doRagSearch()">
            </div>
            <div id="ragSkel" style="display:none;margin-top:.5rem;">
                <div class="shimmer" style="width:90%;"></div>
                <div class="shimmer" style="width:70%;"></div>
                <div class="shimmer" style="width:52%;"></div>
            </div>
            <div class="ai-result-box" id="ragResult">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                    <?= icon('robot', 14, 'var(--indigo)') ?>
                    <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#3730a3;">Librarian Suggestion</p>
                </div>
                <p style="font-size:12px;color:#312e81;line-height:1.6;" id="ragText"></p>
                <div id="ragBooks"></div>
            </div>
            <div id="ragErr" style="display:none;margin-top:5px;font-size:11px;color:#dc2626;font-weight:500;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:11px;">
                <button onclick="doRagSearch()" id="ragBtn" class="find-btn">
                    <?= icon('sparkles', 13, 'white') ?> Find Books
                </button>
                <a href="<?= base_url('/books') ?>" class="link-sm">Full library →</a>
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:14px;">

        <!-- Books catalog -->
        <div class="card card-p" style="flex:1;">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:var(--indigo-light);">
                        <?= icon('book-open', 16, 'var(--indigo)') ?>
                    </div>
                    <div>
                        <div class="card-title">Available Now</div>
                        <div class="card-sub">Books you can borrow today</div>
                    </div>
                </div>
                <a href="<?= base_url('/books') ?>" class="link-sm">All →</a>
            </div>

            <?php if (!empty($featuredBooks)): ?>
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <?php foreach (array_slice($featuredBooks, 0, 6) as $book):
                        $avail = (int)($book['available_copies'] ?? 0);
                        $pillStyle = $avail === 0
                            ? 'background:#fee2e2;color:#991b1b;'
                            : ($avail <= 1
                                ? 'background:#fef3c7;color:#92400e;'
                                : 'background:#dcfce7;color:#166634;');
                        $pillText = $avail === 0 ? 'Out' : ($avail <= 1 ? '1 left' : $avail . ' left');
                    ?>
                        <a href="<?= base_url('/books') ?>"
                           style="display:flex;align-items:center;gap:10px;padding:7px 6px;border-radius:10px;text-decoration:none;color:inherit;transition:background .15s;min-width:0;">
                            <div class="book-letter"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></div>
                            <div style="flex:1;min-width:0;">
                                <div class="book-title"><?= esc($book['title']) ?></div>
                                <div class="book-author"><?= esc($book['author'] ?? 'Unknown') ?></div>
                            </div>
                            <span style="font-size:.6rem;font-weight:800;padding:2px 8px;border-radius:999px;flex-shrink:0;white-space:nowrap;<?= $pillStyle ?>">
                                <?= $pillText ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php if (count($featuredBooks) > 6): ?>
                    <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--border-subtle);text-align:center;">
                        <a href="<?= base_url('/books') ?>" class="link-sm">
                            +<?= count($featuredBooks) - 6 ?> more →
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="text-align:center;padding:32px 12px;">
                    <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);">
                        <?= icon('book-open', 28, 'currentColor') ?>
                    </div>
                    <p style="font-size:.78rem;color:var(--text-sub);font-weight:600;">No books available</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- My borrows — only shown if there are active ones -->
        <?php
        $activeBorrows = array_slice(
            array_values(array_filter($myBorrowings, fn($b) => in_array($b['status'] ?? '', ['approved', 'pending']))),
            0, 4
        );
        if (!empty($activeBorrows)): ?>
            <div class="card card-p">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#dcfce7;">
                            <?= icon('bookmark', 16, '#16a34a') ?>
                        </div>
                        <div>
                            <div class="card-title">My Active Borrows</div>
                            <div class="card-sub">Currently checked out</div>
                        </div>
                    </div>
                    <a href="<?= base_url('/books') ?>#mine" class="link-sm">All →</a>
                </div>
                <div style="display:flex;flex-direction:column;gap:7px;">
                    <?php foreach ($activeBorrows as $borrow):
                        $bs  = strtolower($borrow['status'] ?? 'pending');
                        $due = !empty($borrow['due_date']) ? strtotime($borrow['due_date']) : null;
                        $overdue = $due && $due < time();
                        $dueSoon = $due && !$overdue && $due < time() + 3 * 86400;
                    ?>
                        <div class="borrow-row">
                            <div class="book-letter" style="width:30px;height:30px;font-size:.7rem;">
                                <?= mb_strtoupper(mb_substr($borrow['title'] ?? 'B', 0, 1)) ?>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-weight:600;font-size:.8rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    <?= esc($borrow['title'] ?? 'Unknown Book') ?>
                                </p>
                                <?php if ($due && $bs === 'approved'): ?>
                                    <p style="font-size:.68rem;color:<?= $overdue ? '#ef4444' : ($dueSoon ? '#d97706' : 'var(--text-sub)') ?>;font-family:var(--mono);">
                                        <?= $overdue ? 'Overdue · ' : ($dueSoon ? 'Due soon · ' : '') ?><?= date('M j, Y', $due) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <span class="tag tag-<?= $overdue ? 'declined' : ($dueSoon ? 'pending' : $bs) ?>">
                                <?= $overdue ? 'Overdue' : ($dueSoon ? 'Due Soon' : ucfirst($bs)) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
</main><!-- /.main-area -->

<!-- ═══════════════════════════════════════════════════════════════
     DATE MODAL
════════════════════════════════════════════════════════════════ -->
<div id="dateModal" class="modal-back" onclick="handleModalBack(event)">
    <div class="modal-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div>
                <h3 style="font-size:16px;font-weight:700;letter-spacing:-.2px;" id="modalDateTitle"></h3>
                <p style="font-size:11px;color:var(--text-sub);margin-top:2px;" id="modalDateSub"></p>
            </div>
            <button onclick="closeDateModal()" style="width:36px;height:36px;border-radius:9px;background:var(--input-bg);border:none;color:var(--text-sub);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;touch-action:manipulation;">
                <?= icon('x', 13, 'currentColor') ?>
            </button>
        </div>
        <div id="modalList"></div>
        <div class="hidden" id="modalEmpty" style="text-align:center;padding:24px 12px;">
            <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('calendar-x', 26, 'currentColor') ?></div>
            <p style="font-size:12px;color:var(--text-sub);">No reservations for this date.</p>
        </div>
        <button onclick="closeDateModal()" style="margin-top:16px;width:100%;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:600;color:var(--text-muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;font-family:var(--font);touch-action:manipulation;">Close</button>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     LOGIN TOAST
════════════════════════════════════════════════════════════════ -->
<div id="loginToast" class="login-toast">
    <div class="toast-icon" id="toastIcon"></div>
    <div style="flex:1;min-width:0;">
        <p style="font-weight:700;font-size:12px;line-height:1.3;color:white;" id="toastTitle"></p>
        <p style="font-size:10px;color:rgba(255,255,255,.6);margin-top:2px;" id="toastBody"></p>
    </div>
    <button class="toast-close" onclick="dismissToast()"><?= icon('x', 10, 'white') ?></button>
</div>

<script>
/* ── Data from PHP ─────────────────────────────────────────────── */
const NOTIF_KEY   = 'notified_ids_<?= session()->get('user_id') ?>';
const reservations = <?= json_encode($reservations ?? []) ?>;
const allResData   = <?= json_encode($allReservations ?? []) ?>;
const approvedRes  = reservations.filter(r => r.status === 'approved' && !r.claimed);
let   notifications = [];

/* ── Notification helpers ──────────────────────────────────────── */
const getSeenIds  = () => { try { return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]'); } catch(e) { return []; } };
const saveSeenIds = ids => localStorage.setItem(NOTIF_KEY, JSON.stringify(ids));

function loadNotifications() {
    const seen = getSeenIds();
    notifications = reservations.filter(r => r.status === 'approved').map(r => ({
        id:    parseInt(r.id),
        title: 'Reservation Approved',
        msg:   `${r.resource_name || 'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
        time:  r.updated_at || r.created_at || new Date().toISOString(),
        read:  seen.includes(parseInt(r.id))
    }));
    updateBadge();
    renderNotifs();
}

function markAllRead() {
    saveSeenIds([...new Set([...getSeenIds(), ...notifications.map(n => n.id)])]);
    notifications.forEach(n => n.read = true);
    updateBadge();
    renderNotifs();
}

function markRead(id) {
    const ids = getSeenIds();
    if (!ids.includes(id)) saveSeenIds([...ids, id]);
    const n = notifications.find(n => n.id === id);
    if (n) { n.read = true; updateBadge(); renderNotifs(); }
}

function updateBadge() {
    const badge  = document.getElementById('notifBadge');
    const unread = notifications.filter(n => !n.read).length;
    badge.style.display = unread > 0 ? 'block' : 'none';
    badge.textContent   = unread > 9 ? '9+' : unread;
}

function renderNotifs() {
    const list = document.getElementById('notifList');
    if (!notifications.length) {
        list.innerHTML = `<div style="text-align:center;padding:24px 16px;"><p style="font-size:12px;color:var(--text-sub);">All caught up!</p></div>`;
        return;
    }
    list.innerHTML = notifications
        .sort((a,b) => new Date(b.time) - new Date(a.time))
        .map(n => `
        <div class="notif-item ${!n.read ? 'unread' : ''}" onclick="markRead(${n.id})">
            <div style="display:flex;align-items:flex-start;gap:9px;">
                <div style="width:30px;height:30px;background:var(--indigo-light);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-weight:700;font-size:12px;color:var(--text);">${n.title}</p>
                    <p style="font-size:10px;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                    <p style="font-size:9px;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p>
                </div>
                ${!n.read ? '<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:3px;"></span>' : ''}
            </div>
        </div>`).join('');
}

function toggleNotifications() {
    document.getElementById('notifDD').classList.toggle('show');
}

document.addEventListener('click', e => {
    const dd   = document.getElementById('notifDD');
    const bell = document.querySelector('.notif-bell');
    if (!bell.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('show');
});

const timeAgo = t => {
    const s = Math.floor((Date.now() - new Date(t)) / 1000);
    if (s < 60)    return 'Just now';
    if (s < 3600)  return `${Math.floor(s/60)}m ago`;
    if (s < 86400) return `${Math.floor(s/3600)}h ago`;
    return `${Math.floor(s/86400)}d ago`;
};

/* ── Date modal ────────────────────────────────────────────────── */
function openDateModal(date, items) {
    const d = new Date(date + 'T00:00:00');
    document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
    document.getElementById('modalDateSub').textContent   = items.length ? `${items.length} reservation${items.length>1?'s':''}` : '';
    const list  = document.getElementById('modalList');
    const empty = document.getElementById('modalEmpty');
    list.innerHTML = '';
    if (items.length) {
        empty.classList.add('hidden');
        const cmap = {approved:'#dcfce7|#166534',pending:'#fef3c7|#92400e',declined:'#fee2e2|#991b1b',canceled:'#fee2e2|#991b1b',claimed:'#ede9fe|#5b21b6'};
        items.sort((a,b) => (a.start_time||'').localeCompare(b.start_time||'')).forEach(r => {
            const isClaimed = r.claimed == 1;
            const s  = isClaimed ? 'claimed' : (r.status||'pending').toLowerCase();
            const [cbg,cfg] = (cmap[s]||'#f1f5f9|#475569').split('|');
            const t1 = r.start_time ? r.start_time.substring(0,5) : 'All day';
            const t2 = r.end_time   ? ` – ${r.end_time.substring(0,5)}` : '';
            const row = document.createElement('div');
            row.className = 'date-row';
            row.innerHTML = `
            <div style="width:32px;height:32px;background:var(--input-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border);">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-sub)" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-weight:600;font-size:13px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Reserved'}</p>
                <p style="font-size:10px;color:var(--text-sub);margin-top:1px;font-family:var(--mono);">${t1}${t2}</p>
            </div>
            <span style="display:inline-flex;padding:2px 8px;border-radius:999px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:${cbg};color:${cfg};flex-shrink:0;">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
            list.appendChild(row);
        });
    } else {
        empty.classList.remove('hidden');
    }
    document.getElementById('dateModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDateModal() {
    document.getElementById('dateModal').classList.remove('show');
    document.body.style.overflow = '';
}

function handleModalBack(e) { if (e.target.classList.contains('modal-back')) closeDateModal(); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

/* ── Countdown timer ───────────────────────────────────────────── */
function initTimer() {
    const banner  = document.getElementById('timerBanner'),
          titleEl = document.getElementById('timerTitle'),
          subEl   = document.getElementById('timerSub'),
          hEl     = document.getElementById('tdH'),
          mEl     = document.getElementById('tdM'),
          sEl     = document.getElementById('tdS'),
          iconW   = document.getElementById('timerIconWrap'),
          pw      = document.getElementById('timerPW'),
          pf      = document.getElementById('timerPF');

    const icons = {
        urgent:  `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
        warning: `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round"/></svg>`,
        safe:    `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>`,
    };

    function findTarget() {
        const now = Date.now();
        let active = null, upcoming = null;
        approvedRes.forEach(r => {
            if (!r.reservation_date || !r.start_time || !r.end_time) return;
            const start = new Date(r.reservation_date + 'T' + r.start_time).getTime();
            const end   = new Date(r.reservation_date + 'T' + r.end_time).getTime();
            const minsToStart = (start - now) / 60000;
            const minsToEnd   = (end   - now) / 60000;
            if (now >= start && now < end && !active)   active   = {r,start,end,mode:'active',  minsLeft:minsToEnd  };
            if (!upcoming && minsToStart > 0 && minsToStart <= 30) upcoming = {r,start,end,mode:'upcoming',minsLeft:minsToStart};
        });
        return active || upcoming || null;
    }

    function tick() {
        const target = findTarget();
        if (!target) { banner.style.display = 'none'; return; }
        const {r,start,end,mode,minsLeft} = target;
        const now  = Date.now();
        const diff = Math.max(0, (mode === 'active' ? end : start) - now);
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        hEl.textContent = String(h).padStart(2,'0');
        mEl.textContent = String(m).padStart(2,'0');
        sEl.textContent = String(s).padStart(2,'0');
        banner.classList.remove('urgent','warning','safe');
        if (mode === 'active') {
            if (minsLeft <= 10)      { banner.classList.add('urgent');  iconW.innerHTML = icons.urgent;  }
            else if (minsLeft <= 20) { banner.classList.add('warning'); iconW.innerHTML = icons.warning; }
            else                     { banner.classList.add('safe');    iconW.innerHTML = icons.safe;    }
            titleEl.textContent = minsLeft <= 10 ? '⚠ Reservation ends very soon!' : 'Your reservation is active';
            subEl.textContent   = `${r.resource_name||'Resource'} · Ends at ${(r.end_time||'').substring(0,5)}`;
            const pct = Math.min(100, Math.max(0, ((now-start)/(end-start))*100));
            pw.style.display = 'block';
            pf.style.width   = pct.toFixed(1) + '%';
        } else {
            banner.classList.add('safe');
            iconW.innerHTML     = icons.safe;
            titleEl.textContent = 'Your reservation starts soon';
            subEl.textContent   = `${r.resource_name||'Resource'} · Starts at ${(r.start_time||'').substring(0,5)}`;
            pw.style.display    = 'none';
        }
        banner.style.display = 'block';
    }
    tick();
    setInterval(tick, 1000);
}

/* ── Login toast ───────────────────────────────────────────────── */
function showLoginToast() {
    const key = 'toast_<?= session()->get('user_id') ?>_' + new Date().toDateString();
    if (sessionStorage.getItem(key)) return;
    sessionStorage.setItem(key, '1');
    const now = Date.now();
    let td = null;
    approvedRes.forEach(r => {
        if (!r.reservation_date || !r.start_time || !r.end_time) return;
        const start = new Date(r.reservation_date + 'T' + r.start_time).getTime();
        const end   = new Date(r.reservation_date + 'T' + r.end_time).getTime();
        const minsToStart = (start - now) / 60000;
        const today  = new Date().toDateString();
        const resDay = new Date(r.reservation_date + 'T00:00:00').toDateString();
        if (now >= start && now < end && !td)
            td = {icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8"><polygon points="5 3 19 12 5 21 5 3"/></svg>',bg:'rgba(37,99,235,.2)',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${(r.end_time||'').substring(0,5)}`};
        if (!td && resDay === today && minsToStart > 0 && minsToStart <= 120)
            td = {icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',bg:'rgba(217,119,6,.2)',title:`In ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${(r.start_time||'').substring(0,5)} – ${(r.end_time||'').substring(0,5)}`};
        if (!td && resDay === today) {
            const fmt = t => { const [h,m] = t.split(':'); const hr=+h; return `${hr%12||12}:${m} ${hr<12?'AM':'PM'}`; };
            td = {icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>',bg:'rgba(37,99,235,.2)',title:'Reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`};
        }
    });
    if (!td) return;
    const toast = document.getElementById('loginToast');
    document.getElementById('toastIcon').innerHTML   = td.icon;
    document.getElementById('toastIcon').style.background = td.bg;
    document.getElementById('toastTitle').textContent = td.title;
    document.getElementById('toastBody').textContent  = td.body;
    setTimeout(() => toast.classList.add('show'),    900);
    setTimeout(() => toast.classList.remove('show'), 7500);
}

function dismissToast() { document.getElementById('loginToast').classList.remove('show'); }

/* ── AI Book Finder ────────────────────────────────────────────── */
async function doRagSearch() {
    const query = document.getElementById('ragInput').value.trim();
    if (query.length < 2) return;
    const skel = document.getElementById('ragSkel'),
          res  = document.getElementById('ragResult'),
          err  = document.getElementById('ragErr'),
          btn  = document.getElementById('ragBtn');
    res.classList.remove('show');
    err.style.display = 'none';
    skel.style.display = 'block';
    btn.disabled = true;
    try {
        const r = await fetch('/rag/suggest', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({query})
        });
        const d = await r.json();
        skel.style.display = 'none';
        btn.disabled = false;
        if (d.message && !d.suggestion) { err.textContent = d.message; err.style.display = 'block'; return; }
        if (d.error   && !d.books)      { err.textContent = d.error;   err.style.display = 'block'; return; }
        document.getElementById('ragText').textContent = d.suggestion || '';
        const booksRow = document.getElementById('ragBooks');
        booksRow.innerHTML = '';
        (d.books || []).slice(0, 4).forEach(b => {
            const avail = (b.available_copies || 0) > 0;
            const chip = document.createElement('a');
            chip.href = '/books';
            chip.style.cssText = `display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10px;font-weight:600;border:1px solid;transition:all .15s;text-decoration:none;font-family:var(--font);max-width:100%;overflow:hidden;${avail?'background:var(--card);border-color:var(--indigo-border);color:var(--indigo);':'background:var(--input-bg);border-color:var(--border);color:var(--text-sub);'}`;
            const titleSpan = document.createElement('span');
            titleSpan.style.cssText = 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
            titleSpan.textContent = b.title + (!avail ? ' (out)' : '');
            chip.innerHTML = `<svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0;"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round"/></svg>`;
            chip.appendChild(titleSpan);
            booksRow.appendChild(chip);
        });
        res.classList.add('show');
    } catch(e) {
        skel.style.display = 'none';
        btn.disabled = false;
        err.textContent = 'Network error. Try again.';
        err.style.display = 'block';
    }
}

/* ── DOMContentLoaded ──────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    /* Dark mode is already applied by layout.php — just remove pre-init class */
    document.documentElement.classList.remove('dark-pre');

    if ('Notification' in window) Notification.requestPermission();
    loadNotifications();
    initTimer();
    showLoginToast();

    /* Build calendar event map */
    const byDate = {};
    allResData.forEach(r => {
        if (!r.reservation_date) return;
        if (!byDate[r.reservation_date]) byDate[r.reservation_date] = [];
        byDate[r.reservation_date].push(r);
    });

    const colorMap = {approved:'#10b981',pending:'#fbbf24',declined:'#f87171',canceled:'#f87171',claimed:'#a855f7'};
    const events = allResData.filter(r => r.reservation_date).map(r => {
        const isClaimed = r.claimed == 1;
        const s = isClaimed ? 'claimed' : (r.status||'pending').toLowerCase();
        const d = r.reservation_date.trim();
        return {
            title: r.resource_name || 'Reservation',
            start: d + (r.start_time ? 'T' + r.start_time.substring(0,8) : ''),
            end:   d + (r.end_time   ? 'T' + r.end_time.substring(0,8)   : ''),
            allDay: !r.start_time,
            backgroundColor: colorMap[s] || '#94a3b8',
            borderColor: 'transparent',
            textColor: '#fff',
            extendedProps: {status: s}
        };
    });

    const w         = window.innerWidth;
    const calView   = w < 480 ? 'listWeek' : 'dayGridMonth';
    const calHeight = w < 640 ? 'auto' : 360;

    const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView:   calView,
        headerToolbar: {left:'prev,next', center:'title', right:'today'},
        events,
        height:        calHeight,
        eventDisplay:  'block',
        eventMaxStack: 2,
        dateClick:  info => openDateModal(info.dateStr, byDate[info.dateStr] || []),
        eventClick: info => { const d = info.event.startStr.split('T')[0]; openDateModal(d, byDate[d] || []); },
        dayCellDidMount: info => {
            const d     = info.date.toISOString().split('T')[0];
            const items = byDate[d];
            if (items && items.length) {
                const badge = document.createElement('div');
                badge.style.cssText = 'font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;margin-bottom:1px;font-family:var(--mono);';
                badge.textContent   = items.length;
                info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);
            }
        }
    });
    cal.render();
});
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>