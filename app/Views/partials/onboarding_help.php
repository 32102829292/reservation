<?php
$currentRole = $role ?? session()->get('role') ?? 'user';
$userName    = session()->get('name') ?? 'there';
$accent      = match($currentRole) { 'chairman' => '#2563eb', 'sk' => '#16a34a', default => '#16a34a' };
$roleLabel   = match($currentRole) { 'chairman' => 'Chairman', 'sk' => 'SK Officer', default => 'Resident' };
$roleIcon    = match($currentRole) { 'chairman' => 'fa-crown', 'sk' => 'fa-user-shield', default => 'fa-user' };
?>
<style>
/* ── Onboarding ── */
.ob-wrap{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;pointer-events:none;transition:opacity .25s}
.ob-wrap.open{opacity:1;pointer-events:all}
.ob-bg{position:absolute;inset:0;background:rgba(2,6,23,.88);backdrop-filter:blur(8px)}
.ob-card{position:relative;z-index:1;width:100%;max-width:380px;background:#09090b;border:1px solid rgba(255,255,255,.08);border-radius:20px;overflow:hidden;box-shadow:0 30px 60px -10px rgba(0,0,0,.8);transform:translateY(16px) scale(.97);transition:transform .3s cubic-bezier(.34,1.2,.64,1)}
.ob-wrap.open .ob-card{transform:none}
.ob-glow{display:none}
.ob-top{height:2px;background:linear-gradient(90deg,transparent,var(--a,#16a34a),transparent)}
.ob-body{padding:1.5rem 1.5rem 1rem;position:relative}
/* Step label row */
.ob-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.ob-lbl{font-size:10px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--a,#16a34a)}
.ob-dots{display:flex;gap:5px}
.ob-dot{height:3px;width:18px;border-radius:999px;background:rgba(255,255,255,.1);transition:all .4s cubic-bezier(.34,1.3,.64,1)}
.ob-dot.on{background:var(--a,#16a34a);width:28px;box-shadow:0 0 10px color-mix(in srgb,var(--a,#16a34a) 60%,transparent)}
.ob-dot.done{background:color-mix(in srgb,var(--a,#16a34a) 35%,transparent);width:22px}
/* Icon */
.ob-ico{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:1.1rem;background:color-mix(in srgb,var(--a,#16a34a) 12%,#18181b);border:1px solid color-mix(in srgb,var(--a,#16a34a) 25%,transparent);color:var(--a,#16a34a)}
/* Text */
.ob-title{font-size:1.25rem;font-weight:900;color:#fafafa;letter-spacing:-.4px;line-height:1.2;margin:0 0 .5rem}
.ob-title em{font-style:normal;color:var(--a,#16a34a)}
.ob-desc{font-size:.8rem;line-height:1.6;color:#71717a;margin:0;font-weight:400}
.ob-desc strong{color:#a1a1aa;font-weight:600}
/* Chips */
.ob-chips{display:flex;flex-direction:column;gap:6px;margin-top:1rem}
.ob-chip{display:flex;align-items:center;gap:10px;padding:.55rem .875rem;border-radius:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);transition:all .2s}
.ob-chip:hover{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1)}
.ob-cico{width:26px;height:26px;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.72rem;background:color-mix(in srgb,var(--a,#16a34a) 14%,#18181b);color:var(--a,#16a34a)}
.ob-ctxt{font-size:.75rem;font-weight:500;color:#71717a}
/* Panels */
.ob-panel{display:none}
.ob-panel.on{display:block;animation:obslide .35s cubic-bezier(.34,1.1,.64,1) both}
@keyframes obslide{from{opacity:0;transform:translateX(10px)}to{opacity:1;transform:none}}
/* Footer */
.ob-foot{display:flex;align-items:center;gap:10px;padding:1rem 1.5rem 1.5rem;border-top:1px solid rgba(255,255,255,.05)}
.ob-skip{font-size:.78rem;font-weight:600;color:#3f3f46;background:none;border:none;cursor:pointer;font-family:inherit;padding:.5rem .75rem;border-radius:10px;transition:all .15s;white-space:nowrap}
.ob-skip:hover{color:#71717a;background:rgba(255,255,255,.05)}
.ob-btn{flex:1;padding:.75rem 1.25rem;border:none;border-radius:12px;font-size:.83rem;font-weight:800;color:#09090b;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:9px;transition:all .25s cubic-bezier(.34,1.2,.64,1);background:var(--a,#16a34a);box-shadow:0 8px 24px -6px color-mix(in srgb,var(--a,#16a34a) 55%,transparent);position:relative;overflow:hidden}
.ob-btn::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.18),transparent)}
.ob-btn:hover{transform:translateY(-2px);box-shadow:0 14px 32px -6px color-mix(in srgb,var(--a,#16a34a) 60%,transparent)}
/* Progress */
.ob-prog-wrap{height:2px;background:rgba(255,255,255,.05)}
.ob-prog-fill{height:100%;background:var(--a,#16a34a);border-radius:0 999px 999px 0;transition:width .5s cubic-bezier(.34,1.2,.64,1);box-shadow:0 0 10px color-mix(in srgb,var(--a,#16a34a) 50%,transparent)}

/* ── Help FAB ── */
.help-fab{position:fixed;bottom:90px;right:20px;z-index:8000;width:52px;height:52px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:white;box-shadow:0 8px 28px -4px rgba(0,0,0,.3);transition:all .3s cubic-bezier(.34,1.4,.64,1);background:var(--a,#16a34a);font-family:inherit}
.help-fab:hover{transform:scale(1.14) translateY(-3px);box-shadow:0 16px 40px -6px rgba(0,0,0,.35)}
.help-tip-lbl{position:absolute;right:62px;top:50%;transform:translateY(-50%);background:#18181b;color:white;font-size:.7rem;font-weight:700;padding:.35rem .75rem;border-radius:10px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity .2s;border:1px solid rgba(255,255,255,.08)}
.help-fab:hover .help-tip-lbl{opacity:1}

/* ── Help Modal ── */
.help-over{position:fixed;inset:0;z-index:8500;background:rgba(2,6,23,.75);backdrop-filter:blur(12px);display:flex;align-items:flex-end;justify-content:center;padding:0;opacity:0;pointer-events:none;transition:opacity .3s}
@media(min-width:640px){.help-over{align-items:center;padding:1.5rem}}
.help-over.open{opacity:1;pointer-events:all}
.help-modal{background:#fff;width:100%;max-width:620px;border-radius:28px 28px 0 0;max-height:90dvh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 -16px 60px rgba(0,0,0,.2);transform:translateY(40px);transition:transform .4s cubic-bezier(.34,1.15,.64,1)}
@media(min-width:640px){.help-modal{border-radius:28px;transform:scale(.94) translateY(20px);box-shadow:0 40px 80px -20px rgba(0,0,0,.4)}}
.help-over.open .help-modal{transform:none}
.help-hdr{flex-shrink:0;padding:1.25rem 1.5rem 0}
.help-handle{width:40px;height:4px;background:#e2e8f0;border-radius:999px;margin:0 auto 1.25rem}
.help-hdr-row{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding-bottom:1rem;border-bottom:1px solid #f1f5f9}
.help-x{width:38px;height:38px;border-radius:14px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:.9rem;transition:all .15s;flex-shrink:0;font-family:inherit}
.help-x:hover{background:#fee2e2;color:#dc2626}
.help-rtag{display:inline-flex;align-items:center;gap:5px;padding:.2rem .65rem;border-radius:999px;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;background:color-mix(in srgb,var(--a,#16a34a) 10%,white);color:var(--a,#16a34a);border:1px solid color-mix(in srgb,var(--a,#16a34a) 20%,white)}
.help-tabs-row{display:flex;padding:0 1.5rem;border-bottom:1px solid #f1f5f9;flex-shrink:0;overflow-x:auto}
.help-tabs-row::-webkit-scrollbar{display:none}
.help-tab{padding:.875rem 1rem;font-size:.78rem;font-weight:700;color:#94a3b8;background:none;border:none;cursor:pointer;border-bottom:2.5px solid transparent;white-space:nowrap;transition:all .15s;font-family:inherit;margin-bottom:-1px}
.help-tab:hover{color:#475569}
.help-tab.on{color:var(--a,#16a34a);border-bottom-color:var(--a,#16a34a)}
.help-body{flex:1;overflow-y:auto;padding:1.5rem}
.help-body::-webkit-scrollbar{width:4px}
.help-body::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:4px}
.help-pane{display:none}
.help-pane.on{display:block;animation:obslide .25s ease both}
.h-sec{margin-bottom:1.75rem}
.h-sec:last-child{margin-bottom:0}
.h-sec-title{font-size:.68rem;font-weight:900;color:#94a3b8;letter-spacing:.12em;text-transform:uppercase;margin-bottom:.875rem;display:flex;align-items:center;gap:8px}
.h-sec-title::after{content:'';flex:1;height:1px;background:#f1f5f9}
.h-step{display:flex;gap:12px;margin-bottom:.75rem;align-items:flex-start}
.h-num{width:28px;height:28px;border-radius:10px;flex-shrink:0;margin-top:1px;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:900;color:white;background:var(--a,#16a34a)}
.h-stxt{font-size:.85rem;color:#334155;line-height:1.65;font-weight:500}
.h-stxt strong{color:#0f172a;font-weight:700}
.h-tip{display:flex;gap:12px;padding:1rem 1.1rem;border-radius:16px;margin-bottom:.75rem;border:1px solid}
.h-tip-i{background:#eff6ff;border-color:#bfdbfe}
.h-tip-w{background:#fffbeb;border-color:#fde68a}
.h-tip-s{background:#f0fdf4;border-color:#bbf7d0}
.h-tip-ico{font-size:.9rem;flex-shrink:0;margin-top:2px}
.h-tip-i .h-tip-ico{color:#2563eb}
.h-tip-w .h-tip-ico{color:#d97706}
.h-tip-s .h-tip-ico{color:#16a34a}
.h-tip-txt{font-size:.83rem;line-height:1.65;color:#334155;font-weight:500}
.h-faq{border:1px solid #f1f5f9;border-radius:16px;margin-bottom:.625rem;overflow:hidden;transition:border-color .2s}
.h-faq.open{border-color:color-mix(in srgb,var(--a,#16a34a) 30%,white)}
.h-faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:1rem 1.1rem;background:none;border:none;cursor:pointer;text-align:left;font-family:inherit;font-size:.85rem;font-weight:700;color:#1e293b;transition:background .15s}
.h-faq-q:hover{background:#f8fafc}
.h-faq-q i{color:#94a3b8;font-size:.75rem;transition:transform .25s;flex-shrink:0}
.h-faq.open .h-faq-q i{transform:rotate(180deg);color:var(--a,#16a34a)}
.h-faq-a{font-size:.83rem;color:#475569;line-height:1.7;font-weight:500;padding:0 1.1rem 1rem;display:none}
.h-faq.open .h-faq-a{display:block;animation:obslide .2s ease both}
.h-restart{display:flex;align-items:center;gap:10px;padding:1rem 1.1rem;border-radius:16px;border:1.5px dashed #e2e8f0;background:none;cursor:pointer;width:100%;font-family:inherit;font-size:.85rem;font-weight:700;color:#64748b;transition:all .2s;margin-top:.875rem}
.h-restart:hover{border-color:var(--a,#16a34a);color:var(--a,#16a34a);background:color-mix(in srgb,var(--a,#16a34a) 5%,white)}
</style>

<!-- ONBOARDING -->
<div id="ob" class="ob-wrap" style="--a:<?= $accent ?>">
  <div class="ob-bg" onclick="obClose()"></div>
  <div class="ob-card">
    <div class="ob-glow" id="ob-glow"></div>
    <div class="ob-top"></div>
    <div class="ob-prog-wrap"><div class="ob-prog-fill" id="ob-prog" style="width:0%"></div></div>
    <div class="ob-body">
      <div class="ob-row">
        <span class="ob-lbl" id="ob-lbl">Welcome</span>
        <div class="ob-dots" id="ob-dots"></div>
      </div>

      <?php if ($currentRole === 'chairman'): ?>
      <div class="ob-panel on"><div class="ob-ico"><i class="fa-solid fa-landmark"></i></div><h2 class="ob-title">Hello, <em><?= htmlspecialchars($userName) ?></em>!</h2><p class="ob-desc">Welcome to the <strong>SK E-Learning Resource Reservation System</strong>. As Barangay Chairman, you have full control. Let's take a quick tour!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-chart-line"></i></div><span class="ob-ctxt">Full analytics dashboard with live session tracking</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-user-shield"></i></div><span class="ob-ctxt">Approve SK officers and manage system access</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-user-shield"></i></div><h2 class="ob-title">Approve <em>SK Accounts</em></h2><p class="ob-desc">SK Officers need your approval before they can log in. Go to <strong>Manage SK</strong> to review and approve or reject applications.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-user-check"></i></div><span class="ob-ctxt">Review SK registrations with full profile details</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-envelope"></i></div><span class="ob-ctxt">Officers get notified automatically on your decision</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-calendar-check"></i></div><h2 class="ob-title">Manage <em>Reservations</em></h2><p class="ob-desc">View, approve, or decline all reservations. Track history and validate e-tickets with the QR scanner at the facility.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-ctxt">One-click approve or decline with instant notification</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-qrcode"></i></div><span class="ob-ctxt">QR scanner validates e-tickets at the facility</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-rocket"></i></div><h2 class="ob-title">You're <em>all set!</em></h2><p class="ob-desc">The <strong>Help button</strong> (bottom right) is always available. Start managing from your dashboard!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-circle-question"></i></div><span class="ob-ctxt">Tap Help anytime for guides, tips, and FAQ</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-chart-line"></i></div><span class="ob-ctxt">Track analytics and stats from your dashboard</span></div></div></div>

      <?php elseif ($currentRole === 'sk'): ?>
      <div class="ob-panel on"><div class="ob-ico"><i class="fa-solid fa-seedling"></i></div><h2 class="ob-title">Hello, <em><?= htmlspecialchars($userName) ?></em>!</h2><p class="ob-desc">Welcome to the <strong>SK Reservation System</strong>! Approve reservations, scan QR tickets, and manage the library. Let's get started!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-timer"></i></div><span class="ob-ctxt">Live session timer on your dashboard</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Amber badge shows pending requests needing attention</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-clipboard-list"></i></div><h2 class="ob-title">Review <em>User Requests</em></h2><p class="ob-desc">Residents submit requests needing your approval. Go to <strong>User Requests</strong> to review and approve or decline them.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Yellow badge shows how many requests are pending</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-check"></i></div><span class="ob-ctxt">Approved residents receive a QR e-ticket instantly</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-camera"></i></div><h2 class="ob-title">Scan <em>E-Tickets</em></h2><p class="ob-desc">Use the <strong>Scanner</strong> to scan residents' QR code e-tickets when they arrive. Marks them as claimed and logs the visit.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-camera"></i></div><span class="ob-ctxt">Works with printed and on-screen QR codes</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-print"></i></div><span class="ob-ctxt">Log printing activity after each session ends</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-rocket"></i></div><h2 class="ob-title">You're <em>ready to go!</em></h2><p class="ob-desc">Use the <strong>Help button</strong> (bottom right) anytime. The Active Sessions panel shows live time tracking!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-timer"></i></div><span class="ob-ctxt">Live session alerts when sessions end</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Manage library and book borrowing requests</span></div></div></div>

      <?php else: ?>
      <div class="ob-panel on"><div class="ob-ico"><i class="fa-solid fa-hand-wave"></i></div><h2 class="ob-title">Hi, <em><?= htmlspecialchars($userName) ?></em>!</h2><p class="ob-desc">Welcome to the <strong>SK E-Learning Resource Reservation System</strong> of Brgy. F. De Jesus. Reserve computers, borrow books, and track your schedule.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-desktop"></i></div><span class="ob-ctxt">Book computers and e-learning resources online</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Browse and borrow from the community library</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-calendar-check"></i></div><h2 class="ob-title">Make a <em>Reservation</em></h2><p class="ob-desc">Click <strong>New Reservation</strong> in the sidebar. Choose your date, time, and purpose — then submit for approval!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-desktop"></i></div><span class="ob-ctxt">Choose from available PCs and resources</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Requests reviewed by SK Officers or the Chairman</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-ticket"></i></div><h2 class="ob-title">Get Your <em>E-Ticket</em></h2><p class="ob-desc">When approved, you'll receive a <strong>QR Code E-Ticket</strong>. Show this to the SK Officer at the facility to claim your slot!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-qrcode"></i></div><span class="ob-ctxt">Download your QR ticket to your phone gallery</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-ctxt">Track all reservations from My Reservations</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-book-open"></i></div><h2 class="ob-title">Borrow <em>Books</em></h2><p class="ob-desc">Visit the <strong>Library</strong> to browse and borrow books. Use the <strong>AI Book Finder</strong> to get personalized suggestions!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Browse the full community book collection</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-wand-magic-sparkles"></i></div><span class="ob-ctxt">AI suggests books based on your mood</span></div></div></div>
      <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-graduation-cap"></i></div><h2 class="ob-title">You're <em>all set!</em></h2><p class="ob-desc">The <strong>Help button</strong> (bottom right) is always there. Happy learning!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-circle-question"></i></div><span class="ob-ctxt">Tap Help anytime for guides and FAQ</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-bell"></i></div><span class="ob-ctxt">Notifications alert you when reservations are approved</span></div></div></div>
      <?php endif; ?>
    </div>
    <div class="ob-foot">
      <button class="ob-skip" onclick="obClose()">✕ Skip</button>
      <button class="ob-btn" onclick="obNext()">
        <span id="ob-btn-lbl">Show Me Around</span>
        <i id="ob-btn-ico" class="fa-solid fa-arrow-right"></i>
      </button>
    </div>
  </div>
</div>

<!-- HELP FAB -->
<button class="help-fab" onclick="helpOpen()" style="--a:<?= $accent ?>" aria-label="Help">
  <i class="fa-solid fa-circle-question"></i>
  <span class="help-tip-lbl">Help & Guide</span>
</button>

<!-- HELP MODAL -->
<div id="help-over" class="help-over" style="--a:<?= $accent ?>">
  <div class="help-modal">
    <div class="help-hdr">
      <div class="help-handle"></div>
      <div class="help-hdr-row">
        <div style="display:flex;align-items:center;gap:12px">
          <div style="width:42px;height:42px;border-radius:16px;background:color-mix(in srgb,<?= $accent ?> 10%,white);display:flex;align-items:center;justify-content:center;color:<?= $accent ?>;font-size:1.1rem"><i class="fa-solid fa-circle-question"></i></div>
          <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px">
              <p style="font-size:1rem;font-weight:800;color:#0f172a;margin:0">Help & Guide</p>
              <span class="help-rtag"><i class="fa-solid <?= $roleIcon ?>" style="font-size:.6rem"></i><?= $roleLabel ?></span>
            </div>
            <p style="font-size:.72rem;color:#94a3b8;margin:0;font-weight:500">SK E-Learning Reservation System</p>
          </div>
        </div>
        <button class="help-x" onclick="helpClose()"><i class="fa-solid fa-xmark"></i></button>
      </div>
    </div>
    <div class="help-tabs-row">
      <?php if ($currentRole === 'chairman'): ?>
        <button class="help-tab on" onclick="hTab(this,'ht-ov')">Overview</button>
        <button class="help-tab" onclick="hTab(this,'ht-res')">Reservations</button>
        <button class="help-tab" onclick="hTab(this,'ht-sk')">SK Accounts</button>
        <button class="help-tab" onclick="hTab(this,'ht-faq')">FAQ</button>
      <?php elseif ($currentRole === 'sk'): ?>
        <button class="help-tab on" onclick="hTab(this,'ht-ov')">Overview</button>
        <button class="help-tab" onclick="hTab(this,'ht-app')">Approvals</button>
        <button class="help-tab" onclick="hTab(this,'ht-scan')">Scanner</button>
        <button class="help-tab" onclick="hTab(this,'ht-faq')">FAQ</button>
      <?php else: ?>
        <button class="help-tab on" onclick="hTab(this,'ht-ov')">Overview</button>
        <button class="help-tab" onclick="hTab(this,'ht-rsv')">Reservations</button>
        <button class="help-tab" onclick="hTab(this,'ht-lib')">Library</button>
        <button class="help-tab" onclick="hTab(this,'ht-faq')">FAQ</button>
      <?php endif; ?>
    </div>
    <div class="help-body">
      <!-- OVERVIEW -->
      <div id="ht-ov" class="help-pane on">
        <div class="h-sec">
          <p class="h-sec-title">Your Role — <?= $roleLabel ?></p>
          <?php if ($currentRole === 'chairman'): ?>
            <div class="h-tip h-tip-i"><i class="fa-solid fa-crown h-tip-ico"></i><p class="h-tip-txt">As Barangay Chairman, you have <strong>full access</strong> to the entire system.</p></div>
            <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage SK</strong> to approve or reject SK Officer applications.</p></div>
            <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Go to <strong>Reservations</strong> to review and approve resident requests.</p></div>
            <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Go to <strong>Manage PCs</strong> to add workstations and update status.</p></div>
          <?php elseif ($currentRole === 'sk'): ?>
            <div class="h-tip h-tip-s"><i class="fa-solid fa-user-shield h-tip-ico"></i><p class="h-tip-txt">As SK Officer, you can <strong>approve reservations</strong>, scan QR tickets, and manage the library.</p></div>
            <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Check <strong>User Requests</strong> daily for pending approvals.</p></div>
            <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Use the <strong>Scanner</strong> to validate e-tickets when residents arrive.</p></div>
            <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Manage the <strong>Library</strong> — add books, approve borrowing requests.</p></div>
          <?php else: ?>
            <div class="h-tip h-tip-s"><i class="fa-solid fa-user h-tip-ico"></i><p class="h-tip-txt">As a Resident, you can <strong>request reservations</strong>, borrow books, and track your schedule.</p></div>
            <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Click <strong>New Reservation</strong> to book a computer or other resource.</p></div>
            <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Check <strong>My Reservations</strong> to see the status of your requests.</p></div>
            <div class="h-step"><div class="h-num">3</div><p class="h-stxt">When approved, show your <strong>QR Code E-Ticket</strong> at the facility.</p></div>
            <div class="h-step"><div class="h-num">4</div><p class="h-stxt">Browse the <strong>Library</strong> and request to borrow books.</p></div>
          <?php endif; ?>
        </div>
        <button class="h-restart" onclick="helpClose();obRestart()"><i class="fa-solid fa-rotate-left" style="color:<?= $accent ?>"></i>Replay the onboarding tour</button>
      </div>
      <!-- ROLE-SPECIFIC TABS -->
      <?php if ($currentRole === 'chairman'): ?>
      <div id="ht-res" class="help-pane"><div class="h-sec"><p class="h-sec-title">Approving Reservations</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage Reservations</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Filter by <strong>Pending</strong> to see requests waiting for approval.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click a reservation then click <strong>Approve</strong> or <strong>Decline</strong>.</p></div><div class="h-tip h-tip-i"><i class="fa-solid fa-ticket h-tip-ico"></i><p class="h-tip-txt">Residents receive a QR code e-ticket automatically once you approve.</p></div></div></div>
      <div id="ht-sk" class="help-pane"><div class="h-sec"><p class="h-sec-title">Approving SK Officers</p><div class="h-tip h-tip-w"><i class="fa-solid fa-triangle-exclamation h-tip-ico"></i><p class="h-tip-txt">SK Officers can only log in after you approve their account.</p></div><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage SK</strong> — a badge shows pending approvals.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Click <strong>View</strong> on an SK account to see their details.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Approve</strong> to grant access or <strong>Reject</strong> to deny.</p></div></div></div>
      <?php elseif ($currentRole === 'sk'): ?>
      <div id="ht-app" class="help-pane"><div class="h-sec"><p class="h-sec-title">Approving User Requests</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>User Requests</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Review each request — check the date, time, and resource.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Approve</strong> to confirm or <strong>Decline</strong> to reject.</p></div><div class="h-tip h-tip-s"><i class="fa-solid fa-bell h-tip-ico"></i><p class="h-tip-txt">The yellow badge shows how many requests need attention.</p></div></div></div>
      <div id="ht-scan" class="help-pane"><div class="h-sec"><p class="h-sec-title">Using the QR Scanner</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Scanner</strong> from the sidebar or mobile nav.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Click <strong>Allow</strong> when your browser asks for camera permission.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Point the camera at the resident's QR code — validates automatically.</p></div><div class="h-tip h-tip-i"><i class="fa-solid fa-lightbulb h-tip-ico"></i><p class="h-tip-txt">Ensure good lighting. Works with printed and on-screen QR codes.</p></div></div></div>
      <?php else: ?>
      <div id="ht-rsv" class="help-pane"><div class="h-sec"><p class="h-sec-title">Making a Reservation</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Click <strong>New Reservation</strong> in the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Select the <strong>Resource</strong>, choose your <strong>Date</strong> and <strong>Time</strong>.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Enter your <strong>Purpose</strong> and submit the request.</p></div><div class="h-tip h-tip-w"><i class="fa-solid fa-triangle-exclamation h-tip-ico"></i><p class="h-tip-txt">You have a <strong>monthly quota of 3 reservations</strong>. Slots reset each month.</p></div></div><div class="h-sec"><p class="h-sec-title">Using Your E-Ticket</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">When approved, go to <strong>My Reservations</strong> and click your reservation.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Your <strong>QR Code E-Ticket</strong> appears — tap <strong>Download</strong> to save it.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Show the QR code to the SK Officer at the facility.</p></div><div class="h-tip h-tip-i"><i class="fa-solid fa-mobile-alt h-tip-ico"></i><p class="h-tip-txt">Save your e-ticket to your phone — works without internet!</p></div></div></div>
      <div id="ht-lib" class="help-pane"><div class="h-sec"><p class="h-sec-title">Borrowing a Book</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Library</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Browse — a <strong>green dot</strong> means copies are available.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Borrow</strong> and wait for SK Officer approval.</p></div><div class="h-tip h-tip-s"><i class="fa-solid fa-wand-magic-sparkles h-tip-ico"></i><p class="h-tip-txt">Use the <strong>AI Book Finder</strong> on your dashboard for personalized picks!</p></div></div></div>
      <?php endif; ?>
      <!-- FAQ -->
      <div id="ht-faq" class="help-pane">
        <div class="h-sec"><p class="h-sec-title">Frequently Asked Questions</p>
          <?php if ($currentRole !== 'chairman'): ?><div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">I didn't receive a verification email.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Check your Spam or Junk folder. Try registering again or contact the Barangay office.</p></div><?php endif; ?>
          <?php if ($currentRole === 'sk'): ?><div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">My account says Pending after email verification.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">The Barangay Chairman must approve your SK account. You'll receive an email notification once a decision is made.</p></div><?php endif; ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">The page shows a 404 error after the site wakes up.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">The server may have been inactive. Refresh the page and go back to <strong>reservation-k2eg.onrender.com</strong>.</p></div>
          <?php if ($currentRole === 'user'): ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">My reservation is stuck on Pending.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Contact an SK Officer or the Barangay Chairman directly to review your request.</p></div>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">Can I cancel a reservation?<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Only Pending reservations can be cancelled. Go to My Reservations and click Cancel.</p></div>
          <?php endif; ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">I forgot my password.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Click <strong>Forgot Password?</strong> on the login page. Enter your email to receive a 6-digit code, then set a new password.</p></div>
          <?php if ($currentRole === 'sk'): ?><div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">The QR scanner is not working.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Allow camera access in your browser. Ensure good lighting and hold the code steady. Works best in Chrome or Safari.</p></div><?php endif; ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">How do I install this as an app on my phone?<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Android (Chrome): 3-dot menu → Add to Home Screen → Install. iPhone (Safari): Share → Add to Home Screen → Add.</p></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const ROLE='<?= $currentRole ?>',KEY='ob_v5_'+ROLE;
  const panels=[...document.querySelectorAll('#ob .ob-panel')];
  const TOTAL=panels.length;
  let step=0;
  const dotsEl=document.getElementById('ob-dots');
  for(let i=0;i<TOTAL;i++){const d=document.createElement('div');d.className='ob-dot'+(i===0?' on':'');dotsEl.appendChild(d);}

  function upd(n){
    panels.forEach((p,i)=>p.classList.toggle('on',i===n));
    [...dotsEl.children].forEach((d,i)=>{d.classList.remove('on','done');if(i<n)d.classList.add('done');if(i===n)d.classList.add('on');});
    const pct=TOTAL<=1?100:Math.round(n/(TOTAL-1)*100);
    document.getElementById('ob-prog').style.width=pct+'%';
    document.getElementById('ob-lbl').textContent=n===0?'Welcome':(n===TOTAL-1?'All done!':`Step ${n} of ${TOTAL-1}`);
    const last=n===TOTAL-1;
    document.getElementById('ob-btn-lbl').textContent=last?'Start Exploring':(n===0?'Show Me Around':'Next');
    document.getElementById('ob-btn-ico').className='fa-solid '+(last?'fa-rocket':'fa-arrow-right');
  }
  window.obClose=()=>{localStorage.setItem(KEY,'1');document.getElementById('ob').classList.remove('open');document.body.style.overflow='';};
  window.obRestart=()=>{step=0;localStorage.removeItem(KEY);upd(0);setTimeout(()=>{document.getElementById('ob').classList.add('open');document.body.style.overflow='hidden';},100);};
  window.obNext=()=>{if(step<TOTAL-1){step++;upd(step);}else obClose();};
  upd(0);
  if(!localStorage.getItem(KEY))setTimeout(()=>{document.getElementById('ob').classList.add('open');document.body.style.overflow='hidden';},900);

  window.helpOpen=()=>{document.getElementById('help-over').classList.add('open');document.body.style.overflow='hidden';};
  window.helpClose=()=>{document.getElementById('help-over').classList.remove('open');document.body.style.overflow='';};
  window.hTab=(btn,id)=>{document.querySelectorAll('.help-tab').forEach(t=>t.classList.remove('on'));document.querySelectorAll('.help-pane').forEach(p=>p.classList.remove('on'));btn.classList.add('on');document.getElementById(id).classList.add('on');};
  window.hFaq=btn=>{const item=btn.closest('.h-faq');const o=item.classList.contains('open');document.querySelectorAll('.h-faq').forEach(i=>i.classList.remove('open'));if(!o)item.classList.add('open');};
  document.getElementById('help-over').addEventListener('click',e=>{if(e.target===document.getElementById('help-over'))helpClose();});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'){helpClose();obClose();}});
})();
</script>