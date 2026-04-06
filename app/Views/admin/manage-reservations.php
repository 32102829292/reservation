<?php
$page    = 'manage-reservations';
$sk_name = $sk_name ?? session()->get('name') ?? session()->get('username') ?? 'Administrator';

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

/* pass pending count to layout for nav badge */
$pendingCount = $counts['pending'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage Reservations | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <style>
        /* ── Mobile res cards ── */
        .res-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            padding: 14px 16px;
            cursor: pointer;
            transition: all .15s;
            position: relative;
            overflow: hidden;
        }

        .res-card:hover {
            border-color: var(--indigo-border);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .res-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 0 4px 4px 0;
        }

        .res-card[data-status="pending"]::before {
            background: #fbbf24;
        }

        .res-card[data-status="approved"]::before {
            background: #10b981;
        }

        .res-card[data-status="claimed"]::before {
            background: #a855f7;
        }

        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before {
            background: #ef4444;
        }

        .res-card[data-status="unclaimed"]::before {
            background: #fb923c;
        }

        .res-card[data-status="expired"]::before {
            background: #94a3b8;
        }

        body.dark .res-card {
            background: var(--card);
            border-color: var(--border);
        }

        body.dark .res-card:hover {
            border-color: var(--indigo);
        }

        /* ── Ticket section ── */
        .ticket-section {
            border: 2px dashed var(--indigo-border);
            border-radius: 20px;
            padding: 20px;
            background: var(--indigo-light);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        body.dark .ticket-section {
            background: rgba(99, 102, 241, .08);
            border-color: rgba(99, 102, 241, .3);
        }

        /* ── Print log form ── */
        #dPrintLogForm {
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px 18px;
            margin: 0 24px 14px;
        }

        #dPrintLogForm label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-sub);
            display: block;
            margin-bottom: 6px;
        }

        #dPrintLogForm input[type=number] {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 14px;
            font-family: var(--font);
            color: var(--text);
            background: var(--card);
            outline: none;
        }

        #dPrintLogForm input[type=number]:focus {
            border-color: var(--indigo);
            box-shadow: 0 0 0 3px rgba(67, 56, 202, .1);
        }

        body.dark #dPrintLogForm {
            background: var(--input-bg);
            border-color: var(--border);
        }

        body.dark #dPrintLogForm input[type=number] {
            background: var(--card);
            border-color: var(--border);
            color: var(--text);
        }

        .btn-save-print {
            background: var(--indigo);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            font-family: var(--font);
            transition: all .15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-save-print:hover:not(:disabled) {
            background: #312e81;
        }

        .btn-save-print:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        /* ── Unclaimed banner ── */
        .unclaimed-banner {
            background: var(--orange-bg, #fff7ed);
            border: 1.5px dashed #fdba74;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 24px 14px;
        }

        .ub-icon {
            width: 34px;
            height: 34px;
            background: #fed7aa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c2410c;
            font-size: 13px;
            flex-shrink: 0;
        }

        body.dark .unclaimed-banner {
            background: rgba(251, 146, 60, .1);
            border-color: rgba(251, 146, 60, .3);
        }

        /* ── Print log strip ── */
        #dPrintLog {
            display: none;
            margin: 0 24px 12px;
            border-radius: 18px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            align-items: center;
            gap: 12px;
        }

        body.dark #dPrintLog {
            background: var(--input-bg);
            border-color: var(--border);
        }

        /* ── Auto-refresh indicator ── */
        #autoRefreshIndicator {
            background: rgba(55, 48, 163, .9);
        }

        body.dark #autoRefreshIndicator {
            background: rgba(11, 22, 40, .95);
        }

        /* ── Export button ── */
        .btn-export {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--indigo);
            color: #fff;
            border-radius: var(--r-sm);
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all .15s;
            box-shadow: 0 4px 12px rgba(55, 48, 163, .28);
        }

        .btn-export:hover {
            background: #312e81;
        }

        /* ── Overlay (reservation uses .overlay pattern) ── */
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
            max-width: 520px;
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

        @media(min-width:768px) {
            #mobileCardList {
                display: none !important;
            }

            #mobileEmpty {
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
                <div>
                    <p style="font-weight:800;font-size:13px;color:#c2410c">Not Yet Claimed</p>
                    <p style="font-size:11px;color:#ea580c;font-weight:500;margin-top:2px">Approved but the e-ticket was never scanned.</p>
                </div>
            </div>

            <div style="padding:0 24px 8px">
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-user"></i></div>
                    <div>
                        <p class="dlabel">Requestor</p>
                        <p id="dName" class="dvalue"></p>
                        <p id="dEmail" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-desktop"></i></div>
                    <div>
                        <p class="dlabel">Resource</p>
                        <p id="dResource" class="dvalue"></p>
                        <p id="dPc" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <p class="dlabel">Schedule</p>
                        <p id="dDate" class="dvalue"></p>
                        <p id="dTime" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-pen-to-square"></i></div>
                    <div>
                        <p class="dlabel">Purpose</p>
                        <p id="dPurpose" class="dvalue"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-solid fa-id-badge"></i></div>
                    <div>
                        <p class="dlabel">Visitor Type</p>
                        <p id="dType" class="dvalue"></p>
                    </div>
                </div>
                <div class="drow" id="dApprovedByRow" style="display:none">
                    <div class="dicon" id="dApprovedByIcon"><i class="fa-solid fa-user-check"></i></div>
                    <div>
                        <p class="dlabel" id="dApprovedByLabel">Approved By</p>
                        <p id="dApprovedByName" class="dvalue"></p>
                        <p id="dApprovedByEmail" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p>
                        <p id="dApprovedAt" style="font-size:11px;color:var(--text-sub);font-weight:600;margin-top:2px"></p>
                    </div>
                </div>
                <div class="drow">
                    <div class="dicon"><i class="fa-regular fa-clock"></i></div>
                    <div>
                        <p class="dlabel">Submitted</p>
                        <p id="dCreated" class="dvalue"></p>
                    </div>
                </div>
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
                <div style="flex:1;min-width:0">
                    <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text-sub);margin-bottom:2px">Print Log</p>
                    <p id="dPrintText" style="font-size:13px;font-weight:700;"></p>
                </div>
                <span id="dPrintBadge" style="font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;flex-shrink:0"></span>
            </div>
            <div id="dPrintLogForm">
                <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text-sub);margin-bottom:12px;display:flex;align-items:center;gap:7px"><i class="fa-solid fa-print" style="color:var(--indigo)"></i> Log Print for this Reservation</p>
                <div style="display:flex;align-items:flex-end;gap:10px">
                    <div style="flex:1">
                        <label>Pages Printed <span style="color:var(--text-sub);font-weight:400;text-transform:none;letter-spacing:0">(0 = not printed)</span></label>
                        <input type="number" id="printPagesInput" min="0" max="999" value="0" placeholder="0">
                    </div>
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

    <!-- MAIN -->
    <main class="main-area">
        <div class="fade-up">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:6px">
                <div>
                    <div class="page-eyebrow">Admin Portal</div>
                    <div class="page-title">Manage Reservations</div>
                    <div class="page-sub">
                        <?= $counts['all'] ?> total record<?= $counts['all'] != 1 ? 's' : '' ?>
                        <?php if ($counts['unclaimed'] > 0): ?>
                            · <span style="color:#c2410c;font-weight:700"><?= $counts['unclaimed'] ?> unclaimed</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="icon-btn" onclick="adminToggleDark()"><span id="darkIcon"><i class="fa-regular fa-sun"></i></span></div>
                    <button onclick="exportCSV()" class="btn-export"><i class="fa-solid fa-file-csv"></i> Export CSV</button>
                </div>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="stats-grid fade-up" style="grid-template-columns:repeat(6,minmax(0,1fr));">
            <?php foreach (
                [
                    ['Total',     $counts['all'],       '#3730a3', 'fa-layer-group', 'all'],
                    ['Pending',   $counts['pending'],   '#d97706', 'fa-clock',       'pending'],
                    ['Approved',  $counts['approved'],  '#16a34a', 'fa-circle-check', 'approved'],
                    ['Claimed',   $counts['claimed'],   '#7c3aed', 'fa-check-double', 'claimed'],
                    ['Declined',  $counts['declined'],  '#dc2626', 'fa-xmark-circle', 'declined'],
                    ['Unclaimed', $counts['unclaimed'], '#c2410c', 'fa-ticket',      'unclaimed'],
                ] as [$lbl, $val, $color, $icon, $key]
            ): ?>
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
                <div class="search-wrap" style="flex:1;min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="search-input" oninput="applyFilters()">
                </div>
                <div style="position:relative;width:160px">
                    <i class="fa-regular fa-calendar" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-sub);font-size:11px;pointer-events:none"></i>
                    <input id="dateInput" type="date" class="search-input" style="padding-left:36px;width:100%;" onchange="applyFilters()">
                </div>
                <button onclick="clearFilters()" class="reset-btn"><i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Reset</button>
            </div>
            <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px">
                <button class="qtab active" data-tab="all" onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group" style="font-size:11px"></i> All <span style="font-size:9px;font-weight:800;opacity:.7"><?= $counts['all'] ?></span></button>
                <button class="qtab" data-tab="pending" onclick="setTab(this,'pending')"><i class="fa-solid fa-clock" style="font-size:11px"></i> Pending<?php if ($counts['pending'] > 0): ?><span style="background:#f59e0b;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $counts['pending'] ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="approved" onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check" style="font-size:11px"></i> Approved</button>
                <button class="qtab" data-tab="unclaimed" onclick="setTab(this,'unclaimed')"><i class="fa-solid fa-ticket" style="font-size:11px"></i> Unclaimed<?php if ($counts['unclaimed'] > 0): ?><span style="background:#fb923c;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $counts['unclaimed'] ?></span><?php endif; ?></button>
                <button class="qtab" data-tab="claimed" onclick="setTab(this,'claimed')"><i class="fa-solid fa-check-double" style="font-size:11px"></i> Claimed</button>
                <button class="qtab" data-tab="declined" onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Declined</button>
                <button class="qtab" data-tab="expired" onclick="setTab(this,'expired')"><i class="fa-solid fa-hourglass-end" style="font-size:11px"></i> Expired</button>
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
                        <tr>
                            <td colspan="9">
                                <div style="padding:80px 24px;text-align:center">
                                    <i class="fa-solid fa-calendar-xmark" style="font-size:2.5rem;color:var(--border);display:block;margin-bottom:12px"></i>
                                    <p style="font-weight:800;color:var(--text-sub);font-size:15px">No reservations yet</p>
                                </div>
                            </td>
                        </tr>
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
                            $approvedAt    = !empty($res['updated_at']) && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed'])
                                ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                            $pl          = $printLogMap[(int)$res['id']] ?? null;
                            $plPrinted   = $pl !== null ? (bool)$pl['printed'] : null;
                            $plPages     = $pl ? (int)($pl['pages'] ?? 0) : 0;
                            $plAt        = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                            $isClaimed   = in_array($res['claimed'] ?? false, [true, 1, 't', 'true', '1'], true);
                            $mdata       = json_encode([
                                'id' => $res['id'],
                                'status' => $s,
                                'name' => $name,
                                'email' => $email,
                                'resource' => $resource,
                                'pc' => $pc,
                                'date' => $date,
                                'rawDate' => $rawDate,
                                'start' => $start,
                                'end' => $end,
                                'purpose' => $purpose,
                                'type' => $type,
                                'created' => $created,
                                'code' => $code,
                                'claimed' => $isClaimed,
                                'unclaimed' => $isUnclaimed,
                                'approverName' => $approverName,
                                'approverEmail' => $approverEmail,
                                'approvedAt' => $approvedAt,
                                'plPrinted' => $plPrinted,
                                'plPages' => $plPages,
                                'plAt' => $plAt,
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
                                    <?php if ($approverName && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed'])): ?>
                                        <div style="display:flex;align-items:center;gap:7px">
                                            <div style="width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;flex-shrink:0;<?= $s === 'declined' ? 'background:#fee2e2;color:#dc2626' : 'background:#dcfce7;color:#16a34a' ?>"><?= mb_strtoupper(mb_substr($approverName, 0, 1)) ?></div>
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
                                        <?php elseif ($s === 'unclaimed'): ?>
                                            <span style="font-size:11px;color:#c2410c;font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-ticket" style="font-size:10px"></i> Unclaimed</span>
                                        <?php elseif ($s === 'approved'): ?>
                                            <span style="font-size:11px;color:#16a34a;font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-circle-check" style="font-size:10px"></i> Approved</span>
                                        <?php elseif ($s === 'claimed'): ?>
                                            <span style="font-size:11px;color:#7c3aed;font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-check-double" style="font-size:10px"></i> Claimed</span>
                                        <?php else: ?>
                                            <span style="font-size:11px;color:var(--text-sub);font-style:italic">—</span>
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
                <p style="font-size:11px;color:var(--text-faint);font-weight:600;">Click any row to preview · Export CSV exports current filter</p>
            </div>
        </div>

        <!-- MOBILE CARDS -->
        <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px">
            <?php if (empty($processed)): ?>
                <div class="empty-state"><i class="fa-solid fa-calendar-xmark" style="font-size:2rem;color:var(--border);display:block;margin-bottom:10px"></i>
                    <p style="font-weight:800;color:var(--text-sub)">No reservations yet</p>
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
                    $approvedAt    = !empty($res['updated_at']) && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed'])
                        ? date('M j, Y · g:i A', strtotime($res['updated_at'])) : '';
                    $pl          = $printLogMap[(int)$res['id']] ?? null;
                    $plPrinted   = $pl !== null ? (bool)$pl['printed'] : null;
                    $plPages     = $pl ? (int)($pl['pages'] ?? 0) : 0;
                    $plAt        = ($pl && !empty($pl['printed_at'])) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '';
                    $isClaimed   = in_array($res['claimed'] ?? false, [true, 1, 't', 'true', '1'], true);
                    $mdata       = json_encode([
                        'id' => $res['id'],
                        'status' => $s,
                        'name' => $name,
                        'email' => $email,
                        'resource' => $resource,
                        'pc' => $pc,
                        'date' => $date,
                        'rawDate' => $rawDate,
                        'start' => $start,
                        'end' => $end,
                        'purpose' => $purpose,
                        'type' => $type,
                        'created' => $created,
                        'code' => $code,
                        'claimed' => $isClaimed,
                        'unclaimed' => $isUnclaimed,
                        'approverName' => $approverName,
                        'approverEmail' => $approverEmail,
                        'approvedAt' => $approvedAt,
                        'plPrinted' => $plPrinted,
                        'plPages' => $plPages,
                        'plAt' => $plAt,
                    ]);
                    $avatarBg = ['pending' => 'background:#fef3c7;color:#92400e', 'approved' => 'background:#dcfce7;color:#166534', 'claimed' => 'background:#ede9fe;color:#6b21a8', 'declined' => 'background:#fee2e2;color:#991b1b', 'canceled' => 'background:#fee2e2;color:#991b1b', 'expired' => 'background:#f1f5f9;color:#64748b', 'unclaimed' => 'background:#fff7ed;color:#c2410c'][$s] ?? 'background:#f1f5f9;color:#64748b';
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
                            <div style="width:38px;height:38px;border-radius:14px;<?= $avatarBg ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0"><?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?></div>
                            <div style="flex:1;min-width:0">
                                <p style="font-weight:700;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                                <?php if ($email): ?><p style="font-size:11px;color:var(--text-sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p><?php endif; ?>
                            </div>
                            <span class="badge badge-<?= $s ?>" style="flex-shrink:0"><i class="fa-solid <?= $icon ?>" style="font-size:9px"></i><?= ucfirst($s) ?></span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
                            <div style="flex:1;min-width:0">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                                    <i class="fa-solid fa-desktop" style="font-size:10px;color:var(--text-sub);flex-shrink:0"></i>
                                    <p style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $resource ?><?= $pc ? ' · ' . $pc : '' ?></p>
                                </div>
                                <div style="display:flex;align-items:center;gap:6px">
                                    <i class="fa-regular fa-calendar" style="font-size:10px;color:var(--text-sub);flex-shrink:0"></i>
                                    <p style="font-size:11px;color:var(--text-muted);font-weight:600"><?= $date ?></p>
                                    <span style="font-size:10px;color:var(--indigo);font-weight:700"><?= $start ?> – <?= $end ?></span>
                                </div>
                            </div>
                            <div class="card-print-pill" style="flex-shrink:0">
                                <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> <?= $plPages ?>pg</span>
                                <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span><?php endif; ?>
                            </div>
                        </div>
                        <p style="font-size:11px;color:var(--text-sub);font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:10px"><?= $purpose ?></p>
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding-top:10px;border-top:1px solid var(--border-subtle)">
                            <div style="display:flex;align-items:center;gap:6px;min-width:0">
                                <?php if ($approverName && in_array($s, ['approved', 'claimed', 'declined', 'expired', 'unclaimed'])): ?>
                                    <div style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;flex-shrink:0;<?= $s === 'declined' ? 'background:#fee2e2;color:#dc2626' : 'background:#dcfce7;color:#16a34a' ?>"><?= mb_strtoupper(mb_substr($approverName, 0, 1)) ?></div>
                                    <p style="font-size:10px;color:var(--text-muted);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $s === 'declined' ? 'Declined' : 'Approved' ?> by <?= $approverName ?></p>
                                <?php else: ?>
                                    <p style="font-size:10px;color:var(--border);font-weight:600">#<?= $res['id'] ?></p>
                                <?php endif; ?>
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
        const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
        const allCards = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
        let curTab = 'all';
        let approveTargetId = null,
            declineTargetId = null;

        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        let csrfName = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content') ?? 'csrf_token';

        function refreshCsrf(data) {
            if (data.csrf_hash && data.csrf_token) {
                csrfToken = data.csrf_hash;
                csrfName = data.csrf_token;
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', csrfToken);
                document.querySelector('meta[name="csrf-name"]')?.setAttribute('content', csrfName);
            }
        }

        const printLogMap = {};
        <?php foreach ($printLogMap as $resId => $pl): ?>
            printLogMap[<?= (int)$resId ?>] = {
                printed: <?= isset($pl['printed']) ? (in_array($pl['printed'], [true, 1, 't', 'true', '1'], true) ? 'true' : 'false') : 'false' ?>,
                pages: <?= (int)($pl['pages'] ?? 0) ?>,
                at: "<?= !empty($pl['printed_at']) ? date('M j · g:i A', strtotime($pl['printed_at'])) : '' ?>"
            };
        <?php endforeach; ?>

        let _currentReservationId = null;

        async function savePrintLog() {
            const rid = _currentReservationId,
                pages = parseInt(document.getElementById('printPagesInput').value, 10) || 0;
            const btn = document.getElementById('savePrintBtn'),
                msg = document.getElementById('printSaveMsg');
            if (!rid) {
                msg.textContent = 'No reservation selected.';
                msg.style.color = '#dc2626';
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:11px"></i> Saving…';
            msg.textContent = '';
            const body = new FormData();
            body.append(csrfName, csrfToken);
            body.append('reservation_id', rid);
            body.append('printed', pages > 0 ? 1 : 0);
            body.append('pages', pages);
            try {
                const res = await fetch('<?= base_url('admin/log-print') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body
                });
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch {
                    throw new Error(`Server error (${res.status})`);
                }
                if (data.ok) {
                    refreshCsrf(data);
                    const now = new Date();
                    const fmt = now.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    }) + ' · ' + now.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit'
                    });
                    printLogMap[rid] = {
                        printed: pages > 0,
                        pages,
                        at: fmt
                    };
                    refreshPrintLogStrip(rid);
                    refreshBothPrintCells(rid, pages);
                    msg.textContent = pages > 0 ? `✓ Saved — ${pages} page${pages!==1?'s':''} printed` : '✓ Saved — no printing logged';
                    msg.style.color = '#16a34a';
                    btn.innerHTML = '<i class="fa-solid fa-check" style="font-size:11px"></i> Saved';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';
                    }, 2000);
                } else {
                    throw new Error(data.error ?? 'Unknown error');
                }
            } catch (err) {
                msg.textContent = '✗ Failed: ' + err.message;
                msg.style.color = '#dc2626';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';
            }
        }

        function refreshPrintLogStrip(rid) {
            const plog = printLogMap[rid],
                logEl = document.getElementById('dPrintLog');
            if (!plog) {
                logEl.style.display = 'none';
                return;
            }
            logEl.style.display = 'flex';
            const logText = document.getElementById('dPrintText'),
                logBadge = document.getElementById('dPrintBadge');
            if (plog.printed) {
                logText.textContent = `Printed ${plog.pages} page${plog.pages!==1?'s':''}${plog.at?' · '+plog.at:''}`;
                logBadge.textContent = `${plog.pages}pg`;
                logBadge.style.cssText = 'background:#dcfce7;color:#16a34a';
            } else {
                logText.textContent = 'No printing during this session';
                logBadge.textContent = 'No print';
                logBadge.style.cssText = 'background:#f1f5f9;color:#64748b';
            }
        }

        function refreshBothPrintCells(rid, pages) {
            allTableRows.forEach(row => {
                if (row.dataset.id == rid) {
                    const cell = row.cells[7];
                    if (pages > 0) {
                        cell.innerHTML = `<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> ${pages}pg</span>`;
                        row.dataset.plPrinted = 'Yes';
                        row.dataset.plPages = pages;
                    } else {
                        cell.innerHTML = `<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>`;
                        row.dataset.plPrinted = 'No';
                        row.dataset.plPages = '';
                    }
                }
            });
            allCards.forEach(card => {
                if (card.dataset.id == rid) {
                    const w = card.querySelector('.card-print-pill');
                    if (w) {
                        w.innerHTML = pages > 0 ? `<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> ${pages}pg</span>` : `<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>`;
                    }
                    card.dataset.plPrinted = pages > 0 ? 'Yes' : 'No';
                    card.dataset.plPages = pages > 0 ? pages : '';
                }
            });
        }

        function exportCSV() {
            const visibleRows = allTableRows.filter(r => r.style.display !== 'none');
            const headers = ['ID', 'User Name', 'Email', 'Resource Name', 'PC Number', 'Date', 'Start Time', 'End Time', 'Purpose', 'Visitor Type', 'Status', 'Approved By', 'Approved At', 'Printed', 'Pages Printed', 'Submitted At'];
            const escape = v => {
                const s = String(v ?? '');
                return s.includes(',') || s.includes('"') || s.includes('\n') ? '"' + s.replace(/"/g, '""') + '"' : s;
            };
            const lines = [headers.map(escape).join(',')];
            visibleRows.forEach(row => {
                try {
                    const d = JSON.parse(row.getAttribute('onclick').replace(/^openDetail\(/, '').replace(/\)$/, ''));
                    lines.push([d.id ?? '', d.name ?? '', d.email ?? '', d.resource ?? '', d.pc ?? '', d.date ?? '', d.start ?? '', d.end ?? '', d.purpose ?? '', d.type ?? '', d.status ?? '', d.approverName ?? '', d.approvedAt ?? '', row.dataset.plPrinted ?? '', row.dataset.plPages ?? '', d.created ?? ''].map(escape).join(','));
                } catch (e) {}
            });
            const blob = new Blob([lines.join('\r\n')], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `admin-reservations-${new Date().toISOString().slice(0,10)}.csv`;
            a.click();
            URL.revokeObjectURL(url);
        }

        function setTab(btn, tab) {
            document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            curTab = tab;
            syncCards(tab);
            applyFilters();
        }

        function filterByStatus(tab) {
            curTab = tab;
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
            syncCards(tab);
            applyFilters();
        }

        function syncCards(tab) {
            document.querySelectorAll('[data-filter]').forEach(c => c.classList.toggle('ring', c.dataset.filter === tab));
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const date = document.getElementById('dateInput').value;
            const matchesFilters = el => {
                let matchTab;
                if (curTab === 'all') matchTab = true;
                else if (curTab === 'declined') matchTab = ['declined', 'canceled'].includes(el.dataset.status);
                else matchTab = el.dataset.status === curTab;
                return matchTab && (!q || el.dataset.search.includes(q)) && (!date || el.dataset.date === date);
            };
            let n = 0;
            allTableRows.forEach(row => {
                const show = matchesFilters(row);
                row.style.display = show ? '' : 'none';
                if (show) n++;
            });
            let m = 0;
            allCards.forEach(card => {
                const show = matchesFilters(card);
                card.style.display = show ? '' : 'none';
                if (show) m++;
            });
            if (allCards.length > 0) document.getElementById('mobileEmpty').style.display = m === 0 ? 'block' : 'none';
            const total = allTableRows.length;
            document.getElementById('resultCount').textContent = `Showing ${n} of ${total} reservation${total!==1?'s':''}`;
            document.getElementById('tableFooter').textContent = `${n} result${n!==1?'s':''} displayed`;
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('dateInput').value = '';
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
                if (ic) ic.className = `fa-solid ${i===col?(sortDir[col]?'fa-sort-up':'fa-sort-down'):'fa-sort'} sort-icon`;
            });
        }

        const STATUS_META = {
            pending: {
                icon: 'fa-clock',
                bg: '#fef3c7',
                color: '#92400e',
                label: 'Pending — Awaiting approval'
            },
            approved: {
                icon: 'fa-circle-check',
                bg: '#dcfce7',
                color: '#166534',
                label: 'Approved'
            },
            claimed: {
                icon: 'fa-check-double',
                bg: '#f3e8ff',
                color: '#6b21a8',
                label: 'Claimed — Ticket used'
            },
            declined: {
                icon: 'fa-xmark-circle',
                bg: '#fee2e2',
                color: '#991b1b',
                label: 'Declined'
            },
            canceled: {
                icon: 'fa-ban',
                bg: '#fee2e2',
                color: '#991b1b',
                label: 'Cancelled'
            },
            expired: {
                icon: 'fa-hourglass-end',
                bg: '#f1f5f9',
                color: '#475569',
                label: 'Expired — Was never approved'
            },
            unclaimed: {
                icon: 'fa-ticket',
                bg: '#fff7ed',
                color: '#c2410c',
                label: 'Unclaimed — Approved but did not show up'
            },
        };

        function openDetail(d) {
            _currentReservationId = d.id;
            const plog = printLogMap[d.id];
            document.getElementById('printPagesInput').value = plog ? (plog.printed ? plog.pages : 0) : 0;
            document.getElementById('printSaveMsg').textContent = '';
            const saveBtn = document.getElementById('savePrintBtn');
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';

            const m = STATUS_META[d.status] || STATUS_META.pending;
            document.getElementById('dId').textContent = 'Reservation #' + d.id;
            document.getElementById('dName').textContent = d.name;
            document.getElementById('dEmail').textContent = d.email;
            document.getElementById('dResource').textContent = d.resource;
            document.getElementById('dPc').textContent = d.pc ? 'PC: ' + d.pc : '';
            document.getElementById('dDate').textContent = d.date;
            document.getElementById('dTime').textContent = d.start + ' – ' + d.end;
            document.getElementById('dPurpose').textContent = d.purpose;
            document.getElementById('dType').textContent = d.type;
            document.getElementById('dCreated').textContent = d.created;

            const approverRow = document.getElementById('dApprovedByRow');
            if (d.approverName && ['approved', 'claimed', 'declined', 'expired', 'unclaimed'].includes(d.status)) {
                approverRow.style.display = 'flex';
                const isDeclined = d.status === 'declined';
                document.getElementById('dApprovedByLabel').textContent = isDeclined ? 'Declined By' : 'Approved By';
                const iconEl = document.getElementById('dApprovedByIcon');
                iconEl.className = 'dicon';
                iconEl.style.background = isDeclined ? '#fee2e2' : '#dcfce7';
                iconEl.style.color = isDeclined ? '#dc2626' : '#16a34a';
                iconEl.innerHTML = `<i class="fa-solid ${isDeclined?'fa-user-xmark':'fa-user-check'}"></i>`;
                document.getElementById('dApprovedByName').textContent = d.approverName;
                document.getElementById('dApprovedByEmail').textContent = d.approverEmail || '';
                document.getElementById('dApprovedAt').textContent = d.approvedAt ? `on ${d.approvedAt}` : '';
            } else {
                approverRow.style.display = 'none';
            }

            const bar = document.getElementById('dStatusBar');
            bar.style.background = m.bg;
            bar.style.color = m.color;
            bar.innerHTML = `<i class="fa-solid ${m.icon}"></i> <span style="font-weight:700">${m.label}</span>`;

            document.getElementById('dUnclaimedBanner').style.display = d.unclaimed ? 'flex' : 'none';

            const qrSec = document.getElementById('dQr'),
                clSec = document.getElementById('dClaimed');
            if (d.claimed || d.status === 'claimed') {
                qrSec.style.display = 'none';
                clSec.style.display = 'block';
            } else if (d.status === 'approved' || d.status === 'unclaimed') {
                clSec.style.display = 'none';
                qrSec.style.display = 'flex';
                QRCode.toCanvas(document.getElementById('qrCanvas'), d.code, {
                    width: 150,
                    margin: 1,
                    color: {
                        dark: '#1e293b',
                        light: '#ffffff'
                    }
                });
                document.getElementById('dTicketCode').textContent = d.code;
            } else {
                qrSec.style.display = 'none';
                clSec.style.display = 'none';
            }

            refreshPrintLogStrip(d.id);

            const acts = document.getElementById('dActions');
            if (d.status === 'pending') {
                acts.innerHTML = `<button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button><button onclick="triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>`;
            } else {
                acts.innerHTML = `<button onclick="closeModal('detail')" class="btn-cancel" style="width:100%"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Close</button>`;
            }
            document.getElementById('detailModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function downloadTicket() {
            const canvas = document.getElementById('qrCanvas'),
                code = document.getElementById('dTicketCode').textContent;
            const link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        function triggerApprove(id, name) {
            approveTargetId = id;
            document.getElementById('approveConfirmName').textContent = name ? `"${name}"` : '';
            openModal('approve');
        }

        function triggerDecline(id, name) {
            declineTargetId = id;
            document.getElementById('declineConfirmName').textContent = name ? `"${name}"` : '';
            openModal('decline');
        }

        document.getElementById('confirmApproveBtn').addEventListener('click', function() {
            if (!approveTargetId) return;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
            document.getElementById('approveId').value = approveTargetId;
            document.getElementById('approveForm').submit();
        });
        document.getElementById('confirmDeclineBtn').addEventListener('click', function() {
            if (!declineTargetId) return;
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Declining…';
            document.getElementById('declineId').value = declineTargetId;
            document.getElementById('declineForm').submit();
        });

        const modalIds = {
            detail: 'detailModal',
            approve: 'approveModal',
            decline: 'declineModal'
        };

        function openModal(key) {
            const el = document.getElementById(modalIds[key]);
            if (el) {
                el.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(key) {
            const el = document.getElementById(modalIds[key]);
            if (el) {
                el.classList.remove('open');
                document.body.style.overflow = '';
            }
            if (key === 'detail') _currentReservationId = null;
            if (key === 'approve') {
                const b = document.getElementById('confirmApproveBtn');
                b.disabled = false;
                b.innerHTML = '<i class="fa-solid fa-check"></i> Approve';
            }
            if (key === 'decline') {
                const b = document.getElementById('confirmDeclineBtn');
                b.disabled = false;
                b.innerHTML = '<i class="fa-solid fa-xmark"></i> Decline';
            }
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('detail');
                closeModal('approve');
                closeModal('decline');
            }
        });

        applyFilters();

        /* Auto-refresh */
        const AUTO_REFRESH_INTERVAL = 30;
        let autoRefreshTimer = null,
            countdownTimer = null,
            secondsLeft = AUTO_REFRESH_INTERVAL,
            refreshPaused = false;
        const refreshIndicator = document.createElement('div');
        refreshIndicator.id = 'autoRefreshIndicator';
        refreshIndicator.style.cssText = 'position:fixed;bottom:calc(90px + env(safe-area-inset-bottom,16px));right:16px;backdrop-filter:blur(8px);color:white;font-family:var(--font);font-size:11px;font-weight:700;padding:6px 12px;border-radius:999px;z-index:90;display:flex;align-items:center;gap:6px;box-shadow:0 4px 12px rgba(55,48,163,.3);cursor:pointer;';
        refreshIndicator.title = 'Click to refresh now';
        refreshIndicator.innerHTML = `<span id="refreshDot" style="width:7px;height:7px;border-radius:50%;background:#4ade80;display:inline-block"></span><span id="refreshCountdown">Refresh in ${AUTO_REFRESH_INTERVAL}s</span>`;
        document.body.appendChild(refreshIndicator);
        refreshIndicator.addEventListener('click', () => doAutoRefresh(true));

        function updateCountdown() {
            const el = document.getElementById('refreshCountdown'),
                dot = document.getElementById('refreshDot');
            if (!el) return;
            if (refreshPaused) {
                el.textContent = 'Refresh paused';
                dot.style.background = '#fbbf24';
            } else {
                el.textContent = `Refresh in ${secondsLeft}s`;
                dot.style.background = '#4ade80';
            }
        }

        function startCountdown() {
            clearInterval(countdownTimer);
            secondsLeft = AUTO_REFRESH_INTERVAL;
            updateCountdown();
            countdownTimer = setInterval(() => {
                if (!refreshPaused) {
                    secondsLeft--;
                    if (secondsLeft <= 0) secondsLeft = AUTO_REFRESH_INTERVAL;
                }
                updateCountdown();
            }, 1000);
        }
        async function doAutoRefresh(force = false) {
            const anyOpen = document.querySelector('.overlay.open');
            if (anyOpen && !force) return;
            const search = document.getElementById('searchInput'),
                date = document.getElementById('dateInput');
            if (!force && (document.activeElement === search || document.activeElement === date)) return;
            try {
                const dot = document.getElementById('refreshDot'),
                    el = document.getElementById('refreshCountdown');
                if (dot) dot.style.background = '#818cf8';
                if (el) el.textContent = 'Refreshing…';
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    credentials: 'same-origin'
                });
                if (!response.ok) throw new Error('HTTP ' + response.status);
                const html = await response.text();
                const parser = new DOMParser(),
                    newDoc = parser.parseFromString(html, 'text/html');
                const newTbody = newDoc.querySelector('#tableBody'),
                    oldTbody = document.querySelector('#tableBody');
                if (newTbody && oldTbody) oldTbody.innerHTML = newTbody.innerHTML;
                const newCards = newDoc.querySelector('#mobileCardList'),
                    oldCards = document.querySelector('#mobileCardList');
                if (newCards && oldCards) oldCards.innerHTML = newCards.innerHTML;
                allTableRows.length = 0;
                document.querySelectorAll('#tableBody .res-row').forEach(r => allTableRows.push(r));
                allCards.length = 0;
                document.querySelectorAll('#mobileCardList .res-card').forEach(c => allCards.push(c));
                applyFilters();
                secondsLeft = AUTO_REFRESH_INTERVAL;
                updateCountdown();
                if (dot) dot.style.background = '#4ade80';
            } catch (err) {
                console.warn('Auto-refresh failed:', err.message);
                const dot = document.getElementById('refreshDot');
                if (dot) {
                    dot.style.background = '#f87171';
                    setTimeout(() => {
                        if (dot) dot.style.background = '#4ade80';
                    }, 3000);
                }
            }
        }
        const observer = new MutationObserver(() => {
            refreshPaused = !!document.querySelector('.overlay.open');
            updateCountdown();
        });
        document.querySelectorAll('.overlay').forEach(el => observer.observe(el, {
            attributes: true,
            attributeFilter: ['class']
        }));
        ['searchInput', 'dateInput'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('focus', () => {
                refreshPaused = true;
                updateCountdown();
            });
            el.addEventListener('blur', () => {
                refreshPaused = !!document.querySelector('.overlay.open');
                updateCountdown();
            });
        });
        autoRefreshTimer = setInterval(() => doAutoRefresh(), AUTO_REFRESH_INTERVAL * 1000);
        startCountdown();
    </script>
</body>

</html>