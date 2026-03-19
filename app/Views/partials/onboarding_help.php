<?php
/**
 * Onboarding Tour + Help Modal
 * Include this file in any dashboard view before </body>
 *
 * Usage:
 *   <?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
 *
 * The onboarding tour shows automatically on first visit (per role).
 * The help button (floating bottom-right) is always visible.
 *
 * Pass $role variable before including: 'user', 'sk', or 'chairman'
 */
$currentRole = $role ?? session()->get('role') ?? 'user';
$userName    = session()->get('name') ?? 'there';
?>

<!-- ═══════════════════════════════════════════════════
     ONBOARDING + HELP STYLES
════════════════════════════════════════════════════ -->
<style>
/* ── Variables ── */
:root {
  --ob-green:  #16a34a;
  --ob-blue:   #2563eb;
  --ob-amber:  #d97706;
  --ob-radius: 28px;
}

/* ── Backdrop ── */
.ob-backdrop {
  position: fixed; inset: 0; z-index: 9000;
  background: rgba(10,15,30,0.72);
  backdrop-filter: blur(8px);
  display: flex; align-items: center; justify-content: center;
  padding: 1rem;
  opacity: 0; pointer-events: none;
  transition: opacity 0.3s ease;
}
.ob-backdrop.ob-open {
  opacity: 1; pointer-events: all;
}

/* ── Tour Card ── */
.ob-card {
  background: #ffffff;
  border-radius: var(--ob-radius);
  width: 100%;
  max-width: 480px;
  box-shadow: 0 32px 64px -12px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.08);
  overflow: hidden;
  transform: translateY(24px) scale(0.96);
  transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
}
.ob-backdrop.ob-open .ob-card {
  transform: none;
}

