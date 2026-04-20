<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Activity Logs | Admin</title>
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        (function() { if (localStorage.getItem('admin_theme') === 'dark') document.documentElement.classList.add('dark-pre') })();
    </script>
    <style>
        /* ══════════════════════════════
           BASE
        ══════════════════════════════ */
        .main-area { padding: 24px 20px 80px; }
        @media(max-width:639px) { .main-area { padding: 14px 12px 80px; } }

        /* ══════════════════════════════
           PAGE HEADER
        ══════════════════════════════ */
        .page-header {
            display: flex; flex-wrap: wrap; justify-content: space-between;
            align-items: flex-start; margin-bottom: 24px; gap: 12px;
        }
        .page-header-left p.eyebrow {
            font-size: .62rem; font-weight: 700; letter-spacing: .2em;
            text-transform: uppercase; color: var(--text-sub); margin-bottom: 4px;
        }
        .page-header-left h2 {
            font-size: 1.6rem; font-weight: 800; color: var(--text);
            letter-spacing: -.04em; line-height: 1.1;
        }
        .page-header-left p.sub { font-size: .78rem; color: var(--text-sub); font-weight: 500; margin-top: 4px; }
        .page-header-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; flex-shrink: 0; }

        /* ══════════════════════════════
           STAT CARDS — responsive grid
        ══════════════════════════════ */
        .stat-grid-logs {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }
        @media(max-width:639px) {
            .stat-grid-logs {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }
            .stat-grid-logs .stat-total { grid-column: 1 / -1; }
        }
        .stat-card-log {
            background: var(--card); border-radius: 16px; padding: 14px 16px;
            border: 1px solid rgba(99,102,241,.08); border-bottom-width: 3px;
            box-shadow: var(--shadow-sm); transition: transform .2s, box-shadow .2s; min-width: 0;
        }
        .stat-card-log:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card-log .lbl { font-size: .58rem; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; margin-bottom: 4px; }
        .stat-card-log .num { font-size: 1.6rem; font-weight: 800; color: var(--text); line-height: 1; font-family: var(--mono); }
        @media(max-width:639px) { .stat-card-log .num { font-size: 1.4rem; } .stat-card-log { padding: 12px 14px; } }

        /* ══════════════════════════════
           FILTER BAR
        ══════════════════════════════ */
        .filter-bar {
            padding: 14px 16px; border-bottom: 1px solid rgba(99,102,241,.07);
            background: rgba(99,102,241,.02); display: flex; flex-wrap: wrap; gap: 10px;
        }
        #searchInput, #actionFilter {
            background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--r-sm);
            padding: 10px 14px; font-family: var(--font); font-size: .875rem; color: var(--text);
            outline: none; transition: border-color .2s, box-shadow .2s;
        }
        #searchInput { padding-left: 36px; flex: 1; min-width: 0; }
        #actionFilter { width: auto; min-width: 140px; cursor: pointer; }
        #searchInput:focus, #actionFilter:focus { border-color: var(--indigo); box-shadow: 0 0 0 4px rgba(55,48,163,.08); }
        #searchInput::placeholder { color: var(--text-sub); }
        @media(max-width:480px) { #actionFilter { width: 100%; min-width: 0; } }

        /* ══════════════════════════════
           BADGES
        ══════════════════════════════ */
        .badge {
            padding: 3px 10px; border-radius: 99px; font-size: .7rem;
            font-weight: 800; text-transform: uppercase; letter-spacing: .04em;
            display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;
        }

        /* ══════════════════════════════
           TABLE
        ══════════════════════════════ */
        .log-row { transition: background .12s; }
        .log-row:hover td { background: rgba(99,102,241,.04); }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-scroll::-webkit-scrollbar { height: 4px; }
        .table-scroll::-webkit-scrollbar-thumb { background: rgba(99,102,241,.15); border-radius: 4px; }

        /* ══════════════════════════════
           TOOLTIP
        ══════════════════════════════ */
        .details-tooltip { position: relative; display: inline-block; }
        .tooltip-text {
            visibility: hidden; opacity: 0; pointer-events: none;
            position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%);
            background: #1e293b; color: white; padding: 7px 11px; border-radius: 10px;
            font-size: .73rem; max-width: min(280px, 80vw); white-space: normal; word-break: break-word;
            line-height: 1.45; transition: opacity .18s; z-index: 50; box-shadow: var(--shadow-md);
        }
        .tooltip-text::after { content: ''; position: absolute; top: 100%; left: 50%; margin-left: -5px; border: 5px solid transparent; border-top-color: #1e293b; }
        @media(max-width:640px) { .tooltip-text { left: 0; transform: none; } .tooltip-text::after { left: 16px; } }
        .details-tooltip:hover .tooltip-text { visibility: visible; opacity: 1; }

        /* ══════════════════════════════
           MOBILE LOG CARDS
        ══════════════════════════════ */
        .mobile-log-card {
            background: var(--card); padding: 14px 16px; transition: box-shadow .15s;
            border-bottom: 1px solid rgba(99,102,241,.06);
        }
        .mobile-log-card:last-child { border-bottom: none; }
        .mobile-log-card:hover { box-shadow: inset 0 0 0 1px rgba(99,102,241,.12); }

        /* ══════════════════════════════
           AVATAR COLOURS
        ══════════════════════════════ */
        .av-create  { background:#ecfdf5;color:#059669; }
        .av-approve { background:#eff6ff;color:#2563eb; }
        .av-decline { background:#fef2f2;color:#dc2626; }
        .av-claim   { background:#f3e8ff;color:#6b21a8; }
        .av-print   { background:#f0f9ff;color:#0369a1; }
        .av-default { background:#f1f5f9;color:#64748b; }

        /* ══════════════════════════════
           LEGEND
        ══════════════════════════════ */
        .legend-wrap { display: flex; flex-wrap: wrap; gap: 10px 16px; }
        .legend-footer { margin-top: 18px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; }

        /* ══════════════════════════════
           ICON & ACTION BUTTONS
        ══════════════════════════════ */
        .icon-btn {
            width: 44px; height: 44px; background: var(--card);
            border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-sub); cursor: pointer; transition: all .2s;
            box-shadow: var(--shadow-sm); flex-shrink: 0;
        }

        /* ══════════════════════════════
           SHOW / HIDE HELPERS
        ══════════════════════════════ */
        @media(max-width:767px)  { .hidden-mobile  { display:none!important } }
        @media(min-width:768px)  { .show-mobile    { display:none!important } }

        /* ══════════════════════════════
           PATCH: PAGE HEADER — wrap on mobile
        ══════════════════════════════ */
        .page-header-right { flex-wrap: wrap; }
        @media(max-width:480px) {
            .page-header-left h2 { font-size: 1.3rem !important; }
            .page-header-right { width: 100%; justify-content: flex-end; }
        }

        /* ══════════════════════════════
           PATCH: FILTER BAR — stack on mobile
        ══════════════════════════════ */
        @media(max-width:639px) {
            .filter-bar { flex-direction: column; gap: 8px; }
            #actionFilter { width: 100%; min-width: 0; }
        }

        /* ══════════════════════════════
           DARK MODE — unified palette
        ══════════════════════════════ */
        body.dark .stat-card-log  { background: var(--card); border-color: rgba(99,102,241,.12); }
        body.dark .log-row:hover td { background: rgba(99,102,241,.06)!important; }
        body.dark .mobile-log-card  { background: var(--card); border-color: rgba(99,102,241,.08); }
        body.dark #searchInput,
        body.dark #actionFilter     { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
        body.dark #searchInput::placeholder { color: #4a6fa5 !important; }
        body.dark #actionFilter option { background: #0b1628; }
        body.dark .tooltip-text     { background: #1e3a5f; }
        body.dark .tooltip-text::after { border-top-color: #1e3a5f; }
        body.dark .av-create  { background:rgba(5,150,105,.2);  color:#34d399; }
        body.dark .av-approve { background:rgba(37,99,235,.2);  color:#60a5fa; }
        body.dark .av-decline { background:rgba(220,38,38,.2);  color:#f87171; }
        body.dark .av-claim   { background:rgba(107,33,168,.25);color:#c084fc; }
        body.dark .av-print   { background:rgba(3,105,161,.2);  color:#38bdf8; }
        body.dark .av-default { background:rgba(100,116,139,.2);color:#94a3b8; }
        body.dark .icon-btn   { background:#101e35 !important; border-color:rgba(99,102,241,.18) !important; color:#7fb3e8 !important; }
        body.dark .icon-btn:hover { background:rgba(99,102,241,.12) !important; color:#a5b4fc !important; }
        body.dark .page-header-left h2 { color:#e2eaf8 !important; }
        body.dark .page-header-left p  { color:#4a6fa5 !important; }
        body.dark .tbl-wrap  { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
        body.dark .filter-bar { background: rgba(99,102,241,.04); border-color: rgba(99,102,241,.08); }
    </style>
</head>

<body>
    <?php
    date_default_timezone_set('Asia/Manila');
    $page = 'activity-logs';
    include APPPATH . 'Views/partials/admin_layout.php';

    $actionLabel = function(string $a): string {
        return match($a) {
            'create'                         => 'Created',
            'approve','approve_user_request' => 'Approved',
            'decline','decline_user_request' => 'Declined',
            'claim'                          => 'Claimed',
            'print'                          => 'Print',
            default                          => ucfirst(str_replace('_',' ',$a)),
        };
    };
    $actionSentence = function(string $a): string {
        return match($a) {
            'create'                         => 'Created reservation',
            'approve','approve_user_request' => 'Approved reservation',
            'decline','decline_user_request' => 'Declined reservation',
            'claim'                          => 'Claimed e-ticket',
            'print'                          => 'Logged print',
            default                          => ucfirst(str_replace('_',' ',$a)),
        };
    };
    $badgeClass = function(string $a): string {
        return match($a) {
            'create'                         => 'badge-create',
            'approve','approve_user_request' => 'badge-approve',
            'decline','decline_user_request' => 'badge-decline',
            'claim'                          => 'badge-claim',
            'print'                          => 'badge-print',
            default                          => 'badge-default',
        };
    };
    $avatarClass = function(string $a): string {
        return match($a) {
            'create'                         => 'av-create',
            'approve','approve_user_request' => 'av-approve',
            'decline','decline_user_request' => 'av-decline',
            'claim'                          => 'av-claim',
            'print'                          => 'av-print',
            default                          => 'av-default',
        };
    };
    ?>

    <main class="main-area">

        <!-- Page Header -->
        <header class="page-header">
            <div class="page-header-left">
                <p class="eyebrow">Administration</p>
                <h2>Activity Logs</h2>
                <p class="sub">Complete audit trail of all system actions.</p>
            </div>
            <div class="page-header-right">
                <div onclick="adminToggleDark()" title="Toggle dark mode" class="icon-btn">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <button onclick="generateReport()" class="action-btn-outline" style="gap:7px">
                    <i class="fa-solid fa-file-lines" style="font-size:.8rem"></i>
                    <span class="hide-xs">Generate Report</span>
                </button>
            </div>
        </header>

        <!-- Stat Cards -->
        <div class="stat-grid-logs">
            <div class="stat-card-log stat-total" style="border-bottom-color:#94a3b8">
                <p class="lbl" style="color:var(--text-sub)">Total</p>
                <p class="num"><?= count($logs) ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#10b981">
                <p class="lbl" style="color:#10b981">Created</p>
                <p class="num"><?= $createCount ?? 0 ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#3b82f6">
                <p class="lbl" style="color:#3b82f6">Approved</p>
                <p class="num"><?= $approveCount ?? 0 ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#ef4444">
                <p class="lbl" style="color:#ef4444">Declined</p>
                <p class="num"><?= $declineCount ?? 0 ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#a855f7">
                <p class="lbl" style="color:#a855f7">Claimed</p>
                <p class="num"><?= $claimCount ?? 0 ?></p>
            </div>
        </div>

        <!-- Table / Card container -->
        <div class="tbl-wrap">

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="search-wrap" style="flex:1;min-width:180px">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search user, action, details…" style="width:100%">
                </div>
                <select id="actionFilter">
                    <option value="">All Actions</option>
                    <option value="create">Created</option>
                    <option value="approve">Approved</option>
                    <option value="decline">Declined</option>
                    <option value="claim">Claimed</option>
                    <option value="print">Print</option>
                </select>
            </div>

            <!-- DESKTOP TABLE -->
            <div class="hidden-mobile desktop-table">
                <div class="table-scroll">
                    <table style="min-width:680px">
                        <thead>
                            <tr>
                                <th style="width:140px">Timestamp</th>
                                <th style="width:176px">User</th>
                                <th style="width:112px">Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log):
                                    $action   = strtolower(trim($log['action'] ?? ''));
                                    $name     = $log['name'] ?? 'System';
                                    $initials = strtoupper(substr($name, 0, 2));
                                    $resId    = $log['reservation_id'] ?? null;
                                    $details  = $log['details'] ?? '';
                                    $dateStr  = !empty($log['created_at']) ? date('M d, Y', strtotime($log['created_at'])) : '—';
                                    $timeStr  = !empty($log['created_at']) ? date('h:i A', strtotime($log['created_at'])) : '';
                                    $sentence = $actionSentence($action);
                                ?>
                                    <tr class="log-row"
                                        data-action="<?= htmlspecialchars($action) ?>"
                                        data-search="<?= strtolower(htmlspecialchars("$name $action $details $resId")) ?>">
                                        <td>
                                            <p style="font-size:.82rem;font-weight:700"><?= $dateStr ?></p>
                                            <p style="font-size:.68rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;margin-top:2px"><?= $timeStr ?></p>
                                        </td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:8px;min-width:0">
                                                <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800" class="<?= $avatarClass($action) ?>"><?= $initials ?></div>
                                                <span style="font-size:.82rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px"><?= htmlspecialchars($name) ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge <?= $badgeClass($action) ?>"><?= $actionLabel($action) ?></span></td>
                                        <td>
                                            <div style="display:flex;align-items:flex-start;gap:6px;min-width:0">
                                                <div style="min-width:0">
                                                    <p style="font-size:.82rem;color:var(--text-muted);font-weight:500">
                                                        <?= $sentence ?>
                                                        <?php if ($resId): ?><strong style="color:var(--text)">#<?= htmlspecialchars($resId) ?></strong><?php endif; ?>
                                                    </p>
                                                    <?php if (!empty($details)): ?>
                                                        <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:280px"><?= htmlspecialchars($details) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($details)): ?>
                                                    <div class="details-tooltip" style="flex-shrink:0;margin-top:2px">
                                                        <i class="fa-solid fa-circle-info" style="color:var(--text-faint);font-size:.82rem;transition:color .15s;cursor:default" onmouseover="this.style.color='var(--indigo)'" onmouseout="this.style.color='var(--text-faint)'"></i>
                                                        <span class="tooltip-text"><?= htmlspecialchars($details) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="padding:48px 24px;text-align:center">
                                        <i class="fa-solid fa-clipboard-list" style="font-size:2rem;color:var(--text-faint);display:block;margin-bottom:10px"></i>
                                        <p style="font-weight:700;color:var(--text-sub)">No activity logs found.</p>
                                        <p style="font-size:.78rem;color:var(--text-faint);margin-top:4px">Actions will appear here as they happen.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MOBILE CARDS -->
            <div class="show-mobile mobile-cards" id="mobileCardList">
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log):
                        $action   = strtolower(trim($log['action'] ?? ''));
                        $name     = $log['name'] ?? 'System';
                        $initials = strtoupper(substr($name, 0, 2));
                        $resId    = $log['reservation_id'] ?? null;
                        $details  = $log['details'] ?? '';
                        $dateStr  = !empty($log['created_at']) ? date('M d, Y', strtotime($log['created_at'])) : '—';
                        $timeStr  = !empty($log['created_at']) ? date('h:i A', strtotime($log['created_at'])) : '';
                        $sentence = $actionSentence($action);
                    ?>
                        <div class="mobile-log-card"
                            data-action="<?= htmlspecialchars($action) ?>"
                            data-search="<?= strtolower(htmlspecialchars("$name $action $details $resId")) ?>">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:8px">
                                <div style="display:flex;align-items:center;gap:8px;min-width:0">
                                    <div style="width:32px;height:32px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800" class="<?= $avatarClass($action) ?>"><?= $initials ?></div>
                                    <span style="font-size:.84rem;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($name) ?></span>
                                </div>
                                <span class="badge <?= $badgeClass($action) ?>" style="flex-shrink:0"><?= $actionLabel($action) ?></span>
                            </div>
                            <p style="font-size:.82rem;color:var(--text-muted);font-weight:500;margin-bottom:4px">
                                <?= $sentence ?>
                                <?php if ($resId): ?><strong style="color:var(--text)">#<?= htmlspecialchars($resId) ?></strong><?php endif; ?>
                            </p>
                            <?php if (!empty($details)): ?>
                                <p style="font-size:.72rem;color:var(--text-sub);margin-bottom:6px;line-height:1.5"><?= htmlspecialchars($details) ?></p>
                            <?php endif; ?>
                            <p style="font-size:.62rem;font-weight:700;color:var(--text-faint);text-transform:uppercase;letter-spacing:.08em;margin-top:6px"><?= $dateStr ?> · <?= $timeStr ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding:48px 24px;text-align:center">
                        <i class="fa-solid fa-clipboard-list" style="font-size:2rem;color:var(--text-faint);display:block;margin-bottom:10px"></i>
                        <p style="font-weight:700;color:var(--text-sub)">No activity logs found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- No results -->
            <div id="noResults" style="display:none;padding:48px 24px;text-align:center">
                <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.75rem;color:var(--text-faint);display:block;margin-bottom:10px"></i>
                <p style="font-weight:700;color:var(--text-sub)">No logs match your search.</p>
            </div>

            <div style="padding:10px 16px;border-top:1px solid rgba(99,102,241,.07);background:rgba(99,102,241,.02)">
                <p id="resultCount" style="font-size:.72rem;font-weight:700;color:var(--text-sub)"></p>
            </div>
        </div>

        <!-- Footer legend -->
        <div class="legend-footer">
            <div class="legend-wrap">
                <span style="display:flex;align-items:center;gap:6px;font-size:.72rem;color:var(--text-sub)"><span style="width:8px;height:8px;border-radius:50%;background:#10b981;flex-shrink:0"></span>Created</span>
                <span style="display:flex;align-items:center;gap:6px;font-size:.72rem;color:var(--text-sub)"><span style="width:8px;height:8px;border-radius:50%;background:#3b82f6;flex-shrink:0"></span>Approved</span>
                <span style="display:flex;align-items:center;gap:6px;font-size:.72rem;color:var(--text-sub)"><span style="width:8px;height:8px;border-radius:50%;background:#ef4444;flex-shrink:0"></span>Declined</span>
                <span style="display:flex;align-items:center;gap:6px;font-size:.72rem;color:var(--text-sub)"><span style="width:8px;height:8px;border-radius:50%;background:#a855f7;flex-shrink:0"></span>Claimed</span>
                <span style="display:flex;align-items:center;gap:6px;font-size:.72rem;color:var(--text-sub)"><span style="width:8px;height:8px;border-radius:50%;background:#38bdf8;flex-shrink:0"></span>Print</span>
            </div>
            <span style="font-size:.72rem;color:var(--text-sub);font-weight:500">Last updated: <?= date('F j, Y g:i A') ?></span>
        </div>

    </main>

    <script>
        /* ─── Filter / Search ─── */
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput  = document.getElementById('searchInput');
            const actionFilter = document.getElementById('actionFilter');
            const noResults    = document.getElementById('noResults');
            const countEl      = document.getElementById('resultCount');
            const tableRows    = Array.from(document.querySelectorAll('.log-row'));
            const mobileCards  = Array.from(document.querySelectorAll('.mobile-log-card'));
            const total        = tableRows.length || mobileCards.length;

            function filterAll() {
                const q      = searchInput.value.toLowerCase().trim();
                const action = actionFilter.value.toLowerCase();
                let visible  = 0;

                tableRows.forEach(row => {
                    const matchAction = !action || row.dataset.action === action ||
                        (action === 'approve' && row.dataset.action.startsWith('approve')) ||
                        (action === 'decline' && row.dataset.action.startsWith('decline'));
                    const matchSearch = !q || row.dataset.search.includes(q);
                    const show = matchAction && matchSearch;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                mobileCards.forEach(card => {
                    const matchAction = !action || card.dataset.action === action ||
                        (action === 'approve' && card.dataset.action.startsWith('approve')) ||
                        (action === 'decline' && card.dataset.action.startsWith('decline'));
                    const matchSearch = !q || card.dataset.search.includes(q);
                    card.style.display = (matchAction && matchSearch) ? '' : 'none';
                });

                const count = tableRows.length
                    ? visible
                    : mobileCards.filter(c => c.style.display !== 'none').length;
                noResults.style.display = (count === 0 && total > 0) ? 'block' : 'none';
                countEl.textContent = `Showing ${count} of ${total} log${total !== 1 ? 's' : ''}`;
            }

            searchInput.addEventListener('input', filterAll);
            actionFilter.addEventListener('change', filterAll);
            filterAll();
        });

        /* ─── Generate Report ─── */
        function generateReport() {
            const logs = <?= json_encode($logs) ?>;
            const now  = new Date().toLocaleString('en-PH', { timeZone: 'Asia/Manila', dateStyle: 'long', timeStyle: 'short' });

            const actionLabel = a => ({
                create: 'Created', approve: 'Approved', approve_user_request: 'Approved',
                decline: 'Declined', decline_user_request: 'Declined',
                claim: 'Claimed', print: 'Print'
            }[a] ?? a.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase()));

            const actionSentence = a => ({
                create: 'Created reservation', approve: 'Approved reservation',
                approve_user_request: 'Approved reservation', decline: 'Declined reservation',
                decline_user_request: 'Declined reservation', claim: 'Claimed e-ticket', print: 'Logged print'
            }[a] ?? a.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase()));

            const actionColor = a => ({
                create:'#059669', approve:'#2563eb', approve_user_request:'#2563eb',
                decline:'#dc2626', decline_user_request:'#dc2626', claim:'#7c3aed', print:'#0369a1'
            }[a] ?? '#64748b');

            const actionBg = a => ({
                create:'#ecfdf5', approve:'#eff6ff', approve_user_request:'#eff6ff',
                decline:'#fef2f2', decline_user_request:'#fef2f2', claim:'#f3e8ff', print:'#f0f9ff'
            }[a] ?? '#f1f5f9');

            /* Counts */
            const counts = { total: logs.length, create: 0, approve: 0, decline: 0, claim: 0, print: 0 };
            logs.forEach(l => {
                const a = (l.action || '').toLowerCase().trim();
                if (a === 'create')              counts.create++;
                else if (a.startsWith('approve')) counts.approve++;
                else if (a.startsWith('decline')) counts.decline++;
                else if (a === 'claim')           counts.claim++;
                else if (a === 'print')           counts.print++;
            });

            /* Table rows */
            const rows = logs.map((l, i) => {
                const action   = (l.action || '').toLowerCase().trim();
                const name     = (l.name || 'System').replace(/&/g,'&amp;').replace(/</g,'&lt;');
                const initials = name.substring(0,2).toUpperCase();
                const details  = (l.details || '').replace(/&/g,'&amp;').replace(/</g,'&lt;');
                const resId    = l.reservation_id;
                const color    = actionColor(action);
                const bg       = actionBg(action);
                const label    = actionLabel(action);
                const sentence = actionSentence(action);

                let dateStr = '—', timeStr = '';
                if (l.created_at) {
                    const d = new Date(l.created_at);
                    dateStr = d.toLocaleDateString('en-PH',{month:'short',day:'2-digit',year:'numeric'});
                    timeStr = d.toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'});
                }

                return `
                <tr>
                    <td style="color:#64748b;font-size:.75rem;white-space:nowrap">
                        <span style="font-weight:700;color:#1e293b;display:block">${dateStr}</span>
                        <span style="font-size:.68rem;text-transform:uppercase;letter-spacing:.06em">${timeStr}</span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:28px;height:28px;border-radius:8px;background:${bg};color:${color};display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;flex-shrink:0">${initials}</div>
                            <span style="font-weight:700;font-size:.82rem">${name}</span>
                        </div>
                    </td>
                    <td>
                        <span style="background:${bg};color:${color};padding:3px 10px;border-radius:99px;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap">${label}</span>
                    </td>
                    <td style="font-size:.82rem;color:#374151;line-height:1.5">
                        ${sentence}${resId ? ` <strong style="color:#1e293b">#${resId}</strong>` : ''}
                        ${details ? `<br><span style="font-size:.72rem;color:#94a3b8">${details}</span>` : ''}
                    </td>
                </tr>`;
            }).join('');

            /* Stat card HTML helper */
            const statCard = (num, label, color) => `
                <div style="flex:1;min-width:90px;background:#fff;border:1px solid #e2e8f0;border-bottom:3px solid ${color};border-radius:14px;padding:14px 16px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.05)">
                    <div style="font-size:1.9rem;font-weight:800;color:${color};font-family:ui-monospace,monospace;line-height:1">${num}</div>
                    <div style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#94a3b8;margin-top:5px">${label}</div>
                </div>`;

            const html = `<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Activity Logs Report</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Plus Jakarta Sans',sans-serif;background:#f8fafc;color:#1e293b;padding:40px;font-size:.875rem}

    /* Header */
    .rpt-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;padding-bottom:20px;border-bottom:2px solid #e2e8f0;flex-wrap:wrap;gap:12px}
    .rpt-title{font-size:1.7rem;font-weight:800;letter-spacing:-.04em;color:#0f172a}
    .rpt-meta{font-size:.75rem;color:#94a3b8;margin-top:5px;font-weight:500}
    .rpt-badge{background:#6366f1;color:#fff;padding:4px 12px;border-radius:99px;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;margin-top:8px;display:inline-block}

    /* Stat row */
    .stat-row{display:flex;gap:10px;margin-bottom:28px;flex-wrap:wrap}

    /* Toolbar */
    .toolbar{display:flex;justify-content:flex-end;gap:10px;margin-bottom:16px}
    .btn-print{background:#6366f1;color:#fff;border:none;padding:10px 22px;border-radius:10px;font-weight:700;cursor:pointer;font-size:.85rem;font-family:inherit;display:flex;align-items:center;gap:7px;transition:background .2s}
    .btn-print:hover{background:#4f46e5}
    .btn-pdf{background:#0f172a;color:#fff;border:none;padding:10px 22px;border-radius:10px;font-weight:700;cursor:pointer;font-size:.85rem;font-family:inherit;display:flex;align-items:center;gap:7px;transition:background .2s}
    .btn-pdf:hover{background:#1e293b}

    /* Table */
    .tbl-wrap{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.07);border:1px solid #e2e8f0}
    table{width:100%;border-collapse:collapse}
    thead tr{background:#f1f5f9}
    th{padding:12px 16px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#64748b;white-space:nowrap}
    td{padding:11px 16px;border-top:1px solid #f1f5f9;vertical-align:middle}
    tr:hover td{background:#fafbff}

    /* Footer */
    .rpt-footer{margin-top:24px;text-align:center;font-size:.68rem;color:#cbd5e1;padding-top:16px;border-top:1px solid #e2e8f0}

    @media print{
        body{background:#fff;padding:16px}
        .toolbar,.no-print{display:none!important}
        .tbl-wrap{box-shadow:none;border:1px solid #e2e8f0}
        tr:hover td{background:transparent!important}
    }
    @media(max-width:600px){
        body{padding:16px}
        .rpt-header{flex-direction:column}
        .stat-row{gap:8px}
    }
</style>
</head>
<body>

<!-- Header -->
<div class="rpt-header">
    <div>
        <p style="font-size:.62rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:#6366f1;margin-bottom:6px">Administration · Audit Trail</p>
        <h1 class="rpt-title">Activity Logs Report</h1>
        <p class="rpt-meta">Generated on ${now}</p>
        <span class="rpt-badge">${counts.total} Total Records</span>
    </div>
    <div style="text-align:right">
        <div style="font-size:.62rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.12em;margin-bottom:4px">System</div>
        <div style="font-weight:800;color:#0f172a;font-size:.95rem">Reservation Platform</div>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:3px">Admin Panel</div>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-row">
    ${statCard(counts.total,   'Total',    '#6366f1')}
    ${statCard(counts.create,  'Created',  '#10b981')}
    ${statCard(counts.approve, 'Approved', '#3b82f6')}
    ${statCard(counts.decline, 'Declined', '#ef4444')}
    ${statCard(counts.claim,   'Claimed',  '#a855f7')}
    ${statCard(counts.print,   'Print',    '#38bdf8')}
</div>

<!-- Toolbar -->
<div class="toolbar no-print">
    <button class="btn-print" onclick="window.print()">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
        Print Report
    </button>
    <button class="btn-pdf" onclick="window.print()">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Save as PDF
    </button>
</div>

<!-- Table -->
<div class="tbl-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:120px">Timestamp</th>
                <th style="width:180px">User</th>
                <th style="width:110px">Action</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            ${rows || `<tr><td colspan="4" style="padding:40px;text-align:center;color:#94a3b8;font-weight:600">No activity logs found.</td></tr>`}
        </tbody>
    </table>
</div>

<!-- Footer -->
<p class="rpt-footer">
    This report is auto-generated and reflects all recorded system actions up to the time of generation.
    &nbsp;·&nbsp; Reservation Platform Admin Panel
</p>

</body>
</html>`;

            const win = window.open('', '_blank');
            if (!win) { alert('Pop-up blocked. Please allow pop-ups for this site.'); return; }
            win.document.write(html);
            win.document.close();
        }
    </script>
</body>
</html>