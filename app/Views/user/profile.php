<?php
date_default_timezone_set('Asia/Manila');
$page         = 'profile';
$user_name    = $user['name'] ?? 'Resident';
$avatarLetter = strtoupper(mb_substr(trim($user_name, " \t\n\r\0\x0B"), 0, 1)) ?: 'R';
$memberSince  = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : '—';
$memberYear   = isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>My Profile | <?= esc($user['name'] ?? 'User') ?></title>
    <link rel="manifest" href="/manifest.json">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script>
    (function () {
        try { if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark-pre'); } catch(e) {}
    })();
    </script>

    <style>
        /* ── Layout shell ── */
        body { display: flex; height: 100vh; height: 100dvh; overflow: hidden; }
        html.dark-pre body { background: #060e1e; }

        /* ── Profile avatar ── */
        .profile-avatar-wrap { position: relative; display: inline-block; margin-bottom: 18px; }
        .profile-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--indigo) 0%, #4338ca 60%, #6366f1 100%);
            border-radius: 24px; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 800; color: white;
            box-shadow: 0 8px 24px rgba(55,48,163,.3);
            font-family: var(--mono); letter-spacing: -.04em;
        }
        .profile-status-dot {
            position: absolute; bottom: -4px; right: -4px;
            width: 22px; height: 22px; background: #10b981;
            border: 3px solid var(--card); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Info rows ── */
        .info-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border-subtle); }
        .info-row:last-child { border-bottom: none; }
        .info-icon { width: 34px; height: 34px; border-radius: 10px; background: var(--input-bg); border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .info-label { font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--text-sub); }
        .info-value { font-size: .85rem; font-weight: 600; color: var(--text); margin-top: 1px; }

        /* ── Stat mini grid ── */
        .stat-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-mini { background: var(--input-bg); border: 1px solid var(--border-subtle); border-radius: var(--r-sm); padding: 12px 14px; }
        .stat-mini-lbl { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .14em; color: var(--text-sub); margin-bottom: 4px; }
        .stat-mini-val { font-size: 1.25rem; font-weight: 800; color: var(--text); font-family: var(--mono); line-height: 1; letter-spacing: -.03em; }
        .stat-mini-sub { font-size: .68rem; color: var(--text-sub); margin-top: 3px; }

        /* ── Edit button ── */
        .edit-btn { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; background: var(--indigo); color: white; border-radius: var(--r-sm); font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: var(--font); transition: all var(--ease); box-shadow: 0 4px 12px rgba(55,48,163,.28); margin-top: 16px; }
        .edit-btn:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(55,48,163,.35); }

        .action-btn-sm { display: flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--indigo-light); border: 1px solid var(--indigo-border); border-radius: 9px; font-size: .72rem; font-weight: 700; color: var(--indigo); cursor: pointer; font-family: var(--font); transition: all var(--ease); }
        .action-btn-sm:hover { background: var(--indigo); color: white; }

        /* ── Tip banner ── */
        .tip-banner { background: linear-gradient(135deg,var(--indigo) 0%,#4338ca 60%,#6366f1 100%); border-radius: var(--r-lg); padding: 20px 22px; display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden; }
        .tip-banner::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat; opacity: .4; }
        .tip-icon { width: 42px; height: 42px; background: rgba(255,255,255,.15); border-radius: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; z-index: 1; }

        /* ── Password strength ── */
        .pw-strength { height: 3px; border-radius: 999px; background: var(--border); overflow: hidden; margin-top: 6px; }
        .pw-fill { height: 100%; border-radius: 999px; transition: width .3s, background .3s; }

        /* ── Danger zone ── */
        .danger-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid var(--border-subtle); gap: 12px; }
        .danger-row:last-child { border-bottom: none; padding-bottom: 0; }
        .danger-btn { font-size: .75rem; font-weight: 700; padding: 8px 14px; border-radius: 9px; border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; cursor: pointer; font-family: var(--font); transition: all var(--ease); white-space: nowrap; flex-shrink: 0; }
        .danger-btn:hover { background: #fee2e2; border-color: #f87171; }

        /* ── Quick links ── */
        .quick-link { display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: var(--r-sm); border: 1px solid var(--border); background: var(--card); text-decoration: none; color: var(--text-muted); font-size: .83rem; font-weight: 600; transition: all var(--ease); }
        .quick-link:hover { border-color: var(--indigo); background: var(--indigo-light); color: var(--indigo); }

        /* ── Field inputs (modal) ── */
        .field-label { display: block; font-size: .62rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--text-sub); margin-bottom: 6px; }
        .field-input { width: 100%; background: var(--input-bg); border: 1px solid rgba(99,102,241,.15); border-radius: var(--r-sm); padding: 11px 14px; font-family: var(--font); font-size: .87rem; font-weight: 600; color: var(--text); transition: all .2s; outline: none; }
        .field-input:focus { border-color: #818cf8; background: var(--card); box-shadow: 0 0 0 3px rgba(99,102,241,.08); }
        .field-hint { font-size: .65rem; color: var(--text-sub); margin-top: 4px; }

        /* ── Delete input states ── */
        .input-success { border-color: #86efac !important; background: #f0fdf4 !important; }
        .input-error   { border-color: #f87171 !important; background: #fff5f5 !important; }
        body.dark .input-success { background: #052e16 !important; }
        body.dark .input-error   { background: #2d0a0a !important; }

        /* ── Dark delete modal overrides ── */
        body.dark #deleteConfirmInput { background: var(--input-bg); border-color: #7f1d1d; color: var(--text); }
        body.dark .delete-warning-box { background: rgba(127,29,29,.3) !important; border-color: #7f1d1d !important; }
        body.dark .delete-warning-box p { color: #fca5a5 !important; }
        body.dark .delete-warning-title { color: #fca5a5 !important; }
        body.dark .delete-code-badge { background: rgba(127,29,29,.4) !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }
        body.dark .delete-cancel-btn { background: var(--input-bg) !important; border-color: var(--border) !important; color: #a5b4fc !important; }

        /* ── Card / section sub-elements ── */
        .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .card-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .card-title { font-size: .9rem; font-weight: 700; color: var(--text); letter-spacing: -.01em; }
        .card-sub   { font-size: .7rem; color: var(--text-sub); margin-top: 2px; }
        .section-lbl { font-size: .62rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: var(--text-sub); margin-bottom: 14px; }

        /* ── Profile grid responsive ── */
        #profileGrid { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1.6fr); gap: 16px; }
        @media(max-width:900px) { #profileGrid { grid-template-columns: 1fr; } }
        @media(max-width:639px) { .main-area { padding: 14px 12px 0; } }
    </style>
</head>
<body>

<?php
$page = 'profile';
include(APPPATH . 'Views/partials/layout.php');
?>

<!-- ══════════════════════════════════════════
     EDIT PROFILE MODAL
══════════════════════════════════════════ -->
<div id="editModal" class="modal-back" onclick="if(event.target===this)closeModal('editModal')">
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;gap:12px">
            <div>
                <h3 style="font-size:1rem;font-weight:800;letter-spacing:-.02em;">Update Profile</h3>
                <p style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">Changes are saved immediately.</p>
            </div>
            <button onclick="closeModal('editModal')" style="width:36px;height:36px;border-radius:9px;background:var(--input-bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="<?= base_url('profile/update') ?>" method="POST" style="display:flex;flex-direction:column;gap:16px">
            <?= csrf_field() ?>
            <div><label class="field-label">Full Name</label><input type="text" name="name" value="<?= esc($user['name'] ?? '') ?>" class="field-input" required></div>
            <div><label class="field-label">Email Address</label><input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="field-input" required></div>
            <div><label class="field-label">Contact Number</label><input type="text" name="phone" value="<?= esc($user['phone'] ?? '') ?>" class="field-input" placeholder="+63 912 345 6789"></div>
            <div>
                <label class="field-label">New Password</label>
                <input type="password" name="password" id="pwInput" class="field-input" placeholder="Leave blank to keep current" oninput="checkPw(this.value)">
                <div class="pw-strength"><div id="pwFill" class="pw-fill" style="width:0%;background:var(--border)"></div></div>
                <p class="field-hint" id="pwHint">Minimum 8 characters</p>
            </div>
            <div style="display:flex;gap:10px;padding-top:4px">
                <button type="button" onclick="closeModal('editModal')" style="flex:1;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:700;color:var(--text-muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;font-family:var(--font);">Cancel</button>
                <button type="submit" style="flex:2;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:pointer;font-size:.82rem;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);">Save Changes</button>
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
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;gap:12px">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:46px;height:46px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid #fecaca">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </div>
                <div>
                    <h3 style="font-size:1rem;font-weight:800;letter-spacing:-.02em;">Delete Account?</h3>
                    <p style="font-size:.7rem;color:var(--text-sub);margin-top:2px;font-weight:500;">This cannot be undone</p>
                </div>
            </div>
            <button onclick="closeModal('deleteModal')" style="width:32px;height:32px;border-radius:9px;background:var(--input-bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="delete-warning-box" style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r-sm);padding:14px 16px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.8" style="flex-shrink:0;margin-top:2px"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <p class="delete-warning-title" style="font-size:.8rem;font-weight:700;color:#b91c1c;margin-bottom:4px;">Warning: Permanent Action</p>
                <p style="font-size:.74rem;color:#dc2626;line-height:1.55;">All your reservations, library history, and account data will be <strong>permanently deleted</strong>. You will be immediately logged out and <strong>cannot recover</strong> your account.</p>
            </div>
        </div>
        <div style="margin-bottom:20px">
            <label class="field-label" style="color:#dc2626;margin-bottom:10px;">
                Type <span class="delete-code-badge" style="font-family:var(--mono);background:#fef2f2;border:1px solid #fecaca;padding:2px 8px;border-radius:6px;font-size:.65rem;font-weight:800;color:#b91c1c;letter-spacing:.05em;">DELETE</span> to confirm
            </label>
            <input type="text" id="deleteConfirmInput" placeholder="Type DELETE here…" class="field-input" style="border-color:#fecaca;margin-top:8px" oninput="checkDeleteInput(this.value)" autocomplete="off">
            <p id="deleteInputHint" style="font-size:.65rem;color:var(--text-sub);margin-top:6px;font-weight:500;">This action is irreversible. Case-sensitive.</p>
        </div>
        <div style="display:flex;gap:10px">
            <button type="button" onclick="closeModal('deleteModal')" class="delete-cancel-btn" style="flex:1;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:700;color:var(--text-muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;font-family:var(--font);">Cancel</button>
            <button type="button" id="deleteConfirmBtn" onclick="submitDeleteAccount()" disabled style="flex:2;padding:12px;background:#dc2626;color:white;border-radius:var(--r-sm);font-weight:700;border:none;cursor:not-allowed;font-size:.82rem;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;opacity:.4;transition:all .2s;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                <span id="deleteSubmitTxt">Delete My Account</span>
            </button>
        </div>
        <p id="deleteErrMsg" style="font-size:.72rem;color:#dc2626;font-weight:600;margin-top:10px;min-height:18px;text-align:center"></p>
        <form id="deleteAccountForm" action="<?= base_url('profile/delete') ?>" method="POST" style="display:none"><?= csrf_field() ?></form>
    </div>
</div>

<!-- ══════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════ -->
<main class="main-area">

    <!-- Topbar -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow">Account</div>
            <div class="greeting-name">My Profile</div>
            <div class="greeting-sub">Manage your account settings and security.</div>
        </div>
        <div class="topbar-right">
            <?= layout_dark_toggle() ?>
            <span style="display:inline-flex;align-items:center;gap:5px;font-size:.6rem;font-weight:700;padding:5px 12px;border-radius:999px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border);">Resident</span>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-ok fade-up">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-err fade-up">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div id="profileGrid" class="fade-up-1">

        <!-- LEFT COLUMN -->
        <div style="display:flex;flex-direction:column;gap:14px;">

            <!-- Avatar card -->
            <div class="card card-p" style="text-align:center;">
                <div class="profile-avatar-wrap" style="margin:0 auto 18px;">
                    <div class="profile-avatar"><?= $avatarLetter ?></div>
                    <div class="profile-status-dot">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:var(--text);letter-spacing:-.02em;"><?= esc($user['name'] ?? 'Resident') ?></h3>
                <p style="font-size:.78rem;color:var(--text-sub);font-weight:500;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($user['email'] ?? '') ?></p>
                <?php if (!empty($user['phone'])): ?>
                    <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;font-family:var(--mono);"><?= esc($user['phone']) ?></p>
                <?php endif; ?>
                <div style="margin-top:12px;">
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:.62rem;font-weight:700;padding:4px 12px;border-radius:999px;background:var(--indigo-light);color:var(--indigo);border:1px solid var(--indigo-border);">Resident User</span>
                </div>
                <button class="edit-btn" onclick="openModal('editModal')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Profile
                </button>
            </div>

            <!-- Activity stats -->
            <div class="card card-p">
                <div class="section-lbl">Account Activity</div>
                <div class="stat-mini-grid">
                    <div class="stat-mini">
                        <div class="stat-mini-lbl">Member Since</div>
                        <div class="stat-mini-val" style="font-size:.95rem;letter-spacing:-.01em;"><?= $memberYear ?></div>
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

            <!-- Quick access -->
            <div class="card card-p">
                <div class="section-lbl">Quick Access</div>
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <?php foreach ([
                        ['/reservation',      '#eef2ff', 'var(--indigo)',  'New Reservation',  'plus'],
                        ['/reservation-list', '#ede9fe', '#7c3aed',       'My Reservations',  'calendar'],
                        ['/books',            '#fef3c7', '#d97706',       'Browse Library',   'book-open'],
                        ['/dashboard',        '#f3e8ff', '#9333ea',       'Dashboard',        'house'],
                    ] as [$url, $bg, $fg, $label, $ico]):
                        $svgMap = [
                            'plus'      => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
                            'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                            'book-open' => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
                            'house'     => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/>',
                        ];
                    ?>
                        <a href="<?= base_url($url) ?>" class="quick-link">
                            <div style="width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:<?= $bg ?>;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="<?= $fg ?>" stroke-width="1.8"><?= $svgMap[$ico] ?></svg>
                            </div>
                            <?= $label ?>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="margin-left:auto;color:var(--text-faint);"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div style="display:flex;flex-direction:column;gap:14px;">

            <!-- Personal info -->
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:var(--indigo-light);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3l-4 4-4-4"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Personal Information</div>
                            <div class="card-sub">Your account details</div>
                        </div>
                    </div>
                    <button onclick="openModal('editModal')" class="action-btn-sm">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </button>
                </div>
                <?php foreach ([
                    ['user',     'Full Name',      $user['name']  ?? 'Not set'],
                    ['mail',     'Email Address',  $user['email'] ?? 'Not set'],
                    ['phone',    'Contact Number', $user['phone'] ?? 'Not set'],
                    ['calendar', 'Member Since',   $memberSince],
                ] as [$ico, $label, $val]):
                    $icoMap = [
                        'user'     => '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"/>',
                        'mail'     => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
                        'phone'    => '<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.01 1.2 2 2 0 012 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91A16 16 0 0016 17.91l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>',
                        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                    ];
                ?>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-sub)" stroke-width="1.8"><?= $icoMap[$ico] ?></svg>
                        </div>
                        <div style="min-width:0;">
                            <div class="info-label"><?= $label ?></div>
                            <div class="info-value"><?= esc($val) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Security -->
            <div class="card card-p-lg">
                <div class="card-head">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#fef3c7;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Security</div>
                            <div class="card-sub">Password and account protection</div>
                        </div>
                    </div>
                </div>
                <div class="danger-row">
                    <div style="min-width:0;">
                        <p style="font-size:.83rem;font-weight:600;color:var(--text);">Password</p>
                        <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">Last changed — update regularly for safety</p>
                    </div>
                    <button onclick="openModal('editModal')" class="action-btn-sm">Change</button>
                </div>
                <div class="danger-row" style="border-bottom:none;padding-bottom:0;">
                    <div style="min-width:0;">
                        <p style="font-size:.83rem;font-weight:600;color:var(--text);">Account Access</p>
                        <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">You are currently signed in as a Resident</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;padding:6px 12px;background:#dcfce7;border-radius:999px;flex-shrink:0;">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span style="font-size:.65rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.05em;">Active</span>
                    </div>
                </div>
            </div>

            <!-- Tip banner -->
            <div class="tip-banner">
                <div class="tip-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div style="position:relative;z-index:1;">
                    <h5 style="font-size:.88rem;font-weight:700;color:white;line-height:1.3;">Keep your info up to date</h5>
                    <p style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;line-height:1.5;">Ensure your contact details are correct so reservations and notifications reach you properly.</p>
                </div>
            </div>

            <!-- Danger zone -->
            <div class="card card-p-lg">
                <div class="card-head" style="margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="card-icon" style="background:#fef2f2;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        </div>
                        <div>
                            <div class="card-title" style="color:#dc2626;">Danger Zone</div>
                            <div class="card-sub">Irreversible account actions</div>
                        </div>
                    </div>
                </div>
                <div class="danger-row" style="padding-top:12px;border-bottom:none;padding-bottom:0;">
                    <div style="min-width:0;">
                        <p style="font-size:.83rem;font-weight:600;color:var(--text);">Delete Account</p>
                        <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">Permanently remove your account and all data</p>
                    </div>
                    <button class="danger-btn" onclick="openModal('deleteModal')">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="margin-right:4px;vertical-align:middle;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
    if (id === 'deleteModal') {
        const inp = document.getElementById('deleteConfirmInput');
        inp.value = '';
        inp.className = 'field-input';
        inp.style.borderColor = '#fecaca';
        document.getElementById('deleteInputHint').textContent = 'This action is irreversible. Case-sensitive.';
        document.getElementById('deleteInputHint').style.color = '';
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

function checkPw(val) {
    const fill = document.getElementById('pwFill'), hint = document.getElementById('pwHint');
    if (!val) { fill.style.width='0%'; fill.style.background='var(--border)'; hint.textContent='Minimum 8 characters'; hint.style.color=''; return; }
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const labels = ['Too short','Weak','Fair','Good','Strong'];
    const colors = ['var(--border)','#f87171','#fbbf24','#34d399','#10b981'];
    fill.style.width = (score * 25) + '%';
    fill.style.background = colors[score];
    hint.textContent = labels[score];
    hint.style.color = colors[score];
}

function checkDeleteInput(val) {
    const btn = document.getElementById('deleteConfirmBtn');
    const hint = document.getElementById('deleteInputHint');
    const inp  = document.getElementById('deleteConfirmInput');
    if (val === 'DELETE') {
        btn.disabled = false; btn.style.opacity='1'; btn.style.cursor='pointer'; btn.style.boxShadow='0 4px 14px rgba(220,38,38,.3)';
        inp.className='field-input input-success'; inp.style.borderColor=''; inp.style.background='';
        hint.textContent='✓ Confirmed — you may now delete your account'; hint.style.color='#16a34a';
    } else {
        resetDeleteBtn();
        if (val.length > 0) { inp.className='field-input input-error'; inp.style.borderColor=''; inp.style.background=''; hint.textContent='Must be exactly "DELETE" in uppercase'; hint.style.color='#dc2626'; }
        else { inp.className='field-input'; inp.style.borderColor='#fecaca'; inp.style.background=''; hint.textContent='This action is irreversible. Case-sensitive.'; hint.style.color=''; }
    }
}
function resetDeleteBtn() {
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled=true; btn.style.opacity='.4'; btn.style.cursor='not-allowed'; btn.style.boxShadow='none';
}
function submitDeleteAccount() {
    const val = document.getElementById('deleteConfirmInput').value.trim();
    if (val !== 'DELETE') { document.getElementById('deleteErrMsg').textContent = 'Please type DELETE exactly to confirm.'; return; }
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled=true; btn.style.opacity='.7';
    btn.innerHTML='<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" opacity=".25"/><path d="M21 12a9 9 0 00-9-9"/></svg>&nbsp; Deleting…';
    document.getElementById('deleteErrMsg').textContent = '';
    document.getElementById('deleteAccountForm').submit();
}

document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.remove('dark-pre');
});
</script>
</body>
</html>