<?php
$page      = 'manage-sk';
$adminName = $admin_name ?? session()->get('name') ?? 'Administrator';

$pendingList  = is_array($pending ?? null)  ? ($pending  ?? []) : [];
$approvedList = is_array($approved ?? null) ? ($approved ?? []) : [];
$rejectedList = is_array($rejected ?? null) ? ($rejected ?? []) : [];

$pCount = is_countable($pendingList)  ? count($pendingList)  : 0;
$aCount = is_countable($approvedList) ? count($approvedList) : 0;
$rCount = is_countable($rejectedList) ? count($rejectedList) : 0;
$total  = $pCount + $aCount + $rCount;

$avatarStyles = [
    'background:#dbeafe;color:#1d4ed8',
    'background:#f3e8ff;color:#7c3aed',
    'background:#d1fae5;color:#065f46',
    'background:#ffe4e6;color:#be123c',
    'background:#fef3c7;color:#92400e',
];

$allMerged = array_merge(
    array_map(fn($s) => array_merge($s, ['_status' => 'pending']),  $pendingList  ?? []),
    array_map(fn($s) => array_merge($s, ['_status' => 'approved']), $approvedList ?? []),
    array_map(fn($s) => array_merge($s, ['_status' => 'rejected']), $rejectedList ?? [])
);

