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
    <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        /* ── Page-specific styles only ── */

        /* Profile avatar */
        .profile-avatar { width: 80px; height: 80px; background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #818cf8 100%); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: white; box-shadow: 0 8px 24px rgba(55,48,163,.3); font-family: var(--mono); letter-spacing: -.04em; }
        .profile-status-dot { position: absolute; bottom: -4px; right: -4px; width: 22px; height: 22px; background: #10b981; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

        /* Info rows */
        .info-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid rgba(99,102,241,.07); }
        .info-row:last-child { border-bottom: none; }
        .info-icon { width: 34px; height: 34px; border-radius: 10px; background: #f8fafc; border: 1px solid rgba(99,102,241,.09); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .info-label { font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #94a3b8; }
        .info-value { font-size: .85rem; font-weight: 600; color: #0f172a; margin-top: 1px; }

        /* Mini stats */
        .stat-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-mini { background: #f8fafc; border: 1px solid rgba(99,102,241,.09); border-radius: var(--r-sm); padding: 12px 14px; }
        .stat-mini-lbl { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .14em; color: #94a3b8; margin-bottom: 4px; }
        .stat-mini-val { font-size: 1.25rem; font-weight: 800; color: #0f172a; font-family: var(--mono); line-height: 1; letter-spacing: -.03em; }
        .stat-mini-sub { font-size: .68rem; color: #94a3b8; margin-top: 3px; }

        /* Quick links */
        .quick-link { display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: var(--r-sm); border: 1px solid rgba(99,102,241,.09); background: white; text-decoration: none; color: #475569; font-size: .83rem; font-weight: 600; transition: all var(--ease); }
        .quick-link:hover { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo); }

        /* Buttons */
        .edit-btn { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; background: var(--indigo); color: white; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); box-shadow: 0 4px 12px rgba(55,48,163,.28); margin-top: 16px; }
        .edit-btn:hover { background: #312e81; transform: translateY(-1px); }
        .action-btn-sm { display: flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--indigo-light); border: 1px solid var(--indigo-border); border-radius: 9px; font-size: .72rem; font-weight: 700; color: var(--indigo); cursor: pointer; font-family: var(--font); transition: all var(--ease); }
        .action-btn-sm:hover { background: var(--indigo); color: white; }

        /* Danger zone */
        .danger-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid rgba(99,102,241,.07); gap: 12px; }
        .danger-row:last-child { border-bottom: none; padding-bottom: 0; }
        .danger-btn { font-size: .75rem; font-weight: 700; padding: 8px 14px; border-radius: 9px; border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; cursor: pointer; font-family: var(--font); transition: all var(--ease); white-space: nowrap; flex-shrink: 0; }
        .danger-btn:hover { background: #fee2e2; border-color: #f87171; }

        /* Tip banner */
        .tip-banner { background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%); border-radius: var(--r-lg); padding: 20px 22px; display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden; }
        .tip-icon { width: 42px; height: 42px; background: rgba(255,255,255,.15); border-radius: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 1; }

        /* Password strength */
        .pw-strength { height: 3px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
        .pw-fill { height: 100%; border-radius: 999px; transition: width .3s, background .3s; }

        /* Modal */
        .modal-back { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.6); backdrop-filter: blur(8px); z-index: 300; padding: 1.5rem; overflow-y: auto; align-items: center; justify-content: center; }
        .modal-back.show { display: flex; animation: fadeIn .18s ease; }
        .modal-card { background: white; border-radius: var(--r-xl); width: 100%; max-width: 480px; padding: 28px; max-height: calc(100dvh - 3rem); overflow-y: auto; margin: auto; animation: slideUp .22s ease; box-shadow: var(--shadow-lg); }
        .sheet-handle { display: none; width: 36px; height: 4px; background: #e2e8f0; border-radius: 999px; margin: 0 auto 16px; }
        @media(max-width:639px) { .modal-back { padding: 0; align-items: flex-end !important; } .modal-card { border-radius: var(--r-xl) var(--r-xl) 0 0; max-width: 100%; max-height: 92dvh; animation: sheetUp .25s cubic-bezier(.34,1.2,.64,1) both; } .sheet-handle { display: block; } }

        /* Delete input states */
        .input-success { border-color: #86efac !important; background: #f0fdf4 !important; }
        .input-error   { border-color: #f87171 !important; background: #fff5f5 !important; }

        /* Layouts */
        .card { background: var(--card); border-radius: var(--r-lg); border: 1px solid rgba(99,102,241,.08); box-shadow: var(--shadow-sm); }
        .card-p    { padding: 20px 22px; }
        .card-p-lg { padding: 22px 24px; }
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .card-title { font-size: .9rem; font-weight: 700; color: #0f172a; }
        .card-sub   { font-size: .7rem; color: #94a3b8; margin-top: 2px; }
        .section-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; }

        .icon-btn { width: 44px; height: 44px; background: white; border: 1px solid rgba(99,102,241,.12); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all var(--ease); box-shadow: var(--shadow-sm); }
        .icon-btn:hover { background: var(--indigo-light); border-color: var(--indigo-border); color: var(--indigo); }

        .flash-ok  { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding: 13px 18px; background: var(--indigo-light); border: 1px solid var(--indigo-border); color: var(--indigo); font-weight: 600; border-radius: var(--r-md); font-size: .875rem; animation: slideUp .4s ease both; }
        .flash-err { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding: 13px 18px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-weight: 600; border-radius: var(--r-md); font-size: .875rem; animation: slideUp .4s ease both; }

        @keyframes slideUp { from { opacity:0; transform:translateY(12px) } to { opacity:1; transform:none } }
        @keyframes sheetUp { from { opacity:0; transform:translateY(60px) } to { opacity:1; transform:none } }
        @keyframes fadeIn  { from { opacity:0 } to { opacity:1 } }
        .fade-up   { animation: slideUp .4s ease both; }
        .fade-up-1 { animation: slideUp .45s .05s ease both; }
        .fade-up-2 { animation: slideUp .45s .1s ease both; }
    </style>
</head>
<body>

<!-- ★ Shared layout: sidebar + mobile nav + dark-mode script -->
<?php include APPPATH . 'Views/partials/sk_layout.php'; ?>

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
            <button onclick="closeModal('editModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s">
                <i class="fa-solid fa-xmark" style="font-size:.8rem;color:#64748b"></i>
            </button>
        </div>
        <form action="<?= base_url('sk/profile/update') ?>" method="POST" style="display:flex;flex-direction:column;gap:16px">
            <?= csrf_field() ?>
            <div><label class="field-label">Full Name</label><input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="field-input" required></div>
            <div><label class="field-label">Email Address</label><input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="field-input" required></div>
            <div><label class="field-label">Contact Number</label><input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="field-input" placeholder="+63 912 345 6789"></div>
            <div>
                <label class="field-label">New Password</label>
                <input type="password" name="password" id="pwInput" class="field-input" placeholder="Leave blank to keep current" oninput="checkPw(this.value)">
                <div class="pw-strength"><div id="pwFill" class="pw-fill" style="width:0%;background:#e2e8f0"></div></div>
                <p class="field-hint" id="pwHint">Minimum 8 characters</p>
            </div>
            <div style="display:flex;gap:10px;padding-top:4px">
                <button type="button" onclick="closeModal('editModal')" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-size:.82rem;font-family:var(--font)">Cancel</button>
                <button type="submit" style="flex:2;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28)">Save Changes</button>
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
            <button onclick="closeModal('deleteModal')" style="width:32px;height:32px;border-radius:9px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
                <i class="fa-solid fa-xmark" style="font-size:.8rem;color:#64748b"></i>
            </button>
        </div>
        <div class="delete-warning-box" style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-sm);padding:14px 16px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start">
            <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;font-size:.9rem;margin-top:2px;flex-shrink:0"></i>
            <div>
                <p class="delete-warning-title" style="font-size:.8rem;font-weight:700;color:#b91c1c;margin-bottom:4px">Warning: Permanent Action</p>
                <p style="font-size:.74rem;color:#dc2626;line-height:1.55">All your reservations, borrowings and profile data will be <strong>permanently deleted</strong>. You will be immediately logged out and <strong>cannot recover</strong> your account.</p>
            </div>
        </div>
        <div style="margin-bottom:20px">
            <label class="field-label" style="color:#dc2626;margin-bottom:10px">
                Type <span class="delete-code-badge" style="font-family:var(--mono);background:#fef2f2;border:1px solid #fecaca;padding:2px 8px;border-radius:6px;font-size:.65rem;font-weight:800;color:#b91c1c;letter-spacing:.05em">DELETE</span> to confirm
            </label>
            <input type="text" id="deleteConfirmInput" placeholder="Type DELETE here…" class="field-input" style="border-color:#fecaca;margin-top:8px" oninput="checkDeleteInput(this.value)" autocomplete="off">
            <p id="deleteInputHint" style="font-size:.65rem;color:#94a3b8;margin-top:6px;font-weight:500">This action is irreversible. Case-sensitive.</p>
        </div>
        <div style="display:flex;gap:10px">
            <button type="button" onclick="closeModal('deleteModal')" class="delete-cancel-btn" style="flex:1;padding:12px;background:#f8fafc;border-radius:var(--r-sm);font-weight:700;color:#475569;border:1px solid #e2e8f0;cursor:pointer;font-size:.82rem;font-family:var(--font)">Cancel</button>
            <button type="button" id="deleteConfirmBtn" onclick="submitDeleteAccount()" disabled style="flex:2;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:not-allowed;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;opacity:.4">
                <i class="fa-solid fa-trash" style="font-size:.75rem"></i>
                <span id="deleteSubmitTxt">Delete My Account</span>
            </button>
        </div>
        <p id="deleteErrMsg" style="font-size:.72rem;color:#dc2626;font-weight:600;margin-top:10px;min-height:18px;text-align:center"></p>
        <form id="deleteAccountForm" action="<?= base_url('sk/profile/delete') ?>" method="POST" style="display:none"><?= csrf_field() ?></form>
    </div>
</div>

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
            <!-- ★ layoutToggleDark() from layout.php -->
            <div class="icon-btn" onclick="layoutToggleDark()" id="darkBtn" title="Toggle dark mode">
                <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
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

        <!-- LEFT column -->
        <div style="display:flex;flex-direction:column;gap:14px">
            <div class="card card-p" style="text-align:center">
                <div style="position:relative;display:inline-block;margin:0 auto 18px">
                    <div class="profile-avatar"><?= $avatarLetter ?></div>
                    <div class="profile-status-dot"><i class="fa-solid fa-check" style="font-size:.55rem;color:white"></i></div>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:#0f172a;letter-spacing:-.02em"><?= htmlspecialchars($user['name'] ?? 'SK Officer') ?></h3>
                <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                <?php if (!empty($user['phone'])): ?><p style="font-size:.72rem;color:#94a3b8;margin-top:2px;font-family:var(--mono)"><?= htmlspecialchars($user['phone']) ?></p><?php endif; ?>
                <div style="margin-top:12px">
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;padding:4px 12px;border-radius:999px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border)">
                        <i class="fa-solid fa-shield-halved" style="font-size:.55rem"></i> SK Officer
                    </span>
                </div>
                <button class="edit-btn" onclick="openModal('editModal')"><i class="fa-solid fa-pen-to-square" style="font-size:.8rem"></i> Edit Profile</button>
            </div>

            <div class="card card-p">
                <div class="section-lbl">Account Activity</div>
                <div class="stat-mini-grid">
                    <div class="stat-mini"><div class="stat-mini-lbl">Member Since</div><div class="stat-mini-val" style="font-size:.95rem;font-family:var(--font)"><?= $memberYear ?></div><div class="stat-mini-sub">Year joined</div></div>
                    <div class="stat-mini"><div class="stat-mini-lbl">Status</div><div style="display:flex;align-items:center;gap:5px;margin-top:4px"><div style="width:8px;height:8px;background:#10b981;border-radius:50%;box-shadow:0 0 0 3px rgba(16,185,129,.15);flex-shrink:0"></div><div class="stat-mini-val" style="font-size:.85rem;font-family:var(--font)">Active</div></div><div class="stat-mini-sub">Verified</div></div>
                </div>
            </div>

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
                            <div style="width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $bg ?>"><i class="fa-solid <?= $icon ?>" style="font-size:.8rem;color:<?= $fg ?>"></i></div>
                            <?= $label ?>
                            <i class="fa-solid fa-chevron-right" style="font-size:.65rem;color:#cbd5e1;margin-left:auto"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT column -->
        <div style="display:flex;flex-direction:column;gap:14px">
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="card-icon" style="background:var(--indigo-light)"><i class="fa-solid fa-id-badge" style="color:var(--indigo);font-size:.9rem"></i></div>
                        <div><div class="card-title">Personal Information</div><div class="card-sub">Your account details</div></div>
                    </div>
                    <button onclick="openModal('editModal')" class="action-btn-sm"><i class="fa-solid fa-pen-to-square" style="font-size:.7rem"></i> Edit</button>
                </div>
                <?php foreach ([
                    ['fa-user',     'Full Name',      $user['name']  ?? 'Not set'],
                    ['fa-envelope', 'Email Address',  $user['email'] ?? 'Not set'],
                    ['fa-phone',    'Contact Number', $user['phone'] ?? 'Not set'],
                    ['fa-star',     'Role',           'Sangguniang Kabataan Officer'],
                    ['fa-calendar', 'Member Since',   $memberSince],
                ] as $f): ?>
                    <div class="info-row">
                        <div class="info-icon"><i class="fa-solid <?= $f[0] ?>" style="font-size:.8rem;color:#94a3b8"></i></div>
                        <div style="min-width:0"><div class="info-label"><?= $f[1] ?></div><div class="info-value"><?= htmlspecialchars($f[2]) ?></div></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="card-icon" style="background:#fef3c7"><i class="fa-solid fa-shield-halved" style="color:#d97706;font-size:.9rem"></i></div>
                        <div><div class="card-title">Security</div><div class="card-sub">Password and account protection</div></div>
                    </div>
                </div>
                <div class="danger-row">
                    <div style="min-width:0"><p style="font-size:.83rem;font-weight:600;color:#0f172a">Password</p><p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Update regularly to keep your account safe</p></div>
                    <button onclick="openModal('editModal')" class="action-btn-sm">Change</button>
                </div>
                <div class="danger-row">
                    <div style="min-width:0"><p style="font-size:.83rem;font-weight:600;color:#0f172a">Account Access</p><p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Signed in as an SK Officer</p></div>
                    <div style="display:flex;align-items:center;gap:5px;padding:5px 12px;background:#dcfce7;border-radius:999px;flex-shrink:0"><i class="fa-solid fa-check" style="font-size:.65rem;color:#16a34a"></i><span style="font-size:.62rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.05em">Active</span></div>
                </div>
            </div>

            <div class="tip-banner">
                <div class="tip-icon"><i class="fa-solid fa-lightbulb" style="font-size:1rem;color:white"></i></div>
                <div style="position:relative;z-index:1">
                    <h5 style="font-size:.88rem;font-weight:700;color:white;line-height:1.3">Keep your info up to date</h5>
                    <p style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;line-height:1.5">Ensure your contact details are correct so notifications reach you properly.</p>
                </div>
            </div>

            <div class="card card-p-lg">
                <div class="card-head" style="margin-bottom:8px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="card-icon" style="background:#fef2f2"><i class="fa-solid fa-trash" style="color:#dc2626;font-size:.85rem"></i></div>
                        <div><div class="card-title" style="color:#dc2626">Danger Zone</div><div class="card-sub">Irreversible account actions</div></div>
                    </div>
                </div>
                <div class="danger-row" style="padding-top:12px;border-bottom:none;padding-bottom:0">
                    <div style="min-width:0"><p style="font-size:.83rem;font-weight:600;color:#0f172a">Delete Account</p><p style="font-size:.72rem;color:#94a3b8;margin-top:2px">Permanently remove your account and all data. Cannot be undone.</p></div>
                    <button class="danger-btn" onclick="openModal('deleteModal')"><i class="fa-solid fa-trash" style="font-size:.7rem;margin-right:4px"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
/* ── Modal helpers ── */
function openModal(id)  { document.getElementById(id).classList.add('show');    document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal('editModal'); closeModal('deleteModal'); } });

/* ── Password strength ── */
function checkPw(val) {
    const fill = document.getElementById('pwFill'), hint = document.getElementById('pwHint');
    if (!val) { fill.style.width = '0%'; fill.style.background = '#e2e8f0'; hint.textContent = 'Minimum 8 characters'; hint.style.color = '#94a3b8'; return; }
    let score = 0;
    if (val.length >= 8) score++; if (/[A-Z]/.test(val)) score++; if (/[0-9]/.test(val)) score++; if (/[^A-Za-z0-9]/.test(val)) score++;
    const labels = ['Too short','Weak','Fair','Good','Strong'], colors = ['#e2e8f0','#f87171','#fbbf24','#34d399','#10b981'];
    fill.style.width = (score * 25) + '%'; fill.style.background = colors[score]; hint.textContent = labels[score]; hint.style.color = colors[score];
}

/* ── Delete confirm input ── */
function checkDeleteInput(val) {
    const btn = document.getElementById('deleteConfirmBtn'), hint = document.getElementById('deleteInputHint'), inp = document.getElementById('deleteConfirmInput');
    if (val === 'DELETE') {
        btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer'; btn.style.boxShadow = '0 4px 14px rgba(220,38,38,.3)';
        inp.className = 'field-input input-success'; hint.textContent = '✓ Confirmed — you may now delete your account'; hint.style.color = '#16a34a';
    } else {
        btn.disabled = true; btn.style.opacity = '.4'; btn.style.cursor = 'not-allowed'; btn.style.boxShadow = 'none';
        inp.className = 'field-input' + (val.length > 0 ? ' input-error' : ''); inp.style.borderColor = val.length > 0 ? '' : '#fecaca';
        hint.textContent = val.length > 0 ? 'Must be exactly "DELETE" in uppercase' : 'This action is irreversible. Case-sensitive.';
        hint.style.color = val.length > 0 ? '#dc2626' : '#94a3b8';
    }
}

function submitDeleteAccount() {
    if (document.getElementById('deleteConfirmInput').value.trim() !== 'DELETE') { document.getElementById('deleteErrMsg').textContent = 'Please type DELETE exactly to confirm.'; return; }
    const btn = document.getElementById('deleteConfirmBtn'); btn.disabled = true; btn.style.opacity = '.7'; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:.75rem"></i>&nbsp; Deleting…';
    document.getElementById('deleteErrMsg').textContent = '';
    document.getElementById('deleteAccountForm').submit();
}

/* ── Responsive grid ── */
document.addEventListener('DOMContentLoaded', () => {
    function checkGrid() { const g = document.getElementById('profileGrid'); if (g) g.style.gridTemplateColumns = window.innerWidth < 900 ? '1fr' : 'minmax(0,1fr) minmax(0,1.6fr)'; }
    checkGrid(); window.addEventListener('resize', checkGrid);
});
</script>
</body>
</html>