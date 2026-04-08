<?php
function sk_icon(string $name, int $size = 16, string $stroke = 'currentColor', string $extra = ''): string
{
    $icons = [
        'house'          => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus'           => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'calendar-days'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>',
        'calendar-x'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="14" x2="14" y2="18"/><line x1="14" y1="14" x2="10" y2="18"/>',
        'book-open'      => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'user'           => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'users'          => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
        'clock'          => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'check-circle'   => '<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'check-double'   => '<path d="M17 1l-8.5 8.5L6 7M22 6l-8.5 8.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 13l-4 4 1.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>',
        'check'          => '<polyline points="20 6 9 17 4 12"/>',
        'x'              => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'chevron-right'  => '<polyline points="9 18 15 12 9 6"/>',
        'arrow-right'    => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'layers'         => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'bar-chart'      => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        'chart-line'     => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
        'chart-pie'      => '<path d="M21.21 15.89A10 10 0 118 2.83"/><path d="M22 12A10 10 0 0012 2v10z"/>',
        'stopwatch'      => '<circle cx="12" cy="13" r="8"/><polyline points="12 9 12 13 14 15"/><line x1="9" y1="1" x2="15" y2="1"/>',
        'hourglass'      => '<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/>',
        'bolt'           => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke-linecap="round" stroke-linejoin="round"/>',
        'sparkles'       => '<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round" stroke-linejoin="round"/>',
        'wand'           => '<path d="M15 4V2M15 16v-2M8 9h2M20 9h2M17.8 11.8L19 13M17.8 6.2L19 5M3 21l9-9M12.2 6.2L11 5" stroke-linecap="round" stroke-linejoin="round"/>',
        'search'         => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'bell'           => '<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>',
        'ticket'         => '<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round"/>',
        'bookmark'       => '<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>',
        'robot'          => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><circle cx="12" cy="5" r="1"/>',
        'info'           => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'crown'          => '<path d="M2 20h20M5 20V9l7-7 7 7v11" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 20v-5h6v5"/>',
        'fire'           => '<path d="M12 2c0 6-6 7-6 13a6 6 0 0012 0c0-6-6-7-6-13z" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 12c0 3-2 4-2 6a2 2 0 004 0c0-2-2-3-2-6z" stroke-linecap="round" stroke-linejoin="round"/>',
        'lightbulb'      => '<path d="M9 21h6M12 3a6 6 0 016 6c0 2.5-1.5 4.5-3 6H9c-1.5-1.5-3-3.5-3-6a6 6 0 016-6z" stroke-linecap="round" stroke-linejoin="round"/>',
        'triangle'       => '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'qrcode'         => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="5" y="5" width="3" height="3"/><rect x="16" y="5" width="3" height="3"/><rect x="5" y="16" width="3" height="3"/><line x1="14" y1="14" x2="17" y2="14"/><line x1="14" y1="17" x2="14" y2="20"/><line x1="17" y1="17" x2="20" y2="17"/><line x1="20" y1="14" x2="20" y2="20"/>',
        'refresh'        => '<path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15" stroke-linecap="round" stroke-linejoin="round"/>',
        'sun'  => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
        'moon' => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
        'logout'         => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
    $d  = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    $sw = in_array($name, ['calendar','calendar-days','calendar-x','bar-chart','bookmark','robot','qrcode']) ? '1.5' : '1.8';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="'.$stroke.'" stroke-width="'.$sw.'" '.$extra.'>'.$d.'</svg>';
}

// ── Data setup ─────────────────────────────────────────────────
$myRes  = $reservations    ?? [];
$sysRes = $allReservations ?? [];

$sysTotal    = count($sysRes);
$sysPending  = count(array_filter($sysRes, fn($r) => ($r['status'] ?? '') === 'pending'));
$sysApproved = count(array_filter($sysRes, fn($r) => ($r['status'] ?? '') === 'approved'));
$sysDeclined = count(array_filter($sysRes, fn($r) => in_array($r['status'] ?? '', ['declined','canceled'])));
$sysClaimed  = count(array_filter($sysRes, fn($r) => in_array($r['claimed'] ?? false, [true,1,'t','true','1'], true)));

$sysToday         = date('Y-m-d');
$sysTodayAll      = array_filter($sysRes, fn($r) => ($r['reservation_date'] ?? '') === $sysToday);
$sysTodayTotal    = count($sysTodayAll);
$sysTodayApproved = count(array_filter($sysTodayAll, fn($r) => ($r['status'] ?? '') === 'approved'));
$sysTodayPending  = count(array_filter($sysTodayAll, fn($r) => ($r['status'] ?? '') === 'pending'));
$sysTodayClaimed  = count(array_filter($sysTodayAll, fn($r) => in_array($r['claimed'] ?? false, [true,1,'t','true','1'], true)));

$sysApprovalRate    = $sysTotal    > 0 ? round($sysApproved / $sysTotal    * 100) : 0;
$sysUtilizationRate = $sysApproved > 0 ? round($sysClaimed  / $sysApproved * 100) : 0;
$thirtyDaysAgo      = date('Y-m-d', strtotime('-30 days'));
$sysMonthlyTotal    = count(array_filter($sysRes, fn($r) => ($r['reservation_date'] ?? '') >= $thirtyDaysAgo));

$remainingReservations = $remainingReservations ?? 0;
$pendingUserCount      = $pendingUserCount      ?? 0;
$dashBooks             = $dashBooks             ?? [];
$myBorrowings          = $myBorrowings          ?? [];
$availableCount        = $availableCount        ?? 0;
$totalBooks            = $totalBooks            ?? 0;

$usedSlots = (int)($usedThisMonth   ?? 0);
$maxSlots  = max(1, (int)($maxMonthlySlots ?? 3));
$quotaPct  = min(100, round($usedSlots / $maxSlots * 100));

// ── Insights computation ───────────────────────────────────────
$insHourArr = array_fill(0, 24, 0);
$insDowArr  = array_fill(0, 7, 0);
$insMonArr  = array_fill(0, 12, 0);
$insResMap  = [];
$insDateVol = [];
$ins7 = 0; $insPrev7 = 0;

foreach ($sysRes as $r) {
    if (!empty($r['start_time'])) $insHourArr[(int)date('G', strtotime($r['start_time']))]++;
    if (!empty($r['reservation_date'])) {
        $insDowArr[(int)date('w', strtotime($r['reservation_date']))]++;
        $insMonArr[(int)date('n', strtotime($r['reservation_date'])) - 1]++;
        $insDateVol[$r['reservation_date']] = ($insDateVol[$r['reservation_date']] ?? 0) + 1;
        $d = (int)floor((time() - strtotime($r['reservation_date'])) / 86400);
        if ($d >= 0 && $d < 7)  $ins7++;
        if ($d >= 7 && $d < 14) $insPrev7++;
    }
    $rname = $r['resource_name'] ?? 'Unknown';
    $insResMap[$rname] = ($insResMap[$rname] ?? 0) + 1;
}

$insPH  = array_search(max($insHourArr), $insHourArr);
$insPD  = array_search(max($insDowArr),  $insDowArr);
$insPM  = array_search(max($insMonArr),  $insMonArr);
$f12    = fn($h) => (($h % 12) ?: 12) . ' ' . ($h < 12 ? 'AM' : 'PM');
$insPHL = $f12($insPH) . '–' . $f12($insPH + 1);
$insPDL = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$insPD] ?? '—';
$insPML = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][$insPM] ?? '—';
arsort($insResMap);
$insTopRes    = (string)(array_key_first($insResMap) ?? 'N/A');
$insTopResCnt = (int)(reset($insResMap) ?: 0);
arsort($insDateVol);
$insBD  = array_key_first($insDateVol) ?? null;
$insBDC = (int)(reset($insDateVol) ?: 0);
$insBDL = $insBD ? date('M j, Y', strtotime($insBD)) : 'N/A';
$insTrP = $insPrev7 > 0 ? round((($ins7 - $insPrev7) / $insPrev7) * 100) : ($ins7 > 0 ? 100 : 0);
$insTrD = $insTrP >= 0 ? 'up' : 'down';
$insTrC = $insTrD === 'up' ? '#10b981' : '#ef4444';
$insNS  = $sysApproved > 0 ? round((($sysApproved - $sysClaimed) / $sysApproved) * 100) : 0;
$insDR  = $sysTotal    > 0 ? round(($sysDeclined / $sysTotal) * 100) : 0;

// ── 7-day chart data ───────────────────────────────────────────
$chartLabels = [];
$chartData   = [];
for ($i = 6; $i >= 0; $i--) {
    $d             = date('Y-m-d', strtotime("-$i days"));
    $chartLabels[] = date('D', strtotime($d));
    $chartData[]   = count(array_filter($sysRes, fn($r) => ($r['reservation_date'] ?? '') === $d));
}

// ── Resource donut data ────────────────────────────────────────
$resourceLabels = [];
$resourceData   = [];
$resCount       = [];
foreach ($sysRes as $r) {
    $rn = $r['resource_name'] ?? 'Unknown';
    $resCount[$rn] = ($resCount[$rn] ?? 0) + 1;
}
arsort($resCount);
foreach (array_slice($resCount, 0, 5, true) as $rname => $cnt) {
    $resourceLabels[] = $rname;
    $resourceData[]   = (int)$cnt;
}
if (empty($resourceLabels)) { $resourceLabels = ['No Data']; $resourceData = [1]; }

