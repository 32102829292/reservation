<?php
$page      = 'residents-account';
$adminName = $admin_name ?? session()->get('name') ?? 'Administrator';

$total      = $total      ?? 0;
$verified   = $verified   ?? 0;
$unverified = $unverified ?? 0;
$residents  = $residents  ?? [];
$reservationCounts = $reservationCounts ?? [];

$avatarStyles = [
    'background:#dbeafe;color:#1d4ed8',
    'background:#f3e8ff;color:#7c3aed',
    'background:#d1fae5;color:#065f46',
    'background:#ffe4e6;color:#be123c',
    'background:#fef3c7;color:#92400e',
    'background:#e0f2fe;color:#0369a1',
    'background:#fce7f3;color:#9d174d',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Resident Accounts | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <style>
        /* ── Layout ── */
        .main-area { padding: 24px 20px 80px; }
        @media(max-width:639px) { .main-area { padding: 14px 12px 80px; } }

        /* ── Page header ── */
        .page-header { display: flex; flex-direction: column; gap: 3px; margin-bottom: 20px; }
        .page-header-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; flex-wrap: wrap; }
        .icon-btn {
            width: 44px; height: 44px; background: var(--card);
            border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-sub); cursor: pointer; transition: all .2s;
            box-shadow: var(--shadow-sm);
        }
        @media(max-width:540px) {
            .page-header-top { flex-direction: column; gap: 10px; }
            .topbar-right { width: 100%; justify-content: flex-end; }
        }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        @media(max-width:639px) {
            .stats-grid { gap: 8px; }
        }
        .stat-card {
            background: var(--card); border-radius: var(--r-lg);
            border: 1px solid var(--border); border-left-width: 4px;
            padding: 14px 16px; transition: all .15s; cursor: pointer;
            box-shadow: var(--shadow-sm); min-width: 0;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-lbl { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .14em; color: var(--text-sub); }
        .stat-num { font-size: 1.6rem; font-weight: 800; line-height: 1; font-family: var(--mono); }
        @media(max-width:639px) {
            .stat-num { font-size: 1.2rem; }
            .stat-card { padding: 10px 12px; }
        }

        /* ── Filter / search bar ── */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); }
        .search-wrap { position: relative; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-sub); font-size: 12px; pointer-events: none; }
        .search-input {
            width: 100%; padding: 10px 14px 10px 36px;
            background: var(--input-bg); border: 1px solid rgba(99,102,241,.15);
            border-radius: var(--r-sm); font-family: var(--font); font-size: .875rem;
            color: var(--text); outline: none; transition: all .2s;
        }
        .search-input:focus { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .qtab {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: var(--r-sm);
            font-size: .78rem; font-weight: 700; cursor: pointer;
            border: 1px solid transparent; background: transparent;
            color: var(--text-sub); font-family: var(--font); white-space: nowrap;
            transition: all .15s;
        }
        .qtab:hover { background: var(--input-bg); }
        .qtab.active { background: var(--indigo); color: #fff; border-color: var(--indigo); }

        /* ── Avatar ── */
        .res-avatar {
            width: 36px; height: 36px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px; flex-shrink: 0;
        }

        /* ── Mobile cards ── */
        .res-card {
            background: var(--card); border-radius: var(--r-lg);
            border: 1px solid var(--border); padding: 14px 16px;
            cursor: pointer; transition: all .15s;
        }
        .res-card:hover { border-color: var(--indigo-border); box-shadow: var(--shadow-md); transform: translateY(-1px); }

        /* ── Buttons ── */
        .btn-delete-sm {
            background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;
            border-radius: 9px; padding: 6px 10px;
            font-size: 11px; font-weight: 800; cursor: pointer;
            font-family: var(--font); transition: all .15s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-delete-sm:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

        .btn-ghost {
            background: var(--input-bg-alt); color: var(--text-muted);
            border: none; border-radius: 9px; padding: 6px 10px;
            font-size: 11px; font-weight: 800; cursor: pointer;
            font-family: var(--font); transition: all .15s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-ghost:hover { background: var(--indigo-light); color: var(--indigo); }

        /* ── Flash toasts ── */
        .flash-toast {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            border-radius: 14px; padding: 13px 18px;
            font-size: 13px; font-weight: 700;
            box-shadow: 0 8px 24px rgba(0,0,0,.14);
            display: flex; align-items: center; gap: 9px;
            max-width: 360px; animation: slideInRight .3s cubic-bezier(.34,1.56,.64,1) both;
        }
        .flash-toast .flash-close {
            background: none; border: none; cursor: pointer;
            font-size: 18px; line-height: 1; margin-left: auto;
            opacity: .7; padding: 0 0 0 6px;
        }
        .flash-toast .flash-close:hover { opacity: 1; }
        .flash-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .flash-error   { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }

        /* ── Modals ── */
        .overlay {
            display: none; position: fixed; inset: 0;
            z-index: 300; align-items: center; justify-content: center;
        }
        .overlay.open { display: flex; animation: fadeIn .15s ease; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,.55); backdrop-filter: blur(6px); }
        .modal-box {
            position: relative; margin: auto; background: var(--card);
            border-radius: 28px; width: 94%; max-width: 500px;
            max-height: 92vh; overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.35);
            animation: popIn .22s cubic-bezier(.34,1.56,.64,1) both;
        }
        .modal-box.sm { max-width: 380px; }
        @media(max-width:639px) {
            .overlay#detailModal { align-items: flex-end; }
            .overlay#detailModal .modal-box {
                margin: 0; width: 100%; max-width: 100%;
                border-radius: 28px 28px 0 0; max-height: 92vh;
                animation: slideUp .28s cubic-bezier(.34,1.2,.64,1) both;
            }
        }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* ── Detail modal inner rows ── */
        .drow {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 11px 0; border-bottom: 1px solid var(--border-subtle);
        }
        .drow:last-child { border-bottom: none; }
        .dicon {
            width: 32px; height: 32px; border-radius: 10px;
            background: var(--indigo-light); color: var(--indigo);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; flex-shrink: 0; margin-top: 2px;
        }
        .dlabel { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--text-sub); margin-bottom: 2px; }
        .dvalue { font-size: 13px; font-weight: 700; color: var(--text); }

        /* ── Show/hide helpers ── */
        @media(min-width:768px) { #mobileCardList, #mobileNoResults { display:none!important; } }
        @media(max-width:767px) { .hidden-on-mobile { display:none!important; } }

        /* ── Dark mode overrides ── */
        body.dark .stat-card { background: var(--card); border-color: rgba(99,102,241,.15); }
        body.dark .card { background: var(--card); border-color: rgba(99,102,241,.1); }
        body.dark .res-card { background: var(--card); border-color: rgba(99,102,241,.1); }
        body.dark .res-card:hover { border-color: var(--indigo); }
        body.dark .icon-btn { background:#101e35; border-color:rgba(99,102,241,.18); color:#7fb3e8; }
        body.dark .search-input { background: var(--input-bg); border-color: rgba(99,102,241,.18); color: var(--text); }
        body.dark .modal-box { background: var(--card); }
        body.dark .btn-ghost { background: var(--input-bg); color: #7fb3e8; }
        body.dark .btn-ghost:hover { background: rgba(99,102,241,.18); color: #a5b4fc; }
        body.dark .btn-delete-sm { background: rgba(220,38,38,.15); color: #f87171; border-color: rgba(220,38,38,.3); }
        body.dark .btn-delete-sm:hover { background: #dc2626; color: #fff; }
        body.dark .page-eyebrow { color:#4a6fa5; }
        body.dark .page-title   { color:#e2eaf8; }
        body.dark .page-sub     { color:#4a6fa5; }
        body.dark .flash-success { background: rgba(22,101,52,.25); color: #86efac; border-color: rgba(134,239,172,.3); }
        body.dark .flash-error   { background: rgba(220,38,38,.2); color: #fca5a5; border-color: rgba(252,165,165,.3); }

        /* ── Animations ── */
        @keyframes fadeIn      { from{opacity:0} to{opacity:1} }
        @keyframes popIn       { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:none} }
        @keyframes slideUp     { from{opacity:0;transform:translateY(60px)} to{opacity:1;transform:none} }
        @keyframes slideInRight{ from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:none} }
        .fade-up { animation: slideUp .35s ease both; }
    </style>
</head>

<body>
    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- ── Hidden delete form ── -->
    <form id="deleteResidentForm" method="POST" action="/admin/delete-resident" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="deleteResidentId">
    </form>

    <!-- ════════════════════════════════════════════════════════
         FLASH TOASTS
    ════════════════════════════════════════════════════════ -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-toast flash-success" id="flashSuccess">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= htmlspecialchars(session()->getFlashdata('success')) ?></span>
            <button class="flash-close" onclick="this.closest('.flash-toast').remove()" style="color:#166534">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-toast flash-error" id="flashError">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= htmlspecialchars(session()->getFlashdata('error')) ?></span>
            <button class="flash-close" onclick="this.closest('.flash-toast').remove()" style="color:#dc2626">&times;</button>
        </div>
    <?php endif; ?>

    <!-- ════════════════════════════════════════════════════════
         DETAIL MODAL
    ════════════════════════════════════════════════════════ -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true" aria-labelledby="detailModalTitle">
        <div class="overlay-bg" onclick="closeModal('detail')"></div>
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <!-- Header -->
            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 12px">
                <div>
                    <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--text-sub);margin-bottom:3px">Resident Account</p>
                    <h3 id="detailModalTitle" style="font-size:18px;font-weight:800;">Account Info</h3>
                </div>
                <button onclick="closeModal('detail')" class="modal-close" aria-label="Close" style="margin-top:2px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Hero -->
            <div id="dHero" style="margin:0 20px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px"></div>
            <!-- Verified bar -->
            <div id="dVerifiedBar" style="margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700"></div>
            <!-- Fields -->
            <div style="padding:0 20px 8px">
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-envelope"></i></div>
                    <div><p class="dlabel">Email</p><p id="dEmail" class="dvalue" style="word-break:break-all"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-phone"></i></div>
                    <div><p class="dlabel">Phone</p><p id="dPhone" class="dvalue"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-regular fa-calendar"></i></div>
                    <div><p class="dlabel">Registered</p><p id="dDate" class="dvalue"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-bookmark"></i></div>
                    <div><p class="dlabel">Reservations</p><p id="dReservations" class="dvalue"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-circle-info"></i></div>
                    <div><p class="dlabel">Account Status</p><p id="dStatus" class="dvalue"></p></div>
                </div>
            </div>
            <!-- Actions -->
            <div id="dActions" style="padding:16px 20px;border-top:1px solid var(--border-subtle);display:flex;gap:10px;margin-top:8px;flex-wrap:wrap"></div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ════════════════════════════════════════════════════════ -->
    <div id="deleteModal" class="overlay" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="overlay-bg" onclick="closeModal('delete')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div style="padding:24px 24px 20px;text-align:center">
                <div style="width:64px;height:64px;background:#fee2e2;color:#dc2626;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h3 id="deleteModalTitle" style="font-size:18px;font-weight:800;">Delete Resident Account?</h3>
                <p style="color:var(--text-sub);font-size:13px;margin-top:6px;font-weight:500;line-height:1.6">
                    This will <strong>permanently remove</strong> the account and login credentials.
                    Reservation history will be preserved.
                </p>
                <p id="deleteConfirmName" style="font-size:13px;margin-top:10px;font-weight:800;color:#dc2626"></p>
            </div>
            <div style="padding:0 24px 24px;display:flex;gap:10px">
                <button class="btn-cancel" onclick="closeModal('delete')" style="flex:1">
                    <i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel
                </button>
                <button id="confirmDeleteBtn"
                    onclick="submitDelete()"
                    style="flex:1;height:40px;border-radius:12px;background:#dc2626;color:#fff;border:none;font-weight:800;font-size:13px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;transition:all .15s">
                    <i class="fa-solid fa-trash-can"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         MAIN CONTENT
    ════════════════════════════════════════════════════════ -->
    <main class="main-area">

        <!-- Page header -->
        <header class="page-header fade-up">
            <div class="page-header-top">
                <div>
                    <div class="page-eyebrow">Admin Portal</div>
                    <h2 class="page-title">Resident Accounts</h2>
                    <p class="page-sub">All registered community residents</p>
                </div>
                <div class="topbar-right">
                    <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode">
                        <span id="darkIcon"><i class="fa-regular fa-sun"></i></span>
                    </div>
                    <!-- Export CSV -->
                    <a href="/admin/resident-accounts/export"
                        id="exportBtn"
                        style="display:flex;align-items:center;gap:7px;background:var(--indigo-light);border:1px solid var(--indigo-border);color:var(--indigo);padding:9px 14px;border-radius:var(--r-sm);font-weight:700;font-size:12px;text-decoration:none;transition:all .15s;white-space:nowrap"
                        onmouseover="this.style.background='var(--indigo)';this.style.color='#fff'"
                        onmouseout="this.style.background='var(--indigo-light)';this.style.color='var(--indigo)'">
                        <i class="fa-solid fa-file-csv" style="font-size:11px"></i>
                        Export CSV
                    </a>
                    <!-- SK Accounts shortcut -->
                    <a href="/admin/manage-sk"
                        style="display:flex;align-items:center;gap:7px;background:var(--indigo-light);border:1px solid var(--indigo-border);color:var(--indigo);padding:9px 14px;border-radius:var(--r-sm);font-weight:700;font-size:12px;text-decoration:none;transition:all .15s;white-space:nowrap"
                        onmouseover="this.style.background='var(--indigo)';this.style.color='#fff'"
                        onmouseout="this.style.background='var(--indigo-light)';this.style.color='var(--indigo)'">
                        <i class="fa-solid fa-shield-halved" style="font-size:11px"></i>
                        SK Accounts
                    </a>
                </div>
            </div>
        </header>

        <!-- Stat cards -->
        <div class="stats-grid fade-up">
            <div class="stat-card" style="border-left-color:#3730a3" onclick="switchToTab('all')" title="Show all residents">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <p class="stat-lbl">Total</p>
                    <i class="fa-solid fa-users" style="font-size:13px;color:#3730a3"></i>
                </div>
                <p class="stat-num" style="color:#3730a3"><?= $total ?></p>
            </div>
            <div class="stat-card" style="border-left-color:#16a34a" onclick="switchToTab('verified')" title="Show verified residents">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <p class="stat-lbl">Verified</p>
                    <i class="fa-solid fa-circle-check" style="font-size:13px;color:#16a34a"></i>
                </div>
                <p class="stat-num" style="color:#16a34a"><?= $verified ?></p>
            </div>
            <div class="stat-card" style="border-left-color:#d97706" onclick="switchToTab('unverified')" title="Show unverified residents">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <p class="stat-lbl">Unverified</p>
                    <i class="fa-solid fa-circle-exclamation" style="font-size:13px;color:#d97706"></i>
                </div>
                <p class="stat-num" style="color:#d97706"><?= $unverified ?></p>
            </div>
        </div>

        <!-- Filter bar -->
        <div class="card fade-up" style="padding:14px 16px;margin-bottom:12px;">
            <div style="margin-bottom:10px">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="searchInput"
                        type="text"
                        placeholder="Search by name, email or phone…"
                        class="search-input"
                        autocomplete="off"
                        spellcheck="false">
                </div>
            </div>
            <div style="display:flex;gap:6px;overflow-x:auto;padding-bottom:2px;-webkit-overflow-scrolling:touch">
                <button class="qtab active" data-tab="all" onclick="switchToTab('all')">
                    <i class="fa-solid fa-users" style="font-size:11px"></i> All
                    <span style="font-size:9px;font-weight:800;opacity:.7"><?= $total ?></span>
                </button>
                <button class="qtab" data-tab="verified" onclick="switchToTab('verified')">
                    <i class="fa-solid fa-circle-check" style="font-size:11px"></i> Verified
                    <span style="font-size:9px;font-weight:800;opacity:.7"><?= $verified ?></span>
                </button>
                <button class="qtab" data-tab="unverified" onclick="switchToTab('unverified')">
                    <i class="fa-solid fa-circle-exclamation" style="font-size:11px"></i> Unverified
                    <span style="font-size:9px;font-weight:800;opacity:.7"><?= $unverified ?></span>
                </button>
            </div>
        </div>

        <p id="resultCount" style="font-size:11px;font-weight:700;color:var(--text-sub);padding:0 4px;margin-bottom:10px"></p>

        <!-- ════════════════════════════════════════════════════
             DESKTOP TABLE
        ════════════════════════════════════════════════════ -->
        <div class="tbl-wrap hidden-on-mobile fade-up">
            <table>
                <thead>
                    <tr>
                        <th style="width:48px">ID</th>
                        <th>Resident</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered</th>
                        <th>Reservations</th>
                        <th>Verified</th>
                        <th style="text-align:right;width:130px">Actions</th>
                    </tr>
                </thead>
                <tbody id="resTableBody">
                    <?php if (empty($residents)): ?>
                        <tr id="emptyState">
                            <td colspan="8">
                                <div style="padding:80px 24px;text-align:center">
                                    <i class="fa-solid fa-users" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:12px"></i>
                                    <p style="font-weight:800;color:var(--text-sub);font-size:15px">No resident accounts yet</p>
                                    <p style="font-size:12px;color:var(--text-faint);margin-top:4px">Residents will appear here once they register.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($residents as $idx => $r):
                            $name     = htmlspecialchars($r['full_name'] ?? $r['name'] ?? 'Unknown');
                            $email    = htmlspecialchars($r['email'] ?? '—');
                            $phone    = htmlspecialchars($r['phone'] ?? 'N/A');
                            $date     = !empty($r['created_at']) ? date('M j, Y', strtotime($r['created_at'])) : '—';
                            $isVer    = !empty($r['email_verified']);
                            $status   = $r['status'] ?? 'pending';
                            $resCount = $reservationCounts[$r['id']] ?? 0;
                            $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                            $init     = strtoupper(substr($name, 0, 1));
                            $verTab   = $isVer ? 'verified' : 'unverified';
                            $searchStr= strtolower("$name $email " . ($r['phone'] ?? ''));
                            $mdata    = json_encode([
                                'id'           => $r['id'],
                                'name'         => $name,
                                'email'        => $email,
                                'phone'        => $phone,
                                'date'         => $date,
                                'verified'     => $isVer,
                                'status'       => $status,
                                'reservations' => $resCount,
                                'avatarStyle'  => $avatarStyle,
                                'initials'     => $init,
                            ]);
                        ?>
                        <tr class="res-row"
                            data-verifytab="<?= $verTab ?>"
                            data-search="<?= htmlspecialchars($searchStr) ?>">
                            <td>
                                <span style="font-size:11px;font-weight:800;color:var(--text-sub);font-family:monospace">#<?= $r['id'] ?></span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="res-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                                    <div>
                                        <p style="font-weight:700;font-size:13px;"><?= $name ?></p>
                                        <p style="font-size:11px;color:var(--text-sub);margin-top:2px">Joined <?= $date ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p style="font-size:13px;color:var(--text-muted);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:190px"><?= $email ?></p>
                            </td>
                            <td>
                                <p style="font-size:13px;color:var(--text-muted);font-weight:600"><?= $phone ?></p>
                            </td>
                            <td>
                                <p style="font-size:13px;color:var(--text-muted);font-weight:600;white-space:nowrap"><?= $date ?></p>
                            </td>
                            <td>
                                <span style="font-size:12px;font-weight:800;font-family:var(--mono);color:<?= $resCount > 0 ? 'var(--indigo)' : 'var(--text-sub)' ?>">
                                    <?= $resCount ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($isVer): ?>
                                    <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #86efac">
                                        <i class="fa-solid fa-circle-check" style="font-size:9px"></i> Verified
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a">
                                        <i class="fa-solid fa-circle-exclamation" style="font-size:9px"></i> Unverified
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px">
                                    <button onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-ghost">
                                        <i class="fa-solid fa-eye" style="font-size:10px"></i> View
                                    </button>
                                    <button onclick="triggerDelete(<?= $r['id'] ?>,'<?= addslashes($name) ?>')" class="btn-delete-sm" title="Delete resident">
                                        <i class="fa-solid fa-trash-can" style="font-size:10px"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Desktop no-results row (injected by JS, kept here as fallback) -->
                    <tr id="desktopNoResults" style="display:none">
                        <td colspan="8">
                            <div style="padding:60px 24px;text-align:center">
                                <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
                                <p style="font-weight:800;color:var(--text-sub);font-size:14px">No accounts match your filter.</p>
                                <button onclick="resetFilters()" style="margin-top:10px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border);border-radius:10px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font)">
                                    <i class="fa-solid fa-rotate-left" style="font-size:10px"></i> Clear filters
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Table footer -->
            <div style="padding:10px 18px;border-top:1px solid var(--border-subtle);background:rgba(238,242,255,.4);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                <p id="tableFooter" style="font-size:11px;font-weight:700;color:var(--text-sub)"></p>
                <p style="font-size:11px;color:var(--text-faint);font-weight:600;">
                    <i class="fa-solid fa-eye" style="font-size:10px"></i> Click View for full account details
                </p>
            </div>
        </div>

        <!-- ════════════════════════════════════════════════════
             MOBILE CARDS
        ════════════════════════════════════════════════════ -->
        <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px">
            <?php if (empty($residents)): ?>
                <div class="empty-state" style="text-align:center;padding:60px 20px">
                    <i class="fa-solid fa-users" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
                    <p style="font-weight:800;color:var(--text-sub)">No resident accounts yet</p>
                    <p style="font-size:12px;color:var(--text-faint);margin-top:4px">Residents will appear here once they register.</p>
                </div>
            <?php else: ?>
                <?php foreach ($residents as $idx => $r):
                    $name     = htmlspecialchars($r['full_name'] ?? $r['name'] ?? 'Unknown');
                    $email    = htmlspecialchars($r['email'] ?? '—');
                    $phone    = htmlspecialchars($r['phone'] ?? 'N/A');
                    $date     = !empty($r['created_at']) ? date('M j, Y', strtotime($r['created_at'])) : '—';
                    $isVer    = !empty($r['email_verified']);
                    $status   = $r['status'] ?? 'pending';
                    $resCount = $reservationCounts[$r['id']] ?? 0;
                    $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                    $init     = strtoupper(substr($name, 0, 1));
                    $verTab   = $isVer ? 'verified' : 'unverified';
                    $searchStr= strtolower("$name $email " . ($r['phone'] ?? ''));
                    $mdata    = json_encode([
                        'id'           => $r['id'],
                        'name'         => $name,
                        'email'        => $email,
                        'phone'        => $phone,
                        'date'         => $date,
                        'verified'     => $isVer,
                        'status'       => $status,
                        'reservations' => $resCount,
                        'avatarStyle'  => $avatarStyle,
                        'initials'     => $init,
                    ]);
                ?>
                <div class="res-card mobile-res-card"
                    data-verifytab="<?= $verTab ?>"
                    data-search="<?= htmlspecialchars($searchStr) ?>"
                    onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                    <!-- Top row: avatar + name + badge -->
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                        <div class="res-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                        <div style="flex:1;min-width:0">
                            <p style="font-weight:700;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                            <p style="font-size:11px;color:var(--text-sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p>
                        </div>
                        <?php if ($isVer): ?>
                            <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #86efac;flex-shrink:0">
                                <i class="fa-solid fa-circle-check" style="font-size:9px"></i> Verified
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;flex-shrink:0">
                                <i class="fa-solid fa-circle-exclamation" style="font-size:9px"></i> Unverified
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Meta row: date + reservations -->
                    <div style="display:flex;gap:14px;margin-bottom:10px">
                        <p style="font-size:11px;color:var(--text-sub);font-weight:600">
                            <i class="fa-regular fa-calendar" style="font-size:10px;margin-right:3px"></i><?= $date ?>
                        </p>
                        <p style="font-size:11px;color:var(--text-sub);font-weight:600">
                            <i class="fa-solid fa-bookmark" style="font-size:10px;margin-right:3px"></i>
                            <?= $resCount ?> reservation<?= $resCount !== 1 ? 's' : '' ?>
                        </p>
                        <?php if (!empty($phone) && $phone !== 'N/A'): ?>
                        <p style="font-size:11px;color:var(--text-sub);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            <i class="fa-solid fa-phone" style="font-size:10px;margin-right:3px"></i><?= $phone ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <!-- Bottom row: ID + delete -->
                    <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--border-subtle);align-items:center" onclick="event.stopPropagation()">
                        <p style="font-size:10px;font-weight:800;color:var(--border);font-family:monospace;flex:1">#<?= $r['id'] ?></p>
                        <button onclick="triggerDelete(<?= $r['id'] ?>,'<?= addslashes($name) ?>')"
                            style="height:34px;padding:0 14px;border-radius:10px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;gap:5px;transition:all .15s"
                            onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                            onmouseout="this.style.background='#fee2e2';this.style.color='#dc2626'">
                            <i class="fa-solid fa-trash-can" style="font-size:10px"></i> Delete
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Mobile no-results -->
        <div id="mobileNoResults" style="display:none;text-align:center;padding:60px 20px">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
            <p style="font-weight:800;color:var(--text-sub)">No accounts match your filter.</p>
            <button onclick="resetFilters()" style="margin-top:12px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border);border-radius:10px;padding:8px 18px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font)">
                <i class="fa-solid fa-rotate-left" style="font-size:10px"></i> Clear filters
            </button>
        </div>

    </main><!-- /main-area -->

    <script>
    (function () {
        'use strict';

        /* ─── State ─── */
        let curTab         = 'all';
        let deleteTargetId = null;
        let searchTimer    = null;

        /* ─── Node lists ─── */
        const allTableRows   = Array.from(document.querySelectorAll('.res-row'));
        const allMobileCards = Array.from(document.querySelectorAll('.mobile-res-card'));
        const searchInput    = document.getElementById('searchInput');
        const resultCount    = document.getElementById('resultCount');
        const tableFooter    = document.getElementById('tableFooter');
        const desktopNR      = document.getElementById('desktopNoResults');
        const mobileNR       = document.getElementById('mobileNoResults');

        /* ─── Tab switching ─── */
        function switchToTab(tab) {
            curTab = tab;
            document.querySelectorAll('.qtab').forEach(t =>
                t.classList.toggle('active', t.dataset.tab === tab));
            applyFilter();
        }
        window.switchToTab = switchToTab;

        /* ─── Filter + search ─── */
        function applyFilter() {
            const q = searchInput.value.toLowerCase().trim();

            const match = el => {
                const tabOk    = curTab === 'all' || el.dataset.verifytab === curTab;
                const searchOk = !q || el.dataset.search.includes(q);
                return tabOk && searchOk;
            };

            let n = 0, m = 0;
            allTableRows.forEach(r   => { const s = match(r); r.style.display = s ? '' : 'none'; if (s) n++; });
            allMobileCards.forEach(c => { const s = match(c); c.style.display = s ? '' : 'none'; if (s) m++; });

            const tot = allTableRows.length;
            const shown = tot ? n : m;  // fall back to mobile count if desktop list is hidden
            resultCount.textContent = `Showing ${shown} of ${tot || allMobileCards.length} account${(tot || allMobileCards.length) !== 1 ? 's' : ''}`;

            if (tableFooter) tableFooter.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;

            // Desktop no-results
            if (desktopNR) desktopNR.style.display = (n === 0 && allTableRows.length > 0) ? '' : 'none';
            // Mobile no-results
            if (mobileNR)  mobileNR.style.display  = (m === 0 && allMobileCards.length > 0) ? 'block' : 'none';
        }

        /* Debounced search */
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(applyFilter, 200);
        });

        /* Clear filters helper */
        function resetFilters() {
            searchInput.value = '';
            switchToTab('all');
        }
        window.resetFilters = resetFilters;

        /* ─── Detail modal ─── */
        function openDetail(d) {
            /* Hero */
            document.getElementById('dHero').innerHTML = `
                <div style="width:52px;height:52px;border-radius:16px;${d.avatarStyle};display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;flex-shrink:0">${d.initials}</div>
                <div style="min-width:0">
                    <p style="font-weight:800;font-size:16px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${d.name}</p>
                    <p style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px">${d.email}</p>
                </div>`;

            /* Verified bar */
            const bar = document.getElementById('dVerifiedBar');
            if (d.verified) {
                bar.style.cssText = 'margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;background:#dcfce7;color:#166534';
                bar.innerHTML = `<i class="fa-solid fa-circle-check"></i><span>Email Verified</span>`;
            } else {
                bar.style.cssText = 'margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;background:#fef3c7;color:#92400e';
                bar.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i><span>Email Not Verified</span>`;
            }

            /* Fields */
            document.getElementById('dEmail').textContent        = d.email;
            document.getElementById('dPhone').textContent        = d.phone || 'N/A';
            document.getElementById('dDate').textContent         = d.date;
            document.getElementById('dReservations').textContent = d.reservations + ' total reservation' + (d.reservations !== 1 ? 's' : '');
            document.getElementById('dStatus').textContent       = d.status.charAt(0).toUpperCase() + d.status.slice(1);

            /* Actions */
            const safeName = d.name.replace(/'/g, "\\'");
            document.getElementById('dActions').innerHTML = `
                <button onclick="closeModal('detail')" class="btn-cancel" style="flex:1">
                    <i class="fa-solid fa-xmark" style="font-size:11px"></i> Close
                </button>
                <button onclick="triggerDelete(${d.id},'${safeName}');closeModal('detail');"
                    style="height:40px;padding:0 16px;border-radius:12px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;gap:6px;white-space:nowrap;transition:all .15s">
                    <i class="fa-solid fa-trash-can" style="font-size:11px"></i> Delete Account
                </button>`;

            openModal('detail');
        }
        window.openDetail = openDetail;

        /* ─── Delete flow ─── */
        function triggerDelete(id, name) {
            deleteTargetId = id;
            document.getElementById('deleteConfirmName').textContent = name ? `"${name}"` : '';
            openModal('delete');
        }
        window.triggerDelete = triggerDelete;

        function submitDelete() {
            const btn = document.getElementById('confirmDeleteBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';
            document.getElementById('deleteResidentId').value = deleteTargetId;
            document.getElementById('deleteResidentForm').submit();
        }
        window.submitDelete = submitDelete;

        /* ─── Modal helpers ─── */
        const overlayMap = { detail: 'detailModal', delete: 'deleteModal' };

        function openModal(key) {
            const el = document.getElementById(overlayMap[key]);
            if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
        }
        function closeModal(key) {
            const el = document.getElementById(overlayMap[key]);
            if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
            if (key === 'delete') {
                const btn = document.getElementById('confirmDeleteBtn');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Delete Permanently'; }
            }
        }
        window.openModal  = openModal;
        window.closeModal = closeModal;

        /* Escape key */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeModal('detail'); closeModal('delete'); }
        });

        /* ─── Auto-dismiss flash toasts after 5 s ─── */
        ['flashSuccess', 'flashError'].forEach(id => {
            const el = document.getElementById(id);
            if (el) setTimeout(() => {
                el.style.transition = 'opacity .4s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            }, 5000);
        });

        /* ─── Export button feedback ─── */
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                const orig = this.innerHTML;
                this.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:11px"></i> Preparing…';
                setTimeout(() => { this.innerHTML = orig; }, 2500);
            });
        }

        /* ─── Initial render ─── */
        applyFilter();

    })();
    </script>
</body>
</html>