<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>EduReserve · SK E-Learning Resource Center — Brgy. F. De Jesus</title>
    <meta name="theme-color" content="#1e1b4b">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
        :root {
            --indigo:        #3730a3;
            --indigo-mid:    #4338ca;
            --indigo-light:  #eef2ff;
            --indigo-border: #c7d2fe;
            --red:           #c0392b;
            --gold:          #d4a017;
            --font:          'Plus Jakarta Sans', system-ui, sans-serif;
            --mono:          'JetBrains Mono', monospace;
        }
        html, body { height: 100%; font-family: var(--font); overflow-x: hidden; }
        body { background: #060e1e; color: #e2eaf8; min-height: 100vh; position: relative; }
        .bg-mesh { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
        .bg-mesh::before { content: ''; position: absolute; width: 900px; height: 900px; top: -300px; left: -200px; background: radial-gradient(circle, rgba(55,48,163,.35) 0%, transparent 70%); animation: driftA 18s ease-in-out infinite alternate; }
        .bg-mesh::after { content: ''; position: absolute; width: 700px; height: 700px; bottom: -200px; right: -150px; background: radial-gradient(circle, rgba(192,57,43,.25) 0%, transparent 70%); animation: driftB 14s ease-in-out infinite alternate; }
        .bg-orb { position: fixed; width: 500px; height: 500px; top: 40%; left: 55%; transform: translate(-50%, -50%); background: radial-gradient(circle, rgba(212,160,23,.12) 0%, transparent 65%); animation: driftC 20s ease-in-out infinite alternate; pointer-events: none; z-index: 0; }
        @keyframes driftA { from { transform: translate(0,0) scale(1); } to { transform: translate(60px,80px) scale(1.15); } }
        @keyframes driftB { from { transform: translate(0,0) scale(1); } to { transform: translate(-40px,-60px) scale(1.1); } }
        @keyframes driftC { from { transform: translate(-50%,-50%) scale(1); } to { transform: translate(-45%,-55%) scale(1.2); } }
        .bg-grid { position: fixed; inset: 0; z-index: 0; pointer-events: none; background-image: linear-gradient(rgba(99,102,241,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,.04) 1px, transparent 1px); background-size: 48px 48px; }
        .page { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; }
        nav { display: flex; align-items: center; justify-content: space-between; padding: 20px 40px; border-bottom: 1px solid rgba(99,102,241,.1); backdrop-filter: blur(12px); background: rgba(6,14,30,.6); position: sticky; top: 0; z-index: 100; animation: slideDown .6s ease both; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: none; } }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-brand-text { line-height: 1.1; }
        .nav-brand-name { font-size: 1.1rem; font-weight: 800; color: #e2eaf8; letter-spacing: -.03em; }
        .nav-brand-name em { font-style: normal; color: #818cf8; }
        .nav-brand-sub { font-size: .6rem; font-weight: 600; color: #4a6fa5; letter-spacing: .06em; text-transform: uppercase; }
        .nav-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .btn-ghost { padding: 9px 20px; border-radius: 10px; font-size: .82rem; font-weight: 700; border: 1px solid rgba(99,102,241,.25); background: transparent; color: #a5b4fc; cursor: pointer; font-family: var(--font); text-decoration: none; transition: all .2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-ghost:hover { background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.5); color: #c7d2fe; }
        .btn-primary { padding: 9px 22px; border-radius: 10px; font-size: .82rem; font-weight: 800; border: none; background: var(--indigo); color: #fff; cursor: pointer; font-family: var(--font); text-decoration: none; transition: all .2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 14px rgba(55,48,163,.4); }
        .btn-primary:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(55,48,163,.5); }
        .btn-manual { padding: 9px 18px; border-radius: 10px; font-size: .82rem; font-weight: 700; border: 1px solid rgba(212,160,23,.3); background: rgba(212,160,23,.08); color: #fbbf24; cursor: pointer; font-family: var(--font); transition: all .2s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-manual:hover { background: rgba(212,160,23,.15); border-color: rgba(212,160,23,.5); }

        @media(max-width:540px) {
            nav { padding: 12px 16px; gap: 8px; }
            .nav-brand-sub { display: none; }
            .nav-brand-name { font-size: .95rem; }
            .btn-ghost .label { display: none; }
            .btn-ghost { padding: 8px 12px; }
            .btn-manual .label { display: none; }
            .btn-manual { padding: 8px 12px; }
            .btn-primary { padding: 8px 14px; font-size: .78rem; }
            .hero { padding: 36px 20px 56px; }
            .logo-ring { width: 110px; height: 110px; margin-bottom: 24px; }
            .eyebrow-pill { font-size: .6rem; padding: 4px 12px; margin-bottom: 16px; }
            .hero-subtitle { font-size: .88rem; margin-bottom: 10px; }
            .hero-org { font-size: .72rem; margin-bottom: 32px; }
            .cta-group { flex-direction: column; align-items: stretch; gap: 10px; padding: 0 8px; }
            .cta-login, .cta-register, .cta-manual-hero { justify-content: center; padding: 13px 24px; font-size: .85rem; width: 100%; }
            .features { padding: 0 16px 60px; }
            .features-grid { grid-template-columns: 1fr; gap: 12px; }
            .feat-card { padding: 18px; }
            .divider { margin: 0 16px 32px; }
            footer { padding: 16px 18px; flex-direction: column; align-items: center; text-align: center; gap: 10px; }
            .footer-right { justify-content: center; flex-wrap: wrap; }
        }
        @media(min-width:541px) and (max-width:720px) {
            nav { padding: 14px 24px; }
            .nav-brand-sub { display: none; }
            .hero { padding: 48px 24px 72px; }
            .logo-ring { width: 120px; height: 120px; }
            .features-grid { grid-template-columns: repeat(2,1fr); gap: 14px; }
            .cta-group { gap: 10px; }
        }
        .hero { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 24px 80px; text-align: center; }
        .logo-ring { position: relative; width: 140px; height: 140px; margin: 0 auto 36px; animation: fadeUp .8s .1s ease both; }
        .logo-ring::before { content: ''; position: absolute; inset: -6px; border-radius: 50%; background: conic-gradient(from 0deg, #c0392b, #d4a017, #3730a3, #c0392b); animation: rotateBorder 6s linear infinite; z-index: 0; }
        .logo-ring::after { content: ''; position: absolute; inset: -6px; border-radius: 50%; background: conic-gradient(from 0deg, #c0392b, #d4a017, #3730a3, #c0392b); filter: blur(10px); opacity: .5; animation: rotateBorder 6s linear infinite; z-index: 0; }
        @keyframes rotateBorder { to { transform: rotate(360deg); } }
        .logo-img-wrap { position: relative; z-index: 1; width: 100%; height: 100%; border-radius: 50%; overflow: hidden; background: #060e1e; padding: 4px; }
        .logo-img-wrap img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block; }
        .eyebrow-pill { display: inline-flex; align-items: center; gap: 6px; background: rgba(55,48,163,.18); border: 1px solid rgba(99,102,241,.28); color: #a5b4fc; font-size: .65rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; padding: 5px 14px; border-radius: 999px; margin-bottom: 22px; animation: fadeUp .7s .2s ease both; }
        .eyebrow-pill span.dot { width: 6px; height: 6px; border-radius: 50%; background: #818cf8; display: inline-block; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity:1;transform:scale(1);} 50% {opacity:.5;transform:scale(.8);} }
        .hero-title { font-size: clamp(2.8rem, 7vw, 5rem); font-weight: 800; letter-spacing: -.05em; line-height: 1; color: #e2eaf8; margin-bottom: 10px; animation: fadeUp .7s .3s ease both; }
        .hero-title em { font-style: normal; color: #818cf8; }
        .hero-title .accent { background: linear-gradient(90deg, #c0392b, #d4a017); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-subtitle { font-size: 1rem; color: #4a6fa5; font-weight: 500; max-width: 480px; line-height: 1.65; margin: 0 auto 14px; animation: fadeUp .7s .4s ease both; }
        .hero-org { font-size: .8rem; font-weight: 700; color: #7fb3e8; letter-spacing: .04em; margin-bottom: 44px; animation: fadeUp .7s .45s ease both; }
        .hero-org strong { color: #d4a017; }
        .cta-group { display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; animation: fadeUp .7s .5s ease both; }
        .cta-login { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; background: var(--indigo); color: #fff; border-radius: 14px; font-size: .9rem; font-weight: 800; text-decoration: none; border: none; cursor: pointer; font-family: var(--font); transition: all .2s; box-shadow: 0 6px 24px rgba(55,48,163,.45); letter-spacing: -.01em; }
        .cta-login:hover { background: #312e81; transform: translateY(-2px); box-shadow: 0 10px 32px rgba(55,48,163,.55); }
        .cta-register { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; background: transparent; color: #a5b4fc; border-radius: 14px; font-size: .9rem; font-weight: 800; text-decoration: none; border: 1.5px solid rgba(99,102,241,.3); cursor: pointer; font-family: var(--font); transition: all .2s; letter-spacing: -.01em; }
        .cta-register:hover { background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.6); color: #c7d2fe; transform: translateY(-2px); }
        .cta-manual-hero { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: rgba(212,160,23,.1); color: #fbbf24; border-radius: 14px; font-size: .9rem; font-weight: 700; text-decoration: none; border: 1.5px solid rgba(212,160,23,.3); cursor: pointer; font-family: var(--font); transition: all .2s; }
        .cta-manual-hero:hover { background: rgba(212,160,23,.18); border-color: rgba(212,160,23,.55); transform: translateY(-2px); }
        @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:none; } }
        .features { padding: 0 24px 80px; max-width: 1000px; margin: 0 auto; width: 100%; animation: fadeUp .7s .6s ease both; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media(max-width:720px) { .features-grid { grid-template-columns: 1fr; } }
        @media(min-width:480px) and (max-width:720px) { .features-grid { grid-template-columns: repeat(2,1fr); } }
        .feat-card { background: rgba(11,22,40,.7); border: 1px solid rgba(99,102,241,.1); border-radius: 18px; padding: 22px; backdrop-filter: blur(8px); transition: border-color .2s, transform .2s, box-shadow .2s; }
        .feat-card:hover { border-color: rgba(99,102,241,.3); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.3); }
        .feat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: .95rem; margin-bottom: 14px; flex-shrink: 0; }
        .feat-title { font-size: .88rem; font-weight: 800; color: #e2eaf8; margin-bottom: 6px; letter-spacing: -.01em; }
        .feat-desc { font-size: .75rem; color: #4a6fa5; line-height: 1.6; font-weight: 500; }
        .divider { display: flex; align-items: center; gap: 16px; max-width: 520px; margin: 0 auto 40px; animation: fadeUp .7s .55s ease both; }
        .divider-line { flex: 1; height: 1px; background: rgba(99,102,241,.15); }
        .divider-text { font-size: .62rem; font-weight: 700; color: #1e3a5f; letter-spacing: .12em; text-transform: uppercase; white-space: nowrap; }
        footer { border-top: 1px solid rgba(99,102,241,.08); padding: 22px 40px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; animation: fadeUp .6s .7s ease both; }
        .footer-left { font-size: .68rem; color: #1e3a5f; font-weight: 600; }
        .footer-left strong { color: #4a6fa5; }
        .footer-right { display: flex; align-items: center; gap: 8px; }
        .footer-badge { display: inline-flex; align-items: center; gap: 4px; font-size: .6rem; font-weight: 700; padding: 3px 10px; border-radius: 999px; background: rgba(55,48,163,.15); border: 1px solid rgba(99,102,241,.2); color: #4a6fa5; }
        @media(max-width:479px) { footer { padding: 16px 18px; justify-content: center; text-align: center; } .footer-right { justify-content: center; } }
        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .particle { position: absolute; width: 2px; height: 2px; background: rgba(129,140,248,.4); border-radius: 50%; animation: floatUp linear infinite; }
        @keyframes floatUp { 0% { transform: translateY(100vh) translateX(0); opacity:0;} 10% {opacity:1;} 90% {opacity:.5;} 100% {transform:translateY(-10vh) translateX(20px); opacity:0;} }

        /* ===== MANUAL MODAL ===== */
        .manual-overlay { position: fixed; inset: 0; z-index: 999; background: rgba(4, 10, 22, 0.85); backdrop-filter: blur(16px); display: flex; align-items: flex-start; justify-content: center; padding: 20px 16px 20px; opacity: 0; pointer-events: none; transition: opacity .3s ease; overflow-y: auto; }
        .manual-overlay.open { opacity: 1; pointer-events: all; }
        .manual-modal { background: #0c1729; border: 1px solid rgba(99,102,241,.2); border-radius: 20px; width: 100%; max-width: 860px; min-height: min-content; box-shadow: 0 32px 80px rgba(0,0,0,.6); transform: translateY(24px) scale(.97); transition: transform .35s cubic-bezier(.22,1,.36,1); overflow: hidden; margin: auto; }
        .manual-overlay.open .manual-modal { transform: translateY(0) scale(1); }
        .manual-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 28px; border-bottom: 1px solid rgba(99,102,241,.12); background: rgba(55,48,163,.08); position: sticky; top: 0; z-index: 10; backdrop-filter: blur(12px); gap: 12px; flex-wrap: wrap; }
        .manual-header-left { display: flex; align-items: center; gap: 12px; }
        .manual-header-icon { width: 36px; height: 36px; background: rgba(55,48,163,.25); border: 1px solid rgba(99,102,241,.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #818cf8; font-size: .9rem; flex-shrink: 0; }
        .manual-header-text h2 { font-size: .95rem; font-weight: 800; color: #e2eaf8; letter-spacing: -.02em; }
        .manual-header-text p { font-size: .68rem; color: #4a6fa5; margin-top: 1px; }
        .manual-close { width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(99,102,241,.2); background: transparent; color: #4a6fa5; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: .8rem; transition: all .2s; flex-shrink: 0; }
        .manual-close:hover { background: rgba(99,102,241,.12); color: #a5b4fc; border-color: rgba(99,102,241,.4); }
        .manual-toc { padding: 20px 28px; border-bottom: 1px solid rgba(99,102,241,.1); background: rgba(6,14,30,.4); }
        .manual-toc h3 { font-size: .68rem; font-weight: 700; color: #4a6fa5; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 12px; }
        .toc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 6px; }
        .toc-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(99,102,241,.08); background: rgba(55,48,163,.06); cursor: pointer; transition: all .18s; text-decoration: none; }
        .toc-item:hover { background: rgba(55,48,163,.15); border-color: rgba(99,102,241,.25); }
        .toc-num { font-size: .6rem; font-weight: 700; color: #818cf8; font-family: var(--mono); min-width: 24px; }
        .toc-label { font-size: .72rem; font-weight: 600; color: #7fb3e8; line-height: 1.3; }
        .manual-body { padding: 28px 28px 36px; }
        .manual-section { margin-bottom: 40px; }
        .manual-section:last-child { margin-bottom: 0; }
        .section-header { display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; background: linear-gradient(135deg, rgba(55,48,163,.15), rgba(55,48,163,.05)); border: 1px solid rgba(99,102,241,.15); margin-bottom: 20px; }
        .section-num { font-size: .65rem; font-weight: 700; color: #818cf8; font-family: var(--mono); background: rgba(55,48,163,.2); padding: 3px 8px; border-radius: 5px; white-space: nowrap; }
        .section-title { font-size: 1rem; font-weight: 800; color: #e2eaf8; letter-spacing: -.02em; }
        .section-sub { font-size: .7rem; color: #4a6fa5; margin-top: 1px; }
        .subsection { margin-bottom: 24px; }
        .subsection-title { font-size: .82rem; font-weight: 700; color: #c7d2fe; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .subsection-title::before { content: ''; width: 3px; height: 16px; background: linear-gradient(to bottom, #818cf8, #4338ca); border-radius: 3px; flex-shrink: 0; }
        .callout { border-radius: 10px; padding: 12px 16px; margin: 12px 0; display: flex; gap: 10px; align-items: flex-start; font-size: .78rem; line-height: 1.55; }
        .callout-icon { font-size: .85rem; flex-shrink: 0; margin-top: 1px; }
        .callout-text { flex: 1; }
        .callout-text strong { display: block; margin-bottom: 3px; font-size: .74rem; }
        .callout.info { background: rgba(55,48,163,.1); border: 1px solid rgba(99,102,241,.2); color: #a5b4fc; }
        .callout.info .callout-icon { color: #818cf8; }
        .callout.warning { background: rgba(192,57,43,.08); border: 1px solid rgba(192,57,43,.2); color: #fca5a5; }
        .callout.warning .callout-icon { color: #f87171; }
        .callout.tip { background: rgba(212,160,23,.08); border: 1px solid rgba(212,160,23,.2); color: #fcd34d; }
        .callout.tip .callout-icon { color: #fbbf24; }
        .callout.ai { background: rgba(16,185,129,.07); border: 1px solid rgba(16,185,129,.18); color: #6ee7b7; }
        .callout.ai .callout-icon { color: #34d399; }
        .steps { display: flex; flex-direction: column; gap: 8px; }
        .step { display: flex; gap: 12px; align-items: flex-start; padding: 10px 14px; background: rgba(6,14,30,.5); border: 1px solid rgba(99,102,241,.08); border-radius: 10px; }
        .step-num { width: 22px; height: 22px; min-width: 22px; border-radius: 50%; background: rgba(55,48,163,.3); border: 1px solid rgba(99,102,241,.3); display: flex; align-items: center; justify-content: center; font-size: .65rem; font-weight: 700; color: #818cf8; font-family: var(--mono); margin-top: 1px; }
        .step-text { font-size: .78rem; color: #94a3b8; line-height: 1.5; }
        .step-text strong { color: #c7d2fe; }
        .status-grid { display: grid; gap: 8px; }
        .status-item { display: flex; gap: 12px; align-items: flex-start; padding: 10px 14px; background: rgba(6,14,30,.5); border: 1px solid rgba(99,102,241,.08); border-radius: 10px; }
        .status-badge { font-size: .65rem; font-weight: 700; padding: 3px 9px; border-radius: 6px; white-space: nowrap; font-family: var(--mono); flex-shrink: 0; }
        .status-badge.pending { background: rgba(212,160,23,.15); color: #fbbf24; }
        .status-badge.approved { background: rgba(16,185,129,.12); color: #34d399; }
        .status-badge.declined { background: rgba(192,57,43,.12); color: #f87171; }
        .status-badge.claimed { background: rgba(55,48,163,.2); color: #818cf8; }
        .status-badge.expired { background: rgba(100,116,139,.15); color: #94a3b8; }
        .status-badge.available { background: rgba(16,185,129,.12); color: #34d399; }
        .status-badge.maintenance { background: rgba(212,160,23,.15); color: #fbbf24; }
        .status-badge.outoforder { background: rgba(192,57,43,.12); color: #f87171; }
        .role-table { width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden; border: 1px solid rgba(99,102,241,.12); }
        .role-table th { background: rgba(55,48,163,.18); color: #818cf8; font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 10px 14px; text-align: left; }
        .role-table td { padding: 10px 14px; font-size: .76rem; color: #94a3b8; border-top: 1px solid rgba(99,102,241,.07); }
        .role-table tr:hover td { background: rgba(55,48,163,.05); }
        .role-name { font-weight: 700; color: #c7d2fe; }
        .check-yes { color: #34d399; } .check-no { color: #475569; }
        .log-table { width: 100%; border-collapse: collapse; border-radius: 10px; overflow: hidden; border: 1px solid rgba(99,102,241,.12); }
        .log-table th { background: rgba(55,48,163,.18); color: #818cf8; font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 10px 14px; text-align: left; }
        .log-table td { padding: 10px 14px; font-size: .76rem; color: #94a3b8; border-top: 1px solid rgba(99,102,241,.07); }
        .log-table td:first-child { font-weight: 700; color: #c7d2fe; }
        .trouble-grid { display: flex; flex-direction: column; gap: 10px; }
        .trouble-item { padding: 14px 16px; background: rgba(6,14,30,.5); border: 1px solid rgba(99,102,241,.09); border-radius: 10px; }
        .trouble-q { font-size: .78rem; font-weight: 700; color: #c7d2fe; margin-bottom: 6px; display: flex; gap: 8px; align-items: flex-start; }
        .trouble-q::before { content: 'Q'; font-size: .6rem; background: rgba(192,57,43,.2); color: #f87171; padding: 2px 6px; border-radius: 4px; flex-shrink: 0; margin-top: 1px; font-family: var(--mono); }
        .trouble-a { font-size: .75rem; color: #64748b; line-height: 1.55; padding-left: 26px; }
        .pwa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media(max-width: 500px) { .pwa-grid { grid-template-columns: 1fr; } }
        .pwa-card { background: rgba(6,14,30,.5); border: 1px solid rgba(99,102,241,.1); border-radius: 12px; padding: 14px; }
        .pwa-card-title { font-size: .75rem; font-weight: 700; color: #818cf8; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .manual-contact { margin-top: 32px; padding: 18px 20px; background: rgba(55,48,163,.08); border: 1px solid rgba(99,102,241,.15); border-radius: 12px; text-align: center; }
        .manual-contact p { font-size: .78rem; color: #4a6fa5; line-height: 1.6; }
        .manual-contact a { color: #818cf8; font-weight: 700; text-decoration: none; }
        .manual-contact a:hover { color: #a5b4fc; }
        @media(max-width: 600px) { .manual-header { padding: 16px 18px; } .manual-toc { padding: 16px 18px; } .manual-body { padding: 20px 18px 28px; } .section-header { padding: 12px 14px; } .step { padding: 9px 12px; } .role-table th, .role-table td { padding: 8px 10px; } }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>
    <div class="bg-orb"></div>
    <div class="particles" id="particles"></div>
    <div class="page">
        <nav>
            <a href="#" class="nav-brand">
                <!-- Replace with your actual SK logo -->
                <div style="width:40px;height:40px;border-radius:50%;background:rgba(55,48,163,.3);border:2px solid rgba(212,160,23,.4);display:flex;align-items:center;justify-content:center;box-shadow:0 0 12px rgba(212,160,23,.25);">
                    <i class="fa-solid fa-graduation-cap" style="color:#d4a017;font-size:.9rem;"></i>
                </div>
                <div class="nav-brand-text">
                    <div class="nav-brand-name">edu<em>Reserve</em><span style="color:#d4a017;">.</span></div>
                    <div class="nav-brand-sub">E-Learning Resource Center · Brgy. F. De Jesus</div>
                </div>
            </a>
            <div class="nav-actions">
                <button class="btn-manual" onclick="openManual()">
                    <i class="fa-solid fa-book" style="font-size:.75rem;"></i>
                    <span class="label">User Manual</span>
                </button>
                <a href="/login" class="btn-ghost">
                    <i class="fa-solid fa-right-to-bracket" style="font-size:.75rem;"></i> <span class="label">Sign In</span>
                </a>
                <a href="/register" class="btn-primary">
                    <i class="fa-solid fa-user-plus" style="font-size:.75rem;"></i> Register
                </a>
            </div>
        </nav>

        <section class="hero">
            <div class="logo-ring">
                <div class="logo-img-wrap" style="display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-graduation-cap" style="color:#d4a017;font-size:2.8rem;"></i>
                </div>
            </div>
            <div class="eyebrow-pill">
                <span class="dot"></span>
                Sangguniang Kabataan · AI-Powered Reservation System
            </div>
            <h1 class="hero-title">edu<em>Reserve</em><span class="accent">.</span></h1>
            <p class="hero-subtitle">Reserve e-learning resources, access the library, and manage community facilities — powered by AI and built for the youth of Barangay F. De Jesus.</p>
            <p class="hero-org"><strong>Sangguniang Kabataan</strong> · Brgy. F. De Jesus · Unisan, Quezon</p>
            <div class="cta-group">
                <a href="/login" class="cta-login">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In
                </a>
                <a href="/register" class="cta-register">
                    <i class="fa-solid fa-user-plus"></i> Create Account
                </a>
                <button class="cta-manual-hero" onclick="openManual()">
                    <i class="fa-solid fa-book-open"></i> User Manual
                </button>
            </div>
        </section>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">What you can do</span>
            <div class="divider-line"></div>
        </div>

        <section class="features">
            <div class="features-grid">
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(55,48,163,.18);"><i class="fa-solid fa-calendar-check" style="color:#818cf8;"></i></div>
                    <div class="feat-title">Reserve E-Learning Resources</div>
                    <div class="feat-desc">Book computers, workstations, and barangay facilities online with real-time availability and instant e-ticket generation.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(16,185,129,.12);"><i class="fa-solid fa-book-open" style="color:#34d399;"></i></div>
                    <div class="feat-title">AI-Powered Library</div>
                    <div class="feat-desc">Browse, search, and borrow books from the SK library. Powered by RAG (Retrieval-Augmented Generation) for smart, accurate searches.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(212,160,23,.12);"><i class="fa-solid fa-qrcode" style="color:#fbbf24;"></i></div>
                    <div class="feat-title">E-Ticket System</div>
                    <div class="feat-desc">Get a scannable QR e-ticket for every approved reservation. Paperless, secure, and verifiable at the facility entrance.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(192,57,43,.12);"><i class="fa-solid fa-shield-halved" style="color:#f87171;"></i></div>
                    <div class="feat-title">SK Officer Portal</div>
                    <div class="feat-desc">Officers can approve reservations, scan QR tickets, manage walk-ins, and monitor live resource usage in real time.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(139,92,246,.12);"><i class="fa-solid fa-robot" style="color:#c084fc;"></i></div>
                    <div class="feat-title">RAG AI Assistant</div>
                    <div class="feat-desc">Ask questions about available resources, library books, and policies. The AI assistant retrieves accurate answers from local data.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(6,182,212,.1);"><i class="fa-solid fa-users" style="color:#22d3ee;"></i></div>
                    <div class="feat-title">Community-First Design</div>
                    <div class="feat-desc">Built with equity and fairness in mind — equal access to e-learning resources for every resident of Brgy. F. De Jesus.</div>
                </div>
            </div>
        </section>

        <footer>
            <div class="footer-left">&copy; 2026 <strong>EduReserve · SK Brgy. F. De Jesus</strong> · All rights reserved.</div>
            <div class="footer-right">
                <span class="footer-badge"><i class="fa-solid fa-shield-halved" style="font-size:.55rem;"></i> Secure Portal</span>
                <span class="footer-badge"><i class="fa-solid fa-robot" style="font-size:.55rem;"></i> AI-Powered</span>
                <span class="footer-badge"><i class="fa-solid fa-mobile" style="font-size:.55rem;"></i> PWA</span>
                <button class="footer-badge" onclick="openManual()" style="cursor:pointer;border:1px solid rgba(212,160,23,.3);background:rgba(212,160,23,.08);color:#fbbf24;">
                    <i class="fa-solid fa-book" style="font-size:.55rem;"></i> Manual
                </button>
            </div>
        </footer>
    </div>

    <!-- ===== USER MANUAL MODAL ===== -->
    <div class="manual-overlay" id="manualOverlay" onclick="handleOverlayClick(event)">
        <div class="manual-modal" id="manualModal">
            <div class="manual-header">
                <div class="manual-header-left">
                    <div class="manual-header-icon"><i class="fa-solid fa-book"></i></div>
                    <div class="manual-header-text">
                        <h2>EduReserve · SK E-Learning Resource Reservation System</h2>
                        <p>User Manual · Version 1.0 · 2026 · Brgy. F. De Jesus, Unisan, Quezon</p>
                    </div>
                </div>
                <button class="manual-close" onclick="closeManual()" aria-label="Close manual">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="manual-toc">
                <h3>Jump to Section</h3>
                <div class="toc-grid">
                    <a class="toc-item" href="#intro" onclick="scrollTo('intro')"><span class="toc-num">00</span><span class="toc-label">Introduction & Overview</span></a>
                    <a class="toc-item" href="#s01" onclick="scrollTo('s01')"><span class="toc-num">01</span><span class="toc-label">Getting Started</span></a>
                    <a class="toc-item" href="#s02" onclick="scrollTo('s02')"><span class="toc-num">02</span><span class="toc-label">Making Reservations</span></a>
                    <a class="toc-item" href="#s03" onclick="scrollTo('s03')"><span class="toc-num">03</span><span class="toc-label">SK Officer Guide</span></a>
                    <a class="toc-item" href="#s04" onclick="scrollTo('s04')"><span class="toc-num">04</span><span class="toc-label">Chairman / Admin Guide</span></a>
                    <a class="toc-item" href="#s05" onclick="scrollTo('s05')"><span class="toc-num">05</span><span class="toc-label">AI Library (RAG)</span></a>
                    <a class="toc-item" href="#s06" onclick="scrollTo('s06')"><span class="toc-num">06</span><span class="toc-label">Profile & PWA Install</span></a>
                    <a class="toc-item" href="#s07" onclick="scrollTo('s07')"><span class="toc-num">07</span><span class="toc-label">Troubleshooting</span></a>
                </div>
            </div>
            <div class="manual-body">
                <div class="manual-section" id="intro">
                    <div class="section-header">
                        <span class="section-num">INTRO</span>
                        <div><div class="section-title">Introduction</div><div class="section-sub">Welcome & System Overview</div></div>
                    </div>
                    <p style="font-size:.8rem;color:#64748b;line-height:1.7;margin-bottom:16px;">Welcome to <strong style="color:#c7d2fe;">EduReserve</strong> — the AI-Assisted Progressive Web Application for the Online Reservation System of the E-Learning Resource Center of Barangay F. De Jesus, Unisan, Quezon. This system allows residents, SK Officers, and administrators to reserve e-learning resources (computers, workstations, and facilities), access the AI-powered library via RAG, and manage the entire process digitally — replacing manual, paper-based procedures.</p>
                    <div class="callout info" style="margin-bottom:16px;">
                        <div class="callout-icon"><i class="fa-solid fa-link"></i></div>
                        <div class="callout-text"><strong>Quick Access</strong>Access EduReserve anytime at: <a href="https://reservation-k2eg.onrender.com" style="color:#818cf8;font-weight:700;">https://reservation-k2eg.onrender.com</a></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">User Roles & Access Levels</div>
                        <table class="role-table">
                            <thead><tr><th>Role</th><th>Access Level</th><th>Can Approve</th><th>Can Manage SK</th></tr></thead>
                            <tbody>
                                <tr><td><span class="role-name">Resident / User</span></td><td>Limited</td><td><span class="check-no">✗</span></td><td><span class="check-no">✗</span></td></tr>
                                <tr><td><span class="role-name">SK Officer</span></td><td>Moderate</td><td><span class="check-yes">✓ Reservations</span></td><td><span class="check-no">✗</span></td></tr>
                                <tr><td><span class="role-name">Chairman (Admin)</span></td><td>Full</td><td><span class="check-yes">✓ All</span></td><td><span class="check-yes">✓</span></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">System Requirements</div>
                        <div class="steps">
                            <div class="step"><div class="step-num"><i class="fa-solid fa-globe" style="font-size:.55rem;"></i></div><div class="step-text">A modern web browser — <strong>Chrome, Firefox, Edge, or Safari</strong></div></div>
                            <div class="step"><div class="step-num"><i class="fa-solid fa-wifi" style="font-size:.55rem;"></i></div><div class="step-text">An active <strong>internet connection</strong></div></div>
                            <div class="step"><div class="step-num"><i class="fa-solid fa-envelope" style="font-size:.55rem;"></i></div><div class="step-text">A <strong>registered email address</strong> for account verification</div></div>
                            <div class="step"><div class="step-num"><i class="fa-solid fa-mobile" style="font-size:.55rem;"></i></div><div class="step-text">Install as a PWA on mobile: tap <strong>Add to Home Screen</strong> from your browser</div></div>
                        </div>
                    </div>
                </div>

                <div class="manual-section" id="s01">
                    <div class="section-header">
                        <span class="section-num">01</span>
                        <div><div class="section-title">Getting Started</div><div class="section-sub">Account Registration & Email Verification</div></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">1.1 Creating Your Account</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Visit the EduReserve website and click <strong>Create an Account</strong> on the login page.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Fill in your <strong>Full Name, Email Address</strong>, and select your <strong>Role</strong> (Resident or SK Officer).</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Create a strong password with at least <strong>8 characters, one uppercase letter, and one number</strong>.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Click <strong>Sign Up</strong> to submit your registration.</div></div>
                            <div class="step"><div class="step-num">5</div><div class="step-text">Check your email inbox for a <strong>verification link</strong> from EduReserve.</div></div>
                            <div class="step"><div class="step-num">6</div><div class="step-text">Click <strong>Verify Email Address</strong> in the email to activate your account.</div></div>
                        </div>
                        <div class="callout warning" style="margin-top:12px;">
                            <div class="callout-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <div class="callout-text"><strong>Important for SK Officers</strong>After verifying your email, your account status will be <em>Pending</em>. You cannot log in until the Barangay Chairman approves your SK account. You will be notified by email.</div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">1.2 Logging In</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to the login page and enter your <strong>registered email address</strong>.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Enter your <strong>password</strong>.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Click <strong>Sign In</strong> to access your EduReserve dashboard.</div></div>
                        </div>
                        <div class="callout tip" style="margin-top:12px;">
                            <div class="callout-icon"><i class="fa-solid fa-lock"></i></div>
                            <div class="callout-text"><strong>Forgot Password?</strong>Click <em>Forgot Password?</em> on the login page. Enter your email to receive a 6-digit OTP, verify it, then set a new password.</div>
                        </div>
                    </div>
                </div>

                <div class="manual-section" id="s02">
                    <div class="section-header">
                        <span class="section-num">02</span>
                        <div><div class="section-title">Making Reservations</div><div class="section-sub">For Residents & Community Members</div></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">2.1 Creating a Reservation</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Click <strong>Reserve</strong> from the sidebar or dashboard.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Select the <strong>Resource</strong> you need (e.g., Computer Lab, WiFi workstation).</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Choose your <strong>Reservation Date</strong>, <strong>Start Time</strong>, and <strong>End Time</strong>.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Enter the <strong>Purpose</strong> of your reservation.</div></div>
                            <div class="step"><div class="step-num">5</div><div class="step-text">If a specific PC is needed, select the <strong>PC number</strong> from the availability list.</div></div>
                            <div class="step"><div class="step-num">6</div><div class="step-text">Click <strong>Submit Request</strong> — your request goes to an SK Officer for review.</div></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">2.2 Reservation Statuses</div>
                        <div class="status-grid">
                            <div class="status-item"><span class="status-badge pending">Pending</span><span class="status-desc">Awaiting approval from SK Officer or Chairman.</span></div>
                            <div class="status-item"><span class="status-badge approved">Approved</span><span class="status-desc">Confirmed. Your QR e-ticket is ready to download.</span></div>
                            <div class="status-item"><span class="status-badge declined">Declined</span><span class="status-desc">Reservation was not approved. You may resubmit.</span></div>
                            <div class="status-item"><span class="status-badge claimed">Claimed</span><span class="status-desc">Your ticket was scanned and your session is active.</span></div>
                            <div class="status-item"><span class="status-badge expired">Expired</span><span class="status-desc">Reservation date passed without use.</span></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">2.3 Using Your E-Ticket</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>My Slots</strong> and find your <em>Approved</em> reservation.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Click the <strong>eye icon</strong> to view reservation details.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Your <strong>QR Code E-Ticket</strong> appears. Click <strong>Download E-Ticket</strong> to save it.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Show the QR code to the <strong>SK Officer</strong> at the e-learning center.</div></div>
                            <div class="step"><div class="step-num">5</div><div class="step-text">The officer scans it and your reservation is marked <strong>Claimed</strong>.</div></div>
                        </div>
                        <div class="callout tip" style="margin-top:12px;">
                            <div class="callout-icon"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="callout-text"><strong>Tip</strong>Save your e-ticket image to your phone gallery before your reservation date — accessible even without internet.</div>
                        </div>
                    </div>
                </div>

                <div class="manual-section" id="s03">
                    <div class="section-header">
                        <span class="section-num">03</span>
                        <div><div class="section-title">SK Officer Guide</div><div class="section-sub">Managing Reservations & Scanning Tickets</div></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">3.1 Approving & Declining Reservations</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>Reservations</strong> or <strong>User Requests</strong> from the sidebar.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Select a reservation with <strong>Pending</strong> status.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Review the details, then click <strong>Approve</strong> or <strong>Decline</strong>.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">The resident is notified automatically by email or in-app notification.</div></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">3.2 QR Code Scanner</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>Scanner</strong> from the sidebar.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Allow <strong>camera access</strong> when prompted.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Point the camera at the resident's <strong>QR e-ticket</strong>.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">The system validates and marks the reservation as <strong>Claimed</strong> automatically.</div></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">3.3 Walk-in Reservations</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>New Reservation</strong> from the dashboard.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Select <strong>Walk-in / Visitor</strong> as the type.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Fill in the resident's details and resource needed.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Submit — the reservation is <strong>auto-approved</strong> for immediate use.</div></div>
                        </div>
                    </div>
                </div>

                <div class="manual-section" id="s04">
                    <div class="section-header">
                        <span class="section-num">04</span>
                        <div><div class="section-title">Chairman / Admin Guide</div><div class="section-sub">Full System Administration</div></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">4.1 Approving SK Officer Accounts</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>Manage SK</strong> from the sidebar.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Click on a pending SK account to review their information.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Click <strong>Approve</strong> or <strong>Reject</strong>. The officer is notified by email automatically.</div></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">4.2 Resource & PC Status Management</div>
                        <div class="status-grid">
                            <div class="status-item"><span class="status-badge available">Available</span><span class="status-desc">PC or resource is ready for reservation.</span></div>
                            <div class="status-item"><span class="status-badge maintenance">Maintenance</span><span class="status-desc">Currently undergoing repairs — unavailable.</span></div>
                            <div class="status-item"><span class="status-badge outoforder">Out of Order</span><span class="status-desc">Non-functional — cannot be reserved.</span></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">4.3 System Logs</div>
                        <table class="log-table">
                            <thead><tr><th>Log Type</th><th>What It Shows</th></tr></thead>
                            <tbody>
                                <tr><td>Login Logs</td><td>All login/logout events with timestamps and user roles.</td></tr>
                                <tr><td>Activity Logs</td><td>All reservation actions: created, approved, declined, claimed, printed.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="manual-section" id="s05">
                    <div class="section-header">
                        <span class="section-num">05</span>
                        <div><div class="section-title">AI Library — RAG System</div><div class="section-sub">Smart Book Search & Borrowing</div></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">5.1 For Residents — Borrowing Books</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>Library</strong> from the sidebar.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Use the <strong>AI search bar</strong> to find books by title, author, genre, or topic.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Click <strong>Borrow</strong> on the book you want and wait for SK Officer approval.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Track your borrowing status under <strong>My Borrowings</strong>.</div></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">5.2 For Officers & Admin — Adding Books</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Go to <strong>Library → Add Book</strong>.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Upload the book's PDF file.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">Click <strong>Extract with AI</strong> — the RAG system auto-fills title, author, genre, and summary.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Review and click <strong>Save Book</strong> to add it to the library.</div></div>
                        </div>
                        <div class="callout ai" style="margin-top:12px;">
                            <div class="callout-icon"><i class="fa-solid fa-robot"></i></div>
                            <div class="callout-text"><strong>RAG AI Assistant</strong>EduReserve uses Retrieval-Augmented Generation (RAG) to power intelligent book search and automated metadata extraction — giving fast, accurate results from the local library collection.</div>
                        </div>
                    </div>
                </div>

                <div class="manual-section" id="s06">
                    <div class="section-header">
                        <span class="section-num">06</span>
                        <div><div class="section-title">Profile & PWA Installation</div><div class="section-sub">Account Settings & App Install</div></div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">6.1 Updating Your Profile</div>
                        <div class="steps">
                            <div class="step"><div class="step-num">1</div><div class="step-text">Click <strong>Profile</strong> from the sidebar.</div></div>
                            <div class="step"><div class="step-num">2</div><div class="step-text">Update your <strong>Name, Email, or Phone number</strong>.</div></div>
                            <div class="step"><div class="step-num">3</div><div class="step-text">To change your password, enter a <strong>new password</strong> in the Password field.</div></div>
                            <div class="step"><div class="step-num">4</div><div class="step-text">Click <strong>Save Changes</strong>.</div></div>
                        </div>
                    </div>
                    <div class="subsection">
                        <div class="subsection-title">6.2 Installing EduReserve as an App (PWA)</div>
                        <div class="pwa-grid">
                            <div class="pwa-card">
                                <div class="pwa-card-title"><i class="fa-brands fa-android"></i> Android (Chrome)</div>
                                <div class="steps">
                                    <div class="step"><div class="step-num">1</div><div class="step-text">Open in <strong>Chrome</strong>, tap <strong>⋮ menu</strong>.</div></div>
                                    <div class="step"><div class="step-num">2</div><div class="step-text">Tap <strong>Add to Home Screen</strong> or <strong>Install App</strong>.</div></div>
                                    <div class="step"><div class="step-num">3</div><div class="step-text">Tap <strong>Install</strong>. EduReserve icon appears on home screen.</div></div>
                                </div>
                            </div>
                            <div class="pwa-card">
                                <div class="pwa-card-title"><i class="fa-brands fa-apple"></i> iPhone (Safari)</div>
                                <div class="steps">
                                    <div class="step"><div class="step-num">1</div><div class="step-text">Open in <strong>Safari</strong>, tap the <strong>Share button</strong>.</div></div>
                                    <div class="step"><div class="step-num">2</div><div class="step-text">Tap <strong>Add to Home Screen</strong>.</div></div>
                                    <div class="step"><div class="step-num">3</div><div class="step-text">Tap <strong>Add</strong>. EduReserve icon appears on home screen.</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="manual-section" id="s07">
                    <div class="section-header">
                        <span class="section-num">07</span>
                        <div><div class="section-title">Troubleshooting</div><div class="section-sub">Common Issues & Solutions</div></div>
                    </div>
                    <div class="trouble-grid">
                        <div class="trouble-item"><div class="trouble-q">I did not receive the verification email.</div><div class="trouble-a">Check your <strong>Spam or Junk</strong> folder. If not found, try registering again or contact the admin.</div></div>
                        <div class="trouble-item"><div class="trouble-q">My SK account says Pending after email verification.</div><div class="trouble-a">The Barangay Chairman must approve your SK account. You will be <strong>notified by email</strong> once a decision is made.</div></div>
                        <div class="trouble-item"><div class="trouble-q">The QR scanner is not working.</div><div class="trouble-a"><strong>Allow camera access</strong> in your browser settings. Ensure good lighting when scanning.</div></div>
                        <div class="trouble-item"><div class="trouble-q">The page shows a 404 error after the site wakes up.</div><div class="trouble-a"><strong>Refresh the page</strong> and navigate back to the login page. The server may have been sleeping.</div></div>
                        <div class="trouble-item"><div class="trouble-q">My reservation is stuck on Pending for too long.</div><div class="trouble-a">Contact an <strong>SK Officer or the Barangay Chairman</strong> to review your request.</div></div>
                        <div class="trouble-item"><div class="trouble-q">The AI library search returns no results.</div><div class="trouble-a">Try different keywords — use <strong>topic or subject</strong> words instead of exact titles. The RAG AI searches by meaning, not exact match.</div></div>
                        <div class="trouble-item"><div class="trouble-q">I forgot my password.</div><div class="trouble-a">Use the <strong>Forgot Password</strong> link on the login page to reset via OTP.</div></div>
                    </div>
                    <div class="manual-contact">
                        <p><i class="fa-solid fa-phone" style="color:#818cf8;margin-right:6px;"></i><strong style="color:#c7d2fe;">Need Help?</strong> Contact the Barangay F. De Jesus office in Unisan, Quezon or reach your SK Officers directly.<br>System: <a href="https://reservation-z2uk.onrender.com">https://reservation-z2uk.onrender.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 28; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.bottom = '-10px';
            p.style.width = (Math.random() * 2 + 1) + 'px';
            p.style.height = p.style.width;
            p.style.opacity = Math.random() * 0.5 + 0.1;
            p.style.animationDuration = (Math.random() * 18 + 12) + 's';
            p.style.animationDelay = (Math.random() * 14) + 's';
            const colors = ['rgba(129,140,248,.5)','rgba(192,57,43,.4)','rgba(212,160,23,.4)','rgba(99,102,241,.5)'];
            p.style.background = colors[Math.floor(Math.random() * colors.length)];
            container.appendChild(p);
        }

        // Manual modal
        const overlay = document.getElementById('manualOverlay');
        function openManual() { overlay.classList.add('open'); document.body.style.overflow = 'hidden'; }
        function closeManual() { overlay.classList.remove('open'); document.body.style.overflow = ''; }
        function handleOverlayClick(e) { if (e.target === overlay) closeManual(); }
        function scrollTo(id) { const el = document.getElementById(id); if (el) { setTimeout(() => { el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 50); } return false; }
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeManual(); });
    </script>
</body>
</html>