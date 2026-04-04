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
    <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name"  content="<?= csrf_token() ?>">
    <script>(function(){if(localStorage.getItem('admin_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        *{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
        :root{
            --bg:#f0f2f9;--card:#fff;--brd:rgba(99,102,241,.1);--brd2:rgba(99,102,241,.07);
            --text:#0f172a;--muted:#94a3b8;--muted2:#64748b;
            --indigo:#3730a3;--indigo-mid:#4338ca;--indigo-light:#eef2ff;--indigo-brd:#c7d2fe;
            --green:#16a34a;--green-bg:#dcfce7;--amber:#d97706;--amber-bg:#fef3c7;
            --purple:#7c3aed;--purple-bg:#ede9fe;--red:#dc2626;--red-bg:#fee2e2;
            --orange:#c2410c;--orange-bg:#fff7ed;
            --sidebar-w:268px;--r:20px;--r-md:14px;--r-sm:10px;
            --font:'Plus Jakarta Sans',system-ui,sans-serif;
            --shadow-sm:0 1px 4px rgba(15,23,42,.07),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.09),0 2px 4px rgba(15,23,42,.04);
            --mob-nav-h:60px;
        }
        html.dark-pre body{background:#060e1e}
        body{font-family:var(--font);background:var(--bg);color:var(--text);display:flex;min-height:100vh;-webkit-font-smoothing:antialiased}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;padding:18px 14px;height:100vh;position:sticky;top:0;overflow-y:auto}
        .sidebar::-webkit-scrollbar{display:none}
        .sidebar-inner{background:var(--card);border-radius:24px;border:1px solid var(--brd);height:100%;display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-md)}
        .sb-top{padding:20px 18px 16px;border-bottom:1px solid var(--brd2)}
        .brand-tag{font-size:10px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:var(--muted);margin-bottom:4px}
        .brand-name{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.03em}
        .brand-name em{font-style:normal;color:var(--indigo-mid)}
        .user-card{margin:12px 12px 0;background:var(--indigo-light);border-radius:var(--r-md);padding:12px 14px;border:1px solid var(--indigo-brd);display:flex;align-items:center;gap:9px}
        .user-av{width:34px;height:34px;border-radius:50%;background:var(--indigo);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0;box-shadow:0 2px 8px rgba(55,48,163,.3)}
        .user-name{font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role{font-size:11px;color:#6366f1;font-weight:500;margin-top:1px}
        .sb-nav{flex:1;overflow-y:auto;overflow-x:hidden;padding:10px;display:flex;flex-direction:column;gap:3px}
        .sb-nav::-webkit-scrollbar{display:none}
        .nav-sec-lbl{font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);padding:10px 10px 5px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:13px;font-weight:600;color:var(--muted2);text-decoration:none;transition:all .18s}
        .nav-link:hover{background:var(--indigo-light);color:var(--indigo)}
        .nav-link.active{background:var(--indigo);color:#fff;box-shadow:0 4px 14px rgba(55,48,163,.32)}
        .nav-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;background:#f1f5f9}
        .nav-link:not(.active):hover .nav-icon{background:var(--indigo-light)}
        .nav-link.active .nav-icon{background:rgba(255,255,255,.15)}
        .nav-badge{margin-left:auto;background:rgba(245,158,11,.18);color:#d97706;font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px}
        .nav-link.active .nav-badge{background:rgba(255,255,255,.22);color:#fff}
        .sb-footer{padding:10px 10px 12px;border-top:1px solid var(--brd2)}
        .logout-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;transition:all .18s}
        .logout-link:hover{background:var(--red-bg);color:var(--red)}
        .logout-link:hover .nav-icon{background:var(--red-bg)}

        /* ── Mobile Nav ── */
        .mobile-nav-pill{display:none;position:fixed;bottom:0;left:0;right:0;width:100%;background:var(--card);border-top:1px solid var(--brd);height:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));z-index:200;box-shadow:0 -4px 20px rgba(55,48,163,.1)}
        .mob-scroll{display:flex;justify-content:space-evenly;align-items:center;height:var(--mob-nav-h);overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:0 4px}
        .mob-scroll::-webkit-scrollbar{display:none}
        .mob-item{flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:6px 10px;border-radius:12px;cursor:pointer;text-decoration:none;color:var(--muted2);font-size:10px;font-weight:700;gap:2px;transition:all .15s}
        .mob-item:hover,.mob-item.active{background:var(--indigo-light);color:var(--indigo)}
        .mob-item.active::after{content:'';position:absolute;bottom:4px;left:50%;transform:translateX(-50%);width:4px;height:4px;background:var(--indigo);border-radius:50%}
        .mob-item{position:relative}
        @media(max-width:1023px){.sidebar{display:none!important}.mobile-nav-pill{display:flex!important}.main{padding-bottom:calc(var(--mob-nav-h) + 16px)!important}}
        @media(min-width:1024px){.mobile-nav-pill{display:none!important}}

        /* ── Main ── */
        .main{flex:1;min-width:0;padding:24px 28px 48px;overflow-x:hidden}
        @media(max-width:639px){.main{padding:14px 12px 0}}

        /* ── Topbar ── */
        .topbar{display:flex;flex-direction:column;gap:3px;margin-bottom:24px}
        .topbar-row{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
        .eyebrow{font-size:10px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--muted);margin-bottom:3px}
        .page-title{font-size:26px;font-weight:800;color:var(--text);letter-spacing:-.04em}
        .page-sub{font-size:12px;color:var(--muted);margin-top:3px}
        .topbar-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
        .icon-btn{width:42px;height:42px;background:var(--card);border:1px solid var(--brd);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:var(--muted2);cursor:pointer;transition:all .15s;font-size:15px;border:none}
        .icon-btn:hover{background:var(--indigo-light);color:var(--indigo)}
        .btn-export{display:flex;align-items:center;gap:7px;padding:9px 18px;background:var(--indigo);color:#fff;border-radius:var(--r-sm);font-size:13px;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all .15s;box-shadow:0 4px 12px rgba(55,48,163,.28)}
        .btn-export:hover{background:#312e81}

        /* ── Stat cards ── */
        .stats-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;margin-bottom:18px}
        @media(max-width:900px){.stats-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:600px){.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        .stat-card{background:var(--card);border:1px solid var(--brd);border-left-width:4px;border-radius:var(--r);padding:14px 16px;cursor:pointer;transition:transform .15s,box-shadow .15s}
        .stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md)}
        .stat-card.ring{box-shadow:0 0 0 2px var(--indigo)}
        .stat-lbl{font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:4px}
        .stat-num{font-size:1.8rem;font-weight:800;line-height:1;letter-spacing:-.04em}

        /* ── Filter bar ── */
        .filter-bar{background:var(--card);border:1px solid var(--brd);border-radius:24px;padding:16px 18px;margin-bottom:14px;box-shadow:var(--shadow-sm)}
        .field{background:var(--card);border:1px solid var(--brd);border-radius:12px;padding:9px 14px 9px 36px;font-size:13px;font-family:var(--font);color:var(--text);width:100%;transition:all .2s;outline:none}
        .field:focus{border-color:var(--indigo-mid);box-shadow:0 0 0 3px rgba(67,56,202,.1)}
        .field-plain{background:var(--card);border:1px solid var(--brd);border-radius:12px;padding:9px 14px 9px 36px;font-size:13px;font-family:var(--font);color:var(--text);width:100%;transition:all .2s;outline:none}
        .field-plain:focus{border-color:var(--indigo-mid);box-shadow:0 0 0 3px rgba(67,56,202,.1)}
        .qtab{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid var(--brd);color:var(--muted2);background:var(--card);transition:all .15s;white-space:nowrap;font-family:var(--font)}
        .qtab:hover{border-color:var(--indigo);color:var(--indigo)}
        .qtab.active{background:var(--indigo);color:#fff;border-color:var(--indigo);box-shadow:0 4px 12px rgba(55,48,163,.3)}
        .btn-reset{padding:9px 14px;background:#f1f5f9;color:var(--muted2);border-radius:12px;font-size:13px;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all .15s;display:flex;align-items:center;gap:6px;flex-shrink:0}
        .btn-reset:hover{background:var(--indigo-light);color:var(--indigo)}

        /* ── Table ── */
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
        .table-wrap::-webkit-scrollbar{height:4px}
        .table-wrap::-webkit-scrollbar-thumb{background:var(--brd);border-radius:4px}
        table{width:100%;border-collapse:collapse;min-width:700px}
        thead th{background:#f8fafc;font-weight:800;text-transform:uppercase;font-size:10px;letter-spacing:.12em;color:var(--muted);padding:12px 14px;border-bottom:1px solid var(--brd);white-space:nowrap;cursor:pointer;user-select:none}
        thead th:hover{color:var(--indigo)}
        thead th .sort-icon{opacity:.35;margin-left:4px;font-size:9px}
        thead th.sorted .sort-icon{opacity:1;color:var(--indigo)}
        td{padding:12px 14px;border-bottom:1px solid var(--brd2);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr{transition:background .12s;cursor:pointer}
        tbody tr:hover td{background:var(--indigo-light)}

        /* ── Badges ── */
        .badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:10px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
        .badge-pending{background:var(--amber-bg);color:var(--amber)}
        .badge-approved{background:var(--green-bg);color:var(--green)}
        .badge-declined,.badge-canceled{background:var(--red-bg);color:var(--red)}
        .badge-claimed{background:var(--purple-bg);color:var(--purple)}
        .badge-expired{background:#f1f5f9;color:var(--muted2)}
        .badge-unclaimed{background:var(--orange-bg);color:var(--orange);border:1px dashed #fdba74}

        /* ── Action buttons ── */
        .btn-approve-sm{background:var(--green-bg);color:var(--green);border:1px solid #86efac;border-radius:9px;padding:6px 10px;font-size:12px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .15s;display:inline-flex;align-items:center;gap:4px}
        .btn-approve-sm:hover{background:#bbf7d0}
        .btn-decline-sm{background:var(--red-bg);color:var(--red);border:1px solid #fca5a5;border-radius:9px;padding:6px 8px;font-size:12px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .15s;display:inline-flex;align-items:center;gap:4px}
        .btn-decline-sm:hover{background:#fecaca}
        .print-pill-yes{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:999px;font-size:10px;font-weight:800;background:var(--green-bg);color:var(--green)}
        .print-pill-no{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:999px;font-size:10px;font-weight:800;background:#f1f5f9;color:var(--muted2)}

        /* ── Mobile cards ── */
        .res-card{background:var(--card);border-radius:var(--r);border:1px solid var(--brd);padding:14px 16px;cursor:pointer;transition:all .15s;position:relative;overflow:hidden}
        .res-card:hover{border-color:var(--indigo-brd);box-shadow:var(--shadow-md);transform:translateY(-1px)}
        .res-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:0 4px 4px 0}
        .res-card[data-status="pending"]::before{background:#fbbf24}
        .res-card[data-status="approved"]::before{background:#10b981}
        .res-card[data-status="claimed"]::before{background:#a855f7}
        .res-card[data-status="declined"]::before,.res-card[data-status="canceled"]::before{background:#ef4444}
        .res-card[data-status="unclaimed"]::before{background:#fb923c}
        .res-card[data-status="expired"]::before{background:#94a3b8}

        /* ── Overlays ── */
        .overlay{display:none;position:fixed;inset:0;z-index:200;align-items:center;justify-content:center}
        .overlay.open{display:flex}
        .overlay-bg{position:absolute;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(6px)}
        .modal-box{position:relative;margin:auto;background:var(--card);border-radius:28px;width:94%;max-width:520px;max-height:92vh;overflow-y:auto;box-shadow:0 25px 50px -12px rgba(0,0,0,.35);animation:popIn .22s cubic-bezier(.34,1.56,.64,1) both}
        .modal-box.sm{max-width:380px}
        .sheet-handle{display:none;width:40px;height:4px;background:var(--brd);border-radius:999px;margin:10px auto 0}
        @media(max-width:639px){
            .overlay#detailModal{align-items:flex-end}
            .overlay#detailModal .modal-box{margin:0;width:100%;max-width:100%;border-radius:28px 28px 0 0;max-height:92vh;animation:slideUp .28s cubic-bezier(.34,1.2,.64,1) both}
            .sheet-handle{display:block}
        }
        @keyframes popIn{from{opacity:0;transform:scale(.92) translateY(16px)}to{opacity:1;transform:none}}
        @keyframes slideUp{from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none}}
        .modal-box::-webkit-scrollbar{width:4px}
        .modal-box::-webkit-scrollbar-thumb{background:var(--brd);border-radius:4px}

        /* ── Detail rows ── */
        .drow{display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid var(--brd2)}
        .drow:last-child{border-bottom:none}
        .dicon{width:36px;height:36px;border-radius:12px;background:var(--indigo-light);color:var(--indigo);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
        .dlabel{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:2px}
        .dvalue{font-size:14px;font-weight:700;color:var(--text)}

        /* ── Modal buttons ── */
        .btn-confirm-approve{background:#16a34a;color:#fff;border:none;border-radius:14px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:var(--font);flex:1}
        .btn-confirm-approve:hover:not(:disabled){background:#15803d}
        .btn-confirm-decline{background:#ef4444;color:#fff;border:none;border-radius:14px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:var(--font);flex:1}
        .btn-confirm-decline:hover:not(:disabled){background:#dc2626}
        .btn-cancel{background:#f1f5f9;color:var(--muted2);border:none;border-radius:14px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:var(--font);flex:1}
        .btn-cancel:hover{background:#e2e8f0}

        /* ── Ticket / Print ── */
        .ticket-section{border:2px dashed var(--indigo-brd);border-radius:20px;padding:20px;background:var(--indigo-light);display:flex;flex-direction:column;align-items:center}
        #dPrintLogForm{background:#f8fafc;border:1px solid var(--brd);border-radius:18px;padding:16px 18px;margin:0 24px 14px}
        #dPrintLogForm label{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);display:block;margin-bottom:6px}
        #dPrintLogForm input[type=number]{width:100%;border:1px solid var(--brd);border-radius:10px;padding:8px 12px;font-size:14px;font-family:var(--font);color:var(--text);background:var(--card);outline:none}
        #dPrintLogForm input[type=number]:focus{border-color:var(--indigo-mid);box-shadow:0 0 0 3px rgba(67,56,202,.1)}
        .btn-save-print{background:var(--indigo);color:#fff;border:none;border-radius:10px;padding:9px 16px;font-size:12px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .15s;display:flex;align-items:center;gap:6px}
        .btn-save-print:hover:not(:disabled){background:#312e81}
        .btn-save-print:disabled{opacity:.6;cursor:not-allowed}
        .unclaimed-banner{background:var(--orange-bg);border:1.5px dashed #fdba74;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:10px;margin:0 24px 14px}
        .ub-icon{width:34px;height:34px;background:#fed7aa;border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--orange);font-size:13px;flex-shrink:0}

        .sec-lbl{font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .sec-lbl::before{content:'';width:3px;height:14px;background:var(--indigo);border-radius:2px;flex-shrink:0}
        .card-empty{padding:48px 24px;text-align:center;background:var(--card);border-radius:var(--r);border:1px dashed var(--brd)}
        @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        .fade-up{animation:fadeUp .35s ease both}

        /* ── DARK MODE ── */
        body.dark{background:#060e1e;color:#e2eaf8}
        body.dark .sidebar-inner{background:#0b1628;border-color:rgba(99,102,241,.12)}
        body.dark .sb-top,.dark .sb-footer{border-color:rgba(99,102,241,.1)}
        body.dark .brand-name{color:#e2eaf8}
        body.dark .user-card{background:rgba(55,48,163,.15);border-color:rgba(99,102,241,.2)}
        body.dark .user-name{color:#e2eaf8}
        body.dark .user-role{color:#818cf8}
        body.dark .nav-link{color:#7fb3e8}
        body.dark .nav-link:hover{background:rgba(99,102,241,.12);color:#a5b4fc}
        body.dark .nav-link:not(.active) .nav-icon{background:rgba(99,102,241,.1)}
        body.dark .nav-link:hover:not(.active) .nav-icon{background:rgba(99,102,241,.2)}
        body.dark .mobile-nav-pill{background:#0b1628;border-color:rgba(99,102,241,.18)}
        body.dark .mob-item{color:#7fb3e8}
        body.dark .mob-item.active,.dark .mob-item:hover{background:rgba(99,102,241,.18);color:#a5b4fc}
        body.dark .stat-card,.dark .filter-bar,.dark .card-empty{background:#0b1628;border-color:#1e3a5f}
        body.dark .stat-lbl,.dark .muted{color:#4a6fa5}
        body.dark .qtab{background:#0b1628;border-color:#1e3a5f;color:#93c5fd}
        body.dark .qtab:hover{border-color:var(--indigo);color:#a5b4fc}
        body.dark .qtab.active{background:var(--indigo);border-color:var(--indigo);color:#fff}
        body.dark .field,.dark .field-plain{background:#101e35;border-color:#1e3a5f;color:#e2eaf8}
        body.dark .field:focus,.dark .field-plain:focus{border-color:#6366f1}
        body.dark thead th{background:#101e35!important;color:#4a6fa5!important;border-color:#1e3a5f!important}
        body.dark td{border-color:#1e3a5f;color:#e2eaf8}
        body.dark tbody tr:hover td{background:rgba(99,102,241,.08)!important}
        body.dark .badge-expired{background:rgba(100,116,139,.15);color:#94a3b8}
        body.dark .badge-pending{background:rgba(251,191,36,.15);color:#fbbf24}
        body.dark .badge-approved{background:rgba(34,197,94,.15);color:#4ade80}
        body.dark .badge-declined,.dark .badge-canceled{background:rgba(239,68,68,.15);color:#f87171}
        body.dark .badge-claimed{background:rgba(168,85,247,.15);color:#c084fc}
        body.dark .badge-unclaimed{background:rgba(251,146,60,.12);color:#fb923c;border-color:rgba(251,146,60,.3)}
        body.dark .print-pill-yes{background:rgba(34,197,94,.15);color:#4ade80}
        body.dark .print-pill-no{background:rgba(100,116,139,.15);color:#94a3b8}
        body.dark .res-card{background:#0b1628;border-color:#1e3a5f}
        body.dark .res-card:hover{border-color:var(--indigo)}
        body.dark .modal-box{background:#0b1628}
        body.dark .modal-box::-webkit-scrollbar-thumb{background:#1e3a5f}
        body.dark .sheet-handle{background:#1e3a5f}
        body.dark .drow{border-color:#1e3a5f}
        body.dark .dvalue{color:#e2eaf8}
        body.dark .dicon{background:rgba(99,102,241,.12);color:#818cf8}
        body.dark #dPrintLogForm{background:#101e35;border-color:#1e3a5f}
        body.dark #dPrintLogForm input[type=number]{background:#0b1628;border-color:#1e3a5f;color:#e2eaf8}
        body.dark .btn-cancel{background:#101e35;color:#93c5fd}
        body.dark .btn-cancel:hover{background:#1e3a5f}
        body.dark .btn-reset{background:#101e35;color:#93c5fd}
        body.dark .btn-reset:hover{background:#1e3a5f}
        body.dark .unclaimed-banner{background:rgba(251,146,60,.1);border-color:rgba(251,146,60,.3)}
        body.dark .ticket-section{background:rgba(99,102,241,.08);border-color:rgba(99,102,241,.3)}
        body.dark #dPrintLog{background:#101e35;border-color:#1e3a5f}
        body.dark #autoRefreshIndicator{background:rgba(11,22,40,.95)}
        body.dark .bg-green-50{background:rgba(22,163,74,.12)!important}
        body.dark .border-green-200{border-color:rgba(22,163,74,.3)!important}
        body.dark .text-green-700{color:#4ade80!important}
        body.dark .bg-red-50{background:rgba(239,68,68,.12)!important}
        body.dark .border-red-200{border-color:rgba(239,68,68,.3)!important}
        body.dark .text-red-700{color:#f87171!important}
        body.dark .bg-slate-50\/60{background:rgba(16,30,53,.8)!important}
    </style>
</head>
<body class="flex min-h-screen">

<?php
$navItems = [
    ['url'=>'/admin/dashboard',           'icon'=>'fa-house',           'label'=>'Dashboard',       'key'=>'dashboard'],
    ['url'=>'/admin/new-reservation',     'icon'=>'fa-plus',            'label'=>'New Reservation', 'key'=>'new-reservation'],
    ['url'=>'/admin/manage-reservations', 'icon'=>'fa-calendar',        'label'=>'Reservations',    'key'=>'manage-reservations'],
    ['url'=>'/admin/manage-pcs',          'icon'=>'fa-desktop',         'label'=>'Manage PCs',      'key'=>'manage-pcs'],
    ['url'=>'/admin/manage-sk',           'icon'=>'fa-user-shield',     'label'=>'Manage SK',       'key'=>'manage-sk'],
    ['url'=>'/admin/books',               'icon'=>'fa-book-open',       'label'=>'Library',         'key'=>'books'],
    ['url'=>'/admin/login-logs',          'icon'=>'fa-clock',           'label'=>'Login Logs',      'key'=>'login-logs'],
    ['url'=>'/admin/scanner',             'icon'=>'fa-qrcode',          'label'=>'Scanner',         'key'=>'scanner'],
    ['url'=>'/admin/activity-logs',       'icon'=>'fa-list',            'label'=>'Activity Logs',   'key'=>'activity-logs'],
    ['url'=>'/admin/profile',             'icon'=>'fa-regular fa-user', 'label'=>'Profile',         'key'=>'profile'],
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
$avatarLetter = strtoupper(mb_substr(trim($sk_name), 0, 1));
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
        <div class="flex items-start justify-between px-6 pt-5 pb-3" style="display:flex;align-items:flex-start;justify-content:space-between;padding:20px 24px 12px">
            <div>
                <p id="dId" style="font-size:11px;font-weight:800;color:var(--muted);font-variant-numeric:tabular-nums;margin-bottom:3px"></p>
                <h3 style="font-size:18px;font-weight:800;color:var(--text)">Reservation Details</h3>
            </div>
            <button onclick="closeModal('detail')" style="width:36px;height:36px;border-radius:10px;background:#f1f5f9;border:none;color:var(--muted2);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;font-size:14px"><i class="fa-solid fa-xmark"></i></button>
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
            <div class="drow"><div class="dicon"><i class="fa-solid fa-user"></i></div>
                <div><p class="dlabel">Requestor</p><p id="dName" class="dvalue"></p><p id="dEmail" style="font-size:11px;color:var(--muted);font-weight:600;margin-top:2px"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-desktop"></i></div>
                <div><p class="dlabel">Resource</p><p id="dResource" class="dvalue"></p><p id="dPc" style="font-size:11px;color:var(--muted);font-weight:600;margin-top:2px"></p></div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-solid fa-calendar-day"></i></div>
                <div><p class="dlabel">Schedule</p><p id="dDate" class="dvalue"></p><p id="dTime" style="font-size:11px;color:var(--muted);font-weight:600;margin-top:2px"></p></div>
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
                    <p id="dApprovedByEmail" style="font-size:11px;color:var(--muted);font-weight:600;margin-top:2px"></p>
                    <p id="dApprovedAt" style="font-size:11px;color:var(--muted);font-weight:600;margin-top:2px"></p>
                </div>
            </div>
            <div class="drow"><div class="dicon"><i class="fa-regular fa-clock"></i></div>
                <div><p class="dlabel">Submitted</p><p id="dCreated" class="dvalue"></p></div>
            </div>
        </div>

        <div id="dQr" class="ticket-section" style="display:none;margin:0 24px 14px">
            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:12px">E-Ticket</p>
            <canvas id="qrCanvas" style="border-radius:12px"></canvas>
            <p id="dTicketCode" style="font-size:11px;color:var(--muted);font-family:monospace;margin-top:8px;text-align:center;word-break:break-all;padding:0 8px"></p>
            <button onclick="downloadTicket()" style="margin-top:12px;display:flex;align-items:center;gap:8px;padding:8px 18px;background:var(--indigo);color:#fff;border-radius:10px;font-weight:800;font-size:12px;border:none;cursor:pointer;font-family:var(--font)"><i class="fa-solid fa-download" style="font-size:11px"></i> Download E-Ticket</button>
        </div>
        <div id="dClaimed" style="display:none;margin:0 24px 14px;background:var(--purple-bg);border:2px dashed #c4b5fd;border-radius:18px;padding:20px;text-align:center">
            <i class="fa-solid fa-check-double" style="font-size:1.5rem;color:var(--purple);display:block;margin-bottom:6px"></i>
            <p style="font-weight:800;color:var(--purple);font-size:13px">Ticket Already Claimed</p>
            <p style="font-size:11px;color:#8b5cf6;margin-top:3px">This reservation has been used.</p>
        </div>
        <div id="dPrintLog" style="display:none;margin:0 24px 12px;border-radius:18px;padding:12px 14px;border:1px solid var(--brd);background:#f8fafc;display:none;align-items:center;gap:12px">
            <div style="width:36px;height:36px;background:var(--green-bg);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="fa-solid fa-print" style="color:var(--green);font-size:13px"></i></div>
            <div style="flex:1;min-width:0">
                <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:2px">Print Log</p>
                <p id="dPrintText" style="font-size:13px;font-weight:700;color:var(--text)"></p>
            </div>
            <span id="dPrintBadge" style="font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;flex-shrink:0"></span>
        </div>
        <div id="dPrintLogForm" style="margin:0 24px 14px">
            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:12px;display:flex;align-items:center;gap:7px"><i class="fa-solid fa-print" style="color:var(--indigo)"></i> Log Print for this Reservation</p>
            <div style="display:flex;align-items:flex-end;gap:10px">
                <div style="flex:1">
                    <label>Pages Printed <span style="color:var(--muted);font-weight:400;text-transform:none;letter-spacing:0">(0 = not printed)</span></label>
                    <input type="number" id="printPagesInput" min="0" max="999" value="0" placeholder="0">
                </div>
                <button id="savePrintBtn" class="btn-save-print" onclick="savePrintLog()"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save</button>
            </div>
            <p id="printSaveMsg" style="font-size:12px;font-weight:700;margin-top:6px;min-height:18px;color:var(--muted)"></p>
        </div>
        <div id="dActions" style="padding:16px 24px;border-top:1px solid var(--brd2);display:flex;gap:10px;flex-wrap:wrap;margin-top:8px"></div>
    </div>
</div>

<!-- Approve confirm -->
<div id="approveModal" class="overlay">
    <div class="overlay-bg" onclick="closeModal('approve')"></div>
    <div class="modal-box sm">
        <div style="padding:24px 24px 20px;text-align:center">
            <div style="width:64px;height:64px;background:var(--green-bg);color:var(--green);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-circle-check"></i></div>
            <h3 style="font-size:18px;font-weight:800;color:var(--text)">Approve Reservation?</h3>
            <p style="color:var(--muted);font-size:13px;margin-top:4px;font-weight:500">This will confirm the reservation.</p>
            <p id="approveConfirmName" style="color:var(--text);font-size:13px;margin-top:10px;font-weight:800"></p>
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
            <div style="width:64px;height:64px;background:var(--red-bg);color:var(--red);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <h3 style="font-size:18px;font-weight:800;color:var(--text)">Decline Reservation?</h3>
            <p style="color:var(--muted);font-size:13px;margin-top:4px;font-weight:500">This action cannot be undone.</p>
            <p id="declineConfirmName" style="color:var(--text);font-size:13px;margin-top:10px;font-weight:800"></p>
        </div>
        <div style="padding:0 24px 24px;display:flex;gap:10px">
            <button class="btn-cancel" onclick="closeModal('decline')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Cancel</button>
            <button id="confirmDeclineBtn" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sb-top">
            <div class="brand-tag">Admin Control Room</div>
            <div class="brand-name">my<em>Space.</em></div>
            <div style="font-size:11px;color:var(--muted);margin-top:3px">Administration Panel</div>
        </div>
        <div class="user-card">
            <div class="user-av"><?= $avatarLetter ?></div>
            <div style="min-width:0">
                <div class="user-name"><?= htmlspecialchars($sk_name) ?></div>
                <div class="user-role">System Admin</div>
            </div>
        </div>
        <nav class="sb-nav">
            <div class="nav-sec-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $hasBadge = ($item['key']==='manage-reservations' && ($counts['pending'] ?? 0) > 0);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                    <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>" style="font-size:13px"></i></div>
                    <?= $item['label'] ?>
                    <?php if ($hasBadge): ?>
                        <span class="nav-badge"><?= $counts['pending'] ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sb-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08)"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:13px;color:#f87171"></i></div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- MOBILE NAV -->
<nav class="mobile-nav-pill">
    <div class="mob-scroll">
        <?php foreach ($navItems as $item):
            $active = ($page == $item['key']);
        ?>
            <a href="<?= $item['url'] ?>" class="mob-item <?= $active ? 'active' : '' ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1rem"></i>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-item" style="color:#f87171">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1rem"></i>
        </a>
    </div>
</nav>

<!-- MAIN -->
<main class="main">
    <div class="fade-up">
        <div class="topbar-row" style="margin-bottom:6px">
            <div>
                <div class="eyebrow">Admin Portal</div>
                <div class="page-title">Manage Reservations</div>
                <div class="page-sub">
                    <?= $counts['all'] ?> total record<?= $counts['all'] != 1 ? 's' : '' ?>
                    <?php if ($counts['unclaimed'] > 0): ?>
                        · <span style="color:var(--orange);font-weight:700"><?= $counts['unclaimed'] ?> unclaimed</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="topbar-right">
                <button onclick="toggleDark()" id="darkBtn" style="width:42px;height:42px;background:var(--card);border:1px solid var(--brd);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:var(--muted2);cursor:pointer;transition:all .15s;font-size:15px">
                    <span id="darkIcon"><i class="fa-regular fa-sun"></i></span>
                </button>
                <button onclick="exportCSV()" class="btn-export"><i class="fa-solid fa-file-csv"></i> Export CSV</button>
            </div>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="stats-grid fade-up">
        <?php foreach ([
            ['Total',     $counts['all'],       '#3730a3', 'fa-layer-group', 'all'],
            ['Pending',   $counts['pending'],   '#d97706', 'fa-clock',       'pending'],
            ['Approved',  $counts['approved'],  '#16a34a', 'fa-circle-check','approved'],
            ['Claimed',   $counts['claimed'],   '#7c3aed', 'fa-check-double','claimed'],
            ['Declined',  $counts['declined'],  '#dc2626', 'fa-xmark-circle','declined'],
            ['Unclaimed', $counts['unclaimed'], '#c2410c', 'fa-ticket',      'unclaimed'],
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
        <div style="margin-bottom:16px;padding:14px 18px;background:var(--green-bg);border:1px solid #86efac;color:var(--green);font-weight:700;border-radius:16px;display:flex;align-items:center;gap:10px;font-size:13px" class="fade-up">
            <i class="fa-solid fa-circle-check" style="color:var(--green)"></i><?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div style="margin-bottom:16px;padding:14px 18px;background:var(--red-bg);border:1px solid #fca5a5;color:var(--red);font-weight:700;border-radius:16px;display:flex;align-items:center;gap:10px;font-size:13px" class="fade-up">
            <i class="fa-solid fa-circle-exclamation" style="color:var(--red)"></i><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Filter bar -->
    <div class="filter-bar fade-up">
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:12px" class="sm:flex-row">
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <div style="position:relative;flex:1;min-width:180px">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:11px;pointer-events:none"></i>
                    <input id="searchInput" type="text" placeholder="Search name, resource, purpose…" class="field" oninput="applyFilters()">
                </div>
                <div style="position:relative;width:160px">
                    <i class="fa-regular fa-calendar" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:11px;pointer-events:none"></i>
                    <input id="dateInput" type="date" class="field-plain" style="padding-left:36px" onchange="applyFilters()">
                </div>
                <button onclick="clearFilters()" class="btn-reset"><i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Reset</button>
            </div>
        </div>
        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px">
            <button class="qtab active" data-tab="all" onclick="setTab(this,'all')"><i class="fa-solid fa-layer-group" style="font-size:11px"></i> All <span style="font-size:9px;font-weight:800;opacity:.7"><?= $counts['all'] ?></span></button>
            <button class="qtab" data-tab="pending" onclick="setTab(this,'pending')"><i class="fa-solid fa-clock" style="font-size:11px"></i> Pending<?php if ($counts['pending'] > 0): ?><span style="background:#f59e0b;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;line-height:1"><?= $counts['pending'] ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="approved" onclick="setTab(this,'approved')"><i class="fa-solid fa-circle-check" style="font-size:11px"></i> Approved</button>
            <button class="qtab" data-tab="unclaimed" onclick="setTab(this,'unclaimed')"><i class="fa-solid fa-ticket" style="font-size:11px"></i> Unclaimed<?php if ($counts['unclaimed'] > 0): ?><span style="background:#fb923c;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:999px;line-height:1"><?= $counts['unclaimed'] ?></span><?php endif; ?></button>
            <button class="qtab" data-tab="claimed" onclick="setTab(this,'claimed')"><i class="fa-solid fa-check-double" style="font-size:11px"></i> Claimed</button>
            <button class="qtab" data-tab="declined" onclick="setTab(this,'declined')"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Declined</button>
            <button class="qtab" data-tab="expired" onclick="setTab(this,'expired')"><i class="fa-solid fa-hourglass-end" style="font-size:11px"></i> Expired</button>
        </div>
    </div>

    <p id="resultCount" style="font-size:11px;font-weight:700;color:var(--muted);padding:0 4px;margin-bottom:12px"></p>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block fade-up" style="background:var(--card);border:1px solid var(--brd);border-radius:24px;overflow:hidden;box-shadow:0 1px 4px rgba(15,23,42,.05)">
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
                        <th class="text-right" style="width:140px;text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($processed)): ?>
                        <tr><td colspan="9">
                            <div style="padding:80px 24px;text-align:center">
                                <i class="fa-solid fa-calendar-xmark" style="font-size:2.5rem;color:var(--brd);display:block;margin-bottom:12px"></i>
                                <p style="font-weight:800;color:var(--muted);font-size:15px">No reservations yet</p>
                            </div>
                        </td></tr>
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
                                'id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,
                                'resource'=>$resource,'pc'=>$pc,'date'=>$date,'rawDate'=>$rawDate,
                                'start'=>$start,'end'=>$end,'purpose'=>$purpose,'type'=>$type,
                                'created'=>$created,'code'=>$code,
                                'claimed'=>$isClaimed,'unclaimed'=>$isUnclaimed,
                                'approverName'=>$approverName,'approverEmail'=>$approverEmail,'approvedAt'=>$approvedAt,
                                'plPrinted'=>$plPrinted,'plPages'=>$plPages,'plAt'=>$plAt,
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
                            <td><span style="font-size:11px;font-weight:800;color:var(--muted);font-family:monospace">#<?= $res['id'] ?></span></td>
                            <td>
                                <p style="font-weight:700;font-size:13px;color:var(--text)"><?= $name ?></p>
                                <?php if ($email): ?><p style="font-size:11px;color:var(--muted);margin-top:2px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p><?php endif; ?>
                            </td>
                            <td>
                                <p style="font-weight:700;font-size:13px;color:var(--text)"><?= $resource ?></p>
                                <?php if ($pc): ?><div style="display:flex;align-items:center;gap:5px;margin-top:2px"><i class="fa-solid fa-desktop" style="font-size:9px;color:var(--muted)"></i><span style="font-size:11px;color:var(--muted2);font-weight:600"><?= $pc ?></span></div><?php endif; ?>
                            </td>
                            <td>
                                <p style="font-size:13px;font-weight:700;color:var(--text)"><?= $date ?></p>
                                <p style="font-size:11px;color:var(--indigo-mid);font-weight:600;margin-top:2px"><?= $start ?> – <?= $end ?></p>
                            </td>
                            <td><span style="font-size:12px;color:var(--muted2);font-weight:500;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;max-width:130px"><?= $purpose ?></span></td>
                            <td><span class="badge badge-<?= $s ?>"><i class="fa-solid <?= $icon ?>" style="font-size:9px"></i><?= ucfirst($s) ?></span></td>
                            <td onclick="event.stopPropagation()">
                                <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired','unclaimed'])): ?>
                                    <div style="display:flex;align-items:center;gap:7px">
                                        <div style="width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;flex-shrink:0;<?= $s==='declined'?'background:var(--red-bg);color:var(--red)':'background:var(--green-bg);color:var(--green)' ?>"><?= mb_strtoupper(mb_substr($approverName,0,1)) ?></div>
                                        <div style="min-width:0">
                                            <p style="font-size:12px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:110px"><?= $approverName ?></p>
                                            <?php if ($approvedAt): ?><p style="font-size:10px;color:var(--muted);font-weight:500"><?= $approvedAt ?></p><?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?><span style="font-size:10px;color:var(--brd);font-weight:700">—</span><?php endif; ?>
                            </td>
                            <td onclick="event.stopPropagation()">
                                <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> <?= $plPages ?>pg</span>
                                <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>
                                <?php else: ?><span style="font-size:10px;color:var(--brd);font-weight:700">—</span><?php endif; ?>
                            </td>
                            <td style="text-align:right" onclick="event.stopPropagation()">
                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:5px">
                                    <?php if ($s === 'pending'): ?>
                                        <button onclick="triggerApprove(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-approve-sm"><i class="fa-solid fa-check" style="font-size:10px"></i> Approve</button>
                                        <button onclick="triggerDecline(<?= $res['id'] ?>,'<?= addslashes($name) ?>')" class="btn-decline-sm"><i class="fa-solid fa-xmark" style="font-size:10px"></i></button>
                                    <?php elseif ($s === 'unclaimed'): ?>
                                        <span style="font-size:11px;color:var(--orange);font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-ticket" style="font-size:10px"></i> Unclaimed</span>
                                    <?php elseif ($s === 'approved'): ?>
                                        <span style="font-size:11px;color:var(--green);font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-circle-check" style="font-size:10px"></i> Approved</span>
                                    <?php elseif ($s === 'claimed'): ?>
                                        <span style="font-size:11px;color:var(--purple);font-weight:800;display:flex;align-items:center;gap:4px"><i class="fa-solid fa-check-double" style="font-size:10px"></i> Claimed</span>
                                    <?php else: ?>
                                        <span style="font-size:11px;color:var(--muted);font-style:italic">—</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:10px 18px;border-top:1px solid var(--brd2);background:rgba(238,242,255,.4);display:flex;align-items:center;justify-content:space-between">
            <p id="tableFooter" style="font-size:11px;font-weight:700;color:var(--muted)"></p>
            <p style="font-size:11px;color:var(--brd);font-weight:600;display:none" class="sm:block">Click any row to preview · Export CSV exports current filter</p>
        </div>
    </div>

    <!-- MOBILE CARDS -->
    <div id="mobileCardList" class="md:hidden space-y-3" style="display:flex;flex-direction:column;gap:10px">
        <?php if (empty($processed)): ?>
            <div class="card-empty"><i class="fa-solid fa-calendar-xmark" style="font-size:2rem;color:var(--brd);display:block;margin-bottom:10px"></i><p style="font-weight:800;color:var(--muted)">No reservations yet</p></div>
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
                    'id'=>$res['id'],'status'=>$s,'name'=>$name,'email'=>$email,
                    'resource'=>$resource,'pc'=>$pc,'date'=>$date,'rawDate'=>$rawDate,
                    'start'=>$start,'end'=>$end,'purpose'=>$purpose,'type'=>$type,
                    'created'=>$created,'code'=>$code,
                    'claimed'=>$isClaimed,'unclaimed'=>$isUnclaimed,
                    'approverName'=>$approverName,'approverEmail'=>$approverEmail,'approvedAt'=>$approvedAt,
                    'plPrinted'=>$plPrinted,'plPages'=>$plPages,'plAt'=>$plAt,
                ]);
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
                            <p style="font-weight:700;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $name ?></p>
                            <?php if ($email): ?><p style="font-size:11px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $email ?></p><?php endif; ?>
                        </div>
                        <span class="badge badge-<?= $s ?>" style="flex-shrink:0"><i class="fa-solid <?= $icon ?>" style="font-size:9px"></i><?= ucfirst($s) ?></span>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:8px">
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                                <i class="fa-solid fa-desktop" style="font-size:10px;color:var(--muted);flex-shrink:0"></i>
                                <p style="font-size:12px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $resource ?><?= $pc ? ' · '.$pc : '' ?></p>
                            </div>
                            <div style="display:flex;align-items:center;gap:6px">
                                <i class="fa-regular fa-calendar" style="font-size:10px;color:var(--muted);flex-shrink:0"></i>
                                <p style="font-size:11px;color:var(--muted2);font-weight:600"><?= $date ?></p>
                                <span style="font-size:10px;color:var(--indigo-mid);font-weight:700"><?= $start ?> – <?= $end ?></span>
                            </div>
                        </div>
                        <div class="card-print-pill" style="flex-shrink:0">
                            <?php if ($plPrinted === true): ?><span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> <?= $plPages ?>pg</span>
                            <?php elseif ($plPrinted === false): ?><span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span><?php endif; ?>
                        </div>
                    </div>
                    <p style="font-size:11px;color:var(--muted);font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:10px"><?= $purpose ?></p>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding-top:10px;border-top:1px solid var(--brd2)">
                        <div style="display:flex;align-items:center;gap:6px;min-width:0">
                            <?php if ($approverName && in_array($s, ['approved','claimed','declined','expired','unclaimed'])): ?>
                                <div style="width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:800;flex-shrink:0;<?= $s==='declined'?'background:var(--red-bg);color:var(--red)':'background:var(--green-bg);color:var(--green)' ?>"><?= mb_strtoupper(mb_substr($approverName,0,1)) ?></div>
                                <p style="font-size:10px;color:var(--muted2);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $s==='declined'?'Declined':'Approved' ?> by <?= $approverName ?></p>
                            <?php else: ?>
                                <p style="font-size:10px;color:var(--brd);font-weight:600">#<?= $res['id'] ?></p>
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

    <div id="mobileEmpty" style="display:none" class="card-empty">
        <i class="fa-solid fa-filter-circle-xmark" style="font-size:2rem;color:var(--brd);display:block;margin-bottom:10px"></i>
        <p style="font-weight:800;color:var(--muted)">No reservations match</p>
        <p style="color:var(--brd);font-size:12px;margin-top:4px">Try adjusting your search or filters.</p>
    </div>
</main>

<script>
const allTableRows = Array.from(document.querySelectorAll('#tableBody .res-row'));
const allCards     = Array.from(document.querySelectorAll('#mobileCardList .res-card'));
let   curTab       = 'all';
let   approveTargetId = null, declineTargetId = null;

let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
let csrfName  = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content')  ?? 'csrf_token';

function refreshCsrf(data){
    if(data.csrf_hash && data.csrf_token){
        csrfToken=data.csrf_hash; csrfName=data.csrf_token;
        document.querySelector('meta[name="csrf-token"]')?.setAttribute('content',csrfToken);
        document.querySelector('meta[name="csrf-name"]')?.setAttribute('content',csrfName);
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

function toggleDark(){
    const isDark = document.body.classList.toggle('dark');
    const icon = document.getElementById('darkIcon');
    icon.innerHTML = isDark ? '<i class="fa-regular fa-moon"></i>' : '<i class="fa-regular fa-sun"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
}
(function initDark(){
    if(localStorage.getItem('admin_theme')==='dark'){
        document.body.classList.add('dark');
        const icon=document.getElementById('darkIcon');
        if(icon) icon.innerHTML='<i class="fa-regular fa-moon"></i>';
    }
    document.documentElement.classList.remove('dark-pre');
})();

async function savePrintLog(){
    const rid=_currentReservationId, pages=parseInt(document.getElementById('printPagesInput').value,10)||0;
    const btn=document.getElementById('savePrintBtn'), msg=document.getElementById('printSaveMsg');
    if(!rid){msg.textContent='No reservation selected.';msg.style.color='var(--red)';return;}
    btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin" style="font-size:11px"></i> Saving…'; msg.textContent='';
    const body=new FormData();
    body.append(csrfName,csrfToken); body.append('reservation_id',rid);
    body.append('printed',pages>0?1:0); body.append('pages',pages);
    try{
        const res=await fetch('<?= base_url('admin/log-print') ?>',{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body});
        const text=await res.text(); let data;
        try{data=JSON.parse(text);}catch{throw new Error(`Server error (${res.status})`);}
        if(data.ok){
            refreshCsrf(data);
            const now=new Date();
            const fmt=now.toLocaleDateString('en-US',{month:'short',day:'numeric'})+' · '+now.toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
            printLogMap[rid]={printed:pages>0,pages,at:fmt};
            refreshPrintLogStrip(rid); refreshBothPrintCells(rid,pages);
            msg.textContent=pages>0?`✓ Saved — ${pages} page${pages!==1?'s':''} printed`:'✓ Saved — no printing logged';
            msg.style.color='var(--green)';
            btn.innerHTML='<i class="fa-solid fa-check" style="font-size:11px"></i> Saved';
            setTimeout(()=>{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';},2000);
        }else{throw new Error(data.error??'Unknown error');}
    }catch(err){
        msg.textContent='✗ Failed: '+err.message; msg.style.color='var(--red)';
        btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';
    }
}

function refreshPrintLogStrip(rid){
    const plog=printLogMap[rid],logEl=document.getElementById('dPrintLog');
    if(!plog){logEl.style.display='none';return;}
    logEl.style.display='flex';
    const logText=document.getElementById('dPrintText'), logBadge=document.getElementById('dPrintBadge');
    if(plog.printed){
        logText.textContent=`Printed ${plog.pages} page${plog.pages!==1?'s':''}${plog.at?' · '+plog.at:''}`;
        logBadge.textContent=`${plog.pages}pg`;
        logBadge.style.cssText='background:var(--green-bg);color:var(--green)';
    }else{
        logText.textContent='No printing during this session';
        logBadge.textContent='No print';
        logBadge.style.cssText='background:#f1f5f9;color:var(--muted2)';
    }
}

function refreshBothPrintCells(rid,pages){
    allTableRows.forEach(row=>{
        if(row.dataset.id==rid){
            const cell=row.cells[7];
            if(pages>0){cell.innerHTML=`<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> ${pages}pg</span>`;row.dataset.plPrinted='Yes';row.dataset.plPages=pages;}
            else{cell.innerHTML=`<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>`;row.dataset.plPrinted='No';row.dataset.plPages='';}
        }
    });
    allCards.forEach(card=>{
        if(card.dataset.id==rid){
            const w=card.querySelector('.card-print-pill');
            if(w){w.innerHTML=pages>0?`<span class="print-pill-yes"><i class="fa-solid fa-print" style="font-size:9px"></i> ${pages}pg</span>`:`<span class="print-pill-no"><i class="fa-solid fa-xmark" style="font-size:9px"></i> No print</span>`;}
            card.dataset.plPrinted=pages>0?'Yes':'No'; card.dataset.plPages=pages>0?pages:'';
        }
    });
}

function exportCSV(){
    const visibleRows=allTableRows.filter(r=>r.style.display!=='none');
    const headers=['ID','User Name','Email','Resource Name','PC Number','Date','Start Time','End Time','Purpose','Visitor Type','Status','Approved By','Approved At','Printed','Pages Printed','Submitted At'];
    const escape=v=>{const s=String(v??'');return s.includes(',')||s.includes('"')||s.includes('\n')?'"'+s.replace(/"/g,'""')+'"':s;};
    const lines=[headers.map(escape).join(',')];
    visibleRows.forEach(row=>{
        try{const d=JSON.parse(row.getAttribute('onclick').replace(/^openDetail\(/,'').replace(/\)$/,''));
        lines.push([d.id??'',d.name??'',d.email??'',d.resource??'',d.pc??'',d.date??'',d.start??'',d.end??'',d.purpose??'',d.type??'',d.status??'',d.approverName??'',d.approvedAt??'',row.dataset.plPrinted??'',row.dataset.plPages??'',d.created??''].map(escape).join(','));}catch(e){}
    });
    const blob=new Blob([lines.join('\r\n')],{type:'text/csv;charset=utf-8;'});
    const url=URL.createObjectURL(blob); const a=document.createElement('a');
    a.href=url; a.download=`admin-reservations-${new Date().toISOString().slice(0,10)}.csv`; a.click(); URL.revokeObjectURL(url);
}

function setTab(btn,tab){document.querySelectorAll('.qtab').forEach(t=>t.classList.remove('active'));btn.classList.add('active');curTab=tab;syncCards(tab);applyFilters();}
function filterByStatus(tab){curTab=tab;document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab===tab));syncCards(tab);applyFilters();}
function syncCards(tab){document.querySelectorAll('[data-filter]').forEach(c=>c.classList.toggle('ring',c.dataset.filter===tab));}

function applyFilters(){
    const q=document.getElementById('searchInput').value.toLowerCase().trim();
    const date=document.getElementById('dateInput').value;
    const matchesFilters=el=>{
        let matchTab;
        if(curTab==='all') matchTab=true;
        else if(curTab==='declined') matchTab=['declined','canceled'].includes(el.dataset.status);
        else matchTab=el.dataset.status===curTab;
        return matchTab&&(!q||el.dataset.search.includes(q))&&(!date||el.dataset.date===date);
    };
    let n=0; allTableRows.forEach(row=>{const show=matchesFilters(row);row.style.display=show?'':'none';if(show)n++;});
    let m=0; allCards.forEach(card=>{const show=matchesFilters(card);card.style.display=show?'':'none';if(show)m++;});
    if(allCards.length>0) document.getElementById('mobileEmpty').style.display=m===0?'block':'none';
    const total=allTableRows.length;
    document.getElementById('resultCount').textContent=`Showing ${n} of ${total} reservation${total!==1?'s':''}`;
    document.getElementById('tableFooter').textContent=`${n} result${n!==1?'s':''} displayed`;
}

function clearFilters(){
    document.getElementById('searchInput').value=''; document.getElementById('dateInput').value='';
    curTab='all'; document.querySelectorAll('.qtab').forEach(t=>t.classList.toggle('active',t.dataset.tab==='all'));
    syncCards('all'); applyFilters();
}

let sortDir={};
function sortTable(col){
    sortDir[col]=!sortDir[col];
    const tbody=document.getElementById('tableBody');
    Array.from(tbody.querySelectorAll('.res-row')).sort((a,b)=>{
        const at=(a.cells[col]?.innerText??'').trim().toLowerCase();
        const bt=(b.cells[col]?.innerText??'').trim().toLowerCase();
        return sortDir[col]?at.localeCompare(bt):bt.localeCompare(at);
    }).forEach(r=>tbody.appendChild(r));
    document.querySelectorAll('thead th').forEach((th,i)=>{
        th.classList.toggle('sorted',i===col);
        const ic=th.querySelector('.sort-icon');
        if(ic) ic.className=`fa-solid ${i===col?(sortDir[col]?'fa-sort-up':'fa-sort-down'):'fa-sort'} sort-icon`;
    });
}

const STATUS_META={
    pending:   {icon:'fa-clock',        bg:'#fef3c7',color:'#92400e',label:'Pending — Awaiting approval'},
    approved:  {icon:'fa-circle-check', bg:'#dcfce7',color:'#166534',label:'Approved'},
    claimed:   {icon:'fa-check-double', bg:'#f3e8ff',color:'#6b21a8',label:'Claimed — Ticket used'},
    declined:  {icon:'fa-xmark-circle', bg:'#fee2e2',color:'#991b1b',label:'Declined'},
    canceled:  {icon:'fa-ban',          bg:'#fee2e2',color:'#991b1b',label:'Cancelled'},
    expired:   {icon:'fa-hourglass-end',bg:'#f1f5f9',color:'#475569',label:'Expired — Was never approved'},
    unclaimed: {icon:'fa-ticket',       bg:'#fff7ed',color:'#c2410c',label:'Unclaimed — Approved but did not show up'},
};

function openDetail(d){
    _currentReservationId=d.id;
    const plog=printLogMap[d.id];
    document.getElementById('printPagesInput').value=plog?(plog.printed?plog.pages:0):0;
    document.getElementById('printSaveMsg').textContent='';
    const saveBtn=document.getElementById('savePrintBtn');
    saveBtn.disabled=false; saveBtn.innerHTML='<i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Save';

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
        approverRow.style.display='flex';
        const isDeclined=d.status==='declined';
        document.getElementById('dApprovedByLabel').textContent=isDeclined?'Declined By':'Approved By';
        const iconEl=document.getElementById('dApprovedByIcon');
        iconEl.className='dicon';
        iconEl.style.background=isDeclined?'var(--red-bg)':'var(--green-bg)';
        iconEl.style.color=isDeclined?'var(--red)':'var(--green)';
        iconEl.innerHTML=`<i class="fa-solid ${isDeclined?'fa-user-xmark':'fa-user-check'}"></i>`;
        document.getElementById('dApprovedByName').textContent=d.approverName;
        document.getElementById('dApprovedByEmail').textContent=d.approverEmail||'';
        document.getElementById('dApprovedAt').textContent=d.approvedAt?`on ${d.approvedAt}`:'';
    }else{approverRow.style.display='none';}

    const bar=document.getElementById('dStatusBar');
    bar.style.background=m.bg; bar.style.color=m.color;
    bar.innerHTML=`<i class="fa-solid ${m.icon}"></i> <span style="font-weight:700">${m.label}</span>`;

    document.getElementById('dUnclaimedBanner').style.display=d.unclaimed?'flex':'none';

    const qrSec=document.getElementById('dQr'), clSec=document.getElementById('dClaimed');
    if(d.claimed||d.status==='claimed'){
        qrSec.style.display='none'; clSec.style.display='block';
    }else if(d.status==='approved'||d.status==='unclaimed'){
        clSec.style.display='none'; qrSec.style.display='flex';
        QRCode.toCanvas(document.getElementById('qrCanvas'),d.code,{width:150,margin:1,color:{dark:'#1e293b',light:'#ffffff'}});
        document.getElementById('dTicketCode').textContent=d.code;
    }else{qrSec.style.display='none'; clSec.style.display='none';}

    refreshPrintLogStrip(d.id);

    const acts=document.getElementById('dActions');
    if(d.status==='pending'){
        acts.innerHTML=`<button onclick="triggerApprove(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-approve"><i class="fa-solid fa-check"></i> Approve</button><button onclick="triggerDecline(${d.id},'${d.name.replace(/'/g,"\\'")}');closeModal('detail');" class="btn-confirm-decline"><i class="fa-solid fa-xmark"></i> Decline</button>`;
    }else{
        acts.innerHTML=`<button onclick="closeModal('detail')" class="btn-cancel" style="width:100%"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Close</button>`;
    }
    document.getElementById('detailModal').classList.add('open');
    document.body.style.overflow='hidden';
}

function downloadTicket(){
    const canvas=document.getElementById('qrCanvas'), code=document.getElementById('dTicketCode').textContent;
    const link=document.createElement('a'); link.download=`E-Ticket-${code}.png`; link.href=canvas.toDataURL('image/png'); link.click();
}

function triggerApprove(id,name){approveTargetId=id;document.getElementById('approveConfirmName').textContent=name?`"${name}"`:'';openModal('approve');}
function triggerDecline(id,name){declineTargetId=id;document.getElementById('declineConfirmName').textContent=name?`"${name}"`:'';openModal('decline');}

document.getElementById('confirmApproveBtn').addEventListener('click',function(){
    if(!approveTargetId)return;
    this.disabled=true; this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Approving…';
    document.getElementById('approveId').value=approveTargetId; document.getElementById('approveForm').submit();
});
document.getElementById('confirmDeclineBtn').addEventListener('click',function(){
    if(!declineTargetId)return;
    this.disabled=true; this.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Declining…';
    document.getElementById('declineId').value=declineTargetId; document.getElementById('declineForm').submit();
});

const modalIds={detail:'detailModal',approve:'approveModal',decline:'declineModal'};
function openModal(key){const el=document.getElementById(modalIds[key]);if(el){el.classList.add('open');document.body.style.overflow='hidden';}}
function closeModal(key){
    const el=document.getElementById(modalIds[key]);if(el){el.classList.remove('open');document.body.style.overflow='';}
    if(key==='detail') _currentReservationId=null;
    if(key==='approve'){const b=document.getElementById('confirmApproveBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-check"></i> Approve';}
    if(key==='decline'){const b=document.getElementById('confirmDeclineBtn');b.disabled=false;b.innerHTML='<i class="fa-solid fa-xmark"></i> Decline';}
}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeModal('detail');closeModal('approve');closeModal('decline');}});

applyFilters();

/* Auto-refresh */
const AUTO_REFRESH_INTERVAL=30;
let autoRefreshTimer=null,countdownTimer=null,secondsLeft=AUTO_REFRESH_INTERVAL,refreshPaused=false;
const refreshIndicator=document.createElement('div');
refreshIndicator.id='autoRefreshIndicator';
refreshIndicator.style.cssText='position:fixed;bottom:calc(90px + env(safe-area-inset-bottom,16px));right:16px;background:rgba(55,48,163,.9);backdrop-filter:blur(8px);color:white;font-family:var(--font);font-size:11px;font-weight:700;padding:6px 12px;border-radius:999px;z-index:90;display:flex;align-items:center;gap:6px;box-shadow:0 4px 12px rgba(55,48,163,.3);cursor:pointer;';
refreshIndicator.title='Click to refresh now';
refreshIndicator.innerHTML=`<span id="refreshDot" style="width:7px;height:7px;border-radius:50%;background:#4ade80;display:inline-block"></span><span id="refreshCountdown">Refresh in ${AUTO_REFRESH_INTERVAL}s</span>`;
document.body.appendChild(refreshIndicator);
refreshIndicator.addEventListener('click',()=>doAutoRefresh(true));

function updateCountdown(){
    const el=document.getElementById('refreshCountdown'),dot=document.getElementById('refreshDot');
    if(!el)return;
    if(refreshPaused){el.textContent='Refresh paused';dot.style.background='#fbbf24';}
    else{el.textContent=`Refresh in ${secondsLeft}s`;dot.style.background='#4ade80';}
}
function startCountdown(){clearInterval(countdownTimer);secondsLeft=AUTO_REFRESH_INTERVAL;updateCountdown();countdownTimer=setInterval(()=>{if(!refreshPaused){secondsLeft--;if(secondsLeft<=0)secondsLeft=AUTO_REFRESH_INTERVAL;}updateCountdown();},1000);}
async function doAutoRefresh(force=false){
    const anyOpen=document.querySelector('.overlay.open');
    if(anyOpen&&!force)return;
    const search=document.getElementById('searchInput'),date=document.getElementById('dateInput');
    if(!force&&(document.activeElement===search||document.activeElement===date))return;
    try{
        const dot=document.getElementById('refreshDot'),el=document.getElementById('refreshCountdown');
        if(dot)dot.style.background='#818cf8'; if(el)el.textContent='Refreshing…';
        const response=await fetch(window.location.href,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'text/html'},credentials:'same-origin'});
        if(!response.ok)throw new Error('HTTP '+response.status);
        const html=await response.text();
        const parser=new DOMParser(),newDoc=parser.parseFromString(html,'text/html');
        const newTbody=newDoc.querySelector('#tableBody'),oldTbody=document.querySelector('#tableBody');
        if(newTbody&&oldTbody)oldTbody.innerHTML=newTbody.innerHTML;
        const newCards=newDoc.querySelector('#mobileCardList'),oldCards=document.querySelector('#mobileCardList');
        if(newCards&&oldCards)oldCards.innerHTML=newCards.innerHTML;
        allTableRows.length=0;document.querySelectorAll('#tableBody .res-row').forEach(r=>allTableRows.push(r));
        allCards.length=0;document.querySelectorAll('#mobileCardList .res-card').forEach(c=>allCards.push(c));
        applyFilters(); secondsLeft=AUTO_REFRESH_INTERVAL; updateCountdown();
        if(dot)dot.style.background='#4ade80';
    }catch(err){
        console.warn('Auto-refresh failed:',err.message);
        const dot=document.getElementById('refreshDot');
        if(dot){dot.style.background='#f87171';setTimeout(()=>{if(dot)dot.style.background='#4ade80';},3000);}
    }
}
const observer=new MutationObserver(()=>{refreshPaused=!!document.querySelector('.overlay.open');updateCountdown();});
document.querySelectorAll('.overlay').forEach(el=>observer.observe(el,{attributes:true,attributeFilter:['class']}));
['searchInput','dateInput'].forEach(id=>{
    const el=document.getElementById(id);if(!el)return;
    el.addEventListener('focus',()=>{refreshPaused=true;updateCountdown();});
    el.addEventListener('blur',()=>{refreshPaused=!!document.querySelector('.overlay.open');updateCountdown();});
});
autoRefreshTimer=setInterval(()=>doAutoRefresh(),AUTO_REFRESH_INTERVAL*1000);
startCountdown();
</script>
</body>
</html>