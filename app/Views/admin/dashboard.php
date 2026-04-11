<?php
/**
 * Admin Dashboard View — fully debugged + notification system fixed
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Dashboard | Admin</title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <style>
        /* ── Notification dropdown ── */
        .notif-bell { position: relative; }
        .notif-badge-dot {
            position: absolute; top: -5px; right: -5px;
            background: #ef4444; color: white;
            font-family: var(--font); font-size: .55rem; font-weight: 700;
            padding: 2px 5px; border-radius: 999px; min-width: 17px;
            text-align: center; border: 2px solid var(--bg);
            line-height: 1.3; pointer-events: none;
        }
        .notif-dd {
            position: fixed; top: 80px; right: 20px; width: 320px;
            background: var(--card); border-radius: var(--r-xl);
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.09);
            z-index: 200; display: none; overflow: hidden;
        }
        .notif-dd.show { display: block; animation: fadeInDown .15s ease; }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-6px) scale(.98); }
            to   { opacity: 1; transform: none; }
        }
        .notif-item {
            padding: .85rem 1.1rem;
            border-bottom: 1px solid var(--border-subtle);
            transition: background .15s; cursor: pointer;
        }
        .notif-item:hover { background: var(--input-bg); }
        .notif-item.unread { background: var(--indigo-light); }
        .notif-item:last-child { border-bottom: none; }

        /* Fix: Mark all read button focus ring */
        #notifDD-mark-btn {
            font-size: 11px; color: var(--indigo); font-weight: 600;
            background: none; border: none; cursor: pointer;
            outline: none; padding: 4px 8px; border-radius: 6px;
            transition: background .15s; font-family: var(--font);
        }
        #notifDD-mark-btn:hover { background: rgba(99,102,241,.08); }
        #notifDD-mark-btn:focus-visible { outline: 2px solid var(--indigo); }

        @media(max-width:479px) {
            .notif-dd { left: 12px; right: 12px; width: auto; top: 72px; }
        }
    </style>
</head>

