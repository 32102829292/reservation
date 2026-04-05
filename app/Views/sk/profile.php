<?php
date_default_timezone_set('Asia/Manila');
$page = $page ?? 'profile';
$pendingUserCount = $pendingUserCount ?? 0;
$avatarLetter = strtoupper(mb_substr(trim($user['name'] ?? 'S'), 0, 1));
$memberSince  = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : '—';
$memberYear   = isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>My Profile | SK</title>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>(function(){if(localStorage.getItem('sk_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
        :root{
            --indigo:#3730a3;--indigo-mid:#4338ca;--indigo-light:#eef2ff;--indigo-border:#c7d2fe;
            --bg:#f0f2f9;--card:#ffffff;
            --font:'Plus Jakarta Sans',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;
            --shadow-sm:0 1px 4px rgba(15,23,42,.07),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.09),0 2px 4px rgba(15,23,42,.04);
            --shadow-lg:0 12px 40px rgba(15,23,42,.12),0 4px 8px rgba(15,23,42,.06);
            --r-sm:10px;--r-md:14px;--r-lg:20px;--r-xl:24px;
            --sidebar-w:268px;--ease:.18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h:60px;--mob-nav-total:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));
        }
        html{height:100%;height:100dvh;font-size:16px}
        body{font-family:var(--font);background:var(--bg);color:#0f172a;display:flex;height:100vh;height:100dvh;overflow:hidden;-webkit-font-smoothing:antialiased;overflow-x:hidden}
        html.dark-pre body{background:#060e1e}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;padding:18px 14px;height:100vh;height:100dvh;display:flex;flex-direction:column}
        .sidebar-inner{background:var(--card);border-radius:var(--r-xl);border:1px solid rgba(99,102,241,.1);height:100%;display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-md)}
        .sidebar-top{padding:22px 18px 16px;border-bottom:1px solid rgba(99,102,241,.07)}
        .brand-tag{font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#94a3b8;margin-bottom:5px}
        .brand-name{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.03em;line-height:1.1}
        .brand-name em{font-style:normal;color:var(--indigo)}
        .brand-sub{font-size:.7rem;color:#94a3b8;margin-top:3px}
        .user-card{margin:12px 12px 0;background:var(--indigo-light);border-radius:var(--r-md);padding:12px 14px;border:1px solid var(--indigo-border);display:flex;align-items:center;gap:9px}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:var(--indigo);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0;box-shadow:0 2px 8px rgba(55,48,163,.3)}
        .user-name-txt{font-size:.8rem;font-weight:700;color:#0f172a;letter-spacing:-.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role-txt{font-size:.68rem;color:#6366f1;font-weight:500;margin-top:1px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px;display:flex;flex-direction:column;gap:3px;scrollbar-width:none}
        .sidebar-nav::-webkit-scrollbar{display:none}
        .nav-section-lbl{font-size:.6rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#cbd5e1;padding:10px 10px 5px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:.85rem;font-weight:600;color:#64748b;text-decoration:none;transition:all var(--ease)}
        .nav-link:hover{background:var(--indigo-light);color:var(--indigo)}
        .nav-link.active{background:var(--indigo);color:#fff;box-shadow:0 4px 14px rgba(55,48,163,.32)}
        .nav-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.85rem}
        .nav-link:not(.active) .nav-icon{background:#f1f5f9}
        .nav-link:hover:not(.active) .nav-icon{background:#e0e7ff}
        .nav-link.active .nav-icon{background:rgba(255,255,255,.15)}
        .nav-badge{margin-left:auto;background:rgba(245,158,11,.18);color:#d97706;font-size:.6rem;font-weight:700;padding:2px 7px;border-radius:999px}
        .sidebar-footer{padding:10px 10px 12px;border-top:1px solid rgba(99,102,241,.07)}
        .logout-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:.85rem;font-weight:600;color:#94a3b8;text-decoration:none;transition:all var(--ease)}
        .logout-link:hover{background:#fef2f2;color:#dc2626}
        .logout-link:hover .nav-icon{background:#fee2e2}

        /* ── Mobile nav ── */
        .mobile-nav-pill{display:none;position:fixed;bottom:0;left:0;right:0;background:white;border-top:1px solid rgba(99,102,241,.1);height:var(--mob-nav-total);z-index:200;box-shadow:0 -4px 20px rgba(55,48,163,.1)}
        .mobile-scroll-container{display:flex;justify-content:space-evenly;align-items:center;height:var(--mob-nav-h);width:100%}
        .mob-nav-item{flex:1;display:flex;align-items:center;justify-content:center;height:48px;border-radius:14px;cursor:pointer;text-decoration:none;color:#64748b;position:relative;transition:background .15s,color .15s}
        .mob-nav-item:hover,.mob-nav-item.active{background:var(--indigo-light);color:var(--indigo)}
        .mob-nav-item.active::after{content:'';position:absolute;bottom:4px;left:50%;transform:translateX(-50%);width:4px;height:4px;background:var(--indigo);border-radius:50%}
        .mob-logout{color:#94a3b8}
        .mob-logout:hover{background:#fef2f2;color:#dc2626}
        @media(max-width:1023px){.sidebar{display:none!important}.mobile-nav-pill{display:flex!important}.main-area{padding-bottom:calc(var(--mob-nav-total) + 16px)!important}}
        @media(min-width:1024px){.sidebar{display:flex!important}.mobile-nav-pill{display:none!important}}

        /* ── Main ── */
        .main-area{flex:1;min-width:0;padding:24px 28px 40px;height:100vh;height:100dvh;overflow-y:auto;overflow-x:hidden;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain}
        @media(max-width:1023px){.main-area::-webkit-scrollbar{display:none}.main-area{scrollbar-width:none}}
        @media(min-width:1024px){.main-area::-webkit-scrollbar{width:4px}.main-area::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}}
        @media(max-width:639px){.main-area{padding:14px 12px 0}}

        .icon-btn{width:44px;height:44px;background:white;border:1px solid rgba(99,102,241,.12);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;transition:all var(--ease);box-shadow:var(--shadow-sm)}
        .icon-btn:hover{background:var(--indigo-light);border-color:var(--indigo-border);color:var(--indigo)}

        /* ── Cards ── */
        .card{background:var(--card);border-radius:var(--r-lg);border:1px solid rgba(99,102,241,.08);box-shadow:var(--shadow-sm)}
        .card-p{padding:20px 22px}
        .card-p-lg{padding:22px 24px}
        .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
        .card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .card-title{font-size:.9rem;font-weight:700;color:#0f172a;letter-spacing:-.01em}
        .card-sub{font-size:.7rem;color:#94a3b8;margin-top:2px}
        .section-lbl{font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:14px}

        /* ── Flash ── */
        .flash-ok{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding:13px 18px;background:var(--indigo-light);border:1px solid var(--indigo-border);color:var(--indigo);font-weight:600;border-radius:var(--r-md);font-size:.875rem;animation:slideUp .4s ease both}
        .flash-err{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding:13px 18px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-weight:600;border-radius:var(--r-md);font-size:.875rem;animation:slideUp .4s ease both}

        /* ── Profile avatar ── */
        .profile-avatar{width:80px;height:80px;background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#818cf8 100%);border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;box-shadow:0 8px 24px rgba(55,48,163,.3);font-family:var(--mono);letter-spacing:-.04em}
        .profile-status-dot{position:absolute;bottom:-4px;right:-4px;width:22px;height:22px;background:#10b981;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center}

        /* ── Info rows ── */
        .info-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid rgba(99,102,241,.07)}
        .info-row:last-child{border-bottom:none}
        .info-icon{width:34px;height:34px;border-radius:10px;background:#f8fafc;border:1px solid rgba(99,102,241,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .info-label{font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8}
        .info-value{font-size:.85rem;font-weight:600;color:#0f172a;margin-top:1px}

        /* ── Mini stats ── */
        .stat-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .stat-mini{background:#f8fafc;border:1px solid rgba(99,102,241,.09);border-radius:var(--r-sm);padding:12px 14px}
        .stat-mini-lbl{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:#94a3b8;margin-bottom:4px}
        .stat-mini-val{font-size:1.25rem;font-weight:800;color:#0f172a;font-family:var(--mono);line-height:1;letter-spacing:-.03em}
        .stat-mini-sub{font-size:.68rem;color:#94a3b8;margin-top:3px}

        /* ── Quick links ── */
        .quick-link{display:flex;align-items:center;gap:10px;padding:10px;border-radius:var(--r-sm);border:1px solid rgba(99,102,241,.09);background:white;text-decoration:none;color:#475569;font-size:.83rem;font-weight:600;transition:all var(--ease)}
        .quick-link:hover{border-color:var(--indigo);background:var(--indigo-light);color:var(--indigo)}

        /* ── Buttons ── */
        .edit-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-size:.85rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);box-shadow:0 4px 12px rgba(55,48,163,.28);margin-top:16px}
        .edit-btn:hover{background:#312e81;transform:translateY(-1px);box-shadow:0 6px 18px rgba(55,48,163,.35)}
        .action-btn-sm{display:flex;align-items:center;gap:6px;padding:8px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:9px;font-size:.72rem;font-weight:700;color:var(--indigo);cursor:pointer;font-family:var(--font);transition:all var(--ease)}
        .action-btn-sm:hover{background:var(--indigo);color:white}

        /* ── Danger zone ── */
        .danger-row{display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(99,102,241,.07);gap:12px}
        .danger-row:last-child{border-bottom:none;padding-bottom:0}
        .danger-btn{font-size:.75rem;font-weight:700;padding:8px 14px;border-radius:9px;border:1px solid #fecaca;background:#fef2f2;color:#dc2626;cursor:pointer;font-family:var(--font);transition:all var(--ease);white-space:nowrap;flex-shrink:0}
        .danger-btn:hover{background:#fee2e2;border-color:#f87171}

        /* ── Tip banner ── */
        .tip-banner{background:linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%);border-radius:var(--r-lg);padding:20px 22px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden}
        .tip-banner::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;opacity:.4}
        .tip-icon{width:42px;height:42px;background:rgba(255,255,255,.15);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1}

        /* ── Password strength ── */
        .pw-strength{height:3px;border-radius:999px;background:#e2e8f0;overflow:hidden;margin-top:6px}
        .pw-fill{height:100%;border-radius:999px;transition:width .3s,background .3s}

        /* ── Modal ── */
        .modal-back{display:none;position:fixed;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(8px);z-index:300;padding:1.5rem;overflow-y:auto;align-items:center;justify-content:center}
        .modal-back.show{display:flex;animation:fadeIn .18s ease}
        .modal-card{background:white;border-radius:var(--r-xl);width:100%;max-width:480px;padding:28px;max-height:calc(100dvh - 3rem);overflow-y:auto;margin:auto;animation:slideUp .22s ease;box-shadow:var(--shadow-lg)}
        .field-label{display:block;font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px}
        .field-input{width:100%;background:#f8fafc;border:1px solid rgba(99,102,241,.12);border-radius:var(--r-sm);padding:11px 14px;font-family:var(--font);font-size:.87rem;font-weight:600;color:#0f172a;transition:all .2s;outline:none}
        .field-input:focus{border-color:#818cf8;background:white;box-shadow:0 0 0 3px rgba(99,102,241,.08)}
        .field-hint{font-size:.65rem;color:#94a3b8;margin-top:4px}
        .sheet-handle{display:none;width:36px;height:4px;background:#e2e8f0;border-radius:999px;margin:0 auto 16px}
        @media(max-width:639px){
            .modal-back{padding:0;align-items:flex-end!important}
            .modal-card{border-radius:var(--r-xl) var(--r-xl) 0 0;max-width:100%;max-height:92dvh;animation:sheetUp .25s cubic-bezier(.34,1.2,.64,1) both}
            .sheet-handle{display:block}
        }

        /* ── Delete input states ── */
        .input-success{border-color:#86efac!important;background:#f0fdf4!important}
        .input-error{border-color:#f87171!important;background:#fff5f5!important}

        /* ── Animations ── */
        @keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        @keyframes sheetUp{from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none}}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        .fade-up{animation:slideUp .4s ease both}
        .fade-up-1{animation:slideUp .45s .05s ease both}
        .fade-up-2{animation:slideUp .45s .1s ease both}

        /* ── Dark mode ── */
        body.dark{--bg:#060e1e;--card:#0b1628;--indigo-light:rgba(55,48,163,.12);--indigo-border:rgba(99,102,241,.25);color:#e2eaf8}
        body.dark .sidebar-inner{background:#0b1628;border-color:rgba(99,102,241,.12)}
        body.dark .brand-name{color:#e2eaf8}
        body.dark .nav-link{color:#a5b4fc}
        body.dark .nav-link:hover{background:rgba(99,102,241,.12);color:#c7d2fe}
        body.dark .nav-link:not(.active) .nav-icon{background:rgba(99,102,241,.1)}
        body.dark .user-card{background:rgba(55,48,163,.15);border-color:rgba(99,102,241,.2)}
        body.dark .user-name-txt{color:#e2eaf8}
        body.dark .card{background:#0b1628;border-color:rgba(99,102,241,.1)}
        body.dark .card-title{color:#e2eaf8}
        body.dark .section-lbl{color:#4a6fa5}
        body.dark .info-icon{background:#101e35;border-color:rgba(99,102,241,.1)}
        body.dark .info-value{color:#e2eaf8}
        body.dark .stat-mini{background:#101e35;border-color:rgba(99,102,241,.1)}
        body.dark .stat-mini-val{color:#e2eaf8}
        body.dark .mobile-nav-pill{background:#0b1628;border-color:rgba(99,102,241,.18)}
        body.dark .mob-nav-item{color:#a5b4fc}
        body.dark .mob-nav-item.active{background:rgba(99,102,241,.18)}
        body.dark .profile-status-dot{border-color:#0b1628}
        body.dark .quick-link{background:#0b1628;border-color:rgba(99,102,241,.1);color:#a5b4fc}
        body.dark .quick-link:hover{background:rgba(99,102,241,.12);color:#c7d2fe;border-color:var(--indigo)}
        body.dark .icon-btn{background:#0b1628;border-color:rgba(99,102,241,.15);color:#a5b4fc}
        body.dark .icon-btn:hover{background:rgba(99,102,241,.12)}
        body.dark .pw-strength{background:rgba(99,102,241,.15)}
        body.dark .danger-row{border-color:rgba(99,102,241,.08)}
        body.dark .info-row{border-color:rgba(99,102,241,.07)}

        /* ── Dark modal fixes ── */
        body.dark .modal-card{background:#0b1628;color:#e2eaf8}
        body.dark .modal-card h3{color:#e2eaf8}
        body.dark .modal-card h4{color:#e2eaf8}
        body.dark .modal-card p{color:#a5b4fc}
        body.dark .modal-card .field-label{color:#64748b}
        body.dark .field-input{background:#101e35;border-color:rgba(99,102,241,.18);color:#e2eaf8}
        body.dark .field-input:focus{background:#0b1628}
        body.dark .sheet-handle{background:#1e3a5f}

        /* ── Dark delete modal specific ── */
        body.dark #deleteConfirmInput{background:#101e35;border-color:#7f1d1d;color:#e2eaf8}
        body.dark #deleteConfirmInput.input-success{background:#052e16!important;border-color:#16a34a!important;color:#e2eaf8}
        body.dark #deleteConfirmInput.input-error{background:#2d0a0a!important;border-color:#f87171!important;color:#e2eaf8}
        body.dark .delete-warning-box{background:rgba(127,29,29,.3)!important;border-color:#7f1d1d!important}
        body.dark .delete-warning-box p{color:#fca5a5!important}
        body.dark .delete-warning-title{color:#fca5a5!important}
        body.dark .delete-code-badge{background:rgba(127,29,29,.4)!important;border-color:#7f1d1d!important;color:#fca5a5!important}
        body.dark .delete-cancel-btn{background:#101e35!important;border-color:rgba(99,102,241,.2)!important;color:#a5b4fc!important}
        body.dark .delete-cancel-btn:hover{background:#1e3a5f!important}
        /* ═══════════════════════════════════════════════════
   SK OFFICER PORTAL — DARK MODE COMPREHENSIVE FIX
   Paste at the bottom of <style> in every SK view.
   Targets hardcoded inline colors that class selectors
   cannot override on their own.
═══════════════════════════════════════════════════ */

/* ── General inline text overrides ── */
body.dark [style*="color:#0f172a"],
body.dark [style*="color: #0f172a"] { color: #e2eaf8 !important; }

body.dark [style*="color:#475569"],
body.dark [style*="color: #475569"] { color: #94b4d4 !important; }

body.dark [style*="color:#64748b"],
body.dark [style*="color: #64748b"] { color: #7fb3e8 !important; }

body.dark [style*="color:#94a3b8"],
body.dark [style*="color: #94a3b8"] { color: #4a6fa5 !important; }

body.dark [style*="color:#cbd5e1"],
body.dark [style*="color: #cbd5e1"] { color: #2d4a6a !important; }

body.dark [style*="color:#334155"],
body.dark [style*="color: #334155"] { color: #94b4d4 !important; }

body.dark [style*="color:#1e293b"],
body.dark [style*="color: #1e293b"] { color: #c7d2fe !important; }

/* ── General inline background overrides ── */
body.dark [style*="background:#ffffff"],
body.dark [style*="background: #ffffff"],
body.dark [style*="background:white"],
body.dark [style*="background: white"] { background: #0b1628 !important; }

body.dark [style*="background:#f8fafc"],
body.dark [style*="background: #f8fafc"] { background: #101e35 !important; }

body.dark [style*="background:#f1f5f9"],
body.dark [style*="background: #f1f5f9"] { background: #101e35 !important; }

body.dark [style*="background:#f0f2f9"],
body.dark [style*="background: #f0f2f9"] { background: #060e1e !important; }

/* ── Border overrides ── */
body.dark [style*="border:1px solid #e2e8f0"],
body.dark [style*="border: 1px solid #e2e8f0"],
body.dark [style*="border-color:#e2e8f0"],
body.dark [style*="border-color: #e2e8f0"] { border-color: rgba(99,102,241,.12) !important; }

body.dark [style*="border-bottom:1px solid #f1f5f9"],
body.dark [style*="border-bottom: 1px solid #f1f5f9"] { border-color: rgba(99,102,241,.08) !important; }

/* ── Card titles / subtitles ── */
body.dark .card-title { color: #e2eaf8 !important; }
body.dark .card-sub   { color: #4a6fa5 !important; }
body.dark .section-label,
body.dark .section-lbl { color: #4a6fa5 !important; }
body.dark .link-sm { color: #818cf8 !important; }

/* ── Stat cards ── */
body.dark .stat-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .stat-num  { color: #e2eaf8 !important; }
body.dark .stat-lbl,
body.dark .stat-hint { color: #4a6fa5 !important; }
body.dark .stat-badge { opacity: .85; }
body.dark .kpi-card  { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Table ── */
body.dark thead th  { background: #0f1a2e !important; color: #4a6fa5 !important; border-color: rgba(99,102,241,.08) !important; }
body.dark tbody td  { border-color: rgba(99,102,241,.05) !important; }
body.dark tbody tr:hover td { background: #101e35 !important; }
body.dark table thead th { background: #0f1a2e !important; color: #4a6fa5 !important; }

/* ── Tab bar ── */
body.dark .tab-bar { background: #0b1628 !important; border-color: rgba(99,102,241,.12) !important; }
body.dark .tab-btn { color: #7fb3e8 !important; }
body.dark .tab-btn:not(.active):hover { background: rgba(99,102,241,.12) !important; color: #a5b4fc !important; }
body.dark .tab-badge { background: rgba(255,255,255,.12) !important; color: #fff !important; }

/* ── Search / filter inputs ── */
body.dark .search-input,
body.dark .search-field,
body.dark .filter-select,
body.dark .genre-select { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
body.dark .search-input:focus,
body.dark .search-field:focus { background: #0b1628 !important; border-color: #818cf8 !important; }
body.dark .search-input::placeholder,
body.dark .search-field::placeholder { color: #4a6fa5 !important; }

/* ── Filter pills ── */
body.dark .fpill { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .fpill:hover { background: #1a2a42 !important; }

/* ── Quick tabs ── */
body.dark .qtab { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }
body.dark .qtab:hover { border-color: var(--indigo) !important; color: #a5b4fc !important; }
body.dark .reset-btn { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }

/* ── Books table wrap ── */
body.dark .tbl-wrap { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Book cards ── */
body.dark .book-card  { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .book-letter { background: rgba(55,48,163,.2) !important; color: #818cf8 !important; }
body.dark .book-meta-chip { background: #101e35 !important; border-color: rgba(99,102,241,.12) !important; color: #7fb3e8 !important; }
body.dark .book-copies-row { border-color: rgba(99,102,241,.08) !important; }
body.dark .call-badge { background: rgba(92,33,182,.2) !important; color: #a78bfa !important; }

/* ── Copies control (+/−) ── */
body.dark .cpy-btn { background: #101e35 !important; border-color: rgba(99,102,241,.2) !important; color: #7fb3e8 !important; }
body.dark .cpy-btn:hover { background: rgba(99,102,241,.18) !important; }
body.dark .cpy-val  { color: #e2eaf8 !important; }
body.dark .cpy-total { color: #4a6fa5 !important; }

/* ── Action buttons in books table ── */
body.dark .act-edit { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .act-edit:hover { background: rgba(99,102,241,.18) !important; color: #a5b4fc !important; }
body.dark .act-del  { background: rgba(239,68,68,.1) !important; color: #f87171 !important; }
body.dark .act-del:hover { background: rgba(239,68,68,.18) !important; }
body.dark .act-approve { background: rgba(22,163,74,.15) !important; color: #4ade80 !important; }
body.dark .act-approve:hover { background: rgba(22,163,74,.25) !important; }
body.dark .act-reject { background: rgba(239,68,68,.1) !important; color: #f87171 !important; }
body.dark .act-return { background: #101e35 !important; color: #7fb3e8 !important; }

/* ── Borrow cards ── */
body.dark .borrow-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Status tags (darker semantic versions) ── */
body.dark .tag-pending  { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }
body.dark .tag-approved { background: rgba(16,185,129,.1) !important; color: #34d399 !important; }
body.dark .tag-returned { background: rgba(59,130,246,.1) !important; color: #7fb3e8 !important; }
body.dark .tag-rejected { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .tag-active   { background: rgba(16,185,129,.1) !important; color: #34d399 !important; }
body.dark .tag-inactive { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .tag-rag-yes  { background: rgba(139,92,246,.15) !important; color: #c084fc !important; }
body.dark .tag-rag-no   { background: #101e35 !important; color: #4a6fa5 !important; }
body.dark .tag-expired  { background: rgba(100,116,139,.15) !important; color: #94a3b8 !important; }
body.dark .tag-unclaimed { background: rgba(249,115,22,.12) !important; color: #fb923c !important; border-color: rgba(249,115,22,.3) !important; }
body.dark .tag-claimed  { background: rgba(168,85,247,.15) !important; color: #c084fc !important; }
body.dark .tag-declined,
body.dark .tag-canceled { background: rgba(239,68,68,.15) !important; color: #f87171 !important; }

/* ── Badges (reservations view) ── */
body.dark .badge-pending  { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }
body.dark .badge-approved { background: rgba(99,102,241,.15) !important; color: #a5b4fc !important; }
body.dark .badge-declined,
body.dark .badge-canceled { background: rgba(239,68,68,.15) !important; color: #f87171 !important; }
body.dark .badge-claimed  { background: rgba(168,85,247,.15) !important; color: #c084fc !important; }
body.dark .badge-expired  { background: #101e35 !important; color: #4a6fa5 !important; }
body.dark .badge-unclaimed { background: rgba(249,115,22,.12) !important; color: #fb923c !important; border-color: rgba(249,115,22,.3) !important; }

/* ── Modals (general) ── */
body.dark .modal-box,
body.dark .modal-card { background: #0b1628 !important; color: #e2eaf8 !important; }
body.dark .modal-head { border-color: rgba(99,102,241,.1) !important; }
body.dark .modal-title { color: #e2eaf8 !important; }
body.dark .modal-title-lbl { color: #4a6fa5 !important; }
body.dark .modal-close { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .modal-close:hover { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .modal-cancel { background: #101e35 !important; border-color: rgba(99,102,241,.12) !important; color: #7fb3e8 !important; }
body.dark .modal-cancel:hover { background: #1a2a42 !important; }
body.dark .sheet-handle { background: #1e3a5f !important; }

/* ── Modal action buttons with inline styles ── */
body.dark .modal-card button[style*="background:#f8fafc"],
body.dark .modal-card button[style*="background: #f8fafc"],
body.dark .modal-card button[style*="background:#f1f5f9"],
body.dark .modal-card button[style*="background: #f1f5f9"],
body.dark .modal-box button[style*="background:#f1f5f9"] {
    background: #101e35 !important;
    border-color: rgba(99,102,241,.15) !important;
    color: #a5b4fc !important;
}
body.dark .btn-cancel { background: #101e35 !important; color: #7fb3e8 !important; }
body.dark .btn-cancel:hover { background: #1a2a42 !important; }

/* ── Form inputs (add/edit book modals) ── */
body.dark .form-input,
body.dark .field-input { background: #101e35 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
body.dark .form-input:focus,
body.dark .field-input:focus { background: #0b1628 !important; border-color: #818cf8 !important; }
body.dark .form-input::placeholder,
body.dark .field-input::placeholder { color: #4a6fa5 !important; }
body.dark .form-lbl,
body.dark .field-label { color: #4a6fa5 !important; }
body.dark select.form-input,
body.dark select.field-input { background: #101e35 !important; color: #e2eaf8 !important; }
body.dark .form-input.filled { background: rgba(22,163,74,.08) !important; border-color: #22c55e !important; }

/* ── PDF upload / AI extraction ── */
body.dark .drop-zone { background: rgba(55,48,163,.1) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark .drop-zone:hover { background: rgba(55,48,163,.2) !important; border-color: var(--indigo) !important; }
body.dark .ai-progress-bar { background: #101e35 !important; }
body.dark .ai-spinner { border-color: #1a2a42 !important; border-top-color: #818cf8 !important; }
body.dark .step-dot.pending { background: #101e35 !important; color: #4a6fa5 !important; }
body.dark .step-line.pending { background: #101e35 !important; }
body.dark #skDebugPanel { background: rgba(153,27,27,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }

/* ── Flash / alert banners ── */
body.dark .flash-ok { background: rgba(55,48,163,.15) !important; border-color: rgba(99,102,241,.3) !important; color: #a5b4fc !important; }
body.dark .flash-err { background: rgba(153,27,27,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }
body.dark .pending-pill { background: rgba(180,83,9,.2) !important; border-color: rgba(180,83,9,.3) !important; color: #fcd34d !important; }
body.dark .alert-banner.alert-pending { background: rgba(180,83,9,.2) !important; border-color: rgba(180,83,9,.3) !important; color: #fcd34d !important; }
body.dark .alert-banner.alert-success { background: rgba(20,83,45,.25) !important; border-color: rgba(134,239,172,.2) !important; color: #86efac !important; }
body.dark .alert-banner.alert-error   { background: rgba(127,29,29,.2) !important; border-color: rgba(252,165,165,.2) !important; color: #fca5a5 !important; }

/* ── Quick action links ── */
body.dark .qa-link { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #7fb3e8 !important; }
body.dark .qa-link:hover { background: rgba(99,102,241,.1) !important; color: #a5b4fc !important; border-color: var(--indigo) !important; }
body.dark .qa-chev { color: #1e3a5f !important; }

/* ── Booking rows (dashboard) ── */
body.dark .bk-row:hover { background: rgba(99,102,241,.08) !important; }
body.dark .bk-date { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .bk-day  { color: #e2eaf8 !important; }
body.dark .bk-name { color: #e2eaf8 !important; }
body.dark .bk-month, body.dark .bk-time { color: #4a6fa5 !important; }

/* ── Book rows in dashboard library section ── */
body.dark .book-letter { background: rgba(55,48,163,.2) !important; color: #818cf8 !important; }
body.dark .avail-pill.avail-on  { background: rgba(16,185,129,.12) !important; color: #34d399 !important; }
body.dark .avail-pill.avail-off { background: rgba(239,68,68,.12) !important; color: #f87171 !important; }
body.dark .avail-pill.avail-low { background: rgba(251,191,36,.12) !important; color: #fcd34d !important; }

/* ── Book rows with inline styles ── */
body.dark [style*="color:#0f172a"][style*="font-weight:600"],
body.dark .book-title { color: #e2eaf8 !important; }
body.dark .book-author { color: #4a6fa5 !important; }
body.dark .avail-num { color: #4a6fa5 !important; }

/* ── Library banner stats ── */
body.dark .lib-stat-item { background: rgba(255,255,255,.06) !important; border-color: rgba(255,255,255,.08) !important; }

/* ── Session timeline cards (dashboard live monitor) ── */
body.dark .tl-session-card { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .tl-ended .tl-countdown { background: #1a2a42 !important; color: #7fb3e8 !important; }
body.dark .tl-prog-track { background: rgba(99,102,241,.15) !important; }

/* ── Insight cards ── */
body.dark .insight-mini { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Pending pill in books tab ── */
body.dark .tab-badge { background: rgba(255,255,255,.12) !important; }

/* ── Empty states ── */
body.dark .empty-state { background: transparent !important; }
body.dark .card-empty { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Detail rows (modals) ── */
body.dark .drow { border-color: #101e35 !important; }
body.dark .dvalue { color: #e2eaf8 !important; }
body.dark .dlabel { color: #4a6fa5 !important; }
body.dark .detail-row { border-color: #101e35 !important; }
body.dark .detail-label { color: #4a6fa5 !important; }
body.dark .detail-value { color: #e2eaf8 !important; }
body.dark .mrow { border-color: rgba(99,102,241,.06) !important; }
body.dark .mrow-value { color: #e2eaf8 !important; }
body.dark .mrow-label { color: #4a6fa5 !important; }

/* ── Reservation list cards ── */
body.dark .res-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .res-card:hover { border-color: rgba(99,102,241,.3) !important; }
body.dark .req-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Print log / ticket ── */
body.dark #dPrintLogForm { background: #101e35 !important; border-color: rgba(99,102,241,.12) !important; }
body.dark #dPrintLogForm input[type=number] { background: #0b1628 !important; border-color: rgba(99,102,241,.18) !important; color: #e2eaf8 !important; }
body.dark .ticket-section { background: rgba(55,48,163,.12) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark #dPrintLog { background: #101e35 !important; border-color: rgba(99,102,241,.08) !important; }
body.dark .print-pill-yes { background: rgba(99,102,241,.18) !important; color: #a5b4fc !important; }
body.dark .print-pill-no  { background: #101e35 !important; color: #4a6fa5 !important; }

/* ── Unclaimed banner ── */
body.dark .unclaimed-banner { background: rgba(194,65,12,.1) !important; border-color: rgba(249,115,22,.3) !important; }
body.dark .unclaimed-banner .ub-icon { background: rgba(249,115,22,.2) !important; }

/* ── Progress bars ── */
body.dark .prog-bar { background: rgba(99,102,241,.15) !important; }
body.dark .quota-track { background: rgba(99,102,241,.15) !important; }

/* ── Scanner page ── */
body.dark #scanner-viewport { background: #101e35 !important; border-color: rgba(99,102,241,.15) !important; }
body.dark #scanner-viewport.active { border-color: rgba(99,102,241,.4) !important; }
body.dark .cam-status { color: #4a6fa5 !important; }
body.dark .stat-row:hover { background: rgba(99,102,241,.08) !important; }
body.dark .stat-row-lbl { color: #4a6fa5 !important; }
body.dark .stat-row-val { color: #e2eaf8 !important; }
body.dark .history-item { background: #101e35 !important; border-color: rgba(99,102,241,.08) !important; }
body.dark .history-item:hover { background: rgba(99,102,241,.12) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark .result-sheet { background: #0b1628 !important; }
body.dark .result-success { background: #0a1f10 !important; }
body.dark .result-warning { background: #1a1400 !important; }
body.dark .result-error   { background: #1a0a0a !important; }
body.dark .result-claimed { background: #130a1f !important; }
body.dark .camera-error { background: rgba(127,29,29,.2) !important; border-color: rgba(252,165,165,.2) !important; }

/* ── User requests — notif dropdown ── */
body.dark .notif-dd { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; }
body.dark .notif-item { border-color: #101e35 !important; }
body.dark .notif-item.unread { background: rgba(55,48,163,.18) !important; }
body.dark .notif-item:hover { background: #101e35 !important; }
body.dark .toast { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; }

/* ── Reservation/all-reservations page overlays ── */
body.dark .modal-box { background: #0b1628 !important; }
body.dark .overlay-bg { background: rgba(6,14,30,.7) !important; }

/* ── Profile page (SK) ── */
body.dark .profile-status-dot { border-color: #0b1628 !important; }
body.dark .stat-mini { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .stat-mini-val { color: #e2eaf8 !important; }
body.dark .stat-mini-lbl,
body.dark .stat-mini-sub { color: #4a6fa5 !important; }
body.dark .info-icon { background: #101e35 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .info-value { color: #e2eaf8 !important; }
body.dark .info-label { color: #4a6fa5 !important; }
body.dark .info-row { border-color: rgba(99,102,241,.07) !important; }
body.dark .quick-link { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; color: #a5b4fc !important; }
body.dark .quick-link:hover { background: rgba(99,102,241,.12) !important; color: #c7d2fe !important; }
body.dark .danger-row { border-color: rgba(99,102,241,.08) !important; }

/* ── New reservation page ── */
body.dark .form-card { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .section-head { border-color: rgba(99,102,241,.08) !important; }
body.dark .section-title { color: #e2eaf8 !important; }
body.dark .divider { border-color: rgba(99,102,241,.06) !important; }
body.dark .type-toggle { background: #101e35 !important; }
body.dark .type-btn { color: #7fb3e8 !important; }
body.dark .autocomplete-list { background: #0b1628 !important; border-color: rgba(99,102,241,.2) !important; }
body.dark .autocomplete-item { color: #7fb3e8 !important; }
body.dark .autocomplete-item:hover { background: rgba(99,102,241,.12) !important; color: #a5b4fc !important; }
body.dark .autocomplete-item .sub { color: #4a6fa5 !important; }
body.dark .pc-section { background: rgba(55,48,163,.12) !important; border-color: rgba(99,102,241,.2) !important; }
body.dark .pc-btn { background: #101e35 !important; border-color: rgba(99,102,241,.15) !important; color: #a5b4fc !important; }
body.dark .back-btn { background: #0b1628 !important; border-color: rgba(99,102,241,.15) !important; color: #a5b4fc !important; }

/* ── Confirmation modal (new reservation) ── */
body.dark #confirmModal .modal-card { background: #0b1628 !important; }
body.dark .qr-section { background: rgba(55,48,163,.12) !important; border-color: rgba(99,102,241,.25) !important; }
body.dark .mrow { border-color: rgba(99,102,241,.06) !important; }
body.dark .mrow-value { color: #e2eaf8 !important; }

/* ── Delete confirm modal (SK profile) ── */
body.dark #deleteConfirmInput { background: #101e35 !important; border-color: #7f1d1d !important; color: #e2eaf8 !important; }
body.dark #deleteConfirmInput.input-success { background: #052e16 !important; border-color: #16a34a !important; }
body.dark #deleteConfirmInput.input-error { background: #2d0a0a !important; border-color: #f87171 !important; }
body.dark .delete-warning-box { background: rgba(127,29,29,.3) !important; border-color: #7f1d1d !important; }
body.dark .delete-warning-box p { color: #fca5a5 !important; }
body.dark .delete-warning-title { color: #fca5a5 !important; }
body.dark .delete-code-badge { background: rgba(127,29,29,.4) !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }
body.dark .delete-cancel-btn { background: #101e35 !important; border-color: rgba(99,102,241,.2) !important; color: #a5b4fc !important; }

/* ── My reservations (SK) — filter bar ── */
body.dark .filter-bar { background: #0b1628 !important; border-color: rgba(99,102,241,.1) !important; }

/* ── Sync badge (dashboard) ── */
body.dark .sync-badge { background: rgba(29,78,216,.2) !important; color: #7fb3e8 !important; border-color: rgba(59,130,246,.2) !important; }

/* ── Calendar dark overrides (FullCalendar) ── */
body.dark .fc-toolbar-title { color: #e2eaf8 !important; }
body.dark .fc-daygrid-day-number { color: #7fb3e8 !important; }
body.dark .fc-col-header-cell-cushion { color: #4a6fa5 !important; }
body.dark .fc-day-today { background: rgba(55,48,163,.15) !important; }
body.dark .fc-theme-standard td,
body.dark .fc-theme-standard th,
body.dark .fc-theme-standard .fc-scrollgrid { border-color: #101e35 !important; }
body.dark .fc-daygrid-day { background: #0b1628 !important; }
body.dark .fc-daygrid-day:hover { background-color: rgba(99,102,241,.08) !important; }

/* ── Date modal (dashboard) ── */
body.dark #dateModal .modal-card { background: #0b1628 !important; }
body.dark .date-row { border-color: #101e35 !important; }
body.dark .date-row:hover { background: #101e35 !important; }
body.dark #modalDateTitle { color: #e2eaf8 !important; }
body.dark #modalDateSub { color: #4a6fa5 !important; }

/* ── Quota wrap (sidebar) ── */
body.dark .quota-wrap { background: rgba(99,102,241,.07) !important; border-color: rgba(99,102,241,.1) !important; }
body.dark .quota-track { background: rgba(99,102,241,.15) !important; }

/* ── Nav section labels ── */
body.dark .nav-section-lbl { color: #1e3a5f !important; }
body.dark .brand-tag,
body.dark .brand-sub { color: #4a6fa5 !important; }

/* ── Scrollbars ── */
body.dark ::-webkit-scrollbar-thumb { background: #1e3a5f !important; }
body.dark ::-webkit-scrollbar-track { background: #060e1e !important; }
    </style>
</head>
<body>
<?php
$navItems = [
    ['url'=>'/sk/dashboard',       'icon'=>'fa-house',        'label'=>'Dashboard',        'key'=>'dashboard'],
    ['url'=>'/sk/reservations',    'icon'=>'fa-calendar-alt', 'label'=>'All Reservations', 'key'=>'reservations'],
    ['url'=>'/sk/new-reservation', 'icon'=>'fa-plus',         'label'=>'New Reservation',  'key'=>'new-reservation'],
    ['url'=>'/sk/user-requests',   'icon'=>'fa-users',        'label'=>'User Requests',    'key'=>'user-requests'],
    ['url'=>'/sk/my-reservations', 'icon'=>'fa-calendar',     'label'=>'My Reservations',  'key'=>'my-reservations'],
    ['url'=>'/sk/books',           'icon'=>'fa-book-open',    'label'=>'Library',          'key'=>'books'],
    ['url'=>'/sk/scanner',         'icon'=>'fa-qrcode',       'label'=>'Scanner',          'key'=>'scanner'],
    ['url'=>'/sk/profile',         'icon'=>'fa-user',         'label'=>'Profile',          'key'=>'profile'],
];
?>

<!-- ══════════════════════════════════════════
     EDIT PROFILE MODAL
══════════════════════════════════════════ -->
<div id="editModal" class="modal-back" onclick="if(event.target===this)closeModal('editModal')">
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px">
            <div>
                <h3 style="font-size:1rem;font-weight:800;letter-spacing:-.02em">Update Profile</h3>
                <p style="font-size:.72rem;margin-top:2px">Changes are saved immediately.</p>
            </div>
            <button onclick="closeModal('editModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fa-solid fa-xmark" style="font-size:.8rem;color:#64748b"></i>
            </button>
        </div>
        <form action="<?= base_url('sk/profile/update') ?>" method="POST" style="display:flex;flex-direction:column;gap:16px">
            <?= csrf_field() ?>
            <div>
                <label class="field-label">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="field-input" required>
            </div>
            <div>
                <label class="field-label">Contact Number</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="field-input" placeholder="+63 912 345 6789">
            </div>
            <div>
                <label class="field-label">New Password</label>
                <input type="password" name="password" id="pwInput" class="field-input" placeholder="Leave blank to keep current" oninput="checkPw(this.value)">
                <div class="pw-strength"><div id="pwFill" class="pw-fill" style="width:0%;background:#e2e8f0"></div></div>
                <p class="field-hint" id="pwHint">Minimum 8 characters</p>
            </div>
            <div style="display:flex;gap:10px;padding-top:4px">
                <button type="button" onclick="closeModal('editModal')" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background .15s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Cancel</button>
                <button type="submit" style="flex:2;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);transition:background .15s" onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════
     DELETE ACCOUNT MODAL
══════════════════════════════════════════ -->
<div id="deleteModal" class="modal-back" onclick="if(event.target===this)closeModal('deleteModal')">
    <div class="modal-card" style="max-width:440px">
        <div class="sheet-handle"></div>

        <!-- Header -->
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;gap:12px">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:46px;height:46px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid #fecaca">
                    <i class="fa-solid fa-trash" style="font-size:1.1rem;color:#dc2626"></i>
                </div>
                <div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Delete Account?</h3>
                    <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;font-weight:500">This cannot be undone</p>
                </div>
            </div>
            <button onclick="closeModal('deleteModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;transition:background .15s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fa-solid fa-xmark" style="font-size:.8rem;color:#64748b"></i>
            </button>
        </div>

        <!-- Warning banner -->
        <div class="delete-warning-box" style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-sm);padding:14px 16px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start">
            <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;font-size:.9rem;margin-top:2px;flex-shrink:0"></i>
            <div>
                <p class="delete-warning-title" style="font-size:.8rem;font-weight:700;color:#b91c1c;margin-bottom:4px">Warning: Permanent Action</p>
                <p style="font-size:.74rem;color:#dc2626;line-height:1.55">All your reservations, borrowings and profile data will be <strong>permanently deleted</strong>. You will be immediately logged out and <strong>cannot recover</strong> your account.</p>
            </div>
        </div>

        <!-- Confirm input -->
        <div style="margin-bottom:20px">
            <label class="field-label" style="color:#dc2626;margin-bottom:10px">
                Type <span class="delete-code-badge" style="font-family:var(--mono);background:#fef2f2;border:1px solid #fecaca;padding:2px 8px;border-radius:6px;font-size:.65rem;font-weight:800;color:#b91c1c;letter-spacing:.05em">DELETE</span> to confirm
            </label>
            <input
                type="text"
                id="deleteConfirmInput"
                placeholder="Type DELETE here…"
                class="field-input"
                style="border-color:#fecaca;margin-top:8px"
                oninput="checkDeleteInput(this.value)"
                autocomplete="off"
            >
            <p id="deleteInputHint" style="font-size:.65rem;color:#94a3b8;margin-top:6px;font-weight:500">This action is irreversible. Case-sensitive.</p>
        </div>

        <!-- Actions -->
        <div style="display:flex;gap:10px">
            <button
                type="button"
                onclick="closeModal('deleteModal')"
                class="delete-cancel-btn"
                style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid #e2e8f0;cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background .15s"
                onmouseover="this.style.background='#f1f5f9'"
                onmouseout="this.style.background='#f8fafc'"
            >Cancel</button>
            <button
                type="button"
                id="deleteConfirmBtn"
                onclick="submitDeleteAccount()"
                disabled
                style="flex:2;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:not-allowed;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;opacity:.4;transition:all .2s"
            >
                <i class="fa-solid fa-trash" style="font-size:.75rem"></i>
                <span id="deleteSubmitTxt">Delete My Account</span>
            </button>
        </div>

        <p id="deleteErrMsg" style="font-size:.72rem;color:#dc2626;font-weight:600;margin-top:10px;min-height:18px;text-align:center"></p>

        <!-- Hidden form for submission -->
        <form id="deleteAccountForm" action="<?= base_url('sk/profile/delete') ?>" method="POST" style="display:none">
            <?= csrf_field() ?>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════ -->
<aside class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-top">
            <div class="brand-tag">SK Officer Portal</div>
            <div class="brand-name">my<em>Space.</em></div>
            <div class="brand-sub">Community Management</div>
        </div>
        <div class="user-card">
            <div class="user-avatar"><?= $avatarLetter ?></div>
            <div style="min-width:0">
                <div class="user-name-txt"><?= htmlspecialchars($user['name'] ?? 'SK Officer') ?></div>
                <div class="user-role-txt">SK Officer</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-lbl">Menu</div>
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $showBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                    <div class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>"></i></div>
                    <?= $item['label'] ?>
                    <?php if ($showBadge): ?><span class="nav-badge"><?= $pendingUserCount ?></span><?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,68,68,.08)"><i class="fa-solid fa-arrow-right-from-bracket" style="color:#f87171"></i></div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- ══════════════════════════════════════════
     MOBILE NAV
══════════════════════════════════════════ -->
<nav class="mobile-nav-pill">
    <div class="mobile-scroll-container">
        <?php foreach ($navItems as $item):
            $active = ($page == $item['key']);
            $showBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
        ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= htmlspecialchars($item['label']) ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem"></i>
                <?php if ($showBadge): ?>
                    <span style="position:absolute;top:6px;right:20%;background:#ef4444;color:white;font-size:.5rem;font-weight:700;width:14px;height:14px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white"><?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem;color:#f87171"></i>
        </a>
    </div>
</nav>

<!-- ══════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════ -->
<main class="main-area">

    <!-- Topbar -->
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px" class="fade-up">
        <div>
            <p style="font-size:.7rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px">Account</p>
            <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1">My Profile</h2>
            <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Manage your account settings and security.</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
            <div class="icon-btn" onclick="toggleDark()" id="darkBtn" title="Toggle dark mode">
                <span id="dark-icon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
            </div>
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;padding:5px 12px;border-radius:999px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border)">
                <i class="fa-solid fa-shield-halved" style="font-size:.6rem"></i> SK Verified
            </span>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-ok"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-err"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Profile grid -->
    <div id="profileGrid" style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.6fr);gap:16px" class="fade-up-1">

        <!-- ── LEFT column ── -->
        <div style="display:flex;flex-direction:column;gap:14px">

            <!-- Avatar card -->
            <div class="card card-p" style="text-align:center">
                <div style="position:relative;display:inline-block;margin:0 auto 18px">
                    <div class="profile-avatar"><?= $avatarLetter ?></div>
                    <div class="profile-status-dot">
                        <i class="fa-solid fa-check" style="font-size:.55rem;color:white"></i>
                    </div>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em"><?= htmlspecialchars($user['name'] ?? 'SK Officer') ?></h3>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                <?php if (!empty($user['phone'])): ?>
                    <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;font-family:var(--mono)"><?= htmlspecialchars($user['phone']) ?></p>
                <?php endif; ?>
                <div style="margin-top:12px">
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;padding:4px 12px;border-radius:999px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border)">
                        <i class="fa-solid fa-shield-halved" style="font-size:.55rem"></i> SK Officer
                    </span>
                </div>
                <button class="edit-btn" onclick="openModal('editModal')">
                    <i class="fa-solid fa-pen-to-square" style="font-size:.8rem"></i> Edit Profile
                </button>
            </div>

            <!-- Activity card -->
            <div class="card card-p">
                <div class="section-lbl">Account Activity</div>
                <div class="stat-mini-grid">
                    <div class="stat-mini">
                        <div class="stat-mini-lbl">Member Since</div>
                        <div class="stat-mini-val" style="font-size:.95rem;font-family:var(--font)"><?= $memberYear ?></div>
                        <div class="stat-mini-sub">Year joined</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-lbl">Status</div>
                        <div style="display:flex;align-items:center;gap:5px;margin-top:4px">
                            <div style="width:8px;height:8px;background:#10b981;border-radius:50%;box-shadow:0 0 0 3px rgba(16,185,129,.15);flex-shrink:0"></div>
                            <div class="stat-mini-val" style="font-size:.85rem;font-family:var(--font)">Active</div>
                        </div>
                        <div class="stat-mini-sub">Verified</div>
                    </div>
                </div>
            </div>

            <!-- Quick access -->
            <div class="card card-p">
                <div class="section-lbl">Quick Access</div>
                <div style="display:flex;flex-direction:column;gap:5px">
                    <?php foreach ([
                        ['/sk/reservations',    'fa-calendar-alt', 'var(--indigo-light)', 'var(--indigo)', 'All Reservations'],
                        ['/sk/user-requests',   'fa-users',        '#ede9fe',             '#7c3aed',       'User Requests'],
                        ['/sk/new-reservation', 'fa-plus',         '#fef3c7',             '#d97706',       'New Reservation'],
                        ['/sk/scanner',         'fa-qrcode',       '#f3e8ff',             '#9333ea',       'QR Scanner'],
                    ] as [$url, $icon, $bg, $fg, $label]): ?>
                        <a href="<?= $url ?>" class="quick-link">
                            <div style="width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $bg ?>">
                                <i class="fa-solid <?= $icon ?>" style="font-size:.8rem;color:<?= $fg ?>"></i>
                            </div>
                            <?= $label ?>
                            <i class="fa-solid fa-chevron-right" style="font-size:.65rem;color:#cbd5e1;margin-left:auto"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ── RIGHT column ── -->
        <div style="display:flex;flex-direction:column;gap:14px">

            <!-- Personal info -->
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="card-icon" style="background:var(--indigo-light)">
                            <i class="fa-solid fa-id-badge" style="color:var(--indigo);font-size:.9rem"></i>
                        </div>
                        <div>
                            <div class="card-title">Personal Information</div>
                            <div class="card-sub">Your account details</div>
                        </div>
                    </div>
                    <button onclick="openModal('editModal')" class="action-btn-sm">
                        <i class="fa-solid fa-pen-to-square" style="font-size:.7rem"></i> Edit
                    </button>
                </div>
                <?php foreach ([
                    ['fa-user',     'Full Name',      $user['name']  ?? 'Not set'],
                    ['fa-envelope', 'Email Address',  $user['email'] ?? 'Not set'],
                    ['fa-phone',    'Contact Number', $user['phone'] ?? 'Not set'],
                    ['fa-star',     'Role',           'Sangguniang Kabataan Officer'],
                    ['fa-calendar', 'Member Since',   $memberSince],
                ] as $f): ?>
                    <div class="info-row">
                        <div class="info-icon">
                            <i class="fa-solid <?= $f[0] ?>" style="font-size:.8rem;color:#94a3b8"></i>
                        </div>
                        <div style="min-width:0">
                            <div class="info-label"><?= $f[1] ?></div>
                            <div class="info-value"><?= htmlspecialchars($f[2]) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Security -->
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="card-icon" style="background:#fef3c7">
                            <i class="fa-solid fa-shield-halved" style="color:#d97706;font-size:.9rem"></i>
                        </div>
                        <div>
                            <div class="card-title">Security</div>
                            <div class="card-sub">Password and account protection</div>
                        </div>
                    </div>
                </div>
                <div class="danger-row">
                    <div style="min-width:0">
                        <p style="font-size:.83rem;font-weight:600;color:#0f172a">Password</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Update regularly to keep your account safe</p>
                    </div>
                    <button onclick="openModal('editModal')" class="action-btn-sm">Change</button>
                </div>
                <div class="danger-row">
                    <div style="min-width:0">
                        <p style="font-size:.83rem;font-weight:600;color:#0f172a">Account Access</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Signed in as an SK Officer</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;padding:5px 12px;background:#dcfce7;border-radius:999px;flex-shrink:0">
                        <i class="fa-solid fa-check" style="font-size:.65rem;color:#16a34a"></i>
                        <span style="font-size:.62rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.05em">Active</span>
                    </div>
                </div>
            </div>

            <!-- Tip banner -->
            <div class="tip-banner">
                <div class="tip-icon">
                    <i class="fa-solid fa-lightbulb" style="font-size:1rem;color:white"></i>
                </div>
                <div style="position:relative;z-index:1">
                    <h5 style="font-size:.88rem;font-weight:700;color:white;line-height:1.3">Keep your info up to date</h5>
                    <p style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;line-height:1.5">Ensure your contact details are correct so notifications reach you properly.</p>
                </div>
            </div>

            <!-- Danger zone -->
            <div class="card card-p-lg">
                <div class="card-head" style="margin-bottom:8px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="card-icon" style="background:#fef2f2">
                            <i class="fa-solid fa-trash" style="color:#dc2626;font-size:.85rem"></i>
                        </div>
                        <div>
                            <div class="card-title" style="color:#dc2626">Danger Zone</div>
                            <div class="card-sub">Irreversible account actions</div>
                        </div>
                    </div>
                </div>
                <div class="danger-row" style="padding-top:12px;border-bottom:none;padding-bottom:0">
                    <div style="min-width:0">
                        <p style="font-size:.83rem;font-weight:600;color:#0f172a">Delete Account</p>
                        <p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Permanently remove your account and all data. Cannot be undone.</p>
                    </div>
                    <button class="danger-btn" onclick="openModal('deleteModal')">
                        <i class="fa-solid fa-trash" style="font-size:.7rem;margin-right:4px"></i> Delete
                    </button>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
/* ══════════════════════════════════════════
   Modal helpers
══════════════════════════════════════════ */
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.add('show');
    document.body.style.overflow = 'hidden';

    if (id === 'deleteModal') {
        // Reset every time
        const inp  = document.getElementById('deleteConfirmInput');
        const hint = document.getElementById('deleteInputHint');
        const err  = document.getElementById('deleteErrMsg');
        inp.value = '';
        inp.className = 'field-input';
        inp.style.borderColor = '#fecaca';
        inp.style.background  = '';
        hint.textContent = 'This action is irreversible. Case-sensitive.';
        hint.style.color = '#94a3b8';
        err.textContent  = '';
        resetDeleteBtn();
        setTimeout(() => inp.focus(), 220);
    }
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeModal('editModal');
        closeModal('deleteModal');
    }
});

/* ══════════════════════════════════════════
   Password strength meter
══════════════════════════════════════════ */
function checkPw(val) {
    const fill = document.getElementById('pwFill');
    const hint = document.getElementById('pwHint');
    if (!val) {
        fill.style.width = '0%';
        fill.style.background = '#e2e8f0';
        hint.textContent = 'Minimum 8 characters';
        hint.style.color = '#94a3b8';
        return;
    }
    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;
    const labels = ['Too short', 'Weak', 'Fair', 'Good', 'Strong'];
    const colors = ['#e2e8f0', '#f87171', '#fbbf24', '#34d399', '#10b981'];
    fill.style.width      = (score * 25) + '%';
    fill.style.background = colors[score];
    hint.textContent      = labels[score];
    hint.style.color      = colors[score];
}

/* ══════════════════════════════════════════
   Delete confirm input
══════════════════════════════════════════ */
function checkDeleteInput(val) {
    const btn  = document.getElementById('deleteConfirmBtn');
    const hint = document.getElementById('deleteInputHint');
    const inp  = document.getElementById('deleteConfirmInput');

    if (val === 'DELETE') {
        // Enable button
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor  = 'pointer';
        btn.style.boxShadow = '0 4px 14px rgba(220,38,38,.3)';
        // Input success state
        inp.className = 'field-input input-success';
        inp.style.borderColor = '';
        inp.style.background  = '';
        hint.textContent = '✓ Confirmed — you may now delete your account';
        hint.style.color = '#16a34a';
    } else {
        resetDeleteBtn();
        if (val.length > 0) {
            inp.className = 'field-input input-error';
            inp.style.borderColor = '';
            inp.style.background  = '';
            hint.textContent = 'Must be exactly "DELETE" in uppercase';
            hint.style.color = '#dc2626';
        } else {
            inp.className = 'field-input';
            inp.style.borderColor = '#fecaca';
            inp.style.background  = '';
            hint.textContent = 'This action is irreversible. Case-sensitive.';
            hint.style.color = '#94a3b8';
        }
    }
}

function resetDeleteBtn() {
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled    = true;
    btn.style.opacity    = '.4';
    btn.style.cursor     = 'not-allowed';
    btn.style.boxShadow  = 'none';
}

/* ══════════════════════════════════════════
   Submit delete — plain form POST (reliable,
   avoids JSON/route issues)
══════════════════════════════════════════ */
function submitDeleteAccount() {
    const val = document.getElementById('deleteConfirmInput').value.trim();
    if (val !== 'DELETE') {
        document.getElementById('deleteErrMsg').textContent = 'Please type DELETE exactly to confirm.';
        return;
    }

    const btn     = document.getElementById('deleteConfirmBtn');
    const txtSpan = document.getElementById('deleteSubmitTxt');
    btn.disabled  = true;
    btn.style.opacity = '.7';
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.75rem"></i>&nbsp; Deleting…';
    document.getElementById('deleteErrMsg').textContent = '';

    // Submit the hidden form
    document.getElementById('deleteAccountForm').submit();
}

/* ══════════════════════════════════════════
   Dark mode
══════════════════════════════════════════ */
function toggleDark() {
    const isDark = document.body.classList.toggle('dark');
    const icon   = document.getElementById('dark-icon');
    icon.innerHTML = isDark
        ? '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>'
        : '<i class="fa-regular fa-sun"  style="font-size:.85rem"></i>';
    localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
}

document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('sk_theme') === 'dark') {
        document.body.classList.add('dark');
        const icon = document.getElementById('dark-icon');
        if (icon) icon.innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>';
    }
    document.documentElement.classList.remove('dark-pre');

    // Responsive grid
    function checkGrid() {
        const g = document.getElementById('profileGrid');
        if (g) g.style.gridTemplateColumns = window.innerWidth < 900 ? '1fr' : 'minmax(0,1fr) minmax(0,1.6fr)';
    }
    checkGrid();
    window.addEventListener('resize', checkGrid);
});
</script>
</body>
</html>