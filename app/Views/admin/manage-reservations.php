<?php
$page    = $page    ?? 'manage-reservations';
$sk_name = session()->get('name') ?? session()->get('username') ?? 'SK Officer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Manage Reservations | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name"  content="<?= csrf_token() ?>">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; }

        .sidebar-card { background: white; border-radius: 32px; border: 1px solid #e2e8f0; height: calc(100vh - 48px); position: sticky; top: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav    { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item   { transition: all 0.18s; }
        .sidebar-item.active { background: #2563eb; color: white !important; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.35); }

        .mobile-nav-pill { position: fixed; bottom: calc(20px + env(safe-area-inset-bottom,0px)); left: 50%; transform: translateX(-50%); width: 92%; max-width: 600px; background: rgba(15,23,42,0.97); backdrop-filter: blur(12px); border-radius: 24px; padding: 6px; z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3); }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table  { width: 100%; border-collapse: collapse; min-width: 700px; }
        thead th { background: #f8fafc; font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8; padding: 0.9rem 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap; cursor: pointer; user-select: none; }
        thead th:hover { color: #16a34a; }
        thead th .sort-icon { opacity: 0.35; margin-left: 4px; font-size: 0.6rem; }
        thead th.sorted .sort-icon { opacity: 1; color: #16a34a; }
        td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; cursor: pointer; }
        tbody tr:hover td { background: #f0fdf4; }

        .res-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s; position: relative; overflow: hidden; }
        .res-card:hover, .res-card:active { border-color: #bbf7d0; box-shadow: 0 6px 20px -4px rgba(22,163,74,0.15); transform: translateY(-1px); }
        .res-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 0 4px 4px 0; }
        .res-card[data-status="pending"]::before   { background: #fbbf24; }
        .res-card[data-status="approved"]::before  { background: #22c55e; }
        .res-card[data-status="claimed"]::before   { background: #a855f7; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before  { background: #ef4444; }
        .res-card[data-status="unclaimed"]::before { background: #fb923c; }
        .res-card[data-status="expired"]::before   { background: #94a3b8; }

        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.75rem; border-radius: 10px; font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-approved  { background: #dcfce7; color: #166534; }
        .badge-declined, .badge-canceled { background: #fee2e2; color: #991b1b; }
        .badge-claimed   { background: #f3e8ff; color: #6b21a8; }
        .badge-expired   { background: #f1f5f9; color: #64748b; }
        .badge-unclaimed { background: #fff7ed; color: #c2410c; border: 1px dashed #fdba74; }

        .stat-card { background: white; border-radius: 20px; padding: 1.1rem 1.25rem; border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }
        .stat-card.ring { box-shadow: 0 0 0 2px #16a34a; }

        .qtab { display: inline-flex; align-items: center; gap: 6px; padding: 0.45rem 1rem; border-radius: 12px; font-size: 0.8rem; font-weight: 700; transition: all 0.18s; cursor: pointer; border: 1px solid #e2e8f0; white-space: nowrap; color: #64748b; background: white; }
        .qtab:hover  { border-color: #16a34a; color: #16a34a; }
        .qtab.active { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 4px 12px -2px rgba(37,99,235,0.3); }

        .field { background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 0.7rem 1rem 0.7rem 2.5rem; font-size: 0.875rem; font-family: inherit; color: #1e293b; transition: all 0.2s; width: 100%; }
        .field:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }

        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: center; justify-content: center; }
        .overlay.open { display: flex; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); }
        .modal-box { position: relative; margin: auto; background: white; border-radius: 32px; width: 94%; max-width: 520px; max-height: 92vh; overflow-y: auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both; }
        .modal-box.sm { max-width: 380px; }
        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 0; }

        @media (max-width: 639px) {
            .overlay#detailModal { align-items: flex-end; }
            .overlay#detailModal .modal-box { margin: 0; width: 100%; max-width: 100%; border-radius: 28px 28px 0 0; max-height: 92vh; animation: slideUp 0.28s cubic-bezier(0.34,1.2,0.64,1) both; }
            .sheet-handle { display: block; }
        }

        @keyframes popIn    { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }
        @keyframes slideUp  { from { opacity:0; transform:translateY(60px); }            to { opacity:1; transform:none; } }

        .drow  { display: flex; align-items: flex-start; gap: 12px; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9; }
        .drow:last-child { border-bottom: none; }
        .dicon { width: 36px; height: 36px; border-radius: 12px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
        .dlabel { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 3px; }
        .dvalue { font-size: 0.9rem; font-weight: 700; color: #1e293b; }

        .btn-confirm-approve { background: #16a34a; color: white; border: none; border-radius: 14px; padding: 0.85rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1; }
        .btn-confirm-approve:hover:not(:disabled) { background: #15803d; }
        .btn-confirm-decline { background: #ef4444; color: white; border: none; border-radius: 14px; padding: 0.85rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1; }
        .btn-confirm-decline:hover:not(:disabled) { background: #dc2626; }
        .btn-cancel { background: #f1f5f9; color: #475569; border: none; border-radius: 14px; padding: 0.85rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1; }
        .btn-cancel:hover { background: #e2e8f0; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }
        .ticket-section { border: 2px dashed #bbf7d0; border-radius: 24px; padding: 1.5rem; background: #f0fdf4; display: flex; flex-direction: column; align-items: center; }
        .empty-state { padding: 5rem 2rem; text-align: center; }
        .print-pill-yes { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.55rem; border-radius: 999px; font-size: 0.66rem; font-weight: 800; background: #f0fdf4; color: #15803d; white-space: nowrap; }
        .print-pill-no  { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.55rem; border-radius: 999px; font-size: 0.66rem; font-weight: 800; background: #f1f5f9; color: #64748b; white-space: nowrap; }
        #dPrintLogForm { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1.1rem 1.25rem; margin: 0 1.75rem 1rem; }
        #dPrintLogForm label { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; display: block; margin-bottom: 6px; }
        #dPrintLogForm input[type=number] { width: 100%; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.55rem 0.8rem; font-size: 0.875rem; font-family: inherit; color: #1e293b; }
        #dPrintLogForm input[type=number]:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }
        .btn-save-print { background: #16a34a; color: white; border: none; border-radius: 12px; padding: 0.65rem 1.25rem; font-size: 0.8rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; gap: 6px; font-family: inherit; white-space: nowrap; }
        .btn-save-print:hover:not(:disabled) { background: #15803d; }
        .btn-save-print:disabled { opacity: 0.6; cursor: not-allowed; }
        #printSaveMsg { font-size: 0.75rem; font-weight: 700; margin-top: 6px; min-height: 18px; }
        .card-empty { padding: 3rem 1.5rem; text-align: center; background: white; border-radius: 20px; border: 1px dashed #e2e8f0; }
        .unclaimed-banner { background: #fff7ed; border: 1.5px dashed #fdba74; border-radius: 16px; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 10px; margin: 0 1.75rem 1rem; }
        .unclaimed-banner .ub-icon { width: 34px; height: 34px; background: #fed7aa; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #c2410c; font-size: 0.85rem; flex-shrink: 0; }
    </style>
</head>
<body class="flex min-h-screen">

<?php
$navItems = [
    ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
    ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
    ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
    ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
    ['url' => '/admin/books',               'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
    ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
    ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
    ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
    ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
];

$processed = [];
foreach (($reservations ?? []) as $res) {
    $s          = strtolower($res['status'] ?? 'pending');
    $claimedVal = $res['claimed'] ?? false;
    $isClaimed  = in_array($claimedVal, [true, 1, 't', 'true', '1'], true);

    if ($isClaimed) {
        $s = 'claimed';
    } elseif ($s === 'approved') {
        $edt = strtotime(($res['reservation_date'] ?? '') . ' ' . ($res['end_time'] ?? '23:59'));
        if ($edt && $edt < time()) $s = 'unclaimed';
    } elseif ($s === 'pending') {
        $rdt = strtotime($res['reservation_date'] ?? '');
        if ($rdt && $rdt < strtotime('today')) $s = 'expired';
    }

    $res['_status']    = $s;
    $res['_unclaimed'] = ($s === 'unclaimed');
    $processed[] = $res;
}

$counts = [
    'all'       => count($processed),
    'pending'   => count(array_filter($processed, fn($r) => $r['_status'] === 'pending')),
    'approved'  => count(array_filter($processed, fn($r) => $r['_status'] === 'approved')),
    'claimed'   => count(array_filter($processed, fn($r) => $r['_status'] === 'claimed')),
    'declined'  => count(array_filter($processed, fn($r) => in_array($r['_status'], ['declined','canceled']))),
    'expired'   => count(array_filter($processed, fn($r) => $r['_status'] === 'expired')),
    'unclaimed' => count(array_filter($processed, fn($r) => $r['_status'] === 'unclaimed')),
];

$printLogMap = $printLogMap ?? [];
$statusIcons = [
    'pending'   => 'fa-clock',
    'approved'  => 'fa-circle-check',
    'claimed'   => 'fa-check-double',
    'declined'  => 'fa-xmark',
    'canceled'  => 'fa-ban',
    'expired'   => 'fa-hourglass-end',
    'unclaimed' => 'fa-ticket',
];
?>

<form id="approveForm" method="POST" action="<?= base_url('admin/approve') ?>" style="display:none">
    <?= csrf_field() ?><input type="hidden" name="id" id="approveId">
</form>
<form id="declineForm" method="POST" action="<?= base_url('admin/decline') ?>" style="display:none">
    <?= csrf_field() ?><input type="hidden" name="id" id="declineId">
</form>

<!-- DETAIL MODAL -->
<div id="detailModal" class="overlay" role="dialog" aria-modal="true">
    <div class="overlay-bg" onclick="closeModal('detail')"></div>
    <div class="modal-box">
        <div class="sheet-handle"></div>
        <div class="flex items-start justify-between px-7 pt-5 pb-3">
            <div>
                <p id="dId" class="text-xs font-black text-slate-400 font-mono mb-1"></p>
                <h3 class="text-xl font-black text-slate-900">Reservation Details</h3>
            </div>
            <button onclick="closeModal('detail')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0 mt-0.5">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="dStatusBar" class="mx-7 mb-3 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>

        <div id="dUnclaimedBanner" class="unclaimed-banner" style="display:none">
            <div class="ub-icon"><i class="fa-solid fa-ticket"></i></div>
            <div>
                <p class="font-black text-sm text-orange-700">Not Yet Claimed</p>
                <p class="text-xs text-orange-500 font-medium mt-0.5">Approved but the e-ticket was never scanned.</p>
            </div>
        </div>

        <div class="px-7 pb-2">
            <div class="drow"><div class="dicon"><i class="fa-solid fa-user"></i></div>
                <div><p class="dlabel">Requestor</p><p id="dName" class="dvalue"></p><p id="dEmail" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-desktop"></i></div>
                <div><p class="dlabel">Resource</p><p id="dResource" class="dvalue"></p><p id="dPc" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-calendar-day"></i></div>
                <div><p class="dlabel">Schedule</p><p id="dDate" class="dvalue"></p><p id="dTime" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-pen-to-square"></i></div>
                <div><p class="dlabel">Purpose</p><p id="dPurpose" class="dvalue"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-id-badge"></i></div>
                <div><p class="dlabel">Visitor Type</p><p id="dType" class="dvalue"></p></div>
            </div>
            <div class="drow" id="dApprovedByRow" style="display:none">
                <div class="dicon" id="dApprovedByIcon"><i class="fa-solid fa-user-check"></i></div>
                <div>
                    <p class="dlabel" id="dApprovedByLabel">Approved By</p>
                    <p id="dApprovedByName" class="dvalue"></p>
                    <p id="dApprovedByEmail" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                    <p id="dApprovedAt" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                </div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-regular fa-clock"></i></div>
                <div><p class="dlabel">Submitted</p><p id="dCreated" class="dvalue"></p></div>
            </div>
        </div>

        <div id="dQr" class="mx-7 mb-4 ticket-section" style="display:none">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">E-Ticket</p>
            <canvas id="qrCanvas" class="rounded-xl"></canvas>
            <p id="dTicketCode" class="text-xs text-slate-400 font-mono mt-2 text-center break-all px-2"></p>
            <button onclick="downloadTicket()" class="mt-3 flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition">
                <i class="fa-solid fa-download text-xs"></i> Download E-Ticket
            </button>
        </div>
        <div id="dClaimed" class="mx-7 mb-4 bg-purple-50 border-2 border-dashed border-purple-200 rounded-2xl p-5 text-center" style="display:none">
            <i class="fa-solid fa-check-double text-2xl text-purple-500 mb-1 block"></i>
            <p class="font-black text-purple-700 text-sm">Ticket Already Claimed</p>
            <p class="text-xs text-purple-400 mt-0.5">This reservation has been used.</p>
        </div>
        <div id="dPrintLog" class="mx-7 mb-3 rounded-2xl px-4 py-3 border border-slate-100 bg-slate-50 flex items-center gap-3" style="display:none">
            <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-print text-green-600 text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Print Log</p>
                <p id="dPrintText" class="text-sm font-bold text-slate-700"></p>
            </div>
            <span id="dPrintBadge" class="text-[10px] font-black px-2.5 py-1 rounded-full flex-shrink-0"></span>
        </div>
        <div id="dPrintLogForm">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa-solid fa-print text-green-500"></i> Log Print for this Reservation
            </p>
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <label>Pages Printed <span class="text-slate-300 font-normal normal-case tracking-normal">(0 = not printed)</span></label>
                    <input type="number" id="printPagesInput" min="0" max="999" value="0" placeholder="0">
                </div>
                <button id="savePrintBtn" class="btn-save-print" onclick="savePrintLog()">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Save
                </button>
            </div>
            <p id="printSaveMsg" class="text-slate-400"></p>
        </div>
        <div id="dActions" class="px-7 py-5 border-t border-slate-100 flex gap-3 flex-wrap mt-2"></div>
    </div>
</div>

<!-- Approve confirm modal -->
<div id="approveModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('approve')"></div>
    <div class="modal-box sm">
        <div class="px-7 pt-7 pb-5 text-center">
            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-circle-check"></i></div>
            <h3 class="text-xl font-black text-slate-900">Approve Reservation?</h3>
            <p class="text-slate-400 text-sm mt-1 font-medium">This will confirm the reservation.</p>
            <p id="approveConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
        </div>
        <div class="px-7 pb-7 flex gap-3">
            <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
            <button id="confirmApproveBtn" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
        </div>
    </div>
</div>

<!-- Decline confirm modal -->
<div id="declineModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('decline')"></div>
    <div class="modal-box sm">
        <div class="px-7 pt-7 pb-5 text-center">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <h3 class="text-xl font-black text-slate-900">Decline Reservation?</h3>
            <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
            <p id="declineConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
        </div>
        <div class="px-7 pb-7 flex gap-3">
            <button class="btn-cancel" onclick="closeModal('decline')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
            <button id="confirmDeclineBtn" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
            <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-1">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
            ?>
                <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                    <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                    <?= $item['label'] ?>
                    <?php if ($item['key'] === 'manage-reservations' && ($counts['pending'] ?? 0) > 0): ?>
                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $counts['pending'] ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
            </a>
        </div>
    </div>
</aside>

<!-- MOBILE NAV -->
<nav class="lg:hidden mobile-nav-pill">
    <div class="mobile-scroll-container text-white px-2">
        <?php foreach ($navItems as $item):
            $btnClass = ($page == $item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
        ?>
            <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
            <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
        </a>
    </div>
</nav>

<!-- MAIN -->
<main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 fade-up">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Admin Portal</p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Manage Reservations</h2>
            <p class="text-slate-400 font-medium text-sm mt-0.5">
                <?= $counts['all'] ?> total record<?= $counts['all'] != 1 ? 's' : '' ?>
                <?php if ($counts['unclaimed'] > 0): ?>
                    · <span class="text-orange-500 font-bold"><?= $counts['unclaimed'] ?> unclaimed</span>
                <?php endif; ?>
            </p>
        </div>
        <button onclick="exportCSV()" class="flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm flex-shrink-0">
            <i class="fa-solid fa-file-csv"></i> Export CSV
        </button>
    </header>

    <!-- Stat cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <?php foreach ([
            ['Total',     $counts['all'],       'border-green-400',   'text-slate-700',   'all'],
            ['Pending',   $counts['pending'],   'border-amber-400',   'text-amber-600',   'pending'],
            ['Approved',  $counts['approved'],  'border-emerald-400', 'text-emerald-600', 'approved'],
            ['Claimed',   $counts['claimed'],   'border-purple-400',  'text-purple-600',  'claimed'],
            ['Declined',  $counts['declined'],  'border-rose-400',    'text-rose-600',    'declined'],
            ['Unclaimed', $counts['unclaimed'], 'border-orange-400',  'text-orange-600',  'unclaimed'],
        ] as [$lbl, $val, $border, $color, $key]): ?>
            <div class="stat-card <?= $border ?>" onclick="filterByStatus('<?= $key ?>')" data-filter="<?= $key ?>">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?= $lbl ?></p>
                <p class="text-2xl font-black <?= $color ?>"><?= $val ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-5 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
            <i class="fa-solid fa-circle-check text-green-500"></i><?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-5 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Filter bar -->
    <div class="bg-white border border-slate-200 rounded-[28px] p-4 lg:p-5 mb-4 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="field" oninput="applyFilters()">
            </div>
            <div class="relative sm:w-44">
                <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input id="dateInput" type="date" class="field" onchange="applyFilters()">
            </div>
            <button onclick="clearFilters()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center gap-2 flex-shrink-0">
                <i class="fa-solid fa-rotate-left text-xs"></i> Reset
            </button>
        </div>
        <div class="flex gap-2 mt-3 overflow-x-auto pb-0.5">
            <button class="qtab active" data-tab="all"       onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group text-xs"></i> All <span class="text-[9px] font-black opacity-70"><?= $counts['all'] ?></span></button>
            <button class="qtab" data-tab="pending"          onclick="setTab(this,'pending')"><i class="fa-solid fa-clock text-xs"></i> Pending <?php if ($counts['pending'] > 0): ?><span class="bg-amber-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $counts['pending'] ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="approved"         onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check text-xs"></i> Approved</button>
            <button class="qtab" data-tab="unclaimed"        onclick="setTab(this,'unclaimed')"><i class="fa-solid fa-ticket text-xs"></i> Unclaimed<?php if ($counts['unclaimed'] > 0): ?><span class="bg-orange-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $counts['unclaimed'] ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="claimed"          onclick="setTab(this,'claimed')"><i class="fa-solid fa-check-double text-xs"></i> Claimed</button>
            <button class="qtab" data-tab="declined"         onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark text-xs"></i> Declined</button>
            <button class="qtab" data-tab="expired"          onclick="setTab(this,'expired')"><i class="fa-solid fa-hourglass-end text-xs"></i> Expired</button>
        </div>
    </div>

    <div class="px-1 mb-3"><p id="resultCount" class="text-xs font-bold text-slate-400"></p></div>

    <!-- DESKTOP TABLE -->
    <div id="desktopTableWrap" class="hidden md:block bg-white border border-slate-200 rounded-[28px] shadow-sm overflow-hidden">
        <div class="table-wrap">
            <table id="resTable">
                <thead>
                    <tr>
                        <th style="width:52px">ID</th>
                        <th onclick="sortTable(1)">User <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(2)">Resource <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(3)">Schedule <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Purpose</th>
                        <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th onclick="sortTable(6)">Approved By <i class="fa-solid fa-sort sort-icon"></i></th>
                        <th>Print</th>
                        <th class="text-right" style="width:140px">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($processed)): ?>
                        <tr><td colspan="9"><div class="empty-state">
                            <i class="fa-solid fa-calendar-xmark text-5xl text-slate-200 mb-4 block"></i>
                            <p class="font-black text-slate-400 text-lg">No reservations yet</p>
                        </div></td></tr>
                    <?php else: ?>
                        <?php foreach ($processed as $res):
                            $s           = $res['_status'];
                            $isUnclaimed = $res['_unclaimed'];
                            $name        = htmlspecialchars($res['visitor_name']  ?? $res['full_name']    ?? 'Guest');
                            $email       = htmlspecialchars($res['visitor_email'] ?? $res['user_email']   ?? '');
                            $resource    = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                            $pc          = htmlspecialchars($res['pc_number']     ?? $res['pc_numbers']   ?? '');
                            $rawDate     = $res['reservation_date'] ?? '';
                            $date        = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                            $start       = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                            $end         = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                            $purpose     = htmlspecialchars($res['purpose']      ?? '—');
                            $type        = htmlspecialchars($res['visitor_type'] ?? '—');
                            $created     = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                            $code        = $res['e_ticket_code'] ?? ('RES-' . $res['id'] . '-' . $rawDate);
                            $icon        = $statusIcons[$s] ?? 'fa-circle';
                            $approverName  = htmlspecialchars($res['approver_name']  ?? $res['approved_by_name']  ?? '');
                            $approverEmail = htmlspecialchars($res['approver_email'] ?? $res['approved_by_email'] ?? '');
                            $approvedAt    = !empty($res['updated_at']) && in_array($s, ['approved','claimed','declined','expired','unclaimed'])
                                             ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                            $pl          = $printLogMap[(int)$res['id']] ?? null;
                            $plPrinted   = $pl !== null ? (bool)$pl['printed'] : null;
                            $plPages     = $pl ? (int)($pl['pages'] ?? 0) : 0;
                            $plAt        = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                            $isClaimed   = in_array($res['claimed'] ?? false, [true,1,'t','true','1'], true);
                            $mdata       = json_encode([
                                'id'=>$res['id'], 'status'=>$s, 'name'=>$name, 'email'=>$email,
                                'resource'=>$resource, 'pc'=>$pc, 'date'=>$date, 'rawDate'=>$rawDate,
                                'start'=>$start, 'end'=>$end, 'purpose'=>$purpose, 'type'=>$type,
                                'created'=>$created, 'code'=>$code,
                                'claimed'=>$isClaimed, 'unclaimed'=>$isUnclaimed,
                                'approverName'=>$approverName, 'approverEmail'=>$approverEmail, 'approvedAt'=>$approvedAt,
                                'plPrinted'=>$plPrinted, 'plPages'=>$plPages, 'plAt'=>$plAt,
                            ]);
                        ?>
                        <tr class="res-row"
                            data-id="<?= $res['id'] ?>"
                            data-status="<?= $s ?>"
                            data-unclaimed="<?= $isUnclaimed ? '1' : '0' ?>"
                            data-search="<?= strtolower("$name $resource $purpose $email $approverName") ?>"
                            data-date="<?= $rawDate ?>"
                            data-pl-printed="<?= $plPrinted === null ? '' : ($plPrinted ? 'Yes' : 'No') ?>"
                            data-pl-pages="<?= $plPrinted ? $plPages : '' ?>"
                            data-pl-at="<?= htmlspecialchars($plAt, ENT_QUOTES) ?>"
                            onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                            <td><span class="text-xs font-black text-slate-400 font-mono">#<?= $res['id'] ?></span></td>
                            <td>
                                <p class="font-bold text-sm text-slate-800 leading-tight"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[160px]"><?= $email ?></p><?php endif; ?>
                            </td>
                            <td>
                                <p class="font-bold text-sm text-slate-800 leading-tight"><?= $resource ?></p>
                                <?php if ($pc): ?><div class="flex items-center gap-1 mt-0.5"><i class="fa-solid fa-desktop text-[9px] text-slate-400"></i><span class="text-[11px] text-slate-500 font-semibold"><?= $pc ?></span></div><?php endif; ?>
                            </td>
                            <td>
                                <p class="text-sm font-bold text-slate-700"><?= $date ?></p>
                                <p class="text-[11px] text-green-500 font-semibold mt-0.5"><?= $start ?> – <?= $end ?></p>
                            </td>
                            <td><span class="text-sm text-slate-500 font-medium" style="display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px"><?= $purpose ?></span></td>
                            <td><span class="badge badge-<?= $s ?>"><i class="fa-solid <?= $icon ?> text-[9px]"></i><?= ucfirst($s) ?></span></td>
                            <td onclick="event.stopPropagation()">
                                <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired','unclaimed'])): ?>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[9px] font-black flex-shrink-0 <?= $s === 'declined' ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-700' ?>"><?= mb_strtoupper(mb_substr($approverName, 0, 1)) ?></div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-700 truncate max-w-[110px]"><?= $approverName ?></p>
                                            <?php if ($approvedAt): ?><p class="text-[10px] text-slate-400 font-medium truncate"><?= $approvedAt ?></p><?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?><span class="text-[10px] text-slate-300 font-bold">—</span><?php endif; ?>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> <?= $plPages ?>pg</span>
                                <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>
                                <?php else: ?><span class="text-[10px] text-slate-300 font-bold">—</span><?php endif; ?>
                            </td>
                            <td class="text-right" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-end gap-1.5">
                                    <?php if ($s === 'pending'): ?>
                                        <button onclick="triggerApprove(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="h-8 px-3 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center gap-1.5"><i class="fa-solid fa-check text-[11px]"></i> Approve</button>
                                        <button onclick="triggerDecline(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="h-8 px-2 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center"><i class="fa-solid fa-xmark text-[11px]"></i></button>
                                    <?php elseif ($s === 'unclaimed'): ?>
                                        <span class="text-[11px] text-orange-500 font-black flex items-center gap-1"><i class="fa-solid fa-ticket"></i> Unclaimed</span>
                                    <?php elseif ($s === 'approved'): ?>
                                        <span class="text-[11px] text-emerald-500 font-black flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Approved</span>
                                    <?php elseif ($s === 'claimed'): ?>
                                        <span class="text-[11px] text-purple-500 font-black flex items-center gap-1"><i class="fa-solid fa-check-double"></i> Claimed</span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-300 font-semibold italic">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between">
            <p id="tableFooter" class="text-xs font-bold text-slate-400"></p>
            <p class="text-xs text-slate-300 font-semibold hidden sm:block">Click any row to preview · Export CSV exports current filter</p>
        </div>
    </div>

    <!-- MOBILE CARDS -->
    <div id="mobileCardList" class="md:hidden space-y-3">
        <?php if (empty($processed)): ?>
            <div class="card-empty">
                <i class="fa-solid fa-calendar-xmark text-4xl text-slate-200 mb-3 block"></i>
                <p class="font-black text-slate-400">No reservations yet</p>
            </div>
        <?php else: ?>
            <?php foreach ($processed as $res):
                $s           = $res['_status'];
                $isUnclaimed = $res['_unclaimed'];
                $name        = htmlspecialchars($res['visitor_name']  ?? $res['full_name']    ?? 'Guest');
                $email       = htmlspecialchars($res['visitor_email'] ?? $res['user_email']   ?? '');
                $resource    = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                $pc          = htmlspecialchars($res['pc_number']     ?? $res['pc_numbers']   ?? '');
                $rawDate     = $res['reservation_date'] ?? '';
                $date        = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                $start       = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                $end         = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                $purpose     = htmlspecialchars($res['purpose']      ?? '—');
                $type        = htmlspecialchars($res['visitor_type'] ?? '—');
                $created     = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
                $code        = $res['e_ticket_code'] ?? ('RES-' . $res['id'] . '-' . $rawDate);
                $icon        = $statusIcons[$s] ?? 'fa-circle';
                $approverName  = htmlspecialchars($res['approver_name']  ?? $res['approved_by_name']  ?? '');
                $approverEmail = htmlspecialchars($res['approver_email'] ?? $res['approved_by_email'] ?? '');
                $approvedAt    = !empty($res['updated_at']) && in_array($s, ['approved','claimed','declined','expired','unclaimed'])
                                 ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                $pl          = $printLogMap[(int)$res['id']] ?? null;
                $plPrinted   = $pl !== null ? (bool)$pl['printed'] : null;
                $plPages     = $pl ? (int)($pl['pages'] ?? 0) : 0;
                $plAt        = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                $isClaimed   = in_array($res['claimed'] ?? false, [true,1,'t','true','1'], true);
                $mdata       = json_encode([
                    'id'=>$res['id'], 'status'=>$s, 'name'=>$name, 'email'=>$email,
                    'resource'=>$resource, 'pc'=>$pc, 'date'=>$date, 'rawDate'=>$rawDate,
                    'start'=>$start, 'end'=>$end, 'purpose'=>$purpose, 'type'=>$type,
                    'created'=>$created, 'code'=>$code,
                    'claimed'=>$isClaimed, 'unclaimed'=>$isUnclaimed,
                    'approverName'=>$approverName, 'approverEmail'=>$approverEmail, 'approvedAt'=>$approvedAt,
                    'plPrinted'=>$plPrinted, 'plPages'=>$plPages, 'plAt'=>$plAt,
                ]);
                $avatarBg = [
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'approved'  => 'bg-emerald-100 text-emerald-700',
                    'claimed'   => 'bg-purple-100 text-purple-700',
                    'declined'  => 'bg-red-100 text-red-600',
                    'canceled'  => 'bg-red-100 text-red-600',
                    'expired'   => 'bg-slate-100 text-slate-500',
                    'unclaimed' => 'bg-orange-100 text-orange-700',
                ][$s] ?? 'bg-slate-100 text-slate-500';
            ?>
                <div class="res-card"
                     data-id="<?= $res['id'] ?>"
                     data-status="<?= $s ?>"
                     data-unclaimed="<?= $isUnclaimed ? '1' : '0' ?>"
                     data-search="<?= strtolower("$name $resource $purpose $email $approverName") ?>"
                     data-date="<?= $rawDate ?>"
                     data-pl-printed="<?= $plPrinted === null ? '' : ($plPrinted ? 'Yes' : 'No') ?>"
                     data-pl-pages="<?= $plPrinted ? $plPages : '' ?>"
                     data-pl-at="<?= htmlspecialchars($plAt, ENT_QUOTES) ?>"
                     onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-2xl <?= $avatarBg ?> flex items-center justify-center font-black text-sm flex-shrink-0">
                            <?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-slate-800 truncate leading-tight"><?= $name ?></p>
                            <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                        </div>
                        <span class="badge badge-<?= $s ?> flex-shrink-0"><i class="fa-solid <?= $icon ?> text-[9px]"></i><?= ucfirst($s) ?></span>
                    </div>
                    <div class="flex items-start gap-2 mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-desktop text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs font-bold text-slate-700 truncate"><?= $resource ?><?= $pc ? ' · ' . $pc : '' ?></p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px] text-slate-400 flex-shrink-0"></i>
                                <p class="text-xs text-slate-500 font-semibold"><?= $date ?></p>
                                <span class="text-[10px] text-green-500 font-bold"><?= $start ?> – <?= $end ?></span>
                            </div>
                        </div>
                        <!-- print pill wrapper — targeted by JS -->
                        <div class="card-print-pill flex-shrink-0">
                            <?php if ($plPrinted === true): ?>
                                <span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> <?= $plPages ?>pg</span>
                            <?php elseif ($plPrinted === false): ?>
                                <span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium truncate mb-3"><?= $purpose ?></p>
                    <div class="flex items-center justify-between gap-2 pt-2.5 border-t border-slate-100">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired','unclaimed'])): ?>
                                <div class="w-5 h-5 rounded-md flex items-center justify-center text-[8px] font-black flex-shrink-0 <?= $s === 'declined' ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-700' ?>"><?= mb_strtoupper(mb_substr($approverName, 0, 1)) ?></div>
                                <p class="text-[10px] text-slate-500 font-semibold truncate"><?= $s === 'declined' ? 'Declined' : 'Approved' ?> by <?= $approverName ?></p>
                            <?php else: ?>
                                <p class="text-[10px] text-slate-300 font-semibold">#<?= $res['id'] ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($s === 'pending'): ?>
                            <div class="flex items-center gap-1.5 flex-shrink-0" onclick="event.stopPropagation()">
                                <button onclick="triggerApprove(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="h-7 px-2.5 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center gap-1"><i class="fa-solid fa-check text-[10px]"></i> Approve</button>
                                <button onclick="triggerDecline(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="h-7 px-2 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center"><i class="fa-solid fa-xmark text-[10px]"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="mobileEmpty" class="md:hidden card-empty" style="display:none">
        <i class="fa-solid fa-filter-circle-xmark text-4xl text-slate-200 mb-3 block"></i>
        <p class="font-black text-slate-400">No reservations match</p>
        <p class="text-slate-300 text-sm mt-1">Try adjusting your search or filters.</p>
    </div>

</main>

<script>
const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
const allCards     = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
let   curTab       = 'all';
let   approveTargetId = null, declineTargetId = null;

// ── CSRF: read from meta tags, refreshed after every successful POST ───────
let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
let csrfName  = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content')  ?? 'csrf_token';

// Call this after every successful JSON response that returns new CSRF values
function refreshCsrf(data) {
    if (data.csrf_hash && data.csrf_token) {
        csrfToken = data.csrf_hash;
        csrfName  = data.csrf_token;
        // Also update the meta tags so the approve/decline forms stay fresh
        document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', csrfToken);
        document.querySelector('meta[name="csrf-name"]')?.setAttribute('content', csrfName);
    }
}

const printLogMap = {};
<?php foreach ($printLogMap as $resId => $pl): ?>
printLogMap[<?= (int)$resId ?>] = {
    printed: <?= isset($pl['printed']) ? (in_array($pl['printed'],[true,1,'t','true','1'],true) ? 'true' : 'false') : 'false' ?>,
    pages:   <?= (int)($pl['pages'] ?? 0) ?>,
    at:      "<?= !empty($pl['printed_at']) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '' ?>"
};
<?php endforeach; ?>

let _currentReservationId = null;

// ── FIXED: refreshes CSRF token after save so desktop works without reload ─
async function savePrintLog() {
    const rid   = _currentReservationId;
    const pages = parseInt(document.getElementById('printPagesInput').value, 10) || 0;
    const btn   = document.getElementById('savePrintBtn');
    const msg   = document.getElementById('printSaveMsg');
    if (!rid) { msg.textContent = 'No reservation selected.'; msg.style.color = '#ef4444'; return; }
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Saving…';
    msg.textContent = ''; msg.style.color = '';

    const body = new FormData();
    body.append(csrfName, csrfToken);   // always use current (refreshed) token
    body.append('reservation_id', rid);
    body.append('printed', pages > 0 ? 1 : 0);
    body.append('pages', pages);

    try {
        const res  = await fetch('<?= base_url('admin/log-print') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body
        });

        // ── Parse response — handle both JSON and unexpected HTML (500/404) ──
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); }
        catch { throw new Error(`Server error (${res.status})`); }

        if (data.ok) {
            // ── Refresh CSRF token so next save works without page reload ──
            refreshCsrf(data);

            const now = new Date();
            const fmt = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
                      + ' · '
                      + now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            printLogMap[rid] = { printed: pages > 0, pages, at: fmt };
            refreshPrintLogStrip(rid);
            refreshBothPrintCells(rid, pages);   // updates table row + mobile card
            msg.textContent = pages > 0
                ? `✓ Saved — ${pages} page${pages !== 1 ? 's' : ''} printed`
                : '✓ Saved — no printing logged';
            msg.style.color = '#16a34a';
            btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i> Saved';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save';
            }, 2000);
        } else {
            throw new Error(data.error ?? 'Unknown error');
        }
    } catch (err) {
        msg.textContent = '✗ Failed: ' + err.message;
        msg.style.color = '#ef4444';
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save';
    }
}

function refreshPrintLogStrip(rid) {
    const plog  = printLogMap[rid];
    const logEl = document.getElementById('dPrintLog');
    if (!plog) { logEl.style.display = 'none'; return; }
    logEl.style.display = 'flex';
    const logText  = document.getElementById('dPrintText');
    const logBadge = document.getElementById('dPrintBadge');
    if (plog.printed) {
        logText.textContent  = `Printed ${plog.pages} page${plog.pages !== 1 ? 's' : ''}` + (plog.at ? ` · ${plog.at}` : '');
        logBadge.textContent = `${plog.pages}pg`;
        logBadge.className   = 'text-[10px] font-black px-2.5 py-1 rounded-full bg-green-100 text-green-700';
    } else {
        logText.textContent  = 'No printing during this session';
        logBadge.textContent = 'No print';
        logBadge.className   = 'text-[10px] font-black px-2.5 py-1 rounded-full bg-slate-200 text-slate-500';
    }
}

// ── FIXED: now updates BOTH desktop table rows AND mobile cards instantly ──
function refreshBothPrintCells(rid, pages) {
    // Desktop table — column index 7 = Print column
    allTableRows.forEach(row => {
        if (row.dataset.id == rid) {
            const cell = row.cells[7];
            if (pages > 0) {
                cell.innerHTML      = `<span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> ${pages}pg</span>`;
                row.dataset.plPrinted = 'Yes';
                row.dataset.plPages   = pages;
            } else {
                cell.innerHTML      = `<span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>`;
                row.dataset.plPrinted = 'No';
                row.dataset.plPages   = '';
            }
        }
    });

    // Mobile cards — update the .card-print-pill wrapper
    allCards.forEach(card => {
        if (card.dataset.id == rid) {
            const wrapper = card.querySelector('.card-print-pill');
            if (wrapper) {
                if (pages > 0) {
                    wrapper.innerHTML = `<span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> ${pages}pg</span>`;
                } else {
                    wrapper.innerHTML = `<span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>`;
                }
            }
            card.dataset.plPrinted = pages > 0 ? 'Yes' : 'No';
            card.dataset.plPages   = pages > 0 ? pages : '';
        }
    });
}

function exportCSV() {
    const visibleRows = allTableRows.filter(r => r.style.display !== 'none');
    const headers = ['ID','User Name','Email','Resource Name','PC Number','Date','Start Time','End Time','Purpose','Visitor Type','Status','Approved By','Approved At','Printed','Pages Printed','Submitted At'];
    const escape  = v => { const s = String(v ?? ''); return s.includes(',') || s.includes('"') || s.includes('\n') ? '"' + s.replace(/"/g, '""') + '"' : s; };
    const lines   = [headers.map(escape).join(',')];
    visibleRows.forEach(row => {
        try {
            const d = JSON.parse(row.getAttribute('onclick').replace(/^openDetail\(/, '').replace(/\)$/, ''));
            lines.push([d.id??'',d.name??'',d.email??'',d.resource??'',d.pc??'',d.date??'',d.start??'',d.end??'',d.purpose??'',d.type??'',d.status??'',d.approverName??'',d.approvedAt??'',row.dataset.plPrinted??'',row.dataset.plPages??'',d.created??''].map(escape).join(','));
        } catch (e) {}
    });
    const blob = new Blob([lines.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = `admin-reservations-${new Date().toISOString().slice(0, 10)}.csv`; a.click();
    URL.revokeObjectURL(url);
}

function setTab(btn, tab) { document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active')); btn.classList.add('active'); curTab = tab; syncCards(tab); applyFilters(); }
function filterByStatus(tab) { curTab = tab; document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab)); syncCards(tab); applyFilters(); }
function syncCards(tab) { document.querySelectorAll('[data-filter]').forEach(c => c.classList.toggle('ring', c.dataset.filter === tab)); }

function applyFilters() {
    const q    = document.getElementById('searchInput').value.toLowerCase().trim();
    const date = document.getElementById('dateInput').value;
    const matchesFilters = el => {
        let matchTab;
        if      (curTab === 'all')      matchTab = true;
        else if (curTab === 'declined') matchTab = ['declined','canceled'].includes(el.dataset.status);
        else                            matchTab = el.dataset.status === curTab;
        return matchTab && (!q || el.dataset.search.includes(q)) && (!date || el.dataset.date === date);
    };
    let n = 0;
    allTableRows.forEach(row => { const show = matchesFilters(row); row.style.display = show ? '' : 'none'; if (show) n++; });
    let cardVisible = 0;
    allCards.forEach(card => { const show = matchesFilters(card); card.style.display = show ? '' : 'none'; if (show) cardVisible++; });
    if (allCards.length > 0) document.getElementById('mobileEmpty').style.display = cardVisible === 0 ? 'block' : 'none';
    const total = allTableRows.length;
    document.getElementById('resultCount').textContent = `Showing ${n} of ${total} reservation${total !== 1 ? 's' : ''}`;
    document.getElementById('tableFooter').textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('dateInput').value   = '';
    curTab = 'all';
    document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === 'all'));
    syncCards('all');
    applyFilters();
}

let sortDir = {};
function sortTable(col) {
    sortDir[col] = !sortDir[col];
    const tbody = document.getElementById('tableBody');
    Array.from(tbody.querySelectorAll('.res-row')).sort((a, b) => {
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

const STATUS_META = {
    pending:   { icon: 'fa-clock',         bg: '#fef3c7', color: '#92400e', label: 'Pending — Awaiting approval' },
    approved:  { icon: 'fa-circle-check',  bg: '#dcfce7', color: '#166534', label: 'Approved' },
    claimed:   { icon: 'fa-check-double',  bg: '#f3e8ff', color: '#6b21a8', label: 'Claimed — Ticket used' },
    declined:  { icon: 'fa-xmark-circle',  bg: '#fee2e2', color: '#991b1b', label: 'Declined' },
    canceled:  { icon: 'fa-ban',           bg: '#fee2e2', color: '#991b1b', label: 'Cancelled' },
    expired:   { icon: 'fa-hourglass-end', bg: '#f1f5f9', color: '#475569', label: 'Expired — Was never approved' },
    unclaimed: { icon: 'fa-ticket',        bg: '#fff7ed', color: '#c2410c', label: 'Unclaimed — Approved but did not show up' },
};

function openDetail(d) {
    _currentReservationId = d.id;
    const plog = printLogMap[d.id];
    document.getElementById('printPagesInput').value = plog ? (plog.printed ? plog.pages : 0) : 0;
    document.getElementById('printSaveMsg').textContent = '';
    const saveBtn = document.getElementById('savePrintBtn');
    saveBtn.disabled = false;
    saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save';

    const m = STATUS_META[d.status] || STATUS_META.pending;
    document.getElementById('dId').textContent       = 'Reservation #' + d.id;
    document.getElementById('dName').textContent     = d.name;
    document.getElementById('dEmail').textContent    = d.email;
    document.getElementById('dResource').textContent = d.resource;
    document.getElementById('dPc').textContent       = d.pc ? 'PC: ' + d.pc : '';
    document.getElementById('dDate').textContent     = d.date;
    document.getElementById('dTime').textContent     = d.start + ' – ' + d.end;
    document.getElementById('dPurpose').textContent  = d.purpose;
    document.getElementById('dType').textContent     = d.type;
    document.getElementById('dCreated').textContent  = d.created;

    const approverRow = document.getElementById('dApprovedByRow');
    if (d.approverName && ['approved','claimed','declined','expired','unclaimed'].includes(d.status)) {
        approverRow.style.display = 'flex';
        const isDeclined = d.status === 'declined';
        document.getElementById('dApprovedByLabel').textContent = isDeclined ? 'Declined By' : 'Approved By';
        document.getElementById('dApprovedByIcon').className    = `dicon ${isDeclined ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-600'}`;
        document.getElementById('dApprovedByIcon').innerHTML    = `<i class="fa-solid ${isDeclined ? 'fa-user-xmark' : 'fa-user-check'}"></i>`;
        document.getElementById('dApprovedByName').textContent  = d.approverName;
        document.getElementById('dApprovedByEmail').textContent = d.approverEmail || '';
        document.getElementById('dApprovedAt').textContent      = d.approvedAt ? `on ${d.approvedAt}` : '';
    } else { approverRow.style.display = 'none'; }

    const bar = document.getElementById('dStatusBar');
    bar.style.background = m.bg; bar.style.color = m.color;
    bar.innerHTML = `<i class="fa-solid ${m.icon}"></i> <span>${m.label}</span>`;

    document.getElementById('dUnclaimedBanner').style.display = d.unclaimed ? 'flex' : 'none';

    const qrSec = document.getElementById('dQr'), clSec = document.getElementById('dClaimed');
    if (d.claimed || d.status === 'claimed') {
        qrSec.style.display = 'none'; clSec.style.display = 'block';
    } else if (d.status === 'approved' || d.status === 'unclaimed') {
        clSec.style.display = 'none'; qrSec.style.display = 'flex';
        QRCode.toCanvas(document.getElementById('qrCanvas'), d.code, { width: 150, margin: 1, color: { dark: '#1e293b', light: '#ffffff' } });
        document.getElementById('dTicketCode').textContent = d.code;
    } else { qrSec.style.display = 'none'; clSec.style.display = 'none'; }

    refreshPrintLogStrip(d.id);

    const acts = document.getElementById('dActions');
    if (d.status === 'pending') {
        acts.innerHTML = `<button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-approve flex-1"><i class="fa-solid fa-check"></i> Approve</button><button onclick="triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-decline flex-1"><i class="fa-solid fa-xmark"></i> Decline</button>`;
    } else {
        acts.innerHTML = `<button onclick="closeModal('detail')" class="btn-cancel w-full"><i class="fa-solid fa-xmark text-xs"></i> Close</button>`;
    }
    document.getElementById('detailModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function downloadTicket() {
    const canvas = document.getElementById('qrCanvas'), code = document.getElementById('dTicketCode').textContent;
    const link = document.createElement('a');
    link.download = `E-Ticket-${code}.png`; link.href = canvas.toDataURL('image/png'); link.click();
}

function triggerApprove(id, name) { approveTargetId = id; document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : ''; openModal('approve'); }
function triggerDecline(id, name) { declineTargetId = id; document.getElementById('declineConfirmName').textContent = name ? `"${name}"` : ''; openModal('decline'); }

document.getElementById('confirmApproveBtn').addEventListener('click', function () {
    if (!approveTargetId) return;
    this.disabled = true; this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
    document.getElementById('approveId').value = approveTargetId;
    document.getElementById('approveForm').submit();
});
document.getElementById('confirmDeclineBtn').addEventListener('click', function () {
    if (!declineTargetId) return;
    this.disabled = true; this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Declining…';
    document.getElementById('declineId').value = declineTargetId;
    document.getElementById('declineForm').submit();
});

const modalIds = { detail: 'detailModal', approve: 'approveModal', decline: 'declineModal' };
function openModal(key)  { const el = document.getElementById(modalIds[key]); if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; } }
function closeModal(key) {
    const el = document.getElementById(modalIds[key]); if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
    if (key === 'detail')  _currentReservationId = null;
    if (key === 'approve') { const b = document.getElementById('confirmApproveBtn'); b.disabled = false; b.innerHTML = '<i class="fa-solid fa-check"></i> Approve'; }
    if (key === 'decline') { const b = document.getElementById('confirmDeclineBtn'); b.disabled = false; b.innerHTML = '<i class="fa-solid fa-xmark"></i> Decline'; }
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal('detail'); closeModal('approve'); closeModal('decline'); } });

applyFilters();
</script>
</body>
</html>