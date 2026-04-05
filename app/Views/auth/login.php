<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Login | E-Learning Resource Reservation System</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#3730a3">
  <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --indigo:      #3730a3;
      --indigo-mid:  #4338ca;
      --indigo-dark: #312e81;
      --indigo-light:#eef2ff;
      --indigo-brd:  #c7d2fe;
      --bg:          #f0f2f9;
      --card:        #ffffff;
      --text:        #0f172a;
      --muted:       #64748b;
      --muted2:      #94a3b8;
      --border:      rgba(99,102,241,.13);
      --border2:     rgba(99,102,241,.07);
      --green:       #16a34a;
      --green-bg:    #dcfce7;
      --red:         #dc2626;
      --red-bg:      #fef2f2;
      --amber:       #d97706;
      --amber-bg:    #fef3c7;
      --shadow-lg:   0 12px 40px rgba(55,48,163,.15), 0 4px 8px rgba(55,48,163,.06);
      --r:           24px;
      --r-md:        16px;
      --r-sm:        12px;
      --r-xs:        10px;
      --font:        'Plus Jakarta Sans', system-ui, sans-serif;
    }
    html { height: 100%; }
    body {
      font-family: var(--font);
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 800px 500px at 80% -10%, rgba(67,56,202,.08) 0%, transparent 65%),
        radial-gradient(ellipse 600px 400px at -5% 100%, rgba(99,102,241,.06) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }
    body::after {
      content: '';
      position: fixed;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99,102,241,.06) 0%, transparent 70%);
      top: -80px; right: -80px;
      pointer-events: none;
      z-index: 0;
    }
    .login-wrapper { width: 100%; max-width: 460px; position: relative; z-index: 1; }
    .brand-bar { text-align: center; margin-bottom: 20px; }
    .brand-bar .brand-tag { font-size: 10px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: var(--muted2); display: block; margin-bottom: 4px; }
    .brand-bar .brand-name { font-size: 26px; font-weight: 800; color: var(--text); letter-spacing: -.03em; line-height: 1.1; }
    .brand-bar .brand-name em { font-style: normal; color: var(--indigo-mid); }
    .login-card { background: var(--card); border-radius: var(--r); border: 1px solid var(--border); box-shadow: var(--shadow-lg); padding: 2.25rem 2rem; width: 100%; }
    @media (min-width: 480px) { .login-card { padding: 2.5rem 2.5rem; } }
    .logo-hero { display: flex; flex-direction: column; align-items: center; margin-bottom: 1.5rem; }
    .logo-ring {
      width: 90px; height: 90px;
      border-radius: 50%;
      background: var(--indigo-light);
      border: 2px solid var(--indigo-brd);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 14px;
      overflow: hidden;
      box-shadow: 0 4px 16px rgba(55,48,163,.18);
    }
    .logo-ring img { width: 100%; height: 100%; object-fit: cover; }
    .logo-hero h1 { font-size: 1.5rem; font-weight: 800; color: var(--text); letter-spacing: -.03em; text-align: center; line-height: 1.2; }
    .logo-hero p { font-size: 12px; color: var(--muted); font-weight: 500; margin-top: 4px; text-align: center; }
    .welcome-pill { background: var(--indigo-light); border: 1px solid var(--indigo-brd); border-radius: var(--r-sm); padding: 10px 14px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
    .welcome-pill .wp-icon { width: 30px; height: 30px; border-radius: 9px; background: var(--indigo); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
    .welcome-pill .wp-text h3 { font-size: 12px; font-weight: 800; color: var(--indigo); letter-spacing: -.01em; }
    .welcome-pill .wp-text p { font-size: 11px; color: var(--muted); font-weight: 500; margin-top: 1px; }
    .flash { border-radius: var(--r-sm); padding: 11px 14px; margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: 10px; font-size: 13px; font-weight: 600; animation: fadeDown .3s ease; }
    .flash-error   { background: var(--red-bg);   border: 1px solid #fecaca; color: #991b1b; }
    .flash-success { background: var(--green-bg);  border: 1px solid #bbf7d0; color: #166534; }
    .flash-info    { background: var(--indigo-light); border: 1px solid var(--indigo-brd); color: var(--indigo); }
    .flash i { margin-top: 1px; flex-shrink: 0; font-size: 14px; }
    @keyframes fadeDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:none; } }
    .field-lbl { display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--muted); margin-bottom: 6px; margin-left: 2px; }
    .field-wrap { position: relative; margin-bottom: 1.1rem; }
    .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted2); font-size: 13px; pointer-events: none; transition: color .2s; z-index: 2; }
    .field-input { width: 100%; height: 48px; padding: 0 14px 0 40px; border: 1.5px solid var(--border); border-radius: var(--r-sm); font-family: var(--font); font-size: 14px; background: #f8fafc; color: var(--text); transition: all .2s; outline: none; }
    .field-input:focus { border-color: var(--indigo-mid); background: #fff; box-shadow: 0 0 0 3px rgba(67,56,202,.12); }
    .field-input:focus + .field-icon, .field-wrap:focus-within .field-icon { color: var(--indigo); }
    .field-input::placeholder { color: #cbd5e1; font-weight: 400; }
    .field-input.pr { padding-right: 44px; }
    .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted2); cursor: pointer; padding: 4px; display: flex; align-items: center; font-size: 14px; transition: color .18s; z-index: 3; }
    .eye-btn:hover { color: var(--indigo); }
    .eye-btn:focus { outline: none; }
    .row-meta { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .check-wrap { display: flex; align-items: center; gap: 7px; cursor: pointer; user-select: none; }
    .check-input { appearance: none; width: 17px; height: 17px; border: 1.5px solid var(--border); border-radius: 6px; background: #f8fafc; cursor: pointer; position: relative; flex-shrink: 0; transition: all .18s; }
    .check-input:checked { background: var(--indigo); border-color: var(--indigo); }
    .check-input:checked::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 9px; }
    .check-input:focus { box-shadow: 0 0 0 3px rgba(67,56,202,.12); outline: none; }
    .check-lbl { font-size: 13px; color: var(--muted); font-weight: 600; }
    .forgot-link { font-size: 13px; color: var(--indigo-mid); font-weight: 700; text-decoration: none; transition: color .18s; }
    .forgot-link:hover { color: var(--indigo-dark); text-decoration: underline; }
    .btn-submit { width: 100%; height: 50px; background: var(--indigo); color: #fff; font-family: var(--font); font-weight: 800; font-size: 14px; border: none; border-radius: var(--r-sm); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .18s; box-shadow: 0 4px 14px rgba(55,48,163,.32); position: relative; overflow: hidden; letter-spacing: -.01em; }
    .btn-submit::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,.1) 0%, transparent 100%); pointer-events: none; }
    .btn-submit:hover { background: var(--indigo-dark); box-shadow: 0 6px 20px rgba(55,48,163,.42); transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }
    .divider { display: flex; align-items: center; gap: 10px; margin: 1.25rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .divider span { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: var(--muted2); }
    .register-row { text-align: center; font-size: 13px; color: var(--muted); font-weight: 500; }
    .register-row a { color: var(--indigo-mid); font-weight: 700; text-decoration: none; transition: color .18s; }
    .register-row a:hover { color: var(--indigo-dark); text-decoration: underline; }
    .footer-note { text-align: center; margin-top: 1.25rem; font-size: 11px; color: var(--muted2); line-height: 1.7; }
    .footer-note button { color: var(--indigo-mid); font-weight: 700; font-size: 11px; background: none; border: none; cursor: pointer; font-family: var(--font); transition: color .18s; padding: 0; }
    .footer-note button:hover { color: var(--indigo-dark); text-decoration: underline; }

    /* MODALS */
    .overlay { display: none; position: fixed; inset: 0; z-index: 300; background: rgba(15,23,42,.55); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 1.25rem; }
    .overlay.open { display: flex; animation: overlayIn .2s ease; }
    @keyframes overlayIn { from { opacity:0; } to { opacity:1; } }
    .modal-box { background: var(--card); border-radius: var(--r); border: 1px solid var(--border); box-shadow: 0 24px 60px rgba(15,23,42,.22); width: 100%; max-width: 560px; max-height: 90vh; display: flex; flex-direction: column; animation: modalUp .25s cubic-bezier(.34,1.4,.64,1); overflow: hidden; }
    .modal-box.sm { max-width: 400px; }
    @keyframes modalUp { from { opacity:0; transform:translateY(20px) scale(.97); } to { opacity:1; transform:none; } }
    .modal-head { flex-shrink: 0; padding: 18px 22px 16px; border-bottom: 1px solid var(--border2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .modal-head-left { display: flex; align-items: center; gap: 12px; }
    .modal-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .modal-icon.indigo { background: var(--indigo-light); color: var(--indigo); border: 1px solid var(--indigo-brd); }
    .modal-icon.purple { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .modal-head-title { font-size: 15px; font-weight: 800; color: var(--text); letter-spacing: -.02em; }
    .modal-head-sub { font-size: 11px; color: var(--muted); font-weight: 500; margin-top: 1px; }
    .modal-close-btn { width: 32px; height: 32px; border-radius: 9px; background: #f1f5f9; border: none; color: var(--muted); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; transition: all .15s; flex-shrink: 0; }
    .modal-close-btn:hover { background: #fee2e2; color: var(--red); }
    .modal-prog-bar { height: 3px; background: #f1f5f9; flex-shrink: 0; }
    .modal-prog-fill { height: 100%; width: 0%; border-radius: 0 2px 2px 0; transition: width .1s linear; }
    .fill-indigo { background: var(--indigo); }
    .fill-purple { background: #7c3aed; }
    .modal-body { flex: 1; overflow-y: auto; padding: 20px 22px; }
    .modal-body::-webkit-scrollbar { width: 4px; }
    .modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
    .ts { margin-bottom: 1.5rem; }
    .ts:last-child { margin-bottom: 0; }
    .ts-head { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .ts-num { width: 24px; height: 24px; border-radius: 7px; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ts-num.in { background: var(--indigo-light); color: var(--indigo); }
    .ts-num.pu { background: #f5f3ff; color: #7c3aed; }
    .ts h3 { font-size: 13px; font-weight: 800; color: var(--text); }
    .ts p, .ts li { font-size: 13px; color: var(--muted); line-height: 1.7; font-weight: 500; }
    .ts ul { margin-top: 6px; padding-left: 0; list-style: none; display: flex; flex-direction: column; gap: 4px; }
    .ts li { display: flex; align-items: flex-start; gap: 7px; }
    .ts li::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; margin-top: 8px; }
    .li-in::before { background: var(--indigo); }
    .li-pu::before { background: #7c3aed; }
    .ts-hl { border-radius: 10px; padding: 10px 13px; margin-top: 10px; font-size: 12px; font-weight: 600; line-height: 1.6; }
    .hl-in { background: var(--indigo-light); border: 1px solid var(--indigo-brd); color: var(--indigo); }
    .hl-pu { background: #f5f3ff; border: 1px solid #ddd6fe; color: #6d28d9; }
    .modal-foot { flex-shrink: 0; padding: 14px 22px; border-top: 1px solid var(--border2); background: #fafafa; }
    .modal-foot-note { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--muted2); font-weight: 600; margin-bottom: 10px; }
    .must-hint { font-size: 11px; color: var(--amber); font-weight: 700; text-align: center; display: none; margin-bottom: 8px; }
    .must-hint.show { display: block; }
    .modal-foot-btns { display: flex; gap: 8px; }
    .btn-modal-cancel { flex: 1; height: 42px; background: #f1f5f9; color: var(--muted); border: 1.5px solid var(--border); border-radius: var(--r-xs); font-family: var(--font); font-weight: 700; font-size: 13px; cursor: pointer; transition: all .15s; }
    .btn-modal-cancel:hover { background: #e2e8f0; }
    .btn-modal-accept { flex: 2; height: 42px; color: #fff; border: none; border-radius: var(--r-xs); font-family: var(--font); font-weight: 800; font-size: 13px; cursor: pointer; transition: all .18s; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .btn-modal-accept.c-in { background: var(--indigo); box-shadow: 0 4px 12px rgba(55,48,163,.28); }
    .btn-modal-accept.c-in:hover { background: var(--indigo-dark); }
    .btn-modal-accept.c-pu { background: #7c3aed; box-shadow: 0 4px 12px rgba(124,58,237,.28); }
    .btn-modal-accept.c-pu:hover { background: #6d28d9; }
    .btn-modal-accept:disabled { opacity: .4; cursor: not-allowed; }

    /* FORGOT PASSWORD */
    .fp-dots { display: flex; justify-content: center; gap: 6px; margin-bottom: 1.5rem; }
    .fp-dot { height: 6px; border-radius: 3px; background: var(--border); transition: all .3s; }
    .fp-dot.active { background: var(--indigo); width: 22px; }
    .fp-dot.done   { background: var(--indigo-brd); width: 10px; }
    .fp-dot.pend   { width: 6px; }
    .fp-panel { display: none; }
    .fp-panel.active { display: block; animation: fpSlide .25s ease; }
    @keyframes fpSlide { from { opacity:0; transform:translateX(10px); } to { opacity:1; transform:none; } }
    .fp-hero { text-align: center; margin-bottom: 1.5rem; }
    .fp-hero-icon { width: 68px; height: 68px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; font-size: 1.8rem; }
    .fp-hero-icon.bl { background: var(--indigo-light); color: var(--indigo); border: 1px solid var(--indigo-brd); }
    .fp-hero-icon.am { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .fp-hero-icon.gr { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .fp-hero h3 { font-size: 16px; font-weight: 800; color: var(--text); letter-spacing: -.02em; margin-bottom: 5px; }
    .fp-hero p { font-size: 13px; color: var(--muted); font-weight: 500; line-height: 1.6; max-width: 300px; margin: 0 auto; }
    .fp-hero strong { color: var(--text); font-weight: 700; }
    .fp-info { background: var(--indigo-light); border: 1px solid var(--indigo-brd); border-radius: 10px; padding: 10px 13px; margin-bottom: 1.1rem; display: flex; align-items: flex-start; gap: 8px; font-size: 12px; color: var(--indigo); font-weight: 600; line-height: 1.55; }
    .fp-info i { flex-shrink: 0; margin-top: 1px; }
    .fp-err { font-size: 12px; font-weight: 700; color: var(--red); margin-top: 5px; margin-left: 2px; display: none; }
    .fp-err.show { display: block; }
    .otp-row { display: flex; gap: 8px; justify-content: center; margin-bottom: 1.1rem; }
    .otp-box { width: 48px; height: 54px; text-align: center; border: 1.5px solid var(--border); border-radius: 12px; font-family: var(--font); font-size: 1.4rem; font-weight: 800; color: var(--text); background: #f8fafc; transition: all .18s; caret-color: transparent; outline: none; }
    .otp-box:focus { border-color: var(--indigo-mid); background: #fff; box-shadow: 0 0 0 3px rgba(67,56,202,.1); transform: translateY(-2px); }
    .otp-box.filled { border-color: var(--indigo-brd); background: var(--indigo-light); color: var(--indigo); }
    .otp-box.err { border-color: #fca5a5; background: #fef2f2; animation: shake .35s ease; }
    @keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
    .timer-pill { display: inline-flex; align-items: center; gap: 5px; background: var(--amber-bg); border: 1px solid #fde68a; color: #92400e; font-size: 11px; font-weight: 800; border-radius: 8px; padding: 4px 10px; margin-top: 6px; }
    .resend-row { text-align: center; font-size: 13px; color: var(--muted); font-weight: 600; margin-top: 10px; }
    .resend-btn { background: none; border: none; cursor: pointer; font-family: var(--font); font-size: 13px; font-weight: 700; color: var(--indigo-mid); transition: color .18s; padding: 0; }
    .resend-btn:hover { color: var(--indigo-dark); text-decoration: underline; }
    .resend-btn:disabled { color: var(--muted2); cursor: default; text-decoration: none; }
    .str-row { display: flex; gap: 4px; margin-top: 6px; }
    .str-seg { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background .3s; }
    .str-lbl { font-size: 11px; font-weight: 700; margin-top: 4px; text-align: right; }
    .fp-foot { flex-shrink: 0; padding: 14px 22px; border-top: 1px solid var(--border2); background: #fafafa; display: flex; gap: 8px; }
    .btn-fp-back { height: 44px; padding: 0 16px; background: #f1f5f9; color: var(--muted); border: 1.5px solid var(--border); border-radius: var(--r-xs); font-family: var(--font); font-weight: 700; font-size: 13px; cursor: pointer; transition: all .15s; display: flex; align-items: center; gap: 6px; }
    .btn-fp-back:hover { background: #e2e8f0; }
    .btn-fp-next { flex: 1; height: 44px; background: var(--indigo); color: #fff; border: none; border-radius: var(--r-xs); font-family: var(--font); font-weight: 800; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px; transition: all .18s; box-shadow: 0 4px 12px rgba(55,48,163,.28); letter-spacing: -.01em; }
    .btn-fp-next:hover { background: var(--indigo-dark); transform: translateY(-1px); }
    .btn-fp-next:disabled { opacity: .5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
    .spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    #_toast { position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%); border-radius: 14px; padding: 11px 18px; font-family: var(--font); font-size: 13px; font-weight: 600; box-shadow: 0 8px 28px rgba(0,0,0,.14); z-index: 9999; white-space: nowrap; display: flex; align-items: center; gap: 8px; }
    @keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(8px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="brand-bar">
      <span class="brand-tag">E-Learning Resource System</span>
      <div class="brand-name">my<em>Space.</em></div>
    </div>
    <div class="login-card">
      <div class="logo-hero">
        <div class="logo-ring">
          <img src="/assets/img/icon-192.png" alt="SK Brgy. F De Jesus">
        </div>
        <h1>Welcome Back!</h1>
        <p>Brgy. F De Jesus, Unisan Quezon</p>
      </div>
      <div class="welcome-pill">
        <div class="wp-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        <div class="wp-text">
          <h3>E-Learning Resource Reservation System</h3>
          <p>Sign in to access your account and manage reservations</p>
        </div>
      </div>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="flash flash-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <!-- FIX: esc() prevents XSS if an attacker-crafted message ever reaches here -->
          <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
      <?php endif; ?>
      <?php if(session()->getFlashdata('success')): ?>
        <div class="flash flash-success">
          <i class="fa-solid fa-circle-check"></i>
          <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
      <?php endif; ?>
      <?php if(session()->getFlashdata('info')): ?>
        <div class="flash flash-info">
          <i class="fa-solid fa-circle-info"></i>
          <span><?= esc(session()->getFlashdata('info')) ?></span>
        </div>
      <?php endif; ?>

      <form action="/login-action" method="post" id="loginForm">
        <?= csrf_field() ?>
        <div style="margin-bottom:1.1rem">
          <label class="field-lbl" for="email">Email Address</label>
          <div style="position:relative">
            <i class="field-icon fa-regular fa-envelope"></i>
            <!-- FIX: esc() prevents a crafted old('email') from breaking the HTML attribute -->
            <input type="email" id="email" name="email" class="field-input"
                   placeholder="your.email@example.com"
                   value="<?= esc(old('email')) ?>"
                   required autocomplete="email">
          </div>
        </div>
        <div style="margin-bottom:1rem">
          <label class="field-lbl" for="password">Password</label>
          <div style="position:relative">
            <i class="field-icon fa-solid fa-lock"></i>
            <input type="password" id="password" name="password" class="field-input pr"
                   placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="eye-btn" onclick="togglePwd()" aria-label="Toggle password">
              <i class="fa-regular fa-eye" id="pwdEyeIcon"></i>
            </button>
          </div>
        </div>
        <div class="row-meta">
          <label class="check-wrap">
            <input type="checkbox" name="remember" class="check-input" id="remember">
            <span class="check-lbl">Remember me</span>
          </label>
          <a href="#" class="forgot-link" onclick="openFP(); return false;">Forgot password?</a>
        </div>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-arrow-right-to-bracket" style="font-size:14px"></i>
          Sign In to mySpace
        </button>
        <div class="divider"><span>or</span></div>
        <div class="register-row">
          <span>New to our system?</span>
          <a href="/register">Create an account</a>
        </div>
      </form>
      <div class="footer-note">
        By signing in, you agree to our
        <button onclick="openModalT('terms')">Terms of Service</button>
        and
        <button onclick="openModalT('privacy')">Privacy Policy</button>
      </div>
    </div>
  </div>

  <!-- FORGOT PASSWORD MODAL -->
  <div class="overlay" id="fpOverlay">
    <div class="modal-box sm">
      <div class="modal-head">
        <div class="modal-head-left">
          <div class="modal-icon indigo"><i class="fa-solid fa-key"></i></div>
          <div>
            <div class="modal-head-title">Reset Password</div>
            <div class="modal-head-sub" id="fpSubtitle">Step 1 of 3 — Identify Account</div>
          </div>
        </div>
        <button class="modal-close-btn" onclick="closeFP()"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-body">
        <div class="fp-dots">
          <div class="fp-dot active" id="fd1"></div>
          <div class="fp-dot pend"  id="fd2"></div>
          <div class="fp-dot pend"  id="fd3"></div>
        </div>
        <!-- Step 1 -->
        <div class="fp-panel active" id="fp1">
          <div class="fp-hero">
            <div class="fp-hero-icon bl"><i class="fa-regular fa-envelope"></i></div>
            <h3>Forgot your password?</h3>
            <p>Enter your registered email and we'll send you a 6-digit verification code.</p>
          </div>
          <div style="margin-bottom:1rem">
            <label class="field-lbl" for="fpEmail">Email Address</label>
            <div style="position:relative">
              <i class="field-icon fa-regular fa-envelope"></i>
              <input type="email" id="fpEmail" class="field-input" placeholder="your.email@example.com" autocomplete="email">
            </div>
            <div class="fp-err" id="fpEmailErr"><i class="fa-solid fa-circle-exclamation" style="margin-right:4px"></i><span id="fpEmailMsg">Please enter a valid email.</span></div>
          </div>
          <div class="fp-info">
            <i class="fa-solid fa-circle-info"></i>
            <span>A 6-digit code will be sent to your email. Expires in <strong>10 minutes</strong>.</span>
          </div>
        </div>
        <!-- Step 2 -->
        <div class="fp-panel" id="fp2">
          <div class="fp-hero">
            <div class="fp-hero-icon am"><i class="fa-solid fa-shield-halved"></i></div>
            <h3>Enter Verification Code</h3>
            <p>We sent a 6-digit code to <strong id="fpEmailShow">your email</strong>.<br>Check your inbox and spam folder.</p>
          </div>
          <div class="otp-row">
            <input class="otp-box" id="o0" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
            <input class="otp-box" id="o1" maxlength="1" inputmode="numeric">
            <input class="otp-box" id="o2" maxlength="1" inputmode="numeric">
            <input class="otp-box" id="o3" maxlength="1" inputmode="numeric">
            <input class="otp-box" id="o4" maxlength="1" inputmode="numeric">
            <input class="otp-box" id="o5" maxlength="1" inputmode="numeric">
          </div>
          <div class="fp-err" id="fpOtpErr" style="text-align:center"><i class="fa-solid fa-circle-exclamation" style="margin-right:4px"></i><span id="fpOtpMsg">Invalid or expired code.</span></div>
          <div style="text-align:center">
            <div class="timer-pill" id="timerPill"><i class="fa-regular fa-clock"></i> Expires in <span id="fpTimer">10:00</span></div>
          </div>
          <div class="resend-row">Didn't receive it? <button class="resend-btn" id="fpResendBtn" onclick="fpResend()" disabled>Resend Code</button></div>
        </div>
        <!-- Step 3 -->
        <div class="fp-panel" id="fp3">
          <div class="fp-hero">
            <div class="fp-hero-icon gr"><i class="fa-solid fa-lock-open"></i></div>
            <h3>Create New Password</h3>
            <p>Identity verified. Set a strong, unique password for your account.</p>
          </div>
          <div style="margin-bottom:1rem">
            <label class="field-lbl" for="fpPwd">New Password</label>
            <div style="position:relative">
              <i class="field-icon fa-solid fa-lock"></i>
              <input type="password" id="fpPwd" class="field-input pr" placeholder="Min. 8 characters" autocomplete="new-password" oninput="fpStrength()">
              <button type="button" class="eye-btn" onclick="fpToggle('fpPwd','fpEye1')"><i class="fa-regular fa-eye" id="fpEye1"></i></button>
            </div>
            <div class="str-row">
              <div class="str-seg" id="ss1"></div><div class="str-seg" id="ss2"></div>
              <div class="str-seg" id="ss3"></div><div class="str-seg" id="ss4"></div>
            </div>
            <div class="str-lbl" id="fpStrLbl" style="color:var(--muted2)"></div>
            <div class="fp-err" id="fpPwdErr"><i class="fa-solid fa-circle-exclamation" style="margin-right:4px"></i>Password must be at least 8 characters.</div>
          </div>
          <div style="margin-bottom:1rem">
            <label class="field-lbl" for="fpConf">Confirm Password</label>
            <div style="position:relative">
              <i class="field-icon fa-solid fa-lock"></i>
              <input type="password" id="fpConf" class="field-input pr" placeholder="Re-enter your password" autocomplete="new-password">
              <button type="button" class="eye-btn" onclick="fpToggle('fpConf','fpEye2')"><i class="fa-regular fa-eye" id="fpEye2"></i></button>
            </div>
            <div class="fp-err" id="fpConfErr"><i class="fa-solid fa-circle-exclamation" style="margin-right:4px"></i>Passwords do not match.</div>
          </div>
        </div>
      </div>
      <div class="fp-foot">
        <button class="btn-fp-back" id="fpBackBtn" onclick="fpBack()" style="display:none">
          <i class="fa-solid fa-arrow-left" style="font-size:12px"></i> Back
        </button>
        <button class="btn-fp-next" id="fpNextBtn" onclick="fpNext()">
          <span id="fpNextLbl"><i class="fa-regular fa-paper-plane" style="font-size:13px"></i> Send Code</span>
        </button>
      </div>
    </div>
  </div>

  <!-- TERMS MODAL -->
  <div class="overlay" id="termsOverlay">
    <div class="modal-box">
      <div class="modal-head">
        <div class="modal-head-left">
          <div class="modal-icon indigo"><i class="fa-solid fa-file-contract"></i></div>
          <div><div class="modal-head-title">Terms of Service</div><div class="modal-head-sub">E-Learning Resource Reservation System</div></div>
        </div>
        <button class="modal-close-btn" onclick="closeModalT('terms')"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-prog-bar"><div class="modal-prog-fill fill-indigo" id="termsProg"></div></div>
      <div class="modal-body" id="termsBody">
        <div class="ts"><div class="ts-head"><div class="ts-num in">01</div><h3>Acceptance of Terms</h3></div><p>By accessing and using the E-Learning Resource Reservation System of Brgy. F De Jesus, Unisan Quezon, you accept and agree to be bound by these Terms of Service. If you do not agree, you may not use this system.</p><div class="ts-hl hl-in"><i class="fa-solid fa-circle-info" style="margin-right:5px"></i>These terms apply to all users including students, faculty, and administrators.</div></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">02</div><h3>System Use &amp; Eligibility</h3></div><p>This system is intended exclusively for authorized members. You agree to:</p><ul><li class="li-in">Provide accurate and truthful information when creating an account or making a reservation.</li><li class="li-in">Use reserved resources only during your approved reservation period.</li><li class="li-in">Not share your login credentials with any other person.</li><li class="li-in">Notify administrators of any unauthorized access to your account.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">03</div><h3>Reservation Policy</h3></div><p>All reservations are subject to approval by authorized SK personnel or administrators.</p><ul><li class="li-in">Reservations are not confirmed until officially approved.</li><li class="li-in">You must present your e-ticket QR code upon arrival.</li><li class="li-in">Failure to appear within 15 minutes may result in cancellation.</li><li class="li-in">Misuse of reserved resources may result in account suspension.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">04</div><h3>Responsible Use of Resources</h3></div><ul><li class="li-in">Treat all equipment with care and report any damage immediately.</li><li class="li-in">Not install unauthorized software or modify system settings.</li><li class="li-in">Use resources solely for educational and approved purposes.</li><li class="li-in">Leave the workstation clean and in its original condition after use.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">05</div><h3>Account Suspension &amp; Termination</h3></div><p>The administration reserves the right to suspend or terminate accounts that violate these terms, including repeated no-shows, misuse of equipment, or providing false information.</p></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">06</div><h3>Amendments</h3></div><p>These terms may be updated from time to time. Continued use of the system after changes constitutes your acceptance.</p><p style="margin-top:6px;font-size:11px;color:var(--muted2);font-weight:600">Last updated: <?= date('F j, Y') ?></p></div>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-note"><i class="fa-solid fa-eye" style="color:var(--muted2)"></i><span id="termsNote">Scroll through all sections to enable acceptance.</span></div>
        <div class="must-hint" id="termsMust"><i class="fa-solid fa-arrow-down" style="margin-right:4px"></i> Please scroll to the bottom to accept.</div>
        <div class="modal-foot-btns">
          <button class="btn-modal-cancel" onclick="closeModalT('terms')">Decline</button>
          <button class="btn-modal-accept c-in" id="termsAccept" onclick="acceptT('terms')" disabled><i class="fa-solid fa-circle-check"></i> I Accept These Terms</button>
        </div>
      </div>
    </div>
  </div>

  <!-- PRIVACY MODAL -->
  <div class="overlay" id="privacyOverlay">
    <div class="modal-box">
      <div class="modal-head">
        <div class="modal-head-left">
          <div class="modal-icon purple"><i class="fa-solid fa-shield-halved"></i></div>
          <div><div class="modal-head-title">Privacy Policy</div><div class="modal-head-sub">E-Learning Resource Reservation System</div></div>
        </div>
        <button class="modal-close-btn" onclick="closeModalT('privacy')"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-prog-bar"><div class="modal-prog-fill fill-purple" id="privacyProg"></div></div>
      <div class="modal-body" id="privacyBody">
        <div class="ts"><div class="ts-head"><div class="ts-num pu">01</div><h3>Introduction</h3></div><p>We are committed to full compliance with the Data Privacy Act of 2012 (RA 10173) of the Philippines.</p><div class="ts-hl hl-pu"><i class="fa-solid fa-shield-halved" style="margin-right:5px"></i>Your data is protected under the Data Privacy Act of 2012 (Republic Act No. 10173).</div></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">02</div><h3>Information We Collect</h3></div><ul><li class="li-pu">Full name and contact details (email address).</li><li class="li-pu">Login credentials (stored in encrypted form).</li><li class="li-pu">Reservation history including dates, times, and resources used.</li><li class="li-pu">Activity logs such as login timestamps and system actions.</li><li class="li-pu">Device and browser information for security purposes.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">03</div><h3>How We Use Your Information</h3></div><ul><li class="li-pu">To process and manage your resource reservations.</li><li class="li-pu">To verify your identity and authenticate your access.</li><li class="li-pu">To send reservation confirmations, updates, and e-tickets.</li><li class="li-pu">To generate reports for barangay administration and accountability.</li><li class="li-pu">To improve system functionality and user experience.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">04</div><h3>Data Sharing &amp; Disclosure</h3></div><p>We do not sell, rent, or trade your personal information to third parties.</p><div class="ts-hl hl-pu"><i class="fa-solid fa-lock" style="margin-right:5px"></i>Your data is never sold or shared with advertisers or commercial third parties.</div></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">05</div><h3>Data Retention</h3></div><p>We retain your personal information for as long as your account is active. Reservation records are kept for a minimum of one (1) year for audit and accountability purposes.</p></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">06</div><h3>Data Security</h3></div><ul><li class="li-pu">Password hashing using industry-standard encryption algorithms.</li><li class="li-pu">Secure HTTPS connections for all data transmission.</li><li class="li-pu">Role-based access controls limiting data access to authorized personnel only.</li><li class="li-pu">Regular security reviews and activity monitoring.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">07</div><h3>Your Rights (RA 10173)</h3></div><ul><li class="li-pu"><strong>Right to be informed</strong> — about how your data is collected and used.</li><li class="li-pu"><strong>Right to access</strong> — to request a copy of your personal data.</li><li class="li-pu"><strong>Right to rectification</strong> — to correct inaccurate or incomplete data.</li><li class="li-pu"><strong>Right to erasure</strong> — to request deletion, subject to legal limits.</li><li class="li-pu"><strong>Right to object</strong> — to processing for specific purposes.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">08</div><h3>Cookies &amp; Session Data</h3></div><p>This system uses session cookies to maintain your login state. These cookies do not track you across other websites.</p></div>
        <div class="ts"><div class="ts-head"><div class="ts-num pu">09</div><h3>Changes to This Policy</h3></div><p>We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements.</p><p style="margin-top:6px;font-size:11px;color:var(--muted2);font-weight:600">Last updated: <?= date('F j, Y') ?></p></div>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-note"><i class="fa-solid fa-eye" style="color:var(--muted2)"></i><span id="privacyNote">Scroll through all sections to enable acceptance.</span></div>
        <div class="must-hint" id="privacyMust"><i class="fa-solid fa-arrow-down" style="margin-right:4px"></i> Please scroll to the bottom to accept.</div>
        <div class="modal-foot-btns">
          <button class="btn-modal-cancel" onclick="closeModalT('privacy')">Close</button>
          <button class="btn-modal-accept c-pu" id="privacyAccept" onclick="acceptT('privacy')" disabled><i class="fa-solid fa-shield-halved"></i> I Acknowledge This Policy</button>
        </div>
      </div>
    </div>
  </div>

<script>
/* ── CSRF
 * FIX 1: Use `let` (not `const`) so _csrfHash can be updated.
 * FIX 2: _getActiveCsrf() reads from the CI4 cookie after every response,
 *         so the multi-step forgot-password flow keeps working even after
 *         CI4 regenerates the token on each POST.
 * ─────────────────────────────────────────────────────────────────── */
const _csrfName       = '<?= csrf_token() ?>';
const _csrfCookieName = '<?= config('Security')->cookieName ?>';
let   _csrfHash       = '<?= csrf_hash() ?>';

function _getActiveCsrf() {
  try {
    const re = new RegExp('(?:^|;\\s*)' + _csrfCookieName + '=([^;]*)');
    const m  = document.cookie.match(re);
    return m ? decodeURIComponent(m[1]) : _csrfHash;
  } catch(e) { return _csrfHash; }
}

function _hdrs() {
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    [_csrfName]: _getActiveCsrf()   // always reads the latest token
  };
}

/* ── Login form ── */
function togglePwd(){
  const i=document.getElementById('password'), ic=document.getElementById('pwdEyeIcon');
  i.type=i.type==='password'?'text':'password';
  ic.className=i.type==='password'?'fa-regular fa-eye':'fa-regular fa-eye-slash';
}
document.getElementById('loginForm').addEventListener('submit',function(e){
  const email=document.getElementById('email').value.trim();
  const pwd=document.getElementById('password').value;
  if(!email||!pwd){e.preventDefault();showToast('Please fill in all fields.','error');}
});
setTimeout(()=>{
  document.querySelectorAll('.flash').forEach(el=>{
    el.style.transition='opacity .5s'; el.style.opacity='0';
    setTimeout(()=>el.remove(),500);
  });
},5000);

/* ── Terms / Privacy Modals ── */
const mRead={terms:false,privacy:false};
function openModalT(t){
  const ov=document.getElementById(t+'Overlay');
  const body=document.getElementById(t+'Body');
  ov.classList.add('open');
  document.body.style.overflow='hidden';
  body.scrollTop=0;
  updProg(t);
}
function closeModalT(t){ document.getElementById(t+'Overlay').classList.remove('open'); document.body.style.overflow=''; }
function acceptT(t){
  if(!mRead[t]){document.getElementById(t+'Must').classList.add('show');return;}
  closeModalT(t);
  showToast(t==='terms'?'Terms of Service accepted.':' Privacy Policy acknowledged.','success');
}
function updProg(t){
  const b=document.getElementById(t+'Body');
  const tot=b.scrollHeight-b.clientHeight;
  const pct=tot>0?Math.min(100,Math.round(b.scrollTop/tot*100)):100;
  document.getElementById(t+'Prog').style.width=pct+'%';
  if(pct>=95&&!mRead[t]){
    mRead[t]=true;
    const btn=document.getElementById(t+'Accept');
    const note=document.getElementById(t+'Note');
    btn.disabled=false;
    note.textContent='You have reviewed the policy. You may now accept.';
    note.style.color=t==='terms'?'var(--indigo)':'#7c3aed';
    document.getElementById(t+'Must').classList.remove('show');
  }
}
['terms','privacy'].forEach(t=>{
  document.getElementById(t+'Body').addEventListener('scroll',()=>updProg(t));
  document.getElementById(t+'Overlay').addEventListener('click',function(e){if(e.target===this)closeModalT(t);});
});

/* ── Forgot Password ── */
let fpStep=1,fpToken=null,fpEmail='',fpTi=null,fpRt=null,fpSec=600;

function openFP(){
  fpReset();
  document.getElementById('fpOverlay').classList.add('open');
  document.body.style.overflow='hidden';
  setTimeout(()=>document.getElementById('fpEmail')?.focus(),300);
}
function closeFP(){
  document.getElementById('fpOverlay').classList.remove('open');
  document.body.style.overflow='';
  clearInterval(fpTi);
  clearTimeout(fpRt);
}
function fpReset(){
  fpStep=1;fpToken=null;fpEmail='';
  clearInterval(fpTi);clearTimeout(fpRt);
  fpShowStep(1);
  ['fpEmail','fpPwd','fpConf'].forEach(id=>{
    const el=document.getElementById(id); if(el) el.value='';
  });
  for(let i=0;i<6;i++){
    const el=document.getElementById('o'+i);
    if(el){el.value='';el.classList.remove('filled','err');}
  }
  ['fpEmailErr','fpOtpErr','fpPwdErr','fpConfErr'].forEach(id=>{
    const el=document.getElementById(id);
    if(el){el.style.display='none';el.classList.remove('show');}
  });
  [1,2,3,4].forEach(j=>{ const el=document.getElementById('ss'+j); if(el) el.style.background='var(--border)'; });
  const lbl=document.getElementById('fpStrLbl'); if(lbl) lbl.textContent='';
}
function fpShowStep(n){
  [1,2,3].forEach(i=>{
    const s=document.getElementById('fp'+i);
    const d=document.getElementById('fd'+i);
    if(s) s.classList.toggle('active',i===n);
    if(d) d.className='fp-dot '+(i<n?'done':i===n?'active':'pend');
  });
  const back=document.getElementById('fpBackBtn');
  if(back) back.style.display=n>1?'flex':'none';
  const labels={
    1:'<i class="fa-regular fa-paper-plane" style="font-size:13px"></i> Send Code',
    2:'<i class="fa-solid fa-shield-halved" style="font-size:13px"></i> Verify Code',
    3:'<i class="fa-solid fa-check" style="font-size:13px"></i> Reset Password'
  };
  const subs={1:'Step 1 of 3 — Identify Account',2:'Step 2 of 3 — Verify Identity',3:'Step 3 of 3 — Set New Password'};
  const lbl=document.getElementById('fpNextLbl');
  const sub=document.getElementById('fpSubtitle');
  if(lbl) lbl.innerHTML=labels[n];
  if(sub) sub.textContent=subs[n];
}
function fpNext(){
  const btn=document.getElementById('fpNextBtn');
  if(btn&&btn.disabled) return;
  if(fpStep===1) fpS1();
  else if(fpStep===2) fpS2();
  else fpS3();
}
function fpBack(){
  if(fpStep>1){
    fpStep--;
    fpShowStep(fpStep);
    if(fpStep===1){ clearInterval(fpTi); clearTimeout(fpRt); }
  }
}
async function fpS1(){
  const email=document.getElementById('fpEmail')?.value.trim();
  if(!email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
    document.getElementById('fpEmailMsg').textContent='Please enter a valid email address.';
    fpErr('fpEmailErr',true);
    document.getElementById('fpEmail')?.focus();
    return;
  }
  fpErr('fpEmailErr',false);
  fpLoad(true,'Sending Code…');
  try{
    const r=await fetch('/forgot-password/send-otp',{method:'POST',headers:_hdrs(),body:JSON.stringify({email})});
    let d; try{d=await r.json();}catch{throw new Error();}
    if(d.success){
      fpEmail=email;
      document.getElementById('fpEmailShow').textContent=email;
      fpStep=2;
      fpShowStep(2);
      fpStartTimer();
      setTimeout(()=>document.getElementById('o0')?.focus(),100);
    }else{
      document.getElementById('fpEmailMsg').textContent=d.message||'Email not found in our system.';
      fpErr('fpEmailErr',true);
    }
  }catch{
    document.getElementById('fpEmailMsg').textContent='Network error. Please try again.';
    fpErr('fpEmailErr',true);
  }finally{ fpLoad(false); }
}
async function fpS2(){
  const otp=[0,1,2,3,4,5].map(i=>document.getElementById('o'+i)?.value||'').join('');
  if(otp.length<6){ fpOtpErr('Please enter the complete 6-digit code.'); return; }
  fpLoad(true,'Verifying…');
  try{
    const r=await fetch('/forgot-password/verify-otp',{method:'POST',headers:_hdrs(),body:JSON.stringify({email:fpEmail,otp})});
    let d; try{d=await r.json();}catch{throw new Error();}
    if(d.success){
      fpToken=d.reset_token;
      fpErr('fpOtpErr',false);
      clearInterval(fpTi); clearTimeout(fpRt);
      fpStep=3;
      fpShowStep(3);
      setTimeout(()=>document.getElementById('fpPwd')?.focus(),100);
    }else{ fpOtpErr(d.message||'Invalid or expired code.'); }
  }catch{ fpOtpErr('Network error. Please try again.'); }
  finally{ fpLoad(false); }
}
async function fpS3(){
  if(!fpToken){ showToast('Session expired. Please start over.','error'); fpReset(); return; }
  const pwd=document.getElementById('fpPwd')?.value||'';
  const conf=document.getElementById('fpConf')?.value||'';
  let ok=true;
  if(pwd.length<8){fpErr('fpPwdErr',true);ok=false;}else fpErr('fpPwdErr',false);
  if(pwd!==conf||!conf){fpErr('fpConfErr',true);ok=false;}else fpErr('fpConfErr',false);
  if(!ok) return;
  fpLoad(true,'Updating Password…');
  try{
    const r=await fetch('/forgot-password/reset-password',{method:'POST',headers:_hdrs(),body:JSON.stringify({reset_token:fpToken,password:pwd,password_confirm:conf})});
    let d; try{d=await r.json();}catch{throw new Error();}
    if(d.success){
      closeFP();
      showToast(d.message||'Password reset successfully! You can now sign in.','success');
    }else{
      showToast(d.message||'Session expired. Please start over.','error');
      fpReset();
    }
  }catch{ showToast('Network error. Please try again.','error'); }
  finally{ fpLoad(false); }
}
async function fpResend(){
  for(let i=0;i<6;i++){
    const el=document.getElementById('o'+i);
    if(el){el.value='';el.classList.remove('filled','err');}
  }
  fpErr('fpOtpErr',false);
  document.getElementById('fpResendBtn').disabled=true;
  try{
    const r=await fetch('/forgot-password/send-otp',{method:'POST',headers:_hdrs(),body:JSON.stringify({email:fpEmail})});
    let d; try{d=await r.json();}catch{throw new Error();}
    if(d.success){
      fpStartTimer();
      showToast('A new code has been sent to your email.','info');
      setTimeout(()=>document.getElementById('o0')?.focus(),100);
    }else{
      showToast(d.message||'Could not resend code.','error');
      document.getElementById('fpResendBtn').disabled=false;
    }
  }catch{
    showToast('Network error.','error');
    document.getElementById('fpResendBtn').disabled=false;
  }
}
function fpLoad(on,lbl){
  const btn=document.getElementById('fpNextBtn');
  if(btn) btn.disabled=on;
  if(on){ document.getElementById('fpNextLbl').innerHTML='<div class="spin"></div> '+lbl; }
  else{ fpShowStep(fpStep); }
}
function fpErr(id,show){
  const el=document.getElementById(id); if(!el) return;
  el.style.display=show?'block':'none';
  el.classList.toggle('show',show);
}
function fpOtpErr(msg){
  document.getElementById('fpOtpMsg').textContent=msg;
  fpErr('fpOtpErr',true);
  for(let i=0;i<6;i++){const el=document.getElementById('o'+i);if(el)el.classList.add('err');}
  setTimeout(()=>{ for(let i=0;i<6;i++){const el=document.getElementById('o'+i);if(el)el.classList.remove('err');} },600);
}
function fpStartTimer(){
  fpSec=600; clearInterval(fpTi);
  const p=document.getElementById('timerPill');
  if(p){p.style.background='';p.style.borderColor='';p.style.color='';}
  fpUpdTimer();
  fpTi=setInterval(()=>{
    fpSec--; fpUpdTimer();
    if(fpSec<=0){
      clearInterval(fpTi);
      document.getElementById('fpTimer').textContent='Expired';
      if(p){p.style.background='var(--red-bg)';p.style.borderColor='#fecaca';p.style.color='#991b1b';}
    }
  },1000);
  document.getElementById('fpResendBtn').disabled=true;
  clearTimeout(fpRt);
  fpRt=setTimeout(()=>document.getElementById('fpResendBtn').disabled=false,60000);
}
function fpUpdTimer(){
  const m=Math.floor(fpSec/60).toString().padStart(2,'0');
  const s=(fpSec%60).toString().padStart(2,'0');
  document.getElementById('fpTimer').textContent=m+':'+s;
}
function fpStrength(){
  const p=document.getElementById('fpPwd').value;
  let sc=0;
  if(p.length>=8)sc++; if(/[A-Z]/.test(p))sc++; if(/\d/.test(p))sc++; if(/[^A-Za-z0-9]/.test(p))sc++;
  const c=['','#ef4444','#f97316','#eab308','#22c55e'];
  const l=['','Weak','Fair','Good','Strong'];
  const lc=['','#dc2626','#c2410c','#a16207','#15803d'];
  [1,2,3,4].forEach(j=>document.getElementById('ss'+j).style.background=j<=sc?c[sc]:'var(--border)');
  const lb=document.getElementById('fpStrLbl');
  lb.textContent=p?l[sc]:''; lb.style.color=p?lc[sc]:'var(--muted2)';
}
function fpToggle(id,ic){
  const i=document.getElementById(id),ico=document.getElementById(ic);
  i.type=i.type==='password'?'text':'password';
  ico.className=i.type==='password'?'fa-regular fa-eye':'fa-regular fa-eye-slash';
}
document.addEventListener('DOMContentLoaded',()=>{
  for(let i=0;i<6;i++){
    const el=document.getElementById('o'+i); if(!el) continue;
    el.addEventListener('input',function(){
      const v=this.value.replace(/\D/g,'');
      this.value=v.slice(-1);
      if(v){ this.classList.add('filled'); if(i<5)document.getElementById('o'+(i+1))?.focus(); if(i===5)setTimeout(fpNext,200); }
      else this.classList.remove('filled');
      fpErr('fpOtpErr',false);
    });
    el.addEventListener('keydown',function(e){
      if(e.key==='Backspace'&&!this.value&&i>0)document.getElementById('o'+(i-1))?.focus();
      if(e.key==='ArrowLeft'&&i>0)document.getElementById('o'+(i-1))?.focus();
      if(e.key==='ArrowRight'&&i<5)document.getElementById('o'+(i+1))?.focus();
      if(e.key==='Enter')fpNext();
    });
    el.addEventListener('paste',function(e){
      e.preventDefault();
      const p=(e.clipboardData||window.clipboardData).getData('text').replace(/\D/g,'');
      p.split('').slice(0,6).forEach((c,j)=>{ const t=document.getElementById('o'+j); if(t){t.value=c;t.classList.add('filled');} });
      document.getElementById('o'+Math.min(5,p.length-1))?.focus();
    });
  }
  document.getElementById('fpEmail').addEventListener('keydown',e=>{if(e.key==='Enter')fpNext();});
  document.getElementById('fpPwd').addEventListener('keydown',e=>{if(e.key==='Enter')document.getElementById('fpConf').focus();});
  document.getElementById('fpConf').addEventListener('keydown',e=>{if(e.key==='Enter')fpNext();});
});
document.getElementById('fpOverlay').addEventListener('click',function(e){if(e.target===this)closeFP();});
document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ closeFP(); ['terms','privacy'].forEach(closeModalT); } });

/* ── Toast ── */
function showToast(msg,type){
  const ex=document.getElementById('_toast'); if(ex)ex.remove();
  const c={
    success:{bg:'#f0fdf4',br:'#bbf7d0',tx:'#166534',ic:'fa-circle-check'},
    error:{bg:'#fef2f2',br:'#fecaca',tx:'#991b1b',ic:'fa-circle-exclamation'},
    info:{bg:'var(--indigo-light)',br:'var(--indigo-brd)',tx:'var(--indigo)',ic:'fa-circle-info'}
  }[type]||{bg:'var(--indigo-light)',br:'var(--indigo-brd)',tx:'var(--indigo)',ic:'fa-circle-info'};
  const t=document.createElement('div');
  t.id='_toast';
  t.style.cssText=`position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);background:${c.bg};border:1px solid ${c.br};color:${c.tx};padding:11px 18px;border-radius:14px;font-family:var(--font);font-size:13px;font-weight:600;box-shadow:0 8px 28px rgba(0,0,0,.14);z-index:9999;white-space:nowrap;display:flex;align-items:center;gap:8px;animation:toastIn .3s ease`;
  t.innerHTML=`<i class="fa-solid ${c.ic}"></i>${msg}`;
  document.body.appendChild(t);
  setTimeout(()=>{t.style.transition='opacity .4s';t.style.opacity='0';setTimeout(()=>t.remove(),400);},type==='success'?4500:3500);
}
if('serviceWorker' in navigator){
  window.addEventListener('load',()=>{ navigator.serviceWorker.register('/sw.js',{scope:'/'}).catch(()=>{}); });
}
</script>
</body>
</html>