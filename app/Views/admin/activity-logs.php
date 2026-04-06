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
        (function() {
            if (localStorage.getItem('admin_theme') === 'dark') document.documentElement.classList.add('dark-pre')
        })();
    </script>
    <style>
        /* ── Page-specific layout ── */
        .main-area {
            padding: 24px 28px 60px;
        }

        @media(max-width:639px) {
            .main-area {
                padding: 14px 12px 60px;
            }
        }

        /* ── Badges ── */
        .badge {
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        /* ── Table row hover ── */
        .log-row {
            transition: background 0.12s;
        }

        .log-row:hover td {
            background: rgba(99, 102, 241, .04);
        }

        /* ── Stat cards ── */
        .stat-card-log {
            background: var(--card);
            border-radius: 20px;
            padding: 1.1rem 1.25rem;
            border: 1px solid rgba(99, 102, 241, .08);
            border-bottom-width: 3px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card-log:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ── Table scroll wrapper ── */
        .table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, .15);
            border-radius: 4px;
        }

        /* ── Tooltip ── */
        .details-tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip-text {
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 7px 11px;
            border-radius: 10px;
            font-size: 0.73rem;
            white-space: nowrap;
            max-width: min(280px, 80vw);
            white-space: normal;
            word-break: break-word;
            line-height: 1.45;
            transition: opacity 0.18s;
            z-index: 50;
            box-shadow: var(--shadow-md);
        }

        .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border: 5px solid transparent;
            border-top-color: #1e293b;
        }

        @media (max-width: 640px) {
            .tooltip-text {
                left: 0;
                transform: none;
            }

            .tooltip-text::after {
                left: 16px;
            }
        }

        .details-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* ── Mobile log card ── */
        .mobile-log-card {
            background: var(--card);
            padding: 1rem 1.1rem;
            transition: box-shadow 0.15s;
            border-bottom: 1px solid rgba(99, 102, 241, .06);
        }

        .mobile-log-card:hover {
            box-shadow: inset 0 0 0 1px rgba(99, 102, 241, .12);
        }

        /* ── Filter bar ── */
        #searchInput,
        #actionFilter {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--r-sm);
            padding: 10px 14px;
            font-family: var(--font);
            font-size: .875rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        #searchInput {
            padding-left: 36px;
        }

        #searchInput:focus,
        #actionFilter:focus {
            border-color: var(--indigo);
            box-shadow: 0 0 0 4px rgba(55, 48, 163, .08);
        }

        #searchInput::placeholder {
            color: var(--text-sub);
        }

        /* ── Legend ── */
        .legend-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 16px;
        }

        /* ── Avatar color helpers ── */
        .av-create {
            background: #ecfdf5;
            color: #059669;
        }

        .av-approve {
            background: #eff6ff;
            color: #2563eb;
        }

        .av-decline {
            background: #fef2f2;
            color: #dc2626;
        }

        .av-claim {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .av-print {
            background: #f0f9ff;
            color: #0369a1;
        }

        .av-default {
            background: #f1f5f9;
            color: #64748b;
        }

        /* ── Dark mode extras ── */
        body.dark .stat-card-log {
            background: var(--card);
            border-color: rgba(99, 102, 241, .12);
        }

        body.dark .log-row:hover td {
            background: rgba(99, 102, 241, .06) !important;
        }

        body.dark .mobile-log-card {
            background: var(--card);
            border-color: rgba(99, 102, 241, .08);
        }

        body.dark #searchInput,
        body.dark #actionFilter {
            background: var(--input-bg);
            border-color: rgba(99, 102, 241, .18);
            color: var(--text);
        }

        body.dark #searchInput::placeholder {
            color: var(--text-sub);
        }

        body.dark #actionFilter option {
            background: #0b1628;
        }

        body.dark .tooltip-text {
            background: #1e3a5f;
        }

        body.dark .tooltip-text::after {
            border-top-color: #1e3a5f;
        }

        body.dark .av-create {
            background: rgba(5, 150, 105, .2);
            color: #34d399;
        }

        body.dark .av-approve {
            background: rgba(37, 99, 235, .2);
            color: #60a5fa;
        }

        body.dark .av-decline {
            background: rgba(220, 38, 38, .2);
            color: #f87171;
        }

        body.dark .av-claim {
            background: rgba(107, 33, 168, .25);
            color: #c084fc;
        }

        body.dark .av-print {
            background: rgba(3, 105, 161, .2);
            color: #38bdf8;
        }

        body.dark .av-default {
            background: rgba(100, 116, 139, .2);
            color: #94a3b8;
        }

        body.dark #noResults {
            color: var(--text-sub);
        }

        @media print {

            .l-sidebar,
            .l-mobile-nav,
            header button {
                display: none !important;
            }

            .main-area {
                margin: 0 !important;
                padding: 1rem !important;
            }

            .mobile-cards {
                display: none !important;
            }

            .desktop-table {
                display: block !important;
            }
        }
    </style>
