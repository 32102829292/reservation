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
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; overflow-x: hidden; }

        .sidebar-card { background: white; border-radius: 32px; border: 1px solid #e2e8f0; height: calc(100vh - 48px); position: sticky; top: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; overflow: hidden; width: 100%; }
        .sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
        .sidebar-nav    { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
        .sidebar-item   { transition: all 0.18s; }
        .sidebar-item.active { background: #2563eb; color: white !important; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.35); }

        .mobile-nav-pill { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 92%; max-width: 600px; background: rgba(15,23,42,0.97); backdrop-filter: blur(12px); border-radius: 24px; padding: 6px; z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3); }
        .mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .mobile-scroll-container::-webkit-scrollbar { display: none; }

        /* Desktop table */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-wrap::-webkit-scrollbar { height: 4px; }
        .table-wrap::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        table  { width: 100%; border-collapse: collapse; min-width: 860px; }
        thead th { background: #f8fafc; font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8; padding: 0.85rem 0.9rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap; cursor: pointer; user-select: none; }
        thead th:hover { color: #2563eb; }
        thead th .sort-icon { opacity: 0.3; margin-left: 4px; font-size: 0.6rem; }
        thead th.sorted .sort-icon { opacity: 1; color: #2563eb; }
        td { padding: 0.8rem 0.9rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.12s; cursor: pointer; }
        tbody tr:hover td { background: #eff6ff; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.28rem 0.65rem; border-radius: 10px; font-size: 0.66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-declined,.badge-canceled { background: #fee2e2; color: #991b1b; }
        .badge-claimed  { background: #f3e8ff; color: #6b21a8; }
        .badge-expired  { background: #f1f5f9; color: #64748b; }

        /* Stat cards */
        .stat-card { background: white; border-radius: 20px; padding: 1rem 1.15rem; border: 1px solid #e2e8f0; border-left-width: 4px; transition: all 0.2s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }
        .stat-card.ring { box-shadow: 0 0 0 2px #2563eb; }

        /* Quick-tabs */
        .qtab { display: inline-flex; align-items: center; gap: 6px; padding: 0.4rem 0.9rem; border-radius: 12px; font-size: 0.78rem; font-weight: 700; transition: all 0.18s; cursor: pointer; border: 1px solid #e2e8f0; white-space: nowrap; color: #64748b; background: white; }
        .qtab:hover  { border-color: #2563eb; color: #2563eb; }
        .qtab.active { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 4px 12px -2px rgba(37,99,235,0.3); }

        .field { background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 0.7rem 1rem 0.7rem 2.5rem; font-size: 0.875rem; font-family: inherit; color: #1e293b; transition: all 0.2s; width: 100%; }
        .field:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* Modals */
        .overlay { display: none; position: fixed; inset: 0; z-index: 200; align-items: flex-end; justify-content: center; padding: 0; }
        @media (min-width: 640px) { .overlay { align-items: center; padding: 12px; } }
        .overlay.open { display: flex; }
        .overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); }
        .modal-box {
            position: relative; background: white; width: 100%; max-width: 520px;
            border-radius: 28px 28px 0 0; /* bottom sheet on mobile */
            max-height: 92dvh; overflow-y: auto;
            box-shadow: 0 -8px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.28s cubic-bezier(0.34,1.3,0.64,1) both;
        }
        @media (min-width: 640px) {
            .modal-box { border-radius: 28px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); animation-name: popIn; }
            .modal-box.sm { max-width: 380px; }
        }
        @keyframes slideUp { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:none; } }
        @keyframes popIn  { from { opacity:0; transform:scale(0.92) translateY(16px); } to { opacity:1; transform:none; } }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        .drow  { display: flex; align-items: flex-start; gap: 12px; padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; }
        .drow:last-child { border-bottom: none; }
        .dicon { width: 34px; height: 34px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.82rem; flex-shrink: 0; }
        .dlabel { font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 2px; }
        .dvalue { font-size: 0.88rem; font-weight: 700; color: #1e293b; }

        .btn-confirm-approve { background: #16a34a; color: white; border: none; border-radius: 14px; padding: 0.85rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1; }
        .btn-confirm-approve:hover:not(:disabled) { background: #15803d; }
        .btn-confirm-decline { background: #ef4444; color: white; border: none; border-radius: 14px; padding: 0.85rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1; }
        .btn-confirm-decline:hover:not(:disabled) { background: #dc2626; }
        .btn-cancel { background: #f1f5f9; color: #475569; border: none; border-radius: 14px; padding: 0.85rem; font-size: 0.875rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; flex: 1; }
        .btn-cancel:hover { background: #e2e8f0; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp 0.35s ease both; }

        .ticket-section { border: 2px dashed #bfdbfe; border-radius: 24px; padding: 1.5rem; background: #f0f9ff; display: flex; flex-direction: column; align-items: center; }
        .empty-state { padding: 4rem 2rem; text-align: center; }

        .print-pill-yes { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.55rem; border-radius: 999px; font-size: 0.66rem; font-weight: 800; background: #eff6ff; color: #1d4ed8; white-space: nowrap; }
        .print-pill-no  { display: inline-flex; align-items: center; gap: 4px; padding: 0.2rem 0.55rem; border-radius: 999px; font-size: 0.66rem; font-weight: 800; background: #f1f5f9; color: #64748b; white-space: nowrap; }

        #dPrintLogForm { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1rem 1.1rem; margin: 0 1.25rem 1rem; }
        #dPrintLogForm label { font-size: 0.67rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; display: block; margin-bottom: 6px; }
        #dPrintLogForm input[type=number] { width: 100%; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.55rem 0.8rem; font-size: 0.875rem; font-family: inherit; color: #1e293b; }
        #dPrintLogForm input[type=number]:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn-save-print { background: #2563eb; color: white; border: none; border-radius: 12px; padding: 0.65rem 1.1rem; font-size: 0.8rem; font-weight: 800; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; gap: 6px; font-family: inherit; white-space: nowrap; }
        .btn-save-print:hover:not(:disabled) { background: #1d4ed8; }
        .btn-save-print:disabled { opacity: 0.6; cursor: not-allowed; }
        #printSaveMsg { font-size: 0.75rem; font-weight: 700; margin-top: 6px; min-height: 18px; }

        /* Mobile card */
        .res-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1rem 1.1rem; transition: box-shadow 0.15s; cursor: pointer; }
        .res-card:hover { box-shadow: 0 6px 20px -4px rgba(0,0,0,0.08); border-color: #bfdbfe; }
        .res-card:active { background: #f0f7ff; }
    </style>
</head>
<body class="flex min-h-screen">

    <?php
    $page  = $page ?? 'manage-reservations';
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
        $s = strtolower($res['status'] ?? 'pending');
        if ($s === 'approved') {
            $edt = strtotime(($res['reservation_date'] ?? '') . ' ' . ($res['end_time'] ?? '23:59'));
            if ($edt && $edt < time()) $s = 'expired';
        }
        $claimedVal = $res['claimed'] ?? false; if (in_array($claimedVal, [true, 1, 't', 'true', '1'], true)) $s = 'claimed';
        $res['_status'] = $s;
        $processed[] = $res;
    }
    $counts = [
        'all'      => count($processed),
        'pending'  => count(array_filter($processed, fn($r) => $r['_status'] === 'pending')),
        'approved' => count(array_filter($processed, fn($r) => $r['_status'] === 'approved')),
        'claimed'  => count(array_filter($processed, fn($r) => $r['_status'] === 'claimed')),
        'declined' => count(array_filter($processed, fn($r) => in_array($r['_status'], ['declined','canceled']))),
        'expired'  => count(array_filter($processed, fn($r) => $r['_status'] === 'expired')),
    ];
    $printLogMap = $printLogMap ?? [];
    $statusIcons = ['pending'=>'fa-clock','approved'=>'fa-circle-check','claimed'=>'fa-check-double','declined'=>'fa-xmark','canceled'=>'fa-ban','expired'=>'fa-hourglass-end'];

    // Shared helper to build the mdata array (avoids repeating it twice)
    $mkMdata = function(array $res, string $s) use ($printLogMap): string {
        $name     = htmlspecialchars($res['visitor_name']  ?? $res['full_name']     ?? 'Guest');
        $email    = htmlspecialchars($res['user_email']    ?? $res['visitor_email'] ?? '');
        $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
        $pc       = htmlspecialchars($res['pc_number']     ?? '');
        $rawDate  = $res['reservation_date'] ?? '';
        $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
        $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
        $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
        $purpose  = htmlspecialchars($res['purpose']      ?? '—');
        $type     = htmlspecialchars($res['visitor_type'] ?? '—');
        $created  = !empty($res['created_at']) ? date('M j, Y · g:i A', strtotime($res['created_at'])) : '—';
        $code     = $res['e_ticket_code'] ?? ('RES-' . $res['id'] . '-' . $rawDate);
        $approverName  = htmlspecialchars($res['approver_name']  ?? '');
        $approverEmail = htmlspecialchars($res['approver_email'] ?? '');
        $approvedAt    = !empty($res['updated_at']) && in_array($s, ['approved','claimed','declined','expired'])
                         ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
        $pl        = $printLogMap[(int)$res['id']] ?? null;
        $plPrinted = $pl !== null ? (bool)$pl['printed'] : null;
        $plPages   = $pl ? (int)($pl['pages'] ?? 0) : 0;
        $plAt      = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
        return json_encode([
            'id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,'resource'=>$resource,'pc'=>$pc,
            'date'=>$date,'rawDate'=>$rawDate,'start'=>$start,'end'=>$end,'purpose'=>$purpose,'type'=>$type,
            'created'=>$created,'code'=>$code,'claimed'=>(int)($res['claimed']??0)===1,
            'approverName'=>$approverName,'approverEmail'=>$approverEmail,'approvedAt'=>$approvedAt,
            'plPrinted'=>$plPrinted,'plPages'=>$plPages,'plAt'=>$plAt,
        ]);
    };
    ?>

    <form id="approveForm" method="POST" action="<?= base_url('admin/approve') ?>" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="approveId"></form>
    <form id="declineForm" method="POST" action="<?= base_url('admin/decline') ?>" style="display:none"><?= csrf_field() ?><input type="hidden" name="id" id="declineId"></form>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name"  content="<?= csrf_token() ?>">

    <!-- ══ DETAIL MODAL ══ -->
    <div id="detailModal" class="overlay" role="dialog" aria-modal="true">
        <div class="overlay-bg" onclick="closeModal('detail')"></div>
        <div class="modal-box">
            <!-- Drag handle on mobile -->
            <div class="flex justify-center pt-3 pb-1 sm:hidden"><div class="w-10 h-1 bg-slate-200 rounded-full"></div></div>
            <div class="flex items-start justify-between px-5 pt-3 sm:pt-6 pb-3">
                <div><p id="dId" class="text-xs font-black text-slate-400 font-mono mb-1"></p><h3 class="text-lg sm:text-xl font-black text-slate-900">Reservation Details</h3></div>
                <button onclick="closeModal('detail')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0 mt-0.5"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="dStatusBar" class="mx-5 mb-4 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>
            <div class="px-5 pb-2">
                <div class="drow"><div class="dicon"><i class="fa-solid fa-user"></i></div><div><p class="dlabel">Requestor</p><p id="dName" class="dvalue"></p><p id="dEmail" class="text-xs text-slate-400 font-semibold mt-0.5 break-all"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-desktop"></i></div><div><p class="dlabel">Resource</p><p id="dResource" class="dvalue"></p><p id="dPc" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-calendar-day"></i></div><div><p class="dlabel">Schedule</p><p id="dDate" class="dvalue"></p><p id="dTime" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-pen-to-square"></i></div><div><p class="dlabel">Purpose</p><p id="dPurpose" class="dvalue"></p></div></div>
                <div class="drow"><div class="dicon"><i class="fa-solid fa-id-badge"></i></div><div><p class="dlabel">Visitor Type</p><p id="dType" class="dvalue"></p></div></div>
                <div class="drow" id="dApprovedByRow" style="display:none">
                    <div class="dicon" id="dApprovedByIcon"><i class="fa-solid fa-user-check"></i></div>
                    <div><p class="dlabel" id="dApprovedByLabel">Approved By</p><p id="dApprovedByName" class="dvalue"></p><p id="dApprovedByEmail" class="text-xs text-slate-400 font-semibold mt-0.5 break-all"></p><p id="dApprovedAt" class="text-xs text-slate-400 font-semibold mt-0.5"></p></div>
                </div>
                <div class="drow"><div class="dicon"><i class="fa-regular fa-clock"></i></div><div><p class="dlabel">Submitted</p><p id="dCreated" class="dvalue"></p></div></div>
            </div>
            <div id="dQr" class="mx-5 mb-4 ticket-section" style="display:none">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">E-Ticket</p>
                <canvas id="qrCanvas" class="rounded-xl"></canvas>
                <p id="dTicketCode" class="text-xs text-slate-400 font-mono mt-2 text-center break-all px-2"></p>
                <button onclick="downloadTicket()" class="mt-3 flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition"><i class="fa-solid fa-download text-xs"></i> Download E-Ticket</button>
            </div>
            <div id="dClaimed" class="mx-5 mb-4 bg-purple-50 border-2 border-dashed border-purple-200 rounded-2xl p-5 text-center" style="display:none">
                <i class="fa-solid fa-check-double text-2xl text-purple-500 mb-1 block"></i>
                <p class="font-black text-purple-700 text-sm">Ticket Already Claimed</p>
                <p class="text-xs text-purple-400 mt-0.5">This reservation has been used.</p>
            </div>
            <div id="dPrintLog" class="mx-5 mb-3 rounded-2xl px-4 py-3 border border-slate-100 bg-slate-50 flex items-center gap-3" style="display:none">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-print text-blue-600 text-sm"></i></div>
                <div class="flex-1 min-w-0"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Print Log</p><p id="dPrintText" class="text-sm font-bold text-slate-700"></p></div>
                <span id="dPrintBadge" class="text-[10px] font-black px-2.5 py-1 rounded-full flex-shrink-0"></span>
            </div>
            <div id="dPrintLogForm">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-print text-blue-500"></i> Log Print</p>
                <div class="flex items-end gap-3">
                    <div class="flex-1"><label>Pages Printed <span class="text-slate-300 font-normal normal-case tracking-normal">(0 = not printed)</span></label><input type="number" id="printPagesInput" min="0" max="999" value="0" placeholder="0"></div>
                    <button id="savePrintBtn" class="btn-save-print" onclick="savePrintLog()"><i class="fa-solid fa-floppy-disk text-xs"></i> Save</button>
                </div>
                <p id="printSaveMsg" class="text-slate-400"></p>
            </div>
            <div id="dActions" class="px-5 py-5 border-t border-slate-100 flex gap-3 flex-wrap mt-2"></div>
        </div>
    </div>

    <!-- Approve modal -->
    <div id="approveModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('approve')"></div>
        <div class="modal-box sm">
            <div class="flex justify-center pt-3 sm:hidden"><div class="w-10 h-1 bg-slate-200 rounded-full"></div></div>
            <div class="px-6 pt-5 pb-5 text-center">
                <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-circle-check"></i></div>
                <h3 class="text-xl font-black text-slate-900">Approve Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This will confirm the reservation.</p>
                <p id="approveConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-6 pb-7 flex gap-3">
                <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmApproveBtn" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
            </div>
        </div>
    </div>

    <!-- Decline modal -->
    <div id="declineModal" class="overlay">
        <div class="overlay-bg" onclick="closeModal('decline')"></div>
        <div class="modal-box sm">
            <div class="flex justify-center pt-3 sm:hidden"><div class="w-10 h-1 bg-slate-200 rounded-full"></div></div>
            <div class="px-6 pt-5 pb-5 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <h3 class="text-xl font-black text-slate-900">Decline Reservation?</h3>
                <p class="text-slate-400 text-sm mt-1 font-medium">This action cannot be undone.</p>
                <p id="declineConfirmName" class="text-slate-700 text-sm mt-3 font-black"></p>
            </div>
            <div class="px-6 pb-7 flex gap-3">
                <button class="btn-cancel" onclick="closeModal('decline')"><i class="fa-solid fa-xmark text-xs"></i> Cancel</button>
                <button id="confirmDeclineBtn" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="hidden lg:block w-80 flex-shrink-0 p-6">
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
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i><?= $item['label'] ?>
                        <?php if ($item['key'] === 'manage-reservations' && $counts['pending'] > 0): ?>
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $counts['pending'] ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all"><i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout</a>
            </div>
        </div>
    </aside>

    <!-- Mobile Nav -->
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

    <!-- Main -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-10 pb-32">

        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 fade-up">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Admin Portal</p>
                <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">Manage Reservations</h2>
                <p class="text-slate-400 font-medium text-sm mt-0.5"><?= $counts['all'] ?> total record<?= $counts['all'] != 1 ? 's' : '' ?></p>
            </div>
            <button onclick="exportCSV()" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition shadow-sm flex-shrink-0">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </button>
        </header>

        <!-- Stat cards: 2 cols → 3 (sm) → 5 (lg) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-5">
            <?php foreach ([
                ['Total',    $counts['all'],      'border-blue-400',    'text-slate-700',   'all'],
                ['Pending',  $counts['pending'],  'border-amber-400',   'text-amber-600',   'pending'],
                ['Approved', $counts['approved'], 'border-emerald-400', 'text-emerald-600', 'approved'],
                ['Claimed',  $counts['claimed'],  'border-purple-400',  'text-purple-600',  'claimed'],
                ['Declined', $counts['declined'], 'border-rose-400',    'text-rose-600',    'declined'],
            ] as [$lbl, $val, $border, $color, $key]): ?>
                <div class="stat-card <?= $border ?>" onclick="filterByStatus('<?= $key ?>')" data-filter="<?= $key ?>">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?= $lbl ?></p>
                    <p class="text-2xl font-black <?= $color ?>"><?= $val ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-solid fa-circle-check text-green-500 flex-shrink-0"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm fade-up">
                <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Search + filter bar -->
        <div class="bg-white border border-slate-200 rounded-[24px] p-4 mb-4 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="field" oninput="applyFilters()">
                </div>
                <div class="relative w-full sm:w-44 flex-shrink-0">
                    <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input id="dateInput" type="date" class="field" onchange="applyFilters()">
                </div>
                <button onclick="clearFilters()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2 flex-shrink-0">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </button>
            </div>
            <div class="flex gap-2 mt-3 overflow-x-auto pb-1 -mx-1 px-1" style="-webkit-overflow-scrolling:touch;">
                <button class="qtab active" data-tab="all"     onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group text-xs"></i> All <span class="text-[9px] font-black opacity-70"><?= $counts['all'] ?></span></button>
                <button class="qtab" data-tab="pending"  onclick="setTab(this,'pending')"><i class="fa-solid fa-clock text-xs"></i> Pending<?php if ($counts['pending'] > 0): ?><span class="bg-amber-400 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none"><?= $counts['pending'] ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="approved" onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check text-xs"></i> Approved</button>
                <button class="qtab" data-tab="claimed"  onclick="setTab(this,'claimed')"><i class="fa-solid fa-check-double text-xs"></i> Claimed</button>
                <button class="qtab" data-tab="declined" onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark text-xs"></i> Declined</button>
                <button class="qtab" data-tab="expired"  onclick="setTab(this,'expired')"><i class="fa-solid fa-hourglass-end text-xs"></i> Expired</button>
            </div>
        </div>

        <div class="px-1 mb-3"><p id="resultCount" class="text-xs font-bold text-slate-400"></p></div>

        <!-- ══ DESKTOP TABLE (md+) ══ -->
        <div class="hidden md:block bg-white border border-slate-200 rounded-[28px] shadow-sm overflow-hidden">
            <div class="table-wrap">
                <table id="resTable">
                    <thead>
                        <tr>
                            <th style="width:48px">ID</th>
                            <th onclick="sortTable(1)">User <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(2)">Resource <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(3)">Schedule <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th>Purpose</th>
                            <th onclick="sortTable(5)">Status <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(6)">Approved By <i class="fa-solid fa-sort sort-icon"></i></th>
                            <th>Print</th>
                            <th class="text-right" style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (empty($processed)): ?>
                            <tr><td colspan="9"><div class="empty-state">
                                <i class="fa-solid fa-calendar-xmark text-5xl text-slate-200 mb-4 block"></i>
                                <p class="font-black text-slate-400 text-lg">No reservations yet</p>
                                <p class="text-slate-300 text-sm mt-1">Reservations will appear here once submitted.</p>
                            </div></td></tr>
                        <?php else: ?>
                            <?php foreach ($processed as $res):
                                $s        = $res['_status'];
                                $name     = htmlspecialchars($res['visitor_name']  ?? $res['full_name']     ?? 'Guest');
                                $email    = htmlspecialchars($res['user_email']    ?? $res['visitor_email'] ?? '');
                                $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                                $pc       = htmlspecialchars($res['pc_number']     ?? '');
                                $rawDate  = $res['reservation_date'] ?? '';
                                $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                                $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                                $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                                $purpose  = htmlspecialchars($res['purpose']      ?? '—');
                                $icon     = $statusIcons[$s] ?? 'fa-circle';
                                $approverName = htmlspecialchars($res['approver_name'] ?? '');
                                $approvedAt   = !empty($res['updated_at']) && in_array($s, ['approved','claimed','declined','expired'])
                                                ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                                $pl        = $printLogMap[(int)$res['id']] ?? null;
                                $plPrinted = $pl !== null ? (bool)$pl['printed'] : null;
                                $plPages   = $pl ? (int)($pl['pages'] ?? 0) : 0;
                                $plAt      = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                                $mdata     = $mkMdata($res, $s);
                                $searchStr = strtolower("$name $resource $purpose $email $approverName");
                            ?>
                            <tr class="res-row"
                                data-status="<?= $s ?>"
                                data-search="<?= htmlspecialchars($searchStr, ENT_QUOTES) ?>"
                                data-date="<?= $rawDate ?>"
                                data-pl-printed="<?= $plPrinted === null ? '' : ($plPrinted ? 'Yes' : 'No') ?>"
                                data-pl-pages="<?= $plPrinted ? $plPages : '' ?>"
                                data-pl-at="<?= htmlspecialchars($plAt, ENT_QUOTES) ?>"
                                onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                                <td><span class="text-xs font-black text-slate-400 font-mono">#<?= $res['id'] ?></span></td>
                                <td>
                                    <p class="font-bold text-sm text-slate-800 leading-tight"><?= $name ?></p>
                                    <?php if ($email): ?><p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[150px]"><?= $email ?></p><?php endif; ?>
                                </td>
                                <td>
                                    <p class="font-bold text-sm text-slate-800 leading-tight"><?= $resource ?></p>
                                    <?php if ($pc): ?><div class="flex items-center gap-1 mt-0.5"><i class="fa-solid fa-desktop text-[9px] text-slate-400"></i><span class="text-[11px] text-slate-500 font-semibold"><?= $pc ?></span></div><?php endif; ?>
                                </td>
                                <td>
                                    <p class="text-sm font-bold text-slate-700 whitespace-nowrap"><?= $date ?></p>
                                    <p class="text-[11px] text-blue-500 font-semibold mt-0.5 whitespace-nowrap"><?= $start ?> – <?= $end ?></p>
                                </td>
                                <td><span class="text-sm text-slate-500 font-medium" style="display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:120px"><?= $purpose ?></span></td>
                                <td><span class="badge badge-<?= $s ?>"><i class="fa-solid <?= $icon ?> text-[9px]"></i><?= ucfirst($s) ?></span></td>
                                <td onclick="event.stopPropagation()">
                                    <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired'])): ?>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[9px] font-black flex-shrink-0 <?= $s==='declined'?'bg-red-100 text-red-600':'bg-emerald-100 text-emerald-700' ?>"><?= mb_strtoupper(mb_substr($approverName,0,1)) ?></div>
                                            <div class="min-w-0"><p class="text-xs font-bold text-slate-700 truncate max-w-[100px]"><?= $approverName ?></p><?php if ($approvedAt): ?><p class="text-[10px] text-slate-400 truncate"><?= $approvedAt ?></p><?php endif; ?></div>
                                        </div>
                                    <?php else: ?><span class="text-[10px] text-slate-300 font-bold">—</span><?php endif; ?>
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <?php if ($plPrinted===true): ?><span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> <?= $plPages ?>pg</span>
                                    <?php elseif ($plPrinted===false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>
                                    <?php else: ?><span class="text-[10px] text-slate-300 font-bold">—</span><?php endif; ?>
                                </td>
                                <td class="text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <?php if ($s==='pending'): ?>
                                            <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="h-8 px-3 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center gap-1.5"><i class="fa-solid fa-check text-[11px]"></i> Approve</button>
                                            <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="h-8 px-2 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center"><i class="fa-solid fa-xmark text-[11px]"></i></button>
                                        <?php elseif ($s==='approved'): ?><span class="text-[11px] text-emerald-500 font-black flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Approved</span>
                                        <?php elseif ($s==='claimed'): ?><span class="text-[11px] text-purple-500 font-black flex items-center gap-1"><i class="fa-solid fa-check-double"></i> Claimed</span>
                                        <?php else: ?><span class="text-xs text-slate-300 font-semibold italic">—</span><?php endif; ?>
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

        <!-- ══ MOBILE CARDS (< md) ══ -->
        <div class="md:hidden space-y-3" id="mobileCardList">
            <?php if (empty($processed)): ?>
                <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm">
                    <div class="empty-state"><i class="fa-solid fa-calendar-xmark text-5xl text-slate-200 mb-4 block"></i><p class="font-black text-slate-400 text-lg">No reservations yet</p></div>
                </div>
            <?php else: ?>
                <?php foreach ($processed as $res):
                    $s        = $res['_status'];
                    $name     = htmlspecialchars($res['visitor_name']  ?? $res['full_name']     ?? 'Guest');
                    $email    = htmlspecialchars($res['user_email']    ?? $res['visitor_email'] ?? '');
                    $resource = htmlspecialchars($res['resource_name'] ?? 'Resource #' . ($res['resource_id'] ?? ''));
                    $pc       = htmlspecialchars($res['pc_number']     ?? '');
                    $rawDate  = $res['reservation_date'] ?? '';
                    $date     = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                    $start    = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                    $end      = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                    $purpose  = htmlspecialchars($res['purpose']       ?? '—');
                    $icon     = $statusIcons[$s] ?? 'fa-circle';
                    $approverName = htmlspecialchars($res['approver_name'] ?? '');
                    $pl        = $printLogMap[(int)$res['id']] ?? null;
                    $plPrinted = $pl !== null ? (bool)$pl['printed'] : null;
                    $plPages   = $pl ? (int)($pl['pages'] ?? 0) : 0;
                    $mdata     = $mkMdata($res, $s);
                    $searchStr = strtolower("$name $resource $purpose $email $approverName");
                ?>
                    <div class="res-card mobile-res-card"
                         data-status="<?= $s ?>"
                         data-search="<?= htmlspecialchars($searchStr, ENT_QUOTES) ?>"
                         data-date="<?= $rawDate ?>"
                         onclick='openDetail(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>

                        <!-- Name + badge -->
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-slate-800 leading-tight truncate"><?= $name ?></p>
                                <?php if ($email): ?><p class="text-[11px] text-slate-400 truncate"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="badge badge-<?= $s ?> flex-shrink-0 mt-0.5"><i class="fa-solid <?= $icon ?> text-[9px]"></i><?= ucfirst($s) ?></span>
                        </div>

                        <!-- Resource -->
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-semibold mb-1">
                            <i class="fa-solid fa-desktop text-[10px] text-slate-400 flex-shrink-0"></i>
                            <span class="truncate"><?= $resource ?><?= $pc ? " · $pc" : '' ?></span>
                        </div>
                        <!-- Date/time -->
                        <div class="flex items-center gap-1.5 text-xs text-blue-500 font-semibold mb-2">
                            <i class="fa-solid fa-calendar-day text-[10px] flex-shrink-0"></i>
                            <span><?= $date ?> · <?= $start ?> – <?= $end ?></span>
                        </div>
                        <!-- Purpose -->
                        <p class="text-xs text-slate-500 mb-3 line-clamp-1"><?= $purpose ?></p>

                        <!-- Approve/Decline for pending -->
                        <?php if ($s === 'pending'): ?>
                            <div class="flex gap-2 pt-2.5 border-t border-slate-100" onclick="event.stopPropagation()">
                                <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="flex-1 h-9 rounded-xl bg-green-100 hover:bg-green-600 hover:text-white text-green-700 font-bold text-xs transition flex items-center justify-center gap-1.5"><i class="fa-solid fa-check text-[11px]"></i> Approve</button>
                                <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="flex-1 h-9 rounded-xl bg-red-100 hover:bg-red-500 hover:text-white text-red-600 font-bold text-xs transition flex items-center justify-center gap-1.5"><i class="fa-solid fa-xmark text-[11px]"></i> Decline</button>
                            </div>
                        <?php endif; ?>

                        <!-- Footer: ID + print pill -->
                        <div class="flex items-center justify-between mt-2<?= $s==='pending' ? '' : ' pt-2 border-t border-slate-50' ?>">
                            <span class="text-[10px] font-black text-slate-300 font-mono">#<?= $res['id'] ?></span>
                            <?php if ($plPrinted===true): ?><span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> <?= $plPages ?>pg</span>
                            <?php elseif ($plPrinted===false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="mobileNoResults" class="hidden md:hidden bg-white rounded-[24px] border border-slate-200 shadow-sm px-6 py-10 text-center">
            <i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-200 mb-3 block"></i>
            <p class="font-bold text-slate-400">No reservations match your filters.</p>
        </div>

    </main>

    <script>
    const allTableRows   = Array.from(document.querySelectorAll('.res-row'));
    const allMobileCards = Array.from(document.querySelectorAll('.mobile-res-card'));
    let curTab = 'all', approveTargetId = null, declineTargetId = null;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const csrfName  = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content')  ?? 'csrf_token';

    const printLogMap = {};
    <?php foreach ($printLogMap as $resId => $pl): ?>
    printLogMap[<?= (int)$resId ?>] = { printed:<?= isset($pl['printed'])&&in_array($pl['printed'],[true,1,'t','true','1'],true)?'true':'false' ?>, pages:<?= (int)($pl['pages']??0) ?>, at:"<?= !empty($pl['printed_at'])?date('M j · g:i A',strtotime($pl['printed_at'])):'' ?>" };
    <?php endforeach; ?>

    let _currentReservationId = null;

    // ── Print ──────────────────────────────────────────────────────────────────
    async function savePrintLog() {
        const rid = _currentReservationId, pages = parseInt(document.getElementById('printPagesInput').value,10)||0;
        const btn = document.getElementById('savePrintBtn'), msg = document.getElementById('printSaveMsg');
        if (!rid) { msg.textContent='No reservation selected.'; msg.style.color='#ef4444'; return; }
        btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin text-xs"></i> Saving…';
        msg.textContent=''; msg.style.color='';
        const body = new FormData();
        body.append(csrfName,csrfToken); body.append('reservation_id',rid); body.append('printed',pages>0?1:0); body.append('pages',pages);
        try {
            const res = await fetch('<?= base_url('admin/log-print') ?>',{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body});
            const data = await res.json();
            if (data.ok) {
                const now=new Date(), fmt=now.toLocaleDateString('en-US',{month:'short',day:'numeric'})+' · '+now.toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
                printLogMap[rid]={printed:pages>0,pages,at:fmt};
                refreshPrintLogStrip(rid); refreshTablePrintCell(rid,pages);
                msg.textContent=pages>0?`✓ Saved — ${pages} page${pages!==1?'s':''} printed`:'✓ Saved — no printing logged'; msg.style.color='#16a34a';
                btn.innerHTML='<i class="fa-solid fa-check text-xs"></i> Saved';
                setTimeout(()=>{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-floppy-disk text-xs"></i> Save';},2000);
            } else throw new Error(data.error??'Unknown error');
        } catch(err) { msg.textContent='✗ Failed: '+err.message; msg.style.color='#ef4444'; btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-floppy-disk text-xs"></i> Save'; }
    }
    function refreshPrintLogStrip(rid) {
        const plog=printLogMap[rid],logEl=document.getElementById('dPrintLog'),logText=document.getElementById('dPrintText'),logBadge=document.getElementById('dPrintBadge');
        if (!plog){logEl.style.display='none';return;} logEl.style.display='flex';
        if (plog.printed){logText.textContent=`Printed ${plog.pages} page${plog.pages!==1?'s':''}${plog.at?` · ${plog.at}`:''}`;logBadge.textContent=`${plog.pages}pg`;logBadge.className='text-[10px] font-black px-2.5 py-1 rounded-full bg-blue-100 text-blue-700';}
        else{logText.textContent='No printing during this session';logBadge.textContent='No print';logBadge.className='text-[10px] font-black px-2.5 py-1 rounded-full bg-slate-200 text-slate-500';}
    }
    function refreshTablePrintCell(rid,pages) {
        allTableRows.forEach(r=>{
            try{const d=JSON.parse(r.getAttribute('onclick').replace(/^openDetail\(/,'').replace(/\)$/,''));
            if(d.id==rid){const cell=r.cells[7];
            if(pages>0){cell.innerHTML=`<span class="print-pill-yes"><i class="fa-solid fa-print text-[9px]"></i> ${pages}pg</span>`;r.dataset.plPrinted='Yes';r.dataset.plPages=pages;}
            else{cell.innerHTML=`<span class="print-pill-no"><i class="fa-solid fa-xmark text-[9px]"></i> No print</span>`;r.dataset.plPrinted='No';r.dataset.plPages='';}}}catch(e){}
        });
    }

    // ── CSV ────────────────────────────────────────────────────────────────────
    function exportCSV() {
        const visibleRows=allTableRows.filter(r=>r.style.display!=='none');
        const headers=['ID','User Name','Email','Resource Name','PC Number','Date','Start Time','End Time','Purpose','Visitor Type','Status','Approved By','Approved At','Printed','Pages Printed','Print Logged At','Submitted At'];
        const escape=v=>{const s=String(v??'');return s.includes(',')||s.includes('"')||s.includes('\n')?'"'+s.replace(/"/g,'""')+'"':s;};
        const lines=[headers.map(escape).join(',')];
        visibleRows.forEach(row=>{const id=row.querySelector('td:first-child span')?.textContent?.replace('#','').trim()??'';
        try{const d=JSON.parse(row.getAttribute('onclick').replace(/^openDetail\(/,'').replace(/\)$/,''));
        lines.push([id,d.name??'',d.email??'',d.resource??'',d.pc??'',d.date??'',d.start??'',d.end??'',d.purpose??'',d.type??'',d.status??'',d.approverName??'',d.approvedAt??'',row.dataset.plPrinted??'',row.dataset.plPages??'',row.dataset.plAt??'',d.created??''].map(escape).join(','));}catch(e){}});
        const blob=new Blob([lines.join('\r\n')],{type:'text/csv;charset=utf-8;'});
        const url=URL.createObjectURL(blob);const a=document.createElement('a');
        a.href=url;a.download=`reservations-${new Date().toISOString().slice(0,10)}.csv`;a.click();URL.revokeObjectURL(url);
    }

    // ── Filters ────────────────────────────────────────────────────────────────
    function setTab(btn,tab){document.querySelectorAll('.qtab').forEach(t=>t.classList.remove('active'));btn.classList.add('active');curTab=tab;syncCards(tab);applyFilters();}
    function filterByStatus(tab){curTab=tab;document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab===tab));syncCards(tab);applyFilters();}
    function syncCards(tab){document.querySelectorAll('[data-filter]').forEach(c=>c.classList.toggle('ring',c.dataset.filter===tab));}
    function applyFilters() {
        const q=document.getElementById('searchInput').value.toLowerCase().trim();
        const date=document.getElementById('dateInput').value;
        const match=el=>{
            const mt=curTab==='all'||(curTab==='declined'&&['declined','canceled'].includes(el.dataset.status))||el.dataset.status===curTab;
            const ms=!q||el.dataset.search.includes(q);
            const md=!date||el.dataset.date===date;
            return mt&&ms&&md;
        };
        let n=0;
        allTableRows.forEach(r=>{const s=match(r);r.style.display=s?'':'none';if(s)n++;});
        let m=0;
        allMobileCards.forEach(c=>{const s=match(c);c.style.display=s?'':'none';if(s)m++;});
        const total=allTableRows.length;
        document.getElementById('resultCount').textContent=`Showing ${n||m} of ${total} reservation${total!==1?'s':''}`;
        const tf=document.getElementById('tableFooter');if(tf)tf.textContent=`${n} result${n!==1?'s':''} displayed`;
        const mnr=document.getElementById('mobileNoResults');if(mnr)mnr.classList.toggle('hidden',m>0||allMobileCards.length===0);
    }
    function clearFilters(){document.getElementById('searchInput').value='';document.getElementById('dateInput').value='';curTab='all';document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab==='all'));syncCards('all');applyFilters();}

    // ── Sort ───────────────────────────────────────────────────────────────────
    let sortDir={};
    function sortTable(col){
        sortDir[col]=!sortDir[col];
        const tbody=document.getElementById('tableBody');
        Array.from(tbody.querySelectorAll('.res-row')).sort((a,b)=>{const at=(a.cells[col]?.innerText??'').trim().toLowerCase();const bt=(b.cells[col]?.innerText??'').trim().toLowerCase();return sortDir[col]?at.localeCompare(bt):bt.localeCompare(at);}).forEach(r=>tbody.appendChild(r));
        document.querySelectorAll('thead th').forEach((th,i)=>{th.classList.toggle('sorted',i===col);const ic=th.querySelector('.sort-icon');if(ic)ic.className=`fa-solid ${i===col?(sortDir[col]?'fa-sort-up':'fa-sort-down'):'fa-sort'} sort-icon`;});
    }

    // ── Detail modal ───────────────────────────────────────────────────────────
    const STATUS_META={
        pending: {icon:'fa-clock',        bg:'#fef3c7',color:'#92400e',label:'Pending — Awaiting approval'},
        approved:{icon:'fa-circle-check', bg:'#dcfce7',color:'#166534',label:'Approved'},
        claimed: {icon:'fa-check-double', bg:'#f3e8ff',color:'#6b21a8',label:'Claimed — Ticket used'},
        declined:{icon:'fa-xmark-circle', bg:'#fee2e2',color:'#991b1b',label:'Declined'},
        canceled:{icon:'fa-ban',          bg:'#fee2e2',color:'#991b1b',label:'Cancelled'},
        expired: {icon:'fa-hourglass-end',bg:'#f1f5f9',color:'#475569',label:'Expired — Date has passed'},
    };
    function openDetail(d) {
        _currentReservationId=d.id;
        const plog=printLogMap[d.id];
        document.getElementById('printPagesInput').value=plog?(plog.printed?plog.pages:0):0;
        document.getElementById('printSaveMsg').textContent='';
        const sb=document.getElementById('savePrintBtn');sb.disabled=false;sb.innerHTML='<i class="fa-solid fa-floppy-disk text-xs"></i> Save';
        const m=STATUS_META[d.status]||STATUS_META.pending;
        document.getElementById('dId').textContent='Reservation #'+d.id;
        document.getElementById('dName').textContent=d.name;
        document.getElementById('dEmail').textContent=d.email;
        document.getElementById('dResource').textContent=d.resource;
        document.getElementById('dPc').textContent=d.pc?'PC: '+d.pc:'';
        document.getElementById('dDate').textContent=d.date;
        document.getElementById('dTime').textContent=d.start+' – '+d.end;
        document.getElementById('dPurpose').textContent=d.purpose;
        document.getElementById('dType').textContent=d.type;
        document.getElementById('dCreated').textContent=d.created;
        const approverRow=document.getElementById('dApprovedByRow');
        if(d.approverName&&['approved','claimed','declined','expired'].includes(d.status)){
            approverRow.style.display='flex';const iD=d.status==='declined';
            document.getElementById('dApprovedByLabel').textContent=iD?'Declined By':'Approved By';
            document.getElementById('dApprovedByIcon').className=`dicon ${iD?'bg-red-50 text-red-500':'bg-emerald-50 text-emerald-600'}`;
            document.getElementById('dApprovedByIcon').innerHTML=`<i class="fa-solid ${iD?'fa-user-xmark':'fa-user-check'}"></i>`;
            document.getElementById('dApprovedByName').textContent=d.approverName;
            document.getElementById('dApprovedByEmail').textContent=d.approverEmail||'';
            document.getElementById('dApprovedAt').textContent=d.approvedAt?`on ${d.approvedAt}`:'';
        } else approverRow.style.display='none';
        const bar=document.getElementById('dStatusBar');bar.style.background=m.bg;bar.style.color=m.color;bar.innerHTML=`<i class="fa-solid ${m.icon}"></i> <span>${m.label}</span>`;
        const qrSec=document.getElementById('dQr'),clSec=document.getElementById('dClaimed');
        const isClaimed=d.claimed||d.status==='claimed';
        if(isClaimed){qrSec.style.display='none';clSec.style.display='block';}
        else if(d.status==='approved'){clSec.style.display='none';qrSec.style.display='flex';QRCode.toCanvas(document.getElementById('qrCanvas'),d.code,{width:150,margin:1,color:{dark:'#1e293b',light:'#ffffff'}});document.getElementById('dTicketCode').textContent=d.code;}
        else{qrSec.style.display='none';clSec.style.display='none';}
        refreshPrintLogStrip(d.id);
        const acts=document.getElementById('dActions');
        if(d.status==='pending'){acts.innerHTML=`<button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}'); closeModal('detail');" class="btn-confirm-approve flex-1"><i class="fa-solid fa-check"></i> Approve</button><button onclick="triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}'); closeModal('detail');" class="btn-confirm-decline flex-1"><i class="fa-solid fa-xmark"></i> Decline</button>`;}
        else{acts.innerHTML=`<button onclick="closeModal('detail')" class="btn-cancel w-full"><i class="fa-solid fa-xmark text-xs"></i> Close</button>`;}
        document.getElementById('detailModal').classList.add('open');document.body.style.overflow='hidden';
    }
    function downloadTicket(){const canvas=document.getElementById('qrCanvas'),code=document.getElementById('dTicketCode').textContent;const link=document.createElement('a');link.download=`E-Ticket-${code}.png`;link.href=canvas.toDataURL('image/png');link.click();}
    function triggerApprove(id,name){approveTargetId=id;document.getElementById('approveConfirmName').textContent=name?`"${name}"` :'';openModal('approve');}
    function triggerDecline(id,name){declineTargetId=id;document.getElementById('declineConfirmName').textContent=name?`"${name}"` :'';openModal('decline');}
    document.getElementById('confirmApproveBtn').addEventListener('click',function(){if(!approveTargetId)return;this.disabled=true;this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Approving…';document.getElementById('approveId').value=approveTargetId;document.getElementById('approveForm').submit();});
    document.getElementById('confirmDeclineBtn').addEventListener('click',function(){if(!declineTargetId)return;this.disabled=true;this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Declining…';document.getElementById('declineId').value=declineTargetId;document.getElementById('declineForm').submit();});
    const modalIds={detail:'detailModal',approve:'approveModal',decline:'declineModal'};
    function openModal(key){const el=document.getElementById(modalIds[key]);if(el){el.classList.add('open');document.body.style.overflow='hidden';}}
    function closeModal(key){
        const el=document.getElementById(modalIds[key]);if(el){el.classList.remove('open');document.body.style.overflow='';}
        if(key==='detail')_currentReservationId=null;
        if(key==='approve'){const b=document.getElementById('confirmApproveBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-check"></i> Approve';}
        if(key==='decline'){const b=document.getElementById('confirmDeclineBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-xmark"></i> Decline';}
    }
    document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal('detail');closeModal('approve');closeModal('decline');}});
    applyFilters();
    </script>
</body>
</html>