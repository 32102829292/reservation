<?php
$page    = 'reservations';
$sk_name = $sk_name ?? session()->get('name') ?? session()->get('username') ?? 'SK Officer';

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
    'declined'  => count(array_filter($processed, fn($r) => in_array($r['_status'], ['declined', 'canceled']))),
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Reservations | SK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name"  content="<?= csrf_token() ?>">
    <style>
        /* ── Res cards ── */
        .res-card { background:var(--card); border-radius:var(--r-lg); border:1px solid var(--border); padding:14px 16px; cursor:pointer; transition:all .15s; position:relative; overflow:hidden; }
        .res-card:hover { border-color:var(--indigo-border); box-shadow:var(--shadow-md); transform:translateY(-1px); }
        .res-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:0 4px 4px 0; }
        .res-card[data-status="pending"]::before   { background:#fbbf24; }
        .res-card[data-status="approved"]::before  { background:#10b981; }
        .res-card[data-status="claimed"]::before   { background:#a855f7; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before  { background:#ef4444; }
        .res-card[data-status="unclaimed"]::before { background:#fb923c; }
        .res-card[data-status="expired"]::before   { background:#94a3b8; }

        /* ── Ticket section ── */
        .ticket-section { border:2px dashed var(--indigo-border); border-radius:20px; padding:20px; background:var(--indigo-light); display:flex; flex-direction:column; align-items:center; }

        /* ── Unclaimed banner ── */
        .unclaimed-banner { background:var(--orange-bg,#fff7ed); border:1.5px dashed #fdba74; border-radius:14px; padding:12px 14px; display:flex; align-items:center; gap:10px; margin:0 24px 14px; }
        .ub-icon { width:34px; height:34px; background:#fed7aa; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#c2410c; font-size:13px; flex-shrink:0; }

        /* ── Overlay / modals ── */
        .overlay { display:none; position:fixed; inset:0; z-index:300; align-items:center; justify-content:center; }
        .overlay.open { display:flex; animation:fadeIn .15s ease; }
        .overlay-bg { position:absolute; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(6px); }
        .modal-box { position:relative; margin:auto; background:var(--card); border-radius:28px; width:94%; max-width:520px; max-height:92vh; overflow-y:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,.35); animation:popIn .22s cubic-bezier(.34,1.56,.64,1) both; }
        .modal-box.sm { max-width:380px; }
        .modal-box.lg { max-width:620px; }

        /* Detail modal — bottom sheet on mobile */
        @media(max-width:639px) {
            .overlay#detailModal { align-items:flex-end; }
            .overlay#detailModal .modal-box { margin:0; width:100%; max-width:100%; border-radius:28px 28px 0 0; max-height:92vh; animation:slideUp .28s cubic-bezier(.34,1.2,.64,1) both; }
        }
        /* New-reservation modal — bottom sheet on mobile */
        @media(max-width:639px) {
            .overlay#newResModal { align-items:flex-end; }
            .overlay#newResModal .modal-box { margin:0; width:100%; max-width:100%; border-radius:28px 28px 0 0; max-height:96vh; animation:slideUp .28s cubic-bezier(.34,1.2,.64,1) both; }
        }

        .modal-box::-webkit-scrollbar { width:4px; }
        .modal-box::-webkit-scrollbar-thumb { background:var(--border); border-radius:4px; }

        /* ── Filter tabs ── */
        .qtab { white-space:nowrap; flex-shrink:0; }

        /* ── Export / primary btn ── */
        .btn-export { display:flex; align-items:center; gap:7px; padding:9px 18px; background:var(--indigo); color:#fff; border-radius:var(--r-sm); font-size:13px; font-weight:700; border:none; cursor:pointer; font-family:var(--font); transition:all .15s; box-shadow:0 4px 12px rgba(55,48,163,.28); }
        .btn-export:hover { background:#312e81; }

        /* ── New Reservation btn ── */
        .btn-new-res { display:flex; align-items:center; gap:7px; padding:9px 18px; background:#10b981; color:#fff; border-radius:var(--r-sm); font-size:13px; font-weight:700; border:none; cursor:pointer; font-family:var(--font); transition:all .15s; box-shadow:0 4px 12px rgba(16,185,129,.28); }
        .btn-new-res:hover { background:#059669; }

        /* ── Approve / Decline ── */
        .btn-approve-sm { background:#dcfce7; color:#166534; border:none; border-radius:8px; padding:6px 12px; font-size:11px; font-weight:800; cursor:pointer; font-family:var(--font); display:flex; align-items:center; gap:5px; transition:all .15s; }
        .btn-approve-sm:hover { background:#16a34a; color:#fff; }
        .btn-decline-sm { background:#fee2e2; color:#991b1b; border:none; border-radius:8px; padding:6px 9px; font-size:11px; font-weight:800; cursor:pointer; font-family:var(--font); display:flex; align-items:center; gap:5px; transition:all .15s; }
        .btn-decline-sm:hover { background:#dc2626; color:#fff; }
        .btn-confirm-approve { flex:1; padding:12px; background:#16a34a; color:#fff; border-radius:var(--r-sm); font-weight:700; border:none; cursor:pointer; font-size:13px; font-family:var(--font); display:flex; align-items:center; justify-content:center; gap:7px; transition:all .15s; }
        .btn-confirm-approve:hover { background:#15803d; }
        .btn-confirm-approve:disabled { opacity:.7; cursor:not-allowed; }
        .btn-confirm-decline { flex:1; padding:12px; background:#dc2626; color:#fff; border-radius:var(--r-sm); font-weight:700; border:none; cursor:pointer; font-size:13px; font-family:var(--font); display:flex; align-items:center; justify-content:center; gap:7px; transition:all .15s; }
        .btn-confirm-decline:hover { background:#b91c1c; }
        .btn-confirm-decline:disabled { opacity:.7; cursor:not-allowed; }
        .btn-cancel { flex:1; padding:12px; background:var(--input-bg); border-radius:var(--r-sm); font-weight:700; color:var(--text-muted); border:1px solid var(--border); cursor:pointer; font-size:13px; font-family:var(--font); transition:all .15s; display:flex; align-items:center; justify-content:center; gap:7px; }
        .btn-cancel:hover { background:var(--indigo-light); border-color:var(--indigo); color:var(--indigo); }

        /* ── Print log ── */
        #dPrintLog { display:none; margin:0 24px 12px; border-radius:18px; padding:12px 14px; border:1px solid var(--border); background:var(--input-bg); align-items:center; gap:12px; }
        #dPrintLogForm { background:var(--input-bg); border:1px solid var(--border); border-radius:18px; padding:16px 18px; margin:0 24px 14px; }
        #dPrintLogForm label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--text-sub); display:block; margin-bottom:6px; }
        #dPrintLogForm input[type=number] { width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 12px; font-size:14px; font-family:var(--font); color:var(--text); background:var(--card); outline:none; }
        #dPrintLogForm input[type=number]:focus { border-color:var(--indigo); box-shadow:0 0 0 3px rgba(67,56,202,.1); }
        .btn-save-print { background:var(--indigo); color:#fff; border:none; border-radius:10px; padding:9px 16px; font-size:12px; font-weight:800; cursor:pointer; font-family:var(--font); transition:all .15s; display:flex; align-items:center; gap:6px; }
        .btn-save-print:hover:not(:disabled) { background:#312e81; }
        .btn-save-print:disabled { opacity:.6; cursor:not-allowed; }

        /* ── Stat cards ── */
        .stats-grid[style*="repeat(6"] { grid-template-columns:repeat(3,minmax(0,1fr)) !important; }
        @media(max-width:639px) {
            .stats-grid[style*="repeat(6"] { grid-template-columns:repeat(2,minmax(0,1fr)) !important; gap:8px !important; }
            .stat-card { padding:11px 12px !important; }
            .stat-num  { font-size:1.35rem !important; }
            .stat-lbl  { font-size:.56rem !important; }
        }

        /* ── Desktop/mobile split ── */
        @media(min-width:768px) { #mobileCardList { display:none !important; } #mobileEmpty { display:none !important; } }
        @media(max-width:767px) { .hidden-on-mobile { display:none !important; } }

        /* ── Page header ── */
        .topbar-right { flex-wrap:wrap; }
        @media(max-width:480px) { .page-title { font-size:1.3rem !important; } .topbar-right { width:100%; justify-content:flex-end; } .btn-export span.hide-xs { display:none; } .btn-new-res span.hide-xs { display:none; } }

        /* ── New Res form internals ── */
        .nrf-section-head { display:flex; align-items:center; gap:10px; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid var(--border-subtle); }
        .nrf-section-icon { width:32px; height:32px; background:var(--indigo-light); border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .nrf-divider { border:none; border-top:1px solid var(--border-subtle); margin:16px 0; }
        .nrf-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .nrf-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
        @media(max-width:560px) { .nrf-grid-2 { grid-template-columns:1fr; } .nrf-grid-3 { grid-template-columns:1fr; } }

        /* type toggle */
        .type-toggle { display:flex; background:var(--input-bg,#f1f5f9); padding:4px; border-radius:10px; gap:4px; }
        .type-btn { flex:1; padding:9px 12px; border-radius:8px; cursor:pointer; font-weight:700; font-size:.8rem; transition:all .15s; color:var(--text-sub); border:none; background:transparent; font-family:var(--font); display:flex; align-items:center; justify-content:center; gap:7px; }
        .type-btn.active { background:var(--indigo); color:white; box-shadow:0 4px 12px rgba(55,48,163,.25); }

        /* autocomplete */
        .autocomplete-wrap { position:relative; }
        .autocomplete-list { position:absolute; z-index:50; background:var(--card); border:1px solid var(--border); border-radius:var(--r-md); box-shadow:var(--shadow-lg); max-height:200px; overflow-y:auto; width:100%; top:calc(100% + 4px); left:0; }
        .autocomplete-item { padding:10px 14px; cursor:pointer; font-size:.85rem; transition:background .15s; font-weight:500; color:var(--text); }
        .autocomplete-item:hover { background:var(--indigo-light); color:var(--indigo); }
        .autocomplete-item .sub { font-size:.7rem; color:var(--text-sub); margin-top:2px; }

        /* pc grid */
        .pc-section { background:var(--indigo-light); border:1px solid var(--indigo-border); border-radius:var(--r-md); padding:14px; }
        .pc-btn { padding:8px 10px; border-radius:8px; font-size:.75rem; font-weight:700; border:1px solid var(--border); background:var(--card); color:var(--text-muted); cursor:pointer; transition:all .15s; }
        .pc-btn:hover { border-color:var(--indigo); color:var(--indigo); }
        .pc-btn.selected { background:var(--indigo); color:white; border-color:var(--indigo); }

        /* custom date/time pickers */
        #nrResDate, #nrStartTime, #nrEndTime { display:none !important; }
        .dt-trigger { display:flex; align-items:center; justify-content:space-between; gap:8px; width:100%; padding:10px 13px; background:var(--card); border:1px solid var(--border); border-radius:var(--r-sm); font-family:var(--font); font-size:.87rem; font-weight:500; color:var(--text-sub); cursor:pointer; transition:border .2s,box-shadow .2s; user-select:none; }
        .dt-trigger.has-value { color:var(--text); }
        .dt-trigger:hover { border-color:var(--indigo-border); }
        .dt-trigger.open { border-color:var(--indigo); box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .dt-drop { position:absolute; top:calc(100% + 6px); left:0; z-index:9999; border-radius:14px; animation:dtDrop .15s cubic-bezier(.4,0,.2,1); }
        .dt-drop.cal { width:272px; padding:16px 14px 12px; }
        .dt-drop.tim { width:220px; padding:14px 12px 12px; }
        body:not(.dark) .dt-drop { background:#fff; border:1px solid rgba(99,102,241,.18); box-shadow:0 20px 50px rgba(15,23,42,.18); }
        body.dark     .dt-drop { background:#0e1828; border:1px solid rgba(99,102,241,.22); box-shadow:0 20px 60px rgba(0,0,0,.65); }
        @media(max-width:560px) { .dt-drop.cal { width:calc(100vw - 80px) !important; max-width:280px; } }
        .cal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
        .cal-month-label { font-size:.86rem; font-weight:700; cursor:pointer; padding:3px 7px; border-radius:7px; transition:background .15s; color:var(--text); }
        .cal-month-label:hover { background:var(--indigo-light); }
        .cal-nav-btn { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.72rem; transition:all .15s; background:var(--input-bg); border:1px solid var(--border); color:var(--text-sub); }
        .cal-nav-btn:hover { border-color:var(--indigo); color:var(--indigo); background:var(--indigo-light); }
        .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:2px; }
        .cal-dow { font-size:.58rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; text-align:center; padding:2px 0 8px; color:var(--text-sub); }
        .cal-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; border-radius:7px; font-size:.78rem; font-weight:500; cursor:pointer; transition:all .12s; border:1px solid transparent; color:var(--text-muted); }
        .cal-day:hover:not(.cal-other):not(.cal-selected) { background:var(--indigo-light); color:var(--indigo); border-color:var(--indigo-border); }
        .cal-day.cal-other { pointer-events:none; color:var(--border); }
        .cal-day.cal-today { color:var(--indigo); font-weight:700; }
        .cal-day.cal-selected { background:var(--indigo)!important; color:#fff!important; font-weight:700; border-color:var(--indigo)!important; box-shadow:0 2px 10px rgba(99,102,241,.4); }
        .cal-footer { display:flex; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:1px solid var(--border-subtle); }
        .cal-foot-btn { font-size:.7rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:all .15s; color:var(--text-sub); }
        .cal-foot-btn:hover { color:var(--indigo); background:var(--indigo-light); }
        .cal-foot-btn.today { color:var(--indigo); }
        .tim-title { font-size:.63rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; text-align:center; margin-bottom:10px; color:var(--text-sub); }
        .tim-cols { display:flex; align-items:flex-start; gap:4px; }
        .tim-col { flex:1; display:flex; flex-direction:column; gap:2px; max-height:180px; overflow-y:auto; scrollbar-width:thin; }
        .tim-col::-webkit-scrollbar { width:3px; }
        .tim-col::-webkit-scrollbar-thumb { background:var(--border); border-radius:4px; }
        .tim-item { padding:6px 4px; border-radius:6px; font-size:.79rem; font-weight:500; text-align:center; cursor:pointer; transition:all .1s; border:1px solid transparent; color:var(--text-sub); }
        .tim-item:hover:not(.sel) { background:var(--indigo-light); color:var(--indigo); }
        .tim-item.sel { background:var(--indigo)!important; color:#fff!important; font-weight:700; box-shadow:0 2px 8px rgba(99,102,241,.4); }
        .tim-sep { font-size:1rem; font-weight:700; padding:5px 0; align-self:flex-start; margin-top:4px; color:var(--border); }
        .ampm-col { display:flex; flex-direction:column; gap:4px; padding-top:2px; }
        .ampm-btn { padding:7px 8px; border-radius:7px; font-size:.73rem; font-weight:700; cursor:pointer; text-align:center; transition:all .15s; border:1px solid var(--border); color:var(--text-sub); background:var(--input-bg); }
        .ampm-btn:hover:not(.sel) { color:var(--indigo); border-color:var(--indigo-border); }
        .ampm-btn.sel { background:var(--indigo)!important; color:#fff!important; border-color:var(--indigo)!important; }
        .tim-set-btn { width:100%; margin-top:10px; padding:8px; background:var(--indigo); color:#fff; border:none; border-radius:8px; font-size:.78rem; font-weight:700; font-family:var(--font); cursor:pointer; transition:background .15s; }
        .tim-set-btn:hover { background:#4f46e5; }
        .picker-wrap { position:relative; }

        /* submit btn inside modal */
        .btn-submit-res { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:13px; background:var(--indigo); color:white; border-radius:var(--r-sm); font-size:.88rem; font-weight:700; border:none; cursor:pointer; font-family:var(--font); transition:all .15s; box-shadow:0 4px 12px rgba(55,48,163,.28); }
        .btn-submit-res:hover { background:#312e81; }
        .btn-submit-res:disabled { opacity:.7; cursor:not-allowed; }

        /* confirm inner modal */
        .modal-confirm-box { position:relative; margin:auto; background:var(--card); border-radius:22px; width:94%; max-width:440px; max-height:88vh; overflow-y:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,.35); animation:popIn .2s cubic-bezier(.34,1.56,.64,1) both; }
        .mrow { display:flex; justify-content:space-between; align-items:flex-start; padding:8px 0; border-bottom:1px solid var(--border-subtle); gap:12px; }
        .mrow:last-child { border-bottom:none; }
        .mrow-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--text-sub); flex-shrink:0; padding-top:1px; }
        .mrow-value { font-weight:600; color:var(--text); font-size:.82rem; text-align:right; word-break:break-word; }

        /* ── Animations ── */
        @keyframes popIn   { from{opacity:0;transform:scale(.96)} to{opacity:1;transform:none} }
        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
        @keyframes slideUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
        @keyframes dtDrop  { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:none} }
        .fade-up { animation:slideUp .4s ease both; }

        /* ── Dark mode ── */
        body.dark .stat-card       { background:#0b1628!important; border-color:rgba(99,102,241,.15)!important; }
        body.dark .card            { background:#0b1628!important; border-color:rgba(99,102,241,.1)!important; }
        body.dark .search-input    { background:#101e35!important; border-color:rgba(99,102,241,.18)!important; color:#e2eaf8!important; }
        body.dark #nrDateInput     { background:#101e35!important; border-color:rgba(99,102,241,.18)!important; color:#e2eaf8!important; color-scheme:dark; }
        body.dark .res-card        { background:#0b1628!important; border-color:rgba(99,102,241,.1)!important; }
        body.dark .res-card:hover  { border-color:var(--indigo)!important; }
        body.dark .modal-box       { background:#0b1628!important; }
        body.dark .modal-confirm-box { background:#0b1628!important; }
        body.dark .type-toggle     { background:#101e35!important; }
        body.dark .type-btn        { color:#7fb3e8!important; }
        body.dark .pc-section      { background:rgba(55,48,163,.1)!important; border-color:rgba(99,102,241,.2)!important; }
        body.dark .pc-btn          { background:#101e35!important; border-color:rgba(99,102,241,.22)!important; color:#7fb3e8!important; }
        body.dark .pc-btn:hover    { border-color:var(--indigo)!important; color:#a5b4fc!important; }
        body.dark .pc-btn.selected { background:var(--indigo)!important; color:#fff!important; }
        body.dark .autocomplete-list { background:#0b1628!important; border-color:rgba(99,102,241,.18)!important; }
        body.dark .autocomplete-item { color:#e2eaf8!important; }
        body.dark .autocomplete-item:hover { background:rgba(99,102,241,.12)!important; color:#a5b4fc!important; }
        body.dark #dPrintLogForm   { background:#060e1e!important; border-color:rgba(99,102,241,.12)!important; }
        body.dark #dPrintLogForm input[type=number] { background:#101e35!important; border-color:rgba(99,102,241,.18)!important; color:#e2eaf8!important; }
        body.dark #dPrintLog       { background:#060e1e!important; border-color:rgba(99,102,241,.1)!important; }
        body.dark .unclaimed-banner { background:rgba(251,146,60,.1)!important; border-color:rgba(251,146,60,.3)!important; }
        body.dark .ticket-section  { background:rgba(99,102,241,.08)!important; border-color:rgba(99,102,241,.3)!important; }
        body.dark .tbl-wrap        { background:#0b1628!important; border-color:rgba(99,102,241,.1)!important; }
        body.dark .res-row:hover td { background:rgba(99,102,241,.06)!important; }
        body.dark .page-eyebrow    { color:#4a6fa5!important; }
        body.dark .page-title      { color:#e2eaf8!important; }
        body.dark .page-sub        { color:#4a6fa5!important; }
        body.dark .flash-ok        { background:rgba(55,48,163,.2)!important; border-color:rgba(99,102,241,.3)!important; color:#a5b4fc!important; }
        body.dark .flash-err       { background:rgba(220,38,38,.1)!important; border-color:rgba(248,113,113,.3)!important; color:#f87171!important; }
        body.dark .mrow-label      { color:#4a6fa5!important; }
        body.dark .mrow-value      { color:#e2eaf8!important; }
        body.dark .mrow            { border-color:rgba(99,102,241,.08)!important; }
        body.dark #modalSummaryBox { background:#060e1e!important; border-color:rgba(99,102,241,.1)!important; }
        body.dark .nrf-section-head { border-color:rgba(99,102,241,.1)!important; }
        body.dark .nrf-section-icon { background:rgba(55,48,163,.2)!important; }
    </style>
</head>
<body>

<?php include APPPATH . 'Views/partials/sk_layout.php'; ?>

<!-- Hidden forms for approve / decline -->
<form id="approveForm" method="POST" action="<?= base_url('sk/approve') ?>" style="display:none">
    <?= csrf_field() ?><input type="hidden" name="id" id="approveId">
</form>
<form id="declineForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none">
    <?= csrf_field() ?><input type="hidden" name="id" id="declineId">
</form>

<!-- ═══════════════════════════════════════════
     DETAIL MODAL
═══════════════════════════════════════════ -->
<div id="detailModal" class="overlay" role="dialog" aria-modal="true">
    <div class="overlay-bg" onclick="closeModal('detail')"></div>
    <div class="modal-box">
        <div class="sheet-handle"></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 12px">
            <div>
                <p id="dId" style="font-size:11px;font-weight:800;color:var(--text-sub);font-variant-numeric:tabular-nums;margin-bottom:3px"></p>
                <h3 style="font-size:18px;font-weight:800;">Reservation Details</h3>
            </div>
            <button onclick="closeModal('detail')" class="modal-close" style="margin-top:2px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="dStatusBar" style="margin:0 24px 12px;padding:10px 14px;border-radius:14px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700"></div>
        <div id="dUnclaimedBanner" class="unclaimed-banner" style="display:none">
            <div class="ub-icon"><i class="fa-solid fa-ticket"></i></div>
            <div><p style="font-weight:800;font-size:13px;color:#c2410c">Not Yet Claimed</p><p style="font-size:11px;color:#ea580c;font-weight:500;margin-top:2px">Approved but the e-ticket was never scanned.</p></div>
        </div>
        <div style="padding:0 24px 8px">
            <div class="drow"><div class="dicon"><i class="fa-solid fa-user"></i></div><div><p class="dlabel">Requestor</p><p id="dName" class="dvalue"></p><p id="dEmail" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-desktop"></i></div><div><p class="dlabel">Resource</p><p id="dResource" class="dvalue"></p><p id="dPc" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-calendar-day"></i></div><div><p class="dlabel">Schedule</p><p id="dDate" class="dvalue"></p><p id="dTime" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-pen-to-square"></i></div><div><p class="dlabel">Purpose</p><p id="dPurpose" class="dvalue"></p></div></div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-id-badge"></i></div><div><p class="dlabel">Visitor Type</p><p id="dType" class="dvalue"></p></div></div>
            <div class="drow" id="dApprovedByRow" style="display:none">
                <div class="dicon" id="dApprovedByIcon"><i class="fa-solid fa-user-check"></i></div>
                <div><p class="dlabel" id="dApprovedByLabel">Approved By</p><p id="dApprovedByName" class="dvalue"></p><p id="dApprovedByEmail" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p><p id="dApprovedAt" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-regular fa-clock"></i></div><div><p class="dlabel">Submitted</p><p id="dCreated" class="dvalue"></p></div></div>
        </div>
        <div id="dQr" class="ticket-section" style="display:none;margin:0 24px 14px">
            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--text-sub);margin-bottom:12px">E-Ticket</p>
            <canvas id="qrCanvas" style="border-radius:12px"></canvas>
            <p id="dTicketCode" style="font-size:11px;color:var(--text-sub);font-family:monospace;margin-top:8px;text-align:center;word-break:break-all;padding:0 8px"></p>
            <button onclick="downloadTicket()" style="margin-top:12px;display:flex;align-items:center;gap:8px;padding:8px 18px;background:var(--indigo);color:#fff;border-radius:10px;font-weight:800;font-size:12px;border:none;cursor:pointer;font-family:var(--font)"><i class="fa-solid fa-download" style="font-size:11px"></i> Download E-Ticket</button>
        </div>
        <div id="dClaimed" style="display:none;margin:0 24px 14px;background:#ede9fe;border:2px dashed #c4b5fd;border-radius:18px;padding:20px;text-align:center">
            <i class="fa-solid fa-check-double" style="font-size:1.5rem;color:#7c3aed;display:block;margin-bottom:6px"></i>
            <p style="font-weight:800;color:#7c3aed;font-size:13px">Ticket Already Claimed</p>
            <p style="font-size:11px;color:#8b5cf6;margin-top:3px">This reservation has been used.</p>
        </div>
        <div id="dPrintLog" style="display:none;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fa-solid fa-print" style="color:#16a34a;font-size:13px"></i></div>
            <div style="flex:1;min-width:0"><p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text-sub);margin-bottom:2px">Print Log</p><p id="dPrintText" style="font-size:13px;font-weight:700;"></p></div>
            <span id="dPrintBadge" style="font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;flex-shrink:0"></span>
        </div>
        <div id="dPrintLogForm">
            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text-sub);margin-bottom:12px;display:flex;align-items:center;gap:7px"><i class="fa-solid fa-print" style="color:var(--indigo)"></i> Log Print for this Reservation</p>
            <div style="display:flex;align-items:flex-end;gap:10px">
                <div style="flex:1"><label>Pages Printed <span style="color:var(--text-sub);font-weight:400;text-transform:none;letter-spacing:0">(0 = not printed)</span></label><input type="number" id="printPagesInput" min="0" max="999" value="0" placeholder="0"></div>
                <button id="savePrintBtn" class="btn-save-print" onclick="savePrintLog()"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save</button>
            </div>
            <p id="printSaveMsg" style="font-size:12px;font-weight:700;margin-top:6px;min-height:18px;color:var(--text-sub)"></p>
        </div>
        <div id="dActions" style="padding:16px 24px;border-top:1px solid var(--border-subtle);display:flex;gap:10px;flex-wrap:wrap;margin-top:8px"></div>
    </div>
</div>

<!-- Approve confirm -->
<div id="approveModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('approve')"></div>
    <div class="modal-box sm">
        <div style="padding:24px 24px 20px;text-align:center">
            <div style="width:64px;height:64px;background:#dcfce7;color:#16a34a;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-circle-check"></i></div>
            <h3 style="font-size:18px;font-weight:800;">Approve Reservation?</h3>
            <p style="color:var(--text-sub);font-size:13px;margin-top:4px;font-weight:500">This will confirm the reservation.</p>
            <p id="approveConfirmName" style="font-size:13px;margin-top:10px;font-weight:800"></p>
        </div>
        <div style="padding:0 24px 24px;display:flex;gap:10px">
            <button class="btn-cancel" onclick="closeModal('approve')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
            <button id="confirmApproveBtn" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button>
        </div>
    </div>
</div>

<!-- Decline confirm -->
<div id="declineModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('decline')"></div>
    <div class="modal-box sm">
        <div style="padding:24px 24px 20px;text-align:center">
            <div style="width:64px;height:64px;background:#fee2e2;color:#dc2626;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <h3 style="font-size:18px;font-weight:800;">Decline Reservation?</h3>
            <p style="color:var(--text-sub);font-size:13px;margin-top:4px;font-weight:500">This action cannot be undone.</p>
            <p id="declineConfirmName" style="font-size:13px;margin-top:10px;font-weight:800"></p>
        </div>
        <div style="padding:0 24px 24px;display:flex;gap:10px">
            <button class="btn-cancel" onclick="closeModal('decline')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
            <button id="confirmDeclineBtn" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════
     NEW RESERVATION MODAL
═══════════════════════════════════════════ -->
<div id="newResModal" class="overlay" role="dialog" aria-modal="true">
    <div class="overlay-bg" onclick="closeModal('newRes')"></div>
    <div class="modal-box lg" style="max-height:96vh;">
        <div class="sheet-handle"></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 14px;border-bottom:1px solid var(--border-subtle);">
            <div>
                <p style="font-size:11px;font-weight:800;color:var(--text-sub);margin-bottom:3px">SK Portal</p>
                <h3 style="font-size:18px;font-weight:800;">New Reservation</h3>
            </div>
            <button onclick="closeModal('newRes')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- The form -->
        <form id="reservationForm" method="POST" action="<?= base_url('sk/create-reservation') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="visitor_name"  id="finalVisitorName">
            <input type="hidden" name="user_email"    id="finalUserEmail">
            <input type="hidden" name="user_id"       id="finalUserId">
            <input type="hidden" name="visitor_type"  id="finalVisitorType" value="User">
            <input type="hidden" name="purpose"       id="finalPurpose">
            <input type="hidden" name="pcs"           id="finalPcs" value="[]">

            <div style="padding:18px 24px;display:flex;flex-direction:column;gap:16px;">

                <!-- Visitor type -->
                <div>
                    <label class="field-label" style="margin-bottom:8px;display:block">Visitor Classification</label>
                    <div class="type-toggle">
                        <button type="button" class="type-btn active" id="btnUser" onclick="nrSetType('User')">
                            <i class="fa-solid fa-user" style="font-size:.8rem"></i> Registered User
                        </button>
                        <button type="button" class="type-btn" id="btnVisitor" onclick="nrSetType('Visitor')">
                            <i class="fa-solid fa-person-walking" style="font-size:.8rem"></i> Walk-in Visitor
                        </button>
                    </div>
                </div>

                <hr class="nrf-divider">

                <!-- Personal details -->
                <div>
                    <div class="nrf-section-head">
                        <div class="nrf-section-icon"><i class="fa-solid fa-id-badge" style="color:var(--indigo);font-size:.85rem"></i></div>
                        <div style="font-size:.88rem;font-weight:700;color:var(--text)">Personal Details</div>
                    </div>
                    <div id="nrUserFields" class="nrf-grid-2">
                        <div>
                            <label class="field-label">Full Name</label>
                            <div class="autocomplete-wrap">
                                <input type="text" id="nrUserNameInput" class="field-input" placeholder="Type to search users…" autocomplete="off">
                                <ul id="nrAutocompleteList" class="autocomplete-list" style="display:none"></ul>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="nrUserEmailDisplay" class="field-input" placeholder="Auto-filled on selection" readonly>
                        </div>
                    </div>
                    <div id="nrVisitorFields" style="display:none" class="nrf-grid-2">
                        <div><label class="field-label">Full Name</label><input type="text" id="nrVisitorNameInput" class="field-input" placeholder="Enter visitor's full name"></div>
                        <div><label class="field-label">Email Address</label><input type="email" id="nrVisitorEmailInput" class="field-input" placeholder="Enter email (optional)"></div>
                    </div>
                </div>

                <hr class="nrf-divider">

                <!-- Resource & Schedule -->
                <div>
                    <div class="nrf-section-head">
                        <div class="nrf-section-icon"><i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.85rem"></i></div>
                        <div style="font-size:.88rem;font-weight:700;color:var(--text)">Resource & Schedule</div>
                    </div>

                    <div style="margin-bottom:12px">
                        <label class="field-label">Select Asset / Resource</label>
                        <select id="nrResourceSelect" name="resource_id" class="field-input" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources ?? [] as $res): ?>
                                <option value="<?= $res['id'] ?>" data-name="<?= htmlspecialchars($res['name']) ?>"><?= htmlspecialchars($res['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="nrPcSection" style="display:none;margin-bottom:12px" class="pc-section">
                        <label class="field-label" style="color:var(--indigo);margin-bottom:8px;display:block">Assign Workstation(s)</label>
                        <div id="nrPcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(68px,1fr));gap:7px">
                            <?php foreach ($pcs ?? [] as $pc): ?>
                                <button type="button" onclick="nrTogglePc('<?= htmlspecialchars($pc['pc_number']) ?>',this)" data-pc="<?= htmlspecialchars($pc['pc_number']) ?>" class="pc-btn"><?= htmlspecialchars($pc['pc_number']) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <p style="font-size:.7rem;color:var(--indigo);font-weight:600;margin-top:8px">Selected: <span id="nrPcSelectedLabel">None</span></p>
                    </div>

                    <div class="nrf-grid-3" style="margin-bottom:12px">
                        <div>
                            <label class="field-label">Date</label>
                            <div class="picker-wrap" id="nrDateWrap">
                                <div class="dt-trigger" id="nrDateTrigger">
                                    <span id="nrDateLabel">Pick a date</span>
                                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="1" y="3" width="14" height="12" rx="2.5" stroke="#818cf8" stroke-width="1.4"/><path d="M5 1v3M11 1v3M1 7h14" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop cal" id="nrCalDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="reservation_date" id="nrResDate" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div>
                            <label class="field-label">Start Time</label>
                            <div class="picker-wrap" id="nrStartWrap">
                                <div class="dt-trigger" id="nrStartTrigger">
                                    <span id="nrStartLabel">Select time</span>
                                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="#818cf8" stroke-width="1.4"/><path d="M8 4.5V8l2.5 2.5" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop tim" id="nrStartDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="start_time" id="nrStartTime">
                        </div>
                        <div>
                            <label class="field-label">End Time</label>
                            <div class="picker-wrap" id="nrEndWrap">
                                <div class="dt-trigger" id="nrEndTrigger">
                                    <span id="nrEndLabel">Select time</span>
                                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="#818cf8" stroke-width="1.4"/><path d="M8 4.5V8l2.5 2.5" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop tim" id="nrEndDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="end_time" id="nrEndTime">
                        </div>
                    </div>

                    <div style="margin-bottom:12px">
                        <label class="field-label">Purpose of Visit</label>
                        <select id="nrPurposeSelect" class="field-input" required>
                            <option value="">— Select purpose —</option>
                            <option>Work</option>
                            <option>Personal</option>
                            <option>Study</option>
                            <option>SK Activity</option>
                            <option>Others</option>
                        </select>
                    </div>
                    <div id="nrPurposeOtherWrap" style="display:none">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="nrPurposeOther" class="field-input" placeholder="Describe the purpose…">
                    </div>
                </div>
            </div>

            <div style="padding:14px 24px 20px;border-top:1px solid var(--border-subtle);">
                <button type="button" onclick="nrPreview()" class="btn-submit-res">
                    <i class="fa-solid fa-eye" style="font-size:.85rem"></i> Preview & Confirm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirm sub-modal (sits on top of newResModal) -->
<div id="nrConfirmOverlay" style="display:none;position:fixed;inset:0;z-index:400;align-items:center;justify-content:center;background:rgba(15,23,42,.6);backdrop-filter:blur(6px);">
    <div class="modal-confirm-box" style="padding:0;">
        <div style="padding:20px 22px 14px;border-bottom:1px solid var(--border-subtle);display:flex;align-items:flex-start;justify-content:space-between;">
            <div><p style="font-size:11px;font-weight:800;color:var(--text-sub);margin-bottom:3px">New Reservation</p><h3 style="font-size:17px;font-weight:800;">Confirm Details</h3></div>
            <button onclick="nrCloseConfirm()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div style="margin:14px 22px;padding:10px 14px;border-radius:12px;background:#dcfce7;color:#166534;font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-circle-check"></i> Ready to save — please review below
        </div>
        <div id="modalSummaryBox" style="background:var(--input-bg);border-radius:var(--r-md);padding:12px 14px;border:1px solid var(--border-subtle);margin:0 22px 14px"></div>
        <div id="nrQrWrap" style="display:none;border:2px dashed var(--indigo-border);border-radius:16px;padding:16px;background:var(--indigo-light);display:none;flex-direction:column;align-items:center;gap:10px;margin:0 22px 14px">
            <p style="font-size:10px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:var(--indigo)">E-Ticket Preview</p>
            <canvas id="nrQrCanvas" style="border-radius:10px"></canvas>
            <p id="nrQrText" style="font-size:11px;color:var(--text-sub);font-family:monospace;text-align:center;word-break:break-all"></p>
        </div>
        <div id="nrConfirmActions" style="padding:14px 22px 20px;border-top:1px solid var(--border-subtle);display:flex;gap:10px;">
            <button type="button" class="btn-cancel" onclick="nrCloseConfirm()"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
            <button type="button" id="nrConfirmBtn" class="btn-confirm-approve" onclick="nrSubmit()"><i class="fa-solid fa-check" style="font-size:.8rem"></i> Confirm & Save</button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════ -->
<main class="main-area">
    <div class="fade-up">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:6px">
            <div>
                <div class="page-eyebrow">SK Portal</div>
                <div class="page-title">All Reservations</div>
                <div class="page-sub">
                    <?= $counts['all'] ?> total record<?= $counts['all'] != 1 ? 's' : '' ?>
                    <?php if ($counts['unclaimed'] > 0): ?>
                        · <span style="color:#c2410c;font-weight:700"><?= $counts['unclaimed'] ?> unclaimed</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="topbar-right" style="display:flex;align-items:center;gap:8px;">
                <div class="icon-btn" onclick="layoutToggleDark()"><span id="darkIcon"><i class="fa-regular fa-sun"></i></span></div>
                <button onclick="openModal('newRes')" class="btn-new-res">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hide-xs">New Reservation</span>
                </button>
                <button onclick="exportCSV()" class="btn-export">
                    <i class="fa-solid fa-file-csv"></i>
                    <span class="hide-xs">Export CSV</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="stats-grid fade-up" style="grid-template-columns:repeat(6,minmax(0,1fr));">
        <?php foreach ([
            ['Total',     $counts['all'],       '#3730a3', 'fa-layer-group',  'all'],
            ['Pending',   $counts['pending'],   '#d97706', 'fa-clock',        'pending'],
            ['Approved',  $counts['approved'],  '#16a34a', 'fa-circle-check', 'approved'],
            ['Claimed',   $counts['claimed'],   '#7c3aed', 'fa-check-double', 'claimed'],
            ['Declined',  $counts['declined'],  '#dc2626', 'fa-xmark-circle', 'declined'],
            ['Unclaimed', $counts['unclaimed'], '#c2410c', 'fa-ticket',       'unclaimed'],
        ] as [$lbl, $val, $color, $icon, $key]): ?>
            <div class="stat-card" style="border-left-color:<?= $color ?>" onclick="filterByStatus('<?= $key ?>')" data-filter="<?= $key ?>">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <p class="stat-lbl"><?= $lbl ?></p>
                    <i class="fa-solid <?= $icon ?>" style="font-size:13px;color:<?= $color ?>"></i>
                </div>
                <p class="stat-num" style="color:<?= $color ?>"><?= $val ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-ok fade-up"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-err fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Filter bar -->
    <div class="card fade-up" style="padding:16px 18px;margin-bottom:14px;">
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
            <div style="flex:1;min-width:180px;position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.75rem;pointer-events:none;z-index:1;"></i>
                <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="search-input" style="padding-left:32px;padding-right:30px;width:100%;" oninput="applyFilters();toggleClear('searchInput','searchClear')">
                <button id="searchClear" onclick="document.getElementById('searchInput').value='';applyFilters();toggleClear('searchInput','searchClear');" title="Clear search" style="display:none;position:absolute;right:9px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.75rem;padding:2px 4px;line-height:1;border-radius:4px;transition:color .15s;" onmouseover="this.style.color='#6366f1'" onmouseout="this.style.color='#94a3b8'"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div style="position:relative;width:160px;">
                <i class="fa-regular fa-calendar" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-sub);font-size:11px;pointer-events:none;z-index:1;"></i>
                <input id="dateInput" type="date" class="search-input" style="padding-left:36px;padding-right:30px;width:100%;" onchange="applyFilters();toggleClear('dateInput','dateClear')">
                <button id="dateClear" onclick="document.getElementById('dateInput').value='';applyFilters();toggleClear('dateInput','dateClear');" title="Clear date" style="display:none;position:absolute;right:9px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.75rem;padding:2px 4px;line-height:1;border-radius:4px;transition:color .15s;" onmouseover="this.style.color='#6366f1'" onmouseout="this.style.color='#94a3b8'"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px;scrollbar-width:none;">
            <button class="qtab active" data-tab="all"       onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group" style="font-size:11px"></i> All <span style="font-size:9px;font-weight:800;opacity:.7"><?= $counts['all'] ?></span></button>
            <button class="qtab"        data-tab="pending"   onclick="setTab(this,'pending')"><i class="fa-solid fa-clock" style="font-size:11px"></i> Pending<?php if($counts['pending']>0):?><span style="background:#f59e0b;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;"><?=$counts['pending']?></span><?php endif;?></button>
            <button class="qtab"        data-tab="approved"  onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check" style="font-size:11px"></i> Approved</button>
            <button class="qtab"        data-tab="unclaimed" onclick="setTab(this,'unclaimed')"><i class="fa-solid fa-ticket" style="font-size:11px"></i> Unclaimed<?php if($counts['unclaimed']>0):?><span style="background:#fb923c;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;"><?=$counts['unclaimed']?></span><?php endif;?></button>
            <button class="qtab"        data-tab="claimed"   onclick="setTab(this,'claimed')"><i class="fa-solid fa-check-double" style="font-size:11px"></i> Claimed</button>
            <button class="qtab"        data-tab="declined"  onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Declined</button>
            <button class="qtab"        data-tab="expired"   onclick="setTab(this,'expired')"><i class="fa-solid fa-hourglass-end" style="font-size:11px"></i> Expired</button>
        </div>
    </div>

    <p id="resultCount" style="font-size:11px;font-weight:700;color:var(--text-sub);padding:0 4px;margin-bottom:12px"></p>

    <!-- DESKTOP TABLE -->
    <div class="tbl-wrap hidden-on-mobile fade-up">
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
                    <th style="width:140px;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($processed)): ?>
                    <tr><td colspan="9"><div style="padding:80px 24px;text-align:center"><i class="fa-solid fa-calendar-xmark" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:12px"></i><p style="font-weight:800;color:var(--text-sub);font-size:15px">No reservations yet</p></div></td></tr>
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
                        $purpose     = htmlspecialchars($res['purpose'] ?? '—');
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
                        $mdata = json_encode(['id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,'resource'=>$resource,'pc'=>$pc,'date'=>$date,'rawDate'=>$rawDate,'start'=>$start,'end'=>$end,'purpose'=>$purpose,'type'=>$type,'created'=>$created,'code'=>$code,'claimed'=>$isClaimed,'unclaimed'=>$isUnclaimed,'approverName'=>$approverName,'approverEmail'=>$approverEmail,'approvedAt'=>$approvedAt,'plPrinted'=>$plPrinted,'plPages'=>$plPages,'plAt'=>$plAt]);
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
                            <td><span style="font-size:11px;font-weight:800;color:var(--text-sub);font-family:monospace">#<?= $res['id'] ?></span></td>
                            <td>
                                <p style="font-weight:700;font-size:13px;"><?= $name ?></p>
                                <?php if ($email): ?><p style="font-size:11px;color:var(--text-sub);margin-top:2px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p><?php endif; ?>
                            </td>
                            <td>
                                <p style="font-weight:700;font-size:13px;"><?= $resource ?></p>
                                <?php if ($pc): ?><div style="display:flex;align-items:center;gap:5px;margin-top:2px"><i class="fa-solid fa-desktop" style="font-size:9px;color:var(--text-sub)"></i><span style="font-size:11px;color:var(--text-muted);font-weight:600"><?= $pc ?></span></div><?php endif; ?>
                            </td>
                            <td>
                                <p style="font-size:13px;font-weight:700;"><?= $date ?></p>
                                <p style="font-size:11px;color:var(--indigo);font-weight:600;margin-top:2px"><?= $start ?> – <?= $end ?></p>
                            </td>
                            <td><span style="font-size:12px;color:var(--text-muted);font-weight:500;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px"><?= $purpose ?></span></td>
                            <td><span class="badge badge-<?= $s ?>"><i class="fa-solid <?= $icon ?>" style="font-size:9px"></i><?= ucfirst($s) ?></span></td>
                            <td onclick="event.stopPropagation()">
                                <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired','unclaimed'])): ?>
                                    <div style="display:flex;align-items:center;gap:7px">
                                        <div style="width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;flex-shrink:0;<?= $s==='declined'?'background:#fee2e2;color:#dc2626':'background:#dcfce7;color:#16a34a' ?>"><?= mb_strtoupper(mb_substr($approverName,0,1)) ?></div>
                                        <div style="min-width:0">
                                            <p style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:110px"><?= $approverName ?></p>
                                            <?php if ($approvedAt): ?><p style="font-size:10px;color:var(--text-sub);font-weight:500"><?= $approvedAt ?></p><?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?><span style="font-size:10px;color:var(--border);font-weight:700">—</span><?php endif; ?>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> <?= $plPages ?>pg</span>
                                <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>
                                <?php else: ?><span style="font-size:10px;color:var(--border);font-weight:700">—</span><?php endif; ?>
                            </td>
                            <td style="text-align:right" onclick="event.stopPropagation()">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:5px">
                                    <?php if ($s === 'pending'): ?>
                                        <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve-sm"><i class="fa-solid fa-check" style="font-size:10px"></i> Approve</button>
                                        <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-decline-sm"><i class="fa-solid fa-xmark" style="font-size:10px"></i></button>
                                    <?php elseif ($s === 'unclaimed'): ?><span style="font-size:11px;color:#c2410c;font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-ticket" style="font-size:10px"></i> Unclaimed</span>
                                    <?php elseif ($s === 'approved'):  ?><span style="font-size:11px;color:#16a34a;font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-circle-check" style="font-size:10px"></i> Approved</span>
                                    <?php elseif ($s === 'claimed'):   ?><span style="font-size:11px;color:#7c3aed;font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-check-double" style="font-size:10px"></i> Claimed</span>
                                    <?php else: ?><span style="font-size:11px;color:var(--text-sub);font-style:italic">—</span><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="padding:10px 18px;border-top:1px solid var(--border-subtle);background:rgba(238,242,255,.4);display:flex;align-items:center;justify-content:space-between">
            <p id="tableFooter" style="font-size:11px;font-weight:700;color:var(--text-sub)"></p>
            <p style="font-size:11px;color:var(--text-faint);font-weight:600;">Click any row to preview</p>
        </div>
    </div>

    <!-- MOBILE CARDS -->
    <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px">
        <?php if (empty($processed)): ?>
            <div class="empty-state"><i class="fa-solid fa-calendar-xmark" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i><p style="font-weight:800;color:var(--text-sub)">No reservations yet</p></div>
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
                $purpose     = htmlspecialchars($res['purpose'] ?? '—');
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
                $mdata = json_encode(['id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,'resource'=>$resource,'pc'=>$pc,'date'=>$date,'rawDate'=>$rawDate,'start'=>$start,'end'=>$end,'purpose'=>$purpose,'type'=>$type,'created'=>$created,'code'=>$code,'claimed'=>$isClaimed,'unclaimed'=>$isUnclaimed,'approverName'=>$approverName,'approverEmail'=>$approverEmail,'approvedAt'=>$approvedAt,'plPrinted'=>$plPrinted,'plPages'=>$plPages,'plAt'=>$plAt]);
                $avatarBg = ['pending'=>'background:#fef3c7;color:#92400e','approved'=>'background:#dcfce7;color:#166534','claimed'=>'background:#ede9fe;color:#6b21a8','declined'=>'background:#fee2e2;color:#991b1b','canceled'=>'background:#fee2e2;color:#991b1b','expired'=>'background:#f1f5f9;color:#64748b','unclaimed'=>'background:#fff7ed;color:#c2410c'][$s] ?? 'background:#f1f5f9;color:#64748b';
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
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                        <div style="width:38px;height:38px;border-radius:14px;<?= $avatarBg ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0"><?= mb_strtoupper(mb_substr(strip_tags($name),0,1)) ?></div>
                        <div style="flex:1;min-width:0">
                            <p style="font-weight:700;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                            <?php if ($email): ?><p style="font-size:11px;color:var(--text-sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p><?php endif; ?>
                        </div>
                        <span class="badge badge-<?= $s ?>" style="flex-shrink:0"><i class="fa-solid <?= $icon ?>" style="font-size:9px"></i><?= ucfirst($s) ?></span>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px"><i class="fa-solid fa-desktop" style="font-size:10px;color:var(--text-sub);flex-shrink:0"></i><p style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $resource ?><?= $pc ? ' · '.$pc : '' ?></p></div>
                            <div style="display:flex;align-items:center;gap:6px"><i class="fa-regular fa-calendar" style="font-size:10px;color:var(--text-sub);flex-shrink:0"></i><p style="font-size:11px;color:var(--text-muted);font-weight:600"><?= $date ?></p><span style="font-size:10px;color:var(--indigo);font-weight:700"><?= $start ?> – <?= $end ?></span></div>
                        </div>
                        <div class="card-print-pill" style="flex-shrink:0">
                            <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> <?= $plPages ?>pg</span>
                            <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span><?php endif; ?>
                        </div>
                    </div>
                    <p style="font-size:11px;color:var(--text-sub);font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:10px"><?= $purpose ?></p>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding-top:10px;border-top:1px solid var(--border-subtle)">
                        <div style="display:flex;align-items:center;gap:6px;min-width:0">
                            <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired','unclaimed'])): ?>
                                <div style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;flex-shrink:0;<?= $s==='declined'?'background:#fee2e2;color:#dc2626':'background:#dcfce7;color:#16a34a' ?>"><?= mb_strtoupper(mb_substr($approverName,0,1)) ?></div>
                                <p style="font-size:10px;color:var(--text-muted);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $s==='declined'?'Declined':'Approved' ?> by <?= $approverName ?></p>
                            <?php else: ?><p style="font-size:10px;color:var(--border);font-weight:600">#<?= $res['id'] ?></p><?php endif; ?>
                        </div>
                        <?php if ($s === 'pending'): ?>
                            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0" onclick="event.stopPropagation()">
                                <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve-sm" style="height:28px;padding:0 10px;font-size:11px"><i class="fa-solid fa-check" style="font-size:10px"></i> Approve</button>
                                <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-decline-sm" style="height:28px;padding:0 8px;font-size:11px"><i class="fa-solid fa-xmark" style="font-size:10px"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="mobileEmpty" style="display:none" class="empty-state">
        <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
        <p style="font-weight:800;color:var(--text-sub)">No reservations match</p>
        <p style="color:var(--border);font-size:12px;margin-top:4px">Try adjusting your search or filters.</p>
    </div>
</main>

<script>
/* ══ CORE LIST LOGIC ══ */
const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
const allCards     = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
let curTab = 'all', approveTargetId = null, declineTargetId = null;
let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
let csrfName  = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content') ?? 'csrf_token';

function refreshCsrf(data) {
    if (data.csrf_hash && data.csrf_token) {
        csrfToken = data.csrf_hash; csrfName = data.csrf_token;
        document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', csrfToken);
        document.querySelector('meta[name="csrf-name"]')?.setAttribute('content', csrfName);
    }
}

const printLogMap = {};
<?php foreach ($printLogMap as $resId => $pl): ?>
printLogMap[<?= (int)$resId ?>] = {
    printed: <?= isset($pl['printed']) ? (in_array($pl['printed'],[true,1,'t','true','1'],true)?'true':'false') : 'false' ?>,
    pages: <?= (int)($pl['pages'] ?? 0) ?>,
    at: "<?= !empty($pl['printed_at']) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '' ?>"
};
<?php endforeach; ?>

let _currentReservationId = null;

function toggleClear(inputId, btnId) {
    document.getElementById(btnId).style.display = document.getElementById(inputId).value ? 'block' : 'none';
}

async function savePrintLog() {
    const rid = _currentReservationId, pages = parseInt(document.getElementById('printPagesInput').value,10)||0;
    const btn = document.getElementById('savePrintBtn'), msg = document.getElementById('printSaveMsg');
    if (!rid) { msg.textContent='No reservation selected.'; msg.style.color='#dc2626'; return; }
    btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin" style="font-size:11px"></i> Saving…'; msg.textContent='';
    const body=new FormData(); body.append(csrfName,csrfToken); body.append('reservation_id',rid); body.append('printed',pages>0?1:0); body.append('pages',pages);
    try {
        const res=await fetch('<?= base_url('sk/log-print') ?>',{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body});
        const text=await res.text(); let data;
        try{data=JSON.parse(text);}catch{throw new Error(`Server error (${res.status})`);}
        if(data.ok){
            refreshCsrf(data);
            const now=new Date(), fmt=now.toLocaleDateString('en-US',{month:'short',day:'numeric'})+' · '+now.toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
            printLogMap[rid]={printed:pages>0,pages,at:fmt};
            refreshPrintLogStrip(rid); refreshBothPrintCells(rid,pages);
            msg.textContent=pages>0?`✓ Saved — ${pages} page${pages!==1?'s':''} printed`:'✓ Saved — no printing logged'; msg.style.color='#16a34a';
            btn.innerHTML='<i class="fa-solid fa-check" style="font-size:11px"></i> Saved';
            setTimeout(()=>{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';},2000);
        } else {throw new Error(data.error??'Unknown error');}
    } catch(err){
        msg.textContent='✗ Failed: '+err.message; msg.style.color='#dc2626';
        btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';
    }
}

function refreshPrintLogStrip(rid) {
    const plog=printLogMap[rid], logEl=document.getElementById('dPrintLog');
    if(!plog){logEl.style.display='none';return;}
    logEl.style.display='flex';
    const logText=document.getElementById('dPrintText'), logBadge=document.getElementById('dPrintBadge');
    if(plog.printed){logText.textContent=`Printed ${plog.pages} page${plog.pages!==1?'s':''}${plog.at?' · '+plog.at:''}`;logBadge.textContent=`${plog.pages}pg`;logBadge.style.cssText='background:#dcfce7;color:#16a34a';}
    else{logText.textContent='No printing during this session';logBadge.textContent='No print';logBadge.style.cssText='background:#f1f5f9;color:#64748b';}
}

function refreshBothPrintCells(rid,pages){
    allTableRows.forEach(row=>{if(row.dataset.id==rid){const cell=row.cells[7];if(pages>0){cell.innerHTML=`<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> ${pages}pg</span>`;row.dataset.plPrinted='Yes';row.dataset.plPages=pages;}else{cell.innerHTML=`<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>`;row.dataset.plPrinted='No';row.dataset.plPages='';}}}); 
    allCards.forEach(card=>{if(card.dataset.id==rid){const w=card.querySelector('.card-print-pill');if(w){w.innerHTML=pages>0?`<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> ${pages}pg</span>`:`<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>`;}card.dataset.plPrinted=pages>0?'Yes':'No';card.dataset.plPages=pages>0?pages:'';}});
}

function exportCSV(){
    const visibleRows=allTableRows.filter(r=>r.style.display!=='none');
    const headers=['ID','User Name','Email','Resource Name','PC Number','Date','Start Time','End Time','Purpose','Visitor Type','Status','Approved By','Approved At','Printed','Pages Printed','Submitted At'];
    const escape=v=>{const s=String(v??'');return s.includes(',')||s.includes('"')||s.includes('\n')?'"'+s.replace(/"/g,'""')+'"':s;};
    const lines=[headers.map(escape).join(',')];
    visibleRows.forEach(row=>{try{const d=JSON.parse(row.getAttribute('onclick').replace(/^openDetail\(/,'').replace(/\)$/,''));lines.push([d.id??'',d.name??'',d.email??'',d.resource??'',d.pc??'',d.date??'',d.start??'',d.end??'',d.purpose??'',d.type??'',d.status??'',d.approverName??'',d.approvedAt??'',row.dataset.plPrinted??'',row.dataset.plPages??'',d.created??''].map(escape).join(','));}catch(e){}});
    const blob=new Blob([lines.join('\r\n')],{type:'text/csv;charset=utf-8;'});
    const url=URL.createObjectURL(blob),a=document.createElement('a');a.href=url;a.download=`sk-reservations-${new Date().toISOString().slice(0,10)}.csv`;a.click();URL.revokeObjectURL(url);
}

function setTab(btn,tab){document.querySelectorAll('.qtab').forEach(t=>t.classList.remove('active'));btn.classList.add('active');curTab=tab;applyFilters();}
function filterByStatus(tab){curTab=tab;document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab===tab));applyFilters();}

function applyFilters(){
    const q=document.getElementById('searchInput').value.toLowerCase().trim(), date=document.getElementById('dateInput').value;
    const matchesFilters=el=>{let matchTab;if(curTab==='all')matchTab=true;else if(curTab==='declined')matchTab=['declined','canceled'].includes(el.dataset.status);else matchTab=el.dataset.status===curTab;return matchTab&&(!q||el.dataset.search.includes(q))&&(!date||el.dataset.date===date);};
    let n=0; allTableRows.forEach(row=>{const show=matchesFilters(row);row.style.display=show?'':'none';if(show)n++;});
    let m=0; allCards.forEach(card=>{const show=matchesFilters(card);card.style.display=show?'':'none';if(show)m++;});
    if(allCards.length>0)document.getElementById('mobileEmpty').style.display=m===0?'block':'none';
    const total=allTableRows.length;
    document.getElementById('resultCount').textContent=`Showing ${n} of ${total} reservation${total!==1?'s':''}`;
    document.getElementById('tableFooter').textContent=`${n} result${n!==1?'s':''} displayed`;
}

let sortDir={};
function sortTable(col){
    sortDir[col]=!sortDir[col];
    const tbody=document.getElementById('tableBody');
    Array.from(tbody.querySelectorAll('.res-row')).sort((a,b)=>{const at=(a.cells[col]?.innerText??'').trim().toLowerCase(),bt=(b.cells[col]?.innerText??'').trim().toLowerCase();return sortDir[col]?at.localeCompare(bt):bt.localeCompare(at);}).forEach(r=>tbody.appendChild(r));
    document.querySelectorAll('thead th').forEach((th,i)=>{th.classList.toggle('sorted',i===col);const ic=th.querySelector('.sort-icon');if(ic)ic.className=`fa-solid ${i===col?(sortDir[col]?'fa-sort-up':'fa-sort-down'):'fa-sort'} sort-icon`;});
}

/* ══ DETAIL MODAL ══ */
const STATUS_META={
    pending:{icon:'fa-clock',bg:'#fef3c7',color:'#92400e',label:'Pending — Awaiting approval'},
    approved:{icon:'fa-circle-check',bg:'#dcfce7',color:'#166534',label:'Approved'},
    claimed:{icon:'fa-check-double',bg:'#f3e8ff',color:'#6b21a8',label:'Claimed — Ticket used'},
    declined:{icon:'fa-xmark-circle',bg:'#fee2e2',color:'#991b1b',label:'Declined'},
    canceled:{icon:'fa-ban',bg:'#fee2e2',color:'#991b1b',label:'Cancelled'},
    expired:{icon:'fa-hourglass-end',bg:'#f1f5f9',color:'#475569',label:'Expired — Was never approved'},
    unclaimed:{icon:'fa-ticket',bg:'#fff7ed',color:'#c2410c',label:'Unclaimed — Approved but did not show up'},
};

function openDetail(d){
    _currentReservationId=d.id;
    const plog=printLogMap[d.id];
    document.getElementById('printPagesInput').value=plog?(plog.printed?plog.pages:0):0;
    document.getElementById('printSaveMsg').textContent='';
    const saveBtn=document.getElementById('savePrintBtn');saveBtn.disabled=false;saveBtn.innerHTML='<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';
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
    if(d.approverName&&['approved','claimed','declined','expired','unclaimed'].includes(d.status)){
        approverRow.style.display='flex';const isDeclined=d.status==='declined';
        document.getElementById('dApprovedByLabel').textContent=isDeclined?'Declined By':'Approved By';
        const iconEl=document.getElementById('dApprovedByIcon');iconEl.className='dicon';
        iconEl.style.background=isDeclined?'#fee2e2':'#dcfce7';iconEl.style.color=isDeclined?'#dc2626':'#16a34a';
        iconEl.innerHTML=`<i class="fa-solid ${isDeclined?'fa-user-xmark':'fa-user-check'}"></i>`;
        document.getElementById('dApprovedByName').textContent=d.approverName;
        document.getElementById('dApprovedByEmail').textContent=d.approverEmail||'';
        document.getElementById('dApprovedAt').textContent=d.approvedAt?`on ${d.approvedAt}`:'';
    }else{approverRow.style.display='none';}
    const bar=document.getElementById('dStatusBar');bar.style.background=m.bg;bar.style.color=m.color;
    bar.innerHTML=`<i class="fa-solid ${m.icon}"></i> <span style="font-weight:700">${m.label}</span>`;
    document.getElementById('dUnclaimedBanner').style.display=d.unclaimed?'flex':'none';
    const qrSec=document.getElementById('dQr'),clSec=document.getElementById('dClaimed');
    if(d.claimed||d.status==='claimed'){qrSec.style.display='none';clSec.style.display='block';}
    else if(d.status==='approved'||d.status==='unclaimed'){clSec.style.display='none';qrSec.style.display='flex';QRCode.toCanvas(document.getElementById('qrCanvas'),d.code,{width:150,margin:1,color:{dark:'#1e293b',light:'#ffffff'}});document.getElementById('dTicketCode').textContent=d.code;}
    else{qrSec.style.display='none';clSec.style.display='none';}
    refreshPrintLogStrip(d.id);
    const acts=document.getElementById('dActions');
    if(d.status==='pending'){acts.innerHTML=`<button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}')" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button><button onclick="triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}')" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>`;}
    else{acts.innerHTML=`<button onclick="closeModal('detail')" class="btn-cancel" style="width:100%"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Close</button>`;}
    openModal('detail');
}

function downloadTicket(){const canvas=document.getElementById('qrCanvas'),code=document.getElementById('dTicketCode').textContent;const link=document.createElement('a');link.download=`E-Ticket-${code}.png`;link.href=canvas.toDataURL('image/png');link.click();}
function triggerApprove(id,name){approveTargetId=id;document.getElementById('approveConfirmName').textContent=name?`"${name}"`:'';openModal('approve');}
function triggerDecline(id,name){declineTargetId=id;document.getElementById('declineConfirmName').textContent=name?`"${name}"`:'';openModal('decline');}

document.getElementById('confirmApproveBtn').addEventListener('click',function(){if(!approveTargetId)return;this.disabled=true;this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Approving…';document.getElementById('approveId').value=approveTargetId;document.getElementById('approveForm').submit();});
document.getElementById('confirmDeclineBtn').addEventListener('click',function(){if(!declineTargetId)return;this.disabled=true;this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Declining…';document.getElementById('declineId').value=declineTargetId;document.getElementById('declineForm').submit();});

/* ══ MODAL MANAGER ══ */
const modalIds={detail:'detailModal',approve:'approveModal',decline:'declineModal',newRes:'newResModal'};
function openModal(key){const el=document.getElementById(modalIds[key]);if(el){el.classList.add('open');document.body.style.overflow='hidden';}}
function closeModal(key){
    const el=document.getElementById(modalIds[key]);if(el){el.classList.remove('open');}
    const anyOpen=Object.values(modalIds).some(id=>document.getElementById(id)?.classList.contains('open'));
    if(!anyOpen)document.body.style.overflow='';
    if(key==='detail')_currentReservationId=null;
    if(key==='approve'){const b=document.getElementById('confirmApproveBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-check"></i> Approve';}
    if(key==='decline'){const b=document.getElementById('confirmDeclineBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-xmark"></i> Decline';}
}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal('detail');closeModal('approve');closeModal('decline');closeModal('newRes');nrCloseConfirm();}});

applyFilters();

/* ══ NEW RESERVATION LOGIC ══ */
const allUsers = <?= json_encode($users ?? []) ?>;
let nrCurrentType = 'User', nrSelectedUser = null, nrSelectedPcs = [];

function nrSetType(type) {
    nrCurrentType = type;
    document.getElementById('finalVisitorType').value = type;
    const isUser = type === 'User';
    document.getElementById('btnUser').classList.toggle('active', isUser);
    document.getElementById('btnVisitor').classList.toggle('active', !isUser);
    document.getElementById('nrUserFields').style.display = isUser ? 'grid' : 'none';
    document.getElementById('nrVisitorFields').style.display = isUser ? 'none' : 'grid';
    nrSelectedUser = null;
    ['nrUserNameInput','nrUserEmailDisplay','nrVisitorNameInput','nrVisitorEmailInput'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
    document.getElementById('finalUserId').value = '';
}

const nrUserInput = document.getElementById('nrUserNameInput');
const nrAcList   = document.getElementById('nrAutocompleteList');
nrUserInput.addEventListener('input', () => {
    const q = nrUserInput.value.toLowerCase().trim();
    nrAcList.innerHTML = ''; nrSelectedUser = null;
    if (!q) { nrAcList.style.display = 'none'; return; }
    const matches = allUsers.filter(u => (u.name||'').toLowerCase().includes(q)||(u.full_name||'').toLowerCase().includes(q)||(u.email||'').toLowerCase().includes(q)).slice(0,8);
    if (!matches.length) { nrAcList.style.display = 'none'; return; }
    matches.forEach(u => {
        const displayName = u.full_name || u.name || '';
        const li = document.createElement('li'); li.className = 'autocomplete-item';
        li.innerHTML = `<div style="font-weight:600">${displayName}</div><div class="sub">${u.email}</div>`;
        li.addEventListener('mousedown', () => {
            nrSelectedUser = u; nrUserInput.value = displayName;
            document.getElementById('nrUserEmailDisplay').value = u.email;
            document.getElementById('finalUserId').value = u.id;
            nrAcList.style.display = 'none';
        });
        nrAcList.appendChild(li);
    });
    nrAcList.style.display = 'block';
});
nrUserInput.addEventListener('blur', () => setTimeout(() => nrAcList.style.display = 'none', 150));

document.getElementById('nrResourceSelect').addEventListener('change', function() {
    const name = (this.options[this.selectedIndex]?.dataset.name || '').toLowerCase();
    const showPcs = name.includes('computer') || name.includes('pc') || name.includes('lab');
    document.getElementById('nrPcSection').style.display = showPcs ? 'block' : 'none';
    nrSelectedPcs = []; nrUpdatePcHidden();
    document.querySelectorAll('#nrPcGrid .pc-btn').forEach(b => b.classList.remove('selected'));
});

function nrTogglePc(num, btn) {
    const idx = nrSelectedPcs.indexOf(num);
    if (idx === -1) { nrSelectedPcs.push(num); btn.classList.add('selected'); }
    else            { nrSelectedPcs.splice(idx, 1); btn.classList.remove('selected'); }
    nrUpdatePcHidden();
}
function nrUpdatePcHidden() {
    document.getElementById('finalPcs').value = JSON.stringify(nrSelectedPcs);
    document.getElementById('nrPcSelectedLabel').textContent = nrSelectedPcs.length ? nrSelectedPcs.join(', ') : 'None';
}

document.getElementById('nrPurposeSelect').addEventListener('change', function() {
    document.getElementById('nrPurposeOtherWrap').style.display = this.value === 'Others' ? 'block' : 'none';
});

function nrPreview() {
    const isUser = nrCurrentType === 'User';
    const name   = isUser ? nrUserInput.value.trim() : document.getElementById('nrVisitorNameInput').value.trim();
    const email  = isUser ? document.getElementById('nrUserEmailDisplay').value.trim() : document.getElementById('nrVisitorEmailInput').value.trim();
    const resourceEl   = document.getElementById('nrResourceSelect');
    const resourceName = resourceEl.options[resourceEl.selectedIndex]?.text || '—';
    const showPcs      = document.getElementById('nrPcSection').style.display !== 'none';
    const date         = document.getElementById('nrResDate').value;
    const startTime    = document.getElementById('nrStartTime').value;
    const endTime      = document.getElementById('nrEndTime').value;
    const purposeVal   = document.getElementById('nrPurposeSelect').value;
    const purposeOther = document.getElementById('nrPurposeOther').value.trim();
    const purposeFinal = purposeVal === 'Others' && purposeOther ? `Others — ${purposeOther}` : purposeVal;
    if (!name)             return alert('Please enter a name.');
    if (!resourceEl.value) return alert('Please select a resource.');
    if (showPcs && !nrSelectedPcs.length) return alert('Please select at least one workstation.');
    if (!date)             return alert('Please select a date.');
    if (!startTime)        return alert('Please enter a start time.');
    if (!endTime)          return alert('Please enter an end time.');
    if (!purposeVal)       return alert('Please select a purpose.');
    if (isUser && !nrSelectedUser && !document.getElementById('finalUserId').value) return alert('Please select a registered user from the dropdown.');
    document.getElementById('finalVisitorName').value = name;
    document.getElementById('finalUserEmail').value   = email;
    document.getElementById('finalPurpose').value     = purposeFinal;
    const rows = [
        ['Type',         isUser ? 'Registered User' : 'Walk-in Visitor'],
        ['Name',         name || '—'],
        ['Email',        email || '—'],
        ['Resource',     resourceName],
        ['Workstations', nrSelectedPcs.length ? nrSelectedPcs.join(', ') : '—'],
        ['Date',         document.getElementById('nrDateLabel').textContent],
        ['Time',         `${document.getElementById('nrStartLabel').textContent} – ${document.getElementById('nrEndLabel').textContent}`],
        ['Purpose',      purposeFinal || '—']
    ];
    document.getElementById('modalSummaryBox').innerHTML = rows.map(([l,v]) => `<div class="mrow"><span class="mrow-label">${l}</span><span class="mrow-value">${v}</span></div>`).join('');
    document.getElementById('nrQrWrap').style.display = 'none';
    const btn = document.getElementById('nrConfirmBtn');
    btn.disabled = false; btn.style.display = 'flex';
    btn.innerHTML = '<i class="fa-solid fa-check" style="font-size:.8rem"></i> Confirm & Save';
    document.getElementById('nrConfirmOverlay').style.display = 'flex';
}

function nrCloseConfirm() {
    document.getElementById('nrConfirmOverlay').style.display = 'none';
}

function nrSubmit() {
    const btn = document.getElementById('nrConfirmBtn');
    btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.8rem"></i> Saving…';
    const code = `SK-${Date.now()}`;
    document.getElementById('nrQrText').textContent = code;
    QRCode.toCanvas(document.getElementById('nrQrCanvas'), code,
        {width:140,margin:1,color:{dark:'#1e293b',light:'#ffffff'}},
        () => {
            const qw = document.getElementById('nrQrWrap');
            qw.style.display = 'flex';
            document.getElementById('nrConfirmActions').innerHTML = '';
            setTimeout(() => document.getElementById('reservationForm').submit(), 700);
        }
    );
}
</script>

<!-- ══ CUSTOM DATE/TIME PICKERS ══ -->
<script>
(function() {
    'use strict';
    const TODAY = new Date();
    let calView  = { y: TODAY.getFullYear(), m: TODAY.getMonth() };
    let selDate  = null;
    let tState   = { start: { h: 9, min: 0, ampm: 'am' }, end: { h: 5, min: 0, ampm: 'pm' } };
    let activeDrop = null;
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const DOWS   = ['Su','Mo','Tu','We','Th','Fr','Sa'];
    const MINS   = [0,5,10,15,20,25,30,35,40,45,50,55];
    function $(id){ return document.getElementById(id); }

    function closeAll() {
        ['nrCalDrop','nrStartDrop','nrEndDrop'].forEach(id=>{const el=$(id);if(el)el.style.display='none';});
        ['nrDateTrigger','nrStartTrigger','nrEndTrigger'].forEach(id=>{const el=$(id);if(el)el.classList.remove('open');});
        activeDrop=null;
    }
    function toggle(dropId,triggerId){
        if(activeDrop===dropId){closeAll();return;}
        closeAll();activeDrop=dropId;
        const drop=$(dropId); drop.style.left='0'; drop.style.right=''; drop.style.display='block'; $(triggerId).classList.add('open');
        const rect=drop.getBoundingClientRect(),vw=window.innerWidth;
        if(rect.right>vw-8){const overflow=rect.right-(vw-8);drop.style.left=Math.max(-rect.left+8,-overflow)+'px';}
    }
    document.addEventListener('click',e=>{if(!e.target.closest('.picker-wrap'))closeAll();});

    function renderCal(){
        const {y,m}=calView;
        const firstDow=new Date(y,m,1).getDay(),daysInM=new Date(y,m+1,0).getDate(),prevTotal=new Date(y,m,0).getDate();
        let html=`<div class="cal-head"><div class="cal-nav-btn" id="_calPrev">&#8249;</div><div class="cal-month-label">${MONTHS[m]} ${y}</div><div class="cal-nav-btn" id="_calNext">&#8250;</div></div><div class="cal-grid">${DOWS.map(d=>`<div class="cal-dow">${d}</div>`).join('')}`;
        for(let i=0;i<firstDow;i++) html+=`<div class="cal-day cal-other">${prevTotal-firstDow+1+i}</div>`;
        for(let d=1;d<=daysInM;d++){const isToday=d===TODAY.getDate()&&m===TODAY.getMonth()&&y===TODAY.getFullYear();const isSel=selDate&&selDate.d===d&&selDate.m===m&&selDate.y===y;html+=`<div class="${['cal-day',isToday&&!isSel?'cal-today':'',isSel?'cal-selected':''].filter(Boolean).join(' ')}" data-d="${d}">${d}</div>`;}
        const trail=(7-(firstDow+daysInM)%7)%7;for(let i=1;i<=trail;i++) html+=`<div class="cal-day cal-other">${i}</div>`;
        html+=`</div><div class="cal-footer"><span class="cal-foot-btn" id="_calClear">Clear</span><span class="cal-foot-btn today" id="_calToday">Today</span></div>`;
        $('nrCalDrop').innerHTML=html;
        $('_calPrev').addEventListener('click',e=>{e.stopPropagation();moveMonth(-1);});
        $('_calNext').addEventListener('click',e=>{e.stopPropagation();moveMonth(1);});
        $('_calClear').addEventListener('click',e=>{e.stopPropagation();clearDate();});
        $('_calToday').addEventListener('click',e=>{e.stopPropagation();gotoToday();});
        $('nrCalDrop').querySelectorAll('.cal-day[data-d]').forEach(el=>el.addEventListener('click',e=>{e.stopPropagation();pickDay(+el.dataset.d);}));
    }
    function moveMonth(dir){calView.m+=dir;if(calView.m>11){calView.m=0;calView.y++;}if(calView.m<0){calView.m=11;calView.y--;}renderCal();}
    function pickDay(d){
        selDate={d,m:calView.m,y:calView.y};
        const iso=`${calView.y}-${String(calView.m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        $('nrResDate').value=iso;
        $('nrDateLabel').textContent=new Date(calView.y,calView.m,d).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
        $('nrDateTrigger').classList.add('has-value');
        renderCal();setTimeout(closeAll,180);
    }
    function clearDate(){selDate=null;$('nrResDate').value='';$('nrDateLabel').textContent='Pick a date';$('nrDateTrigger').classList.remove('has-value');renderCal();}
    function gotoToday(){calView={y:TODAY.getFullYear(),m:TODAY.getMonth()};pickDay(TODAY.getDate());}

    function renderTime(which){
        const dropId=which==='start'?'nrStartDrop':'nrEndDrop';
        const st=tState[which];
        const hItems=Array.from({length:12},(_,i)=>i+1).map(h=>`<div class="tim-item${st.h===h?' sel':''}" data-part="h" data-val="${h}">${String(h).padStart(2,'0')}</div>`).join('');
        const mItems=MINS.map(mn=>`<div class="tim-item${st.min===mn?' sel':''}" data-part="min" data-val="${mn}">${String(mn).padStart(2,'0')}</div>`).join('');
        $(dropId).innerHTML=`<div class="tim-title">Select Time</div><div class="tim-cols"><div class="tim-col" id="_tc_h_${which}">${hItems}</div><div class="tim-sep">:</div><div class="tim-col" id="_tc_m_${which}">${mItems}</div><div class="ampm-col"><div class="ampm-btn${st.ampm==='am'?' sel':''}" data-ampm="am">AM</div><div class="ampm-btn${st.ampm==='pm'?' sel':''}" data-ampm="pm">PM</div></div></div><button class="tim-set-btn" id="_timSet_${which}">Set Time</button>`;
        setTimeout(()=>{const sH=$(dropId).querySelector('#_tc_h_'+which+' .sel');const sM=$(dropId).querySelector('#_tc_m_'+which+' .sel');if(sH)sH.scrollIntoView({block:'center',behavior:'instant'});if(sM)sM.scrollIntoView({block:'center',behavior:'instant'});},0);
        $(dropId).querySelectorAll('.tim-item').forEach(el=>el.addEventListener('click',e=>{e.stopPropagation();tState[which][el.dataset.part]=+el.dataset.val;renderTime(which);}));
        $(dropId).querySelectorAll('.ampm-btn').forEach(el=>el.addEventListener('click',e=>{e.stopPropagation();tState[which].ampm=el.dataset.ampm;renderTime(which);}));
        $(`_timSet_${which}`).addEventListener('click',e=>{e.stopPropagation();applyTime(which);});
    }
    function applyTime(which){
        const st=tState[which];
        const label=`${String(st.h).padStart(2,'0')}:${String(st.min).padStart(2,'0')} ${st.ampm.toUpperCase()}`;
        let h24=st.h;if(st.ampm==='am'&&st.h===12)h24=0;if(st.ampm==='pm'&&st.h!==12)h24=st.h+12;
        const iso24=`${String(h24).padStart(2,'0')}:${String(st.min).padStart(2,'0')}`;
        const labelId=which==='start'?'nrStartLabel':'nrEndLabel';
        const inputId=which==='start'?'nrStartTime':'nrEndTime';
        const triggerId=which==='start'?'nrStartTrigger':'nrEndTrigger';
        $(labelId).textContent=label;$(inputId).value=iso24;$(triggerId).classList.add('has-value');
        closeAll();
    }

    $('nrDateTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('nrCalDrop','nrDateTrigger');if(activeDrop==='nrCalDrop')renderCal();});
    $('nrStartTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('nrStartDrop','nrStartTrigger');if(activeDrop==='nrStartDrop')renderTime('start');});
    $('nrEndTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('nrEndDrop','nrEndTrigger');if(activeDrop==='nrEndDrop')renderTime('end');});

    // Init date to today
    (function(){
        const t=new Date();
        $('nrDateLabel').textContent=t.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
        $('nrDateTrigger').classList.add('has-value');
        selDate={d:t.getDate(),m:t.getMonth(),y:t.getFullYear()};
    })();
})();
</script>

</body>
</html>