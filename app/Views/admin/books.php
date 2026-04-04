<?php
/**
 * Views/admin/books.php — Restyled to match SK dashboard design system
 * Design: Plus Jakarta Sans · JetBrains Mono · Indigo palette · dark mode · card/sidebar layout
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
  <title>Library Management | Admin</title>
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <meta name="csrf-name"  content="<?= csrf_token() ?>">
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#3730a3">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent }

    :root {
      --indigo: #3730a3;
      --indigo-mid: #4338ca;
      --indigo-light: #eef2ff;
      --indigo-border: #c7d2fe;
      --bg: #f0f2f9;
      --card: #ffffff;
      --font: 'Plus Jakarta Sans', system-ui, sans-serif;
      --mono: 'JetBrains Mono', monospace;
      --shadow-sm: 0 1px 4px rgba(15,23,42,.07), 0 1px 2px rgba(15,23,42,.04);
      --shadow-md: 0 4px 16px rgba(15,23,42,.09), 0 2px 4px rgba(15,23,42,.04);
      --shadow-lg: 0 12px 40px rgba(15,23,42,.12), 0 4px 8px rgba(15,23,42,.06);
      --r-sm: 10px; --r-md: 14px; --r-lg: 20px; --r-xl: 24px;
      --sidebar-w: 268px;
      --ease: .18s cubic-bezier(.4,0,.2,1);
      --mob-nav-h: 60px;
      --mob-nav-total: calc(var(--mob-nav-h) + env(safe-area-inset-bottom, 0px));
    }

    html { height: 100%; height: 100dvh; font-size: 16px }
    body { font-family: var(--font); background: var(--bg); color: #0f172a; display: flex; height: 100vh; height: 100dvh; overflow: hidden; -webkit-font-smoothing: antialiased }

    /* ── Sidebar ── */
    .sidebar { width: var(--sidebar-w); flex-shrink: 0; padding: 18px 14px; height: 100vh; height: 100dvh; display: flex; flex-direction: column }
    .sidebar-inner { background: var(--card); border-radius: var(--r-xl); border: 1px solid rgba(99,102,241,.1); height: 100%; display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-md) }
    .sidebar-top { padding: 22px 18px 16px; border-bottom: 1px solid rgba(99,102,241,.07) }
    .brand-tag { font-size: .6rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px }
    .brand-name { font-size: 1.35rem; font-weight: 800; color: #0f172a; letter-spacing: -.03em; line-height: 1.1 }
    .brand-name em { font-style: normal; color: var(--indigo) }
    .brand-sub { font-size: .7rem; color: #94a3b8; margin-top: 3px }
    .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 3px; scrollbar-width: none }
    .sidebar-nav::-webkit-scrollbar { display: none }
    .nav-section-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #cbd5e1; padding: 10px 10px 5px }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #64748b; text-decoration: none; transition: all var(--ease) }
    .nav-link:hover { background: var(--indigo-light); color: var(--indigo) }
    .nav-link.active { background: var(--indigo); color: #fff; box-shadow: 0 4px 14px rgba(55,48,163,.32) }
    .nav-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .9rem }
    .nav-link:not(.active) .nav-icon { background: #f1f5f9 }
    .nav-link:hover:not(.active) .nav-icon { background: #e0e7ff }
    .nav-link.active .nav-icon { background: rgba(255,255,255,.15) }
    .nav-badge { margin-left: auto; background: rgba(245,158,11,.18); color: #d97706; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 999px }
    .sidebar-footer { padding: 10px 10px 12px; border-top: 1px solid rgba(99,102,241,.07) }
    .logout-link { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r-sm); font-size: .85rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all var(--ease) }
    .logout-link:hover { background: #fef2f2; color: #dc2626 }
    .logout-link:hover .nav-icon { background: #fee2e2 }

    /* ── Mobile Nav ── */
    .mobile-nav-pill { display: none; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; background: white; border-top: 1px solid rgba(99,102,241,.1); height: var(--mob-nav-total); z-index: 200; box-shadow: 0 -4px 20px rgba(55,48,163,.1) }
    .mobile-scroll-container { display: flex; justify-content: space-evenly; align-items: center; height: var(--mob-nav-h); width: 100% }
    .mob-nav-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 48px; border-radius: 14px; cursor: pointer; text-decoration: none; color: #64748b; position: relative; transition: background .15s, color .15s; font-size: .82rem; touch-action: manipulation }
    .mob-nav-item:hover { background: var(--indigo-light); color: var(--indigo) }
    .mob-nav-item.active { background: var(--indigo-light); color: var(--indigo) }
    .mob-nav-item.active::after { content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; background: var(--indigo); border-radius: 50% }
    .mob-logout:hover { background: #fef2f2; color: #dc2626 }

    @media(max-width:1023px) {
      .sidebar { display: none !important }
      .mobile-nav-pill { display: flex !important }
      .main-area { padding-bottom: calc(var(--mob-nav-total) + 16px) !important }
    }
    @media(min-width:1024px) {
      .sidebar { display: flex !important }
      .mobile-nav-pill { display: none !important }
    }

    /* ── Main ── */
    .main-area { flex: 1; min-width: 0; padding: 24px 28px 40px; height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; overscroll-behavior-y: contain }
    @media(max-width:1023px) { .main-area { scrollbar-width: none } .main-area::-webkit-scrollbar { display: none } }
    @media(min-width:1024px) { .main-area::-webkit-scrollbar { width: 4px } .main-area::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px } }
    @media(max-width:639px) { .main-area { padding: 14px 12px 0 } }

    /* ── Topbar ── */
    .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap }
    .greeting-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px }
    .greeting-name { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; line-height: 1.1 }
    .greeting-date { font-size: .78rem; color: #94a3b8; margin-top: 4px; font-weight: 500 }
    @media(max-width:639px) { .greeting-name { font-size: 1.35rem } }
    .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-top: 4px; flex-wrap: wrap }
    .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); touch-action: manipulation }
    .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo) }
    .action-btn { display: flex; align-items: center; gap: 7px; padding: 10px 18px; background: var(--indigo); color: #fff; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); text-decoration: none; box-shadow: 0 4px 12px rgba(55,48,163,.28) }
    .action-btn:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(55,48,163,.35) }
    .action-btn-outline { display: flex; align-items: center; gap: 7px; padding: 10px 18px; background: white; color: var(--indigo); border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: 1px solid var(--indigo-border); cursor: pointer; font-family: var(--font); transition: all var(--ease); text-decoration: none }
    .action-btn-outline:hover { background: var(--indigo-light) }

    /* ── Section labels ── */
    .section-label, .section-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; display: flex; align-items: center; gap: 8px }
    .section-label::before, .section-lbl::before { content: ''; display: inline-block; width: 3px; height: 14px; border-radius: 2px; background: var(--indigo); flex-shrink: 0 }

    /* ── Cards ── */
    .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm) }
    .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .9rem }
    .card-title { font-size: .9rem; font-weight: 700; color: #0f172a; letter-spacing: -.01em }
    .card-sub { font-size: .7rem; color: #94a3b8; margin-top: 2px }

    /* ── Stat cards ── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; margin-bottom: 20px }
    .stat-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 18px 20px; box-shadow: var(--shadow-sm); transition: transform var(--ease), box-shadow var(--ease); cursor: pointer }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md) }
    .stat-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8 }
    .stat-num { font-size: 2rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -.04em; font-family: var(--mono) }
    .stat-hint { font-size: .72rem; color: #94a3b8; margin-top: 4px }
    .prog-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden }
    .prog-fill { height: 100%; border-radius: 999px; transition: width .8s cubic-bezier(.34,1.56,.64,1) }
    @media(max-width:639px) { .stats-grid { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px } .stat-card { padding: 14px 16px } .stat-num { font-size: 1.6rem } }

    /* ── Tab bar ── */
    .tab-bar { display: flex; background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 5px; gap: 4px; width: fit-content; margin-bottom: 20px; box-shadow: var(--shadow-sm) }
    .tab-btn { padding: 8px 18px; border-radius: var(--r-sm); font-size: .82rem; font-weight: 700; border: none; background: transparent; color: #64748b; cursor: pointer; font-family: var(--font); transition: all var(--ease); display: flex; align-items: center; gap: 7px }
    .tab-btn.active { background: var(--indigo); color: #fff; box-shadow: 0 3px 10px rgba(55,48,163,.28) }
    .tab-btn:not(.active):hover { background: var(--indigo-light); color: var(--indigo) }
    .tab-badge { background: rgba(245,158,11,.18); color: #d97706; font-size: .6rem; font-weight: 700; padding: 2px 6px; border-radius: 999px }
    .tab-btn.active .tab-badge { background: rgba(255,255,255,.22); color: #fff }

    /* ── Search bar ── */
    .search-wrap { position: relative; flex: 1; min-width: 180px }
    .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: .75rem; pointer-events: none }
    .search-input { width: 100%; padding: 10px 12px 10px 34px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.15); font-size: .85rem; font-family: var(--font); background: #f8fafc; color: #0f172a; transition: all var(--ease); outline: none }
    .search-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08) }

    /* ── Filter pills ── */
    .filter-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 16px }
    .fpill { padding: 5px 14px; border-radius: 999px; font-size: .72rem; font-weight: 700; cursor: pointer; border: 1px solid transparent; transition: all var(--ease); background: #f1f5f9; color: #64748b; font-family: var(--font) }
    .fpill:hover { background: #e2e8f0 }
    .fpill.active { background: var(--indigo); color: #fff; border-color: var(--indigo) }
    .fpill.fp-pending.active { background: #fef3c7; color: #92400e; border-color: #fde68a }
    .fpill.fp-approved.active { background: #dcfce7; color: #166534; border-color: #bbf7d0 }
    .fpill.fp-returned.active { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe }
    .fpill.fp-rejected.active { background: #fee2e2; color: #991b1b; border-color: #fecaca }

    /* ── Status tags ── */
    .tag { display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px; border-radius: 999px; font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; white-space: nowrap }
    .tag-active, .tag-approved, .tag-returned { background: #dcfce7; color: #166534 }
    .tag-inactive, .tag-rejected { background: #fee2e2; color: #991b1b }
    .tag-pending { background: #fef3c7; color: #92400e }
    .tag-rag-yes { background: #ede9fe; color: #5b21b6; cursor: pointer }
    .tag-rag-no { background: #f1f5f9; color: #94a3b8; cursor: default }

    /* ── Call number badge ── */
    .call-badge { display: inline-flex; align-items: center; gap: 4px; background: #ede9fe; color: #5b21b6; font-size: .68rem; font-weight: 700; font-family: var(--mono); padding: .2rem .55rem; border-radius: 7px; letter-spacing: .02em; white-space: nowrap }

    /* ── Table ── */
    .tbl-wrap { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); overflow: hidden; box-shadow: var(--shadow-sm) }
    table { width: 100%; border-collapse: collapse; font-size: .82rem; min-width: 0 }
    thead { background: #f8fafc; border-bottom: 2px solid rgba(99,102,241,.08) }
    thead th { padding: .6rem .85rem; text-align: left; font-weight: 700; font-size: .62rem; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; white-space: nowrap }
    tbody tr { border-bottom: 1px solid rgba(99,102,241,.04); transition: background var(--ease) }
    tbody tr:last-child { border-bottom: none }
    tbody tr:hover { background: #f8fafc }
    td { padding: .65rem .85rem; vertical-align: middle }

    /* ── Copies control ── */
    .copies-ctl { display: inline-flex; align-items: center; gap: 5px }
    .cpy-btn { width: 22px; height: 22px; border-radius: 6px; border: 1px solid rgba(99,102,241,.15); background: #f8fafc; color: #475569; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .15s; padding: 0; line-height: 1 }
    .cpy-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo) }
    .cpy-btn:active { transform: scale(.9) }
    .cpy-val { font-size: .82rem; font-weight: 800; color: #0f172a; min-width: 18px; text-align: center; font-family: var(--mono) }
    .cpy-total { font-size: .68rem; color: #94a3b8; white-space: nowrap }

    /* ── Action pair ── */
    .act-pair { display: flex; flex-direction: column; gap: 4px }
    .act-btn { display: flex; align-items: center; justify-content: center; gap: 4px; padding: 5px 10px; border-radius: 7px; font-size: .7rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); text-align: center; width: 100% }
    .act-edit { background: #f1f5f9; color: #475569 }
    .act-edit:hover { background: var(--indigo-light); color: var(--indigo) }
    .act-del { background: #fef2f2; color: #dc2626 }
    .act-del:hover { background: #fee2e2 }
    .act-approve { background: #dcfce7; color: #166534 }
    .act-approve:hover { background: #bbf7d0 }
    .act-reject { background: #fef2f2; color: #dc2626 }
    .act-reject:hover { background: #fee2e2 }
    .act-return { background: #f1f5f9; color: #475569 }
    .act-return:hover { background: #e2e8f0 }

    /* ── Mobile book cards ── */
    .book-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 16px 18px; box-shadow: var(--shadow-sm); transition: transform var(--ease), box-shadow var(--ease) }
    .book-card:hover { transform: translateY(-1px); box-shadow: var(--shadow-md) }
    .book-letter { width: 36px; height: 36px; border-radius: 10px; background: var(--indigo-light); color: var(--indigo); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0 }
    .book-meta-chip { display: inline-flex; align-items: center; gap: 3px; font-size: .65rem; font-weight: 600; color: #64748b; background: #f8fafc; border: 1px solid rgba(99,102,241,.08); border-radius: 6px; padding: .1rem .45rem; white-space: nowrap }
    .book-copies-row { display: flex; align-items: center; justify-content: space-between; padding: .6rem 0; border-bottom: 1px solid rgba(99,102,241,.05) }
    .book-card-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding-top: .6rem }
    .borrow-card { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 16px 18px; box-shadow: var(--shadow-sm) }

    /* ── Empty state ── */
    .empty-state { background: var(--card); border: 1px solid rgba(99,102,241,.08); border-radius: var(--r-lg); padding: 48px 24px; text-align: center; box-shadow: var(--shadow-sm) }

    /* ── Pagination ── */
    .page-btn { min-width: 34px; height: 34px; padding: 0 8px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.12); background: var(--card); color: #475569; font-size: .8rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all var(--ease); font-family: var(--font) }
    .page-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo) }
    .page-btn.active { background: var(--indigo); color: #fff; border-color: var(--indigo); box-shadow: 0 3px 10px rgba(55,48,163,.28) }
    .page-btn:disabled { opacity: .4; cursor: not-allowed }

    /* ── Modals ── */
    .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.52); backdrop-filter: blur(6px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center }
    .modal-back.show { display: flex; animation: fadeIn .15s ease }
    .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 520px; padding: 24px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .2s ease; box-shadow: var(--shadow-lg) }
    .modal-card.wide { max-width: 600px }
    .modal-card.sm { max-width: 420px }
    .modal-card::-webkit-scrollbar { width: 4px }
    .modal-card::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px }
    .modal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid rgba(99,102,241,.07) }
    .modal-title-lbl { font-size: .6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 3px }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; letter-spacing: -.02em }
    .modal-close { width: 36px; height: 36px; border-radius: var(--r-sm); background: #f1f5f9; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all var(--ease) }
    .modal-close:hover { background: #fee2e2; color: #dc2626 }
    @media(max-width:479px) { .modal-back { padding: .75rem } .modal-card { padding: 18px 16px; border-radius: var(--r-lg) } }

    /* ── Form inputs ── */
    .form-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #94a3b8; display: block; margin-bottom: 6px }
    .form-input { width: 100%; padding: .65rem .9rem; background: #f8fafc; border: 1.5px solid rgba(99,102,241,.12); border-radius: var(--r-sm); font-family: var(--font); font-size: .875rem; color: #0f172a; outline: none; transition: all var(--ease) }
    .form-input:focus { border-color: #818cf8; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.08) }
    textarea.form-input { resize: vertical; min-height: 90px }
    .form-input.filled { border-color: #22c55e; background: #f0fdf4 }

    /* ── Modal action buttons ── */
    .modal-submit { width: 100%; padding: 12px; background: var(--indigo); color: white; border-radius: var(--r-sm); font-weight: 700; font-size: .88rem; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); box-shadow: 0 3px 10px rgba(55,48,163,.25) }
    .modal-submit:hover { background: #312e81 }
    .modal-cancel { width: 100%; padding: 12px; background: #f8fafc; border-radius: var(--r-sm); font-weight: 700; font-size: .88rem; border: 1px solid rgba(99,102,241,.1); cursor: pointer; font-family: var(--font); transition: all var(--ease); color: #475569 }
    .modal-cancel:hover { background: #f1f5f9 }
    .modal-danger { width: 100%; padding: 12px; background: #ef4444; color: white; border-radius: var(--r-sm); font-weight: 700; font-size: .88rem; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease) }
    .modal-danger:hover { background: #dc2626 }

    /* ── AI / PDF upload ── */
    .drop-zone { border: 2px dashed rgba(99,102,241,.2); border-radius: var(--r-lg); padding: 2.5rem 1.5rem; text-align: center; background: var(--indigo-light); cursor: pointer; transition: all .2s; position: relative }
    .drop-zone:hover, .drop-zone.dragover { border-color: var(--indigo); background: #e0e7ff }
    .drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100% }
    .ai-progress-bar { height: 5px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin-top: 8px }
    .ai-progress-fill { height: 100%; background: linear-gradient(90deg, var(--indigo), #818cf8, var(--indigo)); background-size: 200% 100%; border-radius: 999px; animation: shimmer 1.4s infinite; width: 0%; transition: width .4s ease }
    .step-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 800; flex-shrink: 0; transition: all .3s }
    .step-dot.done { background: var(--indigo); color: white }
    .step-dot.active { background: var(--indigo-light); color: var(--indigo); border: 2px solid var(--indigo) }
    .step-dot.pending { background: #f1f5f9; color: #94a3b8 }
    .step-line { flex: 1; height: 2px; border-radius: 999px; transition: background .3s }
    .step-line.done { background: var(--indigo) }
    .step-line.pending { background: #e2e8f0 }
    .field-badge-ai { display: inline-flex; align-items: center; gap: 3px; font-size: .6rem; font-weight: 700; padding: .15rem .45rem; border-radius: 999px; background: var(--indigo-light); color: var(--indigo); margin-left: 5px; vertical-align: middle }

    /* ── RAG preview ── */
    .rag-preview { background: #f8fafc; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-md); padding: 1rem; font-size: .8rem; font-family: var(--mono); white-space: pre-wrap; max-height: 260px; overflow-y: auto; color: #3730a3 }

    /* ── Flash messages ── */
    .flash-ok { margin-bottom: 20px; padding: 14px 18px; background: #f0fdf4; border: 1px solid #86efac; color: #14532d; font-size: .85rem; font-weight: 700; border-radius: var(--r-md); display: flex; align-items: center; gap: 10px }
    .flash-err { margin-bottom: 20px; padding: 14px 18px; background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; font-size: .85rem; font-weight: 700; border-radius: var(--r-md); display: flex; align-items: center; gap: 10px }
    .pending-pill { display: flex; align-items: center; gap: 6px; background: #fef3c7; border: 1px solid #fde68a; color: #92400e; padding: 8px 14px; border-radius: var(--r-sm); font-size: .78rem; font-weight: 700 }

    /* ── Animations ── */
    @keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px) } to { opacity: 1; transform: none } }
    @keyframes shimmer { 0% { background-position: 200% 0 } 100% { background-position: -200% 0 } }
    @keyframes spin { to { transform: rotate(360deg) } }
    .fade-up { animation: slideUp .4s ease both }
    .fade-up-1 { animation: slideUp .45s .05s ease both }
    .fade-up-2 { animation: slideUp .45s .1s ease both }
    .fade-up-3 { animation: slideUp .45s .15s ease both }

    #adminDebugPanel { display: none; margin-top: 10px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: var(--r-sm); font-size: .72rem; font-family: var(--mono); color: #991b1b; word-break: break-all; white-space: pre-wrap; max-height: 120px; overflow-y: auto }

    /* ════ DARK MODE ════ */
    body.dark {
      --bg: #060e1e; --card: #0b1628;
      --indigo-light: rgba(55,48,163,.12);
      --indigo-border: rgba(99,102,241,.25);
      color: #e2eaf8
    }
    body.dark .sidebar-inner { background: #0b1628; border-color: rgba(99,102,241,.12) }
    body.dark .sidebar-top, body.dark .sidebar-footer { border-color: rgba(99,102,241,.1) }
    body.dark .brand-name { color: #e2eaf8 }
    body.dark .brand-tag, body.dark .brand-sub, body.dark .nav-section-lbl { color: #1e3a5f }
    body.dark .nav-link { color: #7fb3e8 }
    body.dark .nav-link:hover { background: rgba(99,102,241,.12); color: #a5b4fc }
    body.dark .nav-link:not(.active) .nav-icon { background: rgba(99,102,241,.1) }
    body.dark .nav-link:hover:not(.active) .nav-icon { background: rgba(99,102,241,.2) }
    body.dark .user-card { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.2) }
    body.dark .logout-link { color: #4a6fa5 }
    body.dark .logout-link:hover { background: rgba(239,68,68,.1); color: #f87171 }
    body.dark .logout-link:hover .nav-icon { background: rgba(239,68,68,.12) }
    body.dark .mobile-nav-pill { background: #0b1628; border-color: rgba(99,102,241,.18) }
    body.dark .mob-nav-item { color: #7fb3e8 }
    body.dark .mob-nav-item.active { background: rgba(99,102,241,.18) }
    body.dark .icon-btn { background: #0b1628; border-color: rgba(99,102,241,.15); color: #7fb3e8 }
    body.dark .icon-btn:hover { background: rgba(99,102,241,.12); border-color: rgba(99,102,241,.3); color: #a5b4fc }
    body.dark .action-btn-outline { background: #0b1628; color: #818cf8; border-color: rgba(99,102,241,.25) }
    body.dark .action-btn-outline:hover { background: rgba(99,102,241,.12) }
    body.dark .greeting-eyebrow, body.dark .greeting-date { color: #1e3a5f }
    body.dark .greeting-name { color: #e2eaf8 }
    body.dark .card { background: #0b1628; border-color: rgba(99,102,241,.1) }
    body.dark .card-title { color: #e2eaf8 }
    body.dark .stat-card { background: #0b1628; border-color: rgba(99,102,241,.1) }
    body.dark .stat-num { color: #e2eaf8 }
    body.dark .stat-lbl, body.dark .stat-hint { color: #4a6fa5 }
    body.dark .prog-bar { background: rgba(99,102,241,.15) }
    body.dark .tab-bar { background: #0b1628; border-color: rgba(99,102,241,.12) }
    body.dark .tab-btn { color: #7fb3e8 }
    body.dark .tab-btn:not(.active):hover { background: rgba(99,102,241,.12); color: #a5b4fc }
    body.dark .search-input { background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8 }
    body.dark .search-input:focus { border-color: #818cf8; background: #0b1628 }
    body.dark .fpill { background: #101e35; color: #7fb3e8 }
    body.dark .fpill:hover { background: #1a2a42 }
    body.dark .tbl-wrap { background: #0b1628; border-color: rgba(99,102,241,.1) }
    body.dark thead { background: #0f1a2e }
    body.dark tbody tr { border-color: rgba(99,102,241,.05) }
    body.dark tbody tr:hover { background: #101e35 }
    body.dark .cpy-btn { background: #101e35; border-color: rgba(99,102,241,.2); color: #7fb3e8 }
    body.dark .cpy-btn:hover { background: rgba(99,102,241,.18) }
    body.dark .cpy-val { color: #e2eaf8 }
    body.dark .cpy-total { color: #4a6fa5 }
    body.dark .act-edit { background: #101e35; color: #7fb3e8 }
    body.dark .act-edit:hover { background: rgba(99,102,241,.18); color: #a5b4fc }
    body.dark .act-del { background: rgba(239,68,68,.1); color: #f87171 }
    body.dark .act-del:hover { background: rgba(239,68,68,.18) }
    body.dark .act-approve { background: rgba(22,163,74,.15); color: #4ade80 }
    body.dark .act-reject { background: rgba(239,68,68,.1); color: #f87171 }
    body.dark .act-return { background: #101e35; color: #7fb3e8 }
    body.dark .book-card, body.dark .borrow-card { background: #0b1628; border-color: rgba(99,102,241,.1) }
    body.dark .book-letter { background: rgba(55,48,163,.2); color: #818cf8 }
    body.dark .book-meta-chip { background: #101e35; border-color: rgba(99,102,241,.12); color: #7fb3e8 }
    body.dark .book-copies-row { border-color: rgba(99,102,241,.08) }
    body.dark .call-badge { background: rgba(92,33,182,.2); color: #a78bfa }
    body.dark .tag-rag-no { background: #101e35; color: #4a6fa5 }
    body.dark .empty-state { background: #0b1628; border-color: rgba(99,102,241,.1) }
    body.dark .page-btn { background: #0b1628; border-color: rgba(99,102,241,.15); color: #7fb3e8 }
    body.dark .page-btn:hover { background: rgba(99,102,241,.12) }
    body.dark .modal-card { background: #0b1628; color: #e2eaf8 }
    body.dark .modal-head { border-color: rgba(99,102,241,.1) }
    body.dark .modal-title { color: #e2eaf8 }
    body.dark .modal-close { background: #101e35; color: #7fb3e8 }
    body.dark .modal-close:hover { background: rgba(239,68,68,.12); color: #f87171 }
    body.dark .form-lbl { color: #4a6fa5 }
    body.dark .form-input { background: #101e35; border-color: rgba(99,102,241,.18); color: #e2eaf8 }
    body.dark .form-input:focus { border-color: #818cf8; background: #0b1628 }
    body.dark .form-input.filled { border-color: #22c55e; background: rgba(22,163,74,.08) }
    body.dark .modal-cancel { background: #101e35; border-color: rgba(99,102,241,.12); color: #7fb3e8 }
    body.dark .modal-cancel:hover { background: #1a2a42 }
    body.dark .drop-zone { background: rgba(55,48,163,.1); border-color: rgba(99,102,241,.25) }
    body.dark .drop-zone:hover, body.dark .drop-zone.dragover { background: rgba(55,48,163,.2); border-color: var(--indigo) }
    body.dark .step-dot.pending { background: #101e35; color: #4a6fa5 }
    body.dark .step-line.pending { background: #101e35 }
    body.dark .ai-progress-bar { background: #101e35 }
    body.dark .rag-preview { background: #101e35; border-color: rgba(99,102,241,.2); color: #a5b4fc }
    body.dark .pending-pill { background: rgba(180,83,9,.2); border-color: rgba(180,83,9,.3); color: #fcd34d }
    body.dark .flash-ok { background: rgba(20,83,45,.2); border-color: rgba(134,239,172,.2); color: #86efac }
    body.dark .flash-err { background: rgba(153,27,27,.2); border-color: rgba(252,165,165,.2); color: #fca5a5 }
    body.dark .section-label::before, body.dark .section-lbl::before { background: #818cf8 }
    body.dark .section-label, body.dark .section-lbl { color: #4a6fa5 }
  </style>
</head>
<body>

<?php
$page   = 'books';
$prefix = str_starts_with(current_url(), base_url('admin')) ? 'admin' : 'sk';
if ($prefix === 'admin') {
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
    ['url'=>'/admin/profile',             'icon'=>'fa-user',            'label'=>'Profile',         'key'=>'profile'],
  ];
} else {
  $navItems = [
    ['url'=>'/sk/dashboard',            'icon'=>'fa-house',           'label'=>'Dashboard',       'key'=>'dashboard'],
    ['url'=>'/sk/new-reservation',      'icon'=>'fa-plus',            'label'=>'New Reservation', 'key'=>'new-reservation'],
    ['url'=>'/sk/reservations',         'icon'=>'fa-calendar',        'label'=>'Reservations',    'key'=>'reservations'],
    ['url'=>'/sk/books',                'icon'=>'fa-book-open',       'label'=>'Library',         'key'=>'books'],
    ['url'=>'/sk/claimed-reservations', 'icon'=>'fa-clipboard-check', 'label'=>'Claimed',         'key'=>'claimed-reservations'],
    ['url'=>'/sk/user-requests',        'icon'=>'fa-users',           'label'=>'User Requests',   'key'=>'user-requests'],
    ['url'=>'/sk/activity-logs',        'icon'=>'fa-list',            'label'=>'Activity Logs',   'key'=>'activity-logs'],
    ['url'=>'/sk/scanner',              'icon'=>'fa-qrcode',          'label'=>'Scanner',         'key'=>'scanner'],
    ['url'=>'/sk/profile',              'icon'=>'fa-user',            'label'=>'Profile',         'key'=>'profile'],
  ];
}
$books       = $books      ?? [];
$borrowings  = $borrowings ?? [];
$totalBooks     = count($books);
$activeBooks    = count(array_filter($books, fn($b) => ($b['status'] ?? '') === 'active'));
$ragReady       = count(array_filter($books, fn($b) => !empty($b['preface'])));
$totalBorrows   = count($borrowings);
$pendingBorrows = count(array_filter($borrowings, fn($b) => ($b['status'] ?? '') === 'pending'));
$avatarLetter   = strtoupper(mb_substr(trim($user_name ?? 'A'), 0, 1));
?>

<!-- ════ DELETE CONFIRM MODAL ════ -->
<div id="deleteModal" class="modal-back" onclick="if(event.target===this)closeModal('deleteModal')">
  <div class="modal-card sm">
    <div style="text-align:center;margin-bottom:16px;">
      <div style="width:52px;height:52px;background:#fef2f2;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
        <i class="fa-solid fa-trash" style="color:#ef4444;font-size:1.1rem;"></i>
      </div>
      <div class="card-title" style="margin-bottom:4px;">Delete Book?</div>
      <div class="card-sub">This will permanently remove:</div>
      <div id="deleteBookTitle" style="margin:12px 0;font-weight:700;font-size:.9rem;color:#0f172a;background:#f8fafc;border:1px solid rgba(99,102,241,.08);border-radius:var(--r-sm);padding:10px 14px;">—</div>
      <div style="font-size:.72rem;color:#94a3b8;font-weight:500;">Borrowing history will also be removed. This cannot be undone.</div>
    </div>
    <div style="display:flex;gap:10px;margin-top:8px;">
      <button onclick="closeModal('deleteModal')" class="modal-cancel" style="flex:1;">Cancel</button>
      <form id="deleteForm" method="post" action="" style="flex:1;">
        <?= csrf_field() ?>
        <button type="submit" class="modal-danger" style="width:100%;"><i class="fa-solid fa-trash" style="font-size:.75rem;margin-right:5px;"></i>Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<!-- ════ ADD BOOK MODAL ════ -->
<div id="addModal" class="modal-back" onclick="if(event.target===this)closeModal('addModal')">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div class="modal-title-lbl">New Entry</div>
        <div class="modal-title">Add New Book</div>
      </div>
      <button onclick="closeModal('addModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <form method="post" action="/<?=$prefix?>/books/store">
      <?= csrf_field() ?>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Title *</label><input class="form-input" name="title" required placeholder="Book title"></div>
        <div><label class="form-lbl">Author *</label><input class="form-input" name="author" required placeholder="Author name"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Genre</label><input class="form-input" name="genre" placeholder="e.g. Fiction"></div>
        <div><label class="form-lbl">Published Year</label><input class="form-input" name="published_year" type="number" min="1000" max="2099" placeholder="2024"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">ISBN</label><input class="form-input" name="isbn" placeholder="Optional"></div>
        <div><label class="form-lbl">Total Copies</label><input class="form-input" name="total_copies" type="number" min="1" value="1"></div>
      </div>
      <div style="margin-bottom:14px;"><label class="form-lbl">Call Number <span style="color:#7c3aed;text-transform:none;letter-spacing:0;font-weight:500;">(shelf location code)</span></label><input class="form-input" style="font-family:var(--mono);" name="call_number" placeholder="e.g. 823.914"></div>
      <div style="margin-bottom:20px;"><label class="form-lbl">Preface / Description <span style="color:var(--indigo);text-transform:none;letter-spacing:0;font-weight:500;">(powers AI suggestions)</span></label><textarea class="form-input" name="preface" rows="3" placeholder="Describe the book…"></textarea></div>
      <div style="display:flex;gap:10px;">
        <button type="button" onclick="closeModal('addModal')" class="modal-cancel" style="flex:1;">Cancel</button>
        <button type="submit" class="modal-submit" style="flex:1;"><i class="fa-solid fa-plus" style="font-size:.75rem;margin-right:5px;"></i>Add Book</button>
      </div>
    </form>
  </div>
</div>

<!-- ════ PDF UPLOAD + AI EXTRACTION MODAL ════ -->
<div id="pdfModal" class="modal-back" onclick="if(event.target===this)closeModal('pdfModal')">
  <div class="modal-card wide">
    <div class="modal-head">
      <div style="display:flex;align-items:center;gap:12px;">
        <div class="card-icon" style="background:var(--indigo-light);"><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--indigo);font-size:.9rem;"></i></div>
        <div>
          <div class="modal-title-lbl">AI Extraction</div>
          <div class="modal-title">Upload PDF — AI extracts details</div>
        </div>
      </div>
      <button onclick="closeModal('pdfModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <div style="display:flex;align-items:center;gap:6px;margin-bottom:22px;">
      <div class="step-dot active" id="aStepDot1">1</div>
      <div class="step-line pending" id="aStepLine1"></div>
      <div class="step-dot pending" id="aStepDot2">2</div>
      <div class="step-line pending" id="aStepLine2"></div>
      <div class="step-dot pending" id="aStepDot3">3</div>
      <span style="font-size:.72rem;font-weight:600;color:#94a3b8;margin-left:8px;" id="aStepLabel">Upload PDF</span>
    </div>
    <!-- Step 1 -->
    <div id="aPdfStep1">
      <div class="drop-zone" id="aDropZone">
        <input type="file" id="aPdfFileInput" accept=".pdf" onchange="aHandlePdfFile(event)">
        <div class="card-icon" style="background:var(--indigo);width:48px;height:48px;border-radius:var(--r-md);margin:0 auto 12px;"><i class="fa-solid fa-file-pdf" style="color:white;font-size:1.1rem;"></i></div>
        <p style="font-weight:800;font-size:.9rem;color:#0f172a;margin-bottom:4px;">Drop your PDF here</p>
        <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-bottom:12px;">or click to browse files</p>
        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:white;border:1px solid rgba(99,102,241,.15);border-radius:var(--r-sm);font-size:.72rem;font-weight:700;color:#475569;"><i class="fa-solid fa-file-pdf" style="color:#ef4444;font-size:.75rem;"></i>PDF files only · Max 10MB</span>
      </div>
      <div style="margin-top:10px;display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-sm);">
        <i class="fa-solid fa-shield-halved" style="color:var(--indigo);font-size:.8rem;flex-shrink:0;"></i>
        <p style="font-size:.72rem;font-weight:600;color:#3730a3;">Text is extracted locally in your browser — only extracted text (not the PDF) is sent to the AI.</p>
      </div>
      <div id="aFilePreview" class="hidden" style="margin-top:12px;padding:12px 14px;background:#f8fafc;border-radius:var(--r-md);border:1px solid rgba(99,102,241,.08);display:flex;align-items:center;gap:12px;">
        <div class="card-icon" style="background:#fee2e2;"><i class="fa-solid fa-file-pdf" style="color:#ef4444;font-size:.9rem;"></i></div>
        <div style="flex:1;min-width:0;">
          <p id="aFilePreviewName" style="font-weight:700;font-size:.85rem;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></p>
          <p id="aFilePreviewSize" style="font-size:.7rem;color:#94a3b8;margin-top:2px;"></p>
        </div>
        <button onclick="aClearPdfFile()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.85rem;"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <button id="aExtractBtn" onclick="aExtractFromPdf()" disabled style="margin-top:16px;width:100%;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;font-size:.88rem;border:none;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:8px;transition:all var(--ease);box-shadow:0 3px 10px rgba(55,48,163,.25);">
        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:.8rem;"></i> Extract with AI
      </button>
    </div>
    <!-- Step 2 -->
    <div id="aPdfStep2" style="display:none;text-align:center;padding:24px 0;">
      <div class="card-icon" style="background:var(--indigo-light);width:52px;height:52px;border-radius:var(--r-md);margin:0 auto 14px;"><i class="fa-solid fa-robot" style="color:var(--indigo);font-size:1.2rem;"></i></div>
      <p style="font-weight:800;font-size:.9rem;color:#0f172a;margin-bottom:4px;">AI is reading your PDF…</p>
      <p style="font-size:.78rem;color:#94a3b8;font-weight:500;" id="aAiStatusText">Extracting text…</p>
      <div class="ai-progress-bar" style="max-width:280px;margin:12px auto 0;"><div class="ai-progress-fill" id="aAiProgressFill" style="width:10%"></div></div>
    </div>
    <!-- Step 3 -->
    <div id="aPdfStep3" style="display:none;">
      <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:#f0fdf4;border:1px solid #86efac;border-radius:var(--r-md);margin-bottom:18px;">
        <i class="fa-solid fa-circle-check" style="color:#22c55e;font-size:.9rem;"></i>
        <p style="font-size:.82rem;font-weight:700;color:#14532d;">AI extraction complete! Review and edit below, then save.</p>
      </div>
      <form method="post" action="/<?=$prefix?>/books/store" id="aPdfBookForm">
        <?= csrf_field() ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <div><label class="form-lbl">Title * <span class="field-badge-ai" id="aBadgeTitle" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="title" id="aPdfTitle" required placeholder="Book title"></div>
          <div><label class="form-lbl">Author * <span class="field-badge-ai" id="aBadgeAuthor" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="author" id="aPdfAuthor" required placeholder="Author name"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <div><label class="form-lbl">Genre <span class="field-badge-ai" id="aBadgeGenre" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="genre" id="aPdfGenre" placeholder="e.g. Fiction"></div>
          <div><label class="form-lbl">Year <span class="field-badge-ai" id="aBadgeYear" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="published_year" id="aPdfYear" type="number" min="1000" max="2099" placeholder="2024"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <div><label class="form-lbl">ISBN <span class="field-badge-ai" id="aBadgeIsbn" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="isbn" id="aPdfIsbn" placeholder="Optional"></div>
          <div><label class="form-lbl">Total Copies</label><input class="form-input" name="total_copies" id="aPdfCopies" type="number" min="1" value="1"></div>
        </div>
        <div style="margin-bottom:14px;"><label class="form-lbl">Call Number <span class="field-badge-ai" id="aBadgeCallNumber" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" style="font-family:var(--mono);" name="call_number" id="aPdfCallNumber" placeholder="e.g. 823.914"></div>
        <div style="margin-bottom:14px;"><label class="form-lbl">Preface / Description <span class="field-badge-ai" id="aBadgePreface" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span> <span style="color:var(--indigo);text-transform:none;letter-spacing:0;font-weight:500;font-size:.7rem;">(powers AI suggestions)</span></label><textarea class="form-input" name="preface" id="aPdfPreface" rows="3"></textarea></div>
        <div id="aAiConfidenceNote" class="hidden" style="margin-bottom:14px;padding:10px 14px;background:#fef3c7;border:1px solid #fde68a;border-radius:var(--r-sm);font-size:.75rem;font-weight:600;color:#92400e;"><i class="fa-solid fa-circle-info" style="margin-right:6px;"></i>Title or author wasn't detected — please fill those in before saving.</div>
        <div style="display:flex;gap:10px;">
          <button type="button" onclick="aResetPdfModal()" class="modal-cancel" style="flex:1;"><i class="fa-solid fa-rotate-left" style="font-size:.75rem;margin-right:5px;"></i>Try Another</button>
          <button type="submit" class="modal-submit" style="flex:1;"><i class="fa-solid fa-check" style="font-size:.75rem;margin-right:5px;"></i>Save Book</button>
        </div>
      </form>
    </div>
    <!-- Error -->
    <div id="aPdfStepError" style="display:none;text-align:center;padding:24px 0;">
      <div class="card-icon" style="background:#fef2f2;width:52px;height:52px;border-radius:var(--r-md);margin:0 auto 14px;"><i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:1.1rem;"></i></div>
      <p style="font-weight:800;font-size:.9rem;color:#0f172a;margin-bottom:4px;">Extraction Failed</p>
      <p style="font-size:.82rem;font-weight:700;color:#ef4444;margin-bottom:4px;" id="aPdfErrorText">Could not read the PDF.</p>
      <div id="adminDebugPanel"></div>
      <p style="font-size:.72rem;color:#94a3b8;margin:12px 0 20px;">Make sure the PDF has readable text (not a scanned image).</p>
      <button onclick="aResetPdfModal()" class="modal-cancel" style="display:inline-block;width:auto;padding:10px 24px;">Try Again</button>
    </div>
  </div>
</div>

<!-- ════ EDIT BOOK MODAL ════ -->
<div id="editModal" class="modal-back" onclick="if(event.target===this)closeModal('editModal')">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div class="modal-title-lbl">Edit Entry</div>
        <div class="modal-title">Edit Book</div>
      </div>
      <button onclick="closeModal('editModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <form method="post" id="editForm" action="">
      <?= csrf_field() ?>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Title *</label><input class="form-input" name="title" id="editTitle" required></div>
        <div><label class="form-lbl">Author *</label><input class="form-input" name="author" id="editAuthor" required></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Genre</label><input class="form-input" name="genre" id="editGenre"></div>
        <div><label class="form-lbl">Published Year</label><input class="form-input" name="published_year" id="editYear" type="number"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Total Copies</label><input class="form-input" name="total_copies" id="editCopies" type="number" min="1"></div>
        <div><label class="form-lbl">Status</label><select class="form-input" name="status" id="editStatus"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
      <div style="margin-bottom:14px;"><label class="form-lbl">Call Number</label><input class="form-input" style="font-family:var(--mono);" name="call_number" id="editCallNumber" placeholder="e.g. 823.914"></div>
      <div style="margin-bottom:20px;"><label class="form-lbl">Preface / Description <span style="color:var(--indigo);text-transform:none;letter-spacing:0;font-weight:500;font-size:.7rem;">(AI context)</span></label><textarea class="form-input" name="preface" id="editPreface" rows="3"></textarea></div>
      <div style="display:flex;gap:10px;">
        <button type="button" onclick="closeModal('editModal')" class="modal-cancel" style="flex:1;">Cancel</button>
        <button type="submit" class="modal-submit" style="flex:1;"><i class="fa-solid fa-check" style="font-size:.75rem;margin-right:5px;"></i>Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ════ RAG PREVIEW MODAL ════ -->
<div id="ragModal" class="modal-back" onclick="if(event.target===this)closeModal('ragModal')">
  <div class="modal-card wide">
    <div class="modal-head">
      <div>
        <div class="modal-title-lbl">AI Preview</div>
        <div class="modal-title">RAG Context Block</div>
      </div>
      <button onclick="closeModal('ragModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <p style="font-size:.72rem;color:#94a3b8;font-weight:600;margin-bottom:10px;">Exact context sent to the AI when this book is retrieved:</p>
    <div class="rag-preview" id="ragPreviewText"></div>
    <div style="display:flex;justify-content:flex-end;margin-top:16px;">
      <button onclick="closeModal('ragModal')" class="modal-cancel" style="width:auto;padding:10px 24px;">Close</button>
    </div>
  </div>
</div>

<!-- ════ SIDEBAR ════ -->
<aside class="sidebar">
  <div class="sidebar-inner">
    <div class="sidebar-top">
      <div class="brand-tag">Admin Portal</div>
      <div class="brand-name">Control<em>.</em></div>
      <div class="brand-sub">Library Management</div>
    </div>
    <div style="margin:12px 12px 0;background:var(--indigo-light);border-radius:var(--r-md);padding:12px 14px;border:1px solid var(--indigo-border);display:flex;align-items:center;gap:9px;">
      <div style="width:34px;height:34px;border-radius:50%;background:var(--indigo);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0;box-shadow:0 2px 8px rgba(55,48,163,.3);"><?= $avatarLetter ?></div>
      <div style="min-width:0;">
        <div style="font-size:.8rem;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($user_name ?? 'Administrator') ?></div>
        <div style="font-size:.68rem;color:#6366f1;font-weight:500;margin-top:1px;">Administrator</div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section-lbl">Menu</div>
      <?php foreach($navItems as $item):
        $active = ($page === $item['key']);
        $showBadge = ($item['key'] === 'books' && $pendingBorrows > 0);
      ?>
      <a href="<?=$item['url']?>" class="nav-link <?= $active ? 'active' : '' ?>">
        <div class="nav-icon"><i class="fa-solid <?=$item['icon']?>" style="font-size:.85rem;"></i></div>
        <?=$item['label']?>
        <?php if($showBadge): ?><span class="nav-badge"><?=$pendingBorrows?></span><?php endif; ?>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
      <a href="/logout" class="logout-link">
        <div class="nav-icon" style="background:rgba(239,68,68,.08);"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:.85rem;color:#f87171;"></i></div>
        Sign Out
      </a>
    </div>
  </div>
</aside>

<!-- ════ MOBILE NAV ════ -->
<nav class="mobile-nav-pill">
  <div class="mobile-scroll-container">
    <?php foreach($navItems as $item):
      $active = ($page === $item['key']);
      $showBadge = ($item['key'] === 'books' && $pendingBorrows > 0);
    ?>
    <a href="<?=$item['url']?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?=$item['label']?>">
      <i class="fa-solid <?=$item['icon']?>" style="font-size:1.1rem;"></i>
      <?php if($showBadge): ?><span style="position:absolute;top:6px;right:20%;background:#ef4444;color:white;font-size:.5rem;font-weight:700;width:14px;height:14px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white;"><?=$pendingBorrows > 9 ? '9+' : $pendingBorrows?></span><?php endif; ?>
    </a>
    <?php endforeach; ?>
    <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out">
      <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171;"></i>
    </a>
  </div>
</nav>

<!-- ════ MAIN ════ -->
<main class="main-area">

  <!-- Topbar -->
  <div class="topbar fade-up">
    <div>
      <div class="greeting-eyebrow">Admin Portal</div>
      <div class="greeting-name">Library <span style="color:var(--indigo);">Management</span></div>
      <div class="greeting-date"><?= date('l, F j, Y') ?></div>
    </div>
    <div class="topbar-right">
      <?php if($pendingBorrows > 0): ?>
      <div class="pending-pill"><i class="fa-solid fa-clock" style="font-size:.75rem;"></i><?=$pendingBorrows?> pending borrow<?=$pendingBorrows > 1 ? 's' : ''?></div>
      <?php endif; ?>
      <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
        <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
      </div>
      <button onclick="openModal('pdfModal')" class="action-btn-outline" style="font-size:.82rem;">
        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:.78rem;color:var(--indigo);"></i> Upload PDF
      </button>
      <button onclick="openModal('addModal')" class="action-btn">
        <i class="fa-solid fa-plus" style="font-size:.78rem;"></i> Add Book
      </button>
    </div>
  </div>

  <?php if(session()->getFlashdata('success')): ?>
  <div class="flash-ok fade-up"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
  <div class="flash-err fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- ── Stat Cards ── -->
  <p class="section-label fade-up-1">Library Overview</p>
  <div class="stats-grid fade-up-1">
    <div class="stat-card" onclick="switchTab('books')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#eef2ff;"><i class="fa-solid fa-book" style="color:var(--indigo);font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:var(--indigo);"><?=$activeBooks?> active</span>
      </div>
      <div class="stat-lbl">Total Books</div>
      <div class="stat-num"><?=$totalBooks?></div>
      <div class="stat-hint">in collection</div>
    </div>
    <div class="stat-card" onclick="switchTab('books')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#ede9fe;"><i class="fa-solid fa-wand-magic-sparkles" style="color:#7c3aed;font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:#7c3aed;"><?=$totalBooks>0?round($ragReady/$totalBooks*100):0?>%</span>
      </div>
      <div class="stat-lbl">AI Ready</div>
      <div class="stat-num" style="color:#7c3aed;"><?=$ragReady?></div>
      <div class="prog-bar" style="margin-top:10px;">
        <div class="prog-fill" style="width:<?=$totalBooks>0?round($ragReady/$totalBooks*100):0?>%;background:#7c3aed;"></div>
      </div>
    </div>
    <div class="stat-card" onclick="switchTab('borrowings')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#f3e8ff;"><i class="fa-solid fa-clock-rotate-left" style="color:#9333ea;font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:#9333ea;">all time</span>
      </div>
      <div class="stat-lbl">Total Borrows</div>
      <div class="stat-num" style="color:#9333ea;"><?=$totalBorrows?></div>
      <div class="stat-hint">borrowing requests</div>
    </div>
    <div class="stat-card" onclick="switchTab('borrowings');filterBorrowings('pending')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#fef3c7;"><i class="fa-regular fa-clock" style="color:#d97706;font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:#d97706;">need action</span>
      </div>
      <div class="stat-lbl">Pending</div>
      <div class="stat-num" style="<?=$pendingBorrows>0?'color:#d97706':''?>"><?=$pendingBorrows?></div>
      <div class="stat-hint">awaiting approval</div>
    </div>
  </div>

  <!-- ── Tab bar ── -->
  <div class="tab-bar fade-up-2">
    <button id="tabBooks" class="tab-btn active" onclick="switchTab('books')">
      <i class="fa-solid fa-book" style="font-size:.8rem;"></i> Books Catalog
    </button>
    <button id="tabBorrowings" class="tab-btn" onclick="switchTab('borrowings')">
      <i class="fa-solid fa-clock-rotate-left" style="font-size:.8rem;"></i> Borrowings
      <?php if($pendingBorrows > 0): ?><span class="tab-badge"><?=$pendingBorrows?></span><?php endif; ?>
    </button>
  </div>

  <!-- ════ BOOKS PANE ════ -->
  <div id="paneBooks" class="fade-up-2">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:16px;">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input id="bookSearch" type="text" class="search-input" placeholder="Search title, author, genre, call number…" oninput="applyFilter()">
      </div>
      <span id="resultCount" style="display:none;font-size:.72rem;font-weight:700;color:#94a3b8;"></span>
      <!-- Mobile-only add buttons -->
      <div class="lg:hidden" style="display:flex;gap:8px;">
        <button onclick="openModal('pdfModal')" class="action-btn-outline" style="font-size:.8rem;padding:8px 12px;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.75rem;"></i></button>
        <button onclick="openModal('addModal')" class="action-btn" style="font-size:.8rem;padding:8px 14px;"><i class="fa-solid fa-plus" style="font-size:.75rem;"></i> Add</button>
      </div>
    </div>

    <?php if(empty($books)): ?>
    <div class="empty-state">
      <div class="card-icon" style="background:#eef2ff;width:48px;height:48px;border-radius:var(--r-md);margin:0 auto 12px;"><i class="fa-solid fa-book-open" style="color:var(--indigo);font-size:1.1rem;"></i></div>
      <div class="card-title" style="margin-bottom:6px;">No books yet</div>
      <div class="card-sub" style="margin-bottom:20px;">Add the first book manually or upload a PDF.</div>
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <button onclick="openModal('pdfModal')" class="action-btn-outline" style="font-size:.82rem;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.78rem;"></i> Upload PDF</button>
        <button onclick="openModal('addModal')" class="action-btn" style="font-size:.82rem;"><i class="fa-solid fa-plus" style="font-size:.78rem;"></i> Add Manually</button>
      </div>
    </div>
    <?php else: ?>

    <!-- Desktop table -->
    <div class="tbl-wrap hidden md:block">
      <div style="overflow-x:auto;">
        <table id="booksTable">
          <thead>
            <tr>
              <th style="width:32px;">#</th>
              <th style="min-width:150px;">Title / Author</th>
              <th style="width:90px;">Call #</th>
              <th style="width:90px;">ISBN</th>
              <th style="width:90px;">Genre</th>
              <th style="width:52px;">Year</th>
              <th style="width:110px;">Copies</th>
              <th style="width:60px;">RAG</th>
              <th style="width:72px;">Status</th>
              <th style="width:100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($books as $i => $b): ?>
          <tr data-search="<?= strtolower(htmlspecialchars(($b['title']??'').' '.($b['author']??'').' '.($b['genre']??'').' '.($b['call_number']??''), ENT_QUOTES, 'UTF-8')) ?>">
            <td style="color:#94a3b8;font-weight:700;font-size:.72rem;font-family:var(--mono);"><?=$i+1?></td>
            <td>
              <p style="font-weight:700;font-size:.85rem;color:#0f172a;max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($b['title']??'', ENT_QUOTES, 'UTF-8') ?></p>
              <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($b['author']??'', ENT_QUOTES, 'UTF-8') ?></p>
            </td>
            <td>
              <?php if(!empty($b['call_number'])): ?>
              <span class="call-badge"><?= htmlspecialchars($b['call_number'], ENT_QUOTES, 'UTF-8') ?></span>
              <?php else: ?><span style="color:#94a3b8;font-size:.72rem;">—</span><?php endif; ?>
            </td>
            <td style="font-size:.72rem;color:#64748b;font-family:var(--mono);"><?= htmlspecialchars(!empty($b['isbn']) ? $b['isbn'] : '—', ENT_QUOTES, 'UTF-8') ?></td>
            <td style="font-size:.82rem;color:#475569;font-weight:500;"><?= htmlspecialchars($b['genre']??'—', ENT_QUOTES, 'UTF-8') ?></td>
            <td style="font-size:.82rem;color:#475569;font-weight:500;font-family:var(--mono);"><?= htmlspecialchars($b['published_year']??'—', ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <div class="copies-ctl">
                <button class="cpy-btn" onclick="adjustCopies(<?=$b['id']?>, -1, this)">−</button>
                <span class="cpy-val" id="copiesVal-<?=$b['id']?>"><?=(int)($b['available_copies']??0)?></span>
                <button class="cpy-btn" onclick="adjustCopies(<?=$b['id']?>, 1, this)">+</button>
                <span class="cpy-total">/ <?=(int)($b['total_copies']??1)?></span>
              </div>
            </td>
            <td>
              <?php if(!empty($b['preface'])): ?>
              <button onclick="previewRag(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)" class="tag tag-rag-yes"><i class="fa-solid fa-eye" style="font-size:.55rem;"></i> View</button>
              <?php else: ?><span class="tag tag-rag-no">—</span><?php endif; ?>
            </td>
            <td><span class="tag tag-<?= ($b['status']??'')===  'active' ? 'active' : 'inactive' ?>"><?= ucfirst($b['status']??'inactive') ?></span></td>
            <td>
              <div class="act-pair">
                <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)" class="act-btn act-edit"><i class="fa-solid fa-pen" style="font-size:.65rem;"></i> Edit</button>
                <button onclick="confirmDelete(<?=$b['id']?>, <?= htmlspecialchars(json_encode($b['title']), ENT_QUOTES, 'UTF-8') ?>)" class="act-btn act-del"><i class="fa-solid fa-trash" style="font-size:.65rem;"></i> Delete</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile cards -->
    <div class="md:hidden" style="display:flex;flex-direction:column;gap:10px;" id="bookCardList">
      <?php foreach($books as $b):
        $avail  = (int)($b['available_copies'] ?? 0);
        $total  = (int)($b['total_copies'] ?? 1);
        $hasRag = !empty($b['preface']);
      ?>
      <div class="book-card" data-search="<?= strtolower(htmlspecialchars(($b['title']??'').' '.($b['author']??'').' '.($b['genre']??'').' '.($b['call_number']??''), ENT_QUOTES, 'UTF-8')) ?>">
        <div style="display:flex;align-items:flex-start;gap:12px;padding-bottom:12px;border-bottom:1px solid rgba(99,102,241,.05);margin-bottom:12px;">
          <div class="book-letter"><?= mb_strtoupper(mb_substr($b['title']??'B', 0, 1)) ?></div>
          <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
              <p style="font-weight:800;font-size:.88rem;color:#0f172a;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;"><?= htmlspecialchars($b['title']??'', ENT_QUOTES, 'UTF-8') ?></p>
              <span class="tag tag-<?= ($b['status']??'')===  'active' ? 'active' : 'inactive' ?>" style="flex-shrink:0;margin-top:2px;"><?= ucfirst($b['status']??'') ?></span>
            </div>
            <p style="font-size:.72rem;color:#94a3b8;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($b['author']??'', ENT_QUOTES, 'UTF-8') ?></p>
            <div style="display:flex;gap:5px;flex-wrap:wrap;margin-top:7px;">
              <?php if(!empty($b['call_number'])): ?><span class="call-badge" style="font-size:.6rem;"><?= htmlspecialchars($b['call_number'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
              <?php if(!empty($b['genre'])): ?><span class="book-meta-chip"><i class="fa-solid fa-tag" style="font-size:.6rem;"></i><?= htmlspecialchars($b['genre'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
              <?php if(!empty($b['published_year'])): ?><span class="book-meta-chip"><i class="fa-regular fa-calendar" style="font-size:.6rem;"></i><?= htmlspecialchars($b['published_year'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
              <?php if(!empty($b['isbn'])): ?><span class="book-meta-chip" style="font-family:var(--mono);"><i class="fa-solid fa-barcode" style="font-size:.6rem;"></i><?= htmlspecialchars($b['isbn'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
            </div>
          </div>
        </div>
        <div class="book-copies-row">
          <div style="display:flex;align-items:center;gap:6px;">
            <span style="font-size:.72rem;color:#94a3b8;font-weight:600;">Available:</span>
            <div class="copies-ctl">
              <button class="cpy-btn" onclick="adjustCopies(<?=$b['id']?>, -1, this)">−</button>
              <span class="cpy-val" id="copiesVal-<?=$b['id']?>-m"><?=$avail?></span>
              <button class="cpy-btn" onclick="adjustCopies(<?=$b['id']?>, 1, this)">+</button>
            </div>
            <span class="cpy-total">of <?=$total?></span>
          </div>
          <?php if($hasRag): ?>
          <button onclick="event.stopPropagation();previewRag(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)" class="tag tag-rag-yes" style="font-size:.62rem;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI Ready</button>
          <?php else: ?>
          <span class="tag tag-rag-no" style="font-size:.62rem;"><i class="fa-solid fa-circle-info" style="font-size:.6rem;"></i> No preface</span>
          <?php endif; ?>
        </div>
        <div class="book-card-actions" onclick="event.stopPropagation()">
          <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)" class="act-btn act-edit" style="justify-content:center;padding:10px;"><i class="fa-solid fa-pen" style="font-size:.75rem;"></i> Edit</button>
          <button onclick="confirmDelete(<?=$b['id']?>, <?= htmlspecialchars(json_encode($b['title']), ENT_QUOTES, 'UTF-8') ?>)" class="act-btn act-del" style="justify-content:center;padding:10px;"><i class="fa-solid fa-trash" style="font-size:.75rem;"></i> Delete</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div id="noResultsMsg" class="hidden" style="margin-top:16px;">
      <div class="empty-state">
        <i class="fa-solid fa-magnifying-glass" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
        <p style="font-size:.85rem;font-weight:600;color:#94a3b8;">No books match your search.</p>
      </div>
    </div>

    <!-- Pagination -->
    <div id="paginationControls" class="hidden" style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
      <p id="pageInfo" style="font-size:.72rem;font-weight:700;color:#94a3b8;"></p>
      <div id="pageButtons" style="display:flex;align-items:center;gap:6px;"></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- ════ BORROWINGS PANE ════ -->
  <div id="paneBorrowings" style="display:none;" class="fade-up-2">
    <?php if(empty($borrowings)): ?>
    <div class="empty-state">
      <div class="card-icon" style="background:#eef2ff;width:48px;height:48px;border-radius:var(--r-md);margin:0 auto 12px;"><i class="fa-solid fa-clock-rotate-left" style="color:var(--indigo);font-size:1.1rem;"></i></div>
      <div class="card-title" style="margin-bottom:6px;">No borrowing requests yet</div>
      <div class="card-sub" style="margin-bottom:16px;">Requests from residents will appear here.</div>
      <button onclick="switchTab('books')" class="action-btn" style="font-size:.82rem;margin:0 auto;"><i class="fa-solid fa-book" style="font-size:.78rem;"></i> View Catalog</button>
    </div>
    <?php else: ?>

    <!-- Filter bar -->
    <div class="filter-row">
      <div class="search-wrap" style="max-width:280px;">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input id="borrowSearch" type="text" class="search-input" placeholder="Search resident or book…" oninput="applyBorrowFilter()">
      </div>
      <button class="fpill active" id="bpill-all" onclick="filterBorrowings('all')">All</button>
      <button class="fpill fp-pending" id="bpill-pending" onclick="filterBorrowings('pending')">
        Pending <?php if($pendingBorrows>0): ?><span style="background:rgba(245,158,11,.25);color:#92400e;font-size:.6rem;font-weight:800;padding:1px 6px;border-radius:999px;margin-left:3px;"><?=$pendingBorrows?></span><?php endif; ?>
      </button>
      <button class="fpill fp-approved" id="bpill-approved" onclick="filterBorrowings('approved')">Approved</button>
      <button class="fpill fp-returned" id="bpill-returned" onclick="filterBorrowings('returned')">Returned</button>
      <button class="fpill fp-rejected" id="bpill-rejected" onclick="filterBorrowings('rejected')">Rejected</button>
      <span id="borrowResultCount" style="font-size:.72rem;font-weight:700;color:#94a3b8;display:none;"></span>
    </div>

    <!-- Desktop table -->
    <div class="tbl-wrap hidden md:block">
      <div style="overflow-x:auto;">
        <table id="borrowingsTable">
          <thead>
            <tr>
              <th style="width:32px;">#</th>
              <th style="min-width:140px;">Resident</th>
              <th style="min-width:140px;">Book</th>
              <th style="width:110px;">Borrowed</th>
              <th style="width:100px;">Due Date</th>
              <th style="width:80px;">Status</th>
              <th style="width:130px;">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($borrowings as $i => $bw): $s = strtolower($bw['status']??'pending'); ?>
          <tr data-status="<?=$s?>" data-search="<?= strtolower(htmlspecialchars(($bw['resident_name']??'').' '.($bw['book_title']??''), ENT_QUOTES, 'UTF-8')) ?>">
            <td style="color:#94a3b8;font-weight:700;font-size:.72rem;font-family:var(--mono);"><?=$i+1?></td>
            <td>
              <p style="font-weight:700;font-size:.85rem;color:#0f172a;"><?= htmlspecialchars($bw['resident_name']??'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
              <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($bw['email']??'', ENT_QUOTES, 'UTF-8') ?></p>
            </td>
            <td>
              <p style="font-weight:700;font-size:.85rem;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px;"><?= htmlspecialchars($bw['book_title']??'', ENT_QUOTES, 'UTF-8') ?></p>
              <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($bw['book_author']??'', ENT_QUOTES, 'UTF-8') ?></p>
            </td>
            <td style="font-size:.78rem;color:#475569;font-family:var(--mono);"><?= htmlspecialchars($bw['borrowed_at']??'—', ENT_QUOTES, 'UTF-8') ?></td>
            <td style="font-size:.78rem;color:#475569;font-family:var(--mono);"><?= htmlspecialchars($bw['due_date']??'—', ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="tag tag-<?=$s?>"><?= ucfirst($s) ?></span></td>
            <td>
              <div class="act-pair">
                <?php if($s === 'pending'): ?>
                <form method="post" action="/<?=$prefix?>/borrowings/approve/<?=$bw['id']?>"><?= csrf_field() ?><button class="act-btn act-approve" style="width:100%;"><i class="fa-solid fa-check" style="font-size:.65rem;"></i> Approve</button></form>
                <form method="post" action="/<?=$prefix?>/borrowings/reject/<?=$bw['id']?>"><?= csrf_field() ?><button class="act-btn act-reject" style="width:100%;"><i class="fa-solid fa-xmark" style="font-size:.65rem;"></i> Reject</button></form>
                <?php elseif($s === 'approved'): ?>
                <form method="post" action="/<?=$prefix?>/borrowings/return/<?=$bw['id']?>"><?= csrf_field() ?><button class="act-btn act-return" style="width:100%;"><i class="fa-solid fa-rotate-left" style="font-size:.65rem;"></i> Returned</button></form>
                <?php else: ?>
                <span style="font-size:.72rem;color:#94a3b8;font-weight:600;"><?= ucfirst($s) ?></span>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile borrow cards -->
    <div class="md:hidden" style="display:flex;flex-direction:column;gap:10px;" id="borrowCardList">
      <?php foreach($borrowings as $bw): $s = strtolower($bw['status']??'pending'); ?>
      <div class="borrow-card" data-status="<?=$s?>" data-search="<?= strtolower(htmlspecialchars(($bw['resident_name']??'').' '.($bw['book_title']??''), ENT_QUOTES, 'UTF-8')) ?>">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;padding-bottom:12px;border-bottom:1px solid rgba(99,102,241,.05);margin-bottom:10px;">
          <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
            <div class="card-icon" style="background:#f1f5f9;flex-shrink:0;"><i class="fa-solid fa-user" style="color:#64748b;font-size:.85rem;"></i></div>
            <div style="min-width:0;">
              <p style="font-weight:700;font-size:.88rem;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($bw['resident_name']??'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
              <p style="font-size:.7rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($bw['email']??'', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
          </div>
          <span class="tag tag-<?=$s?>" style="flex-shrink:0;"><?= ucfirst($s) ?></span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <div class="card-icon" style="background:var(--indigo-light);flex-shrink:0;"><i class="fa-solid fa-book" style="color:var(--indigo);font-size:.8rem;"></i></div>
          <div style="min-width:0;">
            <p style="font-weight:700;font-size:.82rem;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($bw['book_title']??'', ENT_QUOTES, 'UTF-8') ?></p>
            <p style="font-size:.7rem;color:#94a3b8;"><?= htmlspecialchars($bw['book_author']??'', ENT_QUOTES, 'UTF-8') ?></p>
          </div>
        </div>
        <div style="display:flex;gap:16px;font-size:.7rem;font-weight:600;color:#94a3b8;font-family:var(--mono);margin-bottom:<?= in_array($s,['pending','approved']) ? '12px' : '0' ?>;">
          <span style="display:flex;align-items:center;gap:5px;"><i class="fa-regular fa-calendar" style="font-size:.65rem;"></i><?= htmlspecialchars($bw['borrowed_at']??'—', ENT_QUOTES, 'UTF-8') ?></span>
          <span style="display:flex;align-items:center;gap:5px;<?=$s==='approved'?'color:#ef4444;font-weight:700;':''?>"><i class="fa-regular fa-calendar-xmark" style="font-size:.65rem;"></i>Due: <?= htmlspecialchars($bw['due_date']??'—', ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <?php if($s === 'pending'): ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding-top:10px;border-top:1px solid rgba(99,102,241,.05);">
          <form method="post" action="/<?=$prefix?>/borrowings/approve/<?=$bw['id']?>"><?= csrf_field() ?><button class="act-btn act-approve" style="width:100%;justify-content:center;padding:10px;"><i class="fa-solid fa-check" style="font-size:.7rem;"></i> Approve</button></form>
          <form method="post" action="/<?=$prefix?>/borrowings/reject/<?=$bw['id']?>"><?= csrf_field() ?><button class="act-btn act-reject" style="width:100%;justify-content:center;padding:10px;"><i class="fa-solid fa-xmark" style="font-size:.7rem;"></i> Reject</button></form>
        </div>
        <?php elseif($s === 'approved'): ?>
        <div style="padding-top:10px;border-top:1px solid rgba(99,102,241,.05);">
          <form method="post" action="/<?=$prefix?>/borrowings/return/<?=$bw['id']?>"><?= csrf_field() ?><button class="act-btn act-return" style="width:100%;justify-content:center;padding:10px;"><i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Mark as Returned</button></form>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <div id="noBorrowResultsMsg" class="hidden" style="margin-top:16px;">
      <div class="empty-state">
        <p style="font-size:.85rem;font-weight:600;color:#94a3b8;">No borrowings match your filter.</p>
      </div>
    </div>
    <?php endif; ?>
  </div>

</main>

<script>
  if (typeof pdfjsLib !== 'undefined') pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

  /* ── Dark mode ── */
  function toggleDark() {
    const isDark = document.body.classList.toggle('dark');
    document.getElementById('darkIcon').innerHTML = isDark
      ? '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>'
      : '<i class="fa-regular fa-sun" style="font-size:.85rem;"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
  }
  (function initDark() {
    if (localStorage.getItem('admin_theme') === 'dark') {
      document.body.classList.add('dark');
      const icon = document.getElementById('darkIcon');
      if (icon) icon.innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem;"></i>';
    }
  })();

  /* ── Tabs ── */
  function switchTab(t) {
    document.getElementById('paneBooks').style.display     = t === 'books'      ? '' : 'none';
    document.getElementById('paneBorrowings').style.display = t === 'borrowings' ? '' : 'none';
    document.getElementById('tabBooks').className      = 'tab-btn' + (t === 'books'      ? ' active' : '');
    document.getElementById('tabBorrowings').className = 'tab-btn' + (t === 'borrowings' ? ' active' : '');
  }
  if (window.location.hash === '#borrowings') switchTab('borrowings');

  /* ── Modals ── */
  function openModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') ['addModal','editModal','pdfModal','deleteModal','ragModal'].forEach(closeModal);
  });

  function confirmDelete(id, title) {
    document.getElementById('deleteBookTitle').textContent = title;
    document.getElementById('deleteForm').action = '/<?=$prefix?>/books/delete/' + id;
    openModal('deleteModal');
  }

  function openEditModal(b) {
    document.getElementById('editTitle').value       = b.title || '';
    document.getElementById('editAuthor').value      = b.author || '';
    document.getElementById('editGenre').value       = b.genre || '';
    document.getElementById('editYear').value        = b.published_year || '';
    document.getElementById('editCopies').value      = b.total_copies || 1;
    document.getElementById('editStatus').value      = b.status || 'active';
    document.getElementById('editPreface').value     = b.preface || '';
    document.getElementById('editCallNumber').value  = b.call_number || '';
    document.getElementById('editForm').action       = '/<?=$prefix?>/books/update/' + b.id;
    openModal('editModal');
  }

  function previewRag(b) {
    const avail = parseInt(b.available_copies || 0) > 0 ? 'Available' : 'Currently checked out';
    document.getElementById('ragPreviewText').textContent =
      '[1] "' + b.title + '" by ' + (b.author || 'Unknown') +
      '\nGenre: ' + (b.genre || 'General') + ' | Year: ' + (b.published_year || 'N/A') +
      (b.call_number ? ' | Call#: ' + b.call_number : '') + ' | ' + avail +
      '\n\nDescription:\n' + (b.preface || '(No preface)');
    openModal('ragModal');
  }

  /* ── Books filter + pagination ── */
  function applyFilter() {
    const q = document.getElementById('bookSearch')?.value?.toLowerCase().trim() || '';
    let visible = 0;
    document.querySelectorAll('#booksTable tbody tr').forEach(r => {
      const m = !q || r.dataset.search.includes(q);
      r.style.display = m ? '' : 'none';
      if (m) visible++;
    });
    document.querySelectorAll('#bookCardList .book-card').forEach(c => {
      c.style.display = (!q || c.dataset.search.includes(q)) ? '' : 'none';
    });
    const countEl = document.getElementById('resultCount');
    const noMsg   = document.getElementById('noResultsMsg');
    if (q) {
      countEl.textContent = visible + ' result' + (visible !== 1 ? 's' : '');
      countEl.style.display = 'inline-flex';
      if (noMsg) noMsg.classList.toggle('hidden', visible > 0);
    } else {
      countEl.style.display = 'none';
      if (noMsg) noMsg.classList.add('hidden');
    }
    initPagination(q);
  }

  let _currentPage = 1;
  const PAGE_SIZE = 20;

  function initPagination(f) {
    const rows       = Array.from(document.querySelectorAll('#booksTable tbody tr')).filter(r => r.style.display !== 'none');
    const total      = rows.length;
    const totalPages = Math.ceil(total / PAGE_SIZE);
    const ctrl       = document.getElementById('paginationControls');
    if (!ctrl) return;
    if (totalPages <= 1) { ctrl.classList.add('hidden'); showPage(1, rows); return; }
    ctrl.classList.remove('hidden');
    _currentPage = Math.min(_currentPage, totalPages);
    showPage(_currentPage, rows);
    const btns = document.getElementById('pageButtons');
    btns.innerHTML = '';
    const prev = document.createElement('button');
    prev.className = 'page-btn'; prev.textContent = '‹'; prev.disabled = _currentPage === 1;
    prev.onclick = () => goToPage(_currentPage - 1, f);
    btns.appendChild(prev);
    paginationRange(_currentPage, totalPages).forEach(p => {
      const btn = document.createElement('button');
      btn.className = 'page-btn' + (p === _currentPage ? ' active' : '');
      btn.textContent = p; btn.style.pointerEvents = p === '…' ? 'none' : '';
      if (p !== '…') btn.onclick = () => goToPage(p, f);
      btns.appendChild(btn);
    });
    const next = document.createElement('button');
    next.className = 'page-btn'; next.textContent = '›'; next.disabled = _currentPage === totalPages;
    next.onclick = () => goToPage(_currentPage + 1, f);
    btns.appendChild(next);
    const start = (_currentPage - 1) * PAGE_SIZE + 1;
    document.getElementById('pageInfo').textContent = `Showing ${start}–${Math.min(_currentPage * PAGE_SIZE, total)} of ${total}`;
  }

  function showPage(p, rows) {
    rows.forEach((r, i) => { r.style.display = (i >= (p-1)*PAGE_SIZE && i < p*PAGE_SIZE) ? '' : 'none'; });
  }
  function goToPage(p, f) { _currentPage = p; initPagination(f); }
  function paginationRange(c, t) {
    if (t <= 7) return Array.from({length: t}, (_, i) => i + 1);
    const r = [1];
    if (c > 3) r.push('…');
    for (let i = Math.max(2, c-1); i <= Math.min(t-1, c+1); i++) r.push(i);
    if (c < t-2) r.push('…');
    r.push(t);
    return r;
  }
  document.addEventListener('DOMContentLoaded', () => initPagination(''));

  /* ── Inline copies +/− with CSRF refresh ── */
  function adjustCopies(bookId, delta, btn) {
    const sels = [`#copiesVal-${bookId}`, `#copiesVal-${bookId}-m`];
    const els  = sels.map(s => document.querySelector(s)).filter(Boolean);
    if (!els.length) return;
    const cur = parseInt(els[0].textContent) || 0;
    const nv  = Math.max(0, cur + delta);
    els.forEach(el => el.textContent = nv);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    btn.disabled = true;
    fetch('/<?=$prefix?>/books/update-copies/' + bookId, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ available_copies: nv })
    }).then(res => {
      const t = res.headers.get('X-CSRF-TOKEN');
      if (t) document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', t);
      btn.disabled = false;
      if (!res.ok) els.forEach(el => el.textContent = cur);
    }).catch(() => { els.forEach(el => el.textContent = cur); btn.disabled = false; });
  }

  /* ── Borrowings filter ── */
  let _borrowStatus = 'all';
  function filterBorrowings(status) {
    _borrowStatus = status;
    ['all','pending','approved','returned','rejected'].forEach(s => {
      const p = document.getElementById('bpill-' + s);
      if (!p) return;
      p.classList.remove('active');
      if (s === status) p.classList.add('active');
    });
    applyBorrowFilter();
  }
  function applyBorrowFilter() {
    const q      = (document.getElementById('borrowSearch')?.value || '').toLowerCase().trim();
    let visible  = 0;
    const matches = el => (_borrowStatus === 'all' || el.dataset.status === _borrowStatus) && (!q || el.dataset.search.includes(q));
    document.querySelectorAll('#borrowingsTable tbody tr').forEach(r => {
      const s = matches(r); r.style.display = s ? '' : 'none'; if (s) visible++;
    });
    document.querySelectorAll('#borrowCardList .borrow-card').forEach(c => {
      c.style.display = matches(c) ? '' : 'none';
    });
    const noMsg    = document.getElementById('noBorrowResultsMsg');
    const countEl  = document.getElementById('borrowResultCount');
    if (_borrowStatus !== 'all' || q) {
      countEl.textContent = visible + ' result' + (visible !== 1 ? 's' : '');
      countEl.style.display = 'inline';
      if (noMsg) noMsg.classList.toggle('hidden', visible > 0);
    } else {
      countEl.style.display = 'none';
      if (noMsg) noMsg.classList.add('hidden');
    }
  }

  /* ── PDF extraction via pdf.js ── */
  let _aPdfText = null, _aPI = null;
  if (typeof pdfjsLib !== 'undefined') {
    const dz = document.getElementById('aDropZone');
    if (dz) {
      dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('dragover'); });
      dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
      dz.addEventListener('drop', e => { e.preventDefault(); dz.classList.remove('dragover'); const f = e.dataTransfer.files[0]; if (f) aProcessPdfFile(f); });
    }
  }

  function aHandlePdfFile(e) { const f = e.target.files[0]; if (f) aProcessPdfFile(f); }

  function aProcessPdfFile(file) {
    if (file.type !== 'application/pdf') { alert('Please upload a PDF file.'); return; }
    if (file.size > 10*1024*1024) { alert('File too large (max 10MB).'); return; }
    document.getElementById('aFilePreviewName').textContent = file.name;
    document.getElementById('aFilePreviewSize').textContent = (file.size/1024).toFixed(1) + ' KB';
    document.getElementById('aFilePreview').classList.remove('hidden');
    document.getElementById('aDropZone').style.borderColor = 'var(--indigo)';
    const reader = new FileReader();
    reader.onload = async ev => {
      try {
        const arr = new Uint8Array(ev.target.result);
        const pdf = await pdfjsLib.getDocument({ data: arr }).promise;
        const pages = [];
        for (let p = 1; p <= Math.min(pdf.numPages, 8); p++) {
          const page = await pdf.getPage(p);
          const c = await page.getTextContent();
          pages.push(c.items.map(s => s.str).join(' '));
        }
        _aPdfText = pages.join('\n\n');
        document.getElementById('aExtractBtn').disabled = !_aPdfText || _aPdfText.trim().length < 20;
        if (!_aPdfText || _aPdfText.trim().length < 20) { alert('Could not extract readable text. This PDF may be a scanned image.'); _aPdfText = null; }
      } catch (err) { alert('Error reading PDF: ' + err.message); _aPdfText = null; }
    };
    reader.readAsArrayBuffer(file);
  }

  function aClearPdfFile() {
    _aPdfText = null;
    document.getElementById('aPdfFileInput').value = '';
    document.getElementById('aFilePreview').classList.add('hidden');
    document.getElementById('aExtractBtn').disabled = true;
    document.getElementById('aDropZone').style.borderColor = '';
  }

  function aSetStep(n) {
    [1,2,3].forEach(i => { document.getElementById('aStepDot'+i).className = 'step-dot ' + (i < n ? 'done' : i === n ? 'active' : 'pending'); });
    [1,2].forEach(i => { document.getElementById('aStepLine'+i).className = 'step-line ' + (i < n ? 'done' : 'pending'); });
    document.getElementById('aStepLabel').textContent = { 1:'Upload PDF', 2:'AI Extracting…', 3:'Review & Save' }[n] || '';
  }

  function aShowPanel(id) {
    ['aPdfStep1','aPdfStep2','aPdfStep3','aPdfStepError'].forEach(p => {
      document.getElementById(p).style.display = p === id ? '' : 'none';
    });
  }

  function aAnimProgress(tp, dur) {
    const fill = document.getElementById('aAiProgressFill');
    const s = parseFloat(fill.style.width) || 0, t0 = Date.now();
    clearInterval(_aPI);
    _aPI = setInterval(() => {
      const pct = Math.min(s + (tp-s)*((Date.now()-t0)/dur), tp);
      fill.style.width = pct + '%';
      if (pct >= tp) clearInterval(_aPI);
    }, 30);
  }

  function aShowError(msg, dbg) {
    clearInterval(_aPI);
    document.getElementById('aPdfErrorText').textContent = msg;
    const d = document.getElementById('adminDebugPanel');
    if (dbg) { d.textContent = dbg; d.style.display = 'block'; } else { d.style.display = 'none'; }
    aShowPanel('aPdfStepError'); aSetStep(1);
  }

  async function aExtractFromPdf() {
    if (!_aPdfText) return;
    aSetStep(2); aShowPanel('aPdfStep2'); aAnimProgress(40, 2000);
    const msgs = ['Sending text to AI…','Extracting title and author…','Identifying genre and year…','Generating description…','Finalizing…'];
    let mi = 0;
    const me = document.getElementById('aAiStatusText');
    const mint = setInterval(() => { mi = (mi+1) % msgs.length; me.textContent = msgs[mi]; }, 2200);
    try {
      aAnimProgress(75, 8000);
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const response = await fetch('/<?=$prefix?>/books/extract-pdf', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ pdf_text: _aPdfText })
      });
      const newToken = response.headers.get('X-CSRF-TOKEN');
      if (newToken) document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);
      clearInterval(mint); aAnimProgress(95, 500);
      const ct = response.headers.get('Content-Type') || '';
      if (!ct.includes('application/json')) {
        const rb = await response.text();
        const snippet = rb.replace(/<[^>]*>/g,' ').replace(/\s+/g,' ').trim().slice(0,300);
        aShowError({ 419:'CSRF expired — refresh and try again.', 403:'Access forbidden.', 401:'Session expired.', 404:'Route not found.', 500:'Server error.' }[response.status] || 'HTTP '+response.status, snippet);
        return;
      }
      const rb = await response.text(); let result;
      try { result = JSON.parse(rb); } catch(e) { aShowError('Server returned invalid JSON.', rb.slice(0,300)); return; }
      if (!result.ok) { aShowError(result.error || 'Extraction failed.', null); return; }
      aAnimProgress(100, 200);
      setTimeout(() => { aPopulate(result.data); aSetStep(3); aShowPanel('aPdfStep3'); }, 400);
    } catch (err) { clearInterval(mint); aShowError('Network error: ' + err.message, null); }
  }

  function aPopulate(data) {
    const fields = {
      aPdfTitle:      { key:'title',          badge:'aBadgeTitle'      },
      aPdfAuthor:     { key:'author',         badge:'aBadgeAuthor'     },
      aPdfGenre:      { key:'genre',          badge:'aBadgeGenre'      },
      aPdfYear:       { key:'published_year', badge:'aBadgeYear'       },
      aPdfIsbn:       { key:'isbn',           badge:'aBadgeIsbn'       },
      aPdfCallNumber: { key:'call_number',    badge:'aBadgeCallNumber' },
      aPdfPreface:    { key:'preface',        badge:'aBadgePreface'    },
    };
    let anyMissing = false;
    Object.entries(fields).forEach(([elId, cfg]) => {
      const el = document.getElementById(elId), badge = document.getElementById(cfg.badge);
      const val = (data[cfg.key] || '').trim();
      el.value = val;
      if (val) { el.classList.add('filled'); if (badge) badge.style.display = 'inline-flex'; }
      else { el.classList.remove('filled'); if (badge) badge.style.display = 'none'; if (['title','author'].includes(cfg.key)) anyMissing = true; }
    });
    const note = document.getElementById('aAiConfidenceNote');
    if (note) note.classList.toggle('hidden', !anyMissing);
  }

  function aResetPdfModal() {
    _aPdfText = null; clearInterval(_aPI);
    document.getElementById('aPdfFileInput').value = '';
    document.getElementById('aFilePreview').classList.add('hidden');
    document.getElementById('aExtractBtn').disabled = true;
    document.getElementById('aDropZone').style.borderColor = '';
    document.getElementById('aAiProgressFill').style.width = '0%';
    document.getElementById('aAiStatusText').textContent = 'Extracting text…';
    document.getElementById('adminDebugPanel').style.display = 'none';
    ['aPdfTitle','aPdfAuthor','aPdfGenre','aPdfYear','aPdfIsbn','aPdfCallNumber','aPdfPreface'].forEach(id => {
      const el = document.getElementById(id); if (el) { el.value = ''; el.classList.remove('filled'); }
    });
    ['aBadgeTitle','aBadgeAuthor','aBadgeGenre','aBadgeYear','aBadgeIsbn','aBadgeCallNumber','aBadgePreface'].forEach(id => {
      const el = document.getElementById(id); if (el) el.style.display = 'none';
    });
    const note = document.getElementById('aAiConfidenceNote');
    if (note) note.classList.add('hidden');
    aSetStep(1); aShowPanel('aPdfStep1');
  }
</script>
</body>
</html>