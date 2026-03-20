<?php
/**
 * Onboarding Tour + Help Modal
 * Include this file in any dashboard view before </body>
 *
 * Usage:
 *   <?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
 */
$currentRole = $role ?? session()->get('role') ?? 'user';
$userName    = session()->get('name') ?? 'there';
?>

<style>
:root {
  --ob-green:  #16a34a;
  --ob-blue:   #2563eb;
  --ob-amber:  #d97706;
  --ob-radius: 32px;
}

/* ── Backdrop ── */
.ob-backdrop {
  position: fixed; inset: 0; z-index: 9000;
  background: rgba(5, 10, 20, 0.80);
  backdrop-filter: blur(12px);
  display: flex; align-items: center; justify-content: center;
  padding: 1rem;
  opacity: 0; pointer-events: none;
  transition: opacity 0.4s ease;
}
.ob-backdrop.ob-open { opacity: 1; pointer-events: all; }

/* ── Tour Card ── */
.ob-card {
  background: #ffffff;
  border-radius: var(--ob-radius);
  width: 100%;
  max-width: 500px;
  box-shadow:
    0 40px 80px -20px rgba(0,0,0,0.5),
    0 0 0 1px rgba(255,255,255,0.06),
    inset 0 1px 0 rgba(255,255,255,0.9);
  overflow: hidden;
  transform: translateY(32px) scale(0.94);
  transition: transform 0.45s cubic-bezier(0.34,1.4,0.64,1);
}
.ob-backdrop.ob-open .ob-card { transform: none; }

