<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Register | E-Learning Resource Reservation System</title>
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#0f1628">
  <link rel="icon" type="image/png" href="/assets/img/icon-192.png">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --navy:        #0f1628;
      --navy-card:   #151f38;
      --navy-border: rgba(99,132,255,.15);
      --indigo:      #3730a3;
      --indigo-mid:  #4338ca;
      --indigo-dark: #312e81;
      --gold:        #f59e0b;
      --gold-light:  #fcd34d;
      --text:        #e2e8f0;
      --text-dim:    #94a3b8;
      --text-dimmer: #64748b;
      --border:      rgba(99,132,255,.12);
      --border2:     rgba(99,132,255,.07);
      --green:       #22c55e;
      --green-bg:    rgba(34,197,94,.12);
      --red:         #ef4444;
      --red-bg:      rgba(239,68,68,.12);
      --amber:       #f59e0b;
      --amber-bg:    rgba(245,158,11,.12);
      --shadow-lg:   0 20px 60px rgba(0,0,0,.5), 0 4px 16px rgba(0,0,0,.3);
      --r:           20px;
      --r-md:        14px;
      --r-sm:        10px;
      --r-xs:        8px;
      --font:        'Plus Jakarta Sans', system-ui, sans-serif;
    }
    html { height: 100%; }
    body {
      font-family: var(--font);
      background: var(--navy);
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 1.5rem;
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: '';
      position: fixed; inset: 0;
      background:
        radial-gradient(ellipse 900px 600px at 85% -5%, rgba(67,56,202,.18) 0%, transparent 60%),
        radial-gradient(ellipse 700px 500px at -10% 105%, rgba(99,102,241,.12) 0%, transparent 55%),
        radial-gradient(ellipse 500px 400px at 50% 50%, rgba(245,158,11,.04) 0%, transparent 65%);
      pointer-events: none; z-index: 0;
    }
    body::after {
      content: '';
      position: fixed; inset: 0;
      background-image: linear-gradient(rgba(99,132,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(99,132,255,.03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none; z-index: 0;
    }

    .register-wrapper { width: 100%; max-width: 480px; position: relative; z-index: 1; padding-bottom: 1.5rem; }

    /* ── Back Button ── */
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--text-dim);
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      margin-bottom: 20px;
      padding: 8px 14px;
      border-radius: var(--r-sm);
      border: 1px solid var(--border);
      background: rgba(255,255,255,.04);
      backdrop-filter: blur(8px);
      transition: all .2s;
      cursor: pointer;
      font-family: var(--font);
    }
    .back-btn:hover {
      color: var(--text);
      border-color: rgba(99,132,255,.3);
      background: rgba(255,255,255,.07);
      transform: translateX(-2px);
    }
    .back-btn i { font-size: 11px; }

    /* ── Brand ── */
    .brand-bar { text-align: center; margin-bottom: 22px; }
    .brand-tag { font-size: 9px; font-weight: 700; letter-spacing: .22em; text-transform: uppercase; color: var(--text-dimmer); display: block; margin-bottom: 5px; }
    .brand-name { font-size: 28px; font-weight: 900; color: #fff; letter-spacing: -.04em; line-height: 1; }
    .brand-name em { font-style: normal; color: var(--gold); }

    /* ── Card ── */
    .register-card {
      background: rgba(21, 31, 56, 0.85);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border-radius: var(--r);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99,102,241,.06);
      padding: 2.25rem 2rem;
      width: 100%;
      position: relative;
      overflow: hidden;
    }
    .register-card::before {
      content: '';
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      width: 200px; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(99,132,255,.5), transparent);
    }
    @media (min-width: 480px) { .register-card { padding: 2.5rem 2.5rem; } }

    /* ── Logo Hero ── */
    .logo-hero { display: flex; flex-direction: column; align-items: center; margin-bottom: 1.75rem; }
    .logo-ring {
      width: 80px; height: 80px;
      border-radius: 50%;
      background: radial-gradient(circle at 40% 35%, rgba(245,158,11,.2) 0%, rgba(67,56,202,.15) 60%, transparent 80%);
      border: 1.5px solid rgba(245,158,11,.35);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 14px;
      overflow: hidden;
      box-shadow: 0 0 0 4px rgba(245,158,11,.08), 0 0 20px rgba(245,158,11,.15), 0 0 50px rgba(67,56,202,.2), 0 8px 24px rgba(0,0,0,.4);
      position: relative;
    }
    .logo-ring::before {
      content: ''; position: absolute; inset: -2px; border-radius: 50%;
      background: conic-gradient(from 0deg, transparent 60%, rgba(245,158,11,.4) 80%, transparent 100%);
      animation: logoSpin 4s linear infinite; z-index: 0;
    }
    @keyframes logoSpin { to { transform: rotate(360deg); } }
    .logo-ring img { width: 84%; height: 84%; object-fit: contain; border-radius: 50%; position: relative; z-index: 1; }

    .logo-hero h1 { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: -.03em; text-align: center; line-height: 1.2; }
    .logo-hero p { font-size: 12px; color: var(--text-dimmer); font-weight: 500; margin-top: 4px; text-align: center; }

    /* ── Welcome pill ── */
    .welcome-pill { background: rgba(99,102,241,.08); border: 1px solid rgba(99,132,255,.2); border-radius: var(--r-sm); padding: 10px 14px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
    .wp-icon { width: 30px; height: 30px; border-radius: 9px; background: linear-gradient(135deg, var(--indigo-mid), var(--indigo)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(67,56,202,.4); }
    .wp-text h3 { font-size: 12px; font-weight: 800; color: #a5b4fc; letter-spacing: -.01em; }
    .wp-text p { font-size: 11px; color: var(--text-dimmer); font-weight: 500; margin-top: 1px; }

    /* ── SK Notice ── */
    .sk-notice { display: none; background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.2); border-radius: var(--r-sm); padding: 11px 14px; margin-bottom: 1.25rem; align-items: flex-start; gap: 10px; font-size: 12px; font-weight: 600; color: #fcd34d; line-height: 1.55; animation: fadeDown .3s ease; }
    .sk-notice.show { display: flex; }
    .sk-notice i { color: var(--gold); margin-top: 1px; flex-shrink: 0; font-size: 14px; }
    .sk-notice strong { color: #fde68a; }

    @keyframes fadeDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:none; } }

    /* ── Fields ── */
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: .9rem; }
    @media (max-width: 440px) { .field-row { grid-template-columns: 1fr; } }

    .field-lbl { display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .16em; color: var(--text-dimmer); margin-bottom: 6px; margin-left: 2px; }
    .field-wrap { position: relative; margin-bottom: 1.1rem; }
    .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-dimmer); font-size: 13px; pointer-events: none; transition: color .2s; z-index: 2; }
    .field-input {
      width: 100%; height: 48px; padding: 0 14px 0 40px;
      border: 1.5px solid var(--border);
      border-radius: var(--r-sm);
      font-family: var(--font); font-size: 14px;
      background: rgba(255,255,255,.04);
      color: var(--text);
      transition: all .2s; outline: none; appearance: none;
    }
    .field-input:focus { border-color: rgba(99,132,255,.5); background: rgba(255,255,255,.07); box-shadow: 0 0 0 3px rgba(67,56,202,.15), 0 0 12px rgba(67,56,202,.1); }
    .field-wrap:focus-within .field-icon { color: #a5b4fc; }
    .field-input::placeholder { color: rgba(148,163,184,.35); font-weight: 400; }
    .field-input.pr { padding-right: 44px; }

    .select-wrap { position: relative; }
    .select-wrap::after { content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-dimmer); font-size: 11px; pointer-events: none; z-index: 3; }
    .field-input option { background: #1a2545; color: var(--text); }

    .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-dimmer); cursor: pointer; padding: 4px; display: flex; align-items: center; font-size: 14px; transition: color .18s; z-index: 3; }
    .eye-btn:hover { color: #a5b4fc; }
    .eye-btn:focus { outline: none; }

    .str-row { display: flex; gap: 4px; margin-top: 6px; }
    .str-seg { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background .3s; }
    .str-lbl { font-size: 11px; font-weight: 700; margin-top: 4px; text-align: right; }

    .pw-reqs { background: rgba(255,255,255,.03); border: 1px solid var(--border); border-radius: var(--r-xs); padding: 10px 13px; margin-top: 8px; display: none; }
    .pw-reqs-title { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: var(--text-dimmer); margin-bottom: 7px; }
    .req { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: var(--text-dimmer); padding: 2px 0; transition: color .2s; }
    .req i { font-size: 11px; width: 13px; transition: color .2s; }
    .req.met { color: #86efac; }
    .req.met i { color: #86efac; }

    .pw-match { display: none; align-items: center; gap: 7px; font-size: 12px; font-weight: 700; margin-top: 7px; padding: 7px 11px; border-radius: var(--r-xs); }
    .pw-match.bad  { display: flex; color: #fca5a5; background: var(--red-bg); border: 1px solid rgba(239,68,68,.25); }
    .pw-match.good { display: flex; color: #86efac; background: var(--green-bg); border: 1px solid rgba(34,197,94,.25); }

    .check-wrap { display: flex; align-items: flex-start; gap: 9px; cursor: pointer; user-select: none; margin-bottom: 1.25rem; }
    .check-input { appearance: none; width: 17px; height: 17px; min-width: 17px; border: 1.5px solid var(--border); border-radius: 6px; background: rgba(255,255,255,.05); cursor: pointer; position: relative; flex-shrink: 0; transition: all .18s; margin-top: 2px; }
    .check-input:checked { background: var(--indigo-mid); border-color: var(--indigo-mid); }
    .check-input:checked::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 9px; }
    .check-input:focus { box-shadow: 0 0 0 3px rgba(67,56,202,.2); outline: none; }
    .check-lbl { font-size: 13px; color: var(--text-dim); font-weight: 500; line-height: 1.5; }
    .check-lbl a { color: #a5b4fc; font-weight: 700; text-decoration: none; transition: color .18s; }
    .check-lbl a:hover { color: #c7d2fe; text-decoration: underline; }

    /* ── Submit btn ── */
    .btn-submit {
      width: 100%; height: 50px;
      background: linear-gradient(135deg, var(--indigo-mid), var(--indigo));
      color: #fff; font-family: var(--font); font-weight: 800; font-size: 14px;
      border: none; border-radius: var(--r-sm);
      cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: all .2s;
      box-shadow: 0 4px 20px rgba(67,56,202,.45), 0 1px 0 rgba(255,255,255,.1) inset;
      position: relative; overflow: hidden; letter-spacing: -.01em;
    }
    .btn-submit::after { content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent); transition: left .5s; }
    .btn-submit:hover { background: linear-gradient(135deg, #4f46e5, #3730a3); box-shadow: 0 6px 28px rgba(67,56,202,.6); transform: translateY(-1px); }
    .btn-submit:hover::after { left: 150%; }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit:disabled { opacity: .45; cursor: not-allowed; transform: none !important; box-shadow: none !important; }

    .divider { display: flex; align-items: center; gap: 10px; margin: 1.25rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .divider span { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: var(--text-dimmer); }

    .login-row { text-align: center; font-size: 13px; color: var(--text-dim); font-weight: 500; }
    .login-row a { color: #a5b4fc; font-weight: 700; text-decoration: none; transition: color .18s; }
    .login-row a:hover { color: #c7d2fe; text-decoration: underline; }

    .footer-note { text-align: center; margin-top: 1.25rem; font-size: 11px; color: var(--text-dimmer); line-height: 1.7; }
    .footer-note button { color: #a5b4fc; font-weight: 700; font-size: 11px; background: none; border: none; cursor: pointer; font-family: var(--font); transition: color .18s; padding: 0; }
    .footer-note button:hover { color: #c7d2fe; text-decoration: underline; }

    /* ══ MODALS ══ */
    .overlay { display: none; position: fixed; inset: 0; z-index: 300; background: rgba(5,10,25,.7); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.25rem; }
    .overlay.open { display: flex; animation: overlayIn .2s ease; }
    @keyframes overlayIn { from { opacity:0; } to { opacity:1; } }
    .modal-box { background: var(--navy-card); border-radius: var(--r); border: 1px solid var(--border); box-shadow: 0 32px 80px rgba(0,0,0,.6); width: 100%; max-width: 560px; max-height: 90vh; display: flex; flex-direction: column; animation: modalUp .25s cubic-bezier(.34,1.4,.64,1); overflow: hidden; }
    @keyframes modalUp { from { opacity:0; transform:translateY(20px) scale(.97); } to { opacity:1; transform:none; } }
    .modal-head { flex-shrink: 0; padding: 18px 22px 16px; border-bottom: 1px solid var(--border2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .modal-head-left { display: flex; align-items: center; gap: 12px; }
    .modal-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .modal-icon.indigo { background: rgba(99,102,241,.15); color: #a5b4fc; border: 1px solid rgba(99,132,255,.2); }
    .modal-icon.purple { background: rgba(139,92,246,.12); color: #c4b5fd; border: 1px solid rgba(139,92,246,.2); }
    .modal-head-title { font-size: 15px; font-weight: 800; color: #fff; letter-spacing: -.02em; }
    .modal-head-sub   { font-size: 11px; color: var(--text-dimmer); font-weight: 500; margin-top: 1px; }
    .modal-close-btn { width: 32px; height: 32px; border-radius: 9px; background: rgba(255,255,255,.06); border: 1px solid var(--border); color: var(--text-dim); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; transition: all .15s; flex-shrink: 0; }
    .modal-close-btn:hover { background: rgba(239,68,68,.15); border-color: rgba(239,68,68,.3); color: #fca5a5; }
    .modal-prog-bar { height: 2px; background: rgba(255,255,255,.05); flex-shrink: 0; }
    .modal-prog-fill { height: 100%; width: 0%; border-radius: 0 2px 2px 0; transition: width .1s linear; }
    .fill-indigo { background: linear-gradient(90deg, var(--indigo-mid), #818cf8); }
    .fill-purple { background: linear-gradient(90deg, #7c3aed, #a78bfa); }
    .modal-body { flex: 1; overflow-y: auto; padding: 20px 22px; }
    .modal-body::-webkit-scrollbar { width: 4px; }
    .modal-body::-webkit-scrollbar-thumb { background: rgba(99,132,255,.2); border-radius: 4px; }
    .ts { margin-bottom: 1.5rem; }
    .ts:last-child { margin-bottom: 0; }
    .ts-head { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .ts-num { width: 24px; height: 24px; border-radius: 7px; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ts-num.in { background: rgba(99,102,241,.15); color: #a5b4fc; }
    .ts-num.pu { background: rgba(139,92,246,.12); color: #c4b5fd; }
    .ts h3 { font-size: 13px; font-weight: 800; color: #fff; }
    .ts p, .ts li { font-size: 13px; color: var(--text-dim); line-height: 1.7; font-weight: 500; }
    .ts ul { margin-top: 6px; padding-left: 0; list-style: none; display: flex; flex-direction: column; gap: 4px; }
    .ts li { display: flex; align-items: flex-start; gap: 7px; }
    .ts li::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; margin-top: 8px; }
    .li-in::before { background: #6366f1; }
    .li-pu::before { background: #8b5cf6; }
    .ts-hl { border-radius: 10px; padding: 10px 13px; margin-top: 10px; font-size: 12px; font-weight: 600; line-height: 1.6; }
    .hl-in { background: rgba(99,102,241,.1); border: 1px solid rgba(99,132,255,.2); color: #a5b4fc; }
    .hl-pu { background: rgba(139,92,246,.1); border: 1px solid rgba(139,92,246,.2); color: #c4b5fd; }
    .modal-foot { flex-shrink: 0; padding: 14px 22px; border-top: 1px solid var(--border2); background: rgba(255,255,255,.02); }
    .modal-foot-note { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-dimmer); font-weight: 600; margin-bottom: 10px; }
    .must-hint { font-size: 11px; color: var(--amber); font-weight: 700; text-align: center; display: none; margin-bottom: 8px; }
    .must-hint.show { display: block; }
    .modal-foot-btns { display: flex; gap: 8px; }
    .btn-modal-cancel { flex: 1; height: 42px; background: rgba(255,255,255,.05); color: var(--text-dim); border: 1px solid var(--border); border-radius: var(--r-xs); font-family: var(--font); font-weight: 700; font-size: 13px; cursor: pointer; transition: all .15s; }
    .btn-modal-cancel:hover { background: rgba(255,255,255,.08); }
    .btn-modal-accept { flex: 2; height: 42px; color: #fff; border: none; border-radius: var(--r-xs); font-family: var(--font); font-weight: 800; font-size: 13px; cursor: pointer; transition: all .18s; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .btn-modal-accept.c-in { background: linear-gradient(135deg, var(--indigo-mid), var(--indigo)); box-shadow: 0 4px 14px rgba(67,56,202,.4); }
    .btn-modal-accept.c-in:hover { background: linear-gradient(135deg, #4f46e5, #3730a3); }
    .btn-modal-accept.c-pu { background: linear-gradient(135deg, #7c3aed, #6d28d9); box-shadow: 0 4px 14px rgba(124,58,237,.4); }
    .btn-modal-accept.c-pu:hover { background: linear-gradient(135deg, #6d28d9, #5b21b6); }
    .btn-modal-accept:disabled { opacity: .35; cursor: not-allowed; }

    /* ══ RESULT MODAL ══ */
    .overlay.result-overlay { z-index: 500; }
    .result-box { background: var(--navy-card); border-radius: var(--r); border: 1px solid var(--border); box-shadow: 0 32px 80px rgba(0,0,0,.6); width: 100%; max-width: 400px; padding: 2.25rem 2rem; text-align: center; animation: modalUp .3s cubic-bezier(.34,1.4,.64,1); }
    .result-icon-wrap { width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px; font-size: 2rem; position: relative; }
    .result-icon-wrap.success { background: var(--green-bg); color: var(--green); box-shadow: 0 12px 28px rgba(34,197,94,.2); }
    .result-icon-wrap.error   { background: var(--red-bg);   color: var(--red);   box-shadow: 0 12px 28px rgba(239,68,68,.2); }
    .result-icon-wrap.warning { background: var(--amber-bg); color: var(--amber); box-shadow: 0 12px 28px rgba(245,158,11,.2); }
    .result-icon-wrap::before { content: ''; position: absolute; inset: -5px; border-radius: 50%; border: 2px solid transparent; animation: ringRot 2.5s linear infinite; }
    .result-icon-wrap.success::before { border-top-color: rgba(34,197,94,.3); border-right-color: rgba(34,197,94,.15); }
    .result-icon-wrap.error::before   { border-top-color: rgba(239,68,68,.3); border-right-color: rgba(239,68,68,.15); }
    .result-icon-wrap.warning::before { border-top-color: rgba(245,158,11,.3); border-right-color: rgba(245,158,11,.15); }
    @keyframes ringRot { to { transform: rotate(360deg); } }
    .result-box h2 { font-size: 1.35rem; font-weight: 800; color: #fff; letter-spacing: -.03em; margin-bottom: 8px; }
    .result-box p  { font-size: 13px; color: var(--text-dim); font-weight: 500; line-height: 1.65; margin-bottom: 1.5rem; }
    .result-actions { display: flex; flex-direction: column; gap: 8px; }
    .btn-result { width: 100%; height: 46px; border: none; border-radius: var(--r-sm); font-family: var(--font); font-weight: 800; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .18s; color: #fff; }
    .btn-result.green  { background: linear-gradient(135deg, #22c55e, #16a34a); box-shadow: 0 4px 14px rgba(34,197,94,.3); }
    .btn-result.green:hover  { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(34,197,94,.4); }
    .btn-result.red    { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 14px rgba(239,68,68,.3); }
    .btn-result.red:hover    { transform: translateY(-1px); }
    .btn-result.blue   { background: linear-gradient(135deg, var(--indigo-mid), var(--indigo)); box-shadow: 0 4px 14px rgba(67,56,202,.3); }
    .btn-result.blue:hover   { transform: translateY(-1px); }
    .btn-result.amber  { background: linear-gradient(135deg, var(--amber), #d97706); box-shadow: 0 4px 14px rgba(245,158,11,.3); }
    .btn-result.amber:hover  { transform: translateY(-1px); }
    .countdown-row { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 12px; font-size: 12px; color: var(--text-dimmer); font-weight: 600; }
    .countdown-num { font-weight: 800; font-size: 13px; }
    .countdown-num.green { color: var(--green); }
    .countdown-num.amber { color: var(--amber); }

    @keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(8px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }
    .spin { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>
  <div class="register-wrapper">

    <!-- Back Button -->
    <a href="/" class="back-btn">
      <i class="fa-solid fa-arrow-left"></i>
      Back to Home
    </a>

    <div class="brand-bar">
      <span class="brand-tag">E-Learning Resource System</span>
      <div class="brand-name">my<em>Space.</em></div>
    </div>

    <div class="register-card">
      <div class="logo-hero">
        <div class="logo-ring">
          <img src="/assets/img/icon-192.png" alt="Brgy. F De Jesus">
        </div>
        <h1>Create an Account</h1>
        <p>Brgy. F De Jesus, Unisan Quezon</p>
      </div>

      <div class="welcome-pill">
        <div class="wp-icon"><i class="fa-solid fa-user-plus"></i></div>
        <div class="wp-text">
          <h3>E-Learning Resource Reservation System</h3>
          <p>Register to start booking learning resources</p>
        </div>
      </div>

      <!-- SK Notice -->
      <div class="sk-notice" id="skNotice">
        <i class="fa-solid fa-shield-halved"></i>
        <span><strong>SK Officer Registration:</strong> After verifying your email, your account requires <strong>approval by the Barangay Chairman</strong> before you can log in. You will be notified via email once a decision is made.</span>
      </div>

      <!-- Register form -->
      <form action="/register-action" method="post" id="regForm" novalidate>
        <?= csrf_field() ?>

        <div class="field-row">
          <div>
            <label class="field-lbl" for="first_name">First Name</label>
            <div style="position:relative">
              <i class="field-icon fa-regular fa-user"></i>
              <input type="text" id="first_name" name="first_name" class="field-input"
                     placeholder="Juan" value="<?= esc(old('first_name')) ?>" required autocomplete="given-name">
            </div>
          </div>
          <div>
            <label class="field-lbl" for="last_name">Last Name</label>
            <div style="position:relative">
              <i class="field-icon fa-regular fa-user"></i>
              <input type="text" id="last_name" name="last_name" class="field-input"
                     placeholder="Dela Cruz" value="<?= esc(old('last_name')) ?>" required autocomplete="family-name">
            </div>
          </div>
        </div>

        <div style="margin-bottom:1.1rem">
          <label class="field-lbl" for="email">Email Address</label>
          <div style="position:relative">
            <i class="field-icon fa-regular fa-envelope"></i>
            <input type="email" id="email" name="email" class="field-input"
                   placeholder="juan@example.com" value="<?= esc(old('email')) ?>" required autocomplete="email">
          </div>
        </div>

        <div style="margin-bottom:1.1rem">
          <label class="field-lbl" for="role">I am a&hellip;</label>
          <div style="position:relative" class="select-wrap">
            <i class="field-icon fa-solid fa-id-badge"></i>
            <select id="role" name="role" class="field-input" required onchange="onRoleChange(this.value)">
              <option value="" disabled <?= old('role') ? '' : 'selected' ?>>Select your role</option>
              <option value="resident" <?= old('role')==='resident' ? 'selected' : '' ?>>Resident</option>
              <option value="SK"       <?= old('role')==='SK'       ? 'selected' : '' ?>>SK Officer</option>
            </select>
          </div>
        </div>

        <div style="margin-bottom:.5rem">
          <label class="field-lbl" for="password">Password</label>
          <div style="position:relative">
            <i class="field-icon fa-solid fa-lock"></i>
            <input type="password" id="password" name="password" class="field-input pr"
                   placeholder="Create a strong password" required autocomplete="new-password">
            <button type="button" class="eye-btn" onclick="togglePwd('password','pwdEye')" aria-label="Toggle">
              <i class="fa-regular fa-eye" id="pwdEye"></i>
            </button>
          </div>
          <div class="str-row">
            <div class="str-seg" id="ss1"></div><div class="str-seg" id="ss2"></div>
            <div class="str-seg" id="ss3"></div><div class="str-seg" id="ss4"></div>
          </div>
          <div class="str-lbl" id="strLbl" style="color:var(--text-dimmer)"></div>
          <div class="pw-reqs" id="pwReqs">
            <div class="pw-reqs-title">Password must have:</div>
            <div class="req" id="r-len"><i class="fa-regular fa-circle"></i> At least 8 characters</div>
            <div class="req" id="r-up"> <i class="fa-regular fa-circle"></i> One uppercase letter</div>
            <div class="req" id="r-lo"> <i class="fa-regular fa-circle"></i> One lowercase letter</div>
            <div class="req" id="r-num"><i class="fa-regular fa-circle"></i> One number</div>
            <div class="req" id="r-sp"> <i class="fa-regular fa-circle"></i> One special character</div>
          </div>
        </div>

        <div style="margin-bottom:1rem">
          <label class="field-lbl" for="confirm_password">Confirm Password</label>
          <div style="position:relative">
            <i class="field-icon fa-solid fa-lock"></i>
            <input type="password" id="confirm_password" name="confirm_password" class="field-input pr"
                   placeholder="Re-enter your password" required autocomplete="new-password">
            <button type="button" class="eye-btn" onclick="togglePwd('confirm_password','cpwdEye')" aria-label="Toggle">
              <i class="fa-regular fa-eye" id="cpwdEye"></i>
            </button>
          </div>
          <div class="pw-match" id="pwMatch"></div>
        </div>

        <label class="check-wrap">
          <input type="checkbox" name="terms" id="termsChk" class="check-input" required>
          <span class="check-lbl">
            I agree to the
            <a href="#" onclick="openModalT('terms'); return false;">Terms of Service</a>
            and
            <a href="#" onclick="openModalT('privacy'); return false;">Privacy Policy</a>
          </span>
        </label>

        <button type="submit" class="btn-submit" id="submitBtn">
          <i class="fa-solid fa-user-plus" style="font-size:14px"></i>
          Create My Account
        </button>

        <div class="divider"><span>or</span></div>

        <div class="login-row">
          <span>Already have an account?</span>
          <a href="/login"> Sign In</a>
        </div>
      </form>

      <div class="footer-note">
        By registering, you agree to our
        <button onclick="openModalT('terms')">Terms of Service</button>
        and
        <button onclick="openModalT('privacy')">Privacy Policy</button>
      </div>
    </div>
  </div>

  <!-- RESULT MODAL -->
  <div class="overlay result-overlay" id="resultOverlay">
    <div class="result-box">
      <div class="result-icon-wrap" id="resultIconWrap"><i id="resultIconEl"></i></div>
      <h2 id="resultTitle"></h2>
      <p  id="resultMsg"></p>
      <div class="result-actions" id="resultActions"></div>
      <div class="countdown-row" id="resultCountdown" style="display:none">
        <i class="fa-solid fa-clock"></i>
        <span>Redirecting in <span class="countdown-num" id="cdNum">5</span>s…</span>
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
        <div class="ts"><div class="ts-head"><div class="ts-num in">03</div><h3>SK Officer Accounts</h3></div><p>SK Officer accounts require email verification followed by explicit approval from the Barangay Chairman. Registration alone does not grant portal access.</p><div class="ts-hl hl-in"><i class="fa-solid fa-shield-halved" style="margin-right:5px"></i>You will be notified via email once your account has been reviewed.</div></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">04</div><h3>Reservation Policy</h3></div><p>All reservations are subject to approval by authorized SK personnel or administrators.</p><ul><li class="li-in">Reservations are not confirmed until officially approved.</li><li class="li-in">You must present your e-ticket QR code upon arrival.</li><li class="li-in">Failure to appear within 15 minutes may result in cancellation.</li><li class="li-in">Misuse of reserved resources may result in account suspension.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">05</div><h3>Responsible Use of Resources</h3></div><ul><li class="li-in">Treat all equipment with care and report any damage immediately.</li><li class="li-in">Not install unauthorized software or modify system settings.</li><li class="li-in">Use resources solely for educational and approved purposes.</li><li class="li-in">Leave the workstation clean and in its original condition after use.</li></ul></div>
        <div class="ts"><div class="ts-head"><div class="ts-num in">06</div><h3>Amendments</h3></div><p>These terms may be updated from time to time. Continued use of the system after changes constitutes your acceptance.</p><p style="margin-top:6px;font-size:11px;color:var(--text-dimmer);font-weight:600">Last updated: <?= date('F j, Y') ?></p></div>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-note"><i class="fa-solid fa-eye" style="color:var(--text-dimmer)"></i><span id="termsNote">Scroll through all sections to enable acceptance.</span></div>
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
        <div class="ts"><div class="ts-head"><div class="ts-num pu">09</div><h3>Changes to This Policy</h3></div><p>We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements.</p><p style="margin-top:6px;font-size:11px;color:var(--text-dimmer);font-weight:600">Last updated: <?= date('F j, Y') ?></p></div>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-note"><i class="fa-solid fa-eye" style="color:var(--text-dimmer)"></i><span id="privacyNote">Scroll through all sections to enable acceptance.</span></div>
        <div class="must-hint" id="privacyMust"><i class="fa-solid fa-arrow-down" style="margin-right:4px"></i> Please scroll to the bottom to accept.</div>
        <div class="modal-foot-btns">
          <button class="btn-modal-cancel" onclick="closeModalT('privacy')">Close</button>
          <button class="btn-modal-accept c-pu" id="privacyAccept" onclick="acceptT('privacy')" disabled><i class="fa-solid fa-shield-halved"></i> I Acknowledge This Policy</button>
        </div>
      </div>
    </div>
  </div>

<script>
const FLASH_ERROR   = <?= json_encode(session()->getFlashdata('error'))   ?>;
const FLASH_SUCCESS = <?= json_encode(session()->getFlashdata('success')) ?>;
const FLASH_INFO    = <?= json_encode(session()->getFlashdata('info'))    ?>;

function onRoleChange(v) { document.getElementById('skNotice').classList.toggle('show', v === 'SK'); }
(function(){ const r = document.getElementById('role'); if(r.value) onRoleChange(r.value); })();

function togglePwd(id, iconId) {
  const i = document.getElementById(id), ic = document.getElementById(iconId);
  i.type = i.type === 'password' ? 'text' : 'password';
  ic.className = i.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
}

const RULES = {
  'r-len': { test: p => p.length >= 8,           label: 'At least 8 characters' },
  'r-up':  { test: p => /[A-Z]/.test(p),         label: 'One uppercase letter' },
  'r-lo':  { test: p => /[a-z]/.test(p),         label: 'One lowercase letter' },
  'r-num': { test: p => /\d/.test(p),             label: 'One number' },
  'r-sp':  { test: p => /[^A-Za-z0-9]/.test(p),  label: 'One special character' },
};
const STRENGTH_C  = ['','#ef4444','#f97316','#eab308','#22c55e'];
const STRENGTH_L  = ['','Weak','Fair','Good','Strong'];
const STRENGTH_LC = ['','#fca5a5','#fdba74','#fde047','#86efac'];

document.getElementById('password').addEventListener('focus', function(){ document.getElementById('pwReqs').style.display = 'block'; });
document.getElementById('password').addEventListener('input', function(){ updateStrength(this.value); updateReqs(this.value); checkMatch(); });
document.getElementById('confirm_password').addEventListener('input', checkMatch);

function updateStrength(p) {
  let sc = 0;
  if(p.length>=8)sc++;if(/[A-Z]/.test(p))sc++;if(/\d/.test(p))sc++;if(/[^A-Za-z0-9]/.test(p))sc++;
  [1,2,3,4].forEach(j => document.getElementById('ss'+j).style.background = j<=sc ? STRENGTH_C[sc] : 'var(--border)');
  const lb = document.getElementById('strLbl');
  lb.textContent = p ? STRENGTH_L[sc] : ''; lb.style.color = p ? STRENGTH_LC[sc] : 'var(--text-dimmer)';
}
function updateReqs(p) {
  for(const [id, rule] of Object.entries(RULES)){
    const el = document.getElementById(id); const ok = rule.test(p);
    el.className = 'req' + (ok ? ' met' : '');
    el.innerHTML = (ok ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-regular fa-circle"></i>') + ' ' + rule.label;
  }
}
function checkMatch() {
  const p = document.getElementById('password').value;
  const c = document.getElementById('confirm_password').value;
  const m = document.getElementById('pwMatch');
  if(!c){ m.className='pw-match'; return; }
  if(p !== c){ m.className='pw-match bad'; m.innerHTML='<i class="fa-solid fa-circle-xmark"></i> Passwords do not match'; }
  else { m.className='pw-match good'; m.innerHTML='<i class="fa-solid fa-circle-check"></i> Passwords match'; }
}

document.getElementById('regForm').addEventListener('submit', function(e){
  const fn    = document.getElementById('first_name').value.trim();
  const ln    = document.getElementById('last_name').value.trim();
  const email = document.getElementById('email').value.trim();
  const role  = document.getElementById('role').value;
  const pwd   = document.getElementById('password').value;
  const cpwd  = document.getElementById('confirm_password').value;
  const terms = document.getElementById('termsChk').checked;
  if(!fn||!ln||!email||!role||!pwd||!cpwd){ e.preventDefault(); showResult('error','Missing Information','Please fill in all required fields before creating your account.',[{label:'Go Back & Fix', icon:'fa-arrow-left', cls:'red', action:'close'}]); return; }
  if(!terms){ e.preventDefault(); showResult('error','Terms Not Accepted','You must agree to the Terms of Service and Privacy Policy.',[{label:'Read Terms', icon:'fa-file-contract', cls:'blue', action:()=>{closeResult();openModalT('terms');}},{label:'Go Back', icon:'fa-arrow-left', cls:'red', action:'close'}]); return; }
  if(pwd !== cpwd){ e.preventDefault(); showResult('error',"Passwords Don't Match","The passwords you entered don't match. Please re-enter them.",[{label:'Go Back & Fix', icon:'fa-arrow-left', cls:'red', action:'close'}]); return; }
  if(pwd.length < 8){ e.preventDefault(); showResult('error','Password Too Short','Your password must be at least 8 characters long.',[{label:'Go Back & Fix', icon:'fa-arrow-left', cls:'red', action:'close'}]); return; }
  const btn = document.getElementById('submitBtn');
  btn.disabled = true; btn.innerHTML = '<div class="spin"></div> Creating account…';
});

let cdTimer = null;
function showResult(type, title, msg, actions, countdown){
  const ov   = document.getElementById('resultOverlay');
  const wrap = document.getElementById('resultIconWrap');
  const ic   = document.getElementById('resultIconEl');
  const icons = {success:'fa-circle-check', error:'fa-circle-xmark', warning:'fa-triangle-exclamation'};
  wrap.className = 'result-icon-wrap ' + type;
  ic.className   = 'fa-solid ' + (icons[type]||'fa-circle-info');
  document.getElementById('resultTitle').textContent = title;
  document.getElementById('resultMsg').textContent   = msg;
  const actEl = document.getElementById('resultActions');
  actEl.innerHTML = '';
  (actions||[]).forEach(a=>{
    const btn = document.createElement('button');
    btn.className = 'btn-result ' + (a.cls||'blue');
    btn.innerHTML = <i class="fa-solid ${a.icon||'fa-check'}"></i> ${a.label};
    btn.onclick = ()=>{ if(a.action==='close')closeResult(); else if(a.action==='login')window.location.href='/login'; else if(typeof a.action==='function')a.action(); };
    actEl.appendChild(btn);
  });
  const cdWrap = document.getElementById('resultCountdown');
  const cdNum  = document.getElementById('cdNum');
  clearInterval(cdTimer);
  if(countdown){ cdWrap.style.display='flex'; cdNum.textContent=countdown; cdNum.className='countdown-num '+(type==='success'?'green':'amber'); let left=countdown; cdTimer=setInterval(()=>{ left--; cdNum.textContent=left; if(left<=0){clearInterval(cdTimer);window.location.href='/login';} },1000); }
  else { cdWrap.style.display='none'; }
  ov.classList.add('open'); document.body.style.overflow='hidden';
}
function closeResult(){ document.getElementById('resultOverlay').classList.remove('open'); document.body.style.overflow=''; clearInterval(cdTimer); }
document.getElementById('resultOverlay').addEventListener('click',function(e){if(e.target===this)closeResult();});

window.addEventListener('DOMContentLoaded', ()=>{
  if(FLASH_SUCCESS){
    const isSK = FLASH_SUCCESS.toLowerCase().includes('pending')||FLASH_SUCCESS.toLowerCase().includes('chairman');
    if(isSK){ showResult('warning','Account Created — Pending Approval', FLASH_SUCCESS,[{label:'Go to Login', icon:'fa-right-to-bracket', cls:'amber', action:'login'}], 8); }
    else { showResult('success','Account Created!', FLASH_SUCCESS,[{label:'Sign In Now', icon:'fa-right-to-bracket', cls:'green', action:'login'}], 5); }
  } else if(FLASH_INFO){ showResult('warning','One More Step', FLASH_INFO,[{label:'Go to Login', icon:'fa-right-to-bracket', cls:'amber', action:'login'}], 8); }
  else if(FLASH_ERROR){ showResult('error','Registration Failed', FLASH_ERROR,[{label:'Try Again', icon:'fa-rotate-left', cls:'red', action:'close'}]); }
});

const mRead={terms:false,privacy:false};
function openModalT(t){ const ov=document.getElementById(t+'Overlay'); const body=document.getElementById(t+'Body'); ov.classList.add('open'); document.body.style.overflow='hidden'; body.scrollTop=0; updProg(t); }
function closeModalT(t){ document.getElementById(t+'Overlay').classList.remove('open'); document.body.style.overflow=''; }
function acceptT(t){ if(!mRead[t]){document.getElementById(t+'Must').classList.add('show');return;} closeModalT(t); if(t==='terms')document.getElementById('termsChk').checked=true; showToast(t==='terms'?'Terms of Service accepted.':'Privacy Policy acknowledged.','success'); }
function updProg(t){
  const b=document.getElementById(t+'Body'); const tot=b.scrollHeight-b.clientHeight; const pct=tot>0?Math.min(100,Math.round(b.scrollTop/tot*100)):100;
  document.getElementById(t+'Prog').style.width=pct+'%';
  if(pct>=95&&!mRead[t]){ mRead[t]=true; const btn=document.getElementById(t+'Accept'); const note=document.getElementById(t+'Note'); btn.disabled=false; note.textContent='You have reviewed the policy. You may now accept.'; note.style.color=t==='terms'?'#a5b4fc':'#c4b5fd'; document.getElementById(t+'Must').classList.remove('show'); }
}
['terms','privacy'].forEach(t=>{
  document.getElementById(t+'Body').addEventListener('scroll',()=>updProg(t));
  document.getElementById(t+'Overlay').addEventListener('click',function(e){if(e.target===this)closeModalT(t);});
});
document.addEventListener('keydown',e=>{ if(e.key==='Escape'){closeResult();['terms','privacy'].forEach(closeModalT);} });

function showToast(msg,type){
  const ex=document.getElementById('_toast');if(ex)ex.remove();
  const c={ success:{bg:'rgba(34,197,94,.12)',br:'rgba(34,197,94,.25)',tx:'#86efac',ic:'fa-circle-check'}, error:{bg:'rgba(239,68,68,.12)',br:'rgba(239,68,68,.25)',tx:'#fca5a5',ic:'fa-circle-exclamation'}, info:{bg:'rgba(99,102,241,.12)',br:'rgba(99,132,255,.25)',tx:'#a5b4fc',ic:'fa-circle-info'} }[type]||{bg:'rgba(99,102,241,.12)',br:'rgba(99,132,255,.25)',tx:'#a5b4fc',ic:'fa-circle-info'};
  const t=document.createElement('div'); t.id='_toast';
  t.style.cssText=`position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);background:${c.bg};border:1px solid ${c.br};color:${c.tx};padding:11px 18px;border-radius:12px;font-family:var(--font);font-size:13px;font-weight:600;box-shadow:0 8px 28px rgba(0,0,0,.4);z-index:9999;white-space:nowrap;display:flex;align-items:center;gap:8px;animation:toastIn .3s ease;backdrop-filter:blur(12px)`;
  t.innerHTML=`<i class="fa-solid ${c.ic}"></i>${msg}`; document.body.appendChild(t);
  setTimeout(()=>{t.style.transition='opacity .4s';t.style.opacity='0';setTimeout(()=>t.remove(),400);},type==='success'?4500:3500);
}
if('serviceWorker' in navigator){ window.addEventListener('load',()=>{ navigator.serviceWorker.register('/sw.js',{scope:'/'}).catch(()=>{}); }); }
</script>
</body>
</html>