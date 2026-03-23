<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Reservations | <?= esc($user_name ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <style>
        :root {
            --green:     #16a34a;
            --green-lt:  #dcfce7;
            --green-dk:  #14532d;
            --ink:       #0f1923;
            --ink-2:     #2d3748;
            --muted:     #64748b;
            --muted-lt:  #94a3b8;
            --border:    #e2e8f0;
            --surface:   #f8fafc;
            --white:     #ffffff;
            --red:       #ef4444;
            --amber:     #f59e0b;
            --purple:    #a855f7;
            --radius-card: 28px;
            --radius-btn:  14px;
            --shadow-card: 0 1px 3px rgba(0,0,0,.06), 0 8px 24px rgba(0,0,0,.04);
            --shadow-pop:  0 24px 64px rgba(0,0,0,.18);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--surface);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 280px;
            flex-shrink: 0;
            padding: 24px 16px;
            display: none;
        }
        @media (min-width: 1024px) { .sidebar { display: flex; flex-direction: column; } }

        .sidebar-inner {
            background: var(--white);
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }

        .sidebar-head {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .sidebar-brand {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--green);
            margin-bottom: 2px;
        }
        .sidebar-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--ink);
            line-height: 1.1;
        }
        .sidebar-title span { color: var(--green); }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 14px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            transition: all .18s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--surface); color: var(--green); }
        .nav-item.active {
            background: var(--green);
            color: white;
            box-shadow: 0 6px 18px -3px rgba(22,163,74,.35);
        }
        .nav-item i { width: 18px; text-align: center; font-size: .95rem; }

        .sidebar-foot {
            padding: 14px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 14px;
            font-size: .82rem;
            font-weight: 700;
            color: var(--red);
            text-decoration: none;
            transition: all .18s;
        }
        .logout-btn:hover { background: #fef2f2; }

        /* ── Mobile Nav ── */
        .mobile-nav {
            position: fixed;
            bottom: calc(16px + env(safe-area-inset-bottom, 0px));
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 32px);
            max-width: 560px;
            background: rgba(15, 25, 35, .96);
            backdrop-filter: blur(16px);
            border-radius: 22px;
            padding: 6px;
            z-index: 100;
            box-shadow: 0 16px 40px rgba(0,0,0,.3);
            display: flex;
        }
        @media (min-width: 1024px) { .mobile-nav { display: none; } }

        .mobile-nav-scroll {
            display: flex;
            gap: 3px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }
        .mobile-nav-scroll::-webkit-scrollbar { display: none; }

        .mob-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            min-width: 68px;
            border-radius: 16px;
            text-decoration: none;
            color: rgba(255,255,255,.55);
            transition: all .18s;
            flex-shrink: 0;
            gap: 3px;
        }
        .mob-nav-item.active { background: var(--green); color: white; }
        .mob-nav-item:not(.active):hover { color: white; background: rgba(255,255,255,.08); }
        .mob-nav-item i { font-size: 1.05rem; }
        .mob-nav-item span { font-size: .62rem; font-weight: 600; white-space: nowrap; }

        /* ── Main ── */
        main {
            flex: 1;
            min-width: 0;
            padding: 24px 20px 120px;
        }
        @media (min-width: 1024px) { main { padding: 32px 40px 60px; } }

        /* ── Page header ── */
        .page-header {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 28px;
            animation: fadeUp .4s ease both;
        }
        @media (min-width: 640px) {
            .page-header { flex-direction: row; align-items: flex-start; justify-content: space-between; }
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--ink);
            letter-spacing: -.02em;
            line-height: 1.1;
        }
        @media (min-width: 1024px) { .page-title { font-size: 2rem; } }

        .page-subtitle { font-size: .82rem; color: var(--muted); font-weight: 500; margin-top: 3px; }

        .header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        .quota-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
        }

        .count-chip {
            text-align: right;
        }
        .count-chip .label { font-size: .6rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--muted-lt); }
        .count-chip .value { font-size: 1.4rem; font-weight: 800; color: var(--green); line-height: 1; }

        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            background: var(--green);
            color: white;
            border-radius: var(--radius-btn);
            font-size: .82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .18s;
            box-shadow: 0 4px 14px -2px rgba(22,163,74,.4);
            white-space: nowrap;
        }
        .btn-new:hover { background: #15803d; transform: translateY(-1px); box-shadow: 0 6px 20px -2px rgba(22,163,74,.5); }

        /* ── Flash messages ── */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 16px;
            font-size: .82rem;
            font-weight: 600;
            margin-bottom: 20px;
            animation: fadeUp .3s ease both;
        }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 14px 16px;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: var(--shadow-card);
            animation: fadeUp .4s .05s ease both;
        }
        @media (min-width: 640px) { .filter-bar { flex-direction: row; align-items: center; } }

        .search-wrap {
            position: relative;
            flex: 1;
        }
        .search-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-lt);
            font-size: .8rem;
            pointer-events: none;
        }
        .search-input {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 9px 14px 9px 36px;
            font-size: .82rem;
            font-family: 'Sora', sans-serif;
            color: var(--ink);
            width: 100%;
            transition: all .2s;
        }
        .search-input:focus { outline: none; border-color: var(--green); background: var(--white); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
        .search-input::placeholder { color: var(--muted-lt); }

        .status-select {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 9px 14px;
            font-size: .82rem;
            font-family: 'Sora', sans-serif;
            color: var(--ink);
            width: 100%;
            transition: all .2s;
            cursor: pointer;
        }
        @media (min-width: 640px) { .status-select { width: 160px; flex-shrink: 0; } }
        .status-select:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(22,163,74,.1); }

        .result-hint { font-size: .72rem; font-weight: 600; color: var(--muted-lt); padding: 0 4px; margin-bottom: 12px; }

        /* ── Desktop table ── */
        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-card);
            margin-bottom: 16px;
            animation: fadeUp .4s .1s ease both;
        }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        table { width: 100%; border-collapse: collapse; min-width: 500px; }

        thead th {
            background: var(--surface);
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--muted-lt);
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            text-align: left;
        }

        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }

        .res-row { transition: background .12s; cursor: pointer; }
        .res-row:hover td { background: #f0fdf4; }
        .res-row[data-status="declined"] td,
        .res-row[data-status="cancelled"] td { opacity: .55; }

        .table-foot {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-foot-txt { font-size: .7rem; font-weight: 600; color: var(--muted-lt); }

        /* ── Status badges ── */
        .s-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            white-space: nowrap;
        }
        .s-pending   { background: #fef3c7; color: #92400e; }
        .s-approved  { background: #dcfce7; color: #166534; }
        .s-declined,
        .s-cancelled { background: #fee2e2; color: #991b1b; }
        .s-claimed   { background: #f3e8ff; color: #6b21a8; }
        .s-expired   { background: #f1f5f9; color: #475569; }

        /* ── Action buttons in table ── */
        .tbl-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            transition: all .15s;
        }
        .tbl-btn-view { background: var(--surface); color: var(--muted); }
        .tbl-btn-view:hover { background: var(--border); color: var(--ink); }
        .tbl-btn-cancel { background: #fef2f2; color: var(--red); }
        .tbl-btn-cancel:hover { background: #fee2e2; }

        /* ── Mobile cards ── */
        .cards-wrap { display: flex; flex-direction: column; gap: 10px; animation: fadeUp .4s .1s ease both; }

        .m-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            padding: 15px 16px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all .18s;
        }
        .m-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
        }
        .m-card[data-status="pending"]::before   { background: var(--amber); }
        .m-card[data-status="approved"]::before  { background: var(--green); }
        .m-card[data-status="claimed"]::before   { background: var(--purple); }
        .m-card[data-status="declined"]::before,
        .m-card[data-status="cancelled"]::before { background: var(--red); }
        .m-card[data-status="expired"]::before   { background: var(--muted-lt); }
        .m-card:hover { border-color: #bbf7d0; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.1); }
        .m-card[data-status="declined"],
        .m-card[data-status="cancelled"] { opacity: .65; }

        /* ── Empty / no-results ── */
        .empty-box {
            padding: 56px 24px;
            text-align: center;
            color: var(--muted-lt);
        }
        .empty-box i { font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: .35; }
        .empty-box p { font-weight: 700; color: var(--muted); font-size: .9rem; }
        .empty-box .sub { font-size: .78rem; margin-top: 4px; font-weight: 500; }

        .no-results-card {
            background: var(--white);
            border: 1px dashed var(--border);
            border-radius: 20px;
            padding: 40px 24px;
            text-align: center;
        }

        /* ── Overlays ── */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 300;
            align-items: center;
            justify-content: center;
        }
        .overlay.show { display: flex; }

        .overlay-bg {
            position: absolute;
            inset: 0;
            background: rgba(10, 18, 28, .65);
            backdrop-filter: blur(8px);
            animation: bgIn .18s ease both;
        }
        @keyframes bgIn { from{opacity:0} to{opacity:1} }

        .modal {
            position: relative;
            background: var(--white);
            border-radius: 28px;
            width: 94%;
            max-width: 480px;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: var(--shadow-pop);
            animation: popIn .22s cubic-bezier(.34,1.56,.64,1) both;
        }
        .modal::-webkit-scrollbar { width: 4px; }
        .modal::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .modal-sm { max-width: 360px; }

        @keyframes popIn { from{opacity:0;transform:scale(.9) translateY(20px)} to{opacity:1;transform:none} }
        @keyframes sheetUp { from{opacity:0;transform:translateY(60px)} to{opacity:1;transform:none} }

        .drag-handle {
            display: none;
            width: 36px; height: 4px;
            background: var(--border);
            border-radius: 9999px;
            margin: 12px auto 0;
        }

        @media (max-width: 639px) {
            .overlay { align-items: flex-end !important; }
            .modal { margin: 0; width: 100%; max-width: 100%; border-radius: 26px 26px 0 0; animation: sheetUp .26s cubic-bezier(.34,1.2,.64,1) both; }
            .drag-handle { display: block; }
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 22px 14px;
        }
        .modal-title { font-size: 1rem; font-weight: 800; color: var(--ink); }

        .close-btn {
            width: 32px; height: 32px;
            background: var(--surface);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: .8rem;
            transition: all .15s;
        }
        .close-btn:hover { background: var(--border); color: var(--ink); }

        /* ── Detail rows ── */
        .detail-grid {
            display: flex;
            flex-direction: column;
            gap: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            margin: 0 18px 14px;
        }
        .d-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 11px 16px;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }
        .d-row:last-child { border-bottom: none; }
        .d-label {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--muted-lt);
            flex-shrink: 0;
            padding-top: 1px;
        }
        .d-value {
            font-size: .82rem;
            font-weight: 700;
            color: var(--ink);
            text-align: right;
            word-break: break-word;
            max-width: 62%;
        }
        .d-mono { font-family: 'JetBrains Mono', monospace; font-size: .78rem; }

        /* ── Notice sections ── */
        .notice {
            margin: 0 18px 14px;
            border-radius: 16px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .notice-pending  { background: #fffbeb; border: 1px solid #fde68a; }
        .notice-rejected { background: #fef2f2; border: 1px solid #fecaca; }
        .notice-expired  { background: var(--surface); border: 1px solid var(--border); }
        .notice-icon {
            width: 34px; height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .85rem;
        }
        .notice-pending  .notice-icon { background: #fef3c7; color: #d97706; }
        .notice-rejected .notice-icon { background: #fee2e2; color: var(--red); }
        .notice-expired  .notice-icon { background: var(--border); color: var(--muted); }
        .notice-head { font-size: .8rem; font-weight: 700; }
        .notice-pending  .notice-head { color: #92400e; }
        .notice-rejected .notice-head { color: #991b1b; }
        .notice-expired  .notice-head { color: var(--muted); }
        .notice-body { font-size: .72rem; margin-top: 2px; }
        .notice-pending  .notice-body { color: #b45309; }
        .notice-rejected .notice-body { color: #b91c1c; }
        .notice-expired  .notice-body { color: var(--muted-lt); }

        /* ── QR ticket ── */
        .ticket-wrap {
            margin: 0 18px 14px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px dashed #86efac;
            border-radius: 20px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .ticket-label {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--green);
        }
        .ticket-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: .72rem;
            color: var(--muted);
            text-align: center;
            word-break: break-all;
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--green);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
            font-family: 'Sora', sans-serif;
        }
        .btn-download:hover { background: #15803d; }

        /* ── Claimed ── */
        .claimed-wrap {
            margin: 0 18px 14px;
            background: #faf5ff;
            border: 2px dashed #d8b4fe;
            border-radius: 20px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            text-align: center;
        }
        .claimed-icon { font-size: 1.8rem; color: var(--purple); }
        .claimed-title { font-size: .88rem; font-weight: 800; color: #6b21a8; }
        .claimed-sub { font-size: .7rem; color: #a78bfa; }

        /* ── Modal footer buttons ── */
        .modal-foot { padding: 14px 18px 20px; display: flex; gap: 10px; }

        .btn-close-modal {
            flex: 1;
            padding: 13px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-btn);
            font-size: .82rem;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            transition: all .15s;
            font-family: 'Sora', sans-serif;
        }
        .btn-close-modal:hover { background: var(--border); color: var(--ink); }

        .btn-cancel-res {
            flex: 1;
            padding: 13px;
            background: var(--red);
            border: none;
            border-radius: var(--radius-btn);
            font-size: .82rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: 'Sora', sans-serif;
        }
        .btn-cancel-res:hover:not(:disabled) { background: #dc2626; }
        .btn-cancel-res:disabled { opacity: .6; cursor: not-allowed; }

        /* ── Cancel confirm modal ── */
        .cancel-confirm-body { padding: 28px 22px 6px; text-align: center; }
        .cancel-icon-wrap {
            width: 56px; height: 56px;
            background: #fef2f2;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.4rem;
            color: var(--red);
        }
        .cancel-modal-title { font-size: 1.1rem; font-weight: 800; color: var(--ink); }
        .cancel-modal-sub { font-size: .78rem; color: var(--muted); margin-top: 4px; }
        .cancel-resource-name { font-size: .82rem; font-weight: 700; color: var(--ink-2); margin-top: 10px; }

        /* ── Resource name cell ── */
        .res-name { font-size: .85rem; font-weight: 700; color: var(--ink); }
        .res-pc   { display: flex; align-items: center; gap: 5px; margin-top: 2px; font-size: .7rem; color: var(--muted); font-weight: 500; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: none; }
        }

        /* Stagger card animations */
        .m-card:nth-child(1) { animation: fadeUp .3s .05s ease both; }
        .m-card:nth-child(2) { animation: fadeUp .3s .1s  ease both; }
        .m-card:nth-child(3) { animation: fadeUp .3s .15s ease both; }
        .m-card:nth-child(4) { animation: fadeUp .3s .2s  ease both; }
        .m-card:nth-child(5) { animation: fadeUp .3s .25s ease both; }
        .m-card:nth-child(n+6) { animation: fadeUp .3s .28s ease both; }
    </style>
</head>
<body>

<?php
$navItems = [
    ['url' => '/dashboard',        'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/reservation',      'icon' => 'fa-plus',            'label' => 'New',             'key' => 'reservation'],
    ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'reservation-list'],
    ['url' => '/books',            'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
    ['url' => '/profile',          'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
];
?>

<!-- Detail Modal -->
<div id="detailsModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('detailsModal')"></div>
    <div class="modal" id="detailModalBox">
        <div class="drag-handle"></div>
        <div class="modal-head">
            <p class="modal-title">Reservation Details</p>
            <button class="close-btn" onclick="closeModal('detailsModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="modalStatusRow" class="px-5 mb-3"></div>
        <div id="detailGrid" class="detail-grid"></div>
        <div id="pendingNotice"  class="notice notice-pending  hidden"><div class="notice-icon"><i class="fa-regular fa-hourglass-half"></i></div><div><p class="notice-head">Awaiting Approval</p><p class="notice-body">Your e-ticket will appear once an SK officer approves your request.</p></div></div>
        <div id="rejectedNotice" class="notice notice-rejected hidden"><div class="notice-icon"><i class="fa-solid fa-ban"></i></div><div><p class="notice-head" id="rejectedHead"></p><p class="notice-body" id="rejectedBody"></p></div></div>
        <div id="expiredNotice"  class="notice notice-expired  hidden"><div class="notice-icon"><i class="fa-regular fa-clock"></i></div><div><p class="notice-head">Reservation Expired</p><p class="notice-body">The scheduled date has passed without this reservation being claimed.</p></div></div>
        <div id="qrSection" class="ticket-wrap hidden">
            <p class="ticket-label">E-Ticket · Scan to Enter</p>
            <canvas id="qrCanvas" style="border-radius:12px"></canvas>
            <p id="qrCodeText" class="ticket-code"></p>
            <button class="btn-download" onclick="downloadTicket()"><i class="fa-solid fa-download text-xs"></i> Download E-Ticket</button>
        </div>
        <div id="claimedSection" class="claimed-wrap hidden">
            <i class="fa-solid fa-check-double claimed-icon"></i>
            <p class="claimed-title">Ticket Already Used</p>
            <p class="claimed-sub">This reservation was claimed and cannot be reused.</p>
            <p id="claimedAtText" class="claimed-sub"></p>
        </div>
        <div class="modal-foot">
            <button class="btn-close-modal" onclick="closeModal('detailsModal')">Close</button>
        </div>
    </div>
</div>

<!-- Cancel Confirm Modal -->
<div id="cancelModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('cancelModal')"></div>
    <div class="modal modal-sm">
        <div class="drag-handle"></div>
        <div class="cancel-confirm-body">
            <div class="cancel-icon-wrap"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <p class="cancel-modal-title">Cancel Reservation?</p>
            <p class="cancel-modal-sub">This action cannot be undone.</p>
            <p id="cancelConfirmResource" class="cancel-resource-name"></p>
        </div>
        <div class="modal-foot">
            <button class="btn-close-modal" onclick="closeModal('cancelModal')">Keep it</button>
            <button id="confirmCancelBtn" class="btn-cancel-res"><i class="fa-solid fa-xmark"></i> Yes, Cancel</button>
        </div>
    </div>
</div>

<form id="cancelForm" method="POST" action="" style="display:none">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="cancelId">
</form>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-head">
            <p class="sidebar-brand">Resident Portal</p>
            <p class="sidebar-title">my<span>Space</span><span style="color:var(--green)">.</span></p>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($navItems as $item):
                $cls = ($page == $item['key']) ? 'nav-item active' : 'nav-item';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="<?= $cls ?>">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-foot">
            <a href="<?= base_url('/logout') ?>" class="logout-btn">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Nav -->
<nav class="mobile-nav">
    <div class="mobile-nav-scroll">
        <?php foreach ($navItems as $item):
            $cls = ($page == $item['key']) ? 'mob-nav-item active' : 'mob-nav-item';
        ?>
            <a href="<?= base_url($item['url']) ?>" class="<?= $cls ?>">
                <i class="fa-solid <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="<?= base_url('/logout') ?>" class="mob-nav-item" style="color:rgba(239,68,68,.7)">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>

<!-- Main -->
<main>

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">My Reservations</h1>
            <p class="page-subtitle">Track and manage your booking requests</p>
        </div>
        <div class="header-actions">
            <?php if (isset($remainingReservations)): ?>
                <div class="quota-pill">
                    <i class="fa-solid fa-circle-half-stroke text-xs"></i>
                    <?= $remainingReservations ?> of 3 slots left this month
                </div>
            <?php endif; ?>
            <div class="count-chip">
                <p class="label">Showing</p>
                <p class="value" id="totalCount">0</p>
            </div>
            <a href="<?= base_url('/reservation') ?>" class="btn-new">
                <i class="fa-solid fa-plus text-xs"></i> New Reservation
            </a>
        </div>
    </div>

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success">
            <i class="fa-solid fa-circle-check"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Filter bar -->
    <div class="filter-bar">
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Search by resource, date, purpose…" oninput="filterTable()">
        </div>
        <select id="statusFilter" class="status-select" onchange="filterTable()">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="claimed">Claimed</option>
            <option value="declined">Declined</option>
            <option value="cancelled">Cancelled</option>
            <option value="expired">Expired</option>
        </select>
    </div>

    <p id="resultHint" class="result-hint"></p>

    <!-- Desktop Table -->
    <div class="hidden md:block">
        <?php if (empty($reservations)): ?>
            <div class="table-card">
                <div class="empty-box">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>No reservations yet</p>
                    <p class="sub">Make your first booking to get started.</p>
                    <a href="<?= base_url('/reservation') ?>" class="btn-new" style="margin-top:16px;display:inline-flex">
                        <i class="fa-solid fa-plus text-xs"></i> Book Now
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-card">
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:48px">ID</th>
                                <th>Resource</th>
                                <th>Schedule</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th style="width:80px;text-align:center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationTableBody">
                            <?php foreach ($reservations as $res):
                                $isClaimed = !empty($res['claimed']) && $res['claimed'] == 1;
                                $status    = $isClaimed ? 'claimed' : strtolower($res['status'] ?? 'pending');
                                if ($status === 'approved') {
                                    $edt = strtotime($res['reservation_date'] . ' ' . ($res['end_time'] ?? '23:59:59'));
                                    if ($edt < time()) $status = 'expired';
                                }
                                $resource   = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                                $pcNumber   = htmlspecialchars($res['pc_number'] ?? '');
                                $purpose    = htmlspecialchars($res['purpose'] ?: '—');
                                $fmtDate    = (new DateTime($res['reservation_date']))->format('M j, Y');
                                $startTime  = date('g:i A', strtotime($res['start_time']));
                                $endTime    = date('g:i A', strtotime($res['end_time']));
                                $searchTxt  = strtolower("$resource $fmtDate $purpose");
                                $icons = ['pending'=>'fa-regular fa-clock','approved'=>'fa-circle-check','claimed'=>'fa-check-double','declined'=>'fa-xmark','cancelled'=>'fa-ban','expired'=>'fa-regular fa-hourglass'];
                            ?>
                                <tr class="res-row"
                                    data-status="<?= $status ?>"
                                    data-id="<?= $res['id'] ?>"
                                    data-search="<?= htmlspecialchars($searchTxt, ENT_QUOTES) ?>"
                                    onclick="viewDetails(<?= $res['id'] ?>)">
                                    <td><span class="d-mono" style="font-size:.7rem;color:var(--muted-lt)">#<?= $res['id'] ?></span></td>
                                    <td>
                                        <p class="res-name"><?= $resource ?></p>
                                        <?php if ($pcNumber): ?>
                                            <div class="res-pc"><i class="fa-solid fa-desktop" style="font-size:.65rem"></i><?= $pcNumber ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <p style="font-size:.82rem;font-weight:700;color:var(--ink-2)"><?= $fmtDate ?></p>
                                        <p style="font-size:.7rem;color:var(--muted-lt);margin-top:2px;font-weight:500"><?= $startTime ?> – <?= $endTime ?></p>
                                    </td>
                                    <td><span style="font-size:.8rem;color:var(--muted);font-weight:500"><?= $purpose ?></span></td>
                                    <td>
                                        <span class="s-badge s-<?= $status ?>">
                                            <i class="fa-solid <?= $icons[$status] ?? 'fa-circle' ?>" style="font-size:.6rem"></i>
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td style="text-align:center" onclick="event.stopPropagation()">
                                        <div style="display:flex;align-items:center;justify-content:center;gap:6px">
                                            <button class="tbl-btn tbl-btn-view" onclick="viewDetails(<?= $res['id'] ?>)" title="View details">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <?php if ($status === 'pending'): ?>
                                                <button class="tbl-btn tbl-btn-cancel" onclick="handleCancel(<?= $res['id'] ?>)" title="Cancel">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="noResults" class="hidden empty-box" style="padding:32px">
                    <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;margin-bottom:8px"></i>
                    <p>No reservations match your search</p>
                </div>
                <div class="table-foot">
                    <p class="table-foot-txt" id="tableFooter"></p>
                    <p class="table-foot-txt" style="display:none" id="tableHint">Click any row to view details</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden">
        <?php if (empty($reservations)): ?>
            <div class="no-results-card">
                <i class="fa-solid fa-calendar-xmark" style="font-size:2rem;color:var(--border);margin-bottom:10px;display:block"></i>
                <p style="font-weight:800;color:var(--muted)">No reservations yet</p>
                <a href="<?= base_url('/reservation') ?>" class="btn-new" style="margin-top:14px;display:inline-flex">
                    <i class="fa-solid fa-plus text-xs"></i> Book Now
                </a>
            </div>
        <?php else: ?>
            <div class="cards-wrap" id="mobileCardList">
                <?php foreach ($reservations as $res):
                    $isClaimed = !empty($res['claimed']) && $res['claimed'] == 1;
                    $status    = $isClaimed ? 'claimed' : strtolower($res['status'] ?? 'pending');
                    if ($status === 'approved') {
                        $edt = strtotime($res['reservation_date'] . ' ' . ($res['end_time'] ?? '23:59:59'));
                        if ($edt < time()) $status = 'expired';
                    }
                    $resource  = htmlspecialchars($res['resource_name'] ?? ('Resource #' . ($res['resource_id'] ?? '?')));
                    $pcNumber  = htmlspecialchars($res['pc_number'] ?? '');
                    $purpose   = htmlspecialchars($res['purpose'] ?: '—');
                    $fmtDate   = (new DateTime($res['reservation_date']))->format('M j, Y');
                    $startTime = date('g:i A', strtotime($res['start_time']));
                    $endTime   = date('g:i A', strtotime($res['end_time']));
                    $searchTxt = strtolower("$resource $fmtDate $purpose");
                    $icons = ['pending'=>'fa-regular fa-clock','approved'=>'fa-circle-check','claimed'=>'fa-check-double','declined'=>'fa-xmark','cancelled'=>'fa-ban','expired'=>'fa-regular fa-hourglass'];
                    $avatarBg = ['pending'=>'#fef3c7;color:#92400e','approved'=>'#dcfce7;color:#166534','claimed'=>'#f3e8ff;color:#6b21a8','declined'=>'#fee2e2;color:#991b1b','cancelled'=>'#fee2e2;color:#991b1b','expired'=>'#f1f5f9;color:#475569'][$status] ?? '#f1f5f9;color:#475569';
                ?>
                    <div class="m-card"
                         data-id="<?= $res['id'] ?>"
                         data-status="<?= $status ?>"
                         data-search="<?= htmlspecialchars($searchTxt, ENT_QUOTES) ?>"
                         onclick="viewDetails(<?= $res['id'] ?>)">

                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                            <div style="width:40px;height:40px;border-radius:14px;background:<?= $avatarBg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fa-solid fa-desktop" style="font-size:.85rem"></i>
                            </div>
                            <div style="flex:1;min-width:0">
                                <p style="font-size:.85rem;font-weight:700;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= $resource ?></p>
                                <?php if ($pcNumber): ?>
                                    <p style="font-size:.68rem;color:var(--muted-lt);margin-top:1px"><?= $pcNumber ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="s-badge s-<?= $status ?>" style="flex-shrink:0">
                                <i class="fa-solid <?= $icons[$status] ?? 'fa-circle' ?>" style="font-size:.55rem"></i>
                                <?= ucfirst($status) ?>
                            </span>
                        </div>

                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                            <i class="fa-regular fa-calendar" style="font-size:.65rem;color:var(--muted-lt)"></i>
                            <span style="font-size:.75rem;font-weight:600;color:var(--muted)"><?= $fmtDate ?></span>
                            <span style="font-size:.68rem;font-weight:700;color:var(--green)"><?= $startTime ?> – <?= $endTime ?></span>
                        </div>

                        <p style="font-size:.72rem;color:var(--muted-lt);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:10px"><?= $purpose ?></p>

                        <?php if ($status === 'pending'): ?>
                            <div style="display:flex;gap:8px;padding-top:10px;border-top:1px solid var(--border)" onclick="event.stopPropagation()">
                                <button onclick="handleCancel(<?= $res['id'] ?>)"
                                    style="flex:1;height:34px;border-radius:10px;background:#fef2f2;border:none;color:var(--red);font-weight:700;font-size:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;font-family:'Sora',sans-serif;transition:all .15s"
                                    onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                    <i class="fa-solid fa-xmark" style="font-size:.7rem"></i> Cancel
                                </button>
                                <button onclick="viewDetails(<?= $res['id'] ?>)"
                                    style="flex:1;height:34px;border-radius:10px;background:var(--surface);border:none;color:var(--muted);font-weight:700;font-size:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;font-family:'Sora',sans-serif;transition:all .15s"
                                    onmouseover="this.style.background='var(--border)'" onmouseout="this.style.background='var(--surface)'">
                                    <i class="fa-solid fa-eye" style="font-size:.7rem"></i> View
                                </button>
                            </div>
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;border-top:1px solid var(--border)">
                                <span style="font-family:'JetBrains Mono',monospace;font-size:.65rem;color:var(--border)">#<?= $res['id'] ?></span>
                                <span style="font-size:.65rem;color:var(--muted-lt);font-weight:500;display:flex;align-items:center;gap:3px">Tap to view <i class="fa-solid fa-chevron-right" style="font-size:.55rem"></i></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="mobileEmpty" class="no-results-card" style="display:none;margin-top:10px">
            <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:var(--border);margin-bottom:8px;display:block"></i>
            <p style="font-weight:800;color:var(--muted);font-size:.88rem">No reservations match your search</p>
        </div>
    </div>

</main>

<script>
const reservationsData = <?= json_encode($reservations ?? []) ?>;
const allTableRows     = Array.from(document.querySelectorAll('#reservationTableBody .res-row'));
const allCards         = Array.from(document.querySelectorAll('#mobileCardList .m-card'));
let   cancelTargetId   = null;

/* ── Filters ── */
function filterTable() {
    const q  = document.getElementById('searchInput').value.toLowerCase();
    const sf = document.getElementById('statusFilter').value;
    let n = 0;

    const matches = el => {
        const mQ = !q  || (el.dataset.search || '').includes(q);
        const mS = !sf || el.dataset.status === sf;
        return mQ && mS;
    };

    allTableRows.forEach(row => { const show = matches(row); row.style.display = show ? '' : 'none'; if (show) n++; });

    let cardVis = 0;
    allCards.forEach(card => { const show = matches(card); card.style.display = show ? '' : 'none'; if (show) cardVis++; });

    document.getElementById('totalCount').textContent = n;

    const noRes = document.getElementById('noResults');
    if (noRes) noRes.classList.toggle('hidden', n > 0);

    const mEmpty = document.getElementById('mobileEmpty');
    if (mEmpty && allCards.length > 0) mEmpty.style.display = cardVis === 0 ? 'block' : 'none';

    const total = allTableRows.length || allCards.length;
    const hint  = document.getElementById('resultHint');
    if (hint) hint.textContent = `Showing ${n} of ${total} reservation${total !== 1 ? 's' : ''}`;

    const footer = document.getElementById('tableFooter');
    if (footer) footer.textContent = `${n} result${n !== 1 ? 's' : ''} shown`;

    const tblHint = document.getElementById('tableHint');
    if (tblHint) tblHint.style.display = n > 0 ? 'block' : 'none';
}
filterTable();

/* ── View Details ── */
function viewDetails(id) {
    const res = reservationsData.find(r => r.id == id);
    if (!res) return;

    const isClaimed = res.claimed == 1;
    const raw       = (res.status || 'pending').toLowerCase();
    const edt       = new Date(res.reservation_date + 'T' + res.end_time);
    const isExpired = !isClaimed && raw === 'approved' && edt < new Date();
    const status    = isClaimed ? 'claimed' : (isExpired ? 'expired' : raw);

    const resourceName = res.resource_name || ('Resource #' + res.resource_id);
    const code         = res.e_ticket_code || null;
    const fmtDate      = new Date(res.reservation_date).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
    const fmtStart     = new Date('1970-01-01T' + res.start_time).toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' });
    const fmtEnd       = new Date('1970-01-01T' + res.end_time).toLocaleTimeString('en-US',   { hour:'numeric', minute:'2-digit' });

    // Status row
    const statusColors = {
        pending:   ['#fef3c7','#92400e'],
        approved:  ['#dcfce7','#166534'],
        claimed:   ['#f3e8ff','#6b21a8'],
        declined:  ['#fee2e2','#991b1b'],
        cancelled: ['#fee2e2','#991b1b'],
        expired:   ['#f1f5f9','#475569'],
    };
    const [bg, fg] = statusColors[status] || ['#f1f5f9','#475569'];
    const statusIcons = { pending:'fa-clock',approved:'fa-circle-check',claimed:'fa-check-double',declined:'fa-xmark',cancelled:'fa-ban',expired:'fa-hourglass-end' };

    document.getElementById('modalStatusRow').innerHTML = `
        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:8px;background:${bg};color:${fg};font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em">
            <i class="fa-solid ${statusIcons[status] || 'fa-circle'}" style="font-size:.6rem"></i>${status.charAt(0).toUpperCase()+status.slice(1)}
        </span>`;

    // Detail grid
    let rows = `
        <div class="d-row"><span class="d-label">Reservation</span><span class="d-value d-mono">#${res.id}</span></div>
        <div class="d-row"><span class="d-label">Resource</span><span class="d-value">${resourceName}</span></div>
        <div class="d-row"><span class="d-label">PC / Station</span><span class="d-value">${res.pc_number || '—'}</span></div>
        <div class="d-row"><span class="d-label">Date</span><span class="d-value">${fmtDate}</span></div>
        <div class="d-row"><span class="d-label">Time</span><span class="d-value">${fmtStart} – ${fmtEnd}</span></div>
        <div class="d-row"><span class="d-label">Purpose</span><span class="d-value">${res.purpose || '—'}</span></div>`;
    if (isClaimed && res.claimed_at) {
        const fc = new Date(res.claimed_at).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });
        rows += `<div class="d-row"><span class="d-label">Claimed</span><span class="d-value">${fc}</span></div>`;
    }
    document.getElementById('detailGrid').innerHTML = rows;

    // Hide all sections
    ['pendingNotice','rejectedNotice','expiredNotice','qrSection','claimedSection'].forEach(i => {
        const el = document.getElementById(i);
        if (el) el.classList.add('hidden');
    });

    if (status === 'pending') {
        document.getElementById('pendingNotice').classList.remove('hidden');
    } else if (status === 'declined' || status === 'cancelled') {
        const el = document.getElementById('rejectedNotice');
        el.classList.remove('hidden');
        document.getElementById('rejectedHead').textContent = status === 'declined' ? 'Reservation Declined' : 'Reservation Cancelled';
        document.getElementById('rejectedBody').textContent = status === 'declined' ? 'This reservation was declined by an SK officer.' : 'This reservation was cancelled.';
    } else if (status === 'expired') {
        document.getElementById('expiredNotice').classList.remove('hidden');
    } else if (status === 'approved' && code) {
        const qr = document.getElementById('qrSection');
        qr.classList.remove('hidden');
        document.getElementById('qrCodeText').textContent = code;
        QRCode.toCanvas(document.getElementById('qrCanvas'), code, { width:170, margin:1, color:{ dark:'#0f1923', light:'#f0fdf4' } });
    } else if (status === 'approved' && !code) {
        document.getElementById('pendingNotice').classList.remove('hidden');
    } else if (status === 'claimed') {
        const cl = document.getElementById('claimedSection');
        cl.classList.remove('hidden');
        if (res.claimed_at) {
            const fc = new Date(res.claimed_at).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
            document.getElementById('claimedAtText').textContent = 'Used on ' + fc;
        }
    }

    openModal('detailsModal');
}

function downloadTicket() {
    const canvas = document.getElementById('qrCanvas');
    const code   = document.getElementById('qrCodeText').textContent;
    const link   = document.createElement('a');
    link.download = `E-Ticket-${code}.png`;
    link.href = canvas.toDataURL('image/png');
    link.click();
}

/* ── Cancel ── */
function handleCancel(id) {
    cancelTargetId = id;
    const res = reservationsData.find(r => r.id == id);
    document.getElementById('cancelConfirmResource').textContent = res ? `"${res.resource_name || 'Resource'}"` : '';
    document.getElementById('cancelForm').action = '<?= base_url("reservation/cancel") ?>/' + id;
    openModal('cancelModal');
}

document.getElementById('confirmCancelBtn').addEventListener('click', function () {
    this.disabled = true;
    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cancelling…';
    document.getElementById('cancelId').value = cancelTargetId;
    document.getElementById('cancelForm').submit();
});

/* ── Modal helpers ── */
function openModal(id)  { document.getElementById(id).classList.add('show');    document.body.style.overflow = 'hidden'; }
function closeModal(id) {
    document.getElementById(id).classList.remove('show'); document.body.style.overflow = '';
    if (id === 'cancelModal') {
        const btn = document.getElementById('confirmCancelBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Yes, Cancel';
    }
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal('detailsModal'); closeModal('cancelModal'); }
});
</script>
</body>
</html>