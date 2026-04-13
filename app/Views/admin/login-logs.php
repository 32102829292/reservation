<?php
date_default_timezone_set('Asia/Manila');
$page      = 'login-logs';
$user_name = $user_name ?? session()->get('name') ?? 'Administrator';
$processed = [];
$totalSessions = 0;
$activeSessions = 0;
$maxDuration = 1;
foreach (($logs ?? []) as $log) {
    $loginDt  = new DateTime($log['login_time']);
    $logoutDt = !empty($log['logout_time']) ? new DateTime($log['logout_time']) : null;
    $isActive = ($logoutDt === null);
    $dur = 0;
    $durLabel = '—';
    if ($logoutDt) {
        $diff = $loginDt->diff($logoutDt);
        $dur  = $diff->h * 60 + $diff->i + ($diff->d ? $diff->d * 1440 : 0);
        $durLabel = ($diff->d ? $diff->d . 'd ' : '') . ($diff->h ? $diff->h . 'h ' : '') . $diff->i . 'm';
    } elseif ($isActive) {
        $durLabel = 'Active now';
    }
    if ($dur > $maxDuration) $maxDuration = $dur;
    $role = strtolower($log['role'] ?? 'user');
    $processed[] = array_merge($log, ['_login' => $loginDt, '_logout' => $logoutDt, '_active' => $isActive, '_dur' => $dur, '_durLabel' => $durLabel, '_role' => $role]);
    $totalSessions++;
    if ($isActive) $activeSessions++;
}
$closedSessions = $totalSessions - $activeSessions;
$avatarColors = ['bg-indigo-100 text-indigo-700', 'bg-purple-100 text-purple-700', 'bg-emerald-100 text-emerald-700', 'bg-rose-100 text-rose-700', 'bg-amber-100 text-amber-700'];
$roleIcons = ['user' => 'fa-user', 'sk' => 'fa-user-shield', 'admin' => 'fa-crown', 'chairman' => 'fa-crown'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Login Logs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @keyframes livePulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .5
            }
        }

        .dur-bar {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            overflow: hidden;
            margin-top: 4px;
        }

        .dur-fill {
            height: 100%;
            border-radius: 2px;
            background: var(--indigo);
        }

        body.dark .dur-bar {
            background: rgba(99, 102, 241, .15);
        }

        /* ── Log cards ── */
        .log-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .08);
            border-radius: var(--r-lg);
            padding: 16px 18px;
            cursor: pointer;
            transition: all var(--ease);
            box-shadow: var(--shadow-sm);
        }

        .log-card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--indigo-border);
        }

        .log-card.active-session {
            border-left: 4px solid #10b981;
        }

        .log-card.closed-session {
            border-left: 4px solid rgba(99, 102, 241, .2);
        }

        body.dark .log-card {
            background: var(--card);
            border-color: rgba(99, 102, 241, .1);
        }

        body.dark .log-card:hover {
            border-color: rgba(99, 102, 241, .3);
        }

        /* ── Log avatar ── */
        .log-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .82rem;
            flex-shrink: 0;
        }

        /* ── Session detail modal ── */
        .detail-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--indigo-light);
            color: var(--indigo);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: .65rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #94a3b8;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: .88rem;
            font-weight: 700;
            color: #0f172a;
        }

        body.dark .detail-row {
            border-color: var(--border-subtle);
        }

        body.dark .detail-label {
            color: var(--text-sub);
        }

        body.dark .detail-value {
            color: var(--text);
        }

        body.dark .detail-icon {
            background: rgba(55, 48, 163, .2);
            color: #818cf8;
        }

        /* ── Responsive table/card visibility ── */
        @media(min-width:768px) {
            #logCardList {
                display: none !important
            }
        }

        @media(max-width:767px) {
            #desktopTable {
                display: none !important
            }
        }

        /* ── Filter bar date input ── */
        .date-input {
            padding: 10px 12px 10px 34px;
            border-radius: var(--r-sm);
            border: 1px solid rgba(99, 102, 241, .15);
            font-size: .82rem;
            font-family: var(--font);
            background: #f8fafc;
            color: #0f172a;
            transition: all var(--ease);
            outline: none;
        }

        .date-input:focus {
            border-color: #818cf8;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        body.dark .date-input {
            background: var(--input-bg);
            border-color: rgba(99, 102, 241, .18);
            color: var(--text);
            /* FIX: ensure calendar picker UI also goes dark */
            color-scheme: dark;
        }

        body.dark .date-input:focus {
            background: var(--card);
            border-color: #818cf8;
        }

        /* ══════════════════════════════════════════════
           FIX: STAT GRID — 3-column responsive
           Was targeting "repeat(6" (never matched).
           Now targets all .stats-grid on mobile.
        ══════════════════════════════════════════════ */
        @media(max-width:639px) {
            .stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 8px !important;
            }

            .stat-card {
                padding: 11px 12px !important;
            }

            .stat-num {
                font-size: 1.25rem !important;
            }

            .stat-lbl {
                font-size: .56rem !important;
            }
        }

        /* ══════════════════════════════════════════════
           FIX: FILTER TABS — prevent text clipping
        ══════════════════════════════════════════════ */
        .qtab {
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════════════
           PATCH: PAGE HEADER — wrap on mobile
        ══════════════════════════════════════════════ */
        .topbar-right {
            flex-wrap: wrap;
        }

        @media(max-width:480px) {
            .page-title {
                font-size: 1.3rem !important;
            }

            .topbar-right {
                width: 100%;
                justify-content: flex-end;
            }

            .btn-export span.hide-xs {
                display: none;
            }
        }

        /* ══════════════════════════════════════════════
           PATCH: FILTER BAR — stack on mobile
        ══════════════════════════════════════════════ */
        @media(max-width:639px) {
            .card > div[style*="flex-wrap:wrap"] {
                flex-direction: column !important;
                gap: 8px !important;
            }

            .card > div > div[style*="width:160px"] {
                width: 100% !important;
            }

            .card > div > .reset-btn {
                width: 100% !important;
            }
        }

        /* ══════════════════════════════════════════════
           PATCH: RES CARDS — better mobile spacing
        ══════════════════════════════════════════════ */
        @media(max-width:639px) {
            .res-card {
                padding: 12px 14px !important;
                border-radius: 14px !important;
            }
        }

        /* ══════════════════════════════════════════════
           PATCH: DARK MODE — unified palette
        ══════════════════════════════════════════════ */
        /* Stat cards */
        body.dark .stat-card {
            background: #0b1628 !important;
            border-color: rgba(99, 102, 241, .15) !important;
        }

        /* Filter card */
        body.dark .card {
            background: #0b1628 !important;
            border-color: rgba(99, 102, 241, .1) !important;
        }

        /* Search input */
        body.dark .search-input {
            background: #101e35 !important;
            border-color: rgba(99, 102, 241, .18) !important;
            color: #e2eaf8 !important;
        }

        body.dark .search-input::placeholder {
            color: #4a6fa5 !important;
        }

        /* Date input — id selector kept for specificity, class already has color-scheme:dark above */
        body.dark #dateInput {
            background: #101e35 !important;
            border-color: rgba(99, 102, 241, .18) !important;
            color: #e2eaf8 !important;
        }

        /* Res cards */
        body.dark .res-card {
            background: #0b1628 !important;
            border-color: rgba(99, 102, 241, .1) !important;
        }

        body.dark .res-card:hover {
            border-color: var(--indigo) !important;
        }

        /* Modal */
        body.dark .modal-box {
            background: #0b1628 !important;
        }

        body.dark .modal-box::-webkit-scrollbar-thumb {
            background: #1e3a5f;
        }

        body.dark #dPrintLogForm {
            background: #060e1e !important;
            border-color: rgba(99, 102, 241, .12) !important;
        }

        body.dark #dPrintLogForm input[type=number] {
            background: #101e35 !important;
            border-color: rgba(99, 102, 241, .18) !important;
            color: #e2eaf8 !important;
        }

        body.dark #dPrintLog {
            background: #060e1e !important;
            border-color: rgba(99, 102, 241, .1) !important;
        }

        body.dark .unclaimed-banner {
            background: rgba(251, 146, 60, .1) !important;
            border-color: rgba(251, 146, 60, .3) !important;
        }

        body.dark .ticket-section {
            background: rgba(99, 102, 241, .08) !important;
            border-color: rgba(99, 102, 241, .3) !important;
        }

        /* Icon button */
        body.dark .icon-btn {
            background: #101e35 !important;
            border-color: rgba(99, 102, 241, .18) !important;
            color: #7fb3e8 !important;
        }

        body.dark .icon-btn:hover {
            background: rgba(99, 102, 241, .12) !important;
            color: #a5b4fc !important;
        }

        /* Table wrapper */
        body.dark .tbl-wrap {
            background: #0b1628 !important;
            border-color: rgba(99, 102, 241, .1) !important;
        }

        body.dark .res-row:hover td {
            background: rgba(99, 102, 241, .06) !important;
        }

        /* Export button dark */
        body.dark .btn-export {
            box-shadow: 0 4px 12px rgba(55, 48, 163, .4) !important;
        }

        /* Page header text */
        body.dark .page-eyebrow {
            color: #4a6fa5 !important;
        }

        body.dark .page-title {
            color: #e2eaf8 !important;
        }

        body.dark .page-sub {
            color: #4a6fa5 !important;
        }

        /* Flash messages */
        body.dark .flash-ok {
            background: rgba(55, 48, 163, .2) !important;
            border-color: rgba(99, 102, 241, .3) !important;
            color: #a5b4fc !important;
        }

        body.dark .flash-err {
            background: rgba(220, 38, 38, .1) !important;
            border-color: rgba(248, 113, 113, .3) !important;
            color: #f87171 !important;
        }

        /* Auto-refresh indicator */
        body.dark #autoRefreshIndicator {
            background: rgba(11, 22, 40, .95) !important;
        }

        /* Result count */
        body.dark #resultCount,
        body.dark #tableFooter {
            color: #4a6fa5 !important;
        }
    </style>
