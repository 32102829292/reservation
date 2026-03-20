<?php
$currentRole = $role ?? session()->get('role') ?? 'user';
$userName    = session()->get('name') ?? 'there';
$accent      = match($currentRole) { 'chairman' => '#2563eb', 'sk' => '#16a34a', default => '#16a34a' };
$roleLabel   = match($currentRole) { 'chairman' => 'Chairman', 'sk' => 'SK Officer', default => 'Resident' };
$roleIcon    = match($currentRole) { 'chairman' => 'fa-crown', 'sk' => 'fa-user-shield', default => 'fa-user' };
?>
<style>
:root{--a:<?= $accent ?>}
/* ── Shared ── */
.ob-wrap,.help-over{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;pointer-events:none;transition:opacity .2s}
.ob-wrap.open,.help-over.open{opacity:1;pointer-events:all}
.ob-bg,.help-bg{position:absolute;inset:0;background:rgba(2,6,23,.85);backdrop-filter:blur(8px)}
/* ── Onboarding card ── */
.ob-card{position:relative;z-index:1;width:100%;max-width:360px;background:#09090b;border:1px solid rgba(255,255,255,.08);border-radius:20px;overflow:hidden;box-shadow:0 24px 48px -8px rgba(0,0,0,.7);transform:translateY(12px) scale(.97);transition:transform .28s cubic-bezier(.34,1.2,.64,1)}
.ob-wrap.open .ob-card{transform:none}
.ob-top{height:2px;background:linear-gradient(90deg,transparent,var(--a),transparent)}
.ob-prog-wrap{height:2px;background:rgba(255,255,255,.06)}
.ob-prog-fill{height:100%;background:var(--a);border-radius:0 2px 2px 0;transition:width .4s ease}
.ob-body{padding:1.4rem 1.4rem .9rem}
.ob-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.1rem}
.ob-lbl{font-size:10px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--a)}
.ob-dots{display:flex;gap:4px}
.ob-dot{height:3px;width:16px;border-radius:999px;background:rgba(255,255,255,.1);transition:all .3s ease}
.ob-dot.on{background:var(--a);width:26px}
.ob-dot.done{background:color-mix(in srgb,var(--a) 40%,transparent);width:20px}
.ob-ico{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1rem;margin-bottom:1rem;background:color-mix(in srgb,var(--a) 12%,#18181b);border:1px solid color-mix(in srgb,var(--a) 25%,transparent);color:var(--a)}
.ob-title{font-size:1.2rem;font-weight:900;color:#fafafa;letter-spacing:-.3px;line-height:1.2;margin:0 0 .45rem}
.ob-title em{font-style:normal;color:var(--a)}
.ob-desc{font-size:.79rem;line-height:1.6;color:#71717a;margin:0}
.ob-desc strong{color:#a1a1aa;font-weight:600}
.ob-chips{display:flex;flex-direction:column;gap:5px;margin-top:.9rem}
.ob-chip{display:flex;align-items:center;gap:9px;padding:.5rem .8rem;border-radius:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06)}
.ob-cico{width:24px;height:24px;border-radius:7px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.68rem;background:color-mix(in srgb,var(--a) 14%,#18181b);color:var(--a)}
.ob-ctxt{font-size:.73rem;font-weight:500;color:#71717a}
.ob-panel{display:none}
.ob-panel.on{display:block;animation:obIn .3s cubic-bezier(.34,1.1,.64,1) both}
@keyframes obIn{from{opacity:0;transform:translateX(8px)}to{opacity:1;transform:none}}
.ob-foot{display:flex;align-items:center;gap:8px;padding:.9rem 1.4rem 1.4rem;border-top:1px solid rgba(255,255,255,.05)}
.ob-skip{font-size:.76rem;font-weight:600;color:#3f3f46;background:none;border:none;cursor:pointer;font-family:inherit;padding:.5rem .6rem;border-radius:9px;transition:color .15s}
.ob-skip:hover{color:#71717a}
.ob-btn{flex:1;padding:.7rem 1.1rem;border:none;border-radius:11px;font-size:.82rem;font-weight:800;color:#09090b;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;background:var(--a);transition:transform .2s,box-shadow .2s}
.ob-btn:hover{transform:translateY(-1px);box-shadow:0 10px 24px -6px color-mix(in srgb,var(--a) 55%,transparent)}
/* ── Help FAB ── */
.help-fab{position:fixed;bottom:88px;right:18px;z-index:8000;width:48px;height:48px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:white;box-shadow:0 6px 20px -4px rgba(0,0,0,.3);transition:transform .25s ease,box-shadow .25s;background:var(--a)}
.help-fab:hover{transform:scale(1.1) translateY(-2px);box-shadow:0 12px 32px -6px rgba(0,0,0,.3)}
/* ── Help modal ── */
.help-over{z-index:8500;align-items:flex-end;padding:0}
@media(min-width:640px){.help-over{align-items:center;padding:1.5rem}}
.help-modal{position:relative;z-index:1;background:#fff;width:100%;max-width:600px;border-radius:24px 24px 0 0;max-height:88dvh;overflow:hidden;display:flex;flex-direction:column;transform:translateY(32px);transition:transform .35s cubic-bezier(.34,1.15,.64,1)}
@media(min-width:640px){.help-modal{border-radius:24px;transform:scale(.94) translateY(16px)}}
.help-over.open .help-modal{transform:none}
.help-hdr{flex-shrink:0;padding:1.1rem 1.4rem 0}
.help-handle{width:36px;height:3px;background:#e2e8f0;border-radius:999px;margin:0 auto 1.1rem}
.help-hdr-row{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding-bottom:.9rem;border-bottom:1px solid #f1f5f9}
.help-x{width:36px;height:36px;border-radius:12px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:.85rem;transition:all .15s;font-family:inherit}
.help-x:hover{background:#fee2e2;color:#dc2626}
.help-rtag{display:inline-flex;align-items:center;gap:4px;padding:.18rem .55rem;border-radius:999px;font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;background:color-mix(in srgb,var(--a) 10%,white);color:var(--a);border:1px solid color-mix(in srgb,var(--a) 20%,white)}
.help-tabs{display:flex;padding:0 1.4rem;border-bottom:1px solid #f1f5f9;flex-shrink:0;overflow-x:auto}
.help-tabs::-webkit-scrollbar{display:none}
.help-tab{padding:.8rem .9rem;font-size:.77rem;font-weight:700;color:#94a3b8;background:none;border:none;cursor:pointer;border-bottom:2px solid transparent;white-space:nowrap;transition:color .15s;font-family:inherit;margin-bottom:-1px}
.help-tab.on{color:var(--a);border-bottom-color:var(--a)}
.help-body{flex:1;overflow-y:auto;padding:1.4rem}
.help-body::-webkit-scrollbar{width:4px}
.help-body::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:4px}
.help-pane{display:none}
.help-pane.on{display:block;animation:obIn .22s ease both}
.h-sec{margin-bottom:1.5rem}
.h-sec:last-child{margin-bottom:0}
.h-sec-title{font-size:.67rem;font-weight:900;color:#94a3b8;letter-spacing:.12em;text-transform:uppercase;margin-bottom:.8rem;display:flex;align-items:center;gap:8px}
.h-sec-title::after{content:'';flex:1;height:1px;background:#f1f5f9}
.h-step{display:flex;gap:10px;margin-bottom:.65rem;align-items:flex-start}
.h-num{width:26px;height:26px;border-radius:9px;flex-shrink:0;margin-top:2px;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:900;color:white;background:var(--a)}
.h-stxt{font-size:.84rem;color:#334155;line-height:1.6;font-weight:500}
.h-stxt strong{color:#0f172a;font-weight:700}
.h-tip{display:flex;gap:11px;padding:.9rem 1rem;border-radius:14px;margin-bottom:.7rem;border:1px solid}
.h-tip-i{background:#eff6ff;border-color:#bfdbfe}.h-tip-w{background:#fffbeb;border-color:#fde68a}.h-tip-s{background:#f0fdf4;border-color:#bbf7d0}
.h-tip-ico{font-size:.85rem;flex-shrink:0;margin-top:2px}
.h-tip-i .h-tip-ico{color:#2563eb}.h-tip-w .h-tip-ico{color:#d97706}.h-tip-s .h-tip-ico{color:#16a34a}
.h-tip-txt{font-size:.82rem;line-height:1.6;color:#334155;font-weight:500}
.h-faq{border:1px solid #f1f5f9;border-radius:14px;margin-bottom:.5rem;overflow:hidden}
.h-faq.open{border-color:color-mix(in srgb,var(--a) 30%,white)}
.h-faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.9rem 1rem;background:none;border:none;cursor:pointer;text-align:left;font-family:inherit;font-size:.84rem;font-weight:700;color:#1e293b;transition:background .15s}
.h-faq-q:hover{background:#f8fafc}
.h-faq-q i{color:#94a3b8;font-size:.72rem;transition:transform .22s;flex-shrink:0}
.h-faq.open .h-faq-q i{transform:rotate(180deg);color:var(--a)}
.h-faq-a{font-size:.82rem;color:#475569;line-height:1.65;font-weight:500;padding:0 1rem .9rem;display:none}
.h-faq.open .h-faq-a{display:block}
.h-restart{display:flex;align-items:center;gap:9px;padding:.9rem 1rem;border-radius:14px;border:1.5px dashed #e2e8f0;background:none;cursor:pointer;width:100%;font-family:inherit;font-size:.84rem;font-weight:700;color:#64748b;transition:all .18s;margin-top:.75rem}
.h-restart:hover{border-color:var(--a);color:var(--a);background:color-mix(in srgb,var(--a) 5%,white)}
</style>

<!-- ── ONBOARDING ── -->
<div id="ob" class="ob-wrap">
  <div class="ob-bg" onclick="obClose()"></div>
  <div class="ob-card">
    <div class="ob-top"></div>
    <div class="ob-prog-wrap"><div class="ob-prog-fill" id="ob-prog" style="width:0%"></div></div>
    <div class="ob-body">
      <div class="ob-row">
        <span class="ob-lbl" id="ob-lbl">Welcome</span>
        <div class="ob-dots" id="ob-dots"></div>
      </div>

      <?php if ($currentRole === 'chairman'): ?>
        <div class="ob-panel on"><div class="ob-ico"><i class="fa-solid fa-landmark"></i></div><h2 class="ob-title">Hello, <em><?= htmlspecialchars($userName) ?></em>!</h2><p class="ob-desc">Welcome to the <strong>SK Reservation System</strong>. As Chairman, you have full control. Quick tour?</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-chart-line"></i></div><span class="ob-ctxt">Full analytics with live session tracking</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-user-shield"></i></div><span class="ob-ctxt">Approve SK officers and manage access</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-user-shield"></i></div><h2 class="ob-title">Approve <em>SK Accounts</em></h2><p class="ob-desc">SK Officers need your approval before logging in. Go to <strong>Manage SK</strong> to review applications.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-user-check"></i></div><span class="ob-ctxt">Review SK registrations with full details</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-envelope"></i></div><span class="ob-ctxt">Officers notified automatically on your decision</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-calendar-check"></i></div><h2 class="ob-title">Manage <em>Reservations</em></h2><p class="ob-desc">View, approve, or decline all reservations. Track history and validate e-tickets with the QR scanner.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-ctxt">One-click approve or decline</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-qrcode"></i></div><span class="ob-ctxt">QR scanner validates tickets at the facility</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-rocket"></i></div><h2 class="ob-title">You're <em>all set!</em></h2><p class="ob-desc">The <strong>Help button</strong> (bottom right) is always available.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-circle-question"></i></div><span class="ob-ctxt">Tap Help anytime for guides and FAQ</span></div></div></div>

      <?php elseif ($currentRole === 'sk'): ?>
        <div class="ob-panel on"><div class="ob-ico"><i class="fa-solid fa-seedling"></i></div><h2 class="ob-title">Hello, <em><?= htmlspecialchars($userName) ?></em>!</h2><p class="ob-desc">Welcome! Approve reservations, scan QR tickets, and manage the library. Let's get started!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-timer"></i></div><span class="ob-ctxt">Live session timer on your dashboard</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Amber badge shows pending requests</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-clipboard-list"></i></div><h2 class="ob-title">Review <em>User Requests</em></h2><p class="ob-desc">Go to <strong>User Requests</strong> to approve or decline resident reservation requests.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Yellow badge shows pending count</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-check"></i></div><span class="ob-ctxt">Approved residents get a QR e-ticket instantly</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-camera"></i></div><h2 class="ob-title">Scan <em>E-Tickets</em></h2><p class="ob-desc">Use the <strong>Scanner</strong> to validate residents' QR tickets when they arrive.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-camera"></i></div><span class="ob-ctxt">Works with printed and on-screen QR codes</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-rocket"></i></div><h2 class="ob-title">You're <em>ready!</em></h2><p class="ob-desc">Use the <strong>Help button</strong> anytime. The Active Sessions panel shows live time tracking!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Manage library and book borrowing requests</span></div></div></div>

      <?php else: ?>
        <div class="ob-panel on"><div class="ob-ico"><i class="fa-solid fa-hand-wave"></i></div><h2 class="ob-title">Hi, <em><?= htmlspecialchars($userName) ?></em>!</h2><p class="ob-desc">Welcome to the <strong>SK Reservation System</strong>. Reserve computers, borrow books, track your schedule.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-desktop"></i></div><span class="ob-ctxt">Book computers and e-learning resources</span></div><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Browse and borrow from the community library</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-calendar-check"></i></div><h2 class="ob-title">Make a <em>Reservation</em></h2><p class="ob-desc">Click <strong>New Reservation</strong>. Choose your date, time, and purpose — then submit for approval.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Monthly quota of 3 reservations</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-ticket"></i></div><h2 class="ob-title">Get Your <em>E-Ticket</em></h2><p class="ob-desc">When approved, you'll receive a <strong>QR Code E-Ticket</strong>. Show it to the SK Officer at the facility.</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-qrcode"></i></div><span class="ob-ctxt">Download to your phone — works offline</span></div></div></div>
        <div class="ob-panel"><div class="ob-ico"><i class="fa-solid fa-graduation-cap"></i></div><h2 class="ob-title">You're <em>all set!</em></h2><p class="ob-desc">The <strong>Help button</strong> (bottom right) is always there. Happy learning!</p><div class="ob-chips"><div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-wand-magic-sparkles"></i></div><span class="ob-ctxt">AI Book Finder suggests reads for you</span></div></div></div>
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

<!-- ── HELP FAB ── -->
<button class="help-fab" onclick="helpOpen()" aria-label="Help & Guide">
  <i class="fa-solid fa-circle-question"></i>
</button>

<!-- ── HELP MODAL ── -->
<div id="help-over" class="help-over">
  <div class="ob-bg" onclick="helpClose()"></div>
  <div class="help-modal">
    <div class="help-hdr">
      <div class="help-handle"></div>
      <div class="help-hdr-row">
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:40px;height:40px;border-radius:14px;background:color-mix(in srgb,<?= $accent ?> 10%,white);display:flex;align-items:center;justify-content:center;color:<?= $accent ?>;font-size:1rem"><i class="fa-solid fa-circle-question"></i></div>
          <div>
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:2px">
              <p style="font-size:.95rem;font-weight:800;color:#0f172a;margin:0">Help & Guide</p>
              <span class="help-rtag"><i class="fa-solid <?= $roleIcon ?>" style="font-size:.58rem"></i><?= $roleLabel ?></span>
            </div>
            <p style="font-size:.7rem;color:#94a3b8;margin:0;font-weight:500">SK E-Learning Reservation System</p>
          </div>
        </div>
        <button class="help-x" onclick="helpClose()"><i class="fa-solid fa-xmark"></i></button>
      </div>
    </div>
    <div class="help-tabs">
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

      <!-- Overview (all roles) -->
      <div id="ht-ov" class="help-pane on">
        <div class="h-sec">
          <p class="h-sec-title">Your Role — <?= $roleLabel ?></p>
          <?php if ($currentRole === 'chairman'): ?>
            <div class="h-tip h-tip-i"><i class="fa-solid fa-crown h-tip-ico"></i><p class="h-tip-txt">As Barangay Chairman, you have <strong>full access</strong> to the entire system.</p></div>
            <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage SK</strong> to approve or reject SK Officer applications.</p></div>
            <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Go to <strong>Reservations</strong> to review and approve resident requests.</p></div>
            <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Go to <strong>Manage PCs</strong> to add workstations and update their status.</p></div>
          <?php elseif ($currentRole === 'sk'): ?>
            <div class="h-tip h-tip-s"><i class="fa-solid fa-user-shield h-tip-ico"></i><p class="h-tip-txt">As SK Officer, you can <strong>approve reservations</strong>, scan QR tickets, and manage the library.</p></div>
            <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Check <strong>User Requests</strong> daily for pending approvals.</p></div>
            <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Use the <strong>Scanner</strong> to validate e-tickets when residents arrive.</p></div>
            <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Manage the <strong>Library</strong> — add books and approve borrowing requests.</p></div>
          <?php else: ?>
            <div class="h-tip h-tip-s"><i class="fa-solid fa-user h-tip-ico"></i><p class="h-tip-txt">As a Resident, you can <strong>request reservations</strong>, borrow books, and track your schedule.</p></div>
            <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Click <strong>New Reservation</strong> to book a computer or resource.</p></div>
            <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Check <strong>My Reservations</strong> to see your request status.</p></div>
            <div class="h-step"><div class="h-num">3</div><p class="h-stxt">When approved, show your <strong>QR E-Ticket</strong> at the facility.</p></div>
            <div class="h-step"><div class="h-num">4</div><p class="h-stxt">Browse the <strong>Library</strong> and request to borrow books.</p></div>
          <?php endif; ?>
        </div>
        <button class="h-restart" onclick="helpClose();obRestart()"><i class="fa-solid fa-rotate-left" style="color:<?= $accent ?>"></i>Replay the onboarding tour</button>
      </div>

      <!-- Chairman tabs -->
      <?php if ($currentRole === 'chairman'): ?>
      <div id="ht-res" class="help-pane"><div class="h-sec"><p class="h-sec-title">Approving Reservations</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage Reservations</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Filter by <strong>Pending</strong> to see requests waiting for approval.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click a reservation then click <strong>Approve</strong> or <strong>Decline</strong>.</p></div><div class="h-tip h-tip-i"><i class="fa-solid fa-ticket h-tip-ico"></i><p class="h-tip-txt">Residents receive a QR e-ticket automatically once you approve.</p></div></div></div>
      <div id="ht-sk" class="help-pane"><div class="h-sec"><p class="h-sec-title">Approving SK Officers</p><div class="h-tip h-tip-w"><i class="fa-solid fa-triangle-exclamation h-tip-ico"></i><p class="h-tip-txt">SK Officers can only log in after you approve their account.</p></div><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage SK</strong> — a badge shows pending approvals.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Click <strong>View</strong> on an SK account to see their details.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Approve</strong> to grant access or <strong>Reject</strong> to deny.</p></div></div></div>

      <!-- SK tabs -->
      <?php elseif ($currentRole === 'sk'): ?>
      <div id="ht-app" class="help-pane"><div class="h-sec"><p class="h-sec-title">Approving User Requests</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>User Requests</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Review each request — check the date, time, and resource.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Approve</strong> to confirm or <strong>Decline</strong> to reject.</p></div><div class="h-tip h-tip-s"><i class="fa-solid fa-bell h-tip-ico"></i><p class="h-tip-txt">The yellow badge shows how many requests need attention.</p></div></div></div>
      <div id="ht-scan" class="help-pane"><div class="h-sec"><p class="h-sec-title">Using the QR Scanner</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Scanner</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Click <strong>Allow</strong> when asked for camera permission.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Point the camera at the QR code — validates automatically.</p></div><div class="h-tip h-tip-i"><i class="fa-solid fa-lightbulb h-tip-ico"></i><p class="h-tip-txt">Good lighting helps. Works with printed and on-screen QR codes.</p></div></div></div>

      <!-- User tabs -->
      <?php else: ?>
      <div id="ht-rsv" class="help-pane"><div class="h-sec"><p class="h-sec-title">Making a Reservation</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Click <strong>New Reservation</strong> in the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Select resource, choose date and time, enter purpose.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Submit — wait for SK Officer approval.</p></div><div class="h-tip h-tip-w"><i class="fa-solid fa-triangle-exclamation h-tip-ico"></i><p class="h-tip-txt">You have a <strong>monthly quota of 3 reservations</strong>. Resets each month.</p></div></div><div class="h-sec"><p class="h-sec-title">Using Your E-Ticket</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>My Reservations</strong> and click your approved reservation.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">Tap <strong>Download</strong> to save the QR code to your phone.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Show the QR code to the SK Officer at the facility.</p></div></div></div>
      <div id="ht-lib" class="help-pane"><div class="h-sec"><p class="h-sec-title">Borrowing a Book</p><div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Library</strong> from the sidebar.</p></div><div class="h-step"><div class="h-num">2</div><p class="h-stxt">A <strong>green dot</strong> means copies are available to borrow.</p></div><div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Borrow</strong> and wait for SK Officer approval.</p></div><div class="h-tip h-tip-s"><i class="fa-solid fa-wand-magic-sparkles h-tip-ico"></i><p class="h-tip-txt">Use the <strong>AI Book Finder</strong> on your dashboard for personalized picks!</p></div></div></div>
      <?php endif; ?>

      <!-- FAQ (all roles) -->
      <div id="ht-faq" class="help-pane">
        <div class="h-sec"><p class="h-sec-title">Frequently Asked Questions</p>
          <?php if ($currentRole !== 'chairman'): ?><div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">I didn't receive a verification email.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Check your Spam folder. Try registering again or contact the Barangay office.</p></div><?php endif; ?>
          <?php if ($currentRole === 'sk'): ?><div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">My account says Pending after email verification.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">The Barangay Chairman must approve your SK account. You'll receive an email when a decision is made.</p></div><?php endif; ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">The page shows a 404 after the site wakes up.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">The server may have been inactive. Refresh the page and navigate back to the site.</p></div>
          <?php if ($currentRole === 'user'): ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">My reservation is stuck on Pending.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Contact an SK Officer or the Barangay Chairman directly to review your request.</p></div>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">Can I cancel a reservation?<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Only Pending reservations can be cancelled. Go to My Reservations and click Cancel.</p></div>
          <?php endif; ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">I forgot my password.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Click <strong>Forgot Password?</strong> on the login page. Enter your email to get a 6-digit reset code.</p></div>
          <?php if ($currentRole === 'sk'): ?><div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">The QR scanner is not working.<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Allow camera access in your browser. Good lighting and holding the code steady helps. Best in Chrome or Safari.</p></div><?php endif; ?>
          <div class="h-faq"><button class="h-faq-q" onclick="hFaq(this)">How do I install this as an app?<i class="fa-solid fa-chevron-down"></i></button><p class="h-faq-a">Android (Chrome): 3-dot menu → Add to Home Screen. iPhone (Safari): Share → Add to Home Screen.</p></div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
(function(){
  const ROLE='<?= $currentRole ?>',KEY='ob_v6_'+ROLE;
  const panels=[...document.querySelectorAll('#ob .ob-panel')];
  const TOTAL=panels.length;
  let step=0;
  const dotsEl=document.getElementById('ob-dots');
  for(let i=0;i<TOTAL;i++){const d=document.createElement('div');d.className='ob-dot'+(i===0?' on':'');dotsEl.appendChild(d);}

  function upd(n){
    panels.forEach((p,i)=>p.classList.toggle('on',i===n));
    [...dotsEl.children].forEach((d,i)=>{d.classList.remove('on','done');if(i<n)d.classList.add('done');else if(i===n)d.classList.add('on');});
    const pct=TOTAL<=1?100:Math.round(n/(TOTAL-1)*100);
    document.getElementById('ob-prog').style.width=pct+'%';
    document.getElementById('ob-lbl').textContent=n===0?'Welcome':(n===TOTAL-1?'All done!':`Step ${n} of ${TOTAL-1}`);
    const last=n===TOTAL-1;
    document.getElementById('ob-btn-lbl').textContent=last?'Start Exploring':(n===0?'Show Me Around':'Next');
    document.getElementById('ob-btn-ico').className='fa-solid '+(last?'fa-rocket':'fa-arrow-right');
  }

  window.obClose=()=>{localStorage.setItem(KEY,'1');document.getElementById('ob').classList.remove('open');document.body.style.overflow='';};
  window.obRestart=()=>{step=0;localStorage.removeItem(KEY);upd(0);setTimeout(()=>{document.getElementById('ob').classList.add('open');document.body.style.overflow='hidden';},100);};
  window.obNext=()=>{step<TOTAL-1?upd(++step):obClose();};
  upd(0);
  if(!localStorage.getItem(KEY))setTimeout(()=>{document.getElementById('ob').classList.add('open');document.body.style.overflow='hidden';},900);

  window.helpOpen=()=>{document.getElementById('help-over').classList.add('open');document.body.style.overflow='hidden';};
  window.helpClose=()=>{document.getElementById('help-over').classList.remove('open');document.body.style.overflow='';};
  window.hTab=(btn,id)=>{document.querySelectorAll('.help-tab').forEach(t=>t.classList.remove('on'));document.querySelectorAll('.help-pane').forEach(p=>p.classList.remove('on'));btn.classList.add('on');document.getElementById(id).classList.add('on');};
  window.hFaq=btn=>{const item=btn.closest('.h-faq'),o=item.classList.contains('open');document.querySelectorAll('.h-faq').forEach(i=>i.classList.remove('open'));if(!o)item.classList.add('open');};
  document.getElementById('help-over').addEventListener('click',e=>{if(e.target===document.getElementById('help-over'))helpClose();});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'){helpClose();obClose();}});
})();
</script>