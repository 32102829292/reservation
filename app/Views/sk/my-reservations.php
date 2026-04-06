<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | SK Officer</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <style>
        /* ── Page-specific styles only (dark-mode overrides live in app.css) ── */
        .filter-bar {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .search-input {
            width: 100%;
            padding: 10px 12px 10px 34px;
            border-radius: var(--r-sm);
            border: 1px solid rgba(99, 102, 241, .15);
            font-size: .85rem;
            font-family: var(--font);
            background: #f8fafc;
            color: #0f172a;
            transition: all var(--ease);
            outline: none;
        }

        .search-input:focus {
            border-color: #818cf8;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .08);
            border-radius: var(--r-lg);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
            border-left-width: 4px;
            transition: transform var(--ease), box-shadow var(--ease);
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8; }
        .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; font-family: var(--mono); margin-top: 6px; }
        .stat-hint { font-size: .72rem; color: #94a3b8; margin-top: 4px; }

        @media(max-width:639px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .stat-num { font-size: 1.6rem; }
        }

        .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 999px; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 640px; }
        thead th { background: #f8fafc; font-weight: 700; text-transform: uppercase; font-size: .62rem; letter-spacing: .12em; color: #94a3b8; padding: .9rem 1rem; border-bottom: 1px solid rgba(99, 102, 241, .08); white-space: nowrap; }
        td { padding: .875rem 1rem; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background var(--ease); cursor: pointer; }
        tbody tr:hover td { background: var(--indigo-light); }

        .res-card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99, 102, 241, .08); padding: 16px 18px; cursor: pointer; transition: all var(--ease); position: relative; overflow: hidden; box-shadow: var(--shadow-sm); }
        .res-card:hover { border-color: var(--indigo-border); box-shadow: var(--shadow-md); transform: translateY(-1px); }
        .res-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 0 4px 4px 0; }
        .res-card[data-status="pending"]::before   { background: #f59e0b; }
        .res-card[data-status="approved"]::before  { background: #10b981; }
        .res-card[data-status="claimed"]::before   { background: #a855f7; }
        .res-card[data-status="declined"]::before,
        .res-card[data-status="canceled"]::before  { background: #ef4444; }

        .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; }
        .tag-pending  { background: #fef3c7; color: #92400e; }
        .tag-approved { background: #dcfce7; color: #166534; }
        .tag-claimed  { background: #ede9fe; color: #5b21b6; }
        .tag-declined, .tag-canceled { background: #fee2e2; color: #991b1b; }
        .tag-expired  { background: #f1f5f9; color: #475569; }

        .btn-view   { padding: .4rem .8rem; border-radius: 8px; font-size: .72rem; font-weight: 700; cursor: pointer; border: 1px solid rgba(99,102,241,.15); background: var(--indigo-light); color: var(--indigo); font-family: var(--font); transition: all var(--ease); display: inline-flex; align-items: center; gap: 4px; }
        .btn-view:hover { background: var(--indigo); color: white; }
        .btn-cancel { padding: .4rem .8rem; border-radius: 8px; font-size: .72rem; font-weight: 700; cursor: pointer; border: 1px solid #fecdd3; background: #fff1f2; color: #991b1b; font-family: var(--font); transition: all var(--ease); display: inline-flex; align-items: center; gap: 4px; }
        .btn-cancel:hover { background: #fee2e2; }
        .btn-cancel:disabled { opacity: .4; cursor: not-allowed; }

        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 520px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg); }
        .modal-card.sm { max-width: 380px; }

        @media(max-width:479px) { .modal-back { padding: 0; align-items: flex-end; } .modal-card { border-radius: var(--r-xl) var(--r-xl) 0 0; max-height: 92dvh; } .sheet-handle { display: block !important; } }

        .sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 10px auto 4px; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: .65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; flex-shrink: 0; margin-top: 1px; }
        .detail-value { font-weight: 700; color: #0f172a; font-size: .88rem; text-align: right; }

        .alert-banner { border-radius: var(--r-md); padding: 14px 18px; margin-bottom: 16px; border: 1px solid; display: flex; align-items: center; gap: 10px; font-size: .82rem; font-weight: 700; }
        .alert-pending { background: #fef3c7; border-color: #fde68a; color: #92400e; }
        .alert-success { background: #dcfce7; border-color: #86efac; color: #14532d; }
        .alert-error   { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }

        .section-label { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .section-label::before { content: ''; display: inline-block; width: 3px; height: 14px; border-radius: 2px; background: var(--indigo); flex-shrink: 0; }

        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99, 102, 241, .08); box-shadow: var(--shadow-sm); }
        .card-p { padding: 20px 22px; }

        .empty-state { text-align: center; padding: 48px 20px; }

        .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99, 102, 241, .12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }

        .reserve-btn { display: flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--indigo); color: #fff; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); text-decoration: none; box-shadow: 0 4px 12px rgba(55,48,163,.28); }
        .reserve-btn:hover { background: #312e81; transform: translateY(-1px); }

        @keyframes fadeIn  { from { opacity: 0 } to { opacity: 1 } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px) } to { opacity: 1; transform: none } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s ease both; }

        @media(min-width:768px) { #mobileCardList { display: none !important; } #mobileEmpty { display: none !important; } }
        @media(max-width:767px) { #desktopTable { display: none !important; } }

        /* ── Topbar ── */
        .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
        .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .page-title { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1; }
        .page-sub { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500; }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; flex-wrap: wrap; }
        @media(max-width:639px) { .page-title { font-size: 1.35rem; } }

        /* main-area handled by app.css via .l-shell / .main-area */
    </style>
</head>

<body>

    <?php
    $page = 'my-reservations';

    $reservations   = $reservations   ?? [];
    $pendingUserCount = $pendingUserCount ?? 0;
    $usedSlots      = (int)($usedThisMonth ?? 0);
    $maxSlots       = max(1, (int)($maxMonthlySlots ?? 3));
    $quotaPct       = min(100, round($usedSlots / $maxSlots * 100));
    $remainingReservations = $remainingReservations ?? 0;
    $user_name      = $user_name ?? 'Officer';
    $avatarLetter   = strtoupper(mb_substr(trim($user_name), 0, 1));

    $myTotal    = count($reservations);
    $myPending  = count(array_filter($reservations, fn($r) => ($r['status'] ?? '') === 'pending'));
    $myApproved = count(array_filter($reservations, fn($r) => ($r['status'] ?? '') === 'approved' && empty($r['claimed'])));
    $myClaimed  = count(array_filter($reservations, fn($r) => !empty($r['claimed']) && $r['claimed'] == 1));
    $myDeclined = count(array_filter($reservations, fn($r) => in_array($r['status'] ?? '', ['declined', 'canceled'])));
    $approvalRate = $myTotal > 0 ? round(($myApproved + $myClaimed) / $myTotal * 100) : 0;
    ?>

    <!-- ★ Shared layout: sidebar + mobile nav + dark-mode script -->
    <?php include APPPATH . 'Views/partials/sk_layout.php'; ?>

    <!-- ════════ MODALS ════════ -->
    <!-- Details modal -->
    <div id="detailsModal" class="modal-back" onclick="if(event.target===this)closeModal('detailsModal')">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:4px;">
                <p id="modalId" style="font-size:.65rem;font-weight:700;color:#94a3b8;font-family:var(--mono);"></p>
                <button onclick="closeModal('detailsModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-xmark" style="font-size:.75rem;"></i>
                </button>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Reservation Details</h3>
                <span id="modalStatusTag" class="tag"></span>
            </div>
            <div id="modalStatusBar" style="border-radius:var(--r-sm);padding:10px 14px;display:flex;align-items:center;gap:8px;font-size:.82rem;font-weight:700;margin-bottom:16px;"></div>
            <div id="modalBody" style="background:#f8fafc;border-radius:var(--r-md);padding:14px;border:1px solid rgba(99,102,241,.07);margin-bottom:16px;"></div>
            <div style="border:2px dashed var(--indigo-border);border-radius:var(--r-lg);padding:20px;text-align:center;margin-bottom:16px;background:var(--indigo-light);">
                <p style="font-size:.6rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:12px;">E-Ticket / Access QR</p>
                <canvas id="qrCanvas" style="border-radius:12px;display:block;margin:0 auto;"></canvas>
                <p id="qrCodeText" style="font-size:.65rem;color:#94a3b8;font-family:var(--mono);margin-top:8px;word-break:break-all;padding:0 8px;"></p>
                <button onclick="downloadTicket()" style="margin-top:12px;display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);">
                    <i class="fa-solid fa-download" style="font-size:.7rem;"></i> Download E-Ticket
                </button>
            </div>
            <div id="modalActions" style="display:flex;gap:10px;"></div>
        </div>
    </div>

    <!-- Cancel confirm modal -->
    <div id="cancelModal" class="modal-back" onclick="if(event.target===this)closeModal('cancelModal')">
        <div class="modal-card sm">
            <div class="sheet-handle"></div>
            <div style="text-align:center;padding:8px 0 16px;">
                <div style="width:52px;height:52px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.5rem;">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i>
                </div>
                <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;">Cancel Reservation?</h3>
                <p style="font-size:.8rem;color:#94a3b8;margin-top:4px;font-weight:500;">This action cannot be undone.</p>
                <p id="cancelConfirmName" style="font-size:.85rem;font-weight:700;color:#0f172a;margin-top:10px;"></p>
            </div>
            <div style="display:flex;gap:10px;">
                <button onclick="closeModal('cancelModal')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);">Keep it</button>
                <button id="confirmCancelBtn" style="flex:1;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.85rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;">
                    <i class="fa-solid fa-xmark" style="font-size:.75rem;"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>

    <form id="cancelForm" method="POST" action="<?= base_url('sk/decline') ?>" style="display:none">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="cancelId">
    </form>

    <!-- ════════ MAIN ════════ -->
    <main class="main-area">

        <!-- TOPBAR -->
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">SK Officer Portal</div>
                <div class="page-title">My Reservations</div>
                <div class="page-sub">Track and manage your personal reservation requests.</div>
            </div>
            <div class="topbar-right">
                <!-- ★ Use layoutToggleDark() provided by layout.php -->
                <div class="icon-btn" onclick="layoutToggleDark()" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
                </div>
                <a href="/sk/new-reservation" class="reserve-btn">
                    <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> New Reservation
                </a>
            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-banner alert-success fade-up"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-banner alert-error fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if ($myPending > 0): ?>
            <div class="alert-banner alert-pending fade-up">
                <i class="fa-regular fa-clock"></i>
                You have <strong style="font-family:var(--mono);margin:0 4px;"><?= $myPending ?></strong> pending reservation<?= $myPending != 1 ? 's' : '' ?> awaiting approval.
            </div>
        <?php endif; ?>

        <!-- STAT CARDS -->
        <p class="section-label fade-up-1">Overview</p>
        <div class="stats-grid fade-up-1">
            <div class="stat-card" style="border-left-color:var(--indigo);">
                <div class="stat-lbl">Total</div>
                <div class="stat-num" style="color:var(--indigo);"><?= $myTotal ?></div>
                <div class="stat-hint">All time</div>
            </div>
            <div class="stat-card" style="border-left-color:#d97706;">
                <div class="stat-lbl">Pending</div>
                <div class="stat-num" style="color:#d97706;"><?= $myPending ?></div>
                <div class="stat-hint">Awaiting review</div>
            </div>
            <div class="stat-card" style="border-left-color:#16a34a;">
                <div class="stat-lbl">Approved</div>
                <div class="stat-num" style="color:#16a34a;"><?= $myApproved ?></div>
                <div class="prog-bar" style="margin-top:8px;">
                    <div class="prog-fill" style="width:<?= $approvalRate ?>%;background:#16a34a;"></div>
                </div>
                <div class="stat-hint" style="margin-top:4px;">Ready to use</div>
            </div>
            <div class="stat-card" style="border-left-color:#7c3aed;">
                <div class="stat-lbl">Claimed</div>
                <div class="stat-num" style="color:#7c3aed;"><?= $myClaimed ?></div>
                <div class="stat-hint">Tickets used</div>
            </div>
        </div>

        <!-- FILTER BAR -->
        <div class="filter-bar fade-up-1">
            <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap;">
                <div style="position:relative;flex:1;min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.72rem;pointer-events:none;"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by name, asset, date…" oninput="applyFilters()">
                </div>
                <button class="reset-btn" onclick="clearFilters()">
                    <i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Reset
                </button>
            </div>
            <div style="display:flex;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:2px;">
                <button class="qtab active" data-tab="all" onclick="setTab(this,'all')">
                    <i class="fa-solid fa-layer-group" style="font-size:.7rem;"></i> All
                    <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $myTotal ?></span>
                </button>
                <button class="qtab" data-tab="pending" onclick="setTab(this,'pending')">
                    <i class="fa-regular fa-clock" style="font-size:.7rem;"></i> Pending
                    <?php if ($myPending > 0): ?><span style="background:#f59e0b;color:white;font-size:.55rem;font-weight:800;padding:1px 6px;border-radius:999px;"><?= $myPending ?></span><?php endif; ?>
                </button>
                <button class="qtab" data-tab="approved" onclick="setTab(this,'approved')">
                    <i class="fa-solid fa-circle-check" style="font-size:.7rem;"></i> Approved
                </button>
                <button class="qtab" data-tab="claimed" onclick="setTab(this,'claimed')">
                    <i class="fa-solid fa-check-double" style="font-size:.7rem;"></i> Claimed
                </button>
                <button class="qtab" data-tab="declined" onclick="setTab(this,'declined')">
                    <i class="fa-solid fa-xmark" style="font-size:.7rem;"></i> Declined
                </button>
            </div>
            <p id="resultCount" style="font-size:.65rem;font-weight:700;color:#94a3b8;margin-top:10px;"></p>
        </div>

        <!-- DESKTOP TABLE -->
        <p class="section-label fade-up-2">Reservations</p>
        <div class="card fade-up-2" id="desktopTable">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:52px;">ID</th>
                            <th>Name</th>
                            <th>Asset</th>
                            <th>Schedule</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th style="text-align:right;width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservationTableBody">
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa-regular fa-calendar-xmark" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                        <p style="font-weight:800;color:#94a3b8;font-size:1rem;">No reservations yet</p>
                                        <p style="color:#cbd5e1;font-size:.82rem;margin-top:4px;margin-bottom:16px;">Make your first reservation to get started.</p>
                                        <a href="/sk/new-reservation" class="reserve-btn" style="display:inline-flex;">
                                            <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> New Reservation
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res):
                                $status    = $res['status'] ?? 'pending';
                                $isClaimed = !empty($res['claimed']) && in_array($res['claimed'], [true, 1, 't', 'true', '1'], true);
                                if ($isClaimed) $status = 'claimed';
                                $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                                $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                $pcNumbers = '';
                                if (!empty($res['pc_numbers'])) { $decoded = json_decode($res['pc_numbers'], true); $pcNumbers = is_array($decoded) ? implode(', ', $decoded) : $res['pc_numbers']; } elseif (!empty($res['pc_number'])) { $pcNumbers = $res['pc_number']; }
                                $rawDate = $res['reservation_date'] ?? '';
                                $fDate   = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                                $fStart  = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                                $fEnd    = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                                $mdata   = json_encode(['id'=>$res['id'],'status'=>$status,'name'=>$name,'email'=>htmlspecialchars($res['visitor_email']??$res['user_email']??''),'resource'=>$resource,'pc'=>$pcNumbers,'date'=>$fDate,'start'=>$fStart,'end'=>$fEnd,'purpose'=>htmlspecialchars($res['purpose']??'—'),'code'=>htmlspecialchars($res['e_ticket_code']??'SK-'.$res['id'].'-'.($res['reservation_date']??''))]);
                            ?>
                                <tr class="res-row" data-status="<?= $status ?>" data-search="<?= strtolower("$name $resource $rawDate $status") ?>" onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                                    <td><span style="font-size:.65rem;font-weight:700;color:#94a3b8;font-family:var(--mono);">#<?= $res['id'] ?></span></td>
                                    <td>
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $name ?></p>
                                        <?php if (!empty($res['visitor_email']) || !empty($res['user_email'])): ?>
                                            <p style="font-size:.7rem;color:#94a3b8;margin-top:1px;"><?= htmlspecialchars($res['visitor_email'] ?? $res['user_email']) ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= $resource ?></p>
                                        <?php if ($pcNumbers): ?><p style="font-size:.7rem;color:#94a3b8;margin-top:1px;"><i class="fa-solid fa-desktop" style="font-size:.65rem;"></i> <?= htmlspecialchars($pcNumbers) ?></p><?php endif; ?>
                                    </td>
                                    <td>
                                        <p style="font-size:.85rem;font-weight:700;color:#0f172a;"><?= $fDate ?></p>
                                        <p style="font-size:.7rem;color:#6366f1;font-family:var(--mono);margin-top:1px;"><?= $fStart ?> – <?= $fEnd ?></p>
                                    </td>
                                    <td><span style="font-size:.82rem;color:#64748b;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px;"><?= htmlspecialchars($res['purpose'] ?? '—') ?></span></td>
                                    <td><span class="tag tag-<?= $status ?>"><?= ucfirst($status) ?></span></td>
                                    <td style="text-align:right;" onclick="event.stopPropagation()">
                                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap;">
                                            <button onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-view"><i class="fa-solid fa-eye" style="font-size:.65rem;"></i> View</button>
                                            <?php if ($status === 'pending'): ?>
                                                <button onclick="handleCancel(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-cancel"><i class="fa-solid fa-xmark" style="font-size:.65rem;"></i> Cancel</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="noResults" class="hidden" style="text-align:center;padding:40px 20px;">
                <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                <p style="font-size:.85rem;font-weight:700;color:#94a3b8;">No reservations match your search.</p>
            </div>
            <div style="padding:12px 20px;border-top:1px solid rgba(99,102,241,.06);background:#f8fafc;">
                <p id="tableFooter" style="font-size:.65rem;font-weight:700;color:#94a3b8;"></p>
            </div>
        </div>

        <!-- MOBILE CARDS -->
        <div id="mobileCardList" style="display:flex;flex-direction:column;gap:10px;" class="fade-up-2">
            <?php if (empty($reservations)): ?>
                <div class="card card-p" style="text-align:center;padding:48px 20px;">
                    <i class="fa-regular fa-calendar-xmark" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                    <p style="font-weight:800;color:#94a3b8;">No reservations yet</p>
                    <a href="/sk/new-reservation" style="display:inline-flex;align-items:center;gap:6px;margin-top:14px;padding:10px 18px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.82rem;font-weight:700;text-decoration:none;">
                        <i class="fa-solid fa-plus" style="font-size:.75rem;"></i> Make one now
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($reservations as $res):
                    $status    = $res['status'] ?? 'pending';
                    $isClaimed = !empty($res['claimed']) && in_array($res['claimed'], [true, 1, 't', 'true', '1'], true);
                    if ($isClaimed) $status = 'claimed';
                    $name      = htmlspecialchars($res['visitor_name'] ?: ($res['full_name'] ?? 'Guest'));
                    $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                    $pcNumbers = '';
                    if (!empty($res['pc_numbers'])) { $decoded = json_decode($res['pc_numbers'], true); $pcNumbers = is_array($decoded) ? implode(', ', $decoded) : $res['pc_numbers']; } elseif (!empty($res['pc_number'])) { $pcNumbers = $res['pc_number']; }
                    $rawDate = $res['reservation_date'] ?? '';
                    $fDate   = $rawDate ? date('M j, Y', strtotime($rawDate)) : '—';
                    $fStart  = !empty($res['start_time']) ? date('g:i A', strtotime($res['start_time'])) : '—';
                    $fEnd    = !empty($res['end_time'])   ? date('g:i A', strtotime($res['end_time']))   : '—';
                    $mdata   = json_encode(['id'=>$res['id'],'status'=>$status,'name'=>$name,'email'=>htmlspecialchars($res['visitor_email']??$res['user_email']??''),'resource'=>$resource,'pc'=>$pcNumbers,'date'=>$fDate,'start'=>$fStart,'end'=>$fEnd,'purpose'=>htmlspecialchars($res['purpose']??'—'),'code'=>htmlspecialchars($res['e_ticket_code']??'SK-'.$res['id'].'-'.($res['reservation_date']??''))]);
                ?>
                    <div class="res-card" data-status="<?= $status ?>" data-search="<?= strtolower("$name $resource $rawDate $status") ?>" onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)'>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:var(--indigo-light);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--indigo);font-size:.85rem;flex-shrink:0;"><?= mb_strtoupper(mb_substr(strip_tags($name), 0, 1)) ?></div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-weight:700;font-size:.85rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= $name ?></p>
                                <p style="font-size:.7rem;color:#94a3b8;"><?= htmlspecialchars($res['visitor_email'] ?? $res['user_email'] ?? '') ?></p>
                            </div>
                            <span class="tag tag-<?= $status ?>"><?= ucfirst($status) ?></span>
                        </div>
                        <div style="margin-bottom:8px;">
                            <p style="font-size:.78rem;font-weight:700;color:#0f172a;margin-bottom:3px;"><i class="fa-solid fa-desktop" style="color:#94a3b8;font-size:.65rem;margin-right:4px;"></i><?= $resource ?><?= $pcNumbers ? ' · ' . htmlspecialchars($pcNumbers) : '' ?></p>
                            <p style="font-size:.72rem;color:#6366f1;font-family:var(--mono);"><?= $fDate ?> · <?= $fStart ?> – <?= $fEnd ?></p>
                        </div>
                        <p style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:10px;"><?= htmlspecialchars($res['purpose'] ?? '—') ?></p>
                        <?php if ($status === 'pending'): ?>
                            <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid rgba(99,102,241,.07);" onclick="event.stopPropagation()">
                                <button onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-view" style="flex:1;justify-content:center;"><i class="fa-solid fa-eye" style="font-size:.65rem;"></i> View</button>
                                <button onclick="handleCancel(<?= $res['id'] ?>, '<?= addslashes($name) ?>')" class="btn-cancel" style="flex:1;justify-content:center;"><i class="fa-solid fa-xmark" style="font-size:.65rem;"></i> Cancel</button>
                            </div>
                        <?php elseif ($status === 'approved'): ?>
                            <div style="padding-top:10px;border-top:1px solid rgba(99,102,241,.07);" onclick="event.stopPropagation()">
                                <button onclick='openDetailModal(<?= htmlspecialchars($mdata, ENT_QUOTES) ?>)' class="btn-view" style="width:100%;justify-content:center;"><i class="fa-solid fa-download" style="font-size:.65rem;"></i> Get E-Ticket</button>
                            </div>
                        <?php else: ?>
                            <div style="padding-top:8px;border-top:1px solid rgba(99,102,241,.07);"><p style="font-size:.62rem;color:#cbd5e1;font-family:var(--mono);">#<?= $res['id'] ?></p></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="mobileEmpty" style="display:none;" class="card card-p">
            <div style="text-align:center;padding:40px 20px;">
                <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:#e2e8f0;display:block;margin-bottom:10px;"></i>
                <p style="font-size:.85rem;font-weight:700;color:#94a3b8;">No reservations match your search.</p>
            </div>
        </div>

    </main>

    <script>
        const reservationsData = <?= json_encode($reservations ?? []) ?>;
        let cancelTargetId = null;
        let curTab = 'all';
        const allTableRows = Array.from(document.querySelectorAll('#reservationTableBody .res-row'));
        const allCards = Array.from(document.querySelectorAll('#mobileCardList .res-card'));

        /* ── Tabs & Filters ── */
        function setTab(btn, tab) {
            document.querySelectorAll('.qtab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            curTab = tab;
            applyFilters();
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            let n = 0;
            const matches = el => {
                const st = el.dataset.status;
                const matchTab = curTab === 'all' || (curTab === 'declined' && ['declined', 'canceled'].includes(st)) || st === curTab;
                const matchSearch = !q || (el.dataset.search || '').includes(q);
                return matchTab && matchSearch;
            };
            allTableRows.forEach(row => { const show = matches(row); row.style.display = show ? '' : 'none'; if (show) n++; });
            let cardVisible = 0;
            allCards.forEach(card => { const show = matches(card); card.style.display = show ? '' : 'none'; if (show) cardVisible++; });
            const total = allTableRows.length;
            document.getElementById('resultCount').textContent = `Showing ${n} of ${total} reservation${total !== 1 ? 's' : ''}`;
            const tf = document.getElementById('tableFooter'); if (tf) tf.textContent = `${n} result${n !== 1 ? 's' : ''} displayed`;
            const noR = document.getElementById('noResults'); if (noR) noR.classList.toggle('hidden', n > 0);
            const me = document.getElementById('mobileEmpty'); if (allCards.length > 0 && me) me.style.display = cardVisible === 0 ? 'block' : 'none';
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            curTab = 'all';
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === 'all'));
            applyFilters();
        }

        /* ── Detail Modal ── */
        const STATUS_META = {
            pending:  { bg: '#fef3c7', color: '#92400e', icon: 'fa-clock',       label: 'Pending — Awaiting approval' },
            approved: { bg: '#dcfce7', color: '#166534', icon: 'fa-circle-check', label: 'Approved — Download your e-ticket' },
            claimed:  { bg: '#ede9fe', color: '#5b21b6', icon: 'fa-check-double', label: 'Claimed — Ticket successfully scanned' },
            declined: { bg: '#fee2e2', color: '#991b1b', icon: 'fa-ban',          label: 'Declined — Try booking a different time' },
            canceled: { bg: '#fee2e2', color: '#991b1b', icon: 'fa-ban',          label: 'Cancelled' },
            expired:  { bg: '#f1f5f9', color: '#475569', icon: 'fa-hourglass-end', label: 'Expired' },
        };

        function openDetailModal(d) {
            const m = STATUS_META[d.status] || STATUS_META.pending;
            document.getElementById('modalId').textContent = 'Request #' + d.id;
            const tag = document.getElementById('modalStatusTag'); tag.textContent = d.status; tag.className = `tag tag-${d.status}`;
            const bar = document.getElementById('modalStatusBar');
            bar.style.background = m.bg; bar.style.color = m.color;
            bar.innerHTML = `<i class="fa-solid ${m.icon}"></i><span style="font-size:.82rem;">${m.label}</span>`;
            document.getElementById('modalBody').innerHTML = [
                ['Name', d.name], ['Email', d.email || '—'], ['Asset', d.resource + (d.pc ? ' · ' + d.pc : '')],
                ['Date', d.date], ['Time', d.start + ' – ' + d.end], ['Purpose', d.purpose],
            ].map(([l, v]) => `<div class="detail-row"><span class="detail-label">${l}</span><span class="detail-value">${v}</span></div>`).join('');
            QRCode.toCanvas(document.getElementById('qrCanvas'), d.code, { width: 180, margin: 1, color: { dark: '#0f172a', light: '#ffffff' } });
            document.getElementById('qrCodeText').textContent = d.code;
            const acts = document.getElementById('modalActions');
            if (d.status === 'pending') {
                acts.innerHTML = `<button onclick="closeModal('detailsModal');handleCancel(${d.id},'${d.name.replace(/'/g,"\\'")}');" style="flex:1;padding:12px;background:#fee2e2;color:#991b1b;border-radius:var(--r-sm);font-weight:700;border:1px solid #fca5a5;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:6px;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i> Cancel Reservation</button><button onclick="closeModal('detailsModal')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);">Close</button>`;
            } else {
                acts.innerHTML = `<button onclick="closeModal('detailsModal')" style="flex:1;padding:12px;background:#f1f5f9;border-radius:var(--r-sm);font-weight:700;color:#475569;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);">Close</button>`;
            }
            openModal('detailsModal');
        }

        function downloadTicket() {
            const canvas = document.getElementById('qrCanvas'), code = document.getElementById('qrCodeText').textContent;
            const link = document.createElement('a'); link.download = `E-Ticket-${code}.png`; link.href = canvas.toDataURL('image/png'); link.click();
        }

        /* ── Cancel ── */
        function handleCancel(id, name) { cancelTargetId = id; document.getElementById('cancelConfirmName').textContent = name ? `"${name}"` : ''; openModal('cancelModal'); }
        document.getElementById('confirmCancelBtn').addEventListener('click', function () {
            if (!cancelTargetId) return;
            this.disabled = true; this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Canceling…';
            document.getElementById('cancelId').value = cancelTargetId; document.getElementById('cancelForm').submit();
        });

        /* ── Modals ── */
        function openModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal('detailsModal'); closeModal('cancelModal'); } });

        applyFilters();
    </script>
</body>

</html>