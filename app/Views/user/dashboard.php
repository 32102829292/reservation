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
    'amber'  => ['bg'=>'#fffbeb','border'=>'#fde68a','icon_bg'=>'#fef3c7','icon_fg'=>'#d97706','btn_bg'=>'#d97706','icon'=>'fa-clock'],
    'blue'   => ['bg'=>'#eff6ff','border'=>'#bfdbfe','icon_bg'=>'#dbeafe','icon_fg'=>'#2563eb','btn_bg'=>'#2563eb','icon'=>'fa-ticket'],
    'orange' => ['bg'=>'#fff7ed','border'=>'#fed7aa','icon_bg'=>'#ffedd5','icon_fg'=>'#ea580c','btn_bg'=>'#ea580c','icon'=>'fa-triangle-exclamation'],
    'slate'  => ['bg'=>'#f8fafc','border'=>'#e2e8f0','icon_bg'=>'#f1f5f9','icon_fg'=>'#64748b','btn_bg'=>'#64748b','icon'=>'fa-calendar-xmark'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | <?= esc($user_name) ?></title>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <meta name="theme-color" content="#2563eb">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&family=Sora:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        /* ── Reset & Tokens ─────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue: #2563eb; --blue-light: #eff6ff; --blue-mid: #bfdbfe; --blue-dark: #1e3a8a;
            --slate-bg: #f0f4fd;
            --font: 'Inter', sans-serif;
            --font-display: 'Sora', sans-serif;
        }
        html { height: 100%; }
        body {
            font-family: var(--font);
            background-color: var(--slate-bg);
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
            border-radius: 24px;
            border: 1px solid rgba(37,99,235,.10);
            height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
            box-shadow: 0 1px 3px rgba(37,99,235,.06);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            width: 100%;
        }
        .sidebar-header {
            flex-shrink: 0;
            padding: 18px 16px 14px;
            border-bottom: 1px solid rgba(37,99,235,.08);
        }
        .sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px; }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 3px; }
        .sidebar-footer { flex-shrink: 0; padding: 12px; border-top: 1px solid rgba(37,99,235,.08); }

        .brand-tag { font-size: 9px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #94a3b8; margin-bottom: 3px; }
        .brand-name { font-family: var(--font-display); font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -.3px; }
        .brand-name span { color: var(--blue); }

        /* ── Nav links ───────────────────────────────────── */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 12px;
            font-size: 13px; font-weight: 600; letter-spacing: -.1px;
            color: #64748b; text-decoration: none;
            transition: all .15s;
        }
        .nav-link:hover { background: var(--blue-light); color: var(--blue); }
        .nav-link.active { background: var(--blue); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,.25); }
        .nav-icon { width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
        .nav-link.active .nav-icon { background: rgba(255,255,255,.18); }
        .nav-link:not(.active) .nav-icon { background: #f1f5f9; }
        .nav-link:hover:not(.active) .nav-icon { background: #dbeafe; }

        /* ── Quota bar ───────────────────────────────────── */
        .quota-wrap { margin: 8px 4px 6px; background: #f8fafc; border-radius: 12px; padding: 11px 12px; border: 1px solid rgba(37,99,235,.08); }
        .quota-lbl { display: flex; justify-content: space-between; font-size: 9px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: #94a3b8; margin-bottom: 7px; }
        .quota-track { height: 4px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .quota-fill { height: 100%; border-radius: 999px; background: var(--blue); transition: width .6s cubic-bezier(.34,1.56,.64,1); }
        .quota-note { font-size: 10px; font-weight: 500; color: #94a3b8; margin-top: 6px; }

        /* ── Mobile nav ──────────────────────────────────── */
        .mobile-nav-pill {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 480px;
            background: rgba(30,58,138,.97); backdrop-filter: blur(16px);
            border-radius: 22px; padding: 6px; z-index: 100;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,.4);
        }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* ── Main layout ─────────────────────────────────── */
        .main-area {
            flex: 1; min-width: 0;
            padding: 20px 24px 80px;
            height: 100vh; overflow-y: auto;
        }

        /* ── Top bar ─────────────────────────────────────── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; gap: 16px; }
        .greeting-eyebrow { font-size: 10px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .greeting-name { font-family: var(--font-display); font-size: 22px; font-weight: 800; color: #0f172a; letter-spacing: -.4px; line-height: 1.1; }
        .greeting-date { font-size: 12px; font-weight: 400; color: #94a3b8; margin-top: 3px; }
        .topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; margin-top: 4px; }

        .reserve-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: var(--blue); color: #fff;
            border-radius: 10px; font-size: 12px; font-weight: 600;
            border: none; cursor: pointer; font-family: var(--font);
            letter-spacing: -.1px; transition: opacity .15s;
            box-shadow: 0 2px 8px rgba(37,99,235,.25);
        }
        .reserve-btn:hover { opacity: .88; }

        /* ── Notification bell ───────────────────────────── */
        .notif-bell { position: relative; cursor: pointer; transition: transform .2s; }
        .notif-bell:hover { transform: scale(1.06); }
        .notif-btn-wrap {
            width: 38px; height: 38px; background: white;
            border: 1px solid rgba(37,99,235,.16); border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; font-size: 14px;
            transition: background .15s;
        }
        .notif-btn-wrap:hover { background: var(--blue-light); }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white;
            font-size: 9px; font-weight: 800;
            padding: .1rem .3rem; border-radius: 999px;
            min-width: 1rem; text-align: center;
            border: 2px solid var(--slate-bg); line-height: 1.3;
        }

        /* ── Theme toggle ────────────────────────────────── */
        .tgl-row { display: flex; align-items: center; gap: 7px; }
        .tgl-label { font-size: 12px; color: #94a3b8; }
        .tgl {
            width: 40px; height: 22px;
            background: #bfdbfe; border-radius: 999px;
            border: 1.5px solid rgba(37,99,235,.20);
            cursor: pointer; position: relative;
            transition: background .25s; flex-shrink: 0;
        }
        .tgl::after {
            content: ''; position: absolute;
            top: 2px; left: 2px;
            width: 14px; height: 14px;
            background: var(--blue); border-radius: 50%;
            transition: transform .25s;
        }
        body.dark .tgl::after { transform: translateX(18px); background: #60a5fa; }

        /* ── Dark mode overrides ─────────────────────────── */
        body.dark {
            background: #06101f; color: #e8f0fe;
        }
        body.dark .sidebar-card {
            background: #0c1a30; border-color: rgba(96,165,250,.10);
        }
        body.dark .sidebar-header, body.dark .sidebar-footer {
            border-color: rgba(96,165,250,.10);
        }
        body.dark .brand-name { color: #e8f0fe; }
        body.dark .nav-link { color: #7fb3e8; }
        body.dark .nav-link:hover { background: rgba(96,165,250,.12); color: #bfdbfe; }
        body.dark .nav-link:not(.active) .nav-icon { background: rgba(96,165,250,.10); }
        body.dark .nav-link.active { background: var(--blue); color: #fff; }
        body.dark .quota-wrap { background: rgba(96,165,250,.06); border-color: rgba(96,165,250,.10); }
        body.dark .quota-track { background: rgba(96,165,250,.15); }
        body.dark .sb-logout { color: rgba(248,113,113,.70); }
        body.dark .sb-logout:hover { background: rgba(239,68,68,.10); color: #f87171; }
        body.dark .main-area { background: #06101f; }
        body.dark .greeting-name { color: #e8f0fe; }
        body.dark .card-base, body.dark .stat-card-wrap, body.dark .dash-card { background: #0c1a30; border-color: rgba(96,165,250,.10); }
        body.dark .notif-btn-wrap { background: #0c1a30; border-color: rgba(96,165,250,.15); color: #7fb3e8; }
        body.dark .notif-btn-wrap:hover { background: rgba(96,165,250,.12); }
        body.dark .reserve-btn { background: #2563eb; }
        body.dark .tgl { background: rgba(96,165,250,.25); }
        body.dark .notif-dropdown { background: #0c1a30; border-color: rgba(96,165,250,.15); box-shadow: 0 24px 48px -8px rgba(0,0,0,.5); }
        body.dark .notif-item { border-color: #101e35; }
        body.dark .notif-item.unread { background: rgba(37,99,235,.12); }
        body.dark .notif-item:hover { background: #101e35; }
        body.dark .modal-card { background: #0c1a30; }
        body.dark .login-toast { background: #0c1a30; border-color: rgba(96,165,250,.12); }
        body.dark .fc-toolbar-title { color: #e8f0fe !important; }
        body.dark .fc-daygrid-day-number { color: #7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color: #7fb3e8; }
        body.dark .fc-day-today { background: rgba(37,99,235,.15) !important; }
        body.dark .fc-daygrid-day:hover { background: rgba(37,99,235,.10) !important; }
        body.dark .stat-val-num { color: #e8f0fe; }
        body.dark .upcoming-pill { background: rgba(37,99,235,.15); border-color: rgba(37,99,235,.30); }
        body.dark .up-name { color: #e8f0fe; }
        body.dark .up-time { color: #93c5fd; }
        body.dark .up-link { background: #0c1a30; border-color: rgba(96,165,250,.20); color: #60a5fa; }
        body.dark .qa-item { background: #0c1a30; border-color: rgba(96,165,250,.12); color: #7fb3e8; }
        body.dark .qa-item:hover { border-color: #60a5fa; background: rgba(96,165,250,.10); color: #60a5fa; }
        body.dark .bk-date { background: #101e35; border-color: rgba(96,165,250,.10); }
        body.dark .bk-day { color: #e8f0fe; }
        body.dark .bk-name { color: #e8f0fe; }
        body.dark .bk-row:hover { background: rgba(96,165,250,.08); }
        body.dark .book-letter-wrap { background: rgba(37,99,235,.20); color: #60a5fa; }
        body.dark .book-title-txt { color: #e8f0fe; }
        body.dark .lib-banner { background: #071526; }
        body.dark .search-input { background: #101e35; border-color: rgba(96,165,250,.20); color: #e8f0fe; }
        body.dark .search-input:focus { border-color: #60a5fa; background: #0c1a30; }
        body.dark .borrow-row { background: #101e35; border-color: rgba(96,165,250,.10); }
        body.dark .card-icon-wrap { background: rgba(37,99,235,.18); color: #60a5fa; }
        body.dark .how-step-num { background: #1d4ed8; }
        body.dark .how-step { border-color: #101e35; }
        body.dark .status-guide-row { border-color: #0f1e38; }
        body.dark .timer-banner.safe { background: rgba(37,99,235,.15); border-color: rgba(37,99,235,.35); color: #93c5fd; }
        body.dark .timer-banner.warning { background: rgba(180,83,9,.2); border-color: rgba(180,83,9,.4); color: #fcd34d; }
        body.dark .timer-banner.urgent { background: rgba(154,52,18,.2); border-color: rgba(154,52,18,.4); color: #fb923c; }

        /* ── Cards ───────────────────────────────────────── */
        .dash-card {
            background: white; border-radius: 20px;
            border: 1px solid rgba(37,99,235,.09);
            box-shadow: 0 1px 3px rgba(37,99,235,.05);
        }
        .card-p { padding: 18px 20px; }
        .card-p-lg { padding: 20px 22px; }

        .section-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 12px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .card-icon-wrap { width: 32px; height: 32px; background: var(--blue-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: var(--blue); flex-shrink: 0; }
        .card-title-txt { font-size: 13px; font-weight: 700; color: #0f172a; letter-spacing: -.1px; }
        .card-sub-txt { font-size: 10px; font-weight: 400; color: #94a3b8; margin-top: 1px; }
        .link-sm { font-size: 10px; font-weight: 700; color: var(--blue); text-decoration: none; letter-spacing: .04em; text-transform: uppercase; }

        /* ── Flash ───────────────────────────────────────── */
        .flash-success { margin-bottom: 16px; padding: 12px 16px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; font-weight: 600; border-radius: 14px; display: flex; align-items: center; gap: 10px; font-size: 13px; }

        /* ── Next action card ────────────────────────────── */
        .next-step-card { border-radius: 16px; padding: 13px 15px; border: 1.5px solid; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
        .next-icon-wrap { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
        .next-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; margin-bottom: 3px; }
        .next-msg { font-size: 12px; font-weight: 400; color: #475569; line-height: 1.55; }
        body.dark .next-msg { color: #7fb3e8; }
        .next-cta {
            display: inline-flex; align-items: center; gap: 4px;
            margin-top: 9px; padding: 5px 12px; border-radius: 8px;
            font-size: 11px; font-weight: 600; color: #fff;
            text-decoration: none; font-family: var(--font);
        }

        /* ── Timer banner ────────────────────────────────── */
        .timer-banner { display: none; border-radius: 16px; padding: 13px 16px; margin-bottom: 16px; border: 1.5px solid; position: relative; overflow: hidden; animation: slideDown .35s cubic-bezier(.34,1.56,.64,1) both; }
        .timer-banner.urgent  { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
        .timer-banner.warning { background: #fefce8; border-color: #fde68a; color: #854d0e; }
        .timer-banner.safe    { background: #eff6ff; border-color: #bfdbfe; color: #1e3a8a; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: none; } }
        .timer-digit { display: inline-flex; flex-direction: column; align-items: center; background: rgba(0,0,0,.07); border-radius: 8px; padding: .2rem .45rem; min-width: 2.4rem; font-variant-numeric: tabular-nums; font-weight: 800; font-size: 1rem; line-height: 1; font-family: var(--font-display); }
        .timer-digit span { font-size: .5rem; font-weight: 600; opacity: .6; text-transform: uppercase; letter-spacing: .07em; margin-top: 2px; font-family: var(--font); }
        .timer-pulse { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .timer-progress-wrap { height: 4px; border-radius: 999px; background: rgba(0,0,0,.08); overflow: hidden; margin-top: 10px; }
        .timer-progress-fill { height: 100%; border-radius: 999px; background: currentColor; opacity: .45; transition: width 1s linear; }

        /* ── Upcoming pill ───────────────────────────────── */
        .upcoming-pill { background: var(--blue-light); border: 1px solid var(--blue-mid); border-radius: 14px; padding: 12px 15px; display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .up-icon { width: 36px; height: 36px; background: var(--blue); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 13px; color: #fff; flex-shrink: 0; }
        .up-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: var(--blue); }
        .up-name { font-size: 13px; font-weight: 600; color: #0f172a; letter-spacing: -.1px; }
        .up-time { font-size: 11px; font-weight: 400; color: #1e40af; }
        .up-link { margin-left: auto; font-size: 11px; font-weight: 600; color: var(--blue); background: white; border: 1px solid var(--blue-mid); border-radius: 8px; padding: 5px 11px; cursor: pointer; white-space: nowrap; text-decoration: none; }

        /* ── Stat cards ──────────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 20px; }
        .stat-card-wrap { background: white; border: 1px solid rgba(37,99,235,.09); border-radius: 20px; padding: 16px 18px; box-shadow: 0 1px 3px rgba(37,99,235,.04); }
        .stat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 11px; }
        .stat-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
        .stat-lbl { font-size: 9px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: #94a3b8; }
        .stat-val-num { font-family: var(--font-display); font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -.5px; }
        .stat-hint { font-size: 11px; font-weight: 400; color: #94a3b8; margin-top: 3px; }

        /* ── Main 2-col grid ─────────────────────────────── */
        .grid-main { display: grid; grid-template-columns: minmax(0,1.9fr) minmax(0,1fr); gap: 16px; margin-bottom: 20px; }
        .side-col { display: flex; flex-direction: column; gap: 14px; }

        /* ── FullCalendar overrides ───────────────────────── */
        #calendar { font-size: .8rem; font-family: var(--font); }
        .fc .fc-toolbar { flex-wrap: wrap; gap: .5rem; }
        .fc-toolbar-title { font-size: .9rem !important; font-weight: 700 !important; color: #0f172a !important; font-family: var(--font-display) !important; }
        .fc-button-primary { background: var(--blue) !important; border-color: var(--blue) !important; border-radius: 9px !important; font-family: var(--font) !important; font-weight: 600 !important; font-size: .72rem !important; padding: .28rem .6rem !important; }
        .fc-button-primary:hover { background: #1d4ed8 !important; }
        .fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active { background: #1e40af !important; }
        .fc-daygrid-event { border-radius: 5px !important; font-size: .65rem !important; font-weight: 700 !important; padding: 2px 5px !important; border: none !important; cursor: pointer !important; font-family: var(--font) !important; }
        .fc-daygrid-day:hover { background-color: #eff6ff !important; cursor: pointer; }
        .fc-day-today { background: #eff6ff !important; }
        .fc-day-today .fc-daygrid-day-number { color: var(--blue) !important; font-weight: 700 !important; }
        .fc-daygrid-day-number { font-size: .72rem; font-weight: 500; font-family: var(--font); }
        .fc-col-header-cell-cushion { font-family: var(--font); font-size: .72rem; font-weight: 600; }

        /* ── Cal legend ──────────────────────────────────── */
        .cal-legend { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .leg-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .leg-lbl { font-size: 10px; font-weight: 500; color: #94a3b8; }

        /* ── Quick actions ───────────────────────────────── */
        .qa-item { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 11px; border: 1px solid rgba(37,99,235,.10); background: white; text-decoration: none; color: #475569; font-size: 12px; font-weight: 600; cursor: pointer; transition: all .15s; letter-spacing: -.1px; }
        .qa-item:hover { border-color: var(--blue); background: var(--blue-light); color: var(--blue); }
        .qa-icon { width: 28px; height: 28px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0; }
        .qa-chevron { margin-left: auto; font-size: 9px; color: #cbd5e1; }
        .qa-item:hover .qa-chevron { color: var(--blue); }

        /* ── Booking rows ────────────────────────────────── */
        .bk-row { display: flex; align-items: center; gap: 10px; padding: 8px 8px; border-radius: 11px; text-decoration: none; color: inherit; transition: background .15s; }
        .bk-row:hover { background: var(--blue-light); }
        .bk-date { width: 36px; height: 36px; background: #f8fafc; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid rgba(37,99,235,.08); }
        .bk-month { font-size: 8px; font-weight: 700; text-transform: uppercase; color: #94a3b8; }
        .bk-day { font-size: 15px; font-weight: 800; color: #0f172a; line-height: 1; font-family: var(--font-display); }
        .bk-name { font-size: 12px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -.1px; }
        .bk-time { font-size: 10px; font-weight: 400; color: #94a3b8; margin-top: 1px; }

        /* ── Status tags ─────────────────────────────────── */
        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 999px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; flex-shrink: 0; }
        .tag-pending   { background: #fef3c7; color: #92400e; }
        .tag-approved  { background: #dcfce7; color: #166534; }
        .tag-claimed   { background: #f3e8ff; color: #6b21a8; }
        .tag-declined, .tag-cancelled { background: #fee2e2; color: #991b1b; }
        .tag-unclaimed { background: #fff7ed; color: #c2410c; border: 1px dashed #fdba74; }
        .tag-expired   { background: #f1f5f9; color: #475569; }

        /* ── Notification dropdown ───────────────────────── */
        .notif-dropdown { position: fixed; top: 68px; right: 20px; width: 320px; background: white; border-radius: 18px; box-shadow: 0 20px 40px -8px rgba(0,0,0,.14), 0 0 0 1px rgba(0,0,0,.05); z-index: 200; display: none; overflow: hidden; animation: dropIn .18s ease; }
        @keyframes dropIn { from{opacity:0;transform:translateY(-6px) scale(.97)} to{opacity:1;transform:none} }
        .notif-dropdown.show { display: block; }
        .notif-item { padding: .8rem 1rem; border-bottom: 1px solid #f1f5f9; transition: background .15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #eff6ff; }
        .notif-item:last-child { border-bottom: none; }

        /* ── Modal ───────────────────────────────────────── */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); backdrop-filter: blur(6px); z-index: 300; padding: 1.25rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; animation: fadeIn .15s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .modal-card { background: white; border-radius: 24px; width: 100%; max-width: 520px; padding: 22px; max-height: calc(100vh - 2.5rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; }
        @keyframes slideUp { from{transform:translateY(14px);opacity:0} to{transform:none;opacity:1} }
        .date-row { display: flex; align-items: center; gap: 12px; padding: .7rem; border-bottom: 1px solid #f1f5f9; transition: background .15s; border-radius: 10px; }
        .date-row:hover { background: #f8fafc; }
        .date-row:last-child { border-bottom: none; }

        /* ── Login toast ─────────────────────────────────── */
        .login-toast { position: fixed; bottom: 88px; left: 50%; transform: translateX(-50%) translateY(20px); background: #0f172a; color: white; border-radius: 18px; padding: .8rem 1.1rem; z-index: 500; max-width: 400px; width: calc(100% - 2.5rem); box-shadow: 0 20px 40px -8px rgba(0,0,0,.3); display: flex; align-items: flex-start; gap: 11px; opacity: 0; pointer-events: none; transition: all .4s cubic-bezier(.34,1.56,.64,1); border: 1px solid rgba(255,255,255,.07); }
        .login-toast.show { opacity: 1; pointer-events: auto; transform: translateX(-50%) translateY(0); }
        .toast-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .85rem; }
        .toast-close { margin-left: auto; flex-shrink: 0; width: 22px; height: 22px; border-radius: 7px; background: rgba(255,255,255,.08); border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: .65rem; transition: background .15s; }
        .toast-close:hover { background: rgba(255,255,255,.18); }

        /* ── Library banner ──────────────────────────────── */
        .lib-banner { background: var(--blue-dark); border-radius: 20px; padding: 20px 22px; position: relative; overflow: hidden; border: 1px solid #1d4ed8; }
        .lib-banner::before { content: ''; position: absolute; right: -20px; top: -20px; width: 130px; height: 130px; border-radius: 50%; background: rgba(255,255,255,.04); pointer-events: none; }
        .lib-eyebrow { font-size: 9px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: rgba(148,197,253,.55); margin-bottom: 4px; }
        .lib-title { font-family: var(--font-display); font-size: 18px; font-weight: 800; color: #fff; letter-spacing: -.3px; }
        .lib-sub { font-size: 11px; font-weight: 400; color: rgba(148,197,253,.65); margin-top: 2px; }
        .lib-browse { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.16); border-radius: 10px; color: #fff; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: none; margin-top: 14px; transition: background .15s; font-family: var(--font); }
        .lib-browse:hover { background: rgba(255,255,255,.20); }
        .lib-stats { display: flex; gap: 14px; margin-top: 14px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,.08); }
        .lib-stat { display: flex; align-items: center; gap: 8px; }
        .lib-stat-icon { width: 26px; height: 26px; background: rgba(255,255,255,.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #93c5fd; }
        .lib-stat-lbl { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: .1em; color: rgba(148,197,253,.50); }
        .lib-stat-val { font-size: 13px; font-weight: 700; color: #fff; font-family: var(--font-display); }

        /* ── Book rows ───────────────────────────────────── */
        .book-letter-wrap { width: 34px; height: 34px; border-radius: 10px; background: var(--blue-light); color: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0; font-family: var(--font-display); }
        .book-title-txt { font-size: 12px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -.1px; }
        .book-author-txt { font-size: 10px; font-weight: 400; color: #94a3b8; }
        .avail-dot { width: 7px; height: 7px; border-radius: 50%; }
        .avail-dot.on { background: #3b82f6; }
        .avail-dot.off { background: #f87171; }
        .avail-num { font-size: 9px; font-weight: 600; color: #94a3b8; }

        /* ── AI search ───────────────────────────────────── */
        .rag-wrap { position: relative; margin: 12px 0 10px; }
        .rag-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #94a3b8; pointer-events: none; }
        .search-input { width: 100%; padding: 8px 12px 8px 30px; border-radius: 11px; border: 1.5px solid rgba(37,99,235,.18); background: #f8fafc; font-family: var(--font); font-size: 12px; font-weight: 400; color: #0f172a; outline: none; transition: border .15s, background .15s; }
        .search-input:focus { border-color: var(--blue); background: white; box-shadow: 0 0 0 3px rgba(37,99,235,.08); }
        .search-input::placeholder { color: #94a3b8; }
        .ai-suggestion-box { background: var(--blue-light); border: 1.5px solid var(--blue-mid); border-radius: 14px; padding: .8rem 1rem; margin-top: .7rem; display: none; animation: fadeIn .3s ease; }
        .ai-suggestion-box.show { display: block; }
        .shimmer { height: 10px; border-radius: 5px; background: linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%); background-size: 200% 100%; animation: shimmerAnim 1.4s infinite; margin-bottom: .4rem; }
        @keyframes shimmerAnim { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .find-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 13px; background: var(--blue); color: #fff; border-radius: 9px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; font-family: var(--font); transition: opacity .15s; }
        .find-btn:hover { opacity: .88; }

        /* ── Borrow rows ─────────────────────────────────── */
        .borrow-row { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 12px; background: #f8fafc; border: 1px solid rgba(37,99,235,.08); }
        .borrow-tag-pending  { background: #fef3c7; color: #92400e; }
        .borrow-tag-approved { background: #dcfce7; color: #166534; }
        .borrow-tag-returned { background: #dbeafe; color: #1e40af; }
        .borrow-tag-rejected { background: #fee2e2; color: #991b1b; }

        /* ── How it works ────────────────────────────────── */
        .how-step { display: flex; align-items: flex-start; gap: 11px; padding: .8rem 0; border-bottom: 1px solid #f1f5f9; }
        .how-step:last-child { border-bottom: none; }
        .how-step-num { width: 26px; height: 26px; border-radius: 8px; background: var(--blue); color: white; font-size: .7rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .status-guide-row { display: flex; align-items: center; gap: 10px; padding: .5rem 0; border-bottom: 1px solid #f8fafc; }
        .status-guide-row:last-child { border-bottom: none; }

        /* ── Fairness bar ────────────────────────────────── */
        .fairness-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .fairness-fill { height: 100%; border-radius: 999px; background: var(--blue); transition: width .6s cubic-bezier(.34,1.56,.64,1); }

        /* ── Grid lib ────────────────────────────────────── */
        .grid-lib { display: grid; grid-template-columns: minmax(0,1.9fr) minmax(0,1fr); gap: 16px; }

        /* ── Fade animations ─────────────────────────────── */
        .fade-in   { animation: fadeIn .4s ease both; }
        .fade-in-up { animation: slideUp .4s ease both; }
        @keyframes countUp { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
        .stat-num { animation: countUp .5s ease both; }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
            .grid-main, .grid-lib { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php
    $navItems = [
        ['url'=>'/dashboard',        'icon'=>'fa-house',           'label'=>'Dashboard',       'key'=>'dashboard'],
        ['url'=>'/reservation',      'icon'=>'fa-plus',            'label'=>'New Reservation', 'key'=>'reservation'],
        ['url'=>'/reservation-list', 'icon'=>'fa-calendar',        'label'=>'My Reservations', 'key'=>'reservation-list'],
        ['url'=>'/books',            'icon'=>'fa-book-open',       'label'=>'Library',         'key'=>'books'],
        ['url'=>'/profile',          'icon'=>'fa-regular fa-user', 'label'=>'Profile',         'key'=>'profile'],
    ];
    ?>

    <!-- ── Sidebar ── -->
    <aside class="hidden lg:flex flex-col w-72 flex-shrink-0 p-5" style="height:100vh;overflow:hidden;">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <div class="brand-tag">Resident Portal</div>
                <div class="brand-name">my<span>Space.</span></div>
            </div>
            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']);
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                        <div class="nav-icon">
                            <i class="fa-solid <?= $item['icon'] ?>" style="font-size:13px;"></i>
                        </div>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if (isset($remainingReservations)): ?>
                <div class="quota-wrap mx-2 mb-3">
                    <div class="quota-lbl">
                        <span>Monthly Quota</span>
                        <span style="color:#64748b;letter-spacing:0;"><?= $usedSlots ?>/<?= $maxSlots ?></span>
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
                <a href="<?= base_url('/logout') ?>" class="sb-logout nav-link" style="color:#f87171;">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);">
                        <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:13px;"></i>
                    </div>
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- ── Mobile Nav ── -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $cls = ($page==$item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
                    <i class="fa-solid <?= $item['icon'] ?>" style="font-size:16px;"></i>
                    <span style="font-size:9px;margin-top:3px;text-align:center;white-space:nowrap;"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0" style="color:rgba(248,113,113,.8);">
                <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:16px;"></i>
                <span style="font-size:9px;margin-top:3px;">Logout</span>
            </a>
        </div>
    </nav>

    <!-- ── Date Modal ── -->
    <div id="dateModal" class="modal-backdrop" onclick="handleModalBackdrop(event)">
        <div class="modal-card">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="font-bold text-slate-900" style="font-size:16px;font-family:var(--font-display);" id="modalDateTitle"></h3>
                    <p style="font-size:11px;color:#94a3b8;margin-top:2px;" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" style="width:34px;height:34px;border-radius:10px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="modalReservationsList" class="space-y-1"></div>
            <div class="mt-4 text-center hidden" id="modalEmptyMessage" style="font-size:13px;color:#94a3b8;">
                <i class="fa-regular fa-calendar-xmark" style="font-size:24px;display:block;margin-bottom:8px;color:#cbd5e1;"></i>No reservations for this date.
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
        <button class="toast-close" onclick="dismissToast()"><i class="fa-solid fa-xmark"></i></button>
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
                <div class="tgl-row hidden sm:flex">
                    <span class="tgl-label" id="tgl-lbl"><i class="fa-regular fa-sun" style="font-size:13px;"></i></span>
                    <div class="tgl" id="tgl" onclick="toggleDark()"></div>
                </div>
                <a href="<?= base_url('/reservation') ?>" class="reserve-btn hidden sm:flex">
                    <i class="fa-solid fa-plus" style="font-size:10px;"></i> Reserve
                </a>
                <div class="notif-bell" onclick="toggleNotifications()">
                    <div class="notif-btn-wrap">
                        <i class="fa-regular fa-bell" style="font-size:14px;"></i>
                    </div>
                    <span class="notif-badge" id="notificationBadge" style="display:none;">0</span>
                </div>
            </div>
        </div>

        <!-- Notification dropdown -->
        <div id="notificationDropdown" class="notif-dropdown">
            <div style="padding:12px 14px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:700;font-size:13px;color:#0f172a;">Notifications</span>
                <button onclick="markAllAsRead()" style="font-size:11px;color:var(--blue);font-weight:700;background:none;border:none;cursor:pointer;">Mark all read</button>
            </div>
            <div id="notificationList" style="max-height:300px;overflow-y:auto;"></div>
        </div>

        <!-- Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-success fade-in">
                <i class="fa-solid fa-circle-check" style="color:#2563eb;font-size:14px;"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- What to do next -->
        <?php if ($nextAction): $nc = $nextColors[$nextAction['color']]; ?>
            <div class="next-step-card fade-in" style="background:<?= $nc['bg'] ?>;border-color:<?= $nc['border'] ?>;">
                <div class="next-icon-wrap" style="background:<?= $nc['icon_bg'] ?>;color:<?= $nc['icon_fg'] ?>;">
                    <i class="fa-solid <?= $nc['icon'] ?>" style="font-size:13px;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="next-eyebrow" style="color:<?= $nc['icon_fg'] ?>;">What to do next</div>
                    <div class="next-msg"><?= $nextAction['msg'] ?></div>
                    <a href="<?= base_url($nextAction['url']) ?>" class="next-cta" style="background:<?= $nc['btn_bg'] ?>;">
                        <?= $nextAction['cta'] ?> <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Timer countdown banner -->
        <div id="timerBanner" class="timer-banner">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <div id="timerIcon" style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:13px;background:rgba(0,0,0,.07);">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-weight:700;font-size:13px;line-height:1.3;" id="timerTitle">Your reservation ends soon</p>
                    <p style="font-size:11px;opacity:.7;margin-top:2px;" id="timerSub"></p>
                </div>
                <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                    <div class="timer-digit"><span id="tdHv">00</span><span>hrs</span></div>
                    <span style="font-weight:800;font-size:15px;opacity:.45;" class="timer-pulse">:</span>
                    <div class="timer-digit"><span id="tdMv">00</span><span>min</span></div>
                    <span style="font-weight:800;font-size:15px;opacity:.45;" class="timer-pulse">:</span>
                    <div class="timer-digit"><span id="tdSv">00</span><span>sec</span></div>
                </div>
            </div>
            <div class="timer-progress-wrap" id="timerProgressWrap" style="display:none;">
                <div class="timer-progress-fill" id="timerProgressFill" style="width:0%;"></div>
            </div>
        </div>

        <!-- Upcoming reservation -->
        <?php if ($upcoming): ?>
            <div class="upcoming-pill fade-in">
                <div class="up-icon"><i class="fa-solid fa-ticket" style="font-size:13px;"></i></div>
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
                    <div class="stat-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fa-solid fa-layer-group" style="font-size:13px;"></i></div>
                    <span class="stat-lbl">Total</span>
                </div>
                <div class="stat-val-num stat-num"><?= $total ?></div>
                <div class="stat-hint">All time</div>
            </div>
            <div class="stat-card-wrap">
                <div class="stat-top">
                    <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="fa-regular fa-clock" style="font-size:13px;"></i></div>
                    <span class="stat-lbl">Pending</span>
                </div>
                <div class="stat-val-num stat-num" style="color:#d97706;"><?= $pending ?></div>
                <div class="stat-hint">Awaiting review</div>
            </div>
            <div class="stat-card-wrap">
                <div class="stat-top">
                    <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="fa-solid fa-circle-check" style="font-size:13px;"></i></div>
                    <span class="stat-lbl">Approved</span>
                </div>
                <div class="stat-val-num stat-num" style="color:#16a34a;"><?= $approved ?></div>
                <div class="stat-hint">Ready to use</div>
            </div>
            <?php if ($unclaimedCount > 0): ?>
                <div class="stat-card-wrap" style="border-color:#fed7aa;">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#fff7ed;color:#ea580c;"><i class="fa-solid fa-ticket" style="font-size:13px;"></i></div>
                        <span class="stat-lbl">No-show</span>
                    </div>
                    <div class="stat-val-num stat-num" style="color:#ea580c;"><?= $unclaimedCount ?></div>
                    <div class="stat-hint" style="color:#fb923c;">Slot<?= $unclaimedCount>1?'s':'' ?> missed</div>
                </div>
            <?php elseif ($claimedCount > 0): ?>
                <div class="stat-card-wrap">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fa-solid fa-check-double" style="font-size:13px;"></i></div>
                        <span class="stat-lbl">Claimed</span>
                    </div>
                    <div class="stat-val-num stat-num" style="color:#9333ea;"><?= $claimedCount ?></div>
                    <div class="stat-hint">Tickets used</div>
                </div>
            <?php else: ?>
                <div class="stat-card-wrap">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="fa-solid fa-ban" style="font-size:13px;"></i></div>
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
                        <div class="card-icon-wrap"><i class="fa-solid fa-calendar-days" style="font-size:12px;"></i></div>
                        <div>
                            <div class="card-title-txt">Community Schedule</div>
                            <div class="card-sub-txt">Click any date to see reservations</div>
                        </div>
                    </div>
                    <div class="cal-legend hidden sm:flex">
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
                    <div class="space-y-1.5">
                        <a href="<?= base_url('/reservation') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-plus" style="font-size:11px;"></i></div>New Reservation<i class="fa-solid fa-chevron-right qa-chevron"></i>
                        </a>
                        <a href="<?= base_url('/reservation-list') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fa-regular fa-calendar" style="font-size:11px;"></i></div>My Reservations<i class="fa-solid fa-chevron-right qa-chevron"></i>
                        </a>
                        <a href="<?= base_url('/books') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><i class="fa-solid fa-book-open" style="font-size:11px;"></i></div>Browse Library<i class="fa-solid fa-chevron-right qa-chevron"></i>
                        </a>
                        <a href="<?= base_url('/profile') ?>" class="qa-item">
                            <div class="qa-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fa-regular fa-user" style="font-size:11px;"></i></div>View Profile<i class="fa-solid fa-chevron-right qa-chevron"></i>
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
                            <i class="fa-regular fa-calendar-xmark" style="font-size:28px;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                            <p style="font-size:12px;color:#94a3b8;">No bookings yet</p>
                            <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:10px;font-size:11px;font-weight:700;color:var(--blue);text-decoration:none;">
                                <i class="fa-solid fa-plus" style="font-size:9px;"></i> Make your first reservation
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
                    <div class="card-icon-wrap"><i class="fa-solid fa-list-check" style="font-size:12px;"></i></div>
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
                    <div class="card-icon-wrap" style="background:#eff6ff;color:var(--blue);"><i class="fa-solid fa-circle-info" style="font-size:12px;"></i></div>
                    <div>
                        <div class="card-title-txt">What Each Status Means</div>
                        <div class="card-sub-txt">So you always know where you stand</div>
                    </div>
                </div>
                <?php foreach ([
                    ['pending',  'fa-clock',        '#fef3c7','#92400e','Pending',  'Your request is waiting for SK officer review.'],
                    ['approved', 'fa-circle-check', '#dcfce7','#166534','Approved', 'Confirmed. Get your e-ticket from My Reservations.'],
                    ['claimed',  'fa-check-double', '#f3e8ff','#6b21a8','Claimed',  'Your e-ticket was scanned. Slot successfully used.'],
                    ['unclaimed','fa-ticket',        '#fff7ed','#c2410c','No-show',  'Approved but you didn\'t come. The slot was wasted.'],
                    ['declined', 'fa-ban',           '#fee2e2','#991b1b','Declined', 'Not approved. Try booking a different time.'],
                    ['expired',  'fa-hourglass-end', '#f1f5f9','#475569','Expired',  'Request wasn\'t approved before the date passed.'],
                ] as [$key,$icon,$bg,$fg,$label,$desc]): ?>
                    <div class="status-guide-row">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:8px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;flex-shrink:0;width:80px;justify-content:center;background:<?= $bg ?>;color:<?= $fg ?>;">
                            <i class="fa-solid <?= $icon ?>" style="font-size:8px;"></i><?= $label ?>
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
                            <i class="fa-solid fa-book-open" style="font-size:11px;"></i> Browse All
                        </a>
                    </div>
                    <div class="lib-stats" style="position:relative;z-index:1;">
                        <div class="lib-stat">
                            <div class="lib-stat-icon"><i class="fa-solid fa-bookmark" style="font-size:11px;"></i></div>
                            <div><div class="lib-stat-lbl">My Borrows</div><div class="lib-stat-val"><?= count($myBorrowings) ?></div></div>
                        </div>
                        <div class="lib-stat">
                            <div class="lib-stat-icon"><i class="fa-solid fa-hourglass-half" style="font-size:11px;color:#fcd34d;"></i></div>
                            <div><div class="lib-stat-lbl">Pending</div><div class="lib-stat-val"><?= count(array_filter($myBorrowings,fn($b)=>($b['status']??'')==='pending')) ?></div></div>
                        </div>
                        <div class="lib-stat">
                            <div class="lib-stat-icon"><i class="fa-solid fa-circle-check" style="font-size:11px;color:#7dd3fc;"></i></div>
                            <div><div class="lib-stat-lbl">Active</div><div class="lib-stat-val"><?= count(array_filter($myBorrowings,fn($b)=>($b['status']??'')==='approved')) ?></div></div>
                        </div>
                    </div>
                </div>

                <!-- AI Book Finder -->
                <div class="dash-card card-p">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:0;">
                        <div class="card-icon-wrap" style="background:#eef2ff;color:#4f46e5;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:12px;"></i></div>
                        <div>
                            <div class="card-title-txt">AI Book Finder</div>
                            <div class="card-sub-txt">Describe what you want to read</div>
                        </div>
                    </div>
                    <div class="rag-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="dashRagInput" class="search-input" placeholder="e.g. Filipino history, funny stories, adventure for kids…" onkeydown="if(event.key==='Enter') dashRagSearch()">
                    </div>
                    <div id="dashRagSkel" style="display:none;margin-top:.6rem;">
                        <div class="shimmer" style="width:90%;"></div>
                        <div class="shimmer" style="width:72%;"></div>
                        <div class="shimmer" style="width:55%;"></div>
                    </div>
                    <div class="ai-suggestion-box" id="dashRagResult">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                            <i class="fa-solid fa-robot" style="color:var(--blue);font-size:11px;flex-shrink:0;"></i>
                            <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#1d4ed8;">Librarian Suggestion</p>
                        </div>
                        <p style="font-size:12px;color:#1e3a8a;line-height:1.6;" id="dashRagText"></p>
                        <div id="dashRagBooks" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:6px;"></div>
                    </div>
                    <div id="dashRagErr" style="display:none;margin-top:6px;font-size:11px;color:#dc2626;font-weight:500;"></div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;">
                        <button onclick="dashRagSearch()" id="dashRagBtn" class="find-btn">
                            <i class="fa-solid fa-wand-magic-sparkles" style="font-size:10px;"></i> Find Books
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
                            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(37,99,235,.07);">
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
                            <i class="fa-solid fa-book" style="font-size:24px;color:#e2e8f0;display:block;margin-bottom:6px;"></i>
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
                        <div class="space-y-2">
                            <?php foreach ($activeBorrows as $borrow): $bs = strtolower($borrow['status'] ?? 'pending'); ?>
                            <div class="borrow-row">
                                <div class="book-letter-wrap" style="width:32px;height:32px;font-size:12px;"><?= mb_strtoupper(mb_substr($borrow['title'] ?? 'B', 0, 1)) ?></div>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-weight:600;font-size:12px;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($borrow['title'] ?? 'Unknown Book') ?></p>
                                    <?php if (!empty($borrow['due_date']) && $bs === 'approved'): ?>
                                        <p style="font-size:10px;color:#94a3b8;">Due <?= date('M j', strtotime($borrow['due_date'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="tag borrow-tag-<?= $bs ?>"><?= ucfirst($bs) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:18px 12px;">
                            <i class="fa-regular fa-bookmark" style="font-size:22px;color:#e2e8f0;display:block;margin-bottom:6px;"></i>
                            <p style="font-size:12px;color:#94a3b8;">No active borrows</p>
                            <a href="<?= base_url('/books') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;font-size:11px;font-weight:700;color:var(--blue);text-decoration:none;">
                                <i class="fa-solid fa-book-open" style="font-size:9px;"></i> Borrow a book
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // ── All original JS from your dashboard.php ──────────────────────────────
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
                list.innerHTML = `<div style="text-align:center;padding:28px 16px;"><i class="fa-regular fa-bell-slash" style="font-size:24px;color:#e2e8f0;display:block;margin-bottom:8px;"></i><p style="font-size:12px;color:#94a3b8;">All caught up!</p></div>`;
                return;
            }
            list.innerHTML = notifications.sort((a,b) => new Date(b.time)-new Date(a.time)).map(n => `
                <div class="notif-item ${!n.read?'unread':''}" onclick="markAsRead(${n.id})">
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div style="width:32px;height:32px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-check" style="color:#2563eb;font-size:11px;"></i></div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:700;font-size:13px;color:#0f172a;">${n.title}</p>
                            <p style="font-size:11px;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                            <p style="font-size:10px;color:#94a3b8;margin-top:2px;">${timeAgo(n.time)}</p>
                        </div>
                        ${!n.read ? '<span style="width:7px;height:7px;background:#2563eb;border-radius:50%;flex-shrink:0;margin-top:4px;"></span>' : ''}
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

        // ── Date modal ───────────────────────────────────────────────────────────
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
                    const colorMap = { approved:'#dcfce7|#166534', pending:'#fef3c7|#92400e', declined:'#fee2e2|#991b1b', canceled:'#fee2e2|#991b1b', claimed:'#f3e8ff|#6b21a8' };
                    const [cbg, cfg] = (colorMap[s] || '#f1f5f9|#475569').split('|');
                    const t1 = r.start_time ? r.start_time.substring(0,5) : 'All day', t2 = r.end_time ? ` – ${r.end_time.substring(0,5)}` : '';
                    const row = document.createElement('div');
                    row.className = 'date-row';
                    row.innerHTML = `
                        <div style="width:34px;height:34px;background:#f8fafc;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid #e2e8f0;"><i class="fa-regular fa-calendar" style="color:#94a3b8;font-size:12px;"></i></div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:600;font-size:13px;color:#0f172a;">${r.resource_name||'Reserved'}</p>
                            <p style="font-size:11px;color:#94a3b8;margin-top:2px;">${t1}${t2}</p>
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

        // ── Dark mode ─────────────────────────────────────────────────────────────
        function toggleDark() {
            const body = document.body;
            const lbl = document.getElementById('tgl-lbl');
            if (body.classList.contains('dark')) {
                body.classList.remove('dark');
                lbl.innerHTML = '<i class="fa-regular fa-sun" style="font-size:13px;"></i>';
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark');
                lbl.innerHTML = '<i class="fa-regular fa-moon" style="font-size:13px;"></i>';
                localStorage.setItem('theme', 'dark');
            }
        }

        // ── Init ──────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            // Restore dark mode preference
            if (localStorage.getItem('theme') === 'dark') {
                document.body.classList.add('dark');
                const lbl = document.getElementById('tgl-lbl');
                if (lbl) lbl.innerHTML = '<i class="fa-regular fa-moon" style="font-size:13px;"></i>';
            }

            if ('Notification' in window) Notification.requestPermission();
            loadNotifications();
            initCountdownBanner();
            showLoginToast();

            // Build calendar
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
                        badge.style.cssText = 'font-size:8px;font-weight:700;color:white;background:#2563eb;border-radius:999px;width:15px;height:15px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;margin-bottom:2px;';
                        badge.textContent = res.length;
                        info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);
                    }
                }
            });
            cal.render();
        });

        // ── Countdown timer ───────────────────────────────────────────────────────
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
                    if (minsLeft <= 10) { banner.classList.add('urgent');  iconEl.innerHTML='<i class="fa-solid fa-triangle-exclamation" style="font-size:13px;"></i>'; }
                    else if (minsLeft <= 20) { banner.classList.add('warning'); iconEl.innerHTML='<i class="fa-solid fa-hourglass-half" style="font-size:13px;"></i>'; }
                    else { banner.classList.add('safe'); iconEl.innerHTML='<i class="fa-solid fa-hourglass-start" style="font-size:13px;"></i>'; }
                    titleEl.textContent = minsLeft <= 10 ? '⚠️ Your reservation ends very soon!' : 'Your reservation is active';
                    subEl.textContent = `${r.resource_name||'Resource'} · Ends at ${r.end_time?.substring(0,5)}`;
                    const pct = Math.min(100, Math.max(0, ((now-start)/(end-start))*100));
                    progressWrap.style.display = 'block';
                    progressFill.style.width = pct.toFixed(1) + '%';
                } else {
                    banner.classList.add('safe');
                    iconEl.innerHTML = '<i class="fa-solid fa-bell" style="font-size:13px;"></i>';
                    titleEl.textContent = 'Your reservation starts soon';
                    subEl.textContent = `${r.resource_name||'Resource'} · Starts at ${r.start_time?.substring(0,5)}`;
                    progressWrap.style.display = 'none';
                }
                banner.style.display = 'block';
            }
            tick();
            setInterval(tick, 1000);
        }

        // ── Login toast ───────────────────────────────────────────────────────────
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
                    toastData = {icon:'<i class="fa-solid fa-circle-play" style="color:#3b82f6;font-size:14px;"></i>',bg:'#2563eb',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${r.end_time?.substring(0,5)} — don't forget!`};
                if (!toastData && resDay===today && minsToStart>0 && minsToStart<=120)
                    toastData = {icon:'<i class="fa-solid fa-bell" style="color:#f59e0b;font-size:14px;"></i>',bg:'#d97706',title:`Reservation in ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${r.start_time?.substring(0,5)} – ${r.end_time?.substring(0,5)}`};
                if (!toastData && resDay===today) {
                    const fmt = t => { const [h,m] = t.split(':'); const hr=+h; return `${hr%12||12}:${m} ${hr<12?'AM':'PM'}`; };
                    toastData = {icon:'<i class="fa-solid fa-calendar-check" style="color:#60a5fa;font-size:14px;"></i>',bg:'#2563eb',title:'You have a reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`};
                }
            });
            if (!toastData) return;
            const toast = document.getElementById('loginToast');
            document.getElementById('toastIcon').innerHTML = toastData.icon;
            document.getElementById('toastIcon').style.background = toastData.bg + '33';
            document.getElementById('toastTitle').textContent = toastData.title;
            document.getElementById('toastBody').textContent = toastData.body;
            setTimeout(() => toast.classList.add('show'), 900);
            setTimeout(() => toast.classList.remove('show'), 7900);
        }

        function dismissToast() { document.getElementById('loginToast').classList.remove('show'); }

        // ── AI Book Finder ────────────────────────────────────────────────────────
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
                    chip.style.cssText = `display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:9px;font-size:10px;font-weight:600;border:1px solid;transition:all .15s;text-decoration:none;${avail?'background:white;border-color:#bfdbfe;color:#1d4ed8;':'background:#f8fafc;border-color:#e2e8f0;color:#94a3b8;'}`;
                    chip.innerHTML = `<i class="fa-solid fa-book" style="font-size:9px;"></i>${b.title}${avail?'':' <span style="font-size:8px;">(out)</span>'}`;
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