</head>

<body>

    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- ════ DETAIL MODAL ════ -->
    <div id="detailModal" class="modal-back" onclick="if(event.target===this)closeDetail()">
        <div class="modal-card">
            <div style="padding:14px 20px 0;">
                <div class="sheet-handle"></div>
            </div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:12px 20px 14px;">
                <div>
                    <div class="modal-title-lbl">Login Logs</div>
                    <h3 class="modal-title">Session Details</h3>
                </div>
                <button onclick="closeDetail()" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button>
            </div>
            <div id="dHero" style="margin:0 20px 12px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-md);padding:14px;display:flex;align-items:center;gap:12px;"></div>
            <div id="dStatusBar" style="margin:0 20px 14px;border-radius:var(--r-sm);padding:10px 14px;display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:700;"></div>
            <div style="padding:0 20px 4px;">
                <div class="detail-row">
                    <div class="detail-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <div class="detail-label">Email</div>
                        <div id="dEmail" class="detail-value"></div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div>
                        <div class="detail-label">Role</div>
                        <div id="dRole" class="detail-value"></div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-icon"><i class="fa-solid fa-right-to-bracket"></i></div>
                    <div>
                        <div class="detail-label">Login Time</div>
                        <div id="dLogin" class="detail-value"></div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                    <div>
                        <div class="detail-label">Logout Time</div>
                        <div id="dLogout" class="detail-value"></div>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-icon"><i class="fa-regular fa-clock"></i></div>
                    <div>
                        <div class="detail-label">Duration</div>
                        <div id="dDur" class="detail-value"></div>
                    </div>
                </div>
            </div>
            <div style="padding:14px 20px 20px;border-top:1px solid rgba(99,102,241,.07);margin-top:8px;">
                <button onclick="closeDetail()" class="modal-cancel" style="width:100%;">Close</button>
            </div>
        </div>
    </div>

    <!-- ════ MAIN ════ -->
    <main class="main-area">

        <div class="topbar fade-up">
            <div>
                <div class="page-eyebrow">Admin Portal</div>
                <div class="page-title">Login Logs</div>
                <div class="page-sub">Authentication history &amp; session tracking</div>
            </div>
            <div class="topbar-right">
                <?php if ($activeSessions > 0): ?>
                    <div style="display:flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #86efac;color:#166534;padding:8px 14px;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;">
                        <span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;animation:livePulse 1.5s infinite;"></span>
                        <?= $activeSessions ?> active session<?= $activeSessions > 1 ? 's' : '' ?>
                    </div>
                <?php endif; ?>
                <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
                </div>
                <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--r-sm);padding:8px 14px;display:flex;align-items:center;gap:6px;">
                    <i class="fa-regular fa-calendar" style="color:var(--indigo);font-size:.78rem;"></i>
                    <span style="font-size:.78rem;font-weight:700;color:var(--text);"><?= date('M j, Y') ?></span>
                </div>
            </div>
        </div>

        <p class="section-label fade-up-1">Overview</p>
        <div class="stats-grid fade-up-1" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card" style="border-left-color:var(--indigo);" onclick="setFilter('all')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div class="stat-lbl">Total Sessions</div><i class="fa-solid fa-list" style="color:var(--indigo);font-size:.85rem;"></i>
                </div>
                <div class="stat-num" style="color:var(--indigo);"><?= $totalSessions ?></div>
            </div>
            <div class="stat-card" style="border-left-color:#16a34a;" onclick="setFilter('active')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div class="stat-lbl">Active Now</div><span style="width:8px;height:8px;background:#10b981;border-radius:50%;"></span>
                </div>
                <div class="stat-num" style="color:#16a34a;"><?= $activeSessions ?></div>
            </div>
            <div class="stat-card" style="border-left-color:#94a3b8;" onclick="setFilter('closed')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div class="stat-lbl">Closed</div><i class="fa-solid fa-circle-xmark" style="color:#94a3b8;font-size:.85rem;"></i>
                </div>
                <div class="stat-num" style="color:#94a3b8;"><?= $closedSessions ?></div>
            </div>
        </div>

        <div class="card fade-up-1" style="padding:16px 20px;margin-bottom:16px;">
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:12px;">
                <!-- Search with inline clear X -->
                <div class="search-wrap" style="flex:1;min-width:180px;position:relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.75rem;pointer-events:none;z-index:1;"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email, or role…" oninput="applyFilters();toggleClear('searchInput','searchClear')" style="padding-right:30px;">
                    <button id="searchClear" onclick="document.getElementById('searchInput').value='';applyFilters();toggleClear('searchInput','searchClear');" title="Clear search"
                        style="display:none;position:absolute;right:9px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.75rem;padding:2px 4px;line-height:1;border-radius:4px;transition:color .15s;"
                        onmouseover="this.style.color='#6366f1'" onmouseout="this.style.color='#94a3b8'">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <!-- Date with inline clear X -->
                <div style="position:relative;">
                    <i class="fa-regular fa-calendar" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.72rem;pointer-events:none;"></i>
                    <input type="date" id="dateInput" class="date-input" onchange="applyFilters();toggleClear('dateInput','dateClear')" style="padding-right:30px;">
                    <button id="dateClear" onclick="document.getElementById('dateInput').value='';applyFilters();toggleClear('dateInput','dateClear');" title="Clear date"
                        style="display:none;position:absolute;right:9px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.75rem;padding:2px 4px;line-height:1;border-radius:4px;transition:color .15s;"
                        onmouseover="this.style.color='#6366f1'" onmouseout="this.style.color='#94a3b8'">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <div style="display:flex;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:4px;scrollbar-width:none;">
                <button class="qtab active" data-tab="all" onclick="setFilter('all')"><i class="fa-solid fa-list" style="font-size:.7rem;"></i> All <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $totalSessions ?></span></button>
                <button class="qtab" data-tab="active" onclick="setFilter('active')"><i class="fa-solid fa-circle" style="font-size:.55rem;color:#10b981;"></i> Active<?php if ($activeSessions > 0): ?><span style="background:#10b981;color:white;font-size:.55rem;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $activeSessions ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="closed" onclick="setFilter('closed')"><i class="fa-solid fa-circle-xmark" style="font-size:.7rem;"></i> Closed</button>
                <button class="qtab" data-tab="user" onclick="setFilter('user')"><i class="fa-solid fa-user" style="font-size:.7rem;"></i> Users</button>
                <button class="qtab" data-tab="sk" onclick="setFilter('sk')"><i class="fa-solid fa-user-shield" style="font-size:.7rem;"></i> SK</button>
                <button class="qtab" data-tab="chairman" onclick="setFilter('chairman')"><i class="fa-solid fa-crown" style="font-size:.7rem;"></i> Chairman</button>
            </div>
            <p id="resultCount" style="font-size:.65rem;font-weight:700;color:var(--text-sub);margin-top:10px;"></p>
        </div>

        <!-- Desktop Table -->
        <p class="section-label fade-up-1">Session Records</p>
        <div class="tbl-wrap fade-up-1" id="desktopTable">
            <table>
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">User <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(1)">Role <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(2)">Login Time <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(3)">Logout Time <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Duration</th>
                        <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    <?php if (empty($processed)): ?>
                        <tr>
                            <td colspan="6">
                                <div style="text-align:center;padding:48px 20px;">
                                    <i class="fa-solid fa-clock" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                    <p style="font-weight:800;color:var(--text-sub);">No login logs yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($processed as $i => $log):
                            $col    = $avatarColors[$i % count($avatarColors)];
                            $init   = strtoupper(substr($log['name'] ?? '?', 0, 2));
                            $name   = htmlspecialchars($log['name'] ?? '—');
                            $email  = htmlspecialchars($log['email'] ?? '—');
                            $role   = $log['_role'];
                            $loginF = $log['_login']->format('M j, Y · g:i A');
                            $logoutF = $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                            $rawDate = $log['_login']->format('Y-m-d');
                            $durPct = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                            $mdata  = json_encode(['name' => $name, 'email' => $email, 'role' => $role, 'login' => $loginF, 'logout' => $logoutF, 'dur' => $log['_durLabel'], 'active' => $log['_active'], 'color' => $col, 'initials' => $init]);
                        ?>
                            <tr class="log-row"
                                data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                                data-role="<?= $role ?>"
                                data-search="<?= strtolower("$name $email $role") ?>"
                                data-date="<?= $rawDate ?>"
                                onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="log-avatar <?= $col ?>"><?= $init ?></div>
                                        <div>
                                            <p style="font-weight:700;font-size:.85rem;"><?= $name ?></p>
                                            <p style="font-size:.7rem;color:var(--text-sub);margin-top:1px;"><?= $email ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="tag tag-<?= $role ?>"><i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?>" style="font-size:.55rem;"></i><?= ucfirst($role) ?></span></td>
                                <td>
                                    <p style="font-size:.85rem;font-weight:700;"><?= $log['_login']->format('M j, Y') ?></p>
                                    <p style="font-size:.7rem;color:#6366f1;font-family:var(--mono);margin-top:1px;"><?= $log['_login']->format('g:i A') ?></p>
                                </td>
                                <td>
                                    <?php if ($log['_logout']): ?>
                                        <p style="font-size:.85rem;font-weight:700;"><?= $log['_logout']->format('M j, Y') ?></p>
                                        <p style="font-size:.7rem;color:var(--text-sub);font-family:var(--mono);margin-top:1px;"><?= $log['_logout']->format('g:i A') ?></p>
                                    <?php else: ?>
                                        <span style="font-size:.75rem;color:#10b981;font-weight:700;font-style:italic;">Still active</span>
                                    <?php endif; ?>
                                </td>
                                <td style="min-width:90px;">
                                    <p style="font-size:.78rem;font-weight:700;"><?= $log['_durLabel'] ?></p>
                                    <?php if ($log['_dur'] > 0): ?>
                                        <div class="dur-bar" style="width:80px;">
                                            <div class="dur-fill" style="width:<?= $durPct ?>%;"></div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($log['_active']): ?>
                                        <span class="tag tag-active"><span style="width:5px;height:5px;background:#10b981;border-radius:50%;display:inline-block;"></span>Active</span>
                                    <?php else: ?>
                                        <span class="tag tag-expired"><i class="fa-solid fa-circle-xmark" style="font-size:.55rem;"></i>Closed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div style="padding:12px 20px;border-top:1px solid var(--border-subtle);background:var(--input-bg);display:flex;align-items:center;justify-content:space-between;">
                <p id="tableFooter" style="font-size:.65rem;font-weight:700;color:var(--text-sub);"></p>
                <p style="font-size:.65rem;color:var(--text-faint);font-weight:600;">Click any row for session details</p>
            </div>
        </div>

        <!-- Mobile Cards -->
        <?php if (!empty($processed)): ?>
            <div id="logCardList" style="display:flex;flex-direction:column;gap:10px;margin-top:16px;" class="fade-up-1">
                <?php foreach ($processed as $i => $log):
                    $col    = $avatarColors[$i % count($avatarColors)];
                    $init   = strtoupper(substr($log['name'] ?? '?', 0, 2));
                    $name   = htmlspecialchars($log['name'] ?? '—');
                    $email  = htmlspecialchars($log['email'] ?? '—');
                    $role   = $log['_role'];
                    $loginF = $log['_login']->format('M j, Y · g:i A');
                    $logoutF = $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                    $rawDate = $log['_login']->format('Y-m-d');
                    $durPct = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                    $mdata  = json_encode(['name' => $name, 'email' => $email, 'role' => $role, 'login' => $loginF, 'logout' => $logoutF, 'dur' => $log['_durLabel'], 'active' => $log['_active'], 'color' => $col, 'initials' => $init]);
                ?>
                    <div class="log-card <?= $log['_active'] ? 'active-session' : 'closed-session' ?>"
                        data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                        data-role="<?= $role ?>"
                        data-search="<?= strtolower("$name $email $role") ?>"
                        data-date="<?= $rawDate ?>"
                        onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
                            <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                                <div class="log-avatar <?= $col ?>"><?= $init ?></div>
                                <div style="min-width:0;">
                                    <p style="font-weight:700;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $name ?></p>
                                    <p style="font-size:.7rem;color:var(--text-sub);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $email ?></p>
                                </div>
                            </div>
                            <?php if ($log['_active']): ?>
                                <span class="tag tag-active" style="flex-shrink:0;"><span style="width:5px;height:5px;background:#10b981;border-radius:50%;display:inline-block;"></span>Active</span>
                            <?php else: ?>
                                <span class="tag tag-expired" style="flex-shrink:0;"><i class="fa-solid fa-circle-xmark" style="font-size:.55rem;"></i>Closed</span>
                            <?php endif; ?>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px;">
                            <span class="tag tag-<?= $role ?>"><i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?>" style="font-size:.55rem;"></i><?= ucfirst($role) ?></span>
                            <span style="font-size:.72rem;color:var(--text-sub);font-weight:500;"><i class="fa-solid fa-right-to-bracket" style="font-size:.65rem;color:var(--indigo);margin-right:4px;"></i><?= $log['_login']->format('M j · g:i A') ?></span>
                            <?php if ($log['_logout']): ?><span style="font-size:.72rem;color:var(--text-sub);font-weight:500;"><i class="fa-solid fa-right-from-bracket" style="font-size:.65rem;margin-right:4px;"></i><?= $log['_logout']->format('g:i A') ?></span><?php endif; ?>
                        </div>
                        <p style="font-size:.72rem;font-weight:700;margin-bottom:5px;"><?= $log['_durLabel'] ?></p>
                        <?php if ($log['_dur'] > 0): ?><div class="dur-bar">
                                <div class="dur-fill" style="width:<?= $durPct ?>%;"></div>
                            </div><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="noResultsMsg" class="hidden empty-state" style="margin-top:16px;">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
            <p style="font-size:.85rem;font-weight:700;color:var(--text-sub);">No sessions match your search.</p>
        </div>

    </main>

    <script>
        let curFilter = 'all';

        function setFilter(f) {
            curFilter = f;
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === f));
            applyFilters();
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const date = document.getElementById('dateInput').value;

            // FIX: count from whichever list is visible (table rows on desktop, cards on mobile)
            const isMobile = window.innerWidth < 768;
            const tableRows = document.querySelectorAll('#logTableBody .log-row');
            const cards = document.querySelectorAll('#logCardList .log-card');
            let n = 0;

            const match = el => {
                const mf = curFilter === 'all' ||
                    (curFilter === 'active' && el.dataset.status === 'active') ||
                    (curFilter === 'closed' && el.dataset.status === 'closed') ||
                    el.dataset.role === curFilter;
                return mf && (!q || el.dataset.search.includes(q)) && (!date || el.dataset.date === date);
            };

            tableRows.forEach(r => {
                const s = match(r);
                r.style.display = s ? '' : 'none';
                if (s && !isMobile) n++;
            });
            cards.forEach(c => {
                const s = match(c);
                c.style.display = s ? '' : 'none';
                if (s && isMobile) n++;
            });

            // If neither branch ran (edge case during resize), fall back to table count
            if (n === 0 && !isMobile) {
                tableRows.forEach(r => { if (r.style.display !== 'none') n++; });
            }

            const total = tableRows.length; // total is always consistent with PHP output
            document.getElementById('resultCount').textContent = `Showing ${n} of ${total} session${total !== 1 ? 's' : ''}`;
            document.getElementById('tableFooter').textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
            document.getElementById('noResultsMsg').classList.toggle('hidden', n > 0);
        }

        function toggleClear(inputId, btnId) {
            const val = document.getElementById(inputId).value;
            document.getElementById(btnId).style.display = val ? 'block' : 'none';
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('dateInput').value = '';
            document.getElementById('searchClear').style.display = 'none';
            document.getElementById('dateClear').style.display = 'none';
            setFilter('all');
        }

        let sortDir = {};

        function sortTable(col) {
            sortDir[col] = !sortDir[col];
            const tbody = document.getElementById('logTableBody');
            Array.from(tbody.querySelectorAll('.log-row')).sort((a, b) => {
                const at = (a.cells[col]?.innerText ?? '').trim().toLowerCase();
                const bt = (b.cells[col]?.innerText ?? '').trim().toLowerCase();
                return sortDir[col] ? at.localeCompare(bt) : bt.localeCompare(at);
            }).forEach(r => tbody.appendChild(r));
            document.querySelectorAll('thead th').forEach((th, i) => {
                th.classList.toggle('sorted', i === col);
                const ic = th.querySelector('.sort-icon');
                if (ic) ic.className = `fa-solid ${i === col ? (sortDir[col] ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'} sort-icon`;
            });
        }

        function openDetail(d) {
            const colors = {
                'bg-indigo-100 text-indigo-700': 'background:#eef2ff;color:#3730a3',
                'bg-purple-100 text-purple-700': 'background:#f3e8ff;color:#6b21a8',
                'bg-emerald-100 text-emerald-700': 'background:#dcfce7;color:#166534',
                'bg-rose-100 text-rose-700': 'background:#fee2e2;color:#991b1b',
                'bg-amber-100 text-amber-700': 'background:#fef3c7;color:#92400e'
            };
            const cs = colors[d.color] || 'background:#eef2ff;color:#3730a3';
            document.getElementById('dHero').innerHTML = `<div style="width:44px;height:44px;border-radius:13px;${cs};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;">${d.initials}</div><div><p style="font-weight:800;font-size:1rem;">${d.name}</p><p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">${d.email}</p></div>`;
            const bar = document.getElementById('dStatusBar');
            if (d.active) {
                bar.style.background = '#dcfce7';
                bar.style.color = '#166534';
                bar.innerHTML = '<span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;"></span> Session currently active';
            } else {
                bar.style.background = '#f1f5f9';
                bar.style.color = '#64748b';
                bar.innerHTML = '<i class="fa-solid fa-circle-xmark" style="font-size:.75rem;"></i> Session closed';
            }
            document.getElementById('dEmail').textContent = d.email;
            document.getElementById('dRole').textContent = d.role.charAt(0).toUpperCase() + d.role.slice(1);
            document.getElementById('dLogin').textContent = d.login;
            document.getElementById('dLogout').textContent = d.logout;
            document.getElementById('dDur').textContent = d.dur;
            document.getElementById('detailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeDetail();
        });
        applyFilters();
    </script>
</body>

</html>