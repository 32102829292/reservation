<?php $page = 'reservation'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>New Reservation | <?= esc($user_name ?? 'User') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>

    <script>
        (function() {
            try { if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark-pre'); } catch(e){}
        })();
    </script>

    <style>
        html.dark-pre body { background: #060e1e; }

        /* ══════════════════════════════ BASE LAYOUT ══════════════════════════════ */
        .main-area { padding: 24px 20px; }
        @media(max-width:639px) { .main-area { padding: 16px 14px !important; } }

        /* ══════════════════════════════ PAGE HEADER ══════════════════════════════ */
        .page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:16px; flex-wrap:wrap; }
        .page-eyebrow { font-size:.62rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:var(--text-sub); margin-bottom:4px; }
        .page-title   { font-size:1.6rem; font-weight:800; color:var(--text); letter-spacing:-.04em; line-height:1.1; }
        .page-sub     { font-size:.8rem; color:var(--text-sub); margin-top:4px; font-weight:500; }
        @media(max-width:480px) { .page-title { font-size:1.35rem; } }

        /* ══════════════════════════════ FLASH ══════════════════════════════ */
        .flash { display:flex; align-items:center; gap:12px; margin-bottom:16px; padding:13px 18px; font-weight:600; border-radius:var(--r-md); font-size:.88rem; border:1px solid; }
        .flash-ok   { background:var(--indigo-light); border-color:var(--indigo-border); color:var(--indigo); }
        .flash-err  { background:#fee2e2; border-color:#fecaca; color:#991b1b; }
        .flash-info { background:#fef3c7; border-color:#fde68a; color:#92400e; }
        body.dark .flash-ok   { background:rgba(55,48,163,.2); border-color:rgba(99,102,241,.3); color:#a5b4fc; }
        body.dark .flash-err  { background:rgba(220,38,38,.1); border-color:rgba(248,113,113,.3); color:#f87171; }
        body.dark .flash-info { background:rgba(180,83,9,.15); border-color:rgba(251,191,36,.25); color:#fcd34d; }

        /* ══════════════════════════════ FORM CARD ══════════════════════════════ */
        .form-card { background:var(--card); border-radius:var(--r-xl); border:1px solid var(--border); box-shadow:var(--shadow-sm); padding:28px; max-width:760px; margin:0 auto; }
        @media(max-width:639px) { .form-card { padding:18px 16px; border-radius:var(--r-lg); } }

        /* ══════════════════════════════ SECTION ══════════════════════════════ */
        .section-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:var(--indigo-light); color:var(--indigo); }
        .section-title { font-size:.95rem; font-weight:700; color:var(--text); }
        .section-sub   { font-size:.7rem; color:var(--text-sub); margin-top:2px; }
        .section-divider { border:none; border-top:1px solid var(--border); margin:1.5rem 0; }
        .field-label { font-size:.62rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--text-sub); display:block; margin-bottom:6px; }

        /* ══════════════════════════════ GRIDS ══════════════════════════════ */
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
        @media(max-width:639px) { .grid-2,.grid-3 { grid-template-columns:1fr; gap:12px; } }

        /* ══════════════════════════════ INPUTS ══════════════════════════════ */
        input, select, textarea {
            width:100%; padding:.75rem 1rem; border:1px solid var(--border);
            font-size:.88rem; transition:all var(--ease); background:var(--input-bg);
            border-radius:var(--r-sm); font-family:var(--font); color:var(--text);
            outline:none; appearance:none; -webkit-appearance:none;
        }
        input:focus, select:focus, textarea:focus { border-color:#818cf8; background:var(--card); box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        input[readonly] { background:var(--input-bg); color:var(--text-sub); cursor:not-allowed; opacity:.75; }
        body.dark input, body.dark select, body.dark textarea { background:#101e35; border-color:rgba(99,102,241,.18); color:#e2eaf8; }
        body.dark input:focus, body.dark select:focus { background:#0b1628; border-color:#818cf8; }
        body.dark input[readonly] { background:#060e1e; color:#4a6fa5; }
        body.dark input::placeholder { color:#4a6fa5; }

        /* ══════════════════════════════ PC SECTION ══════════════════════════════ */
        .pc-section { background:var(--indigo-light); border:1px solid var(--indigo-border); border-radius:var(--r-md); padding:1.25rem; }
        .pc-section-lbl { font-size:.62rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--indigo); display:block; margin-bottom:10px; }
        .pc-btn { padding:.6rem .75rem; border-radius:9px; font-size:.75rem; font-weight:700; border:1px solid var(--indigo-border); background:var(--card); color:var(--text-muted); transition:all var(--ease); cursor:pointer; font-family:var(--font); }
        .pc-btn:hover { border-color:var(--indigo); color:var(--indigo); }
        .pc-btn.selected-pc { background:var(--indigo)!important; color:white!important; border-color:var(--indigo)!important; box-shadow:0 4px 10px rgba(55,48,163,.3); }
        /* Unavailable PC styling */
        .pc-btn.unavailable-pc { background:#fee2e2!important; color:#991b1b!important; border-color:#fecaca!important; cursor:not-allowed!important; opacity:.7; }
        body.dark .pc-btn.unavailable-pc { background:rgba(220,38,38,.15)!important; color:#f87171!important; border-color:rgba(248,113,113,.25)!important; }
        body.dark .pc-section { background:rgba(55,48,163,.1); border-color:rgba(99,102,241,.2); }
        body.dark .pc-btn { background:#101e35; border-color:rgba(99,102,241,.22); color:#7fb3e8; }

        /* ══════════════════════════════ PRIMARY BUTTON ══════════════════════════════ */
        .btn-primary { background:var(--indigo); color:white; border:none; padding:.85rem 1.75rem; border-radius:var(--r-md); font-weight:700; font-size:.88rem; cursor:pointer; transition:all var(--ease); font-family:var(--font); display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(55,48,163,.28); touch-action:manipulation; width:100%; justify-content:center; }
        .btn-primary:hover { background:#312e81; transform:translateY(-1px); box-shadow:0 6px 18px rgba(55,48,163,.35); }
        .btn-primary:disabled { opacity:.6; cursor:not-allowed; transform:none; }

        /* ══════════════════════════════ MODAL ══════════════════════════════ */
        .modal-back { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(7px); z-index:200; padding:1.5rem; overflow-y:auto; align-items:center; justify-content:center; }
        .modal-back.show { display:flex; animation:fadeIn .15s ease; }
        @keyframes fadeIn { from{opacity:0}to{opacity:1} }
        .modal-card { background:var(--card); border-radius:var(--r-xl); width:100%; max-width:460px; padding:28px; margin:auto; animation:slideUp .2s ease; max-height:90vh; overflow-y:auto; box-shadow:var(--shadow-lg); }
        @keyframes slideUp { from{transform:translateY(14px);opacity:0}to{transform:none;opacity:1} }
        .sheet-handle { display:none; width:36px; height:4px; background:#e2e8f0; border-radius:999px; margin:0 auto 16px; }
        @media(max-width:639px) { .modal-back{padding:0;align-items:flex-end!important} .modal-card{border-radius:var(--r-xl) var(--r-xl) 0 0;max-width:100%;animation:sheetUp .25s cubic-bezier(.34,1.2,.64,1) both;padding:20px 16px 32px;} .sheet-handle{display:block;} }
        @keyframes sheetUp { from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none} }
        .modal-summary-box { background:var(--input-bg); border-radius:var(--r-md); padding:16px; border:1px solid var(--border); margin-bottom:16px; }
        .modal-title { font-size:1.1rem; font-weight:800; color:var(--text); letter-spacing:-.02em; }
        .mrow { display:flex; justify-content:space-between; align-items:flex-start; padding:.55rem 0; border-bottom:1px solid var(--border); gap:1rem; }
        .mrow:last-child { border-bottom:none; }
        .mrow-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--text-sub); flex-shrink:0; }
        .mrow-value { font-weight:600; color:var(--text); font-size:.84rem; text-align:right; }
        .modal-cancel-btn { flex:1; padding:.75rem; background:var(--input-bg); border-radius:var(--r-sm); font-weight:700; color:var(--text-muted); border:1px solid var(--border); cursor:pointer; font-family:var(--font); font-size:.85rem; transition:background .15s; }
        .modal-cancel-btn:hover { background:var(--indigo-light); color:var(--indigo); }
        body.dark .modal-card { background:#0b1628; }
        body.dark .modal-summary-box { background:#060e1e; border-color:rgba(99,102,241,.1); }
        body.dark .modal-title { color:#e2eaf8; }
        body.dark .modal-cancel-btn { background:#101e35; border-color:rgba(99,102,241,.18); color:#7fb3e8; }

        /* ══════════════════════════════ TOAST ══════════════════════════════ */
        .toast-wrap { position:fixed; top:80px; right:24px; left:24px; z-index:2000; pointer-events:none; display:flex; flex-direction:column; align-items:flex-end; }
        @media(min-width:640px) { .toast-wrap { left:auto; width:320px; } }
        .toast { background:#0f172a; border-radius:14px; padding:12px 14px; box-shadow:0 8px 32px rgba(0,0,0,.3); margin-bottom:.65rem; pointer-events:auto; width:100%; display:flex; align-items:flex-start; gap:10px; animation:l-slide-up .3s ease; }

        /* ══════════════════════════════ NOTIF ══════════════════════════════ */
        .notif-dd { position:fixed; top:80px; right:20px; width:320px; background:var(--card); border-radius:var(--r-xl); box-shadow:var(--shadow-lg),0 0 0 1px var(--border); z-index:1000; display:none; overflow:hidden; }
        .notif-dd.show { display:block; animation:l-fade-in .15s ease; }
        .notif-item { padding:.85rem 1.1rem; border-bottom:1px solid var(--border-subtle); transition:background .15s; cursor:pointer; color:var(--text); }
        .notif-item:hover { background:var(--input-bg); }
        .notif-item.unread { background:var(--indigo-light); }
        @media(max-width:479px) { .notif-dd{left:12px;right:12px;width:auto;top:72px;} }

        .available   { background:#dcfce7; color:#166534; padding:.3rem .75rem; border-radius:999px; font-size:.75rem; font-weight:600; }
        .unavailable { background:#fee2e2; color:#991b1b; padding:.3rem .75rem; border-radius:999px; font-size:.75rem; font-weight:600; }
        .checking    { background:var(--input-bg); color:var(--text-sub); padding:.3rem .75rem; border-radius:999px; font-size:.75rem; font-weight:600; }
        .hidden { display:none!important; }

        /* ══════════════════════════════ DARK MODE ══════════════════════════════ */
        body.dark .page-title  { color:#e2eaf8; }
        body.dark .page-eyebrow, body.dark .page-sub { color:#4a6fa5; }
        body.dark .section-icon { background:rgba(55,48,163,.2); }
        body.dark .section-title { color:#e2eaf8; }
        body.dark .section-sub, body.dark .field-label { color:#4a6fa5; }
        body.dark .section-divider { border-color:rgba(99,102,241,.1); }
        body.dark .form-card { background:#0b1628; border-color:rgba(99,102,241,.1); }
        body.dark .available   { background:rgba(22,163,74,.15); color:#4ade80; }
        body.dark .unavailable { background:rgba(220,38,38,.15); color:#f87171; }

        /* ══════════════════════════════ CUSTOM DATE/TIME PICKERS ══════════════════════════════ */
        #resDate, #startTime, #endTime { display:none!important; }
        .dt-trigger { display:flex; align-items:center; justify-content:space-between; gap:8px; width:100%; padding:.75rem 1rem; background:var(--input-bg); border:1px solid var(--border); border-radius:var(--r-sm); font-family:var(--font); font-size:.88rem; font-weight:500; color:var(--text-sub); cursor:pointer; transition:border .2s,box-shadow .2s; user-select:none; -webkit-user-select:none; }
        .dt-trigger.has-value { color:var(--text); }
        .dt-trigger:hover { border-color:#818cf8; }
        .dt-trigger.open { border-color:#818cf8; background:var(--card); box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .dt-trigger svg { flex-shrink:0; opacity:.4; }
        .dt-trigger.open svg { opacity:.75; }
        .dt-drop { position:absolute; bottom:calc(100% + 6px); left:0; z-index:9999; border-radius:14px; animation:dtDrop .15s cubic-bezier(.4,0,.2,1); }
        @keyframes dtDrop   { from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none} }
        body:not(.dark) .dt-drop { background:#fff; border:1px solid rgba(99,102,241,.18); box-shadow:0 20px 50px rgba(15,23,42,.18); }
        body.dark .dt-drop { background:#0b1628; border:1px solid rgba(99,102,241,.22); box-shadow:0 20px 60px rgba(0,0,0,.65); }
        .dt-drop.cal { width:288px; padding:18px 16px 14px; }
        .cal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .cal-month-label { font-size:.88rem; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:7px; transition:background .15s; }
        body:not(.dark) .cal-month-label { color:#0f172a; } body:not(.dark) .cal-month-label:hover { background:#f1f5f9; }
        body.dark .cal-month-label { color:#e2e8f0; } body.dark .cal-month-label:hover { background:rgba(99,102,241,.12); }
        .cal-nav-btn { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.75rem; transition:all .15s; }
        body:not(.dark) .cal-nav-btn { background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; } body:not(.dark) .cal-nav-btn:hover { border-color:var(--indigo); color:var(--indigo); background:var(--indigo-light); }
        body.dark .cal-nav-btn { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); color:#94a3b8; } body.dark .cal-nav-btn:hover { border-color:var(--indigo); color:#a5b4fc; background:rgba(99,102,241,.1); }
        .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:2px; }
        .cal-dow { font-size:.6rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; text-align:center; padding:3px 0 9px; }
        body:not(.dark) .cal-dow { color:#94a3b8; } body.dark .cal-dow { color:#4f5a72; }
        .cal-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; border-radius:8px; font-size:.8rem; font-weight:500; cursor:pointer; transition:all .12s; border:1px solid transparent; }
        body:not(.dark) .cal-day { color:#475569; } body.dark .cal-day { color:#8b95b0; }
        body:not(.dark) .cal-day:hover:not(.cal-other):not(.cal-selected):not(.cal-booked) { background:#f1f5f9; color:#0f172a; border-color:#e2e8f0; }
        body.dark .cal-day:hover:not(.cal-other):not(.cal-selected):not(.cal-booked) { background:rgba(255,255,255,.06); color:#e2e8f0; border-color:rgba(255,255,255,.08); }
        .cal-day.cal-other { pointer-events:none; }
        body:not(.dark) .cal-day.cal-other { color:#cbd5e1; } body.dark .cal-day.cal-other { color:#2e3850; }
        body:not(.dark) .cal-day.cal-today { color:var(--indigo); font-weight:700; } body.dark .cal-day.cal-today { color:#818cf8; font-weight:700; }
        .cal-day.cal-selected { background:var(--indigo)!important; color:#fff!important; font-weight:700; border-color:var(--indigo)!important; box-shadow:0 2px 10px rgba(99,102,241,.4); }
        /* Fully-booked day in calendar */
        .cal-day.cal-booked { background:#fee2e2; color:#991b1b; border-color:#fecaca; cursor:not-allowed; font-weight:600; }
        body.dark .cal-day.cal-booked { background:rgba(220,38,38,.15); color:#f87171; border-color:rgba(248,113,113,.2); }
        .cal-footer { display:flex; justify-content:space-between; margin-top:12px; padding-top:12px; }
        body:not(.dark) .cal-footer { border-top:1px solid #f1f5f9; } body.dark .cal-footer { border-top:1px solid rgba(255,255,255,.06); }
        .cal-foot-btn { font-size:.72rem; font-weight:700; cursor:pointer; padding:5px 9px; border-radius:7px; transition:all .15s; }
        body:not(.dark) .cal-foot-btn { color:#94a3b8; } body:not(.dark) .cal-foot-btn:hover { color:var(--indigo); background:var(--indigo-light); } body:not(.dark) .cal-foot-btn.today { color:var(--indigo); }
        body.dark .cal-foot-btn { color:#4f5a72; } body.dark .cal-foot-btn:hover { color:#818cf8; background:rgba(99,102,241,.1); } body.dark .cal-foot-btn.today { color:#818cf8; }
        .dt-drop.tim { width:232px; padding:16px 14px 14px; }
        .tim-title { font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; text-align:center; margin-bottom:12px; }
        body:not(.dark) .tim-title { color:#94a3b8; } body.dark .tim-title { color:#4f5a72; }
        .tim-cols { display:flex; align-items:flex-start; gap:4px; }
        .tim-col { flex:1; display:flex; flex-direction:column; gap:2px; max-height:192px; overflow-y:auto; scrollbar-width:thin; }
        body:not(.dark) .tim-col { scrollbar-color:#e2e8f0 transparent; } body.dark .tim-col { scrollbar-color:rgba(99,102,241,.3) transparent; }
        .tim-col::-webkit-scrollbar { width:3px; }
        body:not(.dark) .tim-col::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }
        body.dark .tim-col::-webkit-scrollbar-thumb { background:rgba(99,102,241,.3); border-radius:4px; }
        .tim-item { padding:7px 6px; border-radius:7px; font-size:.81rem; font-weight:500; text-align:center; cursor:pointer; transition:all .1s; border:1px solid transparent; }
        body:not(.dark) .tim-item { color:#64748b; } body:not(.dark) .tim-item:hover:not(.sel) { background:#f1f5f9; color:#0f172a; }
        body.dark .tim-item { color:#8b95b0; } body.dark .tim-item:hover:not(.sel) { background:rgba(255,255,255,.06); color:#e2e8f0; }
        .tim-item.sel { background:var(--indigo)!important; color:#fff!important; font-weight:700; box-shadow:0 2px 8px rgba(99,102,241,.4); }
        .tim-sep { font-size:1rem; font-weight:700; padding:6px 0; align-self:flex-start; margin-top:4px; }
        body:not(.dark) .tim-sep { color:#cbd5e1; } body.dark .tim-sep { color:#4f5a72; }
        .ampm-col { display:flex; flex-direction:column; gap:5px; padding-top:2px; }
        .ampm-btn { padding:8px 10px; border-radius:8px; font-size:.75rem; font-weight:700; cursor:pointer; text-align:center; transition:all .15s; }
        body:not(.dark) .ampm-btn { border:1px solid #e2e8f0; color:#64748b; background:#f8fafc; } body:not(.dark) .ampm-btn:hover:not(.sel) { color:var(--indigo); border-color:var(--indigo-border); }
        body.dark .ampm-btn { border:1px solid rgba(255,255,255,.07); color:#8b95b0; background:rgba(255,255,255,.04); } body.dark .ampm-btn:hover:not(.sel) { color:#e2e8f0; border-color:rgba(255,255,255,.14); }
        .ampm-btn.sel { background:var(--indigo)!important; color:#fff!important; border-color:var(--indigo)!important; box-shadow:0 2px 8px rgba(99,102,241,.4); }
        .tim-set-btn { width:100%; margin-top:12px; padding:9px; background:var(--indigo); color:#fff; border:none; border-radius:9px; font-size:.8rem; font-weight:700; font-family:var(--font); cursor:pointer; transition:background .15s; }
        .tim-set-btn:hover { background:#4f46e5; }
        .picker-wrap { position:relative; }
        #availabilityMsg { margin-bottom:14px; padding:10px 14px; border-radius:var(--r-sm); font-size:.82rem; font-weight:600; display:flex; align-items:center; gap:8px; }
        #availabilityMsg::before { content:''; width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        #availabilityMsg.available::before   { background:#16a34a; }
        #availabilityMsg.unavailable::before { background:#dc2626; }
        #availabilityMsg.checking::before    { background:#94a3b8; }

        /* ══════════════════════════════ CUSTOM SELECT ══════════════════════════════ */
        .cs-wrap { position:relative; }
        .cs-trigger { display:flex; align-items:center; justify-content:space-between; gap:8px; width:100%; padding:.75rem 1rem; background:var(--input-bg,var(--card,#fff)); border:1px solid var(--border,rgba(99,102,241,.2)); border-radius:var(--r-sm,8px); font-family:var(--font,inherit); font-size:.88rem; font-weight:500; color:var(--text-sub,#94a3b8); cursor:pointer; transition:border .18s,box-shadow .18s; user-select:none; -webkit-user-select:none; outline:none; }
        .cs-trigger.has-value { color:var(--text,#0f172a); }
        .cs-trigger:hover { border-color:#818cf8; }
        .cs-trigger.open { border-color:#818cf8; background:var(--card,#fff); box-shadow:0 0 0 3px rgba(99,102,241,.08); }
        .cs-arrow { width:16px; height:16px; flex-shrink:0; opacity:.4; transition:transform .18s,opacity .18s; }
        .cs-trigger.open .cs-arrow { transform:rotate(180deg); opacity:.75; }
        .cs-drop { position:absolute; bottom:calc(100% + 5px); left:0; right:0; z-index:9999; background:var(--card,#fff); border:1px solid var(--border,rgba(99,102,241,.18)); border-radius:var(--r-md,10px); box-shadow:0 16px 40px rgba(15,23,42,.14); overflow:hidden; display:none; animation:csDropIn .14s cubic-bezier(.4,0,.2,1); }
        @keyframes csDropIn { from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:none} }
        .cs-opt { display:flex; align-items:center; gap:10px; padding:10px 13px; font-size:.87rem; font-weight:500; color:var(--text,#0f172a); cursor:pointer; transition:background .1s; border-bottom:1px solid var(--border-subtle,rgba(99,102,241,.06)); }
        .cs-opt:last-child { border-bottom:none; }
        .cs-opt:hover { background:var(--indigo-light,#eef2ff); color:var(--indigo,#3730a3); }
        .cs-opt.cs-placeholder { color:var(--text-sub,#94a3b8); font-weight:400; font-size:.82rem; }
        .cs-opt.cs-selected { background:rgba(99,102,241,.07); color:var(--indigo,#3730a3); }
        .cs-opt-icon { width:26px; height:26px; border-radius:7px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:11px; }
        .cs-opt-label { flex:1; }
        .cs-check { width:14px; height:14px; flex-shrink:0; color:var(--indigo,#6366f1); opacity:0; transition:opacity .12s; }
        .cs-opt.cs-selected .cs-check { opacity:1; }
        .cs-divider { height:1px; background:var(--border,rgba(99,102,241,.08)); margin:3px 0; }
        body.dark .cs-trigger { background:#101e35; border-color:rgba(99,102,241,.18); color:#4a6fa5; }
        body.dark .cs-trigger.has-value { color:#e2eaf8; }
        body.dark .cs-trigger.open { background:#0b1628; border-color:#818cf8; }
        body.dark .cs-drop { background:#0b1628; border-color:rgba(99,102,241,.22); box-shadow:0 16px 40px rgba(0,0,0,.5); }
        body.dark .cs-opt { color:#e2eaf8; border-color:rgba(99,102,241,.06); }
        body.dark .cs-opt:hover { background:rgba(99,102,241,.12); color:#a5b4fc; }
        body.dark .cs-opt.cs-selected { background:rgba(99,102,241,.15); color:#a5b4fc; }
        body.dark .cs-opt.cs-placeholder { color:#4a6fa5; }
        body.dark .cs-divider { background:rgba(99,102,241,.1); }

        /* ══════════════════════════════ RETRY BUTTON ══════════════════════════════ */
        .avail-retry-btn {
            margin-left: auto;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: .72rem;
            font-weight: 700;
            border: 1px solid currentColor;
            background: transparent;
            cursor: pointer;
            font-family: var(--font);
            opacity: .85;
            transition: opacity .15s;
            color: inherit;
            flex-shrink: 0;
        }
        .avail-retry-btn:hover { opacity: 1; }
    </style>
</head>

<body>
    <?php $page = 'reservation'; include(APPPATH . 'Views/partials/layout.php'); ?>

    <div id="notificationDropdown" class="notif-dd">
        <div style="padding:11px 13px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
            <button onclick="markAllAsRead()" style="font-size:11px;color:var(--indigo);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
        </div>
        <div id="notificationList" style="max-height:280px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
    </div>

    <div id="toastContainer" class="toast-wrap"></div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="modal-back" onclick="handleBackdrop(event)">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px;">
                <div style="width:52px;height:52px;background:#fef3c7;border:2px solid #fde68a;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3 class="modal-title">Confirm Reservation</h3>
                <p style="font-size:.78rem;color:var(--text-sub);margin-top:4px;">Review your booking details</p>
                <p style="font-size:.72rem;color:#d97706;font-weight:700;margin-top:6px;">Your reservation will be pending approval</p>
            </div>
            <div class="modal-summary-box">
                <div class="mrow"><span class="mrow-label">Name</span><span class="mrow-value" id="mName"><?= esc($user['name'] ?? '') ?></span></div>
                <div class="mrow"><span class="mrow-label">Email</span><span class="mrow-value" id="mEmail"><?= esc($user['email'] ?? '') ?></span></div>
                <div class="mrow"><span class="mrow-label">Resource</span><span class="mrow-value" id="mAsset"></span></div>
                <div class="mrow"><span class="mrow-label">Workstation</span><span class="mrow-value" id="mStation"></span></div>
                <div class="mrow"><span class="mrow-label">Date</span><span class="mrow-value" id="mDate"></span></div>
                <div class="mrow"><span class="mrow-label">Time</span><span class="mrow-value" id="mTime"></span></div>
                <div class="mrow"><span class="mrow-label">Purpose</span><span class="mrow-value" id="mPurpose"></span></div>
            </div>
            <div style="background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-md);padding:12px 14px;margin-bottom:16px;text-align:center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="1.8" style="margin-bottom:6px;display:block;margin-left:auto;margin-right:auto;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                <p style="font-size:.75rem;color:var(--indigo);font-weight:500;">You'll receive a notification once your reservation is approved.</p>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeModal()" class="modal-cancel-btn">Cancel</button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" class="btn-primary" style="flex:2;width:auto;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Submit Request
                </button>
            </div>
        </div>
    </div>

    <main class="main-area">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">New Booking</div>
                <div class="page-title">New Reservation</div>
                <div class="page-sub">Book a resource for your upcoming visit.</div>
            </div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash flash-err"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash flash-ok"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (isset($remainingReservations) && $remainingReservations > 0): ?>
            <div class="flash flash-info"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>You have <?= $remainingReservations ?> reservation<?= $remainingReservations != 1 ? 's' : '' ?> remaining this period.</div>
        <?php endif; ?>
        <?php if (isset($isBlocked) && $isBlocked): ?>
            <div class="flash flash-err"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>You are temporarily blocked until <?= date('F j, Y', strtotime($isBlocked['blocked_until'])) ?>.</div>
        <?php endif; ?>

        <div class="form-card">
            <form id="reservationForm" method="POST" action="<?= base_url('reservation/create') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id"       id="finalUserId"      value="<?= $user['id'] ?? '' ?>">
                <input type="hidden" name="visitor_name"  id="finalVisitorName" value="<?= esc($user['name'] ?? '') ?>">
                <input type="hidden" name="user_email"    id="finalUserEmail"   value="<?= esc($user['email'] ?? '') ?>">
                <input type="hidden" name="visitor_type"  id="finalVisitorType" value="User">
                <input type="hidden" name="purpose"       id="finalPurpose">
                <input type="hidden" name="pcs"           id="finalPcs" value="">
                <!-- hidden native selects for form submission -->
                <select name="resource_id" id="nativeResource" style="display:none" required></select>
                <select name="purpose_select" id="nativePurpose" style="display:none"></select>

                <!-- Your Details -->
                <div style="margin-bottom:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                        <div class="section-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                        <div><div class="section-title">Your Details</div><div class="section-sub">Auto-filled from your account</div></div>
                    </div>
                    <div class="grid-2">
                        <div><label class="field-label">Full Name</label><input type="text" value="<?= esc($user['name'] ?? '') ?>" readonly></div>
                        <div><label class="field-label">Email Address</label><input type="email" value="<?= esc($user['email'] ?? '') ?>" readonly></div>
                    </div>
                    <p style="font-size:.72rem;color:var(--indigo);margin-top:8px;display:flex;align-items:center;gap:5px;font-weight:600;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Booking as yourself
                    </p>
                </div>

                <hr class="section-divider">

                <!-- Resource & Schedule -->
                <div style="margin-bottom:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                        <div class="section-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/></svg></div>
                        <div><div class="section-title">Resource & Schedule</div><div class="section-sub">Choose your resource, date and time</div></div>
                    </div>

                    <!-- ── STYLED RESOURCE SELECT ── -->
                    <div style="margin-bottom:16px;">
                        <label class="field-label">Select Resource</label>
                        <div class="cs-wrap" id="resourceWrap">
                            <div class="cs-trigger" id="resourceTrigger">
                                <span class="cs-val-label" id="resourceLabel">— Choose a resource —</span>
                                <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="cs-drop" id="resourceDrop">
                                <div class="cs-opt cs-placeholder" data-value="" data-has-pcs="0">
                                    <span class="cs-opt-label">— Choose a resource —</span>
                                </div>
                                <div class="cs-divider"></div>
                                <?php foreach ($resources as $res):
                                    $rname = esc($res['name']);
                                    $lower = strtolower($res['name']);
                                    $hasPcs = (strpos($lower,'computer')!==false||strpos($lower,'pc')!==false||strpos($lower,'lab')!==false) ? '1' : '0';
                                    $cap = !empty($res['capacity']) ? ' (Capacity: '.$res['capacity'].')' : '';
                                ?>
                                <div class="cs-opt" data-value="<?= $res['id'] ?>" data-name="<?= $rname ?>" data-has-pcs="<?= $hasPcs ?>">
                                    <div class="cs-opt-icon" style="background:rgba(99,102,241,.1)">
                                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><rect x="1" y="2" width="14" height="10" rx="2" stroke="#6366f1" stroke-width="1.3"/><path d="M5 15h6M8 12v3" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/></svg>
                                    </div>
                                    <span class="cs-opt-label"><?= $rname ?><?= $cap ?></span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- PC section -->
                    <div id="pcSection" class="hidden" style="margin-bottom:16px;">
                        <div class="pc-section">
                            <label class="pc-section-lbl"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display:inline;margin-right:5px;"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>Select Workstation(s)</label>
                            <div id="pcGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(70px,1fr));gap:8px;">
                                <?php foreach ($pcs ?? [] as $pc):
                                    $num = esc($pc['pc_number'] ?? $pc['name'] ?? '');
                                    if (!empty($num)): ?>
                                        <button type="button" onclick="togglePc('<?= $num ?>',this)" data-pc="<?= $num ?>" class="pc-btn"><?= $num ?></button>
                                <?php endif; endforeach; ?>
                            </div>
                            <p style="font-size:.68rem;color:var(--indigo);font-weight:600;margin-top:10px;display:flex;align-items:center;gap:4px;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Selected: <span id="pcSelectedLabel" style="font-family:var(--mono)">None</span>
                            </p>
                            <p style="font-size:.68rem;color:#991b1b;font-weight:600;margin-top:4px;display:flex;align-items:center;gap:4px;" id="pcLegend">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                Red = already booked for selected date &amp; time
                            </p>
                        </div>
                    </div>

                    <!-- Date & time -->
                    <div class="grid-3" style="margin-bottom:16px;">
                        <div>
                            <label class="field-label">Date</label>
                            <div class="picker-wrap" id="dateWrap">
                                <div class="dt-trigger" id="dateTrigger">
                                    <span id="dateLabel">Pick a date</span>
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><rect x="1" y="3" width="14" height="12" rx="2.5" stroke="#818cf8" stroke-width="1.4"/><path d="M5 1v3M11 1v3M1 7h14" stroke="#818cf8" stroke-width="1.4" stroke-linecap="round"/></svg>
                                </div>
                                <div class="dt-drop cal" id="calDrop" style="display:none"></div>
                            </div>
                            <input type="hidden" name="reservation_date" id="resDate" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
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

                    <div id="availabilityMsg" class="hidden"></div>

                    <!-- ── STYLED PURPOSE SELECT ── -->
                    <div style="margin-bottom:16px;">
                        <label class="field-label">Purpose of Visit</label>
                        <div class="cs-wrap" id="purposeWrap">
                            <div class="cs-trigger" id="purposeTrigger">
                                <span class="cs-val-label" id="purposeLabel">— Select purpose —</span>
                                <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="cs-drop" id="purposeDrop">
                                <div class="cs-opt cs-placeholder" data-value=""><span class="cs-opt-label">— Select purpose —</span></div>
                                <div class="cs-divider"></div>
                                <?php
                                $purposeIcons = [
                                    'Work'        => ['bg'=>'rgba(99,102,241,.1)',  'svg'=>'<rect x="2" y="5" width="12" height="9" rx="1.5" stroke="#6366f1" stroke-width="1.3"/><path d="M5 5V4a3 3 0 016 0v1" stroke="#6366f1" stroke-width="1.3" stroke-linecap="round"/>'],
                                    'Personal'    => ['bg'=>'rgba(236,72,153,.09)', 'svg'=>'<circle cx="8" cy="5" r="3" stroke="#db2777" stroke-width="1.3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="#db2777" stroke-width="1.3" stroke-linecap="round"/>'],
                                    'Study'       => ['bg'=>'rgba(20,184,166,.1)',  'svg'=>'<path d="M1 4l7-2 7 2-7 2z" stroke="#0d9488" stroke-width="1.3" stroke-linejoin="round"/><path d="M4 6v4c0 1.1 1.8 2 4 2s4-.9 4-2V6" stroke="#0d9488" stroke-width="1.3" stroke-linecap="round"/><path d="M15 4v4" stroke="#0d9488" stroke-width="1.3" stroke-linecap="round"/>'],
                                    'SK Activity' => ['bg'=>'rgba(245,158,11,.1)',  'svg'=>'<polygon points="8,1 10,6 15,6 11,9 13,14 8,11 3,14 5,9 1,6 6,6" stroke="#d97706" stroke-width="1.3" stroke-linejoin="round"/>'],
                                    'Others'      => ['bg'=>'rgba(100,116,139,.1)', 'svg'=>'<circle cx="4" cy="8" r="1.2" fill="#64748b"/><circle cx="8" cy="8" r="1.2" fill="#64748b"/><circle cx="12" cy="8" r="1.2" fill="#64748b"/>'],
                                ];
                                foreach ($purposes ?? array_keys($purposeIcons) as $purpose):
                                    $ic = $purposeIcons[$purpose] ?? $purposeIcons['Others'];
                                ?>
                                <div class="cs-opt" data-value="<?= esc($purpose) ?>">
                                    <div class="cs-opt-icon" style="background:<?= $ic['bg'] ?>">
                                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><?= $ic['svg'] ?></svg>
                                    </div>
                                    <span class="cs-opt-label"><?= esc($purpose) ?></span>
                                    <svg class="cs-check" viewBox="0 0 14 14" fill="none"><polyline points="2 7 6 11 12 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="purposeOtherWrap" class="hidden">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" placeholder="Describe your purpose...">
                    </div>
                </div>

                <div style="padding-top:8px;">
                    <button type="button" onclick="previewReservation()" class="btn-primary" id="previewBtn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Preview & Confirm
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const currentUser = { id: <?= $user['id'] ?? 'null' ?>, name: "<?= esc($user['name'] ?? '', 'js') ?>", email: "<?= esc($user['email'] ?? '', 'js') ?>" };
        const BASE_URL    = "<?= base_url() ?>";
        const CSRF_TOKEN  = "<?= csrf_token() ?>";
        const CSRF_HASH   = "<?= csrf_hash() ?>";

        let selectedPcs = [], selectedResource = null;

        /* ─────────────── Availability state ─────────────── */
        let _isAvailable      = false;  // true only when server confirms available
        let _availCheckCtrl   = null;   // AbortController for in-flight request
        let _availCheckTimer  = null;   // hard-timeout id
        let _availDebounce    = null;   // debounce timer id  ← FIX #1

        /* ─────────────── Notifications ─────────────── */
        let notifications = [<?php if (!empty($recentApprovals)): ?><?php foreach ($recentApprovals as $approval): ?>{id:<?= $approval['id'] ?>,title:'Reservation Approved!',message:'Your reservation for <?= esc($approval['resource_name']) ?> on <?= date('M j, Y', strtotime($approval['reservation_date'])) ?> has been approved.',time:'<?= $approval['approved_at'] ?? date('Y-m-d H:i:s') ?>',read:false},<?php endforeach; ?><?php endif; ?>];
        let unreadCount = notifications.filter(n=>!n.read).length, checkInterval, lastCheckTime = new Date().toISOString();

        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.classList.remove('dark-pre');
            if ('Notification' in window) Notification.requestPermission();
            renderNotifications(); updateBadge();
            checkInterval = setInterval(checkForNewApprovals, 30000);
            document.addEventListener('visibilitychange', function() { if (!document.hidden) checkForNewApprovals(); });
            notifications.forEach(n => { if (!n.read) showToast(n); });
            initCustomSelects();
        });

        /* ══════════════════════════════════════════════════
           DEBOUNCED TRIGGER  ← FIX #1
           Waits 600 ms after the last field change before
           actually firing checkAvailability(). This prevents
           dozens of overlapping requests when users click
           through date/time pickers quickly.
        ══════════════════════════════════════════════════ */
        function triggerAvailCheck() {
            clearTimeout(_availDebounce);
            _availDebounce = setTimeout(checkAvailability, 600);
        }

        /* ══════════════════════════════════════════════════
           AVAILABILITY CHECK
           Timeout increased 8 s → 15 s  ← FIX #2
           On timeout: show retry button instead of hard fail ← FIX #3
        ══════════════════════════════════════════════════ */
        function checkAvailability() {
            const rid  = document.getElementById('nativeResource').value;
            const date = document.getElementById('resDate').value;
            const st   = document.getElementById('startTime').value;
            const et   = document.getElementById('endTime').value;
            const m    = document.getElementById('availabilityMsg');

            // Reset state whenever inputs change
            _isAvailable = false;
            setPreviewBtnState();

            if (!rid || !date || !st || !et) {
                m.className = 'hidden';
                return;
            }

            // Validate end time is after start time
            if (st && et && st >= et) {
                m.className = '';
                m.classList.add('unavailable');
                m.innerHTML = 'End time must be after start time.';
                return;
            }

            // Cancel any in-flight request
            if (_availCheckCtrl) _availCheckCtrl.abort();
            clearTimeout(_availCheckTimer);

            m.className = '';
            m.classList.add('checking');
            m.innerHTML = 'Checking availability…';

            _availCheckCtrl = new AbortController();

            // ── FIX #2: 15-second hard timeout (was 8 s) ──
            _availCheckTimer = setTimeout(() => {
                if (_availCheckCtrl) {
                    _availCheckCtrl.abort();
                    // ── FIX #3: friendly retry instead of silent error ──
                    _availCheckCtrl = null;
                    _isAvailable = false;
                    m.className = '';
                    m.classList.add('checking');
                    m.innerHTML = 'Check is taking longer than expected. <button class="avail-retry-btn" onclick="checkAvailability()">Retry</button>';
                    setPreviewBtnState();
                }
            }, 15000);

            fetch(BASE_URL + 'reservation/check-availability', {
                method  : 'POST',
                headers : { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body    : new URLSearchParams({ resource_id: rid, date, start_time: st, end_time: et, [CSRF_TOKEN]: CSRF_HASH }),
                signal  : _availCheckCtrl.signal
            })
            .then(r => {
                if (!r.ok) throw new Error('Server error ' + r.status);
                return r.json();
            })
            .then(data => {
                clearTimeout(_availCheckTimer);
                _availCheckCtrl = null;

                m.className = '';
                _isAvailable = !!data.available;
                m.classList.add(_isAvailable ? 'available' : 'unavailable');
                m.innerHTML = data.message || (_isAvailable ? 'This slot is available.' : 'This slot is already booked.');

                // Mark unavailable PCs if server returns them
                if (data.booked_pcs && Array.isArray(data.booked_pcs)) {
                    markUnavailablePcs(data.booked_pcs);
                }

                setPreviewBtnState();
            })
            .catch(err => {
                clearTimeout(_availCheckTimer);
                _availCheckCtrl = null;
                _isAvailable = false;

                // Only handle non-abort errors here; abort is handled in the timer callback
                if (err.name !== 'AbortError') {
                    m.className = '';
                    m.classList.add('unavailable');
                    m.innerHTML = 'Could not check availability. <button class="avail-retry-btn" onclick="checkAvailability()">Retry</button>';
                    setPreviewBtnState();
                }
            });
        }

        /* Dim the Preview button when slot is taken */
        function setPreviewBtnState() {
            const rid  = document.getElementById('nativeResource').value;
            const date = document.getElementById('resDate').value;
            const st   = document.getElementById('startTime').value;
            const et   = document.getElementById('endTime').value;
            const btn  = document.getElementById('previewBtn');

            // Only lock it once all fields are filled and we know it's unavailable
            const allFilled = rid && date && st && et;
            if (allFilled && !_isAvailable) {
                btn.style.opacity = '0.55';
                btn.title = 'This slot is not available. Please choose a different time.';
            } else {
                btn.style.opacity = '1';
                btn.title = '';
            }
        }

        /* Grey out already-booked PCs */
        function markUnavailablePcs(bookedPcs) {
            document.querySelectorAll('.pc-btn').forEach(btn => {
                const pcNum = btn.dataset.pc;
                if (bookedPcs.includes(pcNum)) {
                    btn.classList.add('unavailable-pc');
                    btn.classList.remove('selected-pc');
                    btn.disabled = true;
                    btn.title = pcNum + ' is already booked';
                    // Remove from selected list if it was picked
                    const idx = selectedPcs.indexOf(pcNum);
                    if (idx !== -1) { selectedPcs.splice(idx, 1); updatePcHidden(); }
                } else {
                    btn.classList.remove('unavailable-pc');
                    btn.disabled = false;
                    btn.title = '';
                }
            });
        }

        /* Reset PC buttons when resource or date changes */
        function resetPcButtons() {
            document.querySelectorAll('.pc-btn').forEach(btn => {
                btn.classList.remove('unavailable-pc', 'selected-pc');
                btn.disabled = false;
                btn.title = '';
            });
        }

        /* ══════════════════════════════════════════════════
           CUSTOM SELECTS
        ══════════════════════════════════════════════════ */
        function initCustomSelects() {
            initCS('resourceWrap', 'resourceDrop', 'resourceLabel', function(val, opt) {
                const hasPcs = opt.dataset.hasPcs === '1';
                document.getElementById('nativeResource').innerHTML = `<option value="${val}" selected></option>`;
                document.getElementById('pcSection').classList.toggle('hidden', !hasPcs);
                selectedPcs = []; updatePcHidden(); resetPcButtons();
                selectedResource = { id: val, name: opt.querySelector('.cs-opt-label').textContent.trim(), hasPcs };
                _isAvailable = false;
                document.getElementById('availabilityMsg').className = 'hidden';
                setPreviewBtnState();
                triggerAvailCheck(); // ← use debounced trigger
            });
            initCS('purposeWrap', 'purposeDrop', 'purposeLabel', function(val) {
                document.getElementById('purposeOtherWrap').classList.toggle('hidden', val !== 'Others');
                if (val !== 'Others') document.getElementById('purposeOther').value = '';
            });
        }

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
                drop.style.display = 'block'; trigger.classList.add('open');
                const rect = drop.getBoundingClientRect(), vw = window.innerWidth;
                if (rect.right > vw - 8) drop.style.left = (-(rect.right - vw + 8)) + 'px';
            });

            opts.forEach(opt => {
                opt.addEventListener('click', e => {
                    e.stopPropagation();
                    const val  = opt.dataset.value;
                    const text = opt.querySelector('.cs-opt-label')?.textContent.trim() || '';
                    opts.forEach(o => o.classList.remove('cs-selected'));
                    if (val !== '') { opt.classList.add('cs-selected'); label.textContent = text; trigger.classList.add('has-value'); }
                    else { label.textContent = text; trigger.classList.remove('has-value'); }
                    closeAllCS();
                    if (onChange) onChange(val, opt);
                });
            });
        }

        function togglePc(num, btn) {
            if (btn.disabled) return; // block unavailable PCs
            const i = selectedPcs.indexOf(num);
            if (i === -1) { selectedPcs.push(num); btn.classList.add('selected-pc'); }
            else          { selectedPcs.splice(i, 1); btn.classList.remove('selected-pc'); }
            updatePcHidden();
        }
        function updatePcHidden() {
            document.getElementById('finalPcs').value = selectedPcs.join(', ');
            document.getElementById('pcSelectedLabel').textContent = selectedPcs.length ? selectedPcs.join(', ') : 'None';
        }

        /* ══════════════════════════════════════════════════
           PREVIEW — blocks if unavailable
        ══════════════════════════════════════════════════ */
        function getSelectedPurpose() {
            const opt = document.querySelector('#purposeDrop .cs-opt.cs-selected');
            return opt ? opt.dataset.value : '';
        }
        function getSelectedResource() {
            const opt = document.querySelector('#resourceDrop .cs-opt.cs-selected');
            return opt ? { id: opt.dataset.value, name: opt.querySelector('.cs-opt-label')?.textContent.trim(), hasPcs: opt.dataset.hasPcs === '1' } : null;
        }

        function previewReservation() {
            const res   = getSelectedResource();
            const pv    = getSelectedPurpose();
            const po    = document.getElementById('purposeOther').value.trim();
            const pf    = pv === 'Others' && po ? `Others - ${po}` : pv;
            const hasPc = !document.getElementById('pcSection').classList.contains('hidden');
            const rid   = document.getElementById('nativeResource').value;
            const date  = document.getElementById('resDate').value;
            const st    = document.getElementById('startTime').value;
            const et    = document.getElementById('endTime').value;

            // ── Field validation ──
            if (!res || !res.id)              return showFormError('Please select a resource.');
            if (hasPc && selectedPcs.length === 0) return showFormError('Please select at least one workstation.');
            if (!date)                        return showFormError('Please select a date.');
            if (!st)                          return showFormError('Please select a start time.');
            if (!et)                          return showFormError('Please select an end time.');
            if (st >= et)                     return showFormError('End time must be after start time.');
            if (!pv)                          return showFormError('Please select a purpose.');

            // ── Availability gate ──
            if (!_isAvailable) {
                const m = document.getElementById('availabilityMsg');
                m.classList.remove('hidden');
                // If still checking, tell the user
                if (m.classList.contains('checking')) {
                    return showFormError('Please wait — availability check is in progress.');
                }
                return showFormError('This slot is already booked. Please choose a different date or time.');
            }

            // ── Populate modal ──
            document.getElementById('finalPurpose').value = pf;
            document.getElementById('mAsset').textContent    = res.name;
            document.getElementById('mStation').textContent  = selectedPcs.length ? selectedPcs.join(', ') : 'None';
            document.getElementById('mDate').textContent     = document.getElementById('dateLabel').textContent;
            document.getElementById('mTime').textContent     = `${document.getElementById('startLabel').textContent} – ${document.getElementById('endLabel').textContent}`;
            document.getElementById('mPurpose').textContent  = pf;
            openModal();
        }

        function showFormError(msg) {
            const m = document.getElementById('availabilityMsg');
            m.className = '';
            m.classList.add('unavailable');
            m.textContent = msg;
            m.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        /* ══════════════════════════════════════════════════
           SUBMIT
        ══════════════════════════════════════════════════ */
        function submitReservation() {
            if (!_isAvailable) { closeModal(); return showFormError('Slot no longer available. Please recheck.'); }

            const res = getSelectedResource();
            if (res) {
                let sel = document.getElementById('nativeResource');
                if (!sel) {
                    sel = document.createElement('select');
                    sel.name = 'resource_id';
                    sel.style.display = 'none';
                    document.getElementById('reservationForm').appendChild(sel);
                }
                sel.innerHTML = `<option value="${res.id}" selected></option>`;
            }
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10" opacity=".25"/><path d="M12 2a10 10 0 010 20" stroke-dasharray="31.4" stroke-dashoffset="0" opacity=".75"/></svg> Submitting…';
            document.getElementById('reservationForm').submit();
        }

        function openModal()  { document.getElementById('confirmModal').classList.add('show'); document.body.style.overflow = 'hidden'; }
        function closeModal() {
            document.getElementById('confirmModal').classList.remove('show');
            document.body.style.overflow = '';
            const btn = document.getElementById('confirmBtn');
            btn.disabled = false;
            btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Submit Request';
        }
        function handleBackdrop(e) { if (e.target === document.getElementById('confirmModal')) closeModal(); }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
        window.addEventListener('beforeunload', () => { if (checkInterval) clearInterval(checkInterval); });

        /* ══════════════════════════════════════════════════
           NOTIFICATIONS
        ══════════════════════════════════════════════════ */
        function checkForNewApprovals() {
            fetch(BASE_URL + 'reservation/check-new-approvals', {
                method  : 'POST',
                headers : { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body    : JSON.stringify({ user_id: currentUser.id, last_check: lastCheckTime })
            })
            .then(r => r.json())
            .then(data => {
                if (data.new_approvals?.length) {
                    data.new_approvals.forEach(a => {
                        const n = { id: a.id, title: 'Reservation Approved!', message: `Your reservation for ${a.resource_name} on ${new Date(a.date).toLocaleDateString()} has been approved.`, time: new Date().toISOString(), read: false };
                        notifications.unshift(n); unreadCount++;
                        updateBadge(); renderNotifications(); showPush(n); showToast(n);
                    });
                    lastCheckTime = new Date().toISOString();
                }
            })
            .catch(e => console.error(e));
        }
        function showPush(n) { if ('Notification' in window && Notification.permission === 'granted') new Notification(n.title, { body: n.message, icon: '/favicon.ico' }); }
        function showToast(n) {
            const c = document.getElementById('toastContainer'), tid = 't' + Date.now(), t = document.createElement('div');
            t.id = tid; t.className = 'toast';
            t.innerHTML = `<div style="width:28px;height:28px;background:rgba(99,102,241,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.78rem;color:white;">${n.title}</p><p style="font-size:.68rem;color:rgba(255,255,255,.6);margin-top:2px;">${n.message}</p></div><button onclick="document.getElementById('${tid}').remove()" style="background:rgba(255,255,255,.08);border:none;border-radius:6px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;color:rgba(255,255,255,.6);">×</button>`;
            c.appendChild(t);
            setTimeout(() => { const el = document.getElementById(tid); if (el) el.remove(); }, 5000);
        }
        function toggleNotifications() { document.getElementById('notificationDropdown').classList.toggle('show'); }
        function markAllAsRead()        { notifications.forEach(n => n.read = true); unreadCount = 0; updateBadge(); renderNotifications(); }
        function markAsRead(id)         { const n = notifications.find(n => n.id === id); if (n && !n.read) { n.read = true; unreadCount = Math.max(0, unreadCount - 1); updateBadge(); renderNotifications(); } }
        function updateBadge()          { const b = document.getElementById('notificationBadge'); if (b) { if (unreadCount > 0) { b.style.display = 'block'; b.textContent = unreadCount > 9 ? '9+' : unreadCount; } else b.style.display = 'none'; } }
        function renderNotifications() {
            const l = document.getElementById('notificationList');
            if (!notifications.length) { l.innerHTML = '<div style="text-align:center;padding:24px;font-size:.8rem;color:var(--text-sub);">All caught up!</div>'; return; }
            l.innerHTML = notifications.sort((a, b) => new Date(b.time) - new Date(a.time)).map(n => `<div class="notif-item ${!n.read ? 'unread' : ''}" onclick="markAsRead(${n.id})"><div style="display:flex;align-items:flex-start;gap:9px;"><div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div><div style="flex:1;min-width:0;"><p style="font-weight:700;font-size:.8rem;color:var(--text);">${n.title}</p><p style="font-size:.7rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.message}</p><p style="font-size:.62rem;color:var(--text-sub);margin-top:2px;">${timeAgo(n.time)}</p></div>${!n.read ? '<span style="width:6px;height:6px;background:var(--indigo);border-radius:50%;flex-shrink:0;margin-top:3px;"></span>' : ''}</div></div>`).join('');
        }
        const timeAgo = t => { const s = Math.floor((Date.now() - new Date(t)) / 1000); if (s < 60) return 'Just now'; if (s < 3600) return `${Math.floor(s / 60)}m ago`; if (s < 86400) return `${Math.floor(s / 3600)}h ago`; return `${Math.floor(s / 86400)}d ago`; };
        document.addEventListener('click', e => { const dd = document.getElementById('notificationDropdown'); if (!dd.contains(e.target)) dd.classList.remove('show'); });
    </script>

    <!-- ══════════════════════════════ DATE / TIME PICKER JS ══════════════════════════════ -->
    <script>
    (function(){
        'use strict';
        const TODAY = new Date();
        let calView = { y: TODAY.getFullYear(), m: TODAY.getMonth() }, selDate = null;
        let tState  = { start: { h: 9, min: 0, ampm: 'am' }, end: { h: 5, min: 0, ampm: 'pm' } }, activeDrop = null;
        const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const DOWS   = ['Su','Mo','Tu','We','Th','Fr','Sa'];
        const MINS   = [0,5,10,15,20,25,30,35,40,45,50,55];
        function $(id) { return document.getElementById(id); }

        function closeAll() {
            ['calDrop','startDrop','endDrop'].forEach(id => { const el = $(id); if (el) el.style.display = 'none'; });
            ['dateTrigger','startTrigger','endTrigger'].forEach(id => { const el = $(id); if (el) el.classList.remove('open'); });
            activeDrop = null;
        }
        function toggle(dropId, triggerId) {
            if (activeDrop === dropId) { closeAll(); return; }
            closeAll(); activeDrop = dropId;
            const drop = $(dropId);
            drop.style.left = '0'; drop.style.right = '';
            drop.style.display = 'block'; $(triggerId).classList.add('open');
            const rect = drop.getBoundingClientRect(), vw = window.innerWidth;
            if (rect.right > vw - 8) { const ov = rect.right - (vw - 8); drop.style.left = Math.max(-rect.left + 8, -ov) + 'px'; }
        }
        document.addEventListener('click', e => { if (!e.target.closest('.picker-wrap')) closeAll(); });

        /* ── Calendar ── */
        function renderCal() {
            const { y, m } = calView;
            const firstDow = new Date(y, m, 1).getDay(), daysInM = new Date(y, m + 1, 0).getDate(), prevTotal = new Date(y, m, 0).getDate();
            let html = `<div class="cal-head"><div class="cal-nav-btn" id="_calPrev">&#8249;</div><div class="cal-month-label">${MONTHS[m]} ${y}</div><div class="cal-nav-btn" id="_calNext">&#8250;</div></div><div class="cal-grid">${DOWS.map(d => `<div class="cal-dow">${d}</div>`).join('')}`;
            for (let i = 0; i < firstDow; i++) html += `<div class="cal-day cal-other">${prevTotal - firstDow + 1 + i}</div>`;
            for (let d = 1; d <= daysInM; d++) {
                const isToday    = d === TODAY.getDate() && m === TODAY.getMonth() && y === TODAY.getFullYear();
                const isSel      = selDate && selDate.d === d && selDate.m === m && selDate.y === y;
                const isPast     = new Date(y, m, d) < new Date(TODAY.getFullYear(), TODAY.getMonth(), TODAY.getDate());
                html += `<div class="${['cal-day', isToday && !isSel ? 'cal-today' : '', isSel ? 'cal-selected' : '', isPast ? 'cal-other' : ''].filter(Boolean).join(' ')}" data-d="${d}">${d}</div>`;
            }
            const trail = (7 - (firstDow + daysInM) % 7) % 7;
            for (let i = 1; i <= trail; i++) html += `<div class="cal-day cal-other">${i}</div>`;
            html += `</div><div class="cal-footer"><span class="cal-foot-btn" id="_calClear">Clear</span><span class="cal-foot-btn today" id="_calToday">Today</span></div>`;
            $('calDrop').innerHTML = html;
            $('_calPrev').addEventListener('click', e => { e.stopPropagation(); moveMonth(-1); });
            $('_calNext').addEventListener('click', e => { e.stopPropagation(); moveMonth(1); });
            $('_calClear').addEventListener('click', e => { e.stopPropagation(); clearDate(); });
            $('_calToday').addEventListener('click', e => { e.stopPropagation(); gotoToday(); });
            $('calDrop').querySelectorAll('.cal-day[data-d]:not(.cal-other)').forEach(el => el.addEventListener('click', e => { e.stopPropagation(); pickDay(+el.dataset.d); }));
        }
        function moveMonth(dir) {
            calView.m += dir;
            if (calView.m > 11) { calView.m = 0; calView.y++; }
            if (calView.m < 0)  { calView.m = 11; calView.y--; }
            renderCal();
        }
        function pickDay(d) {
            selDate = { d, m: calView.m, y: calView.y };
            const dt  = new Date(calView.y, calView.m, d);
            const iso = `${calView.y}-${String(calView.m + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            $('resDate').value = iso;
            $('dateLabel').textContent = dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            $('dateTrigger').classList.add('has-value');
            renderCal();
            setTimeout(closeAll, 180);
            // Reset PC unavailable state when date changes, then recheck via debounce
            resetPcButtons();
            if ($('startTime').value && $('endTime').value) triggerAvailCheck(); // ← debounced
        }
        function clearDate() {
            selDate = null; $('resDate').value = ''; $('dateLabel').textContent = 'Pick a date';
            $('dateTrigger').classList.remove('has-value');
            _isAvailable = false; setPreviewBtnState();
            document.getElementById('availabilityMsg').className = 'hidden';
            resetPcButtons();
            renderCal();
        }
        function gotoToday() { calView = { y: TODAY.getFullYear(), m: TODAY.getMonth() }; pickDay(TODAY.getDate()); }

        /* ── Time picker ── */
        function renderTime(which) {
            const dropId = which === 'start' ? 'startDrop' : 'endDrop';
            const st     = tState[which];
            const hItems = Array.from({ length: 12 }, (_, i) => i + 1).map(h => `<div class="tim-item${st.h === h ? ' sel' : ''}" data-part="h" data-val="${h}">${String(h).padStart(2,'0')}</div>`).join('');
            const mItems = MINS.map(mn => `<div class="tim-item${st.min === mn ? ' sel' : ''}" data-part="min" data-val="${mn}">${String(mn).padStart(2,'0')}</div>`).join('');
            $(dropId).innerHTML = `<div class="tim-title">Select Time</div><div class="tim-cols"><div class="tim-col" id="_tc_h_${which}">${hItems}</div><div class="tim-sep">:</div><div class="tim-col" id="_tc_m_${which}">${mItems}</div><div class="ampm-col"><div class="ampm-btn${st.ampm === 'am' ? ' sel' : ''}" data-ampm="am">AM</div><div class="ampm-btn${st.ampm === 'pm' ? ' sel' : ''}" data-ampm="pm">PM</div></div></div><button class="tim-set-btn" id="_timSet_${which}">Set Time</button>`;
            setTimeout(() => {
                const sH = $(dropId).querySelector('#_tc_h_' + which + ' .sel');
                const sM = $(dropId).querySelector('#_tc_m_' + which + ' .sel');
                if (sH) sH.scrollIntoView({ block: 'center', behavior: 'instant' });
                if (sM) sM.scrollIntoView({ block: 'center', behavior: 'instant' });
            }, 0);
            $(dropId).querySelectorAll('.tim-item').forEach(el => el.addEventListener('click', e => { e.stopPropagation(); tState[which][el.dataset.part] = +el.dataset.val; renderTime(which); }));
            $(dropId).querySelectorAll('.ampm-btn').forEach(el => el.addEventListener('click', e => { e.stopPropagation(); tState[which].ampm = el.dataset.ampm; renderTime(which); }));
            $(`_timSet_${which}`).addEventListener('click', e => { e.stopPropagation(); applyTime(which); });
        }
        function applyTime(which) {
            const st     = tState[which];
            const label  = `${String(st.h).padStart(2,'0')}:${String(st.min).padStart(2,'0')} ${st.ampm.toUpperCase()}`;
            let h24 = st.h;
            if (st.ampm === 'am' && st.h === 12) h24 = 0;
            if (st.ampm === 'pm' && st.h !== 12) h24 = st.h + 12;
            const iso24    = `${String(h24).padStart(2,'0')}:${String(st.min).padStart(2,'0')}`;
            const labelId  = which === 'start' ? 'startLabel'   : 'endLabel';
            const inputId  = which === 'start' ? 'startTime'     : 'endTime';
            const triggerId = which === 'start' ? 'startTrigger' : 'endTrigger';
            $(labelId).textContent = label;
            $(inputId).value       = iso24;
            $(triggerId).classList.add('has-value');
            closeAll();
            // Reset PC states when time changes, then recheck via debounce
            resetPcButtons();
            const date = $('resDate').value, rid = document.getElementById('nativeResource').value;
            if (rid && date && $('startTime').value && $('endTime').value) triggerAvailCheck(); // ← debounced
        }

        $('dateTrigger').addEventListener('click', e => { e.stopPropagation(); toggle('calDrop','dateTrigger'); if (activeDrop === 'calDrop') renderCal(); });
        $('startTrigger').addEventListener('click', e => { e.stopPropagation(); toggle('startDrop','startTrigger'); if (activeDrop === 'startDrop') renderTime('start'); });
        $('endTrigger').addEventListener('click', e => { e.stopPropagation(); toggle('endDrop','endTrigger'); if (activeDrop === 'endDrop') renderTime('end'); });

        // Auto-set today's date on load
        (function() {
            const t = new Date();
            $('dateLabel').textContent = t.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            $('dateTrigger').classList.add('has-value');
            selDate = { d: t.getDate(), m: t.getMonth(), y: t.getFullYear() };
        })();
    })();
    </script>
</body>
</html>