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
$avatarColors = ['av-ind', 'av-pur', 'av-eme', 'av-ros', 'av-amb'];
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
    <meta name="theme-color" content="#0a0f1e">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════
           DARK NAVY THEME — Login Logs
        ══════════════════════════════════════════ */
        :root {
            --ll-bg:      #0a0f1e;
            --ll-surface: #111827;
            --ll-card:    #151f35;
            --ll-border:  #1e2d4a;
            --ll-accent:  #6366f1;
            --ll-accent2: #818cf8;
            --ll-text:    #e2e8f8;
            --ll-sub:     #4a6fa5;
            --ll-green:   #10b981;
            --ll-amber:   #f59e0b;
            --ll-red:     #f87171;
            --ll-mono:    'JetBrains Mono', monospace;
            --ll-font:    'DM Sans', sans-serif;
        }

        /* Override body/main for this page */
        body { background: var(--ll-bg) !important; font-family: var(--ll-font) !important; color: var(--ll-text) !important; }

        /* ── Topbar ── */
        .ll-topbar {
            background: var(--ll-surface);
            border-bottom: 1px solid var(--ll-border);
            padding: 16px 18px 14px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .ll-eyebrow { font-size: .62rem; font-weight: 700; color: var(--ll-sub); letter-spacing: .12em; text-transform: uppercase; }
        .ll-title   { font-size: 1.4rem; font-weight: 700; color: var(--ll-text); line-height: 1.2; margin-top: 2px; }
        .ll-sub     { font-size: .72rem; color: var(--ll-sub); margin-top: 2px; }
        .ll-meta    { display: flex; align-items: center; gap: 8px; margin-top: 10px; flex-wrap: wrap; }

        @keyframes llPulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        .live-dot { width: 7px; height: 7px; background: var(--ll-green); border-radius: 50%; animation: llPulse 1.5s infinite; flex-shrink: 0; }
        .live-pill {
            background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.25); color: #34d399;
            font-size: .68rem; font-weight: 700; padding: 5px 10px; border-radius: 20px;
            display: flex; align-items: center; gap: 5px;
        }
        .date-chip {
            margin-left: auto; background: var(--ll-surface); border: 1px solid var(--ll-border);
            border-radius: 8px; padding: 5px 10px; font-size: .68rem; font-weight: 700; color: var(--ll-sub);
        }

        /* ── Stats ── */
        .ll-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; padding: 14px 14px 0; }
        .ll-stat {
            background: var(--ll-card); border: 1px solid var(--ll-border); border-radius: 14px;
            padding: 12px 14px; cursor: pointer; transition: border-color .2s;
        }
        .ll-stat.active { border-color: var(--ll-accent); }
        .ll-stat-lbl { font-size: .58rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--ll-sub); }
        .ll-stat-num { font-size: 1.5rem; font-weight: 700; margin-top: 4px; font-family: var(--ll-mono); }
        .ll-stat-num.indigo { color: var(--ll-accent2); }
        .ll-stat-num.green  { color: var(--ll-green); }
        .ll-stat-num.gray   { color: #475569; }

        /* ── Filter bar — one line ── */
        .ll-filter-wrap { padding: 12px 14px 0; }
        .ll-filter-row  { display: flex; gap: 6px; align-items: center; }

        .ll-search-pill {
            background: var(--ll-card); border: 1px solid var(--ll-border); border-radius: 12px;
            display: flex; align-items: center; gap: 6px; padding: 0 10px; height: 38px;
            flex: 1; min-width: 0;
        }
        .ll-search-pill input {
            background: transparent; border: none; outline: none;
            color: var(--ll-text); font-family: var(--ll-font); font-size: .78rem; width: 100%;
        }
        .ll-search-pill input::placeholder { color: var(--ll-sub); }

        .ll-date-pill {
            background: var(--ll-card); border: 1px solid var(--ll-border); border-radius: 12px;
            display: flex; align-items: center; gap: 5px; padding: 0 10px; height: 38px;
            flex-shrink: 0; width: 140px;
        }
        .ll-date-pill input {
            background: transparent; border: none; outline: none;
            color: var(--ll-text); font-family: var(--ll-font); font-size: .72rem;
            width: 100%; cursor: pointer; color-scheme: dark;
        }
        .ll-icon-sm { color: var(--ll-sub); flex-shrink: 0; }

        /* ── Tabs ── */
        .ll-tabs {
            display: flex; gap: 6px; padding: 10px 14px 0;
            overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none;
        }
        .ll-tabs::-webkit-scrollbar { display: none; }
        .ll-tab {
            background: var(--ll-card); border: 1px solid var(--ll-border); border-radius: 20px;
            padding: 6px 13px; font-size: .7rem; font-weight: 700; color: var(--ll-sub);
            white-space: nowrap; cursor: pointer; transition: all .15s; flex-shrink: 0;
            font-family: var(--ll-font);
        }
        .ll-tab.active { background: var(--ll-accent); border-color: var(--ll-accent); color: #fff; }

        /* ── Section labels ── */
        .ll-section-lbl {
            font-size: .6rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .1em; color: var(--ll-sub); padding: 14px 14px 6px;
        }
        .ll-result-info { font-size: .62rem; color: var(--ll-sub); font-weight: 600; padding: 0 14px 8px; }

        /* ── Log cards ── */
        .ll-cards { display: flex; flex-direction: column; gap: 8px; padding: 0 14px; }
        .ll-log-card {
            background: var(--ll-card); border: 1px solid var(--ll-border);
            border-radius: 16px; padding: 14px 16px; cursor: pointer;
            transition: border-color .2s; border-left: 3px solid transparent;
        }
        .ll-log-card.active-s  { border-left-color: var(--ll-green); }
        .ll-log-card.closed-s  { border-left-color: #334155; }
        .ll-log-card:hover { border-color: #2a3f65; }

        /* ── Avatar ── */
        .ll-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem; flex-shrink: 0;
        }
        .av-ind { background: rgba(99,102,241,.2); color: #818cf8; }
        .av-pur { background: rgba(139,92,246,.2); color: #a78bfa; }
        .av-eme { background: rgba(16,185,129,.15); color: #34d399; }
        .av-ros { background: rgba(248,113,113,.15); color: #f87171; }
        .av-amb { background: rgba(251,191,36,.15);  color: #fbbf24; }

        .ll-user-name  { font-size: .88rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ll-user-email { font-size: .68rem; color: var(--ll-sub); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }

        /* ── Badges ── */
        .ll-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 9px; border-radius: 20px; font-size: .62rem; font-weight: 700;
            white-space: nowrap; flex-shrink: 0; font-family: var(--ll-font);
        }
        .badge-active   { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
        .badge-closed-s { background: rgba(100,116,139,.12); color: #64748b; border: 1px solid rgba(100,116,139,.2); }
        .badge-user     { background: rgba(99,102,241,.15); color: #818cf8; }
        .badge-sk       { background: rgba(139,92,246,.15); color: #a78bfa; }
        .badge-admin    { background: rgba(251,191,36,.15); color: #fbbf24; }
        .badge-chairman { background: rgba(248,113,113,.15); color: #f87171; }

        /* ── Duration bar ── */
        .ll-dur-bar  { height: 3px; background: #1e2d4a; border-radius: 2px; overflow: hidden; margin-top: 8px; }
        .ll-dur-fill { height: 100%; background: linear-gradient(90deg,#4f46e5,#818cf8); border-radius: 2px; }

        /* ── Empty / no results ── */
        .ll-empty { text-align: center; padding: 48px 20px; color: var(--ll-sub); }
        #llNoResults { display: none; text-align: center; padding: 40px 20px; color: var(--ll-sub); }
        #llNoResults.show { display: block; }

        /* ── Detail Modal ── */
        .ll-modal-back {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.65); z-index: 100;
            align-items: flex-end; justify-content: center;
        }
        .ll-modal-back.show { display: flex; }

        @keyframes llSlideUp { from{transform:translateY(60px);opacity:0} to{transform:translateY(0);opacity:1} }
        .ll-modal-sheet {
            background: var(--ll-surface); border-radius: 22px 22px 0 0;
            border: 1px solid var(--ll-border); width: 100%; max-width: 480px;
            padding-bottom: 20px; animation: llSlideUp .25s ease;
        }
        .ll-sheet-handle { width: 36px; height: 4px; background: #1e2d4a; border-radius: 2px; margin: 12px auto 0; }
        .ll-sheet-head   { padding: 12px 20px 14px; display: flex; align-items: flex-start; justify-content: space-between; }
        .ll-sheet-title-sm { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--ll-sub); }
        .ll-sheet-title    { font-size: 1.1rem; font-weight: 700; margin-top: 2px; color: var(--ll-text); }
        .ll-close-btn {
            width: 30px; height: 30px; border-radius: 8px; background: #1e2d4a;
            border: none; color: var(--ll-sub); cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: .75rem;
        }
        .ll-sheet-hero {
            margin: 0 20px 12px; background: rgba(99,102,241,.08);
            border: 1px solid rgba(99,102,241,.15); border-radius: 14px;
            padding: 14px; display: flex; align-items: center; gap: 12px;
        }
        .ll-sheet-status { margin: 0 20px 12px; border-radius: 10px; padding: 9px 14px; display: flex; align-items: center; gap: 8px; font-size: .78rem; font-weight: 700; }
        .ll-sheet-status.active { background: rgba(16,185,129,.12); color: #34d399; }
        .ll-sheet-status.closed { background: #1e2d4a; color: #64748b; }

        .ll-detail-rows { padding: 0 20px; }
        .ll-drow { display: flex; align-items: flex-start; gap: 12px; padding: .6rem 0; border-bottom: 1px solid var(--ll-border); }
        .ll-drow:last-child { border-bottom: none; }
        .ll-dicon {
            width: 32px; height: 32px; background: rgba(99,102,241,.12); border-radius: 8px;
            display: flex; align-items: center; justify-content: center; color: var(--ll-accent2);
            font-size: .75rem; flex-shrink: 0;
        }
        .ll-dlbl  { font-size: .58rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--ll-sub); margin-bottom: 2px; }
        .ll-dval  { font-size: .85rem; font-weight: 700; color: var(--ll-text); }

        .ll-sheet-foot { padding: 14px 20px 4px; border-top: 1px solid var(--ll-border); margin-top: 8px; }
        .ll-close-full {
            width: 100%; padding: 12px; background: #1e2d4a; border: none;
            border-radius: 12px; color: var(--ll-sub); font-family: var(--ll-font);
            font-size: .85rem; font-weight: 700; cursor: pointer; transition: background .15s;
        }
        .ll-close-full:hover { background: #243553; }

        /* ── Desktop table (md+) ── */
        .ll-tbl-wrap {
            margin: 0 14px; background: var(--ll-card); border: 1px solid var(--ll-border);
            border-radius: 16px; overflow: hidden;
        }
        .ll-tbl-wrap table { width: 100%; border-collapse: collapse; font-family: var(--ll-font); }
        .ll-tbl-wrap thead tr { background: rgba(99,102,241,.06); border-bottom: 1px solid var(--ll-border); }
        .ll-tbl-wrap th {
            padding: 11px 14px; font-size: .62rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; color: var(--ll-sub); text-align: left; cursor: pointer;
            user-select: none; white-space: nowrap;
        }
        .ll-tbl-wrap th:hover { color: var(--ll-accent2); }
        .ll-tbl-wrap td { padding: 12px 14px; font-size: .82rem; border-bottom: 1px solid rgba(30,45,74,.6); vertical-align: middle; }
        .ll-tbl-wrap tr:last-child td { border-bottom: none; }
        .ll-tbl-wrap .log-row { cursor: pointer; transition: background .15s; }
        .ll-tbl-wrap .log-row:hover td { background: rgba(99,102,241,.05); }
        .ll-tbl-footer {
            padding: 10px 14px; border-top: 1px solid var(--ll-border);
            background: rgba(99,102,241,.03); display: flex; align-items: center; justify-content: space-between;
        }
        .ll-tbl-footer p { font-size: .62rem; font-weight: 700; color: var(--ll-sub); }

        /* ── Responsive visibility ── */
        @media(min-width:768px) { #llCardList { display: none !important; } }
        @media(max-width:767px) { #llDesktopTable { display: none !important; } }

        /* ── Mobile spacing ── */
        @media(max-width:480px) {
            .ll-title { font-size: 1.25rem !important; }
            .ll-date-pill { width: 120px; }
        }
    </style>
</head>

<body>

    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- ════ DETAIL MODAL ════ -->
    <div id="llDetailModal" class="ll-modal-back" onclick="if(event.target===this)closeDetail()">
        <div class="ll-modal-sheet">
            <div class="ll-sheet-handle"></div>
            <div class="ll-sheet-head">
                <div>
                    <div class="ll-sheet-title-sm">Login Logs</div>
                    <div class="ll-sheet-title">Session Details</div>
                </div>
                <button onclick="closeDetail()" class="ll-close-btn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="llDHero" class="ll-sheet-hero"></div>
            <div id="llDStatus" class="ll-sheet-status"></div>
            <div class="ll-detail-rows">
                <div class="ll-drow">
                    <div class="ll-dicon"><i class="fa-solid fa-envelope"></i></div>
                    <div><div class="ll-dlbl">Email</div><div id="llDEmail" class="ll-dval"></div></div>
                </div>
                <div class="ll-drow">
                    <div class="ll-dicon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div><div class="ll-dlbl">Role</div><div id="llDRole" class="ll-dval"></div></div>
                </div>
                <div class="ll-drow">
                    <div class="ll-dicon"><i class="fa-solid fa-right-to-bracket"></i></div>
                    <div><div class="ll-dlbl">Login Time</div><div id="llDLogin" class="ll-dval"></div></div>
                </div>
                <div class="ll-drow">
                    <div class="ll-dicon"><i class="fa-solid fa-right-from-bracket"></i></div>
                    <div><div class="ll-dlbl">Logout Time</div><div id="llDLogout" class="ll-dval"></div></div>
                </div>
                <div class="ll-drow">
                    <div class="ll-dicon"><i class="fa-regular fa-clock"></i></div>
                    <div><div class="ll-dlbl">Duration</div><div id="llDDur" class="ll-dval"></div></div>
                </div>
            </div>
            <div class="ll-sheet-foot">
                <button onclick="closeDetail()" class="ll-close-full">Close</button>
            </div>
        </div>
    </div>

    <!-- ════ MAIN ════ -->
    <main class="main-area" style="background:var(--ll-bg);padding:0;">

        <!-- Topbar -->
        <div class="ll-topbar">
            <div class="ll-eyebrow">Admin Portal</div>
            <div class="ll-title">Login Logs</div>
            <div class="ll-sub">Authentication history &amp; session tracking</div>
            <div class="ll-meta">
                <?php if ($activeSessions > 0): ?>
                    <div class="live-pill">
                        <span class="live-dot"></span>
                        <?= $activeSessions ?> active session<?= $activeSessions > 1 ? 's' : '' ?>
                    </div>
                <?php endif; ?>
                <div class="date-chip"><?= date('M j, Y') ?></div>
            </div>
        </div>

        <!-- Stats -->
        <div class="ll-stats">
            <div class="ll-stat active" id="stat-all" onclick="setFilter('all')">
                <div class="ll-stat-lbl">Total</div>
                <div class="ll-stat-num indigo"><?= $totalSessions ?></div>
            </div>
            <div class="ll-stat" id="stat-active" onclick="setFilter('active')">
                <div class="ll-stat-lbl">Active Now</div>
                <div class="ll-stat-num green"><?= $activeSessions ?></div>
            </div>
            <div class="ll-stat" id="stat-closed" onclick="setFilter('closed')">
                <div class="ll-stat-lbl">Closed</div>
                <div class="ll-stat-num gray"><?= $closedSessions ?></div>
            </div>
        </div>

        <!-- Filter bar — one line -->
        <div class="ll-filter-wrap">
            <div class="ll-filter-row">
                <div class="ll-search-pill">
                    <svg class="ll-icon-sm" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search name, email, role…"
                           oninput="applyFilters()">
                </div>
                <div class="ll-date-pill">
                    <svg class="ll-icon-sm" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <input type="date" id="dateInput" onchange="applyFilters()">
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="ll-tabs">
            <button class="ll-tab active" data-tab="all" onclick="setFilter('all')">
                <i class="fa-solid fa-list" style="font-size:.65rem;"></i> All
                <span style="font-size:.6rem;opacity:.6;font-family:var(--ll-mono);"><?= $totalSessions ?></span>
            </button>
            <button class="ll-tab" data-tab="active" onclick="setFilter('active')">
                <i class="fa-solid fa-circle" style="font-size:.5rem;color:#10b981;"></i> Active
                <?php if ($activeSessions > 0): ?>
                    <span style="background:#10b981;color:#fff;font-size:.55rem;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $activeSessions ?></span>
                <?php endif; ?>
            </button>
            <button class="ll-tab" data-tab="closed" onclick="setFilter('closed')">
                <i class="fa-solid fa-circle-xmark" style="font-size:.65rem;"></i> Closed
            </button>
            <button class="ll-tab" data-tab="user" onclick="setFilter('user')">
                <i class="fa-solid fa-user" style="font-size:.65rem;"></i> Users
            </button>
            <button class="ll-tab" data-tab="sk" onclick="setFilter('sk')">
                <i class="fa-solid fa-user-shield" style="font-size:.65rem;"></i> SK
            </button>
            <button class="ll-tab" data-tab="chairman" onclick="setFilter('chairman')">
                <i class="fa-solid fa-crown" style="font-size:.65rem;"></i> Chairman
            </button>
        </div>

        <!-- Section label + result count -->
        <div class="ll-section-lbl">Session Records</div>
        <div class="ll-result-info" id="resultCount"></div>

        <!-- ── Desktop Table ── -->
        <div id="llDesktopTable" class="ll-tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">User <i class="fa-solid fa-sort" style="font-size:.55rem;opacity:.5;"></i></th>
                        <th onclick="sortTable(1)">Role <i class="fa-solid fa-sort" style="font-size:.55rem;opacity:.5;"></i></th>
                        <th onclick="sortTable(2)">Login Time <i class="fa-solid fa-sort" style="font-size:.55rem;opacity:.5;"></i></th>
                        <th onclick="sortTable(3)">Logout Time <i class="fa-solid fa-sort" style="font-size:.55rem;opacity:.5;"></i></th>
                        <th>Duration</th>
                        <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort" style="font-size:.55rem;opacity:.5;"></i></th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    <?php if (empty($processed)): ?>
                        <tr><td colspan="6">
                            <div class="ll-empty">
                                <i class="fa-solid fa-clock" style="font-size:2rem;opacity:.2;display:block;margin-bottom:10px;"></i>
                                <p style="font-weight:700;">No login logs yet</p>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($processed as $i => $log):
                            $col     = $avatarColors[$i % count($avatarColors)];
                            $init    = strtoupper(substr($log['name'] ?? '?', 0, 2));
                            $name    = htmlspecialchars($log['name'] ?? '—');
                            $email   = htmlspecialchars($log['email'] ?? '—');
                            $role    = $log['_role'];
                            $loginF  = $log['_login']->format('M j, Y · g:i A');
                            $logoutF = $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                            $rawDate = $log['_login']->format('Y-m-d');
                            $durPct  = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                            $mdata   = json_encode(['name'=>$name,'email'=>$email,'role'=>$role,'login'=>$loginF,'logout'=>$logoutF,'dur'=>$log['_durLabel'],'active'=>$log['_active'],'color'=>$col,'initials'=>$init]);
                        ?>
                        <tr class="log-row"
                            data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                            data-role="<?= $role ?>"
                            data-search="<?= strtolower("$name $email $role") ?>"
                            data-date="<?= $rawDate ?>"
                            onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="ll-avatar <?= $col ?>"><?= $init ?></div>
                                    <div>
                                        <p style="font-weight:700;font-size:.85rem;color:var(--ll-text);"><?= $name ?></p>
                                        <p style="font-size:.7rem;color:var(--ll-sub);margin-top:1px;"><?= $email ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="ll-badge badge-<?= $role ?>">
                                    <i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?>" style="font-size:.55rem;"></i>
                                    <?= ucfirst($role) ?>
                                </span>
                            </td>
                            <td>
                                <p style="font-size:.82rem;font-weight:700;color:var(--ll-text);"><?= $log['_login']->format('M j, Y') ?></p>
                                <p style="font-size:.7rem;color:var(--ll-accent2);font-family:var(--ll-mono);margin-top:1px;"><?= $log['_login']->format('g:i A') ?></p>
                            </td>
                            <td>
                                <?php if ($log['_logout']): ?>
                                    <p style="font-size:.82rem;font-weight:700;color:var(--ll-text);"><?= $log['_logout']->format('M j, Y') ?></p>
                                    <p style="font-size:.7rem;color:var(--ll-sub);font-family:var(--ll-mono);margin-top:1px;"><?= $log['_logout']->format('g:i A') ?></p>
                                <?php else: ?>
                                    <span style="font-size:.75rem;color:#34d399;font-weight:700;font-style:italic;">Still active</span>
                                <?php endif; ?>
                            </td>
                            <td style="min-width:90px;">
                                <p style="font-size:.78rem;font-weight:700;color:var(--ll-text);"><?= $log['_durLabel'] ?></p>
                                <?php if ($log['_dur'] > 0): ?>
                                    <div class="ll-dur-bar" style="width:80px;">
                                        <div class="ll-dur-fill" style="width:<?= $durPct ?>%;"></div>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($log['_active']): ?>
                                    <span class="ll-badge badge-active">
                                        <span style="width:5px;height:5px;background:#10b981;border-radius:50%;display:inline-block;"></span>Active
                                    </span>
                                <?php else: ?>
                                    <span class="ll-badge badge-closed-s">
                                        <i class="fa-solid fa-circle-xmark" style="font-size:.55rem;"></i>Closed
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="ll-tbl-footer">
                <p id="tableFooter"></p>
                <p style="color:var(--ll-sub);font-size:.6rem;">Click any row for details</p>
            </div>
        </div>

        <!-- ── Mobile Cards ── -->
        <?php if (!empty($processed)): ?>
        <div id="llCardList" class="ll-cards">
            <?php foreach ($processed as $i => $log):
                $col     = $avatarColors[$i % count($avatarColors)];
                $init    = strtoupper(substr($log['name'] ?? '?', 0, 2));
                $name    = htmlspecialchars($log['name'] ?? '—');
                $email   = htmlspecialchars($log['email'] ?? '—');
                $role    = $log['_role'];
                $loginF  = $log['_login']->format('M j, Y · g:i A');
                $logoutF = $log['_logout'] ? $log['_logout']->format('M j, Y · g:i A') : 'Still active';
                $rawDate = $log['_login']->format('Y-m-d');
                $durPct  = $log['_dur'] > 0 ? min(100, round(($log['_dur'] / $maxDuration) * 100)) : 0;
                $mdata   = json_encode(['name'=>$name,'email'=>$email,'role'=>$role,'login'=>$loginF,'logout'=>$logoutF,'dur'=>$log['_durLabel'],'active'=>$log['_active'],'color'=>$col,'initials'=>$init]);
            ?>
            <div class="ll-log-card <?= $log['_active'] ? 'active-s' : 'closed-s' ?>"
                 data-status="<?= $log['_active'] ? 'active' : 'closed' ?>"
                 data-role="<?= $role ?>"
                 data-search="<?= strtolower("$name $email $role") ?>"
                 data-date="<?= $rawDate ?>"
                 onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                <!-- Card top row -->
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <div class="ll-avatar <?= $col ?>"><?= $init ?></div>
                        <div style="min-width:0;">
                            <div class="ll-user-name"><?= $name ?></div>
                            <div class="ll-user-email"><?= $email ?></div>
                        </div>
                    </div>
                    <?php if ($log['_active']): ?>
                        <span class="ll-badge badge-active" style="flex-shrink:0;">
                            <span style="width:5px;height:5px;background:#10b981;border-radius:50%;display:inline-block;"></span>ACTIVE
                        </span>
                    <?php else: ?>
                        <span class="ll-badge badge-closed-s" style="flex-shrink:0;">
                            <i class="fa-solid fa-circle-xmark" style="font-size:.55rem;"></i>CLOSED
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Meta row -->
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                    <span class="ll-badge badge-<?= $role ?>" style="font-size:.6rem;">
                        <i class="fa-solid <?= $roleIcons[$role] ?? 'fa-circle' ?>" style="font-size:.55rem;"></i>
                        <?= ucfirst($role) ?>
                    </span>
                    <span style="font-size:.65rem;font-weight:600;color:var(--ll-sub);display:flex;align-items:center;gap:4px;">
                        <span style="width:4px;height:4px;border-radius:50%;background:var(--ll-accent);display:inline-block;"></span>
                        <?= $log['_login']->format('M j · g:i A') ?>
                    </span>
                    <?php if ($log['_logout']): ?>
                        <span style="font-size:.65rem;font-weight:600;color:var(--ll-sub);display:flex;align-items:center;gap:4px;">
                            <span style="width:4px;height:4px;border-radius:50%;background:#475569;display:inline-block;"></span>
                            <?= $log['_logout']->format('g:i A') ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Duration -->
                <div style="font-size:.72rem;font-weight:700;color:<?= $log['_active'] ? '#34d399' : 'var(--ll-accent2)' ?>;">
                    <?= $log['_durLabel'] ?>
                </div>
                <?php if ($log['_dur'] > 0): ?>
                    <div class="ll-dur-bar">
                        <div class="ll-dur-fill" style="width:<?= $durPct ?>%;"></div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div id="llNoResults" style="margin-top:16px;">
            <div style="font-size:1.8rem;margin-bottom:8px;opacity:.2;">⊘</div>
            <p style="font-size:.85rem;font-weight:700;">No sessions match your search.</p>
        </div>

        <div style="height:24px;"></div>

    </main>

    <script>
        let curFilter = 'all';

        function setFilter(f) {
            curFilter = f;
            document.querySelectorAll('.ll-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === f));
            ['all','active','closed'].forEach(k => {
                const el = document.getElementById('stat-' + k);
                if (el) el.classList.toggle('active', k === f);
            });
            applyFilters();
        }

        function applyFilters() {
            const q    = document.getElementById('searchInput').value.toLowerCase().trim();
            const date = document.getElementById('dateInput').value;
            const isMobile = window.innerWidth < 768;

            const tableRows = document.querySelectorAll('#logTableBody .log-row');
            const cards     = document.querySelectorAll('#llCardList .ll-log-card');
            let n = 0;

            const match = el => {
                const mf = curFilter === 'all' ||
                    (curFilter === 'active' && el.dataset.status === 'active') ||
                    (curFilter === 'closed' && el.dataset.status === 'closed') ||
                    el.dataset.role === curFilter;
                return mf && (!q || el.dataset.search.includes(q)) && (!date || el.dataset.date === date);
            };

            tableRows.forEach(r => { const s = match(r); r.style.display = s ? '' : 'none'; if (s && !isMobile) n++; });
            cards.forEach(c => { const s = match(c); c.style.display = s ? '' : 'none'; if (s && isMobile) n++; });

            if (n === 0 && !isMobile) tableRows.forEach(r => { if (r.style.display !== 'none') n++; });

            const total = tableRows.length;
            const rc = document.getElementById('resultCount');
            if (rc) rc.textContent = `Showing ${n} of ${total} session${total !== 1 ? 's' : ''}`;
            const tf = document.getElementById('tableFooter');
            if (tf) tf.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
            const nr = document.getElementById('llNoResults');
            if (nr) nr.classList.toggle('show', n === 0);
        }

        let sortDir = {};
        function sortTable(col) {
            sortDir[col] = !sortDir[col];
            const tbody = document.getElementById('logTableBody');
            Array.from(tbody.querySelectorAll('.log-row'))
                .sort((a, b) => {
                    const at = (a.cells[col]?.innerText ?? '').trim().toLowerCase();
                    const bt = (b.cells[col]?.innerText ?? '').trim().toLowerCase();
                    return sortDir[col] ? at.localeCompare(bt) : bt.localeCompare(at);
                })
                .forEach(r => tbody.appendChild(r));
        }

        function openDetail(d) {
            const avMap = {
                'av-ind': 'background:rgba(99,102,241,.2);color:#818cf8',
                'av-pur': 'background:rgba(139,92,246,.2);color:#a78bfa',
                'av-eme': 'background:rgba(16,185,129,.15);color:#34d399',
                'av-ros': 'background:rgba(248,113,113,.15);color:#f87171',
                'av-amb': 'background:rgba(251,191,36,.15);color:#fbbf24',
            };
            const cs = avMap[d.color] || 'background:rgba(99,102,241,.2);color:#818cf8';
            document.getElementById('llDHero').innerHTML =
                `<div style="width:44px;height:44px;border-radius:12px;${cs};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;">${d.initials}</div>
                 <div>
                   <p style="font-weight:700;font-size:.95rem;color:var(--ll-text);">${d.name}</p>
                   <p style="font-size:.68rem;color:var(--ll-sub);margin-top:2px;">${d.email}</p>
                 </div>`;

            const bar = document.getElementById('llDStatus');
            if (d.active) {
                bar.className = 'll-sheet-status active';
                bar.innerHTML = '<span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;"></span> Session currently active';
            } else {
                bar.className = 'll-sheet-status closed';
                bar.innerHTML = '<i class="fa-solid fa-circle-xmark" style="font-size:.75rem;"></i> Session closed';
            }

            document.getElementById('llDEmail').textContent  = d.email;
            document.getElementById('llDRole').textContent   = d.role.charAt(0).toUpperCase() + d.role.slice(1);
            document.getElementById('llDLogin').textContent  = d.login;
            document.getElementById('llDLogout').textContent = d.logout;
            document.getElementById('llDDur').textContent    = d.dur;

            document.getElementById('llDetailModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetail() {
            document.getElementById('llDetailModal').classList.remove('show');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });
        applyFilters();
    </script>

</body>
</html>