/* ── Progress Bar ── */
.ob-progress-track {
  height: 3px;
  background: #f1f5f9;
  position: relative;
  overflow: hidden;
}
.ob-progress-fill {
  height: 100%;
  border-radius: 999px;
  background: var(--ob-accent, #16a34a);
  transition: width 0.5s cubic-bezier(0.34,1.2,0.64,1);
}

/* ── Header ── */
.ob-header {
  padding: 2rem 2rem 1.5rem;
  position: relative;
}

/* ── Step dots ── */
.ob-step-dots {
  display: flex; gap: 5px; margin-bottom: 1.75rem; justify-content: center;
}
.ob-dot {
  height: 6px; border-radius: 999px; background: #e2e8f0;
  transition: all 0.35s cubic-bezier(0.34,1.4,0.64,1);
  width: 6px;
}
.ob-dot.ob-dot-active { background: var(--ob-accent, #16a34a); width: 24px; }
.ob-dot.ob-dot-done   { background: color-mix(in srgb, var(--ob-accent, #16a34a) 35%, white); width: 10px; }

/* ── Icon ── */
.ob-icon-wrap {
  width: 72px; height: 72px; border-radius: 24px;
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; margin-bottom: 1.5rem;
  position: relative;
  box-shadow: 0 8px 24px -6px rgba(0,0,0,0.2);
}
.ob-icon-glow {
  position: absolute; inset: -8px; border-radius: 32px;
  opacity: 0.12; blur: 12px;
  background: var(--ob-accent, #16a34a);
  filter: blur(12px);
}

/* ── Step text ── */
.ob-step-tag {
  font-size: 10px; font-weight: 900; letter-spacing: 0.14em;
  text-transform: uppercase; margin-bottom: 0.5rem;
  display: flex; align-items: center; gap: 6px;
}
.ob-step-tag::before {
  content: '';
  display: inline-block;
  width: 20px; height: 2px; border-radius: 999px;
  background: currentColor; opacity: 0.5;
}
.ob-step-title {
  font-size: 1.5rem; font-weight: 900; color: #0f172a;
  letter-spacing: -0.5px; line-height: 1.2; margin: 0 0 0.875rem;
}
.ob-step-body {
  font-size: 0.88rem; line-height: 1.75; color: #64748b;
  margin: 0; font-weight: 500;
}
.ob-step-body strong { color: #1e293b; font-weight: 700; }

/* ── Feature list ── */
.ob-features {
  display: flex; flex-direction: column; gap: 0.5rem;
  margin-top: 1.25rem;
}
.ob-feature-row {
  display: flex; align-items: center; gap: 12px;
  padding: 0.75rem 1rem; border-radius: 16px;
  background: #f8fafc; border: 1px solid #f1f5f9;
  transition: all 0.2s;
}
.ob-feature-row:hover { background: #f0fdf4; border-color: #bbf7d0; }
.ob-feature-icon {
  width: 34px; height: 34px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.8rem; flex-shrink: 0;
}
.ob-feature-text { font-size: 0.82rem; font-weight: 600; color: #334155; }

/* ── Footer ── */
.ob-footer {
  padding: 1.25rem 2rem 2rem;
  display: flex; align-items: center; gap: 0.75rem;
  background: #fafafa;
  border-top: 1px solid #f1f5f9;
}
.ob-btn-skip {
  font-size: 0.78rem; font-weight: 700; color: #94a3b8;
  background: none; border: none; cursor: pointer;
  padding: 0.625rem 0.75rem; font-family: inherit;
  transition: color 0.15s; border-radius: 10px;
  white-space: nowrap;
}
.ob-btn-skip:hover { color: #64748b; background: #f1f5f9; }
.ob-btn-next {
  flex: 1; padding: 0.95rem 1.5rem;
  border: none; border-radius: 16px;
  font-size: 0.92rem; font-weight: 800;
  color: white; cursor: pointer;
  font-family: inherit;
  display: flex; align-items: center; justify-content: center; gap: 10px;
  transition: all 0.25s cubic-bezier(0.34,1.2,0.64,1);
  background: var(--ob-accent, #16a34a);
  box-shadow: 0 6px 20px -4px color-mix(in srgb, var(--ob-accent, #16a34a) 55%, transparent);
  position: relative; overflow: hidden;
}
.ob-btn-next::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
}
.ob-btn-next:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 32px -6px color-mix(in srgb, var(--ob-accent, #16a34a) 55%, transparent);
}
.ob-btn-next:active { transform: translateY(0); }

/* ── Step transition ── */
.ob-step-panel { display: none; }
.ob-step-panel.ob-active { display: block; animation: obStepIn 0.35s ease both; }
@keyframes obStepIn {
  from { opacity: 0; transform: translateX(20px); }
  to   { opacity: 1; transform: none; }
}

/* ── Step counter ── */
.ob-step-counter {
  font-size: 0.68rem; font-weight: 800; color: #94a3b8;
  text-transform: uppercase; letter-spacing: 0.1em;
  text-align: right; margin-bottom: 1rem;
}

/* ════════════════════════════════════════════════
   HELP MODAL
════════════════════════════════════════════════ */
.help-fab {
  position: fixed; bottom: 90px; right: 20px;
  z-index: 8000;
  width: 52px; height: 52px;
  border-radius: 50%;
  border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; color: white;
  box-shadow: 0 8px 28px -4px rgba(0,0,0,0.28);
  transition: all 0.25s cubic-bezier(0.34,1.4,0.64,1);
  background: var(--ob-accent, #16a34a);
  font-family: inherit;
}
.help-fab:hover {
  transform: scale(1.15) translateY(-3px);
  box-shadow: 0 16px 40px -6px rgba(0,0,0,0.3);
}
.help-fab .help-fab-label {
  position: absolute; right: 62px; top: 50%; transform: translateY(-50%);
  background: #1e293b; color: white;
  font-size: 0.7rem; font-weight: 700;
  padding: 0.35rem 0.75rem; border-radius: 10px; white-space: nowrap;
  opacity: 0; pointer-events: none;
  transition: opacity 0.2s;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.help-fab:hover .help-fab-label { opacity: 1; }

.help-backdrop {
  position: fixed; inset: 0; z-index: 8500;
  background: rgba(5,10,20,0.72);
  backdrop-filter: blur(10px);
  display: flex; align-items: flex-end; justify-content: center;
  padding: 0;
  opacity: 0; pointer-events: none;
  transition: opacity 0.3s ease;
}
@media (min-width: 640px) {
  .help-backdrop { align-items: center; padding: 1.25rem; }
}
.help-backdrop.help-open { opacity: 1; pointer-events: all; }

.help-modal {
  background: #ffffff;
  width: 100%;
  max-width: 620px;
  border-radius: 32px 32px 0 0;
  max-height: 90dvh;
  overflow: hidden;
  display: flex; flex-direction: column;
  box-shadow: 0 -12px 48px rgba(0,0,0,0.2);
  transform: translateY(48px);
  transition: transform 0.4s cubic-bezier(0.34,1.2,0.64,1);
}
@media (min-width: 640px) {
  .help-modal { border-radius: 32px; box-shadow: 0 40px 80px -20px rgba(0,0,0,0.4); transform: scale(0.93) translateY(20px); }
}
.help-backdrop.help-open .help-modal { transform: none; }

.help-header { flex-shrink: 0; padding: 1.25rem 1.5rem 0; }
.help-drag { width: 40px; height: 4px; background: #e2e8f0; border-radius: 999px; margin: 0 auto 1.25rem; }
.help-header-inner {
  display: flex; align-items: center; justify-content: space-between; gap: 1rem;
  padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;
}
.help-close {
  width: 38px; height: 38px; border-radius: 14px;
  background: #f1f5f9; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #64748b; font-size: 0.9rem; transition: all 0.15s;
  flex-shrink: 0; font-family: inherit;
}
.help-close:hover { background: #fee2e2; color: #dc2626; }

.help-tabs {
  display: flex; gap: 0; padding: 0 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  flex-shrink: 0; overflow-x: auto;
}
.help-tabs::-webkit-scrollbar { display: none; }
.help-tab {
  padding: 0.875rem 1rem; font-size: 0.78rem; font-weight: 700;
  color: #94a3b8; background: none; border: none; cursor: pointer;
  border-bottom: 2.5px solid transparent; white-space: nowrap;
  transition: all 0.15s; font-family: inherit; margin-bottom: -1px;
}
.help-tab:hover { color: #475569; }
.help-tab.help-tab-active {
  color: var(--ob-accent, #16a34a);
  border-bottom-color: var(--ob-accent, #16a34a);
}

.help-body { flex: 1; overflow-y: auto; padding: 1.5rem; }
.help-body::-webkit-scrollbar { width: 4px; }
.help-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

.help-tab-panel { display: none; }
.help-tab-panel.help-tab-active { display: block; animation: obStepIn 0.25s ease both; }

.help-section { margin-bottom: 1.75rem; }
.help-section:last-child { margin-bottom: 0; }
.help-section-title {
  font-size: 0.68rem; font-weight: 900; color: #94a3b8;
  letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 0.875rem;
  display: flex; align-items: center; gap: 8px;
}
.help-section-title::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

.help-step-row { display: flex; gap: 12px; margin-bottom: 0.75rem; align-items: flex-start; }
.help-step-num {
  width: 28px; height: 28px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.7rem; font-weight: 900; color: white;
  flex-shrink: 0; margin-top: 1px;
  background: var(--ob-accent, #16a34a);
}
.help-step-text { font-size: 0.85rem; color: #334155; line-height: 1.65; font-weight: 500; }
.help-step-text strong { color: #0f172a; font-weight: 700; }

.help-tip {
  display: flex; gap: 12px; padding: 1rem 1.1rem;
  border-radius: 16px; margin-bottom: 0.75rem; border: 1px solid;
}
.help-tip-info    { background: #eff6ff; border-color: #bfdbfe; }
.help-tip-warn    { background: #fffbeb; border-color: #fde68a; }
.help-tip-success { background: #f0fdf4; border-color: #bbf7d0; }
.help-tip-icon { font-size: 0.9rem; flex-shrink: 0; margin-top: 2px; }
.help-tip-info    .help-tip-icon { color: #2563eb; }
.help-tip-warn    .help-tip-icon { color: #d97706; }
.help-tip-success .help-tip-icon { color: #16a34a; }
.help-tip-text { font-size: 0.83rem; line-height: 1.65; color: #334155; font-weight: 500; }

.help-faq-item {
  border: 1px solid #f1f5f9; border-radius: 16px;
  margin-bottom: 0.625rem; overflow: hidden; transition: border-color 0.2s;
}
.help-faq-item.open { border-color: color-mix(in srgb, var(--ob-accent, #16a34a) 30%, white); }
.help-faq-q {
  width: 100%; display: flex; align-items: center; justify-content: space-between;
  gap: 0.75rem; padding: 1rem 1.1rem;
  background: none; border: none; cursor: pointer;
  text-align: left; font-family: inherit;
  font-size: 0.85rem; font-weight: 700; color: #1e293b;
  transition: background 0.15s;
}
.help-faq-q:hover { background: #f8fafc; }
.help-faq-q i { color: #94a3b8; font-size: 0.75rem; transition: transform 0.25s; flex-shrink: 0; }
.help-faq-item.open .help-faq-q i { transform: rotate(180deg); color: var(--ob-accent, #16a34a); }
.help-faq-a {
  font-size: 0.83rem; color: #475569; line-height: 1.7; font-weight: 500;
  padding: 0 1.1rem 1rem; display: none;
}
.help-faq-item.open .help-faq-a { display: block; animation: obStepIn 0.2s ease both; }

.help-restart-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 1rem 1.1rem; border-radius: 16px;
  border: 1.5px dashed #e2e8f0; background: none; cursor: pointer;
  width: 100%; font-family: inherit;
  font-size: 0.85rem; font-weight: 700; color: #64748b;
  transition: all 0.2s; margin-top: 0.875rem;
}
.help-restart-btn:hover {
  border-color: var(--ob-accent, #16a34a);
  color: var(--ob-accent, #16a34a);
  background: color-mix(in srgb, var(--ob-accent, #16a34a) 6%, white);
}

/* ── Role badge in help header ── */
.help-role-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 0.2rem 0.6rem; border-radius: 999px;
  font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em;
  background: color-mix(in srgb, var(--ob-accent, #16a34a) 12%, white);
  color: var(--ob-accent, #16a34a);
  border: 1px solid color-mix(in srgb, var(--ob-accent, #16a34a) 25%, white);
}
</style>

<?php
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
$roleIcon = match($currentRole) {
    'chairman' => 'fa-crown',
    'sk'       => 'fa-user-shield',
    default    => 'fa-user',
};
?>

<!-- ═══════════════════════════════════
     ONBOARDING TOUR
════════════════════════════════════ -->
<div id="ob-backdrop" class="ob-backdrop" style="--ob-accent: <?= $accentColor ?>">
  <div class="ob-card">

    <!-- Progress bar -->
    <div class="ob-progress-track">
      <div class="ob-progress-fill" id="ob-progress" style="width:0%"></div>
    </div>

    <div class="ob-header">
      <!-- Step counter -->
      <div class="ob-step-counter" id="ob-counter">Step 1</div>

      <!-- Dots -->
      <div class="ob-step-dots" id="ob-dots"></div>

      <?php if ($currentRole === 'chairman'): ?>
      <!-- ── CHAIRMAN STEPS ── -->
      <div class="ob-step-panel ob-active" data-step="0">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">
          <div class="ob-icon-glow"></div>🏛️
        </div>
        <p class="ob-step-tag" style="color:#2563eb">Welcome, Chairman</p>
        <h2 class="ob-step-title">Hello, <?= htmlspecialchars($userName) ?>! 👋</h2>
        <p class="ob-step-body">Welcome to the <strong>SK E-Learning Resource Reservation System</strong>. As Barangay Chairman, you have full control over everything. Let's take a quick tour!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-chart-line"></i></div><span class="ob-feature-text">Full analytics dashboard with live session monitoring</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-user-shield"></i></div><span class="ob-feature-text">Approve SK officers and manage system access</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="1">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#92400e,#d97706);">
          <div class="ob-icon-glow"></div>⏳
        </div>
        <p class="ob-step-tag" style="color:#d97706">Step 1</p>
        <h2 class="ob-step-title">Approve SK Accounts</h2>
        <p class="ob-step-body">When SK Officers register, they need your approval first. Go to <strong>Manage SK</strong> to review, approve, or reject applications.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-user-shield"></i></div><span class="ob-feature-text">Review SK registrations with full profile details</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#dcfce7;color:#166534;"><i class="fa-solid fa-envelope"></i></div><span class="ob-feature-text">Automatic email sent to SK upon your decision</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="2">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#166534,#16a34a);">
          <div class="ob-icon-glow"></div>📅
        </div>
        <p class="ob-step-tag" style="color:#16a34a">Step 2</p>
        <h2 class="ob-step-title">Manage Reservations</h2>
        <p class="ob-step-body">View and manage all reservations. Approve pending requests, decline inappropriate ones, and track history with full details.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-feature-text">One-click approve or decline with instant notification</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f3e8ff;color:#6b21a8;"><i class="fa-solid fa-qrcode"></i></div><span class="ob-feature-text">QR scanner validates e-tickets at the facility</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="3">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">
          <div class="ob-icon-glow"></div>🖥️
        </div>
        <p class="ob-step-tag" style="color:#2563eb">Step 3</p>
        <h2 class="ob-step-title">Manage PCs & Resources</h2>
        <p class="ob-step-body">Add, update, or remove PC workstations from <strong>Manage PCs</strong>. Set their status to Available, Maintenance, or Out of Order.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-desktop"></i></div><span class="ob-feature-text">Add unlimited workstations and resources</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fff7ed;color:#ea580c;"><i class="fa-solid fa-wrench"></i></div><span class="ob-feature-text">Mark PCs as under maintenance anytime</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="4">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#0f172a,#334155);">
          <div class="ob-icon-glow" style="background:#94a3b8;"></div>🚀
        </div>
        <p class="ob-step-tag" style="color:#64748b">All Set!</p>
        <h2 class="ob-step-title">You're ready, Chairman!</h2>
        <p class="ob-step-body">You now know the key features. The <strong>Help button</strong> (bottom right) is always available when you need a reminder.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-circle-question"></i></div><span class="ob-feature-text">Tap Help anytime for guides, tips, and FAQ</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-chart-line"></i></div><span class="ob-feature-text">Track analytics and statistics from your dashboard</span></div>
        </div>
      </div>

      <?php elseif ($currentRole === 'sk'): ?>
      <!-- ── SK STEPS ── -->
      <div class="ob-step-panel ob-active" data-step="0">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#14532d,#16a34a);">
          <div class="ob-icon-glow"></div>🌿
        </div>
        <p class="ob-step-tag" style="color:#16a34a">Welcome, SK Officer</p>
        <h2 class="ob-step-title">Hello, <?= htmlspecialchars($userName) ?>! 👋</h2>
        <p class="ob-step-body">Welcome to the <strong>SK E-Learning Resource Reservation System</strong>! You can approve reservations, scan QR tickets, and manage the library. Let's get you started!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-timer"></i></div><span class="ob-feature-text">Live session timer on your dashboard</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-clock"></i></div><span class="ob-feature-text">Amber badge shows pending requests needing attention</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="1">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#92400e,#d97706);">
          <div class="ob-icon-glow"></div>📋
        </div>
        <p class="ob-step-tag" style="color:#d97706">Step 1</p>
        <h2 class="ob-step-title">Review User Requests</h2>
        <p class="ob-step-body">Residents submit reservation requests that need your approval. Go to <strong>User Requests</strong> to review and approve or decline them.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-clock"></i></div><span class="ob-feature-text">Yellow badge shows pending requests count</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#dcfce7;color:#166534;"><i class="fa-solid fa-check"></i></div><span class="ob-feature-text">Approved residents receive a QR e-ticket instantly</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="2">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">
          <div class="ob-icon-glow"></div>📷
        </div>
        <p class="ob-step-tag" style="color:#2563eb">Step 2</p>
        <h2 class="ob-step-title">Scan E-Tickets</h2>
        <p class="ob-step-body">Use the <strong>Scanner</strong> to scan residents' QR code e-tickets when they arrive. This marks them as claimed and logs their visit.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-camera"></i></div><span class="ob-feature-text">Works with printed and on-screen QR codes</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-print"></i></div><span class="ob-feature-text">Log printing activity after each session ends</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="3">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#0f172a,#334155);">
          <div class="ob-icon-glow" style="background:#94a3b8;"></div>🚀
        </div>
        <p class="ob-step-tag" style="color:#64748b">All Set!</p>
        <h2 class="ob-step-title">You're ready to go!</h2>
        <p class="ob-step-body">You now know the essentials. Use the <strong>Help button</strong> (bottom right) anytime you need a refresher. The Active Sessions panel shows live time tracking!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-timer"></i></div><span class="ob-feature-text">Live session timer alerts when sessions end</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fffbeb;color:#92400e;"><i class="fa-solid fa-book-open"></i></div><span class="ob-feature-text">Manage the library and book borrowing requests</span></div>
        </div>
      </div>

      <?php else: ?>
      <!-- ── RESIDENT STEPS ── -->
      <div class="ob-step-panel ob-active" data-step="0">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#14532d,#16a34a);">
          <div class="ob-icon-glow"></div>👋
        </div>
        <p class="ob-step-tag" style="color:#16a34a">Welcome!</p>
        <h2 class="ob-step-title">Hi, <?= htmlspecialchars($userName) ?>!</h2>
        <p class="ob-step-body">Welcome to the <strong>SK E-Learning Resource Reservation System</strong> of Brgy. F. De Jesus! Reserve computers, borrow books, and track your schedule easily.</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-desktop"></i></div><span class="ob-feature-text">Book computers and e-learning resources online</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-book-open"></i></div><span class="ob-feature-text">Browse and borrow books from the community library</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="1">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">
          <div class="ob-icon-glow"></div>📅
        </div>
        <p class="ob-step-tag" style="color:#2563eb">Step 1</p>
        <h2 class="ob-step-title">Make a Reservation</h2>
        <p class="ob-step-body">Click <strong>New Reservation</strong> in the sidebar to book a resource. Choose your date, time, and purpose — then submit for approval!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-desktop"></i></div><span class="ob-feature-text">Choose from available PCs and resources</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-clock"></i></div><span class="ob-feature-text">Requests reviewed by SK Officers or the Chairman</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="2">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#6b21a8,#a855f7);">
          <div class="ob-icon-glow" style="background:#a855f7;"></div>🎫
        </div>
        <p class="ob-step-tag" style="color:#a855f7">Step 2</p>
        <h2 class="ob-step-title">Get Your E-Ticket</h2>
        <p class="ob-step-body">When approved, you'll receive a <strong>QR Code E-Ticket</strong>. Show this to the SK Officer at the facility to claim your slot. Download it to your phone!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f3e8ff;color:#6b21a8;"><i class="fa-solid fa-qrcode"></i></div><span class="ob-feature-text">Download your QR code ticket to your phone gallery</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-feature-text">Track all reservations from My Reservations</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="3">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#92400e,#d97706);">
          <div class="ob-icon-glow" style="background:#d97706;"></div>📚
        </div>
        <p class="ob-step-tag" style="color:#d97706">Step 3</p>
        <h2 class="ob-step-title">Borrow Books</h2>
        <p class="ob-step-body">Visit the <strong>Library</strong> to browse and borrow books. Use the <strong>AI Book Finder</strong> to get personalized suggestions based on what you want to read!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-book-open"></i></div><span class="ob-feature-text">Browse the full community book collection</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-wand-magic-sparkles"></i></div><span class="ob-feature-text">AI Book Finder suggests books based on your interests</span></div>
        </div>
      </div>
      <div class="ob-step-panel" data-step="4">
        <div class="ob-icon-wrap" style="background:linear-gradient(135deg,#0f172a,#334155);">
          <div class="ob-icon-glow" style="background:#94a3b8;"></div>🎓
        </div>
        <p class="ob-step-tag" style="color:#64748b">All Set!</p>
        <h2 class="ob-step-title">You're all set!</h2>
        <p class="ob-step-body">That's everything you need to know! The <strong>Help button</strong> in the bottom right is always there if you need it. Happy learning!</p>
        <div class="ob-features">
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-circle-question"></i></div><span class="ob-feature-text">Tap Help anytime for guides and FAQ</span></div>
          <div class="ob-feature-row"><div class="ob-feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-bell"></i></div><span class="ob-feature-text">Notifications alert you when reservations are approved</span></div>
        </div>
      </div>
      <?php endif; ?>

    </div><!-- /ob-header -->

    <!-- Footer -->
    <div class="ob-footer">
      <button class="ob-btn-skip" onclick="obClose()">
        <i class="fa-solid fa-xmark" style="font-size:0.7rem;margin-right:4px;"></i>Skip tour
      </button>
      <button class="ob-btn-next" id="ob-next-btn" onclick="obNext()">
        <span id="ob-next-label">Get Started</span>
        <i class="fa-solid fa-arrow-right" id="ob-next-icon"></i>
      </button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════
     FLOATING HELP BUTTON
════════════════════════════════════ -->
<button class="help-fab" onclick="helpOpen()" style="--ob-accent: <?= $accentColor ?>" aria-label="Help">
  <i class="fa-solid fa-circle-question"></i>
  <span class="help-fab-label">Help & Guide</span>
</button>

<!-- ═══════════════════════════════════
     HELP MODAL
════════════════════════════════════ -->
<div id="help-backdrop" class="help-backdrop" style="--ob-accent: <?= $accentColor ?>">
  <div class="help-modal">

    <div class="help-header">
      <div class="help-drag sm:hidden"></div>
      <div class="help-header-inner">
        <div class="flex items-center gap-3">
          <div style="width:42px;height:42px;border-radius:16px;background:color-mix(in srgb,<?= $accentColor ?> 12%,white);display:flex;align-items:center;justify-content:center;color:<?= $accentColor ?>;font-size:1.1rem;">
            <i class="fa-solid fa-circle-question"></i>
          </div>
          <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
              <p style="font-size:1rem;font-weight:800;color:#0f172a;margin:0;">Help & Guide</p>
              <span class="help-role-badge"><i class="fa-solid <?= $roleIcon ?>" style="font-size:0.6rem;"></i><?= $roleLabel ?></span>
            </div>
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

      <!-- OVERVIEW (all roles) -->
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
      <div id="tab-reservations" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Approving Reservations</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Manage Reservations</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Filter by <strong>Pending</strong> to see requests waiting for approval.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click a reservation to open details, then click <strong>Approve</strong> or <strong>Decline</strong>.</p></div>
          <div class="help-tip help-tip-info"><i class="fa-solid fa-ticket help-tip-icon"></i><p class="help-tip-text">Residents receive a QR code e-ticket automatically once you approve their reservation.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">QR Scanner</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Scanner</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Allow camera access when prompted.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Point the camera at the resident's QR code to mark them as <strong>Claimed</strong>.</p></div>
        </div>
      </div>
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
      <div id="tab-approve" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Approving User Requests</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>User Requests</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Review each request — check the date, time, and resource.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click <strong>Approve</strong> to confirm or <strong>Decline</strong> to reject.</p></div>
          <div class="help-tip help-tip-success"><i class="fa-solid fa-bell help-tip-icon"></i><p class="help-tip-text">The yellow badge on the sidebar shows how many requests need your attention.</p></div>
        </div>
      </div>
      <div id="tab-scanner" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Using the QR Scanner</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Scanner</strong> from the sidebar or mobile nav.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Click <strong>Allow</strong> when your browser asks for camera permission.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Point the camera at the resident's QR code — it validates automatically.</p></div>
          <div class="help-tip help-tip-info"><i class="fa-solid fa-lightbulb help-tip-icon"></i><p class="help-tip-text">For best results, ensure good lighting. Works with both printed and on-screen QR codes.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">Session Color Codes</p>
          <div class="help-step-row"><div class="help-step-num" style="background:#10b981;">✓</div><p class="help-step-text"><strong>Green</strong> — More than 5 minutes remaining</p></div>
          <div class="help-step-row"><div class="help-step-num" style="background:#f59e0b;">!</div><p class="help-step-text"><strong>Amber</strong> — 5 minutes or less remaining</p></div>
          <div class="help-step-row"><div class="help-step-num" style="background:#ef4444;">!!</div><p class="help-step-text"><strong>Red</strong> — 2 minutes or less, critically soon</p></div>
        </div>
      </div>

      <?php else: ?>
      <div id="tab-reserve" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Making a Reservation</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Click <strong>New Reservation</strong> in the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Select the <strong>Resource</strong> you need (e.g., Computer Lab).</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Choose your <strong>Date</strong>, <strong>Start Time</strong>, and <strong>End Time</strong>.</p></div>
          <div class="help-step-row"><div class="help-step-num">4</div><p class="help-step-text">Enter your <strong>Purpose</strong> and submit the request.</p></div>
          <div class="help-tip help-tip-warn"><i class="fa-solid fa-triangle-exclamation help-tip-icon"></i><p class="help-tip-text">You have a <strong>monthly quota of 3 reservations</strong>. Used slots reset at the start of each month.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">Using Your E-Ticket</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">When approved, go to <strong>My Reservations</strong> and click your reservation.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Your <strong>QR Code E-Ticket</strong> appears — tap <strong>Download E-Ticket</strong> to save it.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Show the QR code to the SK Officer at the facility.</p></div>
          <div class="help-tip help-tip-info"><i class="fa-solid fa-mobile-alt help-tip-icon"></i><p class="help-tip-text">Save your e-ticket to your phone gallery — you can show it without internet!</p></div>
        </div>
      </div>
      <div id="tab-library" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Borrowing a Book</p>
          <div class="help-step-row"><div class="help-step-num">1</div><p class="help-step-text">Go to <strong>Library</strong> from the sidebar.</p></div>
          <div class="help-step-row"><div class="help-step-num">2</div><p class="help-step-text">Browse available books — a <strong>green dot</strong> means copies are available.</p></div>
          <div class="help-step-row"><div class="help-step-num">3</div><p class="help-step-text">Click <strong>Borrow</strong> on the book you want and wait for approval.</p></div>
        </div>
        <div class="help-section">
          <p class="help-section-title">AI Book Finder</p>
          <div class="help-tip help-tip-success"><i class="fa-solid fa-wand-magic-sparkles help-tip-icon"></i><p class="help-tip-text">Use the <strong>AI Book Finder</strong> on the dashboard — describe what you want to read and get personalized recommendations!</p></div>
        </div>
      </div>
      <?php endif; ?>

      <!-- FAQ (all roles) -->
      <div id="tab-faq" class="help-tab-panel">
        <div class="help-section">
          <p class="help-section-title">Frequently Asked Questions</p>

          <?php if ($currentRole !== 'chairman'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">I didn't receive a verification email. <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">Check your Spam or Junk folder. If still not found, try registering again or contact the Barangay office. Make sure you used a valid email address.</p>
          </div>
          <?php endif; ?>

          <?php if ($currentRole === 'sk'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">My account says Pending after email verification. <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">This is normal for SK Officers. The Barangay Chairman must approve your SK account before you can log in. You'll receive an email notification once a decision is made.</p>
          </div>
          <?php endif; ?>

          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">The page shows a 404 error after the site wakes up. <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">The server may have been inactive. Refresh the page and go back to the login page manually at <strong>reservation-k2eg.onrender.com</strong>.</p>
          </div>

          <?php if ($currentRole === 'user'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">My reservation is stuck on Pending for a long time. <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">Contact an SK Officer or the Barangay Chairman directly to review your reservation request.</p>
          </div>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">Can I cancel a reservation? <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">Only Pending reservations can be cancelled. Go to My Reservations and click Cancel. Approved reservations cannot be cancelled through the system — contact the Barangay office.</p>
          </div>
          <?php endif; ?>

          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">I forgot my password. <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">Click the <strong>Forgot Password?</strong> link on the login page. Enter your email to receive a 6-digit verification code, then set a new password.</p>
          </div>

          <?php if ($currentRole === 'sk'): ?>
          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">The QR scanner is not working. <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">Make sure you've allowed camera access in your browser settings. Ensure good lighting and hold the QR code steady. The scanner works best in Chrome or Safari.</p>
          </div>
          <?php endif; ?>

          <div class="help-faq-item">
            <button class="help-faq-q" onclick="helpFaq(this)">How do I install this as an app on my phone? <i class="fa-solid fa-chevron-down"></i></button>
            <p class="help-faq-a">On Android (Chrome): tap the 3-dot menu → Add to Home Screen → Install. On iPhone (Safari): tap the Share button → Add to Home Screen → Add.</p>
          </div>
        </div>
      </div>

    </div><!-- /help-body -->
  </div>
</div>

<script>
(function() {
  const ROLE        = '<?= $currentRole ?>';
  const OB_KEY      = 'ob_done_' + ROLE + '_v3';
  const TOTAL_STEPS = document.querySelectorAll('.ob-step-panel').length;
  let   obStep      = 0;

  /* ── Build dots ── */
  const dotsEl = document.getElementById('ob-dots');
  for (let i = 0; i < TOTAL_STEPS; i++) {
    const d = document.createElement('div');
    d.className = 'ob-dot' + (i === 0 ? ' ob-dot-active' : '');
    d.id = 'ob-dot-' + i;
    dotsEl.appendChild(d);
  }

  function obUpdateUI(n) {
    /* panels */
    document.querySelectorAll('.ob-step-panel').forEach((p, i) => p.classList.toggle('ob-active', i === n));
    /* dots */
    document.querySelectorAll('.ob-dot').forEach((d, i) => {
      d.classList.remove('ob-dot-active', 'ob-dot-done');
      if (i < n)   d.classList.add('ob-dot-done');
      if (i === n) d.classList.add('ob-dot-active');
    });
    /* progress bar */
    const pct = TOTAL_STEPS <= 1 ? 100 : Math.round((n / (TOTAL_STEPS - 1)) * 100);
    document.getElementById('ob-progress').style.width = pct + '%';
    /* counter */
    document.getElementById('ob-counter').textContent = n === 0 ? 'Welcome' : (n === TOTAL_STEPS - 1 ? 'All Done! 🎉' : `Step ${n} of ${TOTAL_STEPS - 1}`);
    /* button */
    const isLast = n === TOTAL_STEPS - 1;
    document.getElementById('ob-next-label').textContent = isLast ? 'Start Exploring' : (n === 0 ? 'Show Me Around' : 'Next');
    document.getElementById('ob-next-icon').className    = isLast ? 'fa-solid fa-rocket' : 'fa-solid fa-arrow-right';
  }

  window.obClose = function() {
    localStorage.setItem(OB_KEY, '1');
    document.getElementById('ob-backdrop').classList.remove('ob-open');
    document.body.style.overflow = '';
  };

  window.obRestart = function() {
    obStep = 0;
    localStorage.removeItem(OB_KEY);
    obUpdateUI(0);
    setTimeout(() => {
      document.getElementById('ob-backdrop').classList.add('ob-open');
      document.body.style.overflow = 'hidden';
    }, 100);
  };

  window.obNext = function() {
    if (obStep < TOTAL_STEPS - 1) { obStep++; obUpdateUI(obStep); }
    else obClose();
  };

  /* init */
  obUpdateUI(0);
  if (!localStorage.getItem(OB_KEY)) setTimeout(() => {
    document.getElementById('ob-backdrop').classList.add('ob-open');
    document.body.style.overflow = 'hidden';
  }, 900);

  /* ── HELP MODAL ── */
  window.helpOpen  = () => { document.getElementById('help-backdrop').classList.add('help-open');    document.body.style.overflow = 'hidden'; };
  window.helpClose = () => { document.getElementById('help-backdrop').classList.remove('help-open'); document.body.style.overflow = ''; };

  window.helpTab = (btn, tabId) => {
    document.querySelectorAll('.help-tab').forEach(t => t.classList.remove('help-tab-active'));
    document.querySelectorAll('.help-tab-panel').forEach(p => p.classList.remove('help-tab-active'));
    btn.classList.add('help-tab-active');
    document.getElementById(tabId).classList.add('help-tab-active');
  };

  window.helpFaq = btn => {
    const item = btn.closest('.help-faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.help-faq-item').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  };

  document.getElementById('help-backdrop').addEventListener('click', e => { if (e.target === document.getElementById('help-backdrop')) helpClose(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { helpClose(); obClose(); } });
})();
</script>