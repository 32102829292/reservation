<?php
date_default_timezone_set('Asia/Manila');
$page = $page ?? 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Profile | SK</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#15803d">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script>(function(){if(localStorage.getItem('sk_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
        :root{
            --green:#15803d;--green-mid:#16a34a;--green-light:#f0fdf4;--green-border:#bbf7d0;
            --bg:#f0f4f1;--card:#ffffff;
            --font:'Plus Jakarta Sans',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;
            --shadow-sm:0 1px 4px rgba(15,23,42,.07),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.09),0 2px 4px rgba(15,23,42,.04);
            --shadow-lg:0 12px 40px rgba(15,23,42,.12),0 4px 8px rgba(15,23,42,.06);
            --r-sm:10px;--r-md:14px;--r-lg:20px;--r-xl:24px;
            --sidebar-w:268px;--ease:.18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h:60px;--mob-nav-total:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));
        }
        html{height:100%;height:100dvh;font-size:16px}
        body{font-family:var(--font);background:var(--bg);color:#0f172a;display:flex;height:100vh;height:100dvh;overflow:hidden;font-size:1rem;line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden}
        html.dark-pre body{background:#031a0a}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;padding:18px 14px;height:100vh;height:100dvh;display:flex;flex-direction:column}
        .sidebar-inner{background:var(--card);border-radius:var(--r-xl);border:1px solid rgba(22,163,74,.12);height:100%;display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-md)}
        .sidebar-top{padding:22px 18px 16px;border-bottom:1px solid rgba(22,163,74,.08)}
        .brand-tag{font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#94a3b8;margin-bottom:5px}
        .brand-name{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.03em;line-height:1.1}
        .brand-name em{font-style:normal;color:var(--green)}
        .brand-sub{font-size:.7rem;color:#94a3b8;margin-top:3px}
        .user-card{margin:12px 12px 0;background:var(--green-light);border-radius:var(--r-md);padding:12px 14px;border:1px solid var(--green-border)}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:var(--green);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0;box-shadow:0 2px 8px rgba(21,128,61,.3)}
        .user-name-txt{font-size:.8rem;font-weight:700;color:#0f172a;letter-spacing:-.01em}
        .user-role-txt{font-size:.68rem;color:#16a34a;font-weight:500;margin-top:1px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px;display:flex;flex-direction:column;gap:3px}
        .sidebar-nav::-webkit-scrollbar{width:2px}
        .sidebar-nav::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:2px}
        .nav-section-lbl{font-size:.6rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#cbd5e1;padding:10px 10px 5px;margin-top:2px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:.85rem;font-weight:600;color:#64748b;text-decoration:none;transition:all var(--ease)}
        .nav-link:hover{background:var(--green-light);color:var(--green)}
        .nav-link.active{background:var(--green);color:#fff;box-shadow:0 4px 14px rgba(21,128,61,.32)}
        .nav-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .nav-link.active .nav-icon{background:rgba(255,255,255,.15)}
        .nav-link:not(.active) .nav-icon{background:#f1f5f9}
        .nav-link:hover:not(.active) .nav-icon{background:#dcfce7}
        .nav-badge{margin-left:auto;background:rgba(239,68,68,.15);color:#dc2626;font-size:.6rem;font-weight:700;padding:2px 7px;border-radius:999px}
        .nav-link.active .nav-badge{background:rgba(255,255,255,.22);color:#fff}
        .sidebar-footer{padding:10px 10px 12px;border-top:1px solid rgba(22,163,74,.07)}
        .logout-link{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--r-sm);font-size:.85rem;font-weight:600;color:#94a3b8;text-decoration:none;transition:all var(--ease)}
        .logout-link:hover{background:#fef2f2;color:#dc2626}

        /* ── Mobile Nav ── */
        .mobile-nav-pill{display:none;position:fixed;bottom:0;left:0;right:0;width:100%;background:white;border-top:1px solid rgba(22,163,74,.12);height:var(--mob-nav-total);z-index:200;box-shadow:0 -4px 20px rgba(21,128,61,.1)}
        .mobile-scroll-container{display:flex;justify-content:space-evenly;align-items:center;height:var(--mob-nav-h);width:100%}
        .mob-nav-item{flex:1;display:flex;align-items:center;justify-content:center;height:48px;border-radius:14px;cursor:pointer;text-decoration:none;color:#64748b;position:relative;transition:background .15s,color .15s}
        .mob-nav-item:hover,.mob-nav-item.active{background:var(--green-light);color:var(--green)}
        .mob-nav-item.active::after{content:'';position:absolute;bottom:4px;left:50%;transform:translateX(-50%);width:4px;height:4px;background:var(--green);border-radius:50%}
        .mob-logout{color:#94a3b8}
        .mob-logout:hover{background:#fef2f2;color:#dc2626}

        @media(max-width:1023px){.sidebar{display:none!important}.mobile-nav-pill{display:flex!important}.main-area{padding-bottom:calc(var(--mob-nav-total) + 16px)!important}}
        @media(min-width:1024px){.sidebar{display:flex!important}.mobile-nav-pill{display:none!important}}

        /* ── Main ── */
        .main-area{flex:1;min-width:0;padding:24px 28px 40px;height:100vh;height:100dvh;overflow-y:auto;overflow-x:hidden;-webkit-overflow-scrolling:touch;overscroll-behavior-y:contain}
        @media(max-width:1023px){.main-area::-webkit-scrollbar{display:none}.main-area{scrollbar-width:none}}
        @media(min-width:1024px){.main-area::-webkit-scrollbar{width:4px}.main-area::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}}

        /* ── Topbar ── */
        .topbar{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;gap:16px}
        .greeting-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px}
        .greeting-name{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1}
        .greeting-sub{font-size:.78rem;color:#94a3b8;margin-top:4px;font-weight:500}
        .topbar-right{display:flex;align-items:center;gap:10px;flex-shrink:0;margin-top:4px}
        .icon-btn{width:44px;height:44px;background:white;border:1px solid rgba(22,163,74,.12);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;transition:all var(--ease);box-shadow:var(--shadow-sm)}
        .icon-btn:hover{background:var(--green-light);border-color:var(--green-border);color:var(--green)}

        /* ── Cards ── */
        .card{background:var(--card);border-radius:var(--r-lg);border:1px solid rgba(22,163,74,.08);box-shadow:var(--shadow-sm)}
        .card-p{padding:20px 22px}
        .card-p-lg{padding:22px 24px}
        .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
        .card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .card-title{font-size:.9rem;font-weight:700;color:#0f172a;letter-spacing:-.01em}
        .card-sub{font-size:.7rem;color:#94a3b8;margin-top:2px}
        .section-lbl{font-size:.62rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:14px}

        /* ── Flash ── */
        .flash-ok{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding:13px 18px;background:var(--green-light);border:1px solid var(--green-border);color:var(--green);font-weight:600;border-radius:var(--r-md);font-size:.9rem;animation:slideUp .4s ease both}
        .flash-err{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding:13px 18px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-weight:600;border-radius:var(--r-md);font-size:.9rem;animation:slideUp .4s ease both}

        /* ── Profile Avatar ── */
        .profile-avatar{width:80px;height:80px;background:linear-gradient(135deg,var(--green) 0%,#16a34a 60%,#4ade80 100%);border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:white;box-shadow:0 8px 24px rgba(21,128,61,.3);font-family:var(--mono);letter-spacing:-.04em}
        .profile-status-dot{position:absolute;bottom:-4px;right:-4px;width:22px;height:22px;background:#10b981;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center}

        /* ── Info rows ── */
        .info-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid rgba(22,163,74,.06)}
        .info-row:last-child{border-bottom:none}
        .info-icon{width:34px;height:34px;border-radius:10px;background:#f8fafc;border:1px solid rgba(22,163,74,.09);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .info-label{font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8}
        .info-value{font-size:.85rem;font-weight:600;color:#0f172a;margin-top:1px}

        /* ── Mini stats ── */
        .stat-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .stat-mini{background:#f8fafc;border:1px solid rgba(22,163,74,.09);border-radius:var(--r-sm);padding:12px 14px}
        .stat-mini-lbl{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:#94a3b8;margin-bottom:4px}
        .stat-mini-val{font-size:1.25rem;font-weight:800;color:#0f172a;font-family:var(--mono);line-height:1;letter-spacing:-.03em}
        .stat-mini-sub{font-size:.68rem;color:#94a3b8;margin-top:3px}

        /* ── Tip banner ── */
        .tip-banner{background:linear-gradient(135deg,var(--green) 0%,#16a34a 60%,#22c55e 100%);border-radius:var(--r-lg);padding:20px 22px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden}
        .tip-banner::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;opacity:.4}
        .tip-icon{width:42px;height:42px;background:rgba(255,255,255,.15);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1}

        /* ── Edit btn ── */
        .edit-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;background:var(--green);color:white;border-radius:var(--r-sm);font-size:.85rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);box-shadow:0 4px 12px rgba(21,128,61,.28);margin-top:16px}
        .edit-btn:hover{background:#14532d;transform:translateY(-1px);box-shadow:0 6px 18px rgba(21,128,61,.35)}

        /* ── Quick links ── */
        .quick-link{display:flex;align-items:center;gap:10px;padding:10px 10px;border-radius:var(--r-sm);border:1px solid rgba(22,163,74,.09);background:white;text-decoration:none;color:#475569;font-size:.83rem;font-weight:600;transition:all var(--ease)}
        .quick-link:hover{border-color:var(--green);background:var(--green-light);color:var(--green)}

        /* ── Tags ── */
        .tag{display:inline-flex;align-items:center;gap:3px;padding:3px 9px;border-radius:999px;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em}
        .tag-green{background:var(--green-light);color:#14532d;border:1px solid var(--green-border)}

        /* ── Danger zone ── */
        .danger-row{display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(22,163,74,.06);gap:12px}
        .danger-row:last-child{border-bottom:none}
        .danger-btn{font-size:.75rem;font-weight:700;padding:8px 14px;border-radius:9px;border:1px solid #fecaca;background:#fef2f2;color:#dc2626;cursor:pointer;font-family:var(--font);transition:all var(--ease);white-space:nowrap;flex-shrink:0}
        .danger-btn:hover{background:#fee2e2;border-color:#f87171}

        /* ── Password strength ── */
        .pw-strength{height:3px;border-radius:999px;background:#e2e8f0;overflow:hidden;margin-top:6px}
        .pw-fill{height:100%;border-radius:999px;transition:width .3s,background .3s}

        /* ── Modal ── */
        .modal-back{display:none;position:fixed;inset:0;background:rgba(15,23,42,.52);backdrop-filter:blur(6px);z-index:300;padding:1.5rem;overflow-y:auto;align-items:center;justify-content:center}
        .modal-back.show{display:flex;animation:fadeIn .15s ease}
        .modal-card{background:white;border-radius:var(--r-xl);width:100%;max-width:480px;padding:24px;max-height:calc(100dvh - 3rem);overflow-y:auto;margin:auto;animation:slideUp .2s ease;box-shadow:var(--shadow-lg)}
        .field-label{display:block;font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px}
        .field-input{width:100%;background:#f8fafc;border:1px solid rgba(22,163,74,.15);border-radius:var(--r-sm);padding:11px 14px;font-family:var(--font);font-size:.87rem;font-weight:600;color:#0f172a;transition:all .2s;outline:none}
        .field-input:focus{border-color:#4ade80;background:white;box-shadow:0 0 0 3px rgba(22,163,74,.08)}
        .field-hint{font-size:.65rem;color:#94a3b8;margin-top:4px}
        .sheet-handle{display:none;width:36px;height:4px;background:#e2e8f0;border-radius:999px;margin:0 auto 16px}
        @media(max-width:639px){.modal-back{padding:0;align-items:flex-end!important}.modal-card{border-radius:var(--r-xl) var(--r-xl) 0 0;max-width:100%;max-height:92dvh;animation:sheetUp .25s cubic-bezier(.34,1.2,.64,1) both}.sheet-handle{display:block}}
        @keyframes sheetUp{from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none}}

        /* ── Animations ── */
        @keyframes slideUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        .fade-up{animation:slideUp .4s ease both}
        .fade-up-1{animation:slideUp .45s .05s ease both}
        .fade-up-2{animation:slideUp .45s .1s ease both}
        .fade-up-3{animation:slideUp .45s .15s ease both}

        @media(max-width:639px){.main-area{padding:14px 12px 0}}

        /* ── Dark mode ── */
        body.dark{--bg:#031a0a;--card:#061a0e;--green-light:rgba(21,128,61,.12);--green-border:rgba(22,163,74,.25);color:#e2eaf8}
        body.dark .sidebar-inner{background:#061a0e;border-color:rgba(22,163,74,.12)}
        body.dark .sidebar-top,.body.dark .sidebar-footer{border-color:rgba(22,163,74,.1)}
        body.dark .brand-name{color:#e2eaf8}
        body.dark .nav-link{color:#6ee7a0}
        body.dark .nav-link:hover{background:rgba(22,163,74,.12);color:#4ade80}
        body.dark .nav-link:not(.active) .nav-icon{background:rgba(22,163,74,.1)}
        body.dark .user-card{background:rgba(21,128,61,.15);border-color:rgba(22,163,74,.2)}
        body.dark .user-name-txt{color:#e2eaf8}
        body.dark .greeting-name{color:#e2eaf8}
        body.dark .card{background:#061a0e;border-color:rgba(22,163,74,.1)}
        body.dark .card-title{color:#e2eaf8}
        body.dark .icon-btn{background:#061a0e;border-color:rgba(22,163,74,.15);color:#6ee7a0}
        body.dark .icon-btn:hover{background:rgba(22,163,74,.12)}
        body.dark .info-icon{background:#0a2e14;border-color:rgba(22,163,74,.1)}
        body.dark .info-value{color:#e2eaf8}
        body.dark .stat-mini{background:#0a2e14;border-color:rgba(22,163,74,.1)}
        body.dark .stat-mini-val{color:#e2eaf8}
        body.dark .mobile-nav-pill{background:#061a0e;border-color:rgba(22,163,74,.18)}
        body.dark .mob-nav-item{color:#6ee7a0}
        body.dark .mob-nav-item.active{background:rgba(22,163,74,.18)}
        body.dark .modal-card{background:#061a0e;color:#e2eaf8}
        body.dark .field-input{background:#0a2e14;border-color:rgba(22,163,74,.18);color:#e2eaf8}
        body.dark .field-input:focus{background:#061a0e}
        body.dark .flash-ok{background:rgba(21,128,61,.15);border-color:rgba(22,163,74,.3);color:#4ade80}
        body.dark .danger-row{border-color:rgba(22,163,74,.08)}
        body.dark .info-row{border-color:rgba(22,163,74,.06)}
        body.dark .profile-status-dot{border-color:#061a0e}
        body.dark .quick-link{background:#061a0e;border-color:rgba(22,163,74,.1);color:#6ee7a0}
    </style>
</head>
<body>
<?php
$navItems = [
    ['url'=>'/sk/dashboard',           'icon'=>'house',     'label'=>'Dashboard',        'key'=>'dashboard'],
    ['url'=>'/sk/reservations',        'icon'=>'calendar',  'label'=>'All Reservations', 'key'=>'reservations'],
    ['url'=>'/sk/new-reservation',     'icon'=>'plus',      'label'=>'New Reservation',  'key'=>'new-reservation'],
    ['url'=>'/sk/user-requests',       'icon'=>'users',     'label'=>'User Requests',    'key'=>'user-requests'],
    ['url'=>'/sk/my-reservations',     'icon'=>'bookmark',  'label'=>'My Reservations',  'key'=>'my-reservations'],
    ['url'=>'/sk/books',               'icon'=>'book-open', 'label'=>'Library',          'key'=>'books'],
    ['url'=>'/sk/scanner',             'icon'=>'qrcode',    'label'=>'Scanner',          'key'=>'scanner'],
    ['url'=>'/sk/profile',             'icon'=>'user',      'label'=>'Profile',          'key'=>'profile'],
];
$pendingUserCount = $pendingUserCount ?? 0;
$avatarLetter = strtoupper(mb_substr(trim($user['name'] ?? 'S'), 0, 1));
$memberSince  = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : '—';
$memberYear   = isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y');

function skIcon($name, $size = 16, $stroke = 'currentColor') {
    $icons = [
        'house'     => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'plus'      => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'users'     => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-linecap="round" stroke-linejoin="round"/>',
        'bookmark'  => '<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>',
        'book-open' => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'qrcode'    => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="5" y="5" width="3" height="3" fill="currentColor" stroke="none"/><rect x="16" y="5" width="3" height="3" fill="currentColor" stroke="none"/><rect x="5" y="16" width="3" height="3" fill="currentColor" stroke="none"/><path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 17h3" stroke-linecap="round"/>',
        'user'      => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'logout'    => '<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round"/>',
        'sun'       => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
        'moon'      => '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>',
        'edit'      => '<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"/>',
        'id'        => '<path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" stroke-linecap="round" stroke-linejoin="round"/>',
        'mail'      => '<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/>',
        'phone'     => '<path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar2' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/>',
        'shield'    => '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/>',
        'star'      => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke-linecap="round" stroke-linejoin="round"/>',
        'trash'     => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" stroke-linecap="round" stroke-linejoin="round"/>',
        'lock'      => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round" stroke-linejoin="round"/>',
        'check'     => '<polyline points="20 6 9 17 4 12"/>',
        'check-c'   => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>',
        'x'         => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'lightbulb' => '<path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
    $d = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="'.$stroke.'" stroke-width="1.8">'.$d.'</svg>';
}
?>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-back" onclick="handleModalBack(event,'editModal')">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;gap:12px;">
                <div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;">Update Profile</h3>
                    <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Changes are saved immediately.</p>
                </div>
                <button onclick="closeModal('editModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><?= skIcon('x',12,'#64748b') ?></button>
            </div>
            <form action="<?= base_url('sk/profile/update') ?>" method="POST" style="display:flex;flex-direction:column;gap:16px;">
                <?= csrf_field() ?>
                <div><label class="field-label">Full Name</label><input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="field-input" required></div>
                <div><label class="field-label">Email Address</label><input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="field-input" required></div>
                <div><label class="field-label">Contact Number</label><input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="field-input" placeholder="+63 912 345 6789"></div>
                <div>
                    <label class="field-label">New Password</label>
                    <input type="password" name="password" id="pwInput" class="field-input" placeholder="Leave blank to keep current" oninput="checkPw(this.value)">
                    <div class="pw-strength"><div id="pwFill" class="pw-fill" style="width:0%;background:#e2e8f0;"></div></div>
                    <p class="field-hint" id="pwHint">Minimum 8 characters</p>
                </div>
                <div style="display:flex;gap:10px;padding-top:6px;">
                    <button type="button" onclick="closeModal('editModal')" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(22,163,74,.1);cursor:pointer;font-size:.82rem;font-family:var(--font);">Cancel</button>
                    <button type="submit" style="flex:2;padding:12px;background:var(--green);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);box-shadow:0 4px 12px rgba(21,128,61,.28);">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <aside class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-top">
                <div class="brand-tag">SK Officer</div>
                <div class="brand-name">Portal<em>.</em></div>
                <div class="brand-sub">Youth Management System</div>
            </div>
            <div class="user-card" style="display:flex;align-items:center;gap:9px;">
                <div class="user-avatar"><?= $avatarLetter ?></div>
                <div style="min-width:0;">
                    <div class="user-name-txt" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($user['name'] ?? 'SK Officer') ?></div>
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
                        <div class="nav-icon"><?= skIcon($item['icon'], 16, $active ? 'white' : '#64748b') ?></div>
                        <?= $item['label'] ?>
                        <?php if ($showBadge): ?><span class="nav-badge"><?= $pendingUserCount ?></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <a href="/logout" class="logout-link">
                    <div class="nav-icon" style="background:rgba(239,68,68,.08);"><?= skIcon('logout', 16, '#f87171') ?></div>
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <nav class="mobile-nav-pill">
        <div class="mobile-scroll-container">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
                $showBadge = ($item['key'] === 'user-requests' && $pendingUserCount > 0);
            ?>
                <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= htmlspecialchars($item['label']) ?>">
                    <?= skIcon($item['icon'], 22, $active ? 'var(--green)' : '#64748b') ?>
                    <?php if ($showBadge): ?><span style="position:absolute;top:6px;right:20%;background:#ef4444;color:white;font-size:.5rem;font-weight:700;width:14px;height:14px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white;"><?= $pendingUserCount > 9 ? '9+' : $pendingUserCount ?></span><?php endif; ?>
                </a>
            <?php endforeach; ?>
            <a href="/logout" class="mob-nav-item mob-logout" title="Sign Out"><?= skIcon('logout', 22, '#f87171') ?></a>
        </div>
    </nav>

    <main class="main-area">
        <div class="topbar fade-up">
            <div>
                <div class="greeting-eyebrow">Account</div>
                <div class="greeting-name">My Profile</div>
                <div class="greeting-sub">Manage your account settings and security.</div>
            </div>
            <div class="topbar-right">
                <div class="icon-btn" onclick="toggleDark()" id="darkBtn">
                    <span id="dark-icon"><?= skIcon('sun', 14, '#94a3b8') ?></span>
                </div>
                <span class="tag tag-green" style="font-size:.6rem;font-weight:700;">SK Verified</span>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok"><?= skIcon('check-c',15,'var(--green)') ?><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><?= skIcon('x',15,'#dc2626') ?><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.6fr);gap:16px;" id="profileGrid" class="fade-up-1">

            <!-- LEFT -->
            <div style="display:flex;flex-direction:column;gap:14px;">

                <div class="card card-p" style="text-align:center;">
                    <div style="position:relative;display:inline-block;margin:0 auto 18px;">
                        <div class="profile-avatar"><?= $avatarLetter ?></div>
                        <div class="profile-status-dot"><?= skIcon('check',8,'white') ?></div>
                    </div>
                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;"><?= htmlspecialchars($user['name'] ?? 'SK Officer') ?></h3>
                    <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <?php if (!empty($user['phone'])): ?><p style="font-size:.72rem;color:#94a3b8;margin-top:2px;font-family:var(--mono);"><?= htmlspecialchars($user['phone']) ?></p><?php endif; ?>
                    <div style="margin-top:12px;"><span class="tag tag-green">SK Officer</span></div>
                    <button class="edit-btn" onclick="openModal('editModal')"><?= skIcon('edit',14,'white') ?> Edit Profile</button>
                </div>

                <div class="card card-p">
                    <div class="section-lbl">Account Activity</div>
                    <div class="stat-mini-grid">
                        <div class="stat-mini">
                            <div class="stat-mini-lbl">Member Since</div>
                            <div class="stat-mini-val" style="font-size:.95rem;font-weight:800;"><?= $memberYear ?></div>
                            <div class="stat-mini-sub">Year joined</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-lbl">Status</div>
                            <div style="display:flex;align-items:center;gap:5px;margin-top:2px;">
                                <div style="width:8px;height:8px;background:#10b981;border-radius:50%;box-shadow:0 0 0 3px rgba(16,185,129,.15);flex-shrink:0;"></div>
                                <div class="stat-mini-val" style="font-size:.85rem;font-family:var(--font);">Active</div>
                            </div>
                            <div class="stat-mini-sub">Verified</div>
                        </div>
                    </div>
                </div>

                <div class="card card-p">
                    <div class="section-lbl">Quick Access</div>
                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <?php $links = [
                            ['/sk/reservations',    'calendar',  '#f0fdf4', 'var(--green)', 'All Reservations'],
                            ['/sk/user-requests',   'users',     '#ede9fe', '#7c3aed',       'User Requests'],
                            ['/sk/new-reservation', 'plus',      '#fef3c7', '#d97706',        'New Reservation'],
                            ['/sk/scanner',         'qrcode',    '#f3e8ff', '#9333ea',        'QR Scanner'],
                        ];
                        foreach ($links as [$url, $icon, $bg, $fg, $label]): ?>
                        <a href="<?= $url ?>" class="quick-link">
                            <div style="width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $bg ?>;"><?= skIcon($icon,14,$fg) ?></div>
                            <?= $label ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div style="display:flex;flex-direction:column;gap:14px;">

                <div class="card card-p-lg">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:var(--green-light);"><?= skIcon('id',16,'var(--green)') ?></div>
                            <div><div class="card-title">Personal Information</div><div class="card-sub">Your account details</div></div>
                        </div>
                        <button onclick="openModal('editModal')" style="display:flex;align-items:center;gap:6px;padding:8px 14px;background:var(--green-light);border:1px solid var(--green-border);border-radius:9px;font-size:.72rem;font-weight:700;color:var(--green);cursor:pointer;font-family:var(--font);">
                            <?= skIcon('edit',12,'currentColor') ?> Edit
                        </button>
                    </div>
                    <?php $fields = [
                        ['id',       'Full Name',      $user['name']  ?? 'Not set'],
                        ['mail',     'Email Address',  $user['email'] ?? 'Not set'],
                        ['phone',    'Contact Number', $user['phone'] ?? 'Not set'],
                        ['star',     'Role',           'Sangguniang Kabataan Officer'],
                        ['calendar2','Member Since',   $memberSince],
                    ];
                    foreach ($fields as $f): ?>
                    <div class="info-row">
                        <div class="info-icon"><?= skIcon($f[0],14,'#94a3b8') ?></div>
                        <div style="min-width:0;">
                            <div class="info-label"><?= $f[1] ?></div>
                            <div class="info-value"><?= htmlspecialchars($f[2]) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="card card-p-lg">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:#fef3c7;"><?= skIcon('shield',16,'#d97706') ?></div>
                            <div><div class="card-title">Security</div><div class="card-sub">Password and account protection</div></div>
                        </div>
                    </div>
                    <div class="danger-row">
                        <div style="min-width:0;">
                            <p style="font-size:.83rem;font-weight:600;color:#0f172a;">Password</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Update regularly to keep your account safe</p>
                        </div>
                        <button onclick="openModal('editModal')" style="display:flex;align-items:center;gap:6px;padding:8px 14px;background:var(--green-light);border:1px solid var(--green-border);border-radius:9px;font-size:.72rem;font-weight:700;color:var(--green);cursor:pointer;font-family:var(--font);">Change</button>
                    </div>
                    <div class="danger-row" style="border-bottom:none;padding-bottom:0;">
                        <div style="min-width:0;">
                            <p style="font-size:.83rem;font-weight:600;color:#0f172a;">Account Access</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Signed in as an SK Officer</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;padding:6px 12px;background:#dcfce7;border-radius:999px;flex-shrink:0;">
                            <?= skIcon('check',10,'#16a34a') ?>
                            <span style="font-size:.65rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.05em;">Active</span>
                        </div>
                    </div>
                </div>

                <div class="tip-banner">
                    <div class="tip-icon"><?= skIcon('lightbulb',18,'white') ?></div>
                    <div style="position:relative;z-index:1;">
                        <h5 style="font-size:.88rem;font-weight:700;color:white;line-height:1.3;">Keep your info up to date</h5>
                        <p style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;line-height:1.5;">Ensure your contact details are correct so notifications and system alerts reach you properly.</p>
                    </div>
                </div>

                <div class="card card-p-lg">
                    <div class="card-head" style="margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="card-icon" style="background:#fef2f2;"><?= skIcon('trash',15,'#dc2626') ?></div>
                            <div><div class="card-title" style="color:#dc2626;">Danger Zone</div><div class="card-sub">Irreversible account actions</div></div>
                        </div>
                    </div>
                    <div class="danger-row" style="padding-top:12px;">
                        <div style="min-width:0;">
                            <p style="font-size:.83rem;font-weight:600;color:#0f172a;">Delete Account</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px;">Permanently remove your account and all data</p>
                        </div>
                        <button class="danger-btn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function openModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow='hidden'; }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow=''; }
        function handleModalBack(e, id) { if (e.target.classList.contains('modal-back')) closeModal(id); }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal('editModal'); });

        function checkPw(val) {
            const fill = document.getElementById('pwFill'), hint = document.getElementById('pwHint');
            if (!val) { fill.style.width='0%'; fill.style.background='#e2e8f0'; hint.textContent='Minimum 8 characters'; return; }
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            const labels = ['Too short','Weak','Fair','Good','Strong'];
            const colors = ['#e2e8f0','#f87171','#fbbf24','#34d399','#10b981'];
            fill.style.width = (score*25)+'%'; fill.style.background = colors[score];
            hint.textContent = labels[score]; hint.style.color = colors[score];
        }

        function toggleDark() {
            const isDark = document.body.classList.toggle('dark');
            const icon = document.getElementById('dark-icon');
            icon.innerHTML = isDark
                ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`
                : `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
            localStorage.setItem('sk_theme', isDark ? 'dark' : 'light');
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sk_theme') === 'dark') {
                document.body.classList.add('dark');
                const icon = document.getElementById('dark-icon');
                if (icon) icon.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>`;
            }
            document.documentElement.classList.remove('dark-pre');
            function checkGrid() {
                const grid = document.getElementById('profileGrid');
                if (grid) grid.style.gridTemplateColumns = window.innerWidth < 900 ? '1fr' : 'minmax(0,1fr) minmax(0,1.6fr)';
            }
            checkGrid();
            window.addEventListener('resize', checkGrid);
        });
    </script>
</body>
</html>