</head>

<body>

    <?php
    date_default_timezone_set('Asia/Manila');
    $page = 'activity-logs';

    include APPPATH . 'Views/partials/admin_layout.php';

    $actionLabel = function (string $a): string {
        return match ($a) {
            'create'                              => 'Created',
            'approve', 'approve_user_request'     => 'Approved',
            'decline', 'decline_user_request'     => 'Declined',
            'claim'                               => 'Claimed',
            'print'                               => 'Print',
            default                               => ucfirst(str_replace('_', ' ', $a)),
        };
    };
    $actionSentence = function (string $a): string {
        return match ($a) {
            'create'                              => 'Created reservation',
            'approve', 'approve_user_request'     => 'Approved reservation',
            'decline', 'decline_user_request'     => 'Declined reservation',
            'claim'                               => 'Claimed e-ticket',
            'print'                               => 'Logged print',
            default                               => ucfirst(str_replace('_', ' ', $a)),
        };
    };
    $badgeClass = function (string $a): string {
        return match ($a) {
            'create'                              => 'badge-create',
            'approve', 'approve_user_request'     => 'badge-approve',
            'decline', 'decline_user_request'     => 'badge-decline',
            'claim'                               => 'badge-claim',
            'print'                               => 'badge-print',
            default                               => 'badge-default',
        };
    };
    $avatarClass = function (string $a): string {
        return match ($a) {
            'create'                              => 'av-create',
            'approve', 'approve_user_request'     => 'av-approve',
            'decline', 'decline_user_request'     => 'av-decline',
            'claim'                               => 'av-claim',
            'print'                               => 'av-print',
            default                               => 'av-default',
        };
    };
    ?>

    <!-- ════════ MAIN ════════ -->
    <main class="main-area">

        <!-- Header -->
        <header style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;margin-bottom:28px;gap:12px">
            <div>
                <p style="font-size:.62rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--text-sub);margin-bottom:4px">Administration</p>
                <h2 style="font-size:1.75rem;font-weight:800;color:var(--text);letter-spacing:-.04em;line-height:1.1">Activity Logs</h2>
                <p style="font-size:.78rem;color:var(--text-sub);font-weight:500;margin-top:4px">Complete audit trail of all system actions.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap">
                <div onclick="adminToggleDark()" title="Toggle dark mode" class="icon-btn">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <button onclick="window.print()" class="action-btn-outline" style="gap:7px">
                    <i class="fa-solid fa-print" style="font-size:.8rem"></i> Print Report
                </button>
            </div>
        </header>

        <!-- Stat cards -->
        <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-bottom:24px">
            <div class="stat-card-log" style="border-bottom-color:#94a3b8">
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:var(--text-sub);margin-bottom:4px">Total</p>
                <p style="font-size:1.8rem;font-weight:800;color:var(--text);line-height:1;font-family:var(--mono)"><?= count($logs) ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#10b981">
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#10b981;margin-bottom:4px">Created</p>
                <p style="font-size:1.8rem;font-weight:800;color:var(--text);line-height:1;font-family:var(--mono)"><?= $createCount ?? 0 ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#3b82f6">
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#3b82f6;margin-bottom:4px">Approved</p>
                <p style="font-size:1.8rem;font-weight:800;color:var(--text);line-height:1;font-family:var(--mono)"><?= $approveCount ?? 0 ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#ef4444">
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#ef4444;margin-bottom:4px">Declined</p>
                <p style="font-size:1.8rem;font-weight:800;color:var(--text);line-height:1;font-family:var(--mono)"><?= $declineCount ?? 0 ?></p>
            </div>
            <div class="stat-card-log" style="border-bottom-color:#a855f7">
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;color:#a855f7;margin-bottom:4px">Claimed</p>
                <p style="font-size:1.8rem;font-weight:800;color:var(--text);line-height:1;font-family:var(--mono)"><?= $claimCount ?? 0 ?></p>
            </div>
        </div>

        <!-- Table / Card container -->
        <div class="tbl-wrap">

            <!-- Filters -->
            <div style="padding:16px 20px;border-bottom:1px solid rgba(99,102,241,.07);background:rgba(99,102,241,.02);display:flex;flex-wrap:wrap;gap:10px">
                <div class="search-wrap" style="flex:1;min-width:200px">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search user, action, details…" style="width:100%">
                </div>
                <select id="actionFilter" style="width:auto;min-width:160px;cursor:pointer">
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
                                                <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800" class="<?= $avatarClass($action) ?>">
                                                    <?= $initials ?>
                                                </div>
                                                <span style="font-size:.82rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px"><?= htmlspecialchars($name) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge <?= $badgeClass($action) ?>"><?= $actionLabel($action) ?></span>
                                        </td>
                                        <td>
                                            <div style="display:flex;align-items:flex-start;gap:6px;min-width:0">
                                                <div style="min-width:0">
                                                    <p style="font-size:.82rem;color:var(--text-muted);font-weight:500">
                                                        <?= $sentence ?>
                                                        <?php if ($resId): ?>
                                                            <strong style="color:var(--text)">#<?= htmlspecialchars($resId) ?></strong>
                                                        <?php endif; ?>
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
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:6px">
                                <div style="display:flex;align-items:center;gap:8px;min-width:0">
                                    <div style="width:30px;height:30px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800" class="<?= $avatarClass($action) ?>">
                                        <?= $initials ?>
                                    </div>
                                    <span style="font-size:.82rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($name) ?></span>
                                </div>
                                <span class="badge <?= $badgeClass($action) ?>" style="flex-shrink:0"><?= $actionLabel($action) ?></span>
                            </div>
                            <p style="font-size:.82rem;color:var(--text-muted);font-weight:500">
                                <?= $sentence ?>
                                <?php if ($resId): ?>
                                    <strong style="color:var(--text)">#<?= htmlspecialchars($resId) ?></strong>
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($details)): ?>
                                <p style="font-size:.72rem;color:var(--text-sub);margin-top:4px;line-height:1.5"><?= htmlspecialchars($details) ?></p>
                            <?php endif; ?>
                            <p style="font-size:.62rem;font-weight:700;color:var(--text-faint);text-transform:uppercase;letter-spacing:.08em;margin-top:8px"><?= $dateStr ?> · <?= $timeStr ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding:48px 24px;text-align:center">
                        <i class="fa-solid fa-clipboard-list" style="font-size:2rem;color:var(--text-faint);display:block;margin-bottom:10px"></i>
                        <p style="font-weight:700;color:var(--text-sub)">No activity logs found.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- No results state -->
            <div id="noResults" style="display:none;padding:48px 24px;text-align:center">
                <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.75rem;color:var(--text-faint);display:block;margin-bottom:10px"></i>
                <p style="font-weight:700;color:var(--text-sub)">No logs match your search.</p>
            </div>

            <div style="padding:10px 20px;border-top:1px solid rgba(99,102,241,.07);background:rgba(99,102,241,.02)">
                <p id="resultCount" style="font-size:.72rem;font-weight:700;color:var(--text-sub)"></p>
            </div>
        </div>

        <!-- Footer legend -->
        <div style="margin-top:18px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px">
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

    <style>
        @media(max-width:767px) {
            .hidden-mobile {
                display: none !important
            }
        }

        @media(min-width:768px) {
            .show-mobile {
                display: none !important
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const actionFilter = document.getElementById('actionFilter');
            const noResults = document.getElementById('noResults');
            const countEl = document.getElementById('resultCount');

            const tableRows = Array.from(document.querySelectorAll('.log-row'));
            const mobileCards = Array.from(document.querySelectorAll('.mobile-log-card'));
            const total = tableRows.length || mobileCards.length;

            function filterAll() {
                const q = searchInput.value.toLowerCase().trim();
                const action = actionFilter.value.toLowerCase();
                let visible = 0;

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

                const count = tableRows.length ?
                    visible :
                    mobileCards.filter(c => c.style.display !== 'none').length;
                noResults.style.display = (count === 0 && total > 0) ? 'block' : 'none';
                countEl.textContent = `Showing ${count} of ${total} log${total !== 1 ? 's' : ''}`;
            }

            searchInput.addEventListener('input', filterAll);
            actionFilter.addEventListener('change', filterAll);
            filterAll();
        });
    </script>
</body>

</html>