// ── Active timer (my sessions) ────────────────────────────────
$activeBanner   = null;
$upcomingBanner = null;
$now = time();
foreach ($myRes as $r) {
    if (empty($r['reservation_date']) || empty($r['start_time']) || empty($r['end_time'])) continue;
    if (($r['status'] ?? '') === 'approved' && !($r['claimed'] ?? false)) {
        $s = strtotime($r['reservation_date'] . 'T' . $r['start_time']);
        $e = strtotime($r['reservation_date'] . 'T' . $r['end_time']);
        if ($s <= $now && $e >= $now) { $activeBanner = $r; break; }
        if ($s > $now && $s <= $now + 3600) $upcomingBanner = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>Dashboard | SK Officer</title>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <?php /* Dark-mode pre-init — eliminates flash of wrong theme. Mirrors layout.php. */ ?>
    <script>
    (function () {
        try {
            if (localStorage.getItem('sk_theme') === 'dark') {
                document.documentElement.classList.add('dark-pre');
            }
        } catch (e) {}
    })();
    </script>

    <?php /* Dashboard-specific styles only. All global tokens, sidebar, nav, cards,
             tags, modals, dark-mode overrides and utilities live in app.css. */ ?>
    <style>
        /* ── Layout shell ── */
        body { display:flex; height:100vh; height:100dvh; overflow:hidden; }
        html.dark-pre body { background:#060e1e; }

        /* ── Reserve button (topbar) ── */
        .reserve-btn {
            display: inline-flex; align-items:center; gap:7px;
            padding:10px 18px; background:var(--indigo); color:#fff;
            border-radius:var(--r-sm); font-size:.85rem; font-weight:700;
            border:none; cursor:pointer; font-family:var(--font);
            letter-spacing:-.01em; transition:all var(--ease);
            text-decoration:none; box-shadow:0 4px 12px rgba(55,48,163,.28);
            touch-action:manipulation;
        }
        .reserve-btn:hover { background:#312e81; transform:translateY(-1px); box-shadow:0 6px 18px rgba(55,48,163,.35); }

        /* ── Sync badge ── */
        .sync-badge {
            display:inline-flex; align-items:center; gap:4px;
            font-size:.6rem; font-weight:700; padding:2px 7px;
            border-radius:999px; background:#eff6ff; color:#1d4ed8;
            border:1px solid #bfdbfe; white-space:nowrap;
        }
        body.dark .sync-badge { background:rgba(29,78,216,.2); color:#7fb3e8; border-color:rgba(59,130,246,.2); }

        /* ── Notification dropdown ── */
        .notif-bell { position:relative; }
        .notif-badge {
            position:absolute; top:-5px; right:-5px;
            background:#ef4444; color:white; font-size:.55rem; font-weight:700;
            padding:2px 5px; border-radius:999px; min-width:17px;
            text-align:center; border:2px solid var(--bg); line-height:1.3; pointer-events:none;
        }
        .notif-dd {
            position:fixed; top:80px; right:20px; width:320px;
            background:var(--card); border-radius:var(--r-xl);
            box-shadow:var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.09);
            z-index:200; display:none; overflow:hidden;
        }
        .notif-dd.show { display:block; animation:fadeIn .15s ease; }
        .notif-item { padding:.85rem 1.1rem; border-bottom:1px solid var(--border-subtle); transition:background .15s; cursor:pointer; touch-action:manipulation; }
        .notif-item:hover { background:var(--input-bg); }
        .notif-item.unread { background:var(--indigo-light); }
        .notif-item:last-child { border-bottom:none; }
        @media(max-width:479px) { .notif-dd { left:12px; right:12px; width:auto; top:72px; } }

        /* ── Timer banner ── */
        .timer-banner {
            border-radius:var(--r-md); padding:14px 18px; margin-bottom:18px;
            border:1px solid; animation:slideDown .35s cubic-bezier(.34,1.56,.64,1) both;
        }
        .timer-banner.active   { background:#f0fdf4; border-color:#86efac; color:#14532d; }
        .timer-banner.upcoming { background:var(--indigo-light); border-color:var(--indigo-border); color:#312e81; }
        body.dark .timer-banner.active   { background:rgba(20,83,45,.25); border-color:rgba(134,239,172,.2); color:#86efac; }
        body.dark .timer-banner.upcoming { background:rgba(55,48,163,.15); border-color:rgba(99,102,241,.3); color:#a5b4fc; }
        .timer-pulse { width:8px; height:8px; border-radius:50%; background:#22c55e; flex-shrink:0; animation:livePulse 1.5s infinite; }
        @keyframes livePulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.4);opacity:.6} }
        @keyframes slideDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }

        /* ── Upcoming pill ── */
        .upcoming-pill {
            background:var(--indigo-light); border:1px solid var(--indigo-border);
            border-radius:var(--r-md); padding:14px 16px;
            display:flex; align-items:center; gap:14px;
            margin-bottom:20px; animation:slideUp .4s ease both; flex-wrap:wrap;
        }
        .up-icon { width:38px; height:38px; background:var(--indigo); border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 10px rgba(55,48,163,.28); }
        .up-eyebrow { font-size:.6rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--indigo); margin-bottom:2px; }
        .up-name { font-size:.88rem; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:200px; }
        .up-time { font-size:.72rem; color:#4338ca; font-family:var(--mono); margin-top:1px; }
        .up-btn { margin-left:auto; font-size:.72rem; font-weight:700; color:var(--indigo); background:var(--card); border:1px solid var(--indigo-border); border-radius:8px; padding:8px 14px; text-decoration:none; white-space:nowrap; transition:all var(--ease); touch-action:manipulation; }
        .up-btn:hover { background:var(--indigo); color:white; }
        body.dark .up-time { color:#818cf8; }

        /* ── Stats grid ── */
        .stats-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:20px; }
        .stat-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r-lg); padding:18px 20px; box-shadow:var(--shadow-sm); transition:transform var(--ease),box-shadow var(--ease); }
        .stat-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
        .stat-card-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px; }
        .stat-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
        .stat-lbl { font-size:.62rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--text-sub); }
        .stat-num { font-size:2rem; font-weight:800; color:var(--text); line-height:1; letter-spacing:-.04em; font-family:var(--mono); }
        .stat-hint { font-size:.72rem; color:var(--text-sub); margin-top:4px; }
        .stat-badge { font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
        @media(max-width:639px) { .stats-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; } .stat-card { padding:14px 16px; } .stat-num { font-size:1.6rem; } }

        /* ── KPI strip ── */
        .kpi-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-bottom:18px; }
        .kpi-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r-md); padding:14px 16px; border-left-width:4px; box-shadow:var(--shadow-sm); transition:transform var(--ease); }
        .kpi-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
        .kpi-num { font-size:1.6rem; font-weight:800; font-family:var(--mono); line-height:1; margin-top:6px; }
        @media(max-width:639px) { .kpi-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:900px) {
    .grid-main, .grid-three, .grid-lib { grid-template-columns: 1fr; }
}
        /* ── Chart wrappers ── */
        .chart-wrap { position:relative; height:200px; width:100%; }
        @media(max-width:639px) { .chart-wrap { height:160px; } }
        .donut-wrap { display:flex; align-items:center; gap:16px; margin-top:12px; flex-wrap:wrap; }
        .donut-canvas { width:140px !important; height:140px !important; flex-shrink:0; }

        /* ── Grid layouts ── */
        .grid-main  { display:grid; grid-template-columns:minmax(0,1.9fr) minmax(0,1fr); gap:16px; margin-bottom:18px; }
        .grid-two   { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:14px; margin-bottom:18px; }
        .grid-three { display:grid; grid-template-columns:minmax(0,1.5fr) minmax(0,1fr); gap:14px; margin-bottom:18px; }
        .grid-four  { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:18px; }
        .grid-lib   { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:16px; margin-bottom:16px; }
        .side-col   { display:flex; flex-direction:column; gap:14px; }
        @media(max-width:900px) { .grid-main,.grid-three,.grid-lib { grid-template-columns:1fr; } }
        /* ── Library section mobile fixes ── */
@media (max-width: 767px) {
    .grid-lib {
        grid-template-columns: 1fr !important;
    }
}

