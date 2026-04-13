<?php
date_default_timezone_set('Asia/Manila');
$page    = $page ?? 'new-reservation';
$sk_name = session()->get('name') ?? 'SK Officer';
$pendingUserCount = $pendingUserCount ?? 0;
$avatarLetter = strtoupper(mb_substr(trim($sk_name), 0, 1));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | SK</title>
    <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>

    <style>
        /* ══════════════════════════════
           BASE LAYOUT
        ══════════════════════════════ */
        .main-area { padding: 24px 20px; }
        @media(max-width:639px) { .main-area { padding: 16px 14px; } }

        /* ══════════════════════════════
           PAGE HEADER
        ══════════════════════════════ */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            flex-shrink: 0;
        }
        .greeting-name {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.04em;
            line-height: 1.1;
        }
        .eyebrow {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        @media(max-width:480px) {
            .greeting-name { font-size: 1.35rem; }
            .page-header-actions { width: 100%; justify-content: flex-end; }
        }

        /* ══════════════════════════════
           FORM CARD
        ══════════════════════════════ */
        .form-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 28px 32px;
            max-width: 780px;
            margin: 0 auto;
        }
        @media(max-width:639px) { .form-card { padding: 18px 16px; } }

        /* ══════════════════════════════
           SECTION COMPONENTS
        ══════════════════════════════ */
        .section-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
        }
        .section-icon {
            width: 36px;
            height: 36px;
            background: var(--indigo-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .section-title { font-size: .9rem; font-weight: 700; color: #0f172a; }
        .divider { border: none; border-top: 1px solid rgba(99, 102, 241, .07); margin: 20px 0; }

        /* ══════════════════════════════
           FIELD GRIDS — responsive
        ══════════════════════════════ */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        @media(max-width:639px) {
            .grid-2 { grid-template-columns: 1fr; gap: 12px; }
            .grid-3 { grid-template-columns: 1fr; gap: 12px; }
        }


        /* ══════════════════════════════
           TYPE TOGGLE
        ══════════════════════════════ */
        .type-toggle {
            display: flex;
            background: #f1f5f9;
            padding: 5px;
            border-radius: var(--r-md);
            gap: 4px;
        }
        .type-btn {
            flex: 1;
            padding: 10px 14px;
            border-radius: var(--r-sm);
            cursor: pointer;
            font-weight: 700;
            font-size: .82rem;
            transition: all var(--ease);
            color: #64748b;
            border: none;
            background: transparent;
            font-family: var(--font);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .type-btn.active { background: var(--indigo); color: white; box-shadow: 0 4px 12px rgba(55,48,163,.25); }

        /* ══════════════════════════════
           AUTOCOMPLETE
        ══════════════════════════════ */
        .autocomplete-wrap { position: relative; }
        .autocomplete-list {
            position: absolute;
            z-index: 50;
            background: white;
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--r-md);
            box-shadow: var(--shadow-lg);
            max-height: 220px;
            overflow-y: auto;
            width: 100%;
            top: calc(100% + 4px);
            left: 0;
        }
        .autocomplete-item { padding: 12px 16px; cursor: pointer; font-size: .87rem; transition: background .15s; font-weight: 500; }
        .autocomplete-item:hover { background: var(--indigo-light); color: var(--indigo); }
        .autocomplete-item .sub { font-size: .72rem; color: #94a3b8; margin-top: 2px; }

        /* ══════════════════════════════
           PC SECTION
        ══════════════════════════════ */
        .pc-section {
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            border-radius: var(--r-md);
            padding: 18px;
        }
        .pc-btn {
            padding: 9px 12px;
            border-radius: 9px;
            font-size: .75rem;
            font-weight: 700;
            border: 1px solid var(--indigo-border);
            background: white;
            color: #475569;
            cursor: pointer;
            transition: all var(--ease);
        }
        .pc-btn:hover { border-color: var(--indigo); color: var(--indigo); }
        .pc-btn.selected { background: var(--indigo); color: white; border-color: var(--indigo); }

        /* ══════════════════════════════
           SUBMIT BUTTON
        ══════════════════════════════ */
        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: var(--indigo);
            color: white;
            border-radius: var(--r-sm);
            font-size: .9rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            box-shadow: 0 4px 12px rgba(55,48,163,.28);
        }
        .submit-btn:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(55,48,163,.35); }

        /* ══════════════════════════════
           FLASH MESSAGES
        ══════════════════════════════ */
        .flash-ok {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px; padding: 13px 18px;
            background: var(--indigo-light); border: 1px solid var(--indigo-border);
            color: var(--indigo); font-weight: 600; border-radius: var(--r-md); font-size: .875rem;
        }
        .flash-err {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px; padding: 13px 18px;
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; font-weight: 600; border-radius: var(--r-md); font-size: .875rem;
        }

        /* ══════════════════════════════
           HEADER BUTTONS
        ══════════════════════════════ */
        .icon-btn {
            width: 44px; height: 44px;
            background: white; border: 1px solid rgba(99,102,241,.12);
            border-radius: var(--r-sm); display: flex; align-items: center;
            justify-content: center; color: #64748b; cursor: pointer;
            transition: all var(--ease); box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }
        .back-btn {
            display: flex; align-items: center; gap: 7px;
            padding: 10px 16px; background: white;
            border: 1px solid rgba(99,102,241,.15); border-radius: var(--r-sm);
            font-size: .82rem; font-weight: 700; color: #475569;
            text-decoration: none; transition: all var(--ease); box-shadow: var(--shadow-sm);
            white-space: nowrap;
        }
        .back-btn:hover { border-color: var(--indigo); color: var(--indigo); background: var(--indigo-light); }

        /* ══════════════════════════════
           CONFIRM MODAL
        ══════════════════════════════ */
        .modal-back {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,.52); backdrop-filter: blur(6px);
            z-index: 300; padding: 1.5rem; overflow-y: auto;
            align-items: center; justify-content: center;
        }
        .modal-back.show { display: flex; animation: fadeIn .15s ease; }

        .modal-card {
            background: white; border-radius: var(--r-xl);
            width: 100%; max-width: 480px; padding: 24px;
            max-height: calc(100dvh - 3rem); overflow-y: auto;
            margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg);
        }
        .sheet-handle { display: none; width: 36px; height: 4px; background: #e2e8f0; border-radius: 999px; margin: 0 auto 16px; }

        @media(max-width:639px) {
            .modal-back { padding: 0; align-items: flex-end !important; }
            .modal-card {
                border-radius: var(--r-xl) var(--r-xl) 0 0;
                max-width: 100%; max-height: 92dvh;
                animation: sheetUp .25s cubic-bezier(.34,1.2,.64,1) both;
                padding: 20px 16px 32px;
            }
            .sheet-handle { display: block; }
        }

        .mrow { display: flex; justify-content: space-between; align-items: flex-start; padding: 9px 0; border-bottom: 1px solid rgba(99,102,241,.06); gap: 12px; }
        .mrow:last-child { border-bottom: none; }
        .mrow-label { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: #94a3b8; flex-shrink: 0; padding-top: 1px; }
        .mrow-value { font-weight: 600; color: #0f172a; font-size: .83rem; text-align: right; word-break: break-word; }

        .qr-section {
            background: var(--indigo-light); border: 1.5px dashed var(--indigo-border);
            border-radius: var(--r-md); padding: 20px;
            display: flex; flex-direction: column; align-items: center; gap: 12px;
        }

        /* ══════════════════════════════
           ANIMATIONS
        ══════════════════════════════ */
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
        @keyframes sheetUp { from { opacity:0; transform:translateY(60px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }

        /* ══════════════════════════════
           CUSTOM DATE / TIME PICKERS
        ══════════════════════════════ */
        #resDate, #startTime, #endTime { display: none !important; }

        .dt-trigger {
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px; width: 100%; padding: 10px 13px;
            background: var(--card); border: 1px solid rgba(99,102,241,.15);
            border-radius: var(--r-sm); font-family: var(--font);
            font-size: .87rem; font-weight: 500; color: #94a3b8;
            cursor: pointer; transition: border .2s, box-shadow .2s;
            user-select: none; -webkit-user-select: none;
        }
        .dt-trigger.has-value { color: var(--text, #0f172a); }
        .dt-trigger:hover { border-color: rgba(99,102,241,.35); }
        .dt-trigger.open { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .dt-trigger svg { flex-shrink: 0; opacity: .45; }
        .dt-trigger.open svg { opacity: .8; }

        .dt-drop { position: absolute; top: calc(100%+6px); left: 0; z-index: 9999; border-radius: 14px; animation: dtDrop .15s cubic-bezier(.4,0,.2,1); }
        @media(max-width:639px) {
            .dt-drop.cal { width: calc(100vw - 32px) !important; max-width: 320px; }
            .dt-drop.tim { width: 220px; }
        }
        @keyframes dtDrop { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:none; } }

        body:not(.dark) .dt-drop { background:#fff; border:1px solid rgba(99,102,241,.18); box-shadow:0 20px 50px rgba(15,23,42,.18); }
        body.dark .dt-drop { background:#0e1828; border:1px solid rgba(99,102,241,.22); box-shadow:0 20px 60px rgba(0,0,0,.65); }

        .dt-drop.cal { width: 288px; padding: 18px 16px 14px; }


        .cal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .cal-month-label { font-size:.88rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:7px; transition:background .15s; }
        body:not(.dark) .cal-month-label { color:#0f172a; }
        body:not(.dark) .cal-month-label:hover { background:#f1f5f9; }
        body.dark .cal-month-label { color:#e2e8f0; }
        body.dark .cal-month-label:hover { background:rgba(99,102,241,.12); }

        .cal-nav-btn { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.75rem; transition:all .15s; }
        body:not(.dark) .cal-nav-btn { background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; }
        body:not(.dark) .cal-nav-btn:hover { border-color:var(--indigo); color:var(--indigo); background:var(--indigo-light); }
        body.dark .cal-nav-btn { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); color:#94a3b8; }
        body.dark .cal-nav-btn:hover { border-color:var(--indigo); color:#a5b4fc; background:rgba(99,102,241,.1); }

        .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:2px; }
        .cal-dow { font-size:.6rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; text-align:center; padding:3px 0 9px; }
        body:not(.dark) .cal-dow { color:#94a3b8; }
        body.dark .cal-dow { color:#4f5a72; }

        .cal-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; border-radius:8px; font-size:.8rem; font-weight:500; cursor:pointer; transition:all .12s; border:1px solid transparent; }
        body:not(.dark) .cal-day { color:#475569; }
        body.dark .cal-day { color:#8b95b0; }
        body:not(.dark) .cal-day:hover:not(.cal-other):not(.cal-selected) { background:#f1f5f9; color:#0f172a; border-color:#e2e8f0; }
        body.dark .cal-day:hover:not(.cal-other):not(.cal-selected) { background:rgba(255,255,255,.06); color:#e2e8f0; border-color:rgba(255,255,255,.08); }
        .cal-day.cal-other { pointer-events:none; }
        body:not(.dark) .cal-day.cal-other { color:#cbd5e1; }
        body.dark .cal-day.cal-other { color:#2e3850; }
        body:not(.dark) .cal-day.cal-today { color:var(--indigo); font-weight:700; }
        body.dark .cal-day.cal-today { color:#818cf8; font-weight:700; }
        .cal-day.cal-selected { background:var(--indigo)!important; color:#fff!important; font-weight:700; border-color:var(--indigo)!important; box-shadow:0 2px 10px rgba(99,102,241,.4); }

        .cal-footer { display:flex; justify-content:space-between; margin-top:12px; padding-top:12px; }
        body:not(.dark) .cal-footer { border-top:1px solid #f1f5f9; }
        body.dark .cal-footer { border-top:1px solid rgba(255,255,255,.06); }
        .cal-foot-btn { font-size:.72rem; font-weight:700; cursor:pointer; padding:5px 9px; border-radius:7px; transition:all .15s; }
        body:not(.dark) .cal-foot-btn { color:#94a3b8; }
        body:not(.dark) .cal-foot-btn:hover { color:var(--indigo); background:var(--indigo-light); }
        body:not(.dark) .cal-foot-btn.today { color:var(--indigo); }
        body.dark .cal-foot-btn { color:#4f5a72; }
        body.dark .cal-foot-btn:hover { color:#818cf8; background:rgba(99,102,241,.1); }
        body.dark .cal-foot-btn.today { color:#818cf8; }

        .dt-drop.tim { width:232px; padding:16px 14px 14px; }
        .tim-title { font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; text-align:center; margin-bottom:12px; }
        body:not(.dark) .tim-title { color:#94a3b8; }
        body.dark .tim-title { color:#4f5a72; }
        .tim-cols { display:flex; align-items:flex-start; gap:4px; }
        .tim-col { flex:1; display:flex; flex-direction:column; gap:2px; max-height:192px; overflow-y:auto; scrollbar-width:thin; }
        body:not(.dark) .tim-col { scrollbar-color:#e2e8f0 transparent; }
        body.dark .tim-col { scrollbar-color:rgba(99,102,241,.3) transparent; }
        .tim-col::-webkit-scrollbar { width:3px; }
        body:not(.dark) .tim-col::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }
        body.dark .tim-col::-webkit-scrollbar-thumb { background:rgba(99,102,241,.3); border-radius:4px; }
        .tim-item { padding:7px 6px; border-radius:7px; font-size:.81rem; font-weight:500; text-align:center; cursor:pointer; transition:all .1s; border:1px solid transparent; }
        body:not(.dark) .tim-item { color:#64748b; }
        body:not(.dark) .tim-item:hover:not(.sel) { background:#f1f5f9; color:#0f172a; }
        body.dark .tim-item { color:#8b95b0; }
        body.dark .tim-item:hover:not(.sel) { background:rgba(255,255,255,.06); color:#e2e8f0; }
        .tim-item.sel { background:var(--indigo)!important; color:#fff!important; font-weight:700; box-shadow:0 2px 8px rgba(99,102,241,.4); }
        .tim-sep { font-size:1rem; font-weight:700; padding:6px 0; align-self:flex-start; margin-top:4px; }
        body:not(.dark) .tim-sep { color:#cbd5e1; }
        body.dark .tim-sep { color:#4f5a72; }
        .ampm-col { display:flex; flex-direction:column; gap:5px; padding-top:2px; }
        .ampm-btn { padding:8px 10px; border-radius:8px; font-size:.75rem; font-weight:700; cursor:pointer; text-align:center; transition:all .15s; }
        body:not(.dark) .ampm-btn { border:1px solid #e2e8f0; color:#64748b; background:#f8fafc; }
        body:not(.dark) .ampm-btn:hover:not(.sel) { color:var(--indigo); border-color:var(--indigo-border); }
        body.dark .ampm-btn { border:1px solid rgba(255,255,255,.07); color:#8b95b0; background:rgba(255,255,255,.04); }
        body.dark .ampm-btn:hover:not(.sel) { color:#e2e8f0; border-color:rgba(255,255,255,.14); }
        .ampm-btn.sel { background:var(--indigo)!important; color:#fff!important; border-color:var(--indigo)!important; box-shadow:0 2px 8px rgba(99,102,241,.4); }
        .tim-set-btn { width:100%; margin-top:12px; padding:9px; background:var(--indigo); color:#fff; border:none; border-radius:9px; font-size:.8rem; font-weight:700; font-family:var(--font); cursor:pointer; transition:background .15s; }
        .tim-set-btn:hover { background:#4f46e5; }
        .picker-wrap { position: relative; }

        /* ══════════════════════════════
           DARK MODE — UNIFIED WITH ADMIN
           Uses same palette as admin_new_reservation
        ══════════════════════════════ */
        body.dark .greeting-name { color: #e2eaf8; }
        body.dark .eyebrow { color: #4a6fa5; }

        body.dark .icon-btn { background: #101e35; border-color: rgba(99,102,241,.18); color: #7fb3e8; }
        body.dark .icon-btn:hover { background: rgba(99,102,241,.12); border-color: var(--indigo); color: #a5b4fc; }
        body.dark .back-btn { background: #101e35; border-color: rgba(99,102,241,.18); color: #7fb3e8; }
        body.dark .back-btn:hover { background: rgba(99,102,241,.12); border-color: var(--indigo); color: #a5b4fc; }

        body.dark .form-card { background: #0b1628; border-color: rgba(99,102,241,.1); }
        body.dark .divider { border-color: rgba(99,102,241,.1); }
        body.dark .section-head { border-color: rgba(99,102,241,.1); }
        body.dark .section-icon { background: rgba(55,48,163,.2); }
        body.dark .section-title { color: #e2eaf8; }

        body.dark .field-input,
        body.dark input.field-input,
        body.dark select.field-input {
            background: #101e35;
            border-color: rgba(99,102,241,.18);
            color: #e2eaf8;
        }
        body.dark .field-input:focus { background: #0b1628; border-color: var(--indigo); }
        body.dark .field-input[readonly] { background: #060e1e; color: #4a6fa5; }
        body.dark .field-input::placeholder { color: #4a6fa5; }
        body.dark select.field-input option { background: #0b1628; color: #e2eaf8; }
        body.dark .field-label { color: #4a6fa5; }

        body.dark .type-toggle { background: #101e35; }
        body.dark .type-btn { color: #7fb3e8; }

        body.dark .pc-section { background: rgba(55,48,163,.1); border-color: rgba(99,102,241,.2); }
        body.dark .pc-btn { background: #101e35; border-color: rgba(99,102,241,.22); color: #7fb3e8; }
        body.dark .pc-btn:hover { border-color: var(--indigo); color: #a5b4fc; }
        body.dark .pc-btn.selected { background: var(--indigo); color: #fff; border-color: var(--indigo); }

        body.dark .autocomplete-list { background: #0b1628; border-color: rgba(99,102,241,.18); }
        body.dark .autocomplete-item:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .autocomplete-item .sub { color: #4a6fa5; }

        body.dark .flash-err { background: rgba(220,38,38,.1); border-color: rgba(248,113,113,.3); color: #f87171; }
        body.dark .flash-ok { background: rgba(55,48,163,.2); border-color: rgba(99,102,241,.3); color: #a5b4fc; }

        body.dark .modal-card { background: #0b1628; color: #e2eaf8; }
        body.dark .mrow-label { color: #4a6fa5; }
        body.dark .mrow-value { color: #e2eaf8; }
        body.dark .mrow { border-color: rgba(99,102,241,.08); }
        body.dark .sheet-handle { background: #1e3a5f; }
        body.dark .modal-card h3 { color: #e2eaf8; }
        body.dark .modal-card p { color: #4a6fa5; }

        body.dark #modalSummaryBox { background: #060e1e !important; border-color: rgba(99,102,241,.1) !important; }

        body.dark .qr-section { background: rgba(55,48,163,.1); border-color: rgba(99,102,241,.25); }
        body.dark .qr-section p { color: #7fb3e8 !important; }

        body.dark .modal-cancel-btn { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #7fb3e8 !important; }
        body.dark .modal-cancel-btn:hover { background: rgba(99,102,241,.12) !important; color: #a5b4fc !important; }

        body.dark .submit-btn { box-shadow: 0 4px 12px rgba(55,48,163,.4); }
    </style>
</head>

<body>
    <?php include APPPATH . 'Views/partials/sk_layout.php'; ?>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal-back" onclick="if(event.target===this)closeModal()">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px">
                <div style="width:52px;height:52px;background:var(--indigo-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                    <i class="fa-solid fa-clipboard-list" style="font-size:1.3rem;color:var(--indigo)"></i>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:#94a3b8;margin-top:4px">Review details before saving.</p>
            </div>
            <div id="modalSummaryBox" style="background:#f8fafc;border-radius:var(--r-md);padding:14px 16px;border:1px solid rgba(99,102,241,.08);margin-bottom:14px" id="modalSummary"></div>
            <div id="qrWrap" style="display:none;margin-bottom:14px" class="qr-section">
                <p style="font-size:.6rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:var(--indigo)">E-Ticket Preview</p>
                <canvas id="qrCanvas" style="border-radius:10px"></canvas>
                <p id="qrText" style="font-size:.7rem;color:#94a3b8;font-family:var(--mono);text-align:center;word-break:break-all"></p>
                <button onclick="downloadQR()" style="display:flex;align-items:center;gap:7px;padding:9px 16px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.78rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font)">
                    <i class="fa-solid fa-download" style="font-size:.7rem"></i> Download E-Ticket
                </button>
            </div>
            <div id="modalActions" style="display:flex;gap:10px">
                <button type="button" class="modal-cancel-btn" onclick="closeModal()" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font)">Cancel</button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" style="flex:2;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 12px rgba(55,48,163,.28)">
                    <i class="fa-solid fa-check" style="font-size:.8rem"></i> Confirm & Save
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <main class="main-area">
        <!-- Topbar -->
        <div class="page-header fade-up">
            <div>
                <p class="eyebrow">SK Portal</p>
                <h2 class="greeting-name">New Reservation</h2>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Register a manual entry into the system.</p>
            </div>
            <div class="page-header-actions">
                <div class="icon-btn" onclick="layoutToggleDark()" id="darkBtn" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <a href="/sk/my-reservations" class="back-btn">
                    <i class="fa-solid fa-chevron-left" style="font-size:.75rem"></i> Back
                </a>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="form-card fade-up-1">
            <form id="reservationForm" method="POST" action="<?= base_url('sk/create-reservation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="visitor_name" id="finalVisitorName">
                <input type="hidden" name="user_email"   id="finalUserEmail">
                <input type="hidden" name="user_id"      id="finalUserId">
                <input type="hidden" name="visitor_type" id="finalVisitorType" value="User">
                <input type="hidden" name="purpose"      id="finalPurpose">
                <input type="hidden" name="pcs"          id="finalPcs" value="[]">

                <!-- Visitor type -->
                <div style="margin-bottom:20px">
                    <label class="field-label" style="margin-bottom:8px;display:block">Visitor Classification</label>
                    <div class="type-toggle">
                        <button type="button" class="type-btn active" id="btnUser" onclick="setType('User')">
                            <i class="fa-solid fa-user" style="font-size:.8rem"></i> Registered User
                        </button>
                        <button type="button" class="type-btn" id="btnVisitor" onclick="setType('Visitor')">
                            <i class="fa-solid fa-person-walking" style="font-size:.8rem"></i> Walk-in Visitor
                        </button>
                    </div>
                </div>
                <hr class="divider">

                <!-- Personal details -->
                <div style="margin-bottom:20px">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-id-badge" style="color:var(--indigo);font-size:.9rem"></i></div>
                        <div><div class="section-title">Personal Details</div></div>
                    </div>
                    <div id="userFields" class="grid-2">
                        <div>
                            <label class="field-label">Full Name</label>
                            <div class="autocomplete-wrap">
                                <input type="text" id="userNameInput" class="field-input" placeholder="Type to search users…" autocomplete="off">
                                <ul id="autocompleteList" class="autocomplete-list" style="display:none"></ul>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="userEmailDisplay" class="field-input" placeholder="Auto-filled on selection" readonly>
                            <p style="font-size:.65rem;color:#94a3b8;margin-top:4px">Fills automatically when a user is selected</p>
                        </div>
                    </div>
                    <div id="visitorFields" style="display:none" class="grid-2">
                        <div><label class="field-label">Full Name</label><input type="text" id="visitorNameInput" class="field-input" placeholder="Enter visitor's full name"></div>
                        <div><label class="field-label">Email Address</label><input type="email" id="visitorEmailInput" class="field-input" placeholder="Enter email (optional)"></div>
                    </div>
                </div>
                <hr class="divider">

                <!-- Resource & Schedule -->
                <div style="margin-bottom:20px">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.9rem"></i></div>
                        <div><div class="section-title">Resource & Schedule</div></div>
                    </div>

                    <div style="margin-bottom:14px">
                        <label class="field-label">Select Asset / Resource</label>
                        <select id="resourceSelect" name="resource_id" class="field-input" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>" data-name="<?= htmlspecialchars($res['name']) ?>"><?= htmlspecialchars($res['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="pcSection" style="display:none;margin-bottom:14px" class="pc-section">
                        <label class="field-label" style="color:var(--indigo);margin-bottom:10px;display:block">Assign Workstation(s)</label>
                        <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:8px">
                            <?php foreach ($pcs as $pc): ?>
                                <button type="button" onclick="togglePc('<?= htmlspecialchars($pc['pc_number']) ?>',this)" data-pc="<?= htmlspecialchars($pc['pc_number']) ?>" class="pc-btn"><?= htmlspecialchars($pc['pc_number']) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <p style="font-size:.72rem;color:var(--indigo);font-weight:600;margin-top:10px">Selected: <span id="pcSelectedLabel">None</span></p>
                    </div>

                    <!-- Date / time — custom pickers -->
                    <div class="grid-3" style="margin-bottom:14px">
                        <!-- DATE -->
                        <div>
                            <label class="field-label">Date</label>
                            <div class="picker-wrap" id="dateWrap">
                                <div class="dt-trigger" id="dateTrigger">
                                    <span id="dateLabel">Pick a date</span>
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="1" y="3" width="14" height="12" rx="2.5" stroke="#818cf8" stroke-width="1.4"/><path d="M5 1v3M11 1v3M1 7h14" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop cal" id="calDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="reservation_date" id="resDate" value="<?= date('Y-m-d') ?>">
                        </div>
                        <!-- START TIME -->
                        <div>
                            <label class="field-label">Start Time</label>
                            <div class="picker-wrap" id="startWrap">
                                <div class="dt-trigger" id="startTrigger">
                                    <span id="startLabel">Select time</span>
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="#818cf8" stroke-width="1.4"/><path d="M8 4.5V8l2.5 2.5" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop tim" id="startDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="start_time" id="startTime">
                        </div>
                        <!-- END TIME -->
                        <div>
                            <label class="field-label">End Time</label>
                            <div class="picker-wrap" id="endWrap">
                                <div class="dt-trigger" id="endTrigger">
                                    <span id="endLabel">Select time</span>
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="#818cf8" stroke-width="1.4"/><path d="M8 4.5V8l2.5 2.5" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop tim" id="endDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="end_time" id="endTime">
                        </div>
                    </div>

                    <div style="margin-bottom:14px">
                        <label class="field-label">Purpose of Visit</label>
                        <select id="purposeSelect" class="field-input" required>
                            <option value="">— Select purpose —</option>
                            <option>Work</option>
                            <option>Personal</option>
                            <option>Study</option>
                            <option>SK Activity</option>
                            <option>Others</option>
                        </select>
                    </div>
                    <div id="purposeOtherWrap" style="display:none">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" class="field-input" placeholder="Describe the purpose…">
                    </div>
                </div>

                <button type="button" onclick="previewReservation()" class="submit-btn">
                    <i class="fa-solid fa-eye" style="font-size:.85rem"></i> Preview & Confirm
                </button>
            </form>
        </div>
    </main>

    <script>
        const allUsers = <?= json_encode($users ?? []) ?>;
        let currentType = 'User', selectedUser = null, selectedPcs = [];

        function setType(type) {
            currentType = type;
            document.getElementById('finalVisitorType').value = type;
            const isUser = type === 'User';
            document.getElementById('btnUser').classList.toggle('active', isUser);
            document.getElementById('btnVisitor').classList.toggle('active', !isUser);
            document.getElementById('userFields').style.display = isUser ? 'grid' : 'none';
            document.getElementById('visitorFields').style.display = isUser ? 'none' : 'grid';
            selectedUser = null;
            ['userNameInput','userEmailDisplay','visitorNameInput','visitorEmailInput'].forEach(id => {
                const el = document.getElementById(id); if (el) el.value = '';
            });
            document.getElementById('finalUserId').value = '';
        }

        const userNameInput    = document.getElementById('userNameInput');
        const autocompleteList = document.getElementById('autocompleteList');

        userNameInput.addEventListener('input', () => {
            const q = userNameInput.value.toLowerCase().trim();
            autocompleteList.innerHTML = '';
            selectedUser = null;
            if (!q) { autocompleteList.style.display = 'none'; return; }
            const matches = allUsers.filter(u =>
                (u.name || '').toLowerCase().includes(q) ||
                (u.full_name || '').toLowerCase().includes(q) ||
                (u.email || '').toLowerCase().includes(q)
            ).slice(0, 8);
            if (!matches.length) { autocompleteList.style.display = 'none'; return; }
            matches.forEach(u => {
                const displayName = u.full_name || u.name || '';
                const li = document.createElement('li');
                li.className = 'autocomplete-item';
                li.innerHTML = `<div style="font-weight:600">${displayName}</div><div class="sub">${u.email}</div>`;
                li.addEventListener('mousedown', () => {
                    selectedUser = u;
                    userNameInput.value = displayName;
                    document.getElementById('userEmailDisplay').value = u.email;
                    document.getElementById('finalUserId').value = u.id;
                    autocompleteList.style.display = 'none';
                });
                autocompleteList.appendChild(li);
            });
            autocompleteList.style.display = 'block';
        });
        userNameInput.addEventListener('blur', () => setTimeout(() => autocompleteList.style.display = 'none', 150));

        document.getElementById('resourceSelect').addEventListener('change', function() {
            const name = (this.options[this.selectedIndex]?.dataset.name || '').toLowerCase();
            const showPcs = name.includes('computer') || name.includes('pc') || name.includes('lab');
            document.getElementById('pcSection').style.display = showPcs ? 'block' : 'none';
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(b => b.classList.remove('selected'));
        });

        function togglePc(num, btn) {
            const idx = selectedPcs.indexOf(num);
            if (idx === -1) { selectedPcs.push(num); btn.classList.add('selected'); }
            else            { selectedPcs.splice(idx, 1); btn.classList.remove('selected'); }
            updatePcHidden();
        }
        function updatePcHidden() {
            document.getElementById('finalPcs').value = JSON.stringify(selectedPcs);
            document.getElementById('pcSelectedLabel').textContent = selectedPcs.length ? selectedPcs.join(', ') : 'None';
        }

        document.getElementById('purposeSelect').addEventListener('change', function() {
            document.getElementById('purposeOtherWrap').style.display = this.value === 'Others' ? 'block' : 'none';
        });

        function previewReservation() {
            const isUser = currentType === 'User';
            const name   = isUser ? userNameInput.value.trim() : document.getElementById('visitorNameInput').value.trim();
            const email  = isUser ? document.getElementById('userEmailDisplay').value.trim() : document.getElementById('visitorEmailInput').value.trim();
            const resourceEl   = document.getElementById('resourceSelect');
            const resourceName = resourceEl.options[resourceEl.selectedIndex]?.text || '—';
            const showPcs      = document.getElementById('pcSection').style.display !== 'none';
            const date      = document.getElementById('resDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime   = document.getElementById('endTime').value;
            const purposeVal   = document.getElementById('purposeSelect').value;
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ? `Others — ${purposeOther}` : purposeVal;

            if (!name)              return alert('Please enter a name.');
            if (!resourceEl.value)  return alert('Please select a resource.');
            if (showPcs && !selectedPcs.length) return alert('Please select at least one workstation.');
            if (!date)              return alert('Please select a date.');
            if (!startTime)         return alert('Please enter a start time.');
            if (!endTime)           return alert('Please enter an end time.');
            if (!purposeVal)        return alert('Please select a purpose.');
            if (isUser && !selectedUser && !document.getElementById('finalUserId').value)
                return alert('Please select a registered user from the dropdown.');

            document.getElementById('finalVisitorName').value = name;
            document.getElementById('finalUserEmail').value   = email;
            document.getElementById('finalPurpose').value     = purposeFinal;

            const rows = [
                ['Type',         isUser ? 'Registered User' : 'Walk-in Visitor'],
                ['Name',         name || '—'],
                ['Email',        email || '—'],
                ['Resource',     resourceName],
                ['Workstations', selectedPcs.length ? selectedPcs.join(', ') : '—'],
                ['Date',         document.getElementById('dateLabel').textContent],
                ['Time',         `${document.getElementById('startLabel').textContent} – ${document.getElementById('endLabel').textContent}`],
                ['Purpose',      purposeFinal || '—']
            ];
            document.getElementById('modalSummaryBox').innerHTML =
                rows.map(([l,v]) => `<div class="mrow"><span class="mrow-label">${l}</span><span class="mrow-value">${v}</span></div>`).join('');
            document.getElementById('qrWrap').style.display  = 'none';
            document.getElementById('confirmBtn').style.display = 'flex';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.8rem"></i> Saving…';
            const code = `SK-${Date.now()}`;
            document.getElementById('qrText').textContent = code;
            QRCode.toCanvas(document.getElementById('qrCanvas'), code,
                { width: 160, margin: 1, color: { dark: '#1e293b', light: '#ffffff' } },
                () => {
                    document.getElementById('qrWrap').style.display = 'flex';
                    btn.style.display = 'none';
                    setTimeout(() => document.getElementById('reservationForm').submit(), 800);
                }
            );
        }

        function downloadQR() {
            const canvas = document.getElementById('qrCanvas');
            const code   = document.getElementById('qrText').textContent;
            const link   = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href     = canvas.toDataURL('image/png');
            link.click();
        }

        function openModal()  { document.getElementById('confirmModal').classList.add('show'); document.body.style.overflow = 'hidden'; }
        function closeModal() {
            document.getElementById('confirmModal').classList.remove('show');
            document.body.style.overflow = '';
            const btn = document.getElementById('confirmBtn');
            btn.disabled = false;
            btn.style.display = 'flex';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>

    <!-- Custom Date / Time Picker JS -->
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
            ['calDrop','startDrop','endDrop'].forEach(id => { const el=$(id); if(el) el.style.display='none'; });
            ['dateTrigger','startTrigger','endTrigger'].forEach(id => { const el=$(id); if(el) el.classList.remove('open'); });
            activeDrop = null;
        }
        function toggle(dropId, triggerId) {
            if (activeDrop === dropId) { closeAll(); return; }
            closeAll(); activeDrop = dropId;
            const drop = $(dropId);
            drop.style.left = '0';
            drop.style.right = '';
            drop.style.display = 'block';
            $(triggerId).classList.add('open');
            // Smart edge detection — shift left if overflowing right edge
            const rect = drop.getBoundingClientRect();
            const vw = window.innerWidth;
            if (rect.right > vw - 8) {
                const overflow = rect.right - (vw - 8);
                drop.style.left = Math.max(-rect.left + 8, -overflow) + 'px';
            }
        }
        document.addEventListener('click', e => { if (!e.target.closest('.picker-wrap')) closeAll(); });

        function renderCal() {
            const {y,m} = calView;
            const firstDow=new Date(y,m,1).getDay(), daysInM=new Date(y,m+1,0).getDate(), prevTotal=new Date(y,m,0).getDate();
            let html=`<div class="cal-head"><div class="cal-nav-btn" id="_calPrev">&#8249;</div><div class="cal-month-label">${MONTHS[m]} ${y}</div><div class="cal-nav-btn" id="_calNext">&#8250;</div></div><div class="cal-grid">${DOWS.map(d=>`<div class="cal-dow">${d}</div>`).join('')}`;
            for(let i=0;i<firstDow;i++) html+=`<div class="cal-day cal-other">${prevTotal-firstDow+1+i}</div>`;
            for(let d=1;d<=daysInM;d++){
                const isToday=d===TODAY.getDate()&&m===TODAY.getMonth()&&y===TODAY.getFullYear();
                const isSel=selDate&&selDate.d===d&&selDate.m===m&&selDate.y===y;
                html+=`<div class="${['cal-day',isToday&&!isSel?'cal-today':'',isSel?'cal-selected':''].filter(Boolean).join(' ')}" data-d="${d}">${d}</div>`;
            }
            const trail=(7-(firstDow+daysInM)%7)%7;
            for(let i=1;i<=trail;i++) html+=`<div class="cal-day cal-other">${i}</div>`;
            html+=`</div><div class="cal-footer"><span class="cal-foot-btn" id="_calClear">Clear</span><span class="cal-foot-btn today" id="_calToday">Today</span></div>`;
            $('calDrop').innerHTML=html;
            $('_calPrev').addEventListener('click',e=>{e.stopPropagation();moveMonth(-1);});
            $('_calNext').addEventListener('click',e=>{e.stopPropagation();moveMonth(1);});
            $('_calClear').addEventListener('click',e=>{e.stopPropagation();clearDate();});
            $('_calToday').addEventListener('click',e=>{e.stopPropagation();gotoToday();});
            $('calDrop').querySelectorAll('.cal-day[data-d]').forEach(el=>el.addEventListener('click',e=>{e.stopPropagation();pickDay(+el.dataset.d);}));
        }
        function moveMonth(dir){ calView.m+=dir; if(calView.m>11){calView.m=0;calView.y++;} if(calView.m<0){calView.m=11;calView.y--;} renderCal(); }
        function pickDay(d){
            selDate={d,m:calView.m,y:calView.y};
            const dt=new Date(calView.y,calView.m,d);
            const iso=`${calView.y}-${String(calView.m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            $('resDate').value=iso;
            $('dateLabel').textContent=dt.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
            $('dateTrigger').classList.add('has-value');
            renderCal(); setTimeout(closeAll,180);
        }
        function clearDate(){ selDate=null;$('resDate').value='';$('dateLabel').textContent='Pick a date';$('dateTrigger').classList.remove('has-value');renderCal(); }
        function gotoToday(){ calView={y:TODAY.getFullYear(),m:TODAY.getMonth()};pickDay(TODAY.getDate()); }

        function renderTime(which){
            const dropId=which==='start'?'startDrop':'endDrop';
            const st=tState[which];
            const hItems=Array.from({length:12},(_,i)=>i+1).map(h=>`<div class="tim-item${st.h===h?' sel':''}" data-part="h" data-val="${h}">${String(h).padStart(2,'0')}</div>`).join('');
            const mItems=MINS.map(mn=>`<div class="tim-item${st.min===mn?' sel':''}" data-part="min" data-val="${mn}">${String(mn).padStart(2,'0')}</div>`).join('');
            $(dropId).innerHTML=`<div class="tim-title">Select Time</div><div class="tim-cols"><div class="tim-col" id="_tc_h_${which}">${hItems}</div><div class="tim-sep">:</div><div class="tim-col" id="_tc_m_${which}">${mItems}</div><div class="ampm-col"><div class="ampm-btn${st.ampm==='am'?' sel':''}" data-ampm="am">AM</div><div class="ampm-btn${st.ampm==='pm'?' sel':''}" data-ampm="pm">PM</div></div></div><button class="tim-set-btn" id="_timSet_${which}">Set Time</button>`;
            setTimeout(()=>{
                const sH=$(dropId).querySelector('#_tc_h_'+which+' .sel');
                const sM=$(dropId).querySelector('#_tc_m_'+which+' .sel');
                if(sH)sH.scrollIntoView({block:'center',behavior:'instant'});
                if(sM)sM.scrollIntoView({block:'center',behavior:'instant'});
            },0);
            $(dropId).querySelectorAll('.tim-item').forEach(el=>el.addEventListener('click',e=>{e.stopPropagation();tState[which][el.dataset.part]=+el.dataset.val;renderTime(which);}));
            $(dropId).querySelectorAll('.ampm-btn').forEach(el=>el.addEventListener('click',e=>{e.stopPropagation();tState[which].ampm=el.dataset.ampm;renderTime(which);}));
            $(`_timSet_${which}`).addEventListener('click',e=>{e.stopPropagation();applyTime(which);});
        }
        function applyTime(which){
            const st=tState[which];
            const label=`${String(st.h).padStart(2,'0')}:${String(st.min).padStart(2,'0')} ${st.ampm.toUpperCase()}`;
            let h24=st.h;
            if(st.ampm==='am'&&st.h===12)h24=0;
            if(st.ampm==='pm'&&st.h!==12)h24=st.h+12;
            const iso24=`${String(h24).padStart(2,'0')}:${String(st.min).padStart(2,'0')}`;
            const labelId=which==='start'?'startLabel':'endLabel';
            const inputId=which==='start'?'startTime':'endTime';
            const triggerId=which==='start'?'startTrigger':'endTrigger';
            $(labelId).textContent=label;$(inputId).value=iso24;$(triggerId).classList.add('has-value');
            closeAll();
        }

        $('dateTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('calDrop','dateTrigger');if(activeDrop==='calDrop')renderCal();});
        $('startTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('startDrop','startTrigger');if(activeDrop==='startDrop')renderTime('start');});
        $('endTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('endDrop','endTrigger');if(activeDrop==='endDrop')renderTime('end');});

        (function(){
            const t=new Date();
            $('dateLabel').textContent=t.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
            $('dateTrigger').classList.add('has-value');
            selDate={d:t.getDate(),m:t.getMonth(),y:t.getFullYear()};
        })();
    })();
    </script>
</body>
</html>