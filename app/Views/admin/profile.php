<?php
date_default_timezone_set('Asia/Manila');
$page = $page ?? 'profile';
$avatarLetter = strtoupper(mb_substr(trim($user['name'] ?? 'A'), 0, 1));
$memberSince  = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : '—';
$memberYear   = isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Profile | Admin</title>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>(function(){if(localStorage.getItem('admin_theme')==='dark')document.documentElement.classList.add('dark-pre')})();</script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}

        :root{
            --blue:#2563eb;--blue-mid:#1d4ed8;--blue-light:#eff6ff;--blue-border:#bfdbfe;
            --bg:#f8fafc;--card:#ffffff;
            --font:'Plus Jakarta Sans',system-ui,sans-serif;--mono:'JetBrains Mono',monospace;
            --shadow-sm:0 1px 4px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
            --shadow-md:0 4px 16px rgba(15,23,42,.08),0 2px 4px rgba(15,23,42,.04);
            --shadow-lg:0 12px 40px rgba(15,23,42,.12),0 4px 8px rgba(15,23,42,.06);
            --r-sm:10px;--r-md:14px;--r-lg:20px;--r-xl:24px;--r-2xl:32px;
            --sidebar-w:280px;--ease:.18s cubic-bezier(.4,0,.2,1);
            --mob-nav-h:60px;--mob-nav-total:calc(var(--mob-nav-h) + env(safe-area-inset-bottom,0px));
        }

        html{height:100%;height:100dvh;font-size:16px}
        body{font-family:var(--font);background:var(--bg);color:#1e293b;display:flex;height:100vh;height:100dvh;overflow:hidden;-webkit-font-smoothing:antialiased;overflow-x:hidden}
        html.dark-pre body{background:#060e1e}

        /* ── Sidebar ── */
        .sidebar{width:var(--sidebar-w);flex-shrink:0;padding:24px;height:100vh;height:100dvh;display:flex;flex-direction:column}
        .sidebar-inner{background:var(--card);border-radius:var(--r-2xl);border:1px solid #e2e8f0;height:100%;display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-md)}
        .sidebar-top{padding:20px 20px 16px;border-bottom:1px solid #f1f5f9;flex-shrink:0}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px;display:flex;flex-direction:column;gap:3px;scrollbar-width:thin;scrollbar-color:#e2e8f0 transparent}
        .sidebar-nav::-webkit-scrollbar{width:4px}
        .sidebar-nav::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:4px}
        .sidebar-footer{flex-shrink:0;padding:16px;border-top:1px solid #f1f5f9}
        .nav-link{display:flex;align-items:center;gap:14px;padding:12px 18px;border-radius:16px;font-weight:600;font-size:.875rem;text-decoration:none;color:#64748b;transition:all var(--ease)}
        .nav-link:hover{background:var(--blue-light);color:var(--blue)}
        .nav-link.active{background:var(--blue);color:white;box-shadow:0 8px 20px -4px rgba(37,99,235,.35)}
        .nav-link .nav-icon{width:20px;text-align:center;font-size:1rem;flex-shrink:0}
        .logout-link{display:flex;align-items:center;gap:14px;padding:12px 18px;border-radius:16px;font-weight:600;font-size:.875rem;text-decoration:none;color:#ef4444;transition:all var(--ease)}
        .logout-link:hover{background:#fef2f2}

        /* ── Mobile nav ── */
        .mobile-nav-pill{display:none;position:fixed;bottom:calc(16px + env(safe-area-inset-bottom,0px));left:50%;transform:translateX(-50%);width:92%;max-width:600px;background:rgba(15,23,42,.97);backdrop-filter:blur(12px);border-radius:24px;padding:6px;z-index:100;box-shadow:0 20px 25px -5px rgba(0,0,0,.3)}
        .mobile-scroll-container{display:flex;gap:4px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none}
        .mobile-scroll-container::-webkit-scrollbar{display:none}
        .mob-nav-item{flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:8px 12px;min-width:72px;border-radius:14px;text-decoration:none;color:rgba(255,255,255,.6);font-size:.65rem;font-weight:600;transition:all .15s;gap:3px}
        .mob-nav-item:hover,.mob-nav-item.active{background:rgba(37,99,235,.5);color:white}
        .mob-logout-item{color:rgba(239,68,68,.8)}
        .mob-logout-item:hover{background:rgba(239,68,68,.2);color:#f87171}

        @media(max-width:1023px){.sidebar{display:none!important}.mobile-nav-pill{display:block!important}.main-area{padding-bottom:calc(var(--mob-nav-total) + 80px)!important}}
        @media(min-width:1024px){.sidebar{display:flex!important}.mobile-nav-pill{display:none!important}}

        /* ── Main ── */
        .main-area{flex:1;min-width:0;height:100vh;height:100dvh;overflow-y:auto;overflow-x:hidden;-webkit-overflow-scrolling:touch}
        @media(max-width:1023px){.main-area::-webkit-scrollbar{display:none}.main-area{scrollbar-width:none}}
        @media(min-width:1024px){.main-area::-webkit-scrollbar{width:4px}.main-area::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}}

        .main-inner{width:100%;max-width:960px;margin:0 auto;padding:24px 24px 60px}
        @media(max-width:639px){.main-inner{padding:16px 14px 60px}}

        /* ── Cards ── */
        .card{background:var(--card);border-radius:var(--r-2xl);border:1px solid #e2e8f0;box-shadow:var(--shadow-sm)}
        .card-p{padding:24px}
        .card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
        .card-title{font-size:.9rem;font-weight:800;color:#1e293b;text-transform:uppercase;letter-spacing:.1em}
        .section-lbl{font-size:.62rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;margin-bottom:14px}

        /* ── Flash ── */
        .flash-ok{display:flex;align-items:center;gap:12px;margin-bottom:20px;padding:13px 18px;background:var(--blue-light);border:1px solid var(--blue-border);color:var(--blue);font-weight:700;border-radius:var(--r-lg);font-size:.875rem;animation:slideUp .4s ease both}
        .flash-err{display:flex;align-items:center;gap:12px;margin-bottom:20px;padding:13px 18px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;font-weight:700;border-radius:var(--r-lg);font-size:.875rem;animation:slideUp .4s ease both}

        /* ── Avatar ── */
        .profile-avatar{width:88px;height:88px;background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 50%,#60a5fa 100%);border-radius:26px;display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:800;color:white;box-shadow:0 8px 32px rgba(37,99,235,.35)}
        .profile-status-dot{position:absolute;bottom:-4px;right:-4px;width:24px;height:24px;background:#10b981;border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center}

        /* ── Info rows ── */
        .info-row{display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid #f1f5f9}
        .info-row:last-child{border-bottom:none}
        .info-icon{width:38px;height:38px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .info-label{font-size:.6rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8}
        .info-value{font-size:.875rem;font-weight:700;color:#1e293b;margin-top:2px}

        /* ── Stat mini ── */
        .stat-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .stat-mini{background:#f8fafc;border:1px solid #e2e8f0;border-radius:var(--r-md);padding:14px}
        .stat-mini-lbl{font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#94a3b8;margin-bottom:4px}
        .stat-mini-val{font-size:1.3rem;font-weight:800;color:#1e293b;font-family:var(--mono);line-height:1;letter-spacing:-.03em}
        .stat-mini-sub{font-size:.68rem;color:#94a3b8;margin-top:3px}

        /* ── Quick links ── */
        .quick-link{display:flex;align-items:center;gap:10px;padding:12px;border-radius:var(--r-md);border:1px solid #e2e8f0;background:white;text-decoration:none;color:#475569;font-size:.83rem;font-weight:600;transition:all var(--ease)}
        .quick-link:hover{border-color:var(--blue);background:var(--blue-light);color:var(--blue)}

        /* ── Buttons ── */
        .edit-btn-main{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px;background:var(--blue);color:white;border-radius:var(--r-md);font-size:.875rem;font-weight:700;border:none;cursor:pointer;font-family:var(--font);transition:all var(--ease);box-shadow:0 4px 14px rgba(37,99,235,.3);margin-top:16px}
        .edit-btn-main:hover{background:var(--blue-mid);transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,99,235,.38)}
        .action-btn-sm{display:flex;align-items:center;gap:6px;padding:8px 16px;background:var(--blue-light);border:1px solid var(--blue-border);border-radius:9px;font-size:.72rem;font-weight:700;color:var(--blue);cursor:pointer;font-family:var(--font);transition:all var(--ease)}
        .action-btn-sm:hover{background:var(--blue);color:white}

        /* ── Tip banner ── */
        .tip-banner{background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 50%,#3b82f6 100%);border-radius:var(--r-2xl);padding:22px 24px;display:flex;align-items:center;gap:16px;position:relative;overflow:hidden}
        .tip-banner::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;opacity:.4}
        .tip-icon{width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:1}

        /* ── Danger zone ── */
        .danger-row{display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid #f1f5f9;gap:12px}
        .danger-row:last-child{border-bottom:none;padding-bottom:0}
        .danger-btn{font-size:.75rem;font-weight:700;padding:8px 16px;border-radius:9px;border:1px solid #fecaca;background:#fef2f2;color:#dc2626;cursor:pointer;font-family:var(--font);transition:all var(--ease);white-space:nowrap;flex-shrink:0}
        .danger-btn:hover{background:#fee2e2;border-color:#f87171}

        /* ── Password strength ── */
        .pw-strength{height:3px;border-radius:999px;background:#e2e8f0;overflow:hidden;margin-top:6px}
        .pw-fill{height:100%;border-radius:999px;transition:width .3s,background .3s}

        /* ── Modal ── */
        .modal-back{display:none;position:fixed;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(8px);z-index:300;padding:1.5rem;overflow-y:auto;align-items:center;justify-content:center}
        .modal-back.show{display:flex;animation:fadeIn .18s ease}
        .modal-card{background:white;border-radius:var(--r-2xl);width:100%;max-width:480px;padding:28px;max-height:calc(100dvh - 3rem);overflow-y:auto;margin:auto;animation:slideUp .22s ease;box-shadow:var(--shadow-lg)}
        .field-label{display:block;font-size:.62rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px}
        .field-input{width:100%;background:#f8fafc;border:1px solid #e2e8f0;border-radius:var(--r-md);padding:12px 14px;font-family:var(--font);font-size:.92rem;font-weight:700;color:#1e293b;transition:all .2s;outline:none}
        .field-input:focus{border-color:var(--blue);background:white;box-shadow:0 0 0 4px rgba(37,99,235,.08)}
        .field-hint{font-size:.65rem;color:#94a3b8;margin-top:4px}
        .sheet-handle{display:none;width:36px;height:4px;background:#e2e8f0;border-radius:999px;margin:0 auto 16px}

        @media(max-width:639px){
            .modal-back{padding:0;align-items:flex-end!important}
            .modal-card{border-radius:var(--r-2xl) var(--r-2xl) 0 0;max-width:100%;max-height:92dvh;animation:sheetUp .25s cubic-bezier(.34,1.2,.64,1) both}
            .sheet-handle{display:block}
        }

        /* ── Delete input states ── */
        .input-success{border-color:#86efac!important;background:#f0fdf4!important}
        .input-error{border-color:#f87171!important;background:#fff5f5!important}

        /* ── Icon btn ── */
        .icon-btn{width:44px;height:44px;background:white;border:1px solid #e2e8f0;border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;transition:all var(--ease);box-shadow:var(--shadow-sm)}
        .icon-btn:hover{background:var(--blue-light);border-color:var(--blue-border);color:var(--blue)}

        /* ── Animations ── */
        @keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        @keyframes sheetUp{from{opacity:0;transform:translateY(60px)}to{opacity:1;transform:none}}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        .fade-up{animation:slideUp .4s ease both}
        .fade-up-1{animation:slideUp .45s .05s ease both}

        /* ── Dark mode ── */
        body.dark{--bg:#060e1e;--card:#0b1628;--blue-light:rgba(37,99,235,.12);--blue-border:rgba(96,165,250,.25);color:#e2eaf8}
        body.dark .sidebar-inner{background:#0b1628;border-color:rgba(99,102,241,.12)}
        body.dark .sidebar-top,.body.dark .sidebar-footer{border-color:rgba(99,102,241,.1)}
        body.dark .nav-link{color:#93c5fd}
        body.dark .nav-link:hover{background:rgba(37,99,235,.12);color:#bfdbfe}
        body.dark .card{background:#0b1628;border-color:rgba(99,102,241,.1)}
        body.dark .card-title{color:#e2eaf8}
        body.dark .info-icon{background:#101e35;border-color:rgba(99,102,241,.1)}
        body.dark .info-value{color:#e2eaf8}
        body.dark .stat-mini{background:#101e35;border-color:rgba(99,102,241,.1)}
        body.dark .stat-mini-val{color:#e2eaf8}
        body.dark .quick-link{background:#0b1628;border-color:rgba(99,102,241,.1);color:#93c5fd}
        body.dark .quick-link:hover{background:rgba(37,99,235,.12);color:#bfdbfe;border-color:var(--blue)}
        body.dark .danger-row{border-color:rgba(99,102,241,.08)}
        body.dark .info-row{border-color:rgba(99,102,241,.08)}
        body.dark .profile-status-dot{border-color:#0b1628}
        body.dark .icon-btn{background:#0b1628;border-color:rgba(99,102,241,.15);color:#93c5fd}
        body.dark .icon-btn:hover{background:rgba(37,99,235,.12)}
        body.dark .pw-strength{background:rgba(99,102,241,.15)}

        /* ── Dark modal ── */
        body.dark .modal-card{background:#0b1628;color:#e2eaf8}
        body.dark .modal-card h3{color:#e2eaf8}
        body.dark .modal-card h4{color:#e2eaf8}
        body.dark .modal-card p{color:#93c5fd}
        body.dark .modal-card .field-label{color:#64748b}
        body.dark .sheet-handle{background:#1e3a5f}
        body.dark .field-input{background:#101e35;border-color:rgba(99,102,241,.18);color:#e2eaf8}
        body.dark .field-input:focus{background:#0b1628}

        /* ── Dark delete modal ── */
        body.dark #deleteConfirmInput{background:#101e35;border-color:#7f1d1d;color:#e2eaf8}
        body.dark #deleteConfirmInput.input-success{background:#052e16!important;border-color:#16a34a!important;color:#e2eaf8}
        body.dark #deleteConfirmInput.input-error{background:#2d0a0a!important;border-color:#f87171!important;color:#e2eaf8}
        body.dark .delete-warning-box{background:rgba(127,29,29,.3)!important;border-color:#7f1d1d!important}
        body.dark .delete-warning-box p{color:#fca5a5!important}
        body.dark .delete-warning-title{color:#fca5a5!important}
        body.dark .delete-code-badge{background:rgba(127,29,29,.4)!important;border-color:#7f1d1d!important;color:#fca5a5!important}
        body.dark .delete-cancel-btn{background:#101e35!important;border-color:rgba(99,102,241,.2)!important;color:#93c5fd!important}
        body.dark .delete-cancel-btn:hover{background:#1e3a5f!important}
    </style>
</head>
<body>

<?php
$navItems = [
    ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
    ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
    ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
    ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
    ['url' => '/admin/books',               'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
    ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
    ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
    ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
    ['url' => '/admin/profile',             'icon' => 'fa-user',            'label' => 'Profile',         'key' => 'profile'],
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
                <h3 style="font-size:1.05rem;font-weight:800;letter-spacing:-.02em">Update Profile</h3>
                <p style="font-size:.75rem;margin-top:3px">Changes will be logged in the activity trail.</p>
            </div>
            <button onclick="closeModal('editModal')" style="width:34px;height:34px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fa-solid fa-xmark" style="font-size:.8rem;color:#64748b"></i>
            </button>
        </div>
        <form action="<?= base_url('admin/profile/update') ?>" method="POST" style="display:flex;flex-direction:column;gap:16px">
            <?= csrf_field() ?>
            <div><label class="field-label">Full Name</label><input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="field-input" required></div>
            <div><label class="field-label">Email Address</label><input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="field-input" required></div>
            <div><label class="field-label">Contact Number</label><input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="field-input" placeholder="e.g. +63 912 345 6789"></div>
            <div>
                <label class="field-label">New Password</label>
                <input type="password" name="password" id="pwInput" class="field-input" placeholder="Leave blank to keep current" oninput="checkPw(this.value)">
                <div class="pw-strength"><div id="pwFill" class="pw-fill" style="width:0%;background:#e2e8f0"></div></div>
                <p class="field-hint" id="pwHint">Minimum 8 characters</p>
            </div>
            <div style="display:flex;gap:10px;padding-top:4px">
                <button type="button" onclick="closeModal('editModal')" style="flex:1;padding:13px;background:#f8fafc;border-radius:var(--r-md);font-weight:700;color:#475569;border:1px solid #e2e8f0;cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background .15s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Cancel</button>
                <button type="submit" style="flex:2;padding:13px;background:var(--blue);color:white;border-radius:var(--r-md);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);box-shadow:0 4px 12px rgba(37,99,235,.3);transition:background .15s" onmouseover="this.style.background='var(--blue-mid)'" onmouseout="this.style.background='var(--blue)'">Save Changes</button>
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

        <!-- Warning -->
        <div class="delete-warning-box" style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-sm);padding:14px 16px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start">
            <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;font-size:.9rem;margin-top:2px;flex-shrink:0"></i>
            <div>
                <p class="delete-warning-title" style="font-size:.8rem;font-weight:700;color:#b91c1c;margin-bottom:4px">Warning: Permanent Action</p>
                <p style="font-size:.74rem;color:#dc2626;line-height:1.55">All admin records, logs, and account data will be <strong>permanently deleted</strong>. You will be immediately logged out and <strong>cannot recover</strong> your account.</p>
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
                style="flex:1;padding:13px;background:#f8fafc;border-radius:var(--r-md);font-weight:700;color:#475569;border:1px solid #e2e8f0;cursor:pointer;font-size:.82rem;font-family:var(--font);transition:background .15s"
                onmouseover="this.style.background='#f1f5f9'"
                onmouseout="this.style.background='#f8fafc'"
            >Cancel</button>
            <button
                type="button"
                id="deleteConfirmBtn"
                onclick="submitDeleteAccount()"
                disabled
                style="flex:2;padding:13px;background:#dc2626;color:white;border-radius:var(--r-md);font-weight:700;border:none;cursor:not-allowed;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;opacity:.4;transition:all .2s"
            >
                <i class="fa-solid fa-trash" style="font-size:.75rem"></i>
                <span id="deleteSubmitTxt">Delete My Account</span>
            </button>
        </div>

        <p id="deleteErrMsg" style="font-size:.72rem;color:#dc2626;font-weight:600;margin-top:10px;min-height:18px;text-align:center"></p>

        <!-- Hidden form -->
        <form id="deleteAccountForm" action="<?= base_url('admin/profile/delete') ?>" method="POST" style="display:none">
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
            <span style="font-size:.65rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:var(--blue)">Control Room</span>
            <h1 style="font-size:1.5rem;font-weight:900;color:#1e293b;margin-top:2px;letter-spacing:-.03em">Admin<span style="color:var(--blue)">.</span></h1>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']);
            ?>
                <a href="<?= $item['url'] ?>" class="nav-link <?= $active ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid <?= $item['icon'] ?>"></i></span>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <span style="width:20px;text-align:center"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                Logout
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
        ?>
            <a href="<?= $item['url'] ?>" class="mob-nav-item <?= $active ? 'active' : '' ?>" title="<?= htmlspecialchars($item['label']) ?>">
                <i class="fa-solid <?= $item['icon'] ?>" style="font-size:1.1rem"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="mob-nav-item mob-logout-item" title="Logout">
            <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:1.1rem"></i>
            <span>Logout</span>
        </a>
    </div>
</nav>

<!-- ══════════════════════════════════════════
     MAIN
══════════════════════════════════════════ -->
<div class="main-area">
    <div class="main-inner">

        <!-- Header -->
        <header style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px" class="fade-up">
            <div>
                <p style="font-size:.62rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.2em;margin-bottom:4px">Account</p>
                <h2 style="font-size:1.75rem;font-weight:900;color:#1e293b;letter-spacing:-.04em;line-height:1.1">My Profile</h2>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Manage your account settings and security.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
                <div class="icon-btn" onclick="toggleDark()" title="Toggle dark mode">
                    <span id="dark-icon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                </div>
                <span style="display:none;align-items:center;gap:5px;font-size:.62rem;font-weight:800;padding:6px 14px;border-radius:999px;background:var(--blue-light);color:var(--blue);border:1px solid var(--blue-border);text-transform:uppercase;letter-spacing:.08em" class="md-flex">
                    System Verified
                </span>
            </div>
        </header>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-ok"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Grid -->
        <div id="profileGrid" style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,2fr);gap:20px" class="fade-up-1">

            <!-- LEFT -->
            <div style="display:flex;flex-direction:column;gap:16px">

                <!-- Avatar card -->
                <div class="card card-p" style="text-align:center">
                    <div style="position:relative;display:inline-block;margin:0 auto 20px">
                        <div class="profile-avatar"><?= $avatarLetter ?></div>
                        <div class="profile-status-dot"><i class="fa-solid fa-check" style="font-size:.55rem;color:white"></i></div>
                    </div>
                    <h3 style="font-size:1.05rem;font-weight:800;color:#1e293b;letter-spacing:-.02em"><?= htmlspecialchars($user['name'] ?? 'Administrator') ?></h3>
                    <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <div style="margin-top:12px">
                        <span style="display:inline-block;padding:5px 14px;background:#f1f5f9;color:#475569;border-radius:999px;font-size:.62rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em">Administrator</span>
                    </div>
                    <button class="edit-btn-main" onclick="openModal('editModal')">
                        <i class="fa-solid fa-pen-to-square" style="font-size:.8rem"></i> Edit Profile
                    </button>
                </div>

                <!-- Activity -->
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
                            <div class="stat-mini-sub">Full access</div>
                        </div>
                    </div>
                </div>

                <!-- Quick access -->
                <div class="card card-p">
                    <div class="section-lbl">Quick Access</div>
                    <div style="display:flex;flex-direction:column;gap:6px">
                        <?php foreach ([
                            ['/admin/manage-reservations', 'fa-calendar',    'var(--blue-light)', 'var(--blue)',  'Reservations'],
                            ['/admin/manage-sk',           'fa-user-shield', '#ede9fe',           '#7c3aed',      'Manage SK'],
                            ['/admin/manage-pcs',          'fa-desktop',     '#fef3c7',           '#d97706',      'Manage PCs'],
                            ['/admin/activity-logs',       'fa-list',        '#f3e8ff',           '#9333ea',      'Activity Logs'],
                        ] as [$url, $icon, $bg, $fg, $label]): ?>
                            <a href="<?= $url ?>" class="quick-link">
                                <div style="width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $bg ?>">
                                    <i class="fa-solid <?= $icon ?>" style="font-size:.8rem;color:<?= $fg ?>"></i>
                                </div>
                                <?= $label ?>
                                <i class="fa-solid fa-chevron-right" style="font-size:.65rem;color:#cbd5e1;margin-left:auto"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div style="display:flex;flex-direction:column;gap:16px">

                <!-- Account info -->
                <div class="card card-p">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:38px;height:38px;border-radius:12px;background:var(--blue-light);display:flex;align-items:center;justify-content:center">
                                <i class="fa-solid fa-id-badge" style="color:var(--blue);font-size:.9rem"></i>
                            </div>
                            <div>
                                <div class="card-title">Account Information</div>
                                <p style="font-size:.7rem;color:#94a3b8;margin-top:2px">Your admin account details</p>
                            </div>
                        </div>
                        <button onclick="openModal('editModal')" class="action-btn-sm">
                            <i class="fa-solid fa-pen-to-square" style="font-size:.7rem"></i> Edit
                        </button>
                    </div>
                    <?php foreach ([
                        ['fa-id-badge',      'Full Name',      $user['name']  ?? 'Admin'],
                        ['fa-envelope',      'Email Address',  $user['email'] ?? '—'],
                        ['fa-phone',         'Contact Number', $user['phone'] ?? 'Not set'],
                        ['fa-shield-halved', 'Security Level', 'Level 1 (Full Access)'],
                        ['fa-calendar',      'Member Since',   $memberSince],
                    ] as $f): ?>
                        <div class="info-row">
                            <div class="info-icon"><i class="fa-solid <?= $f[0] ?>" style="font-size:.85rem;color:#94a3b8"></i></div>
                            <div style="min-width:0">
                                <div class="info-label"><?= $f[1] ?></div>
                                <div class="info-value"><?= htmlspecialchars($f[2]) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Security -->
                <div class="card card-p">
                    <div class="card-head">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:38px;height:38px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center">
                                <i class="fa-solid fa-shield-halved" style="color:#d97706;font-size:.9rem"></i>
                            </div>
                            <div>
                                <div class="card-title">Security</div>
                                <p style="font-size:.7rem;color:#94a3b8;margin-top:2px">Password and account protection</p>
                            </div>
                        </div>
                    </div>
                    <div class="danger-row">
                        <div style="min-width:0">
                            <p style="font-size:.83rem;font-weight:700;color:#1e293b">Password</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Update regularly to protect admin access</p>
                        </div>
                        <button onclick="openModal('editModal')" class="action-btn-sm">Change</button>
                    </div>
                    <div class="danger-row">
                        <div style="min-width:0">
                            <p style="font-size:.83rem;font-weight:700;color:#1e293b">Account Access</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Signed in with full administrator privileges</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;padding:5px 12px;background:#dcfce7;border-radius:999px;flex-shrink:0">
                            <i class="fa-solid fa-check" style="font-size:.65rem;color:#16a34a"></i>
                            <span style="font-size:.62rem;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:.05em">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Tip banner -->
                <div class="tip-banner">
                    <div class="tip-icon"><i class="fa-solid fa-lightbulb" style="font-size:1rem;color:white"></i></div>
                    <div style="position:relative;z-index:1">
                        <h5 style="font-size:.88rem;font-weight:700;color:white;line-height:1.3">Keep your info up to date</h5>
                        <p style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;line-height:1.5">Ensure your contact details are correct to receive critical system notifications.</p>
                    </div>
                </div>

                <!-- Danger zone -->
                <div class="card card-p">
                    <div class="card-head" style="margin-bottom:8px">
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:38px;height:38px;border-radius:12px;background:#fef2f2;display:flex;align-items:center;justify-content:center">
                                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:.85rem"></i>
                            </div>
                            <div>
                                <div class="card-title" style="color:#dc2626">Danger Zone</div>
                                <p style="font-size:.7rem;color:#94a3b8;margin-top:2px">Irreversible account actions</p>
                            </div>
                        </div>
                    </div>
                    <div class="danger-row" style="padding-top:14px;border-bottom:none;padding-bottom:0">
                        <div style="min-width:0">
                            <p style="font-size:.83rem;font-weight:700;color:#1e293b">Delete Account</p>
                            <p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Permanently remove your admin account and all associated data.</p>
                        </div>
                        <button class="danger-btn" onclick="openModal('deleteModal')">
                            <i class="fa-solid fa-trash" style="font-size:.7rem;margin-right:4px"></i> Delete
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
/* ── Modal helpers ── */
function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
    if (id === 'deleteModal') {
        const inp  = document.getElementById('deleteConfirmInput');
        const hint = document.getElementById('deleteInputHint');
        inp.value = '';
        inp.className = 'field-input';
        inp.style.borderColor = '#fecaca';
        hint.textContent = 'This action is irreversible. Case-sensitive.';
        hint.style.color = '#94a3b8';
        document.getElementById('deleteErrMsg').textContent = '';
        resetDeleteBtn();
        setTimeout(() => inp.focus(), 220);
    }
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal('editModal'); closeModal('deleteModal'); }
});

/* ── Password strength ── */
function checkPw(val) {
    const fill = document.getElementById('pwFill'), hint = document.getElementById('pwHint');
    if (!val) { fill.style.width='0%'; fill.style.background='#e2e8f0'; hint.textContent='Minimum 8 characters'; hint.style.color='#94a3b8'; return; }
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const labels = ['Too short','Weak','Fair','Good','Strong'];
    const colors = ['#e2e8f0','#f87171','#fbbf24','#34d399','#10b981'];
    fill.style.width = (score * 25) + '%';
    fill.style.background = colors[score];
    hint.textContent = labels[score];
    hint.style.color = colors[score];
}

/* ── Delete confirm input ── */
function checkDeleteInput(val) {
    const btn  = document.getElementById('deleteConfirmBtn');
    const hint = document.getElementById('deleteInputHint');
    const inp  = document.getElementById('deleteConfirmInput');
    if (val === 'DELETE') {
        btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer'; btn.style.boxShadow = '0 4px 14px rgba(220,38,38,.3)';
        inp.className = 'field-input input-success'; inp.style.borderColor = ''; inp.style.background = '';
        hint.textContent = '✓ Confirmed — you may now delete your account'; hint.style.color = '#16a34a';
    } else {
        resetDeleteBtn();
        if (val.length > 0) {
            inp.className = 'field-input input-error'; inp.style.borderColor = ''; inp.style.background = '';
            hint.textContent = 'Must be exactly "DELETE" in uppercase'; hint.style.color = '#dc2626';
        } else {
            inp.className = 'field-input'; inp.style.borderColor = '#fecaca'; inp.style.background = '';
            hint.textContent = 'This action is irreversible. Case-sensitive.'; hint.style.color = '#94a3b8';
        }
    }
}

function resetDeleteBtn() {
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled = true; btn.style.opacity = '.4'; btn.style.cursor = 'not-allowed'; btn.style.boxShadow = 'none';
}

/* ── Submit delete via plain form POST ── */
function submitDeleteAccount() {
    const val = document.getElementById('deleteConfirmInput').value.trim();
    if (val !== 'DELETE') { document.getElementById('deleteErrMsg').textContent = 'Please type DELETE exactly to confirm.'; return; }
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled = true; btn.style.opacity = '.7';
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.75rem"></i>&nbsp; Deleting…';
    document.getElementById('deleteErrMsg').textContent = '';
    document.getElementById('deleteAccountForm').submit();
}

/* ── Dark mode ── */
function toggleDark() {
    const isDark = document.body.classList.toggle('dark');
    const icon   = document.getElementById('dark-icon');
    icon.innerHTML = isDark ? '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>' : '<i class="fa-regular fa-sun" style="font-size:.85rem"></i>';
    localStorage.setItem('admin_theme', isDark ? 'dark' : 'light');
}

document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('admin_theme') === 'dark') {
        document.body.classList.add('dark');
        const icon = document.getElementById('dark-icon');
        if (icon) icon.innerHTML = '<i class="fa-regular fa-moon" style="font-size:.85rem"></i>';
    }
    document.documentElement.classList.remove('dark-pre');

    // Responsive grid
    function checkGrid() {
        const g = document.getElementById('profileGrid');
        if (g) g.style.gridTemplateColumns = window.innerWidth < 900 ? '1fr' : 'minmax(0,1fr) minmax(0,2fr)';
    }
    checkGrid();
    window.addEventListener('resize', checkGrid);
});
</script>
</body>
</html>