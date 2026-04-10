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

        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .bg-mesh::before {
            content: '';
            position: absolute;
            width: 900px; height: 900px;
            top: -300px; left: -200px;
            background: radial-gradient(circle, rgba(55,48,163,.35) 0%, transparent 70%);
            animation: driftA 18s ease-in-out infinite alternate;
        }

        .bg-mesh::after {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            bottom: -200px; right: -150px;
            background: radial-gradient(circle, rgba(192,57,43,.25) 0%, transparent 70%);
            animation: driftB 14s ease-in-out infinite alternate;
        }

        .bg-orb {
            position: fixed;
            width: 500px; height: 500px;
            top: 40%; left: 55%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(212,160,23,.12) 0%, transparent 65%);
            animation: driftC 20s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes driftA { from { transform: translate(0,0) scale(1); } to { transform: translate(60px, 80px) scale(1.15); } }
        @keyframes driftB { from { transform: translate(0,0) scale(1); } to { transform: translate(-40px, -60px) scale(1.1); } }
        @keyframes driftC { from { transform: translate(-50%,-50%) scale(1); } to { transform: translate(-45%,-55%) scale(1.2); } }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(99,102,241,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ══ NAV ══ */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(99,102,241,.1);
            backdrop-filter: blur(12px);
            background: rgba(6,14,30,.6);
            position: sticky;
            top: 0;
            z-index: 100;
            animation: slideDown .6s ease both;
            gap: 12px;
        }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: none; } }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            min-width: 0;
            flex-shrink: 1;
        }

        .nav-logo {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(212,160,23,.4);
            box-shadow: 0 0 12px rgba(212,160,23,.25);
            flex-shrink: 0;
        }

        .nav-brand-text {
            line-height: 1.1;
            min-width: 0;
            overflow: hidden;
        }
        .nav-brand-name {
            font-size: 1rem;
            font-weight: 800;
            color: #e2eaf8;
            letter-spacing: -.03em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .nav-brand-name em { font-style: normal; color: #818cf8; }
        .nav-brand-sub  {
            font-size: .58rem;
            font-weight: 600;
            color: #4a6fa5;
            letter-spacing: .06em;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-ghost {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: .78rem;
            font-weight: 700;
            border: 1px solid rgba(99,102,241,.25);
            background: transparent;
            color: #a5b4fc;
            cursor: pointer;
            font-family: var(--font);
            text-decoration: none;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-ghost:hover { background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.5); color: #c7d2fe; }

        .btn-primary {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: .78rem;
            font-weight: 800;
            border: none;
            background: var(--indigo);
            color: #fff;
            cursor: pointer;
            font-family: var(--font);
            text-decoration: none;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 14px rgba(55,48,163,.4);
            white-space: nowrap;
        }

        .btn-primary:hover { background: #312e81; transform: translateY(-1px); }

        /* Hide icon text labels on very small screens, keep icon */
        @media(max-width: 400px) {
            .btn-ghost .btn-label,
            .btn-primary .btn-label { display: none; }
            .btn-ghost, .btn-primary { padding: 8px 12px; }
            .nav-brand-sub { display: none; }
        }

        @media(max-width: 340px) {
            .btn-ghost { display: none; }
        }

        /* ══ HERO ══ */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 20px 64px;
            text-align: center;
        }

        .logo-ring {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 28px;
            animation: fadeUp .8s .1s ease both;
        }

        .logo-ring::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #c0392b, #d4a017, #3730a3, #c0392b);
            animation: rotateBorder 6s linear infinite;
            z-index: 0;
        }

        .logo-ring::after {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #c0392b, #d4a017, #3730a3, #c0392b);
            filter: blur(10px);
            opacity: .5;
            animation: rotateBorder 6s linear infinite;
            z-index: 0;
        }

        @keyframes rotateBorder { to { transform: rotate(360deg); } }

        .logo-img-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: #060e1e;
            padding: 4px;
        }

        .logo-img-wrap img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }

        .eyebrow-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(55,48,163,.18);
            border: 1px solid rgba(99,102,241,.28);
            color: #a5b4fc;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 18px;
            animation: fadeUp .7s .2s ease both;
            max-width: calc(100vw - 48px);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .eyebrow-pill span.dot { width: 6px; height: 6px; border-radius: 50%; background: #818cf8; display: inline-block; animation: pulse 2s infinite; flex-shrink: 0; }
        @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.8); } }

        .hero-title {
            font-size: clamp(2.4rem, 10vw, 5rem);
            font-weight: 800;
            letter-spacing: -.05em;
            line-height: 1;
            color: #e2eaf8;
            margin-bottom: 10px;
            animation: fadeUp .7s .3s ease both;
        }

        .hero-title em { font-style: normal; color: #818cf8; }
        .hero-title .accent {
            background: linear-gradient(90deg, #c0392b, #d4a017);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: .95rem;
            color: #4a6fa5;
            font-weight: 500;
            max-width: 480px;
            line-height: 1.65;
            margin: 0 auto 12px;
            animation: fadeUp .7s .4s ease both;
            padding: 0 4px;
        }

        .hero-org {
            font-size: .78rem;
            font-weight: 700;
            color: #7fb3e8;
            letter-spacing: .04em;
            margin-bottom: 36px;
            animation: fadeUp .7s .45s ease both;
            padding: 0 8px;
            line-height: 1.5;
        }

        .hero-org strong { color: #d4a017; }

        /* CTA buttons */
        .cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            animation: fadeUp .7s .5s ease both;
            width: 100%;
            padding: 0 8px;
        }

        .cta-login {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: var(--indigo);
            color: #fff;
            border-radius: 14px;
            font-size: .88rem;
            font-weight: 800;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all .2s;
            box-shadow: 0 6px 24px rgba(55,48,163,.45);
            letter-spacing: -.01em;
            flex: 1;
            justify-content: center;
            min-width: 140px;
            max-width: 220px;
        }

        .cta-login:hover { background: #312e81; transform: translateY(-2px); }

        .cta-register {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: transparent;
            color: #a5b4fc;
            border-radius: 14px;
            font-size: .88rem;
            font-weight: 800;
            text-decoration: none;
            border: 1.5px solid rgba(99,102,241,.3);
            cursor: pointer;
            font-family: var(--font);
            transition: all .2s;
            letter-spacing: -.01em;
            flex: 1;
            justify-content: center;
            min-width: 140px;
            max-width: 220px;
        }

        .cta-register:hover { background: rgba(99,102,241,.1); border-color: rgba(99,102,241,.6); color: #c7d2fe; transform: translateY(-2px); }

        @media(max-width: 360px) {
            .cta-login, .cta-register {
                padding: 12px 20px;
                font-size: .82rem;
                max-width: 100%;
            }
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

        /* ══ FEATURE CARDS ══ */
        .features {
            padding: 0 16px 60px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
            animation: fadeUp .7s .6s ease both;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media(min-width: 480px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media(min-width: 760px) {
            .features-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .feat-card {
            background: rgba(11,22,40,.7);
            border: 1px solid rgba(99,102,241,.1);
            border-radius: 18px;
            padding: 20px;
            backdrop-filter: blur(8px);
            transition: border-color .2s, transform .2s, box-shadow .2s;
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        @media(min-width: 480px) {
            .feat-card {
                flex-direction: column;
                gap: 0;
            }
        }

        .feat-card:hover { border-color: rgba(99,102,241,.3); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.3); }

        .feat-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        @media(min-width: 480px) {
            .feat-icon { margin-bottom: 14px; }
        }

        .feat-body { min-width: 0; }

        .feat-title { font-size: .86rem; font-weight: 800; color: #e2eaf8; margin-bottom: 5px; letter-spacing: -.01em; }
        .feat-desc  { font-size: .74rem; color: #4a6fa5; line-height: 1.6; font-weight: 500; }

        /* ══ DIVIDER ══ */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            max-width: 520px;
            margin: 0 auto 32px;
            padding: 0 20px;
            animation: fadeUp .7s .55s ease both;
        }

        .divider-line { flex: 1; height: 1px; background: rgba(99,102,241,.15); }
        .divider-text { font-size: .6rem; font-weight: 700; color: #1e3a5f; letter-spacing: .12em; text-transform: uppercase; white-space: nowrap; }

        /* ══ FOOTER ══ */
        footer {
            border-top: 1px solid rgba(99,102,241,.08);
            padding: 20px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            animation: fadeUp .6s .7s ease both;
        }

        .footer-left { font-size: .66rem; color: #1e3a5f; font-weight: 600; line-height: 1.5; }
        .footer-left strong { color: #4a6fa5; }

        .footer-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
        .footer-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .58rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 999px;
            background: rgba(55,48,163,.15);
            border: 1px solid rgba(99,102,241,.2);
            color: #4a6fa5;
            white-space: nowrap;
        }

        @media(max-width: 400px) {
            footer { justify-content: center; text-align: center; }
            .footer-right { justify-content: center; }
        }

        /* Floating particles */
        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(129,140,248,.4);
            border-radius: 50%;
            animation: floatUp linear infinite;
        }

        @keyframes floatUp {
            0%   { transform: translateY(100vh) translateX(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: .5; }
            100% { transform: translateY(-10vh) translateX(20px); opacity: 0; }
        }
    </style>
</head>
<body>

    <div class="bg-mesh"></div>
    <div class="bg-grid"></div>
    <div class="bg-orb"></div>
    <div class="particles" id="particles"></div>

    <div class="page">

        <!-- NAV -->
        <nav>
            <a href="#" class="nav-brand">
                <div id="nav-fallback" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#c0392b,#3730a3);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;color:white;border:2px solid rgba(212,160,23,.4);flex-shrink:0;">SK</div>
                <div class="nav-brand-text">
                    <div class="nav-brand-name">my<em>Space.</em></div>
                    <div class="nav-brand-sub">Community Portal · Brgy. F. De Jesus</div>
                </div>
            </a>
            <div class="nav-actions">
                <a href="/login" class="btn-ghost">
                    <i class="fa-solid fa-right-to-bracket" style="font-size:.72rem;"></i>
                    <span class="btn-label">Sign In</span>
                </a>
                <a href="/register" class="btn-primary">
                    <i class="fa-solid fa-user-plus" style="font-size:.72rem;"></i>
                    <span class="btn-label">Register</span>
                </a>
            </div>
        </nav>

        <!-- HERO -->
        <section class="hero">

            <div class="logo-ring">
                <div class="logo-img-wrap">
                    <img src="" alt="SK Logo"
                         onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;border-radius:50%;background:linear-gradient(135deg,#c0392b,#3730a3);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.4rem;color:white;\'>SK</div>'">
                </div>
            </div>

            <div class="eyebrow-pill">
                <span class="dot"></span>
                Sangguniang Kabataan · Community Portal
            </div>

            <h1 class="hero-title">
                my<em>Space</em><span class="accent">.</span>
            </h1>

            <p class="hero-subtitle">
                Your all-in-one platform for reservations, library access, and community management — built for the youth of Barangay F. De Jesus.
            </p>

            <p class="hero-org">
                <strong>Sangguniang Kabataan</strong> · Brgy. F. De Jesus · Unisan Quezon
            </p>

            <div class="cta-group">
                <a href="/login" class="cta-login">
                    <i class="fa-solid fa-right-to-bracket" style="font-size:.82rem;"></i>
                    Sign In to Portal
                </a>
                <a href="/register" class="cta-register">
                    <i class="fa-solid fa-user-plus" style="font-size:.82rem;"></i>
                    Create Account
                </a>
            </div>

        </section>

        <!-- DIVIDER -->
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">What you can do</span>
            <div class="divider-line"></div>
        </div>

        <!-- FEATURES -->
        <section class="features">
            <div class="features-grid">

                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(55,48,163,.18);">
                        <i class="fa-solid fa-calendar-check" style="color:#818cf8;"></i>
                    </div>
                    <div class="feat-body">
                        <div class="feat-title">Reserve Resources</div>
                        <div class="feat-desc">Book computers, rooms, and barangay facilities with real-time availability and instant e-ticket generation.</div>
                    </div>
                </div>

                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(16,185,129,.12);">
                        <i class="fa-solid fa-book-open" style="color:#34d399;"></i>
                    </div>
                    <div class="feat-body">
                        <div class="feat-title">Library Access</div>
                        <div class="feat-desc">Browse and borrow books from the SK community library. Track due dates and borrowing history easily.</div>
                    </div>
                </div>

                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(212,160,23,.12);">
                        <i class="fa-solid fa-qrcode" style="color:#fbbf24;"></i>
                    </div>
                    <div class="feat-body">
                        <div class="feat-title">E-Ticket System</div>
                        <div class="feat-desc">Get a scannable QR e-ticket for every approved reservation. Fast, paperless, and secure check-in.</div>
                    </div>
                </div>

                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(192,57,43,.12);">
                        <i class="fa-solid fa-shield-halved" style="color:#f87171;"></i>
                    </div>
                    <div class="feat-body">
                        <div class="feat-title">SK Officer Portal</div>
                        <div class="feat-desc">Officers can manage requests, approve reservations, scan tickets, and monitor live sessions in real time.</div>
                    </div>
                </div>

                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(139,92,246,.12);">
                        <i class="fa-solid fa-chart-line" style="color:#c084fc;"></i>
                    </div>
                    <div class="feat-body">
                        <div class="feat-title">Admin Dashboard</div>
                        <div class="feat-desc">Full analytics, insights heatmaps, resource demand rankings, and system-wide live session monitoring.</div>
                    </div>
                </div>

                <div class="feat-card">
                    <div class="feat-icon" style="background:rgba(6,182,212,.1);">
                        <i class="fa-solid fa-users" style="color:#22d3ee;"></i>
                    </div>
                    <div class="feat-body">
                        <div class="feat-title">Community First</div>
                        <div class="feat-desc">Designed to empower youth governance — transparent, efficient, and accessible to every resident.</div>
                    </div>
                </div>

            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-left">
                &copy; 2026 <strong>mySpace · SK Brgy. F. De Jesus</strong> · All rights reserved.
            </div>
            <div class="footer-right">
                <span class="footer-badge"><i class="fa-solid fa-shield-halved" style="font-size:.52rem;"></i> Secure</span>
                <span class="footer-badge"><i class="fa-solid fa-bolt" style="font-size:.52rem;"></i> Real-time</span>
            </div>
        </footer>

    </div>

    <script>
        const container = document.getElementById('particles');
        for (let i = 0; i < 20; i++) {
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
    </script>

</body>
</html>