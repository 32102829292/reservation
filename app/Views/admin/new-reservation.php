<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <script>
        (function() {
            if (localStorage.getItem('admin_theme') === 'dark') document.documentElement.classList.add('dark-pre');
        })();
    </script>

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
        @media(min-width:400px) and (max-width:639px) {
            .grid-3 { grid-template-columns: 1fr 1fr; }
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
       <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <script>
        (function() {
            if (localStorage.getItem('admin_theme') === 'dark') document.documentElement.classList.add('dark-pre');
        })();
    </script>

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
           FIELD GRIDS
        ══════════════════════════════ */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        @media(max-width:639px) {
            .grid-2 { grid-template-columns: 1fr; gap: 12px; }
            .grid-3 { grid-template-columns: 1fr; gap: 12px; }
        }
        @media(min-width:400px) and (max-width:639px) {
            .grid-3 { grid-template-columns: 1fr 1fr; }
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
           AVAILABILITY STATUS
        ══════════════════════════════ */
        #availabilityStatus {
            display: none;
            margin-top: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            align-items: center;
            gap: 10px;
            transition: all .2s;
        }
        #availabilityStatus.av-checking { display:flex; background:#f0f9ff; border:1px solid #bae6fd; color:#0369a1; }
        #availabilityStatus.av-ok       { display:flex; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
        #availabilityStatus.av-conflict { display:flex; background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }
        #availabilityStatus.av-info     { display:flex; background:#fefce8; border:1px solid #fde68a; color:#92400e; }
        .av-icon { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; }
        .av-checking .av-icon { background:#e0f2fe; }
        .av-ok       .av-icon { background:#dcfce7; }
        .av-conflict .av-icon { background:#fee2e2; }
        .av-info     .av-icon { background:#fef9c3; }

        /* Booked slots table */
        #bookedSlotsWrap {
            display: none;
            margin-top: 10px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(99,102,241,.1);
        }
        .bs-header {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 13px; background: #f8fafc;
            border-bottom: 1px solid rgba(99,102,241,.08);
            font-size: .7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em; color: #94a3b8;
        }
        .bs-row {
            display: flex; align-items: center; gap: 0;
            padding: 9px 13px;
            border-bottom: 1px solid rgba(99,102,241,.05);
            font-size: .8rem; background: white;
        }
        .bs-row:last-child { border-bottom: none; }
        .bs-row:hover { background: #f8fafc; }
        .bs-col { flex: 1; }
        .bs-status-pill { display:inline-flex; align-items:center; gap:5px; padding:3px 9px; border-radius:20px; font-size:.68rem; font-weight:700; }
        .bs-pill-approved { background:#dcfce7; color:#15803d; }
        .bs-pill-pending  { background:#fef3c7; color:#92400e; }
        .bs-conflict-row  { background:#fef2f2 !important; }

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
           REGISTERED-USER WARNING BOX
        ══════════════════════════════ */
        .reg-warn-box {
            display: none;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 10px;
            margin-bottom: 12px;
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #9a3412;
            font-size: .82rem;
            font-weight: 600;
        }
        body.dark .reg-warn-box {
            background: rgba(234,88,12,.1);
            border-color: rgba(251,146,60,.3);
            color: #fb923c;
        }

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
           CONFIRMATION CODE WIDGET
        ══════════════════════════════ */
        .confirm-code-box {
            background: rgba(99,102,241,.06);
            border: 1px solid rgba(99,102,241,.22);
            border-radius: 14px;
            padding: 18px 20px;
            margin-top: 4px;
        }
        .confirm-code-header { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .confirm-code-icon {
            width: 34px; height: 34px;
            background: var(--indigo-light);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .gen-code-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            background: var(--indigo); color: white;
            border-radius: 9px; font-size: .78rem; font-weight: 700;
            border: none; cursor: pointer; font-family: var(--font);
            transition: background .15s;
        }
        .gen-code-btn:hover { background: #4338ca; }
        .code-display-pill {
            display: inline-flex; align-items: center; gap: 10px;
            background: white;
            border: 2px solid var(--indigo);
            border-radius: 12px;
            padding: 8px 20px;
            margin-left: 10px;
        }
        .code-digits {
            font-family: var(--mono);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--indigo);
            letter-spacing: .22em;
        }
        .pin-row { display: flex; gap: 10px; margin-top: 12px; margin-bottom: 8px; }
        .pin-box {
            width: 52px; height: 56px;
            text-align: center;
            font-size: 1.3rem; font-weight: 800;
            font-family: var(--mono);
            border: 2px solid rgba(99,102,241,.25);
            border-radius: 12px;
            background: white; color: #0f172a;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            caret-color: var(--indigo);
        }
        .pin-box:focus { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .pin-box.pin-ok  { border-color: #16a34a; background: #f0fdf4; }
        .pin-box.pin-err { border-color: #dc2626; background: #fef2f2; }
        .pin-feedback { font-size: .78rem; font-weight: 700; min-height: 20px; margin-top: 2px; }

        /* ══════════════════════════════
           ANIMATIONS
        ══════════════════════════════ */
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
        @keyframes sheetUp { from { opacity:0; transform:translateY(60px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
        @keyframes shake   { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-5px)} 40%,80%{transform:translateX(5px)} }
        @keyframes spin    { to { transform: rotate(360deg); } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .shake     { animation: shake .35s ease; }
        .spin-icon { animation: spin 1s linear infinite; display: inline-block; }

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

        .dt-drop { position: absolute; bottom: calc(100% + 6px); left: 0; z-index: 9999; border-radius: 14px; animation: dtDrop .15s cubic-bezier(.4,0,.2,1); }
        @keyframes dtDrop { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
        body:not(.dark) .dt-drop { background:#fff; border:1px solid rgba(99,102,241,.18); box-shadow:0 20px 50px rgba(15,23,42,.18); }
        body.dark      .dt-drop { background:#0e1828; border:1px solid rgba(99,102,241,.22); box-shadow:0 20px 60px rgba(0,0,0,.65); }

        .dt-drop.cal { width: 288px; padding: 18px 16px 14px; }
        @media(max-width:380px) { .dt-drop.cal { width: 260px; } }

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
        body:not(.dark) .cal-day:hover:not(.cal-other):not(.cal-selected):not(.cal-past) { background:#f1f5f9; color:#0f172a; border-color:#e2e8f0; }
        body.dark .cal-day:hover:not(.cal-other):not(.cal-selected):not(.cal-past) { background:rgba(255,255,255,.06); color:#e2e8f0; border-color:rgba(255,255,255,.08); }
        .cal-day.cal-other { pointer-events:none; }
        body:not(.dark) .cal-day.cal-other { color:#cbd5e1; }
        body.dark .cal-day.cal-other { color:#2e3850; }
        .cal-day.cal-past { pointer-events:none; opacity:.35; cursor:default; }
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
           CUSTOM SELECT (CS)
        ══════════════════════════════ */
        .cs-wrap { position: relative; }
        .cs-trigger {
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px; width: 100%; padding: .75rem 1rem;
            background: var(--card); border: 1px solid rgba(99,102,241,.15);
            border-radius: var(--r-sm); font-family: var(--font);
            font-size: .88rem; font-weight: 500; color: #94a3b8;
            cursor: pointer; transition: border .18s, box-shadow .18s;
            user-select: none; -webkit-user-select: none; outline: none;
        }
        .cs-trigger.has-value { color: #0f172a; }
        .cs-trigger:hover { border-color: rgba(99,102,241,.35); }
        .cs-trigger.open { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .cs-arrow { width: 16px; height: 16px; flex-shrink: 0; opacity: .4; transition: transform .18s, opacity .18s; }
        .cs-trigger.open .cs-arrow { transform: rotate(180deg); opacity: .75; }
        .cs-drop {
            position: absolute; bottom: calc(100% + 5px); left: 0; right: 0;
            z-index: 9999; background: white;
            border: 1px solid rgba(99,102,241,.18); border-radius: var(--r-md);
            box-shadow: 0 16px 40px rgba(15,23,42,.14);
            overflow: hidden; display: none;
            animation: csDropIn .14s cubic-bezier(.4,0,.2,1);
        }
        @keyframes csDropIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:none; } }
        .cs-opt {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 13px; font-size: .87rem; font-weight: 500;
            color: #0f172a; cursor: pointer; transition: background .1s;
            border-bottom: 1px solid rgba(99,102,241,.06);
        }
        .cs-opt:last-child { border-bottom: none; }
        .cs-opt:hover { background: var(--indigo-light); color: var(--indigo); }
        .cs-opt.cs-placeholder { color: #94a3b8; font-weight: 400; font-size: .82rem; }
        .cs-opt.cs-selected { background: rgba(99,102,241,.07); color: var(--indigo); }
        .cs-opt-icon { width: 26px; height: 26px; border-radius: 7px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; }
        .cs-opt-label { flex: 1; }
        .cs-check { width: 14px; height: 14px; flex-shrink: 0; color: var(--indigo); opacity: 0; transition: opacity .12s; }
        .cs-opt.cs-selected .cs-check { opacity: 1; }
        .cs-divider { height: 1px; background: rgba(99,102,241,.08); margin: 3px 0; }

        /* ══════════════════════════════
           DARK MODE
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
        body.dark select.field-input { background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8; }
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
        body.dark .cs-trigger { background: #101e35; border-color: rgba(99,102,241,.18); color: #4a6fa5; }
        body.dark .cs-trigger.has-value { color: #e2eaf8; }
        body.dark .cs-trigger.open { background: #0b1628; border-color: var(--indigo); }
        body.dark .cs-drop { background: #0b1628; border-color: rgba(99,102,241,.22); box-shadow: 0 16px 40px rgba(0,0,0,.5); }
        body.dark .cs-opt { color: #e2eaf8; border-color: rgba(99,102,241,.06); }
        body.dark .cs-opt:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .cs-opt.cs-selected { background: rgba(99,102,241,.15); color: #a5b4fc; }
        body.dark .cs-opt.cs-placeholder { color: #4a6fa5; }
        body.dark .cs-divider { background: rgba(99,102,241,.1); }
        body.dark .confirm-code-box { background: rgba(55,48,163,.12); border-color: rgba(99,102,241,.25); }
        body.dark .confirm-code-icon { background: rgba(55,48,163,.3); }
        body.dark .code-display-pill { background: #060e1e; }
        body.dark .pin-box { background: #101e35; border-color: rgba(99,102,241,.25); color: #e2eaf8; }
        body.dark .pin-box.pin-ok  { background: rgba(22,163,74,.1);  border-color: #16a34a; }
        body.dark .pin-box.pin-err { background: rgba(220,38,38,.1);  border-color: #dc2626; }
        body.dark #availabilityStatus.av-checking { background: rgba(3,105,161,.12); border-color: rgba(186,230,253,.2); color: #7dd3fc; }
        body.dark #availabilityStatus.av-ok       { background: rgba(21,128,61,.12);  border-color: rgba(187,247,208,.2); color: #86efac; }
        body.dark #availabilityStatus.av-conflict { background: rgba(185,28,28,.12);  border-color: rgba(254,202,202,.2); color: #fca5a5; }
        body.dark #availabilityStatus.av-info     { background: rgba(146,64,14,.12);  border-color: rgba(253,230,138,.2); color: #fcd34d; }
        body.dark #bookedSlotsWrap { border-color: rgba(99,102,241,.15); }
        body.dark .bs-header { background: #101e35; color: #4a6fa5; border-color: rgba(99,102,241,.1); }
        body.dark .bs-row { background: #0b1628; border-color: rgba(99,102,241,.07); color: #e2eaf8; }
        body.dark .bs-row:hover { background: #101e35; }
        body.dark .bs-conflict-row { background: rgba(185,28,28,.1) !important; }
    </style>
</head>

<body>
    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal-back" onclick="if(event.target===this)closeModal()">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px">
                <div style="width:52px;height:52px;background:var(--indigo-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                    <i class="fa-solid fa-clipboard-check" style="font-size:1.3rem;color:var(--indigo)"></i>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:#94a3b8;margin-top:4px">Review details before saving.</p>
            </div>
            <div id="modalSummaryBox" style="background:#f8fafc;border-radius:var(--r-md);padding:14px 16px;border:1px solid rgba(99,102,241,.08);margin-bottom:14px"></div>
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
        <div class="page-header fade-up">
            <div>
                <p class="eyebrow">Administration</p>
                <h2 class="greeting-name">New Reservation</h2>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Register a manual entry into the system.</p>
            </div>
            <div class="page-header-actions">
                <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <a href="/admin/manage-reservations" class="back-btn">
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
            <form id="reservationForm" method="POST" action="<?= base_url('admin/create-reservation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="visitor_name"  id="finalVisitorName">
                <input type="hidden" name="user_email"    id="finalUserEmail">
                <input type="hidden" name="user_id"       id="finalUserId">
                <input type="hidden" name="visitor_type"  id="finalVisitorType" value="User">
                <input type="hidden" name="purpose"       id="finalPurpose">
                <input type="hidden" name="pcs"           id="finalPcs" value="[]">
                <input type="hidden" name="confirm_code"  id="confirmCodeValue" value="">
                <select name="resource_id" id="nativeResource" style="display:none" required></select>
                <select name="purpose_select" id="nativePurpose" style="display:none"></select>

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
                        <div class="section-icon"><i class="fa-solid fa-id-card" style="color:var(--indigo);font-size:.9rem"></i></div>
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

                    <div id="visitorFields" style="display:none">
                        <div class="grid-2" style="margin-bottom:12px">
                            <div>
                                <label class="field-label">Full Name <span style="color:#ef4444;font-size:.75rem">*</span></label>
                                <input type="text" id="visitorNameInput" class="field-input"
                                       placeholder="Enter visitor's full name"
                                       oninput="schedGuestCheck()" onblur="runGuestCheck()">
                            </div>
                            <div>
                                <label class="field-label">
                                    Email Address
                                    <span style="font-size:.68rem;color:#94a3b8;font-weight:400;margin-left:4px">(optional)</span>
                                </label>
                                <input type="email" id="visitorEmailInput" class="field-input"
                                       placeholder="Enter email if available">
                            </div>
                        </div>

                        <div id="registeredWarnBox" class="reg-warn-box">
                            <i class="fa-solid fa-triangle-exclamation" style="margin-top:1px;flex-shrink:0;color:#ea580c"></i>
                            <div>
                                <div id="regWarnTitle" style="font-weight:800;margin-bottom:2px"></div>
                                <div style="font-weight:500;opacity:.85">
                                    Please switch to <strong>Registered User</strong> and select them from the dropdown
                                    so the reservation is counted under their account, not as a walk-in guest slot.
                                </div>
                            </div>
                        </div>

                        <div id="guestLimitBox" style="display:none;margin-bottom:12px">
                            <div id="guestLimitInner" style="display:flex;align-items:center;gap:12px;padding:11px 15px;border-radius:10px;border:1px solid;font-size:.8rem;font-weight:600;transition:all .2s">
                                <div id="guestLimitIcon" style="width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.85rem"></div>
                                <div style="flex:1;min-width:0">
                                    <div id="guestLimitTitle" style="font-weight:700;font-size:.82rem"></div>
                                    <div id="guestLimitSub"   style="font-size:.7rem;margin-top:1px;opacity:.8"></div>
                                </div>
                                <div id="guestLimitPill" style="padding:4px 10px;border-radius:20px;font-size:.72rem;font-weight:800;letter-spacing:.04em;white-space:nowrap"></div>
                            </div>
                            <div style="margin-top:8px;height:5px;background:#e2e8f0;border-radius:999px;overflow:hidden">
                                <div id="guestLimitBar" style="height:100%;border-radius:999px;transition:width .4s ease,background .3s"></div>
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-top:4px">
                                <span style="font-size:.6rem;color:#94a3b8;font-weight:600">Reservations (last 3 days)</span>
                                <span id="guestLimitCount" style="font-size:.6rem;color:#94a3b8;font-weight:700"></span>
                            </div>
                        </div>

                        <div id="confirmCodeSection" style="display:none">
                            <div class="confirm-code-box">
                                <div class="confirm-code-header">
                                    <div class="confirm-code-icon">
                                        <i class="fa-solid fa-shield-halved" style="color:var(--indigo);font-size:.9rem"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:800;color:#3730a3">New visitor — identity confirmation</div>
                                        <div style="font-size:.7rem;color:#6366f1;margin-top:2px;font-weight:500">
                                            Generate a 4-digit code → read it to the visitor → they repeat it back
                                        </div>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px">
                                    <button type="button" onclick="generateConfirmCode()" id="genCodeBtn" class="gen-code-btn">
                                        <i class="fa-solid fa-arrows-rotate" style="font-size:.75rem"></i>
                                        Generate Code
                                    </button>
                                    <div id="codeDisplayWrap" style="display:none">
                                        <div class="code-display-pill">
                                            <span class="code-digits" id="codeDigits"></span>
                                        </div>
                                        <span style="font-size:.68rem;color:#94a3b8;font-weight:600;margin-left:8px;display:inline-block;margin-top:4px">← Read aloud to visitor</span>
                                    </div>
                                </div>
                                <div id="pinInputArea" style="display:none">
                                    <label class="field-label" style="color:#3730a3;margin-bottom:8px;display:block;font-size:.75rem">
                                        <i class="fa-solid fa-keyboard" style="margin-right:5px;font-size:.7rem"></i>
                                        Visitor enters the code:
                                    </label>
                                    <div class="pin-row">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin0" oninput="pinAdvance(this,1)" onkeydown="pinBack(event,this,null)">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin1" oninput="pinAdvance(this,2)" onkeydown="pinBack(event,this,0)">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin2" oninput="pinAdvance(this,3)" onkeydown="pinBack(event,this,1)">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin3" oninput="pinFinish(this)" onkeydown="pinBack(event,this,2)">
                                    </div>
                                    <div id="pinFeedback" class="pin-feedback"></div>
                                </div>
                                <input type="hidden" id="confirmCodeVerified" value="0">
                            </div>
                        </div>
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
                        <div class="cs-wrap" id="resourceWrap">
                            <div class="cs-trigger" id="resourceTrigger">
                                <span id="resourceLabel">— Choose a resource —</span>
                                <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="cs-drop" id="resourceDrop">
                                <div class="cs-opt cs-placeholder" data-value=""><span class="cs-opt-label">— Choose a resource —</span></div>
                                <div class="cs-divider"></div>
                                <?php foreach ($resources ?? [] as $res):
                                    $rname  = htmlspecialchars($res['name']);
                                    $lower  = strtolower($res['name']);
                                    $hasPcs = (strpos($lower,'computer')!==false||strpos($lower,'pc')!==false||strpos($lower,'lab')!==false)?'1':'0';
                                    $isWifi = (strpos($lower,'wifi')!==false)?'1':'0';
                                ?>
                                <div class="cs-opt"
                                     data-value="<?= $res['id'] ?>"
                                     data-name="<?= $rname ?>"
                                     data-has-pcs="<?= $hasPcs ?>"
                                     data-is-wifi="<?= $isWifi ?>">
                                    <div class="cs-opt-icon" style="background:rgba(99,102,241,.1)">
                                        <?php if ($isWifi === '1'): ?>
                                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M1 5.5C4.1 2.8 8 2 8 2s3.9.8 7 3.5" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/><path d="M3.5 8C5.2 6.4 8 6 8 6s2.8.4 4.5 2" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/><path d="M6 10.5C6.7 9.9 8 9.7 8 9.7s1.3.2 2 .8" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/><circle cx="8" cy="13" r="1" fill="#6366f1"/></svg>
                                        <?php else: ?>
                                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="1" y="2" width="14" height="10" rx="2" stroke="#6366f1" stroke-width="1.3"/><path d="M5 15h6M8 12v3" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <span class="cs-opt-label"><?= $rname ?></span>
                                    <?php if ($isWifi === '1'): ?>
                                    <span style="font-size:.6rem;font-weight:700;background:rgba(99,102,241,.1);color:#6366f1;padding:2px 7px;border-radius:20px;letter-spacing:.04em">NO LIMIT</span>
                                    <?php endif; ?>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="wifiNoticeBanner" style="display:none;margin-top:8px;padding:9px 13px;background:#ede9fe;border:1px solid #c4b5fd;border-radius:9px;font-size:.78rem;font-weight:600;color:#4c1d95;align-items:center;gap:8px">
                            <i class="fa-solid fa-wifi" style="color:#7c3aed;font-size:.8rem"></i>
                            WiFi reservations have <strong>no booking limit</strong> — quota and fairness checks are skipped.
                        </div>
                    </div>

                    <div id="pcSection" style="display:none;margin-bottom:14px" class="pc-section">
                        <label class="field-label" style="color:var(--indigo);margin-bottom:10px;display:block">Assign Workstation(s)</label>
                        <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:8px">
                            <?php foreach ($pcs ?? [] as $pc): ?>
                                <button type="button" onclick="togglePc('<?= htmlspecialchars($pc['pc_number']) ?>',this)" data-pc="<?= htmlspecialchars($pc['pc_number']) ?>" class="pc-btn"><?= htmlspecialchars($pc['pc_number']) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <p style="font-size:.72rem;color:var(--indigo);font-weight:600;margin-top:10px">Selected: <span id="pcSelectedLabel">None</span></p>
                    </div>

                    <div class="grid-3" style="margin-bottom:14px">
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

                    <div id="availabilityStatus">
                        <div class="av-icon" id="avIcon"></div>
                        <div style="flex:1">
                            <div id="avTitle" style="font-weight:700;font-size:.82rem"></div>
                            <div id="avSub"   style="font-size:.7rem;margin-top:1px;opacity:.85"></div>
                        </div>
                        <div id="avPill" style="padding:3px 9px;border-radius:20px;font-size:.68rem;font-weight:800;letter-spacing:.04em;white-space:nowrap"></div>
                    </div>

                    <div id="bookedSlotsWrap">
                        <div class="bs-header">
                            <i class="fa-solid fa-clock" style="font-size:.65rem"></i>
                            Other bookings on this date
                        </div>
                        <div id="bookedSlotsList"></div>
                    </div>

                    <div style="margin-bottom:14px;margin-top:14px">
                        <label class="field-label">Purpose of Visit</label>
                        <div class="cs-wrap" id="purposeWrap">
                            <div class="cs-trigger" id="purposeTrigger">
                                <span id="purposeLabel">— Select purpose —</span>
                                <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="cs-drop" id="purposeDrop">
                                <div class="cs-opt cs-placeholder" data-value=""><span class="cs-opt-label">— Select purpose —</span></div>
                                <div class="cs-divider"></div>
                                <div class="cs-opt" data-value="Work">
                                    <div class="cs-opt-icon" style="background:rgba(99,102,241,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="2" y="5" width="12" height="9" rx="1.5" stroke="#6366f1" stroke-width="1.3"/><path d="M5 5V4a3 3 0 016 0v1" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/></svg></div>
                                    <span class="cs-opt-label">Work</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="Personal">
                                    <div class="cs-opt-icon" style="background:rgba(236,72,153,.09)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5" r="3" stroke="#db2777" stroke-width="1.3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="#db2777" stroke-width="1.3" stroke-linecap="round"/></svg></div>
                                    <span class="cs-opt-label">Personal</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="Study">
                                    <div class="cs-opt-icon" style="background:rgba(20,184,166,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M1 4l7-2 7 2-7 2z" stroke="#0d9488" stroke-width="1.3" stroke-linejoin="round"/><path d="M4 6v4c0 1.1 1.8 2 4 2s4-.9 4-2V6" stroke="#0d9488" stroke-width="1.3" stroke-linecap="round"/><path d="M15 4v4" stroke="#0d9488" stroke-width="1.3" stroke-linecap="round"/></svg></div>
                                    <span class="cs-opt-label">Study</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="SK Activity">
                                    <div class="cs-opt-icon" style="background:rgba(245,158,11,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><polygon points="8,1 10,6 15,6 11,9 13,14 8,11 3,14 5,9 1,6 6,6" stroke="#d97706" stroke-width="1.3" stroke-linejoin="round"/></svg></div>
                                    <span class="cs-opt-label">SK Activity</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="Others">
                                    <div class="cs-opt-icon" style="background:rgba(100,116,139,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="4" cy="8" r="1.2" fill="#64748b"/><circle cx="8" cy="8" r="1.2" fill="#64748b"/><circle cx="12" cy="8" r="1.2" fill="#64748b"/></svg></div>
                                    <span class="cs-opt-label">Others</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
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

        /* ─── Visitor type toggle ─── */
        function setType(type) {
            currentType = type;
            document.getElementById('finalVisitorType').value = type;
            const isUser = type === 'User';
            document.getElementById('btnUser').classList.toggle('active', isUser);
            document.getElementById('btnVisitor').classList.toggle('active', !isUser);
            document.getElementById('userFields').style.display   = isUser ? 'grid' : 'none';
            document.getElementById('visitorFields').style.display = isUser ? 'none' : 'block';
            selectedUser = null;
            ['userNameInput','userEmailDisplay','visitorNameInput','visitorEmailInput'].forEach(id => {
                const el = document.getElementById(id); if (el) el.value = '';
            });
            document.getElementById('finalUserId').value = '';
            // FIX: reset conflict state when switching type
            window._avHasConflict = false;
            if (type !== 'Visitor') {
                hideGuestBox();
                hideCodeSection();
                hideRegisteredWarning();
            }
        }

        /* ─── Registered user autocomplete ─── */
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

        /* ─── PC workstation toggle ─── */
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

        /* ─── Custom Select ─── */
        let _csActive = null;

        function closeAllCS() {
            document.querySelectorAll('.cs-drop').forEach(d => d.style.display = 'none');
            document.querySelectorAll('.cs-trigger').forEach(t => t.classList.remove('open'));
            _csActive = null;
        }
        document.addEventListener('click', closeAllCS);

        function initCS(wrapId, dropId, labelId, onChange) {
            const wrap    = document.getElementById(wrapId);
            const trigger = wrap.querySelector('.cs-trigger');
            const label   = document.getElementById(labelId);
            const drop    = document.getElementById(dropId);
            const opts    = drop.querySelectorAll('.cs-opt');

            trigger.addEventListener('click', e => {
                e.stopPropagation();
                if (_csActive === dropId) { closeAllCS(); return; }
                closeAllCS(); _csActive = dropId;
                drop.style.display = 'block';
                trigger.classList.add('open');
            });

            opts.forEach(opt => {
                opt.addEventListener('click', e => {
                    e.stopPropagation();
                    const val  = opt.dataset.value;
                    const text = opt.querySelector('.cs-opt-label')?.textContent.trim() || '';
                    opts.forEach(o => o.classList.remove('cs-selected'));
                    if (val !== '') {
                        opt.classList.add('cs-selected');
                        label.textContent = text;
                        trigger.classList.add('has-value');
                    } else {
                        label.textContent = text;
                        trigger.classList.remove('has-value');
                    }
                    closeAllCS();
                    if (onChange) onChange(val, opt);
                });
            });
        }

        function getSelectedResourceVal()    { const o = document.querySelector('#resourceDrop .cs-opt.cs-selected'); return o ? o.dataset.value : ''; }
        function getSelectedResourceName()   { const o = document.querySelector('#resourceDrop .cs-opt.cs-selected'); return o ? o.querySelector('.cs-opt-label')?.textContent.trim() : '—'; }
        function getSelectedResourceIsWifi() { const o = document.querySelector('#resourceDrop .cs-opt.cs-selected'); return o ? (o.dataset.isWifi === '1') : false; }
        function getSelectedPurpose()        { const o = document.querySelector('#purposeDrop  .cs-opt.cs-selected'); return o ? o.dataset.value : ''; }

        initCS('resourceWrap', 'resourceDrop', 'resourceLabel', function(val, opt) {
            document.getElementById('nativeResource').innerHTML = `<option value="${val}" selected></option>`;
            const hasPcs = opt.dataset.hasPcs === '1';
            const isWifi = opt.dataset.isWifi === '1';
            document.getElementById('pcSection').style.display = hasPcs ? 'block' : 'none';
            const wifiBanner = document.getElementById('wifiNoticeBanner');
            wifiBanner.style.display = isWifi ? 'flex' : 'none';
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(b => b.classList.remove('selected'));
            schedAvailabilityCheck();
        });

        initCS('purposeWrap', 'purposeDrop', 'purposeLabel', function(val) {
            document.getElementById('nativePurpose').innerHTML = `<option value="${val}" selected></option>`;
            document.getElementById('purposeOtherWrap').style.display = val === 'Others' ? 'block' : 'none';
            if (val !== 'Others') document.getElementById('purposeOther').value = '';
        });

        /* ─── LIVE AVAILABILITY CHECKER ─── */
        // FIX: initialise conflict flag here, not patched later
        window._avHasConflict = false;
        let _avTimer = null;

        function schedAvailabilityCheck() {
            clearTimeout(_avTimer);
            _avTimer = setTimeout(runAvailabilityCheck, 450);
        }

        function runAvailabilityCheck() {
            const resourceId = getSelectedResourceVal();
            const date       = document.getElementById('resDate').value;
            const startTime  = document.getElementById('startTime').value;
            const endTime    = document.getElementById('endTime').value;
            const status     = document.getElementById('availabilityStatus');
            const slots      = document.getElementById('bookedSlotsWrap');

            if (!resourceId || !date) {
                status.className = '';
                status.style.display = 'none';
                slots.style.display  = 'none';
                window._avHasConflict = false;
                return;
            }

            setAvStatus('checking',
                '<i class="fa-solid fa-circle-notch spin-icon" style="font-size:.75rem"></i>',
                'Checking availability…',
                startTime && endTime ? 'Verifying your time slot against existing bookings.' : 'Select start & end time to check for conflicts.',
                ''
            );

            const params = new URLSearchParams({
                resource_id: resourceId,
                date:        date,
                start_time:  startTime || '',
                end_time:    endTime   || '',
            });

            fetch('/admin/check-availability?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const booked = data.booked_slots || [];

                // FIX: set _avHasConflict directly here, no patching needed
                if (data.has_conflict) {
                    window._avHasConflict = true;
                    setAvStatus('conflict',
                        '<i class="fa-solid fa-ban" style="font-size:.78rem;color:#b91c1c"></i>',
                        'Time slot conflict detected',
                        'Your selected time overlaps with an existing booking. Please choose a different time.',
                        'UNAVAILABLE'
                    );
                } else if (startTime && endTime) {
                    window._avHasConflict = false;
                    setAvStatus('ok',
                        '<i class="fa-solid fa-circle-check" style="font-size:.78rem;color:#15803d"></i>',
                        'Time slot is available',
                        booked.length
                            ? `${booked.length} other booking(s) on this date — none overlap your selected time.`
                            : 'No other bookings on this date. All yours!',
                        'AVAILABLE'
                    );
                } else {
                    window._avHasConflict = false;
                    setAvStatus('info',
                        '<i class="fa-solid fa-calendar-check" style="font-size:.78rem;color:#92400e"></i>',
                        booked.length ? `${booked.length} booking(s) on this date` : 'No bookings yet on this date',
                        'Select start & end time to check if your slot is free.',
                        booked.length ? `${booked.length} BOOKED` : 'FREE'
                    );
                }

                if (booked.length > 0) {
                    renderBookedSlots(booked, startTime, endTime);
                } else {
                    slots.style.display = 'none';
                }
            })
            .catch(() => {
                window._avHasConflict = false;
                setAvStatus('info',
                    '<i class="fa-solid fa-wifi" style="font-size:.78rem;color:#92400e"></i>',
                    'Could not check availability',
                    'Network error. Please continue manually.',
                    ''
                );
                slots.style.display = 'none';
            });
        }

        function setAvStatus(type, iconHtml, title, sub, pill) {
            const status = document.getElementById('availabilityStatus');
            const pillColors = {
                ok:       { bg: '#dcfce7', color: '#15803d' },
                conflict: { bg: '#fee2e2', color: '#b91c1c' },
                info:     { bg: '#fef3c7', color: '#92400e' },
                checking: { bg: '#e0f2fe', color: '#0369a1' },
            };
            document.getElementById('avIcon').innerHTML   = iconHtml;
            document.getElementById('avTitle').textContent = title;
            document.getElementById('avSub').textContent   = sub;
            status.className = 'av-' + type;
            const avPill = document.getElementById('avPill');
            if (pill) {
                const c = pillColors[type] || {};
                avPill.style.background = c.bg    || '#e2e8f0';
                avPill.style.color      = c.color || '#475569';
                avPill.textContent      = pill;
                avPill.style.display    = 'inline-block';
            } else {
                avPill.style.display = 'none';
            }
        }

        function renderBookedSlots(slots, myStart, myEnd) {
            const wrap = document.getElementById('bookedSlotsWrap');
            const list = document.getElementById('bookedSlotsList');
            let html = '';
            slots.forEach(s => {
                const start = s.start_time.substring(0, 5);
                const end   = s.end_time.substring(0, 5);
                const isConflict = myStart && myEnd && myStart < s.end_time && myEnd > s.start_time;
                const statusClass = s.status === 'approved' ? 'bs-pill-approved' : 'bs-pill-pending';
                const statusLabel = s.status === 'approved' ? '✔ Approved' : '⏳ Pending';
                const rowClass    = isConflict ? 'bs-row bs-conflict-row' : 'bs-row';
                html += `<div class="${rowClass}">
                    <div class="bs-col"><span class="bs-status-pill ${statusClass}">${statusLabel}</span></div>
                    <div class="bs-col" style="font-family:var(--mono);font-size:.78rem;font-weight:600">${start} – ${end}</div>
                    <div class="bs-col" style="text-align:right">
                        ${isConflict ? '<span style="font-size:.68rem;font-weight:700;color:#b91c1c;background:#fee2e2;padding:2px 7px;border-radius:20px">⚡ CONFLICT</span>' : ''}
                    </div>
                </div>`;
            });
            list.innerHTML = html;
            wrap.style.display = 'block';
        }

        // Hook pickers to re-run availability check
        window._onDatePicked = schedAvailabilityCheck;
        window._onTimePicked = schedAvailabilityCheck;

        /* ─── Preview / confirm ─── */
        function previewReservation() {
            if (currentType === 'Visitor' && window._guestIsRegistered) {
                alert('⛔ This person is a registered resident. Please use the Registered User toggle and select them from the dropdown.');
                return;
            }
            if (currentType === 'Visitor' && window._guestBlocked && !getSelectedResourceIsWifi()) {
                const name = document.getElementById('visitorNameInput')?.value?.trim() || 'This guest';
                alert(`⛔ ${name} has reached the 3-reservation limit within the last 3 days and cannot make a new reservation.`);
                return;
            }
            if (window._avHasConflict) {
                alert('⛔ This time slot has a confirmed booking conflict. Please choose a different time or date.');
                return;
            }
            if (currentType === 'Visitor') {
                const codeSection = document.getElementById('confirmCodeSection');
                if (codeSection && codeSection.style.display !== 'none') {
                    const verified = document.getElementById('confirmCodeVerified')?.value;
                    if (verified !== '1') {
                        alert('⚠️ Please generate a confirmation code and have the visitor verify it before proceeding.');
                        document.getElementById('genCodeBtn')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                }
            }

            const isUser       = currentType === 'User';
            const name         = isUser ? userNameInput.value.trim() : document.getElementById('visitorNameInput').value.trim();
            const email        = isUser ? document.getElementById('userEmailDisplay').value.trim() : document.getElementById('visitorEmailInput').value.trim();
            const resourceId   = getSelectedResourceVal();
            const resourceName = getSelectedResourceName();
            const showPcs      = document.getElementById('pcSection').style.display !== 'none';
            const date         = document.getElementById('resDate').value;
            const startTime    = document.getElementById('startTime').value;
            const endTime      = document.getElementById('endTime').value;
            const purposeVal   = getSelectedPurpose();
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ? `Others — ${purposeOther}` : purposeVal;

            if (!name)        return alert('Please enter a name.');
            if (!resourceId)  return alert('Please select a resource.');
            if (showPcs && !selectedPcs.length) return alert('Please select at least one workstation.');
            if (!date)        return alert('Please select a date.');
            if (!startTime)   return alert('Please enter a start time.');
            if (!endTime)     return alert('Please enter an end time.');
            if (!purposeVal)  return alert('Please select a purpose.');
            if (isUser && !selectedUser && !document.getElementById('finalUserId').value)
                return alert('Please select a registered user from the dropdown.');

            document.getElementById('finalVisitorName').value = name;
            document.getElementById('finalUserEmail').value   = email;
            document.getElementById('finalPurpose').value     = purposeFinal;
            document.getElementById('nativeResource').innerHTML = `<option value="${resourceId}" selected></option>`;
            document.getElementById('nativePurpose').innerHTML  = `<option value="${purposeVal}" selected></option>`;

            const rows = [
                ['Type',         isUser ? 'Registered User' : 'Walk-in Visitor'],
                ['Name',         name || '—'],
                ['Email',        email || '—'],
                ['Resource',     resourceName],
                ['Workstations', selectedPcs.length ? selectedPcs.join(', ') : '—'],
                ['Date',         document.getElementById('dateLabel').textContent],
                ['Time',         `${document.getElementById('startLabel').textContent} – ${document.getElementById('endLabel').textContent}`],
                ['Purpose',      purposeFinal || '—'],
                ['Availability', '✓ No conflict detected'],
            ];
            if (!isUser) rows.splice(2, 0, ['Identity', 'Confirmed ✓']);
            if (getSelectedResourceIsWifi()) rows.push(['Quota Check', '⚡ Skipped — WiFi resource']);

            document.getElementById('modalSummaryBox').innerHTML =
                rows.map(([l,v]) => `<div class="mrow"><span class="mrow-label">${l}</span><span class="mrow-value">${v}</span></div>`).join('');
            document.getElementById('qrWrap').style.display    = 'none';
            document.getElementById('confirmBtn').style.display = 'flex';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.8rem"></i> Saving…';
            const code = `ACCESS-${Date.now()}`;
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
            btn.innerHTML = '<i class="fa-solid fa-check" style="font-size:.8rem"></i> Confirm & Save';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    </script>

    <!-- Custom Date / Time Picker -->
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
            drop.style.left  = '0';
            drop.style.right = '';
            drop.style.display = 'block';
            $(triggerId).classList.add('open');
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
            const todayFlat = new Date(TODAY.getFullYear(), TODAY.getMonth(), TODAY.getDate());
            const firstDow=new Date(y,m,1).getDay(), daysInM=new Date(y,m+1,0).getDate(), prevTotal=new Date(y,m,0).getDate();
            let html=`<div class="cal-head"><div class="cal-nav-btn" id="_calPrev">&#8249;</div><div class="cal-month-label">${MONTHS[m]} ${y}</div><div class="cal-nav-btn" id="_calNext">&#8250;</div></div><div class="cal-grid">${DOWS.map(d=>`<div class="cal-dow">${d}</div>`).join('')}`;
            for(let i=0;i<firstDow;i++) html+=`<div class="cal-day cal-other">${prevTotal-firstDow+1+i}</div>`;
            for(let d=1;d<=daysInM;d++){
                const thisDate = new Date(y,m,d);
                const isPast  = thisDate < todayFlat;
                const isToday = d===TODAY.getDate()&&m===TODAY.getMonth()&&y===TODAY.getFullYear();
                const isSel   = selDate&&selDate.d===d&&selDate.m===m&&selDate.y===y;
                const classes = ['cal-day', isPast?'cal-past':'', isToday&&!isSel?'cal-today':'', isSel?'cal-selected':''].filter(Boolean).join(' ');
                html += `<div class="${classes}"${isPast ? '' : ` data-d="${d}"`}>${d}</div>`;
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
            renderCal();
            setTimeout(closeAll,180);
            if (window._onDatePicked) window._onDatePicked();
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
            const labelId  =which==='start'?'startLabel':'endLabel';
            const inputId  =which==='start'?'startTime':'endTime';
            const triggerId=which==='start'?'startTrigger':'endTrigger';
            $(labelId).textContent=label;
            document.getElementById(inputId).value=iso24;
            $(triggerId).classList.add('has-value');
            closeAll();
            if (window._onTimePicked) window._onTimePicked();
        }

        $('dateTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('calDrop','dateTrigger');if(activeDrop==='calDrop')renderCal();});
        $('startTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('startDrop','startTrigger');if(activeDrop==='startDrop')renderTime('start');});
        $('endTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('endDrop','endTrigger');if(activeDrop==='endDrop')renderTime('end');});

        // FIX: use setTimeout so DOM is fully settled before writing hidden inputs,
        // and use document.getElementById directly (bypasses local $ scope issues).
        // Also fires the initial availability check after defaults are applied.
        setTimeout(function() {
            ['start', 'end'].forEach(function(which) {
                var st = tState[which];
                var h24 = st.h;
                if (st.ampm === 'am' && st.h === 12) h24 = 0;
                if (st.ampm === 'pm' && st.h !== 12) h24 = st.h + 12;
                var iso24     = String(h24).padStart(2, '0') + ':' + String(st.min).padStart(2, '0');
                var label     = String(st.h).padStart(2, '0') + ':' + String(st.min).padStart(2, '0') + ' ' + st.ampm.toUpperCase();
                var labelId   = which === 'start' ? 'startLabel'   : 'endLabel';
                var inputId   = which === 'start' ? 'startTime'    : 'endTime';
                var triggerId = which === 'start' ? 'startTrigger' : 'endTrigger';
                document.getElementById(labelId).textContent   = label;
                document.getElementById(inputId).value         = iso24;
                document.getElementById(triggerId).classList.add('has-value');
            });
            // Trigger initial check so the banner shows as soon as a resource is picked
            if (window.schedAvailabilityCheck) window.schedAvailabilityCheck();
        }, 0);

        // Set today's date label and selDate on load
        (function(){
            const t = new Date();
            document.getElementById('dateLabel').textContent = t.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
            document.getElementById('dateTrigger').classList.add('has-value');
            selDate = {d:t.getDate(), m:t.getMonth(), y:t.getFullYear()};
        })();
    })();
    </script>

    <!-- Guest Limit Checker + Registered Warning + Confirmation Code -->
    <script>
    (function () {
        const LIMIT     = 3;
        const CHECK_URL = '/admin/check-guest-limit';

        window._guestBlocked      = false;
        window._guestIsRegistered = false;
        window._guestTimer        = null;
        window._generatedCode     = null;

        window.schedGuestCheck = function () {
            clearTimeout(window._guestTimer);
            window._guestTimer = setTimeout(window.runGuestCheck, 600);
        };

        window.runGuestCheck = function () {
            clearTimeout(window._guestTimer);
            if (currentType === 'User') { hideGuestBox(); hideCodeSection(); hideRegisteredWarning(); return; }

            const name  = (document.getElementById('visitorNameInput')?.value  || '').trim();
            const email = (document.getElementById('visitorEmailInput')?.value || '').trim();
            if (!name && !email) { hideGuestBox(); hideCodeSection(); hideRegisteredWarning(); return; }

            const params = new URLSearchParams();
            if (name)  params.append('name',  name);
            if (email) params.append('email', email);
            params.append('visitor_type', currentType);

            fetch(`${CHECK_URL}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.is_registered) {
                    window._guestIsRegistered = true;
                    window._guestBlocked      = true;
                    hideGuestBox();
                    hideCodeSection();
                    showRegisteredWarning(data.registered_name || name);
                    return;
                }
                window._guestIsRegistered = false;
                hideRegisteredWarning();
                if (data.skip_quota) { hideGuestBox(); hideCodeSection(); return; }
                renderGuestBox(data);
                if (data.is_new && !data.blocked) { showCodeSection(); }
                else { hideCodeSection(); }
            })
            .catch(() => {
                window._guestIsRegistered = false;
                hideGuestBox();
                hideCodeSection();
                hideRegisteredWarning();
            });
        };

        window.showRegisteredWarning = function (name) {
            const box   = document.getElementById('registeredWarnBox');
            const title = document.getElementById('regWarnTitle');
            if (!box) return;
            if (title) title.textContent = `"${name}" is a registered resident`;
            box.style.display = 'flex';
        };

        window.hideRegisteredWarning = function () {
            window._guestIsRegistered = false;
            const box = document.getElementById('registeredWarnBox');
            if (box) box.style.display = 'none';
        };

        window.generateConfirmCode = function () {
            const code = String(Math.floor(1000 + Math.random() * 9000));
            window._generatedCode = code;
            document.getElementById('confirmCodeValue').value    = code;
            document.getElementById('confirmCodeVerified').value = '0';
            document.getElementById('codeDigits').textContent   = code;
            document.getElementById('codeDisplayWrap').style.display = 'flex';
            document.getElementById('pinInputArea').style.display    = 'block';
            ['pin0','pin1','pin2','pin3'].forEach(id => {
                const el = document.getElementById(id);
                el.value = ''; el.classList.remove('pin-ok','pin-err');
            });
            document.getElementById('pinFeedback').textContent = '';
            document.getElementById('pinFeedback').style.color  = '';
            setTimeout(() => document.getElementById('pin0')?.focus(), 50);
        };

        window.pinAdvance = function (el, nextIdx) {
            el.value = el.value.replace(/\D/g, '').slice(-1);
            if (el.value && nextIdx <= 3) document.getElementById('pin' + nextIdx)?.focus();
        };

        window.pinBack = function (e, el, prevIdx) {
            if (e.key === 'Backspace' && !el.value && prevIdx !== null)
                document.getElementById('pin' + prevIdx)?.focus();
        };

        window.pinFinish = function (el) {
            el.value = el.value.replace(/\D/g, '').slice(-1);
            const entered = ['pin0','pin1','pin2','pin3'].map(id => document.getElementById(id).value).join('');
            if (entered.length < 4) return;
            const fb   = document.getElementById('pinFeedback');
            const pins = document.querySelectorAll('.pin-box');
            if (entered === window._generatedCode) {
                pins.forEach(p => { p.classList.remove('pin-err'); p.classList.add('pin-ok'); });
                fb.textContent = '✓ Code verified — visitor identity confirmed';
                fb.style.color  = '#16a34a';
                document.getElementById('confirmCodeVerified').value = '1';
            } else {
                pins.forEach(p => { p.classList.remove('pin-ok'); p.classList.add('pin-err'); });
                fb.textContent = '✗ Code mismatch — ask visitor to repeat or click Generate again';
                fb.style.color  = '#dc2626';
                document.getElementById('confirmCodeVerified').value = '0';
                document.querySelector('.pin-row')?.classList.add('shake');
                setTimeout(() => document.querySelector('.pin-row')?.classList.remove('shake'), 400);
            }
        };

        function showCodeSection() {
            const s = document.getElementById('confirmCodeSection');
            if (s) s.style.display = 'block';
        }

        function hideCodeSection() {
            const s = document.getElementById('confirmCodeSection');
            if (s) s.style.display = 'none';
            window._generatedCode = null;
            const cv  = document.getElementById('confirmCodeVerified');  if (cv)  cv.value = '0';
            const cdv = document.getElementById('confirmCodeValue');      if (cdv) cdv.value = '';
            const wrap = document.getElementById('codeDisplayWrap');     if (wrap) wrap.style.display = 'none';
            const pin  = document.getElementById('pinInputArea');         if (pin)  pin.style.display  = 'none';
            ['pin0','pin1','pin2','pin3'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.value = ''; el.classList.remove('pin-ok','pin-err'); }
            });
            const fb = document.getElementById('pinFeedback');
            if (fb) { fb.textContent = ''; }
        }
        window.hideCodeSection = hideCodeSection;

        function renderGuestBox(data) {
            const count   = data.count   ?? 0;
            const limit   = data.limit   ?? LIMIT;
            const blocked = data.blocked ?? (count >= limit);
            const pct     = Math.min(count / limit * 100, 100);
            window._guestBlocked = blocked;

            const box   = document.getElementById('guestLimitBox');
            const inner = document.getElementById('guestLimitInner');
            const icon  = document.getElementById('guestLimitIcon');
            const title = document.getElementById('guestLimitTitle');
            const sub   = document.getElementById('guestLimitSub');
            const pill  = document.getElementById('guestLimitPill');
            const bar   = document.getElementById('guestLimitBar');
            const cnt   = document.getElementById('guestLimitCount');

            box.style.display = 'block';
            cnt.textContent   = `${count} / ${limit} used`;
            bar.style.width   = pct + '%';

            if (blocked) {
                inner.style.background = '#fef2f2'; inner.style.borderColor = '#fecaca';
                icon.style.background  = '#fee2e2'; icon.innerHTML = '<i class="fa-solid fa-ban" style="color:#dc2626"></i>';
                title.style.color = '#dc2626'; title.textContent = 'Reservation limit reached';
                sub.style.color   = '#dc2626'; sub.textContent = `This guest has used all ${limit} slots within the last 3 days.`;
                pill.style.background = '#dc2626'; pill.style.color = '#fff'; pill.textContent = 'BLOCKED';
                bar.style.background  = '#ef4444';
            } else if (count === limit - 1) {
                inner.style.background = '#fffbeb'; inner.style.borderColor = '#fde68a';
                icon.style.background  = '#fef3c7'; icon.innerHTML = '<i class="fa-solid fa-triangle-exclamation" style="color:#d97706"></i>';
                title.style.color = '#92400e'; title.textContent = 'Last reservation slot';
                sub.style.color   = '#92400e'; sub.textContent = `${limit - count} slot remaining in the 3-day window.`;
                pill.style.background = '#f59e0b'; pill.style.color = '#fff'; pill.textContent = 'LAST SLOT';
                bar.style.background  = '#f59e0b';
            } else if (count > 0) {
                inner.style.background = 'rgba(99,102,241,.05)'; inner.style.borderColor = 'rgba(99,102,241,.2)';
                icon.style.background  = 'rgba(99,102,241,.1)'; icon.innerHTML = '<i class="fa-solid fa-circle-check" style="color:#6366f1"></i>';
                title.style.color = '#3730a3'; title.textContent = 'Returning visitor';
                sub.style.color   = '#4f46e5'; sub.textContent = `${limit - count} slot(s) remaining in the 3-day window.`;
                pill.style.background = '#ede9fe'; pill.style.color = '#4f46e5'; pill.textContent = `${count}/${limit} USED`;
                bar.style.background  = '#6366f1';
            } else {
                inner.style.background = '#f0fdf4'; inner.style.borderColor = '#bbf7d0';
                icon.style.background  = '#dcfce7'; icon.innerHTML = '<i class="fa-solid fa-user-plus" style="color:#16a34a"></i>';
                title.style.color = '#15803d'; title.textContent = 'New visitor';
                sub.style.color   = '#16a34a'; sub.textContent = `No recent reservations found. ${limit} slots available.`;
                pill.style.background = '#22c55e'; pill.style.color = '#fff'; pill.textContent = 'NEW';
                bar.style.background  = '#22c55e';
            }
        }

        function hideGuestBox() {
            window._guestBlocked = false;
            const box = document.getElementById('guestLimitBox');
            if (box) box.style.display = 'none';
        }
        window.hideGuestBox = hideGuestBox;

    })();
    </script>
</body>
</html>     left: 0;
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
           AVAILABILITY STATUS
        ══════════════════════════════ */
        #availabilityStatus {
            display: none;
            margin-top: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            align-items: center;
            gap: 10px;
            transition: all .2s;
        }
        #availabilityStatus.av-checking {
            display: flex;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            color: #0369a1;
        }
        #availabilityStatus.av-ok {
            display: flex;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }
        #availabilityStatus.av-conflict {
            display: flex;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        #availabilityStatus.av-info {
            display: flex;
            background: #fefce8;
            border: 1px solid #fde68a;
            color: #92400e;
        }
        .av-icon {
            width: 28px; height: 28px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: .8rem;
        }
        .av-checking .av-icon { background: #e0f2fe; }
        .av-ok      .av-icon { background: #dcfce7; }
        .av-conflict .av-icon { background: #fee2e2; }
        .av-info    .av-icon { background: #fef9c3; }

        /* Booked slots table */
        #bookedSlotsWrap {
            display: none;
            margin-top: 10px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(99,102,241,.1);
        }
        .bs-header {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 13px;
            background: #f8fafc;
            border-bottom: 1px solid rgba(99,102,241,.08);
            font-size: .7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em; color: #94a3b8;
        }
        .bs-row {
            display: flex; align-items: center; gap: 0;
            padding: 9px 13px;
            border-bottom: 1px solid rgba(99,102,241,.05);
            font-size: .8rem;
            background: white;
        }
        .bs-row:last-child { border-bottom: none; }
        .bs-row:hover { background: #f8fafc; }
        .bs-col { flex: 1; }
        .bs-status-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 20px;
            font-size: .68rem; font-weight: 700;
        }
        .bs-pill-approved { background: #dcfce7; color: #15803d; }
        .bs-pill-pending  { background: #fef3c7; color: #92400e; }
        .bs-conflict-row  { background: #fef2f2 !important; }

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
           REGISTERED-USER WARNING BOX
        ══════════════════════════════ */
        .reg-warn-box {
            display: none;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 10px;
            margin-bottom: 12px;
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #9a3412;
            font-size: .82rem;
            font-weight: 600;
        }
        body.dark .reg-warn-box {
            background: rgba(234,88,12,.1);
            border-color: rgba(251,146,60,.3);
            color: #fb923c;
        }

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
           CONFIRMATION CODE WIDGET
        ══════════════════════════════ */
        .confirm-code-box {
            background: rgba(99,102,241,.06);
            border: 1px solid rgba(99,102,241,.22);
            border-radius: 14px;
            padding: 18px 20px;
            margin-top: 4px;
        }
        .confirm-code-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .confirm-code-icon {
            width: 34px; height: 34px;
            background: var(--indigo-light);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .gen-code-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            background: var(--indigo); color: white;
            border-radius: 9px; font-size: .78rem; font-weight: 700;
            border: none; cursor: pointer; font-family: var(--font);
            transition: background .15s;
        }
        .gen-code-btn:hover { background: #4338ca; }
        .code-display-pill {
            display: inline-flex; align-items: center; gap: 10px;
            background: white;
            border: 2px solid var(--indigo);
            border-radius: 12px;
            padding: 8px 20px;
            margin-left: 10px;
        }
        .code-digits {
            font-family: var(--mono);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--indigo);
            letter-spacing: .22em;
        }
        .pin-row {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        .pin-box {
            width: 52px; height: 56px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 800;
            font-family: var(--mono);
            border: 2px solid rgba(99,102,241,.25);
            border-radius: 12px;
            background: white;
            color: #0f172a;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            caret-color: var(--indigo);
        }
        .pin-box:focus { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .pin-box.pin-ok  { border-color: #16a34a; background: #f0fdf4; }
        .pin-box.pin-err { border-color: #dc2626; background: #fef2f2; }
        .pin-feedback {
            font-size: .78rem; font-weight: 700;
            min-height: 20px; margin-top: 2px;
        }

        /* ══════════════════════════════
           ANIMATIONS
        ══════════════════════════════ */
        @keyframes slideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }
        @keyframes sheetUp { from { opacity:0; transform:translateY(60px); } to { opacity:1; transform:none; } }
        @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
        @keyframes shake   { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-5px)} 40%,80%{transform:translateX(5px)} }
        @keyframes spin    { to { transform: rotate(360deg); } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .shake     { animation: shake .35s ease; }
        .spin-icon { animation: spin 1s linear infinite; display: inline-block; }

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

        .dt-drop { position: absolute; bottom: calc(100% + 6px); left: 0; z-index: 9999; border-radius: 14px; animation: dtDrop .15s cubic-bezier(.4,0,.2,1); }
        @keyframes dtDrop { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
        body:not(.dark) .dt-drop { background:#fff; border:1px solid rgba(99,102,241,.18); box-shadow:0 20px 50px rgba(15,23,42,.18); }
        body.dark      .dt-drop { background:#0e1828; border:1px solid rgba(99,102,241,.22); box-shadow:0 20px 60px rgba(0,0,0,.65); }

        .dt-drop.cal { width: 288px; padding: 18px 16px 14px; }
        @media(max-width:380px) { .dt-drop.cal { width: 260px; } }

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
        body:not(.dark) .cal-day:hover:not(.cal-other):not(.cal-selected):not(.cal-past) { background:#f1f5f9; color:#0f172a; border-color:#e2e8f0; }
        body.dark .cal-day:hover:not(.cal-other):not(.cal-selected):not(.cal-past) { background:rgba(255,255,255,.06); color:#e2e8f0; border-color:rgba(255,255,255,.08); }
        .cal-day.cal-other { pointer-events:none; }
        body:not(.dark) .cal-day.cal-other { color:#cbd5e1; }
        body.dark .cal-day.cal-other { color:#2e3850; }
        .cal-day.cal-past { pointer-events:none; opacity:.35; cursor:default; }
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
           CUSTOM SELECT (CS) — opens ABOVE
        ══════════════════════════════ */
        .cs-wrap { position: relative; }
        .cs-trigger {
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px; width: 100%; padding: .75rem 1rem;
            background: var(--card); border: 1px solid rgba(99,102,241,.15);
            border-radius: var(--r-sm); font-family: var(--font);
            font-size: .88rem; font-weight: 500; color: #94a3b8;
            cursor: pointer; transition: border .18s, box-shadow .18s;
            user-select: none; -webkit-user-select: none; outline: none;
        }
        .cs-trigger.has-value { color: #0f172a; }
        .cs-trigger:hover { border-color: rgba(99,102,241,.35); }
        .cs-trigger.open { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .cs-arrow { width: 16px; height: 16px; flex-shrink: 0; opacity: .4; transition: transform .18s, opacity .18s; }
        .cs-trigger.open .cs-arrow { transform: rotate(180deg); opacity: .75; }
        .cs-drop {
            position: absolute; bottom: calc(100% + 5px); left: 0; right: 0;
            z-index: 9999; background: white;
            border: 1px solid rgba(99,102,241,.18); border-radius: var(--r-md);
            box-shadow: 0 16px 40px rgba(15,23,42,.14);
            overflow: hidden; display: none;
            animation: csDropIn .14s cubic-bezier(.4,0,.2,1);
        }
        @keyframes csDropIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:none; } }
        .cs-opt {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 13px; font-size: .87rem; font-weight: 500;
            color: #0f172a; cursor: pointer; transition: background .1s;
            border-bottom: 1px solid rgba(99,102,241,.06);
        }
        .cs-opt:last-child { border-bottom: none; }
        .cs-opt:hover { background: var(--indigo-light); color: var(--indigo); }
        .cs-opt.cs-placeholder { color: #94a3b8; font-weight: 400; font-size: .82rem; }
        .cs-opt.cs-selected { background: rgba(99,102,241,.07); color: var(--indigo); }
        .cs-opt-icon { width: 26px; height: 26px; border-radius: 7px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; }
        .cs-opt-label { flex: 1; }
        .cs-check { width: 14px; height: 14px; flex-shrink: 0; color: var(--indigo); opacity: 0; transition: opacity .12s; }
        .cs-opt.cs-selected .cs-check { opacity: 1; }
        .cs-divider { height: 1px; background: rgba(99,102,241,.08); margin: 3px 0; }

        /* ══════════════════════════════
           DARK MODE
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
            background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8;
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
        /* custom select dark */
        body.dark .cs-trigger { background: #101e35; border-color: rgba(99,102,241,.18); color: #4a6fa5; }
        body.dark .cs-trigger.has-value { color: #e2eaf8; }
        body.dark .cs-trigger.open { background: #0b1628; border-color: var(--indigo); }
        body.dark .cs-drop { background: #0b1628; border-color: rgba(99,102,241,.22); box-shadow: 0 16px 40px rgba(0,0,0,.5); }
        body.dark .cs-opt { color: #e2eaf8; border-color: rgba(99,102,241,.06); }
        body.dark .cs-opt:hover { background: rgba(99,102,241,.12); color: #a5b4fc; }
        body.dark .cs-opt.cs-selected { background: rgba(99,102,241,.15); color: #a5b4fc; }
        body.dark .cs-opt.cs-placeholder { color: #4a6fa5; }
        body.dark .cs-divider { background: rgba(99,102,241,.1); }
        /* confirm code dark */
        body.dark .confirm-code-box { background: rgba(55,48,163,.12); border-color: rgba(99,102,241,.25); }
        body.dark .confirm-code-icon { background: rgba(55,48,163,.3); }
        body.dark .code-display-pill { background: #060e1e; }
        body.dark .pin-box { background: #101e35; border-color: rgba(99,102,241,.25); color: #e2eaf8; }
        body.dark .pin-box.pin-ok  { background: rgba(22,163,74,.1);  border-color: #16a34a; }
        body.dark .pin-box.pin-err { background: rgba(220,38,38,.1);  border-color: #dc2626; }
        /* availability dark */
        body.dark #availabilityStatus.av-checking { background: rgba(3,105,161,.12); border-color: rgba(186,230,253,.2); color: #7dd3fc; }
        body.dark #availabilityStatus.av-ok       { background: rgba(21,128,61,.12);  border-color: rgba(187,247,208,.2); color: #86efac; }
        body.dark #availabilityStatus.av-conflict { background: rgba(185,28,28,.12);  border-color: rgba(254,202,202,.2); color: #fca5a5; }
        body.dark #availabilityStatus.av-info     { background: rgba(146,64,14,.12);  border-color: rgba(253,230,138,.2); color: #fcd34d; }
        body.dark #bookedSlotsWrap { border-color: rgba(99,102,241,.15); }
        body.dark .bs-header { background: #101e35; color: #4a6fa5; border-color: rgba(99,102,241,.1); }
        body.dark .bs-row { background: #0b1628; border-color: rgba(99,102,241,.07); color: #e2eaf8; }
        body.dark .bs-row:hover { background: #101e35; }
        body.dark .bs-conflict-row { background: rgba(185,28,28,.1) !important; }
    </style>
</head>

<body>
    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal-back" onclick="if(event.target===this)closeModal()">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px">
                <div style="width:52px;height:52px;background:var(--indigo-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                    <i class="fa-solid fa-clipboard-check" style="font-size:1.3rem;color:var(--indigo)"></i>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:#94a3b8;margin-top:4px">Review details before saving.</p>
            </div>
            <div id="modalSummaryBox" style="background:#f8fafc;border-radius:var(--r-md);padding:14px 16px;border:1px solid rgba(99,102,241,.08);margin-bottom:14px"></div>
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
        <div class="page-header fade-up">
            <div>
                <p class="eyebrow">Administration</p>
                <h2 class="greeting-name">New Reservation</h2>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Register a manual entry into the system.</p>
            </div>
            <div class="page-header-actions">
                <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <a href="/admin/manage-reservations" class="back-btn">
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
            <form id="reservationForm" method="POST" action="<?= base_url('admin/create-reservation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="visitor_name"  id="finalVisitorName">
                <input type="hidden" name="user_email"    id="finalUserEmail">
                <input type="hidden" name="user_id"       id="finalUserId">
                <input type="hidden" name="visitor_type"  id="finalVisitorType" value="User">
                <input type="hidden" name="purpose"       id="finalPurpose">
                <input type="hidden" name="pcs"           id="finalPcs" value="[]">
                <input type="hidden" name="confirm_code"  id="confirmCodeValue" value="">
                <!-- hidden native selects for form submission -->
                <select name="resource_id" id="nativeResource" style="display:none" required></select>
                <select name="purpose_select" id="nativePurpose" style="display:none"></select>

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
                        <div class="section-icon"><i class="fa-solid fa-id-card" style="color:var(--indigo);font-size:.9rem"></i></div>
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

                    <div id="visitorFields" style="display:none">
                        <div class="grid-2" style="margin-bottom:12px">
                            <div>
                                <label class="field-label">Full Name <span style="color:#ef4444;font-size:.75rem">*</span></label>
                                <input type="text" id="visitorNameInput" class="field-input"
                                       placeholder="Enter visitor's full name"
                                       oninput="schedGuestCheck()" onblur="runGuestCheck()">
                            </div>
                            <div>
                                <label class="field-label">
                                    Email Address
                                    <span style="font-size:.68rem;color:#94a3b8;font-weight:400;margin-left:4px">(optional)</span>
                                </label>
                                <input type="email" id="visitorEmailInput" class="field-input"
                                       placeholder="Enter email if available">
                            </div>
                        </div>

                        <!-- Registered-user warning box -->
                        <div id="registeredWarnBox" class="reg-warn-box">
                            <i class="fa-solid fa-triangle-exclamation" style="margin-top:1px;flex-shrink:0;color:#ea580c"></i>
                            <div>
                                <div id="regWarnTitle" style="font-weight:800;margin-bottom:2px"></div>
                                <div style="font-weight:500;opacity:.85">
                                    Please switch to <strong>Registered User</strong> and select them from the dropdown
                                    so the reservation is counted under their account, not as a walk-in guest slot.
                                </div>
                            </div>
                        </div>

                        <!-- Guest limit indicator -->
                        <div id="guestLimitBox" style="display:none;margin-bottom:12px">
                            <div id="guestLimitInner" style="
                                display:flex;align-items:center;gap:12px;
                                padding:11px 15px;border-radius:10px;
                                border:1px solid;font-size:.8rem;font-weight:600;
                                transition:all .2s">
                                <div id="guestLimitIcon" style="
                                    width:32px;height:32px;border-radius:9px;
                                    display:flex;align-items:center;justify-content:center;
                                    flex-shrink:0;font-size:.85rem"></div>
                                <div style="flex:1;min-width:0">
                                    <div id="guestLimitTitle" style="font-weight:700;font-size:.82rem"></div>
                                    <div id="guestLimitSub"   style="font-size:.7rem;margin-top:1px;opacity:.8"></div>
                                </div>
                                <div id="guestLimitPill" style="
                                    padding:4px 10px;border-radius:20px;
                                    font-size:.72rem;font-weight:800;
                                    letter-spacing:.04em;white-space:nowrap"></div>
                            </div>
                            <div style="margin-top:8px;height:5px;background:#e2e8f0;border-radius:999px;overflow:hidden">
                                <div id="guestLimitBar" style="height:100%;border-radius:999px;transition:width .4s ease,background .3s"></div>
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-top:4px">
                                <span style="font-size:.6rem;color:#94a3b8;font-weight:600">Reservations (last 3 days)</span>
                                <span id="guestLimitCount" style="font-size:.6rem;color:#94a3b8;font-weight:700"></span>
                            </div>
                        </div>

                        <!-- CONFIRMATION CODE WIDGET — shown only for brand-new walk-in visitors -->
                        <div id="confirmCodeSection" style="display:none">
                            <div class="confirm-code-box">
                                <div class="confirm-code-header">
                                    <div class="confirm-code-icon">
                                        <i class="fa-solid fa-shield-halved" style="color:var(--indigo);font-size:.9rem"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:800;color:#3730a3">New visitor — identity confirmation</div>
                                        <div style="font-size:.7rem;color:#6366f1;margin-top:2px;font-weight:500">
                                            Generate a 4-digit code → read it to the visitor → they repeat it back
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 1: Generate -->
                                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px">
                                    <button type="button" onclick="generateConfirmCode()" id="genCodeBtn" class="gen-code-btn">
                                        <i class="fa-solid fa-arrows-rotate" style="font-size:.75rem"></i>
                                        Generate Code
                                    </button>
                                    <div id="codeDisplayWrap" style="display:none">
                                        <div class="code-display-pill">
                                            <span class="code-digits" id="codeDigits"></span>
                                        </div>
                                        <span style="font-size:.68rem;color:#94a3b8;font-weight:600;margin-left:8px;display:inline-block;margin-top:4px">← Read aloud to visitor</span>
                                    </div>
                                </div>

                                <!-- Step 2: Visitor types code -->
                                <div id="pinInputArea" style="display:none">
                                    <label class="field-label" style="color:#3730a3;margin-bottom:8px;display:block;font-size:.75rem">
                                        <i class="fa-solid fa-keyboard" style="margin-right:5px;font-size:.7rem"></i>
                                        Visitor enters the code:
                                    </label>
                                    <div class="pin-row">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin0"
                                               oninput="pinAdvance(this,1)" onkeydown="pinBack(event,this,null)">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin1"
                                               oninput="pinAdvance(this,2)" onkeydown="pinBack(event,this,0)">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin2"
                                               oninput="pinAdvance(this,3)" onkeydown="pinBack(event,this,1)">
                                        <input type="text" inputmode="numeric" maxlength="1" class="pin-box" id="pin3"
                                               oninput="pinFinish(this)" onkeydown="pinBack(event,this,2)">
                                    </div>
                                    <div id="pinFeedback" class="pin-feedback"></div>
                                </div>

                                <input type="hidden" id="confirmCodeVerified" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="divider">

                <!-- Resource & Schedule -->
                <div style="margin-bottom:20px">
                    <div class="section-head">
                        <div class="section-icon"><i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.9rem"></i></div>
                        <div><div class="section-title">Resource & Schedule</div></div>
                    </div>

                    <!-- Styled resource select -->
                    <div style="margin-bottom:14px">
                        <label class="field-label">Select Asset / Resource</label>
                        <div class="cs-wrap" id="resourceWrap">
                            <div class="cs-trigger" id="resourceTrigger">
                                <span id="resourceLabel">— Choose a resource —</span>
                                <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="cs-drop" id="resourceDrop">
                                <div class="cs-opt cs-placeholder" data-value=""><span class="cs-opt-label">— Choose a resource —</span></div>
                                <div class="cs-divider"></div>
                                <?php foreach ($resources ?? [] as $res):
                                    $rname  = htmlspecialchars($res['name']);
                                    $lower  = strtolower($res['name']);
                                    $hasPcs = (strpos($lower,'computer')!==false||strpos($lower,'pc')!==false||strpos($lower,'lab')!==false)?'1':'0';
                                    $isWifi = (strpos($lower,'wifi')!==false)?'1':'0';
                                ?>
                                <div class="cs-opt"
                                     data-value="<?= $res['id'] ?>"
                                     data-name="<?= $rname ?>"
                                     data-has-pcs="<?= $hasPcs ?>"
                                     data-is-wifi="<?= $isWifi ?>">
                                    <div class="cs-opt-icon" style="background:rgba(99,102,241,.1)">
                                        <?php if ($isWifi === '1'): ?>
                                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M1 5.5C4.1 2.8 8 2 8 2s3.9.8 7 3.5" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/><path d="M3.5 8C5.2 6.4 8 6 8 6s2.8.4 4.5 2" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/><path d="M6 10.5C6.7 9.9 8 9.7 8 9.7s1.3.2 2 .8" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/><circle cx="8" cy="13" r="1" fill="#6366f1"/></svg>
                                        <?php else: ?>
                                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="1" y="2" width="14" height="10" rx="2" stroke="#6366f1" stroke-width="1.3"/><path d="M5 15h6M8 12v3" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <span class="cs-opt-label"><?= $rname ?></span>
                                    <?php if ($isWifi === '1'): ?>
                                    <span style="font-size:.6rem;font-weight:700;background:rgba(99,102,241,.1);color:#6366f1;padding:2px 7px;border-radius:20px;letter-spacing:.04em">NO LIMIT</span>
                                    <?php endif; ?>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- WiFi notice banner — shown when WiFi is selected -->
                        <div id="wifiNoticeBanner" style="display:none;margin-top:8px;padding:9px 13px;background:#ede9fe;border:1px solid #c4b5fd;border-radius:9px;font-size:.78rem;font-weight:600;color:#4c1d95;display:none;align-items:center;gap:8px">
                            <i class="fa-solid fa-wifi" style="color:#7c3aed;font-size:.8rem"></i>
                            WiFi reservations have <strong>no booking limit</strong> — quota and fairness checks are skipped.
                        </div>
                    </div>

                    <!-- PC Section -->
                    <div id="pcSection" style="display:none;margin-bottom:14px" class="pc-section">
                        <label class="field-label" style="color:var(--indigo);margin-bottom:10px;display:block">Assign Workstation(s)</label>
                        <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:8px">
                            <?php foreach ($pcs ?? [] as $pc): ?>
                                <button type="button" onclick="togglePc('<?= htmlspecialchars($pc['pc_number']) ?>',this)" data-pc="<?= htmlspecialchars($pc['pc_number']) ?>" class="pc-btn"><?= htmlspecialchars($pc['pc_number']) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <p style="font-size:.72rem;color:var(--indigo);font-weight:600;margin-top:10px">Selected: <span id="pcSelectedLabel">None</span></p>
                    </div>

                    <!-- Date / time — custom pickers -->
                    <div class="grid-3" style="margin-bottom:14px">
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

                    <!-- ══ LIVE AVAILABILITY STATUS ══ -->
                    <div id="availabilityStatus">
                        <div class="av-icon" id="avIcon"></div>
                        <div style="flex:1">
                            <div id="avTitle" style="font-weight:700;font-size:.82rem"></div>
                            <div id="avSub"   style="font-size:.7rem;margin-top:1px;opacity:.85"></div>
                        </div>
                        <div id="avPill" style="padding:3px 9px;border-radius:20px;font-size:.68rem;font-weight:800;letter-spacing:.04em;white-space:nowrap"></div>
                    </div>

                    <!-- Booked slots breakdown table -->
                    <div id="bookedSlotsWrap">
                        <div class="bs-header">
                            <i class="fa-solid fa-clock" style="font-size:.65rem"></i>
                            Other bookings on this date
                        </div>
                        <div id="bookedSlotsList"></div>
                    </div>

                    <!-- Styled purpose select -->
                    <div style="margin-bottom:14px;margin-top:14px">
                        <label class="field-label">Purpose of Visit</label>
                        <div class="cs-wrap" id="purposeWrap">
                            <div class="cs-trigger" id="purposeTrigger">
                                <span id="purposeLabel">— Select purpose —</span>
                                <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="cs-drop" id="purposeDrop">
                                <div class="cs-opt cs-placeholder" data-value=""><span class="cs-opt-label">— Select purpose —</span></div>
                                <div class="cs-divider"></div>
                                <div class="cs-opt" data-value="Work">
                                    <div class="cs-opt-icon" style="background:rgba(99,102,241,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="2" y="5" width="12" height="9" rx="1.5" stroke="#6366f1" stroke-width="1.3"/><path d="M5 5V4a3 3 0 016 0v1" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/></svg></div>
                                    <span class="cs-opt-label">Work</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="Personal">
                                    <div class="cs-opt-icon" style="background:rgba(236,72,153,.09)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5" r="3" stroke="#db2777" stroke-width="1.3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="#db2777" stroke-width="1.3" stroke-linecap="round"/></svg></div>
                                    <span class="cs-opt-label">Personal</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="Study">
                                    <div class="cs-opt-icon" style="background:rgba(20,184,166,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M1 4l7-2 7 2-7 2z" stroke="#0d9488" stroke-width="1.3" stroke-linejoin="round"/><path d="M4 6v4c0 1.1 1.8 2 4 2s4-.9 4-2V6" stroke="#0d9488" stroke-width="1.3" stroke-linecap="round"/><path d="M15 4v4" stroke="#0d9488" stroke-width="1.3" stroke-linecap="round"/></svg></div>
                                    <span class="cs-opt-label">Study</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="SK Activity">
                                    <div class="cs-opt-icon" style="background:rgba(245,158,11,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><polygon points="8,1 10,6 15,6 11,9 13,14 8,11 3,14 5,9 1,6 6,6" stroke="#d97706" stroke-width="1.3" stroke-linejoin="round"/></svg></div>
                                    <span class="cs-opt-label">SK Activity</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="cs-opt" data-value="Others">
                                    <div class="cs-opt-icon" style="background:rgba(100,116,139,.1)"><svg width="13" height="13" viewBox="0 0 16 16" fill="none"><circle cx="4" cy="8" r="1.2" fill="#64748b"/><circle cx="8" cy="8" r="1.2" fill="#64748b"/><circle cx="12" cy="8" r="1.2" fill="#64748b"/></svg></div>
                                    <span class="cs-opt-label">Others</span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
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
            document.getElementById('visitorFields').style.display = isUser ? 'none' : 'block';
            selectedUser = null;
            ['userNameInput','userEmailDisplay','visitorNameInput','visitorEmailInput'].forEach(id => {
                const el = document.getElementById(id); if (el) el.value = '';
            });
            document.getElementById('finalUserId').value = '';
            if (type !== 'Visitor') {
                hideGuestBox();
                hideCodeSection();
                hideRegisteredWarning();
            }
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

        /* ════════════════════════════
           CUSTOM SELECT LOGIC
        ════════════════════════════ */
        let _csActive = null;

        function closeAllCS() {
            document.querySelectorAll('.cs-drop').forEach(d => d.style.display = 'none');
            document.querySelectorAll('.cs-trigger').forEach(t => t.classList.remove('open'));
            _csActive = null;
        }
        document.addEventListener('click', closeAllCS);

        function initCS(wrapId, dropId, labelId, onChange) {
            const wrap    = document.getElementById(wrapId);
            const trigger = wrap.querySelector('.cs-trigger');
            const label   = document.getElementById(labelId);
            const drop    = document.getElementById(dropId);
            const opts    = drop.querySelectorAll('.cs-opt');

            trigger.addEventListener('click', e => {
                e.stopPropagation();
                if (_csActive === dropId) { closeAllCS(); return; }
                closeAllCS(); _csActive = dropId;
                drop.style.display = 'block';
                trigger.classList.add('open');
            });

            opts.forEach(opt => {
                opt.addEventListener('click', e => {
                    e.stopPropagation();
                    const val  = opt.dataset.value;
                    const text = opt.querySelector('.cs-opt-label')?.textContent.trim() || '';
                    opts.forEach(o => o.classList.remove('cs-selected'));
                    if (val !== '') {
                        opt.classList.add('cs-selected');
                        label.textContent = text;
                        trigger.classList.add('has-value');
                    } else {
                        label.textContent = text;
                        trigger.classList.remove('has-value');
                    }
                    closeAllCS();
                    if (onChange) onChange(val, opt);
                });
            });
        }

        function getSelectedResourceVal()    { const o = document.querySelector('#resourceDrop .cs-opt.cs-selected'); return o ? o.dataset.value : ''; }
        function getSelectedResourceName()   { const o = document.querySelector('#resourceDrop .cs-opt.cs-selected'); return o ? o.querySelector('.cs-opt-label')?.textContent.trim() : '—'; }
        function getSelectedResourceIsWifi() { const o = document.querySelector('#resourceDrop .cs-opt.cs-selected'); return o ? (o.dataset.isWifi === '1') : false; }
        function getSelectedPurpose()        { const o = document.querySelector('#purposeDrop  .cs-opt.cs-selected'); return o ? o.dataset.value : ''; }

        initCS('resourceWrap', 'resourceDrop', 'resourceLabel', function(val, opt) {
            document.getElementById('nativeResource').innerHTML = `<option value="${val}" selected></option>`;
            const hasPcs = opt.dataset.hasPcs === '1';
            const isWifi = opt.dataset.isWifi === '1';
            document.getElementById('pcSection').style.display = hasPcs ? 'block' : 'none';
            // Show/hide WiFi banner
            const wifiBanner = document.getElementById('wifiNoticeBanner');
            wifiBanner.style.display = isWifi ? 'flex' : 'none';
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(b => b.classList.remove('selected'));
            // Trigger availability check when resource changes
            schedAvailabilityCheck();
        });

        initCS('purposeWrap', 'purposeDrop', 'purposeLabel', function(val) {
            document.getElementById('nativePurpose').innerHTML = `<option value="${val}" selected></option>`;
            document.getElementById('purposeOtherWrap').style.display = val === 'Others' ? 'block' : 'none';
            if (val !== 'Others') document.getElementById('purposeOther').value = '';
        });

        /* ════════════════════════════
           LIVE AVAILABILITY CHECKER
        ════════════════════════════ */
        let _avTimer = null;

        function schedAvailabilityCheck() {
            clearTimeout(_avTimer);
            _avTimer = setTimeout(runAvailabilityCheck, 450);
        }

        function runAvailabilityCheck() {
            const resourceId = getSelectedResourceVal();
            const date       = document.getElementById('resDate').value;
            const startTime  = document.getElementById('startTime').value;
            const endTime    = document.getElementById('endTime').value;

            const status = document.getElementById('availabilityStatus');
            const slots  = document.getElementById('bookedSlotsWrap');

            if (!resourceId || !date) {
                status.className = '';
                status.style.display = 'none';
                slots.style.display = 'none';
                return;
            }

            // Show checking state
            setAvStatus('checking',
                '<i class="fa-solid fa-circle-notch spin-icon" style="font-size:.75rem"></i>',
                'Checking availability…',
                startTime && endTime ? 'Verifying your time slot against existing bookings.' : 'Select start & end time to check for conflicts.',
                ''
            );

            const params = new URLSearchParams({
                resource_id: resourceId,
                date:        date,
                start_time:  startTime || '',
                end_time:    endTime   || '',
            });

            fetch('/admin/check-availability?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const booked = data.booked_slots || [];

                if (data.has_conflict) {
                    setAvStatus('conflict',
                        '<i class="fa-solid fa-ban" style="font-size:.78rem;color:#b91c1c"></i>',
                        'Time slot conflict detected',
                        'Your selected time overlaps with an existing booking. Please choose a different time.',
                        'UNAVAILABLE'
                    );
                } else if (startTime && endTime) {
                    setAvStatus('ok',
                        '<i class="fa-solid fa-circle-check" style="font-size:.78rem;color:#15803d"></i>',
                        'Time slot is available',
                        booked.length
                            ? `${booked.length} other booking(s) on this date — none overlap your selected time.`
                            : 'No other bookings on this date. All yours!',
                        'AVAILABLE'
                    );
                } else {
                    setAvStatus('info',
                        '<i class="fa-solid fa-calendar-check" style="font-size:.78rem;color:#92400e"></i>',
                        booked.length ? `${booked.length} booking(s) on this date` : 'No bookings yet on this date',
                        'Select start & end time to check if your slot is free.',
                        booked.length ? `${booked.length} BOOKED` : 'FREE'
                    );
                }

                // Render booked slots table
                if (booked.length > 0) {
                    renderBookedSlots(booked, startTime, endTime);
                } else {
                    slots.style.display = 'none';
                }
            })
            .catch(() => {
                setAvStatus('info',
                    '<i class="fa-solid fa-wifi" style="font-size:.78rem;color:#92400e"></i>',
                    'Could not check availability',
                    'Network error. Please continue manually.',
                    ''
                );
                slots.style.display = 'none';
            });
        }

        function setAvStatus(type, iconHtml, title, sub, pill) {
            const status = document.getElementById('availabilityStatus');
            const pillColors = {
                ok:       { bg: '#dcfce7', color: '#15803d' },
                conflict: { bg: '#fee2e2', color: '#b91c1c' },
                info:     { bg: '#fef3c7', color: '#92400e' },
                checking: { bg: '#e0f2fe', color: '#0369a1' },
            };
            const avIcon  = document.getElementById('avIcon');
            const avTitle = document.getElementById('avTitle');
            const avSub   = document.getElementById('avSub');
            const avPill  = document.getElementById('avPill');

            status.className = 'av-' + type;
            avIcon.innerHTML  = iconHtml;
            avTitle.textContent = title;
            avSub.textContent   = sub;
            if (pill) {
                const c = pillColors[type] || {};
                avPill.style.background = c.bg || '#e2e8f0';
                avPill.style.color      = c.color || '#475569';
                avPill.textContent      = pill;
                avPill.style.display    = 'inline-block';
            } else {
                avPill.style.display = 'none';
            }
        }

        function renderBookedSlots(slots, myStart, myEnd) {
            const wrap = document.getElementById('bookedSlotsWrap');
            const list = document.getElementById('bookedSlotsList');

            let html = '';
            slots.forEach(s => {
                const start = s.start_time.substring(0, 5);
                const end   = s.end_time.substring(0, 5);
                const isConflict = myStart && myEnd &&
                    myStart < s.end_time && myEnd > s.start_time;
                const statusClass = s.status === 'approved' ? 'bs-pill-approved' : 'bs-pill-pending';
                const statusLabel = s.status === 'approved' ? '✔ Approved' : '⏳ Pending';
                const rowClass    = isConflict ? 'bs-row bs-conflict-row' : 'bs-row';

                html += `<div class="${rowClass}">
                    <div class="bs-col">
                        <span class="bs-status-pill ${statusClass}">${statusLabel}</span>
                    </div>
                    <div class="bs-col" style="font-family:var(--mono);font-size:.78rem;font-weight:600">
                        ${start} – ${end}
                    </div>
                    <div class="bs-col" style="text-align:right">
                        ${isConflict ? '<span style="font-size:.68rem;font-weight:700;color:#b91c1c;background:#fee2e2;padding:2px 7px;border-radius:20px">⚡ CONFLICT</span>' : ''}
                    </div>
                </div>`;
            });

            list.innerHTML = html;
            wrap.style.display = 'block';
        }

        // Hook date/time pickers to re-run availability check
        // These are called from the picker IIFE below via window callbacks
        window._onDatePicked  = schedAvailabilityCheck;
        window._onTimePicked  = schedAvailabilityCheck;

        function previewReservation() {
            // Block if walk-in name matches a registered resident
            if (currentType === 'Visitor' && window._guestIsRegistered) {
                alert('⛔ This person is a registered resident. Please use the Registered User toggle and select them from the dropdown.');
                return;
            }

            // Block if walk-in quota exceeded (only for non-WiFi)
            if (currentType === 'Visitor' && window._guestBlocked && !getSelectedResourceIsWifi()) {
                const name = document.getElementById('visitorNameInput')?.value?.trim() || 'This guest';
                alert(`⛔ ${name} has reached the 3-reservation limit within the last 3 days and cannot make a new reservation.`);
                return;
            }

            // Block if hard time conflict detected
            if (window._avHasConflict) {
                alert('⛔ This time slot has a confirmed booking conflict. Please choose a different time or date.');
                return;
            }

            // For new walk-in visitors: require identity confirmation code
            if (currentType === 'Visitor') {
                const codeSection = document.getElementById('confirmCodeSection');
                if (codeSection && codeSection.style.display !== 'none') {
                    const verified = document.getElementById('confirmCodeVerified')?.value;
                    if (verified !== '1') {
                        alert('⚠️ Please generate a confirmation code and have the visitor verify it before proceeding.');
                        document.getElementById('genCodeBtn')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                }
            }

            const isUser       = currentType === 'User';
            const name         = isUser ? userNameInput.value.trim() : document.getElementById('visitorNameInput').value.trim();
            const email        = isUser ? document.getElementById('userEmailDisplay').value.trim() : document.getElementById('visitorEmailInput').value.trim();
            const resourceId   = getSelectedResourceVal();
            const resourceName = getSelectedResourceName();
            const showPcs      = document.getElementById('pcSection').style.display !== 'none';
            const date         = document.getElementById('resDate').value;
            const startTime    = document.getElementById('startTime').value;
            const endTime      = document.getElementById('endTime').value;
            const purposeVal   = getSelectedPurpose();
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ? `Others — ${purposeOther}` : purposeVal;

            if (!name)        return alert('Please enter a name.');
            if (!resourceId)  return alert('Please select a resource.');
            if (showPcs && !selectedPcs.length) return alert('Please select at least one workstation.');
            if (!date)        return alert('Please select a date.');
            if (!startTime)   return alert('Please enter a start time.');
            if (!endTime)     return alert('Please enter an end time.');
            if (!purposeVal)  return alert('Please select a purpose.');
            if (isUser && !selectedUser && !document.getElementById('finalUserId').value)
                return alert('Please select a registered user from the dropdown.');

            document.getElementById('finalVisitorName').value = name;
            document.getElementById('finalUserEmail').value   = email;
            document.getElementById('finalPurpose').value     = purposeFinal;
            document.getElementById('nativeResource').innerHTML = `<option value="${resourceId}" selected></option>`;
            document.getElementById('nativePurpose').innerHTML  = `<option value="${purposeVal}" selected></option>`;

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
            if (!isUser) {
                rows.splice(2, 0, ['Identity', 'Confirmed ✓']);
            }
            if (getSelectedResourceIsWifi()) {
                rows.push(['Quota Check', '⚡ Skipped — WiFi resource']);
            }
            document.getElementById('modalSummaryBox').innerHTML =
                rows.map(([l,v]) => `<div class="mrow"><span class="mrow-label">${l}</span><span class="mrow-value">${v}</span></div>`).join('');
            document.getElementById('qrWrap').style.display     = 'none';
            document.getElementById('confirmBtn').style.display  = 'flex';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.8rem"></i> Saving…';
            const code = `ACCESS-${Date.now()}`;
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
            btn.innerHTML = '<i class="fa-solid fa-check" style="font-size:.8rem"></i> Confirm & Save';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // Track conflict state for blocking preview
        window._avHasConflict = false;
        const _origSetAvStatus = setAvStatus;
        // Patch setAvStatus to track conflict
        const _origSetAv = setAvStatus;
        window.setAvStatus = function(type, ...args) {
            window._avHasConflict = (type === 'conflict');
            _origSetAv(type, ...args);
        };
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
            const todayFlat = new Date(TODAY.getFullYear(), TODAY.getMonth(), TODAY.getDate());
            const firstDow=new Date(y,m,1).getDay(), daysInM=new Date(y,m+1,0).getDate(), prevTotal=new Date(y,m,0).getDate();
            let html=`<div class="cal-head"><div class="cal-nav-btn" id="_calPrev">&#8249;</div><div class="cal-month-label">${MONTHS[m]} ${y}</div><div class="cal-nav-btn" id="_calNext">&#8250;</div></div><div class="cal-grid">${DOWS.map(d=>`<div class="cal-dow">${d}</div>`).join('')}`;
            for(let i=0;i<firstDow;i++) html+=`<div class="cal-day cal-other">${prevTotal-firstDow+1+i}</div>`;
            for(let d=1;d<=daysInM;d++){
                const thisDate = new Date(y, m, d);
                const isPast  = thisDate < todayFlat;
                const isToday = d===TODAY.getDate()&&m===TODAY.getMonth()&&y===TODAY.getFullYear();
                const isSel   = selDate&&selDate.d===d&&selDate.m===m&&selDate.y===y;
                const classes = ['cal-day', isPast?'cal-past':'', isToday&&!isSel?'cal-today':'', isSel?'cal-selected':''].filter(Boolean).join(' ');
                html += `<div class="${classes}"${isPast ? '' : ` data-d="${d}"`}>${d}</div>`;
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
            renderCal();
            setTimeout(closeAll,180);
            // Trigger availability check
            if (window._onDatePicked) window._onDatePicked();
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
            // Trigger availability check
            if (window._onTimePicked) window._onTimePicked();
        }

        $('dateTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('calDrop','dateTrigger');if(activeDrop==='calDrop')renderCal();});
        $('startTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('startDrop','startTrigger');if(activeDrop==='startDrop')renderTime('start');});
        $('endTrigger').addEventListener('click',e=>{e.stopPropagation();toggle('endDrop','endTrigger');if(activeDrop==='endDrop')renderTime('end');});

        (function initDefaultTimes() {
            ['start', 'end'].forEach(function(which) {
                var st = tState[which];
                var h24 = st.h;
                if (st.ampm === 'am' && st.h === 12) h24 = 0;
                if (st.ampm === 'pm' && st.h !== 12) h24 = st.h + 12;
                var iso24 = String(h24).padStart(2, '0') + ':' + String(st.min).padStart(2, '0');
                var label = String(st.h).padStart(2, '0') + ':' + String(st.min).padStart(2, '0') + ' ' + st.ampm.toUpperCase();
                var labelId   = which === 'start' ? 'startLabel'   : 'endLabel';
                var inputId   = which === 'start' ? 'startTime'    : 'endTime';
                var triggerId = which === 'start' ? 'startTrigger' : 'endTrigger';
                $(labelId).textContent = label;
                $(inputId).value       = iso24;
                $(triggerId).classList.add('has-value');
            });
        })();

        (function(){
            const t=new Date();
            $('dateLabel').textContent=t.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
            $('dateTrigger').classList.add('has-value');
            selDate={d:t.getDate(),m:t.getMonth(),y:t.getFullYear()};
        })();
    })();
    </script>

    <!-- ══════════════════════════════════════════
         GUEST LIMIT CHECKER + REGISTERED WARNING + CONFIRMATION CODE
    ══════════════════════════════════════════ -->
    <script>
    (function () {
        const LIMIT     = 3;
        const CHECK_URL = '/admin/check-guest-limit';

        // Module state
        window._guestBlocked      = false;
        window._guestIsRegistered = false;
        window._guestTimer        = null;
        window._generatedCode     = null;

        /* ─── Debounced trigger ─── */
        window.schedGuestCheck = function () {
            clearTimeout(window._guestTimer);
            window._guestTimer = setTimeout(window.runGuestCheck, 600);
        };

        /* ─── Main check ─── */
        window.runGuestCheck = function () {
            clearTimeout(window._guestTimer);

            if (currentType === 'User') { hideGuestBox(); hideCodeSection(); hideRegisteredWarning(); return; }

            const name  = (document.getElementById('visitorNameInput')?.value  || '').trim();
            const email = (document.getElementById('visitorEmailInput')?.value || '').trim();
            if (!name && !email) { hideGuestBox(); hideCodeSection(); hideRegisteredWarning(); return; }

            const params = new URLSearchParams();
            if (name)  params.append('name',  name);
            if (email) params.append('email', email);
            params.append('visitor_type', currentType);

            fetch(`${CHECK_URL}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                // If the typed name matches a registered resident, warn and block
                if (data.is_registered) {
                    window._guestIsRegistered = true;
                    window._guestBlocked      = true;
                    hideGuestBox();
                    hideCodeSection();
                    showRegisteredWarning(data.registered_name || name);
                    return;
                }

                window._guestIsRegistered = false;
                hideRegisteredWarning();

                if (data.skip_quota) { hideGuestBox(); hideCodeSection(); return; }

                renderGuestBox(data);

                // Show confirmation code only for brand-new, non-blocked visitors
                if (data.is_new && !data.blocked) {
                    showCodeSection();
                } else {
                    hideCodeSection();
                }
            })
            .catch(() => {
                window._guestIsRegistered = false;
                hideGuestBox();
                hideCodeSection();
                hideRegisteredWarning();
            });
        };

        /* ─── Registered-resident warning ─── */
        window.showRegisteredWarning = function (name) {
            const box   = document.getElementById('registeredWarnBox');
            const title = document.getElementById('regWarnTitle');
            if (!box) return;
            if (title) title.textContent = `"${name}" is a registered resident`;
            box.style.display = 'flex';
        };

        window.hideRegisteredWarning = function () {
            window._guestIsRegistered = false;
            const box = document.getElementById('registeredWarnBox');
            if (box) box.style.display = 'none';
        };

        /* ─── Generate 4-digit code ─── */
        window.generateConfirmCode = function () {
            const code = String(Math.floor(1000 + Math.random() * 9000));
            window._generatedCode = code;

            document.getElementById('confirmCodeValue').value    = code;
            document.getElementById('confirmCodeVerified').value = '0';

            document.getElementById('codeDigits').textContent = code;
            document.getElementById('codeDisplayWrap').style.display = 'flex';
            document.getElementById('pinInputArea').style.display    = 'block';

            ['pin0','pin1','pin2','pin3'].forEach(id => {
                const el = document.getElementById(id);
                el.value = '';
                el.classList.remove('pin-ok','pin-err');
            });
            document.getElementById('pinFeedback').textContent = '';
            document.getElementById('pinFeedback').style.color  = '';

            setTimeout(() => document.getElementById('pin0')?.focus(), 50);
        };

        /* ─── PIN navigation helpers ─── */
        window.pinAdvance = function (el, nextIdx) {
            el.value = el.value.replace(/\D/g, '').slice(-1);
            if (el.value && nextIdx <= 3) {
                document.getElementById('pin' + nextIdx)?.focus();
            }
        };

        window.pinBack = function (e, el, prevIdx) {
            if (e.key === 'Backspace' && !el.value && prevIdx !== null) {
                document.getElementById('pin' + prevIdx)?.focus();
            }
        };

        /* ─── Final digit entered — check match ─── */
        window.pinFinish = function (el) {
            el.value = el.value.replace(/\D/g, '').slice(-1);
            const entered = ['pin0','pin1','pin2','pin3']
                .map(id => document.getElementById(id).value)
                .join('');
            if (entered.length < 4) return;

            const fb   = document.getElementById('pinFeedback');
            const pins = document.querySelectorAll('.pin-box');

            if (entered === window._generatedCode) {
                pins.forEach(p => { p.classList.remove('pin-err'); p.classList.add('pin-ok'); });
                fb.textContent = '✓ Code verified — visitor identity confirmed';
                fb.style.color  = '#16a34a';
                document.getElementById('confirmCodeVerified').value = '1';
            } else {
                pins.forEach(p => { p.classList.remove('pin-ok'); p.classList.add('pin-err'); });
                fb.textContent = '✗ Code mismatch — ask visitor to repeat or click Generate again';
                fb.style.color  = '#dc2626';
                document.getElementById('confirmCodeVerified').value = '0';
                document.querySelector('.pin-row')?.classList.add('shake');
                setTimeout(() => document.querySelector('.pin-row')?.classList.remove('shake'), 400);
            }
        };

        /* ─── Show / hide helpers ─── */
        function showCodeSection() {
            const s = document.getElementById('confirmCodeSection');
            if (s) s.style.display = 'block';
        }

        function hideCodeSection() {
            const s = document.getElementById('confirmCodeSection');
            if (s) s.style.display = 'none';
            window._generatedCode = null;
            const cv  = document.getElementById('confirmCodeVerified');  if (cv)  cv.value = '0';
            const cdv = document.getElementById('confirmCodeValue');      if (cdv) cdv.value = '';
            const wrap = document.getElementById('codeDisplayWrap');     if (wrap) wrap.style.display = 'none';
            const pin  = document.getElementById('pinInputArea');         if (pin)  pin.style.display  = 'none';
            ['pin0','pin1','pin2','pin3'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.value = ''; el.classList.remove('pin-ok','pin-err'); }
            });
            const fb = document.getElementById('pinFeedback');
            if (fb) { fb.textContent = ''; }
        }
        window.hideCodeSection = hideCodeSection;

        /* ─── Guest quota box renderer ─── */
        function renderGuestBox(data) {
            const count   = data.count   ?? 0;
            const limit   = data.limit   ?? LIMIT;
            const blocked = data.blocked ?? (count >= limit);
            const pct     = Math.min(count / limit * 100, 100);

            window._guestBlocked = blocked;

            const box   = document.getElementById('guestLimitBox');
            const inner = document.getElementById('guestLimitInner');
            const icon  = document.getElementById('guestLimitIcon');
            const title = document.getElementById('guestLimitTitle');
            const sub   = document.getElementById('guestLimitSub');
            const pill  = document.getElementById('guestLimitPill');
            const bar   = document.getElementById('guestLimitBar');
            const cnt   = document.getElementById('guestLimitCount');

            box.style.display = 'block';
            cnt.textContent   = `${count} / ${limit} used`;
            bar.style.width   = pct + '%';

            if (blocked) {
                inner.style.background  = '#fef2f2';
                inner.style.borderColor = '#fecaca';
                icon.style.background   = '#fee2e2';
                icon.innerHTML          = '<i class="fa-solid fa-ban" style="color:#dc2626"></i>';
                title.style.color       = '#dc2626';
                title.textContent       = 'Reservation limit reached';
                sub.style.color         = '#dc2626';
                sub.textContent         = `This guest has used all ${limit} slots within the last 3 days.`;
                pill.style.background   = '#dc2626';
                pill.style.color        = '#fff';
                pill.textContent        = 'BLOCKED';
                bar.style.background    = '#ef4444';
            } else if (count === limit - 1) {
                inner.style.background  = '#fffbeb';
                inner.style.borderColor = '#fde68a';
                icon.style.background   = '#fef3c7';
                icon.innerHTML          = '<i class="fa-solid fa-triangle-exclamation" style="color:#d97706"></i>';
                title.style.color       = '#92400e';
                title.textContent       = 'Last reservation slot';
                sub.style.color         = '#92400e';
                sub.textContent         = `${limit - count} slot remaining in the 3-day window.`;
                pill.style.background   = '#f59e0b';
                pill.style.color        = '#fff';
                pill.textContent        = 'LAST SLOT';
                bar.style.background    = '#f59e0b';
            } else if (count > 0) {
                inner.style.background  = 'rgba(99,102,241,.05)';
                inner.style.borderColor = 'rgba(99,102,241,.2)';
                icon.style.background   = 'rgba(99,102,241,.1)';
                icon.innerHTML          = '<i class="fa-solid fa-circle-check" style="color:#6366f1"></i>';
                title.style.color       = '#3730a3';
                title.textContent       = 'Returning visitor';
                sub.style.color         = '#4f46e5';
                sub.textContent         = `${limit - count} slot(s) remaining in the 3-day window.`;
                pill.style.background   = '#ede9fe';
                pill.style.color        = '#4f46e5';
                pill.textContent        = `${count}/${limit} USED`;
                bar.style.background    = '#6366f1';
            } else {
                inner.style.background  = '#f0fdf4';
                inner.style.borderColor = '#bbf7d0';
                icon.style.background   = '#dcfce7';
                icon.innerHTML          = '<i class="fa-solid fa-user-plus" style="color:#16a34a"></i>';
                title.style.color       = '#15803d';
                title.textContent       = 'New visitor';
                sub.style.color         = '#16a34a';
                sub.textContent         = `No recent reservations found. ${limit} slots available.`;
                pill.style.background   = '#22c55e';
                pill.style.color        = '#fff';
                pill.textContent        = 'NEW';
                bar.style.background    = '#22c55e';
            }
        }

        function hideGuestBox() {
            window._guestBlocked = false;
            const box = document.getElementById('guestLimitBox');
            if (box) box.style.display = 'none';
        }
        window.hideGuestBox = hideGuestBox;

    })();
    </script>
</body>
</html>