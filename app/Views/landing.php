<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>mySpace · Sangguniang Kabataan — Brgy. F. De Jesus</title>
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

        body {
            background: #060e1e;
            color: #e2eaf8;
            min-height: 100vh;
            position: relative;
        }

        /* ══ BACKGROUND ══ */
        .bg-mesh {
            position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none;
        }
        .bg-mesh::before {
            content: ''; position: absolute;
            width: 900px; height: 900px; top: -300px; left: -200px;
            background: radial-gradient(circle, rgba(55,48,163,.35) 0%, transparent 70%);
            animation: driftA 18s ease-in-out infinite alternate;
        }
        .bg-mesh::after {
            content: ''; position: absolute;
            width: 700px; height: 700px; bottom: -200px; right: -150px;
            background: radial-gradient(circle, rgba(192,57,43,.25) 0%, transparent 70%);
            animation: driftB 14s ease-in-out infinite alternate;
        }
        .bg-orb {
            position: fixed; width: 500px; height: 500px;
            top: 40%; left: 55%; transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(212,160,23,.12) 0%, transparent 65%);
            animation: driftC 20s ease-in-out infinite alternate;
            pointer-events: none; z-index: 0;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(99,102,241,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        @keyframes driftA { from { transform: translate(0,0) scale(1); } to { transform: translate(60px, 80px) scale(1.15); } }
        @keyframes driftB { from { transform: translate(0,0) scale(1); } to { transform: translate(-40px, -60px) scale(1.1); } }
        @keyframes driftC { from { transform: translate(-50%,-50%) scale(1); } to { transform: translate(-45%,-55%) scale(1.2); } }

        /* ══ LAYOUT ══ */
        .page {
            position: relative; z-index: 1;
            min-height: 100vh; display: flex; flex-direction: column;
        }

        /* ══ NAV ══ */
        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 40px;
            border-bottom: 1px solid rgba(99,102,241,.1);
            backdrop-filter: blur(12px);
            background: rgba(6,14,30,.6);
            position: sticky; top: 0; z-index: 100;
            animation: slideDown .6s ease both;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: none; } }

        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(212,160,23,.4); box-shadow: 0 0 12px rgba(212,160,23,.25); }
        .nav-brand-text { line-height: 1.1; }
        .nav-brand-name { font-size: 1.1rem; font-weight: 800; color: #e2eaf8; letter-spacing: -.03em; }
        .nav-brand-name em { font-style: normal; color: #818cf8; }
        .nav-brand-sub  { font-size: .6rem; font-weight: 600; color: #4a6fa5; letter-spacing: .06em; text-transform: uppercase; }

        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .btn-ghost {
            padding: 9px 20px; border-radius: 10px; font-size: .82rem; font-weight: 700;
            border: 1px solid rgba(99,102,241,.25); background: transparent; color: #a5b4fc;
            cursor: pointer; font-family: var(--font); text-decoration: none;
            transition: all .2s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-ghost:hover { background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.5); color: #c7d2fe; }

        .btn-primary {
            padding: 9px 22px; border-radius: 10px; font-size: .82rem; font-weight: 800;
            border: none; background: var(--indigo); color: #fff; cursor: pointer;
            font-family: var(--font); text-decoration: none; transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px;
            box-shadow: 0 4px 14px rgba(55,48,163,.4);
        }
        .btn-primary:hover { background: #312e81; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(55,48,163,.5); }

        @media(max-width:479px) { nav { padding: 14px 18px; } .nav-brand-sub { display: none; } }

        /* ══ HERO ══ */
        .hero {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 60px 24px 80px; text-align: center;
        }

        .logo-ring {
            position: relative; width: 140px; height: 140px;
            margin: 0 auto 36px; animation: fadeUp .8s .1s ease both;
        }
        .logo-ring::before {
            content: ''; position: absolute; inset: -6px; border-radius: 50%;
            background: conic-gradient(from 0deg, #c0392b, #d4a017, #3730a3, #c0392b);
            animation: rotateBorder 6s linear infinite; z-index: 0;
        }
        .logo-ring::after {
            content: ''; position: absolute; inset: -6px; border-radius: 50%;
            background: conic-gradient(from 0deg, #c0392b, #d4a017, #3730a3, #c0392b);
            filter: blur(10px); opacity: .5;
            animation: rotateBorder 6s linear infinite; z-index: 0;
        }
        @keyframes rotateBorder { to { transform: rotate(360deg); } }
        .logo-img-wrap {
            position: relative; z-index: 1; width: 100%; height: 100%;
            border-radius: 50%; overflow: hidden; background: #060e1e; padding: 4px;
        }
        .logo-img-wrap img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block; }

        .eyebrow-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(55,48,163,.18); border: 1px solid rgba(99,102,241,.28);
            color: #a5b4fc; font-size: .65rem; font-weight: 700;
            letter-spacing: .14em; text-transform: uppercase;
            padding: 5px 14px; border-radius: 999px; margin-bottom: 22px;
            animation: fadeUp .7s .2s ease both;
        }
        .eyebrow-pill span.dot { width: 6px; height: 6px; border-radius: 50%; background: #818cf8; display: inline-block; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.8); } }

        .hero-title {
            font-size: clamp(2.8rem, 7vw, 5rem); font-weight: 800;
            letter-spacing: -.05em; line-height: 1; color: #e2eaf8;
            margin-bottom: 10px; animation: fadeUp .7s .3s ease both;
        }
        .hero-title em { font-style: normal; color: #818cf8; }
        .hero-title .accent {
            background: linear-gradient(90deg, #c0392b, #d4a017);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero-subtitle {
            font-size: 1rem; color: #4a6fa5; font-weight: 500;
            max-width: 480px; line-height: 1.65; margin: 0 auto 14px;
            animation: fadeUp .7s .4s ease both;
        }
        .hero-org {
            font-size: .8rem; font-weight: 700; color: #7fb3e8;
            letter-spacing: .04em; margin-bottom: 44px;
            animation: fadeUp .7s .45s ease both;
        }
        .hero-org strong { color: #d4a017; }

        .cta-group {
            display: flex; align-items: center; justify-content: center;
            gap: 14px; flex-wrap: wrap; animation: fadeUp .7s .5s ease both;
        }
        .cta-login {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px; background: var(--indigo); color: #fff;
            border-radius: 14px; font-size: .9rem; font-weight: 800;
            text-decoration: none; border: none; cursor: pointer;
            font-family: var(--font); transition: all .2s;
            box-shadow: 0 6px 24px rgba(55,48,163,.45); letter-spacing: -.01em;
        }
        .cta-login:hover { background: #312e81; transform: translateY(-2px); box-shadow: 0 10px 32px rgba(55,48,163,.55); }

        .cta-register {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px; background: transparent; color: #a5b4fc;
            border-radius: 14px; font-size: .9rem; font-weight: 800;
            text-decoration: none; border: 1.5px solid rgba(99,102,241,.3);
            cursor: pointer; font-family: var(--font); transition: all .2s; letter-spacing: -.01em;
        }
        .cta-register:hover { background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.6); color: #c7d2fe; transform: translateY(-2px); }

        .cta-manual {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px; background: rgba(212,160,23,.1); color: #fbbf24;
            border-radius: 14px; font-size: .9rem; font-weight: 800;
            text-decoration: none; border: 1.5px solid rgba(212,160,23,.3);
            cursor: pointer; font-family: var(--font); transition: all .2s; letter-spacing: -.01em;
        }
        .cta-manual:hover { background: rgba(212,160,23,.18); border-color: rgba(212,160,23,.6); color: #fde68a; transform: translateY(-2px); }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

        /* ══ FEATURE CARDS ══ */
        .features { padding: 0 24px 80px; max-width: 1000px; margin: 0 auto; width: 100%; animation: fadeUp .7s .6s ease both; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media(max-width:720px) { .features-grid { grid-template-columns: 1fr; } }
        @media(min-width:480px) and (max-width:720px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
        .feat-card {
            background: rgba(11,22,40,.7); border: 1px solid rgba(99,102,241,.1);
            border-radius: 18px; padding: 22px; backdrop-filter: blur(8px);
            transition: border-color .2s, transform .2s, box-shadow .2s;
        }
        .feat-card:hover { border-color: rgba(99,102,241,.3); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.3); }
        .feat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: .95rem; margin-bottom: 14px; flex-shrink: 0; }
        .feat-title { font-size: .88rem; font-weight: 800; color: #e2eaf8; margin-bottom: 6px; letter-spacing: -.01em; }
        .feat-desc  { font-size: .75rem; color: #4a6fa5; line-height: 1.6; font-weight: 500; }

        /* ══ MANUAL BANNER ══ */
        .manual-banner {
            margin: 0 24px 56px; max-width: 1000px; margin-left: auto; margin-right: auto;
            background: linear-gradient(135deg, rgba(212,160,23,.08) 0%, rgba(55,48,163,.12) 100%);
            border: 1px solid rgba(212,160,23,.2); border-radius: 20px;
            padding: 28px 36px; display: flex; align-items: center; justify-content: space-between;
            gap: 24px; flex-wrap: wrap; animation: fadeUp .7s .65s ease both;
        }
        .manual-banner-left { display: flex; align-items: center; gap: 18px; }
        .manual-banner-icon {
            width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
            background: rgba(212,160,23,.15); border: 1px solid rgba(212,160,23,.25);
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        .manual-banner-title { font-size: 1rem; font-weight: 800; color: #e2eaf8; margin-bottom: 4px; }
        .manual-banner-sub { font-size: .75rem; color: #4a6fa5; font-weight: 500; }
        .btn-open-manual {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 24px; background: rgba(212,160,23,.15);
            border: 1px solid rgba(212,160,23,.35); border-radius: 10px;
            color: #fbbf24; font-size: .82rem; font-weight: 800;
            cursor: pointer; font-family: var(--font); transition: all .2s; white-space: nowrap;
        }
        .btn-open-manual:hover { background: rgba(212,160,23,.25); border-color: rgba(212,160,23,.6); transform: translateY(-1px); }

        /* ══ FOOTER ══ */
        footer {
            border-top: 1px solid rgba(99,102,241,.08);
            padding: 22px 40px; display: flex; align-items: center;
            justify-content: space-between; gap: 12px; flex-wrap: wrap;
            animation: fadeUp .6s .7s ease both;
        }
        .footer-left { font-size: .68rem; color: #1e3a5f; font-weight: 600; }
        .footer-left strong { color: #4a6fa5; }
        .footer-right { display: flex; align-items: center; gap: 8px; }
        .footer-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .6rem; font-weight: 700; padding: 3px 10px;
            border-radius: 999px; background: rgba(55,48,163,.15);
            border: 1px solid rgba(99,102,241,.2); color: #4a6fa5;
        }
        @media(max-width:479px) { footer { padding: 16px 18px; justify-content: center; text-align: center; } .footer-right { justify-content: center; } }

        .divider {
            display: flex; align-items: center; gap: 16px;
            max-width: 520px; margin: 0 auto 40px; animation: fadeUp .7s .55s ease both;
        }
        .divider-line { flex: 1; height: 1px; background: rgba(99,102,241,.15); }
        .divider-text { font-size: .62rem; font-weight: 700; color: #1e3a5f; letter-spacing: .12em; text-transform: uppercase; white-space: nowrap; }

        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .particle {
            position: absolute; width: 2px; height: 2px;
            background: rgba(129,140,248,.4); border-radius: 50%;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0%   { transform: translateY(100vh) translateX(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: .5; }
            100% { transform: translateY(-10vh) translateX(20px); opacity: 0; }
        }

        /* ══ MANUAL MODAL ══ */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 999;
            background: rgba(4,9,20,.85); backdrop-filter: blur(10px);
            display: flex; align-items: center; justify-content: center;
            padding: 20px; opacity: 0; pointer-events: none;
            transition: opacity .3s ease;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }

        .modal {
            background: #0b1628; border: 1px solid rgba(99,102,241,.2);
            border-radius: 22px; width: 100%; max-width: 860px;
            max-height: 90vh; display: flex; flex-direction: column;
            box-shadow: 0 32px 80px rgba(0,0,0,.6);
            transform: translateY(20px) scale(.97);
            transition: transform .3s ease;
        }
        .modal-overlay.open .modal { transform: translateY(0) scale(1); }

        /* Modal Header */
        .modal-header {
            padding: 24px 28px 20px; border-bottom: 1px solid rgba(99,102,241,.12);
            display: flex; align-items: center; gap: 16px; flex-shrink: 0;
        }
        .modal-header-icon {
            width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
            background: rgba(212,160,23,.12); border: 1px solid rgba(212,160,23,.2);
            display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        }
        .modal-header-info { flex: 1; }
        .modal-header-title { font-size: 1rem; font-weight: 800; color: #e2eaf8; margin-bottom: 3px; }
        .modal-header-sub { font-size: .72rem; color: #4a6fa5; font-weight: 500; }
        .modal-close {
            width: 36px; height: 36px; border-radius: 10px; border: 1px solid rgba(99,102,241,.2);
            background: rgba(99,102,241,.08); color: #818cf8; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; transition: all .2s; flex-shrink: 0;
        }
        .modal-close:hover { background: rgba(99,102,241,.18); border-color: rgba(99,102,241,.4); }

        /* Modal Nav Tabs */
        .modal-tabs {
            display: flex; gap: 0; padding: 0 28px;
            border-bottom: 1px solid rgba(99,102,241,.1);
            overflow-x: auto; flex-shrink: 0;
            scrollbar-width: none;
        }
        .modal-tabs::-webkit-scrollbar { display: none; }
        .modal-tab {
            padding: 14px 16px; font-size: .72rem; font-weight: 700;
            color: #4a6fa5; cursor: pointer; white-space: nowrap;
            border-bottom: 2px solid transparent; margin-bottom: -1px;
            transition: all .2s; display: flex; align-items: center; gap: 6px;
            background: none; border-top: none; border-left: none; border-right: none;
            font-family: var(--font); letter-spacing: .02em;
        }
        .modal-tab:hover { color: #818cf8; }
        .modal-tab.active { color: #818cf8; border-bottom-color: #818cf8; }
        .modal-tab i { font-size: .75rem; }

        /* Modal Body */
        .modal-body { flex: 1; overflow-y: auto; padding: 28px; scrollbar-width: thin; scrollbar-color: rgba(99,102,241,.2) transparent; }
        .modal-body::-webkit-scrollbar { width: 4px; }
        .modal-body::-webkit-scrollbar-track { background: transparent; }
        .modal-body::-webkit-scrollbar-thumb { background: rgba(99,102,241,.25); border-radius: 4px; }

        /* Manual Content Panels */
        .manual-panel { display: none; animation: fadeUp .3s ease; }
        .manual-panel.active { display: block; }

        /* Manual Typography */
        .man-intro {
            background: rgba(55,48,163,.08); border: 1px solid rgba(99,102,241,.12);
            border-radius: 14px; padding: 20px 22px; margin-bottom: 24px;
        }
        .man-intro p { font-size: .82rem; color: #a5b4fc; line-height: 1.75; font-weight: 500; }

        .man-section-label {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(55,48,163,.15); border: 1px solid rgba(99,102,241,.2);
            border-radius: 8px; padding: 6px 14px; margin-bottom: 20px;
            font-size: .65rem; font-weight: 800; color: #818cf8; letter-spacing: .1em; text-transform: uppercase;
        }

        .man-h2 { font-size: .95rem; font-weight: 800; color: #e2eaf8; margin: 0 0 14px; display: flex; align-items: center; gap: 10px; }
        .man-h2::before { content: ''; display: block; width: 4px; height: 18px; background: linear-gradient(180deg, #818cf8, #c0392b); border-radius: 2px; }
        .man-h3 { font-size: .82rem; font-weight: 700; color: #c7d2fe; margin: 18px 0 10px; }

        .man-p { font-size: .8rem; color: #6b8bb5; line-height: 1.75; margin-bottom: 14px; }

        /* Steps Table */
        .man-steps { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .man-steps th { font-size: .65rem; font-weight: 700; color: #4a6fa5; text-align: left; padding: 8px 12px; letter-spacing: .08em; text-transform: uppercase; border-bottom: 1px solid rgba(99,102,241,.12); }
        .man-steps td { font-size: .78rem; color: #a5b4fc; padding: 10px 12px; border-bottom: 1px solid rgba(99,102,241,.07); vertical-align: top; line-height: 1.6; }
        .man-steps td:first-child { white-space: nowrap; }
        .man-steps tr:last-child td { border-bottom: none; }
        .man-steps tr:hover td { background: rgba(99,102,241,.04); }
        .step-num {
            display: inline-flex; align-items: center; justify-content: center;
            width: 22px; height: 22px; border-radius: 50%;
            background: rgba(55,48,163,.25); border: 1px solid rgba(99,102,241,.3);
            font-size: .68rem; font-weight: 800; color: #818cf8;
        }

        /* Status/info tables */
        .man-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .man-table th { font-size: .65rem; font-weight: 700; color: #4a6fa5; text-align: left; padding: 8px 12px; letter-spacing: .08em; text-transform: uppercase; border-bottom: 1px solid rgba(99,102,241,.12); background: rgba(99,102,241,.04); }
        .man-table td { font-size: .78rem; color: #a5b4fc; padding: 10px 12px; border-bottom: 1px solid rgba(99,102,241,.07); vertical-align: top; line-height: 1.6; }
        .man-table tr:last-child td { border-bottom: none; }

        /* Role badge */
        .role-badge {
            display: inline-block; padding: 2px 10px; border-radius: 999px;
            font-size: .65rem; font-weight: 700; letter-spacing: .06em;
        }
        .role-resident { background: rgba(99,102,241,.15); color: #818cf8; border: 1px solid rgba(99,102,241,.25); }
        .role-officer  { background: rgba(16,185,129,.1);  color: #34d399; border: 1px solid rgba(16,185,129,.2); }
        .role-chairman { background: rgba(212,160,23,.1);  color: #fbbf24; border: 1px solid rgba(212,160,23,.2); }

        /* Status badge */
        .status-badge {
            display: inline-block; padding: 2px 9px; border-radius: 6px;
            font-size: .65rem; font-weight: 700;
        }
        .status-pending  { background: rgba(251,191,36,.1);  color: #fbbf24; }
        .status-approved { background: rgba(16,185,129,.1);  color: #34d399; }
        .status-declined { background: rgba(239,68,68,.1);   color: #f87171; }
        .status-claimed  { background: rgba(99,102,241,.15); color: #818cf8; }
        .status-expired  { background: rgba(107,114,128,.1); color: #9ca3af; }
        .status-avail    { background: rgba(16,185,129,.1);  color: #34d399; }
        .status-maint    { background: rgba(251,191,36,.1);  color: #fbbf24; }
        .status-outorder { background: rgba(239,68,68,.1);   color: #f87171; }

        /* Note/tip callouts */
        .man-note {
            border-radius: 12px; padding: 14px 18px; margin-bottom: 18px;
            font-size: .78rem; line-height: 1.6; display: flex; gap: 12px; align-items: flex-start;
        }
        .man-note.tip     { background: rgba(16,185,129,.06); border: 1px solid rgba(16,185,129,.15); color: #6ee7b7; }
        .man-note.warning { background: rgba(251,191,36,.06); border: 1px solid rgba(251,191,36,.2);  color: #fde68a; }
        .man-note.info    { background: rgba(99,102,241,.06); border: 1px solid rgba(99,102,241,.18); color: #c7d2fe; }
        .man-note.gold    { background: rgba(212,160,23,.06); border: 1px solid rgba(212,160,23,.2);  color: #fcd34d; }
        .man-note-icon { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

        /* Troubleshoot table */
        .trouble-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .trouble-table th { font-size: .65rem; font-weight: 700; color: #4a6fa5; text-align: left; padding: 8px 12px; letter-spacing: .08em; text-transform: uppercase; border-bottom: 1px solid rgba(99,102,241,.12); background: rgba(99,102,241,.04); }
        .trouble-table td { font-size: .78rem; padding: 12px 12px; border-bottom: 1px solid rgba(99,102,241,.07); vertical-align: top; line-height: 1.65; }
        .trouble-table td:first-child { color: #f87171; font-weight: 600; width: 40%; }
        .trouble-table td:last-child  { color: #a5b4fc; }
        .trouble-table tr:last-child td { border-bottom: none; }

        .man-divider { height: 1px; background: rgba(99,102,241,.1); margin: 24px 0; }

        .man-list { list-style: none; margin: 0 0 16px; padding: 0; }
        .man-list li { font-size: .78rem; color: #6b8bb5; padding: 5px 0 5px 18px; position: relative; line-height: 1.6; }
        .man-list li::before { content: '→'; position: absolute; left: 0; color: #818cf8; font-size: .72rem; }

        .man-url { font-family: var(--mono); font-size: .72rem; color: #fbbf24; background: rgba(212,160,23,.08); padding: 2px 8px; border-radius: 5px; }

        /* Quick Access card */
        .man-access-card {
            background: rgba(55,48,163,.1); border: 1px solid rgba(99,102,241,.2);
            border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        }
        .man-access-card i { color: #818cf8; font-size: 1.1rem; }
        .man-access-card span { font-size: .8rem; color: #a5b4fc; font-weight: 500; flex: 1; }

    </style>
</head>
<body>

    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>
    <div class="bg-orb"></div>
    <div class="particles" id="particles"></div>

    <div class="page">

        <!-- ══ NAV ══ -->
        <nav>
            <a href="#" class="nav-brand">
                <img src="" alt="SK Logo" class="nav-logo"
                     onerror="this.style.display='none';document.getElementById('nav-fallback').style.display='flex'">
                <div id="nav-fallback" style="display:flex;width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#c0392b,#3730a3);align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:white;border:2px solid rgba(212,160,23,.4);">SK</div>
                <div class="nav-brand-text">
                    <div class="nav-brand-name">my<em>Space.</em></div>
                    <div class="nav-brand-sub">Community Portal · Brgy. F. De Jesus</div>
                </div>
            </a>
            <div class="nav-actions">
                <button class="btn-ghost" onclick="openManual()">
                    <i class="fa-solid fa-book" style="font-size:.75rem;"></i> User Manual
                </button>
                <a href="/login" class="btn-ghost">
                    <i class="fa-solid fa-right-to-bracket" style="font-size:.75rem;"></i> Sign In
                </a>
                <a href="/register" class="btn-primary">
                    <i class="fa-solid fa-user-plus" style="font-size:.75rem;"></i> Register
                </a>
            </div>
        </nav>

        <!-- ══ HERO ══ -->
        <section class="hero">
            <div class="logo-ring">
                <div class="logo-img-wrap">
                    <img src="" alt="SK Logo"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'%3E%3Ccircle cx=\'50\' cy=\'50\' r=\'48\' fill=\'%233730a3\'/%3E%3Ctext x=\'50\' y=\'58\' text-anchor=\'middle\' fill=\'white\' font-size=\'28\' font-weight=\'bold\'%3ESK%3C/text%3E%3C/svg%3E'">
                </div>
            </div>

            <div class="eyebrow-pill">
                <span class="dot"></span>
                Sangguniang Kabataan · Community Portal
            </div>

            <h1 class="hero-title">my<em>Space</em><span class="accent">.</span></h1>

            <p class="hero-subtitle">
                Your all-in-one platform for reservations, library access, and community management — built for the youth of Barangay F. De Jesus.
            </p>

            <p class="hero-org">
                <strong>Sangguniang Kabataan</strong> · Brgy. F. De Jesus · Unisan Quezon
            </p>

            <div class="cta-group">
                <a href="/login" class="cta-login">
                    <i class="fa-solid fa-right-to-bracket" style="font-size:.85rem;"></i>
                    Sign In to Portal
                </a>
                <a href="/register" class="cta-register">
                    <i class="fa-solid fa-user-plus" style="font-size:.85rem;"></i>
                    Create Account
                </a>
                <button class="cta-manual" onclick="openManual()">
                    <i class="fa-solid fa-book-open" style="font-size:.85rem;"></i>
                    User Manual
                </button>
            </div>
        </section>

        <!-- ══ DIVIDER ══ -->
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">What you can do</span>
            <div class="divider-line"></div>
        </div>

        <!-- ══ FEATURES ══ -->
        <section class="features">
            <div class="features-grid">
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(55,48,163,.18);">
                        <i class="fa-solid fa-calendar-check" style="color:#818cf8;"></i>
                    </div>
                    <div class="feat-title">Reserve Resources</div>
                    <div class="feat-desc">Book computers, rooms, and barangay facilities with real-time availability and instant e-ticket generation.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(16,185,129,.12);">
                        <i class="fa-solid fa-book-open" style="color:#34d399;"></i>
                    </div>
                    <div class="feat-title">Library Access</div>
                    <div class="feat-desc">Browse and borrow books from the SK community library. Track due dates and borrowing history easily.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(212,160,23,.12);">
                        <i class="fa-solid fa-qrcode" style="color:#fbbf24;"></i>
                    </div>
                    <div class="feat-title">E-Ticket System</div>
                    <div class="feat-desc">Get a scannable QR e-ticket for every approved reservation. Fast, paperless, and secure check-in.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(192,57,43,.12);">
                        <i class="fa-solid fa-shield-halved" style="color:#f87171;"></i>
                    </div>
                    <div class="feat-title">SK Officer Portal</div>
                    <div class="feat-desc">Officers can manage requests, approve reservations, scan tickets, and monitor live sessions in real time.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(139,92,246,.12);">
                        <i class="fa-solid fa-chart-line" style="color:#c084fc;"></i>
                    </div>
                    <div class="feat-title">Admin Dashboard</div>
                    <div class="feat-desc">Full analytics, insights heatmaps, resource demand rankings, and system-wide live session monitoring.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(6,182,212,.1);">
                        <i class="fa-solid fa-users" style="color:#22d3ee;"></i>
                    </div>
                    <div class="feat-title">Community First</div>
                    <div class="feat-desc">Designed to empower youth governance — transparent, efficient, and accessible to every resident.</div>
                </div>
            </div>
        </section>

        <!-- ══ MANUAL BANNER ══ -->
        <div class="features" style="padding-top:0;padding-bottom:60px;">
            <div class="manual-banner">
                <div class="manual-banner-left">
                    <div class="manual-banner-icon">📋</div>
                    <div>
                        <div class="manual-banner-title">New to mySpace? Read the User Manual.</div>
                        <div class="manual-banner-sub">Step-by-step guide for Residents, SK Officers &amp; Administrators — v1.0 · 2026</div>
                    </div>
                </div>
                <button class="btn-open-manual" onclick="openManual()">
                    <i class="fa-solid fa-book-open"></i> Open Manual
                </button>
            </div>
        </div>

        <!-- ══ FOOTER ══ -->
        <footer>
            <div class="footer-left">
                &copy; 2026 <strong>mySpace · SK Brgy. F. De Jesus</strong> · All rights reserved.
            </div>
            <div class="footer-right">
                <span class="footer-badge"><i class="fa-solid fa-shield-halved" style="font-size:.55rem;"></i> Secure Portal</span>
                <span class="footer-badge"><i class="fa-solid fa-bolt" style="font-size:.55rem;"></i> Real-time</span>
            </div>
        </footer>
    </div>

    <!-- ══ USER MANUAL MODAL ══ -->
    <div class="modal-overlay" id="manualOverlay" onclick="handleOverlayClick(event)">
        <div class="modal" id="manualModal">

            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-header-icon">📋</div>
                <div class="modal-header-info">
                    <div class="modal-header-title">SK E-Learning Resource Reservation System — User Manual</div>
                    <div class="modal-header-sub">Version 1.0 &nbsp;·&nbsp; 2026 &nbsp;·&nbsp; Brgy. F. De Jesus, Unisan, Quezon</div>
                </div>
                <button class="modal-close" onclick="closeManual()" title="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Tabs -->
            <div class="modal-tabs" id="modalTabs">
                <button class="modal-tab active" data-tab="intro" onclick="switchTab('intro')">
                    <i class="fa-solid fa-circle-info"></i> Intro
                </button>
                <button class="modal-tab" data-tab="getting-started" onclick="switchTab('getting-started')">
                    <i class="fa-solid fa-rocket"></i> Getting Started
                </button>
                <button class="modal-tab" data-tab="reservations" onclick="switchTab('reservations')">
                    <i class="fa-solid fa-calendar-check"></i> Reservations
                </button>
                <button class="modal-tab" data-tab="sk-officer" onclick="switchTab('sk-officer')">
                    <i class="fa-solid fa-shield-halved"></i> SK Officer
                </button>
                <button class="modal-tab" data-tab="admin" onclick="switchTab('admin')">
                    <i class="fa-solid fa-crown"></i> Admin
                </button>
                <button class="modal-tab" data-tab="library" onclick="switchTab('library')">
                    <i class="fa-solid fa-book-open"></i> Library
                </button>
                <button class="modal-tab" data-tab="profile" onclick="switchTab('profile')">
                    <i class="fa-solid fa-user-gear"></i> Profile &amp; App
                </button>
                <button class="modal-tab" data-tab="troubleshoot" onclick="switchTab('troubleshoot')">
                    <i class="fa-solid fa-wrench"></i> Troubleshooting
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <!-- ── INTRO ── -->
                <div class="manual-panel active" id="tab-intro">
                    <div class="man-intro">
                        <p>Welcome to the <strong style="color:#c7d2fe;">SK E-Learning Resource Reservation System</strong> of Barangay F. De Jesus, Unisan, Quezon. This system allows residents, SK Officers, and administrators to manage reservations for e-learning resources such as computers, workstations, and other facilities. This manual will guide you through every feature of the system.</p>
                    </div>

                    <div class="man-access-card">
                        <i class="fa-solid fa-link"></i>
                        <span>Quick Access — Use the system anytime at:</span>
                        <span class="man-url">https://reservation-k2eg.onrender.com</span>
                    </div>

                    <h2 class="man-h2">System Overview</h2>
                    <p class="man-p">The reservation system is designed for three types of users, each with different access levels:</p>
                    <table class="man-table">
                        <thead>
                            <tr><th>Role</th><th>Access Level</th><th>Can Approve</th><th>Can Manage SK</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="role-badge role-resident">Resident / User</span></td>
                                <td>Limited</td><td>No</td><td>No</td>
                            </tr>
                            <tr>
                                <td><span class="role-badge role-officer">SK Officer</span></td>
                                <td>Moderate</td><td>Yes (Reservations)</td><td>No</td>
                            </tr>
                            <tr>
                                <td><span class="role-badge role-chairman">Chairman (Admin)</span></td>
                                <td>Full</td><td>Yes (All)</td><td>Yes</td>
                            </tr>
                        </tbody>
                    </table>

                    <h2 class="man-h2">System Requirements</h2>
                    <ul class="man-list">
                        <li>A modern web browser (Chrome, Firefox, Edge, or Safari)</li>
                        <li>Internet connection</li>
                        <li>A registered email address</li>
                        <li>For mobile: install the app via the <strong style="color:#c7d2fe;">View More</strong> menu → <strong style="color:#c7d2fe;">Install App</strong></li>
                    </ul>

                    <div class="man-note tip">
                        <span class="man-note-icon">💡</span>
                        <span>Use the tabs above to navigate each section of this manual. You can also find role-specific guides for SK Officers and Administrators.</span>
                    </div>
                </div>

                <!-- ── GETTING STARTED ── -->
                <div class="manual-panel" id="tab-getting-started">
                    <div class="man-section-label"><i class="fa-solid fa-rocket"></i> Section 01 — Getting Started</div>

                    <h2 class="man-h2">1.1 Creating Your Account</h2>
                    <p class="man-p">Follow these steps to register a new account:</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to the reservation system website and click <strong>Create an account</strong> on the login page.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Fill in your <strong>Full Name</strong>, <strong>Email Address</strong>, and select your <strong>Role</strong> (Resident or SK Officer).</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Create a strong password with at least 8 characters, one uppercase letter, and one number.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Click <strong>Sign Up</strong> to submit your registration.</td></tr>
                            <tr><td><span class="step-num">5</span></td><td>Check your email inbox for a verification link from the system.</td></tr>
                            <tr><td><span class="step-num">6</span></td><td>Click <strong>Verify Email Address</strong> in the email to activate your account.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note warning">
                        <span class="man-note-icon">⚠️</span>
                        <span><strong>Important for SK Officers —</strong> After verifying your email, your account will be set to <em>Pending</em> status. You cannot log in until the Barangay Chairman approves your SK account. You will receive an email notification once a decision has been made.</span>
                    </div>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">1.2 Logging In</h2>
                    <p class="man-p">Once your account is verified and approved:</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to the login page and enter your registered email address.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Enter your password.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Click <strong>Sign In</strong> to access your dashboard.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note info">
                        <span class="man-note-icon">🔐</span>
                        <span><strong>Forgot Password?</strong> Click <em>Forgot Password?</em> on the login page. Enter your email to receive a 6-digit OTP code, verify it, then set a new password.</span>
                    </div>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">1.3 Your Dashboard</h2>
                    <p class="man-p">After logging in, you will be taken to your dashboard. The dashboard shows:</p>
                    <ul class="man-list">
                        <li>Your upcoming and recent reservations</li>
                        <li>Quick statistics (pending, approved, claimed)</li>
                        <li>Navigation to all features in the sidebar (desktop) or bottom bar (mobile)</li>
                    </ul>
                </div>

                <!-- ── RESERVATIONS ── -->
                <div class="manual-panel" id="tab-reservations">
                    <div class="man-section-label"><i class="fa-solid fa-calendar-check"></i> Section 02 — Making Reservations</div>

                    <h2 class="man-h2">2.1 Creating a Reservation</h2>
                    <p class="man-p">Residents can request a reservation for e-learning resources:</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Click <strong>Reserve</strong> from the sidebar or dashboard.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Select the <strong>Resource</strong> you want to reserve (e.g., Computer Lab, WiFi).</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Choose your <strong>Reservation Date</strong> and set your <strong>Start Time</strong> and <strong>End Time</strong>.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Enter the <strong>Purpose</strong> of your reservation.</td></tr>
                            <tr><td><span class="step-num">5</span></td><td>If a PC is needed, select the specific <strong>PC number</strong> from the list.</td></tr>
                            <tr><td><span class="step-num">6</span></td><td>Click <strong>Submit</strong> to send your reservation request for approval.</td></tr>
                            <tr><td><span class="step-num">7</span></td><td>Review your Confirmation Details then click <strong>Submit Request</strong>.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note info">
                        <span class="man-note-icon">📋</span>
                        <span>Your reservation will be reviewed by an SK Officer or the Chairman before it is confirmed. You will receive a status update in the system.</span>
                    </div>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">2.2 Checking Reservation Status</h2>
                    <p class="man-p">Go to <strong>My Reservations</strong> from the sidebar. Each reservation shows one of the following statuses:</p>
                    <table class="man-table">
                        <thead><tr><th>Status</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td><span class="status-badge status-pending">Pending</span></td><td>Awaiting approval from SK Officer or Chairman.</td></tr>
                            <tr><td><span class="status-badge status-approved">Approved</span></td><td>Reservation confirmed. Your e-ticket is ready.</td></tr>
                            <tr><td><span class="status-badge status-declined">Declined</span></td><td>Reservation was not approved.</td></tr>
                            <tr><td><span class="status-badge status-claimed">Claimed</span></td><td>You have used your ticket at the facility.</td></tr>
                            <tr><td><span class="status-badge status-expired">Expired</span></td><td>The reservation date has passed without use.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">2.3 Your E-Ticket</h2>
                    <p class="man-p">When your reservation is approved, an E-Ticket with a QR code is generated. Present this code at the facility to claim your reservation.</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to <strong>My Slots</strong> and find your approved reservation.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Click on the <strong>eye icon</strong> to open the details.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Your QR code E-Ticket will appear. Click <strong>Download E-Ticket</strong> to save it.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Present the QR code to the SK Officer or staff at the facility.</td></tr>
                            <tr><td><span class="step-num">5</span></td><td>The staff will scan your QR code to mark it as <span class="status-badge status-claimed">Claimed</span>.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note tip">
                        <span class="man-note-icon">📱</span>
                        <span>Save your e-ticket to your phone's gallery before your reservation date so you can access it even without internet.</span>
                    </div>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">2.4 Cancelling a Reservation</h2>
                    <ul class="man-list">
                        <li>Go to <strong>My Reservations</strong>.</li>
                        <li>Find the reservation with <span class="status-badge status-pending">Pending</span> status.</li>
                        <li>Click <strong>Cancel</strong> on the reservation and confirm.</li>
                    </ul>
                    <div class="man-note warning">
                        <span class="man-note-icon">⚠️</span>
                        <span>Only <strong>Pending</strong> reservations can be cancelled. Approved reservations cannot be cancelled through the system — contact the Barangay office for assistance.</span>
                    </div>
                </div>

                <!-- ── SK OFFICER ── -->
                <div class="manual-panel" id="tab-sk-officer">
                    <div class="man-section-label"><i class="fa-solid fa-shield-halved"></i> Section 03 — SK Officer Guide</div>

                    <h2 class="man-h2">3.1 SK Officer Account Approval</h2>
                    <p class="man-p">When you register as an SK Officer, your account goes through a two-step verification:</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Register and verify your email address.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Wait for the Barangay Chairman to review and approve your SK account.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>You will receive an email notification once your account is approved or rejected.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Once approved, log in and access the SK dashboard.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">3.2 SK Dashboard</h2>
                    <p class="man-p">The SK dashboard gives you an overview of:</p>
                    <ul class="man-list">
                        <li>All reservations submitted by residents</li>
                        <li>Pending user requests awaiting your approval</li>
                        <li>Daily statistics and charts</li>
                        <li>Recent activity logs</li>
                    </ul>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">3.3 Approving &amp; Declining Reservations</h2>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to <strong>Reservations</strong> or <strong>User Requests</strong> from the sidebar.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Find a reservation with <span class="status-badge status-pending">Pending</span> status.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Click on the reservation to view its full details.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Click <strong>Approve</strong> to confirm the reservation, or <strong>Decline</strong> to reject it.</td></tr>
                            <tr><td><span class="step-num">5</span></td><td>The resident will see the updated status in their dashboard.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">3.4 QR Code Scanner</h2>
                    <p class="man-p">Use the built-in QR scanner to validate e-tickets at the facility:</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to <strong>Scanner</strong> from the sidebar.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Allow camera access when prompted by your browser.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Point the camera at the resident's QR code e-ticket.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>The system will automatically validate and mark the reservation as <span class="status-badge status-claimed">Claimed</span>.</td></tr>
                            <tr><td><span class="step-num">5</span></td><td>A success or error message will appear on screen.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note tip">
                        <span class="man-note-icon">📸</span>
                        <span>For best scanning results, ensure good lighting and hold the QR code steady. The scanner works with both printed and on-screen QR codes.</span>
                    </div>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">3.5 Log Print</h2>
                    <ul class="man-list">
                        <li>Open a reservation from the <strong>Reservations</strong> page.</li>
                        <li>Scroll to the <strong>Log Print</strong> section at the bottom of the reservation details.</li>
                        <li>Enter the number of pages printed (enter <strong>0</strong> if no printing occurred).</li>
                        <li>Click <strong>Save</strong> to record the print log.</li>
                    </ul>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">3.6 New Reservation (Walk-in)</h2>
                    <p class="man-p">SK Officers can create reservations on behalf of residents for walk-in requests:</p>
                    <ul class="man-list">
                        <li>Go to <strong>New Reservation</strong> from the sidebar.</li>
                        <li>Select the visitor type: <strong>Registered User</strong> or <strong>Walk-in Visitor</strong>.</li>
                        <li>Fill in all required details and submit.</li>
                        <li>Walk-in reservations are <strong>automatically approved</strong>.</li>
                    </ul>
                </div>

                <!-- ── ADMIN ── -->
                <div class="manual-panel" id="tab-admin">
                    <div class="man-section-label"><i class="fa-solid fa-crown"></i> Section 04 — Chairman / Admin Guide</div>

                    <h2 class="man-h2">4.1 Admin Dashboard</h2>
                    <p class="man-p">The Chairman has access to the full administration panel with:</p>
                    <ul class="man-list">
                        <li>Complete reservation management and approval</li>
                        <li>SK Officer account management</li>
                        <li>PC and resource management</li>
                        <li>Login and activity logs</li>
                        <li>System-wide statistics and reports</li>
                    </ul>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">4.2 Managing SK Accounts</h2>
                    <p class="man-p">The Chairman is responsible for approving or rejecting SK Officer registrations:</p>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to <strong>Manage SK</strong> from the sidebar. A badge shows the number of pending approvals.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Click on an SK account to view their details (name, email, date applied).</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Click <strong>Approve</strong> to grant SK portal access, or <strong>Reject</strong> to deny the application.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>The SK Officer will automatically receive an email notification with the decision.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note gold">
                        <span class="man-note-icon">📧</span>
                        <span>The system automatically sends professional email notifications to SK Officers when their account is approved or rejected. No manual email is needed.</span>
                    </div>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">4.3 Managing PCs &amp; Resources</h2>
                    <ul class="man-list">
                        <li>Go to <strong>Manage PCs</strong> from the sidebar.</li>
                        <li>Click <strong>Add PC</strong> to register a new workstation with its PC number and status.</li>
                        <li>Use the button on each PC to update its status or delete it.</li>
                    </ul>
                    <table class="man-table">
                        <thead><tr><th>Status</th><th>Description</th></tr></thead>
                        <tbody>
                            <tr><td><span class="status-badge status-avail">Available</span></td><td>PC is ready for reservation.</td></tr>
                            <tr><td><span class="status-badge status-maint">Maintenance</span></td><td>PC is undergoing repairs.</td></tr>
                            <tr><td><span class="status-badge status-outorder">Out of Order</span></td><td>PC is not functioning and should not be reserved.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">4.4 Viewing Logs</h2>
                    <p class="man-p">The system keeps detailed records of all activities:</p>
                    <table class="man-table">
                        <thead><tr><th>Log Type</th><th>What It Shows</th></tr></thead>
                        <tbody>
                            <tr><td><strong style="color:#c7d2fe;">Login Logs</strong></td><td>All login and logout events with timestamps and roles.</td></tr>
                            <tr><td><strong style="color:#c7d2fe;">Activity Logs</strong></td><td>All reservation actions: created, approved, declined, claimed, printed.</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- ── LIBRARY ── -->
                <div class="manual-panel" id="tab-library">
                    <div class="man-section-label"><i class="fa-solid fa-book-open"></i> Section 05 — Library Management</div>

                    <h2 class="man-h2">5.1 For Residents — Borrowing Books</h2>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Go to <strong>Library</strong> from the sidebar.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Browse the available books. Books with available copies show a <strong>Borrow</strong> button.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Click <strong>Borrow</strong> on the book you want.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Wait for SK Officer or Admin approval of your borrowing request.</td></tr>
                            <tr><td><span class="step-num">5</span></td><td>Go to <strong>My Borrowings</strong> to track your borrowing status.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">5.2 For SK Officers &amp; Admin — Managing Books</h2>
                    <ul class="man-list">
                        <li>Go to <strong>Library</strong> from the SK or Admin sidebar.</li>
                        <li>Click <strong>Add Book</strong> to add a new book to the library.</li>
                        <li>You can upload a PDF and use AI extraction to auto-fill book details.</li>
                        <li>Go to <strong>Borrowings</strong> to approve, reject, or mark books as returned.</li>
                    </ul>

                    <div class="man-note gold">
                        <span class="man-note-icon">🤖</span>
                        <span><strong>AI Book Extraction —</strong> When adding a book, upload the PDF and click <em>Extract with AI</em>. The system will automatically detect the title, author, genre, and other details from the PDF content.</span>
                    </div>
                </div>

                <!-- ── PROFILE & APP ── -->
                <div class="manual-panel" id="tab-profile">
                    <div class="man-section-label"><i class="fa-solid fa-user-gear"></i> Section 06 — Profile &amp; Settings</div>

                    <h2 class="man-h2">6.1 Updating Your Profile</h2>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Click <strong>Profile</strong> from the sidebar or navigation.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Update your <strong>Name</strong>, <strong>Email</strong>, or <strong>Phone number</strong>.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>To change your password, enter a new password in the Password field.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Click <strong>Save Changes</strong> to apply your updates.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-divider"></div>

                    <h2 class="man-h2">6.2 Installing the App (PWA)</h2>
                    <p class="man-p">The system can be installed as an app on your phone or computer for easier access:</p>

                    <h3 class="man-h3"><i class="fa-brands fa-android" style="color:#34d399;margin-right:6px;"></i> On Android (Chrome)</h3>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Open the reservation system in Chrome.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Tap the <strong>three-dot menu (⋮)</strong> in the top right.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Tap <strong>Add to Home Screen</strong> or <strong>Install App</strong>.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Tap <strong>Install</strong>. The app icon will appear on your home screen.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="man-h3"><i class="fa-brands fa-apple" style="color:#a5b4fc;margin-right:6px;"></i> On iPhone (Safari)</h3>
                    <table class="man-steps">
                        <thead><tr><th>#</th><th>Instructions</th></tr></thead>
                        <tbody>
                            <tr><td><span class="step-num">1</span></td><td>Open the reservation system in Safari.</td></tr>
                            <tr><td><span class="step-num">2</span></td><td>Tap the <strong>Share button</strong> (square with arrow) at the bottom.</td></tr>
                            <tr><td><span class="step-num">3</span></td><td>Scroll down and tap <strong>Add to Home Screen</strong>.</td></tr>
                            <tr><td><span class="step-num">4</span></td><td>Tap <strong>Add</strong>. The app icon will appear on your home screen.</td></tr>
                        </tbody>
                    </table>

                    <div class="man-note tip">
                        <span class="man-note-icon">📱</span>
                        <span>Installing as an app gives you faster loading, a full-screen experience without browser bars, and an icon on your home screen for quick access.</span>
                    </div>
                </div>

                <!-- ── TROUBLESHOOTING ── -->
                <div class="manual-panel" id="tab-troubleshoot">
                    <div class="man-section-label"><i class="fa-solid fa-wrench"></i> Section 07 — Troubleshooting</div>
                    <p class="man-p">If you experience any issues while using the system, refer to the table below:</p>

                    <table class="trouble-table">
                        <thead><tr><th>Issue</th><th>Solution</th></tr></thead>
                        <tbody>
                            <tr>
                                <td>I did not receive the verification email.</td>
                                <td>Check your <strong>Spam or Junk</strong> folder. If not found, try registering again or contact the admin.</td>
                            </tr>
                            <tr>
                                <td>My SK account says <em>Pending</em> after email verification.</td>
                                <td>This is normal. The Barangay Chairman must approve your SK account. You will be notified by email.</td>
                            </tr>
                            <tr>
                                <td>I cannot log in after verifying my email.</td>
                                <td>For SK Officers, wait for Chairman approval. For residents, try resetting your password via <strong>Forgot Password</strong>.</td>
                            </tr>
                            <tr>
                                <td>The QR scanner is not working.</td>
                                <td>Allow camera access in your browser settings. Ensure good lighting and hold the QR code steady.</td>
                            </tr>
                            <tr>
                                <td>The page shows a 404 error after the site wakes up.</td>
                                <td>The system may have been inactive. Refresh the page and go back to the login page manually.</td>
                            </tr>
                            <tr>
                                <td>My reservation is stuck on Pending.</td>
                                <td>Contact an SK Officer or the Barangay Chairman to review your reservation.</td>
                            </tr>
                            <tr>
                                <td>I forgot my password.</td>
                                <td>Use the <strong>Forgot Password</strong> link on the login page. Enter your email to receive a verification code.</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="man-note info">
                        <span class="man-note-icon">📞</span>
                        <span><strong>Need Help?</strong> Contact the Barangay F. De Jesus office in Unisan, Quezon or reach your SK Officers directly. System URL: <span class="man-url">https://reservation-z2uk.onrender.com</span></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 28; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.bottom = '-10px';
            const sz = (Math.random() * 2 + 1) + 'px';
            p.style.width = sz; p.style.height = sz;
            p.style.opacity = Math.random() * 0.5 + 0.1;
            p.style.animationDuration = (Math.random() * 18 + 12) + 's';
            p.style.animationDelay = (Math.random() * 14) + 's';
            const colors = ['rgba(129,140,248,.5)','rgba(192,57,43,.4)','rgba(212,160,23,.4)','rgba(99,102,241,.5)'];
            p.style.background = colors[Math.floor(Math.random() * colors.length)];
            container.appendChild(p);
        }

        // Modal
        function openManual() {
            document.getElementById('manualOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeManual() {
            document.getElementById('manualOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }
        function handleOverlayClick(e) {
            if (e.target === document.getElementById('manualOverlay')) closeManual();
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeManual(); });

        // Tabs
        function switchTab(tabId) {
            document.querySelectorAll('.manual-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
            document.getElementById('manualModal').querySelector('.modal-body').scrollTop = 0;
        }
    </script>

</body>
</html>