<body>

    <?php
    $page = $page ?? 'dashboard';

    /* ── Safe defaults ── */
    $reservations      = $reservations      ?? [];
    $dashBooks         = is_array($dashBooks         ?? null) ? $dashBooks         : [];
    $dashBorrowReqs    = is_array($dashBorrowReqs    ?? null) ? $dashBorrowReqs    : [];
    $bookTotalCount    = (int)($bookTotalCount    ?? 0);
    $bookAvailCount    = (int)($bookAvailCount    ?? 0);
    $pendingBorrowings = (int)($pendingBorrowings ?? 0);
    $total             = (int)($total    ?? 0);
    $approved          = (int)($approved ?? 0);
    $claimed           = (int)($claimed  ?? 0);
    $pending           = (int)($pending  ?? 0);
    $declined          = (int)($declined ?? 0);

    $approvalRate    = $total    > 0 ? round(($approved / $total)    * 100) : 0;
    $utilizationRate = $approved > 0 ? round(($claimed  / $approved) * 100) : 0;

    /* ── Insight calculations ── */
    $insHourArr = array_fill(0, 24, 0);
    $insDowArr  = array_fill(0, 7,  0);
    $insMonArr  = array_fill(0, 12, 0);
    $insResMap  = [];
    $insDateVol = [];
    $ins7 = 0; $insPrev7 = 0;

    foreach ($reservations as $r) {
        if (!empty($r['start_time']))
            $insHourArr[(int)date('G', strtotime($r['start_time']))]++;
        if (!empty($r['reservation_date'])) {
            $ts = strtotime($r['reservation_date']);
            if ($ts !== false) {
                $insDowArr[(int)date('w', $ts)]++;
                $insMonArr[(int)date('n', $ts) - 1]++;
                $dateKey = date('Y-m-d', $ts);
                $insDateVol[$dateKey] = ($insDateVol[$dateKey] ?? 0) + 1;
                $d = (int)floor((time() - $ts) / 86400);
                if ($d >= 0 && $d < 7)  $ins7++;
                if ($d >= 7 && $d < 14) $insPrev7++;
            }
        }
        $resKey = (string)($r['resource_name'] ?? 'Unknown');
        $insResMap[$resKey] = ($insResMap[$resKey] ?? 0) + 1;
    }

    $maxHour = max($insHourArr);
    $maxDow  = max($insDowArr);
    $maxMon  = max($insMonArr);
    $insPH   = $maxHour > 0 ? (int)array_search($maxHour, $insHourArr) : 0;
    $insPD   = $maxDow  > 0 ? (int)array_search($maxDow,  $insDowArr)  : 0;
    $insPM   = $maxMon  > 0 ? (int)array_search($maxMon,  $insMonArr)  : 0;

    $f12    = fn(int $h) => ((($h % 12) ?: 12)) . ' ' . ($h < 12 ? 'AM' : 'PM');
    $endH   = min(23, $insPH + 1);
    $insPHL = $f12($insPH) . '–' . $f12($endH);
    $insPDL = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$insPD] ?? '—';
    $insPML = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][$insPM] ?? '—';

    arsort($insResMap);
    $insTopRes    = count($insResMap) > 0 ? (string)array_key_first($insResMap) : 'N/A';
    $insTopResCnt = count($insResMap) > 0 ? (int)reset($insResMap)              : 0;

    arsort($insDateVol);
    $insBD  = count($insDateVol) > 0 ? (string)array_key_first($insDateVol) : null;
    $insBDC = count($insDateVol) > 0 ? (int)reset($insDateVol)              : 0;
    $insBDL = $insBD ? date('M j, Y', (int)strtotime($insBD)) : 'N/A';

    $insTrP = $insPrev7 > 0
        ? (int)round((($ins7 - $insPrev7) / $insPrev7) * 100)
        : ($ins7 > 0 ? 100 : 0);
    $insTrD = $insTrP >= 0 ? 'up' : 'down';
    $insTrC = $insTrD === 'up' ? '#10b981' : '#ef4444';
    $insNS  = $approved > 0 ? (int)round((($approved - $claimed) / $approved) * 100) : 0;
    $insDR  = $total    > 0 ? (int)round(($declined / $total) * 100)                 : 0;

    $monthlyTotal = (int)($monthlyTotal ?? 0);
    $admin_name   = htmlspecialchars(
        (string)($admin_name ?? session()->get('name') ?? session()->get('username') ?? 'Administrator'),
        ENT_QUOTES, 'UTF-8'
    );

    $bpct = $bookTotalCount > 0
        ? min(100, max(0, (int)round((float)$bookAvailCount / (float)$bookTotalCount * 100)))
        : 0;

    $JSON_FLAGS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE;

    /*
     * FIX: Normalize reservation records so JS always receives
     * visitor_name and full_name as non-null strings.
     * This prevents the date-modal row from silently omitting the name.
     */
    $reservations = array_map(function(array $r): array {
        // Resolve display name from every possible column alias
        $name = (string)(
            $r['visitor_name']  ??
            $r['full_name']     ??
            $r['resident_name'] ??
            $r['user_name']     ??
            $r['name']          ??
            ''
        );
        $r['visitor_name'] = $name;
        $r['full_name']    = $name;
        return $r;
    }, $reservations);
    ?>

    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- ════════ MODALS ════════ -->
    <div id="dateModal" class="modal-back" role="dialog" aria-modal="true" aria-labelledby="modalDateTitle"
         onclick="if(event.target===this)closeDateModal()">
        <div class="modal-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                <div>
                    <h3 style="font-family:var(--font);font-size:16px;font-weight:700;" id="modalDateTitle"></h3>
                    <p style="font-size:11px;color:var(--text-sub);margin-top:2px;" id="modalDateSub"></p>
                </div>
                <button onclick="closeDateModal()" class="modal-close" aria-label="Close date modal">
                    <i class="fa-solid fa-xmark" style="font-size:.8rem;" aria-hidden="true"></i>
                </button>
            </div>
            <div id="modalList"></div>
            <div id="modalEmpty" class="hidden" style="text-align:center;padding:24px 12px;">
                <i class="fa-regular fa-calendar-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;" aria-hidden="true"></i>
                <p style="font-size:12px;color:var(--text-sub);">No reservations for this date.</p>
            </div>
            <button onclick="closeDateModal()" class="modal-cancel" style="margin-top:16px;width:100%;padding:12px;">Close</button>
        </div>
    </div>

    <!-- Print Modal -->
    <div id="tl-print-modal" class="print-modal-back" role="dialog" aria-modal="true" aria-labelledby="tl-modal-title"
         style="display:none;" onclick="if(event.target===this)tlClosePrintModal()">
        <div class="print-modal-card">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div class="card-icon" style="background:#eef2ff;flex-shrink:0;">
                    <i class="fa-solid fa-print" style="color:var(--indigo);font-size:.9rem;" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 style="font-weight:800;font-size:.95rem;" id="tl-modal-title">Session Ended</h3>
                    <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;" id="tl-modal-sub">Did this user print?</p>
                </div>
            </div>
            <p style="font-size:.62rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--text-sub);margin-bottom:8px;">Print status</p>
            <div class="tl-print-toggle" style="margin-bottom:14px;">
                <button id="tl-yes-btn" class="active" onclick="tlSetPrinted(true)" aria-pressed="true">
                    <i class="fa-solid fa-check" style="margin-right:4px;font-size:.75rem;" aria-hidden="true"></i> Yes, printed
                </button>
                <button id="tl-no-btn" onclick="tlSetPrinted(false)" aria-pressed="false">
                    <i class="fa-solid fa-xmark" style="margin-right:4px;font-size:.75rem;" aria-hidden="true"></i> No print
                </button>
            </div>
            <div id="tl-page-section">
                <p style="font-size:.62rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--text-sub);margin-bottom:8px;">Pages printed</p>
                <div class="tl-page-counter">
                    <button onclick="tlAdjustPages(-1)" aria-label="Decrease page count"><i class="fa-solid fa-minus" style="font-size:.7rem;" aria-hidden="true"></i></button>
                    <span class="tl-page-num" id="tl-page-num" aria-live="polite">1</span>
                    <button onclick="tlAdjustPages(1)" aria-label="Increase page count"><i class="fa-solid fa-plus" style="font-size:.7rem;" aria-hidden="true"></i></button>
                </div>
            </div>
            <button class="tl-save-btn" id="tl-save-btn" onclick="tlSavePrint()">
                <i class="fa-solid fa-floppy-disk" style="margin-right:8px;" aria-hidden="true"></i> Save &amp; Log
            </button>
            <button class="tl-skip-btn" onclick="tlSkipPrint()">Skip — don't log</button>
        </div>
    </div>

    <!-- Notification Dropdown -->
    <div id="notifDD" class="notif-dd" role="dialog" aria-label="Notifications">
        <div style="padding:11px 13px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-family:var(--font);font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
            <button id="notifDD-mark-btn" onclick="markAllRead()">Mark all read</button>
        </div>
        <div id="notifList" style="max-height:300px;overflow-y:auto;-webkit-overflow-scrolling:touch;" aria-live="polite"></div>
    </div>

    <div id="tl-toast-container" aria-live="assertive" aria-atomic="false"></div>

    <!-- ════════ MAIN ════════ -->
    <main class="main-area">

        <!-- TOPBAR -->
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">
                    <?php $hh = (int)date('H'); echo $hh < 12 ? 'Good morning' : ($hh < 17 ? 'Good afternoon' : 'Good evening'); ?>,
                    <?= $admin_name ?>
                </div>
                <div class="greeting-name">Admin Dashboard</div>
                <div class="greeting-date">
                    <span><?= date('l, F j, Y') ?></span>
                    <span class="sync-badge">
                        <i class="fa-solid fa-shield-halved" style="font-size:.55rem;" aria-hidden="true"></i> Control Room
                    </span>
                </div>
            </div>
            <div class="topbar-right">
                <?php if ($pending > 0): ?>
                    <a href="/admin/manage-reservations?status=pending" class="pending-pill">
                        <i class="fa-solid fa-clock" style="font-size:.75rem;" aria-hidden="true"></i>
                        <?= $pending ?> pending
                    </a>
                <?php endif; ?>
                <?php if ($pendingBorrowings > 0): ?>
                    <a href="/admin/books#borrowings" class="borrow-pill">
                        <i class="fa-solid fa-book" style="font-size:.75rem;" aria-hidden="true"></i>
                        <?= $pendingBorrowings ?> borrow<?= $pendingBorrowings !== 1 ? 's' : '' ?>
                    </a>
                <?php endif; ?>
                <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode" role="button"
                     tabindex="0" aria-label="Toggle dark mode"
                     onkeydown="if(event.key==='Enter'||event.key===' ')adminToggleDark()">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;" aria-hidden="true"></i></span>
                </div>
                <div class="notif-bell">
                    <div class="icon-btn" role="button" tabindex="0" aria-label="Notifications"
                         aria-expanded="false" id="notifBellBtn"
                         onclick="toggleNotifications()"
                         onkeydown="if(event.key==='Enter'||event.key===' ')toggleNotifications()">
                        <i class="fa-regular fa-bell" style="font-size:.9rem;" aria-hidden="true"></i>
                    </div>
                    <span class="notif-badge-dot" id="notifBadge" style="display:none;" aria-label="unread notifications">0</span>
                </div>
                <a href="/admin/new-reservation" class="action-btn">
                    <i class="fa-solid fa-plus" style="font-size:.8rem;" aria-hidden="true"></i> Reserve
                </a>
            </div>
        </div>

        <!-- ── SECTION 1: LIVE SESSIONS ── -->
        <p class="section-label fade-up-1">
            Live Monitor <span class="sync-badge" style="margin-left:6px;">All Users</span>
        </p>
        <div class="card card-p fade-up-1" style="margin-bottom:20px;">
            <div class="card-head">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="card-icon" style="background:#eef2ff;">
                        <i class="fa-solid fa-stopwatch" style="color:var(--indigo);font-size:.9rem;" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="card-title">Active Sessions</div>
                        <div class="card-sub">System-wide · Real-time</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:var(--text-sub);">
                        <span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;" aria-hidden="true"></span>Active
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:var(--text-sub);">
                        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;" aria-hidden="true"></span>Warning
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;font-size:.65rem;font-weight:600;color:var(--text-sub);">
                        <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;" aria-hidden="true"></span>Critical
                    </span>
                </div>
            </div>
            <div id="tl-sessions-grid" class="grid-four" style="margin-bottom:0;" aria-live="polite" aria-label="Active sessions"></div>
            <p id="tl-no-sessions" class="hidden" style="text-align:center;font-size:.85rem;color:var(--text-sub);padding:24px 0;font-weight:500;">
                <i class="fa-regular fa-circle-pause" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;" aria-hidden="true"></i>
                No active sessions right now
            </p>
        </div>

        <!-- ── SECTION 2: RESERVATION OVERVIEW ── -->
        <p class="section-label fade-up-2">
            Reservation Overview <span class="sync-badge" style="margin-left:6px;">System-wide</span>
        </p>

        <div class="stats-grid fade-up-2">
            <div class="stat-card" style="border-left-color:var(--indigo);">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div class="card-icon" style="background:#eef2ff;">
                        <i class="fa-solid fa-layer-group" style="color:var(--indigo);font-size:.9rem;" aria-hidden="true"></i>
                    </div>
                    <span style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--indigo);">+<?= $monthlyTotal ?> mo</span>
                </div>
                <div class="stat-lbl">Total</div>
                <div class="stat-num"><?= $total ?></div>
                <div class="stat-hint">Avg <strong style="color:var(--indigo);"><?= $total > 0 ? round($total / 30, 1) : 0 ?>/day</strong></div>
            </div>
            <div class="stat-card" style="border-left-color:#16a34a;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div class="card-icon" style="background:#dcfce7;">
                        <i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:.9rem;" aria-hidden="true"></i>
                    </div>
                    <span style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#16a34a;"><?= $approvalRate ?>%</span>
                </div>
                <div class="stat-lbl">Approved</div>
                <div class="stat-num" style="color:#16a34a;"><?= $approved ?></div>
                <div class="prog-bar"><div class="prog-fill" style="width:<?= $approvalRate ?>%;background:#16a34a;"></div></div>
                <div class="stat-hint" style="margin-top:4px;">Approval rate</div>
            </div>
            <div class="stat-card" style="border-left-color:#d97706;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div class="card-icon" style="background:#fef3c7;">
                        <i class="fa-regular fa-clock" style="color:#d97706;font-size:.9rem;" aria-hidden="true"></i>
                    </div>
                    <span style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#d97706;"><?= (int)($todayTotal ?? 0) ?> today</span>
                </div>
                <div class="stat-lbl" style="margin-bottom:8px;">Today</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:4px;text-align:center;">
                    <div>
                        <div style="font-size:1.3rem;font-weight:800;color:#d97706;font-family:var(--mono);"><?= (int)($todayPending  ?? 0) ?></div>
                        <div style="font-size:.6rem;color:var(--text-sub);font-weight:700;">Pending</div>
                    </div>
                    <div>
                        <div style="font-size:1.3rem;font-weight:800;color:#16a34a;font-family:var(--mono);"><?= (int)($todayApproved ?? 0) ?></div>
                        <div style="font-size:.6rem;color:var(--text-sub);font-weight:700;">Approved</div>
                    </div>
                    <div>
                        <div style="font-size:1.3rem;font-weight:800;color:#7c3aed;font-family:var(--mono);"><?= (int)($todayClaimed  ?? 0) ?></div>
                        <div style="font-size:.6rem;color:var(--text-sub);font-weight:700;">Claimed</div>
                    </div>
                </div>
            </div>
            <div class="stat-card" style="border-left-color:#7c3aed;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div class="card-icon" style="background:#ede9fe;">
                        <i class="fa-solid fa-check-double" style="color:#7c3aed;font-size:.9rem;" aria-hidden="true"></i>
                    </div>
                    <span style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#7c3aed;"><?= $utilizationRate ?>%</span>
                </div>
                <div class="stat-lbl">Claimed</div>
                <div class="stat-num" style="color:#7c3aed;"><?= $claimed ?></div>
                <div class="prog-bar"><div class="prog-fill" style="width:<?= $utilizationRate ?>%;background:#7c3aed;"></div></div>
                <div class="stat-hint" style="margin-top:4px;">Utilization rate</div>
            </div>
        </div>

        <div class="kpi-grid fade-up-2">
            <?php foreach (
                [
                    ['Total',    $total,    'border-color:#3730a3', 'color:#3730a3', 'fa-layer-group'],
                    ['Pending',  $pending,  'border-color:#d97706', 'color:#d97706', 'fa-clock'],
                    ['Approved', $approved, 'border-color:#16a34a', 'color:#16a34a', 'fa-circle-check'],
                    ['Declined', $declined, 'border-color:#ef4444', 'color:#ef4444', 'fa-xmark-circle'],
                ] as [$l, $v, $bc, $c, $i]
            ): ?>
                <div class="kpi-card" style="<?= $bc ?>;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                        <span class="stat-lbl"><?= $l ?></span>
                        <i class="fa-solid <?= $i ?>" style="font-size:.85rem;<?= $c ?>;" aria-hidden="true"></i>
                    </div>
                    <div class="kpi-num" style="<?= $c ?>"><?= $v ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Charts -->
        <div class="grid-two fade-up-3" style="margin-bottom:20px;">
            <div class="card card-p">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#eef2ff;">
                            <i class="fa-solid fa-chart-line" style="color:var(--indigo);font-size:.9rem;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="card-title">Reservations Trend</div>
                            <div class="card-sub">Last 7 days · All users</div>
                        </div>
                    </div>
                    <span style="font-size:.65rem;font-weight:700;background:#eef2ff;color:var(--indigo);padding:4px 10px;border-radius:999px;white-space:nowrap;">System-wide</span>
                </div>
                <div class="chart-wrap"><canvas id="trendChart" role="img" aria-label="Reservations trend over the last 7 days"></canvas></div>
            </div>
            <div class="card card-p">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#ede9fe;">
                            <i class="fa-solid fa-chart-pie" style="color:#7c3aed;font-size:.9rem;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="card-title">Popular Resources</div>
                            <div class="card-sub">Most reserved · All users</div>
                        </div>
                    </div>
                    <span style="font-size:.65rem;font-weight:700;background:#ede9fe;color:#7c3aed;padding:4px 10px;border-radius:999px;white-space:nowrap;">Top 5</span>
                </div>
                <div class="resource-chart-wrap">
                    <canvas id="resourceChart" class="resource-chart-canvas"
                            style="width:140px;height:140px;min-width:140px;"
                            role="img" aria-label="Popular resources doughnut chart"></canvas>
                    <div id="resourceLegend" style="flex:1;min-width:0;display:flex;flex-direction:column;gap:8px;"></div>
                </div>
            </div>
        </div>

        <!-- ── SECTION 3: SCHEDULE & MANAGEMENT ── -->
        <p class="section-label fade-up-3">Schedule &amp; Management</p>
        <div class="grid-main fade-up-3">
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#eef2ff;">
                            <i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.9rem;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="card-title">Reservation Calendar</div>
                            <div class="card-sub">All users · Tap date to view</div>
                        </div>
                    </div>
                    <div class="cal-legend">
                        <?php foreach ([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
                            <div class="leg-item">
                                <div class="leg-dot" style="background:<?= $c ?>;" aria-hidden="true"></div>
                                <span class="leg-lbl"><?= $l ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>

            <div class="side-col">
                <!-- System Stats banner -->
                <div style="background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%);border-radius:var(--r-lg);padding:18px;overflow:hidden;position:relative;">
                    <div style="position:absolute;inset:0;background:url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Ccircle cx=\'20\' cy=\'20\' r=\'18\' fill=\'none\' stroke=\'rgba(255,255,255,.05)\' stroke-width=\'1\'/%3E%3C/svg%3E') repeat;opacity:.4;pointer-events:none;" aria-hidden="true"></div>
                    <div style="position:relative;z-index:1;">
                        <div style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-bolt" style="font-size:.6rem;color:#a5b4fc;" aria-hidden="true"></i>System Stats
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <?php foreach (
                                [
                                    ['Approval',    $approvalRate . '%',    'fa-chart-line'],
                                    ['Utilization', $utilizationRate . '%', 'fa-chart-pie'],
                                    ['Resources',   (int)($totalResources ?? 0), 'fa-desktop'],
                                    ['Users',       (int)($totalUsers ?? 0),     'fa-users'],
                                ] as [$l, $v, $ic]
                            ): ?>
                                <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:10px;border:1px solid rgba(255,255,255,.08);">
                                    <div style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.55);margin-bottom:3px;display:flex;align-items:center;gap:4px;">
                                        <i class="fa-solid <?= $ic ?>" style="font-size:.55rem;color:#a5b4fc;" aria-hidden="true"></i><?= $l ?>
                                    </div>
                                    <div style="font-size:1.3rem;font-weight:800;color:white;font-family:var(--mono);"><?= $v ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card card-p">
                    <div class="section-lbl" style="margin-bottom:12px;">Quick Actions</div>
                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <?php foreach (
                            [
                                ['/admin/new-reservation',     'fa-plus',         '#eef2ff', 'var(--indigo)', 'New Reservation'],
                                ['/admin/manage-reservations', 'fa-calendar-alt', '#ede9fe', '#7c3aed',       'All Reservations'],
                                ['/admin/manage-pcs',          'fa-desktop',      '#fef3c7', '#d97706',       'Manage PCs'],
                                ['/admin/manage-sk',           'fa-user-shield',  '#dcfce7', '#16a34a',       'Manage SK Officers'],
                                ['/admin/scanner',             'fa-qrcode',       '#f3e8ff', '#9333ea',       'QR Scanner'],
                            ] as [$url, $ic, $bg, $clr, $lbl]
                        ): ?>
                            <a href="<?= $url ?>" class="qa-link">
                                <div class="qa-icon" style="background:<?= $bg ?>;"><i class="fa-solid <?= $ic ?>" style="color:<?= $clr ?>;font-size:.85rem;" aria-hidden="true"></i></div>
                                <?= $lbl ?>
                                <i class="fa-solid fa-chevron-right qa-chev" style="font-size:.7rem;margin-left:auto;" aria-hidden="true"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Needs Approval -->
                <div class="card card-p" style="flex:1;">
                    <div class="card-head" style="margin-bottom:10px;">
                        <div class="section-lbl" style="margin-bottom:0;">Needs Approval</div>
                        <?php if ($pending > 0): ?>
                            <a href="/admin/manage-reservations?status=pending" class="link-sm">View all →</a>
                        <?php endif; ?>
                    </div>
                    <?php
                    $pl = array_values(array_filter($reservations, fn($r) => ($r['status'] ?? '') === 'pending'));
                    if (!empty($pl)):
                        foreach (array_slice($pl, 0, 4) as $res):
                            $dtObj = null;
                            if (!empty($res['reservation_date'])) {
                                try { $dtObj = new DateTime($res['reservation_date']); } catch (\Exception $e) { $dtObj = null; }
                            }
                    ?>
                        <a href="/admin/manage-reservations?id=<?= (int)$res['id'] ?>" class="bk-row">
                            <?php if ($dtObj): ?>
                                <div class="bk-date" aria-label="<?= $dtObj->format('F j') ?>">
                                    <div class="bk-month"><?= $dtObj->format('M') ?></div>
                                    <div class="bk-day"><?= $dtObj->format('j') ?></div>
                                </div>
                            <?php else: ?>
                                <div style="width:38px;height:38px;background:var(--input-bg);border-radius:10px;border:1px solid var(--border);flex-shrink:0;display:flex;align-items:center;justify-content:center;" aria-hidden="true">
                                    <i class="fa-solid fa-desktop" style="color:var(--text-sub);font-size:.75rem;" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?>
                            <div style="flex:1;min-width:0;">
                                <div class="bk-name"><?= htmlspecialchars((string)($res['resource_name'] ?? 'Resource'), ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="bk-time">
                                    <?= htmlspecialchars((string)($res['visitor_name'] ?? 'Guest'), ENT_QUOTES, 'UTF-8') ?>
                                    · <?= !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—' ?>
                                </div>
                            </div>
                            <span class="tag tag-pending">Pending</span>
                        </a>
                    <?php
                        endforeach;
                        $pc = count($pl);
                        if ($pc > 4): ?>
                            <div style="text-align:center;padding:6px;">
                                <a href="/admin/manage-reservations?status=pending" style="font-size:.75rem;font-weight:700;color:var(--indigo);">+<?= $pc - 4 ?> more →</a>
                            </div>
                        <?php endif;
                    else: ?>
                        <div style="text-align:center;padding:20px 12px;">
                            <i class="fa-regular fa-circle-check" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;" aria-hidden="true"></i>
                            <p style="font-size:12px;color:var(--text-sub);">All caught up!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ── SECTION 4: LIBRARY ── -->
        <p class="section-label fade-up-4">
            Library
            <a href="/admin/books" class="link-sm" style="margin-left:auto;">Browse All →</a>
        </p>

        <div class="grid-lib fade-up-4">

            <div style="display:flex;flex-direction:column;gap:14px;">

                <!-- Banner -->
                <div class="lib-banner">
                    <div style="position:relative;z-index:1;">
                        <div style="font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:4px;">Book Collection</div>
                        <div style="font-size:1.8rem;font-weight:800;color:white;letter-spacing:-.04em;line-height:1.1;">
                            <?= $bookAvailCount ?>
                            <span style="font-size:.9rem;font-weight:500;color:rgba(255,255,255,.55);">available</span>
                        </div>
                        <div style="font-size:.75rem;color:rgba(255,255,255,.45);margin-top:3px;margin-bottom:16px;"><?= $bookTotalCount ?> total titles</div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <div class="lib-stat-item">
                                <div class="lib-stat-lbl">Borrow Reqs</div>
                                <div class="lib-stat-val"><?= $pendingBorrowings ?></div>
                            </div>
                            <div class="lib-stat-item">
                                <div class="lib-stat-lbl">In Stock</div>
                                <div class="lib-stat-val"><?= $bpct ?>%</div>
                            </div>
                            <div class="lib-stat-item">
                                <div class="lib-stat-lbl">Borrowed</div>
                                <div class="lib-stat-val"><?= max(0, $bookTotalCount - $bookAvailCount) ?></div>
                            </div>
                        </div>
                        <a href="/admin/books" class="lib-browse" style="margin-top:14px;">
                            <i class="fa-solid fa-book-open" style="font-size:.75rem;" aria-hidden="true"></i> Browse Library
                        </a>
                    </div>
                </div>

                <!-- Borrow Requests -->
                <div class="card card-p" style="flex:1;">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:#fef3c7;">
                                <i class="fa-solid fa-clipboard-list" style="color:#d97706;font-size:.9rem;" aria-hidden="true"></i>
                            </div>
                            <div>
                                <div class="card-title">Borrow Requests</div>
                                <div class="card-sub">Pending approval</div>
                            </div>
                        </div>
                        <?php if ($pendingBorrowings > 0): ?>
                            <a href="/admin/books#borrowings" class="link-sm">All <?= $pendingBorrowings ?> →</a>
                        <?php endif; ?>
                    </div>
                    <?php
                    $borrowReqsArr = is_array($dashBorrowReqs) ? $dashBorrowReqs : [];
                    $sr = array_slice(
                        array_values(array_filter($borrowReqsArr, fn($b) => ($b['status'] ?? '') === 'pending')),
                        0, 4
                    );
                    if (!empty($sr)): ?>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <?php foreach ($sr as $bw): ?>
                                <div class="borrow-req">
                                    <div class="book-letter" style="width:32px;height:32px;font-size:.75rem;flex-shrink:0;" aria-hidden="true">
                                        <?= mb_strtoupper(mb_substr((string)($bw['book_title'] ?? 'B'), 0, 1)) ?>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-weight:700;font-size:.8rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars((string)($bw['book_title'] ?? 'Unknown Book'), ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                        <p style="font-size:.68rem;color:var(--text-sub);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars((string)($bw['resident_name'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    </div>
                                    <div style="display:flex;gap:5px;flex-shrink:0;">
                                        <form method="post" action="/admin/borrowings/approve/<?= (int)$bw['id'] ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-approve" title="Approve" aria-label="Approve borrow request">✓</button>
                                        </form>
                                        <form method="post" action="/admin/borrowings/reject/<?= (int)$bw['id'] ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-reject" title="Reject" aria-label="Reject borrow request">✕</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center;padding:24px 12px;">
                            <i class="fa-regular fa-circle-check" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;" aria-hidden="true"></i>
                            <p style="font-size:.78rem;color:var(--text-sub);font-weight:600;">No pending requests</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right column: books catalog -->
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                        <div class="card-icon" style="background:var(--indigo-light);color:var(--indigo);flex-shrink:0;">
                            <i class="fa-solid fa-book" style="font-size:.9rem;" aria-hidden="true"></i>
                        </div>
                        <div style="min-width:0;">
                            <div class="card-title">Books Catalog</div>
                            <div class="card-sub">Availability at a glance</div>
                        </div>
                    </div>
                    <a href="/admin/books" class="action-btn" style="padding:7px 14px;font-size:.75rem;flex-shrink:0;">
                        <i class="fa-solid fa-plus" style="font-size:.7rem;" aria-hidden="true"></i> Add Book
                    </a>
                </div>

                <?php if (!empty($dashBooks)):
                    $gc = [
                        'fiction'  => '#3730a3', 'fantasy'  => '#7c3aed',
                        'poetry'   => '#ec4899', 'humor'    => '#f59e0b',
                        'history'  => '#64748b', 'science'  => '#06b6d4',
                        'romance'  => '#f43f5e', 'academic' => '#0369a1',
                    ];
                ?>
                    <div style="display:grid;grid-template-columns:1fr auto;gap:8px;padding:0 6px 8px;border-bottom:1px solid rgba(99,102,241,.07);margin-bottom:4px;">
                        <span class="stat-lbl" style="letter-spacing:.1em;">Title / Author</span>
                        <span class="stat-lbl" style="letter-spacing:.1em;">Stock</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <?php foreach (array_slice($dashBooks, 0, 10) as $book):
                            $g  = strtolower((string)($book['genre'] ?? ''));
                            $sc = $gc[$g] ?? '#3730a3';
                            $av = (int)($book['available_copies'] ?? 0);
                            $ac = $av === 0 ? 'avail-off' : ($av <= 1 ? 'avail-low' : 'avail-on');
                            $at = $av === 0 ? 'Out' : ($av <= 1 ? '1 left' : $av . ' left');
                        ?>
                            <a href="/admin/books" class="book-row" style="overflow:hidden;">
                                <div class="book-spine" style="background:<?= $sc ?>" aria-hidden="true"></div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:.82rem;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        <?= htmlspecialchars((string)($book['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                    <div style="font-size:.7rem;color:var(--text-sub);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        <?= htmlspecialchars((string)($book['author'] ?? '—'), ENT_QUOTES, 'UTF-8') ?>
                                        <?= !empty($book['genre']) ? ' · ' . htmlspecialchars((string)$book['genre'], ENT_QUOTES, 'UTF-8') : '' ?>
                                    </div>
                                </div>
                                <span class="avail-pill <?= $ac ?>" style="flex-shrink:0;"><?= $at ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($dashBooks) > 10): ?>
                        <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(99,102,241,.07);text-align:center;">
                            <a href="/admin/books" class="link-sm">+<?= count($dashBooks) - 10 ?> more books →</a>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div style="text-align:center;padding:48px 12px;">
                        <i class="fa-solid fa-book-open" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:10px;" aria-hidden="true"></i>
                        <p style="font-size:.85rem;color:var(--text-sub);font-weight:600;margin-bottom:14px;">No books yet</p>
                        <a href="/admin/books" class="action-btn" style="display:inline-flex;padding:9px 18px;font-size:.82rem;">
                            <i class="fa-solid fa-plus" style="font-size:.75rem;" aria-hidden="true"></i> Add the first book
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- ── SECTION 5: INSIGHTS ── -->
        <p class="section-label fade-up-4">
            Insights
            <span style="margin-left:auto;font-size:.65rem;font-weight:700;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:3px 10px;border-radius:999px;white-space:nowrap;">
                <i class="fa-solid fa-wand-magic-sparkles" style="font-size:.55rem;" aria-hidden="true"></i> Auto-generated
            </span>
        </p>

        <div class="grid-four fade-up-4">
            <div class="insight-mini" data-emoji="⏰">
                <div class="card-icon" style="background:#fef3c7;margin-bottom:10px;"><i class="fa-solid fa-sun" style="color:#d97706;font-size:.85rem;" aria-hidden="true"></i></div>
                <div class="stat-lbl">Peak Hour</div>
                <div style="font-size:1rem;font-weight:800;margin-top:4px;line-height:1.3;"><?= htmlspecialchars($insPHL, ENT_QUOTES, 'UTF-8') ?></div>
                <div style="font-size:.68rem;color:var(--text-sub);margin-top:4px;">Busiest window</div>
                <div class="prog-bar" style="margin-top:10px;">
                    <div class="prog-fill" style="width:<?= $maxHour > 0 ? min(100, (int)round($insHourArr[$insPH] / $maxHour * 100)) : 0 ?>%;background:#f59e0b;"></div>
                </div>
            </div>
            <div class="insight-mini" data-emoji="📅">
                <div class="card-icon" style="background:#eef2ff;margin-bottom:10px;"><i class="fa-solid fa-calendar-week" style="color:var(--indigo);font-size:.85rem;" aria-hidden="true"></i></div>
                <div class="stat-lbl">Busiest Day</div>
                <div style="font-size:1rem;font-weight:800;margin-top:4px;"><?= htmlspecialchars($insPDL, ENT_QUOTES, 'UTF-8') ?></div>
                <div style="font-size:.68rem;color:var(--text-sub);margin-top:4px;">Most bookings</div>
                <div id="ins-dow-mini" style="display:flex;gap:2px;margin-top:10px;align-items:flex-end;height:20px;" aria-hidden="true"></div>
            </div>
            <div class="insight-mini" data-emoji="🖥️">
                <div class="card-icon" style="background:#dcfce7;margin-bottom:10px;"><i class="fa-solid fa-fire" style="color:#16a34a;font-size:.85rem;" aria-hidden="true"></i></div>
                <div class="stat-lbl">Most Wanted</div>
                <div style="font-size:.9rem;font-weight:800;margin-top:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    <?= htmlspecialchars($insTopRes, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div style="font-size:.68rem;color:var(--text-sub);margin-top:4px;"><?= $insTopResCnt ?> reservations</div>
                <div style="margin-top:10px;">
                    <span style="font-size:.6rem;font-weight:700;background:#dcfce7;color:#166534;padding:2px 8px;border-radius:999px;">
                        <i class="fa-solid fa-arrow-trend-up" style="font-size:.55rem;" aria-hidden="true"></i> High demand
                    </span>
                </div>
            </div>
            <div class="insight-mini" data-emoji="📈">
                <div class="card-icon" style="background:#ede9fe;margin-bottom:10px;"><i class="fa-solid fa-chart-line" style="color:#7c3aed;font-size:.85rem;" aria-hidden="true"></i></div>
                <div class="stat-lbl">WoW Trend</div>
                <div style="font-size:1.1rem;font-weight:800;margin-top:4px;color:<?= $insTrC ?>;"><?= ($insTrD === 'up' ? '+' : '') . $insTrP ?>%</div>
                <div style="font-size:.68rem;color:var(--text-sub);margin-top:4px;">vs prev 7 days</div>
                <div class="prog-bar" style="margin-top:10px;">
                    <div class="prog-fill" style="width:<?= min(abs($insTrP), 100) ?>%;background:<?= $insTrC ?>;"></div>
                </div>
            </div>
        </div>

        <div class="grid-three fade-up-4">
            <div class="card card-p">
                <div class="card-head">
                    <div>
                        <div class="card-title">Hourly Activity Heatmap</div>
                        <div class="card-sub">Booking density by hour</div>
                    </div>
                    <span style="font-size:.65rem;font-weight:700;background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:999px;border:1px solid #fde68a;white-space:nowrap;">Demand Map</span>
                </div>
                <div id="ins-heatmap" style="display:grid;grid-template-columns:repeat(12,1fr);gap:4px;" aria-hidden="true"></div>
                <div style="display:flex;justify-content:space-between;margin-top:6px;padding:0 2px;" aria-hidden="true">
                    <span style="font-size:.6rem;color:var(--text-sub);font-weight:600;">12 AM</span>
                    <span style="font-size:.6rem;color:var(--text-sub);font-weight:600;">12 PM</span>
                    <span style="font-size:.6rem;color:var(--text-sub);font-weight:600;">11 PM</span>
                </div>
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid rgba(99,102,241,.07);">
                    <div class="stat-lbl" style="margin-bottom:10px;">Day-of-Week Volume</div>
                    <div id="ins-dow-bars" style="display:flex;gap:6px;align-items:flex-end;height:56px;" aria-hidden="true"></div>
                    <div id="ins-dow-labels" style="display:flex;gap:6px;margin-top:6px;" aria-hidden="true"></div>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div class="card card-p">
                    <div class="card-title" style="margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-triangle-exclamation" style="color:#f87171;font-size:.85rem;" aria-hidden="true"></i> Health Indicators
                    </div>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;">
                                <span style="font-weight:600;color:var(--text-muted);">No-show rate</span>
                                <span style="font-weight:700;color:#dc2626;"><?= $insNS ?>%</span>
                            </div>
                            <div class="prog-bar"><div class="prog-fill" style="width:<?= $insNS ?>%;background:#f87171;"></div></div>
                            <p style="font-size:.65rem;color:var(--text-sub);margin-top:3px;">Approved but never claimed</p>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;">
                                <span style="font-weight:600;color:var(--text-muted);">Decline rate</span>
                                <span style="font-weight:700;color:#d97706;"><?= $insDR ?>%</span>
                            </div>
                            <div class="prog-bar"><div class="prog-fill" style="width:<?= $insDR ?>%;background:#f59e0b;"></div></div>
                            <p style="font-size:.65rem;color:var(--text-sub);margin-top:3px;">Of all reservations rejected</p>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px;">
                                <span style="font-weight:600;color:var(--text-muted);">Claim rate</span>
                                <span style="font-weight:700;color:#16a34a;"><?= $utilizationRate ?>%</span>
                            </div>
                            <div class="prog-bar"><div class="prog-fill" style="width:<?= $utilizationRate ?>%;background:#10b981;"></div></div>
                            <p style="font-size:.65rem;color:var(--text-sub);margin-top:3px;">Approved slots used</p>
                        </div>
                    </div>
                </div>
                <div class="card card-p">
                    <div class="card-title" style="margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-crown" style="color:#f59e0b;font-size:.85rem;" aria-hidden="true"></i> Record Day
                    </div>
                    <div style="font-size:2rem;font-weight:800;font-family:var(--mono);"><?= $insBDC ?></div>
                    <div style="font-size:.82rem;color:var(--text-muted);font-weight:600;"><?= htmlspecialchars($insBDL, ENT_QUOTES, 'UTF-8') ?></div>
                    <div style="font-size:.7rem;color:var(--text-sub);margin-top:4px;">Most reservations in a single day</div>
                </div>
                <div style="border-radius:var(--r-md);padding:14px 16px;border:1px solid var(--indigo-border);background:var(--indigo-light);">
                    <div style="display:flex;align-items:flex-start;gap:10px;">
                        <div class="card-icon" style="background:rgba(55,48,163,.12);flex-shrink:0;">
                            <i class="fa-solid fa-lightbulb" style="color:var(--indigo);font-size:.85rem;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;font-weight:800;color:#312e81;margin-bottom:5px;">Smart Suggestion</p>
                            <p style="font-size:.78rem;color:#3730a3;line-height:1.65;font-weight:500;" id="ins-suggestion">Analyzing patterns…</p>
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
                    <span style="font-size:.65rem;font-weight:700;background:#eef2ff;color:var(--indigo);padding:4px 10px;border-radius:999px;border:1px solid var(--indigo-border);white-space:nowrap;">
                        Peak: <?= htmlspecialchars($insPML, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
                <div class="chart-wrap" style="height:150px;">
                    <canvas id="ins-month-chart" role="img" aria-label="Monthly reservation volume chart"></canvas>
                </div>
            </div>
            <div class="card card-p">
                <div class="card-head">
                    <div>
                        <div class="card-title">Resource Demand Ranking</div>
                        <div class="card-sub">All-time count per resource</div>
                    </div>
                    <span style="font-size:.65rem;font-weight:700;background:#dcfce7;color:#166634;padding:4px 10px;border-radius:999px;border:1px solid #bbf7d0;white-space:nowrap;">All Time</span>
                </div>
                <div id="ins-resource-ranking" style="display:flex;flex-direction:column;gap:8px;"></div>
            </div>
        </div>

    </main>

    <script>
        const allRes     = <?= json_encode($reservations, $JSON_FLAGS) ?>;
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const PRINT_EP   = '/admin/log-print';
        const INS = {
            hourArr:      <?= json_encode(array_values($insHourArr), $JSON_FLAGS) ?>,
            dowArr:       <?= json_encode(array_values($insDowArr),  $JSON_FLAGS) ?>,
            monthArr:     <?= json_encode(array_values($insMonArr),  $JSON_FLAGS) ?>,
            peakHourIdx:  <?= (int)$insPH ?>,
            peakDowIdx:   <?= (int)$insPD ?>,
            peakMonthIdx: <?= (int)$insPM ?>,
            noShowRate:   <?= (int)$insNS ?>,
            declineRate:  <?= (int)$insDR ?>,
            trendPct:     <?= (int)$insTrP ?>,
            trendDir:     <?= json_encode($insTrD, $JSON_FLAGS) ?>,
            topResource:  <?= json_encode($insTopRes, $JSON_FLAGS) ?>,
            peakDayLabel: <?= json_encode($insPDL, $JSON_FLAGS) ?>,
            resourceMap:  <?= json_encode($insResMap, $JSON_FLAGS) ?>,
            totalCount:   <?= (int)($total) ?>
        };

        /* ─── Utility helpers ─── */
        const clamp    = (v, lo, hi) => Math.max(lo, Math.min(hi, v));
        const pct      = (v, max)    => max > 0 ? clamp(Math.round(v / max * 100), 0, 100) : 0;
        const isMob    = ()          => window.innerWidth < 640;
        const isClaimed = r          => [true, 1, 't', 'true', '1'].includes(r.claimed);
        const timeAgo  = t => {
            const s = Math.floor((Date.now() - new Date(t)) / 1000);
            if (s < 60)    return 'Just now';
            if (s < 3600)  return `${Math.floor(s/60)}m ago`;
            if (s < 86400) return `${Math.floor(s/3600)}h ago`;
            return `${Math.floor(s/86400)}d ago`;
        };
        const escHtml = str => String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        /* ─── FIX: Robust name resolver ───
         * Checks every possible field name so the date-modal always
         * shows a real name instead of a blank line.
         */
        const resolveVisitorName = r =>
            (r.visitor_name  || '').trim() ||
            (r.full_name     || '').trim() ||
            (r.resident_name || '').trim() ||
            (r.user_name     || '').trim() ||
            (r.name          || '').trim() ||
            'Guest';

        /* ═══════════════════════════════════════════
           NOTIFICATIONS  — matches user dashboard
        ═══════════════════════════════════════════ */
        const NOTIF_KEY  = 'admin_notif_seen_ids';
        let   notifications = [];

        const getSeenIds  = () => { try { return JSON.parse(localStorage.getItem(NOTIF_KEY) || '[]'); } catch(e) { return []; } };
        const saveSeenIds = ids => { try { localStorage.setItem(NOTIF_KEY, JSON.stringify(ids)); } catch(e) {} };

        function loadNotifications() {
            const seen = getSeenIds();
            notifications = allRes
                .filter(r => r.status === 'pending')
                .slice(0, 20)
                .map(r => ({
                    id:    parseInt(r.id),
                    title: 'New Pending Request',
                    msg:   `${escHtml(resolveVisitorName(r))} → ${escHtml(r.resource_name || 'Resource')}`,
                    time:  r.created_at || new Date().toISOString(),
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
            const btn = document.getElementById('notifBellBtn');
            if (btn) btn.setAttribute('aria-label', unread > 0 ? `Notifications (${unread} unread)` : 'Notifications');
        }

        function renderNotifs() {
            const list = document.getElementById('notifList');
            if (!notifications.length) {
                list.innerHTML = `
                    <div style="text-align:center;padding:24px 16px;">
                        <i class="fa-regular fa-bell-slash" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                        <p style="font-family:var(--font);font-size:12px;color:var(--text-sub);">All caught up!</p>
                    </div>`;
                return;
            }
            list.innerHTML = [...notifications]
                .sort((a, b) => new Date(b.time) - new Date(a.time))
                .map(n => `
                <div class="notif-item${!n.read ? ' unread' : ''}"
                     onclick="markRead(${n.id}); location='/admin/manage-reservations?id=${encodeURIComponent(n.id)}'"
                     role="button" tabindex="0"
                     onkeydown="if(event.key==='Enter')this.click()">
                    <div style="display:flex;align-items:flex-start;gap:9px;">
                        <div style="width:30px;height:30px;background:#fef3c7;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-clock" style="font-size:.7rem;color:#d97706;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-family:var(--font);font-weight:700;font-size:12px;color:var(--text);">${n.title}</p>
                            <p style="font-family:var(--font);font-size:10px;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
                            <p style="font-family:var(--font);font-size:9px;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p>
                        </div>
                        ${!n.read ? '<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:3px;" aria-hidden="true"></span>' : ''}
                    </div>
                </div>`).join('');
        }

        function toggleNotifications() {
            const dd  = document.getElementById('notifDD');
            const btn = document.getElementById('notifBellBtn');
            const isOpen = dd.classList.toggle('show');
            btn?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        document.addEventListener('click', e => {
            const dd   = document.getElementById('notifDD');
            const bell = document.querySelector('.notif-bell');
            if (!bell?.contains(e.target) && !dd?.contains(e.target)) {
                dd?.classList.remove('show');
                document.getElementById('notifBellBtn')?.setAttribute('aria-expanded', 'false');
            }
        });

        /* ─────────────────── Date modal ─────────────────── */
        function openDateModal(dateStr, list) {
            const safe = (dateStr || '').slice(0, 10);
            const fmt  = new Date(safe + 'T00:00:00').toLocaleDateString('en-US', {
                weekday:'long', month:'long', day:'numeric', year:'numeric'
            });
            document.getElementById('modalDateTitle').textContent = fmt;
            document.getElementById('modalDateSub').textContent  = list?.length
                ? `${list.length} reservation${list.length > 1 ? 's' : ''}`
                : '';
            const c = document.getElementById('modalList'), empty = document.getElementById('modalEmpty');
            c.innerHTML = '';
            if (!list?.length) { empty.classList.remove('hidden'); return; }
            empty.classList.add('hidden');

            [...list]
                .sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''))
                .forEach(r => {
                    const st  = isClaimed(r) ? 'claimed' : (r.status || 'pending');
                    const clr = {
                        approved:'background:#dcfce7;color:#166534',
                        pending :'background:#fef3c7;color:#92400e',
                        declined:'background:#fee2e2;color:#991b1b',
                        claimed :'background:#ede9fe;color:#5b21b6'
                    };
                    const t  = (r.start_time || '').slice(0, 5) || '—';
                    const et = (r.end_time   || '').slice(0, 5) || '';

                    /* FIX: use resolveVisitorName so the name is never blank */
                    const displayName = resolveVisitorName(r);

                    const row = document.createElement('div');
                    row.className = 'date-row';
                    row.setAttribute('role', 'button');
                    row.setAttribute('tabindex', '0');
                    row.onclick   = () => location = `/admin/manage-reservations?id=${encodeURIComponent(r.id)}`;
                    row.onkeydown = e => { if (e.key === 'Enter') row.onclick(); };
                    row.innerHTML = `
                        <div style="width:30px;height:30px;background:#eef2ff;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-desktop" style="font-size:.7rem;color:#3730a3;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(r.resource_name || 'Resource')}</p>
                            <p style="font-size:.7rem;color:var(--text-sub);">${escHtml(displayName)} · ${escHtml(t)}${et ? '–' + escHtml(et) : ''}</p>
                        </div>
                        <span style="padding:2px 8px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase;${clr[st] || 'background:#f1f5f9;color:#64748b'};flex-shrink:0;">${escHtml(st)}</span>`;
                    c.appendChild(row);
                });
            document.getElementById('dateModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDateModal() {
            document.getElementById('dateModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            const printOpen = document.getElementById('tl-print-modal')?.style.display === 'flex';
            const dateOpen  = document.getElementById('dateModal')?.classList.contains('show');
            if (printOpen) tlClosePrintModal();
            else if (dateOpen) closeDateModal();
        });

        /* ─────────────────── Print modal ─────────────────── */
        const TL_LOGGED_KEY = 'tl_admin_print_logged';
        let tlSessions = {}, tlPrintQueue = [], tlCurrentPrint = null, tlPageCount = 1, tlPrinted = true;
        let tlInitialized = false;

        const tlGetLogged  = () => { try { return JSON.parse(localStorage.getItem(TL_LOGGED_KEY) || '[]'); } catch(e) { return []; } };
        const tlMarkLogged = id => {
            try {
                const ids = tlGetLogged();
                if (!ids.includes(id)) { ids.push(id); localStorage.setItem(TL_LOGGED_KEY, JSON.stringify(ids.slice(-500))); }
            } catch(e) {}
        };
        const tlIsLogged = id => tlGetLogged().includes(id);

        function tlOpenPrintModal(r) {
            tlCurrentPrint = r; tlPageCount = 1; tlPrinted = true;
            document.getElementById('tl-modal-title').textContent = resolveVisitorName(r);
            document.getElementById('tl-modal-sub').textContent   = `${r.resource_name || 'Resource'} · Session ended`;
            document.getElementById('tl-page-num').textContent    = '1';
            document.getElementById('tl-page-section').style.display = 'block';
            const yesBtn = document.getElementById('tl-yes-btn');
            const noBtn  = document.getElementById('tl-no-btn');
            yesBtn.classList.add('active');    yesBtn.setAttribute('aria-pressed', 'true');
            noBtn.classList.remove('active');  noBtn.setAttribute('aria-pressed', 'false');
            const pm = document.getElementById('tl-print-modal');
            pm.style.display = 'flex';
            pm.style.alignItems = 'center';
            pm.style.justifyContent = 'center';
            document.body.style.overflow = 'hidden';
        }

        function tlSetPrinted(v) {
            tlPrinted = v;
            const yesBtn = document.getElementById('tl-yes-btn');
            const noBtn  = document.getElementById('tl-no-btn');
            yesBtn.classList.toggle('active', v);    yesBtn.setAttribute('aria-pressed', v ? 'true' : 'false');
            noBtn.classList.toggle('active', !v);    noBtn.setAttribute('aria-pressed', !v ? 'true' : 'false');
            document.getElementById('tl-page-section').style.display = v ? 'block' : 'none';
        }

        function tlAdjustPages(d) {
            tlPageCount = Math.max(1, Math.min(999, tlPageCount + d));
            document.getElementById('tl-page-num').textContent = tlPageCount;
        }

        async function tlSavePrint() {
            if (!tlCurrentPrint) return;
            const btn = document.getElementById('tl-save-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="margin-right:8px;"></i>Saving…';
            const pages = tlPrinted ? clamp(tlPageCount, 1, 999) : 0;
            let success = false;
            try {
                const res = await fetch(PRINT_EP, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body:    JSON.stringify({ reservation_id: tlCurrentPrint.id, printed: tlPrinted, pages })
                });
                if (res.ok) {
                    tlMarkLogged(tlCurrentPrint.id);
                    success = true;
                } else {
                    tlToast('warning', 'Could not save print log', `Server returned ${res.status}. Try again.`);
                }
            } catch(e) {
                tlToast('warning', 'Network error', 'Print log not saved. Check your connection.');
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk" style="margin-right:8px;"></i>Save & Log';
            if (success) { tlClosePrintModal(); tlNextPrintModal(); }
        }

        function tlSkipPrint()      { if (tlCurrentPrint) tlMarkLogged(tlCurrentPrint.id); tlClosePrintModal(); tlNextPrintModal(); }
        function tlClosePrintModal() { const pm = document.getElementById('tl-print-modal'); pm.style.display = 'none'; document.body.style.overflow = ''; tlCurrentPrint = null; }
        function tlNextPrintModal()  { if (tlPrintQueue.length > 0) setTimeout(() => tlOpenPrintModal(tlPrintQueue.shift()), 400); }

        /* ─────────────────── Live sessions ─────────────────── */
        const TL_WARN = 5 * 60 * 1000, TL_CRIT = 2 * 60 * 1000;

        function tlGetActiveSessions() {
            const today = new Date().toISOString().split('T')[0], nowMs = Date.now();
            return allRes.filter(r => {
                if (!r.start_time || !r.end_time || !r.reservation_date) return false;
                if ((r.reservation_date || '').split('T')[0] !== today)  return false;
                if ((r.status || '').toLowerCase() !== 'approved') return false;
                if (!isClaimed(r)) return false;
                const s = new Date(r.reservation_date.split('T')[0] + 'T' + r.start_time).getTime();
                const e = new Date(r.reservation_date.split('T')[0] + 'T' + r.end_time).getTime();
                return s <= nowMs && e >= nowMs;
            });
        }

        const tlFmt   = ms => {
            if (ms <= 0) return 'Ended';
            const s = Math.floor(ms / 1000), m = Math.floor(s / 60), h = Math.floor(m / 60);
            if (h > 0) return `${h}h ${m % 60}m`;
            if (m > 0) return `${m}m ${s % 60}s`;
            return `${s}s`;
        };
        const tlState = ms => ms <= 0 ? 'tl-ended' : ms <= TL_CRIT ? 'tl-critical' : ms <= TL_WARN ? 'tl-warning' : 'tl-ok';

        function tlToast(type, title, sub) {
            const c = document.getElementById('tl-toast-container');
            if (!c) return;
            const t  = document.createElement('div');
            t.className = 'tl-toast';
            const ic = type === 'warning' ? 'fa-triangle-exclamation' : 'fa-clock-rotate-left';
            const bg = type === 'warning' ? 'rgba(245,158,11,.2)' : 'rgba(239,68,68,.2)';
            t.innerHTML = `
                <div class="tl-toast-icon" style="background:${bg};">
                    <i class="fa-solid ${ic}" style="color:${type==='warning'?'#f59e0b':'#ef4444'};font-size:.8rem;"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-weight:700;font-size:.75rem;color:white;">${escHtml(title)}</p>
                    <p style="font-size:.68rem;color:#94a3b8;margin-top:2px;">${escHtml(sub)}</p>
                </div>
                <button onclick="this.closest('.tl-toast').remove()" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:.75rem;flex-shrink:0;" aria-label="Dismiss">
                    <i class="fa-solid fa-xmark"></i>
                </button>`;
            c.appendChild(t);
            setTimeout(() => { t.classList.add('dismissing'); setTimeout(() => t.remove(), 220); }, 7000);
        }

        function tlRender() {
            try {
                const sessions = tlGetActiveSessions();
                const grid     = document.getElementById('tl-sessions-grid');
                const noS      = document.getElementById('tl-no-sessions');
                if (!grid) return;

                if (!sessions.length) {
                    grid.innerHTML = '';
                    noS?.classList.remove('hidden');
                    tlSessions = {};
                    tlInitialized = true;
                    return;
                }
                noS?.classList.add('hidden');

                const nowMs     = Date.now();
                const activeIds = new Set(sessions.map(r => `tl-card-${r.id}`));
                Array.from(grid.children).forEach(c => { if (!activeIds.has(c.id)) c.remove(); });

                sessions.forEach(r => {
                    const datePart = (r.reservation_date || '').split('T')[0];
                    const eMs      = new Date(datePart + 'T' + r.end_time).getTime();
                    const sMs      = new Date(datePart + 'T' + r.start_time).getTime();
                    const totMs    = eMs - sMs;
                    const remMs    = eMs - nowMs;
                    const elMs     = nowMs - sMs;
                    const prog     = Math.min(100, Math.max(0, (elMs / totMs) * 100));
                    const state    = tlState(remMs);
                    const name     = resolveVisitorName(r);
                    const res      = r.resource_name || 'Resource';

                    if (!tlSessions[r.id]) tlSessions[r.id] = { warned: false, expired: false };
                    const s = tlSessions[r.id];

                    if (!s.warned && remMs > 0 && remMs <= TL_WARN) {
                        s.warned = true;
                        if (tlInitialized) tlToast('warning', `${name} — 5 min left`, `${res} ending soon`);
                    }
                    if (!s.expired && remMs <= 0) {
                        s.expired = true;
                        if (tlInitialized) {
                            tlToast('expired', `${name}'s session ended`, `${res} time limit reached`);
                            if (!tlIsLogged(r.id)) {
                                if (!tlCurrentPrint) setTimeout(() => tlOpenPrintModal(r), 1200);
                                else tlPrintQueue.push(r);
                            }
                        }
                    }

                    let card = document.getElementById(`tl-card-${r.id}`);
                    if (!card) {
                        card = document.createElement('div');
                        card.id = `tl-card-${r.id}`;
                        const sf     = (r.start_time || '').substring(0, 5) || '–';
                        const ef     = (r.end_time   || '').substring(0, 5) || '–';
                        const logged = tlIsLogged(r.id);
                        card.className = `tl-session-card ${state}`;
                        card.innerHTML = `
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:8px;">
                                <div style="min-width:0;flex:1;">
                                    <p style="font-weight:700;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(name)}</p>
                                    <p style="font-size:.68rem;color:var(--text-sub);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(res)}</p>
                                </div>
                                <span class="tl-countdown" id="tl-cd-${r.id}">
                                    <i class="fa-regular fa-clock" style="font-size:.6rem;"></i>${escHtml(tlFmt(remMs))}
                                </span>
                            </div>
                            <div class="tl-prog-track"><div class="tl-prog-fill" id="tl-pf-${r.id}" style="width:${prog}%"></div></div>
                            <div style="display:flex;justify-content:space-between;margin-top:7px;">
                                <span style="font-size:.65rem;color:var(--text-sub);font-family:var(--mono);">${escHtml(sf)}–${escHtml(ef)}</span>
                                <span class="tl-used-${r.id}" style="font-size:.65rem;font-weight:600;color:var(--text-muted);">${Math.max(0, Math.floor(elMs/60000))}m used</span>
                            </div>
                            ${logged && remMs <= 0 ? `<div style="margin-top:6px;display:flex;align-items:center;gap:4px;font-size:.65rem;font-weight:700;color:#16a34a;"><i class="fa-solid fa-check" style="font-size:.6rem;"></i>Logged</div>` : ''}`;
                        grid.appendChild(card);
                    } else {
                        card.className = `tl-session-card ${state}`;
                        const cdEl = document.getElementById(`tl-cd-${r.id}`);
                        const pfEl = document.getElementById(`tl-pf-${r.id}`);
                        const usEl = card.querySelector(`.tl-used-${r.id}`);
                        if (cdEl) cdEl.innerHTML = `<i class="fa-regular fa-clock" style="font-size:.6rem;"></i>${escHtml(tlFmt(remMs))}`;
                        if (pfEl) pfEl.style.width = `${prog}%`;
                        if (usEl) usEl.textContent = `${Math.max(0, Math.floor(elMs/60000))}m used`;
                    }
                });

                const activeRIds = new Set(sessions.map(r => r.id));
                Object.keys(tlSessions).forEach(id => { if (!activeRIds.has(id)) delete tlSessions[id]; });
                tlInitialized = true;

            } catch(err) {
                console.error('tlRender error:', err);
            }
        }

        /* ─────────────────── Charts ─────────────────── */
        let trendChartInst = null, monthChartInst = null;

        function getChartColors(isDark) {
            return { grid: isDark ? '#101e35' : '#f1f5f9', tick: isDark ? '#4a6fa5' : '#94a3b8' };
        }

        function updateChartsForTheme(isDark) {
            const c = getChartColors(isDark);
            [trendChartInst, monthChartInst].forEach(chart => {
                if (!chart) return;
                if (chart.options.scales?.x) { chart.options.scales.x.grid.color  = c.grid; chart.options.scales.x.ticks.color = c.tick; }
                if (chart.options.scales?.y) { chart.options.scales.y.grid.color  = c.grid; chart.options.scales.y.ticks.color = c.tick; }
                chart.update('none');
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const _orig = typeof window.adminToggleDark === 'function'
                ? window.adminToggleDark
                : (typeof window.toggleDark === 'function' ? window.toggleDark : null);
            window.adminToggleDark = window.toggleDark = function() {
                if (_orig) _orig.call(this);
                updateChartsForTheme(document.body.classList.contains('dark'));
            };
        }, { once: true });

        let _tlInterval = null;
        function _tlCleanup() { if (_tlInterval) { clearInterval(_tlInterval); _tlInterval = null; } }
        window.addEventListener('beforeunload', _tlCleanup);
        window.addEventListener('pagehide',     _tlCleanup);

        /* ─────────────────── Bootstrap ─────────────────── */
        document.addEventListener('DOMContentLoaded', () => {
            tlRender();
            _tlInterval = setInterval(tlRender, 1000);
            loadNotifications();

            const mob       = isMob();
            const isDark    = document.body.classList.contains('dark');
            const chartFont = { family: 'Plus Jakarta Sans', size: mob ? 9 : 11 };
            const cc        = getChartColors(isDark);

            /* ── Trend Chart ── */
            const tCtx = document.getElementById('trendChart')?.getContext('2d');
            if (tCtx) {
                trendChartInst = new Chart(tCtx, {
                    type: 'line',
                    data: {
                        labels:   <?= json_encode($chartLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], $JSON_FLAGS) ?>,
                        datasets: [{
                            data: <?= json_encode($chartData ?? [0,0,0,0,0,0,0], $JSON_FLAGS) ?>,
                            borderColor:'#3730a3', backgroundColor:'rgba(55,48,163,0.07)',
                            borderWidth:2.5, tension:0.4, fill:true,
                            pointBackgroundColor:'#3730a3',
                            pointRadius: mob ? 3 : 4, pointHoverRadius: mob ? 5 : 6
                        }]
                    },
                    options: {
                        responsive:true, maintainAspectRatio:false,
                        plugins: {
                            legend: { display:false },
                            tooltip: { backgroundColor:'#0f172a', titleFont:{family:'Plus Jakarta Sans',weight:'700'}, bodyFont:{family:'Plus Jakarta Sans'}, padding:10, cornerRadius:10 }
                        },
                        scales: {
                            x: { grid:{display:false}, ticks:{font:chartFont, color:cc.tick} },
                            y: { grid:{color:cc.grid}, ticks:{font:chartFont, color:cc.tick, stepSize:1}, beginAtZero:true }
                        }
                    }
                });
            }

            /* ── Resource doughnut ── */
            const rCtx = document.getElementById('resourceChart')?.getContext('2d');
            const rL   = <?= json_encode($resourceLabels ?? ['No Data'], $JSON_FLAGS) ?>;
            const rD   = <?= json_encode($resourceData   ?? [1],         $JSON_FLAGS) ?>;
            const pal  = ['#3730a3','#7c3aed','#16a34a','#d97706','#ec4899'];
            if (rCtx) {
                const rCanvas = document.getElementById('resourceChart');
                if (!rCanvas.width  || rCanvas.width  < 10) rCanvas.width  = 140;
                if (!rCanvas.height || rCanvas.height < 10) rCanvas.height = 140;
                new Chart(rCtx, {
                    type: 'doughnut',
                    data: { labels: rL, datasets: [{ data:rD, backgroundColor:pal, borderWidth:0, hoverOffset:4 }] },
                    options: {
                        responsive:false, animation:{duration:400}, cutout:'65%',
                        plugins: {
                            legend: { display:false },
                            tooltip: { backgroundColor:'#0f172a', titleFont:{family:'Plus Jakarta Sans',weight:'700'}, bodyFont:{family:'Plus Jakarta Sans'}, padding:10, cornerRadius:10 }
                        }
                    }
                });
                const leg = document.getElementById('resourceLegend');
                if (leg) leg.innerHTML = rL.map((l, i) =>
                    `<div style="display:flex;align-items:center;gap:8px;min-width:0;">
                        <span style="width:9px;height:9px;border-radius:50%;background:${pal[i]||'#94a3b8'};flex-shrink:0;"></span>
                        <span style="font-size:.78rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;font-weight:500;">${escHtml(l)}</span>
                        <span style="font-size:.78rem;font-weight:800;flex-shrink:0;">${escHtml(String(rD[i]??0))}</span>
                    </div>`
                ).join('');
            }

            /* ── Calendar ── */
            const byDate = {};
            allRes.forEach(r => {
                if (!r.reservation_date) return;
                const dk = (r.reservation_date || '').split('T')[0];
                (byDate[dk] = byDate[dk] || []).push(r);
            });

            const events = allRes.filter(r => r.reservation_date).map(r => {
                const dk  = (r.reservation_date || '').split('T')[0];
                const st  = isClaimed(r) ? 'claimed' : (r.status || 'pending');
                const clr = { approved:'#10b981', pending:'#fbbf24', declined:'#f87171', claimed:'#a855f7' };
                return {
                    title:           `${resolveVisitorName(r)} · ${r.resource_name || 'Res'}`,
                    start:           dk + (r.start_time ? 'T' + r.start_time : ''),
                    end:             dk + (r.end_time   ? 'T' + r.end_time   : ''),
                    backgroundColor: clr[st] || '#94a3b8',
                    borderColor:     'transparent',
                    textColor:       '#fff'
                };
            });

            new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView:    'dayGridMonth',
                headerToolbar:  { left:'prev,next', center:'title', right:'today' },
                events,
                height:         mob ? 260 : 380,
                eventDisplay:   'block',
                eventMaxStack:  mob ? 1 : 2,
                dateClick:      info => openDateModal(info.dateStr, byDate[info.dateStr] || []),
                eventClick:     info => {
                    const d   = info.event.start;
                    const key = d
                        ? `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`
                        : (info.event.startStr || '').slice(0, 10);
                    openDateModal(key, byDate[key] || []);
                },
                dayCellDidMount: info => {
                    const d   = info.date;
                    const key = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
                    const cnt = (byDate[key] || []).length;
                    if (cnt) {
                        const b = document.createElement('div');
                        b.style.cssText = 'font-size:8px;font-weight:700;color:white;background:#3730a3;border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;font-family:var(--mono);';
                        b.textContent   = cnt;
                        b.setAttribute('aria-label', `${cnt} reservations`);
                        info.el.querySelector('.fc-daygrid-day-top')?.appendChild(b);
                    }
                }
            }).render();

            /* ══════════════ Insights ══════════════ */
            (function() {
                const DOW   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                const MONTH = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                const { hourArr, dowArr, monthArr, peakHourIdx, peakDowIdx, peakMonthIdx,
                        noShowRate, declineRate, trendPct, trendDir,
                        topResource, peakDayLabel, resourceMap, totalCount } = INS;
                const maxH = Math.max(...hourArr, 1);
                const maxD = Math.max(...dowArr,  1);

                const sg = document.getElementById('ins-suggestion');
                if (sg) {
                    let t = '';
                    if      (noShowRate > 30)                                t = `High no-show rate (${noShowRate}%). Consider sending session reminders.`;
                    else if (declineRate > 25)                               t = `Decline rate elevated (${declineRate}%). Review approval rules or add more resources.`;
                    else if (trendDir === 'up'   && trendPct > 20)           t = `Reservations up ${trendPct}% this week — keep "${topResource}" available.`;
                    else if (trendDir === 'down' && Math.abs(trendPct) > 20) t = `Bookings dropped ${Math.abs(trendPct)}% vs last week. Consider community outreach.`;
                    else                                                      t = `${peakDayLabel}s are your busiest day. Keep "${topResource}" free and well-resourced.`;
                    sg.textContent = t;
                }

                const hm = document.getElementById('ins-heatmap');
                if (hm) {
                    hm.innerHTML = '';
                    const f12 = h => `${h % 12 || 12}${h < 12 ? 'AM' : 'PM'}`;
                    for (let h = 0; h < 24; h++) {
                        const cell = document.createElement('div');
                        cell.className = 'ins-heatmap-cell';
                        const opacity = (0.06 + (pct(hourArr[h], maxH) / 100) * 0.9).toFixed(2);
                        cell.style.cssText = `background:rgba(55,48,163,${opacity});${h === peakHourIdx ? 'box-shadow:0 0 0 2px #3730a3;' : ''}`;
                        cell.title = `${f12(h)}: ${hourArr[h]} reservations`;
                        hm.appendChild(cell);
                    }
                }

                const be = document.getElementById('ins-dow-bars');
                const le = document.getElementById('ins-dow-labels');
                if (be && le) {
                    be.innerHTML = le.innerHTML = '';
                    dowArr.forEach((cnt, i) => {
                        const bar = document.createElement('div');
                        bar.style.cssText = `flex:1;border-radius:5px 5px 0 0;background:${i === peakDowIdx ? '#3730a3' : '#c7d2fe'};height:${Math.max(pct(cnt, maxD), 4)}%;min-height:4px;`;
                        bar.title = `${DOW[i]}: ${cnt}`;
                        be.appendChild(bar);
                        const lbl = document.createElement('div');
                        lbl.style.cssText = `flex:1;text-align:center;font-size:${mob ? '8px' : '9px'};font-weight:${i === peakDowIdx ? '800' : '600'};color:${i === peakDowIdx ? '#3730a3' : '#94a3b8'};`;
                        lbl.textContent = mob ? DOW[i][0] : DOW[i].slice(0, 3);
                        le.appendChild(lbl);
                    });
                }

                const mini = document.getElementById('ins-dow-mini');
                if (mini) {
                    mini.innerHTML = '';
                    dowArr.forEach((cnt, i) => {
                        const b = document.createElement('div');
                        b.style.cssText = `flex:1;border-radius:3px;background:${i === peakDowIdx ? '#3730a3' : '#c7d2fe'};height:${Math.max(pct(cnt, maxD), 10)}%;min-height:3px;`;
                        mini.appendChild(b);
                    });
                }

                const mCtx = document.getElementById('ins-month-chart')?.getContext('2d');
                if (mCtx) {
                    monthChartInst = new Chart(mCtx, {
                        type: 'bar',
                        data: {
                            labels:   MONTH,
                            datasets: [{
                                data: monthArr,
                                backgroundColor: monthArr.map((_, i) => i === peakMonthIdx ? '#3730a3' : 'rgba(55,48,163,.15)'),
                                borderRadius:    5,
                                borderSkipped:   false
                            }]
                        },
                        options: {
                            responsive:true, maintainAspectRatio:false,
                            plugins: {
                                legend: { display:false },
                                tooltip: {
                                    backgroundColor:'#0f172a',
                                    titleFont:{family:'Plus Jakarta Sans',weight:'700'},
                                    bodyFont:{family:'Plus Jakarta Sans'},
                                    padding:10, cornerRadius:10,
                                    callbacks:{ label: ctx => ` ${ctx.raw} reservations` }
                                }
                            },
                            scales: {
                                x: { grid:{display:false}, ticks:{font:{family:'Plus Jakarta Sans',size:mob?8:10},color:cc.tick} },
                                y: { grid:{color:cc.grid}, beginAtZero:true, ticks:{font:{family:'Plus Jakarta Sans',size:mob?8:10},color:cc.tick,stepSize:1} }
                            }
                        }
                    });
                }

                const rk = document.getElementById('ins-resource-ranking');
                if (rk) {
                    const entries = Object.entries(resourceMap).sort((a, b) => b[1] - a[1]);
                    const topMax  = entries[0]?.[1] || 1;
                    const colors  = ['#3730a3','#d97706','#7c3aed','#16a34a','#ec4899','#06b6d4','#f87171'];
                    rk.innerHTML = !entries.length
                        ? '<p style="font-size:.75rem;color:var(--text-sub);text-align:center;padding:16px;">No data yet</p>'
                        : entries.slice(0, 7).map(([name, cnt], i) => {
                            const w     = pct(cnt, topMax);
                            const c     = colors[i] || '#94a3b8';
                            const share = totalCount > 0 ? Math.round(cnt / totalCount * 100) : 0;
                            return `<div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;gap:8px;">
                                    <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                        <span style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:white;background:${c};flex-shrink:0;">${i + 1}</span>
                                        <span style="font-size:.78rem;font-weight:600;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(name)}</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                                        <span style="font-size:.65rem;color:var(--text-sub);">${share}%</span>
                                        <span style="font-size:.78rem;font-weight:800;">${cnt}</span>
                                    </div>
                                </div>
                                <div class="prog-bar"><div class="prog-fill" style="width:${w}%;background:${c};"></div></div>
                            </div>`;
                        }).join('');
                }
            })();
        });
    </script>

    <?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>