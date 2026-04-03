<?php
$currentRole = $role ?? session()->get('role') ?? 'user';
$userName    = session()->get('name') ?? 'there';
$accent      = '#2563eb';
$accentRgb   = '37,99,235';
$roleLabel   = match($currentRole) { 'chairman' => 'Chairman', 'sk' => 'SK Officer', default => 'Resident' };
$roleIcon    = match($currentRole) { 'chairman' => 'fa-crown', 'sk' => 'fa-user-shield', default => 'fa-user' };
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
/* ═══════════════════════════════════════════════════
   ROOT & RESET
═══════════════════════════════════════════════════ */
:root {
  --a: <?= $accent ?>;
  --a-rgb: <?= $accentRgb ?>;
  --a-dim: rgba(<?= $accentRgb ?>,.12);
  --a-glow: rgba(<?= $accentRgb ?>,.35);
  --card-bg: #07090f;
  --card-border: rgba(255,255,255,.07);
  --font-display: 'Roboto', system-ui, sans-serif;
  --font-body: 'Roboto', system-ui, sans-serif;
}

/* ═══════════════════════════════════════════════════
   OVERLAY BACKDROP
═══════════════════════════════════════════════════ */
.ob-wrap, .help-over {
  position: fixed; inset: 0; z-index: 9999;
  display: flex; align-items: center; justify-content: center;
  padding: 1rem;
  opacity: 0; pointer-events: none;
  transition: opacity .25s ease;
}
.ob-wrap.open, .help-over.open {
  opacity: 1; pointer-events: all;
}

/* Animated mesh background */
.ob-bg {
  position: absolute; inset: 0;
  background: rgba(2,4,16,.92);
  backdrop-filter: blur(12px) saturate(1.4);
}
.ob-bg::before {
  content: '';
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 60% 40% at 20% 30%, rgba(<?= $accentRgb ?>,.18) 0%, transparent 60%),
    radial-gradient(ellipse 50% 35% at 80% 70%, rgba(120,80,255,.12) 0%, transparent 60%);
  animation: meshDrift 8s ease-in-out infinite alternate;
}
.ob-bg::after {
  content: '';
  position: absolute; inset: 0;
  background-image:
    radial-gradient(circle, rgba(255,255,255,.025) 1px, transparent 1px);
  background-size: 32px 32px;
}
@keyframes meshDrift {
  from { transform: scale(1) rotate(0deg); }
  to   { transform: scale(1.08) rotate(2deg); }
}

/* ═══════════════════════════════════════════════════
   ONBOARDING CARD
═══════════════════════════════════════════════════ */
.ob-card {
  position: relative; z-index: 1;
  width: 100%; max-width: 380px;
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 24px;
  overflow: hidden;
  box-shadow:
    0 0 0 1px rgba(255,255,255,.04),
    0 32px 64px -16px rgba(0,0,0,.8),
    0 0 80px -20px var(--a-glow);
  transform: translateY(20px) scale(.96);
  transition: transform .38s cubic-bezier(.34,1.3,.64,1), box-shadow .38s ease;
}
.ob-wrap.open .ob-card {
  transform: none;
}

/* Chromatic top accent */
.ob-chrome {
  height: 3px;
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(<?= $accentRgb ?>,.4) 20%,
    var(--a) 50%,
    rgba(<?= $accentRgb ?>,.4) 80%,
    transparent 100%
  );
  position: relative;
}
.ob-chrome::after {
  content: '';
  position: absolute; inset: 0;
  background: inherit;
  filter: blur(6px);
  opacity: .7;
}

/* Progress */
.ob-prog-track {
  height: 2px;
  background: rgba(255,255,255,.05);
  overflow: visible;
  position: relative;
}
.ob-prog-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--a), rgba(<?= $accentRgb ?>,.6));
  border-radius: 0 2px 2px 0;
  transition: width .5s cubic-bezier(.34,1.2,.64,1);
  position: relative;
}
.ob-prog-fill::after {
  content: '';
  position: absolute; right: -3px; top: 50%;
  transform: translateY(-50%);
  width: 6px; height: 6px;
  background: var(--a);
  border-radius: 50%;
  box-shadow: 0 0 8px var(--a), 0 0 16px var(--a-glow);
  transition: opacity .3s;
}

/* Body */
.ob-body {
  padding: 1.6rem 1.6rem 1rem;
  font-family: var(--font-body);
}

/* Header row */
.ob-hrow {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.4rem;
}
.ob-step-lbl {
  font-family: var(--font-body);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .18em;
  text-transform: uppercase;
  color: rgba(<?= $accentRgb ?>,.75);
}
.ob-pips {
  display: flex;
  gap: 5px;
  align-items: center;
}
.ob-pip {
  height: 4px;
  border-radius: 99px;
  background: rgba(255,255,255,.1);
  transition: width .35s cubic-bezier(.34,1.2,.64,1), background .3s ease;
  width: 14px;
}
.ob-pip.active {
  background: var(--a);
  width: 28px;
  box-shadow: 0 0 8px var(--a-glow);
}
.ob-pip.done {
  background: rgba(<?= $accentRgb ?>,.38);
  width: 18px;
}

