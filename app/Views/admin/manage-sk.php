<?php
$page      = 'manage-sk';
$adminName = $admin_name ?? session()->get('name') ?? 'Administrator';

$pCount = count($pending  ?? []);
$aCount = count($approved ?? []);
$rCount = count($rejected ?? []);
$total  = $pCount + $aCount + $rCount;

$avatarStyles = [
    'background:#dbeafe;color:#1d4ed8',
    'background:#f3e8ff;color:#7c3aed',
    'background:#d1fae5;color:#065f46',
    'background:#ffe4e6;color:#be123c',
    'background:#fef3c7;color:#92400e',
];

$allMerged = array_merge(
    array_map(fn($s) => array_merge($s, ['_status' => 'pending']),  $pending  ?? []),
    array_map(fn($s) => array_merge($s, ['_status' => 'approved']), $approved ?? []),
    array_map(fn($s) => array_merge($s, ['_status' => 'rejected']), $rejected ?? [])
);

$sIcon = ['pending' => 'fa-clock', 'approved' => 'fa-check', 'rejected' => 'fa-xmark'];

/* pass pending SK count to layout for nav badge */
$pendingSkCount = $pCount;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage SK Accounts | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <style>
        /* ── SK avatar ── */
        .sk-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* ── SK card (mobile) ── */
        .sk-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            padding: 14px 16px;
            cursor: pointer;
            transition: all .15s;
        }

        .sk-card:hover {
            border-color: var(--indigo-border);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        body.dark .sk-card {
            background: var(--card);
            border-color: var(--border);
        }

        body.dark .sk-card:hover {
            border-color: var(--indigo);
        }

        /* ── Overlay ── */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 300;
            align-items: center;
            justify-content: center;
        }

        .overlay.open {
            display: flex;
            animation: fadeIn .15s ease;
        }

        .overlay-bg {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(6px);
        }

        .modal-box {
            position: relative;
            margin: auto;
            background: var(--card);
            border-radius: 28px;
            width: 94%;
            max-width: 500px;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .35);
            animation: popIn .22s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        .modal-box.sm {
            max-width: 380px;
        }

        @media(max-width:639px) {
            .overlay#detailModal {
                align-items: flex-end;
            }

            .overlay#detailModal .modal-box {
                margin: 0;
                width: 100%;
                max-width: 100%;
                border-radius: 28px 28px 0 0;
                max-height: 92vh;
                animation: slideUp .28s cubic-bezier(.34, 1.2, .64, 1) both;
            }
        }

        .modal-box::-webkit-scrollbar {
            width: 4px;
        }

        .modal-box::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        body.dark .modal-box {
            background: var(--card);
        }

        body.dark .modal-box::-webkit-scrollbar-thumb {
            background: var(--border);
        }

        /* ── Ghost button ── */
        .btn-ghost {
            background: var(--input-bg-alt);
            color: var(--text-muted);
            border: none;
            border-radius: 9px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            font-family: var(--font);
            transition: all .15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-ghost:hover {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        body.dark .btn-ghost {
            background: var(--input-bg);
            color: #7fb3e8;
        }

        body.dark .btn-ghost:hover {
            background: rgba(99, 102, 241, .18);
            color: #a5b4fc;
        }

        @media(min-width:768px) {
            #mobileCardList {
                display: none !important;
            }

            #mobileNoResults {
                display: none !important;
            }
        }

        @media(max-width:767px) {
            .hidden-on-mobile {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <form id="approveForm" method="POST" action="/admin/approve-sk" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
    <form id="rejectForm" method="POST" action="/admin/reject-sk" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="rejectId"></form>

    <!-- DETAIL MODAL -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('detail')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 12px">
                <div>
                    <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--text-sub);margin-bottom:3px">SK Account</p>
                    <h3 style="font-size:18px;font-weight:800;">Account Info</h3>
                </div>
                <button onclick="closeModal('detail')" class="modal-close" style="margin-top:2px;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="dHero" style="margin:0 20px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px"></div>
            <div id="dStatusBar" style="margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700"></div>
            <div style="padding:0 20px 8px">
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <p class="dlabel">Email</p>
                        <p id="dEmail" class="dvalue" style="word-break:break-all"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <p class="dlabel">Phone</p>
                        <p id="dPhone" class="dvalue"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-regular fa-calendar"></i></div>
                    <div>
                        <p class="dlabel">Applied</p>
                        <p id="dDate" class="dvalue"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-shield-check"></i></div>
                    <div>
                        <p class="dlabel">Email Verified</p>
                        <p id="dVerified" class="dvalue"></p>
                    </div>
                </div>
            </div>
            <div id="dActions" style="padding:16px 20px;border-top:1px solid var(--border-subtle);display:flex;gap:10px;margin-top:8px"></div>
        </div>
    </div>

    <!-- Approve confirm -->
    <div id="approveModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('approve')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div style="padding:24px 24px 20px;text-align:center">
                <div style="width:64px;height:64px;background:#dcfce7;color:#16a34a;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-user-check"></i></div>
                <h3 style="font-size:18px;font-weight:800;">Approve SK Account?</h3>
                <p style="color:var(--text-sub);font-size:13px;margin-top:4px;font-weight:500">This will grant SK portal access.</p>
                <p id="approveConfirmName" style="font-size:13px;margin-top:10px;font-weight:800"></p>
            </div>
            <div style="padding:0 24px 24px;display:flex;gap:10px">
                <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
                <button id="confirmApproveBtn" class="btn-confirm-approve" onclick="submitApprove()"><i class="fa-solid fa-check"></i> Approve</button>
            </div>
        </div>
    </div>

    <!-- Reject confirm -->
    <div id="rejectModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('reject')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div style="padding:24px 24px 20px;text-align:center">
                <div style="width:64px;height:64px;background:#fee2e2;color:#dc2626;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-user-xmark"></i></div>
                <h3 style="font-size:18px;font-weight:800;">Reject SK Account?</h3>
                <p style="color:var(--text-sub);font-size:13px;margin-top:4px;font-weight:500">This action cannot be undone.</p>
                <p id="rejectConfirmName" style="font-size:13px;margin-top:10px;font-weight:800"></p>
            </div>
            <div style="padding:0 24px 24px;display:flex;gap:10px">
                <button class="btn-cancel" onclick="closeModal('reject')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
                <button id="confirmRejectBtn" class="btn-confirm-reject" onclick="submitReject()"><i class="fa-solid fa-xmark"></i> Reject</button>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <main class="main-area">
        <header class="fade-up" style="display:flex;flex-direction:column;gap:3px;margin-bottom:24px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
                <div>
                    <div class="page-eyebrow">Admin Portal</div>
                    <h2 class="page-title">SK Accounts</h2>
                    <p class="page-sub">Manage Sangguniang Kabataan registrations</p>
                </div>
                <div class="topbar-right">
                    <div class="icon-btn" onclick="adminToggleDark()"><span id="darkIcon"><i class="fa-regular fa-sun"></i></span></div>
                    <?php if ($pCount > 0): ?>
                        <div style="display:flex;align-items:center;gap:7px;background:#fef3c7;border:1px solid #fde68a;color:#92400e;padding:9px 14px;border-radius:var(--r-sm);font-weight:700;font-size:12px;flex-shrink:0">
                            <i class="fa-solid fa-clock" style="font-size:11px"></i>
                            <?= $pCount ?> pending approval<?= $pCount > 1 ? 's' : '' ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Stat cards -->
        <div class="stats-grid fade-up" style="grid-template-columns:repeat(4,minmax(0,1fr));">
            <?php foreach (
                [
                    ['Total',    $total,  '#3730a3', 'fa-users',      'all'],
                    ['Pending',  $pCount, '#d97706', 'fa-clock',      'pending'],
                    ['Approved', $aCount, '#16a34a', 'fa-user-check', 'approved'],
                    ['Rejected', $rCount, '#dc2626', 'fa-user-xmark', 'rejected'],
                ] as [$lbl, $val, $color, $ico, $tab]
            ): ?>
                <div class="stat-card" style="border-left-color:<?= $color ?>" onclick="switchToTab('<?= $tab ?>')">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                        <p class="stat-lbl"><?= $lbl ?></p>
                        <i class="fa-solid <?= $ico ?>" style="font-size:13px;color:<?= $color ?>"></i>
                    </div>
                    <p class="stat-num" style="color:<?= $color ?>"><?= $val ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Filter bar -->
        <div class="card fade-up" style="padding:16px 18px;margin-bottom:14px;">
            <div style="position:relative;margin-bottom:12px">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="searchInput" type="text" placeholder="Search by name or email…" class="search-input" oninput="applyFilter()">
                </div>
            </div>
            <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px">
                <button class="qtab active" data-tab="all" onclick="switchToTab('all')"><i class="fa-solid fa-users" style="font-size:11px"></i> All <span style="font-size:9px;font-weight:800;opacity:.7"><?= $total ?></span></button>
                <button class="qtab" data-tab="pending" onclick="switchToTab('pending')"><i class="fa-solid fa-clock" style="font-size:11px"></i> Pending<?php if ($pCount > 0): ?><span style="background:#f59e0b;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $pCount ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="approved" onclick="switchToTab('approved')"><i class="fa-solid fa-user-check" style="font-size:11px"></i> Approved</button>
                <button class="qtab" data-tab="rejected" onclick="switchToTab('rejected')"><i class="fa-solid fa-user-xmark" style="font-size:11px"></i> Rejected</button>
            </div>
        </div>

        <p id="resultCount" style="font-size:11px;font-weight:700;color:var(--text-sub);padding:0 4px;margin-bottom:12px"></p>

        <!-- DESKTOP TABLE -->
        <div class="tbl-wrap hidden-on-mobile fade-up">
            <table>
                <thead>
                    <tr>
                        <th style="width:48px">ID</th>
                        <th>Account</th>
                        <th>Email</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th style="text-align:right;width:190px">Actions</th>
                    </tr>
                </thead>
                <tbody id="skTableBody">
                    <?php if (empty($allMerged)): ?>
                        <tr>
                            <td colspan="6">
                                <div style="padding:80px 24px;text-align:center">
                                    <i class="fa-solid fa-users" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:12px"></i>
                                    <p style="font-weight:800;color:var(--text-sub);font-size:15px">No SK accounts yet</p>
                                    <p style="color:var(--text-sub);font-size:12px;margin-top:4px">Accounts will appear when users register.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allMerged as $idx => $sk):
                            $s    = $sk['_status'];
                            $name = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                            $email = htmlspecialchars($sk['email'] ?? '—');
                            $phone = htmlspecialchars($sk['phone'] ?? 'N/A');
                            $date = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                            $ver  = !empty($sk['is_verified']) ? 'Yes' : 'No';
                            $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                            $init = strtoupper(substr($name, 0, 1));
                            $mdata = json_encode(['id' => $sk['id'], 'status' => $s, 'name' => $name, 'email' => $email, 'phone' => $phone, 'date' => $date, 'verified' => $ver, 'avatarStyle' => $avatarStyle, 'initials' => $init]);
                        ?>
                            <tr class="sk-row" data-status="<?= $s ?>" data-search="<?= strtolower("$name $email") ?>">
                                <td><span style="font-size:11px;font-weight:800;color:var(--text-sub);font-family:monospace">#<?= $sk['id'] ?></span></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <div class="sk-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                                        <div>
                                            <p style="font-weight:700;font-size:13px;"><?= $name ?></p>
                                            <p style="font-size:11px;color:var(--text-sub);margin-top:2px">Applied <?= $date ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p style="font-size:13px;color:var(--text-muted);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px"><?= $email ?></p>
                                </td>
                                <td>
                                    <p style="font-size:13px;color:var(--text-muted);font-weight:600;white-space:nowrap"><?= $date ?></p>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $s ?>">
                                        <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?>" style="font-size:9px"></i> <?= ucfirst($s) ?>
                                    </span>
                                </td>
                                <td style="text-align:right">
                                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap">
                                        <button onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-ghost"><i class="fa-solid fa-eye" style="font-size:10px"></i> View</button>
                                        <?php if ($s === 'pending'): ?>
                                            <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve-sm"><i class="fa-solid fa-check" style="font-size:10px"></i> Approve</button>
                                            <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-reject-sm"><i class="fa-solid fa-xmark" style="font-size:10px"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div style="padding:10px 18px;border-top:1px solid var(--border-subtle);background:rgba(238,242,255,.4);display:flex;align-items:center;justify-content:space-between">
                <p id="tableFooter" style="font-size:11px;font-weight:700;color:var(--text-sub)"></p>
                <p style="font-size:11px;color:var(--text-faint);font-weight:600;">Click View to see full account details</p>
            </div>
        </div>

        <!-- MOBILE CARDS -->
        <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px">
            <?php if (empty($allMerged)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-users" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
                    <p style="font-weight:800;color:var(--text-sub)">No SK accounts yet</p>
                </div>
            <?php else: ?>
                <?php foreach ($allMerged as $idx => $sk):
                    $s    = $sk['_status'];
                    $name = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                    $email = htmlspecialchars($sk['email'] ?? '—');
                    $phone = htmlspecialchars($sk['phone'] ?? 'N/A');
                    $date = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                    $ver  = !empty($sk['is_verified']) ? 'Yes' : 'No';
                    $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                    $init = strtoupper(substr($name, 0, 1));
                    $mdata = json_encode(['id' => $sk['id'], 'status' => $s, 'name' => $name, 'email' => $email, 'phone' => $phone, 'date' => $date, 'verified' => $ver, 'avatarStyle' => $avatarStyle, 'initials' => $init]);
                ?>
                    <div class="sk-card mobile-sk-card"
                        data-status="<?= $s ?>"
                        data-search="<?= strtolower("$name $email") ?>"
                        onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                            <div class="sk-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                            <div style="flex:1;min-width:0">
                                <p style="font-weight:700;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                                <p style="font-size:11px;color:var(--text-sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p>
                            </div>
                            <span class="badge badge-<?= $s ?>" style="flex-shrink:0">
                                <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?>" style="font-size:9px"></i> <?= ucfirst($s) ?>
                            </span>
                        </div>
                        <p style="font-size:11px;color:var(--text-sub);font-weight:600;margin-bottom:10px">
                            <i class="fa-regular fa-calendar" style="font-size:10px;margin-right:4px"></i>Applied <?= $date ?>
                        </p>
                        <?php if ($s === 'pending'): ?>
                            <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--border-subtle)" onclick="event.stopPropagation()">
                                <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                    style="flex:1;height:36px;border-radius:10px;background:#dcfce7;color:#16a34a;border:1px solid #86efac;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px">
                                    <i class="fa-solid fa-check" style="font-size:10px"></i> Approve
                                </button>
                                <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                    style="flex:1;height:36px;border-radius:10px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px">
                                    <i class="fa-solid fa-xmark" style="font-size:10px"></i> Reject
                                </button>
                            </div>
                        <?php else: ?>
                            <div style="padding-top:8px;border-top:1px solid var(--border-subtle)">
                                <p style="font-size:10px;font-weight:800;color:var(--border);font-family:monospace">#<?= $sk['id'] ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="mobileNoResults" style="display:none" class="empty-state">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
            <p style="font-weight:800;color:var(--text-sub)">No accounts match your search.</p>
        </div>
    </main>

    <script>
        let curTab = 'all';
        let approveTargetId = null,
            rejectTargetId = null;

        const allTableRows = Array.from(document.querySelectorAll('.sk-row'));
        const allMobileCards = Array.from(document.querySelectorAll('.mobile-sk-card'));

        function switchToTab(tab) {
            curTab = tab;
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
            applyFilter();
        }

        function applyFilter() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const match = el => {
                const mt = curTab === 'all' || el.dataset.status === curTab;
                const ms = !q || el.dataset.search.includes(q);
                return mt && ms;
            };
            let n = 0;
            allTableRows.forEach(r => {
                const s = match(r);
                r.style.display = s ? '' : 'none';
                if (s) n++;
            });
            let m = 0;
            allMobileCards.forEach(c => {
                const s = match(c);
                c.style.display = s ? '' : 'none';
                if (s) m++;
            });
            const total = allTableRows.length;
            document.getElementById('resultCount').textContent = `Showing ${n||m} of ${total} account${total !== 1 ? 's' : ''}`;
            const tf = document.getElementById('tableFooter');
            if (tf) tf.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
            const mnr = document.getElementById('mobileNoResults');
            if (mnr) mnr.style.display = (m === 0 && allMobileCards.length > 0) ? 'block' : 'none';
        }

        const STATUS_META = {
            pending: {
                icon: 'fa-clock',
                bg: '#fef3c7',
                color: '#92400e',
                label: 'Pending — Awaiting review'
            },
            approved: {
                icon: 'fa-user-check',
                bg: '#dcfce7',
                color: '#166534',
                label: 'Approved — Portal access granted'
            },
            rejected: {
                icon: 'fa-user-xmark',
                bg: '#fee2e2',
                color: '#991b1b',
                label: 'Rejected'
            },
        };

        function openDetail(d) {
            const m = STATUS_META[d.status] || STATUS_META.pending;
            document.getElementById('dHero').innerHTML = `
        <div style="width:52px;height:52px;border-radius:16px;${d.avatarStyle};display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;flex-shrink:0">${d.initials}</div>
        <div style="min-width:0">
            <p style="font-weight:800;font-size:16px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${d.name}</p>
            <p style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${d.email}</p>
        </div>`;
            const bar = document.getElementById('dStatusBar');
            bar.style.background = m.bg;
            bar.style.color = m.color;
            bar.innerHTML = `<i class="fa-solid ${m.icon}"></i> <span style="font-weight:700">${m.label}</span>`;
            document.getElementById('dEmail').textContent = d.email;
            document.getElementById('dPhone').textContent = d.phone;
            document.getElementById('dDate').textContent = d.date;
            document.getElementById('dVerified').textContent = d.verified === 'Yes' ? '✓ Verified' : '✗ Not verified';
            const acts = document.getElementById('dActions');
            if (d.status === 'pending') {
                acts.innerHTML = `
            <button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
            <button onclick="triggerReject(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-reject"><i class="fa-solid fa-xmark"></i> Reject</button>`;
            } else {
                acts.innerHTML = `<button onclick="closeModal('detail')" class="btn-cancel" style="width:100%"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Close</button>`;
            }
            document.getElementById('detailModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function triggerApprove(id, name) {
            approveTargetId = id;
            document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : '';
            openModal('approve');
        }

        function triggerReject(id, name) {
            rejectTargetId = id;
            document.getElementById('rejectConfirmName').textContent = name ? `"${name}"` : '';
            openModal('reject');
        }

        function submitApprove() {
            const b = document.getElementById('confirmApproveBtn');
            b.disabled = true;
            b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
            document.getElementById('approveId').value = approveTargetId;
            document.getElementById('approveForm').submit();
        }

        function submitReject() {
            const b = document.getElementById('confirmRejectBtn');
            b.disabled = true;
            b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Rejecting…';
            document.getElementById('rejectId').value = rejectTargetId;
            document.getElementById('rejectForm').submit();
        }

        const overlayIds = {
            detail: 'detailModal',
            approve: 'approveModal',
            reject: 'rejectModal'
        };

        function openModal(key) {
            const el = document.getElementById(overlayIds[key]);
            if (el) {
                el.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(key) {
            const el = document.getElementById(overlayIds[key]);
            if (el) {
                el.classList.remove('open');
                document.body.style.overflow = '';
            }
            if (key === 'approve') {
                const b = document.getElementById('confirmApproveBtn');
                if (b) {
                    b.disabled = false;
                    b.innerHTML = '<i class="fa-solid fa-check"></i> Approve';
                }
            }
            if (key === 'reject') {
                const b = document.getElementById('confirmRejectBtn');
                if (b) {
                    b.disabled = false;
                    b.innerHTML = '<i class="fa-solid fa-xmark"></i> Reject';
                }
            }
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('detail');
                closeModal('approve');
                closeModal('reject');
            }
        });

        applyFilter();
    </script>
</body>

</html>