/* ── Tour Header ── */
.ob-header {
  padding: 2rem 2rem 1.25rem;
  position: relative;
}
.ob-step-dots {
  display: flex; gap: 6px; margin-bottom: 1.5rem;
}
.ob-dot {
  height: 4px; border-radius: 999px; background: #e2e8f0;
  transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
  flex: 1;
}
.ob-dot.ob-dot-active {
  background: var(--ob-accent, #16a34a);
  flex: 2;
}
.ob-dot.ob-dot-done {
  background: #bbf7d0;
}

/* ── Step icon ── */
.ob-icon-wrap {
  width: 64px; height: 64px; border-radius: 22px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.75rem; margin-bottom: 1.25rem;
  position: relative; overflow: hidden;
}
.ob-icon-wrap::after {
  content: '';
  position: absolute; inset: 0;
  background: rgba(255,255,255,0.15);
  border-radius: inherit;
}

/* ── Step text ── */
.ob-step-tag {
  font-size: 10px; font-weight: 800; letter-spacing: 0.12em;
  text-transform: uppercase; margin-bottom: 0.5rem;
  opacity: 0.65;
}
.ob-step-title {
  font-size: 1.35rem; font-weight: 800; color: #0f172a;
  letter-spacing: -0.4px; line-height: 1.25; margin: 0 0 0.75rem;
}
.ob-step-body {
  font-size: 0.9rem; line-height: 1.7; color: #475569;
  margin: 0;
}

/* ── Feature list ── */
.ob-features {
  display: flex; flex-direction: column; gap: 0.5rem;
  margin-top: 1rem;
}
.ob-feature-row {
  display: flex; align-items: center; gap: 10px;
  padding: 0.6rem 0.75rem; border-radius: 12px;
  background: #f8fafc; border: 1px solid #f1f5f9;
}
.ob-feature-icon {
  width: 30px; height: 30px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.75rem; flex-shrink: 0;
}
.ob-feature-text {
  font-size: 0.8rem; font-weight: 600; color: #334155;
}

/* ── Footer ── */
.ob-footer {
  padding: 1.25rem 2rem 2rem;
  display: flex; align-items: center; gap: 0.75rem;
  border-top: 1px solid #f1f5f9;
}
.ob-btn-skip {
  font-size: 0.8rem; font-weight: 700; color: #94a3b8;
  background: none; border: none; cursor: pointer;
  padding: 0.5rem; font-family: inherit;
  transition: color 0.15s;
}
.ob-btn-skip:hover { color: #64748b; }
.ob-btn-next {
  flex: 1; padding: 0.875rem 1.25rem;
  border: none; border-radius: 14px;
  font-size: 0.9rem; font-weight: 800;
  color: white; cursor: pointer;
  font-family: inherit;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: all 0.2s;
  background: var(--ob-accent, #16a34a);
  box-shadow: 0 4px 16px -4px color-mix(in srgb, var(--ob-accent, #16a34a) 60%, transparent);
}
.ob-btn-next:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 24px -4px color-mix(in srgb, var(--ob-accent, #16a34a) 50%, transparent);
}

/* ── Step transition ── */
.ob-step-panel { display: none; }
.ob-step-panel.ob-active {
  display: block;
  animation: obStepIn 0.3s ease both;
}
@keyframes obStepIn {
  from { opacity: 0; transform: translateX(16px); }
  to   { opacity: 1; transform: none; }
}

/* ════════════════════════════════════════════════════
   HELP MODAL
════════════════════════════════════════════════════ */
/* ── Floating Help Button ── */
.help-fab {
  position: fixed; bottom: 90px; right: 20px;
  z-index: 8000;
  width: 50px; height: 50px;
  border-radius: 50%;
  border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.15rem; color: white;
  box-shadow: 0 8px 24px -4px rgba(0,0,0,0.25);
  transition: all 0.2s cubic-bezier(0.34,1.56,0.64,1);
  background: var(--ob-accent, #16a34a);
  font-family: inherit;
}
.help-fab:hover {
  transform: scale(1.12) translateY(-2px);
  box-shadow: 0 16px 32px -6px rgba(0,0,0,0.3);
}
.help-fab .help-fab-label {
  position: absolute; right: 58px; top: 50%; transform: translateY(-50%);
  background: #1e293b; color: white;
  font-size: 0.7rem; font-weight: 700;
  padding: 0.3rem 0.65rem; border-radius: 8px; white-space: nowrap;
  opacity: 0; pointer-events: none;
  transition: opacity 0.2s;
}
.help-fab:hover .help-fab-label { opacity: 1; }

/* ── Help Backdrop ── */
.help-backdrop {
  position: fixed; inset: 0; z-index: 8500;
  background: rgba(10,15,30,0.65);
  backdrop-filter: blur(8px);
  display: flex; align-items: flex-end; justify-content: center;
  padding: 0;
  opacity: 0; pointer-events: none;
  transition: opacity 0.25s ease;
}
@media (min-width: 640px) {
  .help-backdrop { align-items: center; padding: 1.25rem; }
}
.help-backdrop.help-open {
  opacity: 1; pointer-events: all;
}

/* ── Help Modal ── */
.help-modal {
  background: #ffffff;
  width: 100%;
  max-width: 600px;
  border-radius: 28px 28px 0 0;
  max-height: 90dvh;
  overflow: hidden;
  display: flex; flex-direction: column;
  box-shadow: 0 -8px 40px rgba(0,0,0,0.25);
  transform: translateY(40px);
  transition: transform 0.35s cubic-bezier(0.34,1.3,0.64,1);
}
@media (min-width: 640px) {
  .help-modal {
    border-radius: 28px;
    box-shadow: 0 32px 64px -12px rgba(0,0,0,0.4);
    transform: scale(0.94) translateY(16px);
  }
}
.help-backdrop.help-open .help-modal {
  transform: none;
}

/* ── Help Header ── */
.help-header {
  flex-shrink: 0;
  padding: 1.25rem 1.5rem 0;
}
.help-drag { width: 36px; height: 4px; background: #e2e8f0; border-radius: 999px; margin: 0 auto 1.25rem; }
.help-header-inner {
  display: flex; align-items: center; justify-content: space-between; gap: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f1f5f9;
}
.help-close {
  width: 36px; height: 36px; border-radius: 12px;
  background: #f1f5f9; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #64748b; font-size: 0.9rem; transition: all 0.15s;
  flex-shrink: 0; font-family: inherit;
}
.help-close:hover { background: #fee2e2; color: #dc2626; }

/* ── Help Tabs ── */
.help-tabs {
  display: flex; gap: 0; padding: 0 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  flex-shrink: 0; overflow-x: auto;
}
.help-tabs::-webkit-scrollbar { display: none; }
.help-tab {
  padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 700;
  color: #94a3b8; background: none; border: none; cursor: pointer;
  border-bottom: 2px solid transparent; white-space: nowrap;
  transition: all 0.15s; font-family: inherit; margin-bottom: -1px;
}
.help-tab:hover { color: #475569; }
.help-tab.help-tab-active {
  color: var(--ob-accent, #16a34a);
  border-bottom-color: var(--ob-accent, #16a34a);
}

/* ── Help Body ── */
.help-body {
  flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem 1.5rem;
}
.help-body::-webkit-scrollbar { width: 4px; }
.help-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

.help-tab-panel { display: none; }
.help-tab-panel.help-tab-active {
  display: block;
  animation: obStepIn 0.25s ease both;
}

/* ── Help content components ── */
.help-section { margin-bottom: 1.5rem; }
.help-section:last-child { margin-bottom: 0; }
.help-section-title {
  font-size: 0.72rem; font-weight: 800; color: #94a3b8;
  letter-spacing: 0.1em; text-transform: uppercase;
  margin-bottom: 0.75rem;
}
.help-step-row {
  display: flex; gap: 12px; margin-bottom: 0.625rem;
  align-items: flex-start;
}
.help-step-num {
  width: 26px; height: 26px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.7rem; font-weight: 800; color: white;
  flex-shrink: 0; margin-top: 1px;
  background: var(--ob-accent, #16a34a);
}
.help-step-text {
  font-size: 0.85rem; color: #334155; line-height: 1.6; font-weight: 500;
}
.help-step-text strong { color: #0f172a; }

.help-tip {
  display: flex; gap: 10px; padding: 0.875rem 1rem;
  border-radius: 14px; margin-bottom: 0.625rem;
  border: 1px solid;
}
.help-tip-info    { background: #eff6ff; border-color: #bfdbfe; }
.help-tip-warn    { background: #fffbeb; border-color: #fde68a; }
.help-tip-success { background: #f0fdf4; border-color: #bbf7d0; }
.help-tip-icon { font-size: 0.85rem; flex-shrink: 0; margin-top: 1px; }
.help-tip-info    .help-tip-icon { color: #2563eb; }
.help-tip-warn    .help-tip-icon { color: #d97706; }
.help-tip-success .help-tip-icon { color: #16a34a; }
.help-tip-text { font-size: 0.82rem; line-height: 1.6; color: #334155; font-weight: 500; }

.help-faq-item {
  border: 1px solid #f1f5f9; border-radius: 14px;
  margin-bottom: 0.5rem; overflow: hidden;
}
.help-faq-q {
  width: 100%; display: flex; align-items: center; justify-content: space-between;
  gap: 0.75rem; padding: 0.875rem 1rem;
  background: none; border: none; cursor: pointer;
  text-align: left; font-family: inherit;
  font-size: 0.85rem; font-weight: 700; color: #1e293b;
  transition: background 0.15s;
}
.help-faq-q:hover { background: #f8fafc; }
.help-faq-q i { color: #94a3b8; font-size: 0.75rem; transition: transform 0.2s; flex-shrink: 0; }
.help-faq-item.open .help-faq-q i { transform: rotate(180deg); }
.help-faq-a {
  font-size: 0.82rem; color: #475569; line-height: 1.65; font-weight: 500;
  padding: 0 1rem 0.875rem; display: none;
}
.help-faq-item.open .help-faq-a { display: block; }

/* ── Re-run tour button ── */
.help-restart-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 0.875rem 1rem; border-radius: 14px;
  border: 1.5px dashed #e2e8f0; background: none; cursor: pointer;
  width: 100%; font-family: inherit;
  font-size: 0.85rem; font-weight: 700; color: #64748b;
  transition: all 0.15s; margin-top: 0.75rem;
}
.help-restart-btn:hover { border-color: var(--ob-accent, #16a34a); color: var(--ob-accent, #16a34a); background: #f0fdf4; }
</style>

<?php
// ─── Build steps based on role ────────────────────────────────────────────
$accentColor = match($currentRole) {
    'chairman' => '#2563eb',
    'sk'       => '#16a34a',
    default    => '#16a34a',
};
$roleLabel = match($currentRole) {
    'chairman' => 'Barangay Chairman',
    'sk'       => 'SK Officer',
    default    => 'Resident',
};
?>

<!-- ═══════════════════════════════════════════════════
     ONBOARDING TOUR MODAL
════════════════════════════════════════════════════ -->
<div id="ob-backdrop" class="ob-backdrop" style="--ob-accent: <?= $accentColor ?>">
  <div class="ob-card">

    <!-- Step dots -->
    <div class="ob-header">
      <div class="ob-step-dots" id="ob-dots"></div>

      <?php if ($currentRole === 'chairman'): ?>
      <!-- ── CHAIRMAN STEPS ── -->
      <div class="ob-step-panel ob-active" data-step="0">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">🏛️</div>
        <p class="ob-step-tag" style="color:#2563eb">Welcome, Chairman</p>
        <h2 class="ob-step-title">Hello, <?= htmlspecialchars($userName) ?>! 👋</h2>
        <p class="ob-step-body">Welcome to the SK E-Learning Resource Reservation System. As Barangay Chairman, you have full control over reservations, SK accounts, and resources. Let's take a quick tour!</p>
      </div>
      <div class="ob-step-panel" data-step="1">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#92400e,#d97706);">⏳</div>
        <p class="ob-step-tag" style="color:#d97706">Step 1</p>
        <h2 class="ob-step-title">Approve SK Accounts</h2>
        <p class="ob-step-body">When SK Officers register, they need your approval. Go to <strong>Manage SK</strong> in the sidebar to review and approve or reject applications. Approved officers will be notified by email.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-user-shield"></i></div><span class="ob-feature-text">Review SK registrations with full profile details</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#dcfce7;color:#166534;"><i class="fa-solid fa-envelope"></i></div><span class="ob-feature-text">Automatic email sent to SK upon your decision</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="2">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#166534,#16a34a);">📅</div>
        <p class="ob-step-tag" style="color:#16a34a">Step 2</p>
        <h2 class="ob-step-title">Manage Reservations</h2>
        <p class="ob-step-body">View and manage all reservations from the <strong>Reservations</strong> page. Approve pending requests, decline inappropriate ones, and track reservation history with full details.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-feature-text">One-click approve or decline with confirmation</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f3e8ff;color:#6b21a8;"><i class="fa-solid fa-qrcode"></i></div><span class="ob-feature-text">QR scanner validates e-tickets at the facility</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="3">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">🖥️</div>
        <p class="ob-step-tag" style="color:#2563eb">Step 3</p>
        <h2 class="ob-step-title">Manage PCs & Resources</h2>
        <p class="ob-step-body">Add, update, or remove PC workstations from <strong>Manage PCs</strong>. Set their status to Available, Maintenance, or Out of Order to keep the resource list accurate.</p>
      </div>
      <div class="ob-step-panel" data-step="4">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#0f172a,#334155);">✅</div>
        <p class="ob-step-tag" style="color:#64748b">All Set!</p>
        <h2 class="ob-step-title">You're all set, Chairman!</h2>
        <p class="ob-step-body">You now know the key features. The <strong>Help button</strong> (bottom right corner) is always available if you need a reminder. Explore the dashboard and start managing!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-circle-question"></i></div><span class="ob-feature-text">Tap the Help button anytime for a quick guide</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-chart-line"></i></div><span class="ob-feature-text">Track analytics and statistics from your dashboard</span></div>
        </div>
      </div>

      <?php elseif ($currentRole === 'sk'): ?>
      <!-- ── SK STEPS ── -->
      <div class="ob-step-panel ob-active" data-step="0">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#14532d,#16a34a);">🌿</div>
        <p class="ob-step-tag" style="color:#16a34a">Welcome, SK Officer</p>
        <h2 class="ob-step-title">Hello, <?= htmlspecialchars($userName) ?>! 👋</h2>
        <p class="ob-step-body">Welcome to the SK E-Learning Resource Reservation System! As an SK Officer, you can manage reservations, approve user requests, and operate the QR scanner at the facility. Let's get you started!</p>
      </div>
      <div class="ob-step-panel" data-step="1">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#92400e,#d97706);">📋</div>
        <p class="ob-step-tag" style="color:#d97706">Step 1</p>
        <h2 class="ob-step-title">Review User Requests</h2>
        <p class="ob-step-body">Residents submit reservation requests that need your approval. Go to <strong>User Requests</strong> to review pending requests and approve or decline them with a single click.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-clock"></i></div><span class="ob-feature-text">Yellow badge shows how many requests are waiting</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#dcfce7;color:#166534;"><i class="fa-solid fa-check"></i></div><span class="ob-feature-text">Approved residents get an e-ticket with QR code</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="2">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">📷</div>
        <p class="ob-step-tag" style="color:#2563eb">Step 2</p>
        <h2 class="ob-step-title">Scan E-Tickets</h2>
        <p class="ob-step-body">When a resident arrives at the facility with their approved reservation, use the <strong>Scanner</strong> to scan their QR code e-ticket. This marks them as claimed and logs their visit.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-camera"></i></div><span class="ob-feature-text">Works with printed and on-screen QR codes</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-print"></i></div><span class="ob-feature-text">Log printing activity after each session ends</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="3">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#0f172a,#334155);">✅</div>
        <p class="ob-step-tag" style="color:#64748b">All Set!</p>
        <h2 class="ob-step-title">You're ready to go!</h2>
        <p class="ob-step-body">You now know the essentials. Use the <strong>Help button</strong> in the bottom right corner anytime you need a refresher. The Active Sessions panel on your dashboard shows live time tracking!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-timer"></i></div><span class="ob-feature-text">Live session timer alerts you when sessions end</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fffbeb;color:#92400e;"><i class="fa-solid fa-book-open"></i></div><span class="ob-feature-text">Manage the library and book borrowing requests</span></div>
        </div>
      </div>

      <?php else: ?>
      <!-- ── RESIDENT STEPS ── -->
      <div class="ob-step-panel ob-active" data-step="0">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#14532d,#16a34a);">👋</div>
        <p class="ob-step-tag" style="color:#16a34a">Welcome!</p>
        <h2 class="ob-step-title">Hi, <?= htmlspecialchars($userName) ?>!</h2>
        <p class="ob-step-body">Welcome to the SK E-Learning Resource Reservation System of Brgy. F. De Jesus! You can reserve computers and other resources, borrow books, and track your schedule. Let's show you around!</p>
      </div>
      <div class="ob-step-panel" data-step="1">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">📅</div>
        <p class="ob-step-tag" style="color:#2563eb">Step 1</p>
        <h2 class="ob-step-title">Make a Reservation</h2>
        <p class="ob-step-body">Click <strong>New Reservation</strong> in the sidebar to book a resource like a computer or workstation. Choose your date, time, and purpose — then submit for approval!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-desktop"></i></div><span class="ob-feature-text">Choose from available PCs and resources</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-clock"></i></div><span class="ob-feature-text">Requests are reviewed by SK Officers or the Chairman</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="2">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#6b21a8,#a855f7);">🎫</div>
        <p class="ob-step-tag" style="color:#a855f7">Step 2</p>
        <h2 class="ob-step-title">Get Your E-Ticket</h2>
        <p class="ob-step-body">When your reservation is approved, you'll get a <strong>QR code E-Ticket</strong>. Show this to the SK Officer at the facility to claim your slot. You can download and save it to your phone!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f3e8ff;color:#6b21a8;"><i class="fa-solid fa-qrcode"></i></div><span class="ob-feature-text">Download your QR code ticket to your phone gallery</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-feature-text">Track all your reservations from My Reservations</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="3">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#92400e,#d97706);">📚</div>
        <p class="ob-step-tag" style="color:#d97706">Step 3</p>
        <h2 class="ob-step-title">Borrow Books</h2>
        <p class="ob-step-body">Visit the <strong>Library</strong> to browse and borrow books from the community collection. Use the AI Book Finder to get personalized suggestions based on what you want to read!</p>
      </div>
      <div class="ob-step-panel" data-step="4">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#0f172a,#334155);">✅</div>
        <p class="ob-step-tag" style="color:#64748b">All Set!</p>
        <h2 class="ob-step-title">You're all set!</h2>
        <p class="ob-step-body">That's everything you need to know! The <strong>Help button</strong> in the bottom right is always there if you need it. Enjoy the system and happy learning! 🎓</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-circle-question"></i></div><span class="ob-feature-text">Tap Help anytime for guides and FAQ</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-bell"></i></div><span class="ob-feature-text">Notifications alert you when reservations are approved</span></div>
        </div>
      </div>
      <?php endif; ?>
    </div><!-- /ob-header -->

    <!-- Footer -->
    <div class="ob-footer">
      <button class="ob-btn-skip" onclick="obClose()">Skip tour</button>
      <button class="ob-btn-next" id="ob-next-btn" onclick="obNext()" style="--ob-accent: <?= $accentColor ?>">
        <span id="ob-next-label">Get Started</span>
        <i class="fa-solid fa-arrow-right" id="ob-next-icon"></i>
      </button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     FLOATING HELP BUTTON
════════════════════════════════════════════════════ -->
<button class="help-fab" onclick="helpOpen()" style="--ob-accent: <?= $accentColor ?>" aria-label="Help">
  <i class="fa-solid fa-circle-question"></i>
  <span class="help-fab-label">Help & Guide</span>
</button>

<!-- ═══════════════════════════════════════════════════
     HELP MODAL
════════════════════════════════════════════════════ -->
<div id="help-backdrop" class="help-backdrop" style="--ob-accent: <?= $accentColor ?>">
  <div class="help-modal">

    <div class="help-header">
      <div class="help-drag sm:hidden"></div>
      <div class="help-header-inner">
        <div class="flex items-center gap-3">
          <div style="width:38px;height:38px;border-radius:14px;background:<?= $accentColor ?>22;display:flex;align-items:center;justify-content:center;color:<?= $accentColor ?>;font-size:1rem;">
            <i class="fa-solid fa-circle-question"></i>
          </div>
          <div>
            <p style="font-size:1rem;font-weight:800;color:#0f172a;margin:0;">Help & Guide</p>
            <p style="font-size:0.72rem;color:#94a3b8;margin:0;font-weight:500;">SK E-Learning Reservation System</p>
          </div>
        </div>
        <button class="help-close" onclick="helpClose()"><i class="fa-solid fa-xmark"></i></button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="help-tabs">
      <?php if ($currentRole === 'chairman'): ?>
        <button class="help-tab help-tab-active" onclick="helpTab(this,'tab-overview')">Overview</button>
        <button class="help-tab" onclick="helpTab(this,'tab-reservations')">Reservations</button>
        <button class="help-tab" onclick="helpTab(this,'tab-sk')">SK Accounts</button>
        <button class="help-tab" onclick="helpTab(this,'tab-faq')">FAQ</button>
      <?php elseif ($currentRole === 'sk'): ?>
        <button class="help-tab help-tab-active" onclick="helpTab(this,'tab-overview')">Overview</button>
        <button class="help-tab" onclick="helpTab(this,'tab-approve')">Approvals</button>
        <button class="help-tab" onclick="helpTab(this,'tab-scanner')">Scanner</button>
        <button class="help-tab" onclick="helpTab(this,'tab-faq')">FAQ</button>
      <?php else: ?>
        <button class="help-tab help-tab-active" onclick="helpTab(this,'tab-overview')">Overview</button>
        <button class="help-tab" onclick="helpTab(this,'tab-reserve')">Reservations</button>
        <button class="help-tab" onclick="helpTab(this,'tab-library')">Library</button>
        <button class="help-tab" onclick="helpTab(this,'tab-faq')">FAQ</button>
      <?php endif; ?>
    </div>

    <!-- Body -->
    <div class="help-body">

      <!-- ── OVERVIEW TAB (all roles) ── -->
      <div id="tab-overview" class="help-tab-panel help-tab-active">
        <div class="help-section">
          <p class="help-section-title">Your Role — <?= $roleLabel ?></p>
          <?php if ($currentRole === 'chairman'): ?>
            <div class="help-tip help-tip-info"><i class="fa-solid fa-crown help-tip-icon"></i><p class="help-tip-text">As Barangay Chairman, you have <strong>full access</strong> to the entire system — reservations, SK accounts, PCs, library, logs, and analytics.</p></div>
            <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Manage SK</strong> to approve or reject SK Officer applications.</p></div>
            <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Go to <strong>Reservations</strong> to review and approve resident and SK reservation requests.</p></div>
            <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Go to <strong>Manage PCs</strong> to add workstations and update their status.</p></div>
            <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Use <strong>Login Logs</strong> and <strong>Activity Logs</strong> to monitor all system activity.</p></div>
          <?php elseif ($currentRole === 'sk'): ?>
            <div class="help-tip help-tip-success"><i class="fa-solid fa-user-shield help-tip-icon"></i><p class="help-tip-text">As an SK Officer, you can <strong>approve reservations</strong>, scan QR tickets, manage the library, and log printing activity.</p></div>
            <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Check <strong>User Requests</strong> daily for pending reservation approvals.</p></div>
            <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Use the <strong>Scanner</strong> to validate e-tickets when residents arrive.</p></div>
            <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Monitor the <strong>Active Sessions</strong> panel on your dashboard for live time tracking.</p></div>
            <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Manage the <strong>Library</strong> — add books, approve borrowing requests, and record returns.</p></div>
          <?php else: ?>
            <div class="help-tip help-tip-success"><i class="fa-solid fa-user help-tip-icon"></i><p class="help-tip-text">As a Resident, you can <strong>request reservations</strong>, borrow books, and track your schedule from the dashboard.</p></div>
            <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Click <strong>New Reservation</strong> to book a computer or other resource.</p></div>
            <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Check <strong>My Reservations</strong> to see the status of your requests.</p></div>
            <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">When approved, show your <strong>QR Code E-Ticket</strong> at the facility to claim your slot.</p></div>
            <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Browse the <strong>Library</strong> and request to borrow books from the collection.</p></div>
          <?php endif; ?>
        </div>

        <button class="help-restart-btn" onclick="helpClose(); obRestart();">
          <i class="fa-solid fa-rotate-left" style="color:<?= $accentColor ?>"></i>
          Replay the onboarding tour
        </button>
      </div>

      <?php if ($currentRole === 'chairman'): ?>
      <!-- ── RESERVATIONS TAB (chairman) ── -->
      <div id="tab-reservations" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Approving Reservations</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Manage Reservations</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Filter by <strong>Pending</strong> to see requests waiting for approval.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click a reservation row to open the detail panel and review.</p></div>
          <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Click <strong>Approve</strong> to confirm or <strong>Decline</strong> to reject.</p></div>
          <div class="help-tip help-tip-info"><i class="fa-solid fa-ticket help-tip-icon"></i><p class="help-tip-text">Residents receive a QR code e-ticket automatically once you approve their reservation.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">QR Scanner</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Scanner</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Allow camera access when prompted by your browser.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Point the camera at the resident's QR code to mark them as <strong>Claimed</strong>.</p></div>
        </div>
      </div>

      <!-- ── SK ACCOUNTS TAB (chairman) ── -->
      <div id="tab-sk" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Approving SK Officers</p>
          <div class="help-tip help-tip-warn"><i class="fa-solid fa-triangle-exclamation help-tip-icon"></i><p class="help-tip-text">SK Officers can only log in after you approve their account. They are notified by email when you make a decision.</p></div>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Manage SK</strong> — a badge shows pending approvals.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Click <strong>View</strong> on an SK account to see their full details.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click <strong>Approve</strong> to grant access or <strong>Reject</strong> to deny.</p></div>
          <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">The SK Officer receives an automatic email with your decision.</p></div>
        </div>
      </div>

      <?php elseif ($currentRole === 'sk'): ?>
      <!-- ── APPROVALS TAB (sk) ── -->
      <div id="tab-approve" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Approving User Requests</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>User Requests</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Review each request — check the date, time, and resource.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click <strong>Approve</strong> to confirm or <strong>Decline</strong> to reject the request.</p></div>
          <div class="help-tip help-tip-success"><i class="fa-solid fa-bell help-tip-icon"></i><p class="help-tip-text">The yellow badge on the sidebar shows how many requests need your attention.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">Logging Print Activity</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Open any reservation from the <strong>Reservations</strong> page.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Scroll to the <strong>Log Print</strong> section at the bottom.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Enter the number of pages printed (0 = no printing) and click <strong>Save</strong>.</p></div>
        </div>
      </div>

      <!-- ── SCANNER TAB (sk) ── -->
      <div id="tab-scanner" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Using the QR Scanner</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Scanner</strong> from the sidebar or mobile nav.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Click <strong>Allow</strong> when your browser asks for camera permission.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Ask the resident to show their QR code e-ticket on their phone or on paper.</p></div>
          <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Point the camera at the QR code — it validates automatically.</p></div>
          <div class="help-tip help-tip-info"><i class="fa-solid fa-lightbulb help-tip-icon"></i><p class="help-tip-text">For best results, ensure good lighting. The scanner works with both printed and on-screen QR codes.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">Active Sessions Panel</p>
          <p style="font-size:0.85rem;color:#475569;line-height:1.65;margin:0 0 0.5rem;">The top panel on your dashboard shows live countdowns for all approved reservations happening today. Color codes:</p>
          <div class="help-step-row"><div class="help-step-num" style="background:#10b981;">✓</div><p class="help-step-text"><strong>Green</strong> — More than 5 minutes remaining</p></div>
          <div class="help-step-row"><div class="help-step-num" style="background:#f59e0b;">!</div><p class="help-step-text"><strong>Amber</strong> — 5 minutes or less remaining</p></div>
          <div class="help-step-row"><div class="help-step-num" style="background:#ef4444;">!!</div><p class="help-step-text"><strong>Red</strong> — 2 minutes or less, session ending critically soon</p></div>
        </div>
      </div>

      <?php else: ?>
      <!-- ── RESERVATIONS TAB (resident) ── -->
      <div id="tab-reserve" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Making a Reservation</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Click <strong>New Reservation</strong> in the sidebar or tap the green Reserve button.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Select the <strong>Resource</strong> you need (e.g., Computer Lab, WiFi).</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Choose your <strong>Date</strong>, <strong>Start Time</strong>, and <strong>End Time</strong>.</p></div>
          <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Enter your <strong>Purpose</strong> and submit the request.</p></div>
          <div class="help-step-row"><div class="help-step-num">5</div><p class="help-step-text">Wait for SK Officer or Chairman approval — check <strong>My Reservations</strong> for updates.</p></div>
          <div class="help-tip help-tip-warn"><i class="fa-solid fa-triangle-exclamation help-tip-icon"></i><p class="help-tip-text">You have a <strong>monthly quota of 3 reservations</strong>. Used slots reset at the start of each month.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">Using Your E-Ticket</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">When approved, go to <strong>My Reservations</strong> and click your reservation.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Your <strong>QR Code E-Ticket</strong> will appear — tap <strong>Download E-Ticket</strong> to save it.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Show the QR code to the SK Officer at the facility to claim your session.</p></div>
          <div class="help-tip help-tip-info"><i class="fa-solid fa-mobile-alt help-tip-icon"></i><p class="help-tip-text">Save your e-ticket to your phone gallery before your reservation date — you can access it without internet!</p></div>
        </div>
      </div>

      <!-- ── LIBRARY TAB (resident) ── -->
      <div id="tab-library" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Borrowing a Book</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Library</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Browse available books — a <strong>green dot</strong> means copies are available.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click <strong>Borrow</strong> on the book you want.</p></div>
          <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Wait for SK Officer approval — check <strong>My Borrowings</strong> for status.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">AI Book Finder</p>
          <div class="help-tip help-tip-success"><i class="fa-solid fa-wand-magic-sparkles help-tip-icon"></i><p class="help-tip-text">Use the <strong>AI Book Finder</strong> on the dashboard to describe what you want to read and get personalized recommendations from the library collection!</p></div>
        </div>
      </div>
      <?php endif; ?>

      <!-- ── FAQ TAB (all roles) ── -->
      <div id="tab-faq" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Frequently Asked Questions</p>

          <?php if ($currentRole !== 'chairman'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              I didn't receive a verification email. <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">Check your Spam or Junk folder. If still not found, try registering again or contact the Barangay office. Make sure you used a valid email address.</p>
          </div>
          <?php endif; ?>

          <?php if ($currentRole === 'sk'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              My account says Pending after email verification. <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">This is normal for SK Officers. The Barangay Chairman must approve your SK account before you can log in. You'll receive an email notification once a decision is made.</p>
          </div>
          <?php endif; ?>

          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              The page shows a 404 error after the site wakes up. <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">The server may have been inactive. Refresh the page and go back to the login page manually at <strong>reservation-k2eg.onrender.com</strong>.</p>
          </div>

          <?php if ($currentRole === 'user'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              My reservation is stuck on Pending for a long time. <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">Contact an SK Officer or the Barangay Chairman directly to review your reservation request.</p>
          </div>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              Can I cancel a reservation? <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">Only Pending reservations can be cancelled. Go to My Reservations and click Cancel. Approved reservations cannot be cancelled through the system — contact the Barangay office.</p>
          </div>
          <?php endif; ?>

          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              I forgot my password. <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">Click the <strong>Forgot Password?</strong> link on the login page. Enter your email to receive a 6-digit verification code, then set a new password.</p>
          </div>

          <?php if ($currentRole === 'sk'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              The QR scanner is not working. <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">Make sure you've allowed camera access in your browser settings. Ensure good lighting and hold the QR code steady. The scanner works best in Chrome or Safari.</p>
          </div>
          <?php endif; ?>

          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">
              How do I install this as an app on my phone? <i class="fa-solid fa-chevron-down"></i>
            </button>
            <p class="help-faq-a">On Android (Chrome): tap the 3-dot menu → Add to Home Screen → Install. On iPhone (Safari): tap the Share button → Add to Home Screen → Add.</p>
          </div>
        </div>
      </div>

    </div><!-- /help-body -->
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════════════════ -->
<script>
(function() {
  /* ── Config ── */
  const ROLE         = '<?= $currentRole ?>';
  const OB_KEY       = 'ob_done_' + ROLE + '_v2';
  const TOTAL_STEPS  = document.querySelectorAll('.ob-step-panel').length;
  let   obStep       = 0;

  /* ── Build dots ── */
  const dotsEl = document.getElementById('ob-dots');
  for (let i = 0; i < TOTAL_STEPS; i++) {
    const d = document.createElement('div');
    d.className = 'ob-dot' + (i === 0 ? ' ob-dot-active' : '');
    d.id = 'ob-dot-' + i;
    dotsEl.appendChild(d);
  }

  /* ── Show tour if not done ── */
  function obShouldShow() {
    return !localStorage.getItem(OB_KEY);
  }

  function obOpen() {
    document.getElementById('ob-backdrop').classList.add('ob-open');
    document.body.style.overflow = 'hidden';
  }

  window.obClose = function() {
    localStorage.setItem(OB_KEY, '1');
    document.getElementById('ob-backdrop').classList.remove('ob-open');
    document.body.style.overflow = '';
  };

  window.obRestart = function() {
    obStep = 0;
    localStorage.removeItem(OB_KEY);
    obShowStep(0);
    setTimeout(obOpen, 100);
  };

  window.obNext = function() {
    if (obStep < TOTAL_STEPS - 1) {
      obStep++;
      obShowStep(obStep);
    } else {
      obClose();
    }
  };

  function obShowStep(n) {
    document.querySelectorAll('.ob-step-panel').forEach((p, i) => {
      p.classList.toggle('ob-active', i === n);
    });
    document.querySelectorAll('.ob-dot').forEach((d, i) => {
      d.classList.remove('ob-dot-active', 'ob-dot-done');
      if (i < n)  d.classList.add('ob-dot-done');
      if (i === n) d.classList.add('ob-dot-active');
    });
    const isLast = n === TOTAL_STEPS - 1;
    document.getElementById('ob-next-label').textContent = isLast ? 'Start Exploring' : (n === 0 ? 'Show Me Around' : 'Next');
    document.getElementById('ob-next-icon').className = isLast ? 'fa-solid fa-rocket' : 'fa-solid fa-arrow-right';
  }

  /* ── Auto-show on first visit ── */
  if (obShouldShow()) {
    setTimeout(obOpen, 800);
  }

  /* ════════════════════════════════════════════════
     HELP MODAL
  ════════════════════════════════════════════════ */
  window.helpOpen = function() {
    document.getElementById('help-backdrop').classList.add('help-open');
    document.body.style.overflow = 'hidden';
  };

  window.helpClose = function() {
    document.getElementById('help-backdrop').classList.remove('help-open');
    document.body.style.overflow = '';
  };

  window.helpTab = function(btn, tabId) {
    document.querySelectorAll('.help-tab').forEach(t => t.classList.remove('help-tab-active'));
    document.querySelectorAll('.help-tab-panel').forEach(p => p.classList.remove('help-tab-active'));
    btn.classList.add('help-tab-active');
    document.getElementById(tabId).classList.add('help-tab-active');
  };

  window.helpFaq = function(btn) {
    const item = btn.closest('.help-faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.help-faq-item').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  };

  /* Close on backdrop click */
  document.getElementById('help-backdrop').addEventListener('click', function(e) {
    if (e.target === this) helpClose();
  });

  /* Close on ESC */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      helpClose();
      obClose();
    }
  });
})();
</script>