/* Panel */
.ob-panel { display: none; }
.ob-panel.on {
  display: block;
  animation: panelSlideIn .32s cubic-bezier(.34,1.1,.64,1) both;
}
.ob-panel.out {
  animation: panelSlideOut .22s ease both;
}
@keyframes panelSlideIn {
  from { opacity: 0; transform: translateX(18px); }
  to   { opacity: 1; transform: none; }
}
@keyframes panelSlideOut {
  from { opacity: 1; transform: none; }
  to   { opacity: 0; transform: translateX(-14px); }
}
@keyframes panelSlideInRev {
  from { opacity: 0; transform: translateX(-18px); }
  to   { opacity: 1; transform: none; }
}

/* Step icon */
.ob-ico-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 1.1rem;
}
.ob-ico {
  width: 48px; height: 48px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  flex-shrink: 0;
  position: relative;
  background: var(--a-dim);
  border: 1px solid rgba(<?= $accentRgb ?>,.22);
  color: var(--a);
  overflow: hidden;
}
.ob-ico::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(circle at 60% 35%, rgba(255,255,255,.12), transparent 65%);
}
.ob-ico i { position: relative; z-index: 1; }
.ob-role-badge {
  font-family: var(--font-body);
  font-size: .62rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: rgba(255,255,255,.35);
  padding: .22rem .7rem;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 99px;
}

/* Title */
.ob-title {
  font-family: var(--font-display);
  font-size: 1.35rem;
  font-weight: 900;
  color: #f8fafc;
  letter-spacing: -.02em;
  line-height: 1.2;
  margin: 0 0 .5rem;
}
.ob-title em {
  font-style: normal;
  color: var(--a);
  text-shadow: 0 0 20px var(--a-glow);
}
.ob-desc {
  font-family: var(--font-body);
  font-size: .82rem;
  line-height: 1.7;
  color: #52525b;
  margin: 0;
}
.ob-desc strong { color: #a1a1aa; font-weight: 600; }

/* Feature chips */
.ob-chips { display: flex; flex-direction: column; gap: 6px; margin-top: 1rem; }
.ob-chip {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: .6rem .85rem;
  border-radius: 12px;
  background: rgba(255,255,255,.025);
  border: 1px solid rgba(255,255,255,.055);
  transition: background .18s, border-color .18s, transform .18s;
  cursor: default;
}
.ob-chip:hover {
  background: var(--a-dim);
  border-color: rgba(<?= $accentRgb ?>,.2);
  transform: translateX(3px);
}
.ob-cico {
  width: 28px; height: 28px;
  border-radius: 9px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .7rem;
  background: rgba(<?= $accentRgb ?>,.12);
  color: var(--a);
  border: 1px solid rgba(<?= $accentRgb ?>,.18);
}
.ob-ctxt {
  font-family: var(--font-body);
  font-size: .76rem;
  font-weight: 500;
  color: #52525b;
}
.ob-chip:hover .ob-ctxt { color: #71717a; }

/* Footer */
.ob-foot {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 1rem 1.6rem 1.6rem;
  border-top: 1px solid rgba(255,255,255,.05);
}
.ob-skip {
  font-family: var(--font-body);
  font-size: .76rem;
  font-weight: 600;
  color: #3f3f46;
  background: none;
  border: none;
  cursor: pointer;
  padding: .5rem .65rem;
  border-radius: 9px;
  transition: color .15s, background .15s;
}
.ob-skip:hover { color: #71717a; background: rgba(255,255,255,.04); }
.ob-prev {
  width: 42px; height: 42px;
  flex-shrink: 0;
  border-radius: 13px;
  border: 1.5px solid rgba(255,255,255,.1);
  background: rgba(255,255,255,.04);
  color: rgba(255,255,255,.45);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .82rem;
  font-family: var(--font-body);
  transition: all .18s;
}
.ob-prev:hover {
  background: rgba(255,255,255,.1);
  border-color: rgba(255,255,255,.2);
  color: white;
  transform: translateX(-2px);
}
.ob-btn {
  flex: 1;
  padding: .78rem 1rem;
  border: none;
  border-radius: 13px;
  font-family: var(--font-display);
  font-size: .84rem;
  font-weight: 700;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  position: relative;
  overflow: hidden;
  background: var(--a);
  transition: transform .2s, box-shadow .2s;
  letter-spacing: .01em;
}
.ob-btn::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.18) 0%, transparent 60%);
  pointer-events: none;
}
.ob-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 28px -6px var(--a-glow), 0 0 0 1px rgba(255,255,255,.1) inset;
}
.ob-btn:active { transform: translateY(0) scale(.98); }
.ob-btn i { font-size: .78rem; transition: transform .25s; }
.ob-btn:hover i { transform: translateX(3px); }