$sIcon          = ['pending' => 'fa-clock', 'approved' => 'fa-check', 'rejected' => 'fa-xmark'];
$pendingSkCount = $pCount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Manage SK Accounts | Admin</title>
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
        @media(max-width:480px) {
            .page-header-top { flex-direction: column; gap: 10px; }
            .topbar-right { width: 100%; justify-content: flex-end; }
        }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        @media(max-width:639px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 8px; }
        }
        .stat-card {
            background: var(--card); border-radius: var(--r-lg);
            border: 1px solid var(--border); border-left-width: 4px;
            padding: 14px 16px; cursor: pointer; transition: all .15s;
            box-shadow: var(--shadow-sm); min-width: 0;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-lbl { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .14em; color: var(--text-sub); }
        .stat-num { font-size: 1.6rem; font-weight: 800; line-height: 1; font-family: var(--mono); }
        @media(max-width:639px) {
            .stat-num { font-size: 1.35rem; }
            .stat-card { padding: 12px 14px; }
        }

        /* ── Filter card / search ── */
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
        .sk-avatar {
            width: 36px; height: 36px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px; flex-shrink: 0;
        }

        /* ── Mobile cards ── */
        .sk-card {
            background: var(--card); border-radius: var(--r-lg);
            border: 1px solid var(--border); padding: 14px 16px;
            cursor: pointer; transition: all .15s;
        }
        .sk-card:hover { border-color: var(--indigo-border); box-shadow: var(--shadow-md); transform: translateY(-1px); }

        /* ── FIX: Status badges — were referenced but never defined ── */
        .badge-pending  { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
        .badge-approved { background:#dcfce7; color:#166534; border:1px solid #86efac; }
        .badge-rejected { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }

        /* ── FIX: Action buttons — were referenced but never defined ── */
        .btn-approve-sm {
            background: #dcfce7; color: #16a34a; border: 1px solid #86efac;
            border-radius: 9px; padding: 6px 10px;
            font-size: 11px; font-weight: 800; cursor: pointer;
            font-family: var(--font); transition: all .15s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-approve-sm:hover { background: #16a34a; color: #fff; border-color: #16a34a; }

        .btn-reject-sm {
            background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
            border-radius: 9px; padding: 6px 10px;
            font-size: 11px; font-weight: 800; cursor: pointer;
            font-family: var(--font); transition: all .15s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-reject-sm:hover { background: #d97706; color: #fff; border-color: #d97706; }

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

        /* FIX: Modal confirm buttons — were referenced in JS but never defined */
        .btn-confirm-approve {
            flex: 1; height: 40px; border-radius: 12px;
            background: #16a34a; color: #fff; border: none;
            font-weight: 800; font-size: 13px; cursor: pointer;
            font-family: var(--font);
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all .15s;
        }
        .btn-confirm-approve:hover { background: #15803d; }
        .btn-confirm-approve:disabled { opacity: .6; cursor: not-allowed; }

        .btn-confirm-reject {
            flex: 1; height: 40px; border-radius: 12px;
            background: #dc2626; color: #fff; border: none;
            font-weight: 800; font-size: 13px; cursor: pointer;
            font-family: var(--font);
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all .15s;
        }
        .btn-confirm-reject:hover { background: #b91c1c; }
        .btn-confirm-reject:disabled { opacity: .6; cursor: not-allowed; }

        /* ── FIX: Detail modal field rows — used in modal HTML but never defined ── */
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

        /* ── Show/hide ── */
        @media(min-width:768px) { #mobileCardList, #mobileNoResults { display:none!important; } }
        @media(max-width:767px) { .hidden-on-mobile { display:none!important; } }

        /* ── Dark mode overrides ── */
        body.dark .stat-card   { background: var(--card); border-color: rgba(99,102,241,.15); }
        body.dark .card        { background: var(--card); border-color: rgba(99,102,241,.1); }
        body.dark .sk-card     { background: var(--card); border-color: rgba(99,102,241,.1); }
        body.dark .sk-card:hover { border-color: var(--indigo); }
        body.dark .icon-btn    { background:#101e35; border-color:rgba(99,102,241,.18); color:#7fb3e8; }
        body.dark .icon-btn:hover { background:rgba(99,102,241,.12); color:#a5b4fc; }
        body.dark .search-input { background: var(--input-bg); border-color: rgba(99,102,241,.18); color: var(--text); }
        body.dark .modal-box   { background: var(--card); }
        body.dark .btn-ghost   { background: var(--input-bg); color: #7fb3e8; }
        body.dark .btn-ghost:hover { background: rgba(99,102,241,.18); color: #a5b4fc; }
        body.dark .btn-delete-sm { background: rgba(220,38,38,.15); color: #f87171; border-color: rgba(220,38,38,.3); }
        body.dark .btn-delete-sm:hover { background: #dc2626; color: #fff; }
        body.dark .badge-pending  { background: rgba(245,158,11,.15); color: #fbbf24; border-color: rgba(251,191,36,.3); }
        body.dark .badge-approved { background: rgba(22,163,74,.15);  color: #4ade80; border-color: rgba(74,222,128,.3); }
        body.dark .badge-rejected { background: rgba(220,38,38,.15);  color: #f87171; border-color: rgba(248,113,113,.3); }
        body.dark .flash-success  { background: rgba(22,101,52,.25);  color: #86efac; border-color: rgba(134,239,172,.3); }
        body.dark .flash-error    { background: rgba(220,38,38,.2);   color: #fca5a5; border-color: rgba(252,165,165,.3); }
        body.dark .page-eyebrow { color:#4a6fa5; }
        body.dark .page-title   { color:#e2eaf8; }
        body.dark .page-sub     { color:#4a6fa5; }

        /* ── Animations ── */
        @keyframes fadeIn       { from{opacity:0} to{opacity:1} }
        @keyframes popIn        { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:none} }
        @keyframes slideUp      { from{opacity:0;transform:translateY(60px)} to{opacity:1;transform:none} }
        @keyframes slideInRight { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:none} }
        .fade-up { animation: slideUp .35s ease both; }
    </style>
</head>

<body>
    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- Hidden forms -->
    <form id="approveForm" method="POST" action="/admin/approve-sk" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
    <form id="rejectForm"  method="POST" action="/admin/reject-sk"  style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="rejectId"></form>
    <!-- FIX: Route /admin/delete-sk was missing — add to Routes.php and AdminController (see notes below) -->
    <form id="deleteForm"  method="POST" action="/admin/delete-sk"  style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="deleteId"></form>

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
            <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 12px">
                <div>
                    <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--text-sub);margin-bottom:3px">SK Account</p>
                    <h3 id="detailModalTitle" style="font-size:18px;font-weight:800;">Account Info</h3>
                </div>
                <button onclick="closeModal('detail')" class="modal-close" aria-label="Close" style="margin-top:2px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Hero -->
            <div id="dHero" style="margin:0 20px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px"></div>
            <!-- Status bar -->
            <div id="dStatusBar" style="margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700"></div>
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
                    <div><p class="dlabel">Applied</p><p id="dDate" class="dvalue"></p></div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-shield-check"></i></div>
                    <div><p class="dlabel">Email Verified</p><p id="dVerified" class="dvalue"></p></div>
                </div>
            </div>
            <!-- Actions -->
            <div id="dActions" style="padding:16px 20px;border-top:1px solid var(--border-subtle);display:flex;gap:10px;margin-top:8px;flex-wrap:wrap"></div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         APPROVE CONFIRM MODAL
    ════════════════════════════════════════════════════════ -->
    <div id="approveModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('approve')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div style="padding:24px 24px 20px;text-align:center">
                <div style="width:64px;height:64px;background:#dcfce7;color:#16a34a;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h3 style="font-size:18px;font-weight:800;">Approve SK Account?</h3>
                <p style="color:var(--text-sub);font-size:13px;margin-top:6px;font-weight:500">This will grant access to the SK portal and notify the applicant by email.</p>
                <p id="approveConfirmName" style="font-size:13px;margin-top:10px;font-weight:800;color:#16a34a"></p>
            </div>
            <div style="padding:0 24px 24px;display:flex;gap:10px">
                <button class="btn-cancel" onclick="closeModal('approve')">
                    <i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel
                </button>
                <button id="confirmApproveBtn" class="btn-confirm-approve" onclick="submitApprove()">
                    <i class="fa-solid fa-check"></i> Approve
                </button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         REJECT CONFIRM MODAL
    ════════════════════════════════════════════════════════ -->
    <div id="rejectModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('reject')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div style="padding:24px 24px 20px;text-align:center">
                <div style="width:64px;height:64px;background:#fee2e2;color:#dc2626;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <h3 style="font-size:18px;font-weight:800;">Reject SK Account?</h3>
                <p style="color:var(--text-sub);font-size:13px;margin-top:6px;font-weight:500">The applicant will be notified by email. This action cannot be undone.</p>
                <p id="rejectConfirmName" style="font-size:13px;margin-top:10px;font-weight:800;color:#dc2626"></p>
            </div>
            <div style="padding:0 24px 24px;display:flex;gap:10px">
                <button class="btn-cancel" onclick="closeModal('reject')">
                    <i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel
                </button>
                <button id="confirmRejectBtn" class="btn-confirm-reject" onclick="submitReject()">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ════════════════════════════════════════════════════════ -->
    <div id="deleteModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('delete')"></div>
        <div class="modal-box sm">
            <div class="sheet-handle"></div>
            <div style="padding:24px 24px 20px;text-align:center">
                <div style="width:64px;height:64px;background:#fee2e2;color:#dc2626;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h3 style="font-size:18px;font-weight:800;">Delete SK Account?</h3>
                <p style="color:var(--text-sub);font-size:13px;margin-top:6px;font-weight:500;line-height:1.6">
                    This will <strong>permanently remove</strong> the account and login credentials. This cannot be undone.
                </p>
                <p id="deleteConfirmName" style="font-size:13px;margin-top:10px;font-weight:800;color:#dc2626"></p>
            </div>
            <div style="padding:0 24px 24px;display:flex;gap:10px">
                <button class="btn-cancel" onclick="closeModal('delete')">
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
                    <h2 class="page-title">SK Accounts</h2>
                    <p class="page-sub">Manage Sangguniang Kabataan registrations</p>
                </div>
                <div class="topbar-right">
                    <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode">
                        <span id="darkIcon"><i class="fa-regular fa-sun"></i></span>
                    </div>
                    <a href="/admin/resident-accounts"
                        style="display:flex;align-items:center;gap:7px;background:var(--indigo-light);border:1px solid var(--indigo-border);color:var(--indigo);padding:9px 14px;border-radius:var(--r-sm);font-weight:700;font-size:12px;text-decoration:none;transition:all .15s;white-space:nowrap"
                        onmouseover="this.style.background='var(--indigo)';this.style.color='#fff'"
                        onmouseout="this.style.background='var(--indigo-light)';this.style.color='var(--indigo)'">
                        <i class="fa-solid fa-users" style="font-size:11px"></i>
                        View Residents
                    </a>
                    <?php if ($pCount > 0): ?>
                        <div style="display:flex;align-items:center;gap:7px;background:#fef3c7;border:1px solid #fde68a;color:#92400e;padding:9px 14px;border-radius:var(--r-sm);font-weight:700;font-size:12px">
                            <i class="fa-solid fa-clock" style="font-size:11px"></i>
                            <?= $pCount ?> pending<?= $pCount > 1 ? 's' : '' ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Stat cards -->
        <div class="stats-grid fade-up">
            <?php foreach ([
                ['Total',    $total,  '#3730a3', 'fa-users',      'all'],
                ['Pending',  $pCount, '#d97706', 'fa-clock',      'pending'],
                ['Approved', $aCount, '#16a34a', 'fa-user-check', 'approved'],
                ['Rejected', $rCount, '#dc2626', 'fa-user-xmark', 'rejected'],
            ] as [$lbl, $val, $color, $ico, $tab]): ?>
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
        <div class="card fade-up" style="padding:14px 16px;margin-bottom:12px;">
            <div style="margin-bottom:10px">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <!-- FIX: removed inline oninput — now uses debounced addEventListener in JS -->
                    <input id="searchInput"
                        type="text"
                        placeholder="Search by name or email…"
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
                <button class="qtab" data-tab="pending" onclick="switchToTab('pending')">
                    <i class="fa-solid fa-clock" style="font-size:11px"></i> Pending
                    <?php if ($pCount > 0): ?>
                        <span style="background:#f59e0b;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px"><?= $pCount ?></span>
                    <?php endif; ?>
                </button>
                <button class="qtab" data-tab="approved" onclick="switchToTab('approved')">
                    <i class="fa-solid fa-user-check" style="font-size:11px"></i> Approved
                    <span style="font-size:9px;font-weight:800;opacity:.7"><?= $aCount ?></span>
                </button>
                <button class="qtab" data-tab="rejected" onclick="switchToTab('rejected')">
                    <i class="fa-solid fa-user-xmark" style="font-size:11px"></i> Rejected
                    <span style="font-size:9px;font-weight:800;opacity:.7"><?= $rCount ?></span>
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
                        <th style="width:48px">#</th>
                        <th>Account</th>
                        <th>Email</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th style="text-align:right;width:230px">Actions</th>
                    </tr>
                </thead>
                <tbody id="skTableBody">
                    <?php if (empty($allMerged)): ?>
                        <tr>
                            <td colspan="6">
                                <div style="padding:80px 24px;text-align:center">
                                    <i class="fa-solid fa-users" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:12px"></i>
                                    <p style="font-weight:800;color:var(--text-sub);font-size:15px">No SK accounts yet</p>
                                    <p style="font-size:12px;color:var(--text-faint);margin-top:4px">SK applicants will appear here once they register.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allMerged as $idx => $sk):
                            $s     = $sk['_status'];
                            $name  = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                            $email = htmlspecialchars($sk['email'] ?? '—');
                            $phone = htmlspecialchars($sk['phone'] ?? 'N/A');
                            $date  = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                            $ver   = !empty($sk['is_verified']) ? 'Yes' : 'No';
                            $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                            $init  = strtoupper(substr($name, 0, 1));
                            $rowNum = $idx + 1; // FIX: sequential display number instead of raw database id
                            $mdata = json_encode([
                                'id'          => $sk['id'],
                                'rowNum'      => $rowNum,
                                'status'      => $s,
                                'name'        => $name,
                                'email'       => $email,
                                'phone'       => $phone,
                                'date'        => $date,
                                'verified'    => $ver,
                                'avatarStyle' => $avatarStyle,
                                'initials'    => $init,
                            ]);
                        ?>
                        <tr class="sk-row"
                            data-status="<?= $s ?>"
                            data-search="<?= htmlspecialchars(strtolower("$name $email")) ?>">
                            <td>
                                <span style="font-size:11px;font-weight:800;color:var(--text-sub);font-family:monospace">#<?= $rowNum ?></span>
                            </td>
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
                                    <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?>" style="font-size:9px"></i>
                                    <?= ucfirst($s) ?>
                                </span>
                            </td>
                            <td style="text-align:right">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap">
                                    <button onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-ghost">
                                        <i class="fa-solid fa-eye" style="font-size:10px"></i> View
                                    </button>
                                    <?php if ($s === 'pending'): ?>
                                        <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve-sm">
                                            <i class="fa-solid fa-check" style="font-size:10px"></i> Approve
                                        </button>
                                        <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-reject-sm" title="Reject">
                                            <i class="fa-solid fa-xmark" style="font-size:10px"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button onclick="triggerDelete(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')" class="btn-delete-sm" title="Delete">
                                        <i class="fa-solid fa-trash-can" style="font-size:10px"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Desktop no-results row -->
                    <tr id="desktopNoResults" style="display:none">
                        <td colspan="6">
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
            <?php if (empty($allMerged)): ?>
                <div style="text-align:center;padding:60px 20px">
                    <i class="fa-solid fa-users" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
                    <p style="font-weight:800;color:var(--text-sub)">No SK accounts yet</p>
                </div>
            <?php else: ?>
                <?php foreach ($allMerged as $idx => $sk):
                    $s     = $sk['_status'];
                    $name  = htmlspecialchars($sk['full_name'] ?? $sk['name'] ?? 'Unknown');
                    $email = htmlspecialchars($sk['email'] ?? '—');
                    $phone = htmlspecialchars($sk['phone'] ?? 'N/A');
                    $date  = !empty($sk['created_at']) ? date('M j, Y', strtotime($sk['created_at'])) : '—';
                    $ver   = !empty($sk['is_verified']) ? 'Yes' : 'No';
                    $avatarStyle = $avatarStyles[$idx % count($avatarStyles)];
                    $init  = strtoupper(substr($name, 0, 1));
                    $rowNum = $idx + 1; // FIX: sequential display number instead of raw database id
                    $mdata = json_encode([
                        'id'          => $sk['id'],
                        'rowNum'      => $rowNum,
                        'status'      => $s,
                        'name'        => $name,
                        'email'       => $email,
                        'phone'       => $phone,
                        'date'        => $date,
                        'verified'    => $ver,
                        'avatarStyle' => $avatarStyle,
                        'initials'    => $init,
                    ]);
                ?>
                <div class="sk-card mobile-sk-card"
                    data-status="<?= $s ?>"
                    data-search="<?= htmlspecialchars(strtolower("$name $email")) ?>"
                    onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                    <!-- Top row: avatar + name + badge -->
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                        <div class="sk-avatar" style="<?= $avatarStyle ?>"><?= $init ?></div>
                        <div style="flex:1;min-width:0">
                            <p style="font-weight:700;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                            <p style="font-size:11px;color:var(--text-sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p>
                        </div>
                        <span class="badge badge-<?= $s ?>" style="flex-shrink:0">
                            <i class="fa-solid <?= $sIcon[$s] ?? 'fa-circle' ?>" style="font-size:9px"></i>
                            <?= ucfirst($s) ?>
                        </span>
                    </div>

                    <!-- Meta -->
                    <p style="font-size:11px;color:var(--text-sub);font-weight:600;margin-bottom:10px">
                        <i class="fa-regular fa-calendar" style="font-size:10px;margin-right:4px"></i>Applied <?= $date ?>
                    </p>

                    <!-- Bottom actions -->
                    <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--border-subtle);align-items:center" onclick="event.stopPropagation()">
                        <?php if ($s === 'pending'): ?>
                            <button onclick="triggerApprove(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                style="flex:1;height:36px;border-radius:10px;background:#dcfce7;color:#16a34a;border:1px solid #86efac;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px;transition:all .15s">
                                <i class="fa-solid fa-check" style="font-size:10px"></i> Approve
                            </button>
                            <button onclick="triggerReject(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                                style="flex:1;height:36px;border-radius:10px;background:#fef3c7;color:#92400e;border:1px solid #fde68a;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px;transition:all .15s">
                                <i class="fa-solid fa-xmark" style="font-size:10px"></i> Reject
                            </button>
                        <?php else: ?>
                            <p style="font-size:10px;font-weight:800;color:var(--border);font-family:monospace;flex:1">#<?= $rowNum ?></p>
                        <?php endif; ?>
                        <button onclick="triggerDelete(<?= $sk['id'] ?>,'<?= addslashes($name) ?>')"
                            style="height:36px;padding:0 14px;border-radius:10px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:5px;transition:all .15s">
                            <i class="fa-solid fa-trash-can" style="font-size:10px"></i>
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

    </main>

    <script>
    (function () {
        'use strict';

        /* ─── State ─── */
        let curTab          = 'all';
        let approveTargetId = null;
        let rejectTargetId  = null;
        let deleteTargetId  = null;
        let searchTimer     = null;

        /* ─── Node lists ─── */
        const allTableRows   = Array.from(document.querySelectorAll('.sk-row'));
        const allMobileCards = Array.from(document.querySelectorAll('.mobile-sk-card'));
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

        /* ─── Filter ─── */
        function applyFilter() {
            const q = searchInput.value.toLowerCase().trim();
            const match = el => {
                const tabOk    = curTab === 'all' || el.dataset.status === curTab;
                const searchOk = !q || el.dataset.search.includes(q);
                return tabOk && searchOk;
            };
            let n = 0, m = 0;
            allTableRows.forEach(r   => { const s = match(r); r.style.display = s ? '' : 'none'; if (s) n++; });
            allMobileCards.forEach(c => { const s = match(c); c.style.display = s ? '' : 'none'; if (s) m++; });

            const tot = allTableRows.length || allMobileCards.length;
            const shown = allTableRows.length ? n : m;
            if (resultCount) resultCount.textContent = `Showing ${shown} of ${tot} account${tot !== 1 ? 's' : ''}`;
            if (tableFooter) tableFooter.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
            if (desktopNR)   desktopNR.style.display = (n === 0 && allTableRows.length > 0) ? '' : 'none';
            if (mobileNR)    mobileNR.style.display  = (m === 0 && allMobileCards.length > 0) ? 'block' : 'none';
        }

        /* FIX: debounced search — was firing on every keystroke with oninput */
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(applyFilter, 200);
        });

        function resetFilters() {
            searchInput.value = '';
            switchToTab('all');
        }
        window.resetFilters = resetFilters;

        /* ─── Status meta ─── */
        const STATUS_META = {
            pending:  { icon: 'fa-clock',      bg: '#fef3c7', color: '#92400e', label: 'Pending — Awaiting review' },
            approved: { icon: 'fa-user-check',  bg: '#dcfce7', color: '#166534', label: 'Approved — Portal access granted' },
            rejected: { icon: 'fa-user-xmark',  bg: '#fee2e2', color: '#991b1b', label: 'Rejected' },
        };

        /* ─── Detail modal ─── */
        function openDetail(d) {
            const meta = STATUS_META[d.status] || STATUS_META.pending;

            document.getElementById('dHero').innerHTML = `
                <div style="width:52px;height:52px;border-radius:16px;${d.avatarStyle};display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;flex-shrink:0">${d.initials}</div>
                <div style="min-width:0">
                    <p style="font-weight:800;font-size:16px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${d.name}</p>
                    <p style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px">${d.email}</p>
                </div>`;

            const bar = document.getElementById('dStatusBar');
            bar.style.cssText = `margin:0 20px 14px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;background:${meta.bg};color:${meta.color}`;
            bar.innerHTML = `<i class="fa-solid ${meta.icon}"></i><span>${meta.label}</span>`;

            document.getElementById('dEmail').textContent    = d.email;
            document.getElementById('dPhone').textContent    = d.phone || 'N/A';
            document.getElementById('dDate').textContent     = d.date;
            document.getElementById('dVerified').textContent = d.verified === 'Yes' ? '✓ Verified' : '✗ Not verified';

            /* Actions */
            const safeName = d.name.replace(/'/g, "\\'");
            let html = '';
            if (d.status === 'pending') {
                html += `<button onclick="triggerApprove(${d.id},'${safeName}');closeModal('detail');" class="btn-confirm-approve">
                    <i class="fa-solid fa-check"></i> Approve
                </button>`;
                html += `<button onclick="triggerReject(${d.id},'${safeName}');closeModal('detail');" class="btn-confirm-reject">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>`;
            } else {
                html += `<button onclick="closeModal('detail')" class="btn-cancel" style="flex:1">
                    <i class="fa-solid fa-xmark" style="font-size:11px"></i> Close
                </button>`;
            }
            html += `<button onclick="triggerDelete(${d.id},'${safeName}');closeModal('detail');"
                style="height:40px;padding:0 16px;border-radius:12px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;font-weight:800;font-size:12px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;gap:6px;white-space:nowrap;transition:all .15s">
                <i class="fa-solid fa-trash-can" style="font-size:11px"></i> Delete
            </button>`;
            document.getElementById('dActions').innerHTML = html;

            openModal('detail');
        }
        window.openDetail = openDetail;

        /* ─── Approve ─── */
        function triggerApprove(id, name) {
            approveTargetId = id;
            document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : '';
            openModal('approve');
        }
        function submitApprove() {
            const btn = document.getElementById('confirmApproveBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
            document.getElementById('approveId').value = approveTargetId;
            document.getElementById('approveForm').submit();
        }
        window.triggerApprove = triggerApprove;
        window.submitApprove  = submitApprove;

        /* ─── Reject ─── */
        function triggerReject(id, name) {
            rejectTargetId = id;
            document.getElementById('rejectConfirmName').textContent = name ? `"${name}"` : '';
            openModal('reject');
        }
        function submitReject() {
            const btn = document.getElementById('confirmRejectBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Rejecting…';
            document.getElementById('rejectId').value = rejectTargetId;
            document.getElementById('rejectForm').submit();
        }
        window.triggerReject = triggerReject;
        window.submitReject  = submitReject;

        /* ─── Delete ─── */
        function triggerDelete(id, name) {
            deleteTargetId = id;
            document.getElementById('deleteConfirmName').textContent = name ? `"${name}"` : '';
            openModal('delete');
        }
        function submitDelete() {
            const btn = document.getElementById('confirmDeleteBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';
            document.getElementById('deleteId').value = deleteTargetId;
            document.getElementById('deleteForm').submit();
        }
        window.triggerDelete = triggerDelete;
        window.submitDelete  = submitDelete;

        /* ─── Modal helpers ─── */
        const overlayMap = {
            detail:  'detailModal',
            approve: 'approveModal',
            reject:  'rejectModal',
            delete:  'deleteModal',
        };

        function openModal(key) {
            const el = document.getElementById(overlayMap[key]);
            /* FIX: body overflow was not always reset — now handled centrally here */
            if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
        }
        function closeModal(key) {
            const el = document.getElementById(overlayMap[key]);
            if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
            /* Reset button states */
            const resets = {
                approve: ['confirmApproveBtn', '<i class="fa-solid fa-check"></i> Approve'],
                reject:  ['confirmRejectBtn',  '<i class="fa-solid fa-xmark"></i> Reject'],
                delete:  ['confirmDeleteBtn',  '<i class="fa-solid fa-trash-can"></i> Delete Permanently'],
            };
            if (resets[key]) {
                const [btnId, html] = resets[key];
                const btn = document.getElementById(btnId);
                if (btn) { btn.disabled = false; btn.innerHTML = html; }
            }
        }
        window.openModal  = openModal;
        window.closeModal = closeModal;

        /* Escape key closes all modals */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                ['detail', 'approve', 'reject', 'delete'].forEach(closeModal);
            }
        });

        /* ─── Auto-dismiss flash toasts after 5 s ─── */
        ['flashSuccess', 'flashError'].forEach(id => {
            const el = document.getElementById(id);
            if (el) setTimeout(() => {
                el.style.transition = 'opacity .4s';
                el.style.opacity    = '0';
                setTimeout(() => el.remove(), 400);
            }, 5000);
        });

        /* ─── Initial render ─── */
        applyFilter();

    })();
    </script>
</body>
</html>