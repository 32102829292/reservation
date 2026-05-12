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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        (function(){try{if(localStorage.getItem('theme')==='dark')document.documentElement.classList.add('dark-pre');}catch(e){}})();
    </script>
    <style>
        /* ─── Base ─────────────────────────────────────────── */
        body { display:flex; height:100vh; height:100dvh; overflow:hidden; }
        html.dark-pre body, html.dark-pre .l-sidebar__inner, html.dark-pre .l-mobile-nav { background:#060e1e; }

        /* ─── Reserve Button ────────────────────────────────── */
        .reserve-btn {
            display:none; align-items:center; gap:7px; padding:9px 18px;
            background:var(--indigo); color:#fff; border-radius:var(--r-sm);
            font-family:var(--font); font-size:.85rem; font-weight:700; border:none;
            cursor:pointer; text-decoration:none; transition:all var(--ease);
            box-shadow:0 4px 12px rgba(55,48,163,.28); touch-action:manipulation;
        }
        @media(min-width:480px){ .reserve-btn{display:flex;} }
        .reserve-btn:hover { background:#312e81; transform:translateY(-1px); box-shadow:0 6px 18px rgba(55,48,163,.35); }

        /* ─── Notifications ─────────────────────────────────── */
        .notif-bell { position:relative; }
        .notif-badge {
            position:absolute; top:-5px; right:-5px;
            background:#ef4444; color:white; font-family:var(--font); font-size:.55rem;
            font-weight:700; padding:2px 5px; border-radius:999px; min-width:17px;
            text-align:center; border:2px solid var(--bg); line-height:1.3; pointer-events:none;
        }
        .notif-dd {
            position:fixed; top:80px; right:20px; width:320px; background:var(--card);
            border-radius:var(--r-xl); box-shadow:var(--shadow-lg),0 0 0 1px rgba(99,102,241,.09);
            z-index:200; display:none; overflow:hidden;
        }
        .notif-dd.show { display:block; animation:l-fade-in .15s ease; }
        .notif-item { padding:.85rem 1.1rem; border-bottom:1px solid var(--border-subtle); transition:background .15s; cursor:pointer; }
        .notif-item:hover { background:var(--input-bg); }
        .notif-item.unread { background:var(--indigo-light); }
        .notif-item:last-child { border-bottom:none; }
        @media(max-width:479px){ .notif-dd{left:12px;right:12px;width:auto;top:72px;} }

        /* ─── Hero Banner ────────────────────────────────────── */
        .hero-banner {
            background: linear-gradient(120deg, #3730a3 0%, #4338ca 40%, #312e81 100%);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(55,48,163,.3);
        }
        @media(max-width:479px){ .hero-banner{padding:20px 18px;border-radius:16px;} }
        .hero-banner::before {
            content:''; position:absolute; top:-60px; right:-40px;
            width:260px; height:260px; background:rgba(255,255,255,.05); border-radius:50%; pointer-events:none;
        }
        .hero-banner::after {
            content:''; position:absolute; bottom:-80px; right:100px;
            width:200px; height:200px; background:rgba(255,255,255,.04); border-radius:50%; pointer-events:none;
        }
        .hero-inner { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        .hero-greeting { font-family:var(--font); font-size:.7rem; font-weight:600; color:rgba(255,255,255,.55); letter-spacing:.08em; text-transform:uppercase; margin-bottom:4px; }
        .hero-name { font-family:var(--font); font-size:1.8rem; font-weight:800; color:#fff; letter-spacing:-.04em; line-height:1.1; display:flex; align-items:center; gap:10px; }
        @media(max-width:479px){ .hero-name{font-size:1.4rem;} }
        .hero-name-icon { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; background:rgba(255,255,255,.15); border-radius:10px; flex-shrink:0; }
        .hero-date { font-family:var(--font); font-size:.78rem; color:rgba(255,255,255,.5); margin-top:5px; }
        .hero-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        @media(max-width:479px){ .hero-actions{width:100%;} }
        .hero-btn {
            display:inline-flex; align-items:center; gap:7px; padding:11px 20px;
            background:rgba(255,255,255,.15); color:#fff; border-radius:12px;
            font-family:var(--font); font-size:.82rem; font-weight:700;
            border:1.5px solid rgba(255,255,255,.25); text-decoration:none;
            transition:all .2s; touch-action:manipulation;
        }
        @media(max-width:479px){ .hero-btn{flex:1;justify-content:center;padding:10px 14px;font-size:.78rem;} }
        .hero-btn:hover { background:rgba(255,255,255,.25); transform:translateY(-1px); }
        .hero-btn.solid { background:#fff; color:#3730a3; border-color:transparent; }
        .hero-btn.solid:hover { background:#eef2ff; }

        /* ─── Stat Pills ─────────────────────────────────────── */
        .stat-pill-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        @media(max-width:700px){ .stat-pill-row{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;} }
        .stat-pill {
            background: var(--card); border: 1px solid var(--border); border-radius: 16px;
            padding: 18px 20px; display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow-sm); transition: transform var(--ease), box-shadow var(--ease); cursor: default;
        }
        @media(max-width:400px){ .stat-pill{padding:14px 13px;gap:10px;} }
        .stat-pill:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
        .sp-icon { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        @media(max-width:400px){ .sp-icon{width:36px;height:36px;border-radius:10px;} }
        .sp-icon svg { width:20px; height:20px; flex-shrink:0; }
        .sp-lbl { font-family:var(--font); font-size:.65rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--text-sub); margin-bottom:2px; }
        .sp-val { font-family:var(--mono); font-size:1.7rem; font-weight:800; color:var(--text); line-height:1; letter-spacing:-.04em; }
        @media(max-width:400px){ .sp-val{font-size:1.4rem;} }
        .sp-hint { font-family:var(--font); font-size:.68rem; color:var(--text-sub); margin-top:3px; }

        /* ─── Next Action ────────────────────────────────────── */
        .next-card {
            display:flex; align-items:flex-start; gap:14px;
            border-radius:14px; padding:16px 18px; border:1px solid;
            margin-bottom:18px; animation:l-slide-up .4s ease both;
        }
        @media(max-width:479px){ .next-card{padding:14px 14px;gap:11px;border-radius:12px;} }
        .next-icon-wrap { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .next-eyebrow { font-family:var(--font); font-size:.6rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; margin-bottom:4px; }
        .next-msg { font-family:var(--font); font-size:.83rem; color:var(--text-muted); line-height:1.6; }
        .next-cta { display:inline-flex; align-items:center; gap:6px; margin-top:10px; padding:9px 16px; border-radius:9px; font-family:var(--font); font-size:.75rem; font-weight:700; color:#fff; text-decoration:none; transition:opacity var(--ease); }
        .next-cta:hover { opacity:.85; }

        /* ─── Dashboard Grid ─────────────────────────────────── */
        .dash-grid {
            display: grid;
            grid-template-columns: minmax(0,1.55fr) minmax(0,1fr);
            gap: 16px;
            margin-bottom: 18px;
        }
        @media(max-width:860px){ .dash-grid{grid-template-columns:1fr;} }
        .side-col { display:flex; flex-direction:column; gap:14px; }
        @media(max-width:860px){ .side-col{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));} }
        @media(max-width:560px){ .side-col{grid-template-columns:1fr;} }

        /* ─── Card Helpers ───────────────────────────────────── */
        .card-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px; }
        .card-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .card-icon svg { width:16px; height:16px; flex-shrink:0; }
        .card-title { font-family:var(--font); font-size:.9rem; font-weight:700; color:var(--text); letter-spacing:-.01em; }
        .card-sub { font-family:var(--font); font-size:.7rem; color:var(--text-sub); margin-top:2px; }
        .section-lbl { font-family:var(--font); font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--text-sub); margin-bottom:12px; display:flex; align-items:center; gap:6px; }
        .section-lbl::before { content:''; width:3px; height:13px; border-radius:2px; background:var(--indigo); flex-shrink:0; }
        .link-sm { font-family:var(--font); font-size:.65rem; font-weight:700; color:var(--indigo); text-decoration:none; letter-spacing:.05em; text-transform:uppercase; transition:opacity .15s; white-space:nowrap; }
        .link-sm:hover { opacity:.7; }

        /* ─── Quick Actions ──────────────────────────────────── */
        .qa-link {
            display:flex; align-items:center; gap:11px; padding:12px 14px;
            border-radius:12px; border:1px solid var(--border); background:var(--card);
            text-decoration:none; color:var(--text-muted); font-family:var(--font);
            font-size:.83rem; font-weight:600; transition:all var(--ease);
        }
        .qa-link:hover { border-color:var(--indigo); background:var(--indigo-light); color:var(--indigo); }
        @media(pointer:fine){ .qa-link:hover{ transform:translateX(2px); } }
        .qa-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .qa-icon svg { width:15px; height:15px; flex-shrink:0; }
        .qa-chev { margin-left:auto; color:var(--text-faint); flex-shrink:0; }
        .qa-chev svg { width:13px; height:13px; flex-shrink:0; }
        .qa-link:hover .qa-chev { color:var(--indigo); }

        /* ─── Recent Bookings ────────────────────────────────── */
        .bk-row { display:flex; align-items:center; gap:11px; padding:9px 8px; border-radius:11px; text-decoration:none; color:inherit; transition:background var(--ease); }
        .bk-row:hover { background:var(--indigo-light); }
        .bk-date { width:38px; height:38px; background:var(--input-bg); border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0; border:1px solid var(--border-subtle); }
        .bk-month { font-family:var(--font); font-size:.52rem; font-weight:700; text-transform:uppercase; color:var(--text-sub); }
        .bk-day { font-family:var(--mono); font-size:.92rem; font-weight:800; color:var(--text); line-height:1; }
        .bk-name { font-family:var(--font); font-size:.82rem; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .bk-time { font-family:var(--mono); font-size:.67rem; color:var(--text-sub); margin-top:1px; }

        /* ─── Upcoming Pill ──────────────────────────────────── */
        .upcoming-pill {
            background:var(--indigo-light); border:1px solid var(--indigo-border);
            border-radius:14px; padding:14px 16px;
            display:flex; align-items:center; gap:14px;
            margin-bottom:18px; animation:l-slide-up .4s ease both; flex-wrap:wrap;
        }
        .up-icon { width:38px; height:38px; background:var(--indigo); border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 10px rgba(55,48,163,.28); }
        .up-icon svg { width:16px; height:16px; flex-shrink:0; }
        .up-eyebrow { font-family:var(--font); font-size:.58rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--indigo); margin-bottom:2px; }
        .up-name { font-family:var(--font); font-size:.88rem; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:200px; }
        .up-time { font-family:var(--mono); font-size:.7rem; color:#4338ca; margin-top:1px; }
        .up-btn { margin-left:auto; font-family:var(--font); font-size:.72rem; font-weight:700; color:var(--indigo); background:var(--card); border:1px solid var(--indigo-border); border-radius:8px; padding:8px 14px; text-decoration:none; white-space:nowrap; transition:all var(--ease); }
        .up-btn:hover { background:var(--indigo); color:white; }
        @media(max-width:479px){ .up-name{max-width:100%;} .up-btn{margin-left:0;width:100%;text-align:center;display:block;} }

        /* ─── Timer Banner ───────────────────────────────────── */
        .timer-banner { display:none; border-radius:14px; padding:14px 18px; margin-bottom:16px; border:1px solid; animation:l-slide-up .35s cubic-bezier(.34,1.56,.64,1) both; }
        .timer-banner.urgent  { background:#fff7ed; border-color:#fed7aa; color:#9a3412; }
        .timer-banner.warning { background:#fefce8; border-color:#fde68a; color:#854d0e; }
        .timer-banner.safe    { background:var(--indigo-light); border-color:var(--indigo-border); color:#312e81; }
        body.dark .timer-banner.safe    { background:rgba(55,48,163,.15); border-color:rgba(55,48,163,.3); color:#a5b4fc; }
        body.dark .timer-banner.warning { background:rgba(180,83,9,.2); border-color:rgba(180,83,9,.35); color:#fcd34d; }
        body.dark .timer-banner.urgent  { background:rgba(154,52,18,.2); border-color:rgba(154,52,18,.35); color:#fb923c; }
        .timer-inner { display:flex; align-items:center; gap:11px; flex-wrap:wrap; }
        .timer-text-col { flex:1; min-width:140px; }
        .timer-text-col p { font-family:var(--font); }
        .timer-digit { display:inline-flex; flex-direction:column; align-items:center; background:rgba(0,0,0,.07); border-radius:8px; padding:.2rem .5rem; min-width:2.6rem; font-variant-numeric:tabular-nums; font-weight:700; font-size:1.1rem; line-height:1; font-family:var(--mono); }
        .timer-digit span { font-family:var(--font); font-size:.5rem; font-weight:500; opacity:.6; text-transform:uppercase; letter-spacing:.07em; margin-top:3px; }
        .timer-pulse { animation:pulse .9s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1}50%{opacity:.3} }
        .timer-progress-wrap { height:3px; border-radius:999px; background:rgba(0,0,0,.08); overflow:hidden; margin-top:10px; }
        .timer-progress-fill { height:100%; border-radius:999px; background:currentColor; opacity:.4; transition:width 1s linear; }

        /* ─── FullCalendar ───────────────────────────────────── */
        #calendar { font-family:var(--font) !important; font-size:.8rem; }
        .fc .fc-toolbar { flex-wrap:wrap; gap:.5rem; }
        .fc-toolbar-title { font-family:var(--font) !important; font-size:.95rem !important; font-weight:800 !important; color:var(--text) !important; letter-spacing:-.02em !important; }
        .fc-button-primary { background:var(--indigo) !important; border-color:var(--indigo) !important; border-radius:9px !important; font-family:var(--font) !important; font-weight:700 !important; font-size:.72rem !important; padding:.3rem .65rem !important; box-shadow:none !important; }
        .fc-button-primary:hover { background:#312e81 !important; }
        .fc-button-primary:not(:disabled):active,.fc-button-primary:not(:disabled).fc-button-active { background:#1e1b4b !important; }
        .fc-daygrid-event { border-radius:5px !important; font-family:var(--font) !important; font-size:.65rem !important; font-weight:600 !important; padding:2px 5px !important; border:none !important; cursor:pointer !important; }
        .fc-daygrid-day:hover { background-color:var(--indigo-light) !important; cursor:pointer; }
        .fc-day-today { background:rgba(55,48,163,.06) !important; }
        .fc-day-today .fc-daygrid-day-number { color:var(--indigo) !important; font-weight:800 !important; }
        .fc-daygrid-day-number { font-family:var(--font); font-size:.72rem; font-weight:600; }
        .fc-col-header-cell-cushion { font-family:var(--font); font-size:.72rem; font-weight:700; }
        body.dark .fc-toolbar-title { color:var(--text) !important; }
        body.dark .fc-daygrid-day-number { color:#7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color:#7fb3e8; }
        body.dark .fc-day-today { background:rgba(55,48,163,.15) !important; }
        body.dark .fc-daygrid-day { background:var(--card) !important; }
        body.dark .fc-theme-standard td,body.dark .fc-theme-standard th,body.dark .fc-theme-standard .fc-scrollgrid { border-color:#101e35 !important; }
        @media(max-width:479px){
            .fc .fc-toolbar{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:6px;}
            .fc-toolbar-chunk:nth-child(2){text-align:center;}
            .fc-toolbar-title{font-size:.8rem !important;}
            .fc-button-primary{font-size:.65rem !important;padding:.25rem .5rem !important;}
            #calendar{font-size:.7rem;}
            .fc .fc-daygrid-body{min-height:auto !important;}
            #calendar .fc-daygrid-body,#calendar .fc-scrollgrid-sync-table{width:100% !important;}
            .fc .fc-daygrid-day-frame{min-height:32px !important;}
        }

        .cal-legend { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        .leg-item { display:flex; align-items:center; gap:5px; }
        .leg-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .leg-lbl { font-family:var(--font); font-size:.68rem; font-weight:600; color:var(--text-sub); }
        @media(max-width:479px){ .cal-legend{gap:8px;} .leg-lbl{display:none;} .leg-dot{width:9px;height:9px;} }

        /* ─── Guide Grid ─────────────────────────────────────── */
        .guide-grid { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:16px; margin-bottom:18px; }
        @media(max-width:700px){ .guide-grid{grid-template-columns:1fr;} }
        .how-step { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px solid var(--border-subtle); }
        .how-step:last-child { border-bottom:none; }
        .step-num { width:24px; height:24px; border-radius:50%; background:var(--indigo); color:white; font-family:var(--font); font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
        .how-step p { font-family:var(--font); }
        .status-guide-row { display:flex; align-items:center; gap:10px; padding:7px 0; border-bottom:1px solid var(--border-subtle); }
        .status-guide-row:last-child { border-bottom:none; }
        .status-guide-row p { font-family:var(--font); font-size:.72rem; color:var(--text-muted); }

        /* ─── Date Row (Modal) ───────────────────────────────── */
        .date-row { display:flex; align-items:center; gap:11px; padding:.75rem; border-bottom:1px solid var(--border-subtle); border-radius:10px; transition:background .15s; }
        .date-row:hover { background:var(--input-bg); }
        .date-row:last-child { border-bottom:none; }

        /* ─────────────────────────────────────────────────────── */
        /*  LIBRARY SECTION — Rich "reading room" aesthetic        */
        /* ─────────────────────────────────────────────────────── */

        /* CSS vars for library palette */
        :root {
            --lib-amber:   #92400e;
            --lib-amber2:  #d97706;
            --lib-cream:   #fffbf0;
            --lib-wood:    #78350f;
            --lib-paper:   #fef3c7;
            --lib-leather: #b45309;
        }

        .lib-section {
            display: grid;
            grid-template-columns: minmax(0,1.1fr) minmax(0,1.5fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        @media(max-width:900px){ .lib-section{grid-template-columns:1fr;} }

        /* ── Atmospheric Hero Card ─── */
        .lib-hero {
            position: relative;
            border-radius: 22px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 280px;
            box-shadow: 0 16px 48px rgba(120,53,15,.22), 0 4px 12px rgba(0,0,0,.12);
            /* Rich warm library gradient */
            background: linear-gradient(160deg, #1c0d00 0%, #3d1c00 30%, #5c2a00 55%, #78350f 80%, #92400e 100%);
        }
        @media(max-width:900px){ .lib-hero{min-height:220px;} }
        /* Paper texture overlay */
        .lib-hero::before {
            content:'';
            position:absolute; inset:0;
            background:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='.07'/%3E%3C/svg%3E")
                repeat center center;
            pointer-events:none; z-index:1;
        }
        /* Warm golden light bloom */
        .lib-hero::after {
            content:'';
            position:absolute; top:-30px; right:-30px;
            width:260px; height:260px;
            background: radial-gradient(circle, rgba(251,191,36,.18) 0%, transparent 70%);
            pointer-events:none; z-index:1;
        }
        .lib-hero-inner { position:relative; z-index:2; padding:26px 24px 0; }
        @media(max-width:479px){ .lib-hero-inner{padding:20px 18px 0;} }

        /* Floating lantern icon */
        .lib-hero-lantern {
            position: absolute;
            top: 22px;
            right: 22px;
            z-index: 2;
            width: 60px; height: 60px;
            display: flex; align-items:center; justify-content:center;
            background: rgba(251,191,36,.15);
            border: 1.5px solid rgba(251,191,36,.3);
            border-radius: 18px;
            animation: lib-float 3.8s ease-in-out infinite;
        }
        @keyframes lib-float { 0%,100%{transform:translateY(0) rotate(-3deg);}50%{transform:translateY(-9px) rotate(3deg);} }

        .lib-eyebrow { font-family:var(--font); font-size:.6rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:rgba(251,191,36,.6); margin-bottom:6px; }
        .lib-count { font-family:var(--mono); font-size:3.2rem; font-weight:900; color:#fef3c7; line-height:1; letter-spacing:-.06em; text-shadow:0 2px 20px rgba(251,191,36,.25); }
        @media(max-width:479px){ .lib-count{font-size:2.4rem;} }
        .lib-count-sub { font-family:var(--font); font-size:.76rem; color:rgba(251,191,36,.5); margin-top:5px; font-style:italic; }

        /* Decorative shelf line */
        .lib-shelf-line {
            position:absolute; bottom:96px; left:0; right:0;
            height:3px;
            background: linear-gradient(90deg, transparent 0%, rgba(251,191,36,.12) 20%, rgba(251,191,36,.2) 50%, rgba(251,191,36,.12) 80%, transparent 100%);
            z-index:2;
        }

        /* Mini book spines on shelf */
        .lib-book-spines {
            position:absolute; bottom:54px; left:24px;
            display:flex; align-items:flex-end; gap:3px; z-index:2;
        }
        .lib-spine {
            border-radius:2px 2px 0 0;
            opacity:.55;
            transition:opacity .2s, transform .2s;
        }
        .lib-spine:hover { opacity:.85; transform:translateY(-3px); }

        /* Stats row at bottom */
        .lib-stats-row { display:flex; gap:8px; padding:0 24px 22px; position:relative; z-index:2; }
        @media(max-width:479px){ .lib-stats-row{padding:0 18px 18px;gap:6px;} }
        .lib-stat-chip {
            flex:1; background:rgba(0,0,0,.28); border:1px solid rgba(251,191,36,.18);
            border-radius:11px; padding:9px 10px; backdrop-filter:blur(8px);
        }
        .lib-stat-chip-lbl { font-family:var(--font); font-size:.5rem; font-weight:700; color:rgba(251,191,36,.5); text-transform:uppercase; letter-spacing:.08em; display:block; }
        .lib-stat-chip-val { font-family:var(--mono); font-size:.98rem; font-weight:800; color:#fef3c7; line-height:1.3; display:block; }
        .lib-browse-btn {
            display:inline-flex; align-items:center; gap:7px;
            margin:0 24px 22px; padding:11px 18px;
            background:rgba(251,191,36,.15);
            border:1.5px solid rgba(251,191,36,.35); border-radius:12px;
            color:#fef3c7; font-family:var(--font); font-size:.78rem; font-weight:700;
            text-decoration:none; transition:all .2s; position:relative; z-index:2; width:fit-content;
        }
        @media(max-width:479px){ .lib-browse-btn{margin:0 18px 18px;} }
        .lib-browse-btn:hover { background:rgba(251,191,36,.28); transform:translateY(-1px); }
        .lib-browse-btn svg { width:14px; height:14px; flex-shrink:0; }

        /* ── Library Right Column ─── */
        .lib-right { display:flex; flex-direction:column; gap:14px; }

        /* ── Featured Books Card — Shelf Style ─── */
        .lib-books-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            flex: 1;
        }
        .lib-books-header {
            padding: 16px 18px 14px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex; align-items: center; justify-content: space-between;
            /* Subtle warm tint for the header */
            background: linear-gradient(90deg, rgba(251,191,36,.04) 0%, transparent 60%);
        }
        .lib-books-header-left { display:flex; align-items:center; gap:10px; }
        .lib-books-icon {
            width:36px; height:36px; border-radius:11px;
            background: linear-gradient(135deg, #92400e, #d97706);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
            box-shadow: 0 3px 10px rgba(146,64,14,.28);
        }
        .lib-books-icon svg { width:16px; height:16px; flex-shrink:0; }

        /* Book rows */
        .book-list { padding:6px 10px 8px; }
        .book-row {
            display:flex; align-items:center; gap:10px; padding:8px 8px;
            border-radius:11px; text-decoration:none; color:inherit; transition:background .15s;
            position:relative;
        }
        .book-row:hover { background:rgba(251,191,36,.07); }
        body.dark .book-row:hover { background:rgba(251,191,36,.06); }

        /* Book cover thumbnail */
        .book-cover {
            width:34px; height:46px; border-radius:4px 5px 5px 4px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
            font-family:var(--font); font-weight:800; font-size:.78rem; color:#fff;
            box-shadow: 2px 3px 8px rgba(0,0,0,.22), inset -2px 0 0 rgba(0,0,0,.18);
            position:relative; overflow:hidden;
        }
        /* spine accent */
        .book-cover::after {
            content:''; position:absolute; left:0; top:0; bottom:0;
            width:5px; background:rgba(0,0,0,.2); border-radius:3px 0 0 3px;
        }
        /* per-nth cover colors */
        .book-row:nth-child(6n+1) .book-cover { background:linear-gradient(160deg,#6366f1,#4338ca); }
        .book-row:nth-child(6n+2) .book-cover { background:linear-gradient(160deg,#ec4899,#be185d); }
        .book-row:nth-child(6n+3) .book-cover { background:linear-gradient(160deg,#10b981,#065f46); }
        .book-row:nth-child(6n+4) .book-cover { background:linear-gradient(160deg,#f97316,#c2410c); }
        .book-row:nth-child(6n+5) .book-cover { background:linear-gradient(160deg,#3b82f6,#1d4ed8); }
        .book-row:nth-child(6n+6) .book-cover { background:linear-gradient(160deg,#8b5cf6,#6d28d9); }

        .book-title { font-family:var(--font); font-size:.82rem; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; letter-spacing:-.01em; }
        .book-author { font-family:var(--font); font-size:.68rem; color:var(--text-sub); margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Availability pill */
        .avail-pill { font-family:var(--font); font-size:.6rem; font-weight:700; padding:3px 9px; border-radius:999px; flex-shrink:0; white-space:nowrap; }

        /* ── Active Borrows Card ─── */
        .lib-borrows-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .lib-borrows-header {
            padding:16px 18px 14px;
            border-bottom:1px solid var(--border-subtle);
            display:flex; align-items:center; justify-content:space-between;
            background: linear-gradient(90deg, rgba(22,163,74,.04) 0%, transparent 60%);
        }
        .lib-borrows-header-left { display:flex; align-items:center; gap:10px; }
        .lib-borrows-icon {
            width:36px; height:36px; border-radius:11px;
            background:linear-gradient(135deg,#16a34a,#065f46);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
            box-shadow:0 3px 10px rgba(22,163,74,.25);
        }
        .lib-borrows-icon svg { width:16px; height:16px; flex-shrink:0; }

        .borrow-list { padding:8px 10px; display:flex; flex-direction:column; gap:7px; }
        .borrow-row {
            display:flex; align-items:center; gap:9px; padding:10px 10px;
            background:var(--input-bg); border:1px solid var(--border-subtle);
            border-radius:12px; transition:border-color .15s;
        }
        .borrow-row:hover { border-color:rgba(22,163,74,.3); }
        .borrow-cover {
            width:28px; height:38px; border-radius:3px 4px 4px 3px;
            background:linear-gradient(135deg,#a5b4fc,#818cf8);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
            font-family:var(--font); font-weight:800; font-size:.7rem; color:#fff;
            box-shadow:1px 2px 5px rgba(0,0,0,.18), inset -2px 0 0 rgba(0,0,0,.15);
            position:relative;
        }
        .borrow-cover::after { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; background:rgba(0,0,0,.18); border-radius:2px 0 0 2px; }

        /* ─── "Reading Now" ambient strip ─── */
        .lib-reading-strip {
            display:flex; align-items:center; gap:12px;
            padding:12px 18px;
            background:linear-gradient(90deg, rgba(251,191,36,.06) 0%, rgba(251,191,36,.02) 100%);
            border:1px solid rgba(251,191,36,.15);
            border-radius:14px;
            margin-bottom:14px;
        }
        body.dark .lib-reading-strip { background:rgba(251,191,36,.05); border-color:rgba(251,191,36,.12); }
        .lib-reading-strip-icon { width:34px; height:34px; border-radius:9px; background:rgba(251,191,36,.12); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .lib-reading-strip p { font-family:var(--font); font-size:.78rem; color:var(--text-muted); line-height:1.5; }
        .lib-reading-strip strong { color:var(--lib-amber); font-weight:700; }

        /* ─── Login Toast ─────────────────────────────────────── */
        .login-toast {
            position:fixed; bottom:calc(var(--mob-nav-total) + 8px); right:16px; z-index:400;
            max-width:280px; background:#0f172a; border-radius:14px; padding:12px 14px;
            display:flex; align-items:flex-start; gap:10px; box-shadow:0 8px 32px rgba(0,0,0,.3);
            transform:translateY(8px); opacity:0; pointer-events:none; transition:all .35s cubic-bezier(.34,1.56,.64,1);
        }
        .login-toast.show { transform:none; opacity:1; pointer-events:auto; }
        .toast-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .toast-close { background:rgba(255,255,255,.08); border:none; border-radius:6px; width:24px; height:24px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; margin-top:1px; }
        #toastTitle { font-family:var(--font); font-weight:700; font-size:12px; line-height:1.3; color:white; }
        #toastBody  { font-family:var(--font); font-size:10px; color:rgba(255,255,255,.6); margin-top:2px; }
        @media(min-width:1024px){ .login-toast{bottom:24px;} }
        @media(max-width:479px){ .login-toast{bottom:calc(var(--mob-nav-total) + 6px);left:12px;right:12px;max-width:none;} }
    </style>
</head>

<body>
    <?php $page = 'dashboard'; include(APPPATH . 'Views/partials/layout.php'); ?>

    <main class="main-area">

        <div class="topbar fade-up" style="background:transparent;border:none;box-shadow:none;padding-bottom:0;">
            <div style="opacity:0;pointer-events:none;"></div>
            <div class="topbar-right">
                <?= layout_dark_toggle() ?>
                <a href="<?= base_url('/reservation') ?>" class="reserve-btn">
                    <?= icon('plus', 16, 'white') ?> Reserve
                </a>
                <div class="notif-bell" onclick="toggleNotifications()">
                    <div class="l-icon-btn"><?= icon('bell', 16, 'currentColor') ?></div>
                    <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
                </div>
            </div>
        </div>

        <div id="notifDD" class="notif-dd">
            <div style="padding:11px 13px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
                <span style="font-family:var(--font);font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
                <button onclick="markAllRead()" style="font-family:var(--font);font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
            </div>
            <div id="notifList" style="max-height:280px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok fade-up">
                <?= icon('check-circle', 14, 'var(--indigo)') ?>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Hero Banner -->
        <div class="hero-banner fade-up">
            <div class="hero-inner">
                <div>
                    <div class="hero-greeting">
                        <?php $h=(int)date('H'); echo $h<12?'Good morning':($h<17?'Good afternoon':'Good evening'); ?>
                    </div>
                    <div class="hero-name">
                        <?= esc($user_name) ?>
                        <span class="hero-name-icon"><?= icon('hand-wave', 20, 'rgba(255,255,255,.85)') ?></span>
                    </div>
                    <div class="hero-date"><?= date('l, F j, Y') ?></div>
                </div>
                <div class="hero-actions">
                    <a href="<?= base_url('/reservation') ?>" class="hero-btn solid">
                        <?= icon('plus', 16, '#3730a3') ?> New Reservation
                    </a>
                    <a href="<?= base_url('/reservation-list') ?>" class="hero-btn">
                        <?= icon('calendar', 16, 'white') ?> My Bookings
                    </a>
                </div>
            </div>
        </div>

        <!-- Stat Pills -->
        <div class="stat-pill-row fade-up-1">
            <div class="stat-pill">
                <div class="sp-icon" style="background:#eef2ff;">
                    <?= icon('layers', 20, '#3730a3') ?>
                </div>
                <div>
                    <div class="sp-lbl">Total</div>
                    <div class="sp-val"><?= $total ?></div>
                    <div class="sp-hint">All reservations</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="sp-icon" style="background:#fef3c7;">
                    <?= icon('clock', 20, '#d97706') ?>
                </div>
                <div>
                    <div class="sp-lbl">Pending</div>
                    <div class="sp-val" style="color:#d97706;"><?= $pending ?></div>
                    <div class="sp-hint">Awaiting review</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="sp-icon" style="background:#dcfce7;">
                    <?= icon('check-circle', 20, '#16a34a') ?>
                </div>
                <div>
                    <div class="sp-lbl">Approved</div>
                    <div class="sp-val" style="color:#16a34a;"><?= $approved ?></div>
                    <div class="sp-hint">Ready to use</div>
                </div>
            </div>
            <?php if ($unclaimedCount > 0): ?>
                <div class="stat-pill" style="border-color:rgba(251,146,60,.25);">
                    <div class="sp-icon" style="background:#fff7ed;"><?= icon('ticket', 20, '#ea580c') ?></div>
                    <div>
                        <div class="sp-lbl">No-show</div>
                        <div class="sp-val" style="color:#ea580c;"><?= $unclaimedCount ?></div>
                        <div class="sp-hint">Slot<?= $unclaimedCount>1?'s':'' ?> missed</div>
                    </div>
                </div>
            <?php elseif ($claimedCount > 0): ?>
                <div class="stat-pill">
                    <div class="sp-icon" style="background:#ede9fe;"><?= icon('check-double', 20, '#7c3aed') ?></div>
                    <div>
                        <div class="sp-lbl">Claimed</div>
                        <div class="sp-val" style="color:#7c3aed;"><?= $claimedCount ?></div>
                        <div class="sp-hint">Tickets used</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="stat-pill">
                    <div class="sp-icon" style="background:#fee2e2;"><?= icon('ban', 20, '#dc2626') ?></div>
                    <div>
                        <div class="sp-lbl">Declined</div>
                        <div class="sp-val" style="color:#dc2626;"><?= $declined ?></div>
                        <div class="sp-hint">Not approved</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Next Action -->
        <?php if ($nextAction): $nc = $nextColors[$nextAction['color']]; ?>
            <div class="next-card fade-up-1" style="background:<?= $nc['bg'] ?>;border-color:<?= $nc['border'] ?>;">
                <div class="next-icon-wrap" style="background:<?= $nc['icon_bg'] ?>;">
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

        <!-- Timer Banner -->
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
                    <span style="font-family:var(--font);font-weight:700;font-size:14px;opacity:.4;" class="timer-pulse">:</span>
                    <div class="timer-digit"><span id="tdM">00</span><span>min</span></div>
                    <span style="font-family:var(--font);font-weight:700;font-size:14px;opacity:.4;" class="timer-pulse">:</span>
                    <div class="timer-digit"><span id="tdS">00</span><span>sec</span></div>
                </div>
            </div>
            <div id="timerPW" class="timer-progress-wrap" style="display:none;">
                <div id="timerPF" class="timer-progress-fill" style="width:0%;"></div>
            </div>
        </div>

        <!-- Upcoming Pill -->
        <?php if ($upcoming): ?>
            <div class="upcoming-pill fade-up-2">
                <div class="up-icon"><?= icon('ticket', 16, 'white') ?></div>
                <div style="flex:1;min-width:0;">
                    <div class="up-eyebrow">Upcoming Reservation</div>
                    <div class="up-name"><?= esc($upcoming['resource_name'] ?? 'Resource') ?><?php if (!empty($upcoming['pc_number'])): ?> &middot; <span style="font-weight:400;"><?= esc($upcoming['pc_number']) ?></span><?php endif; ?></div>
                    <div class="up-time"><?= date('M j, Y', strtotime($upcoming['reservation_date'])) ?> &nbsp;&middot;&nbsp; <?= date('g:i A', strtotime($upcoming['start_time'])) ?> – <?= date('g:i A', strtotime($upcoming['end_time'])) ?></div>
                </div>
                <a href="<?= base_url('/reservation-list') ?>" class="up-btn">View →</a>
            </div>
        <?php endif; ?>

        <!-- Dashboard Grid -->
        <div class="dash-grid fade-up-2">
            <div class="card card-p-lg">
                <div class="card-head">
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
                <div class="card card-p">
                    <div class="section-lbl">Quick Actions</div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <a href="<?= base_url('/reservation') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#eef2ff;"><?= icon('plus', 15, 'var(--indigo)') ?></div>
                            New Reservation
                            <span class="qa-chev"><?= icon('chevron-right', 13, 'currentColor') ?></span>
                        </a>
                        <a href="<?= base_url('/reservation-list') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#ede9fe;"><?= icon('calendar', 15, '#7c3aed') ?></div>
                            My Reservations
                            <?php if ($pending > 0): ?>
                                <span style="margin-left:auto;background:#fef3c7;color:#92400e;font-family:var(--font);font-size:9px;font-weight:700;padding:1px 7px;border-radius:999px;"><?= $pending ?></span>
                            <?php else: ?>
                                <span class="qa-chev"><?= icon('chevron-right', 13, 'currentColor') ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= base_url('/books') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#fef3c7;"><?= icon('book-open', 15, '#d97706') ?></div>
                            Browse Library
                            <span class="qa-chev"><?= icon('chevron-right', 13, 'currentColor') ?></span>
                        </a>
                        <a href="<?= base_url('/profile') ?>" class="qa-link">
                            <div class="qa-icon" style="background:#f3e8ff;"><?= icon('user', 15, '#9333ea') ?></div>
                            View Profile
                            <span class="qa-chev"><?= icon('chevron-right', 13, 'currentColor') ?></span>
                        </a>
                    </div>
                </div>

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
                            <p style="font-family:var(--font);font-size:12px;color:var(--text-sub);">No bookings yet</p>
                            <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:9px;font-family:var(--font);font-size:11px;font-weight:700;color:var(--indigo);text-decoration:none;">
                                <?= icon('plus', 12, 'var(--indigo)') ?> Make your first reservation
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Guide Grid -->
        <?php if (empty($reservations) || $unclaimedCount > 0 || $pending > 0): ?>
            <div class="guide-grid fade-up-3">
                <div class="card card-p">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                        <div class="card-icon" style="background:#eef2ff;"><?= icon('list-check', 16, 'var(--indigo)') ?></div>
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
                        ['pending',  'clock',       '#fef3c7','#92400e','#d97706','Pending', 'Waiting for SK officer review.'],
                        ['approved', 'check-circle','#dcfce7','#166534','#16a34a','Approved','Confirmed. Get your e-ticket.'],
                        ['claimed',  'check-double','#ede9fe','#5b21b6','#7c3aed','Claimed', 'E-ticket scanned. Slot used.'],
                        ['unclaimed','ticket',      '#fff7ed','#c2410c','#ea580c','No-show', "Approved but you didn't attend."],
                        ['declined', 'ban',         '#fee2e2','#991b1b','#dc2626','Declined','Not approved. Try another time.'],
                        ['expired',  'hourglass',   '#f1f5f9','#475569','#64748b','Expired', 'Date passed before approval.'],
                    ] as [$key,$ico,$bg,$fg,$ic,$label,$desc]): ?>
                        <div class="status-guide-row">
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:7px;font-family:var(--font);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;flex-shrink:0;min-width:74px;justify-content:center;background:<?= $bg ?>;color:<?= $fg ?>;">
                                <?= icon($ico, 8, $ic) ?><?= $label ?>
                            </span>
                            <p><?= $desc ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ══════════════════════════════════════════════════════ -->
        <!--  LIBRARY SECTION                                       -->
        <!-- ══════════════════════════════════════════════════════ -->
        <div class="lib-section fade-up-4">

            <!-- Atmospheric Hero -->
            <div class="lib-hero">
                <!-- Floating icon -->
                <div class="lib-hero-lantern">
                    <?= icon('book-open', 28, 'rgba(251,191,36,.9)') ?>
                </div>

                <!-- Top content -->
                <div class="lib-hero-inner">
                    <div class="lib-eyebrow">Community Library</div>
                    <div class="lib-count"><?= $availableCount ?></div>
                    <div class="lib-count-sub">available &nbsp;·&nbsp; <?= $totalBooks ?> total titles</div>

                    <!-- Decorative mini book spines -->
                    <div style="display:flex;align-items:flex-end;gap:3px;margin-top:22px;margin-bottom:0;">
                        <?php
                        $spines = [
                            ['h'=>52,'w'=>14,'c'=>'#6366f1'],['h'=>66,'w'=>11,'c'=>'#ec4899'],
                            ['h'=>44,'w'=>16,'c'=>'#10b981'],['h'=>58,'w'=>12,'c'=>'#f97316'],
                            ['h'=>70,'w'=>10,'c'=>'#3b82f6'],['h'=>48,'w'=>15,'c'=>'#8b5cf6'],
                            ['h'=>62,'w'=>11,'c'=>'#14b8a6'],['h'=>55,'w'=>13,'c'=>'#f59e0b'],
                            ['h'=>40,'w'=>14,'c'=>'#ef4444'],['h'=>68,'w'=>10,'c'=>'#a855f7'],
                        ];
                        foreach ($spines as $sp): ?>
                            <div style="
                                width:<?= $sp['w'] ?>px; height:<?= $sp['h'] ?>px;
                                background:<?= $sp['c'] ?>;
                                border-radius:2px 2px 0 0;
                                opacity:.5;
                                box-shadow: inset -2px 0 0 rgba(0,0,0,.25);
                                transition: opacity .2s, transform .2s;
                                cursor:default;
                            " onmouseover="this.style.opacity='.8';this.style.transform='translateY(-4px)'"
                               onmouseout="this.style.opacity='.5';this.style.transform=''">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- shelf edge -->
                    <div style="height:3px;background:linear-gradient(90deg,rgba(251,191,36,.08),rgba(251,191,36,.2),rgba(251,191,36,.08));margin-top:0;border-radius:0;"></div>
                </div>

                <!-- Bottom stats -->
                <div>
                    <div class="lib-stats-row">
                        <div class="lib-stat-chip">
                            <span class="lib-stat-chip-lbl">My Borrows</span>
                            <span class="lib-stat-chip-val"><?= count($myBorrowings) ?></span>
                        </div>
                        <div class="lib-stat-chip">
                            <span class="lib-stat-chip-lbl">Pending</span>
                            <span class="lib-stat-chip-val"><?= count(array_filter($myBorrowings, fn($b) => ($b['status']??'') === 'pending')) ?></span>
                        </div>
                        <div class="lib-stat-chip">
                            <span class="lib-stat-chip-lbl">Active</span>
                            <span class="lib-stat-chip-val"><?= count(array_filter($myBorrowings, fn($b) => ($b['status']??'') === 'approved')) ?></span>
                        </div>
                    </div>
                    <a href="<?= base_url('/books') ?>" class="lib-browse-btn">
                        <?= icon('book-open', 14, '#fef3c7') ?> Browse Collection
                    </a>
                </div>
            </div>

            <!-- Right column: books + borrows -->
            <div class="lib-right">

                <!-- Reading strip ambient hint -->
                <div class="lib-reading-strip">
                    <div class="lib-reading-strip-icon">
                        <?= icon('feather', 16, '#d97706') ?>
                    </div>
                    <p>
                        <strong><?= $availableCount ?> books</strong> ready to borrow from the community collection.
                        Browse titles, request a borrow, and return by the due date.
                    </p>
                </div>

                <!-- Available Books Card -->
                <div class="lib-books-card">
                    <div class="lib-books-header">
                        <div class="lib-books-header-left">
                            <div class="lib-books-icon">
                                <?= icon('book-open', 16, 'white') ?>
                            </div>
                            <div>
                                <div class="card-title">Available Now</div>
                                <div class="card-sub">Books you can borrow today</div>
                            </div>
                        </div>
                        <a href="<?= base_url('/books') ?>" class="link-sm">All →</a>
                    </div>

                    <?php if (!empty($featuredBooks)): ?>
                        <div class="book-list">
                            <?php foreach (array_slice($featuredBooks, 0, 5) as $book):
                                $avail = (int)($book['available_copies'] ?? 0);
                                $pillBg  = $avail === 0 ? '#fecaca' : ($avail <= 1 ? '#fde68a' : '#bbf7d0');
                                $pillFg  = $avail === 0 ? '#7f1d1d' : ($avail <= 1 ? '#78350f' : '#14532d');
                                $pillTxt = $avail === 0 ? 'Out' : ($avail <= 1 ? '1 left' : $avail . ' left');
                            ?>
                                <a href="<?= base_url('/books') ?>" class="book-row">
                                    <div class="book-cover">
                                        <?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="book-title"><?= esc($book['title']) ?></div>
                                        <div class="book-author"><?= esc($book['author'] ?? 'Unknown') ?></div>
                                    </div>
                                    <span class="avail-pill" style="background:<?= $pillBg ?>;color:<?= $pillFg ?>;"><?= $pillTxt ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($featuredBooks) > 5): ?>
                            <div style="padding:8px 18px 14px;border-top:1px solid var(--border-subtle);text-align:center;">
                                <a href="<?= base_url('/books') ?>" class="link-sm">+<?= count($featuredBooks) - 5 ?> more titles →</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="text-align:center;padding:32px 16px;">
                            <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('book-open', 30, 'currentColor') ?></div>
                            <p style="font-family:var(--font);font-size:.78rem;color:var(--text-sub);">No books available right now</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Active Borrows Card -->
                <?php
                $activeBorrows = array_slice(
                    array_values(array_filter($myBorrowings, fn($b) => in_array($b['status']??'', ['approved','pending']))),
                    0, 4
                );
                if (!empty($activeBorrows)): ?>
                    <div class="lib-borrows-card">
                        <div class="lib-borrows-header">
                            <div class="lib-borrows-header-left">
                                <div class="lib-borrows-icon">
                                    <?= icon('bookmark', 16, 'white') ?>
                                </div>
                                <div>
                                    <div class="card-title">My Active Borrows</div>
                                    <div class="card-sub">Currently checked out</div>
                                </div>
                            </div>
                            <a href="<?= base_url('/books') ?>#mine" class="link-sm">All →</a>
                        </div>
                        <div class="borrow-list">
                            <?php foreach ($activeBorrows as $borrow):
                                $bs  = strtolower($borrow['status'] ?? 'pending');
                                $due = !empty($borrow['due_date']) ? strtotime($borrow['due_date']) : null;
                                $overdue = $due && $due < time();
                                $dueSoon = $due && !$overdue && $due < time() + 3 * 86400;
                            ?>
                                <div class="borrow-row">
                                    <div class="borrow-cover">
                                        <?= mb_strtoupper(mb_substr($borrow['title'] ?? 'B', 0, 1)) ?>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-family:var(--font);font-weight:700;font-size:.8rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($borrow['title'] ?? 'Unknown Book') ?></p>
                                        <?php if ($due && $bs === 'approved'): ?>
                                            <p style="font-family:var(--mono);font-size:.67rem;color:<?= $overdue ? '#ef4444' : ($dueSoon ? '#d97706' : '#6366f1') ?>;">
                                                <?= $overdue ? 'Overdue · ' : ($dueSoon ? 'Due soon · ' : '') ?><?= date('M j, Y', $due) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="tag tag-<?= $overdue ? 'declined' : ($dueSoon ? 'pending' : $bs) ?>" style="border-radius:8px;font-size:.62rem;">
                                        <?= $overdue ? 'Overdue' : ($dueSoon ? 'Due Soon' : ucfirst($bs)) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="lib-borrows-card" style="padding:28px 20px;text-align:center;">
                        <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('bookmark', 26, 'currentColor') ?></div>
                        <p style="font-family:var(--font);font-size:.78rem;color:var(--text-sub);font-weight:600;">No active borrows</p>
                        <a href="<?= base_url('/books') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;font-family:var(--font);font-size:.72rem;font-weight:700;color:#7c3aed;text-decoration:none;">
                            <?= icon('book-open', 12, '#7c3aed') ?> Borrow a book
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <!-- End Library Section -->

    </main>

    <!-- Date Modal -->
    <div id="dateModal" class="modal-back" onclick="handleModalBack(event)">
        <div class="modal-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                <div>
                    <h3 style="font-family:var(--font);font-size:16px;font-weight:700;letter-spacing:-.2px;" id="modalDateTitle"></h3>
                    <p style="font-family:var(--font);font-size:11px;color:var(--text-sub);margin-top:2px;" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" style="width:36px;height:36px;border-radius:9px;background:var(--input-bg);border:none;color:var(--text-sub);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <?= icon('x', 13, 'currentColor') ?>
                </button>
            </div>
            <div id="modalList"></div>
            <div class="hidden" id="modalEmpty" style="text-align:center;padding:24px 12px;">
                <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= icon('calendar-x', 26, 'currentColor') ?></div>
                <p style="font-family:var(--font);font-size:12px;color:var(--text-sub);">No reservations for this date.</p>
            </div>
            <button onclick="closeDateModal()" style="margin-top:16px;width:100%;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-family:var(--font);font-weight:600;color:var(--text-muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;">Close</button>
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
        const NOTIF_KEY = 'notified_ids_<?= session()->get('user_id') ?>';
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
                msg: `${r.resource_name || 'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
                time: r.updated_at || r.created_at || new Date().toISOString(),
                read: seen.includes(parseInt(r.id))
            }));
            updateBadge(); renderNotifs();
        }

        function markAllRead() {
            saveSeenIds([...new Set([...getSeenIds(), ...notifications.map(n => n.id)])]);
            notifications.forEach(n => n.read = true);
            updateBadge(); renderNotifs();
        }
        function markRead(id) {
            const ids = getSeenIds();
            if (!ids.includes(id)) saveSeenIds([...ids, id]);
            const n = notifications.find(n => n.id === id);
            if (n) { n.read = true; updateBadge(); renderNotifs(); }
        }
        function updateBadge() {
            const badge = document.getElementById('notifBadge');
            const unread = notifications.filter(n => !n.read).length;
            badge.style.display = unread > 0 ? 'block' : 'none';
            badge.textContent = unread > 9 ? '9+' : unread;
        }
        function renderNotifs() {
            const list = document.getElementById('notifList');
            if (!notifications.length) {
                list.innerHTML = `<div style="text-align:center;padding:24px 16px;"><p style="font-family:var(--font);font-size:12px;color:var(--text-sub);">All caught up!</p></div>`;
                return;
            }
            list.innerHTML = notifications
                .sort((a,b) => new Date(b.time) - new Date(a.time))
                .map(n => `
        <div class="notif-item ${!n.read?'unread':''}" onclick="markRead(${n.id})">
            <div style="display:flex;align-items:flex-start;gap:9px;">
                <div style="width:30px;height:30px;background:var(--indigo-light);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-family:var(--font);font-weight:700;font-size:12px;color:var(--text);">${n.title}</p>
                    <p style="font-family:var(--font);font-size:10px;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                    <p style="font-family:var(--font);font-size:9px;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p>
                </div>
                ${!n.read?'<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:3px;"></span>':''}
            </div>
        </div>`).join('');
        }

        function toggleNotifications() { document.getElementById('notifDD').classList.toggle('show'); }
        document.addEventListener('click', e => {
            const dd = document.getElementById('notifDD'), bell = document.querySelector('.notif-bell');
            if (!bell.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('show');
        });

        const timeAgo = t => {
            const s = Math.floor((Date.now() - new Date(t)) / 1000);
            if (s < 60) return 'Just now';
            if (s < 3600) return `${Math.floor(s/60)}m ago`;
            if (s < 86400) return `${Math.floor(s/3600)}h ago`;
            return `${Math.floor(s/86400)}d ago`;
        };

        function to12hPHT(ts) {
            if (!ts) return '—';
            const parts = ts.split(':');
            let h = parseInt(parts[0], 10);
            const m = parts[1] ? parts[1].padStart(2,'0') : '00';
            if (isNaN(h)) return ts;
            const ampm = h < 12 ? 'AM' : 'PM';
            h = h % 12 || 12;
            return `${h}:${m} ${ampm}`;
        }

        function openDateModal(date, items) {
            const d = new Date(date + 'T00:00:00');
            document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
            document.getElementById('modalDateSub').textContent = items.length ? `${items.length} reservation${items.length>1?'s':''}` : '';
            const list = document.getElementById('modalList'), empty = document.getElementById('modalEmpty');
            list.innerHTML = '';
            if (items.length) {
                empty.classList.add('hidden');
                const cmap = { approved:'#dcfce7|#166534', pending:'#fef3c7|#92400e', declined:'#fee2e2|#991b1b', canceled:'#fee2e2|#991b1b', claimed:'#ede9fe|#5b21b6' };
                items.sort((a,b) => (a.start_time||'').localeCompare(b.start_time||'')).forEach(r => {
                    const isClaimed = r.claimed==1 || r.status==='claimed' || !!r.claimed_at;
                    const s = isClaimed ? 'claimed' : (r.status||'pending').toLowerCase();
                    const [cbg,cfg] = (cmap[s]||'#f1f5f9|#475569').split('|');
                    const tFmt = r.start_time ? to12hPHT(r.start_time) : 'All day';
                    const etFmt = r.end_time ? to12hPHT(r.end_time) : '';
                    const timeDisplay = etFmt ? `${tFmt} – ${etFmt} PHT` : tFmt;
                    const row = document.createElement('div');
                    row.className = 'date-row';
                    row.innerHTML = `
            <div style="width:32px;height:32px;background:var(--input-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border);">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-sub)" stroke-width="1.5" style="width:13px;height:13px;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-family:var(--font);font-weight:600;font-size:13px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Reserved'}</p>
                <p style="font-family:var(--font);font-size:11px;color:#3730a3;margin-top:1px;font-weight:600;">${timeDisplay}</p>
            </div>
            <span style="display:inline-flex;padding:2px 8px;border-radius:999px;font-family:var(--font);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:${cbg};color:${cfg};flex-shrink:0;">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
                    list.appendChild(row);
                });
            } else { empty.classList.remove('hidden'); }
            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDateModal() { document.getElementById('dateModal').classList.remove('show'); document.body.style.overflow = ''; }
        function handleModalBack(e) { if (e.target.classList.contains('modal-back')) closeDateModal(); }
        document.addEventListener('keydown', e => { if (e.key==='Escape') closeDateModal(); });

        function initTimer() {
            const banner=document.getElementById('timerBanner'), titleEl=document.getElementById('timerTitle'),
                subEl=document.getElementById('timerSub'), hEl=document.getElementById('tdH'),
                mEl=document.getElementById('tdM'), sEl=document.getElementById('tdS'),
                iconW=document.getElementById('timerIconWrap'), pw=document.getElementById('timerPW'),
                pf=document.getElementById('timerPF');
            const mkSvg=(path,sw)=>`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="${sw}" style="width:16px;height:16px;flex-shrink:0;">${path}</svg>`;
            const icons = {
                urgent:  mkSvg('<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>','1.8'),
                warning: mkSvg('<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round"/>','1.8'),
                safe:    mkSvg('<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>','1.8'),
            };
            function findTarget() {
                const now = Date.now(); let active=null, upcoming=null;
                approvedRes.forEach(r => {
                    if (!r.reservation_date||!r.start_time||!r.end_time) return;
                    const start = new Date(r.reservation_date+'T'+r.start_time).getTime();
                    const end   = new Date(r.reservation_date+'T'+r.end_time).getTime();
                    const minsToStart=(start-now)/60000, minsToEnd=(end-now)/60000;
                    if (now>=start&&now<end&&!active) active={r,start,end,mode:'active',minsLeft:minsToEnd};
                    if (!upcoming&&minsToStart>0&&minsToStart<=30) upcoming={r,start,end,mode:'upcoming',minsLeft:minsToStart};
                });
                return active||upcoming||null;
            }
            function tick() {
                const target = findTarget();
                if (!target) { banner.style.display='none'; return; }
                const {r,start,end,mode,minsLeft} = target;
                const now=Date.now(), diff=Math.max(0,(mode==='active'?end:start)-now);
                const h=Math.floor(diff/3600000),m=Math.floor((diff%3600000)/60000),s=Math.floor((diff%60000)/1000);
                hEl.textContent=String(h).padStart(2,'0'); mEl.textContent=String(m).padStart(2,'0'); sEl.textContent=String(s).padStart(2,'0');
                banner.classList.remove('urgent','warning','safe');
                if (mode==='active') {
                    if (minsLeft<=10){banner.classList.add('urgent');iconW.innerHTML=icons.urgent;}
                    else if(minsLeft<=20){banner.classList.add('warning');iconW.innerHTML=icons.warning;}
                    else{banner.classList.add('safe');iconW.innerHTML=icons.safe;}
                    titleEl.textContent = minsLeft<=10 ? 'Reservation ends very soon!' : 'Your reservation is active';
                    subEl.textContent = `${r.resource_name||'Resource'} · Ends at ${(r.end_time||'').substring(0,5)}`;
                    const pct=Math.min(100,Math.max(0,((now-start)/(end-start))*100));
                    pw.style.display='block'; pf.style.width=pct.toFixed(1)+'%';
                } else {
                    banner.classList.add('safe'); iconW.innerHTML=icons.safe;
                    titleEl.textContent='Your reservation starts soon';
                    subEl.textContent=`${r.resource_name||'Resource'} · Starts at ${(r.start_time||'').substring(0,5)}`;
                    pw.style.display='none';
                }
                banner.style.display='block';
            }
            tick(); setInterval(tick,1000);
        }

        function showLoginToast() {
            const key='toast_<?= session()->get('user_id') ?>_'+new Date().toDateString();
            if (sessionStorage.getItem(key)) return;
            sessionStorage.setItem(key,'1');
            const now=Date.now(); let td=null;
            approvedRes.forEach(r => {
                if (!r.reservation_date||!r.start_time||!r.end_time) return;
                const start=new Date(r.reservation_date+'T'+r.start_time).getTime();
                const end=new Date(r.reservation_date+'T'+r.end_time).getTime();
                const minsToStart=(start-now)/60000;
                const today=new Date().toDateString(), resDay=new Date(r.reservation_date+'T00:00:00').toDateString();
                if (now>=start&&now<end&&!td) td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><polygon points="5 3 19 12 5 21 5 3"/></svg>',bg:'rgba(37,99,235,.2)',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${(r.end_time||'').substring(0,5)}`};
                if (!td&&resDay===today&&minsToStart>0&&minsToStart<=120) td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',bg:'rgba(217,119,6,.2)',title:`In ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${(r.start_time||'').substring(0,5)} – ${(r.end_time||'').substring(0,5)}`};
                if (!td&&resDay===today) { const fmt=t=>{const[h,m]=t.split(':');const hr=+h;return `${hr%12||12}:${m} ${hr<12?'AM':'PM'}`;}; td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8" style="width:13px;height:13px;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>',bg:'rgba(37,99,235,.2)',title:'Reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`}; }
            });
            if (!td) return;
            const toast=document.getElementById('loginToast');
            document.getElementById('toastIcon').innerHTML=td.icon;
            document.getElementById('toastIcon').style.background=td.bg;
            document.getElementById('toastTitle').textContent=td.title;
            document.getElementById('toastBody').textContent=td.body;
            setTimeout(()=>toast.classList.add('show'),900);
            setTimeout(()=>toast.classList.remove('show'),7500);
        }

        function dismissToast() { document.getElementById('loginToast').classList.remove('show'); }

        document.addEventListener('DOMContentLoaded', () => {
            document.documentElement.classList.remove('dark-pre');
            if ('Notification' in window) Notification.requestPermission();
            loadNotifications(); initTimer(); showLoginToast();

            const byDate={};
            allResData.forEach(r => { if (!r.reservation_date) return; if (!byDate[r.reservation_date]) byDate[r.reservation_date]=[]; byDate[r.reservation_date].push(r); });

            const colorMap = { approved:'#10b981', pending:'#fbbf24', declined:'#f87171', canceled:'#f87171', claimed:'#a855f7' };
            const events = allResData.filter(r=>r.reservation_date).map(r => {
                const isClaimed=r.claimed==1||r.status==='claimed'||!!r.claimed_at;
                const s=isClaimed?'claimed':(r.status||'pending').toLowerCase();
                const d=r.reservation_date.trim();
                return {
                    title: r.resource_name||'Reservation',
                    start: d+(r.start_time?'T'+r.start_time.substring(0,8):''),
                    end:   d+(r.end_time?'T'+r.end_time.substring(0,8):''),
                    allDay: !r.start_time,
                    backgroundColor: colorMap[s]||'#94a3b8',
                    borderColor: 'transparent',
                    textColor: '#fff',
                    extendedProps: {status:s}
                };
            });

            const w=window.innerWidth, calView=w<480?'listWeek':'dayGridMonth', calHeight=w<640?'auto':380;
            const cal=new FullCalendar.Calendar(document.getElementById('calendar'),{
                initialView: calView,
                headerToolbar: {left:'prev,next',center:'title',right:'today'},
                events, height:calHeight, eventDisplay:'block', eventMaxStack:2,
                dateClick: info => openDateModal(info.dateStr, byDate[info.dateStr]||[]),
                eventClick: info => { const d=info.event.startStr.split('T')[0]; openDateModal(d, byDate[d]||[]); },
                dayCellDidMount: info => {
                    const d=info.date.toISOString().split('T')[0], items=byDate[d];
                    if (items&&items.length) {
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