@media (max-width: 639px) {
    /* Section label with "Browse All" link */
    .section-lbl {
        flex-wrap: wrap;
    }

    /* Library banner */
    .lib-banner {
        padding: 16px 16px 14px;
    }

    /* Stat items row inside banner */
    .lib-banner > div > div[style*="display:flex;gap:8px"] {
        gap: 6px;
    }

    .lib-stat-item {
        flex: 1 1 0;
        min-width: 0;
        padding: 7px 8px;
    }

    .lib-stat-lbl {
        font-size: .5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lib-stat-val {
        font-size: .88rem;
        line-height: 1.1;
    }

    /* Book list item — critical: prevent title overflow */
    .grid-lib a[style*="display:flex"] > div[style*="flex:1"] {
        min-width: 0;
    }

    /* Insights 4-col → 2-col earlier */
    .grid-four {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    /* Heatmap — fewer columns on tiny screens */
    #insHeatmap {
        grid-template-columns: repeat(12, 1fr) !important;
    }
    .heatmap-cell {
        height: 22px;
    }

    /* Bottom padding for mobile nav */
    .main-area {
        padding-bottom: calc(var(--mob-nav-total) + 24px) !important;
    }
}
        @media(max-width:639px) { .grid-two,.grid-four { grid-template-columns:repeat(2,minmax(0,1fr)); } .main-area { padding:14px 12px 0; } }

        /* ── Card sub-elements ── */
        .card-head  { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
        .card-icon  { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .card-title { font-size:.9rem; font-weight:700; color:var(--text); letter-spacing:-.01em; }
        .card-sub   { font-size:.7rem; color:var(--text-sub); margin-top:2px; }
        .section-lbl { font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--text-sub); margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .section-lbl::before { content:''; display:inline-block; width:3px; height:14px; border-radius:2px; background:var(--indigo); flex-shrink:0; }
        .link-sm { font-size:.65rem; font-weight:700; color:var(--indigo); text-decoration:none; letter-spacing:.05em; text-transform:uppercase; transition:opacity .15s; touch-action:manipulation; }
        .link-sm:hover { opacity:.7; }

        /* ── FullCalendar overrides ── */
        #calendar { font-size:.8rem; font-family:var(--font); }
        .fc .fc-toolbar { flex-wrap:wrap; gap:.5rem; }
        .fc-toolbar-title { font-size:.95rem !important; font-weight:800 !important; color:var(--text) !important; font-family:var(--font) !important; letter-spacing:-.02em !important; }
        .fc-button-primary { background:var(--indigo) !important; border-color:var(--indigo) !important; border-radius:9px !important; font-family:var(--font) !important; font-weight:700 !important; font-size:.72rem !important; padding:.3rem .65rem !important; box-shadow:none !important; touch-action:manipulation !important; }
        .fc-button-primary:hover { background:#312e81 !important; }
        .fc-button-primary:not(:disabled):active { background:#1e1b4b !important; }
        .fc-daygrid-event { border-radius:5px !important; font-size:.65rem !important; font-weight:600 !important; padding:2px 5px !important; border:none !important; cursor:pointer !important; font-family:var(--font) !important; }
        .fc-daygrid-day:hover { background-color:var(--indigo-light) !important; cursor:pointer; }
        .fc-day-today { background:rgba(55,48,163,.06) !important; }
        .fc-day-today .fc-daygrid-day-number { color:var(--indigo) !important; font-weight:800 !important; }
        .fc-daygrid-day-number { font-size:.72rem; font-weight:600; font-family:var(--font); }
        .fc-col-header-cell-cushion { font-family:var(--font); font-size:.72rem; font-weight:700; letter-spacing:.04em; }
        body.dark .fc-toolbar-title { color:var(--text) !important; }
        body.dark .fc-daygrid-day-number { color:#7fb3e8; }
        body.dark .fc-col-header-cell-cushion { color:var(--text-sub); }
        body.dark .fc-day-today { background:rgba(55,48,163,.15) !important; }
        body.dark .fc-daygrid-day { background:var(--card) !important; }
        body.dark .fc-theme-standard td, body.dark .fc-theme-standard th, body.dark .fc-theme-standard .fc-scrollgrid { border-color:var(--input-bg) !important; }
        @media(max-width:479px) { .fc .fc-toolbar { display:grid; grid-template-columns:auto 1fr auto; align-items:center; gap:6px; } .fc-toolbar-chunk:nth-child(2) { text-align:center; } .fc-toolbar-title { font-size:.8rem !important; } }

        /* ── Calendar legend ── */
        .cal-legend { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
        .leg-item   { display:flex; align-items:center; gap:5px; }
        .leg-dot    { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .leg-lbl    { font-size:.68rem; font-weight:600; color:var(--text-sub); }
        @media(max-width:479px) { .leg-lbl { display:none; } .leg-dot { width:9px; height:9px; } }

        /* ── Quick-action links ── */
        .qa-link { display:flex; align-items:center; gap:11px; padding:12px; border-radius:var(--r-sm); border:1px solid var(--border); background:var(--card); text-decoration:none; color:var(--text-muted); font-size:.83rem; font-weight:600; transition:all var(--ease); touch-action:manipulation; }
        .qa-link:hover { border-color:var(--indigo); background:var(--indigo-light); color:var(--indigo); }
        @media(pointer:fine) { .qa-link:hover { transform:translateX(3px); } }
        .qa-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .qa-chev { margin-left:auto; color:var(--text-faint); transition:color var(--ease); }
        .qa-link:hover .qa-chev { color:var(--indigo); }

        /* ── Recent booking rows ── */
        .bk-row   { display:flex; align-items:center; gap:11px; padding:9px 8px; border-radius:11px; text-decoration:none; color:inherit; transition:background var(--ease); touch-action:manipulation; }
        .bk-row:hover { background:var(--indigo-light); }
        .bk-date  { width:38px; height:38px; background:var(--input-bg); border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0; border:1px solid var(--border-subtle); }
        .bk-month { font-size:.55rem; font-weight:700; text-transform:uppercase; color:var(--text-sub); }
        .bk-day   { font-size:.95rem; font-weight:800; color:var(--text); line-height:1; font-family:var(--mono); }
        .bk-name  { font-size:.82rem; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .bk-time  { font-size:.68rem; color:var(--text-sub); margin-top:1px; font-family:var(--mono); }

        /* ── Date modal rows ── */
        .date-row { display:flex; align-items:center; gap:11px; padding:.75rem; border-bottom:1px solid var(--border-subtle); border-radius:10px; transition:background .15s; }
        .date-row:hover { background:var(--input-bg); }
        .date-row:last-child { border-bottom:none; }

        /* ── Live session cards ── */
        .session-card { background:var(--input-bg); border-radius:var(--r-md); border:1px solid var(--border-subtle); padding:12px 14px; border-left-width:4px; transition:all .2s; box-shadow:var(--shadow-sm); }
        .session-card:hover { box-shadow:var(--shadow-md); transform:translateY(-1px); }
        .session-card.s-ok       { border-left-color:#10b981; }
        .session-card.s-warning  { border-left-color:#f59e0b; }
        .session-card.s-critical { border-left-color:#ef4444; }
        .session-card.s-ended    { border-left-color:#94a3b8; opacity:.6; }
        .session-countdown { display:inline-flex; align-items:center; gap:4px; padding:.2rem .6rem; border-radius:999px; font-size:.7rem; font-weight:700; font-family:var(--mono); white-space:nowrap; }
        .s-ok       .session-countdown { background:#dcfce7; color:#166534; }
        .s-warning  .session-countdown { background:#fef3c7; color:#92400e; }
        .s-critical .session-countdown { background:#fee2e2; color:#991b1b; }
        .s-ended    .session-countdown { background:var(--input-bg-alt); color:var(--text-sub); }
        .session-prog-track { height:4px; border-radius:999px; background:rgba(0,0,0,.08); overflow:hidden; margin-top:8px; }
        body.dark .session-prog-track { background:rgba(255,255,255,.06); }
        .session-prog-fill { height:100%; border-radius:999px; transition:width 1s linear; }
        .s-ok       .session-prog-fill { background:#10b981; }
        .s-warning  .session-prog-fill { background:#f59e0b; }
        .s-critical .session-prog-fill { background:#ef4444; }
        .s-ended    .session-prog-fill { background:#94a3b8; }

        /* ── Insight mini cards ── */
        .insight-card { background:var(--card); border:1px solid var(--border-subtle); border-radius:var(--r-lg); padding:16px 18px; box-shadow:var(--shadow-sm); overflow:hidden; position:relative; transition:transform var(--ease),box-shadow var(--ease); }
        .insight-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
        .insight-card::before { content:attr(data-emoji); position:absolute; right:-8px; top:-8px; font-size:4rem; opacity:.04; pointer-events:none; line-height:1; }

        /* ── Heatmap cells ── */
        .heatmap-cell { height:28px; border-radius:5px; cursor:default; transition:transform .15s; }
        .heatmap-cell:hover { transform:scaleY(1.1); }

        /* ── Library banner ── */
        .lib-banner { background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%); border-radius:var(--r-lg); padding:22px 22px 18px; overflow:hidden; position:relative; }
        .lib-banner::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat; opacity:.4; }
        .lib-stat-item { flex:1; background:rgba(255,255,255,.1); border-radius:10px; padding:8px 10px; border:1px solid rgba(255,255,255,.1); }
        .lib-stat-lbl { font-size:.52rem; font-weight:600; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.06em; }
        .lib-stat-val { font-size:.95rem; font-weight:800; color:white; font-family:var(--mono); }

        /* ── Book list items ── */
        .book-letter { width:34px; height:34px; border-radius:9px; background:var(--indigo-light); color:var(--indigo); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.8rem; flex-shrink:0; }
        body.dark .book-letter { background:rgba(55,48,163,.2); color:#818cf8; }
        .avail-pill { font-size:.6rem; font-weight:800; padding:2px 8px; border-radius:999px; flex-shrink:0; }
        .avail-on  { background:#dcfce7; color:#166634; }
        .avail-off { background:#fee2e2; color:#991b1b; }
        .avail-low { background:#fef3c7; color:#92400e; }
        body.dark .avail-on  { background:rgba(16,185,129,.12); color:#34d399; }
        body.dark .avail-off { background:rgba(239,68,68,.12); color:#f87171; }
        body.dark .avail-low { background:rgba(251,191,36,.12); color:#fcd34d; }

        /* ── AI finder ── */
        .rag-wrap { position:relative; margin-top:12px; }
        .rag-icon-pos { position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none; }
        .search-input { width:100%; padding:11px 12px 11px 34px; border-radius:var(--r-sm); border:1px solid rgba(99,102,241,.15); font-size:.9rem; font-family:var(--font); background:var(--input-bg); color:var(--text); transition:all var(--ease); outline:none; }
        .search-input:focus { border-color:#818cf8; background:var(--card); box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .search-input::placeholder { color:var(--text-sub); }
        .find-btn { display:inline-flex; align-items:center; gap:7px; padding:10px 16px; background:var(--indigo); color:white; border-radius:var(--r-sm); font-size:.8rem; font-weight:700; border:none; cursor:pointer; font-family:var(--font); transition:all var(--ease); touch-action:manipulation; }
        .find-btn:hover { background:#312e81; }
        .find-btn:disabled { opacity:.6; cursor:not-allowed; }

        /* ── Toast ── */
        #tl-toast-container { position:fixed; bottom:calc(80px + env(safe-area-inset-bottom,0px)); right:16px; z-index:9000; display:flex; flex-direction:column; gap:8px; pointer-events:none; max-width:320px; }
        @media(max-width:479px) { #tl-toast-container { left:12px; right:12px; max-width:none; } }
        .tl-toast { background:#0f172a; color:white; border-radius:var(--r-md); padding:12px 14px; box-shadow:var(--shadow-lg); display:flex; align-items:flex-start; gap:10px; pointer-events:auto; animation:toastIn .3s cubic-bezier(.34,1.56,.64,1) both; }
        .tl-toast.dismissing { animation:toastOut .2s ease forwards; }
        @keyframes toastIn  { from{opacity:0;transform:translateX(16px) scale(.96)} to{opacity:1;transform:none} }
        @keyframes toastOut { to{opacity:0;transform:translateX(20px) scale(.96)} }
        .tl-toast-icon { width:30px; height:30px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

        /* ── Login toast ── */
        .login-toast { position:fixed; bottom:calc(var(--mob-nav-total) + 8px); right:16px; z-index:400; max-width:280px; background:#0f172a; border-radius:14px; padding:12px 14px; display:flex; align-items:flex-start; gap:10px; box-shadow:0 8px 32px rgba(0,0,0,.3); transform:translateY(8px); opacity:0; pointer-events:none; transition:all .35s cubic-bezier(.34,1.56,.64,1); }
        .login-toast.show { transform:none; opacity:1; pointer-events:auto; }
        @media(min-width:1024px) { .login-toast { bottom:24px; } }

        @media(max-width:639px) { .topbar { margin-bottom:14px; } .greeting-name { font-size:1.35rem; } }
    </style>
</head>

<body>

<?php $page = 'dashboard';
include(APPPATH . 'Views/partials/sk_layout.php');?>
<div id="notifDD" class="notif-dd">
    <div style="padding:11px 13px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
        <button onclick="markAllRead()" style="font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;font-family:var(--font);touch-action:manipulation;">Mark all read</button>
    </div>
    <div id="notifList" style="max-height:280px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     TOAST CONTAINER (live sessions)
════════════════════════════════════════════════════════════════ -->
<div id="tl-toast-container"></div>

<!-- ═══════════════════════════════════════════════════════════════
     LOGIN TOAST
════════════════════════════════════════════════════════════════ -->
<div id="loginToast" class="login-toast">
    <div id="loginToastIcon" style="width:28px;height:28px;border-radius:8px;background:rgba(99,102,241,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <?= sk_icon('bell', 13, '#818cf8') ?>
    </div>
    <div style="flex:1;min-width:0;">
        <p style="font-weight:700;font-size:12px;color:white;">Welcome back, <?= esc($user_name ?? 'Officer') ?>!</p>
        <p style="font-size:10px;color:rgba(255,255,255,.6);margin-top:2px;"><?= date('l, F j') ?></p>
    </div>
    <button onclick="this.closest('.login-toast').classList.remove('show')" style="background:rgba(255,255,255,.08);border:none;border-radius:6px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;touch-action:manipulation;">
        <?= sk_icon('x', 10, 'white') ?>
    </button>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     DATE MODAL
════════════════════════════════════════════════════════════════ -->
<div id="dateModal" class="modal-back" onclick="if(event.target===this)closeDateModal()">
    <div class="modal-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div>
                <h3 style="font-size:16px;font-weight:700;letter-spacing:-.2px;color:var(--text);" id="modalDateTitle"></h3>
                <p style="font-size:11px;color:var(--text-sub);margin-top:2px;" id="modalDateSub"></p>
            </div>
            <button onclick="closeDateModal()" style="width:36px;height:36px;border-radius:9px;background:var(--input-bg);border:none;color:var(--text-sub);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;touch-action:manipulation;">
                <?= sk_icon('x', 13, 'currentColor') ?>
            </button>
        </div>
        <div id="modalList"></div>
        <div id="modalEmpty" class="hidden" style="text-align:center;padding:24px 12px;">
            <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= sk_icon('calendar-x', 26, 'currentColor') ?></div>
            <p style="font-size:12px;color:var(--text-sub);">No reservations for this date.</p>
        </div>
        <button onclick="closeDateModal()" style="margin-top:16px;width:100%;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:600;color:var(--text-muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;font-family:var(--font);touch-action:manipulation;">Close</button>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MAIN CONTENT AREA
════════════════════════════════════════════════════════════════ -->
<main class="main-area">

    <!-- ── Topbar ── -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow"><?php $hh = (int)date('H'); echo $hh < 12 ? 'Good morning' : ($hh < 17 ? 'Good afternoon' : 'Good evening'); ?></div>
            <div class="greeting-name"><?= esc($user_name ?? 'Officer') ?></div>
            <div class="greeting-sub" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <span><?= date('l, F j, Y') ?></span>
                <span class="sync-badge"><?= sk_icon('refresh', 9, 'currentColor') ?> Synced with Admin</span>
            </div>
        </div>
        <div class="topbar-right">
            <?php if (($pendingUserCount ?? 0) > 0): ?>
                <a href="/sk/user-requests" class="pending-pill">
                    <?= sk_icon('clock', 13, '#d97706') ?>
                    <?= $pendingUserCount ?> pending
                </a>
            <?php endif; ?>
            <button class="icon-btn" onclick="layoutToggleDark()" id="darkBtn" title="Toggle dark mode">
                <span id="darkIcon"><?= sk_icon('sun', 15, 'currentColor') ?></span>
            </button>
            <div class="notif-bell" onclick="toggleNotifications()">
                <div class="icon-btn"><?= sk_icon('bell', 16, 'currentColor') ?></div>
                <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
            </div>
            <a href="/sk/new-reservation" class="reserve-btn">
                <?= sk_icon('plus', 15, 'white') ?> Reserve
            </a>
        </div>
    </div>

    <!-- ── Timer banner (my active / upcoming session) ── -->
    <?php if ($activeBanner || $upcomingBanner):
        $b = $activeBanner ?? $upcomingBanner;
        $isActive = !!$activeBanner;
    ?>
        <div class="timer-banner <?= $isActive ? 'active' : 'upcoming' ?>" id="timerBanner"
             data-start="<?= strtotime($b['reservation_date'] . 'T' . $b['start_time']) ?>"
             data-end="<?= strtotime($b['reservation_date'] . 'T' . $b['end_time']) ?>"
             data-active="<?= $isActive ? '1' : '0' ?>">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <div class="timer-pulse"></div>
                <div style="flex:1;min-width:140px;">
                    <p style="font-weight:700;font-size:.9rem;"><?= $isActive ? 'My session in progress' : 'My session starting soon' ?> · <?= esc($b['resource_name'] ?? 'Resource') ?></p>
                    <p style="font-size:.72rem;opacity:.7;margin-top:2px;font-family:var(--mono);"><?= date('g:i A', strtotime($b['start_time'])) ?> – <?= date('g:i A', strtotime($b['end_time'])) ?></p>
                </div>
                <span id="timerDisplay" style="font-weight:800;font-family:var(--mono);font-size:1rem;flex-shrink:0;">—</span>
            </div>
        </div>
    <?php endif; ?>

    <!-- ── Upcoming pill ── -->
    <?php
    $upcomingForPill = null;
    foreach ($myRes as $r) {
        if (($r['status'] ?? '') === 'approved' && empty($r['claimed'])) {
            $start = strtotime($r['reservation_date'] . 'T' . ($r['start_time'] ?? '00:00'));
            if ($start > time()) { $upcomingForPill = $r; break; }
        }
    }
    if ($upcomingForPill && !$activeBanner): ?>
        <div class="upcoming-pill fade-up-1">
            <div class="up-icon"><?= sk_icon('ticket', 16, 'white') ?></div>
            <div style="flex:1;min-width:0;">
                <div class="up-eyebrow">Upcoming Reservation</div>
                <div class="up-name"><?= esc($upcomingForPill['resource_name'] ?? 'Resource') ?></div>
                <div class="up-time"><?= date('M j, Y', strtotime($upcomingForPill['reservation_date'])) ?> &nbsp;·&nbsp; <?= date('g:i A', strtotime($upcomingForPill['start_time'])) ?> – <?= date('g:i A', strtotime($upcomingForPill['end_time'])) ?></div>
            </div>
            <a href="/sk/my-reservations" class="up-btn">View →</a>
        </div>
    <?php endif; ?>

    <!-- ─────────────────────────────────────────────────────────
         SECTION 1 — Live Monitor
    ────────────────────────────────────────────────────────────── -->
    <p class="section-lbl fade-up-1">
        Live Monitor
        <span class="sync-badge" style="margin-left:6px;">All Users</span>
    </p>
    <div class="card card-p fade-up-1" style="margin-bottom:20px;">
        <div class="card-head">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="card-icon" style="background:#eef2ff;"><?= sk_icon('stopwatch', 16, 'var(--indigo)') ?></div>
                <div>
                    <div class="card-title">Active Sessions</div>
                    <div class="card-sub">System-wide · Real-time</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <?php foreach ([['#10b981','Active'],['#f59e0b','Warning'],['#ef4444','Critical']] as [$c,$l]): ?>
                    <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:var(--text-sub);">
                        <span style="width:7px;height:7px;border-radius:50%;background:<?= $c ?>;display:inline-block;"></span><?= $l ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="sessionsGrid" class="grid-four" style="margin-bottom:0;"></div>
        <p id="noSessions" class="hidden" style="text-align:center;font-size:.85rem;color:var(--text-sub);padding:24px 0;font-weight:500;">
            <span style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= sk_icon('stopwatch', 28, 'currentColor') ?></span>
            No active sessions right now
        </p>
    </div>

    <!-- ─────────────────────────────────────────────────────────
         SECTION 2 — Reservation Overview
    ────────────────────────────────────────────────────────────── -->
    <p class="section-lbl fade-up-2">
        Reservation Overview
        <span class="sync-badge" style="margin-left:6px;">System-wide</span>
    </p>

    <div class="stats-grid fade-up-2">
        <div class="stat-card" style="border-left:4px solid var(--indigo);">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#eef2ff;"><?= sk_icon('layers', 16, '#3730a3') ?></div>
                <span class="stat-badge" style="color:var(--indigo);">+<?= $sysMonthlyTotal ?> mo</span>
            </div>
            <div class="stat-lbl">Total</div>
            <div class="stat-num"><?= $sysTotal ?></div>
            <div class="stat-hint">Avg <strong style="color:var(--indigo);"><?= $sysTotal > 0 ? round($sysTotal / 30, 1) : 0 ?>/day</strong></div>
        </div>
        <div class="stat-card" style="border-left:4px solid #16a34a;">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#dcfce7;"><?= sk_icon('check-circle', 16, '#16a34a') ?></div>
                <span class="stat-badge" style="color:#16a34a;"><?= $sysApprovalRate ?>%</span>
            </div>
            <div class="stat-lbl">Approved</div>
            <div class="stat-num" style="color:#16a34a;"><?= $sysApproved ?></div>
            <div class="prog-bar" style="margin-top:8px;"><div class="prog-fill" style="width:<?= $sysApprovalRate ?>%;background:#16a34a;"></div></div>
            <div class="stat-hint" style="margin-top:4px;">Approval rate</div>
        </div>
        <div class="stat-card" style="border-left:4px solid #d97706;">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#fef3c7;"><?= sk_icon('clock', 16, '#d97706') ?></div>
                <span class="stat-badge" style="color:#d97706;"><?= $sysTodayTotal ?> today</span>
            </div>
            <div class="stat-lbl" style="margin-bottom:8px;">Today</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:4px;text-align:center;">
                <div><div style="font-size:1.3rem;font-weight:800;color:#d97706;font-family:var(--mono);"><?= $sysTodayPending ?></div><div style="font-size:.6rem;color:var(--text-sub);font-weight:700;">Pending</div></div>
                <div><div style="font-size:1.3rem;font-weight:800;color:#16a34a;font-family:var(--mono);"><?= $sysTodayApproved ?></div><div style="font-size:.6rem;color:var(--text-sub);font-weight:700;">Approved</div></div>
                <div><div style="font-size:1.3rem;font-weight:800;color:#7c3aed;font-family:var(--mono);"><?= $sysTodayClaimed ?></div><div style="font-size:.6rem;color:var(--text-sub);font-weight:700;">Claimed</div></div>
            </div>
        </div>
        <div class="stat-card" style="border-left:4px solid #7c3aed;">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#ede9fe;"><?= sk_icon('check-double', 16, '#7c3aed') ?></div>
                <span class="stat-badge" style="color:#7c3aed;"><?= $sysUtilizationRate ?>%</span>
            </div>
            <div class="stat-lbl">Claimed</div>
            <div class="stat-num" style="color:#7c3aed;"><?= $sysClaimed ?></div>
            <div class="prog-bar" style="margin-top:8px;"><div class="prog-fill" style="width:<?= $sysUtilizationRate ?>%;background:#7c3aed;"></div></div>
            <div class="stat-hint" style="margin-top:4px;">Utilization rate</div>
        </div>
    </div>

    <div class="kpi-grid fade-up-2">
        <?php foreach ([
            ['Total',    $sysTotal,             '#3730a3', 'layers'],
            ['Pending',  $sysPending,            '#d97706', 'clock'],
            ['Approved', $sysApproved,           '#16a34a', 'check-circle'],
            ['My Slots', $remainingReservations, '#7c3aed', 'hourglass'],
        ] as [$l, $v, $c, $ico]): ?>
            <div class="kpi-card" style="border-left-color:<?= $c ?>;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                    <span class="stat-lbl"><?= $l ?></span>
                    <?= sk_icon($ico, 14, $c) ?>
                </div>
                <div class="kpi-num" style="color:<?= $c ?>;"><?= $v ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ── Charts ── -->
    <div class="grid-two fade-up-3" style="margin-bottom:20px;">
        <div class="card card-p">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#eef2ff;"><?= sk_icon('chart-line', 16, 'var(--indigo)') ?></div>
                    <div>
                        <div class="card-title">Reservations Trend</div>
                        <div class="card-sub">Last 7 days · All users</div>
                    </div>
                </div>
                <span class="tag tag-approved" style="font-size:.65rem;padding:4px 10px;border-radius:999px;">System-wide</span>
            </div>
            <div class="chart-wrap"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="card card-p">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#ede9fe;"><?= sk_icon('chart-pie', 16, '#7c3aed') ?></div>
                    <div>
                        <div class="card-title">Popular Resources</div>
                        <div class="card-sub">Most reserved · All users</div>
                    </div>
                </div>
                <span class="tag tag-claimed" style="font-size:.65rem;padding:4px 10px;border-radius:999px;">Top 5</span>
            </div>
            <div class="donut-wrap">
                <canvas id="resourceChart" class="donut-canvas"></canvas>
                <div id="resourceLegend" style="flex:1;min-width:0;display:flex;flex-direction:column;gap:8px;"></div>
            </div>
        </div>
    </div>

    <!-- ─────────────────────────────────────────────────────────
         SECTION 3 — Schedule & Activity
    ────────────────────────────────────────────────────────────── -->
    <p class="section-lbl fade-up-3">Schedule &amp; Activity</p>
    <div class="grid-main fade-up-3">
        <!-- Calendar -->
        <div class="card card-p-lg">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#eef2ff;"><?= sk_icon('calendar-days', 16, 'var(--indigo)') ?></div>
                    <div>
                        <div class="card-title">Reservation Calendar</div>
                        <div class="card-sub">All users · Tap date to view</div>
                    </div>
                </div>
                <div class="cal-legend">
                    <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                        <div class="leg-item"><div class="leg-dot" style="background:<?= $c ?>;"></div><span class="leg-lbl"><?= $l ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <div class="side-col">
            <!-- System stats banner -->
            <div style="background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%);border-radius:var(--r-lg);padding:18px;overflow:hidden;position:relative;">
                <div style="position:absolute;inset:0;background:url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'20\' cy=\'20\' r=\'18\' fill=\'none\' stroke=\'rgba(255,255,255,.05)\' stroke-width=\'1\'/%3E%3C/svg%3E') repeat;opacity:.4;pointer-events:none;"></div>
                <div style="position:relative;z-index:1;">
                    <div style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                        <?= sk_icon('bolt', 10, '#a5b4fc') ?> System Stats
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <?php foreach ([
                            ['Approval',    $sysApprovalRate.'%',    'chart-line'],
                            ['Utilization', $sysUtilizationRate.'%', 'chart-pie'],
                            ['My Slots',    $remainingReservations,  'hourglass'],
                            ['Total',       $sysTotal,               'layers'],
                        ] as [$l,$v,$ic]): ?>
                            <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:10px;border:1px solid rgba(255,255,255,.08);">
                                <div style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.55);margin-bottom:3px;display:flex;align-items:center;gap:4px;">
                                    <?= sk_icon($ic, 9, '#a5b4fc') ?><?= $l ?>
                                </div>
                                <div style="font-size:1.3rem;font-weight:800;color:white;font-family:var(--mono);"><?= $v ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="card card-p">
                <div class="section-lbl">Quick Actions</div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <?php foreach ([
                        ['/sk/new-reservation', '#eef2ff', 'plus',        'var(--indigo)', 'New Reservation'],
                        ['/sk/reservations',    '#ede9fe', 'calendar',    '#7c3aed',      'All Reservations'],
                        ['/sk/books',           '#fef3c7', 'book-open',   '#d97706',      'Browse Library'],
                        ['/sk/profile',         '#f3e8ff', 'user',        '#9333ea',      'View Profile'],
                    ] as [$url, $bg, $ico, $color, $label]): ?>
                        <a href="<?= $url ?>" class="qa-link">
                            <div class="qa-icon" style="background:<?= $bg ?>;"><?= sk_icon($ico, 15, $color) ?></div>
                            <?= $label ?>
                            <span class="qa-chev"><?= sk_icon('chevron-right', 13, 'currentColor') ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent bookings -->
            <div class="card card-p" style="flex:1;">
                <div class="card-head" style="margin-bottom:10px;">
                    <div class="section-lbl" style="margin-bottom:0;">Recent Bookings</div>
                    <a href="/sk/reservations" class="link-sm">View all →</a>
                </div>
                <?php $recentAll = array_slice(array_reverse($sysRes), 0, 5); ?>
                <?php if (!empty($recentAll)): ?>
                    <?php foreach ($recentAll as $r):
                        $isCl = in_array($r['claimed'] ?? false, [true,1,'t','true','1'], true);
                        $st   = $isCl ? 'claimed' : ($r['status'] ?? 'pending');
                        $isExpired = !empty($r['reservation_date']) && strtotime($r['reservation_date']) < strtotime('today') && !$isCl;
                        $dt = new DateTime($r['reservation_date'] ?? 'today');
                    ?>
                        <a href="/sk/reservations" class="bk-row" style="<?= $isExpired ? 'opacity:.55' : '' ?>">
                            <div class="bk-date">
                                <div class="bk-month"><?= $dt->format('M') ?></div>
                                <div class="bk-day"><?= $dt->format('j') ?></div>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="bk-name"><?= esc($r['resource_name'] ?? 'Resource') ?></div>
                                <div class="bk-time"><?= esc($r['visitor_name'] ?? $r['full_name'] ?? 'Guest') ?> · <?= date('M j', strtotime($r['reservation_date'] ?? 'today')) ?></div>
                            </div>
                            <span class="tag tag-<?= $st ?>"><?= ucfirst($st) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:22px 12px;">
                        <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= sk_icon('calendar-x', 28, 'currentColor') ?></div>
                        <p style="font-size:12px;color:var(--text-sub);">No bookings yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ─────────────────────────────────────────────────────────
         SECTION 4 — Library
    ────────────────────────────────────────────────────────────── -->
    <p class="section-lbl fade-up-4">
        Library
        <a href="/sk/books" class="link-sm" style="margin-left:auto;">Browse All →</a>
    </p>
    <div class="grid-lib fade-up-4">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Banner -->
            <div class="lib-banner">
                <div style="position:relative;z-index:1;">
                    <div style="font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:4px;">Book Collection</div>
                    <div style="font-size:1.8rem;font-weight:800;color:white;letter-spacing:-.04em;line-height:1.1;"><?= $availableCount ?> <span style="font-size:.9rem;font-weight:500;color:rgba(255,255,255,.55);">available</span></div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.45);margin-top:3px;margin-bottom:16px;"><?= $totalBooks ?> total titles</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <div class="lib-stat-item">
                            <div class="lib-stat-lbl">My Borrows</div>
                            <div class="lib-stat-val"><?= count($myBorrowings) ?></div>
                        </div>
                        <div class="lib-stat-item">
                            <?php $bpct = $totalBooks > 0 ? round($availableCount / $totalBooks * 100) : 0; ?>
                            <div class="lib-stat-lbl">In Stock</div>
                            <div class="lib-stat-val"><?= $bpct ?>%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Finder -->
            <div class="card card-p">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#ede9fe;"><?= sk_icon('sparkles', 16, '#7c3aed') ?></div>
                    <div>
                        <div class="card-title">AI Book Finder</div>
                        <div class="card-sub">Describe what you want to read</div>
                    </div>
                </div>
                <div class="rag-wrap">
                    <span class="rag-icon-pos"><?= sk_icon('search', 13, 'var(--text-sub)') ?></span>
                    <input type="text" id="ragInput" class="search-input"
                        placeholder="e.g. Filipino history, science fiction…"
                        autocomplete="off" autocorrect="off" spellcheck="false"
                        onkeydown="if(event.key==='Enter') doRagSearch()">
                </div>
                <div id="ragSkel" style="display:none;margin-top:.5rem;">
                    <div class="shimmer" style="height:11px;border-radius:4px;margin-bottom:6px;"></div>
                    <div class="shimmer" style="height:11px;border-radius:4px;width:70%;margin-bottom:6px;"></div>
                    <div class="shimmer" style="height:11px;border-radius:4px;width:50%;"></div>
                </div>
                <div id="ragResult" style="display:none;margin-top:.75rem;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-sm);padding:12px 14px;">
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                        <?= sk_icon('robot', 13, 'var(--indigo)') ?>
                        <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#3730a3;">AI Suggestion</p>
                    </div>
                    <p style="font-size:12px;color:#312e81;line-height:1.6;" id="ragText"></p>
                    <div id="ragBooks" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:5px;"></div>
                </div>
                <div id="ragErr" class="flash-err" style="display:none;margin-top:8px;padding:10px 14px;"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:11px;">
                    <button onclick="doRagSearch()" id="ragBtn" class="find-btn">
                        <?= sk_icon('sparkles', 13, 'white') ?> Find Books
                    </button>
                    <a href="/sk/books" class="link-sm">Full library →</a>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Books catalog -->
            <div class="card card-p-lg" style="flex:1;">
                <div class="card-head">
                    <div>
                        <div class="card-title">Books Catalog</div>
                        <div class="card-sub">Availability at a glance</div>
                    </div>
                    <a href="/sk/books" class="link-sm">Browse All →</a>
                </div>
                <?php if (!empty($dashBooks)): ?>
                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <?php foreach (array_slice($dashBooks, 0, 8) as $book):
                            $av = (int)($book['available_copies'] ?? 0);
                            $ac = $av === 0 ? 'avail-off' : ($av <= 1 ? 'avail-low' : 'avail-on');
                            $at = $av === 0 ? 'Out' : ($av <= 1 ? '1 left' : $av . ' left');
                        ?>
                            <a href="/sk/books" style="display:flex;align-items:center;gap:10px;padding:7px 6px;border-radius:10px;text-decoration:none;color:inherit;transition:background .15s;min-width:0;">
                                <div class="book-letter"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.82rem;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($book['title']) ?></div>
                                    <div style="font-size:.7rem;color:var(--text-sub);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($book['author'] ?? '—') ?></div>
                                </div>
                                <span class="avail-pill <?= $ac ?>"><?= $at ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($dashBooks) > 8): ?>
                        <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--border-subtle);text-align:center;">
                            <a href="/sk/books" class="link-sm">+<?= count($dashBooks) - 8 ?> more books →</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:32px 12px;">
                        <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--text-faint);"><?= sk_icon('book-open', 28, 'currentColor') ?></div>
                        <p style="font-size:12px;color:var(--text-sub);">No books available</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- My borrows -->
            <?php if (!empty($myBorrowings)): ?>
                <div class="card card-p">
                    <div class="section-lbl" style="margin-bottom:12px;"><?= sk_icon('bookmark', 13, '#16a34a') ?> My Active Borrows</div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <?php foreach (array_slice($myBorrowings, 0, 4) as $bw):
                            $due = !empty($bw['due_date']) ? strtotime($bw['due_date']) : null;
                            $overdue  = $due && $due < time();
                            $dueSoon  = $due && !$overdue && $due < time() + 3 * 86400;
                        ?>
                            <div style="display:flex;align-items:center;gap:10px;background:var(--input-bg);border-radius:10px;padding:9px 12px;border:1px solid var(--border-subtle);">
                                <div class="book-letter" style="width:30px;height:30px;font-size:.72rem;"><?= mb_strtoupper(mb_substr($bw['book_title'] ?? 'B', 0, 1)) ?></div>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-weight:600;font-size:.8rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($bw['book_title'] ?? 'Book') ?></p>
                                    <p style="font-size:.68rem;color:<?= $overdue ? '#ef4444' : ($dueSoon ? '#d97706' : 'var(--text-sub)') ?>;font-family:var(--mono);"><?= $due ? ($overdue ? 'Overdue · ' : ($dueSoon ? 'Due soon · ' : '')) . date('M j, Y', $due) : 'No due date' ?></p>
                                </div>
                                <span class="tag <?= $overdue ? 'tag-declined' : ($dueSoon ? 'tag-pending' : 'tag-approved') ?>"><?= $overdue ? 'Overdue' : ($dueSoon ? 'Due Soon' : 'Active') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ─────────────────────────────────────────────────────────
         SECTION 5 — Insights
    ────────────────────────────────────────────────────────────── -->
    <p class="section-lbl fade-up-4">
        Insights
        <span style="margin-left:auto;font-size:.65rem;font-weight:700;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:3px 10px;border-radius:999px;">
            <?= sk_icon('sparkles', 10, '#16a34a') ?> Auto-generated
        </span>
    </p>

    <div class="grid-four fade-up-4">
        <div class="insight-card" data-emoji="⏰">
            <div class="card-icon" style="background:#fef3c7;margin-bottom:10px;"><?= sk_icon('clock', 15, '#d97706') ?></div>
            <div class="stat-lbl">Peak Hour</div>
            <div style="font-size:1rem;font-weight:800;color:var(--text);margin-top:4px;line-height:1.3;"><?= esc($insPHL) ?></div>
            <div class="stat-hint" style="margin-top:4px;">Busiest window</div>
            <div class="prog-bar" style="margin-top:10px;"><div class="prog-fill" style="width:<?= max(array_values($insHourArr)) > 0 ? min(100, round($insHourArr[$insPH] / max(array_values($insHourArr)) * 100)) : 0 ?>%;background:#f59e0b;"></div></div>
        </div>
        <div class="insight-card" data-emoji="📅">
            <div class="card-icon" style="background:#eef2ff;margin-bottom:10px;"><?= sk_icon('calendar', 15, 'var(--indigo)') ?></div>
            <div class="stat-lbl">Busiest Day</div>
            <div style="font-size:1rem;font-weight:800;color:var(--text);margin-top:4px;"><?= esc($insPDL) ?></div>
            <div class="stat-hint" style="margin-top:4px;">Most bookings</div>
            <div id="insDowMini" style="display:flex;gap:2px;margin-top:10px;align-items:flex-end;height:20px;"></div>
        </div>
        <div class="insight-card" data-emoji="🖥️">
            <div class="card-icon" style="background:#dcfce7;margin-bottom:10px;"><?= sk_icon('fire', 15, '#16a34a') ?></div>
            <div class="stat-lbl">Most Wanted</div>
            <div style="font-size:.9rem;font-weight:800;color:var(--text);margin-top:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($insTopRes) ?></div>
            <div class="stat-hint" style="margin-top:4px;"><?= $insTopResCnt ?> reservations</div>
            <div style="margin-top:10px;"><span class="tag tag-approved"><?= sk_icon('chart-line', 9, 'currentColor') ?> High demand</span></div>
        </div>
        <div class="insight-card" data-emoji="📈">
            <div class="card-icon" style="background:#ede9fe;margin-bottom:10px;"><?= sk_icon('chart-line', 15, '#7c3aed') ?></div>
            <div class="stat-lbl">WoW Trend</div>
            <div style="font-size:1.1rem;font-weight:800;margin-top:4px;color:<?= $insTrC ?>;"><?= ($insTrD === 'up' ? '+' : '') . $insTrP ?>%</div>
            <div class="stat-hint" style="margin-top:4px;">vs prev 7 days</div>
            <div class="prog-bar" style="margin-top:10px;"><div class="prog-fill" style="width:<?= min(abs($insTrP), 100) ?>%;background:<?= $insTrC ?>;"></div></div>
        </div>
    </div>

    <div class="grid-three fade-up-4">
        <div class="card card-p">
            <div class="card-head">
                <div>
                    <div class="card-title">Hourly Activity Heatmap</div>
                    <div class="card-sub">Booking density by hour</div>
                </div>
                <span class="tag tag-pending" style="font-size:.65rem;padding:4px 10px;border-radius:999px;border:1px solid #fde68a;">Demand Map</span>
            </div>
            <div id="insHeatmap" style="display:grid;grid-template-columns:repeat(12,1fr);gap:4px;"></div>
            <div style="display:flex;justify-content:space-between;margin-top:6px;padding:0 2px;">
                <span class="stat-lbl" style="letter-spacing:normal;">12 AM</span>
                <span class="stat-lbl" style="letter-spacing:normal;">12 PM</span>
                <span class="stat-lbl" style="letter-spacing:normal;">11 PM</span>
            </div>
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border-subtle);">
                <div class="stat-lbl" style="margin-bottom:10px;">Day-of-Week Volume</div>
                <div id="insDowBars" style="display:flex;gap:6px;align-items:flex-end;height:56px;"></div>
                <div id="insDowLabels" style="display:flex;gap:6px;margin-top:6px;"></div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card card-p">
                <div class="card-title" style="margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <?= sk_icon('triangle', 14, '#f87171') ?> Health Indicators
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php foreach ([
                        ['No-show rate', $insNS,             '#f87171', 'Approved but never claimed'],
                        ['Decline rate', $insDR,             '#f59e0b', 'Of all reservations rejected'],
                        ['Claim rate',   $sysUtilizationRate,'#10b981', 'Approved slots used'],
                    ] as [$lbl, $val, $clr, $hint]): ?>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;">
                                <span style="font-weight:600;color:var(--text-muted);"><?= $lbl ?></span>
                                <span style="font-weight:700;color:<?= $clr ?>;"><?= $val ?>%</span>
                            </div>
                            <div class="prog-bar"><div class="prog-fill" style="width:<?= $val ?>%;background:<?= $clr ?>;"></div></div>
                            <p class="stat-hint" style="margin-top:3px;"><?= $hint ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card card-p">
                <div class="card-title" style="margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                    <?= sk_icon('crown', 14, '#f59e0b') ?> Record Day
                </div>
                <div style="font-size:2rem;font-weight:800;color:var(--text);font-family:var(--mono);"><?= $insBDC ?></div>
                <div style="font-size:.82rem;color:var(--text-muted);font-weight:600;"><?= esc($insBDL) ?></div>
                <div class="stat-hint" style="margin-top:4px;">Most reservations in a single day</div>
            </div>
            <div style="border-radius:var(--r-md);padding:14px 16px;border:1px solid var(--indigo-border);background:var(--indigo-light);">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div class="card-icon" style="background:rgba(55,48,163,.12);flex-shrink:0;"><?= sk_icon('lightbulb', 15, 'var(--indigo)') ?></div>
                    <div>
                        <p style="font-size:.75rem;font-weight:800;color:#312e81;margin-bottom:5px;">Smart Suggestion</p>
                        <p style="font-size:.78rem;color:#3730a3;line-height:1.65;font-weight:500;" id="insSuggestion">Analyzing patterns…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-two fade-up-4" style="margin-bottom:0;">
        <div class="card card-p">
            <div class="card-head">
                <div>
                    <div class="card-title">Monthly Seasonality</div>
                    <div class="card-sub">Volume by calendar month</div>
                </div>
                <span class="tag tag-returned" style="font-size:.65rem;padding:4px 10px;border-radius:999px;border:1px solid var(--indigo-border);">Peak: <?= esc($insPML) ?></span>
            </div>
            <div style="position:relative;height:150px;width:100%;"><canvas id="insMonthChart"></canvas></div>
        </div>
        <div class="card card-p">
            <div class="card-head">
                <div>
                    <div class="card-title">Resource Demand Ranking</div>
                    <div class="card-sub">All-time count per resource</div>
                </div>
                <span class="tag tag-approved" style="font-size:.65rem;padding:4px 10px;border-radius:999px;border:1px solid #bbf7d0;">All Time</span>
            </div>
            <div id="insResourceRanking" style="display:flex;flex-direction:column;gap:8px;"></div>
        </div>
    </div>

</main><!-- /.main-area -->

<script>
/* ── Data from PHP ─────────────────────────────────────────────── */
const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content || '';
const NOTIF_KEY    = 'sk_notifs_<?= session()->get('user_id') ?>';
const myResData    = <?= json_encode($myRes)  ?>;
const allResData   = <?= json_encode($sysRes) ?>;
const INS = {
    hourArr:      <?= json_encode(array_values($insHourArr)) ?>,
    dowArr:       <?= json_encode(array_values($insDowArr))  ?>,
    monthArr:     <?= json_encode(array_values($insMonArr))  ?>,
    peakHourIdx:  <?= (int)$insPH ?>,
    peakDowIdx:   <?= (int)$insPD ?>,
    peakMonthIdx: <?= (int)$insPM ?>,
    noShowRate:   <?= (int)$insNS ?>,
    declineRate:  <?= (int)$insDR ?>,
    trendPct:     <?= (int)$insTrP ?>,
    trendDir:     '<?= $insTrD ?>',
    topResource:  <?= json_encode($insTopRes) ?>,
    peakDayLabel: <?= json_encode($insPDL)    ?>,
    resourceMap:  <?= json_encode($insResMap) ?>,
    totalCount:   <?= (int)$sysTotal ?>
};

const clamp = (v,lo,hi) => Math.max(lo, Math.min(hi, v));
const pct   = (v,max)   => max > 0 ? clamp(Math.round(v/max*100), 0, 100) : 0;
const timeAgo = t => {
    const s = Math.floor((Date.now() - new Date(t)) / 1000);
    if (s < 60)    return 'Just now';
    if (s < 3600)  return `${Math.floor(s/60)}m ago`;
    if (s < 86400) return `${Math.floor(s/3600)}h ago`;
    return `${Math.floor(s/86400)}d ago`;
};
const mob = () => window.innerWidth < 640;

/* ── Dark-mode icon sync with layoutToggleDark ─────────────────── */
const _origToggle = window.layoutToggleDark;
window.layoutToggleDark = function () {
    _origToggle();
    syncDarkIcon();
    updateChartsForTheme();
};
function syncDarkIcon() {
    const dark = document.body.classList.contains('dark');
    const el   = document.getElementById('darkIcon');
    if (!el) return;
    el.innerHTML = dark
        ? `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
}

/* ── Login toast ───────────────────────────────────────────────── */
(function () {
    const key = 'sk_toast_' + new Date().toDateString();
    if (sessionStorage.getItem(key)) return;
    sessionStorage.setItem(key, '1');
    const t = document.getElementById('loginToast');
    setTimeout(() => t.classList.add('show'),    900);
    setTimeout(() => t.classList.remove('show'), 6000);
})();

/* ── Timer banner ──────────────────────────────────────────────── */
(function () {
    const banner = document.getElementById('timerBanner');
    if (!banner) return;
    const startMs  = parseInt(banner.dataset.start) * 1000;
    const endMs    = parseInt(banner.dataset.end)   * 1000;
    const isActive = banner.dataset.active === '1';
    const disp     = document.getElementById('timerDisplay');
    function tick () {
        const diff = Math.max(0, isActive ? endMs - Date.now() : startMs - Date.now());
        if (!diff) { disp.textContent = isActive ? 'Ended' : 'Now!'; return; }
        const s = Math.floor(diff/1000), m = Math.floor(s/60), h = Math.floor(m/60);
        disp.textContent = h > 0 ? `${h}h ${m%60}m` : `${m}m ${s%60}s`;
    }
    tick(); setInterval(tick, 1000);
})();

/* ── Notifications ─────────────────────────────────────────────── */
let notifications = [];
const getSeenIds  = () => { try { return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]'); } catch { return []; } };
const saveSeenIds = ids => localStorage.setItem(NOTIF_KEY, JSON.stringify(ids));

function loadNotifications () {
    const seen = getSeenIds();
    notifications = myResData
        .filter(r => ['approved','declined'].includes(r.status || ''))
        .map(r => ({
            id:    parseInt(r.id),
            title: `Reservation ${r.status === 'approved' ? 'Approved' : 'Declined'}`,
            msg:   `${r.resource_name || 'Resource'}`,
            time:  r.updated_at || r.created_at || new Date().toISOString(),
            status: r.status,
            read:  seen.includes(parseInt(r.id))
        }));
    updateBadge(); renderNotifs();
}
function markAllRead () {
    saveSeenIds([...new Set([...getSeenIds(), ...notifications.map(n => n.id)])]);
    notifications.forEach(n => n.read = true);
    updateBadge(); renderNotifs();
}
function updateBadge () {
    const badge  = document.getElementById('notifBadge');
    const unread = notifications.filter(n => !n.read).length;
    badge.style.display = unread > 0 ? 'block' : 'none';
    badge.textContent   = unread > 9 ? '9+' : unread;
}
function renderNotifs () {
    const list = document.getElementById('notifList');
    if (!notifications.length) {
        list.innerHTML = `<div style="text-align:center;padding:24px;"><p style="font-size:12px;color:var(--text-sub);">All caught up!</p></div>`;
        return;
    }
    list.innerHTML = notifications
        .sort((a,b) => new Date(b.time) - new Date(a.time))
        .map(n => `
        <div class="notif-item ${!n.read ? 'unread' : ''}">
            <div style="display:flex;align-items:flex-start;gap:9px;">
                <div style="width:30px;height:30px;background:${n.status==='approved'?'#dcfce7':'#fee2e2'};border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="${n.status==='approved'?'#16a34a':'#dc2626'}" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-weight:700;font-size:12px;color:var(--text);">${n.title}</p>
                    <p style="font-size:10px;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                    <p style="font-size:9px;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p>
                </div>
            </div>
        </div>`).join('');
}
function toggleNotifications () { document.getElementById('notifDD').classList.toggle('show'); }
document.addEventListener('click', e => {
    const dd   = document.getElementById('notifDD');
    const bell = document.querySelector('.notif-bell');
    if (bell && !bell.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('show');
});

/* ── Date modal ────────────────────────────────────────────────── */
function openDateModal (dateStr, items) {
    const d = new Date(dateStr + 'T00:00:00');
    document.getElementById('modalDateTitle').textContent = d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
    document.getElementById('modalDateSub').textContent   = items.length ? `${items.length} reservation${items.length>1?'s':''}` : '';
    const list  = document.getElementById('modalList');
    const empty = document.getElementById('modalEmpty');
    list.innerHTML = '';
    if (items.length) {
        empty.classList.add('hidden');
        const tagMap = {approved:'tag-approved',pending:'tag-pending',declined:'tag-declined',claimed:'tag-claimed',canceled:'tag-canceled'};
        [...items].sort((a,b) => (a.start_time||'').localeCompare(b.start_time||'')).forEach(r => {
            const isCl = r.claimed == 1 || r.claimed === true || r.claimed === 'true';
            const s    = isCl ? 'claimed' : (r.status||'pending').toLowerCase();
            const row  = document.createElement('div');
            row.className = 'date-row';
            row.innerHTML = `
            <div style="width:32px;height:32px;background:var(--input-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border);">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-sub)" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
                <p style="font-weight:600;font-size:13px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Resource'}</p>
                <p style="font-size:10px;color:var(--text-sub);margin-top:1px;font-family:var(--mono);">${(r.visitor_name||r.full_name||'Guest')} · ${r.start_time?r.start_time.slice(0,5):'—'}${r.end_time?'–'+r.end_time.slice(0,5):''}</p>
            </div>
            <span class="tag ${tagMap[s]||'tag-expired'}">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
            list.appendChild(row);
        });
    } else {
        empty.classList.remove('hidden');
    }
    document.getElementById('dateModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeDateModal () {
    document.getElementById('dateModal').classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDateModal(); });

/* ── Live sessions ─────────────────────────────────────────────── */
const TL_WARN = 5*60*1000, TL_CRIT = 2*60*1000;
let sessionState = {};

function getActiveSessions () {
    const today = new Date().toISOString().split('T')[0], now = Date.now();
    return allResData.filter(r => {
        if (!r.start_time||!r.end_time||r.reservation_date!==today) return false;
        if ((r.status||'').toLowerCase() !== 'approved') return false;
        const s = new Date(r.reservation_date+'T'+r.start_time).getTime();
        const e = new Date(r.reservation_date+'T'+r.end_time).getTime();
        return s <= now && e >= now;
    });
}
const fmtMs = ms => {
    if (ms <= 0) return 'Ended';
    const s = Math.floor(ms/1000), m = Math.floor(s/60), h = Math.floor(m/60);
    return h > 0 ? `${h}h ${m%60}m` : m > 0 ? `${m}m ${s%60}s` : `${s}s`;
};
const sessionClass = ms => ms <= 0 ? 's-ended' : ms <= TL_CRIT ? 's-critical' : ms <= TL_WARN ? 's-warning' : 's-ok';

function tlToast (type, title, sub) {
    const c = document.getElementById('tl-toast-container');
    const t = document.createElement('div');
    t.className = 'tl-toast';
    const bg = type === 'warning' ? 'rgba(245,158,11,.2)' : 'rgba(239,68,68,.2)';
    const ic = type === 'warning'
        ? `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.8"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
        : `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;
    t.innerHTML = `<div class="tl-toast-icon" style="background:${bg};">${ic}</div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.75rem;">${title}</p><p style="font-size:.68rem;color:rgba(255,255,255,.6);margin-top:2px;">${sub}</p></div><button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:.75rem;flex-shrink:0;">✕</button>`;
    c.appendChild(t);
    setTimeout(() => { t.classList.add('dismissing'); setTimeout(() => t.remove(), 220); }, 7000);
}

function renderSessions () {
    const sessions = getActiveSessions();
    const grid = document.getElementById('sessionsGrid');
    const noS  = document.getElementById('noSessions');
    const now  = Date.now();
    if (!sessions.length) { grid.innerHTML = ''; noS.classList.remove('hidden'); return; }
    noS.classList.add('hidden');
    sessions.forEach(r => {
        const eMs  = new Date(r.reservation_date+'T'+r.end_time).getTime();
        const sMs  = new Date(r.reservation_date+'T'+r.start_time).getTime();
        const totMs = eMs - sMs, remMs = eMs - now, elMs = now - sMs;
        const prog  = Math.min(100, Math.max(0, (elMs/totMs)*100));
        const cls   = sessionClass(remMs);
        const name  = r.visitor_name || r.full_name || 'Guest';
        const res   = r.resource_name || 'Resource';
        if (!sessionState[r.id]) sessionState[r.id] = {warned:false, expired:false};
        const st = sessionState[r.id];
        if (!st.warned  && remMs > 0 && remMs <= TL_WARN) { st.warned  = true; tlToast('warning', `${name} — 5 min left`, `${res} ending soon`); }
        if (!st.expired && remMs <= 0)                    { st.expired = true; tlToast('expired', `${name}'s session ended`, `${res} time limit reached`); }
        let card = document.getElementById(`tl-${r.id}`);
        if (!card) { card = document.createElement('div'); card.id = `tl-${r.id}`; grid.appendChild(card); }
        card.className = `session-card ${cls}`;
        card.innerHTML = `
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:8px;">
            <div style="min-width:0;flex:1;">
                <p style="font-weight:700;font-size:.82rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</p>
                <p style="font-size:.68rem;color:var(--text-sub);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${res}</p>
            </div>
            <span class="session-countdown">${fmtMs(remMs)}</span>
        </div>
        <div class="session-prog-track"><div class="session-prog-fill" style="width:${prog.toFixed(1)}%"></div></div>
        <div style="display:flex;justify-content:space-between;margin-top:7px;">
            <span style="font-size:.65rem;color:var(--text-sub);font-family:var(--mono);">${(r.start_time||'').slice(0,5)}–${(r.end_time||'').slice(0,5)}</span>
            <span style="font-size:.65rem;font-weight:600;color:var(--text-muted);">${Math.max(0,Math.floor(elMs/60000))}m used</span>
        </div>`;
    });
    const ids = sessions.map(r => `tl-${r.id}`);
    Array.from(grid.children).forEach(c => { if (!ids.includes(c.id)) c.remove(); });
}

/* ── AI Book Finder ────────────────────────────────────────────── */
let ragAbort = null;
async function doRagSearch () {
    const q   = document.getElementById('ragInput').value.trim();
    if (q.length < 2) { document.getElementById('ragInput').focus(); return; }
    if (ragAbort) ragAbort.abort();
    ragAbort = new AbortController();
    const skel   = document.getElementById('ragSkel');
    const result = document.getElementById('ragResult');
    const err    = document.getElementById('ragErr');
    const btn    = document.getElementById('ragBtn');
    result.style.display = 'none'; err.style.display = 'none';
    skel.style.display = 'block'; btn.disabled = true;
    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" style="animation:spin .7s linear infinite"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg> Searching…`;
    try {
        const res = await fetch('/rag/suggest', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
            body: JSON.stringify({query: q}),
            signal: ragAbort.signal
        });
        skel.style.display = 'none';
        if (!res.ok) {
            const code = res.status;
            err.textContent = code===422 ? 'Please enter a valid search query.' : code===429 ? 'Too many requests — please wait.' : code >= 500 ? 'AI service error. Try again later.' : `Request failed (${code}).`;
            err.style.display = 'flex'; return;
        }
        const d = await res.json();
        const books = d.books ?? d.results ?? d.data ?? [];
        document.getElementById('ragText').textContent = d.suggestion || '';
        const booksEl = document.getElementById('ragBooks');
        booksEl.innerHTML = '';
        books.slice(0, 5).forEach(b => {
            const avail = (b.available_copies || 0) > 0;
            const a = document.createElement('a');
            a.href = '/sk/books' + (b.id ? '?id=' + b.id : '');
            a.style.cssText = `display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10px;font-weight:600;border:1px solid;transition:all .15s;text-decoration:none;font-family:var(--font);${avail?'background:var(--card);border-color:var(--indigo-border);color:var(--indigo);':'background:var(--input-bg);border-color:var(--border);color:var(--text-sub);'}`;
            a.textContent = (b.title||'Untitled') + (!avail ? ' (out)' : '');
            booksEl.appendChild(a);
        });
        result.style.display = 'block';
    } catch (e) {
        skel.style.display = 'none';
        if (e.name === 'AbortError') return;
        err.textContent = 'Could not connect. Check your connection and try again.';
        err.style.display = 'flex';
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round" stroke-linejoin="round"/></svg> Find Books`;
        ragAbort = null;
    }
}

/* ── Chart instances ───────────────────────────────────────────── */
let trendChart, resourceChart, monthChart;

function getChartColors () {
    const dark = document.body.classList.contains('dark');
    return { grid: dark ? '#101e35' : '#f1f5f9', tick: dark ? '#4a6fa5' : '#94a3b8' };
}
function updateChartsForTheme () {
    const c = getChartColors();
    [trendChart, monthChart].forEach(ch => {
        if (!ch) return;
        ch.options.scales.x.grid.color  = c.grid;
        ch.options.scales.x.ticks.color = c.tick;
        ch.options.scales.y.grid.color  = c.grid;
        ch.options.scales.y.ticks.color = c.tick;
        ch.update('none');
    });
}

/* ── DOMContentLoaded ──────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    /* Dark-mode pre-init cleanup */
    document.documentElement.classList.remove('dark-pre');
    syncDarkIcon();

    loadNotifications();
    renderSessions(); setInterval(renderSessions, 1000);

    const mobile = mob();
    const cc     = getChartColors();
    const font   = { family: 'Plus Jakarta Sans', size: mobile ? 9 : 11 };

    /* Trend line */
    const tCtx = document.getElementById('trendChart')?.getContext('2d');
    if (tCtx) {
        trendChart = new Chart(tCtx, {
            type: 'line',
            data: { labels: <?= json_encode($chartLabels) ?>, datasets: [{ data: <?= json_encode($chartData) ?>, borderColor: '#3730a3', backgroundColor: 'rgba(55,48,163,.07)', borderWidth: 2.5, tension: 0.4, fill: true, pointBackgroundColor: '#3730a3', pointRadius: mobile ? 3 : 4, pointHoverRadius: mobile ? 5 : 6 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', titleFont: { family: 'Plus Jakarta Sans', weight: '700' }, bodyFont: { family: 'Plus Jakarta Sans' }, padding: 10, cornerRadius: 10 } }, scales: { x: { grid: { display: false }, ticks: { font, color: cc.tick } }, y: { grid: { color: cc.grid }, ticks: { font, color: cc.tick, stepSize: 1 }, beginAtZero: true } } }
        });
    }

    /* Resource donut */
    const rCtx = document.getElementById('resourceChart')?.getContext('2d');
    const rL   = <?= json_encode($resourceLabels) ?>;
    const rD   = <?= json_encode($resourceData)   ?>;
    const pal  = ['#3730a3','#7c3aed','#16a34a','#d97706','#ec4899'];
    if (rCtx) {
        resourceChart = new Chart(rCtx, {
            type: 'doughnut',
            data: { labels: rL, datasets: [{ data: rD, backgroundColor: pal, borderWidth: 0, hoverOffset: 4 }] },
            options: { responsive: false, animation: false, cutout: '65%', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', titleFont: { family: 'Plus Jakarta Sans', weight: '700' }, bodyFont: { family: 'Plus Jakarta Sans' }, padding: 10, cornerRadius: 10 } } }
        });
        const leg = document.getElementById('resourceLegend');
        if (leg) leg.innerHTML = rL.map((l,i) => `<div style="display:flex;align-items:center;gap:8px;min-width:0;"><span style="width:9px;height:9px;border-radius:50%;background:${pal[i]||'#94a3b8'};flex-shrink:0;"></span><span style="font-size:.78rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;font-weight:500;">${l}</span><span style="font-size:.78rem;font-weight:800;color:var(--text);flex-shrink:0;">${rD[i]}</span></div>`).join('');
    }

    /* Calendar */
    const byDate = {};
    allResData.forEach(r => {
        if (!r.reservation_date) return;
        (byDate[r.reservation_date] = byDate[r.reservation_date] || []).push(r);
    });
    const colorMap = { approved: '#10b981', pending: '#fbbf24', declined: '#f87171', claimed: '#a855f7' };
    const events   = allResData.filter(r => r.reservation_date).map(r => {
        const isCl = r.claimed == 1 || r.claimed === true || r.claimed === 'true';
        const s    = isCl ? 'claimed' : (r.status || 'pending').toLowerCase();
        return { title: (r.visitor_name||r.full_name||'Guest') + ' · ' + (r.resource_name||'Res'), start: r.reservation_date + (r.start_time ? 'T' + r.start_time : ''), end: r.reservation_date + (r.end_time ? 'T' + r.end_time : ''), backgroundColor: colorMap[s] || '#94a3b8', borderColor: 'transparent', textColor: '#fff' };
    });
    new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView:   mobile ? 'listWeek' : 'dayGridMonth',
        headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
        events, height: mobile ? 'auto' : 380, eventDisplay: 'block', eventMaxStack: mobile ? 1 : 2,
        dateClick:  info => openDateModal(info.dateStr, byDate[info.dateStr] || []),
        eventClick: info => { const d = info.event.startStr.split('T')[0]; openDateModal(d, byDate[d] || []); },
        dayCellDidMount: info => {
            const d = info.date.toISOString().split('T')[0], cnt = (byDate[d] || []).length;
            if (cnt) { const b = document.createElement('div'); b.style.cssText = 'font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;font-family:var(--mono);'; b.textContent = cnt; info.el.querySelector('.fc-daygrid-day-top')?.appendChild(b); }
        }
    }).render();

    /* ── Insights ── */
    (function () {
        const DOW   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        const DOW_S = ['S','M','T','W','T','F','S'];
        const MONTH = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const { hourArr, dowArr, monthArr, peakHourIdx, peakDowIdx, peakMonthIdx, noShowRate, declineRate, trendPct, trendDir, topResource, peakDayLabel, resourceMap, totalCount } = INS;
        const maxH = Math.max(...hourArr, 1), maxD = Math.max(...dowArr, 1);

        /* Smart suggestion */
        const sg = document.getElementById('insSuggestion');
        if (sg) {
            if (noShowRate > 30)          sg.textContent = `High no-show rate (${noShowRate}%). Consider sending reminders before sessions.`;
            else if (declineRate > 25)    sg.textContent = `Many requests declined (${declineRate}%). Book "${topResource}" earlier for better chances.`;
            else if (trendDir==='up' && trendPct>20) sg.textContent = `System activity up ${trendPct}% this week — high demand!`;
            else sg.textContent = `${peakDayLabel}s have highest demand. "${topResource}" is the most booked resource.`;
        }

        /* Heatmap */
        const hm = document.getElementById('insHeatmap');
        if (hm) {
            hm.innerHTML = '';
            const f12 = h => `${h%12||12}${h<12?'AM':'PM'}`;
            hourArr.forEach((cnt, h) => {
                const cell  = document.createElement('div');
                const alpha = 0.06 + (pct(cnt, maxH) / 100) * 0.9;
                const isPk  = h === peakHourIdx;
                cell.className    = 'heatmap-cell';
                cell.style.cssText = `background:rgba(55,48,163,${alpha.toFixed(2)});${isPk?'box-shadow:0 0 0 2px #3730a3;':''}`;
                cell.title        = `${f12(h)}: ${cnt} reservations`;
                hm.appendChild(cell);
            });
        }

        /* DoW bars */
        const be = document.getElementById('insDowBars');
        const le = document.getElementById('insDowLabels');
        if (be && le) {
            be.innerHTML = le.innerHTML = '';
            const labels = mobile ? DOW_S : DOW;
            dowArr.forEach((cnt, i) => {
                const bar = document.createElement('div');
                bar.style.cssText = `flex:1;border-radius:5px 5px 0 0;background:${i===peakDowIdx?'#3730a3':'#c7d2fe'};height:${Math.max(pct(cnt,maxD),4)}%;min-height:4px;`;
                bar.title = `${DOW[i]}: ${cnt}`; be.appendChild(bar);
                const lbl = document.createElement('div');
                lbl.style.cssText = `flex:1;text-align:center;font-size:${mobile?'8px':'9px'};font-weight:${i===peakDowIdx?'800':'600'};color:${i===peakDowIdx?'#3730a3':'#94a3b8'};`;
                lbl.textContent = labels[i]; le.appendChild(lbl);
            });
        }

        /* Mini sparkline (insight card) */
        const mini = document.getElementById('insDowMini');
        if (mini) {
            mini.innerHTML = '';
            dowArr.forEach((cnt, i) => {
                const b = document.createElement('div');
                b.style.cssText = `flex:1;border-radius:3px;background:${i===peakDowIdx?'#3730a3':'#c7d2fe'};height:${Math.max(pct(cnt,maxD),10)}%;min-height:3px;`;
                mini.appendChild(b);
            });
        }

        /* Monthly bar chart */
        const mCtx = document.getElementById('insMonthChart')?.getContext('2d');
        if (mCtx) {
            monthChart = new Chart(mCtx, {
                type: 'bar',
                data: { labels: MONTH, datasets: [{ data: monthArr, backgroundColor: monthArr.map((_,i) => i===peakMonthIdx?'#3730a3':'rgba(55,48,163,.15)'), borderRadius: 5, borderSkipped: false }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', titleFont: { family: 'Plus Jakarta Sans', weight: '700' }, bodyFont: { family: 'Plus Jakarta Sans' }, padding: 10, cornerRadius: 10, callbacks: { label: ctx => ` ${ctx.raw} reservations` } } }, scales: { x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: mobile?8:10 }, color: cc.tick } }, y: { grid: { color: cc.grid }, beginAtZero: true, ticks: { font: { family: 'Plus Jakarta Sans', size: mobile?8:10 }, color: cc.tick, stepSize: 1 } } } }
            });
        }

        /* Resource ranking */
        const rk = document.getElementById('insResourceRanking');
        if (rk) {
            const entries = Object.entries(resourceMap).sort((a,b) => b[1]-a[1]);
            const topMax  = entries[0]?.[1] || 1;
            const colors  = ['#3730a3','#d97706','#7c3aed','#16a34a','#ec4899','#06b6d4','#f87171'];
            rk.innerHTML = !entries.length
                ? '<p style="font-size:.75rem;color:var(--text-sub);text-align:center;padding:16px;">No data yet</p>'
                : entries.slice(0, 7).map(([name,cnt], i) => {
                    const w = pct(cnt, topMax), c = colors[i]||'#94a3b8';
                    const share = totalCount > 0 ? Math.round(cnt/totalCount*100) : 0;
                    return `<div><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;gap:8px;"><div style="display:flex;align-items:center;gap:8px;min-width:0;"><span style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:white;background:${c};flex-shrink:0;">${i+1}</span><span style="font-size:.78rem;font-weight:600;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</span></div><div style="display:flex;align-items:center;gap:8px;flex-shrink:0;"><span style="font-size:.65rem;color:var(--text-sub);">${share}%</span><span style="font-size:.78rem;font-weight:800;color:var(--text);">${cnt}</span></div></div><div class="prog-bar"><div class="prog-fill" style="width:${w}%;background:${c};"></div></div></div>`;
                }).join('');
        }
    })();
});
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>