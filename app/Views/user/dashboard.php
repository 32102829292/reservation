<?php
// ★ Pre-process reservations once for accurate status counts
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
    'amber'  => ['bg'=>'#fffbeb','border'=>'#fde68a','icon_bg'=>'#fef3c7','icon_fg'=>'#d97706','btn_bg'=>'#d97706','icon'=>'clock'],
    'blue'   => ['bg'=>'#eef2ff','border'=>'#c7d2fe','icon_bg'=>'#e0e7ff','icon_fg'=>'#4338ca','btn_bg'=>'#4338ca','icon'=>'ticket'],
    'orange' => ['bg'=>'#fff7ed','border'=>'#fed7aa','icon_bg'=>'#ffedd5','icon_fg'=>'#ea580c','btn_bg'=>'#ea580c','icon'=>'triangle'],
    'slate'  => ['bg'=>'#f8fafc','border'=>'#e2e8f0','icon_bg'=>'#f1f5f9','icon_fg'=>'#64748b','btn_bg'=>'#64748b','icon'=>'calendar-x'],
];

// SVG icon helper
function icon($name, $size=16, $stroke='currentColor', $extra='') {
    $icons = [
        'house'        => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus'         => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'book-open'    => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'user'         => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'logout'       => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
        'clock'        => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'check-circle' => '<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'ticket'       => '<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round"/>',
        'triangle'     => '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'calendar-x'   => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="14" x2="14" y2="18"/><line x1="14" y1="14" x2="10" y2="18"/>',
        'bell'         => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
        'bell-slash'   => '<path d="M13.73 21a2 2 0 01-3.46 0M18.63 13A17.89 17.89 0 0118 8M6.26 6.26A5.86 5.86 0 006 8c0 7-3 9-3 9h14M18 8a6 6 0 00-9.33-5M1 1l22 22" stroke-linecap="round" stroke-linejoin="round"/>',
        'check'        => '<polyline points="20 6 9 17 4 12"/>',
        'x'            => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'chevron-right'=> '<polyline points="9 18 15 12 9 6"/>',
        'chevron-left' => '<polyline points="15 18 9 12 15 6"/>',
        'arrow-right'  => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'arrow-up-right'=> '<path d="M7 17L17 7M17 7H7M17 7v10" stroke-linecap="round" stroke-linejoin="round"/>',
        'ban'          => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>',
        'hourglass'    => '<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/>',
        'layers'       => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'list-check'   => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke-linecap="round" stroke-linejoin="round"/>',
        'sparkles'     => '<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round" stroke-linejoin="round"/>',
        'search'       => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'bookmark'     => '<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>',
        'robot'        => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><circle cx="12" cy="5" r="1"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/>',
        'info'         => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'sun'          => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
        'moon'         => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
        'trending-up'  => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
        'trending-dn'  => '<polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/>',
        'check-double' => '<path d="M17 1l-8.5 8.5L6 7M22 6l-8.5 8.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 13l-4 4 1.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar-days'=> '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>',
        'piggy-bank'   => '<path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c4-2 4.5-5 4-8h-1.5c-.5 0-1.5-.5-1.5-2z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/><circle cx="19.5" cy="7.5" r=".5" fill="currentColor" stroke="none"/>',
        'bar-chart'    => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        'eye'          => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
        'eye-off'      => '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
    $d = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    $sw = in_array($name, ['calendar','calendar-days','calendar-x','bar-chart','bookmark','robot']) ? '1.5' : '1.8';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="'.$stroke.'" stroke-width="'.$sw.'" '.$extra.'>'.$d.'</svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | <?= esc($user_name) ?></title>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <meta name="theme-color" content="#3730a3">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* ── Reset & Tokens ─────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --indigo: #3730a3;
            --indigo-mid: #4338ca;
            --indigo-light: #eef2ff;
            --indigo-border: #c7d2fe;
            --violet: #6d28d9;
            --slate-bg: #f0f4fd;
            --font: 'DM Sans', sans-serif;
            --mono: 'DM Mono', monospace;
        }
        html { height: 100%; }
        body {
            font-family: var(--font);
            background: var(--slate-bg);
            color: #0f172a;
            margin: 0;
            display: flex !important;
            height: 100vh !important;
            overflow: hidden !important;
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ─────────────────────────────────────── */
        .sidebar-card {
            background: white;
            border-radius: 20px;
            border: 0.5px solid rgba(55,48,163,.12);
            height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            width: 100%;
        }
        .sidebar-header { flex-shrink: 0; padding: 20px 18px 16px; border-bottom: 0.5px solid rgba(55,48,163,.08); }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
        .sidebar-footer { flex-shrink: 0; padding: 12px; border-top: 0.5px solid rgba(55,48,163,.08); }

        .brand-tag { font-size: 9px; font-weight: 600; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .brand-name { font-size: 22px; font-weight: 700; color: #0f172a; letter-spacing: -.5px; }
        .brand-name span { color: var(--indigo); }

        /* ── Nav links ───────────────────────────────────── */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 12px;
            font-size: 13px; font-weight: 500; letter-spacing: -.1px;
            color: #64748b; text-decoration: none;
            transition: all .15s;
        }
        .nav-link:hover { background: var(--indigo-light); color: var(--indigo); }
        .nav-link.active { background: var(--indigo); color: #fff; }
        .nav-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.15); }
        .nav-link:not(.active) .nav-icon { background: #f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background: #e0e7ff; }

        /* ── Quota bar ───────────────────────────────────── */
        .quota-wrap { margin: 6px 4px; background: #f8fafc; border-radius: 12px; padding: 12px 13px; border: 0.5px solid rgba(55,48,163,.08); }
        .quota-lbl { display: flex; justify-content: space-between; font-size: 9px; font-weight: 600; letter-spacing: .15em; text-transform: uppercase; color: #94a3b8; margin-bottom: 8px; }
        .quota-track { height: 4px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .quota-fill { height: 100%; border-radius: 999px; background: var(--indigo); transition: width .6s cubic-bezier(.34,1.56,.64,1); }
        .quota-note { font-size: 10px; font-weight: 500; color: #94a3b8; margin-top: 6px; }

        /* ── Mobile nav ──────────────────────────────────── */
        .mobile-nav-pill {
            display: none;
            position: fixed; bottom: 16px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 480px;
            background: white;
            border: 0.5px solid rgba(55,48,163,.12);
            border-radius: 22px; padding: 6px 8px; z-index: 100;
            box-shadow: 0 8px 32px rgba(55,48,163,.14);
        }
        .mobile-scroll-container { display: flex; gap: 2px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }
        .mob-nav-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 7px 12px; min-width: 62px; border-radius: 15px;
            gap: 4px; cursor: pointer; text-decoration: none;
            transition: all .15s; flex-shrink: 0; color: #64748b;
        }
        .mob-nav-item:hover { background: var(--indigo-light); color: var(--indigo); }
        .mob-nav-item.active { background: var(--indigo); color: #fff; }
        .mob-nav-lbl { font-size: 9px; font-weight: 600; white-space: nowrap; letter-spacing: .02em; }
        .mob-nav-logout { color: #f87171; }
        .mob-nav-logout:hover { background: #fef2f2; color: #dc2626; }

        /* ── Responsive show/hide ────────────────────────── */
        @media (max-width: 1023px) {
            .desktop-sidebar { display: none !important; }
            .mobile-nav-pill { display: block; }
            .main-area { padding-bottom: 90px; }
        }
        @media (min-width: 1024px) {
            .desktop-sidebar { display: flex !important; }
            .mobile-nav-pill { display: none !important; }
        }

        /* ── Main layout ─────────────────────────────────── */
        .main-area { flex: 1; min-width: 0; padding: 20px 24px 40px; height: 100vh; overflow-y: auto; }

        /* ── Top bar ─────────────────────────────────────── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; gap: 16px; }
        .greeting-eyebrow { font-size: 10px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .greeting-name { font-size: 24px; font-weight: 700; color: #0f172a; letter-spacing: -.6px; line-height: 1.1; }
        .greeting-date { font-size: 12px; font-weight: 400; color: #94a3b8; margin-top: 3px; }
        .topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; margin-top: 4px; }

        .reserve-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: var(--indigo); color: #fff;
            border-radius: 10px; font-size: 12px; font-weight: 600;
            border: none; cursor: pointer; font-family: var(--font);
            letter-spacing: -.1px; transition: opacity .15s;
            text-decoration: none;
        }
        .reserve-btn:hover { opacity: .88; }

        /* ── Notification bell ───────────────────────────── */
        .notif-bell { position: relative; cursor: pointer; transition: transform .2s; }
        .notif-bell:hover { transform: scale(1.06); }
        .notif-btn-wrap {
            width: 38px; height: 38px; background: white;
            border: 0.5px solid rgba(55,48,163,.16); border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; transition: background .15s;
        }
        .notif-btn-wrap:hover { background: var(--indigo-light); }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white;
            font-size: 9px; font-weight: 700;
            padding: .1rem .3rem; border-radius: 999px;
            min-width: 1rem; text-align: center;
            border: 2px solid var(--slate-bg); line-height: 1.3;
        }

        /* ── Theme toggle ────────────────────────────────── */
        .tgl-row { display: flex; align-items: center; gap: 7px; }
        .tgl {
            width: 40px; height: 22px;
            background: #c7d2fe; border-radius: 999px;
            border: 0.5px solid rgba(55,48,163,.2);
            cursor: pointer; position: relative; transition: background .25s; flex-shrink: 0;
        }
        .tgl::after {
            content: ''; position: absolute;
            top: 2px; left: 2px; width: 14px; height: 14px;
            background: var(--indigo); border-radius: 50%; transition: transform .25s;
        }
        body.dark .tgl::after { transform: translateX(18px); background: #818cf8; }

        /* ── Dark mode ───────────────────────────────────── */
        body.dark { background: #07101f; color: #e8f0fe; }
        body.dark .sidebar-card { background: #0c1a30; border-color: rgba(99,102,241,.1); }
        body.dark .sidebar-header, body.dark .sidebar-footer { border-color: rgba(99,102,241,.1); }
        body.dark .brand-name { color: #e8f0fe; }
        body.dark .nav-link { color: #7fb3e8; }
        body.dark .nav-link:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .nav-link:not(.active) .nav-icon { background: rgba(99,102,241,.1); }
        body.dark .nav-link.active { background: var(--indigo); }
        body.dark .quota-wrap { background: rgba(99,102,241,.06); border-color: rgba(99,102,241,.1); }
        body.dark .quota-track { background: rgba(99,102,241,.15); }
        body.dark .main-area { background: #07101f; }
        body.dark .greeting-name { color: #e8f0fe; }
        body.dark .dash-card, body.dark .stat-card-wrap { background: #0c1a30; border-color: rgba(99,102,241,.1); }
        body.dark .notif-btn-wrap { background: #0c1a30; border-color: rgba(99,102,241,.15); color: #7fb3e8; }
        body.dark .reserve-btn { background: var(--indigo); }
        body.dark .tgl { background: rgba(99,102,241,.25); }
        body.dark .notif-dropdown { background: #0c1a30; border-color: rgba(99,102,241,.15); box-shadow: 0 24px 48px -8px rgba(0,0,0,.5); }
        body.dark .notif-item { border-color: #101e35; }
        body.dark .notif-item.unread { background: rgba(55,48,163,.15); }
        body.dark .notif-item:hover { background: #101e35; }
        body.dark .modal-card { background: #0c1a30; }
        body.dark .login-toast { background: #0c1a30; border-color: rgba(99,102,241,.12); }
        body.dark .stat-val-num { color: #e8f0fe; }
        body.dark .upcoming-pill { background: rgba(55,48,163,.15); border-color: rgba(55,48,163,.35); }
        body.dark .up-name { color: #e8f0fe; }
        body.dark .up-time { color: #a5b4fc; }
        body.dark .up-link { background: #0c1a30; border-color: rgba(99,102,241,.2); color: #818cf8; }
        body.dark .qa-item { background: #0c1a30; border-color: rgba(99,102,241,.12); color: #7fb3e8; }
        body.dark .qa-item:hover { border-color: #818cf8; background: rgba(99,102,241,.1); color: #a5b4fc; }
        body.dark .bk-date { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .bk-day { color: #e8f0fe; }
        body.dark .bk-name { color: #e8f0fe; }
        body.dark .bk-row:hover { background: rgba(99,102,241,.08); }
        body.dark .book-letter-wrap { background: rgba(55,48,163,.2); color: #818cf8; }
        body.dark .book-title-txt { color: #e8f0fe; }
        body.dark .lib-banner { background: #071526; }
        body.dark .search-input { background: #101e35; border-color: rgba(99,102,241,.2); color: #e8f0fe; }
        body.dark .search-input:focus { border-color: #818cf8; background: #0c1a30; }
        body.dark .borrow-row { background: #101e35; border-color: rgba(99,102,241,.1); }
        body.dark .card-icon-wrap { background: rgba(55,48,163,.18); color: #818cf8; }
        body.dark .how-step { border-color: #101e35; }
        body.dark .status-guide-row { border-color: #0f1e38; }
        body.dark .timer-banner.safe { background: rgba(55,48,163,.15); border-color: rgba(55,48,163,.35); color: #a5b4fc; }
        body.dark .timer-banner.warning { background: rgba(180,83,9,.2); border-color: rgba(180,83,9,.4); color: #fcd34d; }
        body.dark .timer-banner.urgent { background: rgba(154,52,18,.2); border-color: rgba(154,52,18,.4); color: #fb923c; }
        body.dark .next-msg { color: #7fb3e8; }
        body.dark .mobile-nav-pill { background: #0c1a30; border-color: rgba(99,102,241,.18); }
        body.dark .mob-nav-item { color: #7fb3e8; }
        body.dark .mob-nav-item:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .how-step-num { background: #3730a3; }
        body.dark .fc-toolbar-title { color: #e8f0fe !important; }
        body.dark .fc-daygrid-day-number { color: #7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color: #7fb3e8; }
        body.dark .fc-day-today { background: rgba(55,48,163,.15) !important; }
        body.dark .fc-daygrid-day:hover { background: rgba(55,48,163,.1) !important; }

        /* ── Cards ───────────────────────────────────────── */
        .dash-card {
            background: white; border-radius: 18px;
            border: 0.5px solid rgba(55,48,163,.09);
        }
        .card-p { padding: 18px 20px; }
        .card-p-lg { padding: 20px 22px; }
        .section-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 12px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .card-icon-wrap { width: 32px; height: 32px; background: var(--indigo-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--indigo); }
        .card-title-txt { font-size: 13px; font-weight: 600; color: #0f172a; letter-spacing: -.1px; }
        .card-sub-txt { font-size: 10px; font-weight: 400; color: #94a3b8; margin-top: 1px; }
        .link-sm { font-size: 10px; font-weight: 700; color: var(--indigo); text-decoration: none; letter-spacing: .04em; text-transform: uppercase; }

        /* ── Flash ───────────────────────────────────────── */
        .flash-success { margin-bottom: 16px; padding: 12px 16px; background: var(--indigo-light); border: 0.5px solid var(--indigo-border); color: var(--indigo); font-weight: 600; border-radius: 14px; display: flex; align-items: center; gap: 10px; font-size: 13px; }

        /* ── Next action card ────────────────────────────── */
        .next-step-card { border-radius: 16px; padding: 13px 15px; border: 1px solid; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
        .next-icon-wrap { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .next-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; margin-bottom: 3px; }
        .next-msg { font-size: 12px; font-weight: 400; color: #475569; line-height: 1.55; }
        .next-cta { display: inline-flex; align-items: center; gap: 5px; margin-top: 9px; padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; color: #fff; text-decoration: none; font-family: var(--font); }

        /* ── Timer banner ────────────────────────────────── */
        .timer-banner { display: none; border-radius: 16px; padding: 13px 16px; margin-bottom: 16px; border: 1px solid; position: relative; overflow: hidden; animation: slideDown .35s cubic-bezier(.34,1.56,.64,1) both; }
        .timer-banner.urgent  { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
        .timer-banner.warning { background: #fefce8; border-color: #fde68a; color: #854d0e; }
        .timer-banner.safe    { background: var(--indigo-light); border-color: var(--indigo-border); color: #312e81; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: none; } }
        .timer-digit { display: inline-flex; flex-direction: column; align-items: center; background: rgba(0,0,0,.07); border-radius: 8px; padding: .2rem .45rem; min-width: 2.4rem; font-variant-numeric: tabular-nums; font-weight: 700; font-size: 1rem; line-height: 1; font-family: var(--mono); }
        .timer-digit span { font-size: .5rem; font-weight: 500; opacity: .6; text-transform: uppercase; letter-spacing: .07em; margin-top: 2px; font-family: var(--font); }
        .timer-pulse { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .timer-progress-wrap { height: 4px; border-radius: 999px; background: rgba(0,0,0,.08); overflow: hidden; margin-top: 10px; }
        .timer-progress-fill { height: 100%; border-radius: 999px; background: currentColor; opacity: .45; transition: width 1s linear; }

        /* ── Upcoming pill ───────────────────────────────── */
        .upcoming-pill { background: var(--indigo-light); border: 0.5px solid var(--indigo-border); border-radius: 14px; padding: 12px 15px; display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .up-icon { width: 36px; height: 36px; background: var(--indigo); border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .up-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: var(--indigo); }
        .up-name { font-size: 13px; font-weight: 600; color: #0f172a; letter-spacing: -.1px; }
        .up-time { font-size: 11px; font-weight: 400; color: #3730a3; }
        .up-link { margin-left: auto; font-size: 11px; font-weight: 600; color: var(--indigo); background: white; border: 0.5px solid var(--indigo-border); border-radius: 8px; padding: 5px 11px; cursor: pointer; white-space: nowrap; text-decoration: none; }

        /* ── Stat cards ──────────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 20px; }
        .stat-card-wrap { background: white; border: 0.5px solid rgba(55,48,163,.09); border-radius: 18px; padding: 16px 18px; }
        .stat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 11px; }
        .stat-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .stat-lbl { font-size: 9px; font-weight: 600; letter-spacing: .15em; text-transform: uppercase; color: #94a3b8; }
        .stat-val-num { font-family: var(--mono); font-size: 28px; font-weight: 500; color: #0f172a; line-height: 1; letter-spacing: -.5px; }
        .stat-hint { font-size: 11px; font-weight: 400; color: #94a3b8; margin-top: 3px; }

        /* ── Main 2-col grid ─────────────────────────────── */
        .grid-main { display: grid; grid-template-columns: minmax(0,1.9fr) minmax(0,1fr); gap: 16px; margin-bottom: 20px; }
        .side-col { display: flex; flex-direction: column; gap: 14px; }

        /* ── FullCalendar overrides ───────────────────────── */
        #calendar { font-size: .8rem; font-family: var(--font); }
        .fc .fc-toolbar { flex-wrap: wrap; gap: .5rem; }
        .fc-toolbar-title { font-size: .9rem !important; font-weight: 700 !important; color: #0f172a !important; font-family: var(--font) !important; }
        .fc-button-primary { background: var(--indigo) !important; border-color: var(--indigo) !important; border-radius: 9px !important; font-family: var(--font) !important; font-weight: 600 !important; font-size: .72rem !important; padding: .28rem .6rem !important; }
        .fc-button-primary:hover { background: #312e81 !important; }
        .fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active { background: #1e1b4b !important; }
        .fc-daygrid-event { border-radius: 5px !important; font-size: .65rem !important; font-weight: 600 !important; padding: 2px 5px !important; border: none !important; cursor: pointer !important; font-family: var(--font) !important; }
        .fc-daygrid-day:hover { background-color: var(--indigo-light) !important; cursor: pointer; }
        .fc-day-today { background: var(--indigo-light) !important; }
        .fc-day-today .fc-daygrid-day-number { color: var(--indigo) !important; font-weight: 700 !important; }
        .fc-daygrid-day-number { font-size: .72rem; font-weight: 500; font-family: var(--font); }
        .fc-col-header-cell-cushion { font-family: var(--font); font-size: .72rem; font-weight: 600; }

        /* ── Cal legend ──────────────────────────────────── */
        .cal-legend { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .leg-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .leg-lbl { font-size: 10px; font-weight: 500; color: #94a3b8; }

        /* ── Quick actions ───────────────────────────────── */
        .qa-item { display: flex; align-items: center; gap: 9px; padding: 9px 11px; border-radius: 11px; border: 0.5px solid rgba(55,48,163,.10); background: white; text-decoration: none; color: #475569; font-size: 12px; font-weight: 500; cursor: pointer; transition: all .15s; letter-spacing: -.1px; }
        .qa-item:hover { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo); }
        .qa-icon { width: 30px; height: 30px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .qa-chevron { margin-left: auto; color: #cbd5e1; }
        .qa-item:hover .qa-chevron { color: var(--indigo); }

        /* ── Booking rows ────────────────────────────────── */
        .bk-row { display: flex; align-items: center; gap: 10px; padding: 8px 8px; border-radius: 11px; text-decoration: none; color: inherit; transition: background .15s; }
        .bk-row:hover { background: var(--indigo-light); }
        .bk-date { width: 36px; height: 36px; background: #f8fafc; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; border: 0.5px solid rgba(55,48,163,.08); }
        .bk-month { font-size: 8px; font-weight: 600; text-transform: uppercase; color: #94a3b8; }
        .bk-day { font-size: 15px; font-weight: 700; color: #0f172a; line-height: 1; font-family: var(--mono); }
        .bk-name { font-size: 12px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -.1px; }
        .bk-time { font-size: 10px; font-weight: 400; color: #94a3b8; margin-top: 1px; font-family: var(--mono); }

        /* ── Status tags ─────────────────────────────────── */
        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 999px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; flex-shrink: 0; }
        .tag-pending   { background: #fef3c7; color: #92400e; }
        .tag-approved  { background: #dcfce7; color: #166534; }
        .tag-claimed   { background: #ede9fe; color: #5b21b6; }
        .tag-declined, .tag-cancelled { background: #fee2e2; color: #991b1b; }
        .tag-unclaimed { background: #fff7ed; color: #c2410c; border: 1px dashed #fdba74; }
        .tag-expired   { background: #f1f5f9; color: #475569; }

        /* ── Notification dropdown ───────────────────────── */
        .notif-dropdown { position: fixed; top: 68px; right: 20px; width: 320px; background: white; border-radius: 18px; box-shadow: 0 20px 40px -8px rgba(0,0,0,.12), 0 0 0 0.5px rgba(55,48,163,.1); z-index: 200; display: none; overflow: hidden; animation: dropIn .18s ease; }
        @keyframes dropIn { from{opacity:0;transform:translateY(-6px) scale(.97)} to{opacity:1;transform:none} }
        .notif-dropdown.show { display: block; }
        .notif-item { padding: .8rem 1rem; border-bottom: 0.5px solid #f1f5f9; transition: background .15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: var(--indigo-light); }
        .notif-item:last-child { border-bottom: none; }

        /* ── Modal ───────────────────────────────────────── */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); backdrop-filter: blur(6px); z-index: 300; padding: 1.25rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; animation: fadeIn .15s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .modal-card { background: white; border-radius: 22px; width: 100%; max-width: 520px; padding: 22px; max-height: calc(100vh - 2.5rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; }
        @keyframes slideUp { from{transform:translateY(14px);opacity:0} to{transform:none;opacity:1} }
        .date-row { display: flex; align-items: center; gap: 12px; padding: .7rem; border-bottom: 0.5px solid #f1f5f9; transition: background .15s; border-radius: 10px; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }

        /* ── Login toast ─────────────────────────────────── */
        .login-toast { position: fixed; bottom: 88px; left: 50%; transform: translateX(-50%) translateY(20px); background: #0f172a; color: white; border-radius: 18px; padding: .8rem 1.1rem; z-index: 500; max-width: 400px; width: calc(100% - 2.5rem); box-shadow: 0 20px 40px -8px rgba(0,0,0,.3); display: flex; align-items: flex-start; gap: 11px; opacity: 0; pointer-events: none; transition: all .4s cubic-bezier(.34,1.56,.64,1); border: 0.5px solid rgba(255,255,255,.07); }
        .login-toast.show { opacity: 1; pointer-events: auto; transform: translateX(-50%) translateY(0); }
        .toast-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-close { margin-left: auto; flex-shrink: 0; width: 22px; height: 22px; border-radius: 7px; background: rgba(255,255,255,.08); border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s; }
        .toast-close:hover { background: rgba(255,255,255,.18); }

        /* ── Library banner ──────────────────────────────── */
        .lib-banner { background: #1e1b4b; border-radius: 18px; padding: 20px 22px; position: relative; overflow: hidden; border: 0.5px solid #312e81; }
        .lib-banner::before { content: ''; position: absolute; right: -20px; top: -20px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.04); pointer-events: none; }
        .lib-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: rgba(165,180,252,.5); margin-bottom: 4px; }
        .lib-title { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: -.4px; }
        .lib-sub { font-size: 11px; font-weight: 400; color: rgba(165,180,252,.6); margin-top: 2px; }
        .lib-browse { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: rgba(255,255,255,.1); border: 0.5px solid rgba(255,255,255,.15); border-radius: 10px; color: #fff; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: none; margin-top: 14px; transition: background .15s; font-family: var(--font); }
        .lib-browse:hover { background: rgba(255,255,255,.18); }
        .lib-stats { display: flex; gap: 14px; margin-top: 14px; padding-top: 14px; border-top: 0.5px solid rgba(255,255,255,.08); }
        .lib-stat { display: flex; align-items: center; gap: 8px; }
        .lib-stat-icon { width: 26px; height: 26px; background: rgba(255,255,255,.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #a5b4fc; }
        .lib-stat-lbl { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: rgba(165,180,252,.5); }
        .lib-stat-val { font-size: 14px; font-weight: 700; color: #fff; font-family: var(--mono); }

        /* ── Book rows ───────────────────────────────────── */
        .book-letter-wrap { width: 34px; height: 34px; border-radius: 10px; background: var(--indigo-light); color: var(--indigo); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0; font-family: var(--mono); }
        .book-title-txt { font-size: 12px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -.1px; }
        .book-author-txt { font-size: 10px; font-weight: 400; color: #94a3b8; }
        .avail-dot { width: 7px; height: 7px; border-radius: 50%; }
        .avail-dot.on { background: #3730a3; }
        .avail-dot.off { background: #f87171; }
        .avail-num { font-size: 9px; font-weight: 600; color: #94a3b8; font-family: var(--mono); }

        /* ── AI search ───────────────────────────────────── */
        .rag-wrap { position: relative; margin: 12px 0 10px; }
        .rag-wrap .rag-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; }
        .search-input { width: 100%; padding: 8px 12px 8px 32px; border-radius: 11px; border: 0.5px solid rgba(55,48,163,.18); background: #f8fafc; font-family: var(--font); font-size: 12px; color: #0f172a; outline: none; transition: border .15s, background .15s; }
        .search-input:focus { border-color: var(--indigo); background: white; box-shadow: 0 0 0 3px rgba(55,48,163,.08); }
        .search-input::placeholder { color: #94a3b8; }
        .ai-suggestion-box { background: var(--indigo-light); border: 0.5px solid var(--indigo-border); border-radius: 14px; padding: .8rem 1rem; margin-top: .7rem; display: none; animation: fadeIn .3s ease; }
        .ai-suggestion-box.show { display: block; }
        .shimmer { height: 10px; border-radius: 5px; background: linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%); background-size: 200% 100%; animation: shimmerAnim 1.4s infinite; margin-bottom: .4rem; }
        @keyframes shimmerAnim { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .find-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 13px; background: var(--indigo); color: #fff; border-radius: 9px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; font-family: var(--font); transition: opacity .15s; }
        .find-btn:hover { opacity: .88; }

        /* ── Borrow rows ─────────────────────────────────── */
        .borrow-row { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 12px; background: #f8fafc; border: 0.5px solid rgba(55,48,163,.08); }
        .borrow-tag-pending  { background: #fef3c7; color: #92400e; }
        .borrow-tag-approved { background: #dcfce7; color: #166534; }
        .borrow-tag-returned { background: #e0e7ff; color: #3730a3; }
        .borrow-tag-rejected { background: #fee2e2; color: #991b1b; }

        /* ── How it works ────────────────────────────────── */
        .how-step { display: flex; align-items: flex-start; gap: 11px; padding: .8rem 0; border-bottom: 0.5px solid #f1f5f9; }
        .how-step:last-child { border-bottom: none; }
        .how-step-num { width: 26px; height: 26px; border-radius: 8px; background: var(--indigo); color: white; font-size: .7rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; font-family: var(--mono); }
        .status-guide-row { display: flex; align-items: center; gap: 10px; padding: .5rem 0; border-bottom: 0.5px solid #f8fafc; }
        .status-guide-row:last-child { border-bottom: none; }

        /* ── Grid lib ────────────────────────────────────── */
        .grid-lib { display: grid; grid-template-columns: minmax(0,1.9fr) minmax(0,1fr); gap: 16px; }
        .fade-in-up { animation: slideUp .4s ease both; }

        @keyframes countUp { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
        .stat-num { animation: countUp .5s ease both; }

        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
            .grid-main, .grid-lib { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php
    $navItems = [
        ['url'=>'/dashboard',        'icon'=>'house',      'label'=>'Dashboard',       'key'=>'dashboard'],
        ['url'=>'/reservation',      'icon'=>'plus',       'label'=>'New Reservation', 'key'=>'reservation'],
        ['url'=>'/reservation-list', 'icon'=>'calendar',   'label'=>'My Reservations', 'key'=>'reservation-list'],
        ['url'=>'/books',            'icon'=>'book-open',  'label'=>'Library',         'key'=>'books'],
        ['url'=>'/profile',          'icon'=>'user',       'label'=>'Profile',         'key'=>'profile'],
    ];
    ?>

    <!-- ── Desktop Sidebar ── -->
    <aside class="desktop-sidebar flex-col w-72 flex-shrink-0 p-5" style="height:100vh;overflow:hidden;">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <div class="brand-tag">Resident Portal</div>
                <div class="brand-name">my<span>Space.</span></div>
            </div>
            <nav class="sidebar-nav" style="display:flex;flex-direction:column;gap:4px;">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']);
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon">
                            <?= icon($item['icon'], 15, $active ? 'white' : '#64748b') ?>
                        </div>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if (isset($remainingReservations)): ?>
                <div class="quota-wrap mx-2 mb-3">
                    <div class="quota-lbl">
                        <span>Monthly Quota</span>
                        <span style="color:#64748b;letter-spacing:0;font-family:var(--mono);"><?= $usedSlots ?>/<?= $maxSlots ?></span>
                    </div>
                    <div class="quota-track">
                        <div class="quota-fill" style="width:<?= ($usedSlots/$maxSlots)*100 ?>%;<?= $remaining===0?'background:#ef4444':($remaining===1?'background:#f59e0b':'') ?>"></div>
                    </div>
                    <p class="quota-note <?= $remaining===0?'text-red-500 font-bold':($remaining===1?'text-amber-500 font-semibold':'') ?>">
                        <?php if ($remaining === 0): ?>⚠ No slots left this month
                        <?php elseif ($remaining === 1): ?>⚡ Only 1 slot remaining
                        <?php else: ?><?= $remaining ?> slot<?= $remaining!=1?'s':'' ?> remaining<?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="nav-link" style="color:#f87171;">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);">
                        <?= icon('logout', 15, '#f87171') ?>
                    </div>
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- ── Mobile Nav ── -->
    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
            ?>
                <a href="<?= base_url($item['url']) ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>">
                    <?= icon($item['icon'], 18, $active ? 'white' : '#64748b') ?>
                    <span class="mob-nav-lbl"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="mob-nav-item mob-nav-logout">
                <?= icon('logout', 18, '#f87171') ?>
                <span class="mob-nav-lbl">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ── Date Modal ── -->
    <div id="dateModal" class="modal-backdrop" onclick="handleModalBackdrop(event)">
        <div class="modal-card">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="font-bold text-slate-900" style="font-size:16px;font-weight:700;letter-spacing:-.3px;" id="modalDateTitle"></h3>
                    <p style="font-size:11px;color:#94a3b8;margin-top:2px;" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" style="width:34px;height:34px;border-radius:10px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <?= icon('x', 14, '#64748b') ?>
                </button>
            </div>
            <div id="modalReservationsList" class="space-y-1"></div>
            <div class="mt-4 text-center hidden" id="modalEmptyMessage" style="font-size:13px;color:#94a3b8;">
                <div style="display:flex;justify-content:center;margin-bottom:8px;color:#cbd5e1;"><?= icon('calendar-x', 28, '#cbd5e1') ?></div>
                No reservations for this date.
            </div>
            <button onclick="closeDateModal()" style="margin-top:18px;width:100%;padding:11px;background:#f1f5f9;border-radius:14px;font-weight:600;color:#475569;border:none;cursor:pointer;font-size:13px;font-family:var(--font);">Close</button>
        </div>
    </div>

    <!-- ── Login Toast ── -->
    <div id="loginToast" class="login-toast">
        <div class="toast-icon" id="toastIcon"></div>
        <div style="flex:1;min-width:0;">
            <p style="font-weight:700;font-size:13px;line-height:1.3;" id="toastTitle"></p>
            <p style="font-size:11px;color:rgba(255,255,255,.65);margin-top:2px;" id="toastBody"></p>
        </div>
        <button class="toast-close" onclick="dismissToast()"><?= icon('x', 11, 'white') ?></button>
    </div>

    <!-- ── Main ── -->
    <main class="main-area">

        <!-- Top bar -->
        <div class="topbar fade-in-up">
            <div>
                <div class="greeting-eyebrow"><?php $hour=(int)date('H'); echo $hour<12?'Good morning':($hour<17?'Good afternoon':'Good evening'); ?></div>
                <div class="greeting-name"><?= esc($user_name) ?></div>
                <div class="greeting-date"><?= date('l, F j, Y') ?></div>
            </div>
            <div class="topbar-right">
                <div class="tgl-row" style="display:none;" id="tglRow">
                    <span id="tgl-lbl" style="color:#94a3b8;"><?= icon('sun', 14, '#94a3b8') ?></span>
                    <div class="tgl" id="tgl" onclick="toggleDark()"></div>
                </div>
                <script>document.getElementById('tglRow').style.display='flex';</script>
                <a href="<?= base_url('/reservation') ?>" class="reserve-btn" style="display:none;" id="reserveBtn">
                    <?= icon('plus', 11, 'white') ?> Reserve
                </a>
                <script>if(window.innerWidth>=640)document.getElementById('reserveBtn').style.display='flex';</script>
                <div class="notif-bell" onclick="toggleNotifications()">
                    <div class="notif-btn-wrap">
                        <?= icon('bell', 15, '#64748b') ?>
                    </div>
                    <span class="notif-badge" id="notificationBadge" style="display:none;">0</span>
                </div>
            </div>
        </div>

        <!-- Notification dropdown -->
        <div id="notificationDropdown" class="notif-dropdown">
            <div style="padding:12px 14px;border-bottom:0.5px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:700;font-size:13px;color:#0f172a;">Notifications</span>
                <button onclick="markAllAsRead()" style="font-size:11px;color:var(--indigo);font-weight:700;background:none;border:none;cursor:pointer;">Mark all read</button>
            </div>
            <div id="notificationList" style="max-height:300px;overflow-y:auto;"></div>
        </div>

        <!-- Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-success fade-in" style="animation:slideUp .4s ease both;">
                <?= icon('check-circle', 15, '#3730a3') ?>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- What to do next -->
        <?php if ($nextAction): $nc = $nextColors[$nextAction['color']]; ?>
            <div class="next-step-card" style="background:<?= $nc['bg'] ?>;border-color:<?= $nc['border'] ?>;animation:slideUp .4s ease both;">
                <div class="next-icon-wrap" style="background:<?= $nc['icon_bg'] ?>;color:<?= $nc['icon_fg'] ?>;">
                    <?= icon($nc['icon'], 15, $nc['icon_fg']) ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="next-eyebrow" style="color:<?= $nc['icon_fg'] ?>;">What to do next</div>
                    <div class="next-msg"><?= $nextAction['msg'] ?></div>
                    <a href="<?= base_url($nextAction['url']) ?>" class="next-cta" style="background:<?= $nc['btn_bg'] ?>;">
                        <?= $nextAction['cta'] ?> <?= icon('arrow-right', 10, 'white') ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Timer countdown banner -->
        <div id="timerBanner" class="timer-banner">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <div id="timerIcon" style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.07);">
                    <?= icon('hourglass', 14, 'currentColor') ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-weight:700;font-size:13px;line-height:1.3;" id="timerTitle">Your reservation ends soon</p>
                    <p style="font-size:11px;opacity:.7;margin-top:2px;" id="timerSub"></p>
                </div>
                <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                    <div class="timer-digit"><span id="tdHv">00</span><span>hrs</span></div>
                    <span style="font-weight:700;font-size:15px;opacity:.45;" class="timer-pulse">:</span>
                    <div class="timer-digit"><span id="tdMv">00</span><span>min</span></div>
                    <span style="font-weight:700;font-size:15px;opacity:.45;" class="timer-pulse">:</span>
                    <div class="timer-digit"><span id="tdSv">00</span><span>sec</span></div>
                </div>
            </div>
            <div class="timer-progress-wrap" id="timerProgressWrap" style="display:none;">
                <div class="timer-progress-fill" id="timerProgressFill" style="width:0%;"></div>
            </div>
        </div>

        <!-- Upcoming reservation -->
        <?php if ($upcoming): ?>
            <div class="upcoming-pill" style="animation:slideUp .4s ease both;">
                <div class="up-icon"><?= icon('ticket', 15, 'white') ?></div>
                <div style="flex:1;min-width:0;">
                    <div class="up-eyebrow">Upcoming Reservation</div>
                    <div class="up-name"><?= esc($upcoming['resource_name'] ?? 'Resource') ?><?php if (!empty($upcoming['pc_number'])): ?> · <span style="font-weight:400;"><?= esc($upcoming['pc_number']) ?></span><?php endif; ?></div>
                    <div class="up-time"><?= date('M j, Y', strtotime($upcoming['reservation_date'])) ?> &nbsp;·&nbsp; <?= date('g:i A', strtotime($upcoming['start_time'])) ?> – <?= date('g:i A', strtotime($upcoming['end_time'])) ?></div>
                </div>
                <a href="<?= base_url('/reservation-list') ?>" class="up-link">View →</a>
            </div>
        <?php endif; ?>

        <!-- Stat cards -->
        <div class="stats-grid">
            <div class="stat-card-wrap">
                <div class="stat-top">
                    <div class="stat-icon" style="background:#eef2ff;"><?= icon('layers', 14, '#3730a3') ?></div>
                    <span class="stat-lbl">Total</span>
                </div>
                <div class="stat-val-num stat-num"><?= $total ?></div>
                <div class="stat-hint">All time</div>
            </div>
            <div class="stat-card-wrap">
                <div class="stat-top">
                    <div class="stat-icon" style="background:#fef3c7;"><?= icon('clock', 14, '#d97706') ?></div>
                    <span class="stat-lbl">Pending</span>
                </div>
                <div class="stat-val-num stat-num" style="color:#d97706;"><?= $pending ?></div>
                <div class="stat-hint">Awaiting review</div>
            </div>
            <div class="stat-card-wrap">
                <div class="stat-top">
                    <div class="stat-icon" style="background:#dcfce7;"><?= icon('check-circle', 14, '#16a34a') ?></div>
                    <span class="stat-lbl">Approved</span>
                </div>
                <div class="stat-val-num stat-num" style="color:#16a34a;"><?= $approved ?></div>
                <div class="stat-hint">Ready to use</div>
            </div>
            <?php if ($unclaimedCount > 0): ?>
                <div class="stat-card-wrap" style="border-color:#fed7aa;">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#fff7ed;"><?= icon('ticket', 14, '#ea580c') ?></div>
                        <span class="stat-lbl">No-show</span>
                    </div>
                    <div class="stat-val-num stat-num" style="color:#ea580c;"><?= $unclaimedCount ?></div>
                    <div class="stat-hint" style="color:#fb923c;">Slot<?= $unclaimedCount>1?'s':'' ?> missed</div>
                </div>
            <?php elseif ($claimedCount > 0): ?>
                <div class="stat-card-wrap">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#ede9fe;"><?= icon('check-double', 14, '#7c3aed') ?></div>
                        <span class="stat-lbl">Claimed</span>
                    </div>
                    <div class="stat-val-num stat-num" style="color:#7c3aed;"><?= $claimedCount ?></div>
                    <div class="stat-hint">Tickets used</div>
                </div>
            <?php else: ?>
                <div class="stat-card-wrap">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#fee2e2;"><?= icon('ban', 14, '#dc2626') ?></div>
                        <span class="stat-lbl">Declined</span>
                    </div>
                    <div class="stat-val-num stat-num" style="color:#dc2626;"><?= $declined ?></div>
                    <div class="stat-hint">Not approved</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main grid: Calendar + Side panel -->
        <div class="grid-main">
            <!-- Calendar -->
            <div class="dash-card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon-wrap"><?= icon('calendar-days', 14, 'var(--indigo)') ?></div>
                        <div>
                            <div class="card-title-txt">Community Schedule</div>
                            <div class="card-sub-txt">Click any date to see reservations</div>
                        </div>
                    </div>
                    <div class="cal-legend" style="display:flex;">
                        <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                            <div style="display:flex;align-items:center;gap:4px;">
                                <div class="leg-dot" style="background:<?= $c ?>;"></div>
                                <span class="leg-lbl"><?= $l ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <!-- Side panel -->
            <div class="side-col">
                <!-- Quick actions -->
                <div class="dash-card card-p">
                    <div class="section-eyebrow">Quick Actions</div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <a href="<?= base_url('/reservation') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#eef2ff;"><?= icon('plus', 13, 'var(--indigo)') ?></div>
                            New Reservation
                            <span class="qa-chevron"><?= icon('chevron-right', 12, '#cbd5e1') ?></span>
                        </a>
                        <a href="<?= base_url('/reservation-list') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#ede9fe;"><?= icon('calendar', 13, '#7c3aed') ?></div>
                            My Reservations
                            <span class="qa-chevron"><?= icon('chevron-right', 12, '#cbd5e1') ?></span>
                        </a>
                        <a href="<?= base_url('/books') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#fef3c7;"><?= icon('book-open', 13, '#d97706') ?></div>
                            Browse Library
                            <span class="qa-chevron"><?= icon('chevron-right', 12, '#cbd5e1') ?></span>
                        </a>
                        <a href="<?= base_url('/profile') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#f3e8ff;"><?= icon('user', 13, '#9333ea') ?></div>
                            View Profile
                            <span class="qa-chevron"><?= icon('chevron-right', 12, '#cbd5e1') ?></span>
                        </a>
                    </div>
                </div>

                <!-- Recent bookings -->
                <div class="dash-card card-p" style="flex:1;">
                    <div class="card-head">
                        <div class="section-eyebrow" style="margin-bottom:0;">Recent Bookings</div>
                        <a href="<?= base_url('/reservation-list') ?>" class="link-sm">View all →</a>
                    </div>
                    <?php if (!empty($processedRecent)): ?>
                        <div>
                            <?php foreach (array_slice($processedRecent, 0, 5) as $res):
                                $s = $res['_status'];
                                $dt = new DateTime($res['reservation_date']);
                            ?>
                                <a href="<?= base_url('/reservation-list') ?>" class="bk-row">
                                    <div class="bk-date">
                                        <div class="bk-month"><?= $dt->format('M') ?></div>
                                        <div class="bk-day"><?= $dt->format('j') ?></div>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="bk-name"><?= esc($res['resource_name'] ?? 'Resource #'.$res['resource_id']) ?></div>
                                        <div class="bk-time"><?= date('g:i A', strtotime($res['start_time'])) ?> – <?= date('g:i A', strtotime($res['end_time'])) ?></div>
                                    </div>
                                    <span class="tag tag-<?= $s ?>"><?= $s==='unclaimed'?'No-show':ucfirst($s) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:24px 12px;">
                            <div style="display:flex;justify-content:center;margin-bottom:8px;color:#e2e8f0;"><?= icon('calendar-x', 30, '#e2e8f0') ?></div>
                            <p style="font-size:12px;color:#94a3b8;">No bookings yet</p>
                            <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:10px;font-size:11px;font-weight:700;color:var(--indigo);text-decoration:none;">
                                <?= icon('plus', 10, 'var(--indigo)') ?> Make your first reservation
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- How it works + Status guide -->
        <?php if (empty($reservations) || $unclaimedCount > 0 || $pending > 0): ?>
        <div class="grid-main" style="margin-bottom:20px;">
            <div class="dash-card card-p">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <div class="card-icon-wrap"><?= icon('list-check', 14, 'var(--indigo)') ?></div>
                    <div>
                        <div class="card-title-txt">How to Reserve</div>
                        <div class="card-sub-txt">Step-by-step guide</div>
                    </div>
                </div>
                <?php $step=1; foreach ([
                    ['Click "New Reservation"','Choose a resource, pick your date and time, and describe your purpose.'],
                    ['Wait for approval','An SK officer will review your request, usually within 24 hours.'],
                    ['Download your e-ticket','Once approved, open My Reservations and download your QR code.'],
                    ['Scan at the entrance','Show your e-ticket to be scanned when you arrive.'],
                    ['Be on time','Slots expire if you don\'t show up. Cancel in advance if plans change.'],
                ] as [$title,$body]): ?>
                    <div class="how-step">
                        <div class="how-step-num"><?= $step++ ?></div>
                        <div>
                            <p style="font-weight:600;font-size:13px;color:#0f172a;letter-spacing:-.1px;"><?= $title ?></p>
                            <p style="font-size:11px;color:#94a3b8;margin-top:2px;"><?= $body ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="dash-card card-p">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <div class="card-icon-wrap"><?= icon('info', 14, 'var(--indigo)') ?></div>
                    <div>
                        <div class="card-title-txt">What Each Status Means</div>
                        <div class="card-sub-txt">So you always know where you stand</div>
                    </div>
                </div>
                <?php foreach ([
                    ['pending',  'clock',        '#fef3c7','#92400e','#d97706','Pending',  'Your request is waiting for SK officer review.'],
                    ['approved', 'check-circle', '#dcfce7','#166534','#16a34a','Approved', 'Confirmed. Get your e-ticket from My Reservations.'],
                    ['claimed',  'check-double', '#ede9fe','#5b21b6','#7c3aed','Claimed',  'Your e-ticket was scanned. Slot successfully used.'],
                    ['unclaimed','ticket',        '#fff7ed','#c2410c','#ea580c','No-show',  'Approved but you didn\'t come. The slot was wasted.'],
                    ['declined', 'ban',           '#fee2e2','#991b1b','#dc2626','Declined', 'Not approved. Try booking a different time.'],
                    ['expired',  'hourglass',     '#f1f5f9','#475569','#64748b','Expired',  'Request wasn\'t approved before the date passed.'],
                ] as [$key,$ico,$bg,$fg,$ic,$label,$desc]): ?>
                    <div class="status-guide-row">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:8px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;flex-shrink:0;width:82px;justify-content:center;background:<?= $bg ?>;color:<?= $fg ?>;">
                            <?= icon($ico, 9, $ic) ?><?= $label ?>
                        </span>
                        <p style="font-size:11px;color:#64748b;"><?= $desc ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Library section -->
        <div class="grid-lib">
            <div style="display:flex;flex-direction:column;gap:16px;">
                <!-- Library banner -->
                <div class="lib-banner">
                    <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div>
                            <div class="lib-eyebrow">Community Library</div>
                            <div class="lib-title"><?= $availableCount ?> books available</div>
                            <div class="lib-sub"><?= $totalBooks ?> total titles in the collection</div>
                        </div>
                        <a href="<?= base_url('/books') ?>" class="lib-browse">
                            <?= icon('book-open', 12, 'white') ?> Browse All
                        </a>
                    </div>
                    <div class="lib-stats" style="position:relative;z-index:1;">
                        <div class="lib-stat">
                            <div class="lib-stat-icon"><?= icon('bookmark', 12, '#a5b4fc') ?></div>
                            <div><div class="lib-stat-lbl">My Borrows</div><div class="lib-stat-val"><?= count($myBorrowings) ?></div></div>
                        </div>
                        <div class="lib-stat">
                            <div class="lib-stat-icon"><?= icon('hourglass', 12, '#fcd34d') ?></div>
                            <div><div class="lib-stat-lbl">Pending</div><div class="lib-stat-val"><?= count(array_filter($myBorrowings,fn($b)=>($b['status']??'')==='pending')) ?></div></div>
                        </div>
                        <div class="lib-stat">
                            <div class="lib-stat-icon"><?= icon('check-circle', 12, '#7dd3fc') ?></div>
                            <div><div class="lib-stat-lbl">Active</div><div class="lib-stat-val"><?= count(array_filter($myBorrowings,fn($b)=>($b['status']??'')==='approved')) ?></div></div>
                        </div>
                    </div>
                </div>

                <!-- AI Book Finder -->
                <div class="dash-card card-p">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:0;">
                        <div class="card-icon-wrap" style="background:#ede9fe;color:#7c3aed;"><?= icon('sparkles', 14, '#7c3aed') ?></div>
                        <div>
                            <div class="card-title-txt">AI Book Finder</div>
                            <div class="card-sub-txt">Describe what you want to read</div>
                        </div>
                    </div>
                    <div class="rag-wrap">
                        <span class="rag-icon"><?= icon('search', 12, '#94a3b8') ?></span>
                        <input type="text" id="dashRagInput" class="search-input" placeholder="e.g. Filipino history, funny stories, adventure for kids…" onkeydown="if(event.key==='Enter') dashRagSearch()">
                    </div>
                    <div id="dashRagSkel" style="display:none;margin-top:.6rem;">
                        <div class="shimmer" style="width:90%;"></div>
                        <div class="shimmer" style="width:72%;"></div>
                        <div class="shimmer" style="width:55%;"></div>
                    </div>
                    <div class="ai-suggestion-box" id="dashRagResult">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                            <?= icon('robot', 12, 'var(--indigo)') ?>
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#3730a3;">Librarian Suggestion</p>
                        </div>
                        <p style="font-size:12px;color:#312e81;line-height:1.6;" id="dashRagText"></p>
                        <div id="dashRagBooks" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:6px;"></div>
                    </div>
                    <div id="dashRagErr" style="display:none;margin-top:6px;font-size:11px;color:#dc2626;font-weight:500;"></div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;">
                        <button onclick="dashRagSearch()" id="dashRagBtn" class="find-btn">
                            <?= icon('sparkles', 11, 'white') ?> Find Books
                        </button>
                        <a href="<?= base_url('/books') ?>" class="link-sm">See full library →</a>
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:16px;">
                <!-- Available books -->
                <div class="dash-card card-p">
                    <div class="card-head">
                        <div class="section-eyebrow" style="margin-bottom:0;">Available Now</div>
                        <a href="<?= base_url('/books') ?>" class="link-sm">All →</a>
                    </div>
                    <?php if (!empty($featuredBooks)): ?>
                        <div>
                            <?php foreach (array_slice($featuredBooks, 0, 5) as $book):
                                $available = (int)($book['available_copies'] ?? 0) > 0;
                            ?>
                            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:0.5px solid rgba(55,48,163,.07);">
                                <a href="<?= base_url('/books') ?>" style="display:contents;">
                                    <div class="book-letter-wrap"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="book-title-txt"><?= esc($book['title']) ?></div>
                                        <div class="book-author-txt"><?= esc($book['author'] ?? 'Unknown') ?></div>
                                    </div>
                                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex-shrink:0;">
                                        <div class="avail-dot <?= $available?'on':'off' ?>"></div>
                                        <div class="avail-num"><?= (int)($book['available_copies'] ?? 0) ?> left</div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:20px 12px;">
                            <div style="display:flex;justify-content:center;margin-bottom:6px;"><?= icon('book-open', 26, '#e2e8f0') ?></div>
                            <p style="font-size:12px;color:#94a3b8;">No books yet</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- My borrows -->
                <div class="dash-card card-p">
                    <div class="card-head">
                        <div class="section-eyebrow" style="margin-bottom:0;">My Borrows</div>
                        <a href="<?= base_url('/books') ?>#mine" class="link-sm">All →</a>
                    </div>
                    <?php $activeBorrows = array_slice(array_values(array_filter($myBorrowings, fn($b)=>in_array($b['status']??'',['approved','pending']))), 0, 4); ?>
                    <?php if (!empty($activeBorrows)): ?>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <?php foreach ($activeBorrows as $borrow): $bs = strtolower($borrow['status'] ?? 'pending'); ?>
                            <div class="borrow-row">
                                <div class="book-letter-wrap" style="width:32px;height:32px;font-size:12px;"><?= mb_strtoupper(mb_substr($borrow['title'] ?? 'B', 0, 1)) ?></div>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-weight:600;font-size:12px;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($borrow['title'] ?? 'Unknown Book') ?></p>
                                    <?php if (!empty($borrow['due_date']) && $bs === 'approved'): ?>
                                        <p style="font-size:10px;color:#94a3b8;font-family:var(--mono);">Due <?= date('M j', strtotime($borrow['due_date'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="tag borrow-tag-<?= $bs ?>"><?= ucfirst($bs) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:18px 12px;">
                            <div style="display:flex;justify-content:center;margin-bottom:6px;"><?= icon('bookmark', 24, '#e2e8f0') ?></div>
                            <p style="font-size:12px;color:#94a3b8;">No active borrows</p>
                            <a href="<?= base_url('/books') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;font-size:11px;font-weight:700;color:var(--indigo);text-decoration:none;">
                                <?= icon('book-open', 10, 'var(--indigo)') ?> Borrow a book
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        const STORAGE_KEY = 'notified_ids_<?= session()->get('user_id') ?>';
        const reservations = <?= json_encode($reservations ?? []) ?>;
        const allReservationsData = <?= json_encode($allReservations ?? []) ?>;
        const _approved = reservations.filter(r => r.status === 'approved' && !r.claimed);
        let notifications = [];

        const getNotifiedIds = () => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch(e) { return []; } };
        const saveNotifiedIds = ids => localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));

        function loadNotifications() {
            const seen = getNotifiedIds();
            notifications = reservations.filter(r => r.status === 'approved').map(r => ({
                id: parseInt(r.id),
                title: 'Reservation Approved',
                msg: `${r.resource_name||'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
                time: r.updated_at || r.created_at || new Date().toISOString(),
                read: seen.includes(parseInt(r.id))
            }));
            updateBadge(); renderNotifs();
        }

        function markAllAsRead() {
            saveNotifiedIds([...new Set([...getNotifiedIds(), ...notifications.map(n => n.id)])]);
            notifications.forEach(n => n.read = true);
            updateBadge(); renderNotifs();
        }

        function markAsRead(id) {
            const ids = getNotifiedIds();
            if (!ids.includes(id)) saveNotifiedIds([...ids, id]);
            const n = notifications.find(n => n.id === id);
            if (n) { n.read = true; updateBadge(); renderNotifs(); }
        }

        function updateBadge() {
            const badge = document.getElementById('notificationBadge');
            const unread = notifications.filter(n => !n.read).length;
            badge.style.display = unread > 0 ? 'block' : 'none';
            badge.textContent = unread > 9 ? '9+' : unread;
        }

        function renderNotifs() {
            const list = document.getElementById('notificationList');
            if (!notifications.length) {
                list.innerHTML = `<div style="text-align:center;padding:28px 16px;"><div style="display:flex;justify-content:center;margin-bottom:8px;color:#e2e8f0;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.8"><path d="M13.73 21a2 2 0 01-3.46 0M18.63 13A17.89 17.89 0 0118 8M6.26 6.26A5.86 5.86 0 006 8c0 7-3 9-3 9h14M18 8a6 6 0 00-9.33-5M1 1l22 22" stroke-linecap="round" stroke-linejoin="round"/></svg></div><p style="font-size:12px;color:#94a3b8;">All caught up!</p></div>`;
                return;
            }
            list.innerHTML = notifications.sort((a,b) => new Date(b.time)-new Date(a.time)).map(n => `
                <div class="notif-item ${!n.read?'unread':''}" onclick="markAsRead(${n.id})">
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="width:32px;height:32px;background:#eef2ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3730a3" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg></div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:700;font-size:13px;color:#0f172a;">${n.title}</p>
                            <p style="font-size:11px;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                            <p style="font-size:10px;color:#94a3b8;margin-top:2px;">${timeAgo(n.time)}</p>
                        </div>
                        ${!n.read ? '<span style="width:7px;height:7px;background:#3730a3;border-radius:50%;flex-shrink:0;margin-top:4px;"></span>' : ''}
                    </div>
                </div>`).join('');
        }

        function toggleNotifications() { document.getElementById('notificationDropdown').classList.toggle('show'); }
        document.addEventListener('click', e => {
            const dd = document.getElementById('notificationDropdown');
            const bell = document.querySelector('.notif-bell');
            if (!bell.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('show');
        });

        const timeAgo = t => {
            const s = Math.floor((Date.now() - new Date(t)) / 1000);
            if (s < 60) return 'Just now';
            if (s < 3600) return `${Math.floor(s/60)}m ago`;
            if (s < 86400) return `${Math.floor(s/3600)}h ago`;
            return `${Math.floor(s/86400)}d ago`;
        };

        function openDateModal(date, items) {
            const d = new Date(date + 'T00:00:00');
            document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
            document.getElementById('modalDateSub').textContent = items.length ? `${items.length} reservation${items.length>1?'s':''}` : '';
            const list = document.getElementById('modalReservationsList'), empty = document.getElementById('modalEmptyMessage');
            list.innerHTML = '';
            if (items.length) {
                empty.classList.add('hidden');
                items.sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).forEach(r => {
                    const isClaimed = r.claimed == 1, s = isClaimed ? 'claimed' : (r.status||'pending').toLowerCase();
                    const colorMap = { approved:'#dcfce7|#166534', pending:'#fef3c7|#92400e', declined:'#fee2e2|#991b1b', canceled:'#fee2e2|#991b1b', claimed:'#ede9fe|#5b21b6' };
                    const [cbg, cfg] = (colorMap[s] || '#f1f5f9|#475569').split('|');
                    const t1 = r.start_time ? r.start_time.substring(0,5) : 'All day', t2 = r.end_time ? ` – ${r.end_time.substring(0,5)}` : '';
                    const row = document.createElement('div');
                    row.className = 'date-row';
                    row.innerHTML = `
                        <div style="width:34px;height:34px;background:#f8fafc;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:0.5px solid #e2e8f0;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:600;font-size:13px;color:#0f172a;">${r.resource_name||'Reserved'}</p>
                            <p style="font-size:11px;color:#94a3b8;margin-top:2px;font-family:'DM Mono',monospace;">${t1}${t2}</p>
                        </div>
                        <span style="display:inline-flex;padding:3px 9px;border-radius:999px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;background:${cbg};color:${cfg};">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
                    list.appendChild(row);
                });
            } else { empty.classList.remove('hidden'); }
            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeDateModal() { document.getElementById('dateModal').classList.remove('show'); document.body.style.overflow = ''; }
        function handleModalBackdrop(e) { if (e.target.classList.contains('modal-backdrop')) closeDateModal(); }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

        function toggleDark() {
            const body = document.body, lbl = document.getElementById('tgl-lbl');
            if (body.classList.contains('dark')) {
                body.classList.remove('dark');
                lbl.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark');
                lbl.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>';
                localStorage.setItem('theme', 'dark');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('dark');
                const lbl = document.getElementById('tgl-lbl');
                if (lbl) lbl.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>';
            }

            if ('Notification' in window) Notification.requestPermission();
            loadNotifications();
            initCountdownBanner();
            showLoginToast();

            const byDate = {};
            allReservationsData.forEach(r => {
                if (!r.reservation_date) return;
                if (!byDate[r.reservation_date]) byDate[r.reservation_date] = [];
                byDate[r.reservation_date].push(r);
            });

            const colorMap = { approved:'#10b981', pending:'#fbbf24', declined:'#f87171', canceled:'#f87171', claimed:'#a855f7' };
            const events = allReservationsData.filter(r => r.reservation_date).map(r => {
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
                    textColor: '#fff'
                };
            });

            const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                headerToolbar: { left:'prev,next', center:'title', right:'today' },
                events,
                height: 380,
                eventDisplay: 'block',
                eventMaxStack: 2,
                dateClick: info => openDateModal(info.dateStr, byDate[info.dateStr] || []),
                eventClick: info => { const d = info.event.startStr.split('T')[0]; openDateModal(d, byDate[d] || []); },
                dayCellDidMount: info => {
                    const d = info.date.toISOString().split('T')[0];
                    const res = byDate[d];
                    if (res?.length) {
                        const badge = document.createElement('div');
                        badge.style.cssText = 'font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:15px;height:15px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;margin-bottom:2px;font-family:DM Mono,monospace;';
                        badge.textContent = res.length;
                        info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);
                    }
                }
            });
            cal.render();
        });

        function initCountdownBanner() {
            const banner = document.getElementById('timerBanner'),
                  titleEl = document.getElementById('timerTitle'),
                  subEl   = document.getElementById('timerSub'),
                  hEl = document.getElementById('tdHv'),
                  mEl = document.getElementById('tdMv'),
                  sEl = document.getElementById('tdSv'),
                  iconEl  = document.getElementById('timerIcon'),
                  progressWrap = document.getElementById('timerProgressWrap'),
                  progressFill = document.getElementById('timerProgressFill');

            const iconSVG = {
                urgent:  '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                warning: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                safe:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',
                bell:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',
            };

            function findTarget() {
                const now = Date.now();
                let active = null, upcoming = null;
                _approved.forEach(r => {
                    if (!r.reservation_date || !r.start_time || !r.end_time) return;
                    const start = new Date(r.reservation_date+'T'+r.start_time).getTime(),
                          end   = new Date(r.reservation_date+'T'+r.end_time).getTime(),
                          minsToStart = (start-now)/60000,
                          minsToEnd   = (end-now)/60000;
                    if (now >= start && now < end && !active) active = {r,start,end,mode:'active',minsLeft:minsToEnd};
                    if (!upcoming && minsToStart > 0 && minsToStart <= 30) upcoming = {r,start,end,mode:'upcoming',minsLeft:minsToStart};
                });
                return active || upcoming || null;
            }

            function tick() {
                const target = findTarget();
                if (!target) { banner.style.display='none'; return; }
                const {r,start,end,mode,minsLeft} = target;
                const now = Date.now();
                const diff = Math.max(0, (mode==='active'?end:start) - now);
                const h = Math.floor(diff/3600000),
                      m = Math.floor((diff%3600000)/60000),
                      s = Math.floor((diff%60000)/1000);
                hEl.textContent = String(h).padStart(2,'0');
                mEl.textContent = String(m).padStart(2,'0');
                sEl.textContent = String(s).padStart(2,'0');
                banner.classList.remove('urgent','warning','safe');
                if (mode === 'active') {
                    if (minsLeft <= 10) { banner.classList.add('urgent'); iconEl.innerHTML = iconSVG.urgent; }
                    else if (minsLeft <= 20) { banner.classList.add('warning'); iconEl.innerHTML = iconSVG.warning; }
                    else { banner.classList.add('safe'); iconEl.innerHTML = iconSVG.safe; }
                    titleEl.textContent = minsLeft <= 10 ? '⚠ Your reservation ends very soon!' : 'Your reservation is active';
                    subEl.textContent = `${r.resource_name||'Resource'} · Ends at ${r.end_time?.substring(0,5)}`;
                    const pct = Math.min(100, Math.max(0, ((now-start)/(end-start))*100));
                    progressWrap.style.display = 'block';
                    progressFill.style.width = pct.toFixed(1) + '%';
                } else {
                    banner.classList.add('safe');
                    iconEl.innerHTML = iconSVG.bell;
                    titleEl.textContent = 'Your reservation starts soon';
                    subEl.textContent = `${r.resource_name||'Resource'} · Starts at ${r.start_time?.substring(0,5)}`;
                    progressWrap.style.display = 'none';
                }
                banner.style.display = 'block';
            }
            tick();
            setInterval(tick, 1000);
        }

        function showLoginToast() {
            const key = 'toast_shown_<?= session()->get('user_id') ?>_' + new Date().toDateString();
            if (sessionStorage.getItem(key)) return;
            sessionStorage.setItem(key, '1');
            const now = Date.now();
            let toastData = null;
            _approved.forEach(r => {
                if (!r.reservation_date || !r.start_time || !r.end_time) return;
                const start = new Date(r.reservation_date+'T'+r.start_time).getTime(),
                      end   = new Date(r.reservation_date+'T'+r.end_time).getTime(),
                      minsToStart = (start-now)/60000,
                      today  = new Date().toDateString(),
                      resDay = new Date(r.reservation_date+'T00:00:00').toDateString();
                if (now >= start && now < end && !toastData)
                    toastData = {icon:'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8"><polygon points="5 3 19 12 5 21 5 3"/></svg>',bg:'#2563eb33',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${r.end_time?.substring(0,5)} — don't forget!`};
                if (!toastData && resDay===today && minsToStart>0 && minsToStart<=120)
                    toastData = {icon:'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',bg:'#d9770633',title:`Reservation in ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${r.start_time?.substring(0,5)} – ${r.end_time?.substring(0,5)}`};
                if (!toastData && resDay===today) {
                    const fmt = t => { const [h,m] = t.split(':'); const hr=+h; return `${hr%12||12}:${m} ${hr<12?'AM':'PM'}`; };
                    toastData = {icon:'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>',bg:'#2563eb33',title:'You have a reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`};
                }
            });
            if (!toastData) return;
            const toast = document.getElementById('loginToast');
            document.getElementById('toastIcon').innerHTML = toastData.icon;
            document.getElementById('toastIcon').style.background = toastData.bg;
            document.getElementById('toastTitle').textContent = toastData.title;
            document.getElementById('toastBody').textContent = toastData.body;
            setTimeout(() => toast.classList.add('show'), 900);
            setTimeout(() => toast.classList.remove('show'), 7900);
        }

        function dismissToast() { document.getElementById('loginToast').classList.remove('show'); }

        async function dashRagSearch() {
            const query = document.getElementById('dashRagInput').value.trim();
            if (query.length < 2) return;
            const skel = document.getElementById('dashRagSkel'),
                  res  = document.getElementById('dashRagResult'),
                  err  = document.getElementById('dashRagErr'),
                  btn  = document.getElementById('dashRagBtn');
            res.classList.remove('show'); err.style.display = 'none'; skel.style.display = 'block'; btn.disabled = true;
            try {
                const r = await fetch('/rag/suggest', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' },
                    body: JSON.stringify({query})
                });
                const d = await r.json();
                skel.style.display = 'none'; btn.disabled = false;
                if (d.message && !d.suggestion) { err.textContent = d.message; err.style.display = 'block'; return; }
                if (d.error && !d.books) { err.textContent = d.error; err.style.display = 'block'; return; }
                document.getElementById('dashRagText').textContent = d.suggestion || '';
                const booksRow = document.getElementById('dashRagBooks');
                booksRow.innerHTML = '';
                (d.books || []).slice(0,4).forEach(b => {
                    const avail = (b.available_copies||0) > 0;
                    const chip = document.createElement('a');
                    chip.href = '/books';
                    chip.style.cssText = `display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:9px;font-size:10px;font-weight:600;border:0.5px solid;transition:all .15s;text-decoration:none;font-family:'DM Sans',sans-serif;${avail?'background:white;border-color:#c7d2fe;color:#3730a3;':'background:#f8fafc;border-color:#e2e8f0;color:#94a3b8;'}`;
                    chip.innerHTML = `<svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/></svg>${b.title}${avail?'':' <span style="font-size:8px;">(out)</span>'}`;
                    booksRow.appendChild(chip);
                });
                res.classList.add('show');
            } catch(e) {
                skel.style.display = 'none'; btn.disabled = false;
                err.textContent = 'Network error. Try again.'; err.style.display = 'block';
            }
        }
    </script>
    <?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>