/* ═══════════════════════════════════════════════════
   HELP FAB
═══════════════════════════════════════════════════ */
.help-fab-wrap {
  position: fixed;
  bottom: 88px; right: 20px;
  z-index: 8000;
}
.help-fab {
  width: 52px; height: 52px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.15rem;
  color: white;
  background: var(--a);
  box-shadow: 0 8px 24px -6px rgba(<?= $accentRgb ?>,.55), 0 0 0 1px rgba(255,255,255,.12) inset;
  transition: transform .28s cubic-bezier(.34,1.4,.64,1), box-shadow .25s;
  position: relative;
  z-index: 1;
}
.help-fab:hover {
  transform: scale(1.12) translateY(-3px);
  box-shadow: 0 16px 40px -8px rgba(<?= $accentRgb ?>,.6), 0 0 0 1px rgba(255,255,255,.18) inset;
}
.help-fab:active { transform: scale(.96); }
/* Pulse ring */
.help-fab-ring {
  position: absolute; inset: -6px;
  border-radius: 50%;
  border: 1.5px solid rgba(<?= $accentRgb ?>,.4);
  animation: fabRing 2.4s ease-out infinite;
  pointer-events: none;
}
.help-fab-ring:nth-child(2) { animation-delay: .8s; }
@keyframes fabRing {
  0%   { transform: scale(1); opacity: .7; }
  100% { transform: scale(1.7); opacity: 0; }
}
/* Tooltip */
.help-fab-tip {
  position: absolute;
  right: calc(100% + 12px);
  top: 50%;
  transform: translateY(-50%) translateX(6px);
  background: #0f172a;
  color: #e2e8f0;
  font-family: var(--font-body);
  font-size: .74rem;
  font-weight: 600;
  padding: .4rem .75rem;
  border-radius: 9px;
  white-space: nowrap;
  pointer-events: none;
  opacity: 0;
  border: 1px solid rgba(255,255,255,.08);
  box-shadow: 0 4px 14px rgba(0,0,0,.35);
  transition: opacity .2s, transform .2s;
}
.help-fab-tip::after {
  content: '';
  position: absolute;
  right: -5px; top: 50%;
  transform: translateY(-50%);
  border: 5px solid transparent;
  border-right: none;
  border-left-color: #0f172a;
}
.help-fab-wrap:hover .help-fab-tip {
  opacity: 1;
  transform: translateY(-50%) translateX(0);
}

/* ═══════════════════════════════════════════════════
   HELP OVERLAY
═══════════════════════════════════════════════════ */
.help-over {
  z-index: 8500;
  align-items: flex-end;
  padding: 0;
}
@media (min-width: 640px) {
  .help-over { align-items: center; padding: 1.5rem; }
}
.help-bg {
  position: absolute; inset: 0;
  background: rgba(2,4,16,.7);
  backdrop-filter: blur(10px);
}
.help-modal {
  position: relative; z-index: 1;
  background: #ffffff;
  width: 100%; max-width: 620px;
  border-radius: 28px 28px 0 0;
  max-height: 90dvh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transform: translateY(40px);
  transition: transform .38s cubic-bezier(.34,1.15,.64,1), box-shadow .38s;
  box-shadow: 0 -8px 40px rgba(0,0,0,.15);
  font-family: var(--font-body);
}
@media (min-width: 640px) {
  .help-modal {
    border-radius: 24px;
    transform: scale(.93) translateY(20px);
    box-shadow: 0 32px 64px -16px rgba(0,0,0,.25);
  }
}
.help-over.open .help-modal { transform: none; }

/* Handle */
.help-handle-wrap {
  flex-shrink: 0;
  padding: .85rem 0 0;
  display: flex;
  justify-content: center;
}
@media (min-width: 640px) { .help-handle-wrap { display: none; } }
.help-handle {
  width: 40px; height: 4px;
  background: #e2e8f0;
  border-radius: 99px;
}

