<?php
date_default_timezone_set('Asia/Manila');
$page = 'user-requests';
$pendingUserCount = $pendingUserCount ?? 0;
$usedSlots = (int)($usedThisMonth ?? 0);
$maxSlots  = max(1, (int)($maxMonthlySlots ?? 3));
// Derive remaining from used+max so the quota widget is never stuck at 0
$remainingReservations = isset($remainingReservations) && (int)$remainingReservations > 0
    ? (int)$remainingReservations
    : max(0, $maxSlots - $usedSlots);
$user_name = $user_name ?? 'Officer';

$userReservations = $userReservations ?? [];
$pending  = array_filter($userReservations, fn($r) => ($r['status'] ?? '') === 'pending');
$approved = array_filter($userReservations, fn($r) => ($r['status'] ?? '') === 'approved');
$declined = array_filter($userReservations, fn($r) => in_array($r['status'] ?? '', ['declined', 'canceled']));
$pCount = count($pending); $aCount = count($approved); $dCount = count($declined);
$total  = count($userReservations);
$statusIcons = ['pending'=>'fa-clock','approved'=>'fa-circle-check','declined'=>'fa-xmark','canceled'=>'fa-ban'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <title>User Requests | SK Officer</title>
    <style>
        body { display: flex; height: 100vh; height: 100dvh; overflow: hidden; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 20px; }
        @media(max-width:639px) { .stats-grid { grid-template-columns: repeat(2,1fr); gap: 10px; } }

        .stat-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 18px 20px; box-shadow: var(--shadow-sm); border-left-width: 4px; transition: transform var(--ease), box-shadow var(--ease); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8; }
        .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; font-family: var(--mono); margin-top: 6px; }
        .stat-hint { font-size: .72rem; color: #94a3b8; margin-top: 4px; }

        .filter-bar { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); padding: 16px 20px; margin-bottom: 16px; }
        .search-input { width: 100%; padding: 10px 12px 10px 34px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .85rem; font-family: var(--font); background: #f8fafc; color: #0f172a; transition: all var(--ease); outline: none; }
        .search-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .qtab { display: inline-flex; align-items: center; gap: 6px; padding: .4rem .9rem; border-radius: var(--r-sm); font-size: .75rem; font-weight: 700; transition: all var(--ease); cursor: pointer; border: 1px solid rgba(99,102,241,.12); white-space: nowrap; color: #64748b; background: white; font-family: var(--font); }
        .qtab:hover { border-color: var(--indigo); color: var(--indigo); }
        .qtab.active { background: var(--indigo); color: white; border-color: var(--indigo); box-shadow: 0 4px 12px rgba(55,48,163,.25); }
        .reset-btn { padding: .5rem .9rem; border-radius: var(--r-sm); font-size: .75rem; font-weight: 700; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; cursor: pointer; font-family: var(--font); transition: all var(--ease); display: flex; align-items: center; gap: 5px; }
        .reset-btn:hover { background: #e2e8f0; color: #0f172a; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 640px; }
        thead th { background: #f8fafc; font-weight: 700; text-transform: uppercase; font-size: .62rem; letter-spacing: .12em; color: #94a3b8; padding: .9rem 1rem; border-bottom: 1px solid rgba(99,102,241,.08); white-space: nowrap; }
        td { padding: .875rem 1rem; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background var(--ease); cursor: pointer; }
        tbody tr:hover td { background: var(--indigo-light); }

        .req-card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); padding: 16px 18px; cursor: pointer; transition: all var(--ease); position: relative; overflow: hidden; box-shadow: var(--shadow-sm); }
        .req-card:hover { border-color: var(--indigo-border); box-shadow: var(--shadow-md); transform: translateY(-1px); }
        .req-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:0 4px 4px 0; }
        .req-card[data-status="pending"]::before  { background: #f59e0b; }
        .req-card[data-status="approved"]::before { background: #10b981; }
        .req-card[data-status="declined"]::before, .req-card[data-status="canceled"]::before { background: #ef4444; }

        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
        .tag-pending  { background: #fef3c7; color: #92400e; }
        .tag-approved { background: #dcfce7; color: #166534; }
        .tag-declined, .tag-canceled { background: #fee2e2; color: #991b1b; }

        .btn-approve { padding: .4rem .8rem; border-radius: 8px; font-size: .72rem; font-weight: 700; cursor: pointer; border: none; background: #dcfce7; color: #166534; font-family: var(--font); transition: all var(--ease); display: inline-flex; align-items: center; gap: 4px; }
        .btn-approve:hover { background: #16a34a; color: white; }
        .btn-decline { padding: .4rem .8rem; border-radius: 8px; font-size: .72rem; font-weight: 700; cursor: pointer; border: none; background: #fee2e2; color: #991b1b; font-family: var(--font); transition: all var(--ease); display: inline-flex; align-items: center; gap: 4px; }
        .btn-decline:hover { background: #ef4444; color: white; }

        .alert-banner { border-radius: var(--r-md); padding: 14px 18px; margin-bottom: 16px; border: 1px solid; display: flex; align-items: center; gap: 10px; font-size: .82rem; font-weight: 700; }
        .alert-pending { background: #fef3c7; border-color: #fde68a; color: #92400e; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #14532d; }
        .alert-error   { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }

        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 520px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg); }
        .modal-card.sm { max-width: 380px; }
        @media(max-width:479px) { .modal-back { padding: 0; align-items: flex-end; } .modal-card { border-radius: var(--r-xl) var(--r-xl) 0 0; max-height: 92dvh; } .sheet-handle { display: block !important; } }
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 4px; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: .65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; flex-shrink: 0; }
        .detail-value { font-weight: 700; color: #0f172a; font-size: .88rem; text-align: right; }

        .notif-dd { position: fixed; top: 80px; right: 20px; width: 320px; background: white; border-radius: var(--r-xl); box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.09); z-index: 200; display: none; overflow: hidden; animation: dropIn .15s ease; }
        .notif-dd.show { display: block; }
        .notif-item { padding: .85rem 1.1rem; border-bottom: 1px solid #f8fafc; transition: background .15s; cursor: pointer; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: var(--indigo-light); }
        .notif-item:last-child { border-bottom: none; }
        @media(max-width:479px) { .notif-dd { left: 12px; right: 12px; width: auto; top: 72px; } }

        .toast-container { position: fixed; top: 80px; right: 16px; z-index: 1000; display: flex; flex-direction: column; gap: 8px; pointer-events: none; max-width: 320px; }
        .toast { background: white; border-radius: var(--r-md); padding: 12px 14px; box-shadow: var(--shadow-lg); display: flex; align-items: flex-start; gap: 10px; pointer-events: auto; border: 1px solid rgba(99,102,241,.1); border-left-width: 4px; border-left-color: #f59e0b; animation: slideRight .3s cubic-bezier(.34,1.56,.64,1) both; }
        @keyframes slideRight { from{opacity:0;transform:translateX(16px)} to{opacity:1;transform:none} }
        @media(max-width:479px) { .toast-container { left: 12px; right: 12px; max-width: none; } }

        .empty-state { text-align: center; padding: 48px 20px; }
        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
        @keyframes dropIn  { from{opacity:0;transform:translateY(-4px) scale(.98)} to{opacity:1;transform:none} }
        @keyframes slideUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s ease both; }

        body.dark .filter-bar { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
        body.dark .search-input { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
        body.dark .search-input:focus { background: #0b1628 !important; border-color: #818cf8 !important; }
        body.dark .qtab { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }
        body.dark .qtab.active { background: var(--indigo) !important; color: white !important; }
        body.dark .reset-btn { background: #101e35 !important; color: #7fb3e8 !important; border-color: rgba(99,102,241,.1) !important; }
        body.dark thead th { background: #101e35 !important; color: #4a6fa5 !important; border-color: rgba(99,102,241,.08) !important; }
        body.dark td { border-color: #101e35 !important; }
        body.dark tbody tr:hover td { background: rgba(99,102,241,.06) !important; }
        body.dark .req-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
        body.dark .req-card:hover { border-color: rgba(99,102,241,.3) !important; }
        body.dark .modal-card { background: #0b1628 !important; color: #e2eaf8 !important; }
        body.dark .detail-row { border-color: #101e35 !important; }
        body.dark .detail-label { color: #4a6fa5 !important; }
        body.dark .detail-value { color: #e2eaf8 !important; }
        body.dark .notif-dd { background: #0b1628 !important; }
        body.dark .notif-item { border-color: #101e35 !important; }
        body.dark .notif-item.unread { background: rgba(55,48,163,.18) !important; }
        body.dark .notif-item:hover { background: #101e35 !important; }
        body.dark .toast { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; border-left-color: #f59e0b !important; }
        body.dark .alert-pending { background: rgba(180,83,9,.2) !important; border-color: rgba(180,83,9,.3) !important; color: #fcd34d !important; }
        body.dark .alert-success { background: rgba(20,83,45,.25) !important; border-color: rgba(134,239,172,.2) !important; color: #86efac !important; }
        body.dark .alert-error   { background: rgba(127,29,29,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }
        body.dark .tag-pending  { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }
        body.dark .tag-approved { background: rgba(16,185,129,.1) !important; color: #34d399 !important; }
        body.dark .tag-declined, body.dark .tag-canceled { background: rgba(239,68,68,.15) !important; color: #f87171 !important; }
        body.dark .stat-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
        body.dark .stat-num { color: #e2eaf8 !important; }
        body.dark .stat-lbl, body.dark .stat-hint { color: #4a6fa5 !important; }
        body.dark .sheet-handle { background: #1e3a5f !important; }
        body.dark .pending-pill { background: rgba(180,83,9,.2) !important; border-color: rgba(180,83,9,.3) !important; color: #fcd34d !important; }

        @media(min-width:768px) { #mobileCardList{display:none!important;} #mobileEmpty{display:none!important;} }
        @media(max-width:767px) { #desktopTable{display:none!important;} }
    </style>
</head>
<body>
<?php include APPPATH . 'Views/partials/sk_layout.php'; ?>

<form id="approveForm" method="POST" action="<?= base_url('sk/approve') ?>" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
<form id="declineForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="declineId"></form>

<!-- Detail modal -->
<div id="detailModal" class="modal-back" onclick="if(event.target===this)closeModal('detail')">
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;">
            <p id="mId" style="font-size:.65rem;font-weight:700;color:#94a3b8;font-family:var(--mono);"></p>
            <button onclick="closeModal('detail')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Request Details</h3>
            <span id="mTag" class="tag"></span>
        </div>
        <div id="mStatusBar" style="border-radius:var(--r-sm);padding:10px 14px;display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:700;margin-bottom:16px;"></div>
        <div id="mBody" style="background:#f8fafc;border-radius:var(--r-md);padding:14px;border:1px solid rgba(99,102,241,.07);margin-bottom:16px;"></div>
        <div id="mActions" style="display:flex;gap:10px;"></div>
    </div>
</div>

<!-- Approve confirm -->
<div id="approveModal" class="modal-back" onclick="if(event.target===this)closeModal('approve')">
    <div class="modal-card sm">
        <div class="sheet-handle"></div>
        <div style="text-align:center;padding:8px 0 16px;">
            <div style="width:52px;height:52px;background:#dcfce7;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.5rem;"><i class="fa-solid fa-circle-check" style="color:#16a34a;"></i></div>
            <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Approve Request?</h3>
            <p style="font-size:.8rem;color:#94a3b8;margin-top:4px;font-weight:500;">This will confirm the reservation.</p>
            <p id="approveConfirmName" style="font-size:.85rem;font-weight:700;color:#0f172a;margin-top:10px;"></p>
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeModal('approve')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);">Cancel</button>
            <button id="confirmApproveBtn" style="flex:1;padding:12px;background:#16a34a;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;"><i class="fa-solid fa-check" style="font-size:.75rem;"></i> Approve</button>
        </div>
    </div>
</div>

<!-- Decline confirm -->
<div id="declineModal" class="modal-back" onclick="if(event.target===this)closeModal('decline')">
    <div class="modal-card sm">
        <div class="sheet-handle"></div>
        <div style="text-align:center;padding:8px 0 16px;">
            <div style="width:52px;height:52px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.5rem;"><i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i></div>
            <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Decline Request?</h3>
            <p style="font-size:.8rem;color:#94a3b8;margin-top:4px;font-weight:500;">This action cannot be undone.</p>
            <p id="declineConfirmName" style="font-size:.85rem;font-weight:700;color:#0f172a;margin-top:10px;"></p>
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeModal('decline')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);">Cancel</button>
            <button id="confirmDeclineBtn" style="flex:1;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i> Decline</button>
        </div>
    </div>
</div>

<div id="notifDD" class="notif-dd">
    <div style="padding:11px 13px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-weight:700;font-size:.82rem;color:#0f172a;">New Requests</span>
        <button onclick="markAllRead()" style="font-size:.7rem;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
    </div>
    <div id="notifList" style="max-height:280px;overflow-y:auto;"></div>
</div>

<div id="toastContainer" class="toast-container"></div>

<main class="main-area">
    <div class="topbar fade-up" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap;">
        <div>
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px">SK Officer Portal</div>
            <div style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1">User Requests</div>
            <div style="font-size:.78rem;color:#94a3b8;margin-top:4px;font-weight:500">Review and manage resident reservation requests</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;margin-top:4px;flex-wrap:wrap;">
            <?php if ($pCount > 0): ?>
                <span class="pending-pill" style="display:flex;align-items:center;gap:6px;background:#fef3c7;border:1px solid #fde68a;color:#92400e;padding:8px 14px;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;">
                    <i class="fa-solid fa-clock" style="font-size:.75rem;"></i> <?= $pCount ?> pending
                </span>
            <?php endif; ?>
            <div class="icon-btn" onclick="layoutToggleDark()" title="Toggle dark mode"><span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span></div>
            <div class="icon-btn notif-bell-btn" onclick="toggleNotif()" style="position:relative;">
                <i class="fa-regular fa-bell" style="font-size:.9rem;"></i>
                <span id="notifBadge" style="display:none;position:absolute;top:-5px;right:-5px;background:#ef4444;color:white;font-size:.55rem;font-weight:700;padding:2px 5px;border-radius:999px;min-width:17px;text-align:center;border:2px solid var(--bg);">0</span>
            </div>
            <a href="/sk/new-reservation" style="display:flex;align-items:center;gap:7px;padding:10px 18px;background:var(--indigo);color:#fff;border-radius:var(--r-sm);font-size:.85rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);text-decoration:none;box-shadow:0 4px 12px rgba(55,48,163,.28);">
                <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> New Reservation
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-banner alert-success fade-up"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-banner alert-error fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if ($pCount > 0): ?>
        <div class="alert-banner alert-pending fade-up">
            <i class="fa-regular fa-clock"></i>
            You have <strong style="font-family:var(--mono);margin:0 4px;"><?= $pCount ?></strong> pending request<?= $pCount!=1?'s':'' ?> waiting for approval.
        </div>
    <?php endif; ?>

    <p style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:14px;display:flex;align-items:center;gap:8px;" class="fade-up-1"><span style="display:inline-block;width:3px;height:14px;border-radius:2px;background:var(--indigo);flex-shrink:0;"></span>Overview</p>
    <div class="stats-grid fade-up-1">
        <div class="stat-card" style="border-left-color:var(--indigo);"><div class="stat-lbl">Total</div><div class="stat-num" style="color:var(--indigo);"><?= $total ?></div><div class="stat-hint">All requests</div></div>
        <div class="stat-card" style="border-left-color:#d97706;"><div class="stat-lbl">Pending</div><div class="stat-num" style="color:#d97706;"><?= $pCount ?></div><div class="stat-hint">Awaiting action</div></div>
        <div class="stat-card" style="border-left-color:#16a34a;"><div class="stat-lbl">Approved</div><div class="stat-num" style="color:#16a34a;"><?= $aCount ?></div><div class="stat-hint">Confirmed</div></div>
        <div class="stat-card" style="border-left-color:#ef4444;"><div class="stat-lbl">Declined</div><div class="stat-num" style="color:#ef4444;"><?= $dCount ?></div><div class="stat-hint">Rejected</div></div>
    </div>

    <div class="filter-bar fade-up-1">
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:180px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.72rem;pointer-events:none;"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search name, resource, purpose…" oninput="applyFilters()">
            </div>
            <button class="reset-btn" onclick="clearFilters()"><i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Reset</button>
        </div>
        <div style="display:flex;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:2px;">
            <button class="qtab active" data-tab="all" onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group" style="font-size:.7rem;"></i> All <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $total ?></span></button>
            <button class="qtab" data-tab="pending" onclick="setTab(this,'pending')"><i class="fa-regular fa-clock" style="font-size:.7rem;"></i> Pending <?php if ($pCount > 0): ?><span style="background:#f59e0b;color:white;font-size:.55rem;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $pCount ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="approved" onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check" style="font-size:.7rem;"></i> Approved</button>
            <button class="qtab" data-tab="declined" onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark" style="font-size:.7rem;"></i> Declined</button>
        </div>
        <p id="resultCount" style="font-size:.65rem;font-weight:700;color:#94a3b8;margin-top:10px;"></p>
    </div>

    <p style="font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:14px;display:flex;align-items:center;gap:8px;" class="fade-up-2"><span style="display:inline-block;width:3px;height:14px;border-radius:2px;background:var(--indigo);flex-shrink:0;"></span>Requests</p>

    <div class="card fade-up-2" id="desktopTable">
        <div class="table-wrap">
            <table>
                <thead><tr><th style="width:52px;">ID</th><th>User</th><th>Resource</th><th>Schedule</th><th>Purpose</th><th>Status</th><th style="text-align:right;width:180px;">Actions</th></tr></thead>
                <tbody id="tableBody">
                    <?php if (empty($userReservations)): ?>
                        <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-inbox" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i><p style="font-weight:800;color:#94a3b8;font-size:1rem;">No requests yet</p></div></td></tr>
                    <?php else: ?>
                        <?php foreach ($userReservations as $res):
                            $s = strtolower($res['status'] ?? 'pending');
                            $name     = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                            $email    = htmlspecialchars($res['user_email'] ?? '');
                            $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #'.($res['resource_id']??''));
                            $pc       = htmlspecialchars($res['pc_number'] ?? '');
                            $rawDate  = $res['reservation_date'] ?? '';
                            $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                            $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                            $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                            $purpose  = htmlspecialchars($res['purpose'] ?? '—');
                            $created  = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                            $mdata    = json_encode(['id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,'resource'=>$resource,'pc'=>$pc,'date'=>$date,'start'=>$start,'end'=>$end,'purpose'=>$purpose,'created'=>$created]);
                        ?>
                            <tr class="res-row" data-status="<?= $s ?>" data-search="<?= strtolower("$name $resource $purpose $email") ?>" onclick='openDetailModal(<?= htmlspecialchars($mdata,ENT_QUOTES) ?>)'>
                                <td><span style="font-size:.65rem;font-weight:700;color:#94a3b8;font-family:var(--mono);">#<?= $res['id'] ?></span></td>
                                <td><p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $name ?></p><?php if ($email): ?><p style="font-size:.7rem;color:#94a3b8;margin-top:1px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= $email ?></p><?php endif; ?></td>
                                <td><p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $resource ?></p><?php if ($pc): ?><p style="font-size:.7rem;color:#94a3b8;margin-top:1px;"><i class="fa-solid fa-desktop" style="font-size:.65rem;"></i> <?= $pc ?></p><?php endif; ?></td>
                                <td><p style="font-size:.85rem;font-weight:700;color:#0f172a;"><?= $date ?></p><p style="font-size:.7rem;color:#6366f1;font-family:var(--mono);margin-top:1px;"><?= $start ?> – <?= $end ?></p></td>
                                <td><span style="font-size:.82rem;color:#64748b;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px;"><?= $purpose ?></span></td>
                                <td><span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                                <td style="text-align:right;" onclick="event.stopPropagation()">
                                    <?php if ($s === 'pending'): ?>
                                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap;">
                                            <button onclick="triggerApprove(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-approve"><i class="fa-solid fa-check" style="font-size:.65rem;"></i> Approve</button>
                                            <button onclick="triggerDecline(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-decline"><i class="fa-solid fa-xmark" style="font-size:.65rem;"></i> Decline</button>
                                        </div>
                                    <?php else: ?><span style="font-size:.7rem;color:#cbd5e1;font-style:italic;">No actions</span><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div id="noResults" class="hidden" style="text-align:center;padding:40px 20px;"><i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i><p style="font-size:.85rem;font-weight:700;color:#94a3b8;">No requests match your search.</p></div>
        <div style="padding:12px 20px;border-top:1px solid rgba(99,102,241,.06);background:#f8fafc;"><p id="tableFooter" style="font-size:.65rem;font-weight:700;color:#94a3b8;"></p></div>
    </div>

    <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px;margin-top:16px;" class="fade-up-2">
        <?php if (empty($userReservations)): ?>
            <div class="card" style="padding:48px 20px;text-align:center;"><i class="fa-solid fa-inbox" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i><p style="font-weight:800;color:#94a3b8;">No requests yet</p></div>
        <?php else: ?>
            <?php foreach ($userReservations as $res):
                $s = strtolower($res['status'] ?? 'pending');
                $name     = htmlspecialchars($res['visitor_name'] ?? $res['full_name'] ?? 'Guest');
                $email    = htmlspecialchars($res['user_email'] ?? '');
                $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #'.($res['resource_id']??''));
                $pc       = htmlspecialchars($res['pc_number'] ?? '');
                $rawDate  = $res['reservation_date'] ?? '';
                $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                $purpose  = htmlspecialchars($res['purpose'] ?? '—');
                $created  = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                $mdata    = json_encode(['id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,'resource'=>$resource,'pc'=>$pc,'date'=>$date,'start'=>$start,'end'=>$end,'purpose'=>$purpose,'created'=>$created]);
            ?>
                <div class="req-card" data-status="<?= $s ?>" data-search="<?= strtolower("$name $resource $purpose $email") ?>" onclick='openDetailModal(<?= htmlspecialchars($mdata,ENT_QUOTES) ?>)'>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div style="width:36px;height:36px;border-radius:10px;background:var(--indigo-light);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--indigo);font-size:.85rem;flex-shrink:0;"><?= mb_strtoupper(mb_substr(strip_tags($name),0,1)) ?></div>
                        <div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $name ?></p><p style="font-size:.7rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $email ?></p></div>
                        <span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span>
                    </div>
                    <div style="margin-bottom:8px;">
                        <p style="font-size:.78rem;font-weight:700;color:#0f172a;margin-bottom:3px;"><i class="fa-solid fa-desktop" style="color:#94a3b8;font-size:.65rem;margin-right:4px;"></i><?= $resource ?><?= $pc?' · '.htmlspecialchars($pc):'' ?></p>
                        <p style="font-size:.72rem;color:#6366f1;font-family:var(--mono);"><?= $date ?> · <?= $start ?> – <?= $end ?></p>
                    </div>
                    <p style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:10px;"><?= $purpose ?></p>
                    <?php if ($s === 'pending'): ?>
                        <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid rgba(99,102,241,.07);" onclick="event.stopPropagation()">
                            <button onclick="triggerApprove(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-approve" style="flex:1;justify-content:center;"><i class="fa-solid fa-check" style="font-size:.65rem;"></i> Approve</button>
                            <button onclick="triggerDecline(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-decline" style="flex:1;justify-content:center;"><i class="fa-solid fa-xmark" style="font-size:.65rem;"></i> Decline</button>
                        </div>
                    <?php else: ?>
                        <div style="padding-top:8px;border-top:1px solid rgba(99,102,241,.07);"><p style="font-size:.62rem;color:#cbd5e1;font-family:var(--mono);">#<?= $res['id'] ?></p></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="mobileEmpty" style="display:none;" class="card" style="padding:48px 20px;text-align:center;">
        <div style="padding:32px 0;text-align:center;"><i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:#e2e8f0;display:block;margin-bottom:10px;"></i><p style="font-size:.85rem;font-weight:700;color:#94a3b8;">No requests match your search.</p></div>
    </div>
</main>

<script>
const resData = <?= json_encode($userReservations ?? []) ?>;
let approveTargetId = null, declineTargetId = null, curTab = 'all';
const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
const allCards     = Array.from(document.querySelectorAll('#mobileCardList .req-card'));

function setTab(btn, tab) { document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active')); btn.classList.add('active'); curTab = tab; applyFilters(); }
function applyFilters() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    let n = 0;
    const matches = el => { const st=el.dataset.status; const matchTab=curTab==='all'||(curTab==='declined'&&['declined','canceled'].includes(st))||st===curTab; return matchTab && (!q || (el.dataset.search||'').includes(q)); };
    allTableRows.forEach(row => { const show=matches(row); row.style.display=show?'':'none'; if(show) n++; });
    let cv=0; allCards.forEach(card => { const show=matches(card); card.style.display=show?'':'none'; if(show) cv++; });
    const total=allTableRows.length;
    document.getElementById('resultCount').textContent = `Showing ${n} of ${total} request${total!==1?'s':''}`;
    document.getElementById('tableFooter').textContent = `${n} result${n!==1?'s':''} displayed`;
    document.getElementById('noResults').classList.toggle('hidden', n>0);
    const me=document.getElementById('mobileEmpty'); if(allCards.length>0) me.style.display=cv===0?'block':'none';
}
function clearFilters() { document.getElementById('searchInput').value=''; curTab='all'; document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab==='all')); applyFilters(); }

const META = { pending:{bg:'#fef3c7',color:'#92400e',icon:'fa-clock',label:'Pending — Awaiting your approval'}, approved:{bg:'#dcfce7',color:'#166534',icon:'fa-circle-check',label:'Approved'}, declined:{bg:'#fee2e2',color:'#991b1b',icon:'fa-ban',label:'Declined'}, canceled:{bg:'#fee2e2',color:'#991b1b',icon:'fa-ban',label:'Cancelled'} };
function openDetailModal(d) {
    const m = META[d.status] || META.pending;
    document.getElementById('mId').textContent = 'Request #' + d.id;
    const tag = document.getElementById('mTag'); tag.textContent=ucfirst(d.status); tag.className=`tag tag-${d.status}`;
    const bar = document.getElementById('mStatusBar'); bar.style.background=m.bg; bar.style.color=m.color; bar.innerHTML=`<i class="fa-solid ${m.icon}"></i><span>${m.label}</span>`;
    document.getElementById('mBody').innerHTML=[['Requestor',d.name],['Email',d.email||'—'],['Resource',d.resource+(d.pc?' · '+d.pc:'')],['Date',d.date],['Time',d.start+' – '+d.end],['Purpose',d.purpose],['Submitted',d.created]].map(([l,v])=>`<div class="detail-row"><span class="detail-label">${l}</span><span class="detail-value">${v}</span></div>`).join('');
    const acts=document.getElementById('mActions');
    if(d.status==='pending'){ acts.innerHTML=`<button onclick="closeModal('detail');triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');" style="flex:1;padding:12px;background:#dcfce7;color:#166534;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;"><i class="fa-solid fa-check" style="font-size:.75rem;"></i> Approve</button><button onclick="closeModal('detail');triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}');" style="flex:1;padding:12px;background:#fee2e2;color:#991b1b;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i> Decline</button>`; }
    else { acts.innerHTML=`<button onclick="closeModal('detail')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);">Close</button>`; }
    openModal('detail');
}
function ucfirst(s){return s?s.charAt(0).toUpperCase()+s.slice(1):s;}

function triggerApprove(id,name){ approveTargetId=id; document.getElementById('approveConfirmName').textContent=name?`"${name}"`:''; openModal('approve'); }
function triggerDecline(id,name){ declineTargetId=id; document.getElementById('declineConfirmName').textContent=name?`"${name}"`:''; openModal('decline'); }
document.getElementById('confirmApproveBtn').addEventListener('click',function(){ if(!approveTargetId)return; this.disabled=true; this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Approving…'; document.getElementById('approveId').value=approveTargetId; document.getElementById('approveForm').submit(); });
document.getElementById('confirmDeclineBtn').addEventListener('click',function(){ if(!declineTargetId)return; this.disabled=true; this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Declining…'; document.getElementById('declineId').value=declineTargetId; document.getElementById('declineForm').submit(); });

const modals={detail:'detailModal',approve:'approveModal',decline:'declineModal'};
function openModal(k){ const el=document.getElementById(modals[k]); if(el){el.classList.add('show');document.body.style.overflow='hidden';} }
function closeModal(k){ const el=document.getElementById(modals[k]); if(el){el.classList.remove('show');document.body.style.overflow='';} if(k==='approve'){const b=document.getElementById('confirmApproveBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-check" style="font-size:.75rem;"></i> Approve';} if(k==='decline'){const b=document.getElementById('confirmDeclineBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-xmark" style="font-size:.75rem;"></i> Decline';} }
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal('detail');closeModal('approve');closeModal('decline');}});

let notifs=[],unread=0,readIds=JSON.parse(localStorage.getItem('sk_read_notifs')||'[]'),lastCheck=new Date().toISOString();
function initNotifs(){ resData.forEach(r=>{ if(r.status!=='pending'||readIds.includes(String(r.id)))return; const hrs=(Date.now()-new Date(r.created_at))/3600000; if(hrs>48)return; notifs.push({id:r.id,title:'Pending Request',msg:`${r.visitor_name||'User'} requested ${r.resource_name||'a resource'}`,time:r.created_at,read:false}); }); unread=notifs.length; renderBadge(); renderNotifs(); }
function toggleNotif(){document.getElementById('notifDD').classList.toggle('show');}
function markAllRead(){notifs.forEach(n=>{n.read=true;if(!readIds.includes(String(n.id)))readIds.push(String(n.id));});unread=0;renderBadge();renderNotifs();localStorage.setItem('sk_read_notifs',JSON.stringify(readIds));}
function renderBadge(){const b=document.getElementById('notifBadge');b.style.display=unread>0?'block':'none';b.textContent=unread>9?'9+':unread;}
function renderNotifs(){
    const list=document.getElementById('notifList');
    if(!notifs.length){list.innerHTML='<div style="text-align:center;padding:24px;"><i class="fa-regular fa-bell-slash" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i><p style="font-size:.78rem;color:#94a3b8;font-weight:500;">No new requests</p></div>';return;}
    const tAgo=t=>{const s=Math.floor((Date.now()-new Date(t))/1000);if(s<60)return'Just now';if(s<3600)return`${Math.floor(s/60)}m ago`;if(s<86400)return`${Math.floor(s/3600)}h ago`;return`${Math.floor(s/86400)}d ago`;};
    list.innerHTML=[...notifs].sort((a,b)=>new Date(b.time)-new Date(a.time)).map(n=>`<div class="notif-item ${!n.read?'unread':''}" onclick="handleNotifClick(${n.id})"><div style="display:flex;align-items:flex-start;gap:9px;"><div style="width:28px;height:28px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-regular fa-clock" style="font-size:.7rem;color:#d97706;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.78rem;color:#0f172a;">${n.title}</p><p style="font-size:.68rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p><p style="font-size:.6rem;color:#94a3b8;margin-top:2px;">${tAgo(n.time)}</p></div>${!n.read?'<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:3px;"></span>':''}</div></div>`).join('');
}
function handleNotifClick(id){
    const n=notifs.find(x=>x.id==id); if(n&&!n.read){n.read=true;unread=Math.max(0,unread-1);if(!readIds.includes(String(id)))readIds.push(String(id));renderBadge();renderNotifs();localStorage.setItem('sk_read_notifs',JSON.stringify(readIds));}
    const res=resData.find(r=>r.id==id); if(res) openDetailModal({id:res.id,status:res.status,name:res.visitor_name||'Guest',email:res.user_email||'',resource:res.resource_name||'',pc:res.pc_number||'',date:res.reservation_date?new Date(res.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}):'—',start:res.start_time||'—',end:res.end_time||'—',purpose:res.purpose||'—',created:res.created_at?new Date(res.created_at).toLocaleString():'—'});
    document.getElementById('notifDD').classList.remove('show');
}
document.addEventListener('click',e=>{const dd=document.getElementById('notifDD'),bell=document.querySelector('.notif-bell-btn');if(bell&&!bell.contains(e.target)&&dd&&!dd.contains(e.target))dd.classList.remove('show');});

function checkNew(){ fetch('<?= base_url("sk/check-new-user-requests") ?>',{method:'POST',headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({last_check:lastCheck})}).then(r=>r.json()).then(data=>{(data.new_requests||[]).forEach(req=>{if(readIds.includes(String(req.id)))return;const n={id:req.id,title:'New Request',msg:`${req.visitor_name} requested ${req.resource_name}`,time:new Date().toISOString(),read:false};notifs.unshift(n);unread++;renderBadge();renderNotifs();showToast(n);});lastCheck=new Date().toISOString();}).catch(()=>{}); }
function showToast(n){ const c=document.getElementById('toastContainer'),id='toast-'+Date.now(),t=document.createElement('div');t.id=id;t.className='toast';t.innerHTML=`<div style="width:30px;height:30px;background:#fef3c7;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-regular fa-clock" style="font-size:.75rem;color:#d97706;"></i></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.78rem;color:#0f172a;">${n.title}</p><p style="font-size:.68rem;color:#94a3b8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${n.msg}</p></div><button onclick="this.closest('.toast').remove()" style="background:none;border:none;color:#94a3b8;cursor:pointer;"><i class="fa-solid fa-xmark" style="font-size:.7rem;"></i></button>`;c.appendChild(t);setTimeout(()=>{const el=document.getElementById(id);if(el)el.remove();},6000); }

initNotifs();
applyFilters();
setInterval(checkNew, 30000);
document.addEventListener('visibilitychange',()=>{if(!document.hidden)checkNew();});
</script>
</body>
</html>