/* Header */
.help-hdr {
  flex-shrink: 0;
  padding: .9rem 1.5rem 0;
}
.help-hdr-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f1f5f9;
}
.help-hdr-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.help-hdr-ico {
  width: 44px; height: 44px;
  border-radius: 15px;
  background: var(--a-dim);
  border: 1px solid rgba(<?= $accentRgb ?>,.2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--a);
  font-size: 1rem;
  flex-shrink: 0;
}
.help-hdr-title {
  font-family: var(--font-display);
  font-size: 1rem;
  font-weight: 900;
  color: #0f172a;
  margin: 0;
  letter-spacing: -.02em;
}
.help-hdr-sub {
  font-size: .7rem;
  color: #94a3b8;
  margin: 2px 0 0;
  font-weight: 500;
}
.help-rtag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: .22rem .65rem;
  border-radius: 99px;
  font-size: .62rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .07em;
  background: rgba(<?= $accentRgb ?>,.08);
  color: var(--a);
  border: 1px solid rgba(<?= $accentRgb ?>,.18);
  margin-top: 4px;
  width: fit-content;
}
.help-x {
  width: 38px; height: 38px;
  border-radius: 12px;
  background: #f1f5f9;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #64748b;
  font-size: .82rem;
  font-family: var(--font-body);
  transition: all .15s;
  flex-shrink: 0;
}
.help-x:hover { background: #fee2e2; color: #dc2626; }

/* Tabs */
.help-tabs {
  display: flex;
  padding: 0 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  flex-shrink: 0;
  overflow-x: auto;
  gap: 2px;
}
.help-tabs::-webkit-scrollbar { display: none; }
.help-tab {
  padding: .85rem 1rem;
  font-family: var(--font-body);
  font-size: .78rem;
  font-weight: 700;
  color: #94a3b8;
  background: none;
  border: none;
  cursor: pointer;
  border-bottom: 2.5px solid transparent;
  white-space: nowrap;
  transition: color .15s, border-color .15s;
  margin-bottom: -1px;
}
.help-tab.on { color: var(--a); border-bottom-color: var(--a); }
.help-tab:hover:not(.on) { color: #475569; }

/* Body */
.help-body {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}
.help-body::-webkit-scrollbar { width: 4px; }
.help-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
.help-pane { display: none; }
.help-pane.on {
  display: block;
  animation: panelSlideIn .24s cubic-bezier(.34,1.1,.64,1) both;
}

/* Section label */
.h-sec { margin-bottom: 1.75rem; }
.h-sec:last-child { margin-bottom: 0; }
.h-sec-lbl {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: .65rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .14em;
  color: #cbd5e1;
  margin-bottom: .85rem;
}
.h-sec-lbl::after {
  content: '';
  flex: 1;
  height: 1px;
  background: #f1f5f9;
}

/* Steps */
.h-step {
  display: flex;
  gap: 12px;
  margin-bottom: .7rem;
  align-items: flex-start;
}
.h-num {
  width: 28px; height: 28px;
  border-radius: 10px;
  flex-shrink: 0;
  margin-top: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--font-display);
  font-size: .72rem;
  font-weight: 900;
  color: white;
  background: var(--a);
}
.h-stxt {
  font-size: .85rem;
  color: #334155;
  line-height: 1.65;
  font-weight: 500;
}
.h-stxt strong { color: #0f172a; font-weight: 700; }

/* Tips */
.h-tip {
  display: flex;
  gap: 12px;
  padding: .95rem 1.1rem;
  border-radius: 14px;
  margin-bottom: .75rem;
  border: 1px solid;
}
.h-tip-i { background: #eff6ff; border-color: #bfdbfe; }
.h-tip-w { background: #fffbeb; border-color: #fde68a; }
.h-tip-s { background: #f0fdf4; border-color: #bbf7d0; }
.h-tip-ico { font-size: .9rem; flex-shrink: 0; margin-top: 2px; }
.h-tip-i .h-tip-ico { color: #2563eb; }
.h-tip-w .h-tip-ico { color: #d97706; }
.h-tip-s .h-tip-ico { color: #16a34a; }
.h-tip-txt { font-size: .83rem; line-height: 1.65; color: #334155; font-weight: 500; }

/* FAQ */
.h-faq {
  border: 1px solid #f1f5f9;
  border-radius: 14px;
  margin-bottom: .5rem;
  overflow: hidden;
  transition: border-color .2s;
}
.h-faq.open { border-color: rgba(<?= $accentRgb ?>,.3); }
.h-faq-q {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .75rem;
  padding: .95rem 1.1rem;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  font-family: var(--font-body);
  font-size: .85rem;
  font-weight: 700;
  color: #1e293b;
  transition: background .15s;
}
.h-faq-q:hover { background: #f8fafc; }
.h-faq-q .h-faq-chevron {
  color: #cbd5e1;
  font-size: .7rem;
  transition: transform .25s cubic-bezier(.34,1.2,.64,1), color .2s;
  flex-shrink: 0;
}
.h-faq.open .h-faq-q .h-faq-chevron {
  transform: rotate(180deg);
  color: var(--a);
}
.h-faq-a {
  font-size: .83rem;
  color: #475569;
  line-height: 1.7;
  font-weight: 500;
  padding: 0 1.1rem;
  max-height: 0;
  overflow: hidden;
  transition: max-height .3s ease, padding .3s ease;
}
.h-faq.open .h-faq-a {
  max-height: 200px;
  padding-bottom: 1rem;
}

/* Restart button */
.h-restart {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: .95rem 1.1rem;
  border-radius: 14px;
  border: 1.5px dashed #e2e8f0;
  background: none;
  cursor: pointer;
  width: 100%;
  font-family: var(--font-body);
  font-size: .84rem;
  font-weight: 700;
  color: #64748b;
  transition: all .2s;
  margin-top: .9rem;
}
.h-restart:hover {
  border-color: var(--a);
  color: var(--a);
  background: rgba(<?= $accentRgb ?>,.04);
}
.h-restart i { color: var(--a); font-size: .85rem; }

/* ═══════════════════════════════════════════════════
   CELEBRATE (last step confetti)
═══════════════════════════════════════════════════ */
.ob-confetti-wrap {
  position: absolute; inset: 0;
  pointer-events: none;
  overflow: hidden;
  z-index: 0;
  opacity: 0;
  transition: opacity .3s;
}
.ob-confetti-wrap.burst { opacity: 1; }
.ob-confetti {
  position: absolute;
  width: 6px; height: 6px;
  border-radius: 1px;
  animation: confettiFall linear both;
}
@keyframes confettiFall {
  0%   { transform: translateY(-20px) rotate(0deg) scale(1); opacity: 1; }
  100% { transform: translateY(300px) rotate(720deg) scale(.3); opacity: 0; }
}
</style>

<!-- ══════════════════════════════════════════════════════
     ONBOARDING CARD
══════════════════════════════════════════════════════ -->
<div id="ob" class="ob-wrap">
  <div class="ob-bg"></div>
  <div class="ob-card">
    <div class="ob-confetti-wrap" id="ob-confetti"></div>
    <div class="ob-chrome"></div>
    <div class="ob-prog-track">
      <div class="ob-prog-fill" id="ob-prog" style="width:0%"></div>
    </div>
    <div class="ob-body">
      <div class="ob-hrow">
        <span class="ob-step-lbl" id="ob-lbl">Welcome</span>
        <div class="ob-pips" id="ob-pips"></div>
      </div>

      <?php if ($currentRole === 'chairman'): ?>
        <div class="ob-panel on">
          <div class="ob-ico-wrap">
            <div class="ob-ico"><i class="fa-solid fa-landmark"></i></div>
            <span class="ob-role-badge"><i class="fa-solid fa-crown" style="font-size:.55rem;color:var(--a)"></i> Chairman</span>
          </div>
          <h2 class="ob-title">Hello, <em><?= htmlspecialchars($userName) ?></em>!</h2>
          <p class="ob-desc">Welcome to the <strong>SK Reservation System</strong>. As Chairman, you have full system control. Quick tour?</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-chart-line"></i></div><span class="ob-ctxt">Full analytics with live session tracking</span></div>
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-user-shield"></i></div><span class="ob-ctxt">Approve SK officers and manage access</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-user-shield"></i></div></div>
          <h2 class="ob-title">Approve <em>SK Accounts</em></h2>
          <p class="ob-desc">SK Officers need your approval before logging in. Head to <strong>Manage SK</strong> to review applications.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-user-check"></i></div><span class="ob-ctxt">Review SK registrations with full details</span></div>
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-envelope"></i></div><span class="ob-ctxt">Officers notified automatically on your decision</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-calendar-check"></i></div></div>
          <h2 class="ob-title">Manage <em>Reservations</em></h2>
          <p class="ob-desc">View, approve, or decline all reservations. Validate e-tickets with the built-in QR scanner.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-calendar-check"></i></div><span class="ob-ctxt">One-click approve or decline</span></div>
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-qrcode"></i></div><span class="ob-ctxt">QR scanner validates tickets at the facility</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-rocket"></i></div></div>
          <h2 class="ob-title">You're <em>all set!</em></h2>
          <p class="ob-desc">The <strong>Help button</strong> (bottom right) is always available whenever you need guidance.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-circle-question"></i></div><span class="ob-ctxt">Tap Help anytime for guides and FAQ</span></div>
          </div>
        </div>

      <?php elseif ($currentRole === 'sk'): ?>
        <div class="ob-panel on">
          <div class="ob-ico-wrap">
            <div class="ob-ico"><i class="fa-solid fa-seedling"></i></div>
            <span class="ob-role-badge"><i class="fa-solid fa-user-shield" style="font-size:.55rem;color:var(--a)"></i> SK Officer</span>
          </div>
          <h2 class="ob-title">Hello, <em><?= htmlspecialchars($userName) ?></em>!</h2>
          <p class="ob-desc">Welcome! Approve reservations, scan QR tickets, and manage the library. Let's get started!</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-timer"></i></div><span class="ob-ctxt">Live session timer on your dashboard</span></div>
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Amber badge shows pending requests</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-clipboard-list"></i></div></div>
          <h2 class="ob-title">Review <em>User Requests</em></h2>
          <p class="ob-desc">Go to <strong>User Requests</strong> to approve or decline resident reservation requests.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Yellow badge shows pending count</span></div>
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-check"></i></div><span class="ob-ctxt">Approved residents get a QR e-ticket instantly</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-camera"></i></div></div>
          <h2 class="ob-title">Scan <em>E-Tickets</em></h2>
          <p class="ob-desc">Use the <strong>Scanner</strong> to validate residents' QR tickets when they arrive at the facility.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-camera"></i></div><span class="ob-ctxt">Works with printed and on-screen QR codes</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-rocket"></i></div></div>
          <h2 class="ob-title">You're <em>ready!</em></h2>
          <p class="ob-desc">Use the <strong>Help button</strong> anytime. The Active Sessions panel shows live time tracking!</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Manage library and book borrowing requests</span></div>
          </div>
        </div>

      <?php else: ?>
        <div class="ob-panel on">
          <div class="ob-ico-wrap">
            <div class="ob-ico"><i class="fa-solid fa-hand-wave"></i></div>
            <span class="ob-role-badge"><i class="fa-solid fa-user" style="font-size:.55rem;color:var(--a)"></i> Resident</span>
          </div>
          <h2 class="ob-title">Hi, <em><?= htmlspecialchars($userName) ?></em>!</h2>
          <p class="ob-desc">Welcome to the <strong>SK Reservation System</strong>. Reserve computers, borrow books, and track your schedule.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-desktop"></i></div><span class="ob-ctxt">Book computers and e-learning resources</span></div>
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-book-open"></i></div><span class="ob-ctxt">Browse and borrow from the community library</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-calendar-check"></i></div></div>
          <h2 class="ob-title">Make a <em>Reservation</em></h2>
          <p class="ob-desc">Click <strong>New Reservation</strong>. Choose your date, time, and purpose — then submit for approval.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-clock"></i></div><span class="ob-ctxt">Max 3 reservations per 2-week period</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-ticket"></i></div></div>
          <h2 class="ob-title">Get Your <em>E-Ticket</em></h2>
          <p class="ob-desc">When approved, you'll receive a <strong>QR Code E-Ticket</strong>. Show it to the SK Officer at the facility.</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-qrcode"></i></div><span class="ob-ctxt">Download to your phone — works offline</span></div>
          </div>
        </div>
        <div class="ob-panel">
          <div class="ob-ico-wrap"><div class="ob-ico"><i class="fa-solid fa-graduation-cap"></i></div></div>
          <h2 class="ob-title">You're <em>all set!</em></h2>
          <p class="ob-desc">The <strong>Help button</strong> (bottom right) is always there. Happy learning!</p>
          <div class="ob-chips">
            <div class="ob-chip"><div class="ob-cico"><i class="fa-solid fa-wand-magic-sparkles"></i></div><span class="ob-ctxt">AI Book Finder suggests reads just for you</span></div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <div class="ob-foot">
      <button class="ob-skip" onclick="obClose()">✕ Skip</button>
      <button class="ob-prev" id="ob-prev-btn" onclick="obPrev()" style="display:none" aria-label="Previous">
        <i class="fa-solid fa-arrow-left"></i>
      </button>
      <button class="ob-btn" onclick="obNext()">
        <span id="ob-btn-lbl">Show Me Around</span>
        <i id="ob-btn-ico" class="fa-solid fa-arrow-right"></i>
      </button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════
     HELP FAB
══════════════════════════════════════════════════════ -->
<div class="help-fab-wrap">
  <div class="help-fab-ring"></div>
  <div class="help-fab-ring"></div>
  <button class="help-fab" onclick="helpOpen()" aria-label="Help & Guide">
    <i class="fa-solid fa-circle-question"></i>
  </button>
  <div class="help-fab-tip">Help & Guide</div>
</div>

<!-- ══════════════════════════════════════════════════════
     HELP MODAL
══════════════════════════════════════════════════════ -->
<div id="help-over" class="help-over">
  <div class="help-bg" onclick="helpClose()"></div>
  <div class="help-modal">
    <div class="help-handle-wrap"><div class="help-handle"></div></div>
    <div class="help-hdr">
      <div class="help-hdr-row">
        <div class="help-hdr-left">
          <div class="help-hdr-ico"><i class="fa-solid fa-circle-question"></i></div>
          <div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
              <p class="help-hdr-title">Help & Guide</p>
            </div>
            <p class="help-hdr-sub">SK E-Learning Reservation System</p>
            <span class="help-rtag"><i class="fa-solid <?= $roleIcon ?>" style="font-size:.56rem"></i><?= $roleLabel ?></span>
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

      <!-- OVERVIEW (all roles) -->
      <div id="ht-ov" class="help-pane on">
        <div class="h-sec">
          <p class="h-sec-lbl">Your Role — <?= $roleLabel ?></p>
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
        <button class="h-restart" onclick="helpClose();obRestart()">
          <i class="fa-solid fa-rotate-left"></i>Replay the onboarding tour
        </button>
      </div>

      <!-- CHAIRMAN TABS -->
      <?php if ($currentRole === 'chairman'): ?>
      <div id="ht-res" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Approving Reservations</p>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage Reservations</strong> from the sidebar.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Filter by <strong>Pending</strong> to see requests waiting for approval.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click a reservation then click <strong>Approve</strong> or <strong>Decline</strong>.</p></div>
          <div class="h-tip h-tip-i"><i class="fa-solid fa-ticket h-tip-ico"></i><p class="h-tip-txt">Residents receive a QR e-ticket automatically once you approve.</p></div>
        </div>
      </div>
      <div id="ht-sk" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Approving SK Officers</p>
          <div class="h-tip h-tip-w"><i class="fa-solid fa-triangle-exclamation h-tip-ico"></i><p class="h-tip-txt">SK Officers can only log in after you approve their account.</p></div>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Manage SK</strong> — a badge shows pending approvals.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Click <strong>View</strong> on an SK account to see their details.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Approve</strong> to grant access or <strong>Reject</strong> to deny.</p></div>
        </div>
      </div>

      <!-- SK TABS -->
      <?php elseif ($currentRole === 'sk'): ?>
      <div id="ht-app" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Approving User Requests</p>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>User Requests</strong> from the sidebar.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Review each request — check the date, time, and resource.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Approve</strong> to confirm or <strong>Decline</strong> to reject.</p></div>
          <div class="h-tip h-tip-s"><i class="fa-solid fa-bell h-tip-ico"></i><p class="h-tip-txt">The yellow badge shows how many requests need attention.</p></div>
        </div>
      </div>
      <div id="ht-scan" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Using the QR Scanner</p>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Scanner</strong> from the sidebar.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Click <strong>Allow</strong> when asked for camera permission.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Point the camera at the QR code — it validates automatically.</p></div>
          <div class="h-tip h-tip-i"><i class="fa-solid fa-lightbulb h-tip-ico"></i><p class="h-tip-txt">Good lighting helps. Works with printed and on-screen QR codes.</p></div>
        </div>
      </div>

      <!-- USER TABS -->
      <?php else: ?>
      <div id="ht-rsv" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Making a Reservation</p>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Click <strong>New Reservation</strong> in the sidebar.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Select resource, choose date and time, enter purpose.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Submit — wait for SK Officer approval.</p></div>
          <div class="h-tip h-tip-w"><i class="fa-solid fa-triangle-exclamation h-tip-ico"></i><p class="h-tip-txt">You have a <strong>quota of 3 reservations per 2-week period</strong>. Resets automatically.</p></div>
        </div>
        <div class="h-sec">
          <p class="h-sec-lbl">Using Your E-Ticket</p>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>My Reservations</strong> and click your approved reservation.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">Tap <strong>Download</strong> to save the QR code to your phone.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Show the QR code to the SK Officer at the facility.</p></div>
        </div>
      </div>
      <div id="ht-lib" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Borrowing a Book</p>
          <div class="h-step"><div class="h-num">1</div><p class="h-stxt">Go to <strong>Library</strong> from the sidebar.</p></div>
          <div class="h-step"><div class="h-num">2</div><p class="h-stxt">A <strong>green dot</strong> means copies are available to borrow.</p></div>
          <div class="h-step"><div class="h-num">3</div><p class="h-stxt">Click <strong>Borrow</strong> and wait for SK Officer approval.</p></div>
          <div class="h-tip h-tip-s"><i class="fa-solid fa-wand-magic-sparkles h-tip-ico"></i><p class="h-tip-txt">Use the <strong>AI Book Finder</strong> on your dashboard for personalized picks!</p></div>
        </div>
      </div>
      <?php endif; ?>

      <!-- FAQ (all roles) -->
      <div id="ht-faq" class="help-pane">
        <div class="h-sec">
          <p class="h-sec-lbl">Frequently Asked Questions</p>
          <?php if ($currentRole !== 'chairman'): ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">I didn't receive a verification email.<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">Check your Spam folder. Try registering again or contact the Barangay office directly.</p>
          </div>
          <?php endif; ?>
          <?php if ($currentRole === 'sk'): ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">My account says Pending after email verification.<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">The Barangay Chairman must approve your SK account. You'll receive an email once a decision is made.</p>
          </div>
          <?php endif; ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">The page shows a 404 after the site wakes up.<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">The server may have been inactive. Refresh the page and navigate back to the site.</p>
          </div>
          <?php if ($currentRole === 'user'): ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">My reservation is stuck on Pending.<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">Contact an SK Officer or the Barangay Chairman directly to review your request.</p>
          </div>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">Can I cancel a reservation?<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">Only Pending reservations can be cancelled. Go to My Reservations and click Cancel.</p>
          </div>
          <?php endif; ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">I forgot my password.<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">Click <strong>Forgot Password?</strong> on the login page. Enter your email to receive a 6-digit reset code.</p>
          </div>
          <?php if ($currentRole === 'sk'): ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">The QR scanner is not working.<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">Allow camera access in your browser settings. Good lighting and holding the code steady helps. Best in Chrome or Safari.</p>
          </div>
          <?php endif; ?>
          <div class="h-faq">
            <button class="h-faq-q" onclick="hFaq(this)">How do I install this as an app?<i class="fa-solid fa-chevron-down h-faq-chevron"></i></button>
            <p class="h-faq-a">Android (Chrome): 3-dot menu → Add to Home Screen. iPhone (Safari): Share → Add to Home Screen.</p>
          </div>
        </div>
      </div>

    </div><!-- /help-body -->
  </div>
</div>

<script>
(function () {
  const ROLE = '<?= $currentRole ?>';
  const KEY  = 'ob_v7_' + ROLE;
  const obEl = document.getElementById('ob');

  const panels = [...obEl.querySelectorAll('.ob-panel')];
  const TOTAL  = panels.length;
  let step = 0;

  /* Build pips */
  const pipsEl = document.getElementById('ob-pips');
  for (let i = 0; i < TOTAL; i++) {
    const p = document.createElement('div');
    p.className = 'ob-pip' + (i === 0 ? ' active' : '');
    pipsEl.appendChild(p);
  }

  /* Update UI for current step */
  function upd(n, reverse) {
    panels.forEach((p, i) => {
      if (p.classList.contains('on')) {
        p.classList.remove('on');
        p.style.animation = reverse
          ? 'panelSlideIn .22s ease reverse both'
          : 'panelSlideOut .22s ease both';
        setTimeout(() => { p.style.animation = ''; }, 240);
      }
    });

    setTimeout(() => {
      panels.forEach((p, i) => {
        p.classList.remove('on');
        if (i === n) {
          p.style.animation = reverse
            ? 'panelSlideInRev .3s cubic-bezier(.34,1.1,.64,1) both'
            : 'panelSlideIn .3s cubic-bezier(.34,1.1,.64,1) both';
          p.classList.add('on');
          setTimeout(() => { p.style.animation = ''; }, 320);
        }
      });
    }, 120);

    [...pipsEl.children].forEach((p, i) => {
      p.classList.remove('active', 'done');
      if (i < n)       p.classList.add('done');
      else if (i === n) p.classList.add('active');
    });

    const pct = TOTAL <= 1 ? 100 : Math.round(n / (TOTAL - 1) * 100);
    document.getElementById('ob-prog').style.width = pct + '%';

    const lblMap = { 0: 'Welcome', [TOTAL - 1]: 'All done!' };
    document.getElementById('ob-lbl').textContent = lblMap[n] ?? `Step ${n} of ${TOTAL - 1}`;

    const isLast = n === TOTAL - 1;
    document.getElementById('ob-btn-lbl').textContent =
      isLast ? 'Start Exploring' : (n === 0 ? 'Show Me Around' : 'Next');
    document.getElementById('ob-btn-ico').className =
      'fa-solid ' + (isLast ? 'fa-rocket' : 'fa-arrow-right');

    document.getElementById('ob-prev-btn').style.display = n > 0 ? 'flex' : 'none';

    if (isLast) spawnConfetti();
  }

  function spawnConfetti() {
    const wrap = document.getElementById('ob-confetti');
    wrap.innerHTML = '';
    const colors = ['#2563eb','#60a5fa','#93c5fd','#ffffff','#fbbf24','#34d399'];
    for (let i = 0; i < 26; i++) {
      const c = document.createElement('div');
      c.className = 'ob-confetti';
      c.style.cssText = `
        left:${10 + Math.random() * 80}%;
        background:${colors[Math.floor(Math.random() * colors.length)]};
        animation-duration:${0.9 + Math.random() * .9}s;
        animation-delay:${Math.random() * .3}s;
        transform:rotate(${Math.random() * 360}deg);
        border-radius:${Math.random() > .5 ? '50%' : '2px'};
        width:${4 + Math.random() * 5}px;
        height:${4 + Math.random() * 5}px;
        opacity:${0.7 + Math.random() * .3};
      `;
      wrap.appendChild(c);
    }
    wrap.classList.add('burst');
    setTimeout(() => { wrap.classList.remove('burst'); wrap.innerHTML = ''; }, 1800);
  }

  window.obClose = () => {
    localStorage.setItem(KEY, '1');
    obEl.classList.remove('open');
    document.body.style.overflow = '';
  };
  window.obRestart = () => {
    step = 0;
    localStorage.removeItem(KEY);
    upd(0, false);
    setTimeout(() => {
      obEl.classList.add('open');
      document.body.style.overflow = 'hidden';
    }, 120);
  };
  window.obNext = () => {
    if (step < TOTAL - 1) { upd(++step, false); }
    else { obClose(); }
  };
  window.obPrev = () => {
    if (step > 0) { upd(--step, true); }
  };

  upd(0, false);
  if (!localStorage.getItem(KEY)) {
    setTimeout(() => {
      obEl.classList.add('open');
      document.body.style.overflow = 'hidden';
    }, 900);
  }

  window.helpOpen = () => {
    document.getElementById('help-over').classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.helpClose = () => {
    document.getElementById('help-over').classList.remove('open');
    document.body.style.overflow = '';
  };
  window.hTab = (btn, id) => {
    document.querySelectorAll('.help-tab').forEach(t => t.classList.remove('on'));
    document.querySelectorAll('.help-pane').forEach(p => p.classList.remove('on'));
    btn.classList.add('on');
    document.getElementById(id).classList.add('on');
  };
  window.hFaq = btn => {
    const item = btn.closest('.h-faq');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.h-faq').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  };

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { helpClose(); obClose(); }
    if (e.key === 'ArrowRight' && obEl.classList.contains('open')) obNext();
    if (e.key === 'ArrowLeft'  && obEl.classList.contains('open')) obPrev();
  });
})();
</script>